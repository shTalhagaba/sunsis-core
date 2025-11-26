<?php

abstract class KPI_Report 
{
	protected $data = array();
	protected $db = null;
	protected $columns = array();
	protected $year = 0;
	protected $programme_type = 2;
	
	function __construct($link, $year, $programme_type)
	{
		$this->db = $link;
		$this->year = $year;
		$this->programme_type = $programme_type;
		
		// get the data
		$this->getData($link);
	}

	abstract protected function getData($link);
	
	public function render($output)
	{
		// 1) calculate columns!
		if(sizeof($this->data) > 0)
		{
			foreach(array_keys($this->data[0]) AS $column)
			{
				$this->columns["$column"] = ucwords(str_replace('_', ' ', $column));
			}
	
			$matrix = new DataMatrix($this->columns, $this->data, false);
			return $matrix->to($output);
		}
		else
		{
			return '<p style="font-weight:bold;">No data</p>';
		}
	}	
	
	public function getTestData()
	{
		return $this->data;
	}
	
	protected function getAge($dob)
	{
	    list($year, $month, $day) = explode('-', $dob);
	    $year_diff  = date('Y') - $year;
	    $month_diff = date('m') - $month;
	    $day_diff   = date('d') - $day;
	    if ($month_diff < 0) $year_diff--;
	    elseif (($month_diff==0) && ($day_diff < 0)) $year_diff--;
	    return $year_diff;			
	}
}

?>