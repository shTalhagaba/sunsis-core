<?php
class save_fdil implements IAction
{
	public function execute(PDO $link)
	{   
        $tr_id = isset($_REQUEST['tr_id']) ? $_REQUEST['tr_id'] : '';

        if($tr_id == '')
        {
            throw new Exception("Missing querystring argument: tr_id");
        }

        $fdil = DAO::getObject($link, "SELECT * FROM ob_learner_fdil WHERE tr_id = '{$tr_id}'");
        if(!isset($fdil->tr_id))
        {
            $fdil = new stdClass();
	    $fdil->id = null;	
        }

        $fdil->tr_id = $tr_id;
        foreach(["fdil_session_date", "fdil_session_start_time", "fdil_session_end_time", "fdil_session_hours", "fdil_trainer_name", "fdil_iqa_allocated"] AS $field)
        {
            $fdil->$field = isset($_REQUEST[$field]) ? $_REQUEST[$field] : '';
        }

	if(is_null($fdil->id))
        {
            $fdil->created_by = $_SESSION['user']->id;
            $fdil->created_at = date('Y-m-d h:i:s');
        }
        else
        {
            $fdil->updated_at = date('Y-m-d h:i:s');
        }

        DAO::saveObjectToTable($link, "ob_learner_fdil", $fdil);

        http_redirect("do.php?_action=read_training&id={$tr_id}");
    }
}