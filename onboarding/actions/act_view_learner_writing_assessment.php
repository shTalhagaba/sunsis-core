<?php
class view_learner_writing_assessment implements IAction
{
	public function execute(PDO $link)
	{
        $subaction = isset($_REQUEST['subaction']) ? $_REQUEST['subaction'] : '';
        if($subaction == 'save_sign_assessment')
        {
            $this->save_sign_assessment($link);
        }

        $id = isset($_REQUEST['tr_id']) ? $_REQUEST['tr_id'] : '';
        if($id == '')
        {
            throw new Exception('Missing querystring argument: id');
        }

        $assessment = DAO::getObject($link, "SELECT * FROM ob_learner_writing_assessment WHERE tr_id = '{$id}'");
        if(!isset($assessment->tr_id))
        {
            throw new Exception("Invalid ID");
        }

        $tr = TrainingRecord::loadFromDatabase($link, $assessment->tr_id);
        $ob_learner = $tr->getObLearnerRecord($link);
        $framework = Framework::loadFromDatabase($link, $tr->framework_id);
        $trainer = $tr->trainers != '' ? User::loadFromDatabaseById($link, $tr->trainers) : new User();

		$_SESSION['bc']->add($link, "do.php?_action=view_learner_writing_assessment&id={$id}", "View Learner Writing Assessment");

        $marking = $assessment->marking != '' ? json_decode($assessment->marking) : new stdClass();


	$provider = Organisation::loadFromDatabase($link, $tr->provider_id);
        $providerLogo = "images/logos/" . SystemConfig::getEntityValue($link, 'logo');
        if(!is_null($provider->provider_logo))
        {
            $providerLogo = $provider->provider_logo;
        }

	require_once('tpl_view_learner_writing_assessment.php');
}

    public function save_sign_assessment(PDO $link)
    {
        $tr_id = isset($_POST['tr_id']) ? $_POST['tr_id'] : '';
        if($tr_id == '')
        {
            return;
        }

        $total_marks = 0;
        $marking = [];
        for($i = 1; $i <= 8; $i++)
        {
            if(isset($_POST["s{$i}"]))
            {
                $total_marks += $_POST["s{$i}"];
                $marking["s{$i}"] = $_POST["s{$i}"];
            }
        }

        DAO::transaction_start($link);
        try
        {
            $save_object = (object) [
                'tr_id' => $tr_id,
                'learner_comments' => $_POST['learner_comments'],
                'provider_sign' => $_POST['provider_sign'],
                'total_marks' => $total_marks,
                'marking' => json_encode($marking),
            ];
	    if($_POST['provider_sign'] != '')
            {
                $save_object->provider_sign_name = $_POST['provider_sign_name'];
                $save_object->provider_sign_date = $_POST['provider_sign_date'];
                $save_object->provider_sign_id = $_SESSION['user']->id;
            }	
            DAO::saveObjectToTable($link, "ob_learner_writing_assessment", $save_object);

            $employer_signatures_log = (object)[
                'entity_id' => $save_object->tr_id,
                'entity_type' => 'ob_learner_writing_assessment',
                'user_sign' => $_POST['provider_sign'],
                'user_sign_date' => date('Y-m-d'),
                'user_sign_name' => $_SESSION['user']->firstnames . ' ' . $_SESSION['user']->surname,
                'user_type' => 'PROVIDER',
            ];

            DAO::saveObjectToTable($link, "documents_signatures", $employer_signatures_log);
    
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
