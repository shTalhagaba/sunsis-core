<?php
class view_appointments_report implements IAction
{
	public function execute(PDO $link)
	{
		$_SESSION['bc']->index = 0;
		$_SESSION['bc']->add($link, "do.php?_action=view_appointments_report", "View Appointments Report");

		$view = ViewAppointmentsReport::getInstance($link);
		$view->refresh($link, $_REQUEST);

		require_once('tpl_view_appointments_report.php');
	}


}
?>