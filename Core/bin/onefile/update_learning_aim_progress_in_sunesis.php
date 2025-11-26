<?php

function __autoload($class_name)
{
    require ("../../htdocs/lib/{$class_name}.php");	
}

require("./common_functions.php");

set_time_limit(0);
ini_set("memory_limit", "8192M");

$link = get_db_link( $argv );

$enabled = DAO::getSingleValue($link, "SELECT value FROM configuration WHERE entity = 'onefile.integration'");
if(is_null($enabled) || !$enabled)
{
    $link = null;
    die ("ERROR: Integration is not enabled for this client.");
}



$X_CustomerToken = DAO::getSingleValue($link, "SELECT value FROM configuration WHERE entity = 'onefile.X-CustomerToken'");
$X_TokenIDAndTS = DAO::getSingleValue($link, "SELECT value FROM configuration WHERE entity = 'onefile.X-TokenID'");
$remoteHost = "wsapi.onefile.co.uk";
$X_TokenID = substr($X_TokenIDAndTS, 19);

$X_TokenID = get_x_token_id($link, $X_CustomerToken, $X_TokenIDAndTS, $remoteHost, $X_TokenID);


$restClient = new RestClient();
$restClient->setRemoteHost("wsapi.onefile.co.uk")
    ->setUriBase('/api/v2.1/')
    ->setUseSsl(true)
    ->setUseSslTestMode(false)
    ->setHeaders([
        'X-TokenID' => $X_TokenID,
        'Content-Type' => 'application/json'
    ]);

echo "\nStarting process of updating learning aims";

$lastModified = date('Y-m') . "-01";
$onefileLearnerIDs = DAO::getSingleColumn($link, "SELECT onefile_learners.ID FROM onefile_learners WHERE onefile_learners.LastModified >= '{$lastModified}'");
$onefileLearnerIDs = implode(',', $onefileLearnerIDs);

$progress = [];

$sql = new SQLStatement("SELECT DISTINCT
  student_qualifications.`onefile_learning_aim_id`
FROM
  student_qualifications
  INNER JOIN tr
    ON student_qualifications.`tr_id` = tr.`id`
  INNER JOIN onefile_learners
    ON tr.`onefile_id` = onefile_learners.`ID`
");
$sql->setClause("WHERE student_qualifications.`onefile_learning_aim_id` IS NOT NULL ");
if($onefileLearnerIDs != '')
{
    $sql->setClause("WHERE onefile_learners.`ID` IN ({$onefileLearnerIDs}) ");
}

$learningAimsIds = DAO::getSingleColumn($link, $sql->__toString());
foreach($learningAimsIds AS $learningAimId)
{
    $response = null;
    try
    {
        $response = $restClient->get('LearningAim/' . $learningAimId);
        if($response->getHttpCode() == 200)
        {
            $r = $response->getBody();
            $r = json_decode($r);
            $progress[] = [
                'onefile_learning_aim_id' => $r->ID,
                'onefile_learning_aim_progress' => $r->Progress,
            ];
        }        
    }
    catch(Exception $e)	
    {
        continue;
    }    
}

DAO::multipleRowInsert($link, 'onefile_learning_aims_progress', $progress);

DAO::execute($link, "UPDATE student_qualifications 
INNER JOIN onefile_learning_aims_progress 
ON student_qualifications.`onefile_learning_aim_id` = onefile_learning_aims_progress.`onefile_learning_aim_id` 
SET student_qualifications.`unitsUnderAssessment` = onefile_learning_aims_progress.`onefile_learning_aim_progress` 
");

echo "\nCompleted process of updating learning aims\n";

$link = null;
