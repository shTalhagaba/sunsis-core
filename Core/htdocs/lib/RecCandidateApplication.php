<?php
class RecCandidateApplication extends Entity
{
	public static function loadFromDatabaseByID(PDO $link, $id)
	{

		if($id == '')
		{
			return null;
		}

		$query = <<<HEREDOC

SELECT
	candidate_applications.*
FROM
	candidate_applications
WHERE
	candidate_applications.id = $id
HEREDOC;
		$st = $link->query($query);

		$candidate_application = null;
		if($st)
		{
			$row = $st->fetch();
			if($row)
			{
				$candidate_application = new RecCandidateApplication();
				$candidate_application->populate($row);
				$candidate_application->candidate = RecCandidate::loadFromDatabase($link, $candidate_application->candidate_id);
				$candidate_application->vacancy = RecVacancy::loadFromDatabase($link, $candidate_application->vacancy_id);
			}
		}
		else
		{
			throw new Exception("ERR: Could not execute database query to find candidate application. " . '----' . $query . '----' . $st->errorCode());
		}

		return $candidate_application;
	}

	public static function loadFromDatabaseByVacancyAndCandidate(PDO $link, $vacancy_id, $candidate_id)
	{
		if($vacancy_id == '' || $candidate_id == '')
		{
			return null;
		}

		$query = <<<HEREDOC

SELECT
	candidate_applications.*
FROM
	candidate_applications
WHERE
	candidate_applications.vacancy_id = $vacancy_id
	AND candidate_applications.candidate_id = $candidate_id;
HEREDOC;

		$st = $link->query($query);

		$candidate_application = null;
		if($st)
		{
			$row = $st->fetch();
			if($row)
			{
				$candidate_application = new RecCandidateApplication();
				$candidate_application->populate($row);
				$candidate_application->candidate = RecCandidate::loadFromDatabase($link, $candidate_application->candidate_id);
				$candidate_application->vacancy = RecVacancy::loadFromDatabase($link, $candidate_application->vacancy_id);
			}
		}
		else
		{
			throw new Exception("ERR: Could not execute database query to find candidate application. " . '----' . $query . '----' . $st->errorCode());
		}

		return $candidate_application;
	}

	public function save(PDO $link)
	{
		if(isset($this->id) && !is_null($this->id) && $this->id != '')
		{
			// Update the application sequence number
			if(!isset($this->seq) || is_null($this->seq) || $this->seq == '')
				$sql = "UPDATE candidate_applications SET seq=1 WHERE id=".$this->id;
			else
				$sql = "UPDATE candidate_applications SET seq=seq+1 WHERE id=".$this->id." AND seq=".$this->seq;
			//pre($sql);
			$affected_rows = $link->exec($sql);
			$this->seq = $this->seq + 1;
			if($affected_rows == 0)
			{
				throw new Exception("Someone has edited and saved this application (application #{$this->id}) while you were editing."
					." To avoid overwriting their changes, you must begin again. The previous screen will now be reloaded.", 5000);
			}
		}
		else
			$this->seq = 1;

		return DAO::saveObjectToTable($link, 'candidate_applications', $this);
	}

	public function getCandidateApplicationFullStatusHistory(PDO $link, $order_by = ' ORDER BY created ASC ')
	{
		return DAO::getResultset($link, "SELECT * FROM candidate_application_status WHERE application_id = '{$this->id}' {$order_by}", DAO::FETCH_ASSOC);
	}

	public function getCandidateApplicationScreeningQuestions(PDO $link)
	{
		return DAO::getResultset($link, "SELECT * FROM candidate_application_screening WHERE application_id = '{$this->id}' ", DAO::FETCH_ASSOC);
	}

	public function saveCandidateApplicationStatus(PDO $link, $status, $comments = '')
	{
		if(is_null($status))
			throw new Exception('Please provide the status for candidate application');

		$this->current_status = $status;
		$this->save($link);

		$obj = new stdClass();
		$obj->application_id = $this->id;
		$obj->status = $status;
		$obj->comments = $comments;
		$obj->created_by = $_SESSION['user']->id;
		DAO::saveObjectToTable($link, 'candidate_application_status', $obj);
	}

	public function saveCandidateApplicationScreeningQuestions(PDO $link, $question_id, $answer, $score)
	{
		$obj = new stdClass();
		$obj->application_id = $this->id;
		$obj->question_id = $question_id;
		$obj->answer = $answer;
		$obj->score = $score;
		DAO::saveObjectToTable($link, 'candidate_application_screening', $obj);
	}

	public function saveCandidateApplicationNextActions(PDO $link, $next_action, $next_action_date, $comments)
	{
		$obj = new stdClass();
		$obj->application_id = $this->id;
		$obj->next_action = $next_action;
		$obj->next_action_date = $next_action_date;
		$obj->comments = $comments;
		$obj->created_by = $_SESSION['user']->id;
		DAO::saveObjectToTable($link, 'candidate_application_next_actions', $obj);
	}

