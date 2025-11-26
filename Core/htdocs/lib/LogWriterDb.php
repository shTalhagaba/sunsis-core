<?php
class LogWriterDb extends Zend_Log_Writer_Abstract
{
	/**
	 * Database adapter instance
	 *
	 * @var PDO
	 */
	private $_db;

	/**
	 * Name of the log table in the database
	 *
	 * @var string
	 */
	private $_table;

	/**
	 * Relates database columns names to log data field keys.
	 *
	 * @var null|array
	 */
	private $_columnMap;

	/**
	 * Class constructor
	 *
	 * @param PDO $db   Database adapter instance
	 * @param string $table         Log table in database
	 * @param array $columnMap
	 */
	public function __construct($db, $table, $columnMap = null)
	{
		$this->_db    = $db;
		$this->_table = $table;
		$this->_columnMap = $columnMap;
	}

	/**
	 * Create a new instance of Zend_Log_Writer_Db
	 *
	 * @param  array|Zend_Config $config
	 * @return Zend_Log_Writer_Db
	 */
	static public function factory($config)
	{
		$config = self::_parseConfig($config);
		$config = array_merge(array(
			'db'        => null,
			'table'     => null,
			'columnMap' => null,
		), $config);

		if (isset($config['columnmap'])) {
			$config['columnMap'] = $config['columnmap'];
		}

		return new self(
			$config['db'],
			$config['table'],
			$config['columnMap']
		);
	}

	/**
	 * @param Zend_Log_Formatter_Interface $formatter
	 * @return void|Zend_Log_Writer_Abstract
	 * @throws Zend_Log_Exception
	 */
	public function setFormatter(Zend_Log_Formatter_Interface $formatter)
	{
		require_once 'Zend/Log/Exception.php';
		throw new Zend_Log_Exception(get_class($this) . ' does not support formatting');
	}

	/**
	 * Remove reference to database adapter
	 *
	 * @return void
	 */
	public function shutdown()
	{
		$this->_db = null;
	}

	/**
	 * Write a message to the log.
	 *
	 * @param  array  $event  event data
	 * @return void
	 * @throws Zend_Log_Exception
	 */
	protected function _write($event)
	{
		if ($this->_db === null) {
			require_once 'Zend/Log/Exception.php';
			throw new Zend_Log_Exception('Database adapter is null');
		}

		if ($this->_columnMap === null) {
			$dataToInsert = $event;
		} else {
			$dataToInsert = array();
			foreach ($this->_columnMap as $columnName => $fieldKey) {
				$dataToInsert[$columnName] = $event[$fieldKey];
			}
		}

		if (preg_match('/(\d+-\d+-\d+)T(\d+:\d+:\d+)((\+|-)\d+:\d+)/', $dataToInsert['timestamp'], $matches)) {
			$dataToInsert['timestamp'] = $matches[1] . ' ' . $matches[2];
		}

		DAO::saveObjectToTable($this->_db, $this->_table, $dataToInsert);
	}
}