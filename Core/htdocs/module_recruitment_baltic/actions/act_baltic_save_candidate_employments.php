<?php
class baltic_save_candidate_employments implements IAction
{
	public function execute(PDO $link)
	{
		$candidate_id = isset($_REQUEST['candidate_id'])?$_REQUEST['candidate_id']:'';
		$indexValue = isset($_REQUEST['indexValue'])?$_REQUEST['indexValue']:'';
		$new_record = isset($_REQUEST['new_record'])?$_REQUEST['new_record']:'';
		$employments = isset($_REQUEST['employments'])?$_REQUEST['employments']:'';
		$employment_status = isset($_REQUEST['employment_status'])?$_REQUEST['employment_status']:'';

		$sql = "";
		if($employments != '')
		{
			$employments_xml = XML::loadSimpleXML($employments);

			foreach($employments_xml->employment as $employment)
			{
				$sql .= "INSERT INTO candidate_history (candidate_id, start_date, end_date, company_name, job_title, skills) VALUES (";
				$sql .= "'" . $candidate_id . "', ";
				if($employment->start_date == '')
					$employment->start_date = '0000-00-00';
				else
					$employment->start_date = Date::to($employment->start_date, 'Y-m-d');
				$sql .= "'" . $employment->start_date . "', ";
				if($employment->end_date == '')
					$employment->end_date = '0000-00-00';
				else
					$employment->end_date = Date::to($employment->end_date, 'Y-m-d');
				$sql .= "'" . $employment->end_date . "', ";
				$sql .= "'" . $employment->company_name . "', ";
				$sql .= "'" . $employment->job_title . "', ";
				$sql .= "'" . $employment->skills . "'); ";
			}
		}
		if($sql != "")
		{
			DAO::transaction_start($link);
			try
			{
				if($new_record == 0)
					DAO::execute($link, "DELETE FROM candidate_history WHERE candidate_id = " . $candidate_id);
				DAO::execute($link, $sql);

				if($employment_status != '')
					DAO::execute($link, "UPDATE candidate SET employment_status = " . $employment_status . " WHERE id = " . $candidate_id);

				DAO::transaction_commit($link);
			}
			catch(Exception $e)
			{
				DAO::transaction_rollback($link);
				throw new WrappedException($e);

			}
		}
		if($employment_status != '')
		{
			DAO::execute($link, "UPDATE candidate SET employment_status = " . $employment_status . " WHERE id = " . $candidate_id);
		}
	}
}
?>
