<?php
define('METRES_IN_A_MILE', 1609.344);

class baltic_report_candidates implements IAction
{
	public function execute(PDO $link)
	{
		$view = ViewCandidates::getInstance($link);
		require_once('tpl_baltic_report_candidates.php');
	}	
}
?>