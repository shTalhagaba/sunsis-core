<?php
class sign_app_agreements implements IUnauthenticatedAction
{
	public function execute(PDO $link)
	{
		// Important: l_id in URL is Training Record ID
		$tr_id = isset($_REQUEST['l_id'])?$_REQUEST['l_id']:'';
		$contact_id = isset($_REQUEST['c_id'])?$_REQUEST['c_id']:'';
		$key = isset($_REQUEST['key'])?$_REQUEST['key']:'';
		if($tr_id != '' && $contact_id != '' && $key != '')
		{
			if(!$this->validateKey())
			{
				$this->generateErrorPage();
				exit;
			}
			$learner = TrainingRecord::loadFromDatabase($link, $tr_id);
			if(is_null($learner))
			{
				$this->generateErrorPage();
				exit;
			}
			$ob_learner = DAO::getObject($link, "SELECT ob_learners.* FROM ob_learners INNER JOIN users ON ob_learners.user_id = users.id INNER JOIN tr ON users.username = tr.username WHERE tr.id = '{$learner->id}'");
			if(is_null($ob_learner))
			{
				$this->generateErrorPage();
				exit;
			}
			elseif($ob_learner->emp_resigned == 'Y')
			{
				$this->generateAlreadyCompletedPage();
				exit;
			}
		}
		else
		{
			$this->generateErrorPage();
			exit;
		}

		$employer_main_site = Location::loadFromDatabase($link, $learner->employer_location_id);

		include_once('tpl_sign_app_agreements.php');
	}

	private function validateKey()
	{
		$tr_id = isset($_REQUEST['l_id'])?$_REQUEST['l_id']:'';
		$contact_id = isset($_REQUEST['c_id'])?$_REQUEST['c_id']:'';
		$key = isset($_REQUEST['key'])?$_REQUEST['key']:'';

		if(md5($tr_id.'_'.$contact_id.'_sunesis_completed') == $key)
			die($this->generateCompletionPage());
		else
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

	private function generateAlreadyCompletedPage()
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
		<h2>You have already signed this agreement</h2><hr>
		<p>For further information please contact: <a style="color: white;" href="mailto:siemensprofessionaleducationonboarding.gb@siemens.com">siemensprofessionaleducationonboarding.gb@siemens.com</a></p>
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

	private function generateCompletionPage()
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

<content id="completionPage">
	<div class="jumbotron">
		<div class="container">
			<div class="nts-secondary-teaser-gradient" style="max-width: 450px; padding: 25px; border-radius: 25px;">
				<h2>Thank you for signing the<br>apprenticeship agreement.</h2>
			</div>
		</div>
	</div>
</content>
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