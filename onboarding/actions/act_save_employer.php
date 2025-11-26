<?php
class save_employer implements IAction
{
    public function execute(PDO $link)
    {
        $employer = new Employer();
        $employer->populate($_POST);

        $loc = new Location();
        $loc->populate($_POST);
        $loc->id = $_POST['main_location_id'];

        if($_POST['id'] != '')
        {
            $existing_record = Employer::loadFromDatabase($link, $_POST['id']);
            $log_string = $existing_record->buildAuditLogString($link, $employer);
            if($log_string != '')
            {
                $note = new Note();
                $note->subject = "Employer record edited";
                $note->note = $log_string;
            }
        }
        else
        {
            $note = new Note();
            $note->subject = "New employer created";
            $note->note = json_encode($_POST);
        }

        DAO::transaction_start($link);
        try
        {
            $employer->save($link);

            $loc->organisations_id = $employer->id;
            $loc->is_legal_address = 1;
            $loc->save($link);

            if(isset($note) && !is_null($note))
            {
                $note->is_audit_note = true;
                $note->parent_table = 'organisations';
                $note->parent_id = $employer->id;
                $note->created = date('Y-m-d H:i:s');
                $note->save($link);
            }

            DAO::transaction_commit($link);
        }
        catch(Exception $e)
        {
            DAO::transaction_rollback($link, $e);
            throw new WrappedException($e);
        }

        http_redirect("do.php?_action=read_employer&id={$employer->id}");
    }

}


?>