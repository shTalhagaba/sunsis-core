<?php
class edit_ob_learner_als implements IAction
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

        $assessment = DAO::getObject($link, "SELECT * FROM ob_learner_additional_support WHERE tr_id = '{$id}'");
        if(!isset($assessment->tr_id))
        {
            $assessment = new stdClass();
            $assessment->tr_id = $id;
            $assessment = DAO::saveObjectToTable($link, "ob_learner_additional_support", $assessment);
            $assessment = DAO::getObject($link, "SELECT * FROM ob_learner_additional_support WHERE tr_id = '{$id}'");
        }

        $tr = TrainingRecord::loadFromDatabase($link, $assessment->tr_id);
        $ob_learner = $tr->getObLearnerRecord($link);
        $framework = Framework::loadFromDatabase($link, $tr->framework_id);

		$_SESSION['bc']->add($link, "do.php?_action=edit_ob_learner_als&id={$id}", "Edit Additional Learning Neends");

        $form_data = is_null($assessment->form_data) ? null : json_decode($assessment->form_data);

        $is_disabled = ($assessment->learner_sign != '' && $assessment->provider_sign != '') ? false : false;

        $funding_year = DAO::getSingleValue($link, "SELECT contract_year FROM central.lookup_submission_dates WHERE '{$tr->practical_period_start_date}' BETWEEN start_submission_date AND last_submission_date ORDER BY contract_year DESC LIMIT 1");
        $funding_year = 2023;
	    if(
            $tr->practical_period_start_date > '2024-05-31' || 
            (isset($form_data->funding_year) && $form_data->funding_year == 2024) // this is if 2024 info is saved. 
        )
        {
		if(!in_array($tr->id, [2149, 2159, 2160, 2180]))
            		$funding_year = 2024;
        }

        $providerLogo = "images/logos/" . SystemConfig::getEntityValue($link, 'logo');
        $provider = Organisation::loadFromDatabase($link, $tr->provider_id);
        if(!is_null($provider->provider_logo))
        {
            $providerLogo = $provider->provider_logo;
        }
        
		require_once('tpl_edit_ob_learner_als.php');
	}

    public function save_sign_form(PDO $link)
    {
        $overwriting = false;
        $tr_id = isset($_POST['tr_id']) ? $_POST['tr_id'] : '';
        if($tr_id == '')
        {
            return;
        }
        $assessment = DAO::getObject($link, "SELECT * FROM ob_learner_additional_support WHERE tr_id = '{$tr_id}'");
        if(!isset($assessment->tr_id))
        {
            throw new Exception("Invalid ID");
        }
        if($assessment->learner_sign != '' && $assessment->provider_sign != '')
        {
            //throw new Exception("Form is already completed and signed.");
            $overwriting = true;
        }
        
        DAO::transaction_start($link);
        try
        {
            $_POST = Helpers::utf8_sanitize_recursive($_POST);

            $save_object = (object) [
                'tr_id' => $tr_id,
                'form_data' => json_encode($_POST),
                'provider_sign' => !$overwriting ? $_POST['provider_sign'] : $assessment->provider_sign,
                'provider_sign_name' => !$overwriting ? $_SESSION['user']->firstnames . ' ' . $_SESSION['user']->surname : $assessment->provider_sign_name,
                'provider_sign_date' => !$overwriting ? date('Y-m-d') : $assessment->provider_sign_date,
                'provider_sign_id' => !$overwriting ? $_SESSION['user']->id : $assessment->provider_sign_id,
            ];

            // users are completing and not signing
            if($_POST['provider_sign'] == '')
            {
                $save_object->provider_sign_name = null;
                $save_object->provider_sign_date = null;
            }

            DAO::saveObjectToTable($link, "ob_learner_additional_support", $save_object);

            $provider_signatures_log = (object)[
                'entity_id' => $save_object->tr_id,
                'entity_type' => 'ob_learner_additional_support',
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
