<?php
define('METRES_IN_A_MILE', 1609.344);

class recruitment_v2_home_page implements IAction
{
	public function execute(PDO $link)
	{
		$_SESSION['bc']->index=0;
		$_SESSION['bc']->add($link, "do.php?_action=recruitment_v2_home_page", "e-Recruitment Home Page");

		require_once('./lib/Calendar.php');
		set_time_limit(0);
		ini_set('memory_limit','512M');
		if(!isset($_REQUEST['v']))
		{
			$_REQUEST['v'] = 1;
		}
		if(!isset($_REQUEST['y']))
		{
			$_REQUEST['y'] = date('Y');
		}
		if(!isset($_REQUEST['m']))
		{
			$_REQUEST['m'] = date('n');
		}
		if(!isset($_REQUEST['d']))
		{
			$_REQUEST['d'] = date('j', strtotime('last sunday'));
		}


		switch($_REQUEST['v'])
		{
			case 1:
				$bc = 'Monthly View';
				$calendar = new Monthly_Calendar($_REQUEST['y'], $_REQUEST['m'], $_REQUEST['d']);
				break;
			case 2:
				$bc = 'Weekly View';
				$calendar = new Weekly_Calendar($_REQUEST['y'], $_REQUEST['m'], $_REQUEST['d']);
				break;
			case 3:
				$bc = 'Daily View';
				$calendar = new Daily_Calendar($_REQUEST['y'], $_REQUEST['m'], $_REQUEST['d']);
				break;
			default:
		}

		$calendar->setQueryString('_action=recruitment_v2_home_page');

		$userCalendar = new UserCalendar(0, array('colour' => '#FFd700'));

		// 2) User events

		$sql = " SELECT * FROM calendar_event WHERE for_whom = '" . $_SESSION['user']->id . "'";
		$st = $link->query($sql);
		if($st)
		{
			while($row = $st->fetch())
			{
				$from_date = new Date($row['datefrom']);
				$to_date = new Date($row['dateto']);
				$event = new CalendarEvent(
					$row['event_id']
					, $userCalendar
					, $row['title']
					, $row['description']
					, $from_date->getYear()
					, $from_date->getMonth()
					, $from_date->getDay()
					, $to_date->getYear()
					, $to_date->getMonth()
					, $to_date->getDay()
					, substr($row['datefromtime'], 0, 2)
					, substr($row['datefromtime'], 3, 2)
					, substr($row['datetotime'], 0, 2)
					, substr($row['datetotime'], 3, 2)
				);
				$event->setLocation($row['location']);
				$calendar->addEvent($event);
			}
		}
		else
		{
			throw new DatabaseException($link, $sql);
		}
		$dataHTML = $calendar->draw();

		$d1 = date('Y')-1 .'-08-01';
		$d2 = date('Y') .'-07-31';

		$sql = <<<SQL
SELECT
  SUM(IF(candidate.`gender` = 'M', 1, 0)) AS `male_applicants`,
  SUM(IF(candidate.`gender` = 'F', 1, 0)) AS `female_applicants`,
  SUM(IF(candidate.`gender` = 'U', 1, 0)) AS `unknown_applicants`,
  SUM(IF(candidate.`gender` = 'W', 1, 0)) AS `witheld_applicants`
FROM
  candidate
WHERE candidate.`created` BETWEEN '$d1' AND '$d2' AND candidate.username IS NULL
GROUP BY candidate.gender
;
SQL;
		$gender_stats = array(
			'male_applicants' => 0
			,'female_applicants' => 0
			,'unknown_applicants' => 0
			,'witheld_applicants' => 0
		);
		$records = DAO::getResultset($link, $sql, DAO::FETCH_ASSOC);
		foreach($records AS $r)
		{
			foreach($r AS $key => $value)
			{
				$gender_stats[$key] += $value;
			}
		}
		$gender_stats_total = $gender_stats['male_applicants']+$gender_stats['female_applicants']+$gender_stats['unknown_applicants']+$gender_stats['witheld_applicants'];
		$gender_stats_total = $gender_stats_total == 0?1:$gender_stats_total;

		$total_eth = DAO::getSingleValue($link, "SELECT COUNT(*) FROM candidate WHERE candidate.`created` BETWEEN '$d1' AND '$d2' AND candidate.username IS NULL ");

		$sql = <<<SQL
SELECT
	(SELECT Ethnicity_Desc FROM lis201213.ilr_ethnicity WHERE Ethnicity = candidate.`ethnicity`) AS Ethnicity,
	COUNT(*) AS cnt
FROM
	candidate
WHERE
	candidate.`created` BETWEEN '$d1' AND '$d2' AND candidate.username IS NULL
GROUP BY
	candidate.`ethnicity`
ORDER BY
	cnt DESC
;

SQL;
		$ethnicityTable = '<table class="table table-bordered table-striped" id="tblEthnicityStats">';
		$ethnicityTable .= '<thead><tr><th>Ethnicity</th><th>Candidates</th><th>%</th></tr></thead>';
		$ethnicityTable .= '<tbody>';
		$records = DAO::getResultset($link, $sql, DAO::FETCH_ASSOC);
		foreach($records AS $r)
		{
			$ethnicityTable .= '<tr><td>' . $r['Ethnicity'] . '</td><td>' . $r['cnt'] . '</td><td>' . round(((int)$r['cnt']/$total_eth)*100, 2) . '</td></tr>';
		}
		$ethnicityTable .= '</tbody>';
		$ethnicityTable .= '</table>';

		if($_SESSION['user']->isAdmin())
			require_once('tpl_recruitment_v2_home_page_1.php');
		else
			require_once('tpl_recruitment_v2_home_page_staff.php');
	}

