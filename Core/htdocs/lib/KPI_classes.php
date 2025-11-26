<?php

function clean_lar($string)
{
	return str_replace('/', '', $string);
}

function pad_year($year)
{
	return str_pad($year, 2, '0', STR_PAD_LEFT);
}

function get_end_year($string)
{
	$bits = explode('/', $string);
	//echo $string . '<br />';
	$day = $bits[0];
	$month = $bits[1];
	$year = substr($bits[2], 2, 4);
	
	if(intval($month) < 8)
	{
		return pad_year($year-1) . '/' . pad_year($year);
	}
	return pad_year($year) . '/' . pad_year($year+1);
}

// ###################################################################
// #############      QUALIFICATIONS COLLECTION     ##################
// ###################################################################
class QualificationsLookupCollection
{
	private $qualifications = null;
	
	function __construct(){}
	function add($LAR, $data)
	{
		$this->qualifications["$LAR"] = $data;
	}
	
	function remove($LAR)
	{
		unset($this->qualifications["$LAR"]);
	}
	
	function size()
	{
		return sizeof($this->qualifications);
	}
	
	function get($LAR)
	{
		if(isset($this->qualifications["$LAR"]))
		{
			return $this->qualifications["$LAR"];
		}
		else
		{
			throw new Exception('Bad dataset: an LAR (' . $LAR . ') was not found in the qualifications lookup table');
		}
	}
	
	function populate($link, $LARS)
	{

		$qualificationsLookup = $link->query("
			SELECT 
			lad201112.learning_aim.LEARNING_AIM_REF AS id
			, learning_aim.LEARNING_AIM_TITLE AS title
			,CONCAT(SSA_TIER1_CODES.SSA_TIER1_CODE, ' ',SSA_TIER1_CODES.SSA_TIER1_DESC) AS mainarea
			, CONCAT(SSA_TIER2_CODES.SSA_TIER2_CODE, ' ',SSA_TIER2_CODES.SSA_TIER2_DESC) AS subarea
			FROM lad201112.learning_aim 
			LEFT JOIN lad201112.all_annual_values ON all_annual_values.Learning_Aim_Ref = learning_aim.LEARNING_AIM_REF
			LEFT JOIN lad201112.SSA_TIER1_CODES ON lad201112.SSA_TIER1_CODES.SSA_TIER1_CODE = all_annual_values.SSA_Tier1_Code
			LEFT JOIN lad201112.SSA_TIER2_CODES ON lad201112.SSA_TIER2_CODES.SSA_TIER2_CODE = all_annual_values.SSA_Tier2_Code
			WHERE  REPLACE(lad201112.learning_aim.LEARNING_AIM_REF ,'/' , '') IN ('" . implode("','", $LARS)  . "')
		");
		while($row = $qualificationsLookup->fetch())
		{
			$this->add(str_replace('/','',$row['id']), $row);
		}		
	}
}

// ###################################################################
// #################      REPORT COLLECTION     ######################
// ###################################################################

interface ReportCollection
{
	function filter($filter);
	function totalSize();
	function getData();
}

class AreaReportCollection implements ReportCollection
{
	private $areas = array();
	
	
	function add($area, $xmlData)
	{
		$this->areas["$area"][] = $xmlData;
	}
	
	public function getUniqueAreas()
	{
		return array_unique(array_keys($this->areas));
	}
	
	function populate(PDO $link, $learners, $qualifications)
	{
		foreach($learners AS $LRN => $nodes)
		{
			foreach($nodes AS $key => $qualificationInfo)
			{
				if(empty($qualificationInfo['qualification']->A09))
				{
					throw new Exception('Bad ILR record. No value found for one of the A09 fields for ' . $qualificationInfo['learnerInfo']->L10 . ' ' . $qualificationInfo['learnerInfo']->L09);
				}
				$data = $qualifications->get($qualificationInfo['qualification']->A09);
				$this->add($data['mainarea'], $qualificationInfo['learnerInfo']);
			}
		}	
	}
	
