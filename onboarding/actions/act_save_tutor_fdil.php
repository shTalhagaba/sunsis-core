<?php
class save_tutor_fdil implements IUnauthenticatedAction
{
    public function execute(PDO $link)
    {
        $id = isset($_REQUEST['id']) ? $_REQUEST['id'] : '';
        $key = isset($_REQUEST['key'])?$_REQUEST['key']:'';
     
        if(trim($id) != '' && trim($key) != '')
        {
            if(!OnboardingHelper::isValidTutorFdilUrl($link, $id, $key))
            {
                http_redirect('do.php?_action=error_page');
            }
        }
        else
        {
            http_redirect('do.php?_action=error_page');
        }

        $tr = TrainingRecord::loadFromDatabase($link, $id);
        if(is_null($tr))
        {
            http_redirect('do.php?_action=error_page');
            exit;
        }   

        $ob_learner = $tr->getObLearnerRecord($link);

        $fdil = DAO::getObject($link, "SELECT * FROM ob_learner_fdil WHERE tr_id = '{$tr->id}'");
        if(!isset($fdil->tr_id))
        {
            http_redirect('do.php?_action=error_page');
            exit;
        }
        $fdil->tutor_sign = isset($_REQUEST['tutor_sign']) ? $_REQUEST['tutor_sign'] : '';
        $fdil->tutor_sign_name = isset($_REQUEST['tutor_sign_name']) ? $_REQUEST['tutor_sign_name'] : '';
        $fdil->tutor_sign_date = date('Y-m-d');

        DAO::transaction_start($link);
        try
        {
            DAO::saveObjectToTable($link, "ob_learner_fdil", $fdil);

            $employer_signatures_log = (object)[
                'entity_id' => $fdil->tr_id,
                'entity_type' => 'ob_learner_fdil',
                'user_sign' => $_POST['tutor_sign'],
                'user_sign_date' => date('Y-m-d'),
                'user_sign_name' => $fdil->tutor_sign_name,
                'user_type' => 'TUTOR',
            ];

            DAO::saveObjectToTable($link, "documents_signatures", $employer_signatures_log);
    
            DAO::transaction_commit($link);
        }
        catch(Exception $ex)
        {
            DAO::transaction_rollback($link);
            throw new Exception($ex->getMessage());
        }



        http_redirect('do.php?_action=cs_completed');
    }

}