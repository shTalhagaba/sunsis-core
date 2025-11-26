<?php
class save_employer_hs implements IAction
{
    public function execute(PDO $link)
    {
        $hs = new EmployerHealthAndSafety();
        $hs->populate($_POST);

        DAO::transaction_start($link);
        try{

            $hs->save($link);

            DAO::transaction_commit($link);
        }
        catch (Exception $ex)
        {
            DAO::transaction_rollback($link);
            throw new Exception($ex->getMessage());
        }




        http_redirect($_SESSION['bc']->getPrevious());
    }
}
?>