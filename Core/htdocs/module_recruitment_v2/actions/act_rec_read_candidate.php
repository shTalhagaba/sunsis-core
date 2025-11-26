<?php
class rec_read_candidate implements IAction
{
	public function execute(PDO $link)
	{
		$selected_tab = isset($_REQUEST['selected_tab'])?$_REQUEST['selected_tab']:'tab1';

		$id = isset($_REQUEST['id']) ? $_REQUEST['id'] : '';
		$subaction = isset($_REQUEST['subaction']) ? $_REQUEST['subaction'] : '';

		if($subaction == 'saveNewCRMNote')
		{
			echo $this->saveNewCRMNote($link);
			exit;
		}
		if($subaction == 'getCRMNoteDetail')
		{
			echo $this->getCRMNoteDetail($link);
			exit;
		}
		if($subaction == 'getInviteDetail')
		{
			echo $this->getInviteDetail($link);
			exit;
		}
		if($subaction == 'getEmailDetail')
		{
			echo $this->getEmailDetail($link);
			exit;
		}
		if($subaction == 'sendEmail')
		{
			echo $this->sendEmail($link);
			exit;
		}
		if($subaction == 'getCandidateInvites')
		{
			header('Content-Type: application/json');
			echo $this->getCandidateInvites($link);
			exit;
		}
		if($subaction == 'createCandidateInvite')
		{
			echo $this->createCandidateInvite($link);
			exit;
		}
		if($subaction == 'loadAndPrepareEmailTemplate')
		{
			echo $this->loadAndPrepareEmailTemplate($link);
			exit;
		}
		if($subaction == 'getLocationFromID')
		{
			echo $this->getLocationFromID($link);
			exit;
		}
		if($subaction == 'saveInvite')
		{
			echo $this->saveInvite($link);
			exit;
		}
		if($subaction == 'saveCandidateInviteToEmployerContact')
		{
			echo $this->saveCandidateInviteToEmployerContact($link);
			exit;
		}
		if($subaction == 'downloadCV')
		{
			$this->downloadCandidateCV();
			exit;
		}

		$candidate = RecCandidate::loadFromDatabase($link, $id);
		if(is_null($candidate))
			throw new Exception('Candidate record not found');

		$_SESSION['bc']->add($link, "do.php?_action=rec_read_candidate&id=" . $candidate->id . '&selected_tab=' . $selected_tab, "View Candidate");

		$photopath = $candidate->getPhotoPath();
		if($photopath)
		{
			$photopath = "do.php?_action=display_image&username=".rawurlencode($candidate->username)."&candidate_id=".$candidate->id;
		}
		else
		{
			$photopath = "/images/no_photo.png";
		}

		$crm_note_contact_type_ddl = DAO::getResultset($link, "SELECT id, description FROM lookup_crm_contact_type ORDER BY description");
		$crm_notes_order_by_ddl = array(
			array('0', 'Order By Date Created (Ascending)', ''),
			array('1', 'Order By Date Created (Descending)', '')
		);
		$candidate_email_templates = DAO::getResultset($link, "SELECT template_type, template_type, null FROM lookup_candidate_email_templates ORDER BY template_type");
		$candidate_invite_templates = DAO::getResultset($link, "SELECT template_type, template_type, null FROM lookup_candidate_email_templates WHERE template_type IN ('SHORTLIST_FOR_FACE_TO_FACE_INTERVIEW', 'SHORTLIST_FOR_TELEPHONE_INTERVIEW') ORDER BY template_type");
		$sql = <<<SQL
SELECT candidate_applications.`id`, vacancies.`vacancy_title`, NULL
FROM candidate_applications INNER JOIN vacancies ON candidate_applications.`vacancy_id` = vacancies.`id`
WHERE candidate_applications.`candidate_id` = '$candidate->id'
ORDER BY vacancies.vacancy_title
;
SQL;
		$application_ddl = DAO::getResultset($link, $sql);

		$tab1 = "";
		$tab2 = "";
		$tab3 = "";
		$tab4 = "";
		$tab5 = "";
		$tab6 = "";

		if(isset($$selected_tab))
			$$selected_tab = " class='selected' ";
		else
			$tab1 = " class='selected' ";

		$yes_no_options = array(
			array('No', 'No', ''),
			array('Yes', 'Yes', '')
		);

		$view_only = false;
		if(!is_null($candidate->username))
			$view_only = true;
		if(!$_SESSION['user']->isAdmin())
			$view_only = true;

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

		DAO::execute($link, "DELETE FROM candidate_qualification WHERE candidate_id = '{$candidate->id}' AND qualification_level IS NULL AND qualification_subject IS NULL AND qualification_grade IS NULL AND qualification_date IS NULL AND institution IS NULL");
		DAO::execute($link, "DELETE FROM candidate_history WHERE candidate_id = '{$candidate->id}' AND company_name IS NULL AND job_title IS NULL AND start_date IS NULL AND end_date IS NULL AND skills IS NULL");

		$client_name = 'Perspective';
		if(DB_NAME == "am_superdrug")
			$client_name = 'Superdrug';
		// Presentation
		//Detect special conditions devices
		$iPod    = stripos($_SERVER['HTTP_USER_AGENT'],"iPod");
		$iPhone  = stripos($_SERVER['HTTP_USER_AGENT'],"iPhone");
		$iPad    = stripos($_SERVER['HTTP_USER_AGENT'],"iPad");
		$Android = stripos($_SERVER['HTTP_USER_AGENT'],"Android");
		$webOS   = stripos($_SERVER['HTTP_USER_AGENT'],"webOS");
		if($iPod || $iPhone || $iPad || $Android || $webOS)
			include('tpl_rec_read_candidate_simple.php');
		else
			include('tpl_rec_read_candidate.php');
	}

