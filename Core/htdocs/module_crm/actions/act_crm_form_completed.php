<?php
class crm_form_completed implements IUnauthenticatedAction
{
    public function execute(PDO $link)
    {
        $logo = SystemConfig::getEntityValue($link, "logo");
        $page_title = isset($_REQUEST['page_title']) ? $_REQUEST['page_title'] : 'Sunesis';
        $message_type = isset($_REQUEST['message_type']) ? $_REQUEST['message_type'] : 'Success';
        $message = isset($_REQUEST['message']) ? $_REQUEST['message'] : 'Your form is saved successfully.';


        echo <<<HTML
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/html">
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<title>$page_title</title>
	<meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
	<link rel="stylesheet" href="/assets/adminlte/bootstrap/css/bootstrap.min.css">
	<link rel="stylesheet" href="/assets/adminlte/dist/css/AdminLTE.min.css">
<body>


<div class="jumbotron text-center">
  <h1 class="display-3">$message_type!</h1>
  <p class="lead"><strong>$message.</strong></p>
  <hr>
  <p class="lead">
    <img height="50px" class="headerlogo" src="images/logos/$logo" />
  </p>
</div>

HTML;

    }
}