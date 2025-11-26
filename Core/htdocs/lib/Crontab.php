<?php
class Crontab
{
	/**
	 * @param string $minute
	 * @param string $hour
	 * @param string $day
	 * @param string $month
	 * @param string $weekday
	 */
	public function __construct($minute, $hour, $day, $month, $weekday)
	{
		$this->_setMinute($minute);
		$this->_setHour($hour);
		$this->_setMonth($month);
		$this->_setDay($day, $weekday);
	}

	/**
	 * @param string $minute
	 */
	private function _setMinute($minute)
	{
		if(is_null($minute) || $minute === ""){
			$minute = "*";
		}

		$this->_minutes = $this->_parseCronString($minute, 0, 59);
	}

	/**
	 * @param string $hour
	 */
	private function _setHour($hour)
	{
		if(is_null($hour) || $hour === ""){
			$hour = "*";
		}

		$this->_hours = $this->_parseCronString($hour, 0, 23);
	}

	/**
	 * @param string $dayOfMonth
	 * @param string $dayOfWeek
	 */
	private function _setDay($dayOfMonth, $dayOfWeek)
	{
		if(!$dayOfMonth){
			$dayOfMonth = "*";
		}
		if(!$dayOfWeek){
			$dayOfWeek = "*";
		}

		// If both dayOfMonth and dayOfWeek are specified then
		// the CronTab matches when either of the patterns match.
		// BUT if dayOfMonth is * and dayOfWeek is not, then dayOfWeek
		// takes precedence and dayOfMonth has no value.
		if($dayOfMonth == "*" && $dayOfWeek != "*")
		{
			$this->_days = array();
			$this->_weekdays = $this->_parseCronString($dayOfWeek, 0, 6);
		}
		else
		{
			$this->_days = $this->_parseCronString($dayOfMonth, 1, 31);
			$this->_weekdays = $this->_parseCronString($dayOfWeek, 0, 6);
		}

		if(in_array(7, $this->_weekdays))
		{
			foreach($this->_weekdays as &$d)
			{
				if($d == 7){
					$d = 0;
				}
			}
			sort($this->_weekdays);
		}
	}

	/**
	 * @param string $month
	 */
	private function _setMonth($month)
	{
		if(!$month){
			$month = "*";
		}

		$this->_months = $this->_parseCronString($month, 1, 12);
	}

	/**
	 * @param string $str
	 * @param int $minValue
	 * @param int $maxValue
	 * @return array
	 */
	private function _parseCronString($str, $minValue, $maxValue)
	{
		$values = array();

		if(strpos($str, ',') !== FALSE)
		{
			$tokens = explode(',', $str);
			foreach($tokens as $token)
			{
				$values = array_merge($values, $this->_expandCronToken($token, $minValue, $maxValue));
			}
			sort($values);
		}
		else
		{
			$values = array_merge($values, $this->_expandCronToken($str, $minValue, $maxValue));
		}

		return $values;
	}

	/**
	 * @param string $str
	 * @param int $minValue
	 * @param int $maxValue
	 * @return array
	 * @throws Exception
	 */
	private function _expandCronToken($str, $minValue, $maxValue)
	{
		$expandedToken = array();
		$t1 = 0;
		$t2 = 0;
		$t3 = 0;
		$counter = 0;

		if(preg_match("#^(\\d+)(?:-(\\d+)(?:/(\\d+))?)?#", $str, $matches))
		{
			$t1 = isset($matches[1]) ? (int)$matches[1] : -1;
			$t2 = isset($matches[2]) ? (int)$matches[2] : -1;
			$t3 = isset($matches[3]) ? (int)$matches[3] : -1;

			if($t1 >= 0 && $t2 == -1 && $t3 == -1)
			{
				// Single digit
				$expandedToken[] = $t1;
			}
			else if($t1 >= 0 && $t2 >= 0 && $t3 == -1)
			{
				// Range
				if($t1 >= $t2)
				{
					throw new Exception("Second value in a range literal must be greater in value than the first value.");
				}

				for($i = 0; $i <= ($t2 - $t1); $i++)
				{
					$expandedToken[] = $t1 + $i;
				}
			}
			else
			{
				//Stepped range
				if($t1 >= $t2)
				{
					throw new Exception("Second value in a range literal must be greater in value than the first value.");
				}

				// Calculate array dimension
				$counter = 0;
				for($i = $t1; $i <= $t2; $i += $t3)
				{
					$counter++;
				}

				// Create and populate array
				for($i = 0; $i < $counter; $i++)
				{
					$expandedToken[] = $t1 + ($t3 * $i);
				}
			}
		}
		else if(preg_match("#^\\*(?:/(\\d+))?$#", $str, $matches))
		{
			$t1 = isset($matches[1]) ? (int)$matches[1] : -1;

			if($t1 >= 0)
			{
				// Stepped wildcard

				// Calculate array dimension
				$counter = 0;
				for($i = $minValue; $i <= $maxValue; $i += $t1)
				{
					$counter++;
				}

				for($i = 0; $i < $counter; $i++)
				{
					$expandedToken[] = $minValue + ($i * $t1);
				}
			}
			else
			{
				// Unstepped wildcard (all values)
				for($i = 0; $i + $minValue <= $maxValue; $i++)
				{
					$expandedToken[] = $i + $minValue;
				}
			}
		}
		else
		{
			throw new Exception("Invalid Cron token '" . $str . "'.");
		}

		return $expandedToken;
	}

	/**
	 * @return array
	 */
	public function getTimes()
	{
		$times = array();
		foreach($this->_hours as $h)
		{
			foreach($this->_minutes as $m)
			{
				$times[] = sprintf('%02d:%02d', $h, $m);
			}
		}
		sort($times);

		return $times;
	}

	/**
	 * @param Date $date
	 * @return bool
	 */
	public function matches(Date $date)
	{
		//var_dump($this);
		if (!in_array($date->getMinute(), $this->_minutes)) {
			return false;
		}
		if (!in_array($date->getHour(), $this->_hours)) {
			return false;
		}
		if ($this->_days && !in_array($date->getDay(), $this->_days)) {
			return false; // The exception: only search '_days' if it has content
		}
		if (!in_array($date->getMonth(), $this->_months)) {
			return false;
		}
		if (!in_array($date->getWeekday(), $this->_weekdays)) {
			return false;
		}

		return true;
	}



	public function __toString()
	{
		$sb = '';
		$sb .= "minutes(";
		foreach($this->_minutes as $val){
			$sb .= $val.',';
		}

		$sb .= "), hours(";
		foreach($this->_hours as $val){
			$sb .= $val.',';
		}

		$sb .= "), days(";
		foreach($this->_days as $val){
			$sb .= $val.',';
		}

		$sb .= "), months(";
		foreach($this->_months as $val){
			$sb .= $val.',';
		}

		$sb .= "), weekdays(";
		foreach($this->_weekdays as $val){
			$sb .= $val.',';
		}

		$sb .= ")";

		return $sb;
	}

	private $_minutes = array();
	private $_hours = array();
	private $_days = array();
	private $_months = array();
	private $_weekdays = array();

}