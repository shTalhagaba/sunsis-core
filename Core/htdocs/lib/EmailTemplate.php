<?php
class EmailTemplate
{
    public function prepare(PDO $link, $template_type, User $user, $schedule_id = '')
    {
        $template = '';
        if($template_type == '')
        {
            return $template;
        }

        $template = DAO::getSingleValue($link, "SELECT template FROM email_templates WHERE template_type = '{$template_type}'");
        if($template == '')
        {
            return $template;
        }

        $class = new ReflectionClass(__CLASS__);
        $methods = $class->getMethods();
        foreach($methods AS $method)
        {
            $method_name = $method->getName();
            if(substr($method_name, 0, 7) != 'replace')
                continue;

            $template = $this->$method_name($link, $template, $user, $template_type, $schedule_id);
        }

        return $template;
    }

    private function replaceCommonKeyWords(PDO $link, $template)
    {
        $template = str_replace('$$CLIENT_EMAIL$$', SystemConfig::getEntityValue($link, 'client_email'), $template);
        $template = str_replace('$$CLIENT_TELEPHONE$$', SystemConfig::getEntityValue($link, 'client_telephone'), $template);
	$template = str_replace('$$CLIENT_WEB_ADDRESS$$', SystemConfig::getEntityValue($link, 'client_web_address'), $template);

        $template = str_replace('$$LOGO$$', '<img title="Perspective" src="'.SystemConfig::getEntityValue($link, 'ob_header_image1').'" alt="Perspective" style="width: 100px;" />', $template);

        return $template;
    }

    private function replaceLearnerInformation(PDO $link, $template, User $user)
    {
        $template = str_replace('$$LEARNER_FIRSTNAME$$', $user->firstnames, $template);
        $template = str_replace('$$OB_LEARNER_NAME$$', $user->firstnames . ' ' . $user->surname, $template);
        $template = str_replace('$$LEARNER_FULL_NAME$$', $user->firstnames . ' ' . $user->surname, $template);

        return $template;
    }

    private function replaceImiRedeemCode(PDO $link, $template, User $user)
    {
        $template = str_replace('$$IMI_REDEEM_CODE$$', $user->imi_redeem_code, $template);

        return $template;
    }

    private function replaceTrainerInfo(PDO $link, $template, User $user)
    {
        $template = str_replace('$$TRAINER_NAME$$', DAO::getSingleValue($link, "SELECT CONCAT(firstnames, ' ', surname) FROM users WHERE users.id = '{$user->trainer}'"), $template);

        return $template;
    }

    private function replaceEmployerInformation(PDO $link, $template, User $user)
    {
        $employer = Employer::loadFromDatabase($link, $user->employer_id);
        $employer_location = Location::loadFromDatabase($link, $user->employer_location_id);

        $address = $employer_location->address_line_1 . ' ' . $employer_location->address_line_2 . ' ' . $employer_location->address_line_3 . ' ' . $employer_location->address_line_4 . ' ' . $employer_location->postcode;

        $template = str_replace('$$EMPLOYER_NAME$$', $employer->legal_name, $template);
        $template = str_replace('$$EMPLOYER_ADDRESS$$', $address, $template);

	$organisation_contact = DAO::getObject($link, "SELECT * FROM organisation_contact WHERE org_id = '{$employer->id}' LIMIT 1");
        if(isset($organisation_contact->contact_email))
        {
            $template = str_replace('$$CRM_EMPLOYER_CONTACT$$', $organisation_contact->contact_title . ' ' . $organisation_contact->contact_name, $template);
        }

        return $template;
    }

    private function replaceHsUrl(PDO $link, $template, User $user)
    {
        $key = md5('sunesis_'.$user->id);
        $u1 = "https://www.cognitoforms.com/DuplexManagement1/ElectricVehicleHybridTrainingLevel3";
        $u2 = "https://www.cognitoforms.com/DuplexManagement1/ElectricVehicleHybridTrainingLevel4";
        $template = str_replace($u1, "https://duplex.sunesis.uk.net/do.php?_action=duplex_hs_form&level=3&key=".$key, $template);
        $template = str_replace($u2, "https://duplex.sunesis.uk.net/do.php?_action=duplex_hs_form&level=4&key=".$key, $template);
	$template = str_replace('$$HS_FORM_LINK$$', "https://duplex.sunesis.uk.net/do.php?_action=duplex_hs_form&key=".$key, $template);

	// replace logo for ruddington
        $ruddington_sessions = DAO::getSingleValue($link, "SELECT COUNT(*) FROM crm_training_schedule INNER JOIN training ON crm_training_schedule.`id` = training.`schedule_id` WHERE training.`learner_id` = '{$user->id}' AND venue = 'Ruddington'");
        if($ruddington_sessions > 0)
        {
            $template = str_replace('city_of_wolverhampton_college.png', 'd2n2-logo.jpg', $template);
        }

        return $template;
    }

