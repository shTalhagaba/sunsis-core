<?php
class show_pfr_values extends ActionController
{
    public function indexAction(PDO $link)
    {

        $single_multi = DAO::getSingleValue($link, "select Value from funding_configuration where ConType = 'SingleMulti'");
        if($single_multi=='')
            $single_multi = "Single";
        // Chart Type
        $chart_type = DAO::getSingleValue($link, "select Value from funding_configuration where ConType = 'ChartType'");
        if($chart_type=='')
            $chart_type = "line";
        $values_type = DAO::getSingleValue($link, "select Value from funding_configuration where ConType = 'ValuesType'");
        if($values_type=='')
            $values_type = "Incremental";
        // Determine the current year
        $current_year = DAO::getSingleValue($link, "select contract_year from contracts order by contract_year desc limit 0,1");
        $current_submission = DAO::getSingleValue($link, "select submission from ilr where contract_id in (select id from contracts where contract_year = '$current_year') order by submission desc limit 0,1");
        if(substr($current_submission, 1, 1) == 0)
            $p = substr($current_submission, 2, 1);
        elseif(substr($current_submission, 1, 1) == 1)
            $p = substr($current_submission, 1, 2);
        // Update business codes
        $bc_query = "SELECT title as bc FROM contracts WHERE active = 1 and contract_year = '$current_year';";

        $business_codes = DAO::getResultset($link, $bc_query, DAO::FETCH_ASSOC);
        foreach ($business_codes as $business_code)
        {
            $bc = $business_code['bc'];
            if($bc!='')
            {
                $exists = DAO::getSingleValue($link, "select description from profile_values where description = '$bc'");
                if($exists != $bc)
                    DAO::execute($link,"insert into profile_values(id, description) values(NULL,'$bc')");
            }
        }
        $business_codes = DAO::getSingleColumn($link, "select description from profile_values where type = 'Y'");

        $total_august=0;
        $total_september=0;
        $total_october=0;
        $total_november=0;
        $total_december=0;
        $total_january=0;
        $total_february=0;
        $total_march=0;
        $total_april=0;
        $total_may=0;
        $total_june=0;
        $total_july=0;
        $total = 0;
        $stacked ='';


        foreach($business_codes as $business_code)
        {
            $aug = 0;
            $sep = 0;
            $oct = 0;
            $nov = 0;
            $dec = 0;
            $jan = 0;
            $feb = 0;
            $mar = 0;
            $apr = 0;
            $may = 0;
            $jun = 0;
            $jul = 0;

            $totalFunding = $this->getFunding($link, $business_code, $p, '', '', '', $current_submission,'',0);
            foreach($totalFunding as $funding)
            {
                $aug += round($funding['P1_total'] ?: 0);
                $sep += round($funding['P2_total'] ?: 0);
                $oct += round($funding['P3_total'] ?: 0);
                $nov += round($funding['P4_total'] ?: 0);
                $dec += round($funding['P5_total'] ?: 0);
                $jan += round($funding['P6_total'] ?: 0);
                $feb += round($funding['P7_total'] ?: 0);
                $mar += round($funding['P8_total'] ?: 0);
                $apr += round($funding['P9_total'] ?: 0);
                $may += round($funding['P10_total'] ?: 0);
                $jun += round($funding['P11_total'] ?: 0);
                $jul += round($funding['P12_total'] ?: 0);
            }

            /*$trs_sql = "select tr_id, contract_id from ilr inner join contracts on contracts.id = ilr.contract_id where contract_id in (select id from contracts where is_active = 1 and contract_year = '$current_year') and submission = '$current_submission' and title='$business_code' and extractvalue(ilr,'count(/Learner/LearningDelivery/LearnAimRef)') <> extractvalue(ilr,'count(/Learner/LearningDelivery[FundModel=99])')";
            $trs = DAO::getResultset($link, $trs_sql, DAO::FETCH_ASSOC);
            $tr_index = 0;
            foreach ($trs as $tr)
            {
                $tr_index++;
                $contract_id = $tr['contract_id'];
                $tr_id = $tr['tr_id'];
                $totalFunding = $this->getFunding($link, $contract_id, $p, '', '', '', $current_submission,'',$tr_id);
                foreach($totalFunding as $funding)
                {
                    $aug += round($funding['P1_total']);
                    $sep += round($funding['P2_total']);
                    $oct += round($funding['P3_total']);
                    $nov += round($funding['P4_total']);
                    $dec += round($funding['P5_total']);
                    $jan += round($funding['P6_total']);
                    $feb += round($funding['P7_total']);
                    $mar += round($funding['P8_total']);
                    $apr += round($funding['P9_total']);
                    $may += round($funding['P10_total']);
                    $jun += round($funding['P11_total']);
                    $jul += round($funding['P12_total']);
                }
            }*/



            $pfr[$business_code][] = "['Aug'," . $aug . "]";
            $pfr[$business_code][] = "['Sep'," . $sep . "]";
            $pfr[$business_code][] = "['Oct'," . $oct . "]";
            $pfr[$business_code][] = "['Nov'," . $nov . "]";
            $pfr[$business_code][] = "['Dec'," . $dec . "]";
            $pfr[$business_code][] = "['Jan'," . $jan . "]";
            $pfr[$business_code][] = "['Feb'," . $feb . "]";
            $pfr[$business_code][] = "['Mar'," . $mar . "]";
            $pfr[$business_code][] = "['Apr'," . $apr . "]";
            $pfr[$business_code][] = "['May'," . $may . "]";
            $pfr[$business_code][] = "['Jun'," . $jun . "]";
            $pfr[$business_code][] = "['Jul'," . $jul . "]";
            $total=$aug+$sep+$oct+$nov+$dec+$jan+$feb+$mar+$apr+$may+$jun+$jul;
            //$pfr[$pfr_value['business_code']][] = "['Total'," . $total . "]";
            $total_august+=$aug;
            $total_september+=$sep;
            $total_october+=$oct;
            $total_november+=$nov;
            $total_december+=$dec;
            $total_january+=$jan;
            $total_february+=$feb;
            $total_march+=$mar;
            $total_april+=$apr;
            $total_may+=$may;
            $total_june+=$jun;
            $total_july+=$jul;
            $stacked .= ",{ name: '" . $business_code . "', data:[" . $total_august . "," . $total_september . "," . $total_october. "," . $total_november. "," . $total_december. "," . $total_january. "," . $total_february. "," . $total_march. "," . $total_april. "," . $total_may. "," . $total_june. "," . $total_july."]}";
        }
        // Business Code Loop
        $stacked = substr($stacked,1);
        $pfr["TOTAL"][] = "['Aug'," . $total_august . "]";
        $pfr["TOTAL"][] = "['Sep'," . $total_september . "]";
        $pfr["TOTAL"][] = "['Oct'," . $total_october . "]";
        $pfr["TOTAL"][] = "['Nov'," . $total_november . "]";
        $pfr["TOTAL"][] = "['Dec'," . $total_december . "]";
        $pfr["TOTAL"][] = "['Jan'," . $total_january . "]";
        $pfr["TOTAL"][] = "['Feb'," . $total_february . "]";
        $pfr["TOTAL"][] = "['Mar'," . $total_march . "]";
        $pfr["TOTAL"][] = "['Apr'," . $total_april . "]";
        $pfr["TOTAL"][] = "['May'," . $total_may . "]";
        $pfr["TOTAL"][] = "['Jun'," . $total_june . "]";
        $pfr["TOTAL"][] = "['Jul'," . $total_july . "]";
        $gt = $total_august+$total_september+$total_october+$total_november+$total_december+$total_january+$total_february+$total_march+$total_april+$total_may+$total_june+$total_july;
        //$pfr["TOTAL"][] = "['Total'," . $gt . "]";
        include('tpl_show_pfr_values.php');
    }

