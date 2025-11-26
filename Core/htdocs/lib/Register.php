<?php
class Register implements Iterator
{
    ###############################################
    ## Iterator interface
    ###############################################
    public function rewind()
    {
        reset($this->entries);
    }

    public function current()
    {
        return current($this->entries);
    }

    public function key()
    {
        return key($this->entries);
    }

    public function next()
    {
        return next($this->entries);
    }

    public function valid()
    {
        return (current($this->entries) !== false);
    }
    ################################################
    ## End Iterator interface
    ################################################


    /**
     *  Constructor
     */
    public function __construct($lesson_id, PDO $link, $belongs_to_attendance_module = false)
    {
        $dao = new LessonDAO($link);
        $this->lesson = $dao->find($lesson_id);

        if(!$belongs_to_attendance_module)
        {
            $dao = new CourseGroupDAO($link);
            $this->group = $dao->find($this->lesson->groups_id);

            $this->course = Course::loadFromDatabase($link, $this->group->courses_id);
        }
        else
        {
            $dao = new AttendanceModuleGroupDAO($link);
            $this->group = $dao->find($this->lesson->groups_id);

            $this->attendance_module = AttendanceModule::loadFromDatabase($link, $this->group->module_id);

        }

        if($belongs_to_attendance_module)
        {
            $dao = new OrganisationDAO($link);
            $this->provider = $dao->find($link, $this->attendance_module->provider_id);
        }
        else
        {
            if(DB_NAME!='am_reed_demo' &&  DB_NAME!='am_reed')
            {
                $dao = new OrganisationDAO($link);
                $this->provider = $dao->find($link, $this->course->organisations_id);
            }
            else
            {
                $group_id = $this->lesson->groups_id;
                $provider_id = DAO::getSingleValue($link, "select id from organisations where id in (select courses_id from groups where id = '$group_id' )");
                $this->provider = Organisation::loadFromDatabase($link, $provider_id);
            }
        }

        $dao = new LocationDAO($link);
        $this->location = $dao->find($this->lesson->location);

        $dao = new PersonnelDAO($link);
        $this->tutor = $dao->find($link, $this->lesson->tutor);

        $sql = "SELECT code, description FROM lookup_register_entry_codes ORDER BY code;";
        $this->desc_lookup = DAO::getLookupTable($link, $sql);

    }


    /**
     * Add a register entry to the register
     *
     * @param RegisterEntry $e
     */
    public function addEntry(RegisterEntry $e)
    {
        // Assign a description if the description field is blank
        // and an attendance code has been specified
        if($e->entry != '' && $e->entry_description == '')
        {
            $e->entry_description = $this->desc_lookup[(integer)$e->entry];
        }

        // Assign a lesson ID to the entry if one has not already been
        // assigned. This allows register entries to have mixed lesson ids,
        // which may be useful when coalescing a day's entries into one
        // all encompassing register.
        if($e->lessons_id == '')
        {
            $e->lessons_id = $this->lesson->id;
        }

        $this->entries[] = $e;
    }

    /**
     * Add a register extra attendee entry to the register
     *
     * @param RegisterExtraAttendeeEntry $e
     */
    public function addExtraAttendeeEntry(RegisterExtraAttendeeEntry $e)
    {
        // Assign a description if the description field is blank
        // and an attendance code has been specified
        if($e->entry != '' && $e->entry_description == '')
        {
            $e->entry_description = $this->desc_lookup[(integer)$e->entry];
        }

        // Assign a lesson ID to the entry if one has not already been
        // assigned. This allows register entries to have mixed lesson ids,
        // which may be useful when coalescing a day's entries into one
        // all encompassing register.
        if($e->lessons_id == '')
        {
            $e->lessons_id = $this->lesson->id;
        }

        $this->extra_attendee_entries[] = $e;
    }



