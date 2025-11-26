<?php
class RecCandidate extends Entity
{
	public static function loadFromDatabase(PDO $link, $id)
	{
		if(is_null($id) || $id == '')
			throw new Exception('Candidate ID not specified');

		$query = "SELECT * FROM candidate WHERE id=" . addslashes((string)$id) . ";";

		$st = $link->query($query);

		$candidate = new RecCandidate();
		if( $st )
		{
			$row = $st->fetch();
			if( $row )
			{
				$candidate = new RecCandidate();
				$candidate->populate($row);
			}
		}
		else
		{
			throw new DatabaseException($link, $query);
		}

		// populate with the candidate qualifications
		$query = "SELECT candidate_qualification.* FROM candidate_qualification WHERE candidate_qualification.candidate_id = ".addslashes((string)$id);
		$st = $link->query($query);

		if( $st ) {
			while( $edu_row = $st->fetch() )
			{
				$candidate->qualifications[] = array(
					'qualification_level' => $edu_row['qualification_level'],
					'qualification_subject' => $edu_row['qualification_subject'],
					'qualification_grade' => $edu_row['qualification_grade'],
					'qualification_date' => $edu_row['qualification_date'],
					'institution' => $edu_row['institution'],
				);
			}
		}
		else
		{
			throw new DatabaseException($link, $query);
		}

		// populate with the candidate employment history
		$query = "SELECT candidate_history.* FROM candidate_history WHERE candidate_history.candidate_id = ".addslashes((string)$id);
		$st = $link->query($query);

		if( $st )
		{
			while( $edu_row = $st->fetch() )
			{
				$candidate->employments[] = array(
					'start_date' => $edu_row['start_date'],
					'end_date' => $edu_row['end_date'],
					'company_name' => $edu_row['company_name'],
					'job_title' => $edu_row['job_title'],
					'skills' => $edu_row['skills'],
				);
			}
		}
		else
		{
			throw new DatabaseException($link, $query);
		}

		return $candidate;
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

		$this->modified = "";
		$this->created = ($this->id == "") ? date('Y-m-d H:i:s') : $this->created;
		$this->telephone = str_replace('&#8237;', '', $this->telephone);
		$this->telephone = str_replace('&#8236;', '', $this->telephone);
		$this->mobile = str_replace('&#8237;', '', $this->mobile);
		$this->mobile = str_replace('&#8236;', '', $this->mobile);
		$this->guardian_contact = str_replace('&#8237;', '', $this->guardian_contact ?: '');
		$this->national_insurance = str_replace(' ', '', $this->national_insurance);

		return DAO::saveObjectToTable($link, 'candidate', $this);
	}

	public function getCVLink($id, $name)
	{
		$cv_file_link = '&nbsp;';
		if ( file_exists(DATA_ROOT."/uploads/".DB_NAME."/recruitment/cv_1_".$id.".doc") ) {
			$cv_file_link = '<a href="do.php?_action=downloader&path=/recruitment/&f=cv_1_'.$id.'.doc">' . $name . ' - CV</a> (doc)';
		}
		elseif( file_exists(DATA_ROOT."/uploads/".DB_NAME."/recruitment/cv_1_".$id.".docx") ) {
			$cv_file_link = '<a href="do.php?_action=downloader&path=/recruitment&f=cv_1_'.$id.'.docx">' . $name . ' - CV</a> (docx)';
		}
		elseif( file_exists(DATA_ROOT."/uploads/".DB_NAME."/recruitment/cv_1_".$id.".pdf") ) {
			$cv_file_link = '<a href="do.php?_action=downloader&path=/recruitment/&f=cv_1_'.$id.'.pdf">' . $name . ' - CV</a> (pdf)';
		}
		return $cv_file_link;
	}

	public function getPhotoPath()
	{

		$root = Repository::getRoot();
		$photo_root = $root.'/photos';
		if(!is_dir($photo_root)){
			$photo_root = null;
		}
		$user_root = $root.'/'.$this->id;
		if(!is_dir($user_root)){
			$user_root = null;
		}
		$user_photo_root =  $root.'/recruitment';
		if(!is_dir($user_photo_root)){
			$user_photo_root = null;
		}

		// (2)
		if($user_photo_root){
			$images = glob($user_photo_root.'/photo_'. $this->id .'.{jpg,jpeg,gif,png,JPG,JPEG,GIF,PNG}', GLOB_BRACE);
			if(count($images) > 0){
				return $images[0]; // return first image in the glob
			}
		}
		// (3)
		if($user_root){
			$images = glob($user_root.'/*.{jpg,jpeg,gif,png,JPG,JPEG,GIF,PNG}', GLOB_BRACE);
			foreach($images as $image){
				if(stripos($image, 'signat') === false){
					return $image; // return first image that is not a user's signature
				}
			}
		}
		return null;
	}

