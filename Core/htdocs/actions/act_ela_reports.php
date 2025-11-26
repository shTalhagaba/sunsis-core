<?php
class ela_reports implements IAction
{

    public $current_contract_year = null;

    public function execute(PDO $link)
    {
        $age_band_filter = isset($_REQUEST['age_band'])?$_REQUEST['age_band']:'All age_band';
        $qar_type_filter = isset($_REQUEST['qar_type'])?$_REQUEST['qar_type']:'Overall';
        $level_filter = isset($_REQUEST['level'])?$_REQUEST['level']:'All level';
        $learner_type_filter = 'All learner type';
        $employer_type_filter = 'All employer type';
        $at_risk_filter = isset($_REQUEST['at_risk'])?$_REQUEST['at_risk']:'1';
        $export = isset($_REQUEST['export'])?$_REQUEST['export']:'0';
        $best_case_filter = isset($_REQUEST['best_case'])?$_REQUEST['best_case']:'Actual';
        $panel = isset($_REQUEST['panel'])?$_REQUEST['panel']:'';
        $tab = isset($_REQUEST['tab'])?$_REQUEST['tab']:'summary';
        if($age_band_filter=='19 ')
            $age_band_filter =  '19+';
        if($age_band_filter=='24 ')
            $age_band_filter =  '24+';

        $contract_year = 2025;
        
        if($export==1)
        {
            header("Content-Type: application/vnd.ms-excel");
            header('Content-Disposition: attachment; filename="Report_Dashboard.CSV"');

            // Internet Explorer requires two extra headers when downloading files over HTTPS
            if(strpos($_SERVER['HTTP_USER_AGENT'], 'MSIE') !== false)
            {
                header('Pragma: public');
                header('Cache-Control: max-age=0');
            }

            $period = DAO::getObject($link, "SELECT * FROM central.lookup_submission_dates WHERE CURDATE() BETWEEN start_submission_date AND last_submission_date ORDER BY contract_year DESC LIMIT 1;");
            $year = DAO::getObject($link, "SELECT * FROM contracts where contract_year = '$contract_year' ORDER BY contract_year DESC LIMIT 1;");

            $period_start_date = $period->start_submission_date;
            $period_end_date = $period->last_submission_date;
            $year_start_date = $year->start_date;
            $year_end_date = $year->end_date;

            $submission = DAO::getSingleValue($link, "SELECT submission AS submission FROM central.`lookup_submission_dates` WHERE CURDATE() BETWEEN start_submission_date AND last_submission_date and contract_year = '$contract_year' ORDER BY contract_year DESC LIMIT 1;");
            $trs = DAO::getSingleValue($link, "SELECT GROUP_CONCAT(tr_id) FROM ilr WHERE submission = '$submission' AND contract_id IN (SELECT id FROM contracts WHERE contract_year = (SELECT contract_year FROM contracts where contract_year = '$contract_year' ORDER BY contract_year DESC LIMIT 1)) AND EXTRACTVALUE(ilr, '/Learner/LearningDelivery[AimType=1]/Outcome')=8");

            $sql = "SELECT 
            concat(firstnames,' ',surname) AS assessor
            ,(SELECT COUNT(id) FROM tr WHERE bil_withdrawal is null and start_date >= '$period_start_date' AND start_date <='$period_end_date' AND users.id = tr.assessor) AS starts_current_period
            ,(SELECT GROUP_CONCAT(id) FROM tr WHERE bil_withdrawal is null and start_date >= '$period_start_date' AND start_date <='$period_end_date' AND users.id = tr.assessor) AS starts_current_period_trs
            ,(SELECT COUNT(id) FROM tr WHERE bil_withdrawal is null and start_date >= '$year_start_date' AND start_date <='$year_end_date' AND users.id = tr.assessor) AS starts_current_year
            ,(SELECT GROUP_CONCAT(id) FROM tr WHERE bil_withdrawal is null and start_date >= '$year_start_date' AND start_date <='$year_end_date' AND users.id = tr.assessor) AS starts_current_year_trs
            ,(SELECT COUNT(id) FROM tr WHERE status_code = 1 and bil_withdrawal is null and users.id = tr.assessor) AS live
            ,(SELECT GROUP_CONCAT(id) FROM tr WHERE status_code = 1 and bil_withdrawal is null AND users.id = tr.assessor) AS live_trs
            ,(SELECT COUNT(id) FROM tr WHERE (status_code = 3 or bil_withdrawal = 2) AND users.id = tr.assessor) AS withdrawals
            ,(SELECT GROUP_CONCAT(id) FROM tr WHERE (status_code = 3 or bil_withdrawal = 2) AND users.id = tr.assessor) AS withdrawals_trs
            ,(SELECT COUNT(id) FROM tr WHERE status_code = 1 and bil_withdrawal is null AND target_date > CURDATE() AND users.id = tr.assessor) AS funded
            ,(SELECT GROUP_CONCAT(id) FROM tr WHERE status_code = 1 and bil_withdrawal is null AND target_date > CURDATE() AND users.id = tr.assessor) AS funded_trs
            ,(SELECT COUNT(id) FROM tr WHERE status_code = 1 and bil_withdrawal is null AND target_date <= CURDATE() AND users.id = tr.assessor) AS oof
            ,(SELECT GROUP_CONCAT(id) FROM tr WHERE status_code = 1 and bil_withdrawal is null AND target_date <= CURDATE() AND users.id = tr.assessor) AS oof_trs
            ,(SELECT COUNT(id) FROM tr WHERE (status_code = 6 or bil_withdrawal = 1) AND users.id = tr.assessor and id not in ($trs)) AS bil
            ,(SELECT GROUP_CONCAT(id) FROM tr WHERE (status_code = 6 or bil_withdrawal = 1) AND users.id = tr.assessor and id not in ($trs)) AS bil_trs
            ,(SELECT COUNT(id) FROM tr WHERE (status_code = 6 or bil_withdrawal = 1) AND users.id = tr.assessor AND l03 NOT IN (SELECT l03 FROM tr AS future WHERE future.start_date > tr.start_date) and id not in ($trs)) AS pending_bil
            ,(SELECT GROUP_CONCAT(id) FROM tr WHERE (status_code = 6 or bil_withdrawal = 1) AND users.id = tr.assessor AND l03 NOT IN (SELECT l03 FROM tr AS future WHERE future.start_date > tr.start_date) and id not in ($trs)) AS pending_bil_trs
            ,(SELECT COUNT(id) FROM tr WHERE status_code = 1 and bil_withdrawal is null AND users.id = tr.assessor AND onefile_id IN (SELECT ID FROM onefile_learners WHERE progress>= 90) and id not in ($trs)) AS progress
            ,(SELECT GROUP_CONCAT(id) FROM tr WHERE status_code = 1 and bil_withdrawal is null and users.id = tr.assessor AND onefile_id IN (SELECT ID FROM onefile_learners WHERE progress>= 90) and id not in ($trs)) AS progress_trs
            ,(SELECT COUNT(id) FROM tr WHERE bil_withdrawal is null and target_date >= '$period_start_date' AND target_date <='$period_end_date' AND users.id = tr.assessor and id not in ($trs)) AS due_current_period
            ,(SELECT GROUP_CONCAT(id) FROM tr WHERE bil_withdrawal is null and target_date >= '$period_start_date' AND target_date <='$period_end_date' AND users.id = tr.assessor and id not in ($trs)) AS due_current_period_trs
            ,(SELECT COUNT(id) FROM tr WHERE status_code = 2 AND GREATEST(closure_date, COALESCE(achievement_date, '0000-00-00')) >= '$period_start_date' AND GREATEST(closure_date, COALESCE(achievement_date, '0000-00-00')) <='$period_end_date' AND users.id = tr.assessor) AS achievers_current_period
            ,(SELECT GROUP_CONCAT(id) FROM tr WHERE status_code = 2 AND GREATEST(closure_date, COALESCE(achievement_date, '0000-00-00')) >= '$period_start_date' AND GREATEST(closure_date, COALESCE(achievement_date, '0000-00-00')) <='$period_end_date' AND users.id = tr.assessor) AS achievers_current_period_trs
            ,(SELECT COUNT(id) FROM tr WHERE status_code = 2 and GREATEST(closure_date, COALESCE(achievement_date, '0000-00-00')) >= '$year_start_date' AND GREATEST(closure_date, COALESCE(achievement_date, '0000-00-00')) <='$year_end_date' AND users.id = tr.assessor) AS achievers_current_year
            ,(SELECT GROUP_CONCAT(id) FROM tr WHERE status_code = 2 AND GREATEST(closure_date, COALESCE(achievement_date, '0000-00-00')) >= '$year_start_date' AND GREATEST(closure_date, COALESCE(achievement_date, '0000-00-00')) <='$year_end_date' AND users.id = tr.assessor) AS achievers_current_year_trs
            ,(SELECT COUNT(id) FROM tr WHERE (status_code = 3 or bil_withdrawal = 2) AND closure_date >= '$period_start_date' AND closure_date <='$period_end_date' AND users.id = tr.assessor) AS withdrawals_current_period
            ,(SELECT GROUP_CONCAT(id) FROM tr WHERE (status_code = 3 or bil_withdrawal = 2) AND closure_date >= '$period_start_date' AND closure_date <='$period_end_date' AND users.id = tr.assessor) AS withdrawals_current_period_trs
            ,(SELECT COUNT(id) FROM tr WHERE (status_code = 3 or bil_withdrawal = 2) AND closure_date >= '$year_start_date' AND closure_date <='$year_end_date' AND users.id = tr.assessor) AS withdrawals_current_year
            ,(SELECT GROUP_CONCAT(id) FROM tr WHERE (status_code = 3 or bil_withdrawal = 2) AND closure_date >= '$year_start_date' AND closure_date <='$year_end_date' AND users.id = tr.assessor) AS withdrawals_current_year_trs
            ,(SELECT COUNT(id) FROM tr WHERE start_date < (NOW() - INTERVAL 10 WEEK) and status_code = 1 and bil_withdrawal is null AND users.id = tr.assessor AND (onefile_id IS NULL OR onefile_id NOT IN (SELECT LearnerID FROM onefile_reviews WHERE StartedOn > DATE_SUB(NOW(), INTERVAL 10 WEEK))) and id not in ($trs)) AS no_reviews
            ,(SELECT GROUP_CONCAT(id) FROM tr WHERE start_date < (NOW() - INTERVAL 10 WEEK) and status_code = 1 and bil_withdrawal is null and users.id = tr.assessor AND (onefile_id IS NULL OR onefile_id NOT IN (SELECT LearnerID FROM onefile_reviews WHERE StartedOn > DATE_SUB(NOW(), INTERVAL 10 WEEK))) and id not in ($trs)) AS no_reviews_trs
            ,(SELECT COUNT(*) FROM tr WHERE start_date < (NOW() - INTERVAL 6 WEEK) and status_code = 1 and bil_withdrawal is null AND users.id = tr.assessor and (onefile_id IS NULL OR onefile_id NOT IN (SELECT LearnerID FROM onefile_tlap WHERE AssessorSignedOn > DATE_SUB(NOW(), INTERVAL 6 WEEK))) and id not in ($trs)) as no_assessment
            ,(SELECT group_concat(id) FROM tr WHERE start_date < (NOW() - INTERVAL 6 WEEK) and status_code = 1 and bil_withdrawal is null and users.id = tr.assessor and (onefile_id IS NULL OR onefile_id NOT IN (SELECT LearnerID FROM onefile_tlap WHERE AssessorSignedOn > DATE_SUB(NOW(), INTERVAL 6 WEEK))) and id not in ($trs)) as no_assessment_trs
            ,(SELECT COUNT(id) FROM tr WHERE assessor = users.id AND status_code = 1 and bil_withdrawal is null and id in ($trs)) AS epa
            ,(SELECT COUNT(CASE WHEN (((DATEDIFF(NOW(), start_date) / DATEDIFF(target_date, start_date)) * 100)>=COALESCE(onefile_otj.actual_hours/onefile_otj.planned_otj*100,0)) THEN 1 END) FROM tr 
                LEFT JOIN onefile_otj ON onefile_otj.onefile_learner_id = tr.onefile_id
                WHERE tr.assessor = users.id AND status_code = 1) AS behind_target
            FROM users 
            WHERE users.type=3 and users.active=1 order by firstnames, surname;";
            DAO::execute($link, "SET SESSION group_concat_max_len = 1000000;");
            $rows = DAO::getResultset($link, $sql, DAO::FETCH_ASSOC);

            echo "Assessor, In-learning, In-funding, OOF, At EPA, No Assessment 6 Weeks, No Review 10 Weeks, Behind with OTJ, BIL, Withdrawn";
            echo "\r\n";

            foreach($rows as $row)
            {
                if($row['starts_current_period']>0 or $row['starts_current_year']>0 or $row['live_trs']>0 or $row['withdrawals']>0 or $row['funded']>0 or $row['oof']>0 or $row['bil']>0 or $row['pending_bil']>0 or $row['progress']>0 or $row['due_current_period']>0 or $row['achievers_current_period']>0 or $row['achievers_current_year']>0 or $row['withdrawals_current_period']>0 or $row['withdrawals_current_year']>0 or $row['no_reviews']>0)
                {

                    echo '="' . $row['assessor'] . '"';
                    echo ',"' . $row['live'] . '"';
                    echo ',"' . $row['funded'] . '"';
                    echo ',"' . $row['oof'] . '"';
                    echo ',"' . $row['epa'] . '"';
                    echo ',"' . $row['no_assessment'] . '"';
                    echo ',"' . $row['no_reviews'] . '"';
                    echo ',"' . $row['behind_target'] . '"';
                    echo ',"' . $row['pending_bil'] . '"';
                    echo ',"' . $row['withdrawals'] . '"';
                    echo "\r\n";
                }
            } 
            exit;
        }

        if(DB_NAME=='am_baltic')
        {
            $learner_type_filter = isset($_REQUEST['learner_type'])?$_REQUEST['learner_type']:'All learner type';
            $employer_type_filter = isset($_REQUEST['employer_type'])?$_REQUEST['employer_type']:'All employer type';
        }

        DAO::execute($link, "UPDATE tr LEFT JOIN taggables ON taggables.taggable_id = tr.id LEFT JOIN tags ON tags.id = taggables.tag_id SET tr.tag_name = tags.`name` where taggables.taggable_type = 'Training Record'");

        $this->case_scenario = $best_case_filter;
        $this->level = $level_filter;
        $this->at_risk = $at_risk_filter;

        DAO::execute($link, "CREATE TEMPORARY TABLE IF NOT EXISTS success_rates AS (SELECT * FROM success_rates2);");

        $table = array();
        $table2 = array();
        $table3 = array();
        // Note: The UNION query below does not work with temporary tables (MySQL cannot "reopen" a temporary table),
        //       so it has been rewritten as two queries which are then joined together, sorted and made DISTINCT in PHP.
        //$years = DAO::getSingleColumn($link, "SELECT expected FROM success_rates UNION SELECT actual FROM success_rates WHERE expected IS NOT NULL AND actual IS NOT NULL order by expected");
        $years_expected = DAO::getSingleColumn($link, "SELECT distinct expected FROM success_rates WHERE expected IS NOT NULL");
        $years_actual = DAO::getSingleColumn($link, "SELECT distinct actual FROM success_rates WHERE actual IS NOT NULL");
        $years = array_merge($years_expected, $years_actual);
        $year = array_unique($years, SORT_STRING);
        sort($year);

        $start_index = sizeof($year)-5;
        if($start_index<0)
            $start_index =0;

        /*DAO::execute($link, "UPDATE success_rates LEFT JOIN central.lookup_la_gor ON success_rates.local_authority = central.lookup_la_gor.local_authority SET success_rates.region = central.lookup_la_gor.government_region;");
        DAO::execute($link, "UPDATE success_rates set sfc = 'Business and Administration' where sfc = 'Business Administration'");
        DAO::execute($link, "UPDATE success_rates INNER JOIN tr ON success_rates.`tr_id` = tr.id SET success_rates.`at_risk` = tr.`at_risk`");

        DAO::execute($link, "CREATE TEMPORARY TABLE success_rates_bcs select * from success_rates");
        DAO::execute($link, "update success_rates_bcs set actual_end_date = planned_end_date, achievement_date = planned_end_date, actual = expected, hybrid = expected, p_prog_status = 1 where p_prog_status = 0");
        DAO::execute($link, "UPDATE success_rates_bcs INNER JOIN tr ON success_rates_bcs.`tr_id` = tr.id SET success_rates_bcs.`at_risk` = tr.`at_risk`");

        DAO::execute($link, "UPDATE qar_archive SET hybrid=GREATEST(expected,actual);");
        DAO::execute($link, "UPDATE qar_archive SET data_error = 0 WHERE data_error IS NULL;");
        DAO::execute($link, "UPDATE success_rates2 SET hybrid=GREATEST(expected,actual);");
        DAO::execute($link, "UPDATE success_rates2 SET data_error = 0 WHERE data_error IS NULL;");*/

        if($panel == 'getOverallSummary')
        {
            $submission = DAO::getSingleValue($link, "SELECT submission AS submission FROM central.`lookup_submission_dates` WHERE CURDATE() BETWEEN start_submission_date AND last_submission_date and contract_year = '$contract_year' ORDER BY contract_year DESC LIMIT 1;");
            $trs = DAO::getSingleValue($link, "SELECT GROUP_CONCAT(tr_id) FROM ilr WHERE submission = '$submission' AND contract_id IN (SELECT id FROM contracts WHERE contract_year = (SELECT contract_year FROM contracts where contract_year = '$contract_year' ORDER BY contract_year DESC LIMIT 1)) AND EXTRACTVALUE(ilr, '/Learner/LearningDelivery[AimType=1]/Outcome')=8");
            $t ="";
            $st = $link->query("select distinct tag_name from tr where tag_name is not null");
            if($st)
            {
                while($tags = $st->fetch(PDO::FETCH_ASSOC))
                {
                    $tag = $tags['tag_name'];    
                    $sql = "SELECT CONCAT(firstnames, ' ', surname) as name 
                    ,(SELECT COUNT(*) FROM tr WHERE assessor = users.id AND status_code = 1 and bil_withdrawal is null and tag_name = '$tag') AS live
                    ,(select group_concat(id) from tr where assessor = users.id AND status_code = 1 and bil_withdrawal is null and tag_name = '$tag') as live_trs
                    ,(SELECT COUNT(*) FROM tr WHERE assessor = users.id AND (status_code = 6 or bil_withdrawal=1) and tag_name = '$tag' AND l03 NOT IN (SELECT l03 FROM tr AS future WHERE future.l03 = tr.l03 AND tr.id < future.id) and id not in ($trs)) AS bil
                    ,(SELECT group_concat(id) FROM tr WHERE assessor = users.id AND (status_code = 6 or bil_withdrawal=1) and tag_name = '$tag' AND l03 NOT IN (SELECT l03 FROM tr AS future WHERE future.l03 = tr.l03 AND tr.id < future.id) and id not in ($trs)) AS bil_trs
                    ,(SELECT COUNT(id) FROM tr WHERE assessor = users.id AND status_code = 1 and bil_withdrawal is null and tag_name = '$tag' and id in ($trs)) AS epa
                    ,(SELECT GROUP_CONCAT(id) FROM tr WHERE assessor = users.id AND status_code = 1 and bil_withdrawal is null and tag_name = '$tag' and id in ($trs)) AS epa_trs
                    ,(SELECT COUNT(*) FROM tr WHERE start_date < (NOW() - INTERVAL 10 WEEK) and assessor = users.id and status_code = 1 and bil_withdrawal is null and tag_name = '$tag' AND (onefile_id IS NULL OR onefile_id NOT IN (SELECT LearnerID FROM onefile_reviews WHERE StartedOn > DATE_SUB(NOW(), INTERVAL 10 WEEK))) and id not in ($trs)) as no_reviews
                    ,(SELECT group_concat(id) FROM tr WHERE start_date < (NOW() - INTERVAL 10 WEEK) and assessor = users.id and status_code = 1 and bil_withdrawal is null and tag_name = '$tag' AND (onefile_id IS NULL OR onefile_id NOT IN (SELECT LearnerID FROM onefile_reviews WHERE StartedOn > DATE_SUB(NOW(), INTERVAL 10 WEEK))) and id not in ($trs)) as no_reviews_trs
                    ,(SELECT COUNT(*) FROM tr WHERE start_date < (NOW() - INTERVAL 6 WEEK) and assessor = users.id and status_code = 1 and bil_withdrawal is null and tag_name = '$tag' AND (onefile_id IS NULL OR onefile_id NOT IN (SELECT LearnerID FROM onefile_tlap WHERE AssessorSignedOn > DATE_SUB(NOW(), INTERVAL 6 WEEK))) and id not in ($trs)) as no_assessment
                    ,(SELECT group_concat(id) FROM tr WHERE start_date < (NOW() - INTERVAL 6 WEEK) and assessor = users.id and status_code = 1 and bil_withdrawal is null and tag_name = '$tag' AND (onefile_id IS NULL OR onefile_id NOT IN (SELECT LearnerID FROM onefile_tlap WHERE AssessorSignedOn > DATE_SUB(NOW(), INTERVAL 6 WEEK))) and id not in ($trs)) as no_assessment_trs
                    ,0 AS high_risk
                    ,(SELECT count(*) FROM tr WHERE assessor = users.id and status_code = 1 and bil_withdrawal is null and tag_name = '$tag' AND onefile_id IN (SELECT ID FROM onefile_learners WHERE progress>= 90) and id not in ($trs)) as learner_progress
                    ,(SELECT group_concat(id) FROM tr WHERE assessor = users.id and status_code = 1 and bil_withdrawal is null and tag_name = '$tag' AND onefile_id IN (SELECT ID FROM onefile_learners WHERE progress>= 90) and id not in ($trs)) as learner_progress_trs
                    ,(SELECT COUNT(id) FROM tr WHERE assessor = users.id AND status_code = 1 and bil_withdrawal is null and tag_name = '$tag' and target_date <= CURDATE()) AS oof
                    ,(SELECT COUNT(id) FROM tr WHERE assessor = users.id AND status_code = 1 and bil_withdrawal is null and tag_name = '$tag' and target_date > CURDATE() AND target_date <= LAST_DAY(CURDATE())) AS oof_1
                    ,(SELECT COUNT(id) FROM tr WHERE assessor = users.id AND status_code = 1 and bil_withdrawal is null and tag_name = '$tag' and target_date >=  DATE_FORMAT(DATE_ADD(CURDATE(), INTERVAL 1 MONTH) ,'%Y-%m-01') AND target_date <= LAST_DAY(DATE_ADD(CURDATE(), INTERVAL 1 MONTH))) AS oof_2
                    ,(SELECT COUNT(id) FROM tr WHERE assessor = users.id AND status_code = 1 and bil_withdrawal is null and tag_name = '$tag' and target_date >=  DATE_FORMAT(DATE_ADD(CURDATE(), INTERVAL 2 MONTH) ,'%Y-%m-01') AND target_date <= LAST_DAY(DATE_ADD(CURDATE(), INTERVAL 2 MONTH))) AS oof_3
                    ,(SELECT group_concat(id) FROM tr WHERE assessor = users.id AND status_code = 1 and bil_withdrawal is null and tag_name = '$tag' and target_date <= CURDATE()) AS oof_trs
                    ,(SELECT group_concat(id) FROM tr WHERE assessor = users.id AND status_code = 1 and bil_withdrawal is null and tag_name = '$tag' and target_date > CURDATE() AND target_date <= LAST_DAY(CURDATE())) AS oof_1_trs
                    ,(SELECT group_concat(id) FROM tr WHERE assessor = users.id AND status_code = 1 and bil_withdrawal is null and tag_name = '$tag' and target_date >=  DATE_FORMAT(DATE_ADD(CURDATE(), INTERVAL 1 MONTH) ,'%Y-%m-01') AND target_date <= LAST_DAY(DATE_ADD(CURDATE(), INTERVAL 1 MONTH))) AS oof_2_trs
                    ,(SELECT group_concat(id) FROM tr WHERE assessor = users.id AND status_code = 1 and bil_withdrawal is null and tag_name = '$tag' and target_date >=  DATE_FORMAT(DATE_ADD(CURDATE(), INTERVAL 2 MONTH) ,'%Y-%m-01') AND target_date <= LAST_DAY(DATE_ADD(CURDATE(), INTERVAL 2 MONTH))) AS oof_3_trs
                    FROM users WHERE TYPE = 3 and active = 1 AND id IN (SELECT assessor FROM tr where tag_name = '$tag') 
                    order by firstnames, surname";

                    $rows = DAO::getResultset($link, $sql, DAO::FETCH_ASSOC);
                    $th = "<div class='panel panel-primary'>";
                    $th .= "<div class='panel-heading'><b>" . $tag ."</b></div>";
                    $th .= "<div class='chart-panel-body '>";
                    $th .= '<table class="table" border="0" cellspacing="0" cellpadding="6">';
                    $th .= '<thead style="position: sticky;top: 0"><tr><th class="towRow header">&nbsp;</th>';
                    $th .= '<th class="towRow">Assessor</th>';
                    $th .= '<th class="towRow">In-learning</th>';
                    $th .= '<th class="towRow">OOF as of today</th>';
                    $th .= '<th class="towRow">EPA</th>';
                    $th .= '<th class="towRow">Actual caseload</th>';
                    $th .= '<th class="towRow">BIL</th>';
                    $th .= '<th class="towRow">No assessment in 6 weeks</th>';
                    $th .= '<th class="towRow">No reviews in 10 weeks</th>';
                    //$th .= '<th class="topRow">High Risk</th>';
                    $th .= '<th class="towRow">Progress 90%+</th>';
                    $th .= '<th class="towRow">Due ' . date('M') . '</th>';
                    $month2 = new DateTime('first day of next month');
                    $th .= '<th class="towRow">Due ' . $month2->format('M') . '</th>';
                    $month3 = $month2->modify('first day of next month');
                    $th .= '<th class="towRow">Due ' . $month3->format('M') . '</th>';
                    $th .= '</tr></thead><tbody>';
                    $tb="";
                    $live_gt = 0;
                    $oof_gt = 0;
                    $epa_gt = 0;
                    $actual_gt = 0;
                    $bil_gt = 0;    
                    $no_assessment_gt = 0;
                    $no_reviews_gt = 0;
                    $learner_progress_gt = 0;
                    $oof_1_gt = 0;
                    $oof_2_gt = 0;
                    $oof_3_gt = 0;
                    foreach($rows as $row)
                    {
                        if($row['live']>0 or $row['oof']>0 or $row['epa']>0 or $row['bil']>0 or $row['no_assessment']>0 or $row['no_reviews']>0 or $row['learner_progress']>0 or $row['oof_1'] or $row['oof_2']>0 or $row['oof_3']>0)
                        {
                            $tb .= '<tr>';
                            $tb .= '<td>&nbsp;</td>';
                            $tb .= '<td>' . $row['name'] . '</td>';
                            $live_trs = $row['live_trs'];
                            $bil_trs = $row['bil_trs'];
                            $oof_trs = $row['oof_trs'];
                            $tb .= '<td><a href="do.php?_action=view_training_records&_reset=1&ViewTrainingRecords_filter_tr_ids=' . $live_trs . '">' . $row['live'] . '</a></td>';
                            $tb .= '<td><a href="do.php?_action=view_training_records&_reset=1&ViewTrainingRecords_filter_tr_ids=' . $oof_trs . '">' . $row['oof'] . '</a></td>';
                            $epa_trs = $row['epa_trs'];
                            $tb .= '<td><a href="do.php?_action=view_training_records&_reset=1&ViewTrainingRecords_filter_tr_ids=' . $epa_trs . '">' . $row['epa'] . '</a></td>';

                            $actual = $row['live'] - $row['oof'];
                            if($live_trs!="" and $oof_trs!="")
                            {
                                $array1 = explode(",", $live_trs);
                                $array2 = explode(",", $oof_trs);
                                $resultArray = array_diff($array1, $array2);
                                $actual_trs = implode(",", $resultArray);
                            }
                            elseif($live_trs!="")
                                $actual_trs = $live_trs;
                            elseif($oof_trs!="")
                                $actual_trs = $oof_trs;
                            else
                                $actual_trs = "";

                            $tb .= '<td><a href="do.php?_action=view_training_records&_reset=1&ViewTrainingRecords_filter_record_status=SHOW_ALL&ViewTrainingRecords_filter_tr_ids=' . $actual_trs . '">' . $actual . '</a></td>';
                            $tb .= '<td><a href="do.php?_action=view_training_records&_reset=1&ViewTrainingRecords_filter_record_status=SHOW_ALL&ViewTrainingRecords_filter_tr_ids=' . $bil_trs . '">' . $row['bil'] . '</a></td>';
                            $no_assessment = $row['no_assessment'];
                            $no_assessment_trs = $row['no_assessment_trs'];
                            $tb .= '<td><a href="do.php?_action=view_training_records&_reset=1&ViewTrainingRecords_filter_record_status=SHOW_ALL&ViewTrainingRecords_filter_tr_ids=' . $no_assessment_trs . '">' . $no_assessment . '</a></td>';

                            //$t .= '<td>' . $row['no_assessment'] . '</td>';
                            $no_reviews_trs = $row['no_reviews_trs'];
                            $tb .= '<td><a href="do.php?_action=view_training_records&_reset=1&ViewTrainingRecords_filter_tr_ids=' . $no_reviews_trs . '">' . $row['no_reviews'] . '</a></td>';
                            //$t .= '<td>' . $row['high_risk'] . '</td>';
                            $learner_progress_trs = $row['learner_progress_trs'];
                            $tb .= '<td><a href="do.php?_action=view_training_records&_reset=1&ViewTrainingRecords_filter_tr_ids=' . $learner_progress_trs . '">' . $row['learner_progress'] . '</a></td>';
                            $oof_1_trs = $row['oof_1_trs'];
                            $oof_2_trs = $row['oof_2_trs'];
                            $oof_3_trs = $row['oof_3_trs'];
                            $tb .= '<td><a href="do.php?_action=view_training_records&_reset=1&ViewTrainingRecords_filter_tr_ids=' . $oof_1_trs . '">' . $row['oof_1'] . '</a></td>';
                            $tb .= '<td><a href="do.php?_action=view_training_records&_reset=1&ViewTrainingRecords_filter_tr_ids=' . $oof_2_trs . '">' . $row['oof_2'] . '</a></td>';
                            $tb .= '<td><a href="do.php?_action=view_training_records&_reset=1&ViewTrainingRecords_filter_tr_ids=' . $oof_3_trs . '">' . $row['oof_3'] . '</a></td>';
                            $tb .= '</tr>';

                            $live_gt += $row['live'];
                            $oof_gt += $row['oof'];
                            $epa_gt += $row['epa'];
                            $actual_gt += $actual;
                            $bil_gt += $row['bil'];    
                            $no_assessment_gt += $no_assessment;
                            $no_reviews_gt += $row['no_reviews'];
                            $learner_progress_gt += $row['learner_progress'];
                            $oof_1_gt += $row['oof_1'];
                            $oof_2_gt += $row['oof_2'];
                            $oof_3_gt += $row['oof_3'];
        
                        }
                    } 
                    if($tb!="")
                    {
                        $tb .= '<tr>';
                        $tb .= '<td>&nbsp;</td>';
                        $tb .= '<td>Total</td>';
                        $tb .= '<td>' . $live_gt . '</td>';
                        $tb .= '<td>' . $oof_gt . '</td>';
                        $tb .= '<td>' . $epa_gt . '</td>';
                        $tb .= '<td>' . $actual_gt . '</td>';
                        $tb .= '<td>' . $bil_gt . '</td>';
                        $tb .= '<td>' . $no_assessment_gt . '</td>';
                        $tb .= '<td>' . $no_reviews_gt . '</td>';
                        $tb .= '<td>' . $learner_progress_gt . '</td>';
                        $tb .= '<td>' . $oof_1_gt . '</td>';
                        $tb .= '<td>' . $oof_2_gt . '</td>';
                        $tb .= '<td>' . $oof_3_gt . '</td>';
                        $tb .= '</tr>';
                        $t .= $th . $tb . '</tbody></table></div></div>';
                    }                    }
            }

            echo $t;
            exit;
        }

        if($panel == 'getRetentionSummary')
        {
            $period = DAO::getObject($link, "SELECT * FROM central.lookup_submission_dates WHERE CURDATE() BETWEEN start_submission_date AND last_submission_date ORDER BY contract_year DESC LIMIT 1;");
            pre($period);
            echo "Under constructon";
            exit;
        }

        if($panel == 'getTimelySummary')
        {
            echo "Under constructon";
            exit;
        }

        if($panel == 'getLineChartOverallTrend')
        {
           /* $programmes = DAO::getResultset($link, "select distinct courses.id, courses.title from courses left join courses_tr on courses_tr.course_id = courses.id left join tr on tr.id = courses_tr.tr_id where status_code = 1", DAO::FETCH_ASSOC);
            $t = "";
            foreach($programmes as $programme)
            {
                $course_id = $programme['id'];
                $sql = "SELECT CONCAT(firstnames, ' ', surname) as name 
                ,(SELECT COUNT(*) FROM tr WHERE assessor = users.id AND status_code = 1 and id in (select tr_id from courses_tr where course_id = '$course_id')) AS live
                ,(SELECT COUNT(*) FROM tr WHERE assessor = users.id AND status_code = 6 AND l03 NOT IN (SELECT l03 FROM tr AS future WHERE future.l03 = tr.l03 AND tr.id < future.id) and id in (select tr_id from courses_tr where course_id = '$course_id')) AS bil
                ,0 AS epa
                ,0 AS gateway
                ,0 AS no_assessment 
                ,0 AS no_review
                ,0 AS high_risk
                ,0 AS learner_progress
                ,(SELECT COUNT(*) FROM tr WHERE assessor = users.id AND target_date >=  DATE_FORMAT(CURDATE() ,'%Y-%m-01') AND target_date <= LAST_DAY(CURDATE()) and id in (select tr_id from courses_tr where course_id = '$course_id')) AS oof_1
                ,(SELECT COUNT(*) FROM tr WHERE assessor = users.id AND target_date >=  DATE_FORMAT(DATE_ADD(CURDATE(), INTERVAL 1 MONTH) ,'%Y-%m-01') AND target_date <= LAST_DAY(DATE_ADD(CURDATE(), INTERVAL 1 MONTH)) and id in (select tr_id from courses_tr where course_id = '$course_id')) AS oof_2
                ,(SELECT COUNT(*) FROM tr WHERE assessor = users.id AND target_date >=  DATE_FORMAT(DATE_ADD(CURDATE(), INTERVAL 2 MONTH) ,'%Y-%m-01') AND target_date <= LAST_DAY(DATE_ADD(CURDATE(), INTERVAL 2 MONTH)) and id in (select tr_id from courses_tr where course_id = '$course_id')) AS oof_3
                FROM users WHERE TYPE = 3 AND id IN (SELECT assessor FROM tr WHERE status_code = 1);
                ";
                $rows = DAO::getResultset($link, $sql, DAO::FETCH_ASSOC);
                $t .= '<br><h3>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;' . $programme['title'] . '</h3>';
                $t .= '<div align="center"><table class="table" border="0" cellspacing="0" cellpadding="6">';
                $t.= '<thead><tr><th class="topRow">&nbsp;</th>';
                $t .= '<th class="topRow">Assessor</th>';
                $t .= '<th class="topRow">In-learning</th>';
                $t .= '<th class="topRow">BIL</th>';
                $t .= '<th class="topRow">EPA</th>';
                $t .= '<th class="topRow">Gateway</th>';
                $t .= '<th class="topRow">Actual caseload</th>';
                $t .= '<th class="topRow">No assessment in 6 weeks</th>';
                $t .= '<th class="topRow">No reviews in 10 weeks</th>';
                $t .= '<th class="topRow">High Risk</th>';
                $t .= '<th class="topRow">Progress 90%+</th>';
                $t .= '<th class="topRow">Unfunded ' . date('M') . '</th>';
                $month2 = new DateTime('first day of next month');
                $t .= '<th class="topRow">Unfunded ' . $month2->format('M') . '</th>';
                $month3 = $month2->modify('first day of next month');
                $t .= '<th class="topRow">Unfunded ' . $month3->format('M') . '</th>';
                $t .= '</tr></thead><tbody>';
                foreach($rows as $row)
                {
                    $t .= '<tr>';
                    $t .= '<td>&nbsp;</td>';
                    $t .= '<td>' . $row['name'] . '</td>';
                    $t .= '<td>' . $row['live'] . '</td>';
                    $t .= '<td>' . $row['bil'] . '</td>';
                    $t .= '<td>' . $row['epa'] . '</td>';
                    $t .= '<td>' . $row['gateway'] . '</td>';
                    $actual = $row['live'] - $row['bil'] - $row['epa'] - $row['gateway'];
                    $t .= '<td>' . $actual . '</td>';
                    $t .= '<td>' . $row['no_assessment'] . '</td>';
                    $t .= '<td>' . $row['no_review'] . '</td>';
                    $t .= '<td>' . $row['high_risk'] . '</td>';
                    $t .= '<td>' . $row['learner_progress'] . '</td>';
                    $t .= '<td>' . $row['oof_1'] . '</td>';
                    $t .= '<td>' . $row['oof_2'] . '</td>';
                    $t .= '<td>' . $row['oof_3'] . '</td>';
                    $t .= '</tr>';
                } 
                $t .= '</tbody></table></div>';
            }
            echo $t;*/
            exit;
        }

        if($panel == 'getLineChartOverallTrend')
        {
            echo "Under constructon";
            exit;
        }


        if($panel == 'getLineChartTimelyTrend')
        {
            echo "Under constructon";
            exit;
        }
        if($panel == 'LearnerByProgrammeTable')
        {

            $submission = DAO::getSingleValue($link, "SELECT submission AS submission FROM central.`lookup_submission_dates` WHERE CURDATE() BETWEEN start_submission_date AND last_submission_date and contract_year = '$contract_year' ORDER BY contract_year DESC LIMIT 1;");
            $trs = DAO::getSingleValue($link, "SELECT GROUP_CONCAT(tr_id) FROM ilr WHERE submission = '$submission' AND contract_id IN (SELECT id FROM contracts WHERE contract_year = '$contract_year') AND EXTRACTVALUE(ilr, '/Learner/LearningDelivery[AimType=1]/Outcome')=8");

            $period = DAO::getObject($link, "SELECT * FROM central.lookup_submission_dates WHERE CURDATE() BETWEEN start_submission_date AND last_submission_date ORDER BY contract_year DESC LIMIT 1;");
            $year = DAO::getObject($link, "SELECT * FROM contracts where contract_year = '$contract_year' ORDER BY contract_year DESC LIMIT 1;");

            $period_start_date = $period->start_submission_date;
            $period_end_date = $period->last_submission_date;
            $year_start_date = $year->start_date;
            $year_end_date = $year->end_date;

            $t ="";
            $st = $link->query("select distinct tag_name from tr where tag_name is not null");
            if($st)
            {
                while($tags = $st->fetch(PDO::FETCH_ASSOC))
                {
                    $tag = $tags['tag_name'];    
                    $sql = "SELECT 
                    `title` AS programme
                    ,(SELECT COUNT(id) FROM tr WHERE bil_withdrawal is null and start_date >= '$period_start_date' AND start_date <='$period_end_date' and tag_name = '$tag' AND id IN (SELECT tr_id FROM courses_tr WHERE course_id = courses.`id`)) AS starts_current_period
                    ,(SELECT GROUP_CONCAT(id) FROM tr WHERE bil_withdrawal is null and start_date >= '$period_start_date' AND start_date <='$period_end_date' and tag_name = '$tag' AND id IN (SELECT tr_id FROM courses_tr WHERE course_id = courses.`id`)) AS starts_current_period_trs
                    ,(SELECT COUNT(id) FROM tr WHERE bil_withdrawal is null and start_date >= '$year_start_date' AND start_date <='$year_end_date' and tag_name = '$tag' AND id IN (SELECT tr_id FROM courses_tr WHERE course_id = courses.`id`)) AS starts_current_year
                    ,(SELECT GROUP_CONCAT(id) FROM tr WHERE bil_withdrawal is null and start_date >= '$year_start_date' AND start_date <='$year_end_date' and tag_name = '$tag' AND id IN (SELECT tr_id FROM courses_tr WHERE course_id = courses.`id`)) AS starts_current_year_trs
                    ,(SELECT COUNT(id) FROM tr WHERE status_code = 1 and bil_withdrawal is null and tag_name = '$tag' AND id IN (SELECT tr_id FROM courses_tr WHERE course_id = courses.`id`)) AS live
                    ,(SELECT GROUP_CONCAT(id) FROM tr WHERE status_code = 1 and bil_withdrawal is null and tag_name = '$tag' AND id IN (SELECT tr_id FROM courses_tr WHERE course_id = courses.`id`)) AS live_trs
                    ,(SELECT COUNT(id) FROM tr WHERE status_code = 1 and bil_withdrawal is null and tag_name = '$tag' and id in ($trs) AND id IN (SELECT tr_id FROM courses_tr WHERE course_id = courses.`id`)) AS epa
                    ,(SELECT GROUP_CONCAT(id) FROM tr where status_code = 1 and bil_withdrawal is null and tag_name = '$tag' and id in ($trs) AND id IN (SELECT tr_id FROM courses_tr WHERE course_id = courses.`id`)) AS epa_trs
                    ,(SELECT COUNT(id) FROM tr WHERE (status_code = 3 or bil_withdrawal = 2) and tag_name = '$tag' AND id IN (SELECT tr_id FROM courses_tr WHERE course_id = courses.`id`) and id not in ($trs)) AS withdrawals
                    ,(SELECT GROUP_CONCAT(id) FROM tr WHERE (status_code = 3 or bil_withdrawal = 2) and tag_name = '$tag' AND id IN (SELECT tr_id FROM courses_tr WHERE course_id = courses.`id`) and id not in ($trs)) AS withdrawals_trs
                    ,(SELECT COUNT(id) FROM tr WHERE status_code = 1 and bil_withdrawal is null and tag_name = '$tag' AND target_date > CURDATE() AND id IN (SELECT tr_id FROM courses_tr WHERE course_id = courses.`id`) and id not in ($trs)) AS funded
                    ,(SELECT GROUP_CONCAT(id) FROM tr WHERE status_code = 1 and bil_withdrawal is null and tag_name = '$tag' AND target_date > CURDATE() AND id IN (SELECT tr_id FROM courses_tr WHERE course_id = courses.`id`) and id not in ($trs)) AS funded_trs
                    ,(SELECT COUNT(id) FROM tr WHERE status_code = 1 and bil_withdrawal is null and tag_name = '$tag' AND target_date <= CURDATE() AND id IN (SELECT tr_id FROM courses_tr WHERE course_id = courses.`id`)) AS oof
                    ,(SELECT GROUP_CONCAT(id) FROM tr WHERE status_code = 1 and bil_withdrawal is null and tag_name = '$tag' AND target_date <= CURDATE() AND id IN (SELECT tr_id FROM courses_tr WHERE course_id = courses.`id`)) AS oof_trs
                    ,(SELECT COUNT(id) FROM tr WHERE (status_code = 6 or bil_withdrawal = 1) AND  tag_name = '$tag' and id IN (SELECT tr_id FROM courses_tr WHERE course_id = courses.`id`) and id not in ($trs)) AS bil
                    ,(SELECT GROUP_CONCAT(id) FROM tr WHERE (status_code = 6 or bil_withdrawal = 1) and tag_name = '$tag' AND id IN (SELECT tr_id FROM courses_tr WHERE course_id = courses.`id`) and id not in ($trs)) AS bil_trs
                    ,(SELECT COUNT(id) FROM tr WHERE (status_code = 6 or bil_withdrawal = 1) and tag_name = '$tag' AND id IN (SELECT tr_id FROM courses_tr WHERE course_id = courses.`id`) AND l03 NOT IN (SELECT l03 FROM tr AS future WHERE future.start_date > tr.start_date) and id not in ($trs)) AS pending_bil
                    ,(SELECT GROUP_CONCAT(id) FROM tr WHERE (status_code = 6 or bil_withdrawal = 1) and tag_name = '$tag' AND id IN (SELECT tr_id FROM courses_tr WHERE course_id = courses.`id`) AND l03 NOT IN (SELECT l03 FROM tr AS future WHERE future.start_date > tr.start_date) and id not in ($trs)) AS pending_bil_trs
                    ,(SELECT COUNT(id) FROM tr WHERE status_code = 1 and bil_withdrawal is null and tag_name = '$tag' AND id IN (SELECT tr_id FROM courses_tr WHERE course_id = courses.`id`) AND onefile_id IN (SELECT ID FROM onefile_learners WHERE progress>= 90) and id not in ($trs)) AS progress
                    ,(SELECT GROUP_CONCAT(id) FROM tr WHERE status_code = 1 and bil_withdrawal is null and tag_name = '$tag' AND id IN (SELECT tr_id FROM courses_tr WHERE course_id = courses.`id`) AND onefile_id IN (SELECT ID FROM onefile_learners WHERE progress>= 90) and id not in ($trs)) AS progress_trs
                    ,(SELECT COUNT(id) FROM tr WHERE bil_withdrawal is null and target_date >= '$period_start_date' AND target_date <='$period_end_date' and tag_name = '$tag' AND id IN (SELECT tr_id FROM courses_tr WHERE course_id = courses.`id`) and id not in ($trs)) AS due_current_period
                    ,(SELECT GROUP_CONCAT(id) FROM tr WHERE bil_withdrawal is null and target_date >= '$period_start_date' AND target_date <='$period_end_date' and tag_name = '$tag' AND id IN (SELECT tr_id FROM courses_tr WHERE course_id = courses.`id`) and id not in ($trs)) AS due_current_period_trs
                    ,(SELECT COUNT(id) FROM tr WHERE status_code = 2 and tag_name = '$tag' AND GREATEST(closure_date, COALESCE(achievement_date, '0000-00-00')) >= '$period_start_date' AND GREATEST(closure_date, COALESCE(achievement_date, '0000-00-00')) <='$period_end_date' AND id IN (SELECT tr_id FROM courses_tr WHERE course_id = courses.`id`)) AS achievers_current_period
                    ,(SELECT GROUP_CONCAT(id) FROM tr WHERE status_code = 2 and tag_name = '$tag' AND GREATEST(closure_date, COALESCE(achievement_date, '0000-00-00')) >= '$period_start_date' AND GREATEST(closure_date, COALESCE(achievement_date, '0000-00-00')) <='$period_end_date' AND id IN (SELECT tr_id FROM courses_tr WHERE course_id = courses.`id`)) AS achievers_current_period_trs
                    ,(SELECT COUNT(id) FROM tr WHERE status_code = 2 and tag_name = '$tag' AND GREATEST(closure_date, COALESCE(achievement_date, '0000-00-00')) >= '$year_start_date' AND GREATEST(closure_date, COALESCE(achievement_date, '0000-00-00')) <='$year_end_date' AND id IN (SELECT tr_id FROM courses_tr WHERE course_id = courses.`id`)) AS achievers_current_year
                    ,(SELECT GROUP_CONCAT(id) FROM tr WHERE status_code = 2 and tag_name = '$tag' AND GREATEST(closure_date, COALESCE(achievement_date, '0000-00-00')) >= '$year_start_date' AND GREATEST(closure_date, COALESCE(achievement_date, '0000-00-00')) <='$year_end_date' AND id IN (SELECT tr_id FROM courses_tr WHERE course_id = courses.`id`)) AS achievers_current_year_trs
                    ,(SELECT COUNT(id) FROM tr WHERE (status_code = 3 or bil_withdrawal = 2) and tag_name = '$tag' AND closure_date >= '$period_start_date' AND closure_date <='$period_end_date' AND id IN (SELECT tr_id FROM courses_tr WHERE course_id = courses.`id`)) AS withdrawals_current_period
                    ,(SELECT GROUP_CONCAT(id) FROM tr WHERE (status_code = 3 or bil_withdrawal = 2) and tag_name = '$tag' AND closure_date >= '$period_start_date' AND closure_date <='$period_end_date' AND id IN (SELECT tr_id FROM courses_tr WHERE course_id = courses.`id`)) AS withdrawals_current_period_trs
                    ,(SELECT COUNT(id) FROM tr WHERE (status_code = 3 or bil_withdrawal = 2) and tag_name = '$tag' AND closure_date >= '$year_start_date' AND closure_date <='$year_end_date' AND id IN (SELECT tr_id FROM courses_tr WHERE course_id = courses.`id`)) AS withdrawals_current_year
                    ,(SELECT GROUP_CONCAT(id) FROM tr WHERE (status_code = 3 or bil_withdrawal = 2) and tag_name = '$tag' AND closure_date >= '$year_start_date' AND closure_date <='$year_end_date' AND id IN (SELECT tr_id FROM courses_tr WHERE course_id = courses.`id`)) AS withdrawals_current_year_trs
                    ,(SELECT COUNT(id) FROM tr WHERE start_date < (NOW() - INTERVAL 10 WEEK) and status_code = 1 and bil_withdrawal is null and tag_name = '$tag' AND id IN (SELECT tr_id FROM courses_tr WHERE course_id = courses.`id`) AND (onefile_id IS NULL OR onefile_id NOT IN (SELECT LearnerID FROM onefile_reviews WHERE StartedOn > DATE_SUB(NOW(), INTERVAL 10 WEEK))) and id not in ($trs)) AS no_reviews
                    ,(SELECT GROUP_CONCAT(id) FROM tr WHERE start_date < (NOW() - INTERVAL 10 WEEK) and status_code = 1 and bil_withdrawal is null and tag_name = '$tag' AND id IN (SELECT tr_id FROM courses_tr WHERE course_id = courses.`id`) AND (onefile_id IS NULL OR onefile_id NOT IN (SELECT LearnerID FROM onefile_reviews WHERE StartedOn > DATE_SUB(NOW(), INTERVAL 10 WEEK))) and id not in ($trs)) AS no_reviews_trs
                    ,(SELECT COUNT(*) FROM tr WHERE start_date < (NOW() - INTERVAL 6 WEEK) and status_code = 1 and bil_withdrawal is null and tag_name = '$tag' AND id IN (SELECT tr_id FROM courses_tr WHERE course_id = courses.`id`) and (onefile_id IS NULL OR onefile_id NOT IN (SELECT LearnerID FROM onefile_tlap WHERE AssessorSignedOn > DATE_SUB(NOW(), INTERVAL 6 WEEK))) and id not in ($trs)) as no_assessment
                    ,(SELECT group_concat(id) FROM tr WHERE start_date < (NOW() - INTERVAL 6 WEEK) and status_code = 1 and bil_withdrawal is null and tag_name = '$tag' AND id IN (SELECT tr_id FROM courses_tr WHERE course_id = courses.`id`) and (onefile_id IS NULL OR onefile_id NOT IN (SELECT LearnerID FROM onefile_tlap WHERE AssessorSignedOn > DATE_SUB(NOW(), INTERVAL 6 WEEK))) and id not in ($trs)) as no_assessment_trs
                    FROM courses 
                    WHERE id IN (SELECT course_id FROM courses_tr WHERE tr_id IN (SELECT id FROM tr where tag_name = '$tag'));";
                    DAO::execute($link, "SET SESSION group_concat_max_len = 1000000;");
                    $rows = DAO::getResultset($link, $sql, DAO::FETCH_ASSOC);
                    $th = "<div class='panel panel-primary'>";
                    $th .= "<div class='panel-heading'><b>" . $tag ."</b></div>";
                    $th .= "<div class='chart-panel-body '>";
                    $th .= '<table class="table" border="0" cellspacing="0" cellpadding="6">';
                    $th .= '<thead style="position: sticky;top: 0"><tr><th class="topRow">&nbsp;</th>';
                    $th .= '<th class="topRow">Programme</th>';
                    $th .= '<th class="topRow">Starts current period</th>';
                    $th .= '<th class="topRow">Starts Current Year</th>';
                    $th .= '<th class="topRow">No Assessment 6 Weeks</th>';
                    $th .= '<th class="topRow">In-learning</th>';
                    $th .= '<th class="topRow">EPA</th>';
                    $th .= '<th class="topRow">Withdrawn</th>';
                    $th .= '<th class="topRow">Funded Learners</th>';
                    $th .= '<th class="topRow">OOF</th>';
                    $th .= '<th class="topRow">BIL</th>';
                    $th .= '<th class="topRow">Progress 90%</th>';
                    $th .= '<th class="topRow">Due current period</th>';
                    $th .= '<th class="topRow">Achievers current period</th>';
                    $th .= '<th class="topRow">Achievers current year</th>';
                    $th .= '<th class="topRow">Withdrawn current period</th>';
                    $th .= '<th class="topRow">Withdrawn current year</th>';
                    $th .= '<th class="topRow">No review in last 10 weeks</th>';
                    $th .= '</tr></thead><tbody>';
                    $tb = ""; 

                    $starts_current_period_gt = 0;
                    $starts_current_year_gt = 0;
                    $no_assessment_gt = 0;
                    $live_gt = 0;
                    $withdrawals_gt = 0;
                    $funded_gt = 0;
                    $bil_gt = 0;    
                    $epa_gt = 0;    
                    $pending_bil_gt = 0;
                    $progress_gt = 0;
                    $due_current_period_gt = 0;
                    $achievers_current_period_gt = 0;
                    $achievers_current_year_gt = 0;
                    $withdrawals_current_period_gt = 0;
                    $withdrawals_current_year_gt = 0;
                    $no_reviews_gt = 0;
                    $oof_gt = 0;

                    foreach($rows as $row)
                    {
                        if($row['starts_current_period']>0 or $row['starts_current_year']>0 or $row['live']>0 or $row['withdrawals']>0 or $row['funded']>0 or $row['oof']>0 or $row['bil']>0 or $row['pending_bil']>0 or $row['progress']>0 or $row['due_current_period']>0 or $row['achievers_current_period']>0 or $row['withdrawals_current_period']>0 or $row['withdrawals_current_year']>0 or $row['no_reviews']>0)
                        {
                            $tb .= '<tr>';
                            $tb .= '<td>&nbsp;</td>';
                            $tb .= '<td>' . $row['programme'] . '</td>';
                            $starts_current_period = $row['starts_current_period'];
                            $starts_current_period_trs = $row['starts_current_period_trs'];
                            $tb .= '<td><a href="do.php?_action=view_training_records&_reset=1&ViewTrainingRecords_filter_tr_ids=' . $starts_current_period_trs . '">' . $starts_current_period . '</a></td>';
                            $starts_current_year = $row['starts_current_year'];
                            $starts_current_year_trs = $row['starts_current_year_trs'];
                            $tb .= '<td><a href="do.php?_action=view_training_records&_reset=1&ViewTrainingRecords_filter_tr_ids=' . $starts_current_year_trs . '">' . $starts_current_year . '</a></td>';
                            $no_assessment = $row['no_assessment'];
                            $no_assessment_trs = $row['no_assessment_trs'];
                            $tb .= '<td><a href="do.php?_action=view_training_records&_reset=1&ViewTrainingRecords_filter_record_status=SHOW_ALL&ViewTrainingRecords_filter_tr_ids=' . $no_assessment_trs . '">' . $no_assessment . '</a></td>';
                            $live = $row['live'];
                            $live_trs = $row['live_trs'];
                            $tb .= '<td><a href="do.php?_action=view_training_records&_reset=1&ViewTrainingRecords_filter_tr_ids=' . $live_trs . '">' . $live . '</a></td>';
                            $epa = $row['epa'];
                            $epa_trs = $row['epa_trs'];
                            $tb .= '<td><a href="do.php?_action=view_training_records&_reset=1&ViewTrainingRecords_filter_tr_ids=' . $epa_trs . '">' . $epa . '</a></td>';
                            $withdrawals = $row['withdrawals'];
                            $withdrawals_trs = $row['withdrawals_trs'];
                            $tb .= '<td><a href="do.php?_action=view_training_records&_reset=1&ViewTrainingRecords_filter_record_status=SHOW_ALL&ViewTrainingRecords_filter_tr_ids=' . $withdrawals_trs . '">' . $withdrawals . '</a></td>';
                            $funded = $row['funded'];
                            $funded_trs = $row['funded_trs'];
                            $tb .= '<td><a href="do.php?_action=view_training_records&_reset=1&ViewTrainingRecords_filter_tr_ids=' . $funded_trs . '">' . $funded . '</a></td>';
                            $oof = $row['oof'];
                            $oof_trs = $row['oof_trs'];
                            $tb .= '<td><a href="do.php?_action=view_training_records&_reset=1&ViewTrainingRecords_filter_tr_ids=' . $oof_trs . '">' . $oof . '</a></td>';
                            //$bil = $row['bil'];
                            //$bil_trs = $row['bil_trs'];
                            //$tb .= '<td><a href="do.php?_action=view_training_records&_reset=1&ViewTrainingRecords_filter_record_status=SHOW_ALL&ViewTrainingRecords_filter_tr_ids=' . $bil_trs . '">' . $bil . '</a></td>';
                            $pending_bil = $row['pending_bil'];
                            $pending_bil_trs = $row['pending_bil_trs'];
                            $tb .= '<td><a href="do.php?_action=view_training_records&_reset=1&ViewTrainingRecords_filter_record_status=SHOW_ALL&ViewTrainingRecords_filter_tr_ids=' . $pending_bil_trs . '">' . $pending_bil . '</a></td>';
                            $progress = $row['progress'];
                            $progress_trs = $row['progress_trs'];
                            $tb .= '<td><a href="do.php?_action=view_training_records&_reset=1&ViewTrainingRecords_filter_tr_ids=' . $progress_trs . '">' . $progress . '</a></td>';
                            $due_current_period = $row['due_current_period'];
                            $due_current_period_trs = $row['due_current_period_trs'];
                            $tb .= '<td><a href="do.php?_action=view_training_records&_reset=1&ViewTrainingRecords_filter_tr_ids=' . $due_current_period_trs . '">' . $due_current_period . '</a></td>';
                            $achievers_current_period = $row['achievers_current_period'];
                            $achievers_current_period_trs = $row['achievers_current_period_trs'];
                            $tb .= '<td><a href="do.php?_action=view_training_records&_reset=1&ViewTrainingRecords_filter_record_status=2&ViewTrainingRecords_filter_tr_ids=' . $achievers_current_period_trs . '">' . $achievers_current_period . '</a></td>';
                            $achievers_current_year = $row['achievers_current_year'];
                            $achievers_current_year_trs = $row['achievers_current_year_trs'];
                            $tb .= '<td><a href="do.php?_action=view_training_records&_reset=1&ViewTrainingRecords_filter_record_status=2&ViewTrainingRecords_filter_tr_ids=' . $achievers_current_year_trs . '">' . $achievers_current_year . '</a></td>';
                            $withdrawals_current_period = $row['withdrawals_current_period'];
                            $withdrawals_current_period_trs = $row['withdrawals_current_period_trs'];
                            $tb .= '<td><a href="do.php?_action=view_training_records&_reset=1&ViewTrainingRecords_filter_record_status=SHOW_ALL&ViewTrainingRecords_filter_tr_ids=' . $withdrawals_current_period_trs . '">' . $withdrawals_current_period . '</a></td>';
                            $withdrawals_current_year = $row['withdrawals_current_year'];
                            $withdrawals_current_year_trs = $row['withdrawals_current_year_trs'];
                            $tb .= '<td><a href="do.php?_action=view_training_records&_reset=1&ViewTrainingRecords_filter_record_status=SHOW_ALL&ViewTrainingRecords_filter_tr_ids=' . $withdrawals_current_year_trs . '">' . $withdrawals_current_year . '</a></td>';
                            $no_reviews = $row['no_reviews'];
                            $no_reviews_trs = $row['no_reviews_trs'];
                            $tb .= '<td><a href="do.php?_action=view_training_records&_reset=1&ViewTrainingRecords_filter_tr_ids=' . $no_reviews_trs . '">' . $no_reviews . '</a></td>';
                            $tb .= '</tr>';

                            $starts_current_period_gt += $starts_current_period;
                            $starts_current_year_gt += $starts_current_year;
                            $no_assessment_gt += $no_assessment;
                            $live_gt += $live;
                            $withdrawals_gt += $withdrawals;
                            $funded_gt += $funded;
                            $pending_bil_gt += $pending_bil;
                            $progress_gt += $progress;
                            $epa_gt += $epa;
                            $due_current_period_gt += $due_current_period;
                            $achievers_current_period_gt += $achievers_current_period;
                            $achievers_current_year_gt += $achievers_current_year;
                            $withdrawals_current_period_gt += $withdrawals_current_period;
                            $withdrawals_current_year_gt += $withdrawals_current_year;
                            $no_reviews_gt += $no_reviews;
                            $oof_gt += $oof;
        
                        }
                    } 
                    if($tb!="")
                    {
                        $tb .= '<tr>';
                        $tb .= '<td>&nbsp;</td>';
                        $tb .= '<td>Total</td>';
                        $tb .= '<td>' . $starts_current_period_gt . '</td>';
                        $tb .= '<td>' . $starts_current_year_gt . '</td>';
                        $tb .= '<td>' . $no_assessment_gt . '</td>';
                        $tb .= '<td>' . $live_gt . '</td>';
                        $tb .= '<td>' . $epa_gt . '</td>';
                        $tb .= '<td>' . $withdrawals_gt . '</td>';
                        $tb .= '<td>' . $funded_gt . '</td>';
                        $tb .= '<td>' . $oof_gt . '</td>';
                        $tb .= '<td>' . $pending_bil_gt . '</td>';
                        $tb .= '<td>' . $progress_gt . '</td>';
                        $tb .= '<td>' . $due_current_period_gt . '</td>';
                        $tb .= '<td>' . $achievers_current_period_gt . '</td>';
                        $tb .= '<td>' . $achievers_current_year_gt . '</td>';
                        $tb .= '<td>' . $withdrawals_current_period_gt . '</td>';
                        $tb .= '<td>' . $withdrawals_current_year_gt . '</td>';
                        $tb .= '<td>' . $no_reviews_gt . '</td>';
                        $tb .= '</tr>';
                        $t .= $th . $tb . '</tbody></table></div></div>';
                    }
                        
                }
            }
            echo $t;
            exit;
        }
        if($panel == 'LearnerByAgeBandTable')
        {
            echo "Under constructon";
            exit;
        }
        if($panel == 'LearnerByLLDDTable')
        {
            echo "Under constructon";
            exit;
        }
        if($panel == 'LearnerByEthnicityTable')
        {
            echo "Under constructon";
            exit;
        }
        if($panel == 'LearnerByDivisionTable')
        {
            $period = DAO::getObject($link, "SELECT * FROM central.lookup_submission_dates WHERE CURDATE() BETWEEN start_submission_date AND last_submission_date and contract_year = '$contract_year' ORDER BY contract_year DESC LIMIT 1;");
            $year = DAO::getObject($link, "SELECT * FROM contracts where contract_year = '$contract_year' ORDER BY contract_year DESC LIMIT 1;");

            $period_start_date = $period->start_submission_date;
            $period_end_date = $period->last_submission_date;
            $year_start_date = $year->start_date;
            $year_end_date = $year->end_date;

            $submission = DAO::getSingleValue($link, "SELECT submission AS submission FROM central.`lookup_submission_dates` WHERE CURDATE() BETWEEN start_submission_date AND last_submission_date and contract_year = '$contract_year' ORDER BY contract_year DESC LIMIT 1;");
            $trs = DAO::getSingleValue($link, "SELECT GROUP_CONCAT(tr_id) FROM ilr WHERE submission = '$submission' AND contract_id IN (SELECT id FROM contracts WHERE contract_year = (SELECT contract_year FROM contracts where contract_year = '$contract_year' ORDER BY contract_year DESC LIMIT 1)) AND EXTRACTVALUE(ilr, '/Learner/LearningDelivery[AimType=1]/Outcome')=8");

            $sql = "SELECT 
            `name` AS tag
            ,(SELECT COUNT(id) FROM tr WHERE bil_withdrawal is null and start_date >= '$period_start_date' AND start_date <='$period_end_date' AND id IN (SELECT taggable_id FROM taggables WHERE tag_id = tags.`id`)) AS starts_current_period
            ,(SELECT GROUP_CONCAT(id) FROM tr WHERE bil_withdrawal is null and start_date >= '$period_start_date' AND start_date <='$period_end_date' AND id IN (SELECT taggable_id FROM taggables WHERE tag_id = tags.`id`)) AS starts_current_period_trs
            ,(SELECT COUNT(id) FROM tr WHERE bil_withdrawal is null and start_date >= '$year_start_date' AND start_date <='$year_end_date' AND id IN (SELECT taggable_id FROM taggables WHERE tag_id = tags.`id`)) AS starts_current_year
            ,(SELECT GROUP_CONCAT(id) FROM tr WHERE bil_withdrawal is null and start_date >= '$year_start_date' AND start_date <='$year_end_date' AND id IN (SELECT taggable_id FROM taggables WHERE tag_id = tags.`id`)) AS starts_current_year_trs
            ,(SELECT COUNT(id) FROM tr WHERE status_code = 1 and bil_withdrawal is null AND id IN (SELECT taggable_id FROM taggables WHERE tag_id = tags.`id`)) AS live
            ,(SELECT GROUP_CONCAT(id) FROM tr WHERE status_code = 1 and bil_withdrawal is null AND id IN (SELECT taggable_id FROM taggables WHERE tag_id = tags.`id`)) AS live_trs
            ,(SELECT COUNT(id) FROM tr WHERE status_code = 1 and bil_withdrawal is null and id in ($trs) AND id IN (SELECT taggable_id FROM taggables WHERE tag_id = tags.`id`)) AS epa
            ,(SELECT GROUP_CONCAT(id) FROM tr where status_code = 1 and bil_withdrawal is null and id in ($trs) AND id IN (SELECT taggable_id FROM taggables WHERE tag_id = tags.`id`)) AS epa_trs
            ,(SELECT COUNT(id) FROM tr WHERE (status_code = 3 or bil_withdrawal = 2) AND id IN (SELECT taggable_id FROM taggables WHERE tag_id = tags.`id`)) AS withdrawals
            ,(SELECT GROUP_CONCAT(id) FROM tr WHERE (status_code = 3 or bil_withdrawal = 2) AND id IN (SELECT taggable_id FROM taggables WHERE tag_id = tags.`id`)) AS withdrawals_trs
            ,(SELECT COUNT(id) FROM tr WHERE status_code = 1 and bil_withdrawal is null AND target_date > CURDATE() AND id IN (SELECT taggable_id FROM taggables WHERE tag_id = tags.`id`)) AS funded
            ,(SELECT GROUP_CONCAT(id) FROM tr WHERE status_code = 1 and bil_withdrawal is null AND target_date > CURDATE() AND id IN (SELECT taggable_id FROM taggables WHERE tag_id = tags.`id`)) AS funded_trs
            ,(SELECT COUNT(id) FROM tr WHERE status_code = 1 and bil_withdrawal is null AND target_date <= CURDATE() AND id IN (SELECT taggable_id FROM taggables WHERE tag_id = tags.`id`)) AS oof
            ,(SELECT GROUP_CONCAT(id) FROM tr WHERE status_code = 1 and bil_withdrawal is null AND target_date <= CURDATE() AND id IN (SELECT taggable_id FROM taggables WHERE tag_id = tags.`id`)) AS oof_trs
            ,(SELECT COUNT(id) FROM tr WHERE (status_code = 6 or bil_withdrawal = 1) AND id IN (SELECT taggable_id FROM taggables WHERE tag_id = tags.`id`)) AS bil
            ,(SELECT GROUP_CONCAT(id) FROM tr WHERE (status_code = 6 or bil_withdrawal = 1) AND id IN (SELECT taggable_id FROM taggables WHERE tag_id = tags.`id`)) AS bil_trs
            ,(SELECT COUNT(id) FROM tr WHERE (status_code = 6 or bil_withdrawal = 1) AND id IN (SELECT taggable_id FROM taggables WHERE tag_id = tags.`id`) AND l03 NOT IN (SELECT l03 FROM tr AS future WHERE future.start_date > tr.start_date)) AS pending_bil
            ,(SELECT GROUP_CONCAT(id) FROM tr WHERE (status_code = 6 or bil_withdrawal = 1) AND id IN (SELECT taggable_id FROM taggables WHERE tag_id = tags.`id`) AND l03 NOT IN (SELECT l03 FROM tr AS future WHERE future.start_date > tr.start_date)) AS pending_bil_trs
            ,(SELECT COUNT(id) FROM tr WHERE status_code = 1 AND id IN (SELECT taggable_id FROM taggables WHERE tag_id = tags.`id`) AND onefile_id IN (SELECT ID FROM onefile_learners WHERE progress>= 90) and id not in ($trs)) AS progress
            ,(SELECT GROUP_CONCAT(id) FROM tr WHERE status_code = 1 and bil_withdrawal is null AND id IN (SELECT taggable_id FROM taggables WHERE tag_id = tags.`id`) AND onefile_id IN (SELECT ID FROM onefile_learners WHERE progress>= 90) and id not in ($trs)) AS progress_trs
            ,(SELECT COUNT(id) FROM tr WHERE bil_withdrawal is null and target_date >= '$period_start_date' AND target_date <='$period_end_date' AND id IN (SELECT taggable_id FROM taggables WHERE tag_id = tags.`id`)) AS due_current_period
            ,(SELECT GROUP_CONCAT(id) FROM tr WHERE bil_withdrawal is null and target_date >= '$period_start_date' AND target_date <='$period_end_date' AND id IN (SELECT taggable_id FROM taggables WHERE tag_id = tags.`id`)) AS due_current_period_trs
            ,(SELECT COUNT(id) FROM tr WHERE status_code = 2 AND GREATEST(closure_date, COALESCE(achievement_date, '0000-00-00')) >= '$period_start_date' AND GREATEST(closure_date, COALESCE(achievement_date, '0000-00-00')) <='$period_end_date' AND id IN (SELECT taggable_id FROM taggables WHERE tag_id = tags.`id`)) AS achievers_current_period
            ,(SELECT GROUP_CONCAT(id) FROM tr WHERE status_code = 2 AND GREATEST(closure_date, COALESCE(achievement_date, '0000-00-00')) >= '$period_start_date' AND GREATEST(closure_date, COALESCE(achievement_date, '0000-00-00')) <='$period_end_date' AND id IN (SELECT taggable_id FROM taggables WHERE tag_id = tags.`id`)) AS achievers_current_period_trs
            ,(SELECT COUNT(id) FROM tr WHERE status_code = 2 AND GREATEST(closure_date, COALESCE(achievement_date, '0000-00-00')) >= '$year_start_date' AND GREATEST(closure_date, COALESCE(achievement_date, '0000-00-00')) <='$year_end_date' AND id IN (SELECT taggable_id FROM taggables WHERE tag_id = tags.`id`)) AS achievers_current_year
            ,(SELECT GROUP_CONCAT(id) FROM tr WHERE status_code = 2 AND GREATEST(closure_date, COALESCE(achievement_date, '0000-00-00')) >= '$year_start_date' AND GREATEST(closure_date, COALESCE(achievement_date, '0000-00-00')) <='$year_end_date' AND id IN (SELECT taggable_id FROM taggables WHERE tag_id = tags.`id`)) AS achievers_current_year_trs
            ,(SELECT COUNT(id) FROM tr WHERE (status_code = 3 or bil_withdrawal = 2) AND closure_date >= '$period_start_date' AND closure_date <='$period_end_date' AND id IN (SELECT taggable_id FROM taggables WHERE tag_id = tags.`id`)) AS withdrawals_current_period
            ,(SELECT GROUP_CONCAT(id) FROM tr WHERE (status_code = 3 or bil_withdrawal = 2) AND closure_date >= '$period_start_date' AND closure_date <='$period_end_date' AND id IN (SELECT taggable_id FROM taggables WHERE tag_id = tags.`id`)) AS withdrawals_current_period_trs
            ,(SELECT COUNT(id) FROM tr WHERE (status_code = 3 or bil_withdrawal = 2) AND closure_date >= '$year_start_date' AND closure_date <='$year_end_date' AND id IN (SELECT taggable_id FROM taggables WHERE tag_id = tags.`id`)) AS withdrawals_current_year
            ,(SELECT GROUP_CONCAT(id) FROM tr WHERE (status_code = 3 or bil_withdrawal = 2) AND closure_date >= '$year_start_date' AND closure_date <='$year_end_date' AND id IN (SELECT taggable_id FROM taggables WHERE tag_id = tags.`id`)) AS withdrawals_current_year_trs
            ,(SELECT COUNT(id) FROM tr WHERE start_date < (NOW() - INTERVAL 10 WEEK) and status_code = 1 and bil_withdrawal is null AND id IN (SELECT taggable_id FROM taggables WHERE tag_id = tags.`id`) AND (onefile_id IS NULL OR onefile_id NOT IN (SELECT LearnerID FROM onefile_reviews WHERE StartedOn > DATE_SUB(NOW(), INTERVAL 10 WEEK))) and id not in ($trs)) AS no_reviews
            ,(SELECT GROUP_CONCAT(id) FROM tr WHERE start_date < (NOW() - INTERVAL 10 WEEK) and status_code = 1 and bil_withdrawal is null AND id IN (SELECT taggable_id FROM taggables WHERE tag_id = tags.`id`) AND (onefile_id IS NULL OR onefile_id NOT IN (SELECT LearnerID FROM onefile_reviews WHERE StartedOn > DATE_SUB(NOW(), INTERVAL 10 WEEK))) and id not in ($trs)) AS no_reviews_trs
            ,(SELECT COUNT(*) FROM tr WHERE start_date < (NOW() - INTERVAL 6 WEEK) and status_code = 1 and bil_withdrawal is null AND id IN (SELECT taggable_id FROM taggables WHERE tag_id = tags.`id`) and (onefile_id IS NULL OR onefile_id NOT IN (SELECT LearnerID FROM onefile_tlap WHERE AssessorSignedOn > DATE_SUB(NOW(), INTERVAL 6 WEEK))) and id not in ($trs)) as no_assessment
            ,(SELECT group_concat(id) FROM tr WHERE start_date < (NOW() - INTERVAL 6 WEEK) and status_code = 1 and bil_withdrawal is null and id IN (SELECT taggable_id FROM taggables WHERE tag_id = tags.`id`) and (onefile_id IS NULL OR onefile_id NOT IN (SELECT LearnerID FROM onefile_tlap WHERE AssessorSignedOn > DATE_SUB(NOW(), INTERVAL 6 WEEK))) and id not in ($trs)) as no_assessment_trs
            FROM tags 
            WHERE `type` = 'Training Record' 
            AND id IN (SELECT tag_id FROM taggables WHERE taggable_type = 'Training Record' AND taggable_id IN (SELECT id FROM tr));";
            DAO::execute($link, "SET SESSION group_concat_max_len = 1000000;");
            $rows = DAO::getResultset($link, $sql, DAO::FETCH_ASSOC);

            $t = '<div align="center"><table class="table" border="0" cellspacing="0" cellpadding="6">';
            $t.= '<thead style="position: sticky;top: 0"><tr><th class="topRow">&nbsp;</th>';
            $t .= '<th class="topRow">Division</th>';
            $t .= '<th class="topRow">Starts current period</th>';
            $t .= '<th class="topRow">Starts Current Year</th>';
            $t .= '<th class="topRow">In-learning</th>';
            $t .= '<th class="topRow">EPA</th>';
            $t .= '<th class="topRow">No Assessment 6 Weeks</th>';
            $t .= '<th class="topRow">Funded Learners</th>';
            $t .= '<th class="topRow">OOF</th>';
            //$t .= '<th class="topRow">BIL</th>';
            $t .= '<th class="topRow">BIL</th>';
            $t .= '<th class="topRow">Progress 90%</th>';
            $t .= '<th class="topRow">Due current period</th>';
            $t .= '<th class="topRow">Achievers current period</th>';
            $t .= '<th class="topRow">Achievers current year</th>';
            $t .= '<th class="topRow">Withdrawn current period</th>';
            $t .= '<th class="topRow">Withdrawn current year</th>';
            $t .= '<th class="topRow">No review in last 10 weeks</th>';
            $t .= '</tr></thead><tbody>';

            $starts_current_period_gt = 0;
            $starts_current_year_gt = 0;
            $live_gt = 0;
            $epa_gt = 0;
            $no_assessment_gt = 0;
            $funded_gt = 0;
            $oof_gt = 0;
            $pending_bil_gt = 0;
            $progress_gt = 0;
            $due_current_period_gt = 0;
            $achievers_current_period_gt = 0;
            $achievers_current_year_gt = 0;
            $withdrawals_current_period_gt = 0;
            $withdrawals_current_year_gt = 0;
            $no_reviews_gt = 0;

            foreach($rows as $row)
            {
                if($row['starts_current_period']>0 or $row['starts_current_year']>0 or $row['live']>0 or $row['funded']>0 or $row['oof']>0 or $row['pending_bil']>0 or $row['progress']>0 or $row['due_current_period']>0 or $row['achievers_current_period']>0 or $row['achievers_current_year']>0 or $row['withdrawals_current_period']>0 or $row['no_reviews']>0 or $row['withdrawals_current_year']>0)
                {
                    $t .= '<tr>';
                    $t .= '<td>&nbsp;</td>';
                    $t .= '<td>' . $row['tag'] . '</td>';
                    $starts_current_period = $row['starts_current_period'];
                    $starts_current_period_trs = $row['starts_current_period_trs'];
                    $t .= '<td><a href="do.php?_action=view_training_records&_reset=1&ViewTrainingRecords_filter_tr_ids=' . $starts_current_period_trs . '">' . $starts_current_period . '</a></td>';
                    $starts_current_year = $row['starts_current_year'];
                    $starts_current_year_trs = $row['starts_current_year_trs'];
                    $t .= '<td><a href="do.php?_action=view_training_records&_reset=1&ViewTrainingRecords_filter_tr_ids=' . $starts_current_year_trs . '">' . $starts_current_year . '</a></td>';
                    $live = $row['live'];
                    $live_trs = $row['live_trs'];
                    $t .= '<td><a href="do.php?_action=view_training_records&_reset=1&ViewTrainingRecords_filter_tr_ids=' . $live_trs . '">' . $live . '</a></td>';
                    $epa = $row['epa'];
                    $epa_trs = $row['epa_trs'];
                    $t .= '<td><a href="do.php?_action=view_training_records&_reset=1&ViewTrainingRecords_filter_tr_ids=' . $epa_trs . '">' . $epa . '</a></td>';
                    //$withdrawals = $row['withdrawals'];
                    //$withdrawals_trs = $row['withdrawals_trs'];
                    //$t .= '<td><a href="do.php?_action=view_training_records&_reset=1&ViewTrainingRecords_filter_record_status=3&ViewTrainingRecords_filter_tr_ids=' . $withdrawals_trs . '">' . $withdrawals . '</a></td>';
                    $no_assessment = $row['no_assessment'];
                    $no_assessment_trs = $row['no_assessment_trs'];
                    $t .= '<td><a href="do.php?_action=view_training_records&_reset=1&ViewTrainingRecords_filter_record_status=SHOW_ALL&ViewTrainingRecords_filter_tr_ids=' . $no_assessment_trs . '">' . $no_assessment . '</a></td>';
                    $funded = $row['funded'];
                    $funded_trs = $row['funded_trs'];
                    $t .= '<td><a href="do.php?_action=view_training_records&_reset=1&ViewTrainingRecords_filter_tr_ids=' . $funded_trs . '">' . $funded . '</a></td>';
                    $oof = $row['oof'];
                    $oof_trs = $row['oof_trs'];
                    $t .= '<td><a href="do.php?_action=view_training_records&_reset=1&ViewTrainingRecords_filter_tr_ids=' . $oof_trs . '">' . $oof . '</a></td>';
                    //$bil = $row['bil'];
                    //$bil_trs = $row['bil_trs'];
                    //$t .= '<td><a href="do.php?_action=view_training_records&_reset=1&ViewTrainingRecords_filter_record_status=6&ViewTrainingRecords_filter_tr_ids=' . $bil_trs . '">' . $bil . '</a></td>';
                    $pending_bil = $row['pending_bil'];
                    $pending_bil_trs = $row['pending_bil_trs'];
                    $t .= '<td><a href="do.php?_action=view_training_records&_reset=1&ViewTrainingRecords_filter_record_status=SHOW_ALL&ViewTrainingRecords_filter_tr_ids=' . $pending_bil_trs . '">' . $pending_bil . '</a></td>';
                    $progress = $row['progress'];
                    $progress_trs = $row['progress_trs'];
                    $t .= '<td><a href="do.php?_action=view_training_records&_reset=1&ViewTrainingRecords_filter_tr_ids=' . $progress_trs . '">' . $progress . '</a></td>';
                    $due_current_period = $row['due_current_period'];
                    $due_current_period_trs = $row['due_current_period_trs'];
                    $t .= '<td><a href="do.php?_action=view_training_records&_reset=1&ViewTrainingRecords_filter_tr_ids=' . $due_current_period_trs . '">' . $due_current_period . '</a></td>';
                    $achievers_current_period = $row['achievers_current_period'];
                    $achievers_current_period_trs = $row['achievers_current_period_trs'];
                    $t .= '<td><a href="do.php?_action=view_training_records&_reset=1&ViewTrainingRecords_filter_record_status=2&ViewTrainingRecords_filter_tr_ids=' . $achievers_current_period_trs . '">' . $achievers_current_period . '</a></td>';
                    $achievers_current_year = $row['achievers_current_year'];
                    $achievers_current_year_trs = $row['achievers_current_year_trs'];
                    $t .= '<td><a href="do.php?_action=view_training_records&_reset=1&ViewTrainingRecords_filter_record_status=2&ViewTrainingRecords_filter_tr_ids=' . $achievers_current_year_trs . '">' . $achievers_current_year . '</a></td>';
                    $withdrawals_current_period = $row['withdrawals_current_period'];
                    $withdrawals_current_period_trs = $row['withdrawals_current_period_trs'];
                    $t .= '<td><a href="do.php?_action=view_training_records&_reset=1&ViewTrainingRecords_filter_record_status=SHOW_ALL&ViewTrainingRecords_filter_tr_ids=' . $withdrawals_current_period_trs . '">' . $withdrawals_current_period . '</a></td>';
                    $withdrawals_current_year = $row['withdrawals_current_year'];
                    $withdrawals_current_year_trs = $row['withdrawals_current_year_trs'];
                    $t .= '<td><a href="do.php?_action=view_training_records&_reset=1&ViewTrainingRecords_filter_record_status=SHOW_ALL&ViewTrainingRecords_filter_tr_ids=' . $withdrawals_current_year_trs . '">' . $withdrawals_current_year . '</a></td>';
                    $no_reviews = $row['no_reviews'];
                    $no_reviews_trs = $row['no_reviews_trs'];
                    $t .= '<td><a href="do.php?_action=view_training_records&_reset=1&ViewTrainingRecords_filter_tr_ids=' . $no_reviews_trs . '">' . $no_reviews . '</a></td>';
                    $t .= '</tr>';

                    $starts_current_period_gt += $starts_current_period;
                    $starts_current_year_gt += $starts_current_year;
                    $live_gt += $live;
                    $epa_gt += $epa;
                    $no_assessment_gt += $no_assessment;
                    $funded_gt += $funded;
                    $oof_gt += $oof;
                    $pending_bil_gt += $pending_bil;
                    $progress_gt += $progress;
                    $due_current_period_gt += $due_current_period;
                    $achievers_current_period_gt += $achievers_current_period;
                    $achievers_current_year_gt += $achievers_current_year;
                    $withdrawals_current_period_gt += $withdrawals_current_period;
                    $withdrawals_current_year_gt += $withdrawals_current_year;
                    $no_reviews_gt += $no_reviews;
                    
                }
            } 
            $t .= '<tr>';
            $t .= '<td>&nbsp;</td>';
            $t .= '<td>Total</td>';
            $t .= '<td>' . $starts_current_period_gt . '</td>';
            $t .= '<td>' . $starts_current_year_gt . '</td>';
            $t .= '<td>' . $live_gt . '</td>';
            $t .= '<td>' . $epa_gt . '</td>';
            $t .= '<td>' . $no_assessment_gt . '</td>';
            $t .= '<td>' . $funded_gt . '</td>';
            $t .= '<td>' . $oof_gt . '</td>';
            $t .= '<td>' . $pending_bil_gt . '</td>';
            $t .= '<td>' . $progress_gt . '</td>';
            $t .= '<td>' . $due_current_period_gt . '</td>';
            $t .= '<td>' . $achievers_current_period_gt . '</td>';
            $t .= '<td>' . $achievers_current_year_gt . '</td>';
            $t .= '<td>' . $withdrawals_current_period_gt . '</td>';
            $t .= '<td>' . $withdrawals_current_year_gt . '</td>';
            $t .= '<td>' . $no_reviews_gt . '</td>';
            $t .= '</tr>';
            $t .= '</tbody></table></div>';
            echo $t;
            exit;
        }

        if($panel == 'LearnerBySSAChartApp')
        {
            echo "Under constructon";
            exit;
        }

        if($panel == 'LearnerBySSAChartEducation')
        {
            echo "Under constructon";
            exit;
        }

        if($panel == 'LearnerByContractTable')
        {
            $period = DAO::getObject($link, "SELECT * FROM central.lookup_submission_dates WHERE CURDATE() BETWEEN start_submission_date AND last_submission_date and contract_year = '$contract_year' ORDER BY contract_year DESC LIMIT 1;");
            $year = DAO::getObject($link, "SELECT * FROM contracts where contract_year = '$contract_year' ORDER BY contract_year DESC LIMIT 1;");

            $period_start_date = $period->start_submission_date;
            $period_end_date = $period->last_submission_date;
            $year_start_date = $year->start_date;
            $year_end_date = $year->end_date;

            $submission = DAO::getSingleValue($link, "SELECT submission AS submission FROM central.`lookup_submission_dates` WHERE CURDATE() BETWEEN start_submission_date AND last_submission_date and contract_year = '$contract_year' ORDER BY contract_year DESC LIMIT 1;");
            $trs = DAO::getSingleValue($link, "SELECT GROUP_CONCAT(tr_id) FROM ilr WHERE submission = '$submission' AND contract_id IN (SELECT id FROM contracts WHERE contract_year = (SELECT contract_year FROM contracts where contract_year = '$contract_year' ORDER BY contract_year DESC LIMIT 1)) AND EXTRACTVALUE(ilr, '/Learner/LearningDelivery[AimType=1]/Outcome')=8");

            $sql = "SELECT 
            `title` AS contract
            ,(SELECT COUNT(id) FROM tr WHERE bil_withdrawal is null and start_date >= '$period_start_date' AND start_date <='$period_end_date' AND contract_id = contracts.id) AS starts_current_period
            ,(SELECT GROUP_CONCAT(id) FROM tr WHERE bil_withdrawal is null and start_date >= '$period_start_date' AND start_date <='$period_end_date' AND contract_id = contracts.id) AS starts_current_period_trs
            ,(SELECT COUNT(id) FROM tr WHERE bil_withdrawal is null and start_date >= '$year_start_date' AND start_date <='$year_end_date' AND contract_id = contracts.id) AS starts_current_year
            ,(SELECT GROUP_CONCAT(id) FROM tr WHERE bil_withdrawal is null and start_date >= '$year_start_date' AND start_date <='$year_end_date' AND contract_id = contracts.id) AS starts_current_year_trs
            ,(SELECT COUNT(id) FROM tr WHERE status_code = 1 and bil_withdrawal is null AND contract_id = contracts.id) AS live
            ,(SELECT GROUP_CONCAT(id) FROM tr WHERE status_code = 1 and bil_withdrawal is null AND contract_id = contracts.id) AS live_trs
            ,(SELECT COUNT(id) FROM tr WHERE status_code = 1 and bil_withdrawal is null AND contract_id = contracts.id and id in ($trs)) AS epa
            ,(SELECT GROUP_CONCAT(id) FROM tr WHERE status_code = 1 and bil_withdrawal is null AND contract_id = contracts.id and id in ($trs)) AS epa_trs
            ,(SELECT COUNT(id) FROM tr WHERE (status_code = 3 or bil_withdrawal = 2) AND contract_id = contracts.id) AS withdrawals
            ,(SELECT GROUP_CONCAT(id) FROM tr WHERE (status_code = 3 or bil_withdrawal = 2) AND contract_id = contracts.id) AS withdrawals_trs
            ,(SELECT COUNT(id) FROM tr WHERE status_code = 1 and bil_withdrawal is null AND target_date > CURDATE() AND contract_id = contracts.id) AS funded
            ,(SELECT GROUP_CONCAT(id) FROM tr WHERE status_code = 1 and bil_withdrawal is null AND target_date > CURDATE() AND contract_id = contracts.id) AS funded_trs
            ,(SELECT COUNT(id) FROM tr WHERE status_code = 1 and bil_withdrawal is null AND target_date <= CURDATE() AND contract_id = contracts.id) AS oof
            ,(SELECT GROUP_CONCAT(id) FROM tr WHERE status_code = 1 and bil_withdrawal is null AND target_date <= CURDATE() AND contract_id = contracts.id) AS oof_trs
            ,(SELECT COUNT(id) FROM tr WHERE (status_code = 6 or bil_withdrawal = 1) AND contract_id = contracts.id) AS bil
            ,(SELECT GROUP_CONCAT(id) FROM tr WHERE (status_code = 6 or bil_withdrawal = 1) AND contract_id = contracts.id) AS bil_trs
            ,(SELECT COUNT(id) FROM tr WHERE (status_code = 6 or bil_withdrawal = 1) AND contract_id = contracts.id AND l03 NOT IN (SELECT l03 FROM tr AS future WHERE future.start_date > tr.start_date)) AS pending_bil
            ,(SELECT GROUP_CONCAT(id) FROM tr WHERE (status_code = 6 or bil_withdrawal = 1) AND contract_id = contracts.id AND l03 NOT IN (SELECT l03 FROM tr AS future WHERE future.start_date > tr.start_date)) AS pending_bil_trs
            ,(SELECT COUNT(id) FROM tr WHERE status_code = 1 and bil_withdrawal is null AND contract_id = contracts.id AND onefile_id IN (SELECT ID FROM onefile_learners WHERE progress>= 90) and id not in ($trs)) AS progress
            ,(SELECT GROUP_CONCAT(id) FROM tr WHERE status_code = 1 and bil_withdrawal is null AND contract_id = contracts.id AND onefile_id IN (SELECT ID FROM onefile_learners WHERE progress>= 90) and id not in ($trs)) AS progress_trs
            ,(SELECT COUNT(id) FROM tr WHERE bil_withdrawal is null and target_date >= '$period_start_date' AND target_date <='$period_end_date' AND contract_id = contracts.id) AS due_current_period
            ,(SELECT GROUP_CONCAT(id) FROM tr WHERE bil_withdrawal is null and target_date >= '$period_start_date' AND target_date <='$period_end_date' AND contract_id = contracts.id) AS due_current_period_trs
            ,(SELECT COUNT(id) FROM tr WHERE status_code = 2 AND GREATEST(closure_date, COALESCE(achievement_date, '0000-00-00')) >= '$period_start_date' AND GREATEST(closure_date, COALESCE(achievement_date, '0000-00-00')) <='$period_end_date' AND contract_id = contracts.id) AS achievers_current_period
            ,(SELECT GROUP_CONCAT(id) FROM tr WHERE status_code = 2 AND GREATEST(closure_date, COALESCE(achievement_date, '0000-00-00')) >= '$period_start_date' AND GREATEST(closure_date, COALESCE(achievement_date, '0000-00-00')) <='$period_end_date' AND contract_id = contracts.id) AS achievers_current_period_trs
            ,(SELECT COUNT(id) FROM tr WHERE status_code = 2 AND GREATEST(closure_date, COALESCE(achievement_date, '0000-00-00')) >= '$year_start_date' AND GREATEST(closure_date, COALESCE(achievement_date, '0000-00-00')) <='$year_end_date' AND contract_id = contracts.id) AS achievers_current_year
            ,(SELECT GROUP_CONCAT(id) FROM tr WHERE status_code = 2 AND GREATEST(closure_date, COALESCE(achievement_date, '0000-00-00')) >= '$year_start_date' AND GREATEST(closure_date, COALESCE(achievement_date, '0000-00-00')) <='$year_end_date' AND contract_id = contracts.id) AS achievers_current_year_trs
            ,(SELECT COUNT(id) FROM tr WHERE (status_code = 3 or bil_withdrawal = 2) AND closure_date >= '$period_start_date' AND closure_date <='$period_end_date' AND contract_id = contracts.id) AS withdrawals_current_period
            ,(SELECT GROUP_CONCAT(id) FROM tr WHERE (status_code = 3 or bil_withdrawal = 2) AND closure_date >= '$period_start_date' AND closure_date <='$period_end_date' AND contract_id = contracts.id) AS withdrawals_current_period_trs
            ,(SELECT COUNT(id) FROM tr WHERE (status_code = 3 or bil_withdrawal = 2) AND closure_date >= '$year_start_date' AND closure_date <='$year_end_date' AND contract_id = contracts.id) AS withdrawals_current_year
            ,(SELECT GROUP_CONCAT(id) FROM tr WHERE (status_code = 3 or bil_withdrawal = 2) AND closure_date >= '$year_start_date' AND closure_date <='$year_end_date' AND contract_id = contracts.id) AS withdrawals_current_year_trs
            ,(SELECT COUNT(id) FROM tr WHERE start_date < (NOW() - INTERVAL 10 WEEK) and status_code = 1 and bil_withdrawal is null AND contract_id = contracts.id AND (onefile_id IS NULL OR onefile_id NOT IN (SELECT LearnerID FROM onefile_reviews WHERE StartedOn > DATE_SUB(NOW(), INTERVAL 10 WEEK))) and id not in ($trs)) AS no_reviews
            ,(SELECT GROUP_CONCAT(id) FROM tr WHERE start_date < DATE_SUB(NOW(), INTERVAL 10 WEEK) and status_code = 1 and bil_withdrawal is null AND contract_id = contracts.id AND (onefile_id IS NULL OR onefile_id NOT IN (SELECT LearnerID FROM onefile_reviews WHERE StartedOn > DATE_SUB(NOW(), INTERVAL 10 WEEK))) and id not in ($trs)) AS no_reviews_trs
            ,(SELECT COUNT(*) FROM tr WHERE start_date < DATE_SUB(NOW(), INTERVAL 6 WEEK) and status_code = 1 and bil_withdrawal is null AND contract_id = contracts.id and (onefile_id IS NULL OR onefile_id NOT IN (SELECT LearnerID FROM onefile_tlap WHERE AssessorSignedOn > DATE_SUB(NOW(), INTERVAL 6 WEEK))) and id not in ($trs)) as no_assessment
            ,(SELECT group_concat(id) FROM tr WHERE start_date < DATE_SUB(NOW(), INTERVAL 6 WEEK) and status_code = 1 and bil_withdrawal is null and contract_id = contracts.id and (onefile_id IS NULL OR onefile_id NOT IN (SELECT LearnerID FROM onefile_tlap WHERE AssessorSignedOn > DATE_SUB(NOW(), INTERVAL 6 WEEK))) and id not in ($trs)) as no_assessment_trs
            FROM contracts 
            WHERE id IN (SELECT contract_id FROM tr WHERE status_code = 1);";
            DAO::execute($link, "SET SESSION group_concat_max_len = 1000000;");
            $rows = DAO::getResultset($link, $sql, DAO::FETCH_ASSOC);

            $t = '<div align="center"><table class="table" border="0" cellspacing="0" cellpadding="6">';
            $t.= '<thead style="position: sticky;top: 0"><tr><th class="topRow">&nbsp;</th>';
            $t .= '<th class="topRow">Contract</th>';
            $t .= '<th class="topRow">Starts current period</th>';
            $t .= '<th class="topRow">Starts Current Year</th>';
            $t .= '<th class="topRow">In-learning</th>';
            $t .= '<th class="topRow">EPA</th>';
            $t .= '<th class="topRow">No Assessment 6 Weeks</th>';
            $t .= '<th class="topRow">Withdrawn</th>';
            $t .= '<th class="topRow">Funded Learners</th>';
            $t .= '<th class="topRow">OOF</th>';
            $t .= '<th class="topRow">BIL</th>';
            //$t .= '<th class="topRow">BIL Pending</th>';
            $t .= '<th class="topRow">Progress 90%</th>';
            $t .= '<th class="topRow">Due current period</th>';
            $t .= '<th class="topRow">Achievers current period</th>';
            $t .= '<th class="topRow">Achievers current year</th>';
            $t .= '<th class="topRow">Withdrawn current period</th>';
            $t .= '<th class="topRow">Withdrawn current year</th>';
            $t .= '<th class="topRow">No review in last 10 weeks</th>';
            $t .= '</tr></thead><tbody>';

            $starts_current_period_gt = 0;
            $starts_current_year_gt = 0;
            $live_gt = 0;
            $epa_gt = 0;
            $no_assessment_gt = 0;
            $withdrawals_gt = 0;
            $funded_gt = 0;
            $oof_gt = 0;
            $pending_bil_gt = 0;
            $progress_gt = 0;
            $due_current_period_gt = 0;
            $achievers_current_period_gt = 0;
            $achievers_current_year_gt = 0;
            $withdrawals_current_period_gt = 0;
            $withdrawals_current_year_gt = 0;
            $no_reviews_gt = 0;

            foreach($rows as $row)
            {
                $t .= '<tr>';
                $t .= '<td>&nbsp;</td>';
                $t .= '<td>' . $row['contract'] . '</td>';
                $starts_current_period = $row['starts_current_period'];
                $starts_current_period_trs = $row['starts_current_period_trs'];
                $t .= '<td><a href="do.php?_action=view_training_records&_reset=1&ViewTrainingRecords_filter_tr_ids=' . $starts_current_period_trs . '">' . $starts_current_period . '</a></td>';
                $starts_current_year = $row['starts_current_year'];
                $starts_current_year_trs = $row['starts_current_year_trs'];
                $t .= '<td><a href="do.php?_action=view_training_records&_reset=1&ViewTrainingRecords_filter_tr_ids=' . $starts_current_year_trs . '">' . $starts_current_year . '</a></td>';
                $live = $row['live'];
                $live_trs = $row['live_trs'];
                $t .= '<td><a href="do.php?_action=view_training_records&_reset=1&ViewTrainingRecords_filter_tr_ids=' . $live_trs . '">' . $live . '</a></td>';
                $epa = $row['epa'];
                $epa_trs = $row['epa_trs'];
                $t .= '<td><a href="do.php?_action=view_training_records&_reset=1&ViewTrainingRecords_filter_tr_ids=' . $epa_trs . '">' . $epa . '</a></td>';
                $no_assessment = $row['no_assessment'];
                $no_assessment_trs = $row['no_assessment_trs'];
                $t .= '<td><a href="do.php?_action=view_training_records&_reset=1&ViewTrainingRecords_filter_record_status=SHOW_ALL&ViewTrainingRecords_filter_tr_ids=' . $no_assessment_trs . '">' . $no_assessment . '</a></td>';
                $withdrawals = $row['withdrawals'];
                $withdrawals_trs = $row['withdrawals_trs'];
                $t .= '<td><a href="do.php?_action=view_training_records&_reset=1&ViewTrainingRecords_filter_record_status=SHOW_ALL&ViewTrainingRecords_filter_tr_ids=' . $withdrawals_trs . '">' . $withdrawals . '</a></td>';
                $funded = $row['funded'];
                $funded_trs = $row['funded_trs'];
                $t .= '<td><a href="do.php?_action=view_training_records&_reset=1&ViewTrainingRecords_filter_tr_ids=' . $funded_trs . '">' . $funded . '</a></td>';
                $oof = $row['oof'];
                $oof_trs = $row['oof_trs'];
                $t .= '<td><a href="do.php?_action=view_training_records&_reset=1&ViewTrainingRecords_filter_tr_ids=' . $oof_trs . '">' . $oof . '</a></td>';
                //$bil = $row['bil'];
                //$bil_trs = $row['bil_trs'];
                //$t .= '<td><a href="do.php?_action=view_training_records&_reset=1&ViewTrainingRecords_filter_record_status=SHOW_ALL&ViewTrainingRecords_filter_tr_ids=' . $bil_trs . '">' . $bil . '</a></td>';
                $pending_bil = $row['pending_bil'];
                $pending_bil_trs = $row['pending_bil_trs'];
                $t .= '<td><a href="do.php?_action=view_training_records&_reset=1&ViewTrainingRecords_filter_record_status=SHOW_ALL&ViewTrainingRecords_filter_tr_ids=' . $pending_bil_trs . '">' . $pending_bil . '</a></td>';
                $progress = $row['progress'];
                $progress_trs = $row['progress_trs'];
                $t .= '<td><a href="do.php?_action=view_training_records&_reset=1&ViewTrainingRecords_filter_tr_ids=' . $progress_trs . '">' . $progress . '</a></td>';
                $due_current_period = $row['due_current_period'];
                $due_current_period_trs = $row['due_current_period_trs'];
                $t .= '<td><a href="do.php?_action=view_training_records&_reset=1&ViewTrainingRecords_filter_tr_ids=' . $due_current_period_trs . '">' . $due_current_period . '</a></td>';
                $achievers_current_period = $row['achievers_current_period'];
                $achievers_current_period_trs = $row['achievers_current_period_trs'];
                $t .= '<td><a href="do.php?_action=view_training_records&_reset=1&ViewTrainingRecords_filter_record_status=2&ViewTrainingRecords_filter_tr_ids=' . $achievers_current_period_trs . '">' . $achievers_current_period . '</a></td>';
                $achievers_current_year = $row['achievers_current_year'];
                $achievers_current_year_trs = $row['achievers_current_year_trs'];
                $t .= '<td><a href="do.php?_action=view_training_records&_reset=1&ViewTrainingRecords_filter_record_status=2&ViewTrainingRecords_filter_tr_ids=' . $achievers_current_year_trs . '">' . $achievers_current_year . '</a></td>';
                $withdrawals_current_period = $row['withdrawals_current_period'];
                $withdrawals_current_period_trs = $row['withdrawals_current_period_trs'];
                $t .= '<td><a href="do.php?_action=view_training_records&_reset=1&ViewTrainingRecords_filter_record_status=SHOW_ALL&ViewTrainingRecords_filter_tr_ids=' . $withdrawals_current_period_trs . '">' . $withdrawals_current_period . '</a></td>';
                $withdrawals_current_year = $row['withdrawals_current_year'];
                $withdrawals_current_year_trs = $row['withdrawals_current_year_trs'];
                $t .= '<td><a href="do.php?_action=view_training_records&_reset=1&ViewTrainingRecords_filter_record_status=SHOW_ALL&ViewTrainingRecords_filter_tr_ids=' . $withdrawals_current_year_trs . '">' . $withdrawals_current_year . '</a></td>';
                $no_reviews = $row['no_reviews'];
                $no_reviews_trs = $row['no_reviews_trs'];
                $t .= '<td><a href="do.php?_action=view_training_records&_reset=1&ViewTrainingRecords_filter_tr_ids=' . $no_reviews_trs . '">' . $no_reviews . '</a></td>';
                $t .= '</tr>';

                $starts_current_period_gt += $starts_current_period;
                $starts_current_year_gt += $starts_current_year;
                $live_gt += $live;
                $epa_gt += $epa;
                $no_assessment_gt += $no_assessment;
                $withdrawals_gt += $withdrawals;
                $funded_gt += $funded;
                $oof_gt += $oof;
                $pending_bil_gt += $pending_bil;
                $progress_gt += $progress;
                $due_current_period_gt += $due_current_period;
                $achievers_current_period_gt += $achievers_current_period;
                $achievers_current_year_gt += $achievers_current_year;
                $withdrawals_current_period_gt += $withdrawals_current_period;
                $withdrawals_current_year_gt += $withdrawals_current_year;
                $no_reviews_gt += $no_reviews;
    
            } 

            $t .= '<tr>';
            $t .= '<td>&nbsp;</td>';
            $t .= '<td>Total</td>';
            $t .= '<td>' . $starts_current_period_gt . '</td>';
            $t .= '<td>' . $starts_current_year_gt . '</td>';
            $t .= '<td>' . $live_gt . '</td>';
            $t .= '<td>' . $epa_gt . '</td>';
            $t .= '<td>' . $no_assessment_gt . '</td>';
            $t .= '<td>' . $withdrawals_gt . '</td>';
            $t .= '<td>' . $funded_gt . '</td>';
            $t .= '<td>' . $oof_gt . '</td>';
            $t .= '<td>' . $pending_bil_gt . '</td>';
            $t .= '<td>' . $progress_gt . '</td>';
            $t .= '<td>' . $due_current_period_gt . '</td>';
            $t .= '<td>' . $achievers_current_period_gt . '</td>';
            $t .= '<td>' . $achievers_current_year_gt . '</td>';
            $t .= '<td>' . $withdrawals_current_period_gt . '</td>';
            $t .= '<td>' . $withdrawals_current_year_gt . '</td>';
            $t .= '<td>' . $no_reviews_gt . '</td>';
            $t .= '</tr>';

            $t .= '</tbody></table></div>';
            echo $t;
            exit;
        }

        if($panel == 'LearnerByFrameworkChartApp')
        {
            echo "Under constructon";
            exit;
        }

        if($panel == 'LearnerByAssessorTable')
        {
            $period = DAO::getObject($link, "SELECT * FROM central.lookup_submission_dates WHERE CURDATE() BETWEEN start_submission_date AND last_submission_date and contract_year = '$contract_year' ORDER BY contract_year DESC LIMIT 1;");
            $year = DAO::getObject($link, "SELECT * FROM contracts where contract_year = '$contract_year' ORDER BY contract_year DESC LIMIT 1;");

            $period_start_date = $period->start_submission_date;
            $period_end_date = $period->last_submission_date;
            $year_start_date = $year->start_date;
            $year_end_date = $year->end_date;

            $submission = DAO::getSingleValue($link, "SELECT submission AS submission FROM central.`lookup_submission_dates` WHERE CURDATE() BETWEEN start_submission_date AND last_submission_date and contract_year = '$contract_year' ORDER BY contract_year DESC LIMIT 1;");
            $trs = DAO::getSingleValue($link, "SELECT GROUP_CONCAT(tr_id) FROM ilr WHERE submission = '$submission' AND contract_id IN (SELECT id FROM contracts WHERE contract_year = (SELECT contract_year FROM contracts where contract_year = '$contract_year' ORDER BY contract_year DESC LIMIT 1)) AND EXTRACTVALUE(ilr, '/Learner/LearningDelivery[AimType=1]/Outcome')=8");

            $t ="";
            $st = $link->query("select distinct tag_name from tr where tag_name is not null");
            if($st)
            {
                while($tags = $st->fetch(PDO::FETCH_ASSOC))
                {
                    $tag = $tags['tag_name'];    
                    $sql = "SELECT 
                    concat(firstnames,' ',surname) AS assessor
                    ,(SELECT COUNT(id) FROM tr WHERE bil_withdrawal is null and tag_name = '$tag' and start_date >= '$period_start_date' AND start_date <='$period_end_date' AND users.id = tr.assessor) AS starts_current_period
                    ,(SELECT GROUP_CONCAT(id) FROM tr WHERE bil_withdrawal is null and tag_name = '$tag' and start_date >= '$period_start_date' AND start_date <='$period_end_date' AND users.id = tr.assessor) AS starts_current_period_trs
                    ,(SELECT COUNT(id) FROM tr WHERE bil_withdrawal is null and tag_name = '$tag' and start_date >= '$year_start_date' AND start_date <='$year_end_date' AND users.id = tr.assessor) AS starts_current_year
                    ,(SELECT GROUP_CONCAT(id) FROM tr WHERE bil_withdrawal is null and tag_name = '$tag' and start_date >= '$year_start_date' AND start_date <='$year_end_date' AND users.id = tr.assessor) AS starts_current_year_trs
                    ,(SELECT COUNT(id) FROM tr WHERE status_code = 1 and bil_withdrawal is null and tag_name = '$tag' AND users.id = tr.assessor) AS live
                    ,(SELECT GROUP_CONCAT(id) FROM tr WHERE status_code = 1 and bil_withdrawal is null and tag_name = '$tag' AND users.id = tr.assessor) AS live_trs
                    ,(SELECT COUNT(id) FROM tr WHERE status_code = 1 and bil_withdrawal is null and tag_name = '$tag' AND users.id = tr.assessor and id in ($trs)) AS epa
                    ,(SELECT GROUP_CONCAT(id) FROM tr WHERE status_code = 1 and bil_withdrawal is null and tag_name = '$tag' AND users.id = tr.assessor and id in ($trs)) AS epa_trs
                    ,(SELECT COUNT(id) FROM tr WHERE tag_name = '$tag' and (status_code = 3 or bil_withdrawal = 2) AND users.id = tr.assessor) AS withdrawals
                    ,(SELECT GROUP_CONCAT(id) FROM tr WHERE tag_name = '$tag' and (status_code = 3 or bil_withdrawal = 2) AND users.id = tr.assessor) AS withdrawals_trs
                    ,(SELECT COUNT(id) FROM tr WHERE status_code = 1 and bil_withdrawal is null and tag_name = '$tag' AND target_date > CURDATE() AND users.id = tr.assessor) AS funded
                    ,(SELECT GROUP_CONCAT(id) FROM tr WHERE status_code = 1 and bil_withdrawal is null and tag_name = '$tag' AND target_date > CURDATE() AND users.id = tr.assessor) AS funded_trs
                    ,(SELECT COUNT(id) FROM tr WHERE status_code = 1 and bil_withdrawal is null and tag_name = '$tag' AND target_date <= CURDATE() AND users.id = tr.assessor) AS oof
                    ,(SELECT GROUP_CONCAT(id) FROM tr WHERE status_code = 1 and bil_withdrawal is null and tag_name = '$tag' AND target_date <= CURDATE() AND users.id = tr.assessor) AS oof_trs
                    ,(SELECT COUNT(id) FROM tr WHERE tag_name = '$tag' and (status_code = 6 or bil_withdrawal = 1) AND users.id = tr.assessor) AS bil
                    ,(SELECT GROUP_CONCAT(id) FROM tr WHERE tag_name = '$tag' and (status_code = 6 or bil_withdrawal = 1) AND users.id = tr.assessor) AS bil_trs
                    ,(SELECT COUNT(id) FROM tr WHERE tag_name = '$tag' and (status_code = 6 or bil_withdrawal = 1) AND users.id = tr.assessor AND l03 NOT IN (SELECT l03 FROM tr AS future WHERE future.start_date > tr.start_date)) AS pending_bil
                    ,(SELECT GROUP_CONCAT(id) FROM tr WHERE tag_name = '$tag' and (status_code = 6 or bil_withdrawal = 1) AND users.id = tr.assessor AND l03 NOT IN (SELECT l03 FROM tr AS future WHERE future.start_date > tr.start_date)) AS pending_bil_trs
                    ,(SELECT COUNT(id) FROM tr WHERE status_code = 1 and bil_withdrawal is null and tag_name = '$tag' AND users.id = tr.assessor AND onefile_id IN (SELECT ID FROM onefile_learners WHERE progress>= 90)) AS progress
                    ,(SELECT GROUP_CONCAT(id) FROM tr WHERE status_code = 1 and bil_withdrawal is null and tag_name = '$tag' AND users.id = tr.assessor AND onefile_id IN (SELECT ID FROM onefile_learners WHERE progress>= 90)) AS progress_trs
                    ,(SELECT COUNT(id) FROM tr WHERE bil_withdrawal is null and tag_name = '$tag' and target_date >= '$period_start_date' AND target_date <='$period_end_date' AND users.id = tr.assessor) AS due_current_period
                    ,(SELECT GROUP_CONCAT(id) FROM tr WHERE bil_withdrawal is null and tag_name = '$tag' and target_date >= '$period_start_date' AND target_date <='$period_end_date' AND users.id = tr.assessor) AS due_current_period_trs
                    ,(SELECT COUNT(id) FROM tr WHERE status_code = 2 and tag_name = '$tag' AND GREATEST(closure_date, COALESCE(achievement_date, '0000-00-00')) >= '$period_start_date' AND GREATEST(closure_date, COALESCE(achievement_date, '0000-00-00')) <='$period_end_date' AND users.id = tr.assessor) AS achievers_current_period
                    ,(SELECT GROUP_CONCAT(id) FROM tr WHERE status_code = 2 and tag_name = '$tag' AND GREATEST(closure_date, COALESCE(achievement_date, '0000-00-00')) >= '$period_start_date' AND GREATEST(closure_date, COALESCE(achievement_date, '0000-00-00')) <='$period_end_date' AND users.id = tr.assessor) AS achievers_current_period_trs
                    ,(SELECT COUNT(id) FROM tr WHERE status_code = 2 and tag_name = '$tag' AND GREATEST(closure_date, COALESCE(achievement_date, '0000-00-00')) >= '$year_start_date' AND GREATEST(closure_date, COALESCE(achievement_date, '0000-00-00')) <='$year_end_date' AND users.id = tr.assessor) AS achievers_current_year
                    ,(SELECT GROUP_CONCAT(id) FROM tr WHERE status_code = 2 and tag_name = '$tag' AND GREATEST(closure_date, COALESCE(achievement_date, '0000-00-00')) >= '$year_start_date' AND GREATEST(closure_date, COALESCE(achievement_date, '0000-00-00')) <='$year_end_date' AND users.id = tr.assessor) AS achievers_current_year_trs
                    ,(SELECT COUNT(id) FROM tr WHERE (status_code = 3 or bil_withdrawal = 2) and tag_name = '$tag' AND closure_date >= '$period_start_date' AND closure_date <='$period_end_date' AND users.id = tr.assessor) AS withdrawals_current_period
                    ,(SELECT GROUP_CONCAT(id) FROM tr WHERE (status_code = 3 or bil_withdrawal = 2) and tag_name = '$tag' AND closure_date >= '$period_start_date' AND closure_date <='$period_end_date' AND users.id = tr.assessor) AS withdrawals_current_period_trs
                    ,(SELECT COUNT(id) FROM tr WHERE (status_code = 3 or bil_withdrawal = 2) and tag_name = '$tag' AND closure_date >= '$year_start_date' AND closure_date <='$year_end_date' AND users.id = tr.assessor) AS withdrawals_current_year
                    ,(SELECT GROUP_CONCAT(id) FROM tr WHERE (status_code = 3 or bil_withdrawal = 2) and tag_name = '$tag' AND closure_date >= '$year_start_date' AND closure_date <='$year_end_date' AND users.id = tr.assessor) AS withdrawals_current_year_trs
                    ,(SELECT COUNT(id) FROM tr WHERE start_date < DATE_SUB(NOW(), INTERVAL 10 WEEK) and status_code = 1 and bil_withdrawal is null and tag_name = '$tag' AND users.id = tr.assessor AND (onefile_id IS NULL OR onefile_id NOT IN (SELECT LearnerID FROM onefile_reviews WHERE StartedOn > DATE_SUB(NOW(), INTERVAL 10 WEEK))) and id not in ($trs)) AS no_reviews
                    ,(SELECT GROUP_CONCAT(id) FROM tr WHERE start_date < DATE_SUB(NOW(), INTERVAL 10 WEEK) and status_code = 1 and bil_withdrawal is null and tag_name = '$tag' AND users.id = tr.assessor AND (onefile_id IS NULL OR onefile_id NOT IN (SELECT LearnerID FROM onefile_reviews WHERE StartedOn > DATE_SUB(NOW(), INTERVAL 10 WEEK))) and id not in ($trs)) AS no_reviews_trs
                    ,(SELECT COUNT(*) FROM tr WHERE start_date < DATE_SUB(NOW(), INTERVAL 6 WEEK) and status_code = 1 and bil_withdrawal is null AND users.id = tr.assessor and (onefile_id IS NULL OR onefile_id NOT IN (SELECT LearnerID FROM onefile_tlap WHERE AssessorSignedOn > DATE_SUB(NOW(), INTERVAL 6 WEEK))) and id not in ($trs)) as no_assessment
                    ,(SELECT group_concat(id) FROM tr WHERE start_date < DATE_SUB(NOW(), INTERVAL 6 WEEK) and status_code = 1 and bil_withdrawal is null and users.id = tr.assessor and (onefile_id IS NULL OR onefile_id NOT IN (SELECT LearnerID FROM onefile_tlap WHERE AssessorSignedOn > DATE_SUB(NOW(), INTERVAL 6 WEEK))) and id not in ($trs)) as no_assessment_trs
                    FROM users 
                    WHERE users.type=3 and users.active=1
                    order by firstnames, surname;";
                    DAO::execute($link, "SET SESSION group_concat_max_len = 1000000;");
                    $rows = DAO::getResultset($link, $sql, DAO::FETCH_ASSOC);

                    $th = "<div class='panel panel-primary'>";
                    $th .= "<div class='panel-heading'><b>" . $tag ."</b></div>";
                    $th .= "<div class='chart-panel-body '>";
                    $th .= '<table class="table" border="0" cellspacing="0" cellpadding="6">';
                    $th .= '<thead style="position: sticky;top: 0"><tr><th class="topRow">&nbsp;</th>';
                    $th .= '<th class="topRow">Trainer</th>';
                    $th .= '<th class="topRow">Starts current period</th>';
                    $th .= '<th class="topRow">Starts Current Year</th>';
                    $th .= '<th class="topRow">In-learning</th>';
                    $th .= '<th class="topRow">EPA</th>';
                    $th .= '<th class="topRow">No Assessment 6 Weeks</th>';
                    $th .= '<th class="topRow">Withdrawn</th>';
                    $th .= '<th class="topRow">Funded Learners</th>';
                    $th .= '<th class="topRow">OOF</th>';
                    $th .= '<th class="topRow">BIL</th>';
                    //$th .= '<th class="topRow">BIL Pending</th>';
                    $th .= '<th class="topRow">Progress 90%</th>';
                    $th .= '<th class="topRow">Due current period</th>';
                    $th .= '<th class="topRow">Achievers current period</th>';
                    $th .= '<th class="topRow">Achievers current year</th>';
                    $th .= '<th class="topRow">Withdrawn current period</th>';
                    $th .= '<th class="topRow">Withdrawn current year</th>';
                    $th .= '<th class="topRow">No review in last 10 weeks</th>';
                    $th .= '</tr></thead><tbody>';
                    $tb=""; 
                    
                    $starts_current_period_gt = 0;
                    $starts_current_year_gt = 0;
                    $live_gt = 0;
                    $epa_gt = 0;
                    $no_assessment_gt = 0;
                    $withdrawals_gt = 0;
                    $funded_gt = 0;
                    $oof_gt = 0;
                    $pending_bil_gt = 0;
                    $progress_gt = 0;
                    $due_current_period_gt = 0;
                    $achievers_current_period_gt = 0;
                    $achievers_current_year_gt = 0;
                    $withdrawals_current_period_gt = 0;
                    $withdrawals_current_year_gt = 0;
                    $no_reviews_gt = 0;

                    foreach($rows as $row)
                    {
                        if($row['starts_current_period']>0 or $row['starts_current_year']>0 or $row['live_trs']>0 or $row['withdrawals']>0 or $row['funded']>0 or $row['oof']>0 or $row['bil']>0 or $row['pending_bil']>0 or $row['progress']>0 or $row['due_current_period']>0 or $row['achievers_current_period']>0 or $row['achievers_current_year']>0 or $row['withdrawals_current_period']>0 or $row['withdrawals_current_year']>0 or $row['no_reviews']>0)
                        {
                            $tb .= '<tr>';
                            $tb .= '<td>&nbsp;</td>';
                            $tb .= '<td>' . $row['assessor'] . '</td>';
                            $starts_current_period = $row['starts_current_period'];
                            $starts_current_period_trs = $row['starts_current_period_trs'];
                            $tb .= '<td><a href="do.php?_action=view_training_records&_reset=1&ViewTrainingRecords_filter_tr_ids=' . $starts_current_period_trs . '">' . $starts_current_period . '</a></td>';
                            $starts_current_year = $row['starts_current_year'];
                            $starts_current_year_trs = $row['starts_current_year_trs'];
                            $tb .= '<td><a href="do.php?_action=view_training_records&_reset=1&ViewTrainingRecords_filter_tr_ids=' . $starts_current_year_trs . '">' . $starts_current_year . '</a></td>';
                            $live = $row['live'];
                            $live_trs = $row['live_trs'];
                            $tb .= '<td><a href="do.php?_action=view_training_records&_reset=1&ViewTrainingRecords_filter_tr_ids=' . $live_trs . '">' . $live . '</a></td>';
                            $epa = $row['epa'];
                            $epa_trs = $row['epa_trs'];
                            $tb .= '<td><a href="do.php?_action=view_training_records&_reset=1&ViewTrainingRecords_filter_tr_ids=' . $epa_trs . '">' . $epa . '</a></td>';
                            $no_assessment = $row['no_assessment'];
                            $no_assessment_trs = $row['no_assessment_trs'];
                            $tb .= '<td><a href="do.php?_action=view_training_records&_reset=1&ViewTrainingRecords_filter_record_status=SHOW_ALL&ViewTrainingRecords_filter_tr_ids=' . $no_assessment_trs . '">' . $no_assessment . '</a></td>';
                            $withdrawals = $row['withdrawals'];
                            $withdrawals_trs = $row['withdrawals_trs'];
                            $tb .= '<td><a href="do.php?_action=view_training_records&_reset=1&ViewTrainingRecords_filter_record_status=SHOW_ALL&ViewTrainingRecords_filter_tr_ids=' . $withdrawals_trs . '">' . $withdrawals . '</a></td>';
                            $funded = $row['funded'];
                            $funded_trs = $row['funded_trs'];
                            $tb .= '<td><a href="do.php?_action=view_training_records&_reset=1&ViewTrainingRecords_filter_tr_ids=' . $funded_trs . '">' . $funded . '</a></td>';
                            $oof = $row['oof'];
                            $oof_trs = $row['oof_trs'];
                            $tb .= '<td><a href="do.php?_action=view_training_records&_reset=1&ViewTrainingRecords_filter_tr_ids=' . $oof_trs . '">' . $oof . '</a></td>';
                            //$bil = $row['bil'];
                            //$bil_trs = $row['bil_trs'];
                            //$tb .= '<td><a href="do.php?_action=view_training_records&_reset=1&ViewTrainingRecords_filter_record_status=SHOW_ALL&ViewTrainingRecords_filter_tr_ids=' . $bil_trs . '">' . $bil . '</a></td>';
                            $pending_bil = $row['pending_bil'];
                            $pending_bil_trs = $row['pending_bil_trs'];
                            $tb .= '<td><a href="do.php?_action=view_training_records&_reset=1&ViewTrainingRecords_filter_record_status=SHOW_ALL&ViewTrainingRecords_filter_tr_ids=' . $pending_bil_trs . '">' . $pending_bil . '</a></td>';
                            $progress = $row['progress'];
                            $progress_trs = $row['progress_trs'];
                            $tb .= '<td><a href="do.php?_action=view_training_records&_reset=1&ViewTrainingRecords_filter_tr_ids=' . $progress_trs . '">' . $progress . '</a></td>';
                            $due_current_period = $row['due_current_period'];
                            $due_current_period_trs = $row['due_current_period_trs'];
                            $tb .= '<td><a href="do.php?_action=view_training_records&_reset=1&ViewTrainingRecords_filter_tr_ids=' . $due_current_period_trs . '">' . $due_current_period . '</a></td>';
                            $achievers_current_period = $row['achievers_current_period'];
                            $achievers_current_period_trs = $row['achievers_current_period_trs'];
                            $tb .= '<td><a href="do.php?_action=view_training_records&_reset=1&ViewTrainingRecords_filter_record_status=2&ViewTrainingRecords_filter_tr_ids=' . $achievers_current_period_trs . '">' . $achievers_current_period . '</a></td>';
                            $achievers_current_year = $row['achievers_current_year'];
                            $achievers_current_year_trs = $row['achievers_current_year_trs'];
                            $tb .= '<td><a href="do.php?_action=view_training_records&_reset=1&ViewTrainingRecords_filter_record_status=2&ViewTrainingRecords_filter_tr_ids=' . $achievers_current_year_trs . '">' . $achievers_current_year . '</a></td>';
                            $withdrawals_current_period = $row['withdrawals_current_period'];
                            $withdrawals_current_period_trs = $row['withdrawals_current_period_trs'];
                            $tb .= '<td><a href="do.php?_action=view_training_records&_reset=1&ViewTrainingRecords_filter_record_status=SHOW_ALL&ViewTrainingRecords_filter_tr_ids=' . $withdrawals_current_period_trs . '">' . $withdrawals_current_period . '</a></td>';
                            $withdrawals_current_year = $row['withdrawals_current_year'];
                            $withdrawals_current_year_trs = $row['withdrawals_current_year_trs'];
                            $tb .= '<td><a href="do.php?_action=view_training_records&_reset=1&ViewTrainingRecords_filter_record_status=SHOW_ALL&ViewTrainingRecords_filter_tr_ids=' . $withdrawals_current_year_trs . '">' . $withdrawals_current_year . '</a></td>';
                            $no_reviews = $row['no_reviews'];
                            $no_reviews_trs = $row['no_reviews_trs'];
                            $tb .= '<td><a href="do.php?_action=view_training_records&_reset=1&ViewTrainingRecords_filter_tr_ids=' . $no_reviews_trs . '">' . $no_reviews . '</a></td>';
                            $tb .= '</tr>';

                            $starts_current_period_gt += $starts_current_period;
                            $starts_current_year_gt += $starts_current_year;
                            $live_gt += $live;
                            $epa_gt += $epa;
                            $no_assessment_gt += $no_assessment;
                            $withdrawals_gt += $withdrawals;
                            $funded_gt += $funded;
                            $oof_gt += $oof;
                            $pending_bil_gt += $pending_bil;
                            $progress_gt += $progress;
                            $due_current_period_gt += $due_current_period;
                            $achievers_current_period_gt += $achievers_current_period;
                            $achievers_current_year_gt += $achievers_current_year;
                            $withdrawals_current_period_gt += $withdrawals_current_period;
                            $withdrawals_current_year_gt += $withdrawals_current_year;
                            $no_reviews_gt += $no_reviews;
                        }
                    } 
                    if($tb!="")
                    {
                        $tb .= '<tr>';
                        $tb .= '<td>&nbsp;</td>';
                        $tb .= '<td>Total</td>';
                        $tb .= '<td>' . $starts_current_period_gt . '</td>';
                        $tb .= '<td>' . $starts_current_year_gt . '</td>';
                        $tb .= '<td>' . $live_gt . '</td>';
                        $tb .= '<td>' . $epa_gt . '</td>';
                        $tb .= '<td>' . $no_assessment_gt . '</td>';
                        $tb .= '<td>' . $withdrawals_gt . '</td>';
                        $tb .= '<td>' . $funded_gt . '</td>';
                        $tb .= '<td>' . $oof_gt . '</td>';
                        $tb .= '<td>' . $pending_bil_gt . '</td>';
                        $tb .= '<td>' . $progress_gt . '</td>';
                        $tb .= '<td>' . $due_current_period_gt . '</td>';
                        $tb .= '<td>' . $achievers_current_period_gt . '</td>';
                        $tb .= '<td>' . $achievers_current_year_gt . '</td>';
                        $tb .= '<td>' . $withdrawals_current_period_gt . '</td>';
                        $tb .= '<td>' . $withdrawals_current_year_gt . '</td>';
                        $tb .= '<td>' . $no_reviews_gt . '</td>';
                        $tb .= '</tr>';
                        $t .= $th . $tb . '</tbody></table></div></div>';
                    }    
                }
            }
            echo $t;
            exit;
        }

        if($panel == 'LearnerByAssessorApp')
        {
            echo "Under constructon";
            exit;
        }

        if($panel == 'LearnerByAssessorEducation')
        {
            echo "Under constructon";
            exit;
        }

        if($panel == 'LearnerByIQATable')
        {
            $period = DAO::getObject($link, "SELECT * FROM central.lookup_submission_dates WHERE CURDATE() BETWEEN start_submission_date AND last_submission_date and contract_year = '$contract_year' ORDER BY contract_year DESC LIMIT 1;");
            $year = DAO::getObject($link, "SELECT * FROM contracts where contract_year = '$contract_year' ORDER BY contract_year DESC LIMIT 1;");

            $period_start_date = $period->start_submission_date;
            $period_end_date = $period->last_submission_date;
            $year_start_date = $year->start_date;
            $year_end_date = $year->end_date;

            $submission = DAO::getSingleValue($link, "SELECT submission AS submission FROM central.`lookup_submission_dates` WHERE CURDATE() BETWEEN start_submission_date AND last_submission_date and contract_year = '$contract_year' ORDER BY contract_year DESC LIMIT 1;");
            $trs = DAO::getSingleValue($link, "SELECT GROUP_CONCAT(tr_id) FROM ilr WHERE submission = '$submission' AND contract_id IN (SELECT id FROM contracts WHERE contract_year = (SELECT contract_year FROM contracts where contract_year = '$contract_year' ORDER BY contract_year DESC LIMIT 1)) AND EXTRACTVALUE(ilr, '/Learner/LearningDelivery[AimType=1]/Outcome')=8");

            $t ="";
            $st = $link->query("select distinct tag_name from tr where tag_name is not null");
            if($st)
            {
                while($tags = $st->fetch(PDO::FETCH_ASSOC))
                {
                    $tag = $tags['tag_name'];    
                    $sql = "SELECT 
                    concat(firstnames,' ',surname) AS iqa
                    ,(SELECT COUNT(id) FROM tr WHERE bil_withdrawal is null and tag_name = '$tag' and start_date >= '$period_start_date' AND start_date <='$period_end_date' AND users.id = tr.verifier) AS starts_current_period
                    ,(SELECT GROUP_CONCAT(id) FROM tr WHERE bil_withdrawal is null and tag_name = '$tag' and start_date >= '$period_start_date' AND start_date <='$period_end_date' AND users.id = tr.verifier) AS starts_current_period_trs
                    ,(SELECT COUNT(id) FROM tr WHERE bil_withdrawal is null and tag_name = '$tag' and start_date >= '$year_start_date' AND start_date <='$year_end_date' AND users.id = tr.verifier) AS starts_current_year
                    ,(SELECT GROUP_CONCAT(id) FROM tr WHERE bil_withdrawal is null and tag_name = '$tag' and start_date >= '$year_start_date' AND start_date <='$year_end_date' AND users.id = tr.verifier) AS starts_current_year_trs
                    ,(SELECT COUNT(id) FROM tr WHERE status_code = 1 and bil_withdrawal is null and tag_name = '$tag' AND users.id = tr.verifier) AS live
                    ,(SELECT GROUP_CONCAT(id) FROM tr WHERE status_code = 1 and bil_withdrawal is null and tag_name = '$tag' AND users.id = tr.verifier) AS live_trs
                    ,(SELECT COUNT(id) FROM tr WHERE status_code = 1 and bil_withdrawal is null and tag_name = '$tag' AND users.id = tr.verifier and id in ($trs)) AS epa
                    ,(SELECT GROUP_CONCAT(id) FROM tr WHERE status_code = 1 and bil_withdrawal is null and tag_name = '$tag' AND users.id = tr.verifier and id in ($trs)) AS epa_trs
                    ,(SELECT COUNT(id) FROM tr WHERE (status_code = 3 or bil_withdrawal = 2) and tag_name = '$tag' AND users.id = tr.verifier) AS withdrawals
                    ,(SELECT GROUP_CONCAT(id) FROM tr WHERE (status_code = 3 or bil_withdrawal = 2) and tag_name = '$tag' AND users.id = tr.verifier) AS withdrawals_trs
                    ,(SELECT COUNT(id) FROM tr WHERE status_code = 1 and bil_withdrawal is null and tag_name = '$tag' AND target_date > CURDATE() AND users.id = tr.verifier) AS funded
                    ,(SELECT GROUP_CONCAT(id) FROM tr WHERE status_code = 1 and bil_withdrawal is null and tag_name = '$tag' AND target_date > CURDATE() AND users.id = tr.verifier) AS funded_trs
                    ,(SELECT COUNT(id) FROM tr WHERE status_code = 1 and bil_withdrawal is null and tag_name = '$tag' AND target_date <= CURDATE() AND users.id = tr.verifier) AS oof
                    ,(SELECT GROUP_CONCAT(id) FROM tr WHERE status_code = 1 and bil_withdrawal is null and tag_name = '$tag' AND target_date <= CURDATE() AND users.id = tr.verifier) AS oof_trs
                    ,(SELECT COUNT(id) FROM tr WHERE (status_code = 6 or bil_withdrawal = 1) and tag_name = '$tag' AND users.id = tr.verifier) AS bil
                    ,(SELECT GROUP_CONCAT(id) FROM tr WHERE (status_code = 6 or bil_withdrawal = 1) and tag_name = '$tag' AND users.id = tr.verifier) AS bil_trs
                    ,(SELECT COUNT(id) FROM tr WHERE (status_code = 6 or bil_withdrawal = 1) and tag_name = '$tag' AND users.id = tr.verifier AND l03 NOT IN (SELECT l03 FROM tr AS future WHERE future.start_date > tr.start_date)) AS pending_bil
                    ,(SELECT GROUP_CONCAT(id) FROM tr WHERE (status_code = 6 or bil_withdrawal = 1) and tag_name = '$tag' AND users.id = tr.verifier AND l03 NOT IN (SELECT l03 FROM tr AS future WHERE future.start_date > tr.start_date)) AS pending_bil_trs
                    ,(SELECT COUNT(id) FROM tr WHERE status_code = 1 and bil_withdrawal is null and tag_name = '$tag' AND users.id = tr.verifier AND onefile_id IN (SELECT ID FROM onefile_learners WHERE progress>= 90)) AS progress
                    ,(SELECT GROUP_CONCAT(id) FROM tr WHERE status_code = 1 and bil_withdrawal is null and tag_name = '$tag' AND users.id = tr.verifier AND onefile_id IN (SELECT ID FROM onefile_learners WHERE progress>= 90)) AS progress_trs
                    ,(SELECT COUNT(id) FROM tr WHERE bil_withdrawal is null and tag_name = '$tag' and target_date >= '$period_start_date' AND target_date <='$period_end_date' AND users.id = tr.verifier) AS due_current_period
                    ,(SELECT GROUP_CONCAT(id) FROM tr WHERE bil_withdrawal is null and tag_name = '$tag' and target_date >= '$period_start_date' AND target_date <='$period_end_date' AND users.id = tr.verifier) AS due_current_period_trs
                    ,(SELECT COUNT(id) FROM tr WHERE status_code = 2 and tag_name = '$tag' AND GREATEST(closure_date, COALESCE(achievement_date, '0000-00-00')) >= '$period_start_date' AND GREATEST(closure_date, COALESCE(achievement_date, '0000-00-00')) <='$period_end_date' AND users.id = tr.verifier) AS achievers_current_period
                    ,(SELECT GROUP_CONCAT(id) FROM tr WHERE status_code = 2 and tag_name = '$tag' AND GREATEST(closure_date, COALESCE(achievement_date, '0000-00-00')) >= '$period_start_date' AND GREATEST(closure_date, COALESCE(achievement_date, '0000-00-00')) <='$period_end_date' AND users.id = tr.verifier) AS achievers_current_period_trs
                    ,(SELECT COUNT(id) FROM tr WHERE status_code = 2 and tag_name = '$tag' AND GREATEST(closure_date, COALESCE(achievement_date, '0000-00-00')) >= '$year_start_date' AND GREATEST(closure_date, COALESCE(achievement_date, '0000-00-00')) <='$year_end_date' AND users.id = tr.verifier) AS achievers_current_year
                    ,(SELECT GROUP_CONCAT(id) FROM tr WHERE status_code = 2 and tag_name = '$tag' AND GREATEST(closure_date, COALESCE(achievement_date, '0000-00-00')) >= '$year_start_date' AND GREATEST(closure_date, COALESCE(achievement_date, '0000-00-00')) <='$year_end_date' AND users.id = tr.verifier) AS achievers_current_year_trs
                    ,(SELECT COUNT(id) FROM tr WHERE (status_code = 3 or bil_withdrawal = 2) and tag_name = '$tag' AND GREATEST(closure_date, COALESCE(achievement_date, '0000-00-00')) >= '$period_start_date' AND GREATEST(closure_date, COALESCE(achievement_date, '0000-00-00')) <='$period_end_date' AND users.id = tr.verifier) AS withdrawals_current_period
                    ,(SELECT GROUP_CONCAT(id) FROM tr WHERE (status_code = 3 or bil_withdrawal = 2) and tag_name = '$tag' AND closure_date >= '$period_start_date' AND closure_date <='$period_end_date' AND users.id = tr.verifier) AS withdrawals_current_period_trs
                    ,(SELECT COUNT(id) FROM tr WHERE (status_code = 3 or bil_withdrawal = 2) and tag_name = '$tag' AND closure_date >= '$year_start_date' AND closure_date <='$year_end_date' AND users.id = tr.verifier) AS withdrawals_current_year
                    ,(SELECT GROUP_CONCAT(id) FROM tr WHERE (status_code = 3 or bil_withdrawal = 2) and tag_name = '$tag' AND closure_date >= '$year_start_date' AND closure_date <='$year_end_date' AND users.id = tr.verifier) AS withdrawals_current_year_trs
                    ,(SELECT COUNT(id) FROM tr WHERE start_date < DATE_SUB(NOW(), INTERVAL 10 WEEK) and status_code = 1 and bil_withdrawal is null and tag_name = '$tag' AND users.id = tr.verifier AND (onefile_id IS NULL OR onefile_id NOT IN (SELECT LearnerID FROM onefile_reviews WHERE StartedOn > DATE_SUB(NOW(), INTERVAL 10 WEEK))) and id not in ($trs)) AS no_reviews
                    ,(SELECT GROUP_CONCAT(id) FROM tr WHERE start_date < DATE_SUB(NOW(), INTERVAL 10 WEEK) and status_code = 1 and bil_withdrawal is null and tag_name = '$tag' AND users.id = tr.verifier AND (onefile_id IS NULL OR onefile_id NOT IN (SELECT LearnerID FROM onefile_reviews WHERE StartedOn > DATE_SUB(NOW(), INTERVAL 10 WEEK))) and id not in ($trs)) AS no_reviews_trs
                    ,(SELECT COUNT(id) FROM tr WHERE start_date < DATE_SUB(NOW(), INTERVAL 6 WEEK) and status_code = 1 and bil_withdrawal is null and tag_name = '$tag' AND users.id = tr.verifier and (onefile_id IS NULL OR onefile_id NOT IN (SELECT LearnerID FROM onefile_tlap WHERE AssessorSignedOn > DATE_SUB(NOW(), INTERVAL 6 WEEK))) and id not in ($trs)) as no_assessment
                    ,(SELECT group_concat(id) FROM tr WHERE start_date < DATE_SUB(NOW(), INTERVAL 6 WEEK) and status_code = 1 and bil_withdrawal is null and tag_name = '$tag' and users.id = tr.verifier and (onefile_id IS NULL OR onefile_id NOT IN (SELECT LearnerID FROM onefile_tlap WHERE AssessorSignedOn > DATE_SUB(NOW(), INTERVAL 6 WEEK))) and id not in ($trs)) as no_assessment_trs
                    FROM users 
                    WHERE users.type=4 and users.active=1
                    order by firstnames, surname;";
                    DAO::execute($link, "SET SESSION group_concat_max_len = 1000000;");
                    $rows = DAO::getResultset($link, $sql, DAO::FETCH_ASSOC);

                    $th = "<div class='panel panel-primary'>";
                    $th .= "<div class='panel-heading'><b>" . $tag ."</b></div>";
                    $th .= "<div class='chart-panel-body '>";
                    $th .= '<table class="table" border="0" cellspacing="0" cellpadding="6">';
                    $th .= '<thead style="position: sticky;top: 0"><tr><th class="topRow">&nbsp;</th>';
                    $th .= '<th class="towRow">IQA</th>';
                    $th .= '<th class="towRow">Starts current period</th>';
                    $th .= '<th class="towRow">Starts Current Year</th>';
                    $th .= '<th class="towRow">In-learning</th>';
                    $th .= '<th class="towRow">EPA</th>';
                    $th .= '<th class="towRow">No Assessment 6 Weeks</th>';
                    $th .= '<th class="towRow">Withdrawn</th>';
                    $th .= '<th class="towRow">Funded Learners</th>';
                    $th .= '<th class="towRow">OOF</th>';
                    $th .= '<th class="towRow">BIL</th>';
                    //$th .= '<th class="towRow">BIL Pending</th>';
                    $th .= '<th class="towRow">Progress 90%</th>';
                    $th .= '<th class="towRow">Due current period</th>';
                    $th .= '<th class="towRow">Achievers current period</th>';
                    $th .= '<th class="towRow">Achievers current year</th>';
                    $th .= '<th class="towRow">Withdrawn current period</th>';
                    $th .= '<th class="towRow">Withdrawn current year</th>';
                    $th .= '<th class="towRow">No review in last 10 weeks</th>';
                    $th .= '</tr></thead><tbody>';
                    $tb="";    

                    $starts_current_period_gt = 0;
                    $starts_current_year_gt = 0;
                    $live_gt = 0;
                    $epa_gt = 0;
                    $no_assessment_gt = 0;
                    $withdrawals_gt = 0;
                    $funded_gt = 0;
                    $oof_gt = 0;
                    $pending_bil_gt = 0;
                    $progress_gt = 0; 
                    $due_current_period_gt = 0; 
                    $achievers_current_period_gt = 0;
                    $achievers_current_year_gt = 0;
                    $withdrawals_current_period_gt = 0; 
                    $withdrawals_current_year_gt = 0;
                    $no_reviews_gt = 0; 

                    foreach($rows as $row)
                    {
                        if($row['starts_current_period']>0 or $row['starts_current_year']>0 or $row['live']>0 or $row['withdrawals']>0 or $row['funded']>0 or $row['oof']>0 or $row['bil']>0 or $row['pending_bil']>0 or $row['progress']>0 or $row['due_current_period']>0 or $row['achievers_current_period']>0 or $row['achievers_current_year']>0 or $row['withdrawals_current_period']>0 or $row['no_reviews']>0)
                        {
                            $tb .= '<tr>';
                            $tb .= '<td>&nbsp;</td>';
                            $tb .= '<td>' . $row['iqa'] . '</td>';
                            $starts_current_period = $row['starts_current_period'];
                            $starts_current_period_trs = $row['starts_current_period_trs'];
                            $tb .= '<td><a href="do.php?_action=view_training_records&_reset=1&ViewTrainingRecords_filter_tr_ids=' . $starts_current_period_trs . '">' . $starts_current_period . '</a></td>';
                            $starts_current_year = $row['starts_current_year'];
                            $starts_current_year_trs = $row['starts_current_year_trs'];
                            $tb .= '<td><a href="do.php?_action=view_training_records&_reset=1&ViewTrainingRecords_filter_tr_ids=' . $starts_current_year_trs . '">' . $starts_current_year . '</a></td>';
                            $live = $row['live'];
                            $live_trs = $row['live_trs'];
                            $tb .= '<td><a href="do.php?_action=view_training_records&_reset=1&ViewTrainingRecords_filter_tr_ids=' . $live_trs . '">' . $live . '</a></td>';
                            $epa = $row['epa'];
                            $epa_trs = $row['epa_trs'];
                            $tb .= '<td><a href="do.php?_action=view_training_records&_reset=1&ViewTrainingRecords_filter_tr_ids=' . $epa_trs . '">' . $epa . '</a></td>';
                            $no_assessment = $row['no_assessment'];
                            $no_assessment_trs = $row['no_assessment_trs'];
                            $tb .= '<td><a href="do.php?_action=view_training_records&_reset=1&ViewTrainingRecords_filter_record_status=SHOW_ALL&ViewTrainingRecords_filter_tr_ids=' . $no_assessment_trs . '">' . $no_assessment . '</a></td>';
                            $withdrawals = $row['withdrawals'];
                            $withdrawals_trs = $row['withdrawals_trs'];
                            $tb .= '<td><a href="do.php?_action=view_training_records&_reset=1&ViewTrainingRecords_filter_record_status=SHOW_ALL&ViewTrainingRecords_filter_tr_ids=' . $withdrawals_trs . '">' . $withdrawals . '</a></td>';
                            $funded = $row['funded'];
                            $funded_trs = $row['funded_trs'];
                            $tb .= '<td><a href="do.php?_action=view_training_records&_reset=1&ViewTrainingRecords_filter_tr_ids=' . $funded_trs . '">' . $funded . '</a></td>';
                            $oof = $row['oof'];
                            $oof_trs = $row['oof_trs'];
                            $tb .= '<td><a href="do.php?_action=view_training_records&_reset=1&ViewTrainingRecords_filter_tr_ids=' . $oof_trs . '">' . $oof . '</a></td>';
                            //$bil = $row['bil'];
                            //$bil_trs = $row['bil_trs'];
                            //$tb .= '<td><a href="do.php?_action=view_training_records&_reset=1&ViewTrainingRecords_filter_record_status=SHOW_ALL&ViewTrainingRecords_filter_tr_ids=' . $bil_trs . '">' . $bil . '</a></td>';
                            $pending_bil = $row['pending_bil'];
                            $pending_bil_trs = $row['pending_bil_trs'];
                            $tb .= '<td><a href="do.php?_action=view_training_records&_reset=1&ViewTrainingRecords_filter_record_status=SHOW_ALL&ViewTrainingRecords_filter_tr_ids=' . $pending_bil_trs . '">' . $pending_bil . '</a></td>';
                            $progress = $row['progress'];
                            $progress_trs = $row['progress_trs'];
                            $tb .= '<td><a href="do.php?_action=view_training_records&_reset=1&ViewTrainingRecords_filter_tr_ids=' . $progress_trs . '">' . $progress . '</a></td>';
                            $due_current_period = $row['due_current_period'];
                            $due_current_period_trs = $row['due_current_period_trs'];
                            $tb .= '<td><a href="do.php?_action=view_training_records&_reset=1&ViewTrainingRecords_filter_tr_ids=' . $due_current_period_trs . '">' . $due_current_period . '</a></td>';
                            $achievers_current_period = $row['achievers_current_period'];
                            $achievers_current_period_trs = $row['achievers_current_period_trs'];
                            $tb .= '<td><a href="do.php?_action=view_training_records&_reset=1&ViewTrainingRecords_filter_record_status=2&ViewTrainingRecords_filter_tr_ids=' . $achievers_current_period_trs . '">' . $achievers_current_period . '</a></td>';
                            $achievers_current_year = $row['achievers_current_year'];
                            $achievers_current_year_trs = $row['achievers_current_year_trs'];
                            $tb .= '<td><a href="do.php?_action=view_training_records&_reset=1&ViewTrainingRecords_filter_record_status=2&ViewTrainingRecords_filter_tr_ids=' . $achievers_current_year_trs . '">' . $achievers_current_year . '</a></td>';
                            $withdrawals_current_period = $row['withdrawals_current_period'];
                            $withdrawals_current_period_trs = $row['withdrawals_current_period_trs'];
                            $tb .= '<td><a href="do.php?_action=view_training_records&_reset=1&ViewTrainingRecords_filter_record_status=SHOW_ALL&ViewTrainingRecords_filter_tr_ids=' . $withdrawals_current_period_trs . '">' . $withdrawals_current_period . '</a></td>';
                            $withdrawals_current_year = $row['withdrawals_current_year'];
                            $withdrawals_current_year_trs = $row['withdrawals_current_year_trs'];
                            $tb .= '<td><a href="do.php?_action=view_training_records&_reset=1&ViewTrainingRecords_filter_record_status=SHOW_ALL&ViewTrainingRecords_filter_tr_ids=' . $withdrawals_current_year_trs . '">' . $withdrawals_current_year . '</a></td>';
                            $no_reviews = $row['no_reviews'];
                            $no_reviews_trs = $row['no_reviews_trs'];
                            $tb .= '<td><a href="do.php?_action=view_training_records&_reset=1&ViewTrainingRecords_filter_tr_ids=' . $no_reviews_trs . '">' . $no_reviews . '</a></td>';
                            $tb .= '</tr>';

                            $starts_current_period_gt += $starts_current_period;
                            $starts_current_year_gt += $starts_current_year;
                            $live_gt += $live;
                            $epa_gt += $epa;
                            $no_assessment_gt += $no_assessment;
                            $withdrawals_gt += $withdrawals;
                            $funded_gt += $funded;
                            $oof_gt += $oof;
                            $pending_bil_gt += $pending_bil;
                            $progress_gt += $progress;
                            $due_current_period_gt += $due_current_period;
                            $achievers_current_period_gt += $achievers_current_period;
                            $achievers_current_year_gt += $achievers_current_year;
                            $withdrawals_current_period_gt += $withdrawals_current_period;
                            $withdrawals_current_year_gt += $withdrawals_current_year;
                            $no_reviews_gt += $no_reviews;
        
                        }
                    }
                    if($tb!="")
                    {
                        $tb .= '<tr>';
                        $tb .= '<td>&nbsp;</td>';
                        $tb .= '<td>Total</td>';
                        $tb .= '<td>' . $starts_current_period_gt . '</td>';
                        $tb .= '<td>' . $starts_current_year_gt . '</td>';
                        $tb .= '<td>' . $live_gt . '</td>';
                        $tb .= '<td>' . $epa_gt . '</td>';
                        $tb .= '<td>' . $no_assessment_gt . '</td>';
                        $tb .= '<td>' . $withdrawals_gt . '</td>';
                        $tb .= '<td>' . $funded_gt . '</td>';
                        $tb .= '<td>' . $oof_gt . '</td>';
                        $tb .= '<td>' . $pending_bil_gt . '</td>';
                        $tb .= '<td>' . $progress_gt . '</td>';
                        $tb .= '<td>' . $due_current_period_gt . '</td>';
                        $tb .= '<td>' . $achievers_current_period_gt . '</td>';
                        $tb .= '<td>' . $achievers_current_year_gt . '</td>';
                        $tb .= '<td>' . $withdrawals_current_period_gt . '</td>';
                        $tb .= '<td>' . $withdrawals_current_year_gt . '</td>';
                        $tb .= '<td>' . $no_reviews_gt . '</td>';
                        $tb .= '</tr>';
                        $t .= $th . $tb . '</tbody></table></div></div>';
                    }
                }   
            }
            echo $t;
            exit;
        }

        if($panel == 'LearnerByProgress')
        {
            $submission = DAO::getSingleValue($link, "SELECT submission AS submission FROM central.`lookup_submission_dates` WHERE CURDATE() BETWEEN start_submission_date AND last_submission_date and contract_year = '$contract_year' ORDER BY contract_year DESC LIMIT 1;");
            $trs = DAO::getSingleValue($link, "SELECT GROUP_CONCAT(tr_id) FROM ilr WHERE submission = '$submission' AND contract_id IN (SELECT id FROM contracts WHERE contract_year = (SELECT contract_year FROM contracts where contract_year = '$contract_year' ORDER BY contract_year DESC LIMIT 1)) AND EXTRACTVALUE(ilr, '/Learner/LearningDelivery[AimType=1]/Outcome')=8");

            $t ="";
            $st = $link->query("select distinct tag_name from tr where tag_name is not null");
            if($st)
            {
                while($tags = $st->fetch(PDO::FETCH_ASSOC))
                {
                    $tag = $tags['tag_name'];    
                    $sql = "SELECT 
                    CONCAT(firstnames,' ', surname) AS assessor

                    ,(SELECT COUNT(id) FROM tr WHERE tr.assessor = users.id AND status_code = 1 and bil_withdrawal is null and tag_name = '$tag') AS live
                    ,(SELECT GROUP_CONCAT(id) FROM tr WHERE tr.assessor = users.id AND status_code = 1 and bil_withdrawal is null and tag_name = '$tag') AS live_trs

                    ,(SELECT COUNT(id) FROM tr WHERE tr.assessor = users.id AND status_code = 1 and bil_withdrawal is null and tag_name = '$tag' and id in ($trs)) AS epa
                    ,(SELECT GROUP_CONCAT(id) FROM tr WHERE tr.assessor = users.id AND status_code = 1 and bil_withdrawal is null and tag_name = '$tag' and id in ($trs)) AS epa_trs

                    ,(SELECT COUNT(CASE WHEN (((DATEDIFF(NOW(), start_date) / DATEDIFF(target_date, start_date)) * 100)<COALESCE(onefile_learners.Progress,0)) THEN 1 END) FROM tr 
                        LEFT JOIN onefile_learners ON onefile_learners.ID = tr.onefile_id
                        WHERE tr.assessor = users.id AND status_code = 1 and bil_withdrawal is null and tag_name = '$tag') AS on_target

                    ,(SELECT GROUP_CONCAT(CASE WHEN (((DATEDIFF(NOW(), start_date) / DATEDIFF(target_date, start_date)) * 100)<COALESCE(onefile_learners.Progress,0)) THEN tr.id END) FROM tr 
                        LEFT JOIN onefile_learners ON onefile_learners.ID = tr.onefile_id
                        WHERE tr.assessor = users.id AND status_code = 1 and bil_withdrawal is null and tag_name = '$tag') AS on_target_trs

                    ,(SELECT COUNT(CASE WHEN (((DATEDIFF(NOW(), start_date) / DATEDIFF(target_date, start_date)) * 100)>=COALESCE(onefile_learners.Progress,0)) THEN 1 END) FROM tr 
                        LEFT JOIN onefile_learners ON onefile_learners.ID = tr.onefile_id
                        WHERE tr.assessor = users.id AND status_code = 1 and bil_withdrawal is null and tag_name = '$tag') AS behind_target

                    ,(SELECT GROUP_CONCAT(CASE WHEN (((DATEDIFF(NOW(), start_date) / DATEDIFF(target_date, start_date)) * 100)>=COALESCE(onefile_learners.Progress,0)) THEN tr.id END) FROM tr 
                        LEFT JOIN onefile_learners ON onefile_learners.ID = tr.onefile_id
                        WHERE tr.assessor = users.id AND status_code = 1 and bil_withdrawal is null and tag_name = '$tag') AS behind_target_trs

                    ,(SELECT COUNT(CASE WHEN COALESCE(onefile_learners.Progress,0) = 0 THEN 1 END) FROM tr 
                        LEFT JOIN onefile_learners ON onefile_learners.ID = tr.onefile_id
                        WHERE tr.assessor = users.id AND status_code = 1 and bil_withdrawal is null and tag_name = '$tag') AS not_started

                    ,(SELECT GROUP_CONCAT(CASE WHEN COALESCE(onefile_learners.Progress,0) = 0 THEN tr.id END) FROM tr 
                        LEFT JOIN onefile_learners ON onefile_learners.ID = tr.onefile_id
                        WHERE tr.assessor = users.id AND status_code = 1 and bil_withdrawal is null and tag_name = '$tag') AS not_started_trs

                    ,(SELECT COUNT(CASE WHEN COALESCE(onefile_learners.Progress,0) >= 90 THEN 1 END) FROM tr 
                        LEFT JOIN onefile_learners ON onefile_learners.ID = tr.onefile_id
                        WHERE tr.assessor = users.id AND status_code = 1 and bil_withdrawal is null and tag_name = '$tag') AS above_90

                    ,(SELECT GROUP_CONCAT(CASE WHEN COALESCE(onefile_learners.Progress,0) >= 90 THEN tr.id END) FROM tr 
                        LEFT JOIN onefile_learners ON onefile_learners.ID = tr.onefile_id
                        WHERE tr.assessor = users.id AND status_code = 1 and bil_withdrawal is null and tag_name = '$tag') AS above_90_trs

                    ,(SELECT COUNT(CASE WHEN (((DATEDIFF(NOW(), start_date) / DATEDIFF(target_date, start_date)) * 100)>=COALESCE(onefile_learners.Progress,0)) 
                        AND (((DATEDIFF(NOW(), start_date) / DATEDIFF(target_date, start_date)) * 100)<(COALESCE(onefile_learners.Progress,0)+10))
                        AND (((DATEDIFF(NOW(), start_date) / DATEDIFF(target_date, start_date)) * 100)>=(COALESCE(onefile_learners.Progress,0)+0))
                        THEN 1 END) FROM tr 
                        LEFT JOIN onefile_learners ON onefile_learners.ID = tr.onefile_id
                        WHERE tr.assessor = users.id AND status_code = 1 and bil_withdrawal is null and tag_name = '$tag') AS behind_1_10

                    ,(SELECT GROUP_CONCAT(CASE WHEN (((DATEDIFF(NOW(), start_date) / DATEDIFF(target_date, start_date)) * 100)>=COALESCE(onefile_learners.Progress,0)) 
                        AND (((DATEDIFF(NOW(), start_date) / DATEDIFF(target_date, start_date)) * 100)<(COALESCE(onefile_learners.Progress,0)+10))
                        AND (((DATEDIFF(NOW(), start_date) / DATEDIFF(target_date, start_date)) * 100)>=(COALESCE(onefile_learners.Progress,0)+0))
                        THEN tr.id END) FROM tr 
                        LEFT JOIN onefile_learners ON onefile_learners.ID = tr.onefile_id
                        WHERE tr.assessor = users.id AND status_code = 1 and bil_withdrawal is null and tag_name = '$tag') AS behind_1_10_trs

                    ,(SELECT COUNT(CASE WHEN (((DATEDIFF(NOW(), start_date) / DATEDIFF(target_date, start_date)) * 100)>=COALESCE(onefile_learners.Progress,0)) 
                        AND (((DATEDIFF(NOW(), start_date) / DATEDIFF(target_date, start_date)) * 100)<(COALESCE(onefile_learners.Progress,0)+25))
                        AND (((DATEDIFF(NOW(), start_date) / DATEDIFF(target_date, start_date)) * 100)>=(COALESCE(onefile_learners.Progress,0)+10))
                        THEN 1 END) FROM tr 
                        LEFT JOIN onefile_learners ON onefile_learners.ID = tr.onefile_id
                        WHERE tr.assessor = users.id AND status_code = 1 and bil_withdrawal is null and tag_name = '$tag') AS behind_11_25

                    ,(SELECT GROUP_CONCAT(CASE WHEN (((DATEDIFF(NOW(), start_date) / DATEDIFF(target_date, start_date)) * 100)>=COALESCE(onefile_learners.Progress,0)) 
                        AND (((DATEDIFF(NOW(), start_date) / DATEDIFF(target_date, start_date)) * 100)<(COALESCE(onefile_learners.Progress,0)+25))
                        AND (((DATEDIFF(NOW(), start_date) / DATEDIFF(target_date, start_date)) * 100)>=(COALESCE(onefile_learners.Progress,0)+10))
                        THEN tr.id END) FROM tr 
                        LEFT JOIN onefile_learners ON onefile_learners.ID = tr.onefile_id
                        WHERE tr.assessor = users.id AND status_code = 1 and bil_withdrawal is null and tag_name = '$tag') AS behind_11_25_trs

                    ,(SELECT COUNT(CASE WHEN (((DATEDIFF(NOW(), start_date) / DATEDIFF(target_date, start_date)) * 100)>=COALESCE(onefile_learners.Progress,0)) 
                        AND (((DATEDIFF(NOW(), start_date) / DATEDIFF(target_date, start_date)) * 100)<(COALESCE(onefile_learners.Progress,0)+50))
                        AND (((DATEDIFF(NOW(), start_date) / DATEDIFF(target_date, start_date)) * 100)>=(COALESCE(onefile_learners.Progress,0)+25))
                        THEN 1 END) FROM tr 
                        LEFT JOIN onefile_learners ON onefile_learners.ID = tr.onefile_id
                        WHERE tr.assessor = users.id AND status_code = 1 and bil_withdrawal is null and tag_name = '$tag') AS behind_26_50

                    ,(SELECT GROUP_CONCAT(CASE WHEN (((DATEDIFF(NOW(), start_date) / DATEDIFF(target_date, start_date)) * 100)>=COALESCE(onefile_learners.Progress,0)) 
                        AND (((DATEDIFF(NOW(), start_date) / DATEDIFF(target_date, start_date)) * 100)<(COALESCE(onefile_learners.Progress,0)+50))
                        AND (((DATEDIFF(NOW(), start_date) / DATEDIFF(target_date, start_date)) * 100)>=(COALESCE(onefile_learners.Progress,0)+25))
                        THEN tr.id END) FROM tr 
                        LEFT JOIN onefile_learners ON onefile_learners.ID = tr.onefile_id
                        WHERE tr.assessor = users.id AND status_code = 1 and bil_withdrawal is null and tag_name = '$tag') AS behind_26_50_trs

                    ,(SELECT COUNT(CASE WHEN (((DATEDIFF(NOW(), start_date) / DATEDIFF(target_date, start_date)) * 100)>=COALESCE(onefile_learners.Progress,0)) 
                        AND (((DATEDIFF(NOW(), start_date) / DATEDIFF(target_date, start_date)) * 100)<(COALESCE(onefile_learners.Progress,0)+100))
                        AND (((DATEDIFF(NOW(), start_date) / DATEDIFF(target_date, start_date)) * 100)>=(COALESCE(onefile_learners.Progress,0)+50))
                        THEN 1 END) FROM tr 
                        LEFT JOIN onefile_learners ON onefile_learners.ID = tr.onefile_id
                        WHERE tr.assessor = users.id AND status_code = 1 and bil_withdrawal is null and tag_name = '$tag') AS behind_51_100

                    ,(SELECT GROUP_CONCAT(CASE WHEN (((DATEDIFF(NOW(), start_date) / DATEDIFF(target_date, start_date)) * 100)>=COALESCE(onefile_learners.Progress,0)) 
                        AND (((DATEDIFF(NOW(), start_date) / DATEDIFF(target_date, start_date)) * 100)<(COALESCE(onefile_learners.Progress,0)+100))
                        AND (((DATEDIFF(NOW(), start_date) / DATEDIFF(target_date, start_date)) * 100)>=(COALESCE(onefile_learners.Progress,0)+50))
                        THEN tr.id END) FROM tr 
                        LEFT JOIN onefile_learners ON onefile_learners.ID = tr.onefile_id
                        WHERE tr.assessor = users.id AND status_code = 1 and bil_withdrawal is null and tag_name = '$tag') AS behind_51_100_trs

                    ,(SELECT COUNT(id) FROM tr WHERE status_code = 1 and bil_withdrawal is null and tag_name = '$tag' AND users.id = tr.assessor and (onefile_id IS NULL OR onefile_id NOT IN (SELECT LearnerID FROM onefile_tlap WHERE AssessorSignedOn > DATE_SUB(NOW(), INTERVAL 6 WEEK)))) as no_assessment
                    ,(SELECT group_concat(id) FROM tr WHERE status_code = 1 and bil_withdrawal is null and tag_name = '$tag' and users.id = tr.assessor and (onefile_id IS NULL OR onefile_id NOT IN (SELECT LearnerID FROM onefile_tlap WHERE AssessorSignedOn > DATE_SUB(NOW(), INTERVAL 6 WEEK)))) as no_assessment_trs

                    FROM users WHERE TYPE=3 AND active = 1 order by firstnames, surname;";
                DAO::execute($link, "SET SESSION group_concat_max_len = 1000000;");
                $rows = DAO::getResultset($link, $sql, DAO::FETCH_ASSOC);

                $th = "<div class='panel panel-primary'>";
                $th .= "<div class='panel-heading'><b>" . $tag ."</b></div>";
                $th .= "<div class='chart-panel-body '>";
                $th .= '<table class="table" border="0" cellspacing="0" cellpadding="6">';
                $th .= '<thead style="position: sticky;top: 0"><tr><th class="topRow">&nbsp;</th>';
                $th .= '<th class="topRow">Assessor</th>';
                $th .= '<th class="topRow">In-learning</th>';
                $th .= '<th class="topRow">EPA</th>';
                //$th .= '<th class="topRow">No Assessment 6 Weeks</th>';
                $th .= '<th class="topRow">Progress - 90%+</th>';
                $th .= '<th class="topRow">Progress - On target</th>';
                $th .= '<th class="topRow">Progress - Behind</th>';
                $th .= '<th class="topRow">Progress - Not started</th>';
                $th .= '<th class="topRow">01%-10% Behind</th>';
                $th .= '<th class="topRow">11%-25% Behind</th>';
                $th .= '<th class="topRow">26%-50% Behind</th>';
                $th .= '<th class="topRow">Above 50% Behind</th>';
                $th .= '</tr></thead><tbody>';
                $tb="";    

                $live_gt = 0;
                $above_90_gt = 0;
                $epa_gt = 0;
                $on_target_gt = 0;
                $behind_target_gt = 0;
                $not_started_gt = 0;
                $behind_1_10_gt = 0;
                $behind_11_25_gt = 0;
                $behind_26_50_gt = 0;
                $behind_51_100_gt = 0;

                foreach($rows as $row)
                {
                    if($row['live']>0 or $row['above_90']>0 or $row['on_target']>0 or $row['behind_target']>0 or $row['not_started']>0 or $row['behind_1_10']>0 or $row['behind_11_25']>0 or $row['behind_26_50']>0 or $row['behind_51_100']>0)
                    {
                        $tb .= '<tr>';
                        $tb .= '<td>&nbsp;</td>';
                        $tb .= '<td>' . $row['assessor'] . '</td>';
                        $live = $row['live'];
                        $live_trs = $row['live_trs'];
                        $tb .= '<td><a href="do.php?_action=view_training_records&_reset=1&ViewTrainingRecords_filter_tr_ids=' . $live_trs . '">' . $live . '</a></td>';
                        $epa = $row['epa'];
                        $epa_trs = $row['epa_trs'];
                        $tb .= '<td><a href="do.php?_action=view_training_records&_reset=1&ViewTrainingRecords_filter_tr_ids=' . $epa_trs . '">' . $epa . '</a></td>';
                        //$no_assessment = $row['no_assessment'];
                        //$no_assessment_trs = $row['no_assessment_trs'];
                        //$tb .= '<td><a href="do.php?_action=view_training_records&_reset=1&ViewTrainingRecords_filter_record_status=SHOW_ALL&ViewTrainingRecords_filter_tr_ids=' . $no_assessment_trs . '">' . $no_assessment . '</a></td>';
                        $above_90 = $row['above_90'];
                        $above_90_trs = $row['above_90_trs'];
                        $tb .= '<td><a href="do.php?_action=view_training_records&_reset=1&ViewTrainingRecords_filter_tr_ids=' . $above_90_trs . '">' . $above_90 . '</a></td>';
                        $on_target = $row['on_target'];
                        $on_target_trs = $row['on_target_trs'];
                        $tb .= '<td><a href="do.php?_action=view_training_records&_reset=1&ViewTrainingRecords_filter_tr_ids=' . $on_target_trs . '">' . $on_target . '</a></td>';
                        $behind_target = $row['behind_target'];
                        $behind_target_trs = $row['behind_target_trs'];
                        $tb .= '<td><a href="do.php?_action=view_training_records&_reset=1&ViewTrainingRecords_filter_tr_ids=' . $behind_target_trs . '">' . $behind_target . '</a></td>';
                        $not_started = $row['not_started'];
                        $not_started_trs = $row['not_started_trs'];
                        $tb .= '<td><a href="do.php?_action=view_training_records&_reset=1&ViewTrainingRecords_filter_tr_ids=' . $not_started_trs . '">' . $not_started . '</a></td>';
                        $behind_1_10 = $row['behind_1_10'];
                        $behind_1_10_trs = $row['behind_1_10_trs'];
                        $tb .= '<td><a href="do.php?_action=view_training_records&_reset=1&ViewTrainingRecords_filter_tr_ids=' . $behind_1_10_trs . '">' . $behind_1_10 . '</a></td>';
                        $behind_11_25 = $row['behind_11_25'];
                        $behind_11_25_trs = $row['behind_11_25_trs'];
                        $tb .= '<td><a href="do.php?_action=view_training_records&_reset=1&ViewTrainingRecords_filter_tr_ids=' . $behind_11_25_trs . '">' . $behind_11_25 . '</a></td>';
                        $behind_26_50 = $row['behind_26_50'];
                        $behind_26_50_trs = $row['behind_26_50_trs'];
                        $tb .= '<td><a href="do.php?_action=view_training_records&_reset=1&ViewTrainingRecords_filter_tr_ids=' . $behind_26_50_trs . '">' . $behind_26_50 . '</a></td>';
                        $behind_51_100 = $row['behind_51_100'];
                        $behind_51_100_trs = $row['behind_51_100_trs'];
                        $tb .= '<td><a href="do.php?_action=view_training_records&_reset=1&ViewTrainingRecords_filter_tr_ids=' . $behind_51_100_trs . '">' . $behind_51_100 . '</a></td>';

                        $live_gt += $live;
                        $above_90_gt += $above_90;
                        $epa_gt += $epa;
                        $on_target_gt += $on_target;
                        $behind_target_gt += $behind_target;
                        $not_started_gt += $not_started;
                        $behind_1_10_gt += $behind_1_10;
                        $behind_11_25_gt += $behind_11_25;
                        $behind_26_50_gt += $behind_26_50;
                        $behind_51_100_gt += $behind_51_100;
        
                    }
                }
                if($tb!="")
                {
                    $tb .= '<tr>';
                    $tb .= '<td>&nbsp;</td>';
                    $tb .= '<td>Total</td>';
                    $tb .= '<td>' . $live_gt . '</td>';
                    $tb .= '<td>' . $epa_gt . '</td>';
                    $tb .= '<td>' . $above_90_gt . '</td>';
                    $tb .= '<td>' . $on_target_gt . '</td>';
                    $tb .= '<td>' . $behind_target_gt . '</td>';
                    $tb .= '<td>' . $not_started_gt . '</td>';
                    $tb .= '<td>' . $behind_1_10_gt . '</td>';
                    $tb .= '<td>' . $behind_11_25_gt . '</td>';
                    $tb .= '<td>' . $behind_26_50_gt . '</td>';
                    $tb .= '<td>' . $behind_51_100_gt . '</td>';
                    $t .= $th . $tb . '</tbody></table></div></div>';
                }    
            }
        }
            echo $t;
            exit;
        }

        if($panel == 'LearnerByOTJ')
        {
            $submission = DAO::getSingleValue($link, "SELECT submission AS submission FROM central.`lookup_submission_dates` WHERE CURDATE() BETWEEN start_submission_date AND last_submission_date and contract_year = '$contract_year' ORDER BY contract_year DESC LIMIT 1;");
            $trs = DAO::getSingleValue($link, "SELECT GROUP_CONCAT(tr_id) FROM ilr WHERE submission = '$submission' AND contract_id IN (SELECT id FROM contracts WHERE contract_year = (SELECT contract_year FROM contracts where contract_year = '$contract_year' ORDER BY contract_year DESC LIMIT 1)) AND EXTRACTVALUE(ilr, '/Learner/LearningDelivery[AimType=1]/Outcome')=8");

            $t ="";
            $st = $link->query("select distinct tag_name from tr where tag_name is not null");
            if($st)
            {
                while($tags = $st->fetch(PDO::FETCH_ASSOC))
                {
                    $tag = $tags['tag_name'];    
                    $sql = "SELECT 
                    CONCAT(firstnames,' ', surname) AS assessor
                    ,(SELECT COUNT(id) FROM tr WHERE tr.assessor = users.id AND status_code = 1 and tag_name = '$tag') AS live
                    ,(SELECT GROUP_CONCAT(id) FROM tr WHERE tr.assessor = users.id AND status_code = 1 and tag_name = '$tag') AS live_trs

                    ,(SELECT COUNT(id) FROM tr WHERE tr.assessor = users.id AND status_code = 1 and tag_name = '$tag' and id in($trs)) AS epa
                    ,(SELECT GROUP_CONCAT(id) FROM tr WHERE tr.assessor = users.id AND status_code = 1 and tag_name = '$tag' and id in ($trs)) AS epa_trs

                    ,(SELECT COUNT(CASE WHEN (((DATEDIFF(NOW(), start_date) / DATEDIFF(target_date, start_date)) * 100)<COALESCE(onefile_otj.actual_hours/onefile_otj.planned_otj*100,0)) THEN 1 END) FROM tr 
                        LEFT JOIN onefile_otj ON onefile_otj.onefile_learner_id = tr.onefile_id
                        WHERE tr.assessor = users.id AND status_code = 1 and tag_name = '$tag') AS on_target
                    ,(SELECT GROUP_CONCAT(CASE WHEN (((DATEDIFF(NOW(), start_date) / DATEDIFF(target_date, start_date)) * 100)<COALESCE(onefile_otj.actual_hours/onefile_otj.planned_otj*100,0)) THEN tr.id END) FROM tr 
                        LEFT JOIN onefile_otj ON onefile_otj.onefile_learner_id = tr.onefile_id
                        WHERE tr.assessor = users.id AND status_code = 1 and tag_name = '$tag') AS on_target_trs
                    ,(SELECT COUNT(CASE WHEN (((DATEDIFF(NOW(), start_date) / DATEDIFF(target_date, start_date)) * 100)>=COALESCE(onefile_otj.actual_hours/onefile_otj.planned_otj*100,0)) THEN 1 END) FROM tr 
                        LEFT JOIN onefile_otj ON onefile_otj.onefile_learner_id = tr.onefile_id
                        WHERE tr.assessor = users.id AND status_code = 1 and tag_name = '$tag') AS behind_target
                    ,(SELECT GROUP_CONCAT(CASE WHEN (((DATEDIFF(NOW(), start_date) / DATEDIFF(target_date, start_date)) * 100)>=COALESCE(onefile_otj.actual_hours/onefile_otj.planned_otj*100,0)) THEN tr.id END) FROM tr 
                        LEFT JOIN onefile_otj ON onefile_otj.onefile_learner_id = tr.onefile_id
                        WHERE tr.assessor = users.id AND status_code = 1 and tag_name = '$tag') AS behind_target_trs
                    ,(SELECT COUNT(CASE WHEN (((DATEDIFF(NOW(), start_date) / DATEDIFF(target_date, start_date)) * 100)>=COALESCE(onefile_otj.actual_hours/onefile_otj.planned_otj*100,0)) 
                        AND (((DATEDIFF(NOW(), start_date) / DATEDIFF(target_date, start_date)) * 100)<(COALESCE(onefile_otj.actual_hours/onefile_otj.planned_otj*100,0)+25))
                        AND (((DATEDIFF(NOW(), start_date) / DATEDIFF(target_date, start_date)) * 100)>=(COALESCE(onefile_otj.actual_hours/onefile_otj.planned_otj*100,0)+0))
                        THEN 1 END) FROM tr 
                        LEFT JOIN onefile_otj ON onefile_otj.onefile_learner_id = tr.onefile_id
                        WHERE tr.assessor = users.id AND status_code = 1 and tag_name = '$tag') AS behind_1_25
                    ,(SELECT GROUP_CONCAT(CASE WHEN (((DATEDIFF(NOW(), start_date) / DATEDIFF(target_date, start_date)) * 100)>=COALESCE(onefile_otj.actual_hours/onefile_otj.planned_otj*100,0)) 
                        AND (((DATEDIFF(NOW(), start_date) / DATEDIFF(target_date, start_date)) * 100)<(COALESCE(onefile_otj.actual_hours/onefile_otj.planned_otj*100,0)+25))
                        AND (((DATEDIFF(NOW(), start_date) / DATEDIFF(target_date, start_date)) * 100)>=(COALESCE(onefile_otj.actual_hours/onefile_otj.planned_otj*100,0)+0))
                        THEN tr.id END) FROM tr 
                        LEFT JOIN onefile_otj ON onefile_otj.onefile_learner_id = tr.onefile_id
                        WHERE tr.assessor = users.id AND status_code = 1 and tag_name = '$tag') AS behind_1_25_trs
                    ,(SELECT COUNT(CASE WHEN (((DATEDIFF(NOW(), start_date) / DATEDIFF(target_date, start_date)) * 100)>=COALESCE(onefile_otj.actual_hours/onefile_otj.planned_otj*100,0)) 
                        AND (((DATEDIFF(NOW(), start_date) / DATEDIFF(target_date, start_date)) * 100)<(COALESCE(onefile_otj.actual_hours/onefile_otj.planned_otj*100,0)+50))
                        AND (((DATEDIFF(NOW(), start_date) / DATEDIFF(target_date, start_date)) * 100)>=(COALESCE(onefile_otj.actual_hours/onefile_otj.planned_otj*100,0)+25))
                        THEN 1 END) FROM tr 
                        LEFT JOIN onefile_otj ON onefile_otj.onefile_learner_id = tr.onefile_id
                        WHERE tr.assessor = users.id AND status_code = 1 and tag_name = '$tag') AS behind_26_50
                    ,(SELECT GROUP_CONCAT(CASE WHEN (((DATEDIFF(NOW(), start_date) / DATEDIFF(target_date, start_date)) * 100)>=COALESCE(onefile_otj.actual_hours/onefile_otj.planned_otj*100,0)) 
                        AND (((DATEDIFF(NOW(), start_date) / DATEDIFF(target_date, start_date)) * 100)<(COALESCE(onefile_otj.actual_hours/onefile_otj.planned_otj*100,0)+50))
                        AND (((DATEDIFF(NOW(), start_date) / DATEDIFF(target_date, start_date)) * 100)>=(COALESCE(onefile_otj.actual_hours/onefile_otj.planned_otj*100,0)+25))
                        THEN tr.id END) FROM tr 
                        LEFT JOIN onefile_otj ON onefile_otj.onefile_learner_id = tr.onefile_id
                        WHERE tr.assessor = users.id AND status_code = 1 and tag_name = '$tag') AS behind_26_50_trs
                    ,(SELECT COUNT(CASE WHEN (((DATEDIFF(NOW(), start_date) / DATEDIFF(target_date, start_date)) * 100)>=COALESCE(onefile_otj.actual_hours/onefile_otj.planned_otj*100,0)) 
                        AND (((DATEDIFF(NOW(), start_date) / DATEDIFF(target_date, start_date)) * 100)<(COALESCE(onefile_otj.actual_hours/onefile_otj.planned_otj*100,0)+75))
                        AND (((DATEDIFF(NOW(), start_date) / DATEDIFF(target_date, start_date)) * 100)>=(COALESCE(onefile_otj.actual_hours/onefile_otj.planned_otj*100,0)+50))
                        THEN 1 END) FROM tr 
                        LEFT JOIN onefile_otj ON onefile_otj.onefile_learner_id = tr.onefile_id
                        WHERE tr.assessor = users.id AND status_code = 1 and tag_name = '$tag') AS behind_51_75
                    ,(SELECT GROUP_CONCAT(CASE WHEN (((DATEDIFF(NOW(), start_date) / DATEDIFF(target_date, start_date)) * 100)>=COALESCE(onefile_otj.actual_hours/onefile_otj.planned_otj*100,0)) 
                        AND (((DATEDIFF(NOW(), start_date) / DATEDIFF(target_date, start_date)) * 100)<(COALESCE(onefile_otj.actual_hours/onefile_otj.planned_otj*100,0)+75))
                        AND (((DATEDIFF(NOW(), start_date) / DATEDIFF(target_date, start_date)) * 100)>=(COALESCE(onefile_otj.actual_hours/onefile_otj.planned_otj*100,0)+50))
                        THEN tr.id END) FROM tr 
                        LEFT JOIN onefile_otj ON onefile_otj.onefile_learner_id = tr.onefile_id
                        WHERE tr.assessor = users.id AND status_code = 1 and tag_name = '$tag') AS behind_51_75_trs
                    ,(SELECT COUNT(CASE WHEN (((DATEDIFF(NOW(), start_date) / DATEDIFF(target_date, start_date)) * 100)>=COALESCE(onefile_otj.actual_hours/onefile_otj.planned_otj*100,0)) 
                        AND (((DATEDIFF(NOW(), start_date) / DATEDIFF(target_date, start_date)) * 100)<(COALESCE(onefile_otj.actual_hours/onefile_otj.planned_otj*100,0)+100))
                        AND (((DATEDIFF(NOW(), start_date) / DATEDIFF(target_date, start_date)) * 100)>=(COALESCE(onefile_otj.actual_hours/onefile_otj.planned_otj*100,0)+75))
                        THEN 1 END) FROM tr 
                        LEFT JOIN onefile_otj ON onefile_otj.onefile_learner_id = tr.onefile_id
                        WHERE tr.assessor = users.id AND status_code = 1 and tag_name = '$tag') AS behind_76_100
                    ,(SELECT GROUP_CONCAT(CASE WHEN (((DATEDIFF(NOW(), start_date) / DATEDIFF(target_date, start_date)) * 100)>=COALESCE(onefile_otj.actual_hours/onefile_otj.planned_otj*100,0)) 
                        AND (((DATEDIFF(NOW(), start_date) / DATEDIFF(target_date, start_date)) * 100)<(COALESCE(onefile_otj.actual_hours/onefile_otj.planned_otj*100,0)+100))
                        AND (((DATEDIFF(NOW(), start_date) / DATEDIFF(target_date, start_date)) * 100)>=(COALESCE(onefile_otj.actual_hours/onefile_otj.planned_otj*100,0)+75))
                        THEN tr.id END) FROM tr 
                        LEFT JOIN onefile_otj ON onefile_otj.onefile_learner_id = tr.onefile_id
                        WHERE tr.assessor = users.id AND status_code = 1 and tag_name = '$tag') AS behind_76_100_trs

                    ,(SELECT COUNT(id) FROM tr WHERE status_code = 1 and bil_withdrawal is null and tag_name = '$tag' AND users.id = tr.assessor and (onefile_id IS NULL OR onefile_id NOT IN (SELECT LearnerID FROM onefile_tlap WHERE AssessorSignedOn > DATE_SUB(NOW(), INTERVAL 6 WEEK)))) as no_assessment
                    ,(SELECT group_concat(id) FROM tr WHERE status_code = 1 and bil_withdrawal is null and tag_name = '$tag' and users.id = tr.assessor and (onefile_id IS NULL OR onefile_id NOT IN (SELECT LearnerID FROM onefile_tlap WHERE AssessorSignedOn > DATE_SUB(NOW(), INTERVAL 6 WEEK)))) as no_assessment_trs

                    FROM users WHERE TYPE=3 AND active = 1 order by firstnames, surname;";
                    DAO::execute($link, "SET SESSION group_concat_max_len = 1000000;");
                    $rows = DAO::getResultset($link, $sql, DAO::FETCH_ASSOC);

                    $th = "<div class='panel panel-primary'>";
                    $th .= "<div class='panel-heading'><b>" . $tag ."</b></div>";
                    $th .= "<div class='chart-panel-body '>";
                    $th .= '<table class="table" border="0" cellspacing="0" cellpadding="6">';
                    $th .= '<thead style="position: sticky;top: 0"><tr><th class="topRow">&nbsp;</th>';
                    $th .= '<th class="topRow">Assessor</th>';
                    $th .= '<th class="topRow">In-learning</th>';
                    $th .= '<th class="topRow">EPA</th>';
                    //$th .= '<th class="topRow">No Assessment 6 Weeks</th>';
                    $th .= '<th class="topRow">Progress - On target</th>';
                    $th .= '<th class="topRow">Progress - Behind</th>';
                    $th .= '<th class="topRow">Behind by 1%-25%</th>';
                    $th .= '<th class="topRow">Behind by 26%-50%</th>';
                    $th .= '<th class="topRow">Behind by 51%-75%</th>';
                    $th .= '<th class="topRow">Behind by above 75%</th>';
                    $th .= '</tr></thead><tbody>';
                    $tb="";

                    $live_gt = 0;
                    $epa_gt = 0;
                    $on_target_gt = 0;
                    $behind_target_gt = 0;
                    $behind_1_25_gt = 0;
                    $behind_26_50_gt = 0;
                    $behind_51_75_gt = 0;
                    $behind_76_100_gt = 0;

                    foreach($rows as $row)
                    {
                        if($row['live']>0 or $row['on_target']>0 or $row['behind_target']>0 or $row['behind_1_25']>0 or $row['behind_26_50']>0 or $row['behind_51_75']>0 or $row['behind_76_100']>0)
                        {
                            $tb .= '<tr>';
                            $tb .= '<td>&nbsp;</td>';
                            $tb .= '<td>' . $row['assessor'] . '</td>';
                            $live = $row['live'];
                            $live_trs = $row['live_trs'];
                            $tb .= '<td><a href="do.php?_action=view_training_records&_reset=1&ViewTrainingRecords_filter_tr_ids=' . $live_trs . '">' . $live . '</a></td>';
                            $epa = $row['epa'];
                            $epa_trs = $row['epa_trs'];
                            $tb .= '<td><a href="do.php?_action=view_training_records&_reset=1&ViewTrainingRecords_filter_tr_ids=' . $epa_trs . '">' . $epa . '</a></td>';
                            //$no_assessment = $row['no_assessment'];
                            //$no_assessment_trs = $row['no_assessment_trs'];
                            //$tb .= '<td><a href="do.php?_action=view_training_records&_reset=1&ViewTrainingRecords_filter_record_status=SHOW_ALL&ViewTrainingRecords_filter_tr_ids=' . $no_assessment_trs . '">' . $no_assessment . '</a></td>';
                            $on_target = $row['on_target'];
                            $on_target_trs = $row['on_target_trs'];
                            $tb .= '<td><a href="do.php?_action=view_training_records&_reset=1&ViewTrainingRecords_filter_tr_ids=' . $on_target_trs . '">' . $on_target . '</a></td>';
                            $behind_target = $row['behind_target'];
                            $behind_target_trs = $row['behind_target_trs'];
                            $tb .= '<td><a href="do.php?_action=view_training_records&_reset=1&ViewTrainingRecords_filter_tr_ids=' . $behind_target_trs . '">' . $behind_target . '</a></td>';
                            $behind_1_25 = $row['behind_1_25'];
                            $behind_1_25_trs = $row['behind_1_25_trs'];
                            $tb .= '<td><a href="do.php?_action=view_training_records&_reset=1&ViewTrainingRecords_filter_tr_ids=' . $behind_1_25_trs . '">' . $behind_1_25 . '</a></td>';
                            $behind_26_50 = $row['behind_26_50'];
                            $behind_26_50_trs = $row['behind_26_50_trs'];
                            $tb .= '<td><a href="do.php?_action=view_training_records&_reset=1&ViewTrainingRecords_filter_tr_ids=' . $behind_26_50_trs . '">' . $behind_26_50 . '</a></td>';
                            $behind_51_75 = $row['behind_51_75'];
                            $behind_51_75_trs = $row['behind_51_75_trs'];
                            $tb .= '<td><a href="do.php?_action=view_training_records&_reset=1&ViewTrainingRecords_filter_tr_ids=' . $behind_51_75_trs . '">' . $behind_51_75 . '</a></td>';
                            $behind_76_100 = $row['behind_76_100'];
                            $behind_76_100_trs = $row['behind_76_100_trs'];
                            $tb .= '<td><a href="do.php?_action=view_training_records&_reset=1&ViewTrainingRecords_filter_tr_ids=' . $behind_76_100_trs . '">' . $behind_76_100 . '</a></td>';

                            $live_gt += $live;
                            $epa_gt += $epa;
                            $on_target_gt += $on_target;
                            $behind_target_gt += $behind_target;
                            $behind_1_25_gt += $behind_1_25;
                            $behind_26_50_gt += $behind_26_50;
                            $behind_51_75_gt += $behind_51_75;
                            $behind_76_100_gt += $behind_76_100;
                        }
                    }
                    if($tb!="")
                    {
                        $tb .= '<tr>';
                        $tb .= '<td>&nbsp;</td>';
                        $tb .= '<td>Total</td>';
                        $tb .= '<td>' . $live_gt . '</td>';
                        $tb .= '<td>' . $epa_gt . '</td>';
                        $tb .= '<td>' . $on_target_gt . '</td>';
                        $tb .= '<td>' . $behind_target_gt . '</td>';
                        $tb .= '<td>' . $behind_1_25_gt . '</td>';
                        $tb .= '<td>' . $behind_26_50_gt . '</td>';
                        $tb .= '<td>' . $behind_51_75_gt . '</td>';
                        $tb .= '<td>' . $behind_76_100_gt . '</td>';
                        $t .= $th . $tb . '</tbody></table></div></div>';
                    }
               }
            }
            echo $t;
            exit;
        }

        if($panel == 'LearnerByLevelApp')
        {
            echo "Under constructon";
            exit;
        }

        if($panel == 'LearnerByLevelEducation')
        {
            echo "Under constructon";
            exit;
        }

        if($panel == 'RetentionByLevelTable')
        {
            echo "Under constructon";
            exit;
        }

        if($panel == 'leavers')
        {
            echo "Under constructon";
            exit;
        }

        if($panel == 'leaversbytrend')
        {
            echo "Under constructon";
            exit;
        }

        if($panel == 'leaversbytrendactual')
        {
            echo "Under constructon";
            exit;
        }

        if($panel == 'leaversbytrendonprogramme')
        {
            echo "Under constructon";
            exit;
        }

        if($panel == 'leaversbyreason')
        {
            echo "Under constructon";
            exit;
        }

        if($panel == 'leaversbyimpact')
        {
            echo "Under constructon";
            exit;
        }

        if($panel == 'leaversbyactual')
        {
            echo "Under constructon";
            exit;
        }

        include_once('tpl_ela_reports.php');

    }

