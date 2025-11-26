<?php
class ajax_delete_additional_qualifications implements IAction
{
	public function execute(PDO $link)
	{
		$tr_id = isset($_REQUEST['tr_id'])?$_REQUEST['tr_id']:'';
	 
		
// deleting all the qualifications from this framework
$query = <<<HEREDOC
delete from student_qualifications
	where tr_id = $tr_id and framework_id=0;
HEREDOC;
		DAO::execute($link, $query);
	}
}
?>