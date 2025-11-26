<?php
class save_lesson implements IAction
{
	public function execute(PDO $link)
	{
		// Populate Value Object from user's <form> submission
		$vo = new LessonVO();
		$vo->populate($_POST);
	
		// Get course ID
		$g_dao = new CourseGroupDAO($link);
		$g_vo = $g_dao->find($vo->groups_id); /* @var $g_vo CourseGroupVO */
		
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
			http_redirect('do.php?_action=view_course_lessons&course_id=' . $g_vo->courses_id . '&group_id=' . $_POST['group_id']);
		}
	}
}
?>