<?php
class edit_crm_note implements IAction
{
	public function execute(PDO $link)
	{
		// Validate data entry

		$id = isset($_REQUEST['id']) ? $_REQUEST['id'] : '';
		$organisation_id = isset($_REQUEST['organisations_id']) ? $_REQUEST['organisations_id'] : '';
		$person_contacted = isset($_REQUEST['person_contacted']) ? $_REQUEST['person_contacted'] : '';
		$mode = isset($_REQUEST['mode']) ? $_REQUEST['mode'] : '';

		if(SOURCE_LOCAL || in_array(DB_NAME, ["am_lead_demo", "am_demo", "am_duplex"]))
			http_redirect("do.php?_action=edit_org_crm_note&id={$id}&organisations_id={$organisation_id}");

		$pool_id = isset($_REQUEST['pool_id']) ? $_REQUEST['pool_id'] : '';

		$organisation_type = isset($_REQUEST['organisation_type']) ? $_REQUEST['organisation_type'] : '';

		$_SESSION['bc']->add($link, "do.php?_action=edit_crm_note&id=" . $id . "&organisations_id=" . $organisation_id . "&organisations_type=" . $organisation_type, "CRM Note");

		if($id !== '' && !is_numeric($id))
		{
			throw new Exception("You must specify a numeric id in the querystring");
		}

		$view2 = NULL;
		$vo = NULL;

		$outcome_required = 0;

		if(	'' != $pool_id ) {
			$view2 = ViewPoolCrmNotes::getInstance($link, $pool_id);
			if( $id == '' ) {
				// New record
				$vo = new PoolCrmNote();
			}
			else {
				$vo = PoolCrmNote::loadFromDatabase($link, $id);
			}
			$organisation_name = DAO::getSingleValue($link, "SELECT company FROM central.emp_pool WHERE auto_id = '" . $pool_id . "'");
		}elseif ( '' != $organisation_id ) {
			$view2 = ViewCrmNotes::getInstance($link, $organisation_id);
			if( $id == '' ) {
				// New record
				$vo = new CrmNote();
			}
			else {
				$vo = CrmNote::loadFromDatabase($link, $id);
			}
			$organisation_name = DAO::getSingleValue($link, "SELECT legal_name FROM organisations WHERE id = '" . $organisation_id . "'");
		}

		if ( isset($_REQUEST['create']) && $_REQUEST['create'] == 'contact' ) {
			if( '' != $pool_id )
				$new_contact = new EmployerPoolContacts();
			else
				$new_contact = new EmployerContacts();

			// remove the default values
			// ---
			foreach ( $_POST as $post_name => $post_value ) {
				if ( preg_match ('/.*\.\.\.$/', $post_value) ) {
					$_POST[$post_name] = '';
				}
			}
			$new_contact->populate($_POST);
			if ( '' != $pool_id )
			{
				$new_contact->org_id = $pool_id;
			}
			else
			{
				$new_contact->org_id = $organisation_id;
			}
			$new_contact->save($link);
		}

		// get the overrall status of the contact
		$next_action_id = 0;
		if ( $organisation_id != "" AND isset($mode) AND $mode == 'edit' AND !isset($_REQUEST['create'])) {
			$sql = "select * from organisations_status where org_id = ".$organisation_id." and note_id = ".$id;
			$org_status = DAO::getResultSet($link, $sql);
			if ( sizeof($org_status) > 0 ) {
				// $org_status[0][3] = org_status_comment ( plain text status )
				if ( preg_match('/appointment/i', $org_status[0][3]) ) {
					$outcome_required = 1;
				}
				$next_action_id = $org_status[0][2];
			}
		}
		elseif($pool_id != '' AND isset($mode) AND $mode == 'edit' AND !isset($_REQUEST['create']))
		{
			$sql = "select * from organisations_status where org_id = ".$pool_id." and note_id = " . $id;
			$org_status = DAO::getResultSet($link, $sql);
			if ( sizeof($org_status) > 0 ) {
				// $org_status[0][3] = org_status_comment ( plain text status )
				if ( preg_match('/appointment/i', $org_status[0][3]) ) {
					$outcome_required = 1;
				}
				$next_action_id = $org_status[0][2];
			}
		}


		// get the addtional crm contacts for the organisation.
		if($pool_id != '')
			$contact = ViewEmployerPoolContacts::getInstance($link, $pool_id);
		else
			$contact = ViewEmployerContacts::getInstance($link, $organisation_id);
		// $contact->refresh($link, $_REQUEST);

		// Dropdown arrays
		if(DB_NAME=="am_baltic" || DB_NAME=="ams")
		{
			if(	'' != $pool_id )
				$sql = "SELECT id, description, null FROM lookup_crm_regarding where description != '' and pool = 1 order by description asc;";
			elseif ( '' != $organisation_id )
				$sql = "SELECT id, description, null FROM lookup_crm_regarding where description != '' and employer = 1 order by description asc;";
		}
		else
			$sql = "SELECT id, description, null FROM lookup_crm_regarding where description != '' order by description asc;";
		$contact_status = DAO::getResultSet($link, $sql);

		$sql = "SELECT id, description, null FROM lookup_crm_contact_type where description != '' order by description asc;";
		$contact_type = DAO::getResultSet($link, $sql);

		if(DB_NAME=="am_reed" || DB_NAME=="am_reed_demo")
			$sql = "SELECT id, description, null FROM lookup_crm_subject_org where description != '' and active=1 order by description asc;";
		elseif(DB_NAME=="am_baltic" || DB_NAME=="ams")
		{
			if(	'' != $pool_id )
				$sql = "SELECT id, description, null FROM lookup_crm_subject where description != '' and pool = 1 order by description asc;";
			elseif ( '' != $organisation_id )
				$sql = "SELECT id, description, null FROM lookup_crm_subject where description != '' and employer = 1 order by description asc;";
		}
		else
			$sql = "SELECT id, description, null FROM lookup_crm_subject where description != '' order by description asc;";
		$subject = DAO::getResultSet($link, $sql);

		$sql = "SELECT description, description, null FROM lookup_crm_outcomes where description != '' order by description asc;";
		$outcomes = DAO::getResultSet($link, $sql);

		$sql = "SELECT description, description, null FROM lookup_crm_outcomes_plus where description != '' order by description asc;";
		$outcomes_plus = DAO::getResultSet($link, $sql);

		include('tpl_edit_crm_note.php');
	}
}
?>