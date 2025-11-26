<?php
class import_contracts implements IAction
{
	public function execute(PDO $link)
	{
		
		$_SESSION['bc']->add($link, "do.php?_action=import_contracts", "Migrate Contracts");
		
		$contract_id = isset($_REQUEST['contract_id']) ? $_REQUEST['contract_id'] : '';
		$sync = isset($_REQUEST['sync']) ? $_REQUEST['sync'] : '';

		$contract_year = DAO::getSingleValue($link, "select contract_year from contracts where id = '$contract_id'");	
		$contract_year -=1;
		$c = DAO::getSingleValue($link, "select count(*) from contracts where contract_year = '$contract_year'");
		if($c<1)
			throw new Exception("There is no contract in the system to import");
		
		$view = ImportContracts::getInstance($link, $contract_id);
		$view->refresh($link, $_REQUEST);
		
		require_once('tpl_import_contracts.php');
	}
}
?>
