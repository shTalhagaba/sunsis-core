<?php
class rec_view_edit_vacancy_application implements IAction
{
	public $contact_name = 'Test Perspective';
	public $contact_telephone = '0XXXX XXXXXX';
	public $client_name = 'Perspective';
	public $client_logo_url = 'https://demo.sunesis.uk.net/images/logos/SUNlogo.jpg';
	public $client_logo = 'SUNlogo.jpg';
	public $client_auto_email_subject = 'Perspective Application Update';

	public function execute(PDO $link)
	{
		$id = isset($_REQUEST['id'])?$_REQUEST['id']:'';
		$selected_tab = isset($_REQUEST['selected_tab'])?$_REQUEST['selected_tab']:'tab1';
		$subaction = isset($_REQUEST['subaction'])?$_REQUEST['subaction']:'';

		if($subaction == 'saveScreening')
		{
			echo $this->saveScreening($link);
			exit;
		}
		if($subaction == 'updateCandidateStatusToCVSent')
		{
			echo $this->updateCandidateStatusToCVSent($link);
			exit;
		}
		if($subaction == 'updateCandidateInterviewStatus')
		{
			echo $this->updateCandidateInterviewStatus($link);
			exit;
		}
		if($subaction == 'convertToSunesisLearner')
		{
			echo $this->convertToSunesisLearner($link);
			exit;
		}
		if($subaction == 'decideScreeningLater')
		{
			echo $this->decideScreeningLater($link);
			exit;
		}
		if($subaction == 'rejectApplication')
		{
			echo $this->rejectApplication($link);
			exit;
		}
		if($subaction == 'saveTelephoneInterview')
		{
			echo $this->saveTelephoneInterview($link);
			exit;
		}
		if($subaction == 'getAssessorEmailText')
		{
			echo $this->getAssessorEmailText($link);
			exit;
		}
		if($subaction == 'getLearnerLearningEvents')
		{
			echo $this->getLearnerLearningEvents($link);
			exit;
		}
		if($subaction == 'saveCandidateULN')
		{
			echo $this->saveCandidateULN($link);
			exit;
		}

		if($id == '')
			throw new Exception('Missing querystring: id');


		$application = RecCandidateApplication::loadFromDatabaseByID($link, $id);
		if(is_null($application))
			throw new Exception('Application not found.');

		$_SESSION['bc']->add($link, "do.php?_action=rec_view_edit_vacancy_application&id=" . $application->id . '&selected_tab=' . $selected_tab, "View/Edit Vacancy Application");

		$vacancy = $application->vacancy;
		$candidate = $application->candidate;
		$shift_pattern = $candidate->getShiftPattern($link);
		if($shift_pattern == '')
		{
			$shift_pattern = new stdClass();
			$shift_pattern->mon_start_time = '';
			$shift_pattern->tue_start_time = '';
			$shift_pattern->wed_start_time = '';
			$shift_pattern->thu_start_time = '';
			$shift_pattern->fri_start_time = '';
			$shift_pattern->sat_start_time = '';
			$shift_pattern->sun_start_time = '';
			$shift_pattern->mon_end_time = '';
			$shift_pattern->tue_end_time = '';
			$shift_pattern->wed_end_time = '';
			$shift_pattern->thu_end_time = '';
			$shift_pattern->fri_end_time = '';
			$shift_pattern->sat_end_time = '';
			$shift_pattern->sun_end_time = '';
		}

		$vacancy_location = DAO::getSingleValue($link, "SELECT CONCAT(COALESCE(full_name), ' (',COALESCE(`address_line_1`,''),' ',COALESCE(`address_line_2`,''),', ', COALESCE(`postcode`,''), ')') AS location FROM locations WHERE id = '$vacancy->location_id'");

		$tab1 = "";
		$tab2 = "";
		$tab3 = "";
		$tab4 = "";
		$tab5 = "";

		if(isset($$selected_tab))
			$$selected_tab = " class='selected' ";
		else
			$tab1 = " class='selected' ";

		$cv_file_link = '';
		if (file_exists(DATA_ROOT . "/uploads/" . DB_NAME . "/recruitment/cv_1_" . $candidate->id . ".doc")) {
			$cv_file_link = '<a href="do.php?_action=downloader&path=/recruitment/&f=cv_1_' . $candidate->id . '.doc">Applicants CV 1</a> (doc)';
		} elseif (file_exists(DATA_ROOT . "/uploads/" . DB_NAME . "/recruitment/cv_1_" . $candidate->id . ".docx")) {
			$cv_file_link = '<a href="do.php?_action=downloader&path=/recruitment&f=cv_1_' . $candidate->id . '.docx">Applicants CV 1</a> (docx)';
		}
		elseif (file_exists(DATA_ROOT . "/uploads/" . DB_NAME . "/recruitment/cv_1_" . $candidate->id . ".pdf")) {
			$cv_file_link = '<a href="do.php?_action=downloader&path=/recruitment/&f=cv_1_' . $candidate->id . '.pdf">Applicants CV 1</a> (pdf)';
		}

		$application_questions = DAO::getResultset($link, "SELECT question_id, description FROM rec_vacancy_questions INNER JOIN rec_questions ON rec_vacancy_questions.question_id = rec_questions.id WHERE vacancy_id = '{$vacancy->id}'", DAO::FETCH_ASSOC);

		$score_ddl = array();
		for($i = 0; $i <= 5; $i++)
			$score_ddl[] = array($i, $i, '');

		$telephone_interview_outcome = array(
			array('decidelater', 'Decide later', ''),
			array('successful', 'Submitted for interview', ''),
			array('unsuccessful', 'Unsuccessful', ''),
			array('withdraw', 'Withdraw', ''),
			array('notcontactable', 'Not Contactable', '')
		);

		$yes_no_options = array(
			array('0', 'No', ''),
			array('1', 'Yes', '')
		);

		$assessorsSQL = <<<SQL
SELECT
  users.id,
  CONCAT(
    users.`firstnames`,
    ' ',
    users.`surname`,
    ' (',
    IF(users.`work_email` IS NULL, '---NO EMAIL ADDRESS---', users.work_email),
    ')'
  ),
  organisations.`legal_name`
FROM
  users
  INNER JOIN organisations
    ON users.`employer_id` = organisations.`id`
WHERE users.type = '3'
  AND users.`web_access` = '1'
ORDER BY legal_name,
  firstnames ;
SQL;
		$assessorsDDL = DAO::getResultset($link, $assessorsSQL);

		$vacancy_is_full = false;
		$number_of_sunesis_learnersIn_this_application = (int)DAO::getSingleValue($link, "SELECT COUNT(*) FROM candidate_applications WHERE vacancy_id = '" . $vacancy->id . "' AND current_status = '" . RecCandidateApplication::SUNESIS_LEARNER . "'");
		if($number_of_sunesis_learnersIn_this_application >= (int)$vacancy->no_of_positions)
			$vacancy_is_full = true;

		$application_interview_score = DAO::getSingleValue($link, "SELECT SUM(score) FROM candidate_application_interview_screening WHERE application_id = '{$application->id}'"); // in case if decide later was previously selected after telephone interview
		if($application_interview_score == '')
			$application_interview_score = (int)0;

		if(DB_NAME == "am_superdrug")
		{
			$this->contact_name = 'Gemma Allman';
			$this->contact_telephone = '01977 657031';
			$this->client_name = 'Superdrug';
			$this->client_logo_url = 'https://superdrug.sunesis.uk.net/images/logos/superdrug.bmp';
			$this->client_logo = 'superdrug.bmp';
			$this->client_auto_email_subject = 'Superdrug Application Update';
		}
		$logo = SystemConfig::getEntityValue($link, 'logo');
		if($logo == '')
			$logo = 'SUNlogo.jpg';

		require_once('tpl_rec_view_edit_vacancy_application.php');
	}

