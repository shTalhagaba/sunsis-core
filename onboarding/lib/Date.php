<?php
/**
 * Date utilities and a GB-date aware wrapper for DateTime
 *  
 * @author ianss
 */
class Date
{
	const MYSQL = 'Y-m-d';
	const SHORT = 'd/m/Y';
	const MEDIUM = 'jS M Y';
	const LONG = 'l jS F Y';
	const DATETIME = 'd/m/Y H:i:s';
	const HM = 'H:i';

	/**
	 * Constructs an instance of a Date object. For most operations
	 * this is not necessary. An instance is required if you wish to modify
	 * a date iteratively.
	 *
	 * @param mixed $date String, timestamp, Date or DateTime
	 */
	public function __construct($date = null)
	{
		$this->setDate($date);
	}
	
	
	/**
	 * @return bool true if the argument is after the date represented by this object
	 * @param mixed $date
	 * @throws Exception
	 */
	public function after($date)
	{
		if(!$this->datetime){
			throw new Exception("Date object has no value");
		}
		if(!$date){
			throw new Exception("Argument \$date cannot be null");
		}
		if(!$date instanceof Date){
			$date = new Date($date);
		}
		if(!$date->datetime){
			throw new Exception("Argument \$date is a null or invalid date");
		}

		$interval = $this->datetime->diff($date->datetime, false);
		$difference = $interval->format("%r%y%m%d%h%i%s");
		return $difference < 0;
	}

	
	/**
	 * @return bool true if the argument is before the date represented by this object
	 * @param mixed $date
	 * @throws Exception
	 */
	public function before($date)
	{
		if(!$this->datetime){
			throw new Exception("Date object has no value");
		}
		if(!$date){
			throw new Exception("Argument \$date cannot be null");
		}
		if(!$date instanceof Date){
			$date = new Date($date);
		}
		if(!$date->datetime){
			throw new Exception("Argument \$date is a null or invalid date");
		}

		$interval = $this->datetime->diff($date->datetime, false);
		$difference = $interval->format("%r%y%m%d%h%i%s");
		return $difference > 0;
	}

	
	/**
	 * @return bool true if the argument equals the date represented by this object
	 * @param mixed $date
	 * @throws Exception
	 */
	public function equals($date)
	{
		if(!$this->datetime){
			return false;
		}
		if(!$date){
			return false;
		}
		if(!$date instanceof Date){
			$date = new Date($date);
		}
		if(!$date->datetime){
			return false;
		}

		$interval = $this->datetime->diff($date->datetime, false);
		$difference = $interval->format("%r%y%m%d%h%i%s");
		return $difference == 0;
	}
	
	
	
	
	/**
	 * UNIX timestamps are restricted to the years 1901-2038 on Unix and 1970-2038 on Windows
	 * due to integer overflow for dates beyond those years.
	 * Included for compatibility with Sunesis.
	 * @deprecated
	 * @return integer UNIX timestamp (seconds after(+) or before(-) the UNIX epoch)
	 */
	public function getDate()
	{
		return $this->getTimestamp();
	}
	
	public function getDateTime()
	{
		return $this->datetime;
	}
	
	/**
	 * UNIX timestamps are restricted to the years 1901-2038 on Unix and 1970-2038 on Windows
	 * due to integer overflow for dates beyond those years. Try to avoid using timestamps
	 * for this reason.
	 * @deprecated
	 * @return integer UNIX timestamp (seconds after(+) or before(-) the UNIX epoch)
	 */
	public function getTimestamp()
	{
		if(!$this->datetime){
			return null;
		}
		
		return $this->datetime->getTimestamp();
	}
	
	/**
	 * Dates can be in the format of 'dd/mm/yyyy' or 'yyyy-mm-dd'
	 * or in the relative formats supported by PHP
	 * (see http://www.php.net/manual/en/datetime.formats.relative.php)
	 * @param mixed $date String, timestamp, Date or DateTime
	 */
	public function setDate($date)
	{
		$info = Date::getInfo($date);
		if(is_array($info))
		{
			$this->datetime = new DateTime('now');
			$this->datetime->setDate($info['year'], $info['mon'], $info['mday']);
			$this->datetime->setTime($info['hours'], $info['minutes'], $info['seconds']);
		}
		else
		{
			$this->datetime = null;				
		}
	}
	
