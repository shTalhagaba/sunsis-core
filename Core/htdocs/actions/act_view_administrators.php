<?php
class view_administrators implements IAction
{
	public function execute(PDO $link)
	{
		
		$emp = $_SESSION['user']->employer_id;

		$_SESSION['bc']->index=0;
		$_SESSION['bc']->add($link, "do.php?_action=view_administrators", "View Administrators");
		
		$que = "select people_type from lookup_people_type where id='1'";
		$people= trim(DAO::getSingleValue($link, $que));
				
		$view = ViewAdministrator::getInstance($link, $emp);
		$view->refresh($link, $_REQUEST);
		
		require_once('tpl_view_administrators.php');
	}
}
?>