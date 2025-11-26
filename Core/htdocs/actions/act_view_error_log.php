<?php
class view_error_log implements IAction
{
	public function execute(PDO $link)
	{
		$view = ViewErrors::getInstance();
		$view->refresh($link, $_REQUEST);	
		
		require_once('tpl_view_error_log.php');
	}
}
?>