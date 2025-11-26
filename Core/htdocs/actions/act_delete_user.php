<?php
class delete_user implements IAction
{
	public function execute(PDO $link)
	{
		// Validate data entry
		$username = isset($_GET['username']) ? $_GET['username'] : '';
		$people_type = isset($_GET['people_type']) ? $_GET['people_type'] : '';
		
		if($username == '')
		{
			throw new Exception("Missing or empty querystring argument: \$id");
		}


		$doc = User::loadFromDatabase($link, $username);

		if($doc->isAdmin())
			throw new Exception("Super Admin cannot be deleted");
		
		if(is_null($doc))
		{
			throw new Exception("Could not find user with username #$username");
		}
		

		
		
		//DAO::transaction_start($link);
		try
		{
		/*	$acl = ACL::loadFromDatabase($link, 'users', $username);
			if(!$acl->isAuthorised($_SESSION['user'], 'write'))
			{
				throw new Exception("You do not have authorisation to delete this record");
			}
		*/	
			$doc->delete($link);
		}
		catch(Exception $e)
		{
			//DAO::transaction_rollback($link, $e);
			throw new WrappedException($e);
		}
		//DAO::transaction_commit($link);
		
		http_redirect($_SESSION['bc']->getPrevious());

	}
}
?>