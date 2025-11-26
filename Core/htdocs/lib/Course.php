<?php
class Course extends ValueObject
{
    public static function loadFromDatabase(PDO $link, $course_id)
    {
        $course = new Course();

        $sql = "SELECT * FROM courses WHERE id='".addslashes((string)$course_id)."';";
        $st = $link->query($sql);
        if($st)
        {
            if($row = $st->fetch())
            {
                $course->populate($row);
            }
            else
            {
                //throw new Exception("No course found with id $course_id");
            }

        }
        else
        {
            throw new DatabaseException($link, $sql);
        }

        return $course;
    }


    public function save(PDO $link)
    {
        if(!isset($this->active))
            $this->active=0;
        return DAO::saveObjectToTable($link, 'courses', $this);
    }


    public function delete(PDO $link)
    {


        $link->beginTransaction();
        try
        {

            // Get student records to update after deletion
            $student_ids = <<<HEREDOC
SELECT
	courses_tr.tr_id	
FROM
	courses INNER JOIN groups INNER JOIN group_members INNER JOIN tr
	ON (courses.id = groups.courses_id
	AND group_members.groups_id = groups.id
	AND tr.id = group_members.tr_id)
	LEFT JOIN courses_tr on courses_tr.course_id = courses.id
WHERE
	courses.id = $this->id
HEREDOC;
            $student_ids = DAO::getSingleColumn($link, $student_ids);


            // Main deletion statement
            $query = <<<HEREDOC
DELETE FROM
	courses,
	course_qualifications_dates,
	groups,
	group_members,
	lessons,
	lesson_notes,
	register_entries,
	register_entry_notes,
	attendance_reports,
	courses_tr,
	tr,
	ilr
USING
	courses 
	LEFT OUTER JOIN	course_qualifications_dates ON courses.id = course_qualifications_dates.course_id
	LEFT OUTER JOIN groups ON groups.courses_id = courses.id
	LEFT OUTER JOIN group_members ON groups.id = group_members.groups_id
	LEFT OUTER JOIN courses_tr on courses_tr.course_id = courses.id
	LEFT OUTER JOIN tr ON courses_tr.tr_id = tr.id
	LEFT OUTER JOIN lessons ON lessons.groups_id = groups.id
	LEFT OUTER JOIN lesson_notes ON lessons.id = lesson_notes.lessons_id
	LEFT OUTER JOIN register_entries ON lessons.id = register_entries.lessons_id
	LEFT OUTER JOIN register_entry_notes ON register_entries.id = register_entry_notes.register_entries_id
	LEFT OUTER JOIN attendance_reports ON register_entries.lessons_id = attendance_reports.lesson_id
	LEFT OUTER JOIN ilr on ilr.tr_id = tr.id
WHERE
	courses.id={$this->id};
HEREDOC;
            DAO::execute($link, $query);

            $note = new Note();
            $note->subject = "Course deleted";
            $note->parent_table = 'Course';
            $note->parent_id = $this->id;
            $note->note = 'Course Title = ' . $this->title . ', Start Date = ' . $this->course_start_date . ', End Date = ' . $this->course_end_date . ', ID = ' . $this->id . ' ';

            $note->save($link);

            // Update student statistics
            $student_dao = new StudentDAO($link);
            $student_dao->updateAttendanceStatistics($student_ids);

            $link->commit();
        }
        catch(Exception $e)
        {
            $link->rollback();
            throw new WrappedException($e);
        }


        return true;
    }

	public function learnersCount(PDO $link)
	{
		if($_SESSION['user']->isAdmin())
		{
			$sql = "SELECT COUNT(*) FROM courses_tr INNER JOIN tr ON courses_tr.tr_id = tr.id WHERE courses_tr.course_id = '{$this->id}'";
		}
		else
		{
			if($_SESSION['caseload_learners_only'] == 1)
				$sql= "SELECT COUNT(*) FROM courses_tr INNER JOIN tr ON courses_tr.tr_id = tr.id WHERE courses_tr.course_id = '{$this->id}' AND tr.coach = '{$_SESSION['user']->id}'";
			else
				$sql = "SELECT COUNT(*) FROM courses_tr INNER JOIN tr ON courses_tr.tr_id = tr.id WHERE courses_tr.course_id = '{$this->id}'";
		}
		return DAO::getSingleValue($link, $sql);
	}

	public function groupsCount(PDO $link)
	{
		return DAO::getSingleValue($link, "SELECT COUNT(*) FROM groups WHERE groups.courses_id = '{$this->id}'");
	}

	public function trainingGroupsCount(PDO $link)
	{
		return DAO::getSingleValue($link, "SELECT COUNT(*) FROM training_groups INNER JOIN groups ON training_groups.group_id = groups.id WHERE groups.courses_id = '{$this->id}'");
	}

