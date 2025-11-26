<?php
class CourseGroupVO extends ValueObject
{
	public $id = 0;
	public $courses_id = 0;
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
    public $group_capacity = NULL;

	public function tutorName(PDO $link)
	{
		return DAO::getSingleValue($link, "SELECT CONCAT(firstnames, ' ', surname) FROM users WHERE users.id = '{$this->tutor}'");
	}
	public function assessorName(PDO $link)
	{
		return DAO::getSingleValue($link, "SELECT CONCAT(firstnames, ' ', surname) FROM users WHERE users.id = '{$this->assessor}'");
	}
	public function verifierName(PDO $link)
	{
		return DAO::getSingleValue($link, "SELECT CONCAT(firstnames, ' ', surname) FROM users WHERE users.id = '{$this->verifier}'");
	}
	public function getLearnersCount(PDO $link)
	{
		return DAO::getSingleValue($link, "SELECT COUNT(*) FROM group_members WHERE groups_id = '{$this->id}'");
	}
	public function getTrainingGroupsCount(PDO $link)
	{
		return DAO::getSingleValue($link, "SELECT COUNT(*) FROM training_groups WHERE group_id = '{$this->id}'");
	}
}
?>