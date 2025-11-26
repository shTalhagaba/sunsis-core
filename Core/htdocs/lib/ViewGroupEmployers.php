<?php
class ViewGroupEmployers extends View
{

	public static function getInstance($link)
	{
		$key = 'view_'.__CLASS__;

		if(!isset($_SESSION[$key]))
		{

			$username = $_SESSION['user']->username;
			$parent_org = $_SESSION['user']->employer_id;
			$where = "";

			if(DB_NAME=="am_demo" && ($_SESSION['user']->username == 'peadaradm' || $_SESSION['user']->username == 'jacquia' || $_SESSION['user']->username == 'imanager'))
			{
				$where = " AND (organisations.legal_name LIKE '%imtech%' OR organisations.legal_name LIKE '%AAA_ESSCI%' ) ";
			}
			elseif( (int)$_SESSION['user']->type==7 )
			{
				// default sales person behaviour
				$where = " and organisations.creator='$username' ";
				if ( 1 == DAO::getSingleValue($link, "Select value from configuration where entity='module_recruitment'") ) {

					// only show the department ( sales region ) the sales person has domain over.
					if (isset($_SESSION['user']->department) ) {
						$where = " and organisations.region = '".$_SESSION['user']->department."'";
					}
					// if no department, this sales person can see all organisations
					else
					{
						$where = "";
					}
				}
			}
			elseif( (int)$_SESSION['user']->type==8 || (DB_NAME=="am_lead" && $_SESSION['user']->type == User::TYPE_ORGANISATION_VIEWER) || (DB_NAME=="am_demo" && $_SESSION['user']->username == 'peadaradm') )
			{
				$where .= " and (tr.provider_id=$parent_org or organisations.creator='$username' or organisations.parent_org = '$parent_org' or organisations.id in (select employer_id from users where who_created = '$username'))";
				if(DB_NAME=="am_baltic")
					$where = "";
			}
			elseif( $_SESSION['user']->type==1 && !$_SESSION['user']->is_org_admin && !$_SESSION['user']->isAdmin() )
			{
				$where = " and organisations.id = {$_SESSION['user']->employer_id}";
			}
			elseif($_SESSION['user']->type==3)
			{
				$id = $_SESSION['user']->id;
				//$where = " and organisations.id in (select employer_id from tr where assessor = '$id') ";
				$where = " and (organisations.id in (select employer_id from tr where assessor = '$id') or (organisations.id in (select employer_id from tr where id in (select tr_id from group_members where groups_id in (select id from groups where assessor = '$id'))))) ";
				if($_SESSION['user']->isAdmin())
					$where = '';
			}
			elseif($_SESSION['user']->type==2)
			{
				$where = " and organisations.parent_org=$parent_org";

				if(DB_NAME=='am_lead')
				{
					$username = $_SESSION['user']->username;
					$where = " and organisations.id in (select employer_id from tr where id in (select tr_id from group_members where groups_id in (select id from groups where tutor = '$username')))";
				}
			}
			elseif($_SESSION['user']->type==19)
			{
				$brand = $_SESSION['user']->department;
				if($brand != '')
					$where = " and organisations.manufacturer=$brand";
			}
			elseif($_SESSION['user']->type==20)
			{
				$id = $_SESSION['user']->id;
				if($_SESSION['user']->isAdmin())
					$where = "";
				else
					$where = " and organisations.id in (select employer_id from tr where programme = '$id') ";
			}
			$pl_date = "(SELECT DATE_FORMAT(pl_date, '%d/%m/%Y') FROM health_safety WHERE location_id = locations.id ORDER BY next_assessment DESC LIMIT 1) as pl_date,";
			$pl_insurance = "(SELECT pl_insurance FROM health_safety WHERE location_id = locations.id ORDER BY next_assessment DESC LIMIT 1) as pl_insurance,";

			$account_manager = " (SELECT CONCAT(firstnames,' ',surname) FROM users WHERE users.username = organisations.creator) AS account_manager, ";
			$rating = " ";
			$assessors = "";
			$business_code = " brands.title as business_code, ";

			$due_diligence = "";
			$source = "";
			if(DB_NAME=="ams" || DB_NAME == "am_baltic" || DB_NAME == "am_baltic_demo")
			{
				$due_diligence = "organisations.due_diligence, ";
				$source = " (SELECT description FROM lookup_prospect_source WHERE id = organisations.source) AS source, ";
			}
			$salary_rate = "";
			if(DB_NAME=="am_superdrug")
			{
				$salary_rate = " , CASE
								WHEN organisations.salary_rate = 0 THEN ''
								WHEN organisations.salary_rate = 1 THEN 'Grade 1'
								WHEN organisations.salary_rate = 2 THEN 'Grade 2'
								WHEN organisations.salary_rate = 3 THEN 'Grade 3'
								ELSE ''
							END AS `salary_rate` ";
			}
			$no_of_vacancies = " (select sum(no_of_vacancies) from vacancies where employer_id = organisations.id) as vacancies, ";
			if(SystemConfig::getEntityValue($link, 'module_recruitment_v2'))
				$no_of_vacancies = " (select count(*) from vacancies where employer_id = organisations.id) as vacancies, ";

			// Create new view object
			if(DB_NAME=='am_baltic')
			{
				$sql = <<<HEREDOC
SELECT
	organisations.id as system_id,
	organisations.legal_name as name,

	organisations.edrs,
	locations.full_name AS location_title,
	locations.lsc_number AS store_number,
	locations.address_line_1,
	locations.address_line_2,
	locations.address_line_3,
	locations.address_line_4,
	locations.telephone,
	locations.fax,
	organisations.dealer_participating,
	#brands.title as manufacturer,
	organisations.organisation_type as org_type,
	organisations.manufacturer AS t,
	organisations.company_number,
	(SELECT lookup_employer_size.description FROM lookup_employer_size WHERE lookup_employer_size.code = organisations.code) AS size,
	organisations.district,
    tr.provider_id,
	#(SELECT GROUP_CONCAT(DISTINCT providers.legal_name SEPARATOR "; ") FROM organisations AS providers LEFT JOIN tr ON providers.id = tr.provider_id WHERE tr.employer_id = organisations.id ) AS `provider(s)`,
	CONCAT(IFNULL(locations.address_line_1, ''), ', ', IFNULL(locations.address_line_2, ''), ', ',
		IFNULL(locations.address_line_3, ''), ', ', IFNULL(locations.address_line_4, '')) AS `full_address`,

	$account_manager
	$assessors
	locations.postcode,
	locations.contact_name,
	locations.contact_telephone,
	locations.contact_email,
	(select count(*) from users where type = 5 and employer_id = organisations.id) as no_of_learners,
	(SELECT COUNT(*) FROM tr WHERE tr.status_code = 1 AND tr.employer_id = organisations.id) AS continuing_learners,

	lookup_sector_types.description as sector,
	lookup_sector_types.id as sector_id,
	organisations.parent_org as parent_org,
	organisations.retailer_code, 				# #191 {0000000251} - retailer code for raytheon
	organisations.region,
	organisations.edrs,
	DATE_FORMAT(health_safety.last_assessment, '%d/%m/%Y') AS last_assessment,
	DATE_FORMAT(health_safety.next_assessment, '%d/%m/%Y') AS next_assessment,
	health_safety.`complient` AS comp2,
    (SELECT COUNT(*) FROM complaints WHERE record_id IN (SELECT DISTINCT id FROM tr WHERE employer_id = organisations.id)) AS complaints,
	DATEDIFF(health_safety.next_assessment, CURDATE()) AS timeliness,
	(SELECT DATEDIFF(pl_date, CURDATE()) FROM health_safety WHERE location_id = locations.id ORDER BY pl_date DESC LIMIT 1) as pl_date_timeliness,
	'' AS h_and_s_due_in_two_months,
	health_safety.paperwork_received AS paper,
	health_safety.assessor AS h_and_s_assessor,
	IF(health_safety.age_range=1,'16-18',IF(health_safety.age_range=2,'19-24',IF(health_safety.age_range=3,'25+',''))) AS age_range,
	$due_diligence
	$pl_date
	$pl_insurance
	$source
	CASE
		WHEN organisations.health_safety = 0 THEN '-'
		WHEN health_safety.paperwork_received = 1 THEN "<img  src='/images/green-tick.gif'  border='0'> </img>"
		WHEN health_safety.paperwork_received = 0 THEN "<img  src='/images/red-cross.gif'  border='0'> </img>"
		ELSE "<img src='/images/notstarted.gif' border='0'> </img>"
	END AS `paperwork_received`,
	CASE
		WHEN organisations.health_safety = 0 THEN '-'
		WHEN health_safety.complient = 1 THEN "<img  src='/images/green-tick.gif'  border='0'> </img>"
		WHEN health_safety.complient = 2 THEN "<img  src='/images/red-cross.gif'  border='0'> </img>"
		WHEN health_safety.complient = 3 THEN "<img  src='/images/warning-17.JPG'  border='0'> </img>"
		ELSE "<img src='/images/notstarted.gif' border='0'> </img>"
	END AS `compliant`,
	CASE
		WHEN organisations.health_safety = 0 THEN '-'
		WHEN DATEDIFF(health_safety.next_assessment, CURDATE()) >30 THEN "<img  src='/images/green-tick.gif'  border='0'> </img>"
		WHEN DATEDIFF(health_safety.next_assessment, CURDATE()) <0 THEN "<img  src='/images/red-cross.gif'  border='0'> </img>"
		WHEN DATEDIFF(health_safety.next_assessment, CURDATE()) >=0 AND DATEDIFF(health_safety.next_assessment, CURDATE()) <= 30 THEN "<img  src='/images/warning-17.JPG' border='0'> </img>"
		ELSE "<img src='/images/notstarted.gif' border='0'> </img>"
	END AS `health_and_safety`,

	health_safety.comments AS h_and_s_comments,
	IF(organisations.c2_applicable = 1, "<img  src='/images/green-tick.gif'  border='0'> </img>", "<img  src='/images/red-cross.gif'  border='0'> </img>") as c2_applicable,
	$no_of_vacancies
	organisations.creator,
    organisations.lead_referral,
	organisations.site_employees,
	(IF(organisations.`levy_employer` = 1, 'Yes', 'No')) AS levy_employer,
	organisation_contact.contact_name as learner_manager_name,
	organisation_contact.contact_email as learner_manager_email,
	organisation_contact.contact_telephone as learner_manager_telephone,
	organisation_contact.contact_mobile as learner_manager_mobile
	#tp.legal_name as training_provider
	$rating
	$salary_rate
FROM
	organisations
	INNER JOIN locations ON (locations.organisations_id=organisations.id AND locations.is_legal_address=1)
	#LEFT JOIN health_safety ON health_safety.location_id = locations.id AND health_safety.id = (SELECT id FROM health_safety WHERE location_id=locations.id ORDER BY next_assessment DESC LIMIT 1)

LEFT JOIN 
(SELECT m1.*
FROM health_safety m1 LEFT JOIN health_safety m2
 ON (m1.`location_id` = m2.`location_id` AND m1.id < m2.id)
WHERE m2.id IS NULL) AS health_safety ON health_safety.location_id = locations.id

	#LEFT JOIN users ON users.employer_id = organisations.id and type='5'
	LEFT JOIN tr on tr.employer_id = organisations.id
	LEFT JOIN organisation_contact on organisation_contact.contact_id = tr.crm_contact_id
	LEFT JOIN lookup_sector_types on lookup_sector_types.id = organisations.sector
	LEFT JOIN brands on brands.id = organisations.manufacturer
	LEFT JOIN vacancies on vacancies.employer_id = organisations.id
	#LEFT JOIN complaints on complaints.record_id = tr.id
	#LEFT JOIN organisations as tp on tp.id = organisations.parent_org
WHERE 
	organisations.`organisation_type` = 2
GROUP BY
	organisations.id
HAVING
	(TRUE) $where
HEREDOC;
			}
			elseif(DB_NAME == "am_city_skills")
			{
				$sql = <<<HEREDOC
SELECT
	organisations.id as system_id,
	organisations.legal_name as name,
	organisations.trading_name,
	$business_code
	organisations.edrs,
	locations.full_name AS location_title,
	locations.lsc_number AS store_number,
	locations.address_line_1,
	locations.address_line_2,
	locations.address_line_3,
	locations.address_line_4,
	locations.telephone,
	locations.fax,
	organisations.dealer_participating,
	brands.title as manufacturer,
	brands.title AS group_employer,
	organisations.organisation_type as org_type,
	organisations.manufacturer AS t,
	organisations.company_number,
	(SELECT lookup_employer_size.description FROM lookup_employer_size WHERE lookup_employer_size.code = organisations.code) AS size,
	organisations.district,
    tr.provider_id,
	(SELECT GROUP_CONCAT(DISTINCT providers.legal_name SEPARATOR "; ") FROM organisations AS providers LEFT JOIN tr ON providers.id = tr.provider_id WHERE tr.employer_id = organisations.id ) AS `provider(s)`,
    #(SELECT GROUP_CONCAT(DISTINCT extractvalue(ilr,'/Learner/LearnRefNumber')) FROM ilr INNER JOIN tr ON tr.id = ilr.tr_id WHERE tr.`employer_id` = organisations.id AND LOCATE("<LearnDelFAMCode>132</LearnDelFAMCode>",ilr)>0) AS age_grant_claimed,
	CONCAT(IFNULL(locations.address_line_1, ''), ', ', IFNULL(locations.address_line_2, ''), ', ',
		IFNULL(locations.address_line_3, ''), ', ', IFNULL(locations.address_line_4, '')) AS `full_address`,

	$account_manager
	$assessors
	locations.postcode,
	(SELECT SFA_AreaCostFactor FROM central.`201415postcodeareacost` WHERE Postcode = locations.postcode) AS sfa_area_cost_factor,
    (SELECT EFA_AreaCostFactor FROM central.`201415postcodeareacost` WHERE Postcode = locations.postcode) AS efa_area_cost_factor,
	locations.contact_name,
	locations.contact_telephone,
	locations.contact_email,
	(select count(*) from users where type = 5 and employer_id = organisations.id) as no_of_learners,
	(SELECT COUNT(*) FROM users LEFT JOIN tr ON tr.username = users.username WHERE tr.status_code = 1 AND users.employer_id = organisations.id) AS continuing_learners,

	lookup_sector_types.description as sector,
	lookup_sector_types.id as sector_id,
	organisations.parent_org as parent_org,
	organisations.retailer_code, 				# #191 {0000000251} - retailer code for raytheon
	organisations.region,
	organisations.edrs,
	DATE_FORMAT(health_safety.last_assessment, '%d/%m/%Y') AS last_assessment,
	DATE_FORMAT(health_safety.next_assessment, '%d/%m/%Y') AS next_assessment,
	health_safety.`complient` AS comp2,
	DATEDIFF(health_safety.next_assessment, CURDATE()) AS timeliness,
	#(SELECT DATE_FORMAT(last_assessment, '%d/%m/%Y') FROM health_safety WHERE location_id = locations.id ORDER BY next_assessment DESC LIMIT 1) as last_assessment,
	#(SELECT DATE_FORMAT(next_assessment, '%d/%m/%Y') FROM health_safety WHERE location_id = locations.id ORDER BY next_assessment DESC LIMIT 1) as next_assessment,
	#(SELECT complient FROM health_safety WHERE location_id = locations.id ORDER BY next_assessment DESC LIMIT 1) as comp2,
	#(SELECT DATEDIFF(next_assessment, CURDATE()) FROM health_safety WHERE location_id = locations.id ORDER BY next_assessment DESC LIMIT 1) as timeliness,
	(SELECT DATEDIFF(pl_date, CURDATE()) FROM health_safety WHERE location_id = locations.id ORDER BY pl_date DESC LIMIT 1) as pl_date_timeliness,
	'' AS h_and_s_due_in_two_months,
	health_safety.paperwork_received AS paper,
	health_safety.assessor AS h_and_s_assessor,
	IF(health_safety.age_range=1,'16-18',IF(health_safety.age_range=2,'19-24',IF(health_safety.age_range=3,'25+',''))) AS age_range,
	#(SELECT paperwork_received from health_safety WHERE location_id = locations.id ORDER BY next_assessment DESC LIMIT 1) as paper,
	#(SELECT assessor FROM health_safety WHERE location_id = locations.id ORDER BY next_assessment DESC LIMIT 1) as h_and_s_assessor,
	$due_diligence
	$pl_date
	$pl_insurance
	$source
	#(SELECT IF(age_range=1,'16-18',IF(age_range=2,'19-24',IF(age_range=3,'25+',''))) FROM health_safety WHERE location_id = locations.id ORDER BY next_assessment DESC LIMIT 1) as age_range,

	#CASE
	#	WHEN organisations.health_safety = 0 THEN '-'
	#	WHEN (SELECT paperwork_received FROM health_safety WHERE location_id = locations.id ORDER BY next_assessment DESC LIMIT 1) = 1 THEN "<img  src='/images/green-tick.gif'  border='0'> </img>"
	#	WHEN (SELECT paperwork_received FROM health_safety WHERE location_id = locations.id ORDER BY next_assessment DESC LIMIT 1) = 0 THEN "<img  src='/images/red-cross.gif'  border='0'> </img>"
	#	ELSE "<img src='/images/notstarted.gif' border='0'> </img>"
	#END AS `paperwork_received`,

	CASE
		WHEN organisations.health_safety = 0 THEN '-'
		WHEN health_safety.paperwork_received = 1 THEN "<img  src='/images/green-tick.gif'  border='0'> </img>"
		WHEN health_safety.paperwork_received = 0 THEN "<img  src='/images/red-cross.gif'  border='0'> </img>"
		ELSE "<img src='/images/notstarted.gif' border='0'> </img>"
	END AS `paperwork_received`,

	#CASE
	#	WHEN organisations.health_safety = 0 THEN '-'
	#	WHEN (SELECT complient FROM health_safety WHERE location_id = locations.id ORDER BY next_assessment DESC LIMIT 1) = 1 THEN "<img  src='/images/green-tick.gif'  border='0'> </img>"
	#	WHEN (SELECT complient FROM health_safety WHERE location_id = locations.id ORDER BY next_assessment DESC LIMIT 1) = 2 THEN "<img  src='/images/red-cross.gif'  border='0'> </img>"
	#	WHEN (SELECT complient FROM health_safety WHERE location_id = locations.id ORDER BY next_assessment DESC LIMIT 1) = 3 THEN "<img  src='/images/warning-17.JPG'  border='0'> </img>"
	#	ELSE "<img src='/images/notstarted.gif' border='0'> </img>"
	#END AS `compliant`,

	CASE
		WHEN organisations.health_safety = 0 THEN '-'
		WHEN health_safety.complient = 1 THEN "<img  src='/images/green-tick.gif'  border='0'> </img>"
		WHEN health_safety.complient = 2 THEN "<img  src='/images/red-cross.gif'  border='0'> </img>"
		WHEN health_safety.complient = 3 THEN "<img  src='/images/warning-17.JPG'  border='0'> </img>"
		ELSE "<img src='/images/notstarted.gif' border='0'> </img>"
	END AS `compliant`,

	#CASE
	#	WHEN organisations.health_safety = 0 THEN '-'
	#	WHEN (SELECT DATEDIFF(next_assessment, CURDATE()) FROM health_safety WHERE location_id = locations.id ORDER BY next_assessment DESC LIMIT 1) >30 THEN "<img  src='/images/green-tick.gif'  border='0'> </img>"
	#	WHEN (SELECT DATEDIFF(next_assessment, CURDATE()) FROM health_safety WHERE location_id = locations.id ORDER BY next_assessment DESC LIMIT 1) <0 THEN "<img  src='/images/red-cross.gif'  border='0'> </img>"
	#	WHEN (SELECT DATEDIFF(next_assessment, CURDATE()) FROM health_safety WHERE location_id = locations.id ORDER BY next_assessment DESC LIMIT 1) >=0 and (SELECT DATEDIFF(next_assessment, CURDATE()) FROM health_safety WHERE location_id = locations.id ORDER BY next_assessment DESC LIMIT 1) <= 30 THEN "<img  src='/images/warning-17.JPG' border='0'> </img>"
	#	ELSE "<img src='/images/notstarted.gif' border='0'> </img>"
	#END AS `health_and_safety`,

	CASE
		WHEN organisations.health_safety = 0 THEN '-'
		WHEN DATEDIFF(health_safety.next_assessment, CURDATE()) >30 THEN "<img  src='/images/green-tick.gif'  border='0'> </img>"
		WHEN DATEDIFF(health_safety.next_assessment, CURDATE()) <0 THEN "<img  src='/images/red-cross.gif'  border='0'> </img>"
		WHEN DATEDIFF(health_safety.next_assessment, CURDATE()) >=0 AND (SELECT DATEDIFF(next_assessment, CURDATE()) FROM health_safety WHERE location_id = locations.id ORDER BY next_assessment DESC LIMIT 1) <= 30 THEN "<img  src='/images/warning-17.JPG' border='0'> </img>"
		ELSE "<img src='/images/notstarted.gif' border='0'> </img>"
	END AS `health_and_safety`,

	(SELECT comments FROM health_safety WHERE location_id = locations.id ORDER BY next_assessment DESC LIMIT 1) as h_and_s_comments,
	IF(organisations.c2_applicable = 1, "<img  src='/images/green-tick.gif'  border='0'> </img>", "<img  src='/images/red-cross.gif'  border='0'> </img>") as c2_applicable,
	$no_of_vacancies
    #,group_concat(contracts.title) as contracts,
	organisations.creator,
    organisations.lead_referral,
	organisations.site_employees,
	(IF(organisations.`levy_employer` = 1, 'Yes', 'No')) AS levy_employer,
	tp.legal_name as training_provider,
	CASE
		WHEN forms_audit.id IS NULL THEN "No Form"
		WHEN forms_audit.id IS NOT NULL AND health_safety_form.`signature_employer_font` IS NULL THEN 'Awaiting Employer'
		WHEN health_safety_form.`signature_employer_font` IS NOT NULL AND health_safety_form.`signature_assessor_font` IS NULL THEN "Awaiting Vetting"
		WHEN health_safety_form.`signature_employer_font` IS NOT NULL AND health_safety_form.`signature_assessor_font` IS NOT NULL THEN "Compliant"
	END AS `health_and_safety_form`
	$rating
	$salary_rate
FROM
	organisations
	INNER JOIN locations ON (locations.organisations_id=organisations.id AND locations.is_legal_address=1)
	LEFT JOIN health_safety ON health_safety.location_id = locations.id AND health_safety.id = (SELECT id FROM health_safety WHERE location_id=locations.id ORDER BY id DESC LIMIT 1)
	LEFT JOIN health_safety_form ON health_safety_form.id = health_safety.`id`
	LEFT JOIN forms_audit ON forms_audit.form_id = health_safety.`id` AND description = 'Health & Safety Form Emailed'
	LEFT JOIN users ON users.employer_id = organisations.id and type='5'
	LEFT JOIN tr on users.username = tr.username
	#LEFT JOIN contracts on contracts.id = tr.contract_id
	LEFT JOIN lookup_sector_types on lookup_sector_types.id = organisations.sector
	LEFT JOIN brands on brands.id = organisations.manufacturer
	LEFT JOIN vacancies on vacancies.employer_id = organisations.id
	#LEFT JOIN lookup_employer_size ON lookup_employer_size.code = organisations.code
	LEFT JOIN organisations as tp on tp.id = organisations.parent_org
GROUP BY
	organisations.id
HAVING
	org_type like '%2%' $where
HEREDOC;

			}
			else
			{
				$sql = <<<HEREDOC
SELECT
	organisations.id as system_id,
	organisations.legal_name as name,
	
	organisations.edrs,
	locations.full_name AS location_title,
	locations.lsc_number AS store_number,
	locations.address_line_1,
	locations.address_line_2,
	locations.address_line_3,
	locations.address_line_4,
	locations.telephone,
	locations.fax,
	organisations.dealer_participating,
	#brands.title as manufacturer,
	organisations.organisation_type as org_type,
	organisations.manufacturer AS t,
	organisations.company_number,
	(SELECT lookup_employer_size.description FROM lookup_employer_size WHERE lookup_employer_size.code = organisations.code) AS size,
	organisations.district,
    tr.provider_id,
	#(SELECT GROUP_CONCAT(DISTINCT providers.legal_name SEPARATOR "; ") FROM organisations AS providers LEFT JOIN tr ON providers.id = tr.provider_id WHERE tr.employer_id = organisations.id ) AS `provider(s)`,
    #(SELECT GROUP_CONCAT(DISTINCT extractvalue(ilr,'/Learner/LearnRefNumber')) FROM ilr INNER JOIN tr ON tr.id = ilr.tr_id WHERE tr.`employer_id` = organisations.id AND LOCATE("<LearnDelFAMCode>132</LearnDelFAMCode>",ilr)>0) AS age_grant_claimed,
	CONCAT(IFNULL(locations.address_line_1, ''), ', ', IFNULL(locations.address_line_2, ''), ', ',
		IFNULL(locations.address_line_3, ''), ', ', IFNULL(locations.address_line_4, '')) AS `full_address`,

	$account_manager
	$assessors
	locations.postcode,
	(SELECT SFA_AreaCostFactor FROM central.`201415postcodeareacost` WHERE Postcode = locations.postcode) AS sfa_area_cost_factor,
    (SELECT EFA_AreaCostFactor FROM central.`201415postcodeareacost` WHERE Postcode = locations.postcode) AS efa_area_cost_factor,
	locations.contact_name,
	locations.contact_telephone,
	locations.contact_email,
	(select count(*) from users where type = 5 and employer_id = organisations.id) as no_of_learners,
	(SELECT COUNT(*) FROM tr WHERE tr.status_code = 1 AND tr.employer_id = organisations.id) AS continuing_learners,

	lookup_sector_types.description as sector,
	lookup_sector_types.id as sector_id,
	organisations.parent_org as parent_org,
	organisations.retailer_code, 				# #191 {0000000251} - retailer code for raytheon
	organisations.region,
	organisations.edrs,
	(SELECT DATE_FORMAT(last_assessment, '%d/%m/%Y') FROM health_safety WHERE location_id = locations.id ORDER BY next_assessment DESC LIMIT 1) as last_assessment,
	(SELECT DATE_FORMAT(next_assessment, '%d/%m/%Y') FROM health_safety WHERE location_id = locations.id ORDER BY next_assessment DESC LIMIT 1) as next_assessment,
	(SELECT complient FROM health_safety WHERE location_id = locations.id ORDER BY next_assessment DESC LIMIT 1) as comp2,
	(SELECT DATEDIFF(next_assessment, CURDATE()) FROM health_safety WHERE location_id = locations.id ORDER BY next_assessment DESC LIMIT 1) as timeliness,
	(SELECT DATEDIFF(pl_date, CURDATE()) FROM health_safety WHERE location_id = locations.id ORDER BY pl_date DESC LIMIT 1) as pl_date_timeliness,
	'' AS h_and_s_due_in_two_months,
	(SELECT paperwork_received from health_safety WHERE location_id = locations.id ORDER BY next_assessment DESC LIMIT 1) as paper,
	(SELECT assessor FROM health_safety WHERE location_id = locations.id ORDER BY next_assessment DESC LIMIT 1) as h_and_s_assessor,
	$due_diligence
	$pl_date
	$pl_insurance
	$source
	(SELECT IF(age_range=1,'16-18',IF(age_range=2,'19-24',IF(age_range=3,'25+',''))) FROM health_safety WHERE location_id = locations.id ORDER BY next_assessment DESC LIMIT 1) as age_range,

	CASE
		WHEN organisations.health_safety = 0 THEN '-'
		WHEN (SELECT paperwork_received FROM health_safety WHERE location_id = locations.id ORDER BY next_assessment DESC LIMIT 1) = 1 THEN "<img  src='/images/green-tick.gif'  border='0'> </img>"
		WHEN (SELECT paperwork_received FROM health_safety WHERE location_id = locations.id ORDER BY next_assessment DESC LIMIT 1) = 0 THEN "<img  src='/images/red-cross.gif'  border='0'> </img>"
		ELSE "<img src='/images/notstarted.gif' border='0'> </img>"
	END AS `paperwork_received`,

	CASE
		WHEN organisations.health_safety = 0 THEN '-'
		WHEN (SELECT complient FROM health_safety WHERE location_id = locations.id ORDER BY next_assessment DESC LIMIT 1) = 1 THEN "<img  src='/images/green-tick.gif'  border='0'> </img>"
		WHEN (SELECT complient FROM health_safety WHERE location_id = locations.id ORDER BY next_assessment DESC LIMIT 1) = 2 THEN "<img  src='/images/red-cross.gif'  border='0'> </img>"
		WHEN (SELECT complient FROM health_safety WHERE location_id = locations.id ORDER BY next_assessment DESC LIMIT 1) = 3 THEN "<img  src='/images/warning-17.JPG'  border='0'> </img>"
		ELSE "<img src='/images/notstarted.gif' border='0'> </img>"
	END AS `compliant`,

	CASE
		WHEN organisations.health_safety = 0 THEN '-'
		WHEN (SELECT DATEDIFF(next_assessment, CURDATE()) FROM health_safety WHERE location_id = locations.id ORDER BY next_assessment DESC LIMIT 1) >30 THEN "<img  src='/images/green-tick.gif'  border='0'> </img>"
		WHEN (SELECT DATEDIFF(next_assessment, CURDATE()) FROM health_safety WHERE location_id = locations.id ORDER BY next_assessment DESC LIMIT 1) <0 THEN "<img  src='/images/red-cross.gif'  border='0'> </img>"
		WHEN (SELECT DATEDIFF(next_assessment, CURDATE()) FROM health_safety WHERE location_id = locations.id ORDER BY next_assessment DESC LIMIT 1) >=0 and (SELECT DATEDIFF(next_assessment, CURDATE()) FROM health_safety WHERE location_id = locations.id ORDER BY next_assessment DESC LIMIT 1) <= 30 THEN "<img  src='/images/warning-17.JPG' border='0'> </img>"
		ELSE "<img src='/images/notstarted.gif' border='0'> </img>"
	END AS `health_and_safety`,
	health_safety.comments AS h_and_s_comments,
	IF(organisations.c2_applicable = 1, "<img  src='/images/green-tick.gif'  border='0'> </img>", "<img  src='/images/red-cross.gif'  border='0'> </img>") as c2_applicable,
	$no_of_vacancies
    #,group_concat(contracts.title) as contracts,
	organisations.creator,
    organisations.lead_referral,
	organisations.site_employees,
	(IF(organisations.`levy_employer` = 1, 'Yes', 'No')) AS levy_employer,
	tp.legal_name as training_provider
	$rating
	$salary_rate
FROM
	organisations
	INNER JOIN locations ON (locations.organisations_id=organisations.id AND locations.is_legal_address=1)
	#LEFT JOIN health_safety ON health_safety.location_id = locations.id AND health_safety.id = (SELECT id FROM health_safety WHERE location_id=locations.id ORDER BY id DESC LIMIT 1)

LEFT JOIN 
(SELECT m1.*
FROM health_safety m1 LEFT JOIN health_safety m2
 ON (m1.`location_id` = m2.`location_id` AND m1.id < m2.id)
WHERE m2.id IS NULL) AS health_safety ON health_safety.location_id = locations.id

	LEFT JOIN users ON users.employer_id = organisations.id and type='5'
	LEFT JOIN tr on users.username = tr.username
	#LEFT JOIN contracts on contracts.id = tr.contract_id
	LEFT JOIN lookup_sector_types on lookup_sector_types.id = organisations.sector
	#LEFT JOIN brands on brands.id = organisations.manufacturer
	#LEFT JOIN vacancies on vacancies.employer_id = organisations.id
	#LEFT JOIN lookup_employer_size ON lookup_employer_size.code = organisations.code
	LEFT JOIN organisations as tp on tp.id = organisations.parent_org
	LEFT JOIN taggables ON (taggables.taggable_id = organisations.id AND taggables.taggable_type = 'Employer')
GROUP BY
	organisations.id
HAVING
	org_type like '%2%' $where
HEREDOC;

			}



			$view = $_SESSION[$key] = new ViewGroupEmployers();
			$view->setSQL($sql);

			// Add view filters
			$options = array(
				0=>array(20,20,null,null),
				1=>array(50,50,null,null),
				2=>array(100,100,null,null),
				3=>array(200,200,null,null),
				4=>array(0, 'No limit', null, null));
			$f = new DropDownViewFilter(View::KEY_PAGE_SIZE, $options, 20, false);
			$f->setDescriptionFormat("Records per page: %s");
			$view->addFilter($f);

			$options = array(
				0=>array(1, 'Company name (asc)', null, 'ORDER BY organisations.legal_name'),
				1=>array(2, 'Company name (desc)', null, 'ORDER BY organisations.legal_name DESC'),
				2=>array(3, 'Location (asc), Provider name (asc)', null, 'ORDER BY organisations.address_line_3, organisations.address_line_2, organisations.legal_name'),
				3=>array(4, 'Location (desc), Provider name (desc)', null, 'ORDER BY organisations.address_line_3 DESC, organisations.address_line_2 DESC, organisations.legal_name DESC'));
			$f = new DropDownViewFilter('order_by', $options, 1, false);
			$f->setDescriptionFormat("Sort by: %s");
			$view->addFilter($f);

			$options = array(
				0=>array(1, 'With and without learners ', null, 'having (select count(users.surname) from users where users.employer_id = organisations.id and users.type = 5)>=0'),
				1=>array(2, 'With learners ', null, 'having (select count(users.surname) from users where users.employer_id = organisations.id and users.type = 5)>0'),
				2=>array(3, 'Without learners ', null, 'having (select count(users.surname) from users where users.employer_id = organisations.id and users.type = 5)=0'));
			$f = new DropDownViewFilter('by_apprentices', $options, 1, false);
			$f->setDescriptionFormat("Learners: %s");
			$view->addFilter($f);

			$options = array(
				0=>array(1, 'Yes ', null, ' WHERE organisations.`levy_employer` = "1" '),
				1=>array(2, 'No ', null, ' WHERE organisations.`levy_employer` = "0" '));
			$f = new DropDownViewFilter('filter_levy_employer', $options, '', true);
			$f->setDescriptionFormat("Levy Employer: %s");
			$view->addFilter($f);

			$options = array(
				0=>array(1, 'With and without notes ', NULL, 'HAVING (SELECT COUNT(*) FROM crm_notes WHERE crm_notes.organisation_id = organisations.id) >= 0'),
				1=>array(2, 'With notes ', NULL, 'HAVING (SELECT COUNT(*) FROM  crm_notes WHERE crm_notes.organisation_id = organisations.id ) > 0'),
				2=>array(3, 'Without notes ', NULL, 'HAVING (SELECT COUNT(*) FROM crm_notes WHERE crm_notes.organisation_id = organisations.id ) = 0'));
			$f = new DropDownViewFilter('by_crm_notes', $options, 1, false);
			$f->setDescriptionFormat("CRM Notes: %s");
			$view->addFilter($f);

			$options = array(
				0=>array(1, 'With and without continuing learners ', null, 'having (select count(tr.surname) from tr where tr.status_code = 1 and tr.employer_id = organisations.id)>=0'),
				1=>array(2, 'With continuing learners ', null, 'having (select count(tr.surname) from tr where tr.status_code = 1 and tr.employer_id = organisations.id)>0'),
				2=>array(3, 'Without continuing learners ', null, 'having (select count(tr.surname) from tr where tr.status_code = 1 and tr.employer_id = organisations.id)=0'));
			$f = new DropDownViewFilter('by_cont_trs', $options, 1, false);
			$f->setDescriptionFormat("Continuing learners: %s");
			$view->addFilter($f);

			$options = array(
				0=>array(1, 'With and without health and safety tracking ', null, null),
				1=>array(2, 'With health and safety ', null, 'where  organisations.health_safety=1'),
				2=>array(3, 'Without health and safety ', null, 'where organisations.health_safety<>1'));
			$f = new DropDownViewFilter('by_hands', $options, 1, false);
			$f->setDescriptionFormat("H&S tracking: %s");
			$view->addFilter($f);

			$options = array(
				0=>array(1, 'With and without H&S paperwork received ', null, null),
				1=>array(2, 'With H&S paperwork received ', null, ' having paper=1'),
				2=>array(3, 'Without H&S paperwork received ', null, ' having paper=0'));
			$f = new DropDownViewFilter('by_paperwork', $options, 1, false);
			$f->setDescriptionFormat("H&S Paperwork: %s");
			$view->addFilter($f);

			// #172 - relmes terminology change
			if ( DB_NAME == 'am_lcpa_test' ) {
				$options = array(
					0=>array(1, 'All Theatres', null, null),
					1=>array(2, 'Active Theatres', null, 'where  organisations.active=1'),
					2=>array(3, 'Inactive Theatres', null, 'where organisations.active<>1'));
				$f = new DropDownViewFilter('by_active', $options, 2, false);
				$f->setDescriptionFormat("Active: %s");
				$view->addFilter($f);
			}
			else {
				$options = array(
					0=>array(1, 'All Employers', null, null),
					1=>array(2, 'Active Employers', null, 'where  organisations.active=1'),
					2=>array(3, 'Inactive Employers', null, 'where organisations.active<>1'));
				$f = new DropDownViewFilter('by_active', $options, 2, false);
				$f->setDescriptionFormat("Active: %s");
				$view->addFilter($f);
			}

			$f = new TextboxViewFilter('filter_name', "WHERE organisations.legal_name LIKE '%%%s%%'", null);
			$f->setDescriptionFormat("Name: %s");
			$view->addFilter($f);

			$f = new TextboxViewFilter('filter_retailer_code', "WHERE organisations.retailer_code LIKE '%%%s%%'", null);
			$f->setDescriptionFormat("Retailer Code: %s");
			$view->addFilter($f);

			$f = new TextboxViewFilter('filter_edrs', "WHERE organisations.edrs LIKE '%%%s%%'", null);
			$f->setDescriptionFormat("EDRS: %s");
			$view->addFilter($f);

			// Lead Referral Filter
			$f = new TextboxViewFilter('filter_lead_referral', "WHERE organisations.lead_referral LIKE '%%%s%%'", null);
			$f->setDescriptionFormat("Lead Referral: %s");
			$view->addFilter($f);

			$f = new TextboxViewFilter('filter_postcode', "WHERE locations.postcode LIKE '%%%s%%'", null);
			$f->setDescriptionFormat("Postcode: %s");
			$view->addFilter($f);

			if(DB_NAME=="ams" || DB_NAME=="am_baltic" || DB_NAME=="am_baltic_demo")
			{
				$f = new TextboxViewFilter('filter_by_p_contact_name', "WHERE locations.contact_name LIKE '%%%s%%' ", null);
				$f->setDescriptionFormat("Filter by Name: %s");
				$view->addFilter($f);

				$f = new TextboxViewFilter('filter_by_p_contact_tel', "WHERE locations.contact_telephone LIKE '%%%s%%' ", null);
				$f->setDescriptionFormat("Filter by Telephone: %s");
				$view->addFilter($f);

				$f = new TextboxViewFilter('filter_by_p_contact_mob', "WHERE locations.contact_mobile LIKE '%%%s%%' ", null);
				$f->setDescriptionFormat("Filter by Mobile: %s");
				$view->addFilter($f);

				$f = new TextboxViewFilter('filter_by_p_contact_email', "WHERE locations.contact_email LIKE '%%%s%%' ", null);
				$f->setDescriptionFormat("Filter by Email: %s");
				$view->addFilter($f);

				$f = new TextboxViewFilter('filter_by_c_contact_name', "WHERE organisations.id IN (SELECT org_id FROM organisation_contact WHERE contact_name LIKE '%%%s%%') ", null);
				$f->setDescriptionFormat("Filter by Name: %s");
				$view->addFilter($f);

				$f = new TextboxViewFilter('filter_by_c_contact_tel', "WHERE organisations.id IN (SELECT org_id FROM organisation_contact WHERE contact_telephone LIKE '%%%s%%') ", null);
				$f->setDescriptionFormat("Filter by Telephone: %s");
				$view->addFilter($f);

				$f = new TextboxViewFilter('filter_by_c_contact_mob', "WHERE organisations.id IN (SELECT org_id FROM organisation_contact WHERE contact_mobile LIKE '%%%s%%') ", null);
				$f->setDescriptionFormat("Filter by Mobile: %s");
				$view->addFilter($f);

				$f = new TextboxViewFilter('filter_by_c_contact_email', "WHERE organisations.id IN (SELECT org_id FROM organisation_contact WHERE contact_email LIKE '%%%s%%') ", null);
				$f->setDescriptionFormat("Filter by Email: %s");
				$view->addFilter($f);

				$options = array(
					0=>array(1, 'Select All', null, null),
					1=>array(2, 'With Due Diligence', null, 'where  organisations.due_diligence=1'),
					2=>array(3, 'Without Due Diligence', null, 'where organisations.due_diligence IS NULL OR organisations.due_diligence<>1'));
				$f = new DropDownViewFilter('filter_by_due_diligence', $options, 1, false);
				$f->setDescriptionFormat("Due Diligence: %s");
				$view->addFilter($f);

				// Creation Date Filter
				$format = "WHERE vacancies.created >= '%s'";
				$f = new DateViewFilter('filter_from_creation_date', $format, '');
				$f->setDescriptionFormat("From Creation Date: %s");
				$view->addFilter($f);

				$format = "WHERE vacancies.created <= '%s'";
				$f = new DateViewFilter('filter_to_creation_date', $format, '');
				$f->setDescriptionFormat("To Creation Date: %s");
				$view->addFilter($f);

				$sources = "SELECT id, description, NULL, CONCAT('WHERE organisations.source = ',CHAR(39),id,CHAR(39)) FROM lookup_prospect_source ORDER BY description";
				$f = new DropDownViewFilter('filter_source', $sources, null, true);
				$f->setDescriptionFormat("Source: %s");
				$view->addFilter($f);
			}

			$options = array(
				0=>array(1, 'All', null, 'having organisations.id IS NOT NULL'),
				1=>array(2, 'Due more than 1 month', null, 'having timeliness > 30'),
				2=>array(3, 'Due within 1 month', null, 'having timeliness <= 30 and timeliness >= 0'),
				3=>array(4, 'Overdue', null, 'having timeliness < 0'));
			$f = new DropDownViewFilter('by_health_safety_timeliness', $options, 1, false);
			$f->setDescriptionFormat("H&S Timeliness: %s");
			$view->addFilter($f);

			$regions = "SELECT description, description, NULL, CONCAT('WHERE organisations.region = ',CHAR(39),description,CHAR(39)) FROM lookup_vacancy_regions ORDER BY description";
			$f = new DropDownViewFilter('filter_region', $regions, null, true);
			$f->setDescriptionFormat("Region is: %s");
			$view->addFilter($f);

			if(DB_NAME == "am_pathway")
			{
				$account_managers = "SELECT id, description, NULL, CONCAT('WHERE organisations.creator = ',CHAR(39),id,CHAR(39)) FROM lookup_account_managers ORDER BY description";
				$f = new DropDownViewFilter('filter_account_manager', $account_managers, null, true);
				$f->setDescriptionFormat("Account Manager: %s");
				$view->addFilter($f);

				$options = array(
					0=>array(0, 'Show all', null, null),
					1=>array(1, 'Red', null, ' WHERE organisations.rating = 1 '),
					2=>array(2, 'Amber', null, ' WHERE organisations.rating = 2 '),
					3=>array(3, 'Green', null, ' WHERE organisations.rating = 3 '),
					4=>array(4, 'Gold', null, ' WHERE organisations.rating = 4 '));
				$f = new DropDownViewFilter('filter_rating', $options, 0, false);
				$f->setDescriptionFormat("Employer Rating: %s");
				$view->addFilter($f);
			}

			$f = new TextboxViewFilter('filter_from_employees', "WHERE organisations.site_employees >= '%s'", null);
			$f->setDescriptionFormat("From Number of Employees: %s");
			$view->addFilter($f);

			$f = new TextboxViewFilter('filter_to_employees', "WHERE organisations.site_employees <= '%s'", null);
			$f->setDescriptionFormat("To Number of Employees: %s");
			$view->addFilter($f);

			$options = array(
				0=>array(1, 'All', null, 'having organisations.id IS NOT NULL'),
				1=>array(2, 'Compliant', null, 'having comp2=1'),
				2=>array(3, 'Non-compliant', null, 'having comp2=2'),
				3=>array(4, 'Outstanding action', null, 'having comp2=3'),
				4=>array(5, 'Never been assessed', null, 'having last_assessment is null'));
			$f = new DropDownViewFilter('by_health_safety_compliance', $options, 1, false);
			$f->setDescriptionFormat("H&S compliance: %s");
			$view->addFilter($f);

			$options = array(
				0=>array(1, 'With or Without Vacancies', null, null),
				1=>array(2, 'With Vacancies', null, 'having vacancies > 0'),
				2=>array(3, 'Without Vacancies', null, 'having vacancies is null'));
			$f = new DropDownViewFilter('by_vacancies', $options, 1, false);
			$f->setDescriptionFormat("Vacancies: %s");
			$view->addFilter($f);

			$options = array(
				0=>array(1, 'With or Without EDRS', null, null),
				1=>array(2, 'With EDRS', null, ' WHERE organisations.edrs IS NOT NULL OR organisations.edrs != "" '),
				2=>array(3, 'Without EDRS', null, ' WHERE organisations.edrs IS NULL OR organisations.edrs = "" '));
			$f = new DropDownViewFilter('filter_by_edrs_present', $options, 1, false);
			$f->setDescriptionFormat("With or without EDRS: %s");
			$view->addFilter($f);

			$options = 'SELECT DISTINCT id, description, null, CONCAT("having sector_id=",id) FROM lookup_sector_types';
			$f = new DropDownViewFilter('filter_sector', $options, null, true);
			$f->setDescriptionFormat("Sector: %s");
			$view->addFilter($f);

			if($_SESSION['user']->isAdmin() || $_SESSION['user']->type==8 || $_SESSION['user']->type==11)
				$options = 'SELECT id, legal_name, null, CONCAT("having parent_org=",id) FROM organisations where organisation_type = 3';
			else
				$options = 'SELECT id, legal_name, null, CONCAT("having parent_org=",id) FROM organisations where organisation_type = 3 and organisations.id = ' . $_SESSION['user']->employer_id;
			$f = new DropDownViewFilter('filter_provider', $options, null, true);
			$f->setDescriptionFormat("Delivery Partner: %s");
			$view->addFilter($f);

			if(DB_NAME == "am_siemens_demo" || DB_NAME == "am_siemens")
			{
				$options = <<<OPTIONS
SELECT
  brands.id,
  brands.title,
  NULL,
  CONCAT(
    "WHERE organisations.id IN (",
    GROUP_CONCAT(employer_business_codes.`employer_id`),
    ")"
  )
FROM
  brands
  LEFT JOIN employer_business_codes
    ON employer_business_codes.brands_id = brands.id
    GROUP BY brands.id
ORDER BY brands.title ;
OPTIONS;

				$f = new DropDownViewFilter('filter_manufacturer', $options, null, true);
				$f->setDescriptionFormat("Manufacturer: %s");
				$view->addFilter($f);
			}
			else
			{
				$options = 'SELECT id, title, null, CONCAT("having organisations.manufacturer=",id) FROM brands ORDER BY title';
				$f = new DropDownViewFilter('filter_manufacturer', $options, null, true);
				$f->setDescriptionFormat("Manufacturer: %s");
				$view->addFilter($f);
			}

			$options = 'SELECT DISTINCT address_line_3, address_line_3, null, CONCAT("having address_line_3=",CHAR(39),address_line_3,CHAR(39)) FROM locations INNER JOIN organisations ON organisations.id = locations.organisations_id WHERE organisations.organisation_type = 2 AND locations.is_legal_address = 1 order by locations.address_line_3';
			$f = new DropDownViewFilter('filter_address_line_3', $options, null, true);
			$f->setDescriptionFormat("Address line 3: %s");
			$view->addFilter($f);

			$options = 'SELECT DISTINCT address_line_4, address_line_4, null, CONCAT("having address_line_4=",CHAR(39),address_line_4,CHAR(39)) FROM locations INNER JOIN organisations ON organisations.id = locations.organisations_id WHERE organisations.organisation_type = 2 AND locations.is_legal_address = 1 order by locations.address_line_4';
			$f = new DropDownViewFilter('filter_county', $options, null, true);
			$f->setDescriptionFormat("Address line 4: %s");
			$view->addFilter($f);

			$options = 'SELECT DISTINCT district, district, null, CONCAT("having district=",CHAR(39),district,CHAR(39)) FROM organisations WHERE organisations.organisation_type = 2 order by district';
			$f = new DropDownViewFilter('filter_district', $options, null, true);
			$f->setDescriptionFormat("District: %s");
			$view->addFilter($f);

			$options = 'SELECT DISTINCT id, description, null, CONCAT("where organisations.id in ( select org_id from organisations_status where org_status = ",id,")") FROM lookup_crm_regarding ORDER BY description';
			$f = new DropDownViewFilter('filter_crmstatus', $options, null, true);
			$f->setDescriptionFormat("CRM Status: %s");
			$view->addFilter($f);

			// Start Date Filter
			$format = "WHERE pl_date >= '%s'";
			$f = new DateViewFilter('start_date', $format, '');
			$f->setDescriptionFormat("From PL date: %s");
			$view->addFilter($f);

			$format = "WHERE pl_date <= '%s'";
			$f = new DateViewFilter('end_date', $format, '');
			$f->setDescriptionFormat("To PL date: %s");
			$view->addFilter($f);

			$options = array(
				0=>array(1, 'All', null, 'having organisations.id IS NOT NULL'),
				1=>array(2, 'Due more than 1 month', null, 'having pl_date_timeliness > 30'),
				2=>array(3, 'Due within 1 month', null, 'having pl_date_timeliness <= 30 and pl_date_timeliness >= 0'));
			$f = new DropDownViewFilter('by_pl_date_timeliness', $options, 1, false);
			$f->setDescriptionFormat("PL Date Timeliness: %s");
			$view->addFilter($f);

			$options = "SELECT id, title, null, CONCAT('WHERE tr.contract_id=',id) FROM contracts where active = 1 order by contract_year desc, title";
			$f = new DropDownViewFilter('filter_contract', $options, null, true);
			$f->setDescriptionFormat("Contract: %s");
			$view->addFilter($f);

			if(DB_NAME == "am_duplex")
			{
				$format = "WHERE organisations.created_at >= '%s'";
				$f = new DateViewFilter('from_created_date', $format, '');
				$f->setDescriptionFormat("From created date: %s");
				$view->addFilter($f);
				$format = "WHERE organisations.created_at <= '%s'";
				$f = new DateViewFilter('to_created_date', $format, '');
				$f->setDescriptionFormat("To created date: %s");
				$view->addFilter($f);
			}

			$options = "SELECT DISTINCT tags.`id`, tags.`name`, NULL, CONCAT('WHERE taggables.tag_id=', tags.`id`) FROM tags WHERE tags.type = 'Employer' ORDER BY tags.`name`";
			$f = new DropDownViewFilter('filter_tag', $options, null, true);
			$f->setDescriptionFormat("Tag: %s");
			$view->addFilter($f);

		}
		return $_SESSION[$key];
	}

