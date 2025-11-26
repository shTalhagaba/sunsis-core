<?php
class read_feedback implements IAction
{
	public function execute(PDO $link)
	{
        $id = isset($_REQUEST['id']) ? $_REQUEST['id'] : '';
        if($id == '')
        {
            throw new Exception("Missing querystring argument: id");
        }

        $feedback = DAO::getObject($link, "SELECT * FROM learner_feedbacks WHERE id = '{$id}'");
        if( is_null($feedback) )
        {
            throw new Exception("Invalid id");
        }

        $_SESSION['bc']->add($link, "do.php?_action=read_feedback&id=" . $feedback->id, "View Feedback");

        $training = DAO::getObject($link, "SELECT * FROM training WHERE id = '{$feedback->training_id}'");
        $schedule = DAO::getObject($link, "SELECT * FROM crm_training_schedule WHERE crm_training_schedule.id = '{$training->schedule_id}'");

        include('tpl_read_feedback.php');
    }
}