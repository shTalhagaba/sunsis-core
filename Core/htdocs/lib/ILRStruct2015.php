<?php

class ILRStruct2015 extends Entity
{
	public function __construct($submission, $contract_id, $tr_id, $l03)
	{
		$this->LearnRefNumber = $l03;
		$this->submission = $submission;
		$this->contract_id = $contract_id;
		$this->tr_id = $tr_id;
	}

	public function populateFromLearner(User $learner)
	{
		if(is_null($learner))
			return;

		$this->ULN = $learner->l45;
		$this->FamilyName = $learner->surname;
		$this->GivenNames = $learner->firstnames;
		$this->DateOfBirth = $learner->dob;
		$this->Ethnicity = $learner->ethnicity;
		$this->Sex = $learner->gender;
		$this->LLDDHealthProb = $learner->l14;
		$this->PrimaryLLDD = $learner->primary_lldd;
		$this->LLDDCat = $learner->lldd_cat;
		$this->NINumber = $learner->ni;
		$this->PriorAttain = $learner->l35;
		$this->AddLine1 = $learner->home_address_line_1;
		$this->AddLine2 = $learner->home_address_line_2;
		$this->AddLine3 = $learner->home_address_line_3;
		$this->AddLine4 = $learner->home_address_line_4;
		$this->PostCodeCurrent = $learner->home_postcode;
		$this->TelNumber = $learner->home_telephone;
		$this->Email = $learner->home_email;
		if(!is_null($learner->lsr) && $learner->lsr != '')
			$this->LSR = explode(',', $learner->lsr);
		if(!is_null($learner->fme) && $learner->fme != '')
			$this->FME = explode(',', $learner->fme);
		$this->ProviderSpecLearnerMonitoringA = $learner->l42a;
		$this->ProviderSpecLearnerMonitoringB = $learner->l42b;
	}

	public function addLearningDelivery(LearningDeliveryStruct $delivery)
	{
		$this->learningDeliveries[] = $delivery;
	}

	public function addLearnerEmploymentStatus(LearnerEmploymentStatusStruct $EmpStatus)
	{
		$this->learnerEmploymentStatus[] = $EmpStatus;
	}

	public static function loadFromXML($xml)
	{
		$vo = XML::loadSimpleXML($xml);
		return $vo;
	}

