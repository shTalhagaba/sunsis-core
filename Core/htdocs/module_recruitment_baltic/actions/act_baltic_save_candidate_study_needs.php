<?php
class baltic_save_candidate_study_needs implements IAction
{
	public function execute(PDO $link)
	{
		$candidate_id = isset($_REQUEST['candidate_id'])?$_REQUEST['candidate_id']:'';
		$disabilities = isset($_REQUEST['disabilities'])?$_REQUEST['disabilities']:'';
		$difficulties = isset($_REQUEST['difficulties'])?$_REQUEST['difficulties']:'';

		$disabilities_xml = XML::loadSimpleXML($disabilities);
		$sql = "";
		foreach($disabilities_xml->disability as $disability)
		{
			$sql .= "INSERT INTO candidate_disability (candidate_id, disability_code) VALUES (";
			$sql .= "'" . $candidate_id . "', ";
			$sql .= "'" . $disability->code . "'); ";
		}

		if($sql != "")
		{
			DAO::transaction_start($link);
			try
			{
				DAO::execute($link, "DELETE FROM candidate_disability WHERE candidate_id = " . $candidate_id);
				DAO::execute($link, $sql);
				DAO::transaction_commit($link);
			}
			catch(Exception $e)
			{
				DAO::transaction_rollback($link);
				throw new WrappedException($e);

			}
		}
		$difficulties_xml = XML::loadSimpleXML($difficulties);
		$sql = "";
		foreach($difficulties_xml->difficulty as $difficulty)
		{
			$sql .= "INSERT INTO candidate_difficulty (candidate_id, difficulty_code) VALUES (";
			$sql .= "'" . $candidate_id . "', ";
			$sql .= "'" . $difficulty->code . "'); ";
		}

		if($sql != "")
		{
			DAO::transaction_start($link);
			try
			{
				DAO::execute($link, "DELETE FROM candidate_difficulty WHERE candidate_id = " . $candidate_id);
				DAO::execute($link, $sql);
				DAO::transaction_commit($link);
			}
			catch(Exception $e)
			{
				DAO::transaction_rollback($link);
				throw new WrappedException($e);

			}
		}
	}
}
?>
