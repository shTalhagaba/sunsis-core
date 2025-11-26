<?php
/**
 * User DAO
 */
class PotDAO
{
	public function __construct($link)
	{
		if(!$link)
		{
			throw new Exception("PotDAO requires a valid PDO link on creation");
		}
		$this->link = $link;
	}


	public function find($id)
	{

		$query = "SELECT * FROM tr WHERE id=" . addslashes((string)$id) . ";";
		$st = $this->link->query($query);

		$vo = new PotVO();
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


	public function insert(PotVO $vo)
	{
		// Check student is not already enrolled on this course
		$existing_enrollments = <<<HEREDOC
SELECT
	COUNT(*)
FROM
	tr
WHERE
	username={$vo->students_id} AND
	courses_id={$vo->courses_id} AND
	status_code=1;
HEREDOC;
		$existing_enrollments = DAO::getSingleValue($this->link, $existing_enrollments);
		if($existing_enrollments > 0)
		{
			throw new Exception("This student is already actively engaged in the course you have selected");
		}

		$exclude = array('id');
		$query = "INSERT INTO tr SET " . $vo->toNameValuePairs($exclude) . ';';
		$st = $this->link->query($query);
		$vo->id = $this->link->lastInsertId();

		return $vo->id; // return assigned ID of the new course
	}


	public function update(PotVO $vo)
	{
		$exclude = array('id');
		$query = "UPDATE tr SET " . $vo->toNameValuePairs($exclude) . ' WHERE id=' . $vo->id . ';';
		$st = $this->link->query($query);
		if($st== false)
		{
			throw new Exception('Error updating record with id ' . $vo->id . ' ' . $st->errorCode() . '-----' . $query);
		}

		return true;
	}


	public function delete($id)
	{
		if(!is_numeric($id))
		{
			throw new Exception("You must specify a numeric id in order to delete a record.");
		}

		if(!$this->isSafeToDelete($id))
		{
			throw new Exception("Once a training record has register entries against it, it cannot be deleted. Close the record instead.");
		}


		$query = <<<HEREDOC
DELETE FROM
	tr, pot_notes, group_members, register_entries, register_entry_notes
USING
	tr LEFT OUTER JOIN pot_notes ON tr.id = pot_notes.pot_id
	LEFT OUTER JOIN group_members ON group_members.pot_id = tr.id
	LEFT OUTER JOIN register_entries ON register_entries.pot_id = tr.id
	LEFT OUTER JOIN register_entry_notes ON register_entry_notes.register_entries_id = register_entries.id
WHERE
	tr.id = $id;
HEREDOC;
		$st = $this->link->query($query);
		if($st== false)
		{
			throw new Exception("Could not delete record with id $id. " . $st->errorCode());
		}


		return true;

	}


	public function isSafeToDelete($id)
	{
		// Training records can be deleted if there are no register entries for
		// the record yet.  This will avoid early data-entry mistakes clogging
		// up the system and confusing reports.
		if(is_array($id))
		{
			sort($id);
			$ids = implode(',', $id);
			$num_register_entries = "SELECT COUNT(*) FROM register_entries WHERE pot_id IN($ids) AND entry != 8;";
		}
		else
		{
			$num_register_entries = "SELECT COUNT(*) FROM register_entries WHERE pot_id=$id AND entry != 8;";
		}

		$num_register_entries = DAO::getSingleValue($this->link, $num_register_entries);

		return $num_register_entries == 0;
	}


	/**
	 * @param mixed $id A numeric id, an array of numeric ids or a SQL
	 * query that produces a list of numeric ids
	 */
	public function updateAttendanceStatistics($link, $id)
	{
		if(is_array($id))
		{
			// List of IDs
			$id = implode(',', $id);
			$where_clause = "tr.id IN ($id)";
		}
		elseif(is_numeric($id))
		{
			// A single ID
			$where_clause = "tr.id = $id";
		}
		else
		{
			// A SQL query (we assume)
			$where_clause = 'tr.id IN ('.$id.')';
		}

		// The number of scheduled lessons for late-starters is adjusted
		// according to their actual start-date.  Early-finishers do not receive
		// the same treatment.  However, should this need to be added, the SQL
		// is: IF(pot.closure_date IS NULL, TRUE, lessons.date <= pot.closure_date)
		$sql = <<<HEREDOC
UPDATE
	tr INNER JOIN
		(SELECT
			tr.id AS pot_id,
			tr.modified,
			COUNT(lessons.id) AS 'scheduled_lessons',
			COUNT(entry) AS 'registered_lessons',
			COUNT(IF(entry=1,1,null)) AS 'attendances',
			COUNT(IF(entry=2,1,null)) AS 'lates',
			COUNT(IF(entry=9,1,null)) AS 'very_lates',
			COUNT(IF(entry=3,1,null)) AS 'authorised_absences',
			COUNT(IF(entry=4,1,null)) AS 'unexplained_absences',
			COUNT(IF(entry=5,1,null)) AS 'unauthorised_absences',
			COUNT(IF(entry=6,1,null)) AS 'dismissals_uniform',
			COUNT(IF(entry=7,1,null)) AS 'dismissals_discipline'
		FROM
			tr INNER JOIN group_members INNER JOIN lessons
			ON (tr.id = group_members.tr_id AND group_members.groups_id = lessons.groups_id
			AND lessons.date >= tr.start_date)
			LEFT OUTER JOIN register_entries ON tr.id=register_entries.pot_id AND lessons.id = register_entries.lessons_id
		GROUP BY
			tr.id) AS stats
	ON tr.id = stats.pot_id
SET
	tr.modified = stats.modified, # Suppress auto-update of the TIMESTAMP field #
	tr.scheduled_lessons = stats.scheduled_lessons,
	tr.registered_lessons = stats.registered_lessons,
	tr.attendances = stats.attendances,
	tr.lates = stats.lates,
	tr.very_lates = stats.very_lates,
	tr.authorised_absences = stats.authorised_absences,
	tr.unexplained_absences = stats.unexplained_absences,
	tr.unauthorised_absences = stats.unauthorised_absences,
	tr.dismissals_uniform = stats.dismissals_uniform,
	tr.dismissals_discipline = stats.dismissals_discipline;
HEREDOC;
		$st = $this->link->query($sql);
		if($st== false)
		{
			throw new Exception("Could not update attendance statistics." . $link->errorCode());
		}
	}


	public function updateProgressStatistics($id)
	{
		if(is_array($id))
		{
			$id = implode(',', $id); // List of IDs
		}


		$sql = <<<HEREDOC
UPDATE
	tr INNER JOIN
		(SELECT
			id,
			COUNT(*) AS units_total,
			COUNT(IF(progress=-2 OR progress IS NULL, 1, NULL)) AS units_not_started,
			COUNT(IF(progress=-1, 1, NULL)) AS units_behind,
			COUNT(IF(progress=0, 1, NULL)) AS units_on_track,
			COUNT(IF(progress=1, 1, NULL)) AS units_under_assessment,
			COUNT(IF(progress=2, 1, NULL)) AS units_completed
		FROM
			(SELECT DISTINCT
				tr.id AS pot_id,
				course_qualification_units.unit_id,
				course_qualification_units.ordinal,
				pot_unit_progress.progress
			FROM
				tr INNER JOIN course_qualification_units
				ON tr.courses_id = course_qualification_units.courses_id
				LEFT OUTER JOIN pot_unit_progress
				ON (tr.id = pot_unit_progress.pot_id AND course_qualification_units.unit_id = pot_unit_progress.unit_id)
				LEFT OUTER JOIN pot_structure
				ON (pot_structure.structure_ordinal=course_qualification_units.ordinal)
			WHERE
				course_qualification_units.unit_id IS NOT NULL
				AND (pot_structure.visibility IS NULL OR pot_structure.visibility = 1)
				AND tr.id IN ($id)
			ORDER BY
				tr.id) AS raw_stats
		GROUP BY
			pot_id) AS stats
	ON tr.id = stats.pot_id
SET
	tr.units_total = stats.units_total,
	tr.units_not_started = stats.units_not_started,
	tr.units_behind = stats.units_behind,
	tr.units_on_track = stats.units_on_track,
	tr.units_under_assessment = stats.units_under_assessment,
	tr.units_completed = stats.units_completed;
HEREDOC;

		$st = $this->link->query($sql);

		if($st== false)
		{
			throw new DatabaseException($this->link, $sql);
		}

	}


	private $link = null;
}
?>