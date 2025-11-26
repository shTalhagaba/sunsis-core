<?php

abstract class FundingCalculator
{
	protected $db;
	protected $contracts;
	
	function __construct($db, $contracts)
	{
		$this->db = $db;
		$this->contracts = $contracts;
	}
	
	abstract function getData($link, $hook_fields = '', $hook_joins = '', $hook_where = '');
	
	public function getTargetPeriods()
	{
		$st = $this->db->query("
			SELECT 
				sq.auto_id
				, GROUP_CONCAT(l.contract_year,'-',l.submission ORDER BY l.contract_year,l.submission) AS learner_periods
			FROM 
				student_qualifications AS sq 
			INNER JOIN
				ilr ON (ilr.tr_id = sq.tr_id AND ilr.contract_id in(" . $this->contracts . ") and ilr.submission = (SELECT MAX(submission) FROM ilr WHERE tr_id = sq.tr_id AND contract_id in (" . $this->contracts . ")))
			LEFT JOIN 
				central.lookup_submission_dates AS l 
				ON 
					(l.census_end_date >= sq.start_date AND l.census_start_date <= sq.end_date AND sq.end_date >= l.census_end_date AND l.submission <> 'W13') OR (sq.start_date > l.census_start_date AND sq.end_date < l.census_end_date)
			GROUP BY sq.auto_id
		");

		$tperiods = array();
		while($row = $st->fetch())
		{
			$tperiods[$row['auto_id']] = $row['learner_periods'];
		}
		return $tperiods;		
	}
	
	public function getUnfundedPeriods()
	{
		$st = $this->db->query("
			SELECT 
				sq.auto_id
				, GROUP_CONCAT(l.contract_year,'-',l.submission ORDER BY l.contract_year,l.submission) AS learner_periods
			FROM 
				student_qualifications AS sq 
			INNER JOIN
				ilr ON (ilr.tr_id = sq.tr_id AND ilr.contract_id in(" . $this->contracts . ") and ilr.submission = (SELECT MAX(submission) FROM ilr WHERE tr_id = sq.tr_id AND contract_id in (" . $this->contracts . ")))
			LEFT JOIN 
				central.lookup_submission_dates AS l 
				ON 
					(l.census_end_date >= sq.start_date AND l.census_start_date <= (IF(sq.end_date>CURDATE(),sq.end_date,CURDATE())) AND l.submission <> 'W13') OR (sq.start_date > l.census_start_date AND (IF(sq.end_date>CURDATE(),sq.end_date,CURDATE())) < l.census_end_date)
			GROUP BY sq.auto_id
		");

		$uperiods = array();
		while($row = $st->fetch())
		{
			$uperiods[$row['auto_id']] = $row['learner_periods'];
		}
		return $uperiods;		
	}
	
	public function getOnProgramPeriods()
	{
		$st = $this->db->query("
			SELECT 
				sq.auto_id
				, GROUP_CONCAT(l.contract_year,'-',l.submission ORDER BY l.contract_year,l.submission) AS learner_periods
			FROM 
				student_qualifications AS sq 
			INNER JOIN
				ilr ON (ilr.tr_id = sq.tr_id AND ilr.contract_id in (" . $this->contracts . ") and ilr.submission = (SELECT MAX(submission) FROM ilr WHERE tr_id = sq.tr_id AND contract_id in (" . $this->contracts . ")))
			LEFT JOIN
				contracts AS c ON (ilr.contract_id = c.id)				
			LEFT JOIN 
				central.lookup_submission_dates AS l 
				ON 
					IF(sq.actual_end_date IS NOT NULL, (l.census_end_date >= sq.start_date AND l.census_start_date <= sq.end_date AND sq.end_date >= l.census_end_date AND l.census_end_date <= IF(sq.actual_end_date<sq.end_date,sq.actual_end_date,sq.end_date) AND l.submission!='W13' AND l.contract_type=2), (l.census_end_date >= sq.start_date AND l.census_end_date <= sq.`end_date`  AND l.submission!='W13' AND l.contract_type=2))
					#(l.census_end_date >= sq.start_date AND l.census_start_date <= (IF(sq.actual_end_date IS NOT NULL, sq.end_date,sq.end_date)) AND (IF(sq.actual_end_date IS NOT NULL, sq.end_date,sq.end_date)) >= l.census_end_date AND l.submission <> 'W13') OR (sq.start_date > l.census_start_date AND sq.end_date < l.census_end_date )
			GROUP BY sq.auto_id
		");

		
		// Khushnood I have changed in the above query actual end date as target end date
		$opperiods = array();
		while($row = $st->fetch())
		{
			$opperiods[$row['auto_id']] = $row['learner_periods'];
			if(DB_NAME=='am_rttg' && $row['learner_periods']=='')
			{
				$start_date = DAO::getSingleValue($this->db,"select start_date from student_qualifications where auto_id = {$row['auto_id']}");
				$opperiods[$row['auto_id']] = DAO::getSingleValue($this->db, "select CONCAT(contract_year,'-',submission) from central.lookup_submission_dates where '$start_date' >= central.lookup_submission_dates.census_start_date AND '$start_date' <= central.lookup_submission_dates.census_end_date and submission!='W13'");
			}
		}
		return $opperiods;		
	}
	
	public function getAchieverPeriods()
	{
		$st = $this->db->query("
			SELECT 
				sq.auto_id
				, GROUP_CONCAT(l.contract_year,'-',l.submission ORDER BY l.contract_year,l.submission) AS learner_periods
			FROM 
				student_qualifications AS sq 
			INNER JOIN
				ilr ON (ilr.tr_id = sq.tr_id AND ilr.contract_id in(" . $this->contracts . ") and ilr.submission = (SELECT MAX(submission) FROM ilr WHERE tr_id = sq.tr_id AND contract_id in(" . $this->contracts . ")))
			LEFT JOIN 
				central.lookup_submission_dates AS l 
				ON 
					(sq.start_date <= l.census_end_date AND IF(sq.achievement_date is not null, sq.achievement_date,sq.actual_end_date) >= l.census_start_date AND l.submission <> 'W13' and l.contract_type = 2)
			GROUP BY sq.auto_id
		");
		$aperiods = array();
		while($row = $st->fetch())
		{
			$aperiods[$row['auto_id']] = $row['learner_periods'];
		}
		return $aperiods;		
	}

	public function getMarkedPeriods()
	{
		$st = $this->db->query("
			SELECT 
				sq.auto_id
				, GROUP_CONCAT(l.contract_year,'-',l.submission ORDER BY l.contract_year,l.submission) AS learner_periods
			FROM 
				student_qualifications AS sq 
			LEFT JOIN
				tr on tr.id = sq.tr_id
			INNER JOIN
				ilr ON (ilr.tr_id = sq.tr_id AND ilr.contract_id in (" . $this->contracts . ") and ilr.submission = (SELECT MAX(submission) FROM ilr WHERE tr_id = sq.tr_id AND contract_id in (" . $this->contracts . ")))
			LEFT JOIN 
				central.lookup_submission_dates AS l 
				ON 
					(l.census_end_date >= tr.start_date AND l.census_start_date <= tr.marked_date AND l.submission <> 'W13')
			GROUP BY sq.auto_id
		");

		$aperiods = array();
		while($row = $st->fetch())
		{
			$aperiods[$row['auto_id']] = $row['learner_periods'];
		}
		return $aperiods;		
	}
	
	public function getContractPeriods()
	{
		$st = $this->db->query("
			SELECT 
				sq.auto_id
				, GROUP_CONCAT(l.contract_year,'-',l.submission ORDER BY l.contract_year,l.submission) AS learner_periods
			FROM 
				student_qualifications AS sq 
			INNER JOIN
				ilr ON (ilr.tr_id = sq.tr_id AND ilr.contract_id in (" . $this->contracts . ") and ilr.submission = (SELECT MAX(submission) FROM ilr WHERE tr_id = sq.tr_id AND contract_id in  (" . $this->contracts . ")))
			LEFT JOIN
				contracts AS c ON (ilr.contract_id = c.id)
			LEFT JOIN 
				central.lookup_submission_dates AS l 
				ON 
					(l.census_end_date >= sq.start_date AND l.census_start_date <= sq.end_date AND sq.end_date >= l.census_end_date AND l.submission <> 'W13' AND l.contract_year = c.contract_year) OR (sq.start_date > l.census_start_date AND sq.end_date < l.census_end_date AND l.contract_year = c.contract_year)
			GROUP BY sq.auto_id		
		");	
		$cperiods = array();
		while($row = $st->fetch())
		{
			$cperiods[$row['auto_id']] = $row['learner_periods'];
		}
		return $cperiods;		
	}
	
	public function getTrIDs()
	{
		$st = $this->db->query("SELECT distinct tr_id FROM ilr WHERE contract_id in (" . $this->contracts . ")");
		$trids = array();
		while($row = $st->fetch())
		{
			$trids[] = $row['tr_id'];
		}
		if(sizeof($trids) == 0)		
		{
			throw new UserErrorException('There are no training records for this contract. Therefore, funding cannot be calculated if there are no learners');
		}
		return implode(',', $trids);		
	}
}

?>