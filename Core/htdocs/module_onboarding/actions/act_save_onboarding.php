<?php
class save_onboarding implements IUnauthenticatedAction
{
	public function execute(PDO $link)
	{
		//Important: - "id" in incoming REQUEST string is the id of "ob_learners" data
		$ob_learner = new stdClass();
		foreach($_REQUEST AS $key => $value)
		{
			$ob_learner->$key = $value;
		}

		DAO::transaction_start($link);
		try
		{
			if($ob_learner->is_finished == "N")
			{// partial save
				$this->saveInformation($link, $ob_learner);
			}
			else
			{// complete save
				$learner_signature = isset($_REQUEST['learner_signature'])?$_REQUEST['learner_signature']:'';
				if($learner_signature == '')
					throw new Exception('Missing learner signature');

				$learner_signature = explode('&', $_REQUEST['learner_signature']);
				unset($learner_signature[0]);
				$ob_learner->learner_signature = implode('&', $learner_signature);

				$this->saveInformation($link, $ob_learner);
				$username = isset($_REQUEST['username'])?$_REQUEST['username']:'';

				$additional_log = '';

				if($username != '')
				{
					// start uploading files
					$target_directory = $username.'/Certificates';
					$valid_extensions = array('doc', 'docx', 'pdf', 'jpg', 'png', 'jpeg');
					if(isset($_FILES['care_or_ehc']['size']) && $_FILES['care_or_ehc']['size'] <= 1024000)
						Repository::processFileUploads('care_or_ehc', $target_directory, $valid_extensions);
					if(isset($_FILES['evidence_dl']['size']) && $_FILES['evidence_dl']['size'] <= 1024000)
						Repository::processFileUploads('evidence_dl', $target_directory, $valid_extensions);
					if(isset($_FILES['evidence_pp']['size']) && $_FILES['evidence_pp']['size'] <= 1024000)
						Repository::processFileUploads('evidence_pp', $target_directory, $valid_extensions);
					if(isset($_FILES['evidence_ilr']['size']) && $_FILES['evidence_ilr']['size'] <= 1024000)
						Repository::processFileUploads('evidence_ilr', $target_directory, $valid_extensions);
					if(isset($_FILES['evidence_previous_uk_study_visa']['size']) && $_FILES['evidence_previous_uk_study_visa']['size'] <= 1024000)
						Repository::processFileUploads('evidence_previous_uk_study_visa', $target_directory, $valid_extensions);

					if(isset($_FILES['file1']['size']) && $_FILES['file1']['size'] <= 1024000)
						Repository::processFileUploads('file1', $target_directory, $valid_extensions);
					if(isset($_FILES['file2']['size']) && $_FILES['file2']['size'] <= 1024000)
						Repository::processFileUploads('file2', $target_directory, $valid_extensions);
					if(isset($_FILES['file3']['size']) && $_FILES['file3']['size'] <= 1024000)
						Repository::processFileUploads('file3', $target_directory, $valid_extensions);
					if(isset($_FILES['file4']['size']) && $_FILES['file4']['size'] <= 1024000)
						Repository::processFileUploads('file4', $target_directory, $valid_extensions);
					if(isset($_FILES['file5']['size']) && $_FILES['file5']['size'] <= 1024000)
						Repository::processFileUploads('file5', $target_directory, $valid_extensions);
					if(isset($_FILES['file6']['size']) && $_FILES['file6']['size'] <= 1024000)
						Repository::processFileUploads('file6', $target_directory, $valid_extensions);

					// set alert on training record
					DAO::execute($link, "UPDATE tr SET tr.ob_alert = '1' WHERE tr.username = '{$username}' AND tr.id = '{$_REQUEST['tr_id']}'");

					$existing_values = DAO::getObject($link, "SELECT tr.firstnames, tr.surname, tr.gender, tr.dob, tr.home_postcode, tr.home_email FROM tr WHERE tr.id = '{$_REQUEST['tr_id']}' AND tr.username = '{$_REQUEST['username']}'");
					if($ob_learner->firstnames != $existing_values->firstnames)
						$additional_log .= "[First Name] is changed from '{$existing_values->firstnames}' to '{$ob_learner->firstnames}'\n";
					if($ob_learner->surname != $existing_values->surname)
						$additional_log .= "[Surname] is changed from '{$existing_values->surname}' to '{$ob_learner->surname}'\n";
					if($ob_learner->gender != $existing_values->gender)
						$additional_log .= "[Gender] is changed from '{$existing_values->gender}' to '{$ob_learner->gender}'\n";
					if(Date::toShort($ob_learner->dob) != Date::toShort($existing_values->dob))
						$additional_log .= "[DOB] is changed from '" . Date::toShort($existing_values->dob) . "' to '" . Date::toShort($ob_learner->dob) . "'\n";
					if($ob_learner->home_postcode != $existing_values->home_postcode)
						$additional_log .= "[Postcode] is changed from '{$existing_values->home_postcode}' to '{$ob_learner->home_postcode}'\n";
					if($ob_learner->home_email != $existing_values->home_email)
						$additional_log .= "[Email] is changed from '{$existing_values->home_email}' to '{$ob_learner->home_email}'\n";
					if($additional_log != '')
						$additional_log = "\nFields changed:\n" . $additional_log;
				}

				$log = new OnboardingLogger();
				$log->subject = 'FORM COMPLETED BY LEARNER';
				$log->note = "Learner has completed and finished the form." . $additional_log;
				$log->ob_learner_id = $ob_learner->id;
				$log->by_whom = $ob_learner->id;
				$log->save($link);
				unset($log);

				// send welcome email to the learner
				$this->sendEmailToEmployer($link, $ob_learner, $_REQUEST['tr_id']);

			}
			DAO::transaction_commit($link);
		}
		catch(Exception $e)
		{
			DAO::transaction_rollback($link, $e);
			throw new Exception($e->getMessage());
			exit;
		}

		if($ob_learner->is_finished == 'N')
		{
			echo json_encode($ob_learner);
			return;
		}

		http_redirect('do.php?_action=onboarding&id='.$_REQUEST['tr_id'].'&key='.md5($_REQUEST['tr_id'].'_sunesis_completed'));
	}

