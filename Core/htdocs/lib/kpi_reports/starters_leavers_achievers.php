<?php

class KPI_Report_starters_leavers_achievers extends KPI_Report
{
	function __construct($link, $year, $programme_type)
	{
		parent::__construct($link, $year, $programme_type);
	}
	
	
	
	protected function getData_ik()
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
				$where = ' and (g.verifier = '. '"' . $username . '" or tr.verifier="' . $username . '")';
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
			select tr.*, tr.id as trid , c.* from tr inner join contracts as c where c.id = tr.contract_id and c.contract_year = '$this->year' $where
		"; 
		
		$query = $this->db->query($sql);
		$result = $query->setFetchMode(PDO::FETCH_ASSOC);
		$c = 0;
		//pr($sql);

		require_once('./lib/funding/FundingPeriod.php');
		$fp = new FundingPeriod($this->db, $this->year);
		
		// build the data structure
		$periods = array();
		$months = array(1 => 'Aug','Sep','Oct','Nov','Dec','Jan','Feb','Mar','Apr','May','Jun','Jul');
		for($w = 1; $w <= 12; $w++)
		{
			$period = 'W' . str_pad($w, 2, 0, STR_PAD_LEFT);
			//$periods["$period"] = array();
			foreach($months AS $key => $month)
			{
				$periods["$period"]["$month"] = array('starters' => 0, 'leavers' => 0, 'achievers' => 0, 'continuing' => 0,'timely' => 0,'unfunded' => 0);
			}
		}
		
		while($row = $query->fetch())
		{
			$dob = mktime(0, 0, 0, intval(substr($row['dob'], 5, 2)), intval(substr($row['dob'], 8, 2)), intval(substr($row['dob'], 0, 4)));
			$startDate = mktime(0, 0, 0, intval(substr($row['start_date'], 5, 2)), intval(substr($row['start_date'], 8, 2)), intval(substr($row['start_date'], 0, 4)));
			$finishDate = mktime(0, 0, 0, intval(substr($row['closure_date'], 5, 2)), intval(substr($row['closure_date'], 8, 2)), intval(substr($row['closure_date'], 0, 4)));
			$targetDate = mktime(0, 0, 0, intval(substr($row['target_date'], 5, 2)), intval(substr($row['target_date'], 8, 2)), intval(substr($row['target_date'], 0, 4)));
			
			// Age calculation
			//pr('DOB = ' . $row['dob'] . 'Start date = ' . $row['start_date'] . 'Age at start = ' . floor(($startDate - $dob) / (60*60*24*365.2425) ));	
			//pr('SD '.$row['start_date']. 'ED ' . $row['closure_date'] );
			
			$query2 = $this->db->query("select * from student_qualifications where tr_id =" .$row['id'] . " and qualification_type = 'nvq'" );
			//pr("select * from student_qualifications where tr_id =" .$row['trid'] );
			//$result2 = $query2->setFetchMode(PDO::FETCH_ASSOC);
			$row2=$query2->fetch();
			//pr($row2['id']);
			$qual_type = 't2g';
			$level="";

			$finished = true;
			
			if ( $row['closure_date'] == "" ) $finished = false;
			
			
			for($p = 1; $p <= 12; $p++)
			{
				if ($p < 10)  
					$index = "0".$p;
				else
					$index = $p;
					
				// starters
				if( ($startDate >= $fp->getStart($p)) AND ($startDate < $fp->getEnd($p)) )
				{
					$periods['W'.$index][$months["$p"]]['starters'] += 1;
					//echo 'Found a starter in submission ' . $row['submission'] . ' for month ' . $months["$p"] . '<br />';
				}
				
				// continuing
				if( ( ($targetDate > $fp->getStart($p)) AND ($targetDate >= $fp->getEnd($p)) AND ($finished == false) ) )
				{
					if($row['status_code'] == 1)
					{
						$periods['W'.$index][$months["$p"]]['continuing'] += 1;
					}						
				
				}
				
				
				// Achieved and leavers ( Cummulative ) 
				if( ( ($finishDate >= $fp->getStart($p)) AND ($finished == true) ) )
				{
					if( $row['status_code'] == 2)	// Achieved
					{
						$periods['W'.$index][$months["$p"]]['achievers'] += 1;
						
						if ($finishDate < $targetDate) // Timely Achievers
							$periods['W'.$index][$months["$p"]]['timely'] += 1;

					}
					else if( $row['status_code'] == 3) // Leavers
					{
						$periods['W'.$index][$months["$p"]]['leavers'] += 1;
					
					}
				
				}
				
				// Unfunded ( Cummulative ) 
				if( ( ($targetDate > $fp->getStart($p)) AND ($finished == false) ) )
				{
					if($row['status_code'] == 1)	// continuing
					{
						$periods['W'.$index][$months["$p"]]['unfunded'] += 1;
						//pr();
					}
				
				}				
				
			}
				
		}
	
