<?php
class save_baltic_caseload_management implements IAction
{
    public function execute(PDO $link)
    {
        $vo = new stdClass();
        $records = DAO::getSingleColumn($link, "SHOW COLUMNS FROM caseload_management");
        foreach($records AS $key => $value)
            $vo->$value = null;

        foreach($_POST AS $field => $value)
        {
            $vo->$field = $value;
        }

        foreach(["bil", "reinstated", "potential_return", "previous_leaver", "audited", "right_candidate", "right_employer", "right_support", "sales_lar", "bh_shortlist"] AS $tickbox)
        {
            $vo->$tickbox = isset($_POST[$tickbox]) ? $_POST[$tickbox] : 0;
        }

	$existing_record = null;
        if($vo->id == '' || is_null($vo->id))
        {
            $vo->created_by = $_SESSION['user']->id;
            $vo->created_at = date('Y-m-d H:i:s');
        }
	    else
        {
            $existing_record = DAO::getObject($link, "SELECT * FROM caseload_management WHERE id = '{$vo->id}'");
        }

        if(isset($existing_record->id))
        {
            $vo->created_by = $existing_record->created_by;
            $vo->created_at = $existing_record->created_at;
        }

        $vo->last_updated_by = $_SESSION['user']->id;
        $vo->updated_at = date('Y-m-d H:i:s');

        DAO::saveObjectToTable($link, "caseload_management", $vo);

	$this->createLog($link, $vo);

	// previous entry closed date
        $previous_entry = DAO::getObject($link, "SELECT * FROM caseload_management WHERE tr_id = '{$vo->tr_id}' AND id != '{$vo->id}' ORDER BY id DESC LIMIT 1;");
        if(
            isset($previous_entry->tr_id) &&
            $previous_entry->status != $vo->status && 
            is_null($previous_entry->closed_date) 
        )
        {
            $previous_entry->closed_date = date('Y-m-d');
            DAO::saveObjectToTable($link, "caseload_management", $previous_entry);
        }

        if(
            ( 
                is_null($existing_record) && 
                $vo->bil[0] == '1'
            ) || 
            (
                isset($existing_record->tr_id) && 
                ($existing_record->bil == 0 || is_null($existing_record->bil)) && 
                (!empty($vo->bil[0]) && isset($vo->bil[0][0]) && $vo->bil[0][0] == 1)
            )
        )
        {
	    //record the added to bil date
            $vo->added_to_bil_date = date('Y-m-d');
            DAO::saveObjectToTable($link, "caseload_management", $vo);

            $this->sendEmail($link, $vo->tr_id, 'bil');
        }

        if(
            ( 
                is_null($existing_record) && 
                in_array($vo->destination, ["Leaver", "Direct Leaver - No intervention"])
            ) || 
            (
                isset($existing_record->tr_id) && 
                !in_array($existing_record->destination, ["Leaver", "Direct Leaver - No intervention"]) &&
                in_array($vo->destination, ["Leaver", "Direct Leaver - No intervention"])       
            )
        )
        {
	    Induction::updateInduction($link, $vo->tr_id);
            $this->sendEmail($link, $vo->tr_id, 'leaver');

            $vo->added_to_leaver_date = date('Y-m-d');
            DAO::saveObjectToTable($link, "caseload_management", $vo);
        }

	if(
            ( 
                is_null($existing_record) && 
                (!empty($vo->change_of_employer) && isset($vo->change_of_employer[0]) && $vo->change_of_employer[0] == 1)
            ) || 
            (
                isset($existing_record->tr_id) && 
                ($existing_record->change_of_employer == 0 || is_null($existing_record->change_of_employer)) && 
                (!empty($vo->change_of_employer) && isset($vo->change_of_employer[0]) && $vo->change_of_employer[0] == 1)
            )
        )
        {
            $this->sendEmail($link, $vo->tr_id, 'change_of_employer');
        }

        http_redirect("do.php?_action=read_training_record&id={$vo->tr_id}&tabClm=1");
    }

