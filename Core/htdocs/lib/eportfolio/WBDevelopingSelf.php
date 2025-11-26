<?php
class WBDevelopingSelf extends Workbook
{
	public function __construct($tr_id)
	{
		$this->wb_title = 'WBDevelopingSelf';
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
<Workbook title="developing_self">
	<TrainingRecordID></TrainingRecordID>
	<Answers>
		<SelfAssessment></SelfAssessment>
		<LearningStyles></LearningStyles>
		<SWOT>
			<Strength></Strength>
			<Weakness></Weakness>
			<Opportunity></Opportunity>
			<Threat></Threat>
		</SWOT>
		<PersonalDevelopmentPlan></PersonalDevelopmentPlan>
		<Research></Research>
	</Answers>
	<Feedback>
		<SelfAssessment>
			<Status></Status>
			<Comments></Comments>
		</SelfAssessment>
		<LearningStyles>
			<Status></Status>
			<Comments></Comments>
		</LearningStyles>
		<SWOT>
			<Status></Status>
			<Comments></Comments>
		</SWOT>
		<PersonalDevelopmentPlan>
			<Status></Status>
			<Comments></Comments>
		</PersonalDevelopmentPlan>
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
		if(isset($feedback->SelfAssessment->Status) && $feedback->SelfAssessment->Status->__toString() == 'A')
			$total += 25;
		if(isset($feedback->LearningStyles->Status) && $feedback->LearningStyles->Status->__toString() == 'A')
			$total += 25;
		if(isset($feedback->SWOT->Status) && $feedback->SWOT->Status->__toString() == 'A')
			$total += 25;
		if(isset($feedback->PersonalDevelopmentPlan->Status) && $feedback->PersonalDevelopmentPlan->Status->__toString() == 'A')
			$total += 25;

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
			if($section == 'SelfAssessment')
			{
				$lookup_result = DAO::getResultset($link, 'SELECT * FROM lookup_wb_dev_self_self_assessment', DAO::FETCH_ASSOC);
				$lookup = array();
				foreach($lookup_result AS $row)
				{
					$lookup[$row['id']] = $row['description'];
				}
				foreach($results AS $row)
				{
					$log_xml = $row['wb_content'];
					$log_xml = XML::loadSimpleXML($log_xml);
					$saved_self_assessment = explode(',', $log_xml->Answers->SelfAssessment->__toString());
					$html .= '<table class="table small"><tr><th colspan="2">Modified DateTime: ' . Date::to($row['created'], Date::DATETIME) . '</th></tr>';
					foreach($lookup AS $key => $value)
					{
						if(in_array($key, $saved_self_assessment))
							$html .= '<tr><td>' . $value . '</td><td><label class="external-event bg-green" style="cursor: text;">YES</label></td></tr>';
						else
							$html .= '<tr><td>' . $value . '</td><td><label class="external-event bg-red" style="cursor: text;">NO</label></td></tr>';
					}
					$html .= '</table><hr>';
				}
			}
			if($section == 'LearningStyles')
			{
				$lookup_result = DAO::getResultset($link, 'SELECT * FROM lookup_wb_dev_self_learn_styles', DAO::FETCH_ASSOC);
				$lookup = array();
				foreach($lookup_result AS $row)
				{
					$lookup[$row['id']] = $row['description'];
				}
				foreach($results AS $row)
				{
					$log_xml = $row['wb_content'];
					$log_xml = XML::loadSimpleXML($log_xml);
					$saved_self_assessment = explode(',', $log_xml->Answers->LearningStyles->__toString());
					$html .= '<table class="table small"><tr><th>Modified DateTime: ' . Date::to($row['created'], Date::DATETIME) . '</th></tr>';
					foreach($lookup AS $key => $value)
					{
						if(in_array($key, $saved_self_assessment))
							$html .= '<tr><td>' . $value . '</td></tr>';
					}
					$html .= '</table><hr>';
				}
			}
			if($section == 'SWOT')
			{
				foreach($results AS $row)
				{
					$log_xml = $row['wb_content'];
					$log_xml = XML::loadSimpleXML($log_xml);
					$html .= '<p class="text-bold">Modified DateTime: ' . Date::to($row['created'], Date::DATETIME) . '</p>';
					$html .= '<table class="table table-bordered small"><tr><th>Strength</th><th>Weakness</th><th>Opportunity</th><th>Threat</th></tr>';
					$html .= '<tr>';
					$html .= '<td>' . $log_xml->Answers->SWOT->Strength->__toString() . '</td>';
					$html .= '<td>' . $log_xml->Answers->SWOT->Weakness->__toString() . '</td>';
					$html .= '<td>' . $log_xml->Answers->SWOT->Opportunity->__toString() . '</td>';
					$html .= '<td>' . $log_xml->Answers->SWOT->Threat->__toString() . '</td>';
					$html .= '</tr>';
					$html .= '</table><hr>';
				}
			}
			if($section == 'PersonalDevelopmentPlan')
			{
				foreach($results AS $row)
				{
					$log_xml = $row['wb_content'];
					$log_xml = XML::loadSimpleXML($log_xml);
					$html .= '<p class="text-bold">Modified DateTime: ' . Date::to($row['created'], Date::DATETIME) . '</p>';
					$html .= '<table class="small table-bordered"><tr><th>Objective</th><th>Resource</th><th>Target</th><th>Review</th></tr>';
					for($i = 1; $i <= 6; $i++)
					{
						$key = 'Set'.$i;
						$f1_val = isset($log_xml->Answers->PersonalDevelopmentPlan->$key->Objective)?$log_xml->Answers->PersonalDevelopmentPlan->$key->Objective:'';
						$f2_val = isset($log_xml->Answers->PersonalDevelopmentPlan->$key->Resource)?$log_xml->Answers->PersonalDevelopmentPlan->$key->Resource:'';
						$f3_val = isset($log_xml->Answers->PersonalDevelopmentPlan->$key->Target)?$log_xml->Answers->PersonalDevelopmentPlan->$key->Target:'';
						$f4_val = isset($log_xml->Answers->PersonalDevelopmentPlan->$key->Review)?$log_xml->Answers->PersonalDevelopmentPlan->$key->Review:'';
						if($f1_val == '' && $f2_val == '' && $f3_val == '' && $f4_val == '')
							continue;
						$html .=  '<tr>';
						$html .=  '<td>' . $f1_val . '</td>';
						$html .=  '<td>' . $f2_val . '</td>';
						$html .=  '<td>' . $f3_val . '</td>';
						$html .=  '<td>' . $f4_val . '</td>';
						$html .=  '</tr>';
					}
					$html .= '</table><hr>';
				}
			}
		}
		return $html;
	}

	public function getUnitReference()
	{
		return 'Unit 02';
	}

	public function getStepsWithQuestions()
	{
		return '3,4,11,13';
	}

	public function updateLearnerProgress(PDO $link)
	{
		parent::updateProgress($link, $this->getUnitReference(), $this->getQAN());
	}

	public function getPageTopLine()
	{
		if($this->savers_or_sp == 'savers')
			return '<p class="topLine"><span style="color: blue;">Developing self</span> <span style="color: red;">Developing self</span> </p>';
		else
			return '<p class="topLine"><span style="color: pink;">Developing self</span> <span style="color: gray;">Developing self</span> </p>';
	}

	public function getPageBottomLine()
	{
		if($this->savers_or_sp == 'savers')
			return '<p class="bottomLine"><span style="color: blue;">Developing self</span> <span style="color: red;">Developing self</span> </p>';
		else
			return '<p class="bottomLine"><span style="color: pink;">Developing self</span> <span style="color: gray;">Developing self</span> </p>';
	}

	public function getQAN()
	{
		return Workbook::CS_QAN;
	}
}
?>