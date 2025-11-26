<?php
/**
 * LoginLog DAO
 */
class LoginLogDAO
{
	public function __construct($link)
	{
		if(!$link)
		{
			throw new Exception("Valid PDO link required on creation");
		}
		$this->link = $link;
	}


	public function find($id)
	{
		$query = "SELECT * FROM logins WHERE id=" . addslashes($id) . ";";
		$st = $this->link->query($query);

		$vo = new LoginLogVO();
		if($st)
		{
			$row = $st->fetch();
			if($row)
			{
				$vo->populate($row);
			}
			else
			{
				throw new Exception("Could not find a record with id $id in the database");
			}
		}
		else
		{
			throw new DatabaseException($this->link, $query);
		}

		return $vo;
	}


	public function insert(LoginLogVO $vo)
	{
		
		$exclude = array('id', 'date');
		$query = "INSERT INTO logins " . $vo->toNameValuePairsSQL($exclude);

		$st = $this->link->query($query);

		if($st == false)
		{
			throw new Exception("Error inserting record into log. " . implode($this->link->errorInfo()));
		}

		if ($this->link->getAttribute(PDO::ATTR_DRIVER_NAME) == 'mysql') 
			$ret = $this->link->lastInsertId();
		else
			$ret = DAO::getSingleValue($this->link, "SELECT id from logins where id=@@IDENTITY");

		return $ret;
	}


	/**
	 * Deletes entries in the log
	 *
	 * @param date $cutoffDate
	 */
	public function delete($cutoffDate = null)
	{
		$date = Date::parseDate();
		
		if(!is_null($cutoffDate))
		{
			$query = "DELETE FROM logins WHERE `date` < '" . Date::toMySQL($date) . "';";
		}
		else
		{
			$query = "DELETE FROM logins;";
		}
		
		$st = $this->link->query($query);

		if($st == false)
		{
			throw new Exception("Could not delete log. " . $st->errorCode());
		}


		return true;
	}

	private $link = null;
}
?>