<?php
/**
 * User DAO
 */
class LessonDAO
{
	public function __construct($link)
	{
		if(!$link)
		{
			throw new Exception("Sunesis requires a valid pdo link on creation");
		}
		$this->link = $link;
	}


	public function find($id)
	{

		$query = "SELECT * FROM lessons WHERE id=" . addslashes((string)$id) . ";";
		$st = $this->link->query($query);

		$vo = new LessonVO();
		if($st)
		{
			$row = $st->fetch();
			if($row)
			{
				$vo->populate($row);
			}
//			else
//			{
//				throw new Exception("Could not find a lesson with id $id in the database");
//			}
		}
		else
		{
			throw new DatabaseException($this->link, $query);
		}

		if(preg_match('/^(\d\d:\d\d)/', $vo->start_time, $matches))
		{
			$vo->start_time = $matches[1];
		}

		if(preg_match('/^(\d\d:\d\d)/', $vo->end_time, $matches))
		{
			$vo->end_time = $matches[1];
		}


		return $vo;
	}


	public function insert(LessonVO $vo, $belongs_to_attendance_module = false)
	{
		$exclude = array('id');
		$query = "INSERT INTO lessons SET " . $vo->toNameValuePairs($exclude) . ';';
		$st = $this->link->query($query);
		if($st== false)
		{
			throw new Exception("Error inserting lesson -- " . $this->link->errorCode());
		}
		
		$vo->id = $this->link->lastInsertId();
		
		if(!$belongs_to_attendance_module)
		{		
			// Add an entry into the attendance reports table
			$query = <<<HEREDOC
REPLACE INTO attendance_reports (lesson_id, provider_id, course_id, group_id, `date`)
SELECT
	lessons.id AS lesson_id,
	courses.organisations_id AS provider_id,
	courses.id AS course_id,
	lessons.groups_id AS group_id,
	lessons.date AS lesson_date
FROM
	lessons INNER JOIN groups INNER JOIN courses
	ON (lessons.groups_id = groups.id AND groups.courses_id = courses.id)
WHERE
	lessons.id={$vo->id};
HEREDOC;
		}
		else
		{
			// Add an entry into the attendance reports table
			$query = <<<HEREDOC
REPLACE INTO attendance_reports_module (lesson_id, provider_id, module_id, group_id, `date`)
SELECT
	lessons.id AS lesson_id,
	attendance_modules.provider_id AS provider_id,
	attendance_modules.id AS module_id,
	lessons.groups_id AS group_id,
	lessons.date AS lesson_date
FROM
	lessons INNER JOIN attendance_module_groups INNER JOIN attendance_modules
	ON (lessons.groups_id = attendance_module_groups.id AND attendance_module_groups.module_id = attendance_modules.id)
WHERE
	lessons.id={$vo->id};
HEREDOC;
		}
		
		$st = $this->link->query($query);
		if($st== false)
		{
			throw new Exception("Error updating attendance reports table -- " . $st->errorCode());
		}
		
		return $vo->id;
	}


	public function update(LessonVO $vo)
	{
		$exclude = array('id');
		$query = "UPDATE lessons SET " . $vo->toNameValuePairs($exclude) . ' WHERE id=' . $vo->id . ';';
		$st = $this->link->query($query);	
		if($st== false)
		{
			throw new Exception("Error updating lesson -- " . $st->errorCode());
		}
		
		$sql = <<<HEREDOC
UPDATE
	attendance_reports INNER JOIN
		(SELECT
			lessons.id AS lesson_id,
			courses.organisations_id AS provider_id,
			courses.id AS course_id,
			lessons.groups_id AS group_id,
			lessons.date AS lesson_date
		FROM
			lessons INNER JOIN groups INNER JOIN courses
			ON (lessons.groups_id = groups.id AND groups.courses_id = courses.id)
		WHERE
			lessons.id={$vo->id}) AS lesson
	ON attendance_reports.lesson_id = lesson.lesson_id
SET
	attendance_reports.provider_id = lesson.provider_id,
	attendance_reports.course_id = lesson.course_id,
	attendance_reports.group_id = lesson.group_id,
	attendance_reports.date = lesson.lesson_date;
HEREDOC;

		$st = $this->link->query($query);
		if($st== false)
		{
			throw new Exception("Error updating attendance reports table -- " . $st->errorCode());
		}
		
		return true;
	}


