<?php
class EmailAutomationDuplex
{
  public static function getCommonWhereClause()
  {
    return " users.type = 5 AND users.home_email IS NOT NULL ";
  }

  public static function getLearnersToSendWeeklyEmailAboutHsForm(PDO $link)
  {
    $learner_ids = [];

    $email_type = 3;
    /*
         * get all learners
         * who have been sent H&S email and
         * have not completed H&S form and
         * are enrolled for Level 3/4 schedule and there are 7 or more days left in course start date
         */
    $sql = <<<SQL
SELECT
  emails.entity_id, MAX(emails.`created`) AS latest_email_sent_date
FROM
  emails
WHERE 
#sent H&S email
emails.`email_type` IN (3, 13) AND emails.`entity_type` = 'sunesis_learner'
#have not yet completed H&S form 
AND emails.`entity_id` IN (SELECT crm_learner_hs_form.learner_id FROM crm_learner_hs_form WHERE crm_learner_hs_form.`learner_sign` IS NULL)
#have been enrolled to Level 3/4 course and course start date is not less than 7 days
AND emails.`entity_id` IN (
                            SELECT training.`learner_id` 
                            FROM crm_training_schedule INNER JOIN training ON crm_training_schedule.`id` = training.`schedule_id` 
                            WHERE crm_training_schedule.training_date > CURDATE() 
                                #AND DATEDIFF(crm_training_schedule.training_date, CURDATE()) >= 7
                          )
GROUP BY emails.`entity_id`                          
HAVING DATEDIFF(CURDATE(), latest_email_sent_date) >= 7
ORDER BY emails.`id` DESC
;
SQL;
    $result = DAO::getResultSet($link, $sql, DAO::FETCH_ASSOC);
    foreach ($result as $row) {
      if (in_array($row['entity_id'], $learner_ids))
        continue;

      $learner_ids[] = $row['entity_id'];
    }

    return $learner_ids;
  }

  public static function getLearnersWithImiCodeToSendJoingInstructions(PDO $link, $level)
  {
    $learner_ids = [];

    $where_clause = self::getCommonWhereClause();

    $email_type = $level == 'L3' ? 2 : 1;
    /*
         * get all learners
         * who have not been sent joining instructions email and
         * having IMI Redeem Code and
         * are enrolled for Level 3/4 schedule and there are 3 weeks left in course start
         */
    $sql = "SELECT training.* FROM crm_training_schedule INNER JOIN training ON crm_training_schedule.`id` = training.`schedule_id`
          INNER JOIN users ON training.`learner_id` = users.`id`
          WHERE crm_training_schedule.`level` = '{$level}'
          AND users.`employer_id` != '3161'
          AND users.`imi_redeem_code` IS NOT NULL
	AND crm_training_schedule.`venue` = 'Wolverhampton'
          AND crm_training_schedule.training_date BETWEEN CURDATE() AND DATE_ADD(CURDATE(), INTERVAL 3 WEEK)";
    $result = DAO::getResultset($link, $sql, DAO::FETCH_ASSOC);

    foreach ($result as $row) {
      $sql_already_sent = "SELECT COUNT(*) FROM emails  
          WHERE emails.`email_type` = '{$email_type}'
            AND emails.`entity_id` = '{$row['learner_id']}'
            AND emails.`entity_type` = 'sunesis_learner'
            AND emails.`schedule_id` = '{$row['schedule_id']}'
          ";
      $already_sent = DAO::getSingleValue($link, $sql_already_sent);
      if ($already_sent == 0) {
        $learner_ids[] = [$row['learner_id'], $row['schedule_id']];
      }
    }

    return $learner_ids;
  }

  public static function getWmpLearnersWithImiCodeToSendJoingInstructions(PDO $link, $level)
  {
    $learner_ids = [];

    $where_clause = self::getCommonWhereClause();

    $email_type = $level == 'L3' ? 15 : 16;
    /*
         * get all learners
         * who have not been sent joining instructions email and
         * having IMI Redeem Code and
         * are enrolled for Level 3/4 schedule and there are 3 weeks left in course start
         */
    $sql = "SELECT training.* FROM crm_training_schedule INNER JOIN training ON crm_training_schedule.`id` = training.`schedule_id`
      INNER JOIN users ON training.`learner_id` = users.`id`
      WHERE crm_training_schedule.`level` = '{$level}'
      AND users.`employer_id` = '3161'
      AND users.`imi_redeem_code` IS NOT NULL
	AND crm_training_schedule.`venue` = 'Wolverhampton'
      AND crm_training_schedule.training_date BETWEEN CURDATE() AND DATE_ADD(CURDATE(), INTERVAL 3 WEEK)";    
    $result = DAO::getResultset($link, $sql, DAO::FETCH_ASSOC);

    foreach ($result as $row) {
      $sql_already_sent = "SELECT COUNT(*) FROM emails  
          WHERE emails.`email_type` = '{$email_type}'
            AND emails.`entity_id` = '{$row['learner_id']}'
            AND emails.`entity_type` = 'sunesis_learner'
            AND emails.`schedule_id` = '{$row['schedule_id']}'
          ";
      $already_sent = DAO::getSingleValue($link, $sql_already_sent);
      if ($already_sent == 0) {
        $learner_ids[] = [$row['learner_id'], $row['schedule_id']];
      }
    }

    return $learner_ids;
  }

