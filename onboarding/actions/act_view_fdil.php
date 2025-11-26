<?php
class view_fdil implements IAction
{
	public function execute(PDO $link)
	{
        $id = isset($_REQUEST['id']) ? $_REQUEST['id'] : '';
        if($id == '')
        {
            throw new Exception('Missing querystring argument: id');
        }

        $fdil = DAO::getObject($link, "SELECT * FROM ob_learner_fdil WHERE id = '{$id}'");
        if(!isset($fdil->id))
        {
            throw new Exception("Invalid ID");
        }

        $tr = TrainingRecord::loadFromDatabase($link, $fdil->tr_id);
        $ob_learner = $tr->getObLearnerRecord($link);
        $framework = Framework::loadFromDatabase($link, $tr->framework_id);
        $trainer = $tr->trainers != '' ? User::loadFromDatabaseById($link, $tr->trainers) : new User();

		$_SESSION['bc']->add($link, "do.php?_action=view_fdil&id={$id}", "View FDIL");


		require_once('tpl_view_fdil.php');
	}
}
?>