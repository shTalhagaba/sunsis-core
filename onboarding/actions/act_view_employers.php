<?php
class view_employers implements IAction
{
	public function execute(PDO $link)
	{
		$_SESSION['bc']->index = 0;
		$_SESSION['bc']->add($link, "do.php?_action=view_employers", "View Employers");

		$view = ViewEmployers::getInstance($link);
		$view->refresh($link, $_REQUEST);

		require_once('tpl_view_employers.php');
	}
}
?>