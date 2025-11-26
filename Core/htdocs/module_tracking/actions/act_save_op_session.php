<?php
class save_op_session implements IAction
{
	public function execute(PDO $link)
	{
		//pre($_REQUEST);

		$vo = new OperationsSession();
		$vo->populate($_REQUEST);

/*		$key = explode('|', $vo->unit_ref);
		$owner_reference = $key[0];
		$qualification_id = $key[1];
		$reference = $key[2];
		$framework_id = $key[3];
		$vo->unit_ref = $owner_reference;
		$vo->qualification_id = $qualification_id;
		$vo->framework_id = $framework_id;
		$vo->reference = $reference;*/

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
			http_redirect("do.php?_action=view_operations_schedule_tabular");
		}
	}
}
?>
