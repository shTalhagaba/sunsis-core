<?php
class view_providers implements IAction
{
	public function execute(PDO $link)
	{
		$view = ViewProviders::getInstance();
		$view->refresh($link, $_REQUEST);
		
		require_once('tpl_view_providers.php');
	}
}
?>
