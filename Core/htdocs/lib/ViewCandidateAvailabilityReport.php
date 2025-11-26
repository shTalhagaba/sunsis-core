<?php
class ViewCandidateAvailabilityReport extends View
{

	public static function getInstance(PDO $link)
	{
		$key = 'view_'.__CLASS__;

		if(!isset($_SESSION[$key]))
		{
			$where = '';

			$sql = <<<SQL


SELECT
	CONCAT(candidate.`firstnames`, ' ', candidate.`surname`) AS candidate_name,
	DATE_FORMAT(candidate.dob, '%d/%m/%Y') AS dob,
	timestampdiff(YEAR,candidate.dob,CURDATE()) AS age_in_years,
	candidate.`region`,
	candidate.`nearest_training_location`,
	(SELECT description FROM lookup_candidate_status WHERE id = candidate.`status_code`) AS `status`,

	candidate.`driver`,
	(SELECT DATE_FORMAT(`date`, '%d/%m/%Y') FROM crm_notes_candidates WHERE crm_notes_candidates.`candidate_id` = candidate.`id` ORDER BY DATE DESC LIMIT 1) AS last_contact,
	(SELECT agreed_action FROM crm_notes_candidates WHERE crm_notes_candidates.`candidate_id` = candidate.`id` ORDER BY DATE DESC LIMIT 1) AS last_contact_agreed_action

FROM
	candidate LEFT JOIN candidate_applications ON candidate.id = candidate_id AND (application_screening IS NULL OR application_screening = 0)
	AND (enrolled IS NULL OR enrolled = 0)
ORDER BY
	candidate.firstnames, candidate.surname

SQL;

			// Create new view object
			$view = $_SESSION[$key] = new ViewCandidateAvailabilityReport();
			$view->setSQL($sql);

			// Candidate Name Filter
			$f = new TextboxViewFilter('filter_firstnames', "WHERE LOWER(candidate.firstnames) LIKE LOWER('%%%s%%')", null);
			$f->setDescriptionFormat("Firstname contains: %s");
			$view->addFilter($f);

			$f = new TextboxViewFilter('filter_surname', "WHERE LOWER(candidate.surname) LIKE LOWER('%%%s%%')", null);
			$f->setDescriptionFormat("Surname contains: %s");
			$view->addFilter($f);

			// Date of Birth Filter
			$format = "WHERE candidate.dob = '%s'";
			$f = new DateViewFilter('filter_dob', $format, '');
			$f->setDescriptionFormat("Date Of Birth: %s");
			$view->addFilter($f);

			// Region Filter
			$options = "select description, description, null, CONCAT('WHERE candidate.region = ',char(39),description,char(39)) from lookup_vacancy_regions order by description";
			$f = new DropDownViewFilter('filter_region', $options, null, true);
			$f->setDescriptionFormat("Region: %s");
			$view->addFilter($f);

			//Nearest Training Location Filter
			$options = "select id, description, null, CONCAT('WHERE candidate.nearest_training_location = ',char(39),id,char(39)) from lookup_delivery_locations order by description";
			$f = new DropDownViewFilter('filter_nearest_training_location', $options, null, true);
			$f->setDescriptionFormat("Nearest Training Location: %s");
			$view->addFilter($f);

			/*// Date filters
			$dateInfo = getdate();
			$weekday = $dateInfo['wday']; // 0 (Sun) -> 6 (Sat)
			$timestamp = time()  - ((60*60*24) * $weekday);

			// Date Filter
			$format = "HAVING last_contact >= DATE_FORMAT('%s', '%d/%m/%Y')";
			$f = new DateViewFilter('filter_from_last_contact', $format, '');
			$f->setDescriptionFormat("From Last Contact Date: %s");
			$view->addFilter($f);*/

			$options = array(
				0=>array(20,20,null,null),
				1=>array(50,50,null,null),
				2=>array(100,100,null,null),
				3=>array(200,200,null,null),
				4=>array(300,300,null,null),
				5=>array(400,400,null,null),
				6=>array(500,500,null,null),
				7=>array(0, 'No limit', null, null));
			$f = new DropDownViewFilter(View::KEY_PAGE_SIZE, $options, 20, false);
			$f->setDescriptionFormat("Records per page: %s");
			$view->addFilter($f);

		}

		return $_SESSION[$key];
	}

	public function render(PDO $link, $columns)
	{
		$st = $link->query($this->getSQL());
		if($st)
		{
			echo $this->getViewNavigator();
			echo '<div align="center"><table class="resultset" border="0" cellspacing="0" cellpadding="6">';

			foreach($columns as $column)
			{
				echo '<th class="bottomRow" style="font-size:80%; color:#555555">' . ucwords(str_replace("_"," ",str_replace("_and_"," & ",$column))) . '</th>';
			}

			echo '</tr></thead>';

			echo '<tbody>';


			while($row = $st->fetch(PDO::FETCH_ASSOC))
			{
				echo '<tr>';
				foreach($columns as $column)
				{
					echo '<td>' . ((isset($row[$column]))?(($row[$column]=='')?'&nbsp':$row[$column]):'&nbsp') . '</td>';
				}
				echo '</tr>';
			}//end while
			echo '</tbody></table>';
			echo $this->getViewNavigator();
		}
		else
		{
			throw new DatabaseException($link, $this->getSQL());
		}

	}


}
?>