<?php
// Each directory has a 'config.php' that defines the constant WEBROOT
// and which must be included *before* this file
if (!defined('WEBROOT')) {
	define('WEBROOT', dirname(__DIR__).'/');
}

// Default framework include path
set_include_path(WEBROOT.'actions'
	.PATH_SEPARATOR.WEBROOT.'templates'
	.PATH_SEPARATOR.get_include_path());

// Class autoloading
spl_autoload_register(function($class_name) {
	@include WEBROOT . 'lib/' . $class_name . '.php'; // Sunesis library
});
if ((@include 'Zend/Loader/Autoloader.php')) {
	Zend_Loader_Autoloader::getInstance(); // Zend library (automatically registers autoloader on initialisation)
}
spl_autoload_register(function($class_name) {
	@include WEBROOT . 'lib/tracking/' . $class_name . '.php'; // Sunesis library
});
spl_autoload_register(function($class_name) {
	@include WEBROOT . 'lib/onboarding/' . $class_name . '.php'; // Sunesis library
});
spl_autoload_register(function($class_name) {
	@include WEBROOT . 'lib/eportfolio/' . $class_name . '.php'; // Sunesis library
});
spl_autoload_register(function($class_name) {
	@include WEBROOT . 'lib/CourseViews/' . $class_name . '.php'; // Sunesis library
});
spl_autoload_register(function($class_name) {
	@include WEBROOT . 'lib/CRM/' . $class_name . '.php'; 
});
spl_autoload_register(function($class_name) {
	@include WEBROOT . 'lib/choc/' . $class_name . '.php'; // Sunesis library
});
spl_autoload_register(function($class_name) {
	@include WEBROOT . 'lib/onefile/' . $class_name . '.php'; 
});
spl_autoload_register(function($class_name) {
	@include WEBROOT . 'lib/bootcamp/' . $class_name . '.php'; 
});

// Error strings
ini_set('error_prepend_string', '<php:error xmlns:php="http://php.net">');
ini_set('error_append_string', '</php:error>');

// Start the session if it has not already been started
if (!isset($_SESSION)) {
	session_start();
}

ini_set('default_charset', 'iso-8859-1');

define('IS_AJAX', (array_key_exists("HTTP_ACCEPT", $_SERVER) && $_SERVER['HTTP_ACCEPT'] == "application/ajax")
	|| (array_key_exists("HTTP_X_REQUESTED_WITH", $_SERVER) && $_SERVER['HTTP_X_REQUESTED_WITH'] == 'XMLHttpRequest') );
	
// location constants
define('SOURCE_BLYTHE_VALLEY', in_array($_SERVER['REMOTE_ADDR'], array('80.195.116.88','80.195.116.89','80.195.116.90','92.30.151.1','94.125.128.205','92.236.163.70', '185.39.250.38')));
define('SOURCE_LOCAL', isset($_SERVER['PERSPECTIVE_ENV']) ? !($_SERVER['PERSPECTIVE_ENV'] == 'production') : in_array($_SERVER['REMOTE_ADDR'], array('127.0.0.1', '::1')));
//define('SOURCE_LOCAL', false);
define('SOURCE_HOME', in_array($_SERVER['REMOTE_ADDR'], ['92.238.144.108', '185.39.248.38', '185.39.248.50']));
define('LIVE_SITE', strpos($_SERVER['SERVER_NAME'], '.sunesis.uk.net') !== FALSE);

// Database connection constants
define('DB_NAME', isset($_SERVER['PERSPECTIVE_DB_NAME'])?$_SERVER['PERSPECTIVE_DB_NAME']:'');
define('DB_USER', isset($_SERVER['PERSPECTIVE_DB_USER'])?$_SERVER['PERSPECTIVE_DB_USER']:ini_get('mysqli.default_user'));
define('DB_PASSWORD', isset($_SERVER['PERSPECTIVE_DB_PASSWORD'])?$_SERVER['PERSPECTIVE_DB_PASSWORD']:ini_get('mysqli.default_pw'));
define('DB_HOST', isset($_SERVER['PERSPECTIVE_DB_HOST'])?$_SERVER['PERSPECTIVE_DB_HOST']:ini_get('mysqli.default_host'));
define('DB_PORT', isset($_SERVER['PERSPECTIVE_DB_PORT'])?$_SERVER['PERSPECTIVE_DB_PORT']:ini_get('mysqli.default_port'));
if(DB_NAME == ''){
	die("Missing CGI environment variable: PERSPECTIVE_DB_NAME. Can go no further without this. Check your web server configuration.");
}

