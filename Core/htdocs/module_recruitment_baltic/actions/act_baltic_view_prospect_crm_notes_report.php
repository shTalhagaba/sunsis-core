<?php
class baltic_view_prospect_crm_notes_report implements IAction
{
	public function execute(PDO $link)
	{
		$_SESSION['bc']->index = 0;
		$_SESSION['bc']->add($link, "do.php?_action=baltic_view_prospect_crm_notes", "View Prospects CRM Notes");

		$view = ViewProspectCRMNotesReport::getInstance($link);
		$view->refresh($link, $_REQUEST);

		require_once('tpl_baltic_view_prospect_crm_notes_report.php');
	}
}