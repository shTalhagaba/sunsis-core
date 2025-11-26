<?php
class view_all_users implements IAction
{
	public function execute(PDO $link)
	{
		$_SESSION['bc']->index = 0;
		$_SESSION['bc']->add($link, "do.php?_action=view_all_users", "Search User Records");

		$view = ViewAllUsers::getInstance($link);
		$view->refresh($link, $_REQUEST);
		
		require_once('tpl_view_all_users.php');
	}
}
?>