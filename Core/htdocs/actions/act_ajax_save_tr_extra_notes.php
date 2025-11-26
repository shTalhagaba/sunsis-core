<?php
class ajax_save_tr_extra_notes implements IAction
{
	public function execute(PDO $link)
	{
		$tr_id = isset($_REQUEST['tr_id'])?$_REQUEST['tr_id']:'';
		$extra_notes = isset($_REQUEST['extra_notes'])?$_REQUEST['extra_notes']:'';
		$updated_by = $_SESSION['user']->username;

		$extra_notes = addslashes((string)$extra_notes);
		$sql = "REPLACE INTO tr_extra_notes (tr_id, notes, updated_by) VALUES ('{$tr_id}', '{$extra_notes}', '{$updated_by}')";

		DAO::execute($link, $sql);
	}
}
?>
