<?php
class dettach_framework implements IAction
{
	public function execute(PDO $link)
	{
		$fid = isset($_REQUEST['framework_id'])?$_REQUEST['framework_id']:'';
		$tr_id = isset($_REQUEST['tr_id'])?$_REQUEST['tr_id']:'';

		
// Deleting framework
$query = <<<HEREDOC
delete from
	student_frameworks
where tr_id='$tr_id';
HEREDOC;
		DAO::execute($link, $query);

// Deleting framework
$query = <<<HEREDOC
delete from
	courses_tr
where tr_id='$tr_id';
HEREDOC;
		DAO::execute($link, $query);
		
		
// deleting qualification from framework		
$query = <<<HEREDOC
delete from
	student_qualifications
where tr_id='$tr_id' and framework_id!=0;
HEREDOC;
		DAO::execute($link, $query);
		
// deleting milestones
$query = <<<HEREDOC
delete from
	student_milestones
where tr_id='$tr_id';
HEREDOC;
		DAO::execute($link, $query);

// deleting milestones
$query = <<<HEREDOC
delete from
	group_members
where tr_id='$tr_id';
HEREDOC;
		DAO::execute($link, $query);
		
		http_redirect('do.php?_action=read_training_record&id=' . $tr_id);		
		
	}
}
?>