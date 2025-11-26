<?php
class view_unsuccessful_logins implements IAction
{
	public function execute(PDO $link)
	{
		$view = ViewUnsuccessfulLogins::getInstance();
		$view->refresh($link, $_REQUEST);		
		
		require_once('tpl_view_unsuccessful_logins.php');
	}
}
?>