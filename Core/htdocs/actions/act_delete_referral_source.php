<?php
class delete_referral_source implements IAction
{
	public function execute(PDO $link)
	{
		header('Content-Type: text/xml');
		$role = isset($_REQUEST['type'])?$_REQUEST['type']:'';

		if($role=='')
		{
			throw new Exception("Missing or empty argument ");
		}


		$query = <<<HEREDOC
delete from
	lookup_referral_source where description = '$role'
HEREDOC;
		DAO::execute($link, $query);

	}
}
?>