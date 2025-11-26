<?php
class funding_reports implements IAction
{
    public $current_contract_year = null;
    public function execute(PDO $link)
    {
    	$_SESSION['bc']->index=0;
        $_SESSION['bc']->add($link, "do.php?_action=funding_reports", "Funding Reports");

        $refresh = isset($_REQUEST['refresh'])?$_REQUEST['refresh']:'0';
        if($refresh)
        {
            $contract_year = DAO::getSingleValue($link, "select max(contract_year) from contracts");
            $submission = DAO::getSingleValue($link, "select max(submission) from ilr where contract_id in (select id from contracts where contract_year = '$contract_year')");
            $sql = "select ilr.L03, ilr.ilr, ilr.tr_id from ilr inner join contracts on contracts.id = ilr.contract_id inner join tr on ilr.tr_id = tr.id where contract_year = '$contract_year' and submission = '$submission' and funded=1";
            $ilrs = DAO::getResultset($link, $sql);
            $rows = Array();
            $index = 0;
            foreach($ilrs as $ilr)
            {
                //Extract Values from ILR
                $ilrxml = XML::loadSimpleXML($ilr[1]);

                foreach($ilrxml->LearningDelivery AS $ld)
                {
                    $rows[$index]['TRID'] = $ilr[2];
                    $rows[$index]['LearnRefNumber'] = (string)$ilrxml->LearnRefNumber;
                    $rows[$index]['ULN'] = (string)$ilrxml->ULN;
                    $rows[$index]['FamilyName'] = (string)$ilrxml->FamilyName;
                    $rows[$index]['GivenNames'] = (string)$ilrxml->GivenNames;
                    $rows[$index]['DateOfBirth'] = (string)$ilrxml->DateOfBirth;
                    $rows[$index]['AimSeqNumber'] = (string)$ld->AimSeqNumber;
                    $rows[$index]['AimType'] = (string)$ld->AimType;
                    $rows[$index]['ProgType'] = (string)$ld->ProgType;
                    $rows[$index]['StdCode'] = (string)$ld->StdCode;
                    $rows[$index]['LearnAimRef'] = (string)$ld->LearnAimRef;
                    $rows[$index]['FundModel'] = (string)$ld->FundModel;
                    $rows[$index]['LearnStartDate'] = (string)$ld->LearnStartDate;
                    $rows[$index]['LearnPlanEndDate'] = (string)$ld->LearnPlanEndDate;
                    $rows[$index]['LearnActEndDate'] = (string)$ld->LearnActEndDate;
                    $rows[$index]['AchDate'] = (string)$ld->AchDate;
                    $rows[$index]['CompStatus'] = (string)$ld->CompStatus;
                    $rows[$index]['Outcome'] = (string)$ld->Outcome;
                    $rows[$index]['PlannedInstalments'] = 0;
                    $rows[$index]['PriceApplicable'] = 0;

                    if((string)$ld->AimType==1)
                    {
                        foreach($ld->TrailblazerApprenticeshipFinancialRecord as $funding)
                        {
                            if((string)$funding->TBFinType=="TNP" and (string)$funding->TBFinCode=="1")
                            {
                                $rows[$index]['TNP1Amount'] = (int)(string)$funding->TBFinAmount;
                                $rows[$index]['TNP1Date'] = (string)$funding->TBFinDate;
                            }    
                            if((string)$funding->TBFinType=="TNP" and (string)$funding->TBFinCode=="2")
                            {
                                $rows[$index]['TNP2Amount'] = (int)(string)$funding->TBFinAmount;
                            }    
                            if((string)$funding->TBFinType=="TNP" and (string)$funding->TBFinCode=="3")
                            {
                                $rows[$index]['TNP3Amount'] = (int)(string)$funding->TBFinAmount;
                                $rows[$index]['TNP3Date'] = (string)$funding->TBFinDate;
                            }    
                            if((string)$funding->TBFinType=="TNP" and (string)$funding->TBFinCode=="4")
                            {
                                $rows[$index]['TNP4Amount'] = (int)(string)$funding->TBFinAmount;
                            }    
                        }
                        $rows[$index]['PriceApplicable'] = 0;
                    }

                    $rows[$index]['CompletionElement'] = $rows[$index]['PriceApplicable']*.2;
                    $rows[$index]['FundingBandUpperLimit'] = 0;
                    $rows[$index]['AmountAboveFundingBandLimit'] = 0;
                    $rows[$index]['AmountRemaining'] = 0;
                    $rows[$index]['TotalEmployerContributionPreviousFundingYear'] = 0;
                    $rows[$index]['TotalEmployerContributionCurrentFundingYear'] = 0;
                    $rows[$index]['FundStart'] = 1;

                    // Funding Occupancy Periodised
                    $censusDates = $this->getLastDatesOfMonths((string)$ld->LearnStartDate, (string)$ld->LearnPlanEndDate);
                    
                    $index++;
                }
            }                
            DAO::execute($link, "truncate funding_occupancy");
            DAO::multipleRowInsert($link, 'funding_occupancy', $rows);
            DAO::execute($link, "update funding_occupancy set PlannedInstalments = TIMESTAMPDIFF(MONTH, LearnStartDate, LAST_DAY(LearnPlanEndDate));");
            DAO::execute($link, "update funding_occupancy set PlannedInstalments = PlannedInstalments + 1 WHERE LearnPlanEndDate = LAST_DAY(LearnPlanEndDate)");

            DAO::execute($link, "update funding_occupancy set InstalmentsThisYear = TIMESTAMPDIFF(MONTH, CONCAT('$contract_year','-08-01'), LAST_DAY(LearnPlanEndDate));");
            DAO::execute($link, "update funding_occupancy set FundStart = 0 WHERE TIMESTAMPDIFF(DAY, LearnStartDate, LEAST(LearnPlanEndDate,LearnActEndDate)) < 42");
            DAO::execute($link, "update funding_occupancy set AugOnProgramme = ((TNP1Amount+TNP2Amount) *.8 / PlannedInstalments) WHERE InstalmentsThisYear >= 1 AND LearnStartDate < '2024-09-01' and FundStart = 1");
        }
        
        //pre("Finished");

        $age_band_filter = isset($_REQUEST['age_band'])?$_REQUEST['age_band']:'All age_band';
        $qar_type_filter = isset($_REQUEST['qar_type'])?$_REQUEST['qar_type']:'Overall';
        $level_filter = isset($_REQUEST['level'])?$_REQUEST['level']:'All level';
        $best_case_filter = isset($_REQUEST['best_case'])?$_REQUEST['best_case']:'Actual';
        $panel = isset($_REQUEST['panel'])?$_REQUEST['panel']:'';
        $tab = isset($_REQUEST['tab'])?$_REQUEST['tab']:'summary';
        if($age_band_filter=='19 ')
            $age_band_filter =  '19+';
        if($age_band_filter=='24 ')
            $age_band_filter =  '24+';




        $periods = Array("Aug","Sep","Oct","Nov","Dec","Jan","Feb","Mar","Apr","May","Jun","Jul");
        $tfunding = $this->getFunding($link,"Trailblazer");
        $fm35funding = $this->getFunding($link,"FM35");
        $fm36funding = $this->getFunding($link,"FM36");
        $tfundingb = $this->getFunding($link,"Trailblazer","BC");
        $fm35fundingb = $this->getFunding($link,"FM35","BC");
        $fm36fundingb = $this->getFunding($link,"FM36","BC");
        $allocation = $this->getAllocation($link);
        $business_codes1 = DAO::getSingleColumn($link, "select distinct BC from fm36_funding");
        $business_codes2 = DAO::getSingleColumn($link, "select distinct BC from fm35_funding");
        $business_codes3 = DAO::getSingleColumn($link, "select distinct BC from trailblazer_funding");
        $business_codes = array_merge($business_codes1,$business_codes2,$business_codes3);
        $business_codes = array_unique($business_codes);

        $business_codes = DAO::getSingleColumn($link, "select title from contracts where contract_year = 2023 and active = 1");

        $allocations=Array();
        foreach($periods as $period)
            $allocations[] = $allocation[0][$period];

        $fundings = Array();
        foreach($periods as $period)
        {
            $ptotal = $tfunding[0][$period]+$fm35funding[0][$period]+$fm36funding[0][$period];
            $fundings[]=$ptotal;
        }


        $this->case_scenario = $best_case_filter;
        $this->level = $level_filter;

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


        if($panel == 'getOverallSummary')
        {
            $data = '<br><table class="table1"  style="margin-left:10px; margin-right:10px"><thead><tr><th colspan=2></th>';

            foreach($periods as $period)
                $data.= "<th style='text-align: center'>" . $period . "</th>";
            $data.= "<th style='text-align: center'>Total</th>";

            //$data .= "</tr></thead><tbody><tr><td colspan=2 style='background: #90ee90;'>Non-Apps</td>";
            $data .= "</tr></thead><tbody>";

            //foreach($periods as $period)
            //{
            //    $data.= "<td>0</td>";
            //}
            //$data.= "<td>0</td>";


            $data .= "<tr><td rowspan=2 style='background: #add8e6'>Funding</td>";
            /*$data .= "<td style='background: #ffb6c1; width:150px'>Apprenticeships</td>";
            $trailblazer_total = 0;
            foreach($periods as $period)
            {
                $data.= "<td style='text-align: center'>&pound;" . round($tfunding[0][$period]) . "</td>";
                $trailblazer_total+=$tfunding[0][$period];
            }
            $data.= "<td style='text-align: center'>&pound;" . round($trailblazer_total) . "</td>";*/

            $data .= "<td style='background: #ffb6c1; width:150px'>AEB</td>";
            $fm35_total = 0;
            foreach($periods as $period)
            {
                $data.= "<td style='text-align: center'>&pound;" . number_format(round($fm35funding[0][$period])) . "</td>";
                $fm35_total+=$fm35funding[0][$period];
            }
            $data.= "<td style='text-align: center'>&pound;" . number_format(round($fm35_total)) . "</td>";

            $data .= "<tr><td style='background: #ffb6c1; width:150px'>Apprenticeship</td>";
            $fm36_total=0;
            foreach($periods as $period)
            {
                $data.= "<td style='text-align: center'>&pound;" . number_format(round($fm36funding[0][$period])) . "</td>";
                $fm36_total+=$fm36funding[0][$period];
            }
            $data.= "<td style='text-align: center'>&pound;" . number_format(round($fm36_total)) . "</td>";

            $data .= "</tr><tr><td colspan=2 style='background: #ECAF'>Total</td>";
            $gt =0;
            foreach($periods as $period)
            {
                $ptotal = $tfunding[0][$period]+$fm35funding[0][$period]+$fm36funding[0][$period];
                $data.= "<td style='text-align: center'>&pound;" . number_format(round($ptotal)) . "</td>";
                $gt+=$ptotal;
            }
            $data.= "<td style='text-align: center'><b>&pound;" . number_format(round($gt)) . "</b></td>";


            $data .= "</tr></tbody></table><br>";

            echo $data;
            exit;
        }

        if($panel == 'getFundingByBusiness')
        {
            $data = '<br><table class="table1"  style="margin-left:10px; margin-right:10px"><thead><tr><th colspan=2></th>';

            foreach($periods as $period)
                $data.= "<th style='text-align: center'>" . $period . "</th>";
            $data.= "<th style='text-align: center'>Total</th>";
            $data .= "</tr></thead><tbody>";
            foreach($business_codes as $business_code)
            {
                $data .= "<tr><td colspan=2 style='background: #90ee90; text-align:left'>" . $business_code . "</td>";
                $p_total = 0;
                foreach($periods as $period)
                {
                    $bc_total = 0;
                    foreach($fm36fundingb as $f)
                        if($f['BC']==$business_code)
                            $bc_total+=$f[$period];
                    foreach($fm35fundingb as $f)
                        if($f['BC']==$business_code)
                            $bc_total+=$f[$period];
                    foreach($tfundingb as $f)
                        if($f['BC']==$business_code)
                            $bc_total+=$f[$period];
                    $p_total+=$bc_total;
                    $data.="<td>&pound;" . number_format(round($bc_total)) . "</td>";
                }
                $data.="<td>&pound;" . number_format(round($p_total)) . "</td>";
                $data.="</tr>";
            }
            $data .= "</tr></tbody></table><br>";

            echo $data;
            exit;
        }

        if($panel == 'MonthlyUtilisationTable')
        {
            $data = '<br><table class="table1"  style="margin-left:10px; margin-right:10px"><thead><tr><th colspan=2></th>';

            foreach($periods as $period)
                $data.= "<th style='text-align: center'>" . $period . "</th>";
            $data.= "<th style='text-align: center'>Total</th>";

            $data .= "</tr></thead><tbody>";
            $data .= "<tr><td colspan=2 style='background: #90ee90;'>Total Funding</td>";
            $total_funding =0;
            foreach($periods as $period)
            {
                $ptotal = $fm35funding[0][$period]+$fm36funding[0][$period];
                $data.= "<td style='text-align: center'>&pound;" . number_format(round($ptotal)) . "</td>";
                $total_funding+=$ptotal;
            }
            $data.= "<td style='text-align: center'><b>&pound;" . number_format(round($total_funding)) . "</b></td></tr>";

            $data .= "<tr><td colspan=2 style='background: #90ee90;'>Total Allocation</td>";
            $total_allocation =0;
            $allocations=Array();
            foreach($periods as $period)
            {
                $data.= "<td style='text-align: center'>&pound;" . number_format(round($allocation[0][$period])) . "</td>";
                $total_allocation+=$allocation[0][$period];
                $allocations[] = $allocation[0][$period];
            }
            $data.= "<td style='text-align: center'><b>&pound;" . number_format(round($total_allocation)) . "</b></td></tr>";

            $data .= "<tr><td colspan=2 style='background: #90ee90;'>Allocation utilised %</td>";
            foreach($periods as $period)
            {
                $ptotal = $fm35funding[0][$period]+$fm36funding[0][$period];
                if($allocation[0][$period]==0)
                    $data.= "<td style='text-align: center'>0%</td>";
                else
                    $data.= "<td style='text-align: center'>" . number_format(round($ptotal/$allocation[0][$period]*100)) . "%</td>";
            }
            $data.= "<td style='text-align: center'><b>" . number_format(round($total_funding/$total_allocation*100)) . "%</b></td></tr>";

            $data .= "<tr><td colspan=2 style='background: #90ee90;'>Remaining allocation</td>";
            foreach($periods as $period)
            {
                $ptotal = $tfunding[0][$period]+$fm35funding[0][$period]+$fm36funding[0][$period];
                $data.= "<td style='text-align: center'>&pound;" . number_format(round($allocation[0][$period]-$ptotal)) . "</td>";
            }
            $data.= "<td style='text-align: center'><b>&pound;" . number_format(round($total_allocation-$total_funding)) . "</b></td></tr>";

            echo $data;
            exit;
        }


        if($panel == 'MonthlyUtilisationAccumulatedTable')
        {
            $data = '<br><table class="table1"  style="margin-left:10px; margin-right:10px"><thead><tr><th colspan=2></th>';
            $funding_accumulated = Array();
            $allocation_accumulated = Array();
            foreach($periods as $period)
                $data.= "<th style='text-align: center'>" . $period . "</th>";

            $data .= "</tr></thead><tbody>";
            $data .= "<tr><td colspan=2 style='background: #90ee90;'>Total Funding</td>";
            $total_funding =0;
            foreach($periods as $period)
            {
                $ptotal = $tfunding[0][$period]+$fm35funding[0][$period]+$fm36funding[0][$period];
                $total_funding+=$ptotal;
                $data.= "<td style='text-align: center'>&pound;" . number_format(round($total_funding)) . "</td>";
                $funding_accumulated[] = $total_funding;
            }
            $data.= "</tr>";

            $data .= "<tr><td colspan=2 style='background: #90ee90;'>Total Allocation</td>";
            $total_allocation =0;
            $allocations=Array();
            foreach($periods as $period)
            {
                $total_allocation+=$allocation[0][$period];
                $allocations[] = $allocation[0][$period];
                $data.= "<td style='text-align: center'>&pound;" . number_format(round($total_allocation)) . "</td>";
                $allocation_accumulated[] = $total_allocation;
            }
            $data.= "</tr>";

            $data .= "<tr><td colspan=2 style='background: #90ee90;'>Allocation utilised %</td>";
            for($a=0; $a<=11; $a++)
            {
                $data.= "<td style='text-align: center'>" . number_format(round($funding_accumulated[$a]/$allocation_accumulated[$a]*100)) . "%</td>";
            }
            $data.= "</tr>";

            $data .= "<tr><td colspan=2 style='background: #90ee90;'>Remaining allocation</td>";
            for($a=0; $a<=11; $a++)
            {
                $data.= "<td style='text-align: center'>&pound;" . number_format(round($allocation_accumulated[$a]-$funding_accumulated[$a])) . "</td>";
            }

            $data.= "</tr>";

            echo $data;
            exit;
        }


        include_once('tpl_funding_reports.php');

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
            $value = addslashes((string)$value);
            if($key=='year')
                $year = $value;
            elseif($key=='ssa')
                $where .= " and concat(ssa1,'<br>',ssa2)='$value'";
            elseif($key=='programme_type' && $value=='Apprenticeship')
                $where .= " and " . $key . " = '$value'";
            elseif($key=='programme_type' && $value=='Education')
                $where .= " and " . $key . " != 'Apprenticeship'";
            elseif($key=='age_band' && $value=='19+')
                $where .= " and (age_band = '19-23' OR age_band = '24+')";
            else
                $where .= " and " . $key . " = '$value'";
        }

