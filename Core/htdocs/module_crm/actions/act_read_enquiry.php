<?php
class read_enquiry implements IAction
{
	public function execute(PDO $link)
	{
		$id = isset($_REQUEST['id']) ? $_REQUEST['id'] : '';
		if($id == '')
			throw new Exception('Missing querystring argument: Lead id');

		$enquiry = Enquiry::loadFromDatabase($link, $id);
		if(is_null($id))
			throw new Exception('Invalid Enquiry ID');

		$_SESSION['bc']->add($link, "do.php?_action=read_enquiry&id=".$enquiry->id, "View Enquiry");

		$repository = Repository::getRoot().'/crm/enquiry/'.$enquiry->id;
		$files = Repository::readDirectory($repository);

		$object = $enquiry;

		$company = null;
		$company_location = null;
		$company_contact = null;
		if($enquiry->company_type == "pool")
		{
			$company = EmployerPool::loadFromDatabase($link, $enquiry->company_id);
			$company_location = DAO::getObject($link, "SELECT * FROM pool_locations WHERE id = '{$enquiry->company_location_id}'");
			$company_contact = EmployerPoolContacts::loadFromDatabase($link, $enquiry->main_contact_id);
			$contacts_count = DAO::getSingleValue($link, "SELECT COUNT(*) FROM pool_contact WHERE pool_id = '{$company->id}';");
			$back_to_org = '<span class="btn btn-xs btn-default" onclick="window.location.href=\'do.php?_action=read_pool_organisation&id='.$enquiry->company_id.'\';"><i class="fa fa-arrow-circle-o-left"></i> Back to Organisation</span>';
		}
		if($enquiry->company_type == "employer")
		{
			$company = Employer::loadFromDatabase($link, $enquiry->company_id);
			$company_location = Location::loadFromDatabase($link, $enquiry->company_location_id);
			$company_contact = OrganisationContact::loadFromDatabase($link, $enquiry->main_contact_id);
			$contacts_count = DAO::getSingleValue($link, "SELECT COUNT(*) FROM organisation_contact WHERE org_id = '{$company->id}';");
			$back_to_org = '<span class="btn btn-xs btn-default" onclick="window.location.href=\'do.php?_action=read_employer&id='.$enquiry->company_id.'\';"><i class="fa fa-arrow-circle-o-left"></i> Back to Organisation</span>';
		}

		$activities_count = DAO::getSingleValue($link, "SELECT COUNT(*) FROM crm_activities WHERE entity_type = 'enquiry' AND entity_id = '{$enquiry->id}';");
		$comments_count = DAO::getSingleValue($link, "SELECT COUNT(*) FROM crm_entities_comments WHERE entity_type = 'enquiry' AND entity_id = '{$enquiry->id}' ORDER BY created ASC;");

		include_once('tpl_read_enquiry.php');
	}


}