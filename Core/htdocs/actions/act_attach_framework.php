<?php
class attach_framework implements IAction
{
	public function execute(PDO $link)
	{ 
		$tr_id = isset($_REQUEST['tr_id'])?$_REQUEST['tr_id']:'';

		$view = PickFramework::getInstance();
		$view->refresh($link, $_REQUEST);
		
		require_once('tpl_attach_framework.php');
	}
}
?>