<?php
class view_attendance_summary2 implements IAction
{
	public function execute(PDO $link)
	{
	
		$_SESSION['bc']->index = 0;
		$_SESSION['bc']->add($link, "do.php?_action=view_attendance_summary2", "View Attendance Summary");
	
		$view = ViewAttendanceSummary::getInstance();
		$view->refresh($link, $_REQUEST);
		
		require_once('tpl_view_attendance_summary2.php');
	}
}
?>