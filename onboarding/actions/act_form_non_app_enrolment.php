<?php
class form_non_app_enrolment implements IUnauthenticatedAction
{
    public function execute(PDO $link)
    {
        $id = isset($_REQUEST['id']) ? $_REQUEST['id'] : '';
        $key = isset($_REQUEST['key'])?$_REQUEST['key']:'';
        if(trim($id) != '' && trim($key) != '')
        {
            if(!OnboardingHelper::isValidNonAppEnrolmentUrl($link, $id, $key))
            {
                http_redirect("do.php?_action=error_page");
                exit;
            }
        }
        else
        {
            http_redirect("do.php?_action=error_page");
            exit;
        }

        $tr = TrainingRecord::loadFromDatabase($link, $id);
        if(is_null($tr))
        {
            http_redirect("do.php?_action=error_page");
            exit;
        }
        $ob_learner = $tr->getObLearnerRecord($link);

        if($tr->is_finished == 'Y')
        {
            http_redirect("do.php?_action=form_already_completed");
            exit;
        }

        $employer = Organisation::loadFromDatabase($link, $tr->employer_id);
        $employer_location = Location::loadFromDatabase($link, $tr->employer_location_id);
        $provider = Organisation::loadFromDatabase($link, $tr->provider_id);
        $provider_location = Location::loadFromDatabase($link, $tr->provider_location_id);
        $subcontractor = null;
        $subcontractor_location = null;
        if($tr->subcontractor_id != '')
        {
            $subcontractor = Organisation::loadFromDatabase($link, $tr->subcontractor_id);
            $subcontractor_location = Location::loadFromDatabase($link, $tr->subcontractor_location_id);
        }
        $framework = Framework::loadFromDatabase($link, $tr->framework_id);
        $skills_analysis = $tr->getSkillsAnalysis($link);

        if(isset($ob_form->signed_by_learner) && $ob_form->signed_by_learner == 1)
        {
            OnboardingHelper::generateAlreadyCompletedPage($link, $tr->id);
            exit;
        }

        $QualLevelsDDL = DAO::getResultset($link,"SELECT DISTINCT id, description, NULL FROM lookup_ob_qual_levels ORDER BY id;");
        $PriorAttainDDL = DAO::getResultset($link,"SELECT DISTINCT code, CONCAT(description), NULL FROM central.lookup_prior_attainment WHERE code NOT IN ('101', '102') ORDER BY sorting;");

        $ob_header_image1 = SystemConfig::getEntityValue($link, 'ob_header_image1');
        $ob_header_image2 = SystemConfig::getEntityValue($link, 'ob_header_image2');

        $scroll_logic = 1;

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
        $selected_llddcat = $tr->llddcat != '' ? explode(',', $tr->llddcat) : [];
        $LOE_dropdown = array(array('1', 'Up to 3 months'), array('2', '4-6 months'), array('3', '7-12 months'), array('4', 'more than 12 months'));
        array_unshift($LOE_dropdown, array('','Length of employment',''));
        $EII_dropdown = array(array('5', '0-10 hours per week'), array('6', '11-20 hours per week'), array('7', '21-30 hours per week'), array('8', '8 Learner is employed for 31+ hours per week'));
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

        $countries = DAO::getResultset($link, "SELECT id, country_name FROM lookup_countries WHERE id = 76 UNION ALL SELECT id, country_name FROM lookup_countries WHERE id != 76;");
        $nationalities = DAO::getResultset($link, "SELECT id, description FROM lookup_nationalities WHERE id = 26 UNION ALL SELECT id, description FROM lookup_nationalities WHERE id != 26;");

        $saved_eligibility_list = $tr->EligibilityList != '' ? explode(',', $tr->EligibilityList) : [];
        $care_leaver_details = $tr->getCareLeaverDetails($link);
        $criminal_conviction_details = $tr->getCriminalConvictionDetails($link);

        $header_image1 = $provider->provider_logo == '' ? SystemConfig::getEntityValue($link, "ob_header_image1") : $provider->provider_logo;

        if(in_array(DB_NAME, ["am_sd_demo", "am_superdrug"]))
        {
            if($employer->manufacturer == 1)
            {
                $header_image1 = 'images/logos/Savers.png';
            }
            elseif($employer->manufacturer == 7)
            {
                $header_image1 = 'images/logos/superdrug.png';
            }
        }

        $_dob = new Date($ob_learner->dob);
        $_diff = $_dob->diff(new Date(date('Y-m-d')));
        $_learner_age = isset($_diff['year']) ? $_diff['year'] : '';
        $ageAtStart = $_learner_age;

        include_once('tpl_form_non_app_enrolment.php');
    }
}