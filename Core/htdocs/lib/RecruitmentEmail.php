<?php
class RecruitmentEmail extends Entity
{
	public static function getInstance(PDO $link)
	{
		return new RecruitmentEmail();
	}

	public static function loadFromDatabase(PDO $link, $email_type)
	{
		if($email_type == '')
		{
			return null;
		}

		$key = addslashes((string)$id);
		$query = <<<HEREDOC
SELECT
	*
FROM
	candidate_email_templates
WHERE
	email_type='$key';
HEREDOC;
		$st = $link->query($query);

		$org = null;
		if($st)
		{
			$row = $st->fetch();
			if($row)
			{
				$email_template = new RecruitmentEmail();
				$email_template->populate($row);
			}
		}
		else
		{
			throw new Exception("Could not execute database query to find contract. " . '----' . $query . '----' . $link->errorCode());
		}

		return $email_template;
	}

	public function save(PDO $link)
	{
		return DAO::saveObjectToTable($link, 'candidate_email_templates', $this);
	}

	public function prepare_email(PDO $link, Candidate $candidate, $extra_information = '')
	{
		switch($this->email_type)
		{
			case 'candidate_registration_welcome':
				$email_template = str_replace('**CANDIDATE_NAME**', $candidate->firstnames . ' ' . $candidate->surname, $this->email_contents);
				break;
			case 'decline_email_sift':
				$email_template = str_replace('**CANDIDATE_NAME**', $candidate->firstnames . ' ' . $candidate->surname, $this->email_contents);
				break;
			case 'consider_email_sift':
				$email_template = str_replace('**CANDIDATE_NAME**', $candidate->firstnames . ' ' . $candidate->surname, $this->email_contents);
				$email_template = str_replace('**JOB_TITLE**', $extra_information['job_title'], $email_template);
				$email_template = str_replace('**JOB_REFERENCE**', $extra_information['code'], $email_template);
				break;
			case 'interview_invitation_brm':
				$email_template = str_replace('**CANDIDATE_NAME**', $candidate->firstnames . ' ' . $candidate->surname, $this->email_contents);
				$email_template = str_replace('**BRM_Name**', $extra_information['brm_name'], $email_template);
				$email_template = str_replace('**INTERVIEW_DATE**', $extra_information['interview_date'], $email_template);
				$email_template = str_replace('**INTERVIEW_TIME**', $extra_information['interview_time'], $email_template);
				$email_template = str_replace('**INTERVIEW_LOCATION**', $extra_information['interview_location'], $email_template);
				break;
			case 'age_inappropriate':
				$email_template = str_replace('**CANDIDATE_NAME**', $candidate->firstnames . ' ' . $candidate->surname, $this->email_contents);
				break;
			default:
				break;
		}
		return $email_template;

	}

	public function sendEmail($receiver_email, $subject, $contents)
	{
		return mail($receiver_email, $subject, $contents);
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
	public $email_type = NULL;
	public $email_subject = NULL;
	public $email_contents = NULL;
	public $sender = NULL;
	public $receiver_email = NULL;
}
?>