	private function downloadCandidateCV()
	{
		$candidate_id = isset($_REQUEST['candidate_id'])?$_REQUEST['candidate_id']:'';
		if($candidate_id == '')
			return;

		$iPod    = stripos($_SERVER['HTTP_USER_AGENT'],"iPod");
		$iPhone  = stripos($_SERVER['HTTP_USER_AGENT'],"iPhone");
		$iPad    = stripos($_SERVER['HTTP_USER_AGENT'],"iPad");
		$Android = stripos($_SERVER['HTTP_USER_AGENT'],"Android");
		$webOS   = stripos($_SERVER['HTTP_USER_AGENT'],"webOS");
		if($iPod || $iPhone || $iPad || $Android || $webOS)
		{
			header("Pragma: public");
			header("Expires: 0");
			header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
			header("Cache-Control: private",false);
			if (file_exists(DATA_ROOT . "/uploads/" . DB_NAME . "/recruitment/cv_1_" . $candidate_id . ".pdf"))
			{
				header("Content-Type: application/pdf");
				readfile(DATA_ROOT . "/uploads/" . DB_NAME . "/recruitment/cv_1_" . $candidate_id . ".pdf");
			}
			elseif (file_exists(DATA_ROOT . "/uploads/" . DB_NAME . "/recruitment/cv_1_" . $candidate_id . ".docx"))
			{
				header("Content-Type: application/msword");
				readfile(DATA_ROOT . "/uploads/" . DB_NAME . "/recruitment/cv_1_" . $candidate_id . ".docx");
			}
			elseif (file_exists(DATA_ROOT . "/uploads/" . DB_NAME . "/recruitment/cv_1_" . $candidate_id . ".doc"))
			{
				header("Content-Type: application/msword");
				readfile(DATA_ROOT . "/uploads/" . DB_NAME . "/recruitment/cv_1_" . $candidate_id . ".doc");
			}
		}
		else
		{
			if (file_exists(DATA_ROOT . "/uploads/" . DB_NAME . "/recruitment/cv_1_" . $candidate_id . ".pdf"))
				http_redirect("do.php?_action=downloader&path=/recruitment/&f=cv_1_" . $candidate_id . ".pdf");
			elseif (file_exists(DATA_ROOT . "/uploads/" . DB_NAME . "/recruitment/cv_1_" . $candidate_id . ".docx"))
				http_redirect("do.php?_action=downloader&path=/recruitment/&f=cv_1_" . $candidate_id . ".docx");
			elseif (file_exists(DATA_ROOT . "/uploads/" . DB_NAME . "/recruitment/cv_1_" . $candidate_id . ".doc"))
				http_redirect("do.php?_action=downloader&path=/recruitment/&f=cv_1_" . $candidate_id . ".doc");
		}
	}

	private function saveCandidateInviteToEmployerContact(PDO $link)
	{
		$invite_id = isset($_REQUEST['invite_id'])?$_REQUEST['invite_id']:'';
		$selected_contact = isset($_REQUEST['selected_contact'])?$_REQUEST['selected_contact']:'';
		if($invite_id == '')
			return '';
		$objCandidateInvite = DAO::getObject($link, "SELECT * FROM calendar_event WHERE event_id = '{$invite_id}'");
		if(is_null($objCandidateInvite))
			return '';
		$objInvite = new stdClass();
		$objInvite->for_whom = $selected_contact;
		$objInvite->title = $objCandidateInvite->title;
		$objInvite->description = 'Interview arranged';
		$objInvite->datefrom = $objCandidateInvite->datefrom;
		$objInvite->datefromtime = $objCandidateInvite->datefromtime;
		$objInvite->dateto = $objCandidateInvite->dateto;
		$objInvite->datetotime = $objCandidateInvite->datetotime;
		$objInvite->allday = $objCandidateInvite->allday;
		$objInvite->location = $objCandidateInvite->location;
		$objInvite->by_whom = $_SESSION['user']->id;
		$objInvite->status = 'CONFIRMED';
		$objInvite->sequence_number = '1';
		$domain = 'exchangecore.com';
		$objInvite->event_uid = date("Ymd\TGis", strtotime($objInvite->datefromtime)).rand()."@".$domain."\r\n";;
		$objInvite->event_id = '';

		DAO::saveObjectToTable($link, 'calendar_event', $objInvite);
		return true;

	}

