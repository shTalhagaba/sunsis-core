<?php
class ajax_save_unit_evidence_mapping implements IAction
{
	public function execute(PDO $link)
	{
		$tr_id = isset($_REQUEST['tr_id'])?$_REQUEST['tr_id']:'';
		$evidence_id = isset($_REQUEST['evidence_id'])?$_REQUEST['evidence_id']:'';
		$unit_id = isset($_REQUEST['unit_id'])?$_REQUEST['unit_id']:'';
		$checked = isset($_REQUEST['checked'])?$_REQUEST['checked']:'';

		if($checked == true)
		{
			$sql = "INSERT INTO tr_portfolio_unit_mapping (tr_id, evidence_id, unit_id) VALUES (" . $tr_id . ", " . $evidence_id . ", " . $unit_id . ")";
		}
		elseif($checked == false)
		{
			$sql = "DELETE FROM tr_portfolio_unit_mapping WHERE tr_id = " . $tr_id . " AND unit_id = " . $unit_id . " AND evidence_id = " . $evidence_id;
		}
		try
		{
			DAO::execute($link, $sql);
		}
		catch(Exception $e)
		{
			throw new Exception($e);
		}
	}
}