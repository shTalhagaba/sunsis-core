<?php
class view_attendance_v2_report implements IAction
{
	public function execute(PDO $link)
	{
		$_SESSION['bc']->index = 0;
		$_SESSION['bc']->add($link, "do.php?_action=view_attendance_v2_report", "View Attendance Report");

		$view = ViewAttendanceV2Report::getInstance($link);
		$view->refresh($link, $_REQUEST);

		require_once('tpl_view_attendance_v2_report.php');
	}


}
?>