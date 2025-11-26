<?php
class view_bespoke_training_plan implements IAction
{
	public function execute(PDO $link)
	{
        $subaction = isset($_REQUEST['subaction']) ? $_REQUEST['subaction'] : '';
        if($subaction == 'save_sign_form')
        {
            $this->save_sign_form($link);
        }

        $id = isset($_REQUEST['tr_id']) ? $_REQUEST['tr_id'] : '';
        if($id == '')
        {
            throw new Exception('Missing querystring argument: id');
        }

        $training_plan = DAO::getObject($link, "SELECT * FROM ob_learner_bespoke_training_plan WHERE tr_id = '{$id}'");
        if(!isset($training_plan->tr_id))
        {
            throw new Exception("Invalid ID");
        }

        $tr = TrainingRecord::loadFromDatabase($link, $training_plan->tr_id);
        $ob_learner = $tr->getObLearnerRecord($link);
        $framework = Framework::loadFromDatabase($link, $tr->framework_id);
        $trainer = $tr->trainers != '' ? User::loadFromDatabaseById($link, $tr->trainers) : new User();

		$_SESSION['bc']->add($link, "do.php?_action=view_bespoke_training_plan&id={$id}", "View Bespoke Training Plan");

        $form_data = is_null($training_plan->form_data) ? null : json_decode($training_plan->form_data);

        $is_disabled = ($training_plan->learner_sign != '' && $training_plan->provider_sign != '') ? false : false;

        $funding_year = DAO::getSingleValue($link, "SELECT contract_year FROM central.lookup_submission_dates WHERE '{$tr->practical_period_start_date}' BETWEEN start_submission_date AND last_submission_date ORDER BY contract_year DESC LIMIT 1");
        if($tr->practical_period_start_date >= '2023-08-01' && (DB_NAME == "am_ela") && in_array($tr->id, OnboardingHelper::UlnsToSkip($link)) )
        {
            $funding_year = '';
        }
        
        $provider = Organisation::loadFromDatabase($link, $tr->provider_id);
        
		require_once('tpl_view_bespoke_training_plan.php');
	}

    public function save_sign_form(PDO $link)
    {
        $overwriting = false;
        $tr_id = isset($_POST['tr_id']) ? $_POST['tr_id'] : '';
        if($tr_id == '')
        {
            return;
        }
        $training_plan = DAO::getObject($link, "SELECT * FROM ob_learner_bespoke_training_plan WHERE tr_id = '{$tr_id}'");
        if(!isset($training_plan->tr_id))
        {
            throw new Exception("Invalid ID");
        }
        if($training_plan->learner_sign != '' && $training_plan->provider_sign != '')
        {
            //throw new Exception("Form is already completed and signed.");
            $overwriting = true;
        }
        
        DAO::transaction_start($link);
        try
        {
            $_POST = Helpers::utf8_sanitize_recursive($_POST);
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

            $save_object = (object) [
                'tr_id' => $tr_id,
                'form_data' => json_encode($_POST),
                'provider_sign' => !$overwriting ? $_POST['provider_sign'] : $training_plan->provider_sign,
                'provider_sign_name' => !$overwriting ? $_SESSION['user']->firstnames . ' ' . $_SESSION['user']->surname : $training_plan->provider_sign_name,
                'provider_sign_date' => !$overwriting ? date('Y-m-d') : $training_plan->provider_sign_date,
                'provider_sign_id' => !$overwriting ? $_SESSION['user']->id : $training_plan->provider_sign_id,
            ];

            // users are completing and not signing
            if($_POST['provider_sign'] == '')
            {
                $save_object->provider_sign_name = null;
                $save_object->provider_sign_date = null;
            }

            DAO::saveObjectToTable($link, "ob_learner_bespoke_training_plan", $save_object);

            $provider_signatures_log = (object)[
                'entity_id' => $save_object->tr_id,
                'entity_type' => 'ob_learner_bespoke_training_plan',
                'user_sign' => $_POST['provider_sign'],
                'user_sign_date' => date('Y-m-d'),
                'user_sign_name' => $_SESSION['user']->firstnames . ' ' . $_SESSION['user']->surname,
                'user_type' => 'PROVIDER',
            ];

            if(!$overwriting)
                DAO::saveObjectToTable($link, "documents_signatures", $provider_signatures_log);
    
            DAO::transaction_commit($link);
        }
        catch(Exception $ex)
        {
            DAO::transaction_rollback($link);
            throw new Exception($ex->getMessage());
        }

        http_redirect('do.php?_action=read_training&id='.$tr_id);
    }
}
?>
