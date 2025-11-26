<?php
class view_tr_qual_portfolio implements IAction
{
	public function execute(PDO $link)
	{
		$qualification_id = isset($_REQUEST['qualification_id'])?$_REQUEST['qualification_id']:'';
		$tr_id = isset($_REQUEST['tr_id'])?$_REQUEST['tr_id']:'';
		$internal_title = isset($_REQUEST['internaltitle'])?$_REQUEST['internaltitle']:'';
		$framework_id = isset($_REQUEST['framework_id'])?$_REQUEST['framework_id']:'';

		$bc_link = "do.php?_action=view_tr_qual_portfolio&qualification_id=".$qualification_id."&tr_id=".$tr_id."&internaltitle=".$internal_title."&framework_id=".$framework_id;

		$_SESSION['bc']->add($link, $bc_link, "View Qualification Portfolio");

		$training_record = TrainingRecord::loadFromDatabase($link, $tr_id);
		$framework = Framework::loadFromDatabase($link, $framework_id);
		$course_id = DAO::getSingleValue($link, "select course_id from courses_tr where tr_id = $tr_id");
		$course = Course::loadFromDatabase($link, $course_id);
		if($training_record->assessor != '')
			$assessor = User::loadFromDatabaseById($link, $training_record->assessor);
		else
			$assessor = NULL;
		$student_qualification = StudentQualification::loadFromDatabase($link, $qualification_id, $framework_id, $tr_id, $internal_title);

		$fsd = new Date($training_record->start_date);
		$fed = new Date($training_record->target_date);

		$coursestamp = $fed->getDate() - $fsd->getDate();
		$currentstamp = time() - $fsd->getDate();

		$days_between_course_start_date_and_end_date = (($coursestamp/60)/60)/24;
		$days_between_course_start_date_and_today = (($currentstamp/60)/60)/24;

		//$months_in_course = round($days_between_course_start_date_and_end_date / 30,0);
		$que = "SELECT TIMESTAMPDIFF(MONTH, start_date, DATE_ADD(target_date, INTERVAL 1 DAY)) FROM tr WHERE id = {$training_record->id};";
		$months_in_course = trim(DAO::getSingleValue($link, $que));

		if($months_in_course==0)
			$months_in_course = 1;

		$months_passed_float = (round($days_between_course_start_date_and_today / 30,2));

		if($months_passed_float>$months_in_course)
			$months_passed_float = $months_in_course;

		$months_passed = floor($days_between_course_start_date_and_today / 30);

		$months_passed = ($months_passed<0)?0:$months_passed;

		if($months_passed>$months_in_course)
			$months_passed = $months_in_course;

		$query_total_pcs_for_this_qual = <<<SQL
		SELECT COUNT(*) FROM qualification_pcs pcs INNER JOIN qualification_elements elements ON pcs.`element_id` = elements.`element_id`
INNER JOIN qualification_units units ON elements.`unit_id` = units.`unit_id`
INNER JOIN student_qualifications s_qualifications ON units.`qualification_id` = s_qualifications.`auto_id`
WHERE  s_qualifications.`id` = '$qualification_id'
AND s_qualifications.`tr_id` = $tr_id
;

SQL;

		$query_total_sign_off = <<<SQL
SELECT COUNT(*) FROM
tr_portfolio_pcs_mapping mapping
INNER JOIN qualification_pcs pcs ON mapping.`pc_id` = pcs.`pc_id`
INNER JOIN qualification_elements elements ON pcs.`element_id` = elements.`element_id` AND mapping.`element_id` = elements.`element_id`
INNER JOIN qualification_units units ON elements.`unit_id` = units.`unit_id`
INNER JOIN student_qualifications s_qualifications ON units.`qualification_id` = s_qualifications.`auto_id`
WHERE  s_qualifications.`id` = '$qualification_id'
AND s_qualifications.`tr_id` = $tr_id
AND pcs.`signoff_date` IS NOT NULL
;
SQL;

		$query_total_awaiting_sign_off = <<<SQL
SELECT COUNT(*) FROM
tr_portfolio_pcs_mapping mapping
INNER JOIN qualification_pcs pcs ON mapping.`pc_id` = pcs.`pc_id`
INNER JOIN qualification_elements elements ON pcs.`element_id` = elements.`element_id` AND mapping.`element_id` = elements.`element_id`
INNER JOIN qualification_units units ON elements.`unit_id` = units.`unit_id`
INNER JOIN student_qualifications s_qualifications ON units.`qualification_id` = s_qualifications.`auto_id`
WHERE  s_qualifications.`id` = '$qualification_id'
AND s_qualifications.`tr_id` = $tr_id
AND pcs.`signoff_date` IS NULL
;
SQL;


		$total_pcs_for_this_qual = DAO::getSingleValue($link, $query_total_pcs_for_this_qual);
		$total_sign_off = DAO::getSingleValue($link, $query_total_sign_off);
		$total_awaiting_sign_off = DAO::getSingleValue($link, $query_total_awaiting_sign_off);

		$total_sign_off_percentage = 0;
		$total_awaiting_sign_off_percentage = 0;
		if($total_pcs_for_this_qual != 0)
		{
			$total_sign_off_percentage = ($total_sign_off / $total_pcs_for_this_qual) * 100;
			$total_awaiting_sign_off_percentage = ($total_awaiting_sign_off / $total_pcs_for_this_qual) * 100;
		}

		$total_sign_off_percentage = $this->roundToTwoDigits($total_sign_off_percentage);
		$total_awaiting_sign_off_percentage = $this->roundToTwoDigits($total_awaiting_sign_off_percentage);

		// Cancel button URL
		$js_cancel = "window.location.replace('do.php?_action=read_training_record&id=$tr_id');";

		require_once('tpl_view_tr_qual_portfolio.php');
	}

