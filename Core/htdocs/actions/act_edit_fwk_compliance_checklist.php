<?php
class edit_fwk_compliance_checklist extends ActionController
{
	public function indexAction(PDO $link)
	{
		$framework_id = isset($_GET['framework_id']) ? $_GET['framework_id'] : '';

		if($framework_id !== '' && !is_numeric($framework_id))
		{
			throw new Exception("You must specify a numeric id in the querystring");
		}

		$framework = Framework::loadFromDatabase($link, $framework_id);
		if(is_null($framework))
		{
			throw new Exception('Invalid framework id');
		}

		$_SESSION['bc']->add($link, "do.php?_action=edit_fwk_compliance_checklist&framework_id=" . $framework->id, "Edit Learner Compliance");

		$sql = <<<SQL
SELECT
  compliance_checklist.*,
  (SELECT COUNT(*) FROM tr_compliance WHERE compliance_item_id = compliance_checklist.id) AS related_entries
FROM
  compliance_checklist
WHERE
  compliance_checklist.framework_id = '{$framework->id}'
ORDER BY
    compliance_checklist.sorting;
SQL;

		$compliance_checklist = DAO::getResultset($link, $sql, DAO::FETCH_ASSOC);

		include('tpl_edit_fwk_compliance_checklist.php');
	}

	public function saveEntryAction(PDO $link)
	{
		if(!isset($_REQUEST['framework_id']) || $_REQUEST['framework_id'] == '')
		{
			throw new Exception("Missing querystring argument: framework_id");
		}

		if(!isset($_REQUEST['c_event']) || trim($_REQUEST['c_event']) == '')
		{
			return;
		}

		$sorting = $_REQUEST['sorting'];
		if($sorting == '')
			$sorting = DAO::getSingleValue($link, "SELECT MAX(sorting)+1 FROM compliance_checklist WHERE framework_id = '{$_REQUEST['framework_id']}'");

		$entry = new stdClass();
		$entry->id = $_REQUEST['id'] == 'newrow' ? null : $_REQUEST['id'];
		$entry->framework_id = $_REQUEST['framework_id'];
		$entry->c_event = $_REQUEST['c_event'];
		$entry->sorting = $sorting;

		DAO::saveObjectToTable($link, "compliance_checklist", $entry);
	}

	public function deleteEntryAction(PDO $link)
	{
		$id = isset($_REQUEST['id']) ? $_REQUEST['id'] : '';
		if($id == '')
			throw new Exception("Missing querystring argument: id");

		$linked_records = DAO::getSingleValue($link, "SELECT COUNT(*) FROM tr_compliance WHERE compliance_item_id = '{$id}'");
		if($linked_records > 0)
			throw new Exception("This entry has associated training records, delete aborted.");

		DAO::execute($link, "DELETE FROM compliance_checklist WHERE id = '{$id}'");

	}
}
?>