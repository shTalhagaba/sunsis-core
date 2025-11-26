<?php
class view_save_fdil implements IAction
{
    public function execute(PDO $link)
    {
        $id = isset($_POST['id']) ? $_POST['id'] : '';
        $tr_id = isset($_POST['tr_id']) ? $_POST['tr_id'] : '';

        $fdil = DAO::getObject($link, "SELECT * FROM ob_learner_fdil WHERE id = '{$id}'");
        if(!isset($fdil->id))
        {
            throw new Exception("Invalid ID");
        }

        DAO::transaction_start($link);
        try {

            if($_POST['provider_sign'] != '')
            {
                $fdil->provider_sign = $_POST['provider_sign'];
                $fdil->provider_sign_date = date('Y-m-d H:i:s');
                $fdil->provider_sign_name = $_SESSION['user']->firstnames . ' ' . $_SESSION['user']->surname;
                $fdil->provider_sign_id = $_SESSION['user']->id;
            }

            DAO::saveObjectToTable($link, "ob_learner_fdil", $fdil);

            // save provider signatures
            if($_POST['provider_sign'] != '')
            {

                $provider_signatures_log = (object)[
                    'entity_id' => $fdil->id,
                    'entity_type' => 'ob_learner_fdil',
                    'user_sign' => $_POST['provider_sign'],
                    'user_sign_date' => date('Y-m-d H:i:s'),
                    'user_sign_name' => $_SESSION['user']->firstnames . ' ' . $_SESSION['user']->surname,
                    'user_type' => 'PROVIDER',
                ];

                DAO::saveObjectToTable($link, "documents_signatures", $provider_signatures_log);
            }

            DAO::transaction_commit($link);
        } catch (Exception $ex) {
            DAO::transaction_rollback($link);
            throw new Exception($ex->getMessage());
        }

        

        //EmployerAgreement::generatePdf($link, $agreement);

        http_redirect($_SESSION['bc']->getPrevious());
    }
}
