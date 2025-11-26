<?php

class PeriodLookup
{
	private $lookup = array();
	private $db = null;
	
	function __construct($link)
	{
		$this->db = $link;
	}
	
	function add($year)
	{
		if(!isset($this->lookup["$year"]))
		{
			$periodObj = new FundingPeriod($this->db, $year);
			$this->lookup["$year"] = $periodObj;
		}
	}
	
	function get($year)
	{
		$year2 = $year-1;
		if(isset($this->lookup["$year"]))
			return $this->lookup["$year"];
		else
			return $this->lookup["$year2"];
		
	}
}

?>