	private function decideScreeningLater(PDO $link)
	{
		$application_id = isset($_REQUEST['application_id'])?$_REQUEST['application_id']:'';
		$screening_rag = isset($_REQUEST['screening_rag'])?$_REQUEST['screening_rag']:'';
		$screening_comments = isset($_REQUEST['screening_comments'])?$_REQUEST['screening_comments']:'';
		if($application_id == '')
			throw new Exception('Missing querystring: application_id');
		$objApplication = RecCandidateApplication::loadFromDatabaseByID($link, $application_id);
		DAO::transaction_start($link);
		try
		{
			$objApplication->current_status = RecCandidateApplication::CREATED;
			$objApplication->screening_rag = $screening_rag;
			$objApplication->save($link);
			$objApplication->saveCandidateApplicationStatus($link, RecCandidateApplication::CREATED, $screening_comments);

			DAO::transaction_commit($link);
		}
		catch(Exception $e)
		{
			DAO::transaction_rollback($link, $e);
			throw new WrappedException($e);
		}
		unset($objApplication);
		return true;
	}

	private function rejectApplication(PDO $link)
	{
		$application_id = isset($_REQUEST['application_id'])?$_REQUEST['application_id']:'';
		$screening_rag = isset($_REQUEST['screening_rag'])?$_REQUEST['screening_rag']:'';
		$screening_comments = isset($_REQUEST['screening_comments'])?$_REQUEST['screening_comments']:'';
		if($application_id == '')
			throw new Exception('Missing querystring: application_id');
		$objApplication = RecCandidateApplication::loadFromDatabaseByID($link, $application_id);
		DAO::transaction_start($link);
		try
		{
			$objApplication->current_status = RecCandidateApplication::REJECTED;
			$objApplication->screening_rag = $screening_rag;
			$objApplication->save($link);
			$objApplication->saveCandidateApplicationStatus($link, RecCandidateApplication::REJECTED, $screening_comments);
			$objApplication->candidate->saveCandidateNotes($link, 'Candidate application is set as "Rejected". [Vacancy Ref: "' . $objApplication->vacancy->vacancy_reference . '", Vacancy Title: "' . $objApplication->vacancy->vacancy_title . '"]');

			if(isset($_REQUEST['after_interview']) && $_REQUEST['after_interview'] == '1')
				$email_body = DAO::getSingleValue($link, "SELECT template FROM lookup_candidate_email_templates WHERE template_type = 'REJECT_AFTER_TELEPHONE_INTERVIEW'");
			else
				$email_body = DAO::getSingleValue($link, "SELECT template FROM lookup_candidate_email_templates WHERE template_type = 'REJECT_BEFORE_TELEPHONE_INTERVIEW'");
			$email_body = str_replace('$$CANDIDATE_NAME$$', $objApplication->candidate->firstnames . ' ' . $objApplication->candidate->surname, $email_body);
			$email_body = str_replace('$$VACANCY_TITLE$$', $objApplication->vacancy->vacancy_title, $email_body);
			$email_body = str_replace('$$STORE_LOCATION$$', $objApplication->vacancy->getLocation($link), $email_body);
			if(DB_NAME == "am_superdrug")
				$email_body = str_replace('$$LOGO$$', '<img title="Superdrug Plc" src="https://superdrug.sunesis.uk.net/images/logos/superdrug.bmp" alt="Superdrug Plc" style="width: 100px;" />', $email_body);
			elseif(DB_NAME == "am_sd_demo")
				$email_body = str_replace('$$LOGO$$', '<img title="Superdrug Plc" src="https://sd_demo.sunesis.uk.net/images/logos/SuperdrugSavers.png" alt="Superdrug Plc" style="width: 100px;" />', $email_body);
			else
				$email_body = str_replace('$$LOGO$$', '<img title="' . $this->contact_name . '" src="' . $this->client_logo_url . '" alt="' . $this->client_name . '" style="width: 100px;" />', $email_body);

			if(isset($_REQUEST['yes_no_auto_email']) && $_REQUEST['yes_no_auto_email'] == '1')
			{
				$objEmail = new stdClass();
				$objEmail->candidate_id = $objApplication->candidate->id;
				$objEmail->subject = 'Application for ' . $objApplication->vacancy->vacancy_title;
				$objEmail->candidate_email = $objApplication->candidate->email;
				$sender_email = SystemConfig::getEntityValue($link, 'rec_v2_email');
				$objEmail->email_body = $email_body;
				$objEmail->by_whom = $_SESSION['user']->id;
				if(Emailer::html_mail($objEmail->candidate_email, $sender_email, $objEmail->subject, '', $objEmail->email_body, array(), array('X-Mailer: PHP/' . phpversion())))
				{
					DAO::saveObjectToTable($link, 'candidate_emails', $objEmail);
				}
			}
			DAO::transaction_commit($link);
		}
		catch(Exception $e)
		{
			DAO::transaction_rollback($link, $e);
			throw new WrappedException($e);
		}
		unset($objApplication);
		return true;
	}

	private function convertToSunesisLearner(PDO $link)
	{
		$application_id = isset($_REQUEST['application_id'])?$_REQUEST['application_id']:'';
		$objApplication = RecCandidateApplication::loadFromDatabaseByID($link, $application_id);
		if(is_null($objApplication))
			throw new Exception("Application record with id {$application_id} not found");

		$assessor = isset($_REQUEST['assessor'])?$_REQUEST['assessor']:'';
		$screening_comments = isset($_REQUEST['screening_comments'])?$_REQUEST['screening_comments']:'';
		$assessor_email_contents = isset($_REQUEST['assessor_email_contents'])?$_REQUEST['assessor_email_contents']:'';

		DAO::transaction_start($link);
		try
		{
			$objApplication->current_status = RecCandidateApplication::SUNESIS_LEARNER;
			$objApplication->save($link);
			$objApplication->saveCandidateApplicationStatus($link, RecCandidateApplication::SUNESIS_LEARNER, $screening_comments);
			$learner_id = $objApplication->candidate->convertToLearner($link, $objApplication->vacancy, $assessor);
			$objApplication->candidate->saveCandidateNotes($link, 'Candidate application is set as "Sunesis Learner". [Vacancy Ref: "' . $objApplication->vacancy->vacancy_reference . '", Vacancy Title: "' . $objApplication->vacancy->vacancy_title . '"]');

			DAO::transaction_commit($link);
		}
		catch(Exception $e)
		{
			DAO::transaction_rollback($link, $e);
			throw new WrappedException($e);
		}
		if($assessor != '' && trim($assessor_email_contents) != '')
		{
			$assessor_email = DAO::getSingleValue($link, "SELECT work_email FROM users WHERE id = '{$assessor}'");
			if($assessor_email != '')
			{
				Emailer::notification_email($assessor_email, 'apprenticeships@perspective-uk.com', SystemConfig::getEntityValue($link, 'rec_v2_email'), $this->client_auto_email_subject, '', $assessor_email_contents, array(), array('X-Mailer: PHP/' . phpversion()));
			}
			$conversion_email_cc = isset($_REQUEST['conversion_email_cc'])?$_REQUEST['conversion_email_cc']:'';
			if($conversion_email_cc != '')
			{
				$conversion_email_cc = trim($conversion_email_cc);
				$conversion_email_cc = explode(',', $conversion_email_cc);
				foreach($conversion_email_cc AS $email)
				{
					if(filter_var($email, FILTER_VALIDATE_EMAIL))
						Emailer::notification_email($email, 'apprenticeships@perspective-uk.com', SystemConfig::getEntityValue($link, 'rec_v2_email'), $this->client_auto_email_subject, '', $assessor_email_contents, array(), array('X-Mailer: PHP/' . phpversion()));
					sleep(2);
				}
			}
		}

		unset($objApplication);
		return $learner_id;
	}