    public function renderBusinessCodes(PDO $link, $year, $pfr)
    {

        echo <<<HEREDOC
<table class="table1" id='contacts' width="580" style="margin-left:10px">
<thead>
	<tr>
		<th>Contract</th>
		<th>August</th>
		<th>September</th>
		<th>October</th>
		<th>November</th>
		<th>December</th>
		<th>January</th>
		<th>February</th>
		<th>March</th>
		<th>April</th>
		<th>May</th>
		<th>June</th>
		<th>July</th>
		<th>Total</th>
	</tr>
	</thead>
	<tbody>
HEREDOC;

        $index = 1;
        $total_august=0;
        $total_september=0;
        $total_october=0;
        $total_november=0;
        $total_december=0;
        $total_january=0;
        $total_february=0;
        $total_march=0;
        $total_april=0;
        $total_may=0;
        $total_june=0;
        $total_july=0;
        foreach ($pfr as $contract=>$value)
        {
            echo '<tr class="dataRow">';
            echo '<td>'.htmlspecialchars((string)$contract).'</td>';
            $gt = 0;
            foreach($value as $v=>$m)
            {
                $a = explode(",",$m);
                echo '<td>'. number_format(substr($a[1],0,-1)) . '</td>';
                $gt += substr($a[1],0,-1);
            }
            echo '<td>'. number_format($gt) . '</td>';
        }
        echo '</tr>';
        echo '</tbody></table>';
    }

