<?php
class edit_learner_interview implements IAction
{
	public function execute(PDO $link)
	{
		// Validate data entry
		$interview_id = isset($_REQUEST['interview_id']) ? $_REQUEST['interview_id'] : '';
		$tr_id = isset($_REQUEST['tr_id']) ? $_REQUEST['tr_id'] : '';

		if($tr_id == '')
			throw new Exception('Missing Training Record ID.');

		$_SESSION['bc']->add($link, "do.php?_action=edit_learner_interview&tr_id=" . $tr_id, "Add/Edit Learner Interview");

		if($interview_id == '')
		{
			// New record
			$vo = new Interview();
			$vo->tr_id = $tr_id;
			$page_title = "Add Interview Details";
		}
		else
		{
			$vo = Interview::loadFromDatabase($link, $interview_id);
			$page_title = "Edit Interview Details";
		}

		// Dropdown arrays
		$sql = "SELECT id, description, null FROM lookup_interview_types ORDER BY description; ";
		$interview_types = DAO::getResultSet($link, $sql);

		$sql = "SELECT id, CONCAT(firstnames, ' ', surname), LEFT(firstnames, 1) FROM users WHERE type = 3 ORDER BY firstnames; ";
		$assessors = DAO::getResultSet($link, $sql);

		$sql = "SELECT id, description, null FROM lookup_interview_status ORDER BY description; ";
		$interview_statuses = DAO::getResultSet($link, $sql);

		$sql = "SELECT id, description, null FROM lookup_interview_paperwork ORDER BY description; ";
		$interview_paperworks = DAO::getResultSet($link, $sql);

		$sql = "SELECT modules.id, title, legal_name FROM modules INNER JOIN organisations ON modules.`provider_id` = organisations.id ORDER BY title; ";
		$modules = DAO::getResultSet($link, $sql);

		// Cancel button URL
		$js_cancel = "window.location.replace('do.php?_action=read_training_record&interview_tab=1&id=$tr_id');";

		include('tpl_edit_learner_interview.php');
	}
}
?>