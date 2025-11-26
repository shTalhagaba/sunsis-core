<?php
class WBTeamWorking extends Workbook
{
	public function __construct($tr_id)
	{
		$this->wb_title = 'WBTeamWorking';
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
<Workbook title="team_working">
	<TrainingRecordID></TrainingRecordID>
	<Answers>
		<Team></Team>
		<EffectiveTeam></EffectiveTeam>
		<TeamDynamics></TeamDynamics>
		<PersuationAndInfluencingSkills>
			<InsideWork></InsideWork>
			<OutsideWork></OutsideWork>
			<YourContributionInTeamMeetings></YourContributionInTeamMeetings>
			<ExamplesOfWorkingAndSupporting></ExamplesOfWorkingAndSupporting>
			<HowYourTeamSupportOthers></HowYourTeamSupportOthers>
		</PersuationAndInfluencingSkills>
		<ImplicationsOfNotWorkingTogether>
			<ExampleGap1></ExampleGap1>
			<ExampleGap2></ExampleGap2>
			<ExampleGap3></ExampleGap3>
			<DetailNotes></DetailNotes>
		</ImplicationsOfNotWorkingTogether>
		<SuperdrugMethods></SuperdrugMethods>
		<Questions>
			<Question1></Question1>
			<Question2></Question2>
			<Question3></Question3>
			<Question4></Question4>
			<Question5></Question5>
			<Question6></Question6>
		</Questions>
		<Research></Research>
	</Answers>
	<Feedback>
		<Team>
			<Status></Status>
			<Comments></Comments>
		</Team>
		<EffectiveTeam>
			<Status></Status>
			<Comments></Comments>
		</EffectiveTeam>
		<TeamDynamics>
			<Status></Status>
			<Comments></Comments>
		</TeamDynamics>
		<PersuationAndInfluencingSkills>
			<Status></Status>
			<Comments></Comments>
		</PersuationAndInfluencingSkills>
		<ImplicationsOfNotWorkingTogether>
			<Status></Status>
			<Comments></Comments>
		</ImplicationsOfNotWorkingTogether>
		<SuperdrugMethods>
			<Status></Status>
			<Comments></Comments>
		</SuperdrugMethods>
		<Questions>
			<Status></Status>
			<Comments></Comments>
		</Questions>
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
			if($section == 'Team' || $section == 'EffectiveTeam' || $section == 'TeamDynamics' || $section == 'SuperdrugMethods')
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
			if($section == 'PersuationAndInfluencingSkills')
			{
				foreach($results AS $row)
				{
					$log_xml = $row['wb_content'];
					$log_xml = XML::loadSimpleXML($log_xml);
					$Answers = $log_xml->Answers;
					$html .= '<table class="table small"><tr class="bg-gray"><th>Modified DateTime: ' . Date::to($row['created'], Date::DATETIME) . '</th></tr>';
					$html .= '<tr><th>Inside Work</th></tr>';
					$html .= '<tr><td>' . $Answers->$section->InsideWork->__toString() . '</td></tr>';
					$html .= '<tr><th>Outside Work</th></tr>';
					$html .= '<tr><td>' . $Answers->$section->OutsideWork->__toString() . '</td></tr>';
					$html .= '<tr><th>When have you attended team briefings or meetings in your store? How did you contribute?</th></tr>';
					$html .= '<tr><td>' . $Answers->$section->YourContributionInTeamMeetings->__toString() . '</td></tr>';
					$html .= '<tr><th>Give some examples of how you work together and support each other</th></tr>';
					$html .= '<tr><td>' . $Answers->$section->ExamplesOfWorkingAndSupporting->__toString() . '</td></tr>';
					$html .= '<tr><th>How your store team may support others in your area</th></tr>';
					$html .= '<tr><td>' . $Answers->$section->HowYourTeamSupportOthers->__toString() . '</td></tr>';
					$html .= '</table><hr>';
				}
			}
			if($section == 'ImplicationsOfNotWorkingTogether')
			{
				foreach($results AS $row)
				{
					$log_xml = $row['wb_content'];
					$log_xml = XML::loadSimpleXML($log_xml);
					$Answers = $log_xml->Answers;
					$html .= '<table class="table small"><tr class="bg-gray"><th colspan="2">Modified DateTime: ' . Date::to($row['created'], Date::DATETIME) . '</th></tr>';
					$html .= '<tr><th>Implications of not working together as a team</th></tr>';
					for($i = 1; $i <= 3; $i++)
					{
						$key = 'ExampleGap'.$i;
						$html .= '<tr><th>Q'.$i.'</th><td>' . $Answers->$section->$key->__toString() . '</td></tr>';
					}
					$html .= '<tr><th>Notes</th><td>' . $Answers->$section->DetailNotes->__toString() . '</td></tr>';
					$html .= '</table><hr>';
				}
			}
			if($section == 'Questions')
			{
				foreach($results AS $row)
				{
					$log_xml = $row['wb_content'];
					$log_xml = XML::loadSimpleXML($log_xml);
					$Answers = $log_xml->Answers;
					$html .= '<table class="table small"><tr class="bg-gray"><th colspan="2">Modified DateTime: ' . Date::to($row['created'], Date::DATETIME) . '</th></tr>';
					for($i = 1; $i <= 6; $i++)
					{
						$key = 'Question'.$i;
						$html .= '<tr><th>'.$key.'</th><td>' . $Answers->$section->$key->__toString() . '</td></tr>';
					}
					$html .= '</table><hr>';
				}
			}
		}
		return $html;
	}

	public function getUnitReference()
	{
		return 'Unit 06';
	}

	public function getStepsWithQuestions()
	{
		return '2,3,4,5,6,7,8';
	}

	public function updateLearnerProgress(PDO $link)
	{
		parent::updateProgress($link, $this->getUnitReference(), $this->getQAN());
	}
	
	public function getPageTopLine()
	{
		if($this->savers_or_sp == 'savers')
			return '<p class="topLine"><span style="color: blue;">Team working</span> <span style="color: red;">Team working</span> </p>';
		else
			return '<p class="topLine"><span style="color: pink;">Team working</span> <span style="color: gray;">Team working</span> </p>';
	}

	public function getPageBottomLine()
	{
		if($this->savers_or_sp == 'savers')
			return '<p class="bottomLine"><span style="color: blue;">Team working</span> <span style="color: red;">Team working</span> </p>';
		else
			return '<p class="bottomLine"><span style="color: pink;">Team working</span> <span style="color: gray;">Team working</span> </p>';
	}

	public function getQAN()
	{
		return Workbook::CS_QAN;
	}
}
?>