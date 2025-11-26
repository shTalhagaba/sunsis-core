<?php

class Ilr2013 extends Entity
{
	public function __construct()
	{

	}

	public static function FundingType($xml)
	{
		$xpath = $xml->xpath("/Learner/LearningDelivery/FundModel");
		$funding_model = (!isset($xpath[0]))?'':$xpath[0];

		$xpath = $xml->xpath("/Learner/LearningDelivery/ProgType");
		$prog_type = (!isset($xpath[0]))?'':$xpath[0];

		if($funding_model=='10')
			$funding_type = "Community";
		elseif($funding_model=='25')
			$funding_type = "1619EFA";
		elseif($funding_model=='35')
			$funding_type = "SFA";
		elseif($funding_model=='70')
			$funding_type = "ESF";
		elseif($funding_model=='81')
			$funding_type = "OTHERSFA";
		elseif($funding_model=='82')
			$funding_type = "OTHEREFA";
		elseif($funding_model=='99')
			$funding_type = "NOFUNDING";
		else
			$funding_type = $funding_model;

		return $funding_type;
	}


	public static function loadFromDatabase(PDO $link, $submission, $contract_id, $tr_id, $L03)
	{
		if(is_null($submission) || is_null($contract_id) || is_null($tr_id))
		{
			return null;
		}

		$vo = XML::loadSimpleXML(DAO::getSingleValue($link, "select ilr from ilr WHERE submission='$submission' and contract_id=$contract_id and tr_id = $tr_id and L03='$L03'"));
		return $vo;
	}


	public static function loadFromXML($xml)
	{
		$vo = XML::loadSimpleXML($xml);
		return $vo;
	}


