<?php
class delete_course_lessons implements IAction
{
	public function execute(PDO $link)
	{
		// Validate arguments
		$lessons = isset($_REQUEST['lessons']) ? $_REQUEST['lessons'] : null;
		$course_id = isset($_REQUEST['course_id'])?$_REQUEST['course_id']: null;
		if(DB_NAME=="am_reed_demo" || DB_NAME=="am_demo")
			$group_id = isset($_REQUEST['course_id'])?$_REQUEST['group_id']: null;
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
//		$course_id = DAO::getSingleValue($link, "SELECT groups.courses_id FROM lessons LEFT JOIN groups ON groups.id = lessons.groups_id WHERE lessons.id = '$lesson_id';");

		$g_dao = new CourseGroupDAO($link);
		$g_vo = $g_dao->find($l_vo->groups_id);
		
		// Delete lessons one by one
//		DAO::transaction_start($link);
		try
		{
			foreach($lessons as $id)
			{
				$l_dao->delete($id); /* @var $lesson LessonVO */
			}	
		}
		catch (Exception $e)
		{
//			DAO::transaction_rollback($link, $e);
			throw new WrappedException($e);
		}
//		DAO::transaction_commit($link);


		// Need to update course record with the new number of lessons
		//$grp_vo = $grp_dao->find($vo->groups_id);
		$course = Course::loadFromDatabase($link, $course_id);
		$course->updateAttendanceStatistics($link);
		
		
		// Presentation
		if(DB_NAME=="am_reed_demo" || DB_NAME=="am_reed")
			http_redirect('do.php?_action=view_course_lessons&course_id=' . $course_id . '&group_id='. $group_id);
		else
			http_redirect('do.php?_action=view_course_lessons&course_id=' . $course_id);
	}
	
}
?>