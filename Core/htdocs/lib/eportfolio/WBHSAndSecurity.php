<?php
class WBHSAndSecurity extends Workbook
{
	public function __construct($tr_id)
	{
		$this->wb_title = 'WBHSAndSecurity';
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
			$journey .= '<DC'.$i.'></DC'.$i.'>';
		$journey .= '</Journey>';
		$xml = <<<XML
<Workbook title="hs_and_security">
	<TrainingRecordID></TrainingRecordID>
	<Answers>
		$journey
		<Responsibilities>
			<Set1>
				<Employer></Employer>
				<Employee></Employee>
			</Set1>
			<Set2>
				<Employer></Employer>
				<Employee></Employee>
			</Set2>
			<Set3>
				<Employer></Employer>
				<Employee></Employee>
			</Set3>
		</Responsibilities>
		<Hazards>
			<COSHH1></COSHH1>
			<COSHH2></COSHH2>
			<COSHH3></COSHH3>
		</Hazards>
		<WorkplaceSafety>
			<Q1></Q1>
			<Q2></Q2>
			<Q3></Q3>
			<Q4></Q4>
			<Q5></Q5>
			<Q6></Q6>
			<Q7></Q7>
			<Q8></Q8>
		</WorkplaceSafety>
		<Security>
			<DC1></DC1>
			<DC2></DC2>
			<DC3></DC3>
			<Q1></Q1>
			<Q2></Q2>
			<Q3></Q3>
			<Q4></Q4>
			<Q5></Q5>
			<Q6></Q6>
			<Q7></Q7>
			<Q8></Q8>
		</Security>
		<Research></Research>
	</Answers>
	<Feedback>
		<Journey>
			<Status></Status>
			<Comments></Comments>
		</Journey>
		<Responsibilities>
			<Status></Status>
			<Comments></Comments>
		</Responsibilities>
		<Hazards>
			<Status></Status>
			<Comments></Comments>
		</Hazards>
		<WorkplaceSafety>
			<Status></Status>
			<Comments></Comments>
		</WorkplaceSafety>
		<Security>
			<Status></Status>
			<Comments></Comments>
		</Security>
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
		if(isset($feedback->Responsibilities->Status) && $feedback->Responsibilities->Status->__toString() == 'A')
			$total += 33.33;
		if(isset($feedback->WorkplaceSafety->Status) && $feedback->WorkplaceSafety->Status->__toString() == 'A')
			$total += 33.33;
		if(isset($feedback->Security->Status) && $feedback->Security->Status->__toString() == 'A')
			$total += 33.33;

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
			if($section == 'Responsibilities')
			{
				foreach($results AS $row)
				{
					$log_xml = $row['wb_content'];
					$log_xml = XML::loadSimpleXML($log_xml);
					$html .= '<table class="table small"><tr><th colspan="2">Modified DateTime: ' . Date::to($row['created'], Date::DATETIME) . '</th></tr>';
					$html .= '<tr><th>Employer Responsibilities</th><th>Employee Responsibilities</th></tr>';
					for($i = 1; $i <= 3; $i++)
					{
						$set = 'Set'.$i;
						$html .= '<tr><td>' . $log_xml->Answers->$section->$set->Employer->__toString() . '</td><td>' . $log_xml->Answers->$section->$set->Employee->__toString() . '</td></tr>';
					}
					$html .= '</table><hr>';
				}
			}
			if($section == 'Hazards')
			{
				foreach($results AS $row)
				{
					$log_xml = $row['wb_content'];
					$log_xml = XML::loadSimpleXML($log_xml);
					$html .= '<table class="table small"><tr><th colspan="3">Modified DateTime: ' . Date::to($row['created'], Date::DATETIME) . '</th></tr>';
					$html .= '<tr><th>COSHH Symbol</th><th>COSHH Symbol</th><th>COSHH Symbol</th></tr>';
					$html .= '<tr><td>' . $log_xml->Answers->$section->COSHH1->__toString() . '</td><td>' . $log_xml->Answers->$section->COSHH2->__toString() . '</td><td>' . $log_xml->Answers->$section->COSHH3->__toString() . '</td></tr>';
					$html .= '</table><hr>';
				}
			}
			if($section == 'WorkplaceSafety' || $section == 'Security')
			{
				foreach($results AS $row)
				{
					$log_xml = $row['wb_content'];
					$log_xml = XML::loadSimpleXML($log_xml);
					$html .= '<table class="table small"><tr><th colspan="2">Modified DateTime: ' . Date::to($row['created'], Date::DATETIME) . '</th></tr>';
					for($i = 1; $i <= 8; $i++)
					{
						$key = 'Q'.$i;
						$html .= '<tr><th>'.$key.'</th><td>'.$log_xml->Answers->$section->$key->__toString().'</td></tr>';
					}
					$html .= '</table><hr>';
				}
			}
		}
		return $html;
	}

	public function getUnitReference()
	{
		return 'Unit 01';
	}

	public function getStepsWithQuestions()
	{
		return '2,3,6,11';
	}

	public function updateLearnerProgress(PDO $link)
	{
		parent::updateProgress($link, $this->getUnitReference(), $this->getQAN());
	}

	public function getPageTopLine()
	{
		if($this->savers_or_sp == 'savers')
			return '<p class="topLine"><span style="color: blue;">H&S and Security</span> <span style="color: red;">H&S and Security</span> </p>';
		else
			return '<p class="topLine"><span style="color: pink;">H&S and Security</span> <span style="color: gray;">H&S and Security</span> </p>';
	}

	public function getPageBottomLine()
	{
		if($this->savers_or_sp == 'savers')
			return '<p class="bottomLine"><span style="color: blue;">H&S and Security</span> <span style="color: red;">H&S and Security</span> </p>';
		else
			return '<p class="bottomLine"><span style="color: pink;">H&S and Security</span> <span style="color: gray;">H&S and Security</span> </p>';
	}

	public static function getLearningJourneyItems($savers_or_sp = '')
	{
		if($savers_or_sp == 'savers')
			return array(
				'Welcome'
			,'Sales Orientation'
			,'Healthcare'
			,'Fire safety awareness'
			,'Manual Handling and roll cages'
			,'Slips and trips'
			,'COSHH Self clean and welfare'
			,'Lifts and conveyers'
			,'Shelving, storage and stacking'
			,'Highfield Health and safety'
			,'Highfield Substance misuse'
			);
		else
			return array(
				'Welcome'
			,'Sales Orientation'
			,'Healthcare'
			,'Fire safety awareness'
			,'Manual Handling and roll cages'
			,'Slips and trips'
			,'COSHH Self clean and welfare'
			,'Lifts and conveyers'
			,'Shelving, storage and stacking'
			,'Highfield Health and safety'
			,'Highfield Substance misuse'
			);
	}

	public function getQAN()
	{
		return Workbook::RETAIL_QAN;
	}
}
?>