<?php
class logout implements IAction
{
	public function execute(PDO $link)
	{
		$_SESSION = array();

		http_redirect("/do.php?_action=login");
	}
}
?>