<?php
class ajax_signoff_pcs_evidence_mapping implements IAction
{
	public function execute(PDO $link)
	{
		$tr_id = isset($_REQUEST['tr_id'])?$_REQUEST['tr_id']:'';
		$evidence_id = isset($_REQUEST['evidence_id'])?$_REQUEST['evidence_id']:'';
		$selected_pcs = isset($_REQUEST['selected_pcs'])?$_REQUEST['selected_pcs']:'';

		$splits = explode(',',$selected_pcs);
		$sql = "";
		$date_time = date('Y-m-d H:i:s');
		foreach($splits AS $split)
		{
			$element_and_pc = explode('_',$split);
			$element = $element_and_pc[1];
			$pc = $element_and_pc[2];

			$sql .= "UPDATE qualification_pcs SET signoff_date = '" . $date_time . "', signoff_by = " . $_SESSION['user']->id . " WHERE element_id = " . $element . " AND pc_id = " . $pc . "; ";
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