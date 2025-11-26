<?php
class WBRoleResponsibility extends Workbook
{
	public function __construct($tr_id)
	{
		$this->wb_title = 'WBRoleResponsibility';
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
<Workbook title="role_responsibility">
	<TrainingRecordID></TrainingRecordID>
	<Answers>
		<DefinitionsRoleAndResponsibilities>
			<JobRole></JobRole>
			<Responsibilities></Responsibilities>
			<DailyWorkObjectives></DailyWorkObjectives>
			<OtherWOrkObjectives></OtherWOrkObjectives>
			<BusinessTargetObjectives></BusinessTargetObjectives>
		</DefinitionsRoleAndResponsibilities>
		<SMARTObjectives>
			<Objective1>
				<Type></Type>
				<Comments></Comments>
			</Objective1>
			<Objective2>
				<Type></Type>
				<Comments></Comments>
			</Objective2>
			<Objective3>
				<Type></Type>
				<Comments></Comments>
			</Objective3>
			<YourSMARTObjective></YourSMARTObjective>
			<ImpactOfNoWorkObjective></ImpactOfNoWorkObjective>
			<YourRoleAndResponsibilitiesImpactOnTeamGoal></YourRoleAndResponsibilitiesImpactOnTeamGoal>
		</SMARTObjectives>
		<EisenhowerPrinciple>
			<Do></Do>
			<Decide></Decide>
			<Delegate></Delegate>
			<Delete></Delete>
		</EisenhowerPrinciple>
		<ToolsTechniquesToMonitorProgress>
			<HowToCheckTaskIsBeingCompleted></HowToCheckTaskIsBeingCompleted>
			<ToolsToMonitorProgress></ToolsToMonitorProgress>
			<TechniquesToMonitorProgress></TechniquesToMonitorProgress>
			<TaskNotGoingToPlan></TaskNotGoingToPlan>
		</ToolsTechniquesToMonitorProgress>
		<QualificationQuestions>
			<Unit1_1></Unit1_1>
			<Unit1_2></Unit1_2>
			<Unit1_3></Unit1_3>
		</QualificationQuestions>
		<Research></Research>
	</Answers>
	<Feedback>
		<DefinitionsRoleAndResponsibilities>
			<Status></Status>
			<Comments></Comments>
		</DefinitionsRoleAndResponsibilities>
		<SMARTObjectives>
			<Status></Status>
			<Comments></Comments>
		</SMARTObjectives>
		<EisenhowerPrinciple>
			<Status></Status>
			<Comments></Comments>
		</EisenhowerPrinciple>
		<ToolsTechniquesToMonitorProgress>
			<Status></Status>
			<Comments></Comments>
		</ToolsTechniquesToMonitorProgress>
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
			if($section == 'DefinitionsRoleAndResponsibilities')
			{
				foreach($results AS $row)
				{
					$log_xml = $row['wb_content'];
					$log_xml = XML::loadSimpleXML($log_xml);
					$Answers = $log_xml->Answers;
					$html .= '<table class="table small"><tr class="bg-gray"><th colspan="2">Modified DateTime: ' . Date::to($row['created'], Date::DATETIME) . '</th></tr>';
					$html .= '<tr><th>Job role</th><td>' . $Answers->$section->JobRole->__toString() . '</td></tr>';
					$html .= '<tr><th>Responsibilities</th><td>' . $Answers->$section->Responsibilities->__toString() . '</td></tr>';
					$html .= '<tr><th>List three other work objectives</th><td>' . $Answers->$section->DailyWorkObjectives->__toString() . '</td></tr>';
					$html .= '<tr><th>Weekly/fortnightly/monthly objectives</th><td>' . $Answers->$section->OtherWOrkObjectives->__toString() . '</td></tr>';
					$html .= '<tr><th>Business targets objectives</th><td>' . $Answers->$section->BusinessTargetObjectives->__toString() . '</td></tr>';
					$html .= '</table><hr>';
				}
			}
			if($section == 'SMARTObjectives')
			{
				foreach($results AS $row)
				{
					$log_xml = $row['wb_content'];
					$log_xml = XML::loadSimpleXML($log_xml);
					$Answers = $log_xml->Answers;
					$html .= '<table class="table small"><tr class="bg-gray"><th colspan="2">Modified DateTime: ' . Date::to($row['created'], Date::DATETIME) . '</th></tr>';
					$html .= '<tr><td colspan="2"><table class="table">';
					$html .= '<tr><th>Objective Type</th><th>Objective Comments</th></tr>';
					if($Answers->$section->Objective1->Type->__toString() == 'S')
						$html .= '<tr><th>It\'s 9am now so I would like you to sell 3 razor packs by the end of your shift at 5pm.</th><td>SMART</td><td>' . $Answers->$section->Objective1->Comments->__toString() . '</td></tr>';
					else
						$html .= '<tr><th>It\'s 9am now so I would like you to sell 3 razor packs by the end of your shift at 5pm.</th><td>NOT SMART</td><td>' . $Answers->$section->Objective1->Comments->__toString() . '</td></tr>';
					if($Answers->$section->Objective2->Type->__toString() == 'S')
						$html .= '<tr><th>You have one hour to put 3 cages of stock out on the skin section.</th><td>SMART</td><td>' . $Answers->$section->Objective2->Comments->__toString() . '</td></tr>';
					else
						$html .= '<tr><th>You have one hour to put 3 cages of stock out on the skin section.</th><td>NOT SMART</td><td>' . $Answers->$section->Objective2->Comments->__toString() . '</td></tr>';
					if($Answers->$section->Objective1->Type->__toString() == 'S')
						$html .= '<tr><th>Please put the POS out at the front of the store</th><td>SMART</td><td>' . $Answers->$section->Objective3->Comments->__toString() . '</td></tr>';
					else
						$html .= '<tr><th>Please put the POS out at the front of the store</th><td>NOT SMART</td><td>' . $Answers->$section->Objective3->Comments->__toString() . '</td></tr>';
					$html .= '</table> </td></tr>';
					$html .= '<tr><th>Smart objective example</th><td>' . $Answers->$section->YourSMARTObjective->__toString() . '</td></tr>';
					$html .= '<tr><th>Impact of no work objectives</th><td>' . $Answers->$section->ImpactOfNoWorkObjective->__toString() . '</td></tr>';
					$html .= '<tr><th>Impact of your role and responsibilities</th><td>' . $Answers->$section->YourRoleAndResponsibilitiesImpactOnTeamGoal->__toString() . '</td></tr>';
					$html .= '</table><hr>';
				}
			}
			if($section == 'EisenhowerPrinciple')
			{
				foreach($results AS $row)
				{
					$log_xml = $row['wb_content'];
					$log_xml = XML::loadSimpleXML($log_xml);
					$Answers = $log_xml->Answers;
					$html .= '<table class="table small"><tr class="bg-gray"><th colspan="2">Modified DateTime: ' . Date::to($row['created'], Date::DATETIME) . '</th></tr>';
					$html .= '<tr><th>Do</th><td>' . $Answers->$section->Do->__toString() . '</td></tr>';
					$html .= '<tr><th>Decide</th><td>' . $Answers->$section->Decide->__toString() . '</td></tr>';
					$html .= '<tr><th>Delegate</th><td>' . $Answers->$section->Delegate->__toString() . '</td></tr>';
					$html .= '<tr><th>Delete</th><td>' . $Answers->$section->Delete->__toString() . '</td></tr>';
					$html .= '</table><hr>';
				}
			}
			if($section == 'ToolsTechniquesToMonitorProgress')
			{
				foreach($results AS $row)
				{
					$log_xml = $row['wb_content'];
					$log_xml = XML::loadSimpleXML($log_xml);
					$Answers = $log_xml->Answers;
					$html .= '<table class="table small"><tr class="bg-gray"><th colspan="2">Modified DateTime: ' . Date::to($row['created'], Date::DATETIME) . '</th></tr>';
					$html .= '<tr><th>What does your team currently do in store to check and monitor whether a task is being completed on time?</th><td>' . $Answers->$section->HowToCheckTaskIsBeingCompleted->__toString() . '</td></tr>';
					$html .= '<tr><th>What other tools could you use to monitor the progress of a task?</th><td>' . $Answers->$section->ToolsToMonitorProgress->__toString() . '</td></tr>';
					$html .= '<tr><th>What techniques could you use to monitor the progress of a task?</th><td>' . $Answers->$section->TechniquesToMonitorProgress->__toString() . '</td></tr>';
					$html .= '<tr><th>What would you do if a task wasn’t going to plan? How would you deal with the situation?</th><td>' . $Answers->$section->TaskNotGoingToPlan->__toString() . '</td></tr>';
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
					$html .= '<tr><th>Unit 1.1</th><td>' . $Answers->$section->Unit1_1->__toString() . '</td></tr>';
					$html .= '<tr><th>Unit 1.2</th><td>' . $Answers->$section->Unit1_2->__toString() . '</td></tr>';
					$html .= '<tr><th>Unit 1.3</th><td>' . $Answers->$section->Unit1_3->__toString() . '</td></tr>';
					$html .= '</table><hr>';
				}
			}
		}
		return $html;
	}

	public function getUnitReference()
	{
		return 'Unit 05';
	}

	public function getStepsWithQuestions()
	{
		return '2,3,5,6,7';
	}

	public function updateLearnerProgress(PDO $link)
	{
		parent::updateProgress($link, $this->getUnitReference(), $this->getQAN());
	}

	public function getPageTopLine()
	{
		if($this->savers_or_sp == 'savers')
			return '<p class="topLine"><span style="color: blue;">Your role and responsibility</span> <span style="color: red;">Personal Organisation</span> </p>';
		else
			return '<p class="topLine"><span style="color: pink;">Your role and responsibility</span> <span style="color: gray;">Personal Organisation</span> </p>';
	}

	public function getPageBottomLine()
	{
		if($this->savers_or_sp == 'savers')
			return '<p class="bottomLine"><span style="color: blue;">Your role and responsibility</span> <span style="color: red;">Personal Organisation</span> </p>';
		else
			return '<p class="bottomLine"><span style="color: pink;">Your role and responsibility</span> <span style="color: gray;">Personal Organisation</span> </p>';
	}

	public function getQAN()
	{
		return Workbook::CS_QAN;
	}
}
?>