	private function renderAgeGroupDashboard(PDO $link)
	{
		$sql = <<<SQL
SELECT
  CASE TRUE
  WHEN ((DATE_FORMAT(candidate_applications.`created`,'%Y') - DATE_FORMAT(candidate.dob,'%Y')) - (DATE_FORMAT(candidate_applications.`created`,'00-%m-%d') < DATE_FORMAT(candidate.dob,'00-%m-%d'))) BETWEEN 16 AND 18 THEN '16-18'
  WHEN ((DATE_FORMAT(candidate_applications.`created`,'%Y') - DATE_FORMAT(candidate.dob,'%Y')) - (DATE_FORMAT(candidate_applications.`created`,'00-%m-%d') < DATE_FORMAT(candidate.dob,'00-%m-%d'))) BETWEEN 19 AND 24 THEN '19-24'
  WHEN ((DATE_FORMAT(candidate_applications.`created`,'%Y') - DATE_FORMAT(candidate.dob,'%Y')) - (DATE_FORMAT(candidate_applications.`created`,'00-%m-%d') < DATE_FORMAT(candidate.dob,'00-%m-%d'))) > 24 THEN '24+'
  WHEN ((DATE_FORMAT(candidate_applications.`created`,'%Y') - DATE_FORMAT(candidate.dob,'%Y')) - (DATE_FORMAT(candidate_applications.`created`,'00-%m-%d') < DATE_FORMAT(candidate.dob,'00-%m-%d'))) < 16 THEN 'Under 16'
  END AS age_group,
  COUNT(*) AS cnt
FROM
  candidate_applications
  INNER JOIN candidate
    ON candidate_applications.`candidate_id` = candidate.id
  INNER JOIN vacancies
    ON candidate_applications.`vacancy_id` = vacancies.id
  INNER JOIN locations
    ON vacancies.`location_id` = locations.id
WHERE candidate.`username` IS NOT NULL
  AND candidate_applications.`current_status` = 6
GROUP BY age_group ;
SQL;

		$records = DAO::getResultset($link, $sql, DAO::FETCH_ASSOC);
		$cat1618 = 0;
		$cat1924 = 0;
		$cat24plus = 0;
		$catUnder16 = 0;
		foreach($records AS $r)
		{
			if($r['age_group'] == '16-18')
				$cat1618 = $r['cnt'];
			elseif($r['age_group'] == '19-24')
				$cat1924 = $r['cnt'];
			elseif($r['age_group'] == '24+')
				$cat24plus = $r['cnt'];
			elseif($r['age_group'] == 'Under 16')
				$catUnder16 = $r['cnt'];

		}

		$html = '';
		$html .= <<<HTML
<div class="col-lg-6 col-xs-6">
	<div class="small-box bg-green">
		<div class="inner">
			<h1>$cat1618</h1>
			<p>16-18 age band</p>
		</div>
		<div class="icon"><i class="fa fa-users"></i> </div>
	</div>
</div>
HTML;
		$html .= <<<HTML
<div class="col-lg-6 col-xs-6">
	<div class="small-box bg-aqua">
		<div class="inner">
			<h1>$cat1924</h1>
			<p>19-24 age band</p>
		</div>
		<div class="icon"><i class="fa fa-users"></i> </div>
	</div>
</div>
HTML;

		return $html;
	}

