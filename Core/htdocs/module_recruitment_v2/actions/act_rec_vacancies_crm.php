<?php
define('METRES_IN_A_MILE', 1609.344);

class rec_vacancies_crm implements IAction
{
	public function execute(PDO $link)
	{

		header('Content-Type: text/html;');
		$diary_filter_region = isset($_REQUEST['diary_filter_region'])?$_REQUEST['diary_filter_region']:'';
		$candidate_crm = $this->display_candidate_actions($link, $diary_filter_region);
		$employer_crm = $this->display_employer_actions($link);
		$unattached_crm = $this->display_unattached_actions($link, $diary_filter_region);

		echo '<h3>Your Actions <a href="do.php?_action=rec_vacancies_home&cand_start_date=-365" class="actionlist" >Overdue</a>';
		echo '&nbsp;|&nbsp;<a href="do.php?_action=rec_vacancies_home&cand_start_date=-1" class="actionlist" >Current Week</a></h3>';
		echo '<ul style="list-style-type: none; text-align: center; width: 100%; padding: 5px 0; margin: 0; border-top: 1px solid #e9e9e9; border-bottom: 1px solid #e9e9e9; ">';
		echo '<li style="display: inline;"><a href="do.php?_action=rec_vacancies_home&cand_start_date='.$candidate_crm['previous_week'].'" class="actionlist" >&lt; Back a week</a></li>';
		echo '<li style="display: inline; font-size: 1em;">&nbsp;|&nbsp;'.date('D d M y', strtotime($candidate_crm['start_date'])).' - '.date('D d M y', strtotime($candidate_crm['end_date'])).'&nbsp;|&nbsp;</li>';
		echo '<li style="display: inline;"><a href="do.php?_action=rec_vacancies_home&cand_start_date='.$candidate_crm['next_week'].'" class="actionlist" >Forward a week &gt;</a></li>';
		echo '</ul>';
		$options = "SELECT id, description, NULL, CONCAT('WHERE candidate.region = ',CHAR(39),id,CHAR(39)) FROM lookup_vacancy_regions ORDER BY description";
		$options = DAO::getResultset($link, $options, $diary_filter_region);
		echo 'Region:<br>'. HTML::select('diary_filter_region', $options, false, true);
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
		if ( sizeof($employer_crm['employer_data']) > 0 ) {
			echo '<h3 style="font-weight: normal; margin-bottom: 5px;" >Employers to follow up:</h3>';
			echo $employer_crm['employer_data'];
		}
	}

