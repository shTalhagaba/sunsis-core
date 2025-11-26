<?php
class skills_scan_employer_view implements IUnauthenticatedAction
{
    public function execute(PDO $link)
    {
        $skills_analysis_id = isset($_REQUEST['id']) ? $_REQUEST['id'] : ''; 
        $key = isset($_REQUEST['key'])?$_REQUEST['key']:'';
        if(trim($skills_analysis_id) != '' && trim($key) != '')
        {
            if(!OnboardingHelper::isValidSkillsScanEmployerUrl($link, $skills_analysis_id, $key))
            {
                OnboardingHelper::generateErrorPage($link);
                exit;
            }
        }
        else
        {
            OnboardingHelper::generateErrorPage($link);
            exit;
        }

        $sa = SkillsAnalysis::loadFromDatabaseById($link, $skills_analysis_id);
        if(is_null($sa))
        {
            OnboardingHelper::generateErrorPage($link);
            exit;
        }
        $tr = TrainingRecord::loadFromDatabase($link, $sa->tr_id);
        if(is_null($tr))
        {
            OnboardingHelper::generateErrorPage($link);
            exit;
        }
        if($sa->employer_sign != '')
        {
            OnboardingHelper::generateAlreadyCompletedPage($link, $tr->id);
            exit;
        }

        $ob_learner = $tr->getObLearnerRecord($link);

        $provider = Organisation::loadFromDatabase($link, $tr->provider_id);

        $employer = Organisation::loadFromDatabase($link, $tr->employer_id);

        $framework = Framework::loadFromDatabase($link, $tr->framework_id);

	$providerLogo = "images/logos/" . SystemConfig::getEntityValue($link, 'logo');
        $provider = Organisation::loadFromDatabase($link, $tr->provider_id);
        if(!is_null($provider->provider_logo))
        {
            $providerLogo = $provider->provider_logo;
        }

        include_once('tpl_skills_scan_employer_view.php');
    }
}