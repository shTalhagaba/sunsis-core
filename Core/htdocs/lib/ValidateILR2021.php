<?php
class ValidateILR2021
{
    public function validate(PDO $link, $ilr)
    {
        $class = new ReflectionClass(__CLASS__);
        $methods = $class->getMethods();
        $rep = 'No Error';
        foreach($methods as $method)
        {
            if(preg_match('/^rule/', $method->getName()) > 0)
            {
                $method_name = $method->getName();

                try
                {
                    $res = $this->$method_name($link, $ilr);
                    if(strpos($res, '|'))
                    {
                        $temp = explode('|', $res);
                        $temp = array_unique($temp);
                        foreach($temp AS $t)
                        {
                            $rep .= "<error>" . $t . "</error>";
                        }
                    }
                    else
                    {
                        if($res!='')
                            $rep .= "<error>" . $res . "</error>";
                    }
                }
                catch(Exception $e)
                {
                    $rep .= "<error>Sunesis Error (" . $method_name . ") " . $e->getMessage() . "</error>";
                }
            }
        }

        if($rep!='No Error')
            $rep = '<report>' . $rep . '</report>';

        return $rep;
    }


    private function rule_Learning_Delivery_Rules($link, $ilr)
    {
        $errors = Array();
        $DateOfBirth = "".$ilr->DateOfBirth;
        $PlanLearnHours = "".$ilr->PlanLearnHours;
        $PlanEEPHours = "".$ilr->PlanEEPHours;
        $EngGrade = "".$ilr->EngGrade;
        $MathGrade = "".$ilr->MathGrade;
        $LearnRefNumber = "".$ilr->LearnRefNumber;
        $LLDDHealthProb = "".$ilr->LLDDHealthProb;
        $no_of_dp_records = DAO::getSingleValue($link, "SELECT * FROM destinations WHERE tr_id IN (SELECT id FROM tr WHERE l03 = '$LearnRefNumber');");
        $LearnerHasFAMEDF2 = false;
        $there_is_no_lldd = true;
        $PriorPostcode = "";

        foreach($ilr->LearnerContact as $lc)
            if(("".$lc->LocType)=='2' && ("".$lc->ContType)=='1')
                $PriorPostcode = "".$lc->Postcode;

        foreach($ilr->LLDDandHealthProblem as $lldd)
            if($lldd->LLDDCat!='')
                $there_is_no_lldd = false;

        foreach($ilr->LearnerFAM as $learnerfam)
            if($learnerfam->LearnFAMType == 'EDF' && $learnerfam->LearnFAMCode=='2')
                $LearnerHasFAMEDF2 = true;

        foreach($ilr->LearningDelivery as $delivery)
        {
            // Fields
            $ProgType = "".$delivery->ProgType;
            $StdCode = "".$delivery->StdCode;
            $LearnAimRef = "".$delivery->LearnAimRef;
            $OrigLearnStartDate = "".$delivery->OrigLearnStartDate;
            $LearnStartDate = "".$delivery->LearnStartDate;
            $LearnStartDate_Date = new Date($LearnStartDate);
            $LearnPlanEndDate = "".$delivery->LearnPlanEndDate;
            $LearnPlanEndDate_Date = new Date($LearnPlanEndDate);
            $there_is_no_end_date = true;
            if(Date::isDate("".$delivery->LearnActEndDate))
            {
                $LearnActEndDate_Date = new Date("".$delivery->LearnActEndDate);
                $LearnActEndDate = "".$delivery->LearnActEndDate;
                $LearnActEndDate6Months = new Date($LearnActEndDate_Date->formatMysql());
                $LearnActEndDate6Months->addMonths(6);

                $there_is_no_end_date = false;
            }
            else
            {
                unset($LearnActEndDate_Date);
                unset($LearnActEndDate);
            }
            if(Date::isDate("".$delivery->AchDate))
            {
                $AchDate_Date = new Date("".$delivery->AchDate);
                $AchDate = "".$delivery->AchDate;
            }
            else
            {
                unset($AchDate_Date);
                unset($AchDate);
            }
            $FundModel = "".$delivery->FundModel;
            $AimType = "".$delivery->AimType;
            $FworkCode = "".$delivery->FworkCode;
            $PwayCode = "".$delivery->PwayCode;
            $PartnerUKPRN="".$delivery->PartnerUKPRN;
            $Outcome = "".$delivery->Outcome;
            $AddHours = "".$delivery->AddHours;
            $CompStatus = "".$delivery->CompStatus;
            $ConRefNumber = "".$delivery->ConRefNumber;
            $DelLocPostCode = "".$delivery->DelLocPostCode;
            $EmpOutcome = "".$delivery->EmpOutcome;
            $EPAOrgID = "".$delivery->EPAOrgID;
            $LearnActEndDate_MySQL = Date::toMySQL($delivery->LearnActEndDate);
            $LessThan12Months = DAO::getSingleValue($link,"SELECT IF(DATE_ADD('$LearnStartDate',INTERVAL 12 MONTH)>DATE_ADD('$LearnActEndDate_MySQL',INTERVAL 1 DAY),1,0);");

            // DD07 Calculation
            if($ProgType==2 || $ProgType==3 || $ProgType==10 || $ProgType==20 || $ProgType==21 || $ProgType==22 || $ProgType==23 || $ProgType==25)
                $DD07 = "Y";
            else
                $DD07 = "N";
            // DD10,DD04 Calculation
            $DD04= new Date('2099-01-01');
            $DD10="N";
            $learner_has_a_core_aim = false;
            $learner_has_app_aim = false;
            $all_aims_are_closed = true;
            foreach($ilr->LearningDelivery as $deliverydd10)
            {
                $DD10FundModel = "".$deliverydd10->FundModel;
                $AimTypedd10 = "".$deliverydd10->AimType;
                $ProgTypedd10 = "".$deliverydd10->ProgType;
                $FworkCodedd10 = "".$deliverydd10->FworkCode;
                $PwayCodedd10 = "".$deliverydd10->PwayCode;
                $There_is_a_matching_adl_prog_aim = false;
                if($deliverydd10->AimType=='5')
                {
                    $learner_has_a_core_aim = true;
                }
                if($deliverydd10->AimType=='3')
                {
                    $xpath = $ilr->xpath("/Learner/LearningDelivery[AimType='1' and FworkCode='$FworkCodedd10' and ProgType='$ProgTypedd10' and PwayCode='$PwayCodedd10']/LearninfDeliveryFAM[LearnDelFAMType='ADL']/LearnDelFAMCode");
                    if(isset($xpath[0]) && $xpath[0]=='1')
                        $There_is_a_matching_adl_prog_aim = true;
                }
                $there_is_no_record_of_wpl = true;
                foreach($deliverydd10->LearningDeliveryFAM as $LearnDelFAM)
                {
                    if($DD10FundModel=='99' && ($AimTypedd10=='1' || $AimTypedd10=='4') && $LearnDelFAM->LearnDelFAMType=="ADL" && $LearnDelFAM->LearnDelFAMCode=="1")
                        $DD10 = "Y";
                    elseif($DD10FundModel=='99' && $AimTypedd10=='3' && $There_is_a_matching_adl_prog_aim)
                        $DD10 = "Y";
                    else
                        $DD10 = "N";
                }
                //DD04
                if($AimTypedd10==1 && $ProgTypedd10!='' && $DD04->after($deliverydd10->LearnStartDate))
                    $DD04 = new Date("".$deliverydd10->LearnStartDate);
                if($deliverydd10->ProgType=='2' or $deliverydd10->ProgType=='3' or $deliverydd10->ProgType=='20' or $deliverydd10->ProgType=='21' or $deliverydd10->ProgType=='22' or $deliverydd10->ProgType=='23' or $deliverydd10->ProgType=='25')
                    $learner_has_app_aim = true;
                if($deliverydd10->LearnActEndDate=='' or $deliverydd10->LearnActEndDate=='dd/mm/yyyy' or $deliverydd10->LearnActEndDate=='undefined')
                    $all_aims_are_closed = false;
            }

            // RESTART
            $Restart = false;
            $there_is_an_open_act = false;
            $there_is_no_record_of_act = true;
            foreach($delivery->LearningDeliveryFAM as $LearnDelFAM)
            {
                if($LearnDelFAM->LearnDelFAMType=="RES" && $LearnDelFAM->LearnDelFAMCode=="1")
                    $Restart = true;

                if($LearnDelFAM->LearnDelFAMType=="ACT" && ($LearnDelFAM->LearnDelFAMDateTo=='' || $LearnDelFAM->LearnDelFAMDateTo=='dd/mm/yyyy'))
                {
                    $there_is_an_open_act = true;
                }

                if($LearnDelFAM->LearnDelFAMType=="ACT" && Date::isDate("".$LearnDelFAM->LearnDelFAMDateFrom))
                {
                    $there_is_no_record_of_act = false;
                }
            }
            if($there_is_no_record_of_act==false and $there_is_no_end_date and $there_is_an_open_act==false and $AimType==1)
            {
                $errors[] = "R113: If the Learning actual end date is not known and there is at least one ACT record for the aim, then the Date applies to for the latest ACT record must be NULL - " . $delivery->LearnAimRef;
            }

            // Learning Delivery Rules in Alphabetical Order

            // AchDate_02
            if(isset($AchDate_Date))
                if($AchDate_Date->after("31/07/2022"))
                    $errors[] = "AchDate_02: If returned, the Achievement date should be before the current teaching year end date";

            // AchDate_03
            if(isset($AchDate))
                if($LearnStartDate_Date->after(Date::toShort($AchDate)))
                    $errors[] = "AchDate_03: If returned, the Achievement date must not be before the Learning start date";

            // AchDate_04
            if(isset($AchDate) && (!isset($LearnActEndDate)))
                $errors[] = "AchDate_04: If the Achievement date is returned, the Learning actual end date must be returned";

            // AchDate_05
            if(isset($LearnActEndDate) && isset($AchDate))
                if($AchDate_Date->before(Date::toMySQL($LearnActEndDate)))
                    $errors[] = "AchDate_05: If returned, the Achievement date must be on or after the Learning actual end date";

            //AchDate_07
            if(isset($AchDate))
                if($AchDate_Date->after(date('Y-m-d')))
                    $errors[] = "AchDate_07: If returned, the Achievement date must not be after the file preparation date";

            // AchDate_08
            if( ($ProgType=='24' || ($ProgType=='25' and $FundModel=='81')) && $AimType=='1' && $Outcome == '1' && (!isset($AchDate)))
                $errors[] = "AchDate_08: The Achievement date must be completed for Trailblazer apprenticeship and traineeship programmes with an Outcome of achieved.";

            // AchDate_09
            if(isset($AchDate) && isset($LearnStartDate) && $LearnStartDate_Date->after("2015-07-31") && $ProgType!='24' && $ProgType!='25' && $AimType=='1')
                $errors[] = "AchDate_09: The Achievement date must not be completed for aims that start after  31-07-2015 that are not traineeship or Trailblazer programme aims";

            //AchDate_10
            if($ProgType=='24' && $AimType=='1' && isset($AchDate) && isset($LearnActEndDate) && $AchDate_Date->after($LearnActEndDate6Months->formatMySQL()))
                $errors[] = "AchDate_10: The Achievement date must be within 6 months of a traineeship programme Learning actual end date";

            // AddHours_1
            if(($FundModel=='35' || $FundModel=='36' || $FundModel=='81') && $AddHours!='' && $LearnStartDate_Date->before("2015-08-01"))
                $errors[] = "AddHours_1: The Additional delivery hours must only be recorded for learning aims with a Learning start date on or after 1 August 2015";

            // AddHours_2
            if(($FundModel=='25' || $FundModel=='82' || $FundModel=='70' || $FundModel=='10' || $FundModel=='99') && $AddHours!='')
                $errors[] = "AddHours_2: The Additional delivery hours must not be recorded for EFA, ESF, Community Learning or non funded provision.";

            //AddHours_3
            $BasicSkillsType = DAO::getSingleValue($link, "SELECT BasicSkillsType FROM lars201718.Core_LARS_AnnualValue WHERE LearnAimRef = '$LearnAimRef' and EffectiveFrom >= '2017-08-01' LIMIT 0,1");
            if($BasicSkillsType!='36' && $BasicSkillsType!='37' && $BasicSkillsType!='38' && $BasicSkillsType!='39' && $AddHours!='')
                $errors[] = "AddHours_3: The Additional delivery hours must only be recorded for ESOL qualification aims ";

            //AddHours_4
            $search_days = Date::dateDiffInfo($LearnPlanEndDate_Date, $LearnStartDate_Date);
            if(isset($search_days['days']))
            {
                $Days_Between_Start_Planned = $search_days['days']+1;
                if($AddHours>60 && ($AddHours/$Days_Between_Start_Planned)>24)
                    $errors[] = "AddHours_4: If the  Additional delivery hours are greater than 60, then the value divided by the number of days of study must not be greater than 24";
            }

            // AimSeqNumber_02 is covered through Batch Generation

            // AimType_01
            if($AimType!='1' && $AimType!='3' && $AimType!='4' && $AimType!='5')
                $errors[] = "AimType_01: This Aim type is not a valid code";

            //AimType_05
            if( ($AimType=='35' || $AimType=='81' || $AimType=='70' || $AimType=='10' || $AimType=='99' || $AimType=='36') && $AimType=='5')
                $errors[] = "AimType_05: The Aim type must not be 'Core aim' ";

            // CompStatus_01
            if($CompStatus!='1' && $CompStatus!='2' && $CompStatus!='3' && $CompStatus!='6')
                $errors[] = "CompStatus_01: The Completion status must be a valid lookup";

            // CompStatus_02
            if($CompStatus=='1' && isset($LearnActEndDate) && $FundModel!="36")
                $errors[] = "CompStatus_02: If the Learning actual end date is returned, the Completion status must not be 'the learner is continuing or intending to continue the learning activities leading to the learning aim'";

            // CompStatus_03
            if($CompStatus!='1' && (!isset($LearnActEndDate)))
                $errors[] = "CompStatus_03: If the Learning actual end date is not returned, the Completion status must be 'learner is continuing or intending to continue the learning activities leading to the learning aim'";

            // CompStatus_04
            if($Outcome=='' && $CompStatus!='1')
                $errors[] = "ComptStatus_04: If the Outcome is not returned, the Completion status must be 'the learner is continuing or intending to continue the learning activities leading to the learning aim'";

            // CompStatus_05
            if($CompStatus=='1' && $Outcome!='' && $FundModel!="36")
                $errors[] = "CompStatus_05: If the Completion status is 'the learner is continuing or intending to continue the learning activities leading to the learning aim', the Outcome must not be returned";

            // CompStatus_06
            if( ($CompStatus=='3' || $CompStatus=='6' ) && ($Outcome=='1' || $Outcome=='6' || $Outcome=='7' || $Outcome=='8'))
                $errors[] = "CompStatus_06: If the Completion status is 'withdrawn' or 'break in learning', the Outcome must not be 'Achieved' or 'Learning activities are complete but outcome is not yet known'";

            // ConRefNumber_01 No Access to FCT Database

            // ConRefNumber_02
            if($FundModel=='70' && $ConRefNumber=='')
                $errors[] = "ConRefNumber_02: The Contract reference number must be returned";

            // ConRefNumber_03
            if( ($FundModel=='10' || $FundModel=='25' || $FundModel=='35' || $FundModel=='36' || $FundModel=='81' || $FundModel=='82' || $FundModel=='99') && $ConRefNumber!='')
                $errors[] = "ConRefNumber_03: The Contract reference number must not be returned";





            // EngGrade_01
            if(($FundModel=='25' || $FundModel=='82') && $EngGrade=='')
                $errors[] = "EngGrade_01: If the learner's learning aim is EFA funded, the GCSE English qualification grade must be returned";

            // EngGrade_03
            if($LearnerHasFAMEDF2 == false && ($EngGrade=='D' || $EngGrade=='DD' || $EngGrade=='DE' || $EngGrade=='E' || $EngGrade=='EE' || $EngGrade=='EF' || $EngGrade=='F' || $EngGrade=='FF' || $EngGrade=='FG' || $EngGrade=='G' || $EngGrade=='GG' || $EngGrade=='N' || $EngGrade=='U'))
                $errors[] = "EngGrade_03: If the GCSE English qualification grade is 'D' or below then a Learner FAM Type of Eligibility for EFA disadvantage funding must be returned";

            // MathGrade_01
            if(($FundModel=='25' || $FundModel=='82') && $MathGrade=='')
                $errors[] = "MathGrade_01: If the learner's learning aim is EFA funded, the GCSE Math qualification grade must be returned";

            //Outcome_07
            if($AimType=='1' && $ProgType=='24' && ($Outcome=='2' || $Outcome=='3' || $Outcome=='6' || $Outcome=='7') && (!$no_of_dp_records))
                $errors[] = "Outcome_07: If the Outcome of a traineeship programme is 'Not achieved' there must be a Destination and progression record recorded";

            // DelLocPostCode_10 Hub only rule

            // DelLocPostCode_11
            if(!$this->checkPostcode($DelLocPostCode))
                $errors[] = "DelLocPostCode_11: The Postcode must conform to the valid postcode format";

            // DelLocPostCode_13
            if($FundModel=='70' && $DelLocPostCode=='ZZ99 9ZZ')
                $errors[] = "DelLocPostCode_13: The Delivery location postcode of ZZ99 9ZZ must not be used";

            // DelLocPostCode_14 No access to FCT

            // DelLocPostCode_15 No access to FCT

            // EmpOutcome_01
            if( ($FundModel=='25' || $FundModel=='82' || $FundModel=='10' || $FundModel=='99') && $EmpOutcome!='')
                $errors[] = "EmpOutcome_01: The Employment outcome must not be returned";

            // EmpOutcome_02
            if( ($FundModel=='35' || $FundModel=='36' || $FundModel=='81' || $FundModel=='10') && $EmpOutcome!='' && $EmpOutcome!='1' && $EmpOutcome!='2')
                $errors[] = "EmpOutcome_02: If returned, the Employment outcome must be a valid lookup";

            $there_is_no_record_of_wpl = true;
            $there_is_no_record_of_hhs = true;
            $there_is_no_record_of_ffi = true;
            $there_is_no_record_of_ldm_034 = true;
            $there_is_no_record_of_ADL = true;
            $there_is_no_record_of_ACT = true;
            $there_is_no_record_of_sof_107 = true;
            foreach($delivery->LearningDeliveryFAM as $LearnDelFAM)
            {
                if($LearnDelFAM->LearnDelFAMType=="WPL" && $LearnDelFAM->LearnDelFAMCode=='1')
                    $there_is_no_record_of_wpl = false;
                if($LearnDelFAM->LearnDelFAMType=="FFI" && $LearnDelFAM->LearnDelFAMCode!='')
                    $there_is_no_record_of_ffi = false;
                if($LearnDelFAM->LearnDelFAMType=="LDM" && $LearnDelFAM->LearnDelFAMCode=='034')
                    $there_is_no_record_of_ldm_034 = false;
                if($LearnDelFAM->LearnDelFAMType=="HHS" && $LearnDelFAM->LearnDelFAMCode!='')
                    $there_is_no_record_of_hhs = false;
                if($LearnDelFAM->LearnDelFAMType=="ADL" && $LearnDelFAM->LearnDelFAMCode!='')
                    $there_is_no_record_of_ADL = false;
                if($LearnDelFAM->LearnDelFAMType=="ACT" && $LearnDelFAM->LearnDelFAMCode!='')
                    $there_is_no_record_of_ACT = false;
                if($LearnDelFAM->LearnDelFAMType=="SOF" && $LearnDelFAM->LearnDelFAMCode=='107')
                    $there_is_no_record_of_sof_107 = false;
            }

            // EmpOutcome_03
            if($DD07=="Y" && $EmpOutcome!='')
                $errors[] = "EmpOutcome_03: If the learning aim is an apprenticeship the Employment outcome must not be returned";

            // FundModel_01
            if($FundModel!='10' && $FundModel!='25' && $FundModel!='35' && $FundModel!='36' && $FundModel!='70' && $FundModel!='81' && $FundModel!='82' && $FundModel!='99')
                $errors[] = "FundModel_01: The Funding model must be a valid lookup";

            // FundModel_03
            if($FundModel!='99' && (!$there_is_no_record_of_ADL))
                $errors[] = "FundModel_03: The Funding model must be 'No Skills Funding Agency or EFA funding' if the learning aim is financed by a 24+ Advanced Learning Loan.";

            // FundModel_04
            if( ($FundModel=='25' || $FundModel=='82') && $DD07=='Y')
                $errors[] = "FundModel_04: If the learning aim is part of an apprenticeship, the Funding model must not be 'EFA funding'.";

            //FwordCode_01
            if($FworkCode=='' && $DD07=="Y" && $ProgType!='24' && $ProgType!='25')
                $errors[] = "FworkCode_01: The Framework code must be returned for all aims that are part of an apprenticeship (not including Trailblazer apprenticeships)";

            //FwordCode_07
            if($FworkCode!='' && ($ProgType=='' || $ProgType=='24' || $ProgType=='25'))
                $errors[] = "FworkCode_02: The Framework code must not be returned for all aims that are not part of a programme,  or aims that are part of a traineeship programme or Trailblazer apprenticeship";

            //FwordCode_05
            //if($AimType=='3' && $DD07=="Y")
            //{
            //	$FrameworkAimsMatchingAims = DAO::getSingleValue($link, "SELECT COUNT(*) FROM lars201516.Core_LARS_FrameworkAims WHERE LearnAimRef = '$LearnAimRef' AND ProgType='$ProgType' AND FworkCode='$FworkCode' AND PwayCode='$PwayCode';");
            //	if(!$FrameworkAimsMatchingAims)
            //		$errors[] = "FworkCode_05: If the learning aim is part of an apprenticeship programme (not including Trailblazer apprenticeships), the Framework code must be a valid lookup in the Framework aims table in LARS for this Programme type and Apprenticeship pathway code.";
            //}

            //LearnActEndDate_01
            if(isset($LearnActEndDate) && $LearnActEndDate_Date->before(Date::toMySQL($LearnStartDate)))
                $errors[] = "LearnActEndDate_01: If returned, the Learning actual end date must not be before the Learning start date";

            //LearnActEndDate_03
            //if(isset($LearnActEndDate) && $LearnActEndDate_Date->after("31/07/2019"))
            //    $errors[] = "LearnActEndDate_03: The Learning actual end date " . $LearnActEndDate_Date->formatShort() . " must be before the end of the following teaching year ";

            //LearnActEndDate_04
            //if(isset($LearnActEndDate) && $LearnActEndDate_Date->after(date('Y-m-d')))
            //	$errors[] = "LearnActEndDate_03: The Learning actual end date must be before the end of the following teaching year ";

            // LearnAimRef_01
            $ValidLookupOnLARS = DAO::getSingleValue($link, "SELECT LearnAimRef FROM lars201718.Core_LARS_LearningDelivery WHERE LearnAimRef = '$LearnAimRef';");
            if($ValidLookupOnLARS=='')
                $errors[] = "LearnAimRef_01: The Learning aim reference must be a valid lookup on LARS (".$LearnAimRef.")";

            //LearnAimRef_03
            $MatchingCategory_Apprenticeship = DAO::getSingleValue($link, "SELECT COUNT(*) FROM lars201718.Core_LARS_Validity WHERE LearnAimRef = '$LearnAimRef' and ValidityCategory='APPRENTICESHIPS';");
            if($LearnStartDate_Date->before("01/08/2011") && $FundModel=="35" && $DD07=="Y" && $AimType==3 && (!$MatchingCategory_Apprenticeship))
                $errors[] = "LearnAimRef_03: If the learning aim is part of an adult skills funded apprenticeship programme and started before 1st August 2011, the learning aim reference must exists in the validity details table on LARS";

            //LearnAimRef_05
            //if($LearnStartDate_Date->before("2011-08-01") && $FundModel=="35" && (!$MatchingCategory)) // todo
            //  $errors[] = "LearnAimRef_05: If the learning aim is funded through the Adult Skills funding model and not part of an Apprenticeship or Traineeship, is not OLASS funded, the learner not recorded as in receipt of JSA, ESA, another state benefit or Universal credit, the learning aim is not recorded with LDM327 or LDM328 and started before 1st August 2011, the learning aim reference must in the validity details table in LARS";

            //LearnAimRef_36
            //$MatchingCategory_Any = DAO::getSingleValue($link, "SELECT COUNT(*) FROM lars201516.Core_LARS_Validity WHERE LearnAimRef = '$LearnAimRef' and ValidityCategory='ANY';");
            //if($LearnStartDate_Date->before("2011-08-01") && ($FundModel=="81" || $FundModel=="70" || $FundModel=="99") && (!$MatchingCategory_Any) && $DD10!='Y')
            //	$errors[] = "LearnAimRef_36: If the learning aim is ESF, Other Skills Funding Agency funding or no funding (excluding aims financed by 25+ advanced Learning Loan) and started before 1st August 2011, the learning aims reference code must exists in the validity details table on LARS";

            //AimType_01 is Covered through dropdown
            //AimType_05
            if($AimType=='5' && ($FundModel=='35' || $FundModel=='81' || $FundModel=='70' || $FundModel=='10' || $FundModel=='99'))
                $errors[] = "AimType_05: The Aim type must not be core aim";

            //AimType_06
            if($AimType=='5' && $LearnStartDate_Date->after("31/08/2022") && $FundModel=='25')
            {
                $errors[] = "AimType_06: The Learning Start Date of the core aim should not be in the following teaching year";
            }

            //AimSeqNumber_02 covered by batch generation
            //LearnStartDate_02
            if($LearnStartDate_Date->before("2005-08-01"))
                $errors[] = "LearnStartDate_02: The learning start date must not be more than 10 years before the start of the current teaching year";

            //LearnStartDate_03
            if($DD07=="N" && $ProgType!='24' && $LearnStartDate_Date->after("31/07/2022"))
                $errors[] = "LearnStartDate_03: If the learning aim is not part of an apprenticeship or traineeship, the learning start date must not be after the current teaching year 1";

            //LearnStartDate_05
            if($DateOfBirth!='' && $LearnStartDate_Date->before($DateOfBirth))
                $errors[] = "LearnStartDate_05: The learning start date must be after the learner's date of birth";

            //LearnStartDate_06
            $EffectiveTo = DAO::getSingleValue($link, "SELECT EffectiveTo FROM lars201718.Core_LARS_Framework WHERE FworkCode='$FworkCode' and ProgType='$ProgType' and PwayCode='$PwayCode'");
            if($DD07=="Y" && $AimType=='1' && $EffectiveTo!='' && $EffectiveTo!='NULL' && $LearnStartDate_Date->after($EffectiveTo) && $ProgType!='25' && (!$Restart))
                $errors[] = "LearnStartDate_06: If the learner is undertaking an apprenticeship programme, not including Trailblzaer apprenticeship, the learning start date of the programme must not be after the effective to date in the framework table in LARS, for this framework, if the learner is a new start";
            /*
                        //LearnStartDate_07
                        $EffectiveTo = DAO::getSingleValue($link, "SELECT EffectiveTo FROM lars201617.Core_LARS_FrameworkAims WHERE LearnAimRef = '$LearnAimRef' and FworkCode='$FworkCode' and ProgType='$ProgType' and PwayCode='$PwayCode'");
                        if($DD07=="Y" && $AimType=='3' && $EffectiveTo!='' && $EffectiveTo!='NULL' && $DD04->after($EffectiveTo) && $ProgType!='25' && (!$Restart))
                            $errors[] = "LearnStartDate_07: If the learning aim is part of an apprenticeship programme, not including trailblazer apprenticeship, the learning start date of the programme must not be after the effective to date in the framework aims table in LARS, for this aim on this framework, if the learner is a new start";
            */
            //WorkPlaceEmpId_05
            foreach($delivery->LearningDeliveryWorkPlacement as $workplacement)
            {
                if($workplacement->WorkPlaceStartDate!='' && $workplacement->WorkPlaceStartDate!='dd/mm/yyyy' && $workplacement->WorkPlaceStartDate!='undefined')
                    if($ProgType=='24' && ("".$workplacement->WorkPlaceEmpId==''))
                        $errors[] = "WorkPlaceEmpId_05: If the learner is undertaking a traineeship programme then there must be an Employer identifier which applies to the workplacement record ";
            }

            //LearnDelFAMType_02
            if($FundModel=='35' && $there_is_no_record_of_ffi)
                $errors[] = "LearnDelFAMType_02: If the learning aim is Adult Skills funded, the Full or co-funding indicator must be returned (" . $delivery->LearnAimRef . ")";

            //LearnDelFAMType_22
            if($FundModel!='35' && $FundModel!='99' && $there_is_no_record_of_ffi == false)
                $errors[] = "LearnDelFAMType_22: If the learning aim is not Adult Skills funded, the Full or co-funding indicator must not be returned (" . $delivery->LearnAimRef . ")";

            //LearnDelFAMType_32
            //if($FundModel=='35' && $DD07=='Y' && $there_is_no_record_of_wpl)
            //$errors[] = "LearnDelFAMType_32: If the learner is undertaking an Adult Skills funded apprenticeship, the Workplace indicator must be returned (" . $delivery->LearnAimRef . ")";

            //LearnDelFAMType_33
            if($there_is_no_record_of_wpl== false && $FundModel!='35' && $FundModel!='99')
                $errors[] = "LearnDelFAMType_33: If the learning aim is not Adult Skills funded, the workplace learning  indicator must not be returned (" . $delivery->LearnAimRef . ")";

            //LearnDelFAMType_47
            if($there_is_no_record_of_wpl== false && $ProgType=='24')
                $errors[] = "LearnDelFAMType_47: The workplace learning indicator must not be used for a traineeship (" . $delivery->LearnAimRef . ")";

            $isComponentAim = false;
            if(($AimType == '3' || $AimType == '5') && !is_null($ProgType) && $ProgType != '')
                $isComponentAim = true;

            //LearnDelFAMType_44
            if($LearnStartDate_Date->after("2015-07-31") && ($FundModel == '35' || $FundModel == '70') && $there_is_no_record_of_hhs && $there_is_no_record_of_ldm_034 && !$isComponentAim)
                $errors[] = "LearnDelFAMType_44: The household situation must be returned (" . $delivery->LearnAimRef . ")";

            $EnglishMathBSTypes = Array('01','11','13','20','23','24','29','31','02','12','14','19','21','25','30','32','33','34','35','1','2');
            //LearnDelFAMType_64
            if($FundModel=='36' and ($AimType=='1' or in_array($BasicSkillsType,$EnglishMathBSTypes)) and  $there_is_no_record_of_ACT)
                $errors[] = "LearnDelFAMType_64: Apprenticeship contract type must be returned (" . $delivery->LearnAimRef . ")";

            $DateOfBirth_25_Under19 = DAO::getSingleValue($link, "SELECT IF(DATE_ADD('$DateOfBirth',INTERVAL 19 YEAR)>DATE_ADD('2021-08-31',INTERVAL 1 DAY),1,0);");

            //DateOfBirth_20
            if( ($FundModel == '25' or $FundModel == '81') && $DateOfBirth!='' && $DateOfBirth_25_Under19 == '1' && $there_is_no_record_of_sof_107==true)
                $errors[] = "DateOfBirth_20: The learner is under 19 and the Source of funding is not the EFA (" . $delivery->LearnAimRef . ")";


            //DateOfBirth_25
            if($FundModel == '35' && $DateOfBirth!='' && $DD07 == 'N' && $DateOfBirth_25_Under19 == '1' && $there_is_no_record_of_ldm_034)
                $errors[] = "DateOfBirth_25: The learner is under 19 (" . $delivery->LearnAimRef . ")";

            //DateOfBirth_38
            if($LessThan12Months != '')
            {
                $DateOfBirth_38_Under19 = DAO::getSingleValue($link, "SELECT IF(DATE_ADD('$DateOfBirth',INTERVAL 19 YEAR)>DATE_ADD('$LearnStartDate',INTERVAL 1 DAY),1,0);");
                if(($FundModel == '35' || $FundModel == '81') && $DateOfBirth!='' && $DateOfBirth_38_Under19 == '1' && $DD07 == 'Y' && $AimType == '1' && $LessThan12Months && $Outcome == '1' && !$Restart)
                    $errors[] = "DateOfBirth_38: The learner is under 19 and the learning start date and learning actual end date do not reach the minimum duration of an apprenticeship (" . $delivery->LearnAimRef . ")";
            }

            //DateOfBirth_46
            $DateOfBirth_46_Under16 = DAO::getSingleValue($link, "SELECT IF(DATE_ADD('$DateOfBirth',INTERVAL 16 YEAR)>DATE_ADD('$LearnStartDate',INTERVAL 1 DAY),1,0);");
            $LearnStartDate_46_date = $LearnStartDate_Date;
            $LearnStartDate_46_date->addDays(371);
            //$errors[] = $LearnPlanEndDate;
            if(!$Restart and $LearnStartDate_Date->after("2016-07-31") and $DateOfBirth_46_Under16 != '1' and ($FundModel=='36' or $FundModel=='81') and $ProgType=='25' and $AimType=='1' and $LearnStartDate_46_date->after(Date::toMySQL($LearnPlanEndDate)))
                $errors[] = "DateOfBirth_46: The planned duration of the programme does not reach the required minimum duration for an apprenticeship standard (" . $delivery->LearnAimRef . ")";
            $LearnStartDate_46_date->subtractDays(371);

            //OrigLearnStartDate_02
            if(($FundModel == '35' || $FundModel == '81' || $FundModel == '99') && !is_null($OrigLearnStartDate) && $OrigLearnStartDate != '' && ($LearnStartDate_Date->before(new Date($OrigLearnStartDate)) || $LearnStartDate_Date->equals(new Date($OrigLearnStartDate))))
                $errors[] = "OrigLearnStartDate_02: The original learning start date is on or after the Learning start date (" . $delivery->LearnAimRef . ")";


            //StdCode_01
            if($ProgType == '25' and $StdCode=='')
                $errors[] = "StdCode_01: If the learning aim is part of an apprenticeship standard programme, then the standard code must be returned (" . $delivery->LearnAimRef . ")";

            // AFinType_10
            /*$price_record_is_present = false;
            foreach($delivery->TrailblazerApprenticeshipFinancialRecord as $TrailblazerApprenticeshipFinancialRecord)
            {
                if($TrailblazerApprenticeshipFinancialRecord->TBFinType=="TNP" and ($TrailblazerApprenticeshipFinancialRecord->TBFinCode=='2' or $TrailblazerApprenticeshipFinancialRecord->TBFinCode=='4'))
                    $price_record_is_present = true;
            }
            if($ProgType=="25" and $AimType=="1" and $price_record_is_present==false)
                $errors[] = "AFinType_10: The end-point assessment price should be returned (" . $delivery->LearnAimRef . ")";
            */

            // EPAOrgId_02
            $price_record_is_present = false;
            foreach($delivery->TrailblazerApprenticeshipFinancialRecord as $TrailblazerApprenticeshipFinancialRecord)
            {
                if($TrailblazerApprenticeshipFinancialRecord->TBFinType=="TNP" and ($TrailblazerApprenticeshipFinancialRecord->TBFinCode=='2' or $TrailblazerApprenticeshipFinancialRecord->TBFinCode=='4'))
                    $price_record_is_present = true;
            }
            if($price_record_is_present and $EPAOrgID=='')
                $errors[] = "EPAOrgId_02: The End point assessment organisation payment record has been returned but there is no record of the End point assessment organisation. (" . $delivery->LearnAimRef . ")";


            // TBFinType_09
            $apprenticeship_financial_record_is_present = false;
            $price_record_is_present = false;
            $price_record_on_learning_delivery_is_present = false;
            foreach($delivery->TrailblazerApprenticeshipFinancialRecord as $TrailblazerApprenticeshipFinancialRecord)
            {
                $apprenticeship_financial_record_is_present = true;
                $TBFinDate_Date = new Date($TrailblazerApprenticeshipFinancialRecord->TBFinDate);
                if($TrailblazerApprenticeshipFinancialRecord->TBFinType=="TNP")
                    $price_record_is_present = true;
                if($TrailblazerApprenticeshipFinancialRecord->TBFinType=="TNP" and $LearnStartDate_Date==$TBFinDate_Date)
                    $price_record_on_learning_delivery_is_present = true;
            }
            if(($FundModel=="36" or ($FundModel=="81" and $ProgType=="25")) and $AimType=="1" and $apprenticeship_financial_record_is_present==false)
                $errors[] = "TBFinType_09: The Apprenticeship Financial Record must be returned (" . $delivery->LearnAimRef . ")";

            // TBFinType_12
            if($FundModel=="36" and $AimType=="1" and $price_record_is_present==false)
                $errors[] = "TBFinType_12: If the programme is an apprenticeship funded programme, there must be a price record (" . $delivery->LearnAimRef . ")";

            // TBFinType_13
            if($FundModel=="36" and $AimType=="1" and $price_record_on_learning_delivery_is_present==false)
                $errors[] = "TBFinType_13: An apprenticeship funded programme must have a price record that applies from the start date of the programme (" . $delivery->LearnAimRef . ")";

            // LLDDHealthProb_06
            if($LLDDHealthProb=='1' && $there_is_no_lldd)
                $errors[] = "LLDDHealthProb_06: If the LLDD and health problem is 'Learner considers himself or herself to have a learning difficulty and/or disability or health problem' then an LLDD and Health Problem record must be returned";

            // PlanLearnHours_01

            //if($learner_has_app_aim==false && $PlanLearnHours=="" && $FundModel!='70' && $all_aims_are_closed==false)
            //    $errors[] = "PlanLearnHours_01: The Planned learning hours must be returned unless the learner is undertaking workplace learning or an apprenticeship";

            // PlanEEPHours_01
            if(($FundModel==25 || $FundModel==82) && $PlanEEPHours=="")
                $errors[] = "PlanEEPHours_01: If the learner's learning aim is EFA funded, the Planned employability, enrichment and pastoral hours, must be returned";

            // PlanLearnHours_01
            $TotalHours = (int)$PlanLearnHours+(int)$PlanEEPHours;
            if(($FundModel==25 || $FundModel==82) && $TotalHours<1)
                $errors[] = "PlanLearnHours_03: The sum of the Planned learning hours and the Planned employability, enrichment and pastoral hours must be greater than zero";

            $sof=0;
            foreach($delivery->LearningDeliveryFAM as $ldf)
            {
                if($ldf->LearnDelFAMType=='SOF')
                {
                    if($ldf->LearnDelFAMCode=='105')
                        $sof++;
                }
            }

            if($FundModel=='35' or $FundModel=='36' or $FundModel=='81' or $FundModel=='70' or $FundModel=='10')
            {
                if($sof==0)
                    $errors[] = "LearnDelFAMType_09: The Source of funding record must be 105 (SFA) (".$delivery->LearnAimRef.")";
            }

            if($FundModel=='25' and  $learner_has_a_core_aim==false)
            {
                $errors[] = "R63: A 16-19 EFA funded learner must have at least one core aim. (".$delivery->LearnAimRef.")";
            }

        }
        // Postcode_10
        //if($PriorPostcode=='')
        //  $errors[] = "Postcode_10: The Prior to enrolment postcode must be returned";

        // LearnStartDate_06
        // LearnStartDate_07




        $error_string = implode("|",$errors);
        return $error_string;
    }


