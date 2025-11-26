<?php
class ajax_convert_employer implements IAction
{
	public function execute(PDO $link)
	{
		$dpn = isset($_REQUEST['dpn'])?$_REQUEST['dpn']:'';
		$creator = $_SESSION['user']->username;
		$region = $_SESSION['user']->department;

		if ( isset($_REQUEST['emp'])) {
			$query = 'update organisations set zone = "'.$dpn.'" where id = '.$_REQUEST['emp'];
			$st = $link->query($query);
			$org_id = $_REQUEST['emp'];
			$emp_pool_id = DAO::getSingleValue($link, "SELECT auto_id FROM central.emp_pool WHERE dpn = '" . $dpn . "'");
			// insert the crm notes
			$convert_crm_sql = <<<HEREDOC
INSERT INTO crm_notes SELECT NULL, $org_id, name_of_person, position, type_of_contact, subject, date, agreed_action, by_whom, whom_position, priority, audit_info, outcome, timeset, next_action_date, date_created, next_action FROM employerpool_notes WHERE organisation_id = $emp_pool_id;
HEREDOC;
			DAO::execute($link, $convert_crm_sql);
/*
			// update the organisation contact of the CRM (previously the crm contact is attached to the employer pool and now it should now become newly added employer contact)
			$convert_crm_pool_contact_to_org_contact_sql = <<<HEREDOC
UPDATE organisation_contact SET org_id = '$org_id' WHERE org_id = '$emp_pool_id';
HEREDOC;
*/
			// move contacts from prospect_contact to organisation_contact
			$convert_crm_pool_contact_to_org_contact_sql = "INSERT INTO organisation_contact SELECT NULL, $org_id, contact_name, contact_telephone, contact_mobile, contact_title, contact_department, contact_email,'' FROM prospect_contact WHERE org_id = $emp_pool_id;" ;
			DAO::execute($link, $convert_crm_pool_contact_to_org_contact_sql);

			return 1;
		}

		$emp_pool = EmployerPool::loadFromDatabase($link, DAO::getSingleValue($link, "SELECT auto_id FROM central.emp_pool WHERE dpn = '$dpn'"));
		$short_name = substr($emp_pool->company, 0, 19);

//INSERT INTO organisations SELECT NULL, 2, '','',central.emp_pool.company,'','','','','','0','$dpn','$region',1,'','','','',1,'',1,'',1,1,1,'','$creator',1,'','',1,1,1 FROM central.emp_pool WHERE dpn = '$dpn';
		$emp_pool->company = $link->quote($emp_pool->company);
		$short_name = $link->quote($short_name);
		if(!isset($emp_pool->source) || is_null($emp_pool->source) || $emp_pool->source == '')
			$emp_pool->source = 0;
		$query = <<<HEREDOC
INSERT INTO `organisations`
            (`id`,
             `organisation_type`,
             `upin`,
             `ukprn`,
             `legal_name`,
             `trading_name`,
             `short_name`,
             `company_number`,
             `charity_number`,
             `vat_number`,
             `is_training_provider`,
             `zone`,
             `region`,
             `status`,
             `fsm`,
             `code`,
             `notes`,
             `shortcode`,
             `sector`,
             `dealer_group`,
             `manufacturer`,
             `org_type`,
             `workplaces_available`,
             `dealer_participating`,
             `reason_not_participating`,
             `edrs`,
             `creator`,
             `parent_org`,
             `retailer_code`,
             `employer_code`,
             `district`,
             `active`,
             `health_safety`,
             `ono`,
             `lead_referral`,
             `c2_applicable`,
			 `source`)
VALUES
(
NULL,
2,
NULL,
NULL,
$emp_pool->company,
$emp_pool->company,
$short_name,
NULL,
NULL,
NULL,
'0',
'$dpn',
'$region',
1,
NULL,
NULL,
NULL,
NULL,
NULL,
NULL,
NULL,
2,
NULL,
NULL,
NULL,
NULL,
'$creator',
NULL,
NULL,
NULL,
NULL,
1,
NULL,
NULL,
NULL,
NULL,
$emp_pool->source
)
HEREDOC;

		DAO::execute($link, $query);

		$org_id = DAO::getSingleValue($link, "select id from organisations where zone = '$dpn'");
		$new_org_id = DAO::getSingleValue($link, "select auto_id from central.emp_pool where dpn = '$dpn'");
		$add1 = DAO::getSingleValue($link, "select address1 from central.emp_pool where dpn = '$dpn'");
		$add2 = DAO::getSingleValue($link, "select address2 from central.emp_pool where dpn = '$dpn'");
		$add3 = DAO::getSingleValue($link, "select address3 from central.emp_pool where dpn = '$dpn'");
		$add4 = DAO::getSingleValue($link, "select address4 from central.emp_pool where dpn = '$dpn'");
		$add5 = DAO::getSingleValue($link, "select address5 from central.emp_pool where dpn = '$dpn'");
		$postcode = DAO::getSingleValue($link, "select postcode from central.emp_pool where dpn = '$dpn'");
		$telephone = DAO::getSingleValue($link, "select telephone from central.emp_pool where dpn = '$dpn'");
		$contact_name = DAO::getSingleValue($link, "select concat(firstname,' ',surname) from central.emp_pool where dpn = '$dpn'");
		$contact_telephone = DAO::getSingleValue($link, "select telephone from central.emp_pool where dpn = '$dpn'");
		$contact_email = DAO::getSingleValue($link, "select if(email1!='',email1,email2) from central.emp_pool where dpn = '$dpn'");

		$l = new Location();
		$l->organisations_id = $org_id;
		$l->full_name = "Main Site";
		$l->short_name = "Main Site";
		/*		$l->saon_description = $add1;
				$l->paon_description = $add1;
				$l->street_description = $add2;
				$l->locality = $add3;
				$l->town = $add4;
				$l->county = $add5;*/
		$l->address_line_1 = $add1;
		$l->address_line_2 = $add2;
		$l->address_line_3 = $add3;
		$l->address_line_4 = $add4;
		$l->postcode = $postcode;
		$l->telephone = $telephone;
		$l->contact_name = $contact_name;
		$l->contact_telephone = $contact_telephone;
		$l->contact_email = $contact_email;
		$l->save($link);

		// set up the CRM notes

		// insert the record
		$convert_crm_sql = <<<HEREDOC
INSERT INTO crm_notes
			(
			 `id`,
             `organisation_id`,
             `name_of_person`,
             `position`,
             `type_of_contact`,
             `subject`,
             `date`,
             `agreed_action`,
             `by_whom`,
             `whom_position`,
             `priority`,
             `audit_info`,
             `outcome`,
             `timeset`,
             `next_action_date`,
             `date_created`
            )
            SELECT NULL, $org_id, name_of_person, position, type_of_contact, subject, date, agreed_action, by_whom, whom_position, priority, audit_info, outcome, timeset, next_action_date, date_created FROM employerpool_notes WHERE organisation_id = $new_org_id;
HEREDOC;
		DAO::execute($link, $convert_crm_sql);
/*
		// update the organisation contact of the CRM (previously the crm contact is attached to the employer pool and now it should now become newly added employer contact)
		$convert_crm_pool_contact_to_org_contact_sql = <<<HEREDOC
UPDATE organisation_contact SET org_id = '$org_id' WHERE org_id = '$emp_pool->auto_id';
HEREDOC;
		DAO::execute($link, $convert_crm_pool_contact_to_org_contact_sql);
*/
		// move contacts from prospect_contact to organisation_contact
		$convert_crm_pool_contact_to_org_contact_sql = "INSERT INTO organisation_contact SELECT NULL, $org_id, contact_name, contact_telephone, contact_mobile, contact_title, contact_department, contact_email, '' FROM prospect_contact WHERE org_id = $emp_pool->auto_id;" ;
		DAO::execute($link, $convert_crm_pool_contact_to_org_contact_sql);

		// update the employer_pool_contact_email_notes of the CRM to attach to the employer
		$convert_crm_pool_contact_to_org_contact_sql = <<<HEREDOC
UPDATE employer_pool_contact_email_notes SET sunesis_employer_id = '$org_id' WHERE org_id = '$emp_pool->auto_id';
HEREDOC;
		DAO::execute($link, $convert_crm_pool_contact_to_org_contact_sql);

		//shifting the email and event records
		$shift_emails = <<<HEREDOC

INSERT INTO `employer_contact_email_notes`
            (`org_id`,
             `sender_name`,
             `sender_email`,
             `receiver_name`,
             `receiver_email`,
             `date_sent`,
             `time_sent`,
             `subject`,
             `email_body`,
             `email_html_preview`,
             `sent_from_sunesis`)
SELECT
			  $org_id,
             `sender_name`,
             `sender_email`,
             `receiver_name`,
             `receiver_email`,
             `date_sent`,
             `time_sent`,
             `subject`,
             `email_body`,
             `email_html_preview`,
             `sent_from_sunesis`
FROM employer_pool_contact_email_notes WHERE org_id = '$emp_pool->auto_id'
HEREDOC;
		DAO::execute($link, $shift_emails);

		$shift_events = <<<HEREDOC

INSERT INTO `employer_calendar_events_notes`
            (`org_id`,
             `sender_name`,
             `sender_email`,
             `contact_name`,
             `contact_email`,
             `start_date`,
             `start_time`,
             `end_date`,
             `end_time`,
             `location`,
             `subject`,
             `description`,
             `event_uid`,
             `status`,
             `sequence_number`)
SELECT
			  $org_id,
             `sender_name`,
             `sender_email`,
             `contact_name`,
             `contact_email`,
             `start_date`,
             `start_time`,
             `end_date`,
             `end_time`,
             `location`,
             `subject`,
             `description`,
             `event_uid`,
             `status`,
             `sequence_number`
FROM emp_pool_calendar_events_notes WHERE org_id = '$emp_pool->auto_id'
HEREDOC;
		DAO::execute($link, $shift_events);

	}
}
?>