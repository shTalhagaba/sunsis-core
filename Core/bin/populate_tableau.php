<?php

spl_autoload_register(function ($class_name) {
    require_once __DIR__ . "/../htdocs/lib/{$class_name}.php";
});

require ("../htdocs/lib/tracking/InductionHelper.php");

require (__DIR__."/tableau/ApprenticeshipSupportSessions.php");
require (__DIR__."/tableau/AssessmentPlans.php");
require (__DIR__."/tableau/OperationsAdditionalInformation.php");
require (__DIR__."/tableau/OperationsBILDetails.php");
require (__DIR__."/tableau/OperationsEPA.php");
require (__DIR__."/tableau/OperationsLARDetails.php");
require (__DIR__."/tableau/OperationsLARReport.php");
require (__DIR__."/tableau/OperationsLastLearningEvidence.php");
require (__DIR__."/tableau/OperationsLearnerComplaints.php");
require (__DIR__."/tableau/OperationsLeaverReport.php");
require (__DIR__."/tableau/OperationsLeaversDetails.php");
require (__DIR__."/tableau/OperationsNotes.php");
require (__DIR__."/tableau/OperationsTrackerProgressReport.php");	
require (__DIR__."/tableau/Reviews.php");
require (__DIR__."/tableau/TrainingRecords.php");
require (__DIR__."/tableau/ToleranceReport.php");
require (__DIR__."/tableau/EvidenceProjects.php");
require (__DIR__."/tableau/OperationsTrackerProgressReport1.php");
require (__DIR__."/tableau/OperationTrackersAndSessions.php");
require (__DIR__."/tableau/ILRData.php");
require (__DIR__."/tableau/SessionsAttendance.php");
require (__DIR__."/tableau/PreviousOnLarReport.php");
require (__DIR__."/tableau/ApprenticeshipFinancialDetails.php");
require (__DIR__."/tableau/OperationsLrasReport.php");



set_time_limit(0);
ini_set("memory_limit", "8192M");

$source_pwd = null;
$target_pwd = null;
if(count($argv) < 2)
{
    $handle = fopen ("php://stdin","r");

    echo "\nPassword: ";
    $source_pwd = trim(fgets($handle));

//    echo "\nTarget Password: ";
    $target_pwd = trim(fgets($handle));

    fclose($handle);
}
else
{
    $source_pwd = $argv[1];
    $target_pwd = $argv[1];
}

// Start new line
echo "\n";

$source_host = 'onboardingproduction.cgelwweigmws.eu-west-2.rds.amazonaws.com';
$source_db = "am_baltic";
$source_user = "root";

$target_host = 'onboardingproduction.cgelwweigmws.eu-west-2.rds.amazonaws.com';
$target_db = "am_baltic_tableau";
$target_user = "root";

try
{
    $start = microtime(true);
    $start_log_time = date('Y-m-d H:i:s');

    echo "\nestablishing source connection\n";
    $source_link = new PDO("mysql:host=" . $source_host . ";dbname=" . $source_db . ";port=3306", $source_user, $source_pwd);
    $source_link->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $source_link->setAttribute(PDO::MYSQL_ATTR_USE_BUFFERED_QUERY, false);
    echo "\nestablised source connection\n";

    echo "\nestablishing target connection\n";
    $target_link = new PDO("mysql:host=" . $target_host . ";dbname=" . $target_db . ";port=3306", $target_user, $target_pwd);
    $target_link->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $target_link->setAttribute(PDO::MYSQL_ATTR_USE_BUFFERED_QUERY, false);
    echo "\nestablised target connection\n";

}
catch(PDOException $e)
{
    die('ERROR: ' . $e->getMessage());
}

try {
    echo "\nprocess started\n";

    $tables_results = [];
    $timestamps = date('Y-m-d H:i:s');


    TrainingRecords($source_link, $target_link, $timestamps);	

    $table_and_queries = DAO::getResultset($source_link, "SELECT * FROM tableau WHERE tableau.required = 1 ", DAO::FETCH_ASSOC);
    foreach($table_and_queries AS $q)
    {
        $msg = populateStraightTable($source_link, $target_link, $q['table_sql'], $q['table_name']);
        echo "\n{$msg}\n";
    }

    OperationsTrackerProgressReport1($source_link, $target_link);
    ApprenticeshipSupportSessions($source_link, $target_link, $timestamps);		
    AssessmentPlans($source_link, $target_link);
    OperationsAdditionalInformation($source_link, $target_link);
    OperationsBilDetails($source_link, $target_link);
    OperationsEPA($source_link, $target_link);
    OperationsLARDetails($source_link, $target_link);
    OperationsLARReport($source_link, $target_link);
    OperationsLastLearningEvidence($source_link, $target_link);
    OperationsLearnerComplaints($source_link, $target_link);	
    OperationsLeaverReport($source_link, $target_link);
    OperationsLeaversDetails($source_link, $target_link);
    //OperationsNotes($source_link, $target_link); // Not required
    //OperationsTrackerProgressReport($source_link, $target_link); // Not required
    //Reviews($source_link, $target_link); // Not required		
    ToleranceReport($source_link, $target_link);
    EvidenceProjects($source_link, $target_link);
    OperationTrackersAndSessions($source_link, $target_link);
    ILRData($source_link, $target_link);
    SessionsAttendance($source_link, $target_link);
    PreviousOnLarReport($source_link, $target_link);	
    ApprenticeshipFinancialDetails($source_link, $target_link);	
    OperationsLrasReport($source_link, $target_link);	


    $time_elapsed_secs = microtime(true) - $start;
    $end_log_time = date('Y-m-d H:i:s');


    echo "\nprocess completed in {$time_elapsed_secs} seconds.\n";

    $log = new stdClass();
    $log->start_log_time = $start_log_time;
    $log->end_log_time = $end_log_time;
    $log->duration = $time_elapsed_secs;
    DAO::saveObjectToTable($source_link, "tableau_population_logs", $log);
    unset($log);

}
catch (Exception $ex)
{
    echo "\n" . $ex->getMessage() . "\n";
	reportException($ex->getMessage());
    exit;
}

$source_link = null;
$target_link = null;

function populateStraightTable($source_link, $target_link, $sql, $table_name)
{
    $start = microtime(true);
    $st = $source_link->query($sql);
    if(!$st)
    {
        throw new DatabaseException($source_link, $sql);
    }
    $rows = $st->fetchAll(PDO::FETCH_ASSOC);
    DAO::execute($target_link, "TRUNCATE {$table_name}");
    DAO::multipleRowInsert($target_link, $table_name, $rows);

    $time_elapsed_secs = microtime(true) - $start;
    return "{$table_name} populated in {$time_elapsed_secs} seconds";
}

function reportException($message)
{
    $headers = "MIME-Version: 1.0" . "\r\n";
    $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
    $headers .= "From: Sunesis Cronjob <no-reply@perspective-uk.com>\r\n";
    $params = "-f no-reply@perspective-uk.com";
    @mail("inaam.azmat@perspective-uk.com", "Baltic Tableau Exception", $message, $headers, $params );
}