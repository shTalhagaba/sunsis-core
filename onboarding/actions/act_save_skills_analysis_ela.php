<?php
class save_skills_analysis_ela implements IAction
{
    public function execute(PDO $link)
    {
        // pr($_POST);
        $id = isset($_POST['id']) ? $_POST['id'] : '';
        if($id == '')
        {
            throw new Exception("Missing querystring argument: id");
        }

        $sa = SkillsAnalysis::loadFromDatabaseById($link, $id);
        $tr = TrainingRecord::loadFromDatabase($link, $sa->tr_id);

        $duration_fa_before_update = $sa->duration_fa;

        $sa->off_the_job_hours_based_on_duration = isset($_REQUEST['off_the_job_hours_based_on_duration']) ? $_REQUEST['off_the_job_hours_based_on_duration'] : '';

        $sa->off_the_job_hours_based_on_duration = SkillsAnalysis::checkForMimimumOtjHours($sa->off_the_job_hours_based_on_duration);

        $sa->price_reduction_percentage = isset($_REQUEST['price_reduction_percentage']) ? $_REQUEST['price_reduction_percentage'] : '';

        $sa->lock_for_learner = isset($_REQUEST['lock_for_learner']) ? $_REQUEST['lock_for_learner'] : 0;

        $score_percentages = $sa->getRplPercentages();

        $delivery_plan_total_fa = 0;

        $delivery_plan_total_ba = 0;

        $ksb_updated_entries = [];

        foreach($sa->ksb AS &$row)
        {
            $delivery_plan_hours = 0;

            $score = $_POST["score_{$row['id']}"];

            $comments = $_POST["comments_{$row['id']}"];

            $del_hours = $row['del_hours'] != '' ? floatval($row['del_hours']) : 0;

            if($score == 5)
                $delivery_plan_hours = $del_hours * $score_percentages["score_5"];
            elseif($score == 4)
                $delivery_plan_hours = $del_hours * $score_percentages["score_4"];
            elseif($score == 3)
                $delivery_plan_hours = $del_hours * $score_percentages["score_3"];
            elseif($score == 2)
                $delivery_plan_hours = $del_hours * $score_percentages["score_2"];
            elseif($score == 1)
                $delivery_plan_hours = $del_hours * $score_percentages["score_1"];

            $delivery_plan_total_fa += $delivery_plan_hours;
            $delivery_plan_total_ba += $del_hours;   
			
			$_comments = !is_null($row['comments']) ? trim($row['comments']) : '';            
            if($row['score'] != $score || $_comments != trim($comments))
            {
                $ksb_updated_entries[] = [
                    'id' => $row['id'],
                    'unit_group' => $row['unit_group'],
                    'unit_title' => $row['unit_title'],
                    'evidence_title' => $row['evidence_title'],
                    'score' => 'old value: ' . $row['score'] . ' | new value: ' . $score,
                    'comments' => 'old value: ' . $row['comments'] . ' | new value: ' . $comments,
                ];
            }
            
            // update the score and comments
            $row['score'] = $score;
            $row['comments'] = $comments;
        }
		
		$delivery_plan_total_fa = floor($delivery_plan_total_fa);

        $sa->delivery_plan_hours_ba = $delivery_plan_total_ba;

        $sa->delivery_plan_hours_fa = $delivery_plan_total_fa;

        $percentage_following_assessment = ($delivery_plan_total_fa/$delivery_plan_total_ba) * 100;

        $sa->percentage_fa = round($percentage_following_assessment, 2);

        if(isset($_POST['percentage_fa']) && $_POST['percentage_fa'] != '')
        {
            $sa->percentage_fa = $_POST['percentage_fa'];
        }

        $sa->duration_fa = round( $sa->duration_ba * ($sa->percentage_fa / 100) );
		$sa->otj_pw_fa = round( $sa->otj_pw_ba * ($sa->percentage_fa / 100) );

        if(isset($_POST['overwrite_duration_fa']) && $_POST['overwrite_duration_fa'] != '')
        {
            $sa->duration_fa = $_POST['overwrite_duration_fa'];
        }

        $_tnp1_prices = (is_null($sa->tnp1) || $sa->tnp1 == '0') ? [] : json_decode($sa->tnp1);
        $_tnp1_costs = array_map(function ($ar) {return $ar->cost;}, $_tnp1_prices);
        $_tnp1_total = array_sum(array_map('floatval', $_tnp1_costs));
        
        $total_reduction_amount = (($_tnp1_total + $sa->epa_price)*$sa->price_reduction_percentage)/100;
        $tnp1_prices = json_decode($sa->tnp1);
        foreach($tnp1_prices AS &$price)
        {
            if($price->reduce == 1)
            {
                if( $tr->practical_period_start_date > '2022-07-31' && $tr->contracted_hours_per_week >= 30 )
                {
                    // $price->cost = round( $price->cost - ( $price->cost * ($sa->price_reduction_percentage / 100) ), 2 );
                    $price->cost = round( $price->cost - $total_reduction_amount * ($price->cost / $_tnp1_total), 2);
                }
                else
                {
                    // $price->cost = round( $price->cost * ($sa->percentage_fa / 100), 2 );
                    // $price->cost = round( $price->cost - ( $price->cost * ($sa->price_reduction_percentage / 100) ), 2 );
                    $price->cost = round( $price->cost - $total_reduction_amount * ($price->cost / $_tnp1_total), 2);
                }
            }
        }
        $sa->tnp1_fa = json_encode($tnp1_prices);
		//$sa->epa_price_fa = round( $sa->epa_price - ( $sa->epa_price * ($sa->price_reduction_percentage / 100) ), 2 );

        $sa->is_finished = $_POST['is_finished'];
        $sa->is_eligible_after_ss = $_POST['is_eligible_after_ss'];
        $sa->rationale_by_provider = $_POST['rationale_by_provider'];
        $sa->provider_sign = $_POST['provider_sign'];
        //$sa->provider_sign_date = $_POST['provider_sign'] != '' ? date('Y-m-d') : null;
		$sa->provider_sign_date = ($_POST['provider_sign'] != '' && $sa->provider_sign_date == '') ? date('Y-m-d') : $sa->provider_sign_date;
        $sa->provider_user_id = $_SESSION['user']->id;
        $sa->signed_by_provider = ($_POST['provider_sign'] != '' && $_POST['is_finished'] == 'Y') ? 1 : 0;

        $existing_record = SkillsAnalysis::loadFromDatabaseById($link, $id);
        $log_string = $existing_record->buildAuditLogString($link, $sa);
        if($log_string != '')
        {
            $note = new Note();
            $note->subject = "Skills analysis record edited";
            $note->note = $log_string;
        }

        // part time calculations
        $total_contracted_hours_per_year = ceil( floatval($tr->contracted_hours_per_week) * floatval($tr->weeks_to_be_worked_per_year) );
        $calcs = SkillsAnalysis::calculateOtjForFullTimers($link, $tr->id, $sa->duration_fa);
        if($tr->contracted_hours_per_week < 30)
        {
            $calcs = SkillsAnalysis::calculateOtjForPartTimers($link, $tr->id, $sa->duration_fa);

            $sa->minimum_duration_part_time = $sa->duration_fa;

            $sa->part_time_total_contracted_hours_full_apprenticeship = round(floatval($calcs->actual_weeks_on_programme)*$tr->contracted_hours_per_week);
    
            $sa->part_time_otj_hours = $calcs->off_the_job_hours;

            $sa->minimum_percentage_otj_training = $calcs->off_the_job_hours;
        }

        
		
		DAO::transaction_start($link);
        try
        {
            $sa->save($link);

            DAO::multipleRowInsert($link, 'ob_learner_ksb', $sa->ksb);

            $duration_change_entry = null;
            if($duration_fa_before_update != $sa->duration_fa)
            {
                $duration_change_entry = $sa->duration_fa;
            }
            if(is_array($ksb_updated_entries) && count($ksb_updated_entries) > 0)
            {
                $ksb_log = (object)[
                    'tr_id' => $tr->id,
                    'updated_by' => $_SESSION['user']->id,
                    'updated_detail' => json_encode($ksb_updated_entries),
                    'overwrite_duration_fa' => $duration_change_entry,
                    'skills_analysis_id' => $sa->id,
                ];
                DAO::saveObjectToTable($link, 'ob_learner_ksb_log', $ksb_log);
            }

            if(isset($note) && !is_null($note))
            {
                $note->is_audit_note = true;
                $note->parent_table = 'ob_learner_skills_analysis';
                $note->parent_id = $sa->id;
                $note->created = date('Y-m-d H:i:s');
                $note->save($link);
            }

            DAO::transaction_commit($link);
        }
        catch(Exception $e)
        {
            DAO::transaction_rollback($link, $e);
            throw new WrappedException($e);
        }

        http_redirect($_SESSION['bc']->getPrevious());
    }
}