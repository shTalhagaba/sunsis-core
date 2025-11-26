<?php
class save_help implements IAction
{
	public function execute(PDO $link)
	{
		if($_SESSION['role'] != 'admin')
		{
			throw new UnauthorizedException();
		}
	    
		// Populate Value Object from user's <form> submission
		$vo = new Help();
		$vo->populate($_POST);

		try
		{
			DAO::transaction_start($link);
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
			echo $vo->id;
		}
		else
		{
			http_redirect('do.php?_action=read_help&id=' . $vo->id);
		}

	}
	
}
?>