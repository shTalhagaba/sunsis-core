<?php
class baltic_save_candidate_interests implements IAction
{
	public function execute(PDO $link)
	{
		$candidate_id = isset($_REQUEST['candidate_id'])?$_REQUEST['candidate_id']:'';
		$interests = isset($_REQUEST['interests'])?$_REQUEST['interests']:'';

		$interests_xml = XML::loadSimpleXML($interests);
		$sql = "";
		foreach($interests_xml->interest as $interest)
		{
			$sql .= "INSERT INTO candidate_sector_choice (candidate_id, sector) VALUES (";
			$sql .= "'" . $candidate_id . "', ";
			$sql .= "'" . $interest->id . "'); ";
		}

		DAO::transaction_start($link);
		try
		{
			DAO::execute($link, "DELETE FROM candidate_sector_choice WHERE candidate_id = " . $candidate_id);
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
?>
