<?php
class view_training_records implements IAction
{
	public function execute(PDO $link)
	{
		$_SESSION['bc']->index = 0;
		$_SESSION['bc']->add($link, "do.php?_action=view_training_records", "View Training Records");

		$view = ViewTrainingRecords::getInstance($link);
		$view->refresh($link, $_REQUEST);

		require_once('tpl_view_training_records.php');
	}
}
?>