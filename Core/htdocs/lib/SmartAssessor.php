<?php

class SmartAssessor
{
	/**
	 * @param bool $readOnly
	 * @throws Exception
	 */
	public function __construct($readOnly = false)
	{
	
		$this->_readOnly = $readOnly ? true : false;
		$this->_wsdl = SystemConfig::get("smartassessor.soap.wsdl");
		$this->_apiKey = SystemConfig::get("smartassessor.soap.api_key");
		$this->_namespace = SystemConfig::get("smartassessor.soap.namespace");
		$this->_integrationEnabled = SystemConfig::get("smartassessor.soap.enabled");
		if (!$this->_wsdl) {
			throw new Exception("Missing configuration parameter: smartassessor.soap.wsdl");
		}
		if (!$this->_apiKey) {
			throw new Exception("Missing configuration parameter: smartassessor.soap.api_key");
		}
		if (!$this->_namespace) {
			throw new Exception("Missing configuration parameter: smartassessor.soap.namespace");
		}
		if (!$this->_integrationEnabled) {
			$this->_integrationEnabled = false; // Normalise
		}
	}


	/**
	 * @return array
	 * @throws Exception
	 */
	public function getEmployers()
	{
		$client = $this->_getSoapClient();
		$employers = array();

		try	{
			$result = $client->getAllEmployerDetail();
			if (isset($result->GetAllEmployerDetailResult->Employer)) {
			    if(count($result->GetAllEmployerDetailResult->Employer) == 1){
                $employers[0] = $result->GetAllEmployerDetailResult->Employer;
			    } else {
				$employers = (array) $result->GetAllEmployerDetailResult->Employer;
                }
			} else {
				$employers = array();
			}
		} catch (SoapFault $e) {
			throw new Exception($e->getMessage() . PHP_EOL . PHP_EOL . $client->getLastRequest(), $e->getCode(), $e);
		}

		// Normalise
		$validFields = array('Name', 'EdsId', 'SunesisId', 'SmartAssessorId', 'AddressLine1', 'AddressLine2', 'AddressTown',
			'AddressCounty', 'AddressPostCode', 'Telephone', 'KeyContactName', 'KeyContactEmail');
		foreach ($employers as &$employer) {
			foreach ($validFields as $vf) {
				if(!isset($employer->$vf) || $employer->$vf === '') {
					$employer->$vf = null;
				}
			}
		}
		return $employers;
	}


	/**
	 * @return array
	 * @throws Exception
	 */
	public function getLearners()
	{
		$client = $this->_getSoapClient();
		$learners = array();

		try	{
			$result = $client->getAllLearnerDetail();
			if (isset($result->GetAllLearnerDetailResult->Learner)) {
			    if(count($result->GetAllLearnerDetailResult->Learner) == 1){
			    $learners[0] = $result->GetAllLearnerDetailResult->Learner;
			    } else {
                $learners = (array) $result->GetAllLearnerDetailResult->Learner;
			    }

			} else {
				$learners = array();
			}
		} catch (SoapFault $e) {
			throw new Exception($e->getMessage() . PHP_EOL . PHP_EOL . $client->getLastRequest(), $e->getCode(), $e);
		}

		// Normalise
		$validFields = array('FamilyName', 'GivenNames', 'ULN', 'NINumber', 'Domicile', 'Email', 'DateOfBirth', 'Sex',
			'TelNumber', 'Mobile', 'LlddDisability', 'LlddLearningDifficulty',
			'EmployerSunesisId', 'EmployerSmartAssessorId',
			'HomeAddressLine1', 'HomeAddressLocality', 'HomeAddressTown',
			'HomeAddressCounty', 'HomeAddressPostCode', 'LearnerLink',
			'SunesisId', 'SmartAssessorId');
		foreach ($learners as &$learner) {
			if (isset($learner->DateOfBirth)) {
				$learner->DateOfBirth = Date::toMySQL($learner->DateOfBirth);
			}
			foreach ($validFields as $vf) {
				if(!isset($learner->$vf) || $learner->$vf === '') {
					$learner->$vf = null;
				}
			}
			if (!is_null($learner->LlddDisability) && !is_numeric($learner->LlddDisability)) {
				$learner->LlddDisability = null;
			}
			if (!is_null($learner->LlddLearningDifficulty) && !is_numeric($learner->LlddLearningDifficulty)) {
				$learner->LlddLearningDifficulty = null;
			}
			if (!is_null($learner->Sex) && strlen($learner->Sex) > 0) {
				$learner->Sex = strtoupper($learner->Sex[0]);
				if ($learner->Sex != 'M' && $learner->Sex != 'F') {
					$learner->Sex = null;
				}
			}
		}

		return $learners;
	}


    /**
	 * @return array
	 * @throws Exception
	 */
	public function getAssessors()
	{
		$client = $this->_getSoapClient();
		$assessors = array();

		try	{
			$result = $client->getAllAssessorDetail();
			if (isset($result->GetAllAssessorDetailResult->Assessor)) {
			    if(count($result->GetAllAssessorDetailResult->Assessor) == 1){
			    $assessors[0] = $result->GetAllAssessorDetailResult->Assessor;
			    } else {
                $assessors = (array) $result->GetAllAssessorDetailResult->Assessor;
			    }
			} else {
				$assessors = array();
			}
		} catch (SoapFault $e) {
			throw new Exception($e->getMessage() . PHP_EOL . PHP_EOL . $client->getLastRequest(), $e->getCode(), $e);
		}

		// Normalise
		$validFields = array('FirstName', 'LastName', 'UserName', 'Password', 'Region', 'Email', 'Telephone', 'Mobile', 'SunesisId', 'SmartAssessorId','UserType');
		foreach ($assessors as &$assessor) {
			foreach ($validFields as $vf) {
				if(!isset($assessor->$vf) || $assessor->$vf === '') {
					$assessor->$vf = null;
				}
			}
		}

		return $assessors;
	}

    /**
	 * @return array
	 * @throws Exception
	 */
	public function getReviews()
	{
		$client = $this->_getSoapClient();
		$reviews = array();

		try	{
			$result = $client->getAllSessionDetail();
			if (isset($result->GetAllSessionDetailResult->Session)) {
			    if(count($result->GetAllSessionDetailResult->Session) == 1){
                $reviews[0] = $result->GetAllSessionDetailResult->Session;
                } else {
                $reviews = (array) $result->GetAllSessionDetailResult->Session;
                }
			} else {
				$reviews = array();
			}
		} catch (SoapFault $e) {
			throw new Exception($e->getMessage() . PHP_EOL . PHP_EOL . $client->getLastRequest(), $e->getCode(), $e);
		}

		// Normalise
		$validFields = array('SmartAssessorId', 'SunesisId', 'LearnerSmartAssessorId', 'AssessorSmartAssessorId', 'QANCode', 'StartTime', 'Comments', 'Status','TypeofReview');
		foreach ($reviews as &$review) {
			foreach ($validFields as $vf) {
				if(!isset($review->$vf) || $review->$vf === '') {
					$review->$vf = null;
				}
			}
		}

		return $reviews;
	}


    /**
	 * @return array
	 * @throws Exception
	 */
	public function getProgresstrack()
	{
		$client = $this->_getSoapClient();
		$Progresstracks = array();

		try	{
			$result = $client->GetCourseProgressDetail();
			if (isset($result->GetCourseProgressDetailResult->CourseProgress)) {
			    if(count($result->GetCourseProgressDetailResult->CourseProgress) == 1){
			    $Progresstracks[0] = $result->GetCourseProgressDetailResult->CourseProgress;
			    } else {
                $Progresstracks = (array) $result->GetCourseProgressDetailResult->CourseProgress;
			    }

			} else {
				$Progresstracks = array();
			}
		} catch (SoapFault $e) {
			throw new Exception($e->getMessage() . PHP_EOL . PHP_EOL . $client->getLastRequest(), $e->getCode(), $e);
		}

        //pre($Progresstracks);

		// Normalise
		$validFields = array('LearnerSmartAssessorId', 'QANCode', 'QualificationTitle', 'Progress', 'SunesisId', 'SmartAssessorId','Progress_AssessedPC','Progress_MappedPC','TimeLinePerc','CourseStatus');
		foreach ($Progresstracks as &$Progresstrack) {
			foreach ($validFields as $vf) {
				if(!isset($Progresstrack->$vf) || $Progresstrack->$vf === '') {
					$Progresstrack->$vf = null;
				}
			}
		}

		return $Progresstracks;
	}


