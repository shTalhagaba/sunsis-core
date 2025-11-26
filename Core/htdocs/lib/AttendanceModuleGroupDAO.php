<?php
/**
 * User DAO
 */
class AttendanceModuleGroupDAO
{
	public function __construct(PDO $link)
	{
		if(!$link)
		{
			throw new Exception("Valid PDO link required on creation");
		}
		$this->link = $link;
	}


	public function find($id)
	{

		$query = "SELECT * FROM `attendance_module_groups` WHERE id=" . addslashes((string)$id) . ";";
		$st = $this->link->query($query);

		$vo = new AttendanceModuleGroupVO();
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
			throw new Exception("Could not execute database query to find record. ");
		}


		return $vo;
	}


	public function insert(CourseGroupVO $vo)
	{
		$exclude = array('id');
		$query = "INSERT INTO `attendance_module_groups` SET " . $vo->toNameValuePairs($exclude) . ';';
		$st = $this->link->query($query);

		if($st == false)
		{
			throw new Exception('Error inserting record with title ' . $vo->title . ' ' . $st->errorCode() . '-----' . $query);
		}

		// Record id of new course
		$vo->id = $this->link->lastInsertId();

		return $vo->id; // return assigned ID of the new course
	}


	public function update(CourseGroupVO $vo)
	{
		$exclude = array('id');
		$query = "UPDATE `attendance_module_groups` SET " . $vo->toNameValuePairs($exclude) . ' WHERE id=' . $vo->id . ';';
		$st = $this->link->query($query);

		if($st == false)
		{
			throw new Exception('Error updating record with id ' . $vo->id . ' ' . $this->link->errorCode() . '-----' . $query);
		}

		return true;
	}


	public function delete($link, $id)
	{
		if(!is_numeric($id))
		{
			throw new Exception("You must specify a numeric id in order to delete a record.");
		}

		if(!$this->isSafeToDelete($id))
		{
			throw new Exception("The group cannot be deleted because statistically significant register entries exist for it");
		}

		// Get module, pot and student records to update later
		$module_id = "SELECT module_id FROM attendance_module_groups WHERE id IN ($id) ORDER BY module_id ";
		$module_id = DAO::getSingleColumn($this->link, $module_id);

		$pot_ids = <<<HEREDOC
SELECT
	tr_id
FROM
	group_members INNER JOIN attendance_module_groups
	ON attendance_module_groups.id = group_members.groups_id
WHERE
	attendance_module_groups.id IN ($id)
HEREDOC;
		$pot_ids = DAO::getSingleColumn($this->link, $pot_ids);

		$student_ids = <<<HEREDOC
SELECT
	CONCAT("'", users.username, "'")
FROM
	users INNER JOIN tr 
	ON tr.username = users.username
	INNER JOIN group_members 
	ON group_members.tr_id = tr.id
	INNER JOIN attendance_module_groups
	ON attendance_module_groups.id = group_members.groups_id
WHERE
	attendance_module_groups.id IN ($id)
HEREDOC;
		$student_ids = DAO::getSingleColumn($this->link, $student_ids);


		// Deletion statement
		$query = <<<HEREDOC
DELETE FROM
	attendance_module_groups, group_members, lessons, lesson_notes, register_entries, register_entry_notes 
USING
	attendance_module_groups
	LEFT OUTER JOIN group_members ON attendance_module_groups.id = group_members.groups_id
	LEFT OUTER JOIN lessons ON lessons.groups_id = attendance_module_groups.id
	LEFT OUTER JOIN lesson_notes ON lessons.id = lesson_notes.lessons_id
	LEFT OUTER JOIN register_entries ON lessons.id = register_entries.lessons_id
	LEFT OUTER JOIN register_entry_notes ON register_entries.id = register_entry_notes.register_entries_id
	#LEFT OUTER JOIN attendance_reports_module ON register_entries.lessons_id = attendance_reports_module.lesson_id
WHERE
	attendance_module_groups.id IN ($id)
HEREDOC;
		$st = $this->link->query($query);
		if($st== false)
		{
			throw new Exception("Could not delete attendance module group #$id. " . $st->errorCode());
		}

		$attendance_module = new AttendanceModule();
		$attendance_module->updateAttendanceStatistics($this->link, $module_id);
		$pot_dao = new PotDAO($this->link);
		$pot_dao->updateAttendanceStatistics($link, $pot_ids);
		$student_dao = new StudentDAO($this->link);
		$student_dao->updateAttendanceStatistics($student_ids);


		return true;
	}


	public function isSafeToDelete($id)
	{
		// A group is safe to delete if there are no register entries for it
		// REMEMBER to delete lessons associated with the group too
		$num_register_entries = <<<HEREDOC
SELECT
	COUNT(*)
FROM
	lessons INNER JOIN register_entries ON lessons.id = register_entries.lessons_id
WHERE
	lessons.groups_id = $id AND register_entries.entry != 8;
HEREDOC;
		$num_register_entries = DAO::getSingleValue($this->link, $num_register_entries);

		return $num_register_entries == 0;
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
			throw new Exception('Could not update attendance module group attendance statistics. ' . $st->errorCode() );
		}
	}

	private $link = null;
}
?>