	private function updateCandidateStatusToCVSent(PDO $link)
	{
		$application_id = isset($_REQUEST['application_id'])?$_REQUEST['application_id']:'';
		$objApplication = RecCandidateApplication::loadFromDatabaseByID($link, $application_id);
		if(is_null($objApplication))
			throw new Exception("Application record with id {$application_id} not found");
		$screening_comments = isset($_REQUEST['screening_comments'])?$_REQUEST['screening_comments']:'';
		DAO::transaction_start($link);
		try
		{
			$objApplication->current_status = RecCandidateApplication::CV_SENT;
			$objApplication->save($link);
			$objApplication->saveCandidateApplicationStatus($link, RecCandidateApplication::CV_SENT, $screening_comments);

			if(isset($_REQUEST['receiverName']) && $_REQUEST['receiverName'] != '')
			{
				$receiverName = $_REQUEST['receiverName']?$_REQUEST['receiverName']:'';
				$receiverEmail = $_REQUEST['receiverEmail']?$_REQUEST['receiverEmail']:'';
				$senderEmail = $_REQUEST['senderEmail']?$_REQUEST['senderEmail']:'';
				$emailSubject = $_REQUEST['emailSubject']?$_REQUEST['emailSubject']:'';
				$email_contents = $_REQUEST['email_contents']?$_REQUEST['email_contents']:'';
				Emailer::notification_email($receiverEmail, 'apprenticeships@perspective-uk.com', $senderEmail, $emailSubject, '', $email_contents, array(), array('X-Mailer: PHP/' . phpversion()));
			}
			//$this->sendAutoEmailToCandidate($link, $objApplication, 'SHORTLIST_FOR_FACE_TO_FACE_INTERVIEW');

			DAO::transaction_commit($link);
		}
		catch(Exception $e)
		{
			DAO::transaction_rollback($link, $e);
			throw new WrappedException($e);
		}
		unset($objApplication);

		return true;
	}

	private function updateCandidateInterviewStatus(PDO $link)
	{
		$application_id = isset($_REQUEST['application_id'])?$_REQUEST['application_id']:'';
		$objApplication = RecCandidateApplication::loadFromDatabaseByID($link, $application_id);
		if(is_null($objApplication))
			throw new Exception("Application record with id {$application_id} not found");
		$interview_outcome = isset($_REQUEST['interview_outcome'])?$_REQUEST['interview_outcome']:'';
		$screening_comments = isset($_REQUEST['screening_comments'])?$_REQUEST['screening_comments']:'';
		DAO::transaction_start($link);
		try
		{
			if($interview_outcome == 'decidelater')
			{
				$objApplication->save($link);
				$objApplication->saveCandidateApplicationStatus($link, $objApplication->current_status, $screening_comments);
			}
			elseif($interview_outcome == 'successfullevel1')
			{
				$objApplication->ftof_interview_level1 = 'successful';
				$objApplication->save($link);
				$objApplication->saveCandidateApplicationStatus($link, $objApplication->current_status, $screening_comments);
			}
			elseif($interview_outcome == 'successfullevel2')
			{
				$objApplication->ftof_interview_level2 = 'successful';
				$objApplication->current_status = RecCandidateApplication::INTERVIEW_SUCCESSFUL;
				$objApplication->save($link);
				$objApplication->saveCandidateApplicationStatus($link, $objApplication->current_status, $screening_comments);
				$objApplication->candidate->saveCandidateNotes($link, 'Candidate application is set as "Interview Successful". [Vacancy Ref: "' . $objApplication->vacancy->vacancy_reference . '", Vacancy Title: "' . $objApplication->vacancy->vacancy_title . '"]');
				$this->sendAutoEmailToAdmin($link, $objApplication);
			}
			elseif($interview_outcome == 'unsuccessful')
			{
				$objApplication->ftof_interview_level2 = 'unsuccessful';
				$objApplication->current_status = RecCandidateApplication::INTERVIEW_UNSUCCESSFUL;
				$objApplication->save($link);
				$objApplication->saveCandidateApplicationStatus($link, $objApplication->current_status, $screening_comments);
				$objApplication->candidate->saveCandidateNotes($link, 'Candidate application is set as "Interview Unsuccessful". [Vacancy Ref: "' . $objApplication->vacancy->vacancy_reference . '", Vacancy Title: "' . $objApplication->vacancy->vacancy_title . '"]');
			}
			elseif($interview_outcome == 'didnotattend')
			{
				$objApplication->current_status = RecCandidateApplication::REJECTED;
				$objApplication->save($link);
				$objApplication->saveCandidateApplicationStatus($link, $objApplication->current_status, $screening_comments);
				$objApplication->candidate->saveCandidateNotes($link, 'Candidate application is set as "Rejected" because of No-Show. [Vacancy Ref: "' . $objApplication->vacancy->vacancy_reference . '", Vacancy Title: "' . $objApplication->vacancy->vacancy_title . '"]');
			}
			elseif($interview_outcome == 'withdraw')
			{
				$objApplication->current_status = RecCandidateApplication::WITHDRAWN;
				$objApplication->save($link);
				$objApplication->saveCandidateApplicationStatus($link, $objApplication->current_status, $screening_comments);
				$objApplication->candidate->saveCandidateNotes($link, 'Candidate application is set as "Withdrawn". [Vacancy Ref: "' . $objApplication->vacancy->vacancy_reference . '", Vacancy Title: "' . $objApplication->vacancy->vacancy_title . '"]');
			}
			else
			{
				throw new Exception('No status given, operation aborted.');
			}
			DAO::transaction_commit($link);
		}
		catch(Exception $e)
		{
			DAO::transaction_rollback($link, $e);
			throw new WrappedException($e);
		}
		unset($objApplication);

		return true;
	}

	private function sendAutoEmailToAdmin(PDO $link, RecCandidateApplication $application)
	{
		$candidate_name = $application->candidate->firstnames . ' ' . $application->candidate->surname;
		$candidate_contact = $application->candidate->mobile . ' ' . $application->candidate->telephone;
		$vacancy_ref = $application->vacancy->vacancy_reference;
		$vacancy_title = $application->vacancy->vacancy_title;

		$store_details = $application->vacancy->getEmployerName($link) . '<br>' . $application->vacancy->getLocation($link);

		if($application->current_status == RecCandidateApplication::INTERVIEW_SUCCESSFUL)
			$html = <<<HTML
<p>This is an automated e-mail sent by Sunesis e-Recruitment.</p>
<p>Following candidate is successful in a face-to-face interview.</p>
<p><strong>Candidate Name: </strong> $candidate_name </p>
<p><strong>Candidate Contact: $candidate_contact</strong> </p>
<p><strong>Vacancy Reference: </strong> $vacancy_ref</p>
<p><strong>Vacancy Title: </strong> $vacancy_title</p>
<p><strong>Store Details: </strong> $store_details</p>
<p><img title="$this->client_name" src="$this->client_logo_url" alt="$this->client_name" style="width: 100px;" /></p>
HTML;
		elseif($application->current_status == RecCandidateApplication::INTERVIEW_UNSUCCESSFUL)
			$html = <<<HTML
<p>This is an automated e-mail sent by Sunesis e-Recruitment.</p>
<p>Following candidate is successful in a face-to-face interview.</p>
<p><strong>Candidate Name: </strong> $candidate_name </p>
<p><strong>Candidate Contact: $candidate_contact</strong> </p>
<p><strong>Vacancy Reference: </strong> $vacancy_ref</p>
<p><strong>Vacancy Title: </strong> $vacancy_title</p>
<p><strong>Store Details: </strong> $store_details</p>
<p><img title="$this->client_name" src="$this->client_logo_url" alt="$this->client_name" style="width: 100px;" /></p>
HTML;
		else
			return;

		Emailer::notification_email(SystemConfig::getEntityValue($link, 'rec_v2_email'), 'apprenticeships@perspective-uk.com', SystemConfig::getEntityValue($link, 'rec_v2_email'), 'Update of Application for : ' . $candidate_name, '', $html, array(), array('X-Mailer: PHP/' . phpversion()));
	}

