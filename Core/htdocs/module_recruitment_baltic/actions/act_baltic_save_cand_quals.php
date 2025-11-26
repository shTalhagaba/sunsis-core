<?php
class baltic_save_cand_quals implements IAction
{
	public function execute(PDO $link)
	{
		$candidate_id = isset($_REQUEST['candidate_id'])?$_REQUEST['candidate_id']:'';
		$indexValue = isset($_REQUEST['indexValue'])?$_REQUEST['indexValue']:'';
		$new_record = isset($_REQUEST['new_record'])?$_REQUEST['new_record']:'';
		$qualifications = isset($_REQUEST['qualifications'])?$_REQUEST['qualifications']:'';
		$last_education = isset($_REQUEST['last_education'])?$_REQUEST['last_education']:'';

		$qualifications_xml = XML::loadSimpleXML($qualifications);
		$sql = "";
		foreach($qualifications_xml->qualification as $qualification)
		{
			$sql .= "INSERT INTO candidate_qualification (candidate_id, qualification_level, qualification_subject, qualification_grade, qualification_date, school_name) VALUES (";
			$sql .= "'" . $candidate_id . "', ";
			$sql .= "'" . $qualification->level . "', ";
			$sql .= "'" . $qualification->subject . "', ";
			$sql .= "'" . $qualification->grade . "', ";
			if($qualification->date == '')
				$qualification->date = '0000-00-00';
			else
				$qualification->date = Date::to($qualification->date, 'Y-m-d');
			$sql .= "'" . $qualification->date . "', ";
			$sql .= "'" . $qualification->school . "'); ";
		}


		if($sql != "")
		{
			DAO::transaction_start($link);
			try
			{
				if($new_record == 0)
					DAO::execute($link, "DELETE FROM candidate_qualification WHERE candidate_id = " . $candidate_id);
				DAO::execute($link, $sql);

				if($last_education != '')
					DAO::execute($link, "UPDATE candidate SET last_education = " . $last_education . " WHERE id = " . $candidate_id);

				DAO::transaction_commit($link);
			}
			catch(Exception $e)
			{
				DAO::transaction_rollback($link);
				throw new WrappedException($e);

			}
		}
		if($last_education != '')
			DAO::execute($link, "UPDATE candidate SET last_education = " . $last_education . " WHERE id = " . $candidate_id);
	}
}
?>