    ///////////////////////// FD_LEARNER Starts//////////////////////////////

    private function rule_FD_LearnRefNumber_01($link, $ilr)
    {
        $LearnRefNumber = (string)$ilr->LearnRefNumber;
        $LearnRefNumber = trim($ilr->LearnRefNumber);
        if($LearnRefNumber == '')
        {
            return "FD_LearnRefNumber_01: The Learner reference number has not been returned.";
        }
    }

    private function rule_FD_LearnRefNumber_02($link, $ilr)
    {
        $LearnRefNumber = (string)$ilr->LearnRefNumber;
        $LearnRefNumber = trim($ilr->LearnRefNumber);
        if($LearnRefNumber != '' && (strlen($LearnRefNumber) > 12))
        {
            return "FD_LearnRefNumber_02: The Learner reference number is more than 12 characters long.";
        }
    }

    private function rule_FD_PrevLearnRefNumber_01($link, $ilr)
    {
        $PrevLearnRefNumber = (string)$ilr->PrevLearnRefNumber;
        $PrevLearnRefNumber = trim($ilr->PrevLearnRefNumber);
        if($PrevLearnRefNumber != '' && (strlen($PrevLearnRefNumber) > 12))
        {
            return "FD_PrevLearnRefNumber_02: The Learner reference number in previous years is more than 12 characters long.";
        }
    }

