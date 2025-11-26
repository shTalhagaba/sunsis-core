<?php
class save_sign_app_agreements implements IUnauthenticatedAction
{
	public function execute(PDO $link)
	{
		//pre($_REQUEST);

		$ob_learner_id = isset($_POST['ob_learner_id'])?$_POST['ob_learner_id']:'';
		$tr_id = isset($_POST['tr_id'])?$_POST['tr_id']:'';
		$contact_id = isset($_POST['contact_id'])?$_POST['contact_id']:'';
		$key = isset($_POST['key'])?$_POST['key']:'';
		$employer_signature = isset($_POST['employer_signature'])?$_POST['employer_signature']:'';

		if($tr_id != '' && $contact_id != '' && $key != '')
		{
			if(!$this->validateKey($tr_id, $contact_id, $key))
			{
				$this->generateErrorPage();
				exit;
			}
		}
		else
		{
			$this->generateErrorPage();
			exit;
		}

		if($employer_signature == '')
			throw new Exception('Missing employer signature');

		$employer_signature = explode('&', $employer_signature);
		unset($employer_signature[0]);
		$employer_signature = implode('&', $employer_signature);

		DAO::execute($link, "UPDATE ob_learners SET ob_learners.employer_signature = '{$employer_signature}', emp_resigned = 'Y' WHERE ob_learners.id = '{$ob_learner_id}'");

		$log = new OnboardingLogger();
		$log->subject = 'FORM RE-SIGNED BY EMPLOYER';
		$log->note = "Form is re-signed by employer contact";
		$log->ob_learner_id = $ob_learner_id;
		$log->by_whom = $contact_id;
		$log->save($link);

		http_redirect('do.php?_action=sign_app_agreement&l_id='.$tr_id.'&c_id='.$contact_id.'&key='.md5($tr_id.'_'.$contact_id.'_sunesis_completed'));

	}

	private function validateKey($tr_id, $contact_id, $key)
	{
		return $key == md5($tr_id.'_'.$contact_id.'_sunesis');
	}

	private function generateErrorPage()
	{
		echo <<<HTML
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/html" xmlns="http://www.w3.org/1999/html" xmlns="http://www.w3.org/1999/html"
      xmlns="http://www.w3.org/1999/html" xmlns="http://www.w3.org/1999/html">
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<title>Siemens | Apprenticeship Agreement</title>
	<meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
	<link rel="stylesheet" href="/assets/adminlte/bootstrap/css/bootstrap.min.css">
	<link rel="stylesheet" href="/assets/adminlte/dist/css/AdminLTE.min.css">
	<link rel="stylesheet" href="/module_onboarding/css/onboarding.css">
<body>

<nav id="mainNav" class="navbar navbar-default navbar-fixed-top navbar-custom">
	<div class="container">
		<div class="navbar-header page-scroll">
			<a class="navbar-brand" href="#">
				<img height="50px" class="headerlogo" src="images/logos/siemens/siemens1.png" />
			</a>
		</div>
	</div>
</nav>

	<div class="nts-secondary-teaser-gradient" style="max-width: 450px; padding: 25px; border-radius: 25px; margin-left: 30%">
		<h2>Invalid Access Credentials</h2><hr>
		<p>The credentials you have supplied are unknown to the system.</p>
		<p>If you are sure that you have used the correct URL as specified in the email you received then contact us at <a style="color: white;" href="mailto:support@perspective-uk.com">support@perspective-uk.com</a>
		and provide the details.</p>
	</div>

	<footer class="main-footer">
		<div class="pull-left">
			<table class="table" style="border-collapse: separate; border-spacing: 0.5em;">
				<tr>
					<td><img width="230px" src="images/logos/siemens/ESFA.png" /></td>
					<td><img src="images/logos/siemens/ESF.png" /></td>
					<td><img src="images/logos/siemens/ofsted.jpg" /></td>
					<td><img src="images/logos/siemens/top70.png" width="200px" height="99px" /></td>
					<td><img src="images/logos/siemens/top100.jpg" width="100px" height="165px" /></td>
				</tr>
			</table>
		</div>
		<div class="pull-right">
			<img src="images/logos/SUNlogo.png" />
		</div>
	</footer>
</body>
</html>
HTML;

	}
}