<?php
class save_initial_contract implements IAction
{
    public function execute(PDO $link)
    {
	if(!isset($_POST['wm_auth']))
        {
            $_POST = array_merge($_POST, ['wm_auth' => 0]);
        }
        $schedule = new EmployerSchedule1();
        $schedule->id = $_POST['id'] == '' ? null : $_POST['id'];
        $schedule->tr_id = $_POST['tr_id'];
        $schedule->employer_id = $_POST['employer_id'];
        $_POST = Helpers::utf8_sanitize_recursive($_POST);
        $schedule->detail = json_encode($_POST);
        $schedule->tp_sign_name = isset($_POST['tp_sign_name']) ? $_POST['tp_sign_name'] : '';
        $schedule->tp_sign = isset($_POST['tp_sign']) ? $_POST['tp_sign'] : '';
        $schedule->tp_sign_date = isset($_POST['tp_sign_date']) ? $_POST['tp_sign_date'] : '';

        $tr = TrainingRecord::loadFromDatabase($link, $_POST['tr_id']);
        $tr->training_cost = isset($_POST['training_cost']) ? $_POST['training_cost'] : 0;
        $tr->training_material_cost = isset($_POST['training_material_cost']) ? $_POST['training_material_cost'] : 0;
        $tr->reg_exam_certification_cost = isset($_POST['reg_exam_certification_cost']) ? $_POST['reg_exam_certification_cost'] : 0;
        $tr->total_training_cost = isset($_POST['total_training_cost']) ? $_POST['total_training_cost'] : 0;
        $tr->epa_cost = isset($_POST['epa_cost']) ? $_POST['epa_cost'] : 0;
        $tr->total_negotiated_price = isset($_POST['total_negotiated_price']) ? $_POST['total_negotiated_price'] : 0;
        $tr->subcontractor_training_cost = isset($_POST['subcontractor_training_cost']) ? $_POST['subcontractor_training_cost'] : 0;
        $tr->subcontractor_management_cost = isset($_POST['subcontractor_management_cost']) ? $_POST['subcontractor_management_cost'] : 0;
        $tr->additional_cost_funded_by_employer = isset($_POST['additional_cost_funded_by_employer']) ? $_POST['additional_cost_funded_by_employer'] : 0;
        $tr->additional_cost_funded_by_provider = isset($_POST['additional_cost_funded_by_provider']) ? $_POST['additional_cost_funded_by_provider'] : 0;
        $tr->additional_cost_resit1 = isset($_POST['additional_cost_resit1']) ? $_POST['additional_cost_resit1'] : 0;
        $tr->additional_cost_resit2 = isset($_POST['additional_cost_resit2']) ? $_POST['additional_cost_resit2'] : 0;
        $tr->fs_maths_opt_in = isset($_POST['fs_maths_opt_in']) ? $_POST['fs_maths_opt_in'] : '';
        $tr->fs_eng_opt_in = isset($_POST['fs_eng_opt_in']) ? $_POST['fs_eng_opt_in'] : '';

        $existing_record = TrainingRecord::loadFromDatabase($link, $_POST['tr_id']);
        $log_string = $existing_record->buildAuditLogString($link, $tr);
        $note = null;
        if ($log_string != '') 
        {
            $note = new Note();
            $note->subject = "Training record details updated";
            $note->note = $log_string;
        }

        DAO::transaction_start($link);
        try
        {
            $schedule->save($link);
            $tr->save($link);
            if(isset($note) && !is_null($note))
            {
                $note->is_audit_note = true;
                $note->parent_table = 'tr';
                $note->parent_id = $tr->id;
                $note->created = date('Y-m-d H:i:s');
                $note->save($link);
            }

            DAO::transaction_commit($link);    
        }
        catch(Exception $e)
        {
            DAO::transaction_rollback($link, $e);
            throw new WrappedException($e);
        }



        http_redirect($_SESSION['bc']->getPrevious());
    }
}
