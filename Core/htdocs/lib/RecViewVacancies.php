<?php
class RecViewVacancies extends View
{
	public static function getInstance(PDO $link, $organisation_id = '')
	{
		$key = 'view_'.__CLASS__.$organisation_id;

		if(isset($_REQUEST['RecViewCandidates_filter_postcodes']) && $_REQUEST['RecViewCandidates_filter_postcodes']!='')
			$key = 'view_'.__CLASS__.$_REQUEST['RecViewCandidates_filter_postcodes'].'_'.$_REQUEST['RecViewCandidates_filter_distance'];

		if(!isset($_SESSION[$key]))
		{
			$sql = new SQLStatement("

	SELECT
  employers.id AS employer_id,
  employers.legal_name,
  locations.id AS employer_location_id,
  locations.full_name AS employer_location_name,
  locations.postcode AS employer_location_postcode,
  CONCAT(
    COALESCE(full_name),
    ' (',
    COALESCE(`address_line_1`, ''),
    ' ',
    COALESCE(`address_line_2`, ''),
    ' ,',
    COALESCE(
      locations.postcode,
      ''
    ),
    ')'
  ) AS employer_location_address,
  vacancies.id AS vacancy_id,
  vacancies.vacancy_title,
  IF(vacancies.`is_active` = 1,'Active','Inactive') AS `vacancy_status`,
  vacancies.postcode,
  vacancies.vacancy_reference,
  vacancies.no_of_positions,
  vacancies.postcode,
  DATE_FORMAT(vacancies.closing_date, '%d/%m/%Y') AS closing_date
FROM
  vacancies
  INNER JOIN organisations AS employers
    ON vacancies.employer_id = employers.id
  INNER JOIN locations
    ON vacancies.location_id = locations.id
GROUP BY
  vacancies.id
");
			$sql->setClause("WHERE employers.organisation_type = 2");

			if($_SESSION['user']->type == User::TYPE_STORE_MANAGER)
			{
				$sql->setClause("WHERE employers.id='" . $_SESSION['user']->employer_id . "'");
			}
			if(DB_NAME == "am_demo" && !$_SESSION['user']->isAdmin() && $_SESSION['user']->type != User::TYPE_LEARNER && $_SESSION['user']->type != User::TYPE_STORE_MANAGER)
			{
				$sql->setClause("WHERE vacancies.provider_id='" . $_SESSION['user']->employer_id . "'");
			}
			if($organisation_id != '')
				$sql->setClause("WHERE employers.id='{$organisation_id}'");

			$view = $_SESSION[$key] = new RecViewVacancies();
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

			$options = array(
				0=>array(1, 'Show All', null, null),
				1=>array(2, 'Active', null, 'WHERE vacancies.is_active = 1'),
				2=>array(3, 'Inactive', null, 'WHERE vacancies.is_active = 0')
			);
			$f = new DropDownViewFilter('filter_status', $options, 1, false);
			$f->setDescriptionFormat("Vacancy Status: %s");
			$view->addFilter($f);

			$options = array(
				0=>array(1, 'Show All', null, null),
				1=>array(2, 'No', null, 'WHERE vacancies.is_archived = 0'),
				2=>array(3, 'Yes', null, 'WHERE vacancies.is_archived = 1')
			);
			if($_SESSION['user']->type == User::TYPE_STORE_MANAGER)
				$f = new DropDownViewFilter('filter_archive', $options, 2, false);
			else
				$f = new DropDownViewFilter('filter_archive', $options, 1, false);
			$f->setDescriptionFormat("Archived: %s");
			$view->addFilter($f);

			// Vacancy Postcode  Filter
			$f = new TextboxViewFilter('filter_postcode', "WHERE LOWER(vacancies.postcode) LIKE LOWER('%%%s%%')", null);
			$f->setDescriptionFormat("Vacancies Postcode: %s");
			$view->addFilter($f);

			// Employer Name Filter 
			$f = new TextboxViewFilter('filter_employername', "WHERE LOWER(employers.legal_name) LIKE LOWER('%%%s%%')", null);
			$f->setDescriptionFormat("Employer Name contains: %s");
			$view->addFilter($f);

			$f = new TextboxViewFilter('filter_vacancy_title', "WHERE LOWER(vacancies.vacancy_title) LIKE LOWER('%%%s%%')", null);
			$f->setDescriptionFormat("Vacancy Title: %s");
			$view->addFilter($f);

			$f = new TextboxViewFilter('filter_vacancy_reference', "WHERE LOWER(vacancies.vacancy_reference) LIKE LOWER('%%%s%%')", null);
			$f->setDescriptionFormat("Vacancy Reference: %s");
			$view->addFilter($f);

			// Primary Sector Type Filter
			$options = "SELECT DISTINCT id, description, NULL, CONCAT('WHERE vacancies.sector = ',CHAR(39),id,CHAR(39)) FROM lookup_sector_types";
			$f = new DropDownViewFilter('filter_sector', $options, null, true);
			$f->setDescriptionFormat("Primary Sector: %s");
			$view->addFilter($f);

			$f = new TextboxViewFilter('filter_postcodes', "WHERE vacancies.easting IS NOT NULL AND '%s' IS NOT NULL", null);
			$f->setDescriptionFormat("Distance from: %s");
			$view->addFilter($f);

			$f = new TextboxViewFilter('filter_distance', "WHERE vacancies.northing IS NOT NULL AND '%s' IS NOT NULL", null);
			$f->setDescriptionFormat("Within in %s miles");
			$view->addFilter($f);

			// closing date filter
			$format = "WHERE vacancies.closing_date >= '%s'";
			$f = new DateViewFilter('filter_from_closing_date', $format, '');
			$f->setDescriptionFormat("From closing date: %s");
			$view->addFilter($f);

			$format = "WHERE vacancies.closing_date <= '%s'";
			$f = new DateViewFilter('filter_to_closing_date', $format, '');
			$f->setDescriptionFormat("To closing date: %s");
			$view->addFilter($f);

		}
		return $_SESSION[$key];
	}

	public function render(PDO $link)
	{
		$loc = NULL;
		$longitude = NULL;
		$latitude = NULL;
		$easting = NULL;
		$northing = NULL;

		$search_distance = NULL;

		$vacancies_sql = $this->getSQL();

		if ( preg_match("/vacancies.easting IS NOT NULL AND \'(.*)\' IS NOT NULL\) AND/", $vacancies_sql, $postcode) )
		{
			$loc = new GeoLocation();
			$loc->setPostcode($postcode[1], $link);
			$longitude = $loc->getLongitude();
			$latitude = $loc->getLatitude();
			$easting = $loc->getEasting();
			$northing = $loc->getNorthing();
		}

		if ( preg_match("/vacancies.northing IS NOT NULL AND \'(.*)\' IS NOT NULL/", $vacancies_sql, $set_distance) )
		{
			$search_distance = $set_distance[1];
			$vacancies_sql = preg_replace("/LIMIT (.*)$/ ","", $vacancies_sql);
		}

		if ( is_object($loc) && is_numeric($search_distance) )
		{
			$distance_check = 'AND (SQRT(POWER(ABS('.$easting.' - vacancies.easting), 2) + POWER(ABS('.$northing.' - vacancies.northing), 2)))/1609.344 <= '.$search_distance.' GROUP BY';
			$vacancies_sql = preg_replace("/GROUP BY/ ",$distance_check, $vacancies_sql);
		}

		$st = $link->query($vacancies_sql);
		if( $st )
		{
			echo $this->getViewNavigator();
			echo '<div align="center" style="display:block;float:clear;" ><table class="resultset sortData" id="dataMatrix" border="0" cellspacing="0" cellpadding="6">';
			if($_SESSION['user']->isAdmin())
			{
				echo '<thead><tr class="topRow"><th colspan="6">Vacancy</th><th colspan="11">Applications</th></tr>';
				echo '<tr class="bottomRow"><th>Employer</th><th>Reference</th><th>Title</th><th>Postcode</th><th>Closing Date</th><th>Total<br>Positions</th>';
				echo '<th>Applied/<br>Not Screened</th><th>Screened</th><th>Telephone<br>Interviewed</th><th>CV Sent</th>';
				echo '<th>Interview<br>Successful </th><th>Interview<br>Unsuccessful </th><th>Sunesis Learner</th><th>Withdrawn</th><th>Rejected</th><th>Filled % (Sunesis Learner/Total Positions)</th></tr></thead>';
			}
			else
			{
				echo '<thead><tr class="topRow"><th colspan="6">Vacancy</th><th colspan="3">Applications</th></tr>';
				echo '<tr class="bottomRow"><th>Employer</th><th>Reference</th><th>Title</th><th>Postcode</th><th>Closing Date</th><th>Total Positions</th><th>CV Sent</th><th>Interview Successful </th><th>Interview Unsuccessful </th></tr></thead>';
			}

			echo '<tbody>';
			while( $row = $st->fetch() )
			{
				if($_SESSION['user']->type == User::TYPE_STORE_MANAGER)
					echo HTML::viewrow_opening_tag('/do.php?_action=rec_view_vacancy_applications&id='.$row['vacancy_id'].'&status=3');
				else
					echo HTML::viewrow_opening_tag('/do.php?_action=rec_view_vacancy&selected_tab=tab1&vacancy_postcode=' . rawurlencode($row['postcode']) .'&id='.$row['vacancy_id']);
				echo '<td align="left">' . HTML::cell($row['legal_name']) . '</td>';
				echo '<td align="left">' . HTML::cell($row['vacancy_reference']) . '</td>';
				echo '<td align="left">' . HTML::cell($row['vacancy_title']) . '</td>';
				echo '<td align="left">' . HTML::cell($row['postcode']) . '</td>';
				echo '<td align="center">' . HTML::cell($row['closing_date']) . '</td>';
				echo '<td align="center">' . HTML::cell($row['no_of_positions']) . '</td>';

				if($_SESSION['user']->isAdmin())
				{
					$status = DAO::getSingleValue($link, "SELECT COUNT(*) FROM candidate, candidate_applications WHERE candidate.id = candidate_applications.candidate_id AND candidate_applications.vacancy_id = '{$row['vacancy_id']}' AND candidate_applications.current_status = '" . RecCandidateApplication::CREATED . "'");
					echo $status == 0?'<td align="center">'.$status.'</td>':'<td align="center" bgcolor="#e0ffff">'.$status.'</td>';
					$status = DAO::getSingleValue($link, "SELECT COUNT(*) FROM candidate, candidate_applications WHERE candidate.id = candidate_applications.candidate_id AND candidate_applications.vacancy_id = '{$row['vacancy_id']}' AND candidate_applications.current_status = '" . RecCandidateApplication::SCREENED . "'");
					echo $status == 0?'<td align="center">'.$status.'</td>':'<td align="center" bgcolor="#e0ffff">'.$status.'</td>';
					$status = DAO::getSingleValue($link, "SELECT COUNT(*) FROM candidate, candidate_applications WHERE candidate.id = candidate_applications.candidate_id AND candidate_applications.vacancy_id = '{$row['vacancy_id']}' AND candidate_applications.current_status = '" . RecCandidateApplication::TELEPHONE_INTERVIEWED . "'");
					echo $status == 0?'<td align="center">'.$status.'</td>':'<td align="center" bgcolor="#e0ffff">'.$status.'</td>';
					$status = DAO::getSingleValue($link, "SELECT COUNT(*) FROM candidate, candidate_applications WHERE candidate.id = candidate_applications.candidate_id AND candidate_applications.vacancy_id = '{$row['vacancy_id']}' AND candidate_applications.current_status = '" . RecCandidateApplication::CV_SENT . "'");
					echo $status == 0?'<td align="center">'.$status.'</td>':'<td align="center" bgcolor="#e0ffff">'.$status.'</td>';
					$status = DAO::getSingleValue($link, "SELECT COUNT(*) FROM candidate, candidate_applications WHERE candidate.id = candidate_applications.candidate_id AND candidate_applications.vacancy_id = '{$row['vacancy_id']}' AND candidate_applications.current_status = '" . RecCandidateApplication::INTERVIEW_SUCCESSFUL . "'");
					echo $status == 0?'<td align="center">'.$status.'</td>':'<td align="center" bgcolor="#e0ffff">'.$status.'</td>';
					$status = DAO::getSingleValue($link, "SELECT COUNT(*) FROM candidate, candidate_applications WHERE candidate.id = candidate_applications.candidate_id AND candidate_applications.vacancy_id = '{$row['vacancy_id']}' AND candidate_applications.current_status = '" . RecCandidateApplication::INTERVIEW_UNSUCCESSFUL . "'");
					echo $status == 0?'<td align="center">'.$status.'</td>':'<td align="center" bgcolor="#e0ffff">'.$status.'</td>';
					$sunesis_learners = DAO::getSingleValue($link, "SELECT COUNT(*) FROM candidate, candidate_applications WHERE candidate.id = candidate_applications.candidate_id AND candidate_applications.vacancy_id = '{$row['vacancy_id']}' AND candidate_applications.current_status = '" . RecCandidateApplication::SUNESIS_LEARNER . "'");
					echo $sunesis_learners == 0?'<td align="center">'.$sunesis_learners.'</td>':'<td align="center" bgcolor="#e0ffff">'.$sunesis_learners.'</td>';
					$status = DAO::getSingleValue($link, "SELECT COUNT(*) FROM candidate, candidate_applications WHERE candidate.id = candidate_applications.candidate_id AND candidate_applications.vacancy_id = '{$row['vacancy_id']}' AND candidate_applications.current_status = '" . RecCandidateApplication::WITHDRAWN . "'");
					echo $status == 0?'<td align="center">'.$status.'</td>':'<td align="center" bgcolor="#e0ffff">'.$status.'</td>';
					$status = DAO::getSingleValue($link, "SELECT COUNT(*) FROM candidate, candidate_applications WHERE candidate.id = candidate_applications.candidate_id AND candidate_applications.vacancy_id = '{$row['vacancy_id']}' AND candidate_applications.current_status = '" . RecCandidateApplication::REJECTED . "'");
					echo $status == 0?'<td align="center">'.$status.'</td>':'<td align="center" bgcolor="#e0ffff">'.$status.'</td>';
					$percentage_filled = round(($sunesis_learners / $row['no_of_positions'])*100);
					echo '<td align="center" width="20%">' . $this->prepareUnitProgressBars($percentage_filled) . '</td>';
				}
				else
				{
					$status = DAO::getSingleValue($link, "SELECT COUNT(*) FROM candidate, candidate_applications WHERE candidate.id = candidate_applications.candidate_id AND candidate_applications.vacancy_id = '{$row['vacancy_id']}' AND candidate_applications.current_status = '" . RecCandidateApplication::CV_SENT . "'");
					echo $status == 0?'<td align="center">'.$status.'</td>':'<td align="center" bgcolor="#e0ffff">'.$status.'</td>';
					$status = DAO::getSingleValue($link, "SELECT COUNT(*) FROM candidate, candidate_applications WHERE candidate.id = candidate_applications.candidate_id AND candidate_applications.vacancy_id = '{$row['vacancy_id']}' AND candidate_applications.current_status = '" . RecCandidateApplication::INTERVIEW_SUCCESSFUL . "'");
					echo $status == 0?'<td align="center">'.$status.'</td>':'<td align="center" bgcolor="#e0ffff">'.$status.'</td>';
					$status = DAO::getSingleValue($link, "SELECT COUNT(*) FROM candidate, candidate_applications WHERE candidate.id = candidate_applications.candidate_id AND candidate_applications.vacancy_id = '{$row['vacancy_id']}' AND candidate_applications.current_status = '" . RecCandidateApplication::INTERVIEW_UNSUCCESSFUL . "'");
					echo $status == 0?'<td align="center">'.$status.'</td>':'<td align="center" bgcolor="#e0ffff">'.$status.'</td>';
				}
				echo '</tr>';
			}
			echo '</tbody></table></div>';
			echo $this->getViewNavigator();
		}
		echo <<<STYLE
<style>
	.SmallPercentageBarSignedOff {background-color: #d3d3d3; position: relative; font-size: small; width: 100%; margin: 1px;}
	.SmallPercentageBarSignedOff DIV {height: 25px; line-height: 25px;}
	.SmallPercentageBarSignedOff .percent {position: absolute; background-color: orange; left: 0px; z-index: 0;}
	.SmallPercentageBarSignedOff .caption {position: relative; text-align: center; color: #000; z-index: 1;}
</style>
STYLE;

	}

	private function prepareUnitProgressBars($total_sign_off_percentage)
	{
		$html = <<<HTML
		<table width="100%">
		<tr style='width:10%; '>
			<td style="width: 10%; color: black; font-family: Arial,Helvetica; font-size: 12px; text-align: center; border-radius: 5px;">$total_sign_off_percentage%</td>
			<td style="padding-left: 5px; padding-right: 5px; ">
				<div class="SmallPercentageBarSignedOff" style=" border-radius:25px;">
					<div class="percent" style="width: $total_sign_off_percentage%; border-radius:25px;">&nbsp;</div>
					<div class="caption"></div>
				</div>
			</td>
		</tr>
	</table>
HTML;

		return $html;
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

	public function render_for_candidate(PDO $link)
	{

		$returnHTML = "";
		$st = $link->query($this->getSQL());
		if($st)
		{
			if($st->rowCount() == 0)
			{
				return '<div style="padding: 50px;"><p><span style="font-weight: 800;">Sorry - There are currently no vacancies matching your search.</span></p><br>
<p>Please check back again soon, as we are always adding new vacancies.</p><br/>
<p>
Alternatively, you can <a href="do.php?_action=application">register with us here</a> and we will contact you regarding relevant opportunities, or you can try searching again with different options.</p>
</div>
';
			}
			$returnHTML .= $this->getViewNavigator('left');
			while($row = $st->fetch())
			{
				$job_title = $row['job_title'];
				$returnHTML .= <<<HTML
<div class="panel">
	<div class="panel-heading"><h3>$job_title</h3></div>
	<div class="panel-body" >
		<table>

HTML;

				$returnHTML .= '<td rowspan="6" valign="top" ><span style="height: 43px; width: 100px; padding-top: 14px; font-weight: bold; font-size: 1.5em; text-align: center;" class="button">&nbsp;&nbsp;&nbsp;Apply&nbsp;&nbsp;&nbsp;</span> </td>';
				$returnHTML .= '<td style="font-weight: 800;">Job Title</td><td>' . $row['job_title'] . '</td></tr>';
				$returnHTML .= '<tr><td style="font-weight: 800;">Reference</td><td>' . $row['code'] . '</td></tr>';
				$returnHTML .= '<tr><td style="font-weight: 800;">Sector</td><td>' . DAO::getSingleValue($link, "SELECT description FROM lookup_sector_types WHERE id = '" . $row['primary_sector'] . "'") . '</td></tr>';
				$returnHTML .= '<tr><td style="font-weight: 800;">Location</td><td>Abergavenny (NP7 5AJ)</td></tr>';
				$returnHTML .= '<tr><td style="font-weight: 800;">Contract Type</td><td>Permanent</td></tr>';
				$returnHTML .= '<tr><td style="font-weight: 800;">Salary</td><td>£16000 - £20000 per annum </td></tr>';

				$returnHTML .= <<<HTML
		</table>
	</div>
</div>
HTML;
			}
			$returnHTML .=  $this->getViewNavigator('left');
		}
		else
		{
			throw new DatabaseException($link, $this->getSQL());
		}
		return $returnHTML;
	}
}

?>
