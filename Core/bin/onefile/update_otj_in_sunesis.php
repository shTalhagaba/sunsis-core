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

echo "\nStarting process of updating OTJ";

$lastModified = date('Y-m') . "-01";
$onefileIds = DAO::getSingleColumn($link, "SELECT onefile_learners.ID FROM onefile_learners WHERE onefile_learners.LastModified >= '{$lastModified}'");
foreach($onefileIds AS $onefileId)
{
    $response = null;
    try
    {
        $response = $restClient->get('User/' . $onefileId . '/offthejob');
        if($response->getHttpCode() == 200)
        {
	        $otj = [];

            $r = $response->getBody();
            $r = json_decode($r);
            $otj[] = [
                'onefile_learner_id' => $r->ID,
                'contracted_hours' => $r->ContractedHours,
                'target_percentage' => $r->TargetPercentage,
                'planned_otj' => $r->PlannedOTJ,
                'duration' => $r->Duration,
                'method_of_calc' => $r->MethodOfCalculatingOTJ,
                'min_otj' => $r->MinimumOTJ,
                'percent_of_planned' => $r->PercentOfPlanned,
                'actual_hours' => $r->CachedActualHours,
            ];

	        DAO::multipleRowInsert($link, 'onefile_otj', $otj);
        }        
    }
    catch(Exception $e)	
    {
	    var_dump($e->getMessage());
        continue;
    }    
}

echo "\nCompleted process of updating OTJ\n";

$link = null;
