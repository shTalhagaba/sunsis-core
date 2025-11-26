<?php
class view_contracts implements IAction
{
	public function execute(PDO $link)
	{
	
		$_SESSION['bc']->index=0;
		$_SESSION['bc']->add($link, "do.php?_action=view_contracts", "View Contracts");
	
		$view = ViewContracts::getInstance($link);
		$view->refresh($link, $_REQUEST);
		
		require_once('tpl_view_contracts.php');
	}
}
?>