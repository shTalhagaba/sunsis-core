<?php
class ajax_save_tr_destinations implements IAction
{
	public function execute(PDO $link)
	{
		$tr_id = isset($_REQUEST['tr_id'])?$_REQUEST['tr_id']:'';
		$xml = isset($_REQUEST['xml'])?$_REQUEST['xml']:'';
		$new_record = isset($_REQUEST['new_record'])?$_REQUEST['new_record']:'';
		$ids = isset($_REQUEST['ids'])?$_REQUEST['ids']:'';
		if($ids != '')
		{
			$sql = "DELETE FROM destinations WHERE tr_id = " . $tr_id . " AND id IN (" . $ids . ")";
			DAO::execute($link, $sql);
			exit;
		}

		$xmlDestinations = XML::loadSimpleXML($xml);

		$sql = "";
		foreach($xmlDestinations->destination as $dest)
		{
			if($dest->outcome_collection_date == '')
				$dest->outcome_collection_date = "'0000-00-00'";
			else
				$dest->outcome_collection_date = "'" . Date::toMySQL($dest->outcome_collection_date) . "'";
			if($dest->outcome_end_date == '')
				$dest->outcome_end_date = "'0000-00-00'";
			else
				$dest->outcome_end_date = "'" . Date::toMySQL($dest->outcome_end_date) . "'";

			$sql .= "INSERT INTO destinations (tr_id, outcome_type, outcome_code, outcome_start_date, outcome_end_date, outcome_collection_date, type_code)
				VALUES (" . $tr_id . ", '" . $dest->outcome_type . "', '" . $dest->outcome_code . "', '" . Date::toMySQL($dest->outcome_start_date) . "',
				" . $dest->outcome_end_date . ", " . $dest->outcome_collection_date . ", '" . $dest->type_code . "');";
		}
//throw new Exception($sql);
		DAO::transaction_start($link);
		try
		{
			if($sql!='')
			{
				if($new_record == 0)
					DAO::execute($link, "DELETE FROM destinations WHERE tr_id = " . $tr_id);

				DAO::execute($link, $sql);
			}
			DAO::transaction_commit($link);
		}
		catch(Exception $e)
		{
			DAO::transaction_rollback($link);
			throw new WrappedException($e);
		}
	}
}
?>
