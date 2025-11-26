<?php
class migrate_ilrs implements IAction
{
	public function execute(PDO $link)
	{
		$xml = isset($_REQUEST['xml'])?$_REQUEST['xml']:'';
		$contract_id = isset($_REQUEST['contract_id'])?$_REQUEST['contract_id']:'';
		$contract_year = DAO::getSingleValue($link, "select contract_year from contracts where id = '$contract_id'");
		$LearnRefNumber = isset($_REQUEST['learnrefnumber'])?$_REQUEST['learnrefnumber']:'';
		$tr_id = isset($_REQUEST['tr_id'])?$_REQUEST['tr_id']:'';
		$contract_from = isset($_REQUEST['contract_from'])?$_REQUEST['contract_from']:'';
		if($LearnRefNumber!='' || $tr_id!='')
		{
			$contract_id = DAO::getSingleValue($link, "select id from contracts where parent_id = '$contract_from'");
			if($contract_id=='')
				throw new Exception("Sunesis is unable to find an associated contract to migrate this ILR into 2015-16. Please contact support to migrate this ILR");
			$contract_year = DAO::getSingleValue($link, "select contract_year from contracts where id = '$contract_id'");
		}

        if($contract_year==2018)
        {
            $cont_q = "SELECT id, contract_id from tr where contract_id in (select id from contracts where contract_year = 2017)";
            if($cont_st = $link->query($cont_q))
            {
                while($cont_row = $cont_st->fetch())
                {
                    $tr_id = $cont_row['id'];
                    $contract_id  = $cont_row['contract_id'];
                    $HasBeenMigrated = DAO::getSingleValue($link, "select count(*) from ilr inner join contracts on contracts.id = ilr.contract_id where tr_id = '$tr_id' and contract_year = 2018");
                    if($HasBeenMigrated==0)
                    {
                        $xml = DAO::getSingleValue($link, "select ilr from ilr where tr_id = '$tr_id' and contract_id = '$contract_id' order by submission desc limit 0,1");
                        $ilr2 = str_replace("'","&apos;",$xml);
                        if($ilr2=='')
                            pre("select ilr from ilr where tr_id = '$tr_id' and contract_id = '$contract_id' order by submission desc limit 0,1");
                        $ilr3 = @XML::loadSimpleXML($ilr2);

                        $L03 = "".$ilr3->LearnRefNumber;

                        $ilr3->PrevLearnRefNumber = '';
                        $ilr3->PrevUKPRN = '';
                        $ilr3->Accom = '';
                        $ilr3->ALSCost = '';
                        $ilr3->PlanLearnHours = '';
                        $ilr3->PlanEEPHours = '';
                        $ilr3->MathGrade = '';
                        $ilr3->EngGrade = '';

                        foreach($ilr3->LearnerFAM as $learnerfam)
                        {
                            if($learnerfam->LearnFAMType=="HNS")
                                $learnerfam->LearnFAMCode = "";
                            if($learnerfam->LearnFAMType=="LSR")
                                $learnerfam->LearnFAMCode = "";
                            if($learnerfam->LearnFAMType=="SEN")
                                $learnerfam->LearnFAMCode = "";
                            if($learnerfam->LearnFAMType=="EDF")
                                $learnerfam->LearnFAMCode = "";
                            if($learnerfam->LearnFAMType=="MCF")
                                $learnerfam->LearnFAMCode = "";
                            if($learnerfam->LearnFAMType=="ECF")
                                $learnerfam->LearnFAMCode = "";
                            if($learnerfam->LearnFAMType=="FME")
                                $learnerfam->LearnFAMCode = "";
                            if($learnerfam->LearnFAMType=="PPE")
                                $learnerfam->LearnFAMCode = "";
                        }

                        foreach($ilr3->LearnerHE as $learnerhe)
                        {
                            $learnerhe->LearnerHEFinancialSupport='';
                            foreach($learnerhe->LearnerHEFinancialSupport as $learnerhefs)
                            {
                                $learnerhefs->FINTYPE='';
                                $learnerhefs->FINAMOUNT='';
                            }
                        }

                        $earliest_start_date = new Date("2017-08-01");
                        foreach($ilr3->LearningDelivery as $ld)
                        {
                            if($ld->ProgType=='10')
                                $ld->ProgType='';
                            $sd = new Date($ld->LearnStartDate);

                            foreach($ld->LearningDeliveryFAM as $ldm)
                            {
                                if($ldm->LearnDelFAMType=='TBS')
                                {
                                    $stdchild = $ld->addChild("StdCode",$ldm->LearnDelFAMCode);
                                    $ldm->LearnDelFAMCode=='';
                                }

                                if($ldm->LearnDelFAMType=='LDM' and $sd->before("01/08/2013") and $ldm->LearnDelFAMCode=='125' and $ld->FundModel=='35' and ($ld->ProgType=='2' or $ld->ProgType=='3' or $ld->ProgType=='10' or $ld->ProgType=='20' or $ld->ProgType=='21' or $ld->ProgType=='22' or $ld->ProgType=='23'))
                                {
                                    $ldm->LearnDelFAMCode='350';
                                }

                                if($ldm->LearnDelFAMType=='WPL' and $sd->before("01/08/2013") and $ld->FundModel=='35' and ($ld->ProgType=='2' or $ld->ProgType=='3' or $ld->ProgType=='10' or $ld->ProgType=='20' or $ld->ProgType=='21' or $ld->ProgType=='22' or $ld->ProgType=='23'))
                                {
                                    $ldm->LearnDelFAMCode='350';
                                }


                            }
                        }
                        // Migration Scenarios
                        /*$migrate = 0;
                        foreach($ilr3->LearningDelivery as $ld)
                        {
                            // 11.1
                            $sd = "" . $ld->LearnActEndDate;
                            if("".$ld->FundModel!="70")
                                if($sd=='' || $sd=='00000000' || $sd=='dd/mm/yyyy')
                                {
                                    $migrate = 1;
                                }
                                else
                                {
                                    $act_end_date = new Date($sd);
                                    if($act_end_date->after('31/07/2017'))
                                        $migrate = 1;
                                }

                            // 11.2
                            if("".$ld->FundModel!="70" and ($ld->AimType=='4' or $ld->AimType=='5') and ($ld->Outcome=='8' or $ld->CompStat=='6'))
                                if(Date::isDate($sd))
                                {
                                    $migrate = 1;
                                }

                            // 12
                            if($ld->FundModel=="70")
                                $migrate = 1;

                            // 15
                            if($ld->AimType=='1' and $ld->CompStat=='6')
                                $migrate = 1;
                        }*/
                        $shouldMigrate = false;
                        $vo = Ilr2017::loadFromDatabase($link, "W12", $contract_id, $tr_id, $L03);
                        foreach($vo->LearningDelivery as $LearningDelivery)
                        {
                            if(!Date::isDate($LearningDelivery->LearnActEndDate))
                            {
                                $shouldMigrate = true;
                            }
                            /*else
                            {
                                $LearnActDate = new Date($LearningDelivery->LearnActEndDate);
                                if($LearnActDate->after("31/07/2017"))
                                    $shouldMigrate = true;
                            }
                            */

                            if($LearningDelivery->CompStatus==6)
                            {
                                $LearnStartDate=Date::toMySQL("".$LearningDelivery->LearnStartDate);
                                $restart = DAO::getSingleValue($link, "select count(*) from tr where l03 = '$L03' and start_date > '$LearnStartDate'");
                                if($restart=="" or $restart==0)
                                    $shouldMigrate = true;
                            }

                            if(($LearningDelivery->FundModel==81 and $LearningDelivery->ProgType==25) or $LearningDelivery->FundModel==36)
                            {
                                if($LearningDelivery->CompStatus==6)
                                {
                                    $LearnStartDate=Date::toMySQL("".$LearningDelivery->LearnStartDate);
                                    $restart = DAO::getSingleValue($link, "select count(*) from tr where l03 = '$L03' and start_date > '$LearnStartDate' and status_code=2");
                                    if($restart=="" or $restart==0)
                                        $shouldMigrate = true;
                                }
                            }

                            if($LearningDelivery->Outcome==8)
                            {
                                $shouldMigrate = true;
                            }
                        }


                        $ilr3 = substr($ilr3->asXML(),22);
                        $ilr3 = str_replace("'","&amp;",$ilr3);

                        $migrate = $shouldMigrate;
                        if($migrate==1)
                        {
                            $new_contract_id = DAO::getSingleValue($link, "select id from contracts where parent_id = $contract_id");
                            if($new_contract_id)
                            {
                                DAO::execute($link, "INSERT INTO ilr values('', '$L03', '', '$ilr3', 'W01', 'ER', $tr_id, 1, 0, 1, 1, $new_contract_id);");
                                DAO::execute($link, "UPDATE tr SET contract_id = $new_contract_id where id = $tr_id");
                            }
                        }
                    }
                }
            }
        pre("Complete");
        }
        elseif($contract_year==2016)
        {
            $cont_q = "SELECT id, contract_id from tr where contract_id in (select id from contracts where contract_year = 2015)";
            if($cont_st = $link->query($cont_q))
            {
                while($cont_row = $cont_st->fetch())
                {
                    $tr_id = $cont_row['id'];
                    $contract_id  = $cont_row['contract_id'];
                    $HasBeenMigrated = DAO::getSingleValue($link, "select count(*) from ilr inner join contracts on contracts.id = ilr.contract_id where tr_id = '$tr_id' and contract_year = 2016");
                    if($HasBeenMigrated==0)
                    {
                        $xml = DAO::getSingleValue($link, "select ilr from ilr where tr_id = '$tr_id' and contract_id = '$contract_id' order by submission desc limit 0,1");
                        $ilr2 = str_replace("'","&apos;",$xml);
                        $ilr3 = @XML::loadSimpleXML($ilr2);

                        $L03 = $ilr3->LearnRefNumber;

                        $ilr3->PrevLearnRefNumber = '';
                        $ilr3->PrevUKPRN = '';
                        $ilr3->Accom = '';
                        $ilr3->ALSCost = '';
                        $ilr3->PlanLearnHours = '';
                        $ilr3->PlanEEPHours = '';
                        $ilr3->MathGrade = '';
                        $ilr3->EngGrade = '';

                        foreach($ilr3->LearnerFAM as $learnerfam)
                        {
                            if($learnerfam->LearnFAMType=="HNS")
                                $learnerfam->LearnFAMCode = "";
                            if($learnerfam->LearnFAMType=="LSR")
                                $learnerfam->LearnFAMCode = "";
                            if($learnerfam->LearnFAMType=="SEN")
                                $learnerfam->LearnFAMCode = "";
                            if($learnerfam->LearnFAMType=="EDF")
                                $learnerfam->LearnFAMCode = "";
                            if($learnerfam->LearnFAMType=="MCF")
                                $learnerfam->LearnFAMCode = "";
                            if($learnerfam->LearnFAMType=="ECF")
                                $learnerfam->LearnFAMCode = "";
                            if($learnerfam->LearnFAMType=="FME")
                                $learnerfam->LearnFAMCode = "";
                            if($learnerfam->LearnFAMType=="PPE")
                                $learnerfam->LearnFAMCode = "";
                        }

                        foreach($ilr3->LearnerHE as $learnerhe)
                        {
                            $learnerhe->LearnerHEFinancialSupport='';
                            foreach($learnerhe->LearnerHEFinancialSupport as $learnerhefs)
                            {
                                $learnerhefs->FINTYPE='';
                                $learnerhefs->FINAMOUNT='';
                            }
                        }

                        $earliest_start_date = new Date("2016-08-01");
                        foreach($ilr3->LearningDelivery as $ld)
                        {
                            if($ld->ProgType=='10')
                                $ld->ProgType='';
                            $sd = new Date($ld->LearnStartDate);

                            foreach($ld->LearningDeliveryFAM as $ldm)
                            {
                                if($ldm->LearnDelFAMType=='TBS')
                                {
                                    $stdchild = $ld->addChild("StdCode",$ldm->LearnDelFAMCode);
                                    $ldm->LearnDelFAMCode=='';
                                }

                                if($ldm->LearnDelFAMType=='LDM' and $sd->before("01/08/2013") and $ldm->LearnDelFAMCode=='125' and $ld->FundModel=='35' and ($ld->ProgType=='2' or $ld->ProgType=='3' or $ld->ProgType=='10' or $ld->ProgType=='20' or $ld->ProgType=='21' or $ld->ProgType=='22' or $ld->ProgType=='23'))
                                {
                                    $ldm->LearnDelFAMCode='350';
                                }

                                if($ldm->LearnDelFAMType=='WPL' and $sd->before("01/08/2013") and $ld->FundModel=='35' and ($ld->ProgType=='2' or $ld->ProgType=='3' or $ld->ProgType=='10' or $ld->ProgType=='20' or $ld->ProgType=='21' or $ld->ProgType=='22' or $ld->ProgType=='23'))
                                {
                                    $ldm->LearnDelFAMCode='350';
                                }


                            }
                        }
                        // Migration Scenarios
                        $migrate = 0;
                        foreach($ilr3->LearningDelivery as $ld)
                        {
                            // 11.1
                            $sd = "" . $ld->LearnActEndDate;
                            if("".$ld->FundModel!="70")
                                if($sd=='' || $sd=='00000000' || $sd=='dd/mm/yyyy')
                                {
                                    $migrate = 1;
                                }
                                else
                                {
                                    $act_end_date = new Date($sd);
                                    if($act_end_date->after('31/07/2016'))
                                        $migrate = 1;
                                }

                            // 11.2
                            if("".$ld->FundModel!="70" and ($ld->AimType=='4' or $ld->AimType=='5') and ($ld->Outcome=='8' or $ld->CompStat=='6'))
                                if(Date::isDate($sd))
                                {
                                    $migrate = 1;
                                }

                            // 12
                            if($ld->FundModel=="70")
                                $migrate = 1;

                            // 15
                            if($ld->AimType=='1' and $ld->CompStat=='6')
                                 $migrate = 1;

                        }

                        $ilr3 = substr($ilr3->asXML(),22);
                        $ilr3 = str_replace("'","&amp;",$ilr3);

                        $bil = DAO::getSingleValue($link, "select id from tr where id = '$tr_id' and status_code = 6");
                        if($bil>0)
                            $migrate=1;
                        //$migrate= 0;
                        if($migrate==1)
                        {
                            $new_contract_id = DAO::getSingleValue($link, "select id from contracts where parent_id = $contract_id");
                            if($new_contract_id)
                            {
                                DAO::execute($link, "INSERT INTO ilr values('', '$L03', '', '$ilr3', 'W01', 'ER', $tr_id, 1, 0, 1, 1, $new_contract_id);");
                                DAO::execute($link, "UPDATE tr SET contract_id = $new_contract_id where id = $tr_id");
                            }
                        }
                    }
                }
            }
        }
		elseif($contract_year==2015 && $tr_id!='' && $contract_id != '')
		{
			$q = "SELECT L01,L03, A09, ilr, 'W01', contract_type, tr_id, is_complete, is_valid, is_approved, is_active FROM ilr WHERE contract_id = '$contract_from' AND submission='W13' and tr_id = '$tr_id';";
			if($st = $link->query($q))
			{
				while($row = $st->fetch())
				{
					$migrate = 0;
					$L03 = $row['L03'];
					$tr_id = $row['tr_id'];
					$is_complete = $row['is_complete'];
					$is_approved = $row['is_approved'];
					$is_active = $row['is_active'];
					$xml = $row['ilr'];
					$ilr2 = str_replace("'","&apos;",$xml);
					$ilr3 = @XML::loadSimpleXML($ilr2);
					$soforig = '';

					$ilr3->PrevLearnRefNumber = '';
					$ilr3->PrevUKPRN = '';
					$ilr3->Accom = '';
					$ilr3->ALSCost = '';
					$ilr3->PlanLearnHours = '';
					$ilr3->PlanEEPHours = '';
					$ilr3->Dest = '';
					$ilr3->addChild("MathGrade","");
					$ilr3->addChild("EngGrade","");

					$ds = '';
					$ld = '';
					foreach($ilr3->LLDDandHealthProblem as $lldd)
					{
						if($lldd->LLDDType=="DS" && ("".$lldd->LLDDCode!=""))
							$ds = "".$lldd->LLDDCode;
						elseif($lldd->LLDDType=="LD" && ("".$lldd->LLDDCode!=""))
							$ld = "".$lldd->LLDDCode;
					}
					unset($ilr3->LLDDandHealthProblem);
					$ds99 = 0;
					if($ds!='' || $ld!='')
					{
						if($ds!='')
						{
							$lldd = $ilr3->addChild("LLDDandHealthProblem");
							if($ds=='1')
								$dschild = $lldd->addChild("LLDDCat","4");
							elseif($ds=='2')
								$dschild = $lldd->addChild("LLDDCat","5");
							elseif($ds=='3')
								$dschild = $lldd->addChild("LLDDCat","6");
							elseif($ds=='4')
								$dschild = $lldd->addChild("LLDDCat","93");
							elseif($ds=='5')
								$dschild = $lldd->addChild("LLDDCat","95");
							elseif($ds=='6')
								$dschild = $lldd->addChild("LLDDCat","1");
							elseif($ds=='7')
								$dschild = $lldd->addChild("LLDDCat","9");
							elseif($ds=='8')
								$dschild = $lldd->addChild("LLDDCat","16");
							elseif($ds=='9')
								$dschild = $lldd->addChild("LLDDCat","7");
							elseif($ds=='10')
								$dschild = $lldd->addChild("LLDDCat","15");
							elseif($ds=='90')
								$dschild = $lldd->addChild("LLDDCat","2");
							elseif($ds=='97')
								$dschild = $lldd->addChild("LLDDCat","97");
							elseif($ds=='99')
							{
								$dschild = $lldd->addChild("LLDDCat","99");
								$ds99 = 1;
							}
						}
						if($ld!='')
						{
							$lldd = $ilr3->addChild("LLDDandHealthProblem");
							if($ld=='1')
								$dschild = $lldd->addChild("LLDDCat","10");
							elseif($ld=='2')
								$dschild = $lldd->addChild("LLDDCat","11");
							elseif($ld=='10')
								$dschild = $lldd->addChild("LLDDCat","12");
							elseif($ld=='11')
								$dschild = $lldd->addChild("LLDDCat","13");
							elseif($ld=='19')
								$dschild = $lldd->addChild("LLDDCat","94");
							elseif($ld=='20')
								$dschild = $lldd->addChild("LLDDCat","14");
							elseif($ld=='90')
								$dschild = $lldd->addChild("LLDDCat","3");
							elseif($ld=='97')
								$dschild = $lldd->addChild("LLDDCat","96");
							elseif($ld=='99' && $ds==0)
								$dschild = $lldd->addChild("LLDDCat","99");
						}

						if(($ds!='' && $ld=='') || ($ds=='' && $ld!=''))
							$dschild = $lldd->addChild("PrimaryLLDD","1");
					}

					foreach($ilr3->LearnerFAM as $learnerfam)
					{
						if($learnerfam->LearnFAMType=="HNS")
							$learnerfam->LearnFAMCode = "";
						if($learnerfam->LearnFAMType=="FME")
							$learnerfam->LearnFAMCode = "";
						if($learnerfam->LearnFAMType=="PPE")
							$learnerfam->LearnFAMCode = "";
						if($learnerfam->LearnFAMType=="NLM" && ($learnerfam->LearnFAMCode=="19" || $learnerfam->LearnFAMCode=="20"))
							$learnerfam->LearnFAMCode = "";
						if($learnerfam->LearnFAMType=="MGA" && ($learnerfam->LearnFAMCode=="2" || $learnerfam->LearnFAMCode=="3"))
						{
							$learnerfam->LearnFAMCode = "";
							$lfam = $ilr3->addChild("LearnerFAM");
							$lfamchild = $lfam->addChild("LearnFAMType","EDF");
							$lfamchild = $lfam->addChild("LearnFAMCode","1");
						}
						if($learnerfam->LearnFAMType=="EGA" && ($learnerfam->LearnFAMCode=="2" || $learnerfam->LearnFAMCode=="3"))
						{
							$learnerfam->LearnFAMCode = "";
							$lfam = $ilr3->addChild("LearnerFAM");
							$lfamchild = $lfam->addChild("LearnFAMType","EDF");
							$lfamchild = $lfam->addChild("LearnFAMCode","2");
						}
					}

					$earliest_start_date = new Date("2015-08-01");
					foreach($ilr3->LearningDelivery as $ld)
					{
						if($ld->FundModel=='82')
							$ld->FundModel="";
						if($ld->ProgType=='15' || $ld->ProgType=='16' || $ld->ProgType=='17' || $ld->ProgType=='18')
							$ld->ProgType="";
						$ld->ESFProjDosNumber = '';
						$ld->ESFLocProjNumber = '';
						if($ld->FundModel=='70')
							$ld->EmpOutcome="";
						if($ld->Outcome=='4' || $ld->Outcome=='5')
							$ld->Outcome="8";

						foreach($ld->LearningDeliveryFAM as $ldfam)
						{
							if($ldfam->LearnDelFAMType=='NSA' && ($ldfam->LearnDelFAMCode=='21' || $ldfam->LearnDelFAMCode=='22' || $ldfam->LearnDelFAMCode=='23' || $ldfam->LearnDelFAMCode=='24' || $ldfam->LearnDelFAMCode=='25'  || $ldfam->LearnDelFAMCode=='26' || $ldfam->LearnDelFAMCode=='27' || $ldfam->LearnDelFAMCode=='28' || $ldfam->LearnDelFAMCode=='29' || $ldfam->LearnDelFAMCode=='30'))
								$ldfam->LearnDelFAMCode = "";
						}

						$LearnAimRef = $ld->LearnAimRef;
						if($LearnAimRef!='Z0007834' && $LearnAimRef!='Z0007835'&& $LearnAimRef!='Z0007836' && $LearnAimRef!='Z0007837' && $LearnAimRef!='Z0007838' && $LearnAimRef!='Z0002347')
							unset($ld->LearningDeliveryWorkPlacement);

						if($earliest_start_date->after("".$ld->LearnStartDate))
							$earliest_start_date = new Date("".$ld->LearnStartDate);
					}

					foreach($ilr3->LearningDelivery as $ld)
					{
						$sd = "" . $ld->LearnActEndDate;
						if($sd=='' || $sd=='00000000' || $sd=='dd/mm/yyyy')
						{
							$migrate = 1;
						}
						else
						{
							$act_end_date = new Date($sd);
							if($act_end_date->after('31/07/2014'))
								$migrate = 1;
						}
					}


					$ilr3 = substr($ilr3->asXML(),22);
					$ilr3 = str_replace("'","&amp;",$ilr3);

					//$migrate = 1;
					// Check if the ilr is eligible to be migrated i.e. continuing or finishing in 2013
					if($migrate==1)
					{
						$need = DAO::getSingleValue($link, "select count(*) from ilr where l03 = '$L03' and contract_id = '$contract_id' and tr_id = '$tr_id'");
						// Check if ILR is not already migrated
						if($need==0)
						{
							if($is_active=='')
								$is_active=0;
							DAO::execute($link, "INSERT INTO ilr values('', '$L03', '', '$ilr3', 'W01', 'ER', $tr_id, $is_complete, 0, $is_approved, $is_active, $contract_id);");
							DAO::execute($link, "UPDATE tr SET contract_id = $contract_id where id = $tr_id");
						}
					}

				}
			}
		}
		elseif($contract_year==2014 && $LearnRefNumber!='' && $contract_id != '')
		{
			$contract_id = DAO::getSingleValue($link, "select id from contracts where parent_id = '$contract_from'");
			$q = "SELECT L01,L03, A09, ilr, 'W01', contract_type, tr_id, is_complete, is_valid, is_approved, is_active FROM ilr WHERE contract_id = '$contract_from' AND submission='W13' and L03 = '$LearnRefNumber';";
			if($st = $link->query($q))
			{
				while($row = $st->fetch())
				{
					$migrate = 0;
					$contract_type = $row['contract_type'];
					$L03 = $row['L03'];
					$tr_id = $row['tr_id'];
					$is_complete = $row['is_complete'];
					$is_valid = $row['is_valid'];
					$is_approved = $row['is_approved'];
					$is_active = $row['is_active'];
					$xml = $row['ilr'];
					$ilr2 = str_replace("'","&apos;",$xml);
					$ilr3 = @XML::loadSimpleXML($ilr2);
					$soforig = '';
					$ffiorig = '';
					$alnorig = '';
					$alnplanned = '';
					$f25flag = '';
					foreach($ilr3->Learner as $learner)
					{
						$learner->PrevLearnRefNumber = '';
						$learner->PrevUKPRN = '';
						$learner->Accom = '';
						$learner->ALSCost = '';
						$learner->PlanLearnHours = '';
						$learner->PlanEEPHours = '';
						foreach($learner->LearnerFAM as $learnerfam)
						{
							if($learnerfam->LearnFAMType=="ALS")
								$learnerfam->LearnFAMCode = "";
							if($learnerfam->LearnFAMType=="LSR")
								$learnerfam->LearnFAMCode = "";
						}
					}

					$is_traineeship = false;
					$earliest_start_date = new Date("2014-08-01");
					foreach($ilr3->LearningDelivery as $ld)
					{
						$xpath = $ld->xpath("./LearningDeliveryFAM[LearnDelFAMType='SOF']/LearnDelFAMCode"); $s = (empty($xpath[0]))?'':$xpath[0];
						if($soforig=='' && $s!='undefined' && $s!='')
							$soforig = "". $s;
						$xpath = $ld->xpath("./LearningDeliveryFAM[LearnDelFAMType='FFI']/LearnDelFAMCode"); $f = (empty($xpath[0]))?'':$xpath[0];
						if($soforig=='' && $f!='undefined' && $f!='')
							$ffiorig = "". $f;
						$xpath = $ld->xpath("./LearningDeliveryFAM[LearnDelFAMType='LDM']/LearnDelFAMCode");
						$ldm1 = (empty($xpath[0]))?'':$xpath[0];
						$ldm2 = (empty($xpath[1]))?'':$xpath[1];
						$ldm3 = (empty($xpath[2]))?'':$xpath[2];
						$ldm4 = (empty($xpath[3]))?'':$xpath[3];
						$xpath = $ld->xpath("./LearningDeliveryFAM[LearnDelFAMType='ALN']/LearnDelFAMCode");
						$aln = (empty($xpath[0]))?'':$xpath[0];

						if($ld->AimType=='4' && ($ldm1=='323' || $ldm2=='323' || $ldm3=='323' || $ldm4=='323'))
							$ld->AimType = "3";
						if($ldm1=='323' || $ldm2=='323' || $ldm3=='323' || $ldm4=='323')
						{
							$ld->ProgType = "24";
							$is_traineeship = true;
							$fund_model = $ld->FundModel;
							$xpath = $ld->xpath("./LearningDeliveryFAM[LearnDelFAMType='SOF']/LearnDelFAMCode");
							$sof = (empty($xpath[0]))?'':$xpath[0];
							$xpath = $ld->xpath("./LearningDeliveryFAM[LearnDelFAMType='FFI']/LearnDelFAMCode");
							$ffi = (empty($xpath[0]))?'':$xpath[0];
							$ldm = 323;
						}
						if($ld->AimType=='1')
						{
							$ld->PriorLearnFundAdj = '';
							$ld->OtherFundAdj = '';
						}
						if($earliest_start_date->after("".$ld->LearnStartDate))
							$earliest_start_date = new Date("".$ld->LearnStartDate);
					}

					foreach($ilr3->LearningDelivery as $ld)
					{
						$sd = "" . $ld->LearnActEndDate;
						if($sd=='' || $sd=='00000000' || $sd=='dd/mm/yyyy')
						{
							$migrate = 1;
						}
						else
						{
							$act_end_date = new Date($sd);
							if($act_end_date->after('31/07/2014'))
								$migrate = 1;
						}
					}
					$ilr3 = substr($ilr3->asXML(),22);
					$ilr3 = str_replace("'","&amp;",$ilr3);

					// Add programme aim for Traineeship
					if($is_traineeship)
					{
						$programme_aim = "<LearningDelivery>";
						$programme_aim .= "<LearnAimRef>ZPROG001</LearnAimRef>";
						$programme_aim .= "<AimType>1</AimType>";
						$programme_aim .= "<LearnStartDate>". $earliest_start_date ."</LearnStartDate>";
						$programme_aim .= "<LearnPlanEndDate></LearnPlanEndDate>";
						$programme_aim .= "<FundModel>".$fund_model."</FundModel>";
						$programme_aim .= "<ProgType>24</ProgType>";
						$programme_aim .= "<CompStatus>1</CompStatus>";
						$programme_aim .= "<LearnActEndDate></LearnActEndDate>";
						$programme_aim .= "<LearningDeliveryFAM><LearnDelFAMType>SOF</LearnDelFAMType><LearnDelFAMCode>".$sof."</LearnDelFAMCode></LearningDeliveryFAM>";
						$programme_aim .= "<LearningDeliveryFAM><LearnDelFAMType>FFI</LearnDelFAMType><LearnDelFAMCode>".$ffi."</LearnDelFAMCode></LearningDeliveryFAM>";
						$programme_aim .= "<LearningDeliveryFAM><LearnDelFAMType>LDM</LearnDelFAMType><LearnDelFAMCode>323</LearnDelFAMCode></LearningDeliveryFAM>";
						$programme_aim .= "</LearningDelivery></Learner>";
						$ilr3 = str_replace("</Learner>",$programme_aim, $ilr3);
					}

					//$migrate = 1;
					// Check if the ilr is eligible to be migrated i.e. continuing or finishing in 2013
					if($migrate==1)
					{
						$need = DAO::getSingleValue($link, "select count(*) from ilr where l03 = '$L03' and contract_id = '$contract_id' and tr_id = '$tr_id'");
						// Check if ILR is not already migrated
						if($need==0)
						{
							if($is_active=='')
								$is_active=0;
							DAO::execute($link, "INSERT INTO ilr values('', '$L03', '', '$ilr3', 'W01', 'ER', $tr_id, $is_complete, 0, $is_approved, $is_active, $contract_id);");
							DAO::execute($link, "UPDATE tr SET contract_id = $contract_id where id = $tr_id");
						}
					}
				}
			}
		}
		//

		if($contract_year==2015)
		{
			$pageDom = XML::loadXmlDom($xml);
			$e = $pageDom->getElementsByTagName('contract');
			error_reporting(0);
			foreach($e as $node)
			{
				$contract_to_import = $node->nodeValue;
				$curr_date = new Date(date('Y/m/d'));
				if($curr_date->after('2015-08-06'))
					$curr_submission = "W13";
				else
					$curr_submission = "W12";

				$q = "SELECT L01,L03, A09, ilr, 'W01', contract_type, tr_id, is_complete, is_valid, is_approved, is_active, $contract_id FROM ilr WHERE contract_id = '$contract_to_import' AND submission='$curr_submission' and tr_id in (select id from tr where closure_date is null)";
				if($st = $link->query($q))
				{
					while($row = $st->fetch())
					{
						$migrate = 0;
						$L03 = $row['L03'];
						$tr_id = $row['tr_id'];
						$is_complete = $row['is_complete'];
						$is_approved = $row['is_approved'];
						$is_active = $row['is_active'];
						$xml = $row['ilr'];
						$ilr2 = str_replace("'","&apos;",$xml);
						$ilr3 = @XML::loadSimpleXML($ilr2);
						$soforig = '';

						$ilr3->PrevLearnRefNumber = '';
						$ilr3->PrevUKPRN = '';
						$ilr3->Accom = '';
						$ilr3->ALSCost = '';
						$ilr3->PlanLearnHours = '';
						$ilr3->PlanEEPHours = '';
						$ilr3->Dest = '';
						$ilr3->addChild("MathGrade","");
						$ilr3->addChild("EngGrade","");

						$ds = '';
						$ld = '';
						foreach($ilr3->LLDDandHealthProblem as $lldd)
						{
							if($lldd->LLDDType=="DS" && ("".$lldd->LLDDCode!=""))
								$ds = "".$lldd->LLDDCode;
							elseif($lldd->LLDDType=="LD" && ("".$lldd->LLDDCode!=""))
								$ld = "".$lldd->LLDDCode;
						}
						unset($ilr3->LLDDandHealthProblem);
						$ds99 = 0;
						if($ds!='' || $ld!='')
						{
							if($ds!='')
							{
								$lldd = $ilr3->addChild("LLDDandHealthProblem");
								if($ds=='1')
									$dschild = $lldd->addChild("LLDDCat","4");
								elseif($ds=='2')
									$dschild = $lldd->addChild("LLDDCat","5");
								elseif($ds=='3')
									$dschild = $lldd->addChild("LLDDCat","6");
								elseif($ds=='4')
									$dschild = $lldd->addChild("LLDDCat","93");
								elseif($ds=='5')
									$dschild = $lldd->addChild("LLDDCat","95");
								elseif($ds=='6')
									$dschild = $lldd->addChild("LLDDCat","1");
								elseif($ds=='7')
									$dschild = $lldd->addChild("LLDDCat","9");
								elseif($ds=='8')
									$dschild = $lldd->addChild("LLDDCat","16");
								elseif($ds=='9')
									$dschild = $lldd->addChild("LLDDCat","7");
								elseif($ds=='10')
									$dschild = $lldd->addChild("LLDDCat","15");
								elseif($ds=='90')
									$dschild = $lldd->addChild("LLDDCat","2");
								elseif($ds=='97')
									$dschild = $lldd->addChild("LLDDCat","97");
								elseif($ds=='99')
								{
									$dschild = $lldd->addChild("LLDDCat","99");
									$ds99 = 1;
								}
							}
							if($ld!='')
							{
								$lldd = $ilr3->addChild("LLDDandHealthProblem");
								if($ld=='1')
									$dschild = $lldd->addChild("LLDDCat","10");
								elseif($ld=='2')
									$dschild = $lldd->addChild("LLDDCat","11");
								elseif($ld=='10')
									$dschild = $lldd->addChild("LLDDCat","12");
								elseif($ld=='11')
									$dschild = $lldd->addChild("LLDDCat","13");
								elseif($ld=='19')
									$dschild = $lldd->addChild("LLDDCat","94");
								elseif($ld=='20')
									$dschild = $lldd->addChild("LLDDCat","14");
								elseif($ld=='90')
									$dschild = $lldd->addChild("LLDDCat","3");
								elseif($ld=='97')
									$dschild = $lldd->addChild("LLDDCat","96");
								elseif($ld=='99' && $ds==0)
									$dschild = $lldd->addChild("LLDDCat","99");
							}

							if(($ds!='' && $ld=='') || ($ds=='' && $ld!=''))
								$dschild = $lldd->addChild("PrimaryLLDD","1");
						}

						foreach($ilr3->LearnerFAM as $learnerfam)
						{
							if($learnerfam->LearnFAMType=="HNS")
								$learnerfam->LearnFAMCode = "";
							if($learnerfam->LearnFAMType=="FME")
								$learnerfam->LearnFAMCode = "";
							if($learnerfam->LearnFAMType=="PPE")
								$learnerfam->LearnFAMCode = "";
							if($learnerfam->LearnFAMType=="NLM" && ($learnerfam->LearnFAMCode=="19" || $learnerfam->LearnFAMCode=="20"))
								$learnerfam->LearnFAMCode = "";
							if($learnerfam->LearnFAMType=="MGA" && ($learnerfam->LearnFAMCode=="2" || $learnerfam->LearnFAMCode=="3"))
							{
								$learnerfam->LearnFAMCode = "";
								$lfam = $ilr3->addChild("LearnerFAM");
								$lfamchild = $lfam->addChild("LearnFAMType","EDF");
								$lfamchild = $lfam->addChild("LearnFAMCode","1");
							}
							if($learnerfam->LearnFAMType=="EGA" && ($learnerfam->LearnFAMCode=="2" || $learnerfam->LearnFAMCode=="3"))
							{
								$learnerfam->LearnFAMCode = "";
								$lfam = $ilr3->addChild("LearnerFAM");
								$lfamchild = $lfam->addChild("LearnFAMType","EDF");
								$lfamchild = $lfam->addChild("LearnFAMCode","2");
							}
						}

						$earliest_start_date = new Date("2015-08-01");
						foreach($ilr3->LearningDelivery as $ld)
						{
							if($ld->FundModel=='82')
								$ld->FundModel="";
							if($ld->ProgType=='15' || $ld->ProgType=='16' || $ld->ProgType=='17' || $ld->ProgType=='18')
								$ld->ProgType="";
							$ld->ESFProjDosNumber = '';
							$ld->ESFLocProjNumber = '';
							if($ld->FundModel=='70')
								$ld->EmpOutcome="";
							if($ld->Outcome=='4' || $ld->Outcome=='5')
								$ld->Outcome="8";

							foreach($ld->LearningDeliveryFAM as $ldfam)
							{
								if($ldfam->LearnDelFAMType=='NSA' && ($ldfam->LearnDelFAMCode=='21' || $ldfam->LearnDelFAMCode=='22' || $ldfam->LearnDelFAMCode=='23' || $ldfam->LearnDelFAMCode=='24' || $ldfam->LearnDelFAMCode=='25'  || $ldfam->LearnDelFAMCode=='26' || $ldfam->LearnDelFAMCode=='27' || $ldfam->LearnDelFAMCode=='28' || $ldfam->LearnDelFAMCode=='29' || $ldfam->LearnDelFAMCode=='30'))
									$ldfam->LearnDelFAMCode = "";
							}

							$LearnAimRef = $ld->LearnAimRef;
							if($LearnAimRef!='Z0007834' && $LearnAimRef!='Z0007835'&& $LearnAimRef!='Z0007836' && $LearnAimRef!='Z0007837' && $LearnAimRef!='Z0007838' && $LearnAimRef!='Z0002347')
								unset($ld->LearningDeliveryWorkPlacement);

							if($earliest_start_date->after("".$ld->LearnStartDate))
								$earliest_start_date = new Date("".$ld->LearnStartDate);
						}

						foreach($ilr3->LearningDelivery as $ld)
						{
							$sd = "" . $ld->LearnActEndDate;
							if($sd=='' || $sd=='00000000' || $sd=='dd/mm/yyyy')
							{
								$migrate = 1;
							}
							else
							{
								$act_end_date = new Date($sd);
								if($act_end_date->after('31/07/2014'))
									$migrate = 1;
							}
						}


						$ilr3 = substr($ilr3->asXML(),22);
						$ilr3 = str_replace("'","&amp;",$ilr3);

						//$migrate = 1;
						// Check if the ilr is eligible to be migrated i.e. continuing or finishing in 2013
						if($migrate==1)
						{
							$need = DAO::getSingleValue($link, "select count(*) from ilr where l03 = '$L03' and contract_id = '$contract_id' and tr_id = '$tr_id'");
							// Check if ILR is not already migrated
							if($need==0)
							{
								if($is_active=='')
									$is_active=0;
								DAO::execute($link, "INSERT INTO ilr values('', '$L03', '', '$ilr3', 'W01', 'ER', $tr_id, $is_complete, 0, $is_approved, $is_active, $contract_id);");
								DAO::execute($link, "UPDATE tr SET contract_id = $contract_id where id = $tr_id");
							}
						}
					}
				}
			}
			http_redirect($_SESSION['bc']->getPrevious());
		}
		elseif($contract_year==2014)
		{
			$L25 = DAO::getSingleValue($link, "select L25 from contracts where id = $contract_id");
			$contracts = Array();
			$pageDom = XML::loadXmlDom($xml);
			$e = $pageDom->getElementsByTagName('contract');
			$prev = '';
			$count = 0;
			error_reporting(0);
			foreach($e as $node)
			{
				$contract_to_import = $node->nodeValue;
				$q = "SELECT L01,L03, A09, ilr, 'W01', contract_type, tr_id, is_complete, is_valid, is_approved, is_active, $contract_id FROM ilr WHERE contract_id = '$contract_to_import' AND submission='W13'";
				if($st = $link->query($q))
				{
					while($row = $st->fetch())
					{
						$migrate = 0;
						$contract_type = $row['contract_type'];
						$L03 = $row['L03'];
						$tr_id = $row['tr_id'];
						$is_complete = $row['is_complete'];
						$is_valid = $row['is_valid'];
						$is_approved = $row['is_approved'];
						$is_active = $row['is_active'];
						$xml = $row['ilr'];
						$ilr2 = str_replace("'","&apos;",$xml);
						$ilr3 = @XML::loadSimpleXML($ilr2);
						$soforig = '';
						$ffiorig = '';
						$alnorig = '';
						$alnplanned = '';
						$f25flag = '';
						foreach($ilr3->Learner as $learner)
						{
							$learner->PrevLearnRefNumber = '';
							$learner->PrevUKPRN = '';
							$learner->Accom = '';
							$learner->ALSCost = '';
							$learner->PlanLearnHours = '';
							$learner->PlanEEPHours = '';
							foreach($learner->LearnerFAM as $learnerfam)
							{
								if($learnerfam->LearnFAMType=="ALS")
									$learnerfam->LearnFAMCode = "";
								if($learnerfam->LearnFAMType=="LSR")
									$learnerfam->LearnFAMCode = "";
							}
						}

						$is_traineeship = false;
						$earliest_start_date = new Date("2014-08-01");
						foreach($ilr3->LearningDelivery as $ld)
						{
							$xpath = $ld->xpath("./LearningDeliveryFAM[LearnDelFAMType='SOF']/LearnDelFAMCode"); $s = (empty($xpath[0]))?'':$xpath[0];
							if($soforig=='' && $s!='undefined' && $s!='')
								$soforig = "". $s;
							$xpath = $ld->xpath("./LearningDeliveryFAM[LearnDelFAMType='FFI']/LearnDelFAMCode"); $f = (empty($xpath[0]))?'':$xpath[0];
							if($soforig=='' && $f!='undefined' && $f!='')
								$ffiorig = "". $f;
							$xpath = $ld->xpath("./LearningDeliveryFAM[LearnDelFAMType='LDM']/LearnDelFAMCode");
							$ldm1 = (empty($xpath[0]))?'':$xpath[0];
							$ldm2 = (empty($xpath[1]))?'':$xpath[1];
							$ldm3 = (empty($xpath[2]))?'':$xpath[2];
							$ldm4 = (empty($xpath[3]))?'':$xpath[3];
							$xpath = $ld->xpath("./LearningDeliveryFAM[LearnDelFAMType='ALN']/LearnDelFAMCode");
							$aln = (empty($xpath[0]))?'':$xpath[0];

							if($ld->AimType=='4' && ($ldm1=='323' || $ldm2=='323' || $ldm3=='323' || $ldm4=='323'))
								$ld->AimType = "3";
							if($ldm1=='323' || $ldm2=='323' || $ldm3=='323' || $ldm4=='323')
							{
								$ld->ProgType = "24";
								$is_traineeship = true;
								$fund_model = $ld->FundModel;
								$xpath = $ld->xpath("./LearningDeliveryFAM[LearnDelFAMType='SOF']/LearnDelFAMCode");
								$sof = (empty($xpath[0]))?'':$xpath[0];
								$xpath = $ld->xpath("./LearningDeliveryFAM[LearnDelFAMType='FFI']/LearnDelFAMCode");
								$ffi = (empty($xpath[0]))?'':$xpath[0];
								$ldm = 323;
							}
							if($ld->AimType=='1')
							{
								$ld->PriorLearnFundAdj = '';
								$ld->OtherFundAdj = '';
							}
							if($earliest_start_date->after("".$ld->LearnStartDate))
								$earliest_start_date = new Date("".$ld->LearnStartDate);
						}

						foreach($ilr3->LearningDelivery as $ld)
						{
							$sd = "" . $ld->LearnActEndDate;
							if($sd=='' || $sd=='00000000' || $sd=='dd/mm/yyyy')
							{
								$migrate = 1;
							}
							else
							{
								$act_end_date = new Date($sd);
								if($act_end_date->after('31/07/2014'))
									$migrate = 1;
							}
						}


						$ilr3 = substr($ilr3->asXML(),22);
						$ilr3 = str_replace("'","&amp;",$ilr3);

						// Add programme aim for Traineeship
						if($is_traineeship)
						{
							$programme_aim = "<LearningDelivery>";
							$programme_aim .= "<LearnAimRef>ZPROG001</LearnAimRef>";
							$programme_aim .= "<AimType>1</AimType>";
							$programme_aim .= "<LearnStartDate>". $earliest_start_date ."</LearnStartDate>";
							$programme_aim .= "<LearnPlanEndDate></LearnPlanEndDate>";
							$programme_aim .= "<FundModel>".$fund_model."</FundModel>";
							$programme_aim .= "<ProgType>24</ProgType>";
							$programme_aim .= "<CompStatus>1</CompStatus>";
							$programme_aim .= "<LearnActEndDate></LearnActEndDate>";
							$programme_aim .= "<LearningDeliveryFAM><LearnDelFAMType>SOF</LearnDelFAMType><LearnDelFAMCode>".$sof."</LearnDelFAMCode></LearningDeliveryFAM>";
							$programme_aim .= "<LearningDeliveryFAM><LearnDelFAMType>FFI</LearnDelFAMType><LearnDelFAMCode>".$ffi."</LearnDelFAMCode></LearningDeliveryFAM>";
							$programme_aim .= "<LearningDeliveryFAM><LearnDelFAMType>LDM</LearnDelFAMType><LearnDelFAMCode>323</LearnDelFAMCode></LearningDeliveryFAM>";
							$programme_aim .= "</LearningDelivery></Learner>";
							$ilr3 = str_replace("</Learner>",$programme_aim, $ilr3);
						}

						//$migrate = 1;
						// Check if the ilr is eligible to be migrated i.e. continuing or finishing in 2013
						if($migrate==1)
						{
							$need = DAO::getSingleValue($link, "select count(*) from ilr where l03 = '$L03' and contract_id = '$contract_id' and tr_id = '$tr_id'");
							// Check if ILR is not already migrated
							if($need==0)
							{
								if($is_active=='')
									$is_active=0;
								DAO::execute($link, "INSERT INTO ilr values('', '$L03', '', '$ilr3', 'W01', 'ER', $tr_id, $is_complete, 0, $is_approved, $is_active, $contract_id);");
								DAO::execute($link, "UPDATE tr SET contract_id = $contract_id where id = $tr_id");
							}
						}
					}
				}
			}
			http_redirect($_SESSION['bc']->getPrevious());
		}
		elseif($contract_year==2013)
		{
			$L25 = DAO::getSingleValue($link, "select L25 from contracts where id = $contract_id");
			$contracts = Array();
			$pageDom = XML::loadXmlDom($xml);
			$e = $pageDom->getElementsByTagName('contract');
			$prev = '';
			$count = 0;
			error_reporting(0);
			foreach($e as $node)
			{
				$contract_to_import = $node->nodeValue;
				if($st = $link->query("SELECT L01,L03, A09, ilr, 'W01', contract_type, tr_id, is_complete, is_valid, is_approved, is_active, $contract_id FROM ilr WHERE contract_id = '$contract_to_import' AND submission='W13'"))
				{
					while($row = $st->fetch())
					{
						$migrate = 0;
						$contract_type = $row['contract_type'];
						$L03 = $row['L03'];
						$tr_id = $row['tr_id'];
						$is_complete = $row['is_complete'];
						$is_valid = $row['is_valid'];
						$is_approved = $row['is_approved'];
						$is_active = $row['is_active'];
						$xml = $row['ilr'];
						$ilr2 = str_replace("'","&apos;",$xml);
						$ilr3 = @XML::loadSimpleXML($ilr2);
						$soforig = '';
						$ffiorig = '';
						$alnorig = '';
						$alnplanned = '';
						$f25flag = '';
						foreach($ilr3->LearningDelivery as $ld)
						{
							$xpath = $ld->xpath("./LearningDeliveryFAM[LearnDelFAMType='SOF']/LearnDelFAMCode"); $s = (empty($xpath[0]))?'':$xpath[0];
							if($soforig=='' && $s!='undefined' && $s!='')
								$soforig = "". $s;
							$xpath = $ld->xpath("./LearningDeliveryFAM[LearnDelFAMType='FFI']/LearnDelFAMCode"); $f = (empty($xpath[0]))?'':$xpath[0];
							if($soforig=='' && $f!='undefined' && $f!='')
								$ffiorig = "". $f;
							$xpath = $ld->xpath("./LearningDeliveryFAM[LearnDelFAMType='LDM']/LearnDelFAMCode");
							$ldm1 = (empty($xpath[0]))?'':$xpath[0];
							$ldm2 = (empty($xpath[1]))?'':$xpath[1];
							$ldm3 = (empty($xpath[2]))?'':$xpath[2];
							$ldm4 = (empty($xpath[3]))?'':$xpath[3];
							$xpath = $ld->xpath("./LearningDeliveryFAM[LearnDelFAMType='ALN']/LearnDelFAMCode");
							$aln = (empty($xpath[0]))?'':$xpath[0];
							if($aln=='1' && ($ld->LearnActEndDate=='' || $ld->LearnActEndDate=='dd/mm/yyyy' || $ld->LearnActEndDate=='00000000'))
							{
								$alnorig = 1;
								$alnplanned = "" . $ld->LearnPlanEndDate;
							}
						}

						foreach($ilr3->LearningDelivery as $ld)
						{
							// Funding Mapping
							if($ld->FundModel=='21')
								$ld->FundModel = "25";
							elseif($ld->FundModel=='22' && $soforig=='105')
								$ld->FundModel = "25";
							elseif($ld->FundModel=='45' && ($soforig=='105' || $soforig=='' || $soforig=='undefined'))
								$ld->FundModel = "35";

							if($ld->FundModel=='25' || $ld->FundModel=='82')
								$f25flag=1;
						}


						$lda='';
						foreach($ilr3->LearnerFAM as $lf)
						{
							if($lf->LearnerFAMType=='ALS' && $lf->LearnerFAMCode=='3')
								$lf->LearnerFAMCode=='';
							if($lf->LearnerFAMType=='ALS' && $lf->LearnerFAMCode=='2')
								$lf->LearnerFAMCode=='1';
							if($lf->LearnerFAMType=='LDA')
								$lda="".$lf->LearnerFAMType;
						}

						foreach($ilr3->Learner as $learner)
						{
							if($lf->Accom=='1' && $f25flag==1)
								$lf->Accom=='5';
						}

						foreach($ilr3->LearningDelivery as $ld)
						{
							$sd = "" . $ld->LearnActEndDate;
							if($sd=='' || $sd=='00000000' || $sd=='dd/mm/yyyy')
							{
								$migrate = 1;
							}
							else
							{
								$act_end_date = new Date($sd);
								if($act_end_date->after('31/07/2013'))
									$migrate = 1;
							}

							// If achieved in 2013-14
							$achdate = "" . $ld->AchDate;
							if($achdate!='' && $achdate!='00000000' && $achdate!='dd/mm/yyyy')
							{
								$achdate = new Date($achdate);
								if($achdate->after('31/07/2013'))
									$migrate = 1;
							}

							//
							$xpath = $ld->xpath("./LearningDeliveryFAM[LearnDelFAMType='SOF']/LearnDelFAMCode"); $sof = (empty($xpath[0]))?'':$xpath[0];
							if($sof=='undefined' || $sof=='')
							{
								$one = $ld->addChild("LearningDeliveryFAM");
								$one->addChild("LearnDelFAMType","SOF");
								$one->addChild("LearnDelFAMCode",$soforig);
							}
							if($ld->AimType=='1')
							{
								$one = $ld->addChild("LearningDeliveryFAM");
								$one->addChild("LearnDelFAMType","FFI");
								$one->addChild("LearnDelFAMCode",$ffiorig);
								if($alnorig=='1')
								{
									$one = $ld->addChild("LearningDeliveryFAM");
									$one->addChild("LearnDelFAMType","LSF");
									$one->addChild("LearnDelFAMCode","1");
									$one->addChild("LearnDelFAMDateFrom","01/08/2013");
									$one->addChild("LearnDelFAMDateTo",$alnplanned);
								}
							}
							if($ld->AimType=='4')
							{
								if($alnorig=='1')
								{
									$one = $ld->addChild("LearningDeliveryFAM");
									$one->addChild("LearnDelFAMType","LSF");
									$one->addChild("LearnDelFAMCode","1");
									$one->addChild("LearnDelFAMDateFrom","01/08/2013");
									$one->addChild("LearnDelFAMDateTo",$alnplanned);
								}
							}
							if($ld->AimType=='2' || $ld->AimType=='3' || $ld->AimType=='4')
							{
								if( ("".$ld->PropFundRemain)!='100')
									$one = $ld->addChild("PriorLearnFundAdj", "".$ld->PropFundRemain);
							}

							if($ld->FundModel=='35' && $ldm1!='125' && $ldm2!='125' && $ldm3!='125' && $ldm4!='125')
							{
								$one = $ld->addChild("LearningDeliveryFAM");
								$one->addChild("LearnDelFAMType","WPL");
								$one->addChild("LearnDelFAMCode","1");
							}

						}

						$ilr3 = substr($ilr3->asXML(),22);
						$ilr3 = str_replace("'","&amp;",$ilr3);
						// Conversion Starts
						$ilr3 = str_replace("<FworkCode>51</FworkCode>","<FworkCode></FworkCode>",$ilr3);
						$ilr3 = str_replace("<FworkCode>52</FworkCode>","<FworkCode></FworkCode>",$ilr3);
						$ilr3 = str_replace("<FworkCode>53</FworkCode>","<FworkCode></FworkCode>",$ilr3);
						$ilr3 = str_replace("<FworkCode>54</FworkCode>","<FworkCode></FworkCode>",$ilr3);
						$ilr3 = str_replace("<FworkCode>55</FworkCode>","<FworkCode></FworkCode>",$ilr3);
						//$ilr3 = str_replace("<FundModel>21</FundModel>","<FundModel>25</FundModel>",$ilr3);
						// Conversion Finished
						//$migrate = 1;
						//$migrate = 1;

						// Check if the ilr is eligible to be migrated i.e. continuing or finishing in 2013
						if($migrate==1)
						{
							$need = DAO::getSingleValue($link, "select count(*) from ilr where l03 = '$L03' and contract_id = '$contract_id' and tr_id = '$tr_id'");
							// Check if ILR is not already migrated
							if($need==0)
							{
								if($is_active=='')
									$is_active=0;
								DAO::execute($link, "INSERT INTO ilr values('', '$L03', '', '$ilr3', 'W01', 'ER', $tr_id, $is_complete, 0, $is_approved, $is_active, $contract_id);");
								DAO::execute($link, "UPDATE tr SET contract_id = $contract_id where id = $tr_id");
							}
						}
					}
				}
			}
			http_redirect($_SESSION['bc']->getPrevious());
		}
		elseif($contract_year==2012)
		{
			$L25 = DAO::getSingleValue($link, "select L25 from contracts where id = $contract_id");
			$contracts = Array();
			//$pageDom = new DomDocument();
			//$pageDom->loadXML($xml);
			$pageDom = XML::loadXmlDom($xml);
			$e = $pageDom->getElementsByTagName('contract');
			$prev = '';
			$count = 0;
			error_reporting(0);
			foreach($e as $node)
			{

				$contract_to_import = $node->nodeValue;
				$ukprn = DAO::getSingleValue($link, "select ukprn from contracts where contracts.id = '$contract_to_import'");
				if($st = $link->query("SELECT L01,L03, A09, ilr, 'W01', contract_type, tr_id, is_complete, is_valid, is_approved, is_active, $contract_id FROM ilr WHERE contract_id = '$contract_to_import' AND submission='W13'"))
				{
					while($row = $st->fetch())
					{


						$migrate = 0;
						$deleted = 0;

						$L01 = $row['L01'];
						$L03 = $row['L03'];
						$A09 = $row['A09'];
						$ilr = $row['ilr'];
						$contract_type = $row['contract_type'];
						$tr_id = $row['tr_id'];
						$is_complete = $row['is_complete'];
						$is_valid = $row['is_valid'];
						$is_approved = $row['is_approved'];
						$is_active = $row['is_active'];
						$ilr2 = @XML::loadSimpleXML($ilr);

						foreach($ilr2->learner as $learner)
						{
							$learner->L25 = $L25;
							$xml = "<Learner>";
							$xml .= "\n<LearnRefNumber>" . $learner->L03 . "</LearnRefNumber>";
							$xml .= "\n<ULN>" . $learner->L45 . "</ULN>";
							$xml .= "\n<FamilyName>" . $learner->L09 . "</FamilyName>";
							$xml .= "\n<GivenNames>" . $learner->L10 . "</GivenNames>";
							$xml .= "\n<DateOfBirth>" . Date::toMySQL($learner->L11) . "</DateOfBirth>";
							$xml .= "\n<Ethnicity>" . $learner->L12 . "</Ethnicity>";
							$xml .= "\n<Sex>" . $learner->L13 . "</Sex>";
							$xml .= "\n<LLDDHealthProb>" . $learner->L14 . "</LLDDHealthProb>";
							$xml .= "\n<NINumber>" . $learner->L26 . "</NINumber>";
							$xml .= "\n<Domicile>" . $learner->L24 . "</Domicile>";
							$xml .= "\n<PriorAttain>" . $learner->L35 . "</PriorAttain>";
							if($learner->L34a=='49')
								$xml .= "\n<Accom>1</Accom>";
							elseif($learner->L34a=='50')
								$xml .= "\n<Accom>2</Accom>";
							elseif($learner->L34a=='51')
								$xml .= "\n<Accom>3</Accom>";
							elseif($learner->L34a=='52')
								$xml .= "\n<Accom>4</Accom>";
							$xml .= "\n<Dest>" . $learner->L39 . "</Dest>";
							$xml .= "\n<LearnerContact><LocType>2</LocType><ContType>1</ContType><PostCode>" . $learner->L17 . "</PostCode></LearnerContact>";
							$xml .= "\n<LearnerContact><LocType>1</LocType><ContType>2</ContType><PostAdd><AddLine1>" . $learner->L18 . "</AddLine1>";
							$xml .= "<AddLine2>" . $learner->L19 . "</AddLine2>";
							$xml .= "<AddLine3>" . $learner->L20 . "</AddLine3>";
							$xml .= "<AddLine4>" . $learner->L21 . "</AddLine4></PostAdd></LearnerContact>";
							$xml .= "\n<LearnerContact><LocType>2</LocType><ContType>2</ContType><PostCode>" . $learner->L22 . "</PostCode></LearnerContact>";
							$xml .= "\n<LearnerContact><LocType>3</LocType><ContType>2</ContType><TelNumber>" . $learner->L23 . "</TelNumber></LearnerContact>";
							if($learner->L51!='')
								$xml .= "\n<LearnerContact><LocType>4</LocType><ContType>2</ContType><Email>" . $learner->L51 . "</Email></LearnerContact>";
							if($learner->L27=='1')
								$xml .= "\n<ContactPreference><ContPrefType>RUI</ContPrefType><ContPrefCode>1</ContPrefCode></ContactPreference><ContactPreference><ContPrefType>RUI</ContPrefType><ContPrefCode>2</ContPrefCode></ContactPreference>";
							elseif($learner->L27=='2')
								$xml .= "\n<ContactPreference><ContPrefType>RUI</ContPrefType><ContPrefCode>3</ContPrefCode></ContactPreference>";
							elseif($learner->L27=='3')
								$xml .= "\n<ContactPreference><ContPrefType>RUI</ContPrefType><ContPrefCode>1</ContPrefCode></ContactPreference>";
							elseif($learner->L27=='4')
								$xml .= "\n<ContactPreference><ContPrefType>RUI</ContPrefType><ContPrefCode>2</ContPrefCode></ContactPreference>";
							$xml .= "\n<ContactPreference><ContPrefType>PMC</ContPrefType><ContPrefCode>" . $learner->L52 . "</ContPrefCode></ContactPreference>";
							if($learner->L15!='98')
								$xml .= "\n<LLDDandHealthProblem><LLDDType>DS</LLDDType><LLDDCode>" . $learner->L15 . "</LLDDCode></LLDDandHealthProblem>";
							if($learner->L16!='98')
								$xml .= "\n<LLDDandHealthProblem><LLDDType>LD</LLDDType><LLDDCode>" . $learner->L16 . "</LLDDCode></LLDDandHealthProblem>";
							if($learner->L28!='')
								$xml .= "\n<LearnerFAM><LearnFAMType>EFE</LearnFAMType><LearnFAMCode>" . $learner->L28 . "</LearnFAMCode></LearnerFAM>";

							if($learner->L29=='43')
							{
								$xml .= "\n<LearnerFAM><LearnFAMType>LDA</LearnFAMType><LearnFAMCode>1</LearnFAMCode></LearnerFAM>";
							}
							elseif($learner->L29=='44')
							{
								$xml .= "\n<LearnerFAM><LearnFAMType>LDA</LearnFAMType><LearnFAMCode>1</LearnFAMCode></LearnerFAM>";
								$xml .= "\n<LearnerFAM><LearnFAMType>ALS</LearnFAMType><LearnFAMCode>2</LearnFAMCode></LearnerFAM>";
							}
							elseif($learner->L29=='45')
							{
								$xml .= "\n<LearnerFAM><LearnFAMType>ALS</LearnFAMType><LearnFAMCode>2</LearnFAMCode></LearnerFAM>";
							}
							elseif($learner->L29=='46')
							{
								$xml .= "\n<LearnerFAM><LearnFAMType>LDA</LearnFAMType><LearnFAMCode>1</LearnFAMCode></LearnerFAM>";
								$xml .= "\n<LearnerFAM><LearnFAMType>ALS</LearnFAMType><LearnFAMCode>3</LearnFAMCode></LearnerFAM>";
							}
							elseif($learner->L29=='47')
							{
								$xml .= "\n<LearnerFAM><LearnFAMType>ALS</LearnFAMType><LearnFAMCode>3</LearnFAMCode></LearnerFAM>";
							}
							elseif($learner->L29=='71')
							{
								$xml .= "\n<LearnerFAM><LearnFAMType>DLA</LearnFAMType><LearnFAMCode>1</LearnFAMCode></LearnerFAM>";
							}
							if($learner->L32!='')
								$xml .= "\n<LearnerFAM><LearnFAMType>DUE</LearnFAMType><LearnFAMCode>" . $learner->L32 . "</LearnFAMCode></LearnerFAM>";

							if($learner->L34a=='32' || $learner->L34a=='36' || $learner->L34a=='37' || $learner->L34a=='41' || $learner->L34a=='54')
								$xml .= "\n<LearnerFAM><LearnFAMType>LSR</LearnFAMType><LearnFAMCode>" . $learner->L34a . "</LearnFAMCode></LearnerFAM>";

							if($learner->L34b=='32' || $learner->L34b=='36' || $learner->L34b=='37' || $learner->L34b=='41' || $learner->L34b=='54')
								$xml .= "\n<LearnerFAM><LearnFAMType>LSR</LearnFAMType><LearnFAMCode>" . $learner->L34b . "</LearnFAMCode></LearnerFAM>";

							if($learner->L34c=='32' || $learner->L34c=='36' || $learner->L34c=='37' || $learner->L34c=='41' || $learner->L34c=='54')
								$xml .= "\n<LearnerFAM><LearnFAMType>LSR</LearnFAMType><LearnFAMCode>" . $learner->L34c . "</LearnFAMCode></LearnerFAM>";

							if($learner->L34d=='32' || $learner->L34d=='36' || $learner->L34d=='37' || $learner->L34d=='41' || $learner->L34d=='54')
								$xml .= "\n<LearnerFAM><LearnFAMType>LSR</LearnFAMType><LearnFAMCode>" . $learner->L34d . "</LearnFAMCode></LearnerFAM>";

							if($learner->L49a!='' && $learner->L49a!='99' && $learner->L49a!='6')
								$xml .= "\n<LearnerFAM><LearnFAMType>DSF</LearnFAMType><LearnFAMCode>" . $learner->L49a . "</LearnFAMCode></LearnerFAM>";

							if($learner->L49b!='' && $learner->L49b!='99' && $learner->L49b!='6')
								$xml .= "\n<LearnerFAM><LearnFAMType>DSF</LearnFAMType><LearnFAMCode>" . $learner->L49b . "</LearnFAMCode></LearnerFAM>";

							if($learner->L49c!='' && $learner->L49c!='99' && $learner->L49c!='6')
								$xml .= "\n<LearnerFAM><LearnFAMType>DSF</LearnFAMType><LearnFAMCode>" . $learner->L49c . "</LearnFAMCode></LearnerFAM>";

							if($learner->L49d!='' && $learner->L49d!='99' && $learner->L49d!='6')
								$xml .= "\n<LearnerFAM><LearnFAMType>DSF</LearnFAMType><LearnFAMCode>" . $learner->L49d . "</LearnFAMCode></LearnerFAM>";

							if($learner->L40a!='' && $learner->L40a!='99' && $learner->L40a!='10' && $learner->L40a!='11' && $learner->L40a!='16')
								$xml .= "\n<LearnerFAM><LearnFAMType>NLM</LearnFAMType><LearnFAMCode>" . $learner->L40a . "</LearnFAMCode></LearnerFAM>";

							if($learner->L40b!='' && $learner->L40b!='99' && $learner->L40b!='10' && $learner->L40b!='11' && $learner->L40b!='16')
								$xml .= "\n<LearnerFAM><LearnFAMType>NLM</LearnFAMType><LearnFAMCode>" . $learner->L40b . "</LearnFAMCode></LearnerFAM>";

							if($learner->L42a!='')
								$xml .= "\n<ProviderSpecLearnerMonitoring><ProvSpecLearnMonOccur>A</ProvSpecLearnMonOccur><ProvSpecLearnMon>" . $learner->L42a . "</ProvSpecLearnMon></ProviderSpecLearnerMonitoring>";

							if($learner->L42b!='')
								$xml .= "\n<ProviderSpecLearnerMonitoring><ProvSpecLearnMonOccur>B</ProvSpecLearnMonOccur><ProvSpecLearnMon>" . $learner->L42b . "</ProvSpecLearnMon></ProviderSpecLearnerMonitoring>";

							if($learner->L47=='98')
							{
								$xml .= "\n<LearnerEmploymentStatus><EmpStat>" . 98 . "</EmpStat><DateEmpStatApp>" . Date::toMySQL($ilr2->main->A27) . "</DateEmpStatApp><EmpId>" . $ilr2->main->A44 . "</EmpId><WorkLocPostCode>" . $ilr2->main->A45 . "</WorkLocPostCode></LearnerEmploymentStatus>";
							}
							elseif($learner->L47=='17')
							{
								$xml .= "\n<LearnerEmploymentStatus><EmpStat>" . 12 . "</EmpStat><DateEmpStatApp>" . Date::toMySQL($ilr2->main->A27) . "</DateEmpStatApp><EmpId>" . $ilr2->main->A44 . "</EmpId><WorkLocPostCode>" . $ilr2->main->A45 . "</WorkLocPostCode></LearnerEmploymentStatus>";
							}
							elseif($learner->L47=='16' || $learner->L47=='2' || $learner->L47=='5')
							{
								$xml .= "\n<LearnerEmploymentStatus><EmpStat>" . 11 . "</EmpStat><DateEmpStatApp>" . Date::toMySQL($ilr2->main->A27) . "</DateEmpStatApp><EmpId>" . $ilr2->main->A44 . "</EmpId><WorkLocPostCode>" . $ilr2->main->A45 . "</WorkLocPostCode></LearnerEmploymentStatus>";
							}
							elseif($learner->L47=='15')
							{
								$xml .= "\n<LearnerEmploymentStatus><EmpStat>" . 11 . "</EmpStat><DateEmpStatApp>" . Date::toMySQL($ilr2->main->A27) . "</DateEmpStatApp><EmpId>" . $ilr2->main->A44 . "</EmpId><WorkLocPostCode>" . $ilr2->main->A45 . "</WorkLocPostCode><EmploymentStatusMonitoring><ESMType>BSI</ESMType><ESMCode>2</ESMCode></EmploymentStatusMonitoring></LearnerEmploymentStatus>";
							}
							elseif($learner->L47=='14')
							{
								$xml .= "\n<LearnerEmploymentStatus><EmpStat>" . 11 . "</EmpStat><DateEmpStatApp>" . Date::toMySQL($ilr2->main->A27) . "</DateEmpStatApp><EmpId>" . $ilr2->main->A44 . "</EmpId><WorkLocPostCode>" . $ilr2->main->A45 . "</WorkLocPostCode><EmploymentStatusMonitoring><ESMType>BSI</ESMType><ESMCode>1</ESMCode></EmploymentStatusMonitoring></LearnerEmploymentStatus>";
							}
							elseif($learner->L47=='13' || $learner->L47=='4')
							{
								$xml .= "\n<LearnerEmploymentStatus><EmpStat>" . 11 . "</EmpStat><DateEmpStatApp>" . Date::toMySQL($ilr2->main->A27) . "</DateEmpStatApp><EmpId>" . $ilr2->main->A44 . "</EmpId><WorkLocPostCode>" . $ilr2->main->A45 . "</WorkLocPostCode><EmploymentStatusMonitoring><ESMType>RFU</ESMType><ESMCode>2</ESMCode></EmploymentStatusMonitoring></LearnerEmploymentStatus>";
							}
							elseif($learner->L47=='12')
							{
								$xml .= "\n<LearnerEmploymentStatus><EmpStat>" . 11 . "</EmpStat><DateEmpStatApp>" . Date::toMySQL($ilr2->main->A27) . "</DateEmpStatApp><EmpId>" . $ilr2->main->A44 . "</EmpId><WorkLocPostCode>" . $ilr2->main->A45 . "</WorkLocPostCode><EmploymentStatusMonitoring><ESMType>RFU</ESMType><ESMCode>2</ESMCode></EmploymentStatusMonitoring><EmploymentStatusMonitoring><ESMType>BSI</ESMType><ESMCode>2</ESMCode></EmploymentStatusMonitoring></LearnerEmploymentStatus>";
							}
							elseif($learner->L47=='11')
							{
								$xml .= "\n<LearnerEmploymentStatus><EmpStat>" . 11 . "</EmpStat><DateEmpStatApp>" . Date::toMySQL($ilr2->main->A27) . "</DateEmpStatApp><EmpId>" . $ilr2->main->A44 . "</EmpId><WorkLocPostCode>" . $ilr2->main->A45 . "</WorkLocPostCode><EmploymentStatusMonitoring><ESMType>RFU</ESMType><ESMCode>2</ESMCode></EmploymentStatusMonitoring><EmploymentStatusMonitoring><ESMType>BSI</ESMType><ESMCode>1</ESMCode></EmploymentStatusMonitoring></LearnerEmploymentStatus>";
							}
							elseif($learner->L47=='10' || $learner->L47=='3')
							{
								$xml .= "\n<LearnerEmploymentStatus><EmpStat>" . 11 . "</EmpStat><DateEmpStatApp>" . Date::toMySQL($ilr2->main->A27) . "</DateEmpStatApp><EmpId>" . $ilr2->main->A44 . "</EmpId><WorkLocPostCode>" . $ilr2->main->A45 . "</WorkLocPostCode><EmploymentStatusMonitoring><ESMType>RFU</ESMType><ESMCode>1</ESMCode></EmploymentStatusMonitoring></LearnerEmploymentStatus>";
							}
							elseif($learner->L47=='9')
							{
								$xml .= "\n<LearnerEmploymentStatus><EmpStat>" . 11 . "</EmpStat><DateEmpStatApp>" . Date::toMySQL($ilr2->main->A27) . "</DateEmpStatApp><EmpId>" . $ilr2->main->A44 . "</EmpId><WorkLocPostCode>" . $ilr2->main->A45 . "</WorkLocPostCode><EmploymentStatusMonitoring><ESMType>RFU</ESMType><ESMCode>1</ESMCode></EmploymentStatusMonitoring><EmploymentStatusMonitoring><ESMType>BSI</ESMType><ESMCode>2</ESMCode></EmploymentStatusMonitoring></LearnerEmploymentStatus>";
							}
							elseif($learner->L47=='8')
							{
								$xml .= "\n<LearnerEmploymentStatus><EmpStat>" . 11 . "</EmpStat><DateEmpStatApp>" . Date::toMySQL($ilr2->main->A27) . "</DateEmpStatApp><EmpId>" . $ilr2->main->A44 . "</EmpId><WorkLocPostCode>" . $ilr2->main->A45 . "</WorkLocPostCode><EmploymentStatusMonitoring><ESMType>RFU</ESMType><ESMCode>1</ESMCode></EmploymentStatusMonitoring><EmploymentStatusMonitoring><ESMType>BSI</ESMType><ESMCode>1</ESMCode></EmploymentStatusMonitoring></LearnerEmploymentStatus>";
							}
							elseif($learner->L47=='7')
							{
								$xml .= "\n<LearnerEmploymentStatus><EmpStat>" . 10 . "</EmpStat><DateEmpStatApp>" . Date::toMySQL($ilr2->main->A27) . "</DateEmpStatApp><EmpId>" . $ilr2->main->A44 . "</EmpId><WorkLocPostCode>" . $ilr2->main->A45 . "</WorkLocPostCode><EmploymentStatusMonitoring><ESMType>EII</ESMType><ESMCode>2</ESMCode></EmploymentStatusMonitoring></LearnerEmploymentStatus>";
							}
							elseif($learner->L47=='6')
							{
								$xml .= "\n<LearnerEmploymentStatus><EmpStat>" . 10 . "</EmpStat><DateEmpStatApp>" . Date::toMySQL($ilr2->main->A27) . "</DateEmpStatApp><EmpId>" . $ilr2->main->A44 . "</EmpId><WorkLocPostCode>" . $ilr2->main->A45 . "</WorkLocPostCode><EmploymentStatusMonitoring><ESMType>EII</ESMType><ESMCode>1</ESMCode></EmploymentStatusMonitoring></LearnerEmploymentStatus>";
							}
							elseif($learner->L47=='1')
							{
								$xml .= "\n<LearnerEmploymentStatus><EmpStat>" . 10 . "</EmpStat><DateEmpStatApp>" . Date::toMySQL($ilr2->main->A27) . "</DateEmpStatApp><EmpId>" . $ilr2->main->A44 . "</EmpId><WorkLocPostCode>" . $ilr2->main->A45 . "</WorkLocPostCode></LearnerEmploymentStatus>";
							}
							//	else
							//		pre("No CES was found " . $learner->L47);

							$date = Array();
							$date[] = "[" . Date::toMySQL($ilr2->main->A27) . "]";
							foreach($ilr2->subaim as $aim)
							{
								if(!in_array("[" . Date::toMySQL($aim->A27) . "]",$date))
								{
									$date[] = "[" . Date::toMySQL($aim->A27) . "]";
									if($learner->L47=='98')
									{
										$xml .= "\n<LearnerEmploymentStatus><EmpStat>" . 98 . "</EmpStat><DateEmpStatApp>" . Date::toMySQL($aim->A27) . "</DateEmpStatApp><EmpId>" . $ilr2->main->A44 . "</EmpId><WorkLocPostCode>" . $ilr2->main->A45 . "</WorkLocPostCode></LearnerEmploymentStatus>";
									}
									elseif($learner->L47=='17')
									{
										$xml .= "\n<LearnerEmploymentStatus><EmpStat>" . 12 . "</EmpStat><DateEmpStatApp>" . Date::toMySQL($aim->A27) . "</DateEmpStatApp><EmpId>" . $ilr2->main->A44 . "</EmpId><WorkLocPostCode>" . $ilr2->main->A45 . "</WorkLocPostCode></LearnerEmploymentStatus>";
									}
									elseif($learner->L47=='16' || $learner->L47=='2' || $learner->L47=='5')
									{
										$xml .= "\n<LearnerEmploymentStatus><EmpStat>" . 11 . "</EmpStat><DateEmpStatApp>" . Date::toMySQL($aim->A27) . "</DateEmpStatApp><EmpId>" . $ilr2->main->A44 . "</EmpId><WorkLocPostCode>" . $ilr2->main->A45 . "</WorkLocPostCode></LearnerEmploymentStatus>";
									}
									elseif($learner->L47=='15')
									{
										$xml .= "\n<LearnerEmploymentStatus><EmpStat>" . 11 . "</EmpStat><DateEmpStatApp>" . Date::toMySQL($aim->A27) . "</DateEmpStatApp><EmpId>" . $ilr2->main->A44 . "</EmpId><WorkLocPostCode>" . $ilr2->main->A45 . "</WorkLocPostCode><EmploymentStatusMonitoring><ESMType>BSI</ESMType><ESMCode>2</ESMCode></EmploymentStatusMonitoring></LearnerEmploymentStatus>";
									}
									elseif($learner->L47=='14')
									{
										$xml .= "\n<LearnerEmploymentStatus><EmpStat>" . 11 . "</EmpStat><DateEmpStatApp>" . Date::toMySQL($aim->A27) . "</DateEmpStatApp><EmpId>" . $ilr2->main->A44 . "</EmpId><WorkLocPostCode>" . $ilr2->main->A45 . "</WorkLocPostCode><EmploymentStatusMonitoring><ESMType>BSI</ESMType><ESMCode>1</ESMCode></EmploymentStatusMonitoring></LearnerEmploymentStatus>";
									}
									elseif($learner->L47=='13' || $learner->L47=='4')
									{
										$xml .= "\n<LearnerEmploymentStatus><EmpStat>" . 11 . "</EmpStat><DateEmpStatApp>" . Date::toMySQL($aim->A27) . "</DateEmpStatApp><EmpId>" . $ilr2->main->A44 . "</EmpId><WorkLocPostCode>" . $ilr2->main->A45 . "</WorkLocPostCode><EmploymentStatusMonitoring><ESMType>RFU</ESMType><ESMCode>2</ESMCode></EmploymentStatusMonitoring></LearnerEmploymentStatus>";
									}
									elseif($learner->L47=='12')
									{
										$xml .= "\n<LearnerEmploymentStatus><EmpStat>" . 11 . "</EmpStat><DateEmpStatApp>" . Date::toMySQL($aim->A27) . "</DateEmpStatApp><EmpId>" . $ilr2->main->A44 . "</EmpId><WorkLocPostCode>" . $ilr2->main->A45 . "</WorkLocPostCode><EmploymentStatusMonitoring><ESMType>RFU</ESMType><ESMCode>2</ESMCode></EmploymentStatusMonitoring><EmploymentStatusMonitoring><ESMType>BSI</ESMType><ESMCode>2</ESMCode></EmploymentStatusMonitoring></LearnerEmploymentStatus>";
									}
									elseif($learner->L47=='11')
									{
										$xml .= "\n<LearnerEmploymentStatus><EmpStat>" . 11 . "</EmpStat><DateEmpStatApp>" . Date::toMySQL($aim->A27) . "</DateEmpStatApp><EmpId>" . $ilr2->main->A44 . "</EmpId><WorkLocPostCode>" . $ilr2->main->A45 . "</WorkLocPostCode><EmploymentStatusMonitoring><ESMType>RFU</ESMType><ESMCode>2</ESMCode></EmploymentStatusMonitoring><EmploymentStatusMonitoring><ESMType>BSI</ESMType><ESMCode>1</ESMCode></EmploymentStatusMonitoring></LearnerEmploymentStatus>";
									}
									elseif($learner->L47=='10' || $learner->L47=='3')
									{
										$xml .= "\n<LearnerEmploymentStatus><EmpStat>" . 11 . "</EmpStat><DateEmpStatApp>" . Date::toMySQL($aim->A27) . "</DateEmpStatApp><EmpId>" . $ilr2->main->A44 . "</EmpId><WorkLocPostCode>" . $ilr2->main->A45 . "</WorkLocPostCode><EmploymentStatusMonitoring><ESMType>RFU</ESMType><ESMCode>1</ESMCode></EmploymentStatusMonitoring></LearnerEmploymentStatus>";
									}
									elseif($learner->L47=='9')
									{
										$xml .= "\n<LearnerEmploymentStatus><EmpStat>" . 11 . "</EmpStat><DateEmpStatApp>" . Date::toMySQL($aim->A27) . "</DateEmpStatApp><EmpId>" . $ilr2->main->A44 . "</EmpId><WorkLocPostCode>" . $ilr2->main->A45 . "</WorkLocPostCode><EmploymentStatusMonitoring><ESMType>RFU</ESMType><ESMCode>1</ESMCode></EmploymentStatusMonitoring><EmploymentStatusMonitoring><ESMType>BSI</ESMType><ESMCode>2</ESMCode></EmploymentStatusMonitoring></LearnerEmploymentStatus>";
									}
									elseif($learner->L47=='8')
									{
										$xml .= "\n<LearnerEmploymentStatus><EmpStat>" . 11 . "</EmpStat><DateEmpStatApp>" . Date::toMySQL($aim->A27) . "</DateEmpStatApp><EmpId>" . $ilr2->main->A44 . "</EmpId><WorkLocPostCode>" . $ilr2->main->A45 . "</WorkLocPostCode><EmploymentStatusMonitoring><ESMType>RFU</ESMType><ESMCode>1</ESMCode></EmploymentStatusMonitoring><EmploymentStatusMonitoring><ESMType>BSI</ESMType><ESMCode>1</ESMCode></EmploymentStatusMonitoring></LearnerEmploymentStatus>";
									}
									elseif($learner->L47=='7')
									{
										$xml .= "\n<LearnerEmploymentStatus><EmpStat>" . 10 . "</EmpStat><DateEmpStatApp>" . Date::toMySQL($aim->A27) . "</DateEmpStatApp><EmpId>" . $ilr2->main->A44 . "</EmpId><WorkLocPostCode>" . $ilr2->main->A45 . "</WorkLocPostCode><EmploymentStatusMonitoring><ESMType>EII</ESMType><ESMCode>2</ESMCode></EmploymentStatusMonitoring></LearnerEmploymentStatus>";
									}
									elseif($learner->L47=='6')
									{
										$xml .= "\n<LearnerEmploymentStatus><EmpStat>" . 10 . "</EmpStat><DateEmpStatApp>" . Date::toMySQL($aim->A27) . "</DateEmpStatApp><EmpId>" . $ilr2->main->A44 . "</EmpId><WorkLocPostCode>" . $ilr2->main->A45 . "</WorkLocPostCode><EmploymentStatusMonitoring><ESMType>EII</ESMType><ESMCode>1</ESMCode></EmploymentStatusMonitoring></LearnerEmploymentStatus>";
									}
									elseif($learner->L47=='1')
									{
										$xml .= "\n<LearnerEmploymentStatus><EmpStat>" . 10 . "</EmpStat><DateEmpStatApp>" . Date::toMySQL($aim->A27) . "</DateEmpStatApp><EmpId>" . $ilr2->main->A44 . "</EmpId><WorkLocPostCode>" . $ilr2->main->A45 . "</WorkLocPostCode></LearnerEmploymentStatus>";
									}
									//else
									//	pre("No CES was found " . $learner->L47);

								}
							}

						}

						if(($ilr2->programmeaim->A15!="99" && $ilr2->programmeaim->A15!=""))
						{
							if($ilr2->programmeaim->A31=='' || $ilr2->programmeaim->A31=='dd/mm/yyyy' || $ilr2->programmeaim->A31=='00000000')
							{
								$migrate = 1;
							}
							elseif( ($ilr2->programmeaim->A40=='' || $ilr2->programmeaim->A40=='dd/mm/yyyy' || $ilr2->programmeaim->A40=='00000000') && ($ilr2->programmeaim->A35=='4' || $ilr2->programmeaim->A35=='5'))
							{
								$migrate = 1;
							}

							if($ilr2->programmeaim->A28!='00000000')
							{
								$A28 = new Date($ilr2->programmeaim->A28);
								if($A28->before('01/08/2009'))
									$migrate = 0;
							}
							if($ilr2->programmeaim->A31!='' && $ilr2->programmeaim->A31!='dd/mm/yyyy' && $ilr2->programmeaim->A31!='00000000')
							{
								$A31 = new Date($ilr2->programmeaim->A31);
								if($A31->after('01/08/2012'))
									$migrate = 1;
							}
						}
						else
						{
							if($ilr2->main->A31=='' || $ilr2->main->A31=='dd/mm/yyyy' || $ilr2->main->A31=='00000000')
							{
								$migrate = 1;
							}
							elseif( ($ilr2->main->A40=='' || $ilr2->main->A40=='dd/mm/yyyy' || $ilr2->main->A40=='00000000') && ($ilr2->main->A35=='4' || $ilr2->main->A35=='5'))
							{
								$migrate = 1;
							}

							$A28 = new Date($ilr2->main->A28);
							if($A28->before('01/08/2009'))
								$migrate = 0;

							if($ilr2->main->A31!='' && $ilr2->main->A31!='dd/mm/yyyy' && $ilr2->main->A31!='00000000')
							{
								$A31 = new Date($ilr2->main->A31);
								if($A31->after('01/08/2012'))
									$migrate = 1;
							}
						}

						foreach($ilr2->programmeaim as $aim)
						{
							if( ($aim->A15!='99' && $aim->A15!='') || $aim->A10=='70')
							{
								$xml .= "\n<LearningDelivery>";
								$xml .= "\n<LearnAimRef>" . $aim->A09 . "</LearnAimRef>";
								$xml .= "\n<AimType>1</AimType>";
								$xml .= "\n<LearnStartDate>" . Date::toMySQL($aim->A27) . "</LearnStartDate>";
								$xml .= "\n<LearnPlanEndDate>" . Date::toMySQL($aim->A28) . "</LearnPlanEndDate>";
								if($aim->A10=='46')
									$xml .= "\n<FundModel>45</FundModel>";
								else
									$xml .= "\n<FundModel>" . $aim->A10 . "</FundModel>";
								$xml .= "\n<GLH>" . $aim->A32 . "</GLH>";
								$xml .= "\n<PlanCredVal>" . $aim->A59 . "</PlanCredVal>";
								$xml .= "\n<ProgType>" . $aim->A15 . "</ProgType>";
								$xml .= "\n<FworkCode>" . $aim->A26 . "</FworkCode>";
								if($aim->A15=='2' || $aim->A15=='3' || $aim->A15=='10' || $aim->A15=='20' || $aim->A15=='21')
									$xml .= "\n<PwayCode>0</PwayCode>";
								if($aim->A15=='2' || $aim->A15=='3' || $aim->A15=='10' || $aim->A15=='20' || $aim->A15=='21')
									$xml .= "\n<ProgEntRoute>" . $aim->A16 . "</ProgEntRoute>";
								$xml .= "\n<MainDelMeth>" . $aim->A18 . "</MainDelMeth>";
								$xml .= "\n<DelMode>" . $aim->A17 . "</DelMode>";
								$xml .= "\n<PartnerUKPRN>" . $aim->A22 . "</PartnerUKPRN>";
								$xml .= "\n<DelLocPostCode>" . $aim->A23 . "</DelLocPostCode>";
								$xml .= "\n<DistLearnSLN>" . $aim->A52 . "</DistLearnSLN>";
								$xml .= "\n<FeeYTD>" . $aim->A13 . "</FeeYTD>";
								$xml .= "\n<FeeSource>" . $aim->A57 . "</FeeSource>";
								$xml .= "\n<PropFundRemain>" . $aim->A51a . "</PropFundRemain>";
								$xml .= "\n<EmpRole>" . $aim->A19 . "</EmpRole>";
								if($aim->A10=='70')
								{
									$xml .= "\n<ESFProjDosNumber>" . $aim->A61 . "</ESFProjDosNumber>";
									$xml .= "\n<ESFLocProjNumber>" . $aim->A62 . "</ESFLocProjNumber>";
								}
								$Cont = $ilr2->programmeaim->A70;
								$Cont = DAO::getSingleValue($link, "select Cont2012 from central.mapping where UKPRN2011 = '$ukprn' and Cont2011 = '$Cont'");
								//if($Cont=='')
								//	pre("Not found for " . $ukprn);
								$xml .= "\n<ContOrg>" . $Cont . "</ContOrg>";
								if($aim->A68!='' && $aim->A68!='99')
									$xml .= "\n<EmpOutcome>" . $aim->A68 . "</EmpOutcome>";
								if($aim->A34=='1' || $aim->A34=='2' || $aim->A34=='3' || $aim->A34=='6')
									$xml .= "\n<CompStatus>" . $aim->A34 . "</CompStatus>";
								elseif($aim->A34=='4' || $aim->A34=='5')
									$xml .= "\n<CompStatus>3</CompStatus>";
								$xml .= "\n<LearnActEndDate>" . Date::toMySQL($aim->A31) . "</LearnActEndDate>";
								if(($aim->A34=='3') && ($aim->A50=='1' || $aim->A50=='2' || $aim->A50=='3' || $aim->A50=='7' || $aim->A50=='27' || $aim->A50=='28' || $aim->A50=='29' || $aim->A50=='97' || $aim->A50=='98'))
									$xml .= "\n<WithdrawReason>" . $aim->A50 . "</WithdrawReason>";
								elseif($aim->A34=='4')
									$xml .= "\n<WithdrawReason>40</WithdrawReason>";
								elseif($aim->A34=='5')
									$xml .= "\n<WithdrawReason>1</WithdrawReason>";
								elseif($aim->A34=='3')
									$xml .= "\n<WithdrawReason>97</WithdrawReason>";
								if($aim->A35!='9')
									$xml .= "\n<Outcome>" . $aim->A35 . "</Outcome>";
								$xml .= "\n<AchDate>" . $aim->A40 . "</AchDate>";
								$xml .= "\n<CredAch>" . $aim->A60 . "</CredAch>";
								if($aim->A50=='5')
									$xml .= "\n<ActProgRoute>" . 3 . "</ActProgRoute>";
								elseif($aim->A50=='30')
									$xml .= "\n<ActProgRoute>" . 1 . "</ActProgRoute>";
								elseif($aim->A50=='31')
									$xml .= "\n<ActProgRoute>" . 2 . "</ActProgRoute>";
								elseif($aim->A50=='32')
									$xml .= "\n<ActProgRoute>" . 3 . "</ActProgRoute>";
								elseif($aim->A50=='33')
									$xml .= "\n<ActProgRoute>" . 4 . "</ActProgRoute>";
								elseif($aim->A50=='34')
									$xml .= "\n<ActProgRoute>" . 5 . "</ActProgRoute>";
								elseif($aim->A50=='35')
									$xml .= "\n<ActProgRoute>" . 6 . "</ActProgRoute>";
								$xml .= "\n<LearningDeliveryFAM><LearnDelFAMType>SOF</LearnDelFAMType><LearnDelFAMCode>" . $aim->A11a . "</LearnDelFAMCode></LearningDeliveryFAM>";
								if($aim->A11b!='999')
									$xml .= "\n<LearningDeliveryFAM><LearnDelFAMType>SOF</LearnDelFAMType><LearnDelFAMCode>" . $aim->A11b . "</LearnDelFAMCode></LearningDeliveryFAM>";
								if($aim->A58=='1' || $aim->A58=='2' || $aim->A58=='3' || $aim->A58=='4')
									$xml .= "\n<LearningDeliveryFAM><LearnDelFAMType>ASL</LearnDelFAMType><LearnDelFAMCode>" . $aim->A58 . "</LearnDelFAMCode></LearningDeliveryFAM>";
								if($aim->A58=='5')
									$xml .= "\n<LearningDeliveryFAM><LearnDelFAMType>FSI</LearnDelFAMType><LearnDelFAMCode>1</LearnDelFAMCode></LearningDeliveryFAM>";
								if($aim->A20=='1' || $aim->A20=='2')
									$xml .= "\n<LearningDeliveryFAM><LearnDelFAMType>RET</LearnDelFAMType><LearnDelFAMCode>" . $aim->A20 . "</LearnDelFAMCode></LearningDeliveryFAM>";
								if($aim->A63 >= 1 && $aim->A63 <= 17)
									$xml .= "\n<LearningDeliveryFAM><LearnDelFAMType>NSA</LearnDelFAMType><LearnDelFAMCode>" . $aim->A63 . "</LearnDelFAMCode></LearningDeliveryFAM>";
								if($aim->A69 >= 1 && $aim->A69 <= 3)
									$xml .= "\n<LearningDeliveryFAM><LearnDelFAMType>EEF</LearnDelFAMType><LearnDelFAMCode>" . $aim->A69 . "</LearnDelFAMCode></LearningDeliveryFAM>";
								if($aim->A46a!='' && $aim->A46a!='999')
									$xml .= "\n<LearningDeliveryFAM><LearnDelFAMType>LDM</LearnDelFAMType><LearnDelFAMCode>" . $aim->A46a . "</LearnDelFAMCode></LearningDeliveryFAM>";
								if($aim->A46b!='' && $aim->A46b!='999')
									$xml .= "\n<LearningDeliveryFAM><LearnDelFAMType>LDM</LearnDelFAMType><LearnDelFAMCode>" . $aim->A46b . "</LearnDelFAMCode></LearningDeliveryFAM>";
								if($aim->A16=='1' || $aim->A16=='7' || $aim->A16=='8' || $aim->A16=='11' || $aim->A16=='12')
									$xml .= "\n<LearningDeliveryFAM><LearnDelFAMType>RES</LearnDelFAMType><LearnDelFAMCode>1</LearnDelFAMCode></LearningDeliveryFAM>";
								if($aim->A48a!='')
									$xml .= "\n<ProviderSpecDeliveryMonitoring><ProvSpecDelMonOccur>A</ProvSpecDelMonOccur><ProvSpecDelMon>" . $aim->A48a . "</ProvSpecDelMon></ProviderSpecDeliveryMonitoring>";
								if($aim->A48b!='')
									$xml .= "\n<ProviderSpecDeliveryMonitoring><ProvSpecDelMonOccur>B</ProvSpecDelMonOccur><ProvSpecDelMon>" . $aim->A48b . "</ProvSpecDelMon></ProviderSpecDeliveryMonitoring>";

								$xml .= "\n</LearningDelivery>";
							}
						}

						foreach($ilr2->main as $aim)
						{
							$xml .= "\n<LearningDelivery>";
							$xml .= "\n<LearnAimRef>" . $aim->A09 . "</LearnAimRef>";
							if($aim->A15=='99')
								$xml .= "\n<AimType>4</AimType>";
							elseif($aim->A04=='30' || $aim->A04=='2')
								$xml .= "\n<AimType>2</AimType>";
							$xml .= "\n<LearnStartDate>" . Date::toMySQL($aim->A27) . "</LearnStartDate>";
							$xml .= "\n<LearnPlanEndDate>" . Date::toMySQL($aim->A28) . "</LearnPlanEndDate>";
							if($aim->A10=='46')
								$xml .= "\n<FundModel>45</FundModel>";
							else
								$xml .= "\n<FundModel>" . $aim->A10 . "</FundModel>";
							$xml .= "\n<GLH>" . $aim->A32 . "</GLH>";
							$xml .= "\n<PlanCredVal>" . $aim->A59 . "</PlanCredVal>";
							$xml .= "\n<ProgType>" . $aim->A15 . "</ProgType>";
							$xml .= "\n<FworkCode>" . $aim->A26 . "</FworkCode>";
							if($aim->A15=='2' || $aim->A15=='3' || $aim->A15=='10' || $aim->A15=='20' || $aim->A15=='21')
								$xml .= "\n<PwayCode>0</PwayCode>";
							$xml .= "\n<MainDelMeth>" . $aim->A18 . "</MainDelMeth>";
							$xml .= "\n<DelMode>" . $aim->A17 . "</DelMode>";
							$xml .= "\n<PartnerUKPRN>" . $aim->A22 . "</PartnerUKPRN>";
							$xml .= "\n<DelLocPostCode>" . $aim->A23 . "</DelLocPostCode>";
							$xml .= "\n<DistLearnSLN>" . $aim->A52 . "</DistLearnSLN>";
							$xml .= "\n<FeeYTD>" . $aim->A13 . "</FeeYTD>";
							$xml .= "\n<FeeSource>" . $aim->A57 . "</FeeSource>";
							$xml .= "\n<PropFundRemain>" . $aim->A51a . "</PropFundRemain>";
							$xml .= "\n<EmpRole>" . $aim->A19 . "</EmpRole>";
							if($aim->A10=='70')
							{
								$xml .= "\n<ESFProjDosNumber>" . $aim->A61 . "</ESFProjDosNumber>";
								$xml .= "\n<ESFLocProjNumber>" . $aim->A62 . "</ESFLocProjNumber>";
							}

							if($aim->A15=='99')
							{
								$Cont = $ilr2->main->A70;
								$Cont = DAO::getSingleValue($link, "select Cont2012 from central.mapping where UKPRN2011 = '$ukprn' and Cont2011 = '$Cont'");
								//	if($Cont=='')
								//		pre("Not found for " . $ukprn . " while the Cont is " . $L03);
								$xml .= "\n<ContOrg>" . $Cont . "</ContOrg>";
							}

							if($aim->A68!='' && $aim->A68!='99')
								$xml .= "\n<EmpOutcome>" . $aim->A68 . "</EmpOutcome>";
							if($aim->A34=='1' || $aim->A34=='2' || $aim->A34=='3' || $aim->A34=='6')
								$xml .= "\n<CompStatus>" . $aim->A34 . "</CompStatus>";
							elseif($aim->A34=='4' || $aim->A34=='5')
								$xml .= "\n<CompStatus>3</CompStatus>";
							$xml .= "\n<LearnActEndDate>" . Date::toMySQL($aim->A31) . "</LearnActEndDate>";
							if( ($aim->A34=='3') && ($aim->A50=='1' || $aim->A50=='2' || $aim->A50=='3' || $aim->A50=='7' || $aim->A50=='27' || $aim->A50=='28' || $aim->A50=='29' || $aim->A50=='97' || $aim->A50=='98'))
								$xml .= "\n<WithdrawReason>" . $aim->A50 . "</WithdrawReason>";
							elseif($aim->A34=='4')
								$xml .= "\n<WithdrawReason>40</WithdrawReason>";
							elseif($aim->A34=='5')
								$xml .= "\n<WithdrawReason>1</WithdrawReason>";
							elseif($aim->A34=='3')
								$xml .= "\n<WithdrawReason>97</WithdrawReason>";
							if($aim->A35!='9')
								$xml .= "\n<Outcome>" . $aim->A35 . "</Outcome>";
							$xml .= "\n<AchDate>" . $aim->A40 . "</AchDate>";
							$xml .= "\n<CredAch>" . $aim->A60 . "</CredAch>";
							if($aim->A50=='5')
								$xml .= "\n<ActProgRoute>" . 3 . "</ActProgRoute>";
							elseif($aim->A50=='30')
								$xml .= "\n<ActProgRoute>" . 1 . "</ActProgRoute>";
							elseif($aim->A50=='31')
								$xml .= "\n<ActProgRoute>" . 2 . "</ActProgRoute>";
							elseif($aim->A50=='32')
								$xml .= "\n<ActProgRoute>" . 3 . "</ActProgRoute>";
							elseif($aim->A50=='33')
								$xml .= "\n<ActProgRoute>" . 4 . "</ActProgRoute>";
							elseif($aim->A50=='34')
								$xml .= "\n<ActProgRoute>" . 5 . "</ActProgRoute>";
							elseif($aim->A50=='35')
								$xml .= "\n<ActProgRoute>" . 6 . "</ActProgRoute>";

							if($aim->A15=='99')
							{
								$xml .= "\n<LearningDeliveryFAM><LearnDelFAMType>SOF</LearnDelFAMType><LearnDelFAMCode>" . $ilr2->programmeaim->A11a . "</LearnDelFAMCode></LearningDeliveryFAM>";
								if($aim->A11b!='999')
									$xml .= "\n<LearningDeliveryFAM><LearnDelFAMType>SOF</LearnDelFAMType><LearnDelFAMCode>" . $ilr2->programmeaim->A11b . "</LearnDelFAMCode></LearningDeliveryFAM>";
							}

							if(($aim->A71=='1' || $aim->A71=='2') && ($aim->A10!='21' && $aim->A10!='70' && $aim->A10!='10' && $aim->A10!='82' && $aim->A10!='99'))
								$xml .= "\n<LearningDeliveryFAM><LearnDelFAMType>FFI</LearnDelFAMType><LearnDelFAMCode>" . $aim->A71 . "</LearnDelFAMCode></LearningDeliveryFAM>";
							if($aim->A10!='21' && $aim->A10!='22' && $aim->A10!='70' && $aim->A10!='10' && $aim->A10!='99')
								if($aim->A53=='11')
									$xml .= "\n<LearningDeliveryFAM><LearnDelFAMType>ALN</LearnDelFAMType><LearnDelFAMCode>1</LearnDelFAMCode></LearningDeliveryFAM>";
								elseif($aim->A53=='12')
									$xml .= "\n<LearningDeliveryFAM><LearnDelFAMType>ASN</LearnDelFAMType><LearnDelFAMCode>1</LearnDelFAMCode></LearningDeliveryFAM>";
								elseif($aim->A53=='13')
								{
									$xml .= "\n<LearningDeliveryFAM><LearnDelFAMType>ALN</LearnDelFAMType><LearnDelFAMCode>1</LearnDelFAMCode></LearningDeliveryFAM>";
									$xml .= "\n<LearningDeliveryFAM><LearnDelFAMType>ASN</LearnDelFAMType><LearnDelFAMCode>1</LearnDelFAMCode></LearningDeliveryFAM>";
								}
							if($aim->A58=='1' || $aim->A58=='2' || $aim->A58=='3' || $aim->A58=='4')
								$xml .= "\n<LearningDeliveryFAM><LearnDelFAMType>ASL</LearnDelFAMType><LearnDelFAMCode>" . $aim->A58 . "</LearnDelFAMCode></LearningDeliveryFAM>";
							if($aim->A58=='5')
								$xml .= "\n<LearningDeliveryFAM><LearnDelFAMType>FSI</LearnDelFAMType><LearnDelFAMCode>1</LearnDelFAMCode></LearningDeliveryFAM>";
							if($aim->A20=='1' || $aim->A20=='2')
								$xml .= "\n<LearningDeliveryFAM><LearnDelFAMType>RET</LearnDelFAMType><LearnDelFAMCode>" . $aim->A20 . "</LearnDelFAMCode></LearningDeliveryFAM>";
							if($aim->A63 >= 1 && $aim->A63 <= 17)
								$xml .= "\n<LearningDeliveryFAM><LearnDelFAMType>NSA</LearnDelFAMType><LearnDelFAMCode>" . $aim->A63 . "</LearnDelFAMCode></LearningDeliveryFAM>";
							if($aim->A69 >= 1 && $aim->A69 <= 3)
								$xml .= "\n<LearningDeliveryFAM><LearnDelFAMType>EEF</LearnDelFAMType><LearnDelFAMCode>" . $aim->A69 . "</LearnDelFAMCode></LearningDeliveryFAM>";
							if($aim->A46a!='' && $aim->A46a!='999')
								$xml .= "\n<LearningDeliveryFAM><LearnDelFAMType>LDM</LearnDelFAMType><LearnDelFAMCode>" . $aim->A46a . "</LearnDelFAMCode></LearningDeliveryFAM>";
							if($aim->A46b!='' && $aim->A46b!='999')
								$xml .= "\n<LearningDeliveryFAM><LearnDelFAMType>LDM</LearnDelFAMType><LearnDelFAMCode>" . $aim->A46b . "</LearnDelFAMCode></LearningDeliveryFAM>";
							if($aim->A16=='1' || $aim->A16=='7' || $aim->A16=='8' || $aim->A16=='11' || $aim->A16=='12')
								$xml .= "\n<LearningDeliveryFAM><LearnDelFAMType>RES</LearnDelFAMType><LearnDelFAMCode>1</LearnDelFAMCode></LearningDeliveryFAM>";
							if($aim->A48a!='')
								$xml .= "\n<ProviderSpecDeliveryMonitoring><ProvSpecDelMonOccur>A</ProvSpecDelMonOccur><ProvSpecDelMon>" . $aim->A48a . "</ProvSpecDelMon></ProviderSpecDeliveryMonitoring>";
							if($aim->A48b!='')
								$xml .= "\n<ProviderSpecDeliveryMonitoring><ProvSpecDelMonOccur>B</ProvSpecDelMonOccur><ProvSpecDelMon>" . $aim->A48b . "</ProvSpecDelMon></ProviderSpecDeliveryMonitoring>";

							$xml .= "\n</LearningDelivery>";

						}

						foreach($ilr2->subaim as $aim)
						{
							$xml .= "\n<LearningDelivery>";
							$xml .= "\n<LearnAimRef>" . $aim->A09 . "</LearnAimRef>";
							$ProgType = $aim->A15;
							$FworkCode = $aim->A26;
							$LearnAimRef = $aim->A09;
							$found = DAO::getSingleValue($link, "SELECT FRAMEWORK_COMPONENT_TYPE_CODE FROM lad201213.framework_aims WHERE FRAMEWORK_TYPE_CODE= '$ProgType' AND FRAMEWORK_CODE= '$FworkCode'  AND LEARNING_AIM_REF= '$LearnAimRef';");
							if($found=='001' || $found=='003')
								$xml .= "\n<AimType>2</AimType>";
							elseif($aim->A10=='99')
								$xml .= "\n<AimType>4</AimType>";
							else
								$xml .= "\n<AimType>3</AimType>";
							$xml .= "\n<LearnStartDate>" . Date::toMySQL($aim->A27) . "</LearnStartDate>";
							$xml .= "\n<LearnPlanEndDate>" . Date::toMySQL($aim->A28) . "</LearnPlanEndDate>";
							if($aim->A10=='46')
								$xml .= "\n<FundModel>45</FundModel>";
							else
								$xml .= "\n<FundModel>" . $aim->A10 . "</FundModel>";
							$xml .= "\n<GLH>" . $aim->A32 . "</GLH>";
							$xml .= "\n<PlanCredVal>" . $aim->A59 . "</PlanCredVal>";
							$xml .= "\n<ProgType>" . $aim->A15 . "</ProgType>";
							//if($aim->A10!='99')
							$xml .= "\n<FworkCode>" . $aim->A26 . "</FworkCode>";
							if($aim->A15=='2' || $aim->A15=='3' || $aim->A15=='10' || $aim->A15=='20' || $aim->A15=='21')
								$xml .= "\n<PwayCode>0</PwayCode>";
							if($aim->A18!='')
								$xml .= "\n<MainDelMeth>" . $aim->A18 . "</MainDelMeth>";
							else
								$xml .= "\n<MainDelMeth>" . $ilr2->main->A18 . "</MainDelMeth>";
							$xml .= "\n<DelMode>" . $aim->A17 . "</DelMode>";
							$xml .= "\n<PartnerUKPRN>" . $aim->A22 . "</PartnerUKPRN>";
							$xml .= "\n<DelLocPostCode>" . $aim->A23 . "</DelLocPostCode>";
							$xml .= "\n<DistLearnSLN>" . $aim->A52 . "</DistLearnSLN>";
							$xml .= "\n<FeeYTD>" . $aim->A13 . "</FeeYTD>";
							$xml .= "\n<FeeSource>" . $aim->A57 . "</FeeSource>";
							$xml .= "\n<PropFundRemain>" . $aim->A51a . "</PropFundRemain>";
							$xml .= "\n<EmpRole>" . $aim->A19 . "</EmpRole>";
							if($aim->A10=='70')
							{
								$xml .= "\n<ESFProjDosNumber>" . $aim->A61 . "</ESFProjDosNumber>";
								$xml .= "\n<ESFLocProjNumber>" . $aim->A62 . "</ESFLocProjNumber>";
							}
							if($aim->A15=='99')
							{
								$Cont = $ilr2->main->A70;
								$Cont = DAO::getSingleValue($link, "select Cont2012 from central.mapping where UKPRN2011 = '$ukprn' and Cont2011 = '$Cont'");
								//if($Cont=='')
								//	pre("Not found for " . $ukprn);
								$xml .= "\n<ContOrg>" . $Cont . "</ContOrg>";
							}
							if($aim->A68!='' && $aim->A68!='99')
								$xml .= "\n<EmpOutcome>" . $aim->A68 . "</EmpOutcome>";
							if($aim->A34=='1' || $aim->A34=='2' || $aim->A34=='3' || $aim->A34=='6')
								$xml .= "\n<CompStatus>" . $aim->A34 . "</CompStatus>";
							elseif($aim->A34=='4' || $aim->A34=='5')
								$xml .= "\n<CompStatus>3</CompStatus>";
							$xml .= "\n<LearnActEndDate>" . Date::toMySQL($aim->A31) . "</LearnActEndDate>";
							if( ($aim->A34=='3') && ($aim->A50=='1' || $aim->A50=='2' || $aim->A50=='3' || $aim->A50=='7' || $aim->A50=='27' || $aim->A50=='28' || $aim->A50=='29' || $aim->A50=='97' || $aim->A50=='98'))
								$xml .= "\n<WithdrawReason>" . $aim->A50 . "</WithdrawReason>";
							elseif($aim->A34=='4')
								$xml .= "\n<WithdrawReason>40</WithdrawReason>";
							elseif($aim->A34=='5')
								$xml .= "\n<WithdrawReason>1</WithdrawReason>";
							elseif($aim->A34=='3')
								$xml .= "\n<WithdrawReason>97</WithdrawReason>";
							if($aim->A35!='9')
								$xml .= "\n<Outcome>" . $aim->A35 . "</Outcome>";
							$xml .= "\n<AchDate>" . $aim->A40 . "</AchDate>";
							$xml .= "\n<CredAch>" . $aim->A60 . "</CredAch>";
							if($aim->A50=='5')
								$xml .= "\n<ActProgRoute>" . 3 . "</ActProgRoute>";
							elseif($aim->A50=='30')
								$xml .= "\n<ActProgRoute>" . 1 . "</ActProgRoute>";
							elseif($aim->A50=='31')
								$xml .= "\n<ActProgRoute>" . 2 . "</ActProgRoute>";
							elseif($aim->A50=='32')
								$xml .= "\n<ActProgRoute>" . 3 . "</ActProgRoute>";
							elseif($aim->A50=='33')
								$xml .= "\n<ActProgRoute>" . 4 . "</ActProgRoute>";
							elseif($aim->A50=='34')
								$xml .= "\n<ActProgRoute>" . 5 . "</ActProgRoute>";
							elseif($aim->A50=='35')
								$xml .= "\n<ActProgRoute>" . 6 . "</ActProgRoute>";
							if($aim->A15=='99')
							{
								$xml .= "\n<LearningDeliveryFAM><LearnDelFAMType>SOF</LearnDelFAMType><LearnDelFAMCode>" . $ilr2->programmeaim->A11a . "</LearnDelFAMCode></LearningDeliveryFAM>";
								if($aim->A11b!='999')
									$xml .= "\n<LearningDeliveryFAM><LearnDelFAMType>SOF</LearnDelFAMType><LearnDelFAMCode>" . $ilr2->programmeaim->A11b . "</LearnDelFAMCode></LearningDeliveryFAM>";
							}
							if(($ilr2->main->A71=='1' || $ilr2->main->A71=='2') && ($aim->A10!='21' && $aim->A10!='70' && $aim->A10!='10' && $aim->A10!='82' && $aim->A10!='99'))
								$xml .= "\n<LearningDeliveryFAM><LearnDelFAMType>FFI</LearnDelFAMType><LearnDelFAMCode>" . $ilr2->main->A71 . "</LearnDelFAMCode></LearningDeliveryFAM>";
							if($aim->A10!='21' && $aim->A10!='22' && $aim->A10!='70' && $aim->A10!='10' && $aim->A10!='99')
								if($aim->A53=='11')
									$xml .= "\n<LearningDeliveryFAM><LearnDelFAMType>ALN</LearnDelFAMType><LearnDelFAMCode>1</LearnDelFAMCode></LearningDeliveryFAM>";
								elseif($aim->A53=='12')
									$xml .= "\n<LearningDeliveryFAM><LearnDelFAMType>ASN</LearnDelFAMType><LearnDelFAMCode>1</LearnDelFAMCode></LearningDeliveryFAM>";
								elseif($aim->A53=='13')
								{
									$xml .= "\n<LearningDeliveryFAM><LearnDelFAMType>ALN</LearnDelFAMType><LearnDelFAMCode>1</LearnDelFAMCode></LearningDeliveryFAM>";
									$xml .= "\n<LearningDeliveryFAM><LearnDelFAMType>ASN</LearnDelFAMType><LearnDelFAMCode>1</LearnDelFAMCode></LearningDeliveryFAM>";
								}
							if($aim->A58=='1' || $aim->A58=='2' || $aim->A58=='3' || $aim->A58=='4')
								$xml .= "\n<LearningDeliveryFAM><LearnDelFAMType>ASL</LearnDelFAMType><LearnDelFAMCode>" . $aim->A58 . "</LearnDelFAMCode></LearningDeliveryFAM>";
							if($aim->A58=='5')
								$xml .= "\n<LearningDeliveryFAM><LearnDelFAMType>FSI</LearnDelFAMType><LearnDelFAMCode>1</LearnDelFAMCode></LearningDeliveryFAM>";
							if($aim->A20=='1' || $aim->A20=='2')
								$xml .= "\n<LearningDeliveryFAM><LearnDelFAMType>RET</LearnDelFAMType><LearnDelFAMCode>" . $aim->A20 . "</LearnDelFAMCode></LearningDeliveryFAM>";
							if($aim->A63 >= 1 && $aim->A63 <= 17)
								$xml .= "\n<LearningDeliveryFAM><LearnDelFAMType>NSA</LearnDelFAMType><LearnDelFAMCode>" . $aim->A63 . "</LearnDelFAMCode></LearningDeliveryFAM>";
							if($aim->A69 >= 1 && $aim->A69 <= 3)
								$xml .= "\n<LearningDeliveryFAM><LearnDelFAMType>EEF</LearnDelFAMType><LearnDelFAMCode>" . $aim->A69 . "</LearnDelFAMCode></LearningDeliveryFAM>";
							if($aim->A46a!='' && $aim->A46a!='999')
								$xml .= "\n<LearningDeliveryFAM><LearnDelFAMType>LDM</LearnDelFAMType><LearnDelFAMCode>" . $aim->A46a . "</LearnDelFAMCode></LearningDeliveryFAM>";
							if($aim->A46b!='' && $aim->A46b!='999')
								$xml .= "\n<LearningDeliveryFAM><LearnDelFAMType>LDM</LearnDelFAMType><LearnDelFAMCode>" . $aim->A46b . "</LearnDelFAMCode></LearningDeliveryFAM>";
							if($aim->A16=='1' || $aim->A16=='7' || $aim->A16=='8' || $aim->A16=='11' || $aim->A16=='12')
								$xml .= "\n<LearningDeliveryFAM><LearnDelFAMType>RES</LearnDelFAMType><LearnDelFAMCode>1</LearnDelFAMCode></LearningDeliveryFAM>";
							if($aim->A48a!='')
								$xml .= "\n<ProviderSpecDeliveryMonitoring><ProvSpecDelMonOccur>A</ProvSpecDelMonOccur><ProvSpecDelMon>" . $aim->A48a . "</ProvSpecDelMon></ProviderSpecDeliveryMonitoring>";
							if($aim->A48b!='')
								$xml .= "\n<ProviderSpecDeliveryMonitoring><ProvSpecDelMonOccur>B</ProvSpecDelMonOccur><ProvSpecDelMon>" . $aim->A48b . "</ProvSpecDelMon></ProviderSpecDeliveryMonitoring>";
							$xml .= "\n</LearningDelivery>";

						}

						$xml .=  "</Learner>";

						$ilr3 = str_replace("'","&apos;",$xml);
						/*$to_be_migrated = Array('PP55170373TH',	'PP72150082WJ',	'PP55170373OH',	'OP71140025TH',	'PP72150082UJ',	'PP54160209SJ',	'PP82181362SI',	'PP55170373PH',	'OP23190090PJ',	'PP54160209PJ',	'PP82181362VI',	'PP72150082VJ',	'OP71140025QH',	'OP71140025UH',	'PP82181362WI',	'NP32170071TE',	'PP55170373VH',	'OP71140025RH',	'PP72150182VJ',	'OP23190090QJ',	'PP82181362TI',	'PP82181262OI',	'PP55170373WH',	'PP55170273VH',	'QP41130269QG',	'PP42161662NI',	'OP23190090RJ',	'PP82181362SI',	'PP55170373PH',	'PP82181362VI',	'NP32170071TE',	'PP82181262OI',	'OP23190090RJ');
												if(in_array($L03,$to_be_migrated))
													$migrate = 1;
												else
													$migrate = 0; */

						//throw new Exception($migrate);
						if($migrate==1 && $deleted==0)
						{
							$count++;
							$need = DAO::getSingleValue($link, "select count(*) from ilr where l03 = '$L03' and contract_id = '$contract_id' and tr_id = '$tr_id'");
							if($need==0)
							{
								if($is_active=='')
									$is_active=0;
								DAO::execute($link, "INSERT INTO ilr values('$L01', '$L03', '$A09', '$ilr3', 'W01', 'ER', $tr_id, $is_complete, 0, $is_approved, $is_active, $contract_id);");
								DAO::execute($link, "UPDATE tr SET contract_id = $contract_id where id = $tr_id");
							}
						}
					}
				}
			}


			http_redirect($_SESSION['bc']->getPrevious());

		}
		elseif($contract_year==2011)
		{
			$L25 = DAO::getSingleValue($link, "select L25 from contracts where id = $contract_id");
			$contracts = Array();
			//$pageDom = new DomDocument();
			//$pageDom->loadXML($xml);
			$pageDom = XML::loadXmlDom($xml);
			$e = $pageDom->getElementsByTagName('contract');
			$prev = '';
			$count = 0;
			error_reporting(0);
			foreach($e as $node)
			{

				$contract_to_import = $node->nodeValue;

				if($st = $link->query("SELECT L01,L03, A09, ilr, 'W01', contract_type, tr_id, is_complete, is_valid, is_approved, is_active, $contract_id FROM ilr WHERE contract_id = '$contract_to_import' AND submission='W13'"))
				{
					while($row = $st->fetch())
					{


						$migrate = 0;
						$deleted = 0;

						$L01 = $row['L01'];
						$L03 = $row['L03'];
						$A09 = $row['A09'];
						$ilr = $row['ilr'];
						$contract_type = $row['contract_type'];
						$tr_id = $row['tr_id'];
						$is_complete = $row['is_complete'];
						$is_valid = $row['is_valid'];
						$is_approved = $row['is_approved'];
						$is_active = $row['is_active'];

						/*
						try
						{
							$ilr2 = new SimpleXMLElement($ilr);		
						}
						catch(Exception $e)
						{
							pre($ilr);
						}
						*/
						$ilr2 = XML::loadSimpleXML($ilr);

						foreach($ilr2->learner as $learner)
						{
							$learner->L25 = $L25;

							if($learner->L08=='Y')
								$deleted = 1;

							if($learner->L12=='11')
								$learner->L12 = "41";
							elseif($learner->L12=='12')
								$learner->L12 = "39";
							elseif($learner->L12=='13')
								$learner->L12 = "40";
							elseif($learner->L12=='14')
								$learner->L12 = "43";
							elseif($learner->L12=='15')
								$learner->L12 = "44";
							elseif($learner->L12=='16')
								$learner->L12 = "45";
							elseif($learner->L12=='17')
								$learner->L12 = "46";
							elseif($learner->L12=='18')
								$learner->L12 = "42";
							elseif($learner->L12=='19')
								$learner->L12 = "37";
							elseif($learner->L12=='20')
								$learner->L12 = "36";
							elseif($learner->L12=='21')
								$learner->L12 = "35";
							elseif($learner->L12=='22')
								$learner->L12 = "38";
							elseif($learner->L12=='23')
								$learner->L12 = "31";
							elseif($learner->L12=='24')
								$learner->L12 = "32";
							elseif($learner->L12=='25')
								$learner->L12 = "34";

							if($learner->L48!='00000000' && $learner->L48!='' && $learner->L48!='dd/mm/yyyy')
								$learner->L48 = $ilr2->main->A27;



							$learner->addChild('L51','');
							$learner->addChild('L52','');
						}

						foreach($ilr2->programmeaim as $programmeaim)
						{

//							if( (($learner->L28a=='14' && $learner->L28b=='15') || ($learner->L28a=='15' && $learner->L28b=='14')) && ($programmeaim->A15=='2' || $programmeaim->A15=='02' || $programmeaim->A15=='3' || $programmeaim->A15=='03' || $programmeaim->A15=='10'))
//							{
//								$programmeaim->A14 = '28';										
//							}



							if($programmeaim->A14=='01' || $programmeaim->A14=='1')
								$programmeaim->addChild('A71','1');
							else
								$programmeaim->addChild('A71','2');

							$programmeaim->addChild('A72a','');
							$programmeaim->addChild('A72b','');

							if($programmeaim->A16=='04' || $programmeaim->A16=='4' || $programmeaim->A16=='06' || $programmeaim->A16=='6')
								$programmeaim->A16 = '21';
							elseif($programmeaim->A16=='09' || $programmeaim->A16=='9' || $programmeaim->A16=='10')
								$programmeaim->A16 = '16';
							elseif($programmeaim->A16=='13')
								$programmeaim->A16 = '17';
							elseif($programmeaim->A16=='14' || $programmeaim->A16=='15')
								$programmeaim->A16 = '20';

							if($programmeaim->A34=='01' || $programmeaim->A34=='1' || $programmeaim->A34=='02' || $programmeaim->A34=='2' || $programmeaim->A34=='06' || $programmeaim->A34=='6')
								$programmeaim->A50 = '';
							elseif($programmeaim->A34=='04' || $programmeaim->A34=='4')
								$programmeaim->A50 = '40';
						}

						foreach($ilr2->main as $main)
						{


							if($main->A16=='04' || $main->A16=='4' || $main->A16=='06' || $main->A16=='6')
								$main->A16 = '21';
							elseif($main->A16=='09' || $main->A16=='9' || $main->A16=='10')
								$main->A16 = '16';
							elseif($main->A16=='13')
								$main->A16 = '17';
							elseif($main->A16=='14' || $main->A16=='15')
								$main->A16 = '20';

							if($main->A34=='01' || $main->A34=='1' || $main->A34=='02' || $main->A34=='2' || $main->A34=='06' || $main->A34=='6')
								$main->A50 = '';
							elseif($main->A34=='04' || $main->A34=='4')
								$main->A50 = '40';

							if(($ilr2->programmeaim->A15!="99" && $ilr2->programmeaim->A15!=""))
							{
								if($programmeaim->A31=='' || $programmeaim->A31=='dd/mm/yyyy' || $programmeaim->A31=='00000000')
								{
									$migrate = 1;
								}
								elseif( ($programmeaim->A40=='' || $programmeaim->A40=='dd/mm/yyyy' || $programmeaim->A40=='00000000') && ($programmeaim->A35=='4' || $programmeaim->A35=='5'))
								{
									$migrate = 1;
								}


								if($programmeaim->A28!='00000000')
								{
									$A28 = new Date($programmeaim->A28);
									$d = new Date('01/08/2008');
									if($A28->getDate()<$d->getDate())
										$migrate = 0;
								}
								if($programmeaim->A31!='' && $programmeaim->A31!='dd/mm/yyyy' && $programmeaim->A31!='00000000')
								{
									$A31 = new Date($programmeaim->A31);
									$d = new Date('01/08/2011');
									if($A31->getDate()>=$d->getDate())
										$migrate = 1;
								}

							}
							else
							{
								if($main->A31=='' || $main->A31=='dd/mm/yyyy' || $main->A31=='00000000')
								{
									$migrate = 1;
								}
								elseif( ($main->A40=='' || $main->A40=='dd/mm/yyyy' || $main->A40=='00000000') && ($main->A35=='4' || $main->A35=='5'))
								{
									$migrate = 1;
								}

								$A28 = new Date($main->A28);
								$d = new Date('01/08/2008');
								if($A28->getDate()<$d->getDate())
									$migrate = 0;

								if($main->A31!='' && $main->A31!='dd/mm/yyyy' && $main->A31!='00000000')
								{
									$A31 = new Date($main->A31);
									$d = new Date('01/08/2011');
									if($A31->getDate()>=$d->getDate())
										$migrate = 1;
								}
							}


//							if( (($learner->L28a=='14' && $learner->L28b=='15') || ($learner->L28a=='15' && $learner->L28b=='14')) && ($main->A15=='2' || $main->A15=='02' || $main->A15=='3' || $main->A15=='03' || $main->A15=='10'))
//							{
//								$main->A14 = '28';										
//							}

							if($main->A14=='01' || $main->A14=='1')
								$main->addChild('A71','1');
							else
								$main->addChild('A71','2');

							$main->addChild('A72a','');
							$main->addChild('A72b','');

						}


						foreach($ilr2->subaim as $subaim)
						{

							if($subaim->A16=='04' || $subaim->A16=='4' || $subaim->A16=='06' || $subaim->A16=='6')
								$subaim->A16 = '21';
							elseif($subaim->A16=='09' || $subaim->A16=='9' || $subaim->A16=='10')
								$subaim->A16 = '16';
							elseif($subaim->A16=='13')
								$subaim->A16 = '17';
							elseif($subaim->A16=='14' || $subaim->A16=='15')
								$subaim->A16 = '20';

							if($subaim->A34=='01' || $subaim->A34=='1' || $subaim->A34=='02' || $subaim->A34=='2' || $subaim->A34=='06' || $subaim->A34=='6')
								$subaim->A50 = '';
							elseif($subaim->A34=='04' || $subaim->A34=='4')
								$subaim->A50 = '40';

							if($subaim->A31=='' || $subaim->A31=='dd/mm/yyyy' || $subaim->A31=='00000000')
							{
								$migrate = 1;
							}
							elseif( ($subaim->A40=='' || $subaim->A40=='dd/mm/yyyy' || $subaim->A40=='00000000') && ($subaim->A35=='4' || $subaim->A35=='5'))
							{
								$migrate = 1;
							}


							$A28 = new Date($subaim->A28);
							$d = new Date('01/08/2008');
							if($A28->getDate()<$d->getDate())
								$migrate = 0;

							$subaim->addChild('A71','');
							if($subaim->A14=='01' || $subaim->A14=='1')
								$subaim->addChild('A71','1');
							else
								$subaim->addChild('A71','2');

							$subaim->addChild('A72a','');
							$subaim->addChild('A72b','');
						}

						$ilr3 = substr($ilr2->asXML(),22);
						$ilr3 = str_replace("'","&nbsp;",$ilr3);
						if($migrate==1 && $deleted==0)
						{
							$count++;
							$need = DAO::getSingleValue($link, "select count(*) from ilr where l03 = '$L03' and contract_id = '$contract_id' and tr_id = '$tr_id'");
							if($need==0)
							{
								DAO::execute($link, "INSERT INTO ilr values('$L01', '$L03', '$A09', '$ilr3', 'W01', 'ER', $tr_id, $is_complete, 0, $is_approved, $is_active, $contract_id);");
							}
						}
					}
				}
			}

			DAO::execute($link, "UPDATE tr SET contract_id = (SELECT contract_id FROM ilr WHERE tr_id = tr.id ORDER BY contract_id DESC LIMIT 0,1);");

			http_redirect($_SESSION['bc']->getPrevious());

		}
		elseif($contract_year==2010)
		{
			$L25 = DAO::getSingleValue($link, "select L25 from contracts where id = $contract_id");
			$contracts = Array();
			//$pageDom = new DomDocument();
			//$pageDom->loadXML($xml);
			$pageDom = XML::loadXmlDom($xml);
			$e = $pageDom->getElementsByTagName('contract');
			$prev = '';
			$count = 0;
			error_reporting(0);
			foreach($e as $node)
			{

				$contract_to_import = $node->nodeValue;

				if($st = $link->query("SELECT L01,L03, A09, ilr, 'W01', contract_type, tr_id, is_complete, is_valid, is_approved, is_active, $contract_id FROM ilr WHERE contract_id = '$contract_to_import' AND submission='W13'"))
				{
					while($row = $st->fetch())
					{


						$migrate = 0;
						$deleted = 0;

						$L01 = $row['L01'];
						$L03 = $row['L03'];
						$A09 = $row['A09'];
						$ilr = $row['ilr'];
						$contract_type = $row['contract_type'];
						$tr_id = $row['tr_id'];
						$is_complete = $row['is_complete'];
						$is_valid = $row['is_valid'];
						$is_approved = $row['is_approved'];
						$is_active = $row['is_active'];

						/*
						try
						{
							$ilr2 = new SimpleXMLElement($ilr);		
						}
						catch(Exception $e)
						{
							pre($ilr);
						}
						*/
						$ilr2 = XML::loadSimpleXML($ilr);

						foreach($ilr2->learner as $learner)
						{
							$learner->L25 = $L25;

							if($learner->L08=='Y')
								$deleted = 1;
						}

						foreach($ilr2->programmeaim as $programmeaim)
						{


							if($programmeaim->A02=='99')
								$programmeaim->A02='00';


							if($programmeaim->A10=='45' || $programmeaim->A10=='46' || $programmeaim->A10=='70' || $programmeaim->A10=='80')
							{
								$programmeaim->A11a = '105';
								$programmeaim->A11b = '999';
							}
							elseif($programmeaim->A10=='99')
							{
								$programmeaim->A11a = '105';
								$programmeaim->A11b = '999';
							}

							if( (($learner->L28a=='14' && $learner->L28b=='15') || ($learner->L28a=='15' && $learner->L28b=='14')) && ($programmeaim->A15=='2' || $programmeaim->A15=='02' || $programmeaim->A15=='3' || $programmeaim->A15=='03' || $programmeaim->A15=='10'))
							{
								$programmeaim->A14 = '28';
							}

							if( (($learner->L28a=='15' && $learner->L28b=='99') || ($learner->L28a=='99' && $learner->L28b=='15')) && ($programmeaim->A15=='2' || $programmeaim->A15=='02' || $programmeaim->A15=='3' || $programmeaim->A15=='03' || $programmeaim->A15=='10'))
							{
								$programmeaim->A14 = '1';
							}

//							if($programmeaim->A18=='22' || $programmeaim->A18=='23')
							{
								$programmeaim->A18 = '24';
							}

							if($programmeaim->A22=='' || $programmeaim->A22=='        ')
							{
								$programmeaim->A22 = '00000000';
							}

							if($programmeaim->A44=='' || $programmeaim->A44=='                              ')
							{
								$programmeaim->A44 = '000000000';
							}

							if( ($programmeaim->A10=='45') && ($programmeaim->A15=='99') && ($programmeaim->A46a=='83' || $programmeaim->A46b=='83'))
							{
								$programmeaim->A44 = '888888880';
							}



							if($programmeaim->A04!='30')
								$programmeaim->addChild('A69','00');
							else
								if($programmeaim->A10=='99')
									$programmeaim->addChild('A69','99');
								else
									if($programmeaim->A15!='2' && $programmeaim->A15!='3' && $programmeaim->A15!='10')
									{
										$programmeaim->addChild('A69','99');
									}
									else
										if( ($learner->L28a=='14' && $learner->L28b=='99') || ($learner->L28a=='99' && $learner->L28b=='14'))
											$programmeaim->addChild('A69','99');
										else
											if( ($learner->L28a=='14' && $learner->L28b=='15') || ($learner->L28a=='15' && $learner->L28b=='14'))
											{
												$programmeaim->addChild('A69','1');
												$programmeaim->A14 = '28';
											}
											else
												if( ($learner->L28a=='15' && $learner->L28b=='99') || ($learner->L28a=='99' && $learner->L28b=='15'))
												{
													$programmeaim->addChild('A69','2');
													$programmeaim->A14 = '1';
												}
												else
													$programmeaim->addChild('A69','99');

							if($L01=='118790')
								$programmeaim->addChild('A70','SFEE');
							elseif($L01=='108459')
								$programmeaim->addChild('A70','SFSE');
							elseif($L01=='118047' || $L01=='116503' || $L01=='108458')
								$programmeaim->addChild('A70','SFNE');
							elseif($L01=='117358' || $L01 == '117954')
								$programmeaim->addChild('A70','SFNW');
							elseif($L01=='118469')
								$programmeaim->addChild('A70','SFNE');
							elseif($L01=='105060')
								$programmeaim->addChild('A70','SFWM');
							elseif($L01=='106689' || $L01=='106007')
								$programmeaim->addChild('A70','SFYH');
							else
								if($programmeaim->A10=='80' && ($programmeaim->A46a=='999' && $programmeaim->A46b=='999') && $programmeaim->A49=='')
									$programmeaim->addChild('A70','');

						}

						foreach($ilr2->main as $main)
						{

							if(($ilr2->programmeaim->A15!="99" && $ilr2->programmeaim->A15!=""))
							{
								if($programmeaim->A31=='' || $programmeaim->A31=='dd/mm/yyyy' || $programmeaim->A31=='00000000')
								{
									$migrate = 1;
								}
								elseif( ($programmeaim->A40=='' || $programmeaim->A40=='dd/mm/yyyy' || $programmeaim->A40=='00000000') && ($programmeaim->A35=='4' || $programmeaim->A35=='5'))
								{
									$migrate = 1;
								}

								if($programmeaim->A28!='00000000')
								{
									$A28 = new Date($programmeaim->A28);
									$d = new Date('01/08/2007');
									if($A28->getDate()<$d->getDate())
										$migrate = 0;
								}

								if($programmeaim->A31!='' && $programmeaim->A31!='dd/mm/yyyy' && $programmeaim->A31!='00000000')
								{
									$A31 = new Date($programmeaim->A31);
									$d = new Date('01/08/2010');
									if($A31->getDate()>=$d->getDate())
										$migrate = 1;
								}
							}
							else
							{
								if($main->A31=='' || $main->A31=='dd/mm/yyyy' || $main->A31=='00000000')
								{
									$migrate = 1;
								}
								elseif( ($main->A40=='' || $main->A40=='dd/mm/yyyy' || $main->A40=='00000000') && ($main->A35=='4' || $main->A35=='5'))
								{
									$migrate = 1;
								}

								$A28 = new Date($main->A28);
								$d = new Date('01/08/2007');
								if($A28->getDate()<$d->getDate())
									$migrate = 0;

								if($main->A31!='' && $main->A31!='dd/mm/yyyy' && $main->A31!='00000000')
								{
									$A31 = new Date($main->A31);
									$d = new Date('01/08/2010');
									if($A31->getDate()>=$d->getDate())
										$migrate = 1;
								}
							}


							if($main->A02=='99')
								$main->A02='00';

							if($main->A10=='45' || $main->A10=='46' || $main->A10=='70' || $main->A10=='80')
							{
								$main->A11a = '105';
								$main->A11b = '999';
							}
							elseif($main->A10=='99')
							{
								$main->A11a = '105';
								$main->A11b = '999';
							}

							if( (($learner->L28a=='14' && $learner->L28b=='15') || ($learner->L28a=='15' && $learner->L28b=='14')) && ($main->A15=='2' || $main->A15=='02' || $main->A15=='3' || $main->A15=='03' || $main->A15=='10'))
							{
								$main->A14 = '28';
							}

							if( (($learner->L28a=='15' && $learner->L28b=='99') || ($learner->L28a=='99' && $learner->L28b=='15')) && ($main->A15=='2' || $main->A15=='02' || $main->A15=='3' || $main->A15=='03' || $main->A15=='10'))
							{
								$main->A14 = '1';
							}

//							if($main->A18=='22' || $main->A18=='23')
							{
								$main->A18 = '24';
							}

							if($main->A22=='' || $main->A22=='        ')
							{
								$main->A22 = '00000000';
							}

							if($main->A44=='' || $main->A44=='                              ')
							{
								$main->A44 = '000000000';
							}

							if( ($main->A10=='45') && ($main->A15=='99') && ($main->A46a=='83' || $main->A46b=='83'))
							{
								$main->A44 = '888888880';
							}

							if($main->A04!='30')
								$main->addChild('A69','00');
							else
								if($main->A10=='99')
									$main->addChild('A69','99');
								else
									if($main->A15!='2' && $main->A15!='3' && $main->A15!='10')
										$main->addChild('A69','99');
									else
										if( ($learner->L28a=='14' && $learner->L28b=='99') || ($learner->L28a=='99' && $learner->L28b=='14'))
											$main->addChild('A69','99');
										else
											if( ($learner->L28a=='14' && $learner->L28b=='15') || ($learner->L28a=='15' && $learner->L28b=='14'))
											{
												$main->addChild('A69','1');
												$main->A14 = '28';
											}
											else
												if( ($learner->L28a=='15' && $learner->L28b=='99') || ($learner->L28a=='99' && $learner->L28b=='15'))
												{
													$main->addChild('A69','2');
													$main->A14 = '1';
												}
												else
													$main->addChild('A69','99');

							if($L01=='118790')
								$main->addChild('A70','SFEE');
							elseif($L01=='108459')
								$main->addChild('A70','SFSE');
							elseif($L01=='118047' || $L01=='116503' || $L01=='108458')
								$main->addChild('A70','SFNE');
							elseif($L01=='117358' || $L01 == '117954')
								$main->addChild('A70','SFNW');
							elseif($L01=='118469')
								$main->addChild('A70','SFNE');
							elseif($L01=='105060')
								$main->addChild('A70','SFWM');
							elseif($L01=='106689' || $L01=='106007')
								$main->addChild('A70','SFYH');
							else
								if($main->A10=='80' && ($main->A46a=='999' && $main->A46b=='999') && $main->A49=='')
									$main->addChild('A70','');
						}

						foreach($ilr2->subaim as $subaim)
						{
							if($subaim->A31=='' || $subaim->A31=='dd/mm/yyyy' || $subaim->A31=='00000000')
							{
								$migrate = 1;
							}
							elseif( ($subaim->A40=='' || $subaim->A40=='dd/mm/yyyy' || $subaim->A40=='00000000') && ($subaim->A35=='4' || $subaim->A35=='5'))
							{
								$migrate = 1;
							}


							$A28 = new Date($subaim->A28);
							$d = new Date('01/08/2007');
							if($A28->getDate()<$d->getDate())
								$migrate = 0;

							if($subaim->A02=='99')
								$subaim->A02='00';

							if($subaim->A10=='45' || $subaim->A10=='46' || $subaim->A10=='70' || $subaim->A10=='80')
							{
								$subaim->A11a = '105';
								$subaim->A11b = '999';
							}
							elseif($subaim->A10=='99')
							{
								$subaim->A11a = '105';
								$subaim->A11b = '999';
							}

							if( (($learner->L28a=='14' && $learner->L28b=='15') || ($learner->L28a=='15' && $learner->L28b=='14')) && ($subaim->A15=='2' || $subaim->A15=='02' || $subaim->A15=='3' || $subaim->A15=='03' || $subaim->A15=='10'))
							{
								$subaim->A14 = '28';
							}

							if( (($learner->L28a=='15' && $learner->L28b=='99') || ($learner->L28a=='99' && $learner->L28b=='15')) && ($subaim->A15=='2' || $subaim->A15=='02' || $subaim->A15=='3' || $subaim->A15=='03' || $subaim->A15=='10'))
							{
								$subaim->A14 = '1';
							}

							//	if($subaim->A18=='22' || $subaim->A18=='23')
							{
								$subaim->A18 = '24';
							}

							if($subaim->A22=='' || $subaim->A22=='        ')
							{
								$subaim->A22 = '00000000';
							}

							if($subaim->A44=='' || $subaim->A44=='                              ')
							{
								$subaim->A44 = '000000000';
							}

							if( ($subaim->A10=='45') && ($subaim->A15=='99') && ($subaim->A46a==83 || $subaim->A46b==83))
							{
								$subaim->A44 = '888888880';
							}

							if($subaim->A04!='30')
								$subaim->addChild('A69','00');
							else
								if($subaim->A10=='99')
									$subaim->addChild('A69','99');
								else
									if($subaim->A15!='2' && $subaim->A15!='3' && $subaim->A15!='10')
										$subaim->addChild('A69','99');
									else
										if( ($learner->L28a=='14' && $learner->L28b=='99') || ($learner->L28a=='99' && $learner->L28b=='14'))
											$subaim->addChild('A69','99');
										else
											if( ($learner->L28a=='14' && $learner->L28b=='15') || ($learner->L28a=='15' && $learner->L28b=='14'))
											{
												$subaim->addChild('A69','1');
												$subaim->A14 = '28';
											}
											else
												if( ($learner->L28a=='15' && $learner->L28b=='99') || ($learner->L28a=='99' && $learner->L28b=='15'))
												{
													$subaim->addChild('A69','2');
													$subaim->A14 = '1';
												}
												else
													$subaim->addChild('A69','99');


							if($L01=='118790')
								$subaim->addChild('A70','SFEE');
							elseif($L01=='108459')
								$subaim->addChild('A70','SFSE');
							elseif($L01=='118047' || $L01=='116503' || $L01=='108458')
								$subaim->addChild('A70','SFNE');
							elseif($L01=='117358' || $L01 == '117954')
								$subaim->addChild('A70','SFNW');
							elseif($L01=='118469')
								$subaim->addChild('A70','SFNE');
							elseif($L01=='105060')
								$subaim->addChild('A70','SFWM');
							elseif($L01=='106689' || $L01=='106007')
								$subaim->addChild('A70','SFYH');
							else
								if($subaim->A10=='80' && ($subaim->A46a=='999' && $subaim->A46b=='999') && $subaim->A49=='')
									$subaim->addChild('A70','');

						}

						$ilr3 = substr($ilr2->asXML(),22);
						$ilr3 = str_replace("'","&nbsp;",$ilr3);
						if($migrate==1 && $deleted==0)
						{
							$count++;
							DAO::execute($link, "INSERT INTO ilr values('$L01', '$L03', '$A09', '$ilr3', 'W01', 'ER', $tr_id, $is_complete, 0, $is_approved, $is_active, $contract_id);");
						}
					}
				}
			}

			DAO::execute($link, "UPDATE tr SET contract_id = (SELECT contract_id FROM ilr WHERE tr_id = tr.id ORDER BY contract_id DESC LIMIT 0,1);");

			http_redirect($_SESSION['bc']->getPrevious());

		}
		elseif($contract_year==2009)
		{
			$L25 = DAO::getSingleValue($link, "select L25 from contracts where id = $contract_id");
			$contracts = Array();
			//$pageDom = new DomDocument();
			//$pageDom->loadXML($xml);
			$pageDom = XML::loadXmlDom($xml);
			$e = $pageDom->getElementsByTagName('contract');
			$prev = '';

			foreach($e as $node)
			{

				$contract_to_import = $node->nodeValue;

				if($st = $link->query("SELECT L01,L03, A09, ilr, 'W01', contract_type, tr_id, is_complete, is_valid, is_approved, is_active, $contract_id FROM ilr WHERE contract_id = '$contract_to_import' AND submission='W11'"))
				{
					while($row = $st->fetch())
					{

						$migrate = 0;
						$deleted = 0;

						$L01 = $row['L01'];
						$L03 = $row['L03'];
						$A09 = $row['A09'];
						$ilr = $row['ilr'];
						$contract_type = $row['contract_type'];
						$tr_id = $row['tr_id'];
						$is_complete = $row['is_complete'];
						$is_valid = $row['is_valid'];
						$is_approved = $row['is_approved'];
						$is_active = $row['is_active'];

						//$ilr2 = new SimpleXMLElement($ilr);
						$ilr2 = XML::loadSimpleXML($ilr);

						foreach($ilr2->learner as $learner)
						{
							$learner->L25 = $L25;
							if($learner->L39=='93' || $learner->L39=='94')
								$learner->L39='98';

							$learner->L36 = '0';
							if($learner->L08=='Y')
								$deleted = 1;


						}

						foreach($ilr2->programmeaim as $programmeaim)
						{

							if($programmeaim->A31=='' || $programmeaim->A31=='dd/mm/yyyy' || $programmeaim->A31=='00000000')
								$migrate = 1;
							else
							{
								$A31	= new Date($programmeaim->A31);
								$d 		= new Date('31/07/2009');
								if($A31->getDate()>$d->getDate())
									$migrate = 1;
								else
									$migrate = 0;
							}


							if($programmeaim->A02=='00')
								$programmeaim->A02='99';
							if($programmeaim->A10=='80')
								throw new Exception("A10 is 80");
							if(($programmeaim->A10=='45' || $programmeaim->A10=='46') && ($programmeaim->A14=='10' || $programmeaim->A14=='99'))
								$programmeaim->A14 = '32';
							$programmeaim->A21='00';
							$programmeaim->A22='        ';
							$programmeaim->A32='00000';
							if($programmeaim->A46a=='046' || $programmeaim->A46a=='047' || $programmeaim->A46a=='048' || $programmeaim->A46a=='048' || $programmeaim->A46a=='049' || $programmeaim->A46a=='050' || $programmeaim->A46a=='051' || $programmeaim->A46a=='052' || $programmeaim->A46a=='053' || $programmeaim->A46a=='054' || $programmeaim->A46a=='055' || $programmeaim->A46a=='056' || $programmeaim->A46a=='057' || $programmeaim->A46a=='058' || $programmeaim->A46a=='059' || $programmeaim->A46a=='060')
								$programmeaim->A46a = '999';
							if($programmeaim->A46b=='046' || $programmeaim->A46b=='047' || $programmeaim->A46b=='048' || $programmeaim->A46b=='048' || $programmeaim->A46b=='049' || $programmeaim->A46b=='050' || $programmeaim->A46b=='051' || $programmeaim->A46b=='052' || $programmeaim->A46b=='053' || $programmeaim->A46b=='054' || $programmeaim->A46b=='055' || $programmeaim->A46b=='056' || $programmeaim->A46b=='057' || $programmeaim->A46b=='058' || $programmeaim->A46b=='059' || $programmeaim->A46b=='060')
								$programmeaim->A46b = '999';
							//						if(( ($programmeaim->A50=='00' || $programmeaim->A50=='') && ($programmeaim->A31=='' || $programmeaim->A31=='00000000' || $programmeaim->A31=='dd/mm/yyyy'))
							//							$programmeaim->A50 = '96';
							//						if(( ($programmeaim->A50=='00' || $programmeaim->A50=='') && ($programmeaim->A31!='' && $programmeaim->A31!='00000000' && $programmeaim->A31!='dd/mm/yyyy'))
							//							$programmeaim->A50 = '98';
							//						if($programmeaim->A51a=='00' || $programmeaim->A51a='')
							//							$programmeaim->A51a='100';
							$programmeaim->addChild('A61','         ');
							$programmeaim->addChild('A62','000');

							if($programmeaim->A04=='35')
								$programmeaim->addChild('A63','00');
							else
								if($programmeaim->A04=='30' && ($programmeaim->A46a=='046' || $programmeaim->A46b=='046'))
									$programmeaim->addChild('A63','01');
								else
									if($programmeaim->A04=='30' && ($programmeaim->A46a=='047' || $programmeaim->A46b=='047'))
										$programmeaim->addChild('A63','02');
									else
										if($programmeaim->A04=='30' && ($programmeaim->A46a=='048' || $programmeaim->A46b=='048'))
											$programmeaim->addChild('A63','03');
										else
											if($programmeaim->A04=='30' && ($programmeaim->A46a=='049' || $programmeaim->A46b=='049'))
												$programmeaim->addChild('A63','04');
											else
												if($programmeaim->A04=='30' && ($programmeaim->A46a=='050' || $programmeaim->A46b=='050'))
													$programmeaim->addChild('A63','05');
												else
													if($programmeaim->A04=='30' && ($programmeaim->A46a=='051' || $programmeaim->A46b=='051'))
														$programmeaim->addChild('A63','06');
													else
														if($programmeaim->A04=='30' && ($programmeaim->A46a=='052' || $programmeaim->A46b=='052'))
															$programmeaim->addChild('A63','07');
														else
															if($programmeaim->A04=='30' && ($programmeaim->A46a=='053' || $programmeaim->A46b=='053'))
																$programmeaim->addChild('A63','08');
															else
																if($programmeaim->A04=='30' && ($programmeaim->A46a=='054' || $programmeaim->A46b=='054'))
																	$programmeaim->addChild('A63','09');
																else
																	if($programmeaim->A04=='30' && ($programmeaim->A46a=='055' || $programmeaim->A46b=='055'))
																		$programmeaim->addChild('A63','10');
																	else
																		if($programmeaim->A04=='30' && ($programmeaim->A46a=='056' || $programmeaim->A46b=='056'))
																			$programmeaim->addChild('A63','11');
																		else
																			if($programmeaim->A04=='30' && ($programmeaim->A46a=='057' || $programmeaim->A46b=='057'))
																				$programmeaim->addChild('A63','12');
																			else
																				if($programmeaim->A04=='30' && ($programmeaim->A46a=='058' || $programmeaim->A46b=='058'))
																					$programmeaim->addChild('A63','13');
																				else
																					$programmeaim->addChild('A63','99');

						}

						foreach($ilr2->main as $main)
						{

							if($main->A31=='' || $main->A31=='dd/mm/yyyy' || $main->A31=='00000000')
								$migrate = 1;
							else
							{
								$A31	= new Date($main->A31);
								$d 		= new Date('31/07/2009');
								if($A31->getDate()>$d->getDate())
									$migrate = 1;
								else
									$migrate = 0;
							}

							if($main->A02=='00')
								$main->A02='99';
							if($main->A10=='80')
								throw new Exception("A10 is 80");
							if(($main->A10=='45' || $main->A10=='46') && ($main->A14=='10' || $main->A14=='99'))
								$main->A14 = '32';
							$main->A21='00';
							$main->A22='        ';
							$main->A32='00000';
							if($main->A46a=='046' || $main->A46a=='047' || $main->A46a=='048' || $main->A46a=='048' || $main->A46a=='049' || $main->A46a=='050' || $main->A46a=='051' || $main->A46a=='052' || $main->A46a=='053' || $main->A46a=='054' || $main->A46a=='055' || $main->A46a=='056' || $main->A46a=='057' || $main->A46a=='058' || $main->A46a=='059' || $main->A46a=='060')
								$main->A46a = '999';
							if($main->A46b=='046' || $main->A46b=='047' || $main->A46b=='048' || $main->A46b=='048' || $main->A46b=='049' || $main->A46b=='050' || $main->A46b=='051' || $main->A46b=='052' || $main->A46b=='053' || $main->A46b=='054' || $main->A46b=='055' || $main->A46b=='056' || $main->A46b=='057' || $main->A46b=='058' || $main->A46b=='059' || $main->A46b=='060')
								$main->A46b = '999';
							//						if(( ($main->A50=='00' || $main->A50=='') && ($main->A31=='' || $main->A31=='00000000' || $main->A31=='dd/mm/yyyy'))
							//							$main->A50 = '96';
							//						if(( ($main->A50=='00' || $main->A50=='') && ($main->A31!='' && $main->A31!='00000000' && $main->A31!='dd/mm/yyyy'))
							//							$main->A50 = '98';
							if($main->A51a=='00' || $main->A51a='' || $main->A51a='0')
								$main->A51a='100';
							$main->addChild('A61','         ');
							$main->addChild('A62','000');

							if($main->A04=='35')
								$main->addChild('A63','00');
							else
								if($main->A04=='30' && ($main->A46a=='046' || $main->A46b=='046'))
									$main->addChild('A63','01');
								else
									if($main->A04=='30' && ($main->A46a=='047' || $main->A46b=='047'))
										$main->addChild('A63','02');
									else
										if($main->A04=='30' && ($main->A46a=='048' || $main->A46b=='048'))
											$main->addChild('A63','03');
										else
											if($main->A04=='30' && ($main->A46a=='049' || $main->A46b=='049'))
												$main->addChild('A63','04');
											else
												if($main->A04=='30' && ($main->A46a=='050' || $main->A46b=='050'))
													$main->addChild('A63','05');
												else
													if($main->A04=='30' && ($main->A46a=='051' || $main->A46b=='051'))
														$main->addChild('A63','06');
													else
														if($main->A04=='30' && ($main->A46a=='052' || $main->A46b=='052'))
															$main->addChild('A63','07');
														else
															if($main->A04=='30' && ($main->A46a=='053' || $main->A46b=='053'))
																$main->addChild('A63','08');
															else
																if($main->A04=='30' && ($main->A46a=='054' || $main->A46b=='054'))
																	$main->addChild('A63','09');
																else
																	if($main->A04=='30' && ($main->A46a=='055' || $main->A46b=='055'))
																		$main->addChild('A63','10');
																	else
																		if($main->A04=='30' && ($main->A46a=='056' || $main->A46b=='056'))
																			$main->addChild('A63','11');
																		else
																			if($main->A04=='30' && ($main->A46a=='057' || $main->A46b=='057'))
																				$main->addChild('A63','12');
																			else
																				if($main->A04=='30' && ($main->A46a=='058' || $main->A46b=='058'))
																					$main->addChild('A63','13');
																				else
																					$main->addChild('A63','99');
						}

						foreach($ilr2->subaim as $subaim)
						{
							if($subaim->A31=='' || $subaim->A31=='dd/mm/yyyy' || $subaim->A31=='00000000')
								$migrate = 1;
							else
							{
								$A31	= new Date($subaim->A31);
								$d 		= new Date('31/07/2009');
								if($A31->getDate()>$d->getDate())
									$migrate = 1;
								else
									$migrate = 0;
							}

							if($subaim->A02=='00')
								$subaim->A02='99';
							if($subaim->A10=='80')
								throw new Exception("A10 is 80");
							if(($subaim->A10=='45' || $subaim->A10=='46') && ($subaim->A14=='10' || $subaim->A14=='99'))
								$subaim->A14 = '32';
							$subaim->A21='00';
							$subaim->A22='        ';
							$subaim->A32='00000';
							if($subaim->A46a=='046' || $subaim->A46a=='047' || $subaim->A46a=='048' || $subaim->A46a=='048' || $subaim->A46a=='049' || $subaim->A46a=='050' || $subaim->A46a=='051' || $subaim->A46a=='052' || $subaim->A46a=='053' || $subaim->A46a=='054' || $subaim->A46a=='055' || $subaim->A46a=='056' || $subaim->A46a=='057' || $subaim->A46a=='058' || $subaim->A46a=='059' || $subaim->A46a=='060')
								$subaim->A46a = '999';
							if($subaim->A46b=='046' || $subaim->A46b=='047' || $subaim->A46b=='048' || $subaim->A46b=='048' || $subaim->A46b=='049' || $subaim->A46b=='050' || $subaim->A46b=='051' || $subaim->A46b=='052' || $subaim->A46b=='053' || $subaim->A46b=='054' || $subaim->A46b=='055' || $subaim->A46b=='056' || $subaim->A46b=='057' || $subaim->A46b=='058' || $subaim->A46b=='059' || $subaim->A46b=='060')
								$subaim->A46b = '999';
							//						if(( ($subaim->A50=='00' || $subaim->A50=='') && ($subaim->A31=='' || $subaim->A31=='00000000' || $subaim->A31=='dd/mm/yyyy'))
							//							$subaim->A50 = '96';
							//						if(( ($subaim->A50=='00' || $subaim->A50=='') && ($subaim->A31!='' && $subaim->A31!='00000000' && $subaim->A31!='dd/mm/yyyy'))
							//							$subaim->A50 = '98';
							if($subaim->A51a=='00' || $subaim->A51a='' || $subaim->A51a='0')
								$subaim->A51a='100';
							$subaim->addChild('A61','         ');
							$subaim->addChild('A62','000');

							if($subaim->A04=='35')
								$subaim->addChild('A63','00');
							else
								if($subaim->A04=='30' && ($subaim->A46a=='046' || $subaim->A46b=='046'))
									$subaim->addChild('A63','01');
								else
									if($subaim->A04=='30' && ($subaim->A46a=='047' || $subaim->A46b=='047'))
										$subaim->addChild('A63','02');
									else
										if($subaim->A04=='30' && ($subaim->A46a=='048' || $subaim->A46b=='048'))
											$subaim->addChild('A63','03');
										else
											if($subaim->A04=='30' && ($subaim->A46a=='049' || $subaim->A46b=='049'))
												$subaim->addChild('A63','04');
											else
												if($subaim->A04=='30' && ($subaim->A46a=='050' || $subaim->A46b=='050'))
													$subaim->addChild('A63','05');
												else
													if($subaim->A04=='30' && ($subaim->A46a=='051' || $subaim->A46b=='051'))
														$subaim->addChild('A63','06');
													else
														if($subaim->A04=='30' && ($subaim->A46a=='052' || $subaim->A46b=='052'))
															$subaim->addChild('A63','07');
														else
															if($subaim->A04=='30' && ($subaim->A46a=='053' || $subaim->A46b=='053'))
																$subaim->addChild('A63','08');
															else
																if($subaim->A04=='30' && ($subaim->A46a=='054' || $subaim->A46b=='054'))
																	$subaim->addChild('A63','09');
																else
																	if($subaim->A04=='30' && ($subaim->A46a=='055' || $subaim->A46b=='055'))
																		$subaim->addChild('A63','10');
																	else
																		if($subaim->A04=='30' && ($subaim->A46a=='056' || $subaim->A46b=='056'))
																			$subaim->addChild('A63','11');
																		else
																			if($subaim->A04=='30' && ($subaim->A46a=='057' || $subaim->A46b=='057'))
																				$subaim->addChild('A63','12');
																			else
																				if($subaim->A04=='30' && ($subaim->A46a=='058' || $subaim->A46b=='058'))
																					$subaim->addChild('A63','13');
																				else
																					$subaim->addChild('A63','99');
						}

						$ilr3 = substr($ilr2->asXML(),22);
						if($migrate==1 && $deleted==0)
						{
							DAO::execute($link, "INSERT INTO ilr values('$L01', '$L03', '$A09', '$ilr3', 'W01', 'ER', $tr_id, $is_complete, 0, $is_approved, $is_active, $contract_id);");
							DAO::execute($link, "update tr set contract_id = '$contract_id' where id = '$tr_id'");
						}
					}
				}
			}


			http_redirect($_SESSION['bc']->getPrevious());
		}
	}
}

?>