    private function rule_FD_PrevUKPRN_01($link, $ilr)
    {
        $PrevUKPRN = (string)$ilr->PrevUKPRN;
        $PrevUKPRN = trim($ilr->PrevUKPRN);
        if($PrevUKPRN != '' && ($PrevUKPRN < 10000000 || $PrevUKPRN > 99999999))
        {
            return "FD_PrevUKPRN_01: The UKPRN in previous year is not in the range [10000000 - 99999999].";
        }
    }

    private function rule_FD_ULN_01($link, $ilr)
    {
        $ULN = (string)$ilr->ULN;
        $ULN = trim($ilr->ULN);
        if($ULN == '')
        {
            return "ULN: ULN is left blank.";
        }
        if($ULN != '' && ($ULN < 1000000000 || $ULN > 9999999999))
        {
            return "FD_ULN_01: The ULN is not in the range [1000000000 - 9999999999].";
        }
    }

    private function rule_FD_FamilyName_01($link, $ilr)
    {
        $FamilyName = (string)$ilr->FamilyName;
        $FamilyName = trim($ilr->FamilyName);
        if($FamilyName != '' && (strlen($FamilyName) > 100))
        {
            return "FD_FamilyName_01: The Family name is more than 100 characters long.";
        }
    }

    private function rule_FD_GivenNames_01($link, $ilr)
    {
        $GivenNames = (string)$ilr->GivenNames;
        $GivenNames = trim($ilr->GivenNames);
        if($GivenNames != '' && (strlen($GivenNames) > 100))
        {
            return "FD_GivenNames_01: The Given name is more than 100 characters long.";
        }
    }

    private function rule_FD_Ethnicity_01($link, $ilr)
    {
        $Ethnicity = (string)$ilr->Ethnicity;
        $Ethnicity = trim($ilr->Ethnicity);
        if($Ethnicity == '' || $Ethnicity == 'undefined' || is_null($Ethnicity))
        {
            return "FD_Ethnicity_01: The Ethnicity has not been returned.";
        }
    }

    private function rule_FD_Ethnicity_02($link, $ilr)
    {
        $Ethnicity = (string)$ilr->Ethnicity;
        $Ethnicity = trim($ilr->Ethnicity);
        if($Ethnicity != '' && strlen($Ethnicity) > 2)
        {
            return "FD_Ethnicity_02: The Ethnicity is more than two digits long.";
        }
    }

    private function rule_FD_Sex_01($link, $ilr)
    {
        $Sex = (string)$ilr->Sex;
        $Sex = trim($ilr->Sex);
        if($Sex == '' || is_null($Sex))
        {
            return "FD_Sex_01: The Sex has not been returned.";
        }
    }

    private function rule_FD_Sex_02($link, $ilr)
    {
        $Sex = (string)$ilr->Sex;
        $Sex = trim($ilr->Sex);
        if($Sex != '' && strlen($Sex) > 1)
        {
            return "FD_Sex_02: The Sex is more than one character long.";
        }
    }

    private function rule_FD_LLDDHealthProb_01($link, $ilr)
    {
        $LLDDHealthProb = (string)$ilr->LLDDHealthProb;
        $LLDDHealthProb = trim($ilr->LLDDHealthProb);
        if($LLDDHealthProb == '' || is_null($LLDDHealthProb))
        {
            return "FD_LLDDHealthProb_01: The LLDD and Health Problem has not been returned.";
        }
    }

    private function rule_FD_LLDDHealthProb_02($link, $ilr)
    {
        $LLDDHealthProb = (string)$ilr->LLDDHealthProb;
        $LLDDHealthProb = trim($ilr->LLDDHealthProb);
        if($LLDDHealthProb != '' && strlen($LLDDHealthProb) > 1)
        {
            return "FD_LLDDHealthProb_02: The LLDDHealthProb is more than one digit long.";
        }
    }

    private function rule_FD_NINumber_01($link, $ilr)
    {
        $NINumber = (string)$ilr->NINumber;
        $NINumber = trim($ilr->NINumber);
        if($NINumber != '' && strlen($NINumber) > 9)
        {
            return "FD_NINumber_02: The NI Number is more than 9 characters long.";
        }
    }

    private function rule_FD_PriorAttain_01($link, $ilr)
    {
        $PriorAttain = (string)$ilr->PriorAttain;
        $PriorAttain = trim($ilr->PriorAttain);
        if($PriorAttain != '' && strlen($PriorAttain) > 2)
        {
            return "FD_PriorAttain_01: The Prior Attainment is more than 2 digits long.";
        }
    }

