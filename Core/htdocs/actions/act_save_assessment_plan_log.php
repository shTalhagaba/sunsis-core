<?php
class save_assessment_plan_log implements IAction
{
    public function execute(PDO $link)
    {
        $vo = new AssessmentPlanLog();
        $vo->populate($_POST);
        $vo->save($link);

        if(IS_AJAX)
        {
            header("Content-Type: text/plain");
            echo $vo->id;
        }
        else
        {
            http_redirect('do.php?_action=read_training_record&webinars_tab=1&id=' . $vo->tr_id);
        }
    }
}
?>