<?php

class KPI_Report_new_starts extends KPI_Report
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
			SELECT 
				ilr.submission, ilr.ilr 
			FROM 
				ilr
			INNER JOIN 
				contracts ON ilr.contract_id = contracts.id 
			WHERE
				contracts.contract_year = '" . $this->year . "' AND ilr.submission <> 'W13' $where
			ORDER BY contracts.contract_year, ilr.submission ASC
		";
		$query = $this->db->query($sql);
		$result = $query->setFetchMode(PDO::FETCH_ASSOC);
		$c = 0;
		
		require_once('./lib/funding/FundingPeriod.php');
		$fp = new FundingPeriod($this->db, $this->year);
		
		// build the data structure
		$periods = $data = $a09 = array();
		
		while($row = $query->fetch())
		{
			//$xml = new SimpleXMLElement($row['ilr']);
			$xml = XML::loadSimpleXML($row['ilr']);
			$learners["".$xml->learner->L03] = array();
			$aims = $xml->xpath('//subaim|//main');
			//pr($xml->learner->L11);
			pr('L03 ='.$xml->learner->L03);
			foreach($aims AS $key => $ilrdata)
			{
				//pr($data->A27);
				$dob = mktime(0, 0, 0, intval(substr($xml->learner->L11, 3, 2)), intval(substr($xml->learner->L11, 0, 2)), intval(substr($xml->learner->L11, 6, 4)));
				$startDate = mktime(0, 0, 0, intval(substr($ilrdata->A27, 3, 2)), intval(substr($ilrdata->A27, 0, 2)), intval(substr($ilrdata->A27, 6, 4)));
				$finishDate = mktime(0, 0, 0, intval(substr($ilrdata->A31, 3, 2)), intval(substr($ilrdata->A31, 0, 2)), intval(substr($ilrdata->A31, 6, 4)));
				pr('DOB = ' . $xml->learner->L11 . 'Start date = ' . $ilrdata->A27 . 'Age at start = ' . floor(($startDate - $dob) / (60*60*24*365.2425) ));	

					$a09[] = (string)$ilrdata->A09;
					$data[] = array(
						'A09' => (string)$ilrdata->A09
						,'start_date' => $startDate
						,'learner_name' => $xml->learner->L09 . ', ' . $xml->learner->L10
						,'dob' => (string)$xml->learner->L11
						// you will want to pull out more fields from the ILR here like qual name etc.
					); 

				
			}
			// Remember at this point we have looped through all aims of a learner. you may need to put
			// some logic in this block (specifically the loop above this) which checks all aims to determine
			// if it's a start.
	
			//$row['name'] = ucwords(strtolower($row['name']));
			//$this->data[] = $row;
		}
		
		
		
		$a09s = implode(',', $a09);
		$a09lookup = array();
		// run query here - pseudocode
		/* table names are obviously different
		 $st = $this->db->query("
		 	SELECT nsql.level_number FROM learner_aims
		 	LEFT JOIN ON nvq_levels as nvql ON (learning_aim_ref)
		 	WHERE learner_aims.learner_aim_ref IN('$a09s')
		 ");
		 while($row = $st->fetch())
		 {
		 	$a09lookup[$row['learner_aim_ref']] = $row['level_number'];
		 }
		 
		 // now we've got our lookup array for A09 level numbers we can just iterate through the data
		    rejigging the data
		    
		 foreach($data AS $key => $d)
		 {
		 	$this->data["$key"] = $d;
		 	$this->data["$key"]['level'] = $a09lookup[$d['A09']];
		 	$this->data["$key"]['age_group'] = calculate_age_group($d['dob']);
		 	unset($this->data["$key']['dob']);
		 }
		 
		 at this point the $this->data array is built so nothing else needs to be done
		 as the report is generated from this array
		 
		 */
	
		pre($data); // change this to pre($this->data); when you're ready to test
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