    private function rule_FD_Accom_01($link, $ilr)
    {
        $Accom = (string)$ilr->Accom;
        $Accom = trim($ilr->Accom);
        if($Accom != '' && strlen($ilr->Accom) > 1)
        {
            return "FD_Accom_01: The Accommodation is more than 1 digit long.";
        }
    }

    private function rule_FD_ALSCost_01($link, $ilr)
    {
        $ALSCost = trim($ilr->ALSCost);

        if($ALSCost != '' && (floatval($ALSCost) < 0 || floatval($ALSCost) > 999999))
        {
            return "FD_ALSCost_01: This Learning support cost [ALSCost] is not in the range [0 - 999,999].";
        }
    }

    private function rule_FD_PlanLearnHours_01($link, $ilr)
    {
        $PlanLearnHours = trim($ilr->PlanLearnHours);

        if($PlanLearnHours != '' && (intval($PlanLearnHours) < 0 || intval($PlanLearnHours) > 9999))
        {
            return "FD_PlanLearnHours_01: The Planned learning hours are not in the range [0 - 9999].";
        }
    }

    private function rule_FD_PlanEEPHours_01($link, $ilr)
    {
        $PlanEEPHours = trim($ilr->PlanEEPHours);

        if($PlanEEPHours != '' && (intval($PlanEEPHours) < 0 || intval($PlanEEPHours) > 9999))
        {
            return "FD_PlanLearnHours_01: The Planned employability, enrichment and pastoral hours are not in the range [0 - 9999].";
        }
    }

    private function rule_FD_LocType_01($link, $ilr)
    {
        foreach($ilr->LearnerContact as $lc)
        {
            if(is_null($lc->LocType) || $lc->LocType == '')
            {
                return "FD_LocType_01: The Locator type has not been returned.";
            }
        }
    }

    private function rule_FD_LocType_02($link, $ilr)
    {
        foreach($ilr->LearnerContact as $lc)
        {
            $LocType = (string)$ilr->LocType;
            $LocType = trim($ilr->LocType);
            if($LocType != '' && (strlen($LocType) > 1))
            {
                return "FD_LocType_02: The Locator type is more than one digit long.";
            }
        }
    }

    private function rule_FD_ContType_01($link, $ilr)
    {
        foreach($ilr->LearnerContact as $lc)
        {
            if(is_null($lc->ContType) || $lc->ContType == '')
            {
                return "FD_ContType_01: The Contact type has not been returned.";
            }
        }
    }

    private function rule_FD_ContType_02($link, $ilr)
    {
        foreach($ilr->LearnerContact as $lc)
        {
            $ContType = (string)$ilr->ContType;
            $ContType = trim($ilr->ContType);
            if($ContType != '' && (strlen($ContType) > 1))
            {
                return "FD_ContType_02: The Contact type is more than one digit long.";
            }
        }
    }

    private function rule_FD_AddLine1_01($link, $ilr)
    {
        foreach($ilr->LearnerContact as $lc)
        {
            if($lc->LocType == '1' && (is_null($lc->PostAdd->AddLine1) || $lc->PostAdd->AddLine1 == ''))
            {
                return "FD_AddLine1_01: The Address Line 1 has not been returned.";
            }
        }
    }

    private function rule_FD_AddLine1_02($link, $ilr)
    {
        foreach($ilr->LearnerContact as $lc)
        {
            $AddLine1 = (string)$ilr->AddLine1;
            $AddLine1 = trim($ilr->AddLine1);
            if($AddLine1 != '' && (strlen($AddLine1) > 50))
            {
                return "FD_AddLine1_02: The Address line 1 is more than 50 characters long.";
            }
        }
    }

    private function rule_FD_AddLine2_01($link, $ilr)
    {
        foreach($ilr->LearnerContact as $lc)
        {
            $AddLine2 = (string)$ilr->AddLine2;
            $AddLine2 = trim($ilr->AddLine2);
            if($AddLine2 != '' && (strlen($AddLine2) > 50))
            {
                return "FD_AddLine2_01: The Address line 2 is more than 50 characters long.";
            }
        }
    }

    private function rule_FD_AddLine3_01($link, $ilr)
    {
        foreach($ilr->LearnerContact as $lc)
        {
            $AddLine3 = (string)$ilr->AddLine3;
            $AddLine3 = trim($ilr->AddLine3);
            if($AddLine3 != '' && (strlen($AddLine3) > 50))
            {
                return "FD_AddLine3_01: The Address line 3 is more than 50 characters long.";
            }
        }
    }

    private function rule_FD_AddLine1_04($link, $ilr)
    {
        foreach($ilr->LearnerContact as $lc)
        {
            $AddLine4 = (string)$ilr->AddLine4;
            $AddLine4 = trim($ilr->AddLine4);
            if($AddLine4 != '' && (strlen($AddLine4) > 50))
            {
                return "FD_AddLine4_01: The Address line 4 is more than 50 characters long.";
            }
        }
    }

    private function rule_FD_PostCode_01($link, $ilr)
    {
        foreach($ilr->LearnerContact as $lc)
        {
            $LocType = $lc->LocType;
            $PostCode = $lc->PostCode;
            if($LocType=='2' && ($PostCode=='' || is_null($PostCode)))
            {
                return "FD_PostCode_01: The Postcode has not been returned.";
            }
        }
    }

    private function rule_FD_PostCode_02($link, $ilr)
    {
        foreach($ilr->LearnerContact as $lc)
        {
            $PostCode = (string)$lc->PostCode;
            $PostCode = trim($PostCode);
            if($PostCode !='' && strlen($PostCode) > 8)
            {
                return "FD_PostCode_02: The Postcode is more than 8 characters long.";
            }
        }
    }

    private function rule_FD_Email_01($link, $ilr)
    {
        foreach($ilr->LearnerContact as $lc)
        {
            $LocType = $lc->LocType;
            $Email = $lc->Email;
            if($LocType=='4' && ($Email=='' || is_null($Email)))
            {
                return "FD_Email_01: The Email has not been returned and the Locator type is Email address.";
            }
        }
    }

    private function rule_FD_Email_02($link, $ilr)
    {
        foreach($ilr->LearnerContact as $lc)
        {
            $LocType = $lc->LocType;
            $Email = $lc->Email;
            if($LocType=='4' && $Email !='' && strlen($Email) > 100)
            {
                return "FD_Email_01: The Email address is more than 100 characters long.";
            }
        }
    }

    private function rule_FD_TelNumber_01($link, $ilr)
    {
        foreach($ilr->LearnerContact as $lc)
        {
            $LocType = $lc->LocType;
            $TelNumber = $lc->TelNumber;
            if($LocType=='3' && ($TelNumber=='' || is_null($TelNumber)))
            {
                return "FD_TelNumber_01: The Telephone number has not been returned and the Locator type is Telephone.";
            }
        }
    }

    private function rule_FD_TelNumber_02($link, $ilr)
    {
        foreach($ilr->LearnerContact as $lc)
        {
            $LocType = $lc->LocType;
            $TelNumber = $lc->TelNumber;
            if($LocType=='3' && $TelNumber !='' && strlen($TelNumber) > 18)
            {
                return "FD_TelNumber_01: The Telephone Number is more than 18 characters long.";
            }
        }
    }

    private function rule_FD_ContPrefType_01($link, $ilr)
    {
        foreach($ilr->ContactPreference as $lcp)
        {
            $ContPrefType = $lcp->ContPrefType;
            if($ContPrefType == '')
            {
                return "FD_ContPrefType_01: The Contact preference code has not been returned.";
            }
        }
    }

    private function rule_FD_ContPrefType_02($link, $ilr)
    {
        foreach($ilr->ContactPreference as $lcp)
        {
            $ContPrefType = $lcp->ContPrefType;
            if($ContPrefType != '' && strlen($ContPrefType) > 3)
            {
                return "FD_ContPrefType_02: The Contact preference type is more than 3 characters long.";
            }
        }
    }

    private function rule_FD_ContPrefCode_01($link, $ilr)
    {
        foreach($ilr->ContactPreference as $lcp)
        {
            $ContPrefType = $lcp->ContPrefType;
            $ContPrefCode = $lcp->ContPrefCode;
            if($ContPrefType != '' && $ContPrefCode == '')
            {
                return "FD_ContPrefCode_01: The Contact preference code has not been returned.";
            }
        }
    }

    private function rule_FD_ContPrefCode_02($link, $ilr)
    {
        foreach($ilr->ContactPreference as $lcp)
        {
            $ContPrefType = $lcp->ContPrefType;
            $ContPrefCode = $lcp->ContPrefCode;
            if($ContPrefType != '' && strlen($ContPrefCode) > 1)
            {
                return "FD_ContPrefCode_02: The Contact preference code is more than 1 character long.";
            }
        }
    }

    private function rule_FD_LLDDType_01($link, $ilr)
    {
        if($ilr->LLDDHealthProb=='2' && $ilr->LLDDandHealthProblem->LLDDType!='')
            return  "FD_LLDDType_01: The LLDD Type has not been returned";
    }

    private function rule_FD_LLDDType_02($link, $ilr)
    {
        if($ilr->LLDDHealthProb=='2' && $ilr->LLDDandHealthProblem->LLDDType!='' && strlen($ilr->LLDDandHealthProblem->LLDDType) > 2)
            return  "FD_LLDDType_02: The LLDD Type is more than 2 characters long";
    }

    private function rule_FD_LLDDCode_01($link, $ilr)
    {
        if($ilr->LLDDHealthProb=='2' && $ilr->LLDDandHealthProblem->LLDDType!='' && $ilr->LLDDandHealthProblem->LLDDCode =='')
            return  "FD_LLDDCode_01: The LLDD Code has not been returned";
    }

    private function rule_FD_LLDDCode_02($link, $ilr)
    {
        if($ilr->LLDDHealthProb=='2' && $ilr->LLDDandHealthProblem->LLDDType!='' && strlen($ilr->LLDDandHealthProblem->LLDDCode) > 2)
            return  "FD_LLDDType_02: The LLDD Code is more than 2 digits long";
    }

    private function rule_FD_LearnFAMType_01($link, $ilr)
    {
        foreach($ilr->LearnerFAM as $learnerfam)
            if($learnerfam->LearnFAMType == '' || is_null($learnerfam->LearnFAMType))
                return "FD_LearnFAMType_01: The Learner FAM type has not been returned.";
    }

    private function rule_FD_LearnFAMType_02($link, $ilr)
    {
        foreach($ilr->LearnerFAM as $learnerfam)
            if($learnerfam->LearnFAMType != '' && strlen($learnerfam->LearnFAMType) > 3)
                return "FD_LearnFAMType_02: The Learner FAM type is more than 3 characters long.";
    }

    private function rule_FD_LearnFAMCode_01($link, $ilr)
    {
        foreach($ilr->LearnerFAM as $learnerfam)
            if($learnerfam->LearnFAMCode == '' || is_null($learnerfam->LearnFAMCode))
                return "FD_LearnFAMCode_01: The Learner FAM code has not been returned.";
    }

    private function rule_FD_LearnFAMCode_02($link, $ilr)
    {
        foreach($ilr->LearnerFAM as $learnerfam)
            if($learnerfam->LearnFAMCode != '' && strlen($learnerfam->LearnFAMCode) > 3)
                return "FD_LearnFAMCode_02: The Learner FAM code is more than 3 digits long.";
    }

    private function rule_FD_ProvSpecLearnMonOccur_01($link, $ilr)
    {
        foreach($ilr->ProviderSpecLearnerMonitoring as $provSpecLearnMon)
            if($provSpecLearnMon->ProvSpecLearnMonOccur == '' || is_null($provSpecLearnMon->ProvSpecLearnMonOccur))
                return "FD_ProvSpecLearnMonOccur_01: The Provider specified learner monitoring occurrence has not been returned.";
    }

    private function rule_FD_ProvSpecLearnMonOccur_02($link, $ilr)
    {
        foreach($ilr->ProviderSpecLearnerMonitoring as $provSpecLearnMon)
            if($provSpecLearnMon->ProvSpecLearnMonOccur != '' && strlen($provSpecLearnMon->ProvSpecLearnMonOccur) > 1)
                return "FD_ProvSpecLearnMonOccur_02: The Provider specified learner monitoring occurrence is more than 1 character long.";
    }

    private function rule_FD_ProvSpecLearnMon_01($link, $ilr)
    {
        foreach($ilr->ProviderSpecLearnerMonitoring as $provSpecLearnMon)
            if($provSpecLearnMon->ProvSpecLearnMon == '' || is_null($provSpecLearnMon->ProvSpecLearnMon))
                return "FD_ProvSpecLearnMon_01: The Provider specified learner monitoring has not been returned.";
    }

    private function rule_FD_ProvSpecLearnMon_02($link, $ilr)
    {
        foreach($ilr->ProviderSpecLearnerMonitoring as $provSpecLearnMon)
            if($provSpecLearnMon->ProvSpecLearnMon != '' && strlen($provSpecLearnMon->ProvSpecLearnMon) > 20)
                return "FD_ProvSpecLearnMon_02: The Provider specified learner monitoring is more than 20 characters long.";
    }
///////////////////////// FD_LEARNER Ends//////////////////////////////

    private function rule_ULN_04($link, $ilr)
    {
        $ULN = trim("".$ilr->ULN);
        if($ULN=='9999999999' || $ULN=='')
        {
            $DD01 = "Y";
        }
        else
        {
            $remainder = ( (10 * (int)substr($ULN,0,1)) + (9 * (int)substr($ULN,1,1)) + (8 * (int)substr($ULN,2,1)) + (7 * (int)substr($ULN,3,1)) + (6 * (int)substr($ULN,4,1)) + (5 * (int)substr($ULN,5,1)) + (4 * (int)substr($ULN,6,1)) + (3 * (int)substr($ULN,7,1)) + (2 * (int)substr($ULN,8,1))) % 11;
            if($remainder==0)
                $DD01 = "N";
            else
                $DD01 = 10 - $remainder;
        }
        if($DD01==="N" || ($DD01!="Y" && $DD01!= substr($ULN,9,1)))
        {
            return "ULN_04: The Unique learner number has not passed the checksum calculation :1";
        }
    }

    private function rule_ULN_07($link, $ilr)
    {
        $ULN = (string)$ilr->ULN;
        $days = false;
        foreach($ilr->LearningDelivery as $delivery)
        {
            $LearnStartDate = Date::toMySQL($delivery->LearnStartDate);
            $CurrentDate = Date::toMySQL(date('d/m/Y'));
            $diff = DAO::getSingleValue($link, "select DATEDIFF('$CurrentDate','$LearnStartDate')");
            if($diff>60)
                $days = true;
        }
        if($days && $ULN=='9999999999' && DB_NAME!='am_direct')
        {
            return "ULN_07: If the file preparation date/entry date is on or after 1 January 2013, the Unique learner number must not be 9999999999 if the learning aim has a Planned or Actual duration of 10 days or more and the Learning start date is more than 60 calendar days before the file preparation date/entry date (for POL), unless the learner is an OLASS - Offender in Custody :1";
        }
    }

    private function rule_FamilyName_01($link, $ilr)
    {
        $FamilyName = $ilr->FamilyName;
        if($FamilyName=='')
        {
            return "FamilyName_01: The learner's Family name must be returned  \n";
        }
    }

