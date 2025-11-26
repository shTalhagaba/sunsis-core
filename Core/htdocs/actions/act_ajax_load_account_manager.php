<?php
class ajax_load_account_manager implements IAction
{
	public function execute(PDO $link)
	{
		$subaction = isset($_REQUEST['subaction'])?$_REQUEST['subaction']:'';
		if($subaction == 'load_provider_locations')
		{
			$this->load_provider_locations($link);
			exit;
		}
		if($subaction == 'load_assessors')
		{
			$this->load_assessors($link);
			exit;
		}
		if($subaction == 'load_tutors')
		{
			$this->load_tutors($link);
			exit;
		}
		if($subaction == 'load_organisation_contacts')
		{
			$this->load_organisation_contacts($link);
			exit;
		}
		if($subaction == 'load_group_employers')
		{
			$this->load_group_employers($link);
			exit;
		}
		if($subaction == 'load_epa_org_assessors')
		{
			$this->load_epa_org_assessors($link);
			exit;
		}
		if($subaction != '' && $subaction == 'load_employer_locations')
		{
			$this->load_employer_locations($link);
			exit;
		}
		if($subaction != '' && $subaction == 'load_onefile_users')
		{
			$this->load_onefile_users($link);
			exit;
		}
		if($subaction != '' && $subaction == 'load_onefile_classrooms')
		{
			$this->load_onefile_classrooms($link);
			exit;
		}
		if($subaction != '' && $subaction == 'load_onefile_placements')
		{
			$this->load_onefile_placements($link);
			exit;
		}
		if($subaction != '' && $subaction == 'load_onefile_standards')
		{
			$this->load_onefile_standards($link);
			exit;
		}
		if($subaction != '' && $subaction == 'load_onefile_fwk_tpls')
		{
			$this->load_onefile_fwk_tpls($link);
			exit;
		}

		header('Content-Type: text/xml');


		$sql = <<<HEREDOC
SELECT
	id, description, null
FROM
	lookup_account_managers
ORDER BY
	description
HEREDOC;

		$st = $link->query($sql);
		if($st)
		{
			echo "<?xml version=\"1.0\" ?>\r\n";
			echo "<select>\r\n";

			// First entry is empty
			echo "<option value=\"\"></option>\r\n";

			while($row = $st->fetch())
			{
				echo '<option value="' . htmlspecialchars((string)$row[0]) . '">' . htmlspecialchars((string)$row[1]) . "</option>\r\n";
			}

			echo '</select>';

		}
		else
		{
			throw new DatabaseException($link, $sql);
		}
	}

	private function load_provider_locations(PDO $link)
	{
		$course_id = isset($_REQUEST['course_id'])?$_REQUEST['course_id']:'';
		if($course_id == '')
			throw new Exception('Missing querystring argument: course_id');

		header('Content-Type: text/xml');


		$sql = <<<HEREDOC
SELECT DISTINCT
	locations.id, CONCAT('Organisation: ', organisations.`legal_name`, ' [Location: ', locations.full_name, ' (', locations.`postcode`, ')]') AS full_name
FROM
	locations
	INNER JOIN organisations ON locations.`organisations_id` = organisations.`id`
	INNER JOIN courses ON organisations.id = courses.`organisations_id`
WHERE
	courses.id = '$course_id'
ORDER BY full_name
;
HEREDOC;

		$st = $link->query($sql);
		if($st)
		{
			echo "<?xml version=\"1.0\" ?>\r\n";
			echo "<select>\r\n";

			// First entry is empty
			echo "<option value=\"\"></option>\r\n";

			while($row = $st->fetch())
			{
				echo '<option value="' . htmlspecialchars((string)$row[0]) . '">' . htmlspecialchars((string)$row[1]) . "</option>\r\n";
			}

			echo '</select>';

		}
		else
		{
			throw new DatabaseException($link, $sql);
		}
	}

