<?php

class KPI_Report_newstarts_period4 extends KPI_Report
{
	function __construct($link, $year, $programme_type)
	{
		parent::__construct($link, $year, $programme_type);
	}
	
	protected function getData($link)
	{
		
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
			SELECT distinct
				c.title as contract
				, sq.internaltitle as programme_level				
				, CONCAT(tr.surname,', ', tr.firstnames) AS `name`
				, l03 AS reference_number
				#, DATE_FORMAT(tr.dob,'%d/%m/%Y') AS date_of_birth
				, DATE_FORMAT(NOW(), '%Y') - DATE_FORMAT(tr.dob, '%Y') - (DATE_FORMAT(NOW(), '00-%m-%d') < DATE_FORMAT(tr.dob, '00-%m-%d')) AS age
				, (CASE WHEN 
					DATE_FORMAT(tr.start_date, '%Y') - DATE_FORMAT(tr.dob, '%Y') - (DATE_FORMAT(tr.start_date, '00-%m-%d') < DATE_FORMAT(tr.dob, '00-%m-%d')) between 16 and 18  THEN '16-18'
					WHEN
					DATE_FORMAT(tr.start_date, '%Y') - DATE_FORMAT(tr.dob, '%Y') - (DATE_FORMAT(tr.start_date, '00-%m-%d') < DATE_FORMAT(tr.dob, '00-%m-%d')) between 19 and 24  THEN '19-24' 
					WHEN
					DATE_FORMAT(tr.start_date, '%Y') - DATE_FORMAT(tr.dob, '%Y') - (DATE_FORMAT(tr.start_date, '00-%m-%d') < DATE_FORMAT(tr.dob, '00-%m-%d')) between 25 and 100  THEN '24+' 
					ELSE 'Other' END ) as ageband
				#, (CASE tr.gender WHEN 'M' THEN 'Male' WHEN 'F' THEN 'Female' ELSE 'Undefined' END) AS gender
				, sq.id as aim_reference				
				#, org.legal_name as employer
				, org.manufacturer as Brand				
				, DATE_FORMAT(tr.start_date,'%d/%m/%Y') as start_date
				, DATE_FORMAT(tr.target_date,'%d/%m/%Y') AS target_completion_date
				#, course.title as course_title
				#, g.title as group_title
				, ua.surname AS assessor_surname
				# fields that must be unset in the loop ;)
				, tr.contract_id
				, tr.id as tr_id
				#, RIGHT(central.lookup_submission_dates.submission,2) as submission
			FROM 
				tr
			LEFT JOIN
				group_members AS gm ON (gm.tr_id = tr.id)
			LEFT JOIN
				central.lookup_submission_dates ON tr.start_date >= central.lookup_submission_dates.start_submission_date AND tr.start_date <= central.lookup_submission_dates.last_submission_date AND submission!='W13'
			LEFT JOIN
				groups AS g ON (g.id = gm.groups_id)					
			LEFT JOIN
				courses_tr AS ctr ON (tr.id = ctr.tr_id)
			LEFT JOIN
				courses AS course ON (ctr.course_id = course.id)	
			LEFT JOIN
				contracts AS c ON (c.id = tr.contract_id)
			LEFT JOIN
				assessor_review AS ar ON (ar.tr_id = tr.id)
			LEFT JOIN
				users AS ua ON (ua.username = g.assessor)
			LEFT JOIN
				organisations AS org ON (org.id = tr.employer_id)
			LEFT JOIN
				student_qualifications as sq on sq.tr_id = tr.id and sq.qualification_type = 'NVQ'						
			WHERE 
				tr.start_date >= '2009-08-01' and tr.start_date <= '2010-07-31' $where ORDER BY tr.start_date, tr.surname ASC"	;
		$query = $this->db->query($sql);
		$result = $query->setFetchMode(PDO::FETCH_ASSOC);
		$c = 0;
		while($row = $query->fetch())
		{
			$row['name'] = '<a href="/do.php?_action=read_training_record&amp;id=' . $row['tr_id'] . '&amp;contract=' . $row['contract_id'] . '">' . ucwords(strtolower($row['name'])) . '</a>';
			$tr_id = $row['tr_id'];
			unset($row['tr_id'], $row['contract_id']);

			if ( preg_match('/LSC/i',$row['contract']) ) { $row['contract'] = 'LSC';  }
			elseif ( preg_match('/Scottish/i',$row['contract']) ) {  $row['contract'] = 'Scottish';   } 

			if ( preg_match('/Level 1/i',$row['programme_level']) ) { $row['programme_level'] = 'Level 1';  }
			elseif ( preg_match('/Level 2/i',$row['programme_level']) ) {  $row['programme_level'] = 'Level 2';   } 
			elseif ( preg_match('/Level 3/i',$row['programme_level']) ) {  $row['programme_level'] = 'Level 3';   } 	
			
			// Is it TtG or Apprenticeship?
			$ilr = DAO::getSingleValue($link, "select ilr from ilr where tr_id = $tr_id order by contract_id DESC, submission DESC LIMIT 1");
			$ilr = Ilr2009::loadFromXML($ilr);
			$a15 = (int)$ilr->aims[0]->A15;
			$row['programme_type'] = ($a15==99)?"Adult NVQ":"Apprenticeship";	
						
			$this->data[] = $row;
		}
	}
	
	private function rgb2hex($r, $g=-1, $b=-1)
	{
	    if (is_array($r) && sizeof($r) == 3)
	        list($r, $g, $b) = $r;
	
	    $r = intval($r); $g = intval($g);
	    $b = intval($b);
	
	    $r = dechex($r<0?0:($r>255?255:$r));
	    $g = dechex($g<0?0:($g>255?255:$g));
	    $b = dechex($b<0?0:($b>255?255:$b));
	
	    $color = (strlen($r) < 2?'0':'').$r;
	    $color .= (strlen($g) < 2?'0':'').$g;
	    $color .= (strlen($b) < 2?'0':'').$b;
	    return '#'.$color;
	}
	
	private function colorStrengthen($value, $lower, $upper, $boundaries)
	{
		$red = 255;
		$green = 0;
		$blue = 0;
		
		$colourStrengthen = 50;
		
		$interval = floor(($upper - $lower) / $boundaries);
		//echo $lower . '-' . $upper . '-' . $boundaries . '-' . $interval;
		//die;
		
		for($b = 0; $b < $boundaries; $b++)
		{
			$l = $lower + ($interval * 1);
			$u = $l + $interval;
			
			if($value >= $l AND $value < $u)
			{
				echo 'Found ' . $value . ' in region ' . $l . '-' . $u . '<br />';
				return $this->rgb2hex($red, $green + ($colourStrengthen * $b), $blue * ($colourStrengthen + $b));
			}
		}
		return $this->rgb2hex($red, $green, $blue);
	}
	

}

?>