	/**
	 * @return integer Day of month (1 - 31)
	 */
	public function getDay()
	{
		if(is_null($this->datetime)){
			return 0;
		}
		
		return (integer)$this->datetime->format('j');
	}
	
	/**
	 * Retained for compatibility with Sunesis
	 * @deprecated
	 * @return integer Day of month (1 - 31)
	 */
	public function getDays()
	{
		return $this->getDay();
	}
	
	/**
	 * @return int weekday (1=Monday, 7=Sunday)
	 */
	public function getWeekday()
	{
		if(is_null($this->datetime)){
			return 0;
		}
		
		return (integer)$this->datetime->format('N');		
	}
	
	/**
	 * @return integer Month of year (1 - 12)
	 */	
	public function getMonth()
	{
		if(is_null($this->datetime)){
			return 0;
		}
		
		return (integer)$this->datetime->format('n');
	}
	
	/**
	 * @return integer Year
	 */	
	public function getYear()
	{
		if(is_null($this->datetime)){
			return 0;
		}
		
		return (integer)$this->datetime->format('Y');
	}
	
	/**
	 * @return integer Hour (0 - 23)
	 */	
	public function getHour()
	{
		if(is_null($this->datetime)){
			return 0;
		}
		
		return (integer)$this->datetime->format('G');
	}
	
	/**
	 * @return integer Minute (0 - 59)
	 */	
	public function getMinute()
	{
		if(is_null($this->datetime)){
			return 0;
		}
		
		return (integer)$this->datetime->format('i');
	}
	
	/**
	 * @return integer Second (0 - 59)
	 */	
	public function getSecond()
	{
		if(is_null($this->datetime)){
			return 0;
		}
		
		return (integer)$this->datetime->format('s');
	}
	
	/**
	 * @param integer $days
	 */
	public function addDays($days)
	{
		if(!is_null($this->datetime) && is_numeric($days)){
			$this->datetime->modify("+$days day");
		}
	}
	
	/**
	 * @param integer $days
	 */
	public function subtractDays($days)
	{
		if(!is_null($this->datetime) && is_numeric($days)){
			$this->datetime->modify("-$days day");
		}
	}
	
	/**
	 * @param integer $months
	 */
	public function addMonths($months)
	{
		if(!is_null($this->datetime) && is_numeric($months)){
			$this->datetime->modify("+$months month");
		}
	}
	
	/**
	 * @param integer $months
	 */
	public function subtractMonths($months)
	{
		if(!is_null($this->datetime) && is_numeric($months)){
			$this->datetime->modify("-$months month");
		}
	}
	
	/**
	 * @param integer $years
	 */
	public function addYears($years)
	{
		if(!is_null($this->datetime) && is_numeric($years)){
			$this->datetime->modify("+$years year");
		}
	}
	
	/**
	 * @param integer $years
	 */
	public function subtractYears($years)
	{
		if(!is_null($this->datetime) && is_numeric($years)){
			$this->datetime->modify("-$years year");
		}
	}	
	
	/**
	 * @param integer $hours
	 */
	public function addHours($hours)
	{
		if(!is_null($this->datetime) && is_numeric($hours)){
			$this->datetime->modify("+$hours hour");
		}
	}
	
	/**
	 * @param integer $hours
	 */
	public function subtractHours($hours)
	{
		if(!is_null($this->datetime) && is_numeric($hours)){
			$this->datetime->modify("-$hours hour");
		}
	}	
	
	/**
	 * @param integer $minutes
	 */
	public function addMinutes($minutes)
	{
		if(!is_null($this->datetime) && is_numeric($minutes)){
			$this->datetime->modify("+$minutes minute");
		}
	}
	
	/**
	 * @param integer $minutes
	 */
	public function subtractMinutes($minutes)
	{
		if(!is_null($this->datetime) && is_numeric($minutes)){
			$this->datetime->modify("-$minutes minute");
		}
	}
	
	/**
	 * @param integer $seconds
	 */
	public function addSeconds($seconds)
	{
		if(!is_null($this->datetime) && is_numeric($seconds)){
			$this->datetime->modify("+$seconds second");
		}
	}
	
