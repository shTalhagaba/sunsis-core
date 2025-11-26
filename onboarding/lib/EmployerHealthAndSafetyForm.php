<?php


class EmployerHealthAndSafetyForm extends Entity
{
    public function save(PDO $link)
    {
        return DAO::saveObjectToTable($link, 'health_safety_form', $this);
    }

    public function delete(PDO $link)
    {
        if(!$_SESSION['user']->isAdmin() && $_SESSION['user']->type != User::TYPE_ADMIN)
        {
            throw new Exception("You are not authorised to perform this action.");
        }
        if(in_array($this->status, [self::TYPE_SENT, self::TYPE_SIGNED_BY_EMPLOYER]))
        {
            throw new Exception("This form cannot be deleted.");
        }

        DAO::execute($link, "DELETE FROM health_safety_form WHERE hs_id = '{$this->hs_id}'");

        return true;
    }

    public static function validateKey(PDO $link, $id, $employer_id, $key)
    {
        if(md5($id.'_'.$employer_id.'_sunesis_completed') == $key)
            die(self::generateCompletionPage($link));
        else
            return $key == md5($id.'_'.$employer_id.'_sunesis');
    }
    
    public static function generateErrorPage(PDO $link)
    {
        $logo = "images/logos/" . SystemConfig::getEntityValue($link, 'logo');
        echo <<<HTML
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/html">
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<title>Sunesis | Employer Health & Safety Form</title>
	<meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
	<link rel="stylesheet" href="/assets/adminlte/bootstrap/css/bootstrap.min.css">
	<link rel="stylesheet" href="/assets/adminlte/dist/css/AdminLTE.min.css">
<body>

<nav id="mainNav" class="navbar navbar-default navbar-fixed-top navbar-custom" style="background-color: #BD1730;background-image: linear-gradient(to left, #BD1730, #9D8D8F)">
	<div class="container-float">
		<div class="navbar-header page-scroll">
			<a class="navbar-brand" href="#">
				<img height="35px" class="headerlogo" src="$logo" />
			</a>
		</div>
	</div>
	
</nav>

	<div style="max-width: 450px; padding: 25px; border-radius: 25px; margin-left: 30%; margin-top: 5%;">
		<h2>Invalid Access Credentials</h2><hr>
		<p>The credentials you have supplied are unknown to the system. This may be because you have already completed and submitted the information.</p>
		<p>If you are sure that you have used the correct URL as specified in the email you received then contact us at <a href="mailto:support@perspective-uk.com">support@perspective-uk.com</a>
		and provide the details.</p>
	</div>

	<footer class="main-footer">
		<div class="pull-left">
			<table class="table" style="border-collapse: separate; border-spacing: 0.5em;">
				<tr>
					<td><img width="230px" src="$logo" /></td>
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

    public static function generateCompletionPage(PDO $link, $hs_id = '')
    {
        $logo = '';
        if($logo == '' || DB_NAME == "am_onboarding")
            $logo = "images/logos/" . SystemConfig::getEntityValue($link, 'logo');

            echo <<<HTML

            <!DOCTYPE html>
            <html xmlns="http://www.w3.org/1999/html">
            <head>
                <meta charset="utf-8">
                <meta http-equiv="X-UA-Compatible" content="IE=edge">
                <title>Sunesis | Health & Safety Form</title>
                <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
                <link rel="stylesheet" href="/assets/adminlte/bootstrap/css/bootstrap.min.css">
                <link rel="stylesheet" href="/assets/adminlte/dist/css/AdminLTE.min.css">
            <body>
            
            
            <div class="jumbotron text-center">
              <h1 class="display-3">Thank You!</h1>
              <p class="lead"><strong>Your information has been saved successfully.</strong></p>
              <hr>
              <p class="lead">
                <img height="50px" class="headerlogo" src="$logo" />
              </p>
            </div>
            </body>
            </html>
HTML;

    }

    public static function generateAlreadyCompleted(PDO $link, $hs_id = '')
    {
        $logo = '';
        
        if($logo == '')
            $logo = "images/logos/" . SystemConfig::getEntityValue($link, 'logo');

        echo <<<HTML
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/html">
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<title>Sunesis | Health & Safety Form</title>
	<meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
	<link rel="stylesheet" href="/assets/adminlte/bootstrap/css/bootstrap.min.css">
	<link rel="stylesheet" href="/assets/adminlte/dist/css/AdminLTE.min.css">
    
    <style>
    .img {
        width: 100px; /* You can set the dimensions to whatever you want */
        height: 100px;
        object-fit: contain ;
    }
</style>
<body>

    <div class="jumbotron text-center">
    <h1 class="display-3">Thank You!</h1>
    <p class="lead"><strong>Your form has already been completed.</strong></p>
    <hr>
    <p class="lead">
        <img height="50px" class="headerlogo" src="$logo" />
    </p>
    </div>
</body>
</html>
HTML;

    }

    public function getStatusDesc()
    {
        switch ($this->status)
        {
            case self::TYPE_NOT_STARTED:
                return 'NOT CREATED';
            case self::TYPE_CREATED:
                return 'CREATED';
            case self::TYPE_SENT:
                return 'EMAILED TO EMPLOYER';
            case self::TYPE_SIGNED_BY_EMPLOYER:
                return 'SIGNED BY EMPLOYER';
            case self::TYPE_COMPLETED:
                return 'COMPLETED';
            default:
                return $this->status;
        }
    }

    public static function generateEmployerHealthAndSafetyUrl($id, $employer_id)
    {
        return OnboardingHelper::getCurrentScriptUrl()."?_action=sign_employer_hs_form&id={$id}&employer_id={$employer_id}&key=".md5($id.'_'.$employer_id.'_sunesis');
    }

    
    public $hs_id = NULL;
    public $detail = NULL;
    public $outcome = NULL;
    public $nature_of_business = NULL;





    const TYPE_NOT_STARTED = 0;
    const TYPE_CREATED = 1;
    const TYPE_SENT = 2;
    const TYPE_SIGNED_BY_EMPLOYER = 3;
    const TYPE_COMPLETED = 4;
}