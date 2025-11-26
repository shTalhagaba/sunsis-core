<?php
class save_sign_app_agreement implements IUnauthenticatedAction
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
                http_redirect('do.php?_action=form_error');
		    }
	    }
	    else
	    {
            http_redirect('do.php?_action=form_error');
	    }

	    if($employer_signature == '')
		    throw new Exception('Missing employer signature');

	    $employer_signature = explode('&', $employer_signature);
	    unset($employer_signature[0]);
	    $employer_signature = implode('&', $employer_signature);

	    $employer_signature_name = isset($_POST['employer_signature_name']) ? $_POST['employer_signature_name'] : '';
	    $employer_signature_date = date("Y-m-d");

	    $update_sql = <<<SQL
UPDATE 
    ob_learners 
SET 
    ob_learners.employer_signature = '{$employer_signature}', 
    ob_learners.employer_signature_name = '{$employer_signature_name}', 
    ob_learners.employer_signature_date = '{$employer_signature_date}' 
WHERE 
    ob_learners.id = '{$ob_learner_id}'
;
SQL;
	    DAO::execute($link, $update_sql);

	    $log = new OnboardingLogger();
	    $log->subject = 'FORM SIGNED BY EMPLOYER';
	    $log->note = "Form is signed by employer contact";
	    $log->ob_learner_id = $ob_learner_id;
	    $log->by_whom = $contact_id;
	    $log->save($link);

        http_redirect('do.php?_action=form_completed');
    }

	private function validateKey($tr_id, $contact_id, $key)
	{
		return $key == md5($tr_id.'_'.$contact_id.'_sunesis');
	}

}