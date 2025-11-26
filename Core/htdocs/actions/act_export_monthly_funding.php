<?php
class export_monthly_funding implements IAction
{
    public function execute(PDO $link)
    {

        $trs = isset($_REQUEST['trs'])?$_REQUEST['trs']:'';
        $funding = isset($_REQUEST['funding'])?$_REQUEST['funding']:'0';
        $start_date = isset($_REQUEST['start_date'])?$_REQUEST['start_date']:'';

        $start_date = Date::toMySQL($start_date);

        $file="Details.csv";
        header("Content-type: application/vnd.ms-excel");
        header("Content-Disposition: attachment; filename=$file");

        $contract_year = DAO::getSingleValue($link, "select contract_year from contracts order by contract_year desc limit 1");
        $contract_yearp = $contract_year+1;
        $data = "Learner Reference Number,Firstname,Surname,LearnAimRef";
        if(strtotime($start_date)>=strtotime("$contract_year-08-01"))
        {
            $data.=",August On Programme Payment, August Balancing Payment, August Completion Payment, August English and Maths On Programme Payment, August English and Maths Balancing Payment, August LSF Payment, August Disadvantage Payment, August 16-18 additional payments for employer, August 16-18 additional payments for provider, August 16-18 framework uplift on programme payment, August 16-18 framework uplift balancing payment, August 16-18 framework uplift completion payment";
        }
        if(strtotime($start_date)>=strtotime("$contract_year-09-01"))
        {
            $data.=",September On Programme Payment, September Balancing Payment, September Completion Payment, September English and Maths On Programme Payment, September English and Maths Balancing Payment, September LSF Payment, September Disadvantage Payment, September 16-18 additional payments for employer, September 16-18 additional payments for provider, September 16-18 framework uplift on programme payment, September 16-18 framework uplift balancing payment, September 16-18 framework uplift completion payment";
        }
        if(strtotime($start_date)>=strtotime("$contract_year-10-01"))
        {
            $data.=",October On Programme Payment, October Balancing Payment, October Completion Payment, October English and Maths On Programme Payment, October English and Maths Balancing Payment, October LSF Payment, October Disadvantage Payment, October 16-18 additional payments for employer, October 16-18 additional payments for provider, October 16-18 framework uplift on programme payment, October 16-18 framework uplift balancing payment, October 16-18 framework uplift completion payment";
        }
        if(strtotime($start_date)>=strtotime("$contract_year-11-01"))
        {
            $data.=",November On Programme Payment, November Balancing Payment, November Completion Payment, November English and Maths On Programme Payment, November English and Maths Balancing Payment, November LSF Payment, November Disadvantage Payment, November 16-18 additional payments for employer, November 16-18 additional payments for provider, November 16-18 framework uplift on programme payment, November 16-18 framework uplift balancing payment, November 16-18 framework uplift completion payment";
        }
        if(strtotime($start_date)>=strtotime("$contract_year-12-01"))
        {
            $data.=",December On Programme Payment, December Balancing Payment, December Completion Payment, December English and Maths On Programme Payment, December English and Maths Balancing Payment, December LSF Payment, December Disadvantage Payment, December 16-18 additional payments for employer, December 16-18 additional payments for provider, December 16-18 framework uplift on programme payment, December 16-18 framework uplift balancing payment, December 16-18 framework uplift completion payment";
        }
        if(strtotime($start_date)>=strtotime("$contract_yearp-01-01"))
        {
            $data.=",January On Programme Payment, January Balancing Payment, January Completion Payment, January English and Maths On Programme Payment, January English and Maths Balancing Payment, January LSF Payment, January Disadvantage Payment, January 16-18 additional payments for employer, January 16-18 additional payments for provider, January 16-18 framework uplift on programme payment, January 16-18 framework uplift balancing payment, January 16-18 framework uplift completion payment";
        }
        if(strtotime($start_date)>=strtotime("$contract_yearp-02-01"))
        {
            $data.=",February On Programme Payment, February Balancing Payment, February Completion Payment, February English and Maths On Programme Payment, February English and Maths Balancing Payment, February LSF Payment, February Disadvantage Payment, February 16-18 additional payments for employer, February 16-18 additional payments for provider, February 16-18 framework uplift on programme payment, February 16-18 framework uplift balancing payment, February 16-18 framework uplift completion payment";
        }
        if(strtotime($start_date)>=strtotime("$contract_yearp-03-01"))
        {
            $data.=",March On Programme Payment, March Balancing Payment, March Completion Payment, March English and Maths On Programme Payment, March English and Maths Balancing Payment, March LSF Payment, March Disadvantage Payment, March 16-18 additional payments for employer, March 16-18 additional payments for provider, March 16-18 framework uplift on programme payment, March 16-18 framework uplift balancing payment, March 16-18 framework uplift completion payment";
        }
        if(strtotime($start_date)>=strtotime("$contract_yearp-04-01"))
        {
            $data.=",April On Programme Payment, April Balancing Payment, April Completion Payment, April English and Maths On Programme Payment, April English and Maths Balancing Payment, April LSF Payment, April Disadvantage Payment, April 16-18 additional payments for employer, April 16-18 additional payments for provider, April 16-18 framework uplift on programme payment, April 16-18 framework uplift balancing payment, April 16-18 framework uplift completion payment";
        }
        if(strtotime($start_date)>=strtotime("$contract_yearp-05-01"))
        {
            $data.=",May On Programme Payment, May Balancing Payment, May Completion Payment, May English and Maths On Programme Payment, May English and Maths Balancing Payment, May LSF Payment, May Disadvantage Payment, May 16-18 additional payments for employer, May 16-18 additional payments for provider, May 16-18 framework uplift on programme payment, May 16-18 framework uplift balancing payment, May 16-18 framework uplift completion payment";
        }
        if(strtotime($start_date)>=strtotime("$contract_yearp-06-01"))
        {
            $data.=",June On Programme Payment, June Balancing Payment, June Completion Payment, June English and Maths On Programme Payment, June English and Maths Balancing Payment, June LSF Payment, June Disadvantage Payment, June 16-18 additional payments for employer, June 16-18 additional payments for provider, June 16-18 framework uplift on programme payment, June 16-18 framework uplift balancing payment, June 16-18 framework uplift completion payment";
        }
        if(strtotime($start_date)>=strtotime("$contract_yearp-07-01"))
        {
            $data.=",July On Programme Payment, July Balancing Payment, July Completion Payment, July English and Maths On Programme Payment, July English and Maths Balancing Payment, July LSF Payment, July Disadvantage Payment, July 16-18 additional payments for employer, July 16-18 additional payments for provider, July 16-18 framework uplift on programme payment, July 16-18 framework uplift balancing payment, July 16-18 framework uplift completion payment";
        }
        $data.=",YTD On Programme Payment, YTD Balancing Payment, YTD Completion Payment, YTD English and Maths On Programme Payment, YTD English and Maths Balancing Payment, YTD LSF Payment, YTD Disadvantage Payment, YTD 16-18 Additional Payments for Employer, YTD 16-18 Additional Payments for Provider, YTD 16-18 framework uplift on programme payment, YTD 16-18 framework uplift balancing payment, YTD 16-18 framework uplift completion payment" . "\r\n";

        $learners = DAO::getResultSet($link, "select distinct TRID, LearnRefNumber, GivenNames, FamilyName, LearnAimRef from monthly_funding");

        foreach($learners as $learner)
        {
            $data .= $learner[1] . "," . $learner[2] . "," . $learner[3] . "," . $learner[4];
            $opp = 0;
            $bal = 0;
            $com = 0;
            $fopp = 0;
            $fbal = 0;
            $fcom = 0;
            $eng = 0;
            $eng_bal = 0;
            $lsf = 0;
            $dis = 0;
            $emp = 0;
            $prov = 0;

            if(strtotime($start_date)>=strtotime("$contract_year-08-01"))
            {
                $funding = DAO::getObject($link, "select OnProgPayment AS OnProgPayment
                                                            ,BalancingPayment AS BalancingPayment
                                                            ,CompletionPayment AS CompletionPayment
                                                            ,EnglishMaths AS EnglishMathsPayment
                                                            ,EnglishMathsBalancing AS EnglishMathsBalancing
                                                            ,LSFPayment AS LSFPayment
                                                            ,(DisadvantagePayment1+DisadvantagePayment2) AS DisadvantagePayment
                                                            ,1618Employer as Employer
                                                            ,1618Provider as Provider
                                                            ,FUOnProgPayment
                                                            ,FUBalancingPayment
                                                            ,FUCompletionPayment
                                                            from monthly_funding where TRID = '$learner[0]' and LearnAimRef = '$learner[4]' and Period = '$contract_year-08-01'");

                if(!isset($funding))
                    pre("select OnProgPayment AS OnProgPayment
                                                            ,BalancingPayment AS BalancingPayment
                                                            ,CompletionPayment AS CompletionPayment
                                                            ,EnglishMaths AS EnglishMathsPayment
                                                            ,EnglishMathsBalancing AS EnglishMathsBalancing
                                                            ,LSFPayment AS LSFPayment
                                                            ,(DisadvantagePayment1+DisadvantagePayment2) AS DisadvantagePayment
                                                            ,1618Employer as Employer
                                                            ,1618Provider as Provider
                                                            ,FUOnProgPayment
                                                            ,FUBalancingPayment
                                                            ,FUCompletionPayment
                                                            from monthly_funding where TRID = '$learner[0]' and LearnAimRef = '$learner[4]' and Period = '$contract_year-08-01'");
                $data.= "," . $funding->OnProgPayment . "," . $funding->BalancingPayment . "," . $funding->CompletionPayment . "," . $funding->EnglishMathsPayment . "," . $funding->EnglishMathsBalancing . "," . $funding->LSFPayment . "," . $funding->DisadvantagePayment . "," . $funding->Employer . "," . $funding->Provider . "," . $funding->FUOnProgPayment . "," . $funding->FUBalancingPayment . "," . $funding->FUCompletionPayment;

                $opp += $funding->OnProgPayment;
                $bal += $funding->BalancingPayment;
                $com += $funding->CompletionPayment;
                $fopp += $funding->FUOnProgPayment;
                $fbal += $funding->FUBalancingPayment;
                $fcom += $funding->FUCompletionPayment;
                $eng += $funding->EnglishMathsPayment;
                $eng_bal += $funding->EnglishMathsBalancing;
                $lsf += $funding->LSFPayment;
                $dis += $funding->DisadvantagePayment;
                $emp += $funding->Employer;
                $prov += $funding->Provider;
            }
            if(strtotime($start_date)>=strtotime("$contract_year-09-01"))
            {
                $funding = DAO::getObject($link, "select OnProgPayment AS OnProgPayment
                                                            ,BalancingPayment AS BalancingPayment
                                                            ,CompletionPayment AS CompletionPayment
                                                            ,EnglishMaths AS EnglishMathsPayment
                                                            ,EnglishMathsBalancing AS EnglishMathsBalancing
                                                            ,LSFPayment AS LSFPayment
                                                            ,(DisadvantagePayment1+DisadvantagePayment2) AS DisadvantagePayment
                                                            ,1618Employer as Employer
                                                            ,1618Provider as Provider
                                                            ,FUOnProgPayment
                                                            ,FUBalancingPayment
                                                            ,FUCompletionPayment
                                                            from monthly_funding where TRID = '$learner[0]' and LearnAimRef = '$learner[4]' and Period = '$contract_year-09-01'");


                $data.= "," . $funding->OnProgPayment . "," . $funding->BalancingPayment . "," . $funding->CompletionPayment . "," . $funding->EnglishMathsPayment . "," . $funding->EnglishMathsBalancing . "," . $funding->LSFPayment . "," . $funding->DisadvantagePayment . "," . $funding->Employer . "," . $funding->Provider . "," . $funding->FUOnProgPayment . "," . $funding->FUBalancingPayment . "," . $funding->FUCompletionPayment;
                $opp += $funding->OnProgPayment;
                $bal += $funding->BalancingPayment;
                $com += $funding->CompletionPayment;
                $fopp += $funding->FUOnProgPayment;
                $fbal += $funding->FUBalancingPayment;
                $fcom += $funding->FUCompletionPayment;
                $eng += $funding->EnglishMathsPayment;
                $eng_bal += $funding->EnglishMathsBalancing;
                $lsf += $funding->LSFPayment;
                $dis += $funding->DisadvantagePayment;
                $emp += $funding->Employer;
                $prov += $funding->Provider;

            }
            if(strtotime($start_date)>=strtotime("$contract_year-10-01"))
            {
                $funding = DAO::getObject($link, "select OnProgPayment AS OnProgPayment
                                                            ,BalancingPayment AS BalancingPayment
                                                            ,CompletionPayment AS CompletionPayment
                                                            ,EnglishMaths AS EnglishMathsPayment
                                                            ,EnglishMathsBalancing AS EnglishMathsBalancing
                                                            ,LSFPayment AS LSFPayment
                                                            ,(DisadvantagePayment1+DisadvantagePayment2) AS DisadvantagePayment
                                                            ,1618Employer as Employer
                                                            ,1618Provider as Provider
                                                            ,FUOnProgPayment
                                                            ,FUBalancingPayment
                                                            ,FUCompletionPayment
                                                            from monthly_funding where TRID = '$learner[0]' and LearnAimRef = '$learner[4]' and Period = '$contract_year-10-01'");
                $data.= "," . $funding->OnProgPayment . "," . $funding->BalancingPayment . "," . $funding->CompletionPayment . "," . $funding->EnglishMathsPayment . "," . $funding->EnglishMathsBalancing . "," . $funding->LSFPayment . "," . $funding->DisadvantagePayment . "," . $funding->Employer . "," . $funding->Provider . "," . $funding->FUOnProgPayment . "," . $funding->FUBalancingPayment . "," . $funding->FUCompletionPayment;
                $opp += $funding->OnProgPayment;
                $bal += $funding->BalancingPayment;
                $com += $funding->CompletionPayment;
                $fopp += $funding->FUOnProgPayment;
                $fbal += $funding->FUBalancingPayment;
                $fcom += $funding->FUCompletionPayment;
                $eng += $funding->EnglishMathsPayment;
                $eng_bal += $funding->EnglishMathsBalancing;
                $lsf += $funding->LSFPayment;
                $dis += $funding->DisadvantagePayment;
                $emp += $funding->Employer;
                $prov += $funding->Provider;
            }
            if(strtotime($start_date)>=strtotime("$contract_year-11-01"))
            {
                $funding = DAO::getObject($link, "select OnProgPayment AS OnProgPayment
                                                            ,BalancingPayment AS BalancingPayment
                                                            ,CompletionPayment AS CompletionPayment
                                                            ,EnglishMaths AS EnglishMathsPayment
                                                            ,EnglishMathsBalancing AS EnglishMathsBalancing
                                                            ,LSFPayment AS LSFPayment
                                                            ,(DisadvantagePayment1+DisadvantagePayment2) AS DisadvantagePayment
                                                            ,1618Employer as Employer
                                                            ,1618Provider as Provider
                                                            ,FUOnProgPayment
                                                            ,FUBalancingPayment
                                                            ,FUCompletionPayment
                                                            from monthly_funding where TRID = '$learner[0]' and LearnAimRef = '$learner[4]' and Period = '$contract_year-11-01'");
                $data.= "," . $funding->OnProgPayment . "," . $funding->BalancingPayment . "," . $funding->CompletionPayment . "," . $funding->EnglishMathsPayment . "," . $funding->EnglishMathsBalancing . "," . $funding->LSFPayment . "," . $funding->DisadvantagePayment . "," . $funding->Employer . "," . $funding->Provider . "," . $funding->FUOnProgPayment . "," . $funding->FUBalancingPayment . "," . $funding->FUCompletionPayment;
                $opp += $funding->OnProgPayment;
                $bal += $funding->BalancingPayment;
                $com += $funding->CompletionPayment;
                $eng += $funding->EnglishMathsPayment;
                $fopp += $funding->FUOnProgPayment;
                $fbal += $funding->FUBalancingPayment;
                $fcom += $funding->FUCompletionPayment;
                $eng_bal += $funding->EnglishMathsBalancing;
                $lsf += $funding->LSFPayment;
                $dis += $funding->DisadvantagePayment;
                $emp += $funding->Employer;
                $prov += $funding->Provider;

            }
            if(strtotime($start_date)>=strtotime("$contract_year-12-01"))
            {
                $funding = DAO::getObject($link, "select OnProgPayment AS OnProgPayment
                                                            ,BalancingPayment AS BalancingPayment
                                                            ,CompletionPayment AS CompletionPayment
                                                            ,EnglishMaths AS EnglishMathsPayment
                                                            ,EnglishMathsBalancing AS EnglishMathsBalancing
                                                            ,LSFPayment AS LSFPayment
                                                            ,(DisadvantagePayment1+DisadvantagePayment2) AS DisadvantagePayment
                                                            ,1618Employer as Employer
                                                            ,1618Provider as Provider
                                                            ,FUOnProgPayment
                                                            ,FUBalancingPayment
                                                            ,FUCompletionPayment
                                                            from monthly_funding where TRID = '$learner[0]' and LearnAimRef = '$learner[4]' and Period = '$contract_year-12-01'");
                $data.= "," . $funding->OnProgPayment . "," . $funding->BalancingPayment . "," . $funding->CompletionPayment . "," . $funding->EnglishMathsPayment . "," . $funding->EnglishMathsBalancing . "," . $funding->LSFPayment . "," . $funding->DisadvantagePayment . "," . $funding->Employer . "," . $funding->Provider . "," . $funding->FUOnProgPayment . "," . $funding->FUBalancingPayment . "," . $funding->FUCompletionPayment;
                $opp += $funding->OnProgPayment;
                $bal += $funding->BalancingPayment;
                $com += $funding->CompletionPayment;
                $eng += $funding->EnglishMathsPayment;
                $fopp += $funding->FUOnProgPayment;
                $fbal += $funding->FUBalancingPayment;
                $fcom += $funding->FUCompletionPayment;
                $eng_bal += $funding->EnglishMathsBalancing;
                $lsf += $funding->LSFPayment;
                $dis += $funding->DisadvantagePayment;
                $emp += $funding->Employer;
                $prov += $funding->Provider;

            }
            if(strtotime($start_date)>=strtotime("$contract_yearp-01-01"))
            {
                $funding = DAO::getObject($link, "select OnProgPayment AS OnProgPayment
                                                            ,BalancingPayment AS BalancingPayment
                                                            ,CompletionPayment AS CompletionPayment
                                                            ,EnglishMaths AS EnglishMathsPayment
                                                            ,EnglishMathsBalancing AS EnglishMathsBalancing
                                                            ,LSFPayment AS LSFPayment
                                                            ,(DisadvantagePayment1+DisadvantagePayment2) AS DisadvantagePayment
                                                            ,1618Employer as Employer
                                                            ,1618Provider as Provider
                                                            ,FUOnProgPayment
                                                            ,FUBalancingPayment
                                                            ,FUCompletionPayment
                                                            from monthly_funding where TRID = '$learner[0]' and LearnAimRef = '$learner[4]' and Period = '$contract_yearp-01-01'");
                $data.= "," . $funding->OnProgPayment . "," . $funding->BalancingPayment . "," . $funding->CompletionPayment . "," . $funding->EnglishMathsPayment . "," . $funding->EnglishMathsBalancing . "," . $funding->LSFPayment . "," . $funding->DisadvantagePayment . "," . $funding->Employer . "," . $funding->Provider . "," . $funding->FUOnProgPayment . "," . $funding->FUBalancingPayment . "," . $funding->FUCompletionPayment;
                $opp += $funding->OnProgPayment;
                $bal += $funding->BalancingPayment;
                $com += $funding->CompletionPayment;
                $eng += $funding->EnglishMathsPayment;
                $fopp += $funding->FUOnProgPayment;
                $fbal += $funding->FUBalancingPayment;
                $fcom += $funding->FUCompletionPayment;
                $eng_bal += $funding->EnglishMathsBalancing;
                $lsf += $funding->LSFPayment;
                $dis += $funding->DisadvantagePayment;
                $emp += $funding->Employer;
                $prov += $funding->Provider;

            }
            if(strtotime($start_date)>=strtotime("$contract_yearp-02-01"))
            {
                $funding = DAO::getObject($link, "select OnProgPayment AS OnProgPayment
                                                            ,BalancingPayment AS BalancingPayment
                                                            ,CompletionPayment AS CompletionPayment
                                                            ,EnglishMaths AS EnglishMathsPayment
                                                            ,EnglishMathsBalancing AS EnglishMathsBalancing
                                                            ,LSFPayment AS LSFPayment
                                                            ,(DisadvantagePayment1+DisadvantagePayment2) AS DisadvantagePayment
                                                            ,1618Employer as Employer
                                                            ,1618Provider as Provider
                                                            ,FUOnProgPayment
                                                            ,FUBalancingPayment
                                                            ,FUCompletionPayment
                                                            from monthly_funding where TRID = '$learner[0]' and LearnAimRef = '$learner[4]' and Period = '$contract_yearp-02-01'");
                $data.= "," . $funding->OnProgPayment . "," . $funding->BalancingPayment . "," . $funding->CompletionPayment . "," . $funding->EnglishMathsPayment . "," . $funding->EnglishMathsBalancing . "," . $funding->LSFPayment . "," . $funding->DisadvantagePayment . "," . $funding->Employer . "," . $funding->Provider . "," . $funding->FUOnProgPayment . "," . $funding->FUBalancingPayment . "," . $funding->FUCompletionPayment;
                $opp += $funding->OnProgPayment;
                $bal += $funding->BalancingPayment;
                $com += $funding->CompletionPayment;
                $eng += $funding->EnglishMathsPayment;
                $fopp += $funding->FUOnProgPayment;
                $fbal += $funding->FUBalancingPayment;
                $fcom += $funding->FUCompletionPayment;
                $eng_bal += $funding->EnglishMathsBalancing;
                $lsf += $funding->LSFPayment;
                $dis += $funding->DisadvantagePayment;
                $emp += $funding->Employer;
                $prov += $funding->Provider;

            }
            if(strtotime($start_date)>=strtotime("$contract_yearp-03-01"))
            {
                $funding = DAO::getObject($link, "select OnProgPayment AS OnProgPayment
                                                            ,BalancingPayment AS BalancingPayment
                                                            ,CompletionPayment AS CompletionPayment
                                                            ,EnglishMaths AS EnglishMathsPayment
                                                            ,EnglishMathsBalancing AS EnglishMathsBalancing
                                                            ,LSFPayment AS LSFPayment
                                                            ,(DisadvantagePayment1+DisadvantagePayment2) AS DisadvantagePayment
                                                            ,1618Employer as Employer
                                                            ,1618Provider as Provider
                                                            ,FUOnProgPayment
                                                            ,FUBalancingPayment
                                                            ,FUCompletionPayment
                                                            from monthly_funding where TRID = '$learner[0]' and LearnAimRef = '$learner[4]' and Period = '$contract_yearp-03-01'");
                $data.= "," . $funding->OnProgPayment . "," . $funding->BalancingPayment . "," . $funding->CompletionPayment . "," . $funding->EnglishMathsPayment . "," . $funding->EnglishMathsBalancing . "," . $funding->LSFPayment . "," . $funding->DisadvantagePayment . "," . $funding->Employer . "," . $funding->Provider . "," . $funding->FUOnProgPayment . "," . $funding->FUBalancingPayment . "," . $funding->FUCompletionPayment;
                $opp += $funding->OnProgPayment;
                $bal += $funding->BalancingPayment;
                $com += $funding->CompletionPayment;
                $fopp += $funding->FUOnProgPayment;
                $fbal += $funding->FUBalancingPayment;
                $fcom += $funding->FUCompletionPayment;
                $eng += $funding->EnglishMathsPayment;
                $eng_bal += $funding->EnglishMathsBalancing;
                $lsf += $funding->LSFPayment;
                $dis += $funding->DisadvantagePayment;
                $emp += $funding->Employer;
                $prov += $funding->Provider;

            }
            if(strtotime($start_date)>=strtotime("$contract_yearp-04-01"))
            {
                $funding = DAO::getObject($link, "select OnProgPayment AS OnProgPayment
                                                            ,BalancingPayment AS BalancingPayment
                                                            ,CompletionPayment AS CompletionPayment
                                                            ,EnglishMaths AS EnglishMathsPayment
                                                            ,EnglishMathsBalancing AS EnglishMathsBalancing
                                                            ,LSFPayment AS LSFPayment
                                                            ,(DisadvantagePayment1+DisadvantagePayment2) AS DisadvantagePayment
                                                            ,1618Employer as Employer
                                                            ,1618Provider as Provider
                                                            ,FUOnProgPayment
                                                            ,FUBalancingPayment
                                                            ,FUCompletionPayment
                                                            from monthly_funding where TRID = '$learner[0]' and LearnAimRef = '$learner[4]' and Period = '$contract_yearp-04-01'");
                $data.= "," . $funding->OnProgPayment . "," . $funding->BalancingPayment . "," . $funding->CompletionPayment . "," . $funding->EnglishMathsPayment . "," . $funding->EnglishMathsBalancing . "," . $funding->LSFPayment . "," . $funding->DisadvantagePayment . "," . $funding->Employer . "," . $funding->Provider . "," . $funding->FUOnProgPayment . "," . $funding->FUBalancingPayment . "," . $funding->FUCompletionPayment;
                $opp += $funding->OnProgPayment;
                $bal += $funding->BalancingPayment;
                $com += $funding->CompletionPayment;
                $fopp += $funding->FUOnProgPayment;
                $fbal += $funding->FUBalancingPayment;
                $fcom += $funding->FUCompletionPayment;
                $eng += $funding->EnglishMathsPayment;
                $eng_bal += $funding->EnglishMathsBalancing;
                $lsf += $funding->LSFPayment;
                $dis += $funding->DisadvantagePayment;
                $emp += $funding->Employer;
                $prov += $funding->Provider;

            }
            if(strtotime($start_date)>=strtotime("$contract_yearp-05-01"))
            {
                $funding = DAO::getObject($link, "select OnProgPayment AS OnProgPayment
                                                            ,BalancingPayment AS BalancingPayment
                                                            ,CompletionPayment AS CompletionPayment
                                                            ,EnglishMaths AS EnglishMathsPayment
                                                            ,EnglishMathsBalancing AS EnglishMathsBalancing
                                                            ,LSFPayment AS LSFPayment
                                                            ,(DisadvantagePayment1+DisadvantagePayment2) AS DisadvantagePayment
                                                            ,1618Employer as Employer
                                                            ,1618Provider as Provider
                                                            ,FUOnProgPayment
                                                            ,FUBalancingPayment
                                                            ,FUCompletionPayment
                                                            from monthly_funding where TRID = '$learner[0]' and LearnAimRef = '$learner[4]' and Period = '$contract_yearp-05-01'");
                $data.= "," . $funding->OnProgPayment . "," . $funding->BalancingPayment . "," . $funding->CompletionPayment . "," . $funding->EnglishMathsPayment . "," . $funding->EnglishMathsBalancing . "," . $funding->LSFPayment . "," . $funding->DisadvantagePayment . "," . $funding->Employer . "," . $funding->Provider . "," . $funding->FUOnProgPayment . "," . $funding->FUBalancingPayment . "," . $funding->FUCompletionPayment;
                $opp += $funding->OnProgPayment;
                $bal += $funding->BalancingPayment;
                $com += $funding->CompletionPayment;
                $eng += $funding->EnglishMathsPayment;
                $fopp += $funding->FUOnProgPayment;
                $fbal += $funding->FUBalancingPayment;
                $fcom += $funding->FUCompletionPayment;
                $eng_bal += $funding->EnglishMathsBalancing;
                $lsf += $funding->LSFPayment;
                $dis += $funding->DisadvantagePayment;
                $emp += $funding->Employer;
                $prov += $funding->Provider;

            }
            if(strtotime($start_date)>=strtotime("$contract_yearp-06-01"))
            {
                $funding = DAO::getObject($link, "select OnProgPayment AS OnProgPayment
                                                            ,BalancingPayment AS BalancingPayment
                                                            ,CompletionPayment AS CompletionPayment
                                                            ,EnglishMaths AS EnglishMathsPayment
                                                            ,EnglishMathsBalancing AS EnglishMathsBalancing
                                                            ,LSFPayment AS LSFPayment
                                                            ,(DisadvantagePayment1+DisadvantagePayment2) AS DisadvantagePayment
                                                            ,1618Employer as Employer
                                                            ,1618Provider as Provider
                                                            ,FUOnProgPayment
                                                            ,FUBalancingPayment
                                                            ,FUCompletionPayment
                                                            from monthly_funding where TRID = '$learner[0]' and LearnAimRef = '$learner[4]' and Period = '$contract_yearp-06-01'");
                $data.= "," . $funding->OnProgPayment . "," . $funding->BalancingPayment . "," . $funding->CompletionPayment . "," . $funding->EnglishMathsPayment . "," . $funding->EnglishMathsBalancing . "," . $funding->LSFPayment . "," . $funding->DisadvantagePayment . "," . $funding->Employer . "," . $funding->Provider . "," . $funding->FUOnProgPayment . "," . $funding->FUBalancingPayment . "," . $funding->FUCompletionPayment;
                $opp += $funding->OnProgPayment;
                $bal += $funding->BalancingPayment;
                $com += $funding->CompletionPayment;
                $fopp += $funding->FUOnProgPayment;
                $fbal += $funding->FUBalancingPayment;
                $fcom += $funding->FUCompletionPayment;
                $eng += $funding->EnglishMathsPayment;
                $eng_bal += $funding->EnglishMathsBalancing;
                $lsf += $funding->LSFPayment;
                $dis += $funding->DisadvantagePayment;
                $emp += $funding->Employer;
                $prov += $funding->Provider;

            }
            if(strtotime($start_date)>=strtotime("$contract_yearp-07-01"))
            {
                $funding = DAO::getObject($link, "select OnProgPayment AS OnProgPayment
                                                            ,BalancingPayment AS BalancingPayment
                                                            ,CompletionPayment AS CompletionPayment
                                                            ,EnglishMaths AS EnglishMathsPayment
                                                            ,EnglishMathsBalancing AS EnglishMathsBalancing
                                                            ,LSFPayment AS LSFPayment
                                                            ,(DisadvantagePayment1+DisadvantagePayment2) AS DisadvantagePayment
                                                            ,1618Employer as Employer
                                                            ,1618Provider as Provider
                                                            ,FUOnProgPayment
                                                            ,FUBalancingPayment
                                                            ,FUCompletionPayment
                                                            from monthly_funding where TRID = '$learner[0]' and LearnAimRef = '$learner[4]' and Period = '$contract_yearp-07-01'");
                $data.= "," . $funding->OnProgPayment . "," . $funding->BalancingPayment . "," . $funding->CompletionPayment . "," . $funding->EnglishMathsPayment . "," . $funding->EnglishMathsBalancing . "," . $funding->LSFPayment . "," . $funding->DisadvantagePayment . "," . $funding->Employer . "," . $funding->Provider . "," . $funding->FUOnProgPayment . "," . $funding->FUBalancingPayment . "," . $funding->FUCompletionPayment;
                $opp += $funding->OnProgPayment;
                $bal += $funding->BalancingPayment;
                $com += $funding->CompletionPayment;
                $fopp += $funding->FUOnProgPayment;
                $fbal += $funding->FUBalancingPayment;
                $fcom += $funding->FUCompletionPayment;
                $eng += $funding->EnglishMathsPayment;
                $eng_bal += $funding->EnglishMathsBalancing;
                $lsf += $funding->LSFPayment;
                $dis += $funding->DisadvantagePayment;
                $emp += $funding->Employer;
                $prov += $funding->Provider;

            }

            $data.= "," . $opp . "," . $bal . "," . $com . "," . $eng . "," . $eng_bal . "," . $lsf . "," . $dis . "," . $emp . "," . $prov. "," . $fopp. "," . $fbal. "," . $fcom;
            $data.="\r\n";
        }
        $data .= "\r\n";
        echo($data);
        return true;
    }
}
?>