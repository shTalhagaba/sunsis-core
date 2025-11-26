<?php
class WBCustomerExperience extends Workbook
{
	public function __construct($tr_id)
	{
		$this->wb_title = 'WBCustomerExperience';
		$this->tr_id = $tr_id;
		$this->wb_content = self::getBlankXML();
	}

	/**
	 * @return xml
	 * @param null
	 */
	public static function getBlankXML()
	{
		$xml = <<<XML
<Workbook title="customer_experience">
	<TrainingRecordID></TrainingRecordID>
	<Answers>
		<CustomerExperienceMeaning>
			<Superdrug>
				<Set1>
					<Training></Training>
					<Learning></Learning>
					<Impact></Impact>
				</Set1>
				<Set2>
					<Training></Training>
					<Learning></Learning>
					<Impact></Impact>
				</Set2>
				<Set3>
					<Training></Training>
					<Learning></Learning>
					<Impact></Impact>
				</Set3>
			</Superdrug>
			<NonSuperdrug>
				<Set1>
					<Retailer></Retailer>
					<Experience></Experience>
					<Comparison></Comparison>
				</Set1>
				<Set2>
					<Retailer></Retailer>
					<Experience></Experience>
					<Comparison></Comparison>
				</Set2>
			</NonSuperdrug>
		</CustomerExperienceMeaning>
		<CustomerExperienceFeatures>
			<Superdrug>
				<Set1>
					<Offer></Offer>
					<Features></Features>
					<Benefits></Benefits>
				</Set1>
				<Set2>
					<Offer></Offer>
					<Features></Features>
					<Benefits></Benefits>
				</Set2>
				<Set3>
					<Offer></Offer>
					<Features></Features>
					<Benefits></Benefits>
				</Set3>
			</Superdrug>
			<NonSuperdrug></NonSuperdrug>
		</CustomerExperienceFeatures>
		<ImplicationsOfPoorCustomerService></ImplicationsOfPoorCustomerService>
		<HelpingOurCustomers></HelpingOurCustomers>
		<DealConflict>
			<Complains>
				<Q1></Q1>
				<Q2></Q2>
				<Q3></Q3>
				<Q4></Q4>
				<Q5></Q5>
				<Q6></Q6>
			</Complains>
			<Examples>
				<Example1>
					<Step1></Step1>
					<Step2></Step2>
					<Step3></Step3>
					<Step4></Step4>
					<Step5></Step5>
					<Step6></Step6>
					<Step7></Step7>
					<Step8></Step8>
					<Step9></Step9>
				</Example1>
				<Example2>
					<Step1></Step1>
					<Step2></Step2>
					<Step3></Step3>
					<Step4></Step4>
					<Step5></Step5>
					<Step6></Step6>
					<Step7></Step7>
					<Step8></Step8>
					<Step9></Step9>
				</Example2>
			</Examples>
		</DealConflict>
		<Tools>
			<KPIStorePerformance></KPIStorePerformance>
			<KPIEffect></KPIEffect>
			<Observation>
				<Set1>
					<CriteriaMet></CriteriaMet>
					<CriteriaNotMet></CriteriaNotMet>
					<NextTime></NextTime>
				</Set1>
				<Set2>
					<CriteriaMet></CriteriaMet>
					<CriteriaNotMet></CriteriaNotMet>
					<NextTime></NextTime>
				</Set2>
				<Set3>
					<CriteriaMet></CriteriaMet>
					<CriteriaNotMet></CriteriaNotMet>
					<NextTime></NextTime>
				</Set3>
			</Observation>
			<MSReportEvaluation>
				<Set1>
					<Area></Area>
					<Latest></Latest>
					<Previous></Previous>
					<Difference></Difference>
				</Set1>
				<Set2>
					<Area></Area>
					<Latest></Latest>
					<Previous></Previous>
					<Difference></Difference>
				</Set2>
				<Set3>
					<Area></Area>
					<Latest></Latest>
					<Previous></Previous>
					<Difference></Difference>
				</Set3>
				<Set4>
					<Area></Area>
					<Latest></Latest>
					<Previous></Previous>
					<Difference></Difference>
				</Set4>
				<Set5>
					<Area></Area>
					<Latest></Latest>
					<Previous></Previous>
					<Difference></Difference>
				</Set5>
				<Set6>
					<Area></Area>
					<Latest></Latest>
					<Previous></Previous>
					<Difference></Difference>
				</Set6>
				<Set7>
					<Area></Area>
					<Latest></Latest>
					<Previous></Previous>
					<Difference></Difference>
				</Set7>
			</MSReportEvaluation>
			<ActionPlan>
				<Set1>
					<Area></Area>
					<WhatToAchieve></WhatToAchieve>
					<Implementation></Implementation>
					<ByWhom></ByWhom>
					<ByWhen></ByWhen>
				</Set1>
				<Set2>
					<Area></Area>
					<WhatToAchieve></WhatToAchieve>
					<Implementation></Implementation>
					<ByWhom></ByWhom>
					<ByWhen></ByWhen>
				</Set2>
				<Set3>
					<Area></Area>
					<WhatToAchieve></WhatToAchieve>
					<Implementation></Implementation>
					<ByWhom></ByWhom>
					<ByWhen></ByWhen>
				</Set3>
				<Set4>
					<Area></Area>
					<WhatToAchieve></WhatToAchieve>
					<Implementation></Implementation>
					<ByWhom></ByWhom>
					<ByWhen></ByWhen>
				</Set4>
				<Set5>
					<Area></Area>
					<WhatToAchieve></WhatToAchieve>
					<Implementation></Implementation>
					<ByWhom></ByWhom>
					<ByWhen></ByWhen>
				</Set5>
			</ActionPlan>
			<ActionPlanRevision></ActionPlanRevision>
			<ToolsDiscussion></ToolsDiscussion>
		</Tools>
		<QualificationQuestions>
			<Question1></Question1>
			<Question2></Question2>
			<Question3></Question3>
			<Question4></Question4>
		</QualificationQuestions>
		<Research></Research>
	</Answers>
	<Feedback>
		<CustomerExperienceMeaning>
			<Status></Status>
			<Comments></Comments>
		</CustomerExperienceMeaning>
		<CustomerExperienceFeatures>
			<Status></Status>
			<Comments></Comments>
		</CustomerExperienceFeatures>
		<ImplicationsOfPoorCustomerService>
			<Status></Status>
			<Comments></Comments>
		</ImplicationsOfPoorCustomerService>
		<HelpingOurCustomers>
			<Status></Status>
			<Comments></Comments>
		</HelpingOurCustomers>
		<DealConflict>
			<Status></Status>
			<Comments></Comments>
		</DealConflict>
		<Tools>
			<Status></Status>
			<Comments></Comments>
		</Tools>
		<QualificationQuestions>
			<Status></Status>
			<Comments></Comments>
		</QualificationQuestions>
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
		$results = DAO::getResultset($link, "SELECT wb_content, created FROM workbooks_log WHERE wb_id = '{$this->id}' AND user_type = '" . User::TYPE_LEARNER . "' ORDER BY created DESC LIMIT 10000 OFFSET 1", DAO::FETCH_ASSOC);
		if(count($results) == 0)
		{
			$html .= '<i>No records found</i>';
		}
		else
		{
			if($section == 'CustomerExperienceMeaning')
			{
				foreach($results AS $row)
				{
					$log_xml = $row['wb_content'];
					$log_xml = XML::loadSimpleXML($log_xml);
					$Answers = $log_xml->Answers;
					$html .= '<table class="table small"><tr class="bg-gray"><th colspan="3">Modified DateTime: ' . Date::to($row['created'], Date::DATETIME) . '</th></tr>';
					$html .= '<tr><th>Customer Experience Training you have had?</th><th>What have you learned?</th><th>What impact has this had on customers?</th></tr>';
					for($i = 1; $i <= 3; $i++)
					{
						$key = 'Set'.$i;
						$html .= '<tr><td>' . $Answers->$section->Superdrug->$key->Training->__toString() . '</td><td>' . $Answers->$section->Superdrug->$key->Learning->__toString() . '</td><td>' . $Answers->$section->Superdrug->$key->Impact->__toString() . '</td></tr>';
					}
					$html .= '<tr><th>Retailer</th><th>Experience</th><th>How did the service compare with what you do? Good/Bad?</th></tr>';
					for($i = 1; $i <= 2; $i++)
					{
						$key = 'Set'.$i;
						$html .= '<tr><td>' . $Answers->$section->NonSuperdrug->$key->Retailer->__toString() . '</td><td>' . $Answers->$section->NonSuperdrug->$key->Experience->__toString() . '</td><td>' . $Answers->$section->NonSuperdrug->$key->Comparison->__toString() . '</td></tr>';
					}
					$html .= '</table><hr>';
				}
			}
			if($section == 'CustomerExperienceFeatures')
			{
				foreach($results AS $row)
				{
					$log_xml = $row['wb_content'];
					$log_xml = XML::loadSimpleXML($log_xml);
					$Answers = $log_xml->Answers;
					$html .= '<table class="table small"><tr class="bg-gray"><th colspan="3">Modified DateTime: ' . Date::to($row['created'], Date::DATETIME) . '</th></tr>';
					$html .= '<tr><th style="width: 33%;">Customer Service Offer</th><th style="width: 33%;">Features</th><th style="width: 33%;">Benefits</th></tr>';
					for($i = 1; $i <= 3; $i++)
					{
						$key = 'Set'.$i;
						$html .= '<tr><td>' . $Answers->$section->Superdrug->$key->Offer->__toString() . '</td><td>' . $Answers->$section->Superdrug->$key->Features->__toString() . '</td><td>' . $Answers->$section->Superdrug->$key->Benefit->__toString() . '</td></tr>';
					}
					$html .= '<tr><th colspan="3">Identify a local retail business which has a good reputation for customer service and explain why you think this is?</th></tr>';
					$html .= '<tr><td colspan="3">' . $Answers->$section->NonSuperdrug->__toString() . '</td></tr>';
					$html .= '</table><hr>';
				}
			}
			if($section == 'ImplicationsOfPoorCustomerService' || $section == 'HelpingOurCustomers')
			{
				foreach($results AS $row)
				{
					$log_xml = $row['wb_content'];
					$log_xml = XML::loadSimpleXML($log_xml);
					$Answers = $log_xml->Answers;
					$html .= '<table class="table small"><tr class="bg-gray"><th>Modified DateTime: ' . Date::to($row['created'], Date::DATETIME) . '</th></tr>';
					$html .= '<tr><td>' . $Answers->$section->__toString() . '</td></tr>';
					$html .= '</table><hr>';
				}
			}
			if($section == 'DealConflict')
			{
				foreach($results AS $row)
				{
					$log_xml = $row['wb_content'];
					$log_xml = XML::loadSimpleXML($log_xml);
					$Answers = $log_xml->Answers;
					$html .= '<table class="table small"><tr class="bg-gray"><th colspan="2">Modified DateTime: ' . Date::to($row['created'], Date::DATETIME) . '</th></tr>';
					$html .= '<tr><th colspan="2">Identify different things that customers might complain about for each category by filling in the empty boxes.</th></tr>';
					for($i = 1; $i <= 6; $i++)
					{
						$key = 'Q'.$i;
						$html .= '<tr><td>Q'.$i.'</td><td>' . $Answers->$section->Complains->$key->__toString() . '</td></tr>';
					}
					$j = 0;
					for($i = 1; $i <= 2; $i++)
					{
						$example = 'Example'.$i;
						$html .= '<tr><th colspan="2">Overview of customer conflict or challenge - Example ' . $i . '</th></tr>';
						foreach(self::getOverviewOfCustomerConflictQuestions() AS $q)
						{
							$step = 'Step'.++$j;
							$html .= '<tr><td>'.$q.'</td><td>' . $Answers->$section->Examples->$example->$step->__toString() . '</td></tr>';
						}
					}
					$html .= '</table><hr>';
				}
			}
			if($section == 'Tools')
			{
				foreach($results AS $row)
				{
					$log_xml = $row['wb_content'];
					$log_xml = XML::loadSimpleXML($log_xml);
					$Answers = $log_xml->Answers;
					$html .= '<table class="table small"><tr class="bg-gray"><th colspan="2">Modified DateTime: ' . Date::to($row['created'], Date::DATETIME) . '</th></tr>';
					$html .= '<tr><th>Select one of your stores KPIs. Explain how well the store is performing against target.</th><td>' . $Answers->$section->KPIStorePerformance->__toString() . '</td></tr>';
					$html .= '<tr><th>How does the above KPI performance affect your customer service levels?</th><td>' . $Answers->$section->KPIEffect->__toString() . '</td></tr>';
					$html .= '<tr><th colspan="2">Observation</th></tr>';
					$html .= '<tr><td><table class="table"><tr><th>What criteria did you meet?</th><th>What criteria did you not meet?</th><th>What are you going to do differently/better next time?</th></tr>';
					for($i = 1; $i <= 3; $i++)
					{
						$key = 'Set'.$i;
						$html .= '<tr><td>' . $Answers->$section->Observation->$key->CriteriaMet->__toString() . '</td><td>' . $Answers->$section->Observation->$key->CriteriaNotMet->__toString() . '</td><td>' . $Answers->$section->Observation->$key->NextTime->__toString() . '</td></tr>';
					}
					$html .= '</table></td></tr>';
					$html .= '<tr><th colspan="2">Mystery Shopper Report Evaluation</th></tr>';
					$html .= '<tr><td><table class="table"><tr><th>Service Area Measured</th><th>Latest Report %</th><th>Previous Report %</th><th>+ or – % </th></tr>';
					for($i = 1; $i <= 5; $i++)
					{
						$key = 'Set'.$i;
						$html .= '<tr><td>' . $Answers->$section->MSReportEvaluation->$key->Area->__toString() . '</td><td>' . $Answers->$section->MSReportEvaluation->$key->Latest->__toString() . '</td><td>' . $Answers->$section->MSReportEvaluation->$key->Previous->__toString() . '</td><td>' . $Answers->$section->MSReportEvaluation->$key->Difference->__toString() . '</td></tr>';
					}
					$html .= '</table></td></tr>';
					$html .= '<tr><th colspan="2">Action Plan</th></tr>';
					$html .= '<tr><td><table class="table"><tr><th style="width: 20%;"> Service Area to be improved or maintained?</th><th style="width: 20%;">What needs to be achieved?</th><th style="width: 20%;">Implementation?</th><th style="width: 20%;">By whom?</th><th style="width: 20%;">By when?</th></tr>';
					for($i = 1; $i <= 5; $i++)
					{
						$key = 'Set'.$i;
						$html .= '<tr><td>' . $Answers->$section->ActionPlan->$key->Area->__toString() . '</td><td>' . $Answers->$section->ActionPlan->$key->WhatToAchieve->__toString() . '</td><td>' . $Answers->$section->ActionPlan->$key->Implementation->__toString() . '</td><td>' . $Answers->$section->ActionPlan->$key->ByWhom->__toString() . '</td><td>' . $Answers->$section->ActionPlan->$key->ByWhen->__toString() . '</td></tr>';
					}
					$html .= '</table></td></tr>';
					$html .= '<tr><th>Revisit this action plan and evaluate its effectiveness.  Record your findings</th><td>' . $Answers->$section->ActionPlan->ActionPlanRevision->__toString() . '</td></tr>';
					$html .= '<tr><th>Two Examples of tools</th><td>' . $Answers->$section->ActionPlan->ToolsDiscussion->__toString() . '</td></tr>';
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
					$html .= '<tr><th>3.1 Explain how an understanding of the facts can be used to create a customer focused experience</th><td>' . $Answers->$section->Question1->__toString() . '</td></tr>';
					$html .= '<tr><th>3.2 Explain how to build trust with customers and the importance of doing so</th><td>' . $Answers->$section->Question2->__toString() . '</td></tr>';
					$html .= '<tr><th>3.3 Explain how to respond to customer needs and requirements positively</th><td>' . $Answers->$section->Question3->__toString() . '</td></tr>';
					$html .= '<tr><th>5.1 Describe the measures and evaluation tools used in the organisation to monitor customer service levels.</th><td>' . $Answers->$section->Question4->__toString() . '</td></tr>';
					$html .= '</table><hr>';
				}
			}
		}
		return $html;
	}

