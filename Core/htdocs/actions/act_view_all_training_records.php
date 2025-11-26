<?php
class view_all_training_records implements IAction
{
	public function execute(PDO $link)
	{

		$_SESSION['bc']->index = 0;
		$_SESSION['bc']->add($link, "do.php?_action=view_all_training_records", "Search Training Records");
		
		$view = ViewAllTrainingRecords::getInstance($link);
		$view->refresh($link, $_REQUEST);
		
		require_once('tpl_view_all_training_records.php');
	}
}
?>