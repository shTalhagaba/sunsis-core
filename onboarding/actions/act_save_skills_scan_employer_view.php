<?php
class save_skills_scan_employer_view implements IUnauthenticatedAction
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

        $sa->employer_comments = isset($_POST['employer_comments']) ? substr($_POST['employer_comments'], 0, 1799) : null;
        $sa->employer_sign = isset($_POST['employer_sign']) ? $_POST['employer_sign'] : null;
        $sa->employer_sign_date = date('Y-m-d');
        $sa->signed_by_employer = 1;
	$sa->employer_sign_name = isset($_POST['employer_sign_name']) ? $_POST['employer_sign_name'] : null;
        $sa->save($link);

        http_redirect('do.php?_action=cs_completed');

    }
}