	public function display_candidate_actions(PDO $link, $diary_filter_region = false )
	{

		$start_date = '-1';
		$end_date = '7';

		if ( isset($_REQUEST['cand_start_date']) )
		{
			$start_date = $_REQUEST['cand_start_date'];
			$end_date = $start_date+7;
			// overdue
			if ( $start_date == -365 )
			{
				$end_date = '-1';
			}
		}

		$sql_request_retrieval = 'SELECT candidate.id, candidate.firstnames, candidate.surname, ';
		$sql_request_retrieval .= 'vacancies.id as vac_id, vacancies.postcode, vacancies.job_title, candidate_applications.next_action_date, (SELECT lookup_next_actions.description FROM `lookup_next_actions` WHERE id = candidate_applications.`next_action`) AS description, ';
		$sql_request_retrieval .= 'candidate_applications.application_comments, DATE_ADD(NOW(), INTERVAL '.$start_date.' DAY) as start_date, ';
		$sql_request_retrieval .= 'DATE_ADD(NOW(), INTERVAL '.$end_date.' DAY) as end_date ';
		$sql_request_retrieval .= 'FROM candidate LEFT JOIN candidate_applications ';
		$sql_request_retrieval .= 'ON candidate.id = candidate_applications.candidate_id, ';
		$sql_request_retrieval .= 'vacancies, organisations ';
		$sql_request_retrieval .= 'WHERE vacancies.id = candidate_applications.vacancy_id ';
		$sql_request_retrieval .= 'AND vacancies.employer_id = organisations.id ';
		if ( isset($_SESSION['user']->department) )
		{
			$sql_request_retrieval .= 'AND organisations.region = "'.$_SESSION['user']->department.'" ';
		}
		#$sql_request_retrieval .= 'AND candidate_applications.application_comments != "Screened" ';
		$sql_request_retrieval .= 'AND candidate_applications.next_action_date BETWEEN DATE_ADD(NOW(), INTERVAL '.$start_date.' DAY) AND DATE_ADD(NOW(), INTERVAL '.$end_date.' DAY) ';
		if ( $diary_filter_region )
		{
			$sql_request_retrieval .= 'AND candidate.region = "'.$diary_filter_region.'" ';
		}
		$sql_request_retrieval .= 'order by candidate_applications.next_action_date asc';

		$daterange = array('data' => null, 'start_date' => null, 'end_date' => null);

		$current_date = '';
		$count = 0;

		if( $result = $link->query($sql_request_retrieval) )
		{
			while( $row = $result->fetch() ) {
				if ( $current_date != $row['next_action_date'])
				{
					$current_date = $row['next_action_date'];

					if ( !isset($this->date_info[$current_date]) )
					{
						$this->date_info[$current_date] = 1;
					}
					$daterange[$current_date] = '';
					$daterange['data'] = 1;
				}
				$daterange[$current_date] .= '<div style="';
				if($_SESSION['user']->type != User::TYPE_SYSTEM_VIEWER)
					$daterange[$current_date] .= ' border-top: 1px solid #e9e9e9; width: 98%"><a href="/do.php?_action=rec_view_vacancy&pc='.rawurlencode($row['postcode']).'&id='.$row['vac_id'].'&display='.$row['id'].'">'.$row['firstnames'].' '.$row['surname'].'</a><div style="padding: 0; margin: 0; float:right; width:auto;"> ('.$row['description'].')</div><p style="padding: 0; margin: 0;">'.$row['job_title'].'</p></div>';
				else
					$daterange[$current_date] .= ' border-top: 1px solid #e9e9e9; width: 98%">'.$row['firstnames'].' '.$row['surname'].'<div style="padding: 0; margin: 0; float:right; width:auto;"> ('.$row['application_comments'].')</div><p style="padding: 0; margin: 0;">'.$row['job_title'].'</p></div>';
				$daterange['start_date'] = $row['start_date'];
				$daterange['end_date'] = $row['end_date'];
				$daterange['previous_week'] = $start_date-7;
				$daterange['next_week'] = $start_date+7;
				$count++;
			}
		}

		if ( $count == 0 )
		{
			$sql_request_retrieval = 'SELECT DATE_ADD(NOW(), INTERVAL '.$start_date.' DAY) as start_date, DATE_ADD(NOW(), INTERVAL '.$end_date.' DAY) as end_date from configuration';
			if( $result = $link->query($sql_request_retrieval) )
			{
				while( $row = $result->fetch() )
				{
					$daterange['start_date'] = $row['start_date'];
					$daterange['end_date'] = $row['end_date'];
					$daterange['previous_week'] = $start_date-7;
					$daterange['next_week'] = $start_date+7;
				}
			}
		}

		return $daterange;
	}

