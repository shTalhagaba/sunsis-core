<?php
class ajax_save_evidence_feedback implements IAction
{
	public function execute(PDO $link)
	{
		$tr_id = isset($_REQUEST['tr_id'])?$_REQUEST['tr_id']:'';
		$evidence_id = isset($_REQUEST['evidence_id'])?$_REQUEST['evidence_id']:'';
		$feedback = isset($_REQUEST['feedback'])?$_REQUEST['feedback']:'';

		$feedback .= ' - Added On ' . date('Y-m-d H:i:s') . " By " . $_SESSION['user']->username;
		$sql = "UPDATE tr_qual_portfolio_evidences SET feedback = CONCAT(feedback, '<br>', '" . $feedback . "') WHERE tr_id = " . $tr_id . " AND id = " . $evidence_id;

		try
		{
			DAO::execute($link, $sql);
		}
		catch(Exception $e)
		{
			throw new Exception($e->getMessage());
		}

	}
}