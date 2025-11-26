<?php
class read_allocation implements IAction
{
    public function execute(PDO $link)
    {
        $allocation_id = isset($_REQUEST['allocation_id'])?$_REQUEST['allocation_id']:'';
        $allocation = Allocation::loadFromDatabase($link, $allocation_id);

        $allocation_start_date = $allocation->start_date;
        $allocation_end_date = $allocation->end_date;

        $months = Array();
        $i = date("Ym", strtotime($allocation_start_date));
        while($i <= date("Ym", strtotime($allocation_end_date)))
        {
            $months[] =  $i;
            if(substr($i, 4, 2) == "12")
                $i = (date("Y", strtotime($i."01")) + 1)."01";
            else
                $i++;
        }
        $monthly_allocation = $allocation->allocation_amount/sizeof($months);

        // dependencies
        require_once('./lib/funding/FundingCore.php');
        require_once('./lib/funding/PeriodLookup.php');
        require_once('./lib/funding/LearnerFunding.php');
        require_once('./lib/funding/FundingPeriod.php');
        require_once('./lib/funding/FundingPrediction.php');
        require_once('./lib/funding/FundingPredictionPeriod.php');
        $submission_months = Array();
        $submission_months[1] = 6;
        $submission_months[2] = 7;
        $submission_months[3] = 8;
        $submission_months[4] = 9;
        $submission_months[5] = 10;
        $submission_months[6] = 11;
        $submission_months[7] = 12;
        $submission_months[8] = 1;
        $submission_months[9] = 2;
        $submission_months[10] = 3;
        $submission_months[11] = 4;
        $submission_months[12] = 5;

        $learner_funding = Array();
        $sql = "SELECT id,l03 FROM tr WHERE start_date >= '$allocation->learner_start_date' and start_date<= '$allocation->learner_end_date' and contract_id IN (SELECT id FROM contracts WHERE allocation_id = $allocation_id)";
        $st = $link->query($sql);
        if($st)
        {
            $test = 0;
            while($row = $st->fetch())
            {
                $test++;
                $tr_id = $row['id'];
                $l03 = $row['l03'];

                foreach($months as $month)
                {
                    $contract_year = DAO::getSingleValue($link, "SELECT contract_year FROM central.`lookup_submission_dates` WHERE DATE_FORMAT(census_start_date,'%Y%m')='$month'");
                    $contracts = DAO::getSingleValue($link, "SELECT * FROM contracts WHERE contract_year = '$contract_year' AND id IN (SELECT contract_id FROM ilr WHERE tr_id = '$tr_id')");
                    if($contracts!='')
                    {
                        $current_submission = DAO::getSingleValue($link, "SELECT submission FROM ilr WHERE contract_id = '$contracts' ORDER BY submission DESC LIMIT 0,1;");
                        $p = (int)substr($month, 4, 2);
                        $p2 = $submission_months[$p];
                        $predictions = new FundingPredictionPeriod($link, $contracts, 25, '', '', '', $current_submission, '', $tr_id);
                        $data = $predictions->get_learnerdata();
                        if(isset($learner_funding[$month][$tr_id]))
                            $learner_funding[$l03][$month] += $this->getTotalFunding($data,$p2);
                        else
                            $learner_funding[$l03][$month] = $this->getTotalFunding($data,$p2);
                    }
                }
            }
        }

        include_once('tpl_read_allocation.php');
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

    public function getTotalFunding($data, $p)
    {
        $amount =0;
        foreach($data as $d)
        {
            $amount += $d["P".$p."_OPP"];
            $amount += $d["P".$p."_bal"];
            $amount += $d["P".$p."_ach"];
            //$amount += $d["P".$p."_ach_p"];
            $amount += $d["P".$p."_EM_OPP"];
            $amount += $d["P".$p."_EM_Bal"];
            $amount += $d["P".$p."_1618_Pro_Inc"];
            $amount += $d["P".$p."_1618_Emp_Inc"];
            $amount += $d["P".$p."_FM36_Disadv"];
            $amount += $d["P".$p."_ALS"];
            $amount += $d["P".$p."_1618_FW_Uplift_OPP"];
            $amount += $d["P".$p."_1618_FW_Uplift_Bal"];
            $amount += $d["P".$p."_1618_FW_Uplift_Comp"];
        }
        return $amount;
    }

    public function getFormattedDate($month)
    {
        return substr($month,4,2) . '-' . substr($month,0,4);
    }

    public $case_scenario = NULL;
    public $level = NULL;
}