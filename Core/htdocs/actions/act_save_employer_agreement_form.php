<?php
class save_employer_agreement_form implements IUnauthenticatedAction
{
    public function execute(PDO $link)
    {
        DAO::transaction_start($link);
        try
        {
            $form = new EmployerAgreementForm();
            $form->populate($_POST);
            if(isset($_POST['english_exempt']))
                $form->english_exempt=1;
            else
                $form->english_exempt=0;
            $form->save($link);
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
            echo $form->id;
        }
        elseif(isset($_SESSION['user']->type))
        {
            http_redirect('do.php?_action=rec_read_employer&id='.$form->employer_id);
        }
        else
        {
            echo "Saved";
        }
    }

}
?>



