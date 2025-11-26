<?php
class view_attendance_v2_ad_hoc_registers_report implements IAction
{
	public function execute(PDO $link)
	{
		$_SESSION['bc']->index = 0;
		$_SESSION['bc']->add($link, "do.php?_action=view_attendance_v2_ad_hoc_registers_report", "View Attendance Report");

		$view = ViewAttendanceV2AdHocRegistersReport::getInstance($link);
		$view->refresh($link, $_REQUEST);

		require_once('tpl_view_attendance_v2_ad_hoc_registers_report.php');
	}


}
?>