	private function sendAutoEmailToCandidate(PDO $link, RecCandidateApplication $application, $template_type)
	{
		if(is_null($application))
			return;

		$email_body = DAO::getSingleValue($link, "SELECT template FROM lookup_candidate_email_templates WHERE template_type = '{$template_type}'");
		$email_body = str_replace('$$CANDIDATE_NAME$$', $application->candidate->firstnames . ' ' . $application->candidate->surname, $email_body);
		$email_body = str_replace('$$VACANCY_TITLE$$', $application->vacancy->vacancy_title, $email_body);
		$email_body = str_replace('$$STORE_LOCATION$$', $application->vacancy->getLocation($link), $email_body);
		$email_body = str_replace('$$STORE_TELEPHONE$$', $application->vacancy->getLocationTelephone($link), $email_body);
		//$email_body = str_replace('$$LOGO$$', '<img title="' . $this->client_name . '" src="' . $this->client_logo_url . '" alt="' . $this->client_name . '" style="width: 100px;" />', $email_body);
		
		if(DB_NAME == "am_superdrug")
			$email_body = str_replace('$$LOGO$$', '<img title="Superdrug Plc" src="https://superdrug.sunesis.uk.net/images/logos/superdrug.bmp" alt="Superdrug Plc" style="width: 100px;" />', $email_body);
		elseif(DB_NAME == "am_sd_demo")
			$email_body = str_replace('$$LOGO$$', '<img title="Superdrug Plc" src="https://sd_demo.sunesis.uk.net/images/logos/SuperdrugSavers.png" alt="Superdrug Plc" style="width: 100px;" />', $email_body);
		else
			$email_body = str_replace('$$LOGO$$', '<img title="' . $this->contact_name . '" src="' . $this->client_logo_url . '" alt="' . $this->client_name . '" style="width: 100px;" />', $email_body);

		$objEmail = new stdClass();
		$objEmail->candidate_id = $application->candidate->id;
		$objEmail->subject = $this->client_auto_email_subject;
		$objEmail->candidate_email = $application->candidate->email;
		$sender_email = SystemConfig::getEntityValue($link, 'rec_v2_email');
		$objEmail->email_body = $email_body;
		$objEmail->by_whom = $_SESSION['user']->id;
		if(Emailer::html_mail($objEmail->candidate_email, $sender_email, $objEmail->subject, '', $objEmail->email_body, array(), array('X-Mailer: PHP/' . phpversion())))
		{
			DAO::saveObjectToTable($link, 'candidate_emails', $objEmail);
		}
	}

	private function saveScreening(PDO $link)
	{
		$application_id = isset($_REQUEST['application_id'])?$_REQUEST['application_id']:'';
		$objApplication = RecCandidateApplication::loadFromDatabaseByID($link, $application_id);
		if(is_null($objApplication))
			throw new Exception("Application record with id {$application_id} not found");

		$screening_rag = isset($_REQUEST['screening_rag'])?$_REQUEST['screening_rag']:'';
		$screening_comments = isset($_REQUEST['screening_comments'])?$_REQUEST['screening_comments']:'';
		$screening = isset($_REQUEST['screening'])?json_decode($_REQUEST['screening'], true):'';
		foreach($screening AS &$aa)
			foreach($aa as $key => &$value)
				if($key == 'question_id')
					$value = str_replace('q_a_', '', $value);

		DAO::transaction_start($link);
		try
		{
			//update candidate_applications table - overall candidate application object
			$objApplication->current_status = RecCandidateApplication::SCREENED;
			$objApplication->screening_rag = $screening_rag;
			$objApplication->save($link);

			//update candidate_application_status table - this saves the status history
			$objApplication->saveCandidateApplicationStatus($link, RecCandidateApplication::SCREENED, $screening_comments);

			//update candidate_screening table - this is only for screening information i.e. questions, answers and scores
			DAO::multipleRowInsert($link, 'candidate_application_screening', $screening);

			//add an entry into overall candidate notes table
			$objApplication->candidate->saveCandidateNotes($link, 'Candidate application is screened. [Vacancy Ref: "' . $objApplication->vacancy->vacancy_reference . '", Vacancy Title: "' . $objApplication->vacancy->vacancy_title . '"]');

			if(isset($_REQUEST['yes_no_auto_email']) && $_REQUEST['yes_no_auto_email'] == '1')
				$this->sendAutoEmailToCandidate($link, $objApplication, 'SHORTLIST_FOR_TELEPHONE_INTERVIEW');

			DAO::transaction_commit($link);
		}
		catch(Exception $e)
		{
			DAO::transaction_rollback($link, $e);
			throw new WrappedException($e);
		}
		unset($objApplication);
		return true;
	}

