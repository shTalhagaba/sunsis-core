<?php
class save_reinstatement implements IAction
{
	public function execute(PDO $link)
	{
        $tr_id = isset($_REQUEST['tr_id']) ? $_REQUEST['tr_id'] : '';

        $tr = TrainingRecord::loadFromDatabase($link, $tr_id);

        $tr->last_day_of_active_learning = isset($_POST['last_day_of_active_learning']) ? $_POST['last_day_of_active_learning'] : null;
        $tr->first_day_of_active_learning = isset($_POST['first_day_of_active_learning']) ? $_POST['first_day_of_active_learning'] : null;
        $tr->new_planned_end_date = isset($_POST['new_planned_end_date']) ? $_POST['new_planned_end_date'] : null;
        $tr->training_plan_sent = isset($_POST['training_plan_sent']) ? $_POST['training_plan_sent'] : 0;
        $tr->training_plan_sent_date = isset($_POST['training_plan_sent_date']) ? $_POST['training_plan_sent_date'] : null;
        $tr->training_plan_signed = isset($_POST['training_plan_signed']) ? $_POST['training_plan_signed'] : 0;
        $tr->training_plan_signed_date = isset($_POST['training_plan_signed_date']) ? $_POST['training_plan_signed_date'] : null;
        $tr->reinstatement_nda = isset($_POST['reinstatement_nda']) ? $_POST['reinstatement_nda'] : null;
        $tr->reinstatement_owner = isset($_POST['reinstatement_owner']) ? $_POST['reinstatement_owner'] : null;
        $tr->reinstatement_type = isset($_POST['reinstatement_type']) ? $_POST['reinstatement_type'] : null;
        $tr->reinstatement_date_raised = isset($_POST['reinstatement_date_raised']) ? $_POST['reinstatement_date_raised'] : null;
        $tr->reinstatement_date_closed = isset($_POST['reinstatement_date_closed']) ? $_POST['reinstatement_date_closed'] : null;

        $tr->reinstatement_notes = $this->saveNotes($link, $tr, 'reinstatement_notes', $_POST['reinstatement_notes']);

        $tr->save($link);

        http_redirect('do.php?_action=read_training_record&tabRein=1&id=' . $tr_id);

	}

    private function saveNotes(PDO $link, TrainingRecord $tr, $field, $notes)
	{
		if(trim($notes) == '')
			return;

		$notes = str_replace("£", "&pound;", $notes);
		$notes = Text::utf8_to_latin1($notes);

		$notes = htmlspecialchars((string)$notes, 16);
		$xml = '';
		$xml = $tr->$field;
		if(is_null($xml) || $xml == '')
			$xml = '<Notes></Notes>';
		$xml = XML::loadSimpleXML($xml);
		$new_note = $xml->addChild('Note');
		$new_note->DateTime = date('Y-m-d H:i:s');
		$new_note->CreatedBy = $_SESSION['user']->id;
		$new_note->Comment = $notes;
		$dom = new DOMDocument;
		$dom->preserveWhiteSpace = FALSE;
		@$dom->loadXML($xml->saveXML());
		$dom->formatOutput = TRUE;
		$modified_xml = $dom->saveXml();
		$modified_xml = str_replace('<?xml version="1.0"?>', '', $modified_xml);

		return $modified_xml;
	}
}
?>