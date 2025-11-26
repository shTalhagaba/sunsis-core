<?php
define('METRES_IN_A_MILE', 1609.344);

class rec_ajax_vacancies_crm implements IAction
{
	public function execute(PDO $link)
	{
		header('Content-Type: text/html;');
		$candidate_crm = $this->display_candidate_actions($link);
		$unattached_crm = $this->display_unattached_actions($link);

		echo '<h3>Your Actions <a href="do.php?_action=vacancies_home&cand_start_date=-365" class="actionlist" >Overdue</a>';
		echo '&nbsp;|&nbsp;<a href="do.php?_action=vacancies_home&cand_start_date=-1" class="actionlist" >Current Week</a></h3>';
		echo '<ul style="list-style-type: none; text-align: center; width: 100%; padding: 5px 0; margin: 0; border-top: 1px solid #e9e9e9; border-bottom: 1px solid #e9e9e9; ">';
		echo '<li style="display: inline;"><a href="do.php?_action=vacancies_home&cand_start_date='.$candidate_crm['previous_week'].'" class="actionlist" >&lt; Back a week</a></li>';
		echo '<li style="display: inline; font-size: 1em;">&nbsp;|&nbsp;'.date('D d M y', strtotime($candidate_crm['start_date'])).' - '.date('D d M y', strtotime($candidate_crm['end_date'])).'&nbsp;|&nbsp;</li>';
		echo '<li style="display: inline;"><a href="do.php?_action=vacancies_home&cand_start_date='.$candidate_crm['next_week'].'" class="actionlist" >Forward a week &gt;</a></li>';
		echo '</ul>';

		if ( sizeof($candidate_crm['data']) > 0 || sizeof($unattached_crm['data']) > 0 ) {
			echo '<h3 style="font-weight: normal; margin-bottom: 5px;" >Candidates to follow up:</h3>';
			foreach ( $this->date_info as $date_rec => $date_count ) {
				echo '<div style="border-top: 1px solid #e9e9e9; background-color: #E0EAD0; line-height: 1.2em; font-size:1.2em; font-weight: bold; text-align: right; padding-top: 2px; width: 98%">'.date("D d M Y", strtotime($date_rec)).'</div>';
				if ( isset($candidate_crm[$date_rec]) ) {
					echo $candidate_crm[$date_rec];
				}
				if ( isset($unattached_crm[$date_rec]) ) {
					echo $unattached_crm[$date_rec];
				}
			}
		}
	}

