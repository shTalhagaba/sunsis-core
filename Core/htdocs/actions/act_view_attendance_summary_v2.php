<?php
class view_attendance_summary_v2 implements IAction
{
	public function execute(PDO $link)
	{

		$_SESSION['bc']->index = 0;
		$_SESSION['bc']->add($link, "do.php?_action=view_attendance_summary_v2", "View Attendance Summary");

		$view = ViewAttendanceSummaryV2::getInstance();
		$view->refresh($link, $_REQUEST);

		require_once('tpl_view_attendance_summary_v2.php');
	}
}
?>