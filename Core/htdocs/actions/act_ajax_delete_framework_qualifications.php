<?php
class ajax_delete_framework_qualifications implements IAction
{
	public function execute(PDO $link)
	{
		$fid = isset($_REQUEST['framework_id'])?$_REQUEST['framework_id']:'';
	
		 
// deleting all the qualifications from this framework
$query = <<<HEREDOC
delete from framework_qualifications
	where framework_id = $fid;
HEREDOC;
		DAO::execute($link, $query);
		
// deleting all the qualifications from this framework
$query = <<<HEREDOC
delete from milestones
	where framework_id = $fid;
HEREDOC;
		DAO::execute($link, $query);
		
		
	}
}
?>