    public function getTable($link, $year, $by, $filters, $qar_type_filter)
    {
        $data = '<br><table class="table1"  style="margin-left:10px; margin-right:10px"><thead><tr><td colspan=2>Apprenticeships<span style="margin-left:10px; background: #90ee90">&nbsp;&nbsp;&nbsp;&nbsp;</span></td>';
        $start_index = sizeof($year)-5;
        if($start_index<0)
            $start_index =0;
        for($i = $start_index; $i<=sizeof($year)-1; $i++)
            $data.= "<th colspan=2 style='text-align: center'>" . Date::getFiscal($year[$i]);

        $data .= '<tr><td colspan=2>Education & Training<span style="margin-left:10px; background: #ffb6c1">&nbsp;&nbsp;&nbsp;&nbsp;</span></td></th>';
        $data .= "</tr><tr><th colspan=2></th>";
        for($i = $start_index; $i<=sizeof($year)-1; $i++)
            $data.= "<th>Cohort</th><th>QAR</th>";
        if($by=='sfc')
        {
            $outer_query = "select distinct ssa1 from success_rates where ssa1 is not null and programme_type = 'Apprenticeship' order by ssa1";
            $ssa1_values = DAO::getSingleColumn($link, $outer_query);
            foreach($ssa1_values as $ssa1_value)
            {
                $cohort = Array();
                $rate = Array();
                $query = "select distinct " . $by . " from success_rates where " . $by . " is not null and programme_type = 'Apprenticeship' and ssa1 = '$ssa1_value' order by " . $by;
                $by_values = DAO::getSingleColumn($link, $query);
                $value_found = false;
                foreach($by_values as $value)
                {
                    //if($ssa1_value=="06 - Information and Communication Technology" && $value!="2 - Software Developer" and $value!="576 - Data Technician")
                      // pre($value);
                    $display_level = $value;
                    $row = "</tr></thead><tbody><tr><td width='2000px' colspan=2 style='background: #90ee90;'>".$display_level."</td>";
                    $value_found = false;
                    for($i = $start_index; $i<=sizeof($year)-1; $i++)
                    {
                        $filters['year'] = $year[$i];
                        $filters['programme_type'] = "Apprenticeship";
                        $filters[$by] = $value;
                        $QAR = $this->getQAR($link,$filters);
                        if(!isset($rate[$i]))
                            $rate[$i] = array();

                        if($qar_type_filter=="Overall")
                        {
                            $row.= "<td><a href=javascript:expor('" . $QAR['OverallLeaver'][0][1] . "');>" . $QAR['OverallLeaver'][0][0] . "</td>";
                            if(isset($cohort[$i]))
                                $cohort[$i]+= $QAR['OverallLeaver'][0][0];
                            else
                                $cohort[$i] = $QAR['OverallLeaver'][0][0];
                            if($QAR['OverallLeaver'][0][0]>0)
                            {    $row.= "<td>" . sprintf("%.2f",$QAR['OverallAchiever'][0][0]/$QAR['OverallLeaver'][0][0]*100) . "%</td>";
                                $value_found = true;
                            }
                            else
                                $row.= "<td>0%</td>";

                            //SSA1 Rate
                            if($QAR['OverallLeaver'][0][0]>0)
                            {
                                $rate[$i][]=sprintf("%.2f",$QAR['OverallAchiever'][0][0]/$QAR['OverallLeaver'][0][0]*100);
                            }
                        }
                        else
                        {
                            $row.= "<td><a href=javascript:expor('" . $QAR['TimelyLeaver'][0][1] . "');>" . $QAR['TimelyLeaver'][0][0] . "</td>";
                            if($QAR['TimelyLeaver'][0][0]>0)
                            {    $row.= "<td>" . sprintf("%.2f",$QAR['TimelyAchiever'][0][0]/$QAR['TimelyLeaver'][0][0]*100) . "%</td>";
                                $value_found = true;
                            }
                            else
                                $row.= "<td>0%</td>";

                            if($QAR['TimelyLeaver'][0][0]>0)
                            {
                                $rate[$i][]=sprintf("%.2f",$QAR['TimelyAchiever'][0][0]/$QAR['TimelyLeaver'][0][0]*100);
                            }


                        }
                    }
                    if($value_found)
                        $data.=$row;
                }

                // Total SSA1 Row
                if($value_found)
                {
                    $row = "</tr></thead><tbody><tr><td width='2000px' colspan=2 style='background: #ee90ee;'>".$ssa1_value." (Group Total)</td>";
                    for($i = $start_index; $i<=sizeof($year)-1; $i++)
                    {
                        $row.= isset($cohort[$i]) ? "<td>" . $cohort[$i] . "</td>" : "<td> - </td>";
                        $n = (count($rate[$i])==0)?1:count($rate[$i]);
                        $r = array_sum($rate[$i])/$n;
                        $row.= "<td>" . sprintf("%.2f",$r) . "</td>";
                    }
                    $data.=$row;
                }

            }
        }
        else
        {
            $query = "select distinct " . $by . " from success_rates where " . $by . " is not null order by " . $by;
            $by_values = DAO::getSingleColumn($link, $query);
            foreach($by_values as $value)
            {
                if($value=='2')
                    $display_level = "2 - Advanced Apprenticeship";
                elseif($value=='3')
                    $display_level = "3 - Intermediate Apprenticeship";
                elseif($value=='20')
                    $display_level = "20 - Higher Apprenticeship";
                elseif($value=='25')
                    $display_level = "25 - Standard";
                elseif($value=='Std 2')
                    $display_level = "Level 2 - Standard";
                elseif($value=='Std 3')
                    $display_level = "Level 3 - Standard";
                else
                    $display_level = $value;

                $row = "</tr></thead><tbody><tr><td width='2000px' colspan=2 style='background: #90ee90;'>".$display_level."</td>";
                $value_found = false;
                for($i = $start_index; $i<=sizeof($year)-1; $i++)
                {
                    $filters['year'] = $year[$i];
                    $filters['programme_type'] = "Apprenticeship";
                    $filters[$by] = $value;
                    $QAR = $this->getQAR($link,$filters);

                    if($qar_type_filter=="Overall")
                    {
                        $row.= "<td><a href=javascript:expor('"  . $QAR['OverallLeaver'][0][1] . "');>" . $QAR['OverallLeaver'][0][0] . "</td>";
                        if($QAR['OverallLeaver'][0][0]>0)
                        {    $row.= "<td>" . sprintf("%.2f",$QAR['OverallAchiever'][0][0]/$QAR['OverallLeaver'][0][0]*100) . "%</td>";
                            $value_found = true;
                        }
                        else
                            $row.= "<td>0%</td>";
                    }
                    else
                    {
                        $row.= "<td><a href=javascript:expor('" . $QAR['TimelyLeaver'][0][1] . "');>" .  $QAR['TimelyLeaver'][0][0] . "</td>";
                        if($QAR['TimelyLeaver'][0][0]>0)
                        {    $row.= "<td>" . sprintf("%.2f",$QAR['TimelyAchiever'][0][0]/$QAR['TimelyLeaver'][0][0]*100) . "%</td>";
                            $value_found = true;
                        }
                        else
                            $row.= "<td>0%</td>";
                    }
                }
                if($value_found)
                    $data.=$row;
            }
        }
        if($by!='programme' and $by!='sfc')
        foreach($by_values as $value)
        {
            $row = "</tr></thead><tbody><tr><td width='2000px' colspan=2 style='background: #ffb6c1'>".$value."</td>";
            $value_found = false;
            for($i = $start_index; $i<=sizeof($year)-1; $i++)
            {
                $filters['year'] = $year[$i];
                $filters[$by] = $value;
                $filters['programme_type'] = "Education";
                $QAR = $this->getQAR($link,$filters);

                if($qar_type_filter=="Overall")
                {
                    $row.= "<td><a href=javascript:expor('" . $QAR['OverallLeaver'][0][1] . "');>" . $QAR['OverallLeaver'][0][0] . "</td>";
                    if($QAR['OverallLeaver'][0][0]>0)
                    {
                        $row.= "<td>" . sprintf("%.2f",$QAR['OverallAchiever'][0][0]/$QAR['OverallLeaver'][0][0]*100) . "%</td>";
                        $value_found = true;
                    }
                    else
                        $row.= "<td>0%</td>";
                }
                else
                {
                    $row.= "<td><a href=javascript:expor('" . $QAR['TimelyLeaver'][0][1] . "');>" . $QAR['TimelyLeaver'][0][0] . "</td>";
                    if($QAR['TimelyLeaver'][0][0]>0)
                    {
                        $row.= "<td>" . sprintf("%.2f",$QAR['TimelyAchiever'][0][0]/$QAR['TimelyLeaver'][0][0]*100) . "%</td>";
                        $value_found = true;
                    }
                    else
                        $row.= "<td>0%</td>";
                }
            }
            if($value_found)
                $data.=$row;
        }
        $data .= "</tr></tbody></table><br>";
        return $data;
    }

