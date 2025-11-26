<?php
class view_support_tickets implements IAction
{
	public function execute(PDO $link)
	{
		if( SystemConfig::getEntityValue($link, "module_support_v2") != 1 )
        {
            throw new Exception("Version 2 of support system is not enabled for you.");
        }

		require_once('./lib/SupportModule/SupportModuleHelper.php');

		$_SESSION['bc']->index = 0;
		$_SESSION['bc']->add($link, "do.php?_action=view_support_tickets", "View Support Tickets");

		$filters = $_REQUEST;
		unset($filters['_action']);

		$statusList = [
			['1', 'Assigned'],
			['3', 'Awaiting Client'],
			['4', 'Awaiting Confirmation'],
			['5', 'Bespoke Development'],
			['6', 'Closed'],
			['7', 'Deployment'],
			['8', 'Duplicate'],
			['9', 'New'],
			['10', 'On Hold'],
			['11', 'Refused Development'],
			['12', 'Reopened'],
			['2', 'Requires Additional Requirements'],
			['13', 'Validation'],
		];
		
		$typesList = [
			['1', 'Documentation'],
			['2', 'Enhancement / Development'],
			['3', 'General Enquiry'],
			['4', 'How to?'],
			['13', 'ILR Related'],
			['5', 'Incident'],
			['6', 'Inputting / Data Collection'],
			['7', 'Login Issue'],
			['8', 'Non Technical'],
			['10', 'Reports'],
			['9', 'System Issue / Bug'],
			['11', 'Training'],
			['12', 'UI (User Interface)'],
		];
		
		$prioritiesList = [
			['1', 'Critical'],
			['2', 'High'],
			['3', 'Medium'],
			['4', 'Low'],
		];

		$supportHelper = new SupportModuleHelper();
		
		require_once('tpl_view_support_tickets1.php');
	}    
}
?>