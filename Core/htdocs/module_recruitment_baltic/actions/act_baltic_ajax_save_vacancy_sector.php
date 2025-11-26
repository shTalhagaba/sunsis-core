<?php
class baltic_ajax_save_vacancy_sector implements IAction
{
	public function execute(PDO $link)
	{

		$sector_desc = isset($_REQUEST['sector_desc'])?$_REQUEST['sector_desc']:'';

		$query = <<<HEREDOC
insert into lookup_vacancy_type (id, description) values(NULL,'$sector_desc');
HEREDOC;
		DAO::execute($link, $query);
	}
}
?>