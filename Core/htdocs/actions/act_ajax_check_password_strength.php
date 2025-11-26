<?php
class ajax_check_password_strength implements IAction
{
	public function execute(PDO $link)
	{
		$pwd = array_key_exists("pwd", $_REQUEST) ? $_REQUEST['pwd']:"";
		$extra_words = array_key_exists("extra_words", $_REQUEST) ? $_REQUEST['extra_words']:"";

		if($extra_words)
		{
			$extra_words = explode(' ', preg_replace("/[^A-Za-z0-9']/", ' ', $extra_words));
		}
		else
		{
			$extra_words = array();
		}

		header('Content-Type: application/json');
		echo json_encode(PasswordUtilities::checkPasswordStrength($pwd, $extra_words));
	}
}
?>