<?php
class create_support_ticket implements IAction
{
	public function execute(PDO $link)
	{
		if( SystemConfig::getEntityValue($link, "module_support_v2") != 1 )
        {
            throw new Exception("Version 2 of support system is not enabled for you.");
        }
		
		require_once('./lib/SupportModule/SupportModuleHelper.php');

        $_SESSION['bc']->index = 0;
		$_SESSION['bc']->add($link, "do.php?_action=create_support_ticket", "Create Support Ticket");

        $typesList = [
			// ['1', 'Documentation'],
			['2', 'Enhancement / Development'],
			['3', 'General Enquiry'],
			// ['4', 'How to?'],
			['13', 'ILR Enquiry'],
			// ['5', 'Incident'],
			['6', 'Inputting / Data Collection'],
			// ['7', 'Login Issue'],
			// ['8', 'Non Technical'],
			['10', 'Reports Enquiry'],
			['9', 'System Issue / Bug'],
			['11', 'Training'],
			// ['12', 'UI (User Interface)'],
			['14', 'Other'],
		];
		
		$prioritiesList = [
			['4', 'Low'],
			['3', 'Medium'],
			['2', 'High'],
			['1', 'Critical'],
		];

		$supportHelper = new SupportModuleHelper();

        require_once('tpl_create_support_ticket.php');
    }
}