    public function getRetentionTable($link, $year, $by, $filters, $qar_type_filter)
    {
        $data = '<br><table class="table1"  style="margin-left:10px; margin-right:10px"><thead><tr><td colspan=2>Apprenticeships<span style="margin-left:10px; background: #90ee90">&nbsp;&nbsp;&nbsp;&nbsp;</span></td>';
        $start_index = sizeof($year)-5;
        if($start_index<0)
            $start_index =0;
        for($i = $start_index; $i<=sizeof($year)-1; $i++)
            $data.= "<th colspan=2 style='text-align: center'>" . Date::getFiscal($year[$i]);

        $data .= '<tr><td colspan=2>Education & Training<span style="margin-left:10px; background: #ffb6c1">&nbsp;&nbsp;&nbsp;&nbsp;</span></td></th>';
        $data .= "</tr><tr><th colspan=2></th>";
        for($i = $start_index; $i<=sizeof($year)-1; $i++)
            $data.= "<th>Cohort</th><th>Ret:</th>";
        if($by=='sfc')
        {
            $outer_query = "select distinct ssa1 from success_rates where ssa1 is not null and programme_type = 'Apprenticeship' order by ssa1";
            $ssa1_values = DAO::getSingleColumn($link, $outer_query);
            foreach($ssa1_values as $ssa1_value)
            {
                $cohort = Array();
                $rate = Array();
                $query = "select distinct " . $by . " from success_rates where " . $by . " is not null and programme_type = 'Apprenticeship' and ssa1 = '$ssa1_value' order by " . $by;
                $by_values = DAO::getSingleColumn($link, $query);
                $value_found = false;
                foreach($by_values as $value)
                {
                    $display_level = $value;
                    $row = "</tr></thead><tbody><tr><td width='2000px' colspan=2 style='background: #90ee90;'>".$display_level."</td>";
                    $value_found = false;
                    for($i = $start_index; $i<=sizeof($year)-1; $i++)
                    {
                        $filters['year'] = $year[$i];
                        $filters['programme_type'] = "Apprenticeship";
                        $filters[$by] = $value;
                        $QAR = $this->getQAR($link,$filters);
                        if(!isset($rate[$i]))
                            $rate[$i] = array();

                        if($qar_type_filter=="Overall")
                        {
                            $row.= "<td><a href=javascript:expor('" . $QAR['OverallLeaver'][0][1] . "');>" . $QAR['OverallLeaver'][0][0] . "</td>";
                            if(isset($cohort[$i]))
                                $cohort[$i]+= $QAR['OverallLeaver'][0][0];
                            else
                                $cohort[$i] = $QAR['OverallLeaver'][0][0];
                            if($QAR['OverallLeaver'][0][0]>0)
                            {    $row.= "<td>" . sprintf("%.2f",$QAR['OverallAchiever'][0][0]/$QAR['OverallLeaver'][0][0]*100) . "%</td>";
                                $value_found = true;
                            }
                            else
                                $row.= "<td>0%</td>";

                            //SSA1 Rate
                            if($QAR['OverallLeaver'][0][0]>0)
                            {
                                $rate[$i][]=sprintf("%.2f",$QAR['OverallAchiever'][0][0]/$QAR['OverallLeaver'][0][0]*100);
                            }
                        }
                        else
                        {
                            $row.= "<td><a href=javascript:expor('" . $QAR['TimelyLeaver'][0][1] . "');>" . $QAR['TimelyLeaver'][0][0] . "</td>";
                            if($QAR['TimelyLeaver'][0][0]>0)
                            {    $row.= "<td>" . sprintf("%.2f",$QAR['TimelyAchiever'][0][0]/$QAR['TimelyLeaver'][0][0]*100) . "%</td>";
                                $value_found = true;
                            }
                            else
                                $row.= "<td>0%</td>";

                            if($QAR['TimelyLeaver'][0][0]>0)
                            {
                                $rate[$i][]=sprintf("%.2f",$QAR['TimelyAchiever'][0][0]/$QAR['TimelyLeaver'][0][0]*100);
                            }


                        }
                    }
                    if($value_found)
                        $data.=$row;
                }

                // Total SSA1 Row
                if($value_found)
                {
                    $row = "</tr></thead><tbody><tr><td width='2000px' colspan=2 style='background: #ee90ee;'>".$ssa1_value." (Group Total)</td>";
                    for($i = $start_index; $i<=sizeof($year)-1; $i++)
                    {
                        $row.= isset($cohort[$i]) ? "<td>" . $cohort[$i] . "</td>" : "<td> - </td>";
                        $n = (count($rate[$i])==0)?1:count($rate[$i]);
                        $r = array_sum($rate[$i])/$n;
                        $row.= "<td>" . sprintf("%.2f",$r) . "</td>";
                    }
                    $data.=$row;
                }

            }
        }
        else
        {
            $query = "select distinct " . $by . " from success_rates where " . $by . " is not null order by " . $by;
            $by_values = DAO::getSingleColumn($link, $query);
            foreach($by_values as $value)
            {
                if($value=='2')
                    $display_level = "2 - Advanced Apprenticeship";
                elseif($value=='3')
                    $display_level = "3 - Intermediate Apprenticeship";
                elseif($value=='20')
                    $display_level = "20 - Higher Apprenticeship";
                elseif($value=='25')
                    $display_level = "25 - Standard";
                else
                    $display_level = $value;

                $row = "</tr></thead><tbody><tr><td width='2000px' colspan=2 style='background: #90ee90;'>".$display_level."</td>";
                $value_found = false;
                for($i = $start_index; $i<=sizeof($year)-1; $i++)
                {
                    $filters['year'] = $year[$i];
                    $filters['programme_type'] = "Apprenticeship";
                    $filters[$by] = $value;
                    $QAR = $this->getRetention($link,$filters);

                    if($qar_type_filter=="Overall")
                    {
                        $row.= "<td><a href=javascript:expor('"  . $QAR['OverallLeaver'][0][1] . "');>" . $QAR['OverallLeaver'][0][0] . "</td>";
                        if($QAR['OverallLeaver'][0][0]>0)
                        {    $row.= "<td>" . sprintf("%.2f",$QAR['OverallAchiever'][0][0]/$QAR['OverallLeaver'][0][0]*100) . "%</td>";
                            $value_found = true;
                        }
                        else
                            $row.= "<td>0%</td>";
                    }
                    else
                    {
                        $row.= "<td><a href=javascript:expor('" . $QAR['TimelyLeaver'][0][1] . "');>" .  $QAR['TimelyLeaver'][0][0] . "</td>";
                        if($QAR['TimelyLeaver'][0][0]>0)
                        {    $row.= "<td>" . sprintf("%.2f",$QAR['TimelyAchiever'][0][0]/$QAR['TimelyLeaver'][0][0]*100) . "%</td>";
                            $value_found = true;
                        }
                        else
                            $row.= "<td>0%</td>";
                    }
                }
                if($value_found)
                    $data.=$row;
            }
        }
        if($by!='programme' and $by!='sfc')
            foreach($by_values as $value)
            {
                $row = "</tr></thead><tbody><tr><td width='2000px' colspan=2 style='background: #ffb6c1'>".$value."</td>";
                $value_found = false;
                for($i = $start_index; $i<=sizeof($year)-1; $i++)
                {
                    $filters['year'] = $year[$i];
                    $filters[$by] = $value;
                    $filters['programme_type'] = "Education";
                    $QAR = $this->getQAR($link,$filters);

                    if($qar_type_filter=="Overall")
                    {
                        $row.= "<td><a href=javascript:expor('" . $QAR['OverallLeaver'][0][1] . "');>" . $QAR['OverallLeaver'][0][0] . "</td>";
                        if($QAR['OverallLeaver'][0][0]>0)
                        {
                            $row.= "<td>" . sprintf("%.2f",$QAR['OverallAchiever'][0][0]/$QAR['OverallLeaver'][0][0]*100) . "%</td>";
                            $value_found = true;
                        }
                        else
                            $row.= "<td>0%</td>";
                    }
                    else
                    {
                        $row.= "<td><a href=javascript:expor('" . $QAR['TimelyLeaver'][0][1] . "');>" . $QAR['TimelyLeaver'][0][0] . "</td>";
                        if($QAR['TimelyLeaver'][0][0]>0)
                        {
                            $row.= "<td>" . sprintf("%.2f",$QAR['TimelyAchiever'][0][0]/$QAR['TimelyLeaver'][0][0]*100) . "%</td>";
                            $value_found = true;
                        }
                        else
                            $row.= "<td>0%</td>";
                    }
                }
                if($value_found)
                    $data.=$row;
            }
        $data .= "</tr></tbody></table><br>";
        return $data;
    }