	/**
	 * @param integer $seconds
	 */
	public function subtractSeconds($seconds)
	{
		if(!is_null($this->datetime) && is_numeric($seconds)){
			$this->datetime->modify("-$seconds second");
		}
	}
	
	/**
	 * @return string
	 */
	public function formatMySQL()
	{
		if(is_null($this->datetime))
		{
			return '';
		}
		else
		{
			return $this->datetime->format(Date::MYSQL);
		}
	}
	
	/**
	 * @return string
	 */
	public function formatShort()
	{
		if(is_null($this->datetime))
		{
			return '';
		}
		else
		{
			return $this->datetime->format(Date::SHORT);
		}
	}
	
	/**
	 * @return string
	 */
	public function formatMedium()
	{
		if(is_null($this->datetime))
		{
			return '';
		}
		else
		{
			return $this->datetime->format(Date::MEDIUM);
		}
	}
	
	/**
	 * @return string
	 */
	public function formatLong()
	{
		if(is_null($this->datetime))
		{
			return '';
		}
		else
		{
			return $this->datetime->format(Date::LONG);
		}
	}

	/**
	 * @return int
	 */
	public function formatTimestamp()
	{
		if(!$this->datetime){
			return '';
		}
		
		return $this->datetime->getTimestamp();
	}
	
	/**
	 * Format a Date object as a string using a custom formatting string.
	 * For formatting syntax, see the documentation for the PHP date() function.
	 * @param string $strFormat Format
	 * @return string
	 */
	public function format($strFormat)
	{
		if(is_null($this->datetime))
		{
			return '';
		}
		else
		{
			return $this->datetime->format($strFormat);
		}		
	}
	
	/**
	 * Convenience function to parse and reformat a date in one operation
	 * @param mixed $date String, timestamp, Date or DateTime
	 * @return string
	 */
	static public function toMySQL($date)
	{
		return Date::to($date, Date::MYSQL);
	}
	
	/**
	 * Convenience function to parse and reformat a date in one operation
	 * @param mixed $date String, timestamp, Date or DateTime
	 * @return string
	 */	
	static public function toShort($date)
	{
		return Date::to($date, Date::SHORT);
	}
	
	/**
	 * Convenience function to parse and reformat a date in one operation
	 * @param mixed $date String, timestamp, Date or DateTime
	 * @return string
	 */	
	static public function toMedium($date)
	{
		return Date::to($date, Date::MEDIUM);
	}
	
	/**
	 * Convenience function to parse and reformat a date in one operation
	 * @param mixed $date String, timestamp, Date or DateTime
	 * @return string
	 */	
	static public function toLong($date)
	{
		return Date::to($date, Date::LONG);
	}

	/**
	 * Convenience function to parse and reformat a date in one operation
	 * @param mixed $date String, timestamp, Date or DateTime
	 * @param string $format Date format string, as supported by date() and DateTime.
	 * @return string formatted date string or an empty string if the date argument could not be parsed
	 */
	static public function to($date, $format)
	{
		if(is_null(self::$dt)){
			self::$dt = new DateTime('now');
		}
		
		if(is_null($date) || $date == '')
		{
			return $date;
		}
		else
		{
			$info = Date::getInfo($date);
			if(is_array($info))
			{
				self::$dt->setDate($info['year'], $info['mon'], $info['mday']);
				self::$dt->setTime($info['hours'], $info['minutes'], $info['seconds']);
				return self::$dt->format($format);
			}
			else
			{
				return '';
			}
		}
	}
	
	/**
	 * Timestamps support only a limited
	 * range of dates (at their minimum they support years 1970 - 2038).
	 * 
	 * @param mixed $date String, timestamp, Date or DateTime
	 * @return integer UNIX timestamp
	 */
	public static function toTimestamp($date)
	{
		if($date instanceof SimpleXMLElement){
			$date = (string) $date;
		}

		$info = Date::getInfo($date);
		if(!is_array($info)){
			return null;
		}
		
		return gmmktime($info['hours'], $info['minutes'], $info['seconds'], $info['mon'], $info['mday'], $info['year']);
	}
	
