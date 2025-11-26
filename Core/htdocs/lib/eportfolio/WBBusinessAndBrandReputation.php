<?php
class WBBusinessAndBrandReputation extends Workbook
{
	public function __construct($tr_id)
	{
		$this->wb_title = 'WBBusinessAndBrandReputation';
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
<Workbook title="business_and_brand_reputation">
	<TrainingRecordID></TrainingRecordID>
	<Answers>
		<VisionMissionCoreValues>
			<Vision></Vision>
			<Mission></Mission>
			<CoreValues></CoreValues>
			<Benefits></Benefits>
			<ImpactOnRole></ImpactOnRole>
			<People></People>
		</VisionMissionCoreValues>
		<Logos>
			<L1></L1>
			<L2></L2>
			<L3></L3>
			<L4></L4>
		</Logos>
		<OwnBrandStandards>
			<What></What>
			<Where></Where>
			<HubNotes></HubNotes>
			<HowYourPresentBrand></HowYourPresentBrand>
		</OwnBrandStandards>
		<CorporateObjectives>
			<ImpactOfNotHavingObjectives></ImpactOfNotHavingObjectives>
			<Daily></Daily>
			<Weekly></Weekly>
			<Monthly></Monthly>
			<YourRole></YourRole>
		</CorporateObjectives>
		<BrandReputation>
			<Superdrug></Superdrug>
			<InWork>
				<Positive>
					<Set1>
						<Behaviour></Behaviour>
						<ImpactOnBusiness></ImpactOnBusiness>
					</Set1>
					<Set2>
						<Behaviour></Behaviour>
						<ImpactOnBusiness></ImpactOnBusiness>
					</Set2>
				</Positive>
				<Negative>
					<Set1>
						<Behaviour></Behaviour>
						<ImpactOnBusiness></ImpactOnBusiness>
					</Set1>
					<Set2>
						<Behaviour></Behaviour>
						<ImpactOnBusiness></ImpactOnBusiness>
					</Set2>
				</Negative>
			</InWork>
			<OutsideWork>
				<Positive>
					<Set1>
						<Behaviour></Behaviour>
						<ImpactOnBusiness></ImpactOnBusiness>
					</Set1>
					<Set2>
						<Behaviour></Behaviour>
						<ImpactOnBusiness></ImpactOnBusiness>
					</Set2>
				</Positive>
				<Negative>
					<Set1>
						<Behaviour></Behaviour>
						<ImpactOnBusiness></ImpactOnBusiness>
					</Set1>
					<Set2>
						<Behaviour></Behaviour>
						<ImpactOnBusiness></ImpactOnBusiness>
					</Set2>
				</Negative>
			</OutsideWork>
			<ExampleOfDealing></ExampleOfDealing>
		</BrandReputation>
		<QualificationQuestions>
			<Question1></Question1>
			<Question2></Question2>
			<Question3></Question3>
			<Question4></Question4>
			<Question5></Question5>
			<Question6></Question6>
			<Question7></Question7>
		</QualificationQuestions>
		<Research></Research>
	</Answers>
	<Feedback>
		<VisionMissionCoreValues>
			<Status></Status>
			<Comments></Comments>
		</VisionMissionCoreValues>
		<Logos>
			<Status></Status>
			<Comments></Comments>
		</Logos>
		<OwnBrandStandards>
			<Status></Status>
			<Comments></Comments>
		</OwnBrandStandards>
		<CorporateObjectives>
			<Status></Status>
			<Comments></Comments>
		</CorporateObjectives>
		<BrandReputation>
			<Status></Status>
			<Comments></Comments>
		</BrandReputation>
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
			if($section == 'VisionMissionCoreValues')
			{
				foreach($results AS $row)
				{
					$log_xml = $row['wb_content'];
					$log_xml = XML::loadSimpleXML($log_xml);
					$Answers = $log_xml->Answers;
					$html .= '<table class="table small"><tr class="bg-gray"><th colspan="2">Modified DateTime: ' . Date::to($row['created'], Date::DATETIME) . '</th></tr>';
					$html .= '<tr><th>Vision</th><td>' . $Answers->$section->Vision->__toString() . '</td></tr>';
					$html .= '<tr><th>Mission</th><td>' . $Answers->$section->Mission->__toString() . '</td></tr>';
					$html .= '<tr><th>CoreValues</th><td>' . $Answers->$section->CoreValues->__toString() . '</td></tr>';
					$html .= '<tr><th>Benefits</th><td>' . $Answers->$section->Benefits->__toString() . '</td></tr>';
					$html .= '<tr><th>Impact on Role</th><td>' . $Answers->$section->ImpactOnRole->__toString() . '</td></tr>';
					$html .= '<tr><th>People</th><td>' . $Answers->$section->People->__toString() . '</td></tr>';
					$html .= '</table><hr>';
				}
			}
			if($section == 'OwnBrandStandards')
			{
				foreach($results AS $row)
				{
					$log_xml = $row['wb_content'];
					$log_xml = XML::loadSimpleXML($log_xml);
					$Answers = $log_xml->Answers;
					$html .= '<table class="table small"><tr class="bg-gray"><th colspan="2">Modified DateTime: ' . Date::to($row['created'], Date::DATETIME) . '</th></tr>';
					$html .= '<tr><th>What do you think they are</th><td>' . $Answers->$section->What->__toString() . '</td></tr>';
					$html .= '<tr><th>Where you see our brand ...</th><td>' . $Answers->$section->Where->__toString() . '</td></tr>';
					$html .= '<tr><th>Notes for Brand tone of voice</th><td>' . $Answers->$section->HubNotes->__toString() . '</td></tr>';
					$html .= '<tr><th>Finally, how do you think ...</th><td>' . $Answers->$section->HowYourPresentBrand->__toString() . '</td></tr>';
					$html .= '</table><hr>';
				}
			}
			if($section == 'CorporateObjectives')
			{
				foreach($results AS $row)
				{
					$log_xml = $row['wb_content'];
					$log_xml = XML::loadSimpleXML($log_xml);
					$Answers = $log_xml->Answers;
					$html .= '<table class="table small"><tr class="bg-gray"><th colspan="2">Modified DateTime: ' . Date::to($row['created'], Date::DATETIME) . '</th></tr>';
					$html .= '<tr><th>If no objectives ...</th><td>' . $Answers->$section->ImpactOfNotHavingObjectives->__toString() . '</td></tr>';
					$html .= '<tr><th>Objectives Daily</th><td>' . $Answers->$section->Daily->__toString() . '</td></tr>';
					$html .= '<tr><th>Objectives Weekly</th><td>' . $Answers->$section->Weekly->__toString() . '</td></tr>';
					$html .= '<tr><th>Objectives Monthly</th><td>' . $Answers->$section->Monthly->__toString() . '</td></tr>';
					$html .= '<tr><th>What is your own role ...</th><td>' . $Answers->$section->YourRole->__toString() . '</td></tr>';
					$html .= '</table><hr>';
				}
			}
			if($section == 'Logos')
			{
				foreach($results AS $row)
				{
					$log_xml = $row['wb_content'];
					$log_xml = XML::loadSimpleXML($log_xml);
					$Answers = $log_xml->Answers;
					$html .= '<table class="table small"><tr class="bg-gray"><th colspan="2">Modified DateTime: ' . Date::to($row['created'], Date::DATETIME) . '</th></tr>';
					$html .= '<tr><th><img class="img-responsive img-sm" src="module_eportfolio/assets/images/wbr07_pg5_img2.png" /></td></th><td>' . $Answers->$section->L1->__toString() . '</td></tr>';
					$html .= '<tr><th><img class="img-responsive img-sm" src="module_eportfolio/assets/images/wbr07_pg5_img3.png" /></td></th><td>' . $Answers->$section->L2->__toString() . '</td></tr>';
					$html .= '<tr><th><img class="img-responsive img-sm" src="module_eportfolio/assets/images/wbr07_pg5_img4.png" /></td></th><td>' . $Answers->$section->L3->__toString() . '</td></tr>';
					$html .= '<tr><th><img class="img-responsive img-sm" src="module_eportfolio/assets/images/wbr07_pg5_img5.png" /></td></th><td>' . $Answers->$section->L4->__toString() . '</td></tr>';
					$html .= '</table><hr>';
				}
			}
			if($section == 'BrandReputation')
			{
				foreach($results AS $row)
				{
					$log_xml = $row['wb_content'];
					$log_xml = XML::loadSimpleXML($log_xml);
					$Answers = $log_xml->Answers;
					$html .= '<table class="table small"><tr class="bg-gray"><th colspan="2">Modified DateTime: ' . Date::to($row['created'], Date::DATETIME) . '</th></tr>';
					$html .= '<tr><th>Superdrug/Savers Brand Image and Promise</th><td>' . $Answers->$section->Superdrug->__toString() . '</td></tr>';
					$html .= '<tr><th colspan="2">In Work</th></tr>';
					$html .= '<tr><th>Positive Behaviour</th><th>Impact</th></tr>';
					$html .= '<tr><td>' . $Answers->$section->InWork->Positive->Set1->Behaviour->__toString() . '</td><td>' . $Answers->$section->InWork->Positive->Set1->ImpactOnBusiness->__toString() . '</td></tr>';
					$html .= '<tr><td>' . $Answers->$section->InWork->Positive->Set2->Behaviour->__toString() . '</td><td>' . $Answers->$section->InWork->Positive->Set2->ImpactOnBusiness->__toString() . '</td></tr>';
					$html .= '<tr><th>Negative Behaviour</th><th>Impact</th></tr>';
					$html .= '<tr><td>' . $Answers->$section->InWork->Negative->Set1->Behaviour->__toString() . '</td><td>' . $Answers->$section->InWork->Negative->Set1->ImpactOnBusiness->__toString() . '</td></tr>';
					$html .= '<tr><td>' . $Answers->$section->InWork->Negative->Set2->Behaviour->__toString() . '</td><td>' . $Answers->$section->InWork->Negative->Set2->ImpactOnBusiness->__toString() . '</td></tr>';
					$html .= '<tr><th colspan="2">Outside of Work</th></tr>';
					$html .= '<tr><th>Positive Behaviour</th><th>Impact</th></tr>';
					$html .= '<tr><td>' . $Answers->$section->OutsideWork->Positive->Set1->Behaviour->__toString() . '</td><td>' . $Answers->$section->OutsideWork->Positive->Set1->ImpactOnBusiness->__toString() . '</td></tr>';
					$html .= '<tr><td>' . $Answers->$section->OutsideWork->Positive->Set2->Behaviour->__toString() . '</td><td>' . $Answers->$section->OutsideWork->Positive->Set2->ImpactOnBusiness->__toString() . '</td></tr>';
					$html .= '<tr><th>Negative Behaviour</th><th>Impact</th></tr>';
					$html .= '<tr><td>' . $Answers->$section->OutsideWork->Negative->Set1->Behaviour->__toString() . '</td><td>' . $Answers->$section->OutsideWork->Negative->Set1->ImpactOnBusiness->__toString() . '</td></tr>';
					$html .= '<tr><td>' . $Answers->$section->OutsideWork->Negative->Set2->Behaviour->__toString() . '</td><td>' . $Answers->$section->OutsideWork->Negative->Set2->ImpactOnBusiness->__toString() . '</td></tr>';
					$html .= '<tr><th>Based on the information you have ...</th><td>' . $Answers->$section->ExampleOfDealing->__toString() . '</td></tr>';
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
					for($i = 1; $i <= 7; $i++)
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
		return 'Unit 07';
	}

	public function getStepsWithQuestions()
	{
		return '2,4,5,6,7,8';
	}

	public function updateLearnerProgress(PDO $link)
	{
		parent::updateProgress($link, $this->getUnitReference(), $this->getQAN());
	}

	public function getPageTopLine()
	{
		if($this->savers_or_sp == 'savers')
			return '<p class="topLine"><span style="color: blue;">Business and brand reputation</span> <span style="color: red;">Business and brand reputation</span> </p>';
		else
			return '<p class="topLine"><span style="color: pink;">Business and brand reputation</span> <span style="color: gray;">Business and brand reputation</span> </p>';
	}

	public function getPageBottomLine()
	{
		if($this->savers_or_sp == 'savers')
			return '<p class="bottomLine"><span style="color: blue;">Business and brand reputation</span> <span style="color: red;">Business and brand reputation</span> </p>';
		else
			return '<p class="bottomLine"><span style="color: pink;">Business and brand reputation</span> <span style="color: gray;">Business and brand reputation</span> </p>';
	}

	public function getQAN()
	{
		return Workbook::RETAIL_QAN;
	}
}
?>