	private function load_assessors(PDO $link)
	{
		$location_id = isset($_REQUEST['location_id'])?$_REQUEST['location_id']:'';
		if($location_id == '')
			throw new Exception('Missing querystring argument: location_id');

		header('Content-Type: text/xml');

        if(in_array(DB_NAME, ["am_baltic", "am_baltic_demo", "am_demo"]))
        {
            $sql = <<<HEREDOC
SELECT
users.id,
CONCAT(
    IF(firstnames IS NULL, '', IF(surname IS NULL,firstnames, CONCAT(firstnames,' '))),
    IF(surname IS NULL,'',surname), ' - ',
    users.username
),
NULL
FROM
users
LEFT JOIN lookup_user_types ON lookup_user_types.`id` = users.`type`
INNER JOIN organisations ON organisations.id = users.employer_id
WHERE users.active = 1 AND TYPE != 5
ORDER BY CONCAT(firstnames, ' ', surname);
HEREDOC;

        }
        elseif(in_array(DB_NAME, ["am_city_skills", "am_ela"]))
        {
            $sql = <<<HEREDOC
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
INNER JOIN organisations on organisations.id = users.employer_id 
where type=3 and users.active = 1
ORDER BY CONCAT(firstnames, ' ', surname)
HEREDOC;

        }
        else
        {
            $sql = <<<HEREDOC
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
INNER JOIN organisations on organisations.id = users.employer_id 
where type=3 and users.active = 1
ORDER BY CONCAT(firstnames, ' ', surname)
HEREDOC;

        }

// 		$sql = <<<HEREDOC
// SELECT
// 	id, CONCAT(firstnames, ' ', surname), NULL
// FROM
// 	 users
// WHERE
// 	users.type = '3'
// 	AND users.employer_location_id = '$location_id'
// ORDER BY
// 	users.firstnames
// ;
// HEREDOC;

		$st = $link->query($sql);
		if($st)
		{
			echo "<?xml version=\"1.0\" ?>\r\n";
			echo "<select>\r\n";

			// First entry is empty
			echo "<option value=\"\"></option>\r\n";

			while($row = $st->fetch())
			{
				echo '<option value="' . htmlspecialchars((string)$row[0]) . '">' . htmlspecialchars((string)$row[1]) . "</option>\r\n";
			}

			echo '</select>';

		}
		else
		{
			throw new DatabaseException($link, $sql);
		}
	}

	private function load_tutors(PDO $link)
	{
		$location_id = isset($_REQUEST['location_id'])?$_REQUEST['location_id']:'';
		if($location_id == '')
			throw new Exception('Missing querystring argument: location_id');

		header('Content-Type: text/xml');

		$tutor_type = User::TYPE_TUTOR;
		$sql = <<<HEREDOC
SELECT
		users.id,
		CONCAT(
			IF(firstnames IS NULL, '', IF(surname IS NULL,firstnames, CONCAT(firstnames,' '))),
			IF(surname IS NULL,'',surname),
			IF(department IS NOT NULL OR job_role IS NOT NULL,
				CONCAT(' (', IF(department IS NOT NULL, IF(job_role IS NOT NULL, CONCAT(department,', ', job_role),department), job_role), ')'), '')
		),
		NULL
	FROM
		users
	WHERE
	users.active = 1 and type = '$tutor_type'
	ORDER BY
		firstnames, surname
;
HEREDOC;

		$st = $link->query($sql);
		if($st)
		{
			echo "<?xml version=\"1.0\" ?>\r\n";
			echo "<select>\r\n";

			// First entry is empty
			echo "<option value=\"\"></option>\r\n";

			while($row = $st->fetch())
			{
				echo '<option value="' . htmlspecialchars((string)$row[0]) . '">' . htmlspecialchars((string)$row[1]) . "</option>\r\n";
			}

			echo '</select>';

		}
		else
		{
			throw new DatabaseException($link, $sql);
		}
	}

	private function load_organisation_contacts(PDO $link)
	{
		$employer_id = isset($_REQUEST['employer_id'])?$_REQUEST['employer_id']:'';
		if($employer_id == '')
			throw new Exception('Missing querystring argument: employer_id');

		header('Content-Type: text/xml');


		$sql = <<<HEREDOC
SELECT
	contact_id, contact_name, NULL
FROM
	 organisation_contacts
WHERE
	org_id = '$employer_id'
ORDER BY
	contact_name
;
HEREDOC;

		$st = $link->query($sql);
		if($st)
		{
			echo "<?xml version=\"1.0\" ?>\r\n";
			echo "<select>\r\n";

			// First entry is empty
			echo "<option value=\"\"></option>\r\n";

			while($row = $st->fetch())
			{
				echo '<option value="' . htmlspecialchars((string)$row[0]) . '">' . htmlspecialchars((string)$row[1]) . "</option>\r\n";
			}

			echo '</select>';

		}
		else
		{
			throw new DatabaseException($link, $sql);
		}
	}

	private function load_group_employers(PDO $link)
	{
		header('Content-Type: text/xml');


		$sql = <<<HEREDOC
SELECT
	id, description, null
FROM
	lookup_group_employers
ORDER BY
	description
HEREDOC;

		$st = $link->query($sql);
		if($st)
		{
			echo "<?xml version=\"1.0\" ?>\r\n";
			echo "<select>\r\n";

			// First entry is empty
			echo "<option value=\"\"></option>\r\n";

			while($row = $st->fetch())
			{
				echo '<option value="' . htmlspecialchars((string)$row[0]) . '">' . htmlspecialchars((string)$row[1]) . "</option>\r\n";
			}

			echo '</select>';

		}
		else
		{
			throw new DatabaseException($link, $sql);
		}
	}

