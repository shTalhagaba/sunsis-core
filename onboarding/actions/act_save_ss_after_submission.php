<?php
class save_ss_after_submission implements IUnauthenticatedAction
{
    public function execute(PDO $link)
    {
        //pre($_POST);
        $tr_id = isset($_POST['tr_id']) ? $_POST['tr_id'] : '';
        if($tr_id == '')
        {
            throw new Exception("Missing querystring argument: tr_id");
        }

        $tr = TrainingRecord::loadFromDatabase($link, $tr_id);
        if(is_null($tr))
        {
            throw new Exception("Invalid argument: tr_id");
        }

        $ob_learner = $tr->getObLearnerRecord($link);

        $schedule1 = $tr->getEmployerAgreementSchedule1($link);
        $schedule1_detail = json_decode($schedule1->detail);

        $schedule_price_fields = [
            "training_cost",
            "training_material",
            "reg_and_cert",
            "total_col_train_cost",
            "epa_cost",
            "total_negotiated_price",
            "subcontractor_training_cost",
            "subcontractor_management_cost",
            "additional_costs_by_employer",
            "additional_costs_by_tp",
            "cost_paid_to_barnsley1",
            "cost_paid_to_barnsley2",
            "cost_paid_to_barnsley3",
            "cost_paid_to_barnsley4",
        ];
        foreach($schedule_price_fields AS $_f)
        {
            $schedule1_detail->$_f = isset($_POST[$_f]) ? $_POST[$_f] : $schedule1_detail->$_f;
        }
        $schedule1->detail = json_encode($schedule1_detail);
        $schedule1->save($link);

        $log_details = [];
        $ksb_existing_result = DAO::getResultset($link, "SELECT * FROM ob_learner_ksb WHERE tr_id = '{$tr->id}'", DAO::FETCH_ASSOC);
        foreach($ksb_existing_result AS $ksb_existing_row)
        {
            $id = $ksb_existing_row['id'];
            if(isset($_POST['score_'.$id]) && $_POST['score_'.$id] != $ksb_existing_row['score'])
            {
                // score value has changed
                $log_object = (object)[
                    'id' => $id,
                    'score' => 'old value: ' . $ksb_existing_row['score'] . ' | new value: ' . $_POST['score_'.$id],
                    'comments' => 'old value: ' . $ksb_existing_row['comments'] . ' | new value: ' . $_POST['comments_'.$id],
                ];
                $log_details[] = $log_object;

                $ksb_existing_row['score'] = $_POST['score_'.$id];
                $ksb_existing_row['comments'] = $_POST['comments_'.$id];

                $objKsb = (object)$ksb_existing_row;
                DAO::saveObjectToTable($link, 'ob_learner_ksb', $objKsb);
            }
        }

        //update ob_learner_ksb first
        $ksb_ids = isset($_POST['ksb_ids']) ? explode(",", $_POST['ksb_ids']) : [];
        foreach($ksb_ids AS $ksb_id)
        {
            $ob_learner_ksb_entry = DAO::getObject($link, "SELECT * FROM ob_learner_ksb WHERE id = '{$ksb_id}'");
            if(isset($ob_learner_ksb_entry->id))
            {
                $ob_learner_ksb_entry->score = isset($_POST['score_'.$ksb_id]) ? $_POST['score_'.$ksb_id] : $ob_learner_ksb_entry->score;
                $ob_learner_ksb_entry->del_hours = isset($_POST['del_hours_'.$ksb_id]) ? $_POST['del_hours_'.$ksb_id] : $ob_learner_ksb_entry->del_hours;
                $ob_learner_ksb_entry->comments = isset($_POST['comments_'.$ksb_id]) ? $_POST['comments_'.$ksb_id] : $ob_learner_ksb_entry->comments;

                DAO::saveObjectToTable($link, "ob_learner_ksb", $ob_learner_ksb_entry);
            }
        }

        $result = DAO::getResultset($link, "SELECT * FROM ob_learner_ksb WHERE tr_id = '{$tr->id}' ORDER BY id", DAO::FETCH_ASSOC);
        $delivery_plan_total_fa = 0;
        $delivery_plan_total_ba = 0;
        foreach($result AS $row)
        {
            $delivery_plan_hours = 0;
            $del_hours = $row['del_hours'] != '' ? floatval($row['del_hours']) : 0;
            if($row['score'] == 5)
                $delivery_plan_hours = ceil($del_hours * 0.25);
            elseif($row['score'] == 4)
                $delivery_plan_hours = ceil($del_hours * 0.5);
            elseif($row['score'] == 3)
                $delivery_plan_hours = ceil($del_hours * 0.75);
            elseif($row['score'] == 2)
                $delivery_plan_hours = ceil($del_hours * 0.9);
            elseif($row['score'] == 1)
                $delivery_plan_hours = $del_hours;
            $delivery_plan_total_fa += $delivery_plan_hours;
            $delivery_plan_total_ba += $del_hours;
        }
        $delivery_plan_total_fa = ceil($delivery_plan_total_fa);
        $percentage_following_assessment = round(($delivery_plan_total_fa/$delivery_plan_total_ba) * 100, 0);

        $skills_analysis = $tr->getSkillsAnalysis($link);

        $log = (object)[
            'tr_id' => $tr->id,
            'updated_by' => $_SESSION['user']->id,
            'updated_detail' => json_encode($log_details),
        ];
        if(isset($_POST['overwrite_max_duration_fa']) &&
            $_POST['overwrite_max_duration_fa'] != '' &&
            $_POST['overwrite_max_duration_fa'] != $skills_analysis->max_duration_fa)
        {
            $log->overwrite_max_duration_fa = $_POST['overwrite_max_duration_fa'];
        }
        DAO::saveObjectToTable($link, 'ob_learner_ksb_log', $log);


        $skills_analysis->percentage_fa = $percentage_following_assessment;

        $skills_analysis->max_training_price_minus_epa = floatval($skills_analysis->funding_band_maximum) - floatval($tr->epa_price);

        $percentage_following_assessment = floatval($skills_analysis->percentage_fa)/100;
        $skills_analysis->total_training_price = floatval($schedule1_detail->training_cost) * $percentage_following_assessment;
        $skills_analysis->total_training_price += floatval($schedule1_detail->training_material);
        $skills_analysis->total_training_price += floatval($schedule1_detail->reg_and_cert);
        $skills_analysis->total_training_price = ceil($skills_analysis->total_training_price);

        $skills_analysis->total_nego_price_fa = $skills_analysis->total_training_price + floatval($tr->epa_price);
        $skills_analysis->total_nego_price_fa = ceil($skills_analysis->total_nego_price_fa);

        $skills_analysis->recommended_duration = $_POST['new_recommended_duration'];

        // max_duration_fa is actually minimum duration following assessment
        $skills_analysis->max_duration_fa = round($skills_analysis->recommended_duration*$percentage_following_assessment,0);
        if(isset($_POST['overwrite_max_duration_fa']) &&
            $_POST['overwrite_max_duration_fa'] != '' &&
            $_POST['overwrite_max_duration_fa'] != $skills_analysis->max_duration_fa)
        {
            $skills_analysis->max_duration_fa = $_POST['overwrite_max_duration_fa'];
        }

        $skills_analysis->delivery_plan_hours_ba = $delivery_plan_total_ba;
        $skills_analysis->delivery_plan_hours_fa = $delivery_plan_total_fa;

        $skills_analysis->length_of_programme_practical_period = $skills_analysis->max_duration_fa;

        $skills_analysis->total_contracted_hours_full_apprenticeship = (floatval($tr->total_contracted_hours_per_year)/12)*floatval($skills_analysis->length_of_programme_practical_period);

        $skills_analysis->total_contracted_hours_full_apprenticeship = ceil($skills_analysis->total_contracted_hours_full_apprenticeship);

        $skills_analysis->minimum_percentage_otj_training = $skills_analysis->total_contracted_hours_full_apprenticeship*0.2;
        $skills_analysis->minimum_percentage_otj_training = ceil($skills_analysis->minimum_percentage_otj_training);

        $minimum_duration_part_time = floatval($skills_analysis->length_of_programme_practical_period*30)/floatval($tr->contracted_hours_per_week);
        $skills_analysis->minimum_duration_part_time = ceil($minimum_duration_part_time);

        $part_time_total_contracted_hours_full_apprenticeship = floatval($tr->total_contracted_hours_per_year/12)*floatval($skills_analysis->minimum_duration_part_time);
        $skills_analysis->part_time_total_contracted_hours_full_apprenticeship = ceil($part_time_total_contracted_hours_full_apprenticeship);

        $skills_analysis->part_time_otj_hours = floatval($skills_analysis->part_time_total_contracted_hours_full_apprenticeship)*0.2;
        $skills_analysis->part_time_otj_hours = ceil($skills_analysis->part_time_otj_hours);

        DAO::saveObjectToTable($link, 'ob_learner_skills_analysis', $skills_analysis);

        $tr->length_of_programme_practical_period = $skills_analysis->length_of_programme_practical_period;
        $tr->total_contracted_hours_full_apprenticeship = $skills_analysis->total_contracted_hours_full_apprenticeship;
        $tr->minimum_percentage_otj_training = $skills_analysis->minimum_percentage_otj_training;

        $tr->duration_practical_period = $tr->length_of_programme_practical_period;
        $tr->apprenticeship_duration_inc_epa = intval($tr->duration_practical_period)+3;
        $practical_period_end_date = new Date($tr->practical_period_start_date);
        $practical_period_end_date->addMonths($tr->duration_practical_period);
        $tr->practical_period_end_date = $practical_period_end_date->formatMySQL();
        $apprenticeship_end_date = new Date($tr->apprenticeship_start_date);
        $apprenticeship_end_date->addMonths($tr->apprenticeship_duration_inc_epa);
        $tr->apprenticeship_end_date_inc_epa = $apprenticeship_end_date->formatMySQL();

        DAO::execute($link, "UPDATE ob_learner_quals SET qual_start_date = '{$tr->practical_period_start_date}', qual_end_date = '{$tr->practical_period_end_date}' WHERE tr_id = '{$tr->id}'");

        $tr->generate_pdfs = ["SS", "AA", "CS", "LA", "FL", "S1"];
        $tr->save($link);

        http_redirect('do.php?_action=read_training&id='.$tr->id);
    }
}