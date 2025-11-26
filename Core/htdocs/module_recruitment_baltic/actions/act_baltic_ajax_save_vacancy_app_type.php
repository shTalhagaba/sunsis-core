<?php
class baltic_ajax_save_vacancy_app_type implements IAction
{
	public function execute(PDO $link)
	{

		$reason = isset($_REQUEST['reason'])?$_REQUEST['reason']:'';

		$query = <<<HEREDOC
insert into lookup_vacancy_app_type (id, description) values(NULL,'$reason');
HEREDOC;
		DAO::execute($link, $query);
	}
}
?>