    public function getChart($link, $year, $by, $filters, $qar_type_filter,$app)
    {
        $result = array();
        $category = Array();
        $data = Array();
        $start_index = sizeof($year)-5;
        if($start_index<0)
            $start_index =0;
        for($i = $start_index; $i<=sizeof($year)-1; $i++)
            $category['categories'][]= Date::getFiscal($year[$i]);

        if($app=='Apprenticeship')
            $query = "select distinct " . $by . " from success_rates where programme_type ='Apprenticeship' and " . $by . " is not null order by " . $by;
        else
            $query = "select distinct " . $by . " from success_rates where programme_type !='Apprenticeship' and " . $by . " is not null order by " . $by;

        $by_values = DAO::getSingleColumn($link, $query);
        foreach($by_values as $value)
        {
            $series = Array();
            $value_found = false;
            $series['name'] = $value;
            for($i = $start_index; $i<=sizeof($year)-1; $i++)
            {
                $filters['year'] = $year[$i];
                $filters['programme_type'] = $app;
                $filters[$by] = $value;
                $QAR = $this->getQAR($link,$filters);

                if($qar_type_filter=="Overall")
                {
                    if($QAR['OverallLeaver'][0][0]>0)
                    {
                        $series['data'][]= sprintf("%.2f",$QAR['OverallAchiever'][0][0]/$QAR['OverallLeaver'][0][0]*100);
                        $value_found = true;
                    }
                    else
                        $series['data'][]= 0;
                }
                else
                {
                    if($QAR['TimelyLeaver'][0][0]>0)
                    {
                        $series['data'][]= sprintf("%.2f",$QAR['TimelyAchiever'][0][0]/$QAR['TimelyLeaver'][0][0]*100);
                        $value_found = true;
                    }
                    else
                        $series['data'][]= 0;
                }
            }
            array_push($result,$series);
        }
        echo(json_encode($result, JSON_NUMERIC_CHECK));
    }