	/**
	 * Returns the date formatted as the academic year within which the date falls
	 * e.g. 2010-07-01 would return 2009/10 and 2010-09-05 would return 2010/11.
	 * @param mixed $date
	 * @return string
	 */
	public static function toAcademicYear($date)
	{
		if(is_null(self::$dt)){
			self::$dt = new DateTime('now');
		}
		
		if(is_null($date) || $date == '')
		{
			return $date;
		}
		else
		{
			$info = Date::getInfo($date);
			if($info['mon'] >= 9)
			{
				$year_start = $info['year'];
				$year_end = $info['year'] + 1;
			}
			else
			{
				$year_start = $info['year'] - 1;
				$year_end = $info['year'];				
			}
			
			return $year_start.'/'.substr($year_end, 2);
		}		
	}
	
	/**
	 * Timestamps support only a limited
	 * range of dates (at their minimum they support years 1970 - 2038).
	 * 
	 * @param mixed $date String, timestamp, Date or DateTime
	 * @return integer UNIX timestamp
	 * @deprecated
	 */	
	public static function parseDate($date)
	{
		return Date::toTimestamp($date);
	}
	
	
	/**
	 * Returns an array identical to that returned by the PHP getdate() function.
	 * Dates can be in the format of 'dd/mm/yyyy' or 'yyyy-mm-dd'
	 * or in the relative formats supported by PHP
	 * (see http://www.php.net/manual/en/datetime.formats.relative.php)
	 * @param mixed $date String, timestamp, Date or DateTime
	 * @return array
	 * @throws Exception
	 */
	public static function getInfo($date)
	{
		if(is_null($date)){
			return null;
		}
		
		// Process non-string types first
		
		if($date instanceof SimpleXMLElement){
			$date = (string) $date;
		}
		
		if($date instanceof Date)
		{
			if(is_null($date->datetime)){
				return null;
			}
			
			return Date::getInfo($date->datetime);
		}
		
		if($date instanceof DateTime)
		{
			$info = array();
			$info['seconds'] = (integer)$date->format('s');
			$info['minutes'] = (integer)$date->format('i');
			$info['hours'] = (integer)$date->format('G');
			$info['mday'] = (integer)$date->format('j'); // 1 to 31
			$info['wday'] = (integer)$date->format('w'); // 0 to 6
			$info['mon'] = (integer)$date->format('n'); // 1 to 12
			$info['year'] = (integer)$date->format('Y');
			$info['yday'] = (integer)$date->format('z'); // 0 to 365
			$info['weekday'] = $date->format('l'); // Sunday to Saturday
			$info['month'] = $date->format('F'); // January to December
			
			return $info;
		}
		
		if(is_numeric($date) && strlen(trim($date)) > 8){
			return getDate($date); // assume UNIX timestamp
		}
		
		// Only strings allowed beyond this point
		if(!is_string($date)){
			return null;
		}
		
		// Trim and check for null values
		$date = trim($date);
		if(Date::isNullDate($date)){
			return null;
		}

		// Parse strings
		if(preg_match(Date::REG_MYSQL, $date, $matches))
		{
			// Year-month-day (MySQL)
			$year = (integer)$matches[1];
			$month = (integer)$matches[2];
			$day = (integer)$matches[3];
			
			switch(count($matches))
			{
				case 6:
					$hour = (integer)$matches[4];
					$minute = (integer)$matches[5];
					$second = 0;
					break;					
				
				case 7:
					$hour = (integer)$matches[4];
					$minute = (integer)$matches[5];
					$second = (integer)$matches[6];
					break;
				
				default:
					$hour = 0;
					$minute = 0;
					$second = 0;
					break;			
			}
		}
		elseif(preg_match(Date::REG_GB, $date, $matches))
		{
			// Day/month/year
			$year = (integer)$matches[3];
			$month = (integer)$matches[2];
			$day = (integer)$matches[1];
			
			switch(count($matches))
			{
				case 6:
					$hour = (integer)$matches[4];
					$minute = (integer)$matches[5];
					$second = 0;
					break;				
				
				case 7:
					$hour = (integer)$matches[4];
					$minute = (integer)$matches[5];
					$second = (integer)$matches[6];
					break;
				
				default:
					$hour = 0;
					$minute = 0;
					$second = 0;
					break;					
			}
		}
		else
		{
			try
			{
				$datetime = new DateTime($date);
			}
			catch(Exception $e)
			{
				throw new Exception("Invalid date format '$date'. Valid date formats: dd/mm/yyyy or yyyy-mm-dd");
			}
			
			$info = array();
			$info['seconds'] = (integer)$datetime->format('s');
			$info['minutes'] = (integer)$datetime->format('i');
			$info['hours'] = (integer)$datetime->format('G');
			$info['mday'] = (integer)$datetime->format('j'); // 1 to 31
			$info['wday'] = (integer)$datetime->format('w'); // 0 to 6
			$info['mon'] = (integer)$datetime->format('n'); // 1 to 12
			$info['year'] = (integer)$datetime->format('Y');
			$info['yday'] = (integer)$datetime->format('z'); // 0 to 365
			$info['weekday'] = $datetime->format('l'); // Sunday to Saturday
			$info['month'] = $datetime->format('F'); // January to December
			
			return $info;
		}
		
		// Only the two regular expression cases above fall through to here
		
		// Final test for a null date
		if($year == 0 && $month == 0 && $day == 0 && $hour == 0 && $minute == 0 && $second == 0){
			return null;
		}
		
		// Check the date is a valid calendar date
		if(!checkdate($month, $day, $year))
		{
			throw new Exception("Date '$date' is not a valid calendar date. "
				." Check that you are using the UK date format (dd/mm/yyyy) and that you are not specifying 31 days for a month with only 30 days.");
		}
		
		$dt = new DateTime();
		$dt->setDate($year, $month, $day);
		$dt->setTime($hour, $minute, $second);
		
		$info = array();
		$info['seconds'] = $second;
		$info['minutes'] = $minute;
		$info['hours'] = $hour;
		$info['mday'] = $day; // 1 to 31
		$info['wday'] = (integer)$dt->format('w'); // 0 to 6
		$info['mon'] = $month; // 1 to 12
		$info['year'] = $year;
		$info['yday'] = (integer)$dt->format('z'); // 0 to 365
		$info['weekday'] = $dt->format('l'); // Sunday to Saturday
		$info['month'] = $dt->format('F'); // January to December
		
		
		return $info;		
	}
	
