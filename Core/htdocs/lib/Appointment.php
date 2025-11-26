<?php
class Appointment extends Entity
{
	public static function loadFromDatabase(PDO $link, $id)
	{
		if ($id == '') {
			return null;
		}

		$key = addslashes((string)$id);
		$query = <<<HEREDOC
SELECT
	*
FROM
	appointments
WHERE
	id='$key';
HEREDOC;
		$st = $link->query($query);

		$appointment = null;
		if ($st) {
			$appointment = null;
			$row = $st->fetch();
			if ($row) {
				$appointment = new Appointment();
				$appointment->populate($row);
			}
		} else {
			throw new Exception("Could not execute database query to find appointment for the training record. " . '----' . $query . '----' . $link->errorCode());
		}

		if (preg_match('/^(\d\d:\d\d)/', $appointment->appointment_start_time, $matches)) {
			$appointment->appointment_start_time = $matches[1];
		}

		if (preg_match('/^(\d\d:\d\d)/', $appointment->appointment_end_time, $matches)) {
			$appointment->appointment_end_time = $matches[1];
		}

		return $appointment;
	}

	public function save(PDO $link)
	{
		// If we are inserting a new record (id is empty) or updating an existing one,
		// we should first check for duplicates
		$query = "
        SELECT id 
        FROM appointments 
        WHERE appointment_date = :date
          AND appointment_start_time = :start_time
          AND appointment_end_time = :end_time
          AND (:id IS NULL OR id != :skip_id)
        LIMIT 1
    ";

		$stmt = $link->prepare($query);
		$stmt->execute([
			':date'       => $this->appointment_date,
			':start_time' => $this->appointment_start_time,
			':end_time'   => $this->appointment_end_time,
			':id'         => $this->id,
			':skip_id'    => $this->id
		]);

		if ($stmt->fetch()) {
			throw new Exception("An appointment already exists with the same date and start/end time.");
		}

		// Proceed with save if no duplicate
		$this->modified = "";
		$this->created = ($this->id == "") ? date('Y-m-d H:i:s') : $this->created;

		return DAO::saveObjectToTable($link, 'appointments', $this);
	}


	public function delete(PDO $link) {}


	public function isSafeToDelete(PDO $link)
	{
		return false;
	}

	/**
	 * Overridden method
	 * @param pdo $link
	 * @param ValueObject $new_object
	 * @param array $exclude_fields
	 */
	public function buildAuditLogString(PDO $link, Entity $new_vo, array $exclude_fields = array())
	{
		if (count($exclude_fields) == 0) {
			// These fields use lookup codes
			$exclude_fields = array('appointment_type', 'interviewer', 'appointment_status', 'appointment_paperwork', 'appointment_module');
		}

		$changes_list = parent::buildAuditLogString($link, $new_vo, $exclude_fields);

		// Test each of the exceptions separately
		if ($this->appointment_type != $new_vo->appointment_type) {
			$lookup = DAO::getLookupTable($link, "SELECT id, description FROM lookup_appointment_types ORDER BY description");
			$from = isset($lookup[$this->appointment_type]) ? $lookup[$this->appointment_type] : $this->appointment_type;
			$to = isset($lookup[$new_vo->appointment_type]) ? $lookup[$new_vo->appointment_type] : $new_vo->appointment_type;
			$changes_list .= "[Appointment Type] changed from '$from' to '$to'\n";
		}
		if ($this->interviewer != $new_vo->interviewer) {
			$lookup = DAO::getLookupTable($link, "SELECT id, CONCAT(users.firstnames, ' ', users.surname) FROM users WHERE TYPE = 3 ORDER BY firstnames ");
			$from = isset($lookup[$this->interviewer]) ? $lookup[$this->interviewer] : $this->interviewer;
			$to = isset($lookup[$new_vo->interviewer]) ? $lookup[$new_vo->interviewer] : $new_vo->interviewer;
			$changes_list .= "[Interviewer] changed from '$from' to '$to'\n";
		}
		if ($this->appointment_status != $new_vo->appointment_status) {
			$lookup = DAO::getLookupTable($link, "SELECT id, description FROM lookup_appointment_status ORDER BY description");
			$from = isset($lookup[$this->appointment_status]) ? $lookup[$this->appointment_status] : $this->appointment_status;
			$to = isset($lookup[$new_vo->appointment_status]) ? $lookup[$new_vo->appointment_status] : $new_vo->appointment_status;
			$changes_list .= "[Appointment Status] changed from '$from' to '$to'\n";
		}
		if ($this->appointment_paperwork != $new_vo->appointment_paperwork) {
			$lookup = DAO::getLookupTable($link, "SELECT id, description FROM lookup_appointment_paperwork ORDER BY description");
			$from = isset($lookup[$this->appointment_paperwork]) ? $lookup[$this->appointment_paperwork] : $this->appointment_paperwork;
			$to = isset($lookup[$new_vo->appointment_paperwork]) ? $lookup[$new_vo->appointment_paperwork] : $new_vo->appointment_paperwork;
			$changes_list .= "[Appointment Paperwork Status] changed from '$from' to '$to'\n";
		}
		if ($this->appointment_module != $new_vo->appointment_module) {
			$lookup = DAO::getLookupTable($link, "SELECT id, title FROM modules ORDER BY title");
			$from = isset($lookup[$this->appointment_module]) ? $lookup[$this->appointment_module] : $this->appointment_module;
			$to = isset($lookup[$new_vo->appointment_module]) ? $lookup[$new_vo->appointment_module] : $new_vo->appointment_module;
			$changes_list .= "[Module] changed from '$from' to '$to'\n";
		}

		return $changes_list;
	}

	public $id = NULL;
	public $appointment_date = NULL;
	public $appointment_start_time = NULL;
	public $appointment_end_time = NULL;
	public $appointment_type = NULL;
	public $interviewer = NULL;
	public $appointment_status = NULL;
	public $appointment_rgb_status = NULL;
	public $appointment_paperwork = NULL;
	public $appointment_module = NULL;
	public $appointment_comments = NULL;
	public $tr_id = NULL;
	public $created_by = NULL;

	public $created = NULL;
	public $modified = NULL;

	const BookedAppointment = 1;
	const RescheduledAppointment = 7;

	protected $audit_fields = array(
		'appointment_date' => 'Date',
		'appointment_start_time' => 'Start Time',
		'appointment_end_time' => 'End Time',
		'appointment_rgb_status' => 'GYR Status',
		'appointment_comments' => 'Comments'
	);
}