	public function filter($filter)
	{
		$thisClass = __CLASS__;
		$new = new $thisClass;
		foreach($this->areas AS $area=> $nodes)
		{
			foreach($nodes AS $key => $xmlData)
			{
				if($filter->execute($xmlData))
				{
					$new->add($area, $xmlData);
				}
			}
		}
		return $new;
	}
	
	function size($area)
	{
		if(isset($this->areas["$area"]))
		{
			return sizeof($this->areas["$area"]);
		}
		return 0;
	}
	
	function totalSize()
	{
		$total = 0;
		foreach($this->areas AS $key => $val)
		{
			$total += sizeof($val);
		}
		return $total;
	}
	
	function getData()
	{
		ksort($this->areas, SORT_STRING);
		return $this->areas;
	}
}

class EarlyLeaversReportCollection implements ReportCollection
{
	private $qualifications;
	
	function add($LAR, $endYear, $xmlData)
	{
		$this->qualifications["$LAR"]["$endYear"][] = $xmlData;
	}	
	
	public function getUniqueLARs()
	{
		return array_unique(array_keys($this->qualifications));
	}
	
	public function populate($link, $providerID, $contract_id)
	{
		$this->qualifications = array();

//Dean		$result = $link->query("
//			SELECT 
//				* 
//			FROM 
//				ilr
//			WHERE 
//				L01 = '" . $providerID . "' AND submission = '" . addslashes((string)$submission) . "' and contract_id ='14'
//		");		

		// Khshnood
		$result = $link->query("
			SELECT 
				* 
			FROM 
				ilr
			WHERE 
				tr_id in (select id from tr where status_code = 1) and submission = (select max(submission) from ilr where contract_id = '$this->submission') and contract_id = '$this->submission'
		");	
		
		
		$total = 0;
		
		while($row = $result->fetch())
		{
			$total++;
			//$xml = new SimpleXMLElement($row['ilr']);
			$xml = XML::loadSimpleXML($row['ilr']);
			//$xresult = $xml->xpath('//subaim|//main');
			$xresult = $xml->xpath('//main');
			if(!empty($xresult))
			{
				foreach($xresult AS $key => $node)
				{
					if(!empty($node) && ($node->A35=='9' ))
					{
						$LAR = clean_lar($node->A09);
						if(!empty($LAR))
						{
							// get planned end year
							$this->add("".$LAR, get_end_year($node->A28), $node);
						}
					}
				}
			}
		}
		return $total;
	}
	
	public function filter($filter)
	{
		$thisClass = __CLASS__;
		$new = new $thisClass;
		foreach($this->qualifications AS $LAR => $years)
		{
			foreach($years AS $year => $learners)
			{
				foreach($learners AS $key => $xmlData)
				{
					if($filter->execute($xmlData))
					{
						$new->add($LAR, $year, $xmlData);
					}
				}
			}
		}
		return $new;
	}
	
	function size($LAR, $year)
	{
		if(isset($this->qualifications["$LAR"]["$year"]))
		{
			return sizeof($this->qualifications["$LAR"]["$year"]);
		}
		return 0;
	}
	
	function totalSize()
	{
		$total = 0;
		foreach($this->qualifications AS $key => $val)
		{
			$total += sizeof($val);
		}
		return $total;
	}
	
	function getData()
	{
		return $this->qualifications;
	}
}

class LearnerQualificationCollection
{
	private $link;
	private $size = 0;
	private $LARS;
	private $submission;
	
	function __construct($link, $providerID, $submission)
	{
		$this->link =& $link;
		$this->providerID = $providerID;
		$this->submission = $submission;
	}
	
