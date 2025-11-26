<?php
class save_feedback_form implements IUnauthenticatedAction
{
	public function execute(PDO $link)
	{
        $key = isset($_REQUEST['key']) ? $_REQUEST['key'] : '';
		if($key == '')
		{
			http_redirect('do.php?_action=bc_error_page');
		}

		$training = DAO::getObject($link, "SELECT * FROM training WHERE MD5(CONCAT(id, '_feedback_form')) = '{$key}'");
		if(! isset($training->id))
		{
			http_redirect('do.php?_action=bc_error_page');
		}


        $feedback = new stdClass();
        $records = DAO::getSingleColumn($link, "SHOW COLUMNS FROM learner_feedbacks");
        foreach($records AS $key => $value)
        {
            $feedback->$value = isset($_POST[$value]) ? $_POST[$value] : null;
        }

        $feedback->training_id = $training->id;
        $feedback->learner_id = $training->learner_id;
        $feedback->learner_name = DAO::getSingleValue($link, "SELECT CONCAT(firstnames, ' ', surname) FROM users WHERE users.id = '{$training->learner_id}'");
        $feedback->is_completed = 1;

        try
        {
            DAO::saveObjectToTable($link, "learner_feedbacks", $feedback);
        }
        catch(Exception $ex)
        {
            http_redirect('do.php?_action=feedback_error');
        }

        http_redirect('do.php?_action=feedback_thanks');
	}
}