    public function sendEmail(PDO $link, $tr_id, $message_type)
    {
        $tr = DAO::getObject($link, "SELECT id, firstnames, surname, assessor FROM tr WHERE tr.id = '{$tr_id}'");
        if(!isset($tr->id))
        {
            return;
        }

        if($message_type == "bil")
        {
            $html = <<<HEREDOC

<h2>Notification of BIL</h2>

<p>The following Learner has been raised as a BIL (Break in Learning).</p>

<p>{$tr->firstnames} {$tr->surname}</p>

HEREDOC;

            $to = SOURCE_HOME ? "inaam.azmat@perspective-uk.com" : "Admin@balticapprenticeships.com";
            if(Emailer::html_mail($to, "no-reply@perspective-uk.com", "BIL Notification", "", $html, [], array("Importance: high")))
            {
                $email_log = new stdClass();
                $email_log->entity_type = 'caseload_management';
                $email_log->entity_id = $tr_id;
                $email_log->email_to = "Admin@balticapprenticeships.com";
                $email_log->email_from = "no-reply@perspective-uk.com";
                $email_log->email_subject = "BIL Notification";
                $email_log->email_body = $html;
                $email_log->by_whom = $_SESSION['user']->id;
                $email_log->created = date('Y-m-d H:i:s');
                DAO::saveObjectToTable($link, "emails", $email_log);
            }
        }

	if($message_type == "change_of_employer")
        {
            $assessor_name = DAO::getSingleValue($link, "SELECT CONCAT(firstnames, ' ', surname) FROM users WHERE users.id = '{$tr->assessor}'");
            $html = <<<HEREDOC

<h2>Change Of Employer</h2>

<p>The following Learner has been ticked as a Change of Employer.</p>

<p>Learner: {$tr->firstnames} {$tr->surname}</p>
<p>Coach/Assessor: {$assessor_name}</p>

HEREDOC;

            $to = SOURCE_HOME ? "inaam.azmat@perspective-uk.com" : "on-boarding@balticapprenticeships.com";
            if(Emailer::html_mail($to, "no-reply@perspective-uk.com", "Change of Employer Notification", "", $html, [], array("Importance: high")))
            {
                $email_log = new stdClass();
                $email_log->entity_type = 'caseload_management';
                $email_log->entity_id = $tr_id;
                $email_log->email_to = "on-boarding@balticapprenticeships.com";
                $email_log->email_from = "no-reply@perspective-uk.com";
                $email_log->email_subject = "Change of Employer Notification";
                $email_log->email_body = $html;
                $email_log->by_whom = $_SESSION['user']->id;
                $email_log->created = date('Y-m-d H:i:s');
                DAO::saveObjectToTable($link, "emails", $email_log);
            }
        }
        
        if($message_type == "leaver")
        {
            $html = <<<HEREDOC

<h2>Notification of Leaver</h2>

<p>The following Learner has been raised as a leaver.</p>

<p>{$tr->firstnames} {$tr->surname}</p>

HEREDOC;

            $to = SOURCE_HOME ? "inaam.azmat@perspective-uk.com" : "Admin@balticapprenticeships.com";
            if(Emailer::html_mail($to, "no-reply@perspective-uk.com", "Leaver Notification", "", $html, [], array("Importance: high")))
            {
                $email_log = new stdClass();
                $email_log->entity_type = 'caseload_management';
                $email_log->entity_id = $tr_id;
                $email_log->email_to = "Admin@balticapprenticeships.com";
                $email_log->email_from = "no-reply@perspective-uk.com";
                $email_log->email_subject = "Leaver Notification";
                $email_log->email_body = $html;
                $email_log->by_whom = $_SESSION['user']->id;
                $email_log->created = date('Y-m-d H:i:s');
                DAO::saveObjectToTable($link, "emails", $email_log);
            }
        }

    }

    private function createLog(PDO $link, stdClass $vo)
    {
        $existingLogsCount = DAO::getSingleValue($link, "SELECT COUNT(*) FROM caseload_management_log WHERE caseload_id = '{$vo->id}'");
        $existingLogsCount++;
        $entryId = "{$vo->id}-{$existingLogsCount}";
        $log = new stdClass();
        foreach($vo AS $key => $value)
        {
            $log->$key = $value;
            $log->entry_id = $entryId;
            $log->caseload_id = $vo->id;

            $log->id = null;
            $log->created_at = date('Y-m-d H:i:s');
        }
        DAO::saveObjectToTable($link, "caseload_management_log", $log);
    }
}