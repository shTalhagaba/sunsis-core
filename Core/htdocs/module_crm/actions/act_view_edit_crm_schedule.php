<?php
class view_edit_crm_schedule implements IAction
{
    public function execute(PDO $link)
    {
        $subaction = isset($_REQUEST['subaction'])?$_REQUEST['subaction']:'';
        $id = isset($_REQUEST['id'])?$_REQUEST['id']:'';

        $schedule = DAO::getObject($link, "SELECT * FROM crm_training_schedule WHERE id = '{$id}'");
        $schedule->learner_ids = DAO::getSingleColumn($link, "SELECT learner_id FROM training WHERE schedule_id = '{$schedule->id}'");
        $number_of_added_learners = count($schedule->learner_ids);

        $_SESSION['bc']->add($link, "do.php?_action=view_edit_crm_schedule&id={$id}", "Manage CRM Schedule");

        include_once('tpl_view_edit_crm_schedule.php');

    }

    public function scheduleLevelDesc($levelId)
    {
        return AppHelper::duplexTrainingLevelDesc($levelId);
    }

}