	private function saveTelephoneInterview(PDO $link)
	{
		$application_id = isset($_REQUEST['application_id'])?$_REQUEST['application_id']:'';
		$objApplication = RecCandidateApplication::loadFromDatabaseByID($link, $application_id);
		if(is_null($objApplication))
			throw new Exception("Application record with id {$application_id} not found");

		$telephone_interview_score = isset($_REQUEST['telephone_interview_score'])?$_REQUEST['telephone_interview_score']:'';
		$telephone_interview_outcome = isset($_REQUEST['telephone_interview_outcome'])?$_REQUEST['telephone_interview_outcome']:'';
		$telephone_interview_comments = isset($_REQUEST['telephone_interview_comments'])?$_REQUEST['telephone_interview_comments']:'';
		$telephone_interview_outcome_email = isset($_REQUEST['telephone_interview_outcome_email'])?$_REQUEST['telephone_interview_outcome_email']:'';
		$telephone_interview = isset($_REQUEST['telephone_interview'])?json_decode($_REQUEST['telephone_interview'], true):'';
		foreach($telephone_interview AS &$aa)
			foreach($aa as $key => &$value)
			{
				if($key == 'question_id')
				{
					$value = str_replace('q_a_', '', $value);
					$value = str_replace('undefined', '0', $value);
				}
			}
		DAO::transaction_start($link);
		try
		{
			//update candidate_applications table - overall candidate application object
			//if($telephone_interview_outcome != 'decidelater' && $telephone_interview_outcome != 'withdraw')
			if($telephone_interview_outcome == 'successful')
				$objApplication->current_status = RecCandidateApplication::TELEPHONE_INTERVIEWED;
			if($telephone_interview_outcome == 'withdraw')
				$objApplication->current_status = RecCandidateApplication::WITHDRAWN;
			if($telephone_interview_outcome == 'notcontactable')
				$objApplication->current_status = RecCandidateApplication::REJECTED;
			if($telephone_interview_outcome == 'unsuccessful')
				$objApplication->current_status = RecCandidateApplication::REJECTED;

			$objApplication->telephone_interview_score = $telephone_interview_score;
			$objApplication->telephone_interview_outcome = $telephone_interview_outcome;
			$objApplication->save($link);

			//update candidate_application_status table - this saves the status history
			if($telephone_interview_outcome == 'decidelater')
				$telephone_interview_comments .= ' [---Decide Later option selected during telephonic interview---]';
			if($telephone_interview_outcome == 'withdraw')
				$telephone_interview_comments .= ' [---Withdraw option selected during telephonic interview---]';
			if($telephone_interview_outcome == 'notcontactable')
				$telephone_interview_comments .= ' [---Not Contactable option selected during telephonic interview---]';
			if($telephone_interview_outcome == 'unsuccessful')
				$telephone_interview_comments .= ' [---Unsuccessful option selected during telephonic interview---]';
			$objApplication->saveCandidateApplicationStatus($link, $objApplication->current_status, $telephone_interview_comments);

			//update candidate_screening table - this is only for screening information i.e. questions, answers and scores
			DAO::multipleRowInsert($link, 'candidate_application_interview_screening', $telephone_interview);

			//add an entry into overall candidate notes table
			$objApplication->candidate->saveCandidateNotes($link, 'Candidate application is telephone interviewed as "' . $telephone_interview_outcome . '". [Vacancy Ref: "' . $objApplication->vacancy->vacancy_reference . '", Vacancy Title: "' . $objApplication->vacancy->vacancy_title . '"]');

			if(trim($objApplication->candidate->email) != '' && trim($telephone_interview_outcome_email) != '')
			{
				if(Emailer::html_mail($objApplication->candidate->email, SystemConfig::getEntityValue($link, 'rec_v2_email'), $this->client_auto_email_subject, '', $telephone_interview_outcome_email, array(), array('X-Mailer: PHP/' . phpversion())))
				{
					$objEmail = new stdClass();
					$objEmail->candidate_id = $objApplication->candidate->id;
					$objEmail->subject = $this->client_auto_email_subject;
					$objEmail->candidate_email = $objApplication->candidate->email;
					$objEmail->email_body = $telephone_interview_outcome_email;
					$objEmail->by_whom = $_SESSION['user']->id;
					DAO::saveObjectToTable($link, 'candidate_emails', $objEmail);
					unset($objEmail);
				}
			}

			DAO::transaction_commit($link);
		}
		catch(Exception $e)
		{
			DAO::transaction_rollback($link, $e);
			throw new WrappedException($e);
		}
		unset($objApplication);
		return true;
	}

	private function renderStatus(PDO $link, RecCandidateApplication $application)
	{
		$status_dates = $application->getApplicationStatusUpdateDate($link);

		$html = '<ul class="progress-indicator">';
		switch($application->current_status)
		{
			case 0:
				$html .= '<li class="active"><span class="bubble"></span>Not Screened<br>' . Date::toShort($status_dates[0]) . '</li>';
				$html .= '<li><span class="bubble"></span>Screened</li><li><span class="bubble"></span>Telephone Interviewed</li><li><span class="bubble"></span>CV Sent</li><li><span class="bubble"></span>Interview Successful</li><li><span class="bubble"></span>Interview Unsuccessful</li><li><span class="bubble"></span>Sunesis Learner</li>';
				break;
			case 1:
				$html .= '<li><span class="bubble"></span>Not Screened<br>' . Date::toShort($status_dates[0]) . '</li>';
				$html .= '<li class="active"><span class="bubble"></span>Screened<br>' . Date::toShort($status_dates[1]) . '</li>';
				$html .= '<li><span class="bubble"></span>Telephone Interviewed</li><li><span class="bubble"></span>CV Sent</li><li><span class="bubble"></span>Interview Successful</li><li><span class="bubble"></span>Interview Unsuccessful</li><li><span class="bubble"></span>Sunesis Learner</li>';
				break;
			case 2:
				$html .= '<li><span class="bubble"></span>Not Screened<br>' . Date::toShort($status_dates[0]) . '</li>';
				$html .= '<li><span class="bubble"></span>Screened<br>' . Date::toShort($status_dates[1]) . '</li>';
				$html .= '<li class="active"><span class="bubble"></span>Telephone Interviewed<br>' . Date::toShort($status_dates[2]) . '</li>';
				$html .= '<li><span class="bubble"></span>CV Sent</li><li><span class="bubble"></span>Interview Successful</li><li><span class="bubble"></span>Interview Unsuccessful</li><li><span class="bubble"></span>Sunesis Learner</li>';
				break;
			case 3:
				$html .= '<li><span class="bubble"></span>Not Screened<br>' . Date::toShort($status_dates[0]) . '</li>';
				$html .= '<li><span class="bubble"></span>Screened<br>' . Date::toShort($status_dates[1]) . '</li>';
				$html .= '<li><span class="bubble"></span>Telephone Interviewed<br>' . Date::toShort($status_dates[2]) . '</li>';
				$html .= '<li class="active"><span class="bubble"></span>CV Sent<br>' . Date::toShort($status_dates[3]) . '</li>';
				$html .= '<li><span class="bubble"></span>Interview Successful</li><li><span class="bubble"></span>Interview Unsuccessful</li><li><span class="bubble"></span>Sunesis Learner</li>';
				break;
			case 4:
				$html .= '<li><span class="bubble"></span>Not Screened<br>' . Date::toShort($status_dates[0]) . '</li>';
				$html .= '<li><span class="bubble"></span>Screened<br>' . Date::toShort($status_dates[1]) . '</li>';
				$html .= '<li><span class="bubble"></span>Telephone Interviewed<br>' . Date::toShort($status_dates[2]) . '</li>';
				$html .= '<li><span class="bubble"></span>CV Sent<br>' . Date::toShort($status_dates[3]) . '</li>';
				$html .= '<li class="active"><span class="bubble"></span>Interview Successful<br>' . Date::toShort($status_dates[4]) . '</li>';
				$html .= '<li><span class="bubble"></span>Sunesis Learner</li>';
				break;
			case 5:
				$html .= '<li><span class="bubble"></span>Not Screened<br>' . Date::toShort($status_dates[0]) . '</li>';
				$html .= '<li><span class="bubble"></span>Screened<br>' . Date::toShort($status_dates[1]) . '</li>';
				$html .= '<li><span class="bubble"></span>Telephone Interviewed<br>' . Date::toShort($status_dates[2]) . '</li>';
				$html .= '<li><span class="bubble"></span>CV Sent<br>' . Date::toShort($status_dates[3]) . '</li>';
				$html .= '<li class="active"><span class="bubble"></span>Interview Unsuccessful<br>' . Date::toShort($status_dates[5]) . '</li>';
				$html .= '<li><span class="bubble"></span>Sunesis Learner</li>';
				break;
			case 6:
				$html .= '<li><span class="bubble"></span>Not Screened<br>' . Date::toShort($status_dates[0]) . '</li>';
				$html .= '<li><span class="bubble"></span>Screened<br>' . Date::toShort($status_dates[1]) . '</li>';
				$html .= '<li><span class="bubble"></span>Telephone Interviewed<br>' . Date::toShort($status_dates[2]) . '</li>';
				$html .= '<li><span class="bubble"></span>CV Sent<br>' . Date::toShort($status_dates[3]) . '</li>';
				$html .= '<li><span class="bubble"></span>Interview Successful<br>' . Date::toShort($status_dates[4]) . '</li>';
				$html .= '<li class="active"><span class="bubble"></span>Sunesis Learner<br>' . Date::toShort($status_dates[6]) . '</li>';
				break;
			case 99:
				$html .= '<li><span class="bubble"></span>Not Screened<br>' . Date::toShort($status_dates[0]) . '</li>';
				$html .= '<li><span class="bubble"></span>Screened<br>' . Date::toShort($status_dates[1]) . '</li>';
				$html .= '<li><span class="bubble"></span>Telephone Interviewed<br>' . Date::toShort($status_dates[2]) . '</li>';
				$html .= '<li><span class="bubble"></span>CV Sent<br>' . Date::toShort($status_dates[3]) . '</li>';
				$html .= '<li><span class="bubble"></span>Interview Successful<br>' . Date::toShort($status_dates[4]) . '</li>';
				$html .= '<li><span class="bubble"></span>Interview UnSuccessful<br>' . Date::toShort($status_dates[5]) . '</li>';
				$html .= '<li class="active"><span class="bubble"></span>Rejected<br>' . Date::toShort($status_dates[99]) . '</li>';
				break;
			default:
				$html = '';
				break;
		}
		$html .= '</ul>';
		return $html;
		/*		$counter = 0;
		for($i = $counter; $i <= 5; $i++)
		{
			$latest_status_update_date = DAO::getSingleValue($link, "SELECT created FROM candidate_application_status WHERE application_id = '{$application->id}' AND status = '{$i}' ORDER BY created DESC LIMIT 1");
			if($latest_status_update_date != '')
			{
				if($i == (int)$application->current_status)
				{
					$counter++;
					$html .= '<li class="active"><span class="bubble"></span>' . RecCandidateApplication::getStatusDesc($i) . '<br>(' . Date::toShort($latest_status_update_date) . ')</li>';
				}
				elseif($i < (int)$application->current_status)
				{
					$counter++;
					$html .= '<li class="completed"><span class="bubble"></span>' . RecCandidateApplication::getStatusDesc($i) . '<br>(' . Date::toShort($latest_status_update_date) . ')</li>';
				}
			}
		}
		if($counter != 5)
		{
			//$counter--;
			for($ii = $counter; $ii<=5;$ii++)
			{
				$html .= '<li><span class="bubble"></span>' . RecCandidateApplication::getStatusDesc($ii) . '</li>';
			}
		}*/

	}

