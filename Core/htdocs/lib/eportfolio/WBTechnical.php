<?php
class WBTechnical extends Workbook
{
    public function __construct($tr_id)
    {
        $this->wb_title = 'WBTechnical';
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
	    $instore_activity = '<InstoreActivity>';
	    for($i = 1; $i <= count(self::getLearningJourneyItems2()); $i++)
	    {
		    $instore_activity .= '<DC'.$i.'></DC'.$i.'>';
	    }
	    $instore_activity .= '</InstoreActivity>';
        $xml = <<<XML
<Workbook title="technical">
	<TrainingRecordID></TrainingRecordID>
	<Answers>
		$journey
		<HowToRegisterBeautyCard></HowToRegisterBeautyCard>
		<TechQuestions>
			<Q1></Q1>
			<Q2></Q2>
			<Q3></Q3>
			<Q4></Q4>
			<Q5></Q5>
		</TechQuestions>
		$instore_activity
		<QualificationQuestions>
			<Unit1_1></Unit1_1>
			<Unit1_2></Unit1_2>
			<Unit2_1></Unit2_1>
			<Unit2_2></Unit2_2>
		</QualificationQuestions>
		<Research></Research>
	</Answers>
	<Feedback>
		<HowToRegisterBeautyCard>
			<Status></Status>
			<Comments></Comments>
		</HowToRegisterBeautyCard>
		<TechQuestions>
			<Status></Status>
			<Comments></Comments>
		</TechQuestions>
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
        $feedback = $this->wb_content->Feedback;
        if(isset($feedback->QualificationQuestions->Status) && $feedback->QualificationQuestions->Status->__toString() == 'A')
            $total += 33.33;
        if(isset($feedback->TechQuestions->Status) && $feedback->TechQuestions->Status->__toString() == 'A')
            $total += 33.33;
        if(isset($feedback->QualificationQuestions->Status) && $feedback->QualificationQuestions->Status->__toString() == 'A')
            $total += 33.33;

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
                    $html .= '<table class="table small"><tr><th colspan="1">Modified DateTime: ' . Date::to($row['created'], Date::DATETIME) . '</th></tr>';
                    $html .= '<tr><th>Explain how you would register a new customers Beauty Card</th></tr>';
                    $html .= '<tr><td>' . $log_xml->Answers->HowToRegisterBeautyCard->__toString() . '</td></tr>';
                    $html .= '</table><hr>';
                }
            }
	        if($section == 'TechQuestions')
	        {
		        foreach($results AS $row)
		        {
			        $log_xml = $row['wb_content'];
			        $log_xml = XML::loadSimpleXML($log_xml);
			        $Answers = $log_xml->Answers;
			        $html .= '<table class="table small"><tr class="bg-gray"><th colspan="2">Modified DateTime: ' . Date::to($row['created'], Date::DATETIME) . '</th></tr>';
			        $html .= '<tr><th>How does the store technology support the effective and efficient sale of products and services</th><td>' . $Answers->$section->Q1->__toString() . '</td></tr>';
			        $html .= '<tr><th>Have you ever experienced technology failing at work?</th><td>' . $Answers->$section->Q2->__toString() . '</td></tr>';
			        $html .= '<tr><th>What would you do if the iPad doesn\'t work when you need to do a click and collect order</th><td>' . $Answers->$section->Q3->__toString() . '</td></tr>';
			        $html .= '<tr><th>What would you do if the till stops working</th><td>' . $Answers->$section->Q4->__toString() . '</td></tr>';
			        $html .= '<tr><th>What would you do if the HHT stops working</th><td>' . $Answers->$section->Q5->__toString() . '</td></tr>';
			        $html .= '</table><hr>';
		        }
	        }
            if($section == 'QualificationQuestions')
            {
                foreach($results AS $row)
                {
                    $log_xml = $row['wb_content'];
                    $log_xml = XML::loadSimpleXML($log_xml);
                    $html .= '<table class="table small"><tr><th colspan="2">Modified DateTime: ' . Date::to($row['created'], Date::DATETIME) . '</th></tr>';
                    $html .= '<tr><th>Unit 9 1.1</th><td>'.$log_xml->Answers->QualificationQuestions->Unit1_1->__toString().'</td></tr>';
                    $html .= '<tr><th>Unit 9 1.2</th><td>'.$log_xml->Answers->QualificationQuestions->Unit1_2->__toString().'</td></tr>';
                    $html .= '<tr><th>Unit 9 2.1</th><td>'.$log_xml->Answers->QualificationQuestions->Unit2_1->__toString().'</td></tr>';
                    $html .= '<tr><th>Unit 9 2.2</th><td>'.$log_xml->Answers->QualificationQuestions->Unit2_2->__toString().'</td></tr>';
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
        return '3,6,8';
    }

    public function updateLearnerProgress(PDO $link)
    {
        parent::updateProgress($link, $this->getUnitReference(), $this->getQAN());
    }

     public function getPageTopLine()
    {
        if($this->savers_or_sp == 'savers')
            return '<p class="topLine"><span style="color: blue;">Technical</span> <span style="color: red;">Technical</span> </p>';
        else
            return '<p class="topLine"><span style="color: pink;">Technical</span> <span style="color: gray;">Technical</span> </p>';
    }

    public function getPageBottomLine()
    {
        if($this->savers_or_sp == 'savers')
            return '<p class="bottomLine"><span style="color: blue;">Technical </span> <span style="color: red;">Technical </span> </p>';
        else
            return '<p class="bottomLine"><span style="color: pink;">Technical </span> <span style="color: gray;">Technical </span> </p>';
    }

    public static function getLearningJourneyItems($savers_or_sp = '')
    {
        if($savers_or_sp == 'savers')
            return array(
             'Basic sale cash/card'
            ,'Age restricted sales'
            ,'Coupon'
            ,'E-Cigs'
            ,'Voids/refunds/exchanges policy'
            ,'Price reductions policy'
            ,'Damages Policy'
            ,'Date code reductions/policy'
            ,'Staff shopping'
            ,'Price enquiry'
            ,'Manual bar code'
            ,'Active selling/link selling'
            ,'Greetings/customer service language'
            );
        else
            return array(
                'Basic sale cash/card'
            ,'Gift voucher'
            ,'Coupon'
            ,'Love2 shop voucher'
            ,'Voids/refunds/exchanges policy'
            ,'Price reductions policy'
            ,'Damages Policy'
            ,'Date code reductions/policy'
            ,'Staff discount/shopping'
            ,'Price enquiry'
            ,'Manual bar code'
            ,'Active selling/link selling'
            ,'Beauty cards/registration'
            ,'Greetings/customer service language'
            );
    }
    public static function getLearningJourneyItems2($savers_or_sp = '')
    {
        if($savers_or_sp == 'savers')
            return array(
                'Security Tagging'
            ,'Alarmed Barriers'
            ,'Fake notes'
            ,'SAS'
            ,'Queues/bells'
            ,'Detector pens/scanner'
            ,'Till limits/cash/till security'
            ,'Receipts'
            ,'Data protection'
            ,'Customer law / Legalities'
            ,'HHT- Price enquiry'
            ,'HHT- Tag request'
            ,'HHT- Pick list'
            ,'HHT- Price check'
            ,'HHT- Zero to Zero'
            ,'HHT- Damages'
            ,'HHT- Look up product information'
            ,'Introduction to the back office'
            );
        else
            return array(
                'Security Tagging/spider wraps'
            ,'Alarmed Barriers'
            ,'Fake notes'
            ,'Star Buys'
            ,'Queues/bells'
            ,'Detector pens/scanner'
            ,'Counter cache/till limits'
            ,'Till limits/cash/till security'
            ,'Receipts'
            ,'Data protection'
            ,'Customer law / Legalities'
            ,'HHT- Price enquiry'
            ,'HHT- Tag request'
            ,'HHT- Pick list'
            ,'HHT- Price check'
            ,'HHT- Zero to Zero'
            ,'HHT- Damages'
            ,'HHT- Look up product information'
            ,'iPad - Customer orders/pick orders'
            ,'iPad - Product information'
            ,'Introduction to the back office'
            );

    }

	public function getQAN()
	{
		return Workbook::RETAIL_QAN;
	}
}
?>