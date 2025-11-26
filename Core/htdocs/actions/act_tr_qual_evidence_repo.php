<?php
class tr_qual_evidence_repo implements IAction
{
	public function execute(PDO $link)
	{
		$qualification_id = isset($_REQUEST['qualification_id'])?$_REQUEST['qualification_id']:'';
		$tr_id = isset($_REQUEST['tr_id'])?$_REQUEST['tr_id']:'';
		$internal_title = isset($_REQUEST['internaltitle'])?$_REQUEST['internaltitle']:'';
		$framework_id = isset($_REQUEST['framework_id'])?$_REQUEST['framework_id']:'';

		$bc_link = "do.php?_action=tr_qual_evidence_repo&qualification_id=".$qualification_id."&tr_id=".$tr_id."&internaltitle=".$internal_title."&framework_id=".$framework_id;

		$_SESSION['bc']->add($link, $bc_link, "View Qualification Evidence Repository");

		$training_record = TrainingRecord::loadFromDatabase($link, $tr_id);
		$framework = Framework::loadFromDatabase($link, $framework_id);
		$course_id = DAO::getSingleValue($link, "select course_id from courses_tr where tr_id = $tr_id");
		$course = Course::loadFromDatabase($link, $course_id);
		$assessor = User::loadFromDatabaseById($link, $training_record->assessor);
		$student_qualification = StudentQualification::loadFromDatabase($link, $qualification_id, $framework_id, $tr_id, $internal_title);
		$evidence_type_dropdown = "SELECT id, content, null FROM lookup_evidence_content ORDER BY id;";
		$evidence_type_dropdown = DAO::getResultset($link, $evidence_type_dropdown);


		// Cancel button URL
		$js_cancel = "window.location.href='do.php?_action=view_tr_qual_portfolio&qualification_id=".$qualification_id."&tr_id=".$tr_id."&internaltitle=".$internal_title."&framework_id=".$framework_id."'";

		require_once('tpl_tr_qual_evidence_repo.php');
	}

