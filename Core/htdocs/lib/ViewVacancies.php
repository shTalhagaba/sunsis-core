<?php
class ViewVacancies extends View
{
	public static function getInstance(PDO $link)
	{
		$key = 'view_'.__CLASS__;

		$user_access_limit = '';

		if( isset($_SESSION['user']) && ( SystemConfig::getEntityValue($link, "salesman") && !$_SESSION['user']->isAdmin()) ){
			if ( isset($_SESSION['user']->department) ) {
				$user_access_limit = " and organisations.region = '{$_SESSION['user']->department}'";
			}
		}
		$excludeMatchMakerType = "";
		if(DB_NAME=="am_baltic")
		{
			$excludeMatchMakerType = " AND vacancies.type != 14 ";
		}

		if(!isset($_SESSION[$key]))
		{
			// remove the .* in these queries!!
			$sql = <<<HEREDOC
SELECT	
	organisations.*, 
	vacancies.*, 
	lookup_vacancy_type.description as vac_desc, 
	vacancies.id AS vac_id 
FROM 
	vacancies, 
	organisations,
	lookup_vacancy_type,
	locations
WHERE 
	vacancies.employer_id = organisations.id 
and vacancies.type = lookup_vacancy_type.id
AND organisations.id = locations.organisations_id
$excludeMatchMakerType
$user_access_limit
group by vacancies.id
order by organisations.trading_name asc
HEREDOC;

			$view = $_SESSION[$key] = new ViewVacancies();
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

			// new / unassigned filter
			$options = array(
				0=>array(1, 'Is Active/Inactive', null, null),
				1=>array(2, 'Is Active', null, 'WHERE vacancies.active = 1'),
				2=>array(3, 'Is Inactive', null, 'WHERE vacancies.active = 0')
			);
			$f = new DropDownViewFilter('filter_isactive', $options, 2, true);
			$f->setDescriptionFormat("Vacancy Status: %s");
			$view->addFilter($f);

			// Employer Postcode  Filter
			$f = new TextboxViewFilter('filter_employerpostcode', "WHERE LOWER(locations.postcode) LIKE LOWER('%%%s%%')", null);
			$f->setDescriptionFormat("Location Postcode: %s");
			$view->addFilter($f);

			// Employer Name Filter 
			$f = new TextboxViewFilter('filter_employername', "WHERE LOWER(organisations.trading_name) LIKE LOWER('%%%s%%')", null);
			$f->setDescriptionFormat("Employer Name contains: %s");
			$view->addFilter($f);

			if(DB_NAME=="ams" || DB_NAME=="am_baltic" || DB_NAME=="am_baltic_demo" || DB_NAME=="am_demo" || DB_NAME=="am_ray_recruit" || DB_NAME=="am_lcurve_demo")
			{
				//Recruitment Stage
				$options = "SELECT id, description, null, CONCAT('WHERE vacancies.status=',char(39),id,char(39)) FROM lookup_vacancy_status ORDER BY description";
				$f = new DropDownViewFilter('filter_rec_stage', $options, null, true);
				$f->setDescriptionFormat("Recruitment Stage: %s");
				$view->addFilter($f);
			}

			// Sector Type Filter 
			$options = "SELECT DISTINCT id, description, null, CONCAT('WHERE vacancies.type = ',char(39),id,char(39)) FROM lookup_vacancy_type";
			$f = new DropDownViewFilter('filter_sectortype', $options, null, true);
			$f->setDescriptionFormat("Sector Type: %s");
			$view->addFilter($f);

			$f = new TextboxViewFilter('filter_postcodes', "WHERE vacancies.easting is not null and '%s' is not null", null);
			$f->setDescriptionFormat("Distance from: %s");
			$view->addFilter($f);

			$f = new TextboxViewFilter('filter_distance', "WHERE vacancies.northing is not null and '%s' is not null", null);
			$f->setDescriptionFormat("Within in %s miles");
			$view->addFilter($f);

			if(DB_NAME == "am_demo" || DB_NAME == "am_baltic_demo" || DB_NAME=="am_baltic" || DB_NAME=="ams" || DB_NAME=="am_ray_recruit" || DB_NAME=="am_lcurve_demo")
			{
				$options = array(
					0=>array(0, 'Show all', null, null),
					1=>array(2014, '2014', null, ' WHERE YEAR(vacancies.date_expected_to_fill) = 2014 '),
					2=>array(2015, '2015', null, 'WHERE YEAR(vacancies.date_expected_to_fill) = 2015 '));
				$f = new DropDownViewFilter('filter_year_expected_to_fill', $options, null, false);
				$f->setDescriptionFormat("Year Expected To Fill: %s");
				$view->addFilter($f);

				$options = array(
					0=>array(0, 'Show all', null, null),
					1=>array(1, 'January', null, ' WHERE MONTHNAME(vacancies.date_expected_to_fill) = 1 '),
					2=>array(2, 'February', null, ' WHERE MONTHNAME(vacancies.date_expected_to_fill) = 2 '),
					3=>array(3, 'March', null, ' WHERE MONTHNAME(vacancies.date_expected_to_fill) = 3 '),
					4=>array(4, 'April', null, ' WHERE MONTHNAME(vacancies.date_expected_to_fill) = 4 '),
					5=>array(5, 'May', null, ' WHERE MONTHNAME(vacancies.date_expected_to_fill) = 5 '),
					6=>array(6, 'June', null, ' WHERE MONTHNAME(vacancies.date_expected_to_fill) = 6 '),
					7=>array(7, 'July', null, ' WHERE MONTHNAME(vacancies.date_expected_to_fill) = 7 '),
					8=>array(8, 'August', null, ' WHERE MONTHNAME(vacancies.date_expected_to_fill) = 8 '),
					9=>array(9, 'September', null, ' WHERE MONTHNAME(vacancies.date_expected_to_fill) = 9 '),
					10=>array(10, 'October', null, ' WHERE MONTHNAME(vacancies.date_expected_to_fill) = 10 '),
					11=>array(11, 'November', null, ' WHERE MONTHNAME(vacancies.date_expected_to_fill) = 11 '),
					12=>array(12, 'December', null, 'WHERE MONTHNAME(vacancies.date_expected_to_fill) = 12 '));
				$f = new DropDownViewFilter('filter_month_expected_to_fill', $options, null, false);
				$f->setDescriptionFormat("Month Expected To Fill: %s");
				$view->addFilter($f);

				$options = "select id, description, null, CONCAT('WHERE vacancies.region = ',char(39),id,char(39)) from lookup_vacancy_regions order by description";
				$f = new DropDownViewFilter('filter_region', $options, null, true);
				$f->setDescriptionFormat("Region: %s");
				$view->addFilter($f);

				$options = "SELECT DISTINCT username, CONCAT(firstnames, ' ', surname) AS name, null, CONCAT('WHERE vacancies.brm = ',char(39),username,char(39)) FROM users WHERE users.type = 23";
				$f = new DropDownViewFilter('filter_brm', $options, null, true);
				$f->setDescriptionFormat("BRM: %s");
				$view->addFilter($f);

				// Vacancy Filled or not
				$options = array(
					0=>array(0, 'Is Not Filled', null, 'WHERE vacancies.no_of_vacancies > 0'),
					1=>array(1, 'Is Filled', null, 'WHERE vacancies.no_of_vacancies = 0')
				);
				$f = new DropDownViewFilter('filter_vacancy_live', $options, null, true);
				$f->setDescriptionFormat("Filled/Not Filled: %s");
				$view->addFilter($f);

				//vacancy code/reference filter
				$f = new TextboxViewFilter('filter_vacancy_code', "WHERE vacancies.code LIKE '%s%%'", null);
				$f->setDescriptionFormat("Vacancy Code/Reference: %s");
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

			}

		}
		return $_SESSION[$key];
	}


