<?php
class rec_view_vacancy_applications implements IAction
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
		$status = isset($_REQUEST['status'])?$_REQUEST['status']:'';
		$subaction = isset($_REQUEST['subaction'])?$_REQUEST['subaction']:'';

		if($_SESSION['user']->type == User::TYPE_STORE_MANAGER && $status != RecCandidateApplication::CV_SENT && $status != RecCandidateApplication::INTERVIEW_SUCCESSFUL && $status != RecCandidateApplication::INTERVIEW_UNSUCCESSFUL && $status != RecCandidateApplication::SUNESIS_LEARNER)
			throw new UnauthorizedException();

		if($subaction == 'update_application_status_to_cv_sent')
		{
			$this->update_application_status_to_cv_sent($link);
			exit;
		}

		if($subaction == 'update_application_status_to_rejected')
		{
			$this->update_application_status_to_rejected($link);
			exit;
		}

		if($subaction == 'moveBackApplication')
		{
			$this->moveBackApplication($link);
			exit;
		}

		if($subaction == 'getCVSentEmailText')
		{
			echo $this->getCVSentEmailText($link);
			exit;
		}

		$vacancy = RecVacancy::loadFromDatabase($link, $id);
		$status_desc = RecCandidateApplication::getStatusDesc($status);

		$_SESSION['bc']->add($link, "do.php?_action=rec_view_vacancy_applications&id=" . $id . '&status=' . $status, "Vacancy Applications");

		$view = ViewVacancyApplications::getInstance($vacancy->id, $status);
		$view->refresh($link, $_REQUEST);

		$vacancy_location = DAO::getSingleValue($link, "SELECT CONCAT(COALESCE(full_name), ' (',COALESCE(`address_line_1`,''),' ',COALESCE(`address_line_2`,''),', ', COALESCE(`postcode`,''), ')') AS location FROM locations WHERE id = '$vacancy->location_id'");

		$top_message = '';
		if($vacancy->is_active == 0)
			$top_message = 'THE STATUS OF THIS VACANCY IS NOT ACTIVE';
		$d1 = new Date($vacancy->closing_date);
		$today = new Date(date('Y-m-d'));
		if($d1->before($today))
			$top_message .= 'THE VACANCY IS CLOSED - CLOSING DATE IS PASSED';
		$number_of_sunesis_learnersIn_this_application = (int)DAO::getSingleValue($link, "SELECT COUNT(*) FROM candidate_applications WHERE vacancy_id = '" . $vacancy->id . "' AND current_status = '" . RecCandidateApplication::SUNESIS_LEARNER . "'");
		if($number_of_sunesis_learnersIn_this_application >= (int)$vacancy->no_of_positions)
			$top_message .= $top_message == ''?'THE VACANCY IS FULL (TOTAL POSITIONS = ' . $vacancy->no_of_positions . ' AND SUCCESSFUL APPLICATIONS = ' . $number_of_sunesis_learnersIn_this_application . ')':'. THE VACANCY IS FULL (TOTAL POSITIONS = ' . $vacancy->no_of_positions . ' AND SUCCESSFUL APPLICATIONS = ' . $number_of_sunesis_learnersIn_this_application . ')';

		if(DB_NAME == "am_superdrug")
		{
			$this->contact_name = 'Lisa Taylor';
			$this->contact_telephone = '01977 657031';
			$this->client_name = 'Superdrug';
			$this->client_logo_url = 'https://superdrug.sunesis.uk.net/images/logos/superdrug.bmp';
			$this->client_logo = 'superdrug.bmp';
			$this->client_auto_email_subject = 'Superdrug Application Update';
		}

		require_once('tpl_rec_view_vacancy_applications.php');
	}

	private function moveBackApplication(PDO $link)
	{
		$application_id = isset($_REQUEST['application_id'])?$_REQUEST['application_id']:'';

		if($application_id == '')
			return;

		$objApplication = RecCandidateApplication::loadFromDatabaseByID($link, $application_id);
		if(is_null($objApplication))
			return;

		$sunesis_learner_withdrawn = false;
		$current_status = RecCandidateApplication::getStatusDesc($objApplication->current_status);
		if($objApplication->current_status == RecCandidateApplication::SCREENED)
			$objApplication->current_status = RecCandidateApplication::CREATED;
		elseif($objApplication->current_status == RecCandidateApplication::TELEPHONE_INTERVIEWED)
			$objApplication->current_status = RecCandidateApplication::SCREENED;
		elseif($objApplication->current_status == RecCandidateApplication::CV_SENT)
			$objApplication->current_status = RecCandidateApplication::TELEPHONE_INTERVIEWED;
		elseif($objApplication->current_status == RecCandidateApplication::INTERVIEW_SUCCESSFUL)
			$objApplication->current_status = RecCandidateApplication::WITHDRAWN;
		elseif($objApplication->current_status == RecCandidateApplication::REJECTED)
			$objApplication->current_status = RecCandidateApplication::SCREENED;
		elseif($objApplication->current_status == RecCandidateApplication::WITHDRAWN)
			$objApplication->current_status = RecCandidateApplication::SCREENED;
		elseif($objApplication->current_status == RecCandidateApplication::SUNESIS_LEARNER)
		{
			$objApplication->current_status = RecCandidateApplication::WITHDRAWN;
			$sunesis_learner_withdrawn = true;
		}

		$new_status = RecCandidateApplication::getStatusDesc($objApplication->current_status);
		DAO::transaction_start($link);
		try
		{
			$objApplication->save($link);
			$objApplication->saveCandidateApplicationStatus($link, $objApplication->current_status, "[--- Auto: Candidate application is moved back from '{$current_status}' to '{$new_status}' ---]");
			if($sunesis_learner_withdrawn)
			{
				$objApplication->candidate->enrolled = '';
				$objApplication->candidate->username = '';
				$objApplication->candidate->save($link);
			}
			$objApplication->candidate->saveCandidateNotes($link, 'Candidate application is moved back to "' . $objApplication->getCandidateApplicationCurrentStatusDesc($link) . '". [Vacancy Ref: "' . $objApplication->vacancy->vacancy_reference . '", Vacancy Title: "' . $objApplication->vacancy->vacancy_title . '"]');
			DAO::transaction_commit($link);
		}
		catch(Exception $e)
		{
			DAO::transaction_rollback($link, $e);
			throw new WrappedException($e);
		}
		unset($objApplication);
	}

	private function update_application_status_to_cv_sent(PDO $link)
	{
		$selected_application_ids = isset($_REQUEST['selected_application_ids'])?$_REQUEST['selected_application_ids']:'';
		$comments = isset($_REQUEST['comments'])?$_REQUEST['comments']:'';
		$send_email = isset($_REQUEST['send_email'])?$_REQUEST['send_email']:'';
		$email_contents = isset($_REQUEST['email_contents'])?$_REQUEST['email_contents']:'';
		if($selected_application_ids == '')
			throw new Exception('No application ids provided');

		$candidate_rows = "";
		$vacancy = "";
		$selected_application_ids = explode(',', $selected_application_ids);
		foreach($selected_application_ids AS $application_id)
		{
			$objApplication = RecCandidateApplication::loadFromDatabaseByID($link, $application_id);
			if(is_null($objApplication))
				continue;

			$objApplication->current_status = RecCandidateApplication::CV_SENT;
			$objApplication->save($link);

			$objApplication->saveCandidateApplicationStatus($link, RecCandidateApplication::CV_SENT, $comments);

			$objApplication->candidate->saveCandidateNotes($link, 'Candidate application is set as "CV Sent". [Vacancy Ref: "' . $objApplication->vacancy->vacancy_reference . '", Vacancy Title: "' . $objApplication->vacancy->vacancy_title . '"]');

			$candidate_rows = '<tr>';
			$candidate_rows .= '<td>' . $objApplication->candidate->firstnames . ' ' . $objApplication->candidate->surname . '</td>';
			$candidate_rows .= '<td>' . Date::toShort($objApplication->candidate->dob) . '</td>';
			$candidate_rows .= '<td>' . $objApplication->candidate->address1 . ' ' . $objApplication->candidate->address2 . ' ' . $objApplication->candidate->borough . ' ' . $objApplication->candidate->county . ' (' . $objApplication->candidate->postcode . ')</td>';
			$candidate_rows .= '</tr>';
			$vacancy = $objApplication->vacancy; /* @var $vacancy RecVacancy */

			unset($objApplication);
		}

		//$store_manager_name = DAO::getSingleValue($link, "SELECT CONCAT(firstnames, ' ', surname) FROM users WHERE users.type = '" . User::TYPE_STORE_MANAGER . "' AND users.employer_id = '" . $vacancy->employer_id . "' AND users.web_access = '1' LIMIT 0,1 ");
		$store_manager_email = DAO::getSingleValue($link, "SELECT work_email FROM users WHERE users.type = '" . User::TYPE_STORE_MANAGER . "' AND users.employer_id = '" . $vacancy->employer_id . "' AND users.web_access = '1' LIMIT 0,1 ");

		if($store_manager_email != '' && $send_email == '1' && $email_contents != '')
		{
			Emailer::notification_email($store_manager_email, 'apprenticeships@perspective-uk.com', SystemConfig::getEntityValue($link, 'rec_v2_email'), $vacancy->vacancy_title . ' - Shortlisted Candidates', '', $email_contents, array(), array('X-Mailer: PHP/' . phpversion()));
			unset($email_contents);
		}
	}

	private function update_application_status_to_rejected(PDO $link)
	{
		$selected_application_ids = isset($_REQUEST['selected_application_ids'])?$_REQUEST['selected_application_ids']:'';
		$comments = isset($_REQUEST['comments'])?$_REQUEST['comments']:'';
		if($selected_application_ids == '')
			throw new Exception('No application ids provided');

		$selected_application_ids = explode(',', $selected_application_ids);
		DAO::transaction_start($link);
		try
		{
			foreach($selected_application_ids AS $application_id)
			{
				$objApplication = RecCandidateApplication::loadFromDatabaseByID($link, $application_id);
				if(is_null($objApplication))
					continue;

				$objApplication->current_status = RecCandidateApplication::REJECTED;
				$objApplication->save($link);

				$objApplication->saveCandidateApplicationStatus($link, RecCandidateApplication::REJECTED, $comments);

				$objApplication->candidate->saveCandidateNotes($link, 'Candidate application is set as "Rejected". [Vacancy Ref: "' . $objApplication->vacancy->vacancy_reference . '", Vacancy Title: "' . $objApplication->vacancy->vacancy_title . '"]');

				$email_body = DAO::getSingleValue($link, "SELECT template FROM lookup_candidate_email_templates WHERE template_type = 'REJECT_BEFORE_TELEPHONE_INTERVIEW'");
				$email_body = str_replace('$$CANDIDATE_NAME$$', $objApplication->candidate->firstnames . ' ' . $objApplication->candidate->surname, $email_body);
				$email_body = str_replace('$$VACANCY_TITLE$$', $objApplication->vacancy->vacancy_title, $email_body);
				$email_body = str_replace('$$STORE_LOCATION$$', $objApplication->vacancy->getLocation($link), $email_body);
				$email_body = str_replace('$$LOGO$$', '<img title="' . $this->contact_name . '" src="' . $this->client_logo_url . '" alt="' . $this->client_name . '" style="width: 100px;" />', $email_body);

				if(trim($objApplication->candidate->email) != '')
				{
					if(Emailer::html_mail($objApplication->candidate->email, SystemConfig::getEntityValue($link, 'rec_v2_email'), $this->client_auto_email_subject, '', $email_body, array(), array('X-Mailer: PHP/' . phpversion())))
					{
						$objEmail = new stdClass();
						$objEmail->candidate_id = $objApplication->candidate->id;
						$objEmail->subject = $this->client_auto_email_subject;
						$objEmail->candidate_email = $objApplication->candidate->email;
						$objEmail->email_body = $email_body;
						$objEmail->by_whom = $_SESSION['user']->id;
						DAO::saveObjectToTable($link, 'candidate_emails', $objEmail);
						unset($objEmail);
					}
				}

				unset($objApplication);
			}
			DAO::transaction_commit($link);
		}
		catch(Exception $e)
		{
			DAO::transaction_rollback($link, $e);
			throw new WrappedException($e);
		}
	}

	private function getCVSentEmailText(PDO $link)
	{
		$contact_name = 'Test Perspective';
		$contact_telephone = '0XXXX XXXXXX';
		if(DB_NAME == "am_superdrug")
		{
			$contact_name = 'Gemma Allman';
			$contact_telephone = '01977 657031';
		}
		$email_contents = <<<HTML
<p>Store Management Team,</p>
<p>Great news! We have shortlisted applications for your Apprentice vacancy in store, ready for you to interview. You can now view these on your iPad. For instructions on how to do this please see User guide on the intranet under Documents/Apprenticeships/Information.</p>
<p><strong>Stage 1 - Invite to interview</strong></p>
<p>All applicants have already taken part in a telephone interview, are eligible and are the strongest candidates. Contact <strong><u>ALL</u></strong> candidates by phone and invite to interview offering them a suitable time slot.</p>
<p><strong>PLEASE CONTACT <u>ALL</u> THE CANDIDATES WITHIN THE NEXT 72HRS</strong></p>
<p><strong>If you have not already done so please remove the window poster and store in a safe place.</strong></p>
<p><strong>Stage 2 - Interview</strong></p>
<p>Please complete the full interview process as per the flowchart on page 1 of the Apprentice Interview script found on the intranet under Documents/Apprenticeships/Interview documents.</p>
<p><strong>If there are comments in your candidates' notes on their application on the iPad stating "please complete initial assessments" then print these from the intranet and ensure the candidates complete these as part of their interview. </strong>You can find these under documents/apprenticeships/interview documents.</p>
<p><strong>THEY MUST NOT GET HELP WITH THE QUESTIONS OR USE A CALCULATOR.</strong></p>
<p><strong>Stage 3 - After Interview</strong></p>
<p><strong>Update all candidates' status on the iPad and contact all of them, informing of next steps or giving feedback to unsuccessful candidates.</strong></p>
<p><strong>For successful candidates(s) from stage 2 </strong>- Arrange a suitable time/date with the candidates for the Area Manager to <strong><u>meet</u></strong> them and conduct the final interview.</p>
<p><strong>Once the final stage with your Area Manager is complete, update the candidate status on the iPad and contact $contact_name on $contact_telephone <u>BEFORE</u> agreeing a start date.</strong></p>
<p>If you have any further questions at any point, please call $contact_name on $contact_telephone.</p>
<p>Kind Regards,</p>
<p>Apprenticeship Recruitment Team</p>

HTML;

		if(DB_NAME == 'am_superdrug')
			$email_contents .= '<p><img title="Superdrug" src="/images/logos/superdrug.bmp" alt="Superdrug" style="width: 100px;" /></p>';

		return $email_contents;
	}
}
?>