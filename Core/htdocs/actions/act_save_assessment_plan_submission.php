<?php
class save_assessment_plan_submission implements IAction
{
    public function execute(PDO $link)
    {
        $vo = new AssessmentPlanSubmission();
        $vo->populate($_POST);

        if($_POST['id']=='' and $_POST['assessment_plan_id']=='' and $_POST['tr_id']!='')
        {
            $plan = new AssessmentPlanLog2();
            $plan->mode = $_POST['mode'];
            $plan->tr_id = $_POST['tr_id'];
            $plan->save($link);

            $vo->assessment_plan_id = $plan->id;
        }
        else
        {
            $plan = AssessmentPlanLog2::loadFromDatabase($link, $_POST['assessment_plan_id']);
            $plan->mode = $_POST['mode'];
            $plan->save($link);
        }

        $vo->user = $_SESSION['user']->username;
        $vo->save($link);

        http_redirect("do.php?_action=edit_assessment_plan_log2&apl_id=$vo->assessment_plan_id&tr_id=".$_POST['tr_id']);
    }
}
?>