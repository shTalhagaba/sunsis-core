<?php
class update_course_learners implements IAction
{
	public function execute(PDO $link)
	{
		
		$course_id = isset($_REQUEST['course_id'])?$_REQUEST['course_id']:'';
		
		$_SESSION['bc']->add($link, "do.php?_action=update_course_learners&id=" . $course_id, "Update Learners");
		
		$c_vo = Course::loadFromDatabase($link, $course_id);		
		
		$dao = new OrganisationDAO($link);
		$o_vo = $dao->find($link, (integer) $c_vo->organisations_id); /* @var $o_vo OrganisationVO */
		
		$framework_id = $c_vo->framework_id;

		require_once('tpl_update_course_learners.php');
	}
}
?>