	private function saveInvite(PDO $link)
	{
		$objInvite = new stdClass();
		$objInvite->for_whom = isset($_REQUEST['candidate_id'])?$_REQUEST['candidate_id']:'';
		$objInvite->title = isset($_REQUEST['title'])?$_REQUEST['title']:'';
		$objInvite->description = isset($_REQUEST['description'])?$_REQUEST['description']:'';
		$objInvite->datefrom = isset($_REQUEST['datefrom'])?Date::toMySQL($_REQUEST['datefrom']):'';
		$objInvite->datefromtime = isset($_REQUEST['datefromtime'])?$_REQUEST['datefromtime']:'';
		$objInvite->datetotime = isset($_REQUEST['datetotime'])?$_REQUEST['datetotime']:'';
		$objInvite->dateto = isset($_REQUEST['dateto'])?Date::toMySQL($_REQUEST['dateto']):'';
		$objInvite->allday = isset($_REQUEST['allday'])?$_REQUEST['allday']:'';
		$objInvite->location = isset($_REQUEST['location'])?$_REQUEST['location']:'';
		$objInvite->by_whom = $_SESSION['user']->id;
		if(isset($_REQUEST['mode']) && ($_REQUEST['mode'] == 'add' || $_REQUEST['mode'] == 'update'))
			$objInvite->status = 'CONFIRMED';
		if(isset($_REQUEST['mode']) && $_REQUEST['mode'] == 'cancel')
			$objInvite->status = 'CANCELLED';
		if(isset($_REQUEST['sequence_number']) && $_REQUEST['sequence_number'] == '')
			$objInvite->sequence_number = '1';
		if(isset($_REQUEST['sequence_number']) && $_REQUEST['sequence_number'] != '')
			$objInvite->sequence_number = (int)$_REQUEST['sequence_number'] + 1;
		$domain = 'exchangecore.com';
		if(isset($_REQUEST['event_uid']) && $_REQUEST['event_uid'] == '')
			$objInvite->event_uid = date("Ymd\TGis", strtotime($objInvite->datefromtime)).rand()."@".$domain."\r\n";;
		if(isset($_REQUEST['event_uid']) && $_REQUEST['event_uid'] != '')
			$objInvite->event_uid = $_REQUEST['event_uid'];
		$objInvite->event_id = isset($_REQUEST['event_id'])?$_REQUEST['event_id']:'';

		DAO::saveObjectToTable($link, 'calendar_event', $objInvite);
		return true;
	}

	private function getLocationFromID(PDO $link)
	{
		$application_id = isset($_REQUEST['application_id'])?$_REQUEST['application_id']:'';
		if($application_id == '')
			return '';
		$application = RecCandidateApplication::loadFromDatabaseByID($link, $application_id);
		if(is_null($application))
			return '';
		return DAO::getSingleValue($link, "SELECT CONCAT(COALESCE(full_name),' (',COALESCE(`address_line_1`, ''),' ',COALESCE(`address_line_2`, ''),' ,',COALESCE(`postcode`, ''),')') FROM locations WHERE locations.`id` = '{$application->vacancy->location_id}'");
	}

	private function loadAndPrepareEmailTemplate(PDO $link)
	{
		$candidate_id = isset($_REQUEST['candidate_id'])?$_REQUEST['candidate_id']:'';
		$application_id_for_email = isset($_REQUEST['application_id_for_email'])?$_REQUEST['application_id_for_email']:'';
		$template_type = isset($_REQUEST['template_type'])?$_REQUEST['template_type']:'';
		if($candidate_id == '' || $application_id_for_email == '' || $template_type == '')
			throw new Exception('Cannot load email template, missing querystring: candidate_id, application_id or template type');

		$candidate = RecCandidate::loadFromDatabase($link, $candidate_id);
		if(is_null($candidate))
			throw new Exception('Candidate record not found');
		$application = RecCandidateApplication::loadFromDatabaseByID($link, $application_id_for_email);
		if(is_null($application))
			throw new Exception('Candidate application not found');
		$vacancy = RecVacancy::loadFromDatabase($link, $application->vacancy_id);
		if(is_null($vacancy))
			throw new Exception('Vacancy record not found');
		$vacancy_location = Location::loadFromDatabase($link, $vacancy->location_id);
		$template = DAO::getSingleValue($link, "SELECT template FROM lookup_candidate_email_templates WHERE template_type = '{$template_type}'");
		$template = str_replace('$$CANDIDATE_NAME$$', $candidate->firstnames . ' ' . $candidate->surname, $template);
		$template = str_replace('$$VACANCY_TITLE$$', $vacancy->vacancy_title, $template);
		$template = str_replace('$$STORE_LOCATION$$', $vacancy_location->address_line_1 . ', ' . $vacancy_location->address_line_2, $template);
		$template = str_replace('$$STORE_TELEPHONE$$', $vacancy_location->telephone, $template);
		if(DB_NAME == "am_superdrug")
			$template = str_replace('$$LOGO$$', '<img title="Superdrug" src="/images/logos/superdrug.bmp" alt="Superdrug" style="width: 100px;" />', $template);
		else
			$template = str_replace('$$LOGO$$', '<img title="Perspective" src="/images/logos/SUNlogo.jpg" alt="Perspective" style="width: 100px;" />', $template);
		unset($candidate);
		unset($application);
		unset($vacancy);
		return $template;
	}

