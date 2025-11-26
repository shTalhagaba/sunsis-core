<?php
class VacanciesHome extends View
{

	public static function getInstance(PDO $link)
	{
		$key = 'view_'.__CLASS__;

		if(!isset($_SESSION[$key]))
		{
			$user_access_limit = '';
			if ( isset($_SESSION['user']->department) ) {
				$user_access_limit = " and organisations.region = '{$_SESSION['user']->department}'";	
			}
			
			
			$sql = <<<HEREDOC
SELECT	candidate.*, 
		CONCAT(candidate.address1, ' ', candidate.address2, ' ', candidate.county ) AS address,
		candidate.created AS registered,
		vacancies.code,
		vacancies.description,
		vacancies.job_title,
		organisations.region,
		COUNT(candidate_applications.application_id) AS applications,
		candidate_applications.application_status as vacancy_status,
		COUNT(candidate_notes.candidate_id) AS note_count,
		candidate_notes.note AS last_note
FROM 	candidate 
		LEFT JOIN 
		candidate_notes
		ON ( candidate.id = candidate_notes.candidate_id )
		LEFT JOIN
		candidate_applications
		ON (candidate.id = candidate_applications.candidate_id)
		LEFT JOIN vacancies
		ON (candidate_applications.vacancy_id = vacancies.id)
		LEFT JOIN organisations
		ON (vacancies.employer_id = organisations.id)
WHERE
		( candidate.username IS NULL OR candidate.username = '' )
		$user_access_limit
GROUP BY 
		candidate.id;
HEREDOC;

			$view = $_SESSION[$key] = new ViewCandidates($link);
			$view->setSQL($sql);
			
		// new / unassigned filter
			$options = array(
				0=>array(1, 'Has applied for a vacancy', null, 'HAVING applications > 0'),
				1=>array(2, 'Is not linked to a vacancy', null, 'HAVING applications = 0'),
				2=>array(3, 'Has been approved for a vacancy', null, 'WHERE candidate_applications.application_status = 1',),
				3=>array(4, 'Has been removed from a vacancy', null, 'WHERE candidate_applications.application_status = 2 '),
				);
				
			$f = new DropDownViewFilter('filter_appliedfor', $options, null, true);
			$f->setDescriptionFormat("Candidate Status: %s");
			$view->addFilter($f);
			
			// been screened ( more than two comments  two are added during registration )
			if(DB_NAME=="am_demo")
			{
				$options = array(
					0=>array(1, 'Requires Screening', null, 'where candidate_applications.has_been_screened = 0 OR where candidate_applications.has_been_screened IS NULL'),
					1=>array(2, 'Has Been Screened', null, 'where candidate_applications.has_been_screened = 1 ')
				);
			}
			else
			{
				$options = array(
					0=>array(1, 'Requires Screening', null, 'where candidate_applications.has_been_screened = 0'),
					1=>array(2, 'Has Been Screened', null, 'where candidate_applications.has_been_screened = 1 ')
					);
			}
			$f = new DropDownViewFilter('filter_screened', $options, null, true);
			$f->setDescriptionFormat("Candidate Screened: %s");
			$view->addFilter($f);
			
			// screening score filter
			// this needs changing dramatically
			$options = array(
				0=>array(1, 'Is a green candidate', null, 'WHERE candidate.screening_score >= 70'),
				1=>array(2, 'Is an amber candidate', null, 'WHERE candidate.screening_score < 70 and candidate.screening_score >= 45'),
				2=>array(3, 'Is a red candidate', null, 'WHERE candidate.screening_score < 45'),
				);
			$f = new DropDownViewFilter('filter_screening', $options, null, true);
			$f->setDescriptionFormat("Candidate Screening: %s");
			$view->addFilter($f);
			
			// Surname Sort
			$options = array(
				0=>array(1, 'Surname (asc)', null, 'ORDER BY surname'),
				1=>array(2, 'Surname (desc)', null, 'ORDER BY surname DESC'),
				2=>array(3, 'Registration Date (asc)', null, 'ORDER by registered'),
				3=>array(4, 'Registration Date (desc)', null, 'ORDER by registered DESC'));
			$f = new DropDownViewFilter('order_by', $options, 1, false);
			$f->setDescriptionFormat("Sort by: %s");
			$view->addFilter($f);	

			// Date filters	
			// ---
			$dateInfo = getdate();
			$weekday = $dateInfo['wday']; // 0 (Sun) -> 6 (Sat)
			$timestamp = time()  - ((60*60*24) * $weekday);
		
			// Rewind by a week
			$timestamp = $timestamp - ((60*60*24) * 7);
				
			// Start Date Filter
			$format = "WHERE candidate.created >= '%s'";
			$f = new DateViewFilter('start_date', $format, '');
			$f->setDescriptionFormat("Registered after: %s");
			$view->addFilter($f);

			// Calculate the timestamp for the end of this week
			$timestamp = time() + ((60*60*24) * (7 - $weekday));
		
			$format = "WHERE candidate.created <= '%s'";
			$f = new DateViewFilter('end_date', $format, '');
			$f->setDescriptionFormat("Registered before: %s");
			$view->addFilter($f);	
			// ---
			
			// Age filter
			// ---
			$options = array();
			$option_count = 1;
			for( $age=16; $age<=50; $age++ ) {
				array_push($options, array($option_count, $age, null, "WHERE (DATE_FORMAT(NOW(), '%Y') - DATE_FORMAT(candidate.dob, '%Y') - (DATE_FORMAT(NOW(), '00-%m-%d') < DATE_FORMAT(candidate.dob, '00-%m-%d'))) = ".$age ));
				$option_count++;
			}
			$f = new DropDownviewFilter('filter_age', $options, null, true);
			$f->setDescriptionFormat("Age: %s");
			$view->addFilter($f);
			// ---
						
			// Gender filter
			$options = "SELECT DISTINCT gender, gender, null, CONCAT('WHERE candidate.gender=',char(39),gender,char(39)) FROM candidate";
			$f = new DropDownViewFilter('filter_gender', $options, null, true);
			$f->setDescriptionFormat("Gender: %s");
			$view->addFilter($f);
			
			// Candidate Name Filter 
			$f = new TextboxViewFilter('filter_firstnames', "WHERE candidate.firstnames LIKE '%%%s%%'", null);
			$f->setDescriptionFormat("Firstname contains: %s");
			$view->addFilter($f);
				
			$f = new TextboxViewFilter('filter_surname', "WHERE candidate.surname LIKE '%%%s%%'", null); 
			$f->setDescriptionFormat("Surname contains: %s");
			$view->addFilter($f);
			
			if ( !isset($_SESSION['user']->department) ) {
				/*$options = array(
					0 => array(1, 'North West', null, ' WHERE organisations.region = "North West" '),
					1 => array(2, 'North East', null, ' WHERE organisations.region = "North East" '),
					2 => array(3, 'Midlands', null, ' WHERE organisations.region = "Midlands" '),
					3 => array(4, 'East Midlands', null, ' WHERE organisations.region = "East Midlands" '),
					4 => array(5, 'West Midlands', null, ' WHERE organisations.region = "West Midlands" '),
					5 => array(6, 'London North', null, ' WHERE organisations.region = "London North" '),
					6 => array(7, 'London South', null, ' WHERE organisations.region = "London South" '),
					7 => array(8, 'Peterborough', null, ' WHERE organisations.region = "Peterborough" '),
					8 => array(9, 'Yorkshire', null, ' WHERE organisations.region = "Yorkshire" '),
				);*/
				$options = "select description, description, null, CONCAT(' WHERE organisations.region = ',char(39), description, char(39)) from lookup_vacancy_regions order by description;";
				// $options = DAO::getResultset($link, $region_dropdown);
				$f = new DropDownViewFilter('filter_region', $options, null, true);
				$f->setDescriptionFormat("Region: %s");
				$view->addFilter($f);		
			}
			
			
			
		}
		return $_SESSION[$key];
	}
	
	
	public function render(PDO $link, $columns)	{
	}
	
