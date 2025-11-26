<?php
class save_assessor_review_form implements IUnauthenticatedAction
{
    public function execute(PDO $link)
    {
        $vo = new AssessorReviewForm();

        // Remove learner Signature when mandatory information is missing
        if($_POST['source']==2)
        {
            if($_POST['learner_comment']=="")
            {
                //$_POST['signature_learner_font']="";
                //$_POST['signature_learner_name']="";
            }
        }

        // Remove assessor Signature when mandatory information is missing
        if($_POST['source']==3)
        {
            if($_POST['employer_progress_review']=="" or $_POST['behaviours']=="" or $_POST['ability']=="" or $_POST['skills_knowledge']=="" or $_POST['achievements']=="")
            {
                //$_POST['signature_employer_font']="";
                //$_POST['signature_employer_name']="";
            }
        }

        $vo->populate($_POST);


        DAO::transaction_start($link);
        try
        {
            if($vo->review_id != '')
            {
                $existing = AssessorReviewForm::loadFromDatabase($link, $vo->review_id);
                if($existing->signature_employer_font!='' && $vo->signature_employer_font=='')
                    $vo->signature_employer_font = $existing->signature_employer_font;
            }

           $vo->save($link);

            DAO::transaction_commit($link);
        }
        catch(Exception $e)
        {
            DAO::transaction_rollback($link, $e);
            throw new WrappedException($e);
        }

        if(IS_AJAX)
        {
            header("Content-Type: text/plain");
            echo $vo->review_id;
        }
        elseif(isset($_SESSION['user']->type))
        {
            http_redirect('do.php?_action=read_training_record&id='.$vo->tr_id);
        }
        else
        {
            echo "Saved";
        }
    }

        /*
        $tr_id = isset($_REQUEST['tr_id'])?$_REQUEST['tr_id']:'';
        $meeting_date = isset($_REQUEST['meeting_date'])?$_REQUEST['meeting_date']:'';
        $assessor_comments = isset($_REQUEST['assessor_comments'])?$_REQUEST['assessor_comments']:'';
        $assessor_signed = isset($_REQUEST['assessor_sign'])?$_REQUEST['assessor_sign']:'';
        $learner_comments = isset($_REQUEST['learner_comments'])?$_REQUEST['learner_comments']:'';
        $learner_signed = isset($_REQUEST['learner_sign'])?$_REQUEST['learner_sign']:'';
        $employer_comments = isset($_REQUEST['employer_comments'])?$_REQUEST['employer_comments']:'';
        $employer_signed = isset($_REQUEST['employer_sign'])?$_REQUEST['employer_sign']:'';
        $source = isset($_REQUEST['source'])?$_REQUEST['source']:'';

        if($assessor_signed=='on')
            $assessor_signed = 1;
        else
            $assessor_signed = 0;

        if($learner_signed=='on')
            $learner_signed = 1;
        else
            $learner_signed = 0;

        if($employer_signed=='on')
            $employer_signed = 1;
        else
            $employer_signed = 0;

        DAO::execute($link, "replace into assessor_review_form values($tr_id,'$meeting_date','$assessor_comments',$assessor_signed,'$learner_comments',$learner_signed,'$employer_comments',$employer_signed);");

        if($source=='assessor')
        {
            http_redirect("do.php?_action=read_training_record&id=".$tr_id);
        }
        else
        {
            echo '<script language="javascript">';
            echo 'alert("Successful!")';
            echo '</script>';
            echo "<script>window.close();</script>";    }
        }
        */
}
?>



