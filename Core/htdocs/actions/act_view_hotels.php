<?php
class view_hotels implements IAction
{
	public function execute(PDO $link)
	{

		$_SESSION['bc']->index=0;
		$_SESSION['bc']->add($link, "do.php?_action=view_hotels" , "View Hotels");

		$view = ViewHotels::getInstance();
		$view->refresh($link, $_REQUEST);

		require_once('tpl_view_hotels.php');
	}
}
?>