<?php
class view_ilr_funding_changes1314 implements IAction
{
	public function execute(PDO $link)
	{
		header('Content-type: application/pdf');
		readfile('./docs/ILRFundingChanges1314Document.pdf');
		exit;
	}

}
?>