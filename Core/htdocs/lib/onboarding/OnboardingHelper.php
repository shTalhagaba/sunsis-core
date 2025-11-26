<?php
class OnboardingHelper {

	public static function generateKSAssessmentUrl($ob_learner_id)
	{
		return $_SERVER['SCRIPT_URI'] . "?_action=ob_2fa&id={$ob_learner_id}&forwarding=ksa&key=" . md5($ob_learner_id . '_sunesis');
	}

	public static function generateInitialScreeningUrl($ob_learner_id)
	{
		return $_SERVER['SCRIPT_URI'] . "?_action=ob_2fa&id={$ob_learner_id}&forwarding=ia&key=" . md5($ob_learner_id . '_sunesis');
	}

	public static function generateOnboardingUrl($ob_learner_id)
	{
		return $_SERVER['SCRIPT_URI'] . "?_action=ob_2fa&id={$ob_learner_id}&forwarding=ob&key=" . md5($ob_learner_id . '_sunesis');
	}

	public static function generateEmployerAgreementUrl($id, $employer_id)
    	{
        	return $_SERVER['SCRIPT_URI']."?_action=sign_employer_agreement&id={$id}&employer_id={$employer_id}&key=".md5($id.'_'.$employer_id.'_sunesis');
   	}

	public static function generateEmployerTnaUrl($employer_id)
	{
		return $_SERVER['SCRIPT_URI']."?_action=employer_tna&employer_id={$employer_id}&key=".md5($employer_id.'_sunesis');
	}

	public static function generateEmployerOnboardingSignUrl($tr_id, $contact_id)
	{
        $key = md5($tr_id.'_'.$contact_id.'_sunesis');
        return $_SERVER['SCRIPT_URI']."?_action=sign_app_agreement&l_id={$tr_id}&c_id={$contact_id}&key={$key}";
	}

	public static function isValidKey(PDO $link, $id, $key)
	{
		return $key == md5($id.'_sunesis');
	}

	public static function isFormCompletedKey(PDO $link, $id, $forwarding, $key)
	{
		return $key == md5($id.$forwarding.'_sunesis_completed');
	}

	public static function generateErrorPage(PDO $link)
	{
        $header_image1 = SystemConfig::getEntityValue($link, "ob_header_image1");

        echo <<<HTML
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/html">
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<title>Sunesis | Onboarding</title>
	<meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
	<link rel="stylesheet" href="/assets/adminlte/bootstrap/css/bootstrap.min.css">
	<link rel="stylesheet" href="/assets/adminlte/dist/css/AdminLTE.min.css">
<body>


<div class="jumbotron text-center">
  <h1 class="display-3">Error!</h1>
  <p class="lead"><strong>Invalid Access Credentials</strong></p>
  <hr>
  <p>The credentials you have supplied are unknown to the system.
    If you are sure that you have used the correct URL as specified in the email you received then contact us at support@perspective-uk.com and provide the details.</p>
  <p class="lead">
    <img height="50px" class="headerlogo" src="$header_image1" />
  </p>
</div>

HTML;
        exit;

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
	<title>Sunesis | Onboarding</title>
	<meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
	<link rel="stylesheet" href="/assets/adminlte/bootstrap/css/bootstrap.min.css">
	<link rel="stylesheet" href="/assets/adminlte/dist/css/AdminLTE.min.css">
<body>


<div class="jumbotron text-center">
  <h1 class="display-3">Alert!</h1>
  <p class="lead"><strong>Your form is already completed.</strong></p>
  <hr>
  <p class="lead">
    <img height="50px" class="headerlogo" src="$header_image1" />
  </p>
</div>

HTML;
        exit;


    }

