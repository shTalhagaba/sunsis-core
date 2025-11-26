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

echo "\nStarted process of updating learners";

$params = [
    'Role' => 1,
    'LastModified' => date('Y-m') . "-01T00:00:00Z",
];

$learnersToUpdate = [];
try
{
    $response = $restClient->post('User/Search', json_encode($params));
    if($response->getHttpCode() == 200)
    {
        $result = json_decode($response->getBody());

        foreach($result AS $row)
        {
            $learnersToUpdate[] = $row->ID;
        }
    }      
}
catch(Exception $e)	
{
    var_dump($e->getMessage());
}

if( count($learnersToUpdate) > 0 )
{
    $onefileLearners = [];
    try
    {
        foreach($learnersToUpdate AS $learnerID)
        {
            $response = $restClient->get('User/' . $learnerID);
            if($response->getHttpCode() == 200)
            {
                $result = json_decode($response->getBody());
                if( isset($result->DOB) && !empty($result->DOB) )
                {
                    $result->DOB = formatOneFileDate($result->DOB);
                }
                if( isset($result->LastModified) && !empty($result->LastModified) )
                {
                    $result->LastModified = formatOneFileDate($result->LastModified, true);
                }
                else
                {
                    $result->LastModified = date('Y-m-d H:i:s');
                }
                $temp = json_encode($result);
                $result->detail = $temp;

                $onefileLearners[] = $result;    
            }   
        }

        DAO::multipleRowInsert($link, 'onefile_learners', $onefileLearners);

        DAO::execute($link, "UPDATE tr INNER JOIN onefile_learners ON (tr.`onefile_id` = onefile_learners.`ID` AND tr.`onefile_username` = onefile_learners.`Username`) SET tr.`l36` = onefile_learners.`Progress`");
    }
    catch(Exception $e)	
    {
        var_dump($e->getMessage());
    }
}

echo "\nCompleted process of updating learner \n";

$link = null;



