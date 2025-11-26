<?php
class save_training_record_v2 implements  IAction
{
	public function execute(PDO $link)
	{
		if(!isset($_POST['id']) || $_POST['id'] == '')
			throw new Exception('Save aborted.');

		$existing_record = TrainingRecord::loadFromDatabase($link, $_POST['id']);
		if(is_null($existing_record))
			throw new Exception('Save aborted.');

		$vo = new TrainingRecord();
		$vo->populate($_POST);

		$vo->employer_id = DAO::getSingleValue($link, "SELECT locations.organisations_id FROM locations WHERE locations.id = '{$vo->employer_location_id}'");

		$provider_location = Location::loadFromDatabase($link, $vo->provider_location_id);
		$vo->provider_id = $provider_location->organisations_id;
		$vo->provider_address_line_1 = $provider_location->address_line_1;
		$vo->provider_address_line_2 = $provider_location->address_line_2;
		$vo->provider_address_line_3 = $provider_location->address_line_3;
		$vo->provider_address_line_4 = $provider_location->address_line_4;
		$vo->provider_postcode = $provider_location->postcode;
		$vo->provider_telephone = $provider_location->telephone;

		DAO::transaction_start($link);
		try
		{
			$log_string = $existing_record->buildAuditLogString($link, $vo);
			if($log_string!='')
			{
				$note = new Note();
				$note->subject = "Training record updated";
				$note->note = $log_string;
			}

			if(DB_NAME == "am_baltic")
			{
				if(in_array("dparks", ["dparks"]))
				{
					$existing_record->ad_lldd = substr($vo->ad_lldd, 0, 199);
					$existing_record->ad_arrangement_req = substr($vo->ad_arrangement_req, 0, 199);
					$existing_record->ad_arrangement_agr = substr($vo->ad_arrangement_agr, 0, 799);
					$existing_record->ad_evidence = $vo->ad_evidence;
					$existing_record->save($link);
					DAO::transaction_commit($link);
					http_redirect('do.php?_action=read_training_record&id=' . $vo->id);
				}
				else
				{
					$vo->ad_lldd = $existing_record->ad_lldd;
					$vo->ad_arrangement_req = $existing_record->ad_arrangement_req;
					$vo->ad_arrangement_agr = $existing_record->ad_arrangement_agr;
					$vo->ad_evidence = $existing_record->ad_evidence;
				}
			}

			$vo->save($link);

			if($existing_record->contract_id != $vo->contract_id)
			{
				DAO::execute($link, "UPDATE ilr SET contract_id = '{$vo->contract_id}' WHERE tr_id = '{$vo->id}' AND contract_id = '{$existing_record->contract_id}'");
			}

			$userRecord = User::loadFromDatabase($link, $vo->username);
			$userRecord->firstnames = $vo->firstnames;
			$userRecord->surname = $vo->surname;
			$userRecord->gender = $vo->gender;
			$userRecord->dob = $vo->dob;
			$userRecord->ethnicity = $vo->ethnicity;
			$userRecord->home_address_line_1 = $vo->home_address_line_1;
			$userRecord->home_address_line_2 = $vo->home_address_line_2;
			$userRecord->home_address_line_3 = $vo->home_address_line_3;
			$userRecord->home_address_line_4 = $vo->home_address_line_4;
			$userRecord->home_postcode = $vo->home_postcode;
			$userRecord->home_telephone = $vo->home_telephone;
			$userRecord->home_mobile = $vo->home_mobile;
			$userRecord->home_email = $vo->home_email;
			$userRecord->enrollment_no = $_POST['enrollment_no'];
			$userRecord->save($link);

			if(isset($note) && !is_null($note))
			{
				$note->is_audit_note = true;
				$note->parent_table = 'tr';
				$note->parent_id = $vo->id;
				$note->save($link);
			}

			DAO::transaction_commit($link);
		}
		catch(Exception $e)
		{
			DAO::transaction_rollback($link, $e);
			throw new WrappedException($e);
		}

		http_redirect('do.php?_action=read_training_record&id=' . $vo->id);
	}
}
?>