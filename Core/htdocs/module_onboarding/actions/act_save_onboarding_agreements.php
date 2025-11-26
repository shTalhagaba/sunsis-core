<?php
class save_onboarding_agreements implements IUnauthenticatedAction
{
	public function execute(PDO $link)
	{
		//pre($_REQUEST);
		//Important: - "id" in incoming REQUEST string is the id of "ob_learners" data
		$ob_learner = DAO::getObject($link, "SELECT * FROM ob_learners WHERE id = '{$_REQUEST['id']}'");

		DAO::transaction_start($link);
		try
		{
			$learner_signature = isset($_REQUEST['learner_signature'])?$_REQUEST['learner_signature']:'';
			if($learner_signature == '')
				throw new Exception('Missing learner signature');

			$learner_signature = explode('&', $_REQUEST['learner_signature']);
			unset($learner_signature[0]);
			$ob_learner->learner_signature = implode('&', $learner_signature);
			$ob_learner->is_finished = 'Y';
			DAO::saveObjectToTable($link, "ob_learners", $ob_learner);

			$log = new OnboardingLogger();
			$log->subject = 'FORM RE-SIGNED BY LEARNER';
			$log->note = "Learner has checked and re-signed the form.";
			$log->ob_learner_id = $ob_learner->id;
			$log->by_whom = $ob_learner->id;
			$log->save($link);
			unset($log);

			// send welcome email to the learner
			$this->sendEmailToEmployer($link, $ob_learner, $_REQUEST['tr_id']);

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

		http_redirect('do.php?_action=onboarding_agreements&id='.$_REQUEST['tr_id'].'&key='.md5($_REQUEST['tr_id'].'_sunesis_completed'));
	}

	private function sendEmailToEmployer(PDO $link, $ob_learner, $tr_id)
	{
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
			$client_url = 'https://localhost/do.php?_action=sign_app_agreements&l_id=' . $tr_id . '&c_id=' . $employer_contact->contact_id . '&key=' . $key;
		elseif(SystemConfig::getEntityValue($link, "module_onboarding"))
			$client_url = 'https://'.$client_name_in_url.'.sunesis.uk.net/do.php?_action=sign_app_agreements&l_id=' . $tr_id . '&c_id=' . $employer_contact->contact_id . '&key=' . $key;
		else
			return;

		$email_content = <<<HTML
<p><img alt="" src="https://demo.sunesis.uk.net/images/logos/siemens/siemens1.png" style="height:50px; width:149px" /></p>

<hr />
<p>Dear $employer_contact->contact_name,</p>

<p>Minor changes have been made to your new Apprentices Programme including Start and Planned End Dates. Therefore, the Apprenticeship Agreement should be re-signed.</p>

<p>Could you click on the link below to view the changes and re-sign this document.</p>

<p>$client_url</p>

<p>Many thanks for your help with this process.</p>
<p>&nbsp;</p>
<p>Sarah Burns</p>

<p>Email: siemensprofessionaleducationonboarding.gb@siemens.com</p>

<p>Mobile +44 7921247888</p>
HTML;

		Emailer::html_mail($employer_contact->contact_email, 'no-reply-onboarding@perweb07.perspective-uk.com', 'Your Apprentice - Updated Apprenticeship Agreement', '', $email_content, array(), array('X-Mailer: PHP/' . phpversion()));
	}
}