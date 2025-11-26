<?php
class view_ilr_report implements IAction
{
	public function execute(PDO $link)
	{
		
		$contract_id = isset($_REQUEST['contract']) ? $_REQUEST['contract']:'';
		$submission = isset($_REQUEST['submission']) ? $_REQUEST['submission']:'';
        	$assessor = isset($_REQUEST['assessor']) ? $_REQUEST['assessor']:'';
        	$employer = isset($_REQUEST['employer']) ? $_REQUEST['employer']:'';
        	$course = isset($_REQUEST['course']) ? $_REQUEST['course']:'';
        	$provider = isset($_REQUEST['provider']) ? $_REQUEST['provider']:'';
        	$active = isset($_REQUEST['active']) ? $_REQUEST['active']:'';
        	$valid = isset($_REQUEST['valid']) ? $_REQUEST['valid']:'';
		$lsf = isset($_REQUEST['lsf']) ? $_REQUEST['lsf']:'';
		$zprog = isset($_REQUEST['zprog']) ? $_REQUEST['zprog']:'';

		$_SESSION['bc']->add($link, "do.php?_action=view_ilr_report&contract_id=" . $contract_id . "&submission=" . $submission . "&assessor=" . $assessor . "&employer=" . $employer . "&course=" . $course .  "&provider=" . $provider, "View ILR Report");
		
		if($contract_id == '' || $submission == '')
			throw new Exception("Please select at least one contract and submission period");

		$vo = Contract::loadFromDatabase($link, $contract_id);

		if($vo->contract_year<2012)
		{
			$view = ViewIlrReport::getInstance($contract_id, $submission);
			$view->refresh($link, $_REQUEST);
			require_once('tpl_view_ilr_report.php');
		}
		else
		{
			$view = ViewIlrReportXML::getInstance($contract_id, $submission, $assessor, $employer, $course, $provider, $active, $valid, $lsf, $zprog);
			$view->refresh($link, $_REQUEST);
			require_once('tpl_view_ilr_report_xml.php');
		}
	}
}
?>