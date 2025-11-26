<?php
class VacancyUploader extends Entity
{
	public static function checkVacancyMandatoryInformation(PDO $link, RecVacancy $vacancy)
	{
		$errors = '';
		if(is_null($vacancy))
			$errors .= '<error><ErrorDescription>Vacancy object is null</ErrorDescription></error>';

		if($vacancy->uploaded_to_nas == '1')
			$errors .= '<error><ErrorCode>Sunesis</ErrorCode><ErrorDescription>Vacancy is already uploaded</ErrorDescription></error>';

		$vacancyLocation = Location::loadFromDatabase($link, $vacancy->location_id);
		if(is_null($vacancyLocation))
			$errors .= '<error><ErrorCode>Sunesis</ErrorCode><ErrorDescription>Vacancy location is not found</ErrorDescription></error>';

		$vacancyEmployer = Organisation::loadFromDatabase($link, $vacancy->employer_id);
		if(is_null($vacancyEmployer))
			$errors .= '<error><ErrorCode>Sunesis</ErrorCode><ErrorDescription>Vacancy employer is not found</ErrorDescription></error>';

		$vacancyProvider = Organisation::loadFromDatabase($link, $vacancy->provider_id);
		if(is_null($vacancyProvider))
			$errors .= '<error><ErrorCode>Sunesis</ErrorCode><ErrorDescription>Vacancy provider is not found</ErrorDescription></error>';

		$vacancyFramework = Framework::loadFromDatabase($link, $vacancy->app_framework);
		if(is_null($vacancyFramework))
			$errors .= '<error><ErrorCode>Sunesis</ErrorCode><ErrorDescription>Vacancy framework is not found</ErrorDescription></error>';

		$vacancyMandatoryFields = array(
			'id',
			'vacancy_reference',
			'vacancy_title',
			'short_description',
			'full_description',
			'wage',
			'wage_type',
			'location_type',
			'working_week',
			'postcode',
			'no_of_positions',
			'suppl_q_1',
			'suppl_q_2',
			'closing_date',
			'interview_from_date',
			'possible_start_date',
			'vacancy_type'
		);
		foreach ($vacancy AS $key => $value)
		{
			if(in_array($key, $vacancyMandatoryFields) && (is_null($value) || trim($value) == ''))
				$errors .= '<error><ErrorCode>Sunesis</ErrorCode><ErrorDescription>Vacancy field: ' . $key . ' is not provided</ErrorDescription></error>';
		}

		$vacancyEmployerMandatoryFields = array(
			'edrs'
		);
		foreach ($vacancyEmployer AS $key => $value)
		{
			if(in_array($key, $vacancyEmployerMandatoryFields) && (is_null($value) || trim($value) == ''))
				$errors .= '<error><ErrorCode>Sunesis</ErrorCode><ErrorDescription>Vacancy employer field: ' . $key . ' is not provided</ErrorDescription></error>';
		}

		$vacancyEmployerLocationMandatoryFields = array(
			'address_line_1',
			'address_line_3',
			'address_line_4',
			'postcode'
		);
		foreach ($vacancyLocation AS $key => $value)
		{
			if(in_array($key, $vacancyEmployerLocationMandatoryFields) && (is_null($value) || trim($value) == ''))
				$errors .= '<error><ErrorCode>Sunesis</ErrorCode><ErrorDescription>Vacancy employer location field: ' . $key . ' is not provided</ErrorDescription></error>';
		}

		$vacancyProviderMandatoryFields = array(
			'ukprn'
		);
		foreach ($vacancyProvider AS $key => $value)
		{
			if(in_array($key, $vacancyProviderMandatoryFields) && (is_null($value) || trim($value) == ''))
				$errors .= '<error><ErrorCode>Sunesis</ErrorCode><ErrorDescription>Vacancy provider field: ' . $key . ' is not provided</ErrorDescription></error>';
		}

		$vacancyFrameworkMandatoryFields = array(
			'title',
			'framework_type'
		);
		foreach ($vacancyFramework AS $key => $value)
		{
			if(in_array($key, $vacancyFrameworkMandatoryFields) && (is_null($value) || trim($value) == ''))
				$errors .= '<error><ErrorCode>Sunesis</ErrorCode><ErrorDescription>Vacancy framework field: ' . $key . ' is not provided</ErrorDescription></error>';
		}

		unset($vacancyEmployer);
		unset($vacancyLocation);
		unset($vacancyProvider);
		unset($vacancyFramework);

		return $errors;
	}

