<?php
class WBCustomer extends Workbook
{
	public function __construct($tr_id)
	{
		$this->wb_title = 'WBCustomer';
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
		for($i = 1; $i <= count(self::getLearningJourneyItems('')); $i++)
			$journey .= '<DC'.$i.'></DC'.$i.'>';
		$journey .= '</Journey>';
		$xml = <<<XML
<Workbook title="customer">
	<TrainingRecordID></TrainingRecordID>
	<Answers>
		$journey
		<ServiceObservation>
			<Q1></Q1>
			<Q2></Q2>
			<Q3></Q3>
			<Q4></Q4>
		</ServiceObservation>
		<InternalAndExternalCustomers>
			<InternalCustomers></InternalCustomers>
			<ExternalCustomers></ExternalCustomers>
		</InternalAndExternalCustomers>
		<Retailers>
			<Higher></Higher>
			<Lower></Lower>
		</Retailers>
		<CustomersWithSpecialNeeds>
			<YourExperience></YourExperience>
			<IfYouStruggle></IfYouStruggle>
			<HowCustomerFeels></HowCustomerFeels>
		</CustomersWithSpecialNeeds>
		<ImpatientCustomers>
			<WhatYouDid></WhatYouDid>
			<WhatYouWillDo></WhatYouWillDo>
		</ImpatientCustomers>
		<HelpSigns></HelpSigns>
		<GladSureSorryTechnique></GladSureSorryTechnique>
		<GoodCustomerService>
			<SuperdrugSpecificFeatures>
				<Set1>
					<Offer></Offer>
					<Features></Features>
					<Benefits></Benefits>
				</Set1>
				<Set2>
					<Offer></Offer>
					<Features></Features>
					<Benefits></Benefits>
				</Set2>
			</SuperdrugSpecificFeatures>
			<StoreWithGoodCS></StoreWithGoodCS>
			<StoreWithBadCS></StoreWithBadCS>
		</GoodCustomerService>
		<CustomerLoyality>
			<Q1></Q1>
			<Q2></Q2>
			<Q3></Q3>
			<Q4></Q4>
			<Q5></Q5>
			<Q6></Q6>
			<Q7></Q7>
			<Q8></Q8>
			<Q9></Q9>
			<Q10></Q10>
			<Q11></Q11>
			<Q12></Q12>
			<Q13></Q13>
		</CustomerLoyality>
		<CustomerExperience>
			<YourTraining></YourTraining>
			<YourExperienceWithOthers>
				<Set1>
					<Store></Store>
					<Experience></Experience>
					<GoodOrBad></GoodOrBad>
				</Set1>
				<Set2>
					<Store></Store>
					<Experience></Experience>
					<GoodOrBad></GoodOrBad>
				</Set2>
				<Set3>
					<Store></Store>
					<Experience></Experience>
					<GoodOrBad></GoodOrBad>
				</Set3>
			</YourExperienceWithOthers>
			<TypicalCustomerProfile></TypicalCustomerProfile>
		</CustomerExperience>
		<CustomerPurchasingHabit>
			<A></A>
			<B></B>
			<C></C>
			<D></D>
		</CustomerPurchasingHabit>
		<Feedback>
			<Q1></Q1>
			<Q2></Q2>
			<Q3></Q3>
			<Q4></Q4>
			<Q5></Q5>
			<Q6></Q6>
			<Q7></Q7>
			<Q8></Q8>
		</Feedback>
		<LocateCustomerInformation></LocateCustomerInformation>
		<PurchasingMethods>
			<Q1></Q1>
			<Q2></Q2>
		</PurchasingMethods>
		<QualificationQuestions>
			<Question1></Question1>
			<Question2></Question2>
			<Question3></Question3>
			<Question4></Question4>
			<Question5></Question5>
		</QualificationQuestions>
		<Research></Research>
	</Answers>
	<Feedback>
		<ServiceObservation>
			<Status></Status>
			<Comments></Comments>
		</ServiceObservation>
		<InternalAndExternalCustomers>
			<Status></Status>
			<Comments></Comments>
		</InternalAndExternalCustomers>
		<Retailers>
			<Status></Status>
			<Comments></Comments>
		</Retailers>
		<CustomersWithSpecialNeeds>
			<Status></Status>
			<Comments></Comments>
		</CustomersWithSpecialNeeds>
		<ImpatientCustomers>
			<Status></Status>
			<Comments></Comments>
		</ImpatientCustomers>
		<HelpSigns>
			<Status></Status>
			<Comments></Comments>
		</HelpSigns>
		<GladSureSorryTechnique>
			<Status></Status>
			<Comments></Comments>
		</GladSureSorryTechnique>
		<GoodCustomerService>
			<Status></Status>
			<Comments></Comments>
		</GoodCustomerService>
		<CustomerLoyality>
			<Status></Status>
			<Comments></Comments>
		</CustomerLoyality>
		<CustomerExperience>
			<Status></Status>
			<Comments></Comments>
		</CustomerExperience>
		<CustomerPurchasingHabit>
			<Status></Status>
			<Comments></Comments>
		</CustomerPurchasingHabit>
		<Feedback>
			<Status></Status>
			<Comments></Comments>
		</Feedback>
		<LocateCustomerInformation>
			<Status></Status>
			<Comments></Comments>
		</LocateCustomerInformation>
		<PurchasingMethods>
			<Status></Status>
			<Comments></Comments>
		</PurchasingMethods>
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

					$html .= '</table>';
					$html .= '</div><hr>';
				}

			}
			if($section == 'ServiceObservation')
			{
				foreach($results AS $row)
				{
					$log_xml = $row['wb_content'];
					$log_xml = XML::loadSimpleXML($log_xml);
					$html .= '<p class="text-bold">Modified DateTime: ' . Date::to($row['created'], Date::DATETIME) . '</p>';
					$html .= '<table class="table table-bordered small"><tr><th>Q1</th><th>Q2</th><th>Q3</th><th>Q4</th></tr>';
					$html .= '<tr>';
					$html .= '<td>' . $log_xml->Answers->$section->Q1->__toString() . '</td>';
					$html .= '<td>' . $log_xml->Answers->$section->Q2->__toString() . '</td>';
					$html .= '<td>' . $log_xml->Answers->$section->Q3->__toString() . '</td>';
					$html .= '<td>' . $log_xml->Answers->$section->Q4->__toString() . '</td>';
					$html .= '</tr>';
					$html .= '</table><hr>';
				}
			}
			if($section == 'CustomerPurchasingHabit')
			{
				foreach($results AS $row)
				{
					$log_xml = $row['wb_content'];
					$log_xml = XML::loadSimpleXML($log_xml);
					$html .= '<p class="text-bold">Modified DateTime: ' . Date::to($row['created'], Date::DATETIME) . '</p>';
					$html .= '<table class="table table-bordered small"><tr><th>A</th><th>B</th><th>C</th><th>D</th></tr>';
					$html .= '<tr>';
					$html .= '<td>' . $log_xml->Answers->$section->A->__toString() . '</td>';
					$html .= '<td>' . $log_xml->Answers->$section->B->__toString() . '</td>';
					$html .= '<td>' . $log_xml->Answers->$section->C->__toString() . '</td>';
					$html .= '<td>' . $log_xml->Answers->$section->D->__toString() . '</td>';
					$html .= '</tr>';
					$html .= '</table><hr>';
				}
			}
			if($section == 'Feedback')
			{
				foreach($results AS $row)
				{
					$log_xml = $row['wb_content'];
					$log_xml = XML::loadSimpleXML($log_xml);
					$html .= '<p class="text-bold">Modified DateTime: ' . Date::to($row['created'], Date::DATETIME) . '</p>';
					$html .= '<table class="table table-bordered small">';
					for($i = 1; $i <= 8; $i++)
					{
						$key = 'Q'.$i;
						$html .= '<tr>';
						$html .= '<td>' . $key . '</td>';
						$html .= '<td>' . $log_xml->Answers->$section->$key->__toString() . '</td>';
						$html .= '</tr>';
					}
					$html .= '</table><hr>';

				}
			}
			if($section == 'PurchasingMethods')
			{
				foreach($results AS $row)
				{
					$log_xml = $row['wb_content'];
					$log_xml = XML::loadSimpleXML($log_xml);
					$html .= '<p class="text-bold">Modified DateTime: ' . Date::to($row['created'], Date::DATETIME) . '</p>';
					$html .= '<table class="table table-bordered small">';
					for($i = 1; $i <= 2; $i++)
					{
						$key = 'Q'.$i;
						$html .= '<tr>';
						$html .= '<td>' . $key . '</td>';
						$html .= '<td>' . $log_xml->Answers->$section->$key->__toString() . '</td>';
						$html .= '</tr>';
					}
					$html .= '</table><hr>';

				}
			}
			if($section == 'GoodCustomerService')
			{
				foreach($results AS $row)
				{
					$log_xml = $row['wb_content'];
					$log_xml = XML::loadSimpleXML($log_xml);
					$html .= '<p class="text-bold">Modified DateTime: ' . Date::to($row['created'], Date::DATETIME) . '</p>';
					$html .= '<table class="table table-bordered small"><tr><th>Customer Service Offer</th><th>Features</th><th>Benefits</th></tr>';
					$html .= '<tr>';
					$html .= '<td>' . $log_xml->Answers->$section->SuperdrugSpecificFeatures->Set1->Offer->__toString() . '</td>';
					$html .= '<td>' . $log_xml->Answers->$section->SuperdrugSpecificFeatures->Set1->Features->__toString() . '</td>';
					$html .= '<td>' . $log_xml->Answers->$section->SuperdrugSpecificFeatures->Set1->Benefits->__toString() . '</td>';
					$html .= '</tr>';
					$html .= '<tr>';
					$html .= '<td>' . $log_xml->Answers->$section->SuperdrugSpecificFeatures->Set2->Offer->__toString() . '</td>';
					$html .= '<td>' . $log_xml->Answers->$section->SuperdrugSpecificFeatures->Set2->Features->__toString() . '</td>';
					$html .= '<td>' . $log_xml->Answers->$section->SuperdrugSpecificFeatures->Set2->Benefits->__toString() . '</td>';
					$html .= '</tr>';
					$html .= '<tr><th colspan="3">Local retail business with good customer service.</th><tr><tr><td>' . $log_xml->Answers->$section->StoreWithGoodCS->__toString() . '</td></tr>';
					$html .= '<tr><th colspan="3">Local retail business with bad customer service.</th><tr><tr><td>' . $log_xml->Answers->$section->StoreWithBadCS->__toString() . '</td></tr>';
					$html .= '</table><hr>';
				}
			}
			if($section == 'CustomerExperience')
			{
				foreach($results AS $row)
				{
					$log_xml = $row['wb_content'];
					$log_xml = XML::loadSimpleXML($log_xml);
					$html .= '<p class="text-bold">Modified DateTime: ' . Date::to($row['created'], Date::DATETIME) . '</p>';
					$html .= '<table class="table table-bordered small">';
					$html .= '<tr><th colspan="3">What customer experience training have you had in store ...</th><tr><tr><td>' . $log_xml->Answers->$section->YourTraining->__toString() . '</td></tr>';
					$html .= '<tr><th>Store</th><th>Experience</th><th>Good or bad</th></tr>';
					for($i = 1; $i <= 3; $i++)
					{
						$key = 'Set'.$i;
						$html .= '<tr>';
						$html .= '<td>' . $log_xml->Answers->$section->YourExperienceWithOthers->$key->Store->__toString() . '</td>';
						$html .= '<td>' . $log_xml->Answers->$section->YourExperienceWithOthers->$key->Experience->__toString() . '</td>';
						$html .= '<td>' . $log_xml->Answers->$section->YourExperienceWithOthers->$key->GoodOrBad->__toString() . '</td>';
						$html .= '</tr>';
					}
					$html .= '<tr><th colspan="3">TypicalCustomerProfile ...</th><tr><tr><td>' . $log_xml->Answers->$section->TypicalCustomerProfile->__toString() . '</td></tr>';
					$html .= '</table><hr>';
				}
			}
			if($section == 'CustomerLoyality')
			{
				foreach($results AS $row)
				{
					$log_xml = $row['wb_content'];
					$log_xml = XML::loadSimpleXML($log_xml);
					$html .= '<p class="text-bold">Modified DateTime: ' . Date::to($row['created'], Date::DATETIME) . '</p>';
					$html .= '<table class="table table-bordered small">';
					for($i = 1; $i <= 13; $i++)
					{
						$key = 'Q'.$i;
						$html .= '<tr><th>Q'.$i.'</th><td>' . $log_xml->Answers->$section->$key->__toString() . '</td></tr>';
					}
					$html .= '</table><hr>';
				}
			}
			if($section == 'CustomersWithSpecialNeeds')
			{
				foreach($results AS $row)
				{
					$log_xml = $row['wb_content'];
					$log_xml = XML::loadSimpleXML($log_xml);
					$html .= '<p class="text-bold">Modified DateTime: ' . Date::to($row['created'], Date::DATETIME) . '</p>';
					$html .= '<table class="table table-bordered small">';
					$html .= '<tr>';
					$html .= '<th>Q1</th><td>' . $log_xml->Answers->$section->YourExperience->__toString() . '</td>';
					$html .= '</tr><tr>';
					$html .= '<th>Q2</th><td>' . $log_xml->Answers->$section->IfYouStruggle->__toString() . '</td>';
					$html .= '</tr><tr>';
					$html .= '<th>Q3</th><td>' . $log_xml->Answers->$section->HowCustomerFeels->__toString() . '</td>';
					$html .= '</tr>';
					$html .= '</table><hr>';
				}
			}
			if($section == 'ImpatientCustomers')
			{
				foreach($results AS $row)
				{
					$log_xml = $row['wb_content'];
					$log_xml = XML::loadSimpleXML($log_xml);
					$html .= '<p class="text-bold">Modified DateTime: ' . Date::to($row['created'], Date::DATETIME) . '</p>';
					$html .= '<table class="table table-bordered small">';
					$html .= '<tr>';
					$html .= '<th>Q1</th><td>' . $log_xml->Answers->$section->WhatYouDid->__toString() . '</td>';
					$html .= '</tr><tr>';
					$html .= '<th>Q2</th><td>' . $log_xml->Answers->$section->WhatYouWillDo->__toString() . '</td>';
					$html .= '</tr>';
					$html .= '</table><hr>';
				}
			}
			if($section == 'HelpSigns')
			{
				foreach($results AS $row)
				{
					$log_xml = $row['wb_content'];
					$log_xml = XML::loadSimpleXML($log_xml);
					$html .= '<p class="text-bold">Modified DateTime: ' . Date::to($row['created'], Date::DATETIME) . '</p>';
					$html .= '<table class="table table-bordered small">';
					$html .= '<tr>';
					$html .= '<th>Can you think of any others \'I need help signs\'</th><td>' . $log_xml->Answers->$section->__toString() . '</td>';
					$html .= '</tr>';
					$html .= '</table><hr>';
				}
			}
			if($section == 'GladSureSorryTechnique')
			{
				foreach($results AS $row)
				{
					$log_xml = $row['wb_content'];
					$log_xml = XML::loadSimpleXML($log_xml);
					$html .= '<p class="text-bold">Modified DateTime: ' . Date::to($row['created'], Date::DATETIME) . '</p>';
					$html .= '<table class="table table-bordered small">';
					$html .= '<tr><th>Document a time when you have used the Glad, Sure, Sorry technique.</th><tr><tr><td>' . $log_xml->Answers->$section->__toString() . '</td></tr>';
					$html .= '</table><hr>';
				}
			}
			if($section == 'LocateCustomerInformation')
			{
				foreach($results AS $row)
				{
					$log_xml = $row['wb_content'];
					$log_xml = XML::loadSimpleXML($log_xml);
					$html .= '<p class="text-bold">Modified DateTime: ' . Date::to($row['created'], Date::DATETIME) . '</p>';
					$html .= '<table class="table table-bordered small">';
					$html .= '<tr><th>Describe a time when you have had to locate ...</th><tr><tr><td>' . $log_xml->Answers->$section->__toString() . '</td></tr>';
					$html .= '</table><hr>';
				}
			}
			if($section == 'Retailers')
			{
				foreach($results AS $row)
				{
					$log_xml = $row['wb_content'];
					$log_xml = XML::loadSimpleXML($log_xml);
					$html .= '<p class="text-bold">Modified DateTime: ' . Date::to($row['created'], Date::DATETIME) . '</p>';
					$html .= '<table class="table table-bordered small">';
					$html .= '<tr>';
					$html .= '<th>Which retailers do you think customers have higher expectations of regarding their customer service?</th><td>' . $log_xml->Answers->$section->Higher->__toString() . '</td>';
					$html .= '</tr><tr>';
					$html .= '<th>Which retailers do you think customers have lower expectations of regarding customer service and higher expectations of regarding their prices?</th><td>' . $log_xml->Answers->$section->Lower->__toString() . '</td>';
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
					for($i = 1; $i <= 5; $i++)
					{
						$key = 'Question'.$i;
						$html .= '<tr><th>' . $key . '</th></tr>';
						$html .= '<tr><td colspan="2">' . $Answers->$section->$key->__toString() . '</td></tr>';
					}
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
		return '2,3,5,6,7,8,13,15,17,18,19,21,22,23,24';
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
			return '<p class="topLine"><span style="color: blue;">Customer</span> <span style="color: red;">Customer</span> </p>';
		else
			return '<p class="topLine"><span style="color: pink;">Customer</span> <span style="color: gray;">Customer</span> </p>';
	}

	public function getPageBottomLine()
	{
		if($this->savers_or_sp == 'savers')
			return '<p class="bottomLine"><span style="color: blue;">Customer</span> <span style="color: red;">Customer</span> </p>';
		else
			return '<p class="bottomLine"><span style="color: pink;">Customer</span> <span style="color: gray;">Customer</span> </p>';
	}

	public function getQAN()
	{
		return Workbook::RETAIL_QAN;
	}
}
?>