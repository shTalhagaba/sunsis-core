<?php
class edit_training_record_v2 implements IAction
{
	public function execute(PDO $link)
	{
		$id = isset($_GET['id']) ? $_GET['id'] : '';
		if($id == '')
			throw new Exception("Missing querystring argument: id");

		$tr = TrainingRecord::loadFromDatabase($link, $id);
		if(is_null($tr))
			throw new Exception('Invalid id');

		$_SESSION['bc']->add($link, "do.php?_action=edit_training_record_v2&id={$id}", "Edit Training Record");

		$provider = Organisation::loadFromDatabase($link, $tr->provider_id);

		$course_id = DAO::getSingleValue($link, "SELECT course_id FROM courses_tr WHERE tr_id = '{$tr->id}'");

		$course = Course::loadFromDatabase($link, $course_id);

		$contract = Contract::loadFromDatabase($link, $tr->contract_id);

		$tutor_type = User::TYPE_TUTOR;
		$assessor_type = User::TYPE_ASSESSOR;
		$verifier_type = User::TYPE_VERIFIER;
		$accordinator_type = User::TYPE_APPRENTICE_COORDINATOR;
		$wbccordinator_type = User::TYPE_OTHER_LEARNER; // used for wbcoordinator

		$assessor_of_the_provider = " AND organisations.id = '$provider->id' ";
		if(DB_NAME == "am_superdrug" || DB_NAME == "am_sd_demo")
			$assessor_of_the_provider = " ";

		$tutor_sql = <<<HEREDOC
SELECT
	users.id,
	CONCAT(
		IF(firstnames IS NULL, '', IF(surname IS NULL,firstnames, CONCAT(firstnames,' '))),
		IF(surname IS NULL,'',surname),
		IF(department IS NOT NULL OR job_role IS NOT NULL,
			CONCAT(' (', IF(department IS NOT NULL, IF(job_role IS NOT NULL, CONCAT(department,', ', job_role),department), job_role), ')'), ''),
		' - ',
		users.username
	),
	NULL
FROM
	users
WHERE
	(type = '$tutor_type' AND web_access = 1) OR users.id = '{$tr->tutor}'
ORDER BY CONCAT(firstnames, ' ', surname)
HEREDOC;

		$assessor_sql = <<<HEREDOC
SELECT
	users.id,
	CONCAT(
		IF(firstnames IS NULL, '', IF(surname IS NULL,firstnames, CONCAT(firstnames,' '))),
		IF(surname IS NULL,'',surname),
		IF(department IS NOT NULL OR job_role IS NOT NULL,
			CONCAT(' (', IF(department IS NOT NULL, IF(job_role IS NOT NULL, CONCAT(department,', ', job_role),department), job_role), ')'), ''),
		' - ',
		users.username
	),
	NULL
FROM
	users
INNER JOIN organisations on organisations.id = users.employer_id #{$assessor_of_the_provider}
WHERE (type = '$assessor_type' AND web_access = 1) OR users.id = '{$tr->assessor}'
ORDER BY CONCAT(firstnames, ' ', surname)
HEREDOC;

		$verifier_sql = <<<HEREDOC
SELECT
	users.id,
	CONCAT(
		IF(firstnames IS NULL, '', IF(surname IS NULL,firstnames, CONCAT(firstnames,' '))),
		IF(surname IS NULL,'',surname),
		IF(department IS NOT NULL OR job_role IS NOT NULL,
			CONCAT(' (', IF(department IS NOT NULL, IF(job_role IS NOT NULL, CONCAT(department,', ', job_role),department), job_role), ')'), ''),
		' - ',
		users.username
	),
	NULL
FROM
	users

INNER JOIN organisations on organisations.id = users.employer_id and organisations.id = '{$provider->id}'
WHERE (type = '{$verifier_type}' AND web_access = 1) OR users.id = '{$tr->verifier}'
HEREDOC;

		$acoordinator_sql = <<<HEREDOC
SELECT
	users.id,
	CONCAT(
		IF(firstnames IS NULL, '', IF(surname IS NULL,firstnames, CONCAT(firstnames,' '))),
		IF(surname IS NULL,'',surname),
		IF(department IS NOT NULL OR job_role IS NOT NULL,
			CONCAT(' (', IF(department IS NOT NULL, IF(job_role IS NOT NULL, CONCAT(department,', ', job_role),department), job_role), ')'), ''),
		' - ',
		users.username
	),
	NULL
FROM
	users

INNER JOIN organisations on organisations.id = users.employer_id and organisations.id = '{$provider->id}'
WHERE (type = '{$accordinator_type}' AND web_access = 1) OR users.id = '{$tr->programme}'
HEREDOC;
		if(in_array(DB_NAME, ["am_baltic", "am_baltic_demo"]))
		{
			$acoordinator_sql = <<<HEREDOC
SELECT
	users.id,
	CONCAT(
		IF(firstnames IS NULL, '', IF(surname IS NULL,firstnames, CONCAT(firstnames,' '))),
		IF(surname IS NULL,'',surname),
		IF(department IS NOT NULL OR job_role IS NOT NULL,
			CONCAT(' (', IF(department IS NOT NULL, IF(job_role IS NOT NULL, CONCAT(department,', ', job_role),department), job_role), ')'), ''),
		' - ',
		users.username
	),
	NULL
FROM
	users
WHERE
	users.username IN ('rherdman16', 'lepearson', 'bmilburn', 'nimaxwell', 'opennington', 'ajohnson18')
ORDER BY CONCAT(firstnames, ' ', surname)
HEREDOC;
		}

		$wbcoordinator_sql = <<<HEREDOC
SELECT
	users.id,
	CONCAT(
		IF(firstnames IS NULL, '', IF(surname IS NULL,firstnames, CONCAT(firstnames,' '))),
		IF(surname IS NULL,'',surname),
		IF(department IS NOT NULL OR job_role IS NOT NULL,
			CONCAT(' (', IF(department IS NOT NULL, IF(job_role IS NOT NULL, CONCAT(department,', ', job_role),department), job_role), ')'), ''),
		' - ',
		users.username
	),
	NULL
FROM
	users

INNER JOIN organisations on organisations.id = users.employer_id and organisations.id = '{$provider->id}'
WHERE (type = '{$wbccordinator_type}' AND web_access = 1) OR users.id = '{$tr->wbcoordinator}'
HEREDOC;

		$tutor_select = DAO::getResultset($link, $tutor_sql);
		$assessor_select = DAO::getResultset($link, $assessor_sql);
		$verifier_select = DAO::getResultset($link, $verifier_sql);
		$acoordinator_select = DAO::getResultset($link, $acoordinator_sql);
		$wbcoordinator_select = DAO::getResultset($link, $wbcoordinator_sql);
		$yes_no = array(
			array('0', 'No', ''),
			array('1', 'Yes', '')
		);
		$contracts_select = DAO::getResultset($link,"SELECT id, title, null FROM contracts WHERE contract_year = '{$contract->contract_year}' ORDER BY contract_year DESC, title");

		$sql = <<<SQL
SELECT
  locations.id,
  CONCAT(legal_name, ': ', COALESCE(locations.`full_name`, ''), ' (',COALESCE(`address_line_1`,''),',',COALESCE(`postcode`,''), ')') AS detail,
  organisations.`legal_name`
FROM
  locations INNER JOIN organisations ON locations.`organisations_id` = organisations.`id`
WHERE
  (organisations.`organisation_type` = '2' AND organisations.active = '1') OR (organisations.id = '{$tr->employer_id}')
ORDER BY legal_name, full_name ;
SQL;
		$employers_locations_select = DAO::getResultset($link, $sql);

		$crm_contacts_select = DAO::getResultset($link, "SELECT contact_id, CONCAT(
  COALESCE(contact_name),
  ' (',
  COALESCE(`contact_department`, ''),
  ', ',
  COALESCE(`contact_email`, ''),
  ', ',
  COALESCE(`contact_telephone`, ''),
  ', ',
  COALESCE(`contact_mobile`, ''),
  ')'
), null FROM organisation_contacts WHERE org_id = '{$tr->employer_id}' ORDER BY contact_name");

		$sql = <<<SQL
SELECT
  locations.id,
  CONCAT(legal_name, ', ', COALESCE(locations.`full_name`, ''), ' (',COALESCE(`address_line_1`,''),',',COALESCE(`postcode`,''), ')') AS detail,
  organisations.`legal_name`
FROM
  locations INNER JOIN organisations ON locations.`organisations_id` = organisations.`id`
WHERE
  (organisations.`organisation_type` = '3' AND organisations.active = '1') OR (organisations.id = '{$tr->provider_id}')
ORDER BY legal_name, full_name ;
SQL;
		$provider_locations_select = DAO::getResultset($link, $sql);

		$status_select = [
			["1", "1 The learner is continuing or intending to continue the learning activities"],
			["2", "2 The learner has completed the learning activities"],
			["3", "3 The learner has withdrawn from the learning activities"],
			["4", "4 The learner has transferred to a new learning"],
			["5", "5 Changes in learning within the same programme"],
			["6", "6 Learner has temporarily withdrawn from the aim due to an agreed break in learning"],
		];
		$gender_select = DAO::getResultset($link, "SELECT id, description, null FROM lookup_gender ORDER BY id;");
		$ethnicity_select = DAO::getResultset($link,"SELECT DISTINCT Ethnicity, CONCAT(Ethnicity, ' ', Ethnicity_Desc), NULL FROM lis201314.ilr_ethnicity ORDER BY Ethnicity;");
		$reasons_for_leaving_select = DAO::getResultset($link,"SELECT id, description, NULL FROM lookup_reasons_for_leaving ORDER BY description;");

		$enrollment_no = DAO::getSingleValue($link, "SELECT enrollment_no FROM users WHERE users.username = '{$tr->username}'");

		$showWarningForZProgEmptyEndDate = $this->checkForZprogEndDate($link, $id);

		if(SOURCE_LOCAL || in_array(DB_NAME, ["am_lead_demo", "am_lead"]))
		{
			$coaches_sql = <<<HEREDOC
SELECT
	users.id,
	CONCAT(
		IF(firstnames IS NULL, '', IF(surname IS NULL,firstnames, CONCAT(firstnames,' '))),
		IF(surname IS NULL,'',surname),
		IF(department IS NOT NULL OR job_role IS NOT NULL,
			CONCAT(' (', IF(department IS NOT NULL, IF(job_role IS NOT NULL, CONCAT(department,', ', job_role),department), job_role), ')'), ''),
		' - ',
		users.username
	),
	NULL
FROM
	users
INNER JOIN organisations ON organisations.id = users.employer_id
WHERE users.web_access = 1 AND users.type NOT IN (5, 12)
AND users.`username` NOT IN (SELECT ident FROM acl WHERE resource_category = 'application' AND privilege = 'administrator')
ORDER BY CONCAT(firstnames, ' ', surname)
;
HEREDOC;
			$coaches_select = DAO::getResultset($link, $coaches_sql);
		}

		include('tpl_edit_training_record_v2.php');
	}

	public function checkForZprogEndDate(PDO $link, $id)
	{
		$showWarning = false;
		$showWarning = DAO::getSingleValue($link, "SELECT locate('1',extractvalue(ilr,'/Learner/LearningDelivery/CompStatus'))>0 FROM ilr INNER JOIN contracts ON ilr.`contract_id` = contracts.id WHERE ilr.tr_id = {$id} ORDER BY contracts.`contract_year` DESC, submission DESC LIMIT 0,1; ");
		return $showWarning;
	}
}
