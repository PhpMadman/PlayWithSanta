<?php

class User {

	public $db = null;

	public function __construct() {
		if ( $this->db === null ) {
			$db = Db::dbLink();
			$this->db = $db;
		}
	}

	public function userExists( $username, $password = null ) {
		$stmnt = $this->db->prepare( 'SELECT id, password FROM Players WHERE username = ?' );
		$stmnt->bind_param( 's', $username );
		$stmnt->execute();
		$stmnt->store_result();
		if ($stmnt->num_rows > 0) {
			$stmnt->bind_result( $id, $storedPass );
			$stmnt->fetch();
			$stmnt->close();
			if ( isset( $password ) && password_verify( $password, $storedPass )) {
				return $id; // returns id if user exists and password is correct
			} else {
				return true; // return true if user exist, but bad password
			}
		}
		$stmnt->close();
		return false;
	}

	public function addUser( $username, $password ) {
		// We should probarly do something cool here
	}

	public function loginUser( $user_id ) {
		$_SESSION['LoggedIn'] = true;
		$_SESSION['id'] = $user_id;
		$arrCookie = array(
			'expires' => strtotime( '+4 hours' ),
			'path' => '/',
			'secure' => true,
			'samesite' => 'None',
		);
		setcookie( 'pws', $user_id, $arrCookie );
		return true;
	}
}