    /**
	 * @return array
	 * @throws Exception
	 */
	public function getGetLearnerCourseDetail()
	{
		$client = $this->_getSoapClient();
		$LearnerCourses = array();

		try	{
			$result = $client->GetLearnerCourseDetail();
			if (isset($result->GetLearnerCourseDetailResult->CourseProgress)) {
			      if(count($result->GetLearnerCourseDetailResult->CourseProgress) == 1){
			      $LearnerCourses[0] = $result->GetLearnerCourseDetailResult->CourseProgress;
        	      } else {
                  $LearnerCourses = (array) $result->GetLearnerCourseDetailResult->CourseProgress;
        	      }

			} else {
				$LearnerCourses = array();
			}
		} catch (SoapFault $e) {
			throw new Exception($e->getMessage() . PHP_EOL . PHP_EOL . $client->getLastRequest(), $e->getCode(), $e);
		}

		// Normalise
		$validFields = array('SmartAssessorId', 'SunesisId', 'LearnerSmartAssessorId', 'AssessorSmartAssessorId', 'QANCode', 'QualificationTitle', 'Progress_AssessedPC','Progress_MappedPC','TimeLinePerc');
		foreach ($LearnerCourses as &$LearnerCourse) {
			foreach ($validFields as $vf) {
				if(!isset($LearnerCourse->$vf) || $LearnerCourse->$vf === '') {
					$LearnerCourse->$vf = null;
				}
			}
		}

		return $LearnerCourses;
	}


    /**
	 * @return array
	 * @throws Exception
	 */
	public function getLearnerCourseLinkWithAssessor()
	{
		$client = $this->_getSoapClient();
		$LearnerCourses = array();

		try	{
			$result = $client->GetLearnerCourseLinkWithAssessor();
			if (isset($result->GetLearnerCourseLinkWithAssessorResult->CourseProgress)) {
			  if(count($result->GetLearnerCourseLinkWithAssessorResult->CourseProgress) == 1){
			    $LearnerCourses[0] = $result->GetLearnerCourseLinkWithAssessorResult->CourseProgress;
			  } else {
                $LearnerCourses = (array) $result->GetLearnerCourseLinkWithAssessorResult->CourseProgress;
			  }

			} else {
				$LearnerCourses = array();
			}
		} catch (SoapFault $e) {
			throw new Exception($e->getMessage() . PHP_EOL . PHP_EOL . $client->getLastRequest(), $e->getCode(), $e);
		}

		// Normalise
		$validFields = array('SmartAssessorId', 'SunesisId', 'LearnerSmartAssessorId', 'AssessorSmartAssessorId', 'QANCode', 'QualificationTitle', 'Progress_AssessedPC','Progress_MappedPC','TimeLinePerc');
		foreach ($LearnerCourses as &$LearnerCourse) {
			foreach ($validFields as $vf) {
				if(!isset($LearnerCourse->$vf) || $LearnerCourse->$vf === '') {
					$LearnerCourse->$vf = null;
				}
			}
		}

		return $LearnerCourses;
	}


    /**
	 * @return array
	 * @throws Exception
	 */
	public function getCourses()
	{
		$client = $this->_getSoapClient();
		$Courses = array();
		try	{
			$result = $client->GetAllCourseDetail();
			if (isset($result->GetAllCourseDetailResult->CourseDisplay)) {
			    if(count($result->GetAllCourseDetailResult->CourseDisplay) == 1){
			      $Courses[0] = $result->GetAllCourseDetailResult->CourseDisplay;
			    } else {
                 $Courses = (array) $result->GetAllCourseDetailResult->CourseDisplay;
			    }

			} else {
				$Courses = array();
			}
		} catch (SoapFault $e) {
			throw new Exception($e->getMessage() . PHP_EOL . PHP_EOL . $client->getLastRequest(), $e->getCode(), $e);
		}

		// Normalise
		$validFields = array('SmartAssessorId', 'QANCode', 'QualificationTitle');
		foreach ($Courses as &$Course) {
			foreach ($validFields as $vf) {
				if(!isset($Course->$vf) || $Course->$vf === '') {
					$Course->$vf = null;
				}
			}
		}

		return $Courses;
	}


    /**
	 * @return array
	 * @throws Exception
	 */
	public function getLearnerBatchDetails()
	{
		$client = $this->_getSoapClient();
		$BatchDetails = array();
		try	{
			$result = $client->GetAllBatchDetail();
			if (isset($result->GetAllBatchDetailResult->BatchDetailDisplay)) {
			    if(count($result->GetAllBatchDetailResult->BatchDetailDisplay) == 1){
			      $BatchDetails[0] = $result->GetAllBatchDetailResult->BatchDetailDisplay;
			    } else {
                 $BatchDetails = (array) $result->GetAllBatchDetailResult->BatchDetailDisplay;
			    }

			} else {
				$BatchDetails = array();
			}
		} catch (SoapFault $e) {
			throw new Exception($e->getMessage() . PHP_EOL . PHP_EOL . $client->getLastRequest(), $e->getCode(), $e);
		}

		// Normalise
		$validFields = array('IQASmartAssessorId', 'CourseSmartAssessorId', 'LearnerSmartAssessorId','BatchId','SampleType','QANCode','PlannedDate','ActualDate','SampleFeedback');
		foreach ($BatchDetails as &$BatchDetail) {
			foreach ($validFields as $vf) {
				if(!isset($BatchDetail->$vf) || $BatchDetail->$vf === '') {
					$BatchDetail->$vf = null;
				}
			}
		}

		return $BatchDetails;
	}


	/**
	 * @param mixed $learner
	 * @throws Exception
	 */
	public function createLearner($learner)
	{
		$link = DAO::getConnection();
		$input = null;

		// Normalise input to an array of keyed values
		if ($learner instanceof User) {
			$addr = new Address($learner, 'home_');
			$location = Location::loadFromDatabase($link, $learner->employer_location_id);
			if (!$location) {
				throw new Exception("Could not load Location object for learner #" . $learner->id);
			}
			$addrLines = $addr->to4Lines();
			$input = array(
				'SunesisId' => $learner->id,
				'EmployerSunesisId' => $learner->employer_location_id,
				'EmployerSmartAssessorId' => $location->smart_assessor_id,
				'FamilyName' => $learner->surname,
				'GivenNames' => $learner->firstnames,
				'ULN' => $learner->uln,
				'Sex' => $learner->gender,
				'DateOfBirth' => $learner->dob,
				'NINumber' => $learner->ni,
				'Domicile' => $learner->l24,
				'Email' => $learner->home_email,
				'TelNumber' => $learner->home_telephone,
				'Mobile' => $learner->home_mobile,
				'LlddDisability' => $learner->l15,
				'LlddLearningDifficulty' => $learner->l16,
				'HomeAddressLine1' => $addrLines[0],
				'HomeAddressLocality' => $addrLines[1],
				'HomeAddressTown' => $addrLines[2],
				'HomeAddressCounty' => $addrLines[3],
				'HomeAddressPostCode' => $learner->home_postcode
			);
		} else if (is_object($learner) || is_array($learner)) {
			$input = (array) $learner;
		} else {
			throw new Exception("Illegal datatype for argument \$learner");
		}

        //$input['LearnerLink'] = 'http://'.$_SERVER['HTTP_HOST'].'/do.php?_action=survey_form&uid=' . $input['SunesisId'];

		// Remove invalid fields
		$validFields = array('FamilyName', 'GivenNames', 'ULN', 'NINumber', 'Domicile', 'Email', 'DateOfBirth', 'Sex',
			'TelNumber', 'Mobile', 'LlddDisability', 'LlddLearningDifficulty',
			'EmployerSunesisId', 'EmployerSmartAssessorId',
			'SunesisId',
			'HomeAddressLine1', 'HomeAddressLocality', 'HomeAddressTown',
			'HomeAddressCounty', 'HomeAddressPostCode', 'LearnerLink'
		);
		$keys = array_keys($input);
		foreach ($keys as $key) {
			if (!in_array($key, $validFields)) {
				unset($input[$key]);
			}
		}

		// Remove empty fields
		$keys = array_keys($input);
		foreach ($keys as $key) {
			if (empty($input[$key])) {
				unset($input[$key]);
			}
		}

		// Normalise content
		if (isset($input['DateOfBirth'])) {
			$input['DateOfBirth'] = Date::to($input['DateOfBirth'], 'Y/m/d');
		}
		if (isset($input['Sex'])) {
			switch(strtolower($input['Sex'])) {
				case 'm':
				case 'male':
					$input['Sex'] = 'Male';
					break;
				case 'f':
				case 'female':
					$input['Sex'] = 'Female';
					break;
				default:
					//unset($input['Sex']);
                    $input['Sex'] = 'Male';
					break;
			}
		} else {
		   $input['Sex'] = 'Male';
		}


		// Validate input
		if (!array_key_exists('FamilyName', $input) || empty($input['FamilyName'])) {
			throw new Exception("Missing or empty value for field 'FamilyName'");
		}
		if (!array_key_exists('GivenNames', $input) || empty($input['GivenNames'])) {
			throw new Exception("Missing or empty value for field 'GivenNames'");
		}
		if (!array_key_exists('DateOfBirth', $input) || empty($input['DateOfBirth'])) {
			throw new Exception("Missing or empty value for field 'DateOfBirth'");
		}
		if (!array_key_exists('SunesisId', $input) || empty($input['SunesisId']) || !is_numeric($input['SunesisId'])) {
			throw new Exception("Missing, empty or invalid value for field 'SunesisId'");
		}
		if (!array_key_exists('Sex', $input) || empty($input['Sex']) || !in_array($input['Sex'], array('Male', 'Female'))) {
			throw new Exception("Missing, empty or invalid value for field 'Sex'");
		}
		if (!array_key_exists('ULN', $input) || empty($input['ULN'])) {
			throw new Exception("Missing or empty value for field 'ULN'");
		}
		if (!array_key_exists('EmployerSmartAssessorId', $input) || empty($input['EmployerSmartAssessorId']) || !is_numeric($input['EmployerSmartAssessorId'])) {
			throw new Exception("Missing, empty or invalid value for field 'EmployerSmartAssessorId'");
		}

		// Record Sunesis ID
		$user_id = $input['SunesisId'];

		// Marshall data (cast every value to a string for simplicity)
		foreach ($input as $key=>&$value) {
			$value = new SoapVar((string)$value, XSD_STRING);
		}

		$client = $this->_getSoapClient();
		try	{
			$data = array('Learner' => $input);
			$result = $client->inserLearnerDetail($data);
			$smartAssessorId = $result->InserLearnerDetailResult;
			if ($smartAssessorId) {
				DAO::execute($link, "UPDATE users SET smart_assessor_id = " . $link->quote($smartAssessorId) . " WHERE id = " . $user_id);
			}
		} catch (SoapFault $e) {
			throw new Exception($e->getMessage() . PHP_EOL . PHP_EOL . $client->getLastRequest(), $e->getCode(), $e);
		}

	}


