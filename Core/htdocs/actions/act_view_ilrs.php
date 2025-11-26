<?php
class view_ilrs implements IAction
{
	public function execute(PDO $link)
	{
		//$contract_id = isset($_REQUEST['contract_id'])?$_REQUEST['contract_id']:'';
		//$submission = isset($_REQUEST['submission'])?$_REQUEST['submission']:'';
	
		$id = isset($_REQUEST['id'])?$_REQUEST['id']:'';
		
		$contract_id = substr($id,3);
		$submission = substr($id,0,3);
		
		$contract_year = DAO::getSingleValue($link, "select contract_year from contracts where id = $contract_id");
		$contract_title = DAO::getSingleValue($link, "select title from contracts where id = $contract_id");
		
		$_SESSION['bc']->add($link, "do.php?_action=view_ilrs&id=" . $id, "View ILRs");	
		
		$passed = 'dfsdf';	
		
		if($id=='')
		{
			throw new Exception("Missing contract id and submission ");
		}
		
		$sql = "Select L01 from ilr where submission='$submission' and contract_id='$contract_id'";
		$L01 = DAO::getSingleValue($link, $sql);
			
		$view = ViewIlrs::getInstance($link, $id);
		$view->refresh($link, $_REQUEST);

		require_once('tpl_view_ilrs.php');
	}
}
?>