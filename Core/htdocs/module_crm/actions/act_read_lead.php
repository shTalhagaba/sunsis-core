<?php
class read_lead implements IAction
{
	public function execute(PDO $link)
	{
		$id = isset($_REQUEST['id']) ? $_REQUEST['id'] : '';
		if($id == '')
			throw new Exception('Missing querystring argument: Lead id');

		$lead = Lead::loadFromDatabase($link, $id);
		if(is_null($id))
			throw new Exception('Invalid Lead ID');

		$_SESSION['bc']->add($link, "do.php?_action=read_lead&id=".$lead->id, "View Lead");

		$repository = Repository::getRoot().'/crm/lead/'.$lead->id;
		$files = Repository::readDirectory($repository);

		$object = $lead;

		$company = null;
		$company_location = null;
		$company_contact = null;
		if($lead->company_type == "pool")
		{
			$company = EmployerPool::loadFromDatabase($link, $lead->company_id);
			$company_location = DAO::getObject($link, "SELECT * FROM pool_locations WHERE id = '{$lead->company_location_id}'");
			$company_contact = EmployerPoolContacts::loadFromDatabase($link, $lead->main_contact_id);
			$contacts_count = DAO::getSingleValue($link, "SELECT COUNT(*) FROM pool_contact WHERE pool_id = '{$company->id}';");
			$back_to_org = '<span class="btn btn-xs btn-default" onclick="window.location.href=\'do.php?_action=read_pool_organisation&id='.$lead->company_id.'\';"><i class="fa fa-arrow-circle-o-left"></i> Back to Organisation</span>';
		}
		if($lead->company_type == "employer")
		{
			$company = Employer::loadFromDatabase($link, $lead->company_id);
			$company_location = Location::loadFromDatabase($link, $lead->company_location_id);
			$company_contact = OrganisationContact::loadFromDatabase($link, $lead->main_contact_id);
			$contacts_count = DAO::getSingleValue($link, "SELECT COUNT(*) FROM organisation_contact WHERE org_id = '{$company->id}';");
			$back_to_org = '<span class="btn btn-xs btn-default" onclick="window.location.href=\'do.php?_action=read_employer&id='.$lead->company_id.'\';"><i class="fa fa-arrow-circle-o-left"></i> Back to Organisation</span>';
		}

		$activities_count = DAO::getSingleValue($link, "SELECT COUNT(*) FROM crm_activities WHERE entity_type = 'lead' AND entity_id = '{$lead->id}';");
		$comments_count = DAO::getSingleValue($link, "SELECT COUNT(*) FROM crm_entities_comments WHERE entity_type = 'lead' AND entity_id = '{$lead->id}' ORDER BY created ASC;");

		include_once('tpl_read_lead.php');
	}


}