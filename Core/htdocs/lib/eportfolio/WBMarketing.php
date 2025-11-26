<?php
class WBMarketing extends Workbook
{
	public function __construct($tr_id)
	{
		$this->wb_title = 'WBMarketing';
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
<Workbook title="marketing">
	<TrainingRecordID></TrainingRecordID>
	<Answers>
		<DifferentiationWithCompetitors></DifferentiationWithCompetitors>
		<MarketingStrategy>
			<Set1>
				<BN></BN>
				<Type></Type>
				<BP></BP>
				<HowDifferent></HowDifferent>
			</Set1>
			<Set2>
				<BN></BN>
				<Type></Type>
				<BP></BP>
				<HowDifferent></HowDifferent>
			</Set2>
		</MarketingStrategy>
		<USP>
			<OurUSP></OurUSP>
			<SimilarBusinessesUSP>
				<Set1>
					<BN></BN>
					<USP></USP>
					<Comparison></Comparison>
				</Set1>
				<Set2>
					<BN></BN>
					<USP></USP>
					<Comparison></Comparison>
				</Set2>
			</SimilarBusinessesUSP>
		</USP>
		<Advertising>
			<OurCompaign></OurCompaign>
			<SimilarBusinessCompaign>
				<Set1>
					<Activity></Activity>
					<Impact></Impact>
				</Set1>
				<Set2>
					<Activity></Activity>
					<Impact></Impact>
				</Set2>
			</SimilarBusinessCompaign>
		</Advertising>
		<Competitors>
			<A></A>
			<B></B>
			<C></C>
		</Competitors>
		<SWOT>
			<OurSWOT>
				<Strength></Strength>
				<Weaknesses></Weaknesses>
				<Opportunities></Opportunities>
				<Threats></Threats>
			</OurSWOT>
			<CompetitorSWOT>
				<Strength></Strength>
				<Weaknesses></Weaknesses>
				<Opportunities></Opportunities>
				<Threats></Threats>
			</CompetitorSWOT>
		</SWOT>
		<BusinessStrapline>
			<Set1>
				<CN></CN>
				<SL></SL>
			</Set1>
			<Set2>
				<CN></CN>
				<SL></SL>
			</Set2>
			<Set3>
				<CN></CN>
				<SL></SL>
			</Set3>
			<Set4>
				<CN></CN>
				<SL></SL>
			</Set4>
		</BusinessStrapline>
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
		<DifferentiationWithCompetitors>
			<Status></Status>
			<Comments></Comments>
		</DifferentiationWithCompetitors>
		<MarketingStrategy>
			<Status></Status>
			<Comments></Comments>
		</MarketingStrategy>
		<USP>
			<Status></Status>
			<Comments></Comments>
		</USP>
		<Advertising>
			<Status></Status>
			<Comments></Comments>
		</Advertising>
		<Competitors>
			<Status></Status>
			<Comments></Comments>
		</Competitors>
		<SWOT>
			<Status></Status>
			<Comments></Comments>
		</SWOT>
		<BusinessStrapline>
			<Status></Status>
			<Comments></Comments>
		</BusinessStrapline>
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
			if($section == 'DifferentiationWithCompetitors')
			{
				foreach($results AS $row)
				{
					$log_xml = $row['wb_content'];
					$log_xml = XML::loadSimpleXML($log_xml);
					$Answers = $log_xml->Answers;
					$html .= '<table class="table small"><tr class="bg-gray"><th colspan="1">Modified DateTime: ' . Date::to($row['created'], Date::DATETIME) . '</th></tr>';
					$html .= '<tr><td>' . $Answers->$section->__toString() . '</td></tr>';
					$html .= '</table><hr>';
				}
			}
			if($section == 'MarketingStrategy')
			{
				foreach($results AS $row)
				{
					$log_xml = $row['wb_content'];
					$log_xml = XML::loadSimpleXML($log_xml);
					$Answers = $log_xml->Answers;
					$html .= '<table class="table small"><tr class="bg-gray"><th colspan="4">Modified DateTime: ' . Date::to($row['created'], Date::DATETIME) . '</th></tr>';
					$html .= '<tr><th>Business Name</th><th>Type</th><th>Position</th><th>How do they differentiate ...</th></tr>';
					for($i = 1; $i <= 2; $i++)
					{
						$key = 'Set'.$i;
						$html .= '<tr>';
						$html .= '<td>' . $Answers->$section->$key->BN->__toString() . '</td>';
						$html .= '<td>' . $Answers->$section->$key->Type->__toString() . '</td>';
						$html .= '<td>' . $Answers->$section->$key->BP->__toString() . '</td>';
						$html .= '<td>' . $Answers->$section->$key->HowDifferent->__toString() . '</td>';
						$html .= '</tr>';
					}
					$html .= '</table><hr>';
				}
			}
			if($section == 'USP')
			{
				foreach($results AS $row)
				{
					$log_xml = $row['wb_content'];
					$log_xml = XML::loadSimpleXML($log_xml);
					$Answers = $log_xml->Answers;
					$html .= '<table class="table small"><tr class="bg-gray"><th colspan="3">Modified DateTime: ' . Date::to($row['created'], Date::DATETIME) . '</th></tr>';
					$html .= '<tr><th>Our selling proposition ...</th><td colspan="2">'.$Answers->$section->OurUSP->__toString().'</td> </tr>';
					$html .= '<tr><th>Business Name</th><th>USP</th><th>How do they compare ...</th></tr>';
					for($i = 1; $i <= 2; $i++)
					{
						$key = 'Set'.$i;
						$html .= '<tr>';
						$html .= '<td>' . $Answers->$section->SimilarBusinessesUSP->$key->BN->__toString() . '</td>';
						$html .= '<td>' . $Answers->$section->SimilarBusinessesUSP->$key->USP->__toString() . '</td>';
						$html .= '<td>' . $Answers->$section->SimilarBusinessesUSP->$key->Comparison->__toString() . '</td>';
						$html .= '</tr>';
					}
					$html .= '</table><hr>';
				}
			}
			if($section == 'Advertising')
			{
				foreach($results AS $row)
				{
					$log_xml = $row['wb_content'];
					$log_xml = XML::loadSimpleXML($log_xml);
					$Answers = $log_xml->Answers;
					$html .= '<table class="table small"><tr class="bg-gray"><th colspan="2">Modified DateTime: ' . Date::to($row['created'], Date::DATETIME) . '</th></tr>';
					$html .= '<tr><th>Our promotional compaign ...</th><td>'.$Answers->$section->OurCompaign->__toString().'</td> </tr>';
					$html .= '<tr><th>Activity</th><th>How do they impact ...</th></tr>';
					for($i = 1; $i <= 2; $i++)
					{
						$key = 'Set'.$i;
						$html .= '<tr>';
						$html .= '<td>' . $Answers->$section->SimilarBusinessCompaign->$key->Activity->__toString() . '</td>';
						$html .= '<td>' . $Answers->$section->SimilarBusinessCompaign->$key->Impact->__toString() . '</td>';
						$html .= '</tr>';
					}
					$html .= '</table><hr>';
				}
			}
			if($section == 'BusinessStrapline')
			{
				foreach($results AS $row)
				{
					$log_xml = $row['wb_content'];
					$log_xml = XML::loadSimpleXML($log_xml);
					$Answers = $log_xml->Answers;
					$html .= '<table class="table small"><tr class="bg-gray"><th colspan="2">Modified DateTime: ' . Date::to($row['created'], Date::DATETIME) . '</th></tr>';
					$html .= '<tr><th>Company Name</th><th>Company strapline</th></tr>';
					for($i = 1; $i <= 4; $i++)
					{
						$key = 'Set'.$i;
						$html .= '<tr>';
						$html .= '<td>' . $Answers->$section->$key->CN->__toString() . '</td>';
						$html .= '<td>' . $Answers->$section->$key->SL->__toString() . '</td>';
						$html .= '</tr>';
					}
					$html .= '</table><hr>';
				}
			}
			if($section == 'Competitors')
			{
				foreach($results AS $row)
				{
					$log_xml = $row['wb_content'];
					$log_xml = XML::loadSimpleXML($log_xml);
					$Answers = $log_xml->Answers;
					$html .= '<table class="table small"><tr class="bg-gray"><th colspan="3">Modified DateTime: ' . Date::to($row['created'], Date::DATETIME) . '</th></tr>';
					$html .= '<tr><th>A</th><th>B</th><th>C</th></tr>';
					$html .= '<tr>';
					$html .= '<td>' . $Answers->$section->A->__toString() . '</td>';
					$html .= '<td>' . $Answers->$section->B->__toString() . '</td>';
					$html .= '<td>' . $Answers->$section->C->__toString() . '</td>';
					$html .= '</tr>';
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
					$html .= '<table class="table table-bordered small"><tr><th colspan="4">Your Store</th> </tr><tr><th>Strength</th><th>Weakness</th><th>Opportunity</th><th>Threat</th></tr>';
					$html .= '<tr>';
					$html .= '<td>' . $log_xml->Answers->SWOT->OurSWOT->Strength->__toString() . '</td>';
					$html .= '<td>' . $log_xml->Answers->SWOT->OurSWOT->Weaknesses->__toString() . '</td>';
					$html .= '<td>' . $log_xml->Answers->SWOT->OurSWOT->Opportunities->__toString() . '</td>';
					$html .= '<td>' . $log_xml->Answers->SWOT->OurSWOT->Threats->__toString() . '</td>';
					$html .= '</tr>';
					$html .= '<tr><th colspan="4">Competitor</th>';
					$html .= '<tr>';
					$html .= '<td>' . $log_xml->Answers->SWOT->CompetitorSWOT->Strength->__toString() . '</td>';
					$html .= '<td>' . $log_xml->Answers->SWOT->CompetitorSWOT->Weaknesses->__toString() . '</td>';
					$html .= '<td>' . $log_xml->Answers->SWOT->CompetitorSWOT->Opportunities->__toString() . '</td>';
					$html .= '<td>' . $log_xml->Answers->SWOT->CompetitorSWOT->Threats->__toString() . '</td>';
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
		return 'Unit 08';
	}

	public function getStepsWithQuestions()
	{
		return '1,2,3,4,5,7,8,9';
	}

	public function updateLearnerProgress(PDO $link)
	{
		parent::updateProgress($link, $this->getUnitReference(), $this->getQAN());
	}

	public function getPageTopLine()
	{
		if($this->savers_or_sp == 'savers')
			return '<p class="topLine"><span style="color: blue;">Marketing</span> <span style="color: red;">Marketing</span> </p>';
		else
			return '<p class="topLine"><span style="color: pink;">Marketing</span> <span style="color: gray;">Marketing</span> </p>';
	}

	public function getPageBottomLine()
	{
		if($this->savers_or_sp == 'savers')
			return '<p class="bottomLine"><span style="color: blue;">Marketing</span> <span style="color: red;">Marketing</span> </p>';
		else
			return '<p class="bottomLine"><span style="color: pink;">Marketing</span> <span style="color: gray;">Marketing</span> </p>';
	}

	public function getQAN()
	{
		return Workbook::RETAIL_QAN;
	}
}
?>