<?php
class qar implements IAction
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
        $best_case_filter = isset($_REQUEST['best_case'])?$_REQUEST['best_case']:'Actual';
        $panel = isset($_REQUEST['panel'])?$_REQUEST['panel']:'';
        $tab = isset($_REQUEST['tab'])?$_REQUEST['tab']:'summary';
        if($age_band_filter=='19 ')
            $age_band_filter =  '19+';
        if($age_band_filter=='24 ')
            $age_band_filter =  '24+';

        if(DB_NAME=='am_baltic')
        {
            $learner_type_filter = isset($_REQUEST['learner_type'])?$_REQUEST['learner_type']:'All learner type';
            $employer_type_filter = isset($_REQUEST['employer_type'])?$_REQUEST['employer_type']:'All employer type';
        }

        $this->case_scenario = $best_case_filter;
        $this->level = $level_filter;
        $this->at_risk = $at_risk_filter;

        /*DAO::execute($link, "drop table IF EXISTS success_rates");
        $this->createTempTable($link);
        DAO::execute($link, "insert into success_rates select * from success_rates2");*/

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

        DAO::execute($link, "UPDATE success_rates LEFT JOIN central.lookup_la_gor ON success_rates.local_authority = central.lookup_la_gor.local_authority SET success_rates.region = central.lookup_la_gor.government_region;");
        DAO::execute($link, "UPDATE success_rates set sfc = 'Business and Administration' where sfc = 'Business Administration'");
        DAO::execute($link, "UPDATE success_rates INNER JOIN tr ON success_rates.`tr_id` = tr.id SET success_rates.`at_risk` = tr.`at_risk`");

        DAO::execute($link, "DROP TEMPORARY TABLE IF EXISTS success_rates_bcs");
        DAO::execute($link, "CREATE TEMPORARY TABLE success_rates_bcs select * from success_rates");
        DAO::execute($link, "update success_rates_bcs set actual_end_date = planned_end_date, achievement_date = planned_end_date, actual = expected, hybrid = expected, p_prog_status = 1 where p_prog_status = 0");
        DAO::execute($link, "UPDATE success_rates_bcs INNER JOIN tr ON success_rates_bcs.`tr_id` = tr.id SET success_rates_bcs.`at_risk` = tr.`at_risk`");

        DAO::execute($link, "UPDATE qar_archive SET hybrid=GREATEST(expected,actual);");
        DAO::execute($link, "UPDATE qar_archive SET data_error = 0 WHERE data_error IS NULL;");
        DAO::execute($link, "UPDATE qar_archive SET programme_type = 'Study Programme' where tr_id in (select tr_id from ilr where contract_id in (select id from contracts where locate('Study Programme',title)))");
        DAO::execute($link, "UPDATE success_rates2 SET hybrid=GREATEST(expected,actual);");
        DAO::execute($link, "UPDATE success_rates2 SET data_error = 0 WHERE data_error IS NULL;");
        DAO::execute($link, "UPDATE success_rates2 SET programme_type = 'Study Programme' where tr_id in (select tr_id from ilr where contract_id in (select id from contracts where locate('Study Programme',title)))");



        if($panel == 'getOverallSummary')
        {
            $data = '<br><table class="table1"  style="margin-left:10px; margin-right:10px"><thead><tr><th colspan=2></th>';

            for($i = $start_index; $i<=sizeof($year)-1; $i++)
                $data.= "<th colspan=2 style='text-align: center'>" . Date::getFiscal($year[$i]) . "</th>";

            $data .= "</tr><tr><th colspan=2></th>";

            for($i = $start_index; $i<=sizeof($year)-1; $i++)
                $data.= "<th>Cohort</th><th>QAR</th>";

            $data .= "</tr></thead><tbody><tr><td colspan=2 style='background: #90ee90;'>Apprenticeships</td>";

            for($i = $start_index; $i<=sizeof($year)-1; $i++)
            {
                $filters = Array();
                $filters['year'] = $year[$i];
                $filters['programme_type'] = "Apprenticeship";
                if($age_band_filter!='All age_band')
                    $filters['age_band'] = $age_band_filter;
                if($learner_type_filter!='All learner type')
                    $filters['learner_type'] = $learner_type_filter;
                if($employer_type_filter!='All employer type')
                    $filters['employer_type'] = $employer_type_filter;
                $QAR = $this->getQAR($link,$filters);

                $data.= "<td><a href=javascript:expor('" . $QAR['OverallLeaver'][0][1] . "');>" . $QAR['OverallLeaver'][0][0] . "</td>";
                if($QAR['OverallLeaver'][0][0]>0)
                    $data.= "<td>" . sprintf("%.2f",$QAR['OverallAchiever'][0][0]/$QAR['OverallLeaver'][0][0]*100) . "%</td>";
                else
                    $data.= "<td>0%</td>";
            }

            $data .= "</tr></thead><tbody><tr><td colspan=2 style='background: #90ee90;'>Standard</td>";

            for($i = $start_index; $i<=sizeof($year)-1; $i++)
            {
                $filters = Array();
                $filters['year'] = $year[$i];
                $filters['programme_type'] = "Standard";
                if($age_band_filter!='All age_band')
                    $filters['age_band'] = $age_band_filter;
                if($learner_type_filter!='All learner type')
                    $filters['learner_type'] = $learner_type_filter;
                if($employer_type_filter!='All employer type')
                    $filters['employer_type'] = $employer_type_filter;
                $QAR = $this->getQAR($link,$filters);

                $data.= "<td><a href=javascript:expor('" . $QAR['OverallLeaver'][0][1] . "');>" . $QAR['OverallLeaver'][0][0] . "</td>";
                if($QAR['OverallLeaver'][0][0]>0)
                    $data.= "<td>" . sprintf("%.2f",$QAR['OverallAchiever'][0][0]/$QAR['OverallLeaver'][0][0]*100) . "%</td>";
                else
                    $data.= "<td>0%</td>";
            }

            $data .= "</tr></thead><tbody><tr><td colspan=2 style='background: #90ee90;'>Framework</td>";

            for($i = $start_index; $i<=sizeof($year)-1; $i++)
            {
                $filters = Array();
                $filters['year'] = $year[$i];
                $filters['programme_type'] = "Framework";
                if($age_band_filter!='All age_band')
                    $filters['age_band'] = $age_band_filter;
                if($learner_type_filter!='All learner type')
                    $filters['learner_type'] = $learner_type_filter;
                if($employer_type_filter!='All employer type')
                    $filters['employer_type'] = $employer_type_filter;
                $QAR = $this->getQAR($link,$filters);

                $data.= "<td><a href=javascript:expor('" . $QAR['OverallLeaver'][0][1] . "');>" . $QAR['OverallLeaver'][0][0] . "</td>";
                if($QAR['OverallLeaver'][0][0]>0)
                    $data.= "<td>" . sprintf("%.2f",$QAR['OverallAchiever'][0][0]/$QAR['OverallLeaver'][0][0]*100) . "%</td>";
                else
                    $data.= "<td>0%</td>";
            }

			$data .= "</tr><tr><td rowspan=2 style='background: #add8e6'>Education & Training</td><td style='background: #ffb6c1; width:150px'>16-18</td>";

            for($i = $start_index; $i<=sizeof($year)-1; $i++)
            {
                $filters = Array();
                $filters['year'] = $year[$i];
                $filters['programme_type'] = "Education";
                $filters['age_band'] = "16-18";
                if($learner_type_filter!='All learner type')
                    $filters['learner_type'] = $learner_type_filter;
                if($employer_type_filter!='All employer type')
                    $filters['employer_type'] = $employer_type_filter;
                $QAR = $this->getQAR($link,$filters);

                $data.= "<td><a href=javascript:expor('" . $QAR['OverallLeaver'][0][1] . "');>" . $QAR['OverallLeaver'][0][0] . "</td>";
                if($QAR['OverallLeaver'][0][0]>0)
                    $data.= "<td>" . sprintf("%.2f",$QAR['OverallAchiever'][0][0]/$QAR['OverallLeaver'][0][0]*100) . "%</td>";
                else
                    $data.= "<td>0%</td>";
            }

			$data .= "<tr><td style='background: #ffb6c1; width:150px'>19+</td>";

            for($i = $start_index; $i<=sizeof($year)-1; $i++)
            {
                $filters = Array();
                $filters['year'] = $year[$i];
                $filters['programme_type'] = "Education";
                $filters['age_band'] = "19+";
                if($learner_type_filter!='All learner type')
                    $filters['learner_type'] = $learner_type_filter;
                if($employer_type_filter!='All employer type')
                    $filters['employer_type'] = $employer_type_filter;
                $QAR = $this->getQAR($link,$filters);

                $data.= "<td><a href=javascript:expor('" . $QAR['OverallLeaver'][0][1] . "');>" . $QAR['OverallLeaver'][0][0] . "</td>";
                if($QAR['OverallLeaver'][0][0]>0)
                    $data.= "<td>" . sprintf("%.2f",$QAR['OverallAchiever'][0][0]/$QAR['OverallLeaver'][0][0]*100) . "%</td>";
                else
                    $data.= "<td>0%</td>";
            }

            $data .= "</tr></thead><tbody><tr><td colspan=2 style='background: #ae9ed4ff;'>Study Programme</td>";

            for($i = $start_index; $i<=sizeof($year)-1; $i++)
            {
                $filters = Array();
                $filters['year'] = $year[$i];
                $filters['programme_type'] = "Study Programme";
                if($learner_type_filter!='All learner type')
                    $filters['learner_type'] = $learner_type_filter;
                if($employer_type_filter!='All employer type')
                    $filters['employer_type'] = $employer_type_filter;
                $QAR = $this->getQAR($link,$filters);

                $data.= "<td><a href=javascript:expor('" . $QAR['OverallLeaver'][0][1] . "');>" . $QAR['OverallLeaver'][0][0] . "</td>";
                if($QAR['OverallLeaver'][0][0]>0)
                    $data.= "<td>" . sprintf("%.2f",$QAR['OverallAchiever'][0][0]/$QAR['OverallLeaver'][0][0]*100) . "%</td>";
                else
                    $data.= "<td>0%</td>";
            }


			$data .= "</tr></tbody></table><br>";

            echo $data;
            exit;
        }

        if($panel == 'getRetentionSummary')
        {
            $data = '<br><table class="table1"  style="margin-left:10px; margin-right:10px"><thead><tr><th colspan=2></th>';

            for($i = $start_index; $i<=sizeof($year)-1; $i++)
                $data.= "<th colspan=2 style='text-align: center'>" . Date::getFiscal($year[$i]) . "</th>";

            $data .= "</tr><tr><th colspan=2></th>";

            for($i = $start_index; $i<=sizeof($year)-1; $i++)
                $data.= "<th>Cohort</th><th>Ret:</th>";

            $data .= "</tr></thead><tbody><tr><td colspan=2 style='background: #90ee90;'>Apprenticeships</td>";

            for($i = $start_index; $i<=sizeof($year)-1; $i++)
            {
                $filters = Array();
                $filters['year'] = $year[$i];
                $filters['programme_type'] = "Apprenticeship";
                if($age_band_filter!='All age_band')
                    $filters['age_band'] = $age_band_filter;
                if($learner_type_filter!='All learner type')
                    $filters['learner_type'] = $learner_type_filter;
                if($employer_type_filter!='All employer type')
                    $filters['employer_type'] = $employer_type_filter;
                $QAR = $this->getRetention($link,$filters);

                $data.= "<td><a href=javascript:expor('" . $QAR['OverallLeaver'][0][1] . "');>" . $QAR['OverallLeaver'][0][0] . "</td>";
                if($QAR['OverallLeaver'][0][0]>0)
                    $data.= "<td>" . sprintf("%.2f",$QAR['OverallAchiever'][0][0]/$QAR['OverallLeaver'][0][0]*100) . "%</td>";
                else
                    $data.= "<td>0%</td>";
            }

            $data .= "</tr><tr><td rowspan=2 style='background: #add8e6'>Education & Training</td><td style='background: #ffb6c1; width:150px'>16-18</td>";

            for($i = $start_index; $i<=sizeof($year)-1; $i++)
            {
                $filters = Array();
                $filters['year'] = $year[$i];
                $filters['programme_type'] = "Education";
                $filters['age_band'] = "16-18";
                if($learner_type_filter!='All learner type')
                    $filters['learner_type'] = $learner_type_filter;
                if($employer_type_filter!='All employer type')
                    $filters['employer_type'] = $employer_type_filter;
                $QAR = $this->getRetention($link,$filters);

                $data.= "<td><a href=javascript:expor('" . $QAR['OverallLeaver'][0][1] . "');>" . $QAR['OverallLeaver'][0][0] . "</td>";
                if($QAR['OverallLeaver'][0][0]>0)
                    $data.= "<td>" . sprintf("%.2f",$QAR['OverallAchiever'][0][0]/$QAR['OverallLeaver'][0][0]*100) . "%</td>";
                else
                    $data.= "<td>0%</td>";
            }

            $data .= "<tr><td style='background: #ffb6c1; width:150px'>19+</td>";

            for($i = $start_index; $i<=sizeof($year)-1; $i++)
            {
                $filters = Array();
                $filters['year'] = $year[$i];
                $filters['programme_type'] = "Education";
                $filters['age_band'] = "19+";
                if($learner_type_filter!='All learner type')
                    $filters['learner_type'] = $learner_type_filter;
                if($employer_type_filter!='All employer type')
                    $filters['employer_type'] = $employer_type_filter;
                $QAR = $this->getRetention($link,$filters);

                $data.= "<td><a href=javascript:expor('" . $QAR['OverallLeaver'][0][1] . "');>" . $QAR['OverallLeaver'][0][0] . "</td>";
                if($QAR['OverallLeaver'][0][0]>0)
                    $data.= "<td>" . sprintf("%.2f",$QAR['OverallAchiever'][0][0]/$QAR['OverallLeaver'][0][0]*100) . "%</td>";
                else
                    $data.= "<td>0%</td>";
            }

            $data .= "</tr></tbody></table><br>";

            echo $data;
            exit;
        }

        if($panel == 'getTimelySummary')
        {
            $data = '<br><table class="table1"  style="margin-left:10px; margin-right:10px"><thead><tr><th colspan=2></th>';

            for($i = $start_index; $i<=sizeof($year)-1; $i++)
                $data.= "<th colspan=2 style='text-align: center'>" . Date::getFiscal($year[$i]) . "</th>";

            $data .= "</tr><tr><th colspan=2></th>";

            for($i = $start_index; $i<=sizeof($year)-1; $i++)
                $data.= "<th>Cohort</th><th>QAR</th>";

            $data .= "</tr></thead><tbody><tr><td colspan=2 style='background: #90ee90;'>Apprenticeships</td>";

            for($i = $start_index; $i<=sizeof($year)-1; $i++)
            {
                $filters = Array();
                $filters['year'] = $year[$i];
                $filters['programme_type'] = "Apprenticeship";
                if($age_band_filter!='All age_band')
                    $filters['age_band'] = $age_band_filter;
                if($learner_type_filter!='All learner type')
                    $filters['learner_type'] = $learner_type_filter;
                if($employer_type_filter!='All employer type')
                    $filters['employer_type'] = $employer_type_filter;
                $QAR = $this->getQAR($link,$filters);

                $data.= "<td><a href=javascript:expor('" . $QAR['TimelyLeaver'][0][1] . "');>" . $QAR['TimelyLeaver'][0][0] . "</td>";
                if($QAR['TimelyLeaver'][0][0]>0)
                    $data.= "<td>" . sprintf("%.2f",$QAR['TimelyAchiever'][0][0]/$QAR['TimelyLeaver'][0][0]*100) . "%</td>";
                else
                    $data.= "<td>0%</td>";
            }

            $data .= "</tr><tr><td rowspan=2 style='background: #add8e6'>Education & Training</td><td style='background: #ffb6c1; width:150px'>16-18</td>";

            for($i = $start_index; $i<=sizeof($year)-1; $i++)
            {
                $filters = Array();
                $filters['year'] = $year[$i];
                $filters['programme_type'] = "Education";
                $filters['age_band'] = "16-18";
                if($learner_type_filter!='All learner type')
                    $filters['learner_type'] = $learner_type_filter;
                if($employer_type_filter!='All employer type')
                    $filters['employer_type'] = $employer_type_filter;
                $QAR = $this->getQAR($link,$filters);

                $data.= "<td><a href=javascript:expor('"  . $QAR['TimelyLeaver'][0][1] . "');>" . $QAR['TimelyLeaver'][0][0] . "</td>";
                if($QAR['TimelyLeaver'][0][0]>0)
                    $data.= "<td>" . sprintf("%.2f",$QAR['TimelyAchiever'][0][0]/$QAR['TimelyLeaver'][0][0]*100) . "%</td>";
                else
                    $data.= "<td>0%</td>";
            }

            $data .= "<tr><td style='background: #ffb6c1; width:150px'>19+</td>";

            for($i = $start_index; $i<=sizeof($year)-1; $i++)
            {
                $filters = Array();
                $filters['year'] = $year[$i];
                $filters['programme_type'] = "Education";
                $filters['age_band'] = "19+";
                if($learner_type_filter!='All learner type')
                    $filters['learner_type'] = $learner_type_filter;
                if($employer_type_filter!='All employer type')
                    $filters['employer_type'] = $employer_type_filter;
                $QAR = $this->getQAR($link,$filters);

                $data.= "<td><a href=javascript:expor('" . $QAR['TimelyLeaver'][0][1] . "');>" . $QAR['TimelyLeaver'][0][0] . "</td>";
                if($QAR['TimelyLeaver'][0][0]>0)
                    $data.= "<td>" . sprintf("%.2f",$QAR['TimelyAchiever'][0][0]/$QAR['TimelyLeaver'][0][0]*100) . "%</td>";
                else
                    $data.= "<td>0%</td>";
            }

            $data .= "</tr></tbody></table><br>";

            echo $data;
            exit;
        }

        if($panel == 'getLineChartOverallTrend')
        {
            $overall_trend = array();
            $overall_trend[0]['Years'] = $year;
            foreach($year AS $y)
            {
                $filters = Array();
                $filters['year'] = $y;
                $filters['programme_type'] = "Apprenticeship";
                if($age_band_filter!='All age_band')
                    $filters['age_band'] = $age_band_filter;
                if($learner_type_filter!='All learner type')
                    $filters['learner_type'] = $learner_type_filter;
                if($employer_type_filter!='All employer type')
                    $filters['employer_type'] = $employer_type_filter;
                $QAR = $this->getQAR($link,$filters);
                if($QAR['OverallLeaver'][0][0]>0)
                    $overall_trend[1][] = (sprintf("%.2f",$QAR['OverallAchiever'][0][0]/$QAR['OverallLeaver'][0][0]*100));
                else
                    $overall_trend[1][] = 0;
            }
            foreach($year AS $y)
            {
                $filters = Array();
                $filters['year'] = $y;
                $filters['programme_type'] = "Education";
                $filters['age_band'] = "16-18";
                if($learner_type_filter!='All learner type')
                    $filters['learner_type'] = $learner_type_filter;
                if($employer_type_filter!='All employer type')
                    $filters['employer_type'] = $employer_type_filter;
                $QAR = $this->getQAR($link,$filters);
                if($QAR['OverallLeaver'][0][0]>0)
                    $overall_trend[2][] = (sprintf("%.2f",$QAR['OverallAchiever'][0][0]/$QAR['OverallLeaver'][0][0]*100));
                else
                    $overall_trend[2][] = 0;
            }
            foreach($year AS $y)
            {
                $filters = Array();
                $filters['year'] = $y;
                $filters['programme_type'] = "Education";
                $filters['age_band'] = "19+";
                if($learner_type_filter!='All learner type')
                    $filters['learner_type'] = $learner_type_filter;
                if($employer_type_filter!='All employer type')
                    $filters['employer_type'] = $employer_type_filter;
                $QAR = $this->getQAR($link,$filters);
                if($QAR['OverallLeaver'][0][0]>0)
                    $overall_trend[3][] = (sprintf("%.2f",$QAR['OverallAchiever'][0][0]/$QAR['OverallLeaver'][0][0]*100));
                else
                    $overall_trend[3][] = 0;
            }
            echo(json_encode($overall_trend,JSON_NUMERIC_CHECK));
            exit;
        }

        if($panel == 'getLineChartOverallTrend')
        {
            $overall_trend = array();
            $overall_trend[0]['Years'] = $year;
            foreach($year AS $y)
            {
                $filters = Array();
                $filters['year'] = $y;
                $filters['programme_type'] = "Apprenticeship";
                if($age_band_filter!='All age_band')
                    $filters['age_band'] = $age_band_filter;
                if($learner_type_filter!='All learner type')
                    $filters['learner_type'] = $learner_type_filter;
                if($employer_type_filter!='All employer type')
                    $filters['employer_type'] = $employer_type_filter;
                $QAR = $this->getQAR($link,$filters);
                if($QAR['OverallLeaver'][0][0]>0)
                    $overall_trend[1][] = (sprintf("%.2f",$QAR['OverallAchiever'][0][0]/$QAR['OverallLeaver'][0][0]*100));
                else
                    $overall_trend[1][] = 0;
            }
            foreach($year AS $y)
            {
                $filters = Array();
                $filters['year'] = $y;
                $filters['programme_type'] = "Education";
                $filters['age_band'] = "16-18";
                if($learner_type_filter!='All learner type')
                    $filters['learner_type'] = $learner_type_filter;
                if($employer_type_filter!='All employer type')
                    $filters['employer_type'] = $employer_type_filter;
                $QAR = $this->getQAR($link,$filters);
                if($QAR['OverallLeaver'][0][0]>0)
                    $overall_trend[2][] = (sprintf("%.2f",$QAR['OverallAchiever'][0][0]/$QAR['OverallLeaver'][0][0]*100));
                else
                    $overall_trend[2][] = 0;
            }
            foreach($year AS $y)
            {
                $filters = Array();
                $filters['year'] = $y;
                $filters['programme_type'] = "Education";
                $filters['age_band'] = "19+";
                if($learner_type_filter!='All learner type')
                    $filters['learner_type'] = $learner_type_filter;
                if($employer_type_filter!='All employer type')
                    $filters['employer_type'] = $employer_type_filter;
                $QAR = $this->getQAR($link,$filters);
                if($QAR['OverallLeaver'][0][0]>0)
                    $overall_trend[3][] = (sprintf("%.2f",$QAR['OverallAchiever'][0][0]/$QAR['OverallLeaver'][0][0]*100));
                else
                    $overall_trend[3][] = 0;
            }
            echo(json_encode($overall_trend,JSON_NUMERIC_CHECK));
            exit;
        }


        if($panel == 'getLineChartTimelyTrend')
        {
            $timely_trend = array();
            $timely_trend[0]['Years'] = $year;
            foreach($year AS $y)
            {
                $filters = Array();
                $filters['year'] = $y;
                $filters['programme_type'] = "Apprenticeship";
                if($age_band_filter!='All age_band')
                    $filters['age_band'] = $age_band_filter;
                if($learner_type_filter!='All learner type')
                    $filters['learner_type'] = $learner_type_filter;
                if($employer_type_filter!='All employer type')
                    $filters['employer_type'] = $employer_type_filter;
                $QAR = $this->getQAR($link,$filters);
                if($QAR['TimelyLeaver'][0][0]>0)
                    $timely_trend[1][] = (sprintf("%.2f",$QAR['TimelyAchiever'][0][0]/$QAR['TimelyLeaver'][0][0]*100));
                else
                    $timely_trend[1][] = 0;
            }
            foreach($year AS $y)
            {
                $filters = Array();
                $filters['year'] = $y;
                $filters['programme_type'] = "Education";
                $filters['age_band'] = "16-18";
                if($learner_type_filter!='All learner type')
                    $filters['learner_type'] = $learner_type_filter;
                if($employer_type_filter!='All employer type')
                    $filters['employer_type'] = $employer_type_filter;
                $QAR = $this->getQAR($link,$filters);
                if($QAR['TimelyLeaver'][0][0]>0)
                    $timely_trend[2][] = (sprintf("%.2f",$QAR['TimelyAchiever'][0][0]/$QAR['TimelyLeaver'][0][0]*100));
                else
                    $timely_trend[2][] = 0;
            }
            foreach($year AS $y)
            {
                $filters = Array();
                $filters['year'] = $y;
                $filters['programme_type'] = "Education";
                $filters['age_band'] = "19+";
                if($learner_type_filter!='All learner type')
                    $filters['learner_type'] = $learner_type_filter;
                if($employer_type_filter!='All employer type')
                    $filters['employer_type'] = $employer_type_filter;
                $QAR = $this->getQAR($link,$filters);
                if($QAR['TimelyLeaver'][0][0]>0)
                    $timely_trend[3][] = (sprintf("%.2f",$QAR['TimelyAchiever'][0][0]/$QAR['TimelyLeaver'][0][0]*100));
                else
                    $timely_trend[3][] = 0;
            }
            echo(json_encode($timely_trend,JSON_NUMERIC_CHECK));
            exit;
        }
        if($panel == 'LearnerByGenderTable')
        {
            $filters = Array();
            if($age_band_filter!='All age_band')
                $filters['age_band'] = $age_band_filter;
            if($learner_type_filter!='All learner type')
                $filters['learner_type'] = $learner_type_filter;
            if($employer_type_filter!='All employer type')
                $filters['employer_type'] = $employer_type_filter;
            $data = $this->getTable($link,$year,"gender",$filters,$qar_type_filter);
            echo $data;
            exit;
        }
        if($panel == 'LearnerByAgeBandTable')
        {
            $filters = Array();
            if($age_band_filter!='All age_band')
                $filters['age_band'] = $age_band_filter;
            if($learner_type_filter!='All learner type')
                $filters['learner_type'] = $learner_type_filter;
            if($employer_type_filter!='All employer type')
                $filters['employer_type'] = $employer_type_filter;
            $data = $this->getTable($link,$year,"age_band",$filters,$qar_type_filter);
            echo $data;
            exit;
        }
        if($panel == 'LearnerByLLDDTable')
        {
            $filters = Array();
            if($age_band_filter!='All age_band')
                $filters['age_band'] = $age_band_filter;
            if($learner_type_filter!='All learner type')
                $filters['learner_type'] = $learner_type_filter;
            if($employer_type_filter!='All employer type')
                $filters['employer_type'] = $employer_type_filter;
            $data = $this->getTable($link,$year,"lldd",$filters,$qar_type_filter);
            echo $data;
            exit;
        }
        if($panel == 'LearnerByEthnicityTable')
        {
            $filters = Array();
            if($age_band_filter!='All age_band')
                $filters['age_band'] = $age_band_filter;
            if($learner_type_filter!='All learner type')
                $filters['learner_type'] = $learner_type_filter;
            if($employer_type_filter!='All employer type')
                $filters['employer_type'] = $employer_type_filter;
            $data = $this->getTable($link,$year,"ethnicity",$filters,$qar_type_filter);
            echo $data;
            exit;
        }
        if($panel == 'LearnerBySSATable')
        {
            $filters = Array();
            if($age_band_filter!='All age_band')
                $filters['age_band'] = $age_band_filter;
            if($learner_type_filter!='All learner type')
                $filters['learner_type'] = $learner_type_filter;
            if($employer_type_filter!='All employer type')
                $filters['employer_type'] = $employer_type_filter;
            $data = $this->getTable($link,$year,"ssa1",$filters,$qar_type_filter);
            echo $data;
            exit;
        }

        if($panel == 'LearnerBySSAChartApp')
        {
            $filters = Array();
            if($age_band_filter!='All age_band')
                $filters['age_band'] = $age_band_filter;
            if($learner_type_filter!='All learner type')
                $filters['learner_type'] = $learner_type_filter;
            if($employer_type_filter!='All employer type')
                $filters['employer_type'] = $employer_type_filter;
            $data = $this->getChart($link,$year,"ssa1",$filters,$qar_type_filter,"Apprenticeship");
            echo $data;
            exit;
        }

        if($panel == 'LearnerBySSAChartEducation')
        {
            $filters = Array();
            if($age_band_filter!='All age_band')
                $filters['age_band'] = $age_band_filter;
            if($learner_type_filter!='All learner type')
                $filters['learner_type'] = $learner_type_filter;
            if($employer_type_filter!='All employer type')
                $filters['employer_type'] = $employer_type_filter;
            $data = $this->getChart($link,$year,"ssa1",$filters,$qar_type_filter,"Education");
            echo $data;
            exit;
        }

        if($panel == 'LearnerByFrameworkTable')
        {
            $filters = Array();
            if($age_band_filter!='All age_band')
                $filters['age_band'] = $age_band_filter;
            if($learner_type_filter!='All learner type')
                $filters['learner_type'] = $learner_type_filter;
            if($employer_type_filter!='All employer type')
                $filters['employer_type'] = $employer_type_filter;
            $data = $this->getTable($link,$year,"sfc",$filters,$qar_type_filter);
            echo $data;
            exit;
        }

        if($panel == 'LearnerByFrameworkChartApp')
        {
            $filters = Array();
            if($age_band_filter!='All age_band')
                $filters['age_band'] = $age_band_filter;
            if($learner_type_filter!='All learner type')
                $filters['learner_type'] = $learner_type_filter;
            if($employer_type_filter!='All employer type')
                $filters['employer_type'] = $employer_type_filter;
            $data = $this->getChart($link,$year,"sfc",$filters,$qar_type_filter,"Apprenticeship");
            echo $data;
            exit;
        }

        if($panel == 'LearnerByAssessorTable')
        {
            $filters = Array();
            if($age_band_filter!='All age_band')
                $filters['age_band'] = $age_band_filter;
            if($learner_type_filter!='All learner type')
                $filters['learner_type'] = $learner_type_filter;
            if($employer_type_filter!='All employer type')
                $filters['employer_type'] = $employer_type_filter;
            $data = $this->getTable($link,$year,"assessor",$filters,$qar_type_filter);
            echo $data;
            exit;
        }

        if($panel == 'LearnerByAssessorApp')
        {
            $filters = Array();
            if($age_band_filter!='All age_band')
                $filters['age_band'] = $age_band_filter;
            if($learner_type_filter!='All learner type')
                $filters['learner_type'] = $learner_type_filter;
            if($employer_type_filter!='All employer type')
                $filters['employer_type'] = $employer_type_filter;
            $data = $this->getChart($link,$year,"assessor",$filters,$qar_type_filter,"Apprenticeship");
            echo $data;
            exit;
        }

        if($panel == 'LearnerByAssessorEducation')
        {
            $filters = Array();
            if($age_band_filter!='All age_band')
                $filters['age_band'] = $age_band_filter;
            if($learner_type_filter!='All learner type')
                $filters['learner_type'] = $learner_type_filter;
            if($employer_type_filter!='All employer type')
                $filters['employer_type'] = $employer_type_filter;
            $data = $this->getChart($link,$year,"assessor",$filters,$qar_type_filter,"Education");
            echo $data;
            exit;
        }

        if($panel == 'LearnerByLevelTable')
        {
            $filters = Array();
            if($age_band_filter!='All age_band')
                $filters['age_band'] = $age_band_filter;
            if($learner_type_filter!='All learner type')
                $filters['learner_type'] = $learner_type_filter;
            if($employer_type_filter!='All employer type')
                $filters['employer_type'] = $employer_type_filter;
            $data = $this->getTable($link,$year,"level",$filters,$qar_type_filter);
            echo $data;
            exit;
        }

        if($panel == 'LearnerByLevelApp')
        {
            $filters = Array();
            if($age_band_filter!='All age_band')
                $filters['age_band'] = $age_band_filter;
            if($learner_type_filter!='All learner type')
                $filters['learner_type'] = $learner_type_filter;
            if($employer_type_filter!='All employer type')
                $filters['employer_type'] = $employer_type_filter;
            $data = $this->getChart($link,$year,"level",$filters,$qar_type_filter,"Apprenticeship");
            echo $data;
            exit;
        }

        if($panel == 'LearnerByLevelEducation')
        {
            $filters = Array();
            if($age_band_filter!='All age_band')
                $filters['age_band'] = $age_band_filter;
            if($learner_type_filter!='All learner type')
                $filters['learner_type'] = $learner_type_filter;
            if($employer_type_filter!='All employer type')
                $filters['employer_type'] = $employer_type_filter;
            $data = $this->getChart($link,$year,"level",$filters,$qar_type_filter,"Education");
            echo $data;
            exit;
        }

        if($panel == 'RetentionByLevelTable')
        {
            $filters = Array();
            if($age_band_filter!='All age_band')
                $filters['age_band'] = $age_band_filter;
            if($learner_type_filter!='All learner type')
                $filters['learner_type'] = $learner_type_filter;
            if($employer_type_filter!='All employer type')
                $filters['employer_type'] = $employer_type_filter;
            $data = $this->getRetentionTable($link,$year,"level",$filters,$qar_type_filter);
            echo $data;
            exit;
        }

        if($panel == 'leavers')
        {
            $query = "SELECT hybrid AS `year` , COUNT(hybrid) AS leavers FROM success_rates WHERE p_prog_status NOT IN (0,1) AND programme_type = 'Apprenticeship' AND hybrid IS NOT NULL AND hybrid != 0 GROUP BY hybrid";
            $leavers = DAO::getResultset($link, $query);
            $result = Array();
            $n= Array();
            $c= Array();
            $cohort=Array();
            foreach($leavers as $leaver)
            {
                $n[] = $leaver[1];
                $c[] = $leaver[0];
                $year = $leaver[0];
                $coh = DAO::getSingleValue($link, "SELECT COUNT(*) FROM success_rates WHERE ((expected = '$year' AND actual<= '$year') OR (expected <= '$year' AND actual = '$year'))  AND programme_type = 'Apprenticeship'");
                $cohort[] = $coh;
            }
            $series = Array();
            $series['name'] = "Leavers";
            $series['data'] = $c;
            array_push($result,$series);
            $series['name'] = "Leavers";
            $series['data'] = $cohort;
            array_push($result,$series);
            $series['name'] = "Withdrawn";
            $series['data'] = $n;
            array_push($result,$series);
            echo(json_encode($result, JSON_NUMERIC_CHECK));
            exit;
        }

        if($panel == 'leaversbytrend')
        {
            $query = "SELECT hybrid AS `year` , COUNT(hybrid) AS leavers FROM success_rates WHERE p_prog_status NOT IN (0,1) AND programme_type = 'Apprenticeship' AND hybrid IS NOT NULL AND hybrid != 0 GROUP BY hybrid";
            $leavers = DAO::getResultset($link, $query);
            $result = Array();
            $trend= Array();
            $c= Array();
            foreach($leavers as $leaver)
            {
                $c[] = $leaver[0];
                $year = $leaver[0];
                $coh = DAO::getSingleValue($link, "SELECT COUNT(*) FROM success_rates WHERE ((expected = '$year' AND actual<= '$year') OR (expected <= '$year' AND actual = '$year'))  AND programme_type = 'Apprenticeship'");
                if($leaver[1]==0)
                    $leaver[1]=1;
                $trend[] = round(($leaver[1]/$coh*100),2);
            }
            $series = Array();
            $series['name'] = "Leavers";
            $series['data'] = $c;
            array_push($result,$series);
            $series['name'] = "Percentage of withdrawn learners against leavers (Hybrid end-year)";
            $series['data'] = $trend;
            array_push($result,$series);
            echo(json_encode($result, JSON_NUMERIC_CHECK));
            exit;
        }

        if($panel == 'leaversbytrendactual')
        {
            $query = "SELECT actual AS `year` , COUNT(actual) AS leavers FROM success_rates WHERE p_prog_status NOT IN (0,1) AND programme_type = 'Apprenticeship' AND actual IS NOT NULL AND actual != 0 GROUP BY actual";
            $leavers = DAO::getResultset($link, $query);
            $result = Array();
            $trend= Array();
            $c= Array();
            foreach($leavers as $leaver)
            {
                $c[] = $leaver[0];
                $year = $leaver[0];
                $coh = DAO::getSingleValue($link, "SELECT COUNT(*) FROM success_rates WHERE actual = '$year'  AND programme_type = 'Apprenticeship'");
                if($leaver[1]==0)
                    $leaver[1]=1;
                $trend[] = round(($leaver[1]/$coh*100),2);
            }
            $series = Array();
            $series['name'] = "Leavers";
            $series['data'] = $c;
            array_push($result,$series);
            $series['name'] = "Percentage of withdrawn learners against leavers (year learning ended)";
            $series['data'] = $trend;
            array_push($result,$series);
            echo(json_encode($result, JSON_NUMERIC_CHECK));
            exit;
        }

        if($panel == 'leaversbytrendonprogramme')
        {
            $query = "SELECT actual AS `year` , COUNT(actual) AS leavers FROM success_rates WHERE p_prog_status NOT IN (0,1) AND programme_type = 'Apprenticeship' AND actual IS NOT NULL AND actual != 0 GROUP BY actual";
            $leavers = DAO::getResultset($link, $query);
            $result = Array();
            $trend= Array();
            $c= Array();
            foreach($leavers as $leaver)
            {
                $c[] = $leaver[0];
                $year = $leaver[0];
                $year1 = $year+1;
                $start_date = $year."-08-01";
                $end_date = $year1."-07-31";
                $coh = DAO::getSingleValue($link, "SELECT COUNT(*) FROM success_rates WHERE start_date >= '$start_date' and (actual_end_date is null or actual_end_date<= '$end_date') AND programme_type = 'Apprenticeship'");
                if($leaver[1]==0)
                    $leaver[1]=1;
                $trend[] = round(($leaver[1]/$coh*100),2);
            }
            $series = Array();
            $series['name'] = "Leavers";
            $series['data'] = $c;
            array_push($result,$series);
            $series['name'] = "Percentage of withdrawn learners against learners on programme (year learning ended)";
            $series['data'] = $trend;
            array_push($result,$series);
            echo(json_encode($result, JSON_NUMERIC_CHECK));
            exit;
        }

        if($panel == 'leaversbyreason')
        {
            $query = "SELECT hybrid, COUNT(CASE WHEN data_error>0 THEN data_error ELSE NULL END) AS data_error, COUNT(CASE WHEN data_error<>1 THEN 1 ELSE NULL END) AS genuine FROM success_rates WHERE p_prog_status NOT IN (0,1) AND programme_type = 'Apprenticeship'  GROUP BY hybrid";
            $leavers = DAO::getResultset($link, $query);
            $result = Array();
            $year= Array();
            $error= Array();
            $genuine= Array();
            foreach($leavers as $leaver)
            {
                if($leaver[0]=='')
                       continue;
                $year[] = $leaver[0];
                $error[] = $leaver[1];
                $genuine[] = $leaver[2];
            }
            $series = Array();
            $series['name'] = "Leavers";
            $series['data'] = $year;
            array_push($result,$series);
            $series['name'] = "Data Error";
            $series['data'] = $error;
            array_push($result,$series);
            $series['name'] = "Withdrawn";
            $series['data'] = $genuine;
            array_push($result,$series);
            echo(json_encode($result, JSON_NUMERIC_CHECK));
            exit;
        }

        if($panel == 'leaversbyimpact')
        {
            $query = "SELECT DISTINCT hybrid AS Impact_Year FROM success_rates WHERE p_prog_status NOT IN (0,1) AND programme_type = 'Apprenticeship' AND hybrid IS NOT NULL GROUP BY hybrid ;";
            $leavers = DAO::getResultset($link, $query);
            $result = Array();
            $year= Array();
            foreach($leavers as $leaver)
            {
                $year[] = $leaver[0];
            }
            $series = Array();
            $series['name'] = "Impact Year";
            $series['data'] = $year;
            array_push($result,$series);
            unset($series);
            $actual_query = "SELECT DISTINCT actual AS Impact_Year FROM success_rates WHERE p_prog_status NOT IN (0,1) AND programme_type = 'Apprenticeship' AND actual IS NOT NULL ORDER BY actual;";
            $actual_years = DAO::getResultset($link, $actual_query);
            foreach($actual_years as $actual_year)
            {
                $ay = $actual_year[0];
                $series['name'] = $ay;
                foreach($leavers as $leaver)
                {
                    $hy = $leaver[0];
                    $series['data'][] = DAO::getSingleValue($link, "select count(*) from success_rates where p_prog_status not in (0,1) and actual='$ay' and hybrid='$hy'");
                }
                array_push($result,$series);
                unset($series);
            }
            echo(json_encode($result, JSON_NUMERIC_CHECK));
            exit;
        }

        if($panel == 'leaversbyactual')
        {
            $query = "SELECT DISTINCT actual AS Impact_Year FROM success_rates WHERE p_prog_status NOT IN (0,1) AND programme_type = 'Apprenticeship' AND actual IS NOT NULL GROUP BY actual;";
            $actual_leavers = DAO::getResultset($link, $query);
            $result = Array();
            $year= Array();
            foreach($actual_leavers as $leaver)
            {
                $year[] = $leaver[0];
            }
            $series = Array();
            $series['name'] = "Actual Year";
            $series['data'] = $year;
            array_push($result,$series);
            unset($series);
            $hybrid_query = "SELECT DISTINCT hybrid AS Impact_Year FROM success_rates WHERE p_prog_status NOT IN (0,1) AND programme_type = 'Apprenticeship' AND hybrid IS NOT NULL ORDER BY hybrid;";
            $hybrid_years = DAO::getResultset($link, $hybrid_query);
            foreach($hybrid_years as $hybrid_year)
            {
                $hy = $hybrid_year[0];
                $series['name'] = $hy;
                foreach($actual_leavers as $actual_leaver)
                {
                    $ay = $actual_leaver[0];
                    $series['data'][] = DAO::getSingleValue($link, "select count(*) from success_rates where p_prog_status not in (0,1) and actual='$ay' and hybrid='$hy'");
                }
                array_push($result,$series);
                unset($series);
            }
            echo(json_encode($result, JSON_NUMERIC_CHECK));
            exit;
        }

        include_once('tpl_qar.php');

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
                $where .= " and " . $key . " != 'Apprenticeship' and " . $key . " != 'Study Programme'";
            elseif($key=='programme_type' && $value=='Study Programme')
                $where .= " and programme_type = 'Study Programme'";
            elseif($key=='age_band' && $value=='19+')
                $where .= " and (age_band = '19-23' OR age_band = '24+')";
            elseif($key=='learner_type' && $value!='All learner type')
                $where .= " and learner_type = '$value'";
            elseif($key=='employer_type' && $value!='All employer type')
                $where .= " and employer_type = '$value'";
            else
                $where .= " and " . $key . " = '$value'";


        }

           $where .= " and outcome!=8 ";


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

            $where .= " and outcome!=8 ";

        if($this->level!='' and $this->level!='All level')
        {
            $where .= " and programme_type='Apprenticeship' and level = '" . $this->level . "'";
        }

        $result = Array();
        if(true)
        {
            $end_year = ($year+1) . "-07-31";
            $result['OverallLeaver'] = DAO::getResultset($link, "SELECT count(*),GROUP_CONCAT(tr_id) FROM success_rates WHERE ((expected = $year AND actual<= $year) OR (expected <= $year AND actual = $year) OR (actual_end_date IS NULL AND start_date<='$end_year')) $where;");
            $result['OverallAchiever'] = DAO::getResultset($link, "SELECT count(*),GROUP_CONCAT(tr_id) FROM success_rates WHERE ((expected = $year AND actual<= $year) OR (expected <= $year AND actual = $year) OR (actual_end_date IS NULL AND start_date<='$end_year')) AND p_prog_status in (0,1)  $where;");
            $result['TimelyAchiever'] = DAO::getResultset($link, "SELECT count(*),GROUP_CONCAT(tr_id) FROM success_rates WHERE expected = $year AND p_prog_status = 1 and DATEDIFF(actual_end_date, planned_end_date)<=90  $where;");
            $result['TimelyLeaver'] = DAO::getResultset($link, "SELECT count(*),GROUP_CONCAT(tr_id) FROM success_rates WHERE expected = $year and actual_end_date is not null and outcome!=8 $where;");
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