		//pre($periods);
		foreach($periods as $period => $periodInfo)
		{
			$dataBits = array();
			foreach($periodInfo AS $month => $metaData)
			{
				$dataBits[$month . '_starters'] = $metaData['starters'];
				$dataBits[$month . '_leavers'] = $metaData['leavers'];
				$dataBits[$month . '_achievers'] = $metaData['achievers'];
				$dataBits[$month . '_continuing'] = $metaData['continuing'];
				$dataBits[$month . '_unfunded'] = $metaData['unfunded'];
				$dataBits[$month . '_timely'] = $metaData['timely'];
				
			}
			$this->data[] = array_merge(array('submission' => $period), $dataBits);
		}
		//die;
	}

	protected function getData()
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
		
		$sql = "
			SELECT distinct
				tr.id, ilr.submission, ilr.ilr 
			FROM 
				ilr
			INNER JOIN 
				contracts ON ilr.contract_id = contracts.id 
			INNER JOIN
				 tr ON tr.id = ilr.tr_id
			WHERE
				contracts.contract_year = '" . $this->year . "' AND ilr.submission <> 'W13' $where
			ORDER BY tr.id DESC
		"; 
		$query = $this->db->query($sql);
		$result = $query->setFetchMode(PDO::FETCH_ASSOC);
		$c = 0;
		
		require_once('./lib/funding/FundingPeriod.php');
		$fp = new FundingPeriod($this->db, $this->year);
		
		// build the data structure
		$periods = array();
		$months = array(1 => 'Aug','Sep','Oct','Nov','Dec','Jan','Feb','Mar','Apr','May','Jun','Jul');
		for($w = 1; $w <= 12; $w++)
		{
			$period = 'W' . str_pad($w, 2, 0, STR_PAD_LEFT);
			//$periods["$period"] = array();
			foreach($months AS $key => $month)
			{
				$periods["$period"]["$month"] = array('starters' => 0, 'leavers' => 0, 'achievers' => 0, 'continuing' => 0);
			}
		}
		$nl = 0 ;
		while($row = $query->fetch())
		{
			//$xml = new SimpleXMLElement($row['ilr']);
			$xml = XML::loadSimpleXML($row['ilr']);
			$learners["".$xml->learner->L03] = array();
			$aims = $xml->xpath('//subaim|//main');
			$nl += 1;
			foreach($aims AS $key => $data)
			{
				//pr($data->A27);
				$startDate = mktime(0, 0, 0, intval(substr($data->A27, 3, 2)), intval(substr($data->A27, 0, 2)), intval(substr($data->A27, 6, 4)));
				$finishDate = mktime(0, 0, 0, intval(substr($data->A31, 3, 2)), intval(substr($data->A31, 0, 2)), intval(substr($data->A31, 6, 4)));
				$s=$c=$l=$a=0;
				
				for($p = 1; $p <= 12; $p++)
				{
					// starters
					if($startDate >= $fp->getStart($p) AND $startDate < $fp->getEnd($p))
					{
						//$periods[$row['submission']][$months["$p"]]['starters'] += 1;
						//echo 'Found a starter in submission ' . $row['submission'] . ' for month ' . $months["$p"] . '<br />';
						$s=1;
						
					}
					else
					{
					
						$s=0;
					}
					// achievers
					//if($startDate >= $fp->getStart($p) AND $startDate < $fp->getEnd($p))
					//{
						// Contining
						if($data->A34 == 1)
						{
							//$periods[$row['submission']][$months["$p"]]['continuing'] += 1;
							$c = 1;
						}
						else
						{
							$c = 0;
						
						}
						
						if($data->A34 == 2)
						{
							//$periods[$row['submission']][$months["$p"]]['achievers'] += 1;
							$a = 1;
							
							
						}
						else
						{
							$a = 0;
						}
						// leavers
						if($data->A34 == 3)
						{
							//$periods[$row['submission']][$months["$p"]]['leavers'] += 1;
							$l = 1;
						}
						else
						{
						
							$l=0;
						}
					//}
					
					
				}
				
				// achievers
				
				
			}

			for($p = 1; $p <= 12; $p++)
			{
			
				if ( (($row['submission'] == 'W01') && ($p == 1)) or (($row['submission'] == 'W02') && ($p == 2)) or
				(($row['submission'] == 'W03') && ($p == 3)) or 
				(($row['submission'] == 'W04') && ($p == 4)) or 
				(($row['submission'] == 'W05') && ($p == 5)) or 
				(($row['submission'] == 'W06') && ($p == 6)) or 
				(($row['submission'] == 'W07') && ($p == 7)) or
				(($row['submission'] == 'W08') && ($p == 8)) or
				(($row['submission'] == 'W09') && ($p == 9)) or
				(($row['submission'] == 'W10') && ($p == 10)) or
				(($row['submission'] == 'W11') && ($p == 11)) or
				(($row['submission'] == 'W12') && ($p == 12)) 
				
				) 
				{
					$periods[$row['submission']][$months["$p"]]['leavers'] += $l;
					$periods[$row['submission']][$months["$p"]]['achievers'] += $a;
					if ( $a == 1 && ($row['submission'] == 'W03') ) echo $xml->learner->L03 . "," ;
					$periods[$row['submission']][$months["$p"]]['continuing'] += $c;
					$periods[$row['submission']][$months["$p"]]['starters'] += $s;
				}
			}
			
			//$row['name'] = ucwords(strtolower($row['name']));
			//$this->data[] = $row;
		}
	
		//pre($periods);
		foreach($periods as $period => $periodInfo)
		{
			$dataBits = array();
			foreach($periodInfo AS $month => $metaData)
			{
				$dataBits[$month . '_starters'] = $metaData['starters'];
				$dataBits[$month . '_leavers'] = $metaData['leavers'];
				$dataBits[$month . '_achievers'] = $metaData['achievers'];
				$dataBits[$month . '_continuing'] = $metaData['continuing'];
			}
			$this->data[] = array_merge(array('submission' => $period), $dataBits);
		}
		//die;
	}

	public function render($output)
	{
		// 1) calculate columns!
		if(sizeof($this->data) > 0)
		{
			$matrix = new DataMatrix(array(), $this->data, false);
			$matrix->setSpecialHeaders(array(
				'' => array('')
				,'Aug' => array('S','L','A','C')
				,'Sep' => array('S','L','A','C')
				,'Oct' => array('S','L','A','C')
				,'Nov' => array('S','L','A','C')
				,'Dec' => array('S','L','A','C')
				,'Jan' => array('S','L','A','C')
				,'Feb' => array('S','L','A','C')
				,'Mar' => array('S','L','A','C')
				,'Apr' => array('S','L','A','C')
				,'May' => array('S','L','A','C')
				,'Jun' => array('S','L','A','C')
				,'Jul' => array('S','L','A','C')
			));

			return $matrix->to($output);
		}
		else
		{
			return '<p style="font-weight:bold;">No data</p>';
		}
	}	
}

?>