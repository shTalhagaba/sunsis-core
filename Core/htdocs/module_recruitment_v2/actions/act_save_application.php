<?php
class save_application implements IUnauthenticatedAction
{
	public function execute( PDO $link )
	{
		// grab recaptcha library
		require_once "lib/ReCaptcha.php";
		// your secret key
		$secret = "6Lf1CA8UAAAAANDYPFqnIxbhIYJD35k26c08Vdq_";
		// empty response
		$response = null;

		/*// check secret key
		$reCaptcha = new ReCaptcha($secret);
		// if submitted check response
		if (isset($_POST["g-recaptcha-response"]))
		{
			$response = $reCaptcha->verifyResponse(
				$_SERVER["REMOTE_ADDR"],
				$_POST["g-recaptcha-response"]
			);
			if (is_null($response) || !$response->success)
			{
				$msg = 3;
				http_redirect('do.php?_action=application&msg='.$msg);
			}
		}*/

		if(!isset($_POST['hascomefrom']) || $_POST['hascomefrom'] == '' || $_POST['hascomefrom'] != md5('hascomefromsunesiserec')) // just for security
			throw new UnauthorizedException();

		$msg = "";

		//pre($_REQUEST);

		$application_has_not_passed_killer_questions = $this->checkForKillerQuestions($link, $_REQUEST);
		if($application_has_not_passed_killer_questions)
		{
			//$this->sendRejectEmail($link, $_REQUEST);
			$msg = 5;
			http_redirect('do.php?_action=application&msg='.$msg);
		}

		$candidate_already_exits = false;
		$candidate_has_already_applied_for_this_vacancy = false;

		if(isset($_REQUEST['candidate_id']) && $_REQUEST['candidate_id'] != '')
		{
			$candidate_already_exits = true;
			if(isset($_POST['vacancy_id']) && !is_null(RecCandidateApplication::loadFromDatabaseByVacancyAndCandidate($link, $_REQUEST['vacancy_id'], $_REQUEST['candidate_id'])))
				$candidate_has_already_applied_for_this_vacancy = true;
		}
		else
		{
			//http_redirect('do.php?_action=search_vacancies');
			$candidate_already_exits = $this->candidateAlreadyExists($link, $_POST['firstnames'], $_POST['surname'], $_POST['dob'], $_POST['postcode']);
			if(isset($_POST['vacancy_id']))
				$candidate_has_already_applied_for_this_vacancy = $this->candidateHasAppliedForThisVacancy($link, $_POST['firstnames'], $_POST['surname'], $_POST['dob'], $_POST['postcode'], $_POST['vacancy_id']);
		}


		if(!isset($_POST['hascomefrom']) || $_POST['hascomefrom'] == '' || $_POST['hascomefrom'] != md5('hascomefromsunesiserec')) // just for security
			$msg = 3;
		elseif($candidate_already_exits AND $candidate_has_already_applied_for_this_vacancy)
			$msg = 4;
		elseif($candidate_already_exits AND !isset($_POST['vacancy_id']))
			$msg = 2;
		else
		{
			$vo = new RecCandidate();
			$vo->populate($_POST);
			$vo->id = $_POST['candidate_id'];
			//if(SOURCE_LOCAL || DB_NAME == "am_sd_demo" || DB_NAME == "am_demo")
				$vo->id = $_POST['candidate_id'] != '' ? $_POST['candidate_id'] : $this->candidateAlreadyExistsSoGiveMeCandidateID($link, $_POST['firstnames'], $_POST['surname'], $_POST['dob'], $_POST['postcode']);
			
			try
			{
				DAO::transaction_start($link);

				$cv_ext = '';
				if(isset($_FILES['uploadedfile']))
				{
					$fileSelected = empty($_FILES['uploadedfile']['name'])?'No':'Yes';
					$ext = strtolower(pathinfo($_FILES['uploadedfile']['name'], PATHINFO_EXTENSION));
					if($ext == 'doc' || $ext == 'docx' || $ext == 'pdf' || $ext == 'txt' || $ext == 'zip')
						$cv_ext = 'OK';
				}
				else
					$fileSelected = "No";

				if(!$candidate_already_exits)
				{
					$vo->save($link);
					$this->save_candidate_qualifications($link, $vo->id, $_REQUEST);
					$this->save_candidate_employments($link, $vo->id, $_REQUEST);
				}

				if($cv_ext == 'OK')
					$this->upload_candidate_files($vo->id);

				$this->saveShiftPatterns($link, $vo, $_REQUEST);

				$vacancy_for_which_candidate_applied_for = isset($_POST['vacancy_id']) ? $_POST['vacancy_id'] : '';

				if($vacancy_for_which_candidate_applied_for != '')
				{
					if(!$candidate_has_already_applied_for_this_vacancy)
					{
						$new_vacancy_application = new RecCandidateApplication();
						$new_vacancy_application->candidate_id = $vo->id;
						$new_vacancy_application->vacancy_id = $vacancy_for_which_candidate_applied_for;
						$new_vacancy_application->current_status = RecCandidateApplication::CREATED;
						$new_vacancy_application->supplementary_question_1_answer = isset($_REQUEST['supplementary_question_1_answer'])?$_REQUEST['supplementary_question_1_answer']:'';
						$new_vacancy_application->supplementary_question_2_answer = isset($_REQUEST['supplementary_question_2_answer'])?$_REQUEST['supplementary_question_2_answer']:'';
						$new_vacancy_application->save($link);
						$this->saveApplicationQuestions($link, $new_vacancy_application->id, $_REQUEST);
						$msg = "1";
						$this->sendWelcomeEmailToCandidate($link, $new_vacancy_application->id);
					}
				}
				if($msg == '')
					$msg = 1;


				DAO::transaction_commit($link);
			}
			catch(Exception $e)
			{
				DAO::transaction_rollback($link, $e);
				throw new WrappedException($e);
			}
		}

		http_redirect('do.php?_action=application&msg='.$msg);
	}

