<?php
class save_training_record_customized implements IAction
{
    public function execute(PDO $link)
    {
        $vo = TrainingRecord::loadFromDatabase($link, $_REQUEST['id']);

        $form_name = isset($_REQUEST['form_name']) ? $_REQUEST['form_name'] : '';
        if($form_name == 'frmTrProgressionFields')
        {
            $_fields = [
                "progression_status",
                "app_title",
                "notified_arm",
                "reason_not_progressing",
                "progression_comments",
                "progression_last_date",
                "progression_rating",
                "arm_prog_status",
                "arm_reason_not_prog",
                "arm_closed_date",
                "arm_revisit_progression",
                "arm_prog_rating",
                "arm_comments",
                "employer_mentor",
                "actual_progression",
                "planned_induction_date",
                "actual_induction_date",
                "trusted_contact_name",
                "trusted_contact_mobile",
                "trusted_contact_rel",
                "details_checked_date",
            ];
            foreach($_fields AS $_f)
            {
                $vo->$_f = isset($_REQUEST[$_f]) ? $_REQUEST[$_f] : $vo->$_f;
            }
        }

        $vo->disability = isset($_REQUEST['disability']) ? $_REQUEST['disability'] : $vo->disability;
        $vo->learning_difficulty = isset($_REQUEST['learning_difficulty']) ? $_REQUEST['learning_difficulty'] : $vo->learning_difficulty;
        $vo->ad_lldd = isset($_REQUEST['ad_lldd']) ? $_REQUEST['ad_lldd'] : $vo->ad_lldd;
        $vo->ad_arrangement_req = isset($_REQUEST['ad_arrangement_req']) ? $_REQUEST['ad_arrangement_req'] : $vo->ad_arrangement_req;
        $vo->ad_arrangement_agr = isset($_REQUEST['ad_arrangement_agr']) ? $_REQUEST['ad_arrangement_agr'] : $vo->ad_arrangement_agr;
        $vo->ad_evidence = isset($_REQUEST['ad_evidence']) ? $_REQUEST['ad_evidence'] : $vo->ad_evidence;

        $vo->save($link);

        http_redirect('do.php?_action=read_training_record&id=' . $vo->id);
    }
}