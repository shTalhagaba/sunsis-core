<?php
class ajax_save_brand implements IAction
{
	public function execute(PDO $link)
	{

		$brand = isset($_REQUEST['brand'])?$_REQUEST['brand']:'';
		
// deleting all the qualifications from this framework
$query = <<<HEREDOC
insert into brands (id, title) values(NULL,'$brand');
HEREDOC;
		DAO::execute($link, $query);
	}
}
?>