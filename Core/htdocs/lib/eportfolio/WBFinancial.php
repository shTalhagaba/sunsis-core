<?php
class WBFinancial extends Workbook
{
	public function __construct($tr_id)
	{
		$this->wb_title = 'WBFinancial';
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
<Workbook title="financial">
	<TrainingRecordID></TrainingRecordID>
	<Answers>
		<Cost>
			<SuperdrugCost></SuperdrugCost>
			<CostSavingIdeas>
				<Set1>
					<Idea></Idea>
					<Process></Process>
				</Set1>
				<Set2>
					<Idea></Idea>
					<Process></Process>
				</Set2>
				<Set3>
					<Idea></Idea>
					<Process></Process>
				</Set3>
			</CostSavingIdeas>
		</Cost>
		<KPIs>
			<ListOfKPIs></ListOfKPIs>
			<StorePerformanceForKPIs></StorePerformanceForKPIs>
		</KPIs>
		<SalesTarget>
			<STARBUYSKPIs>
				<Set1>
					<STARBUY></STARBUY>
					<StoreKPI></StoreKPI>
					<IndividualKPI></IndividualKPI>
				</Set1>
				<Set2>
					<STARBUY></STARBUY>
					<StoreKPI></StoreKPI>
					<IndividualKPI></IndividualKPI>
				</Set2>
				<Set3>
					<STARBUY></STARBUY>
					<StoreKPI></StoreKPI>
					<IndividualKPI></IndividualKPI>
				</Set3>
				<Set4>
					<STARBUY></STARBUY>
					<StoreKPI></StoreKPI>
					<IndividualKPI></IndividualKPI>
				</Set4>
			</STARBUYSKPIs>
			<STARBUYSPromotion>
				<Set1>
					<KPI></KPI>
					<Promotion></Promotion>
				</Set1>
				<Set2>
					<KPI></KPI>
					<Promotion></Promotion>
				</Set2>
				<Set3>
					<KPI></KPI>
					<Promotion></Promotion>
				</Set3>
			</STARBUYSPromotion>
			<WeeklySalesTarget></WeeklySalesTarget>
			<TeamSupportToAchieveTarget></TeamSupportToAchieveTarget>
		</SalesTarget>
		<Wastage>
			<StoreWastage>
				<Set1>
					<Reason></Reason>
					<HowToAvoid></HowToAvoid>
				</Set1>
				<Set2>
					<Reason></Reason>
					<HowToAvoid></HowToAvoid>
				</Set2>
				<Set3>
					<Reason></Reason>
					<HowToAvoid></HowToAvoid>
				</Set3>
				<Set4>
					<Reason></Reason>
					<HowToAvoid></HowToAvoid>
				</Set4>
			</StoreWastage>
			<DateCodingActivity></DateCodingActivity>
			<SustainabilityContribution1></SustainabilityContribution1>
			<SustainabilityContribution2></SustainabilityContribution2>
			<SustainabilityContribution3></SustainabilityContribution3>
		</Wastage>
		<QualificationQuestions>
			<Unit1_1></Unit1_1>
			<Unit1_2></Unit1_2>
			<Unit1_3></Unit1_3>
		</QualificationQuestions>
		<Research></Research>
	</Answers>
	<Feedback>
		<Cost>
			<Status></Status>
			<Comments></Comments>
		</Cost>
		<KPIs>
			<Status></Status>
			<Comments></Comments>
		</KPIs>
		<SalesTarget>
			<Status></Status>
			<Comments></Comments>
		</SalesTarget>
		<Wastage>
			<Status></Status>
			<Comments></Comments>
		</Wastage>
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
		$feedback = $this->wb_content->Feedback;
		if(isset($feedback->Cost->Status) && $feedback->Cost->Status->__toString() == 'A')
			$total += 20;
		if(isset($feedback->KPIs->Status) && $feedback->KPIs->Status->__toString() == 'A')
			$total += 20;
		if(isset($feedback->SalesTarget->Status) && $feedback->SalesTarget->Status->__toString() == 'A')
			$total += 20;
		if(isset($feedback->Wastage->Status) && $feedback->Wastage->Status->__toString() == 'A')
			$total += 20;
		if(isset($feedback->QualificationQuestions->Status) && $feedback->QualificationQuestions->Status->__toString() == 'A')
			$total += 20;

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
			if($section == 'Cost')
			{
				foreach($results AS $row)
				{
					$log_xml = $row['wb_content'];
					$log_xml = XML::loadSimpleXML($log_xml);
					$Answers = $log_xml->Answers;
					$html .= '<table class="table small"><tr class="bg-gray"><th>Modified DateTime: ' . Date::to($row['created'], Date::DATETIME) . '</th></tr>';
					$html .= '<tr><th>What costs does Superdrug have? Write your ideas.</th></tr>';
					$html .= '<tr><td>' . $Answers->$section->SuperdrugCost->__toString() . '</td></tr>';
					$html .= '</table>';
					$html .= '<table class="table small">';
					$html .= '<tr><th colspan="2">Discuss costs with your colleagues and management team. Thinking about all of the costs that Superdrug has, come up with three cost saving ideas on the table below.</th></tr>';
					$html .= '<tr><th>Cost</th><th>Process</th></tr>';
					for($i = 1; $i <= 3; $i++)
					{
						$key = 'Set'.$i;
						$html .= '<tr><td>' . $Answers->$section->CostSavingIdeas->$key->Idea->__toString() . '</td><td>' . $Answers->$section->CostSavingIdeas->$key->Process->__toString() . '</td></tr>';
					}
					$html .= '</table><hr>';
				}
			}
			if($section == 'KPIs')
			{
				foreach($results AS $row)
				{
					$log_xml = $row['wb_content'];
					$log_xml = XML::loadSimpleXML($log_xml);
					$Answers = $log_xml->Answers;
					$html .= '<table class="table small"><tr class="bg-gray"><th>Modified DateTime: ' . Date::to($row['created'], Date::DATETIME) . '</th></tr>';
					$html .= '<tr><th>Talk to you colleagues and management team and find out exactly what your KPIs are. List some of them below.</th></tr>';
					$html .= '<tr><td>' . $Answers->$section->ListOfKPIs->__toString() . '</td></tr>';
					$html .= '<tr><th>Select two of the KPIs above and speak to your management team on how your store is performing against these. Write your findings in the box below.</th></tr>';
					$html .= '<tr><td>' . $Answers->$section->StorePerformanceForKPIs->__toString() . '</td></tr>';
					$html .= '</table><hr>';
				}
			}
			if($section == 'SalesTarget')
			{
				foreach($results AS $row)
				{
					$log_xml = $row['wb_content'];
					$log_xml = XML::loadSimpleXML($log_xml);
					$Answers = $log_xml->Answers;
					$html .= '<table class="table small">';
					$html .= '<tr><th colspan="3">Think about the stores KPI and your individual KPI for STARBUYS this week. What are these targets?</th></tr>';
					$html .= '<tr><th>STARBUY</th><th>STORE KPI</th><th>INDIVIDUAL KPI</th></tr>';
					for($i = 1; $i <= 4; $i++)
					{
						$key = 'Set'.$i;
						$html .= '<tr><td>' . $Answers->$section->STARBUYSKPIs->$key->STARBUY->__toString() . '</td><td>' . $Answers->$section->STARBUYSKPIs->$key->StoreKPI->__toString() . '</td><td>' . $Answers->$section->STARBUYSKPIs->$key->IndividualKPI->__toString() . '</td></tr>';
					}
					$html .= '</table>';
					$html .= '<table class="table small">';
					$html .= '<tr><th colspan="2">Thinking about your individual KPI for STARBUYS, list at least three ways you will promote these products effectively to meet your targets.</th></tr>';
					$html .= '<tr><th>STARBUY</th><th>How To Promote</th></tr>';
					for($i = 1; $i <= 3; $i++)
					{
						$key = 'Set'.$i;
						$html .= '<tr><td>' . $Answers->$section->STARBUYSPromotion->$key->KPI->__toString() . '</td><td>' . $Answers->$section->STARBUYSPromotion->$key->Promotion->__toString() . '</td></tr>';
					}
					$html .= '</table>';
					$html .= '<table class="table small"><tr class="bg-gray"><th>Modified DateTime: ' . Date::to($row['created'], Date::DATETIME) . '</th></tr>';
					$html .= '<tr><th>Talk to your management team about your weekly sales target for this week.  What is that target? How is it broken down? How is the store performing to date?</th></tr>';
					$html .= '<tr><td>' . $Answers->$section->WeeklySalesTarget->__toString() . '</td></tr>';
					$html .= '<tr><th>Think of ways you can support your team to achieve this target. List your ideas below:</th></tr>';
					$html .= '<tr><td>' . $Answers->$section->TeamSupportToAchieveTarget->__toString() . '</td></tr>';
					$html .= '</table><hr>';
				}
			}
			if($section == 'Wastage')
			{
				foreach($results AS $row)
				{
					$log_xml = $row['wb_content'];
					$log_xml = XML::loadSimpleXML($log_xml);
					$Answers = $log_xml->Answers;
					$html .= '<table class="table small">';
					$html .= '<tr><th colspan="2">Think about the wastage of goods in your store. Think of at least four reasons goods may become unfit for sale and how you can avoid this happening. Complete the table below:</th></tr>';
					$html .= '<tr><th>Reason</th><th>How to avoid</th></tr>';
					for($i = 1; $i <= 4; $i++)
					{
						$key = 'Set'.$i;
						$html .= '<tr><td>' . $Answers->$section->StoreWastage->$key->Reason->__toString() . '</td><td>' . $Answers->$section->StoreWastage->$key->HowToAvoid->__toString() . '</td></tr>';
					}
					$html .= '<tr><th colspan="2">Date Coding Activity</th> </tr>';
					$html .= '<tr><td colspan="2">'.$Answers->$section->DateCodingActivity->__toString().'</td> </tr>';
					$html .= '</table>';
					$html .= '<table class="table small">';
					$html .= '<tr><th colspan="3">Superdrug Sustainability Policy, your contribution:</th></tr>';
					$html .= '<tr>';
					for($i = 1; $i <= 3; $i++)
					{
						$key = 'SustainabilityContribution'.$i;
						$html .= '<td>' . $Answers->$section->$key->__toString() . '</td>';
					}
					$html .= '</tr>';
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
					$html .= '<tr><th>7 - 1.1 Identify the key principles of operating commercially</th><td>' . $Answers->$section->Unit1_1->__toString() . '</td></tr>';
					$html .= '<tr><th>7 - 1.2 Explain how to support the financial performance of a business including reducing wastage and returns</th><td>' . $Answers->$section->Unit1_2->__toString() . '</td></tr>';
					$html .= '<tr><th>7 - 1.3 Explain methods for working towards sales targets</th><td>' . $Answers->$section->Unit1_3->__toString() . '</td></tr>';
					$html .= '</table><hr>';
				}
			}
		}
		return $html;
	}

	public function getUnitReference()
	{
		return 'Unit 12';
	}

	public function getStepsWithQuestions()
	{
		return '2,3,4,5,6';
	}

	public function updateLearnerProgress(PDO $link)
	{
		parent::updateProgress($link, $this->getUnitReference(), $this->getQAN());
	}

	public function getPageTopLine()
	{
		if($this->savers_or_sp == 'savers')
			return '<p class="topLine small"><span style="color: blue;">Financial</span> <span style="color: red;">Financial</span> </p>';
		else
			return '<p class="topLine small"><span style="color: pink;">Financial</span> <span style="color: gray;">Financial</span> </p>';
	}

	public function getPageBottomLine()
	{
		if($this->savers_or_sp == 'savers')
			return '<p class="bottomLine small"><span style="color: blue;">Financial</span> <span style="color: red;">Financial</span> </p>';
		else
			return '<p class="bottomLine small"><span style="color: pink;">Financial</span> <span style="color: gray;">Financial</span> </p>';
	}

	public function getQAN()
	{
		return Workbook::RETAIL_QAN;
	}
}
?>