	private function sendEmail(PDO $link)
	{
		$objEmail = new stdClass();
		$objEmail->candidate_id = isset($_REQUEST['candidate_id'])?$_REQUEST['candidate_id']:'';
		$objEmail->subject = isset($_REQUEST['subject'])?$_REQUEST['subject']:'';
		$objEmail->candidate_email = isset($_REQUEST['candidate_email'])?$_REQUEST['candidate_email']:'';
		$sender_email = isset($_REQUEST['sender_email'])?$_REQUEST['sender_email']:SystemConfig::getEntityValue($link, 'rec_v2_email');
		$objEmail->email_body = isset($_REQUEST['email_contents'])?$_REQUEST['email_contents']:'';
		$objEmail->by_whom = $_SESSION['user']->id;
		if(DB_NAME == "am_superdrug")
			$objEmail->email_body = str_replace('images/logos/superdrug.bmp', 'https://sd-demo.sunesis.uk.net/images/logos/superdrug.bmp', $objEmail->email_body);
		else
			$objEmail->email_body = str_replace('images/logos/SUNlogo.jpg', 'https://sd-demo.sunesis.uk.net/images/logos/SUNlogo.jpg', $objEmail->email_body);

		if(Emailer::html_mail($objEmail->candidate_email, $sender_email, $objEmail->subject, '', $objEmail->email_body, array(), array('X-Mailer: PHP/' . phpversion())))
		{
			return DAO::saveObjectToTable($link, 'candidate_emails', $objEmail);
		}
		else
		{
			throw new Exception('Email not sent.');
		}
	}

	private function getCRMNoteDetail(PDO $link)
	{
		$crm_note_id = isset($_REQUEST['crm_note_id'])?$_REQUEST['crm_note_id']:'';
		if($crm_note_id == '')
			throw new Exception('Missing querystring: crm_note_id');

		$note = DAO::getResultset($link, "SELECT * FROM candidate_crm_notes WHERE id = '{$crm_note_id}'", DAO::FETCH_ASSOC);
		if(count($note) > 0)
		{
			$note[0]['crm_date'] = !is_null($note[0]['crm_date'])?Date::toShort($note[0]['crm_date']):'';
			$note[0]['next_action_date'] = !is_null($note[0]['next_action_date'])?Date::toShort($note[0]['next_action_date']):'';
			return json_encode($note[0]);
		}
		else
			return '';
	}

	private function getInviteDetail(PDO $link)
	{
		$event_id = isset($_REQUEST['event_id'])?$_REQUEST['event_id']:'';
		if($event_id == '')
			throw new Exception('Missing querystring: event_id');

		$note = DAO::getResultset($link, "SELECT * FROM calendar_event WHERE event_id = '{$event_id}'", DAO::FETCH_ASSOC);
		if(count($note) > 0)
		{
			$note[0]['datefrom'] = !is_null($note[0]['datefrom'])?Date::toShort($note[0]['datefrom']):'';
			$note[0]['dateto'] = !is_null($note[0]['dateto'])?Date::toShort($note[0]['dateto']):'';
			return json_encode($note[0]);
		}
		else
			return '';
	}

	private function getEmailDetail(PDO $link)
	{
		$email_id = isset($_REQUEST['email_id'])?$_REQUEST['email_id']:'';
		if($email_id == '')
			throw new Exception('Missing querystring: email_id');

		$email = DAO::getResultset($link, "SELECT * FROM candidate_emails WHERE id = '{$email_id}'", DAO::FETCH_ASSOC);
		if(count($email) > 0)
		{
			$email[0]['created'] = !is_null($email[0]['created'])?Date::toShort($email[0]['created']):'';
			$email[0]['email_body'] = !is_null($email[0]['email_body'])?strip_tags($email[0]['email_body']):'';
			return json_encode($email[0]);
		}
		else
			return '';
	}

	private function saveNewCRMNote(PDO $link)
	{
		$objNewCRMNote = new stdClass();
		$objNewCRMNote->candidate_id = isset($_REQUEST['candidate_id'])?$_REQUEST['candidate_id']:'';
		$objNewCRMNote->type_of_contact = isset($_REQUEST['type_of_contact'])?$_REQUEST['type_of_contact']:'';
		$objNewCRMNote->subject = isset($_REQUEST['subject'])?$_REQUEST['subject']:'';
		$objNewCRMNote->crm_date = isset($_REQUEST['crm_date'])?$_REQUEST['crm_date']:'';
		$objNewCRMNote->next_action_date = isset($_REQUEST['next_action_date'])?$_REQUEST['next_action_date']:'';
		$objNewCRMNote->agreed_action = isset($_REQUEST['agreed_action'])?$_REQUEST['agreed_action']:'';
		$objNewCRMNote->other_notes = isset($_REQUEST['other_notes'])?$_REQUEST['other_notes']:'';
		$objNewCRMNote->created_by = $_SESSION['user']->id;
		$objNewCRMNote->id = isset($_REQUEST['crm_note_id'])?$_REQUEST['crm_note_id']:null;
		$objNewCRMNote->application_id = isset($_REQUEST['application_id_for_crm_note'])?$_REQUEST['application_id_for_crm_note']:null;
		$objNewCRMNote->actioned = isset($_REQUEST['actioned'])?$_REQUEST['actioned']:null;
		return DAO::saveObjectToTable($link, 'candidate_crm_notes', $objNewCRMNote);
	}

