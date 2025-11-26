<?php
class save_reason_not_participating implements IAction
{
	public function execute(PDO $link)
	{
		$id = isset($_REQUEST['id'])?$_REQUEST['id']:'';
		$reason = isset($_REQUEST['type'])?$_REQUEST['type']:'';
		
		
		if($id == '' || $reason=='')
		{
			throw new Exception("Missing or empty argument ");
		}
		
		
$query = <<<HEREDOC
insert into
	lookup_reason_not_participating (id, description)
VALUES(null,'$reason');
HEREDOC;
			DAO::execute($link, $query);

		$sql = "select id from lookup_reason_not_participating where description='$reason'";
			if($st = $link->query($sql))
			{	
				$r = $st->fetch();
				$d = $r['id'];
			}
			else
			{
				throw new DatabaseException($link, $sql);
			}
			
		header('Content-Type: text/xml');
		echo $d;	

	}	
}
?>