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

echo "\n Started process of adding tlaps";

$response = null;
try
{
    $data = ['OrganisationID' => 2379];

    $response = $restClient->post('Plan/Search', json_encode($data));

    if($response->getHttpCode() == 200)
    {
        $result = json_decode($response->getBody());

        foreach($result AS $row)
        {
            if( isset($row->ID) && isset($row->PlanOn) && !empty($row->PlanOn) )
            {
                $tlap = (object)[
                    'PlanOn' => formatOneFileDate($row->PlanOn, true),
                    'ID' => $row->ID, // Plan ID
                ];

                DAO::saveObjectToTable($link, 'onefile_tlap', $tlap);
            }
        }
    }        
}
catch(Exception $e)	
{
    var_dump($e->getMessage());
}    

echo "\nCompleted process of adding tlaps\n";

$link = null;