	private function renderApplicationHistory(PDO $link, RecCandidateApplication $application)
	{
		$html = "";
		if($_SESSION['user']->isAdmin())
			$history = DAO::getResultset($link, "SELECT * FROM candidate_application_status WHERE application_id = '{$application->id}' ORDER BY created ", DAO::FETCH_ASSOC);
		else
			$history = DAO::getResultset($link, "SELECT * FROM candidate_application_status WHERE application_id = '{$application->id}' AND status = '3' ORDER BY created ", DAO::FETCH_ASSOC);
		foreach($history AS $h)
		{
			$html .= '<li><p style="line-height: 20px;letter-spacing: 1px;">';
			$html .= '<em><span style="border: 1px; border-radius: 5px;padding: 4px; background-color:#21ba45!important;color:#ffffff !important">On <span style="display:inline-block;vertical-align:center;font-weight:bold;margin-left:1em;">' . Date::to($h['created'], Date::DATETIME) . '</span></span> </em>';
			$html .= '<em><strong>Status: </strong>' . RecCandidateApplication::getStatusDesc($h['status']) . ' </em>';
			$html .= '<em><strong>Comments: </strong>' . $h['comments'] . ' </em>';
			$html .= '<em><strong>By: </strong>' . DAO::getSingleValue($link, "SELECT CONCAT(firstnames, ' ', surname) FROM users WHERE id = '" . $h['created_by'] . "'") . ' </em>';
			$html .= '</p></li>';
		}
		return $html;
	}

	private function renderApplicationScreeningHistory(PDO $link, RecCandidateApplication $application)
	{

		if($_SESSION['user']->type == User::TYPE_STORE_MANAGER)
		{
			$html = "<table cellpadding='6' border='0' class='resultset'>";
			$html .= '<tr><th>Screening Score</th><th>Comments</th></tr>';
			$sql = <<<SQL
SELECT
	comments
FROM
	candidate_application_status
WHERE
	application_id = '$application->id'
	AND status = '2'
SQL;

			$html .= '<tr><td><span style="font-size: 500%;">' . $application->screening_score . '</span></td><td style="line-height: 175%;">' . htmlspecialchars((string) DAO::getSingleValue($link, $sql)) . '</td></tr>';
			$html .= '</table>';
			return $html;
		}

		$sql = <<<SQL
SELECT
	rec_questions.`description`,
	candidate_application_screening.`answer`,
	candidate_application_screening.`score`
FROM
	candidate_application_screening INNER JOIN rec_questions ON candidate_application_screening.`question_id` = rec_questions.`id`
WHERE
	candidate_application_screening.`application_id` = '$application->id'
;
SQL;

		$html = "<table cellpadding='6' border='0'>";
		$history = DAO::getResultset($link, $sql, DAO::FETCH_ASSOC);
		if(count($history) > 0)
		{
			foreach($history AS $h)
			{
				$html .= '<tr><td><strong>' . $h['description'] . '</strong></td></tr>';
				if(is_null($h['answer']))
					$html .= '<tr><td>No answer entered</td></tr>';
				else
					$html .= '<tr><td>' . $h['answer'] . '</td></tr>';
				$html .= '<tr><td><hr></td></tr>';
			}
		}
		else
		{
			$html .= '<tr><td>No records found.</td> </tr>';
		}
		$html .= '</table>';
		return $html;
	}

	private function renderApplicationTelephoneInterviewHistory(PDO $link, RecCandidateApplication $application)
	{

		if($_SESSION['user']->type == User::TYPE_STORE_MANAGER)
		{
			$html = "<table cellpadding='6' border='0' class='resultset'>";
			$html .= '<tr><th>Score</th><th>Comments</th></tr>';
			$sql = <<<SQL
SELECT
	comments
FROM
	candidate_application_status
WHERE
	application_id = '$application->id'
	AND status = '2'
SQL;

			$html .= '<tr><td><span style="font-size: 500%;">' . $application->telephone_interview_score . '</span></td><td style="line-height: 175%;">' . htmlspecialchars((string) DAO::getSingleValue($link, $sql)) . '</td></tr>';
			$html .= '</table>';
			return $html;
		}

		$sql = <<<SQL
SELECT
	rec_interview_questions.`id`,
	rec_interview_questions.`description`,
	candidate_application_interview_screening.`answer`,
	candidate_application_interview_screening.`score`
FROM
	candidate_application_interview_screening INNER JOIN rec_interview_questions ON candidate_application_interview_screening.`question_id` = rec_interview_questions.`id`
WHERE
	candidate_application_interview_screening.`application_id` = '$application->id'
;
SQL;

		$html = "<table cellpadding='6' border='0'>";
		$history = DAO::getResultset($link, $sql, DAO::FETCH_ASSOC);
		if(count($history) > 0)
		{
			foreach($history AS $h)
			{
				$h['description'] = str_replace('$$STORE_NAME$$', $application->vacancy->getEmployerName($link), $h['description']);
				$h['description'] = str_replace('$$STORE_LOCATION$$', $application->vacancy->getLocation($link), $h['description']);
				$html .= '<tr><td><strong>' . $h['description'] . '</strong></td></tr>';
				if(is_null($h['answer']))
					$html .= '<tr><td>No answer entered</td></tr>';
				else
					$html .= '<tr><td>' . $h['answer'] . '</td></tr>';
				if($h['id'] >= 12 && $h['id'] <= 18)
					$html .= '<tr><td>Score: ' . $h['score'] . '</strong></td></tr>';
				$html .= '<tr><td><hr></td></tr>';
			}
		}
		else
		{
			$html .= '<tr><td>No records found.</td> </tr>';
		}
		$html .= '</table>';
		return $html;
	}

