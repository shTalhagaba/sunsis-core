<?php
class AttendanceModule extends Entity
{
	public static function loadFromDatabase(PDO $link, $id)
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
	attendance_modules
WHERE
	id='$key';
HEREDOC;
		$st = $link->query($query);

		$module = null;
		if($st)
		{
			$module = null;
			$row = $st->fetch();
			if($row)
			{
				$module = new AttendanceModule();
				$module->populate($row);
			}

		}
		else
		{
			throw new Exception("Could not execute database query to find module for the training record. " . '----' . $query . '----' . $link->errorCode());
		}

		return $module;
	}

	public function save(PDO $link)
	{
		$this->modified = "";
		$this->created = ($this->id == "") ? date('Y-m-d H:i:s') : $this->created;

		return DAO::saveObjectToTable($link, 'attendance_modules', $this);
	}

	public function delete(PDO $link)
	{

	}

	/**
	 * Updates attendance statistics based on the statistics of teaching groups
	 * in the module.  Therefore be sure that the teaching groups are up to
	 * date before running this method.
	 *
	 * @param mixed $id A numeric id, an array of numeric ids or a SQL
	 * query that produces a list of numeric ids
	 */
	public function updateAttendanceStatistics(PDO $link, $attendance_modules_ids = null)
	{
		if(is_null($attendance_modules_ids))
		{
			$attendance_modules_ids = $this->id;
		}

		if(is_array($attendance_modules_ids))
		{
			// List of IDs
			sort($attendance_modules_ids);
			$attendance_modules_ids = implode(',', $attendance_modules_ids);
		}

		if($attendance_modules_ids == '')
		{
			return;
		}


		$sql = <<<HEREDOC
UPDATE
	attendance_modules INNER JOIN
		(SELECT
			attendance_module_groups.module_id AS module_id,
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
      		attendance_module_groups.module_id IN ($attendance_modules_ids)
   	GROUP BY
     		attendance_module_groups.module_id) AS stats
	ON attendance_modules.id = stats.module_id
SET
	attendance_modules.scheduled_lessons = stats.scheduled_lessons,
	attendance_modules.registered_lessons = stats.registered_lessons,
	attendance_modules.attendances = stats.attendances,
	attendance_modules.lates = stats.lates,
	attendance_modules.very_lates = stats.very_lates,
	attendance_modules.authorised_absences = stats.authorised_absences,
	attendance_modules.unexplained_absences = stats.unexplained_absences,
	attendance_modules.unauthorised_absences = stats.unauthorised_absences,
	attendance_modules.dismissals_uniform = stats.dismissals_uniform,
	attendance_modules.dismissals_discipline = stats.dismissals_discipline;
HEREDOC;
		DAO::execute($link, $sql);
	}

	public function isSafeToDelete(PDO $link)
	{
		return false;
	}



	public $id = NULL;
	public $module_title = NULL;
	public $qualification_id = NULL;
	public $qualification_title = NULL;
	public $hours = NULL;
	public $provider_id = NULL;

	public $created = NULL;
	public $modified = NULL;

	// ATTENDANCE STATISTICS
	public $scheduled_lessons = null;
	public $registered_lessons = null;
	public $attendances = null;
	public $lates = null;
	public $very_lates = null;
	public $authorised_absences = null;
	public $unexplained_absences = null;
	public $unauthorised_absences = null;
	public $dismissals_uniform = null;
	public $dismissals_discipline = null;
	public $attendance_not_required = null;


}
?>