	public function getCandidateApplicationCurrentStatusDesc(PDO $link)
	{
		switch((int)$this->current_status)
		{
			case RecCandidateApplication::CREATED:
				return 'Not Screened';
			case RecCandidateApplication::SCREENED:
				return 'Screened';
			case RecCandidateApplication::TELEPHONE_INTERVIEWED:
				return 'Telephone Interviewed';
			case RecCandidateApplication::CV_SENT:
				return 'CV Sent';
			case RecCandidateApplication::INTERVIEW_SUCCESSFUL:
				return 'Interview Successful';
			case RecCandidateApplication::INTERVIEW_UNSUCCESSFUL:
				return 'Interview Unsuccessful';
			case RecCandidateApplication::SUNESIS_LEARNER:
				return 'Sunesis Learner';
			case RecCandidateApplication::WITHDRAWN:
				return 'Withdrawn';
			case RecCandidateApplication::REJECTED:
				return 'Rejected';
			default:
				return 'Unknown';
		}
	}

	public static function getStatusDesc($status)
	{
		switch((int)$status)
		{
			case RecCandidateApplication::CREATED:
				return 'Not Screened';
			case RecCandidateApplication::SCREENED:
				return 'Screened';
			case RecCandidateApplication::TELEPHONE_INTERVIEWED:
				return 'Telephone Interviewed';
			case RecCandidateApplication::CV_SENT:
				return 'CV Sent';
			case RecCandidateApplication::INTERVIEW_SUCCESSFUL:
				return 'Interview Successful';
			case RecCandidateApplication::INTERVIEW_UNSUCCESSFUL:
				return 'Interview Unsuccessful';
			case RecCandidateApplication::SUNESIS_LEARNER:
				return 'Sunesis Learner';
			case RecCandidateApplication::WITHDRAWN:
				return 'Withdrawn';
			case RecCandidateApplication::REJECTED:
				return 'Rejected';
			default:
				return 'Unknown';
		}
	}

	public function getVacancyTitle(PDO $link)
	{
		return $this->id != ''?DAO::getSingleValue($link, "SELECT vacancies.vacancy_title FROM vacancies WHERE vacancies.id = " . $this->vacancy_id):'';
	}
	public function getVacancyEmployerName(PDO $link)
	{
		return $this->id != ''?DAO::getSingleValue($link, "SELECT organisations.legal_name FROM organisations INNER JOIN vacancies ON organisations.id = vacancies.employer_id WHERE vacancies.id = '{$this->vacancy_id}'"):'';
	}

	public static function getInterviewOutcomeIcon($outcome)
	{
		if($outcome == 'successful')
			return '<img title="Successful" height="25" src="/images/smile-face.png" border="0" />';
		elseif($outcome == 'unsuccessful')
			return '<img title="Unsuccessful" height="25" src="/images/sad-face.png" border="0" />';
		else
			return '';
	}

	public static function getApplicationRAGIcon($rag)
	{
		switch($rag)
		{
			case 'G':
				return '<img title="Green (Highly Suitable)" height="30" src="/images/green_button.png" border="0" />';
			case 'A':
				return '<img title="Amber (Suitable)" height="30" src="/images/amber_button.png" border="0" />';
				break;
			case 'R':
				return '<img title="Red (UnSuitable)" height="30" src="/images/red_button.png" border="0" />';
				break;
			default:
				return '';
		}
	}

	public function getApplicationStatusUpdateDate(PDO $link)
	{
		$result = array();
		$result[0] = '';
		$result[1] = '';
		$result[2] = '';
		$result[3] = '';
		$result[4] = '';
		$result[5] = '';
		$result[6] = '';
		$result[98] = '';
		$result[99] = '';
		foreach($result AS $key => $value)
		{
			$result[$key] = DAO::getSingleValue($link, "SELECT created FROM candidate_application_status WHERE application_id = '{$this->id}' AND status = '{$key}' ORDER BY created DESC LIMIT 1");
		}
		return $result;
	}

	public $id = null;
	public $candidate_id = null;
	public $vacancy_id = null;
	public $created = null;
	public $current_status = null;
	public $created_by = null;
	public $screening_rag = null;
	public $screening_score = null;
	public $telephone_interview_score = null;
	public $telephone_interview_outcome = null;
	public $ftof_interview_level1 = null;
	public $ftof_interview_level2 = null;
	public $supplementary_question_1_answer = null;
	public $supplementary_question_2_answer = null;
	public $candidate = null;/* @var $candidate RecCandidate */
	public $vacancy = null;/* @var $vacancy RecVacancy*/

	public $seq = null;

	const CREATED = 0;
	const SCREENED = 1;
	const TELEPHONE_INTERVIEWED = 2;
	const CV_SENT = 3;
	const INTERVIEW_SUCCESSFUL = 4;
	const INTERVIEW_UNSUCCESSFUL = 5;
	const SUNESIS_LEARNER = 6;
	const REJECTED = 99;
	const WITHDRAWN = 98;

}
?>