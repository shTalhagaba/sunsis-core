<?php
class dealers_report implements IAction
{
	public function execute(PDO $link)
	{
	
		$_SESSION['bc']->add($link, "do.php?_action=dealers_report", "Dealers Report");

		$view = DealersReport::getInstance();
		$view->refresh($link, $_REQUEST);

		require_once('tpl_dealers_report.php');
	}
}
?>