<?php
class populate_sunesis_from_2011batch_xml implements IAction
{
	public function execute(PDO $link)
	{


		$filename = $_FILES['uploadedfile']['tmp_name'];
		$content = file_get_contents($filename);

		DAO::execute($link, "truncate learner");
		DAO::execute($link, "truncate aim");
		DAO::execute($link, "TRUNCATE tr;");
		DAO::execute($link, "TRUNCATE courses_tr;");
		DAO::execute($link, "delete from locations where organisations_id in (select id from organisations where organisation_type = 2)");
		DAO::execute($link, "DELETE FROM organisations WHERE organisation_type = 2;");
		DAO::execute($link, "DELETE FROM users WHERE TYPE = 5;");
		DAO::execute($link, "truncate ilr;");
		DAO::execute($link, "TRUNCATE student_qualifications;");
		DAO::execute($link, "TRUNCATE assessor_review;");
		DAO::execute($link, "TRUNCATE group_members;");
		DAO::execute($link, "TRUNCATE qualifications;");
		DAO::execute($link, "TRUNCATE student_frameworks;");

		$qans = array();
		$employers = Array();
		$learners = Array();

		//$xml = new SimpleXMLElement($content);
		$xml = XML::loadSimpleXML($content);
		
		foreach ($xml->LearningProvider as $provider) 
		{
			$l01 = "" . $provider->UPIN;
			$l46 = "" . $provider->UKPRN;
			$upin = $l01;
		}
		
		foreach ($xml->Learner as $learner) 
		{
			$a16 = '0';
			$l02 = 0;
			$l03 = "" . $learner->LearnRefNumber;
			$l04 = "10";
			$l05 = "0";
			$l07 = "0";
			$l08 = "N";
			$l09 = "" . addslashes((string)$learner->FamilyName);
			$l10 = "" . addslashes((string)$learner->GivenNames);
			$l11 = "" . $learner->DateOfBirth;
			$l12 = "" . $learner->Ethnicity;
			$l13 = "" . $learner->Sex;
			$l14 = "" . $learner->LLDDInd;

			$l15 = '';
			$l16 = '';
			if(isset($learner->LLDDandHealthProblem))
			{
				foreach ($learner->LLDDandHealthProblem as $dsld) 
				{
					$llddtype = "" . $dsld->LLDDType;
					if($llddtype=="DS")
						$l15 = 0 . $dsld->LLDDCode;	

					if($llddtype=="LD")
						$l16 = 0 . $dsld->LLDDCode;	
				}			
			}
			else
			{
				$l15 = "98";
				$l16 = "98";
			}
			if($l15=='')
				$l15 = "98";
			if($l16=='')
				$l16 = "98";

				
			$l51 = '';	
			$l23 = "";
			foreach($learner->LearnerContact as $contact)
			{
				$loctype = "" . $contact->LocType;
				$conttype = "" . $contact->ContType;
				if($loctype=="1" && $conttype=="1") // Cant happen
				{
					pre("Error");
				}
				if($loctype=="1" && $conttype=="2") // postal address
				{
					$l18 = "" . addslashes((string)$contact->PostAdd->AddLine1);
					$l19 = "" . addslashes((string)$contact->PostAdd->AddLine2);
					$l20 = "" . addslashes((string)$contact->PostAdd->AddLine3);
					$l21 = "" . addslashes((string)$contact->PostAdd->AddLine4);
				}
				if($loctype=="2" && $conttype=="1") // Postcode prior
				{
					$l17 = "" . $contact->PostCode;
				}
				if($loctype=="2" && $conttype=="2") // Current Postcode
				{
					$l22 = "" . $contact->PostCode;
				}
				if($loctype=="3" && $conttype=="2") // Telephone Number
				{
					$l23 = "" . $contact->TelNumber;
				}
				if($loctype=="4" && $conttype=="2") // Email
				{
					$l51 = "" . addslashes((string)$contact->Email);
				}
			}	
			
		
			
			$l24 = "" . $learner->Domicile;	
			$l25 = '0';
			$l26 = "" . $learner->NINumber;
			
			$rui = array();
			$pmc = array();
			foreach($learner->ContactPreference as $cp)
			{
				$cpt = "" . $cp->ContPrefType;
				$cpc = "" . $cp->ContPrefCode;
				if($cpt=="RUI")
					$rui[] = $cpc;

				if($cpt=="PMC")
					$pmc[] = $cpc;
			}

			// L27 Calculation
			if(count($rui)==0)
			{
				$l27 = "9";
			}			
			elseif(count($rui)==1)
			{
				if($rui[0] == "3")
					$l27 = "2";
				elseif($rui[0] == "1")
					$l27 = "3";
				elseif($rui[0]=="2")
					$l27 = "4";  
			}
			else
			{
				$l27 = 1;
			}

			// L52 Calculation
			if(count($pmc)==0)
			{
				$l52 = "9";
			}
			elseif(count($pmc)==1)
			{
				$l52 = $pmc[0];
			}
			elseif(count($pmc)==2)
			{
				if(($pmc[0]==1 && $pmc[1]==2) || ($pmc[0]==2 && $pmc[1]==1))
					$l52 = "4";

				if(($pmc[0]==1 && $pmc[1]==3) || ($pmc[0]==3 && $pmc[1]==1))
					$l52 = "5";

				if(($pmc[0]==2 && $pmc[1]==3) || ($pmc[0]==3 && $pmc[1]==2))
					$l52 = "6";
			}
			elseif(count($pmc)==3)
			{
				$l52 = "7";
			}
			else 
			{
				$l52 = "9";
			}

			$l28a = "99";
			$l28b = "99";
			$l29 = "0";
			$l31 = "0";
			$l32 = "0";
			$l33 = "0";	
			
			$l34 = array();
			$index = 0;
			foreach($learner->LearnerFAM as $lfam)
			{
				$lft = "" . $lfam->LearnFAMType;
				$lfc = "" . $lfam->LearnFAMCode;
				if($lft == "LSR")
					$l34[$index++] = $lfc;
			}
			if(isset($l34[0]))
				$l34a = $l34[0];
			else
				$l34a = "99";

			if(isset($l34[1]))
				$l34b = $l34[1];
			else
				$l34b = "99";
				
			if(isset($l34[2]))
				$l34c = $l34[2];
			else
				$l34c = "99";
				
			if(isset($l34[3]))
				$l34d = $l34[3];
			else
				$l34d = "99";

			$l35 = "" . $learner->PriorAttain;
            if($l35=='')
                $l35 = 0;
			$l36 = "0";	

			$l37xml = array();	
			foreach($learner->LearnerEmploymentStatus as $les)
			{
				if(isset($les->EmpStatType))
				{
					$l37xml[] = "" . $les->EmpStatType . $les->EmpStatCode;
				}
				if(isset($les->EmploymentStatusMonitoring->ESMType))
				{
					$l37xml[] = "" . $les->EmploymentStatusMonitoring->ESMType . $les->EmploymentStatusMonitoring->ESMCode;
				}				
			}
			if(in_array("FDL4",$l37xml) && in_array("RFU1",$l37xml) && in_array("BSI1",$l37xml))
			{
				$l37 = "8";
			}
			elseif(in_array("FDL4",$l37xml) && in_array("RFU1",$l37xml) && in_array("BSI2",$l37xml))
			{
				$l37 = "9";
			}
			elseif(in_array("FDL4",$l37xml) && in_array("RFU2",$l37xml) && in_array("BSI1",$l37xml))
			{
				$l37 = "11";
			}
			elseif(in_array("FDL4",$l37xml) && in_array("RFU2",$l37xml) && in_array("BSI2",$l37xml))
			{
				$l37 = "12";
			}
			elseif(in_array("FDL4",$l37xml) && in_array("RFU1",$l37xml))
			{
				$l37 = "3";
			}
			elseif(in_array("FDL4",$l37xml) && in_array("RFU2",$l37xml))
			{
				$l37 = "4";
			}
			elseif(in_array("FDL1",$l37xml) && in_array("EII1",$l37xml))
			{
				$l37 = "6";
			}
			elseif(in_array("FDL1",$l37xml) && in_array("EII2",$l37xml))
			{
				$l37 = "7";
			}
			elseif(in_array("FDL4",$l37xml) && in_array("RFU1",$l37xml))
			{
				$l37 = "10";
			}
			elseif(in_array("FDL4",$l37xml) && in_array("RFU2",$l37xml))
			{
				$l37 = "13";
			}
			elseif(in_array("FDL4",$l37xml) && in_array("BSI1",$l37xml))
			{
				$l37 = "14";
			}
			elseif(in_array("FDL4",$l37xml) && in_array("BSI2",$l37xml))
			{
				$l37 = "15";
			}
			elseif(in_array("FDL1",$l37xml))
			{
				$l37 = "1";
			}
			elseif(in_array("FDL4",$l37xml))
			{
				$l37 = "16";
			}
			elseif(in_array("FDL6",$l37xml))
			{
				$l37 = "17";
			}
			elseif(in_array("FDL98",$l37xml))
			{
				$l37 = "98";
			}
					
			$l39 = "" . $learner->Dest;
			$l39 = ($l39=='')?0:$l39;
            $l40a = "99";

			if(isset($learner->LearnerFAM))
			{
				foreach($learner->LearnerFAM as $lfam)
				{
					if($lfam->LearnFAMType=="NLM")
						$l40a = "" . $lfam->LearnFAMCode;
				}
			}
			else 
			{
				$l40a = "99";
			}
		
			$l40b = "99";
			$l41a = "0";
			$l41b = "0";
			$l42a = "            ";
			$l42b = "            ";
			$l44 = '0';
			$l45 = "" . $learner->ULN;
			$l47 = "98";
			if(in_array("CES4",$l37xml) && in_array("RFU1",$l37xml) && in_array("BSI1",$l37xml))
			{
				$l47 = "8";
			}
			elseif(in_array("CES4",$l37xml) && in_array("RFU1",$l37xml) && in_array("BSI2",$l37xml))
			{
				$l47 = "9";
			}
			elseif(in_array("CES4",$l37xml) && in_array("RFU2",$l37xml) && in_array("BSI1",$l37xml))
			{
				$l47 = "11";
			}
			elseif(in_array("CES4",$l37xml) && in_array("RFU2",$l37xml) && in_array("BSI2",$l37xml))
			{
				$l47 = "12";
			}
			elseif(in_array("CES4",$l37xml) && in_array("RFU1",$l37xml))
			{
				$l47 = "3";
			}
			elseif(in_array("CES4",$l37xml) && in_array("RFU2",$l37xml))
			{
				$l47 = "4";
			}
			elseif(in_array("CES1",$l37xml) && in_array("EII1",$l37xml))
			{
				$l47 = "6";
			}
			elseif(in_array("CES1",$l37xml) && in_array("EII2",$l37xml))
			{
				$l47 = "7";
			}
			elseif(in_array("CES4",$l37xml) && in_array("RFU1",$l37xml))
			{
				$l47 = "10";
			}
			elseif(in_array("CES4",$l37xml) && in_array("RFU2",$l37xml))
			{
				$l47 = "13";
			}
			elseif(in_array("CES4",$l37xml) && in_array("BSI1",$l37xml))
			{
				$l47 = "14";
			}
			elseif(in_array("CES4",$l37xml) && in_array("BSI2",$l37xml))
			{
				$l47 = "15";
			}
			elseif(in_array("CES1",$l37xml))
			{
				$l47 = "1";
			}
			elseif(in_array("CES4",$l37xml))
			{
				$l47 = "16";
			}
			elseif(in_array("CES6",$l37xml))
			{
				$l47 = "17";
			}
			elseif(in_array("CES98",$l37xml))
			{
				$l47 = "98";
			}
			
			$l48 = 'NULL';
			$a44 = '';
			foreach($learner->LearnerEmploymentStatus as $les)
			{
				if(isset($les->EmpStatType) && $les->EmpStatType=='CES' && $les->EmpStatCode!='98')
				{
					$l48 = "" . $les->DateEmpStatApp;
				}				

				$a44 = isset($les->EmpId)?$les->EmpId:$a44;	
				$a45 = $les->WorkLocPostCode;
			}
			if($l48!='NULL')
				$l48 = "'" . Date::toMySQL($l48) . "'"; 
			
			$l49a = "0";
			$l49b = "0";
			$l49c = "0";
			$l49d = "0";

			
			$l52xml = array();
			foreach($learner->ContactPreference as $cp)
			{
				$l52xml[] = "" . $cp->ContPrefType . $cp->ContPrefCode;
			}

			if(in_array("PMC1",$l52xml) && in_array("PMC2",$l52xml) && in_array("PMC3",$l52xml))
			{
				$l52 = "7";
			}
			elseif(in_array("PMC1",$l52xml) && in_array("PMC2",$l52xml))
			{
				$l52 = "4";
			}
			elseif(in_array("PMC1",$l52xml) && in_array("PMC3",$l52xml))
			{
				$l52 = "5";
			}
			elseif(in_array("PMC2",$l52xml) && in_array("PMC3",$l52xml))
			{
				$l52 = "6";
			}

			foreach($learner->ProviderSpecLearnerMonitoring as $lm)
			{
				if($lm->LearnOccurCode=="A")
					$l42a = $lm->ProvSpecLearnMon;
				elseif($lm->LearnOccurCode=="B")
					$l42b = $lm->ProvSpecLearnMon;
			}
			
            if(!isset($l37))
                $l37=0;

			$sql = "insert into learner values('0','$l01','$l02','$l03','$l04','$l05','$l07','$l08','$l09','$l10','$l11','$l12','$l13','$l14','$l15','$l16','$l17','$l18','$l19','$l20','$l21','$l22','$l23','$l24','$l25','$l26','$l27','$l28a','$l28b','$l29','$l31','$l32','$l33','$l34a','$l34b','$l34c','$l34d','$l35','$l36','$l37','$l39','$l40a','$l40b','$l41a','$l41b','$l42a','$l42b','$l44','$l45','$l46','$l47',$l48,'$l49a','$l49b','$l49c','$l49d','$l51','$l52',0,0,0);";
			DAO::execute($link, $sql);

			foreach($learner->LearningDelivery as $ld)
			{
				$a01 = $l01;
				$a02 = $l02;
				$a03 = $l03;
				$a70 = '';
				if($ld->AimType=='1')
					$a04 = '35';
				else
					$a04 = '30';

				$a05 = $ld->AimSeqNumber;
				$a07 = '0';
				$a08 = '0';
				$a09 = $ld->LearnAimRef;
				if($ld->AimType=='2' && $ld->FundModel=='45')
					$a10 = '46';
				else
					$a10 = $ld->FundModel;		

				$a11 = Array();	
				$a11a = '';
				$a11b = '';
				$a20 = 0;
				$a53array = Array();
				foreach($ld->LearningDeliveryFAM as $ldf)
				{
					if($ldf->LearnDelFAMType == 'SOF')
						$a11[] = $ldf->LearnDelFAMCode; 
					
					if($ldf->LearnDelFAMType == 'RET')
						$a20 = $ldf->LearnDelFAMCode; 

					$a53array[] = "" . $ldf->LearnDelFAMType . $ldf->LearnDelFAMCode;	
				}		

				if(in_array("ALN1",$a53array) && in_array("ASN1",$a53array))
				{
					$a53 = "13";
				}
				elseif(in_array("ALN1",$a53array))
				{
					$a53 = "11";
				}				
				elseif(in_array("ASN1",$a53array))
				{
					$a53 = "12";
				}
				else
				{
					$a53 = '97';
				}

				if(in_array("ASL1",$a53array))
				{
					$a58 = '1';
				}
				elseif(in_array("ASL2",$a53array))
				{
					$a58 = '2';
				}
				elseif(in_array("ASL3",$a53array))
				{
					$a58 = '3';
				}
				elseif(in_array("ASL4",$a53array))
				{
					$a58 = '4';
				}
				elseif(in_array("FSI1",$a53array))
				{
					$a58 = '5';
				}
				else
				{
					$a58 = '99';	
				}	

				if(in_array("NSA1",$a53array))
				{
					$a63 = '1';
				}
				elseif(in_array("NSA2",$a53array))
				{
					$a63 = '2';
				}
				elseif(in_array("NSA3",$a53array))
				{
					$a63 = '3';
				}
				elseif(in_array("NSA4",$a53array))
				{
					$a63 = '4';
				}
				elseif(in_array("NSA5",$a53array))
				{
					$a63 = '5';
				}
				elseif(in_array("NSA6",$a53array))
				{
					$a63 = '6';
				}
				elseif(in_array("NSA7",$a53array))
				{
					$a63 = '7';
				}
				elseif(in_array("NSA8",$a53array))
				{
					$a63 = '8';
				}
				elseif(in_array("NSA9",$a53array))
				{
					$a63 = '9';
				}
				elseif(in_array("NSA10",$a53array))
				{
					$a63 = '10';
				}
				elseif(in_array("NSA11",$a53array))
				{
					$a63 = '11';
				}
				elseif(in_array("NSA12",$a53array))
				{
					$a63 = '12';
				}
				elseif(in_array("NSA13",$a53array))
				{
					$a63 = '13';
				}
				elseif(in_array("NSA14",$a53array))
				{
					$a63 = '14';
				}
				elseif(in_array("NSA15",$a53array))
				{
					$a63 = '15';
				}
				elseif(in_array("NSA16",$a53array))
				{
					$a63 = '16';
				}
				elseif(in_array("NSA17",$a53array))
				{
					$a63 = '17';
				}
				else
				{
					$a63 = '99';
				}				
				
				$a55 = $l45;
				$a56 = $l46;
							
					
				if(isset($a11[0]) && $a11a=='')
					$a11a = $a11[0];
				else
					$a11a = '999';
				
				if(isset($a11[1]) && $a11b=='')
					$a11b = $a11[1];
				else 
					$a11b = '999';

				$a46a = '999';
				$a46b = '999';
					
				$a13 = (isset($ld->FeeYTD)?$ld->FeeYTD:0);
				$a14 = 0;
				$a15 = $ld->ProgType;
				$a16 = isset($ld->ProgEntRoute)?$ld->ProgEntRoute:$a16;
				$a17 = (isset($ld->DelMode)?$ld->DelMode:0);
				$a18 = isset($ld->MainDelMeth)?$ld->MainDelMeth:0;			
				$a19 = isset($ld->EmpRole)?$ld->EmpRole:0;
				$a21 = 0;
				$a22 = $ld->PartnerUKPRN;
				$a23 = $ld->DelLocPostCode;
				$a26 = isset($ld->FworkCode)?$ld->FworkCode:0;
				$a27 = $ld->LearnStartDate;
				$a28 = $ld->LearnPlanEndDate;
				$a31 = isset($ld->LearnActEndDate)?"'" . $ld->LearnActEndDate . "'":'NULL';
				$a32 = isset($ld->GLH)?$ld->GLH:0;

				
				if($ld->CompStatus=='3' && $ld->WithdrawReason=='40')
					$a34 = '4';
//				elseif($ld->CompStatus=='3' && $ld->WithdrawReason!='40' && $ld->WithdrawReason!='98')
//					$a34 = '5';
				else
					$a34 = $ld->CompStatus;

				$a35 = isset($ld->OutcomeInd)?$ld->OutcomeInd:'9';
				$a36 = isset($ld->OutGrade)?$ld->OutGrade:'';
				$a40 = isset($ld->AchDate)?"'" . $ld->AchDate . "'":'NULL';
				$a49 = '';

				if($ld->WithdrawReason=='1')
					$a50 = '1';
				elseif($ld->WithdrawReason=='2')
					$a50 = '2';
				elseif($ld->WithdrawReason=='3')
					$a50 = '3';
				elseif($ld->ActProgRoute=='3')
					$a50 = '5';
				elseif($ld->WithdrawReason=='7')
					$a50 = '7';
				elseif($ld->WithdrawReason=='27')
					$a50 = '27';
				elseif($ld->WithdrawReason=='28')
					$a50 = '28';
				elseif($ld->WithdrawReason=='29')
					$a50 = '29';
				elseif($ld->ActProgRoute=='1')
					$a50 = '30';
				elseif($ld->ActProgRoute=='2')
					$a50 = '31';
				elseif($ld->ActProgRoute=='3')
					$a50 = '32';
				elseif($ld->ActProgRoute=='4')
					$a50 = '34';
				elseif($ld->ActProgRoute=='5')
					$a50 = '33';
				elseif($ld->ActProgRoute=='6')
					$a50 = '35';
				elseif($ld->WithdrawReason=='97')
					$a50 = '97';
				elseif($ld->WithdrawReason=='98')
					$a50 = '98';
				else
					$a50 = '96';
										
				$a51a = isset($ld->PropFundRemain)?$ld->PropFundRemain:100;
				$a52 = isset($ld->DistLearnSLN)?$ld->DistLearnSLN:0;
				$a57 = isset($ld->FeeSource)?$ld->FeeSource:'98';

				$a59 = isset($ld->PlanCredVal)?$ld->PlanCredVal:0;
				$a60 = isset($ld->CredAch)?$ld->CredAch:0;
				$a61 = isset($ld->ESFProjDosNumber)?$ld->ESFProjDosNumber:'';
				$a62 = isset($ld->ESFLocProjNumber)?$ld->ESFLocProjNumber:'0';
				$a64 = isset($ld->PlanGrpHrs)?$ld->PlanGrpHrs:0;
				$a65 = isset($ld->PlanOneToOneHrs)?$ld->PlanOneToOneHrs:0;
								
				if(in_array("DBS1",$l37xml))
				{
					$a66 = '1';
				}
				elseif(in_array("DBS2",$l37xml))
				{
					$a66 = '2';
				}				
				elseif(in_array("DBS3",$l37xml))
				{
					$a66 = '3';
				}				
				elseif(in_array("DBS4",$l37xml))
				{
					$a66 = '4';
				}				
				elseif(in_array("DBS6",$l37xml))
				{
					$a66 = '6';
				}				
				elseif(in_array("DBS7",$l37xml))
				{
					$a66 = '7';
				}				
				elseif(in_array("DBS98",$l37xml))
				{
					$a66 = '98';
				}				
				
				if(in_array("DBS4",$l37xml) && in_array("LOU1",$l37xml))
				{
					$a67 = "1";
				}
				elseif(in_array("DBS4",$l37xml) && in_array("LOU2",$l37xml))
				{
					$a67 = "2";
				}
				elseif(in_array("DBS4",$l37xml) && in_array("LOU3",$l37xml))
				{
					$a67 = "3";
				}
				elseif(in_array("DBS4",$l37xml) && in_array("LOU4",$l37xml))
				{
					$a67 = "4";
				}
				elseif(in_array("DBS4",$l37xml) && in_array("LOU5",$l37xml))
				{
					$a67 = "5";
				}
				else
				{
					$a67 = "99";
				}
	
				$a68 = (isset($ld->EmpOutcome)?$ld->EmpOutcome:'99');
				
				if(in_array("EEF1",$a53array))
				{
					$a69 = '1';
				}
				elseif(in_array("EEF2",$a53array))
				{
					$a69 = '2';
				}
				elseif(in_array("EEF3",$a53array))
				{
					$a69 = '3';
				}
				else
				{
					$a69 = '99';
				}

				$a70 = isset($ld->ContOrgCode)?$ld->ContOrgCode:$a70;
				
				if(in_array("FFI1",$a53array))
				{
					$a71 = '1';
				}
				elseif(in_array("FFI2",$a53array))
				{
					$a71 = '2';
				}
				else
				{
					$a71 = '99';
				}

				$a72a = '';
				$a72b = '';
				$a48a = '';
				$a48b = '';
								
				foreach($learner->ProviderSpecLearnerMonitoring as $lm)
				{
					if($lm->LearnOccurCode=="C")
						$a72a = $lm->ProvSpecLearnMon;
					elseif($lm->LearnOccurCode=="D")
						$a72b = $lm->ProvSpecLearnMon;
				}

				
				$a47a = '0';
				$a47b = '0';
				$a54 = '0';
				if($a15=='')
					$a15=0;

				$sql = "insert into aim values('0','$a01','$a02','$a03','$a04','$a05','$a07','$a08','$a09','$a10','$a11a','$a11b','$a13','$a14','$a15','$a16','$a17','$a18','$a19','$a20','$a21','$a22','$a23','$a26','$a27','$a28',$a31,'$a32','$a34','$a35','$a36',$a40,'$a44','$a45','$a46a','$a46b','$a47a','$a47b','$a48a','$a48b','$a49','$a50','$a51a','$a52','$a53','$a54','$a55','$a56','$a57','$a58','$a59','$a60','$a61','$a62','$a63','$a64','$a65','$a66','$a67','$a68','$a69','$a70','$a71','$a72a','$a72b');";
				DAO::execute($link, $sql);
			} 
		}

    //    DAO::execute($link, "delete from aim where A10=21 or A10=22");
      //  DAO::execute($link, "delete from aim where A15=19");
     //   DAO::execute($link, "delete from learner where L03 not in (select distinct A03 from aim)");

		// Delete existing employers and locations
		DAO::execute($link, "DELETE FROM organisations, locations USING organisations LEFT OUTER JOIN locations ON locations.organisations_id = organisations.id WHERE organisations.organisation_type = 2;");
		DAO::execute($link, "DELETE FROM locations WHERE organisations_id NOT IN (SELECT id FROM organisations WHERE organisation_type = 2);");
		$sql = "SELECT DISTINCT a44 FROM aim WHERE a44 !='';";
		$st = $link->query($sql);
		if($st) 
		{
			while($row = $st->fetch())
			{
				$a44 = $row['a44'];
				$emp_id = DAO::getSingleValue($link, "select id from organisations where edrs = '$a44'");
	
				// Create an employer
				if($emp_id=='')
				{
					$e = new Employer();
					$e->legal_name = trim($row['a44']);
					$e->edrs = trim($row['a44']);
					$e->trading_name = trim($row['a44']);
					$e->active = 1;
					$e->save($link);
				}
				else
				{
					$e = Employer::loadFromDatabase($link, $emp_id);
				}

				$loc = new Location();
				$loc->short_name = "Main Site";
				$loc->full_name = "Main Site";
				$loc->organisations_id = $e->id;
				$loc->postcode = DAO::getSinglevalue($link, "select A45 from aim where A44 = '$a44' order by A44 desc LIMIT 0,1");
				$loc->is_legal_address = 1;
				$loc->save($link);
				
			}
		}

		// Create Learners
		DAO::execute($link, "DELETE FROM users where type = 5;");
		DAO::execute($link, "truncate tr");

			$sql = <<<HEREDOC
select * from learner
HEREDOC;
		
		$st = $link->query($sql);
		if($st) 
		{
			while($row = $st->fetch())
			{
				// Create a learner
				$user = new User();
				$user->firstnames = trim($row['L10']);
				$user->surname = trim($row['L09']);
				$user->username = $row['L03'];
				$user->dob = $row['L11'];
				$user->password = "password";
				$user->record_status = 1;
				$user->ni = $row['L26'];
				$user->gender = $row['L13'];
				$user->ethnicity = $row['L12'];
				$user->home_address_line_1 = $row['L18'];
				$user->home_address_line_2 = $row['L19'];
				$user->home_address_line_3 = $row['L20'];
				$user->home_address_line_4 = $row['L21'];
				$user->home_postcode = $row['L17'];
				$user->home_telephone = $row['L23'];
				$user->uln = $row['L45'];
				$user->l24 = $row['L24'];
				$user->l14 = $row['L14'];
				$user->l15 = $row['L15'];
				$user->l16 = $row['L16'];
				$user->l34a = $row['L34a'];
				$user->l34b = $row['L34b'];
				$user->l34c = $row['L34c'];
				$user->l34d = $row['L34d'];
				$user->l35 = $row['L35'];
				$user->l36 = $row['L36'];
				$user->l37 = $row['L37'];
				$user->l28a = $row['L28a'];
				$user->l28b = $row['L28b'];
				$user->l39 = $row['L39'];
				$user->l40a = $row['L40a'];
				$user->l40b = $row['L40b'];
				$user->l41a = $row['L41a'];
				$user->l41b = $row['L41b'];
				$user->l47 = $row['L47'];
				$user->l48 = $row['L48'];
				$user->l45 = $row['L45'];

				$L03 = $row['L03'];
				
				$a44 = DAO::getSingleValue($link, "SELECT a44 FROM aim WHERE A03 = '$L03' ORDER BY a44 DESC ,a45 DESC LIMIT 0,1;");
				$a45 = DAO::getSingleValue($link, "SELECT a45 FROM aim WHERE A03 = '$L03' ORDER BY a44 DESC ,a45 DESC LIMIT 0,1;");
				$a4445 = $a44.$a45;

//				$emp_id = DAO::getSingleValue($link, "SELECT organisations.id FROM organisations INNER JOIN locations ON locations.organisations_id = organisations.id WHERE CONCAT(organisations.legal_name,locations.postcode) = '$a4445'");
				$emp_id = DAO::getSingleValue($link, "SELECT organisations.id FROM organisations INNER JOIN locations ON locations.organisations_id = organisations.id WHERE organisations.legal_name = '$a44'");
				
				$location_id = DAO::getSingleValue($link, "SELECT locations.id FROM locations INNER JOIN organisations ON organisations.id = locations.organisations_id WHERE organisations.id = '$emp_id';");
				//if(!$emp_id)
				//	pre("employer " . $a44 . " not found for learner " . $row['L03']);
				$user->employer_id = $emp_id;
				$user->employer_location_id = $location_id;
				$user->type = 5;
				$user->save($link, true);
			}
		}

		$ttg = 0;

		// Create TtG training records and ILRs;
		$sql = "SELECT DISTINCT L03,A15,A26,A09 FROM learner INNER JOIN aim ON aim.A03 = learner.L03 where A15 = 99 and A26 = 0;";
		$submission = DAO::getSingleValue($link, "select submission from central.lookup_submission_dates where contract_type = '2' and last_submission_date>=CURDATE() and contract_year = '2011' order by last_submission_date LIMIT 1;");
		$st = $link->query($sql);
		if($st) 
		{
			while($row = $st->fetch())
			{
				$l03 = $row['L03'];
				$a15 = $row['A15'];
				$a26 = $row['A26'];
				$a09 = $row['A09'];
				$sql2 = "SELECT learner.*,aim.* FROM learner INNER JOIN aim ON aim.A03 = learner.L03 WHERE learner.L03 = '$l03' AND aim.a15 = '$a15' AND aim.a26 = '$a26' and aim.a09 = '$a09';"; 
				$st2 = $link->query($sql2);
				if($st2) 
				{
					$subaim = '';
					$programmeaim = '';
					$mainaim = '';
					$L05 = DAO::getSingleValue($link,"SELECT count(*) FROM learner INNER JOIN aim ON aim.A03 = learner.L03 WHERE learner.L03 = '$l03' AND aim.a15 = '$a15' AND aim.a26 = '$a26';");	
					$a10flag = DAO::getSingleValue($link,"SELECT count(*) FROM learner INNER JOIN aim ON aim.A03 = learner.L03 WHERE learner.L03 = '$l03' AND aim.a15 = '$a15' AND aim.a26 = '$a26' and aim.a10='46';");	
					while($row2 = $st2->fetch())
					{
						$a15 = $row2['A15'];
						$a10 = $row2['A10'];
						$ttg++;
						$ilr = "<ilr><learner>";
						$ilr .= "<L01>" . $row2['L01'] . "</L01>"; 	
						$ilr .= "<L02>" . $row2['L02'] . "</L02>";	//	Contract/ Allocation type
						$ilr .= "<L03>" . $row2['L03'] . "</L03>";	//	Learner Reference Number 
						$ilr .= "<L04>" . $row2['L04'] . "</L04>";	//	Data Set Identifier Code. It defines what type of data set it is. 10 in case of learner data set and 30 in case of subsidiary aims data sets.
						//$ilr .= "<L05>" . $row2['L05'] . "</L05>"; 	// 	How many learning aims data sets inner loop
						$ilr .= "<L05>2</L05>"; 	// 	How many learning aims data sets inner loop
						$ilr .= "<L07>" . $row2['L07'] . "</L07>"; 	// 	How many HE data sets. There isn't any in case of Toyota
						$ilr .= "<L08>" . $row2['L08'] . "</L08>";	//	Deletion Flag
						$ilr .= "<L09>" . addslashes((string)$row2['L09']) . "</L09>";
						$ilr .= "<L10>" . addslashes((string)$row2['L10']) . "</L10>";	//	Forenames
						$ilr .= "<L11>" . Date::toShort($row2['L11']) . "</L11>"; // Date of Birth
						$ilr .= "<L12>" . $row2['L12'] . "</L12>";	//	Ethnicity
						$ilr .= "<L13>" . $row2['L13'] . "</L13>";	//	Sex
						$ilr .= "<L14>" . $row2['L14'] . "</L14>";	//	Learning difficulties/ disabilities/ health problems
						$ilr .= "<L15>" . $row2['L15'] . "</L15>";	//	Disability			
						$ilr .= "<L16>" . $row2['L16'] . "</L16>";	//	Learning difficulty
						$ilr .= "<L17>" . $row2['L17'] . "</L17>";	//	Home postcode
						$ilr .= "<L18>" . addslashes((string)$row2['L18']) . "</L18>";	//	Address line 1
						$ilr .= "<L19>" . addslashes((string)$row2['L19']) . "</L19>";	//	Address line 2
						$ilr .= "<L20>" . addslashes((string)$row2['L20']) . "</L20>";	//	Address line 3
						$ilr .= "<L21>" . addslashes((string)$row2['L21']) . "</L21>";	//	Address line 4
						$ilr .= "<L22>" . $row2['L22'] . "</L22>";		//	Current postcode
						$ilr .= "<L23>" . $row2['L23'] . "</L23>";	//	Home telephone
						$ilr .= "<L24>" . $row2['L24'] . "</L24>";	//	Country of domicile
						$ilr .= "<L25>" . $row2['L25'] . "</L25>";	//	LSC Number of funding LSC
						$ilr .= "<L26>" . $row2['L26'] . "</L26>";	//	National insurance number
						$ilr .= "<L27>" . $row2['L27'] . "</L27>";	//	Restricted use indicator
						$ilr .= "<L28a>" . $row2['L28a'] . "</L28a>";	//	Eligibility for enhanced funding
						$ilr .= "<L28b>" . $row2['L28b'] . "</L28b>";	//	Eligibility for enhanced funding
						$ilr .= "<L29>" . $row2['L29'] . "</L29>";	//	Additional support
						$ilr .= "<L31>" . $row2['L31'] . "</L31>";	//	Additional support cost 
						$ilr .= "<L32>" . $row2['L32'] . "</L32>";	//	Eligibility for disadvatnage uplift
						$ilr .= "<L33>" . $row2['L33'] . "</L33>";	//	Disadvatnage uplift factor
						$ilr .= "<L34a>" . $row2['L34a'] . "</L34a>";	//	Learner support reason
						$ilr .= "<L34b>" . $row2['L34b'] . "</L34b>";	//	Learner support reason
						$ilr .= "<L34c>" . $row2['L34c'] . "</L34c>";	//	Learner support reason
						$ilr .= "<L34d>" . $row2['L34d'] . "</L34d>";	//	Learner support reason
						$ilr .= "<L35>" . $row2['L35'] . "</L35>";	//	Prior attainment level
						$ilr .= "<L36>" . $row2['L36'] . "</L36>";	//	Learner status on last working day
						$ilr .= "<L37>" . $row2['L37'] . "</L37>";	//	Employment status on first day of learning
						$ilr .= "<L39>" . $row2['L39'] . "</L39>";	//	Destination
						$ilr .= "<L40a>" . $row2['L40a'] . "</L40a>";	//	National learner monitoring
						$ilr .= "<L40b>" . $row2['L40b'] . "</L40b>";	//	National learner monitoring
						$ilr .= "<L41a>" . $row2['L41a'] . "</L41a>";	//	Local learner monitoring
						$ilr .= "<L41b>" . $row2['L41b'] . "</L41b>";	//	Local learner monitoring
						$ilr .= "<L42a>" . $row2['L42a'] . "</L42a>";	//	Provider specified learner data
						$ilr .= "<L42b>" . $row2['L42b'] . "</L42b>";	//	Provider specified learner data
						$ilr .= "<L44>" . $row2['L44'] . "</L44>";	//	NES delivery LSC number
						$ilr .= "<L45>" . $row2['L45'] . "</L45>";	//	Unique learner number
						$ilr .= "<L46>" . $row2['L46'] . "</L46>";	
						$ilr .= "<L47>" . $row2['L47'] . "</L47>";	//	Current employment status
						$ilr .= "<L48>" . Date::toShort($row2['L48']) . "</L48>"; // Date employment status changed
						$ilr .= "<L49a>" . $row2['L49a'] . "</L49a>";	//	Current employment status
						$ilr .= "<L49b>" . $row2['L49b'] . "</L49b>";	//	Current employment status
						$ilr .= "<L49c>" . $row2['L49c'] . "</L49c>";	//	Current employment status
						$ilr .= "<L49d>" . $row2['L49d'] . "</L49d>";	//	Current employment status
						$ilr .= "<L51>" . $row2['L51'] . "</L51>";	
						$ilr .= "<L52>" . $row2['L52'] . "</L52>";	
						$ilr .= "</learner>";
						$ilr .= "<subaims>" . 0 . "</subaims>";	//	Subaims
						$ilr .= "<programmeaim>";
						$ilr .= $this->createAim($row2);
						$ilr .= "</programmeaim>";					
						$ilr .= "<main>";
						$ilr .= $this->createAim($row2);
						$ilr .= "</main>";					
						$ilr .= "</ilr>";

						$user = User::loadFromDatabase($link, $row2['L03']);
						$tr = new TrainingRecord();
						$tr->populate($user, true);
						if($tr->id>0)
							$tr->id = NULL;
						$tr->contract_id = 23;
						$tr->start_date = $row2['A27'];
						$tr->target_date = $row2['A28'];
						//$tr->closure_date = $row['A31'];
						$tr->status_code = 1;
						$tr->ethnicity = $user->ethnicity;
						$tr->work_experience = 1;
						$tr->l03 = $row2['L03'];
						$tr->save($link);

						DAO::execute($link, "update aim set processid = {$tr->id} WHERE a03 = '$l03' AND a15 = '$a15' AND a26 = '$a26';");

						$L01 = $row2['L01'];
						$L03 = $row2['L03'];
						$A09m = $row2['A09'];
						$contract_type = 1;
						$tr_id = $tr->id;
						$is_complete = 0;
						$is_valid = 0;
						$is_approved = 0;
						$is_active = 1;
						$contract_id = 23;
                        $ilr = str_replace("'","",$ilr);
						$query = "insert into ilr VALUES('$L01','$L03','$A09m','$ilr','$submission','$contract_type','$tr_id','$is_complete','$is_valid','$is_approved','$is_active','$contract_id');";
						DAO::execute($link, $query);
					}		
				}
			}
		}


		// Create Apps training records and ILRs;
		$sql = "SELECT DISTINCT A03, A15, A26 FROM aim WHERE A15!=99 AND A26!=0";
		$submission = DAO::getSingleValue($link, "select submission from central.lookup_submission_dates where contract_type = '2' and last_submission_date>=CURDATE() and contract_year = '2011' order by last_submission_date LIMIT 1;");
		$st = $link->query($sql);
		if($st)
		{
			while($row = $st->fetch())
			{
				$l03 = $row['A03'];
				$a15 = $row['A15'];
				$a26 = $row['A26'];
				$sql2 = "SELECT learner.*,aim.* FROM learner INNER JOIN aim ON aim.A03 = learner.L03 WHERE learner.L03 = '$l03' AND aim.a15 = '$a15' AND aim.a26 = '$a26'";
				$st2 = $link->query($sql2);
				if($st2)
				{
					$sub = '';
					$zprog = '';
					$main = '';
					$L05 = DAO::getSingleValue($link,"SELECT count(*) FROM learner INNER JOIN aim ON aim.A03 = learner.L03 WHERE learner.L03 = '$l03' AND aim.a15 = '$a15' AND aim.a26 = '$a26'");
					//$a10flag = DAO::getSingleValue($link,"SELECT count(*) FROM learner INNER JOIN aim ON aim.A03 = learner.L03 WHERE learner.L03 = '$l03' AND aim.a15 = '$a15' AND aim.a26 = '$a26' and aim.a10='46' and aim.A34=6;");
					while($row2 = $st2->fetch())
					{
						$a15 = $row2['A15'];
						$a10 = $row2['A10'];
						$l03 = $row2['L03'];

						$ilr = "<ilr><learner>";
						$ilr .= "<L01>" . $row2['L01'] . "</L01>";
						$ilr .= "<L02>" . $row2['L02'] . "</L02>";	//	Contract/ Allocation type
						$ilr .= "<L03>" . $row2['L03'] . "</L03>";	//	Learner Reference Number
						$ilr .= "<L04>" . $row2['L04'] . "</L04>";	//	Data Set Identifier Code. It defines what type of data set it is. 10 in case of learner data set and 30 in case of subsidiary aims data sets.
						//$ilr .= "<L05>" . $row2['L05'] . "</L05>"; 	// 	How many learning aims data sets inner loop
						$ilr .= "<L05>" . $L05 . "</L05>"; 	// 	How many learning aims data sets inner loop
						$ilr .= "<L07>" . $row2['L07'] . "</L07>"; 	// 	How many HE data sets. There isn't any in case of Toyota
						$ilr .= "<L08>" . $row2['L08'] . "</L08>";	//	Deletion Flag
						$ilr .= "<L09>" . addslashes((string)$row2['L09']) . "</L09>";
						$ilr .= "<L10>" . addslashes((string)$row2['L10']) . "</L10>";	//	Forenames
						$ilr .= "<L11>" . Date::toShort($row2['L11']) . "</L11>"; // Date of Birth
						$ilr .= "<L12>" . $row2['L12'] . "</L12>";	//	Ethnicity
						$ilr .= "<L13>" . $row2['L13'] . "</L13>";	//	Sex
						$ilr .= "<L14>" . $row2['L14'] . "</L14>";	//	Learning difficulties/ disabilities/ health problems
						$ilr .= "<L15>" . $row2['L15'] . "</L15>";	//	Disability
						$ilr .= "<L16>" . $row2['L16'] . "</L16>";	//	Learning difficulty
						$ilr .= "<L17>" . $row2['L17'] . "</L17>";	//	Home postcode
						$ilr .= "<L18>" . addslashes((string)$row2['L18']) . "</L18>";	//	Address line 1
						$ilr .= "<L19>" . addslashes((string)$row2['L19']) . "</L19>";	//	Address line 2
						$ilr .= "<L20>" . addslashes((string)$row2['L20']) . "</L20>";	//	Address line 3
						$ilr .= "<L21>" . addslashes((string)$row2['L21']) . "</L21>";	//	Address line 4
						$ilr .= "<L22>" . $row2['L22'] . "</L22>";		//	Current postcode
						$ilr .= "<L23>" . $row2['L23'] . "</L23>";	//	Home telephone
						$ilr .= "<L24>" . $row2['L24'] . "</L24>";	//	Country of domicile
						$ilr .= "<L25>" . $row2['L25'] . "</L25>";	//	LSC Number of funding LSC
						$ilr .= "<L26>" . $row2['L26'] . "</L26>";	//	National insurance number
						$ilr .= "<L27>" . $row2['L27'] . "</L27>";	//	Restricted use indicator
						$ilr .= "<L28a>" . $row2['L28a'] . "</L28a>";	//	Eligibility for enhanced funding
						$ilr .= "<L28b>" . $row2['L28b'] . "</L28b>";	//	Eligibility for enhanced funding
						$ilr .= "<L29>" . $row2['L29'] . "</L29>";	//	Additional support
						$ilr .= "<L31>" . $row2['L31'] . "</L31>";	//	Additional support cost
						$ilr .= "<L32>" . $row2['L32'] . "</L32>";	//	Eligibility for disadvatnage uplift
						$ilr .= "<L33>" . $row2['L33'] . "</L33>";	//	Disadvatnage uplift factor
						$ilr .= "<L34a>" . $row2['L34a'] . "</L34a>";	//	Learner support reason
						$ilr .= "<L34b>" . $row2['L34b'] . "</L34b>";	//	Learner support reason
						$ilr .= "<L34c>" . $row2['L34c'] . "</L34c>";	//	Learner support reason
						$ilr .= "<L34d>" . $row2['L34d'] . "</L34d>";	//	Learner support reason
						$ilr .= "<L35>" . $row2['L35'] . "</L35>";	//	Prior attainment level
						$ilr .= "<L36>" . $row2['L36'] . "</L36>";	//	Learner status on last working day
						$ilr .= "<L37>" . $row2['L37'] . "</L37>";	//	Employment status on first day of learning
						$ilr .= "<L39>" . $row2['L39'] . "</L39>";	//	Destination
						$ilr .= "<L40a>" . $row2['L40a'] . "</L40a>";	//	National learner monitoring
						$ilr .= "<L40b>" . $row2['L40b'] . "</L40b>";	//	National learner monitoring
						$ilr .= "<L41a>" . $row2['L41a'] . "</L41a>";	//	Local learner monitoring
						$ilr .= "<L41b>" . $row2['L41b'] . "</L41b>";	//	Local learner monitoring
						$ilr .= "<L42a>" . $row2['L42a'] . "</L42a>";	//	Provider specified learner data
						$ilr .= "<L42b>" . $row2['L42b'] . "</L42b>";	//	Provider specified learner data
						$ilr .= "<L44>" . $row2['L44'] . "</L44>";	//	NES delivery LSC number
						$ilr .= "<L45>" . $row2['L45'] . "</L45>";	//	Unique learner number
						$ilr .= "<L46>" . $row2['L46'] . "</L46>";
						$ilr .= "<L47>" . $row2['L47'] . "</L47>";	//	Current employment status
						$ilr .= "<L48>" . Date::toShort($row2['L48']) . "</L48>"; // Date employment status changed
						$ilr .= "<L49a>" . $row2['L49a'] . "</L49a>";	//	Current employment status
						$ilr .= "<L49b>" . $row2['L49b'] . "</L49b>";	//	Current employment status
						$ilr .= "<L49c>" . $row2['L49c'] . "</L49c>";	//	Current employment status
						$ilr .= "<L49d>" . $row2['L49d'] . "</L49d>";	//	Current employment status
						$ilr .= "<L51>" . $row2['L51'] . "</L51>";
						$ilr .= "<L52>" . $row2['L52'] . "</L52>";
						$ilr .= "</learner>";
						$ilr .= "<subaims>" . 0 . "</subaims>";	//	Subaims

						if($row2['A09']=='ZPROG001')
						{
							$zprog .= "<programmeaim>" . $this->createAim($row2) . "</programmeaim>";
							$A27 = $row2['A27'];
							$A28 = $row2['A28'];
							$L01 = $row2['L01'];
							$l03 = $row2['L03'];
						}
						elseif($row2['A10']=='46' && $main=='')
						{
							$main .= "<main>" . $this->createAim($row2) . "</main>";
							$A09m = $row2['A09'];
						}
						else
						{
							$sub .= "<subaim>" . $this->createAim($row2) . "</subaim>";
						}
					}
				}

               // if($zprog=='')
                 //   pre($ilr);

				$ilr = $ilr . $zprog . $main . $sub . "</ilr>";
				$user = User::loadFromDatabase($link, $l03);
				$tr = new TrainingRecord();
				$tr->populate($user, true);
				if($tr->id>0)
					$tr->id = NULL;
				$tr->contract_id = 23;
				$tr->start_date = $A27;
				$tr->target_date = $A28;
				//$tr->closure_date = $row['A31'];
				$tr->status_code = 1;
				$tr->ethnicity = $user->ethnicity;
				$tr->work_experience = 1;
				$tr->l03 = $l03;
				$tr->save($link);

				DAO::execute($link, "update aim set processid = {$tr->id} WHERE a03 = '$l03' AND a15 = '$a15' AND a26 = '$a26'");

				$L01 = $row2['L01'];
				$L03 = $l03;
				$A09m = $row2['A09'];

				$contract_type = 1;
				$tr_id = $tr->id;
				$is_complete = 0;
				$is_valid = 0;
				$is_approved = 0;
				$is_active = 1;
				$contract_id = 23;

				$query = "insert into ilr VALUES('$L01','$L03','$A09m','$ilr','$submission','$contract_type','$tr_id','$is_complete','$is_valid','$is_approved','$is_active','$contract_id');";
				DAO::execute($link, $query);
			}
		}




	// Set A09
/*		$sql = "SELECT * from ilr";
		$st = $link->query($sql);
		if($st) 
		{
			while($row = $st->fetch())
			{
				$tr_id = $row['tr_id'];
				$xml = $row['ilr'];
				$submission = $row['submission'];
				$contract_id = $row['contract_id'];
				//$pageDom = new DomDocument();
				//$pageDom->loadXML($xml);
				$pageDom = XML::loadXmlDom($xml);
				$e = $pageDom->getElementsByTagName('main');
				foreach($e as $node)
				{
					$a09 = $node->getElementsByTagName('A09')->item(0)->nodeValue;
				}

				DAO::execute($link, "update ilr set a09 = '$a09' where tr_id='$tr_id' and submission = '$submission' and contract_id = '$contract_id'");
			}
		}
*/

	}
		
