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

echo "\nStarted process of updateing tlaps\n";

$response = null;
try
{
    $sql = "SELECT
  onefile_tlap.`ID`
FROM
  onefile_tlap
WHERE (
    onefile_tlap.`LearnerID` IS NULL
    OR onefile_tlap.`LearnerID` IN
    (SELECT
      tr.`onefile_id`
    FROM
      tr
    WHERE tr.`status_code` = 1)
  )
  AND (
    onefile_tlap.`AssessorSignedOn` IS NULL
    OR onefile_tlap.`LearnerSignedOn` IS NULL
  )
";

    // update tlaps
    $tlapsIDs = DAO::getSingleColumn($link, $sql);
    foreach($tlapsIDs AS $tlapID)
    {
        $response = $restClient->get( 'Plan/' . $tlapID );
    
        if($response->getHttpCode() == 200)
        {
            $result = $response->getBody();

            $result = json_decode($result);

            if( isset($result->Title) && !empty($result->Title) )
            {
                $result->Title = preg_replace('/[^\x00-\x7F]/', '', $result->Title);
            }
            if( isset($result->PlanOn) && !empty($result->PlanOn) )
            {
                $result->PlanOn = formatOneFileDate($result->PlanOn, true);
            }
            if( isset($result->AssessorSignedOn) && !empty($result->AssessorSignedOn) )
            {
                $result->AssessorSignedOn = formatOneFileDate($result->AssessorSignedOn, true);
            }
            if( isset($result->LearnerSignedOn) && !empty($result->LearnerSignedOn) )
            {
                $result->LearnerSignedOn = formatOneFileDate($result->LearnerSignedOn, true);
            }

            DAO::saveObjectToTable($link, "onefile_tlap", $result);
        }
    }
}
catch(Exception $e)	
{
    var_dump($e->getMessage());
}    

echo "\nCompleted process of updateing tlaps\n";

$link = null;