  public static function getLearnersToSend1WeekReminder(PDO $link, $level)
  {
    $learner_ids = [];

    $where_clause = self::getCommonWhereClause();

    $email_type = $level == 'L3' ? 9 : 10;
    /*
         * get all learners
         * who have not been sent 1 week reminder email and
         * are enrolled for Level 3/4 schedule and there is 1 week left in course start
         */
    $sql = "SELECT training.* FROM crm_training_schedule INNER JOIN training ON crm_training_schedule.`id` = training.`schedule_id`
        INNER JOIN users ON training.`learner_id` = users.`id`
        WHERE crm_training_schedule.`level` = '{$level}'
	AND crm_training_schedule.`venue` = 'Wolverhampton'
        AND crm_training_schedule.training_date BETWEEN CURDATE() AND DATE_ADD(CURDATE(), INTERVAL 1 WEEK) ";
    $result = DAO::getResultset($link, $sql, DAO::FETCH_ASSOC);

    foreach ($result as $row) {
      $sql_already_sent = "SELECT COUNT(*) FROM emails  
          WHERE emails.`email_type` = '{$email_type}'
            AND emails.`entity_id` = '{$row['learner_id']}'
            AND emails.`entity_type` = 'sunesis_learner'
            AND emails.`schedule_id` = '{$row['schedule_id']}'
          ";
      $already_sent = DAO::getSingleValue($link, $sql_already_sent);
      if ($already_sent == 0) {
        $learner_ids[] = [$row['learner_id'], $row['schedule_id']];
      }
    }

    return $learner_ids;
  }

  public static function getLearnersToSend1DayToGo(PDO $link, $level)
  {
    $learner_ids = [];

    $where_clause = self::getCommonWhereClause();

    $email_type = $level == 'L3' ? 11 : 12;
    /*
         * get all learners
         * who have not been sent 1 day to go email and
         * are enrolled for Level 3/4 schedule and there is 1 day left in course start
         */
    $sql = "SELECT training.* FROM crm_training_schedule INNER JOIN training ON crm_training_schedule.`id` = training.`schedule_id`
        INNER JOIN users ON training.`learner_id` = users.`id`
        WHERE crm_training_schedule.`level` = '{$level}'
	AND crm_training_schedule.`venue` = 'Wolverhampton'
        AND crm_training_schedule.training_date = DATE_ADD(CURDATE(), INTERVAL 1 DAY) ";
    $result = DAO::getResultset($link, $sql, DAO::FETCH_ASSOC);

    foreach ($result as $row) {
      $sql_already_sent = "SELECT COUNT(*) FROM emails 
          WHERE emails.`email_type` = '{$email_type}'
            AND emails.`entity_id` = '{$row['learner_id']}'
            AND emails.`entity_type` = 'sunesis_learner'
            AND emails.`schedule_id` = '{$row['schedule_id']}'
          ";
      $already_sent = DAO::getSingleValue($link, $sql_already_sent);
      if ($already_sent == 0) {
        $learner_ids[] = [$row['learner_id'], $row['schedule_id']];
      }
    }

    return $learner_ids;
  }

  public static function getLearnersToSend2WeekElearningReminder(PDO $link, $level)
  {
    $learner_ids = [];

    $where_clause = self::getCommonWhereClause();

    $email_type = $level == 'L3' ? 18 : 19;
    /*
         * get all learners
         * who have not been sent 2 weeks before e-learning reminder email and
         * are enrolled for Level 3/4 schedule and there are 2 weeks left in course start
         */
    $sql = "SELECT training.* FROM crm_training_schedule INNER JOIN training ON crm_training_schedule.`id` = training.`schedule_id`
        INNER JOIN users ON training.`learner_id` = users.`id`
        WHERE crm_training_schedule.`level` = '{$level}'
	AND crm_training_schedule.`venue` = 'Wolverhampton'
        AND crm_training_schedule.training_date BETWEEN CURDATE() AND DATE_ADD(CURDATE(), INTERVAL 2 WEEK) ";
    $result = DAO::getResultset($link, $sql, DAO::FETCH_ASSOC);

    foreach ($result as $row) {
      $sql_already_sent = "SELECT COUNT(*) FROM emails 
        WHERE emails.`email_type` = '{$email_type}'
          AND emails.`entity_id` = '{$row['learner_id']}'
          AND emails.`entity_type` = 'sunesis_learner'
          AND emails.`schedule_id` = '{$row['schedule_id']}'
        ";
      $already_sent = DAO::getSingleValue($link, $sql_already_sent);
      if ($already_sent == 0) {
        $learner_ids[] = [$row['learner_id'], $row['schedule_id']];
      }
    }

    return $learner_ids;
  }

