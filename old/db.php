<?php

class Db {

	//phpcs:ignore
	public static function DbLink()
	{
		$db = new mysqli( DB_URL, DB_USER, DB_PASS, DB_NAME );
		return $db;
	}
}
