<?php
class ajax_save_crm_action implements IAction
{
	public function execute(PDO $link)	{
		$reason = isset($_REQUEST['action'])?$_REQUEST['action']:'';
		if ( $reason != '' ) {
			$query = "insert into lookup_crm_regarding (id, description) values(NULL,'$reason');";
			DAO::execute($link, $query);
		}
	}
}
?>