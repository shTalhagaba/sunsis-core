<?php
class save_prospect implements IAction
{
	public function execute(PDO $link)
	{
		$edit_mode = isset($_REQUEST['edit_mode'])?$_REQUEST['edit_mode']:'';

		$org = new EmployerPool();

		$org->populate($_POST);

		if($edit_mode == "2")
			$this->addProspect($link, $org);
		else
			$this->saveProspect($link, $org);

		http_redirect($_SESSION['bc']->getPrevious());

	}

	private function addProspect(PDO $link, EmployerPool $org)
	{
		$dpn = DAO::getSingleValue($link, "SELECT MAX(CONVERT(dpn,UNSIGNED INTEGER)) FROM central.emp_pool");
		$auto_id = DAO::getSingleValue($link, "SELECT MAX(auto_id) FROM central.emp_pool");
		$new_id = $auto_id + 1;
		$new_dpn = $dpn+1;
		$org->dpn = $new_dpn;
		$org->auto_id = $new_id;
		$company = addslashes((string)$org->company);
		$address1 = addslashes((string)$org->address1);
		$address2 = addslashes((string)$org->address2);
		$address3 = addslashes((string)$org->address3);
		$address4 = addslashes((string)$org->address4);
		$address5 = addslashes((string)$org->address5);
		$postcode = addslashes((string)$org->postcode);
		$telephone = addslashes((string)$org->telephone);
		$fax = addslashes((string)$org->fax);
		$primary_email_address = addslashes((string)$org->primary_email_address);
		$url = addslashes((string)$org->url);
		$twitter_address = addslashes((string)$org->twitter_address);
		$facebook_address = addslashes((string)$org->facebook_address);
		$country = addslashes((string)$org->country);
		$region = addslashes((string)$org->region);
		$no_employees = addslashes((string)$org->no_employees);
		if($no_employees=='')$no_employees=0;
		$source = addslashes((string)$org->source);
		$title = addslashes((string)$org->title);
		$firstname = addslashes((string)$org->firstname);
		$surname = addslashes((string)$org->surname);
		$job = addslashes((string)$org->job);
		$email1 = addslashes((string)$org->email1);
		$email2 = addslashes((string)$org->email2);

		$sql = <<<HEREDOC

		INSERT INTO
			central.emp_pool
		SET
			company = '$company',
			address1 = '$address1',
			address2 = '$address2',
			address3 = '$address3',
			address4 = '$address4',
			address5 = '$address5',
			postcode = '$postcode',
			telephone = '$telephone',
			fax = '$fax',
			primary_email_address = '$primary_email_address',
			url = '$url',
			twitter_address = '$twitter_address',
			facebook_address = '$facebook_address',
			country = '$country',
			region = '$region',
			no_employees = '$no_employees',
			source = '$source',
			title = '$title',
			firstname = '$firstname',
			surname = '$surname',
			job = '$job',
			email1 = '$email1',
			email2 = '$email2',
			dpn = '$org->dpn',
			auto_id = '$org->auto_id'

HEREDOC;

		if(DAO::execute($link, $sql))
		{
			$note = new Note();
			$note->subject = "Prospect Added - " . $company;
			$note->note = 'A new prospect record has been created.';

			if(isset($note) && !is_null($note))
			{
				$note->is_audit_note = true;
				$note->parent_table = 'emp_pool';
				$note->parent_id = $org->auto_id;
				$note->save($link);
			}

		}

	}

	private function saveProspect(PDO $link, EmployerPool $org)
	{

		$company = addslashes((string)$org->company);
		$address1 = addslashes((string)$org->address1);
		$address2 = addslashes((string)$org->address2);
		$address3 = addslashes((string)$org->address3);
		$address4 = addslashes((string)$org->address4);
		$address5 = addslashes((string)$org->address5);
		$postcode = addslashes((string)$org->postcode);
		$telephone = addslashes((string)$org->telephone);
		$fax = addslashes((string)$org->fax);
		$primary_email_address = addslashes((string)$org->primary_email_address);
		$url = addslashes((string)$org->url);
		$twitter_address = addslashes((string)$org->twitter_address);
		$facebook_address = addslashes((string)$org->facebook_address);
		$country = addslashes((string)$org->country);
		$region = addslashes((string)$org->region);
		$no_employees = addslashes((string)$org->no_employees);
		if($no_employees=='')$no_employees=0;
		$source = addslashes((string)$org->source);
		$title = addslashes((string)$org->title);
		$firstname = addslashes((string)$org->firstname);
		$surname = addslashes((string)$org->surname);
		$job = addslashes((string)$org->job);
		$email1 = addslashes((string)$org->email1);
		$email2 = addslashes((string)$org->email2);

		$sql = <<<HEREDOC

		UPDATE
			central.emp_pool
		SET
			company = '$company',
			address1 = '$address1',
			address2 = '$address2',
			address3 = '$address3',
			address4 = '$address4',
			address5 = '$address5',
			postcode = '$postcode',
			telephone = '$telephone',
			fax = '$fax',
			primary_email_address = '$primary_email_address',
			url = '$url',
			twitter_address = '$twitter_address',
			facebook_address = '$facebook_address',
			country = '$country',
			region = '$region',
			no_employees = '$no_employees',
			source = '$source',
			title = '$title',
			firstname = '$firstname',
			surname = '$surname',
			job = '$job',
			email1 = '$email1',
			email2 = '$email2'
		WHERE
			dpn = '$org->dpn' AND auto_id = '$org->auto_id'

HEREDOC;
		$existing_record = EmployerPool::loadFromDatabase($link, $org->auto_id);
		$log_string = $existing_record->buildAuditLogString($link, $org);

		DAO::execute($link, $sql);

		if($log_string!='')
		{
			$note = new Note();
			$note->subject = "Prospect Edited";
			$note->note = $log_string;
		}

		if(isset($note) && !is_null($note))
		{
			$note->is_audit_note = true;
			$note->parent_table = 'emp_pool';
			$note->parent_id = $org->auto_id;
			$note->save($link);
		}
	}
}


?>