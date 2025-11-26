<?php
class save_crm_contact implements IAction
{
	public function execute(PDO $link)
	{
		$contact = new OrganisationContact($_POST['org_id']);
		$contact->populate($_POST);

		$contact->contact_title = substr($contact->contact_title, 0, 9);

		if($contact->contact_department == '')
		    $contact->contact_department = isset($_POST['txtNewDepartment']) ? $_POST['txtNewDepartment'] : '';

		$contact->save($link);

		http_redirect($_SESSION['bc']->getPrevious());
	}
}
?>