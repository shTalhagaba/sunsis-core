<?php
class save_review_comment implements IAction
{
	public function execute(PDO $link)
	{
		
		$org = new ReviewComment();
		$org->populate($_POST);
		$org->save($link);
		
		http_redirect('do.php?_action=assessor_review&tr_id='.$_POST['tr_id']);
	}
}
?>