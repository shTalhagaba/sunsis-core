<?php
class save_epa_certification implements IAction
{
	public function execute(PDO $link)
	{
		$tr_id = isset($_REQUEST['tr_id']) ? $_REQUEST['tr_id'] : '';
		if($tr_id == '')
			throw new Exception('Missing querystring argument: tr_id');

		$records = DAO::getResultset($link, "SELECT * FROM student_qualifications WHERE tr_id = '{$tr_id}'", PDO::FETCH_ASSOC);
		foreach($records AS $row)
		{

			$qual = (object)$row;
			$qual->awarding_body = isset($_REQUEST['awarding_body'.$row['auto_id']]) ? $_REQUEST['awarding_body'.$row['auto_id']] : $qual->awarding_body;
			$qual->awarding_body_reg = isset($_REQUEST['awarding_body_reg'.$row['auto_id']]) ? $_REQUEST['awarding_body_reg'.$row['auto_id']] : $qual->awarding_body_reg;
			$qual->certificate_applied = isset($_REQUEST['certificate_applied'.$row['auto_id']]) ? $_REQUEST['certificate_applied'.$row['auto_id']] : $qual->certificate_applied;
			$qual->certificate_received = isset($_REQUEST['certificate_received'.$row['auto_id']]) ? $_REQUEST['certificate_received'.$row['auto_id']] : $qual->certificate_received;
			$qual->certificate_sent = isset($_REQUEST['certificate_sent'.$row['auto_id']]) ? $_REQUEST['certificate_sent'.$row['auto_id']] : $qual->certificate_sent;

			DAO::saveObjectToTable($link, 'student_qualifications', $qual);
		}






		http_redirect('do.php?_action=read_training_record&id=' . $tr_id);
	}

}
?>