<?php
class save_contractholder implements IAction
{
	public function execute(PDO $link)
	{

		$org = new ContractHolder();
		$org->populate($_POST);
		
		$org->save($link);
		
		http_redirect($_SESSION['bc']->getPrevious());
	}
}
?>