<?php
class sign_app_agreement implements IUnauthenticatedAction
{
    public function execute(PDO $link)
    {
	    // Important: l_id in URL is Training Record ID
	    $tr_id = isset($_REQUEST['l_id'])?$_REQUEST['l_id']:'';
	    $contact_id = isset($_REQUEST['c_id'])?$_REQUEST['c_id']:'';
	    $key = isset($_REQUEST['key'])?$_REQUEST['key']:'';
	    if($tr_id != '' && $contact_id != '' && $key != '')
	    {
		    if(!$this->validateKey($link))
		    {
                http_redirect('do.php?_action=form_error');
		    }
		    $learner = TrainingRecord::loadFromDatabase($link, $tr_id);
		    if(is_null($learner))
		    {
                http_redirect('do.php?_action=form_error');
		    }
		    $ob_learner = DAO::getObject($link, "SELECT ob_learners.* FROM ob_learners INNER JOIN users ON ob_learners.user_id = users.id INNER JOIN tr ON users.username = tr.username WHERE tr.id = '{$learner->id}'");
		    if(is_null($ob_learner))
		    {
                http_redirect('do.php?_action=form_error');
		    }
		    elseif(!is_null($ob_learner->employer_signature))
		    {
                http_redirect('do.php?_action=form_already_completed');
		    }
	    }
	    else
	    {
            http_redirect('do.php?_action=form_error');
	    }

	    $employer_main_site = Location::loadFromDatabase($link, $learner->employer_location_id);

        $header_image1 = SystemConfig::getEntityValue($link, "ob_header_image1");
        $header_image2 = SystemConfig::getEntityValue($link, "ob_header_image2");
        $client_name = SystemConfig::getEntityValue($link, "client_name");


        include_once('tpl_sign_app_agreement.php');
    }

	private function validateKey(PDO $link)
	{
		$tr_id = isset($_REQUEST['l_id'])?$_REQUEST['l_id']:'';
		$contact_id = isset($_REQUEST['c_id'])?$_REQUEST['c_id']:'';
		$key = isset($_REQUEST['key'])?$_REQUEST['key']:'';

		if(md5($tr_id.'_'.$contact_id.'_sunesis_completed') == $key)
			die($this->generateCompletionPage($link));
		else
			return $key == md5($tr_id.'_'.$contact_id.'_sunesis');
	}

}