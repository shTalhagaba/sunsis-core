<?php
class view_awarding_bodies implements IAction
{
	public function execute(PDO $link)
	{

		$_SESSION['bc']->index=0;	
		$_SESSION['bc']->add($link, "do.php?_action=view_awarding_bodies" , "View Awarding Bodies");
	
		$view = ViewAwardingBodies::getInstance();
		$view->refresh($link, $_REQUEST);

		require_once('tpl_view_awarding_bodies.php');
	}
}
?>