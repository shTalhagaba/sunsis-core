<?php
class view_learnergroups implements IAction
{
	public function execute(PDO $link)
	{
		$view = ViewLearnerGroups::getInstance();
		$view->refresh($link, $_REQUEST);
		
		require_once('tpl_view_learnergroups.php');
	}
}
?>