<?php
class AttendanceModuleGroupVO extends ValueObject
{
	public $id = NULL;
	public $module_id = NULL;
	public $title = NULL;
	public $tutor = NULL;
	public $old_tutor = NULL;
	public $assessor = NULL;
	public $old_assessor = NULL;
	public $verifier = NULL;
	public $old_verifier = NULL;
	public $wbcoordinator = NULL;
	public $old_wbcoordinator = NULL;
	public $tutor2 = NULL;

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
}
?>