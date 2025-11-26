<?php
class get_contracts_miap implements IAction
{
	public function execute(PDO $link)
	{
		
		$_SESSION['bc']->add($link, "do.php?_action=get_contracts_miap", "MIAP Batch");
		
		$view = GetContractsMIAP::getInstance();
		$view->refresh($link, $_REQUEST);
		
		require_once('tpl_get_contracts_miap.php');
	}
}
?>