    public function createTempTable(PDO $link)
    {
        $sql = <<<HEREDOC
CREATE TEMPORARY TABLE `success_rates` (
  `l03` varchar(12) DEFAULT NULL,
  `tr_id` int(11) DEFAULT NULL,
  `programme_type` varchar(15) DEFAULT NULL,
  `start_date` date DEFAULT NULL,
  `planned_end_date` date DEFAULT NULL,
  `actual_end_date` date DEFAULT NULL,
  `achievement_date` date DEFAULT NULL,
  `expected` int(11) DEFAULT NULL,
  `actual` int(11) DEFAULT NULL,
  `completion_status` int(11) DEFAULT NULL,
  `outcome` int(11) DEFAULT NULL,
  `hybrid` int(11) DEFAULT NULL,
  `p_prog_status` int(11) DEFAULT NULL,
  `contract_id` int(11) DEFAULT NULL,
  `submission` varchar(3) DEFAULT NULL,
  `level` varchar(20) DEFAULT NULL,
  `age_band` varchar(20) DEFAULT NULL,
  `a09` varchar(8) DEFAULT NULL,
  `local_authority` varchar(50) DEFAULT NULL,
  `region` varchar(50) DEFAULT NULL,
  `postcode` varchar(10) DEFAULT NULL,
  `sfc` varchar(100) DEFAULT NULL,
  `ssa1` varchar(100) DEFAULT NULL,
  `ssa2` varchar(100) DEFAULT NULL,
  `employer` varchar(100) DEFAULT NULL,
  `assessor` varchar(100) DEFAULT NULL,
  `provider` varchar(100) DEFAULT NULL,
  `contractor` varchar(100) DEFAULT NULL,
  `ethnicity` varchar(255) DEFAULT NULL,
  `aim_type` varchar(50) DEFAULT NULL,
  `lldd` varchar(20) DEFAULT NULL,
  `gender` varchar(10) DEFAULT NULL,
  `programme` varchar(200) DEFAULT NULL,
  `dob` date DEFAULT NULL,
  `StdCode` int(11) default null,
  `FworkCode` int(11) default null,
  `PwayCode` int(11) default null,
  `data_error` int(11) default null,
  `year_left` int(11) default null,
  KEY `prog` (`programme_type`,`expected`,`actual`),
  INDEX(ssa1), INDEX(ssa2), index(programme_type), index(age_band), index(employer), index(assessor), index(provider), index(contractor)
) ENGINE 'MEMORY'
HEREDOC;
        DAO::execute($link, $sql);
    }