        if($this->level!='' and $this->level!='All level')
        {
            $where .= " and programme_type='Apprenticeship' and level = '" . $this->level . "'";
        }

        $result = Array();
        if($this->case_scenario=="Actual")
        {
            $result['OverallLeaver'] = DAO::getResultset($link, "SELECT count(*),GROUP_CONCAT(tr_id) FROM success_rates WHERE ((expected = $year AND actual<= $year) OR (expected <= $year AND actual = $year))  $where;");
            $result['OverallAchiever'] = DAO::getResultset($link, "SELECT count(*),GROUP_CONCAT(tr_id) FROM success_rates WHERE ((expected = $year AND actual<= $year) OR (expected <= $year AND actual = $year)) AND p_prog_status = 1 $where;");
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
            $value = addslashes((string)$value);
            if($key=='year')
                $year = $value;
            elseif($key=='ssa')
                $where .= " and concat(ssa1,'<br>',ssa2)='$value'";
            elseif($key=='programme_type' && $value=='Apprenticeship')
                $where .= " and " . $key . " = '$value'";
            elseif($key=='programme_type' && $value=='Education')
                $where .= " and " . $key . " != 'Apprenticeship'";
            elseif($key=='age_band' && $value=='19+')
                $where .= " and (age_band = '19-23' OR age_band = '24+')";
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

    public function getFunding($link, $FundLine, $BC = "")
    {
        if($BC!="")
        {
            if($FundLine=="Trailblazer")
                return DAO::getResultset($link, "SELECT BC, SUM(P1) AS Aug
            ,SUM(P2) AS Sep
            ,SUM(P3) AS Oct
            ,SUM(P4) AS Nov
            ,SUM(P5) AS `Dec`
            ,SUM(P6) AS Jan
            ,SUM(P7) AS Feb
            ,SUM(P8) AS Mar
            ,SUM(P9) AS Apr
            ,SUM(P10) AS May
            ,SUM(P11) AS Jun
            ,SUM(P12) AS Jul
            FROM trailblazer_funding WHERE AttributeName IN (\"AchPayment\",\"LearnSuppFundCash\",\"MathEngBalPayment\",\"MathEngOnProgPayment\",\"SmallBusPayment\",\"YoungAppPayment\") GROUP BY BC",DAO::FETCH_ASSOC);
            elseif($FundLine=="FM35")
                return DAO::getResultset($link, "SELECT BC, SUM(P1) AS Aug
            ,SUM(P2) AS Sep
            ,SUM(P3) AS Oct
            ,SUM(P4) AS Nov
            ,SUM(P5) AS `Dec`
            ,SUM(P6) AS Jan
            ,SUM(P7) AS Feb
            ,SUM(P8) AS Mar
            ,SUM(P9) AS Apr
            ,SUM(P10) AS May
            ,SUM(P11) AS Jun
            ,SUM(P12) AS Jul
            FROM fm35_funding WHERE AttributeName IN (\"AchievePayment\",\"BalancePayment\",\"EmpOutcomePay\",\"LearnSuppFundCash\",\"OnProgPayment\") GROUP BY BC",DAO::FETCH_ASSOC);
            elseif($FundLine=="FM36")
                return DAO::getResultset($link, "SELECT BC, SUM(P1) AS Aug
            ,SUM(P2) AS Sep
            ,SUM(P3) AS Oct
            ,SUM(P4) AS Nov
            ,SUM(P5) AS `Dec`
            ,SUM(P6) AS Jan
            ,SUM(P7) AS Feb
            ,SUM(P8) AS Mar
            ,SUM(P9) AS Apr
            ,SUM(P10) AS May
            ,SUM(P11) AS Jun
            ,SUM(P12) AS Jul
            FROM fm36_funding WHERE AttributeName IN (\"DisadvFirstPayment\",\"DisadvSecondPayment\",\"LDApplic1618FrameworkUpliftBalancingPayment\",\"LDApplic1618FrameworkUpliftBalancingPayment\",
            \"LDApplic1618FrameworkUpliftOnProgPayment\",\"LearnDelFirstEmp1618Pay\",\"LearnDelFirstProv1618Pay\",\"LearnDelSecondEmp1618Pay\",\"LearnDelSecondProv1618Pay\",
            \"LearnSuppFundCash\",\"MathEngBalPayment\",\"MathEngOnProgPayment\",\"ProgrammeAimBalPayment\",\"ProgrammeAimCompletionPayment\",\"ProgrammeAimOnProgPayment\") GROUP BY BC",DAO::FETCH_ASSOC);
            return null;
        }
        else
        {
            if($FundLine=="Trailblazer")
                return DAO::getResultset($link, "SELECT SUM(P1) AS Aug
            ,SUM(P2) AS Sep
            ,SUM(P3) AS Oct
            ,SUM(P4) AS Nov
            ,SUM(P5) AS `Dec`
            ,SUM(P6) AS Jan
            ,SUM(P7) AS Feb
            ,SUM(P8) AS Mar
            ,SUM(P9) AS Apr
            ,SUM(P10) AS May
            ,SUM(P11) AS Jun
            ,SUM(P12) AS Jul
            FROM trailblazer_funding WHERE AttributeName IN (\"AchPayment\",\"LearnSuppFundCash\",\"MathEngBalPayment\",\"MathEngOnProgPayment\",\"SmallBusPayment\",\"YoungAppPayment\")",DAO::FETCH_ASSOC);
            elseif($FundLine=="FM35")
                return DAO::getResultset($link, "SELECT SUM(P1) AS Aug
            ,SUM(P2) AS Sep
            ,SUM(P3) AS Oct
            ,SUM(P4) AS Nov
            ,SUM(P5) AS `Dec`
            ,SUM(P6) AS Jan
            ,SUM(P7) AS Feb
            ,SUM(P8) AS Mar
            ,SUM(P9) AS Apr
            ,SUM(P10) AS May
            ,SUM(P11) AS Jun
            ,SUM(P12) AS Jul
            FROM fm35_funding WHERE AttributeName IN (\"AchievePayment\",\"BalancePayment\",\"EmpOutcomePay\",\"LearnSuppFundCash\",\"OnProgPayment\")",DAO::FETCH_ASSOC);
            elseif($FundLine=="FM36")
                return DAO::getResultset($link, "SELECT SUM(P1) AS Aug
            ,SUM(P2) AS Sep
            ,SUM(P3) AS Oct
            ,SUM(P4) AS Nov
            ,SUM(P5) AS `Dec`
            ,SUM(P6) AS Jan
            ,SUM(P7) AS Feb
            ,SUM(P8) AS Mar
            ,SUM(P9) AS Apr
            ,SUM(P10) AS May
            ,SUM(P11) AS Jun
            ,SUM(P12) AS Jul
            FROM fm36_funding WHERE AttributeName IN (\"DisadvFirstPayment\",\"DisadvSecondPayment\",\"LDApplic1618FrameworkUpliftBalancingPayment\",\"LDApplic1618FrameworkUpliftBalancingPayment\",
            \"LDApplic1618FrameworkUpliftOnProgPayment\",\"LearnDelFirstEmp1618Pay\",\"LearnDelFirstProv1618Pay\",\"LearnDelSecondEmp1618Pay\",\"LearnDelSecondProv1618Pay\",
            \"LearnSuppFundCash\",\"MathEngBalPayment\",\"MathEngOnProgPayment\",\"ProgrammeAimBalPayment\",\"ProgrammeAimCompletionPayment\",\"ProgrammeAimOnProgPayment\")",DAO::FETCH_ASSOC);
            return null;
        }
    }

    public function getAllocation($link)
    {
             return DAO::getResultset($link, "SELECT SUM(aug) AS Aug
            ,SUM(sep) AS Sep
            ,SUM(oct) AS Oct
            ,SUM(nov) AS Nov
            ,SUM(dece) AS `Dec`
            ,SUM(jan) AS Jan
            ,SUM(feb) AS Feb
            ,SUM(mar) AS Mar
            ,SUM(apr) AS Apr
            ,SUM(may) AS May
            ,SUM(jun) AS Jun
            ,SUM(jul) AS Jul
            FROM profile_values",DAO::FETCH_ASSOC);
    }

    public function CalculateFunding($link, $row)
    {
        $obj = new stdClass();
        // Calculate opp instalments
        $obj->PlannedInstalments = $this->countMonthsWithEndOfMonth($row['LearnStartDate'], $row['LearnPlanEndDate']);
        return $obj;
    }


    public function getLastDatesOfMonths($start, $end) 
    {
        $startDate = new DateTime($start);
        $endDate = new DateTime($end);
    
        // Set to first day of the month to ensure full months are covered
        $startDate->modify('first day of this month');
        $endDate->modify('last day of this month');
    
        $lastDays = array();
    
        while ($startDate <= $endDate) {
            // Clone to avoid modifying original
            $lastDay = clone $startDate;
            $lastDay->modify('last day of this month');
            $lastDays[] = $lastDay->format('Y-m-d');
    
            // Move to next month
            $startDate->modify('first day of next month');
        }
    
        return $lastDays;
    }

    public $case_scenario = NULL;
    public $level = NULL;
}