	public function display_candidate_actions(PDO $link)
	{
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

/*		$sql_request_retrieval = 'SELECT candidate.id, candidate.firstnames, candidate.surname, ';
		$sql_request_retrieval .= 'vacancies.id as vac_id, vacancies.postcode, vacancies.job_title, candidate_applications.next_action_date,  ';
		$sql_request_retrieval .= 'candidate_applications.application_comments, DATE_ADD(NOW(), INTERVAL '.$start_date.' DAY) as start_date, ';
		$sql_request_retrieval .= 'DATE_ADD(NOW(), INTERVAL '.$end_date.' DAY) as end_date ';
		$sql_request_retrieval .= 'FROM candidate LEFT JOIN candidate_applications ';
		$sql_request_retrieval .= 'ON candidate.id = candidate_applications.candidate_id, ';
		$sql_request_retrieval .= 'vacancies, organisations ';
		$sql_request_retrieval .= 'WHERE vacancies.id = candidate_applications.vacancy_id ';
		$sql_request_retrieval .= 'AND vacancies.employer_id = organisations.id ';
		if ( isset($_SESSION['user']->department) ) {
			$sql_request_retrieval .= 'AND organisations.region = "'.$_SESSION['user']->department.'" ';
		}
		$sql_request_retrieval .= 'AND candidate_applications.application_comments != "Screened" ';
		$sql_request_retrieval .= 'AND candidate_applications.next_action_date BETWEEN DATE_ADD(NOW(), INTERVAL '.$start_date.' DAY) AND DATE_ADD(NOW(), INTERVAL '.$end_date.' DAY) ';
		$sql_request_retrieval .= 'order by candidate_applications.next_action_date asc';*/

		$sql = <<<SQL
SELECT
  candidate.id,
  candidate.firstnames,
  candidate.surname,
  vacancies.id AS vac_id,
  vacancies.postcode,
  vacancies.vacancy_title,
  candidate_crm_notes.next_action_date,
  candidate_crm_notes.`subject`,
  DATE_ADD(NOW(), INTERVAL $start_date DAY) AS start_date,
  DATE_ADD(NOW(), INTERVAL $end_date DAY) AS end_date
FROM
  candidate
  LEFT JOIN candidate_applications
    ON candidate.id = candidate_applications.candidate_id
  LEFT JOIN vacancies
	ON candidate_applications.`vacancy_id` = vacancies.`id`
  LEFT JOIN organisations
	ON vacancies.`employer_id` = organisations.`id`
  LEFT JOIN candidate_crm_notes
	ON candidate.id = candidate_crm_notes.`candidate_id`
WHERE
  candidate.username IS NULL
  AND (candidate_crm_notes.actioned = "No" OR candidate_crm_notes.actioned IS NULL)
  AND candidate_crm_notes.next_action_date BETWEEN DATE_ADD(NOW(), INTERVAL $start_date DAY)
  AND DATE_ADD(NOW(), INTERVAL $end_date DAY)
ORDER BY
  candidate_crm_notes.next_action_date ASC
;
SQL;

		$daterange = array('data' => null, 'start_date' => null, 'end_date' => null);

		$current_date = '';
		$count = 0;

		if( $result = $link->query($sql) ) {
			while( $row = $result->fetch() ) {
				if ( $current_date != $row['next_action_date']) {
					$current_date = $row['next_action_date'];

					if ( !isset($this->date_info[$current_date]) ) {
						$this->date_info[$current_date] = 1;
					}
					$daterange[$current_date] = '';
					$daterange['data'] = 1;
				}
				$daterange[$current_date] .= '<div style="';
				if($_SESSION['user']->type != User::TYPE_SYSTEM_VIEWER)
					$daterange[$current_date] .= ' border-top: 1px solid #e9e9e9; width: 98%">'.$row['firstnames'].' '.$row['surname'].'<div style="padding: 0; margin: 0; float:right; width:auto;"> ('.$row['subject'].')</div><p style="padding: 0; margin: 0;">'.$row['vacancy_title'].'</p></div>';
				else
					$daterange[$current_date] .= ' border-top: 1px solid #e9e9e9; width: 98%">'.$row['firstnames'].' '.$row['surname'].'<div style="padding: 0; margin: 0; float:right; width:auto;"> ('.$row['application_comments'].')</div><p style="padding: 0; margin: 0;">'.$row['job_title'].'</p></div>';
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

	public function display_unattached_actions(PDO $link) {

		$start_date = '-1';
		$end_date = '7';

		$unattachedata = array('data' => null);

		if ( isset($_REQUEST['cand_start_date']) ) {
			$start_date = $_REQUEST['cand_start_date'];
			$end_date = $start_date+7;
			// overdue
			if ( $start_date == -365 ) {
				$end_date = '-1';
			}
		}

		$sql_request_retrieval = 'SELECT candidate.id, candidate.firstnames, candidate.surname, ';
		$sql_request_retrieval .= 'candidate_applications.next_action_date,  ';
		$sql_request_retrieval .= 'candidate_applications.application_comments, DATE_ADD(NOW(), INTERVAL '.$start_date.' DAY) as start_date, ';
		$sql_request_retrieval .= 'DATE_ADD(NOW(), INTERVAL '.$end_date.' DAY) as end_date ';
		$sql_request_retrieval .= 'FROM candidate, candidate_applications ';
		$sql_request_retrieval .= 'where candidate.id = candidate_applications.candidate_id ';
		$sql_request_retrieval .= 'and candidate_applications.vacancy_id = 0 ';
		$sql_request_retrieval .= 'AND candidate_applications.application_comments != "Screened" ';
		$sql_request_retrieval .= 'AND candidate_applications.next_action_date BETWEEN DATE_ADD(NOW(), INTERVAL '.$start_date.' DAY) AND DATE_ADD(NOW(), INTERVAL '.$end_date.' DAY) ';
		$sql_request_retrieval .= 'order by candidate_applications.next_action_date asc';

		if( $result = $link->query($sql_request_retrieval) ) {
			$count = 1;
			$current_date = '';
			while( $row = $result->fetch() ) {
				if ( $current_date != $row['next_action_date']) {
					$current_date = $row['next_action_date'];
					if ( !isset($this->date_info[$current_date]) ) {
						$this->date_info[$current_date] = 1;
					}
					$unattachedata[$current_date] = '';
					$unattachedata['data'] = 1;
				}
				$unattachedata[$current_date] .= '<div style="';
				if($_SESSION['user']->type != User::TYPE_SYSTEM_VIEWER)
					$unattachedata[$current_date] .= ' border-top: 1px solid #e9e9e9; width: 98%"><a href="/do.php?_action=view_candidates&ViewCandidates_filter_surname='.$row['surname'].'&ViewCandidates_filter_firstnames='.$row['firstnames'].'">'.$row['firstnames'].' '.$row['surname'].'</a><div style="padding: 0; margin: 0; float:right; width:auto;"> ('.$row['application_comments'].')</div><p style="padding: 0; margin: 0;">unassigned candidate</p></div>';
				else
					$unattachedata[$current_date] .= ' border-top: 1px solid #e9e9e9; width: 98%">'.$row['firstnames'].' '.$row['surname'].'<div style="padding: 0; margin: 0; float:right; width:auto;"> ('.$row['application_comments'].')</div><p style="padding: 0; margin: 0;">unassigned candidate</p></div>';
				$count++;
			}
		}

		return $unattachedata;
	}

	private $date_info = array();
}
?>
