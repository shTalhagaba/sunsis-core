<?php
class view_workplaces implements IAction
{
	public function execute(PDO $link)
	{
	
		$_SESSION['bc']->index = 0;
		$_SESSION['bc']->add($link, "do.php?_action=view_workplaces", "View Experience");

		$view = ViewWorkPlaces::getInstance();
		$view->refresh($link, $_REQUEST);

		require_once('tpl_view_workplaces.php');
	}
}
?>