<?php
/**
 * Shared Action
 *
 */
class print_training_records implements IAction
{
	public function execute(PDO $link)
	{
		$view = ViewTrainingRecords::getInstance($link);
		$view->refresh($link, $_REQUEST);

		require_once('tpl_print_training_records.php');
		//echo "OK";
	}

}
?>