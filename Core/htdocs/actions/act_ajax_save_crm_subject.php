<?php
class ajax_save_crm_subject implements IAction
{
	public function execute(PDO $link)
	{

		$reason = isset($_REQUEST['reason'])?$_REQUEST['reason']:'';
		
// deleting all the qualifications from this framework
$query = <<<HEREDOC
insert into lookup_crm_subject (id, description) values(NULL,'$reason');
HEREDOC;
		DAO::execute($link, $query);
	}
}
?>