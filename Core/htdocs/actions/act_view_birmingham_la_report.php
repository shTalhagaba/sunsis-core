<?php
class view_birmingham_la_report implements IAction
{
	public function execute(PDO $link)
	{

		$_SESSION['bc']->index=0;
		$_SESSION['bc']->add($link, "do.php?_action=view_birmingham_la_report", "View Birmingham LA Report");

		$view = ViewBirminghamLAReport::getInstance();
		$view->refresh($link, $_REQUEST);

		require_once('tpl_view_birmingham_la_report.php');
	}
}
?>