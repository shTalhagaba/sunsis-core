<?php
class WBLegalAndGovernance extends Workbook
{
	public function __construct($tr_id)
	{
		$this->wb_title = 'WBLegalAndGovernance';
		$this->tr_id = $tr_id;
		$this->wb_content = self::getBlankXML();
	}

	/**
	 * @return xml
	 * @param null
	 */
	public static function getBlankXML()
	{
		$hs_video_questions_xml = '';
		for($i = 1; $i <= 24; $i++)
			$hs_video_questions_xml .= '<Question'.$i.'></Question'.$i.'>';
		$penalties_xml = '';
		for($i = 1; $i <= 9; $i++)
			$penalties_xml .= '<Question'.$i.'></Question'.$i.'>';
		$qualifications_xml = '';
		for($i = 1; $i <= 7; $i++)
			$qualifications_xml .= '<Question'.$i.'></Question'.$i.'>';
		$xml = <<<XML
<Workbook title="legal_and_governance">
	<TrainingRecordID></TrainingRecordID>
	<Answers>
		<Journey>
			<DC1></DC1>
			<DC2></DC2>
			<DC3></DC3>
		</Journey>
		<Scenarios>
			<Scenario1></Scenario1>
			<Scenario2></Scenario2>
		</Scenarios>
		<ConsumerCreditAct>
			<HowProtect></HowProtect>
			<HowImpact></HowImpact>
		</ConsumerCreditAct>
		<DataProtectionAct>
			<HowProtect></HowProtect>
			<HowImpact></HowImpact>
			<WhatInfo></WhatInfo>
			<HowToKeepInfo></HowToKeepInfo>
			<AdminFee></AdminFee>
		</DataProtectionAct>
		<WeightsAndMeasuresAct>
			<Information></Information>
			<HowProtect></HowProtect>
			<HowImpact></HowImpact>
		</WeightsAndMeasuresAct>
		<LicensingLaws>
			<Information></Information>
			<HowProtect></HowProtect>
			<HowImpact></HowImpact>
		</LicensingLaws>
		<AgeRelatedLegislation>
			<HowProtect></HowProtect>
			<HowImpact></HowImpact>
			<AgeRestrictions>
				<Set1>
					<Product></Product>
					<Age></Age>
				</Set1>
				<Set2>
					<Product></Product>
					<Age></Age>
				</Set2>
				<Set3>
					<Product></Product>
					<Age></Age>
				</Set3>
				<Set4>
					<Product></Product>
					<Age></Age>
				</Set4>
				<Set5>
					<Product></Product>
					<Age></Age>
				</Set5>
			</AgeRestrictions>
		</AgeRelatedLegislation>
		<HealthAndSafetyNotes></HealthAndSafetyNotes>
		<RiskAssessment>
			<InStore>
				<Set1>
					<Type></Type>
					<Frequency></Frequency>
					<Impact></Impact>
				</Set1>
				<Set2>
					<Type></Type>
					<Frequency></Frequency>
					<Impact></Impact>
				</Set2>
				<Set3>
					<Type></Type>
					<Frequency></Frequency>
					<Impact></Impact>
				</Set3>
			</InStore>
			<Yours>
				<Assessment></Assessment>
				<Findings></Findings>
				<Recommendations></Recommendations>
			</Yours>
		</RiskAssessment>
		<FirstAid>
			<RIDDOR></RIDDOR>
			<Poster></Poster>
		</FirstAid>
		<HSVideo>
			$hs_video_questions_xml
			<RegionalHSContact></RegionalHSContact>
		</HSVideo>
		<SecurityMeasures>
			<YouFollowInStore>
				<Set1>
					<Measure></Measure>
					<Why></Why>
				</Set1>
				<Set2>
					<Measure></Measure>
					<Why></Why>
				</Set2>
				<Set3>
					<Measure></Measure>
					<Why></Why>
				</Set3>
			</YouFollowInStore>
			<OtherMeasuresForProtection></OtherMeasuresForProtection>
			<CustomerStealing></CustomerStealing>
			<CustomerActDishonestly></CustomerActDishonestly>
			<TeamMemberStealing></TeamMemberStealing>
		</SecurityMeasures>
		<DealingEmergency></DealingEmergency>
		<CaseStudy></CaseStudy>
		<FloorPlan></FloorPlan>
		<ContraveningLegislation>
			<Q1></Q1>
			<Q2></Q2>
			<Q3></Q3>
		</ContraveningLegislation>
		<Penalties>
			$penalties_xml
		</Penalties>
		<Diversity>
			<Example></Example>
			<C1></C1>
			<C2></C2>
			<C3></C3>
		</Diversity>
		<Demographics>
			<Q1></Q1>
			<Q2></Q2>
			<Q3></Q3>
			<Q4></Q4>
			<Q5></Q5>
			<Q6></Q6>
			<Q7></Q7>
			<Q8></Q8>
		</Demographics>
		<QualificationQuestions>
			$qualifications_xml
		</QualificationQuestions>
		<Research></Research>
	</Answers>
	<Feedback>
		<Scenarios>
			<Status></Status>
			<Comments></Comments>
		</Scenarios>
		<ConsumerCreditAct>
			<Status></Status>
			<Comments></Comments>
		</ConsumerCreditAct>
		<DataProtectionAct>
			<Status></Status>
			<Comments></Comments>
		</DataProtectionAct>
		<WeightsAndMeasuresAct>
			<Status></Status>
			<Comments></Comments>
		</WeightsAndMeasuresAct>
		<LicensingLaws>
			<Status></Status>
			<Comments></Comments>
		</LicensingLaws>
		<AgeRelatedLegislation>
			<Status></Status>
			<Comments></Comments>
		</AgeRelatedLegislation>
		<HealthAndSafetyNotes>
			<Status></Status>
			<Comments></Comments>
		</HealthAndSafetyNotes>
		<RiskAssessment>
			<Status></Status>
			<Comments></Comments>
		</RiskAssessment>
		<FirstAid>
			<Status></Status>
			<Comments></Comments>
		</FirstAid>
		<HSVideo>
			<Status></Status>
			<Comments></Comments>
		</HSVideo>
		<SecurityMeasures>
			<Status></Status>
			<Comments></Comments>
		</SecurityMeasures>
		<DealingEmergency>
			<Status></Status>
			<Comments></Comments>
		</DealingEmergency>
		<CaseStudy>
			<Status></Status>
			<Comments></Comments>
		</CaseStudy>
		<FloorPlan>
			<Status></Status>
			<Comments></Comments>
		</FloorPlan>
		<ContraveningLegislation>
			<Status></Status>
			<Comments></Comments>
		</ContraveningLegislation>
		<Penalties>
			<Status></Status>
			<Comments></Comments>
		</Penalties>
		<Diversity>
			<Status></Status>
			<Comments></Comments>
		</Diversity>
		<Demographics>
			<Status></Status>
			<Comments></Comments>
		</Demographics>
		<QualificationQuestions>
			<Status></Status>
			<Comments></Comments>
		</QualificationQuestions>
		<CriteriaMet></CriteriaMet>
	</Feedback>
</Workbook>
XML;

		return XML::loadSimpleXML($xml);
	}

