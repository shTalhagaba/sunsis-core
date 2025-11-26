<?php
class save_client implements IAction
{
	public function execute(PDO $link)
	{

		$org = new Client();
		$org->populate($_POST);
		
		$org->save($link);
		
		http_redirect('do.php?_action=read_client&id=12');
	}
}
?>