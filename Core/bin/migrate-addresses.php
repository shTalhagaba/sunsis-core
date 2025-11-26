<?php
define("WEBROOT", dirname(__DIR__));

// Increase memory limit
$memoryLimitBytes = 368 * 1024 * 1024;
ini_set('memory_limit', $memoryLimitBytes);

// Class autoloading
spl_autoload_register(function($class_name) {
	@include WEBROOT . '/htdocs/lib/' . $class_name . '.php'; // Sunesis library
});
if ((@include 'Zend/Loader/Autoloader.php')) {
	Zend_Loader_Autoloader::getInstance(); // Zend library (automatically registers autoloader on initialisation)
}

// Command line arguments
if (count($argv) < 4) {
	die("Usage: php migrate-addresses.php {username} {password} {db_name}");
}
$user = $argv[1];
$pwd = $argv[2];
$db = addslashes($argv[3]);
if (!$db) {
	die("Usage: php migrate-addresses.php {username} {password} {db_name}");
}

// Database connection
$link = DAO::getConnection("127.0.0.1", 3306, $user, $pwd);

$sql = <<<SQL
SELECT DISTINCT
	table_schema,
	table_name,
	column_name
FROM
	information_schema.columns
WHERE
	column_name LIKE "%saon_start_number"
	AND table_schema LIKE '{$db}'
ORDER BY
	table_schema, table_name, column_name
SQL;

