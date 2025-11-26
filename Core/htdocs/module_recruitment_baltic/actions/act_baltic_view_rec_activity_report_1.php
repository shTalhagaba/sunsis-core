<?php
class baltic_view_rec_activity_report_1 implements IAction
{
	public function execute(PDO $link)
	{
		$_SESSION['bc']->index = 0;
		$_SESSION['bc']->add($link, "do.php?_action=baltic_view_rec_activity_report_1", "View Recruitment Activity Report From Prospect");

		$view = ViewRecActivityReport1::getInstance($link);
		$view->refresh($link, $_REQUEST);

		require_once('tpl_baltic_view_rec_activity_report_1.php');
	}
}