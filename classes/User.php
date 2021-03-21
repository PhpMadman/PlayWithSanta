<?php
class User {

	public $db = null;

	/* TODO
	 check if username exists
	 check if password matches
	 set cookie & session
	 destroy session
	 add user to db
	*/
	/*
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
	*/
	public function __construct() {
		if ( $this->db === null ) {
			$db = Db::DbLink();
			$this->db = $db;
		}
	}

	public function userExists( $username, $password = null ) {
		echo $username;
		$stmnt = $this->db->prepare( 'SELECT id FROM Players WHERE username = ?' );
		$stmnt->bind_param('s', $username);
		$stmnt->execute();
		$stmnt->store_result();
		//print_r($stmnt);
		if ($stmnt->num_rows > 0) {
			$stmnt->bind_result($id, $storedPass);
			$stmnt->fetch();
			$stmnt->close();
			if (isset($password) && password_verify($password, $storedPass)) {
				return $id; // returns id if user exists and password is correct
			} else {
				return true; // return true if user exist, but bad password
			}
		}
		$stmnt->close();
		return false;
	}
}