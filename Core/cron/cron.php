<?php
/**
 * This file requires a cron entry (replace mysqluser and mysqlpassword with appropriate values):
 *  * * * * * cd /srv/www/am_common/cron && /usr/local/bin/php -f cron.php mysqluser mysqlpassword 2>&1
 *
 * For example, the crontab for the 'root' user:
 *  PATH=/bin:/sbin:/usr/bin:/usr/sbin:/usr/local/bin
 *  * * * * cd /srv/www/am_common/cron && /usr/local/bin/php /srv/www/am_common/cron/cron.php root perspective 2>&1
 *
 */

$now = time(); // Record the timestamp at the start of execution

include('./common.php');

$now = new Date($now);  // Convert the timestamp to a Sunesis Date

// ---------------------------------------------------------------------------------------------------------------------

// Command line arguments
if (count($argv) < 3) {
	die("Usage: php dispatcher.php {username} {password}");
}
$user = $argv[1];
$pwd = $argv[2];
define('DB_USER', $user);
define('DB_PASSWORD', $pwd);

// Database connection
$link = DAO::getConnection();

// Retrieve a list of Sunesis schemata with both crontab tables
$sql = <<<SQL
SELECT DISTINCT
	table_schema
FROM
	information_schema.`TABLES`
WHERE
	`table_name` IN ('crontab', 'crontab_config', 'crontab_log')
	AND table_schema LIKE 'am\_%'
GROUP BY
	table_schema
HAVING
	COUNT(*) = 3
SQL;
$schemata = DAO::getSingleColumn($link, $sql);

// Logger
$columnMap = array(
	'priority' => 'priority',
	'priority_name' => 'priorityName',
	'message' => 'message',
	'timestamp' => 'timestamp',
	'crontab_id' => 'crontab_id'
);


// Iterate through participating schemata
$actions = array();
foreach ($schemata as $schema) {
	DAO::execute($link, "use $schema");

	// Check crontab is enabled
	$enabled = DAO::getSingleValue($link, "SELECT `value` FROM `configuration` WHERE `entity` = 'crontab.enabled'");
	if (!$enabled) {
		continue;
	}

	$ids = DAO::getSingleColumn($link, "SELECT * FROM `crontab` WHERE `enabled`=1 ORDER BY `order`, `id`");
	foreach ($ids as $id) {
		$action = CrontabAction::loadFromDatabase($link, $id);
		//$action->setLog($logger);
		if(!$action->matchDate($now)) {
			continue;
		}
		$actions[] = $action;
	}
}

// Disconnect from database
$link = null;

// Dispatch
dispatch($actions);