	private function roundToTwoDigits($number)
	{
		return number_format((float)$number, 2, '.', '');
	}

	public function getUnitProgress(PDO $link, $unit_id, $qualification_id, $tr_id)
	{
		$query_total_pcs_for_this_unit = <<<SQL
		SELECT COUNT(*) FROM qualification_pcs pcs INNER JOIN qualification_elements elements ON pcs.`element_id` = elements.`element_id`
INNER JOIN qualification_units units ON elements.`unit_id` = units.`unit_id`
INNER JOIN student_qualifications s_qualifications ON units.`qualification_id` = s_qualifications.`auto_id`
WHERE  s_qualifications.`id` = '$qualification_id'
AND s_qualifications.`tr_id` = $tr_id
AND units.`unit_id` = $unit_id
;
SQL;
		$query_total_sign_off = <<<SQL
		SELECT COUNT(*) FROM
tr_portfolio_pcs_mapping mapping
INNER JOIN qualification_pcs pcs ON mapping.`pc_id` = pcs.`pc_id`
INNER JOIN qualification_elements elements ON pcs.`element_id` = elements.`element_id` AND mapping.`element_id` = elements.`element_id`
INNER JOIN qualification_units units ON elements.`unit_id` = units.`unit_id`
INNER JOIN student_qualifications s_qualifications ON units.`qualification_id` = s_qualifications.`auto_id`
WHERE  s_qualifications.`id` = '$qualification_id'
AND s_qualifications.`tr_id` = $tr_id
AND pcs.`signoff_date` IS NOT NULL
AND units.`unit_id` = $unit_id
SQL;

		$query_total_awaiting_sign_off = <<<SQL
		SELECT COUNT(*) FROM
tr_portfolio_pcs_mapping mapping
INNER JOIN qualification_pcs pcs ON mapping.`pc_id` = pcs.`pc_id`
INNER JOIN qualification_elements elements ON pcs.`element_id` = elements.`element_id` AND mapping.`element_id` = elements.`element_id`
INNER JOIN qualification_units units ON elements.`unit_id` = units.`unit_id`
INNER JOIN student_qualifications s_qualifications ON units.`qualification_id` = s_qualifications.`auto_id`
WHERE  s_qualifications.`id` = '$qualification_id'
AND s_qualifications.`tr_id` = $tr_id
AND pcs.`signoff_date` IS NULL
AND units.`unit_id` = $unit_id
SQL;
		$total_pcs_for_this_unit = DAO::getSingleValue($link, $query_total_pcs_for_this_unit);
		$total_sign_off = DAO::getSingleValue($link, $query_total_sign_off);
		$total_awaiting_sign_off = DAO::getSingleValue($link, $query_total_awaiting_sign_off);

		$total_sign_off_percentage = 0;
		$total_awaiting_sign_off_percentage = 0;
		if($total_pcs_for_this_unit != 0)
		{
			$total_sign_off_percentage = ($total_sign_off / $total_pcs_for_this_unit) * 100;
			$total_awaiting_sign_off_percentage = ($total_awaiting_sign_off / $total_pcs_for_this_unit) * 100;
		}

		$total_sign_off_percentage = $this->roundToTwoDigits($total_sign_off_percentage);
		$total_awaiting_sign_off_percentage = $this->roundToTwoDigits($total_awaiting_sign_off_percentage);

		$result = array();
		$result[] = $this->roundToTwoDigits($total_sign_off_percentage);
		$result[] = $this->roundToTwoDigits($total_awaiting_sign_off_percentage);

		return $result;
	}