	/**
	 * @param $learner
	 * @throws Exception
	 */
	public function updateLearner($learner)
	{
		$link = DAO::getConnection();
		$input = null;

		// Normalise input to an array of keyed values
		if ($learner instanceof User) {
			$location = Location::loadFromDatabase($link, $learner->employer_location_id);
			if (!$location) {
				throw new Exception("Could not load Location object (#{$learner->employer_location_id} for learner #" . $learner->id);
			}

			if (!is_null($learner->id)) {
				$input['SunesisId'] = $learner->id;
			}
			if (!is_null($learner->id)) {
				$input['SmartAssessorId'] = $learner->smart_assessor_id;
			}
			if (!is_null($learner->employer_location_id)) {
				$input['EmployerSunesisId'] = $learner->employer_location_id;
			}
			if (!is_null($location->smart_assessor_id)) {
				$input['EmployerSmartAssessorId'] = $location->smart_assessor_id;
			}
			if (!is_null($learner->surname)) {
				$input['FamilyName'] = $learner->surname;
			}
			if (!is_null($learner->firstnames)) {
				$input['GivenNames'] = $learner->firstnames;
			}
			if (!is_null($learner->uln)) {
				$input['ULN'] = $learner->uln;
			}
			if (!is_null($learner->ni)) {
				$input['NINumber'] = $learner->ni;
			}
			if (!is_null($learner->l24)) {
				$input['Domicile'] = $learner->l24;
			}
			if (!is_null($learner->dob)) {
				$input['DateOfBirth'] = Date::to($learner->dob, 'Y/m/d');
			}
			if (!is_null($learner->gender)) {
				switch($learner->gender) {
					case 'M':
						$input['Sex'] = 'Male';
						break;
					case 'F':
						$input['Sex'] = 'Female';
						break;
					default:
                        $input['Sex'] = 'Male';
						break;
				}
			}
			if (!is_null($learner->home_email)) {
				$input['Email'] = $learner->home_email;
			}
			if (!is_null($learner->home_telephone)) {
				$input['TelNumber'] = $learner->home_telephone;
			}
			if (!is_null($learner->home_mobile)) {
				$input['Mobile'] = $learner->home_mobile;
			}
			if (!is_null($learner->l15)) {
				$input['LlddDisability'] = $learner->l15;
			}
			if (!is_null($learner->l16)) {
				$input['LlddLearningDifficulty'] = $learner->l16;
			}
/*			if(!is_null($learner->paon_start_number)
				|| !is_null($learner->paon_start_suffix)
				|| !is_null($learner->paon_end_number)
				|| !is_null($learner->paon_end_suffix)
				|| !is_null($learner->paon_description)
				|| !is_null($learner->saon_start_number)
				|| !is_null($learner->saon_start_suffix)
				|| !is_null($learner->saon_end_number)
				|| !is_null($learner->saon_end_suffix)
				|| !is_null($learner->saon_description)
				|| !is_null($learner->street_description)
				|| !is_null($learner->locality)
				|| !is_null($learner->town)
				|| !is_null($learner->county)
				|| !is_null($learner->postcode)
			) {
				$addr = new Address($learner, 'home_');
				list($input['HomeAddressLine1'], $input['HomeAddressLocality'], $input['HomeAddressTown'], $input['HomeAddressCounty']) = $addr->to4Lines();
			}*/
			if (!is_null($learner->home_address_line_1)) {
				$input['HomeAddressLine1'] = $learner->home_address_line_1;
			}
			if (!is_null($learner->home_address_line_2)) {
				$input['HomeAddressLocality'] = $learner->home_address_line_2;
			}
			if (!is_null($learner->home_address_line_3)) {
				$input['HomeAddressTown'] = $learner->home_address_line_3;
			}
			if (!is_null($learner->home_address_line_4)) {
				$input['HomeAddressCounty'] = $learner->home_address_line_4;
			}
			if (!is_null($learner->home_postcode)) {
				$input['HomeAddressPostCode'] = $learner->home_postcode;
			}
		} else if (is_object($learner) || is_array($learner)) {
			$input = (array) $learner;
		} else {
			throw new Exception("Illegal datatype for argument \$employer");
		}

        $input['LearnerLink'] = 'http://'.$_SERVER['HTTP_HOST'].'/do.php?_action=survey_form&uid=' . $input['SunesisId'];

		// Remove invalid fields
		$validFields = array('FamilyName', 'GivenNames', 'ULN', 'NINumber', 'Domicile', 'Email', 'DateOfBirth', 'Sex',
			'TelNumber', 'Mobile', 'LlddDisability', 'LlddLearningDifficulty',
			'EmployerSunesisId', 'EmployerSmartAssessorId',
			'SunesisId', 'SmartAssessorId',
			'HomeAddressLine1', 'HomeAddressLocality', 'HomeAddressTown',
			'HomeAddressCounty', 'HomeAddressPostCode', 'LearnerLink'
		);
		$keys = array_keys($input);
		foreach ($keys as $key) {
			if (!in_array($key, $validFields)) {
				unset($input[$key]);
			}
		}

		// Normalise
		if (isset($input['DateOfBirth'])) {
			$input['DateOfBirth'] = Date::to($input['DateOfBirth'], 'Y/m/d');
		}
		if (isset($input['Sex'])) {
			switch(strtolower($input['Sex'])) {
				case 'm':
				case 'male':
					$input['Sex'] = 'Male';
					break;
				case 'f':
				case 'female':
					$input['Sex'] = 'Female';
					break;
				default:
					//unset($input['Sex']);
                    $input['Sex'] = 'Male';
					break;
			}
		}  else {
		   $input['Sex'] = 'Male';
		}

		// Validate input
		if (!array_key_exists('SmartAssessorId', $input) || empty($input['SmartAssessorId'])) {
			throw new Exception("Missing or empty field 'SmartAssessorId'");
		}

		// Marshall data (cast every value to a string for simplicity)
		foreach ($input as $key=>&$value) {
			$value = new SoapVar((string)$value, XSD_STRING);
		}

		$client = $this->_getSoapClient();
		try	{
			$client->updateLearnerDetail(array('Learner'=>$input));
        } catch (SoapFault $e) {
			throw new Exception($e->getMessage() . PHP_EOL . PHP_EOL . $client->getLastRequest(), $e->getCode(), $e);
		}
	}


	/**
	 * @throws Exception
	 */
	public function deleteLearner()
	{
		throw new Exception("Not implemented");
	}


