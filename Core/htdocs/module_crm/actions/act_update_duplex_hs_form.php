<?php

class update_duplex_hs_form implements IAction
{
    public function execute(PDO $link)
    {
        $id = isset($_POST['id']) ? $_POST['id'] : '';
        $learner_id = isset($_POST['learner_id']) ? $_POST['learner_id'] : '';

        $hs_form = DAO::getObject($link, "SELECT * FROM crm_learner_hs_form WHERE learner_id = '{$learner_id}' ");

        if(!isset($hs_form->learner_id))
        {
            $hs_form = new stdClass();
            $records = DAO::getSingleColumn($link, "SHOW COLUMNS FROM crm_learner_hs_form");
            foreach($records AS $key => $value)
                $hs_form->$value = null;
        }

        $hs_form->id = $_POST['id'];
        $hs_form->learner_id = $learner_id;
        $hs_form->date_of_course_attending = isset($_POST['date_of_course_attending']) ? $_POST['date_of_course_attending'] : null;
        $hs_form->s2c1 = isset($_POST['s2c1']) ? $_POST['s2c1'] : 0;
        $hs_form->s2d1 = isset($_POST['s2d1']) ? $_POST['s2d1'] : '';
        $hs_form->s2c2 = isset($_POST['s2c2']) ? $_POST['s2c2'] : 0;
        $hs_form->s2d2 = isset($_POST['s2d2']) ? $_POST['s2d2'] : '';
        $hs_form->s2c3 = isset($_POST['s2c3']) ? $_POST['s2c3'] : 0;
        $hs_form->s2d3 = isset($_POST['s2d3']) ? $_POST['s2d3'] : '';
        $hs_form->s3c1 = isset($_POST['s3c1']) ? $_POST['s3c1'] : 0;
        $hs_form->s3c2 = isset($_POST['s3c2']) ? $_POST['s3c2'] : 0;
        $hs_form->s3c3 = isset($_POST['s3c3']) ? $_POST['s3c3'] : 0;
        $hs_form->s3c4 = isset($_POST['s3c4']) ? $_POST['s3c4'] : 0;
        $hs_form->s3c5 = isset($_POST['s3c5']) ? $_POST['s3c5'] : 0;
        $hs_form->s4c1 = isset($_POST['s4c1']) ? $_POST['s4c1'] : 0;
        $hs_form->s4c2 = isset($_POST['s4c2']) ? $_POST['s4c2'] : 0;
        $hs_form->gdpr1 = isset($_POST['gdpr1']) ? $_POST['gdpr1'] : 0;
        $hs_form->gdpr2 = isset($_POST['gdpr2']) ? $_POST['gdpr2'] : 0;
        $hs_form->is_completed = $hs_form->learner_sign != '' ? 1 : 0;
        $hs_form->s3c6 = isset($_POST['s3c6']) ? $_POST['s3c6'] : 0;
        $hs_form->s3c6_detail = isset($_POST['s3c6_detail']) ? substr($_POST['s3c6_detail'], 0, 499) : null;

        DAO::saveObjectToTable($link, "crm_learner_hs_form", $hs_form);

        $learner = User::loadFromDatabaseById($link, $learner_id);
        $learner->job_role = isset($_POST['job_role']) ? $_POST['job_role'] : '';
        $learner->home_postcode = isset($_POST['home_postcode']) ? $_POST['home_postcode'] : '';
        $learner->home_email = isset($_POST['home_email']) ? $_POST['home_email'] : '';
        $learner->home_mobile = isset($_POST['home_mobile']) ? $_POST['home_mobile'] : '';
        $learner->save($link);

        http_redirect('do.php?_action=read_learner&username='.$learner->username.'&id='.$learner->id);
    }
}