	public function display_screening_stats(PDO $link )
	{
		$graph_data = "";
		$graph_data .= "{type: 'bar',color: '#FFBFBF', name: 'red', data: [".DAO::getSingleValue($link, "SELECT COUNT(*) FROM candidate_applications INNER JOIN candidate ON candidate_applications.`candidate_id` = candidate.id WHERE candidate.`username` IS NULL AND candidate_applications.`screening_rag` = 'R'")."]},";
		$graph_data .= "{type: 'bar',color: '#FFE6D7', name: 'amber', data: [".DAO::getSingleValue($link, "SELECT COUNT(*) FROM candidate_applications INNER JOIN candidate ON candidate_applications.`candidate_id` = candidate.id WHERE candidate.`username` IS NULL AND candidate_applications.`screening_rag` = 'A'")."]},";
		$graph_data .= "{type: 'bar',color: '#E0EAD0', name: 'green', data: [".DAO::getSingleValue($link, "SELECT COUNT(*) FROM candidate_applications INNER JOIN candidate ON candidate_applications.`candidate_id` = candidate.id WHERE candidate.`username` IS NULL AND candidate_applications.`screening_rag` = 'G'")."]},";


		$data = <<<HEREDOC
		chart = new Highcharts.Chart({
				chart: {
					renderTo: 'stat-container'
				},
				title: {
					text: null
				},
				xAxis: {
					categories: ['screenings'],
					labels:{ enabled:null }
				},
				yAxis: { 
					title:{text:'Number of Vacancy Applications'}
				},
				tooltip: {
					formatter: function() {
						var s = ''+	this.series.name  +': '+ this.y;
						return s;
					}
				},
				credits: {
        			enabled: false
    			},
				plotOptions: {
					bar: {
						dataLabels: {
							enabled: true
						},
						events: {
               				click: function() {
               					switch (this.name) {
               						case 'green':
               							//window.location.href='do.php?_action=view_candidates&_reset=1&ViewCandidates_filter_screening=1';
               							break;
               						case 'amber':
               							//window.location.href='do.php?_action=view_candidates&_reset=1&ViewCandidates_filter_screening=2';
               							break;
               						default:
               							//window.location.href='do.php?_action=view_candidates&_reset=1&ViewCandidates_filter_screening=3';
               							break;
               					}
 							}
						}
					}
				},
				labels: {
				},
				series: [{$graph_data}]
			});
HEREDOC;

		return $data;
	}

