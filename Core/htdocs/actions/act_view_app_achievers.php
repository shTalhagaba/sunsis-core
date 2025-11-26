<?php
class view_app_achievers implements IAction
{
	public function execute(PDO $link)
	{
		
		$view = ViewAppAchievers::getInstance($link);
		$view->refresh($link, $_REQUEST);
		
		require_once('tpl_view_app_achievers.php');
	}
}
?>