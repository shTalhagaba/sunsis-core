<?php
class delete_training implements IAction
{
	public function execute(PDO $link)
	{
		
		$course_id = isset($_REQUEST['id'])?$_REQUEST['id']:'';
		
		$_SESSION['bc']->add($link, "do.php?_action=delete_training&id=" . $course_id, "Remove Learners");
		
		$c_vo = Course::loadFromDatabase($link, $course_id);		
		$dao = new OrganisationDAO($link);
		$o_vo = $dao->find($link, (integer) $c_vo->organisations_id); /* @var $o_vo OrganisationVO */
		
		$view = DeleteTraining::getInstance($link, $course_id);
		$view->refresh($link, $_REQUEST);
		
		require_once('tpl_delete_training.php');
	}
}
?>