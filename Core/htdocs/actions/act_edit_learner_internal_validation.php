<?php
class edit_learner_internal_validation implements IAction
{
	public function execute(PDO $link)
	{
		// Validate data entry
		$iv_id = isset($_REQUEST['iv_id']) ? $_REQUEST['iv_id'] : '';
		$tr_id = isset($_REQUEST['tr_id']) ? $_REQUEST['tr_id'] : '';
		$subaction = isset($_REQUEST['subaction']) ? $_REQUEST['subaction'] : '';

		if(isset($_REQUEST['ajax_request']) && $_REQUEST['ajax_request'] && $subaction == 'delete_record')
		{
			if(isset($_REQUEST['internal_validation_id']))
				echo $this->deleteLearnerInternalValidationRecord($link, $_REQUEST['internal_validation_id']);
			else
				echo 'Missing query string argument.';
			exit;
		}

		if($tr_id == '')
			throw new Exception('Missing Training Record ID.');

		if(isset($_REQUEST['ajax_request']) && $_REQUEST['ajax_request'] && $subaction == 'load_units')
		{
			echo $this->getUnitsGridHTML($link, $_REQUEST['qualification_id'], $_REQUEST['tr_id']);
			exit;
		}

		$_SESSION['bc']->add($link, "do.php?_action=edit_learner_internal_validation&tr_id=" . $tr_id, "Add/Edit Learner IV Record");

		$selected_units = array();
		if($iv_id== '')
		{
			// New record
			$vo = new InternalValidation();
			$vo->tr_id = $tr_id;
			$page_title = "Create IV Details";
			$qualifications_ddl = DAO::getResultset($link, "SELECT REPLACE(id, '/', '') AS id, CONCAT(REPLACE(id, '/', ''), ' - ', internaltitle) FROM student_qualifications WHERE tr_id = '$tr_id'");
			$units_ddl = array();
		}
		else
		{
			$vo = InternalValidation::loadFromDatabase($link, $iv_id);
			$page_title = "Edit Internal Validation Details";
			$qualifications_ddl = DAO::getResultset($link, "SELECT REPLACE(id, '/', '') AS id, CONCAT(REPLACE(id, '/', ''), ' - ', internaltitle) FROM student_qualifications WHERE tr_id = '$tr_id' AND REPLACE(id, '/', '') = '{$vo->iv_qualification_id}'");
			$units_ddl = $this->getUnits($link, $vo->iv_qualification_id, $tr_id);
			$selected_units = DAO::getSingleColumn($link, "SELECT unit_reference FROM internal_validation_unit_details WHERE internal_validation_unit_details.internal_validation_id = " . $vo->id);
		}

		$iv_types = array(
			0=>array(1, 'Interim'),
			1=>array(2, 'Summative'))
		;

		$sql = "SELECT users.id, CONCAT(firstnames, ' ', surname , ' (', lookup_user_types.`description`, ')'), NULL FROM users INNER JOIN lookup_user_types ON users.type = lookup_user_types.id WHERE users.type IN (4,15,17) AND web_access = 1 ORDER BY firstnames; ";
		$iv_ddl = DAO::getResultSet($link, $sql);

		// Cancel button URL
		$js_cancel = "window.location.replace('do.php?_action=read_training_record&internal_validation_tab=1&id=$tr_id');";

		$user_types_with_save_access  = array(
			User::TYPE_MANAGER,
			User::TYPE_GLOBAL_VERIFIER,
			User::TYPE_VERIFIER
		);

		$tr_username = DAO::getSingleValue($link, "SELECT username FROM tr WHERE tr.id = '{$tr_id}'");
		$evidence_link = '';
		if (file_exists(Repository::getRoot().'/'.$tr_username.'/'.$vo->evidence))
		{
			$evidence_link = '<a href="do.php?_action=downloader&path=/'.$tr_username.'/&f='.$vo->evidence.'">Evidence File</a>';
		}

		include('tpl_edit_learner_internal_validation.php');
	}

	private function deleteLearnerInternalValidationRecord(PDO $link, $internal_validation_id)
	{
		$sql = <<<HEREDOC
DELETE FROM
	internal_validation, internal_validation_unit_details
USING
	internal_validation LEFT JOIN internal_validation_unit_details
	ON internal_validation.id = internal_validation_unit_details.`internal_validation_id`
WHERE
	internal_validation.id = $internal_validation_id

HEREDOC;
		$result = DAO::execute($link, $sql);
		if($result > 0)
			return 'The record has been successfully deleted.';
		else
			return 'Operation failed.';
	}

	private function getUnitsGridHTML(PDO $link, $qualification_id, $tr_id, $iv_id = null)
	{
		$selected_units = array();
		$units_ddl = $this->getUnits($link, $qualification_id, $tr_id);
		if(!is_null($iv_id))
			$selected_units = DAO::getSingleColumn($link, "SELECT unit_reference FROM internal_validation_unit_details WHERE internal_validation_unit_details.internal_validation_id = " . $iv_id);
		return HTML::checkboxGrid('unit_references', $units_ddl, $selected_units, 2);
	}

	private function getUnits(PDO $link, $qualification_id, $tr_id)
	{
		$qualification_id = str_replace('/', '', $qualification_id);

		$sql = <<<HEREDOC
SELECT
	 student_qualifications.id,
	 student_qualifications.evidences
FROM
	 student_qualifications
WHERE
	 student_qualifications.tr_id = '$tr_id' AND REPLACE(student_qualifications.id, '/', '') = '$qualification_id' ;
HEREDOC;

		$student_qualifications = DAO::getResultset($link, $sql, DAO::FETCH_ASSOC);

		$units_ddl = array();
		foreach ($student_qualifications AS $qualification)
		{
			$evidence = XML::loadSimpleXML($qualification['evidences']);

			$units = $evidence->xpath('//unit');
			$q_units = array();
			foreach ($units AS $unit)
			{
				$temp = (array)$unit->attributes();
				$temp = $temp['@attributes'];
				//$temp['reference'] = str_replace('/','', $temp['reference']);
				if($temp['chosen'] == 'true')
					$q_units[] = $temp;
			}
			$units_ddl[] = $q_units;
		}
		$final_ddl = array();
		foreach($units_ddl AS $unit_entry)
		{
			for($i=0;$i<count($unit_entry);$i++)
				$final_ddl[] = array($unit_entry[$i]['reference'], $unit_entry[$i]['reference'] . ' - ' . $unit_entry[$i]['title']);
		}
		return $final_ddl;
	}
}
?>