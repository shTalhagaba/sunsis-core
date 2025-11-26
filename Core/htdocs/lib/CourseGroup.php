<?php
/**
 * User DAO
 */
class CourseGroup
{
	public function __construct(PDO $link)
	{
		if(!$link)
		{
			throw new Exception("Valid PDO link required on creation");
		}
		$this->link = $link;
	}


	public function loadFromDatabase($link, $id)
	{

		$query = "SELECT * FROM `groups` WHERE id=" . addslashes((string)$id) . ";";
		$st = $link->query($query);
		$vo = new CourseGroup($link);
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
			throw new Exception("Could not execute database query to find record. " . $link->errorCode());
		}


		return $vo;
	}


	public function insert(CourseGroupVO $vo)
	{
		$exclude = array('id');
		$query = "INSERT INTO `groups` SET " . $vo->toNameValuePairs($exclude) . ';';

		$st = $this->link->query($query);	

		if($st== false)
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
		$query = "UPDATE `groups` SET " . $vo->toNameValuePairs($exclude) . ' WHERE id=' . $vo->id . ';';
		$st = $this->link->query($query);

		if($st == false)
		{
			throw new Exception('Error updating record with id ' . $vo->id . ' ' . $st->errorCode() . '-----' . $query);
		}

		return true;
	}


	public function delete($link, $id)
	{
		if(!is_numeric($id))
		{
			throw new Exception("You must specify a numeric id in order to delete a record.");
		}

        if(DB_NAME!='am_reed' && DB_NAME!='am_reed_demo')
            if(!$this->isSafeToDelete($id))
            {
                throw new Exception("The group cannot be deleted because statistically significant register entries exist for it");
            }
		
		// Get course, pot and student records to update later
		$course_ids = "SELECT courses_id FROM groups WHERE id IN ($id) ORDER BY courses_id";
		$course_ids = DAO::getSingleColumn($this->link, $course_ids);
		
		$pot_ids = <<<HEREDOC
SELECT
	pot_id
FROM
	groups INNER JOIN group_members
	ON groups.id = group_members.groups_id
WHERE
	groups.id IN ($id)
HEREDOC;
		$pot_ids = DAO::getSingleColumn($this->link, $pot_ids);
		
		$student_ids = <<<HEREDOC
SELECT
	students_id
FROM
	groups INNER JOIN group_members INNER JOIN pot
	ON groups.id = group_members.groups_id
WHERE
	groups.id IN ($id)
HEREDOC;
		$student_ids = DAO::getSingleColumn($this->link, $student_ids);
		
		
		// Deletion statement
		$query = <<<HEREDOC
DELETE FROM
	groups, group_members, lessons, lesson_notes, register_entries, register_entry_notes, attendance_reports
USING
	groups LEFT OUTER JOIN group_members ON groups.id = group_members.groups_id
	LEFT OUTER JOIN lessons ON lessons.groups_id = groups.id
	LEFT OUTER JOIN lesson_notes ON lessons.id = lesson_notes.lessons_id
	LEFT OUTER JOIN register_entries ON lessons.id = register_entries.lessons_id
	LEFT OUTER JOIN register_entry_notes ON register_entries.id = register_entry_notes.register_entries_id
	LEFT OUTER JOIN attendance_reports ON register_entries.lessons_id = attendance_reports.lesson_id
WHERE
	groups.id IN ($id)	
HEREDOC;
		
		$st = $this->link->query($query);
		if($st== false)
		{
			throw new Exception("Could not delete group #$id. " . $st->errorCode());
		}
		
		
		$course = new Course();
		$course->updateAttendanceStatistics($this->link, $course_ids);
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
		
		return $num_register_entries === 0;
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
	groups INNER JOIN		
		(SELECT
			groups.id AS groups_id,
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
			groups INNER JOIN lessons
			ON (groups.id = lessons.groups_id)
      		LEFT OUTER JOIN register_entries ON lessons.id = register_entries.lessons_id
		WHERE
			groups.id IN ($id)
    	GROUP BY
     		groups.id) AS stats
	ON groups.id = stats.groups_id
SET
	groups.scheduled_lessons = stats.scheduled_lessons,
	groups.registered_lessons = stats.registered_lessons,
	groups.attendances = stats.attendances,
	groups.lates = stats.lates,
	groups.very_lates = stats.very_lates,
	groups.authorised_absences = stats.authorised_absences,
	groups.unexplained_absences = stats.unexplained_absences,
	groups.unauthorised_absences = stats.unauthorised_absences,
	groups.dismissals_uniform = stats.dismissals_uniform,
	groups.dismissals_discipline = stats.dismissals_discipline;
HEREDOC;
	
		$st = $this->link->query($sql);	
		if($st== false)
		{
			throw new Exception('Could not update group attendance statistics. ' . $st->errorCode() );
		}
	}
	
	private $link = null;

	public $id = 0;
	public $courses_id = 0;
	public $title = NULL;
	public $tutor = NULL;
	public $old_tutor = NULL;
	public $assessor = NULL;
	public $old_assessor = NULL;
	public $verifier = NULL;
	public $old_verifier = NULL;
	
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

    public $start_date = NULL;
    public $end_date = NULL;
    public $capacity = NULL;
    public $status = NULL;
    public $group_capacity = NULL;
}
?>