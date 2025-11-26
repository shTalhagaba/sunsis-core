<?php
class WBStock extends Workbook
{
	public function __construct($tr_id)
	{
		$this->wb_title = 'WBStock';
		$this->tr_id = $tr_id;
		$this->wb_content = self::getBlankXML();
	}

	/**
	 * @return xml
	 * @param null
	 */
	public static function getBlankXML()
	{
		$journey = '<Journey>';
		for($i = 1; $i <= count(self::getLearningJourneyItems()); $i++)
		{
			$journey .= '<Act'.$i.'></Act'.$i.'>';
			$journey .= '<DC'.$i.'></DC'.$i.'>';
		}
		$journey .= '</Journey>';
		$xml = <<<XML
<Workbook title="stock">
	<TrainingRecordID></TrainingRecordID>
	<Answers>
		$journey
		<ProtectingStock>
			<Q1></Q1>
			<Q2></Q2>
		</ProtectingStock>
		<PossibleRisks>
			<Scenario1></Scenario1>
			<Scenario2></Scenario2>
			<Scenario3></Scenario3>
			<Scenario4></Scenario4>
		</PossibleRisks>
		<InStoreStorageFacilities>
			<Q1></Q1>
			<Q2></Q2>
			<Q3></Q3>
		</InStoreStorageFacilities>
		<Deliveries>
			<Q1></Q1>
			<Q2></Q2>
			<Q3></Q3>
			<Q4></Q4>
		</Deliveries>
		<QualificationQuestions>
			<Unit1_1></Unit1_1>
			<Unit1_2></Unit1_2>
			<Unit2_1></Unit2_1>
			<Unit2_2></Unit2_2>
			<Unit3_1></Unit3_1>
			<Unit3_2></Unit3_2>
		</QualificationQuestions>
		<Research></Research>
	</Answers>
	<Feedback>
		<ProtectingStock>
			<Status></Status>
			<Comments></Comments>
		</ProtectingStock>
		<PossibleRisks>
			<Status></Status>
			<Comments></Comments>
		</PossibleRisks>
		<InStoreStorageFacilities>
			<Status></Status>
			<Comments></Comments>
		</InStoreStorageFacilities>
		<Deliveries>
			<Status></Status>
			<Comments></Comments>
		</Deliveries>
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
			if($section == 'ProtectingStock')
			{
				foreach($results AS $row)
				{
					$log_xml = $row['wb_content'];
					$log_xml = XML::loadSimpleXML($log_xml);
					$html .= '<table class="table small"><tr><th colspan="2">Modified DateTime: ' . Date::to($row['created'], Date::DATETIME) . '</th></tr>';
					$html .= '<tr><th>Q1</th>';
					$html .= '<td>' . $log_xml->Answers->ProtectingStock->Q1->__toString() . '</td></tr>';
					$html .= '<tr><th>Q2</th>';
					$html .= '<td>' . $log_xml->Answers->ProtectingStock->Q2->__toString() . '</td></tr>';
					$html .= '</table><hr>';
				}
			}
			if($section == 'InStoreStorageFacilities')
			{
				foreach($results AS $row)
				{
					$log_xml = $row['wb_content'];
					$log_xml = XML::loadSimpleXML($log_xml);
					$html .= '<table class="table small"><tr><th colspan="2">Modified DateTime: ' . Date::to($row['created'], Date::DATETIME) . '</th></tr>';
					$html .= '<tr><th>Q1</th>';
					$html .= '<td>' . $log_xml->Answers->InStoreStorageFacilities->Q1->__toString() . '</td></tr>';
					$html .= '<tr><th>Q2</th>';
					$html .= '<td>' . $log_xml->Answers->InStoreStorageFacilities->Q2->__toString() . '</td></tr>';
					$html .= '<tr><th>Q3</th>';
					$html .= '<td>' . $log_xml->Answers->InStoreStorageFacilities->Q3->__toString() . '</td></tr>';
					$html .= '</table><hr>';
				}
			}
			if($section == 'Deliveries')
			{
				foreach($results AS $row)
				{
					$log_xml = $row['wb_content'];
					$log_xml = XML::loadSimpleXML($log_xml);
					$html .= '<table class="table small"><tr><th colspan="2">Modified DateTime: ' . Date::to($row['created'], Date::DATETIME) . '</th></tr>';
					$html .= '<tr><th>Q1</th>';
					$html .= '<td>' . $log_xml->Answers->Deliveries->Q1->__toString() . '</td></tr>';
					$html .= '<tr><th>Q2</th>';
					$html .= '<td>' . $log_xml->Answers->Deliveries->Q2->__toString() . '</td></tr>';
					$html .= '<tr><th>Q3</th>';
					$html .= '<td>' . $log_xml->Answers->Deliveries->Q3->__toString() . '</td></tr>';
					$html .= '<tr><th>Q4</th>';
					$html .= '<td>' . $log_xml->Answers->Deliveries->Q4->__toString() . '</td></tr>';
					$html .= '</table><hr>';
				}
			}
			if($section == 'QualificationQuestions')
			{
				foreach($results AS $row)
				{
					$log_xml = $row['wb_content'];
					$log_xml = XML::loadSimpleXML($log_xml);
					$html .= '<table class="table small"><tr><th colspan="2">Modified DateTime: ' . Date::to($row['created'], Date::DATETIME) . '</th></tr>';
					$html .= '<tr><th>Unit 5 - 1.1</th>';
					$html .= '<td>' . $log_xml->Answers->QualificationQuestions->Unit1_1->__toString() . '</td></tr>';
					$html .= '<tr><th>Unit 5 - 1.2</th>';
					$html .= '<td>' . $log_xml->Answers->QualificationQuestions->Unit1_2->__toString() . '</td></tr>';
					$html .= '<tr><th>Unit 5 - 2.1</th>';
					$html .= '<td>' . $log_xml->Answers->QualificationQuestions->Unit2_1->__toString() . '</td></tr>';
					$html .= '<tr><th>Unit 5 - 2.2</th>';
					$html .= '<td>' . $log_xml->Answers->QualificationQuestions->Unit2_2->__toString() . '</td></tr>';
					$html .= '<tr><th>Unit 5 - 3.1</th>';
					$html .= '<td>' . $log_xml->Answers->QualificationQuestions->Unit3_1->__toString() . '</td></tr>';
					$html .= '<tr><th>Unit 5 - 3.2</th>';
					$html .= '<td>' . $log_xml->Answers->QualificationQuestions->Unit3_2->__toString() . '</td></tr>';
					$html .= '</table><hr>';
				}
			}
			if($section == 'PossibleRisks')
			{
				foreach($results AS $row)
				{
					$log_xml = $row['wb_content'];
					$log_xml = XML::loadSimpleXML($log_xml);
					$html .= '<table class="table small"><tr><th colspan="2">Modified DateTime: ' . Date::to($row['created'], Date::DATETIME) . '</th></tr>';
					$html .= '<tr><th>Scenario 1</th>';
					$html .= '<td>' . $log_xml->Answers->PossibleRisks->Scenario1->__toString() . '</td></tr>';
					$html .= '<tr><th>Scenario 2</th>';
					$html .= '<td>' . $log_xml->Answers->PossibleRisks->Scenario2->__toString() . '</td></tr>';
					$html .= '<tr><th>Scenario 3</th>';
					$html .= '<td>' . $log_xml->Answers->PossibleRisks->Scenario3->__toString() . '</td></tr>';
					$html .= '<tr><th>Scenario 4</th>';
					$html .= '<td>' . $log_xml->Answers->PossibleRisks->Scenario4->__toString() . '</td></tr>';
					$html .= '</table><hr>';
				}
			}
		}
		return $html;
	}

	public function getUnitReference()
	{
		return 'Unit 09';
	}

	public function getStepsWithQuestions()
	{
		return $this->savers_or_sp == 'savers'?'6,7,8':'6,7,8';
	}

	public function updateLearnerProgress(PDO $link)
	{
		parent::updateProgress($link, $this->getUnitReference(), $this->getQAN());
	}

	public static function getLearningJourneyItems($savers_or_sp = '')
	{
		if($savers_or_sp == 'savers')
			return array(
				"Store standards and replenishment"
				,"Stock rotation"
				,"Damages process"
				,"Date code process"
				,"HHT functions"
				,"Top 30 shrink lines"
				,"Zero to zero"
				,"Pick lists"
				,"Cosmetic ordering (testers and parts)"
				,"Prepare stock areas for delivery"
				,"Deliveries and checking invoices"
				,"Deliveries / tagging / packing away / storage"
				,"Stock take"
			);
		else
			return array(
				"Store standards and replenishment"
				,"Stock rotation"
				,"Damages process"
				,"Date code process"
				,"HHT functions"
				,"Top 30 shrink lines"
				,"Zero to zero"
				,"Pick lists"
				,"Cosmetic ordering (testers and parts)"
				,"Prepare stock areas for delivery"
				,"Deliveries and checking invoices"
				,"Deliveries / tagging / packing away / storage"
				,"Stock take"
			);
	}

	public function getPageTopLine()
	{
		if($this->savers_or_sp == 'savers')
			return '<p class="topLine"><span style="color: blue;">Stock</span> <span style="color: red;">Stock</span> </p>';
		else
			return '<p class="topLine"><span style="color: pink;">Stock</span> <span style="color: gray;">Stock</span> </p>';
	}

	public function getPageBottomLine()
	{
		if($this->savers_or_sp == 'savers')
			return '<p class="bottomLine"><span style="color: blue;">Stock</span> <span style="color: red;">Stock</span> </p>';
		else
			return '<p class="bottomLine"><span style="color: pink;">Stock</span> <span style="color: gray;">Stock</span> </p>';
	}

	public function getQAN()
	{
		return Workbook::RETAIL_QAN;
	}
}
?>