	private function getApplicationDecideLaterComments(PDO $link, RecCandidateApplication $application)
	{
		if($_SESSION['user']->isAdmin())
			return '';

		$created_by = " (created_by = '" . $_SESSION['user']->id . "' OR created_by IN (SELECT users.id FROM users WHERE users.type = 23 AND users.employer_id = '" . $_SESSION['user']->employer_id . "'))";
		$comments = "";
		$sql = <<<SQL
SELECT
	comments
FROM
	candidate_application_status
WHERE
	application_id = '$application->id'
	AND
	status = '2'
	AND $created_by
;
SQL;
		return DAO::getSingleValue($link, $sql);
	}

	private function getAssessorEmailText(PDO $link)
	{
		$assessor_id = isset($_REQUEST['assessor_id'])?$_REQUEST['assessor_id']:'';
		$application_id = isset($_REQUEST['application_id'])?$_REQUEST['application_id']:'';
		if($assessor_id == '' || $application_id == '')
			throw new Exception('Missing querystring arguments: assessor_id, application_id');

		$assessor = User::loadFromDatabaseById($link, $assessor_id);
		if(is_null($assessor))
			throw new Exception('Assessor record not found');

		$application = RecCandidateApplication::loadFromDatabaseByID($link, $application_id);
		if(is_null($application))
			throw new Exception('Application record not found');

		$assessor_org = DAO::getSingleValue($link, "SELECT REPLACE(legal_name, '&', 'and') FROM organisations WHERE id = '{$assessor->employer_id}'");

		$email_contents = '';
		$email_contents .= '<p>Dear <u>' . $assessor->firstnames . ' ' . $assessor->surname . '/' . $assessor_org . ',</u></p>';
		$email_contents .= '<p>We have filled the <span style="text-decoration: underline;">' . $application->vacancy->vacancy_title . '</span> in <u>' . $application->vacancy->getLocation($link) . '</u>.</p>';
		$email_contents .= '<p>The candidate has achieved the following grades for Maths and English.</p>';
		$email_contents .= '<p>Maths - <span style="text-decoration: underline;">' . $application->candidate->getGCSEMathsDetails($link)->qualification_grade . '</span></p>';
		$email_contents .= '<p>English - <span style="text-decoration: underline;">' . $application->candidate->getGCSEEnglishDetails($link)->qualification_grade . '</span></p>';
		$email_contents .= '<p>This has/has not been confirmed on LRS.</p>';
		$email_contents .= '<p>The candidate\'s date of birth is <span style="text-decoration: underline;">' . Date::toShort($application->candidate->dob) . '.</span></p>';
		$email_contents .= '<p>Please contact the Store on <span style="text-decoration: underline;">' . $application->vacancy->getLocationTelephone($link) . '</span> to arrange a suitable start date and reply back with the date agreed.</p>';
		$email_contents .= '<p>Many Thanks,</p>';
		$email_contents .= '<p>Apprenticeship Recruitment Team</p>';

		if(DB_NAME == "am_superdrug")
			$email_contents .= '<p><img title="Superdrug" src="https://superdrug.sunesis.uk.net/images/logos/superdrug.bmp" alt="Superdrug" style="width: 100px;" /></p>';
		elseif(DB_NAME == "am_sd_demo")
			$email_contents .= '<p><img title="Superdrug" src="https://sd-demo.sunesis.uk.net/images/logos/superdrug.bmp" alt="Superdrug" style="width: 100px;" /></p>';
		else
			$email_contents .= '<p><img title="' . $this->client_name . '" src="' . $this->client_logo_url . '" alt="' . $this->client_name . '" style="width: 100px;" /></p>';

		unset($assessor);
		unset($application);

		return $email_contents;
	}

	private function render_candidate_employment (PDO $link, RecCandidate $candidate)
	{
		$return_html = '';
		$return_html .= '<table id="tbl_employment" class="resultset" border="0" cellspacing="0" cellpadding="4" width="380px;">';
		$return_html .= '<thead><th>Company Name</th><th>Job Title</th><th>Start Date</th><th>End Date</th><th>Skills</th></thead>';
		if ( sizeof($candidate->employments) > 0 )
		{
			foreach ( $candidate->employments AS $edu_pos => $edu_row )
			{
				$return_html .= '<tr>';
				$return_html .= '<td>'.$edu_row['company_name'].'</td><td>'.$edu_row['job_title'].'</td><td>'.Date::toShort($edu_row['start_date']).'</td><td>'.Date::toShort($edu_row['end_date']).'</td><td style="font-size:smaller;">'.nl2br($edu_row['skills'] ?: '').'</td>';
				$return_html .= '</tr>';
			}
		}
		else
		{
			$return_html .= '<tr><td colspan="5">No records found.</td></tr>';
		}
		$return_html .= '</table>';
		return $return_html;
	}

	private function renderCandidateApplications(PDO $link, RecCandidateApplication $application)
	{
		$return_html = '';
		$return_html .= '<table class="resultset" border="0" cellspacing="0" cellpadding="4" style="font-size: smaller;">';
		$return_html .= '<thead><th>Reference</th><th>Title</th><th>Status</th><th>RAG</th><th>T.Int. Score</th></thead>';
		$applications = DAO::getSingleColumn($link, "SELECT id FROM candidate_applications WHERE candidate_id = '{$application->candidate_id}' AND vacancy_id != '{$application->vacancy_id}'");
		if(count($applications) == 0)
		{
			$return_html .= '<tr><td colspan="7">No records found.</td></tr>';
		}
		else
		{
			foreach($applications AS $app)
			{
				$objApplication = RecCandidateApplication::loadFromDatabaseByID($link, $app);
				if(!is_null($objApplication->screening_rag) && $objApplication->screening_rag != '')
				{
					if($objApplication->screening_rag == 'G')
						$return_html .= '<tr style="background-color: #E0EAD0;border-color: #E0EAD0;color: #000;">';
					elseif($objApplication->screening_rag == 'A')
						$return_html .= '<tr style="background-color: #FFE6D7;border-color: #FFE6D7;color: #000;">';
					elseif($objApplication->screening_rag == 'R')
						$return_html .= '<tr style="background-color: #FFBFBF;border-color: #FFBFBF;color: #000;">';
					else
						$return_html .= '<tr>';
				}
				else
					$return_html .= '<tr>';
				$return_html .= '<td align="center">' . $objApplication->vacancy->vacancy_reference . '</td>';
				$return_html .= '<td align="center">' . $objApplication->vacancy->vacancy_title . '</td>';
				$return_html .= '<td align="center">' . $objApplication->getCandidateApplicationCurrentStatusDesc($link) . '</td>';
				$return_html .= '<td align="center">' . $objApplication->screening_rag . '</td>';
				if(in_array($objApplication->current_status, array(0,1)))
				{
					$return_html .= '<td align="center"></td>';
				}
				else
				{
					if(is_null($objApplication->telephone_interview_score))
						$objApplication->telephone_interview_score = 0;
					$return_html .= '<td align="center">' . $objApplication->telephone_interview_score . '</td>';
				}
				$return_html .= '</tr>';
				unset($objApplication);
			}
		}
		$return_html .= '</table>';

		return $return_html;
	}

