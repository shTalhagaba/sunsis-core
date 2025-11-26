<?php
class LessonVO extends ValueObject
{
    public $id = NULL;
    public $groups_id = NULL;
    public $date = NULL;
    public $start_time = NULL;
    public $end_time = NULL;
    public $tutor = NULL;
    public $location = NULL;
    public $not_applicables = NULL;
    public $qualification = NULL;
    public $module = NULL;
    public $lesson_title = NULL;
    public $otj_hours = NULL;
    public $otj_minutes = NULL;
    public $otj_type = NULL;
    public $set_as_otj = NULL;

    // ATTENDANCE STATISTICS
    public $num_entries = null;
    public $scheduled_lessons = 1;
    public $registered_lessons = null;
    public $attendances = null;
    public $lates = null;
    public $very_lates = null;
    public $authorised_absences = null;
    public $unexplained_absences = null;
    public $unauthorised_absences = null;
    public $dismissals_uniform = null;
    public $dismissals_discipline = null;
}
?>