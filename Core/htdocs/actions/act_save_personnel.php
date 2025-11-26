<?php
class save_personnel implements IAction
{
	public function execute(PDO $link)
	{
		// Populate Value Object from user's <form> submission
		
		$vo = new User();
		$vo->populate($_POST);
	
		// Create DAO
		$dao = new User($link);
	
		if($vo->id == 0)
		{
			$vo->id = $vo->save($link);
		}
		else
		{
			$dao->update($vo);
		}
		
		http_redirect('do.php?_action=read_provider&id=' . $vo->organisations_id);
	}
}
?>