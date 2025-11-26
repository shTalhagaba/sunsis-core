<?php
class view_clients implements IAction
{
	public function execute(PDO $link)
	{

		$view = ViewClients::getInstance();
		$view->refresh($link, $_REQUEST);

		require_once('tpl_view_clients.php');
	}
}
?>