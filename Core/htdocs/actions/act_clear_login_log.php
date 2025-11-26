<?php
class clear_login_log implements IAction
{
	public function execute(PDO $link)
	{
		if(!SOURCE_LOCAL && !SOURCE_BLYTHE_VALLEY){
			throw new UnauthorizedException();
		}
		
		// Validate data entry
		$cutoff_date = isset($_REQUEST['date']) ? $_REQUEST['date'] : '';
	 
		if($cutoff_date == '')
		{
			$sql = "TRUNCATE logins;";
		}
		else
		{
			$cutoff_date = Date::toMySQL($cutoff_date);
			$sql = "DELETE FROM logins WHERE `date` < '$cutoff_date';";
		}

		DAO::execute($link, $sql);

		// Presentation
		if(IS_AJAX)
		{
			return 1;
		}
		else
		{
			http_redirect('do.php?_action=view_logins');
		}
	}

}
?>