// Data directory
if (isset($_SERVER['PERSPECTIVE_DATA_ROOT'])) {
	define('DATA_ROOT', rtrim($_SERVER['PERSPECTIVE_DATA_ROOT'], '\\/'));
} else if(PHP_OS == "WINNT") {
	define('DATA_ROOT', "C:/Apps/sunesis-data");
} else {
	define('DATA_ROOT', "/var/www/html/am_common_data");
}

// Global debug value
if (!defined('DEBUG')) {
	define('DEBUG', false);
}

////////////////////////////////////////////////////////////////////////////////


function exception_handler($e)
{
	if ($e instanceof UnauthenticatedException)	{
		$destination = urlencode($_SERVER['PHP_SELF'].'?'.$_SERVER['QUERY_STRING']);
		$message = urlencode('Your Sunesis session has expired. Please login again.');
		http_redirect("/do.php?_action=login&destination=$destination&message=$message");
	}

	// Unwrap WrappedException objects
	if ($e instanceof WrappedException) {
		while($e instanceof WrappedException && $e->getWrappedException()) {
			$e = $e->getWrappedException();
		}
	}

	if ($e->getPrevious()) {
		$data = '';
		$original = $e;
		while ($original->getPrevious()) {
			$original = $original->getPrevious();
			if (strlen($data) > 0) {
				$data .= "\r\n\r\n----------------------------------\r\n\r\n";
			}
			$data .= $original->getFile() . " (" . $original->getLine() . "): "
				. $original->getMessage() . "\r\n\r\n" . $e->getTraceAsString();
			if ($original instanceof SQLException) {
				$data .= "\r\n\r\n" . $original->getSql();
			} else if ($original instanceof XMLException) {
				$data .= "\r\n\r\n" . $original->getXML();
			}
		}
		main_error_routine($e->getCode(), $e->getMessage(), $e->getFile(), $e->getLine(), $e->getTraceAsString(), $data);
	} else if ($e instanceof SQLException) {
		main_error_routine($e->getCode(), $e->getMessage(), $e->getFile(), $e->getLine(), $e->getTraceAsString(), $e->getSql());
	} else if ($e instanceof XMLException) {
		main_error_routine($e->getCode(), $e->getMessage(), $e->getFile(), $e->getLine(), $e->getTraceAsString(), $e->getXML());
	} else {
		main_error_routine($e->getCode(), $e->getMessage(), $e->getFile(), $e->getLine(), $e->getTraceAsString());
	}
}

set_exception_handler('exception_handler');

function custom_error_handler($errno, $errstr, $errfile, $errline)
{
	// Only report errors that are within the current level of error reporting
	// This allows the @ operator to work, which temporarily sets
	// the current error report level to 0
	if($errno & error_reporting())
	{
		// The custom error handler does not receive a stack trace in its
		// arguments, so we'll generate our own.
		$backtrace = debug_backtrace();
		$trace = "";
		$count = 0;
		foreach($backtrace as $entry)
		{
			if(isset($entry['file'], $entry['line']))
			{
				$trace .= '#'.$count++.' '.$entry['file'].' ('.$entry['line'].")\n\n";
			}
		}
		main_error_routine($errno, $errstr, $errfile, $errline, $trace);
		exit(0);
	}
}

set_error_handler('custom_error_handler');