	/**
	 * 
	 * Report on candidate status by region / screening score
	 * @param PDO $link
	 * @param unknown_type $columns
	 */
	public function render_report(PDO $link, $columns) {	
	}
	
	public static function homepageDashboard(PDO $link) {
		
		$all_candidates = 0;
		$new_candidates = 0;
		$screened_candidates = 0;
		$approved_candidates = 0;
		$unassigned_candidates = 0;
		
		$view = VacanciesHome::getInstance($link);
		$view->refresh($link, $_REQUEST);
		
		$view->resetFilters();
		$st = $link->query($view->getSQL());
		if( $st ) {
			$all_candidates =  $st->rowCount();
		}
		
		$view->resetFilters();
		$view->filters["filter_screened"]->setValue('1');
		if(DB_NAME=="am_demo")
			$view->filters["filter_appliedfor"]->setValue('1');
		$st = $link->query($view->getSQL());
		if( $st ) {
			$new_candidates =  $st->rowCount();
		}
		
		$view->resetFilters();
		$view->filters["filter_screened"]->setValue('2');
		if(DB_NAME=="am_demo")
			$view->filters["filter_appliedfor"]->setValue('1');
		$st = $link->query($view->getSQL());
		if( $st ) {
			$screened_candidates =  $st->rowCount();
		}
		
		$view->resetFilters();
		$view->filters["filter_appliedfor"]->setValue('2');
		$st = $link->query($view->getSQL());
		if( $st ) {
			$unassigned_candidates =  $st->rowCount();
		}

		echo '<div>';
		if(DB_NAME=="am_demo")
		{
			echo '	Number of candidates who applied for vacancies and waiting to be screened: ';
			echo '	<a href="/do.php?_action=view_candidates&amp;_reset=1&amp;ViewCandidates_filter_screened=1&amp;ViewCandidates_filter_appliedfor=1">';
		}
		else
		{
			echo '	Number of candidates waiting to be screened for vacancies: ';
			echo '	<a href="/do.php?_action=view_candidates&amp;_reset=1&amp;ViewCandidates_filter_screened=1&amp;ViewCandidates_filter_appliedfor=">';
		}
		echo $new_candidates;
		echo '	</a>';
		echo '</div>';
		
		echo '<div>';
		if(DB_NAME=="am_demo")
		{
			echo '	Number of candidates who applied for vacancies and have been screened:';
			echo '	<a href="/do.php?_action=view_candidates&amp;_reset=1&amp;ViewCandidates_filter_screened=2&amp;ViewCandidates_filter_appliedfor=1">';
		}
		else
		{
			echo '	Number of candidates screened for vacancies:';
			echo '	<a href="/do.php?_action=view_candidates&amp;_reset=1&amp;ViewCandidates_filter_screened=2&amp;ViewCandidates_filter_appliedfor=">';
		}
		echo $screened_candidates;
		echo '	</a>';
		echo '</div>';
		
		echo '<div>';
		echo '	Number of candidates not attached to vacancies:';
		echo '	<a href="/do.php?_action=view_candidates&amp;_reset=1&amp;ViewCandidates_filter_screened=&amp;ViewCandidates_filter_appliedfor=2">';
		echo $unassigned_candidates;
		echo '	</a>';
		echo '</div>';
		
		echo '<div>';
		echo '	Total applicants:';
		echo '	<a href="/do.php?_action=view_candidates&amp;_reset=1&amp;ViewCandidates_filter_screened=&amp;ViewCandidates_filter_appliedfor=">';
		echo $all_candidates;
		echo '	</a>';
		echo '</div>';
		
	}
}
?>
