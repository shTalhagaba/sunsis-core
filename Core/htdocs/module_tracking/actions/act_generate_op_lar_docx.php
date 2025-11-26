<?php
class generate_op_lar_docx implements IAction
{
	public function execute(PDO $link)
	{
		$tr_id = isset($_REQUEST['tr_id']) ? $_REQUEST['tr_id'] : '';
		$timestamp = isset($_REQUEST['timestamp']) ? $_REQUEST['timestamp'] : '';
		$tracker_id = isset($_REQUEST['tracker_id']) ? $_REQUEST['tracker_id'] : '';

		$tr = TrainingRecord::loadFromDatabase($link, $tr_id);
		$tracker = OperationsTracker::loadFromDatabase($link, $tracker_id);
		$employer = Employer::loadFromDatabase($link, $tr->employer_id);

		$start_date = Date::toShort($tr->start_date);
		$added_to_lar_date = DAO::getSingleValue($link, "SELECT extractvalue(tr_operations.`lar_details`, '/Notes/Note[1]/Date') FROM tr_operations WHERE tr_id = '{$tr_id}'");
		$assessor_name = DAO::getSingleValue($link, "SELECT CONCAT(firstnames, ' ', surname) FROM users WHERE id = '{$tr->assessor}'");
		$coordinator = DAO::getSingleValue($link, "SELECT CONCAT(firstnames, ' ', surname) FROM users WHERE id = '{$tr->coordinator}'");
		$course_id = DAO::getSingleValue($link, "SELECT course_id FROM courses_tr WHERE tr_id = '{$tr->id}'");

		$sql = <<<SQL
SELECT DISTINCT
	induction.`brm`
FROM
	induction
	INNER JOIN inductees ON induction.`inductee_id` = inductees.id
	INNER JOIN induction_programme ON induction_programme.`inductee_id` = inductees.id
WHERE
	inductees.`sunesis_username` = '$tr->username' AND induction_programme.`programme_id` = '$course_id'
SQL;
		$bdm = DAO::getSingleValue($link, $sql);

		$legal_name = str_replace('&', '&amp;amp;', $employer->legal_name);
		$tracker_title = str_replace('&', '&amp;amp;', $tracker->title);
		$html = <<<HTML
<table style="width: 100%; border: 1px #000000 solid; border-spacing: 10px;" cellpadding="6" cellspacing="6">
	<tr><th><strong>Learner Name</strong></th><td>$tr->firstnames $tr->surname</td></tr>
	<tr><th><strong>Start Date</strong></th><td>$start_date</td></tr>
	<tr><th><strong>Programme</strong></th><td>$tracker_title</td></tr>
	<tr><th><strong>Employer</strong></th><td>$legal_name</td></tr>
	<tr><th><strong>Added to LAR</strong></th><td>$added_to_lar_date</td></tr>
	<tr><th><strong>Assessor Name</strong></th><td>$assessor_name</td></tr>
	<tr><th><strong>BDM Name</strong></th><td>$bdm</td></tr>
	<tr><th><strong>Coordinator Name</strong></th><td>$coordinator</td></tr>
</table>
<p></p>
HTML;

		$notes = DAO::getSingleValue($link, "SELECT tr_operations.lar_details FROM tr_operations WHERE tr_id = '{$tr_id}'");
		$notes = XML::loadSimpleXML($notes);

		$lar_types = InductionHelper::getListLAR();
		$rags = InductionHelper::getListLARRAGRating();

		$html1 = '<table style="width: 100%; border: 1px #000000 solid; border-spacing: 10px;" cellpadding="6" >';
		$html1 .= '<tr><td><strong>Date and Time Telephone No</strong></td><td><strong>Detail</strong></td><td><strong>Status NDA</strong></td></tr>';
		foreach($notes->Note AS $note)
		{
			$given_timestamp = DateTime::createFromFormat("Y-m-d H:i:s", $timestamp);
			$given_timestamp = $given_timestamp->getTimestamp();

			$note_timestamp = DateTime::createFromFormat("Y-m-d H:i:s", $note->DateTime->__toString());
			$note_timestamp = $note_timestamp->getTimestamp();

			if($note_timestamp < $given_timestamp)//$note->DateTime->__toString() != $timestamp)
				continue;

			$detail = str_replace('&', ' and ', $note->Note->__toString());
			$html1 .= '<tr>';
			$html1 .= '<td>' . Date::to($note->DateTime->__toString(), Date::DATETIME) . '</td>';
			$html1 .= '<td>' . nl2br($detail) . '</td>';
			$html1 .= isset($rags[$note->RAG->__toString()]) ? '<td>' . $rags[$note->RAG->__toString()] . ' ' . $note->NextActionDate->__toString() . '</td>' : '<td>' . $note->NextActionDate->__toString() . '</td>';

			$html1 .= '</tr>';

			if($note->Type->__toString() == "N")
				break;

		}
		$html1 .= '</table>';


		$tr = null;
		$tracker = null;
		$employer = null;

		if ((@include 'PhpOffice/PhpWord/Autoloader.php')) {
			\PhpOffice\PhpWord\Autoloader::register(); // PhpWord library (automatically registers autoloader on initialisation)
		}
		if ((@include 'PhpOffice/Common/Autoloader.php')) {
			\PhpOffice\Common\Autoloader::register(); // PhpOffice Common library (automatically registers autoloader on initialisation)
		}


		$phpWord = new \PhpOffice\PhpWord\PhpWord();

		$section = $phpWord->addSection();

		$html .= $html1;

		\PhpOffice\PhpWord\Shared\Html::addHtml($section, $html, false, false);


		header('Content-Type: application/octet-stream');
		header('Content-Disposition: attachment;filename="LAR.docx"');
		$objWriter = \PhpOffice\PhpWord\IOFactory::createWriter($phpWord, 'Word2007');
		$objWriter->save('php://output');
	}

