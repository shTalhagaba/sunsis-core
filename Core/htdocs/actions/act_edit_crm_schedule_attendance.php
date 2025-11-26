<?php
class edit_crm_schedule_attendance implements IAction
{
    public function execute(PDO $link)
    {
        $id = isset($_REQUEST['id']) ? $_REQUEST['id'] : '';
        $tabId = isset($_REQUEST['tabId']) ? $_REQUEST['tabId'] : 'tab1';

        $schedule = DAO::getObject($link, "SELECT * FROM crm_training_schedule WHERE id = '{$id}'");
        $entries_sql = <<<SQL
SELECT 
    training.*,
    users.firstnames, users.surname,
    crm_training_schedule.training_date,
    users.home_email,
    users.imi_redeem_code,
    users.imi_candidate_number,
    (SELECT COUNT(*) FROM crm_learner_hs_form WHERE learner_id = users.`id` AND crm_learner_hs_form.`learner_sign` IS NOT NULL) AS is_signed
FROM 
     training 
         INNER JOIN crm_training_schedule ON crm_training_schedule.id = training.schedule_id
         INNER JOIN users ON training.learner_id = users.id
WHERE
    crm_training_schedule.id = '$id'
ORDER BY crm_training_schedule.training_date, users.surname, users.firstnames
;
SQL;
        $entries = DAO::getResultset($link, $entries_sql, DAO::FETCH_ASSOC);

        $_SESSION['bc']->add($link, "do.php?_action=edit_crm_schedule_attendance&id=$id&tabId=$tabId", "Mark Attendancce");

        include('tpl_edit_crm_schedule_attendance.php');
    }
}