<?php
class edit_op_session_cancellation_entry implements IAction
{
    public function execute(PDO $link)
    {
        $subaction = isset($_REQUEST['subaction']) ? $_REQUEST['subaction'] : '';
        $session_cancellation_id = isset($_REQUEST['session_cancellation_id']) ? $_REQUEST['session_cancellation_id'] : '';
        $tracker_id = isset($_REQUEST['tracker_id']) ? $_REQUEST['tracker_id'] : '';

        $entry = DAO::getObject($link, "SELECT * FROM session_cancellations WHERE id = '{$session_cancellation_id}'");
        if(!isset($entry->id))
        {
            throw new Exception("Invalid querystring argument: session_cancellation_id");
        }

        if($subaction == 'save')
        {
            $this->saveEntry($link, $_REQUEST);
        }
	if($subaction == 'delete')
        {
            $this->deleteEntry($link, $_REQUEST);
        }

        $event_types = InductionHelper::getListEventTypes();
        $resched_categories = InductionHelper::getListReschedulingCategory();
        $cancellation_types_list = InductionHelper::getListReschedulingType();	

        $tr = TrainingRecord::loadFromDatabase($link, $entry->tr_id);
        $session = OperationsSession::loadFromDatabase($link, $entry->session_id);
        $session_event_type = isset($event_types[$session->event_type]) ? $event_types[$session->event_type] : $session->event_type;

        $_SESSION['bc']->add($link, "do.php?_action=edit_op_session_cancellation_entry&session_cancellation_id={$session_cancellation_id}&tr_id={$tr->id}&tracker_id={$tracker_id}", "Edit Learner Cancellation Entry");

        include('tpl_edit_op_session_cancellation_entry.php');
    }

    public function saveEntry(PDO $link)
    {
        $tracker_id = isset($_REQUEST['tracker_id']) ? $_REQUEST['tracker_id'] : '';
        $session_cancellation_id = isset($_REQUEST['session_cancellation_id']) ? $_REQUEST['session_cancellation_id'] : '';
        $entry = DAO::getObject($link, "SELECT * FROM session_cancellations WHERE id = '{$session_cancellation_id}'");
        $entry->category = $_REQUEST['category'];
        $entry->cancellation_type = $_REQUEST['cancellation_type'];

        DAO::saveObjectToTable($link, "session_cancellations", $entry);

        http_redirect("do.php?_action=view_edit_op_learner&tr_id={$entry->tr_id}&tracker_id={$tracker_id}");
    }

    public function deleteEntry(PDO $link)
    {
        if($_SESSION['user']->username != 'jcoates')
        {
            throw new Exception("You are not authorised to perform this action.");
        }

        $session_cancellation_id = isset($_REQUEST['session_cancellation_id']) ? $_REQUEST['session_cancellation_id'] : '';
        if($session_cancellation_id == '')
        {
            throw new Exception("Missing querystring argument.");
        }
        $tracker_id = isset($_REQUEST['tracker_id']) ? $_REQUEST['tracker_id'] : '';
        $tr_id = isset($_REQUEST['tr_id']) ? $_REQUEST['tr_id'] : '';

        DAO::execute($link, "DELETE FROM session_cancellations WHERE session_cancellations.id = '{$session_cancellation_id}'");

        http_redirect("do.php?_action=view_edit_op_learner&tr_id={$tr_id}&tracker_id={$tracker_id}");
    }
}