	private function saveShiftPatterns(PDO $link, RecCandidate $candidate, $input_data)
	{
		DAO::execute($link, "DELETE FROM candidate_shift_patterns WHERE candidate_id = '{$candidate->id}'");

		$shift_pattern = array(
			'mon_start_time',
			'tue_start_time',
			'wed_start_time',
			'thu_start_time',
			'fri_start_time',
			'sat_start_time',
			'sun_start_time',
			'mon_end_time',
			'tue_end_time',
			'wed_end_time',
			'thu_end_time',
			'fri_end_time',
			'sat_end_time',
			'sun_end_time'
		);

		$stdShiftPattern = new stdClass();
		foreach($shift_pattern AS $key)
		{
			if(isset($input_data[$key]))
				$stdShiftPattern->$key = $input_data[$key];
		}
		if(isset($stdShiftPattern))
		{
			$stdShiftPattern->candidate_id = $candidate->id;
			if($stdShiftPattern->candidate_id != "")
				DAO::saveObjectToTable($link, 'candidate_shift_patterns', $stdShiftPattern);
		}
	}

	private function checkForKillerQuestions(PDO $link, $input_data)
	{
		foreach($input_data AS $key => $value)
		{
			if(substr($key, 0, 4) != 'q_a_')
				continue;
			$question_id = explode('_', $key);
			$question_id = $question_id[2];
			if(RecVacancy::isKillerQuestion($link, $question_id) && RecVacancy::isKillerAnswerGivenForKillerQuestion($link, $question_id, $value))
			{
				return true;
			}
		}
		return false;
	}

	private function saveApplicationQuestions(PDO $link, $application_id, $input_data)
	{
		if($application_id == '')
			return;

		if(!is_array($input_data))
			return;

		$data = array();
		foreach($input_data AS $key => $value)
		{
			if(substr($key, 0, 4) != 'q_a_')
				continue;
			$question_id = explode('_', $key);
			if(isset($question_id[2]) && $question_id[2] != '')
			{
				$obj = new stdClass();
				$obj->application_id = $application_id;
				$obj->question_id = $question_id[2];
				$obj->answer = Text::utf8_to_latin1($value);
				$data[] = $obj;
				unset($obj);
			}
		}
		if(count($data) > 0)
			DAO::multipleRowInsert($link, 'candidate_application_screening', $data);
	}

