<?php
class delete_announcement implements IAction
{
	public function execute(PDO $link)
	{
		$id = isset($_REQUEST['id'])?$_REQUEST['id']:'';

		if($id == '')
		{
			throw new Exception("Missing or empty querystring argument: id");
		}

		$vo = Announcement::loadFromDatabase($link, $id);
		if(is_null($vo))
		{
			throw new Exception("No announcement with id #$id found");
		}

	
		// Check permissions
		if( !$vo->isEditor($link) )
		{
			throw new UnauthorizedException();
		}

			
		try
		{
			//DAO::transaction_start($link);
			$vo->delete($link);
			//DAO::transaction_commit($link);
		}
		catch(Exception $e)
		{
			//DAO::transaction_rollback($link, $e);
			throw new WrappedException($e);
		}
		
		
		
		if(IS_AJAX)
		{
			echo 1;
		}
		else
		{
			http_redirect('do.php?_action=home_page');
		}
	}

}
?>