	public static function generateStream4(PDO $link, $submission, $contracts, $con1, $beta = 0)
	{
		set_time_limit(0);
		if(is_null($contracts) || is_null($submission))
		{
			return null;
		}

		$l03 = '';

		$no_of_aims = 0;
		$funding_model = DAO::getSingleValue($link, "select funding_body from contracts where id in ($contracts) limit 0,1");

		$sqlouter = "SELECT distinct l03 FROM ilr left join contracts on contracts.id = ilr.contract_id WHERE submission = '$submission' and contract_id in ($contracts) and is_active=1 ORDER BY l03, tr_id";
		$stouter = $link->query($sqlouter);
		if($stouter)
		{
			// writing header information in data stream file
			print('<?xml version="1.0" encoding="utf-8"?>');
			print("\r\n");
			//print('<Message xsi:schemaLocation="http://www.theia.org.uk/ILR/2011-12/1ILR-2012-13-Structure.xsd" xmlns="http://www.theia.org.uk/ILR/2012-13/1" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance">');
			print ('<Message xmlns="http://www.theia.org.uk/ILR/2013-14/1" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xsi:schemaLocation="http://www.theia.org.uk/ILR/2013-14/1 file://lsc.local/sites$/CVH/Site/Organisational%20Data/Information%20Authority/Data%20Standards%20and%20Quality/Data%20Specification/ILR/2013-14/XMLSchema/Version1/ILR-2013-14-Structure.xsd">');
			print("\r\n\t");
			print("<Header>");
			print("\r\n\t\t");
			print("<CollectionDetails>");
			print("\r\n\t\t\t");
			print("<Collection>ILR</Collection>\r\n\t\t\t");
			print("<Year>1314</Year>\r\n\t\t\t");
			print("<FilePreparationDate>" .	date("Y-m-d") . "</FilePreparationDate>\r\n\t\t");
			print("</CollectionDetails>\r\n\t\t");
			print("<Source>\r\n\t\t\t");
			print("<ProtectiveMarking>PROTECT-PRIVATE</ProtectiveMarking>\r\n\t\t\t");
			if($beta == 1)
				print("<UKPRN>99999999</UKPRN>\r\n\t\t\t");
			else
				print("<UKPRN>" . $con1->ukprn . "</UKPRN>\r\n\t\t\t");
			print("<TransmissionType>A</TransmissionType>\r\n\t\t\t");
			print("<SoftwareSupplier>Perspective UK Limited</SoftwareSupplier>\r\n\t\t\t");
			print("<SoftwarePackage>Sunesis</SoftwarePackage>\r\n\t\t\t");
			print("<Release>V 5</Release>\r\n\t\t\t");
			print("<SerialNo>1</SerialNo>\r\n\t\t");
			print("<DateTime>" . date('Y-m-d') . "T" . date('H:i:s') . "</DateTime>");
			print("</Source>\r\n\t");
			print("</Header>\r\n\t");
			print("<LearningProvider>\r\n\t\t");
			if($beta == 1)
				print("<UKPRN>99999999</UKPRN>\r\n\t\t");
			else
				print("<UKPRN>" . $con1->ukprn . "</UKPRN>\r\n\t\t");
			print("</LearningProvider>");
			//	print("<Test>" . $sqlouter . "</Test>");
			while($rowouter = $stouter->fetch())
			{
				$l03 = $rowouter['l03'];
				$record=0;
				$AimSeqNumber =0;
				$no_of_aims=0;
				$sql = "SELECT * FROM ilr left join contracts on contracts.id = ilr.contract_id WHERE submission = '$submission' and contract_id in ($contracts) and l03 = '$l03' and is_active=1 ORDER BY tr_id desc";
				$destinations = DAO::getSingleValue($link, "SELECT count(*) FROM ilr WHERE submission = '$submission' and contract_id in ($contracts) and l03 = '$l03' and extractvalue(ilr,'/Learner/Dest')='95'");
				$st = $link->query($sql);
				if($st)
				{
					while($row = $st->fetch())
					{
						$ilr = $row['ilr'];
						$ilr = str_replace("&", "a", $ilr);
						$record++;
						$ilr = Ilr2013::loadFromXML($ilr);
						$funding_type = Ilr2013::FundingType($ilr);
						if($record==1)
						{
							print("\r\n\t<Learner>\r\n\t\t");
							print("<LearnRefNumber>" . $ilr->LearnRefNumber . "</LearnRefNumber>\r\n\t\t");
							if($ilr->PrevLearnRefNumber!='' && $ilr->PrevLearnRefNumber!='undefined')
								print("<PrevLearnRefNumber>" . $ilr->PrevLearnRefNumber . "</PrevLearnRefNumber>\r\n\t\t");
							if($ilr->PrevUKPRN!='' && $ilr->PrevUKPRN!='undefined')
								print("<PrevUKPRN>" . $ilr->PrevUKPRN . "</PrevUKPRN>\r\n\t\t");
							print("<ULN>" . str_pad($ilr->ULN,10,'9',STR_PAD_LEFT) . "</ULN>\r\n\t\t");
							print("<FamilyName>" . trim(str_replace("apos;","'",substr($ilr->FamilyName,0,20))) . "</FamilyName>\r\n\t\t");
							print("<GivenNames>" . trim($ilr->GivenNames) . "</GivenNames>\r\n\t\t");
							if($ilr->DateOfBirth!='' && $ilr->DateOfBirth!='00000000' && $ilr->DateOfBirth!='dd/mm/yyyy')
								print("<DateOfBirth>" . Date::toMySQL($ilr->DateOfBirth) . "</DateOfBirth>\r\n\t\t");
							print("<Ethnicity>" . str_pad($ilr->Ethnicity,2,'9',STR_PAD_LEFT) . "</Ethnicity>\r\n\t\t");
							print("<Sex>" . $ilr->Sex . "</Sex>\r\n\t\t");
							print("<LLDDHealthProb>" . str_pad($ilr->LLDDHealthProb,1,'9',STR_PAD_LEFT) . "</LLDDHealthProb>\r\n\t\t");
							if($ilr->NINumber!='')
								print("<NINumber>" . $ilr->NINumber . "</NINumber>\r\n\t\t");

							if($ilr->PriorAttain!='')
								print("<PriorAttain>" . $ilr->PriorAttain . "</PriorAttain>\r\n\t\t");
							else
								print("<PriorAttain>98</PriorAttain>\r\n\t\t");

							if($funding_type=="1619EFA")
							{
								if($ilr->Accom!='')
									print("<Accom>" . $ilr->Accom . "</Accom>\r\n\t\t");
								if($ilr->ALSCost!='')
									print("<ALSCost>" . $ilr->ALSCost . "</ALSCost>\r\n\t\t");
							}


							if(DB_NAME=='am_crackerjack')
							{
								$plan_learn_hours = DAO::getSingleColumn($link,"SELECT extractvalue(ilr,'/Learner/PlanLearnHours') FROM ilr left join contracts on contracts.id = ilr.contract_id WHERE submission = '$submission' and contract_id in ($contracts) and l03 = '$l03' and is_active=1 ORDER BY tr_id desc");
								$plan_learn_hours = array_sum($plan_learn_hours);
								if($plan_learn_hours!='' && $plan_learn_hours!='0')
									print("<PlanLearnHours>" . $plan_learn_hours . "</PlanLearnHours>\r\n\t\t");
							}
							else
							{
								if($ilr->PlanLearnHours!='' && $ilr->PlanLearnHours!='undefined')
									print("<PlanLearnHours>" . $ilr->PlanLearnHours . "</PlanLearnHours>\r\n\t\t");
							}

							if(DB_NAME=='am_crackerjack')
							{
								$plan_eep_hours = DAO::getSingleColumn($link,"SELECT extractvalue(ilr,'/Learner/PlanEEPHours') FROM ilr left join contracts on contracts.id = ilr.contract_id WHERE submission = '$submission' and contract_id in ($contracts) and l03 = '$l03' and is_active=1 ORDER BY tr_id desc");
								$plan_eep_hours = array_sum($plan_eep_hours);
								if($plan_eep_hours!='' && $plan_eep_hours!='0')
									print("<PlanEEPHours>" . $plan_eep_hours . "</PlanEEPHours>\r\n\t\t");
							}
							else
							{
								if($ilr->PlanEEPHours!='' && $ilr->PlanEEPHours!='undefined')
									print("<PlanEEPHours>" . $ilr->PlanEEPHours . "</PlanEEPHours>\r\n\t\t");
							}

							if($destinations)
								print("<Dest>95</Dest>\r\n\t\t");
							elseif($ilr->Dest=='')
								print("<Dest>95</Dest>\r\n\t\t");
							else
								print("<Dest>" . $ilr->Dest . "</Dest>\r\n\t\t");

							$xpath = $ilr->xpath("/Learner/LearnerContact[ContType='1' and LocType='2']/PostCode");
							if(!empty($xpath))
								print("<LearnerContact><LocType>2</LocType><ContType>1</ContType><PostCode>" . trim($xpath[0]) . "</PostCode></LearnerContact>");

							$add1 = $ilr->xpath('/Learner/LearnerContact/PostAdd/AddLine1');
							$add2 = $ilr->xpath('/Learner/LearnerContact/PostAdd/AddLine2');
							$add3 = $ilr->xpath('/Learner/LearnerContact/PostAdd/AddLine3');
							$add4 = $ilr->xpath('/Learner/LearnerContact/PostAdd/AddLine4');
							if(!empty($add1) || !empty($add2) || !empty($add3) || !empty($add1))
								if($add1[0]!='' || $add2[0]!='' || $add3[0]!='' || $add4[0]!='')
								{
									print("<LearnerContact><LocType>1</LocType><ContType>2</ContType><PostAdd>");
									if(!empty($add1))
										print("<AddLine1>" . substr($add1[0],0,30) . "</AddLine1>");

									if(!empty($add2))
										print("<AddLine2>" . $add2[0] . "</AddLine2>");

									if(!empty($add3))
										print("<AddLine3>" . $add3[0] . "</AddLine3>");

									if(!empty($add4))
										print("<AddLine4>" . $add4[0] . "</AddLine4>");
									print("</PostAdd></LearnerContact>");
								}

							$xpath = $ilr->xpath("/Learner/LearnerContact[ContType='2' and LocType='2']/PostCode");
							if(!empty($xpath))
								print("<LearnerContact><LocType>2</LocType><ContType>2</ContType><PostCode>" . trim($xpath[0]) . "</PostCode></LearnerContact>");

							$xpath = $ilr->xpath("/Learner/LearnerContact[ContType='2' and LocType='4']/Email");
							if(!empty($xpath[0]))
								print("<LearnerContact><LocType>4</LocType><ContType>2</ContType><Email>" . $xpath[0] . "</Email></LearnerContact>");

							$xpath = $ilr->xpath('/Learner/LearnerContact/TelNumber');
							if(!empty($xpath) && $xpath[0]!='')
								print("<LearnerContact><LocType>3</LocType><ContType>2</ContType><TelNumber>" . str_replace(" ","",$xpath[0]) . "</TelNumber></LearnerContact>");

							$rui1 = $rui2 = $rui3 = '';
							$xpath = ($ilr->xpath("/Learner/ContactPreference[ContPrefType='RUI' and ContPrefCode='1']/ContPrefCode"));
							if(isset($xpath[0]))
								$rui1 = $xpath[0];
							$xpath = ($ilr->xpath("/Learner/ContactPreference[ContPrefType='RUI' and ContPrefCode='2']/ContPrefCode"));
							if(isset($xpath[0]))
								$rui2 = $xpath[0];
							$xpath = ($ilr->xpath("/Learner/ContactPreference[ContPrefType='RUI' and ContPrefCode='3']/ContPrefCode"));
							if(isset($xpath[0]))
								$rui3 = $xpath[0];
							if($rui1=='1')
								print("<ContactPreference><ContPrefType>RUI</ContPrefType><ContPrefCode>1</ContPrefCode></ContactPreference>");
							elseif($rui2=='2')
								print("<ContactPreference><ContPrefType>RUI</ContPrefType><ContPrefCode>2</ContPrefCode></ContactPreference>");


							if($rui3!='3' && $rui1!='1' && $rui2!='2')
							{
								$xpath = ($ilr->xpath("/Learner/ContactPreference[ContPrefType='PMC' and ContPrefCode='1']/ContPrefCode"));
								$pmc1 = (!isset($xpath[0]))?'':$xpath[0];
								$xpath = ($ilr->xpath("/Learner/ContactPreference[ContPrefType='PMC' and ContPrefCode='2']/ContPrefCode"));
								$pmc2 = (!isset($xpath[0]))?'':$xpath[0];
								$xpath = ($ilr->xpath("/Learner/ContactPreference[ContPrefType='PMC' and ContPrefCode='3']/ContPrefCode"));
								$pmc3 = (!isset($xpath[0]))?'':$xpath[0];

								if($pmc1=='1')
									print("<ContactPreference><ContPrefType>PMC</ContPrefType><ContPrefCode>1</ContPrefCode></ContactPreference>");

								if($pmc2=='2')
									print("<ContactPreference><ContPrefType>PMC</ContPrefType><ContPrefCode>2</ContPrefCode></ContactPreference>");

								if($pmc3=='3')
									print("<ContactPreference><ContPrefType>PMC</ContPrefType><ContPrefCode>3</ContPrefCode></ContactPreference>");
							}

							$xpath = $ilr->xpath("/Learner/LLDDandHealthProblem[LLDDType='DS']/LLDDCode");
							if(isset($xpath[0])  && $xpath[0]!='' && $xpath[0]!='98')
								print("<LLDDandHealthProblem><LLDDType>DS</LLDDType><LLDDCode>" . $xpath[0] . "</LLDDCode></LLDDandHealthProblem>");

							$xpath = $ilr->xpath("/Learner/LLDDandHealthProblem[LLDDType='LD']/LLDDCode");
							if(isset($xpath[0]) && $xpath[0]!='' && $xpath[0]!='98')
								print("<LLDDandHealthProblem><LLDDType>LD</LLDDType><LLDDCode>" . $xpath[0] . "</LLDDCode></LLDDandHealthProblem>");

							$xpath = $ilr->xpath("/Learner/LearnerFAM[LearnFAMType='LDA']/LearnFAMCode");
							if(!empty($xpath[0]) && $xpath[0]!='99')
								print("<LearnerFAM><LearnFAMType>LDA</LearnFAMType><LearnFAMCode>" . $xpath[0]  . "</LearnFAMCode></LearnerFAM>");
							$xpath = $ilr->xpath("/Learner/LearnerFAM[LearnFAMType='ALS']/LearnFAMCode");
							if(!empty($xpath[0]) && $xpath[0]!='99')
								print("<LearnerFAM><LearnFAMType>ALS</LearnFAMType><LearnFAMCode>" . $xpath[0]  . "</LearnFAMCode></LearnerFAM>");
							$xpath = $ilr->xpath("/Learner/LearnerFAM[LearnFAMType='EHC']/LearnFAMCode");
							if(!empty($xpath[0]) && $xpath[0]!='99')
								print("<LearnerFAM><LearnFAMType>EHC</LearnFAMType><LearnFAMCode>" . $xpath[0]  . "</LearnFAMCode></LearnerFAM>");
							$xpath = $ilr->xpath("/Learner/LearnerFAM[LearnFAMType='DLA']/LearnFAMCode");
							if(!empty($xpath[0]) && $xpath[0]!='99')
								print("<LearnerFAM><LearnFAMType>DLA</LearnFAMType><LearnFAMCode>" . $xpath[0]  . "</LearnFAMCode></LearnerFAM>");

							$xpath = $ilr->xpath("/Learner/LearnerFAM[LearnFAMType='LSR']/LearnFAMCode");
							if(!empty($xpath[0]) && $xpath[0]!='99' && $xpath[0]!='41')
								print("<LearnerFAM><LearnFAMType>LSR</LearnFAMType><LearnFAMCode>" . $xpath[0]  . "</LearnFAMCode></LearnerFAM>");
							if(!empty($xpath[1]) && $xpath[1]!='99' && $xpath[0]!='41')
								print("<LearnerFAM><LearnFAMType>LSR</LearnFAMType><LearnFAMCode>" . $xpath[1]  . "</LearnFAMCode></LearnerFAM>");
							if(!empty($xpath[2])  && $xpath[2]!='99' && $xpath[0]!='41')
								print("<LearnerFAM><LearnFAMType>LSR</LearnFAMType><LearnFAMCode>" . $xpath[2]  . "</LearnFAMCode></LearnerFAM>");
							if(!empty($xpath[3]) && $xpath[3]!='99' && $xpath[0]!='41')
								print("<LearnerFAM><LearnFAMType>LSR</LearnFAMType><LearnFAMCode>" . $xpath[3]  . "</LearnFAMCode></LearnerFAM>");


							$xpath = $ilr->xpath("/Learner/LearnerFAM[LearnFAMType='NLM']/LearnFAMCode");
							if(!empty($xpath[0]) && $xpath[0]!='99')
								print("<LearnerFAM><LearnFAMType>NLM</LearnFAMType><LearnFAMCode>" . $xpath[0]  . "</LearnFAMCode></LearnerFAM>");
							if(!empty($xpath[1]) && $xpath[1]!='99')
								print("<LearnerFAM><LearnFAMType>NLM</LearnFAMType><LearnFAMCode>" . $xpath[1]  . "</LearnFAMCode></LearnerFAM>");

							$xpath = $ilr->xpath("/Learner/ProviderSpecLearnerMonitoring[ProvSpecLearnMonOccur='A']/ProvSpecLearnMon");
							if(!empty($xpath) && trim($xpath[0])!='')
								print("<ProviderSpecLearnerMonitoring><ProvSpecLearnMonOccur>A</ProvSpecLearnMonOccur><ProvSpecLearnMon>" . $xpath[0]  . "</ProvSpecLearnMon></ProviderSpecLearnerMonitoring>");

							$xpath = $ilr->xpath("/Learner/ProviderSpecLearnerMonitoring[ProvSpecLearnMonOccur='B']/ProvSpecLearnMon");
							if(!empty($xpath) && trim($xpath[0])!='')
								print("<ProviderSpecLearnerMonitoring><ProvSpecLearnMonOccur>B</ProvSpecLearnMonOccur><ProvSpecLearnMon>" . $xpath[0]  . "</ProvSpecLearnMon></ProviderSpecLearnerMonitoring>");

							$sqlemp = "SELECT * FROM ilr left join contracts on contracts.id = ilr.contract_id WHERE submission = '$submission' and contract_id in ($contracts) and l03 = '$l03' and is_active=1 ORDER BY tr_id desc";
							$stemp = $link->query($sqlemp);
							if($stemp)
							{
								while($rowemp = $stemp->fetch())
								{
									$ilremp = $rowemp['ilr'];
									$ilremp = str_replace("&", "a", $ilremp);
									$ilremp = Ilr2013::loadFromXML($ilremp);
									foreach($ilremp->LearnerEmploymentStatus as $empstatusemp)
									{
										if(("".$empstatusemp->EmpStat)!='' && ("".$empstatusemp->DateEmpStatApp)!='' && ("".$empstatusemp->DateEmpStatApp)!='dd/mm/yyyy')
										{
											print("<LearnerEmploymentStatus>");
											print("<EmpStat>" . $empstatusemp->EmpStat . "</EmpStat>");
											if($empstatusemp->DateEmpStatApp!='' && $empstatusemp->DateEmpStatApp!='dd/mm/yyyy')
												print("<DateEmpStatApp>" . Date::toMySQL($empstatusemp->DateEmpStatApp) . "</DateEmpStatApp>");
											$edrs = "" . $empstatusemp->EmpId;
											$edrs = trim(substr($edrs,0,9));
											if($edrs!='')
												print("<EmpId>" . $edrs . "</EmpId>");
											$xpath = $empstatusemp->xpath("./EmploymentStatusMonitoring[ESMType='SEI']/ESMCode");
											if(!empty($xpath[0]))
												print("<EmploymentStatusMonitoring><ESMType>SEI</ESMType><ESMCode>" . $xpath[0] . "</ESMCode></EmploymentStatusMonitoring>");
											$xpath = $empstatusemp->xpath("./EmploymentStatusMonitoring[ESMType='EII']/ESMCode");
											if(!empty($xpath[0]))
												print("<EmploymentStatusMonitoring><ESMType>EII</ESMType><ESMCode>" . $xpath[0] . "</ESMCode></EmploymentStatusMonitoring>");
											$xpath = $empstatusemp->xpath("./EmploymentStatusMonitoring[ESMType='LOU']/ESMCode");
											if(!empty($xpath[0]))
												print("<EmploymentStatusMonitoring><ESMType>LOU</ESMType><ESMCode>" . $xpath[0] . "</ESMCode></EmploymentStatusMonitoring>");
											$xpath = $empstatusemp->xpath("./EmploymentStatusMonitoring[ESMType='LOE']/ESMCode");
											if(!empty($xpath[0]))
												print("<EmploymentStatusMonitoring><ESMType>LOE</ESMType><ESMCode>" . $xpath[0] . "</ESMCode></EmploymentStatusMonitoring>");
											$xpath = $empstatusemp->xpath("./EmploymentStatusMonitoring[ESMType='BSI']/ESMCode");
											if(!empty($xpath[0]))
												print("<EmploymentStatusMonitoring><ESMType>BSI</ESMType><ESMCode>" . $xpath[0] . "</ESMCode></EmploymentStatusMonitoring>");
											$xpath = $empstatusemp->xpath("./EmploymentStatusMonitoring[ESMType='PEI']/ESMCode");
											if(!empty($xpath[0]))
												print("<EmploymentStatusMonitoring><ESMType>PEI</ESMType><ESMCode>" . $xpath[0] . "</ESMCode></EmploymentStatusMonitoring>");
											$xpath = $empstatusemp->xpath("./EmploymentStatusMonitoring[ESMType='RON']/ESMCode");
											if(!empty($xpath[0]))
												print("<EmploymentStatusMonitoring><ESMType>RON</ESMType><ESMCode>" . $xpath[0] . "</ESMCode></EmploymentStatusMonitoring>");
											print("</LearnerEmploymentStatus>");
										}
									}
								}
							}
						}
						foreach($ilr->LearningDelivery as $delivery)
						{
							if(DB_NAME=='am_raytheon' && $delivery->FundModel=='99')
								continue;
							if(DB_NAME=='am_raytheon' && ($delivery->LearnAimRef=='CMISC001' || $delivery->LearnAimRef=='ZVOC0004'))
								continue;

							$AimSeqNumber++;
							print("\r\n<LearningDelivery>");
							print("\r\n<LearnAimRef>" . strtoupper($delivery->LearnAimRef) . "</LearnAimRef>");
							if($delivery->AimType!='')
								print("\r\n<AimType>" . $delivery->AimType . "</AimType>");
							else
								print("\r\n<AimType>3</AimType>");
							print("\r\n<AimSeqNumber>" . $AimSeqNumber . "</AimSeqNumber>");
							if($delivery->LearnStartDate!='' && $delivery->LearnStartDate!='dd/mm/yyyy')
								print("\r\n<LearnStartDate>" . Date::toMySQL($delivery->LearnStartDate) . "</LearnStartDate>");
							if($delivery->OrigLearnStartDate!='' && $delivery->OrigLearnStartDate!='undefined' && $delivery->OrigLearnStartDate!='dd/mm/yyyy')
								print("\r\n<OrigLearnStartDate>" . Date::toMySQL($delivery->OrigLearnStartDate) . "</OrigLearnStartDate>");
							if($delivery->LearnPlanEndDate!='' && $delivery->LearnPlanEndDate!='dd/mm/yyyy')
								print("\r\n<LearnPlanEndDate>" . Date::toMySQL($delivery->LearnPlanEndDate) . "</LearnPlanEndDate>");
							if($delivery->FundModel!='')
								print("\r\n<FundModel>" . $delivery->FundModel . "</FundModel>");
							else
								print("\r\n<FundModel>35</FundModel>");
							if($delivery->FundModel!='10' && $delivery->FundModel!='99' && $delivery->ProgType!='' && $delivery->ProgType!='99')
								print("\r\n<ProgType>" . $delivery->ProgType . "</ProgType>");
							if($delivery->FworkCode!='' && $delivery->FworkCode!='undefined' && $delivery->FundModel!='10' && $delivery->FundModel!='99' && $delivery->ProgType!='99' && $delivery->ProgType!='')
								print("\r\n<FworkCode>" . $delivery->FworkCode . "</FworkCode>");
							if($delivery->PwayCode!='' && $delivery->PwayCode!='undefined' && $delivery->FundModel!='99' && $delivery->FundModel!='10' && $delivery->FundModel!='21' && $delivery->FundModel!='22' && $delivery->ProgType!='99' && $delivery->ProgType!='')
								print("\r\n<PwayCode>" . $delivery->PwayCode . "</PwayCode>");
							elseif($delivery->FundModel=='35' && ($delivery->AimType=='1' || $delivery->AimType=='2' || $delivery->AimType=='3'))
								print("\r\n<PwayCode>0</PwayCode>");
							if($delivery->PartnerUKPRN!='' && $delivery->PartnerUKPRN!='undefined' && $delivery->AimType!='1' && ($delivery->FundModel=='21' || $delivery->FundModel=='22' || $delivery->FundModel=='35'))
								print("\r\n<PartnerUKPRN>" . $delivery->PartnerUKPRN . "</PartnerUKPRN>");
							print("\r\n<DelLocPostCode>" . trim($delivery->DelLocPostCode) . "</DelLocPostCode>");
							if($delivery->PriorLearnFundAdj!='' && $delivery->PriorLearnFundAdj!='undefined')
								print("\r\n<PriorLearnFundAdj>" . $delivery->PriorLearnFundAdj . "</PriorLearnFundAdj>");
							if($delivery->OtherFundAdj!='' && $delivery->OtherFundAdj!='undefined')
								print("\r\n<OtherFundAdj>" . $delivery->OtherFundAdj . "</OtherFundAdj>");
							if(($delivery->AimType!='1') && ($delivery->FundModel=='70') && ("".$delivery->ESFProjDosNumber)!='')
							{
								if ( mb_strlen($delivery->ESFProjDosNumber) == 8 && substr($delivery->ESFProjDosNumber, 0, 1) > 1 )
									$delivery->ESFProjDosNumber = '0'.$delivery->ESFProjDosNumber;

								print("\r\n<ESFProjDosNumber>" . $delivery->ESFProjDosNumber . "</ESFProjDosNumber>");
							}
							if(($delivery->AimType!='1') && ($delivery->FundModel=='70') && ("".$delivery->ESFLocProjNumber)!='' ) {
								print("\r\n<ESFLocProjNumber>" . $delivery->ESFLocProjNumber . "</ESFLocProjNumber>");
							}

							if($delivery->EmpOutcome!='' && $delivery->EmpOutcome!='undefined' && $delivery->AimType!='1' && $delivery->FundModel!='21' && $delivery->FundModel!='10' && $delivery->FundModel!='99' && $delivery->ProgType!='99' && $delivery->ProgType!='') {
								print("\r\n<EmpOutcome>" . $delivery->EmpOutcome . "</EmpOutcome>");
							}
							if( $delivery->CompStatus!='' ) {
								print("\r\n<CompStatus>" . $delivery->CompStatus . "</CompStatus>");
							}
							if( $delivery->LearnActEndDate!='' && $delivery->LearnActEndDate!='dd/mm/yyyy' ) {
								print("\r\n<LearnActEndDate>" . Date::toMySQL($delivery->LearnActEndDate) . "</LearnActEndDate>");
							}
							if( $delivery->WithdrawReason!='' && $delivery->CompStatus=='3') {
								print("\r\n<WithdrawReason>" . $delivery->WithdrawReason . "</WithdrawReason>");
							}
							if( $delivery->Outcome!='' && $delivery->Outcome!='undefined' ) {
								print("\r\n<Outcome>" . $delivery->Outcome . "</Outcome>");
							}

							// Decide weather to send achievement date
							$flag125 = false;
							foreach($delivery->LearningDeliveryFAM as $ldf)
								if($ldf->LearnDelFAMType=='LDM' && $ldf->LearnDelFAMCode=='125')
									$flag125 = true;
							$xpath = $delivery->xpath("./LearningDeliveryFAM[LearnDelFAMType='WPL']/LearnDelFAMCode"); $wpl = (empty($xpath[0]))?'':$xpath[0];
							if($wpl!='' || ($delivery->ProgType!='' && $delivery->ProgType!='99') || $flag125)
								if( $delivery->AchDate!='' && $delivery->AchDate!='undefined' && $delivery->AchDate!='dd/mm/yyyy' && $delivery->AchDate!='00000000' && $delivery->FundModel!='81' && $delivery->FundModel!='99' )
								{
									print("\r\n<AchDate>" . Date::toMySQL($delivery->AchDate) . "</AchDate>");
								}
							// Decision ended


							if( $delivery->AimType!='1' && $delivery->OutGrade!='' && $delivery->OutGrade != 'undefined' )
							{
								print("\r\n<OutGrade>" . $delivery->OutGrade . "</OutGrade>");
							}

							$sof = '';
							$done = false;
							if($delivery->FundModel!='99')
								if((($delivery->FundModel=='35' || $delivery->FundModel=='25' || $delivery->FundModel=='70') && $delivery->ProgType!='99') || ($delivery->ProgType=='99')){
									$xpath = $delivery->xpath("./LearningDeliveryFAM[LearnDelFAMType='SOF']/LearnDelFAMCode"); $sof = (empty($xpath[0]))?'':$xpath[0];}
							if($sof!='' && $sof!='undefined')
								print("<LearningDeliveryFAM><LearnDelFAMType>SOF</LearnDelFAMType><LearnDelFAMCode>" . $sof . "</LearnDelFAMCode></LearningDeliveryFAM>");
							elseif($delivery->FundModel=='35')
								print("<LearningDeliveryFAM><LearnDelFAMType>SOF</LearnDelFAMType><LearnDelFAMCode>105</LearnDelFAMCode></LearningDeliveryFAM>");

							if($delivery->FundModel=='35')
							{
								$xpath = $delivery->xpath("./LearningDeliveryFAM[LearnDelFAMType='FFI']/LearnDelFAMCode"); $ffi = (empty($xpath[0]))?'':$xpath[0];
								if($ffi!='' && $ffi!='undefined' && $delivery->FundModel!='99')
									print("<LearningDeliveryFAM><LearnDelFAMType>FFI</LearnDelFAMType><LearnDelFAMCode>" . $ffi . "</LearnDelFAMCode></LearningDeliveryFAM>");
								$xpath = $delivery->xpath("./LearningDeliveryFAM[LearnDelFAMType='WPL']/LearnDelFAMCode"); $wpl = (empty($xpath[0]))?'':$xpath[0];
								if($wpl!='')
									print("<LearningDeliveryFAM><LearnDelFAMType>WPL</LearnDelFAMType><LearnDelFAMCode>" . $wpl . "</LearnDelFAMCode></LearningDeliveryFAM>");
								elseif($delivery->FundModel=='35' && ($delivery->ProgType=='2' || $delivery->ProgType=='3'))
									print("<LearningDeliveryFAM><LearnDelFAMType>WPL</LearnDelFAMType><LearnDelFAMCode>1</LearnDelFAMCode></LearningDeliveryFAM>");

								$xpath = $delivery->xpath("./LearningDeliveryFAM[LearnDelFAMType='EEF']/LearnDelFAMCode"); $eef = (empty($xpath[0]))?'':$xpath[0];
								if($eef!='' && $eef!='undefined')
									print("<LearningDeliveryFAM><LearnDelFAMType>EEF</LearnDelFAMType><LearnDelFAMCode>" . $eef . "</LearnDelFAMCode></LearningDeliveryFAM>");
							}

							$xpath = $delivery->xpath("./LearningDeliveryFAM[LearnDelFAMType='RES']/LearnDelFAMCode"); $res = (empty($xpath[0]))?'':$xpath[0];
							if($res!='')
								print("<LearningDeliveryFAM><LearnDelFAMType>RES</LearnDelFAMType><LearnDelFAMCode>" . $res . "</LearnDelFAMCode></LearningDeliveryFAM>");

							if($delivery->FundModel=='35' && $delivery->AimType=='1')
							{
								foreach($delivery->LearningDeliveryFAM as $ldf)
								{
									if($ldf->LearnDelFAMType=='LSF' && ("".$ldf->LearnDelFAMDateFrom)!='' && ("".$ldf->LearnDelFAMDateTo)!='')
									{
										print("<LearningDeliveryFAM><LearnDelFAMType>LSF</LearnDelFAMType><LearnDelFAMCode>" . $ldf->LearnDelFAMCode . "</LearnDelFAMCode>");
										print("<LearnDelFAMDateFrom>" . Date::toMySQL($ldf->LearnDelFAMDateFrom) . "</LearnDelFAMDateFrom>");
										print("<LearnDelFAMDateTo>" . Date::toMySQL($ldf->LearnDelFAMDateTo) . "</LearnDelFAMDateTo>");
										print("</LearningDeliveryFAM>");
									}
								}
							}

							if($delivery->FundModel=='99')
							{
								$xpath = $delivery->xpath("./LearningDeliveryFAM[LearnDelFAMType='ADL']/LearnDelFAMCode"); $adl = (empty($xpath[0]))?'':$xpath[0];
								if($adl!='' && $adl!='undefined')
									print("<LearningDeliveryFAM><LearnDelFAMType>ADL</LearnDelFAMType><LearnDelFAMCode>" . $adl . "</LearnDelFAMCode></LearningDeliveryFAM>");
								foreach($delivery->LearningDeliveryFAM as $ldf)
								{
									if($ldf->LearnDelFAMType=='ALB')
										print("<LearningDeliveryFAM><LearnDelFAMType>ALB</LearnDelFAMType><LearnDelFAMCode>" . $ldf->LearnDelFAMCode . "</LearnDelFAMCode></LearningDeliveryFAM>");
								}
							}

							if($delivery->FundModel=='10' || $delivery->AimType!='1')
							{
								$xpath = $delivery->xpath("./LearningDeliveryFAM[LearnDelFAMType='ASL']/LearnDelFAMCode"); $asl = (empty($xpath[0]))?'':$xpath[0];
								if($asl!='' && $asl!='undefined')
									print("<LearningDeliveryFAM><LearnDelFAMType>ASL</LearnDelFAMType><LearnDelFAMCode>" . $asl . "</LearnDelFAMCode></LearningDeliveryFAM>");
							}

							if($delivery->AimType=="1" || $delivery->AimType=="4" || $delivery->AimType=="5")
								foreach($delivery->LearningDeliveryFAM as $ldf)
									if($ldf->LearnDelFAMType=='LDM' && $ldf->LearnDelFAMCode!='' && $ldf->LearnDelFAMCode!='undefined' && $ldf->LearnDelFAMCode!='98')
										print("<LearningDeliveryFAM><LearnDelFAMType>LDM</LearnDelFAMType><LearnDelFAMCode>" . $ldf->LearnDelFAMCode . "</LearnDelFAMCode></LearningDeliveryFAM>");

							if($delivery->AimType=="1" || $delivery->AimType=="4")
							{
								$xpath = $delivery->xpath("./LearningDeliveryFAM[LearnDelFAMType='SPP']/LearnDelFAMCode"); $spp = (empty($xpath[0]))?'':$xpath[0];
								if($spp!='' && $spp!='undefined')
									print("<LearningDeliveryFAM><LearnDelFAMType>SPP</LearnDelFAMType><LearnDelFAMCode>" . $spp . "</LearnDelFAMCode></LearningDeliveryFAM>");

								$xpath = $delivery->xpath("./LearningDeliveryFAM[LearnDelFAMType='NSA']/LearnDelFAMCode"); $nsa = (empty($xpath[0]))?'':$xpath[0];
								if($nsa!='' && $nsa!='undefined')
									print("<LearningDeliveryFAM><LearnDelFAMType>NSA</LearnDelFAMType><LearnDelFAMCode>" . $nsa . "</LearnDelFAMCode></LearningDeliveryFAM>");

							}

							$xpath = $delivery->xpath("/Learner/LearningDelivery/ProviderSpecDeliveryMonitoring[ProvSpecDelMonOccur='A']/ProvSpecDelMon");
							if(!empty($xpath) && trim($xpath[0])!='')
								print("<ProviderSpecDeliveryMonitoring><ProvSpecDelMonOccur>A</ProvSpecDelMonOccur><ProvSpecDelMon>" . $xpath[0]  . "</ProvSpecDelMon></ProviderSpecDeliveryMonitoring>");
							$xpath = $delivery->xpath("/Learner/LearningDelivery/ProviderSpecDeliveryMonitoring[ProvSpecDelMonOccur='B']/ProvSpecDelMon");
							if(!empty($xpath) && trim($xpath[0])!='')
								print("<ProviderSpecDeliveryMonitoring><ProvSpecDelMonOccur>B</ProvSpecDelMonOccur><ProvSpecDelMon>" . $xpath[0]  . "</ProvSpecDelMon></ProviderSpecDeliveryMonitoring>");

							print("</LearningDelivery>");
						}
					}
					print("\r\n\t\t\t</Learner>");
				}
			}
			print("</Message>");
		}
	}