	public function display_unattached_actions(PDO $link, $diary_filter_region ) {

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
		if ( $diary_filter_region ) {
			$sql_request_retrieval .= 'AND candidate.region = "'.$diary_filter_region.'" ';
		}
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
					$unattachedata[$current_date] .= ' border-top: 1px solid #e9e9e9; width: 98%"><a href="/do.php?_action=view_candidates&RecViewCandidates_filter_surname='.$row['surname'].'&RecViewCandidates_filter_firstnames='.$row['firstnames'].'">'.$row['firstnames'].' '.$row['surname'].'</a><div style="padding: 0; margin: 0; float:right; width:auto;"> ('.$row['application_comments'].')</div><p style="padding: 0; margin: 0;">unassigned candidate</p></div>';
				else
					$unattachedata[$current_date] .= ' border-top: 1px solid #e9e9e9; width: 98%">'.$row['firstnames'].' '.$row['surname'].'<div style="padding: 0; margin: 0; float:right; width:auto;"> ('.$row['application_comments'].')</div><p style="padding: 0; margin: 0;">unassigned candidate</p></div>';
				$count++;
			}
		}

		return $unattachedata;
	}


	public function display_employer_actions(PDO $link ) {

		$start_date = '-1';
		$end_date = '7';

		$employerdata = array('employer_data' => null);

		if ( isset($_REQUEST['cand_start_date']) ) {
			$start_date = $_REQUEST['cand_start_date'];
			$end_date = $start_date+7;
			// overdue
			if ( $start_date == -365 ) {
				$end_date = '-1';
			}
		}

		$sql_request_retrieval = 'SELECT dpn, company, agreed_action AS note, next_action_date ';
		$sql_request_retrieval .= 'FROM central.emp_pool LEFT JOIN crm_notes ';
		$sql_request_retrieval .= 'ON central.emp_pool.dpn = crm_notes.organisation_id ';
		$sql_request_retrieval .= 'WHERE crm_notes.next_action_date ';
		$sql_request_retrieval .= 'BETWEEN DATE_ADD(NOW(), INTERVAL '.$start_date.' DAY) AND DATE_ADD(NOW(), INTERVAL '.$end_date.' DAY) ';
		if ( isset($_SESSION['user']->department) ) {
			// 	$region_code = array('North West' => 1, 'North East' => 2, 'Midlands' => 3, 'East Midlands' => 4, 'West Midlands' => 5, 'London North' => 6, 'London South' => 7, 'Peterborough' => 8, 'Yorkshire' => 9);
			$region_code = 'select description, id from lookup_vacancy_regions order by description;';
			$region_code = DAO::getResultSet($link, $region_code);
			$regions = array();
			foreach ( $region_code as $code_loc => $region_detail ) {
				$regions[$region_detail[0]] = $region_detail[1];
			}
			$sql_request_retrieval .= 'AND crm_notes.status = "'.$regions[$_SESSION['user']->department].'" ';
		}
		$sql_request_retrieval .= 'GROUP BY dpn ';
		$sql_request_retrieval .= 'order by crm_notes.next_action_date asc';

		if( $result = $link->query($sql_request_retrieval) ) {
			$count = 1;
			$current_date = '';
			while( $row = $result->fetch() ) {
				// don't show ones once they've been converted.
				$exists = DAO::getSingleValue($link, "select count(*) from organisations where zone = '".$row['dpn']."'");
				if ( $exists ) {
					continue;
				}

				if ( $current_date != $row['next_action_date']) {
					$current_date = $row['next_action_date'];

					$employerdata['employer_data'] .= '<div style="border-top: 1px solid #e9e9e9; background-color: #E0EAD0; line-height: 1.2em; font-size:1.2em; font-weight: bold; text-spacing: 0.9em; text-align: right; padding-top: 2px; width: 98%">'.date("D d M Y", strtotime($row['next_action_date'])).'</div>';
				}
				$employerdata['employer_data'] .= '<div style="';
				/*$note_info = $row['note'];
				if ( $note_info == '' ) {
					$note_info = '<br/>';
				}
				else {
					$note_info = DAO::getSingleValue($link, "select note from employerpool_notes where emp_id = '".$row['dpn']."' order by id desc limit 0,1");
				}
				$employerdata['employer_data'] .= ' border-top: 1px solid #e9e9e9; width: 98%"><a href="/do.php?_action=view_employers_pool&ViewEmployersPool_filter_company='.$row['company'].'">'.$row['company'].'</a><p style="padding: 0; margin: 0;">'.$note_info.'</p></div>';
				*/
				$count++;
			}
		}

		return $employerdata;
	}

	private $date_info = array();
}
?>
