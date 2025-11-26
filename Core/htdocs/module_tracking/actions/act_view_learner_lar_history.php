<?php
class view_learner_lar_history implements IAction
{
    public function execute(PDO $link)
    {
        $tr_id = isset($_REQUEST['tr_id'])?$_REQUEST['tr_id']:'';
	$tracker_id = isset($_REQUEST['tracker_id'])?$_REQUEST['tracker_id']:'';
        $subaction = isset($_REQUEST['subaction'])?$_REQUEST['subaction']:'';

        if($tr_id == '')
        {
            throw new Exception('Missing querystring argument: tr_id');
        }
        
        $sql = <<<SQL
SELECT 
	EXTRACTVALUE(tr_operations.`lar_details`, '/Notes/Note[1]/DateTime') AS from_date,
	EXTRACTVALUE(tr_operations.`lar_details`, '/Notes/Note[last()]/DateTime') AS to_date,
	tr_operations.lar_details 
FROM tr_operations WHERE tr_id = '{$tr_id}';

SQL;
        $notes = DAO::getObject($link, $sql);

        if($subaction == 'export_csv')
        {
            $this->exportToCsv($link, $notes);
            exit;
        }

        $_SESSION['bc']->add($link, "do.php?_action=view_learner_lar_history", "View Learner LAR History");

        include_once('tpl_view_learner_lar_history.php');
    }

    public function exportToCsv(PDO $link, $notes)
    {
        header("Content-Type: application/vnd.ms-excel");
        header('Content-Disposition: attachment; filename=LearnerLarHistory.csv');
        if(strpos($_SERVER['HTTP_USER_AGENT'], 'MSIE') !== false)
        {
            header('Pragma: public');
            header('Cache-Control: max-age=0');
        }
        echo "Creation Date Time,Created By,Type,LAR Date,Notes,Primary Reason,Secondary Reason,Retention Category,Retention Category Other,RAG,Revisit Date,Owner,At Risk Of,Leaver Decision Made,No Contact,Actively Involved,Summary,Communication,Contact History,Next Action Summary";
        echo "\n";
        $ragDDL = InductionHelper::getListLARRAGRating();
        $reasonDDL = InductionHelper::getListLARReason();
        $risks = InductionHelper::getListLarRiskOf();
        $retentions = InductionHelper::getListRetentionCategories();
        $owners = InductionHelper::getListOpOwners();
        $types = InductionHelper::getListLAR();
        $notes = XML::loadSimpleXML($notes->lar_details);
        foreach($notes->Note AS $note)
        {
            echo Date::to($note->DateTime, Date::DATETIME) . ',';
            echo HTML::csvSafe(DAO::getSingleValue($link, "SELECT CONCAT(firstnames, ' ', surname) FROM users WHERE users.id = '{$note->CreatedBy}'")) . ',';
            echo isset($types[$note->Type->__toString()])?$types[$note->Type->__toString()] . ',':',';
            echo Date::toShort($note->Date->__toString()) . ',';
            echo HTML::csvSafe(html_entity_decode($note->Note)) . ',';
            if(isset($note->Reason))
                echo isset($reasonDDL[$note->Reason->__toString()])?$reasonDDL[$note->Reason->__toString()].',':',';
            else
                echo ',';
            if(isset($note->SecondReason))
                echo isset($reasonDDL[$note->SecondReason->__toString()])?$reasonDDL[$note->SecondReason->__toString()].',':',';
            else
                echo ',';
            echo isset($retentions[$note->Retention->__toString()])?$retentions[$note->Retention->__toString()].',':',';
            echo isset($note->RetentionOther) ? HTML::csvSafe($note->RetentionOther->__toString()) . ',' : ',';
            echo isset($ragDDL[$note->RAG->__toString()])?$ragDDL[$note->RAG->__toString()].',':',';
            echo isset($note->NextActionDate) ? Date::toShort($note->NextActionDate->__toString()) . ',' : ',';
            echo isset($owners[$note->Owner->__toString()])?$owners[$note->Owner->__toString()].',':',';
            echo isset($risks[$note->RiskOf->__toString()])?$risks[$note->RiskOf->__toString()].',':',';
            echo isset($note->LeaverDecision) ? Date::toShort($note->LeaverDecision->__toString()) . ',' : ',';
	    echo (isset($note->NoContact) && $note->NoContact->__toString() == '1') ? 'Yes,' : ',';
            if(isset($note->ActivelyInvolved) && $note->ActivelyInvolved != '')
            {
                $ActivelyInvolvedUsers = explode(",", $note->ActivelyInvolved);
                if(count($ActivelyInvolvedUsers) > 0)
                {
                    foreach($ActivelyInvolvedUsers AS $_user_id)
                    {
                        echo HTML::csvSafe(DAO::getSingleValue($link, "SELECT CONCAT(firstnames, ' ', surname) FROM users WHERE users.id = '{$_user_id}'")) . '; ';
                    }
                }
                echo ',';
            }
            else
            {
                echo ',';
            }	
            echo isset($note->Summary) ? HTML::csvSafe(html_entity_decode($note->Summary->__toString())) . ',' : ',';
            echo isset($note->Communication) ? HTML::csvSafe(html_entity_decode($note->Communication->__toString())) . ',' : ',';
            echo isset($note->ContactHistory) ? HTML::csvSafe(html_entity_decode($note->ContactHistory->__toString())) . ',' : ',';
            echo isset($note->NextActionHistory) ? HTML::csvSafe(html_entity_decode($note->NextActionHistory->__toString())) . ',' : ',';
            
            
            
            
            echo "\n";
        }
    }
}