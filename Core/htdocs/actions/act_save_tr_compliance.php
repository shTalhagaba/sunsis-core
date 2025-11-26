<?php
class save_tr_compliance implements IAction
{
	public function execute(PDO $link)
	{
		$compliance_ids = isset($_POST['compliance_ids']) ? explode(",", $_POST['compliance_ids']) : [];
		if(count($compliance_ids) == 0)
			throw new Exception('Nothing to save.');

		$compliance_fields = [
			"submitted_date",
			//"compliant",
			"actual_date",
			"status1",
			"comments",
			"sub_events",
		];

		$info_to_be_saved = [];

		foreach($compliance_ids AS $id)
		{
			$entry = [
				'compliance_item_id' => $id,
				'tr_id' => $_POST['tr_id'],
			];
			foreach($compliance_fields AS $field)
			{
				$field_name = "{$field}_{$id}";
				$entry[$field] = isset($_POST[$field_name]) ? $_POST[$field_name] : null;
			}
			$blank_entry = true;
			foreach($compliance_fields AS $field)
			{
				if($entry[$field] != '')
				{
					$blank_entry = false;
					break;
				}
			}
			if(!$blank_entry)
				$info_to_be_saved[] = $entry;
		}

		//pre($info_to_be_saved);

		DAO::transaction_start($link);
		try
		{
			DAO::execute($link, "DELETE FROM tr_compliance WHERE tr_id = '{$_POST['tr_id']}'");

			DAO::multipleRowInsert($link, 'tr_compliance', $info_to_be_saved);

			DAO::transaction_commit($link);
		}
		catch(Exception $ex)
		{
			DAO::transaction_rollback($link, $ex);
			throw new WrappedException($ex);
		}

		$referrer = isset($_POST['referrer']) ? $_POST['referrer'] : 'do.php?_action=read_training_record&id='.$_POST['tr_id'];
		$start = strpos($referrer, 'do.php');
		$extract = substr($referrer, $start);

		http_redirect($extract);
	}
}