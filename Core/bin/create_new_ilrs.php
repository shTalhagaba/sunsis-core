<?php
function __autoload($class_name)
{
	require("../htdocs/lib/$class_name.php");
}

// Limit execution time to 5 minutes
set_time_limit(60 * 5);
ini_set("memory_limit", "368M");

// Arguments: db username pwd
$db = null;
$user = null;
$pwd = null;
if (count($argv) < 4) {
	$handle = fopen("php://stdin", "r");

	echo "\nDatabase: ";
	$db = trim(fgets($handle));

	echo "\nUsername: ";
	$user = trim(fgets($handle));

	if (PHP_OS != "WINNT") {
		echo "\nPassword: ";
		$pwd = getPassword(true);
	} else {
		echo "\nPassword: ";
		$pwd = trim(fgets($handle));
	}

	fclose($handle);
} else {
	$db = $argv[1];
	$user = $argv[2];
	$pwd = $argv[3];
}


// Start new line
#echo "\nStarting";


/* @var $link PDO */
if (PHP_OS == "Linux") {
	$host = '127.0.0.1';
} else {
	$host = '127.0.0.1';
}
try {

	$dsn = "mysql:host=$host;dbname=$db;charset=utf8mb4";

	$options = [
		PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
	];


	// If RDS SSL CA is provided in env, add SSL options
	$sslCa = getenv('PERSPECTIVE_DB_SSL_CA');
	if ($sslCa && file_exists($sslCa)) {
		$options[PDO::MYSQL_ATTR_SSL_CA] = $sslCa;
		$options[PDO::MYSQL_ATTR_SSL_VERIFY_SERVER_CERT] = false;
	}
	
	#die("host: " . $host . ", user: " . $user . ", password: " . $pwd);
	$link = new PDO($dsn, $user, $pwd, $options);
	$link->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
	die('ERROR: ' . $e->getMessage());
}

// Create ILRs for this submission period if missing
try {
	echo "\nStarted for " . $db;
	DAO::transaction_start($link);
	_createNewEmployerResponsiveIlrs($link);
	_createNewLearnerResponsiveIlrs($link);
	DAO::transaction_commit($link);
	echo "\nFinished for " . $db;
} catch (Exception $e) {
	DAO::transaction_rollback($link);
	die($e);
}

die();

/**
 * Create ILRs up until the current submission period
 * @param PDO $link
 */
function _createNewLearnerResponsiveIlrs(PDO $link)
{
	_createNewILRs($link, 5, 1);
}

/**
 * Create ILRs up until the current submission period
 * @param PDO $link
 */
function _createNewEmployerResponsiveIlrs(PDO $link)
{
	_createNewILRs($link, 13, 2);
}


/**
 * @param PDO $link
 * @param int $numSubmissionPeriods 5 = Learner Responsive, 13 = Employer Responsive
 * @param int $fundingStreamType 1 = Learner Responsive, 2 = Employer Responsive
 * @throws InvalidArgumentException
 */
function _createNewILRs(PDO $link, $numSubmissionPeriods, $fundingStreamType)
{
	if (empty($numSubmissionPeriods) || !is_numeric($numSubmissionPeriods)) {
		die("Invalid value for numSubmissionPeriods");
	}
	if (empty($fundingStreamType) || !is_numeric($fundingStreamType)) {
		die("Invalid value for fundingStreamType");
	}

	// Determine the current contract year
	$currentContractYear = DAO::getSingleValue($link, "SELECT contract_year FROM central.lookup_submission_dates "
		. "WHERE contract_type = $fundingStreamType "
		. "AND CURRENT_DATE BETWEEN start_submission_date AND last_submission_date AND submission <> 'W13'");
	if (empty($currentContractYear)) {
		return;
	}

	// Determine current submission period
	$currentSubmissionPeriod = DAO::getSingleValue($link, "SELECT UPPER(submission) FROM central.lookup_submission_dates "
		. "WHERE contract_type = $fundingStreamType "
		. "AND CURRENT_DATE BETWEEN start_submission_date AND last_submission_date AND submission <> 'W13'");
	if (empty($currentSubmissionPeriod)) {
		return; // Cannot always determine submission period for LR funding (W01 starts in November), so just skip it if we cannot determine it
	}

	// Iterate through each period and create a new ILR for the *following* period
	// if it does not yet exist. This approach works so long as we stop iterating *before*
	// the current submission period.
	$i = 1;
	$period = 'W01';
	$followingPeriod = 'W02';
	while ($i < $numSubmissionPeriods && $period < $currentSubmissionPeriod) {
		$sql = <<<HEREDOC
INSERT INTO ilr (L01, L03, A09, ilr, submission, contract_type,
 	tr_id, is_complete, is_valid, is_approved, is_active, contract_id)
SELECT
	ilr1.L01,
	ilr1.L03,
	ilr1.A09,
	ilr1.ilr,
	'$followingPeriod',
	ilr1.contract_type,
	ilr1.tr_id,
	ilr1.is_complete,
	ilr1.is_valid,
	ilr1.is_approved,
	ilr1.is_active,
	ilr1.contract_id
FROM
	ilr AS ilr1 INNER JOIN contracts
		ON ilr1.contract_id = contracts.`id`
	LEFT OUTER JOIN ilr AS ilr2
		ON ilr1.`contract_id` = ilr2.`contract_id`
		AND ilr1.`tr_id` = ilr2.`tr_id`
		AND ilr2.`submission` = '$followingPeriod'
WHERE
	ilr1.`submission` = '$period'
	AND contracts.funding_body = $fundingStreamType
	AND contracts.`contract_year` = $currentContractYear
	AND MID(ilr1.ilr, LOCATE('<L08>', ilr1.ilr) + 5, 1) != 'Y'
	AND ilr2.`submission` IS NULL
HEREDOC;
		DAO::execute($link, $sql);

		$i = $i + 1; // Next period
		$period = sprintf('W%02d', $i); // e.g. W02
		$followingPeriod = sprintf('W%02d', $i + 1); // e.g. W03
	}
}

function getPassword($stars = false)
{
	// Get current style

	$oldStyle = shell_exec('stty -g');

	if ($stars === false) {
		shell_exec('stty -echo');
		$password = rtrim(fgets(STDIN), "\n");
	} else {
		shell_exec('stty -icanon -echo min 1 time 0');

		$password = '';
		while (true) {
			$char = fgetc(STDIN);

			if ($char === "\n") {
				break;
			} else if (ord($char) === 127) {
				if (strlen($password) > 0) {
					fwrite(STDOUT, "\x08 \x08");
					$password = substr($password, 0, -1);
				}
			} else {
				fwrite(STDOUT, "*");
				$password .= $char;
			}
		}
	}

	// Reset old style

	shell_exec('stty ' . $oldStyle);

	// Return the password

	return $password;
}