<?php
class view_group_qualifications implements IAction
{
	public function execute(PDO $link)
	{

		$group_id = isset($_GET['id']) ? $_GET['id'] : '';
		$course_id = isset($_GET['course_id']) ? $_GET['course_id'] : '';
		$batch = isset($_GET['batch']) ? $_GET['batch'] : '';
		
		$_SESSION['bc']->add($link, "do.php?_action=view_group_qualifications&id=" . $group_id . "&batch=" . $batch . "&course_id=" . $course_id, "Select Qualification for matrix");
		
		$que = "select DISTINCT framework_id from courses where id='$course_id'";
		$fid = DAO::getSingleValue($link, $que);
		
		$view = ViewGroupQualifications::getInstance($group_id);
		$view->refresh($link, $_REQUEST);
		
		require_once('tpl_view_group_qualifications.php');
	}
}
?>