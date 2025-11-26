<?php

class Period
{
	function __construct($year, $number)
	{
		$this->year = $year;
		$this->number;
	}
	
	public static function difference($period1, $period2)
	{
		$found = false;
		$currentPeriod = $period1->number;
		$currentYear = $period1->year;
		
		while($found == false)
		{
			if($currentPeriod == $period2->number AND $currentYear == $period2->year)
			{
				$found = true;
			}
			else
			{
				$currentPeriod++;
			}
		}
	}
}