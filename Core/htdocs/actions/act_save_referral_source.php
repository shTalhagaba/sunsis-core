<?php
class save_referral_source implements IAction
{
	public function execute(PDO $link)
	{
		header('Content-Type: text/xml');
		$id = isset($_REQUEST['id'])?$_REQUEST['id']:'';
		$role = isset($_REQUEST['type'])?$_REQUEST['type']:'';

		if($id == '' || $role=='')
		{
			throw new Exception("Missing or empty argument ");
		}


		$query = <<<HEREDOC
insert into
	lookup_referral_source (id, description)
VALUES(null,'$role');
HEREDOC;
		DAO::execute($link, $query);

		$sql = "select id from lookup_referral_source where description='$role' ";
		if($st = $link->query($sql))
		{
			$r = $st->fetch();
			$d = $r['id'];
		}
		else
		{
			throw new DatabaseException($link, $sql);
		}

		echo $d;
	}
}
?>