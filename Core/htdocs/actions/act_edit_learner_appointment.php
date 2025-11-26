<?php
class edit_learner_appointment implements IAction
{
	public function execute(PDO $link)
	{
		// Validate data entry
		$appointment_id = isset($_REQUEST['appointment_id']) ? $_REQUEST['appointment_id'] : '';
		$tr_id = isset($_REQUEST['tr_id']) ? $_REQUEST['tr_id'] : '';

		if(isset($_REQUEST['ajax_request']) && $_REQUEST['ajax_request'])
		{
			if(isset($_REQUEST['appointment_id']))
				echo $this->deleteLearnerAppointment($link, $_REQUEST['appointment_id']);
			else
				echo 'Missing query string argument.';
			exit;
		}

		if($tr_id == '')
			throw new Exception('Missing Training Record ID.');

		$_SESSION['bc']->add($link, "do.php?_action=edit_learner_appointment&tr_id=" . $tr_id, "Add/Edit Learner Appointment");

		if($appointment_id == '')
		{
			// New record
			$vo = new Appointment();
			$vo->tr_id = $tr_id;
			$page_title = "Add Appointment Details";
			$sql = "SELECT id, description, null FROM lookup_appointment_status WHERE id IN (" . Appointment::BookedAppointment. ", " . appointment::RescheduledAppointment . ") ORDER BY description; ";
			$appointment_statuses = DAO::getResultSet($link, $sql);
			$vo->interviewer = $_SESSION['user']->id;
			$sql = "SELECT id, CONCAT(firstnames, ' ', surname), NULL FROM users WHERE type != 5 AND web_access = 1 ORDER BY firstnames; "; // reed asked for a change this means that anyone can book an appointment for him/herself and this should be preset and not editable.
			$assessors = DAO::getResultSet($link, $sql);
		}
		else
		{
			$vo = Appointment::loadFromDatabase($link, $appointment_id);
			$page_title = "Edit Appointment Details";
			$today_date = new Date(date('Y-m-d'));
			$appointment_date = new Date($vo->appointment_date);
			if($appointment_date->after($today_date))
				$sql = "SELECT id, description, null FROM lookup_appointment_status WHERE id IN (" . Appointment::BookedAppointment. ", " . appointment::RescheduledAppointment . ") ORDER BY description; ";
			else
				$sql = "SELECT id, description, null FROM lookup_appointment_status ORDER BY description; ";
			$appointment_statuses = DAO::getResultSet($link, $sql);
			$sql = "SELECT id, CONCAT(firstnames, ' ', surname), NULL FROM users WHERE id = " . $vo->interviewer . " ORDER BY firstnames; "; // reed asked for a change this means that anyone can book an appointment for him/herself and this should be preset and not editable.
			$assessors = DAO::getResultSet($link, $sql);
		}

		// Dropdown arrays
		$sql = "SELECT id, description, null FROM lookup_appointment_types ORDER BY description; ";
		$appointment_types = DAO::getResultSet($link, $sql);

		$sql = "SELECT id, description, null FROM lookup_appointment_paperwork ORDER BY description; ";
		$appointment_paperworks = DAO::getResultSet($link, $sql);

		/*$sql = "SELECT modules.id, title, legal_name FROM modules INNER JOIN organisations ON modules.`provider_id` = organisations.id ORDER BY title; ";
		$modules = DAO::getResultSet($link, $sql);*/

		// Cancel button URL
		$js_cancel = "window.location.replace('do.php?_action=read_training_record&appointment_tab=1&id=$tr_id');";

		include('tpl_edit_learner_appointment.php');
	}

	private function deleteLearnerAppointment(PDO $link, $appointment_id)
	{
		$result = DAO::execute($link, "DELETE FROM appointments WHERE appointments.id = " . $appointment_id);
		if($result > 0)
			return 'The record has been successfully deleted.';
		else
			return 'Operation failed.';
	}

}
?>