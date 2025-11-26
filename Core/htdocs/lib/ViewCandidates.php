<?php
class ViewCandidates extends View
{

	public static function getInstance(PDO $link)
	{
		$key = 'view_'.__CLASS__;

		if(!isset($_SESSION[$key]))
		{
			$user_access_limit = '';
			if ( isset($_SESSION['user']->department) ) {
				$user_access_limit = " and ( organisations.region = '{$_SESSION['user']->department}' or candidate.region = '{$_SESSION['user']->department}' ) ";
			}

			if(DB_NAME == "am_demo" || DB_NAME == "am_baltic_demo" || DB_NAME == "am_baltic" || DB_NAME == "am_ray_recruit" || DB_NAME == "am_lcurve_demo")
			{
				$sql = <<<HEREDOC
	SELECT
			timestampdiff(YEAR,candidate.dob,CURDATE()) AS age_in_years,
			timestampdiff(MONTH,candidate.dob,CURDATE()) MOD 12 AS age_in_months,
			candidate.*,
			candidate.borough as town,
			DATE_FORMAT(candidate.dob, '%d/%m/%Y') AS dob,
			CONCAT(IFNULL(candidate.address1,''), ' ', IFNULL(candidate.address2,''), ' ', IFNULL(candidate.county,'')) AS address,
			candidate.created AS registered,
			vacancies.code,
			vacancies.description,
			vacancies.job_title,
			organisations.region,
			COUNT(candidate_applications.application_id) AS applications,
			candidate_applications.application_status as vacancy_status,
			COUNT(candidate_notes.candidate_id) AS note_count,
			candidate_notes.note AS last_note,
			(SELECT LEFT(CONCAT(Ethnicity, ' ', Ethnicity_Desc),60)  FROM lis201314.ilr_ethnicity WHERE candidate.ethnicity = Ethnicity) AS ethnicity,
			(SELECT description FROM lookup_source WHERE lookup_source.id = candidate.source) AS candidate_source,
			(SELECT description FROM lookup_candidate_status WHERE id = candidate.status_code) AS candidate_status
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
			LEFT JOIN candidate_sector_choice ON
			( candidate.id = candidate_sector_choice.candidate_id )
	WHERE
			( candidate.username IS NULL OR candidate.username = '' )
			$user_access_limit
	GROUP BY
			candidate.id;
HEREDOC;
			}
			else
			{
				$sql = <<<HEREDOC
	SELECT
			timestampdiff(YEAR,candidate.dob,CURDATE()) AS age_in_years,
			timestampdiff(MONTH,candidate.dob,CURDATE()) MOD 12 AS age_in_months,
			candidate.*,
			candidate.borough as town,
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

			}

			$view = $_SESSION[$key] = new ViewCandidates($link);
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

			if(DB_NAME=="am_baltic_demo" || DB_NAME=="am_demo" || DB_NAME=="am_baltic" || DB_NAME=="ams" || DB_NAME == "am_ray_recruit" || DB_NAME == "am_lcurve_demo")
			{
				// status filter
				$options = DAO::getResultset($link, "SELECT DISTINCT id, description, NULL, CONCAT('WHERE candidate.status_code=', CHAR(39), id, CHAR(39)) FROM lookup_candidate_status");
				$options[] = array('NULL', 'Blank Status', NULL, ' WHERE candidate.status_code IS NULL ');
			}
			else
			{
				// new / unassigned filter
				$options = array(
					0=>array(1, 'Has applied for a vacancy', null, 'HAVING applications > 0'),
					1=>array(2, 'Is not linked to a vacancy', null, 'HAVING applications = 0'),
					2=>array(3, 'Has been approved for a vacancy', null, 'WHERE candidate_applications.application_status = 1',),
					3=>array(4, 'Has been removed from a vacancy', null, 'WHERE candidate_applications.application_status = 2 '),
				);
			}

			$f = new DropDownViewFilter('filter_appliedfor', $options, null, true);
			$f->setDescriptionFormat("Candidate Status: %s");
			$view->addFilter($f);

			// been screened ( more than two comments  two are added during registration )
			$options = array(
				0=>array(1, 'Requires Screening', null, 'where candidate_applications.has_been_screened = 0 OR candidate_applications.has_been_screened IS NULL'),
				1=>array(2, 'Has Been Screened', null, 'where candidate_applications.has_been_screened = 1 ')
			);
			$f = new DropDownViewFilter('filter_screened', $options, null, true);
			$f->setDescriptionFormat("Candidate Screened: %s");
			$view->addFilter($f);

			// applicant status
			// ----
			$options = "SELECT DISTINCT application_comments, application_comments, null,  CONCAT('WHERE candidate_applications.application_comments=',char(39),application_comments,char(39)) FROM candidate_applications WHERE application_comments IS NOT NULL ORDER BY application_comments ASC;";
			$f = new DropDownViewFilter('filter_applicant_status', $options, null, true);
			$f->setDescriptionFormat("Candidate Status: %s");
			$view->addFilter($f);

			if(DB_NAME=="ams" || DB_NAME=="am_demo" || DB_NAME=="am_baltic" || DB_NAME == "am_ray_recruit" || DB_NAME == "am_lcurve_demo")
			{
				$options = "SELECT Ethnicity AS id, LEFT(CONCAT(Ethnicity, ' ', Ethnicity_Desc),60) AS description, null, CONCAT('WHERE candidate.ethnicity=',Ethnicity) FROM lis201314.ilr_ethnicity";
				$f = new DropDownViewFilter('filter_ethnicity', $options, null, true);
				$f->setDescriptionFormat("Ethnicity: %s");
				$view->addFilter($f);

				$options = "SELECT DISTINCT id, description, NULL, CONCAT('WHERE candidate_sector_choice.sector = ', CHAR(39), id, CHAR(39)) FROM lookup_vacancy_type ORDER BY description;";
				$f = new DropDownViewFilter('filter_cand_interests', $options, null, true);
				$f->setDescriptionFormat("Candidates With Interest In: %s");
				$view->addFilter($f);

				$options = "SELECT DISTINCT id, description, NULL, CONCAT('WHERE candidate.source = ', CHAR(39), id, CHAR(39)) FROM lookup_source ORDER BY description;";
				$f = new DropDownViewFilter('filter_cand_source', $options, null, true);
				$f->setDescriptionFormat("Candidate Source: %s");
				$view->addFilter($f);

				$options = array(
					0=>array(0, 'Show all', null, null),
					1=>array(1, 'Candidate', null, 'WHERE candidate.applied_directly = 1 AND candidate.has_been_screened = 0'),
					2=>array(2, 'System User', null, 'WHERE candidate.applied_directly = 0 AND candidate.has_been_screened = 0'));
				$f = new DropDownViewFilter('filter_applied_directly', $options, 0, false);
				$f->setDescriptionFormat("Candidate Record Created By: %s");
				$view->addFilter($f);

				$options = array(
					0=>array(0, 'Show all', null, null),
					1=>array(1, 'Yes', null, 'where candidate.jobatar = 1'),
					2=>array(2, 'No', null, 'where candidate.jobatar = 2 ')
				);
				$f = new DropDownViewFilter('filter_jobatar', $options, 0, false);
				$f->setDescriptionFormat("Candidate Jobatar: %s");
				$view->addFilter($f);
			}
			// ------
			// screening score filter
			// this needs changing dramatically
			/*
			   $options = array(
				   0=>array(1, 'Is a green candidate', null, 'WHERE candidate.screening_score >= 70'),
				   1=>array(2, 'Is an amber candidate', null, 'WHERE candidate.screening_score < 70 and candidate.screening_score >= 45'),
				   2=>array(3, 'Is a red candidate', null, 'WHERE candidate.screening_score < 45'),
				   );
   */
			$options = array(
				0=>array(1, 'Is a green candidate', null, 'WHERE candidate_applications.application_screening >= 70'),
				1=>array(2, 'Is an amber candidate', null, 'WHERE candidate_applications.application_screening < 70 and candidate_applications.application_screening >= 45'),
				2=>array(3, 'Is a red candidate', null, 'WHERE candidate_applications.application_screening < 45'),
			);
			$f = new DropDownViewFilter('filter_screening', $options, null, true);
			$f->setDescriptionFormat("Applications Screening: %s");
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
			$format = "WHERE date(candidate.created) >= '%s'";
			$f = new DateViewFilter('start_date', $format, '');
			$f->setDescriptionFormat("Registered after: %s");
			$view->addFilter($f);

			// Calculate the timestamp for the end of this week
			$timestamp = time() + ((60*60*24) * (7 - $weekday));

			$format = "WHERE date(candidate.created) <= '%s'";
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
			$options = "SELECT DISTINCT gender, IF(gender='F','Female',IF(gender='M','Male',IF(gender='U','Unknown',IF(gender='W','Witheld','')))), null, CONCAT('WHERE candidate.gender=',char(39),gender,char(39)) FROM candidate";
			$f = new DropDownViewFilter('filter_gender', $options, null, true);
			$f->setDescriptionFormat("Gender: %s");
			$view->addFilter($f);

			// Candidate Name Filter 
			$f = new TextboxViewFilter('filter_firstnames', "WHERE LOWER(candidate.firstnames) LIKE LOWER('%%%s%%')", null);
			$f->setDescriptionFormat("Firstname contains: %s");
			$view->addFilter($f);

			$f = new TextboxViewFilter('filter_surname', "WHERE LOWER(candidate.surname) LIKE LOWER('%%%s%%')", null);
			$f->setDescriptionFormat("Surname contains: %s");
			$view->addFilter($f);

			//re 08/11/2011 - hack, but why?
			$f = new TextboxViewFilter('filter_postcodes', "WHERE candidate.easting is not null and '%s' is not null", null);
			$f->setDescriptionFormat("Distance from: %s");
			$view->addFilter($f);

			//re 08/11/2011 - hack, but why?
			$f = new TextboxViewFilter('filter_distance', "WHERE candidate.northing is not null and '%s' is not null", null);
			$f->setDescriptionFormat("Within in %s miles");
			$view->addFilter($f);

			if ( !isset($_SESSION['user']->department) ) {
				$options = "select description, description, null, CONCAT('WHERE candidate.region = ',char(39),description,char(39)) from lookup_vacancy_regions order by description";
				$f = new DropDownViewFilter('filter_region', $options, null, true);
				$f->setDescriptionFormat("Region: %s");
				$view->addFilter($f);
			}

			// Add age filter
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

		// re 08/11/2011 - rttg request
		// sort out the postcode distance column if requested.
		// -----
		$loc = NULL;
		$longitude = NULL;
		$latitude = NULL;
		$easting = NULL;
		$northing = NULL;

		$search_distance = NULL;

		$candidate_sql = $this->getSQL();

		// re - HELP!! how do you have a textboxfilter with no sql related content 
		// paginatable??
		// nasty hack number xxx !!!
		// re - updated the preg match, as was incorrectly matching the postcode.
		if ( preg_match("/easting is not null and \'(.*)\' is not null\) AND/", $candidate_sql, $postcode) ) {
			$loc = new GeoLocation();
			$loc->setPostcode($postcode[1], $link);
			$longitude = $loc->getLongitude();
			$latitude = $loc->getLatitude();
			$easting = $loc->getEasting();
			$northing = $loc->getNorthing();
		}

		if ( preg_match("/northing is not null and \'(.*)\' is not null/", $candidate_sql, $set_distance) ) {
			$search_distance = $set_distance[1];
			$candidate_sql = preg_replace("/LIMIT (.*)$/ ","", $candidate_sql);
		}

		// re: need to update the query to
		// allow for postcode matching in query
		//  -- I don't like this either!
		if ( is_object($loc) && is_numeric($search_distance) ) {
			$distance_check = 'AND (SQRT(POWER(ABS('.$easting.' - candidate.easting), 2) + POWER(ABS('.$northing.' - candidate.northing), 2)))/1609.344 <= '.$search_distance.' GROUP BY';
			$candidate_sql = preg_replace("/GROUP BY/ ",$distance_check, $candidate_sql);
		}
		$this->query = $candidate_sql;
		$st = $link->query($candidate_sql);
		if( $st ) {
			if ( !is_numeric($search_distance) ) {
				echo $this->getViewNavigator();
			}
			echo '<div align="center"><table class="resultset" border="0" cellspacing="0" cellpadding="6">';
			echo '<thead><tr>';
			echo '<th>&nbsp;</th>';
			foreach($columns as $column)
			{
				echo '<th>' . ucwords(str_replace("_"," ",str_replace("_and_"," & ",$column))) . '</th>';
			}
			// re 08/11/2011 - rttg request 
			// find out distance
			// ----
			if ( is_object($loc) ) {
				echo '<th>Distance</th>';
			}
			// ----
			echo '<th>Last action</th>';
			echo '<th>&nbsp;</th>';
			echo '</thead>';
			echo '<tbody>';
			$row_count = 1;
			while( $row = $st->fetch() ) {

				$candidate_id = $row['id'];

				$candidate_details = Candidate::loadFromDatabase($link, $candidate_id);
				$candidate_details->age = '';
				$candidate_details->learner_name = '';
				$candidate_details->distance = '';
				// load the candidate history
				$candidate_details->candidate_notes = CandidateNotes::loadFromDatabase($link, $candidate_id);

				// re 08/11/2011 - rttg request 
				// find out distance
				// ----
				if ( is_object($loc) ) {
					$distance = sqrt(pow(abs($easting - $row['easting']),2)+pow(abs($northing - $row['northing']),2));
					$distance = sprintf("%.2f", $distance/1609.344);
					// if ( $search_distance != NULL && is_numeric($search_distance) ) {
					// 	if ( $distance >= $search_distance ) {
					// 		continue;
					// 	}	
					// }
				}

				if ( $row_count % 2 ) {
					$row_style = 'background-color: #F9F9F9';
				}
				else {
					$row_style = 'background-color: #FFFFFF';
				}
				$row_count++;
				// ----
				echo '<tr id="user_1_'.$candidate_id.'" style="'.$row_style.'" class="shortrecord" >';
				$age = Date::dateDiffInfo(date("Y-m-d"),$row['dob']);
				$years = $age['year'];

				if($row['gender'] == 'M')
				{
					if(($years >= 24))
						echo "<td><img src=\"/images/boy-blonde-hair-24+.png\" border=\"0\" /></td>";
					//elseif(($years == 24))
					//	echo "<td><img src=\"/images/boy-blonde-hair-24.png\" border=\"0\" /></td>";
					else
						echo "<td><img src=\"/images/boy-blonde-hair.gif\" border=\"0\" /></td>";
				}
				elseif($row['gender'] == 'F')
				{
					if($years >= 24)
						echo "<td><img src=\"/images/girl-black-hair-24+.png\" border=\"0\" /></td>";
					//elseif($years == 24)
					//	echo "<td><img src=\"/images/girl-black-hair-24.png\" border=\"0\" /></td>";
					else
						echo "<td><img src=\"/images/girl-black-hair.gif\" border=\"0\" /></td>";
				}
				else
				{
					echo "<td></td>";
				}

				foreach( $columns as $column ) {
					if( $column=='name' ) {
						if(isset($column))  echo '<td align="left" >' . ($row[$column]==''?'&nbsp':$row[$column]) . '</td>';
					}
					if( $column=='age' ) {
						if(isset($column))  echo '<td align="left" >' . ($row['dob']==''?'&nbsp':Date::dateDiff(date("Y-m-d"),$row['dob'])) . '</td>';
					}
					else if( $column == 'last_login' ) {
						if( empty($row["$column"]) ) {
							echo '<td align="left" >n/a</td>';
						}
						else
						{
							if(isset($column))  echo '<td align="left" >' . $row[$column] . '</td>';
						}
					}
					else
					{
						if(isset($column)) echo '<td align="left" >' . ($row[$column]==''?'&nbsp':$row[$column]) . '</td>';
					}
				}

				$comment_html = '<td>&nbsp;</td>';

				foreach ( $candidate_details->candidate_notes->comments as $comment ) {
					if ( $comment['status'] == 0 ) {
						$comment_html = '<td>'.$comment['note'].' ('.$comment['username'].')</td>';
						break;
					}
				}

				// re 08/11/2011 - rttg request 
				// display distance
				// ----
				if ( is_object($loc) ) {
					echo '<td>'.$distance.' miles</td>';
				}
				// -----

				echo $comment_html;
				if($_SESSION['user']->type != User::TYPE_SYSTEM_VIEWER)
					echo '<td><a href="#" onclick="displaydetail(\'1_'.$candidate_id.'\'); return false;">&nbsp; View &raquo; </a>';
				else
					echo '<td>';
				if(DB_NAME=="am_baltic_demo" || DB_NAME=="am_demo" || DB_NAME=="am_baltic" || DB_NAME=="ams" || DB_NAME == "am_ray_recruit" || DB_NAME == "am_lcurve_demo")
					echo '<a href="do.php?_action=read_candidate&amp;candidate_id='.$candidate_id.'">&nbsp; Detailed View &raquo; </a></td>';
				if(DB_NAME=='am_pathway')
					echo '<a href="#" onclick="delete_candidate(' . $candidate_id . ');">&nbsp; Delete &raquo; </a></td>';
				echo '</tr>';

				echo '<tr id="detail_1_'.$candidate_id.'" style="display:none;">';
				echo '	<td colspan="11" style="text-align:center" >';
				echo ' <img src="images/candidate_loader.gif" /> ';
				echo '	</td>';
				echo '</tr>';
			}
			echo '</tbody></table>';
			echo '</div>';
			if ( !is_numeric($search_distance) ) {
				echo $this->getViewNavigator();
			}
		}
	}

	/**
	 *
	 * Report on candidate status by region / screening score
	 * @param PDO $link
	 * @param unknown_type $columns
	 */
	public function render_report(PDO $link, $columns) {

		/*$region_dropdown = array(
			'North West' => array(),
			'North East' => array(),
			'Midlands' => array(),
			'East Midlands' => array(),
			'West Midlands' => array(),
			'London North' => array(),
			'London South' => array(),
			'Peterborough' => array(),
			'Yorkshire' => array(),
		);*/

		$region_dropdown = 'select description, description, null from lookup_vacancy_regions order by description;';
		$region_dropdown = DAO::getResultset($link, $region_dropdown);



		$st = $link->query($this->getSQL());
		if( $st ) {
			// row style
			$row_style = '';
			// establish row colors
			$row_colors = array(
				'high' => '#E0EAD0',
				'med' => '#FFE6D7',
				'low' => '#FFBFBF');

			echo '<div id="tabs">';

			echo '<ul>';
			$region_tab = 1;
			foreach($region_dropdown as $region => $content ) {
				echo '<li><a href="#tab-'.$region_tab.'">'.$region.'</a></li>';
				$region_tab++;
			}
			echo '<li><a href="#tab-'.$region_tab.'">No Region</a></li>';
			echo '</ul>';

			while( $row = $st->fetch() ) {
				$candidate_id = $row['id'];

				$candidate_details = Candidate::loadFromDatabase($link, $candidate_id);
				$candidate_details->age = '';
				$candidate_details->learner_name = '';
				$candidate_details->distance = '';
				// load the candidate history
				$candidate_details->candidate_notes = CandidateNotes::loadFromDatabase($link, $candidate_id);

				$screen_level = 'low';
				if ( $row['screening_score'] >= 45 && $row['screening_score'] <= 70 ) {
					$screen_level = 'med';
				}
				else if ( $row['screening_score'] >= 70 ) {
					$screen_level = 'high';
				}
				if ( !isset($region_dropdown[$row['region']][$screen_level]) ) {
					$region_dropdown[$row['region']][$screen_level] = '';
				}

				$region_dropdown[$row['region']][$screen_level] .= '<tr id="user_1_'.$candidate_id.'" style="background-color:'.$row_colors[$screen_level].'">';
				foreach( $columns as $column ) {
					if( $column=='name' ) {
						$region_dropdown[$row['region']][$screen_level] .= '<td align="left" style="'.$row_style.'" >' . ($row[$column]==''?'&nbsp':$row[$column]) . '</td>';
					}
					else if( $column == 'last_login' ) {
						if( empty($row["$column"]) ) {
							$region_dropdown[$row['region']][$screen_level] .= '<td align="left" style="'.$row_style.'" >n/a</td>';
						}
						else
						{
							$region_dropdown[$row['region']][$screen_level] .= '<td align="left" style="'.$row_style.'">' . $row[$column] . '</td>';
						}
					}
					else
					{
						$region_dropdown[$row['region']][$screen_level] .= '<td align="left" style="'.$row_style.'">' . ($row[$column]==''?'&nbsp':$row[$column]) . '</td>';
					}
				}
				if ( isset($candidate_details->candidate_notes->comments[0]['note']) ) {
					$region_dropdown[$row['region']][$screen_level] .= '<td>'.$candidate_details->candidate_notes->comments[0]['note'].' ('.$candidate_details->candidate_notes->comments[0]['username'].')</td>';
				}
				else {
					$region_dropdown[$row['region']][$screen_level] .= '<td>&nbsp;</td>';
				}
				$region_dropdown[$row['region']][$screen_level] .= '<td><a href="#" onclick="displaydetail(\'1_'.$candidate_id.'\'); return false;">view</a></td>';
				$region_dropdown[$row['region']][$screen_level] .= '</tr>';
				$region_dropdown[$row['region']][$screen_level] .= $candidate_details->render_candidate_details($link, 1);
				$region_dropdown[$row['region']][$screen_level] .= '</td>';
				$region_dropdown[$row['region']][$screen_level] .= '</tr>';
			}

			$region_tab = 1;
			foreach ( $region_dropdown as $region => $region_content ) {

				echo '<div id="tab-'.$region_tab.'" >';
				ksort($region_content);
				foreach ( $region_content as $candidate_level => $candidates ) {
					echo '<h3>Candidates with a '.$candidate_level.' screening</h3>';
					echo '<table class="resultset" border="0" cellspacing="0" cellpadding="6" style="width:90%" >';
					echo '<thead><tr>';
					foreach( $columns as $column )
					{
						if(isset($column)) echo '<th>' . ucwords(str_replace("_"," ",str_replace("_and_"," & ",$column))) . '</th>';
					}
					echo '<th>Last action</th>';
					echo '<th>&nbsp;</th>';
					echo '</thead>';
					echo '<tbody>';
					echo $candidates;
					echo '</tbody></table>';
				}
				echo '</div>';
				$region_tab++;
			}
		}
	}

	public static function homepageDashboard(PDO $link) {

		$all_candidates = 0;
		$new_candidates = 0;
		$screened_candidates = 0;
		$approved_candidates = 0;
		$unassigned_candidates = 0;

		$view = ViewCandidates::getInstance($link);
		$view->refresh($link, $_REQUEST);

		$view->resetFilters();
		$view->filters["__page_size"]->setValue('0');
		$st = $link->query($view->getSQL());
		if( $st ) {
			$all_candidates =  $st->rowCount();
		}

		$view->resetFilters();
		$view->filters["__page_size"]->setValue('0');
		$view->filters["filter_screened"]->setValue('1');
		$st = $link->query($view->getSQL());
		if( $st ) {
			$new_candidates =  $st->rowCount();
		}

		$view->resetFilters();
		$view->filters["__page_size"]->setValue('0');
		$view->filters["filter_screened"]->setValue('2');
		$st = $link->query($view->getSQL());
		if( $st ) {
			$screened_candidates =  $st->rowCount();
		}

		$view->resetFilters();
		$view->filters["__page_size"]->setValue('0');
		$view->filters["filter_appliedfor"]->setValue('2');
		$st = $link->query($view->getSQL());
		if( $st ) {
			$unassigned_candidates =  $st->rowCount();
		}

		echo '<tr><td colspan="2"><h3>Recruitment Manager</h3></td></tr>';

		echo '<tr>';
		echo '<td colspan="2">';
		echo '<table><tr><td colspan="3">';
		echo 'Find Candidate:</td>';
		echo '</tr><tr>';
		echo '<td>';
		echo '<form action="do.php" name="find_candidate" >';
		echo '<input type="hidden" name="_action" value="view_candidates" /> ';
		echo '<input type="hidden" name="ViewCandidates_filter_appliedfor" value="" /> ';
		echo '<input type="hidden" name="ViewCandidates_filter_screened" value="" /> ';
		echo 'firstname</td><td>surname</td><td>&nbsp;</td></tr>';
		echo '<tr><td><input id="ViewCandidates_filter_firstnames" type="text" value="" name="ViewCandidates_filter_firstnames">';
		echo '<td><input id="ViewCandidates_filter_surname" type="text" value="" name="ViewCandidates_filter_surname">';
		echo '</td>';
		echo '<td>';
		echo '<input type="submit" name="search_candidates" value="go &raquo;" />';
		echo '</form>';
		echo '</td>';
		echo '</tr>';

		echo '<tr>';
		echo '<td colspan="3">Find Employer:</h</td></tr>';
		echo '<tr><td>&nbsp;</td><td>';
		echo '<form action="do.php" name="find_vacancies" >';
		echo '<input type="hidden" name="_action" value="view_vacancies" /> ';
		echo '<input id="ViewVacancies_filter_employername" type="text" value="" name="ViewVacancies_filter_employername">';
		echo '</td>';
		echo '<td>';
		echo '<input type="submit" name="search_candidates" value="go &raquo;" />';
		echo '</td>';
		echo '</tr>';
		echo '</form>';

		echo '</table>';
		echo '</td>';
		echo '</tr>';

		echo '<tr><td>Number of candidates waiting to be screened for vacancies:</td>';
		echo '<td><a href="/do.php?_action=view_candidates&amp;ViewCandidates_filter_screened=1&amp;ViewCandidates_filter_appliedfor=">';
		echo $new_candidates;
		echo '</a><td></tr>';

		echo '<tr><td>Number of candidates screened for vacancies:</td>';
		echo '<td><a href="/do.php?_action=view_candidates&amp;ViewCandidates_filter_screened=2&amp;ViewCandidates_filter_appliedfor=">';
		echo $screened_candidates;
		echo '</a><td></tr>';

		echo '<tr><td>Number of candidates not attached to vacancies:</td>';
		echo '<td><a href="/do.php?_action=view_candidates&amp;ViewCandidates_filter_screened=&amp;ViewCandidates_filter_appliedfor=2">';
		echo $unassigned_candidates;
		echo '</a><td></tr>';

		echo '<tr><td>Total applicants:</td>';
		echo '<td><a href="/do.php?_action=view_candidates&amp;ViewCandidates_filter_screened=&amp;ViewCandidates_filter_appliedfor=">';
		echo $all_candidates;
		echo '</a><td></tr>';

	}

	public $query = NULL;
}
?>