	private function renderCRMNotesTab(PDO $link, RecCandidate $candidate, $order_by = '')
	{
		$order_by = $order_by == ''?' ORDER BY created ASC ':$order_by;
		$crm_notes = DAO::getResultset($link, "SELECT * FROM candidate_crm_notes WHERE candidate_id = '{$candidate->id}' {$order_by} ", DAO::FETCH_ASSOC);
		if(count($crm_notes) == 0)
			return 'No CRM Notes created for this participant';

		$html = '';
		foreach($crm_notes AS $note)
		{
			$vacancy_title = '';
			if(!is_null($note['application_id']) && $note['application_id'] != '')
				$vacancy_title = '<p>This note is attached to candidate application. [Vacancy Title: <strong>' . DAO::getSingleValue($link, "SELECT vacancy_title FROM vacancies INNER JOIN candidate_applications ON vacancies.id = candidate_applications.vacancy_id WHERE candidate_applications.id = '" . $note['application_id'] . "'") . '</strong>]</p>';
			switch($note['type_of_contact'])
			{
				case 1:
					$note_type_icon = "/images/phone.png";
					break;
				case 2:
					$note_type_icon = "/images/email.JPG";
					break;
				case 3:
					$note_type_icon = "/images/interview-icon.png";
					break;
				case 4:
				default:
					$note_type_icon = "/images/text-left.png";
					break;
			}
			$note_id = $note['id'];
			$note_type = DAO::getSingleValue($link, "SELECT description FROM lookup_crm_contact_type WHERE id = '" . $note['type_of_contact'] . "'");
			$created_by = "by " . DAO::getSingleValue($link, "SELECT CONCAT(firstnames, ' ', surname) FROM users WHERE id = '" . $note['created_by'] . "'") . " on " . Date::to($note['created'], Date::DATETIME);
			$subject = $note['subject'];
			$next_action_date = Date::toShort($note['next_action_date']);
			if($next_action_date != '')
			{
				$diff = Date::dateDiffInfo(date('Y-m-d'), $note['next_action_date'], false);
				$diff = (int)$diff['days'] > 0?$diff['days']:'';
				if(!is_null($note['next_action_date']) && $diff != '')
					$next_action_date .= " <span style='color:red;font-style:italic'>&nbsp;(" . $diff . ' days left)</span>';
			}
			$agreed_action = $note['agreed_action'];
			$other_notes = $note['other_notes'];
			$actioned = $note['actioned'] == 'Yes'?'<img width="20" height="20" src="/images/green-tick.gif" border="0" alt="Actioned" title="This CRM note has been actioned and locked" />':'<img onclick="editCRMNote(\'' . $note_id . '\');" src="/images/edit.jpg" height="20px;" alt="" style="cursor: pointer; float: left;" title="Edit this CRM note" />';

			$html .= <<<HTML
			<div class="panel">
				<table>
					<tr>
						<td><img src="$note_type_icon" height="20px;" alt="" style="float: left;" /></td>
						<td>&nbsp; <span style="font-size: 150%;">$note_type</span> &nbsp; <span style="color:gray;font-style:italic"> &nbsp; ($created_by)</span><span style="float: right;">$actioned</span> </td>
					</tr>
					<tr>
						<td></td>
						<td>
							<table cellpadding="6">
								<tr>
									<td class="fieldLabel">Subject: </td><td class="fieldValue">$subject</td>
									<td class="fieldLabel" align="right">Next Action Date: </td><td class="fieldValue">$next_action_date</td>
								</tr>
								<tr>
									<td class="fieldLabel">Agreed Action: </td><td colspan="3" class="fieldValue">$agreed_action</td>
								</tr>
								<tr>
									<td class="fieldLabel">Other Notes: </td><td colspan="3" class="fieldValue">$other_notes</td>
								</tr>
							</table>
						</td>
					</tr>
				</table>
				$vacancy_title
			</div>
HTML;
		}
		return $html;
	}

