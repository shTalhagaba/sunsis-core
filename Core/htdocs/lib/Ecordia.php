<?php

class Ecordia
{
	/**
	 * @param bool $readOnly
	 * @throws Exception
	 */
	public function __construct($readOnly = false)
	{

		$this->_readOnly = $readOnly ? true : false;
		$this->_wsdl = '';
		$this->_apiKey = SystemConfig::get("ecordia.soap.api_key");
		$this->_username = SystemConfig::get("ecordia.soap.username");
		$this->_namespace = SystemConfig::get("ecordia.soap.namespace");
		$this->_integrationEnabled = SystemConfig::get("ecordia.soap.enabled");

		if (!$this->_apiKey) {
			throw new Exception("Missing configuration parameter: ecordia.soap.api_key");
		}
		if (!$this->_namespace) {
			throw new Exception("Missing configuration parameter: ecordia.soap.namespace");
		}
		if (!$this->_integrationEnabled) {
			$this->_integrationEnabled = false; // Normalise
		}
	}


	/**
	 * @return array
	 * @throws Exception
	 */
	public function getLearners()
	{
		$headers = array("Content-type:application/json");
		$auth_details = '{"UserName":"' . $this->_username . '","SecretKey":"' . $this->_apiKey . '"}';

		// Initialise cURL
		$curl = curl_init($this->_namespace);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, true); // return a string (don't output to the browser directly)
		curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($curl, CURLOPT_POST, true);
		curl_setopt($curl, CURLOPT_HEADER, 0); // include the headers in the output
		curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
		curl_setopt($curl, CURLOPT_POSTFIELDS, $auth_details);
		curl_setopt($curl, CURLOPT_CONNECTTIMEOUT, 120); // seconds
		curl_setopt($curl, CURLOPT_TIMEOUT, 10); // seconds
		curl_setopt($curl, CURLOPT_USERAGENT, "Perspective/Sunesis");

		// Connect (retry up to five times if we experience DNS issues)
		$result = curl_exec($curl);
		if(curl_error($curl) == CURLE_COULDNT_RESOLVE_HOST)
		{
			$tries = 0;
			do{
				$tries++;
				sleep(1);
				$result = curl_exec($curl);
			} while(curl_error($curl) == CURLE_COULDNT_RESOLVE_HOST && $tries < 5);
			if(curl_error($curl)){
				curl_close($curl);
				throw new Exception(curl_error($curl), curl_errno($curl));
			}
		}

		curl_close($curl);

		$learners = json_decode($result, true);

		// Normalise
		/*$validFields = array('CandidateId', 'Title', 'FirstName', 'MiddleNames', 'LastName', 'Email', 'PhoneNumber', 'DateOfBirth',
			'ULN', 'LearnRefNumber', 'WorkplaceName', 'WorkplacePostcode',
			'CourseCode', 'TargetStartDate',
			'TargetEndDate', 'EcordiaUsername', 'PortfolioStatus');*/

		foreach ($learners as &$learner)
		{
			if (isset($learner['DateOfBirth']) && $learner['DateOfBirth'] != '')
			{
				$learner['DateOfBirth'] = substr($learner['DateOfBirth'], 0, 10);
			}
			if (isset($learner['TargetStartDate']) && $learner['TargetStartDate'] != '')
			{
				$learner['TargetStartDate'] = substr($learner['TargetStartDate'], 0, 10);
			}
			if (isset($learner['TargetEndDate']) && $learner['TargetEndDate'] != '')
			{
				$learner['TargetEndDate'] = substr($learner['TargetEndDate'], 0, 10);
			}
			if(isset($learner['WorkplacePostcode']) && strlen($learner['WorkplacePostcode']) > 10)
				$learner['WorkplacePostcode'] = substr($learner['WorkplacePostcode'], 0, 10);
		}

