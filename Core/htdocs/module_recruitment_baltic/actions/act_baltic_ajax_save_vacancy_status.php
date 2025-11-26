<?php
class baltic_ajax_save_vacancy_status implements IAction
{
	public function execute(PDO $link)
	{

		$vac_status_desc = isset($_REQUEST['vac_status_desc'])?$_REQUEST['vac_status_desc']:'';

		$query = <<<HEREDOC
insert into lookup_vacancy_status (id, description) values(NULL,'$vac_status_desc');
HEREDOC;

		DAO::execute($link, $query);
	}
}
?>