    /**
	 * @param mixed $learner
	 * @throws Exception
	 */
	public function createLearnerinSunesis($learner)
	{
		$link = DAO::getConnection();
		$input = null;



		// Normalise input to an array of keyed values
		if (is_object($learner) || is_array($learner)) {
			$input = (array) $learner;
		} else {
			throw new Exception("Illegal datatype for argument \$learner");
		}


		// Remove invalid fields
		$validFields = array('firstnames', 'surname', 'l45', 'ni', 'l24', 'home_email', 'dob', 'gender',
			'home_telephone', 'home_mobile', 'l15', 'l16',
			'employer_id', 'employer_location_id',
			'smart_assessor_id',
			'home_address_line_1', 'home_address_line_2', 'home_address_line_3',
			'home_address_line_4', 'home_postcode',
		);
		$keys = array_keys($input);
		foreach ($keys as $key) {
			if (!in_array($key, $validFields)) {
				unset($input[$key]);
			}
		}

		// Remove empty fields
		$keys = array_keys($input);
		foreach ($keys as $key) {
			if (empty($input[$key])) {
				unset($input[$key]);
			}
		}

		// Normalise content
		if (isset($input['dob'])) {
			$input['dob'] = Date::to($input['dob'], 'Y-m-d');
		}
		if (isset($input['gender'])) {
			switch(strtolower($input['gender'])) {
				case 'm':
				case 'male':
					$input['gender'] = 'M';
					break;
				case 'f':
				case 'female':
					$input['gender'] = 'F';
					break;
                case 'u':
					$input['gender'] = 'U';
					break;
				default:
					unset($input['gender']);
					break;
			}
		}

        $type = User::TYPE_LEARNER;
        $input['web_access']= 1;
        $input['username']= strtolower($input['surname']).time();
        $input['password']= 'password';
        $input['pwd_sha1']= sha1($input['password']);
        $input['type']= $type;
        $input['uln']= $input['l45'];


    	// Validate input
		if (!array_key_exists('surname', $input) || empty($input['surname'])) {
			throw new Exception("Missing or empty value for field 'Surname'");
		}
		if (!array_key_exists('firstnames', $input) || empty($input['firstnames'])) {
			throw new Exception("Missing or empty value for field 'Firstname'");
		}
		/*if (!array_key_exists('dob', $input) || empty($input['dob'])) {
			throw new Exception("Missing or empty value for field 'DateOfBirth'");
		}*/
		if (!array_key_exists('smart_assessor_id', $input) || empty($input['smart_assessor_id'])) {
			throw new Exception("Missing, empty or invalid value for field 'smart_assessor_id'");
		}
		if (!array_key_exists('gender', $input) || empty($input['gender']) || !in_array($input['gender'], array('M', 'F','U'))) {
			throw new Exception("Missing, empty or invalid value for field 'Gender'");
		}
		if (!array_key_exists('l45', $input) || empty($input['l45'])) {
			throw new Exception("Missing or empty value for field 'ULN'");
		}
		if (!array_key_exists('employer_id', $input) || empty($input['employer_id']) || !is_numeric($input['employer_id'])) {
			throw new Exception("Missing, empty or invalid value for field 'employer_id'");
		}

        $vo = new User();
        $vo->populate($input);

        $newUser = empty($vo->id);
		$vo->save($link, $newUser);


		// Record Sunesis ID
		$user_id = $vo->id;

        // Link with Smart Assessor
        $input = array(
					'SunesisId' => $user_id,
					'SmartAssessorId' => $input['smart_assessor_id']
				);

		// Marshall data (cast every value to a string for simplicity)
		foreach ($input as $key=>&$value) {
			$value = new SoapVar((string)$value, XSD_STRING);
		}

		$client = $this->_getSoapClient();
		try	{
			$client->updateLearnerDetail(array('Learner'=>$input));
		} catch (SoapFault $e) {
			throw new Exception($e->getMessage() . PHP_EOL . PHP_EOL . $client->getLastRequest(), $e->getCode(), $e);
		}

	}


	/**
	 * @param mixed $employer
	 * @throws Exception
	 */
	public function createEmployer($employer)
	{
		$link = DAO::getConnection();
		$input = null;

		// Normalise input to an array of keyed values
		if ($employer instanceof Location) {
			$org = Organisation::loadFromDatabase($link, $employer->organisations_id);
			if (!$org) {
				throw new Exception("Could not find parent organisation for location #" . $employer->id);
			}
			$addr = new Address($employer);
			$addrLines = $addr->to4Lines();
			$input = array(
				'Name' => $org->legal_name,
				'EdsId' => $org->edrs,
				'SunesisId' => $employer->id,
				'AddressLine1' => $addrLines[0],
				'AddressLine2' => $addrLines[1],
				'AddressTown' => $addrLines[2],
				'AddressCounty' => $addrLines[3],
				'AddressPostCode' => $employer->postcode,
				'Telephone' => $employer->telephone,
				'KeyContactName' => $employer->contact_name,
				'KeyContactEmail' => $employer->contact_email
			);
		} else if (is_object($employer) || is_array($employer)) {
			$input = (array) $employer;
		} else {
			throw new Exception("Illegal datatype for argument \$employer");
		}

		// Remove invalid fields
		$validFields = array('Name', 'EdsId', 'SunesisId', 'AddressLine1', 'AddressLine2', 'AddressTown',
			'AddressCounty', 'AddressPostCode', 'Telephone', 'KeyContactName', 'KeyContactEmail');
		$keys = array_keys($input);
		foreach($keys as $key) {
			if (!in_array($key, $validFields)) {
				unset($input[$key]);
			}
		}

		// Remove empty fields
		$keys = array_keys($input);
		foreach ($keys as $key) {
			if (empty($input[$key])) {
				unset($input[$key]);
			}
		}

		// Validate input
		if (!array_key_exists('Name', $input) || empty($input['Name'])) {
			throw new Exception("Missing or empty value for field 'Name'");
		}
		if (!array_key_exists('EdsId', $input) || empty($input['EdsId'])) {
			throw new Exception("Missing or empty value for field 'EdsId'");
		}
		if (!array_key_exists('SunesisId', $input) || empty($input['SunesisId']) || !is_numeric($input['SunesisId'])) {
			throw new Exception("Missing, empty or illegal value for field 'SunesisId'");
		}
		if (!array_key_exists('AddressPostCode', $input) || empty($input['AddressPostCode'])) {
			throw new Exception("Missing or empty value for field 'AddressPostCode'");
		}

		// Record Sunesis ID
		$location_id = $input['SunesisId'];

		// Marshall data (cast every value to a string for simplicity)
		foreach ($input as $key=>&$value) {
			$value = new SoapVar((string)$value, XSD_STRING);
		}

		$client = $this->_getSoapClient();
		try	{
			$result = $client->inserEmployerDetail(array('Employer'=>$input));
			$smartAssessorId = $result->InserEmployerDetailResult;
			if (is_numeric($smartAssessorId)) {
				DAO::execute($link, "UPDATE locations SET smart_assessor_id = " . $smartAssessorId . " WHERE id = " . $location_id);
			}
		} catch (SoapFault $e) {
			throw new Exception($e->getMessage() . PHP_EOL . PHP_EOL . $client->getLastRequest(), $e->getCode(), $e);
		}
	}


