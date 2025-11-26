<?php
class mobile_monthly implements IUnauthenticatedAction
{
	public function execute(PDO $link)
	{
		header('Content-Type: text/xml;');
		echo "Test";
	}
}
?>
