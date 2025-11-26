<?php
class save_skills_analysis_dl implements IAction
{
    public function execute(PDO $link)
    {

        $id = isset($_POST['id']) ? $_POST['id'] : '';
        if($id == '')
        {
            throw new Exception("Missing querystring argument: id");
        }

        $sa = SkillsAnalysis::loadFromDatabaseById($link, $id);
        $tr = TrainingRecord::loadFromDatabase($link, $sa->tr_id);

        $duration_fa_before_update = $sa->duration_fa;

        $sa->off_the_job_hours_based_on_duration = isset($_REQUEST['off_the_job_hours_based_on_duration']) ? $_REQUEST['off_the_job_hours_based_on_duration'] : '';
	    $sa->off_the_job_hours_based_on_duration = (int)$sa->off_the_job_hours_based_on_duration < 279 ? 279 : $sa->off_the_job_hours_based_on_duration;
        $sa->price_reduction_percentage = isset($_REQUEST['price_reduction_percentage']) ? $_REQUEST['price_reduction_percentage'] : '';
        $sa->lock_for_learner = isset($_REQUEST['lock_for_learner']) ? $_REQUEST['lock_for_learner'] : 0;

        $score_instances = [
            0 => 0,
            1 => 0,
            2 => 0,
            3 => 0,
            4 => 0,
            5 => 0,
        ];
        
        $ksb_updated_entries = [];
        foreach($sa->ksb AS &$row)
        {
            $score = $_POST["score_{$row['id']}"];
            $comments = $_POST["comments_{$row['id']}"];
            $del_hours = $row['del_hours'] != '' ? floatval($row['del_hours']) : 0;
            $score_instances[$score] += $del_hours;

            if($row['score'] != $score || trim($row['comments']) != trim($comments))
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

        $sa->delivery_plan_hours_ba = array_sum($score_instances);
        $sa->delivery_plan_hours_fa = $score_instances[5] + $score_instances[4] + $score_instances[3];
        $sa->percentage_fa = 100 - round( ($sa->delivery_plan_hours_fa/$sa->delivery_plan_hours_ba) * 100 );
        $sa->duration_fa = ceil( $sa->duration_ba * ($sa->percentage_fa / 100) );
        $sa->otj_pw_fa = round( $sa->otj_pw_ba * ($sa->percentage_fa / 100) );
        if(isset($_POST['overwrite_duration_fa']) && $_POST['overwrite_duration_fa'] != '')
        {
            $sa->duration_fa = $_POST['overwrite_duration_fa'];
        }
        // can't occur normally - if price_reduction_percentage is not given
        if( !isset($_POST['price_reduction_percentage']) || $_POST['price_reduction_percentage'] == '' )
        {
            $_p = 100 - $sa->percentage_fa;
            $sa->price_reduction_percentage = ceil( $_p/2 );
        }

        $tnp1_prices = json_decode($sa->tnp1);
        foreach($tnp1_prices AS &$price)
        {
            if($price->reduce == 1)
            {
                //$price->cost = $price->cost - ceil( $price->cost * ($sa->price_reduction_percentage / 100) );
		        $price->cost = ceil( $price->cost * ( (100 - $sa->price_reduction_percentage) / 100 ) );
            }
        }
        $sa->tnp1_fa = json_encode($tnp1_prices);

        $sa->is_finished = $_POST['is_finished'];
        $sa->is_eligible_after_ss = $_POST['is_eligible_after_ss'];
        $sa->rationale_by_provider = $_POST['rationale_by_provider'];
        $sa->provider_sign = $_POST['provider_sign'];
        $sa->provider_sign_date = $_POST['provider_sign'] != '' ? date('Y-m-d') : null;
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
        $sa->minimum_duration_part_time = $sa->duration_fa;
        $part_time_total_contracted_hours_full_apprenticeship = floatval($tr->total_contracted_hours_per_year/12)*floatval($sa->minimum_duration_part_time);
        $sa->part_time_total_contracted_hours_full_apprenticeship = ceil($part_time_total_contracted_hours_full_apprenticeship);
        $sa->part_time_otj_hours = floatval($sa->part_time_total_contracted_hours_full_apprenticeship)*0.2;
        $sa->part_time_otj_hours = ceil($sa->part_time_otj_hours);

        DAO::transaction_start($link);
        try{
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