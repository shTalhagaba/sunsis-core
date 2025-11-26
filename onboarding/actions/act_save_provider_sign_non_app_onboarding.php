<?php
class save_provider_sign_non_app_onboarding implements IAction
{
    public function execute(PDO $link)
    {
        $tr_id = isset($_REQUEST['tr_id']) ? $_REQUEST['tr_id'] : '';

        $tr = TrainingRecord::loadFromDatabase($link, $tr_id);
        $tr->tp_sign_name = isset($_POST['tp_sign_name']) ? $_POST['tp_sign_name'] : '';
        $tr->tp_sign = isset($_POST['tp_sign']) ? $_POST['tp_sign'] : '';
        $tr->tp_sign_date = isset($_POST['tp_sign_date']) ? $_POST['tp_sign_date'] : '';
        $tr->status_code = TrainingRecord::STATUS_COMPLETED;

        $induction_checklist_provider_agree = isset($_REQUEST['induction_checklist_provider_agree']) ? $_REQUEST['induction_checklist_provider_agree'] : '';
        $lookup_result = DAO::getResultset($link, "SELECT * FROM lookup_induction_checklist", DAO::FETCH_ASSOC);
        $tr_checklist_entries = DAO::getSingleColumn($link, "SELECT checklist_item_id FROM ob_learner_induction_checklist WHERE tr_id = '{$tr_id}'");
        
        DAO::transaction_start($link);
        try
        {
            $tr->save($link);

            foreach($lookup_result AS $lookup_row)
            {
                // if entry is not in the table then create it
                if(!in_array($lookup_row['id'], $tr_checklist_entries))
                {
                    $entry = (object)[
                        'tr_id' => $tr_id,
                        'checklist_item_id' => $lookup_row['id'],
                    ];
                    DAO::saveObjectToTable($link, "ob_learner_induction_checklist", $entry);
                }
                else
                {
                    DAO::execute($link, "UPDATE ob_learner_induction_checklist SET provider_agree = 0 WHERE tr_id = '{$tr_id}'");
                }
            }
            foreach($lookup_result AS $lookup_row)
            {
                if(in_array($lookup_row['id'], $induction_checklist_provider_agree))
                {
                    DAO::execute($link, "UPDATE ob_learner_induction_checklist SET provider_agree = 1 WHERE tr_id = '{$tr_id}' AND checklist_item_id = '{$lookup_row['id']}'");
                }
            }
            DAO::transaction_commit($link);
        }
        catch (Exception $e)
        {
            DAO::transaction_rollback($link, $e);
            throw new WrappedException($e);
        }

        http_redirect($_SESSION['bc']->getPrevious());
    }
}
?>