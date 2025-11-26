<?php
class view_screen1 implements IAction
{
	public function execute(PDO $link)
	{
		$_SESSION['bc']->index=0;
		$_SESSION['bc']->add($link, "do.php?_action=view_screen1" , "View Screen 1");

		$view = ViewScreen1::getInstance($link);
		$view->refresh($link, $_REQUEST);

		require_once('tpl_view_screen1.php');
	}
}
?>