<?php
class WBEnvironment extends Workbook
{
	public function __construct($tr_id)
	{
		$this->wb_title = 'WBEnvironment';
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
<Workbook title="environment">
	<TrainingRecordID></TrainingRecordID>
	<Answers>
		<WorkActivitiesImpact>
			<Set1>
				<Activity></Activity>
				<Impact></Impact>
			</Set1>
			<Set2>
				<Activity></Activity>
				<Impact></Impact>
			</Set2>
			<Set3>
				<Activity></Activity>
				<Impact></Impact>
			</Set3>
		</WorkActivitiesImpact>
		<WorkActivitiesImpactImprove>
			<Set1>
				<Activity></Activity>
				<Improvement></Improvement>
			</Set1>
			<Set2>
				<Activity></Activity>
				<Improvement></Improvement>
			</Set2>
			<Set3>
				<Activity></Activity>
				<Improvement></Improvement>
			</Set3>
			<Set4>
				<Activity></Activity>
				<Improvement></Improvement>
			</Set4>
			<Set5>
				<Activity></Activity>
				<Improvement></Improvement>
			</Set5>
			<EnvironmentPolicyImplementation></EnvironmentPolicyImplementation>
		</WorkActivitiesImpactImprove>
		<QualificationQuestions>
			<Unit1_1></Unit1_1>
			<Unit1_2></Unit1_2>
		</QualificationQuestions>
		<Research></Research>
	</Answers>
	<Feedback>
		<WorkActivitiesImpact>
			<Status></Status>
			<Comments></Comments>
		</WorkActivitiesImpact>
		<WorkActivitiesImpactImprove>
			<Status></Status>
			<Comments></Comments>
		</WorkActivitiesImpactImprove>
		<EnvironmentPolicyImplementation>
			<Status></Status>
			<Comments></Comments>
		</EnvironmentPolicyImplementation>
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
		if(isset($feedback->WorkActivitiesImpact->Status) && $feedback->WorkActivitiesImpact->Status->__toString() == 'A')
			$total += 33.33;
		if(isset($feedback->WorkActivitiesImpactImprove->Status) && $feedback->WorkActivitiesImpactImprove->Status->__toString() == 'A')
			$total += 33.33;
		if(isset($feedback->QualificationQuestions->Status) && $feedback->QualificationQuestions->Status->__toString() == 'A')
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
			if($section == 'WorkActivitiesImpact')
			{
				foreach($results AS $row)
				{
					$log_xml = $row['wb_content'];
					$log_xml = XML::loadSimpleXML($log_xml);
					$html .= '<table class="table small"><tr><th colspan="2">Modified DateTime: ' . Date::to($row['created'], Date::DATETIME) . '</th></tr>';
					$html .= '<tr><th>Work Activity</th><th>Impact on the environment</th></tr>';
					for($i = 1; $i <= 3; $i++)
					{
						$set = 'Set'.$i;
						$html .= '<tr><td>' . $log_xml->Answers->WorkActivitiesImpact->$set->Activity->__toString() . '</td><td><td>' . $log_xml->Answers->WorkActivitiesImpact->$set->Impact->__toString() . '</td></tr>';
					}
					$html .= '</table><hr>';
				}
			}
			if($section == 'WorkActivitiesImpactImprove')
			{
				foreach($results AS $row)
				{
					$log_xml = $row['wb_content'];
					$log_xml = XML::loadSimpleXML($log_xml);
					$html .= '<table class="table small"><tr><th colspan="2">Modified DateTime: ' . Date::to($row['created'], Date::DATETIME) . '</th></tr>';
					$html .= '<tr><th>Work Activity</th><th>Ways to improve the impact on the environment</th></tr>';
					for($i = 1; $i <= 5; $i++)
					{
						$set = 'Set'.$i;
						$html .= '<tr><td>' . $log_xml->Answers->WorkActivitiesImpactImprove->$set->Activity->__toString() . '</td><td><td>' . $log_xml->Answers->WorkActivitiesImpactImprove->$set->Improvement->__toString() . '</td></tr>';
					}
					$html .= '<tr><td colspan="2">Details about Superdrug initiative from the Environment policy</td></tr>';
					$html .= '<tr><td colspan="2">'.$log_xml->Answers->WorkActivitiesImpactImprove->EnvironmentPolicyImplementation->__toString().'</td></tr>';
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
					$html .= '<tr><th>Unit 10 1.1</th><td>'.$log_xml->Answers->QualificationQuestions->Unit1_1->__toString().'</td></tr>';
					$html .= '<tr><th>Unit 10 1.2</th><td>'.$log_xml->Answers->QualificationQuestions->Unit1_1->__toString().'</td></tr>';
					$html .= '</table><hr>';
				}
			}
		}
		return $html;
	}

	public function getUnitReference()
	{
		return 'Unit 13';
	}

	public function getStepsWithQuestions()
	{
		return '2,3,4';
	}

	public function updateLearnerProgress(PDO $link)
	{
		parent::updateProgress($link, $this->getUnitReference(), $this->getQAN());
	}

	public function getPageTopLine()
	{
		if($this->savers_or_sp == 'savers')
			return '<p class="topLine"><span style="color: blue;">Environment</span> <span style="color: red;">Environment</span> </p>';
		else
			return '<p class="topLine"><span style="color: pink;">Environment</span> <span style="color: gray;">Environment</span> </p>';
	}

	public function getPageBottomLine()
	{
		if($this->savers_or_sp == 'savers')
			return '<p class="bottomLine"><span style="color: blue;">Environment</span> <span style="color: red;">Environment</span> </p>';
		else
			return '<p class="bottomLine"><span style="color: pink;">Environment</span> <span style="color: gray;">Environment</span> </p>';
	}

	public function getQAN()
	{
		return Workbook::RETAIL_QAN;
	}
}
?>