	public function display_candidate_actions(PDO $link ) {

		$start_date = '-1';
		$end_date = '7';

		if ( isset($_REQUEST['cand_start_date']) ) {
			$start_date = $_REQUEST['cand_start_date'];
			$end_date = $start_date+7;
			// overdue
			if ( $start_date == -365 ) {
				$end_date = '-1';
			}
		}

		$sql_request_retrieval = 'SELECT candidate.id, candidate.firstnames, candidate.surname, ';
		$sql_request_retrieval .= 'vacancies.id as vac_id, vacancies.postcode, vacancies.job_title, candidate_applications.next_action_date,  ';
		$sql_request_retrieval .= 'candidate_applications.application_comments, DATE_ADD(NOW(), INTERVAL '.$start_date.' DAY) as start_date, ';
		$sql_request_retrieval .= 'DATE_ADD(NOW(), INTERVAL '.$end_date.' DAY) as end_date ';
		$sql_request_retrieval .= 'FROM candidate LEFT JOIN candidate_applications ';
		$sql_request_retrieval .= 'ON candidate.id = candidate_applications.candidate_id, ';
		$sql_request_retrieval .= 'vacancies, organisations ';
		$sql_request_retrieval .= 'WHERE vacancies.id = candidate_applications.vacancy_id ';
		$sql_request_retrieval .= 'AND vacancies.employer_id = organisations.id ';
		$sql_request_retrieval .= 'AND candidate_applications.application_comments != "Screened" ';
		$sql_request_retrieval .= 'AND candidate_applications.next_action_date BETWEEN DATE_ADD(NOW(), INTERVAL '.$start_date.' DAY) AND DATE_ADD(NOW(), INTERVAL '.$end_date.' DAY) ';
		if ( isset($_SESSION['user']->department) ) {
			$sql_request_retrieval .= 'AND organisations.region = "'.$_SESSION['user']->department.'" ';
		}
		$sql_request_retrieval .= 'order by candidate_applications.next_action_date desc';

		$daterange = array('candidate_data' => null, 'start_date' => null, 'end_date' => null);

		$current_date = '';

		if( $result = $link->query($sql_request_retrieval) ) {
			$count = 0;
			while( $row = $result->fetch() ) {
				if ( $current_date != $row['next_action_date']) {
					$current_date = $row['next_action_date'];
					$daterange['candidate_data'] .= '<div style="font-size:1.2em; font-weight: bold; text-spacing: 0.9em;">'.date('D d M y', strtotime($row['next_action_date'])).'</div>';
				}
				$daterange['candidate_data'] .= '<div style="';
				if( $odd = $count%2 ) {
					$daterange['candidate_data'] .= 'background-color: #E0EAD0;';
				}
				$daterange['candidate_data'] .= ' border-top: 1px solid #e9e9e9;"><a href="/do.php?_action=view_vacancy&pc='.rawurlencode($row['postcode']).'&id='.$row['vac_id'].'&display='.$row['id'].'">'.$row['firstnames'].' '.$row['surname'].'</a><div style="padding: 0; margin: 0; float:right; width:auto;"> ('.$row['application_comments'].')</div><p style="padding: 0; margin: 0;">'.$row['job_title'].'</p></div>';
				$daterange['start_date'] = $row['start_date'];
				$daterange['end_date'] = $row['end_date'];
				$daterange['previous_week'] = $start_date-7;
				$daterange['next_week'] = $start_date+7;
				$count++;
			}
		}

		if ( $count == 0 ) {
			$sql_request_retrieval = 'SELECT DATE_ADD(NOW(), INTERVAL '.$start_date.' DAY) as start_date, DATE_ADD(NOW(), INTERVAL '.$end_date.' DAY) as end_date from configuration';
			if( $result = $link->query($sql_request_retrieval) ) {
				while( $row = $result->fetch() ) {
					$daterange['start_date'] = $row['start_date'];
					$daterange['end_date'] = $row['end_date'];
					$daterange['previous_week'] = $start_date-7;
					$daterange['next_week'] = $start_date+7;
				}
			}
		}

		return $daterange;
	}