  public static function getLearnersToSendAfterCompletingCourseEmail(PDO $link)
  {
    $learner_ids = [];

    $where_clause = self::getCommonWhereClause();

    $email_type = 17;
    /*
         * get all learners
         * who have not been sent thank you email and
         * have completed for Level 3/4 schedule a
         */

    $sql = "SELECT training.* FROM crm_training_schedule INNER JOIN training ON crm_training_schedule.`id` = training.`schedule_id`
        INNER JOIN users ON training.`learner_id` = users.`id`
        WHERE training.status = '2'
	AND crm_training_schedule.`venue` = 'Wolverhampton'
        AND CURDATE() = DATE_ADD(crm_training_schedule.training_end_date, INTERVAL 1 DAY)  ";
    $result = DAO::getResultset($link, $sql, DAO::FETCH_ASSOC);

    foreach ($result as $row) {
      $sql_already_sent = "SELECT COUNT(*) FROM emails 
        WHERE emails.`email_type` = '{$email_type}'
          AND emails.`entity_id` = '{$row['learner_id']}'
          AND emails.`entity_type` = 'sunesis_learner'
          AND emails.`schedule_id` = '{$row['schedule_id']}'
        ";
      $already_sent = DAO::getSingleValue($link, $sql_already_sent);
      if ($already_sent == 0) {
        $learner_ids[] = [$row['learner_id'], $row['schedule_id']];
      }
    }

    return $learner_ids;
  }

  public static function sendEmail(PDO $link, $learner_ids, $email_type, $subject)
  {
    $template = DAO::getObject($link, "SELECT * FROM email_templates WHERE id = '{$email_type}'");
    if (!isset($template->id))
      return;

    $from = 'no-reply@perspective-uk.com';

    foreach ($learner_ids as $learner_id) {
      $user = DAO::getObject($link, "SELECT * FROM users WHERE users.id = '{$learner_id[0]}'");
      if (!isset($user->id)) continue;
      if ($user->home_email == '') continue;

      $learner = new User();
      $learner->populate($user);

      $email_template = new EmailTemplate();
      $ready_template = $email_template->prepare($link, $template->template_type, $learner);

      $save_email = false;
      if (in_array($email_type, [2, 1, 15, 16]) && is_file('/srv/www/am_common_data/uploads/am_duplex/section_email_attachments/EV-map.pdf')) {
        $save_email = Emailer::multi_attach_mail($learner->home_email, $subject, $ready_template, $from, "Sunesis", ['/srv/www/am_common_data/uploads/am_duplex/section_email_attachments/EV-map.pdf']);
      } else {
        $save_email = Emailer::notification_email($learner->home_email, $from, $from, $subject, '', $ready_template);
      }

      //if(Emailer::notification_email($learner->home_email, $from, $from, $subject, '', $ready_template))
      if ($save_email) {
        $email = new stdClass();
        $email->entity_type = 'sunesis_learner';
        $email->entity_id = $learner->id;
        $email->email_to = $learner->home_email;
        $email->email_from = $from;
        $email->email_subject = $subject;
        $email->email_body = substr($ready_template, 0, 4998);
        $email->by_whom = 9999;
        $email->email_type = $email_type;
        $email->schedule_id = $learner_id[1];

        DAO::saveObjectToTable($link, 'emails', $email);
      }
    }
  }

  public static function getLearnersWithNoImiRedeemCode(PDO $link, $days_left)
  {
    $where_clause = self::getCommonWhereClause();

    /*
         * get all learners
         * who are enrolled for Level 3/4 schedule and start date is in future
         * do not have IMI Redeem Code
         */
    $sql = <<<SQL
SELECT
  users.id
FROM
  users 
WHERE $where_clause 
  AND users.home_email IS NOT NULL AND users.type = '$learner_type' AND (users.imi_redeem_code IS NULL OR users.imi_redeem_code = '')
#have been enrolled to Level 3/4 course and course start date is in future
AND users.`id` IN (
                    SELECT training.`learner_id` 
                    FROM crm_training_schedule INNER JOIN training ON crm_training_schedule.`id` = training.`schedule_id` 
                    WHERE crm_training_schedule.`level` = '$level'
                        AND crm_training_schedule.training_date BETWEEN CURDATE() AND DATE_ADD(CURDATE(), INTERVAL $days_left DAY) 
                  )
;
SQL;
    return DAO::getSingleColumn($link, $sql);
  }
}
