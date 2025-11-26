<?php
class save_bespoke_training_plan_form implements IUnauthenticatedAction
{
    public function execute(PDO $link)
    {
        $id = isset($_REQUEST['id']) ? $_REQUEST['id'] : '';
        $key = isset($_REQUEST['key'])?$_REQUEST['key']:'';
     
        if(trim($id) != '' && trim($key) != '')
        {
            if(!OnboardingHelper::isValidBespokeTrainingPlanFormUrl($link, $id, $key))
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

	if(!isset($_POST['question15']))
        {
            $_POST["question15"] = '';
        }
        if(!isset($_POST['question16']))
        {
            $_POST["question16"] = '';
        }
        if(!isset($_POST['question17']))
        {
            $_POST["question17"] = '';
        }
        if(!isset($_POST['question18']))
        {
            $_POST["question18"] = '';
        }

        DAO::transaction_start($link);
        try
        {
            $_POST = Helpers::utf8_sanitize_recursive($_POST);

            $save_object = (object) [
                'tr_id' => $tr->id,
                'learner_sign' => $_POST['learner_sign'],
                'learner_sign_name' => $ob_learner->firstnames . ' ' . $ob_learner->surname,
                'learner_sign_date' => date('Y-m-d'),
                'form_data' => json_encode($_POST),
            ];
            DAO::saveObjectToTable($link, "ob_learner_bespoke_training_plan", $save_object);

            $signatures_log = (object)[
                'entity_id' => $save_object->tr_id,
                'entity_type' => 'ob_learner_bespoke_training_plan',
                'user_sign' => $_POST['learner_sign'],
                'user_sign_date' => date('Y-m-d'),
                'user_sign_name' => $ob_learner->firstnames . ' ' . $ob_learner->surname,
                'user_type' => 'LEARNER',
            ];

            DAO::saveObjectToTable($link, "documents_signatures", $signatures_log);
    
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