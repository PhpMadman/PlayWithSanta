<?php

if ( isset( $_POST['username'] ) && isset( $_POST['password'] ) ) {
	$user = new User();
	$user_id = $user->userExists( $_POST['username'], $_POST['password'] );
	if ( isset( $user_id ) && $user_id > 0) {
		// BLOCK! User exists
	} else {
		// User did not exist.
		// We are good to go and create it
		//$signin_failed = true;
	}
}
/*
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
*/
?>
<div class="text-center">
	<form class="form-signup" method="post">
		<h1 class="h3 mb-3 font-weight-normal">Please sign up</h1>
		<label for="inputUsername" class="sr-only">Username</label>
		<input type="text" id="inputUsername" name="username" class="form-control" placeholder="Username" required>
		<label for="inputPassword" class="sr-only">Password</label>
		<input type="password" id="inputPassword" name="password" class="form-control" placeholder="Password" required>
		<label for="inputPasswordRepeat" class="sr-only">Password again</label>
		<input type="password" id="inputPasswordRepeat" name="password-repeat" class="form-control" placeholder="Password again" required>
		<button class="btn btn-lg btn-primary-pws btn-block" type="submit">Sign up</button>
	</form>
</div>