	public function getCompletedPercentage()
	{
		return parent::getCompletedPercentage();
	}

	public function getSignOffPercentage()
	{
		$total = 0;
		return round($total);
	}

	public function showAssessorFeedback(PDO $link, $section)
	{
		return parent::showAssessorFeedback($link, $section);
	}

	public function showSectionHistory(PDO $link, $section)
	{
		$html = '';
		$results = DAO::getResultset($link, "SELECT wb_content, created FROM workbooks_log WHERE wb_id = '{$this->id}' AND user_type = '" . User::TYPE_LEARNER . "' ORDER BY created DESC LIMIT 10000 #OFFSET 1", DAO::FETCH_ASSOC);
		if(count($results) == 0)
		{
			$html .= '<i>No records found</i>';
		}
		else
		{
			if($section == 'Scenarios')
			{
				foreach($results AS $row)
				{
					$log_xml = $row['wb_content'];
					$log_xml = XML::loadSimpleXML($log_xml);
					$Answers = $log_xml->Answers;
					$html .= '<table class="table small"><tr class="bg-gray"><th colspan="3">Modified DateTime: ' . Date::to($row['created'], Date::DATETIME) . '</th></tr>';
					$html .= '<tr><th>First Scenario</th><th>Second Scenario</th></tr>';
					$html .= '<tr><td>' . $Answers->$section->Scenario1->__toString() . '</td><td>' . $Answers->$section->Scenario2->__toString() . '</td></tr>';
					$html .= '</table><hr>';
				}
			}
			if($section == 'ConsumerCreditAct')
			{
				foreach($results AS $row)
				{
					$log_xml = $row['wb_content'];
					$log_xml = XML::loadSimpleXML($log_xml);
					$Answers = $log_xml->Answers;
					$html .= '<table class="table small"><tr class="bg-gray"><th colspan="3">Modified DateTime: ' . Date::to($row['created'], Date::DATETIME) . '</th></tr>';
					$html .= '<tr><th colspan="2">Consumer Credit Act</th></tr>';
					$html .= '<tr><th>How does it protect</th><th>How does it impact</th></tr>';
					$html .= '<tr><td>' . $Answers->$section->HowProtect->__toString() . '</td><td>' . $Answers->$section->HowImpact->__toString() . '</td></tr>';
					$html .= '</table><hr>';
				}
			}
			if($section == 'DataProtectionAct')
			{
				foreach($results AS $row)
				{
					$log_xml = $row['wb_content'];
					$log_xml = XML::loadSimpleXML($log_xml);
					$Answers = $log_xml->Answers;
					$html .= '<table class="table small"><tr class="bg-gray"><th colspan="3">Modified DateTime: ' . Date::to($row['created'], Date::DATETIME) . '</th></tr>';
					$html .= '<tr><th>How does it protect?</th><td>' . $Answers->$section->HowProtect->__toString() . '</td></tr>';
					$html .= '<tr><th>How does it impact?</th><td>' . $Answers->$section->HowImpact->__toString() . '</td></tr>';
					$html .= '<tr><th>What information do we request off our customers and why?</th><td>' . $Answers->$section->WhatInfo->__toString() . '</td></tr>';
					$html .= '<tr><th>Your responsibilities to keep information confidential?</th><td>' . $Answers->$section->HowToKeepInfo->__toString() . '</td></tr>';
					$html .= '<tr><th>Admin Fee for personal information?</th><td>' . $Answers->$section->AdminFee->__toString() . '</td></tr>';
					$html .= '</table><hr>';
				}
			}
			if($section == 'WeightsAndMeasuresAct' || $section == 'ConsumerContractsRegulations' || $section == 'LicensingLaws')
			{
				foreach($results AS $row)
				{
					$log_xml = $row['wb_content'];
					$log_xml = XML::loadSimpleXML($log_xml);
					$Answers = $log_xml->Answers;
					$html .= '<table class="table small"><tr class="bg-gray"><th colspan="3">Modified DateTime: ' . Date::to($row['created'], Date::DATETIME) . '</th></tr>';
					$html .= '<tr><th>What did you find out about this Act?</th><td>' . $Answers->$section->Information->__toString() . '</td></tr>';
					$html .= '<tr><th>How does it protect?</th><td>' . $Answers->$section->HowProtect->__toString() . '</td></tr>';
					$html .= '<tr><th>How does it impact?</th><td>' . $Answers->$section->HowImpact->__toString() . '</td></tr>';
					$html .= '</table><hr>';
				}
			}
			if($section == 'AgeRelatedLegislation')
			{
				foreach($results AS $row)
				{
					$log_xml = $row['wb_content'];
					$log_xml = XML::loadSimpleXML($log_xml);
					$Answers = $log_xml->Answers;
					$html .= '<table class="table small"><tr class="bg-gray"><th colspan="3">Modified DateTime: ' . Date::to($row['created'], Date::DATETIME) . '</th></tr>';
					$html .= '<tr><th>How does it protect?</th><td>' . $Answers->$section->HowProtect->__toString() . '</td></tr>';
					$html .= '<tr><th>How does it impact?</th><td>' . $Answers->$section->HowImpact->__toString() . '</td></tr>';
					$html .= '<tr><td colspan="2"><table class="table">';
					$html .= '<tr><th colspan="2">Products with age restrictions</th></tr>';
					$html .= '<tr><th>Product</th><th>Age</th></tr>';
					for($i = 1; $i <= 5; $i++)
					{
						$set = 'Set'.$i;
						$html .= '<tr><td>' . $Answers->$section->AgeRestrictions->$set->Product->__toString() . '</td><td>' . $Answers->$section->AgeRestrictions->$set->Age->__toString() . '</td></tr>';
					}
					$html .= '</table></td></tr>';
					$html .= '</table><hr>';
				}
			}
			if($section == 'HealthAndSafetyNotes' || $section == 'DealingEmergency' || $section == 'CaseStudy')
			{
				foreach($results AS $row)
				{
					$log_xml = $row['wb_content'];
					$log_xml = XML::loadSimpleXML($log_xml);
					$Answers = $log_xml->Answers;
					$html .= '<table class="table small"><tr class="bg-gray"><th>Modified DateTime: ' . Date::to($row['created'], Date::DATETIME) . '</th></tr>';
					$html .= '<tr><th>Health and Safety Notes</th></tr>';
					$html .= '<tr><td>' . $Answers->$section->__toString() . '</td></tr>';
					$html .= '</table><hr>';
				}
			}
			if($section == 'RiskAssessment')
			{
				foreach($results AS $row)
				{
					$log_xml = $row['wb_content'];
					$log_xml = XML::loadSimpleXML($log_xml);
					$Answers = $log_xml->Answers;
					$html .= '<table class="table small"><tr class="bg-gray"><th colspan="2">Modified DateTime: ' . Date::to($row['created'], Date::DATETIME) . '</th></tr>';
					$html .= '<tr><td colspan="2"><table class="table">';
					$html .= '<tr><th>Risk assessment type</th><th>How often</th><th>What could happen if not done</th></tr>';
					for($i = 1; $i <= 3; $i++)
					{
						$set = 'Set'.$i;
						$html .= '<tr><td>' . $Answers->$section->InStore->$set->Type->__toString() . '</td><td>' . $Answers->$section->InStore->$set->Frequency->__toString() . '</td><td>' . $Answers->$section->InStore->$set->Impact->__toString() . '</td></tr>';
					}
					$html .= '<tr><th>Your risk assessment</th><td>' . $Answers->$section->Yours->YoursAssessment->__toString() . '</td></tr>';
					$html .= '<tr><th>Your findings</th><td>' . $Answers->$section->Yours->YoursFindings->__toString() . '</td></tr>';
					$html .= '<tr><th>Your recommendations</th><td>' . $Answers->$section->Yours->YoursRecommendations->__toString() . '</td></tr>';
					$html .= '</table></td></tr>';
					$html .= '</table><hr>';
				}
			}
			if($section == 'FirstAid')
			{
				foreach($results AS $row)
				{
					$log_xml = $row['wb_content'];
					$log_xml = XML::loadSimpleXML($log_xml);
					$Answers = $log_xml->Answers;
					$html .= '<table class="table small"><tr class="bg-gray"><th>Modified DateTime: ' . Date::to($row['created'], Date::DATETIME) . '</th></tr>';
					$html .= '<tr><th>What foes RIDDOR stand for?</th></tr>';
					$html .= '<tr><td>' . $Answers->$section->RIDDOR->__toString() . '</td></tr>';
					$html .= '<tr><th>Where is H&S poster displayed?</th></tr>';
					$html .= '<tr><td>' . $Answers->$section->Poster->__toString() . '</td></tr>';
					$html .= '</table><hr>';
				}
			}
			if($section == 'HSVideo')
			{
				foreach($results AS $row)
				{
					$log_xml = $row['wb_content'];
					$log_xml = XML::loadSimpleXML($log_xml);
					$Answers = $log_xml->Answers;
					$html .= '<table class="table small"><tr class="bg-gray"><th colspan="2">Modified DateTime: ' . Date::to($row['created'], Date::DATETIME) . '</th></tr>';
					for($i = 1; $i <= 24; $i++)
					{
						$q = 'Question'.$i;
						$html .= '<tr><th>' . $q. '</th><td>' . $Answers->$section->$q->__toString() . '</td></tr>';
					}
					$html .= '<tr><th>Your regional contact within H&S</th><td>' . $Answers->$section->RegionalHSContact->__toString() . '</td></tr>';
					$html .= '</table><hr>';
				}
			}
			if($section == 'SecurityMeasures')
			{
				foreach($results AS $row)
				{
					$log_xml = $row['wb_content'];
					$log_xml = XML::loadSimpleXML($log_xml);
					$Answers = $log_xml->Answers;
					$html .= '<table class="table small"><tr class="bg-gray"><th colspan="2">Modified DateTime: ' . Date::to($row['created'], Date::DATETIME) . '</th></tr>';
					$html .= '<tr><td colspan="2"><table class="table">';
					$html .= '<tr><th>Security measure</th><th>Why is this in place?</th></tr>';
					for($i = 1; $i <= 3; $i++)
					{
						$set = 'Set'.$i;
						$html .= '<tr><td>' . $Answers->$section->YouFollowInStore->$set->Measure->__toString() . '</td><td>' . $Answers->$section->YouFollowInStore->$set->Why->__toString() . '</td></tr>';
					}
					$html .= '<tr><th>What security measures or procedures are in place to protect you, your colleagues and the public from harm?</th><td>' . $Answers->$section->OtherMeasuresForProtection->__toString() . '</td></tr>';
					$html .= '<tr><th>What actions should you take if you observe a member of the public stealing goods?If you are unsure speak to your manager or mentor.</th><td>' . $Answers->$section->CustomerStealing->__toString() . '</td></tr>';
					$html .= '<tr><th>What types of behaviour or signs have you come across that could suggest a customer was acting dishonestly?</th><td>' . $Answers->$section->CustomerActDishonestly->__toString() . '</td></tr>';
					$html .= '<tr><th>What action should you take if you observe a member of your store team stealing?</th><td>' . $Answers->$section->TeamMemberStealing->__toString() . '</td></tr>';
					$html .= '</table></td></tr>';
					$html .= '</table><hr>';
				}
			}
			if($section == 'ContraveningLegislation')
			{
				foreach($results AS $row)
				{
					$log_xml = $row['wb_content'];
					$log_xml = XML::loadSimpleXML($log_xml);
					$Answers = $log_xml->Answers;
					$html .= '<table class="table small"><tr class="bg-gray"><th colspan="2">Modified DateTime: ' . Date::to($row['created'], Date::DATETIME) . '</th></tr>';
					$html .= '<tr><th>What are the consequences to the supermarket / staff member?</th><td>' . $Answers->$section->Q1->__toString() . '</td></tr>';
					$html .= '<tr><th>What are the consequences to the restaurant and its owner?</th><td>' . $Answers->$section->Q2->__toString() . '</td></tr>';
					$html .= '<tr><th>What are the consequences to the pub/employee?</th><td>' . $Answers->$section->Q3->__toString() . '</td></tr>';
					$html .= '</table></td></tr>';
					$html .= '</table><hr>';
				}
			}
			if($section == 'Penalties')
			{
				foreach($results AS $row)
				{
					$log_xml = $row['wb_content'];
					$log_xml = XML::loadSimpleXML($log_xml);
					$Answers = $log_xml->Answers;
					$html .= '<table class="table small"><tr class="bg-gray"><th colspan="2">Modified DateTime: ' . Date::to($row['created'], Date::DATETIME) . '</th></tr>';
					for($i = 1; $i <= 9; $i++)
					{
						$q = 'Question'.$i;
						$html .= '<tr><th>' . $q. '</th><td>' . $Answers->$section->$q->__toString() . '</td></tr>';
					}
					$html .= '</table></td></tr>';
					$html .= '</table><hr>';
				}
			}
			if($section == 'QualificationQuestions')
			{
				foreach($results AS $row)
				{
					$log_xml = $row['wb_content'];
					$log_xml = XML::loadSimpleXML($log_xml);
					$Answers = $log_xml->Answers;
					$html .= '<table class="table small"><tr class="bg-gray"><th colspan="2">Modified DateTime: ' . Date::to($row['created'], Date::DATETIME) . '</th></tr>';
					for($i = 1; $i <= 7; $i++)
					{
						$q = 'Question'.$i;
						$html .= '<tr><th>' . $q. '</th><td>' . $Answers->$section->$q->__toString() . '</td></tr>';
					}
					$html .= '</table></td></tr>';
					$html .= '</table><hr>';
				}
			}
		}
		return $html;
	}