	public function getUnitReference()
	{
		return 'Unit 03';
	}

	public function getStepsWithQuestions()
	{
		return '2,5,6,8,12,14,15';
	}

	public function updateLearnerProgress(PDO $link)
	{
		parent::updateProgress($link, $this->getUnitReference(), $this->getQAN());
	}

	public static function getOverviewOfCustomerConflictQuestions()
	{
		$_list = array(
			'Describe how you welcomed them'
		,'What listening skills did you demonstrate?'
		,'What questions did you ask?'
		,'What next steps did you explain to the customer?'
		,'What resolution did you offer?'
		,'Describe how you dealt with the situation'
		,'How did you resolve the conflict or challenge?'
		,'How did you keep customers informed?'
		,'What records did you keep? '
		);
		return $_list;
	}
	
	public function getPageTopLine()
	{
		if($this->savers_or_sp == 'savers')
			return '<p class="topLine"><span style="color: blue;">Customer experience</span> <span style="color: red;">Customer experience</span> </p>';
		else
			return '<p class="topLine"><span style="color: pink;">Customer experience</span> <span style="color: gray;">Customer experience</span> </p>';
	}

	public function getPageBottomLine()
	{
		if($this->savers_or_sp == 'savers')
			return '<p class="bottomLine"><span style="color: blue;">Customer experience</span> <span style="color: red;">Customer experience</span> </p>';
		else
			return '<p class="bottomLine"><span style="color: pink;">Customer experience</span> <span style="color: gray;">Customer experience</span> </p>';
	}

	public function getQAN()
	{
		return Workbook::CS_QAN;
	}
}
?>