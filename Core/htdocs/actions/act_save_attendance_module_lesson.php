<?php
class save_attendance_module_lesson implements IAction
{
	public function execute(PDO $link)
	{
		// Populate Value Object from user's <form> submission
		$vo = new LessonVO();
		$vo->populate($_POST);

		// Get attendance module ID
		$g_dao = new AttendanceModuleGroupDAO($link);
		$g_vo = $g_dao->find($vo->groups_id); /* @var $g_vo AttendanceModuleGroupVO */

		// Create DAO
		$l_dao = new LessonDAO($link);

		// Compare with existing record
		$existing_record = $l_dao->find($vo->id);
		if($existing_record->groups_id != $vo->groups_id)
		{
			// Delete register entries for the lesson

			// Update all statistics
		}

		$l_dao->update($vo);

		// Presentation
		if(IS_AJAX)
		{
			header('Content-Type: text/plain; charset=ISO-8859-1');
			echo $vo->id;
		}
		else
		{
			http_redirect('do.php?_action=view_attendance_module_lessons&module_id=' . $g_vo->module_id . '&group_id=' . $_REQUEST['groups_id']);
		}
	}
}
?>