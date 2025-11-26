<?php
/**
 * Convenience subclass of SQLException that allows construction
 * of a SQLException using
 * @author iss
 */
class DatabaseException extends SQLException
{
	/**
	 * @param mixed $db_resource Reference to a PDO, PDOStatement or mysqli object
	 * @param string $sql The SQL query that caused the error
	 */
	public function __construct($db_resource, $sql = "")
	{
		if($db_resource)
		{
			if($db_resource instanceof PDO || $db_resource instanceof PDOStatement)
			{
				$error_info = $db_resource->errorInfo();
				$error_msg = $error_info[2] ? $error_info[2] : "";
				$error_code = $error_info[1] ? $error_info[1] : 0;
				parent::__construct($error_msg, $error_code, $sql);
			}
			elseif($db_resource instanceof mysqli)
			{
				parent::__construct($db_resource->error, $db_resource->errno, $sql);
			}
			else
			{
				parent::__construct("", 0, $sql);
			}
		}
		else
		{
			parent::__construct("", 0, $sql);
		}
	}

}