	private function renderInvitesTab(PDO $link, RecCandidate $candidate)
	{
		$contacts_sql = <<<SQL
SELECT
  users.id,
  CONCAT(users.firstnames, ' ', users.surname),
  NULL
FROM
  users
  INNER JOIN vacancies ON users.`employer_id` = vacancies.`employer_id`
  INNER JOIN candidate_applications ON vacancies.id = candidate_applications.`vacancy_id`
 WHERE
  candidate_applications.`candidate_id` = '$candidate->id' AND candidate_applications.`current_status` = '2'
  AND users.type = '23'
;

SQL;
		$contacts = DAO::getResultset($link, $contacts_sql);
		$contacts_panel = "";

		$invites = DAO::getResultset($link, "SELECT * FROM calendar_event WHERE for_whom = '{$candidate->id}' ", DAO::FETCH_ASSOC);
		if(count($invites) == 0)
			return 'No Invites created for this participant';

		$html = '';
		foreach($invites AS $note)
		{
			$note_type_icon = "/images/bell.JPG";
			$note_id = $note['event_id'];
			$note_title = $note['title'];
			$note_desc = $note['description'];
			$note_fromdate = Date::toMedium($note['datefrom']);
			$note_fromtime = $note['datefromtime'];
			$note_todate = $note['datetotime'];
			$note_totime = Date::toMedium($note['datetotime']);
			$note_allday = $note['allday'];
			$note_location = $note['location'];
			$created_by = DAO::getSingleValue($link, "SELECT CONCAT(firstnames, ' ', surname) FROM users WHERE id = '" . $note['by_whom'] . "'");
			$buttons = "";
			$d1 = new Date($note_fromdate);
			$d2 = new Date(date('Y-m-d'));
			if($note['status'] != 'CANCELLED' && ($d1->after($d2) || $d1->equals($d2)))
				$buttons = <<<BUTTONS
<span style="float: right;">
	<img onclick="editInvite('$note_id');" src="/images/edit.jpg" height="20px;" alt="" style="cursor: pointer; float: left;" title="Update Invite Details" />
	&nbsp;<img onclick="cancelInvite('$note_id');" src="/images/cancel-icon.png" height="20px;" alt="" style="cursor: pointer; float: left;" title="Cancel Invite" />
	&nbsp;<img onclick="$('#trAddInviteToEmployerContact$note_id').toggle();" src="/images/plus.png" height="20px;" alt="" style="cursor: pointer; float: left;" title="Add this invite into employer/store contact calendar" />
</span>
BUTTONS;
;
			if(count($contacts) > 0)
			{
				$contacts_panel = '<tr id="trAddInviteToEmployerContact' . $note_id . '" style="display:none;"><td colspan="2" align="right">' . HTML::select('contacts'.$note_id, $contacts, '') . ' &nbsp; <span class="button" id="btnAddInviteToEmployerContact" onclick="addCandidateInviteToEmployerContact(\'' . $note_id . '\');"> &nbsp; Add &nbsp; </span></td></tr>';
			}

			$html .= <<<HTML
			<div class="panel">
				<table>
					<tr>
						<td><img src="$note_type_icon" width="30" height="30" alt="" style="float: left;" /></td>
						<td>&nbsp; <span style="font-size: 150%;">$note_title</span> &nbsp; <span style="color:gray;font-style:italic"> &nbsp; created by ($created_by)</span> $buttons</td>
					</tr>
					$contacts_panel
					<tr>
						<td></td>
						<td>
							<table cellpadding="6">
								<tr>
									<td class="fieldLabel">From: </td><td class="fieldValue">$note_fromdate ($note_fromtime) - $note_totime ($note_todate)</td>
									<td class="fieldLabel" align="right">Location: </td><td class="fieldValue">$note_location</td>
								</tr>
								<tr>
									<td class="fieldLabel">Detail: </td><td colspan="3" class="fieldValue">$note_desc</td>
								</tr>
							</table>
						</td>
					</tr>
				</table>
			</div>
HTML;
		}
		return $html;
	}

	private function renderEmailsTab(PDO $link, RecCandidate $candidate, $order_by = '')
	{
		$order_by = $order_by == ''?' ORDER BY created ASC ':$order_by;
		$emails = DAO::getResultset($link, "SELECT * FROM candidate_emails WHERE candidate_id = '{$candidate->id}' {$order_by} ", DAO::FETCH_ASSOC);
		if(count($emails) == 0)
			return 'No emails sent to this participant from Sunesis';

		$html = '';
		foreach($emails AS $e)
		{
			$e_id = $e['id'];
			$e_on = Date::toLong($e['created']);
			$e_by = DAO::getSingleValue($link, "SELECT CONCAT(firstnames, ' ', surname) FROM users WHERE id = '" . $e['by_whom'] . "'");
			$e_to = $candidate->firstnames . ' ' . $candidate->surname . ' (' . $e['candidate_email'] . ')';
			$e_subject = $e['subject'];
			$e_body = $e['email_body'];
			$e_body_tr = 'trEmail'.$e_id.'Detail';
			$html .= <<<HTML
			<div class="panel">
				<table>
					<tr>
						<td><img src="/images/email.JPG" height="25px;" alt="" style="float: left;" /></td><td>&nbsp; <span style="font-size: 150%;">$e_subject</span> &nbsp;</td>
						<td align="right"><span class="button" onclick="saveEmailAsCRMNote('$e_id');">Save as CRM</span> </td>
					</tr>
					<tr>
						<td></td><td><span style="color:gray;font-style:italic"> &nbsp; On: $e_on</span></span> </td>
					</tr>
					<tr>
						<td></td><td><span style="color:gray;font-style:italic"> &nbsp; By: $e_by</span></span> </td>
					</tr>
					<tr>
						<td></td><td><span style="color:gray;font-style:italic"> &nbsp; To: $e_to </span></span> </td>
					</tr>
					<tr>
						<td></td><td><span title="Show/hide email contents" class="button" onclick="$('#$e_body_tr').slideToggle();">+/-</span> </td>
					</tr>
					<tr id="$e_body_tr" style="display: none;">
						<td></td><td class="fieldValue">$e_body</td>
					</tr>
				</table>
			</div>
HTML;
		}
		return $html;
	}

