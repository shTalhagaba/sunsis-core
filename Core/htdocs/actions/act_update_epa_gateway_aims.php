<?php
class update_epa_gateway_aims implements IAction
{
	public function execute(PDO $link)
	{
        $result = DAO::getResultset($link, "SELECT * FROM epa_gateway_dates WHERE learnaimref IN ('118AS','118GW','118EP','119AS','119GW','119EP','550AS'
,'550GW','550EPA','430AS','430GW','430EP','309AS','309GW','237EP','237GW','237AS','364EP','364GW','364AS','364AS','139GW','139AS'
,'104EP','104GW','104AS','655EP','139EP','655GW','655AS','PRINCE2','105EP','105GW','105AS','128EP','128GW','309EP','551AS','551GW'
,'551EP','537AS','537GW','537EP','308AS','308GW','308EP','80GW','80EPA','128AS','PMQ');
", DAO::FETCH_ASSOC);
        foreach($result AS $row)
        {
            $uln = $row['uln'];
            $ilrs = DAO::getResultset($link, "select * From ilr where locate($uln,ilr)>0 and submission in ('W13','W02')", DAO::FETCH_ASSOC);
            $learnstartdate = $row['start_date'];
            $targetdate = $row['target_date'];
            $actenddate = $row['actual_end_date'];
            foreach($ilrs as $ilr)
            {
                $L03 = $ilr['L03'];
                $tr_id = $ilr['tr_id'];
                $submission = $ilr['submission'];
                $contract_id = $ilr['contract_id'];
                $ilrxml = $ilr['ilr'];
                $learnaimref = $row['learnaimref'];

                $ilr2 = @simplexml_load_string($ilrxml);
                $submission = $submission;
                $contract_id = $contract_id;
                foreach($ilr2->LearningDelivery AS $LearningDelivery)
                {
                    IF($LearningDelivery->LearnAimRef==$learnaimref)
                        pre($LearningDelivery);
                    IF($LearningDelivery->LearnAimRef==$learnaimref and $LearningDelivery->LearnStartDate==$learnstartdate)
                    {
                        if($actenddate!="")
                        {
                            if(isset($LearningDelivery->LearnActEndDate))
                                $LearningDelivery->LearnActEndDate = $actenddate;
                            else
                                $LearningDelivery->addChild('LearnActEndDate', $actenddate);

                                pre($LearningDelivery);

                        }

                    }
                }
                /*$dom = NEW DOMDocument;
                $dom->preserveWhiteSpace = FALSE;
                @$dom->loadXML($ilr->saveXML());
                $dom->formatOutput = TRUE;
                $modified_ilr = $dom->saveXml();
                $modified_ilr = str_replace('<?xml version="1.0"?>', '', $modified_ilr);
                DAO::EXECUTE($link, "UPDATE ilr SET ilr.ilr = '{$modified_ilr}' WHERE ilr.tr_id = '$previous_tr_id' AND ilr.contract_id = '$contract_id' AND ilr.submission = '$submission'");*/

            }            
        }
    }
}