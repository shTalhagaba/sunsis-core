<?php
class save_register implements IAction
{
    public function execute(PDO $link)
    {
        // Validate data entry
        $lesson_id = isset($_REQUEST['lesson_id']) ? $_REQUEST['lesson_id'] : '';
        $attendance_module = isset($_REQUEST['attendance_module']) ? $_REQUEST['attendance_module'] : '';

        if($lesson_id == '' || !is_numeric($lesson_id))
        {
            throw new Exception("You must specify a numeric id in the querystring");
        }

        // Create register object hierarchy
        if($attendance_module)
            $reg = new Register($lesson_id, $link, true);
        else
            $reg = new Register($lesson_id, $link);
        $rowNum = 1;
        while(array_key_exists('r' . $rowNum . '_id', $_REQUEST))
        {
            $entry = new RegisterEntry();

            $prefix = 'r' . $rowNum;
            $entry->id = $_REQUEST[$prefix . '_id'];
            $entry->lessons_id = $lesson_id;
            $entry->pot_id = $_REQUEST[$prefix . '_pot_id'];
            $entry->entry = $_REQUEST[$prefix . '_entry'];
            $entry->school_id = $_REQUEST[$prefix . '_school_id'];

            $note_text = $_REQUEST[$prefix . '_note'];
            if(!is_null($note_text) && $note_text != '')
            {
                $note = new RegisterEntryNote();
                $note->note = $note_text;

                $entry->addNote($note);
            }

            $reg->addEntry($entry);

            $rowNum++;
        }

        $rowNum = 1;
        while(array_key_exists('ar' . $rowNum . '_id', $_REQUEST))
        {
            $entry = new RegisterExtraAttendeeEntry();

            $prefix = 'ar' . $rowNum;
            $entry->id = $_REQUEST[$prefix . '_id'];
            $entry->lessons_id = $lesson_id;
            $entry->attendee_id = $_REQUEST[$prefix . '_attendee_id'];
            $entry->entry = $_REQUEST[$prefix . '_entry'];

            $reg->addExtraAttendeeEntry($entry);

            $rowNum++;
        }

        // Create a lesson note object (will not be saved if empty -- see below)
        $note = new LessonNote();
        $note->lessons_id = $lesson_id;
        $note->subject = isset($_POST['newnote_subject'])?$_POST['newnote_subject']:'';
        $note->note = isset($_POST['newnote_note'])?$_POST['newnote_note']:'';
        $note->public = isset($_POST['newnote_public'])?$_POST['newnote_public']:'';
        $note->readers = isset($_POST['newnote_readers'])?$_POST['newnote_readers']:'';

        if(isset($_REQUEST['set_as_otj']) && $_REQUEST['set_as_otj'] == '1')
            $reg->lesson->set_as_otj = 1;
        else
            $reg->lesson->set_as_otj = 0;


        DAO::transaction_start($link);
        try
        {
            // save register
            $reg->save($link);

            // Update attendance statistics
            $this->updatePotStats($link, $lesson_id);
            $this->updateStudentStats($link, $lesson_id);
            $this->updateLessonStats($link, $lesson_id);
            if(!$attendance_module)
            {
                $this->updateGroupStats($link, $lesson_id);
                $this->updateCourseStats($link, $lesson_id);
            }
            else
            {
                $this->updateAttendanceModuleGroupStats($link, $lesson_id);
                $this->updateAttendanceModuleStats($link, $lesson_id);
            }

            // Add lesson note
            if( ($note->subject !='') || ($note->note != '') )
            {
                $note->save($link);
            }
            DAO::transaction_commit($link);
        }
        catch(Exception $e)
        {
            DAO::transaction_rollback($link, $e);
            throw new WrappedException($e);
        }


        // Presentation
        if(IS_AJAX)
        {
            header('Content-Type: text/plain; charset=ISO-8859-1');
            echo $lesson_id;
        }
        else
        {
            http_redirect('do.php?_action=read_register&lesson_id=' . $lesson_id);
        }
    }