	public function display_employer_actions(PDO $link ) {

		$start_date = '-1';
		$end_date = '7';

		if ( isset($_REQUEST['cand_start_date']) ) {
			$start_date = $_REQUEST['cand_start_date'];
			$end_date = $start_date+7;
			// overdue
			if ( $start_date == -365 ) {
				$end_date = '-1';
			}
		}

		$sql_request_retrieval = 'SELECT dpn, company, note, next_action ';
		$sql_request_retrieval .= 'FROM central.emp_pool LEFT JOIN employerpool_notes ';
		$sql_request_retrieval .= 'ON central.emp_pool.dpn = employerpool_notes.emp_id ';
		$sql_request_retrieval .= 'WHERE employerpool_notes.next_action ';
		$sql_request_retrieval .= 'BETWEEN DATE_ADD(NOW(), INTERVAL '.$start_date.' DAY) AND DATE_ADD(NOW(), INTERVAL '.$end_date.' DAY) ';
		if ( isset($_SESSION['user']->department) ) {
			// $region_code = array('North West' => 1, 'North East' => 2, 'Midlands' => 3, 'East Midlands' => 4, 'West Midlands' => 5, 'London North' => 6, 'London South' => 7, 'Peterborough' => 8, 'Yorkshire' => 9);
			$region_code = 'select description, id from lookup_vacancy_regions order by description;';
			$region_code = DAO::getResultset($link, $region_code);
			$sql_request_retrieval .= 'AND employerpool_notes.status = "'.$region_code[$_SESSION['user']->department].'" ';
		}
		$sql_request_retrieval .= 'GROUP BY dpn ';
		$sql_request_retrieval .= 'order by employerpool_notes.next_action desc';

		// throw new Exception($sql_request_retrieval);
		if( $result = $link->query($sql_request_retrieval) ) {
			$count = 1;
			$current_date = '';
			while( $row = $result->fetch() ) {
				if ( $current_date != $row['next_action']) {
					$current_date = $row['next_action'];
					echo '<div style="font-size:1.2em; font-weight: bold; text-spacing: 0.9em;">'.date('D d M y', strtotime($row['next_action'])).'</div>';
				}
				echo '<div style="';
				if( $odd = $count%2 ) {
					echo 'background-color: #E0EAD0;';
				}
				echo ' border-top: 1px solid #e9e9e9;"><a href="/do.php?_action=view_employers_pool&ViewEmployersPool_filter_company='.$row['company'].'">'.$row['company'].'</a><p style="padding: 0; margin: 0;">'.$row['note'].'</p></div>';
				$count++;
			}
		}
	}

	private function renderDashboardTableForVacanciesWithNotScreenedApplications(PDO $link)
	{
		$sql = <<<SQL
SELECT DISTINCT
  vacancies.id,
  vacancy_reference,
  vacancy_title,
  interview_from_date,
  (SELECT legal_name FROM organisations WHERE id = vacancies.`employer_id`) AS legal_name,
  vacancies.postcode ,
  (SELECT COUNT(*) FROM candidate_applications WHERE vacancy_id = vacancies.`id` AND current_status = '0') AS applications
FROM
  vacancies INNER JOIN candidate_applications ON vacancies.id = candidate_applications.`vacancy_id`
  WHERE candidate_applications.`current_status` = '0' AND LOCATE('TEST', vacancy_title) = 0;
SQL;
		echo <<<HTML
<table class="table table-striped" id="tblNotScreenedApplications" style="font-size: smaller;">
	<thead><tr><th>Reference</th><th>Title</th><th>Employer/Store</th><th>Postcode</th><th>Interview Start Date</th><th>Applications</th></tr></thead>
	<tbody>
HTML;
		$records = DAO::getResultset($link, $sql, DAO::FETCH_ASSOC);
		foreach($records AS $r)
		{
			echo '<tr style="cursor:pointer;" onclick="window.location.href=\'do.php?_action=rec_view_vacancy_applications&status=0&id='.$r['id'].'\';">';
			echo '<td>' . $r['vacancy_reference'] . '</td>';
			echo '<td>' . $r['vacancy_title'] . '</td>';
			echo '<td>' . $r['legal_name'] . '</td>';
			echo '<td>' . $r['postcode'] . '</td>';
			echo '<td>' . Date::toShort($r['interview_from_date']) . '</td>';
			echo '<td align="center">' . $r['applications'] . '</td>';
			echo '</tr>';
		}
		echo <<<HTML
	</tbody>
</table>
HTML;

	}