	public function delete($id)
	{
		if(!is_numeric($id))
		{
			throw new Exception("You must specify a numeric id.");
		}

		if(!$this->isSafeToDelete($id))
		{
			throw new Exception("Lesson #$id cannot be deleted because register entries exist for it.");
		}
		
		$query = <<<HEREDOC
DELETE FROM
	lessons, lesson_notes, register_entries, register_entry_notes, attendance_reports
USING
	lessons LEFT OUTER JOIN lesson_notes ON lessons.id = lesson_notes.lessons_id
	LEFT OUTER JOIN register_entries ON lessons.id = register_entries.lessons_id
	LEFT OUTER JOIN register_entry_notes ON register_entries.id = register_entry_notes.register_entries_id
	LEFT OUTER JOIN attendance_reports ON register_entries.id = attendance_reports.lesson_id
WHERE
	lessons.id = $id;
HEREDOC;
		$st = $this->link->query($query);
		if($st== false)
		{
			throw new Exception("Could not delete lesson with id $id. " . $st->errorCode());
		}
		
		return true;
	}

	
	public function isSafeToDelete($id)
	{
		if(is_array($id))
		{
			sort($id, SORT_NUMERIC);
			$lesson_ids = implode(',', $id);
			$num_register_entries = "SELECT COUNT(*) FROM register_entries WHERE lessons_id IN($lesson_ids) AND entry != 8;";
		}
		else
		{
			$num_register_entries = "SELECT COUNT(*) FROM register_entries WHERE lessons_id=$id AND entry != 8;";
		}
		$num_register_entries = DAO::getSingleValue($this->link, $num_register_entries);
		
		return ($num_register_entries == 0);
	}
	
	
	/**
	 * @param mixed $id A numeric id, an array of numeric ids or a SQL
	 * query that produces a list of numeric ids
	 */
	public function updateAttendanceStatistics($id)
	{
		if(is_array($id))
		{
			// List of IDs
			$id = implode(',', $id);
			$where_clause = "lessons.id IN ($id)";
		}
		elseif(is_numeric($id))
		{
			// A single ID
			$where_clause = "lessons.id = $id";
		}
		else
		{
			// A SQL query (we assume)
			$where_clause = 'lessons.id IN ('.$id.')';
		}

		// The LEFT OUTER JOIN with register entries ensures that lessons
		// without any register entries will be included and updated. This is
		// only really important if we delete any register entries, which
		// should never, ever happen.
		$sql = <<<HEREDOC
UPDATE
	lessons INNER JOIN
		(SELECT
			lessons.id AS lessons_id,
			COUNT(IF(entry > 0 AND entry < 8,1,null)) AS `num_entries`,
			IF(SUM(entry) > 0, 1, 0) AS `registered_lessons`,
			COUNT(IF(entry=1,1,null)) AS `attendances`,
			COUNT(IF(entry=2,1,null)) AS `lates`,
			COUNT(IF(entry=9,1,null)) AS `very_lates`,
			COUNT(IF(entry=3,1,null)) AS `authorised_absences`,
			COUNT(IF(entry=4,1,null)) AS `unexplained_absences`,
			COUNT(IF(entry=5,1,null)) AS `unauthorised_absences`,
			COUNT(IF(entry=6,1,null)) AS `dismissals_uniform`,
			COUNT(IF(entry=7,1,null)) AS `dismissals_discipline`,
			COUNT(IF(entry=8,1,null)) AS `not_applicables`
		FROM
			lessons LEFT OUTER JOIN register_entries ON lessons.id = register_entries.lessons_id
		WHERE
			$where_clause
		GROUP BY
			lessons.id) AS stats
	ON lessons.id = stats.lessons_id
SET
	lessons.num_entries = stats.num_entries,
	lessons.scheduled_lessons = 1,
	lessons.registered_lessons = stats.registered_lessons,
	lessons.attendances = stats.attendances,
	lessons.lates = stats.lates,
	lessons.very_lates = stats.very_lates,
	lessons.authorised_absences = stats.authorised_absences,
	lessons.unexplained_absences = stats.unexplained_absences,
	lessons.unauthorised_absences = stats.unauthorised_absences,
	lessons.dismissals_uniform = stats.dismissals_uniform,
	lessons.dismissals_discipline = stats.dismissals_discipline,
	lessons.not_applicables = stats.not_applicables;
HEREDOC;

		$st = $this->link->query($sql);
		if($st == false)
		{
			throw new Exception('Could not update lesson attendance statistics. ' . $st->errorCode() );
		}
		
		/*
		$sql = <<<HEREDOC
REPLACE INTO attendance_reports (lesson_id, provider_id, course_id, group_id, `date`)
SELECT
	lessons.id AS lesson_id,
	courses.organisations_id AS provider_id,
	courses.id AS course_id,
	lessons.groups_id AS group_id,
	lessons.date AS lesson_date
FROM
	lessons INNER JOIN groups INNER JOIN courses
	ON (lessons.groups_id = groups.id AND groups.courses_id = courses.id)
WHERE
	$where_clause
HEREDOC;
		$st = $this->link->query($sql);
		if($st== false)
		{
			throw new Exception('Could not update lesson attendance statistics. ' . $st->errorCode() );
		}

		
		
		$sql = <<<HEREDOC
INSERT INTO attendance_reports (lesson_id, pot_id, school_id, provider_id, course_id, group_id, `date`, entry)
SELECT
	lessons.id AS lesson_id,
	register_entries.pot_id AS pot_id,
	register_entries.school_id AS school_id,
	courses.organisations_id AS provider_id,
	courses.id AS course_id,
	lessons.groups_id AS group_id,
	lessons.date AS lesson_date,
	register_entries.entry
FROM
	lessons INNER JOIN groups INNER JOIN courses INNER JOIN register_entries
	ON (lessons.groups_id = groups.id AND
	groups.courses_id = courses.id AND
	register_entries.lessons_id = lessons.id)
WHERE
	$where_clause
HEREDOC;
		$st = $this->link->query($sql);
		if($st== false)
		{
			throw new Exception('Could not update lesson attendance statistics. ' . $st->errorCode() );
		}	
		*/
	}
	
	
	private $link = null;
}
?>