	private function upload_candidate_files($candidate_id, $mode = false, $fileSelected = false)
	{
		$target_directory = 'recruitment';

		$valid_extensions = array('pdf', 'doc', 'docx', 'txt', 'zip', 'rar', '7z');

		$filepaths = Repository::processFileUploads('uploadedfile', $target_directory, $valid_extensions);
		foreach($filepaths as $filepath)
		{
			$ext = pathinfo($filepath, PATHINFO_EXTENSION);
			$path = dirname($filepath);
			rename($filepath, $path.'/cv_1_'.$candidate_id.'.'.$ext);
		}

		$filepaths = Repository::processFileUploads('uploadedmockfile', $target_directory);
		foreach($filepaths as $filepath)
		{
			$ext = pathinfo($filepath, PATHINFO_EXTENSION);
			$path = dirname($filepath);
			rename($filepath, $path.'/mock_'.$candidate_id.'.'.$ext);
		}

		$filepaths = Repository::processFileUploads('uploadednotesfile', $target_directory);
		foreach($filepaths as $filepath)
		{
			$ext = pathinfo($filepath, PATHINFO_EXTENSION);
			$path = dirname($filepath);
			rename($filepath, $path.'/notes_'.$candidate_id.'.'.$ext);
		}
	}

	private function sendRejectEmail(PDO $link, $input_data)
	{
		$candidate_email = isset($input_data['email'])?$input_data['email']:'';
		if(isset($input_data['candidate_id']) && $input_data['candidate_id'] != '' && $candidate_email == '')
		{
			$candidate_email = DAO::getSingleValue($link, "SELECT email FROM candidate WHERE id = '" . $input_data['candidate_id'] . "'");
		}
		if($candidate_email == '')
			return;
		$candidate_name = isset($input_data['firstnames'])?$input_data['firstnames']:'';
		$candidate_name .= isset($input_data['surname'])?$input_data['surname']:'';
		if(isset($input_data['candidate_id']) && $input_data['candidate_id'] != '' && $candidate_name == '')
		{
			$candidate_name = DAO::getSingleValue($link, "SELECT CONCAT(firstnames, ' ', surname) FROM candidate WHERE id = '" . $input_data['candidate_id'] . "'");
		}
		$email_body = DAO::getSingleValue($link, "SELECT template FROM lookup_candidate_email_templates WHERE template_type = 'KILLER_QUESTION_DECLINE_EMAIL'");
		$email_body = str_replace('$$CANDIDATE_NAME$$', $candidate_name, $email_body);
		if(DB_NAME == "am_superdrug")
			$email_body = str_replace('$$LOGO$$', '<img title="Superdrug" src="https://sd-demo.sunesis.uk.net/images/logos/superdrug.bmp" alt="Superdrug" style="width: 100px;" />', $email_body);
		else
			$email_body = str_replace('$$LOGO$$', '<img title="Perspective" src="https://sd-demo.sunesis.uk.net/images/logos/SUNlogo.jpg" alt="Perspective" style="width: 100px;" />', $email_body);
		if(DB_NAME == "am_superdrug")
			Emailer::html_mail($candidate_email, SystemConfig::getEntityValue($link, 'rec_v2_email'), 'Superdrug Application Update', '', $email_body, array(), array('X-Mailer: PHP/' . phpversion()));
		else
			Emailer::html_mail($candidate_email, SystemConfig::getEntityValue($link, 'rec_v2_email'), 'Perspective Application Update', '', $email_body, array(), array('X-Mailer: PHP/' . phpversion()));
	}

