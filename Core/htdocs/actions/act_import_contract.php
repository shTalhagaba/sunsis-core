<?php
class import_contract implements IAction
{
	public function execute(PDO $link)
	{

		$current_contract_id = isset($_REQUEST['current_contract_id'])?$_REQUEST['current_contract_id']:'';
		$contract_to_import_id = isset($_REQUEST['contract_to_import_id'])?$_REQUEST['contract_to_import_id']:'';
		

		return "<report>success</report>";
		
		
	}
}
?>