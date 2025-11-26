<?php
class import_evidences implements IAction
{
	public function execute(PDO $link)
	{
		$qualification_id = isset($_REQUEST['qualification_id'])?$_REQUEST['qualification_id']:'';
		$framework_id = isset($_REQUEST['framework_id'])?$_REQUEST['framework_id']:'';
		$tr_id = isset($_REQUEST['tr_id'])?$_REQUEST['tr_id']:'';
		$internaltitle = isset($_REQUEST['internaltitle'])?$_REQUEST['internaltitle']:'';
		$target= isset($_REQUEST['target'])?$_REQUEST['target']:'';
		$achieved= isset($_REQUEST['achieved'])?$_REQUEST['achieved']:'';
		$group_id= isset($_REQUEST['group_id'])?$_REQUEST['group_id']:'';
		
		
// importing evidences
		$sql2 = <<<HEREDOC
insert into 
	evidence_template
(select 0, reference, type, content, CURDATE(), '', $tr_id, portfolio, 0, category, title, qualification_id, internaltitle, '' from evidence where qualification_id = '$qualification_id' and internaltitle = '$internaltitle') 
HEREDOC;
		DAO::execute($link, $sql2);
		
		http_redirect('do.php?_action=view_evidence&qualification_id=' . $qualification_id . '&internaltitle=' . $internaltitle . '&framework_id=' . $framework_id . '&tr_id=' . $tr_id . '&target=' . $target . '&achieved=' . $achieved . '&group_id=' . $group_id);
	}
}
?>