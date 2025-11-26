<?php
class add_ob_learners implements IAction
{
	public function execute(PDO $link)
	{
		$subaction = isset($_REQUEST['subaction'])?$_REQUEST['subaction']:'';
		$employer_id = isset($_REQUEST['employer_id'])?$_REQUEST['employer_id']:'';

		if($subaction == 'get_tech_certs')
		{
			$this->get_tech_certs($link);
			exit;
		}
		if($subaction == 'get_fs_maths' || $subaction == 'get_fs_eng' || $subaction == 'get_fs_ict')
		{
			$this->get_fs($link);
			exit;
		}
		if($subaction == 'get_courses')
		{
			$this->get_courses($link);
			exit;
		}

//		$_SESSION['bc']->index = 0;
		$_SESSION['bc']->add($link, "do.php?_action=add_ob_learners&employer_id={$employer_id}", "Add Learners");

		$ddlEmployers = DAO::getResultset($link, "SELECT id, legal_name, LEFT(legal_name, 1) FROM organisations WHERE (organisation_type = '" . Organisation::TYPE_EMPLOYER . "') ORDER BY legal_name");
		$ddlEmployersLocations = [
			['', 'Select an employer to populate locations']
		];

		if($employer_id != '')
		{
			$sql = <<<SQL
SELECT
  locations.id,
  CONCAT(COALESCE(locations.`full_name`), ' (',COALESCE(`address_line_1`,''),',',COALESCE(`postcode`,''), ')') AS detail,
  null
FROM
  locations
WHERE
	locations.organisations_id = '$employer_id'
ORDER BY full_name ;
SQL;
			$ddlEmployersLocations = DAO::getResultset($link, $sql);
		}
		$ddlColleges = DAO::getResultset($link, "SELECT id, legal_name, null FROM organisations WHERE organisation_type = '7' ORDER BY legal_name");
		$ddlAppCoordinators = DAO::getResultset($link, "SELECT DISTINCT contact_id, CONCAT(
  COALESCE(contact_name),
  ' (',
  COALESCE(`contact_department`, ''),
  ', ',
  COALESCE(`contact_mobile`, ''),
  ')'
), null FROM organisation_contact WHERE job_role = '5' ORDER BY contact_name");

		$ddlFrameworks = DAO::getResultset($link, "SELECT id, title, framework_code FROM frameworks WHERE active = 1 ORDER BY framework_code, title;");
		if(in_array(DB_NAME, ["am_demo", "am_barnsley"]))
			$ddlJobTitles = DAO::getResultset($link, "SELECT id, description, null FROM lookup_job_titles ORDER BY description;");

		$ddlAssessmentTypes = [
			["l3it", "Level 3 Improvement Technician"],
			["l4ip", "Level 4 Improvement Practitioner"],
			["lmo", "Lean Manufacturing Operative"]
		];

		$coaches_sql = <<<HEREDOC
SELECT
	users.id,
	CONCAT(
		CONCAT(users.firstnames, ' ', users.surname),
		IF(department IS NOT NULL OR job_role IS NOT NULL,
			CONCAT(' (', IF(department IS NOT NULL, IF(job_role IS NOT NULL, CONCAT(department,', ', job_role),department), job_role), ')'), ''),
		' - ',
		users.username
	),
	NULL
FROM
	users
INNER JOIN organisations ON organisations.id = users.employer_id
WHERE (users.web_access = 1 AND users.type NOT IN (5, 12)
AND users.`username` NOT IN (SELECT ident FROM acl WHERE resource_category = 'application' AND privilege = 'administrator')
) ORDER BY CONCAT(firstnames, ' ', surname)
;
HEREDOC;
		$coaches_list = DAO::getResultset($link, $coaches_sql);

		$ddlContracts = DAO::getResultset($link, "SELECT id, title, null FROM contracts WHERE contract_year = '2020' AND active = '1' ORDER BY title");

		if(in_array(DB_NAME, ["am_lead", "am_lead_demo"]))
			include_once('tpl_add_ob_learners_v2.php');
		else
			include_once('tpl_add_ob_learners.php');
	}

	function get_tech_certs(PDO $link)
	{
		$framework_id = isset($_REQUEST['framework_id'])?$_REQUEST['framework_id']:'';

		$sql = <<<SQL
SELECT DISTINCT
  REPLACE(id, '/', '') AS id,
  CONCAT(id, ' - ', internaltitle) AS internaltitle
FROM
  framework_qualifications
WHERE
  framework_id = '$framework_id'
  AND qualification_type != 'FS'
ORDER BY
  internaltitle
;
SQL;

		$records = DAO::getResultset($link, $sql, DAO::FETCH_ASSOC);
		echo '<option value="" >Select an option</option>';
		if(count($records) > 0)
		{
			foreach($records AS $row)
			{
				echo '<option value="'.$row['id'].'">'.$row['internaltitle'].'</option>';
			}
		}
		else
			echo '<option value="">No qualifications/certificates found</option>';

	}

	function get_fs(PDO $link)
	{
		$subaction = isset($_REQUEST['subaction'])?$_REQUEST['subaction']:'';
		$framework_id = isset($_REQUEST['framework_id'])?$_REQUEST['framework_id']:'';

		$where = ' AND LOWER(internaltitle) LIKE "%math%"';
		if($subaction == 'get_fs_eng')
			$where = ' AND LOWER(internaltitle) LIKE "%english%"';
		if($subaction == 'get_fs_ict')
			$where = ' AND LOWER(internaltitle) LIKE "%ict%"';

		$sql = <<<SQL
SELECT DISTINCT
  REPLACE(id, '/', '') AS id,
  CONCAT(id, ' - ', internaltitle) AS internaltitle
FROM
  framework_qualifications
WHERE
  framework_id = '$framework_id'
  AND qualification_type = 'FS'
  $where
ORDER BY
  internaltitle
;
SQL;

		$records = DAO::getResultset($link, $sql, DAO::FETCH_ASSOC);
		echo '<option value="" >Select an option</option>';
		if(count($records) > 0)
		{
			foreach($records AS $row)
			{
				echo '<option value="'.$row['id'].'">'.$row['internaltitle'].'</option>';
			}
		}
		else
			echo '<option value="">No qualifications/certificates found</option>';

	}

	function get_courses(PDO $link)
	{
		$framework_id = isset($_REQUEST['framework_id'])?$_REQUEST['framework_id']:'';

		$sql = <<<SQL
SELECT DISTINCT
  id,
  title
FROM
  courses
WHERE
  framework_id = '$framework_id'
  AND active = '1'
ORDER BY
  title
;
SQL;

		$records = DAO::getResultset($link, $sql, DAO::FETCH_ASSOC);
		echo '<option value="" >Select an option</option>';
		if(count($records) > 0)
		{
			foreach($records AS $row)
			{
				echo '<option value="'.$row['id'].'">'.$row['title'].'</option>';
			}
		}
		else
			echo '<option value="">No courses found</option>';

	}
}