function main_error_routine($code = '', $message = '', $file = '', $line = '', $trace = '', $extra_info = '')
{
	// Don't use custom code to report any errors from code in here
	restore_error_handler();

	// RE 02/09/2011
	// only log errors occurring from external sources in the db
	// - still report all errors to technical team.
	// - prevents skewing of logs during internal error investigation.
	if ( !SOURCE_BLYTHE_VALLEY && !SOURCE_LOCAL) {
	
		// Attempt to log the error using a new connection
		// (the main connection could be in a non-committed transaction)
		try
		{
/*			if (isset($GLOBALS['link'])) {
				$error_link = $GLOBALS['link'];
			} else {
				$error_link = new PDO("mysql:host=".DB_HOST.";dbname=".DB_NAME.";port=".DB_PORT, DB_USER, DB_PASSWORD);
			}*/
			$error_link = new PDO("mysql:host=".DB_HOST.";dbname=".DB_NAME.";port=".DB_PORT, DB_USER, DB_PASSWORD);

			$usr = isset($_SESSION['user']) ? "'".addslashes((string)$_SESSION['user']->username)."'" : "NULL";
			$cde = $code !== '' ? "'".addslashes((string)$code)."'" : "NULL";
			$msg = $message !== '' ? "'".addslashes((string)$message)."'" : "NULL";
			$fl = $file !== '' ? "'".addslashes(basename($file))."'" : "NULL";
			$ln = $line !== '' ? "'".addslashes((string)$line)."'" : "NULL";
			$trc = $trace !== '' ? "'".addslashes((string)$trace)."'" : "NULL";
			$sql = <<<HEREDOC
INSERT INTO error_log (`username`, `code`, `message`, `file1`, `line`, `trace`)
	VALUES ($usr, $cde, $msg, $fl, $ln, $trc);			
HEREDOC;
		
			@$error_link->exec($sql);
			$error_link = null;
		}
		catch(Exception $e)
		{
			// Do nothing
		}
	}
	
	
	// Log the error in the Apache error log
	if(strpos($_SERVER['SERVER_SOFTWARE'], 'Microsoft-IIS') === false)
	{
		error_log("$file($line): $message");
	}
	
	// Email the error
	email_support($code, $message, $file, $line, $trace, $extra_info);
	
	
	if(IS_AJAX)
	{
		if(headers_sent())
		{
			// Not a good situation to be in with AJAX!
			// Trick the AJAX framework into thinking this is a PHP generated error
			// (make sure the INI variables 'error_append_string' and
			// 'error_prepend_string' are set in either php.ini or in the Apache
			// VirtualHosts definition).
			echo '<php:error xmlns:php="http://php.net">'
				.htmlspecialchars((string)$file.'('.$line.'): '.$message)
				.'</php:error>';
			exit(0);		
		}
		else
		{
			// Headers have not been sent -- we can clear the output buffer and begin again
			$status = @ob_get_status(true);
			if(count($status) > 0)
			{
				while(@ob_end_clean());
			}
			
			header("X-Perspective: Application error", true, 500);
			if (SOURCE_LOCAL || SOURCE_BLYTHE_VALLEY) {
				header("X-Perspective-Error-Message: " . substr(preg_replace('/[^A-Za-z0-9():_ ]/', '', $message), 0, 100));
				header("X-Perspective-Error-File: " . substr(preg_replace('/[^A-Za-z0-9():_ ]/', '', $file), 0, 100));
				header("X-Perspective-Error-Line: " . substr(preg_replace('/[^A-Za-z0-9():_ ]/', '', $line), 0, 100));
			}
			header("Content-Type: text/xml");
			echo "<?xml version=\"1.0\"?>";
			echo "<error>";
			echo "<message>" . htmlspecialchars((string)$message). "</message>";
			echo "<file>" . htmlspecialchars(basename($file)) . "</file>";
			echo "<line>" . htmlspecialchars((string)$line) . "</line>";
			echo "<code>" . htmlspecialchars((string)$code) . "</code>";
			echo "<trace>" . htmlspecialchars((string)$trace) . "</trace>";
			if (SOURCE_LOCAL || SOURCE_BLYTHE_VALLEY) {
				echo "<extra_info>" . htmlspecialchars((string)$extra_info) . "</extra_info>";
			}
			echo "</error>";
			exit(0);
		}
	}
	else
	{
		if(headers_sent())
		{
			// Cannot redirect to error.php
			echo '<div style="border:3px red solid;color:red;padding:5px;">'
				. '<font size="3"><b>' . htmlspecialchars((string)$message) . '</b></font>';
			if(SOURCE_LOCAL || SOURCE_BLYTHE_VALLEY){
				echo "<br/><font size=\"3\">@$file($line)</font><br/>";
				echo '<font size="1">' . wordwrap(htmlspecialchars((string)$trace), 60, "&#8203;", 1) . '</font>'; // &#8203; is a zero-length space (helps mozilla break long words)
			}
			echo '</div>';
			exit(0);
		}
		else
		{
			// Clear output buffer
			$status = @ob_get_status(true);
			if(count($status) > 0)
			{
				while(@ob_end_clean());
			}
			
			$trace = preg_replace('/\((\d+)\)/', '(<span style="font-weight:bold;">\1</span>)', htmlspecialchars((string)$trace));
			$trace = preg_replace('/^(#.+?)$/m', '<p>$1</p>', $trace);
			
			header("X-Perspective: Application error");
			if(SOURCE_LOCAL || SOURCE_BLYTHE_VALLEY){
				header("X-Perspective-Error-Message: " . substr(preg_replace('/[^A-Za-z0-9():_ ]/', '', $message), 0, 100));
				header("X-Perspective-Error-File: " . substr(preg_replace('/[^A-Za-z0-9():_ ]/', '', $file), 0, 100));
				header("X-Perspective-Error-Line: " . substr(preg_replace('/[^A-Za-z0-9():_ ]/', '', $line), 0, 100));
			}
			require_once('tpl_error_page.php');
			exit(0);
		}
	}	
}