    public function load(PDO $link)
    {
        // Combines two queries
        // (1) Register entries specifically for all students of the teaching group
        // (2) Register entries that already exist, regardless of whether the student
        //     is still a member of the group

        // #170 relmes - added in tr.username returned

        $sql = <<<HEREDOC
(SELECT
	re.id AS id,
	l.id AS lessons_id, 
	tr.id AS pot_id,
	re.entry,
	re.created,
	re.lesson_contribution,
	IF(re.school_id IS NULL, tr.employer_id, re.school_id) AS school_id, # school ID in register has precedence

	l.date AS `lesson_date`,

	lookup.description AS entry_description,

	tr.l03,
	tr.id AS student_id,
	tr.username AS student_username,
	tr.firstnames AS student_firstnames,
	tr.surname AS student_surname,
	tr.gender AS student_gender,
	tr.start_date AS pot_start,
	tr.closure_date AS pot_end,
	IF(l.date < tr.start_date || (tr.closure_date IS NOT NULL AND l.date >= tr.closure_date), FALSE, TRUE) AS within_pot_dates,
	schools.short_name AS school_short_name,

	notes.id AS note_id, notes.note AS note, notes.created AS note_created,
	notes.modified as note_modified,
	notes.username AS note_username, notes.firstnames AS note_firstnames,
	notes.surname AS note_surname, notes.organisation_name,
	notes.entry AS note_entry, notes.is_audit_note,
	l.`tutor`
FROM
	lessons AS l INNER JOIN group_members AS gm INNER JOIN tr INNER JOIN organisations AS schools
	ON (l.groups_id = gm.groups_id AND tr.id = gm.tr_id AND tr.employer_id = schools.id)
	LEFT OUTER JOIN register_entries AS re ON (re.pot_id = tr.id AND re.lessons_id = l.id)
	LEFT OUTER JOIN register_entry_notes AS notes ON notes.register_entries_id = re.id
	LEFT OUTER JOIN lookup_register_entry_codes AS lookup ON lookup.code = re.entry
WHERE
	l.id = {$this->lesson->id} and (tr.closure_date is null or tr.closure_date = '0000-00-00' or tr.closure_date>l.date))

UNION

(SELECT
	re.id AS id,
	re.lessons_id AS lessons_id, 
	tr.id AS pot_id,
	re.entry,
	re.created,
	re.lesson_contribution,
	IF(re.school_id IS NULL, tr.employer_id, re.school_id) AS school_id,  # school ID in register has precedence

	lessons.date AS 'lesson_date',

	lookup.description AS entry_description,

	tr.l03,
	tr.id AS student_id,
	tr.username AS student_username,
	tr.firstnames AS student_firstnames,
	tr.surname AS student_surname,
	tr.gender AS student_gender,
	tr.start_date AS pot_start,
	tr.closure_date AS pot_end,
	IF(lessons.date < tr.start_date || (tr.closure_date IS NOT NULL AND lessons.date >= tr.closure_date), FALSE, TRUE) AS within_pot_dates,
	schools.short_name AS school_short_name,

	notes.id AS note_id, notes.note AS note, notes.created AS note_created,
	notes.modified as note_modified,
	notes.username AS note_username, notes.firstnames AS note_firstnames,
	notes.surname AS note_surname, notes.organisation_name,
	notes.entry AS note_entry, notes.is_audit_note,
	lessons.`tutor`
FROM
	register_entries AS re INNER JOIN lessons INNER JOIN tr INNER JOIN organisations AS schools
	ON (re.pot_id = tr.id AND re.lessons_id = lessons.id AND tr.employer_id = schools.id)
	LEFT OUTER JOIN register_entry_notes AS notes ON notes.register_entries_id = re.id
	LEFT OUTER JOIN lookup_register_entry_codes AS lookup ON lookup.code = re.entry	
WHERE
	re.lessons_id = {$this->lesson->id} and (tr.closure_date is null or tr.closure_date = '0000-00-00' or tr.closure_date>lessons.date))
UNION
(SELECT
	re.id AS id,
	extra_learners.`lesson_id` AS lessons_id,
	extra_learners.`tr_id` AS pot_id,
	re.entry AS entry,
	re.created AS created,
	re.lesson_contribution,
	tr.provider_id AS school_id, # school ID in register has precedence

	l.date AS `lesson_date`,

	lookup.description AS entry_description,

	tr.l03,
	tr.id AS student_id,
	tr.username AS student_username,
	tr.firstnames AS student_firstnames,
	tr.surname AS student_surname,
	tr.gender AS student_gender,
	tr.start_date AS pot_start,
	tr.closure_date AS pot_end,
	IF(l.date < tr.start_date || (tr.closure_date IS NOT NULL AND l.date >= tr.closure_date), FALSE, TRUE) AS within_pot_dates,
	providers.short_name AS school_short_name,

	notes.id AS note_id, notes.note AS note, notes.created AS note_created,
	notes.modified AS note_modified,
	notes.username AS note_username, notes.firstnames AS note_firstnames,
	notes.surname AS note_surname, notes.organisation_name,
	notes.entry AS note_entry, notes.is_audit_note,
	l.`tutor`
FROM
	lessons AS l INNER JOIN lesson_extra_learners AS extra_learners INNER JOIN tr INNER JOIN organisations AS providers
	ON (l.id = extra_learners.lesson_id AND tr.id = extra_learners.tr_id AND tr.employer_id = providers.id)
	LEFT OUTER JOIN register_entries AS re ON (re.pot_id = tr.id AND re.lessons_id = l.id)
	LEFT OUTER JOIN register_entry_notes AS notes ON notes.register_entries_id = re.id
	LEFT OUTER JOIN lookup_register_entry_codes AS lookup ON lookup.code = re.entry
WHERE
	l.id = {$this->lesson->id} AND (tr.closure_date IS NULL OR tr.closure_date = '0000-00-00' OR tr.closure_date>l.date))

ORDER BY
	student_surname, student_firstnames, note_created ASC;
HEREDOC;

        $st = $link->query($sql);

        if($st)
        {
            $row = $st->fetch();

            while($row)
            {
                $entry = new RegisterEntry();
                $entry->populate($row); // sql query written carefully so that this will work

                $current_pot_id = $row['pot_id'];

                if(!is_null($row['note']))
                {
                    // Note present.  Add the note to the entry
                    // and see if there are any further notes for this
                    // entry in the following rows.
                    do
                    {
                        $note = new RegisterEntryNote();
                        $note->id = 						$row['note_id'];
                        $note->register_entries_id = 	$row['id'];
                        $note->note = 						$row['note'];
                        $note->username = 				$row['note_username'];
                        $note->firstnames = 				$row['note_firstnames'];
                        $note->surname = 					$row['note_surname'];
                        $note->created = 					$row['note_created'];
                        $note->entry =						$row['note_entry'];
                        $note->organisation_name =		$row['organisation_name'];
                        $note->is_audit_note =			$row['is_audit_note'];
                        $note->modified =				$row['note_modified'];

                        $entry->addNote($note);

                        $row = $st->fetch();

                    } while($row['pot_id'] == $current_pot_id); // check the next row
                }
                else
                {
                    // No note - move on to next record
                    $row = $st->fetch();
                }

                // Add the register entry to this register
                $this->addEntry($entry);
            }

        }
        else
        {
            throw new DatabaseException($link, $sql);
        }

    }


