<?php
class view_subcontractors implements IAction
{
	public function execute(PDO $link)
	{

		$view = ViewSubContractors::getInstance();
		$view->refresh($link, $_REQUEST);

		require_once('tpl_view_subcontractors.php');
	}
}
?>