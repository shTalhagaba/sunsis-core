<?php
class view_er1 implements IAction
{
	public function execute(PDO $link)
	{
		
		$contract_id = isset($_REQUEST['contract_id']) ? $_REQUEST['contract_id']:'';
		$submission = isset($_REQUEST['submission']) ? $_REQUEST['submission']:'';
		
		if($contract_id == '' || $submission == '')
			throw new Exception("Data missing");
		
		$view = ViewER1::getInstance($contract_id, $submission);
		$view->refresh($link, $_REQUEST);
		
		require_once('tpl_view_er1.php');
	}
}
?>