<?php
class ob_form implements IUnauthenticatedAction
{
	public function execute(PDO $link)
	{
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
			$qs = $_SERVER['QUERY_STRING'];
			$qs = explode('&', $qs);
			$url = SOURCE_LOCAL ? 'http://' : 'https://';
			$url .= SOURCE_LOCAL ? 'sunesis/' : DB_NAME;

			$url .= "do.php?_action=ob_2fa&id={$learner_id}&forwarding={$forwarding}&key={$key}";
			if($_SERVER['HTTP_REFERER'] != $url)
			{
//				pr($url);
//				pre($_SERVER['HTTP_REFERER']);
				//OnboardingHelper::generateErrorPage($link);
			}
		}

		if(!OnboardingHelper::isValidKey($link, $learner_id, $key))
		{
            http_redirect('do.php?_action=form_error');
		}

		$company_name = "Lead";

		$header_image1 = SystemConfig::getEntityValue($link, "ob_header_image1");
		$header_image2 = SystemConfig::getEntityValue($link, "ob_header_image2");

		// if valid key then check if the form is already completed
		$already_completed = DAO::getSingleValue($link, "SELECT COUNT(*) FROM ob_learners WHERE id = '{$learner_id}' AND is_finished = 'Y'");
		if($already_completed > 0)
		{
            http_redirect('do.php?_action=form_error');
		}

		$ob_learner = DAO::getObject($link, "SELECT * FROM ob_learners WHERE id = '{$learner_id}'");
		if(!isset($ob_learner) || $ob_learner->linked_tr_id == '')
		{
            http_redirect('do.php?_action=form_error');
		}
		$tr = TrainingRecord::loadFromDatabase($link, $ob_learner->linked_tr_id);
		if(is_null($tr))
		{
            http_redirect('do.php?_action=form_error');
		}

		$employer = Employer::loadFromDatabase($link, $ob_learner->employer_id);
		$employer_main_site = Location::loadFromDatabase($link, $ob_learner->employer_location_id);


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
		$selected_llddcat = explode(',', $ob_learner->llddcat);
		$LOE_dropdown = array(array('1', 'Up to 3 months'), array('2', '4-6 months'), array('3', '7-12 months'), array('4', 'more than 12 months'));
		array_unshift($LOE_dropdown, array('','Length of employment',''));
		$EII_dropdown = array(array('5', '0-10 hours per week'), array('6', '11-20 hours per week'), array('7', '21-30 hours per week'), array('8', '30 hours or more per week'));
		array_unshift($EII_dropdown, array('','Hours/week',''));
		$LOU_dropdown = array(array('1', 'unemployed for less than 6 months'), array('2', 'unemployed for 6-11 months'), array('3', 'unemployed for 12-23 months'), array('4', 'unemployed for 24-35 months'), array('5', 'unemployed for over 36 months'));
		array_unshift($LOU_dropdown, array('','Length of unemployment',''));
		$BSI_dropdown = array(array('1', 'JSA'), array('2', 'ESA WRAG'), array('3', 'Another state benefit'), array('4', 'Universal Credit'));
		array_unshift($BSI_dropdown, array('','Select benefit type if applicable',''));

		$ethnicityDDL = DAO::getResultset($link,"SELECT Ethnicity, Ethnicity_Desc, null FROM lis201213.ilr_ethnicity ORDER BY Ethnicity;");
		$qual_grades = DAO::getResultset($link,"SELECT id, description, NULL FROM lookup_gcse_grades ORDER BY id;", DAO::FETCH_NUM);
		$titlesDDl = [
			['Mr', 'Mr'],
			['Mrs', 'Mrs'],
			['Miss', 'Miss'],
			['Ms', 'Ms']
		];
		$PriorAttainDDL = DAO::getResultset($link,"SELECT DISTINCT code, CONCAT(description), NULL FROM central.lookup_prior_attainment WHERE code NOT IN ('101', '102') ORDER BY sorting;");
		$QualLevelsDDL = DAO::getResultset($link,"SELECT DISTINCT id, description, NULL FROM lookup_ob_qual_levels ORDER BY id;");
		$selected_hhs = explode(',', $ob_learner->HHS);
        $selected_bsi = explode(',', $ob_learner->BSI);
        $framework_id = DAO::getSingleValue($link, "SELECT student_frameworks.id FROM student_frameworks WHERE tr_id = '{$tr->id}'");
        $framework = Framework::loadFromDatabase($link, $framework_id);
        $programme_type = DAO::getSingleValue($link, "SELECT ProgTypeDesc FROM lars201617.CoreReference_LARS_ProgType_Lookup WHERE ProgType = '{$framework->framework_type}'");


        include('tpl_ob_form.php');
	}
}