	public function getILRXML()
	{
		$xml = <<<XML
<Learner>
	<LearnRefNumber>$this->LearnRefNumber</LearnRefNumber>
	<ULN>$this->ULN</ULN>
	<FamilyName>$this->FamilyName</FamilyName>
	<GivenNames>$this->GivenNames</GivenNames>
	<DateOfBirth>$this->DateOfBirth</DateOfBirth>
	<Ethnicity>$this->Ethnicity</Ethnicity>
	<Sex>$this->Sex</Sex>
	<LLDDHealthProb>$this->LLDDHealthProb</LLDDHealthProb>
	<NINumber>$this->NINumber</NINumber>
	<PriorAttain>$this->PriorAttain</PriorAttain>
	<LearnerContact><LocType>2</LocType><ContType>1</ContType><PostCode>$this->PostCodeCurrent</PostCode></LearnerContact>
	<LearnerContact><LocType>1</LocType><ContType>2</ContType><PostAdd>
	<AddLine1>$this->AddLine1</AddLine1>
	<AddLine2>$this->AddLine2</AddLine2>
	<AddLine3>$this->AddLine3</AddLine3>
	<AddLine4>$this->AddLine4</AddLine4>
	</PostAdd></LearnerContact>
	<LearnerContact><LocType>2</LocType><ContType>2</ContType><PostCode>$this->PostCodeCurrent</PostCode></LearnerContact>
	<LearnerContact><LocType>3</LocType><ContType>2</ContType><TelNumber>$this->TelNumber</TelNumber></LearnerContact>
	<LearnerContact><LocType>4</LocType><ContType>2</ContType><Email>$this->Email</Email></LearnerContact>
	<LLDDandHealthProblem>
	<LLDDCat>$this->PrimaryLLDD</LLDDCat>
	<PrimaryLLDD>1</PrimaryLLDD>
	</LLDDandHealthProblem>
XML;

		if(!is_null($this->LLDDCat) && $this->LLDDCat != '')
		{
			$lldds = explode(',', $this->LLDDCat);
			for($i = 0; $i < count($lldds); $i++)
			{
				if($this->PrimaryLLDD != $lldds[$i])
				{
					$xml .= "<LLDDandHealthProblem>";
					$xml .= "<LLDDCat>" . $lldds[$i] . "</LLDDCat>";
					$xml .= "</LLDDandHealthProblem>";
				}
			}
		}

		foreach($this->learnerEmploymentStatus AS $emp_status)/* @var $emp_status LearnerEmploymentStatusStruct*/
		{
			$xml .= '<LearnerEmploymentStatus>';
			$xml .= '<EmpStat>' . $emp_status->EmpStat . '</EmpStat>';
			$xml .= '<DateEmpStatApp>' . $emp_status->DateEmpStatApp . '</DateEmpStatApp>';
			$xml .= '<EmpId>' . $emp_status->EmpId . '</EmpId>';
			if(!is_null($emp_status->LOU) && $emp_status->LOU != '')
				$xml .= '<EmploymentStatusMonitoring><ESMType>LOU</ESMType><ESMCode>' . $emp_status->LOU . '</ESMCode></EmploymentStatusMonitoring>';
			$xml .= '</LearnerEmploymentStatus>';
		}

		foreach($this->LSR AS $lsr)
		{
			$xml .= "<LearnerFAM><LearnFAMType>LSR</LearnFAMType><LearnFAMCode>" . $lsr . "</LearnFAMCode></LearnerFAM>";
		}

		foreach($this->FME AS $fme)
		{
			$xml .= "<LearnerFAM><LearnFAMType>FME</LearnFAMType><LearnFAMCode>" . $fme . "</LearnFAMCode></LearnerFAM>";
		}

		foreach($this->NLM AS $nlm)
		{
			$xml .= "<LearnerFAM><LearnFAMType>NLM</LearnFAMType><LearnFAMCode>" . $nlm . "</LearnFAMCode></LearnerFAM>";
		}

		$xml .= "<ProviderSpecLearnerMonitoring><ProvSpecLearnMonOccur>A</ProvSpecLearnMonOccur><ProvSpecLearnMon>" . $this->ProviderSpecLearnerMonitoringA . "</ProvSpecLearnMon></ProviderSpecLearnerMonitoring>";
		$xml .= "<ProviderSpecLearnerMonitoring><ProvSpecLearnMonOccur>B</ProvSpecLearnMonOccur><ProvSpecLearnMon>" . $this->ProviderSpecLearnerMonitoringB . "</ProvSpecLearnMon></ProviderSpecLearnerMonitoring>";

		foreach($this->learningDeliveries AS $delivery)/* @var $delivery LearningDeliveryStruct*/
		{
			$xml .= "<LearningDelivery>";
			$xml .= "<LearnAimRef>" . $delivery->LearnAimRef . "</LearnAimRef>";
			$xml .= "<AimType>" . $delivery->AimType . "</AimType>";
			$xml .= "<AimSeqNumber>" . $delivery->AimSeqNumber . "</AimSeqNumber>";
			$xml .= "<LearnStartDate>" . Date::toMySQL($delivery->LearnStartDate) . "</LearnStartDate>";
			$xml .= "<LearnPlanEndDate>" . Date::toMySQL($delivery->LearnPlanEndDate) . "</LearnPlanEndDate>";
			$xml .= "<LearnActEndDate>" . Date::toMySQL($delivery->LearnActEndDate) . "</LearnActEndDate>";
			$xml .= "<FundModel>" . $delivery->FundModel . "</FundModel>";
			$xml .= "<ProgType>" . $delivery->ProgType . "</ProgType>";
			$xml .= "<FworkCode>" . $delivery->FworkCode . "</FworkCode>";
			$xml .= "<DelLocPostCode>" . $delivery->DelLocPostCode . "</DelLocPostCode>";
			$xml .= "<CompStatus>" . $delivery->CompStatus . "</CompStatus>";
			$xml .= "<Outcome>" . $delivery->Outcome . "</Outcome>";
			$xml .= "<PartnerUKPRN>" . $delivery->PartnerUKPRN . "</PartnerUKPRN>";
			if($delivery->LearnAimRef == 'ZESF0001')
			{
				if(is_array($delivery->HHS) && count($delivery->HHS) > 0)
				{
					for($i = 0; $i < count($delivery->HHS); $i++)
					{
						$xml .= "<LearningDeliveryFAM><LearnDelFAMType>HHS</LearnDelFAMType><LearnDelFAMCode>" . $delivery->HHS[$i] . "</LearnDelFAMCode></LearningDeliveryFAM>";
					}
				}
			}
			if($delivery->LearnAimRef == 'ZPROG001')
			{
				$xml .= "<LearningDeliveryFAM><LearnDelFAMType>LDM</LearnDelFAMType><LearnDelFAMCode>356</LearnDelFAMCode></LearningDeliveryFAM>";
			}
			if($delivery->SOF != '' && !is_null($delivery->SOF))
			{
				$xml .= "<LearningDeliveryFAM><LearnDelFAMType>SOF</LearnDelFAMType><LearnDelFAMCode>" . $delivery->SOF . "</LearnDelFAMCode></LearningDeliveryFAM>";
			}
			$xml .= "</LearningDelivery>";
		}
		$xml .= "</Learner>";

		$xml = str_replace("&", "&amp;", $xml);
		$xml = str_replace("'", "&apos;", $xml);

		$dom = new DOMDocument;
		$dom->preserveWhiteSpace = FALSE;
		@$dom->loadXML($xml);
		$dom->formatOutput = TRUE;
		return $dom->saveXml();
	}

	public $submission = null;
	public $tr_id = null;
	public $contract_id = null;
	public $LearnRefNumber = null;
	public $PrevLearnRefNumber = null;
	public $PrevUKPRN = null;
	public $ULN = null;
	public $FamilyName = null;
	public $GivenNames = null;
	public $DateOfBirth = null;
	public $Ethnicity = null;
	public $Sex = null;
	public $LLDDHealthProb = null;
	public $NINumber = null;
	public $PriorAttain = null;
	public $Accom = null;
	public $ALSCost = null;
	public $PlanLearnHours = null;
	public $PlanEEPHours = null;
	public $MathGrade = null;
	public $EngGrade = null;

	public $AddLine1 = null;
	public $AddLine2 = null;
	public $AddLine3 = null;
	public $AddLine4 = null;
	public $PostCodeCurrent = null;
	public $PostCodePriorEnrolment = null;
	public $TelNumber = null;
	public $Email = null;

	public $RUI = null;
	public $PMC = null;

	public $LLDDCat = array();
	public $PrimaryLLDD = null;

	public $LDA = null;
	public $HNS = null;
	public $EHC = null;
	public $DLA = null;
	public $LSR = array();
	public $SEN = null;
	public $NLM = array();
	public $EDF = null;
	public $MCF = null;
	public $ECF = null;
	public $FME = array();
	public $PPE = null;

	public $ProviderSpecLearnerMonitoringA = null;
	public $ProviderSpecLearnerMonitoringB = null;

	public $learningDeliveries = array();
	public $learnerEmploymentStatus = array();
}
?>