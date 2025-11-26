<?php
class save_evidence_template implements IAction
{
	public function execute(PDO $link)
	{
		
		$org = new Evidence();
		$org->populate($_POST);
		$org->save($link);
		
		$qualification_id = $_POST['qualification_id'];
		$framework_id = $_POST['framework_id'];
		$tr_id = $_POST['tr_id'];
		$internaltitle = $_POST['internaltitle'];
		$target = $_POST['target'];
		$achieved = $_POST['achieved'];
		$group_id = $_POST['group_id'];
		
				
		http_redirect('do.php?_action=view_evidence&qualification_id='. $qualification_id . '&framework_id=' . $framework_id . '&tr_id=' . $tr_id . '&internaltitle=' . $internaltitle . '&achieved=' . $achieved . '&target=' . $target . '&group_id=' . $group_id);
	}
}
?>