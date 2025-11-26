<?php
class delete_assessment_submission implements IAction
{
    public function execute(PDO $link)
    {

        $id = isset($_REQUEST['submission_id'])?$_REQUEST['submission_id']:'';
        DAO::execute($link, "delete from assessment_plan_log_submissions where id = '$id'");
        DAO::execute($link, "DELETE FROM assessment_plan_log WHERE id NOT IN (SELECT assessment_plan_id FROM assessment_plan_log_submissions);");
    }
}
?>