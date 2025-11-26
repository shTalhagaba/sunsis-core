<?php
class CandidateCRM extends Entity
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
	crm_notes_candidates
WHERE
	id='$key';
HEREDOC;
		$st = $link->query($query);

		$crm_note = null;
		if($st)
		{
			$crm_note = null;
			$row = $st->fetch();
			if($row)
			{
				$crm_note = new CandidateCRM();
				$crm_note->populate($row);
			}

		}
		else
		{
			throw new Exception("Could not execute database query to find contract. " . '----' . $query . '----' . $link->errorCode());
		}

		return $crm_note;
	}

	public function save(PDO $link)
	{
		return DAO::saveObjectToTable($link, 'crm_notes_candidates', $this);
	}

	public function delete(PDO $link)
	{
		// Placeholder
	}


	public function isSafeToDelete(PDO $link)
	{
		return false;
	}


	public $id = null;
	public $candidate_id = NULL;
	public $name_of_person = NULL;
	public $position = NULL;
	public $type_of_contact = NULL;
	public $subject = NULL;
	public $date = NULL;
	public $agreed_action = NULL;
	public $by_whom = NULL;
	public $whom_position = NULL;
	public $next_action_date = NULL;
	public $next_action = NULL;
	public $crm_type = NULL;
	public $other_notes = NULL;
	public $audit_info = NULL;

	protected $audit_fields = array(
		'name_of_person'=>'Name of Person Contacted',
		'type_of_contact'=>'Type of contact',
		'subject'=>'Subject',
		'date'=>'Date',
		'by_whom'=>'By Whom',
		'position'=>'Position',
		'next_action_date'=>'Next Action Date',
		'agreed_action'=>'Agreed Action',
		'other_notes'=>'Other Notes'
		);

}
?>