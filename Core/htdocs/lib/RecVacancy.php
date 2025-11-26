<?php
class RecVacancy extends Entity
{
    public $radius = null;
    public $radius_metres = null;

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
	vacancies
WHERE 
	vacancies.id='$key';
HEREDOC;
		$st = $link->query($query);

		$vacancy = null;
		if($st)
		{
			$row = $st->fetch();
			if($row)
			{
				$vacancy = new RecVacancy();
				$vacancy->populate($row);
			}
		}
		else
		{
			throw new Exception("ERR: Could not execute database query to find vacancy. " . '----' . $query . '----' . $st->errorCode());
		}

		return $vacancy;
	}

	public function save(PDO $link)
	{
		if ( isset($this->postcode) )
		{
			$loc = new GeoLocation();
			$loc->setPostcode($this->postcode, $link);
			$this->longitude = $loc->getLongitude();
			$this->latitude = $loc->getLatitude();
			$this->easting = $loc->getEasting();
			$this->northing = $loc->getNorthing();
		}
		return DAO::saveObjectToTable($link, 'vacancies', $this);
	}

	public function delete(PDO $link)
	{
		$vacancy = addslashes((string)$this->id);

		// Delete the vacancy and associated candidate requests.
		$sql = <<<HEREDOC
DELETE FROM 
	vacancies, 
	candidate_applications 
USING 
	vacancies 
	LEFT JOIN candidate_applications 
		ON (candidate_applications.vacancy_id = vacancies.id) 
WHERE 
	vacancies.id = '$vacancy';
HEREDOC;
		DAO::execute($link, $sql);
	}

	public function isSafeToDelete(PDO $link)
	{
		return false;
	}

	public function getProgressions(PDO $link, $candidate = false, $learner = false)
	{
		$count = DAO::getSingleValue($link, "SELECT COUNT(*) FROM candidate_applications INNER JOIN vacancies ON candidate_applications.`vacancy_id` = vacancies.`id` INNER JOIN users ON candidate_applications.`candidate_id` = users.`id` WHERE vacancies.id = {$this->id} AND candidate_applications.application_status = 33 ;");
		return $count==''?0:$count;
	}

	public function getNumberOfAllApplications(PDO $link, $candidate = false, $learner = false)
	{
		if($candidate)
			$count = (int)DAO::getSingleValue($link, "SELECT COUNT(*) FROM candidate_applications INNER JOIN vacancies ON candidate_applications.`vacancy_id` = vacancies.`id` INNER JOIN candidate ON candidate_applications.`candidate_id` = candidate.`id` WHERE vacancies.id = {$this->id} ;");
		elseif($learner)
			$count = (int)DAO::getSingleValue($link, "SELECT COUNT(*) FROM candidate_applications INNER JOIN vacancies ON candidate_applications.`vacancy_id` = vacancies.`id` INNER JOIN users ON candidate_applications.`candidate_id` = users.`id` WHERE vacancies.id = {$this->id} ;");
		return $count;
	}

	public function getNumberOfApplicationsExcludingRemoved(PDO $link)
	{
		$count = (int)DAO::getSingleValue($link, "SELECT COUNT(*) FROM candidate_applications INNER JOIN vacancies ON candidate_applications.`vacancy_id` = vacancies.`id` INNER JOIN users ON candidate_applications.`candidate_id` = users.`id` AND candidate_applications.application_status != '99' WHERE vacancies.id = {$this->id} ;");
		return $count;
	}

	public function getNumberOfNotScreenedApplications(PDO $link)
	{
		$count = (int)DAO::getSingleValue($link, "SELECT COUNT(*) FROM candidate,candidate_applications WHERE candidate.username IS NULL AND candidate.id = candidate_applications.candidate_id AND candidate_applications.vacancy_id = {$this->id} AND candidate_applications.current_status = '" . RecCandidateApplication::CREATED . "' GROUP BY vacancy_id;");
		return $count==''?0:$count;
	}
	public function getNumberOfScreenedApplications(PDO $link, $candidate = false, $learner = false)
	{
		$count = (int)DAO::getSingleValue($link, "SELECT COUNT(*) FROM candidate,candidate_applications WHERE candidate.username IS NULL AND candidate.id = candidate_applications.candidate_id AND candidate_applications.vacancy_id = {$this->id} AND candidate_applications.current_status = '" . RecCandidateApplication::SCREENED . "' GROUP BY vacancy_id;");
		return $count==''?0:$count;
	}
	public function getNumberOfTelephonicInterviewedApplications(PDO $link, $candidate = false, $learner = false)
	{
		$count = (int)DAO::getSingleValue($link, "SELECT COUNT(*) FROM candidate,candidate_applications WHERE candidate.username IS NULL AND candidate.id = candidate_applications.candidate_id AND candidate_applications.vacancy_id = {$this->id} AND candidate_applications.current_status = '" . RecCandidateApplication::TELEPHONE_INTERVIEWED . "' GROUP BY vacancy_id;");
		return $count==''?0:$count;
	}
	public function getNumberOfCVSentApplications(PDO $link, $candidate = false, $learner = false)
	{
		$count = (int)DAO::getSingleValue($link, "SELECT COUNT(*) FROM candidate,candidate_applications WHERE candidate.username IS NULL AND candidate.id = candidate_applications.candidate_id AND candidate_applications.vacancy_id = {$this->id} AND candidate_applications.current_status = '" . RecCandidateApplication::CV_SENT . "' GROUP BY vacancy_id;");
		return $count==''?0:$count;
	}
	public function getNumberOfSuccessfulApplications(PDO $link, $candidate = false, $learner = false)
	{
		$count = (int)DAO::getSingleValue($link, "SELECT COUNT(*) FROM candidate,candidate_applications WHERE candidate.username IS NULL AND candidate.id = candidate_applications.candidate_id AND candidate_applications.vacancy_id = {$this->id} AND candidate_applications.current_status = '" . RecCandidateApplication::INTERVIEW_SUCCESSFUL . "' GROUP BY vacancy_id;");
		return $count==''?0:$count;
	}
	public function getNumberOfUnsuccessfulApplications(PDO $link, $candidate = false, $learner = false)
	{
		$count = (int)DAO::getSingleValue($link, "SELECT COUNT(*) FROM candidate,candidate_applications WHERE candidate.username IS NULL AND candidate.id = candidate_applications.candidate_id AND candidate_applications.vacancy_id = {$this->id} AND candidate_applications.current_status = '" . RecCandidateApplication::INTERVIEW_UNSUCCESSFUL . "' GROUP BY vacancy_id;");
		return $count==''?0:$count;
	}
	public function getNumberOfRejectedApplications(PDO $link, $candidate = false, $learner = false)
	{
		$count = (int)DAO::getSingleValue($link, "SELECT COUNT(*) FROM candidate,candidate_applications WHERE candidate.username IS NULL AND candidate.id = candidate_applications.candidate_id AND candidate_applications.vacancy_id = {$this->id} AND candidate_applications.current_status = '" . RecCandidateApplication::REJECTED . "' GROUP BY vacancy_id;");
		return $count==''?0:$count;
	}
	public function getNumberOfWithdrawnApplications(PDO $link, $candidate = false, $learner = false)
	{
		$count = (int)DAO::getSingleValue($link, "SELECT COUNT(*) FROM candidate,candidate_applications WHERE candidate.username IS NULL AND candidate.id = candidate_applications.candidate_id AND candidate_applications.vacancy_id = {$this->id} AND candidate_applications.current_status = '" . RecCandidateApplication::WITHDRAWN . "' GROUP BY vacancy_id;");
		return $count==''?0:$count;
	}
	public function getNumberOfSunesisLearners(PDO $link)
	{
		$count = (int)DAO::getSingleValue($link, "SELECT COUNT(*) FROM candidate,candidate_applications WHERE candidate.username IS NOT NULL AND candidate.id = candidate_applications.candidate_id AND candidate_applications.vacancy_id = {$this->id} AND candidate_applications.current_status = '" . RecCandidateApplication::SUNESIS_LEARNER . "' GROUP BY vacancy_id;");
		return $count==''?0:$count;
	}

	public function isVacancyFull(PDO $link)
	{
		$total = $this->getNumberOfApprovedApplications($link, false, true) + $this->getNumberOfSuccessfulApplications($link, false, true) + $this->getNumberOfUnsuccessfulApplications($link, false, true) + $this->getProgressions($link, false, true);
		return $total >= $this->max_submissions?true:false;
	}

	public function getEmployerName(PDO $link)
	{
		return DAO::getSingleValue($link, "SELECT legal_name FROM organisations WHERE id = '{$this->employer_id}'");
	}

	public function getSectorDescription(PDO $link)
	{
		return DAO::getSingleValue($link, "SELECT description FROM lookup_sector_types WHERE id = '{$this->sector}'");
	}

	public function getLocation(PDO $link)
	{
		$sql = <<<SQL
SELECT
  CONCAT(
    COALESCE(full_name),
    ' (',
    COALESCE(`address_line_1`, ''),
    ' ',
    COALESCE(`address_line_2`, ''),
    ', ',
    COALESCE(`address_line_3`, ''),
    ')'
  ) AS location
FROM
  locations
WHERE locations.`id` = '$this->location_id'
ORDER BY locations.full_name
SQL;
		return DAO::getSingleValue($link, $sql);
	}

	public function getLocationTelephone(PDO $link)
	{
		return DAO::getSingleValue($link, "SELECT locations.telephone FROM locations WHERE locations.`id` = '$this->location_id'");
	}

	public function getSupplementaryQuestion1Description(PDO $link)
	{
		return DAO::getSingleValue($link, "SELECT description FROM lookup_vacancies_supp_questions WHERE id = '{$this->suppl_q_1}'");
	}

	public function getSupplementaryQuestion2Description(PDO $link)
	{
		return DAO::getSingleValue($link, "SELECT description FROM lookup_vacancies_supp_questions WHERE id = '{$this->suppl_q_2}'");
	}

	public static function isKillerQuestion(PDO $link, $question_id)
	{
		$killer = (int)DAO::getSingleValue($link, "SELECT type FROM rec_questions WHERE id = '{$question_id}'");
		if($killer == '2')
		{
			//var_dump("SELECT killer FROM rec_questions WHERE id = '{$question_id}'");
			return true;
		}
		else
			return false;
	}

	public static function isKillerAnswerGivenForKillerQuestion(PDO $link, $question_id, $answer)
	{
		if($question_id == '' || $answer == '')
			return false;

		$killer_answer = DAO::getSingleValue($link, "SELECT killer_answer FROM rec_questions WHERE id = '{$question_id}'");
		if($killer_answer == $answer)
		{
			//var_dump("SELECT killer_answer FROM rec_questions WHERE id = '{$question_id}'");
			return true;
		}
		else
			return false;
	}

	public $id = NULL;
	public $vacancy_reference = NULL;
	public $location_type = "Standard";
	public $location_id = NULL;
	public $app_framework = NULL;
	public $closing_date = NULL;
	public $short_description = NULL;
	public $no_of_positions = NULL;
	public $vacancy_title = NULL;
	public $created = NULL;
	public $vacancy_type = "Offline";
	public $vacancy_url = NULL;
	public $full_description = NULL;
	public $suppl_q_1 = NULL;
	public $suppl_q_2 = NULL;
	public $contact_person = NULL;
	public $expected_duration = NULL;
	public $future_prospects = NULL;
	public $interview_from_date = NULL;
	public $personal_qualities = NULL;
	public $possible_start_date = NULL;
	public $qualifications_required = NULL;
	public $skills_required = NULL;
	public $training_to_be_provided = NULL;
	public $wage_type = NULL;
	public $wage = NULL;
	public $wage_text = NULL;
	public $working_week = NULL;
	public $other_info = NULL;
	public $employer_website = NULL;
	public $vacancy_manager = NULL;
    public $vacancy_guid = NULL;
    public $employer_id = NULL;
    public $provider_id = NULL;

    public $hide_salary = '0';
    public $longitude = NULL;
    public $latitude = NULL;
    public $northing = NULL;
    public $easting = NULL;
    public $uploaded_to_nas = '0';
    public $framework_id = NULL;
    public $max_submissions = NULL;
    public $max_approved_submissions = NULL;
    public $is_active = '1';
	public $is_archived = '0';
    public $postcode = NULL;
    public $sector = NULL;

}
?>