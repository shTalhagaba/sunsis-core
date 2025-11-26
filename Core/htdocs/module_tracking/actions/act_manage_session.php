<?php
class manage_session implements IAction
{
	public function execute(PDO $link)
	{
		$selected_tab = isset($_REQUEST['selected_tab'])?$_REQUEST['selected_tab']:'';
		$id = isset($_REQUEST['id'])?$_REQUEST['id']:'';
		$tab2FilterIndDateFrom = isset($_REQUEST['tab2FilterIndDateFrom'])?$_REQUEST['tab2FilterIndDateFrom']:'01/03/2018';
		$tab2FilterIndDateTo = isset($_REQUEST['tab2FilterIndDateTo'])?$_REQUEST['tab2FilterIndDateTo']:'';

		if($id == '')
			throw new Exception('Missing querystring: session id');

		if($selected_tab == '')
			$selected_tab = isset($_SESSION['ManageSessionSelectedTab'])?$_SESSION['ManageSessionSelectedTab']:'tab1';
		$selected_tab = isset($_REQUEST['selected_tab'])?$_REQUEST['selected_tab']:(isset($_SESSION['ManageSessionSelectedTab'])?$_SESSION['ManageSessionSelectedTab']:'tab1');

		if(isset($_SERVER['HTTP_REFERER']) && strpos($_SERVER['HTTP_REFERER'], 'manage_session') !== false)
		{
			$_SESSION['bc']->pop();
		}
		$_SESSION['bc']->add($link, "do.php?_action=manage_session&id=".$id.'&tab2FilterIndDateFrom='.$tab2FilterIndDateFrom.'&tab2FilterIndDateTo='.$tab2FilterIndDateTo, "Manage Session");

		$session = OperationsSession::loadFromDatabase($link, $id);

		$d1 = new Date($session->end_date);
		$d2 = new Date(date('Y-m-d'));
		$disabled = $d1->equals($d2) ? '' : ($d1->after($d2) ? '' : ' disabled="disabled" ');

		$tab1 = "";
		$tab2 = "";
		$tab3 = "";
		$tab4 = "";
		if(isset($$selected_tab))
			$$selected_tab = " active ";
		else
			$tab1 = " active ";

		include_once('tpl_manage_session.php');
	}
}