    public function checkPermissions(PDO $link, Register $reg)
    {
        if($_SESSION['role'] == 'admin')
        {
            return true;
        }
        elseif($_SESSION['org']->org_type_id == ORG_SCHOOL)
        {
            $num_pupils_in_lesson = <<<HEREDOC
SELECT
	COUNT(pot.school_id)
FROM
	lessons INNER JOIN group_members INNER JOIN pot
	ON(lessons.groups_id = group_members.groups_id AND group_members.pot_id = pot.id)
WHERE
	lessons.id = {$reg->lesson->id} AND pot.school_id = {$_SESSION['org']->id};
HEREDOC;
            $num_pupils_in_lesson = DAO::getSingleValue($link, $num_pupils_in_lesson);

            return ($num_pupils_in_lesson > 0) && ($reg->lesson->num_entries > 0);
        }
        elseif($_SESSION['org']->org_type_id == ORG_PROVIDER)
        {
            $acl = CourseACL::loadFromDatabase($link, $reg->course->id);
            $is_employee = $_SESSION['org']->id == $reg->course->organisations_id;
            $is_local_admin = in_array('ladmin', $_SESSION['privileges']);
            $listed_in_course_acl = in_array($_SESSION['username'], $acl->usernames);

            return $is_employee && ($is_local_admin || $listed_in_course_acl);
        }
        else
        {
            return false;
        }

    }



    /**
     * Update the training records of all members of the group that this
     * lesson is for
     */
    private function updatePotStats(PDO $link, $lesson_id)
    {
        $sql_pot_ids = <<<HEREDOC
SELECT
  group_members.tr_id
FROM
  lessons INNER JOIN group_members ON lessons.groups_id = group_members.groups_id
WHERE
  lessons.id = $lesson_id
ORDER BY
	group_members.tr_id
HEREDOC;

        $pot_dao = new PotDAO($link);
        $pot_dao->updateAttendanceStatistics($link, $sql_pot_ids);
    }


    private function updateStudentStats(PDO $link, $lesson_id)
    {
        $sql_student_ids = <<<HEREDOC
SELECT
 	tr.id
FROM
	lessons INNER JOIN group_members INNER JOIN tr
	ON lessons.groups_id = group_members.groups_id
	AND group_members.tr_id = tr.id
WHERE
	lessons.id = $lesson_id
ORDER BY
	tr.id
HEREDOC;

        $stu_dao = new StudentDAO($link);
        $stu_dao->updateAttendanceStatistics($sql_student_ids);
    }


    private function updateCourseStats(PDO $link, $lesson_id)
    {

        $sql_course_id = <<<HEREDOC
SELECT
 	groups.courses_id
FROM
	lessons INNER JOIN groups ON lessons.groups_id = groups.id
WHERE
	lessons.id = $lesson_id
HEREDOC;

        $course = new Course();
        $course->updateAttendanceStatistics($link, $sql_course_id);
    }

    private function updateAttendanceModuleStats(PDO $link, $lesson_id)
    {

        $sql_attendance_module_id = <<<HEREDOC
SELECT
 	attendance_module_groups.module_id
FROM
	lessons INNER JOIN attendance_module_groups ON lessons.groups_id = attendance_module_groups.id
WHERE
	lessons.id = $lesson_id
HEREDOC;

        $attendance_module = new AttendanceModule();
        $attendance_module->updateAttendanceStatistics($link, $sql_attendance_module_id);
    }


    private function updateLessonStats(PDO $link, $lesson_id)
    {
        $lesson_dao = new LessonDAO($link);
        $lesson_dao->updateAttendanceStatistics($lesson_id);
    }


    private function updateGroupStats(PDO $link, $lesson_id)
    {
        $sql_group_id = "SELECT lessons.groups_id FROM lessons WHERE lessons.id=$lesson_id";
        $group_dao = new CourseGroupDAO($link);
        $group_dao->updateAttendanceStatistics($sql_group_id);
    }

    private function updateAttendanceModuleGroupStats(PDO $link, $lesson_id)
    {
        $sql_group_id = "SELECT lessons.groups_id FROM lessons WHERE lessons.id=$lesson_id";
        $group_dao = new AttendanceModuleGroupDAO($link);
        $group_dao->updateAttendanceStatistics($sql_group_id);
    }


    private $link = null;
}
?>