<?php
class view_interviews_report implements IAction
{
	public function execute(PDO $link)
	{
		$_SESSION['bc']->index = 0;
		$_SESSION['bc']->add($link, "do.php?_action=view_interviews_report", "View Interviews Report");

		$view = ViewInterviewsReport::getInstance($link);
		$view->refresh($link, $_REQUEST);

		require_once('tpl_view_interviews_report.php');
	}


}
?>