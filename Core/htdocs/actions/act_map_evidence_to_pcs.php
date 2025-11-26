<?php
class map_evidence_to_pcs implements IAction
{
	public function execute(PDO $link)
	{
		$qualification_id = isset($_REQUEST['qualification_id'])?$_REQUEST['qualification_id']:'';
		$tr_id = isset($_REQUEST['tr_id'])?$_REQUEST['tr_id']:'';
		$internal_title = isset($_REQUEST['internaltitle'])?$_REQUEST['internaltitle']:'';
		$evidence_id = isset($_REQUEST['evidence_id'])?$_REQUEST['evidence_id']:'';


		$_SESSION['bc']->add($link, "do.php?_action=map_evidence_to_pcs", "Map Evidence to Tasks");

		$training_record = TrainingRecord::loadFromDatabase($link, $tr_id);
		$assessor = User::loadFromDatabaseById($link, $training_record->assessor);

		$evidence_type_dropdown = "SELECT id, content, null FROM lookup_evidence_content ORDER BY id;";
		$evidence_type_dropdown = DAO::getResultset($link, $evidence_type_dropdown);

		// Cancel button URL
		$js_cancel = "window.history.go(-1);";

		include_once('tpl_map_evidence_to_pcs.php');
	}

	public function generatePCSTable(PDO $link, $evidence_id, $tr_id, $qualification_id)
	{
		$sql = <<<SQL
SELECT mapping.*, units.`title`, units.`reference` FROM tr_portfolio_unit_mapping mapping INNER JOIN qualification_units units ON mapping.`unit_id` = units.`unit_id`
 INNER JOIN student_qualifications ON units.`qualification_id` = student_qualifications.`auto_id`
 WHERE mapping.`tr_id` = $tr_id AND mapping.`evidence_id` = $evidence_id AND student_qualifications.id = '$qualification_id';
SQL;
		//pre($sql);
		$selected_units = DAO::getResultset($link, $sql, DAO::FETCH_ASSOC);
		$html = "";
		$set = 1;
		$html .= '<div align="left" style="border-radius: 25px;">';
		$html .= '<table width="100%" bgcolor="#e6e6fa" cellspacing="0" cellpadding="6" style="border-radius: 25px;">';

		foreach($selected_units AS $selected_unit)
		{
			$html .= '<tr><td align="left"><span title="click to show/hide detail" class="button" onclick="showComments(' . $selected_unit['unit_id'] . ');">+/-</span> &nbsp;&nbsp;&nbsp;Unit - <b> [' . $selected_unit['reference'] . '] ' . $selected_unit['title'] . '</b></td></tr>';
			$elements = DAO::getResultset($link, "SELECT elements.* FROM qualification_elements elements WHERE elements.`unit_id` = " . $selected_unit['unit_id'], DAO::FETCH_ASSOC);
			$html .= '<tr>';
			$html .= '<td align="left">';
			$html .= '<div style="display: none;" id="'. $selected_unit['unit_id'] . '">';
			$html .= '<table style="border-radius: 25px;" width="100%" bgcolor="#EFEDF5" cellspacing="0" cellpadding="6">';
			foreach($elements AS $element)
			{
				$html .= '<tr><td align="left"><input type="checkbox" onclick="select_element_pcs(this);" id="select_all_' . $element['element_id'] . '" />Select All PC\'s</td><td></td>';
				$html .= '<td align="right"><input type="checkbox" onclick="signoff_element_pcs(this);" id="signoff_all_' . $element['element_id'] . '" />Sign Off All PC\'s</td></tr>';
				$html .= '<tr><td align="left">Map</td><td align="left"><strong>' . $element['title'] . '</strong></td><td align="right">Sign Off</td></tr>';
				$pcs = DAO::getResultset($link, "SELECT pcs.* FROM qualification_pcs pcs WHERE pcs.`element_id` = " . $element['element_id'], DAO::FETCH_ASSOC);
				$html .= '<tr>';
				$html .= '<td colspan="3" align="left">';
				$html .= '<table style="border-radius: 25px;" width="100%" bgcolor="white" cellspacing="0" cellpadding="6">';
				foreach($pcs AS $pc)
				{
					$file_attached = DAO::getSingleValue($link, "SELECT COUNT(*) FROM tr_portfolio_pcs_mapping WHERE tr_id = " . $tr_id . " AND element_id = " . $pc['element_id'] . " AND pc_id = " . $pc['pc_id'] . " AND evidence_id = " . $evidence_id);
					if($file_attached > 0)
						$html .= '<tr><td align="right"><input checked="checked" class="mapped_chkbox" type="checkbox" id="map_' . $element['element_id'] . '_' . $pc['pc_id'] . '" /></td>';
					else
						$html .= '<tr><td align="right"><input  class="mapped_chkbox" type="checkbox" id="map_' . $element['element_id'] . '_' . $pc['pc_id'] . '" /></td>';
					$html .= '<td align="left">' . $pc['title'] . '</td>';
					$signed_off = DAO::getSingleValue($link, "SELECT signoff_date FROM qualification_pcs WHERE element_id = " . $element['element_id'] . " AND pc_id = " . $pc['pc_id']);
					if(is_null($signed_off) OR $signed_off == '')
						$html .= '<td align="right"><input class="signoff_chkbox" type="checkbox" id="signoff_' . $element['element_id'] . '_' . $pc['pc_id'] . '" /></td></tr>';
					else
						$html .= '<td align="right"><input checked="checked" class="signoff_chkbox" type="checkbox" id="signoff_' . $element['element_id'] . '_' . $pc['pc_id'] . '" /></td></tr>';
				}
				$html .= '</table>';
				$html .= '</td></tr><tr><td colspan="3" align="center"><hr></td></tr>';
			}
			$html .= '</table>';
			$html .= '</div></td></tr><tr><td><hr></td></tr>';
		}

		$html .= "</table></div>";

		return $html;

		return $html;
	}
}