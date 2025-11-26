<?php
class view_courses2 implements IAction
{
	public function execute(PDO $link)
	{
	
		$_SESSION['bc']->index = 0;
		$_SESSION['bc']->add($link, "do.php?_action=view_courses2", "View Courses");
	
	
		$view = ViewCourses::getInstance($link);
		$view->refresh($link, $_REQUEST);
		
		require_once('tpl_view_courses2.php');
	}
}
?>