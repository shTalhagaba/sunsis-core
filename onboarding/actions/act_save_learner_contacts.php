<?php
class save_learner_contacts implements IAction
{
    public function execute(PDO $link)
    {
        $tr_id = isset($_POST['tr_id']) ? $_POST['tr_id'] : '';
        if($tr_id == '')
            throw new Exception("Missing querystring argument: tr_id");

        $tr = TrainingRecord::loadFromDatabase($link, $tr_id);
        if(is_null($tr))
            throw new Exception("Invalid tr_id");

        DAO::transaction_start($link);
        try
        {
            DAO::execute($link, "DELETE FROM ob_learner_emergency_contacts WHERE tr_id = '{$tr->id}'");
            for($i = 1; $i <= 5; $i++)
            {
                $obContact = new stdClass();
                $obContact->em_con_seq = $i;
                $obContact->tr_id = $tr->id;
                $obContact->em_con_title = isset($_POST['em_con_title'.$i])?$_POST['em_con_title'.$i]:'';
                $obContact->em_con_name = isset($_POST['em_con_name'.$i])?$_POST['em_con_name'.$i]:'';
                $obContact->em_con_rel = isset($_POST['em_con_rel'.$i])?$_POST['em_con_rel'.$i]:'';
                $obContact->em_con_tel = isset($_POST['em_con_tel'.$i])?$_POST['em_con_tel'.$i]:'';
                $obContact->em_con_mob = isset($_POST['em_con_mob'.$i])?$_POST['em_con_mob'.$i]:'';
                $obContact->em_con_email = isset($_POST['em_con_email'.$i])?$_POST['em_con_email'.$i]:'';
                if(trim($obContact->em_con_name) != '')
                    DAO::saveObjectToTable($link, 'ob_learner_emergency_contacts', $obContact);
                unset($obContact);
            }

            DAO::transaction_commit($link);
        }
        catch (Exception $ex)
        {
            DAO::transaction_rollback($link);
            throw new Exception($ex->getMessage());
        }

        if(IS_AJAX)
        {
            echo "Learner contacts have been updated successfully";
        }
        else
        {
            http_redirect("do.php?_action=read_training&id={$tr->id}");
        }

    }
}