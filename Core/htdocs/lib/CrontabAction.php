<?php
abstract class CrontabAction
{
	public $id;
	public $schema;
	public $task;
	public $mail_log;
	public $mail_errors;
	public $order;
	public $read_only;
	public $enabled;
	public $minute;
	public $hour;
	public $day_of_month;
	public $month;
	public $day_of_week;

	/** @var Zend_Log */
	private $_log;

	public static function loadFromDatabase(PDO $link, $id)
	{
		if (!$id || !is_numeric($id)) {
			throw new Exception("Illegal value for argument \$id");
		}

		$rows = DAO::getResultset($link, "SELECT * FROM crontab WHERE id = " . $id, DAO::FETCH_ASSOC);
		if (!$rows) {
			return null;
		}
		if (!$rows[0]['task']) {
			throw new Exception("CrontabAction #$id has an empty task field");
		}

		// Instantiate the correct action class and populate (from crontab table)
		$action = self::getInstance($rows[0]['task']);
		$action->set($rows[0]);

		// Retrieve properties (from crontab_config table)
		$properties = DAO::getLookupTable($link, "SELECT `key`, `value` FROM crontab_config WHERE crontab_id=" . $id);
		$action->set($properties);

		// The schema variable is essential when running actions from cron.php
		$action->schema = DAO::getSingleValue($link, "SELECT DATABASE()");

		return $action;
	}


	/**
	 * Takes the name of a class and returns a new instance of the appropriate
	 * CrontabAction subclass.
	 * @static
	 * @param string $task e.g. 'Noop', 'SynchroniseLearners', ..
	 * @return CrontabAction
	 */
	public static function getInstance($task)
	{
		// Load the action class file appropriate to the type of action
		$class = 'CrontabAction' . $task;
		return new $class();
	}


	/**
	 * Populates the CrontabAction from an array or object.
	 * @param $data Array or object
	 * @throws Exception
	 */
	public function set($data)
	{
		if (!$data) {
			return;
		}
		if (!is_array($data) && !is_object($data)) {
			throw new Exception("Invalid type for argument $data");
		}
		$data = (array) $data;

		$publicProperties = $this->getPublicProperties($this);
		foreach ($data as $key=>$value) {
			if (in_array($key, $publicProperties)) {
				$this->$key = trim((string) $value);
			}
		}
	}

	/**
	 * Override this in subclasses to implement the action's functionality
	 * @abstract
	 * @param PDO $link
	 * @return mixed
	 */
	public abstract function execute(PDO $link);

	public function save(PDO $link)
	{
		// New records must have a schedule specified
		if (!$this->id) {
			if (is_null($this->minute)) {
				$this->minute = '0';
			}
			if (is_null($this->hour)) {
				$this->hour = '0';
			}
			if (is_null($this->day_of_month)) {
				$this->day_of_month = '*';
			}
			if (is_null($this->month)) {
				$this->month = '*';
			}
			if (is_null($this->day_of_week)) {
				$this->day_of_week = '*';
			}
		}

		// New and existing records cannot have empty strings in schedule fields
		if ($this->minute === '') {
			$this->minute = '0';
		}
		if ($this->hour === '') {
			$this->hour = '0';
		}
		if ($this->day_of_month === '') {
			$this->day_of_month = '*';
		}
		if ($this->month === '') {
			$this->month = '*';
		}
		if ($this->day_of_week === '') {
			$this->day_of_week = '*';
		}

		// Save main fields to crontab table
		DAO::saveObjectToTable($link, 'crontab', $this);

		// Calculate which properties should go in the configuration table
		$publicProperties = $this->getPublicProperties($this);
		$coreProperties = $this->getPublicProperties('CrontabAction');
		$properties = array_diff($publicProperties, $coreProperties);

		// Save subclass fields to crontab_config table
		$data = array();
		foreach ($properties as $prop) {
			$datum = array();
			$datum['crontab_id'] = $this->id;
			$datum['key'] = $prop;
			$datum['value'] = $this->$prop;
			$data[] = $datum;
		}
		DAO::execute($link, "DELETE FROM crontab_config WHERE crontab_id = " . $this->id);
		DAO::multipleRowInsert($link, 'crontab_config', $data);
	}


	public function delete(PDO $link)
	{
		DAO::execute($link, "DELETE FROM crontab WHERE id = " . $this->id);
		DAO::execute($link, "DELETE FROM crontab_config WHERE crontab_id = " . $this->id);
	}


	/**
	 * @param mixed $spec
	 * @return array
	 */
	private function getPublicProperties($spec)
	{
		$props = array();
		$reflectionClass = new ReflectionClass($spec);
		$properties = $reflectionClass->getProperties();
		foreach ($properties as $prop) {
			if ($prop->isPublic()) {
				$props[] = $prop->getName();
			}
		}
		return $props;
	}

	/**
	 * @param mixed $date
	 * @return bool
	 */
	public function matchDate($date)
	{
		$crontab = new Crontab($this->minute, $this->hour, $this->day_of_month, $this->month, $this->day_of_week);
		if (!$date instanceof Date) {
			$date = new Date($date);
		}
		return $crontab->matches($date);
	}


	/**
	 * @param Zend_Log $log
	 */
	public function setLog(Zend_Log $log)
	{
		$this->_log = $log;
	}

	/**
	 * @return null|Zend_Log
	 */
	public function getLog()
	{
		return $this->_log;
	}

	/**
	 * @param string|Exception $message
	 * @param int $priority
	 * @param  $extras
	 */
	public function log($message, $priority, $extras = null)
	{
		if ($this->_log) {
			if ($message instanceof Exception) {
				$e = $message;
				$message = $e->getFile() . '(' . $e->getLine() . '): '
					. $e->getMessage() . "\r\n\r\n" . $e->getTraceAsString();
			}
			$extras = (array) $extras;
			$extras['crontab_id'] = $this->id;
			$this->_log->log($message, $priority, $extras);
		}
	}
}