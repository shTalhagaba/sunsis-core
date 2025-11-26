<?php
class delete_attendance_module_lessons implements IAction
{
	public function execute(PDO $link)
	{
		// Validate arguments
		$lessons = isset($_REQUEST['lessons']) ? $_REQUEST['lessons'] : null;
		$module_id = isset($_REQUEST['module_id'])?$_REQUEST['module_id']: null;

		if(is_null($lessons))
		{
			throw new Exception("You must specify one or more lesson ids");
		}

		$l_dao = new LessonDAO($link);

		// Check that lessons with register entries are not being erased
		if(!$l_dao->isSafeToDelete($lessons))
		{
			throw new Exception("One or more of the specified lesson(s) has register entries");
		}


		// Before deleting lessons, find out which course they come from
		// (Assume they are all of the same course)
		$l_vo = $l_dao->find($lessons[0]);
		$lesson_id = $lessons[0];

		$g_dao = new AttendanceModuleGroupDAO($link);
		$g_vo = $g_dao->find($l_vo->groups_id);

		// Delete lessons one by one
		DAO::transaction_start($link);
		try
		{
			foreach($lessons as $id)
			{
				$l_dao->delete($id); /* @var $lesson LessonVO */
			}
		}
		catch (Exception $e)
		{
			DAO::transaction_rollback($link, $e);
			throw new WrappedException($e);
		}
		DAO::transaction_commit($link);


		// Need to update module record with the new number of lessons
		$attendance_module = AttendanceModule::loadFromDatabase($link, $module_id);
		$attendance_module->updateAttendanceStatistics($link);


		// Presentation
		http_redirect('do.php?_action=view_attendance_module_lessons&module_id=' . $module_id);
	}

}
?>