	function build()
	{

		// Dean
/*		$result = $this->link->query("
			SELECT 
				* 
			FROM 
				ilr
			WHERE 
				L01 = '" . $this->providerID . "' and contract_id ='14'
		");	
*/
		// Khushnood
		$sql = "
			SELECT 
				* 
			FROM 
				ilr
			WHERE 
				submission = (select max(submission) from ilr where contract_id = '$this->submission')  and contract_id = '$this->submission'
		";	
		$result = $this->link->query($sql);
		$size = 0;
		$learners = $this->LARS = array();
		$con = Contract::loadFromDatabase($this->link, $this->submission);
		$start_date = new Date($con->start_date);
		while($row = $result->fetch())
		{
			$size++;
			/*try
			{
				$xml = @new SimpleXMLElement($row['ilr']);
			}
			catch(Exception $e)
			{
				throw new Exception($row['tr_id'] . $row['submission'] . $row['contract_id']);
			}*/
			$xml = XML::loadSimpleXML($row['ilr']);
			$learners["".$xml->learner->L03] = array();
			//$xresult = $xml->xpath('//subaim|//main');
			$xresult = $xml->xpath('//main');
			if(!empty($xresult))
			{
				foreach($xresult AS $key => $node)
				{
					if(!empty($node))
					{
						$lsd = new Date($node->A27);
						if($lsd->getDate()>=$start_date->getDate())
						{
							$this->LARS[] = $node->A09;
							$learners["".$xml->learner->L03][] = array('qualification' => $node, 'learnerInfo' => $xml->learner);
						}
					}
				}
			}
		}
		$this->size = $size;
		//pre($learners);
		return $learners;

	}
	
	function getLars()
	{
		return $this->LARS;
	}
	
	function size()
	{
		return $this->size;
	}
}

// ###################################################################
// #################            FILTERS           ####################
// ###################################################################

interface ReportCollectionFilter
{
	function execute($xmlData);
}

class DateDifferenceWeeksFilter
{
	function __construct($startDate, $endDate, $difference) 
	{
		$this->startDate = $startDate;
		$this->endDate = $endDate;
		$this->difference = $difference;
	}
	
	function execute($xmlData)
	{
		$startDate = $xmlData->{$this->startDate};
		$startDate = mktime(0, 0, 0, intval(substr($startDate, 2, 4)), intval(substr($startDate, 0, 2)), intval(substr($startDate, 4, 8)));
		
		
		$endDate = $xmlData->{$this->endDate};
		
		// check for unknown actual end date
		if($endDate == '00000000') 
		{
			return false;
		}
		$endDate = mktime(0, 0, 0, intval(substr($endDate, 2, 4)), intval(substr($endDate, 0, 2)), intval(substr($endDate, 4, 8)));
		
		// fix for courses that are only 1 day :(
		if($startDate == $endDate)
		{
			return false;
		}
		
		if(($endDate - $startDate) < 3628800) // 3628800 = 6 weeks in seconds
		{
			//echo 'Left 6 weeks early with A34 = ' . $xmlData->A34 . ' Start = ' . date('d/m/Y', $startDate) . "($startDate)"  . ' | End = ' . date('d/m/Y', $endDate) . "($endDate)" . ' | Difference = ' . ($endDate - $startDate) . ' | ' . (60*60*24*7*6) . '<br />';
			//die;
			return true;
		}
		return false;
	}
}

class ComparisonFilter implements ReportCollectionFilter
{
	function __construct($field, $value, $checkTrue = true)
	{
		$this->field = $field;
		$this->value = $value;
		$this->checkTrue = $checkTrue;
	}
	
	function execute($xmlData)
	{
		$data = $xmlData->{$this->field};
		if($this->checkTrue)
		{
			if(is_array($this->value))
			{
				return in_array($data, $this->value);
			}
			else
			{
				if($data == $this->value)
				{
					return true;
				}
				return false;
			}
		}
		else
		{
			if(is_array($this->value))
			{
				return !in_array($data, $this->value);
			}
			else
			{
				if($data != $this->value)
				{
					return true;
				}
				return false;
			}
		}
	}
}

class ChainFilter implements ReportCollectionFilter
{
	var $filters;
	
	function __construct($filters)
	{
		$this->filters = $filters;
	}
	
	function execute($xmlData)
	{
		foreach($this->filters AS $key => $filter)
		{
			if(!$filter->execute($xmlData))
			{
				return false;
			}
		}
		return true;
	}
}

?>