	/**
	 * @static
	 * @param $date
	 * @return DateTime|null
	 */
	public static function toDateTime($date)
	{
		$dt = null;
		
		if($date instanceof DateTime)
		{
			return $date;
		}
		elseif($date instanceof Date)
		{
			return $date->datetime;
		}
		else
		{
			$info = Date::getInfo($date);
			if(!is_array($info)){
				return null;
			}
			
			$dt = new DateTime('now');
			$dt->setDate($info['year'], $info['mon'], $info['mday']);
			$dt->setTime($info['hours'], $info['minutes'], $info['seconds']);		
		}

		return $dt;
	}
	
	/**
	 * @static
	 * @param $strDate
	 * @return bool
	 */
	public static function isDate($strDate)
	{
		if($strDate instanceof SimpleXMLElement){
			$strDate = (string) $strDate;
		}
		
		if(is_null($strDate) || !is_string($strDate) || $strDate == ''){
			return false;
		}
		
		if(preg_match(Date::REG_MYSQL, $strDate) || preg_match(Date::REG_GB, $strDate)){
			return true;
		}
		
		return false;
	}
	
	/**
	 * @static
	 * @param $strDate
	 * @return bool
	 */
	public static function isNullDate($strDate)
	{
		return is_null($strDate) || !is_string($strDate) || $strDate == '' || in_array($strDate, Date::$null_dates);
	}
	
	/**
	 * Formats a year as yyyy/yy.
	 * Included for compatibility with Sunesis.
	 * @deprecated
	 * @param mixed $year numeric year (as a string or integer)
	 * @return string a string in the format yyyy/yy (e.g. 2010/11) or an empty string if $year is not numeric
	 */
	public static function getFiscal($year)
	{
		if(!is_numeric($year)){
			return '';
		}
		 
		$nextYear = str_pad( ($year + 1), 4, '0', STR_PAD_LEFT);
		return $year.'/'.substr($nextYear, 2, 2);
	}
	