	public function render(PDO $link) {
		/* @var $result pdo_result */
		$loc = NULL;
		$longitude = NULL;
		$latitude = NULL;
		$easting = NULL;
		$northing = NULL;

		$search_distance = NULL;

		$vacancies_sql = $this->getSQL();

		if ( preg_match("/vacancies.easting is not null and \'(.*)\' is not null\) AND/", $vacancies_sql, $postcode) ) {
			$loc = new GeoLocation();
			$loc->setPostcode($postcode[1], $link);
			$longitude = $loc->getLongitude();
			$latitude = $loc->getLatitude();
			$easting = $loc->getEasting();
			$northing = $loc->getNorthing();
		}

		if ( preg_match("/vacancies.northing is not null and \'(.*)\' is not null/", $vacancies_sql, $set_distance) ) {
			$search_distance = $set_distance[1];
			$vacancies_sql = preg_replace("/LIMIT (.*)$/ ","", $vacancies_sql);
		}

		if ( is_object($loc) && is_numeric($search_distance) )
		{
			$distance_check = 'AND (SQRT(POWER(ABS('.$easting.' - vacancies.easting), 2) + POWER(ABS('.$northing.' - vacancies.northing), 2)))/1609.344 <= '.$search_distance.' GROUP BY';
			$vacancies_sql = preg_replace("/group by/ ",$distance_check, $vacancies_sql);
		}

		//echo $vacancies_sql;
		//$st = $link->query($this->getSQL());
		$st = $link->query($vacancies_sql);
		if( $st ) {
			echo $this->getViewNavigator();
			echo '<div align="center" style="display:block;float:clear;" ><table class="resultset sortData" id="dataMatrix" border="0" cellspacing="0" cellpadding="6">';
			if(DB_NAME=="am_demo")
				echo '<thead><tr><th>Employer</th><th>Code/Reference</th><th>Sector</th><th>Job Title</th><th>Total Positions</th><th>Current Applications</th><th>New Applications</th></tr></thead>';
			else
				echo '<thead><tr><th class="topRow">Employer</th><th class="topRow">Code/Reference</th><th class="topRow">Sector</th><th class="topRow">Job Title</th><th class="topRow">Total Positions</th><th class="topRow">Current Applications</th><th class="topRow">New Applications</th><th class="topRow">Number of Candidates Applied</th></tr></thead>';

			echo '<tbody>';
			$row_count = 1;
			while( $row = $st->fetch() ) {
				$row_style = '';
				//if ( isset($_REQUEST['id'])&& $_REQUEST['id'] == $row['vac_id'] ) {
				if ( $row_count % 2 ) {
					$row_style = 'background-color: #F9F9F9';
				}
				else {
					$row_style = 'background-color: #FFFFFF';
				}
				$row_count++;
				echo HTML::viewrow_opening_tag('/do.php?_action=view_vacancy&pc=' . rawurlencode($row['postcode']) .'&id='.$row['vac_id']);
				echo '<td align="left" style="'.$row_style.'" >' . HTML::cell($row['legal_name']) . '</td>';
				echo '<td align="left" style="'.$row_style.'" >' . HTML::cell($row['code']) . '</td>';
				echo '<td align="left" style="'.$row_style.'" >' . HTML::cell($row['vac_desc']) . '</td>';
				echo '<td align="left" style="'.$row_style.'" >' . HTML::cell($row['job_title']) . '</td>';
				echo '<td align="center" style="'.$row_style.'" >' . HTML::cell($row['no_of_vacancies']) . '</td>';
				echo '<td align="center" style="'.$row_style.'" >';
				// application status is 1 meaning that number of candidates have been approved for the vacancy
				$current_sql = <<<HEREDOC
SELECT
	count(*)
FROM
	candidate, candidate_applications
WHERE
	candidate.id = candidate_applications.candidate_id
AND
	candidate_applications.vacancy_id = {$row['vac_id']}
AND
	candidate_applications.application_status = 1;				
	
HEREDOC;
				echo DAO::getSingleValue($link, $current_sql);
				echo '</td>';
				echo '<td align="center" style="'.$row_style.'" >';
				// application status is
				$current_sql = <<<HEREDOC
SELECT
	count(*)
FROM
	candidate, candidate_applications
WHERE
	candidate.id = candidate_applications.candidate_id
AND
	candidate.enrolled = 1
AND
	candidate_applications.vacancy_id = {$row['vac_id']}
AND
	candidate_applications.application_status is null;
HEREDOC;
				echo DAO::getSingleValue($link, $current_sql);
				echo '</td>';
				if(DB_NAME!="am_demo")
				{
					echo '<td align="center" style="'.$row_style.'" >';
					$current_sql = <<<HEREDOC
SELECT
	count(*)
FROM
	candidate, candidate_applications
WHERE
	candidate.id = candidate_applications.candidate_id
AND
	candidate_applications.vacancy_id = {$row['vac_id']}
AND candidate_applications.application_status != 2
;

HEREDOC;
					echo DAO::getSingleValue($link, $current_sql);
					echo '</td>';
				}
				echo '</tr>';
			}

			echo '</tbody></table></div>';
			echo $this->getViewNavigator();

		}
		else {
			throw new DatabaseException($link, $this->getSQL());
		}
	}

