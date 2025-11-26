<?php
class WBUnderstandingTheOrganisation extends Workbook
{
	public function __construct($tr_id)
	{
		$this->wb_title = 'WBUnderstandingTheOrganisation';
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
<Workbook title="understanding_the_organisation">
	<TrainingRecordID></TrainingRecordID>
	<Answers>
		<Sectors>
			<Public>
				<Set1>
					<Organisation></Organisation>
					<AimOfOrganisation></AimOfOrganisation>
				</Set1>
				<Set2>
					<Organisation></Organisation>
					<AimOfOrganisation></AimOfOrganisation>
				</Set2>
			</Public>
			<Private>
				<Set1>
					<Organisation></Organisation>
					<AimOfOrganisation></AimOfOrganisation>
				</Set1>
				<Set2>
					<Organisation></Organisation>
					<AimOfOrganisation></AimOfOrganisation>
				</Set2>
			</Private>
			<NonProfit>
				<Set1>
					<Organisation></Organisation>
					<AimOfOrganisation></AimOfOrganisation>
				</Set1>
				<Set2>
					<Organisation></Organisation>
					<AimOfOrganisation></AimOfOrganisation>
				</Set2>
			</NonProfit>
		</Sectors>
		<VisionMissionCoreValues>
			<SuperdrugStatement>
				<Blank1></Blank1>
				<Blank2></Blank2>
				<Blank3></Blank3>
				<Blank4></Blank4>
				<Blank5></Blank5>
				<Blank6></Blank6>
			</SuperdrugStatement>
			<Vision></Vision>
			<Mission></Mission>
			<CoreValues></CoreValues>
			<Benefits></Benefits>
			<ImpactOnRole></ImpactOnRole>
			<People></People>
		</VisionMissionCoreValues>
		<BrandImagePromise>
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
		</BrandImagePromise>
		<OrganisationCulture>
			<Demonstration>
				<Set1>
					<What></What>
					<How></How>
				</Set1>
				<Set2>
					<What></What>
					<How></How>
				</Set2>
			</Demonstration>
			<LinkOfCoreValues></LinkOfCoreValues>
			<ImpactOfPoliciesProcedures></ImpactOfPoliciesProcedures>
		</OrganisationCulture>
		<DigitalMedia></DigitalMedia>
		<QualificationQuestions>
			<Question1></Question1>
			<Question2></Question2>
			<Question3></Question3>
			<Question4></Question4>
			<Question5></Question5>
			<Question6></Question6>
		</QualificationQuestions>
		<Research></Research>
	</Answers>
	<Feedback>
		<Sectors>
			<Status></Status>
			<Comments></Comments>
		</Sectors>
		<VisionMissionCoreValues>
			<Status></Status>
			<Comments></Comments>
		</VisionMissionCoreValues>
		<BrandImagePromise>
			<Status></Status>
			<Comments></Comments>
		</BrandImagePromise>
		<OrganisationCulture>
			<Status></Status>
			<Comments></Comments>
		</OrganisationCulture>
		<DigitalMedia>
			<Status></Status>
			<Comments></Comments>
		</DigitalMedia>
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
			if($section == 'Sectors')
			{
				foreach($results AS $row)
				{
					$log_xml = $row['wb_content'];
					$log_xml = XML::loadSimpleXML($log_xml);
					$Answers = $log_xml->Answers;
					$html .= '<table class="table small"><tr class="bg-gray"><th colspan="2">Modified DateTime: ' . Date::to($row['created'], Date::DATETIME) . '</th></tr>';
					$html .= '<tr><th colspan="2">Public</th></tr>';
					$html .= '<tr><th>Organisation</th><th>Aim of the organisation</th></tr>';
					$html .= '<tr><th>' . $Answers->$section->Public->Set1->Organisation->__toString() . '</th><td>' . $Answers->$section->Public->Set1->AimOfOrganisation->__toString() . '</td></tr>';
					$html .= '<tr><th>' . $Answers->$section->Public->Set2->Organisation->__toString() . '</th><td>' . $Answers->$section->Public->Set2->AimOfOrganisation->__toString() . '</td></tr>';
					$html .= '<tr><th colspan="2">Private</th></tr>';
					$html .= '<tr><th>Organisation</th><th>Aim of the organisation</th></tr>';
					$html .= '<tr><th>' . $Answers->$section->Private->Set1->Organisation->__toString() . '</th><td>' . $Answers->$section->Private->Set1->AimOfOrganisation->__toString() . '</td></tr>';
					$html .= '<tr><th>' . $Answers->$section->Private->Set2->Organisation->__toString() . '</th><td>' . $Answers->$section->Private->Set2->AimOfOrganisation->__toString() . '</td></tr>';
					$html .= '<tr><th colspan="2">NonProfit</th></tr>';
					$html .= '<tr><th>Organisation</th><th>Aim of the organisation</th></tr>';
					$html .= '<tr><th>' . $Answers->$section->NonProfit->Set1->Organisation->__toString() . '</th><td>' . $Answers->$section->NonProfit->Set1->AimOfOrganisation->__toString() . '</td></tr>';
					$html .= '<tr><th>' . $Answers->$section->NonProfit->Set2->Organisation->__toString() . '</th><td>' . $Answers->$section->NonProfit->Set2->AimOfOrganisation->__toString() . '</td></tr>';
					$html .= '</table><hr>';
				}
			}
			if($section == 'VisionMissionCoreValues')
			{
				foreach($results AS $row)
				{
					$log_xml = $row['wb_content'];
					$log_xml = XML::loadSimpleXML($log_xml);
					$Answers = $log_xml->Answers;
					$html .= '<table class="table small"><tr class="bg-gray"><th colspan="2">Modified DateTime: ' . Date::to($row['created'], Date::DATETIME) . '</th></tr>';
					$html .= '<tr><th colspan="2">Superdrug Statement</th></tr>';
					$html .= '<tr><td colspan="2">Our purpose is quite ' . $Answers->$section->SuperdrugStatement->Blank1->__toString() . ' to be the ' . $Answers->$section->SuperdrugStatement->Blank2->__toString() . '
				in everyday accessible ' . $Answers->$section->SuperdrugStatement->Blank3->__toString() . ' and health. We are committed to bring ' . $Answers->$section->SuperdrugStatement->Blank4->__toString() . '
				and the latest styles and trends to ' . $Answers->$section->SuperdrugStatement->Blank5->__toString() . ' high streets in the UK and Republic of Ireland
				at ' . $Answers->$section->SuperdrugStatement->Blank6->__toString() . ' prices.</td></tr>';
					$html .= '<tr><th>Vision</th><td>' . $Answers->$section->Vision->__toString() . '</td></tr>';
					$html .= '<tr><th>Mission</th><td>' . $Answers->$section->Mission->__toString() . '</td></tr>';
					$html .= '<tr><th>CoreValues</th><td>' . $Answers->$section->CoreValues->__toString() . '</td></tr>';
					$html .= '<tr><th>Benefits</th><td>' . $Answers->$section->Benefits->__toString() . '</td></tr>';
					$html .= '<tr><th>Impact on Role</th><td>' . $Answers->$section->ImpactOnRole->__toString() . '</td></tr>';
					$html .= '<tr><th>People</th><td>' . $Answers->$section->People->__toString() . '</td></tr>';
					$html .= '</table><hr>';
				}
			}
			if($section == 'BrandImagePromise')
			{
				foreach($results AS $row)
				{
					$log_xml = $row['wb_content'];
					$log_xml = XML::loadSimpleXML($log_xml);
					$Answers = $log_xml->Answers;
					$html .= '<table class="table small"><tr class="bg-gray"><th colspan="2">Modified DateTime: ' . Date::to($row['created'], Date::DATETIME) . '</th></tr>';
					$html .= '<tr><th>Superdrug Brand Image and Promise</th><td>' . $Answers->$section->Superdrug->__toString() . '</td></tr>';
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
					$html .= '</table><hr>';
				}
			}
			if($section == 'OrganisationCulture')
			{
				foreach($results AS $row)
				{
					$log_xml = $row['wb_content'];
					$log_xml = XML::loadSimpleXML($log_xml);
					$Answers = $log_xml->Answers;
					$html .= '<table class="table small"><tr class="bg-gray"><th colspan="2">Modified DateTime: ' . Date::to($row['created'], Date::DATETIME) . '</th></tr>';
					$html .= '<tr><th>Superdrug Brand Image and Promise</th><td>' . $Answers->$section->Superdrug->__toString() . '</td></tr>';
					$html .= '<tr><th colspan="2">Demonstrating service culture</th></tr>';
					$html .= '<tr><th>What do we do?</th><th>How do we do it?</th></tr>';
					$html .= '<tr><td>' . $Answers->$section->Demonstration->Set1->What->__toString() . '</td><td>' . $Answers->$section->Demonstration->Set1->How->__toString() . '</td></tr>';
					$html .= '<tr><td>' . $Answers->$section->Demonstration->Set2->What->__toString() . '</td><td>' . $Answers->$section->Demonstration->Set2->How->__toString() . '</td></tr>';
					$html .= '<tr><th colspan="2">Superdrug core values link to service culture</th></tr>';
					$html .= '<tr><td colspan="2">' . $Answers->$section->LinkOfCoreValues->__toString() . '</td></tr>';
					$html .= '<tr><th colspan="2">Organisational policy that affect your role and why</th></tr>';
					$html .= '<tr><td colspan="2">' . $Answers->$section->ImpactOfPoliciesProcedures->__toString() . '</td></tr>';
					$html .= '</table><hr>';
				}
			}
			if($section == 'DigitalMedia')
			{
				foreach($results AS $row)
				{
					$log_xml = $row['wb_content'];
					$log_xml = XML::loadSimpleXML($log_xml);
					$Answers = $log_xml->Answers;
					$html .= '<table class="table small"><tr class="bg-gray"><th colspan="2">Modified DateTime: ' . Date::to($row['created'], Date::DATETIME) . '</th></tr>';
					$html .= '<tr><td colspan="2">' . $Answers->$section->__toString() . '</td></tr>';
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
					for($i = 1; $i <= 6; $i++)
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
		return 'Unit 09';
	}

	public function getStepsWithQuestions()
	{
		return '3,4,5,6,7,8';
	}

	public function updateLearnerProgress(PDO $link)
	{
		parent::updateProgress($link, $this->getUnitReference(), $this->getQAN());
	}

	public function getPageTopLine()
	{
		if($this->savers_or_sp == 'savers')
			return '<p class="topLine"><span style="color: blue;">Understanding the organisation</span> <span style="color: red;">Understanding the organisation</span> </p>';
		else
			return '<p class="topLine"><span style="color: pink;">Understanding the organisation</span> <span style="color: gray;">Understanding the organisation</span> </p>';
	}

	public function getPageBottomLine()
	{
		if($this->savers_or_sp == 'savers')
			return '<p class="bottomLine"><span style="color: blue;">Understanding the organisation</span> <span style="color: red;">Understanding the organisation</span> </p>';
		else
			return '<p class="bottomLine"><span style="color: pink;">Understanding the organisation</span> <span style="color: gray;">Understanding the organisation</span> </p>';
	}

	public function getQAN()
	{
		return Workbook::CS_QAN;
	}
}
?>