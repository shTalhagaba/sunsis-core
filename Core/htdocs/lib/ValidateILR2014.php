<?php
class ValidateILR2014
{
	public function validate(PDO $link, $ilr)
	{

		$class = new ReflectionClass(__CLASS__);
		$methods = $class->getMethods();
		$rep = 'No Error';
		// Create separate connections for lis and lad
		foreach($methods as $method)
		{
			if(preg_match('/^rule/', $method->getName()) > 0)
			{
				$method_name = $method->getName();

				$res = $this->$method_name($link, $ilr);

				if($res!='')
					$rep .= "<error>" . $res . "</error>";

			}
		}

		if($rep!='No Error')
			$rep = '<report>' . $rep . '</report>';

		return $rep;
	}

	private function DD07($_progType)
	{
		if(in_array($_progType, array('2', '3', '10', '20', '21', '22', '23', '25')))
			return 'Y';
		else
			return 'N';
	}

	private function rule_FundModel_01($link, $ilr)
	{
		$FundModel = '';

		foreach($ilr->LearningDelivery as $delivery)
			if($delivery->FundModel=='' || $delivery->FundModel=='undefined' || !in_array($delivery->FundModel, DAO::getSingleColumn($link, "SELECT FundModel FROM lis201415.`ilr_fundmodel`;")))
				return "FundModel_01: The funding model for aim " . $delivery->LearnAimRef . " is not a valid code. ";
	}

	private function rule_ULN_01($link, $ilr)
	{
		$ULN = (string)$ilr->ULN;
		if($ULN=='')
		{
			return "ULN_01: The Unique learner number has not been returned";
		}
	}

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

