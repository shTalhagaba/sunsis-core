<?php
class duplex_hs_form implements IUnauthenticatedAction
{
    public function execute(PDO $link)
    {
        $key = isset($_REQUEST['key']) ? $_REQUEST['key'] : '';

        if($key == '')
        {
            http_redirect('do.php?_action=crm_form_error');
        }

        $id = DAO::getSingleValue($link, "SELECT id FROM users WHERE MD5(CONCAT('sunesis_', users.id)) = '{$key}'");
        if($id == '')
        {
            http_redirect('do.php?_action=crm_form_error');
        }

        $form_already_completed = DAO::getSingleValue($link, "SELECT COUNT(*) FROM crm_learner_hs_form WHERE learner_id = '{$id}' AND learner_sign != '' ");
        if($form_already_completed > 0)
        {
            http_redirect('do.php?_action=crm_form_already_completed');
        }

        $learner = User::loadFromDatabaseById($link, $id);

        $header_image1 = "images/logos/duplex.png";
        $client_name = "Duplex Business Services";
        $logo1 = "images/logos/imi.jpg";
        $logo2 = "images/logos/wolverhampton_college.jpg";
	$location_address = "Unit 46 Planetary Industrial Estate, Planetary Road, Wednesfield, Wolverhampton WV13 3XA";

        $hs_form = DAO::getObject($link, "SELECT * FROM crm_learner_hs_form WHERE learner_id = '{$learner->id}'");
        if(!isset($hs_form->learner_id))
        {
            $hs_form = new stdClass();
            $records = DAO::getSingleColumn($link, "SHOW COLUMNS FROM crm_learner_hs_form");
            foreach($records AS $_key => $value)
                $hs_form->$value = null;
            $hs_form->learner_id = $learner->id;
        }

	$ruddington_sessions = DAO::getSingleValue($link, "SELECT COUNT(*) FROM crm_training_schedule INNER JOIN training ON crm_training_schedule.`id` = training.`schedule_id` WHERE training.`learner_id` = '{$learner->id}' AND venue = 'Ruddington'");
        $is_ruddington_learner = $ruddington_sessions > 0 ? true : false;
        if($is_ruddington_learner)
        {
            $logo2 = "images/logos/d2n2-logo.jpg";
            $location_address = "Nottingham College, Mere Way, Ruddington Business Park, Ruddington NG11 6JZ";
        }

        include('tpl_duplex_hs_form.php');
    }


}