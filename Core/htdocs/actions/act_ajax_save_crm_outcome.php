<?php
class ajax_save_crm_outcome implements IAction
{
	public function execute(PDO $link)	{
		$reason = isset($_REQUEST['action'])?$_REQUEST['action']:'';
		if ( $reason != '' ) {
			$query = "insert into lookup_crm_outcomes (id, description) values(NULL,'$reason');";
			DAO::execute($link, $query);
		}
	}
}
?>