    private function replaceTrainingDates(PDO $link, $template, User $user, $template_type, $schedule_id = '')
    {
        $scheudle = '';
        /*
        if(in_array($template_type, ["LEVEL3_JOIN_INST", "LEVEL3_REMINDER_1_WEEK_TO_GO", "LEVEL3_LOOKING_FORWARD_1_DAY_TO_GO"]))
            $scheudle = DAO::getObject($link, "SELECT * FROM crm_training_schedule WHERE FIND_IN_SET($user->id, learner_ids) AND level = 'L3'");
        if(in_array($template_type, ["LEVEL4_JOIN_INST", "LEVEL4_REMINDER_1_WEEK_TO_GO", "LEVEL4_LOOKING_FORWARD_1_DAY_TO_GO"]))
            $scheudle = DAO::getObject($link, "SELECT * FROM crm_training_schedule WHERE FIND_IN_SET($user->id, learner_ids) AND level = 'L4'");
        */
	if ($schedule_id == '')
    {
        if(in_array($template_type, ["LEVEL3_JOIN_INST", "LEVEL3_REMINDER_1_WEEK_TO_GO", "LEVEL3_LOOKING_FORWARD_1_DAY_TO_GO", "LEVEL3_WMP_JOIN_INST", "LEVEL3_EL_REMINDER_VOCANTO"]))
            $scheudle = DAO::getObject($link, "SELECT crm_training_schedule.* FROM crm_training_schedule INNER JOIN training ON crm_training_schedule.`id` = training.`schedule_id` WHERE crm_training_schedule.`level` = 'L3' AND training.`learner_id` = '{$user->id}' AND crm_training_schedule.`venue` != 'Ruddington' LIMIT 1");
        if(in_array($template_type, ["LEVEL4_JOIN_INST", "LEVEL4_REMINDER_1_WEEK_TO_GO", "LEVEL4_LOOKING_FORWARD_1_DAY_TO_GO", "LEVEL4_WMP_JOIN_INST", "LEVEL4_EL_REMINDER_VOCANTO"]))
            $scheudle = DAO::getObject($link, "SELECT crm_training_schedule.* FROM crm_training_schedule INNER JOIN training ON crm_training_schedule.`id` = training.`schedule_id` WHERE crm_training_schedule.`level` = 'L4' AND training.`learner_id` = '{$user->id}' AND crm_training_schedule.`venue` != 'Ruddington' LIMIT 1");
        if(in_array($template_type, ["LEVEL3_JOIN_INST_RUDDINGTON", "LEVEL3_REMINDER_1_WEEK_TO_GO_RUDDINGTON", "LEVEL3_LOOKING_FORWARD_1_DAY_TO_GO_RUDDINGTON", "LEVEL3_EL_REMINDER_VOCANTO_RUDDINGTON"]))
            $scheudle = DAO::getObject($link, "SELECT crm_training_schedule.* FROM crm_training_schedule INNER JOIN training ON crm_training_schedule.`id` = training.`schedule_id` WHERE crm_training_schedule.`level` = 'L3' AND training.`learner_id` = '{$user->id}' AND crm_training_schedule.`venue` = 'Ruddington' LIMIT 1");
        if(in_array($template_type, ["LEVEL4_JOIN_INST_RUDDINGTON", "LEVEL4_REMINDER_1_WEEK_TO_GO_RUDDINGTON", "LEVEL4_LOOKING_FORWARD_1_DAY_TO_GO_RUDDINGTON", "LEVEL4_EL_REMINDER_VOCANTO_RUDDINGTON"]))
            $scheudle = DAO::getObject($link, "SELECT crm_training_schedule.* FROM crm_training_schedule INNER JOIN training ON crm_training_schedule.`id` = training.`schedule_id` WHERE crm_training_schedule.`level` = 'L4' AND training.`learner_id` = '{$user->id}' AND crm_training_schedule.`venue` = 'Ruddington' LIMIT 1");
	if(in_array($template_type, ["LEVEL2_JOIN_INST_RUDDINGTON", "LEVEL2_REMINDER_1_WEEK_TO_GO_RUDDINGTON", "LEVEL2_LOOKING_FORWARD_1_DAY_TO_GO_RUDDINGTON"]))
                $scheudle = DAO::getObject($link, "SELECT crm_training_schedule.* FROM crm_training_schedule INNER JOIN training ON crm_training_schedule.`id` = training.`schedule_id` WHERE crm_training_schedule.`level` = 'L2' AND training.`learner_id` = '{$user->id}' LIMIT 1");

	if(in_array($template_type, ["WOLVERHAMPTON_LEVEL_3_REMINDER", "WOLVERHAMPTON_LEVEL_3_JOIN_INST_NO_IMI", "WOLVERHAMPTON_LEVEL_3_JOIN_INST_WITH_IMI"]))
                $scheudle = DAO::getObject($link, "SELECT crm_training_schedule.* FROM crm_training_schedule INNER JOIN training ON crm_training_schedule.`id` = training.`schedule_id` WHERE crm_training_schedule.`level` = 'L3' AND training.`learner_id` = '{$user->id}' AND crm_training_schedule.`venue` = 'Wolverhampton' LIMIT 1");            
            if(in_array($template_type, ["WOLVERHAMPTON_LEVEL_4_REMINDER", "WOLVERHAMPTON_LEVEL_4_JOIN_INST_NO_IMI", "WOLVERHAMPTON_LEVEL_4_JOIN_INST_WITH_IMI"]))
                $scheudle = DAO::getObject($link, "SELECT crm_training_schedule.* FROM crm_training_schedule INNER JOIN training ON crm_training_schedule.`id` = training.`schedule_id` WHERE crm_training_schedule.`level` = 'L4' AND training.`learner_id` = '{$user->id}' AND crm_training_schedule.`venue` = 'Wolverhampton' LIMIT 1");            

            if(in_array($template_type, ["RUDDINGTON_LEVEL_3_JOIN_INST_NO_IMI", "RUDDINGTON_LEVEL_3_JOIN_INST_WITH_IMI", "RUDDINGTON_LEVEL_3_REMINDER"]))
                $scheudle = DAO::getObject($link, "SELECT crm_training_schedule.* FROM crm_training_schedule INNER JOIN training ON crm_training_schedule.`id` = training.`schedule_id` WHERE crm_training_schedule.`level` = 'L3' AND training.`learner_id` = '{$user->id}' AND crm_training_schedule.`venue` = 'Ruddington' LIMIT 1");            
            if(in_array($template_type, ["RUDDINGTON_LEVEL_4_JOIN_INST_NO_IMI", "RUDDINGTON_LEVEL_4_JOIN_INST_WITH_IMI", "RUDDINGTON_LEVEL_4_REMINDER"]))
                $scheudle = DAO::getObject($link, "SELECT crm_training_schedule.* FROM crm_training_schedule INNER JOIN training ON crm_training_schedule.`id` = training.`schedule_id` WHERE crm_training_schedule.`level` = 'L4' AND training.`learner_id` = '{$user->id}' AND crm_training_schedule.`venue` = 'Ruddington' LIMIT 1");            

            if(in_array($template_type, ["LINCOLN_LEVEL_3_JOIN_INST_NO_IMI", "LINCOLN_LEVEL_3_JOIN_INST_WITH_IMI", "LINCOLN_LEVEL_3_REMINDER"]))
                $scheudle = DAO::getObject($link, "SELECT crm_training_schedule.* FROM crm_training_schedule INNER JOIN training ON crm_training_schedule.`id` = training.`schedule_id` WHERE crm_training_schedule.`level` = 'L3' AND training.`learner_id` = '{$user->id}' AND crm_training_schedule.`venue` = 'Lincoln Training Academy' LIMIT 1");            
            if(in_array($template_type, ["LINCOLN_LEVEL_4_JOIN_INST_NO_IMI", "LINCOLN_LEVEL_4_JOIN_INST_WITH_IMI", "LINCOLN_LEVEL_4_REMINDER"]))
                $scheudle = DAO::getObject($link, "SELECT crm_training_schedule.* FROM crm_training_schedule INNER JOIN training ON crm_training_schedule.`id` = training.`schedule_id` WHERE crm_training_schedule.`level` = 'L4' AND training.`learner_id` = '{$user->id}' AND crm_training_schedule.`venue` = 'Lincoln Training Academy' LIMIT 1");            

	// New Templates - 2024
        if(in_array($template_type, ["Master_Level_4_WOLVES_JI"]))
            $scheudle = DAO::getObject($link, "SELECT crm_training_schedule.* FROM crm_training_schedule INNER JOIN training ON crm_training_schedule.`id` = training.`schedule_id` WHERE crm_training_schedule.`level` = 'L4' AND training.`learner_id` = '{$user->id}' AND crm_training_schedule.`venue` = 'Wolverhampton' LIMIT 1");            
        if(in_array($template_type, ["Master_Level_3_WOLVES_JI"]))
            $scheudle = DAO::getObject($link, "SELECT crm_training_schedule.* FROM crm_training_schedule INNER JOIN training ON crm_training_schedule.`id` = training.`schedule_id` WHERE crm_training_schedule.`level` = 'L3' AND training.`learner_id` = '{$user->id}' AND crm_training_schedule.`venue` = 'Wolverhampton' LIMIT 1");            
        if(in_array($template_type, ["Master_Level_4_Nottingham_JI"]))
            $scheudle = DAO::getObject($link, "SELECT crm_training_schedule.* FROM crm_training_schedule INNER JOIN training ON crm_training_schedule.`id` = training.`schedule_id` WHERE crm_training_schedule.`level` = 'L4' AND training.`learner_id` = '{$user->id}' AND crm_training_schedule.`venue` = 'Ruddington' LIMIT 1");            
        if(in_array($template_type, ["Master_Level_3_Nottingham_JI"]))
            $scheudle = DAO::getObject($link, "SELECT crm_training_schedule.* FROM crm_training_schedule INNER JOIN training ON crm_training_schedule.`id` = training.`schedule_id` WHERE crm_training_schedule.`level` = 'L3' AND training.`learner_id` = '{$user->id}' AND crm_training_schedule.`venue` = 'Ruddington' LIMIT 1");            
        if(in_array($template_type, ["Master_Level_4_Lincoln_JI"]))
            $scheudle = DAO::getObject($link, "SELECT crm_training_schedule.* FROM crm_training_schedule INNER JOIN training ON crm_training_schedule.`id` = training.`schedule_id` WHERE crm_training_schedule.`level` = 'L4' AND training.`learner_id` = '{$user->id}' AND crm_training_schedule.`venue` = 'Lincoln Training Academy' LIMIT 1");            
        if(in_array($template_type, ["Master_Level_3_Lincoln_JI"]))
            $scheudle = DAO::getObject($link, "SELECT crm_training_schedule.* FROM crm_training_schedule INNER JOIN training ON crm_training_schedule.`id` = training.`schedule_id` WHERE crm_training_schedule.`level` = 'L3' AND training.`learner_id` = '{$user->id}' AND crm_training_schedule.`venue` = 'Lincoln Training Academy' LIMIT 1");           


	}
	else
	{
		$scheudle = DAO::getObject($link, "SELECT * FROM crm_training_schedule WHERE id = '{$schedule_id}'");
	}

        if(isset($scheudle->id))
        {
            $template = str_replace('$$TRAINING_START_DATE$$', Date::toShort($scheudle->training_date), $template);
            $template = str_replace('$$TRAINING_END_DATE$$', Date::toShort($scheudle->training_end_date), $template);
            $template = str_replace('$$TRAINING_DURATION$$', $scheudle->duration, $template);

            $l3_join_email_sent_date = DAO::getSingleValue($link, "SELECT DATE_FORMAT(created, '%d/%m/%Y') FROM emails WHERE emails.email_type = '2' AND entity_type = 'sunesis_learner' AND entity_id = '{$user->id}' ORDER BY id DESC LIMIT 1");
            $template = str_replace('$$LEVEL3_JOIN_EMAIL_SENT_DATE$$', $l3_join_email_sent_date, $template);

            $l4_join_email_sent_date = DAO::getSingleValue($link, "SELECT DATE_FORMAT(created, '%d/%m/%Y') FROM emails WHERE emails.email_type = '1' AND entity_type = 'sunesis_learner' AND entity_id = '{$user->id}' ORDER BY id DESC LIMIT 1");
            $template = str_replace('$$LEVEL4_JOIN_EMAIL_SENT_DATE$$', $l4_join_email_sent_date, $template);

	    $template = str_replace('$$TRAINING_START_DATE_OTHER_FORMAT$$', Date::to($scheudle->training_date, Date::LONG), $template);

	    $template = str_replace('$$TRAINING_END_DATE_OTHER_FORMAT$$', Date::to($scheudle->training_end_date, Date::LONG), $template);

	    $template = str_replace('$$LEARNER_FEEDBACK_FORM$$', self::generateDuplexLearnerFeedbackUrl($link, $scheudle, $user->id), $template);
        }

        return $template;
    }

    public function replaceBcRegistrationUrl(PDO $link, $template, User $user)
    {
        $registrationId = DAO::getSingleValue($link, "SELECT id FROM registrations WHERE registrations.entity_id = '{$user->id}' AND registrations.entity_type = 'User'");
        if($registrationId == '')
        {
            throw new Exception("No registration record found.");
        }

        return str_replace('$$BC_REGISTRATION_FORM_URL$$', BootcampHelper::getBootcampRegistrationUrl($registrationId), $template);
    }

    public static function generateDuplexLearnerFeedbackUrl(PDO $link, $schedule, $learner_id)
    {
        if(! isset($schedule->id) )
            return;
        
        $training_id = DAO::getSingleValue($link, "SELECT id FROM training WHERE training.schedule_id = '{$schedule->id}' AND training.learner_id = '{$learner_id}'");
        $key = md5($training_id . '_feedback_form');
        return $_SERVER['SCRIPT_URI']."?_action=feedback_form&key=".$key;
    }

}

