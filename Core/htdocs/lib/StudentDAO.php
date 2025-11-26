<?php
/**
 * User DAO
 */
class StudentDAO
{
	public function __construct($link)
	{
		if(!$link)
		{
			throw new Exception("Valid PDO link on creation");
		}
		$this->link = $link;
	}


	public function find($id)
	{

		$query = "SELECT * FROM tr WHERE id=" . addslashes((string)$id) . ";";
		$st = $this->link->query($query);	
		$vo = new StudentVO();
		if($st)
		{
			$row = $st->fetch();
			if($row)
			{
				$vo->populate($row);
			}
			else
			{
				return null;
			}
		}
		else
		{
			throw new Exception("Could not execute database query to find learner. " . $st->errorCode());
		}


		return $vo;
	}


	public function insert(StudentVO $vo)
	{
		// Calcuate KeyStage 4 and KeyStage 5 years
		$dob_timestamp = Date::parseDate($vo->dob);
		if(is_null($dob_timestamp))
		{
			throw new Exception("You must enter a date of birth");
		}
		$dob_elements = getdate($dob_timestamp);
		if($dob_elements['mon'] >= 9)
		{
			$vo->ks4 = ($dob_elements['year'] + 15) . '-09-01';
			$vo->ks5 = ($dob_elements['year'] + 17) . '-09-01';
		}
		else
		{
			$vo->ks4 = ($dob_elements['year'] + 14) . '-09-01';
			$vo->ks5 = ($dob_elements['year'] + 16) . '-09-01';			
		}
		
		$exclude = array('id');
		$query = "INSERT INTO users SET " . $vo->toNameValuePairs($exclude) . ';';
		$st = $this->link->query($query);
		if($st== false)
		{
			throw new Exception("Error inserting learner with name " . $vo->firstnames . " " . $vo->surname . " -- " . $st->errorCode() . $query);
		}
		
		$vo->id = $this->link->lastInsertId();

		return $vo->id;
	}


	public function update(StudentVO $vo)
	{
		// Calcuate KeyStage 4 and KeyStage 5 years
		$dob_timestamp = Date::parseDate($vo->dob);
		if(is_null($dob_timestamp))
		{
			throw new Exception("You must enter a date of birth");
		}
		$dob_elements = getdate($dob_timestamp);
		if($dob_elements['mon'] >= 9)
		{
			$vo->ks4 = ($dob_elements['year'] + 15) . '-09-01';
			$vo->ks5 = ($dob_elements['year'] + 17) . '-09-01';
		}
		else
		{
			$vo->ks4 = ($dob_elements['year'] + 14) . '-09-01';
			$vo->ks5 = ($dob_elements['year'] + 16) . '-09-01';			
		}
		
		$exclude = array('id');
		$query = "UPDATE users SET " . $vo->toNameValuePairs($exclude) . ' WHERE id=' . $vo->id . ';';
		$st = $this->link->query($query);

		if($st == false)
		{
			throw new Exception("Error updating learner with id " . $vo->id . " " . $st->errorCode() . $query);
		}

		return true;
	}


	public function delete($id)
	{
		if(!is_numeric($id))
		{
			throw new Exception("You must specify a numeric id in order to delete a student.");
		}

		if(!$this->isSafeToDelete($id))
		{
			throw new Exception("Student #$id cannot be deleted because they have one or more active training records");
		}
		
		// Get record IDs to update after the deletion
		$group_ids = <<<HEREDOC
SELECT DISTINCT
	groups_id
FROM
	users INNER JOIN tr INNER JOIN group_members
	ON (users.username = tr.username AND tr.id = group_members.tr_id)
WHERE 
	users.username IN ($id)
ORDER BY
	groups_id
HEREDOC;
		$group_ids = DAO::getSingleColumn($this->link, $group_ids);
		
		$course_ids = <<<HEREDOC
SELECT DISTINCT
	tr.courses_id
FROM
	users INNER JOIN tr
	ON (users.username = tr.username)
WHERE 
	users.username IN ($id)
ORDER BY
	courses_id
HEREDOC;
		$course_ids = DAO::getSingleColumn($this->link, $course_ids);
		
		
		// Main deletion routine
		$query = <<<HEREDOC
DELETE FROM
	users, tr, pot_notes, pot_unit_progress, pot_overall_progress, group_members, register_entries, register_entry_notes, attendance_reports
USING
	users LEFT OUTER JOIN tr ON users.username = tr.username
	LEFT OUTER JOIN pot_notes ON tr.id = pot_notes.pot_id
	LEFT OUTER JOIN pot_unit_progress ON tr.id = pot_unit_progress.pot_id
	LEFT OUTER JOIN pot_overall_progress ON tr.id = pot_overall_progress.pot_id
	LEFT OUTER JOIN group_members ON group_members.tr_id = tr.id
	LEFT OUTER JOIN register_entries ON register_entries.pot_id = tr.id
	LEFT OUTER JOIN register_entry_notes ON register_entry_notes.register_entries_id = register_entries.id
	LEFT OUTER JOIN attendance_reports ON tr.id = attendance_reports.pot_id
WHERE
	users.username = $id;
HEREDOC;
		$st = $this->link->query($query);
		if($st== false)
		{
			throw new Exception("Could not delete student with id $id. " . $st->errorCode());
		}
		
		
		// Update group and course statistics
		$group_dao = new CourseGroupDAO($this->link);
		$group_dao->updateAttendanceStatistics($group_ids);
		$course = new Course();
		$course->updateAttendanceStatistics($this->link, $course_ids);
				
		return true;
	}
	
	/**
	 * A student can be deleted if the student has no register entries
	 * under any training records they may have.
	 */
	public function isSafeToDelete($record_id)
	{
		$register_entries = <<<HEREDOC
SELECT
	COUNT(*)
FROM
	tr INNER JOIN register_entries
	ON tr.id = register_entries.pot_id
WHERE
	tr.username = $record_id AND
	register_entries.entry != 8;
HEREDOC;
		$register_entries = DAO::getSingleValue($this->link, $register_entries);
		
		return $register_entries == 0;
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
	tr INNER JOIN
		(SELECT
			tr.id AS students_id,
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
			AND lessons.date >= tr.start_date AND IF(tr.closure_date IS NULL, TRUE, lessons.date <= tr.closure_date) )
			LEFT OUTER JOIN register_entries ON tr.id=register_entries.pot_id AND lessons.id = register_entries.lessons_id
		WHERE
			tr.id IN ($id)
		GROUP BY
			tr.username) AS stats
	ON tr.id = stats.students_id
SET    
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
			throw new Exception($st->errorCode().'............'.$sql, $st->errorCode());
		}
	}

	private $link = null;
}
?>