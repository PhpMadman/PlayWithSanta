<?php

//phpcs:disable
// Debug code
ini_set( 'display_errors', 1 );
ini_set( 'display_startup_errors', 1 );
error_reporting( E_ALL );
//phpcs:enable

	require_once 'conf/config.php';
require_once 'autoloader.php';

session_start(); // Start session so we can handle logged in users

// Render all diffrent templates
require_once 'templates/main.php';