	/**
	 * @param mixed $employer
	 * @throws Exception
	 */
	public function updateEmployer($employer)
	{
		$link = DAO::getConnection();
		$input = null;

		// Normalise input to an array of keyed values
		if ($employer instanceof Location) {
			$org = Organisation::loadFromDatabase($link, $employer->organisations_id);
			if (!$org) {
				throw new Exception("Could not find parent organisation for location #" . $employer->id);
			}
			if (!is_null($org->legal_name)) {
				$input['Name'] = $org->legal_name;
			}
			if (!is_null($employer->id)) {
				$input['SunesisId'] = $employer->id; // The Location object id
			}
			if (!is_null($employer->smart_assessor_id)) {
				$input['SmartAssessorId'] = $employer->smart_assessor_id;
			}
			if (!is_null($org->edrs)) {
				$input['EdsId'] = $org->edrs;
			}
/*			if(!is_null($employer->paon_start_number)
				|| !is_null($employer->paon_start_suffix)
				|| !is_null($employer->paon_end_number)
				|| !is_null($employer->paon_end_suffix)
				|| !is_null($employer->paon_description)
				|| !is_null($employer->saon_start_number)
				|| !is_null($employer->saon_start_suffix)
				|| !is_null($employer->saon_end_number)
				|| !is_null($employer->saon_end_suffix)
				|| !is_null($employer->saon_description)
				|| !is_null($employer->street_description)
				|| !is_null($employer->locality)
				|| !is_null($employer->town)
				|| !is_null($employer->county)
				|| !is_null($employer->postcode)
			) {
				$addr = new Address($employer);
				list($input['AddressLine1'], $input['AddressLine2'], $input['AddressTown'], $input['AddressCounty']) = $addr->to4Lines();
			}*/
			if (!is_null($employer->address_line_1)) {
				$input['AddressLine1'] = $employer->address_line_1;
			}
			if (!is_null($employer->address_line_2)) {
				$input['AddressLine2'] = $employer->address_line_2;
			}
			if (!is_null($employer->address_line_3)) {
				$input['AddressTown'] = $employer->address_line_3;
			}
			if (!is_null($employer->address_line_4)) {
				$input['AddressCounty'] = $employer->address_line_4;
			}
			if (!is_null($employer->postcode)) {
				$input['AddressPostCode'] = $employer->postcode;
			}
			if (!is_null($employer->telephone)) {
				$input['Telephone'] = $employer->telephone;
			}
			if (!is_null($employer->contact_name)) {
				$input['KeyContactName'] = $employer->contact_name;
			}
			if (!is_null($employer->contact_email)) {
				$input['KeyContactEmail'] = $employer->contact_email;
			}
		} else if (is_object($employer) || is_array($employer)) {
			$input = (array) $employer;
		} else {
			throw new Exception("Illegal datatype for argument \$employer");
		}

		// Remove invalid fields
		$validFields = array('Name', 'EdsId', 'SunesisId', 'SmartAssessorId', 'AddressLine1', 'AddressLine2', 'AddressTown',
			'AddressCounty', 'AddressPostCode', 'Telephone', 'KeyContactName', 'KeyContactEmail');
		$keys = array_keys($input);
		foreach($keys as $key) {
			if (!in_array($key, $validFields)) {
				unset($input[$key]);
			}
		}

		// Validate input
		if (!array_key_exists('SmartAssessorId', $input) || empty($input['SmartAssessorId']) || !is_numeric($input['SmartAssessorId'])) {
			throw new Exception("Missing, empty or illegal value for field 'SmartAssessorId'");
		}

		// Marshall data (cast every value to a string for simplicity)
		foreach ($input as $key=>&$value) {
			$value = new SoapVar((string)$value, XSD_STRING);
		}

		$client = $this->_getSoapClient();
		try	{
			$client->updateEmployerDetail(array('Employer'=>$input));
		} catch (SoapFault $e) {
			throw new Exception($e->getMessage() . PHP_EOL . PHP_EOL . $client->getLastRequest(), $e->getCode(), $e);
		}
	}

    /**
	 * @param mixed $employer
	 * @throws Exception
	 */
	public function createEmployerinSunesis($employer)
	{

        $link = DAO::getConnection();
		$input = null;

		// Normalise input to an array of keyed values
	    if (is_object($employer) || is_array($employer)) {

            // Validate input
    		if (!array_key_exists('Name', $employer) || empty($employer['Name'])) {
    			throw new Exception("Missing or empty value for field 'Name'");
    		}
    		if (!array_key_exists('EdsId', $employer) || empty($employer['EdsId'])) {
    			throw new Exception("Missing or empty value for field 'EdsId'");
    		}
    		if (!array_key_exists('SmartAssessorId', $employer) || empty($employer['SmartAssessorId']) || !is_numeric($employer['SmartAssessorId'])) {
    			throw new Exception("Missing, empty or illegal value for field 'SmartAssessorId'");
    		}
    		if (!array_key_exists('AddressPostCode', $employer) || empty($employer['AddressPostCode'])) {
    			throw new Exception("Missing or empty value for field 'AddressPostCode'");
    		}

            // Add organisation
            $org = new Employer();

            $org->organisation_type = Organisation::TYPE_EMPLOYER;
            $org->legal_name = $employer['Name'];
            $org->trading_name = $employer['Name'];
            $org->short_name = strtolower(substr($employer['Name'], 0, 11));
            $org->edrs = $employer['EdsId'];

            $org->save($link);

            $employerId = $org->id;


            // Add location
             $loc = new Location();

             $loc->organisations_id = $employerId;
             $loc->is_legal_address = 1;
             $loc->full_name = 'Main site';
             $loc->short_name = 'main site';
             $loc->address_line_1 = $employer['AddressLine1'];
             $loc->address_line_2 = $employer['AddressLine2'];
             $loc->address_line_3 = $employer['AddressTown'];
             $loc->address_line_4 = $employer['AddressCounty'];
             $loc->postcode = $employer['AddressPostCode'];
             $loc->telephone = $employer['Telephone'];
             $loc->contact_name = $employer['KeyContactName'];
             $loc->contact_email = $employer['KeyContactEmail'];
             $loc->smart_assessor_id = $employer['SmartAssessorId'];

             $loc->save($link);

             $locationId = $loc->id;
		} else {
			throw new Exception("Illegal datatype for argument \$employer");
		}

        //validate output
        if(empty($locationId)){
          throw new Exception("Missing, empty or illegal value for field 'LocationId'");
        }

        // Link with Smart Assessor
        $input = array(
					'SunesisId' => $locationId,
					'SmartAssessorId' => $employer['SmartAssessorId']
				);

		// Marshall data (cast every value to a string for simplicity)
		foreach ($input as $key=>&$value) {
			$value = new SoapVar((string)$value, XSD_STRING);
		}

		$client = $this->_getSoapClient();
		try	{
			$client->updateEmployerDetail(array('Employer'=>$input));
		} catch (SoapFault $e) {
			throw new Exception($e->getMessage() . PHP_EOL . PHP_EOL . $client->getLastRequest(), $e->getCode(), $e);
		}
	}


    /**
	 * @param mixed $assessor
	 * @throws Exception
	 */
	public function createAssessor($assessor)
	{
		$link = DAO::getConnection();
		$input = null;

		if (is_object($assessor) || is_array($assessor)) {
			$input = (array) $assessor;
		} else {
			throw new Exception("Illegal datatype for argument \$assessor");
		}


		// Remove invalid fields
		$validFields = array( 'FirstName', 'LastName', 'UserName', 'Password', 'Region', 'Email', 'Telephone', 'Mobile', 'SunesisId','UserType');
		$keys = array_keys($input);
		foreach ($keys as $key) {
			if (!in_array($key, $validFields)) {
				unset($input[$key]);
			}
		}

		// Remove empty fields
		$keys = array_keys($input);
		foreach ($keys as $key) {
			if (empty($input[$key])) {
				unset($input[$key]);
			}
		}
        $input['Password']= 'password';


		// Validate input
		if (!array_key_exists('FirstName', $input) || empty($input['FirstName'])) {
			throw new Exception("Missing or empty value for field 'FirstName'");
		}
		if (!array_key_exists('LastName', $input) || empty($input['LastName'])) {
			throw new Exception("Missing or empty value for field 'LastName'");
		}
		if (!array_key_exists('UserName', $input) || empty($input['UserName'])) {
			throw new Exception("Missing or empty value for field 'UserName'");
		}
		if (!array_key_exists('SunesisId', $input) || empty($input['SunesisId']) || !is_numeric($input['SunesisId'])) {
			throw new Exception("Missing, empty or invalid value for field 'SunesisId'");
		}


		// Record Sunesis ID
		$user_id = $input['SunesisId'];

		// Marshall data (cast every value to a string for simplicity)
		foreach ($input as $key=>&$value) {
			$value = new SoapVar((string)$value, XSD_STRING);
		}

		$client = $this->_getSoapClient();
		try	{
			$data = array('Assessor' => $input);
			$result = $client->inserAssessorDetail($data);
			$smartAssessorId = $result->InserAssessorDetailResult;
			if ($smartAssessorId) {
				DAO::execute($link, "UPDATE users SET smart_assessor_id = " . $link->quote($smartAssessorId) . " WHERE id = " . $user_id);
			}
		} catch (SoapFault $e) {
			throw new Exception($e->getMessage() . PHP_EOL . PHP_EOL . $client->getLastRequest(), $e->getCode(), $e);
		}

	}


	/**
	 * @param $assessor
	 * @throws Exception
	 */
	public function updateAssessor($assessor)
	{
		$link = DAO::getConnection();
		$input = null;

		// Normalise input to an array of keyed values
		if (is_object($assessor) || is_array($assessor)) {
			$input = (array) $assessor;
		} else {
			throw new Exception("Illegal datatype for argument \$assessor");
		}

		// Remove invalid fields
		$validFields = array('FirstName', 'LastName', 'UserName', 'Password', 'Region', 'Email', 'Telephone', 'Mobile', 'UserType',
			'SunesisId', 'SmartAssessorId'
		);
		$keys = array_keys($input);
		foreach ($keys as $key) {
			if (!in_array($key, $validFields)) {
				unset($input[$key]);
			}
		}

		// Validate input
		if (!array_key_exists('SmartAssessorId', $input) || empty($input['SmartAssessorId'])) {
			throw new Exception("Missing or empty field 'SmartAssessorId'");
		}

		// Marshall data (cast every value to a string for simplicity)
		foreach ($input as $key=>&$value) {
			$value = new SoapVar((string)$value, XSD_STRING);
		}

		$client = $this->_getSoapClient();
		try	{
			$client->updateAssessorDetail(array('Assessor'=>$input));
		} catch (SoapFault $e) {
			throw new Exception($e->getMessage() . PHP_EOL . PHP_EOL . $client->getLastRequest(), $e->getCode(), $e);
		}
	}