	public function buildUnitProgressTable(PDO $link, $qualification_id, $tr_id)
	{
		$sql = <<<SQL
SELECT unit_id, reference, qualification_units.title FROM qualification_units
INNER JOIN student_qualifications ON student_qualifications.auto_id = qualification_units.qualification_id
WHERE student_qualifications.id = '$qualification_id'
AND tr_id = '$tr_id';
SQL;
		$resulting_units = DAO::getResultset($link, $sql, DAO::FETCH_ASSOC);

		$html = "";
		$html .= '<table class="resultset" border="0" cellspacing="0" cellpadding="6">';
		$html .= '<thead><tr>';
		$html .= '<th>Unit Reference</th><th>Unit Title</th><th>Evidences</th><th>Progress (Signed off % / Awaiting Sign off %)</th>';
		$html .= '</tr></thead>';
		$html .= '<tbody>';
		foreach($resulting_units AS $unit)
		{
			$html .= '<tr>';
			$html .= '<td>' . $unit['reference'] . '</td>';
			$html .= '<td>' . $unit['title'] . '</td>';
			$html .= '<td>' . $this->prepareUnitEvidenceFiles($link, $unit['unit_id'], $tr_id) . '</td>';
			$progress = $this->getUnitProgress($link, $unit['unit_id'], $qualification_id, $tr_id);
			$total_sign_off_percentage = $progress[0];
			$total_awaiting_sign_off_percentage = $progress[1];
			$html .= '<td>' . $this->prepareUnitProgressBars($total_sign_off_percentage, $total_awaiting_sign_off_percentage) . '</td>';
			$html .= '</tr>';
		}
		$html .= '</tbody>';
		$html .= '</table>';

		return $html;
	}