    private function rule_FamilyName_03($link, $ilr)
    {
        $FamilyName = $ilr->FamilyName;
        if(preg_match('#[0-9]#',$FamilyName))
        {
            return "FamilyName_03: Only alphabetical characters must be returned in the learner's Family name :1";
        }
    }

    private function rule_GivenNames_01($link, $ilr)
    {
        $GivenNames = $ilr->GivenNames;
        if($GivenNames=='')
        {
            return "GivenNames_01: The learner's Given names must be returned :1";
        }
    }

    private function rule_GivenNames_03($link, $ilr)
    {
        $GivenNames = $ilr->GivenNames;
        if(preg_match('#[0-9]#',$GivenNames))
        {
            return "GivenNames_03: Only alphabetical characters must be returned in the learner's Given names :1";
        }
    }

    private function rule_DateOfBirth_01($link, $ilr)
    {
        $DateOfBirth = $ilr->DateOfBirth;
        if($DateOfBirth=='' || $DateOfBirth=='dd/mm/yyyy')
        {
            return "DateOfBirth_01: The Date of birth must be returned :1";
        }
    }

    private function rule_DateOfBirth_04($link, $ilr)
    {
        if($ilr->DateOfBirth!='' && $ilr->DateOfBirth!='dd/mm/yyyy')
        {
            $DateOfBirth = new Date($ilr->DateOfBirth);
            if($DateOfBirth->after('01/08/2013') || $DateOfBirth->before('01/01/1887'))
            {
                return "DateOfBirth_04: The learner must be under 115 at the start of the current teaching year ";
            }
        }
    }

    private function rule_DateOfBirth_07($link, $ilr)
    {
        if($ilr->DateOfBirth!='' && $ilr->DateOfBirth!='dd/mm/yyyy')
        {
            $DateOfBirth = new Date($ilr->DateOfBirth);
            $ldm = false;
            foreach($ilr->LearningDelivery as $delivery)
            {
                foreach($delivery->LearningDeliveryFAM as $ldf)
                {
                    if($ldf->LearnDelFAMType=='SOF' && $ldf->LearnDelFAMCode=='107')
                        $ldm = true;
                }
            }
            if($DateOfBirth->before('31/08/1988') && $ldm)
            {
                return "DateOfBirth_07: If the learner is over 25 on 31 August of the current teaching year, the Source of funding must not be EFA";
            }
        }
    }

    private function rule_DateOfBirth_28($link, $ilr)
    {
        if($ilr->DateOfBirth!='' && $ilr->DateOfBirth!='dd/mm/yyyy')
        {
            $DateOfBirth = Date::toMySQL($ilr->DateOfBirth);
            $ldm = false;
            foreach($ilr->LearningDelivery as $delivery)
            {
                if($delivery->AimType=='1' && ($delivery->ProgType=='2' || $delivery->ProgType=='3' || $delivery->ProgType=='10' || $delivery->ProgType=='20' || $delivery->ProgType=='21' || $delivery->ProgType=='22' || $delivery->ProgType=='23'))
                {
                    $LearnStartDate = Date::toMySQL($delivery->LearnStartDate);
                    // If Learner is < 19
                    $Under19 = DAO::getSingleValue($link, "SELECT IF(DATE_ADD('$DateOfBirth',INTERVAL 19 YEAR)>DATE_ADD('$LearnStartDate',INTERVAL 1 DAY),1,0);");
                    if($Under19=='1')
                    {
                        // If duration is less than 12 months
                        $LearnPlanEndDate = Date::toMySQL($delivery->LearnPlanEndDate);
                        $LeassThan12Months = DAO::getSingleValue($link,"SELECT IF(DATE_ADD('$LearnStartDate',INTERVAL 12 MONTH)>DATE_ADD('$LearnPlanEndDate',INTERVAL 1 DAY),1,0);");
                        if($LeassThan12Months=='1')
                        {
                            $Restart = false;
                            foreach($delivery->LearningDeliveryFAM as $ldf)
                            {
                                if($ldf->LearnDelFAMType=='RES' && $ldf->LearnDelFAMCode=='1')
                                    $Restart = true;
                            }
                            if(!$Restart)
                            {
                                return "DateOfBirth_28: Warning - The learner is under 19 and the Learning start date [LearnStartDate] and Learning planned end date [LearnPlanEndDate] do not reach the minimum duration of an Apprenticeship.";
                            }
                        }
                    }
                }
            }
        }
    }
    /*    private function rule_DateOfBirth_29($link, $ilr)
         {
             if($ilr->DateOfBirth!='' && $ilr->DateOfBirth!='dd/mm/yyyy')
             {
                 $DateOfBirth = Date::toMySQL($ilr->DateOfBirth);
                 $ldm = false;
                 foreach($ilr->LearningDelivery as $delivery)
                 {
                     if($delivery->AimType=='1' && ($delivery->ProgType=='2' || $delivery->ProgType=='3' || $delivery->ProgType=='10' || $delivery->ProgType=='20' || $delivery->ProgType=='21' || $delivery->ProgType=='22' || $delivery->ProgType=='23'))
                     {
                         $LearnStartDate = Date::toMySQL($delivery->LearnStartDate);
                         // If Learner is >= 19
                         $Under19 = DAO::getSingleValue($link, "SELECT IF(DATE_ADD('$DateOfBirth',INTERVAL 19 YEAR)>'$LearnStartDate',1,0);");
                         if($Under19=='0')
                         {
                             // If duration is less than 6 months
                             $LearnPlanEndDate = Date::toMySQL($delivery->LearnPlanEndDate);
                             $LeassThan12Months = DAO::getSingleValue($link,"SELECT IF(DATE_ADD('$LearnStartDate',INTERVAL 6 MONTH)>DATE_ADD(DATE_ADD('$LearnPlanEndDate'INTERVAL 1 DAY),INTERVAL 1 DAY),1,0);");
                             if($LeassThan12Months=='1')
                             {
                                 $Restart = false;
                                 foreach($delivery->LearningDeliveryFAM as $ldf)
                                 {
                                     if($ldf->LearnDelFAMType=='RES' && $ldf->LearnDelFAMCode=='1')
                                         $Restart = true;
                                 }
                                 if(!$Restart)
                                 {
                                     return "DateOfBirth_29: Warning - The learner is 19 or over and the Learning start date [LearnStartDate] and Learning planned end date [LearnPlanEndDate] do not reach the minimum duration of an Apprenticeship.   The Date of birth returned is [DateOfBirth]";
                                 }
                             }
                         }
                     }
                 }
             }
         }
     */







    private function rule_LLDDHealthProb_04($link, $ilr)
    {
        $ldds_exists = false;
        foreach($ilr->LLDDandHealthProblem as $lldd)
            if($lldd->LLDDCat!='')
                $ldds_exists = true;
        if($ilr->LLDDHealthProb=='2' && $ldds_exists)
            return  "LLDDHealthProb_04: If the learner's LLDD and health problem is 'Learner does not consider himself or herself to have a learning difficulty and/or disability or health problem' then an LLDD and Health Problem entity must not be returned :1";
    }

    /*private function rule_LLDDHealthProb_06($link, $ilr)
        {
            if($ilr->LLDDHealthProb=='1' && $ilr->LLDDandHealthProblem->LLDDType=='')
                return  "LLDDHealthProb_06: If the learner's LLDD and health problem is 'Learner considers himself or herself to have a learning difficulty and/or disability or health problem' then an LLDD and Health Problem entity must be returned :1";
        }*/

    private function rule_NINumber_01($link, $ilr)
    {
        $NINumber = trim($ilr->NINumber);
        if($NINumber!='')
        {
            if(strlen($NINumber)!=9)
            {
                return "NINumber_01: Invalid insurance number :1";
            }
            $one = substr($NINumber,0,1);
            $two = substr($NINumber,1,1);
            $digi = substr($NINumber,2,6);
            $st='0123456789';
            $nine = substr($NINumber,8,1);

            if(ord($one)<65 || ord($one)>90 || $one=='D' || $one=='F' || $one=='I' || $one=='Q' || $one=='U' || $one=='V')
            {
                return "NINumber_01: The first character of National Insurance no. must be an alphabet other than D, F, I, Q, U and V :1";
            }
            if(ord($two)<65 || ord($two)>90 || $two=='D' || $two=='F' || $two=='I' || $two=='O' || $two=='Q' || $two=='U' || $two=='V')
            {
                return "NINumber_01: The second character of National Insurance no. must be an alphabet other than D, F, I, O, Q, U and V \n";
            }
            for($lp=0;$lp<strlen($digi);$lp++)
            {
                if(strpos($st,substr($digi,$lp,1))==-1)
                {
                    return "NINumber_01: Characters 3 to 8 of National Insuarnce no. must only be digits :1";
                }
            }
            if( ord($nine)<65 || ord($nine)>90 || ($nine!='A' && $nine!='B' && $nine!='C' && $nine!='D' && $nine!=' '))
            {
                return "The character 9 of National Insurance no. must be A, B, C, D or space \n";
            }
        }
    }


    private function rule_PriorAttain_01($link, $ilr)
    {
        if($ilr->PriorAttain=='')
            return  "PriorAttain_01: The Prior attainment code must be returned :1";
    }

    private function rule_LocType_01($link, $ilr)
    {
        foreach($ilr->LearnerContact as $lc)
        {
            $LocType = $lc->LocType;
            $PostAdd = $lc->PostAdd;
            if($LocType=='1' && $PostAdd=='')
            {
                return "LocType_01: The Locator type is Postal Address and a  corresponding Postal Address value has not been returned :1";
            }
        }
    }

    private function rule_ContType_01($link, $ilr)
    {
        foreach($ilr->LearnerContact as $lc)
        {
            $LocType = $lc->LocType;
            $ContType = $lc->ContType;
            if($ContType=='1' && ($LocType=='1' || $LocType=='3' || $LocType=='4'))
            {
                return "ContType_01: If the Contact type is Prior to Enrolment then the Locator type must not be Postal Address, Telephone or Email address :1";
            }
        }
    }

    private function rule_AddLine1_01($link, $ilr)
    {
        $AddLine1 = '';
        foreach($ilr->LearnerContact as $lc)
        {
            if(trim($lc->PostAdd->AddLine1)!='')
                $AddLine1 = trim($lc->PostAdd->AddLine1);
        }
        if($AddLine1=='')
        {
            return "AddLine1: The Address line 1 must exist and not be null.";
        }
    }


    private function rule_FundModel_03($link, $ilr)
    {
        foreach($ilr->LearningDelivery as $delivery)
        {
            if($delivery->FundModel!='99')
            {
                foreach($delivery->LearningDeliveryFAM as $ldf)
                {
                    if($ldf->LearnDelFAMType=='ADL')
                    {
                        if($ldf->LearnDelFAMCode!='' && $ldf->LearnDelFAMCode!='undefined')
                            return "FundModel_03: The Funding model is not valid for a learning aim (" . $delivery->LearnAimRef . ") financed by a 24+ Advanced Learning Loan. \n";
                    }
                }
            }
        }
    }

    /*private function rule_PwayCode_01($link, $ilr)
        {
            foreach($ilr->LearningDelivery as $delivery)
            {
                if($delivery->FundModel=='35' && $delivery->AimType=='1' && $delivery->ProgType!='24')
                {
                    $ProgType = $delivery->ProgType;
                    $PwayCode = $delivery->PwayCode;
                    $FworkCode = $delivery->FworkCode;
                    $found = DAO::getSingleValue($link, "SELECT COUNT(*) FROM lars201314.LARS_Framework1314 WHERE LARS_FwkProgType = '$ProgType' AND LARS_FwkSectorCode = '$FworkCode' AND LARS_FwkPway = '$PwayCode';");
                    if($found < 1)
                        return "PwayCode_01: There must be a valid record in the Frameworks table in LARA for this Framework code, Programme type and Apprenticeship pathway for this learning aim. (".$delivery->LearnAimRef.")";
                }
            }
        }*/

    private function rule_FundModel_04($link, $ilr)
    {
        foreach($ilr->LearningDelivery as $delivery)
        {
            if(($delivery->FundModel=='25' || $delivery->AimType=='82') && ($delivery->ProgType != 'undefined' || $delivery->ProgType != ''))
            {
                if(in_array($delivery->ProgType, array('2', '3', '10', '20', '21', '22', '23', '25')))
                    return "FundModel_04: This Funding model is not valid for a learning aim (" . $delivery->LearnAimRef . ") which is part of an Apprenticeship programme.";
            }
        }
    }

    private function rule_PwayCode_03($link, $ilr)
    {
        foreach($ilr->LearningDelivery as $delivery)
        {
            if(!isset($delivery->LearnStartDate) OR $delivery->LearnStartDate == '' OR $delivery->LearnStartDate == 'undefined') return $delivery->LearnAimRef . ' - Learning Start Date is blank';
            $LearnStartDate = new Date($delivery->LearnStartDate);
            if($LearnStartDate->after('31/07/2013') && $delivery->FundModel=='35' && ($delivery->ProgType=='2' || $delivery->ProgType=='3') && ("".$delivery->PwayCode)=='')
            {
                return "PwayCode_03: If the learning aim is part of an Apprenticeship programme, the Apprenticeship pathway must be returned.(".$delivery->LearnAimRef.")";
            }
        }
    }

    private function rule_WorkPlaceStartDate_01($link, $ilr)
    {
        foreach($ilr->LearningDelivery as $delivery)
        {
            if(!isset($delivery->LearnStartDate) OR $delivery->LearnStartDate == '' OR $delivery->LearnStartDate == 'undefined') return $delivery->LearnAimRef . ' - Learning Start Date is blank';
            $LearnStartDate = new Date($delivery->LearnStartDate);
            if($LearnStartDate->after('31/07/2014') && ($delivery->LearnAimRef=='Z0007834' || $delivery->LearnAimRef=='Z0007835' || $delivery->LearnAimRef=='Z0007836' || $delivery->LearnAimRef=='Z0007837' || $delivery->LearnAimRef=='Z0007838' || $delivery->LearnAimRef=='Z0002347'))
            {
                $work = '';
                foreach($delivery->LearningDeliveryWorkPlacement as $workplacement)
                {
                    if(isset($workplacement->WorkPlaceStartDate) && $workplacement->WorkPlaceStartDate!='' && $workplacement->WorkPlaceStartDate!='undefined' && $workplacement->WorkPlaceStartDate!='dd/mm/yyyy')
                        $work = $workplacement->WorkPlaceStartDate;
                }
                if($work=='')
                    return "WorkPlaceStartDate_01: A work experience aim or the Supported Internship learning aim has been returned and the Work Placement record has not been returned.(".$delivery->LearnAimRef.")";
            }
        }
    }

    /*
         private function rule_Email_01($link, $ilr)
         {
             $Email = '';
             foreach($ilr->LearnerContact as $lc)
             {
                 if(trim($lc->Email)!='')
                     $Email = trim($lc->Email);
             }
             if($Email!='' && (strpos($Email,"@")==0 || strpos($Email,".")==0))
             {
                 return "Email_01: If returned, the Email address must contain at least an @ sign and a dot (.) : 1";
             }
         }
     */


