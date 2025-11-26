<?php
class edit_attendance_module_lesson implements IAction
{
	public function execute(PDO $link)
	{
		// Validate data entry
		$id = isset($_REQUEST['id']) ? $_REQUEST['id'] : '';

		$dao = new LessonDAO($link);
		$l_vo = $dao->find($id); /* @var $l_vo LessonVO */
		$is_safe_to_delete = $dao->isSafeToDelete($id);

		$dao = new AttendanceModuleGroupDAO($link);
		$g_vo = $dao->find($l_vo->groups_id); /* @var $g_vo CourseGroupVO */

		$attendance_module = AttendanceModule::loadFromDatabase($link, $g_vo->module_id);

		$locations = DAO::getResultset($link, "SELECT id, full_name, null FROM locations WHERE organisations_id=" . $attendance_module->provider_id . " ORDER BY is_legal_address DESC;");

		$groups = DAO::getResultset($link, 'SELECT id, title, null FROM attendance_module_groups WHERE module_id=' . $attendance_module->id);

		$provider = Organisation::loadFromDatabase($link, $attendance_module->provider_id);

		$ddlHours = [];
		for($i = 0; $i < 24; $i++)
			$ddlHours[] = $i <= 9 ? ["0{$i}", $i] : [$i, $i];
		$ddlMinutes = [];
		for($i = 0; $i <= 60; $i++)
			$ddlMinutes[] = $i <= 9 ? ["0{$i}", $i] : [$i, $i];

		$otj_types = DAO::getResultSet($link, "SELECT id, description, null FROM lookup_otj_types ORDER BY description; ");

		include "templates/tpl_edit_attendance_module_lesson.php";
	}

}
?>