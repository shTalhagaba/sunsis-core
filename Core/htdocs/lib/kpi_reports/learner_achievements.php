<?php

class KPI_Report_learner_achievements extends KPI_Report
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
				$where = ' and (g.tutor = '. '"' . $username . '"' . ' or course_qualifications_dates.tutor_username = ' . '"' . $id . '" or tr.tutor="' . $id . '")';
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
			SELECT 
				tr.l03 AS reference_number
				, CONCAT(tr.surname,', ', tr.firstnames) AS `name`
				, DATE_FORMAT(tr.dob,'%d/%m/%Y') AS date_of_birth
				, DATE_FORMAT(NOW(), '%Y') - DATE_FORMAT(tr.dob, '%Y') - (DATE_FORMAT(NOW(), '00-%m-%d') < DATE_FORMAT(tr.dob, '00-%m-%d')) AS age				
				, sq.internaltitle as programme_level
				#, (CASE tr.gender WHEN 'M' THEN 'Male' WHEN 'F' THEN 'Female' ELSE 'Undefined' END) AS gender
				#, org.legal_name as employer
				 , concat(acs.firstnames,' ',acs.surname) as apprentice_coordinator
				 , c.title as contract
				#, sf.title as framework
				, COALESCE(g.title,'n/a') AS `group`
				, IF(ua2.surname is not null,ua2.surname,ua.surname) AS assessor
				, tsubquery.total AS total_aims
				, COALESCE(asubquery.total,0) AS total_completed
				, round((COALESCE(asubquery.total,0) / tsubquery.total * 100),2) as percentage_completed
				# fields that must be unset in the loop ;)
				, tr.contract_id
				, tr.id as tr_id				
			FROM 
				tr
			LEFT JOIN
				courses_tr on courses_tr.tr_id = tr.id
			LEFT JOIN
				courses as course on course.id = courses_tr.course_id
			LEFT JOIN
				contracts as c ON (c.id = tr.contract_id)	
			 LEFT JOIN
				assessor_review AS ar ON (ar.tr_id = tr.id)
			LEFT JOIN
				group_members AS gm ON (gm.tr_id = tr.id)
			LEFT JOIN
				groups AS g ON (g.id = gm.groups_id)												
			LEFT JOIN
				users AS ua ON (ua.username = g.assessor)
			LEFT JOIN
				users as acs on acs.username = tr.programme
			LEFT JOIN
				users AS ua2 ON (ua2.username = tr.assessor)
			LEFT JOIN
				student_qualifications as sq on sq.tr_id = tr.id					
			LEFT JOIN
				(
					SELECT 
						COUNT(*) AS total
						, tr_id 
					FROM 
						student_qualifications 
					WHERE 
						achievement_date IS NOT NULL
						AND framework_id > 0
					GROUP BY 
						tr_id
				) AS asubquery ON (asubquery.tr_id = tr.id)
			LEFT JOIN
				(
					SELECT 
						COUNT(*) AS total
						, tr_id 
					FROM 
						student_qualifications 
					WHERE
						framework_id > 0
					GROUP BY 
						tr_id
				) AS tsubquery ON (tsubquery.tr_id = tr.id)	
			LEFT JOIN
				student_frameworks as sf ON (sf.tr_id = tr.id)	
			LEFT JOIN
				organisations AS org ON (org.id = tr.employer_id)					
			WHERE
				(asubquery.total IS NULL OR asubquery.total < tsubquery.total)
				AND c.contract_year = '" . $this->year . "' and tr.status_code = 1 $where
			group by tr.l03
			ORDER BY 
				NAME ASC
				, total_aims
				, total_completed DESC
		";
		//pre($sql);
		$query = $this->db->query($sql);
		$result = $query->setFetchMode(PDO::FETCH_ASSOC);
		$c = 0;
		while($row = $query->fetch())
		{
			$row['reference_number'] = '<a href="do.php?_action=read_training_record&id=' . $row['tr_id'] . '&amp;contract=' . $row['contract_id'] . '">' . ucwords(strtolower($row['reference_number'])) . '</a>';
			$row['total_aims'] = '<a href="do.php?_action=read_training_record&id=' . $row['tr_id'] . '&amp;contract=' . $row['contract_id'] . '#anchor-qualifications" style="text-decoration: underline;">' . $row['total_aims'] . '</a>';
			$row['total_completed'] = '<a href="do.php?_action=read_training_record&id=' . $row['tr_id'] . '&amp;contract=' . $row['contract_id'] . '#anchor-qualifications" style="text-decoration: underline;">' . $row['total_completed'] . '</a>';
			$tr_id = $row['tr_id'];
			unset($row['tr_id'], $row['contract_id']);

			if ( preg_match('/LSC/i',$row['contract']) ) { $row['contract'] = 'LSC';  }
			elseif ( preg_match('/Scottish/i',$row['contract']) ) {  $row['contract'] = 'Scottish';   } 
			
			if ( preg_match('/Level 1/i',$row['programme_level']) ) { $row['programme_level'] = 'Level 1';  }
			elseif ( preg_match('/Level 2|NVQ2/i',$row['programme_level']) ) {  $row['programme_level'] = 'Level 2';   } 
			elseif ( preg_match('/Level 3/i',$row['programme_level']) ) {  $row['programme_level'] = 'Level 3';   } 
			
			// Is it TtG or Apprenticeship?
//			$ilr = DAO::getSingleValue($link, "select ilr from ilr where tr_id = $tr_id order by contract_id DESC, submission DESC LIMIT 1");
//			$ilr = Ilr2009::loadFromXML($ilr);
//			$a15 = (int)$ilr->aims[0]->A15;
//			$row['programme_type'] = ($a15==99)?"Adult NVQ":"Apprenticeship";	

			$additional = DAO::getResultset($link, "select extractvalue(ilr,'/ilr/learner/L13'), extractvalue(ilr,'/ilr/learner/L15'), extractvalue(ilr,'/ilr/learner/L12'), extractvalue(ilr,'/ilr/learner/L29'), extractvalue(ilr,'/ilr/learner/L17') from ilr left join contracts on contracts.id = ilr.contract_id where tr_id = $tr_id order by contract_year desc, submission desc");
		//	$row['gender'] = $additional[0][0];
		//	$row['disability'] = $additional[0][1];
		//	$row['ethnicity'] = $additional[0][2];
		//	$row['als'] = $additional[0][3];
		//	$row['home_postcode'] = $additional[0][4];


			$this->data[] = $row;
		}

	}
}

?>