    private function rule_EmpId_10($link, $ilr)
    {
        foreach($ilr->LearningDelivery as $delivery)
        {
            if($delivery->AimType=='1')
            {
                if(($delivery->FundModel=='36' || $delivery->FundModel=='35') && ($delivery->ProgType=='2' || $delivery->ProgType=='3' || $delivery->ProgType=='10' || $delivery->ProgType=='20' || $delivery->ProgType=='21' || $delivery->ProgType=='25'))
                {
                    $LearnStartDate = new Date(Date::toMySQL($delivery->LearnStartDate));
                    foreach($ilr->LearnerEmploymentStatus as $les)
                    {
                        if($les->DateEmpStatApp == '' || $les->DateEmpStatApp == 'undefined')
                            return 'Date Employment Status must not be left blank.';
                        $DateEmpStatApp = new Date(Date::toMySQL($les->DateEmpStatApp));
                        if(trim($les->EmpStat=='10') && trim($les->EmpId)=='')
                            return "EmpId_10: If the learner is undertaking an Apprenticeship programme and is 'in paid employment' on the programme start date then there must be an Employer id with a Date employment status which applies on or before to the programme start date.(".$delivery->LearnAimRef.")";
                    }
                }
            }
        }
    }

    private function rule_EmpId_02($link, $ilr)
    {
        foreach($ilr->LearnerEmploymentStatus as $les)
        {
            $EmpId = "" . $les->EmpId;
            $AD03 = '';
            if($EmpId!='')
            {
                $A44 = $EmpId;
                $flag1 = true;
                for($a=0;$a<=8; $a++)
                    if(!($this->isDigit(substr($A44,$a,1))))
                        $flag1 = false;

                $flag2 = true;
                if(strlen($A44)>9)
                    for($a=9;$a<=29; $a++)
                        if((substr($A44,$a,1)!=' ') && (substr($A44,$a,1)!=''))
                            $flag2 = false;

                if($flag1 && $flag2)
                {
                    $res = 11-((9*(int)substr($A44,0,1)+8*(int)substr($A44,1,1)+7*(int)substr($A44,2,1)+6*(int)substr($A44,3,1)+5*(int)substr($A44,4,1)+4*(int)substr($A44,5,1) + 3*(int)substr($A44,6,1) + 2*(int)substr($A44,7,1)) % 11);
                    if($res==11)
                        $AD03='0';
                    else
                        if($res==10)
                            $AD03='X';
                        else
                            $AD03=$res;
                }
                else
                    $AD03 = 'T';
            }
            if($AD03=='T')
            {
                return "EmpId_02: If returned, the Employer identifier must pass the check sum calculation in DD05 \n";
            }
        }
    }

    private function rule_EmpId_11($link, $ilr)
    {
        foreach($ilr->LearningDelivery as $delivery)
        {
            if($delivery->AimType=='4' && $delivery->FundModel=='35')
            {
                $ldm = false;
                foreach($delivery->LearningDeliveryFAM as $ldf)
                {
                    if($ldf->LearnDelFAMType=='WPL' && $ldf->LearnDelFAMCode=='1')
                        $ldm = true;
                }
                if($ldm)
                {
                    $LearnStartDate = Date::toMySQL($delivery->LearnStartDate);
                    $error = true;
                    foreach($ilr->LearnerEmploymentStatus as $les)
                    {
                        $DateEmpStatApp = Date::toMySQL($les->DateEmpStatApp);
                        if($DateEmpStatApp==$LearnStartDate && $les->EmpId!='')
                            $error = false;
                    }
                    if($error )
                    {
                        return "EmpId_11: If the learner is undertaking non-Apprenticeship workplace learning then there must be an Employer identifier which applies to the learning aim start date \n";
                    }
                }
            }
        }
    }

/*    private function rule_EmpId_13($link, $ilr)
    {
        foreach($ilr->LearnerEmploymentStatus as $les)
        {
            $EmpId = "". trim($les->EmpId);
            $days = false;
            foreach($ilr->LearningDelivery as $delivery)
            {
                $LearnStartDate = Date::toMySQL($delivery->LearnStartDate);
                $CurrentDate = Date::toMySQL(date('d/m/Y'));
                $diff = DAO::getSingleValue($link, "select DATEDIFF('$CurrentDate','$LearnStartDate')");
                if($diff>60)
                    $days = true;
            }
            if($days && $EmpId=='999999999')
            {
                return "EmpId_13: The Employer id must not be 999999999 if the latest workplace learning aim or Apprenticeship programme aim start date is more than 60 days before the file preparation date or entry date   \n";
            }
        }
    }*/

    private function rule_ESMType_02($link, $ilr)
    {
        foreach($ilr->LearnerEmploymentStatus as $les)
        {
            if(("".$les->DateEmpStatApp)!='' && ("".$les->DateEmpStatApp)!='dd/mm/yyyy')
            {
                $EmpStat = "". trim($les->EmpStat);
                $DateEmpStatApp = new Date("".$les->DateEmpStatApp);
                $eiifound = false;
                foreach($les->EmploymentStatusMonitoring as $esm)
                {
                    if($esm->ESMType=='EII')
                        $eiifound = true;
                }
                if($EmpStat=='10' && $DateEmpStatApp->after('31/07/2012') && $eiifound==false)
                {
                    return "ESMType_02: If Employment status is 'In paid employment' and the Date employment status applies is on or after 1 August 2012, then an Employment intensity indicator must be returned.";
                }
            }
        }
    }

    private function rule_ESMType_08($link, $ilr)
    {
        foreach($ilr->LearnerEmploymentStatus as $les)
        {
            if(("".$les->DateEmpStatApp)!='' && ("".$les->DateEmpStatApp)!='dd/mm/yyyy')
            {
                $EmpStat = "". trim($les->EmpStat);
                $DateEmpStatApp = new Date("".$les->DateEmpStatApp);
                $loufound = false;
                foreach($les->EmploymentStatusMonitoring as $esm)
                {
                    if($esm->ESMType=='LOU')
                        $loufound = true;
                }
                if( ($EmpStat=='11' || $EmpStat=='12') && $DateEmpStatApp->after('31/07/2012') && $loufound==false)
                {
                    return "ESMType_08: If Employment status is 'Not in paid employment' then the Length of unemployment must be returned if the Date employment status applies to is on or after 1 August 2012.";
                }
            }
        }
    }

    private function rule_ESMType_09($link, $ilr)
    {
        foreach($ilr->LearnerEmploymentStatus as $les)
        {
            if(("".$les->DateEmpStatApp)!='' && ("".$les->DateEmpStatApp)!='dd/mm/yyyy')
            {
                $EmpStat = "". trim($les->EmpStat);
                $DateEmpStatApp = new Date("".$les->DateEmpStatApp);
                $loefound = false;
                foreach($les->EmploymentStatusMonitoring as $esm)
                {
                    if($esm->ESMType=='LOE')
                        $loefound = true;
                }
                if($EmpStat=='10' && $DateEmpStatApp->after('31/07/2013') && $loefound==false)
                {
                    foreach($ilr->LearningDelivery as $delivery)
                    {
                        if($delivery->FundModel=='35' && $delivery->AimType=='1')
                        {
                            return "ESMType_09: If the learner is undertaking an Apprenticeship programme and the Employment status is 'In paid employment' and the Date employment status applies is on or after 1 August 2013, then an Length of Employment indicator must be returned  \n";
                        }
                    }
                }
            }
        }
    }

    private function rule_ESMType_11($link, $ilr)
    {
        foreach($ilr->LearnerEmploymentStatus as $les)
        {
            if(("".$les->DateEmpStatApp)!='' && ("".$les->DateEmpStatApp)!='dd/mm/yyyy')
            {
                $DateEmpStatApp = new Date("".$les->DateEmpStatApp);
                foreach($les->EmploymentStatusMonitoring as $esm)
                {
                    if($esm->ESMType=='EII' && $esm->ESMCode=='1' && $DateEmpStatApp->after('31/07/2013'))
                        return "ESMType_11: EII - This Employment status monitoring type and code is not valid for this employment status date  \n";
                }
            }
        }
    }

    private function rule_EmpStat_02($link, $ilr)
    {
        foreach($ilr->LearningDelivery as $delivery)
        {
            if($delivery->AimType=='4' && $delivery->FundModel=='35')
            {
                $LearnStartDate = Date::toMySQL($delivery->LearnStartDate);
                $error = true;
                foreach($ilr->LearnerEmploymentStatus as $les)
                {
                    $DateEmpStatApp = Date::toMySQL($les->DateEmpStatApp);
                    if($DateEmpStatApp==$LearnStartDate)
                        $error = false;
                }
                if($error)
                {
                    return "EmpStat_02: If the learner is undertaking workplace learning, there must be an Employment status record where the Date employment status applies is on or before the learning aim or Programme start date. \n";
                }
            }
        }
    }

    private function rule_R29($link, $ilr)
    {
        $Pway = Array();
        foreach($ilr->LearningDelivery as $les)
        {
            if($les->ProgType!='' && $les->FworkCode!='' && ($les->LearnActEndDate == '' OR $les->LearnActEndDate == 'undefined' || is_null($les->LearnActEndDate)) && (in_array($les->AimType, array('1', '3'))))
                $Pway[] = "".$les->PwayCode;
            if(sizeof(array_unique($Pway))>1)
                return "R29: All open aims that are part of a programme must have the same Framework code and Apprenticeship pathway (where completed). Learning Aim (" . $les->LearnAimRef . ")";
        }
    }


    private function rule_R43($link, $ilr)
    {
        $dates = Array();
        foreach($ilr->LearnerEmploymentStatus as $les)
        {
            $DateEmpStatApp = "".$les->DateEmpStatApp;
            if(in_array($DateEmpStatApp,$dates))
            {
                return "R43: No two Learner Employment status records should have the same UKPRN, Learner Reference number and Date employment status applies :1";
            }
            else
            {
                $dates[] = $DateEmpStatApp;
            }
        }
    }


    /*private function rule_LearnAimRef_02($link, $ilr)
        {
            foreach($ilr->LearningDelivery as $delivery)
            {
                if($delivery->ProgType=='99')
                {
                    $found = DAO::getSingleValue($link, "select count(*) from lad201213.validity_details where LEARNING_AIM_REF = '$delivery->LearnAimRef' AND (FUND_MODEL_ILR_SUBSET_CODE='ER_OTHER' OR FUND_MODEL_ILR_SUBSET_CODE='ANY')");
                    if($found=='0')
                    {
                        return "LearnAimRef_02: If the Learning aim is not part of an Apprenticeship Programme, the Learning aim reference must exist in the validity details table on LARA (".$delivery->LearnAimRef.")";
                    }
                }
            }
        }*/

    private function rule_LearnAimRef_03($link, $ilr)
    {
        foreach($ilr->LearningDelivery as $delivery)
        {
            if($delivery->ProgType!='' && $delivery->FundModel=='35')
            {
                $found = DAO::getSingleValue($link, "SELECT COUNT(*) FROM lars201718.Core_LARS_Validity WHERE LearnAimRef = '$delivery->LearnAimRef'");
                if($found=='0')
                {
                    // return "LearnAimRef_03: If the Learning aim is part of an ER funded Apprenticeship Programme, the Learning aim reference must exist in the validity details table on LARA  \n";
                    return "LearnAimRef_03: This learning aim (".$delivery->LearnAimRef.") does not appear in the list for the ER funded Apprenticeship Programme.";
                }
            }
        }
    }


    /*private function rule_LearnAimRef_05($link, $ilr)
        {
            foreach($ilr->LearningDelivery as $delivery)
            {
                if($delivery->FundModel=='22')
                {
                    $found = DAO::getSingleValue($link, "select count(*) from lad201213.validity_details where LEARNING_AIM_REF = '$delivery->LearnAimRef' AND FUND_MODEL_ILR_SUBSET_CODE='ADULT_LR'");
                    if($found=='0')
                    {
                        return "LearnAimRef_05: If the Learning aim is funded through the Adult learner responsive model, the Learning aim reference code must exist in the validity details table on LARA.(".$delivery->LearnAimRef.")";
                    }
                }
            }
        }*/

    private function rule_LearnStartDate_02($link, $ilr)
    {
        foreach($ilr->LearningDelivery as $delivery)
        {
            if(!isset($delivery->LearnStartDate) OR $delivery->LearnStartDate == '' OR $delivery->LearnStartDate == 'undefined') return $delivery->LearnAimRef . ' - Learning Start Date is blank';
            $LearnStartDate = new Date($delivery->LearnStartDate);
            if($LearnStartDate->before('01/08/2002'))
            {
                return "LearnStartDate_02: The Learning start date must not be more than 10 years ago. (".$delivery->LearnAimRef.")";
            }
        }
    }

    private function DD07($_progType)
    {
        if(in_array($_progType, array('2', '3', '10', '20', '21', '22', '23', '25')))
            return 'Y';
        else
            return 'N';
    }

    private function DD09($_progType)
    {
        if(in_array($_progType, array('15', '16', '17', '18')))
            return 'Y';
        else
            return 'N';
    }

    private function DD11 (PDO $link, $ilr)
    {
        return 'Y';
    }

    private function DD10(PDO $link, $ilr)
    {
        $programme_aim_prog_type = "";
        $programme_aim_fwork_code = "";
        $programme_aim_pway_code = "";

        foreach($ilr->LearningDelivery AS $delivery)
        {
            if($delivery->AimType == '1')
            {
                $programme_aim_prog_type = $delivery->ProgType;
                $programme_aim_fwork_code = $delivery->FworkCode;
                $programme_aim_pway_code = $delivery->PwayCode;
                break;
            }
        }

        if($programme_aim_prog_type == "" || $programme_aim_fwork_code == "" || $programme_aim_pway_code == "")
            return 'N';

        foreach($ilr->LearningDelivery AS $delivery)
        {
            if(in_array($delivery->AimType, array('1', '4')))
            {
                foreach($delivery->LearningDeliveryFAM as $ldf)
                {
                    if($ldf->LearnDelFAMType=='ADL' && $ldf->LearnDelFAMCode=='1')
                        return 'Y';
                }
            }
            elseif($delivery->AimType == '3')
            {
                $ldm = false;
                foreach($delivery->LearningDeliveryFAM as $ldf)
                {
                    if($ldf->LearnDelFAMType=='ADL' && $ldf->LearnDelFAMCode=='1')
                        $ldm = true;
                }
                if($ldm)
                {
                    if($delivery->ProgType == $programme_aim_prog_type && $delivery->FworkCode == $programme_aim_fwork_code && $delivery->PwayCode == $programme_aim_pway_code)
                        return 'Y';
                }
            }
        }
        return 'N';
    }

