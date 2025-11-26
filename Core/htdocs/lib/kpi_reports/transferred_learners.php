<?php

class KPI_Report_transferred_learners extends KPI_Report
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

		$where .= " and course.programme_type= $this->programme_type";
			
		$sql = "
			SELECT 
				CONCAT(tr.surname,', ', tr.firstnames) AS `name`
				, tr.l03 AS reference_number
				, DATE_FORMAT(tr.dob,'%d/%m/%Y') as date_of_birth
				, DATE_FORMAT(NOW(), '%Y') - DATE_FORMAT(tr.dob, '%Y') - (DATE_FORMAT(NOW(), '00-%m-%d') < DATE_FORMAT(tr.dob, '00-%m-%d')) AS age
				, (CASE tr.gender WHEN 'M' THEN 'Male' WHEN 'F' THEN 'Female' ELSE 'Undefined' END) AS gender
				, org.legal_name as employer
				, c.title as contract
				, sq.title as qualification
				, g.title AS `group`
				, IF(ua2.surname is not null,ua2.surname,ua.surname) AS assessor_surname
				#, COALESCE(CONCAT(ua.surname, ', ', ua.firstnames),'n/a') AS assessor	
				# fields that must be unset in the loop ;)
				, tr.contract_id
				, tr.id as tr_id							
			FROM 
				student_qualifications as sq
			LEFT JOIN 
				tr ON (tr.id = sq.tr_id)
			LEFT JOIN
				contracts as c ON (c.id = tr.contract_id)	
			LEFT JOIN
				group_members AS gm ON (gm.tr_id = tr.id)
			LEFT JOIN
				groups AS g ON (g.id = gm.groups_id)	
			LEFT JOIN
				assessor_review AS ar ON (ar.tr_id = tr.id)
			LEFT JOIN
				users AS ua ON (ua.username = g.assessor)
			LEFT JOIN
				users AS ua2 ON (ua2.username = tr.assessor)
			LEFT JOIN
				organisations AS org ON (org.id = tr.employer_id)																
			WHERE 
				sq.a16 = '07' OR sq.a16 = '7' # note i wasn't sure whether khush was stripping off leading zeros or not
				AND c.contract_year = '" . $this->year . "' $where
			ORDER BY
				`name` ASC	
		";
		$query = $this->db->query($sql);
		$result = $query->setFetchMode(PDO::FETCH_ASSOC);
		$c = 0;
		while($row = $query->fetch())
		{
			$row['name'] = '<a href="/do.php?_action=read_training_record&amp;id=' . $row['tr_id'] . '&amp;contract=' . $row['contract_id'] . '">' . ucwords(strtolower($row['name'])) . '</a>';
			unset($row['tr_id'], $row['contract_id']);
			$this->data[] = $row;
		}

	}
}

?>