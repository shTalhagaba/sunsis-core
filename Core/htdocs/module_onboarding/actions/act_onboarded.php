<?php
class onboarded implements IUnauthenticatedAction
{
	public function execute(PDO $link)
	{
		// Important: id in URL is Training Record ID
		$id = isset($_REQUEST['id'])?$_REQUEST['id']:'';
		$key = isset($_REQUEST['key'])?$_REQUEST['key']:'';

		if(!isset($_SERVER['HTTP_REFERER']))
		{
			$this->generateErrorPage();
			exit;
		}
		elseif($_SERVER['HTTP_REFERER'] != $_SERVER['SCRIPT_URI'].'?_action=onboarding&id='.$id.'&key='.$key && $_SERVER['HTTP_REFERER'] != $_SERVER['SCRIPT_URI'].'?_action=onboarding')
		{
			http_redirect('do.php?_action=onboarding&id='.$id.'&key='.$key);
			exit;
		}

		if($id != '' && $key != '')
		{
			if(!$this->validateKey())
			{
				$this->generateErrorPage();
				exit;
			}
			$learner = TrainingRecord::loadFromDatabase($link, $id);
			if(is_null($learner))
			{
				$this->generateErrorPage();
				exit;
			}
			$ob_learner = DAO::getObject($link, "SELECT ob_learners.* FROM ob_learners INNER JOIN users ON ob_learners.user_id = users.id INNER JOIN tr ON users.username = tr.username WHERE tr.id = '{$learner->id}'");
			if(is_null($ob_learner))
			{
				$this->generateErrorPage();
				exit;
			}
			elseif($ob_learner->is_finished == 'Y')
			{
				$this->generateAlreadyCompletedPage();
				exit;
			}

			$employer_main_site = Location::loadFromDatabase($link, $learner->employer_location_id);
			$college_location_id = DAO::getSingleValue($link, "SELECT locations.id FROM locations WHERE locations.organisations_id = '{$learner->college_id}'");
			$college_main_site = Location::loadFromDatabase($link, $college_location_id);
			$tech_cert = DAO::getObject($link, "SELECT awarding_body, REPLACE(id, '/', '') AS id, title, level, start_date, end_date, awarding_body_reg FROM student_qualifications WHERE tr_id = '{$learner->id}' AND REPLACE(id, '/', '') = '{$ob_learner->tech_cert}'");
			$l2_found_competence = DAO::getObject($link, "SELECT awarding_body, REPLACE(id, '/', '') AS id, title, level, start_date, end_date, awarding_body_reg FROM student_qualifications WHERE tr_id = '{$learner->id}' AND REPLACE(id, '/', '') = '{$ob_learner->l2_found_competence}'");
			$main_aim = DAO::getObject($link, "SELECT awarding_body, REPLACE(id, '/', '') AS id, title, level, start_date, end_date, awarding_body_reg FROM student_qualifications WHERE tr_id = '{$learner->id}' AND REPLACE(id, '/', '') = '{$ob_learner->main_aim}' LIMIT 0, 1");
			$fs_maths = DAO::getObject($link, "SELECT awarding_body, REPLACE(id, '/', '') AS id, title, level, start_date, end_date, awarding_body_reg FROM student_qualifications WHERE tr_id = '{$learner->id}' AND REPLACE(id, '/', '') = '{$ob_learner->fs_maths}'");
			$fs_eng = DAO::getObject($link, "SELECT awarding_body, REPLACE(id, '/', '') AS id, title, level, start_date, end_date, awarding_body_reg FROM student_qualifications WHERE tr_id = '{$learner->id}' AND REPLACE(id, '/', '') = '{$ob_learner->fs_eng}'");
			$fs_ict = DAO::getObject($link, "SELECT awarding_body, REPLACE(id, '/', '') AS id, title, level, start_date, end_date, awarding_body_reg FROM student_qualifications WHERE tr_id = '{$learner->id}' AND REPLACE(id, '/', '') = '{$ob_learner->fs_ict}'");
			$err = DAO::getObject($link, "SELECT awarding_body, REPLACE(id, '/', '') AS id, title, level, start_date, end_date, awarding_body_reg FROM student_qualifications WHERE tr_id = '{$learner->id}' AND REPLACE(id, '/', '') = '60002906'");
			$plts = DAO::getObject($link, "SELECT awarding_body, REPLACE(id, '/', '') AS id, title, level, start_date, end_date, awarding_body_reg FROM student_qualifications WHERE tr_id = '{$learner->id}' AND REPLACE(id, '/', '') = '60020192'");

			$LLDD = array(array('Y', 'Yes'), array('N', 'No'), array('P', 'Prefer not to say'));
			$LLDDCat = array(
				'4' => 'Visual impairment',
				'5' => 'Hearing impairment',
				'6' => 'Disability affecting mobility',
				'7' => 'Profound complex disabilities',
				'8' => 'Social and emotional difficulties',
				'9' => 'Mental health difficulty',
				'10' => 'Moderate learning difficulty',
				'11' => 'Severe learning difficulty',
				'12' => 'Dyslexia',
				'13' => 'Dyscalculia',
				'14' => 'Autism spectrum disorder',
				'15' => 'Asperger\'s syndrome',
				'16' => 'Temporary disability after illness (for example post-viral) or accident',
				'17' => 'Speech, Language and Communication Needs',
				'93' => 'Other physical disability',
				'94' => 'Other specific learning difficulty (e.g. Dyspraxia)',
				'95' => 'Other medical condition (for example epilepsy, asthma, diabetes)',
				'96' => 'Other learning difficulty',
				'97' => 'Other disability',
				'98' => 'Prefer not to say'
			);
			$selected_RUI = explode(',', $ob_learner->RUI);
			$selected_PMC = explode(',', $ob_learner->PMC);
			$selected_llddcat = explode(',', $ob_learner->llddcat);
			$LOE_dropdown = array(array('1', 'Up to 3 months'), array('2', '4-6 months'), array('3', '7-12 months'), array('4', 'more than 12 months'));
			array_unshift($LOE_dropdown, array('','Length of employment',''));
			$EII_dropdown = array(array('5', '0-10 hours per week'), array('6', '11-20 hours per week'), array('7', '21-30 hours per week'), array('8', '30 hours or more per week'));
			array_unshift($EII_dropdown, array('','Hours/week',''));
			$LOU_dropdown = array(array('1', 'unemployed for less than 6 months'), array('2', 'unemployed for 6-11 months'), array('3', 'unemployed for 12-23 months'), array('4', 'unemployed for 24-35 months'), array('5', 'unemployed for over 36 months'));
			array_unshift($LOU_dropdown, array('','Length of unemployment',''));
			$BSI_dropdown = array(array('1', 'JSA'), array('2', 'ESA WRAG'), array('3', 'Another state benefit'), array('4', 'Universal Credit'));
			array_unshift($BSI_dropdown, array('','Select benefit type if applicable',''));
		}
		else
		{
			$this->generateErrorPage();
			exit;
		}

		$ethnicityDDL = DAO::getResultset($link,"SELECT Ethnicity, Ethnicity_Desc, null FROM lis201213.ilr_ethnicity ORDER BY Ethnicity;");
		$qual_grades = DAO::getResultset($link,"SELECT id, description, NULL FROM lookup_gcse_grades ORDER BY id;", DAO::FETCH_NUM);
		$titlesDDl = array(
			array('Mr', 'Mr'),
			array('Mrs', 'Mrs'),
			array('Miss', 'Miss'),
			array('Ms', 'Ms')
		);
		$PriorAttainDDL = DAO::getResultset($link,"SELECT DISTINCT code, CONCAT(description), NULL FROM central.lookup_prior_attainment WHERE code NOT IN ('101', '102') ORDER BY sorting;");
		$QualLevelsDDL = DAO::getResultset($link,"SELECT DISTINCT id, description, NULL FROM lookup_ob_qual_levels ORDER BY id;");
		$framework_type = DAO::getSingleValue($link, "SELECT framework_type FROM student_frameworks INNER JOIN frameworks ON student_frameworks.id = frameworks.id WHERE tr_id = '{$learner->id}'");
		$programme_type = DAO::getSingleValue($link, "SELECT ProgTypeDesc FROM lars201617.CoreReference_LARS_ProgType_Lookup WHERE ProgType = '{$framework_type}'");
		$countries = DAO::getResultset($link, "SELECT id, country_name FROM lookup_countries ORDER BY id;");
		$nationalities = DAO::getResultset($link, "SELECT code, description FROM lookup_country_list ORDER BY description;");

		$username = DAO::getSingleValue($link, "SELECT username FROM users WHERE users.id = '{$ob_learner->user_id}'");

		$header_image1 = SystemConfig::getEntityValue($link, "ob_header_image1");
		$header_image2 = SystemConfig::getEntityValue($link, "ob_header_image2");

		$company_name = "";
		if(DB_NAME == "am_siemens" || DB_NAME == "am_siemens_demo")
			$company_name = "Siemens";
		elseif(DB_NAME == "am_presentation")
			$company_name = "CSoft";
		else
			$company_name = "Perspective";

		include_once('tpl_onboarded.php');
	}