function html_mail($to, $from, $subject, $plain_text, $html, array $files = array(), array $extra_headers = array())
{
	// Clean recipients
	if(is_array($to)){
		$to = implode(', ', $to);
	}
	
	// Create the "MAIL From:" address for the SMTP envelope
	if(preg_match('/<(.*@.*)>/', $from, $matches))
	{
		$envelope_from = $matches[1];
	}
	else
	{
		$envelope_from = $from;
	}
	
	// Add custom headers
	$boundary = md5(uniqid(time()));
	$headers = "MIME-Version: 1.0\r\n";
	$headers .= "From: ".$from."\r\n";
	$headers .= "Subject: $subject\r\n";
	foreach($extra_headers as $header){
		$headers .= $header."\r\n"; // custom header
	}
	$headers .= "Content-Type: multipart/alternative;\r\n boundary=" . $boundary . "\r\n";
	
	$message = "This is a MIME encoded message.\r\n";
	
	$message .= "\r\n--" . $boundary . "\r\n";
	$message .= "Content-Type: text/plain; charset=ISO-8859-1; format=flowed\r\n";
	$message .= "Content-Transfer-Encoding: base64\r\n\r\n";
	$message .= chunk_split(base64_encode($plain_text));
	
	$message .= "\r\n--" . $boundary . "\r\n";
	$message .= "Content-Type: text/html; charset=ISO-8859-1\r\n";
	$message .= "Content-Transfer-Encoding: base64\r\n\r\n";
	$message .= chunk_split(base64_encode($html));
	
	foreach($files as $f)
	{
		$message .= "\r\n--" . $boundary . "\r\n";
		$message .= "Content-Type: text/plain; charset=ISO-8859-1\r\n";
		$message .= "Content-Disposition: attachment; filename=\"{$f['filename']}\"\r\n";
		$message .= "Content-Transfer-Encoding: base64\r\n\r\n";
		$message .= chunk_split(base64_encode($f['data']));
	}
	
	//mail($to, $subject, $message, $headers, '-f'.$envelope_from.' -ODeliveryMode=b');
	mail($to, $subject, $message, $headers, '-f '.$envelope_from );
}