	private function renderDashboardTableForVacanciesWithFilledApplications(PDO $link)
	{
		$sql = <<<SQL
SELECT DISTINCT
  vacancies.id,
  vacancy_reference,
  vacancy_title,
  (SELECT legal_name FROM organisations WHERE id = vacancies.`employer_id`) AS legal_name,
  DATE_FORMAT(vacancies.`created`, '%d/%m/%Y') AS vacancy_created,
  DATE_FORMAT(candidate_application_status.`created`, '%d/%m/%Y') AS vacancy_filled,
  DATEDIFF(candidate_application_status.`created`, vacancies.`created`) AS diff
FROM
  candidate_application_status INNER JOIN candidate_applications ON candidate_application_status.`application_id` = candidate_applications.`id`
  INNER JOIN vacancies ON vacancies.id = candidate_applications.`vacancy_id`
  WHERE candidate_application_status.`status` = '6' AND LOCATE('TEST', vacancy_title) = 0
;
SQL;
		echo <<<HTML
<table class="table table-striped" id="tblFilledApplications" style="font-size: smaller;">
	<thead><tr><th>Reference</th><th>Title</th><th>Employer/Store</th><th>Creation Date</th><th>Filled Date</th><th>Days Taken</th></tr></thead>
	<tbody>
HTML;
		$records = DAO::getResultset($link, $sql, DAO::FETCH_ASSOC);
		foreach($records AS $r)
		{
			echo '<tr style="cursor:pointer;" onclick="window.location.href=\'do.php?_action=rec_view_vacancy_applications&status=6&id='.$r['id'].'\';">';
			echo '<td>' . $r['vacancy_reference'] . '</td>';
			echo '<td>' . $r['vacancy_title'] . '</td>';
			echo '<td>' . $r['legal_name'] . '</td>';
			echo '<td>' . $r['vacancy_created'] . '</td>';
			echo '<td>' . $r['vacancy_filled'] . '</td>';
			echo '<td align="center">' . $r['diff'] . '</td>';
			echo '</tr>';
		}
		echo <<<HTML
	</tbody>
</table>
HTML;

	}

	private function renderFilledVacanciesByRegionTable(PDO $link)
	{
		$sql = <<<SQL
SELECT
  locations.`address_line_4`,
  SUM(IF(candidate_applications.`current_status` IN (1, 2, 3, 4, 5),1,0)) AS recruiting,
  SUM(IF(candidate_applications.`current_status` = '6',1,0)) AS filled
  ,SUM(IF(tr.`username` = candidate.`username`, 1, 0)) AS on_prog
FROM
  candidate_applications
  INNER JOIN candidate
    ON candidate_applications.`candidate_id` = candidate.id
  INNER JOIN vacancies
    ON vacancy_id = vacancies.`id`
  INNER JOIN locations
    ON vacancies.`location_id` = locations.`id`
  LEFT JOIN tr ON (candidate.`username` = tr.`username` AND tr.`employer_location_id` = vacancies.`location_id`)

GROUP BY locations.`address_line_4`
;

SQL;
		echo <<<HTML
<table class="table table-striped" id="tblFilledVacanciesByRegion" style="font-size: smaller;">
	<thead><tr><th>Region</th><th>Recruiting</th><th>Filled</th><th>On Program</th></tr></thead>
	<tbody>
HTML;
		$records = DAO::getResultset($link, $sql, DAO::FETCH_ASSOC);
		foreach($records AS $r)
		{
			echo '<tr><th>' . $r['address_line_4'] . '</th><td align="center">' . $r['recruiting'] . '</td><td align="center">' . $r['filled'] . '</td><td align="center">' . $r['on_prog'] . '</td></tr>';
		}
		echo '</table>';
	}


	public $all_candidates = 0;
	public $new_candidates = 0;
	public $screened_candidates = 0;
	public $approved_candidates = 0;
	public $unassigned_candidates = 0;
}
?>
