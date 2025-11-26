<?php
define('METRES_IN_A_MILE', 1609.344);

class ajax_employer_crm implements IAction
{
	public function execute(PDO $link)	{

		header('Content-Type: text/html;');
		$diary_filter_region = isset($_REQUEST['diary_filter_region'])?$_REQUEST['diary_filter_region']:'';
		$employerpool_crm = $this->display_employerpool_actions($link, $diary_filter_region);
		$employer_crm = $this->display_employer_actions($link, $diary_filter_region);
		echo '<div class="block"><h3>Your Actions <a href="do.php?_action=empengage_home&emp_start_date=-365" class="actionlist" >Overdue</a>';
		echo '&nbsp;|&nbsp;<a href="do.php?_action=empengage_home&emp_start_date=-1" class="actionlist" >Current Week</a></h3>';
		echo '<ul style="list-style-type: none; text-align: center; width: 100%; padding: 5px 0; margin: 0; border-top: 1px solid #e9e9e9; border-bottom: 1px solid #e9e9e9; ">';
		echo '<li style="display: inline;"><a href="do.php?_action=empengage_home&emp_start_date='.$employerpool_crm['previous_week'].'" class="actionlist" >&lt; Back a week</a></li>';
		echo '<li style="display: inline; font-size: 1em;">&nbsp;|&nbsp;'.date('D d M y', strtotime($employerpool_crm['start_date'])).' - '.date('D d M y', strtotime($employerpool_crm['end_date'])).'&nbsp;|&nbsp;</li>';
		echo '<li style="display: inline;"><a href="do.php?_action=empengage_home&emp_start_date='.$employerpool_crm['next_week'].'" class="actionlist" >Forward a week &gt;</a></li>';
		echo '</ul>';
		$options = "select description, description, null from lookup_vacancy_regions order by description";
		$options = DAO::getResultset($link, $options, $diary_filter_region);
		echo 'Region:<br>'. HTML::select('diary_filter_region', $options, false, true);
		if ( $employerpool_crm['data'] == 1 || $employer_crm['data'] == 1 ) {
			echo '<h3 style="font-weight: normal; margin-bottom: 5px;" >Employers/Prospects to follow up:</h3>';
			foreach ( $this->date_info as $date_rec => $date_count ) {
				echo '<div style="border-top: 1px solid #e9e9e9; background-color: #E0EAD0; line-height: 1.2em; font-size:1.2em; font-weight: bold; text-align: right; padding-top: 2px; width: 98%">'.date("D d M Y", strtotime($date_rec)).'</div>';
				if ( isset($employerpool_crm[$date_rec]) ) {
					echo $employerpool_crm[$date_rec];
				}
				if ( isset($employer_crm[$date_rec]) ) {
					echo $employer_crm[$date_rec];
				}
			}
		}
		echo '</div>';
	}