	///////////////////////////
	public function render_candidate_for_baltic(PDO $link) {
		/* @var $result pdo_result */
		// have to reset the filter to allow view all vacancies
		$this->filters["__page_size"]->setValue('0');
		$st = $link->query($this->getSQL());
		if( $st ) {
			$row_count = 0;
			$colspan = 5;
			while( $row = $st->fetch() ) {
				if ( $row_count == 0 ) {
					echo '<h1>Matching Vacancies.</h1>';
					echo '<p>We have found the vacancies below matching your search, select which vacancy you would like to apply for to complete your application</p>';
					echo '<table class="resultset" border="0" cellspacing="0" cellpadding="6" >';
					echo '<thead><tr><th>Vacancy Code</th><th>Vacancy</th><th>Job Title</th>';
					if ( isset($row['distance']) ) {
						echo '<th>Distance</th>';
					}
					echo '<th>Apply for this role</th>';
					echo '<th>Vacancy Details</th>';
					echo '</tr></thead>';
					echo '<tbody>';
				}
				//echo HTML::viewrow_opening_tag('/do.php?_action=view_candidate_register&amp;vac_id='.$row['vac_id']);
				echo '<tr>';
				echo '<td align="left" >' . HTML::cell($row['code']) . '</td>';
				echo '<td align="left" >' . HTML::cell($row['job_title']) . '</td>';
				echo '<td align="left" >' . HTML::cell($row['vac_desc']) . '</td>';
				if ( isset($row['distance']) ) {
					$colspan = 6;
					echo '<td align="center" >'.HTML::cell(sprintf("%.2f",($row['distance'] / METRES_IN_A_MILE))). ' miles</td>';
				}
				echo '<td align="center" >';
				echo '';
				echo '<input type="checkbox" name="vac_id[]" value="'.$row['vac_id'].'" />';
				echo '</td>';
				echo '<td><a href="#" id="link_'.$row['vac_id'].'" onclick="displaydetail(\''.$row['vac_id'].'\');return false;">vacancy details...</a></td>';
				echo '</tr>';
				echo '<tr id="detail_'.$row['vac_id'].'" style="display:none" ><td colspan="'.$colspan.'">';
				echo '<table style="background-color:#ffffff" >';
				echo '<tr><td><strong>Description</strong></td>';
				echo '<td>'.nl2br($row['description']).'</td></tr>';
				echo '<tr><td><strong>Skills Requirement</strong></td>';
				echo '<td>'.nl2br($row['skills_req']).'</td></tr>';
				echo '<tr><td><strong>Training To Be Provided</strong></td>';
				echo '<td>'.nl2br($row['training_provided']).'</td></tr>';
				echo '<tr><td><strong>Personal Qualities</strong></td>';
				echo '<td>'.nl2br($row['person_spec']).'</td></tr>';
				echo '<tr><td><strong>Qualification Requirements</strong></td>';
				echo '<td>'.nl2br($row['required_quals']).'</td></tr>';
				echo '<tr><td><strong>Future Prospects</strong></td>';
				echo '<td>'.nl2br($row['future_prospects']).'</td></tr>';
				echo '<tr><td><strong>Additional Information</strong></td>';
				echo '<td>'.nl2br($row['misc']).'</td></tr>';
				echo '<tr><td><strong>Salary (per week)</strong></td>';
				echo '<td>'.$row['salary'].'</td></tr>';

				$shift_pattern = $row['shift_pattern'];
				if ( $shift_pattern == '' ) {
					// join all the shift data together to enable switch to single textbox
					$hours_per_week = (int)$row['hours_mon']+(int)$row['hours_tues']+(int)$row['hours_wed']+(int)$row['hours_thurs']+(int)$row['hours_fri']+(int)$row['hours_sat']+(int)$row['hours_sun'];
					if ( is_int($hours_per_week) && $hours_per_week > 0 ) {
						$shift_pattern = "General hours per week: ".$hours_per_week;
					}
					$shift_pattern .= isset($row['shifts_mon'])?"\nMonday: ".$row['shifts_mon']:'';
					$shift_pattern .= isset($row['shifts_tues'])?"\nTuesday: ".$row['shifts_tues']:'';
					$shift_pattern .= isset($row['shifts_wed'])?"\nWednesday: ".$row['shifts_wed']:'';
					$shift_pattern .= isset($row['shifts_thurs'])?"\nThursday: ".$row['shifts_thurs']:'';
					$shift_pattern .= isset($row['shifts_fri'])?"\nFriday: ".$row['shifts_fri']:'';
					$shift_pattern .= isset($row['shifts_sat'])?"\nSaturday: ".$row['shifts_sat']:'';
					$shift_pattern .= isset($row['shifts_sun'])?"\nSunday: ".$row['shifts_sun']:'';
				}
				echo '<tr><td><strong>Weekly working schedule:</strong></td>';
				echo '<td>'.nl2br($shift_pattern).'</td></tr>';
				echo '<tr><td><strong>Number of Hours (per week)</strong></td>';
				echo '<td>'.$row['hrs_per_week'].'</td></tr>';
				echo '</table></td></tr>';
				$row_count++;
			}

			// close the table if one was found
			// - else throw in a no results message
			if ( $row_count >= 1 ) {
				echo '<tr>';
				echo '<td colspan="'.$colspan.'" style="text-align:right" >';
				echo '<input type="hidden" name="_action" value="view_candidate_register"/>';
				echo '<input type="hidden" name="mode" value="application"/>';
				echo '<button type="submit" class="button" id="submit" >Apply&nbsp;&raquo;</button>';
				echo '</td>';
				echo '</tr>';
				echo '</tbody></table>';
			}
			else {
				echo '<h1>There are currently no vacancies matching your search.</h1>';
				echo '<p>Please check back again soon, as we are always adding new vacancies.<br/>Alternatively, you can <a href="do.php?_action=view_candidate_register">register with us here</a> and we will contact you regarding relevant opportunities, or you can try searching again with different options.</p>';
			}
			// echo $this->getViewNavigator();
		}
		else {
			throw new DatabaseException($link, $this->getSQL());
		}
	}
	//////////////////////////