    /**
	 * @param mixed $assessor
	 * @throws Exception
	 */
	public function createAssessorinSunesis($assessor)
	{
		$link = DAO::getConnection();
		$input = null;


		// Normalise input to an array of keyed values
		if (is_object($assessor) || is_array($assessor)) {
			$input = (array) $assessor;

        //Get Training Provider of Assessor
        $query_getTP = <<<SQL
SELECT
    organisations.id as employer_id,
    locations.id as employer_location_id
FROM
    organisations
    INNER JOIN locations
        ON locations.organisations_id=organisations.id
WHERE
    organisation_type = 3
    AND ukprn in (select ukprn from organisations where organisation_type = 1)
ORDER BY organisations.id LIMIT 1
SQL;
    	$rs_TP = DAO::getResultset($link, $query_getTP, DAO::FETCH_ASSOC);

        $input['employer_id']=$rs_TP[0]['employer_id'];
        $input['employer_location_id']=$rs_TP[0]['employer_location_id'];

		} else {
			throw new Exception("Illegal datatype for argument \$assessor");
		}


		// Remove invalid fields
		$validFields = array('firstnames', 'surname', 'username', 'password', 'home_email',
			'home_telephone', 'home_mobile', 'home_address_line_3', 'employer_id', 'employer_location_id', 'type',
			'smart_assessor_id'
		);
		$keys = array_keys($input);
		foreach ($keys as $key) {
			if (!in_array($key, $validFields)) {
				unset($input[$key]);
			}
		}

		// Remove empty fields
		$keys = array_keys($input);
		foreach ($keys as $key) {
			if (empty($input[$key])) {
				unset($input[$key]);
			}
		}

		// Normalise content
        //$type = User::TYPE_ASSESSOR;
        $input['web_access']= 1;
        //$input['username']= strtolower($input['surname']).time();
        $input['password']= 'password';
        $input['pwd_sha1']= sha1($input['password']);
        //$input['type']= $type;


    	// Validate input
		if (!array_key_exists('surname', $input) || empty($input['surname'])) {
			throw new Exception("Missing or empty value for field 'Surname'");
		}
		if (!array_key_exists('firstnames', $input) || empty($input['firstnames'])) {
			throw new Exception("Missing or empty value for field 'Firstname'");
		}
		if (!array_key_exists('smart_assessor_id', $input) || empty($input['smart_assessor_id'])) {
			throw new Exception("Missing, empty or invalid value for field 'smart_assessor_id'");
		}
		if (!array_key_exists('employer_id', $input) || empty($input['employer_id']) || !is_numeric($input['employer_id'])) {
			throw new Exception("Missing, empty or invalid value for field 'employer_id'");
		}

        $vo = new User();
        $vo->populate($input);

        $newUser = empty($vo->id);
		$vo->save($link, $newUser);


		// Record Sunesis ID
		$user_id = $vo->id;

        // Link with Smart Assessor
        $input = array(
					'SunesisId' => $user_id,
					'SmartAssessorId' => $input['smart_assessor_id']
				);

		// Marshall data (cast every value to a string for simplicity)
		foreach ($input as $key=>&$value) {
			$value = new SoapVar((string)$value, XSD_STRING);
		}

		$client = $this->_getSoapClient();
		try	{
			$client->updateAssessorDetail(array('Assessor'=>$input));
		} catch (SoapFault $e) {
			throw new Exception($e->getMessage() . PHP_EOL . PHP_EOL . $client->getLastRequest(), $e->getCode(), $e);
		}

	}


    /**
	 * @param $review
	 * @throws Exception
	 */
	public function updateReview($review)
	{
		$link = DAO::getConnection();
		$input = null;

		// Normalise input to an array of keyed values
		if (is_object($review) || is_array($review)) {
			$input = (array) $review;
		} else {
			throw new Exception("Illegal datatype for argument \$review");
		}

		// Remove invalid fields
		$validFields = array('StartTime', 'Comments', 'TypeofReview', 'Status', 'SunesisId', 'SmartAssessorId');
		$keys = array_keys($input);
		foreach ($keys as $key) {
			if (!in_array($key, $validFields)) {
				unset($input[$key]);
			}
		}

		// Validate input
		if (!array_key_exists('SmartAssessorId', $input) || empty($input['SmartAssessorId'])) {
			throw new Exception("Missing or empty field 'SmartAssessorId'");
		}

		// Marshall data (cast every value to a string for simplicity)
		foreach ($input as $key=>&$value) {
			$value = new SoapVar((string)$value, XSD_STRING);
		}

		$client = $this->_getSoapClient();
		try	{
			$client->updateSessionDetail(array('Session'=>$input));
		} catch (SoapFault $e) {
			throw new Exception($e->getMessage() . PHP_EOL . PHP_EOL . $client->getLastRequest(), $e->getCode(), $e);
		}
	}

    /**
	 * @param mixed $review
	 * @throws Exception
	 */
	public function createReviewinSunesis($review)
	{
		$link = DAO::getConnection();
		$input = null;


		// Normalise input to an array of keyed values
		if (is_object($review) || is_array($review)) {
			$input = (array) $review;
		} else {
			throw new Exception("Illegal datatype for argument \$review");
		}

        $date_m = substr($input["meeting_date"],0,strpos($input["meeting_date"]," "));
        $date_m_arr = explode("/",$date_m);
        $input["meeting_date"] = Date("Y-m-d",strtotime($date_m_arr[2]."-".$date_m_arr[1]."-".$date_m_arr[0]));

		// Remove invalid fields
		$validFields = array('tr_id', 'meeting_date', 'assessor', 'comments','typeofreview', 'assessor_comments', 'qualification', 'smart_assessor_id');
		$keys = array_keys($input);
		foreach ($keys as $key) {
			if (!in_array($key, $validFields)) {
				unset($input[$key]);
			}
		}

		// Remove empty fields
		$keys = array_keys($input);
		foreach ($keys as $key) {
			if (empty($input[$key])) {
				unset($input[$key]);
			}
		}

    	// Validate input
		if (!array_key_exists('tr_id', $input) || empty($input['tr_id'])) {
			throw new Exception("Missing or empty value for field 'tr_id'");
		}
		if (!array_key_exists('assessor', $input) || empty($input['assessor'])) {
			throw new Exception("Missing or empty value for field 'assessor'");
		}
		if (!array_key_exists('smart_assessor_id', $input) || empty($input['smart_assessor_id'])) {
			throw new Exception("Missing, empty or invalid value for field 'smart_assessor_id'");
		}

        $input['id'] = '';
        DAO::saveObjectToTable($link, 'assessor_review', $input);

		// Record Sunesis ID
		$review_id = $input['id'];

        // Link with Smart Assessor
        $input = array(
					'SunesisId' => $review_id,
					'SmartAssessorId' => $input['smart_assessor_id']
				);


		// Marshall data (cast every value to a string for simplicity)
		foreach ($input as $key=>&$value) {
			$value = new SoapVar((string)$value, XSD_STRING);
		}

		$client = $this->_getSoapClient();
		try	{
			$client->updateSessionDetail(array('Session'=>$input));
		} catch (SoapFault $e) {
			throw new Exception($e->getMessage() . PHP_EOL . PHP_EOL . $client->getLastRequest(), $e->getCode(), $e);
		}

	}



    /**
	 * @param mixed $progress
	 * @throws Exception
	 */
	public function updateProgress($progress)
	{
		$link = DAO::getConnection();
		$input = null;

		// Normalise input to an array of keyed values
	    if (is_object($progress) || is_array($progress)) {
			$input = (array) $progress;
		} else {
			throw new Exception("Illegal datatype for argument \$progress");
		}

		// Remove invalid fields
		$validFields = array('SunesisId', 'SmartAssessorId');
		$keys = array_keys($input);
		foreach($keys as $key) {
			if (!in_array($key, $validFields)) {
				unset($input[$key]);
			}
		}

		// Validate input
		if (!array_key_exists('SmartAssessorId', $input) || empty($input['SmartAssessorId']) || !is_numeric($input['SmartAssessorId'])) {
			throw new Exception("Missing, empty or illegal value for field 'SmartAssessorId'");
		}

		// Marshall data (cast every value to a string for simplicity)
		foreach ($input as $key=>&$value) {
			$value = new SoapVar((string)$value, XSD_STRING);
		}

		$client = $this->_getSoapClient();
		try	{
			$client->updateProgressDetail(array('Progress'=>$input));
		} catch (SoapFault $e) {
			throw new Exception($e->getMessage() . PHP_EOL . PHP_EOL . $client->getLastRequest(), $e->getCode(), $e);
		}
	}



