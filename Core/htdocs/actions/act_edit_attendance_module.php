<?php
class edit_attendance_module implements IAction
{
	public function execute(PDO $link)
	{
		// Validate data entry
		$attendance_module_id = isset($_REQUEST['id']) ? $_REQUEST['id'] : '';

		$_SESSION['bc']->add($link, "do.php?_action=edit_attendance_module&attendance_module_id=" . $attendance_module_id, "Add/Edit Attendance Module");

		if($attendance_module_id == '')
		{
			// New record
			$vo = new AttendanceModule();
			$page_title = "Add Attendance Module";
			$qualifications_sql = "SELECT REPLACE(id, '/', '') AS id, CONCAT(id, ' - ', internaltitle) FROM qualifications WHERE qualifications.active = 1";
		}
		else
		{
			$vo = AttendanceModule::loadFromDatabase($link, $attendance_module_id);
			$page_title = "Edit Attendance Module";
			$qualifications_sql = "SELECT REPLACE(id, '/', '') AS id, CONCAT(id, ' - ', internaltitle) FROM qualifications WHERE qualifications.active = 1 OR REPLACE(id, '/', '') = 'Z0001926'";
		}

		$qualifications_list = DAO::getResultSet($link, $qualifications_sql);

		$sql = "SELECT id, legal_name FROM organisations WHERE organisation_type = " . Organisation::TYPE_TRAINING_PROVIDER . " ORDER BY legal_name; ";
		$providers_list = DAO::getResultSet($link, $sql);

		// Cancel button URL
		$js_cancel = "window.location.replace('do.php?_action=read_attendance_module&id=$attendance_module_id');";

		include('tpl_edit_attendance_module.php');
	}

}
?>