<?php

class KPI_Report_continuing_learners extends KPI_Report
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
                $id = $_SESSION['user']->id;
				$where = ' and (g.assessor = '. '"' . $username . '" or tr.assessor="' . $id . '")';
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
				$assessors = DAO::getSingleValue($link, "SELECT GROUP_CONCAT(\"\'\",id,\"\'\") FROM users WHERE supervisor in ($supervisors);");
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

        if($this->programme_type!=0)
		$where .= " and course.programme_type= $this->programme_type";

		$year = $this->year;

		$sql = "
			SELECT distinct
			    l03 AS Trainee_ID
				#, DATE_FORMAT(tr.dob,'%d/%m/%Y') AS date_of_birth
				#, (CASE tr.gender WHEN 'M' THEN 'Male' WHEN 'F' THEN 'Female' ELSE 'Undefined' END) AS gender
				, g.title as group_title
				, org.employer_code as Dealer_code
				, org.retailer_code as region
				, org.district as area_code
				, org.legal_name as employer
				, provider.legal_name as provider
				#,CONCAT(tr.surname,', ', tr.firstnames) AS `name`
				,tr.surname as surname
				,tr.firstnames as firstname
				, DATE_FORMAT(NOW(), '%Y') - DATE_FORMAT(tr.dob, '%Y') - (DATE_FORMAT(NOW(), '00-%m-%d') < DATE_FORMAT(tr.dob, '00-%m-%d')) AS age
				, DATE_FORMAT(tr.target_date, '%Y') - DATE_FORMAT(tr.dob, '%Y') - (DATE_FORMAT(tr.target_date, '00-%m-%d') < DATE_FORMAT(tr.dob, '00-%m-%d')) AS age_at_completion
				#, fq.internaltitle as programme_level
                , concat(acs.firstnames,' ',acs.surname) as apprentice_coordinator
				, IF(frameworks.framework_type=99,'ER Other',IF(frameworks.framework_type is null, 'ER Other','Apprenticeship')) as programme_type
				, c.title as contract
				#, fq.internaltitle as main_aim
				#, fq.id as aim_reference
				, DATE_FORMAT(tr.start_date,'%d/%m/%Y') as start_date
				, DATE_FORMAT(tr.target_date,'%d/%m/%Y') AS target_completion_date
				#, COALESCE(CONCAT(ua.surname, ', ', ua.firstnames),'n/a') AS assessor
				, IF(ua2.surname is not null,ua2.surname,ua.surname) AS assessor_surname
				, brands.title as manufacturer
				#, course.title as course_title
				# fields that must be unset in the loop ;)
				, tr.contract_id
				, tr.id as tr_id
				, tr.home_postcode
			FROM 
				tr
			LEFT JOIN
				group_members AS gm ON (gm.tr_id = tr.id)
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
				users AS ua2 ON (ua2.id = tr.assessor)
			LEFT JOIN
				organisations AS org ON (org.id = tr.employer_id)
			LEFT JOIN
				users as acs on acs.username = tr.programme
			LEFT JOIN
				organisations AS provider ON (provider.id = tr.provider_id)
#			LEFT JOIN
#				framework_qualifications AS fq ON fq.framework_id = course.framework_id AND fq.main_aim = 1
			LEFT JOIN
				frameworks ON frameworks.id = course.`framework_id`
#			LEFT JOIN
#				student_qualifications AS sq ON sq.tr_id = tr.id AND sq.id = fq.id
			LEFT JOIN 
				brands on brands.id = org.manufacturer					
			WHERE 
				status_code = 1 
				AND tr.contract_id in (select id from contracts where contract_year =  '" . $year .  "' ) $where
		" . "ORDER BY tr.surname ASC"	;

		$query = $this->db->query($sql);
		$result = $query->setFetchMode(PDO::FETCH_ASSOC);
		$c = 0;
		while($row = $query->fetch())
		{
			$tr_id = $row['tr_id'];
			
			$row['Trainee_ID'] = '<a href="/do.php?_action=read_training_record&amp;id=' . $row['tr_id'] . '&amp;contract=' . $row['contract_id'] . '">' . ucwords(strtolower($row['Trainee_ID'])) . '</a>';
			$tr_id = $row['tr_id'];
			unset($row['tr_id'], $row['contract_id']);
			//$row['days_left_early'] = $row['days_left_early'];
			if ( preg_match('/LSC/i',$row['contract']) ) { $row['contract'] = 'LSC';  }
			elseif ( preg_match('/Scottish/i',$row['contract']) ) {  $row['contract'] = 'Scottish';   } 
			
			//if ( preg_match('/Level 1/i',$row['programme_level']) ) { $row['programme_level'] = 'Level 1';  }
			//elseif ( preg_match('/Level 2/i',$row['programme_level']) ) {  $row['programme_level'] = 'Level 2';   }
			//elseif ( preg_match('/Level 3/i',$row['programme_level']) ) {  $row['programme_level'] = 'Level 3';   }

            $contract_year = DAO::getSingleValue($link, "select contract_year from contracts inner join tr on tr.contract_id = contracts.id where tr.id = '$tr_id'");
            if($contract_year<2012)
    			$additional = DAO::getResultset($link, "select extractvalue(ilr,'/ilr/learner/L13'), extractvalue(ilr,'/ilr/learner/L15'), extractvalue(ilr,'/ilr/learner/L12'), extractvalue(ilr,'/ilr/learner/L29'), extractvalue(ilr,'/ilr/learner/L17') from ilr left join contracts on contracts.id = ilr.contract_id where tr_id = $tr_id order by contract_year desc, submission desc");
            else
                $additional = DAO::getResultset($link, "select extractvalue(ilr,'/Learner/Sex'), extractvalue(ilr,'/Learner/LLDDandHealthProblem[LLDDType=\'DS\']/LLDDCode'), extractvalue(ilr,'/Learner/Ethnicity'), extractvalue(ilr,'/Learner/LearningDelivery/LearningDeliveryFAM[LearnDelFAMType=\'ALN\']/LearnDelFAMCode') from ilr left join contracts on contracts.id = ilr.contract_id where tr_id = $tr_id order by contract_year desc, submission desc");
			$row['gender'] = @$additional[0][0];
			$row['disability'] = @$additional[0][1];
			$row['ethnicity'] = @$additional[0][2];
			$row['als'] = @$additional[0][3];

			// Is it TtG or Apprenticeship?
		//	$ilr = DAO::getSingleValue($link, "select ilr from ilr where tr_id = $tr_id order by contract_id DESC, submission DESC LIMIT 1");
		//	$ilr = Ilr2011::loadFromXML($ilr);
		//	$a15 = (int)$ilr->aims[0]->A15;
		//	$row['programme_type'] = ($a15==99)?"Adult NVQ":"Apprenticeship";	
		//	$row['main_aim'] = $ilr->aims[0]->A09;						
			
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