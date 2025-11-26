<?php
class save_evidence_matrix_submission implements IAction
{
    public function execute(PDO $link)
    {

        $save_iqa = isset($_REQUEST['save_iqa']) ? $_REQUEST['save_iqa'] : '0';
        if($save_iqa==1)
        {
            $id = isset($_REQUEST['id']) ? $_REQUEST['id'] : '0';
            $iqa_status = isset($_REQUEST['iqa_status']) ? $_REQUEST['iqa_status'] : '0';
            $acc_rej_date = isset($_REQUEST['acc_rej_date']) ? $_REQUEST['acc_rej_date'] : '';
            $iqa_reason = isset($_REQUEST['iqa_reason']) ? $_REQUEST['iqa_reason'] : '';
            $attempt = isset($_REQUEST['attempt']) ? $_REQUEST['attempt'] : '';
            $feedback_summary = isset($_REQUEST['feedback_summary']) ? $_REQUEST['feedback_summary'] : '';
            if($id>0)
            {
                $project_submission = EvidenceMatrixSubmission::loadFromDatabase($link, $id);
                $project_submission->iqa_status = $iqa_status;
                $project_submission->acc_rej_date = $acc_rej_date;
                $project_submission->iqa_reason = $iqa_reason;
                $project_submission->attempt = $attempt;
                $project_submission->feedback_summary = $feedback_summary;
                $project_submission->save($link);
            }
            return true;
        }

        ini_set('memory_limit','2048M');

        $course_id = $_POST['course_id'];	
        $tr_id = $_POST['tr_id'];

        $vo = new EvidenceMatrixSubmission();
        $vo->populate($_POST);
        if(isset($_POST['matrix']))
            $vo->matrix  = implode(",",$_POST['matrix']);
        else
            $vo->matrix  = "";

        if(isset($_POST['reduced_projects']))
            $vo->reduced_projects = 1;
        else
            $vo->reduced_projects = 0;

        if($_POST['id']=='' and $_POST['project_id']=='' and $_POST['tr_id']!='')
        {
            $plan = new EvidenceProject();
            $plan->project = $_POST['mode'];
            $plan->tr_id = $_POST['tr_id'];
            $plan->save($link);
            $vo->project_id = $plan->id;
            $vo->user = $_SESSION['user']->username;
            $vo->save($link);
    
            if(isset($_POST['other_learners']))
            {
                foreach($_POST['other_learners'] as $learner)
                {
                    $plan = new EvidenceProject();
                    $plan->project = $_POST['mode'];
                    $plan->tr_id = $learner;
                    $plan->save($link);

                    $vo = new EvidenceMatrixSubmission();
                    $vo->populate($_POST);
                    if(isset($_POST['matrix']))
                        $vo->matrix  = implode(",",$_POST['matrix']);
                    else
                        $vo->matrix  = "";

                    if(isset($_POST['reduced_projects']))
                        $vo->reduced_projects = 1;
                    else
                        $vo->reduced_projects = 0;
        
                    $vo->project_id = $plan->id;
                    $vo->user = $_SESSION['user']->username;
                    $vo->save($link);
                }            
            }

        }
        else
        {
            $plan = EvidenceProject::loadFromDatabase($link, $_POST['project_id']);
            $plan->project_id = $_POST['project_id'];
            $plan->project = $_POST['mode'];
            $plan->save($link);

            $vo = new EvidenceMatrixSubmission();
            $vo->populate($_POST);
            if(isset($_POST['matrix']))
                $vo->matrix  = implode(",",$_POST['matrix']);
            else
                $vo->matrix  = "";

            if(isset($_POST['reduced_projects']))
                $vo->reduced_projects = 1;
            else
                $vo->reduced_projects = 0;
    
            $vo->project_id = $plan->id;
            $vo->iqa = $_POST['iqa'];
            $vo->user = $_SESSION['user']->username;

            /*if($_SESSION['user']->username = "hcoatesa")
            {
		pr( 'Number of keys: ' . count(array_keys($_POST)) );
		pr( 'CONTENT_LENGTH: '. (int) $_SERVER['CONTENT_LENGTH'] );
                pre($_POST);
            }*/

            $vo->save($link);
        }


        if(DB_NAME=='am_baltic')
        {
            if(isset($_POST['matrix']))
            {
                foreach($_POST['matrix'] as $matrix_id)
                {
                    $matrix_id = trim($matrix_id);
                    $iqa_submission = new StdClass();
                    $iqa_submission->tr_id = $_POST['tr_id'];
                    $iqa_submission->submission_id = $vo->id;
                    $iqa_submission->competency_id = trim($matrix_id);
                    $iqa_submission->dropdown_id = isset($_POST['evidence_options'.$matrix_id]) ? $_POST['evidence_options'.$matrix_id] : '';    
                    DAO::saveObjectToTable($link, 'submissions_iqa', $iqa_submission, true);  
                }    
            }
        }    


        $evidence_ids = DAO::getSingleColumn($link, "SELECT id FROM evidence_criteria WHERE course_id = '$course_id' order by sequence");

        if(DB_NAME=='am_baltic_demo' or DB_NAME=='am_baltic')
        {
            if(EvidenceMatrixSubmission::allowedToEdit())
            {
                if(isset($_POST['matrix']))
                {
                    //foreach($_POST['matrix'] as $matrix_id)
                    foreach($evidence_ids as $matrix_id)
                    {
                        $matrix_id = trim($matrix_id);
                        $iqa_submission = new StdClass();
                        $iqa_submission->tr_id = $_POST['tr_id'];
                        $iqa_submission->submission_id = $vo->id;    
                        //$iqa_submission->iqa_status = $_POST['iqa_status2_id'.$matrix_id];
                        $iqa_submission->reject_reason = $_POST['reject_reason'.$matrix_id];
                        //$iqa_submission->first_sample = $_POST['attempt'.$matrix_id];
                        $iqa_submission->fail_reason1 = $_POST['fail_reason1_'.$matrix_id];
                        $iqa_submission->fail_reason2 = $_POST['fail_reason2_'.$matrix_id];
                        $iqa_submission->fail_reason3 = $_POST['fail_reason3_'.$matrix_id];
                        $iqa_submission->competency_id = trim($matrix_id);
                        $iqa_submission->rejection_comments = $_POST['rejection_comments_'.$matrix_id];
                        $iqa_submission->recommendation_comments = $_POST['recommendation_comments_'.$matrix_id];
                        $iqa_submission->recommendations_type = $_POST['recommendations_type_'.$matrix_id];
                        $iqa_submission->coach_actioned_status = $_POST['coach_actioned_status_'.$matrix_id];
                        if(isset($_POST['coach_recommendations_'.$matrix_id]))
                            $iqa_submission->coach_recommendations = 1;
                        else
                            $iqa_submission->coach_recommendations = 0;
                        if(isset($_POST['iqa_accept']) and in_array($matrix_id,$_POST['iqa_accept']))
                            $iqa_submission->iqa_accept=1;
                        else
                            $iqa_submission->iqa_accept=0;
                        if(isset($_POST['iqa_reject']) and in_array($matrix_id,$_POST['iqa_reject']))
                            $iqa_submission->iqa_reject=1;
                        else
                            $iqa_submission->iqa_reject=0;

                        DAO::saveObjectToTable($link, 'submissions_iqa', $iqa_submission, true);    
                    }    
                }
            }

            //if(DB_NAME=='am_baltic_demo')
              //  pre($_POST);
            $obj = TrainingRecord::getEvidenceProgress($link, $_POST['tr_id'], $_POST['course_id'],1);
            $tr_id = $_POST['tr_id'];
            if($obj->total > 0)
            {
                $percentage = round(($obj->matrix/$obj->total) * 100);
                if($obj->summative_raised_date_pct>0 and $percentage>=$obj->summative_raised_date_pct)    
                    DAO::execute($link, "update tr set summative_raised_date = CURDATE() where id = '$tr_id' and summative_raised_date is null");
            }

        }    

        http_redirect("do.php?_action=view_evidence_project&apl_id=$vo->project_id&tr_id=".$_POST['tr_id']);
    }
}
?>