	private function get_lar_update_entry_details(PDO $link)
	{
		$tr_id = isset($_REQUEST['tr_id']) ? $_REQUEST['tr_id'] : '';
		$timestamp = isset($_REQUEST['timestamp']) ? $_REQUEST['timestamp'] : '';
		if($tr_id == '' || $timestamp == '')
			throw new Exception('Missing querystring argument: tr_id, timestamp');

		$notes = DAO::getSingleValue($link, "SELECT tr_operations.lar_details FROM tr_operations WHERE tr_id = '{$tr_id}'");
		$notes = XML::loadSimpleXML($notes);

		$lar_types = InductionHelper::getListLAR();
		$rags = InductionHelper::getListLARRAGRating();

		foreach($notes->Note AS $note)
		{
			if($note->DateTime->__toString() == $timestamp)
			{
				$obj = new stdClass();
				$obj->modal_creation_date_time = Date::to($note->DateTime->__toString(), Date::DATETIME);
				$obj->timestamp = $note->DateTime->__toString();
				$obj->lar_notes = $note->Note->__toString();
				$obj->modal_created_by = DAO::getSingleValue($link, "SELECT CONCAT(firstnames, ' ', surname) FROM users WHERE users.id = '" . $note->CreatedBy->__toString() . "'");
				$obj->modal_type = isset($lar_types[$note->Type->__toString()]) ? $lar_types[$note->Type->__toString()] : '';
				$obj->modal_lar_date = $note->Date->__toString();
				$obj->modal_last_action_date = $note->LastActionDate->__toString();
				$obj->modal_next_action_date = $note->NextActionDate->__toString();
				$obj->modal_sales_deadline_date = $note->SalesDeadlineDate->__toString();
				$obj->modal_rag = isset($rags[$note->RAG->__toString()]) ? $rags[$note->RAG->__toString()] : '';

				echo json_encode($obj);
				return;
			}
		}

	}
}