<?php
class Vacancy extends Entity
{
	public static function loadFromDatabase(PDO $link, $id)
	{

		if($id == '')
		{
			return null;
		}

		// this is odd re 04/11/2011
		if ( is_array($id) ) {
			$id = $id[0];
		}


		$key = addslashes((string)$id);
		$query = <<<HEREDOC
SELECT	
	organisations.trading_name, 
	vacancies.*, 
	lookup_vacancy_type.description as vac_desc, 
	vacancies.id AS vac_id 
FROM 
	vacancies, 
	organisations, 
	lookup_vacancy_type 
WHERE 
	vacancies.employer_id = organisations.id 
and vacancies.type = lookup_vacancy_type.id
and	vacancies.id='$key';
HEREDOC;
		$st = $link->query($query);



		$vacancy = null;
		if($st)
		{
			$row = $st->fetch();
			if($row)
			{
				$vacancy = new Vacancy();
				$vacancy->populate($row);


				if ( $vacancy->shift_pattern === NULL ) {
					// join all the shift data together to enable switch to single textbox
					$hours_per_week = $vacancy->hours_mon+$vacancy->hours_tues+$vacancy->hours_wed+$vacancy->hours_thurs+$vacancy->hours_fri+$vacancy->hours_sat+$vacancy->hours_sun;
					if ( is_int($hours_per_week) && $hours_per_week > 0 ) {
						$vacancy->shift_pattern = "General hours per week: ".$hours_per_week;
					}
					$vacancy->shift_pattern .= isset($vacancy->shifts_mon)?"\nMonday: ".$vacancy->shifts_mon:'';
					$vacancy->shift_pattern .= isset($vacancy->shifts_tues)?"\nTuesday: ".$vacancy->shifts_tues:'';
					$vacancy->shift_pattern .= isset($vacancy->shifts_wed)?"\nWednesday: ".$vacancy->shifts_wed:'';
					$vacancy->shift_pattern .= isset($vacancy->shifts_thurs)?"\nThursday: ".$vacancy->shifts_thurs:'';
					$vacancy->shift_pattern .= isset($vacancy->shifts_fri)?"\nFriday: ".$vacancy->shifts_fri:'';
					$vacancy->shift_pattern .= isset($vacancy->shifts_sat)?"\nSaturday: ".$vacancy->shifts_sat:'';
					$vacancy->shift_pattern .= isset($vacancy->shifts_sun)?"\nSunday: ".$vacancy->shifts_sun:'';
				}
				$vacancy->id = $id;
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
		// convert dates to correct format
		$this->live_date = Date::toMySQL($this->live_date);
		$this->expiry_date = Date::toMySQL($this->expiry_date);

		if(DB_NAME == "am_baltic" || DB_NAME == "am_baltic_demo")
		{
			if(isset($this->id) AND $this->id != '')
			{
				$sql = "SELECT status FROM vacancies WHERE id = " . $this->id;
				$status = DAO::getSingleValue($link, $sql);
				if($status != $this->status)
					$this->date_status_changed = date('Y-m-d');
			}
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

	public function update(PDO $link)
	{

		$id = addslashes((string)$this->id);
		$upd_sql = ' - 1 ';
		if ( isset($_REQUEST['rmv']) ) {
			$upd_sql = ' + 1 ';
		}

		$sql = <<<HEREDOC
UPDATE 
	vacancies
SET 
	no_of_vacancies = no_of_vacancies $upd_sql
WHERE
	id = '$id';
HEREDOC;
		DAO::execute($link, $sql);
	}


	public function isSafeToDelete(PDO $link)
	{
		return false;
	}


	public $id = null;
	public $code = NULL;
	public $no_of_vacancies = NULL;
	public $max_submissions = NULL;
	public $status = NULL;
	public $reason_not_filled = NULL;
	public $description = NULL;
	public $employer_id = NULL;
	public $trading_name = NULL;
	public $postcode = NULL;
	public $location = NULL;
	public $vac_desc = NULL;
	public $longitude = NULL;
	public $latitude = NULL;
	public $northing = NULL;
	public $easting = NULL;
	public $type = NULL;
	public $radius = NULL;
	public $radius_metres = NULL;
	public $live_date = NULL;
	public $expiry_date = NULL;
	public $job_title = NULL;
	public $award_sector = NULL;
	public $salary = NULL;
	public $hours_mon = NULL;
	public $hours_tues = NULL;
	public $hours_wed = NULL;
	public $hours_thurs = NULL;
	public $hours_fri = NULL;
	public $hours_sat = NULL;
	public $hours_sun = NULL;
	public $shifts_mon = NULL;
	public $shifts_tues = NULL;
	public $shifts_wed = NULL;
	public $shifts_thurs = NULL;
	public $shifts_fri = NULL;
	public $shifts_sat = NULL;
	public $shifts_sun = NULL;

	public $shift_pattern = NULL;

	public $person_spec = NULL;
	public $required_quals = NULL;
	public $misc = NULL;
	public $to_level_3 = NULL;
	public $prospects = NULL;
	public $interview_date = NULL;
	public $current_applications = NULL;
	public $new_applications = NULL;
	public $active = NULL;
	public $feedback = array( 'message' => NULL, 'background-color' => NULL, 'location' => NULL);

	// Changes to the Recruitment for Baltic
	public $source = NULL;
	public $brm = NULL;
	public $apprenticeship_type = NULL;
	public $dd = NULL;
	public $age = NULL;
	public $at_risk = NULL;
	public $induction_confirmed = NULL;
	public $induction_date = NULL;
	public $inductor = NULL;
	public $comments = NULL;
	public $created = NULL;

	public $training_provided = NULL;
	public $future_prospects = NULL;
	public $hrs_per_week = NULL;
	public $skills_req = NULL;
	public $client_contact_name = NULL;
	public $client_contact_email = NULL;
	public $client_contact_number = NULL;
	public $date_expected_to_fill = NULL;
	public $date_status_changed = NULL;
	public $region = NULL;
	public $job_type = NULL;
	public $job_hours = NULL;

	/*protected $audit_fields = array(
		'active'=>'Vacancy Active Status',
		'no_of_vacancies'=>'No of vacancies',
		'status'=>'Vacancy Status',
		'description'=>'Vacancy Description',
		'type'=>'Vacancy Type',
		'job_title'=>'Job Title'
	);*/

	protected $audit_fields = array(
		'active'=>'Vacancy Active Status',
		'no_of_vacancies'=>'No of vacancies',
		'status'=>'Vacancy Status',
		'description'=>'Vacancy Description',
		'type'=>'Vacancy Type',
		'job_title'=>'Job Title',
		'source' => 'Source',
		'created' => 'Created',
		'brm'=> 'Business Resource Manager',
		'apprenticeship_type' => 'Apprenticeship Type',
		'dd' => 'Due Diligence',
		'age' => 'Age',
		'at_risk' => 'At Risk',
		'induction_confirmed' => 'Induction Confirmed',
		'induction_date' => 'Induction Date',
		'inductor' => 'Inductor',
		'comments' => 'Additional Comments',
		'training_provided' => 'Training Provided',
		'future_prospects' => 'Future Prospects',
		'hrs_per_week' => 'Hours per week',
		'skills_req' => 'Skills Required',
		'client_contact_name' => 'Client Contact Name',
		'client_contact_email' => 'Client Contact Email',
		'client_contact_number' => 'Client Contact Number',
		'temp_perm' => 'Temp Permanent',
		'min_age' => 'Min Age',
		'max_age' => 'Max Age',
		'web_ref' => 'Web Reference',
		'start_salary'=> 'Start Salary',
		'region' => 'Region',
		'person_spec' => 'Person Specifications',
		'required_quals' => 'Required Qualifications',
		'misc' => 'Important Other Information',
		'to_level_3' => 'To level 3',
		'prospects' => 'Prospects',
		'interview_date' => 'Interview Date',
		'current_applications' => 'Current Applications',
		'date_expected_to_fill' => 'Date Expected To Fill',
		'date_status_changed' => 'Date Status Changed',
		'job_type' => 'Job Type',
		'job_hours' => 'Job Hours',
		'new_applications' => 'New Applications');
}
?>