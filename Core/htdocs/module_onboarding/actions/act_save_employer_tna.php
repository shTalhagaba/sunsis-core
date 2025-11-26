<?php
class save_employer_tna implements IUnauthenticatedAction
{
	public function execute(PDO $link)
	{
		$employer_id = isset($_POST['employer_id'])?$_POST['employer_id']:'';
		$key = isset($_POST['key'])?$_POST['key']:'';

		if(trim($employer_id) != '' && trim($key) != '')
		{
			if(!$this->validateKey($employer_id, $key))
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

		$tna = new EmployerTnaVO();
		$tna->populate($_POST, true);

		if(DAO::saveObjectToTable($link, 'employer_tna', $tna))
		{
			DAO::execute($link, "UPDATE employer_tna SET is_completed = 'Y', completed_date = CURRENT_TIMESTAMP WHERE employer_id = '{$tna->employer_id}'");
		}

		http_redirect("do.php?_action=employer_tna&employer_id={$employer_id}&key=".md5($employer_id."_sunesis_completed"));

	}

	private function validateKey($employer_id, $key)
	{
		return $key == md5($employer_id.'_sunesis');
	}

	private function generateErrorPage()
	{
		echo <<<HTML
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/html">
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<title>Sunesis | Employer Training Needs Analysis</title>
	<meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
	<link rel="stylesheet" href="/assets/adminlte/bootstrap/css/bootstrap.min.css">
	<link rel="stylesheet" href="/assets/adminlte/dist/css/AdminLTE.min.css">
<body>

<nav id="mainNav" class="navbar navbar-default navbar-fixed-top navbar-custom bg-green">
	<div class="container-float">
		<div class="navbar-header page-scroll">
			<a class="navbar-brand" href="#">
				<img height="35px" class="headerlogo" src="images/logos/lead_.png" />
			</a>
		</div>
	</div>
	<div class="text-center" style="margin-top: 5px;"><h3>Employer Training Needs Analysis</h3></div>
</nav>

	<div style="max-width: 450px; padding: 25px; border-radius: 25px; margin-left: 30%; margin-top: 5%;">
		<h2>Invalid Access Credentials</h2><hr>
		<p>The credentials you have supplied are unknown to the system.</p>
		<p>If you are sure that you have used the correct URL as specified in the email you received then contact us at <a style="color: white;" href="mailto:support@perspective-uk.com">support@perspective-uk.com</a>
		and provide the details.</p>
	</div>

	<footer class="main-footer">
		<div class="pull-left">
			<table class="table" style="border-collapse: separate; border-spacing: 0.5em;">
				<tr>
					<td><img width="230px" src="images/logos/lead_.png" /></td>
					<td><img src="images/logos/siemens/ESF.png" /></td>
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