    public function renderBusinessCodes2(PDO $link, $year)
    {
        if($year)
        {
            $sql = <<<HEREDOC
SELECT *
from profile_values
HEREDOC;
            $business_codes = DAO::getResultset($link, $sql, DAO::FETCH_ASSOC);
        }

        echo <<<HEREDOC
<table class="table1" id='contacts' width="580" style="margin-left:10px">
<thead>
	<tr>
		<th>Contract</th>
		<th>Applicable<br><input type=checkbox id = "selectAll" onclick="selectAllCodes()"></th>
	</tr>
	</thead>
	<tbody>
HEREDOC;

        $index = 1;
        foreach ($business_codes as $business_code)
        {
            echo '<tr class="dataRow">';
            echo '<td>'.htmlspecialchars((string)$business_code['description']).'</td>';
            if($business_code['type']=="Y")
                echo '<td align=center><input checked id="button'.$index.'" type="checkbox"  name="boroughradio" value="' . $business_code['id'] . '" /></td>';
            else
                echo '<td align=center><input id="button'.$index.'" type="checkbox"   name="boroughradio" value="' . $business_code['id'] . '" /></td>';

            echo '</tr>';
            $index++;
        }
        echo '</tbody></table>';
    }

    private function getFunding(PDO $link, $contracts, $period, $course, $assessor, $employer, $submission, $tutor, $tr_id)
    {
        $data = Array();
        if($contracts == "2023-24 Skills Bootcamp West Mids")
        {
            $data[0]["P1_total"] = DAO::getSingleValue($link, "select sum(P1) from fm36_funding WHERE AttributeName IN (\"DisadvFirstPayment\",\"DisadvSecondPayment\",\"LDApplic1618FrameworkUpliftBalancingPayment\",\"LDApplic1618FrameworkUpliftBalancingPayment\",
            \"LDApplic1618FrameworkUpliftOnProgPayment\",\"LearnDelFirstEmp1618Pay\",\"LearnDelFirstProv1618Pay\",\"LearnDelSecondEmp1618Pay\",\"LearnDelSecondProv1618Pay\",
            \"LearnSuppFundCash\",\"MathEngBalPayment\",\"MathEngOnProgPayment\",\"ProgrammeAimBalPayment\",\"ProgrammeAimCompletionPayment\",\"ProgrammeAimOnProgPayment\")");
            $data[0]["P2_total"] = DAO::getSingleValue($link, "select sum(P2) from fm36_funding WHERE AttributeName IN (\"DisadvFirstPayment\",\"DisadvSecondPayment\",\"LDApplic1618FrameworkUpliftBalancingPayment\",\"LDApplic1618FrameworkUpliftBalancingPayment\",
            \"LDApplic1618FrameworkUpliftOnProgPayment\",\"LearnDelFirstEmp1618Pay\",\"LearnDelFirstProv1618Pay\",\"LearnDelSecondEmp1618Pay\",\"LearnDelSecondProv1618Pay\",
            \"LearnSuppFundCash\",\"MathEngBalPayment\",\"MathEngOnProgPayment\",\"ProgrammeAimBalPayment\",\"ProgrammeAimCompletionPayment\",\"ProgrammeAimOnProgPayment\")");
            $data[0]["P3_total"] = DAO::getSingleValue($link, "select sum(P3) from fm36_funding WHERE AttributeName IN (\"DisadvFirstPayment\",\"DisadvSecondPayment\",\"LDApplic1618FrameworkUpliftBalancingPayment\",\"LDApplic1618FrameworkUpliftBalancingPayment\",
            \"LDApplic1618FrameworkUpliftOnProgPayment\",\"LearnDelFirstEmp1618Pay\",\"LearnDelFirstProv1618Pay\",\"LearnDelSecondEmp1618Pay\",\"LearnDelSecondProv1618Pay\",
            \"LearnSuppFundCash\",\"MathEngBalPayment\",\"MathEngOnProgPayment\",\"ProgrammeAimBalPayment\",\"ProgrammeAimCompletionPayment\",\"ProgrammeAimOnProgPayment\")");
            $data[0]["P4_total"] = DAO::getSingleValue($link, "select sum(P4) from fm36_funding WHERE AttributeName IN (\"DisadvFirstPayment\",\"DisadvSecondPayment\",\"LDApplic1618FrameworkUpliftBalancingPayment\",\"LDApplic1618FrameworkUpliftBalancingPayment\",
            \"LDApplic1618FrameworkUpliftOnProgPayment\",\"LearnDelFirstEmp1618Pay\",\"LearnDelFirstProv1618Pay\",\"LearnDelSecondEmp1618Pay\",\"LearnDelSecondProv1618Pay\",
            \"LearnSuppFundCash\",\"MathEngBalPayment\",\"MathEngOnProgPayment\",\"ProgrammeAimBalPayment\",\"ProgrammeAimCompletionPayment\",\"ProgrammeAimOnProgPayment\")");
            $data[0]["P5_total"] = DAO::getSingleValue($link, "select sum(P5) from fm36_funding WHERE AttributeName IN (\"DisadvFirstPayment\",\"DisadvSecondPayment\",\"LDApplic1618FrameworkUpliftBalancingPayment\",\"LDApplic1618FrameworkUpliftBalancingPayment\",
            \"LDApplic1618FrameworkUpliftOnProgPayment\",\"LearnDelFirstEmp1618Pay\",\"LearnDelFirstProv1618Pay\",\"LearnDelSecondEmp1618Pay\",\"LearnDelSecondProv1618Pay\",
            \"LearnSuppFundCash\",\"MathEngBalPayment\",\"MathEngOnProgPayment\",\"ProgrammeAimBalPayment\",\"ProgrammeAimCompletionPayment\",\"ProgrammeAimOnProgPayment\")");
            $data[0]["P6_total"] = DAO::getSingleValue($link, "select sum(P6) from fm36_funding WHERE AttributeName IN (\"DisadvFirstPayment\",\"DisadvSecondPayment\",\"LDApplic1618FrameworkUpliftBalancingPayment\",\"LDApplic1618FrameworkUpliftBalancingPayment\",
            \"LDApplic1618FrameworkUpliftOnProgPayment\",\"LearnDelFirstEmp1618Pay\",\"LearnDelFirstProv1618Pay\",\"LearnDelSecondEmp1618Pay\",\"LearnDelSecondProv1618Pay\",
            \"LearnSuppFundCash\",\"MathEngBalPayment\",\"MathEngOnProgPayment\",\"ProgrammeAimBalPayment\",\"ProgrammeAimCompletionPayment\",\"ProgrammeAimOnProgPayment\")");
            $data[0]["P7_total"] = DAO::getSingleValue($link, "select sum(P7) from fm36_funding WHERE AttributeName IN (\"DisadvFirstPayment\",\"DisadvSecondPayment\",\"LDApplic1618FrameworkUpliftBalancingPayment\",\"LDApplic1618FrameworkUpliftBalancingPayment\",
            \"LDApplic1618FrameworkUpliftOnProgPayment\",\"LearnDelFirstEmp1618Pay\",\"LearnDelFirstProv1618Pay\",\"LearnDelSecondEmp1618Pay\",\"LearnDelSecondProv1618Pay\",
            \"LearnSuppFundCash\",\"MathEngBalPayment\",\"MathEngOnProgPayment\",\"ProgrammeAimBalPayment\",\"ProgrammeAimCompletionPayment\",\"ProgrammeAimOnProgPayment\")");
            $data[0]["P8_total"] = DAO::getSingleValue($link, "select sum(P8) from fm36_funding WHERE AttributeName IN (\"DisadvFirstPayment\",\"DisadvSecondPayment\",\"LDApplic1618FrameworkUpliftBalancingPayment\",\"LDApplic1618FrameworkUpliftBalancingPayment\",
            \"LDApplic1618FrameworkUpliftOnProgPayment\",\"LearnDelFirstEmp1618Pay\",\"LearnDelFirstProv1618Pay\",\"LearnDelSecondEmp1618Pay\",\"LearnDelSecondProv1618Pay\",
            \"LearnSuppFundCash\",\"MathEngBalPayment\",\"MathEngOnProgPayment\",\"ProgrammeAimBalPayment\",\"ProgrammeAimCompletionPayment\",\"ProgrammeAimOnProgPayment\")");
            $data[0]["P9_total"] = DAO::getSingleValue($link, "select sum(P9) from fm36_funding WHERE AttributeName IN (\"DisadvFirstPayment\",\"DisadvSecondPayment\",\"LDApplic1618FrameworkUpliftBalancingPayment\",\"LDApplic1618FrameworkUpliftBalancingPayment\",
            \"LDApplic1618FrameworkUpliftOnProgPayment\",\"LearnDelFirstEmp1618Pay\",\"LearnDelFirstProv1618Pay\",\"LearnDelSecondEmp1618Pay\",\"LearnDelSecondProv1618Pay\",
            \"LearnSuppFundCash\",\"MathEngBalPayment\",\"MathEngOnProgPayment\",\"ProgrammeAimBalPayment\",\"ProgrammeAimCompletionPayment\",\"ProgrammeAimOnProgPayment\")");
            $data[0]["P10_total"] = DAO::getSingleValue($link, "select sum(P10) from fm36_funding WHERE AttributeName IN (\"DisadvFirstPayment\",\"DisadvSecondPayment\",\"LDApplic1618FrameworkUpliftBalancingPayment\",\"LDApplic1618FrameworkUpliftBalancingPayment\",
            \"LDApplic1618FrameworkUpliftOnProgPayment\",\"LearnDelFirstEmp1618Pay\",\"LearnDelFirstProv1618Pay\",\"LearnDelSecondEmp1618Pay\",\"LearnDelSecondProv1618Pay\",
            \"LearnSuppFundCash\",\"MathEngBalPayment\",\"MathEngOnProgPayment\",\"ProgrammeAimBalPayment\",\"ProgrammeAimCompletionPayment\",\"ProgrammeAimOnProgPayment\")");
            $data[0]["P11_total"] = DAO::getSingleValue($link, "select sum(P11) from fm36_funding WHERE AttributeName IN (\"DisadvFirstPayment\",\"DisadvSecondPayment\",\"LDApplic1618FrameworkUpliftBalancingPayment\",\"LDApplic1618FrameworkUpliftBalancingPayment\",
            \"LDApplic1618FrameworkUpliftOnProgPayment\",\"LearnDelFirstEmp1618Pay\",\"LearnDelFirstProv1618Pay\",\"LearnDelSecondEmp1618Pay\",\"LearnDelSecondProv1618Pay\",
            \"LearnSuppFundCash\",\"MathEngBalPayment\",\"MathEngOnProgPayment\",\"ProgrammeAimBalPayment\",\"ProgrammeAimCompletionPayment\",\"ProgrammeAimOnProgPayment\")");
            $data[0]["P12_total"] = DAO::getSingleValue($link, "select sum(P12) from fm36_funding WHERE AttributeName IN (\"DisadvFirstPayment\",\"DisadvSecondPayment\",\"LDApplic1618FrameworkUpliftBalancingPayment\",\"LDApplic1618FrameworkUpliftBalancingPayment\",
            \"LDApplic1618FrameworkUpliftOnProgPayment\",\"LearnDelFirstEmp1618Pay\",\"LearnDelFirstProv1618Pay\",\"LearnDelSecondEmp1618Pay\",\"LearnDelSecondProv1618Pay\",
            \"LearnSuppFundCash\",\"MathEngBalPayment\",\"MathEngOnProgPayment\",\"ProgrammeAimBalPayment\",\"ProgrammeAimCompletionPayment\",\"ProgrammeAimOnProgPayment\")");
        }
        else
        {
            $data[0]["P1_total"] = DAO::getSingleValue($link, "select sum(P1) from fm35_funding where BC = '$contracts' and  AttributeName IN (\"AchievePayment\",\"BalancePayment\",\"EmpOutcomePay\",\"LearnSuppFundCash\",\"OnProgPayment\")");
            $data[0]["P2_total"] = DAO::getSingleValue($link, "select sum(P2) from fm35_funding where BC = '$contracts' and  AttributeName IN (\"AchievePayment\",\"BalancePayment\",\"EmpOutcomePay\",\"LearnSuppFundCash\",\"OnProgPayment\")");
            $data[0]["P3_total"] = DAO::getSingleValue($link, "select sum(P3) from fm35_funding where BC = '$contracts' and  AttributeName IN (\"AchievePayment\",\"BalancePayment\",\"EmpOutcomePay\",\"LearnSuppFundCash\",\"OnProgPayment\")");
            $data[0]["P4_total"] = DAO::getSingleValue($link, "select sum(P4) from fm35_funding where BC = '$contracts' and  AttributeName IN (\"AchievePayment\",\"BalancePayment\",\"EmpOutcomePay\",\"LearnSuppFundCash\",\"OnProgPayment\")");
            $data[0]["P5_total"] = DAO::getSingleValue($link, "select sum(P5) from fm35_funding where BC = '$contracts' and  AttributeName IN (\"AchievePayment\",\"BalancePayment\",\"EmpOutcomePay\",\"LearnSuppFundCash\",\"OnProgPayment\")");
            $data[0]["P6_total"] = DAO::getSingleValue($link, "select sum(P6) from fm35_funding where BC = '$contracts' and  AttributeName IN (\"AchievePayment\",\"BalancePayment\",\"EmpOutcomePay\",\"LearnSuppFundCash\",\"OnProgPayment\")");
            $data[0]["P7_total"] = DAO::getSingleValue($link, "select sum(P7) from fm35_funding where BC = '$contracts' and  AttributeName IN (\"AchievePayment\",\"BalancePayment\",\"EmpOutcomePay\",\"LearnSuppFundCash\",\"OnProgPayment\")");
            $data[0]["P8_total"] = DAO::getSingleValue($link, "select sum(P8) from fm35_funding where BC = '$contracts' and  AttributeName IN (\"AchievePayment\",\"BalancePayment\",\"EmpOutcomePay\",\"LearnSuppFundCash\",\"OnProgPayment\")");
            $data[0]["P9_total"] = DAO::getSingleValue($link, "select sum(P9) from fm35_funding where BC = '$contracts' and  AttributeName IN (\"AchievePayment\",\"BalancePayment\",\"EmpOutcomePay\",\"LearnSuppFundCash\",\"OnProgPayment\")");
            $data[0]["P10_total"] = DAO::getSingleValue($link, "select sum(P10) from fm35_funding where BC = '$contracts' and  AttributeName IN (\"AchievePayment\",\"BalancePayment\",\"EmpOutcomePay\",\"LearnSuppFundCash\",\"OnProgPayment\")");
            $data[0]["P11_total"] = DAO::getSingleValue($link, "select sum(P11) from fm35_funding where BC = '$contracts' and AttributeName IN (\"AchievePayment\",\"BalancePayment\",\"EmpOutcomePay\",\"LearnSuppFundCash\",\"OnProgPayment\")");
            $data[0]["P12_total"] = DAO::getSingleValue($link, "select sum(P12) from fm35_funding where BC = '$contracts' and  AttributeName IN (\"AchievePayment\",\"BalancePayment\",\"EmpOutcomePay\",\"LearnSuppFundCash\",\"OnProgPayment\")");
        }

        return $data;
        // dependencies
        /*require_once('./lib/funding/FundingCore.php');
        require_once('./lib/funding/PeriodLookup.php');
        require_once('./lib/funding/LearnerFunding.php');
        require_once('./lib/funding/FundingPeriod.php');
        require_once('./lib/funding/FundingPrediction.php');
        require_once('./lib/funding/FundingPredictionPeriod.php');

        $predictions = new FundingPredictionPeriod($link, $contracts, 25, $course,$assessor,$employer,$submission,$tutor,$tr_id);
        $data = $predictions->get_learnerdata();

        return $data;*/
    }

}
?>