	private function sendWelcomeEmailToCandidate(PDO $link, $application_id)
	{
		$application = RecCandidateApplication::loadFromDatabaseByID($link, $application_id);

		$email_body = DAO::getSingleValue($link, "SELECT template FROM lookup_candidate_email_templates WHERE template_type = 'FIRST_RESPONSE_EMAIL_FOR_CANDIDATE'");
		$email_body = str_replace('$$CANDIDATE_NAME$$', $application->candidate->firstnames . ' ' . $application->candidate->surname, $email_body);
		$email_body = str_replace('$$VACANCY_TITLE$$', $application->vacancy->vacancy_title, $email_body);
		$email_body = str_replace('$$STORE_LOCATION$$', $application->vacancy->getLocation($link), $email_body);
		if(DB_NAME == "am_superdrug")
			$email_body = str_replace('$$LOGO$$', '<img title="Superdrug" src="https://sd-demo.sunesis.uk.net/images/logos/superdrug.bmp" alt="Superdrug" style="width: 100px;" />', $email_body);
		else
			$email_body = str_replace('$$LOGO$$', '<img title="Perspective" src="https://sd-demo.sunesis.uk.net/images/logos/SUNlogo.jpg" alt="Perspective" style="width: 100px;" />', $email_body);
		if(DB_NAME == "am_superdrug")
			Emailer::html_mail($application->candidate->email, SystemConfig::getEntityValue($link, 'rec_v2_email'), 'Superdrug Application Update', '', $email_body, array(), array('X-Mailer: PHP/' . phpversion()));
		else
			Emailer::html_mail($application->candidate->email, SystemConfig::getEntityValue($link, 'rec_v2_email'), 'Perspective Application Update', '', $email_body, array(), array('X-Mailer: PHP/' . phpversion()));
		unset($application);
	}

	private function candidateAlreadyExists(PDO $link, $firstnames, $surname, $dob, $postcode)
	{
		$firstnames = trim(strtolower($link->quote($firstnames)));
		$surname = trim(strtolower($link->quote($surname)));
		$dob = Date::toMySQL($dob);
		$dob = trim(strtolower($link->quote($dob)));
		$postcode = trim(strtolower($link->quote($postcode)));

		$sql = new SQLStatement("SELECT COUNT(*) FROM candidate");
		$sql->setClause("WHERE TRIM(LOWER(candidate.firstnames)) = {$firstnames}");
		$sql->setClause("WHERE TRIM(LOWER(candidate.surname)) = {$surname}");
		$sql->setClause("WHERE TRIM(LOWER(candidate.dob)) = {$dob}");
		$sql->setClause("WHERE TRIM(LOWER(candidate.postcode)) = {$postcode}");

		$found = (int)DAO::getSingleValue($link, $sql->__toString());
		if($found > 0)
			return true;
		else
			return false;
	}

	private function candidateAlreadyExistsSoGiveMeCandidateID(PDO $link, $firstnames, $surname, $dob, $postcode)
	{
		$firstnames = trim(strtolower($link->quote($firstnames)));
		$surname = trim(strtolower($link->quote($surname)));
		$dob = Date::toMySQL($dob);
		$dob = trim(strtolower($link->quote($dob)));
		$postcode = trim(strtolower($link->quote($postcode)));

		$sql = new SQLStatement("SELECT candidate.id FROM candidate");
		$sql->setClause("WHERE TRIM(LOWER(candidate.firstnames)) = {$firstnames}");
		$sql->setClause("WHERE TRIM(LOWER(candidate.surname)) = {$surname}");
		$sql->setClause("WHERE TRIM(LOWER(candidate.dob)) = {$dob}");
		$sql->setClause("WHERE TRIM(LOWER(candidate.postcode)) = {$postcode}");

		return (int)DAO::getSingleValue($link, $sql->__toString());
	}

	private function candidateHasAppliedForThisVacancy(PDO $link, $firstnames, $surname, $dob, $postcode, $vacancy_id)
	{
		$firstnames = trim(strtolower($link->quote($firstnames)));
		$surname = trim(strtolower($link->quote($surname)));
		$dob = Date::toMySQL($dob);
		$dob = trim(strtolower($link->quote($dob)));
		$postcode = trim(strtolower($link->quote($postcode)));

		$sql = new SQLStatement("SELECT candidate.id FROM candidate");
		$sql->setClause("WHERE TRIM(LOWER(candidate.firstnames)) = {$firstnames}");
		$sql->setClause("WHERE TRIM(LOWER(candidate.surname)) = {$surname}");
		$sql->setClause("WHERE TRIM(LOWER(candidate.dob)) = {$dob}");
		$sql->setClause("WHERE TRIM(LOWER(candidate.postcode)) = {$postcode}");

		$candidate_id = DAO::getSingleValue($link, $sql->__toString());

		if($candidate_id == '')
			return false;

		$candidate_application = RecCandidateApplication::loadFromDatabaseByVacancyAndCandidate($link, $vacancy_id, $candidate_id);
		if(!is_null($candidate_application))
			return true;
		else
			return false;
	}

