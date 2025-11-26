<?php
class AttendanceModuleGroup extends Entity
{

	public static function loadFromDatabase(PDO $link, $id)
	{
		if($id == '')
		{
			return null;
		}

		if(is_numeric($id))
		{
			$query = "SELECT * FROM attendance_module_groups WHERE id=" . addslashes((string)$id);
		}
		else
		{
			$query = "SELECT * FROM attendance_module_groups WHERE title='" . addslashes((string)$id) . "';";
		}

		$attendance_module_group = new AttendanceModuleGroup();
		$st = $link->query($query);
		if($st)
		{
			$row = $st->fetch();
			if($row)
			{
				$attendance_module_group->populate($row);
			}
			else
			{
				return null;
			}

		}
		else
		{
			throw new DatabaseException($link, $query);
		}

		$sql = "SELECT tr_id FROM group_members WHERE groups_id=" . $attendance_module_group->id . ";";
		$attendance_module_group->members = DAO::getSingleColumn($link, $sql);

		return $attendance_module_group;
	}

	public function insert(AttendanceModuleGroupVO $vo)
	{
		$exclude = array('id');
		$query = "INSERT INTO `attendance_module_groups` SET " . $vo->toNameValuePairs($exclude) . ';';

		$st = $this->link->query($query);

		if($st== false)
		{
			throw new Exception('Error inserting record with title ' . $vo->title . ' ' . $st->errorCode() . '-----' . $query);
		}

		// Record id of new course
		$vo->id = $this->link->lastInsertId();

		return $vo->id; // return assigned ID of the new course
	}

	public function update(AttendanceModuleGroupVO $vo)
	{
		$exclude = array('id');
		$query = "UPDATE `attendance_module_groups` SET " . $vo->toNameValuePairs($exclude) . ' WHERE id=' . $vo->id . ';';
		$st = $this->link->query($query);

		if($st == false)
		{
			throw new Exception('Error updating record with id ' . $vo->id . ' ' . $st->errorCode() . '-----' . $query);
		}

		return true;
	}

	public function save(PDO $link)
	{
		if($this->id != '')
		{

		}

		DAO::saveObjectToTable($link, 'attendance_module_groups', $this);

		$values = '';
		foreach($this->members as $member)
		{
			if(strlen($values) > 0)
			{
				$values .= ',';
			}
			$values .= "({$this->id}, '".addslashes((string)$member)."')";
		}

		if(strlen($values) > 0)
		{
			DAO::execute($link, "DELETE FROM group_members WHERE groups_id = " . $this->id);
			$sql = "INSERT INTO group_members (groups_id, tr_id) VALUES $values";
			DAO::execute($link, $sql);
		}

	}

	public function setMembers(PDO $link, array $members)
	{
		$this->members = $this->trim_array_elements($members);
	}

	public function delete(PDO $link)
	{
		$query = <<<HEREDOC
DELETE FROM
	attendance_module_groups, group_members
USING
	attendance_module_groups LEFT OUTER JOIN group_members ON attendance_module_groups.id = group_members.groups_id
WHERE
	attendance_module_groups.id = '{$this->id}';
HEREDOC;
		DAO::execute($link, $query);

		return true;
	}


	public function isSafeToDelete(PDO $link)
	{
		//todo to be finalized yet
	}

	/**
	 * @param mixed $id A numeric id, an array of numeric ids or a SQL
	 * query that produces a list of numeric ids
	 */
	public function updateAttendanceStatistics($id)
	{
		if(is_array($id))
		{
			$id = implode(',', $id);
		}

		if($id == '')
		{
			return;
		}

		$sql = <<<HEREDOC
UPDATE
	attendance_module_groups INNER JOIN
		(SELECT
			attendance_module_groups.id AS groups_id,
      		COUNT(DISTINCT lessons.id) AS `scheduled_lessons`,
			COUNT(DISTINCT IF(entry IS NOT NULL,lessons.id,null)) AS `registered_lessons`,
			COUNT(IF(entry=1,1,null)) AS `attendances`,
			COUNT(IF(entry=2,1,null)) AS `lates`,
			COUNT(IF(entry=9,1,null)) AS `very_lates`,
			COUNT(IF(entry=3,1,null)) AS `authorised_absences`,
			COUNT(IF(entry=4,1,null)) AS `unexplained_absences`,
			COUNT(IF(entry=5,1,null)) AS `unauthorised_absences`,
			COUNT(IF(entry=6,1,null)) AS `dismissals_uniform`,
			COUNT(IF(entry=7,1,null)) AS `dismissals_discipline`
		FROM
			attendance_module_groups INNER JOIN lessons
			ON (attendance_module_groups.id = lessons.groups_id)
      		LEFT OUTER JOIN register_entries ON lessons.id = register_entries.lessons_id
		WHERE
			attendance_module_groups.id IN ($id)
    	GROUP BY
     		attendance_module_groups.id) AS stats
	ON attendance_module_groups.id = stats.groups_id
SET
	attendance_module_groups.scheduled_lessons = stats.scheduled_lessons,
	attendance_module_groups.registered_lessons = stats.registered_lessons,
	attendance_module_groups.attendances = stats.attendances,
	attendance_module_groups.lates = stats.lates,
	attendance_module_groups.very_lates = stats.very_lates,
	attendance_module_groups.authorised_absences = stats.authorised_absences,
	attendance_module_groups.unexplained_absences = stats.unexplained_absences,
	attendance_module_groups.unauthorised_absences = stats.unauthorised_absences,
	attendance_module_groups.dismissals_uniform = stats.dismissals_uniform,
	attendance_module_groups.dismissals_discipline = stats.dismissals_discipline;
HEREDOC;

		$st = $this->link->query($sql);
		if($st== false)
		{
			throw new Exception('Could not update group attendance statistics. ' . $st->errorCode() );
		}
	}

	private function trim_array_elements(array $a)
	{
		$b = array();
		foreach($a as $element)
		{
			$trimmed = trim($element);
			if(strlen($trimmed) > 0)
			{
				$b[] = $trimmed;
			}
		}

		return $b;
	}

	public $id = NULL;
	public $module_id = NULL;
	public $title = NULL;
	public $tutor = NULL;
	public $assessor = NULL;
	public $verifier = NULL;
	public $wbcoordinator = NULL;

	private $members = array();
}
?>