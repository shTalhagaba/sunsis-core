<?php
class ajax_save_account_manager implements IAction
{
	public function execute(PDO $link)
	{

		$account_manager_desc = isset($_REQUEST['account_manager_desc'])?$_REQUEST['account_manager_desc']:'';

		$query = <<<HEREDOC
insert into lookup_account_managers (id, description) values(NULL,'$account_manager_desc');
HEREDOC;

		DAO::execute($link, $query);
	}
}
?>