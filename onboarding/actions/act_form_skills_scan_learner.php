<?php
class form_skills_scan_learner implements IUnauthenticatedAction
{
    public function execute(PDO $link)
    {
        $key = isset($_REQUEST['key'])?$_REQUEST['key']:'';
        if(trim($key) != '')
        {
            $id = OnboardingHelper::getSkillsAnalysisIdFromKey($link, $key);
            if($id == '')
            {
                http_redirect("do.php?_action=error_page");
            }
        }
        else
        {
            http_redirect("do.php?_action=error_page");
        }

        $skills_analysis = SkillsAnalysis::loadFromDatabaseById($link, $id);
        if($skills_analysis->learner_sign != '')
        {
            http_redirect("do.php?_action=form_already_completed");
        }

        $tr = TrainingRecord::loadFromDatabase($link, $skills_analysis->tr_id);
        $ob_learner = $tr->getObLearnerRecord($link);


        $QualLevelsDDL = DAO::getResultset($link,"SELECT DISTINCT id, description, NULL FROM lookup_ob_qual_levels ORDER BY id;");
        $PriorAttainDDL = DAO::getResultset($link,"SELECT DISTINCT code, CONCAT(description), NULL FROM central.lookup_prior_attainment WHERE code NOT IN ('101', '102') ORDER BY sorting;");

        $employer = Employer::loadFromDatabase($link, $tr->employer_id);
        $framework = Framework::loadFromDatabase($link, $tr->framework_id);

        $ob_header_image1 = SystemConfig::getEntityValue($link, 'ob_header_image1');
        $ob_header_image2 = SystemConfig::getEntityValue($link, 'ob_header_image2');

        $scroll_logic = 1;

        $provider = Organisation::loadFromDatabase($link, $tr->provider_id);

        $header_image1 = $provider->provider_logo == '' ? SystemConfig::getEntityValue($link, "ob_header_image1") : $provider->provider_logo;

	    if(in_array(DB_NAME, ["am_superdrug", "am_sd_demo"]))
        {
            $header_image1 = $employer->logoPath();
        }

        $provider_location = $provider->getMainLocation($link);
	    $mainLocation = $employer->getMainLocation($link);

        $ageAtStart = 0;
        if(!empty($tr->practical_period_start_date) && !empty($ob_learner->dob))
        {
            $ageAtStart = Date::dateDiffInfo($tr->practical_period_start_date, $ob_learner->dob);
            $ageAtStart = isset($ageAtStart["year"]) ? $ageAtStart["year"] : 0;
        }

        include_once('tpl_form_skills_scan_learner.php');
    }
}