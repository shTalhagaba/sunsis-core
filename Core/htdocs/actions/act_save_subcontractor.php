<?php
class save_subcontractor implements IAction
{
	public function execute(PDO $link)
	{

		$org = new SubContractor();
		$org->populate($_POST);
		
		$org->save($link);
		
		http_redirect('do.php?_action=read_subcontractor&id=' . $_POST['id']);
	}
}
?>