	private function renderCandidateLRSHistory(PDO $link, RecCandidateApplication $application)
	{
		$return_html = '<p>';
		$return_html .= '<span class="fieldLabel">Candidate ULN:</span> &nbsp; ';
		$return_html .= '<span class="fieldValue"><input type="text" id="l45" name="l45" value="'.$application->candidate->l45.'" size="10" maxlength="10"/></span>';
		$return_html .= '&nbsp; <span class="button" id="btnDownloadULN">Download ULN From LRS</span>';
		$return_html .= '</p>';

		$return_html .= '<h3>LRS Achievement Results</h3>';
		$return_html .= '<p><span class="button" id="btnGetLearnerLearningEvents">Get Achievement From LRS</span> </p>';
		$return_html .= '<table class="resultset" border="0" cellspacing="0" cellpadding="10">';
		$return_html .= '<thead><tr>';
		$return_html .= '<th></th><th>Level</th><th>Subject</th><th>Achievement Award Date</th>';
		$return_html .= '<th>Grade</th><th>Participation Start Date</th><th>Participation End Date</th>';
		$return_html .= '</tr></thead>';
		$records = DAO::getResultset($link, "SELECT * FROM lrs_learner_learning_events WHERE candidate_id = '{$application->candidate_id}' ORDER BY id", DAO::FETCH_ASSOC);
		if(count($records) == 0)
		{
			$return_html .= '<tr><td colspan="15">No records found.</td></tr>';
		}
		else
		{
			foreach($records AS $row)
			{
				$return_html .= '<tr>';
				$return_html .= '<td><img src="images/rosette.gif" alt=""></td>';
				$return_html .= '<td>' . $row['Level'] . '</td>';
				$return_html .= '<td>' . $row['Subject'] . '</td>';
				$return_html .= '<td>' . Date::toShort($row['AchievementAwardDate']) . '</td>';
				$return_html .= '<td>' . $row['Grade'] . '</td>';
				$return_html .= '<td>' . Date::toShort($row['ParticipationStartDate']) . '</td>';
				$return_html .= '<td>' . Date::toShort($row['ParticipationEndDate']) . '</td>';
				$return_html .= '</tr>';
			}
		}
		$return_html .= '</table>';

		return $return_html;
	}

	private function getLearnerLearningEvents(PDO $link)
	{
		$candidate_id = isset($_REQUEST['candidate_id'])?$_REQUEST['candidate_id']:'';
		$l45 = isset($_REQUEST['l45'])?$_REQUEST['l45']:'';

		if($candidate_id == '')
			return;
		$candidate = RecCandidate::loadFromDatabase($link, $candidate_id);
		if(is_null($candidate))
			return;

		$candidate->l45 = $l45;
		$candidate->save($link);

		include_once WEBROOT . './LRS/LRSAutoload.php';
/*
		$miap_password = 'P8rsp8ct1veP3rsp';
		$miap_username = 'TEST0001';
		$miap_ukprn = 'TEST0001';
		$miap_wsdl_local_cert = './LRS/cert.pem';
*/
		$miap_password = SystemConfig::getEntityValue($link, 'miap.soap.password');
		$miap_username = SystemConfig::getEntityValue($link, 'miap.soap.username');
		$miap_ukprn = SystemConfig::getEntityValue($link, 'miap.soap.ukprn');
		$miap_wsdl_local_cert = Repository::getRoot().'/MIAPCertificate/'.SystemConfig::getEntityValue($link, 'miap.soap.wsdl_local_cert');

		$connectionArray = array();
		$connectionArray['wsdl_local_cert'] = $miap_wsdl_local_cert;

		$lRSServiceGet = new LRSServiceGet($connectionArray);

		$invokingOrganisation = new LRSStructInvokingOrganisationR10();
		$invokingOrganisation->setUkprn($miap_ukprn);
		$invokingOrganisation->setPassword($miap_password);
		$invokingOrganisation->setUsername($_SESSION['user']->username);

		$lrs_gender = '';
		switch($candidate->gender)
		{
			case 'M':
				$lrs_gender = '1';
				break;
			case 'F':
				$lrs_gender = '2';
				break;
			case 'U':
				$lrs_gender = '0';
				break;
			case 'W':
				$lrs_gender = '9';
				break;
			default:
				$lrs_gender = '';
				break;
		}

		$learner = new LRSStructGetLearnerLearningEvents($invokingOrganisation, 'ORG', '01', 'en', $candidate->l45, $candidate->firstnames, $candidate->surname, Date::toMySQL($candidate->dob), $lrs_gender, 'FULL');
//if(SOURCE_BLYTHE_VALLEY) throw new Exception(json_encode($learner));
		if($lRSServiceGet->GetLearnerLearningEvents($learner))
		{
			DAO::transaction_start($link);
			try
			{
				DAO::execute($link, "DELETE FROM lrs_learner_learning_events WHERE candidate_id = '{$candidate_id}'");
				$result = $lRSServiceGet->getResult();
				$LRSStructGetLearnerLearningEventsResponse = $result->GetLearnerLearningEventsResult; /* @var $LRSStructGetLearnerLearningEventsResponse LRSStructGetLearnerLearningEventsResponse */

				$learnerRecord = $LRSStructGetLearnerLearningEventsResponse->getLearnerRecord(); /* @var $learnerRecord LRSStructArrayOfLearningEvent*/
				$events = $learnerRecord->getLearningEvent(); 
				if(count($events) > 0)
				{
					foreach($events AS $e) /* @var $e LRSStructLearningEvent */
					{
						$row = new stdClass();
						$row->candidate_id = $candidate_id;
						$row->AchievementAwardDate = $e->getAchievementAwardDate();
						$row->AchievementProviderName = $e->getAchievementProviderName();
						$row->AchievementProviderUkprn = $e->getAchievementProviderUkprn();
						$row->AwardingOrganisationName = $e->getAwardingOrganisationName();
						$row->Credits = $e->getCredits();
						$row->DateLoaded = $e->getDateLoaded();
						$row->Grade = $e->getGrade();
						$row->LanguageForAssessment = $e->getLanguageForAssessment();
						$row->Level = $e->getLevel();
						$row->QualificationType = $e->getQualificationType();
						$row->Source = $e->getSource();
						$row->Subject = $e->getSubject();
						$row->SubjectCode = $e->getSubjectCode();
						if($e->getParticipationEndDate() != '')
							$row->ParticipationEndDate = $e->getParticipationEndDate();
						if($e->getParticipationStartDate() != '')
							$row->ParticipationStartDate = $e->getParticipationStartDate();
						DAO::saveObjectToTable($link, 'lrs_learner_learning_events', $row);
					}
				}
				else
				{
					if($LRSStructGetLearnerLearningEventsResponse->getResponseCode() == "WSEC0206")
					{

						$msg = "No learning events returned for this learner.\r\n";
						$msg .= "LRS webservice Response Details:\r\n";
						$msg .= "Code: " . $LRSStructGetLearnerLearningEventsResponse->getResponseCode() . "\r\n";
						$msg .= "Detail: Learner has not opted to share data";
					}
					else
					{
						$msg = "No learning events returned for this learner.\r\n";
						$msg .= "LRS webservice Response Details:\r\n";
						$msg .= "Code: " . $LRSStructGetLearnerLearningEventsResponse->getResponseCode() . "\r\n";
						$msg .= "Detail: Learner could not be verified";
					}
					throw new Exception($msg);
				}
				DAO::transaction_commit($link);
			}
			catch(Exception $e)
			{
				DAO::transaction_rollback($link, $e);
				throw new WrappedException($e);
			}
		}
		else
		{
			echo json_encode($lRSServiceGet->getLastError());
		}
	}

	private function saveCandidateULN(PDO $link)
	{
		$candidate_id = isset($_REQUEST['candidate_id']) ? $_REQUEST['candidate_id'] : '';
		$l45 = isset($_REQUEST['l45']) ? $_REQUEST['l45'] : '';
		if($candidate_id == '' || $l45 == '')
			return;

		DAO::execute($link, "UPDATE candidate SET candidate.l45 = '{$l45}' WHERE id = '{$candidate_id}'");

	}
}
?>