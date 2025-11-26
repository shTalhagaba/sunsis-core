<?php
class save_employments implements IAction
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
            DAO::execute($link, "DELETE FROM ob_learners_ea WHERE tr_id = '{$tr->id}'");
            for($i = 1; $i <= 8; $i++)
            {
                $objEA = new stdClass();
                $objEA->tr_id = $tr->id;
                $objEA->ea_date_from = isset($_POST['ea_date_from'.$i])?$_POST['ea_date_from'.$i]:'';
                $objEA->ea_date_to = isset($_POST['ea_date_to'.$i])?$_POST['ea_date_to'.$i]:'';
                $objEA->ea_employer = isset($_POST['ea_employer'.$i]) ? substr($_POST['ea_employer'.$i], 0, 149):'';
                $objEA->ea_role = isset($_POST['ea_role'.$i]) ? substr($_POST['ea_role'.$i], 0, 149):'';
                $objEA->ea_resp = isset($_POST['ea_resp'.$i]) ? substr($_POST['ea_resp'.$i], 0, 149):'';
                if(trim($objEA->ea_date_from) != '' && trim($objEA->ea_employer) != '')
                    DAO::saveObjectToTable($link, 'ob_learners_ea', $objEA);
                unset($objEA);
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
            echo "Employment information has been updated successfully";
        }
        else
        {
            http_redirect("do.php?_action=read_training&id={$tr->id}");
        }

    }
}