<?php
class WBRetailProductAndService extends Workbook
{
	public function __construct($tr_id)
	{
		$this->wb_title = 'WBRetailProductAndService';
		$this->tr_id = $tr_id;
		$this->wb_content = self::getBlankXML();
	}

	public static function getDepartmentsList()
	{
		return array(
			'Cosmetics'
		,'Skin'
		,'Mens'
		,'Medics'
		,'Dental'
		,'Hair'
		,'Electricals'
		);
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
		$features_and_benefits = '<FeaturesAndBenefits>';
		foreach(self::getDepartmentsList() AS $department)
		{
			$features_and_benefits .= '<'.$department.'>';
			$features_and_benefits .= '<Product></Product>';
			$features_and_benefits .= '<Feature></Feature>';
			$features_and_benefits .= '<Benefit></Benefit>';
			$features_and_benefits .= '</'.$department.'>';
		}
		$features_and_benefits .= '</FeaturesAndBenefits>';
		$questions = '';
		for($i = 1; $i <= count(self::getClosedOpenProbingQuestionsList()); $i++)
			$questions .= '<Question'.$i.'></Question'.$i.'>';
		$xml = <<<XML
<Workbook title="retail_product_and_service">
	<TrainingRecordID></TrainingRecordID>
	<Answers>
		$journey
		<ProductAndService></ProductAndService>
		<PreparingToDeliver>
			<Question1></Question1>
			<Question2></Question2>
			<Question3></Question3>
			<Question4></Question4>
			<Question5></Question5>
			<Question6></Question6>
			<OtherSkills></OtherSkills>
		</PreparingToDeliver>
		<KnowingYourProducts></KnowingYourProducts>
		$features_and_benefits
		<ActiveLinkSelling>
			<CurrentStartBuysOffers></CurrentStartBuysOffers>
			<StartBuysBenefits></StartBuysBenefits>
			<MascaraLink></MascaraLink>
			<SelfTanLink></SelfTanLink>
			<ShampooLink></ShampooLink>
		</ActiveLinkSelling>
		<OwnBrand>
			<PromotionExample></PromotionExample>
			<Skin>
				<Branded></Branded>
				<OwnBrand></OwnBrand>
			</Skin>
			<Mens>
				<Branded></Branded>
				<OwnBrand></OwnBrand>
			</Mens>
			<Cosmetics>
				<Branded></Branded>
				<OwnBrand></OwnBrand>
			</Cosmetics>
		</OwnBrand>
		<IdentifyingCustomerNeeds>
			$questions
			<OpenQuestionKeyWords></OpenQuestionKeyWords>
			<ExampleAskingQuestions></ExampleAskingQuestions>
			<ExampleToDoBetter></ExampleToDoBetter>
			<IncorrectQuestionResult></IncorrectQuestionResult>
		</IdentifyingCustomerNeeds>
		<LegalRights>
			<Legislation>
				<CRA2015>
					<Task></Task>
					<Why></Why>
				</CRA2015>
				<TDA1968>
					<Task></Task>
					<Why></Why>
				</TDA1968>
				<PMA2004>
					<Task></Task>
					<Why></Why>
				</PMA2004>
			</Legislation>
		</LegalRights>
		<ExcellentService></ExcellentService>
		<DealConflict>
			<Question1></Question1>
			<Question2></Question2>
			<Question3></Question3>
			<Question4></Question4>
			<Question5></Question5>
			<Question6></Question6>
		</DealConflict>
		<QualificationQuestions>
			<Question1></Question1>
			<Question2></Question2>
			<Question3></Question3>
			<Question4></Question4>
			<Question5></Question5>
			<Question6></Question6>
			<Question7></Question7>
		</QualificationQuestions>
		<QQuestions>
			<QQuestion1></QQuestion1>
			<QQuestion2></QQuestion2>
		</QQuestions>
		<Research></Research>
	</Answers>
	<Feedback>
		<ProductAndService>
			<Status></Status>
			<Comments></Comments>
		</ProductAndService>
		<PreparingToDeliver>
			<Status></Status>
			<Comments></Comments>
		</PreparingToDeliver>
		<KnowingYourProducts>
			<Status></Status>
			<Comments></Comments>
		</KnowingYourProducts>
		<FeaturesAndBenefits>
			<Status></Status>
			<Comments></Comments>
		</FeaturesAndBenefits>
		<ActiveLinkSelling>
			<Status></Status>
			<Comments></Comments>
		</ActiveLinkSelling>
		<OwnBrand>
			<Status></Status>
			<Comments></Comments>
		</OwnBrand>
		<IdentifyingCustomerNeeds>
			<Status></Status>
			<Comments></Comments>
		</IdentifyingCustomerNeeds>
		<LegalRights>
			<Status></Status>
			<Comments></Comments>
		</LegalRights>
		<ExcellentService>
			<Status></Status>
			<Comments></Comments>
		</ExcellentService>
		<DealConflict>
			<Status></Status>
			<Comments></Comments>
		</DealConflict>
		<QualificationQuestions>
			<Status></Status>
			<Comments></Comments>
		</QualificationQuestions>
		<QQuestions>
			<Status></Status>
			<Comments></Comments>
		</QQuestions>
	</Feedback>
	<CriteriaMet></CriteriaMet>
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
			if($section == 'ProductAndService' || $section == 'KnowingYourProducts' || $section == 'ExcellentService')
			{
				foreach($results AS $row)
				{
					$log_xml = $row['wb_content'];
					$log_xml = XML::loadSimpleXML($log_xml);
					$Answers = $log_xml->Answers;
					$html .= '<table class="table small"><tr class="bg-gray"><th>Modified DateTime: ' . Date::to($row['created'], Date::DATETIME) . '</th></tr>';
					$html .= '<tr><td>' . $Answers->$section->__toString() . '<td></tr>';
					$html .= '</table><hr>';
				}
			}
			if($section == 'PreparingToDeliver')
			{
				foreach($results AS $row)
				{
					$log_xml = $row['wb_content'];
					$log_xml = XML::loadSimpleXML($log_xml);
					$Answers = $log_xml->Answers;
					$html .= '<table class="table small"><tr class="bg-gray"><th colspan="2">Modified DateTime: ' . Date::to($row['created'], Date::DATETIME) . '</th></tr>';
					$html .= '<tr><th>Q 1</th><td>' . $Answers->$section->Question1->__toString() . '</td></tr>';
					$html .= '<tr><th>Q 2</th><td>' . $Answers->$section->Question2->__toString() . '</td></tr>';
					$html .= '<tr><th>Q 3</th><td>' . $Answers->$section->Question3->__toString() . '</td></tr>';
					$html .= '<tr><th>Q 4</th><td>' . $Answers->$section->Question4->__toString() . '</td></tr>';
					$html .= '<tr><th>Other Skills</th><td>' . $Answers->$section->OtherSkills->__toString() . '</td></tr>';
					$html .= '</table><hr>';
				}
			}
			if($section == 'FeaturesAndBenefits')
			{
				foreach($results AS $row)
				{
					$log_xml = $row['wb_content'];
					$log_xml = XML::loadSimpleXML($log_xml);
					$Answers = $log_xml->Answers;
					$html .= '<table class="table small"><tr class="bg-gray"><th colspan="4">Modified DateTime: ' . Date::to($row['created'], Date::DATETIME) . '</th></tr>';
					$html .= '<tr><th>Department</th><th>Product</th><th>Feature</th><th>Benefit</th></tr>';
					foreach(self::getDepartmentsList() AS $department)
					{
						$html .= '<tr><th> '. $department . '</th><td>' . $Answers->$section->$department->Product->__toString() . '</td><td>' . $Answers->$section->$department->Feature->__toString() . '</td><td>' . $Answers->$section->$department->Benefit->__toString() . '</td></tr>';
					}
					$html .= '</table><hr>';
				}
			}
			if($section == 'ActiveLinkSelling')
			{
				foreach($results AS $row)
				{
					$log_xml = $row['wb_content'];
					$log_xml = XML::loadSimpleXML($log_xml);
					$Answers = $log_xml->Answers;
					$html .= '<table class="table small"><tr class="bg-gray"><th colspan="2">Modified DateTime: ' . Date::to($row['created'], Date::DATETIME) . '</th></tr>';
					$html .= '<tr><th colspan="2">Current Star Buys offer in store</th></tr>';
					$html .= '<tr><td colspan="2">' . $Answers->$section->CurrentStartBuysOffers->__toString() . '</td></tr>';
					$html .= '<tr><th colspan="2">How would you sell Start Buys to customer?</th></tr>';
					$html .= '<tr><td colspan="2">' . $Answers->$section->StartBuysBenefits->__toString() . '</td></tr>';
					$html .= '<tr><td colspan="2"><table class="table">';
					$html .= '<tr><th>Product</th><th>Link Selling Item</th></tr>';
					$html .= '<tr><th>Mascara</th><td>' . $Answers->$section->MascaraLink->__toString() . '</td></tr>';
					$html .= '<tr><th>Self-tan</th><td>' . $Answers->$section->SelfTanLink->__toString() . '</td></tr>';
					$html .= '<tr><th>Shampoo</th><td>' . $Answers->$section->ShampooLink->__toString() . '</td></tr>';
					$html .= '</table></td></tr>';
					$html .= '</table><hr>';
				}
			}
			if($section == 'OwnBrand')
			{
				foreach($results AS $row)
				{
					$log_xml = $row['wb_content'];
					$log_xml = XML::loadSimpleXML($log_xml);
					$Answers = $log_xml->Answers;
					$html .= '<table class="table small"><tr class="bg-gray"><th colspan="2">Modified DateTime: ' . Date::to($row['created'], Date::DATETIME) . '</th></tr>';
					$html .= '<tr><th colspan="2">Own brand promotion example</th></tr>';
					$html .= '<tr><td colspan="2">' . $Answers->$section->PromotionExample->__toString() . '</td></tr>';
					$html .= '<tr><td colspan="2"><table class="table">';
					$html .= '<tr><th>Department</th><th>Branded</th><th>Own brand recommendation</th></tr>';
					$html .= '<tr><th>Skin</th><td>' . $Answers->$section->Skin->Branded->__toString() . '</td><td>' . $Answers->$section->Skin->OwnBrand->__toString() . '</td></tr>';
					$html .= '<tr><th>Mens</th><td>' . $Answers->$section->Mens->Branded->__toString() . '</td><td>' . $Answers->$section->Mens->OwnBrand->__toString() . '</td></tr>';
					$html .= '<tr><th>Cosmetics</th><td>' . $Answers->$section->Cosmetics->Branded->__toString() . '</td><td>' . $Answers->$section->Cosmetics->OwnBrand->__toString() . '</td></tr>';
					$html .= '</table></td></tr>';
					$html .= '</table><hr>';
				}
			}
			if($section == 'IdentifyingCustomerNeeds')
			{
				foreach($results AS $row)
				{
					$log_xml = $row['wb_content'];
					$log_xml = XML::loadSimpleXML($log_xml);
					$Answers = $log_xml->Answers;
					$html .= '<table class="table small"><tr class="bg-gray"><th colspan="2">Modified DateTime: ' . Date::to($row['created'], Date::DATETIME) . '</th></tr>';
					$html .= '<tr><th colspan="2">Open, closed, or probing questions</th></tr>';
					$questions = self::getClosedOpenProbingQuestionsList();
					$i = 0;
					foreach($questions AS $q)
					{
						$key = 'Question'.++$i;
						$html .= '<tr><th>' . $q . '</th>';
						if(!isset($Answers->$section->$key))
						{
							$html .= '<td></td>';
						}
						else
						{
							if($Answers->$section->$key->__toString() == 'O')
								$html .= '<td>Open</td>';
							if($Answers->$section->$key->__toString() == 'C')
								$html .= '<td>Closed</td>';
							if($Answers->$section->$key->__toString() == 'P')
								$html .= '<td>Probing</td>';
						}
						$html .= '</tr>';
					}
					$html .= '<tr><th colspan="2">Identify the keywords</td></tr>';
					$html .= '<tr><td colspan="2">' . $Answers->$section->OpenQuestionKeyWords->__toString() . '</td></tr>';
					$html .= '<tr><th colspan="2">Give an example of when you identified a customer\'s needs by asking them questions. What happened, did they make a purchase?</td></tr>';
					$html .= '<tr><td colspan="2">' . $Answers->$section->ExampleAskingQuestions->__toString() . '</td></tr>';
					$html .= '<tr><th colspan="2">Give an example of when you think you could have done more to identify a custome\'s needs.<br>What happened?</td></tr>';
					$html .= '<tr><td colspan="2">' . $Answers->$section->ExampleToDoBetter->__toString() . '</td></tr>';
					$html .= '<tr><th colspan="2">If you don\'t ask the right type of questions what do you think will happen</td></tr>';
					$html .= '<tr><td colspan="2">' . $Answers->$section->IncorrectQuestionResult->__toString() . '</td></tr>';
					$html .= '</table><hr>';
				}
			}
			if($section == 'LegalRights')
			{
				foreach($results AS $row)
				{
					$log_xml = $row['wb_content'];
					$log_xml = XML::loadSimpleXML($log_xml);
					$Answers = $log_xml->Answers;
					$html .= '<table class="table small"><tr class="bg-gray"><th colspan="3">Modified DateTime: ' . Date::to($row['created'], Date::DATETIME) . '</th></tr>';
					$html .= '<tr><th>Legislation</th><th>Task/Procedure</th><th>Why is this important?</th></tr>';
					$html .= '<tr><th>Consumer Rights Act 2015</th><td>' . $Answers->$section->Legislation->CRA2015->Task->__toString() . '</td><td>' . $Answers->$section->Legislation->CRA2015->Why->__toString() . '</td></tr>';
					$html .= '<tr><th>Trade Description Act 1968</th><td>' . $Answers->$section->Legislation->TDA1968->Task->__toString() . '</td><td>' . $Answers->$section->Legislation->TDA1968->Why->__toString() . '</td></tr>';
					$html .= '<tr><th>Price Marking Order 2004</th><td>' . $Answers->$section->Legislation->PMA2004->Task->__toString() . '</td><td>' . $Answers->$section->Legislation->PMA2004->Why->__toString() . '</td></tr>';
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
					$html .= '<tr><th>2.1 Describe the features and benefits of products / services the organisation offers*</th><td>' . $Answers->$section->Question1->__toString() . '</td></tr>';
					$html .= '<tr><th>2.2 Describe how to maintain their knowledge of the organisation\'s products and / or services</th><td>' . $Answers->$section->Question2->__toString() . '</td></tr>';
					$html .= '<tr><th>2.3 Explain why it is important to update their knowledge on the organisation\'s products / services</th><td>' . $Answers->$section->Question3->__toString() . '</td></tr>';
					$html .= '</table><hr>';
				}
			}
			if($section == 'QQuestions')
			{
				foreach($results AS $row)
				{
					$log_xml = $row['wb_content'];
					$log_xml = XML::loadSimpleXML($log_xml);
					$Answers = $log_xml->Answers;
					$html .= '<table class="table small"><tr class="bg-gray"><th colspan="2">Modified DateTime: ' . Date::to($row['created'], Date::DATETIME) . '</th></tr>';
					$html .= '<tr><th>Unit 1-3.1:Identify how customer feedback can be obtained</th><td>' . $Answers->$section->QQuestion1->__toString() . '</td></tr>';
					$html .= '<tr><th>Unit 1-3.2:Describe how customer feedback can help to improve own quality of service provision</th><td>' . $Answers->$section->QQuestion2->__toString() . '</td></tr>';
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
		return '2,4,5,6,7,8,11,13,14,17,19,20';
	}

	public function updateLearnerProgress(PDO $link)
	{
		parent::updateProgress($link, $this->getUnitReference(), $this->getQAN());
	}

	public static function getClosedOpenProbingQuestionsList()
	{
		return array(
			'Do you want black mascara?'
		,'Have you seen our Star Buys this week?'
		,'Can I help you?'
		,'What fragrance does your sister normally wear?'
		,'Which one do you like the best?'
		,'Are you ok there?'
		,'How much do you want to spend?'
		,'Would you like a tester?'
		,'Who are you buying a product for?'
		,'Have you seen anything you like?'
		,'Where else have you looked for the product?'
		,'When are you planning to use the product?'
		);
	}

	public static function getLearningJourneyItems()
	{
		return array(
			'Vision Mission Values'
		,'Brand standards'
		,'Staff appearance/codes of conduct'
		,'Customer experience'
		,'Customer service language'
		,'Dealing with queries and complaints'
		,'Service observations'
		,'Active/link selling'
		,'Features & benefits'
		,'Product knowledge'
		,'Policies & procedures'
		,'Law & legislation'
		,'Mystery shopper'
		,'Communication'
		);
	}

	public function getPageTopLine()
	{
		if($this->savers_or_sp == 'savers')
			return '<p class="topLine"><span style="color: blue;">Product and service</span> <span style="color: red;">Product and service</span> </p>';
		else
			return '<p class="topLine"><span style="color: pink;">Product and service</span> <span style="color: gray;">Product and service</span> </p>';
	}

	public function getPageBottomLine()
	{
		if($this->savers_or_sp == 'savers')
			return '<p class="bottomLine"><span style="color: blue;">Product and service</span> <span style="color: red;">Product and service</span> </p>';
		else
			return '<p class="bottomLine"><span style="color: pink;">Product and service</span> <span style="color: gray;">Product and service</span> </p>';
	}

	public function getQAN()
	{
		return Workbook::RETAIL_QAN;
	}
}
?>