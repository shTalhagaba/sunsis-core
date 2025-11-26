<?php
class show_funding_comparison extends ActionController
{
    public function indexAction(PDO $link)
    {
        // get Single/ Multi Configuration Option
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
        if(DB_NAME=='am_siemens' or DB_NAME=='am_siemens_demo')
            $bc_query = "SELECT DISTINCT trim(extractvalue(ilr,'/Learner/ProviderSpecLearnerMonitoring[ProvSpecLearnMonOccur=\"B\"]/ProvSpecLearnMon')) as bc FROM ilr WHERE submission = '$current_submission' and is_active = 1 and contract_id IN (SELECT id FROM contracts WHERE contract_year = '$current_year');";
        else
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
if($single_multi == "Multi")
{
    $profile_sql = <<<HEREDOC
    SELECT
    description AS business_code
    ,`type`
    ,ROUND(SUM(aug)) AS aug
    ,ROUND((SUM(sep))) AS sep
    ,ROUND((SUM(OCT))) AS `oct`
    ,ROUND((SUM(nov))) AS nov
    ,ROUND((SUM(dece))) AS dece
    ,ROUND((SUM(jan))) AS jan
    ,ROUND((SUM(feb))) AS feb
    ,ROUND((SUM(mar))) AS mar
    ,ROUND((SUM(apr))) AS apr
    ,ROUND((SUM(may))) AS may
    ,ROUND((SUM(jun))) AS jun
    ,ROUND((SUM(jul))) AS jul
    FROM profile_values
    group by description
    having type = 'Y'
HEREDOC;

    $profile_values = DAO::getResultset($link, $profile_sql, DAO::FETCH_ASSOC);
    foreach ($profile_values as $profile_value)
    {
        if($values_type=="Individual")
        {
            $profile[$profile_value['business_code']][] = $profile_value['aug'];
            $profile[$profile_value['business_code']][] = $profile_value['sep'];
            $profile[$profile_value['business_code']][] = $profile_value['oct'];
            $profile[$profile_value['business_code']][] = $profile_value['nov'];
            $profile[$profile_value['business_code']][] = $profile_value['dece'];
            $profile[$profile_value['business_code']][] = $profile_value['jan'];
            $profile[$profile_value['business_code']][] = $profile_value['feb'];
            $profile[$profile_value['business_code']][] = $profile_value['mar'];
            $profile[$profile_value['business_code']][] = $profile_value['apr'];
            $profile[$profile_value['business_code']][] = $profile_value['may'];
            $profile[$profile_value['business_code']][] = $profile_value['jun'];
            $profile[$profile_value['business_code']][] = $profile_value['jul'];
        }
        else
        {
            $profile[$profile_value['business_code']][] = $profile_value['aug'];
            $profile[$profile_value['business_code']][] = $profile_value['aug']+$profile_value['sep'];
            $profile[$profile_value['business_code']][] = $profile_value['aug']+$profile_value['sep']+$profile_value['oct'];
            $profile[$profile_value['business_code']][] = $profile_value['aug']+$profile_value['sep']+$profile_value['oct']+$profile_value['nov'];
            $profile[$profile_value['business_code']][] = $profile_value['aug']+$profile_value['sep']+$profile_value['oct']+$profile_value['nov']+$profile_value['dece'];
            $profile[$profile_value['business_code']][] = $profile_value['aug']+$profile_value['sep']+$profile_value['oct']+$profile_value['nov']+$profile_value['dece']+$profile_value['jan'];
            $profile[$profile_value['business_code']][] = $profile_value['aug']+$profile_value['sep']+$profile_value['oct']+$profile_value['nov']+$profile_value['dece']+$profile_value['jan']+$profile_value['feb'];
            $profile[$profile_value['business_code']][] = $profile_value['aug']+$profile_value['sep']+$profile_value['oct']+$profile_value['nov']+$profile_value['dece']+$profile_value['jan']+$profile_value['feb']+$profile_value['mar'];
            $profile[$profile_value['business_code']][] = $profile_value['aug']+$profile_value['sep']+$profile_value['oct']+$profile_value['nov']+$profile_value['dece']+$profile_value['jan']+$profile_value['feb']+$profile_value['mar']+$profile_value['apr'];
            $profile[$profile_value['business_code']][] = $profile_value['aug']+$profile_value['sep']+$profile_value['oct']+$profile_value['nov']+$profile_value['dece']+$profile_value['jan']+$profile_value['feb']+$profile_value['mar']+$profile_value['apr']+$profile_value['may'];
            $profile[$profile_value['business_code']][] = $profile_value['aug']+$profile_value['sep']+$profile_value['oct']+$profile_value['nov']+$profile_value['dece']+$profile_value['jan']+$profile_value['feb']+$profile_value['mar']+$profile_value['apr']+$profile_value['may']+$profile_value['jun'];
            $profile[$profile_value['business_code']][] = $profile_value['aug']+$profile_value['sep']+$profile_value['oct']+$profile_value['nov']+$profile_value['dece']+$profile_value['jan']+$profile_value['feb']+$profile_value['mar']+$profile_value['apr']+$profile_value['may']+$profile_value['jun']+$profile_value['jul'];
        }
    }


    $pfr = Array();
    foreach($business_codes as $business_code)
    {

        $pfr[$business_code][0] = 0;
        $pfr[$business_code][1] = 0;
        $pfr[$business_code][2] = 0;
        $pfr[$business_code][3] = 0;
        $pfr[$business_code][4] = 0;
        $pfr[$business_code][5] = 0;
        $pfr[$business_code][6] = 0;
        $pfr[$business_code][7] = 0;
        $pfr[$business_code][8] = 0;
        $pfr[$business_code][9] = 0;
        $pfr[$business_code][10] = 0;
        $pfr[$business_code][11] = 0;
    
        $totalFunding = $this->getFunding($link, $business_code, $p, '', '', '', $current_submission,'',0);
        foreach($totalFunding as $funding)
        {
            $P1_total = $funding['P1_total'] ?: 0;
            $P2_total = $funding['P2_total'] ?: 0;
            $P3_total = $funding['P3_total'] ?: 0;
            $P4_total = $funding['P4_total'] ?: 0;
            $P5_total = $funding['P5_total'] ?: 0;
            $P6_total = $funding['P6_total'] ?: 0;
            $P7_total = $funding['P7_total'] ?: 0;
            $P8_total = $funding['P8_total'] ?: 0;
            $P9_total = $funding['P9_total'] ?: 0;
            $P10_total = $funding['P10_total'] ?: 0;
            $P11_total = $funding['P11_total'] ?: 0;
            $P12_total = $funding['P12_total'] ?: 0;

            if($values_type=="Individual")
            {
                $pfr[$business_code][0] += round($P1_total);
                $pfr[$business_code][1] += round($P2_total);
                $pfr[$business_code][2] += round($P3_total);
                $pfr[$business_code][3] += round($P4_total);
                $pfr[$business_code][4] += round($P5_total);
                $pfr[$business_code][5] += round($P6_total);
                $pfr[$business_code][6] += round($P7_total);
                $pfr[$business_code][7] += round($P8_total);
                $pfr[$business_code][8] += round($P9_total);
                $pfr[$business_code][9] += round($P10_total);
                $pfr[$business_code][10] += round($P11_total);
                $pfr[$business_code][11] += round($P12_total);
            }
            else
            {
                $pfr[$business_code][0] += round($P1_total);
                $pfr[$business_code][1] += round($P1_total) + round($P2_total);
                $pfr[$business_code][2] += round($P1_total) + round($P2_total) + round($P3_total);
                $pfr[$business_code][3] += round($P1_total) + round($P2_total) + round($P3_total) + round($P4_total);
                $pfr[$business_code][4] += round($P1_total) + round($P2_total) + round($P3_total) + round($P4_total) + round($P5_total);
                $pfr[$business_code][5] += round($P1_total) + round($P2_total) + round($P3_total) + round($P4_total) + round($P5_total) + round($P6_total);
                $pfr[$business_code][6] += round($P1_total) + round($P2_total) + round($P3_total) + round($P4_total) + round($P5_total) + round($P6_total) + round($P7_total);
                $pfr[$business_code][7] += round($P1_total) + round($P2_total) + round($P3_total) + round($P4_total) + round($P5_total) + round($P6_total) + round($P7_total) + round($P8_total);
                $pfr[$business_code][8] += round($P1_total) + round($P2_total) + round($P3_total) + round($P4_total) + round($P5_total) + round($P6_total) + round($P7_total) + round($P8_total) + round($P9_total);
                $pfr[$business_code][9] += round($P1_total) + round($P2_total) + round($P3_total) + round($P4_total) + round($P5_total) + round($P6_total) + round($P7_total) + round($P8_total) + round($P9_total) + round($P10_total);
                $pfr[$business_code][10] += round($P1_total) + round($P2_total) + round($P3_total) + round($P4_total) + round($P5_total) + round($P6_total) + round($P7_total) + round($P8_total) + round($P9_total) + round($P10_total) + round($P11_total);
                $pfr[$business_code][11] += round($P1_total) + round($P2_total) + round($P3_total) + round($P4_total) + round($P5_total) + round($P6_total) + round($P7_total) + round($P8_total) + round($P9_total) + round($P10_total) + round($P11_total) + round($P12_total);
            }
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
                if($values_type=="Individual")
                {
                    $pfr[$business_code][0] += round($funding['P1_total']);
                    $pfr[$business_code][1] += round($funding['P2_total']);
                    $pfr[$business_code][2] += round($funding['P3_total']);
                    $pfr[$business_code][3] += round($funding['P4_total']);
                    $pfr[$business_code][4] += round($funding['P5_total']);
                    $pfr[$business_code][5] += round($funding['P6_total']);
                    $pfr[$business_code][6] += round($funding['P7_total']);
                    $pfr[$business_code][7] += round($funding['P8_total']);
                    $pfr[$business_code][8] += round($funding['P9_total']);
                    $pfr[$business_code][9] += round($funding['P10_total']);
                    $pfr[$business_code][10] += round($funding['P11_total']);
                    $pfr[$business_code][11] += round($funding['P12_total']);
                }
                else
                {
                    $pfr[$business_code][0] += round($funding['P1_total']);
                    $pfr[$business_code][1] += round($funding['P1_total']) + round($funding['P2_total']);
                    $pfr[$business_code][2] += round($funding['P1_total']) + round($funding['P2_total']) + round($funding['P3_total']);
                    $pfr[$business_code][3] += round($funding['P1_total']) + round($funding['P2_total']) + round($funding['P3_total']) + round($funding['P4_total']);
                    $pfr[$business_code][4] += round($funding['P1_total']) + round($funding['P2_total']) + round($funding['P3_total']) + round($funding['P4_total']) + round($funding['P5_total']);
                    $pfr[$business_code][5] += round($funding['P1_total']) + round($funding['P2_total']) + round($funding['P3_total']) + round($funding['P4_total']) + round($funding['P5_total']) + round($funding['P6_total']);
                    $pfr[$business_code][6] += round($funding['P1_total']) + round($funding['P2_total']) + round($funding['P3_total']) + round($funding['P4_total']) + round($funding['P5_total']) + round($funding['P6_total']) + round($funding['P7_total']);
                    $pfr[$business_code][7] += round($funding['P1_total']) + round($funding['P2_total']) + round($funding['P3_total']) + round($funding['P4_total']) + round($funding['P5_total']) + round($funding['P6_total']) + round($funding['P7_total']) + round($funding['P8_total']);
                    $pfr[$business_code][8] += round($funding['P1_total']) + round($funding['P2_total']) + round($funding['P3_total']) + round($funding['P4_total']) + round($funding['P5_total']) + round($funding['P6_total']) + round($funding['P7_total']) + round($funding['P8_total']) + round($funding['P9_total']);
                    $pfr[$business_code][9] += round($funding['P1_total']) + round($funding['P2_total']) + round($funding['P3_total']) + round($funding['P4_total']) + round($funding['P5_total']) + round($funding['P6_total']) + round($funding['P7_total']) + round($funding['P8_total']) + round($funding['P9_total']) + round($funding['P10_total']);
                    $pfr[$business_code][10] += round($funding['P1_total']) + round($funding['P2_total']) + round($funding['P3_total']) + round($funding['P4_total']) + round($funding['P5_total']) + round($funding['P6_total']) + round($funding['P7_total']) + round($funding['P8_total']) + round($funding['P9_total']) + round($funding['P10_total']) + round($funding['P11_total']);
                    $pfr[$business_code][11] += round($funding['P1_total']) + round($funding['P2_total']) + round($funding['P3_total']) + round($funding['P4_total']) + round($funding['P5_total']) + round($funding['P6_total']) + round($funding['P7_total']) + round($funding['P8_total']) + round($funding['P9_total']) + round($funding['P10_total']) + round($funding['P11_total']) + round($funding['P12_total']);
                }
            }
        }*/
    }
}
else
{
    $profile_sql = <<<HEREDOC
SELECT
ROUND(SUM(aug)) AS aug
,ROUND(SUM(sep)) AS sep
,ROUND(SUM(OCT)) AS `oct`
,ROUND(SUM(nov)) AS nov
,ROUND(SUM(dece)) AS dece
,ROUND(SUM(jan)) AS jan
,ROUND(SUM(feb)) AS feb
,ROUND(SUM(mar)) AS mar
,ROUND(SUM(apr)) AS apr
,ROUND(SUM(may)) AS may
,ROUND(SUM(jun)) AS jun
,ROUND(SUM(jul)) AS jul FROM profile_values where type = 'Y';
HEREDOC;
    $profile_values = DAO::getResultset($link, $profile_sql, DAO::FETCH_ASSOC);
    foreach ($profile_values as $profile_value)
    {
        if($values_type=="Individual")
        {
            $profile[] = $profile_value['aug'];
            $profile[] = $profile_value['sep'];
            $profile[] = $profile_value['oct'];
            $profile[] = $profile_value['nov'];
            $profile[] = $profile_value['dece'];
            $profile[] = $profile_value['jan'];
            $profile[] = $profile_value['feb'];
            $profile[] = $profile_value['mar'];
            $profile[] = $profile_value['apr'];
            $profile[] = $profile_value['may'];
            $profile[] = $profile_value['jun'];
            $profile[] = $profile_value['jul'];
        }
        else
        {
            $profile[] = $profile_value['aug'];
            $profile[] = $profile_value['aug']+$profile_value['sep'];
            $profile[] = $profile_value['aug']+$profile_value['sep']+$profile_value['oct'];
            $profile[] = $profile_value['aug']+$profile_value['sep']+$profile_value['oct']+$profile_value['nov'];
            $profile[] = $profile_value['aug']+$profile_value['sep']+$profile_value['oct']+$profile_value['nov']+$profile_value['dece'];
            $profile[] = $profile_value['aug']+$profile_value['sep']+$profile_value['oct']+$profile_value['nov']+$profile_value['dece']+$profile_value['jan'];
            $profile[] = $profile_value['aug']+$profile_value['sep']+$profile_value['oct']+$profile_value['nov']+$profile_value['dece']+$profile_value['jan']+$profile_value['feb'];
            $profile[] = $profile_value['aug']+$profile_value['sep']+$profile_value['oct']+$profile_value['nov']+$profile_value['dece']+$profile_value['jan']+$profile_value['feb']+$profile_value['mar'];
            $profile[] = $profile_value['aug']+$profile_value['sep']+$profile_value['oct']+$profile_value['nov']+$profile_value['dece']+$profile_value['jan']+$profile_value['feb']+$profile_value['mar']+$profile_value['apr'];
            $profile[] = $profile_value['aug']+$profile_value['sep']+$profile_value['oct']+$profile_value['nov']+$profile_value['dece']+$profile_value['jan']+$profile_value['feb']+$profile_value['mar']+$profile_value['apr']+$profile_value['may'];
            $profile[] = $profile_value['aug']+$profile_value['sep']+$profile_value['oct']+$profile_value['nov']+$profile_value['dece']+$profile_value['jan']+$profile_value['feb']+$profile_value['mar']+$profile_value['apr']+$profile_value['may']+$profile_value['jun'];
            $profile[] = $profile_value['aug']+$profile_value['sep']+$profile_value['oct']+$profile_value['nov']+$profile_value['dece']+$profile_value['jan']+$profile_value['feb']+$profile_value['mar']+$profile_value['apr']+$profile_value['may']+$profile_value['jun']+$profile_value['jul'];
        }
    }

    $pfr=Array();
    $pfr[0] = 0;
    $pfr[1] = 0;
    $pfr[2] = 0;
    $pfr[3] = 0;
    $pfr[4] = 0;
    $pfr[5] = 0;
    $pfr[6] = 0;
    $pfr[7] = 0;
    $pfr[8] = 0;
    $pfr[9] = 0;
    $pfr[10] = 0;
    $pfr[11] = 0;

    foreach($business_codes as $business_code)
    {
        $totalFunding = $this->getFunding($link, $business_code, $p, '', '', '', $current_submission,'',0);
        foreach($totalFunding as $funding)
        {
            $P1_total = $funding['P1_total'] ?: 0;
            $P2_total = $funding['P2_total'] ?: 0;
            $P3_total = $funding['P3_total'] ?: 0;
            $P4_total = $funding['P4_total'] ?: 0;
            $P5_total = $funding['P5_total'] ?: 0;
            $P6_total = $funding['P6_total'] ?: 0;
            $P7_total = $funding['P7_total'] ?: 0;
            $P8_total = $funding['P8_total'] ?: 0;
            $P9_total = $funding['P9_total'] ?: 0;
            $P10_total = $funding['P10_total'] ?: 0;
            $P11_total = $funding['P11_total'] ?: 0;
            $P12_total = $funding['P12_total'] ?: 0;

            if($values_type=="Individual")
            {
                $pfr[0] += round($P1_total);
                $pfr[1] += round($P2_total);
                $pfr[2] += round($P3_total);
                $pfr[3] += round($P4_total);
                $pfr[4] += round($P5_total);
                $pfr[5] += round($P6_total);
                $pfr[6] += round($P7_total);
                $pfr[7] += round($P8_total);
                $pfr[8] += round($P9_total);
                $pfr[9] += round($P10_total);
                $pfr[10] += round($P11_total);
                $pfr[11] += round($P12_total);
            }
            else
            {
                $pfr[0] += round($P1_total);
                $pfr[1] += round($P1_total) + round($P2_total);
                $pfr[2] += round($P1_total) + round($P2_total) + round($P3_total);
                $pfr[3] += round($P1_total) + round($P2_total) + round($P3_total) + round($P4_total);
                $pfr[4] += round($P1_total) + round($P2_total) + round($P3_total) + round($P4_total) + round($P5_total);
                $pfr[5] += round($P1_total) + round($P2_total) + round($P3_total) + round($P4_total) + round($P5_total) + round($P6_total);
                $pfr[6] += round($P1_total) + round($P2_total) + round($P3_total) + round($P4_total) + round($P5_total) + round($P6_total) + round($P7_total);
                $pfr[7] += round($P1_total) + round($P2_total) + round($P3_total) + round($P4_total) + round($P5_total) + round($P6_total) + round($P7_total) + round($P8_total);
                $pfr[8] += round($P1_total) + round($P2_total) + round($P3_total) + round($P4_total) + round($P5_total) + round($P6_total) + round($P7_total) + round($P8_total) + round($P9_total);
                $pfr[9] += round($P1_total) + round($P2_total) + round($P3_total) + round($P4_total) + round($P5_total) + round($P6_total) + round($P7_total) + round($P8_total) + round($P9_total) + round($P10_total);
                $pfr[10] += round($P1_total) + round($P2_total) + round($P3_total) + round($P4_total) + round($P5_total) + round($P6_total) + round($P7_total) + round($P8_total) + round($P9_total) + round($P10_total) + round($P11_total);
                $pfr[11] += round($P1_total) + round($P2_total) + round($P3_total) + round($P4_total) + round($P5_total) + round($P6_total) + round($P7_total) + round($P8_total) + round($P9_total) + round($P10_total) + round($P11_total) + round($P12_total);
            }
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
                if($values_type=="Individual")
                {
                    $pfr[0] += round($funding['P1_total']);
                    $pfr[1] += round($funding['P2_total']);
                    $pfr[2] += round($funding['P3_total']);
                    $pfr[3] += round($funding['P4_total']);
                    $pfr[4] += round($funding['P5_total']);
                    $pfr[5] += round($funding['P6_total']);
                    $pfr[6] += round($funding['P7_total']);
                    $pfr[7] += round($funding['P8_total']);
                    $pfr[8] += round($funding['P9_total']);
                    $pfr[9] += round($funding['P10_total']);
                    $pfr[10] += round($funding['P11_total']);
                    $pfr[11] += round($funding['P12_total']);
                }
                else
                {
                    $pfr[0] += round($funding['P1_total']);
                    $pfr[1] += round($funding['P1_total']) + round($funding['P2_total']);
                    $pfr[2] += round($funding['P1_total']) + round($funding['P2_total']) + round($funding['P3_total']);
                    $pfr[3] += round($funding['P1_total']) + round($funding['P2_total']) + round($funding['P3_total']) + round($funding['P4_total']);
                    $pfr[4] += round($funding['P1_total']) + round($funding['P2_total']) + round($funding['P3_total']) + round($funding['P4_total']) + round($funding['P5_total']);
                    $pfr[5] += round($funding['P1_total']) + round($funding['P2_total']) + round($funding['P3_total']) + round($funding['P4_total']) + round($funding['P5_total']) + round($funding['P6_total']);
                    $pfr[6] += round($funding['P1_total']) + round($funding['P2_total']) + round($funding['P3_total']) + round($funding['P4_total']) + round($funding['P5_total']) + round($funding['P6_total']) + round($funding['P7_total']);
                    $pfr[7] += round($funding['P1_total']) + round($funding['P2_total']) + round($funding['P3_total']) + round($funding['P4_total']) + round($funding['P5_total']) + round($funding['P6_total']) + round($funding['P7_total']) + round($funding['P8_total']);
                    $pfr[8] += round($funding['P1_total']) + round($funding['P2_total']) + round($funding['P3_total']) + round($funding['P4_total']) + round($funding['P5_total']) + round($funding['P6_total']) + round($funding['P7_total']) + round($funding['P8_total']) + round($funding['P9_total']);
                    $pfr[9] += round($funding['P1_total']) + round($funding['P2_total']) + round($funding['P3_total']) + round($funding['P4_total']) + round($funding['P5_total']) + round($funding['P6_total']) + round($funding['P7_total']) + round($funding['P8_total']) + round($funding['P9_total']) + round($funding['P10_total']);
                    $pfr[10] += round($funding['P1_total']) + round($funding['P2_total']) + round($funding['P3_total']) + round($funding['P4_total']) + round($funding['P5_total']) + round($funding['P6_total']) + round($funding['P7_total']) + round($funding['P8_total']) + round($funding['P9_total']) + round($funding['P10_total']) + round($funding['P11_total']);
                    $pfr[11] += round($funding['P1_total']) + round($funding['P2_total']) + round($funding['P3_total']) + round($funding['P4_total']) + round($funding['P5_total']) + round($funding['P6_total']) + round($funding['P7_total']) + round($funding['P8_total']) + round($funding['P9_total']) + round($funding['P10_total']) + round($funding['P11_total']) + round($funding['P12_total']);
                }
            }
        }*/
    }
}
    //pre($pfr);
    include('tpl_show_funding_comparison.php');
    }

    public function renderBusinessCodes(PDO $link, $year)
    {
        if($year)
        {
            $sql = <<<HEREDOC
SELECT profile_values.description as description
,profile_values.aug as aug
,profile_values.sep as sep
,profile_values.oct as oct
,profile_values.nov as nov
,profile_values.dece as dece
,profile_values.jan as jan
,profile_values.feb as feb
,profile_values.mar as mar
,profile_values.apr as apr
,profile_values.may as may
,profile_values.jun as jun
,profile_values.jul as jul
FROM profile_values
HEREDOC;
            $profile_values = DAO::getResultset($link, $sql, DAO::FETCH_ASSOC);
        }

        echo <<<HEREDOC
<table class="table1" id='contacts' width="580" style="margin-left:10px">
<thead>
	<tr>
		<th>Business Code</th>
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
	</tr>
	</thead>
	<tbody>
HEREDOC;

        $index = 1;
        foreach ($profile_values as $profile_value)
        {
            echo '<tr class="dataRow">';
            echo '<td>'.htmlspecialchars((string)$profile_value['description']).'</td>';
            echo '<td><input name="'.$profile_value['description'].'|aug" size = 3 value="' . $profile_value['aug'] . '" /> </td>';
            echo '<td><input name="'.$profile_value['description'].'|sep" size = 3 value="' . $profile_value['sep'] . '" /> </td>';
            echo '<td><input name="'.$profile_value['description'].'|oct" size = 3 value="' . $profile_value['oct'] . '" /> </td>';
            echo '<td><input name="'.$profile_value['description'].'|nov" size = 3 value="' . $profile_value['nov'] . '" /> </td>';
            echo '<td><input name="'.$profile_value['description'].'|dece" size = 3 value="' . $profile_value['dece'] . '" /> </td>';
            echo '<td><input name="'.$profile_value['description'].'|jan" size = 3 value="' . $profile_value['jan'] . '" /> </td>';
            echo '<td><input name="'.$profile_value['description'].'|feb" size = 3 value="' . $profile_value['feb'] . '" /> </td>';
            echo '<td><input name="'.$profile_value['description'].'|mar" size = 3 value="' . $profile_value['mar'] . '" /> </td>';
            echo '<td><input name="'.$profile_value['description'].'|apr" size = 3 value="' . $profile_value['apr'] . '" /> </td>';
            echo '<td><input name="'.$profile_value['description'].'|may" size = 3 value="' . $profile_value['may'] . '" /> </td>';
            echo '<td><input name="'.$profile_value['description'].'|jun" size = 3 value="' . $profile_value['jun'] . '" /> </td>';
            echo '<td><input name="'.$profile_value['description'].'|jul" size = 3 value="' . $profile_value['jul'] . '" /> </td>';
            echo '</tr>';
        }
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
		<th>Business Code</th>
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
            $data[0]["P11_total"] = DAO::getSingleValue($link, "select sum(P11) from fm35_funding where BC = '$contracts' and  AttributeName IN (\"AchievePayment\",\"BalancePayment\",\"EmpOutcomePay\",\"LearnSuppFundCash\",\"OnProgPayment\")");
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