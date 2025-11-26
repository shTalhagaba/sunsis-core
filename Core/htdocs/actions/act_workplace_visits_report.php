<?php
class workplace_visits_report implements IAction
{
	public function execute(PDO $link)
	{
		$_SESSION['bc']->add($link, "do.php?_action=workplace_visits_report", "Workplace Visits Report");
		$view = WorkplaceVisitsReport::getInstance();
		$view->refresh($link, $_REQUEST);
		require_once('tpl_workplace_visits_report.php');
	}
}
?>