	private function validateKey()
	{
		$id = isset($_REQUEST['id'])?$_REQUEST['id']:'';
		$key = isset($_REQUEST['key'])?$_REQUEST['key']:'';

		if(md5($id.'_sunesis_completed') == $key)
			die($this->generateCompletionPage());
		else
			return $key == md5($id.'_sunesis');
	}

	private function generateErrorPage()
	{
		$header_image1 = "images/logos/siemens/siemens1.png";
		if(in_array(DB_NAME, ["am_demo", "am_barnsley"]))
			$header_image1 = "images/logos/SUNlogo.png";

		echo <<<HTML
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/html" xmlns="http://www.w3.org/1999/html" xmlns="http://www.w3.org/1999/html"
      xmlns="http://www.w3.org/1999/html" xmlns="http://www.w3.org/1999/html">
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<title>Sunesis | Homepage</title>
	<meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
	<link rel="stylesheet" href="/assets/adminlte/bootstrap/css/bootstrap.min.css">
	<link rel="stylesheet" href="/assets/adminlte/dist/css/AdminLTE.min.css">
	<link rel="stylesheet" href="/module_onboarding/css/onboarding.css">
<body>

<nav id="mainNav" class="navbar navbar-default navbar-fixed-top navbar-custom">
	<div class="container">
		<div class="navbar-header page-scroll">
			<a class="navbar-brand" href="#">
				<img height="50px" class="headerlogo" src="$header_image1" />
			</a>
		</div>
	</div>
</nav>

	<div class="nts-secondary-teaser-gradient" style="max-width: 450px; padding: 25px; border-radius: 25px; margin-left: 30%">
		<h2>Invalid Access Credentials</h2><hr>
		<p>The credentials you have supplied are unknown to the system.</p>
		<p>If you are sure that you have used the correct URL as specified in the email you received then contact us at <a style="color: white;" href="mailto:support@perspective-uk.com">support@perspective-uk.com</a>
		and provide the details.</p>
	</div>

	<footer class="main-footer">
		<div class="pull-left">
			<table class="table" style="border-collapse: separate; border-spacing: 0.5em;">
				<tr>
					<td><img width="230px" src="images/logos/siemens/ESFA.png" /></td>
					<td><img src="images/logos/siemens/ESF.png" /></td>
					<td><img src="images/logos/siemens/ofsted.jpg" /></td>
					<td><img src="images/logos/siemens/top70.png" width="200px" height="99px" /></td>
					<td><img src="images/logos/siemens/top100.jpg" width="100px" height="165px" /></td>
				</tr>
			</table>
		</div>
		<div class="pull-right">
			<img src="images/logos/SUNlogo.png" />
		</div>
	</footer>
</body>
</html>
HTML;

	}

