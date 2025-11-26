<?php
class Complaint extends Entity
{
	public static function loadFromDatabase(PDO $link, $id)
	{
		if($id == '')
		{
			return null;
		}

		$key = addslashes((string)$id);
		$query = <<<HEREDOC
SELECT
	*
FROM
	complaints
WHERE
	id='$key';
HEREDOC;
		$st = $link->query($query);

		$complaint = null;
		if($st)
		{
			$complaint = null;
			$row = $st->fetch();
			if($row)
			{
				$complaint = $row['complaint_type'] == self::LEARNER_COMPLAINT ? new ComplaintLearner($row['record_id']) : new ComplaintEmployer($row['record_id']);
				$complaint->populate($row);
			}
		}
		else
		{
			throw new Exception("Could not execute database query to find complaint record. " . '----' . $query . '----' . $link->errorCode());
		}

		return $complaint;
	}

	public function save(PDO $link)
	{
		$this->modified = "";
		$this->created = ($this->id == "") ? date('Y-m-d H:i:s') : $this->created;
		$this->created_by = ($this->id == "") ? $_SESSION['user']->id : $this->created_by;

		return DAO::saveObjectToTable($link, 'complaints', $this);
	}

	public function getCreatedByName(PDO $link)
	{
		return DAO::getSingleValue($link, "SELECT CONCAT(firstnames,  ' ', surname) FROM users WHERE users.id = '{$this->created_by}'");
	}

	public static function userWithEditAccess($username)
	{
		return in_array($username, array('nhobbssv', 'arockett16', 'sahutchinson', 'hgibson1')) ? true : false;
	}


	public $id = NULL;
	public $record_id = NULL;
	public $reference = NULL;
	public $date_of_complaint = NULL;
	public $date_of_event = NULL;
	public $complaint_summary = NULL;
	public $outcome = 'O';
	public $related_person = NULL;
	public $related_department = NULL;
	public $investigation_needed = NULL;
	public $created_by = NULL;
	public $date_of_response = NULL;
	public $response_summary = NULL;
	public $investigation_form_sent = NULL;
	public $investigation_form_to = NULL;
	public $investigation_form_date = NULL;
	public $corrective_action_taken = NULL;
	public $baltic_values = NULL;
	public $created = NULL;
	public $modified = NULL;
	public $complaint_type = NULL;

	const LEARNER_COMPLAINT = 1;
	const EMPLOYER_COMPLAINT = 2;
}
?>