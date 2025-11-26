<?php
class already_completed implements IUnauthenticatedAction
{
    public function execute(PDO $link)
    {
        $k = isset($_REQUEST['k']) ? $_REQUEST['k'] : '';
        if($k == '')
            http_redirect('do.php?_action=form_error');

        $valid = DAO::getSingleValue($link, "SELECT ob_learners.id FROM ob_learners WHERE MD5(CONCAT('sunesis_already_completed_for_',ob_learners.id)) = '{$k}'");
        if($valid == '')
        {
            http_redirect('do.php?_action=form_error');
            exit;
        }

        //OnboardingHelper::generateCompletionPage($link, $valid);

        $header_image1 = SystemConfig::getEntityValue($link, "ob_header_image1");

        echo <<<HTML
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/html">
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<title>Sunesis | Onboarding</title>
	<meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
	<link rel="stylesheet" href="/assets/adminlte/bootstrap/css/bootstrap.min.css">
	<link rel="stylesheet" href="/assets/adminlte/dist/css/AdminLTE.min.css">
<body>


<div class="jumbotron text-center">
  <h1 class="display-3">Thank You!</h1>
  <p class="lead"><strong>Your information has been saved successfully.</strong></p>
  <hr>
  <p class="lead">
    <img height="50px" class="headerlogo" src="$header_image1" />
  </p>
</div>

HTML;

    }
}