    private function rule_LearnAimRef_09($link, $ilr)
    {
        foreach($ilr->LearningDelivery as $delivery)
        {
            if(!isset($delivery->ProgType) || $delivery->ProgType == '' || $delivery->ProgType == 'undefined')
                return '';
            $DD07 = $this->DD07($delivery->ProgType);
            $LearnStartDate = false;

            if(isset($delivery->LearnStartDate) AND $delivery->LearnStartDate != '' AND $delivery->LearnStartDate != 'undefined')
                $LearnStartDate = new Date($delivery->LearnStartDate);
            else
                return '';
            if(!$LearnStartDate)
                return '';

            $found = DAO::getSingleValue($link, "SELECT COUNT(*) FROM lars201718.Core_LARS_Validity WHERE LearnAimRef = '$delivery->LearnAimRef' AND UPPER(ValidityCategory) = 'APPRENTICESHIPS' AND IF(EndDate IS NOT NULL, '$LearnStartDate' BETWEEN StartDate AND EndDate, '$LearnStartDate' >= StartDate) ;");
            if($LearnStartDate->after('01/08/2011') AND in_array($delivery->FundModel, array('35')) AND $DD07 == 'Y' AND $delivery->AimType == '3' AND $found == 0)
                return "LearnAimRef_09: This Learning aim reference (".$delivery->LearnAimRef.") is part of an Adult Skills funded Apprenticeship and is not valid in LARS on this Learning start date.";
        }
        return '';
    }



    private function rule_LearnAimRef_10($link, $ilr)
    {
        foreach($ilr->LearningDelivery as $delivery)
        {
            $LearnStartDate = false;

            if(isset($delivery->LearnStartDate) AND $delivery->LearnStartDate != '' AND $delivery->LearnStartDate != 'undefined')
                $LearnStartDate = new Date($delivery->LearnStartDate);
            else
                return '';


            //$found = DAO::getSingleValue($link, "SELECT COUNT(*) FROM lars201415.Core_LARS_Validity WHERE LearnAimRef = '$delivery->LearnAimRef' AND UPPER(ValidityCategory) = '1619_EFA' AND '$LearnStartDate' BETWEEN StartDate AND EndDate ;");
            $found = DAO::getSingleValue($link, "SELECT COUNT(*) FROM lars201718.Core_LARS_Validity WHERE LearnAimRef = '$delivery->LearnAimRef' AND UPPER(ValidityCategory) = '1619_EFA' AND (('$LearnStartDate' BETWEEN StartDate AND EndDate ) OR ('$LearnStartDate' >= StartDate AND EndDate IS NULL));");
            if(in_array($delivery->FundModel, array('25','82')) AND $found == 0)
                return "LearnAimRef_10: This Learning aim reference (".$delivery->LearnAimRef.") is 16-19 EFA funded and is not valid in LARS on this Learning start date.";
        }
    }

    private function rule_LearnAimRef_11($link, $ilr)
    {
        foreach($ilr->LearningDelivery as $delivery)
        {
            $LearnStartDate = false;
            $DD07 = $this->DD07($delivery->ProgType);
            $DD11 = $this->DD11($link, $ilr);
            if(isset($delivery->LearnStartDate) AND $delivery->LearnStartDate != '' AND $delivery->LearnStartDate != 'undefined')
                $LearnStartDate = new Date($delivery->LearnStartDate);
            else
                return '';

            $ldm= false;
            foreach($delivery->LearningDeliveryFAM as $ldf)
            {
                if($ldf->LearnDelFAMType=='LDM' && in_array($ldf->LearnDelFAMCode, array('034', '327', '328')))
                    $ldm = true;
            }

            $found = DAO::getSingleValue($link, "SELECT COUNT(*) FROM lars201415.Core_LARS_Validity WHERE LearnAimRef = '$delivery->LearnAimRef' AND UPPER(ValidityCategory) = 'ADULT_SKILLS' AND '$LearnStartDate' BETWEEN StartDate AND EndDate ;");
            if($LearnStartDate->after('2011-08-01') && $delivery->FundModel = '35' && $DD07 == 'N' && $delivery->ProgType != '24' && !$ldm && $DD11 == 'N' && !$found)
                return "LearnAimRef_11: This Learning aim reference (".$delivery->LearnAimRef.") is Adult Skills funded and is not valid in LARD on this Learning start date.";
        }
    }

    private function rule_LearnAimRef_37($link, $ilr)
    {
        $DD10 = $this->DD10($link, $ilr);
        foreach($ilr->LearningDelivery as $delivery)
        {
            $LearnStartDate = false;

            if(isset($delivery->LearnStartDate) AND $delivery->LearnStartDate != '' AND $delivery->LearnStartDate != 'undefined')
                $LearnStartDate = new Date($delivery->LearnStartDate);
            else
                return '';

            $found = DAO::getSingleValue($link, "SELECT COUNT(*) FROM lars201415.Core_LARS_Validity WHERE LearnAimRef = '$delivery->LearnAimRef' AND UPPER(ValidityCategory) = 'ANY' AND '$LearnStartDate' BETWEEN StartDate AND EndDate ;");
            if(($LearnStartDate->after('01/08/2011') && in_array($delivery->FundModel, array('81', '70'))) || ($delivery->FundModel == '99' && $DD10 == 'N') && !$found)
                return "LearnAimRef_37: This Learning aim reference (".$delivery->LearnAimRef.") is not valid in LARS for this Funding model and this Learning start date.";
        }
    }

    private function rule_LearnAimRef_16($link, $ilr)
    {
        foreach($ilr->LearningDelivery as $delivery)
        {
            $LearnStartDate = false;
            $DD07 = $this->DD07($delivery->ProgType);
            if(isset($delivery->LearnStartDate) AND $delivery->LearnStartDate != '' AND $delivery->LearnStartDate != 'undefined')
                $LearnStartDate = new Date($delivery->LearnStartDate);
            else
                return '';

            $res= false;
            foreach($delivery->LearningDeliveryFAM as $ldf)
            {
                if($ldf->LearnDelFAMType=='RES' && $ldf->LearnDelFAMCode == '1')
                    $res = true;
            }

            $found = DAO::getSingleValue($link, "SELECT COUNT(*) FROM lars201415.Core_LARS_Validity WHERE LearnAimRef = '$delivery->LearnAimRef' AND UPPER(ValidityCategory) = 'APPRENTICESHIPS' AND ('$LearnStartDate' < StartDate OR '$LearnStartDate' > LastNewStartDate);");
            if($LearnStartDate->after('01/08/2013') && in_array($delivery->FundModel, array('35')) && $DD07 == 'Y' && $delivery->AimType == '3' && !$res && !$found)
                return "LearnAimRef_16: This Learning aim reference (".$delivery->LearnAimRef.") is part of an Adult Skills funded Apprenticeship and is not valid in LARD for a new starter on this Learning start date.";
        }
    }

    /*private function rule_LearnAimRef_46($link, $ilr)
        {
            foreach($ilr->LearningDelivery as $delivery)
            {
                $LearnStartDate = false;
                $DD07 = $this->DD07($delivery->ProgType);
                $DD11 = $this->DD11($link, $ilr);

                $ldm= false;
                foreach($delivery->LearningDeliveryFAM as $ldf)
                {
                    if($ldf->LearnDelFAMType=='LDM' && in_array($ldf->LearnDelFAMCode, array('034')))
                        $ldm = true;
                }

                $ldm1= false;
                foreach($delivery->LearningDeliveryFAM as $ldf)
                {
                    if($ldf->LearnDelFAMType=='LDM' && in_array($ldf->LearnDelFAMCode, array('327', '328')))
                        $ldm1 = true;
                }

                $found = DAO::getSingleValue($link, "SELECT COUNT(*) FROM lars201415.Core_LARS_Validity WHERE LearnAimRef = '$delivery->LearnAimRef' AND UPPER(ValidityCategory) = 'UNEMPLOYED' AND '$LearnStartDate' BETWEEN StartDate AND EndDate ;");
                if(($delivery->FundModel == '35' && $DD07 == 'N' && $ldm) || ($ldm1) || ($delivery->ProgType == '24' && !$found))
                    return "LearnAimRef_46: This Learning aim reference (".$delivery->LearnAimRef.") is recorded as in receipt of JSA, ESA (WRAG), another state benefit or Universal credit, is part of a Traineeship or recorded with LDM327 or LDM328 and this Learning aim reference is not valid in LARS on this Learning start date.";
            }
        }*/

    private function rule_LearnAimRef_45($link, $ilr)
    {
        $DD10 = $this->DD10($link, $ilr);
        foreach($ilr->LearningDelivery as $delivery)
        {
            if($delivery->FundModel != '99')
                continue;
            $LearnStartDate = false;
            $DD07 = $this->DD07($delivery->ProgType);

            $found = DAO::getSingleValue($link, "SELECT COUNT(*) FROM lars201415.Core_LARS_Validity WHERE LearnAimRef = '$delivery->LearnAimRef' AND UPPER(ValidityCategory) = 'ADV_LEARN_LOAN' AND '$LearnStartDate' BETWEEN StartDate AND EndDate ;");
            if($DD10 == 'Y' && $DD07 == 'N' && !$found)
                return "LearnAimRef_45: This Learning aim reference (".$delivery->LearnAimRef.") is financed by a 24+ Advanced Learning Loan and is not valid in LARS on this Learning start date.";
        }
    }

    private function rule_LearnAimRef_12($link, $ilr)
    {
        foreach($ilr->LearningDelivery as $delivery)
        {
            if($delivery->FundModel != '10')
                continue;
            $LearnStartDate = false;
            if(isset($delivery->LearnStartDate) AND $delivery->LearnStartDate != '' AND $delivery->LearnStartDate != 'undefined')
                $LearnStartDate = new Date($delivery->LearnStartDate);
            else
                return '';

            $found = DAO::getSingleValue($link, "SELECT COUNT(*) FROM lars201415.Core_LARS_Validity WHERE LearnAimRef = '$delivery->LearnAimRef' AND UPPER(ValidityCategory) = 'COMM_LEARN' AND '$LearnStartDate' BETWEEN StartDate AND EndDate ;");
            if(!$found && $delivery->LearnAimRef != 'ZPROG001')
                return "LearnAimRef_12: This Learning aim reference (".$delivery->LearnAimRef.") is Community Learning funded and is not valid in LARS on this Learning start date.";
        }
    }



    private function rule_LearnStartDate_05($link, $ilr)
    {
        if(!isset($ilr->DateOfBirth) || trim($ilr->DateOfBirth) == '' || is_null($ilr->DateOfBirth == ''))
            return "Learner Date of Birth is blank ";
        foreach($ilr->LearningDelivery as $delivery)
        {
            if(!isset($delivery->LearnStartDate) OR $delivery->LearnStartDate == '' OR $delivery->LearnStartDate == 'undefined') return $delivery->LearnAimRef . ' - Learning Start Date is blank';
            $LearnStartDate = new Date($delivery->LearnStartDate);
            if($LearnStartDate->before($ilr->DateOfBirth))
            {
                return "LearnStartDate_05: The Learning start date must be after the learner's Date of birth (".$delivery->LearnAimRef.")";
            }
        }
    }

    /*private function rule_LearnStartDate_06($link, $ilr)
        {
            foreach($ilr->LearningDelivery as $delivery)
            {
                if($delivery->AimType=='1')
                {
                    $LearnStartDate = Date::toMySQL($delivery->LearnStartDate);
                    $FworkCode = $delivery->FworkCode;
                    $ProgType = $delivery->ProgType;
                    $found = DAO::getSingleValue($link, "SELECT COUNT(*) FROM lad201213.frameworks WHERE FRAMEWORK_TYPE_CODE = '$ProgType' AND FRAMEWORK_CODE = '$FworkCode' AND (EFFECTIVE_TO > '$LearnStartDate' OR EFFECTIVE_TO IS NULL);");
                    if($found=='0' && ($ProgType=='2' || $ProgType=='3' || $ProgType=='10' || $ProgType=='20' || $ProgType=='21' || $ProgType=='22' || $ProgType=='23' || $ProgType=='25'))
                    {
                        return "LearnStartDate_06: If the Framework code is returned, then the learner must not start the programme after the 'Effective to' date in the Framework table in LARA, for this framework, if the learner is a new start (".$delivery->LearnAimRef.")";
                    }
                }
            }
        }*/

    /*private function rule_LearnStartDate_07($link, $ilr)
        {
            foreach($ilr->LearningDelivery as $delivery)
            {
                if($delivery->AimType=='2')
                {
                    $LearnStartDate = Date::toMySQL($delivery->LearnStartDate);
                    $LearnStartDateDate = new Date($delivery->LearnStartDate);
                    if($LearnStartDateDate->after('31/07/2012'))
                        $DD08 = 'Y';
                    else
                        $DD08 = 'N';
                    $FworkCode = $delivery->FworkCode;
                    $LearnAimRef = $delivery->LearnAimRef;
                    $ProgType = $delivery->ProgType;
                    $PwayCode = $delivery->PwayCode;
                    $found = DAO::getSingleValue($link, "SELECT COUNT(*) FROM lad201213.framework_aims WHERE LEARNING_AIM_REF = '$LearnAimRef' AND FRAMEWORK_TYPE_CODE = '$ProgType' AND FRAMEWORK_CODE = '$FworkCode' AND FRAMEWORK_PATHWAY_CODE = '$PwayCode' AND (EFFECTIVE_TO > '$LearnStartDate' OR EFFECTIVE_TO IS NULL);");
                    if($FworkCode!='' && $DD08=="Y" && $found=='0')
                    {
                        return "LearnStartDate_07: If the Framework code is returned, then the learner must not start the learning aim, if the Learning start date of the programme is after the 'Effective to' date in the Framework aims table in LARA, for this aim on this framework, if the learner is a new start (".$delivery->LearnAimRef.")";
                    }
                }
            }
        }*/


    private function rule_LearnPlanEndDate_02($link, $ilr)
    {
        foreach($ilr->LearningDelivery as $delivery)
        {
            if(!isset($delivery->LearnStartDate) OR $delivery->LearnStartDate == '' OR $delivery->LearnStartDate == 'undefined') return $delivery->LearnAimRef . ' - Learning Start Date is blank';
            $LearnPlanEndDate = new Date($delivery->LearnPlanEndDate);
            if($LearnPlanEndDate->before($delivery->LearnStartDate))
            {
                return "LearnPlanEndDate_02: The Learning planned end date must not be before the Learning start date (".$delivery->LearnAimRef.")";
            }
        }
    }

    private function rule_AchDate_05($link, $ilr)
    {
        foreach($ilr->LearningDelivery as $delivery)
        {
            if($delivery->AchDate!='' && $delivery->AchDate!='undefined' && $delivery->AchDate!='dd/mm/yyyy' && $delivery->LearnActEndDate!='' && $delivery->LearnActEndDate!='undefined' && $delivery->LearnActEndDate!='dd/mm/yyyy')
            {
                $AchDate = new Date("".$delivery->AchDate);
                if($AchDate->before("".$delivery->LearnActEndDate))
                    return "AchDate_05: If returned, the Achievement date must be on or after the Learning actual end date (".$delivery->LearnAimRef.")";
            }
        }
    }

    private function rule_LearnActEndDate_01($link, $ilr)
    {
        foreach($ilr->LearningDelivery as $delivery)
        {
            if(!isset($delivery->LearnStartDate) OR $delivery->LearnStartDate == '' OR $delivery->LearnStartDate == 'undefined') return $delivery->LearnAimRef . ' - Learning Start Date is blank';
            if($delivery->LearnActEndDate!='' && $delivery->LearnActEndDate!='undefined' && $delivery->LearnActEndDate!='dd/mm/yyyy')
            {
                $LearnActEndDate = new Date("".$delivery->LearnActEndDate);
                $LearnStartDate = "".$delivery->LearnStartDate;
                if($LearnActEndDate->before($LearnStartDate))
                    return "LearnActEndDate_01: The learning actual end date must not be before the learning start date (".$delivery->LearnAimRef.")";
            }
        }
    }

