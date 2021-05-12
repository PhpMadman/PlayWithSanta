<?php
/*
    The Lua:
        A score table.
        Insulting table when reaching X score
        !throwsanta - Throws santa in to a big/medium/small hole, gives diffrent score depending on size. Big is small score
        !runover - Try to run over santa. Santa might kill you. Gives score depending who kills who
        !killsanta - Almost same as runover, just more of gore / kill
        !killuser - Try to use santa to kill victim. User or Victim might score / get killed
        All commands show a funny text about how Santa got thrown / killed
        !myscore - Show your score
        !scoreboard - Shows top 10 scores

    The Php Beta
        DB
            Hit, Miss, kill etc msg tabels
x            A user table to store username, pass, score
                Deaths / Kills ?
        Buttons
x            Throwsanta
            Killsanta / runover button, rewrite? and merge in to ThrowSanta
            Killuser - Use Userid? to select victim
x            Login
x               Once logged in, personal score should always be shown
        Layout
x            2 column.
x            Left is play area
            Right is the Top 10? players
        Extra
            Add throwspeed, splatter damage on gory kills
            Random NPC to acidently kill or get killed by.
        
        Mobile friendly
        
    
*/
?>
<?php

    // DEBUG CODE
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);

    require_once 'db.php';
    require_once '../conf/config.php';

    session_start();
    
    /** POST Handling**/
   
    if (isset($_POST["throw"])) {
        if (isset($_POST['vicuser']) && !empty($_POST['vicuser'])) {
            echo "Santa was thrown at " . $_POST['vicuser']; // DEBUG
            /* TODO
            Mysql vicuser msg
            Fetch vicuser name
            Update FixMsg function
            Award points if logged in
            2.0: Kill + Deaths to players
                User id of last kill / killer ?
                    Allows for starting wars
            2.0: Add NPC's later
            */
        } else {
            list($msg,$debugStats) = throwSanta();
        }
    }
    
    if (isset($_POST['sign-out'])) {
        session_destroy();
        $_SESSION = array();
    }
    
    if (isset($_POST['sign-in'])) {
        if (!empty($_POST['username']) && !empty($_POST['password'])) {
            $db = Db::DbLink();
            $db = Db::DbLink();
            // Check if a player with that name exists
            $userId = UserCheck($db, $_POST['username'], $_POST['password']);
            if (isset($userId) && $userId > 0) {
                $_SESSION['LoggedIn'] = true;
                $_SESSION['username'] = $_POST['username'];
                $_SESSION['id'] = $userId;
                $arrCookie = array (
                    'expires' => strtotime( '+4 hours' ),
                    'path' => '/',
                    'secure' => true,
                    'samesite' => 'None'
                );
                setcookie("pws", $_SESSION['username'], $arrCookie);
            } else {
                $errorSign = "That info is incorrect";
            }
        }
    }
    
    if (isset($_POST['sign-up'])) {
        if (!empty($_POST['username']) && !empty($_POST['password'])) {
            $db = Db::DbLink();
            // Check if a player with that name exists
            if (!preg_match("/^[a-zA-Z0-9]*$/", $_POST['username'])) {
                $errorSign = "Due to lazy Dev's only a-Z and 0-9 is allowed chars";
            } else {
                $isUser = UserCheck($db, $_POST['username']);
                if ($isUser === false ) { // username free
                    $stmnt = $db->prepare("INSERT INTO Players(username, password) VALUES(?, ?)");
                    $stmnt->bind_param("ss", $username, $safeword);
                    $safeword = password_hash($_POST['password'], PASSWORD_DEFAULT);
                    $username = $_POST['username'];
                    $stmnt->execute();
                    $userId = $stmnt->insert_id;
                    $_SESSION['LoggedIn'] = true;
                    $_SESSION['username'] = $_POST['username'];
                    $_SESSION['id'] = $userId;
                    $arrCookie = array (
                        'expires' => strtotime( '+4 hours' ),
                        'path' => '/',
                        'secure' => true,
                        'samesite' => 'None'
                    );
                    setcookie("pws", $_SESSION['username'], $arrCookie);
                } else {
                    $errorSign = "You are not the only one";
                }
            }
        } else {
            $errorSign = "Please fill in the blanks...";
        }
    }