	private function prepareUnitEvidenceFiles(PDO $link, $unit_id, $tr_id)
	{
		$tr_username = DAO::getSingleValue($link, "SELECT username FROM tr WHERE id = " . $tr_id);
		$sql = <<<SQL
		SELECT evidences.* FROM tr_qual_portfolio_evidences evidences
INNER JOIN tr_portfolio_unit_mapping unit_mapping ON evidences.`id` = unit_mapping.`evidence_id`
WHERE unit_mapping.`unit_id` = $unit_id
AND unit_mapping.`tr_id` = $tr_id;
SQL;
		$evidences = DAO::getResultset($link, $sql, DAO::FETCH_ASSOC);
		$result = "";
		foreach($evidences AS $evidence)
		{
			$evidence_tooltip = "Evidence: " . $evidence['evidence_name'];
			$evidence_tooltip .= "<br>Evidence Type: " . DAO::getSingleValue($link, "SELECT type FROM lookup_evidence_type WHERE id = " . $evidence['evidence_type']);
			$evidence_tooltip .= "<br>Evidence Description: " . $evidence['evidence_description'];
			$evidence_tooltip .= "<br>Evidence Size: " . Repository::formatFileSize($evidence['evidence_size']);
			$evidence_tooltip .= "<br>DateTime Uploaded: " . Date::to($evidence['date_uploaded'], 'd/m/Y H:i:s');
			$evidence_tooltip .= "<br>Uploaded By: " . DAO::getSingleValue($link, "SELECT CONCAT(users.firstnames, ' ', users.surname, ' (', lookup_user_types.description, ')') FROM users, lookup_user_types WHERE username = '" . $evidence['uploaded_by'] . "' AND users.type = lookup_user_types.id");
			$download_link = "do.php?_action=downloader&path=" . DB_NAME . "/" . $tr_username . "/portfolio/&f=" . $evidence['evidence_name'];
			$result .= '<a href="' . $download_link . '"><img class="tooltip"  src="/images/file.png" alt="evidence" title="' . $evidence_tooltip . '"></a>&nbsp;&nbsp;&nbsp;';
		}
		return $result;
	}

	private function prepareUnitProgressBars($total_sign_off_percentage, $total_awaiting_sign_off_percentage)
	{
		$html = <<<HTML
		<table width="100%">
		<tr style='width:10%; '>
			<td style="width: 10%; color: black; font-family: Arial,Helvetica; font-size: 12px; text-align: center; border-radius: 5px;">$total_sign_off_percentage%</td>
			<td style="padding-left: 5px; padding-right: 5px; ">
				<div class="SmallPercentageBarSignedOff" style=" border-radius:25px;">
					<div class="percent" style="width: $total_sign_off_percentage%; border-radius:25px;">&nbsp;</div>
					<div class="caption"></div>
				</div>
			</td>
		</tr>
		<tr style='width:100%; '>
			<td style="width: 10%; color: black; font-family: Arial,Helvetica; font-size: 12px; text-align: center; border-radius: 5px;">$total_awaiting_sign_off_percentage%</td>
			<td style="padding-left: 5px; padding-right: 5px; ">
				<div class="SmallPercentageBarASignedOff" style=" border-radius:25px;">
					<div class="percent" style="width: $total_awaiting_sign_off_percentage%; border-radius:25px;">&nbsp;</div>
					<div class="caption"></div>
				</div>
			</td>
		</tr>
	</table>
HTML;

		return $html;
	}
	/*public function buildUnitEvidencesTable(PDO $link, $qualification_id, $internal_title, $tr_id)
	{
		$qual = $qualification_id.$internal_title;
		$sql = <<<SQL
SELECT unit_id, reference, qualification_units.title FROM qualification_units
INNER JOIN student_qualifications ON student_qualifications.auto_id = qualification_units.qualification_id
WHERE CONCAT(student_qualifications.id,student_qualifications.internaltitle) = '$qual'
AND tr_id = '$tr_id';
SQL;
		$resulting_units = DAO::getResultset($link, $sql, DAO::FETCH_ASSOC);

		$html = "";
		$html .= '<table class="resultset" border="0" cellspacing="0" cellpadding="6">';
		$html .= '<thead><tr>';
		$html .= '<th>Evidence Name</th><th>Evidence Type</th><th>Description</th><th>Date Uploaded</th><th>Size</th>';
		if($resulting_units)
		{
			foreach($resulting_units AS $unit)
			{
				$html .= '<th title="' . $unit['reference'] . ' - ' . $unit['title'] . '">' . $unit['reference'] . '</th>';
			}
		}
		$html .= '</tr></thead>';
		$html .= '<tbody>';
		$html .= '</tbody>';
		$html .= '</table>';

		return $html;
	}*/
}
?>