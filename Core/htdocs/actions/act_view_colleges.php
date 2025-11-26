<?php
class view_colleges implements IAction
{
	public function execute(PDO $link)
	{

		$_SESSION['bc']->index=0;
		$_SESSION['bc']->add($link, "do.php?_action=view_colleges", "View Colleges");

		$view = ViewColleges::getInstance($link);
		$view->refresh($link, $_REQUEST);

		require_once('tpl_view_colleges.php');
	}
}
?>