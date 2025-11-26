<?php
class update_choc implements IAction
{
	public function execute(PDO $link)
	{
        $id = isset($_POST['id']) ? $_POST['id'] : '';
        $tr_id = isset($_POST['tr_id']) ? $_POST['tr_id'] : '';

        if($id !== '' && !is_numeric($id))
		{
			throw new Exception("You must specify a numeric id in the querystring");
		}

        $tr = TrainingRecord::loadFromDatabase($link, $tr_id);
        if(is_null($tr))
        {
            throw new Exception("Invalid tr_id");        
        }
        $choc = Choc::loadFromDatabase($link, $id);

        if(isset($_POST['completion']) && $_POST['completion'] == 1)
        {
            $choc->choc_status = "COMPLETED";

        }
        else
        {
            $choc->choc_status = isset($_POST['choc_status']) ? $_POST['choc_status'] : null;
            $choc->assigned_to = isset($_POST['assigned_to']) ? $_POST['assigned_to'] : null;
        }
        if(isset($_POST['comments']) && $_POST['comments'] != '')
        {
            $choc->comments = Choc::saveComments($link, $choc, $_POST['comments']);
        }

        $choc->save($link);

        if(isset($_POST['completion']) && $_POST['completion'] == 1)
        {
            $this->makeChanges($link, $choc);

        }

        if(IS_AJAX)
        {
            return true;
        }

        if($choc->choc_status == "IN PROGRESS" && $choc->assigned_to != "")
        {
            // create a notification for the assigned to personnel
            $notification = new stdClass();
            $notification->user_id = $choc->assigned_to;
            $notification->detail = "You have been assigned a <strong>{$choc->choc_type}</strong> request.";
            $notification->type = "CHOC";
            $notification->link = "do.php?_action=read_training_record&id={$choc->tr_id}&tabChoc=1";
            DAO::saveObjectToTable($link, "user_notifications", $notification);
        }

        http_redirect('do.php?_action=read_training_record&id='.$_POST['tr_id'].'&tabChoc=1');
    }

    public function makeChanges(PDO $link, Choc $choc)
    {
        $this->updateIlr($link, $choc);
        $this->updateTrainingRecord($link, $choc);
        $this->updateLearnerRecord($link, $choc);
    }

