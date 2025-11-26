<?php
class form_skills_scan implements IUnauthenticatedAction
{
    public function execute(PDO $link)
    {
        $id = isset($_REQUEST['id']) ? $_REQUEST['id'] : ''; // $id is the training record id
        $key = isset($_REQUEST['key'])?$_REQUEST['key']:'';
        // if(trim($id) != '' && trim($key) != '')
        // {
        //     if(!OnboardingLearner::validateKey($link, $id, $key))
        //     {
        //         OnboardingHelper::generateErrorPage($link);
        //         exit;
        //     }
        // }
        // else
        // {
        //     OnboardingHelper::generateErrorPage($link);
        //     exit;
        // }

        $tr = TrainingRecord::loadFromDatabase($link, $id);
        if(is_null($tr))
        {
            OnboardingHelper::generateErrorPage($link);
            exit;
        }
        $ob_learner = $tr->getObLearnerRecord($link);

        $skills_analysis = $tr->getSkillsAnalysis($link);

        if(isset($skills_analysis->signed_by_learner) && $skills_analysis->signed_by_learner == 1)
        {
            OnboardingHelper::generateAlreadyCompletedPage($link, $tr->id);
            exit;
        }

        $QualLevelsDDL = DAO::getResultset($link,"SELECT DISTINCT id, description, NULL FROM lookup_ob_qual_levels ORDER BY id;");
        $PriorAttainDDL = DAO::getResultset($link,"SELECT DISTINCT code, CONCAT(description), NULL FROM central.lookup_prior_attainment WHERE code NOT IN ('101', '102') ORDER BY sorting;");

        $employer = Employer::loadFromDatabase($link, $tr->employer_id);
        $framework = Framework::loadFromDatabase($link, $tr->framework_id);

        $ob_header_image1 = SystemConfig::getEntityValue($link, 'ob_header_image1');
        $ob_header_image2 = SystemConfig::getEntityValue($link, 'ob_header_image2');

        $scroll_logic = 1;

        $provider = Organisation::loadFromDatabase($link, $tr->provider_id);

        $header_image1 = $provider->provider_logo == '' ? SystemConfig::getEntityValue($link, "ob_header_image1") : $provider->provider_logo;

        include_once('tpl_form_skills_scan.php');
    }
}