	public function save_candidate_employments(PDO $link, $employments_xml)
	{
		$employments = XML::loadSimpleXML($employments_xml);
		$values = '';
		$query = "";
		foreach($employments->employment as $employment)
		{
			$employment->company_name = str_replace("'","\'", $employment->company_name);
			$employment->job_title = str_replace("'","\'", $employment->job_title);
			$employment->skills = str_replace("'","\'", $employment->skills);
			$employment->company_name = str_replace("&"," and ", $employment->company_name);
			$employment->job_title = str_replace("&"," and ", $employment->job_title);
			$employment->skills = str_replace("&"," and ", $employment->skills);

			$start_date = "NULL";
			$end_date = "NULL";
			if($employment->start_date != 'dd/mm/yyyy' && $employment->start_date != '' && $employment->start_date != '0000-00-00')
			{
				$d = new Date($employment->start_date);
				$start_date = "'" . $d->getYear() . '-' . $d->getMonth() . '-' . $d->getDays() . "'";
			}
			if($employment->end_date != 'dd/mm/yyyy' && $employment->end_date != '' && $employment->end_date != '0000-00-00')
			{
				$d = new Date($employment->end_date);
				$end_date = "'" . $d->getYear() . '-' . $d->getMonth() . '-' . $d->getDays() . "'";
			}
			$query .= " INSERT INTO candidate_history (candidate_id, start_date, end_date, company_name, job_title, skills) VALUES (" . $this->id . ", " . $start_date . ", " . $end_date . ", '" . $employment->company_name . "', '" . $employment->job_title . "', '" . $employment->skills . "'); ";
		}

		if($query != '')
		{
			$sql2 = <<<HEREDOC
DELETE FROM
candidate_history
WHERE candidate_id = '$this->id'
HEREDOC;
			DAO::execute($link, $sql2);

			DAO::execute($link, $query);
		}
	}



	private function checkForDuplicates(PDO $link)
	{
		$firstnames = strtolower(trim($this->firstnames));
		$surname = strtolower(trim($this->surname));
		$sql = <<<SQL
SELECT
	COUNT(*)
FROM
	users
WHERE
	users.type = '5'
	AND LOWER(TRIM(users.firstnames)) = '{$firstnames}'
	AND LOWER(TRIM(users.firstnames)) = '{$surname}'
	AND users.dob = '{$this->dob}'
SQL;

		return (int)DAO::execute($link, $sql);
	}

	private function getUniqueUsername(PDO $link, $firstnames, $surname)
	{
		$number_of_attempts = 0;
		$i = 1;
		do
		{
			$number_of_attempts++;
			if($number_of_attempts > 20)
				return null;
			$username = strtolower(substr(substr($firstnames, 0, 1).$surname, 0, 20));
			$username = str_replace(' ', '', $username);
			$username = str_replace("'", '', $username);
			$username = str_replace('"', '', $username);
			$username = $username . $i;
			$i++;
		}while((int)DAO::getSingleValue($link, "SELECT COUNT(*) FROM users WHERE username = '$username'") > 0);
		return strtolower($username);
	}

	public function getAssessorName(PDO $link)
	{
		if($this->assessor == '' || is_null($this->assessor))
			return '';
		return DAO::getSingleValue($link, "SELECT CONCAT(firstnames, ' ', surname) FROM users WHERE id = '{$this->assessor}'");
	}

	public function convertToLearner (PDO $link, RecVacancy $vacancy, $assessor_id = '' )
	{
		if( $this->id != '' && !is_null($vacancy) )
		{
			$this->username =  $this->getUniqueUsername($link, $this->firstnames, $this->surname);
			$learner = new User();
			$learner->firstnames = $this->firstnames;
			$learner->surname = $this->surname;
			$learner->dob = $this->dob;
			$learner->gender = $this->gender;
			$learner->ethnicity = $this->ethnicity;
			$learner->ni = str_replace(' ', '', $this->national_insurance);
			$learner->home_address_line_1 = $this->address1;
			$learner->home_address_line_2 = $this->address2;
			$learner->home_address_line_3 = $this->borough;
			$learner->home_address_line_4 = $this->county;
			$learner->home_postcode = $this->postcode;
			$learner->home_telephone = trim($this->telephone);
			$learner->home_mobile = trim($this->mobile);
			$learner->home_email = trim($this->email);
			$learner->home_fax = trim($this->fax);
			$learner->home_mobile = trim($this->mobile);
			$learner->username = $this->username;
			$learner->password = 'W0rdpalpha55';
			$learner->pwd_sha1 = sha1('W0rdpalpha55');
			$learner->web_access = 0;
			$learner->type = User::TYPE_LEARNER;

			$learner->employer_id = $vacancy->employer_id;
			$learner->employer_location_id = $vacancy->location_id;
			$location = Location::loadFromDatabase($link, $vacancy->location_id);
			$learner->work_address_line_1 = $location->address_line_1;
			$learner->work_address_line_2 = $location->address_line_2;
			$learner->work_address_line_3 = $location->address_line_3;
			$learner->work_address_line_4 = $location->address_line_4;
			$learner->work_postcode = $location->postcode;
			$learner->work_telephone = $location->telephone;
			$learner->work_fax = $location->fax;

			$learner->save($link);

			$this->enrolled = $vacancy->id;
						if($assessor_id != '')
				$this->assessor = $assessor_id;
			$this->save($link);

			unset($location);

			return $learner->id;
		}
		else
		{
			return 'Cannot build new learner';
		}

	}

