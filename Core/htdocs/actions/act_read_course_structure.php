<?php
class read_course_structure implements IAction
{
	public function execute(PDO $link)
	{
		$course_id = isset($_REQUEST['courses_id'])?$_REQUEST['courses_id']:'';
		
		$_SESSION['bc']->add($link, "do.php?_action=read_course_structure&courses_id=" . $course_id, "Course Qualifications");
		
		$course = Course::loadFromDatabase($link, $course_id);
		$c_vo = Course::loadFromDatabase($link, $course_id);
		
		$dao = new OrganisationDAO($link);
		$o_vo = $dao->find($link, (integer) $c_vo->organisations_id); /* @var $o_vo OrganisationVO */
		
		$view = GetCourseStructure::getInstance($course_id);
		$view->refresh($link, $_REQUEST);
		
		require_once('tpl_read_course_structure.php');
	}
}
?>