<?php
class view_training_records_v2 implements IAction
{
	public function execute(PDO $link)
	{
		$subaction = isset($_REQUEST['subaction'])?$_REQUEST['subaction']:null;

		$_SESSION['bc']->index = 0;
		$_SESSION['bc']->add($link, "do.php?_action=view_training_records_v2", "View Training Records");

		$view = ViewTrainingRecordsV2::getInstance($link); /* @var $view View */
		$view->refresh($link, $_REQUEST);

		include_once('tpl_view_training_records_v2.php');
	}
}