	private function generateAlreadyCompletedPage()
	{
		$header_image1 = "images/logos/siemens/siemens1.png";
		if(in_array(DB_NAME, ["am_demo", "am_barnsley"]))
			$header_image1 = "images/logos/SUNlogo.png";

		echo <<<HTML
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/html" xmlns="http://www.w3.org/1999/html" xmlns="http://www.w3.org/1999/html"
      xmlns="http://www.w3.org/1999/html" xmlns="http://www.w3.org/1999/html">
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<title>Sunesis | Homepage</title>
	<meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
	<link rel="stylesheet" href="/assets/adminlte/bootstrap/css/bootstrap.min.css">
	<link rel="stylesheet" href="/assets/adminlte/dist/css/AdminLTE.min.css">
	<link rel="stylesheet" href="/module_onboarding/css/onboarding.css">
<body>

<nav id="mainNav" class="navbar navbar-default navbar-fixed-top navbar-custom">
	<div class="container">
		<div class="navbar-header page-scroll">
			<a class="navbar-brand" href="#">
				<img height="50px" class="headerlogo" src="$header_image1" />
			</a>
		</div>
	</div>
</nav>

	<div class="nts-secondary-teaser-gradient" style="max-width: 450px; padding: 25px; border-radius: 25px; margin-left: 30%">
		<h2>Your form is already completed</h2><hr>
		<p>For further information please contact: <a style="color: white;" href="mailto:siemensprofessionaleducationonboarding.gb@siemens.com">siemensprofessionaleducationonboarding.gb@siemens.com</a></p>
	</div>

	<footer class="main-footer">
		<div class="pull-left">
			<table class="table" style="border-collapse: separate; border-spacing: 0.5em;">
				<tr>
					<td><img width="230px" src="images/logos/siemens/ESFA.png" /></td>
					<td><img src="images/logos/siemens/ESF.png" /></td>
					<td><img src="images/logos/siemens/ofsted.jpg" /></td>
					<td><img src="images/logos/siemens/top70.png" width="200px" height="99px" /></td>
					<td><img src="images/logos/siemens/top100.jpg" width="100px" height="165px" /></td>
				</tr>
			</table>
		</div>
		<div class="pull-right">
			<img src="images/logos/SUNlogo.png" />
		</div>
	</footer>
</body>
</html>
HTML;

	}