	public static function generateCompletionPage(PDO $link)
	{
		$header_image1 = SystemConfig::getEntityValue($link, "ob_header_image1");

		echo <<<HTML
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/html">
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<title>Sunesis | Onboarding</title>
	<meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
	<link rel="stylesheet" href="/assets/adminlte/bootstrap/css/bootstrap.min.css">
	<link rel="stylesheet" href="/assets/adminlte/dist/css/AdminLTE.min.css">
	<link rel="stylesheet" href="/module_onboarding/css/onboarding.css">
<body>

<nav id="mainNav" class="navbar navbar-default navbar-fixed-top navbar-custom" style="background-color: black;background-image: linear-gradient(to right, black, gold)">
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
				<h2>Thank you for completing your details.<br>We will be in touch again soon regarding your<br>apprenticeship programme.</h2>
			</div>
		</div>
	</div>
</content>
<footer class="main-footer">
	<div class="pull-left">
		<table class="table" style="border-collapse: separate; border-spacing: 0.5em;">
			<tr>
				<td><img width="230px" src="$header_image1" /></td>
				<td><img src="images/logos/siemens/ESF.png" /></td>
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

	public static function getCheckboxesNames()
	{
		return [
			"previous_training",
			"currently_undertaking_training",
			"same_or_lower",
			"genuine_job",
			"substantially_diff",
		];
	}

	public static function getKnowledgeAnswersDDL()
	{
		$ddlKnowledgeOptions = [
			[0, "No understanding (little to no knowledge)"],
			[1, "Basic understanding (limited knowledge requires significant training)"],
			[2, "Good understanding (some additional training required)"],
			[3, "Proficient (capable and experienced in all areas)"],
			[4, "Expert and can train others"],
		];

		return $ddlKnowledgeOptions;
	}

	public static function getKnowledgeAnswersList()
	{
		$listKnowledgeOptions = [
			0 => "No understanding",
			1 => "Basic understanding",
			2 => "Good understanding",
			3 => "Proficient",
			4 => "Expert and can train others",
		];

		return $listKnowledgeOptions;
	}

	public static function getSkillsAnswersDDL()
	{
		$ddlSkillsOptions = [
			[0, "No experience"],
			[1, "Some experience"],
			[2, "Extensive experience"],
			[3, "Expert and can train others"],
		];

		return $ddlSkillsOptions;
	}

	public static function getSkillsAnswersList()
	{
		$listSkillsOptions = [
			0 => "No experience",
			1 => "Some experience",
			2 => "Extensive experience",
			3 => "Expert and can train others",
		];

		return $listSkillsOptions;
	}

	public static function getYesNoList()
	{
		$listYesNo = [
			1 => "Yes",
			2 => "No",
		];

		return $listYesNo;
	}

	public static function getYesNoDDL()
	{
		$ddlYesNo = [
			[1, "Yes"],
			[2, "No"],
		];

		return $ddlYesNo;
	}

	public static function getYesNoListYN()
	{
		$listYesNo = [
			'Y' => "Yes",
			'N' => "No",
		];

		return $listYesNo;
	}

	public static function getYesNoDdlYN()
	{
		$ddlYesNo = [
			['Y', "Yes"],
			['N', "No"],
		];

		return $ddlYesNo;
	}

	public static function getTopicsList()
	{
		$listTopics = [
			1 => "Health and Safety",
			2 => "Computer/digital skills (office apps e.g. word, excel)",
			3 => "English skills",
			4 => "Maths skills",
			5 => "Communication skills",
			6 => "Problem solving and analytical skills",
			7 => "Presentation skills",
			8 => "Change management skills",
			9 => "Managing conflict skills (people management)",
			10 => "Coaching and mentoring skills",
			11 => "Business reporting skills",
			12 => "Project management skills",
			13 => "Strategic planning skills",
			14 => "Data analysis and planning skills",
			15 => "Collaboration and team building skills",
			16 => "Management/Leadership skills",
			17 => "Time management skills",
			18 => "Mental health/Healthy living awareness",
			19 => "Equality and diversity awareness",
			20 => "Policy and procedure development",
			21 => "Other (please state)",
		];

		return $listTopics;
	}

	public static function getTopicsDDL()
	{
		$ddlTopics = [
			[1, "Health and Safety"],
			[2, "Computer/digital skills (office apps e.g. word, excel)"],
			[3, "English skills"],
			[4, "Maths skills"],
			[5, "Communication skills"],
			[6, "Problem solving and analytical skills"],
			[7, "Presentation skills"],
			[8, "Change management skills"],
			[9, "Managing conflict skills (people management)"],
			[10, "Coaching and mentoring skills"],
			[11, "Business reporting skills"],
			[12, "Project management skills"],
			[13, "Strategic planning skills"],
			[14, "Data analysis and planning skills"],
			[15, "Collaboration and team building skills"],
			[16, "Management/Leadership skills"],
			[17, "Time management skills"],
			[18, "Mental health/Healthy living awareness"],
			[19, "Equality and diversity awareness"],
			[20, "Policy and procedure development"],
			[21, "Other (please state)"],
		];

		return $ddlTopics;
	}

	public static function getHowLongList()
	{
		$listHowLong = [
			1 => "Less than one year",
			2 => "1 -2 years",
			3 => "3 - 4 years",
			4 => "5 - 10 years",
			5 => "10+ years",
		];

		return $listHowLong;
	}

	public static function getHowLongDDL()
	{
		$ddlHowLong = [
			[1, "Less than one year"],
			[2, "1 -2 years"],
			[3, "3 - 4 years"],
			[4, "5 - 10 years"],
			[5, "10+ years"],
		];

		return $ddlHowLong;
	}

	public static function getSkillsList()
	{
		$listSkills = [
			1 => "Leadership skills",
			2 => "Team working skills - cross-functional departments",
			3 => "Communication skills",
			4 => "Interview skills",
			5 => "Problem-solving skills",
			6 => "Maths skills - understanding data and problem solving",
			7 => "English skills - e.g. report writing and effective communication",
			8 => "Coaching skills",
			9 => "Digital skills",
			10 => "Other (please state)",
		];

		return $listSkills;
	}

	public static function getSkillsDDL()
	{
		$ddlSkills = [
			[1, "Leadership skills"],
			[2, "Team working skills - cross-functional departments"],
			[3, "Communication skills"],
			[4, "Interview skills"],
			[5, "Problem-solving skills"],
			[6, "Maths skills - understanding data and problem solving"],
			[7, "English skills - e.g. report writing and effective communication"],
			[8, "Coaching skills"],
			[9, "Digital skills"],
			[10, "Other (please state)"],
		];

		return $ddlSkills;
	}

	public static function getChallangesList()
	{
		$listChallanges = [
			1 => "Time and business demands",
			2 => "Work-based culture of learning",
			3 => "Resistance to change",
			4 => "Management commitment",
			5 => "Remote working and availability",
			6 => "None",
			7 => "Other (please state)",
		];

		return $listChallanges;
	}

	public static function getChallangesDDL()
	{
		$ddlChallanges = [
			[1, "Time and business demands"],
			[2, "Work-based culture of learning"],
			[3, "Resistance to change"],
			[4, "Management commitment"],
			[5, "Remote working and availability"],
			[6, "None"],
			[7, "Other (please state)"],
		];

		return $ddlChallanges;
	}

	public static function getUnderstandingList()
	{
		$listUnderstanding = [
			1 => "Full understanding",
			2 => "Some understanding",
			3 => "No understanding",
		];

		return $listUnderstanding;
	}

	public static function getUnderstandingDDL()
	{
		$ddlUnderstanding = [
			[1, "Full understanding"],
			[2, "Some understanding"],
			[3, "No understanding"],
		];

		return $ddlUnderstanding;
	}

	public static function getJobRolesList()
	{
		$listJobRoles = [
			1 => "Production/Assembly",
			2 => "Inspection/Quality Assurance",
			3 => "Logistics/Material Handling",
			4 => "Production processing/Finishing",
		];

		return $listJobRoles;
	}

	public static function getJobRolesDDL()
	{
		$ddlJobRoles = [
			[1, "Production/Assembly", ''],
			[2, "Inspection/Quality Assurance", ''],
			[3, "Logistics/Material Handling", ''],
			[4, "Production processing/Finishing", ''],
		];

		return $ddlJobRoles;
	}

	public static function getAssessmentTypesList()
	{
		$listAssessmentTypes = [
			"l2iop" => "Level 2 Improving Operational Performance",
			"l3it" => "Level 3 Improvement Technician",
			"l4ip" => "Level 4 Improvement Practitioner",
			"lmo" => "Lean Manufacturing Operative"
		];

		return $listAssessmentTypes;
	}

	public static function getAssessmentTypesDDL()
	{
		$ddlAssessmentTypes = [
			["l2iop", "Level 2 Improving Operational Performance"],
			["l3it", "Level 3 Improvement Technician"],
			["l4ip", "Level 4 Improvement Practitioner"],
			["lmo", "Lean Manufacturing Operative"]
		];

		return $ddlAssessmentTypes;
	}

	public static function getQuestions(PDO $link, $assessment_type, $question_type)
	{
		$sql = <<<SQL
SELECT CONCAT(assessment_type, question_id), question_desc
FROM lookup_ks_questions
WHERE assessment_type = '{$assessment_type}' AND question_type = '{$question_type}'
ORDER BY question_id
SQL;
		$questions = DAO::getLookupTable($link, $sql);
		return $questions;
	}

	public static function calculateKS($type, $assessment)
	{
		$result = new stdClass();

		$assessment = !is_array($assessment) ? (array)$assessment : $assessment;

		// total score
		$result->total_score = $type != 's' ? count($assessment)*4 : count($assessment)*3;

		// score earned by learner
		$result->score = array_sum($assessment);

		$temp = array_count_values($assessment);

		// answered 3 or 4 by learner for knowledge elements
		$result->t_3_or_4 = 0;
		if(isset($temp[3]))
			$result->t_3_or_4 += $temp[3];
		if(isset($temp[4]))
			$result->t_3_or_4 += $temp[4];

		// answered 2 or 3 by learner for skills elements
		$result->t_2_or_3 = 0;
		if(isset($temp[2]))
			$result->t_2_or_3 += $temp[2];
		if(isset($temp[3]))
			$result->t_2_or_3 += $temp[3];

		$count_of_questions = count($assessment) == 0 ? 1 : count($assessment);
		// percentage of answered 3 or 4 for knowledge elements
		$result->percentage_3_or_4 =  round( ($result->t_3_or_4/$count_of_questions)*100, 2);

		// percentage of answered 2 or 3 for skills elements
		$result->percentage_2_or_3 =  round( ($result->t_2_or_3/$count_of_questions)*100, 2);

		return $result;
	}
}
