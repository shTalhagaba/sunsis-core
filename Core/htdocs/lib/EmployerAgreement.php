<?php


class EmployerAgreement extends Entity
{
    /**
     * @static
     * @param PDO $link
     * @param $id
     * @return EmployerAgreement
     * @throws DatabaseException
     */
    public static function loadFromDatabase(PDO $link, $id)
    {
        $agreement = null;
        if($id != '' && is_numeric($id))
        {
            $query = "SELECT * FROM employer_agreements WHERE id = " . addslashes((string)$id) . ";";
            $st = $link->query($query);

            if($st)
            {
                $row = $st->fetch();
                if($row)
                {
                    $agreement = new EmployerAgreement();
                    $agreement->populate($row);
                }
            }
            else
            {
                throw new DatabaseException($link, $query);
            }
        }

        return $agreement;
    }

    public function save(PDO $link)
    {
        if($this->id == '')
        {
            $this->status = self::TYPE_CREATED;
            $this->created = date('Y-m-d H:i:s');
            $this->created_by = $_SESSION['user']->id;
        }

        $this->sort_code = substr($this->sort_code, 0, 9);
        $this->updated_at = date('Y-m-d H:i:s');

        return DAO::saveObjectToTable($link, 'employer_agreements', $this);
    }

    public function delete(PDO $link)
    {
        if(!$_SESSION['user']->isAdmin() && $_SESSION['user']->type != User::TYPE_ADMIN)
        {
            throw new Exception("You are not authorised to perform this action.");
        }
        if(in_array($this->status, [self::TYPE_SENT, self::TYPE_SIGNED_BY_EMPLOYER]))
        {
            throw new Exception("This agreement cannot be deleted.");
        }

        DAO::execute($link, "DELETE FROM employer_agreements WHERE id = '{$this->id}'");

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
	<title>Sunesis | Employer Agreement</title>
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

    public static function generateCompletionPage(PDO $link, $agreement_id = '')
    {
        $logo = '';
        if($agreement_id != '')
        {
            $sql = <<<SQL
SELECT provider_logo 
FROM organisations INNER JOIN users ON organisations.id = users.employer_id 
INNER JOIN employer_agreements ON users.id = employer_agreements.tp_rep
WHERE employer_agreements.id = '{$agreement_id}'
SQL;
            $logo = DAO::getSingleValue($link, $sql);
        }
        if($logo == '' || DB_NAME == "am_onboarding")
            $logo = "images/logos/" . SystemConfig::getEntityValue($link, 'logo');

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
                <img height="50px" class="headerlogo" src="$logo" />
              </p>
            </div>
            </body>
            </html>
HTML;

    }

    public static function generateAlreadyCompleted(PDO $link, $agreement_id = '')
    {
        $logo = '';
        if($agreement_id != '')
        {
            $sql = <<<SQL
SELECT provider_logo 
FROM organisations INNER JOIN users ON organisations.id = users.employer_id 
INNER JOIN employer_agreements ON users.id = employer_agreements.tp_rep
WHERE employer_agreements.id = '{$agreement_id}'
SQL;
            $logo = DAO::getSingleValue($link, $sql);
        }
        if($logo == '')
            $logo = "images/logos/" . SystemConfig::getEntityValue($link, 'logo');

        echo <<<HTML
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/html">
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<title>Sunesis | Employer Agreement</title>
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

    public static function generatePdf(PDO $link, EmployerAgreement $agreement)
    {
        $employer_agreement_class = SystemConfig::getEntityValue($link, 'class_employer_agreement');
        include_once(__DIR__ . '/OnboardingDocuments/' . $employer_agreement_class . '.php');
        $employer_agreement_class::generatePdf($link, $agreement);
        return;
        
    }

    public $id = NULL;
    public $employer_id = NULL;
    public $status = NULL;
    public $employer_type = NULL;
    public $employer_rep = NULL;
    public $finance_contact = NULL;
    public $levy_contact = NULL;
    public $tp_rep = NULL;
    public $expiry_date = NULL;
    public $admin_service = NULL;
    public $avg_no_of_employees = NULL;
    public $bank_name = NULL;
    public $account_name = NULL;
    public $sort_code = NULL;
    public $account_number = NULL;
    public $created_by = NULL;
    public $created = NULL;
    public $modified = NULL;
    public $funding_type = NULL;
    public $signed_by_uploaded_file = NULL;
    public $signed_file_name = NULL;
    public $file_upload = NULL;
    public $finance_contact_name = NULL;
    public $finance_contact_email = NULL;
    public $finance_contact_telephone = NULL;
    public $levy_contact_name = NULL;
    public $levy_contact_email = NULL;
    public $levy_contact_telephone = NULL;
    public $updated_at = NULL;
    public $locations = NULL;
    public $agreement_number = NULL;
    public $company_number = NULL;

    public $employer_sign = null;
    public $employer_sign_name = null;
    public $employer_sign_date = null;
    public $provider_sign = null;
    public $provider_sign_name = null;
    public $provider_sign_date = null;
    public $provider_sign_id = null;

    const TYPE_NOT_STARTED = 0;
    const TYPE_CREATED = 1;
    const TYPE_SENT = 2;
    const TYPE_SIGNED_BY_EMPLOYER = 3;
    const TYPE_COMPLETED = 4;
}