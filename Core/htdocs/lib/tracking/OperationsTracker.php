<?php

class OperationsTracker extends Entity
{
	public static function loadFromDatabase(PDO $link, $id = '')
	{
		if($id == '')
		{
			return null;
		}

		$key = addslashes((string)$id);
		$query = <<<HEREDOC
SELECT
	*
FROM
	op_trackers
WHERE
	id='$key';
HEREDOC;
		$st = $link->query($query);

		$tracker = null;
		if($st)
		{
			$tracker = null;
			$row = $st->fetch();
			if($row)
			{
				$tracker = new OperationsTracker();
				$tracker->populate($row);

				$records = DAO::getSingleColumn($link, "SELECT framework_id FROM op_tracker_frameworks WHERE tracker_id = '{$tracker->id}'");
				foreach($records AS $r)
				{
					$tracker->frameworks[] = $r;
				}
				$records = DAO::getSingleColumn($link, "SELECT unit_ref FROM op_tracker_units WHERE tracker_id = '{$tracker->id}'");
				foreach($records AS $r)
				{
					$tracker->units[] = $r;
				}
			}
		}
		else
		{
			throw new Exception("Could not execute database query to find tracker record. " . '----' . $query . '----' . $link->errorCode());
		}

		return $tracker;
	}

	public function save(PDO $link)
	{
		$this->modified = "";
		$this->created = ($this->id == "") ? date('Y-m-d H:i:s') : $this->created;
		$this->created_by = ($this->id == "") ? $_SESSION['user']->id : $this->created_by;

		return DAO::saveObjectToTable($link, 'op_trackers', $this);
	}

	public function saveTrackerFrameworks(PDO $link, $frameworks = array())
	{
		if(count($frameworks) == 0)
			return;

		DAO::execute($link, "DELETE FROM op_tracker_frameworks WHERE tracker_id = '{$this->id}'");
		$insert_sql = "";
		foreach($frameworks AS $f)
			$insert_sql .= " INSERT INTO op_tracker_frameworks (tracker_id, framework_id) VALUES ('{$this->id}', '{$f}'); ";

		DAO::execute($link, $insert_sql);
	}

	public function saveTrackerUnits(PDO $link, $units = array())
	{
		if(count($units) == 0)
			return;

		DAO::execute($link, "DELETE FROM op_tracker_units WHERE tracker_id = '{$this->id}'");

		$insert_sql = "";
		foreach($units AS $v)
		{
			$v = json_decode($v);
			$insert_sql .= " INSERT INTO op_tracker_units (tracker_id, unit_ref) VALUES ('{$this->id}', '{$v->unit_ref}'); ";
		}

		DAO::execute($link, $insert_sql);
	}

	public function isSafeToDelete(PDO $link)
	{
		return false;
	}

	public $id  = NULL;
	public $title = NULL;
	public $created = NULL;
	public $modified = NULL;
	public $created_by = NULL;

	public $frameworks = array();
	public $units = array();

}
