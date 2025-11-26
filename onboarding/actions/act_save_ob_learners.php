<?php
class save_ob_learners implements IAction
{
	public function execute(PDO $link)
	{
        $ob_learner = new OnboardingLearner();
        $ob_learner->populate($_POST);
        
	if(DB_NAME == "am_ela")
        {
            $ob_learner->caseload_org_id = isset($_REQUEST['caseload_org_id']) ? $_REQUEST['caseload_org_id'] : $_SESSION['user']->learners_caseload;
        }

        if($_POST['id'] != '')
        {
            $existing_record = OnboardingLearner::loadFromDatabase($link, $_POST['id']);
            $log_string = $existing_record->buildAuditLogString($link, $ob_learner);
            if($log_string != '')
            {
                $note = new Note();
                $note->subject = "Learner record edited";
                $note->note = $log_string;
            }
        }
        else
        {
            $note = new Note();
            $note->subject = "Learner added";
            $note->note = json_encode($_POST);
        }
	    DAO::transaction_start($link);
        try
        {
            $ob_learner->save($link);

            if(isset($note) && !is_null($note))
            {
                $note->is_audit_note = true;
                $note->parent_table = 'ob_learners';
                $note->parent_id = $ob_learner->id;
                $note->created = date('Y-m-d H:i:s');
                $note->save($link);
            }

            DAO::transaction_commit($link);
        }
        catch(Exception $ex)
        {
            DAO::transaction_rollback($link);
            throw new Exception($ex->getMessage());
        }

        http_redirect("do.php?_action=view_ob_learner&id={$ob_learner->id}");
	}
}