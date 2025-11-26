<?php
class clear_unsuccessful_login_log implements IAction
{
	public function execute(PDO $link)
	{
		// Validate data entry
		$cutoff_date = isset($_REQUEST['date']) ? $_REQUEST['date'] : '';

		if($cutoff_date == '')
		{
			$sql = "TRUNCATE logins_unsuccessful;";
		}
		else
		{
			$cutoff_date = Date::toMySQL($cutoff_date);
			$sql = "DELETE FROM logins_unsuccessful WHERE `date` < '$cutoff_date';";
		}
		DAO::execute($link, $sql);

		// Presentation
		if(IS_AJAX)
		{
			return 1;
		}
		else
		{
			http_redirect('do.php?_action=view_unsuccessful_logins');
		}
	}
	
}
?>