	private function sendEmailToEmployer(PDO $link, $ob_learner, $tr_id)
	{
		$email_content = DAO::getSingleValue($link, "SELECT template_content FROM lookup_email_templates WHERE template_name = 'employer_contact_email' ");
		if($email_content == '')
			return;

		$crm_contact_id = DAO::getSingleValue($link, "SELECT tr.crm_contact_id FROM tr WHERE tr.id = '{$tr_id}' ");
		if($crm_contact_id == '')
			return;

		$employer_contact = DAO::getObject($link, "SELECT * FROM organisation_contact WHERE contact_id = '{$crm_contact_id}'");
		if(is_null($employer_contact) || !isset($employer_contact->contact_email))
			return;

		$key = md5($tr_id.'_'.$employer_contact->contact_id.'_sunesis');

		$client_name_in_url = DB_NAME;
		$client_name_in_url = str_replace('am_', '', $client_name_in_url);
		$client_name_in_url = str_replace('_', '-', $client_name_in_url);
		if(SOURCE_LOCAL)
			$client_url = 'https://localhost/do.php?_action=sign_app_agreement&l_id=' . $tr_id . '&c_id=' . $employer_contact->contact_id . '&key=' . $key;
		elseif(SystemConfig::getEntityValue($link, "module_onboarding"))
			$client_url = 'https://'.$client_name_in_url.'.sunesis.uk.net/do.php?_action=sign_app_agreement&l_id=' . $tr_id . '&c_id=' . $employer_contact->contact_id . '&key=' . $key;
		else
			return;

		$email_content = str_replace('$EMPLOYER_CONTACT_FIRST_NAME$', $employer_contact->contact_name, $email_content);
		$email_content = str_replace('$LEARNER_FULL_NAME$', $ob_learner->firstnames . ' ' . $ob_learner->surname, $email_content);

		$email_content = str_replace('$ONBOARDING_EMPLOYER_URL$', $client_url, $email_content);

		if(isset($ob_learner->gender))
		{
			if($ob_learner->gender == 'M')
				$email_content = str_replace('$HIS_HER$', 'his', $email_content);
			elseif($ob_learner->gender == 'F')
				$email_content = str_replace('$HIS_HER$', 'her', $email_content);
			else
				$email_content = str_replace('$HIS_HER$', 'his/her', $email_content);
		}
		else
			$email_content = str_replace('$HIS_HER$', 'his/her', $email_content);

		Emailer::html_mail($employer_contact->contact_email, 'no-reply-onboarding@perweb07.perspective-uk.com', 'Your new Apprentice', '', $email_content, array(), array('X-Mailer: PHP/' . phpversion()));
	}

