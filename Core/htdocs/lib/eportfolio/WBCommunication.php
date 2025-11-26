<?php
class WBCommunication extends Workbook
{
	public function __construct($tr_id)
	{
		$this->wb_title = 'WBCommunication';
		$this->tr_id = $tr_id;
		$link = DAO::getConnection();
		$retail_qual = DAO::getSingleValue($link, "SELECT COUNT(*) FROM student_qualifications WHERE tr_id = '{$tr_id}' AND REPLACE(id, '/', '') = '" . Workbook::RETAIL_QAN . "'");
		if($retail_qual > 0)
			$this->wb_content = self::getBlankXMLForRetail();
		else
			$this->wb_content = self::getBlankXML();
	}

	/**
	 * @return xml
	 * @param null
	 */
	public static function getBlankXMLForRetail()
	{
		$xml = <<<XML
<Workbook title="communication">
	<TrainingRecordID></TrainingRecordID>
	<Answers>
		<PoorCommunicationExamples></PoorCommunicationExamples>
		<ExampleToOvercomeNegativeCommunication></ExampleToOvercomeNegativeCommunication>
		<BodyLanguageSkills>
			<Positive></Positive>
			<Negative></Negative>
		</BodyLanguageSkills>
		<CustomerScenarios>
			<Scenario1Reply></Scenario1Reply>
			<Scenario2Reply></Scenario2Reply>
			<Scenario3Reply></Scenario3Reply>
		</CustomerScenarios>
		<EmpathyExample></EmpathyExample>
		<CommunicationMethodsImportance>
			<Why></Why>
			<Which></Which>
		</CommunicationMethodsImportance>
		<QualificationQuestions>
			<Unit2_1></Unit2_1>
			<Unit2_2></Unit2_2>
			<Unit3_1></Unit3_1>
			<Unit3_2></Unit3_2>
		</QualificationQuestions>
		<Research></Research>
	</Answers>
	<Feedback>
		<PoorCommunicationExamples>
			<Status></Status>
			<Comments></Comments>
		</PoorCommunicationExamples>
		<ExampleToOvercomeNegativeCommunication>
			<Status></Status>
			<Comments></Comments>
		</ExampleToOvercomeNegativeCommunication>
		<BodyLanguageSkills>
			<Status></Status>
			<Comments></Comments>
		</BodyLanguageSkills>
		<CustomerScenarios>
			<Status></Status>
			<Comments></Comments>
		</CustomerScenarios>
		<EmpathyExample>
			<Status></Status>
			<Comments></Comments>
		</EmpathyExample>
		<CommunicationMethodsImportance>
			<Status></Status>
			<Comments></Comments>
		</CommunicationMethodsImportance>
		<QualificationQuestions>
			<Status></Status>
			<Comments></Comments>
		</QualificationQuestions>
	</Feedback>
</Workbook>
XML;

		return XML::loadSimpleXML($xml);
	}