	public function getCandidateLLDDOptions(PDO $link)
	{
		return DAO::getSingleColumn($link, "SELECT lldd FROM candidate_lldd WHERE candidate_id = '{$this->id}'");
	}

	public function saveCandidateNotes(PDO $link, $note)
	{
		$obj = new stdClass();
		$obj->candidate_id = $this->id;
		$obj->note = $note;
		$obj->created_by = $_SESSION['user']->id;
		DAO::saveObjectToTable($link, 'candidate_notes', $obj);
	}

	public function getShiftPattern(PDO $link)
	{
		return DAO::getObject($link, "SELECT * FROM candidate_shift_patterns WHERE candidate_id = '{$this->id}' LIMIT 1");
	}

	public static function getCandidateIDFromBasicDetails(PDO $link, $basic_details)
	{
		if(!is_array($basic_details))
			throw new UnauthorizedException();

		$valid_keys = array('firstnames', 'surname', 'dob', 'postcode');

		foreach($basic_details AS $key => &$value)
		{
			if(!in_array($key, $valid_keys))
				throw new UnauthorizedException();

			$value = strtolower(trim($value));
		}

		$sql = new SQLStatement("SELECT id FROM candidate");
		$sql->setClause("WHERE LOWER(TRIM(firstnames)) = " . $basic_details['firstnames']);
		$sql->setClause("WHERE LOWER(TRIM(surname)) = " . $basic_details['surname']);
		$sql->setClause("WHERE dob = '" . $basic_details['dob'] . "'");
		$sql->setClause("WHERE LOWER(TRIM(postcode)) = " . $basic_details['postcode']);

		$candidate_id = DAO::getSingleValue($link, $sql->__toString());
		if($candidate_id != '')
		{
			$candidate = RecCandidate::loadFromDatabase($link, $candidate_id);
			if(!is_null($candidate))
				return $candidate;
			else
				return false;
		}
		else
		{
			return false;
		}
	}

	public function getGCSEEnglishDetails(PDO $link)
	{
		return DAO::getObject($link, "SELECT * FROM candidate_qualification WHERE candidate_id = '{$this->id}' AND qualification_level = 'GCSE' AND qualification_subject = 'English Language'");
	}

	public function getGCSEMathsDetails(PDO $link)
	{
		return DAO::getObject($link, "SELECT * FROM candidate_qualification WHERE candidate_id = '{$this->id}' AND qualification_level = 'GCSE' AND qualification_subject = 'Maths'");
	}

	public function getGenderDesc()
	{
		switch(strtolower($this->gender))
		{
			case 'm':
				return 'Male';
			case 'f':
				return 'Female';
			case 'u':
				return 'Undefined';
			case 'w':
				return 'Withheld';
			default:
				return '';
		}
	}

	public $id = NULL;
	public $firstnames = NULL;
	public $surname = NULL;
	public $gender = NULL;
	public $ethnicity = NULL;
	public $dob = NULL;
	public $national_insurance = NULL;
	public $address1 = NULL;
	public $address2 = NULL;
	public $borough = NULL;
	public $county = NULL;
	public $postcode = NULL;
	public $telephone = NULL;
	public $mobile = NULL;
	public $fax = NULL;
	public $email = NULL;
	public $employment_status = NULL;
	public $hours_per_week = NULL;
	public $time_last_worked = NULL;
	public $last_education = NULL;
	public $previous_qualification = NULL;
	public $created = NULL;
	public $modified = NULL;
	public $longitude = NULL;
	public $latitude = NULL;
	public $northing = NULL;
	public $easting = NULL;
	public $enrolled = NULL;
	public $sunesis_learner_id = NULL;
	public $successful_application_id = NULL;
	public $lldd = NULL;
	public $username = NULL;
	public $assessor = NULL;
	public $guardian_email = NULL;
	public $guardian_contact = NULL;
	public $l45 = NULL;

	public $qualifications = array();
	public $employments = array();

}
?>