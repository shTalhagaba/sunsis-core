<?php
class view_dars_requests_admin implements IAction
{
	public function execute(PDO $link)
	{
		$_SESSION['bc']->index = 0;
		$_SESSION['bc']->add($link, "do.php?_action=view_dars_requests_admin", "View DARS summary");

		$view = ViewDARSRequests::getInstance($link);
		$view->refresh($link, $_REQUEST);

		require_once('tpl_view_dars_requests_admin.php');
	}
}