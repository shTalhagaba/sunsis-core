<?php
class view_ilr_funding_changes1415 implements IAction
{
	public function execute(PDO $link)
	{
		header('Content-type: application/pdf');
		readfile('./docs/ILRFundingchanges2015-16.pdf');
		exit;
	}

}
?>