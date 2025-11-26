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
		if($subaction == 'load_provider_trainers')
		{
			$this->load_provider_trainers($link);
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
		if($subaction != '' && $subaction == 'load_employer_contacts')
		{
			$this->load_employer_contacts($link);
			exit;
		}
		if($subaction != '' && $subaction == 'load_price_reduction_ddl')
		{
			$this->load_price_reduction_ddl($link);
			exit;
		}
		if($subaction != '' && $subaction == 'load_duration_practical_period_from_standard')
		{
			$this->load_duration_practical_period_from_standard($link);
			exit;
		}
		if($subaction != '' && $subaction == 'get_duration_practical_period_from_otj')
		{
			$this->get_duration_practical_period_from_otj($link);
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
				echo '<option value="' . htmlspecialchars($row[0]) . '">' . htmlspecialchars($row[1]) . "</option>\r\n";
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
				echo '<option value="' . htmlspecialchars($row[0]) . '">' . htmlspecialchars($row[1]) . "</option>\r\n";
			}

			echo '</select>';

		}
		else
		{
			throw new DatabaseException($link, $sql);
		}
	}

	private function load_provider_trainers(PDO $link)
	{
		$provider_location_id = isset($_REQUEST['provider_location_id'])?$_REQUEST['provider_location_id']:'';
		if($provider_location_id == '')
			throw new Exception('Missing querystring argument: provider_location_id');

		header('Content-Type: text/xml');

        $trainer_type = User::TYPE_ASSESSOR;
		$sql = <<<HEREDOC
SELECT DISTINCT
	users.id, CONCAT(users.`firstnames`, ' ', users.surname, ' [', lookup_user_types.description, ']') AS full_user_name
FROM
	users
	INNER JOIN organisations ON users.`employer_id` = organisations.`id`
	INNER JOIN locations ON organisations.id = locations.`organisations_id`
	INNER JOIN lookup_user_types ON users.type = lookup_user_types.id
WHERE
    	users.type NOT IN (5) AND
	locations.id = '$provider_location_id'
ORDER BY full_user_name
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
				echo '<option value="' . htmlspecialchars($row[0]) . '">' . htmlspecialchars($row[1]) . "</option>\r\n";
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


		$sql = <<<HEREDOC
SELECT
	id, CONCAT(firstnames, ' ', surname), NULL
FROM
	 users
WHERE
	users.type = '3'
	AND users.employer_location_id = '$location_id'
ORDER BY
	users.firstnames
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
				echo '<option value="' . htmlspecialchars($row[0]) . '">' . htmlspecialchars($row[1]) . "</option>\r\n";
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


		$sql = <<<HEREDOC
SELECT
	id, CONCAT(firstnames, ' ', surname), NULL
FROM
	 users
WHERE
	users.type = '2'
	AND users.employer_location_id = '$location_id'
ORDER BY
	users.firstnames
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
				echo '<option value="' . htmlspecialchars($row[0]) . '">' . htmlspecialchars($row[1]) . "</option>\r\n";
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
				echo '<option value="' . htmlspecialchars($row[0]) . '">' . htmlspecialchars($row[1]) . "</option>\r\n";
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
				echo '<option value="' . htmlspecialchars($row[0]) . '">' . htmlspecialchars($row[1]) . "</option>\r\n";
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
  CONCAT(COALESCE(locations.`full_name`), ' (',COALESCE(`address_line_1`,''),', ',COALESCE(`postcode`,''), ')') AS detail,
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
			//echo "<option value=\"\"></option>\r\n";

			while($row = $st->fetch())
			{
				echo '<option value="' . htmlspecialchars($row[0]) . '">' . htmlspecialchars($row[1]) . "</option>\r\n";
			}

			echo '</select>';

		}
		else
		{
			throw new DatabaseException($link, $sql);
		}

	}

	private function load_employer_contacts(PDO $link)
	{
		header('Content-Type: text/xml');
		$employer_id = isset($_REQUEST['employer_id'])?$_REQUEST['employer_id']:'';
		if($employer_id == '')
		{
			throw new Exception("Missing querystring argument 'employer_id'");
		}

		$sql = <<<HEREDOC
SELECT
  organisation_contacts.contact_id,
  organisation_contacts.contact_name,
  null
FROM
  organisation_contacts
WHERE organisation_contacts.org_id = '$employer_id' AND job_role IN ('2', '28')
ORDER BY contact_name
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
				echo '<option value="' . htmlspecialchars($row[0]) . '">' . htmlspecialchars($row[1]) . "</option>\r\n";
			}

			echo '</select>';

		}
		else
		{
			throw new DatabaseException($link, $sql);
		}

	}

	private function load_price_reduction_ddl(PDO $link)
	{
		$percentage = isset($_REQUEST['percentage']) ? $_REQUEST['percentage'] : '';
		if($percentage == '')
		{
			return;
		}

		$reduction_percentage = 100-$percentage;

		// if(DB_NAME == "am_ela")
		// {
		// 	$reduction_percentage = $percentage;
		// }

		$minimum = round( ($reduction_percentage)*0.5, 2 );
		$maximum = $reduction_percentage;

		header('Content-Type: text/xml');
		echo "<?xml version=\"1.0\" ?>\r\n";
		echo "<select>\r\n";
		echo "<option value=\"\"></option>\r\n";
		for($i = $minimum; $i <= $maximum; $i=$i+0.25)
		{
			echo '<option value="' . $i . '">' . $i . "</option>\r\n";
		}

		echo  '</select>';
	}
	
	private function load_duration_practical_period_from_standard(PDO $link)
	{
		$framework_id = isset($_REQUEST['framework_id']) ? $_REQUEST['framework_id'] : '';
		if($framework_id == '')
		{
			return;
		}

		$framework = Framework::loadFromDatabase($link, $framework_id);
		if(is_null($framework))
		{
			return;
		}

		$sql = "
			SELECT 
				lookup_otj_durations.* 
			FROM 
				central.lookup_otj_durations 
				INNER JOIN central.lookup_app_otj_requirements ON lookup_otj_durations.otj_hours = lookup_app_otj_requirements.otj_hours
			WHERE 
				lookup_app_otj_requirements.standard_code = '{$framework->standard_ref_no}'
			LIMIT 1
		";
		$result = DAO::getObject($link, $sql);
		
		header('Content-Type: text/xml');
		echo "<?xml version=\"1.0\" ?>\r\n";
		echo "<select>\r\n";
		echo "<option value=\"\"></option>\r\n";
		if($result)
		{
			if($result->hpw_6 != '')
			{
				echo '<option value="' . $result->hpw_6 . '">' . $result->hpw_6 . " months</option>\r\n";
			}
			if($result->hpw_7p5 != '')
			{
				echo '<option value="' . $result->hpw_7p5 . '">' . $result->hpw_7p5 . " months</option>\r\n";
			}
			if($result->hpw_9 != '')
			{
				echo '<option value="' . $result->hpw_9 . '">' . $result->hpw_9 . " months</option>\r\n";
			}
			if($result->hpw_10p5 != '')
			{
				echo '<option value="' . $result->hpw_10p5 . '">' . $result->hpw_10p5 . " months</option>\r\n";
			}
			if($result->hpw_12 != '')
			{
				echo '<option value="' . $result->hpw_12 . '">' . $result->hpw_12 . " months</option>\r\n";
			}
			if($result->hpw_13p5 != '')
			{
				echo '<option value="' . $result->hpw_13p5 . '">' . $result->hpw_13p5 . " months</option>\r\n";
			}
			if($result->hpw_15 != '')
			{
				echo '<option value="' . $result->hpw_15 . '">' . $result->hpw_15 . " months</option>\r\n";
			}
		}
		
		echo  '</select>';
	}

	private function get_duration_practical_period_from_otj(PDO $link)
	{
		$otj_hours_col = isset($_REQUEST['otj_hours_col']) ? $_REQUEST['otj_hours_col'] : '';
		$framework_id = isset($_REQUEST['framework_id']) ? $_REQUEST['framework_id'] : '';

		if($otj_hours_col == '' || $framework_id == '')
		{
			return;
		}

		$framework = Framework::loadFromDatabase($link, $framework_id);
		if(is_null($framework))
		{
			return;
		}

		$sql = "
			SELECT 
				lookup_otj_durations.{$otj_hours_col} 
			FROM 
				central.lookup_otj_durations 
				INNER JOIN central.lookup_app_otj_requirements ON lookup_otj_durations.otj_hours = lookup_app_otj_requirements.otj_hours
			WHERE 
				lookup_app_otj_requirements.standard_code = '{$framework->standard_ref_no}'
			LIMIT 1
		";
		$response = [
            'success' => true,
            'duration' => DAO::getSingleValue($link, $sql)
        ];
        header('Content-Type: application/json');
        echo json_encode($response);
        exit;
	}
	
	private function get_otj_from_duration(PDO $link)
	{
		$duration = isset($_REQUEST['duration']) ? $_REQUEST['duration'] : '';
		$framework_id = isset($_REQUEST['framework_id']) ? $_REQUEST['framework_id'] : '';

		if($duration == '' || $framework_id == '')
		{
			return;
		}

		$framework = Framework::loadFromDatabase($link, $framework_id);
		if(is_null($framework))
		{
			return;
		}

		$sql = "
			SELECT 
				lookup_otj_durations.{$otj_hours_col} 
			FROM 
				central.lookup_otj_durations 
				INNER JOIN central.lookup_app_otj_requirements ON lookup_otj_durations.otj_hours = lookup_app_otj_requirements.otj_hours
			WHERE 
				lookup_app_otj_requirements.standard_code = '{$framework->standard_ref_no}'
			LIMIT 1
		";
		$response = [
            'success' => true,
            'duration' => DAO::getSingleValue($link, $sql)
        ];
        header('Content-Type: application/json');
        echo json_encode($response);
        exit;
	}
}
?>