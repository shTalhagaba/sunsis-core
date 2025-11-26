<?php
class view_assessors implements IAction
{
	public function execute(PDO $link)
	{
		
		$_SESSION['bc']->index=0;
		$_SESSION['bc']->add($link, "do.php?_action=view_assessors", "View Assessors");
		
		$emp = $_SESSION['user']->employer_id;
		
		$view = ViewAssessors::getInstance($link);
		$view->refresh($link, $_REQUEST);
		
		require_once('tpl_view_assessors.php');
	}
}
?>