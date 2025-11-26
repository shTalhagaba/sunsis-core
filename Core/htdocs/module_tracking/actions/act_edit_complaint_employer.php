<?php
class edit_complaint_employer implements IAction
{
	public function execute(PDO $link)
	{
		$id = isset($_REQUEST['id']) ? $_REQUEST['id'] : '';
		$record_id = isset($_REQUEST['record_id']) ? $_REQUEST['record_id'] : '';
		if($record_id == '')
			throw new Exception('Missing querystring argument: record_id');

		$_SESSION['bc']->add($link, "do.php?_action=edit_complaint_employer&id=".$id."&record_id=".$record_id, "Edit Employer Complaint");

		if($id == '')
		{
			$complaint = new ComplaintEmployer($record_id);
			$complaint->outcome = 'O';
		}
		else
		{
			$complaint = ComplaintEmployer::loadFromDatabase($link, $id);
		}

		include('tpl_edit_complaint_employer.php');
	}
}
?>