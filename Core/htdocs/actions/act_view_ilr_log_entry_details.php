<?php
class view_ilr_log_entry_details implements IAction
{
	public function execute(PDO $link)
	{
		$entry_id = isset($_REQUEST['entry_id'])? $_REQUEST['entry_id']:'';

		$ilr_audit = DAO::getObject($link, "SELECT * FROM ilr_audit WHERE id = '{$entry_id}'");

		if(!isset($ilr_audit->id))
			throw new Exception('Invalid Audit ID: ' . $entry_id);

		$_SESSION['bc']->add($link, "do.php?_action=view_ilr_log_entry_details&entry_id=" . $entry_id, "View ILR Log Entry Details");

        $resultText = Ilr::getAuditDetails($link, $entry_id);

		include('tpl_view_ilr_log_entry_details.php');
	}

	private function generateText(PDO $link)
	{
		$html = "";
		$html .= "<div><h4>None of the following fields have been changed in this entry:</h4>";
		$fields_set = DAO::getSingleColumn($link, "SELECT field_title FROM lookup_ilr_audit_fields WHERE active = 1 AND aim_specific = 0 ");
		$html .= '<ul>';
		foreach($fields_set AS $field)
		{
			$html .= "<li>" . $field . "</li>";
		}
		$html .= "</ul>";
		$html .= "Aim Level";
		$fields_set = DAO::getSingleColumn($link, "SELECT field_title FROM lookup_ilr_audit_fields WHERE active = 1 AND aim_specific = 1 ");
		$html .= '<ul>';
		foreach($fields_set AS $field)
		{
			$html .= "<li>" . $field . "</li>";
		}
		$html .= "</ul>";
		$html .= "</div>";
		return $html;
	}
}