$rs = DAO::getResultset($link, $sql, DAO::FETCH_ASSOC);
foreach($rs as $row) {
	preg_match('/^(\w+_)?saon_start_number/', $row['column_name'], $matches);
	$prefix = count($matches) > 1 ? $matches[1] : '';

	// Schema changes
	if (DAO::schemaEntityExists($link, $row['table_schema'], $row['table_name'], $prefix . 'address_line_1')) {
		$sql = "ALTER TABLE `{$row['table_schema']}`.`{$row['table_name']}` CHANGE COLUMN {$prefix}address_line_1 {$prefix}address_line_1 VARCHAR(100)";
		echo "`{$row['table_schema']}`.`{$row['table_name']}`: changing {$prefix}address_line_1\r\n";
		DAO::execute($link, $sql);
	} else {
		$sql = "ALTER TABLE `{$row['table_schema']}`.`{$row['table_name']}` ADD COLUMN {$prefix}address_line_1 VARCHAR(100) AFTER `{$prefix}county`";
		echo "`{$row['table_schema']}`.`{$row['table_name']}`: creating {$prefix}address_line_1\r\n";
		DAO::execute($link, $sql);
	}
	if (DAO::schemaEntityExists($link, $row['table_schema'], $row['table_name'], $prefix . 'address_line_2')) {
		$sql = "ALTER TABLE `{$row['table_schema']}`.`{$row['table_name']}` CHANGE COLUMN {$prefix}address_line_2 {$prefix}address_line_2 VARCHAR(100)";
		echo "`{$row['table_schema']}`.`{$row['table_name']}`: changing {$prefix}address_line_2\r\n";
		DAO::execute($link, $sql);
	} else {
		$sql = "ALTER TABLE `{$row['table_schema']}`.`{$row['table_name']}` ADD COLUMN {$prefix}address_line_2 VARCHAR(100) AFTER `{$prefix}address_line_1`";
		echo "`{$row['table_schema']}`.`{$row['table_name']}`: creating {$prefix}address_line_2\r\n";
		DAO::execute($link, $sql);
	}
	if (DAO::schemaEntityExists($link, $row['table_schema'], $row['table_name'], $prefix . 'address_line_3')) {
		$sql = "ALTER TABLE `{$row['table_schema']}`.`{$row['table_name']}` CHANGE COLUMN {$prefix}address_line_3 {$prefix}address_line_3 VARCHAR(100)";
		echo "`{$row['table_schema']}`.`{$row['table_name']}`: changing {$prefix}address_line_3\r\n";
		DAO::execute($link, $sql);
	} else {
		$sql = "ALTER TABLE `{$row['table_schema']}`.`{$row['table_name']}` ADD COLUMN {$prefix}address_line_3 VARCHAR(100) AFTER `{$prefix}address_line_2`";
		echo "`{$row['table_schema']}`.`{$row['table_name']}`: creating {$prefix}address_line_3\r\n";
		DAO::execute($link, $sql);
	}
	if (DAO::schemaEntityExists($link, $row['table_schema'], $row['table_name'], $prefix . 'address_line_4')) {
		$sql = "ALTER TABLE `{$row['table_schema']}`.`{$row['table_name']}` CHANGE COLUMN {$prefix}address_line_4 {$prefix}address_line_4 VARCHAR(100)";
		echo "`{$row['table_schema']}`.`{$row['table_name']}`: changing {$prefix}address_line_4\r\n";
		DAO::execute($link, $sql);
	} else {
		$sql = "ALTER TABLE `{$row['table_schema']}`.`{$row['table_name']}` ADD COLUMN {$prefix}address_line_4 VARCHAR(100) AFTER `{$prefix}address_line_3`";
		echo "`{$row['table_schema']}`.`{$row['table_name']}`: creating {$prefix}address_line_4\r\n";
		DAO::execute($link, $sql);
	}

	// Reset new fields
	$sql = <<<SQL
UPDATE
	`{$row['table_schema']}`.`{$row['table_name']}`
SET
	{$prefix}address_line_1 = NULL,
	{$prefix}address_line_2 = NULL,
	{$prefix}address_line_3 = NULL,
	{$prefix}address_line_4 = NULL;
SQL;
	DAO::execute($link, $sql);

	// Populate new fields
	try
	{
		DAO::transaction_start($link);
		echo "`{$row['table_schema']}`.`{$row['table_name']}`: populating new fields\r\n";
		$primaryKeys = DAO::getTablePrimaryKeys($link, $row['table_schema'] . '.' . $row['table_name']);
		$stmt = $link->query("SELECT * FROM `{$row['table_schema']}`.`{$row['table_name']}`");
		while ($r = $stmt->fetch(PDO::FETCH_ASSOC)) {
			$addr = new Address($r, $prefix);
			$lines = $addr->to4Lines();
			$datum = array();
			foreach ($primaryKeys as $key=>$value) {
				$datum[$key] = $r[$key];
			}
			if ($lines[0]) {
				$datum[$prefix . 'address_line_1'] = substr($lines[0], 0, 100);
			}
			if ($lines[1]) {
				$datum[$prefix . 'address_line_2'] = substr($lines[1], 0, 100);
			}
			if ($lines[2]) {
				$datum[$prefix . 'address_line_3'] = substr($lines[2], 0, 100);
			}
			if ($lines[3]) {
				$datum[$prefix . 'address_line_4'] = substr($lines[3], 0, 100);
			}
			//var_dump($datum);

			DAO::saveObjectToTable($link, $row['table_schema'] . '.' . $row['table_name'], $datum);
		}
		$stmt->closeCursor();
		DAO::transaction_commit($link);
	}
	catch(Exception $e)
	{
		// If there is any error, it's very important we stop execution
		// or there's a danger we'll delete the BS7666 fields without
		// having properly populated the new fields.
		echo "\r\n\r\n";
		echo $e->getMessage();
		echo "\r\n\r\n";
		echo $e->getTraceAsString();
		echo "\r\n\r\n";
		exit(1);
	}


	try
	{
		// Remove old fields
		$sql = <<<SQL
ALTER TABLE `{$row['table_schema']}`.`{$row['table_name']}`
DROP COLUMN {$prefix}saon_start_number,
DROP COLUMN {$prefix}saon_start_suffix,
DROP COLUMN {$prefix}saon_end_number,
DROP COLUMN {$prefix}saon_end_suffix,
DROP COLUMN {$prefix}saon_description,
DROP COLUMN {$prefix}paon_start_number,
DROP COLUMN {$prefix}paon_start_suffix,
DROP COLUMN {$prefix}paon_end_number,
DROP COLUMN {$prefix}paon_end_suffix,
DROP COLUMN {$prefix}paon_description,
DROP COLUMN {$prefix}street_description,
DROP COLUMN {$prefix}locality,
DROP COLUMN {$prefix}town,
DROP COLUMN {$prefix}county;
SQL;
		echo "`{$row['table_schema']}`.`{$row['table_name']}`: deleting old fields\r\n\r\n";
		DAO::execute($link, $sql);
	}
	catch(Exception $e)
	{
		echo "\r\n\r\n";
		echo $e->getMessage();
		echo "\r\n\r\n";
		echo $e->getTraceAsString();
		echo "\r\n\r\n";
		exit(1);
	}

}