	/*
	 public function getSizeDescription(PDO $link)
	 {
		 $st = Employer::getEmployerSizeDescription($link);
		 $sizeDesc = array();
		 while($row = $st->fetch())
		 {
			 $sizeDesc[$row['code']] = $row['description'];
		 }
		 return $sizeDesc;
	 }
 */
	public function render(PDO $link, $columns)
	{
		/* @var $result pdo_result */
		$st = $link->query($this->getSQL());
		if($st)
		{
			echo $this->getViewNavigator();
			echo '<div align="center"><table class="resultset sortData" id="dataMatrix" border="0" cellspacing="0" cellpadding="4">';
			echo '<thead><tr><th>&nbsp;</th>';

			foreach($columns as $column)
			{
				//echo '<th>' . ucwords(str_replace("_"," ",str_replace("_and_"," & ",$column))) . '</th>';
				echo '<th class="rowData">' . ucwords(str_replace("_"," ",$column)) . '</th>';
			}

			//$sizeDesc = $this->getSizeDescription($link) ;


			echo '<tbody>';
			while($row = $st->fetch())
			{
				#(SELECT COUNT(*) FROM users LEFT JOIN tr ON tr.username = users.username WHERE tr.status_code = 1 AND users.employer_id = organisations.id) AS continuing_learners,
				/*
				 if(isset($sizeDesc[$row['size']]))
					 $row['size'] = $sizeDesc[$row['size']];
				 else
					 $row['size'] = '';
			 */
				//$row['continuing_learners'] = DAO::getSingleValue($link, "SELECT COUNT(*) FROM users LEFT JOIN tr ON tr.username = users.username WHERE tr.status_code = 1 AND users.employer_id = {$row['system_id']}");
				//$row['no_of_learners'] = DAO::getSingleValue($link, "select count(*) from users where type =5 and employer_id = {$row['system_id']}");
				if(DB_NAME == "am_baltic_demo" || DB_NAME=="am_baltic")
					echo HTML::viewrow_opening_tag('/do.php?_action=baltic_read_employer&id=' . $row['system_id']);
				elseif(SystemConfig::getEntityValue($link, 'module_recruitment_v2') && DB_NAME != "am_demo" && DB_NAME != "am_presentation")
					echo HTML::viewrow_opening_tag('/do.php?_action=rec_read_employer&id=' . $row['system_id']);
				else
					echo HTML::viewrow_opening_tag('/do.php?_action=read_employer&id=' . $row['system_id']);
				echo '<td><img src="/images/blue-building.png" width="25" height="30" border="0" /></td>';

				foreach($columns as $column)
				{
					if($column=='name' || $column=='full_address')
						echo '<td align="left">' . ((isset($row[$column]))?(($row[$column]=='')?'&nbsp':$row[$column]):'&nbsp') . '</td>';
					elseif($column=='h_and_s_due_in_two_months')
					{
						if($row['timeliness'] <= 60 and $row['timeliness'] > 0)
							echo '<td align="center" bgcolor="#00FF00">Yes</td>';
						else
							echo '<td align="center">&nbsp;</td>';
					}
					else
						echo '<td align="center">' . ((isset($row[$column]))?(($row[$column]=='')?'&nbsp':$row[$column]):'&nbsp') . '</td>';
				}

				echo '</tr>';
			}

			echo '</tbody></table></div>';
			echo $this->getViewNavigator();

		}
		else
		{
			throw new DatabaseException($link, $this->getSQL());
		}

	}


}
?>
