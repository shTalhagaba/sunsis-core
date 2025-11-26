<?php
class save_job_role implements IAction
{
	public function execute(PDO $link)
	{
		header('Content-Type: text/xml');
		$id = isset($_REQUEST['id'])?$_REQUEST['id']:'';
		$role = isset($_REQUEST['type'])?$_REQUEST['type']:'';
		$usertype = isset($_REQUEST['usertype'])?$_REQUEST['usertype']:'';
		
		
		if($id == '' || $role=='' || $usertype=='')
		{
			throw new Exception("Missing or empty argument ");
		}
		
		
$query = <<<HEREDOC
insert into
	lookup_job_roles (id, description, cat)
VALUES(null,'$role','$usertype');
HEREDOC;
		DAO::execute($link, $query);

		$sql = "select id from lookup_job_roles where description='$role' and cat='$usertype'";
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