    private function rule_LearnActEndDate_04($link, $ilr)
    {
        foreach($ilr->LearningDelivery as $delivery)
        {
            if($delivery->LearnActEndDate!='' && $delivery->LearnActEndDate!='undefined' && $delivery->LearnActEndDate!='dd/mm/yyyy')
            {
                $LearnActEndDate = new Date("".$delivery->LearnActEndDate);
                if($LearnActEndDate->after(date('d/m/Y')))
                    return "LearnActEndDate_04: The Learning actual end date must not be after the file preparation date/entry date (".$delivery->LearnAimRef.")";
            }
        }
    }

    private function rule_AchDate_07($link, $ilr)
    {
        foreach($ilr->LearningDelivery as $delivery)
        {
            if($delivery->AchDate!='' && $delivery->AchDate!='undefined' && $delivery->AchDate!='dd/mm/yyyy')
            {
                $AchDate = new Date("".$delivery->AchDate);
                if($AchDate->after(date('d/m/Y')))
                    return "AchDate_07: If returned, the Achievement date must not be after the file preparation date/entry date (".$delivery->LearnAimRef.")";
            }
        }
    }

    private function rule_Outcome_04($link, $ilr)
    {
        foreach($ilr->LearningDelivery as $delivery)
        {
            if($delivery->AchDate!='' && $delivery->AchDate!='undefined' && $delivery->AchDate!='dd/mm/yyyy')
            {
                if($delivery->Outcome!='1' and !($delivery->FundModel=='36' and $delivery->ProgType=='25'))
                    return "Outcome_04: If the Achievement date is returned then the Outcome must be 'Achieved' (".$delivery->LearnAimRef.")";
            }
        }
    }

    private function rule_ProgType_01($link, $ilr)
    {
        foreach($ilr->LearningDelivery as $delivery)
        {
            if($delivery->ProgType=='' && ($delivery->AimType=='1' || $delivery->AimType=='3'))
            {
                return "ProgType_01: The Programme type must be returned (".$delivery->LearnAimRef.")";
            }
        }
    }


    private function rule_LearnDelFAMType_01($link, $ilr)
    {
        $FundModelsForThisRule = array('25','82','35','81','70','10');
        foreach($ilr->LearningDelivery as $delivery)
        {
            if(in_array($delivery->FundModel, $FundModelsForThisRule))
            {
                $sof=0;
                foreach($delivery->LearningDeliveryFAM as $ldf)
                {
                    if($ldf->LearnDelFAMType=='SOF')
                    {
                        if($ldf->LearnDelFAMCode!='' && $ldf->LearnDelFAMCode!='undefined')
                            $sof++;
                    }
                }
                if($sof==0)
                    return "LearnDelFAMType_01: The Source of funding has not been returned (".$delivery->LearnAimRef.")";
            }
        }
    }

    private function rule_LearnDelFAMType_02($link, $ilr)
    {
        foreach($ilr->LearningDelivery as $delivery)
        {
            if($delivery->FundModel =='35')
            {
                $ffi=0;
                foreach($delivery->LearningDeliveryFAM as $ldf)
                {
                    if($ldf->LearnDelFAMType=='FFI')
                    {
                        if($ldf->LearnDelFAMCode!='' && $ldf->LearnDelFAMCode!='undefined')
                            $ffi++;
                    }
                }
                if($ffi==0)
                    return "LearnDelFAMType_02: The Full or co-funding indicator must be returned for ALR and ER funded learning aims. (".$delivery->LearnAimRef.")";
            }
        }
    }


    private function rule_FworkCode_01($link, $ilr)
    {
        foreach($ilr->LearningDelivery as $delivery)
        {
            $ProgType = $delivery->ProgType;
            if($delivery->ProgType!='25' && $delivery->ProgType!='99' && $delivery->ProgType!='' && $delivery->FworkCode=='' && ($ProgType=='2' || $ProgType=='3' || $ProgType=='10' || $ProgType=='20' || $ProgType=='21' || $ProgType=='22' || $ProgType=='23' || $ProgType=='25'))
                return "FworkCode_01: The Framework code must be returned for all aims that are part of a programme (".$delivery->LearnAimRef.")";
        }
    }

    private function rule_FworkCode_05($link, $ilr)
    {
        foreach($ilr->LearningDelivery as $delivery)
        {
            if($delivery->FworkCode!='' && $delivery->FworkCode!='undefined' && $delivery->LearnAimRef!='ZPROG001' && $delivery->FundModel!='99')
            {
                $LearnAimRef = "" . $delivery->LearnAimRef;
                $PwayCode = "" . $delivery->PwayCode;
                $FworkCode = "" . $delivery->FworkCode;
                $ProgType = "" . $delivery->ProgType;
                $DD07 = $this->DD07($ProgType);

                $first = DAO::getSingleValue($link, "SELECT COUNT(*) FROM lars201718.Core_LARS_FrameworkAims WHERE LearnAimRef = '$LearnAimRef' AND FworkCode = '$FworkCode' AND PwayCode = '$PwayCode' AND ProgType = '$ProgType'");
                $second = DAO::getSingleValue($link, "SELECT COUNT(*) FROM lars201718.Core_LARS_FrameworkCmnComp WHERE ProgType = '$ProgType' AND FworkCode = '$FworkCode' AND PwayCode = '$PwayCode' AND CommonComponent IN (SELECT FrameworkCommonComponent FROM lars201718.Core_LARS_LearningDelivery WHERE LearnAimRef = '$LearnAimRef');");

                if($delivery->AimType == '3' && $DD07 && $ProgType != '25' && $first=='0' && $second=='0')
                    return "FworkCode_05: The Framework code, Pathway code, and Programme type combination is not valid in LARS for this Learning aim reference. (".$delivery->LearnAimRef.")";
            }
        }
    }

    private function rule_WithdrawReason_03($link, $ilr)
    {
        foreach($ilr->LearningDelivery as $delivery)
        {
            if($delivery->CompStatus=='3' && $delivery->WithdrawReason=='')
                return "WithdrawReason_03: The Withdrawal reason must be returned if the Completion status is 'Withdrawn'. (".$delivery->LearnAimRef.")";
        }
    }



    private function rule_CompStatus_04($link, $ilr)
    {
        foreach($ilr->LearningDelivery as $delivery)
        {
            if($delivery->CompStatus!='1' && $delivery->Outcome=='')
                return "CompStatus_04: If the outcome is not returned, the completion status must be continuing. (".$delivery->LearnAimRef.")";
        }
    }



    private function rule_R52($link, $ilr)
    {
        foreach($ilr->LearningDelivery as $delivery)
        {
            $ldm = Array();
            foreach($delivery->LearningDeliveryFAM as $ldf)
            {
                if($ldf->LearnDelFAMCode!='' && $ldf->LearnDelFAMCode!='undefined')
                {
                    $value = $ldf->LearnDelFAMType . $ldf->LearnDelFAMCode;
                    if(!in_array($value,$ldm))
                        $ldm[] = $value;
                    else
                        return "R52: No two Learning Delivery FAM records should have the same UKPRN,  Learner Reference number, Aim Sequence number, LearningDeliveryFAM code and LearningDeliveryFAM type  (".$delivery->LearnAimRef.")";
                }
            }
        }
    }

    private function rule_R57($link, $ilr)
    {
        foreach($ilr->LearningDelivery as $delivery)
        {
            if($delivery->FundModel=='35' && $delivery->AimType=='1' && isset($delivery->AchDate) && ("" . $delivery->AchDate)!='' && ("".$delivery->AchDate)!='dd/mm/yyyy')
                $fach = new Date("" . $delivery->AchDate);
        }
        if(isset($fach))
        {
            foreach($ilr->LearningDelivery as $delivery)
            {
                if($delivery->AimType!='1' && isset($delivery->AchDate) && $delivery->AchDate!='' && $delivery->AchDate!='dd/mm/yyyy')
                    if($fach->before($delivery->AchDate))
                        return "R57: The Achievement date for the programme aim must not be before the Achievement date of the latest aim within that programme. (".$delivery->LearnAimRef.")";
            }
        }
    }

    public static function isAlphaNum($ch)
    {
        if((ord($ch)>=48 && ord($ch)<=57) || (ord($ch)>=97 && ord($ch)<=122) || (ord($ch)>=65 && ord($ch)<=90))
            return true;
        else
            return false;
    }

    public static function isDigit($ch)
    {
        if(ord($ch)>=48 && ord($ch)<=57)
            return true;
        else
            return false;
    }

    public static function isAlpha($ch)
    {
        try
        {
            if((ord($ch)>=97 && ord($ch)<=122) || (ord($ch)>=65 && ord($ch)<=90))
                return true;
            else
                return false;
        }
        catch(Exception $e)
        {
            throw new Exception($ch);
        }
    }

    // Pass Separator i.e. /, end date and begin date
    public static function dateDiff($dformat, $endDate, $beginDate)
    {

        try
        {
            $date_parts1=explode($dformat, $beginDate);
            $date_parts2=explode($dformat, $endDate);

            $start 	= mktime(0,0,0,$date_parts1[0], $date_parts1[1], $date_parts1[2]);
            $end 	= mktime(0,0,0,$date_parts2[0], $date_parts2[1], $date_parts2[2]);

            $d = $end - $start;
            $fullDays = floor($d/(60*60*24));

            $start_date=gregoriantojd($date_parts1[0], $date_parts1[1], $date_parts1[2]);
            $end_date=gregoriantojd($date_parts2[0], $date_parts2[1], $date_parts2[2]);
        }
        catch(Exception $e)
        {
            throw new Exception("Wrong date");
        }
        //return $end_date - $start_date;

        return $fullDays;

    }


    private function dummy($rubbish)
    {
        echo "<p>dummy()</p>";
    }


    function GetAge($DOB, $DOD) {

        // Get current date
        $CD = date("d/n/Y");
        list($cd,$cm,$cY) = explode("/",$CD);

        // Get date of birth
        list($bd,$bm,$bY) = explode("/",$DOB);
        // is there a date of death?

        if ($DOD!="" && $DOD != "0000-00-00") {

            // Animal is dead
            list($dd,$dm,$dY) = explode("/",$DOD);
            if ($bY == $dY) {
                $months = $dm - $bm;
                if ($months == 0 || $months > 1) {
                    return "$months months";
                } else
                    return "$months month";
            } else
                $years = ( $dm.$dd < $bm.$bd ? $dY-$bY-1 : $dY-$bY );
            if ($years == 0 || $years > 1) {
                return $years;
            } else {
                return $years;
            }

        } else {

            // Animal is alive
            if ($bY != "" && $bY != "0000") {

                if ($bY == $cY) {
                    // Birth year is current year
                    $months = $cm - $bm;
                    if ($months == 0 || $months > 1) {
                        return "$months months";
                    } else
                        return "$months month";
                } else if ($cY - $bY == 1 && $cm - $bm < 12) {
                    // Born within 12 months, either side of 01 Jan
                    //Determine days and therefore proportion of month
                    if ($cd - $bd > 0) {
                        $xm = 0;
                    } else {
                        $xm = 1;
                    }
                    $months = 12 - $bm + $cm - $xm;
                    if ($months == 0 || $months > 1) {
                        return "$months months";
                    } else {
                        return "$months month";
                    }
                }

                // Animal older than 12 months, return in years
                $years = (date("md") < $bm.$bd ? date("Y")-$bY-1 : date("Y")-$bY );
                if ($years == 0 || $years > 1) {
                    return "$years years";
                } else {
                    return "$years year";
                }

            } else
                return "No Date of Birth!";
        }
    }

    function checkPostcode (&$toCheck) {

        // Permitted letters depend upon their position in the postcode.
        $alpha1 = "[abcdefghijklmnoprstuwyz]";                          // Character 1
        $alpha2 = "[abcdefghklmnopqrstuvwxy]";                          // Character 2
        $alpha3 = "[abcdefghjkpmnrstuvwxy]";                            // Character 3
        $alpha4 = "[abehmnprvwxy]";                                     // Character 4
        $alpha5 = "[abdefghjlnpqrstuwxyz]";                             // Character 5
        $BFPOa5 = "[abdefghjlnpqrst]{1}";                               // BFPO character 5
        $BFPOa6 = "[abdefghjlnpqrstuwzyz]{1}";                          // BFPO character 6

        // Expression for BF1 type postcodes
        $pcexp[0] =  '/^(bf1)([[:space:]]{0,})([0-9]{1}' . $BFPOa5 . $BFPOa6 .')$/';

        // Expression for postcodes: AN NAA, ANN NAA, AAN NAA, and AANN NAA with a space
        $pcexp[1] = '/^('.$alpha1.'{1}'.$alpha2.'{0,1}[0-9]{1,2})([[:space:]]{0,})([0-9]{1}'.$alpha5.'{2})$/';

        // Expression for postcodes: ANA NAA
        $pcexp[2] =  '/^('.$alpha1.'{1}[0-9]{1}'.$alpha3.'{1})([[:space:]]{0,})([0-9]{1}'.$alpha5.'{2})$/';

        // Expression for postcodes: AANA NAA
        $pcexp[3] =  '/^('.$alpha1.'{1}'.$alpha2.'{1}[0-9]{1}'.$alpha4.')([[:space:]]{0,})([0-9]{1}'.$alpha5.'{2})$/';

        // Exception for the special postcode GIR 0AA
        $pcexp[4] =  '/^(gir)([[:space:]]{0,})(0aa)$/';

        // Standard BFPO numbers
        $pcexp[5] = '/^(bfpo)([[:space:]]{0,})([0-9]{1,4})$/';

        // c/o BFPO numbers
        $pcexp[6] = '/^(bfpo)([[:space:]]{0,})(c\/o([[:space:]]{0,})[0-9]{1,3})$/';

        // Overseas Territories
        $pcexp[7] = '/^([a-z]{4})([[:space:]]{0,})(1zz)$/';

        // Anquilla
        $pcexp[8] = '/^ai-2640$/';

        // Load up the string to check, converting into lowercase
        $postcode = strtolower($toCheck);

        if(trim($postcode) == 'zz99 9zz')
            return true;

        // Assume we are not going to find a valid postcode
        $valid = false;

        // Check the string against the six types of postcodes
        foreach ($pcexp as $regexp) {

            if (preg_match($regexp,$postcode, $matches)) {

                // Load new postcode back into the form element
                $postcode = strtoupper ($matches[1] . ' ' . $matches [3]);

                // Take account of the special BFPO c/o format
                $postcode = preg_replace ('/C\/O([[:space:]]{0,})/', 'c/o ', $postcode);

                // Take acount of special Anquilla postcode format (a pain, but that's the way it is)
                if (preg_match($pcexp[7],strtolower($toCheck), $matches)) $postcode = 'AI-2640';

                // Remember that we have found that the code is valid and break from loop
                $valid = true;
                break;
            }
        }

        // Return with the reformatted valid postcode in uppercase if the postcode was
        // valid
        if ($valid){
            $toCheck = $postcode;
            return true;
        }
        else return false;
    }



    public $report = NULL;

}


