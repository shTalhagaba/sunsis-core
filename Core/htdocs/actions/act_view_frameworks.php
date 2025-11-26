<?php
class view_frameworks implements IAction
{
	public function execute(PDO $link)
	{
	
		$_SESSION['bc']->index = 0;
		$_SESSION['bc']->add($link, "do.php?_action=view_frameworks", "View Frameworks");
	
		$view = ViewFrameworks::getInstance();
		$view->refresh($link, $_REQUEST);
		
		require_once('tpl_view_frameworks.php');
	}
}
?>