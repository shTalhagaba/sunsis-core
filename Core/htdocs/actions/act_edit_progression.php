<?php
class edit_progression implements IAction
{
	public function execute(PDO $link)
	{
        $subaction = isset($_REQUEST['subaction']) ? $_REQUEST['subaction'] : '';
        $tr_id = isset($_REQUEST['tr_id']) ? $_REQUEST['tr_id'] : '';

        if($tr_id == '')
        {
            throw new Exception("Missing querystring arguments: tr_id, inductee_id, induction_id");
        }
        else
        {
            $progression = Progression::loadFromDatabase($link, $tr_id);
        }

        if($subaction == 'save')
        {
            $this->saveInformation($link, $_REQUEST);            
        }

        $_SESSION['bc']->add($link, "do.php?_action=edit_progression&tr_id={$tr_id}", "Update Progression Information");

        include('tpl_edit_progression.php');
    }

    public function saveInformation(PDO $link, $data)
    {
        $object = (object) $data;

        DAO::saveObjectToTable($link, 'progression', $object);
        
        DAO::execute($link, "UPDATE progression SET month_9_learner_date =  NOW() WHERE month_9_learner_date IS NULL AND month_9_learner IS NOT NULL;");
        DAO::execute($link, "UPDATE progression SET month_12_learner_date =  NOW() WHERE month_12_learner_date IS NULL AND month_12_learner IS NOT NULL;");
        DAO::execute($link, "UPDATE progression SET latest_learner_status_date =  NOW() WHERE latest_learner_status_date IS NULL AND latest_learner_status IS NOT NULL;");
        DAO::execute($link, "UPDATE progression SET month_9_employer_date =  NOW() WHERE month_9_employer_date IS NULL AND month_9_employer IS NOT NULL;");
        DAO::execute($link, "UPDATE progression SET month_12_employer_date =  NOW() WHERE month_12_employer_date IS NULL AND month_12_employer IS NOT NULL;");
        DAO::execute($link, "UPDATE progression SET latest_employer_status_date =  NOW() WHERE latest_employer_status_date IS NULL AND latest_employer_status IS NOT NULL;");

        http_redirect('do.php?_action=read_training_record&id='.$data['tr_id']);
    }
}