<?php
class ajax_assign_qualifications implements IAction
{
	public function execute(PDO $link)
	{
		$qualification_id = isset($_REQUEST['id'])?$_REQUEST['id']:'';
		$internaltitle = isset($_REQUEST['internaltitle'])?addslashes((string)$_REQUEST['internaltitle']):'';
		$organisation_id = isset($_REQUEST['organisation_id'])?$_REQUEST['organisation_id']:'';
		
// importing qualification from qualification database 		
$query = <<<HEREDOC
insert into provider_qualifications (org_id, qualification_id, internaltitle)
values ($organisation_id, '$qualification_id', '$internaltitle')
HEREDOC;
		DAO::execute($link, $query);

		
	}
}
?>