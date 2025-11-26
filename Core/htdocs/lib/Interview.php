<?php
class Interview extends Entity
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
	interviews
WHERE
	id='$key';
HEREDOC;
		$st = $link->query($query);

		$interview = null;
		if($st)
		{
			$interview = null;
			$row = $st->fetch();
			if($row)
			{
				$interview = new Interview();
				$interview->populate($row);
			}

		}
		else
		{
			throw new Exception("Could not execute database query to find interview for the training record. " . '----' . $query . '----' . $link->errorCode());
		}

		if(preg_match('/^(\d\d:\d\d)/', $interview->interview_start_time, $matches))
		{
			$interview->interview_start_time = $matches[1];
		}

		if(preg_match('/^(\d\d:\d\d)/', $interview->interview_end_time, $matches))
		{
			$interview->interview_end_time = $matches[1];
		}

		return $interview;
	}

	public function save(PDO $link)
	{
		$this->modified = "";
		$this->created = ($this->id == "") ? date('Y-m-d H:i:s') : $this->created;

		return DAO::saveObjectToTable($link, 'interviews', $this);
	}

	public function delete(PDO $link)
	{

	}


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
		if(count($exclude_fields) == 0)
		{
			// These fields use lookup codes
			$exclude_fields = array('interview_type', 'interviewer', 'interview_status', 'interview_paperwork', 'interview_module');
		}

		$changes_list = parent::buildAuditLogString($link, $new_vo, $exclude_fields);

		// Test each of the exceptions separately
		if($this->interview_type != $new_vo->interview_type)
		{
			$lookup = DAO::getLookupTable($link, "SELECT id, description FROM lookup_interview_types ORDER BY description");
			$from = isset($lookup[$this->interview_type]) ? $lookup[$this->interview_type] : $this->interview_type;
			$to = isset($lookup[$new_vo->interview_type]) ? $lookup[$new_vo->interview_type] : $new_vo->interview_type;
			$changes_list .= "[Interview Type] changed from '$from' to '$to'\n";
		}
		if($this->interviewer != $new_vo->interviewer)
		{
			$lookup = DAO::getLookupTable($link, "SELECT id, CONCAT(users.firstnames, ' ', users.surname) FROM users WHERE TYPE = 3 ORDER BY firstnames ");
			$from = isset($lookup[$this->interviewer]) ? $lookup[$this->interviewer] : $this->interviewer;
			$to = isset($lookup[$new_vo->interviewer]) ? $lookup[$new_vo->interviewer] : $new_vo->interviewer;
			$changes_list .= "[Interviewer] changed from '$from' to '$to'\n";
		}
		if($this->interview_status != $new_vo->interview_status)
		{
			$lookup = DAO::getLookupTable($link, "SELECT id, description FROM lookup_interview_status ORDER BY description");
			$from = isset($lookup[$this->interview_status]) ? $lookup[$this->interview_status] : $this->interview_status;
			$to = isset($lookup[$new_vo->interview_status]) ? $lookup[$new_vo->interview_status] : $new_vo->interview_status;
			$changes_list .= "[Interview Status] changed from '$from' to '$to'\n";
		}
		if($this->interview_paperwork != $new_vo->interview_paperwork)
		{
			$lookup = DAO::getLookupTable($link, "SELECT id, description FROM lookup_interview_paperwork ORDER BY description");
			$from = isset($lookup[$this->interview_paperwork]) ? $lookup[$this->interview_paperwork] : $this->interview_paperwork;
			$to = isset($lookup[$new_vo->interview_paperwork]) ? $lookup[$new_vo->interview_paperwork] : $new_vo->interview_paperwork;
			$changes_list .= "[Interview Paperwork Status] changed from '$from' to '$to'\n";
		}
		if($this->interview_module != $new_vo->interview_module)
		{
			$lookup = DAO::getLookupTable($link, "SELECT id, title FROM modules ORDER BY title");
			$from = isset($lookup[$this->interview_module]) ? $lookup[$this->interview_module] : $this->interview_module;
			$to = isset($lookup[$new_vo->interview_module]) ? $lookup[$new_vo->interview_module] : $new_vo->interview_module;
			$changes_list .= "[Module] changed from '$from' to '$to'\n";
		}

		return $changes_list;
	}

	public $id = NULL;
	public $interview_date = NULL;
	public $interview_start_time = NULL;
	public $interview_end_time = NULL;
	public $interview_type = NULL;
	public $interviewer = NULL;
	public $interview_status = NULL;
	public $interview_rgb_status = NULL;
	public $interview_paperwork = NULL;
	public $interview_module = NULL;
	public $interview_comments = NULL;
	public $tr_id = NULL;

	public $created = NULL;
	public $modified = NULL;

	const BookedInterview = 1;
	const RescheduledInterview = 7;

	protected $audit_fields = array(
		'interview_date'=>'Date',
		'interview_start_time'=>'Start Time',
		'interview_end_time'=>'End Time',
		'interview_rgb_status'=>'GYR Status',
		'interview_comments'=>'Comments'
	);

}
?>