    public function getQAR($link, $filters = Array())
    {
        DAO::execute($link, "SET SESSION group_concat_max_len = 1000000000;");
        $where = '';
        foreach($filters as $key => $value)
        {
            $value = addslashes($value);
            if($key=='year')
                $year = $value;
            elseif($key=='ssa')
                $where .= " and concat(ssa1,'<br>',ssa2)='$value'";
            elseif($key=='programme_type' && $value=='Apprenticeship')
                $where .= " and " . $key . " = '$value'";
            elseif($key=='programme_type' && $value=='Standard')
                $where .= " and StdCode is not null and FworkCode is null";
            elseif($key=='programme_type' && $value=='Framework')
                $where .= " and FworkCode is not null and StdCode is null";
            elseif($key=='programme_type' && $value=='Education')
                $where .= " and " . $key . " != 'Apprenticeship'";
            elseif($key=='age_band' && $value=='19+')
                $where .= " and (age_band = '19-23' OR age_band = '24+')";
            elseif($key=='learner_type' && $value!='All learner type')
                $where .= " and learner_type = '$value'";
            elseif($key=='employer_type' && $value!='All employer type')
                $where .= " and employer_type = '$value'";
            else
                $where .= " and " . $key . " = '$value'";
       }


        if($this->level!='' and $this->level!='All level')
        {
            $where .= " and programme_type='Apprenticeship' and level = '" . $this->level . "'";
        }

        if($this->at_risk!='' and $this->at_risk!='1')
        {
            if($this->at_risk=='2')
                $where .= " and at_risk='1'";
            elseif($this->at_risk=='3')
                $where .= " and (at_risk!='1' or at_risk is null) ";
        }

        $result = Array();
        if($this->case_scenario=="Actual")
        {
            $result['OverallLeaver'] = DAO::getResultset($link, "SELECT count(*),GROUP_CONCAT(tr_id) FROM success_rates WHERE ((expected = $year AND actual<= $year) OR (expected <= $year AND actual = $year))  $where;");
            $result['OverallAchiever'] = DAO::getResultset($link, "SELECT count(*),GROUP_CONCAT(tr_id) FROM success_rates WHERE ((expected = $year AND actual<= $year) OR (expected <= $year AND actual = $year)) AND p_prog_status = 1 and outcome = 1 $where;");
            $result['TimelyAchiever'] = DAO::getResultset($link, "SELECT count(*),GROUP_CONCAT(tr_id) FROM success_rates WHERE expected = $year AND p_prog_status = 1 and DATEDIFF(actual_end_date, planned_end_date)<=90  $where;");
            $result['TimelyLeaver'] = DAO::getResultset($link, "SELECT count(*),GROUP_CONCAT(tr_id) FROM success_rates WHERE expected = $year and actual_end_date is not null $where;");
        }
        else
        {
            $result['OverallLeaver'] = DAO::getResultset($link, "SELECT count(*),GROUP_CONCAT(tr_id) FROM success_rates_bcs WHERE ((expected = $year AND actual<= $year) OR (expected <= $year AND actual = $year))  $where;");
            $result['OverallAchiever'] = DAO::getResultset($link, "SELECT count(*),GROUP_CONCAT(tr_id) FROM success_rates_bcs WHERE ((expected = $year AND actual<= $year) OR (expected <= $year AND actual = $year)) AND p_prog_status = 1 $where;");
            $result['TimelyAchiever'] = DAO::getResultset($link, "SELECT count(*),GROUP_CONCAT(tr_id) FROM success_rates_bcs WHERE expected = $year AND p_prog_status = 1 and DATEDIFF(actual_end_date, planned_end_date)<=90  $where;");
            $result['TimelyLeaver'] = DAO::getResultset($link, "SELECT count(*),GROUP_CONCAT(tr_id) FROM success_rates_bcs WHERE expected = $year and actual_end_date is not null $where;");
        }

        return $result;
    }

