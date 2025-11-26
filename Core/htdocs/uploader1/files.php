<?php
/*
 * jQuery File Upload Plugin PHP Example 5.14
 * https://github.com/blueimp/jQuery-File-Upload
 *
 * Copyright 2010, Sebastian Tschan
 * https://blueimp.net
 *
 * Licensed under the MIT license:
 * http://www.opensource.org/licenses/MIT
 */

error_reporting(E_ALL | E_STRICT);
define('DB_NAME', isset($_SERVER['PERSPECTIVE_DB_NAME'])?$_SERVER['PERSPECTIVE_DB_NAME']:'');
// Data directory
if (isset($_SERVER['PERSPECTIVE_DATA_ROOT'])) {
	define('DATA_ROOT', rtrim($_SERVER['PERSPECTIVE_DATA_ROOT'], '\\/'));
} else if(PHP_OS == "WINNT") {
	define('DATA_ROOT', "C:/Apps/sunesis-data");
} else {
	define('DATA_ROOT', "/srv/www/am_common_data");
}

require('UploadHandler.php');
$upload_handler = new UploadHandler();
?>