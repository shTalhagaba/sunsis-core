<?php
class SQLException extends Exception
{
	/**
	 * @param string $message The database error message
	 * @param string $code The database error code
	 * @param string $sql The SQL query that caused the error
	 */
	public function __construct($message, $code, $sql = "")
	{
		parent::__construct($message, $code);
		$this->sql = $sql;
	}

	/**
	 * @return string
	 */
	public function getSql()
	{
		return $this->sql;
	}
	
	private $sql;
}
?>