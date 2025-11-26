<?php
class ajax_save_pcs_evidence_mapping implements IAction
{
	public function execute(PDO $link)
	{
		$tr_id = isset($_REQUEST['tr_id'])?$_REQUEST['tr_id']:'';
		$evidence_id = isset($_REQUEST['evidence_id'])?$_REQUEST['evidence_id']:'';
		$selected_pcs = isset($_REQUEST['selected_pcs'])?$_REQUEST['selected_pcs']:'';

		$splits = explode(',',$selected_pcs);

		foreach($splits AS $split)
		{
			$element_and_pc = explode('_',$split);
			$element = $element_and_pc[1];
			$pc = $element_and_pc[2];

			$is_there = DAO::getSingleValue($link, "SELECT COUNT(*) FROM tr_portfolio_pcs_mapping WHERE tr_id = " . $tr_id . " AND evidence_id = " . $evidence_id . " AND element_id = " . $element . " AND pc_id = " . $pc);

			if($is_there == 0)
			{
				$sql = "INSERT INTO tr_portfolio_pcs_mapping (element_id, tr_id, pc_id, evidence_id) VALUES (" . $element . ", " . $tr_id . ", " . $pc . ", " . $evidence_id . ")";
			}
				//$sql = "DELETE FROM tr_portfolio_pcs_mapping WHERE tr_id = " . $tr_id . " AND element_id = " . $element . " AND evidence_id = " . $evidence_id . " AND pc_id = " . $pc;
			try
			{
				if($is_there == 0)
					DAO::execute($link, $sql);
			}
			catch(Exception $e)
			{
				throw new Exception($e);
			}

		}

	}
}