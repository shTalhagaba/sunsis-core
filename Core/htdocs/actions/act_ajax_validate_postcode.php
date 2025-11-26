<?php
class ajax_validate_postcode implements IAction
{
	public function execute(PDO $link)
	{
		require_once('lib/Postcodes-IO-PHP.php');

		$incoming_postcode = isset($_REQUEST['postcode'])?trim($_REQUEST['postcode']):'';

		if($incoming_postcode == '')
		{
			echo "no postcode given";
			exit;
		}

		$postcode = new Postcode();

		header('Content-Type: text/plain');
		if($postcode->validate($incoming_postcode))
			echo "valid";
		else
			echo "invalid";
	}
}
?>