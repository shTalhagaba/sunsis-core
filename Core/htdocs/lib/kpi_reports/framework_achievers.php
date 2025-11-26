<?php

class KPI_Report_framework_achievers extends KPI_Report
{
	function __construct($link, $year, $programme_type)
	{
		parent::__construct($link, $year, $programme_type);
	}
	
	protected function getData($link)
	{
			$where = '';
			if($_SESSION['user']->isAdmin() || $_SESSION['user']->type==12 || $_SESSION['user']->type==15)
			{
				$where = '';
			}
			elseif($_SESSION['user']->isOrgAdmin() || $_SESSION['user']->type==8 || $_SESSION['user']->type==13 || $_SESSION['user']->type==14)
			{
				$emp = $_SESSION['user']->employer_id;
				$where = ' and (tr.provider_id= '. $emp . ' or tr.employer_id=' . $emp . ')';
			}
			elseif($_SESSION['user']->type==2)
			{
				$username = $_SESSION['user']->username;
                $id = $_SESSION['user']->id;
				$where = ' and (g.tutor = '. '"' . $username . '"' . ' or course_qualifications_dates.tutor_username like ' . '"' . $id . '" or tr.tutor="' . $id . '")';
			}
			elseif($_SESSION['user']->type==3)
			{
				$username = $_SESSION['user']->username;
				$where = ' and (g.assessor = '. '"' . $username . '" or tr.assessor="' . $username . '")'; 
			}
			elseif($_SESSION['user']->type==4)
			{
				$username = $_SESSION['user']->username;
                $id = $_SESSION['user']->id;
				$where = ' and (g.verifier = '. '"' . $username . '" or tr.verifier="' . $id . '")';
			}
			elseif($_SESSION['user']->type==6)
			{
				$username = $_SESSION['user']->username;
				$where = ' and g.wbcoordinator = '. '"' . $username . '"'; 
			}
			elseif($_SESSION['user']->type==5)
			{
				$username = $_SESSION['user']->username;
				$where = ' and tr.username = ' . '"' . $username . '"';
			}
			elseif($_SESSION['user']->type==9)
			{
				$username = $_SESSION['user']->username;
				$where = ' and assessors.supervisor = '. '"' . $username . '"'; 
			}
			elseif($_SESSION['user']->type==16)
			{
				$emp = $_SESSION['user']->employer_id;
				$where = ' and (contracts.contract_holder= '. $emp . ')';
			}
			elseif($_SESSION['user']->type==18)
			{
				$supervisors = preg_replace('/([^,]+)/', '\'$1\'', $_SESSION['user']->supervisor);
				$assessors = DAO::getSingleValue($link, "SELECT GROUP_CONCAT(\"\'\",username,\"\'\") FROM users WHERE supervisor in ($supervisors);");
				$where = ' and (g.assessor in (' . $assessors . ') or tr.assessor in (' . $assessors . '))';
			}
			elseif($_SESSION['user']->type==19)
			{
				$brand = $_SESSION['user']->department;
				$where = " and org.manufacturer = '$brand'";
			}
            elseif($_SESSION['user']->type==20)
            {
                $username = $_SESSION['user']->username;
                $where = ' and (tr.programme="' . $username . '")';
            }
            elseif($_SESSION['user']->type==21)
            {
                $username = $_SESSION['user']->username;
                $where = ' and (find_in_set("' . $username . '", course.director))';
            }

		$where .= " and course.programme_type= $this->programme_type";
			
			$sql = "
				SELECT DISTINCT
					CONCAT(tr.surname,', ', tr.firstnames) AS `name`
					, l03 AS reference_number
					, learners.enrollment_no as member_no
					#, DATE_FORMAT(tr.dob,'%d/%m/%Y') as date_of_birth
					, DATE_FORMAT(NOW(), '%Y') - DATE_FORMAT(tr.dob, '%Y') - (DATE_FORMAT(NOW(), '00-%m-%d') < DATE_FORMAT(tr.dob, '00-%m-%d')) AS age
					#, (CASE tr.gender WHEN 'M' THEN 'Male' WHEN 'F' THEN 'Female' ELSE 'Undefined' END) AS gender
                , concat(acs.firstnames,' ',acs.surname) as apprentice_coordinator
					, sq.id as aim_reference
					, org.retailer_code as region
					, org.district as area_code
					, org.legal_name as employer
					, c.title as contract
					, sf.title as framework
					, g.title as group_title
					, COALESCE(CONCAT(ua.surname, ', ', ua.firstnames),'n/a') AS assessor				
					, DATE_FORMAT(tr.start_date,'%d/%m/%Y') as start_date
					, DATE_FORMAT(tr.target_date,'%d/%m/%Y') AS target_completion_date
					, DATE_FORMAT(tr.closure_date,'%d/%m/%Y') AS actual_completion_date 
					, (TO_DAYS(target_date) - TO_DAYS(closure_date)) as days_finished_early
					# fields that must be unset in the loop ;)
					, tr.contract_id
					, tr.id as tr_id				
                    , lookup_programme_type.description as programme_type
				FROM
					tr 
				LEFT JOIN 
					courses_tr on courses_tr.tr_id = tr.id
				LEFT JOIN
					courses as course on course.id = courses_tr.course_id
                LEFT JOIN lookup_programme_type
                    on lookup_programme_type.code = course.programme_type
				LEFT JOIN
					group_members AS gm ON (gm.tr_id = tr.id)
				LEFT JOIN
					groups AS g ON (g.id = gm.groups_id)				
				LEFT JOIN
					contracts as c ON (c.id = tr.contract_id)				
				LEFT JOIN 
					student_frameworks as sf ON (sf.tr_id = tr.id)
				LEFT JOIN
					assessor_review AS ar ON (ar.tr_id = tr.id)
				LEFT JOIN
					users AS ua ON (ua.username = g.assessor)	
				LEFT JOIN
					organisations AS org ON (org.id = tr.employer_id)
				LEFT JOIN
					student_qualifications as sq on sq.tr_id = tr.id and sq.qualification_type = 'NVQ'
    			LEFT JOIN
	    			users as acs on acs.username = tr.programme
				LEFT JOIN
					users as learners on learners.username = tr.username							
				WHERE 
					status_code = 2	
					#and tr.closure_date >= c.start_date and tr.closure_date <= c.end_date
					AND c.contract_year = '" . $this->year . "' $where
				GROUP BY tr.id
				ORDER BY `name` ASC	
			";
			
		//pre($sql);
		$query = $this->db->query($sql);
		$result = $query->setFetchMode(PDO::FETCH_ASSOC);
		$c = 0;
		while($row = $query->fetch())
		{
			$tr_id = $row['tr_id'];
						
			$row['name'] = '<a href="do.php?_action=read_training_record&id=' . $row['tr_id'] . '&amp;contract=' . $row['contract_id'] . '">' . ucwords(strtolower($row['name'])) . '</a>';
			unset($row['tr_id'], $row['contract_id']);

			if(DB_NAME!='am_tmuk')
			{if ( preg_match('/LSC/i',$row['contract']) ) { $row['contract'] = 'LSC';  }
			elseif ( preg_match('/Scottish/i',$row['contract']) ) {  $row['contract'] = 'Scottish';   } 

			if ( preg_match('/NVQ2/i',$row['framework']) ) { $row['framework'] = 'Level 2';  }
			elseif ( preg_match('/NVQ3/i',$row['framework']) ) {  $row['framework'] = 'Level 3';   } 
			elseif ( preg_match('/NVQ1/i',$row['framework']) ) {  $row['framework'] = 'Level 1';   }

			$additional = DAO::getResultset($link, "select extractvalue(ilr,'/ilr/learner/L13'), extractvalue(ilr,'/ilr/learner/L15'), extractvalue(ilr,'/ilr/learner/L12'), extractvalue(ilr,'/ilr/learner/L29'), extractvalue(ilr,'/ilr/learner/L17') from ilr left join contracts on contracts.id = ilr.contract_id where tr_id = $tr_id order by contract_year desc, submission desc");
			//$row['gender'] = $additional[0][0];
			//$row['disability'] = $additional[0][1];
			//$row['ethnicity'] = $additional[0][2];
			//$row['als'] = $additional[0][3];
			}
			
			$this->data[] = $row;
		}

	}
}

?>