    /**
	 * @param mixed $progress
	 * @throws Exception
	 */
	public function updateProgressInSunesis($progress)
	{
		$link = DAO::getConnection();
		$input = null;

		// Normalise input to an array of keyed values
		if (is_object($progress) || is_array($progress)) {
			$input = (array) $progress;
		} else {
			throw new Exception("Illegal datatype for argument \$progress");
		}

		// Remove invalid fields
        $validFields = array('unitsUnderAssessment','smart_assessor_id','id','framework_id','tr_id','internaltitle','username','trading_name','sa_coursestatus');
		$keys = array_keys($input);
		foreach ($keys as $key) {
			if (!in_array($key, $validFields)) {
				unset($input[$key]);
			}
		}

		// Remove empty fields
		$keys = array_keys($input);
		foreach ($keys as $key) {
			if ($input[$key] == '') {
				unset($input[$key]);
			}
		}


    	// Validate input
		if (!array_key_exists('unitsUnderAssessment', $input) || $input['unitsUnderAssessment'] == '') {
			throw new Exception("Missing or empty value for field 'unitsUnderAssessment'");
		}
		if (!array_key_exists('smart_assessor_id', $input) || empty($input['smart_assessor_id'])) {
			throw new Exception("Missing, empty or invalid value for field 'smart_assessor_id'");
		}


        $vo = new StudentQualification();
        $vo->populate($input);

        // Update progress into Qualification of training record.
        DAO::saveObjectToTable($link, 'student_qualifications', $vo);

        TrainingRecord::updateProgressStatistics($link, $vo->tr_id);
        // Link with Smart Assessor
        /*$input = array(
					'SunesisId' => str_replace($input['id'],"/",""),       // id = QAN code
					'SmartAssessorId' => $input['smart_assessor_id']
				);

		// Marshall data (cast every value to a string for simplicity)
		foreach ($input as $key=>&$value) {
			$value = new SoapVar((string)$value, XSD_STRING);
		}

		$client = $this->_getSoapClient();
		try	{
			$client->updateProgressDetail(array('Progress'=>$input));
		} catch (SoapFault $e) {
			throw new Exception($e->getMessage() . PHP_EOL . PHP_EOL . $client->getLastRequest(), $e->getCode(), $e);
		}      */

	}


    /**
	 * @param mixed $learnerqualification
	 * @throws Exception
	 */
	public function createLearnerQualification($learnerqualification)
	{
		$link = DAO::getConnection();
		$input = null;

		// Normalise input to an array of keyed values
		if (is_object($learnerqualification) || is_array($learnerqualification)) {
			$input = (array) $learnerqualification;
		} else {
			throw new Exception("Illegal datatype for argument \$learnerqualification");
		}

		// Remove invalid fields
		$validFields = array('SmartAssessorId', 'LearnerSmartAssessorId', 'AssessorSmartAssessorId', 'QANCode', 'QualificationTitle', 'StatusofCourse', 'CourseStartDate', 'CourseEndDate');
		$keys = array_keys($input);
		foreach ($keys as $key) {
			if (!in_array($key, $validFields)) {
				unset($input[$key]);
			}
		}

		// Remove empty fields
		$keys = array_keys($input);
		foreach ($keys as $key) {
			if (empty($input[$key])) {
				unset($input[$key]);
			}
		}

		// Validate input
        if (!array_key_exists('SmartAssessorId', $input) || empty($input['SmartAssessorId'])) {
			throw new Exception("Missing or empty value for field 'SmartAssessorId'");
		}
        if (!array_key_exists('LearnerSmartAssessorId', $input) || empty($input['LearnerSmartAssessorId'])) {
			throw new Exception("Missing or empty value for field 'LearnerSmartAssessorId'");
		}
		if (!array_key_exists('QANCode', $input) || empty($input['QANCode'])) {
			throw new Exception("Missing or empty value for field 'QANCode'");
		}

		// Record Sunesis ID
		//$user_id = $input['SunesisId'];

		// Marshall data (cast every value to a string for simplicity)
		foreach ($input as $key=>&$value) {
			$value = new SoapVar((string)$value, XSD_STRING);
		}


		$client = $this->_getSoapClient();
		try	{
			$data = array('LearnerCourse' => $input);
			$result = $client->AssignQualficationToLearner($data);
			$smartAssessorId = $result->AssignQualficationToLearnerResult;
			/*if ($smartAssessorId) {
				DAO::execute($link, "UPDATE users SET smart_assessor_id = " . $link->quote($smartAssessorId) . " WHERE id = " . $user_id);
			}*/
		} catch (SoapFault $e) {
			throw new Exception($e->getMessage() . PHP_EOL . PHP_EOL . $client->getLastRequest(), $e->getCode(), $e);
		}

	}

    /**
	 * @param mixed $learnerqualification
	 * @throws Exception
	 */
	public function deleteLearnerQualification($learnerqualification)
	{
		$link = DAO::getConnection();
		$input = null;

		// Normalise input to an array of keyed values
	    if (is_object($learnerqualification) || is_array($learnerqualification)) {
			$input = (array) $learnerqualification;
		} else {
			throw new Exception("Illegal datatype for argument \$learnerqualification");
		}

		// Remove invalid fields
		$validFields = array('SmartAssessorId', 'LearnerSmartAssessorId');
		$keys = array_keys($input);
		foreach($keys as $key) {
			if (!in_array($key, $validFields)) {
				unset($input[$key]);
			}
		}

		// Validate input
		if (!array_key_exists('LearnerSmartAssessorId', $input) || empty($input['LearnerSmartAssessorId'])) {
			throw new Exception("Missing or empty value for field 'LearnerSmartAssessorId'");
		}
		if (!array_key_exists('SmartAssessorId', $input) || empty($input['SmartAssessorId'])) {
			throw new Exception("Missing or empty value for field 'SmartAssessorId'");
		}

		// Marshall data (cast every value to a string for simplicity)
		foreach ($input as $key=>&$value) {
			$value = new SoapVar((string)$value, XSD_STRING);
		}

		$client = $this->_getSoapClient();
		try	{
			$client->deleteLearnerQualification(array('DeleteLearnerCourses'=>$input));
		} catch (SoapFault $e) {
			throw new Exception($e->getMessage() . PHP_EOL . PHP_EOL . $client->getLastRequest(), $e->getCode(), $e);
		}
	}

     /**
	 * @param mixed $learnerassessor
	 * @throws Exception
	 */
	public function updateLearnerAssessor($learnerassessor)
	{
		$link = DAO::getConnection();
		$input = null;

		// Normalise input to an array of keyed values
		if (is_object($learnerassessor) || is_array($learnerassessor)) {
			$input = (array) $learnerassessor;
		} else {
			throw new Exception("Illegal datatype for argument \$learnerassessor");
		}

		// Remove invalid fields
		$validFields = array('SmartAssessorId','AssessorSmartAssessorId', 'LearnerSmartAssessorId');
		$keys = array_keys($input);
		foreach ($keys as $key) {
			if (!in_array($key, $validFields)) {
				unset($input[$key]);
			}
		}

		// Remove empty fields
		$keys = array_keys($input);
		foreach ($keys as $key) {
			if (empty($input[$key])) {
				unset($input[$key]);
			}
		}

		// Validate input
        if (!array_key_exists('LearnerSmartAssessorId', $input) || empty($input['LearnerSmartAssessorId'])) {
			throw new Exception("Missing or empty value for field 'LearnerSmartAssessorId'");
		}
		if (!array_key_exists('AssessorSmartAssessorId', $input) || empty($input['AssessorSmartAssessorId'])) {
			throw new Exception("Missing or empty value for field 'AssessorSmartAssessorId'");
		}
        if (!array_key_exists('SmartAssessorId', $input) || empty($input['SmartAssessorId'])) {
			throw new Exception("Missing or empty value for field 'SmartAssessorId'");
		}


		// Marshall data (cast every value to a string for simplicity)
		foreach ($input as $key=>&$value) {
			$value = new SoapVar((string)$value, XSD_STRING);
		}

		$client = $this->_getSoapClient();
		try	{
			$data = array('LearnerlinkwithAssessor' => $input);
			$client->AssignAssessorToLearner($data);
		} catch (SoapFault $e) {
			throw new Exception($e->getMessage() . PHP_EOL . PHP_EOL . $client->getLastRequest(), $e->getCode(), $e);
		}
	}