	private function rule_ULN_08($link, $ilr)
	{
		$ULN = (string)$ilr->ULN;
		$days = false;
		foreach($ilr->LearningDelivery as $delivery)
		{
			$LearnStartDate = Date::toMySQL($delivery->LearnStartDate);
			$CurrentDate = Date::toMySQL(date('d/m/Y'));
			$diff = DAO::getSingleValue($link, "select DATEDIFF('$CurrentDate','$LearnStartDate')");
			if($diff>15)
				$days = true;

			$ldm = false;
			foreach($delivery->LearningDeliveryFAM as $ldf)
			{
				if($ldf->LearnDelFAMType=='ADL' && $ldf->LearnDelFAMCode=='1')
					$ldm = true;
			}

		}
		if($days && $ULN=='9999999999' && $ldm && DB_NAME!='am_direct')
		{
			return "ULN_08: If the learner's learning aim is financed by a  24+ Advanced Learning Loan,  the Unique learner number must not be 9999999999 if the Learning start date of the aim is > 14 days before the file preparation date, unless the learner is an OLASS - Offender in Custody";
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
// Learner Fields
	private function rule_LearnRefNumber_01($link, $ilr)
	{
		$LearnRefNumber = (string)$ilr->LearnRefNumber;
		if($LearnRefNumber=='')
		{
			return "LearnRefNumber_01: The Learner reference number must be returned :1";
		}
	}


	private function rule_LLDDHealthProb_04($link, $ilr)
	{
		if($ilr->LLDDHealthProb=='2' && $ilr->LLDDandHealthProblem->LLDDType!='')
			return  "LLDDHealthProb_04: If the learner's LLDD and health problem is 'Learner does not consider himself or herself to have a learning difficulty and/or disability or health problem' then an LLDD and Health Problem entity must not be returned :1";
	}

	private function rule_LLDDHealthProb_06($link, $ilr)
	{
		if($ilr->LLDDHealthProb=='1' && $ilr->LLDDandHealthProblem->LLDDType=='')
			return  "LLDDHealthProb_06: If the learner's LLDD and health problem is 'Learner considers himself or herself to have a learning difficulty and/or disability or health problem' then an LLDD and Health Problem entity must be returned :1";
	}

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

	private function rule_Dest_01($link, $ilr)
	{
		if($ilr->Dest=='')
			return  "Dest_01: The Destination must be returned :1";
	}

	// Dest_02 is sorted through dropdown

	private function rule_Dest_03($link, $ilr)
	{
		$Dest = $ilr->Dest;
		foreach($ilr->LearningDelivery as $delivery)
		{
			$AimType = $delivery->AimType;
			$LearnActEndDate = $delivery->LearnActEndDate;
			if($AimType!='1' && $LearnActEndDate=='' && $Dest!='95')
			{
				return $LearnActEndDate . "Dest_03: If the learning aim is not a programme aim and the Learning actual end date is not returned, then the Destination must be continuing :1";
			}
		}
	}

	private function rule_LocType_01($link, $ilr)
	{
		foreach($ilr->LearnerContact as $lc)
		{
			$LocType = $lc->LocType;
			$PostAdd = $lc->PostAdd;
			if($LocType=='1' && $PostAdd=='')
			{
				return "LocType_01: The Locator type is Postal Address and a  corresponding Postal Address�value�has not been returned :1";
			}
		}
	}

	private function rule_LocType_02($link, $ilr)
	{
		foreach($ilr->LearnerContact as $lc)
		{
			$LocType = $lc->LocType;
			$PostCode = $lc->PostCode;
			if($LocType=='2' && $PostCode=='')
			{
				return "LocType_02: The Locator type is Postcode and a corresponding PostCode value has not been returned :1";
			}
		}
	}

	private function rule_LocType_03($link, $ilr)
	{
		foreach($ilr->LearnerContact as $lc)
		{
			$LocType = $lc->LocType;
			$TelNumber = $lc->TelNumber;
			if($LocType=='3' && $TelNumber=='')
			{
				return "LocType_03: The Locator type is Telephone and a corresponding Telephone value has not been returned :1";
			}
		}
	}

	private function rule_LocType_04($link, $ilr)
	{
		foreach($ilr->LearnerContact as $lc)
		{
			$LocType = $lc->LocType;
			$Email = $lc->Email;
			if($LocType=='4' && $Email=='')
			{
				return "LocType_04: The Locator type is Email address and a corresponding Email value has not been returned :1";
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
			return "AddLine1: The Address line 1 must exist and not be null :1";
		}
	}

	private function rule_PostCode_01($link, $ilr)
	{
		foreach($ilr->LearnerContact as $lc)
		{
			$xpath = $ilr->xpath("/Learner/LearnerContact[ContType='1' and LocType='2']/PostCode");
			$ppe = (empty($xpath))?'':$xpath[0];
			if($ilr->Domicile=='XF' && $ppe=='')
			{
				return "PostCode_01: If the Country of domicile code is UK, the Postcode prior to enrolment must exist and not be null \n";
			}
		}
	}

	private function rule_PostCode_08($link, $ilr)
	{
		foreach($ilr->LearnerContact as $lc)
		{
			$LocType = $lc->LocType;
			$ContType = $lc->ContType;
			$PostCode = $lc->PostCode;
			if($LocType=='2' && ($ContType=='1' || $ContType=='2') && $PostCode!='')
			{
				$check = substr($PostCode,(strpos($PostCode," ")+1),3);
				$first = substr($check,0,1);
				$second = substr($check,1,1);
				$third = substr($check,2,1);
				if(ord($first)<48 || ord($first)>57 || ord($second)<65 || ord($second)>90 || ord($third)<65 || ord($third)>90 || $second=='C' || $second=='I' || $second=='K' || $second=='M' || $second=='O' || $second=='V' || $third=='C' || $third=='I' || $third=='K' || $third=='M' || $third=='O' || $third=='V')
					if($check!='ZZZ')
						return "PostCode_08: If returned, the second part of the Postcode must conform to the valid postcode format :1";
			}
		}
	}

    private function rule_LearnFAMType_12($link, $ilr)
    {
        $ega = '';
        foreach($ilr->LearnerFAM as $learnerfam)
            if($learnerfam->LearnFAMType=='MGA')
                $ega = "".$learnerfam->LearnFAMCode;

        if($ega=='')
            foreach($ilr->LearningDelivery as $delivery)
                if($delivery->FundModel=='25')
                    return "LearnFAMType_12: The learning aim is EFA funded and no Maths GCSE achievement has been returned. ";
    }

    private function rule_LearnFAMType_13($link, $ilr)
    {
        $ega = '';
        foreach($ilr->LearnerFAM as $learnerfam)
            if($learnerfam->LearnFAMType=='EGA')
                $ega = "".$learnerfam->LearnFAMCode;

        if($ega=='')
            foreach($ilr->LearningDelivery as $delivery)
                if($delivery->FundModel=='25')
                    return "LearnFAMType_13: The learning aim is EFA funded and no English GCSE achievement has been returned. ";
    }

	private function rule_PwayCode_01($link, $ilr)
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
					return "PwayCode_01: There must be a valid record in the Frameworks table in LARA for this Framework code, Programme type and Apprenticeship pathway for this learning aim ";
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
				return "PwayCode_03: If the learning aim is part of an Apprenticeship programme, the Apprenticeship pathway must be returned ";
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
                    return "WorkPlaceStartDate_01: A work experience aim or the Supported Internship learning aim has been returned and the Work Placement record has not been returned ";
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
				if($delivery->FundModel=='35' && ($delivery->ProgType=='2' || $delivery->ProgType=='3' || $delivery->ProgType=='10' || $delivery->ProgType=='20' || $delivery->ProgType=='21'))
				{
					$LearnStartDate = new Date(Date::toMySQL($delivery->LearnStartDate));
					$error = true;
					foreach($ilr->LearnerEmploymentStatus as $les)
					{
						if($les->DateEmpStatApp == '' || $les->DateEmpStatApp == 'undefined')
							return 'Date Employment Status Applies must not be left blank.';
						$DateEmpStatApp = new Date(Date::toMySQL($les->DateEmpStatApp));
						if( ($DateEmpStatApp->getDate()==$LearnStartDate->getDate() || $DateEmpStatApp->before($LearnStartDate->formatMySQL())) && trim($les->EmpId)!='')
							$error = false;
					}
					if($error )
					{
						return "EmpId_10: If the learner is undertaking an Apprenticeship programme and is 'in paid employment' on the programme start date then there must be an Employer id with a Date employment status which applies on or before to the programme start date \n";
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

	private function rule_EmpId_13($link, $ilr)
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
	}

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
					return "ESMType_02: If Employment status is 'In paid employment' and the Date employment status applies is on or after 1 August 2012, then an Employment intensity indicator must be returned   \n";
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
					return "ESMType_08: If Employment status is 'Not in paid employment' then the Length of unemployment must be returned if the Date employment status applies to is on or after 1 August 2012  \n";
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
			if($les->ProgType!='' && $les->FworkCode!='')
				$Pway[] = "".$les->PwayCode;
		}
		if(sizeof(array_unique($Pway))>1)
			return sizeof(array_unique($Pway)) . "R29: All open aims that are part of a programme must have the same Framework code and Apprenticeship pathway (where completed)";
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

	private function rule_LearnAimRef_01($link, $ilr)
	{
		foreach($ilr->LearningDelivery as $delivery)
		{
			$found = DAO::getSingleValue($link, "SELECT COUNT(*) FROM lars201415.Core_LARS_LearningDelivery WHERE LearnAimRef = '$delivery->LearnAimRef';");
			if($found=='0' && $delivery->LearnAimRef!='ZPROG001' && $delivery->LearnAimRef!='Z0007835' && $delivery->LearnAimRef!='CMISC001' && $delivery->LearnAimRef!='ZVOC0004' && $delivery->LearnAimRef!='PLTS' && $delivery->LearnAimRef!='CFAERR1')
			{
				return "LearnAimRef_01: The Learning aim reference must be a valid lookup on LARA :".$delivery->LearnAimRef;
			}
		}
	}

	private function rule_LearnAimRef_02($link, $ilr)
	{
		foreach($ilr->LearningDelivery as $delivery)
		{
			if($delivery->ProgType=='99')
			{
				$found = DAO::getSingleValue($link, "select count(*) from lad201213.validity_details where LEARNING_AIM_REF = '$delivery->LearnAimRef' AND (FUND_MODEL_ILR_SUBSET_CODE='ER_OTHER' OR FUND_MODEL_ILR_SUBSET_CODE='ANY')");
				if($found=='0')
				{
					return "LearnAimRef_02: If the Learning aim is not part of an Apprenticeship Programme, the Learning aim reference must exist in the validity details table on LARA :".$delivery->AimSeqNumber;
				}
			}
		}
	}

	private function rule_LearnAimRef_03($link, $ilr)
	{
		foreach($ilr->LearningDelivery as $delivery)
		{
			if($delivery->ProgType!='' && $delivery->FundModel=='35')
			{
				$found = DAO::getSingleValue($link, "SELECT COUNT(*) FROM lars201415.Core_LARS_Validity WHERE LearnAimRef = '$delivery->LearnAimRef'");
				if($found=='0')
				{
					// return "LearnAimRef_03: If the Learning aim is part of an ER funded Apprenticeship Programme, the Learning aim reference must exist in the validity details table on LARA  \n";
					return "LearnAimRef_03: This learning aim (".$delivery->LearnAimRef.") does not appear in the list for the ER funded Apprenticeship Programme :".$delivery->AimSeqNumber;
				}
			}
		}
	}


	private function rule_LearnAimRef_05($link, $ilr)
	{
		foreach($ilr->LearningDelivery as $delivery)
		{
			if($delivery->FundModel=='22')
			{
				$found = DAO::getSingleValue($link, "select count(*) from lad201213.validity_details where LEARNING_AIM_REF = '$delivery->LearnAimRef' AND FUND_MODEL_ILR_SUBSET_CODE='ADULT_LR'");
				if($found=='0')
				{
					return "LearnAimRef_05: If the Learning aim is funded through the Adult learner responsive model, the Learning aim reference code must exist in the validity details table on LARA  :".$delivery->AimSeqNumber;
				}
			}
		}
	}

	private function rule_LearnStartDate_02($link, $ilr)
	{
		foreach($ilr->LearningDelivery as $delivery)
		{
			if(!isset($delivery->LearnStartDate) OR $delivery->LearnStartDate == '' OR $delivery->LearnStartDate == 'undefined') return $delivery->LearnAimRef . ' - Learning Start Date is blank';
			$LearnStartDate = new Date($delivery->LearnStartDate);
			if($LearnStartDate->before('01/08/2002'))
			{
				return $delivery->LearnAimRef . " - LearnStartDate_02: The Learning start date must not be more than 10 years ago :".$delivery->AimSeqNumber;
			}
		}
	}

	private function rule_LearnStartDate_03($link, $ilr)
	{
		foreach($ilr->LearningDelivery as $delivery)
		{
			$LearnStartDate = new Date($delivery->LearnStartDate);
			if(($delivery->FundModel=='21' || $delivery->FundModel=='22' || $delivery->FundModel=='70' || $delivery->FundModel=='10')&& $LearnStartDate->after('31/07/2015'))
			{
				return $delivery->LearnAimRef . " - LearnStartDate_03: The Learning start date must not be after the current teaching year :".$delivery->AimSeqNumber;
			}
		}
	}


	private function rule_LearnStartDate_05($link, $ilr)
	{
		if(!isset($ilr->DateOfBirth) || $ilr->DateOfBirth == '')
			return "Learner Date of Birth is blank ";
		foreach($ilr->LearningDelivery as $delivery)
		{
			if(!isset($delivery->LearnStartDate) OR $delivery->LearnStartDate == '' OR $delivery->LearnStartDate == 'undefined') return $delivery->LearnAimRef . ' - Learning Start Date is blank';
			$LearnStartDate = new Date($delivery->LearnStartDate);
			if($LearnStartDate->before($ilr->DateOfBirth))
			{
				return $delivery->LearnAimRef . " - LearnStartDate_05: The Learning start date must be after the learner's Date of birth :".$delivery->AimSeqNumber;
			}
		}
	}

	private function rule_LearnStartDate_06($link, $ilr)
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
					return $delivery->LearnAimRef . " - LearnStartDate_06: If the Framework code is returned, then the learner must not start the programme after the 'Effective to' date in the Framework table in LARA, for this framework, if the learner is a new start \n";
				}
			}
		}
	}

	private function rule_LearnStartDate_07($link, $ilr)
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
					return $delivery->LearnAimRef . " - LearnStartDate_07: If the Framework code is returned, then the learner must not start the learning aim, if the Learning start date of the programme is after the 'Effective to' date in the Framework aims table in LARA, for this aim on this framework, if the learner is a new start \n";
				}
			}
		}
	}


	private function rule_LearnPlanEndDate_02($link, $ilr)
	{
		foreach($ilr->LearningDelivery as $delivery)
		{
			if(!isset($delivery->LearnStartDate) OR $delivery->LearnStartDate == '' OR $delivery->LearnStartDate == 'undefined') return $delivery->LearnAimRef . ' - Learning Start Date is blank';
			if(!isset($delivery->LearnPlanEndDate) OR $delivery->LearnPlanEndDate == '' OR $delivery->LearnPlanEndDate == 'undefined') return $delivery->LearnAimRef . ' - Learning Planned End Date is blank';
			$LearnPlanEndDate = new Date($delivery->LearnPlanEndDate);
			if($LearnPlanEndDate->before($delivery->LearnStartDate))
			{
				return "LearnPlanEndDate_02: The Learning planned end date must not be before the Learning start date :".$delivery->AimSeqNumber;
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
					return "AchDate_05: If returned, the Achievement date must be on or after the Learning actual end date \n";
			}
		}
	}

	private function rule_AchDate_06($link, $ilr)
	{
		foreach($ilr->LearningDelivery as $delivery)
		{
			if( ($delivery->AchDate=='' || $delivery->AchDate=='undefined' || $delivery->AchDate=='dd/mm/yyyy') && $delivery->Outcome=='1' && $delivery->FundModel=='35')
			{
				return "AchDate_06: If the learning aim is part of an Apprenticeship programme or is Workplace learning and the Outcome is 'Achieved' then the Achievement date must be returned \n";
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
					return "LearnActEndDate_01: The learning actual end date must not be before the learning start date \n";
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
					return "LearnActEndDate_04: The Learning actual end date must not be after the file preparation date/entry date \n";
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
					return "AchDate_07: If returned, the Achievement date must not be after the file preparation date/entry date \n";
			}
		}
	}

	private function rule_Outcome_04($link, $ilr)
	{
		foreach($ilr->LearningDelivery as $delivery)
		{
			if($delivery->AchDate!='' && $delivery->AchDate!='undefined' && $delivery->AchDate!='dd/mm/yyyy')
			{
				if($delivery->Outcome!='1')
					return "Outcome_04: If the Achievement date is returned then the Outcome must be 'Achieved' \n";
			}
		}
	}

	private function rule_ProgType_01($link, $ilr)
	{
		foreach($ilr->LearningDelivery as $delivery)
		{
			if($delivery->ProgType=='' && ($delivery->AimType=='1' || $delivery->AimType=='3'))
			{
				return "ProgType_01: The Programme type must be returned \n";
			}
		}
	}


	private function rule_LearnDelFAMType_01($link, $ilr)
	{
		foreach($ilr->LearningDelivery as $delivery)
		{
			if(($delivery->AimType=='1' || $delivery->AimType=='4') && $delivery->FundModel!='70' && $delivery->FundModel!='99')
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
					return "LearnDelFAMType_01: The Source of funding must be returned  \n";
			}
		}
	}

	private function rule_LearnDelFAMType_02($link, $ilr)
	{
		foreach($ilr->LearningDelivery as $delivery)
		{
			if(($delivery->AimType=='2' || $delivery->AimType=='4' || $delivery->AimType=='3') && $delivery->FundModel!='70' && $delivery->FundModel!='99')
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
					return "LearnDelFAMType_02: The Full or co-funding indicator must be returned for ALR and ER funded learning aims \n" . $delivery->LearnAimRef;
			}
		}
	}

	private function rule_LearnDelFAMType_09($link, $ilr)
	{
		foreach($ilr->LearningDelivery as $delivery)
		{
			if(($delivery->AimType=='1' || $delivery->AimType=='4') && $delivery->FundModel=='35')
			{
				$sof=0;
				foreach($delivery->LearningDeliveryFAM as $ldf)
				{
					if($ldf->LearnDelFAMType=='SOF')
					{
						if($ldf->LearnDelFAMCode=='105')
							$sof++;
					}
				}
				if($sof==0)
					return "LearnDelFAMType_09: One of the Source of funding records must be 105 (Skills Funding Agency)  \n";
			}
		}
	}





	private function rule_FworkCode_01($link, $ilr)
	{
		foreach($ilr->LearningDelivery as $delivery)
		{
			$ProgType = $delivery->ProgType;
			if($delivery->ProgType!='99' && $delivery->ProgType!='' && $delivery->FworkCode=='' && ($ProgType=='2' || $ProgType=='3' || $ProgType=='10' || $ProgType=='20' || $ProgType=='21' || $ProgType=='22' || $ProgType=='23' || $ProgType=='25'))
				return "FworkCode_01: The Framework code must be returned for all aims that are part of a programme :".$delivery->AimSeqNumber;
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

				$first = DAO::getSingleValue($link, "SELECT COUNT(*) FROM lars201415.Core_LARS_FrameworkAims WHERE LearnAimRef = '$LearnAimRef' AND FworkCode = '$FworkCode' AND PwayCode = '$PwayCode' AND ProgType = '$ProgType'");
				//$second = DAO::getSingleValue($link, "SELECT COUNT(*) FROM lars201314.LARS_FrameworkCmnCmpn1314 WHERE LARS_FwkProgType = '$ProgType' AND LARS_FwkSectorCode = '$FworkCode' AND LARS_FwkPway = '$PwayCode' AND LARS_FwkCmnCmpn IN (SELECT LARS_FwkCmnCmpn FROM lars201314.LARS_1314 WHERE LARS_LearnAimRef = '$LearnAimRef');");
				$second = DAO::getSingleValue($link, "SELECT COUNT(*) FROM lars201415.Core_LARS_FrameworkCmnComp WHERE ProgType = '$ProgType' AND FworkCode = '$FworkCode' AND PwayCode = '$PwayCode' AND CommonComponent IN (SELECT FrameworkCommonComponent FROM lars201415.Core_LARS_LearningDelivery WHERE LearnAimRef = '$LearnAimRef');");

				if($delivery->AimType == '3' && $DD07 && $ProgType != '25' && $first=='0' && $second=='0')
					return "FworkCode_05: The Framework code, Pathway code, and Programme type combination is not valid in LARS for this Learning aim reference. :".$delivery->LearnAimRef;
			}
		}
	}

	private function rule_WithdrawReason_03($link, $ilr)
	{
		foreach($ilr->LearningDelivery as $delivery)
		{
			if($delivery->CompStatus=='3' && $delivery->WithdrawReason=='')
				return "WithdrawReason_03: The Withdrawal reason must be returned if the Completion status is 'Withdrawn'. \n";
		}
	}

	private function rule_CompStatus_02($link, $ilr)
	{
		foreach($ilr->LearningDelivery as $delivery)
		{
			if(isset($delivery->LearnActEndDate))
				if(("".$delivery->LearnActEndDate)!='' && ("".$delivery->LearnActEndDate)!='dd/mm/yyyy' && ("".$delivery->LearnActEndDate)!='00000000')
					if($delivery->CompStatus=='1')
						return "CompStatus_02: If the Learning actual end date is returned, the Completion status must not be 'the learner is continuing or intending to continue the learning activities leading to the learning aim' \n";
		}
	}


	private function rule_CompStatus_04($link, $ilr)
	{
		foreach($ilr->LearningDelivery as $delivery)
		{
			if($delivery->CompStatus!='1' && $delivery->Outcome=='')
				return "CompStatus_04: If the outcome is not returned, the completion status must be continuing. \n";
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
						return "R52: No two Learning Delivery FAM records should have the same UKPRN,  Learner Reference number, Aim Sequence number, LearningDeliveryFAM code and LearningDeliveryFAM type  \n";
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
						return "R57: The Achievement date for the programme aim must not be before the Achievement date of the latest aim within that programme. \n";
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


