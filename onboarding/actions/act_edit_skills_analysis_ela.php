<?php
class edit_skills_analysis_ela implements IAction
{
    public function execute(PDO $link)
    {
        $id = isset($_REQUEST['id']) ? $_REQUEST['id'] : '';
        if ($id == '') {
            throw new Exception("Missing querystring argument: id");
        }

        $sa = SkillsAnalysis::loadFromDatabaseById($link, $id);
        $tr = TrainingRecord::loadFromDatabase($link, $sa->tr_id);
        $ob_learner = OnboardingLearner::loadFromDatabase($link, $tr->ob_learner_id);
	    
        if($_SESSION['user']->learners_caseload == 0)
        {
            // do nothing
        }
        elseif($_SESSION['user']->learners_caseload != $ob_learner->caseload_org_id)
        {
            throw new UnauthorizedException("You are not authorised to view this record.");
        }
        
        $framework = Framework::loadFromDatabase($link, $tr->framework_id);

        $_SESSION['bc']->add($link, "do.php?_action=edit_skills_analysis_ela&id={$sa->id}", "Edit Skills Analysis");

        if($tr->contracted_hours_per_week < 30)
        {
            if( $sa->minimum_duration_part_time != '' && intval($sa->minimum_duration_part_time) > intval($framework->duration_in_months) )
            {
                $max_duration_list_option = $framework->epa_duration == '' ? $sa->minimum_duration_part_time + 3 : $sa->minimum_duration_part_time + $framework->epa_duration;
            }
            else
            {
                $rd = $framework->duration_in_months == '' ? $framework->getRecommendedDuration($link) : $framework->duration_in_months;	
                $max_duration_list_option = ceil( ($rd*30)/$tr->contracted_hours_per_week );
                $max_duration_list_option += $framework->epa_duration == '' ? $max_duration_list_option + 3 : $max_duration_list_option + $framework->epa_duration;
            }
        }
        else
        {
            if( $sa->duration_ba != '' && intval($sa->duration_ba) > intval($framework->duration_in_months) )
            {
                $max_duration_list_option = $framework->epa_duration == '' ? $sa->duration_ba + 3 : $sa->duration_ba + $framework->epa_duration;
            }
            else
            {
                $max_duration_list_option = $framework->duration_in_months == '' ? $framework->getRecommendedDuration($link) : $framework->duration_in_months;	
                $max_duration_list_option += $framework->epa_duration == '' ? $max_duration_list_option + 3 : $max_duration_list_option + $framework->epa_duration;
            }
        }

        $scores = LookupHelper::getDDLKsbScores();
        $score_percentages = $sa->getRplPercentages();
        $total_planned_hours = DAO::getSingleValue($link, "SELECT SUM(del_hours) FROM ob_learner_ksb WHERE skills_analysis_id = '{$sa->id}'");
        $recommended_duration = $framework->duration_in_months == '' ? $framework->getRecommendedDuration($link) : $framework->duration_in_months;
        $recommended_duration = ($tr->duration_practical_period != '') ? $tr->duration_practical_period : $recommended_duration;

	    $duration_list_selected_option = $sa->duration_fa;
        $duration_list_selected_option = $duration_list_selected_option == '' ? $sa->duration_ba : $duration_list_selected_option;
        $training_cost = ($sa->training_cost_fa == 0 || $sa->training_cost_fa == '') ? $tr->training_cost : $sa->training_cost_fa;

        $otj_based_on_6_hrs_pr_week = true;
        if($tr->contracted_hours_per_week < 30)
        {
            $otj_based_on_6_hrs_pr_week = false;
            $duration_list_selected_option = $sa->minimum_duration_part_time;
        }

        include('tpl_edit_skills_analysis_ela.php');
    }
}
