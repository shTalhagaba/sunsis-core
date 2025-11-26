<?php
class edit_skills_analysis implements IAction
{
    public function execute(PDO $link)
    {
        $id = isset($_REQUEST['id']) ? $_REQUEST['id'] : '';
        if ($id == '') {
            throw new Exception("Missing querystring argument: id");
        }

        $sa = SkillsAnalysis::loadFromDatabaseById($link, $id);
        $tr = TrainingRecord::loadFromDatabase($link, $sa->tr_id);
	if(DB_NAME == "am_ela" && $tr->practical_period_start_date > '2023-06-31')
        {
            http_redirect('do.php?_action=edit_skills_analysis_ela&id='.$id);
        }
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

        $_SESSION['bc']->add($link, "do.php?_action=edit_skills_analysis&id={$sa->id}", "Edit Skills Analysis");

        $scores = LookupHelper::getDDLKsbScores();
        $score_percentages = $sa->getRplPercentages(); //SkillsAnalysis::getScoreAndPercentageList();
        $total_planned_hours = DAO::getSingleValue($link, "SELECT SUM(del_hours) FROM ob_learner_ksb WHERE skills_analysis_id = '{$sa->id}'");
        $recommended_duration = $framework->duration_in_months == '' ? $framework->getRecommendedDuration($link) : $framework->duration_in_months;
        $recommended_duration = ($tr->duration_practical_period != '') ? $tr->duration_practical_period : $recommended_duration;

	    $duration_list_selected_option = $sa->duration_fa;
	    $duration_list_selected_option = $duration_list_selected_option == '' ? $sa->duration_ba : $duration_list_selected_option;
        $max_duration_list_option = $framework->epa_duration == '' ? $recommended_duration + 3 : $recommended_duration + $framework->epa_duration;
        $training_cost = ($sa->training_cost_fa == 0 || $sa->training_cost_fa == '') ? $tr->training_cost : $sa->training_cost_fa;

        $otj_based_on_6_hrs_pr_week = true;
        if($tr->contracted_hours_per_week < 30)
        {
            $otj_based_on_6_hrs_pr_week = false;
	    $rd = $framework->duration_in_months == '' ? $framework->getRecommendedDuration($link) : $framework->duration_in_months;	
            $max_duration_list_option = ceil( ($rd*30)/$tr->contracted_hours_per_week );
            $max_duration_list_option += 3;
	    $duration_list_selected_option = $sa->minimum_duration_part_time;
        }

        if( SystemConfig::getEntityValue($link, "onboarding_sa_route") == "NON_DL" )
        {
            include_once('tpl_edit_skills_analysis.php');    
        }
        else
        {
            //include_once('tpl_edit_skills_analysis_dl.php');
	    if($tr->id == 886)
            {
                include_once('tpl_edit_skills_analysis_ela.php');
            }
            else
            {
                include_once('tpl_edit_skills_analysis_dl.php');
            }
        }
    }
}