    public function updateIlr(PDO $link, Choc $choc)
    {
        $choc_details = json_decode($choc->choc_details);
        $tr = TrainingRecord::loadFromDatabase($link, $choc->tr_id);
        $new_employer = isset($choc_details->new_employer) ? Organisation::loadFromDatabase($link, $choc_details->new_employer) : null;
        $new_employer_location = isset($choc_details->new_employer_location) ? Location::loadFromDatabase($link, $choc_details->new_employer_location) : null;

        $ilr_row = DAO::getObject($link, "SELECT * FROM ilr WHERE ilr.tr_id = '{$tr->id}' AND ilr.contract_id = '{$tr->contract_id}' ORDER BY submission DESC LIMIT 0, 1");
        if(isset($ilr_row->ilr))
        {
            $ilr_row->L01 = "000000";
            $ilr_row->A09 = "00000000";
            $ilr_row->contract_type = "0";
            if ($choc->choc_type == "Change of Employer")
            {
                if(isset($choc_details->new_employer) && $choc_details->new_employer != '')
                {
                    $ilr = XML::loadSimpleXML( $ilr_row->ilr );
                    $LearnerEmploymentStatus = $ilr->addChild('LearnerEmploymentStatus');
                    $LearnerEmploymentStatus->EmpStat = 10;
                    $LearnerEmploymentStatus->DateEmpStatApp = isset($choc_details->new_start_date) ? Date::toMySQL($choc_details->new_start_date) : '';
                    $LearnerEmploymentStatus->EmpId = (isset($new_employer->edrs) && $new_employer->edrs != '') ? $new_employer->edrs : '999999999';
                    $LOE = $LearnerEmploymentStatus->addChild('EmploymentStatusMonitoring');
                    $LOE->addChild('ESMType', 'LOE');
                    $LOE->addChild('ESMCode', 1);

                    $EII = $LearnerEmploymentStatus->addChild('EmploymentStatusMonitoring');
                    $EII->addChild('ESMType', 'EII');
                    $EII->addChild('ESMCode', 7);

                    $EII = $LearnerEmploymentStatus->addChild('EmploymentStatusMonitoring');
                    $EII->addChild('ESMType', 'OET');
                    $EII->addChild('ESMCode', 3);
                }   

                $tnp1 = DAO::getSingleValue($link, "SELECT EXTRACTVALUE(ilr, 'Learner/LearningDelivery[LearnAimRef=\'ZPROG001\']/TrailblazerApprenticeshipFinancialRecord[TBFinType=\'TNP\' and TBFinCode=\'1\']/TBFinAmount') FROM ilr WHERE ilr.tr_id = '{$tr->id}' ORDER BY submission DESC LIMIT 1"); 
                $tnp2 = DAO::getSingleValue($link, "SELECT EXTRACTVALUE(ilr, 'Learner/LearningDelivery[LearnAimRef=\'ZPROG001\']/TrailblazerApprenticeshipFinancialRecord[TBFinType=\'TNP\' and TBFinCode=\'2\']/TBFinAmount') FROM ilr WHERE ilr.tr_id = '{$tr->id}' ORDER BY submission DESC LIMIT 1"); 

                if(isset($choc_details->new_act) && $choc_details->new_act != '')
                {
                    foreach($ilr->LearningDelivery AS $LearningDelivery)
                    {
                        $LearningDelivery->DelLocPostCode = $new_employer_location->postcode;

                        if($LearningDelivery->LearnAimRef->__toString() == "ZPROG001")
                        {
                            $LearningDeliveryFAM = $LearningDelivery->addChild('LearningDeliveryFAM');    
                            $LearningDeliveryFAM->addChild('LearnDelFAMType', 'ACT');
                            $LearningDeliveryFAM->addChild('LearnDelFAMCode', $choc_details->new_act);
                            $LearningDeliveryFAM->addChild('LearnDelFAMDateFrom', $choc_details->new_start_date);
        
                            $TrailblazerApprenticeshipFinancialRecord = $LearningDelivery->addChild('TrailblazerApprenticeshipFinancialRecord');
                            $TrailblazerApprenticeshipFinancialRecord->addChild('TBFinType', 'TNP');
                            if($tnp1 != '')
                                $TrailblazerApprenticeshipFinancialRecord->addChild('TBFinCode', '3');
                            else
                                $TrailblazerApprenticeshipFinancialRecord->addChild('TBFinCode', '1');
                            $TrailblazerApprenticeshipFinancialRecord->addChild('TBFinDate', $choc_details->new_start_date);
                            $TrailblazerApprenticeshipFinancialRecord->addChild('TBFinAmount', $choc_details->tnp3);
        
                            $TrailblazerApprenticeshipFinancialRecord = $LearningDelivery->addChild('TrailblazerApprenticeshipFinancialRecord');
                            $TrailblazerApprenticeshipFinancialRecord->addChild('TBFinType', 'TNP');
                            if($tnp2 != '')
                                $TrailblazerApprenticeshipFinancialRecord->addChild('TBFinCode', '4');
                            else    
                                $TrailblazerApprenticeshipFinancialRecord->addChild('TBFinCode', '2');
                            $TrailblazerApprenticeshipFinancialRecord->addChild('TBFinDate', $choc_details->new_start_date);
                            $TrailblazerApprenticeshipFinancialRecord->addChild('TBFinAmount', $choc_details->tnp4);
                        }
                    }
                }
            }
            if($choc->choc_type == "Break in Learning")
            {
                $ilr = XML::loadSimpleXML( $ilr_row->ilr );
                foreach($ilr->LearningDelivery AS $LearningDelivery)
                {
                    if($LearningDelivery->CompStatus=="1")
                    {
                        $LearningDelivery->CompStatus = 6;
                        $LearningDelivery->Outcome = 3;
                        $LearningDelivery->LearnActEndDate = $choc_details->bil_last_date;
                    }
                }
            }
            

            $dom = new DOMDocument;
            $dom->preserveWhiteSpace = FALSE;
            @$dom->loadXML($ilr->saveXML());
            $dom->formatOutput = TRUE;
            $modified_ilr = $dom->saveXml();
            $modified_ilr = str_replace('<?xml version="1.0"?>', '', $modified_ilr);
            $ilr_row->ilr = $modified_ilr;

            DAO::saveObjectToTable($link, "ilr", $ilr_row);
        }
    }

    public function updateTrainingRecord(PDO $link, Choc $choc)
    {
        $choc_details = json_decode($choc->choc_details);
        $tr = TrainingRecord::loadFromDatabase($link, $choc->tr_id);
        if ($choc->choc_type == "Change of Employer")
        {
            if(isset($choc_details->new_employer) && isset($choc_details->new_employer_location))
            {
                $tr->employer_id = $choc_details->new_employer;
                $tr->employer_location_id = $choc_details->new_employer_location;
                $tr->crm_contact_id = $choc_details->new_employer_line_manager;
                $location = Location::loadFromDatabase($link, $choc_details->new_employer_location);
                $tr->work_address_line_1 = $location->address_line_1;
                $tr->work_address_line_2 = $location->address_line_2;
                $tr->work_address_line_3 = $location->address_line_3;
                $tr->work_address_line_4 = $location->address_line_4;
                $tr->work_postcode = $location->postcode;
            }
        }
        if($choc->choc_type == "Break in Learning")
        {
            $tr->status_code = 6;
            $tr->closure_date = $choc_details->bil_last_date;
        }
        $tr->save($link);
    }

    public function updateLearnerRecord(PDO $link, Choc $choc)
    {
        $choc_details = json_decode($choc->choc_details);
        $tr = TrainingRecord::loadFromDatabase($link, $choc->tr_id);
        $learner = User::loadFromDatabase($link, $tr->username);
        if(isset($choc_details->new_employer) && isset($choc_details->new_employer_location))
        {
            $learner->employer_id = $choc_details->new_employer;
            $learner->employer_location_id = $choc_details->new_employer_location;
            $location = Location::loadFromDatabase($link, $choc_details->new_employer_location);
            $learner->work_address_line_1 = $location->address_line_1;
            $learner->work_address_line_2 = $location->address_line_2;
            $learner->work_address_line_3 = $location->address_line_3;
            $learner->work_address_line_4 = $location->address_line_4;
            $learner->work_postcode = $location->postcode;
        }
        $learner->save($link);
    }
    
}