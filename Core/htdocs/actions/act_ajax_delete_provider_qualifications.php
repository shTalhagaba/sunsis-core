<?php
class ajax_delete_provider_qualifications implements IAction
{
	public function execute(PDO $link)
	{
		$org_id = isset($_REQUEST['organisation_id'])?$_REQUEST['organisation_id']:'';
	
		 
// deleting all the qualifications from this framework
$query = <<<HEREDOC
delete from provider_qualifications
	where org_id = $org_id;
HEREDOC;
		DAO::execute($link, $query);
	}
}
?>