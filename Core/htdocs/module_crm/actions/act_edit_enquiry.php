<?php
class edit_enquiry implements IAction
{
	public function execute(PDO $link)
	{
		$id = isset($_REQUEST['id']) ? $_REQUEST['id'] : '';
		$org_id = isset($_REQUEST['org_id']) ? $_REQUEST['org_id'] : '';
		$org_type = isset($_REQUEST['org_type']) ? $_REQUEST['org_type'] : '';

		if (!in_array($org_type, ["pool", "employer"])) {
			throw new Exception("Invalid organisation type");
		}

		if ($id == '') {
			$enquiry = new Enquiry();
			$enquiryStatus = [['1', 'New']];
			$enquiry->company_id = $org_id;
			$enquiry->company_type = $org_type;
		} else {
			$enquiry = Enquiry::loadFromDatabase($link, $id);
			if (is_null($enquiry))
				throw new Exception('Invalid Enquiry ID');
			$enquiryStatus = Enquiry::getDDLEnquiryStatus();
		}

		$_SESSION['bc']->add($link, "do.php?_action=edit_enquiry&id={$enquiry->id}&org_id={$enquiry->company_id}&org_type={$enquiry->company_type}", "Create/Edit Enquiry");

		$organisation = null;
		if ($enquiry->company_type == "pool") 
		{
			$locations_sql = <<<SQL
SELECT
	pool_locations.id,
	CONCAT(pool.`legal_name`, ', ', COALESCE(pool_locations.`full_name`), ' (',COALESCE(`address_line_1`,''),',',COALESCE(`postcode`,''), ')') AS detail,
	NULL
FROM
	pool INNER JOIN pool_locations ON pool.id = pool_locations.`pool_id`
WHERE pool.id = '{$enquiry->company_id}'
ORDER BY pool.`legal_name`, pool_locations.`full_name`
;
SQL;

			$contacts_sql = <<<SQL
SELECT pool_contact.`contact_id`, 
CONCAT( 
	COALESCE(contact_title, ''), ' ',
	COALESCE(contact_name, ''), ', ',
	COALESCE(job_title, ''), ' '
 ) AS detail,
 NULL
FROM pool_contact
WHERE pool_id = '{$enquiry->company_id}'
ORDER BY contact_name
;
SQL;

			$organisation = EmployerPool::loadFromDatabase($link, $enquiry->company_id);
		} 
		elseif ($enquiry->company_type == "employer") 
		{
			$locations_sql = <<<SQL
SELECT
	locations.id,
	CONCAT(organisations.`legal_name`, ', ', COALESCE(locations.`full_name`), ' (',COALESCE(`address_line_1`,''),',',COALESCE(`postcode`,''), ')') AS detail,
	null
FROM
	organisations INNER JOIN locations ON organisations.id = locations.`organisations_id`
WHERE organisations.id = '{$enquiry->company_id}'
ORDER BY organisations.`legal_name`, locations.`full_name`
;
SQL;

			$contacts_sql = <<<SQL
SELECT organisation_contact.`contact_id`, 
CONCAT( 
	COALESCE(contact_title, ''), ' ',
	COALESCE(contact_name, ''), ', ',
	COALESCE(job_title, ''), ' '
 ) AS detail,
 NULL
FROM organisation_contact
WHERE org_id = '{$enquiry->company_id}'
ORDER BY contact_name
;
SQL;

			$organisation = Organisation::loadFromDatabase($link, $enquiry->company_id);
		}
		$company_locations = DAO::getResultset($link, $locations_sql);
		$company_contacts = DAO::getResultset($link, $contacts_sql);


		include_once('tpl_edit_enquiry.php');
	}
}
