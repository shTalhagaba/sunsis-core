<?php
class update_city_skills implements IAction
{
	public function execute(PDO $link)
	{
		
        $sql = "SELECT * from temp inner join tr on tr.uln = temp.uln WHERE temp.uln is not null and temp.uln not in ('8215643077')";
        $result = DAO::getResultset($link, $sql, DAO::FETCH_ASSOC);
        foreach($result AS $row)
        {
            $uln = $row['uln'];
            $learnaimref = $row['learnaimref'];
            $enddate = $row['enddate'];
            $tr_id = $row['id'];

            $xmlString = DAO::getSingleValue($link, "select ilr from ilr where tr_id = $tr_id and submission = 'W08' and contract_id in (select id from contracts where contract_year = '2024')");
            $learnAimRefTarget = $learnaimref; 
            $newCompStatusValue = "3";  

            if($xmlString=="")
                continue;

            // Load the XML string
            $dom = new DOMDocument;
            $dom->loadXML($xmlString, LIBXML_NOERROR | LIBXML_NOWARNING);
            $xpath = new DOMXPath($dom);

            
            $learningDeliveryFAMNodes = $xpath->query("//LearningDelivery[LearnAimRef='$learnAimRefTarget']/LearningDeliveryFAM");

            foreach ($learningDeliveryFAMNodes as $learningDeliveryFAM) {
                // Use "./LearnDelFAMDateFrom" for relative path
                $act = $xpath->query("./LearnDelFAMDateFrom", $learningDeliveryFAM)->item(0);
            
                if ($act) {
                    var_dump($act->nodeValue);
                } else {
                    pre ("LearnDelFAMDateFrom not found.\n");
                }
            }
            
            pre($uln);

            // Find <LearningDelivery> with <LearnAimRef> = 60153532
            /*$learningDeliveryNodes = $xpath->query("//LearningDelivery[LearnAimRef='$learnAimRefTarget']");
            
            foreach ($learningDeliveryNodes as $learningDelivery) 
            {
                $compStatus = $xpath->query("CompStatus", $learningDelivery)->item(0);
                if ($compStatus && $compStatus->nodeValue != 1)
                    continue; 
                if ($compStatus) 
                {
                    $compStatus->nodeValue = $newCompStatusValue;
                } 
                else 
                {
                    $newCompStatus = $dom->createElement("CompStatus", $newCompStatusValue);
                    $learningDelivery->appendChild($newCompStatus);
                }

                $outcome = $xpath->query("Outcome", $learningDelivery)->item(0);
                if ($outcome) 
                {
                    $outcome->nodeValue = '3';
                } 
                else 
                {
                    $newoutcome = $dom->createElement("Outcome", '3');
                    $learningDelivery->appendChild($newoutcome);
                }

                $withdraw = $xpath->query("WithdrawReason", $learningDelivery)->item(0);
                if ($withdraw) 
                {
                    $withdraw->nodeValue = '97';
                } 
                else 
                {
                    $newwithdraw = $dom->createElement("WithdrawReason", '97');
                    $learningDelivery->appendChild($newwithdraw);
                }

                $enddatenode = $xpath->query("LearnActEndDate", $learningDelivery)->item(0);
                if ($enddatenode) 
                {
                    $enddatenode->nodeValue = $enddate;
                } 
                else 
                {
                    $newenddate = $dom->createElement("LearnActEndDate", $enddate);
                    $learningDelivery->appendChild($newenddate);
                }

            }*/
            
            // Save the modified XML
            $updatedXmlString = $dom->saveXML();
            
            DAO::execute($link, "update ilr set ilr = '$updatedXmlString'  where tr_id = $tr_id and submission = 'W08' and contract_id in (select id from contracts where contract_year = '2024')");            

        }

    }
}