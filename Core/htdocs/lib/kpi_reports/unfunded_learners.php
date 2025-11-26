<?php

class KPI_Report_unfunded_learners extends KPI_Report
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
				CONCAT(tr.surname,', ', tr.firstnames) AS `name`
				, l03 AS reference_number
				#, DATE_FORMAT(tr.dob,'%d/%m/%Y') as date_of_birth
				, DATE_FORMAT(NOW(), '%Y') - DATE_FORMAT(tr.dob, '%Y') - (DATE_FORMAT(NOW(), '00-%m-%d') < DATE_FORMAT(tr.dob, '00-%m-%d')) AS age
				#, (CASE tr.gender WHEN 'M' THEN 'Male' WHEN 'F' THEN 'Female' ELSE 'Undefined' END) AS gender
				, (SELECT id FROM framework_qualifications WHERE framework_id IN (SELECT framework_id FROM student_qualifications WHERE tr_id = tr.id) LIMIT 0,1)  AS aim_reference
				, (SELECT internaltitle FROM framework_qualifications WHERE main_aim = 1 and framework_id IN (SELECT framework_id FROM student_qualifications WHERE tr_id = tr.id) LIMIT 0,1) AS aim_title				
				, concat(acs.firstnames,' ',acs.surname) as apprentice_coordinator
                , org.legal_name as employer
                , (SELECT title FROM brands WHERE brands.id = org.manufacturer) AS brand
				, c.title as contract
				, g.title as group_title
				, IF(ua2.surname is not null,ua2.surname,ua.surname) AS assessor_surname
				, DATE_FORMAT(tr.start_date,'%d/%m/%Y') as start_date
				, DATE_FORMAT(tr.target_date,'%d/%m/%Y') AS target_completion_date
				, (TO_DAYS(NOW()) - TO_DAYS(tr.target_date)) as unfunded_days
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
				group_members AS gm ON (gm.tr_id = tr.id)
			LEFT JOIN
				groups AS g ON (g.id = gm.groups_id)				
			LEFT JOIN
				contracts as c ON (c.id = tr.contract_id)
			LEFT JOIN
				users AS ua ON (ua.id = g.assessor)
			LEFT JOIN
				users AS ua2 ON (ua2.id = tr.assessor)
			LEFT JOIN
				users as acs on acs.username = tr.programme
			LEFT JOIN
				organisations AS org ON (org.id = tr.employer_id)
			WHERE 
				status_code <> 2 AND closure_date IS NULL AND NOW() > target_date
				AND c.contract_year = '" . $this->year . "' $where
			ORDER BY
				`name` ASC	
		";
		//pre($sql);
		$query = $this->db->query($sql);
		$result = $query->setFetchMode(PDO::FETCH_ASSOC);
		$c = 0;
		while($row = $query->fetch())
		{
			$row['name'] = '<a href="/do.php?_action=read_training_record&amp;id=' . $row['tr_id'] . '&amp;contract=' . $row['contract_id'] . '">' . ucwords(strtolower($row['name'])) . '</a>';
			
			//if ( preg_match('/LSC/i',$row['contract']) ) { $row['contract'] = 'LSC';  }
			//elseif ( preg_match('/Scottish/i',$row['contract']) ) {  $row['contract'] = 'Scottish';   }

		//	if ( preg_match('/Level 2/i',$row['NVQ_Title']) ) { $row['NVQ_Title'] = 'Level 2';  }
		//	elseif ( preg_match('/Level 3/i',$row['NVQ_Title']) ) {  $row['NVQ_Title'] = 'Level 3';   } 
		//	elseif ( preg_match('/Level 1/i',$row['NVQ_Title']) ) {  $row['NVQ_Title'] = 'Level 1';   } 
			
			$tr_id = $row['tr_id'];
			
			unset($row['tr_id'], $row['contract_id']);

			// set default values to handle issue with no ilr being found - re
			$row['programme_type'] = "";
			$row['national_insurance'] = "";
			$row['gender'] = "";
			$row['disability'] = "";
			$row['ethnicity'] = "";
			$row['als'] = "";
			$row['home_postcode'] = "";
			
			// Is it TtG or Apprenticeship?
			$ilr = DAO::getSingleValue($link, "select ilr from ilr where tr_id = $tr_id order by contract_id DESC, submission DESC LIMIT 0,1");
			if ( isset($ilr) && $ilr != '' ) {
				$ilr = Ilr2010::loadFromXML($ilr);
				$a15 = (int)$ilr->aims[0]->A15;
				$row['programme_type'] = ($a15==99)?"Adult NVQ":"Apprenticeship";
			}

			$additional = DAO::getResultset($link, "select extractvalue(ilr,'/ilr/learner/L13'), extractvalue(ilr,'/ilr/learner/L15'), extractvalue(ilr,'/ilr/learner/L12'), extractvalue(ilr,'/ilr/learner/L29'), extractvalue(ilr,'/ilr/learner/L17') from ilr left join contracts on contracts.id = ilr.contract_id where tr_id = $tr_id order by contract_year desc, submission desc");
			if ( isset($additional[0]) ) {
				$row['gender'] = $additional[0][0];
				$row['disability'] = $additional[0][1];
				$row['ethnicity'] = $additional[0][2];
				$row['als'] = $additional[0][3];
				$row['home_postcode'] = $additional[0][4];
			}
			$this->data[] = $row;
		}

	}
}

?>