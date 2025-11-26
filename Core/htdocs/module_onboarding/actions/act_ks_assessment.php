<?php
class ks_assessment implements IUnauthenticatedAction
{
	public function execute(PDO $link)
	{
		$assessment_type = isset($_REQUEST['assessment_type']) ? strtolower($_REQUEST['assessment_type']) : '';
		$learner_id = isset($_REQUEST['id']) ? $_REQUEST['id'] : '';
		$forwarding = isset($_REQUEST['forwarding']) ? $_REQUEST['forwarding'] : '';
		$key = isset($_REQUEST['key']) ? $_REQUEST['key'] : '';

		//referrer validation
		if(!isset($_SERVER['HTTP_REFERER']))
		{
            http_redirect('do.php?_action=form_error');
		}
		else
		{
			$url = SOURCE_LOCAL ? 'http://' : 'https://';
			$url .= SOURCE_LOCAL ? 'sunesis' : DB_NAME;
			$url .= "/do.php?_action=ob_2fa&id={$learner_id}&forwarding={$forwarding}&key={$key}";
            $referrer = str_replace('&invalid=1', '', $_SERVER['HTTP_REFERER']);
//			pr($referrer);
//			pre($url);
			if($referrer != $url && !in_array(DB_NAME, ["am_lead_demo", "am_lead"]))
			{
                http_redirect('do.php?_action=form_error');
			}
		}

		// check if key is form completed key
		if(OnboardingHelper::isFormCompletedKey($link, $learner_id, $forwarding, $key))
		{
            http_redirect('do.php?_action=form_already_completed');
		}

		// validate key
		if(!OnboardingHelper::isValidKey($link, $learner_id, $key))
		{
            http_redirect('do.php?_action=form_error');
		}

		// if valid key then check if the form is already completed
		$already_completed = DAO::getSingleValue($link, "SELECT COUNT(*) FROM ks_assessment WHERE ob_learner_id = '{$learner_id}' AND is_finished = 'Y'");
		if($already_completed > 0)
		{
            http_redirect('do.php?_action=form_already_completed');
		}

		$ob_learner = DAO::getObject($link, "SELECT * FROM ob_learners WHERE id = '{$learner_id}'");
		if(!isset($ob_learner))
		{
            http_redirect('do.php?_action=form_error');
		}

		// if valid key then check if the form is already completed
		if($ob_learner->is_finished == 'Y')
		{
            http_redirect('do.php?_action=form_already_completed');
		}

        $client_name = SystemConfig::getEntityValue($link, "client_name");

		$employer = Organisation::loadFromDatabase($link, $ob_learner->employer_id);
		$location = Location::loadFromDatabase($link, $ob_learner->employer_location_id);

		$listAssessmentTypes = OnboardingHelper::getAssessmentTypesList();
		$ddlKnowledgeOptions = OnboardingHelper::getKnowledgeAnswersDDL();
		$ddlSkillsOptions = OnboardingHelper::getSkillsAnswersDDL();
		$ddlHowLong = OnboardingHelper::getHowLongDDL();
		$ddlYesNo = OnboardingHelper::getYesNoDDL();
		$ddlTopics = OnboardingHelper::getTopicsDDL();
		$ddlSkills = OnboardingHelper::getSkillsDDL();
		$ddlChallanges = OnboardingHelper::getChallangesDDL();
		$ddlUnderstanding = OnboardingHelper::getUnderstandingDDL();
		$ddlJobRoles = OnboardingHelper::getJobRolesDDL();

		$header_image1 = SystemConfig::getEntityValue($link, "ob_header_image1");
		$header_image2 = SystemConfig::getEntityValue($link, "ob_header_image2");

		$assessment_type = $ob_learner->ks_assessment;
		$questions_k = $this->getQuestions($link, $assessment_type, 'k');
		$questions_s = $this->getQuestions($link, $assessment_type, 's');
		$questions_p = $this->getQuestions($link, $assessment_type, 'p');

		switch($assessment_type)
		{
			case 'l2iop':
				$landing_page_heading = 'Level 2 Improving Operational Performance Knowledge and Skills Assessment (Framework)';
				$landing_page_text = 'Thank you for taking time to complete this Knowledge and Skills Initial Assessment. Please put aside time to complete this knowledge and skills analysis as accurately and honestly as possible. This will allow '.$client_name.' to build an individual learning plan based on what you already know, what you can already do and what you need to know and understand more of, as part of your Level 2 Improving Operational Performance Apprenticeship journey.';
				break;
			case 'l3it':
				$landing_page_heading = 'Level 3 Improvement Technician  Knowledge and Skills Initial Assessment';
				$landing_page_text = 'Thank you for taking time to complete this Knowledge and Skills Initial Assessment. Please put aside time to complete this knowledge and skills analysis as accurately and honestly as possible. This will allow '.$client_name.' to build an individual learning plan based on what you already know, what you can already do and what you need to know and understand more of, as part of your Level 3 Improvement Technician Apprenticeship journey.';
				break;
			case 'l4ip':
				$landing_page_heading = 'Level 4 Improvement Practitioner Knowledge and Skills Initial Assessment';
				$landing_page_text = 'Thank you for taking time to complete this Knowledge and Skills Initial Assessment. Please put aside time to complete this knowledge and skills analysis as accurately and honestly as possible. This will allow '.$client_name.' to build an individual learning plan based on what you already know, what you can already do and what you need to know and understand more of, as part of your Level 4 Improvement Practitioner Apprenticeship journey.';
				break;
			case 'lmo':
				$landing_page_heading = 'Lean Manufacturing Operative Knowledge and Skills Initial Assessment';
				$landing_page_text = 'Thank you for taking time to complete this Knowledge and Skills Initial Assessment. Please put aside time to complete this knowledge and skills analysis as accurately and honestly as possible. This will allow '.$client_name.' to build an individual learning plan based on what you already know, what you can already do and what you need to know and understand more of, as part of your Level 2 Lean Manufacturing Operative apprenticeship journey.';
				break;
		}

		$assessment = DAO::getObject($link, "SELECT * FROM ks_assessment WHERE ob_learner_id = '{$ob_learner->id}' AND assessment_type = '{$assessment_type}'");
		if(!isset($assessment->ob_learner_id))
		{
			$assessment = new stdClass();
			$assessment->k_qs = [];
			$assessment->s_qs = [];
			$assessment->pd_qs = [];
			$assessment->p_qs = [];
			$assessment->your_role = null;
			$assessment->job_title = null;
		}
		else
		{
			$assessment->k_qs = json_decode($assessment->k_qs);
			$assessment->s_qs = json_decode($assessment->s_qs);
			$assessment->pd_qs = json_decode($assessment->pd_qs);
			$assessment->p_qs = json_decode($assessment->p_qs);
		}

		include('tpl_ks_assessment.php');
	}

	public function getQuestions(PDO $link, $assessment_type, $question_type)
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
}