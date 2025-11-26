<?php
class WBKnowingYourCustomers extends Workbook
{
	public function __construct($tr_id)
	{
		$this->wb_title = 'WBKnowingYourCustomers';
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
<Workbook title="knowing_your_customers">
	<TrainingRecordID></TrainingRecordID>
	<Answers>
		$journey
		<InternalAndExternalCustomers>
			<InternalCustomers></InternalCustomers>
			<ExternalCustomers></ExternalCustomers>
		</InternalAndExternalCustomers>
		<StoreCategories>
			<Loyal></Loyal>
			<Discount></Discount>
			<Impulse></Impulse>
			<Wandering></Wandering>
			<NeedBased></NeedBased>
		</StoreCategories>
		<CustomerExpectations>
			<Higher></Higher>
			<Lower></Lower>
		</CustomerExpectations>
		<CustomerWithSpecificNeeds>
			<ExampleOfCustomerService></ExampleOfCustomerService>
			<NeedsAndPriorities></NeedsAndPriorities>
		</CustomerWithSpecificNeeds>
		<PoorCustomerServiceImplications></PoorCustomerServiceImplications>
		<QualificationQuestions>
			<Unit1_1></Unit1_1>
			<Unit1_2></Unit1_2>
			<Unit1_3_And_1_4></Unit1_3_And_1_4>
			<Unit2_1></Unit2_1>
			<Unit2_2></Unit2_2>
			<Unit2_3></Unit2_3>
		</QualificationQuestions>
		<Research></Research>
	</Answers>
	<Feedback>
		<InternalAndExternalCustomers>
			<Status></Status>
			<Comments></Comments>
		</InternalAndExternalCustomers>
		<StoreCategories>
			<Status></Status>
			<Comments></Comments>
		</StoreCategories>
		<CustomerExpectations>
			<Status></Status>
			<Comments></Comments>
		</CustomerExpectations>
		<CustomerWithSpecificNeeds>
			<Status></Status>
			<Comments></Comments>
		</CustomerWithSpecificNeeds>
		<PoorCustomerServiceImplications>
			<Status></Status>
			<Comments></Comments>
		</PoorCustomerServiceImplications>
		<QualificationQuestions>
			<Status></Status>
			<Comments></Comments>
		</QualificationQuestions>
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
		$feedback = $this->wb_content->Feedback;
		if(isset($feedback->InternalAndExternalCustomers->Status) && $feedback->InternalAndExternalCustomers->Status->__toString() == 'A')
			$total += 16.66;
		if(isset($feedback->StoreCategories->Status) && $feedback->StoreCategories->Status->__toString() == 'A')
			$total += 16.66;
		if(isset($feedback->CustomerExpectations->Status) && $feedback->CustomerExpectations->Status->__toString() == 'A')
			$total += 16.66;
		if(isset($feedback->CustomerWithSpecificNeeds->Status) && $feedback->CustomerWithSpecificNeeds->Status->__toString() == 'A')
			$total += 16.66;
		if(isset($feedback->PoorCustomerServiceImplications->Status) && $feedback->PoorCustomerServiceImplications->Status->__toString() == 'A')
			$total += 16.66;
		if(isset($feedback->QualificationQuestions->Status) && $feedback->QualificationQuestions->Status->__toString() == 'A')
			$total += 16.66;

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
			if($section == 'InternalAndExternalCustomers')
			{
				foreach($results AS $row)
				{
					$log_xml = $row['wb_content'];
					$log_xml = XML::loadSimpleXML($log_xml);
					$answers = $log_xml->Answers;
					$html .= '<p class="text-bold">Modified DateTime: ' . Date::to($row['created'], Date::DATETIME) . '</p>';
					$html .= '<div class="col-sm-12 table-responsive">';
					$InternalCustomers = array();
					if(isset($answers->InternalAndExternalCustomers->InternalCustomers))
						$InternalCustomers = explode(',', $answers->InternalAndExternalCustomers->InternalCustomers->__toString());
					$ExternalCustomers = array();
					if(isset($answers->InternalAndExternalCustomers->ExternalCustomers))
						$ExternalCustomers = explode(',', $answers->InternalAndExternalCustomers->ExternalCustomers->__toString());
					$html .= '<table class="table row-border">';
					$html .= '<tr><th>Description</th><th>External Customer?</th><th>Internal Customer?</th></tr>';
					$html .= '<tr><td>Superdrug Regional General Manager</td>';
					$html .= in_array('SGRM', $ExternalCustomers)?'<td class="text-center"><i class="fa fa-check"></i></td>':'<td></td>';
					$html .= in_array('SGRM', $InternalCustomers)?'<td class="text-center"><i class="fa fa-check"></i></td>':'<td></td>';
					$html .= '</tr>';

					$html .= '<tr><td>Superdrug delivery driver</td>';
					$html .= in_array('SDD', $ExternalCustomers)?'<td class="text-center"><i class="fa fa-check"></i></td>':'<td></td>';
					$html .= in_array('SDD', $InternalCustomers)?'<td class="text-center"><i class="fa fa-check"></i></td>':'<td></td>';
					$html .= '</tr>';

					$html .= '<tr><td>You</td>';
					$html .= in_array('You', $ExternalCustomers)?'<td class="text-center"><i class="fa fa-check"></i></td>':'<td></td>';
					$html .= in_array('You', $InternalCustomers)?'<td class="text-center"><i class="fa fa-check"></i></td>':'<td></td>';
					$html .= '</tr>';

					$html .= '<tr><td>Friend who works in Greggs</td>';
					$html .= in_array('FWWIG', $ExternalCustomers)?'<td class="text-center"><i class="fa fa-check"></i></td>':'<td></td>';
					$html .= in_array('FWWIG', $InternalCustomers)?'<td class="text-center"><i class="fa fa-check"></i></td>':'<td></td>';
					$html .= '</tr>';

					$html .= '<tr><td>Local Postman</td>';
					$html .= in_array('LP', $ExternalCustomers)?'<td class="text-center"><i class="fa fa-check"></i></td>':'<td></td>';
					$html .= in_array('LP', $InternalCustomers)?'<td class="text-center"><i class="fa fa-check"></i></td>':'<td></td>';
					$html .= '</tr>';

					$html .= '<tr><td>Apprenticeship Assessor</td>';
					$html .= in_array('AA', $ExternalCustomers)?'<td class="text-center"><i class="fa fa-check"></i></td>':'<td></td>';
					$html .= in_array('AA', $InternalCustomers)?'<td class="text-center"><i class="fa fa-check"></i></td>':'<td></td>';
					$html .= '</tr>';

					$html .= '<tr><td>Store Pharmacist</td>';
					$html .= in_array('SP', $ExternalCustomers)?'<td class="text-center"><i class="fa fa-check"></i></td>':'<td></td>';
					$html .= in_array('SP', $InternalCustomers)?'<td class="text-center"><i class="fa fa-check"></i></td>':'<td></td>';
					$html .= '</tr>';
					$html .= '</table>';
					$html .= '</div><hr>';
				}

			}
			if($section == 'StoreCategories')
			{
				foreach($results AS $row)
				{
					$log_xml = $row['wb_content'];
					$log_xml = XML::loadSimpleXML($log_xml);
					$html .= '<p class="text-bold">Modified DateTime: ' . Date::to($row['created'], Date::DATETIME) . '</p>';
					$html .= '<table class="table table-bordered small"><tr><th>Loyal</th><th>Discount</th><th>Impulse</th><th>Wandering</th><th>Need-Based</th></tr>';
					$html .= '<tr>';
					$html .= '<td>' . $log_xml->Answers->StoreCategories->Loyal->__toString() . '</td>';
					$html .= '<td>' . $log_xml->Answers->StoreCategories->Discount->__toString() . '</td>';
					$html .= '<td>' . $log_xml->Answers->StoreCategories->Impulse->__toString() . '</td>';
					$html .= '<td>' . $log_xml->Answers->StoreCategories->Wandering->__toString() . '</td>';
					$html .= '<td>' . $log_xml->Answers->StoreCategories->NeedBased->__toString() . '</td>';
					$html .= '</tr>';
					$html .= '</table><hr>';
				}
			}
			if($section == 'CustomerExpectations')
			{
				foreach($results AS $row)
				{
					$log_xml = $row['wb_content'];
					$log_xml = XML::loadSimpleXML($log_xml);
					$html .= '<p class="text-bold">Modified DateTime: ' . Date::to($row['created'], Date::DATETIME) . '</p>';
					$html .= '<table class="table table-bordered small">';
					$html .= '<tr>';
					$html .= '<th>Which retailers do you think customers have higher expectations of regarding their customer service?</th><td>' . $log_xml->Answers->CustomerExpectations->Higher->__toString() . '</td>';
					$html .= '</tr><tr>';
					$html .= '<th>Which retailers do you think customers have lower expectations of regarding customer service and higher expectations of regarding their prices?</th><td>' . $log_xml->Answers->CustomerExpectations->Lower->__toString() . '</td>';
					$html .= '</tr>';
					$html .= '</table><hr>';
				}
			}
			if($section == 'CustomerWithSpecificNeeds')
			{
				foreach($results AS $row)
				{
					$log_xml = $row['wb_content'];
					$log_xml = XML::loadSimpleXML($log_xml);
					$html .= '<p class="text-bold">Modified DateTime: ' . Date::to($row['created'], Date::DATETIME) . '</p>';
					$html .= '<table class="table table-bordered small">';
					$html .= '<tr>';
					$html .= '<th>Give an example of when you have served a customer who had specific needs. Explain when and how you adapted your service approach to meet their needs and priorities?</th><td>' . $log_xml->Answers->CustomerWithSpecificNeeds->ExampleOfCustomerService->__toString() . '</td>';
					$html .= '</tr><tr>';
					$html .= '<th>Describe some of the specific needs and priorities of different customers who are protected under the Equality Act 2010.</th><td>' . $log_xml->Answers->CustomerWithSpecificNeeds->NeedsAndPriorities->__toString() . '</td>';
					$html .= '</tr>';
					$html .= '</table><hr>';
				}
			}
			if($section == 'PoorCustomerServiceImplications')
			{
				foreach($results AS $row)
				{
					$log_xml = $row['wb_content'];
					$log_xml = XML::loadSimpleXML($log_xml);
					$html .= '<p class="text-bold">Modified DateTime: ' . Date::to($row['created'], Date::DATETIME) . '</p>';
					$html .= '<table class="table table-bordered small">';
					$html .= '<tr>';
					$html .= '<th>What are some of the implications of poor customer service?</th><td>' . $log_xml->Answers->PoorCustomerServiceImplications->ExampleOfCustomerService->__toString() . '</td>';
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
					$html .= '<p class="text-bold">Modified DateTime: ' . Date::to($row['created'], Date::DATETIME) . '</p>';
					$html .= '<table class="table table-bordered small">';
					$html .= '<tr>';
					$html .= '<th>1.1	Explain the importance of building good customer relations to the organisation</th><td>' . $log_xml->Answers->QualificationQuestions->Unit1_1->__toString() . '</td>';
					$html .= '</tr>';
					$html .= '<tr>';
					$html .= '<th>1.2	Explain the difference between internal and external customers</th><td>' . $log_xml->Answers->QualificationQuestions->Unit1_2->__toString() . '</td>';
					$html .= '</tr>';
					$html .= '<tr>';
					$html .= '<th>1.3/1.4	Describe different types of internal and external customers giving examples from within Superdrug</th><td>' . $log_xml->Answers->QualificationQuestions->Unit1_3_And_1_4->__toString() . '</td>';
					$html .= '</tr>';
					$html .= '<tr>';
					$html .= '<th>Describe specific needs and priorities of different customers, including those protected under current Equality law</th><td>' . $log_xml->Answers->QualificationQuestions->Unit2_1->__toString() . '</td>';
					$html .= '</tr>';
					$html .= '<tr>';
					$html .= '<th>Explain when and how to adapt your service approach to meet the needs and expectations of customers</th><td>' . $log_xml->Answers->QualificationQuestions->Unit2_2->__toString() . '</td>';
					$html .= '</tr>';
					$html .= '<tr>';
					$html .= '<th>Why is it important to manage customer expectations?</th><td>' . $log_xml->Answers->QualificationQuestions->Unit2_3->__toString() . '</td>';
					$html .= '</tr>';
					$html .= '</table><hr>';
				}
			}
		}
		return $html;
	}

	public function getUnitReference()
	{
		return 'Unit 04';
	}

	public function getStepsWithQuestions()
	{
		return '2,3,4,5,6,7,8';
	}

	public function updateLearnerProgress(PDO $link)
	{
		parent::updateProgress($link, $this->getUnitReference(), $this->getQAN());
	}

	public static function getLearningJourneyItems($savers_or_sp = '')
	{
		if($savers_or_sp == 'savers')
			return array(
				'Customer Service and Customer Journey Map – watch 2 x DVDs'
				,'Customer Service and Customer Journey Map – store floor walk'
				,'Customer Service - Basket Fantastic'
				,'Customer Service - Savers Service Standards'
				,'Customer Service – Mystery Shop programme'
				,'Customer Service – Knowledge is Power'
				,'Tills – Cashier competency'
				,'Tills – Savers Service Standards'
			);
		else
			return array(
				'Ownership and responsibility mind set'
				,'The people v task- technical P/T circle'
				,'The red/black continuum'
				,'Zoe’s story'
				,'A fresh approach'
				,'Customer comes first rule'
				,'Before during and after rule'
				,'Positive first response in yes situations'
				,'Positive first response in no/disappointing news'
				,'Glad sure sorry'
				,'Till talk trio'
			);
	}

	public function getPageTopLine()
	{
		if($this->savers_or_sp == 'savers')
			return '<p class="topLine"><span style="color: blue;">Knowing your Customers</span> <span style="color: red;">Knowing your Customers</span> </p>';
		else
			return '<p class="topLine"><span style="color: pink;">Knowing your Customers</span> <span style="color: gray;">Knowing your Customers</span> </p>';
	}

	public function getPageBottomLine()
	{
		if($this->savers_or_sp == 'savers')
			return '<p class="bottomLine"><span style="color: blue;">Knowing your Customers</span> <span style="color: red;">Knowing your Customers</span> </p>';
		else
			return '<p class="bottomLine"><span style="color: pink;">Knowing your Customers</span> <span style="color: gray;">Knowing your Customers</span> </p>';
	}

	public function getQAN()
	{
		return Workbook::CS_QAN;
	}
}
?>