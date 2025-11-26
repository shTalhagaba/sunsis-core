<?php

/**
 * Keep the session alive.
 * Return "1" to the browser on succes
 * Return "0" to the browser on failure (timeout or unauthenticated/new-session)
 */

define('WEBROOT', __DIR__ . '/');
require(WEBROOT . 'lib/config.php');

// Start the session if it has not already been started
// (it should have been started in config.php)
if (!isset($_SESSION)) {
	session_start();
}

define("CODE_SUCCESS", "1");
define("CODE_FAILURE", "0");

header("Content-Type: text/plain");

// The login form never times out
if (strpos($_SERVER['HTTP_REFERER'], '_action=login')) {
	die(CODE_SUCCESS);
}

// Check if the user's session has been destroyed
// since they last contacted the server. Look for
// the user object.
if (!isset($_SESSION['user'])) {
	die(CODE_FAILURE);
}

$options = [];
// If RDS SSL CA is provided in env, add SSL options
$sslCa = getenv('PERSPECTIVE_DB_SSL_CA');
if ($sslCa && file_exists($sslCa)) {
	$options = [
		PDO::MYSQL_ATTR_SSL_CA => $sslCa,
		PDO::MYSQL_ATTR_SSL_VERIFY_SERVER_CERT => false,
	];
}

// Existing session found
$link = new PDO("mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";port=" . DB_PORT, DB_USER, DB_PASSWORD, $options);
$system_timeout = SystemConfig::getEntityValue($link, 'system_timeout');
if (isset($system_timeout) && is_numeric($system_timeout)) {
	if (isset($_SESSION['session_time']) && (time() - $_SESSION['session_time'] > $system_timeout)) {
		// session time exceeded
		session_destroy();
		die(CODE_FAILURE);
	} else {
		// update the session_time
		$_SESSION['session_time'] = time();
		die(CODE_SUCCESS);
	}
} else {
	die(CODE_SUCCESS); // no system_timeout config variable
}
