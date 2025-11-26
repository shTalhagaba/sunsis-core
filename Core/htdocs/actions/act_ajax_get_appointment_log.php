<?php
class ajax_get_appointment_log implements IAction
{
	public function execute(PDO $link)
	{
		$appointment_id = isset($_REQUEST['appointment_id'])?$_REQUEST['appointment_id']: '';
		if($appointment_id == '')
			throw new Exception("No Appointment ID provided");

		echo Note::renderNotes($link, "appointments", $appointment_id);


	}
}