	private function load_epa_org_assessors(PDO $link)
	{
		$EPA_Org_ID = isset($_REQUEST['EPA_Org_ID']) ? $_REQUEST['EPA_Org_ID'] : '';
		if($EPA_Org_ID == '')
			return ;



		header('Content-Type: text/xml');


		$sql = <<<HEREDOC
SELECT
  id,
  CONCAT(
    COALESCE(title, ' '),
    `firstnames`, ' ',
    `surname`,
    ' (',
    COALESCE(`address1`, ''), ' ',
    COALESCE(`address4`, ' '), ' ',
    `postcode`, ') ',
    COALESCE(`email`, ''), ' '
  ) AS contact_name,
  NULL
FROM
  epa_org_assessors
WHERE epa_org_assessors.`EPA_Org_ID` = '$EPA_Org_ID'
ORDER BY epa_org_assessors.firstnames
;
HEREDOC;

		$st = $link->query($sql);
		if($st)
		{
			echo "<?xml version=\"1.0\" ?>\r\n";
			echo "<select>\r\n";

			// First entry is empty
			echo "<option value=\"\"></option>\r\n";

			while($row = $st->fetch())
			{
				echo '<option value="' . htmlspecialchars((string)$row[0]) . '">' . htmlspecialchars((string)$row[1]) . "</option>\r\n";
			}

			echo '</select>';

		}
		else
		{
			throw new DatabaseException($link, $sql);
		}
	}

	private function load_employer_locations(PDO $link)
	{
		header('Content-Type: text/xml');
		$employer_id = isset($_REQUEST['employer_id'])?$_REQUEST['employer_id']:'';
		if($employer_id == '')
		{
			throw new Exception("Missing querystring argument 'employer_id'");
		}

		$sql = <<<HEREDOC
SELECT
  locations.id,
  CONCAT(COALESCE(locations.`full_name`), ' (',COALESCE(`address_line_1`,''),',',COALESCE(`postcode`,''), ')') AS detail,
  null
FROM
  locations
WHERE locations.organisations_id = '$employer_id'
ORDER BY full_name
;
HEREDOC;


		$st = $link->query($sql);
		if($st)
		{
			echo "<?xml version=\"1.0\" ?>\r\n";
			echo "<select>\r\n";

			// First entry is empty
			echo "<option value=\"\"></option>\r\n";

			while($row = $st->fetch())
			{
				echo '<option value="' . htmlspecialchars((string)$row[0]) . '">' . htmlspecialchars((string)$row[1]) . "</option>\r\n";
			}

			echo '</select>';

		}
		else
		{
			throw new DatabaseException($link, $sql);
		}

	}

	private function load_onefile_users(PDO $link)
	{
		header('Content-Type: text/xml');
		$sunesis_user_type = isset($_REQUEST['sunesis_user_type'])?$_REQUEST['sunesis_user_type']:'';
		if($sunesis_user_type == '')
		{
			throw new Exception("Missing querystring argument 'sunesis_user_type'");
		}

		echo "<?xml version=\"1.0\" ?>\r\n";
		echo "<select>\r\n";
		echo "<option value=\"\"></option>\r\n";

		$onefile_users_list_from_db = DAO::getSingleValue($link, "SELECT `value` FROM onefile WHERE `key` = 'users_{$sunesis_user_type}'");
		if($onefile_users_list_from_db != '')
		{
			$onefile_users_list_from_db = json_decode($onefile_users_list_from_db);
			usort($onefile_users_list_from_db, function($a, $b) {return strcmp($a->LastName, $b->LastName);});
			foreach($onefile_users_list_from_db AS $_onefile_user)
			{
				echo '<option value="' . htmlspecialchars((string)$_onefile_user->ID) . '">' . htmlspecialchars((string)$_onefile_user->LastName . ', ' . $_onefile_user->FirstName) . "</option>\r\n";
			}
		}
		echo '</select>';
	}
	
