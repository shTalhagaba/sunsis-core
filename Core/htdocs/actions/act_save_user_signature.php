<?php
class save_user_signature implements IAction
{
	public function execute(PDO $link)
	{
		$id = isset($_REQUEST['id'])?$_REQUEST['id']:'';
		$_action_from = isset($_REQUEST['from_page'])?$_REQUEST['from_page']:'';
		$signature = isset($_REQUEST['user_signature'])?$_REQUEST['user_signature']:'';

		if($signature == '')
			throw new Exception('Please provide your signature');

		DAO::execute($link, "UPDATE users SET users.signature = '{$signature}' WHERE users.id = '{$id}'");

		if(IS_AJAX)
		{
			header("Content-Type: text/plain");
			echo $id;
		}
		else
		{
			http_redirect('do.php?_action=' . $_action_from . '&id=' . $id);
		}
	}
}