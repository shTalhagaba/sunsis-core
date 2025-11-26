<?php
class baltic_ajax_save_candidate_status_code implements IAction
{
	public function execute(PDO $link)
	{

		$status_desc = isset($_REQUEST['status_desc'])?$_REQUEST['status_desc']:'';

		$query = <<<HEREDOC
insert into lookup_candidate_status (id, description) values(NULL,'$status_desc');
HEREDOC;

		DAO::execute($link, $query);
	}
}
?>