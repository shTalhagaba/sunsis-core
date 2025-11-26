<?php
class view_learner_crm implements IAction
{
	public function execute(PDO $link)
	{
	
		$_SESSION['bc']->index = 0;
		$_SESSION['bc']->add($link, "do.php?_action=view_learner_crm", "View Learner Notes");

		$view = ViewLearnerCRM::getInstance();
		$view->refresh($link, $_REQUEST);

		require_once('tpl_view_learner_crm.php');
	}
}
?>