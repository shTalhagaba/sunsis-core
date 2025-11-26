<?php
class EmployerPoolContactEmail extends Entity
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
	employer_pool_contact_email_notes
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
				$crm_note = new EmployerPoolContactEmail();
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
		return DAO::saveObjectToTable($link, 'employer_pool_contact_email_notes', $this);
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
	public $org_id = NULL;
	public $sender_name = NULL;
	public $sender_email = NULL;
	public $receiver_name = NULL;
	public $receiver_email = NULL;
	public $date_sent = NULL;
	public $time_sent = NULL;
	public $subject = NULL;
	public $email_body = NULL;
	public $email_html_preview = NULL;
	public $sent_from_sunesis = NULL;

}
?>