	private function saveInformation(PDO $link, $ob_learner)
	{
		if(isset($ob_learner->SEI) && $ob_learner->SEI == 'on')
			$ob_learner->SEI = '1';
		if(isset($ob_learner->PEI) && $ob_learner->PEI == 'on')
			$ob_learner->PEI = '1';
		if(isset($ob_learner->SEM) && $ob_learner->SEM == 'on')
			$ob_learner->SEM = '1';
		if(isset($ob_learner->EHC_Plan) && $ob_learner->EHC_Plan == 'on')
			$ob_learner->EHC_Plan = '1';
		if(isset($ob_learner->care_leaver) && $ob_learner->care_leaver == 'on')
			$ob_learner->care_leaver = '1';
		if(isset($ob_learner->is_non_eu_resident) && $ob_learner->is_non_eu_resident == 'on')
			$ob_learner->is_non_eu_resident = '1';
		if(isset($ob_learner->need_visa_to_study) && $ob_learner->need_visa_to_study == 'on')
			$ob_learner->need_visa_to_study = '1';

		DAO::saveObjectToTable($link, "ob_learners", $ob_learner);

		//save Prior Attainment
		DAO::execute($link, "DELETE FROM ob_learners_pa WHERE ob_learner_id = '{$ob_learner->id}'");
		$english = new stdClass();
		$english->ob_learner_id = $ob_learner->id;
		$english->level = isset($_REQUEST['gcse_english_level'])?$_REQUEST['gcse_english_level']:'';
		$english->subject = isset($_REQUEST['gcse_english_subject'])?$_REQUEST['gcse_english_subject']:'';
		$english->p_grade = isset($_REQUEST['gcse_english_grade_predicted'])?$_REQUEST['gcse_english_grade_predicted']:'';
		$english->a_grade = isset($_REQUEST['gcse_english_grade_actual'])?$_REQUEST['gcse_english_grade_actual']:'';
		$english->date_completed = isset($_REQUEST['gcse_english_date_completed'])?$_REQUEST['gcse_english_date_completed']:'';
		$english->q_type = 'g';
		if($english->p_grade != '' || $english->a_grade != '')
			DAO::saveObjectToTable($link, 'ob_learners_pa', $english);
		unset($english);
		$maths = new stdClass();
		$maths->ob_learner_id = $ob_learner->id;
		$maths->level = isset($_REQUEST['gcse_maths_level'])?$_REQUEST['gcse_maths_level']:'';
		$maths->subject = isset($_REQUEST['gcse_maths_subject'])?$_REQUEST['gcse_maths_subject']:'';
		$maths->p_grade = isset($_REQUEST['gcse_maths_grade_predicted'])?$_REQUEST['gcse_maths_grade_predicted']:'';
		$maths->a_grade = isset($_REQUEST['gcse_maths_grade_actual'])?$_REQUEST['gcse_maths_grade_actual']:'';
		$maths->date_completed = isset($_REQUEST['gcse_maths_date_completed'])?$_REQUEST['gcse_maths_date_completed']:'';
		$maths->q_type = 'g';
		if($maths->p_grade != '' || $maths->a_grade != '')
			DAO::saveObjectToTable($link, 'ob_learners_pa', $maths);
		unset($maths);
		for($i = 1; $i <= 15; $i++)
		{
			$objPA = new stdClass();
			$objPA->ob_learner_id = $ob_learner->id;
			$objPA->level = isset($_REQUEST['level'.$i])?$_REQUEST['level'.$i]:'';
			$objPA->subject = isset($_REQUEST['subject'.$i])?substr($_REQUEST['subject'.$i], 0, 79):'';
			$objPA->p_grade= isset($_REQUEST['predicted_grade'.$i])?$_REQUEST['predicted_grade'.$i]:'';
			$objPA->a_grade = isset($_REQUEST['actual_grade'.$i])?$_REQUEST['actual_grade'.$i]:'';
			$objPA->date_completed = isset($_REQUEST['date_completed'.$i])?$_REQUEST['date_completed'.$i]:'';
			$objPA->q_type = isset($_REQUEST['q_type'.$i])?$_REQUEST['q_type'.$i]:'';
			if(trim($objPA->level) != '' && trim($objPA->subject) != '')
				DAO::saveObjectToTable($link, 'ob_learners_pa', $objPA);
			unset($objPA);
		}
		$high_level = new stdClass();
		$high_level->ob_learner_id = $ob_learner->id;
		$high_level->level = isset($_REQUEST['high_level'])?$_REQUEST['high_level']:'';
		$high_level->subject = isset($_REQUEST['high_subject'])?$_REQUEST['high_subject']:'h';
		$high_level->q_type = 'h';
		DAO::saveObjectToTable($link, 'ob_learners_pa', $high_level);
	}

}