	private function exportToPDF(PDO $link, Candidate $candidate)
	{

		include("./MPDF57/mpdf.php");

		$mpdf=new mPDF('','','','',15,15,47,16,9,9);
		// LOAD a stylesheet
		$stylesheet = file_get_contents('common.css');
		$mpdf->WriteHTML($stylesheet,1);	// The parameter 1 tells that this is css/style only and no body/html/text


		$html = '';
		$dob = htmlspecialchars(Date::toMedium($candidate->dob));
		if ($candidate->dob)
			$age = '<span style="margin-left:30px;color:gray">(' . Date::dateDiff(date("Y-m-d"), $candidate->dob) . ')</span>';
		$candidate->gender = DAO::getSingleValue($link, "SELECT description FROM lookup_gender WHERE id='{$candidate->gender}';");
		$candidate->ethnicity = DAO::getSingleValue($link, "SELECT description FROM lookup_country_list WHERE code='{$candidate->ethnicity}';");
		$candidate_address = $candidate->displayCandidateAddresses($link);
		$candidate_qualifications = $candidate->render_candidate_qualifications($link, false);
		$candidate_employment = $candidate->render_candidate_employment($link, false);
		$candidate_applications = $candidate->render_candidate_applications($link, false);

		$html = <<<HEREDOC
		<div>
			<h3>Personal Details</h3>
			<table border="0" cellspacing="4" cellpadding="4" style="margin-left:10px">
				<col width="190"/>
				<col width="380"/>
				<tr>
					<td class="fieldLabel">Firstnames</td>
					<td class="fieldValue">$candidate->firstnames</td>
				</tr>
				<tr>
					<td class="fieldLabel">Surname</td>
					<td class="fieldValue">$candidate->surname</td>
				</tr>

				<tr>
					<td class="fieldLabel">Date of birth</td>
					<td class="fieldValue">$dob $age</td>
				</tr>
				<tr>
					<td class="fieldLabel">Email</td>
					<td class="fieldValue">$candidate->email</td>
				</tr>
				<tr>
					<td class="fieldLabel">National Insurance</td>
					<td class="fieldValue">$candidate->national_insurance</td>
				</tr>
				<tr>
					<td class="fieldLabel">Gender</td>
					<td class="fieldValue">$candidate->gender</td>
				</tr>
				<tr>
					<td class="fieldLabel">Ethnicity</td>
					<td class="fieldValue">$candidate->ethnicity</td>
				</tr>
			</table>
		</div>
		$candidate_address
		$candidate_qualifications
		$candidate_employment
		$candidate_applications

HEREDOC;

		$mpdf->SetHTMLFooter('<div align="center"><span style="font-size: 10px;">Baltic Training Services Ltd<br>Tel: 01325 731 050<br>F: 01325 317 156<br>E: yourfuture@baltictraining.com</span></div>');
		$mpdf->SetHTMLHeader("<div align='center'><img src='./images/logos/baltic.png' alt='Baltic Training Services'></div>  ");
		$mpdf->WriteHTML($html);




		$mpdf->Output();

		exit;

	}