		return $learners;
	}

	/**
	 * @return array
	 * @throws Exception
	 */
	public function getProgresstrack($ecordia_id)
	{

		$headers = array("Content-type:application/json");
		$auth_details = '{"UserName":"' . $this->_username . '","SecretKey":"' . $this->_apiKey . '","CandidateId":"' . $ecordia_id . '"}';


		// Initialise cURL
		$curl = curl_init('https://app-2.ecordia.co.uk/app/api/CandidateExport/CandidatePortfolioProgress/');
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, true); // return a string (don't output to the browser directly)
		curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($curl, CURLOPT_POST, true);
		curl_setopt($curl, CURLOPT_HEADER, 0); // include the headers in the output
		curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
		curl_setopt($curl, CURLOPT_POSTFIELDS, $auth_details);
		curl_setopt($curl, CURLOPT_CONNECTTIMEOUT, 120); // seconds
		curl_setopt($curl, CURLOPT_TIMEOUT, 10); // seconds
		curl_setopt($curl, CURLOPT_USERAGENT, "Perspective/Sunesis");

		// Connect (retry up to five times if we experience DNS issues)
		$result = curl_exec($curl);
		if(curl_error($curl) == CURLE_COULDNT_RESOLVE_HOST)
		{
			$tries = 0;
			do{
				$tries++;
				sleep(1);
				$result = curl_exec($curl);
			} while(curl_error($curl) == CURLE_COULDNT_RESOLVE_HOST && $tries < 5);
			if(curl_error($curl)){
				curl_close($curl);
				throw new Exception(curl_error($curl), curl_errno($curl));
			}
		}

		curl_close($curl);

		$learner_progress = json_decode($result, true);

		return $learner_progress;
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
		if (is_object($learner) || is_array($learner))
		{
			$input = (array) $learner;
		}
		else
		{
			throw new Exception("Illegal datatype for argument \$learner");
		}


		// Remove invalid fields
		$validFields =
			array(
				'CandidateId'
				,'FirstName'
				,'LastName'
				,'Email'
				,'PhoneNumber'
				,'DateOfBirth'
				,'ULN'
				#,'LeranerRefNumber'
				,'WorkplaceName'
				,'WorkplacePostcode'
				#,'CourseCode'
				#,'TargetStartDate'
				#,'TargetEndDate'
				#,'EcordiaUsername'
				#,'PortfolioStatus'
		);
		$keys = array_keys($input);
		foreach ($keys as $key)
		{
			if (!in_array($key, $validFields))
			{
				unset($input[$key]);
			}
		}

		/*// Remove empty fields
		$keys = array_keys($input);
		foreach ($keys as $key)
		{
			if (empty($input[$key]))
			{
				unset($input[$key]);
			}
		}*/

		// Normalise content
		if (isset($input['DateOfBirth']))
		{
			$input['DateOfBirth'] = Date::to($input['DateOfBirth'], 'Y-m-d');
		}

		$final_input = array();

		$type = User::TYPE_LEARNER;
		$final_input['ecordia_id']= $input['CandidateId'];
		$final_input['firstnames']= $input['FirstName'];
		$final_input['surname']= $input['LastName'];
		$final_input['home_email']= $input['Email'];
		$final_input['home_telephone']= $input['PhoneNumber'];
		$final_input['dob']= $input['DateOfBirth'];
		$final_input['l45']= $input['ULN'];
		$final_input['uln']= $input['ULN'];
		$final_input['web_access']= 1;
		$final_input['username']= strtolower($input['LastName']).time();
		$final_input['password']= 'password';
		$final_input['pwd_sha1']= sha1('password');
		$final_input['type']= $type;

		$workplace_name = trim($input['WorkplaceName']);
		$workplace_postcode = trim($input['WorkplacePostcode']);


		// Validate input
		if (!array_key_exists('surname', $final_input) || empty($final_input['surname'])) {
			throw new Exception("Missing or empty value for field 'Surname'");
		}
		if (!array_key_exists('firstnames', $final_input) || empty($final_input['firstnames'])) {
			throw new Exception("Missing or empty value for field 'Firstname'");
		}
		if (!array_key_exists('ecordia_id', $final_input) || empty($final_input['ecordia_id'])) {
			throw new Exception("Missing, empty or invalid value for field 'ecordia_id'");
		}
		if (!array_key_exists('l45', $final_input) || empty($final_input['l45'])) {
			throw new Exception("Missing or empty value for field 'ULN'");
		}

		// before creating learner record check the employer record exists:
		// for this integration at the moment there are two fields to check Workplace Name and Workplace Postcode

		$employer_details = $this->processEmployer($workplace_name, $workplace_postcode);
		if(!isset($employer_details['EmployerId']) || !isset($employer_details['EmployerLocationId']))
			throw new Exception('Employer Details Missing');

		$final_input['employer_id'] = $employer_details['EmployerId'];
		$final_input['employer_location_id'] = $employer_details['EmployerLocationId'];
		$final_input['work_postcode'] = $workplace_postcode;
		$final_input['who_created'] = $_SESSION['user']->username;

		$vo = new User();
		$vo->populate($final_input);

		$newUser = empty($vo->id);
		$vo->save($link, $newUser);
	}

	/**
	 * @param mixed $learner
	 * @throws Exception
	 */
	public function linkTrainingRecord($learner)
	{
		$link = DAO::getConnection();
		$input = null;

		// Normalise input to an array of keyed values
		if (is_object($learner) || is_array($learner))
		{
			$input = (array) $learner;
		}
		else
		{
			throw new Exception("Illegal datatype for argument \$learner");
		}


		// Remove invalid fields
		$validFields =
			array(
				'CandidateId'
				#,'FirstName'
				#,'LastName'
				#,'Email'
				#,'PhoneNumber'
				#,'DateOfBirth'
				,'ULN'
				#,'LeranerRefNumber'
				#,'WorkplaceName'
				#,'WorkplacePostcode'
				#,'CourseCode'
				,'TargetStartDate'
				,'TargetEndDate'
				#,'EcordiaUsername'
				#,'PortfolioStatus'
		);
		$keys = array_keys($input);
		foreach ($keys as $key)
		{
			if (!in_array($key, $validFields))
			{
				unset($input[$key]);
			}
		}

		/*// Remove empty fields
		$keys = array_keys($input);
		foreach ($keys as $key)
		{
			if (empty($input[$key]))
			{
				unset($input[$key]);
			}
		}*/

		// Normalise content
		if (isset($input['TargetStartDate']))
		{
			$input['TargetStartDate'] = Date::to($input['TargetStartDate'], 'Y-m-d');
		}
		if (isset($input['TargetEndDate']))
		{
			$input['TargetEndDate'] = Date::to($input['TargetStartDTargetEndDatete'], 'Y-m-d');
		}


		// Validate input
		if (!array_key_exists('TargetStartDate', $input) || empty($input['TargetStartDate'])) {
			throw new Exception("Missing or empty value for field 'Target Start Date'");
		}
		if (!array_key_exists('TargetEndDate', $input) || empty($input['TargetEndDate'])) {
			throw new Exception("Missing or empty value for field 'Target End Date'");
		}
		if (!array_key_exists('ULN', $input) || empty($input['ULN'])) {
			throw new Exception("Missing or empty value for field 'ULN'");
		}
		if (!array_key_exists('CandidateId', $input) || empty($input['CandidateId'])) {
			throw new Exception("Missing or empty value for field 'Candidate Id'");
		}


		$vo = new TrainingRecord();
		$vo->populate($input);
	}

	private function processEmployer($workplaceName, $workplacePostcode)
	{
		$resultingEmployer = array();

		$link = DAO::getConnection();

		if($workplaceName == '' || $workplacePostcode == '')
			throw new Exception("Missing workplace arguments.");

		$sql = <<<SQL
SELECT COUNT(*) FROM organisations INNER JOIN locations ON organisations.id = locations.`organisations_id`
WHERE organisations.`organisation_type` = 2 AND locations.`is_legal_address` = 1
AND TRIM(organisations.`legal_name`) = TRIM('$workplaceName')
AND TRIM(locations.`postcode`) = TRIM('$workplacePostcode')
;
SQL;
		$employerExists = DAO::getSingleValue($link, $sql);
		if($employerExists == 0)
		{
			$org = new Employer();
			$org->legal_name = $workplaceName;
			$org->trading_name = $workplaceName;
			$org->short_name = substr($workplaceName, 0, 19);
			$org->organisation_type = Organisation::TYPE_EMPLOYER;
			$org->active = 1;
			$org->save($link);

			$location = new Location();
			$location->organisations_id = $org->id;
			$location->is_legal_address = 1;
			$location->full_name = 'Main Site';
			$location->short_name = 'Main';
			$location->postcode = $workplacePostcode;
			$location->save($link);

			$resultingEmployer['EmployerId'] = $org->id;
			$resultingEmployer['EmployerLocationId'] = $location->id;
		}
		else
		{
			$sql = <<<SQL
SELECT organisations.id AS EmployerId, locations.id AS EmployerLocationId  FROM organisations INNER JOIN locations ON organisations.id = locations.`organisations_id`
WHERE organisations.`organisation_type` = 2 AND locations.`is_legal_address` = 1
AND TRIM(organisations.`legal_name`) = TRIM('$workplaceName')
AND TRIM(locations.`postcode`) = TRIM('$workplacePostcode')
LIMIT 1
;
SQL;
			$result = DAO::getResultset($link, $sql, DAO::FETCH_ASSOC);
			$resultingEmployer = $result[0];
		}
		return $resultingEmployer;
	}

	private $_apiKey = '';
	private $_namespace = '';
	private $_integrationEnabled = false;
	private $_readOnly = false;
	private $_username = '';

	/** @var Zend_Soap_Client */
	private $_client = null;
}