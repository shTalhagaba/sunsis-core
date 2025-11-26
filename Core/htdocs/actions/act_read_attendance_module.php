<?php
class read_attendance_module implements IAction
{
	public function execute(PDO $link)
	{
		// Validate data entry
		$id = isset($_GET['id']) ? $_GET['id'] : '';

		$_SESSION['bc']->add($link, "do.php?_action=read_attendance_module&id=" . $id, "View Attendance Module");

		if($id == '' || !is_numeric($id))
		{
            http_redirect('do.php?_action=view_modules');
		}

		$m_vo = AttendanceModule::loadFromDatabase($link, $id);

		// Presentation
		include('tpl_read_attendance_module.php');
	}

	private function renderAttendance(PDO $link, $attendance_module)
	{
		echo '<table class="resultset" border="0" cellspacing="0" cellpadding="6" style="margin-left:10px">';
		echo '<tr>';
		AttendanceHelper::echoHeaderCells(true);
		echo '</tr>';
		echo '<tr>';
		AttendanceHelper::echoDataCells($attendance_module);
		echo '</tr>';
		echo '</table>';
	}
}
?>