?>
<?php
    //--Functions--//
    
    function UserCheck($db, $username, $pass = null) {
        $stmnt = $db->prepare('SELECT id, password FROM Players WHERE username = ?');
        $stmnt->bind_param('s', $username);
        $stmnt->execute();
        $stmnt->store_result();
        if ($stmnt->num_rows > 0) {
            $stmnt->bind_result($id, $storedPass);
            $stmnt->fetch();
            $stmnt->close();
            if (isset($pass) && password_verify($pass, $storedPass)) {
                return $id; // returns id if user exists and password is correct
            } else {
                return true; // return true if user exist, but bad password
            }
        }
        $stmnt->close();
        return false;
        /*
	https://codeshack.io/secure-login-system-php-mysql/
        */
    }
    
    function getScore($db, $id) {
        $stmnt = $db->prepare('SELECT score FROM Players WHERE id = ?');
        $stmnt->bind_param('s', $id);
        $stmnt->execute();
        $stmnt->store_result();
        $stmnt->bind_result($storedScore);
        $stmnt->fetch();
        return $storedScore;
    }
    
    function throwSanta() {
        $db = Db::DbLink();
        $username = "[GUEST] Random stray"; // TODO Replace with [GUEST] random names or logged in user name
        if (isset($_SESSION['LoggedIn'])) {
            $username = $_SESSION['username'];
        }
        
        $throw = rand(0,10); // Throw santa chance. 6-10 is a hit
        //$throw = 4; // DEBUG always miss/hit
        $debugStats = "[".$throw."]"; // DEBUG
        if ($throw > 5) {
            $debugStats .= ", HIT"; // DEBUG
            list($hole, $score) = getHole();
            $debugStats .= ", ".$hole; // DEBUG
            $debugStats .= ", ".$score; // DEBUG
            // Get hit messages and slect a random one
            $result = $db->query('SELECT msg,score FROM HitMsg');
            $HitMsg = array();
            while($row = mysqli_fetch_array($result))
            {
                $HitMsg[] = array(
                    'msg' => $row['msg'],
                    'score' => $row['score'],
                );
            }
            $result->close();
            $hitKey = array_rand($HitMsg,1);
            $msg = FixMsg($HitMsg[$hitKey]['msg'], $username, $hole, $HitMsg[$hitKey]['score']);
            if (isset($HitMsg[$hitKey]['score'])) { // if message contains extra points
                $score += $HitMsg[$hitKey]['score']; // add points to score
            }
            if (isset($_SESSION['LoggedIn']) && $score > 0) {
                // Get score
                //$storedScore = getScore($db, $_SESSION['id']);
                // Update score
                $stmnt = $db->prepare('UPDATE Players SET score = score + ? WHERE id = ?');
                $stmnt->bind_param('ii', $score, $_SESSION['id']);
                $stmnt->execute();
            }
            $debugStats .= ", ".$score; // DEBUG
            $msg .= "<br><strong>".$username."</strong> gets ".$score." points for this throw.";
            // Extra: Fake speed of throw. Score for splatter damage.
        } else {
            $result = $db->query('SELECT msg FROM MissMsg');
            $MissMsg = array();
            while($row = mysqli_fetch_array($result))
            {
                $MissMsg[] = array(
                    'msg' => $row['msg'],
                );
            }
            $result->close();
            $missKey = array_rand($MissMsg,1);
            list($hole, $score) = getHole();
            $msg = FixMsg($MissMsg[$missKey]['msg'], $username, $hole);
            //$msg = "MISS";
        }
        return array($msg, $debugStats);
    }
    
    function FixMsg($msg, $cururser, $hole = "", $score = "", $vicuser = "") {
        $msg = str_replace(array("[hole]", "[curuser]"),array($hole, '<strong>'.$cururser.'</strong>'), $msg); // Basic fixes of message
        $msg = str_replace("[points]", $score, $msg); // update message if user should get points
        // TODO add vicuser
        return $msg;
    }
    
    function getHole()
    {
        $holeSize = lcg_value(); // float between 0 and 1
        if ($holeSize < 0.50) { // 50% change to be big
            $hole = "big";
            $score = rand(1,5);
        } elseif ($holeSize >= 0.50 && $holeSize <= 0.80) { // 30% change to be medium
            $hole = "medium";
            $score = rand(6,10);
        } elseif ($holeSize >= 0.80 && $holeSize <= 1.0) { // 20% change to be small
            $hole = "small";
            $score = rand(11,15);
        }
        return array($hole, $score);
    }
    
