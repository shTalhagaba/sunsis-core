<?php
class ajax_delete_columns implements IAction
{
	public function execute(PDO $link)
	{
		$view = isset($_REQUEST['view'])?$_REQUEST['view']:'';
		if(!$view){
			return;
		}
		
		$query = "DELETE FROM view_columns WHERE view='".addslashes((string)$view)."' AND user='".addslashes((string)$_SESSION['user']->username)."'";
		DAO::execute($link, $query);
	}
}
?>