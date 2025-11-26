<?php
class save_announcement implements IAction
{
	
	public function execute(PDO $link)
	{
		$id = isset($_REQUEST['id']) ? $_REQUEST['id'] : '';
		$org_id = isset($_REQUEST['org_id']) ? $_REQUEST['org_id'] : array();
		$all_partnerships = isset($_REQUEST['all_partnerships']) ? $_REQUEST['all_partnerships'] : 0;
		//$all_schools = isset($_REQUEST['all_schools']) ? $_REQUEST['all_schools'] : 0;
		//$all_providers = isset($_REQUEST['all_providers']) ? $_REQUEST['all_providers'] : 0;
		
		$vo = new Announcement();
		$vo->populate($_REQUEST);
		//$vo->addAclEntry($org_id);
		
	//	$vo->all_partnerships = $all_partnerships;
	//	$vo->all_schools = $all_schools;
	//	$vo->all_providers = $all_providers;
		
		
		
		if(!$vo->id){
			
			$vo->users_id = $_SESSION['user']->username;
			$vo->organisations_id = $_SESSION['user']->employer_id;
			
		}
		
		try
		{
			
			DAO::transaction_start($link);
			$vo->save($link);
			DAO::transaction_commit($link);
		}
		catch(Exception $e)
		{
			DAO::transaction_rollback($link, $e);
			throw new WrappedException($e);
		}
		
		$key = $_SERVER['SERVER_NAME'].' announcement '.$vo->id;
		//xcache_unset($key);
		
		if(IS_AJAX)
		{
			header("Content-Type: text/plain");
			echo $vo->id;
		}
		else
		{
			http_redirect("do.php?_action=read_announcement&id=".$vo->id);
		}
	}
}

?>