	public static function getFilename(PDO $link, $contract_id, $submission, $L01)
	{
		if(is_null($contract_id))
		{
			return null;
		}

		$contract = Contract::loadFromDatabase($link, $contract_id);

		$vo = new Ilr2011();
		$vo->learnerinformation = new LearnerInformation();
		$vo->aims[0] = new Aim();

		$sql = "SELECT * FROM ilr WHERE submission = '$submission' and contract_id ='$contract_id' and is_active=1;";

		// R06 record level validation starts
		$que = "select count(DISTINCT concat(L01,L03)) from ilr where submission = '$submission' and contract_id='$contract_id' and is_active=1;";
		$no_of_distinct_ilrs = trim(DAO::getSingleValue($link, $que));
		$que = "select count(concat(L01,L03)) from ilr where submission = '$submission' and contract_id = '$contract_id' and is_active=1;";
		$no_of_total_ilrs = trim(DAO::getSingleValue($link, $que));
		if($no_of_distinct_ilrs<$no_of_total_ilrs)
			throw new Exception("R06: No two learners must have the same provider number and learner reference");
		// R06 record level validation ends
		$st = $link->query($sql);
		if($st)
		{
			$file='A';
			$file.= $contract->ukprn;
			//$file.= '00';
			$file.= '1314';
			$file.= $submission;
		}
		return $file;
	}