	public function render_candidate(PDO $link) {
		/* @var $result pdo_result */
		// have to reset the filter to allow view all vacancies
		$this->filters["__page_size"]->setValue('0');
		$st = $link->query($this->getSQL());
		if( $st ) {
			$row_count = 0;
			$colspan = 5;
			while( $row = $st->fetch() ) {
				if ( $row_count == 0 ) {
					echo '<h1>Matching Vacancies.</h1>';
					echo '<p>We have found the vacancies below matching your search, select which vacancy you would like to apply for to complete your application</p>';
					echo '<table class="resultset" border="0" cellspacing="0" cellpadding="6">';
					echo '<thead><tr><th>Vacancy Code</th><th>Vacancy</th><th>Job Title</th>';
					if ( isset($row['distance']) ) {
						echo '<th>Distance</th>';
					}
					echo '<th>Apply for this role</th>';
					echo '<th>Vacancy Details</th>';
					echo '</tr></thead>';
					echo '<tbody>';
				}
				//echo HTML::viewrow_opening_tag('/do.php?_action=view_candidate_register&amp;vac_id='.$row['vac_id']);
				echo '<tr>';
				echo '<td align="left" >' . HTML::cell($row['code']) . '</td>';
				echo '<td align="left" >' . HTML::cell($row['vac_desc']) . '</td>';
				echo '<td align="left" >' . HTML::cell($row['job_title']) . '</td>';
				if ( isset($row['distance']) ) {
					$colspan = 6;
					echo '<td align="center" >'.HTML::cell(sprintf("%.2f",($row['distance'] / METRES_IN_A_MILE))). ' miles</td>';
				}
				echo '<td align="center" >';
				echo '';
				echo '<input type="checkbox" name="vac_id[]" value="'.$row['vac_id'].'" />';
				echo '</td>';
				echo '<td><a href="#" id="link_'.$row['vac_id'].'" onclick="displaydetail(\''.$row['vac_id'].'\');return false;">vacancy details...</a></td>';
				echo '</tr>';
				echo '<tr id="detail_'.$row['vac_id'].'" style="display:none" ><td colspan="'.$colspan.'">';
				echo '<table style="background-color:#ffffff" >';
				echo '<tr><td><strong>Description</strong></td>';
				echo '<td>'.nl2br($row['description']).'</td></tr>';
				echo '<tr><td><strong>Personal Requirements</strong></td>';
				echo '<td>'.nl2br($row['person_spec']).'</td></tr>';
				echo '<tr><td><strong>Qualification Requirements</strong></td>';
				echo '<td>'.nl2br($row['required_quals']).'</td></tr>';
				echo '<tr><td><strong>Additional Information</strong></td>';
				echo '<td>'.nl2br($row['misc']).'</td></tr>';
				echo '<tr><td><strong>Salary (per week)</strong></td>';
				echo '<td>'.$row['salary'].'</td></tr>';

				$shift_pattern = $row['shift_pattern'];
				if ( $shift_pattern == '' ) {
					// join all the shift data together to enable switch to single textbox
					$hours_per_week = (int)$row['hours_mon']+(int)$row['hours_tues']+(int)$row['hours_wed']+(int)$row['hours_thurs']+(int)$row['hours_fri']+(int)$row['hours_sat']+(int)$row['hours_sun'];
					if ( is_int($hours_per_week) && $hours_per_week > 0 ) {
						$shift_pattern = "General hours per week: ".$hours_per_week;
					}
					$shift_pattern .= isset($row['shifts_mon'])?"\nMonday: ".$row['shifts_mon']:'';
					$shift_pattern .= isset($row['shifts_tues'])?"\nTuesday: ".$row['shifts_tues']:'';
					$shift_pattern .= isset($row['shifts_wed'])?"\nWednesday: ".$row['shifts_wed']:'';
					$shift_pattern .= isset($row['shifts_thurs'])?"\nThursday: ".$row['shifts_thurs']:'';
					$shift_pattern .= isset($row['shifts_fri'])?"\nFriday: ".$row['shifts_fri']:'';
					$shift_pattern .= isset($row['shifts_sat'])?"\nSaturday: ".$row['shifts_sat']:'';
					$shift_pattern .= isset($row['shifts_sun'])?"\nSunday: ".$row['shifts_sun']:'';
				}
				echo '<tr><td><strong>Weekly working schedule:</strong></td>';
				echo '<td>'.nl2br($shift_pattern).'</td></tr>';
				echo '</table></td></tr>';
				$row_count++;
			}

			// close the table if one was found 
			// - else throw in a no results message
			if ( $row_count >= 1 ) {
				echo '<tr>';
				echo '<td colspan="'.$colspan.'" style="text-align:right" >';
				echo '<input type="hidden" name="_action" value="view_candidate_register"/>';
				echo '<input type="hidden" name="mode" value="application"/>';
				echo '<button type="submit" class="button" id="submit" >Apply&nbsp;&raquo;</button>';
				echo '</td>';
				echo '</tr>';
				echo '</tbody></table>';
			}
			else {
				echo '<h1>There are currently no vacancies matching your search.</h1>';
				echo '<p>Please check back again soon, as we are always adding new vacancies.<br/>Alternatively, you can <a href="do.php?_action=view_candidate_register">register with us here</a> and we will contact you regarding relevant opportunities, or you can try searching again with different options.</p>';
			}
			// echo $this->getViewNavigator();
		}
		else {
			throw new DatabaseException($link, $this->getSQL());
		}
	}

