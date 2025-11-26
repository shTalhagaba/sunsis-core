<?php

class KPI_Report_temporarily_withdrawn extends KPI_Report
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
            elseif($_SESSION['user']->type==20)
            {
                $username = $_SESSION['user']->username;
                $where = ' and (tr.programme="' . $username . '")';
            }

		$where .= " and course.programme_type= $this->programme_type";
			

		$this->createTempTable($link);
		DAO::execute($link, "truncate ilr2");

		$sql = "select distinct tr_id from ilr";
		$query = $this->db->query($sql);
		$result = $query->setFetchMode(PDO::FETCH_ASSOC);
		while($row = $query->fetch())
		{
			$tr_id = $row['tr_id'];
			$sql = <<<HEREDOC
insert into ilr2
(L01,L03,A09,ilr,submission,contract_type,tr_id,is_complete,is_valid,is_approved,is_active,contract_id,contract_year)
select
	L01,
	L03,
	A09,
	ilr,
	submission,
	ilr.contract_type,
	tr_id,
	is_complete,
	is_valid,
	is_approved,
	is_active,
	contract_id,
	contracts.contract_year
from
	ilr
inner join
	contracts on contracts.id = ilr.contract_id and contract_year>=2011
where
	tr_id = $tr_id
order by
	contract_year desc, submission desc limit 0,1
HEREDOC;
			DAO::execute($link, $sql);
		}

		DAO::execute($link, "update ilr2 set comp_status = extractvalue(ilr,'/ilr/main/A34'), fwork_code = extractvalue(ilr,'/ilr/main/A26'), prog_type=extractvalue(ilr,'/ilr/main/A15') where contract_year<2012");
		DAO::execute($link, "update ilr2 set comp_status = LEFT(extractvalue(ilr,'/Learner/LearningDelivery/CompStatus'),1), fwork_code = left(extractvalue(ilr,'/Learner/LearningDelivery/FworkCode'),3), prog_type=left(extractvalue(ilr,'/Learner/LearningDelivery/ProgType'),1) where contract_year>=2012");
		//DAO::execute($link, "update ilr2 set fwork_code = 443 where fwork_code = 112");
		//DAO::execute($link, "update ilr2 set fwork_code = 260 where fwork_code = 487");
		//DAO::execute($link, "update ilr2 set fwork_code = 263 where fwork_code = 488");
		DAO::execute($link, "delete from ilr2 where fwork_code =''  or prog_type='' or prog_type = '99' or comp_status=''");
		DAO::execute($link, "create temporary table ilr3 select * from ilr2");

		$sql = "SELECT * FROM ilr2 WHERE comp_status='6' AND CONCAT(L03,prog_type,fwork_code) NOT IN (SELECT CONCAT(L03,prog_type,fwork_code) FROM ilr3 WHERE comp_status!='6');";
//		HTML::renderQuery($link,"select * From ilr2");
//		HTML::renderQuery($link,$sql);
//		die("sss");
		$query = $this->db->query($sql);
		$result = $query->setFetchMode(PDO::FETCH_ASSOC);
		$learnersout = array();
		while($row = $query->fetch())
		{
			$tr_id = $row['tr_id'];	
			if($row['contract_year']<2012)
			{
				$ilr = Ilr2011::loadFromXML($row['ilr']);
				$a34 = (int)$ilr->aims[0]->A34;
				$a15 = (int)$ilr->aims[0]->A15;
				$a26 = (int)$ilr->aims[0]->A26;

				if($a34==6)
				{
					$rec['Trainee_ID'] = $ilr->learnerinformation->L03;
					$rec['Firstname'] = $ilr->learnerinformation->L10;
					$rec['Surname'] = $ilr->learnerinformation->L09;
					$rec['Start_Date'] = Date::toShort($ilr->programmeaim->A27);
					$rec['Planned_End_Date'] = Date::toShort($ilr->programmeaim->A28);
					$this->data[] = $rec;
				}
			}
			else
			{
                $ilr = Ilr2012::loadFromXML($row['ilr']);
                //if($ilr->LearningDelivery->CompStatus==6)
                //{
                    $rec['Trainee_ID'] = $row['L03'];
                    $rec['Firstname'] = $ilr->GivenNames;
                    $rec['Surname'] = $ilr->FamilyName;
                    $rec['Start_Date'] = Date::toShort($ilr->LearningDelivery->LearnStartDate);
                    $rec['Planned_End_Date'] = Date::toShort($ilr->LearningDelivery->LearnPlanEndDate);
                    $this->data[] = $rec;
                //}
			}
		}
	}

	public function createTempTable(PDO $link)
	{
		$sql = <<<HEREDOC
CREATE TEMPORARY TABLE `ilr2` (
  `L01` varchar(6) DEFAULT NULL,
  `L03` varchar(12) DEFAULT NULL,
  `A09` varchar(8) DEFAULT NULL,
  `ilr` text DEFAULT NULL,
  `submission` varchar(3) DEFAULT NULL,
  `contract_type` varchar(100) DEFAULT NULL,
  `tr_id` int(11) DEFAULT NULL,
  `is_complete` tinyint(1) DEFAULT NULL,
  `is_valid` tinyint(1) DEFAULT NULL,
  `is_approved` tinyint(1) DEFAULT NULL,
  `is_active` tinyint(1) DEFAULT NULL,
  `contract_id` int(11) DEFAULT NULL,
  `comp_status` varchar(1) DEFAULT NULL,
  `prog_type` varchar(2) DEFAULT NULL,
  `fwork_code` varchar(3) DEFAULT NULL,
  `contract_year` varchar(4) DEFAULT NULL,
  PRIMARY KEY (`submission`,`tr_id`,`contract_id`),
  KEY `tr_id` (`tr_id`,`submission`),
  KEY `contract_id` (`contract_id`),
  KEY `comp_status` (`comp_status`)
)
HEREDOC;
		DAO::execute($link, $sql);
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
