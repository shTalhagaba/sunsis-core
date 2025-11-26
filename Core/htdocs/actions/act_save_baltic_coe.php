<?php
class save_baltic_coe implements IAction
{
	public function execute(PDO $link)
	{

        $tr_id = isset($_POST['tr_id']) ? $_POST['tr_id'] : '';
        if($tr_id == '')
        {
            throw new Exception("Invalid data.");            
        }
        $tr = TrainingRecord::loadFromDatabase($link, $tr_id);
        if(is_null($tr))
        {
            throw new Exception("Invalid data..");
        }

        $newRecord = DAO::getSingleValue($link, "SELECT COUNT(*) FROM tr_coe WHERE tr_id = '{$tr->id}'");

        $tr_coe = new stdClass();
        $tr_coe->tr_id = $tr->id;
        $tr_coe->coe_new_employer_name = isset($_POST['coe_new_employer_name']) ? $_POST['coe_new_employer_name'] : null;
        $tr_coe->coe_last_day = isset($_POST['coe_last_day']) ? $_POST['coe_last_day'] : null;
        $tr_coe->coe_start_date = isset($_POST['coe_start_date']) ? $_POST['coe_start_date'] : null;
        $tr_coe->coe_das_month = isset($_POST['coe_das_month']) ? $_POST['coe_das_month'] : null;
        $tr_coe->coe_rfs = isset($_POST['coe_rfs']) ? $_POST['coe_rfs'] : 0;
        $tr_coe->coe_fa = isset($_POST['coe_fa']) ? $_POST['coe_fa'] : 0;
        $tr_coe->coe_hs = isset($_POST['coe_hs']) ? $_POST['coe_hs'] : 0;
        $tr_coe->coe_ilp = isset($_POST['coe_ilp']) ? $_POST['coe_ilp'] : 0;
        $tr_coe->coe_tp_sent = isset($_POST['coe_tp_sent']) ? $_POST['coe_tp_sent'] : 0;
        $tr_coe->coe_tp_sent_date = isset($_POST['coe_tp_sent_date']) ? $_POST['coe_tp_sent_date'] : null;
        $tr_coe->coe_tp_signed = isset($_POST['coe_tp_signed']) ? $_POST['coe_tp_signed'] : 0;
        $tr_coe->coe_tp_signed_date = isset($_POST['coe_tp_signed_date']) ? $_POST['coe_tp_signed_date'] : null;
        //$tr_coe->coe_notes = isset($_POST['coe_notes']) ? $_POST['coe_notes'] : null;
        $tr_coe->coe_das_stopped = isset($_POST['coe_das_stopped']) ? $_POST['coe_das_stopped'] : null;
        $tr_coe->coe_added_new_das = isset($_POST['coe_added_new_das']) ? $_POST['coe_added_new_das'] : null;
        $tr_coe->coe_new_das = isset($_POST['coe_new_das']) ? $_POST['coe_new_das'] : null;
        $tr_coe->coe_nda = isset($_POST['coe_nda']) ? $_POST['coe_nda'] : null;
        $tr_coe->coe_process_complete = isset($_POST['coe_process_complete']) ? $_POST['coe_process_complete'] : 0;
        $tr_coe->coe_owner = isset($_POST['coe_owner']) ? $_POST['coe_owner'] : null;
        $tr_coe->coe_status = isset($_POST['coe_status']) ? $_POST['coe_status'] : null;
        $tr_coe->coe_date_raised = isset($_POST['coe_date_raised']) ? $_POST['coe_date_raised'] : null;
        $tr_coe->coe_date_closed = isset($_POST['coe_date_closed']) ? $_POST['coe_date_closed'] : null;

        $tr_coe->coe_notes = $this->saveNotes($link, $tr, 'coe_notes', $_POST['coe_notes']);

        if($newRecord == 0)
        {
            $tr_coe->current_employer = DAO::getSingleValue($link, "SELECT legal_name FROM organisations WHERE id = '{$tr->employer_id}'");
        }

        DAO::saveObjectToTable($link, "tr_coe", $tr_coe);
        
        http_redirect('do.php?_action=read_training_record&tabCoe=1&id=' . $tr_id);
	}

	private function saveNotes(PDO $link, TrainingRecord $tr, $field, $notes)
	{
		if(trim($notes) == '')
			return;

		$notes = str_replace("�", "&pound;", $notes);
		$notes = Text::utf8_to_latin1($notes);

		$coe_note = DAO::getSingleValue($link, "SELECT coe_notes FROM tr_coe WHERE tr_id = '{$tr->id}'");
		$notes = htmlspecialchars((string)$notes, 16);
		$xml = '';
		$xml = $coe_note;
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