// Update saved column names
$sql = <<<SQL
SELECT DISTINCT
	table_schema
FROM
	information_schema.`TABLES`
WHERE
	table_schema LIKE '$db'
	AND table_name = 'view_columns'
SQL;
$schemata = DAO::getSingleColumn($link, $sql);
try
{
	echo "\r\n";
	DAO::transaction_start($link);
	foreach($schemata as $schema) {
		echo "`$schema`.`view_columns`: renaming saved column names\r\n";
		DAO::execute($link, "UPDATE `$schema`.view_columns SET colum = 'address_line_1' WHERE colum = 'street_description'");
		DAO::execute($link, "UPDATE `$schema`.view_columns SET colum = 'home_address_line_1' WHERE colum = 'home_street_description'");
		DAO::execute($link, "UPDATE `$schema`.view_columns SET colum = 'work_address_line_1' WHERE colum = 'work_street_description'");
		DAO::execute($link, "UPDATE `$schema`.view_columns SET colum = 'provider_address_line_1' WHERE colum = 'provider_street_description'");
		DAO::execute($link, "UPDATE `$schema`.view_columns SET colum = 'address_line_2' WHERE colum = 'locality'");
		DAO::execute($link, "UPDATE `$schema`.view_columns SET colum = 'home_address_line_2' WHERE colum = 'home_locality'");
		DAO::execute($link, "UPDATE `$schema`.view_columns SET colum = 'work_address_line_2' WHERE colum = 'work_locality'");
		DAO::execute($link, "UPDATE `$schema`.view_columns SET colum = 'provider_address_line_2' WHERE colum = 'provider_locality'");
		DAO::execute($link, "UPDATE `$schema`.view_columns SET colum = 'address_line_3' WHERE colum = 'town'");
		DAO::execute($link, "UPDATE `$schema`.view_columns SET colum = 'home_address_line_3' WHERE colum = 'home_town'");
		DAO::execute($link, "UPDATE `$schema`.view_columns SET colum = 'work_address_line_3' WHERE colum = 'work_town'");
		DAO::execute($link, "UPDATE `$schema`.view_columns SET colum = 'provider_address_line_3' WHERE colum = 'provider_town'");
		DAO::execute($link, "UPDATE `$schema`.view_columns SET colum = 'address_line_4' WHERE colum = 'county'");
		DAO::execute($link, "UPDATE `$schema`.view_columns SET colum = 'home_address_line_4' WHERE colum = 'home_county'");
		DAO::execute($link, "UPDATE `$schema`.view_columns SET colum = 'work_address_line_4' WHERE colum = 'work_county'");
		DAO::execute($link, "UPDATE `$schema`.view_columns SET colum = 'provider_address_line_4' WHERE colum = 'provider_county'");
	}
	DAO::transaction_commit($link);
}
catch(Exception $e)
{
	DAO::transaction_rollback($link);
	echo "\r\n\r\n";
	echo $e->getMessage();
	echo "\r\n\r\n";
	echo $e->getTraceAsString();
	echo "\r\n\r\n";
	exit(1);
}

