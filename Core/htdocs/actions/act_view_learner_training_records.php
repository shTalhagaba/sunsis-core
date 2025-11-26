<?php
/**
 * Shared Action
 *
 */
class view_learner_training_records implements IAction
{
	public function execute(PDO $link)
	{

		$username = isset($_REQUEST['username'])?$_REQUEST['username']:'';
		
		$_SESSION['bc']->add($link, "do.php?_action=view_learner_training_records&username=" . "{'" . $username . "'}" , "View Learner TRs");
		
		$view = ViewLearnerTrainingRecords::getInstance($link, $username);
		$view->refresh($link, $_REQUEST);

		require_once('tpl_view_learner_training_records.php');
	}

}
?>