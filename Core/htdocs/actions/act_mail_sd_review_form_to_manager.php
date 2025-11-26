<?php
class mail_sd_review_form_to_manager implements IAction
{
	public function execute(PDO $link)
	{
		if($_SESSION['user']->type == User::TYPE_LEARNER)
			throw new UnauthorizedException();

		$tr_id = isset($_REQUEST['tr_id'])?$_REQUEST['tr_id']:'';
		$review_id = isset($_REQUEST['review_id']) ? $_REQUEST['review_id'] : '';

		$key = md5("SunesisSuperdrugTrainingId=".$tr_id."ReviewId=".$review_id);

		$sql = <<<SQL
SELECT
	organisation_contact.*
FROM
	organisation_contact INNER JOIN tr ON organisation_contact.contact_id = tr.crm_contact_id
WHERE
	tr.id = '$tr_id'
SQL;
		$organisation_contact = DAO::getObject($link, $sql);
		if(!isset($organisation_contact->contact_id))
			throw new Exception('Employer contact information not found.');

		if(is_null($organisation_contact->contact_email) || $organisation_contact->contact_email == '')
			throw new Exception("Email address has not been entered in the system for {$organisation_contact->contact_title} {$organisation_contact->contact_name}");

		$tr = TrainingRecord::loadFromDatabase($link, $tr_id);
		$assessor = User::loadFromDatabaseById($link, $tr->assessor);

		$client = "sd-demo";
		if(DB_NAME == "am_superdrug")
			$client = "superdrug";

		$detail = '<table border="1" cellpadding="5" cellspacing="5">';
		$detail .= '<tr><th colspan="2">LEARNER</th></tr>';
		$detail .= '<tr><td>Name: </td><td>' . $tr->firstnames . ' ' . $tr->surname . '</td></tr>';
		$detail .= $tr->home_email != '' ? '<tr><td>Email: </td><td>' . $tr->home_email . '</td></tr>' : '';
		$detail .= $tr->home_mobile != '' ? '<tr><td>Mobile: </td><td>' . $tr->home_mobile . '</td></tr>' : '';
		$detail .= '<tr><td>Training Start Date: </td><td>' . Date::toShort($tr->start_date) . '</td></tr>';
		$detail .= '<tr><th colspan="2"></th></tr>';
		$detail .= '<tr><th colspan="2">ASSESSOR</th></tr>';
		$detail .= '<tr><td>Name: </td><td>' . $assessor->firstnames . ' ' . $assessor->surname . '</td></tr>';
		$detail .= $assessor->work_email != '' ? '<tr><td>Email: </td><td>' . $assessor->work_email . '</td></tr>' : '';
		$detail .= $assessor->work_mobile != '' ? '<tr><td>Mobile: </td><td>' . $assessor->work_mobile . '</td></tr>' : '';
		$detail .= '</table>';

		$sender_name = $_SESSION['user']->firstnames . ' ' . $_SESSION['user']->surname;
		$message = <<<HTML
<p>Dear {$organisation_contact->contact_title} {$organisation_contact->contact_name},</p>

<p>Please click the link below to open review form for the following learner:</p>

<p>{$detail}</p>

<p><a href ='https://{$client}.sunesis.uk.net/do.php?_action=sd_form_manager&review_id={$review_id}&tr_id={$tr_id}&key={$key}' > Open Review Form </a></p>

<p>Please can you complete any parts of the review relevant to you, sign, date and save.</p>

<p>Many Thanks,</p>

<p>{$sender_name}</p>

<p>If you are unable to click/see the link above then copy the URL below and paste it in your browser to open the form.</p>

<p>https://{$client}.sunesis.uk.net/do.php?_action=sd_form_manager&review_id={$review_id}&tr_id={$tr_id}&key={$key}</p>

HTML;

		if(SOURCE_LOCAL)
		{
			echo $message;
		}
		else
		{
			Emailer::notification_email_review($organisation_contact->contact_email, 'no-reply@perspective-uk.com', 'no-reply@perspective-uk.com', 'Review Form', '', $message, array(), array('X-Mailer: PHP/' . phpversion()));
		}
	}
}