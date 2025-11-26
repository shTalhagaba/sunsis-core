<?php
define('METRES_IN_A_MILE', 1609.344);

class baltic_vacancies_home implements IAction
{
	public function execute(PDO $link)
	{
		$_SESSION['bc']->index=0;
		$_SESSION['bc']->add($link, "do.php?_action=vacancies_home", "Vacancies Home");	

		$diary_filter_region = isset($_REQUEST['diary_filter_region'])?$_REQUEST['diary_filter_region']:'';

		// $vacancies = ViewVacancies::getInstance($link);
		// $vacancies->refresh($link, $_REQUEST);
		
		
		// $candidates = ViewCandidates::getInstance();
		// $candidates->refresh($link, $_REQUEST);
		
		require_once('tpl_baltic_vacancies_home.php');
	}
	
	public function display_screening_stats(PDO $link ) {

/*        // get the historicals
        // - review this query, not happy with it re 30/08/2011
        $sql_request_retrieval = 'SELECT COUNT(candidate.id) AS counter, screening_score AS screening ';
        $sql_request_retrieval .= 'FROM candidate WHERE candidate.username IS NULL OR candidate.username = "" ';
        $sql_request_retrieval .= 'AND candidate.id IN ( ';
        $sql_request_retrieval .= 'SELECT candidate_applications.candidate_id FROM candidate_applications, vacancies, organisations ';
        $sql_request_retrieval .= 'WHERE vacancies.id = candidate_applications.vacancy_id ';
        $sql_request_retrieval .= 'AND vacancies.employer_id = organisations.id ';
        if ( isset($_SESSION['user']->department) ) {
                $sql_request_retrieval .= 'AND organisations.region = "'.$_SESSION['user']->department.'" ';
        }
        $sql_request_retrieval .= 'GROUP BY candidate_applications.candidate_id ) ';
        $sql_request_retrieval .= 'GROUP BY screening';*/

		$sql_request_retrieval = <<<HEREDOC
SELECT
  COUNT(candidate.id) AS counter,
  candidate_applications.`application_screening` AS screening
FROM
  candidate
  INNER JOIN candidate_applications ON candidate.id = candidate_applications.`candidate_id` AND candidate_applications.`vacancy_id` != 0
WHERE candidate.username IS NULL
  OR candidate.username = ""
 GROUP BY screening ;
HEREDOC;

		$data = '';

		$data_categories = array();

		$date_records = '';
		$average_records = '';
		$daily_records = '';
		
		if( $result = $link->query($sql_request_retrieval) ) {
			$count = 1;
			while( $row = $result->fetch() ) {
				if ( is_null($row['screening']) ) {
					if ( !array_key_exists('not_screened', $data_categories) ) {
						$data_categories['not_screened'] = array('total' => $row['counter']);
					}
					else {
						$data_categories['not_screened']['total'] += $row['counter'];
					}
				}
				if ( !is_null($row['screening']) AND $row['screening'] < 45 ) {
					if ( !array_key_exists('red', $data_categories) ) {
						$data_categories['red'] = array('total' => $row['counter']);
					}
					else {
						$data_categories['red']['total'] += $row['counter'];	
					}	
				}
				elseif ( !is_null($row['screening']) AND $row['screening'] < 70 ) {
					if ( !array_key_exists('amber', $data_categories) ) {
						$data_categories['amber'] = array('total' => $row['counter']);
					}
					else {
						$data_categories['amber']['total'] += $row['counter'];	
					}	
				}
				elseif ( !is_null($row['screening']) AND $row['screening'] > 70 )  {
					if ( !array_key_exists('green', $data_categories) ) {
						$data_categories['green'] = array('total' => $row['counter']);
					}
					else {
						$data_categories['green']['total'] += $row['counter'];	
					}	
				}
			}
			
			foreach ( $data_categories as $code => $code_total ) {
				$date_records .= "'".$code."',";
				if ( $code == 'red' ) {
					$average_records .= "{type: 'bar',color: '#FFBFBF', name: '".$code."', data: [".$code_total['total']."]},";
				}
				if ( $code == 'amber' ) {
					$average_records .= "{type: 'bar',color: '#FFE6D7', name: '".$code."', data: [".$code_total['total']."]},";
				}
				if ( $code == 'green' ) {
					$average_records .= "{type: 'bar',color: '#E0EAD0', name: '".$code."', data: [".$code_total['total']."]},";
				}
				if ( $code == 'not_screened' ) {
					$average_records .= "{type: 'bar',color: '#395596', name: '".$code."', data: [".$code_total['total']."]},";
				}
			}
		}
		
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
               						case 'red':
               							//window.location.href='do.php?_action=view_candidates&_reset=1&ViewCandidates_filter_screening=3';
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
				series: [${average_records}]
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

	
	public $all_candidates = 0;
	public $new_candidates = 0;
	public $screened_candidates = 0;
	public $approved_candidates = 0;
	public $unassigned_candidates = 0;
}
?>