?>
<html>
    <head>
        <title>PWS - Play With Santa 2.0.5</title>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">  
        <link rel="stylesheet" href="styles.css">
    </head>
    <body>
        From the deep deep hole, that is my hardrives subfolders, a legend rises. A legend, that disappeared over 10 <?php // Y - 2009 ?> years ago. But now has returned. I give you
        <h1>PWS - Play With Santa!</h1> <!-- TODO Center this text -->
        <br><br>
        GDPR - Blah blah blah, Login requires cookie. Register / Login means you accept cookies. I like Singoalla with raspberry.
        <br>
        <a href="">Dummy Link</a><br><br>
        <div class="wrapper">
            <div class="col_left">
                <div class="game_box">
                    <form name="the_game" method="post">
                        <button type="submit" name="throw">Try to throw santa</button><br>
                        <span class="note">Lazy Dev again, Enter a id of a user, and try to kill them using Santa</span><br>
                        <input type="text" name="vicuser"></input>
                    </form>
                    <p>
                    <?php
                        if (isset($msg)) {
                            echo $msg;
                        }
                        if (isset($debugStats)) {
                            echo "<br>".$debugStats;
                        }
                    ?>
                    </p>
                </div>
                <div class="userarea">
                <?php
                    if (isset($_SESSION['username'])) {
                        echo "Hello ". $_SESSION['username']."<br>";
                        $db = Db::DbLink();
                        $score = getScore($db, $_SESSION['id']);
                        echo "Your current score is ".$score.".";
                        ?>
                        <div class="sign-out">
                            <form name="sign-out" method="post">
                                <button type="submit" name="sign-out">Log out</button>
                            </form>
                        </div>
                        <?php
                    } else {
                ?>
                    <form name="sign-in" method="post">
                        Username: <input type="text" name='username' placeholder="Yeah, you know..." value="<? echo (!empty($_POST['username']) ? $_POST['username'] : "") ?>"><br>
                        Password: <input type="password" name="password" placeholder="The magic word"><br>
                        <button type="submit" name="sign-in">Sign in</button>
                        <button type="submit" name="sign-up">Sign up</button>
                        <br><br>
                        <?php
                        if (isset($errorSign)) {
                            echo $errorSign;
                        }
                        ?>
                    </form>
                <?php
                    }
                ?>
                </div>
            </div>
            <div class="col_right">
                <table style="width:100%; text-align: center;">
                <tr>
                    <th>Score</th>
                    <th>Username</th>
                    <th>User ID</th>
                </tr>
                <?php
                    /* TODO
                        Get score, id, username
                        Sort mysql by score, id
                    */
                    $db = Db::DbLink();
                    // TODO Update min req? Set to like 50 or something?
                    $stmnt = $db->prepare('SELECT score, username, id FROM Players WHERE score > 0 ORDER BY score DESC,id ASC LIMIT 10');
                    $stmnt->execute();
                    $result = $stmnt->get_result();
                    while ($row = $result->fetch_assoc()) {
                            //echo $row['score']." | ".$row['username']." | ".$row["id"]."<br>";
                            echo "<tr><td>".$row['score']."</td><td>".$row['username']."</td><td>".$row["id"]."</td></tr>\n";
                    }
                ?>
                </table>
            </div>
            <div class="clear"></div>
        </div>
        <footer class="footer">
            <hr> <!-- TODO Remove hr and use css to style footer -->
            PloppyLeft - Copy as much as posible - Madman (<?php echo '2006 - '.date('Y'); ?>)
        </footer>
    </body>
</html>
