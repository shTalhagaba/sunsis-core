<?php
class RecViewCandidates extends View
{

	public static function getInstance(PDO $link)
	{
		$key = 'view_'.__CLASS__;

		if(isset($_REQUEST['RecViewCandidates_filter_postcodes']) && $_REQUEST['RecViewCandidates_filter_postcodes']!='')
			$key = 'view_'.__CLASS__.$_REQUEST['RecViewCandidates_filter_postcodes'].'_'.$_REQUEST['RecViewCandidates_filter_distance'];

		if(!isset($_SESSION[$key]))
		{
			if($_SESSION['user']->isAdmin())
			{
				$where = '';
			}
			elseif($_SESSION['user']->type == User::TYPE_BUSINESS_RESOURCE_MANAGER)
			{
				$where = ' WHERE (vacancies.employer_id = "' . $_SESSION['user']->employer_id . '") ';
			}

			$sql = <<<HEREDOC
SELECT
	candidate.id,
	candidate.firstnames,
	candidate.surname,
	DATE_FORMAT(candidate.dob, '%d/%m/%Y') AS dob,
	timestampdiff(YEAR,candidate.dob,CURDATE()) AS age_in_years,
	timestampdiff(MONTH,candidate.dob,CURDATE()) MOD 12 AS age_in_months,
	candidate.gender,
	CONCAT(IFNULL(candidate.address1,''), ' ', IFNULL(candidate.address2,''), ' ', IFNULL(candidate.county,'')) AS address,
	candidate.postcode,
	(SELECT LEFT(CONCAT(Ethnicity, ' ', Ethnicity_Desc),60)  FROM lis201314.ilr_ethnicity WHERE candidate.ethnicity = Ethnicity) AS ethnicity,
	candidate.national_insurance,
	candidate.email,
	candidate.telephone,
	candidate.northing,
	candidate.easting,
	candidate.latitude,
	candidate.longitude,
	candidate.created,
	candidate_applications.telephone_interview_score,
	candidate_applications.current_status,
	(SELECT COUNT(*) FROM candidate_applications WHERE candidate_id = candidate.id) AS applications

FROM
	candidate
	LEFT JOIN candidate_applications ON candidate.id = candidate_applications.candidate_id
	LEFT JOIN vacancies ON candidate_applications.vacancy_id = vacancies.id
#WHERE
#	(candidate.username IS NULL OR candidate.username = '')
$where
GROUP BY candidate.id
;
HEREDOC;

			$view = $_SESSION[$key] = new RecViewCandidates($link);
			$view->setSQL($sql);

			// Add view filters
			$options = array(
				0=>array(20,20,null,null),
				1=>array(50,50,null,null),
				2=>array(100,100,null,null),
				3=>array(0,'No limit',null,null));
			$f = new DropDownViewFilter(View::KEY_PAGE_SIZE, $options, 20, false);
			$f->setDescriptionFormat("Records per page: %s");
			$view->addFilter($f);

			$options = "SELECT Ethnicity AS id, LEFT(CONCAT(Ethnicity, ' ', Ethnicity_Desc),60) AS description, null, CONCAT('WHERE candidate.ethnicity=',Ethnicity) FROM lis201314.ilr_ethnicity";
			$f = new DropDownViewFilter('filter_ethnicity', $options, null, true);
			$f->setDescriptionFormat("Ethnicity: %s");
			$view->addFilter($f);

			$options = array(
				0=>array(1, 'Surname (asc)', null, 'ORDER BY surname'),
				1=>array(2, 'Surname (desc)', null, 'ORDER BY surname DESC'),
				2=>array(3, 'Creation Date (asc)', null, 'ORDER by created'),
				3=>array(4, 'Creation Date (desc)', null, 'ORDER by created DESC'));
			$f = new DropDownViewFilter('order_by', $options, 1, false);
			$f->setDescriptionFormat("Sort by: %s");
			$view->addFilter($f);

			$format = "WHERE date(candidate.created) >= '%s'";
			$f = new DateViewFilter('filter_from_created', $format, '');
			$f->setDescriptionFormat("Created from: %s");
			$view->addFilter($f);

			$format = "WHERE date(candidate.created) <= '%s'";
			$f = new DateViewFilter('filter_to_created', $format, '');
			$f->setDescriptionFormat("Created to: %s");
			$view->addFilter($f);

			$options = array();
			$option_count = 1;
			for( $age=16; $age<=50; $age++ )
			{
				array_push($options, array($option_count, $age, null, "WHERE (DATE_FORMAT(NOW(), '%Y') - DATE_FORMAT(candidate.dob, '%Y') - (DATE_FORMAT(NOW(), '00-%m-%d') < DATE_FORMAT(candidate.dob, '00-%m-%d'))) = ".$age ));
				$option_count++;
			}
			$f = new DropDownviewFilter('filter_age', $options, null, true);
			$f->setDescriptionFormat("Age: %s");
			$view->addFilter($f);

			$options = "SELECT DISTINCT gender, IF(gender='F','Female',IF(gender='M','Male',IF(gender='U','Unknown',IF(gender='W','Witheld','')))), null, CONCAT('WHERE candidate.gender=',char(39),gender,char(39)) FROM candidate";
			$f = new DropDownViewFilter('filter_gender', $options, null, true);
			$f->setDescriptionFormat("Gender: %s");
			$view->addFilter($f);

			$f = new TextboxViewFilter('filter_firstnames', "WHERE LOWER(candidate.firstnames) LIKE LOWER('%%%s%%')", null);
			$f->setDescriptionFormat("Firstname contains: %s");
			$view->addFilter($f);

			$f = new TextboxViewFilter('filter_surname', "WHERE LOWER(candidate.surname) LIKE LOWER('%%%s%%')", null);
			$f->setDescriptionFormat("Surname contains: %s");
			$view->addFilter($f);

			$f = new TextboxViewFilter('filter_email', "WHERE LOWER(candidate.email) LIKE LOWER('%%%s%%')", null);
			$f->setDescriptionFormat("Email contains: %s");
			$view->addFilter($f);

			$f = new TextboxViewFilter('filter_postcodes', "WHERE candidate.easting is not null and '%s' is not null", null);
			$f->setDescriptionFormat("Distance from: %s");
			$view->addFilter($f);

			$f = new TextboxViewFilter('filter_distance', "WHERE candidate.northing is not null and '%s' is not null", null);
			$f->setDescriptionFormat("Within in %s miles");
			$view->addFilter($f);

			$options = array(
				0=>array(0, 'Show all', null, null),
				1=>array(1, 'Green (Highly Suitable)', null, ' WHERE candidate_applications.screening_rag = "G" '),
				2=>array(2, 'Amber (Suitable)', null, ' WHERE candidate_applications.screening_rag = "A" '),
				3=>array(3, 'Red (Not Suitable)', null, ' WHERE candidate_applications.screening_rag = "R" '));
			$f = new DropDownViewFilter('filter_screening_rag', $options, 0, false);
			$f->setDescriptionFormat("Screening RAG: %s");
			$view->addFilter($f);

			$options = array(
				0=>array(0, 'Show all', null, null),
				1=>array(1, 'Not Screened', null, ' WHERE candidate_applications.current_status = "0" '),
				2=>array(2, 'Screened', null, ' WHERE candidate_applications.current_status = "1" '),
				3=>array(3, 'Telephone Interviewed', null, ' WHERE candidate_applications.current_status = "2" '),
				4=>array(4, 'CV Sent', null, ' WHERE candidate_applications.current_status = "3" '),
				5=>array(5, 'Interview Successful', null, ' WHERE candidate_applications.current_status = "4" '),
				6=>array(6, 'Interview Unsuccessful', null, ' WHERE candidate_applications.current_status = "5" '),
				7=>array(7, 'Sunesis Learner', null, ' WHERE candidate_applications.current_status = "6" '),
				8=>array(8, 'Rejected', null, ' WHERE candidate_applications.current_status = "99" '));
			$f = new DropDownViewFilter('filter_application_status', $options, 0, false);
			$f->setDescriptionFormat("Screening RAG: %s");
			$view->addFilter($f);

			$options = array(
				0=>array(0, 'Show all', null, null),
				1=>array(1, '0 - 10', null, 'HAVING candidate_applications.current_status = "2" AND telephone_interview_score BETWEEN 0 AND 10 '),
				2=>array(2, '10 - 20', null, 'HAVING candidate_applications.current_status = "2" AND telephone_interview_score BETWEEN 10 AND 20 '),
				3=>array(3, '20 - 30', null, 'HAVING candidate_applications.current_status = "2" AND telephone_interview_score BETWEEN 20 AND 30 '),
				4=>array(4, '30 - 35', null, 'HAVING candidate_applications.current_status = "2" AND telephone_interview_score BETWEEN 30 AND 35 '));
			$f = new DropDownViewFilter('filter_app_interview_score', $options, 0, false);
			$f->setDescriptionFormat("Age: %s");
			$view->addFilter($f);

			$f = new TextboxViewFilter('filter_vacancy_title', "WHERE LOWER(vacancies.vacancy_title) LIKE LOWER('%%%s%%')", null);
			$f->setDescriptionFormat("Vacancy Title: %s");
			$view->addFilter($f);

			$f = new TextboxViewFilter('filter_vacancy_reference', "WHERE LOWER(vacancies.vacancy_reference) LIKE LOWER('%%%s%%')", null);
			$f->setDescriptionFormat("Vacancy Reference: %s");
			$view->addFilter($f);

			$options = array(
				0=>array(0, 'Show all', null, null),
				1=>array(1, 'Less than 16', null, 'HAVING age_in_years BETWEEN 5 AND 15 '),
				2=>array(2, '18 or less', null, 'HAVING age_in_years BETWEEN 5 AND 18 '),
				3=>array(3, '16 - 18', null, 'HAVING age_in_years BETWEEN 16 AND 18 '),
				4=>array(4, '19 or more', null, 'HAVING age_in_years >= 19 '),
				5=>array(5, '19 - 23', null, 'HAVING age_in_years > 18 AND age_in_years <= 23 '),
				6=>array(6, '24+', null, 'HAVING age_in_years >= 24 '),
				7=>array(7, 'Unknown', null, 'HAVING dob = "00/00/0000" OR dob = \'\' '),
				8=>array(8, 'Out of Range', null, 'HAVING age_in_years <= 0 OR age_in_years >= 100  '));
			$f = new DropDownViewFilter('filter_age_custom', $options, 0, false);
			$f->setDescriptionFormat("Age: %s");
			$view->addFilter($f);
		}
		return $_SESSION[$key];
	}


