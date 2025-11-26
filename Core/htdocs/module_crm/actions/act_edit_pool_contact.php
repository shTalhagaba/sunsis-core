<?php
class edit_pool_contact implements IAction
{
	public function execute(PDO $link)
	{
		$contact_id = isset($_GET['contact_id']) ? $_GET['contact_id'] : '';
		$pool_id = isset($_GET['pool_id']) ? $_GET['pool_id'] : '';
		$org_type = isset($_GET['org_type']) ? $_GET['org_type'] : '';
		$subaction = isset($_GET['subaction']) ? $_GET['subaction'] : '';

        if($pool_id == '')
        {
            throw new Exception("Missing querystring argument: pool_id");
        }
		if($subaction == 'delete_learner_crm_contact')
		{
			$this->delete_learner_crm_contact($link);
		}

		$_SESSION['bc']->add($link, "do.php?_action=edit_pool_contact&contact_id={$contact_id}&pool_id={$pool_id}&org_type={$org_type}", "Add/ Edit Pool Contact");

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
			9=>array(9,'Training Manager',null,null)
		);

		$job_roles_ddl = DAO::getResultset($link, "SELECT id, description, null FROM lookup_job_roles WHERE cat = 'CRM Contact' ORDER BY description");

		if($contact_id == '')
		{
            $vo = new EmployerPoolContacts();
            $vo->pool_id = $pool_id;

		}
		else
		{
			$vo = DAO::getObject($link, "SELECT * FROM pool_contact WHERE contact_id = '{$contact_id}'");
		}

		$pool = EmployerPool::loadFromDatabase($link, $vo->pool_id);

		include('tpl_edit_pool_contact.php');
	}

	public function delete_learner_crm_contact(PDO $link)
	{
		$contact_id = isset($_GET['contact_id']) ? $_GET['contact_id'] : '';
		$vo = EmployerPoolContacts::loadFromDatabase($link, $contact_id);
		$vo->delete($link);

		http_redirect($_SESSION['bc']->getPrevious());
	}
}