	function createAim($row2)
	{
		$ilr = "<A01>" . $row2['A01'] . "</A01>";
		$ilr .= "<A02>" . $row2['A02'] . "</A02>";	//	Contract/ Allocation Type
		$ilr .= "<A03>" . $row2['A03'] . "</A03>";	//	Learner reference number
		$ilr .= "<A04>" . $row2['A04'] . "</A04>";	//	Data set identifier code
		$ilr .= "<A05>" . $row2['A05'] . "</A05>";	//	Learning aim data set sequence
		$ilr .= "<A07>" . $row2['A07'] . "</A07>";	//	HE data sets
		$ilr .= "<A08>" . $row2['A08'] . "</A08>";	//	Data set format
		$ilr .= "<A09>" . $row2['A09'] . "</A09>";	//	Learning aim reference
		$ilr .= "<A10>" . $row2['A10'] . "</A10>";	//	LSC funding stream
		$ilr .= "<A11a>" . $row2['A11a'] . "</A11a>";	//	Source of funding
		$ilr .= "<A11b>" . $row2['A11b'] . "</A11b>";	//	Source of funding
		$ilr .= "<A13>" . $row2['A13'] . "</A13>";	//	Tuition fee received for year
		$ilr .= "<A14>" . $row2['A14'] . "</A14>";	//	Reason for partial or full non-peyment of tuition fee
		$ilr .= "<A15>" . $row2['A15'] . "</A15>";	//	Programme type
		$ilr .= "<A16>" . $row2['A16'] . "</A16>";	//	Programme entry route
		$ilr .= "<A17>" . $row2['A17'] . "</A17>";	//	Delivery mode
		$ilr .= "<A18>" . $row2['A18'] . "</A18>";	//	Main delivery method
		$ilr .= "<A19>" . $row2['A19'] . "</A19>";	//	Employer role
		$ilr .= "<A20>" . $row2['A20'] . "</A20>";	//	Resit
		$ilr .= "<A21>" . $row2['A21'] . "</A21>";	//	Franchised out and partnership arrangement
		$ilr .= "<A22>" . $row2['A22'] . "</A22>";	//	Franchised out and partnership delivery provider number
		$ilr .= "<A23>" . $row2['A23'] . "</A23>";	//	Delivery location postcode
		$ilr .= "<A26>" . $row2['A26'] . "</A26>";	//	Sector framework of learning 
		$ilr .= "<A27>" . Date::toShort($row2['A27']) . "</A27>"; // Learning start date
		$ilr .= "<A28>" . Date::toShort($row2['A28']) . "</A28>"; // Learning planned end date
		$ilr .= "<A31>" . Date::toShort($row2['A31']) . "</A31>"; // Learning actual end date
		$ilr .= "<A32>" . $row2['A32'] . "</A32>";	//	Guided learning hours
		$ilr .= "<A34>" . $row2['A34'] . "</A34>";	//	Completion status
		$ilr .= "<A35>" . $row2['A35'] . "</A35>";	//	Learning outcome
		$ilr .= "<A36>" . $row2['A36'] . "</A36>";	//	Learning outcome grade
		$ilr .= "<A40>" . Date::toShort($row2['A40']) . "</A40>"; // Achivement date
		$ilr .= "<A44>" . $row2['A44'] . "</A44>";	//	Employer identifier
		$ilr .= "<A45>" . $row2['A45'] . "</A45>";	//	Workplace location postcode
		$ilr .= "<A46a>" . $row2['A46a'] . "</A46a>";	//	National learning aim monitoring
		$ilr .= "<A46b>" . $row2['A46b'] . "</A46b>";	//	National learning aim monitoring
		$ilr .= "<A47a>" . $row2['A47a'] . "</A47a>";	//	Local learning aim monitoring
		$ilr .= "<A47b>" . $row2['A47b'] . "</A47b>";	//	Local learning aim monitoring
		$ilr .= "<A48a>" . $row2['A48a'] . "</A48a>";	//	Provider specified learning aim data
		$ilr .= "<A48b>" . $row2['A48b'] . "</A48b>";	//	Provider specified learning aim data
		$ilr .= "<A49>" . $row2['A49'] . "</A49>";	//	Special projects and pilots
		$ilr .= "<A50>" . $row2['A50'] . "</A50>";	//	Reason learning ended
		$ilr .= "<A51a>" . $row2['A51a'] . "</A51a>";	//	Proportion of funding remaining
		$ilr .= "<A52>" . $row2['A52'] . "</A52>";	//	Distance learning funding
		$ilr .= "<A53>" . $row2['A53'] . "</A53>";	//	Additional learning needs
		$ilr .= "<A54>" . $row2['A54'] . "</A54>";	//	Broker contract number
		$ilr .= "<A55>" . $row2['A55'] . "</A55>";	//	Unique learner number
		$ilr .= "<A56>" . $row2['A56'] . "</A56>";	//	UK Provider reference number
		$ilr .= "<A57>" . $row2['A57'] . "</A57>";	//	Source of tuition fees
		$ilr .= "<A58>" . $row2['A58'] . "</A58>";	//	Source of tuition fees
		$ilr .= "<A59>" . $row2['A59'] . "</A59>";	//	Source of tuition fees
		$ilr .= "<A60>" . $row2['A60'] . "</A60>";	//	Source of tuition fees
		$ilr .= "<A61>" . $row2['A61'] . "</A61>";	//	Source of tuition fees
		$ilr .= "<A62>" . $row2['A62'] . "</A62>";	//	Source of tuition fees
		$ilr .= "<A63>" . $row2['A63'] . "</A63>";	//	Source of tuition fees
		$ilr .= "<A64>" . $row2['A64'] . "</A64>";	//	Source of tuition fees
		$ilr .= "<A65>" . $row2['A65'] . "</A65>";	//	Source of tuition fees
		$ilr .= "<A66>" . $row2['A66'] . "</A66>";	//	Source of tuition fees
		$ilr .= "<A67>" . $row2['A67'] . "</A67>";	//	Source of tuition fees
		$ilr .= "<A68>" . $row2['A68'] . "</A68>";	//	Source of tuition fees
		$ilr .= "<A69>" . $row2['A69'] . "</A69>";	//	Source of tuition fees
		$ilr .= "<A70>" . $row2['A70'] . "</A70>";	//	Source of tuition fees
		$ilr .= "<A71>" . $row2['A71'] . "</A71>";	//	Source of tuition fees
		$ilr .= "<A72a>" . $row2['A72a'] . "</A72a>";	//	Source of tuition fees
		$ilr .= "<A72b>" . $row2['A72b'] . "</A72b>";	//	Source of tuition fees
		return $ilr;
	}
}
?>