	/**
	 * Returns the number of days in a month.
	 * Deprecated, but included for compatibility with Sunesis. Please use
	 * the PHP built-in function cal_days_in_month() instead.
	 * @deprecated
	 * @param integer $month
	 * @param integer $year
	 * @return integer the number of days in the specified month
	 */
	public static function getDaysInMonth($month, $year)
	{
		return cal_days_in_month(CAL_GREGORIAN, $month, $year);
	}
	
	public static function isLeapYear($year)
	{
		if($year % 400 == 0)
		{
			return true;
		}
		elseif($year % 100 == 0)
		{
			return false;
		}
		elseif($year % 4 == 0)
		{
			return true;
		}
		else
		{
			return false;
		}
	}
	
	public function __toString()
	{
		return $this->formatMySQL();
	}

	/**
	 * @param mixed $d
	 * @param bool $absolute Whether to return an absolute value (default) or a signed value. If signed, returned values will be negative if $d is smaller and positive if $d is greater
	 * @return array
	 */
	public function diff($d, $absolute=true)
	{
		return Date::dateDiffInfo($this, $d, $absolute);
	}

	/**
	* The difference between two dates expressed as a string.
	* @param mixed $d1 Date 1
	* @param mixed $d2 Date 2
	* @param int $precision (optional) 1 - 6
	* @return string The difference between the dates or a blank string if the dates are equal or either of the two dates is null
	*/
	public static function dateDiff($d1, $d2, $precision = 6)
	{
		$info = Date::dateDiffInfo($d1, $d2, true);
		if(!$info){
			return null;
		}

		$str = "";
		$p = 1;
		foreach ($info as $key=>$value)
		{
			if ($p > $precision) {
				break;
			}
			if ($value && $key != "days"){
				$str .= ', '.$value.' '.$key.($value > 1 ? 's':'');
			}
			$p = $p + 1;
		}

		// Clean up and replace final comma with ' and '
		$str = trim($str, ' ,');
		$str = preg_replace('/, ([^,]+)?$/', ' and $1', $str, 1);
		
		return $str;		
	}
	
	/**
	* The difference between two dates expressed as an array
	* @param mixed $d1 Date 1
	* @param mixed $d2 Date 2
	* @param boolean $absolute Whether to return an absolute value (default) or a signed value. If signed, returned values will be negative if $d is smaller and positive if $d is greater
	* @return array An associative array in the format ('year'=>val, 'month'=>val, 'day'=>val, 'hour'=>val, 'minute'=>va, 'second'=>val) or NULL if either d1 or d2 are NULL
	*/	
	public static function dateDiffInfo($d1, $d2, $absolute = true)
	{
		$d1 = Date::toDateTime($d1);
		$d2 = Date::toDateTime($d2);
		if(!$d1 || !$d2){
			return array("year"=>0, "month"=>0, "day"=>0, "hour"=>0, "minute"=>0, "second"=>0);
		}

		/** @var $interval DateInterval */
		$interval = $d1->diff($d2, $absolute);

		$diff = array();
		$diff["year"] = (int)$interval->format('%r%y');
		$diff["month"] = (int)$interval->format('%r%m');
		$diff["day"] = (int)$interval->format('%r%d');
		$diff["days"] = (int)$interval->format('%r%a');
		$diff["hour"] = (int)$interval->format('%r%h');
		$diff["minute"] = (int)$interval->format('%r%i');
		$diff["second"] = (int)$interval->format('%r%s');

		return $diff;
	}
	
	
	// Date patterns
	const REG_GB = '#^(\d{1,2})[-/.](\d{1,2})[-/.](\d\d\d\d)(?:\s(\d\d):(\d\d):(\d\d)?)?$#';
	const REG_MYSQL = '#^(\d\d\d\d)[-/](\d{1,2})[-/](\d{1,2})(?:\s(\d\d):(\d\d):(\d\d)?)?$#';

	/**
	 * The DateTime object wrapped by this class
	 * @var DateTime $datetime
	 */
	private $datetime = null;

	/**
	 * Static DateTime object used by the Date::toX() functions (to avoid repeated object creation)
	 * @var DateTime $dt
	 */
	private static $dt = null;
	
	// Strings that represent a null date
	private static $null_dates = array('dd/mm/yyyy', '0000-00-00', '0000-00-00 00:00:00',
		'00/00/0000', '00/00/00', '00:00:00', '00000000', 'null', 'undefined');
}
?>