<?php
class view_er3 implements IAction
{
	public function execute(PDO $link)
	{
		
		$view = ViewER3::getInstance();
		$view->refresh($link, $_REQUEST);
		
		require_once('tpl_view_er3.php');
	}
}
?>