<?php
class view_discrepency_report implements IAction
{
	public function execute(PDO $link)
	{
		
		$contract_id = isset($_REQUEST['contract_id']) ? $_REQUEST['contract_id']:'';
		$submission = isset($_REQUEST['submission']) ? $_REQUEST['submission']:'';

		$_SESSION['bc']->add($link, "do.php?_action=view_discrepency_report&contract_id=" . $contract_id . "&submission=" . $submission, "View Courses");
		
		if($contract_id == '' || $submission == '')
			throw new Exception("Data missing");

		$vo = Contract::loadFromDatabase($link, $contract_id);

		$view = ViewDiscrepencyReport::getInstance($contract_id, $submission);
		$view->refresh($link, $_REQUEST);
		
		require_once('tpl_view_discrepency_report2.php');
	}
}
?>