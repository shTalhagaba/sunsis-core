<?php
class onboarding implements IUnauthenticatedAction
{
	public function execute(PDO $link)
	{
		// Important: id in URL is Training Record ID
		$id = isset($_REQUEST['id'])?$_REQUEST['id']:(isset($_POST['id'])?$_POST['id']:'');
		$key = isset($_REQUEST['key'])?$_REQUEST['key']:(isset($_POST['key'])?$_POST['key']:'');

		$invalid = false;

		if($id != '' && $key != '')
		{
			if(!$this->validateKey($link))
			{
				$this->generateErrorPage($link);
				exit;
			}
			$learner = TrainingRecord::loadFromDatabase($link, $id);
			if(is_null($learner))
			{
				$this->generateErrorPage($link);
				exit;
			}
			$ob_learner = DAO::getObject($link, "SELECT ob_learners.* FROM ob_learners INNER JOIN users ON ob_learners.user_id = users.id INNER JOIN tr ON users.username = tr.username WHERE tr.id = '{$learner->id}'");
			if(is_null($ob_learner))
			{
				$this->generateErrorPage($link);
				exit;
			}
			elseif($ob_learner->is_finished == 'Y')
			{
				$this->generateAlreadyCompletedPage($link);
				exit;
			}
			if(isset($_POST['dob']))
			{
				$dob = Date::toMySQL($_POST['dob']);
				$dob = $link->quote($dob);
				$found = DAO::getSingleValue($link, "SELECT COUNT(*) FROM tr WHERE tr.id = '$id' AND tr.dob = $dob");
				if($found > 0)
				{
					http_redirect('do.php?_action=onboarded&id='.$id.'&key='.$key);
				}
				else
				{
					$invalid = true;
				}
			}
		}
		else
		{
			pre($id . $key);
			$this->generateErrorPage();
			exit;
		}

		$username = DAO::getSingleValue($link, "SELECT username FROM users WHERE users.id = '{$ob_learner->user_id}'");

		$header_image1 = SystemConfig::getEntityValue($link, "ob_header_image1");
		$header_image2 = SystemConfig::getEntityValue($link, "ob_header_image2");

		include_once('tpl_onboarding.php');
	}

	private function validateKey(PDO $link)
	{
		$id = isset($_REQUEST['id'])?$_REQUEST['id']:'';
		$key = isset($_REQUEST['key'])?$_REQUEST['key']:'';

		if(md5($id.'_sunesis_completed') == $key)
			die($this->generateCompletionPage($link));
		else
			return $key == md5($id.'_sunesis');
	}

	private function generateErrorPage(PDO $link)
	{
		$header_image1 = SystemConfig::getEntityValue($link, "ob_header_image1");
		if(DB_NAME == "am_demo")
			$header_image1 = "images/logos/SUNlogo.png";

		echo <<<HTML
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/html" xmlns="http://www.w3.org/1999/html" xmlns="http://www.w3.org/1999/html"
      xmlns="http://www.w3.org/1999/html" xmlns="http://www.w3.org/1999/html">
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<title>Sunesis | Homepage</title>
	<meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
	<link rel="stylesheet" href="/assets/adminlte/bootstrap/css/bootstrap.min.css">
	<link rel="stylesheet" href="/assets/adminlte/dist/css/AdminLTE.min.css">
	<link rel="stylesheet" href="/module_onboarding/css/onboarding.css">
<body>

<nav id="mainNav" class="navbar navbar-default navbar-fixed-top navbar-custom">
	<div class="container">
		<div class="navbar-header page-scroll">
			<a class="navbar-brand" href="#">
				<img height="50px" class="headerlogo" src="$header_image1" />
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

	private function generateAlreadyCompletedPage(PDO $link)
	{
		$header_image1 = SystemConfig::getEntityValue($link, "ob_header_image1");
		if(DB_NAME == "am_demo")
			$header_image1 = "images/logos/SUNlogo.png";

		echo <<<HTML
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/html" xmlns="http://www.w3.org/1999/html" xmlns="http://www.w3.org/1999/html"
      xmlns="http://www.w3.org/1999/html" xmlns="http://www.w3.org/1999/html">
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<title>Sunesis | Homepage</title>
	<meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
	<link rel="stylesheet" href="/assets/adminlte/bootstrap/css/bootstrap.min.css">
	<link rel="stylesheet" href="/assets/adminlte/dist/css/AdminLTE.min.css">
	<link rel="stylesheet" href="/module_onboarding/css/onboarding.css">
<body>

<nav id="mainNav" class="navbar navbar-default navbar-fixed-top navbar-custom">
	<div class="container">
		<div class="navbar-header page-scroll">
			<a class="navbar-brand" href="#">
				<img height="50px" class="headerlogo" src="$header_image1" />
			</a>
		</div>
	</div>
</nav>

	<div class="nts-secondary-teaser-gradient" style="max-width: 450px; padding: 25px; border-radius: 25px; margin-left: 30%">
		<h2>Your form is already completed</h2><hr>
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

	private function generateCompletionPage(PDO $link)
	{
		$header_image1 = SystemConfig::getEntityValue($link, "ob_header_image1");
		if(DB_NAME == "am_demo")
			$header_image1 = "images/logos/SUNlogo.png";

		echo <<<HTML
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/html" xmlns="http://www.w3.org/1999/html" xmlns="http://www.w3.org/1999/html"
      xmlns="http://www.w3.org/1999/html" xmlns="http://www.w3.org/1999/html">
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<title>Siemens | Onboarding</title>
	<meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
	<link rel="stylesheet" href="/assets/adminlte/bootstrap/css/bootstrap.min.css">
	<link rel="stylesheet" href="/assets/adminlte/dist/css/AdminLTE.min.css">
	<link rel="stylesheet" href="/module_onboarding/css/onboarding.css">
<body>

<nav id="mainNav" class="navbar navbar-default navbar-fixed-top navbar-custom">
	<div class="container">
		<div class="navbar-header page-scroll">
			<a class="navbar-brand" href="#">
				<img height="50px" class="headerlogo" src="$header_image1" />
			</a>
		</div>
	</div>
</nav>

<content id="completionPage">
	<div class="jumbotron">
		<div class="container">
			<div class="nts-secondary-teaser-gradient" style="max-width: 450px; padding: 25px; border-radius: 25px;">
				<h2>Thank you for completing your<br>apprenticeship details. We look<br>forward to you starting in<br>September!</h2>
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