    /**
     * Updates attendance statistics based on the statistics of teaching groups
     * in the course.  Therefore be sure that the teaching groups are up to
     * date before running this method.
     *
     * @param mixed $id A numeric id, an array of numeric ids or a SQL
     * query that produces a list of numeric ids
     */
    public function updateAttendanceStatistics(PDO $link, $course_ids = null)
    {
        if(is_null($course_ids))
        {
            $course_ids = $this->id;
        }

        if(is_array($course_ids))
        {
            // List of IDs
            sort($course_ids);
            $course_ids = implode(',', $course_ids);
        }

        if($course_ids == '')
        {
            return;
        }


        $sql = <<<HEREDOC
UPDATE
	courses INNER JOIN		
		(SELECT
			groups.courses_id AS courses_id,
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
      		groups.courses_id IN ($course_ids)
   	GROUP BY
     		groups.courses_id) AS stats
	ON courses.id = stats.courses_id
SET
	courses.scheduled_lessons = stats.scheduled_lessons,
	courses.registered_lessons = stats.registered_lessons,
	courses.attendances = stats.attendances,
	courses.lates = stats.lates,
	courses.very_lates = stats.very_lates,
	courses.authorised_absences = stats.authorised_absences,
	courses.unexplained_absences = stats.unexplained_absences,
	courses.unauthorised_absences = stats.unauthorised_absences,
	courses.dismissals_uniform = stats.dismissals_uniform,
	courses.dismissals_discipline = stats.dismissals_discipline;
HEREDOC;
        DAO::execute($link, $sql);
    }


    public function isSafeToDelete(PDO $link)
    {
        // A course is safe to delete unless significant register entries exist for it
        $sql = <<<HEREDOC
SELECT
	COUNT(*)
FROM
	courses INNER JOIN groups INNER JOIN lessons INNER JOIN register_entries
	ON (courses.id = groups.courses_id
	AND groups.id = lessons.groups_id
	AND register_entries.lessons_id = lessons.id)
WHERE
	courses.id = {$this->id} AND register_entries.entry != 8
HEREDOC;

        $register_entries = DAO::getSingleValue($link, $sql);

        return $register_entries === 0;
    }


    private function cleanTextField($fieldValue)
    {
        $fieldValue = str_replace($this->HTML_NEW_LINES, "\n", $fieldValue); // Convert <br/> etc. into \n
        $fieldValue = str_replace("\r", '', $fieldValue); // Remove all carriage returns (we'll use the UNIX newline)
        $fieldValue = preg_replace('/\n{2,}/', "\n", $fieldValue); // Remove superfluous newlines
        $fieldValue = strip_tags($fieldValue); // Remove HTML tags

        return $fieldValue;
    }

	public function programmeTitle(PDO $link)
	{
		return DAO::getSingleValue($link, "SELECT description FROM lookup_programme_type WHERE code = '{$this->programme_type}'");
	}

	public function getKSBTemplate(PDO $link)
	{
		$template = (object)['sections' => [], 'elements' => [], 'evidences' => []];

		$template_rows = DAO::getResultset($link, "SELECT * FROM tracking_template WHERE course_id = '{$this->id}'", DAO::FETCH_ASSOC);
		foreach($template_rows AS $row)
		{
			if(is_null($row['section_id']) && is_null($row['element_id']))
			{
				$template->sections[] = (object)[
					'section_id' => $row['id'],
					'section_title' => $row['title'],
					'elements' => [],
					'evidences' => [],
				];
			}
		}

		foreach($template->sections AS $section)
		{
			foreach($template_rows AS $row)
			{
				if($row['section_id'] == $section->section_id && is_null($row['element_id']))
				{
					$section->elements[] = (object)[
						'element_id' => $row['id'],
						'element_title' => $row['title'],
						'section_id' => $section->section_id,
						'section_title' => $section->section_title,
						'evidences' => [],
					];


					$template->elements[] = (object)[
						'element_id' => $row['id'],
						'element_title' => $row['title'],
						'section_id' => $section->section_id,
						'section_title' => $section->section_title,
					];

				}
			}
		}

		foreach($template->sections AS $section)
		{
			foreach($section->elements AS $element)
			{
				foreach($template_rows AS $row)
				{
					if($row['element_id'] == $element->element_id)
					{
						$element->evidences[] = (object)[
							'evidence_id' => $row['id'],
							'evidence_title' => $row['title'],
							'element_id' => $element->element_id,
							'element_title' => $element->element_title,
							'section_id' => $section->section_id,
							'section_title' => $section->section_title,
						];

						$section->evidences[] = (object)[
							'evidence_id' => $row['id'],
							'evidence_title' => $row['title'],
							'element_id' => $element->element_id,
							'element_title' => $element->element_title,
							'section_id' => $section->section_id,
							'section_title' => $section->section_title,
						];

						$template->evidences[] = (object)[
							'evidence_id' => $row['id'],
							'evidence_title' => $row['title'],
							'element_id' => $element->element_id,
							'element_title' => $element->element_title,
							'section_id' => $section->section_id,
							'section_title' => $section->section_title,
						];
					}
				}
			}
		}

		return $template;
	}

    public $id = NULL;
    public $director = NULL;
    public $title = NULL;
    public $apprenticeship_title = NULL;
    public $description = NULL;
    public $main_qualification_id = NULL;
    public $framework_id = NULL;
    public $awarding_body_centre = NULL;
    public $programme_number = NULL;
    public $course_start_date = NULL;
    public $course_end_date = NULL;
    public $min_numbers = 0;
    public $max_numbers = 0;


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


    // Collections
    public $qualifications = null;
    public $organisations_id = NULL;
    public $programme_type = NULL;
    public $active = 1;
    public $frequency = NULL;
    public $subsequent = NULL;
    public $review_programme_title = NULL;
    public $induction = NULL;
    public $l4 = NULL;
	public $course_group = NULL;
    public $routway = NULL;
    public $assessment_evidence = NULL;
	public $glh;
    public $course_capacity = NULL;
    public $course_price = NULL;
}
