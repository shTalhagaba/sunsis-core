<?php
class MonthlyFunding
{
    public static function getMonthlyFunding($link, $start_date, $end_date, $where)
    {
        $opp_total = 0;
        $comp_total = 0;
        $lsf_total = 0;
        $english_total = 0;
        $l03s = Array();
        $learners = Array();
        $learner = Array();
        $current_year = DAO::getSingleValue($link, "select contract_year from contracts order by contract_year desc limit 1");
        $periods = DAO::getSingleColumn($link, "SELECT census_start_date FROM central.lookup_submission_dates WHERE contract_year = $current_year AND submission!='W13';");
        foreach($periods as $period)
        {
            $start_date = $period;
            $funding_period = DAO::getSingleValue($link, "select LAST_DAY('$start_date')");

            // August Calculations
            $sql = "select tr.* from tr
                    inner join contracts on contracts.id = tr.contract_id
                    where funding_type = 1 and tr.id NOT IN (SELECT COALESCE(TRID,0) FROM monthly_funding) $where order by l03 ;";

            //$sql = "select tr.* from tr
            //        inner join contracts on contracts.id = tr.contract_id
            //        where funding_type = 1 $where and (tr.closure_date is null or tr.closure_date >= '$start_date') order by l03;";

            $st = $link->query($sql);
            if($st)
            {
                while($row = $st->fetch())
                {
                    $tr_id = $row['id'];
                    $ilr = DAO::getSingleValue($link, "select ilr from ilr where tr_id = '$tr_id' ORDER BY contract_id DESC, submission DESC LIMIT 1");
                    $ilr = Ilr2020::loadFromXML($ilr);
                    foreach($ilr->LearningDelivery as $LearningDelivery)
                    {
                        if($LearningDelivery->AimType==3)
                        {
                            unset($learner);
                            $learner["Period"] = $period;
                            $learner["TRID"] = $tr_id;
                            $learner["LearnRefNumber"] = $row['l03'];
                            $LearnAimRef = "".$LearningDelivery->LearnAimRef;
                            $LearnAimRefTitle = DAO::getSingleValue($link, "SELECT LearnAimRefTitle FROM lars201718.Core_LARS_LearningDelivery WHERE LearnAimRef = '$LearnAimRef';");
                            if($LearnAimRefTitle=="Functional Skills Qualification in English" or $LearnAimRefTitle=="Functional Skills Qualification in Mathematics")
                            {
                                $learner["DateOfBirth"] = "".$ilr->DateOfBirth;
                                $learner["FamilyName"] = "".$ilr->FamilyName;
                                $learner["GivenNames"] = "".$ilr->GivenNames;
                                $learner["LearnStartDate"] = "".$LearningDelivery->LearnStartDate;
                                $learner["LearnPlanEndDate"] = "".$LearningDelivery->LearnPlanEndDate;
                                $learner["LearnActEndDate"] = "".$LearningDelivery->LearnActEndDate;
                                $learner["CompStatus"] = "".$LearningDelivery->CompStatus;
                                $learner["OrigLearnStartDate"] = "".$LearningDelivery->OrigLearnStartDate;
                                $learner["EnglishMathsBalancing"] = 0;
                                $learner["EnglishMaths"] = 0;
                                $learner["AchievementDate"] = "".$LearningDelivery->AchDate;
                                $learner["FworkCode"] = "".$LearningDelivery->FworkCode;
                                $learner["LearnAimRef"] = "".$LearningDelivery->LearnAimRef;

                                foreach($ilr->LearnerContact as $lc)
                                {
                                    if($lc->LocType==2 and $lc->ContType==2)
                                    {
                                        $learner["Postcode"] = "".$lc->PostCode;
                                    }
                                }

                                if(Date::isDate($learner["OrigLearnStartDate"]))
                                    $learner["ApplicableStartDate"] = "".$LearningDelivery->OrigLearnStartDate;
                                else
                                    $learner["ApplicableStartDate"] = "".$LearningDelivery->LearnStartDate;

                                if($learner["LearnActEndDate"]=="")
                                {
                                    $learner["ActualDuration"] = $planned;
                                    $learner["ApplicableEndDate"] = $learner["LearnPlanEndDate"];
                                }
                                else
                                {
                                    $planned = DAO::getSingleValue($link, "SELECT TIMESTAMPDIFF(MONTH, DATE_ADD(DATE_SUB(LAST_DAY('{$learner['LearnStartDate']}'), INTERVAL 1 MONTH), INTERVAL 1 DAY), IF('{$learner['LearnActEndDate']}'=LAST_DAY('{$learner['LearnActEndDate']}'),DATE_ADD('{$learner['LearnActEndDate']}',INTERVAL 1 DAY),'{$learner['LearnActEndDate']}'));");
                                    $learner["ActualDuration"] = $planned;
                                    if(strtotime($learner["LearnPlanEndDate"])<strtotime($learner["LearnActEndDate"]))
                                    {
                                        $learner["ApplicableEndDate"] = $learner["LearnPlanEndDate"];
                                    }
                                    else
                                    {
                                        $learner["ApplicableEndDate"] = $learner["LearnActEndDate"];
                                    }
                                }
                                $learner["PlannedDuration"] = DAO::getSingleValue($link, "SELECT TIMESTAMPDIFF(MONTH, DATE_ADD(DATE_SUB(LAST_DAY('{$learner['LearnStartDate']}'), INTERVAL 1 MONTH), INTERVAL 1 DAY), IF('{$learner['LearnPlanEndDate']}'=LAST_DAY('{$learner['LearnPlanEndDate']}'),DATE_ADD('{$learner['LearnPlanEndDate']}',INTERVAL 1 DAY),'{$learner['LearnPlanEndDate']}'));");
                                if(isset($learner["OPPAmount"]) and $learner["OPPAmount"]>0)
                                    $learner["OPPAmount"] = 471/$learner["PlannedDuration"];
                                if(strtotime($learner['LearnStartDate']) <= strtotime($funding_period) and strtotime($learner['ApplicableEndDate']) >= strtotime($funding_period) )
                                {
                                    $learner["EnglishMaths"] = isset($learner["OPPAmount"])?$learner["OPPAmount"]:0;
                                }
                                else
                                    $learner["EnglishMaths"] = 0;

                                if(isset($learner["ActualDuration"]) and isset($learner["PlannedDuration"]) and $learner["ActualDuration"] < $learner["PlannedDuration"] and isset($learner["CompStatus"]) and $learner["CompStatus"]==2)
                                {
                                    if(strtotime($learner['LearnActEndDate']) <= strtotime($funding_period) and strtotime($learner['LearnActEndDate']) >= strtotime($start_date) )
                                    {
                                        $bal = $learner["PlannedDuration"] - $learner["ActualDuration"];
                                        $learner["EnglishMathsBalancing"] = $bal * $learner["OPPAmount"];
                                    }
                                }

                                $learners[] = $learner;
                                $english_total += $learner["EnglishMaths"];
                                //if($learner["EnglishMaths"]>0)
                                //$l03s[$learner["LearnRefNumber-"."".$LearningDelivery->LearnAimRef"]] = $learner["EnglishMaths"];
                                //if($learner["LearnRefNumber"]=="000000022075" && $LearningDelivery->LearnAimRef=="60348045")
                                //  pre($learner);

                            }
                        }


                        if($LearningDelivery->AimType==1)
                        {
                            unset($learner);
                            $learner["Period"] = $period;
                            $learner["TRID"] = $tr_id;
                            $learner["LearnRefNumber"] = $row['l03'];
                            $learner["DateOfBirth"] = "".$ilr->DateOfBirth;
                            $learner["FamilyName"] = "".$ilr->FamilyName;
                            $learner["GivenNames"] = "".$ilr->GivenNames;
                            $learner["EnglishMaths"] = 0;
                            $learner["EnglishMathsBalancing"] = 0;
                            $learner["LearnStartDate"] = "".$LearningDelivery->LearnStartDate;
                            $learner["LearnPlanEndDate"] = "".$LearningDelivery->LearnPlanEndDate;
                            $learner["LearnActEndDate"] = "".$LearningDelivery->LearnActEndDate;
                            $learner["OrigLearnStartDate"] = "".$LearningDelivery->OrigLearnStartDate;
                            $learner["AchievementDate"] = "".$LearningDelivery->AchDate;
                            $learner["FworkCode"] = "".$LearningDelivery->FworkCode;
                            $learner["CompStatus"] = "".$LearningDelivery->CompStatus;
                            $learner["LSFPayment"] = 0;
                            $learner["FUOnProgPayment"] = 0;
                            $learner["FUBalancingPayment"] = 0;
                            $learner["FUCompletionPayment"] = 0;
                            $learner["LearnAimRef"] = "".$LearningDelivery->LearnAimRef;
                            foreach($ilr->LearnerContact as $lc)
                            {
                                if($lc->LocType==2 and $lc->ContType==2)
                                {
                                    $learner["Postcode"] = "".$lc->PostCode;
                                }
                            }


                            foreach($LearningDelivery->LearningDeliveryFAM as $LDFAM)
                            {
                                if("".$LDFAM->LearnDelFAMType=="LSF" and "".$LDFAM->LearnDelFAMCode=="1")
                                {
                                    $learner['LSFStart'] = "".$LDFAM->LearnDelFAMDateFrom;
                                    $learner['LSFEnd'] = "".$LDFAM->LearnDelFAMDateTo;

                                    if(strtotime($funding_period)>=strtotime($learner['LSFStart']) and strtotime($funding_period)<=strtotime($learner['LSFEnd']))
                                        $learner["LSFPayment"] = 150;
                                }
                            }

                            $learner["ApplicableStartDate"] = "".$LearningDelivery->LearnStartDate;
                            foreach($LearningDelivery->TrailblazerApprenticeshipFinancialRecord as $FinancialRecord)
                            {
                                if("".$FinancialRecord->TBFinType=="TNP" and ("".$FinancialRecord->TBFinCode=="1" or "".$FinancialRecord->TBFinCode=="3"))
                                {
                                    $learner['TNP1'] = "".$FinancialRecord->TBFinAmount;
                                    if(!Date::isDate($learner["OrigLearnStartDate"]) or "".$FinancialRecord->TBFinCode=="3")
                                    {
                                        $learner["ApplicableStartDate"] = "".$LearningDelivery->LearnStartDate;
                                        $learner["ApplicableTNP1"] =  $learner['TNP1'];
                                    }
                                    else
                                    {
                                        $learner["ApplicableStartDate"] = "".$LearningDelivery->OrigLearnStartDate;
                                        $learner["ApplicableTNP1"] =  MonthlyFunding::getApplicableTNP1($link, $row['l03'], $learner['TNP1'], $row['contract_id'], $tr_id);
                                    }
                                }
                                if("".$FinancialRecord->TBFinType=="TNP" and "".$FinancialRecord->TBFinCode=="2")
                                {
                                    $learner['TNP2'] = "".$FinancialRecord->TBFinAmount;
                                }
                            }

                            $learner['TNP1']=isset($learner['TNP1'])?$learner['TNP1']:0;
                            $learner['ApplicableTNP1']=isset($learner['ApplicableTNP1'])?$learner['ApplicableTNP1']:0;
                            $learner["FUApplicableTNP1"] = 2400;

                            $planned = DAO::getSingleValue($link, "SELECT TIMESTAMPDIFF(MONTH, DATE_ADD(DATE_SUB(LAST_DAY('{$learner['LearnStartDate']}'), INTERVAL 1 MONTH), INTERVAL 1 DAY), IF('{$learner['LearnPlanEndDate']}'=LAST_DAY('{$learner['LearnPlanEndDate']}'),DATE_ADD('{$learner['LearnPlanEndDate']}',INTERVAL 1 DAY),'{$learner['LearnPlanEndDate']}'));");
                            $learner["PlannedDuration"] = $planned;
                            if($learner["LearnActEndDate"]=="")
                            {
                                $learner["ActualDuration"] = $planned;
                                $learner["ApplicableEndDate"] = $learner["LearnPlanEndDate"];
                            }
                            else
                            {
                                $planned = DAO::getSingleValue($link, "SELECT TIMESTAMPDIFF(MONTH, DATE_ADD(DATE_SUB(LAST_DAY('{$learner['LearnStartDate']}'), INTERVAL 1 MONTH), INTERVAL 1 DAY), IF('{$learner['LearnActEndDate']}'=LAST_DAY('{$learner['LearnActEndDate']}'),DATE_ADD('{$learner['LearnActEndDate']}',INTERVAL 1 DAY),'{$learner['LearnActEndDate']}'));");
                                $learner["ActualDuration"] = $planned;
                                if(strtotime($learner["LearnPlanEndDate"])<strtotime($learner["LearnActEndDate"]))
                                {
                                    $learner["ApplicableEndDate"] = $learner["LearnPlanEndDate"];
                                }
                                else
                                {
                                    $learner["ApplicableEndDate"] = $learner["LearnActEndDate"];
                                }
                            }
                            if($learner["ActualDuration"]<1)
                                $learner["ActualDuration"] = 1;
                            if($learner["PlannedDuration"]<1)
                                $learner["PlannedDuration"] = 1;

                            $learner["CompletionPayment"] = 0;
                            if($learner["AchievementDate"]!='')
                                $learner["CompletionDate"] = $learner["AchievementDate"];
                            else
                                $learner["CompletionDate"] = $learner["LearnActEndDate"];

                            if($learner['FworkCode']!="")
                            {
                                $opp = $learner['ApplicableTNP1']*0.8/$learner["PlannedDuration"];
                                $fuopp = $learner['FUApplicableTNP1']*0.8/$learner["PlannedDuration"];
                                if($learner['CompStatus']=="2" and strtotime($learner['CompletionDate']) >= strtotime($start_date) and strtotime($learner['CompletionDate']) <= strtotime($funding_period))
                                {
                                    $learner["CompletionPayment"] = $learner['ApplicableTNP1']*0.2;
                                    $learner["FUCompletionPayment"] = $learner['FUApplicableTNP1']*0.2;
                                }
                            }
                            else
                            {
                                $opp = $learner['ApplicableTNP1']/$learner["PlannedDuration"];
                                $fuopp = 0;
                                if($learner['CompStatus']=="2" and strtotime($learner['CompletionDate']) >= strtotime($start_date) and strtotime($learner['CompletionDate']) <= strtotime($funding_period))
                                {
                                    $learner["CompletionPayment"] = $learner['TNP2'];
                                }
                            }

                            $learner["OPPAmount"] = $opp;
                            $learner["FUOPPAmount"] = $fuopp;

                            if(strtotime($learner['LearnStartDate']) <= strtotime($funding_period) and strtotime($learner['ApplicableEndDate']) >= strtotime($funding_period) )
                            {
                                $learner["OnProgPayment"] = $opp;
                                $learner["FUOnProgPayment"] = $fuopp;
                            }
                            else
                            {
                                $learner["OnProgPayment"] = 0;
                                $learner["FUOnProgPayment"] = 0;
                            }

                            if(isset($learner["ActualDuration"]) and isset($learner["PlannedDuration"]) and $learner["ActualDuration"] < $learner["PlannedDuration"] and isset($learner["CompStatus"]) and $learner["CompStatus"]==2)
                            {
                                if(strtotime($learner['LearnActEndDate']) <= strtotime($funding_period) and strtotime($learner['LearnActEndDate']) >= strtotime($start_date) )
                                {
                                    $bal = $learner["PlannedDuration"] - $learner["ActualDuration"];
                                    $learner["BalancingPayment"] = $bal * $learner["OPPAmount"];
                                    $learner["FUBalancingPayment"] = $bal * $learner["FUOPPAmount"];
                                }
                            }

                            $learners[] = $learner;
                            $opp_total += $learner["OnProgPayment"];
                            $comp_total += $learner["CompletionPayment"];
                            $lsf_total += $learner["LSFPayment"];

                        }
                    }
                }
            }
        }


        DAO::multipleRowInsert($link, "monthly_funding", $learners);

        DAO::execute($link, "UPDATE monthly_funding
        INNER JOIN lars201718.disadvantage ON lars201718.disadvantage.Postcode = monthly_funding.Postcode
        SET monthly_funding.DisadvantageAmount = lars201718.disadvantage.Uplift;");

        DAO::execute($link, "UPDATE monthly_funding SET DisadvantageDate1 = DATE_ADD(LearnStartDate, INTERVAL 90 DAY);
        UPDATE monthly_funding SET DisadvantageDate2 = DATE_ADD(LearnStartDate, INTERVAL 365 DAY);");

        DAO::execute($link, "UPDATE monthly_funding
        INNER JOIN monthly_funding AS mfp ON monthly_funding.LearnRefNumber = mfp.LearnRefNumber AND monthly_funding.LearnAimRef = mfp.LearnAimRef AND monthly_funding.LearnStartDate > mfp.ApplicableEndDate AND monthly_funding.Period=mfp.Period
        SET monthly_funding.DisadvantageDate1 =  DATE_ADD(DATE_ADD(mfp.ApplicableStartDate, INTERVAL 90 DAY), INTERVAL (DATEDIFF(mfp.ApplicableEndDate, monthly_funding.LearnStartDate)) DAY);");

        DAO::execute($link, "UPDATE monthly_funding
        INNER JOIN monthly_funding AS mfp ON monthly_funding.LearnRefNumber = mfp.LearnRefNumber AND monthly_funding.LearnAimRef = mfp.LearnAimRef AND monthly_funding.LearnStartDate > mfp.ApplicableEndDate AND monthly_funding.Period=mfp.Period
        SET monthly_funding.DisadvantageDate2 =  DATE_ADD(DATE_ADD(mfp.ApplicableStartDate, INTERVAL 365 DAY), INTERVAL (DATEDIFF(mfp.ApplicableEndDate, monthly_funding.LearnStartDate)) DAY);");

        DAO::execute($link, "UPDATE monthly_funding SET DisadvantagePayment1 = 0, DisadvantagePayment2 = 0;");

        DAO::execute($link, "UPDATE monthly_funding
        SET DisadvantagePayment1 = DisadvantageAmount/2
        WHERE DisadvantageAmount IS NOT NULL
        AND LearnAimRef = 'ZPROG001'
        AND FworkCode IS NOT NULL
        AND DisadvantageDate1 BETWEEN ApplicableStartDate AND ApplicableEndDate
        AND DisadvantageDate1 BETWEEN Period AND LAST_DAY(Period);");

        DAO::execute($link, "UPDATE monthly_funding
        SET DisadvantagePayment2 = DisadvantageAmount/2
        WHERE DisadvantageAmount IS NOT NULL
        AND LearnAimRef = 'ZPROG001'
        AND FworkCode IS NOT NULL
        AND DisadvantageDate2 BETWEEN ApplicableStartDate AND ApplicableEndDate
        AND DisadvantageDate2 BETWEEN Period AND LAST_DAY(Period);");

        DAO::execute($link, "UPDATE monthly_funding SET AgeBand = TIMESTAMPDIFF(YEAR, DateOfBirth, ApplicableStartDate);");

        DAO::execute($link, "UPDATE monthly_funding SET 1618Provider = 500 WHERE LearnAimRef= 'ZPROG001' AND AgeBand < 19 AND (DisadvantageDate2 BETWEEN Period AND LAST_DAY(Period) OR DisadvantageDate1 BETWEEN Period AND LAST_DAY(Period));");
        DAO::execute($link, "UPDATE monthly_funding SET 1618Employer = 500 WHERE LearnAimRef= 'ZPROG001' AND AgeBand < 19 AND (DisadvantageDate2 BETWEEN Period AND LAST_DAY(Period) OR DisadvantageDate1 BETWEEN Period AND LAST_DAY(Period));");
        DAO::execute($link, "UPDATE monthly_funding SET FUOnProgPayment = 0, FUBalancingPayment = 0, FUCompletionPayment = 0 WHERE LearnAimRef= 'ZPROG001' AND AgeBand >= 19;");



        return $learners;


        /*pre($l03s);
        pre("OnProg=" . $opp_total . " Comp=" . $comp_total . " LSF=" . $lsf_total . " English=" . $english_total);
        pre($learners);*/

    }

    public static function getApplicableTNP1($link, $l03, $tnp1, $contract_id, $tr_id)
    {
        $record_count = DAO::getSingleValue($link, "select count(*) from tr where contract_id = '$contract_id' and status_code = 6 and l03 = '$l03' and id != '$tr_id'");
        if($record_count>0)
        {
            $planned = DAO::getSingleValue($link, "SELECT TIMESTAMPDIFF(MONTH, DATE_ADD(DATE_SUB(LAST_DAY(start_date), INTERVAL 1 MONTH), INTERVAL 1 DAY), IF(target_date=LAST_DAY(target_date),DATE_ADD(target_date,INTERVAL 1 DAY),target_date)) from tr where l03 = '$l03' and status_code = 6 order by start_date desc limit 1;");
            $actual = DAO::getSingleValue($link, "SELECT TIMESTAMPDIFF(MONTH, DATE_ADD(DATE_SUB(LAST_DAY(start_date), INTERVAL 1 MONTH), INTERVAL 1 DAY), IF(closure_date=LAST_DAY(closure_date),DATE_ADD(closure_date,INTERVAL 1 DAY),closure_date)) from tr where l03 = '$l03' and status_code = 6 order by start_date desc limit 1;");
            //if($l03 == "000000010559")
            // pre($planned . "-" .$actual);
            if($planned < 1)
                $planned = 1;
            return $tnp1 - ($tnp1/$planned*$actual);
        }
        else
        {
            return $tnp1;
        }
    }

}