	public function display_employerpool_actions(PDO $link, $diary_filter_region = false ) {

		if($_SESSION['user']->isAdmin())
			$where = "";
		else
			$where = ' AND LOCATE("' . $_SESSION['user']->username . '", employerpool_notes.audit_info) > 0 ';
		$start_date = '-1';
		$end_date = '7';

		$employerdata = array('data' => 0, 'start_date' => null, 'end_date' => null);

		if ( isset($_REQUEST['emp_start_date']) ) {
			$start_date = $_REQUEST['emp_start_date'];
			$end_date = $start_date+7;
			// overdue
			if ( $start_date == -365 ) {
				$end_date = '-1';
			}
		}

		$count = 0;

		$sql_request_retrieval = 'SELECT central.emp_pool.dpn, central.emp_pool.auto_id as id, central.emp_pool.company as legal_name, employerpool_notes.agreed_action, employerpool_notes.next_action_date as date, employerpool_notes.name_of_person, employerpool_notes.priority, ';
		$sql_request_retrieval .= 'DATE_ADD(NOW(), INTERVAL '.$start_date.' DAY) AS start_date, ';
		$sql_request_retrieval .= 'DATE_ADD(NOW(), INTERVAL '.$end_date.' DAY) AS end_date ';
		$sql_request_retrieval .= 'FROM central.emp_pool LEFT JOIN employerpool_notes ON central.emp_pool.auto_id = employerpool_notes.organisation_id ';
		$sql_request_retrieval .= 'WHERE employerpool_notes.next_action_date BETWEEN DATE_ADD(NOW(), INTERVAL '.$start_date.' DAY) AND DATE_ADD(NOW(), INTERVAL '.$end_date.' DAY) ';
		//if ( isset($_SESSION['user']->department) ) {
		//		$region_code = array('North West' => 1, 'North East' => 2, 'Midlands' => 3, 'East Midlands' => 4, 'West Midlands' => 5, 'London North' => 6, 'London South' => 7, 'Peterborough' => 8, 'Yorkshire' => 9);
		//        $sql_request_retrieval .= 'AND employerpool_notes.status = "'.$region_code[$_SESSION['user']->department].'" ';
		//}
		if ( $diary_filter_region ) {
			$sql_request_retrieval .= 'AND central.emp_pool.region = "'.$diary_filter_region.'" ';
		}
		$sql_request_retrieval .= $where;
		$sql_request_retrieval .= 'GROUP BY central.emp_pool.dpn ';
		$sql_request_retrieval .= 'ORDER BY employerpool_notes.next_action_date ASC';

		if( $result = $link->query($sql_request_retrieval) ) {

			$current_date = '';
			while( $row = $result->fetch() ) {

				if ( !isset($this->date_info[$row['date']]) ) {
					$this->date_info[$row['date']] = 1;
					$employerdata['data'] = 1;
				}

				if ( $current_date != $row['date']) {
					$current_date = $row['date'];
					$employerdata[$current_date] = '';
					$count++;
				}
				$note_info = $row['agreed_action'];
				if ( $note_info == '' ) {
					$note_info = '<br/>';
				}
				else {
					$note_info = DAO::getSingleValue($link, "select agreed_action from employerpool_notes where organisation_id = '".$row['id']."' order by id desc limit 0,1");
				}
				// next action
				$next_action_desc = DAO::getSingleValue($link, "select org_status_comment from organisations_status where org_id = '".$row['id']."' ");
				$employerdata[$current_date] .= '<div style="border-top: 1px solid #e9e9e9; width: 98% ';
				if ( $row['priority'] == 1 ) {
					$employerdata[$current_date] .= 'color: #FF0000; ';
				}
				$employerdata[$current_date] .= '"><strong>(Employer Pool)</strong> <a href="/do.php?_action=edit_crm_note&pool_id='.$row['dpn'].'&organisations_id='.$row['id'].'&amp;organisation_type=prospect">'.$row['legal_name'].'</a><span style="float:right">'.$row['name_of_person'].'</span><p style="padding: 0; margin: 0;">'.$note_info.'&nbsp;<strong>'.$next_action_desc.'</strong></p></div>';
				$employerdata['start_date'] = $row['start_date'];
				$employerdata['end_date'] = $row['end_date'];
				$employerdata['previous_week'] = $start_date-7;
				$employerdata['next_week'] = $start_date+7;
				$count++;
			}
		}

		if ( $count == 0 ) {
			$sql_request_retrieval = 'SELECT DATE_ADD(NOW(), INTERVAL '.$start_date.' DAY) as start_date, DATE_ADD(NOW(), INTERVAL '.$end_date.' DAY) as end_date from configuration';
			if( $result = $link->query($sql_request_retrieval) ) {
				while( $row = $result->fetch() ) {
					$employerdata['start_date'] = $row['start_date'];
					$employerdata['end_date'] = $row['end_date'];
					$employerdata['previous_week'] = $start_date-7;
					$employerdata['next_week'] = $start_date+7;
				}
			}
		}

		return $employerdata;
	}