	/**
	 * Enter description here...
	 *
	 */
	public function getViewPaginator($al = 'center')
	{
		if( !array_key_exists(View::KEY_PAGE_SIZE, $this->filters) ) {
			$records_per_page = 0;
		}
		else {
			$records_per_page = (integer) $this->filters[View::KEY_PAGE_SIZE]->getValue();
		}


		if( $records_per_page > 0 ) {
			$numPages = ceil($this->rowCount / $records_per_page);
		}
		else {
			$numPages = 1;
		}

		// View objects keep their state, so the URL needs only to contain the action
		// and the page number.  The event (window.navigator_onclick()) can be used to
		// handle more complex page transitions that require the addition of further
		// querystring parameters e.g. when the a View object is used on the student enrollment form.
		if( preg_match('/[&]{0,1}_action=([^&]*)/', $_SERVER['QUERY_STRING'], $matches) > 0 ) {
			$qs = '_action='.$matches[1].'&amp;'; // extract the action
		}
		else {
			$qs = '';
		}


		// ick: id is now passed on with next prev option 
		if(preg_match('/[&]{0,1}id=([^&]*)/', $_SERVER['QUERY_STRING'], $matches) > 0 ) {
			$qs .= 'id='.$matches[1].'&amp;'; // extract the id
		}


		$pageNumberFieldName = get_class($this).'_'.View::KEY_PAGE_NUMBER;
		$qsFirst 	= $qs . $pageNumberFieldName . '=' . '1';
		$qsPrevious = $qs . $pageNumberFieldName . '=' . ($this->pageNumber - 1);
		$qsNext 	= $qs . $pageNumberFieldName . '=' . ($this->pageNumber + 1);
		$qsLast 	= $qs . $pageNumberFieldName . '=' . ($numPages);
		$viewName	= $this->getViewName();
		$pageNumber = $this->pageNumber;
		$pageNumberNext = $pageNumber + 1;
		$pageNumberPrev = $pageNumber - 1;

		$html  = '<div align="center" class="viewPaginator"><ul>';
		if($this->pageNumber <= 1) {
			$html .= '<li>|&lt;&lt;</li><li>&lt;</li>';
		}
		else 	{
			$html .= <<<HEREDOC
<li><a href="?$qsFirst" title="first page"
onclick="if(window.navigator__onclick){return window.navigator__onclick('$viewName', this, '$pageNumberFieldName', 1, arguments.length > 0 ? arguments[0] : window.event);} else {return true;}">|&lt;&lt;</a></li>
<li><a href="?$qsPrevious" title="previous page"
onclick="if(window.navigator__onclick){return window.navigator__onclick('$viewName', this, '$pageNumberFieldName', $pageNumberPrev, arguments.length > 0 ? arguments[0] : window.event);} else {return true;}">&lt;</a></li>
HEREDOC;
		}

		$start_page = $this->pageNumber-5<=1?1:$this->pageNumber;
		$end_page = $start_page+10>$numPages?$numPages:$start_page+10;

		while ( $start_page <= $end_page ) {
			if ( $start_page == $this->pageNumber ) {
				$html .= '<li class="active">'.$start_page.'</li>';
			}
			else {
				$html .= '<li><a href="?'.$qs.$pageNumberFieldName.'='.$start_page.'" title="page '.$start_page.'" ';
				$html .= 'onclick="if(window.navigator__onclick){return window.navigator__onclick(\''.$viewName;
				$html .= '\', this, \''.$pageNumberFieldName.'\', '.$start_page.', arguments.length > 0 ? arguments[0] : window.event);}';
				$html .= 'else {return true;}">'.$start_page.'</a></li>';
			}
			$start_page++;
		}

		if($this->pageNumber < $numPages)
		{
			//$qsNext .= '&id='. $_SERVER['QUERY_STRING'];
			$html .= <<<HEREDOC
<li><a href="?$qsNext" title="next page"
onclick="if(window.navigator__onclick){return window.navigator__onclick('$viewName', this, '$pageNumberFieldName', $pageNumberNext, arguments.length > 0 ? arguments[0] : window.event);} else {return true;}">&gt;</a></li>
<li><a href="?$qsLast" title="last page"
onclick="if(window.navigator__onclick){return window.navigator__onclick('$viewName', this, '$pageNumberFieldName', $numPages, arguments.length > 0 ? arguments[0] : window.event);} else {return true;}">&gt;&gt;|</a></li>
HEREDOC;
		}
		else
		{
			$html .= '<li>&gt;</li><li>&gt;&gt;|</li>';
		}
		$html .= '</ul>';

		$html .= 'page ' . $this->pageNumber . ' of ' . $numPages . ' (<span id="page-totalrows">' . ($this->rowCount == '' ? 0 : $this->rowCount ) . '</span> records )';

		$html .= '<br/></div>';

		return $html;
	}


}

?>