	private function render_candidate_qualifications (PDO $link, RecCandidate $candidate)
	{
		$return_html = '';
		//$return_html .= '<table cellpadding="4"><tr><td class="fieldLabel">Highest education completed:</td><td class="fieldValue">' . DAO::getSingleValue($link, "SELECT DISTINCT description FROM lookup_candidate_qualification WHERE id = '{$candidate->last_education}'") . '</td></tr></table>';
		$return_html .= '<table border="0" class="resultset" cellspacing="0" cellpadding="4" >';
		$return_html .= '<thead><th>Level</th><th>Subject</th><th>Grade</th><th>Date</th><th>Institution</th></thead>';
		if(count($candidate->qualifications) > 0)
		{
			foreach ( $candidate->qualifications AS $edu_pos => $edu_row )
			{
				$return_html .= '<tr>';
				if($edu_row['qualification_level'] != 'GCSE')
					$return_html .= '<td>'.DAO::getSingleValue($link, "SELECT distinct description FROM lookup_candidate_qualification WHERE id = '" . $edu_row['qualification_level'] . "'").'</td>';
				else
					$return_html .= '<td>GCSE</td>';
				$return_html .= '<td>'.$edu_row['qualification_subject'].'</td>';
				$return_html .= '<td>'.DAO::getSingleValue($link, "SELECT description FROM lookup_gcse_grades WHERE id = '" . $edu_row['qualification_grade'] . "'").'</td>';
				$return_html .= '<td>'.Date::to($edu_row['qualification_date'], 'd/m/Y').'</td>';
				$return_html .= '<td>'.$edu_row['institution'].'</td>';
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

	private function render_candidate_employment (PDO $link, RecCandidate $candidate)
	{
		$return_html = '';
		$return_html .= '<table id="tbl_employment" class="resultset" border="0" cellspacing="0" cellpadding="4">';
		$return_html .= '<thead><th>Company Name</th><th>Job Title</th><th>Start Date</th><th>End Date</th><th>Skills</th></thead>';
		if ( sizeof($candidate->employments) > 0 )
		{
			foreach ( $candidate->employments AS $edu_pos => $edu_row )
			{
				$return_html .= '<tr>';
				$return_html .= '<td>'.$edu_row['company_name'].'</td><td>'.$edu_row['job_title'].'</td><td>'.Date::toShort($edu_row['start_date']).'</td><td>'.Date::toShort($edu_row['end_date']).'</td><td>'.nl2br($edu_row['skills'] ?: '').'</td>';
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

	private function renderCandidateHistory(PDO $link, RecCandidate $candidate)
	{
		$html = "<table>";
		$html .= '<tr><td valign="top"><h4>Candidate</h4>';
		$history = DAO::getResultset($link, "SELECT * FROM candidate_notes WHERE candidate_id = '{$candidate->id}' ORDER BY created ", DAO::FETCH_ASSOC);
		foreach($history AS $h)
		{
			$html .= '<li><p style="line-height: 20px;letter-spacing: 0.5px;">';
			$html .= '<em><span style="border: 1px; border-radius: 5px;padding: 2px; background-color:#21ba45!important;color:#ffffff !important"> on <span style="display:inline-block;vertical-align:center;font-weight:bold;margin-left:1em;">' . Date::to($h['created'], Date::DATETIME) . '</span></span> </em>';
			$html .= '<em><strong>Detail: </strong>' . htmlspecialchars((string)$h['note']) . ' </em>';
			$html .= '<em><strong>By: </strong>' . DAO::getSingleValue($link, "SELECT CONCAT(firstnames, ' ', surname) FROM users WHERE id = '" . $h['created_by'] . "'") . ' </em>';
			$html .= '</p></li>';
		}
		$html .= '</td><td> &nbsp; </td>';
		$html .= '<td valign="top"><h4>Applications</h4>';
		$app_ids = DAO::getSingleColumn($link, "SELECT id FROM candidate_applications WHERE candidate_id = '{$candidate->id}'");
		foreach($app_ids AS $app_id)
		{
			$application = RecCandidateApplication::loadFromDatabaseByID($link, $app_id);
			if(is_null($application))
				continue;
			$html .= '<p>'.$application->vacancy->vacancy_reference.' ('.$application->vacancy->vacancy_title.')</p>';
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
			$html .= '<hr>';
		}
		$html .= '</td></tr>';
		$html .= '</table>';
		return $html;
	}

	private function renderCandidateApplication(PDO $link, RecCandidate $candidate)
	{
		$return_html = '';
		$return_html .= '<table class="resultset" border="0" cellspacing="0" cellpadding="6">';
		$return_html .= '<thead><th>Employer</th><th>Vacancy<br>Reference</th><th>Vacancy<br>Title</th><th>Application<br>Status</th><th>Screening<br>RAG</th><th>Telephone Interview<br>Score</th><th>Telephone Interview<br>Comments</th></thead>';
		$applications = DAO::getSingleColumn($link, "SELECT id FROM candidate_applications WHERE candidate_id = '{$candidate->id}'");
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
				$return_html .= '<td align="center">' . $objApplication->vacancy->getEmployerName($link) . '</td>';
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
					$return_html .= '<td align="center"><table cellpadding="6">';
					$return_html .= '<tr><td><span style="font-size: smaller;color:gray">score:</span></td><td>' . $objApplication->telephone_interview_score . '</td></tr>';
					$return_html .= '<tr><td><span style="font-size: smaller;color:gray">total marks:</span></td><td>35</td></tr>';
					$return_html .= '<tr><td><span style="font-size: smaller;color:gray">pass marks:</span></td><td>20</td></tr>';
					$return_html .= '</td></table>';
				}
				$return_html .= '<td align="center">' . DAO::getSingleValue($link, "SELECT comments FROM candidate_application_status WHERE application_id = '" . $objApplication->id . "' AND status = '" . RecCandidateApplication::TELEPHONE_INTERVIEWED . "' ORDER BY id DESC LIMIT 1") . '</td>';
				$return_html .= '</tr>';
				unset($objApplication);
			}
		}
		$return_html .= '</table>';

		return $return_html;
	}

}
?>