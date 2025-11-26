<?php
class feedback_already_completed implements IUnauthenticatedAction
{
    public function execute(PDO $link)
    {
        $header_image1 = SystemConfig::getEntityValue($link, "ob_header_image1");

        echo <<<HTML
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/html">
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<title>Sunesis | Already Completed</title>
	<meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
	<link rel="stylesheet" href="/assets/adminlte/bootstrap/css/bootstrap.min.css">
	<link rel="stylesheet" href="/assets/adminlte/dist/css/AdminLTE.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.5.0/css/font-awesome.min.css">
	
<body>


<div class="jumbotron text-center">
    <h1 class="display-3">ALERT!</h1>
    <p class="text-center">
        <i class="fa fa-exclamation fa-3x text-danger"></i>
    </p>
    <p class="text-center text-bold">
        Your form is already completed.
    </p>
    <hr>
    <p class="lead">
        <img height="50px" class="headerlogo" src="$header_image1" />
    </p>
    </div>

	
</body>
</html>
HTML;
    }
}