	public function display_employer_actions(PDO $link, $diary_filter_region = false ) {

		if($_SESSION['user']->isAdmin())
			$where = "";
		else
			$where = ' AND LOCATE("' . $_SESSION['user']->username . '", crm_notes.audit_info) > 0 ';

		$start_date = '-1';
		$end_date = '7';

		$employerdata = array('data' => 0, 'start_date' => null, 'end_date' => null);

		if ( isset($_REQUEST['emp_start_date']) ) {
			$start_date = $_REQUEST['emp_start_date'];
			$end_date = $start_date+7;
			// overdue
			if ( $start_date == -365 ) {
				$end_date = '-1';
			}
		}

		$count = 0;

		$sql_request_retrieval = 'SELECT organisations.id, organisations.legal_name, crm_notes.agreed_action, crm_notes.next_action_date as date, crm_notes.name_of_person, crm_notes.priority, ';
		$sql_request_retrieval .= 'DATE_ADD(NOW(), INTERVAL '.$start_date.' DAY) AS start_date, ';
		$sql_request_retrieval .= 'DATE_ADD(NOW(), INTERVAL '.$end_date.' DAY) AS end_date ';
		$sql_request_retrieval .= 'FROM organisations LEFT JOIN crm_notes ON organisations.id = crm_notes.organisation_id ';
		$sql_request_retrieval .= 'WHERE crm_notes.next_action_date BETWEEN DATE_ADD(NOW(), INTERVAL '.$start_date.' DAY) AND DATE_ADD(NOW(), INTERVAL '.$end_date.' DAY) ';
		if ( isset($_SESSION['user']->department) ) {
			$region_code = array('North West' => 1, 'North East' => 2, 'Midlands' => 3, 'East Midlands' => 4, 'West Midlands' => 5, 'London North' => 6, 'London South' => 7, 'Peterborough' => 8, 'Yorkshire' => 9);
			$sql_request_retrieval .= 'AND employerpool_notes.status = "'.$region_code[$_SESSION['user']->department].'" ';
		}
		if ( $diary_filter_region ) {
			$sql_request_retrieval .= 'AND organisations.region = "'.$diary_filter_region.'" ';
		}
		$sql_request_retrieval .= $where;
		$sql_request_retrieval .= 'GROUP BY organisations.id ';
		$sql_request_retrieval .= 'ORDER BY crm_notes.next_action_date ASC';

		if( $result = $link->query($sql_request_retrieval) ) {

			$current_date = '';
			while( $row = $result->fetch() ) {

				if ( !isset($this->date_info[$row['date']]) ) {
					$this->date_info[$row['date']] = 1;
					$employerdata['data'] = 1;
				}

				if ( $current_date != $row['date']) {
					$current_date = $row['date'];
					$employerdata[$current_date] = '';
					$count++;
				}
				$note_info = $row['agreed_action'];
				if ( $note_info == '' ) {
					$note_info = '<br/>';
				}
				else {
					$note_info = DAO::getSingleValue($link, "select agreed_action from crm_notes where organisation_id = '".$row['id']."' order by id desc limit 0,1");
				}

				// next action
				$next_action_desc = DAO::getSingleValue($link, "select org_status_comment from organisations_status where org_id = '".$row['id']."' ");
				$employerdata[$current_date] .= '<div style="border-top: 1px solid #e9e9e9; width: 98%; ';
				if ( $row['priority'] == 1 ) {
					$employerdata[$current_date] .= 'color: #FF0000; ';
				}
				$employerdata[$current_date] .= '"><strong>(Employer) </strong><a href="/do.php?_action=edit_crm_note&organisations_id='.$row['id'].'&amp;organisation_type=employer">'.$row['legal_name'].'</a><span style="float:right">'.$row['name_of_person'].'</span><p style="padding: 0; margin: 0;">'.$note_info.'&nbsp;<strong>'.$next_action_desc.'</strong></p></div>';
				$employerdata['start_date'] = $row['start_date'];
				$employerdata['end_date'] = $row['end_date'];
				$employerdata['previous_week'] = $start_date-7;
				$employerdata['next_week'] = $start_date+7;
				$count++;
			}
		}

		if ( $count == 0 ) {
			$sql_request_retrieval = 'SELECT DATE_ADD(NOW(), INTERVAL '.$start_date.' DAY) as start_date, DATE_ADD(NOW(), INTERVAL '.$end_date.' DAY) as end_date from configuration';
			if( $result = $link->query($sql_request_retrieval) ) {
				while( $row = $result->fetch() ) {
					$employerdata['start_date'] = $row['start_date'];
					$employerdata['end_date'] = $row['end_date'];
					$employerdata['previous_week'] = $start_date-7;
					$employerdata['next_week'] = $start_date+7;
				}
			}
		}

		return $employerdata;
	}

	private $date_info = array();
}
?>
