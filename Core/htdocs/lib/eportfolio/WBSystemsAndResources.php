<?php
class WBSystemsAndResources extends Workbook
{
	public function __construct($tr_id)
	{
		$this->wb_title = 'WBSystemsAndResources';
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
		{
			$journey .= '<Act'.$i.'></Act'.$i.'>';
			$journey .= '<DC'.$i.'></DC'.$i.'>';
		}
		$journey .= '</Journey>';
		$xml = <<<XML
<Workbook title="systems_and_resources">
	<TrainingRecordID></TrainingRecordID>
	<Answers>
		$journey
		<HowToRegisterBeautyCard></HowToRegisterBeautyCard>
		<SystemsUse>
			<SuperdrugSystems>
				<Set1>
					<System></System>
					<Comments></Comments>
				</Set1>
				<Set2>
					<System></System>
					<Comments></Comments>
				</Set2>
				<Set3>
					<System></System>
					<Comments></Comments>
				</Set3>
				<Set4>
					<System></System>
					<Comments></Comments>
				</Set4>
				<Set5>
					<System></System>
					<Comments></Comments>
				</Set5>
			</SuperdrugSystems>
			<ImpactOfNoSystems></ImpactOfNoSystems>
		</SystemsUse>
		<Research></Research>
	</Answers>
	<Feedback>
		<HowToRegisterBeautyCard>
			<Status></Status>
			<Comments></Comments>
		</HowToRegisterBeautyCard>
		<SystemsUse>
			<Status></Status>
			<Comments></Comments>
		</SystemsUse>
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
			if($section == 'HowToRegisterBeautyCard')
			{
				foreach($results AS $row)
				{
					$log_xml = $row['wb_content'];
					$log_xml = XML::loadSimpleXML($log_xml);
					$Answers = $log_xml->Answers;
					$html .= '<table class="table small"><tr class="bg-gray"><th colspan="2">Modified DateTime: ' . Date::to($row['created'], Date::DATETIME) . '</th></tr>';
					$html .= '<tr><th>Impact of no system use</th><td>' . $Answers->$section->__toString() . '</td></tr>';
					$html .= '</table><hr>';
				}
			}
			if($section == 'SystemsUse')
			{
				foreach($results AS $row)
				{
					$log_xml = $row['wb_content'];
					$log_xml = XML::loadSimpleXML($log_xml);
					$Answers = $log_xml->Answers;
					$html .= '<table class="table small"><tr class="bg-gray"><th colspan="2">Modified DateTime: ' . Date::to($row['created'], Date::DATETIME) . '</th></tr>';
					$html .= '<tr><th>System/ Equipment/ Technology</th><th>Comments</th></tr>';
					for($i = 1; $i <= 5; $i++)
					{
						$key = 'Set'.$i;
						$html .= '<tr><td>' . $Answers->$section->SuperdrugSystems->$key->System->__toString() . '</td><td>' . $Answers->$section->SuperdrugSystems->$key->Comments->__toString() . '</td></tr>';
					}
					$html .= '<tr><th colspan="2">Impact of no system use</th></tr>';
					$html .= '<tr><td colspan="2">' . $Answers->$section->ImpactOfNoSystems->__toString() . '</td></tr>';
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
		return $this->savers_or_sp == 'savers'?'7':'4,7';
	}

	public function updateLearnerProgress(PDO $link)
	{
		parent::updateProgress($link, $this->getUnitReference(), $this->getQAN());
	}

	public static function getLearningJourneyItems($savers_or_sp = '')
	{
		if($savers_or_sp == 'savers')
			return array(
				"Basic sale cash/card"
				,"Age restricted sales"
				,"Coupon"
				,"E-Cigs"
				,"Voids/refunds/exchanges policy"
				,"Price reductions policy"
				,"Damages Policy"
				,"Date code reductions/policy"
				,"Staff shopping"
				,"Price enquiry"
				,"Manual bar code"
				,"Active selling/link selling"
				,"Greetings/customer service language"
			);
		else
			return array(
				"Basic sale cash/card"
				,"Gift voucher"
				,"Coupon"
				,"Love2 shop voucher"
				,"Voids/refunds/exchanges policy"
				,"Price reductions policy"
				,"Damages Policy"
				,"Date code reductions/policy"
				,"Staff discount/shopping"
				,"Price enquiry"
				,"Manual bar code"
				,"Active selling/link selling"
				,"Beauty cards/registration"
				,"Greetings/customer service language"
				,"Learning "
				,"Security Tagging/spider wraps"
				,"Alarmed Barriers"
				,"Fake notes"
				,"Star Buys"
				,"Queues/bells"
				,"Detector pens/scanner"
				,"Counter cache/till limits"
				,"Till limits/cash/till security"
				,"Receipts"
				,"Data protection"
				,"Customer law / Legalities"
				,"HHT- Price enquiry"
				,"HHT- Tag request"
				,"HHT- Pick list"
				,"HHT- Price check"
				,"HHT- Zero to Zero"
				,"HHT- Damages"
				,"HHT- Look up product information"
				,"iPad - Customer orders/pick orders"
				,"iPad – Product information"
				,"Introduction to the back office"
			);
	}

	public function getPageTopLine()
	{
		if($this->savers_or_sp == 'savers')
			return '<p class="topLine"><span style="color: blue;">Systems and resources</span> <span style="color: red;">Systems and resources</span> </p>';
		else
			return '<p class="topLine"><span style="color: pink;">Systems and resources</span> <span style="color: gray;">Systems and resources</span> </p>';
	}

	public function getPageBottomLine()
	{
		if($this->savers_or_sp == 'savers')
			return '<p class="bottomLine"><span style="color: blue;">Systems and resources</span> <span style="color: red;">Systems and resources</span> </p>';
		else
			return '<p class="bottomLine"><span style="color: pink;">Systems and resources</span> <span style="color: gray;">Systems and resources</span> </p>';
	}

	public function getQAN()
	{
		return Workbook::CS_QAN;
	}
}
?>