	private function cleanTextField($fieldValue)
	{
		$fieldValue = str_replace($this->HTML_NEW_LINES, "\n", $fieldValue); // Convert <br/> etc. into \n
		$fieldValue = str_replace("\r", '', $fieldValue); // Remove all carriage returns (we'll use the UNIX newline)
		$fieldValue = preg_replace('/\n{2,}/', "\n", $fieldValue); // Remove superfluous newlines
		$fieldValue = strip_tags($fieldValue); // Remove HTML tags

		return $fieldValue;
	}

	public static function copyILRFields($xml, $template)
	{
		//$pageDomTemplate = new DomDocument();
		//@$pageDomTemplate->loadXML($template);
		$pageDomTemplate = XML::loadXmlDom($template);
		//$pageDomXML = new DomDocument();
		//@$pageDomXML->loadXML($xml);
		$pageDomXML = XML::loadXmlDom($xml);

		$evidencesTemplate = $pageDomTemplate->getElementsByTagName('subaim');
		foreach($evidencesTemplate as $evidenceTemplate)
		{
			$a09t = "" . $evidenceTemplate->getElementsByTagName('A09')->item(0)->nodeValue;

			$evidencesXML = $pageDomXML->getElementsByTagName('subaim');
			foreach($evidencesXML as $evidenceXML)
			{
				$a09x = "" . $evidenceXML->getElementsByTagName('A09')->item(0)->nodeValue;

				if($a09x == $a09t)
				{
					$evidenceXML->getElementsByTagName('A10')->item(0)->nodeValue = $evidenceTemplate->getElementsByTagName('A10')->item(0)->nodeValue;
					$evidenceXML->getElementsByTagName('A11a')->item(0)->nodeValue = $evidenceTemplate->getElementsByTagName('A11a')->item(0)->nodeValue;
					$evidenceXML->getElementsByTagName('A11b')->item(0)->nodeValue = $evidenceTemplate->getElementsByTagName('A11b')->item(0)->nodeValue;
					$evidenceXML->getElementsByTagName('A70')->item(0)->nodeValue = $evidenceTemplate->getElementsByTagName('A70')->item(0)->nodeValue;
					$evidenceXML->getElementsByTagName('A71')->item(0)->nodeValue = $evidenceTemplate->getElementsByTagName('A71')->item(0)->nodeValue;
					$evidenceXML->getElementsByTagName('A69')->item(0)->nodeValue = $evidenceTemplate->getElementsByTagName('A69')->item(0)->nodeValue;
					$evidenceXML->getElementsByTagName('A46a')->item(0)->nodeValue = $evidenceTemplate->getElementsByTagName('A46a')->item(0)->nodeValue;
					$evidenceXML->getElementsByTagName('A46b')->item(0)->nodeValue = $evidenceTemplate->getElementsByTagName('A46b')->item(0)->nodeValue;
					$evidenceXML->getElementsByTagName('A18')->item(0)->nodeValue = $evidenceTemplate->getElementsByTagName('A18')->item(0)->nodeValue;
					$evidenceXML->getElementsByTagName('A63')->item(0)->nodeValue = $evidenceTemplate->getElementsByTagName('A63')->item(0)->nodeValue;
					$evidenceXML->getElementsByTagName('A66')->item(0)->nodeValue = $evidenceTemplate->getElementsByTagName('A66')->item(0)->nodeValue;
					$evidenceXML->getElementsByTagName('A67')->item(0)->nodeValue = $evidenceTemplate->getElementsByTagName('A67')->item(0)->nodeValue;
					$evidenceXML->getElementsByTagName('A34')->item(0)->nodeValue = $evidenceTemplate->getElementsByTagName('A34')->item(0)->nodeValue;
					$evidenceXML->getElementsByTagName('A35')->item(0)->nodeValue = $evidenceTemplate->getElementsByTagName('A35')->item(0)->nodeValue;
					$evidenceXML->getElementsByTagName('A50')->item(0)->nodeValue = $evidenceTemplate->getElementsByTagName('A50')->item(0)->nodeValue;
					$evidenceXML->getElementsByTagName('A53')->item(0)->nodeValue = $evidenceTemplate->getElementsByTagName('A53')->item(0)->nodeValue;
					$evidenceXML->getElementsByTagName('A59')->item(0)->nodeValue = $evidenceTemplate->getElementsByTagName('A59')->item(0)->nodeValue;
					$evidenceXML->getElementsByTagName('A60')->item(0)->nodeValue = $evidenceTemplate->getElementsByTagName('A60')->item(0)->nodeValue;
					$evidenceXML->getElementsByTagName('A61')->item(0)->nodeValue = $evidenceTemplate->getElementsByTagName('A61')->item(0)->nodeValue;
					$evidenceXML->getElementsByTagName('A62')->item(0)->nodeValue = $evidenceTemplate->getElementsByTagName('A62')->item(0)->nodeValue;
					$evidenceXML->getElementsByTagName('A63')->item(0)->nodeValue = $evidenceTemplate->getElementsByTagName('A63')->item(0)->nodeValue;
					$evidenceXML->getElementsByTagName('A66')->item(0)->nodeValue = $evidenceTemplate->getElementsByTagName('A66')->item(0)->nodeValue;
					$evidenceXML->getElementsByTagName('A67')->item(0)->nodeValue = $evidenceTemplate->getElementsByTagName('A67')->item(0)->nodeValue;
					$evidenceXML->getElementsByTagName('A68')->item(0)->nodeValue = $evidenceTemplate->getElementsByTagName('A68')->item(0)->nodeValue;
					$evidenceXML->getElementsByTagName('A69')->item(0)->nodeValue = $evidenceTemplate->getElementsByTagName('A69')->item(0)->nodeValue;
					$evidenceXML->getElementsByTagName('A70')->item(0)->nodeValue = $evidenceTemplate->getElementsByTagName('A70')->item(0)->nodeValue;
					$evidenceXML->getElementsByTagName('A71')->item(0)->nodeValue = $evidenceTemplate->getElementsByTagName('A71')->item(0)->nodeValue;
				}
			}
		}
		$ilr = $pageDomXML->saveXML();
		$ilr=substr($ilr,21);
		return $ilr;
	}

	public $id = NULL;
	public $gender = NULL;
	public $firstnames = NULL;
	public $surname = NULL;
	public $postcode = NULL;
	public $town = NULL;
	public $L26 = NULL;
	public $submission_date=NULL;
	public $subaims=0;
	public $learnerinformation = NULL;
	public $aims = array();
	public $active = NULL;
	public $approve = NULL;
	public $programmeaim = NULL;

	private $HTML_NEW_LINES = array('<br>', '<br/>', '<br />', '<BR>', '<BR/>', '<BR />', '</p>', '</P>');
}
?>