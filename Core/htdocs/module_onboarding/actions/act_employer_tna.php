<?php
class employer_tna implements IUnauthenticatedAction
{
	public function execute(PDO $link)
	{
		$employer_id = isset($_REQUEST['employer_id']) ? $_REQUEST['employer_id'] : '';
		$key = isset($_REQUEST['key'])?$_REQUEST['key']:'';
		if(trim($employer_id) != '' && trim($key) != '')
		{
			if(!$this->validateKey($link, $employer_id, $key))
			{
				$this->generateErrorPage($link);
				exit;
			}
		}
		else
		{
			$this->generateErrorPage($link);
			exit;
		}

		$employer = Employer::loadFromDatabase($link, $employer_id);
		if(is_null($employer))
		{
			$this->generateErrorPage($link);
			exit;
		}
		$location_id = DAO::getSingleValue($link, "SELECT id FROM locations WHERE locations.organisations_id = '{$employer->id}' AND is_legal_address = '1'");
		$location = Location::loadFromDatabase($link, $location_id);
		if(is_null($location))
		{
			$this->generateErrorPage($link);
			exit;
		}

		$tna = DAO::getObject($link, "SELECT * FROM employer_tna WHERE employer_id = '{$employer->id}'");
		if(!isset($tna->employer_id))
		{
			$tna = new stdClass();
			$records = DAO::getSingleColumn($link, "SHOW COLUMNS FROM employer_tna");
			foreach($records AS $_key => $value)
				$tna->$value = null;
			$tna->employer_id = $employer->id;
		}
		if($tna->is_completed == "Y")
		{
			$this->generateAlreadyCompletedPage($link);
			exit;
		}

		$listApprenticeships = [
			[1, "Level 2 Lean Manufacturing Operative Standard"],
			[3, "Level 3 Improvement Technician Standard"],
			[4, "Level 4 Improvement Practitioner Standard"],
		];

		$listSkills = [
			[1, "Health and safety"],
			[2, "Computer/digital skills (office apps e.g. word, excel)"],
			[3, "Maths skills"],
			[4, "Communication skills"],
			[5, "English Skills"],
			[6, "Problem solving and analytical skills"],
			[7, "Presentation skills"],
			[8, "Change management skills"],
			[9, "Managing conflict skills (people management)"],
			[10, "Coaching and mentoring skills"],
			[11, "Business reporting skills"],
			[12, "Project management skills"],
			[13, "Strategic planning skills"],
			[14, "Data analysis and planning] skills"],
			[15, "Collaboration and team building skills"],
			[16, "Management/Leadership skills"],
			[17, "Time management skills"],
			[18, "quality and diversity awareness"],
			[19, "Process and procedure development"],
			[20, "Mental health awareness"],
			[21, "Other (please state)"],
		];

		$ddlExistingSkills = [
			[1, "New Skills"],
			[2, "Existing skills employees can enhance"],
			[3, "Mixture of both"]
		];

		$listReasonOfPrevention = [
			[1, "Time and business demands"],
			[2, "Work-based culture of learning"],
			[3, "Resistance to change"],
			[4, "Management Commitment"],
			[5, "Misconception of ability or willingness to learn"],
			[6, "Remote working and availability"],
			[7, "None"],
			[8, "Other (please state)"],
		];

		$useOfSkills = [
			[1, "Taking on additional responsibilities"],
			[2, "Communicating effectively to all departments"],
			[3, "Effective team-working"],
			[4, "Maintaining organised/efficient work areas"],
			[5, "Confidence in identifying problems and agreeing solutions"],
			[6, "Following structured problem solving methodology"],
			[7, "Collating and understanding data that feeds into improvements"],
			[8, "Helping others when asked"],
			[9, "Acting on feedback and reflecting appropriately on own performance"],
			[10, "Using maths skills to create data driven improvements"],
			[11, "Using English skills to generate effective reports and clear legible communications"],
			[12, "Coaching others in effective problem solving techniques"],
			[13, "Other (please state)"],
		];

		$benefitsOfImprovement = [
			[1, "Better communication processes"],
			[2, "Cross-functional effective team working"],
			[3, "Improved understanding of business and improvement areas"],
			[4, "Focused problem solving to drive results"],
			[5, "Change management/effective problem solving culture"],
			[6, "Other (please state)"],
		];

		$listHealthAgenda = [
			[1, "Mental health"],
			[2, "Healthy living"],
			[3, "Wellness"],
			[4, "All of the above"],
			[5, "None of the above"],
		];

		$listOtherAgenda = [
			[1, "Prevent"],
			[2, "Safeguarding"],
			[3, "British Values"],
			[4, "All of the above"],
			[5, "None of the above"],
		];

		$header_image1 = SystemConfig::getEntityValue($link, "ob_header_image1");
		$header_image2 = SystemConfig::getEntityValue($link, "ob_header_image2");
        $client_name = SystemConfig::getEntityValue($link, "client_name");

		include('tpl_employer_tna.php');
	}

	private function validateKey($link, $employer_id, $key)
	{
		if(md5($employer_id.'_sunesis_completed') == $key)
			die($this->generateCompletionPage($link));
		else
			return $key == md5($employer_id.'_sunesis');
	}

	private function generateCompletionPage(PDO $link)
	{
        $header_image1 = SystemConfig::getEntityValue($link, "ob_header_image1");

        echo <<<HTML
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/html">
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<title>Sunesis | Employer TNA Form</title>
	<meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
	<link rel="stylesheet" href="/assets/adminlte/bootstrap/css/bootstrap.min.css">
	<link rel="stylesheet" href="/assets/adminlte/dist/css/AdminLTE.min.css">
<body>


<div class="jumbotron text-center">
  <h1 class="display-3">Thank You!</h1>
  <p class="lead"><strong>This form has been saved successfully.</strong></p>
  <hr>
  <p class="lead">
    <img height="50px" class="headerlogo" src="$header_image1" />
  </p>
</div>

HTML;

	}

	private function generateErrorPage(PDO $link)
	{
        $header_image1 = SystemConfig::getEntityValue($link, "ob_header_image1");

        echo <<<HTML
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/html">
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<title>Sunesis | Employer TNA Form</title>
	<meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
	<link rel="stylesheet" href="/assets/adminlte/bootstrap/css/bootstrap.min.css">
	<link rel="stylesheet" href="/assets/adminlte/dist/css/AdminLTE.min.css">
<body>


<div class="jumbotron text-center">
  <h1 class="display-3">Error!</h1>
  <p class="lead"><strong>Invalid details</strong></p>
  <hr>
  <p class="lead">
    <img height="50px" class="headerlogo" src="$header_image1" />
  </p>
</div>

HTML;

	}

    public static function generateAlreadyCompletedPage(PDO $link)
    {
        $header_image1 = SystemConfig::getEntityValue($link, "ob_header_image1");

        echo <<<HTML
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/html">
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<title>Sunesis | Employer TNA Form</title>
	<meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
	<link rel="stylesheet" href="/assets/adminlte/bootstrap/css/bootstrap.min.css">
	<link rel="stylesheet" href="/assets/adminlte/dist/css/AdminLTE.min.css">
<body>


<div class="jumbotron text-center">
  <h1 class="display-3">Alert!</h1>
  <p class="lead"><strong>This form has already been completed.</strong></p>
  <hr>
  <p class="lead">
    <img height="50px" class="headerlogo" src="$header_image1" />
  </p>
</div>

HTML;

    }

}