	public function getUnitReference()
	{
		return 'Unit 11';
	}

	public function getStepsWithQuestions()
	{
		return '1,4,6,7,8,9,10,11,12,14,15,16,17,18,19,20,22,23,25,26';
	}

	public function updateLearnerProgress(PDO $link)
	{
		parent::updateProgress($link, $this->getUnitReference(), $this->getQAN());
	}

	public static function getLearningJourneyItems()
	{
		return array(
			'Policies and procedures'
		,'Law and legislation'
		,'Age related sales'
		);
	}

	public function getPageTopLine()
	{
		if($this->savers_or_sp == 'savers')
			return '<p class="topLine"><span style="color: blue;">Legal & Governance and Diversity</span> <span style="color: red;">Legal & Governance and Diversity</span> </p>';
		else
			return '<p class="topLine"><span style="color: pink;">Legal & Governance and Diversity</span> <span style="color: gray;">Legal & Governance and Diversity</span> </p>';
	}

	public function getPageBottomLine()
	{
		if($this->savers_or_sp == 'savers')
			return '<p class="bottomLine"><span style="color: blue;">Legal & Governance and Diversity</span> <span style="color: red;">Legal & Governance and Diversity</span> </p>';
		else
			return '<p class="bottomLine"><span style="color: pink;">Legal & Governance and Diversity</span> <span style="color: gray;">Legal & Governance and Diversity</span> </p>';
	}

	public function getQAN()
	{
		return Workbook::RETAIL_QAN;
	}
}
?>