function email_support($code, $message, $file, $line, $trace, $extra_info = '')
{
	if( SOURCE_LOCAL || PHP_OS == "WINNT") {
		return;
	}

	// Make filepaths relative to the application root -- no need to include the full path
	$file = preg_replace('#/srv/www/.*?/htdocs/#', '', $file);
	$trace = preg_replace('#/srv/www/.*?/htdocs/#', '', $trace);

	$subject = "Error: ".basename($file)." (".$line.")";
	$from_parts = explode('.',$_SERVER['SERVER_NAME']);
	$from = strtoupper($from_parts[0])." <sunesis@perspective-uk.com>";
	$to = "K.Khan@sunesis.co,inaam@sunesis.co";
	$headers = array("Importance: high");
	
	$full_message = $message;
	$partial_message = Text::abbreviate($message, 250, '...');
	
	$dbname = DB_NAME;
    $firstnames = isset($_SESSION['user']->firstnames) ? $_SESSION['user']->firstnames : "";
    $surname = isset($_SESSION['user']->surname) ? $_SESSION['user']->surname : "";
    $username = isset($_SESSION['user']->username) ? $_SESSION['user']->username : "";
    $usertype = isset($_SESSION['user']->type) ? $_SESSION['user']->type : "";
    $isSysAdmin = isset($_SESSION['user']) ? ($_SESSION['user']->isAdmin()?'Yes':'No') : '';
    $contact_email = isset($_SESSION['user']->work_email) ? $_SESSION['user']->work_email : $_SESSION['user']->home_email." (home)";
    $org = isset($_SESSION['user']->org->legal_name) ? $_SESSION['user']->org->legal_name : "";
    $org_id = isset($_SESSION['user']->org->id) ? $_SESSION['user']->org->id : "";
    $url = isset($_SERVER['REQUEST_URI']) ? $_SERVER['REQUEST_URI'] : "";
    $method = isset($_SERVER["REQUEST_METHOD"]) ? $_SERVER["REQUEST_METHOD"] : "";
    $referer = isset($_SERVER['HTTP_REFERER']) ? (parse_url($_SERVER['HTTP_REFERER'], PHP_URL_PATH).'?'.parse_url($_SERVER['HTTP_REFERER'], PHP_URL_QUERY)) : "";
    $user_agent = isset($_SERVER['HTTP_USER_AGENT']) ? Text::formatUserAgent($_SERVER['HTTP_USER_AGENT']) : "";
    $remote_addr = isset($_SERVER['REMOTE_ADDR']) ? $_SERVER['REMOTE_ADDR'] : "";
    $date = new DateTime("now");
    $date = $date->format("d/m/Y H:i:s");

    $sugar_url = "";
    if($surname) {
    	$sugar_url = "https://sugar.perspective-uk.com/index.php?module=Home"
        . "&advanced=true&action=UnifiedSearch&search_form=false&search_mod_Contacts=true"
        . "&query_string=" . rawurlencode($surname);
    }

    $text = <<<HEREDOC
Error: {$file}({$line})
URL: {$url}
Method: {$method}
Date: {$date}
DB: {$dbname}

User: {$firstnames} {$surname} ({$username})
Org: {$org} (#{$org_id})
User-Type: {$usertype}
Sys-Admin: {$isSysAdmin}
Email: {$contact_email}
Tel: {$_SESSION['user']->work_telephone}
Role: {$_SESSION['user']->job_role}
Sugar: {$sugar_url}

Referring page: {$referer}
Browser: {$user_agent}
IP: {$remote_addr}


$message

{$file}({$line})
$trace
HEREDOC;
	
	$file = htmlspecialchars(str_replace("/", "/&#8203;", $file), ENT_COMPAT, "ISO-8859-1", false);
	$file_line = $file.'('.$line.')';
	$file_line = preg_replace('/[a-zA-Z0-9_\\-]+\\.php\\s*\\([0-9]+\\)/', '<b style="color:black">$0</b>', $file_line);
    $firstnames = htmlspecialchars(Text::softBreak($firstnames), ENT_COMPAT, "ISO-8859-1", false);
    $surname = htmlspecialchars(Text::softBreak($surname), ENT_COMPAT, "ISO-8859-1", false);
    $username = htmlspecialchars(Text::softBreak($username), ENT_COMPAT, "ISO-8859-1", false);
    $usertype = htmlspecialchars(Text::softBreak($usertype), ENT_COMPAT, "ISO-8859-1", false);
    $isSysAdmin = htmlspecialchars(Text::softBreak($isSysAdmin), ENT_COMPAT, "ISO-8859-1", false);
    $contact_email = htmlspecialchars(Text::softBreak($contact_email), ENT_COMPAT, "ISO-8859-1", false);
    $telephone = htmlspecialchars(Text::softBreak($_SESSION['user']->work_telephone), ENT_COMPAT, "ISO-8859-1", false);
    $role = htmlspecialchars(Text::softBreak($_SESSION['user']->job_role), ENT_COMPAT, "ISO-8859-1", false);
    $org = htmlspecialchars(Text::softBreak($org));
    $org_id = htmlspecialchars(Text::softBreak($org_id), ENT_COMPAT, "ISO-8859-1", false);
    $url = htmlspecialchars(Text::softBreak($url), ENT_COMPAT, "ISO-8859-1", false);
    $method = htmlspecialchars(Text::softBreak($method), ENT_COMPAT, "ISO-8859-1", false);
    $referer = htmlspecialchars(Text::softBreak($referer), ENT_COMPAT, "ISO-8859-1", false);
    $user_agent = htmlspecialchars(Text::softBreak($user_agent), ENT_COMPAT, "ISO-8859-1", false);
    $remote_addr = htmlspecialchars(Text::softBreak($remote_addr), ENT_COMPAT, "ISO-8859-1", false);
    $date = htmlspecialchars(Text::softBreak($date), ENT_COMPAT, "ISO-8859-1", false);
    
    $full_message = htmlspecialchars((string)$full_message);
    $partial_message = htmlspecialchars((string)$partial_message);
    $trace = preg_replace('/[a-zA-Z0-9_\\-]+\\.php\\s*\\([0-9]+\\)/', '<b style="color:black">$0</b>', $trace);
    $trace = preg_replace('/[\\r\\n]+/', '<br/><br/>', $trace);
    
    $attachments = array();
    if(strlen($extra_info) < 5120)
    {
    	// Show extra_info inline if less than 5KB in size
    	$extra_info = str_replace("\t", "&nbsp;&nbsp;&nbsp;", nl2br(htmlspecialchars((string)$extra_info)));
    }
    else
    {
    	// Attach extra_info as a file if more than 5KB in size
    	$attachments[] = array("filename"=>"additional-info.txt", "data"=>$extra_info);
    	$extra_info = 'Please see attachment';
    }
    
    if($_SERVER['REQUEST_METHOD'] == "POST")
    {
    	$post_data = "";
    	foreach($_POST as $key=>$val)
    	{
    		$post_data .= $key." = ".$val."\r\n\r\n";
    	}
    	$attachments[] = array("filename"=>"POST.txt", "data"=>$post_data);
    }

	$html = <<<HEREDOC
<p style="font-size:1.1em;font-family:sans-serif;background-color:#dfe9cd;font-weight:bold">{$partial_message}</p>

<h3 style="margin-top:30px; margin-bottom:5px;font-family:Calibri,sans-serif;">General</h3>
<table cellpadding="2" cellspacing="2" style="margin-left:20px; color:#444444; font-family:Calibri,sans-serif;font-size:11pt;">
<tr><td valign="top" style="font-weight:bold;" width="130">File</td><td valign="top">{$file_line}</td></tr>
<tr><td valign="top" style="font-weight:bold;" width="130">Code</td><td valign="top">{$code}</td></tr>
<tr><td valign="top" style="font-weight:bold;" width="130">URL</td><td valign="top">{$url}</td></tr>
<tr><td valign="top" style="font-weight:bold;" width="130">Database</td><td valign="top"><code>{$dbname}</code></td></tr>
</table>

<h3 style="margin-top:30px; margin-bottom:5px;font-family:Calibri,sans-serif;">User</h3>
<table cellpadding="2" cellspacing="2" style="margin-left:20px; color:#444444; font-family:Calibri,sans-serif;font-size:11pt;">
<tr><td valign="top" style="font-weight:bold;" width="130">Name</td><td valign="top">{$firstnames} {$surname} ({$username})</td></tr>
<tr><td valign="top" style="font-weight:bold;" width="130">Org</td><td valign="top">{$org} (#{$org_id})</td></tr>
<tr><td valign="top" style="font-weight:bold;" width="130">Position</td><td valign="top">{$role}</td></tr>
<tr><td valign="top" style="font-weight:bold;" width="130">User Type</td><td valign="top">{$usertype}</td></tr>
<tr><td valign="top" style="font-weight:bold;" width="130">Sys Admin</td><td valign="top">{$isSysAdmin}</td></tr>
<tr><td valign="top" style="font-weight:bold;" width="130">Email</td><td valign="top">{$contact_email}</td></tr>
<tr><td valign="top" style="font-weight:bold;" width="130">Telephone</td><td valign="top">{$telephone}</td></tr>
<tr><td valign="top" style="font-weight:bold;" width="130">Sugar CRM</td><td valign="top"><a href="{$sugar_url}">search for record</a></td></tr>
</table>

<h3 style="margin-top:30px; margin-bottom:5px;font-family:Calibri,sans-serif;">HTTP variables</h3>
<table cellpadding="2" cellspacing="2" style="margin-left:20px; color:#444444; font-family:Calibri,sans-serif;font-size:11pt;">
<tr><td valign="top" style="font-weight:bold;" width="130">Referer</td><td valign="top">{$referer}</td></tr>
<tr><td valign="top" style="font-weight:bold;" width="130">Method</td><td valign="top">{$method}</td></tr>
<tr><td valign="top" style="font-weight:bold;" width="130">Browser</td><td valign="top">{$user_agent}</td></tr>
<tr><td valign="top" style="font-weight:bold;" width="130">IP</td><td valign="top">{$remote_addr}</td></tr>
</table>

<h3 style="margin-top:30px; margin-bottom:5px;font-family:Calibri,sans-serif;">Full Message</h3>
<div style="margin-left:20px;word-wrap:break-word; color:#444444; font-family:Calibri,sans-serif;font-size:11pt">$full_message</div>

<h3 style="margin-top:30px; margin-bottom:5px;font-family:Calibri,sans-serif;">Stack Trace</h3>
<p style="margin-left:20px; word-wrap:break-word; color:#444444; font-family:Calibri,sans-serif;font-size:11pt">{$file_line}</p>
<div style="margin-left:40px;word-wrap:break-word; color:#444444; font-family:Calibri,sans-serif;font-size:11pt">$trace</div>
HEREDOC;

	// Display extra_info inline only if it has content
	if($extra_info){
		$html .= '<h3 style="margin-top:30px; margin-bottom:5px;font-family:Calibri,sans-serif;">Additional Info</h3><div style="margin-left:20px;font-family:monospace;font-size:9pt;word-wrap:break-word; color:#444444;">'.$extra_info.'</div>';
	}

	html_mail($to, $from, $subject, $text, $html, $attachments, array("Importance: high"));
}


////////////////////////////////////////////////////////////////////////////////

/**
 * @param string $uri
 */
function http_redirect($uri)
{
	$uri = str_replace("&amp;", "&", $uri);

	// Absolute URIs
	if(strpos($uri, 'http://') === 0)
	{
		header("Location: $uri", true, 302);
		exit(0);		
	}
	
	$host = isset($_SERVER['SERVER_NAME']) ? $_SERVER['SERVER_NAME'] : $_SERVER['SERVER_ADDR'];
	$port = $_SERVER['SERVER_PORT'];
	$protocol = array_key_exists('HTTPS', $_SERVER) && $_SERVER['HTTPS'] == 'on'?'https':'http';
	
	// If the website has been accessed by IP address, there will be no hostname
	// (SSH requests will use the hostname 'localhost')
	if($host == '')
	{
		$host = $_SERVER['SERVER_ADDR'];
	}
	
	// Make relative uri's absolute
	if($uri[0] != '/')
	{
		$path   = rtrim(dirname($_SERVER['PHP_SELF']), '/\\');
		$uri = $path.'/'.$uri;
	}

	header("Location: $protocol://$host$uri", true, 302);
	exit(0);
}

function pre($array)
{
	echo '<div style="background: #FFBABA; padding: 20px 20px; border: 1px solid #ff0000;">';
	echo '<pre style="padding:0;margin:0;">';
	print_r($array);
	echo '</pre>';
	echo '</div>';
	die;
}

function pr($array)
{
	echo '<div style="background: #BDE5F8; padding: 20px 20px; border: 1px solid #00529B;">';
	echo '<pre style="padding:0;margin:0;">';
	print_r($array);
	echo '</pre>';
	echo '</div>';	
}


?>
