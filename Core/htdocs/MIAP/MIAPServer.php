<?php

require_once dirname(__FILE__) . '/MIAPAutoload.php';

function learnerByDemographics(MIAPStructLearnerByDemographicsRqst $req)
{
	switch($req->Gender)
	{
		case '1':
			$req->Gender = 'M';
			break;
		case '2':
			$req->Gender = 'F';
			break;

	}

	$link = DAO::getConnection();
	$sql = <<<HEREDOC
SELECT
	l45, surname, firstnames, dob, gender, home_postcode, home_email
FROM
	users
WHERE
	surname = '$req->FamilyName'
AND
	firstnames = '$req->GivenName'
AND
	dob = '$req->DateOfBirth'
AND
	gender = '$req->Gender'
AND
	home_postcode = '$req->LastKnownPostCode'

HEREDOC;

	$resultSet = DAO::getResultset($link, $sql);
	$learners = array();
	if($resultSet)
	{
		foreach($resultSet AS $rs)
		{
			$learner = new MIAPStructMIAPLearnerRecord($rs[0], '', $rs[2], '', '', $rs[1], '' );
			$learners[] = $learner;
		}
	}

	$response = new MIAPStructLearnerRecordResp('WSRC0004', '', $req->FamilyName, $req->GivenName, $req->DateOfBirth, $req->Gender, $req->LastKnownPostCode, $learners);
	return $response;
}

function learnerByULN(MIAPStructLearnerByULNRqst $req)
{
	$response = new MIAPStructLearnerRecordResp('WSRC0001');
	return $response;
}

ini_set("soap.wsdl_cache_enabled", "0");
$server = new SoapServer("miap1.wsdl");
$server->addFunction("learnerByDemographics");
$server->addFunction("learnerByULN");
$server->handle();

$learner = new MIAPStructLearnerByDemographicsRqst('FUL', 'SamplePassword', 'SampleUserName', 'Slavin', 'Catherine', '1994-03-03', '2', 'B35 6LR');
pre(learnerByDemographics($learner));

?>
