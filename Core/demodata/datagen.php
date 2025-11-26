#!/usr/bin/php
<?php
require_once('includes.php');

if($argc < 4)
{
	exit("Usage: php datagen.php {engage_database} {user} {pwd}");
}

//$db = $argv[1];
$db = 'am';
$user = $argv[2];
$pwd = $argv[3];
$port = 3310; // tunnelled to the Perspective server

// Connect to the database (specify defaults explicitly because otherwise default_port does not seem to work)
if(!($link = @mysqli_connect('localhost', $user, $pwd, $db, $port) ) ) /* @var $link mysqli */
{
	trigger_error("Connection to database '" . $db . "' failed. " . mysqli_connect_error(), E_USER_ERROR);
}

/**
 * Automatic class loading from the library
 */
function __autoload($class_name)
{
   require_once ("../htdocs/lib/$class_name.php");
}


define("MALE", 1);
define("FEMALE", 2);


// Wipe the database and load default organisations, locations and groups
//$result = `mysql -u $user -p$pwd $db < static.sql`;


query_and_echo($link, "START TRANSACTION;");


$sql = <<<HEREDOC
SELECT
	organisations.trading_name,
	organisations.short_name AS `org_short_name`,
	organisations.id AS `employer_id`,
	locations.id AS `employer_location_id`,
	locations.short_name AS `loc_short_name`,
	locations.is_legal_address
FROM
	organisations INNER JOIN locations
		ON organisations.id = locations.organisations_id
ORDER BY
	organisations.id, locations.id
HEREDOC;

// When static.sql is edited, remove this
if($link->query("TRUNCATE users;") == false)
{
	trigger_error(implode($link->errorInfo()), E_USER_ERROR);
};

$buffer = "INSERT INTO users (username, firstnames, surname, employer_id, employer_location_id,"
	. " password, record_status, web_access, gender, ethnicity, work_email, acl_filters, acl_adopted_identities, type) VALUES\n";
if($result = $link->query($sql))
{
	$usernames = array();
	while($row = $st->fetch())
	{
		// Three employees at each location
		for($i = 0; $i < 3; $i++)
		{
			do
			{
				$fullname = random_name_uk($link, $i <= 3 ? MALE : FEMALE);
				$username = preg_replace('/[^a-z]/', '', substr(strtolower($fullname['firstname'][0].$fullname['surname']),0, 20));
			} while (in_array($username, $usernames));
			$usernames[] = $username;
			
			$firstname = addslashes($fullname['firstname']);
			$surname = addslashes($fullname['surname']);
			$gender = ($i <= 3) ? "M" : "F";
			$email = $username.'@test.com';

			if($i == 0)
			{
				if($row['is_legal_address'])
				{
					$acl_filters = $acl_adopted_identities = "'*/".$row['org_short_name']."'";
				}
				else
				{
					$acl_filters = $acl_adopted_identities = "'*/".$row['loc_short_name'].'/'.$row['org_short_name']."'";
				}
			}
			else
			{
				$acl_filters = $acl_adopted_identities = "NULL";
			}
			
			$buffer .= " ('$username', '$firstname', '$surname', {$row['employer_id']}, {$row['employer_location_id']},"
				. " 'password', 1, 1, '$gender', 'WBRI', '$email', $acl_filters, $acl_adopted_identities, 5),\n";
		}
	}
	
	$buffer[strlen($buffer) - 2] = ';';
	query_and_echo($link, $buffer);
}
else
{
	trigger_error(implode($link->errorInfo()), E_USER_ERROR);
}

$sql = <<<HEREDOC
# Add the superuser
INSERT INTO users (username, firstnames, surname, employer_id, employer_location_id, password, record_status, web_access, gender, ethnicity, work_email, acl_filters, acl_adopted_identities)
VALUES ('admin', 'Joe', 'Bloggs', 1, 1, 'severn', 1, 1, 'M', 'NOBT', 'engage@learningtracker.co.uk', NULL, NULL);
HEREDOC;
query_and_echo($link, $sql);

$sql = <<<HEREDOC
# Record superuser's ID
SET @superuser_id := LAST_INSERT_ID();
HEREDOC;
query_and_echo($link, $sql);


$sql = <<<HEREDOC
# Populate user's work address fields
UPDATE
	users INNER JOIN locations
		ON users.employer_location_id = locations.id
SET
	users.work_saon_start_number = locations.saon_start_number,
	users.work_saon_start_suffix = locations.saon_start_suffix,
	users.work_saon_end_number = locations.saon_end_number,
	users.work_saon_end_suffix = locations.saon_end_suffix,
	users.work_saon_description = locations.saon_description,
	users.work_paon_start_number = locations.paon_start_number,
	users.work_paon_start_suffix = locations.paon_start_suffix,
	users.work_paon_end_number = locations.paon_end_number,
	users.work_paon_end_suffix = locations.paon_end_suffix,
	users.work_paon_description = locations.paon_description,
	users.work_street_description = locations.street_description,
	users.work_locality = locations.locality,
	users.work_town = locations.town,
	users.work_county = locations.county,
	users.work_postcode = locations.postcode,
	users.work_telephone = locations.telephone,
	users.work_fax = locations.fax;
HEREDOC;
query_and_echo($link, $sql);



?>

<?php

$link->close();

?>