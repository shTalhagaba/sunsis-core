<?php
class edit_tr_manager_comment implements IAction
{
	public function execute(PDO $link)
	{
		$id = isset($_REQUEST['id']) ? $_REQUEST['id'] : '';
		$tr_id = isset($_REQUEST['tr_id']) ? $_REQUEST['tr_id'] : '';

		if(isset($_REQUEST['ajax_request']) && $_REQUEST['ajax_request'])
		{
			if(isset($_REQUEST['id']))
				echo $this->deleteManagerCommentRecord($link, $_REQUEST['id']);
			else
				echo 'Missing query string argument.';
			exit;
		}

		if($tr_id == '')
			throw new Exception('Missing Training Record ID.');

		$pot_vo = TrainingRecord::loadFromDatabase($link,$tr_id);
		if(is_null($pot_vo))
			throw new Exception('Invalid Training Record ID.');

		$_SESSION['bc']->add($link, "do.php?_action=edit_tr_manager_comment&tr_id=" . $tr_id, "Add/Edit Manager Comment");

		if($id == '')
		{
			$vo = new stdClass();
			$records = DAO::getSingleColumn($link, "SHOW COLUMNS FROM manager_comments");
			foreach($records AS $key => $value)
				$vo->$value = null;
			$vo->tr_id = $tr_id;
			$page_title = "Add Manager Comment";
		}
		else
		{
			$vo = DAO::getObject($link, "SELECT * FROM manager_comments WHERE id = '{$id}'");
			$page_title = "Edit Manager Comment";
		}

		$enable_save = true;
		if($_SESSION['user']->type == User::TYPE_LEARNER || $_SESSION['user']->type == User::TYPE_SYSTEM_VIEWER)
			$enable_save = false;
		if(in_array($_SESSION['user']->username, ['jcoates', 'fkhan1234', 'jakbird', 'atodd123', 'mijones12', 'codiefoster', 'arockett16', 'jparkin18', 'marbrown', 'creay123', 'nellwood1', 'hgibson1', 'lcolquhoun', 'ecann123', 'nrichardson1', 'rachaelgreen', 'jrearsv']))
			$enable_save = true;

		$js_cancel = "window.location.replace('do.php?_action=read_training_record&manager_comment_tab=1&id=$tr_id');";

		$other_records = $this->renderOtherRecords($link, $pot_vo, $vo->id);

		include('tpl_edit_tr_manager_comment.php');
	}


	private function deleteManagerCommentRecord(PDO $link, $id)
	{
		$result = DAO::execute($link, "DELETE FROM manager_comments WHERE id = '{$id}'");
		if($result > 0)
			return 'The record has been successfully deleted.';
		else
			return 'Operation failed.';
	}

	private function renderOtherRecords(PDO $link, TrainingRecord $tr, $exclude_id = '')
	{
		if($exclude_id == '')
			$records = DAO::getResultset($link, "SELECT * FROM manager_comments WHERE tr_id = '{$tr->id}' ORDER BY id", DAO::FETCH_ASSOC);
		else
			$records = DAO::getResultset($link, "SELECT * FROM manager_comments WHERE tr_id = '{$tr->id}' AND id != '{$exclude_id}' ORDER BY id", DAO::FETCH_ASSOC);

		$html = '';
		if(count($records) == 0)
			return $html;

		$comment_types = [
			'ER' => 'Employer reference comment',
			'LP' => 'Learner progress comment'
		];
		$rags = [
			'R' => 'Red',
			'A' => 'Amber',
			'G' => 'Green'
		];
		foreach($records AS $row)
		{
			$ct = isset($comment_types[$row['comment_type']]) ? $comment_types[$row['comment_type']] : $row['comment_type'];
			$rag = isset($rags[$row['rag']]) ? $rags[$row['rag']] : $row['rag'];
			$created_by = DAO::getSingleValue($link, "SELECT CONCAT(firstnames, ' ', surname) FROM users WHERE users.id = '{$row['created_by']}'");
			$last_updated_by = DAO::getSingleValue($link, "SELECT CONCAT(firstnames, ' ', surname) FROM users WHERE users.id = '{$row['last_updated_by']}'");
			$html .= '<div class="well well-small well-sm">';
			$html .= '<div class="table-responsive">';
			$html .= '<table class="table">';
			$html .= '<tr><th>Created At:</th><td>' . Date::to($row['created_at'], Date::DATETIME) . '</td><th>Last Updated At:</th><td>' . Date::to($row['updated_at'], Date::DATETIME) . '</td></tr>';
			$html .= '<tr><th>Created By:</th><td>' . $created_by . '</td><th>Last Updated By:</th><td>' . $last_updated_by . '</td></tr>';
			$html .= '<tr><th>Comment Type:</th><td>' . $ct . '</td><th>RAG: </th><td>' . $rag . '</td></tr>';
			$html .= '<tr><th>Comments:</th><td colspan="3">' . $row['comment'] . '</td> </tr>';
			$html .= '</table> ';
			$html .= '</div>';
			$html .= '</div><hr>';
		}

		return $html;
	}
}
?>