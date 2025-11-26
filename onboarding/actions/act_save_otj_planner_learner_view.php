<?php
class save_otj_planner_learner_view implements IUnauthenticatedAction
{
    public function execute(PDO $link)
    {
        $tr_id = isset($_REQUEST['tr_id']) ? $_REQUEST['tr_id'] : '';
        $key = isset($_REQUEST['key'])?$_REQUEST['key']:'';
     
        if(trim($tr_id) != '' && trim($key) != '')
        {
            if(!OnboardingHelper::isValidOtjPlannerLearnerViewUrl($link, $tr_id, $key))
            {
                http_redirect('do.php?_action=error_page');
            }
        }
        else
        {
            http_redirect('do.php?_action=error_page');
        }

        $tr = TrainingRecord::loadFromDatabase($link, $tr_id);
        if(is_null($tr))
        {
            http_redirect('do.php?_action=error_page');
            exit;
        }

        $ob_learner = $tr->getObLearnerRecord($link);

        DAO::transaction_start($link);
        try
        {
            $save_object = (object) [
                'tr_id' => $tr->id,
                'learner_sign' => $_POST['learner_sign'],
                'learner_sign_name' => $ob_learner->firstnames . ' ' . $ob_learner->surname,
                'learner_sign_date' => date('Y-m-d'),
            ];
            DAO::saveObjectToTable($link, "otj_planner_signatures", $save_object);

            $signatures_log = (object)[
                'entity_id' => $save_object->tr_id,
                'entity_type' => 'otj_planner_signatures',
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