	public function save_candidate_qualifications(PDO $link, $candidate_id, $input_data)
	{
		if($candidate_id == '')
			return;

		if(!is_array($input_data))
			return;

		$quals = array();

		$objGCSEEnglish = new stdClass();
		$objGCSEEnglish->candidate_id = $candidate_id;
		$objGCSEEnglish->qualification_level = 'GCSE';
		$objGCSEEnglish->qualification_subject = 'English Language';
		$objGCSEEnglish->qualification_grade = isset($input_data['gcse_english_grade'])?$input_data['gcse_english_grade']:'';
		$objGCSEEnglish->qualification_date = isset($input_data['gcse_english_date_completed'])?$input_data['gcse_english_date_completed']:'';
		$objGCSEEnglish->institution = isset($input_data['gcse_english_school'])?Text::utf8_to_latin1($input_data['gcse_english_school']):'';
		$quals[] = $objGCSEEnglish;
		unset($objGCSEEnglish);

		$objGCSEMaths = new stdClass();
		$objGCSEMaths->candidate_id = $candidate_id;
		$objGCSEMaths->qualification_level = 'GCSE';
		$objGCSEMaths->qualification_subject = 'Maths';
		$objGCSEMaths->qualification_grade = isset($input_data['gcse_maths_grade'])?$input_data['gcse_maths_grade']:'';
		$objGCSEMaths->qualification_date = isset($input_data['gcse_maths_date_completed'])?$input_data['gcse_maths_date_completed']:'';
		$objGCSEMaths->institution = isset($input_data['gcse_maths_school'])?Text::utf8_to_latin1($input_data['gcse_maths_school']):'';
		$quals[] = $objGCSEMaths;
		unset($objGCSEMaths);

		for($i = 1; $i <= 3; $i++)
		{
			$objQual = new stdClass();
			$objQual->candidate_id = $candidate_id;
			$objQual->qualification_level = isset($input_data['level'.$i])?$input_data['level'.$i]:'';
			$objQual->qualification_subject = isset($input_data['subject'.$i])?Text::utf8_to_latin1($input_data['subject'.$i]):'';
			$objQual->qualification_grade = isset($input_data['grade'.$i])?$input_data['grade'.$i]:'';
			$objQual->qualification_date = isset($input_data['date_completed'.$i])?$input_data['date_completed'.$i]:'';
			$objQual->institution = isset($input_data['date_school'.$i])?Text::utf8_to_latin1($input_data['date_school'.$i]):'';
			$quals[] = $objQual;
			unset($objQual);
		}

		DAO::multipleRowInsert($link, 'candidate_qualification', $quals);
		unset($quals);
	}

	public function save_candidate_employments(PDO $link, $candidate_id, $input_data)
	{
		if($candidate_id == '')
			return;

		if(!is_array($input_data))
			return;

		$employments = array();

		for($i = 1; $i <= 5; $i++)
		{
			$objEmployment = new stdClass();
			$objEmployment->candidate_id = $candidate_id;
			$objEmployment->company_name = isset($input_data['company_name'.$i])?Text::utf8_to_latin1($input_data['company_name'.$i]):'';
			$objEmployment->job_title = isset($input_data['job_title'.$i])?Text::utf8_to_latin1($input_data['job_title'.$i]):'';
			$objEmployment->start_date = isset($input_data['start_date'.$i])?$input_data['start_date'.$i]:'';
			$objEmployment->end_date = isset($input_data['end_date'.$i])?$input_data['end_date'.$i]:'';
			$objEmployment->skills = isset($input_data['skills'.$i])?Text::utf8_to_latin1($input_data['skills'.$i]):'';
			$employments[] = $objEmployment;
			unset($objEmployment);
		}

		DAO::multipleRowInsert($link, 'candidate_history', $employments);
		unset($employments);
	}
}
?>