	public function buildUnitEvidencesTable(PDO $link, $qualification_id, $internal_title, TrainingRecord $training_record)
	{

		$tr_id = $training_record->id;

		$evidences = DAO::getResultset($link, "SELECT * FROM tr_qual_portfolio_evidences WHERE tr_id = " . $tr_id, DAO::FETCH_ASSOC);

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
		$html .= '<th>Evidence Name</th><th>Evidence Type</th><th>Description</th><th>Size</th><th>Date Uploaded</th><th>Uploaded By</th><th>Actions</th>';


		if($resulting_units)
		{
			foreach($resulting_units AS $unit)
			{
				$html .= '<th class="tooltip" title="' . $unit['reference'] . ' - ' . $unit['title'] . '">' . $unit['reference'] . '</th>';
			}
		}
		$html .= '</tr></thead>';
		$html .= '<tbody>';
		if($resulting_units)
		{
			foreach($evidences as $evidence)
			{

				$html .= "<tr>";
				$html .= '<td class="tooltip" align="left" style="cursor:pointer;word-wrap:break-word;" onclick="downloadFile(\''.$evidence['evidence_name'].'\');" title="Click to Download file">'.htmlspecialchars((string)$evidence['evidence_name']).'</td>';
				$html .= '<td>' . DAO::getSingleValue($link, "SELECT content FROM lookup_evidence_content WHERE id = " . $evidence['evidence_type']) . '</td>';
				$html .= '<td>' . $evidence['evidence_description'] . '</td>';
				$html .= '<td>'.Repository::formatFileSize($evidence['evidence_size']).'</td>';
				$html .= '<td align="center" style="font-family:monospace" width="175">'.Date::to($evidence['date_uploaded'], 'd/m/Y H:i:s') . '</td>';
				$html .= '<td align="center" style="font-family:monospace" width="175">'.DAO::getSingleValue($link, "SELECT CONCAT(users.firstnames, ' ', users.surname, ' (', lookup_user_types.description, ')') FROM users, lookup_user_types WHERE username = '" . $evidence['uploaded_by'] . "' AND users.type = lookup_user_types.id") . '</td>';
				$html .= '<td align="center"><a href="do.php?_action=map_evidence_to_pcs&tr_id=' . $training_record->id . '&qualification_id=' . $qualification_id . '&internaltitle=' . $internal_title . '&evidence_id=' . $evidence['id'].'"><img class="tooltip" style="border-width: 1px;border-style: solid;" title="Map evidence to PCs" height="25" width="25" src="/images/green-tick.gif" /></a></td>';
				foreach($resulting_units AS $unit)
				{
					$evidence_mapped_to_unit = DAO::getSingleValue($link, "SELECT COUNT(*) FROM tr_portfolio_unit_mapping WHERE unit_id = " . $unit['unit_id'] . " AND evidence_id = " . $evidence['id'] . " AND tr_id = " . $tr_id);
					if($evidence_mapped_to_unit > 0)
						$html .= '<td align="center"><input class="tooltip" checked = "checked" title="' . $unit['reference'] . ' - ' . $unit['title'] . '" type="checkbox" name="' . $evidence['id'] . '_' . $unit['unit_id'] . '" id="' . $evidence['id'] . '_' . $unit['unit_id'] . '" onclick="CheckUnCheckEvidenceModule(this);" /></td>';
					else
						$html .= '<td align="center"><input class="tooltip" title="' . $unit['reference'] . ' - ' . $unit['title'] . '" type="checkbox" name="' . $evidence['id'] . '_' . $unit['unit_id'] . '" id="' . $evidence['id'] . '_' . $unit['unit_id'] . '" onclick="CheckUnCheckEvidenceModule(this);" /></td>';
				}

				$html .='</tr>';

			}
		}
		$html .= '</tbody>';
		$html .= '</table>';

		return $html;
	}
	/*public function buildUnitEvidencesTable(PDO $link, $qualification_id, $internal_title, TrainingRecord $training_record)
	{//instead of picking file info from directory save it in table and pick up from there and show here
		$learner_dir = Repository::getRoot().'/'.trim($training_record->username);

		$learner_dir = Repository::getRoot().'/'.trim($training_record->username).'/portfolio';

		$files = Repository::readDirectory($learner_dir);
		if(count($files) == 0){
			return "";
		}
		$tr_id = $training_record->id;
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
		$html .= '<th>Evidence Name</th><th>Evidence Type</th><th>Description</th><th>Size</th><th>Date Uploaded</th>';


		if($resulting_units)
		{
			foreach($resulting_units AS $unit)
			{
				$html .= '<th title="' . $unit['reference'] . ' - ' . $unit['title'] . '">' . $unit['reference'] . '</th>';
			}
		}
		$html .= '</tr></thead>';
		$html .= '<tbody>';
		if($resulting_units)
		{
			foreach($files as $f)
			{
				if($f->isDir()){
					continue;
				}
				$html .= "<tr>";
				$html .= '<td align="left" style="cursor:pointer;word-wrap:break-word;" onclick="downloadFile(\''.$f->getRelativePath().'\');" title="Download file">'.htmlspecialchars((string)$f->getName()).'</td>';
				$html .= '<td>Photograph</td>';
				$html .= '<td>Description</td>';
				$html .= '<td>'.Repository::formatFileSize($f->getSize()).'</td>';
				$html .= '<td align="right" style="font-family:monospace" width="170">'.date("d/m/Y H:i:s", $f->getModifiedTime()).'</td>';
				foreach($resulting_units AS $unit)
				{//onclick="javascript:return  CheckUnCheckEvidenceModule(this,&#39;_10516_168&#39;);"
					$html .= '<td><input title="' . $unit['reference'] . ' - ' . $unit['title'] . '" type="checkbox" name="evidence_' . $unit['unit_id'] . '" id="evidence_' . $unit['unit_id'] . '" onclick="CheckUnCheckEvidenceModule(this);" /></td>';
				}

				$html .='</tr>';

			}
		}
		$html .= '</tbody>';
		$html .= '</table>';

		return $html;
	}*/


}
?>