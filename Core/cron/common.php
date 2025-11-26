<?php
/**
 * Bootstrap code and function library
 */

// Name of lock file (if we decide to use locks)
define('LOCK_FILE', __FILE__ . '.lock');

// Application root (called WEBROOT for historical reasons)
define('WEBROOT', dirname(__DIR__));

// Provide route to dispatcher's action directory and the main Sunesis class library directory
set_include_path((WEBROOT . '/htdocs/lib') . PATH_SEPARATOR . get_include_path());

// When this script is run by cron, it runs with a very limited set of environment variables
// Create missing elements manually.
if (!isset($_SERVER['SERVER_NAME'])) {
	$_SERVER['SERVER_NAME'] = `hostname`;
}
if (!isset($_SERVER['HOSTNAME'])) {
	$_SERVER['HOSTNAME'] = `hostname`;
}

// Global variable holding the email recipients for action errors/output
$mailLog = null;
$mailErrors = null;

function load_class($className)
{
	if (class_exists($className)) {
		return;
	} else {
		@include $className . '.php'; // Don't throw an exception, give Zend's autoloader a chance
	}
}

// Class autoloading
spl_autoload_register(function($class_name) {
	load_class($class_name); // Sunesis library
});
if ((@include 'Zend/Loader/Autoloader.php')) {
	Zend_Loader_Autoloader::getInstance(); // Zend library (automatically registers autoloader on initialisation)
}

/**
 * Creates a lock file if it does not yet exist or exits
 * the script if it does.
 */
function createLockFile()
{
	if (file_exists(LOCK_FILE)) {
		exit(1);
	} else {
		touch(LOCK_FILE);
	}
}

/**
 * Deletes the lock file
 */
function deleteLockFile()
{
	if (file_exists(LOCK_FILE)) {
		unlink(LOCK_FILE);
	}
}

/**
 * Adapted from DAO::getConnection(), but this time without caching
 * @param string $host
 * @param string $port
 * @param string $user
 * @param string $password
 * @param string $dbName
 * @return PDO
 */
/*function getDatabaseConnection($host = '', $port = '', $user = '', $password = '', $dbName = '')
{
	if (!$host) {
		if (defined("DB_HOST")) {
			$host = DB_HOST;
		} else {
			if (PHP_OS == "WINNT") {
				$host = '127.0.0.1';
			} else {
				$host = "localhost";
			}
		}
	}
	if (!$port) {
		if (defined("DB_PORT")) {
			$port = DB_PORT;
		} else {
			$port = 3306;
		}
	}
	if (!$user) {
		if (defined("DB_USER")) {
			$user = DB_USER;
		}
	}
	if (!$password) {
		if (defined("DB_PASSWORD")) {
			$password = DB_PASSWORD;
		}
	}
	if (!$dbName) {
		if (defined("DB_NAME")) {
			$dbName = DB_NAME;
		}
	}

	$link = new PDO("mysql:host=" . $host . ";dbname=" . $dbName . ";port=" . $port, $user, $password);

	return $link;
}*/


/*function executeAction($schema, $actionName, $actionId)
{
	if (function_exists('pcntl_fork')) {
		$child_pid = pcntl_fork(); // Fork off
		if ($child_pid) {
			return; // Parent thread
		} else {
			$link = getDatabaseConnection();
			$action = new $actionName($link, $schema, $actionId);
			$action->execute($link);
			exit(0);
		}
	} else {
		$link = getDatabaseConnection();
		$action = new $actionName($link, $schema, $actionId);
		$action->execute();
	}
}*/


/*function dispatch(array $actions)
{
	global $mailTo;
	$maxProcesses = 5;

	if (function_exists('pcntl_fork')) {
		$pids = array();
		while ($action = array_shift($array)) {
			$pid = pcntl_fork();
			if ($pid) {
				// PARENT process
				$pids[] = $pid;
				if(count($pids) >= $maxProcesses) {
					$status = null;
					$pid = pcntl_wait($status);
					$index = array_search($pid, $pids);
					unset($pids[$index]);
				}
			} else {
				// CHILD process
				$link = getDatabaseConnection();
				DAO::execute($link, "use " . $action->schema);
				$mailTo = $action->mail_to;
				$action->execute($link);
				$link = null;
				exit(0);
			}
		}

		// Clean up
		foreach ($pids as $pid) {
			$status = null;
			pcntl_waitpid($pid, $status);
		}
	} else {
		$link = getDatabaseConnection();
		foreach ($actions as $action) {
			$mailTo = $action->mail_to;
			DAO::execute($link, "use " . $action->schema);
			$action->execute($link);
		}
	}
}*/

/**
 * @param array[CrontabAction] $actions
 */