	/**
	 * @return xml
	 * @param null
	 */
	public static function getBlankXML()
	{
		$xml = <<<XML
<Workbook title="communication">
	<TrainingRecordID></TrainingRecordID>
	<Answers>
		<PoorCommunicationExamples></PoorCommunicationExamples>
		<ExampleToOvercomeNegativeCommunication></ExampleToOvercomeNegativeCommunication>
		<BodyLanguageSkills>
			<Positive></Positive>
			<Negative></Negative>
		</BodyLanguageSkills>
		<CustomerScenarios>
			<Scenario1Reply></Scenario1Reply>
			<Scenario2Reply></Scenario2Reply>
			<Scenario3Reply></Scenario3Reply>
		</CustomerScenarios>
		<EmpathyExample></EmpathyExample>
		<CommunicationMethodsImportance>
			<Why></Why>
			<Which></Which>
		</CommunicationMethodsImportance>
		<Research></Research>
	</Answers>
	<Feedback>
		<PoorCommunicationExamples>
			<Status></Status>
			<Comments></Comments>
		</PoorCommunicationExamples>
		<ExampleToOvercomeNegativeCommunication>
			<Status></Status>
			<Comments></Comments>
		</ExampleToOvercomeNegativeCommunication>
		<BodyLanguageSkills>
			<Status></Status>
			<Comments></Comments>
		</BodyLanguageSkills>
		<CustomerScenarios>
			<Status></Status>
			<Comments></Comments>
		</CustomerScenarios>
		<EmpathyExample>
			<Status></Status>
			<Comments></Comments>
		</EmpathyExample>
		<CommunicationMethodsImportance>
			<Status></Status>
			<Comments></Comments>
		</CommunicationMethodsImportance>
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
		return parent::getCompletedPercentage();
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
			if($section == 'PoorCommunicationExamples')
			{
				foreach($results AS $row)
				{
					$log_xml = $row['wb_content'];
					$log_xml = XML::loadSimpleXML($log_xml);
					$html .= '<p class="text-bold">Modified DateTime: ' . Date::to($row['created'], Date::DATETIME) . '</p>';
					$html .= '<table class="table table-bordered small">';
					$html .= '<tr>';
					$html .= '<td>' . $log_xml->Answers->PoorCommunicationExamples->__toString() . '</td>';
					$html .= '</tr>';
					$html .= '</table><hr>';
				}
			}
			if($section == 'ExampleToOvercomeNegativeCommunication')
			{
				foreach($results AS $row)
				{
					$log_xml = $row['wb_content'];
					$log_xml = XML::loadSimpleXML($log_xml);
					$html .= '<p class="text-bold">Modified DateTime: ' . Date::to($row['created'], Date::DATETIME) . '</p>';
					$html .= '<table class="table table-bordered small">';
					$html .= '<tr>';
					$html .= '<td>' . $log_xml->Answers->ExampleToOvercomeNegativeCommunication->__toString() . '</td>';
					$html .= '</tr>';
					$html .= '</table><hr>';
				}
			}
			if($section == 'EmpathyExample')
			{
				foreach($results AS $row)
				{
					$log_xml = $row['wb_content'];
					$log_xml = XML::loadSimpleXML($log_xml);
					$html .= '<p class="text-bold">Modified DateTime: ' . Date::to($row['created'], Date::DATETIME) . '</p>';
					$html .= '<table class="table table-bordered small">';
					$html .= '<tr>';
					$html .= '<td>' . $log_xml->Answers->EmpathyExample->__toString() . '</td>';
					$html .= '</tr>';
					$html .= '</table><hr>';
				}
			}
			if($section == 'BodyLanguageSkills')
			{
				foreach($results AS $row)
				{
					$log_xml = $row['wb_content'];
					$log_xml = XML::loadSimpleXML($log_xml);
					$html .= '<p class="text-bold">Modified DateTime: ' . Date::to($row['created'], Date::DATETIME) . '</p>';
					$html .= '<table class="table table-bordered small">';
					$html .= '<tr><th>Positive</th><th>Negative</th></tr>';
					$html .= '<tr><td>' . $log_xml->Answers->BodyLanguageSkills->Positive->__toString() . '</td><td>' . $log_xml->Answers->BodyLanguageSkills->Negative->__toString() . '</td></tr>';
					$html .= '</table><hr>';
				}
			}
			if($section == 'CustomerScenarios')
			{
				foreach($results AS $row)
				{
					$log_xml = $row['wb_content'];
					$log_xml = XML::loadSimpleXML($log_xml);
					$html .= '<p class="text-bold">Modified DateTime: ' . Date::to($row['created'], Date::DATETIME) . '</p>';
					$html .= '<table class="table table-bordered small">';
					$html .= '<tr><th>Scenario 1</th><th>Scenario 2</th><th>Scenario 3</th></tr>';
					$html .= '<tr><td>' . $log_xml->Answers->CustomerScenarios->Scenario1Reply->__toString() . '</td><td>' . $log_xml->Answers->CustomerScenarios->Scenario2Reply->__toString() . '</td><td>' . $log_xml->Answers->CustomerScenarios->Scenario3Reply->__toString() . '</td></tr>';
					$html .= '</table><hr>';
				}
			}
			if($section == 'CommunicationMethodsImportance')
			{
				foreach($results AS $row)
				{
					$log_xml = $row['wb_content'];
					$log_xml = XML::loadSimpleXML($log_xml);
					$html .= '<p class="text-bold">Modified DateTime: ' . Date::to($row['created'], Date::DATETIME) . '</p>';
					$html .= '<table class="table table-bordered small">';
					$html .= '<tr><th>Write your own thoughts/opinions of why we need different communication methods</th><td>' . $log_xml->Answers->CommunicationMethodsImportance->Why->__toString() . '</td></tr>';
					$html .= '<tr><th>Which different communication methods do you use in store with colleagues and customers? Think about both formal and informal ways of communicating to others</th><td>' . $log_xml->Answers->CommunicationMethodsImportance->Which->__toString() . '</td></tr>';
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
					$html .= '<tr><th>Unit 1-2.1 Describe different methods of communicating with customers</th><td>' . $Answers->$section->Unit2_1->__toString() . '</td></tr>';
					$html .= '<tr><th>Unit 1-2.2 Describe how to determine an individual\'s situation and needs</th><td>' . $Answers->$section->Unit2_2->__toString() . '</td></tr>';
					$html .= '<tr><th>Unit 2-3.1 Identify what is meant by rapport</th><td>' . $Answers->$section->Unit3_1->__toString() . '</td></tr>';
					$html .= '<tr><th>Unit 2-3.2 Explain how to establish a rapport with customers</th><td>' . $Answers->$section->Unit3_2->__toString() . '</td></tr>';
					$html .= '</table><hr>';
				}
			}
		}
		return $html;
	}

	public function getUnitReference(PDO $link)
	{
		$retail_qual = DAO::getSingleValue($link, "SELECT COUNT(*) FROM student_qualifications WHERE tr_id = '{$this->tr_id}' AND REPLACE(id, '/', '') = '" . Workbook::RETAIL_QAN . "'");
		return $retail_qual > 0 ? 'Unit 03' : 'Unit 07';
	}

	public function getStepsWithQuestions(PDO $link)
	{
		$retail_qual = DAO::getSingleValue($link, "SELECT COUNT(*) FROM student_qualifications WHERE tr_id = '{$this->tr_id}' AND REPLACE(id, '/', '') = '" . Workbook::RETAIL_QAN . "'");
		return $retail_qual > 0 ? '2,3,5,6,7,9,10' : '3,4,6,7,8,10';
	}

	public function updateLearnerProgress(PDO $link)
	{
		parent::updateProgress($link, $this->getUnitReference($link), $this->getQAN($link));
	}

	public function getPageTopLine()
	{
		if($this->savers_or_sp == 'savers')
			return '<p class="topLine"><span style="color: blue;">Communication</span> <span style="color: red;">Communication</span> </p>';
		else
			return '<p class="topLine"><span style="color: pink;">Communication</span> <span style="color: gray;">Communication</span> </p>';
	}

	public function getPageBottomLine()
	{
		if($this->savers_or_sp == 'savers')
			return '<p class="bottomLine"><span style="color: blue;">Communication</span> <span style="color: red;">Communication</span> </p>';
		else
			return '<p class="bottomLine"><span style="color: pink;">Communication</span> <span style="color: gray;">Communication</span> </p>';
	}

	public function getQAN(PDO $link)
	{
		$retail_qual = DAO::getSingleValue($link, "SELECT COUNT(*) FROM student_qualifications WHERE tr_id = '{$this->tr_id}' AND REPLACE(id, '/', '') = '" . Workbook::RETAIL_QAN . "'");
		return $retail_qual > 0 ? Workbook::RETAIL_QAN : Workbook::CS_QAN;
	}
}
?>