	public static function uploadVacancyToNAS(PDO $link, RecVacancy $vacancy)
	{
		require_once './lib/NAS/VacancyManagement/Autoload.php';

		$guid_vacancy = VacancyUploader::getGUID();
		$guid_message = VacancyUploader::getGUID();
		//sleep(1);

		$vacancyLocation = Location::loadFromDatabase($link, $vacancy->location_id);
		$vacancyEmployer = Organisation::loadFromDatabase($link, $vacancy->employer_id);
		$vacancyProvider = Organisation::loadFromDatabase($link, $vacancy->provider_id);
		$vacancyFramework = Framework::loadFromDatabase($link, $vacancy->app_framework);

		$arrayOfVacancyUploadData = new StructArrayOfVacancyUploadData();

		$vacancyUploadData = new StructVacancyUploadData();
		$vacancyUploadData->setVacancyId($guid_vacancy);
		$vacancyUploadData->setTitle(new SoapVar((string)$vacancy->vacancy_title, XSD_STRING));
		$vacancyUploadData->setShortDescription(new SoapVar((string)$vacancy->short_description, XSD_STRING));
		$vacancyUploadData->setLongDescription(new SoapVar((string)$vacancy->full_description, XSD_STRING));

		$employerData = new StructEmployerData();
		$employerData->setEdsUrn(new SoapVar((int)$vacancyEmployer->company_number, XSD_INT));
		$employerDescription = <<<HTML
Superdrug is part of the AS Watson Group and is the UK's second-largest beauty and health retailer currently operating over nearly 900 stores in England, Scotland, Wales, Northern Ireland and the Republic of Ireland. We currently have about 200 in-store pharmacies.<br />
<br />
Our purpose is to be the best in everyday accessible beauty and we are committed to bringing innovation and the latest styles and trends to the high street at fantastic prices.
HTML;
		//$employerData->setDescription(new SoapVar((string)$employerDescription, XSD_STRING));
		//$employerData->setAnonymousName(new SoapVar((string)'Superdrug Stores Plc', XSD_STRING));
		$employerData->setDescription(new SoapVar((string)'', XSD_STRING));
		$employerData->setAnonymousName(new SoapVar((string)'', XSD_STRING));
		$employerData->setContactName(new SoapVar((string)'Lisa Taylor', XSD_STRING));
		$employerData->setWebsite(new SoapVar((string)'https://www.superdrug.jobs/see-all-vacancies.html', XSD_STRING));

		$vacancyUploadData->setEmployer($employerData);

		$vacancyData = new StructVacancyData();
		$vacancyData->setWage(new SoapVar(floatval($vacancy->wage), XSD_DECIMAL));
		$vacancyData->setWageType($vacancy->wage_type);
		$vacancyData->setWorkingWeek(new SoapVar((string)$vacancy->working_week, XSD_STRING));
		$vacancyData->setSkillsRequired(new SoapVar((string)$vacancy->skills_required, XSD_STRING));
		$vacancyData->setQualificationRequired(new SoapVar((string)$vacancy->qualifications_required, XSD_STRING));
		$vacancyData->setPersonalQualities(new SoapVar((string)$vacancy->personal_qualities, XSD_STRING));
		$vacancyData->setFutureProspects(new SoapVar((string)$vacancy->future_prospects, XSD_STRING));
		$vacancyData->setOtherImportantInformation(new SoapVar((string)$vacancy->other_info, XSD_STRING));
		$vacancyData->setLocationType($vacancy->location_type);

		$addressData = new StructAddressData();
		$addressData->setAddressLine1(new SoapVar((string)$vacancyLocation->address_line_1, XSD_STRING));
		//$addressData->setAddressLine2(new SoapVar((string)$vacancyLocation->address_line_2, XSD_STRING));
		$addressData->setCounty(new SoapVar((string)$vacancyLocation->address_line_4, XSD_STRING));
		$addressData->setGridEastM(new SoapVar($vacancyLocation->easting, XSD_INT));
		$addressData->setGridNorthM(new SoapVar($vacancyLocation->northing, XSD_INT));
		$addressData->setLatitude(new SoapVar(floatval($vacancyLocation->latitude), XSD_FLOAT));
		$addressData->setLongitude(new SoapVar(floatval($vacancyLocation->longitude), XSD_FLOAT));
		$addressData->setPostCode(new SoapVar((string)$vacancyLocation->postcode, XSD_STRING));
		$addressData->setTown(new SoapVar((string)$vacancyLocation->address_line_3, XSD_STRING));

		$siteVacancyData = new StructSiteVacancyData();
		$siteVacancyData->setAddressDetails($addressData);
		$siteVacancyData->setNumberOfVacancies(new SoapVar((int)$vacancy->no_of_positions, XSD_INT));
		$siteVacancyData->setEmployerWebsite(new SoapVar((string)'https://superdrug.sunesis.uk.net/do.php?_action=vacancy_detail&vacancy_id='.$vacancy->id, XSD_STRING));

		$arrayOfSiteVacancyData = new StructArrayOfSiteVacancyData();
		$arrayOfSiteVacancyData->setSiteVacancyData($siteVacancyData);

		$vacancyData->setLocationDetails($arrayOfSiteVacancyData);

		$vacancyData->setSupplementaryQuestion1(new SoapVar((string)DAO::getSingleValue($link, "SELECT description FROM lookup_vacancies_supp_questions WHERE id = '{$vacancy->suppl_q_1}'"), XSD_STRING));
		$vacancyData->setSupplementaryQuestion2(new SoapVar((string)DAO::getSingleValue($link, "SELECT description FROM lookup_vacancies_supp_questions WHERE id = '{$vacancy->suppl_q_2}'"), XSD_STRING));

		$vacancyUploadData->setVacancy($vacancyData);

		$applicationData = new StructApplicationData();
		$applicationData->setClosingDate(new SoapVar($vacancy->closing_date, XSD_DATETIME));
		$applicationData->setInterviewStartDate(new SoapVar($vacancy->interview_from_date, XSD_DATETIME));
		$applicationData->setPossibleStartDate(new SoapVar($vacancy->possible_start_date, XSD_DATETIME));
		$applicationData->setType(EnumApplicationType::VALUE_OFFLINE);
		$instructions = "Apply online at https://superdrug.sunesis.uk.net/do.php?_action=vacancy_detail&vacancy_id=".$vacancy->id." Click 'Apply' and follow the application instructions providing as much information as possible.";
		$applicationData->setInstructions(new SoapVar((string)$instructions, XSD_STRING));

		$vacancyUploadData->setApplication($applicationData);

		$apprenticeshipData = new StructApprenticeshipData();
		$apprenticeshipData->setFramework(new SoapVar((string)$vacancyFramework->framework_code, XSD_STRING));
		$level = EnumVacancyApprenticeshipType::VALUE_UNSPECIFIED;
		if($vacancyFramework->framework_type == '2')
			$level = EnumVacancyApprenticeshipType::VALUE_ADVANCEDLEVELAPPRENTICESHIP;
		elseif($vacancyFramework->framework_type == '3')
			$level = EnumVacancyApprenticeshipType::VALUE_INTERMEDIATELEVELAPPRENTICESHIP;
		elseif($vacancyFramework->framework_type >= 20 && $vacancyFramework->framework_type <= 23)
			$level = EnumVacancyApprenticeshipType::VALUE_HIGHERAPPRENTICESHIP;
		elseif($vacancyFramework->framework_type == '24')
			$level = EnumVacancyApprenticeshipType::VALUE_TRAINEESHIP;
		elseif($vacancyFramework->framework_type == '99')
			$level = EnumVacancyApprenticeshipType::VALUE_UNSPECIFIED;
		$apprenticeshipData->setType($level);
		$apprenticeshipData->setTrainingToBeProvided(new SoapVar("<p>Your HABC Level 2 Customer Service Practitioner Diploma will be delivered on site within your store where you will be supported throughout.</p>
<p>You will work through a programme of learning with the support of an Assessor and your Manager over a 12 month period, all done on site, so no college days!</p>
<p>During the 13th month of your contract you will take part in an end assessment with an independent Assessor, which includes an observation of your ability, a discussion around your experiences and a multiple choice written test.</p>
<p>Our Apprenticeship is a nationally recognised qualification and demonstrates that you have the skills and knowledge needed to do the job effectively.</p>", XSD_STRING));

		$vacancyUploadData->setApprenticeship($apprenticeshipData);

		$vacancyUploadData->setContractedProviderUkprn(new SoapVar((int)$vacancyProvider->ukprn, XSD_INT));
		$vacancyUploadData->setVacancyOwnerEdsUrn(new SoapVar((int)'153266619', XSD_INT));


		$vacancyUploadData->setIsDisplayRecruitmentAgency(new SoapVar((string)true, XSD_BOOLEAN));
		$vacancyUploadData->setIsSmallEmployerWageIncentive(new SoapVar((string)false, XSD_BOOLEAN));

		$arrayOfVacancyUploadData->setVacancyUploadData($vacancyUploadData);

		$vacancyUploadRequest = new StructVacancyUploadRequest();
		$vacancyUploadRequest->setVacancies($arrayOfVacancyUploadData);

		//throw new Exception(json_encode($vacancyUploadRequest));

		$service = new ServiceUpload();
		$service->setSoapHeaderExternalSystemId(SystemConfig::getEntityValue($link, 'nas.soap.system_id'));
		$service->setSoapHeaderMessageId($guid_message);
		$service->setSoapHeaderPublicKey(SystemConfig::getEntityValue($link, 'nas.soap.public_key'));

		$errors = '';

		if($service->UploadVacancies($vacancyUploadRequest))
		{
			$response = $service->getResult(); /* @var $response StructVacancyUploadResponse */
			$response_vacancies = $response->getVacancies()->getVacancyUploadResultData(); /* @var $response_vacancies StructArrayOfVacancyUploadResultData */

			foreach($response_vacancies AS $response_vacancy) /* @var $response_vacancy StructVacancyUploadResultData */
			{
				if($response_vacancy->getStatus() == EnumVacancyUploadResult::VALUE_FAILURE)
				{
					$errors .= '<result>';
					$errors .= '<VacancyId>' . $response_vacancy->getVacancyId() . '</VacancyId>';
					$errors .= '<Status>' . $response_vacancy->getStatus() . '</Status>';
					$errorCodes = $response_vacancy->getErrorCodes()->getElementErrorData(); /* @var $errorCodes StructArrayOfElementErrorData */
					foreach($errorCodes AS $errorCode) /* @var $errorCode StructElementErrorData */
					{
						$errors .= '<error>';
						$errors .= '<ErrorCode>' . $errorCode->getErrorCode() . '</ErrorCode>';
						$errors .= '<ErrorDescription>' . DAO::getSingleValue($link, "SELECT ErrorDescription FROM nas_error_codes WHERE ErrorCode = '" . $errorCode->getErrorCode() . "'") . '</ErrorDescription>';
						$errors .= '</error>';
					}
					$errors .= '</result>';
				}
				elseif($response_vacancy->getStatus() == EnumVacancyUploadResult::VALUE_SUCCESS)//vacancy_guid
				{
					$vacancy->uploaded_to_nas = '1';
					$vacancy->vacancy_guid = $guid_vacancy;
					$vacancy->save($link);
				}
			}
			if($errors != '')
			{
				VacancyUploader::logNASUpload($link, $errors, $guid_vacancy, $guid_message);
				return '<errors>' . $errors . '</errors>';
			}
			else
			{
				VacancyUploader::logNASUpload($link, $service->getResult(), $guid_vacancy, $guid_message);
				return 'success';
			}
		}
		else
		{
			VacancyUploader::logNASUpload($link, $service->getResult(), $guid_vacancy, $guid_message);
			return json_encode($service->getLastError());
		}
	}

	private static function logNASUpload(PDO $link, $detail, $guid_vacancy, $guid_message)
	{
		if($detail == '')
			return;
		$log = new stdClass();
		$log->user_id = $_SESSION['user']->id;
		$log->datetime = date('Y-m-d H:i:s');
		$log->details = $detail;
		$log->guid_vacancy = $guid_vacancy;
		$log->guid_message = $guid_message;
		DAO::saveObjectToTable($link, 'nas_upload_log', $log);
		unset($log);
	}

	public static function getGUID()
	{
		if (function_exists('com_create_guid'))
		{
			return com_create_guid();
		}
		else
		{
			mt_srand((double)microtime()*10000);//optional for php 4.2.0 and up.
			$charid = strtoupper(md5(uniqid(rand(), true)));
			$hyphen = chr(45);// "-"
			$uuid = chr(123)// "{"
				.substr($charid, 0, 8).$hyphen
				.substr($charid, 8, 4).$hyphen
				.substr($charid,12, 4).$hyphen
				.substr($charid,16, 4).$hyphen
				.substr($charid,20,12)
				.chr(125);// "}"
			$uuid =
				substr($charid, 0, 8).$hyphen
					.substr($charid, 8, 4).$hyphen
					.substr($charid,12, 4).$hyphen
					.substr($charid,16, 4).$hyphen
					.substr($charid,20,12)
			;
			return $uuid;
		}
	}
}
