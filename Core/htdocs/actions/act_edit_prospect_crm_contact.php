<?php
class edit_prospect_crm_contact implements IAction
{
	public function execute(PDO $link)
	{
		$contact_id = isset($_REQUEST['contact_id'])?$_REQUEST['contact_id']:'';
		$subaction = isset($_REQUEST['subaction'])?$_REQUEST['subaction']:'';

		if($contact_id == '')
		{
			throw new Exception("CRM Contact ID is missing");
		}

		if($subaction == 'save')
		{
			if(isset($_REQUEST['contact_id']))
			{
				$sql = "UPDATE organisation_contact SET contact_name = '" . $_REQUEST['contact_name'] . "'";
				$sql .= ", contact_telephone = '" . $_REQUEST['contact_telephone'] . "'";
				$sql .= ", contact_mobile = '" . $_REQUEST['contact_mobile'] . "'";
				$sql .= ", contact_title = '" . $_REQUEST['contact_title'] . "'";
				$sql .= ", contact_department = '" . $_REQUEST['contact_department'] . "'";
				$sql .= ", contact_email = '" . $_REQUEST['contact_email'] . "'";
				$sql .= " WHERE org_id = '" . $_REQUEST['org_id'] . "' ";
				$sql .= " AND contact_id = '" . $_REQUEST['contact_id'] . "' ";

				$result = DAO::execute($link, $sql);

				$name_of_person_old = $_REQUEST['contact_old_title'] . " " . $_REQUEST['contact_old_name'];
				$name_of_person_new = $_REQUEST['contact_title'] . " " . $_REQUEST['contact_name'];

				if($result)
					DAO::execute($link, "UPDATE employerpool_notes SET name_of_person = '".$name_of_person_new."' WHERE name_of_person = '".$name_of_person_old."' AND organisation_id = '" . $_REQUEST['org_id'] . "' ");

				http_redirect('do.php?_action=read_employers_pool_emp&auto_id='.$_REQUEST['org_id']);
			}
		}

		$result = DAO::getResultset($link, "SELECT * FROM organisation_contact WHERE contact_id = " . $contact_id);
		$contact = array();
		$contact['contact_id'] = $result[0][0];
		$contact['org_id'] = $result[0][1];
		$contact['contact_name'] = $result[0][2];
		$contact['contact_telephone'] = $result[0][3];
		$contact['contact_mobile'] = $result[0][4];
		$contact['contact_title'] = $result[0][5];
		$contact['contact_department'] = $result[0][6];
		$contact['contact_email'] = $result[0][7];

		include('tpl_edit_prospect_crm_contact.php');
	}
}