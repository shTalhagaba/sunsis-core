<?php
class edit_crm_contact implements IAction
{
	public function execute(PDO $link)
	{
		$contact_id = isset($_GET['contact_id']) ? $_GET['contact_id'] : '';
		$org_id = isset($_GET['org_id']) ? $_GET['org_id'] : '';
		$org_type = isset($_GET['org_type']) ? $_GET['org_type'] : '';
		$subaction = isset($_GET['subaction']) ? $_GET['subaction'] : '';

		if($subaction == 'delete_learner_crm_contact')
		{
			$this->delete_learner_crm_contact($link);
		}

		if(!isset($_REQUEST['push']))
			$_SESSION['bc']->add($link, "do.php?_action=edit_crm_contact&contact_id={$contact_id}&org_id={$org_id}", "Add/ Edit CRM Contact");

        $job_roles = array(
            0=>array(0, 'Admin', null, null),
            1=>array(1, 'Line Manager/ Supervisor',null,null),
            2=>array(2,'HE',null,null),
            3=>array(3,'Finance',null,null),
			4=>array(4,'Levy Contact',null,null),
			5=>array(5,'Apprentice Coordinator',null,null),
			6=>array(6,'HR Manager',null,null),
			7=>array(7,'HR Adviser',null,null),
			8=>array(8,'L & D Manager',null,null),
			9=>array(9,'Training Manager',null,null),
			10=>array(10,'Secondary Mentor (Pastoral Care)',null,null),
			11=>array(11,'Digital Accountant / ACCM',null,null)
        );

		$job_roles_ddl = DAO::getResultset($link, "SELECT id, description, null FROM lookup_job_roles WHERE cat = 'CRM Contact' ORDER BY description");

		if($contact_id == '')
		{
			$vo = new OrganisationCRMContact();
			$vo->org_id = $org_id;
		}
		else
		{
			$vo = OrganisationCRMContact::loadFromDatabase($link, $contact_id);
		}

		if( DB_NAME == "am_baltic" && ($vo->contact_id == '' || $vo->contact_id > 15431) )
		{
			$job_roles = [
				[1, 'Line Manager/ Supervisor'],
				[3,'Finance'],
				[10,'Secondary Mentor (Pastoral Care)'],
				[11,'Digital Accountant / ACCM']
			];
		}

		$organisation = Organisation::loadFromDatabase($link, $vo->org_id);

		include('tpl_edit_crm_contact.php');
	}

	public function delete_learner_crm_contact(PDO $link)
	{
		$contact_id = isset($_GET['contact_id']) ? $_GET['contact_id'] : '';
		$vo = OrganisationCRMContact::loadFromDatabase($link, $contact_id);
		$vo->delete($link);

		http_redirect($_SESSION['bc']->getPrevious());
	}
}