	private function load_onefile_classrooms(PDO $link)
	{
		header('Content-Type: text/xml');
		$organisation_id = isset($_REQUEST['organisation_id'])?$_REQUEST['organisation_id']:'';
		if($organisation_id == '')
		{
			throw new Exception("Missing querystring argument 'organisation_id'");
		}

		echo "<?xml version=\"1.0\" ?>\r\n";
		echo "<select>\r\n";
		echo "<option value=\"\"></option>\r\n";

		$onefile_classrooms_list_from_db = DAO::getSingleValue($link, "SELECT `value` FROM onefile WHERE `key` = 'classrooms_{$organisation_id}'");
		if($onefile_classrooms_list_from_db != '')
		{
			$onefile_classrooms_list_from_db = json_decode($onefile_classrooms_list_from_db);
			usort($onefile_classrooms_list_from_db, function($a, $b) {return strcmp($a->Name, $b->Name);});
			foreach($onefile_classrooms_list_from_db AS $_onefile_classroom)
			{
				echo '<option value="' . htmlspecialchars((string)$_onefile_classroom->ID) . '">' . htmlspecialchars((string)$_onefile_classroom->Name) . "</option>\r\n";
			}
		}
		echo '</select>';
	}
	
	private function load_onefile_placements(PDO $link)
	{
		header('Content-Type: text/xml');
		$organisation_id = isset($_REQUEST['organisation_id'])?$_REQUEST['organisation_id']:'';
		if($organisation_id == '')
		{
			throw new Exception("Missing querystring argument 'organisation_id'");
		}

		echo "<?xml version=\"1.0\" ?>\r\n";
		echo "<select>\r\n";
		echo "<option value=\"\">Select Onefile Placement</option>\r\n";

		$onefile_placements_list_from_db = DAO::getSingleValue($link, "SELECT `value` FROM onefile WHERE `key` = 'placements_{$organisation_id}'");
		if($onefile_placements_list_from_db != '')
		{
			$onefile_placements_list_from_db = json_decode($onefile_placements_list_from_db);
			usort($onefile_placements_list_from_db, function($a, $b) {return strcmp($a->Name, $b->Name);});
			foreach($onefile_placements_list_from_db AS $_onefile_placement)
			{
				echo '<option value="' . htmlspecialchars((string)$_onefile_placement->ID) . '">[' . htmlspecialchars((string)$_onefile_placement->ID) . '] ' . htmlspecialchars((string)$_onefile_placement->Name) . "</option>\r\n";
			}
		}
		echo '</select>';
	}
	
	private function load_onefile_standards(PDO $link)
	{
		header('Content-Type: text/xml');
		$organisation_id = isset($_REQUEST['organisation_id'])?$_REQUEST['organisation_id']:'';
		if($organisation_id == '')
		{
			throw new Exception("Missing querystring argument 'organisation_id'");
		}

		echo "<?xml version=\"1.0\" ?>\r\n";
		echo "<select>\r\n";
		echo "<option value=\"\"></option>\r\n";

		$onefile_standards_list_from_db = DAO::getSingleValue($link, "SELECT `value` FROM onefile WHERE `key` = 'standards_{$organisation_id}'");
		if($onefile_standards_list_from_db != '')
		{
			$onefile_standards_list_from_db = json_decode($onefile_standards_list_from_db);
			usort($onefile_standards_list_from_db, function($a, $b) {return strcmp($a->Title, $b->Title);});
			foreach($onefile_standards_list_from_db AS $_onefile_standard)
			{
				echo '<option value="' . htmlspecialchars((string)$_onefile_standard->ID) . '">' . htmlspecialchars((string)$_onefile_standard->Title) . "</option>\r\n";
			}
		}
		echo '</select>';
	}
	
	private function load_onefile_fwk_tpls(PDO $link)
	{
		header('Content-Type: text/xml');
		$organisation_id = isset($_REQUEST['organisation_id'])?$_REQUEST['organisation_id']:'';
		if($organisation_id == '')
		{
			throw new Exception("Missing querystring argument 'organisation_id'");
		}

		echo "<?xml version=\"1.0\" ?>\r\n";
		echo "<select>\r\n";
		echo "<option value=\"\"></option>\r\n";

		$onefile_fwk_tpls_list_from_db = DAO::getSingleValue($link, "SELECT `value` FROM onefile WHERE `key` = 'fwk_tpls_{$organisation_id}'");
		if($onefile_fwk_tpls_list_from_db != '')
		{
			$onefile_fwk_tpls_list_from_db = json_decode($onefile_fwk_tpls_list_from_db);
			usort($onefile_fwk_tpls_list_from_db, function($a, $b) {return strcmp($a->Title, $b->Title);});
			foreach($onefile_fwk_tpls_list_from_db AS $_onefile_fwk_tpl)
			{
				echo '<option value="' . htmlspecialchars((string)$_onefile_fwk_tpl->ID) . '">' . htmlspecialchars((string)$_onefile_fwk_tpl->Title) . "</option>\r\n";
			}
		}
		echo '</select>';
	}

}
?>