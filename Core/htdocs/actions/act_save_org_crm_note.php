<?php
class save_org_crm_note implements IAction
{
	public function execute(PDO $link)
	{
		$crm_note = new OrganisationCRMNote();
		$crm_note->populate($_POST);

		$existing_record = OrganisationCRMNote::loadFromDatabase($link, $_POST['id']);

		DAO::transaction_start($link);
		try
		{
			if(!is_null($existing_record))
			{
				$log_string = $existing_record->buildAuditLogString($link, $crm_note);
				if($log_string != '')
				{
					$audit_note = new Note();
					$audit_note->subject = "CRM note updated";
					$audit_note->note = $log_string;
				}
			}

			$crm_note->save($link);

			if(isset($audit_note) && !is_null($audit_note))
			{
				$audit_note->is_audit_note = true;
				$audit_note->parent_table = 'crm_notes_orgs';
				$audit_note->parent_id = $crm_note->id;
				$audit_note->created = date('Y-m-d H:i:s');
				$audit_note->save($link);
			}

			DAO::transaction_commit($link);
		}
		catch(Exception $e)
		{
			DAO::transaction_rollback($link, $e);
			throw new WrappedException($e);
		}

		http_redirect('do.php?_action=read_employer_v3&id='.$crm_note->organisation_id);
	}
}
