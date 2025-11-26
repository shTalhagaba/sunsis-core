<?php
class edit_learner_additional_support implements IAction
{
    public function execute(PDO $link)
    {
        // Validate data entry
        $id = isset($_REQUEST['id']) ? $_REQUEST['id'] : '';
        $tr_id = isset($_REQUEST['tr_id']) ? $_REQUEST['tr_id'] : '';

        if(isset($_REQUEST['ajax_request']) && $_REQUEST['ajax_request'])
        {
            if(isset($_REQUEST['id']))
                echo $this->deleteLearnerAdditionalSupportRecord($link, $_REQUEST['id']);
            else
                echo 'Missing query string argument.';
            exit;
        }

        if($tr_id == '')
            throw new Exception('Missing Training Record ID.');

        $_SESSION['bc']->add($link, "do.php?_action=edit_learner_additional_support&tr_id=" . $tr_id, "Add/Edit Learner Additional Support Session");

        if($id == '')
        {
            // New record
            $vo = new AdditionalSupport();
            $vo->tr_id = $tr_id;
            $page_title = "Add Additional Support Details";
            $exam_status = "";
        }
        else
        {
            $vo = AdditionalSupport::loadFromDatabase($link, $id);
            $page_title = "Edit Additional Support Details";
            $today_date = new Date(date('Y-m-d'));
        }

        $enable_save = true;
        if($_SESSION['user']->type == User::TYPE_LEARNER || $_SESSION['user']->type == User::TYPE_SYSTEM_VIEWER)
            $enable_save = false;

        if(in_array(DB_NAME, ["am_baltic_demo", "am_baltic"]) &&
            in_array($_SESSION['user']->username, ['lepearson', 'nimaxwell', 'rherdman16', 'dpetrusowsv', 'cherylreay', 'creay123', 'elee1234'])
        )
        {
            $enable_save = true;
        }

        // Cancel button URL
        $js_cancel = "window.location.replace('do.php?_action=read_training_record&webinars_tab=1&id=$tr_id');";

        $pot_vo = TrainingRecord::loadFromDatabase($link,$tr_id);

        $other_records = $this->renderOtherRecords($link, $pot_vo, $vo->id);

        include('tpl_edit_learner_additional_support.php');
    }


    private function deleteLearnerAdditionalSupportRecord(PDO $link, $fs_progress_id)
    {
        $result = DAO::execute($link, "DELETE FROM additional_support WHERE id = " . $fs_progress_id);
        if($result > 0)
            return 'The record has been successfully deleted.';
        else
            return 'Operation failed.';
    }

    private function renderOtherRecords(PDO $link, TrainingRecord $tr, $exclude_id = '')
    {
        if($exclude_id == '')
            $records = DAO::getResultset($link, "SELECT * FROM additional_support WHERE tr_id = '{$tr->id}' ORDER BY id", DAO::FETCH_ASSOC);
        else
            $records = DAO::getResultset($link, "SELECT * FROM additional_support WHERE tr_id = '{$tr->id}' AND id != '{$exclude_id}' ORDER BY id", DAO::FETCH_ASSOC);

        $html = '';
        if(count($records) == 0)
            return $html;

        $subject_areas = [
            '0' => 'Assessment Plans',
            '1' => 'Reflective Hours',
            '2' => 'Functional Skills',
            '3' => 'Others'
        ];
        foreach($records AS $row)
        {
            $sa = isset($subject_areas[$row['subject_area']]) ? $subject_areas[$row['subject_area']] : $row['subject_area'];
            $html .= '<div class="well well-small">';
            $html .= '<div class="table-responsive">';
            $html .= '<table class="table">';
            $html .= '<tr><th>Actual Date:</th><td>' . Date::toShort($row['actual_date']) . '</td><th>Due Date:</th><td>' . Date::toShort($row['due_date']) . '</td></tr>';
            $html .= '<tr><th>Time:</th><td>' . $row['time_from'] . ' - ' . $row['time_to'] . '</td><th>Subject Area:</th><td>' . $sa . '</td></tr>';
            $html .= '<tr><th>Comments:</th><td colspan="3">' . $row['comments'] . '</td> </tr>';
            $html .= '</table> ';
            $html .= '</div>';
            $html .= '</div><hr>';
        }

        return $html;
    }
}
?>