<?php
/**
 * Shared Action
 *
 */
class view_training_records_ilr implements IAction
{
	public function execute(PDO $link)
	{
		$view = ViewTrainingRecordsIlr::getInstance();
		$view->refresh($link, $_REQUEST);
		
		require_once('tpl_view_training_records_ilr.php');
	}

}
?>