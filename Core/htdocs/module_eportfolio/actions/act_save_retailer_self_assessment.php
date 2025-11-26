<?php
class save_retailer_self_assessment implements IAction
{
	public function execute(PDO $link)
	{
		//pre($_POST);

		if(!isset($_POST['tr_id']) || $_POST['tr_id'] == '')
			throw new Exception('Missing querystring argument: tr_id. Save action aborted.');

		DAO::transaction_start($link);
		try
		{
			DAO::execute($link, "DELETE FROM retailer_self_assessment WHERE tr_id = '{$_POST['tr_id']}'");

			if(isset($_POST['criteria_codes']))
			{
				$o = new stdClass();
				$o->tr_id = $_POST['tr_id'];
				$o->criteria_codes = $_POST['criteria_codes'];
				DAO::saveObjectToTable($link, 'retailer_self_assessment', $o);
			}

			DAO::transaction_commit($link);
		}
		catch(Exception $e)
		{
			DAO::transaction_rollback($link, $e);
			throw new WrappedException($e);
		}

		if(IS_AJAX)
		{
			header("Content-Type: text/plain");
			echo 'success';
		}
		else
		{
			http_redirect($_SESSION['bc']->getPrevious());
		}

	}
}