	private function generateCompletionPage()
	{
		$header_image1 = "images/logos/siemens/siemens1.png";
		if(in_array(DB_NAME, ["am_demo", "am_barnsley"]))
			$header_image1 = "images/logos/SUNlogo.png";

		echo <<<HTML
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/html" xmlns="http://www.w3.org/1999/html" xmlns="http://www.w3.org/1999/html"
      xmlns="http://www.w3.org/1999/html" xmlns="http://www.w3.org/1999/html">
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<title>Siemens | Onboarding</title>
	<meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
	<link rel="stylesheet" href="/assets/adminlte/bootstrap/css/bootstrap.min.css">
	<link rel="stylesheet" href="/assets/adminlte/dist/css/AdminLTE.min.css">
	<link rel="stylesheet" href="/module_onboarding/css/onboarding.css">
<body>

<nav id="mainNav" class="navbar navbar-default navbar-fixed-top navbar-custom">
	<div class="container">
		<div class="navbar-header page-scroll">
			<a class="navbar-brand" href="#">
				<img height="50px" class="headerlogo" src="$header_image1" />
			</a>
		</div>
	</div>
</nav>

<content id="completionPage">
	<div class="jumbotron">
		<div class="container">
			<div class="nts-secondary-teaser-gradient" style="max-width: 450px; padding: 25px; border-radius: 25px;">
				<h2>Thank you for completing your<br>apprenticeship details. We look<br>forward to you starting in<br>September!</h2>
			</div>
		</div>
	</div>
</content>
<footer class="main-footer">
	<div class="pull-left">
		<table class="table" style="border-collapse: separate; border-spacing: 0.5em;">
			<tr>
				<td><img width="230px" src="images/logos/siemens/ESFA.png" /></td>
				<td><img src="images/logos/siemens/ESF.png" /></td>
				<td><img src="images/logos/siemens/ofsted.jpg" /></td>
				<td><img src="images/logos/siemens/top70.png" width="200px" height="99px" /></td>
				<td><img src="images/logos/siemens/top100.jpg" width="100px" height="165px" /></td>
			</tr>
		</table>
	</div>
	<div class="pull-right">
		<img src="images/logos/SUNlogo.png" />
	</div>
</footer>
</body>
</html>
HTML;

	}
}