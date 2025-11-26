<?php
class view_contracts_ilr implements IAction
{
	public function execute(PDO $link)
	{
		$view = ViewContractsIlr::getInstance();
		$view->refresh($link, $_REQUEST);
		
		
		require_once('tpl_view_contracts_ilr.php');
	}
}
?>