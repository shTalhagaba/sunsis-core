<?php
class left_hand_menu implements IAction
{
	public function execute(PDO $link)
	{
		$report_categories = array();

		$report_categories[] = 'caseload_and_attendance';
		$report_categories[] = 'employment_and_IWS';
		$report_categories[] = 'claims_report';
		$report_categories[] = 'ilr_quality_and_audit';

		require_once('tpl_left_hand_menu.php');
	}
}
?>