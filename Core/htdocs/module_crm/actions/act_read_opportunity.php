<?php
class read_opportunity implements IAction
{
	public function execute(PDO $link)
	{
		$id = isset($_REQUEST['id']) ? $_REQUEST['id'] : '';
		if($id == '')
			throw new Exception('Missing querystring argument: Opportunity id');

		$opportunity = Opportunity::loadFromDatabase($link, $id);
		if(is_null($id))
			throw new Exception('Invalid Opportunity ID');

		$_SESSION['bc']->add($link, "do.php?_action=read_opportunity&id=".$opportunity->id, "View Opportunity");

		$opportunity_agreements = $opportunity->getEmployerAgreements($link);

		$repository = Repository::getRoot().'/crm/opportunity/'.$opportunity->id;
		$files = Repository::readDirectory($repository);

		$object = $opportunity;

		$company = null;
		$company_location = null;
		$company_contact = null;
		if($opportunity->company_type == "pool")
		{
			$company = EmployerPool::loadFromDatabase($link, $opportunity->company_id);
			$company_location = DAO::getObject($link, "SELECT * FROM pool_locations WHERE id = '{$opportunity->company_location_id}'");
			$company_contact = EmployerPoolContacts::loadFromDatabase($link, $opportunity->main_contact_id);
			$contacts_count = DAO::getSingleValue($link, "SELECT COUNT(*) FROM pool_contact WHERE pool_id = '{$company->id}';");
			$back_to_org = '<span class="btn btn-xs btn-default" onclick="window.location.href=\'do.php?_action=read_pool_organisation&id='.$opportunity->company_id.'\';"><i class="fa fa-arrow-circle-o-left"></i> Back to Organisation</span>';
		}
		if($opportunity->company_type == "employer")
		{
			$company = Employer::loadFromDatabase($link, $opportunity->company_id);
			$company_location = Location::loadFromDatabase($link, $opportunity->company_location_id);
			$company_contact = OrganisationContact::loadFromDatabase($link, $opportunity->main_contact_id);
			$contacts_count = DAO::getSingleValue($link, "SELECT COUNT(*) FROM organisation_contact WHERE org_id = '{$company->id}';");
			$back_to_org = '<span class="btn btn-xs btn-default" onclick="window.location.href=\'do.php?_action=read_employer&id='.$opportunity->company_id.'\';"><i class="fa fa-arrow-circle-o-left"></i> Back to Organisation</span>';
		}

		$activities_count = DAO::getSingleValue($link, "SELECT COUNT(*) FROM crm_activities WHERE entity_type = 'opportunity' AND entity_id = '{$opportunity->id}';");
		$comments_count = DAO::getSingleValue($link, "SELECT COUNT(*) FROM crm_entities_comments WHERE entity_type = 'opportunity' AND entity_id = '{$opportunity->id}' ORDER BY created ASC;");


		include_once('tpl_read_opportunity.php');
	}


}