    public function getRetention($link, $filters = Array())
    {
        DAO::execute($link, "SET SESSION group_concat_max_len = 10000000;");
        $where = '';
        foreach($filters as $key => $value)
        {
            $value = addslashes($value);
            if($key=='year')
                $year = $value;
            elseif($key=='ssa')
                $where .= " and concat(ssa1,'<br>',ssa2)='$value'";
            elseif($key=='programme_type' && $value=='Apprenticeship')
                $where .= " and " . $key . " = '$value'";
            elseif($key=='programme_type' && $value=='Standard')
                $where .= " and StdCode is not null and FworkCode is null";
            elseif($key=='programme_type' && $value=='Education')
                $where .= " and " . $key . " != 'Apprenticeship'";
            elseif($key=='age_band' && $value=='19+')
                $where .= " and (age_band = '19-23' OR age_band = '24+')";
            elseif($key=='learner_type' && $value!='All learner type')
                $where .= " and learner_type = '$value'";
            elseif($key=='employer_type' && $value!='All employer type')
                $where .= " and employer_type = '$value'";
            else
                $where .= " and " . $key . " = '$value'";
        }

        if($this->level!='' and $this->level!='All level')
        {
            $where .= " and programme_type='Apprenticeship' and level = '" . $this->level . "'";
        }

        $result = Array();
        if(true)
        {
            $end_year = ($year+1) . "-07-31";
            $result['OverallLeaver'] = DAO::getResultset($link, "SELECT count(*),GROUP_CONCAT(tr_id) FROM success_rates WHERE ((expected = $year AND actual<= $year) OR (expected <= $year AND actual = $year) OR (actual_end_date IS NULL AND start_date<='$end_year'))  $where;");
            $result['OverallAchiever'] = DAO::getResultset($link, "SELECT count(*),GROUP_CONCAT(tr_id) FROM success_rates WHERE ((expected = $year AND actual<= $year) OR (expected <= $year AND actual = $year) OR (actual_end_date IS NULL AND start_date<='$end_year')) AND p_prog_status in (0,1)  $where;");
            $result['TimelyAchiever'] = DAO::getResultset($link, "SELECT count(*),GROUP_CONCAT(tr_id) FROM success_rates WHERE expected = $year AND p_prog_status = 1 and DATEDIFF(actual_end_date, planned_end_date)<=90  $where;");
            $result['TimelyLeaver'] = DAO::getResultset($link, "SELECT count(*),GROUP_CONCAT(tr_id) FROM success_rates WHERE expected = $year and actual_end_date is not null $where;");
        }
        else
        {
            $result['OverallLeaver'] = DAO::getResultset($link, "SELECT count(*),GROUP_CONCAT(tr_id) FROM success_rates_bcs WHERE ((expected = $year AND actual<= $year) OR (expected <= $year AND actual = $year))  $where;");
            $result['OverallAchiever'] = DAO::getResultset($link, "SELECT count(*),GROUP_CONCAT(tr_id) FROM success_rates_bcs WHERE ((expected = $year AND actual<= $year) OR (expected <= $year AND actual = $year)) AND p_prog_status = 1 $where;");
            $result['TimelyAchiever'] = DAO::getResultset($link, "SELECT count(*),GROUP_CONCAT(tr_id) FROM success_rates_bcs WHERE expected = $year AND p_prog_status = 1 and DATEDIFF(actual_end_date, planned_end_date)<=90  $where;");
            $result['TimelyLeaver'] = DAO::getResultset($link, "SELECT count(*),GROUP_CONCAT(tr_id) FROM success_rates_bcs WHERE expected = $year and actual_end_date is not null $where;");
        }

        return $result;
    }


    public $case_scenario = NULL;
    public $level = NULL;
    public $at_risk = NULL;

}