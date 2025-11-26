<?php

$position = strpos(__DIR__, 'htdocs');

$sunesisLibraryPath = substr(__DIR__, 0, $position + strlen('htdocs'));

// Database connection constants
define('DB_NAME', isset($_SERVER['PERSPECTIVE_DB_NAME'])?$_SERVER['PERSPECTIVE_DB_NAME']:'');
define('DB_USER', isset($_SERVER['PERSPECTIVE_DB_USER'])?$_SERVER['PERSPECTIVE_DB_USER']:ini_get('mysqli.default_user'));
define('DB_PASSWORD', isset($_SERVER['PERSPECTIVE_DB_PASSWORD'])?$_SERVER['PERSPECTIVE_DB_PASSWORD']:ini_get('mysqli.default_pw'));
define('DB_HOST', isset($_SERVER['PERSPECTIVE_DB_HOST'])?$_SERVER['PERSPECTIVE_DB_HOST']:ini_get('mysqli.default_host'));
define('DB_PORT', isset($_SERVER['PERSPECTIVE_DB_PORT'])?$_SERVER['PERSPECTIVE_DB_PORT']:ini_get('mysqli.default_port'));
define('SOURCE_LOCAL', in_array($_SERVER['REMOTE_ADDR'], array('127.0.0.1', '::1')));

if(DB_NAME == '')
{
	die("Missing CGI environment variable: PERSPECTIVE_DB_NAME. Can go no further without this. Check your web server configuration.");
}

include 'HttpRequest.php';
include 'ErrorHandler.php';
include 'Validator.php';

spl_autoload_register(function($class_name) use ($sunesisLibraryPath) {
    include $sunesisLibraryPath . '/lib/' . $class_name . '.php';
});

set_error_handler("ErrorHandler::handleError");
set_exception_handler('ErrorHandler::handleException');

