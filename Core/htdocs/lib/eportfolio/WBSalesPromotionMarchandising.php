<?php
class WBSalesPromotionMarchandising extends Workbook
{
	public function __construct($tr_id)
	{
		$this->wb_title = 'WBSalesPromotionMarchandising';
		$this->tr_id = $tr_id;
		$this->wb_content = self::getBlankXML();
	}

	/**
	 * @return xml
	 * @param null
	 */
	public static function getBlankXML()
	{
		$months_sales = '';
		foreach(self::getMonthsDDL() AS $key => $val)
		{
			$months_sales .= '<'.$key.'SOpp></'.$key.'SOpp>';
			$months_sales .= '<'.$key.'RProd></'.$key.'RProd>';
		}
		$displays = '';
		for($i = 1; $i <= 5; $i++)
		{
			$displays .= '<Set'.$i.'>';
			$displays .= '<Dis></Dis>';
			$displays .= '<Prod></Prod>';
			$displays .= '<What></What>';
			$displays .= '</Set'.$i.'>';
		}
		$xml = <<<XML
<Workbook title="sales_promotion_marchandising">
	<TrainingRecordID></TrainingRecordID>
	<Answers>
		<SalesTarget>
			<Total></Total>
			<StarBuys></StarBuys>
			<BeautyCards></BeautyCards>
			<YourHelp></YourHelp>
		</SalesTarget>
		<SalesOpportunities>
			$months_sales
		</SalesOpportunities>
		<SellingProcess>
			<A></A>
			<B></B>
			<C></C>
		</SellingProcess>
		<OvercomingObjections>
			<S1></S1>
			<S2></S2>
			<S3></S3>
		</OvercomingObjections>
		<Displays>
			$displays
		</Displays>
		<Window>
			<Your>
				<What></What>
				<IsItGood></IsItGood>
			</Your>
			<Competitors>
				<Set1>
					<Store></Store>
					<What></What>
					<GoodOrBad></GoodOrBad>
					<Why></Why>
				</Set1>
				<Set2>
					<Store></Store>
					<What></What>
					<GoodOrBad></GoodOrBad>
					<Why></Why>
				</Set2>
			</Competitors>
		</Window>
		<Merchandising>
			<DualProducts></DualProducts>
			<Spots>
				<H1></H1>
				<H2></H2>
				<M1></M1>
				<M2></M2>
				<C1></C1>
				<C2></C2>
			</Spots>
			<SectionPlacement></SectionPlacement>
			<ComplProds></ComplProds>
			<Exercise></Exercise>
			<ExerciseNext></ExerciseNext>
		</Merchandising>
		<QualificationQuestions>
			<Question1></Question1>
			<Question2></Question2>
			<Question3></Question3>
			<Question4></Question4>
			<Question5></Question5>
			<Question6></Question6>
			<Question7></Question7>
			<Question8></Question8>
			<Question9></Question9>
		</QualificationQuestions>
		<Research></Research>
	</Answers>
	<Feedback>
		<SalesTarget>
			<Status></Status>
			<Comments></Comments>
		</SalesTarget>
		<SalesOpportunities>
			<Status></Status>
			<Comments></Comments>
		</SalesOpportunities>
		<SellingProcess>
			<Status></Status>
			<Comments></Comments>
		</SellingProcess>
		<OvercomingObjections>
			<Status></Status>
			<Comments></Comments>
		</OvercomingObjections>
		<Displays>
			<Status></Status>
			<Comments></Comments>
		</Displays>
		<Window>
			<Status></Status>
			<Comments></Comments>
		</Window>
		<Merchandising>
			<Status></Status>
			<Comments></Comments>
		</Merchandising>
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
			if($section == 'SalesTarget')
			{
				foreach($results AS $row)
				{
					$log_xml = $row['wb_content'];
					$log_xml = XML::loadSimpleXML($log_xml);
					$Answers = $log_xml->Answers;
					$html .= '<table class="table small"><tr class="bg-gray"><th colspan="2">Modified DateTime: ' . Date::to($row['created'], Date::DATETIME) . '</th></tr>';
					$html .= '<tr><td>Total sales:<td>' . $Answers->$section->Total->__toString() . '</td></tr>';
					$html .= '<tr><td>Start Buys:<td>' . $Answers->$section->StarBuys->__toString() . '</td></tr>';
					$html .= '<tr><td>Beauty cards:<td>' . $Answers->$section->BeautyCards->__toString() . '</td></tr>';
					$html .= '<tr><td>What do you personally have to do...<td>' . $Answers->$section->YourHelp->__toString() . '</td></tr>';
					$html .= '</table><hr>';
				}
			}
			if($section == 'SalesOpportunities')
			{
				foreach($results AS $row)
				{
					$log_xml = $row['wb_content'];
					$log_xml = XML::loadSimpleXML($log_xml);
					$Answers = $log_xml->Answers;
					$html .= '<table class="table small"><tr class="bg-gray"><th colspan="4">Modified DateTime: ' . Date::to($row['created'], Date::DATETIME) . '</th></tr>';
					$html .= '<tr><th>Month</th><th>Key sales opportunities</th><th>Relevant products</th></tr>';
					foreach(self::getMonthsDDL() AS $key => $value)
					{
						$sopp = $key.'SOpp';
						$rprod = $key.'RProd';
						$html .= '<tr>';
						$html .= '<td>' . $value . '</td>';
						$html .= '<td>' . $Answers->$section->$sopp->__toString() . '</td>';
						$html .= '<td>' . $Answers->$section->$rprod->__toString() . '</td>';
						$html .= '</tr>';
					}
					$html .= '</table><hr>';
				}
			}
			if($section == 'SellingProcess')
			{
				foreach($results AS $row)
				{
					$log_xml = $row['wb_content'];
					$log_xml = XML::loadSimpleXML($log_xml);
					$Answers = $log_xml->Answers;
					$html .= '<table class="table small"><tr class="bg-gray"><th colspan="3">Modified DateTime: ' . Date::to($row['created'], Date::DATETIME) . '</th></tr>';
					$html .= '<tr><th>A</th><th>B</th><th>C</th></tr>';
					$html .= '<tr><td>' . $Answers->$section->A->__toString() . '</td><td>' . $Answers->$section->B->__toString() . '</td><td>' . $Answers->$section->C->__toString() . '</td></tr>';
					$html .= '</table><hr>';
				}
			}
			if($section == 'OvercomingObjections')
			{
				foreach($results AS $row)
				{
					$log_xml = $row['wb_content'];
					$log_xml = XML::loadSimpleXML($log_xml);
					$Answers = $log_xml->Answers;
					$html .= '<table class="table small"><tr class="bg-gray"><th colspan="3">Modified DateTime: ' . Date::to($row['created'], Date::DATETIME) . '</th></tr>';
					$html .= '<tr><th>Scenario 1</th><th>Scenario 2</th><th>Scenario 3</th></tr>';
					$html .= '<tr><td>' . $Answers->$section->S1->__toString() . '</td><td>' . $Answers->$section->S2->__toString() . '</td><td>' . $Answers->$section->S3->__toString() . '</td></tr>';
					$html .= '</table><hr>';
				}
			}
			if($section == 'Displays')
			{
				foreach($results AS $row)
				{
					$log_xml = $row['wb_content'];
					$log_xml = XML::loadSimpleXML($log_xml);
					$Answers = $log_xml->Answers;
					$html .= '<table class="table small"><tr class="bg-gray"><th colspan="3">Modified DateTime: ' . Date::to($row['created'], Date::DATETIME) . '</th></tr>';
					$html .= '<tr><th>Display</th><th>Product</th><th>Why would you want to buy?</th></tr>';
					for($i = 1; $i <= 5; $i++)
					{
						$set = 'Set'.$i;
						$html .= '<tr>';
						$html .= '<td>' . $Answers->$section->$set->Dis->__toString() . '</td><td>' . $Answers->$section->$set->Prod->__toString() . '</td><td>' . $Answers->$section->$set->What->__toString() . '</td></tr>';

					}
					$html .= '</table><hr>';
				}
			}
			if($section == 'Window')
			{
				foreach($results AS $row)
				{
					$log_xml = $row['wb_content'];
					$log_xml = XML::loadSimpleXML($log_xml);
					$Answers = $log_xml->Answers;
					$html .= '<table class="table small"><tr class="bg-gray"><th colspan="4">Modified DateTime: ' . Date::to($row['created'], Date::DATETIME) . '</th></tr>';
					$html .= '<tr><th>What\' in your window?Window</th><td colspan="3">'.$Answers->$section->Your->What->__toString().'</td> </tr>';
					$html .= '<tr><th>Does it give positive impression?</th><td colspan="3">'.$Answers->$section->Your->IsItGood->__toString().'</td> </tr>';
					$html .= '<tr><th>Store</th><th>What\'s in the window</th><th>Good or Bad</th><th>Why</th></tr>';
					for($i = 1; $i <= 2; $i++)
					{
						$key = 'Set'.$i;
						$html .= '<tr>';
						$html .= '<td>' . $Answers->$section->Competitors->$key->Store->__toString() . '</td>';
						$html .= '<td>' . $Answers->$section->Competitors->$key->What->__toString() . '</td>';
						$html .= '<td>' . $Answers->$section->Competitors->$key->GoodOrBad->__toString() . '</td>';
						$html .= '<td>' . $Answers->$section->Competitors->$key->Why->__toString() . '</td>';
						$html .= '</tr>';
					}
					$html .= '</table><hr>';
				}
			}
			if($section == 'Merchandising')
			{
				foreach($results AS $row)
				{
					$log_xml = $row['wb_content'];
					$log_xml = XML::loadSimpleXML($log_xml);
					$Answers = $log_xml->Answers;
					$html .= '<table class="table small"><tr class="bg-gray"><th colspan="3">Modified DateTime: ' . Date::to($row['created'], Date::DATETIME) . '</th></tr>';
					$html .= '<tr><th>Dual merchandising</th><td colspan="2">'.$Answers->$section->DualProducts->__toString().'</td> </tr>';
					$html .= '<tr><th>Spot</th><th>Location in store</th><th>Products displayed</th></tr>';
					$html .= '<tr><th>Hot</th><td>' . $Answers->$section->Spots->H1->__toString() . '</td><td>' . $Answers->$section->Spots->H2->__toString() . '</td></tr>';
					$html .= '<tr><th>Medium</th><td>' . $Answers->$section->Spots->M1->__toString() . '</td><td>' . $Answers->$section->Spots->M2->__toString() . '</td></tr>';
					$html .= '<tr><th>Cold</th><td>' . $Answers->$section->Spots->C1->__toString() . '</td><td>' . $Answers->$section->Spots->C2->__toString() . '</td></tr>';
					$html .= '<tr><th>Choose a section ...</th><td colspan="2">'.$Answers->$section->SectionPlacement->__toString().'</td> </tr>';
					$html .= '<tr><th>Next think about ...</th><td colspan="2">'.$Answers->$section->ComplProds->__toString().'</td> </tr>';
					$html .= '<tr><th>For your next task ...</th><td colspan="2">'.$Answers->$section->Exercise->__toString().'</td> </tr>';
					$html .= '<tr><th>Finally, discuss the ...</th><td colspan="2">'.$Answers->$section->ExerciseNext->__toString().'</td> </tr>';
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
					for($i = 1; $i <= 9; $i++)
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
		return 'Unit 10';
	}

	public function getStepsWithQuestions()
	{
		return '1,2,3,4,5,7,8,11';
	}

	public function updateLearnerProgress(PDO $link)
	{
		parent::updateProgress($link, $this->getUnitReference(), $this->getQAN());
	}

	public function getPageTopLine()
	{
		if($this->savers_or_sp == 'savers')
			return '<p class="topLine"><span style="color: blue;">Sales and Promotion & Merchandising</span> <span style="color: red;">Sales and Promotion & Merchandising</span> </p>';
		else
			return '<p class="topLine"><span style="color: pink;">Sales and Promotion & Merchandising</span> <span style="color: gray;">Sales and Promotion & Merchandising</span> </p>';
	}

	public function getPageBottomLine()
	{
		if($this->savers_or_sp == 'savers')
			return '<p class="bottomLine"><span style="color: blue;">Sales and Promotion & Merchandising</span> <span style="color: red;">Sales and Promotion & Merchandising</span> </p>';
		else
			return '<p class="bottomLine"><span style="color: pink;">Sales and Promotion & Merchandising</span> <span style="color: gray;">Sales and Promotion & Merchandising</span> </p>';
	}

	public function getQAN()
	{
		return Workbook::RETAIL_QAN;
	}

	public static function getMonthsDDL()
	{
		return array(
			'Jan' => 'January'
			,'Feb' => 'February'
			,'Mar' => 'March'
			,'Apr' => 'April'
			,'May' => 'May'
			,'Jun' => 'June'
			,'Jul' => 'July'
			,'Aug' => 'August'
			,'Sep' => 'September'
			,'Oct' => 'October'
			,'Nov' => 'November'
			,'Dec' => 'December'
		);
	}
}
?>