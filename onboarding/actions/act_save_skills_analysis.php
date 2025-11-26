<?php
class save_skills_analysis implements IAction
{
    public function execute(PDO $link)
    {
        // pre($_POST);
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

        $tr->off_the_job_hours_based_on_duration = $sa->off_the_job_hours_based_on_duration;
        $tr->price_reduction_percentage = $sa->price_reduction_percentage;

        $score_percentages = $sa->getRplPercentages(); //SkillsAnalysis::getScoreAndPercentageList();
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
                $delivery_plan_hours = round($del_hours * $score_percentages["score_5"], 2);
            elseif($score == 4)
                $delivery_plan_hours = round($del_hours * $score_percentages["score_4"], 2);
            elseif($score == 3)
                $delivery_plan_hours = round($del_hours * $score_percentages["score_3"], 2);
            elseif($score == 2)
                $delivery_plan_hours = round($del_hours * $score_percentages["score_2"], 2);
            elseif($score == 1)
                $delivery_plan_hours = $del_hours * $score_percentages["score_1"];
            $delivery_plan_total_fa += $delivery_plan_hours;
            $delivery_plan_total_ba += $del_hours;      

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

	    // non delivery hours route for skills scan
        $sa_percentage = 0;
        $learners_scores = [];
        foreach($_POST AS $key => $score)
        {
            if(substr($key, 0, 6) != 'score_')
                continue;

            if(!isset($learners_scores["score_{$score}"]))
            {
                $learners_scores["score_{$score}"] = 1;
            }
            else
            {
                $learners_scores["score_{$score}"]++;
            }
        }
        foreach($learners_scores AS $key => $value)
        {
            if(isset($score_percentages[$key]))
            {
                $sa_percentage += intval($value) * floatval($score_percentages[$key]);
            }

        }

        $delivery_plan_total_fa = ceil($delivery_plan_total_fa);
        if( SystemConfig::getEntityValue($link, "onboarding_sa_route") == "NON_DL" )
        {
            $percentage_following_assessment = round( 100-$sa_percentage, 2 );
        }
        else
        {
            $percentage_following_assessment = round( ($delivery_plan_total_fa/$delivery_plan_total_ba) * 100, 2 );
        }

        $sa->percentage_fa = $percentage_following_assessment;
        $sa->duration_fa = ceil( $sa->duration_ba * ($sa->percentage_fa / 100) );
        $sa->otj_pw_fa = round( $sa->otj_pw_ba * ($sa->percentage_fa / 100) );
        if(isset($_POST['overwrite_duration_fa']) && $_POST['overwrite_duration_fa'] != '')
        {
            $sa->duration_fa = $_POST['overwrite_duration_fa'];
        }
        if(isset($_POST['percentage_fa']) && $_POST['percentage_fa'] != '')
        {
            $sa->percentage_fa = $_POST['percentage_fa'];
        }

        $tnp1_prices = json_decode($sa->tnp1);
        foreach($tnp1_prices AS &$price)
        {
            if($price->reduce == 1)
            {
                if( $tr->practical_period_start_date > '2022-07-31' && $tr->contracted_hours_per_week >= 30 )
                {
                    $price->cost = $price->cost - ceil( $price->cost * ($sa->price_reduction_percentage / 100) );
                }
                else
                {
                    $price->cost = ceil( $price->cost * ($sa->percentage_fa / 100) );
                }
            }
        }
        $sa->tnp1_fa = json_encode($tnp1_prices);

        $sa->delivery_plan_hours_ba = $delivery_plan_total_ba;
        $sa->delivery_plan_hours_fa = $delivery_plan_total_fa;

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