    public function save(PDO $link)
    {
        if(!SystemConfig::getEntityValue($link, 'attendance_module_v2'))
        {
            if(!is_array($this->entries) || count($this->entries) === 0){
                throw new Exception("You cannot save a register with no register entries");
            }

            foreach($this->entries as $e)
            {
                $e->save($link);
            }

            // Delete previous entries in the reports table
            $sql = "DELETE FROM attendance_reports WHERE lesson_id={$this->lesson->id} AND pot_id > 0;";
            DAO::execute($link, $sql);

            // Insert fresh entries into the reports table
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
	lessons.id = {$this->lesson->id}
HEREDOC;
            DAO::execute($link, $sql);

            $sql = 'SELECT COUNT(entry) FROM attendance_reports WHERE lesson_id='.$this->lesson->id;
            $entryCount = DAO::getSingleValue($link, $sql);
            if($entryCount > 0)
            {
                // Remove placeholder, if present
                $sql = "DELETE FROM attendance_reports WHERE lesson_id={$this->lesson->id} AND pot_id = 0;";
                DAO::execute($link, $sql);
            }
            else
            {
                // Restore placeholder (this should never execute in normal circumstances)
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
	lessons.id={$this->lesson->id};
HEREDOC;
                DAO::execute($link, $sql);
            }

        }
        else
        {
            if(!is_array($this->entries) || count($this->entries) === 0){
                if(count($this->extra_attendee_entries) === 0)
                    throw new Exception("You cannot save a register with no register entries");
            }

            foreach($this->entries as $e)
            {
                $e->save($link);
            }

            foreach($this->extra_attendee_entries as $e)
            {
                $e->save($link);
            }
        }

        // OTJ Adjustment
        $tr_ids = [];
        foreach($this->entries AS $_entry)
        {
            $tr_ids[] = $_entry->pot_id;
        }
        // first delete the entries in otj as lesson could be saved again and entries attendance can be changed.
        OTJ::deleteEntityRelatedRecords($link, $tr_ids, 'lesson', $this->lesson->id);

        //save in the otj table if applicable
        if($this->lesson->otj_hours != '' && $this->lesson->otj_minutes != '' && $this->lesson->otj_hours != 0 && $this->lesson->otj_minutes != 0)
        {
            foreach($this->entries AS $_entry)
            {
                if(in_array($_entry->entry, [1]))
                {
                    $otj = new OTJ();
                    $otj->tr_id = $_entry->pot_id;
                    $otj->date = $this->lesson->date;
                    $otj->time_from = $this->lesson->start_time;
                    $otj->time_to = $this->lesson->end_time;
                    $otj->duration_hours = $this->lesson->otj_hours;
                    $otj->duration_minutes = $this->lesson->otj_minutes;
                    $otj->type = $this->lesson->otj_type;
                    $otj->entity_type = 'lesson';
                    $otj->entity_id = $this->lesson->id;
                    $otj->created = date('Y-m-d H:i:s');
                    DAO::saveObjectToTable($link, 'otj', $otj);
                }
            }
        }
        DAO::execute($link, "UPDATE lessons SET lessons.set_as_otj = '{$this->lesson->set_as_otj}' WHERE lessons.id = '{$this->lesson->id}'");
    }


    // Value objects
    public $lesson = null;		            /* @var $lesson LessonVO */
    public $group = null;		            /* @var $group CourseGroupVO */
    public $course = null;		            /* @var $course Course */
    public $attendance_module = null;       /* @var $attendance_module AttendanceModule */
    public $provider = null;	            /* @var $provider ProviderVO */
    public $location = null;	            /* @var $location LocationVO */
    public $tutor = null;		            /* @var $tutor PersonnelVO */
    public $qualification = null;

    public $entries = array();
    public $extra_attendee_entries = array();

    //private $attendance_codes = null;
    private $desc_lookup = null;
}

?>