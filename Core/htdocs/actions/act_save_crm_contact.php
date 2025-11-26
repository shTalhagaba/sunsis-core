<?php
class save_crm_contact implements IAction
{
	public function execute(PDO $link)
	{
		$contact = new OrganisationCRMContact();
		$contact->populate($_POST);

		if(isset($_REQUEST['left_employer']))
			$contact->left_employer = 1;
		else
			$contact->left_employer = 0;

		$contact->save($link);

		http_redirect($_SESSION['bc']->getPrevious());
	}
}
?>