<?php
class ajax_diceware implements IAction
{
	public function execute(PDO $link)
	{
		$illegal_words = isset($_REQUEST['illegal_words']) ? $_REQUEST['illegal_words'] : array();

		do
		{
			//$pwd = PasswordUtilities::generateDicewarePassword($link, 2, 8, 9999);
			$pwd = PasswordUtilities::generateDatePassword();
			$pwd = PasswordUtilities::randomCapitalisation($pwd, 1);
			$pwd = PasswordUtilities::replaceSpacesWithNumbers($pwd);
			$validationResults = PasswordUtilities::checkPasswordStrength($pwd, $illegal_words);
		} while($validationResults['code'] == 0);

		header('Content-Type: text/plain; charset=ISO-8859-1');
		echo $pwd;
	}
}
?>