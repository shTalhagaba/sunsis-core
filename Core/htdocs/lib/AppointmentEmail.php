<?php
class AppointmentEmail extends Entity
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
	learner_appointments_emails
WHERE
	id='$key';
HEREDOC;
		$st = $link->query($query);

		$appointment_email = null;
		if($st)
		{
			$appointment_email = null;
			$row = $st->fetch();
			if($row)
			{
				$appointment_email = new AppointmentEmail();
				$appointment_email->populate($row);
			}

		}
		else
		{
			throw new Exception("Could not execute database query to find appointment email. " . '----' . $query . '----' . $link->errorCode());
		}

		return $appointment_email;
	}

	public function save(PDO $link)
	{
		$this->created = "";

		return DAO::saveObjectToTable($link, 'learner_appointments_emails', $this);
	}

	public function delete(PDO $link)
	{

	}


	public function isSafeToDelete(PDO $link)
	{
		return false;
	}

	public $id = NULL;
	public $tr_id = NULL;
	public $appointment_id = NULL;
	public $sent_by_user_id = NULL;
	public $sender_email = NULL;
	public $receiver_email = NULL;
	public $subject = NULL;
	public $email_body = NULL;
	public $created = NULL;

}
?>