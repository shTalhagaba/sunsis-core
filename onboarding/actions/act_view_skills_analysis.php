<?php
class view_skills_analysis implements IAction
{
    public function execute(PDO $link)
    {
        $id = isset($_REQUEST['id']) ? $_REQUEST['id'] : '';
        if($id == '')
        {
            throw new Exception("Missing querystring argument: id");
        }

        $sa = SkillsAnalysis::loadFromDatabaseById($link, $id);
        $tr = TrainingRecord::loadFromDatabase($link, $sa->tr_id);
        $ob_learner = OnboardingLearner::loadFromDatabase($link, $tr->ob_learner_id);
	if(DB_NAME == "am_ela")
        {
            if($_SESSION['user']->learners_caseload == 0)
            {
                // do nothing
            }
            elseif($_SESSION['user']->learners_caseload != $ob_learner->caseload_org_id)
            {
                throw new UnauthorizedException("You are not authorised to view this record.");
            }
        }
        $framework = Framework::loadFromDatabase($link, $tr->framework_id);

        $_SESSION['bc']->add($link, "do.php?_action=view_skills_analysis&id={$sa->id}", "View Skills Analysis");
        
        include_once('tpl_view_skills_analysis.php');
    }
}