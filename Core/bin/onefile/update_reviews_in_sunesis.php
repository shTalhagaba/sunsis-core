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

echo "\nStarting process for reviews";


$response = null;
try
{
    $data = ['OrganisationID' => 2379];

    $response = $restClient->post('Review/Search', json_encode($data));

    if($response->getHttpCode() == 200)
    {
        $result = json_decode($response->getBody());

        echo "\nNumber of Review IDs downloaded: " . count($result);

        $rowIdsSaved = 0;
        foreach($result AS $row)
        {
            if(isset($row->ID) && isset($row->ScheduledFor) && !empty($row->ScheduledFor))
            {
                $review = (object)[
                    'ID' => $row->ID,
                    'ScheduledFor' => formatOneFileDate($row->ScheduledFor, true),
                ];

                if(DAO::saveObjectToTable($link, 'onefile_reviews', $review))
                {
                    $rowIdsSaved++;
                }
            }
        }
    }        
}
catch(Exception $e)	
{
    var_dump($e->getMessage());
}

echo "\nNumber of Review IDs saved: " . $rowIdsSaved;

$reviewDetails = [];

$sql = "SELECT
  onefile_reviews.`ID`
FROM
  onefile_reviews
WHERE (
    onefile_reviews.`LearnerID` IS NULL
    OR onefile_reviews.`LearnerID` IN
    (SELECT
      tr.`onefile_id`
    FROM
      tr
    WHERE tr.`status_code` = 1)
  )
  AND (
    onefile_reviews.`AssessorSignedOn` IS NULL
    OR onefile_reviews.`LearnerSignedOn` IS NULL
  )
  ";
$onefileReviewIds = DAO::getSingleColumn($link, $sql);
foreach($onefileReviewIds AS $onefileReviewId)
{
    $response = null;
    try
    {
        $response = $restClient->get('Review/' . $onefileReviewId);
        if($response->getHttpCode() == 200)
        {
            $r = $response->getBody();
            $r = json_decode($r);
            $reviewDetails[] = [
                'LearnerID' => $r->LearnerID,
                'AssessorID' => $r->AssessorID,
                'EmployerID' => $r->EmployerID,
                'CreatedOn' => isset($r->CreatedOn) && !empty($r->CreatedOn) ? formatOneFileDate($r->CreatedOn, true) : null,
                'ScheduledFor' => isset($r->ScheduledFor) && !empty($r->ScheduledFor) ? formatOneFileDate($r->ScheduledFor, true) : null,
                'StartedOn' => isset($r->StartedOn) && !empty($r->StartedOn) ? formatOneFileDate($r->StartedOn, true) : null,
                'AssessorSignedOn' => isset($r->AssessorSignedOn) && !empty($r->AssessorSignedOn) ? formatOneFileDate($r->AssessorSignedOn, true) : null,
                'LearnerSignedOn' => isset($r->LearnerSignedOn) && !empty($r->LearnerSignedOn) ? formatOneFileDate($r->LearnerSignedOn, true) : null,
                'VisitID' => $r->VisitID,
                'Progress' => $r->Progress,
                'ID' => $r->ID,
            ];
        }        
    }
    catch(Exception $e)	
    {
	    var_dump($e->getMessage());
        continue;
    }    
}

echo "\nNumber of Reviews to be saved or updated: " . count($reviewDetails);

$reviewsUpdated = 0;
// save the data
foreach($reviewDetails AS $row)
{
    try
    {
        $rowObject = (object)$row;
        if(DAO::saveObjectToTable($link, "onefile_reviews", $rowObject))
        {
            $reviewsUpdated++;
        }
    }
    catch(Exception $e)	
    {
        var_dump($e->getMessage());
        continue;
    }    
}

echo "\nNumber of Reviews actually saved or updated: " . $reviewsUpdated;

echo "\nCompleted process of updating reviews\n";

$link = null;
