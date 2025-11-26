<?php
class grant implements IAction
{
	public function execute(PDO $link)
	{
		$sql = <<<HEREDOC
SELECT
	id, username
FROM
	tr;
HEREDOC;
		
		$st = $link->query($sql);
		if($st) 
		{
			while($row = $st->fetch())
			{
				
				$id=$row['id'];
				$username = $row['username'];
								
				$query = <<<HEREDOC
insert into
	acl (resource_category, resource_id, privilege, ident)
VALUES('trainingrecord','$id','read', concat('$username','/main/vf'));
HEREDOC;
				DAO::execute($link, $query);
			}
		}
	}
}
?>
