<?php
class save_learnergroup implements IAction
{
	public function execute(PDO $link)
	{
		
		$org = new LearnerGroup();
		$org->populate($_POST);
		$org->save($link);
		
		http_redirect('do.php?_action=view_learnergroups');
	}
}
?>