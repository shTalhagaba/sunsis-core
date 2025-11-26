<?php
class save_complaint_employer implements IAction
{
	public function execute(PDO $link)
	{
		//pre($_REQUEST);

		$vo = new ComplaintEmployer($_POST['record_id']);
		$vo->populate($_REQUEST);

		//pre($vo);

		DAO::transaction_start($link);
		try
		{
			$vo->save($link);

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
?>