    /**
	 * @param mixed $learnerassessor
	 * @throws Exception
	 */
	public function updateLearnerAssessorInSunesis($learnerassessor)
	{
		$link = DAO::getConnection();
		$input = null;

		// Normalise input to an array of keyed values
		if (is_object($learnerassessor) || is_array($learnerassessor)) {
			$input = (array) $learnerassessor;
		} else {
			throw new Exception("Illegal datatype for argument \$learnerassessor");
		}

		// Remove invalid fields
		$validFields = array('id','assessor');
		$keys = array_keys($input);
		foreach ($keys as $key) {
			if (!in_array($key, $validFields)) {
				unset($input[$key]);
			}
		}

		// Remove empty fields
		$keys = array_keys($input);
		foreach ($keys as $key) {
			if (empty($input[$key])) {
				unset($input[$key]);
			}
		}

    	// Validate input
		if (!array_key_exists('id', $input) || empty($input['id'])) {
			throw new Exception("Missing or empty value for field 'id'");
		}
		if (!array_key_exists('assessor', $input) || empty($input['assessor'])) {
			throw new Exception("Missing, empty or invalid value for field 'assessor'");
		}

        $vo = new TrainingRecord();
        $vo->populate($input);


        // Update assessor into Training record
        DAO::saveObjectToTable($link, 'tr', $vo);


	}


    /**
	 * @param mixed $batches
	 * @throws Exception
	 */
	public function createBatchesInSunesis($batches)
	{
		$link = DAO::getConnection();
		$input = null;


		// Normalise input to an array of keyed values
		if (is_object($batches) || is_array($batches)) {
			$input = (array) $batches;
		} else {
			throw new Exception("Illegal datatype for argument \$review");
		}

        if($input["actual_date_1"]!='')
        {
            $date_m = substr($input["actual_date_1"],0,strpos($input["actual_date_1"]," "));
            $date_m_arr = explode("/",$date_m);
            $input["actual_date_1"] = Date("Y-m-d",strtotime($date_m_arr[2]."-".$date_m_arr[1]."-".$date_m_arr[0]));
        }

		// Remove invalid fields
		$validFields = array('tr_id', 'iv_name_1', 'actual_date_1', 'comment1', 'comment2', 'smart_assessor_id','auto_id');
		$keys = array_keys($input);
		foreach ($keys as $key) {
			if (!in_array($key, $validFields)) {
				unset($input[$key]);
			}
		}

		// Remove empty fields
		$keys = array_keys($input);
		foreach ($keys as $key) {
			if (empty($input[$key])) {
				unset($input[$key]);
			}
		}


    	// Validate input
		if (!array_key_exists('tr_id', $input) || empty($input['tr_id'])) {
			throw new Exception("Missing or empty value for field 'tr_id'");
		}
		if (!array_key_exists('iv_name_1', $input) || empty($input['iv_name_1'])) {
			throw new Exception("Missing or empty value for field 'iv_name_1'");
		}
		if (!array_key_exists('smart_assessor_id', $input) || empty($input['smart_assessor_id'])) {
			throw new Exception("Missing, empty or invalid value for field 'smart_assessor_id'");
		}
        if (!array_key_exists('auto_id', $input) || empty($input['auto_id'])) {
			throw new Exception("Missing, empty or invalid value for field 'auto_id'");
		}

        DAO::saveObjectToTable($link, 'iv', $input);

		// Record Sunesis ID
		$batch_id = $input['auto_id'];

	}


    /**
	 * @param $learner
	 * @throws Exception
	 */
	public function updateSurveyLinktoLearner($learnersurveylink)
	{
		$link = DAO::getConnection();
		$input = null;

		// Normalise input to an array of keyed values
		if (is_object($learnersurveylink) || is_array($learnersurveylink)) {
			$input = (array) $learnersurveylink;
		} else {
			throw new Exception("Illegal datatype for argument \$employer");
		}

		// Remove invalid fields
		$validFields = array('LearnerSmartAssessorId', 'LearnersLink');
		$keys = array_keys($input);
		foreach ($keys as $key) {
			if (!in_array($key, $validFields)) {
				unset($input[$key]);
			}
		}

		// Validate input
		if (!array_key_exists('LearnerSmartAssessorId', $input) || empty($input['LearnerSmartAssessorId'])) {
			throw new Exception("Missing or empty field 'LearnerSmartAssessorId'");
		}
        if (!array_key_exists('LearnersLink', $input) || empty($input['LearnersLink'])) {
			throw new Exception("Missing or empty field 'LearnersLink'");
		}

		// Marshall data (cast every value to a string for simplicity)
		foreach ($input as $key=>&$value) {
			$value = new SoapVar((string)$value, XSD_STRING);
		}

		$client = $this->_getSoapClient();
		try	{
			$client->AssignLearnerLinkToLearner(array('LearnerLink'=>$input));
		} catch (SoapFault $e) {
			throw new Exception($e->getMessage() . PHP_EOL . PHP_EOL . $client->getLastRequest(), $e->getCode(), $e);
		}
	}


	/**
	 * @return string
	 * @throws BadMethodCallException If the SOAP client has not been created
	 */
	public function getLastRequest()
	{
		if (!$this->_client) {
			throw new BadMethodCallException("No Zend_Soap_Client object has been created yet");
		}
		return $this->_client->getLastRequest();
	}

	/**
	 * @return string
	 * @throws BadMethodCallException If the SOAP client has not been created
	 */
	public function getLastResponse()
	{
		if (!$this->_client) {
			throw new BadMethodCallException("No Zend_Soap_Client object has been created yet");
		}
		return $this->_client->getLastResponse();
	}


	/**
	 * Used during and after testing. There should be no reason to call this during normal
	 * operation on production systems.
	 *
	 */
	public function reset()
	{
		$link = DAO::getConnection();

		$sql = "SELECT smart_assessor_id FROM users WHERE smart_assessor_id IS NOT NULL";
		$rs = DAO::query($link, $sql);
		foreach ($rs as $row) {
			$data = array(
				'SmartAssessorId' => $row['smart_assessor_id'],
				'SunesisId' => ''
			);
			$this->updateLearner($data);
		}

		$sql = "SELECT smart_assessor_id FROM locations WHERE smart_assessor_id IS NOT NULL";
		$rs = DAO::query($link, $sql);
		foreach ($rs as $row) {
			$data = array(
				'SmartAssessorId' => $row['smart_assessor_id'],
				'SunesisId' => ''
			);
			$this->updateEmployer($data);
		}

        $sql = "SELECT smart_assessor_id FROM student_qualifications WHERE smart_assessor_id IS NOT NULL";
		$rs = DAO::query($link, $sql);
		foreach ($rs as $row) {
			$data = array(
				'SmartAssessorId' => $row['smart_assessor_id'],
				'SunesisId' => ''
			);
			$this->updateProgress($data);
		}

		try	{
			DAO::transaction_start($link);
			DAO::execute($link, "UPDATE locations SET smart_assessor_id = NULL");
			DAO::execute($link, "UPDATE users SET smart_assessor_id = NULL");
            DAO::execute($link, "UPDATE student_qualifications SET smart_assessor_id = NULL");
			DAO::transaction_commit($link);
		} catch (Exception $e) {
			DAO::transaction_rollback($link);
			throw $e;
		}
	}


	/**
	 * @return bool
	 */
	public function isReadOnly()
	{
		return $this->_readOnly;
	}

	/**
	 * @return Zend_Soap_Client
	 */
	private function _getSoapClient()
	{
		if (!$this->_client) {
			$context = stream_context_create(array('http' => array('timeout' => '10')));
			$this->_client = new Zend_Soap_Client($this->_wsdl, array('soap_version' => SOAP_1_2,
				'compression' => SOAP_COMPRESSION_ACCEPT,
				'stream_context' => $context) );
		}

		$readOnly = $this->_readOnly ? new SoapVar('true', XSD_STRING) : new SoapVar('false', XSD_STRING);
		$apiKey = new SoapVar($this->_apiKey, XSD_STRING);
		$header = array('ApiKey' => $apiKey, 'ReadOnly' => $readOnly);
		$this->_client->addSoapInputHeader(new SoapHeader($this->_namespace, 'AuthHeader', $header));

		return $this->_client;
	}


	private $_wsdl = '';
	private $_apiKey = '';
	private $_namespace = '';
	private $_integrationEnabled = false;
	private $_readOnly = false;

	/** @var Zend_Soap_Client */
	private $_client = null;
}