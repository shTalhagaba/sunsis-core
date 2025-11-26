<?php
class view_form_onboarding implements IAction
{
    public function execute(PDO $link)
    {
        $id = isset($_REQUEST['id']) ? $_REQUEST['id'] : '';
        if ($id == '') {
            throw new Exception("Missing querystring argument: id");
        }

        $tr = TrainingRecord::loadFromDatabase($link, $id);
        if (is_null($tr)) {
            throw new Exception("Invalid id");
        }

        $_SESSION['bc']->add($link, "do.php?_action=view_form_onboarding&id={$id}", "View Onboarding Form");

        $ob_learner = $tr->getObLearnerRecord($link);

        $employer = Organisation::loadFromDatabase($link, $ob_learner->employer_id);
        $employer_location = Location::loadFromDatabase($link, $ob_learner->employer_location_id);
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

        $cs = $tr->getCommitmentStatement($link);
        if(!isset($cs))
        {
            $cs = new CommitmentStatement();
        }

        $QualLevelsDDL = DAO::getLookupTable($link,"SELECT DISTINCT id, description FROM lookup_ob_qual_levels ORDER BY id;");
        $PriorAttainDDL = DAO::getLookupTable($link,"SELECT DISTINCT code, CONCAT(description) FROM central.lookup_prior_attainment WHERE code NOT IN ('101', '102') ORDER BY sorting;");

        $ob_header_image1 = SystemConfig::getEntityValue($link, 'ob_header_image1');
        $ob_header_image2 = SystemConfig::getEntityValue($link, 'ob_header_image2');

        $scroll_logic = 1;

        $LLDD = array('Y' => 'Yes', 'N' => 'No', 'P' => 'Prefer not to say');
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
        $LOE_dropdown = array('1' => 'Up to 3 months', '2' => '4-6 months', '3' => '7-12 months', '4' => 'more than 12 months');
        $EII_dropdown = array('5' => '0-10 hours per week', '6' => '11-20 hours per week', '7' => '21-30 hours per week', '8' => '30 hours or more per week');
        $LOU_dropdown = array('1' => 'unemployed for less than 6 months', '2' => 'unemployed for 6-11 months', '3' => 'unemployed for 12-23 months', '4' => 'unemployed for 24-35 months', '5' => 'unemployed for over 36 months');
        $BSI_dropdown = array('1' => 'JSA', '2' => 'ESA WRAG', '3' => 'Another state benefit', '4' => 'Universal Credit');

        $ethnicityDDL = DAO::getLookupTable($link,"SELECT Ethnicity, Ethnicity_Desc FROM lis201213.ilr_ethnicity ORDER BY Ethnicity;");
        $qual_grades = DAO::getLookupTable($link,"SELECT id, description FROM lookup_gcse_grades ORDER BY id;");
        $titlesDDl = [
            'Mr' => 'Mr',
            'Mrs' => 'Mrs',
            'Miss' => 'Miss',
            'Ms' => 'Ms'
        ];

        //$countries = DAO::getLookupTable($link, "SELECT id, country_name FROM lookup_countries ORDER BY id;");
        $countries = DAO::getLookupTable($link, "SELECT id, country_name FROM lookup_countries WHERE id = 76 UNION ALL SELECT id, country_name FROM lookup_countries WHERE id != 76");
        $nationalities = DAO::getLookupTable($link, "SELECT code, description FROM lookup_country_list ORDER BY description;");

        $saved_eligibility_list = $tr->EligibilityList != '' ? explode(',', $tr->EligibilityList) : [];
        $care_leaver_details = $tr->getCareLeaverDetails($link);
        $criminal_conviction_details = $tr->getCriminalConvictionDetails($link);

        $header_image1 = SystemConfig::getEntityValue($link, "ob_header_image1");

        $selected_rui = $tr->RUI != '' ? explode(',', $tr->RUI) : [];
        $selected_pmc = $tr->PMC != '' ? explode(',', $tr->PMC) : [];
        $selected_disclaimer = $tr->disclaimer != '' ? explode(',', $tr->disclaimer) : [];

        $learner_directory = Repository::getRoot() . DIRECTORY_SEPARATOR . 'learners' . DIRECTORY_SEPARATOR . $ob_learner->id . DIRECTORY_SEPARATOR .
            $tr->id . DIRECTORY_SEPARATOR . 'onboarding' . DIRECTORY_SEPARATOR;

        $app_agreement_provider_url = in_array(DB_NAME, ["am_barnsley", "am_barnsley_demo"]) ? "www.barnsley.ac.uk" : "www.test.com";

        include_once('tpl_view_form_onboarding.php');

    }
}