	public function render(PDO $link, $columns)
	{
		$loc = NULL;
		$longitude = NULL;
		$latitude = NULL;
		$easting = NULL;
		$northing = NULL;

		$search_distance = NULL;

		$candidate_sql = $this->getSQL();

		if ( preg_match("/easting is not null and \'(.*)\' is not null\) AND/", $candidate_sql, $postcode) )
		{
			$loc = new GeoLocation();
			$loc->setPostcode($postcode[1], $link);
			$longitude = $loc->getLongitude();
			$latitude = $loc->getLatitude();
			$easting = $loc->getEasting();
			$northing = $loc->getNorthing();
		}

		if ( preg_match("/northing is not null and \'(.*)\' is not null/", $candidate_sql, $set_distance) )
		{
			$search_distance = $set_distance[1];
			$candidate_sql = preg_replace("/LIMIT (.*)$/ ","", $candidate_sql);
		}

		if ( is_object($loc) && is_numeric($search_distance) )
		{
			$distance_check = 'AND (SQRT(POWER(ABS('.$easting.' - candidate.easting), 2) + POWER(ABS('.$northing.' - candidate.northing), 2)))/1609.344 <= '.$search_distance.' GROUP BY';
			$candidate_sql = preg_replace("/GROUP BY/ ",$distance_check, $candidate_sql);
		}

		$this->query = $candidate_sql;
		$st = $link->query($candidate_sql);
		if( $st )
		{
			if ( !is_numeric($search_distance) )
			{
				echo $this->getViewNavigator();
			}
			echo '<div align="center"><table id="tblViewCandidates" class="resultset" border="0" cellspacing="0" cellpadding="6">';
			echo '<thead><tr>';
			echo '<th>&nbsp;</th>';
			foreach($columns as $column)
			{
				echo '<th>' . ucwords(str_replace("_"," ",str_replace("_and_"," & ",$column))) . '</th>';
			}
			if ( is_object($loc) )
			{
				echo '<th>Distance</th>';
			}

			echo '</thead>';
			echo '<tbody>';
			$row_count = 1;
			while( $row = $st->fetch() )
			{
				if ( is_object($loc) )
				{
					$distance = sqrt(pow(abs($easting - $row['easting']),2)+pow(abs($northing - $row['northing']),2));
					$distance = sprintf("%.2f", $distance/1609.344);
				}

				echo HTML::viewrow_opening_tag('/do.php?_action=rec_read_candidate&id=' . $row['id']);

				/*if($row['gender'] == 'M')
				{
					if(($row['age_in_years'] >= 24))
						echo "<td><img src=\"/images/boy-blonde-hair-24+.png\" border=\"0\" /></td>";
					else
						echo "<td><img src=\"/images/boy-blonde-hair.gif\" border=\"0\" /></td>";
				}
				elseif($row['gender'] == 'F')
				{
					if(($row['age_in_years'] >= 24))
						echo "<td><img src=\"/images/girl-black-hair-24+.png\" border=\"0\" /></td>";
					else
						echo "<td><img src=\"/images/girl-black-hair.gif\" border=\"0\" /></td>";
				}
				else
				{
					echo "<td></td>";
				}*/

				echo "<td></td>";

				foreach( $columns as $column )
				{
					if($column == 'created')
						echo '<td align="center">' . ((isset($row[$column]))?(($row[$column]=='')?'&nbsp':Date::to($row[$column], Date::DATETIME)):'&nbsp') . '</td>';
					else
						echo '<td align="center">' . ((isset($row[$column]))?(($row[$column]=='')?'&nbsp':$row[$column]):'&nbsp') . '</td>';
				}

				if ( is_object($loc) )
				{
					echo '<td>'.$distance.' miles</td>';
				}

				echo '</tr>';

			}
			echo '</tbody></table>';
			echo '</div>';
			if ( !is_numeric($search_distance) )
			{
				echo $this->getViewNavigator();
			}
		}
	}

	public $query = NULL;
}
?>