function dispatch(array $actions)
{
	global $mailLog;
	global $mailErrors;

	// Set up logger (use a separate database connection so we can rollback the main query without rolling back the log)
	$link_log = DAO::getConnection(null, null, null, null, null, false);
	$columnMap = array(
		'priority' => 'priority',
		'priority_name' => 'priorityName',
		'message' => 'message',
		'timestamp' => 'timestamp',
		'crontab_id' => 'crontab_id'
	);
	$writerDb = new LogWriterDb($link_log, 'crontab_log', $columnMap);
	$logger = new Zend_Log($writerDb);
	$logger->addWriter(new Zend_Log_Writer_Stream('php://output'));

	$link = DAO::getConnection();

	foreach ($actions as $action) {
		$mailLog = $action->mail_log;
		$mailErrors = $action->mail_errors;
		DAO::execute($link, "use " . $action->schema);
		DAO::execute($link_log, "use " . $action->schema);

		$errorCaught = false;
		ob_start();
		try
		{
			$action->setLog($logger);
			$action->log('Task started', Zend_Log::INFO);
			DAO::transaction_start($link);
			$action->execute($link);
			$action->log('Task completed', Zend_Log::INFO);
			DAO::transaction_commit($link);
		}
		catch(Exception $e)
		{
			try {
				DAO::transaction_rollback($link);
			} catch (Exception $e2) {
				// If we cannot rollback, keep going
			}
			$action->log($e, Zend_Log::ERR);
			$action->log('Task aborted', Zend_Log::INFO);
			$errorCaught = true;
		}
		$strLog = ob_get_clean();

		// Prepare recipients list (add the error recipients if an Exception has been thrown)
		$recipients = array();
		if ($mailLog) {
			$recipients = array_merge($recipients, explode(',', $mailLog));
		}
		if ($errorCaught && $mailErrors) {
			$recipients = array_merge($recipients, explode(',', $mailErrors));
		}
		$recipients = array_unique($recipients);

		if ($recipients) {
			if (PHP_OS == 'Linux') {
				$host = strstr($_SERVER['SERVER_NAME'], '.', true);
				$mail = new Zend_Mail();
				$mail->setBodyText($strLog);
				$mail->setSubject('Scheduled task ' . $action->task . ' #' . $action->id);
				$mail->setFrom('noreply@perspective-uk.com', $action->schema . ' (' . $host . ')');
				foreach ($recipients as $recipient) {
					$mail->addTo($recipient);
				}
				$mail->send();
			}
		}
	}
}

/**
 * Custom exception handler
 * @param Exception $e
 */
function exception_handler(Exception $e)
{
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

/**
 * Custom error handler
 * @param integer $errno
 * @param string $errstr
 * @param string $errfile
 * @param integer $errline
 */
function error_handler($errno, $errstr, $errfile, $errline)
{
	// Only report errors that are within the current level of error reporting
	// This allows the @ operator to work, which temporarily sets
	// the current error report level to 0
	if ($errno & error_reporting()) {
		// The custom error handler does not receive a stack trace in its
		// arguments, so we'll generate our own.
		$backtrace = debug_backtrace();
		$trace = "";
		$count = 0;
		foreach ($backtrace as $entry) {
			if(isset($entry['file'], $entry['line'])) {
				$trace .= '#' . $count++ . ' ' . $entry['file'] . ' (' . $entry['line'] . ")\n\n";
			}
		}
		main_error_routine($errno, $errstr, $errfile, $errline, $trace);
	}
}


/**
 * Central error handler
 * @param string $code
 * @param string $message
 * @param string $file
 * @param string $line
 * @param string $trace
 * @param string $extra_info
 */
function main_error_routine($code = '', $message = '', $file = '', $line = '', $trace = '', $extra_info = '')
{
	global $mailLog;
	global $mailErrors;

	$recipients = array();
	if ($mailLog) {
		$recipients = array_merge($recipients, explode(',', $mailLog));
	}
	if ($mailErrors) {
		$recipients = array_merge($recipients, explode(',', $mailErrors));
	}
	$recipients = array_unique($recipients);
	if (!$recipients) {
		$recipients = array('iss@perspective-uk.com');
	}

	if (PHP_OS == 'Linux') {
		if ($recipients) {
			$host = strstr($_SERVER['SERVER_NAME'], '.', true);
			$mail = new Zend_Mail();
			$mail->setBodyText($message . "\r\n\r\n" . $trace);
			$mail->setFrom('noreply@perspective-uk.com', $host);
			foreach($recipients as $recipient) {
				$mail->addTo($recipient);
			}
			$mail->setSubject('Error executing cron.php');
			$mail->send();
		}
	} else {
		echo "\r\n\r\n", $message, "\r\n", $trace, "\r\n";
	}

	deleteLockFile();
	exit(1);
}

set_exception_handler('exception_handler');
set_error_handler('error_handler');
