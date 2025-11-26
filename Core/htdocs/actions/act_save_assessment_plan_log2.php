<?php
class save_assessment_plan_log2 implements IAction
{
    public function execute(PDO $link)
    {
        $vo = new AssessmentPlanLog2();
        $vo->populate($_POST);
        $vo->save($link);

        if($_POST['submission_id1']!='' or $_POST['set_date1']!='' or $_POST['due_date1']!='')
        {
            $object = new stdClass();
            $object->id = $_POST['submission_id1'];
            if($_POST['id']=='')
            {
                $object->assessment_plan_id = $vo->id;
            }
            else
            {
                $object->assessment_plan_id = $_POST['id'];
                $object->modified = date('Y-m-d H:i:s');
            }
            $object->set_date = $_POST['set_date1'];
            $object->due_date = $_POST['due_date1'];
            $object->submission_date = $_POST['submission_date1'];
            $object->marked_date = $_POST['marked_date1'];
            //$object->status = $_POST['status1'];
            $object->sent_iqa_date = $_POST['sent_iqa_date1'];
            $object->iqa_status = $_POST['iqa_status1'];
            $object->acc_rej_date = $_POST['acc_rej_date1'];
            $object->learner_feedback_date = $_POST['learner_feedback_date1'];
            $object->feedback_received_date = $_POST['feedback_received_date1'];
            $object->completion_date = $_POST['completion_date1'];
            $object->comments = $_POST['comments1'];
            $object->assessor = $_POST['assessor1'];
            $object->user = $_SESSION['user']->username;
            $object->assessor_signed_off = $_POST['assessor_signed_off1'];
            DAO::saveObjectToTable($link, 'assessment_plan_log_submissions', $object);
        }
        if($_POST['submission_id2']!='' or $_POST['set_date2']!='' or $_POST['due_date2']!='')
        {
            $object = new stdClass();
            $object->id = $_POST['submission_id2'];
            if($_POST['id']=='')
            {
                $object->assessment_plan_id = $vo->id;
            }
            else
            {
                $object->assessment_plan_id = $_POST['id'];
                $object->modified = date('Y-m-d H:i:s');
            }
            $object->set_date = $_POST['set_date2'];
            $object->due_date = $_POST['due_date2'];
            $object->submission_date = $_POST['submission_date2'];
            $object->marked_date = $_POST['marked_date2'];
            //$object->status = $_POST['status2'];
            $object->sent_iqa_date = $_POST['sent_iqa_date2'];
            $object->iqa_status = $_POST['iqa_status2'];
            $object->acc_rej_date = $_POST['acc_rej_date2'];
            $object->learner_feedback_date = $_POST['learner_feedback_date2'];
            $object->feedback_received_date = $_POST['feedback_received_date2'];
            $object->completion_date = $_POST['completion_date2'];
            $object->comments = $_POST['comments2'];
            $object->assessor = $_POST['assessor2'];
            $object->user = $_SESSION['user']->username;
            $object->assessor_signed_off = $_POST['assessor_signed_off2'];
            DAO::saveObjectToTable($link, 'assessment_plan_log_submissions', $object);
        }
        if($_POST['submission_id3']!='' or $_POST['set_date3']!='' or $_POST['due_date3']!='')
        {
            $object = new stdClass();
            $object->id = $_POST['submission_id3'];
            if($_POST['id']=='')
            {
                $object->assessment_plan_id = $vo->id;
            }
            else
            {
                $object->assessment_plan_id = $_POST['id'];
                $object->modified = date('Y-m-d H:i:s');
            }
            $object->set_date = $_POST['set_date3'];
            $object->due_date = $_POST['due_date3'];
            $object->submission_date = $_POST['submission_date3'];
            $object->marked_date = $_POST['marked_date3'];
            //$object->status = $_POST['status3'];
            $object->sent_iqa_date = $_POST['sent_iqa_date3'];
            $object->iqa_status = $_POST['iqa_status3'];
            $object->acc_rej_date = $_POST['acc_rej_date3'];
            $object->learner_feedback_date = $_POST['learner_feedback_date3'];
            $object->feedback_received_date = $_POST['feedback_received_date3'];
            $object->completion_date = $_POST['completion_date3'];
            $object->comments = $_POST['comments3'];
            $object->assessor = $_POST['assessor3'];
            $object->user = $_SESSION['user']->username;
            $object->assessor_signed_off = $_POST['assessor_signed_off3'];
            DAO::saveObjectToTable($link, 'assessment_plan_log_submissions', $object);
        }
        if($_POST['submission_id4']!='' or $_POST['set_date4']!='' or $_POST['due_date4']!='')
        {
            $object = new stdClass();
            $object->id = $_POST['submission_id4'];
            if($_POST['id']=='')
            {
                $object->assessment_plan_id = $vo->id;
            }
            else
            {
                $object->assessment_plan_id = $_POST['id'];
                $object->modified = date('Y-m-d H:i:s');
            }
            $object->set_date = $_POST['set_date4'];
            $object->due_date = $_POST['due_date4'];
            $object->submission_date = $_POST['submission_date4'];
            $object->marked_date = $_POST['marked_date4'];
            //$object->status = $_POST['status4'];
            $object->sent_iqa_date = $_POST['sent_iqa_date4'];
            $object->iqa_status = $_POST['iqa_status4'];
            $object->acc_rej_date = $_POST['acc_rej_date4'];
            $object->learner_feedback_date = $_POST['learner_feedback_date4'];
            $object->feedback_received_date = $_POST['feedback_received_date4'];
            $object->completion_date = $_POST['completion_date4'];
            $object->comments = $_POST['comments4'];
            $object->assessor = $_POST['assessor4'];
            $object->user = $_SESSION['user']->username;
            $object->assessor_signed_off = $_POST['assessor_signed_off4'];
            DAO::saveObjectToTable($link, 'assessment_plan_log_submissions', $object);
        }
        if($_POST['submission_id5']!='' or $_POST['set_date5']!='' or $_POST['due_date5']!='')
        {
            $object = new stdClass();
            $object->id = $_POST['submission_id5'];
            if($_POST['id']=='')
            {
                $object->assessment_plan_id = $vo->id;
            }
            else
            {
                $object->assessment_plan_id = $_POST['id'];
                $object->modified = date('Y-m-d H:i:s');
            }
            $object->set_date = $_POST['set_date5'];
            $object->due_date = $_POST['due_date5'];
            $object->submission_date = $_POST['submission_date5'];
            $object->marked_date = $_POST['marked_date5'];
            //$object->status = $_POST['status5'];
            $object->sent_iqa_date = $_POST['sent_iqa_date5'];
            $object->iqa_status = $_POST['iqa_status5'];
            $object->acc_rej_date = $_POST['acc_rej_date5'];
            $object->learner_feedback_date = $_POST['learner_feedback_date5'];
            $object->feedback_received_date = $_POST['feedback_received_date5'];
            $object->completion_date = $_POST['completion_date5'];
            $object->comments = $_POST['comments5'];
            $object->assessor = $_POST['assessor5'];
            $object->user = $_SESSION['user']->username;
            $object->assessor_signed_off = $_POST['assessor_signed_off5'];
            DAO::saveObjectToTable($link, 'assessment_plan_log_submissions', $object);
        }
        if($_POST['submission_id6']!='' or $_POST['set_date6']!='' or $_POST['due_date6']!='')
        {
            $object = new stdClass();
            $object->id = $_POST['submission_id6'];
            if($_POST['id']=='')
            {
                $object->assessment_plan_id = $vo->id;
            }
            else
            {
                $object->assessment_plan_id = $_POST['id'];
                $object->modified = date('Y-m-d H:i:s');
            }
            $object->set_date = $_POST['set_date6'];
            $object->due_date = $_POST['due_date6'];
            $object->submission_date = $_POST['submission_date6'];
            $object->marked_date = $_POST['marked_date6'];
            //$object->status = $_POST['status5'];
            $object->sent_iqa_date = $_POST['sent_iqa_date6'];
            $object->iqa_status = $_POST['iqa_status6'];
            $object->acc_rej_date = $_POST['acc_rej_date6'];
            $object->learner_feedback_date = $_POST['learner_feedback_date6'];
            $object->feedback_received_date = $_POST['feedback_received_date6'];
            $object->completion_date = $_POST['completion_date6'];
            $object->comments = $_POST['comments6'];
            $object->assessor = $_POST['assessor6'];
            $object->user = $_SESSION['user']->username;
            $object->assessor_signed_off = $_POST['assessor_signed_off6'];
            DAO::saveObjectToTable($link, 'assessment_plan_log_submissions', $object);
        }
        if($_POST['submission_id7']!='' or $_POST['set_date7']!='' or $_POST['due_date7']!='')
        {
            $object = new stdClass();
            $object->id = $_POST['submission_id7'];
            if($_POST['id']=='')
            {
                $object->assessment_plan_id = $vo->id;
            }
            else
            {
                $object->assessment_plan_id = $_POST['id'];
                $object->modified = date('Y-m-d H:i:s');
            }
            $object->set_date = $_POST['set_date7'];
            $object->due_date = $_POST['due_date7'];
            $object->submission_date = $_POST['submission_date7'];
            $object->marked_date = $_POST['marked_date7'];
            //$object->status = $_POST['status5'];
            $object->sent_iqa_date = $_POST['sent_iqa_date7'];
            $object->iqa_status = $_POST['iqa_status7'];
            $object->acc_rej_date = $_POST['acc_rej_date7'];
            $object->learner_feedback_date = $_POST['learner_feedback_date7'];
            $object->feedback_received_date = $_POST['feedback_received_date7'];
            $object->completion_date = $_POST['completion_date7'];
            $object->comments = $_POST['comments7'];
            $object->assessor = $_POST['assessor7'];
            $object->user = $_SESSION['user']->username;
            $object->assessor_signed_off = $_POST['assessor_signed_off7'];
            DAO::saveObjectToTable($link, 'assessment_plan_log_submissions', $object);
        }
        if($_POST['submission_id8']!='' or $_POST['set_date8']!='' or $_POST['due_date8']!='')
        {
            $object = new stdClass();
            $object->id = $_POST['submission_id8'];
            if($_POST['id']=='')
            {
                $object->assessment_plan_id = $vo->id;
            }
            else
            {
                $object->assessment_plan_id = $_POST['id'];
                $object->modified = date('Y-m-d H:i:s');
            }
            $object->set_date = $_POST['set_date8'];
            $object->due_date = $_POST['due_date8'];
            $object->submission_date = $_POST['submission_date8'];
            $object->marked_date = $_POST['marked_date8'];
            //$object->status = $_POST['status5'];
            $object->sent_iqa_date = $_POST['sent_iqa_date8'];
            $object->iqa_status = $_POST['iqa_status8'];
            $object->acc_rej_date = $_POST['acc_rej_date8'];
            $object->learner_feedback_date = $_POST['learner_feedback_date8'];
            $object->feedback_received_date = $_POST['feedback_received_date8'];
            $object->completion_date = $_POST['completion_date8'];
            $object->comments = $_POST['comments8'];
            $object->assessor = $_POST['assessor8'];
            $object->user = $_SESSION['user']->username;
            $object->assessor_signed_off = $_POST['assessor_signed_off8'];
            DAO::saveObjectToTable($link, 'assessment_plan_log_submissions', $object);
        }
        if($_POST['submission_id9']!='' or $_POST['set_date9']!='' or $_POST['due_date9']!='')
        {
            $object = new stdClass();
            $object->id = $_POST['submission_id9'];
            if($_POST['id']=='')
            {
                $object->assessment_plan_id = $vo->id;
            }
            else
            {
                $object->assessment_plan_id = $_POST['id'];
                $object->modified = date('Y-m-d H:i:s');
            }
            $object->set_date = $_POST['set_date9'];
            $object->due_date = $_POST['due_date9'];
            $object->submission_date = $_POST['submission_date9'];
            $object->marked_date = $_POST['marked_date9'];
            //$object->status = $_POST['status5'];
            $object->sent_iqa_date = $_POST['sent_iqa_date9'];
            $object->iqa_status = $_POST['iqa_status9'];
            $object->acc_rej_date = $_POST['acc_rej_date9'];
            $object->learner_feedback_date = $_POST['learner_feedback_date9'];
            $object->feedback_received_date = $_POST['feedback_received_date9'];
            $object->completion_date = $_POST['completion_date9'];
            $object->comments = $_POST['comments9'];
            $object->assessor = $_POST['assessor9'];
            $object->user = $_SESSION['user']->username;
            $object->assessor_signed_off = $_POST['assessor_signed_off9'];
            DAO::saveObjectToTable($link, 'assessment_plan_log_submissions', $object);
        }
        if($_POST['submission_id10']!='' or $_POST['set_date10']!='' or $_POST['due_date10']!='')
        {
            $object = new stdClass();
            $object->id = $_POST['submission_id10'];
            if($_POST['id']=='')
            {
                $object->assessment_plan_id = $vo->id;
            }
            else
            {
                $object->assessment_plan_id = $_POST['id'];
                $object->modified = date('Y-m-d H:i:s');
            }
            $object->set_date = $_POST['set_date10'];
            $object->due_date = $_POST['due_date10'];
            $object->submission_date = $_POST['submission_date10'];
            $object->marked_date = $_POST['marked_date10'];
            //$object->status = $_POST['status5'];
            $object->sent_iqa_date = $_POST['sent_iqa_date10'];
            $object->iqa_status = $_POST['iqa_status10'];
            $object->acc_rej_date = $_POST['acc_rej_date10'];
            $object->learner_feedback_date = $_POST['learner_feedback_date10'];
            $object->feedback_received_date = $_POST['feedback_received_date10'];
            $object->completion_date = $_POST['completion_date10'];
            $object->comments = $_POST['comments10'];
            $object->assessor = $_POST['assessor10'];
            $object->user = $_SESSION['user']->username;
            $object->assessor_signed_off = $_POST['assessor_signed_off10'];
            DAO::saveObjectToTable($link, 'assessment_plan_log_submissions', $object);
        }


        // Add Gateway Review
        /*$tr_id = $vo->tr_id;
        $gateway = DAO::getSingleValue($link, "select count(*) from assessor_review where template_review=3 and tr_id='$tr_id'");
        if($gateway==0)
        {
            // Check if Assessment Plan Progress is 100%
            if(TrainingRecord::getAssessmentProgress($link, $tr_id)>=100)
            {
                if(DAO::getSingleValue($link, "SELECT COUNT(*) FROM op_epa WHERE task_status = 1 AND tr_id = '$tr_id'")>0)
                {
                    $due_date = DAO::getSingleValue($link, "SELECT MAX(next_contact) FROM arf_introduction WHERE review_id IN (SELECT id FROM assessor_review WHERE tr_id='$tr_id')");
                    if($due_date=='')
                        $due_date = DAO::getSingleValue($link, "SELECT MAX(STR_TO_DATE(next_contact,'%d/%m/%Y')) FROM assessor_review_forms_assessor4 WHERE review_id IN (SELECT id FROM assessor_review WHERE tr_id='$tr_id')");

                    $review_id = DAO::getSingleValue($link, "SELECT id FROM assessor_review WHERE tr_id = '$tr_id' AND id NOT IN (SELECT review_id FROM arf_introduction WHERE signature_assessor_font IS NOT NULL) AND id NOT IN (SELECT review_id FROM assessor_review_forms_assessor4 WHERE signature_assessor_font IS NOT NULL)");
                    if($review_id!=='')
                        DAO::execute($link,"update assessor_review set template_review = 3 where id = '$review_id'");
                    else
                        DAO::execute($link,"insert into assessor_review values(NULL,'$tr_id','$due_date',NULL,'','',0,'','','','','',3,NULL,NULL,NULL,'','','','','','',0,0,0,0,0,0,0,0,0)");
                }
            }
        }*/



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