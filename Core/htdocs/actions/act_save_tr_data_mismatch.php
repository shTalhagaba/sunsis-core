<?php
class save_tr_data_mismatch implements IAction
{
	public function execute(PDO $link)
	{
        $tr_id = isset($_REQUEST['tr_id']) ? $_REQUEST['tr_id'] : '';

        $tr = TrainingRecord::loadFromDatabase($link, $tr_id);

        $tr->dm_reason = isset($_POST['dm_reason']) ? $_POST['dm_reason'] : null;
        $tr->dm_assigned_to = isset($_POST['dm_assigned_to']) ? $_POST['dm_assigned_to'] : null;
        $tr->dm_date_added = isset($_POST['dm_date_added']) ? $_POST['dm_date_added'] : null;
        $tr->dm_date_removed = isset($_POST['dm_date_removed']) ? $_POST['dm_date_removed'] : null;
	$tr->dm_stage = isset($_POST['dm_stage']) ? $_POST['dm_stage'] : null;
        //$tr->dm_additional_info_comments = isset($_POST['dm_additional_info_comments']) ? $_POST['dm_additional_info_comments'] : null;
        //$tr->dm_contact_comment = isset($_POST['dm_contact_comment']) ? $_POST['dm_contact_comment'] : null;

	$tr->dm_additional_info_comments = $this->saveNotes($link, $tr, 'dm_additional_info_comments', $_POST['dm_additional_info_comments']);
        $tr->dm_contact_comment = $this->saveNotes($link, $tr, 'dm_contact_comment', $_POST['dm_contact_comment']);

        $tr->save($link);

        http_redirect('do.php?_action=read_training_record&tabHci=1&id=' . $tr_id);

	}

	private function saveNotes(PDO $link, TrainingRecord $tr, $field, $notes)
	{
		if(trim($notes) == '')
			return;

		$notes = str_replace("�", "&pound;", $notes);
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