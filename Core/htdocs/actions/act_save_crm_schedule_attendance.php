<?php
class save_crm_schedule_attendance implements IAction
{
    public function execute(PDO $link)
    {
        $attendance_date = $_POST['formDate'];
        $schedule_id = $_POST['scheduleId'];

        $learner_ids = DAO::getSingleColumn($link, "SELECT learner_id FROM training WHERE schedule_id = '{$schedule_id}'");
        foreach($learner_ids AS $learner_id)
        {
            $key = "attendance_{$learner_id}_{$attendance_date}";
            if(!isset($_POST[$key]))
                continue;

            $attendance = new stdClass();
            $attendance->schedule_id = $schedule_id;
            $attendance->attendance_date = $attendance_date;
            $attendance->learner_id = $learner_id;
            $attendance->attendance_code = $_POST[$key];
            $attendance->attendance_day = Date::to($attendance_date, 'l');

            DAO::saveObjectToTable($link, "session_attendance", $attendance);

            if(isset($_POST['is_completed']))
            {
                DAO::execute($link, "UPDATE training SET training.status = '1' WHERE training.schedule_id = '{$schedule_id}' AND training.learner_id = '{$learner_id}'");
            }
        }

        if(isset($_POST['is_completed']))
        {
            DAO::execute($link, "UPDATE training SET training.status = '2' WHERE training.schedule_id = '{$schedule_id}' AND training.learner_id IN (" . implode(",", $_POST['is_completed']) . ")");
        }

        $_SESSION['alert-success'] = 'Attendance register for ' . Date::toShort($attendance_date) . ' is saved successfully.';

        $tabId = isset($_REQUEST['tabId']) ? $_REQUEST['tabId'] : 'tab1';

        http_redirect("do.php?_action=edit_crm_schedule_attendance&id={$schedule_id}&tabId={$tabId}");
    }
}