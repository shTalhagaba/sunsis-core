<?php
define('METRES_IN_A_MILE', 1609.344);

class report_candidates implements IAction
{
	public function execute(PDO $link)
	{
		$view = ViewCandidates::getInstance();
		require_once('tpl_report_candidates.php');
	}	
}
?>