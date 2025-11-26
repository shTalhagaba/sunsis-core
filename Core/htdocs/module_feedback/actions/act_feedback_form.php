<?php
class feedback_form implements IUnauthenticatedAction
{
	public function execute(PDO $link)
	{
		$key = isset($_REQUEST['key']) ? $_REQUEST['key'] : '';
		if($key == '')
		{
			http_redirect('do.php?_action=feedback_error');
		}

		$training = DAO::getObject($link, "SELECT * FROM training WHERE MD5(CONCAT(id, '_feedback_form')) = '{$key}'");
		if(! isset($training->id))
		{
			http_redirect('do.php?_action=feedback_error');
		}

		$schedule = DAO::getObject($link, "SELECT * FROM crm_training_schedule WHERE id = '{$training->schedule_id}'");
		$levels = [
			'L1' => 'Level 1',
			'L2' => 'Level 2',
			'L3' => 'Level 3',
			'L4' => 'Level 4',
		];
		$level = isset($levels[$schedule->level]) ? $levels[$schedule->level] : '';

		$isCompleted = DAO::getSingleValue($link, "SELECT is_completed FROM learner_feedbacks WHERE training_id = '{$training->id}'");
		if($isCompleted == 1)
		{
			http_redirect('do.php?_action=feedback_already_completed');
		}

        $headerImage1 = SystemConfig::getEntityValue($link, 'ob_header_image1');
        $clientName = SystemConfig::getEntityValue($link, 'client_name');

		include_once('tpl_feedback_form.php');
	}
}