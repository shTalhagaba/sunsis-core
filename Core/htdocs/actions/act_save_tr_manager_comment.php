<?php
class save_tr_manager_comment implements IAction
{
	public function execute(PDO $link)
	{
		if($_POST['id'] == '')
		{
			$vo = new stdClass();
			$records = DAO::getSingleColumn($link, "SHOW COLUMNS FROM manager_comments");
			foreach($records AS $key => $value)
				$vo->$value = null;
		}
		else
		{
			$vo = DAO::getObject($link, "SELECT * FROM manager_comments WHERE id = '{$_POST['id']}'");
		}

		$vo->tr_id = $_POST['tr_id'];
		$vo->rag = $_POST['rag'];
		$vo->comment_type = $_POST['comment_type'];
		$vo->comment = $_POST['comment'];
		$vo->updated_at = "";
		$vo->last_updated_by = $_SESSION['user']->id;
		$vo->created_at = ($vo->id == "") ? date('Y-m-d H:i:s') : $vo->created_at;
		$vo->created_by = ($vo->id == "") ? $_SESSION['user']->id : $vo->created_by;
		$vo->save_tr_manager_comment = isset($_POST['save_tr_manager_comment']) ? $_POST['save_tr_manager_comment'] : '';
		$vo->to_be_processed_deadline = isset($_POST['to_be_processed_deadline']) ? $_POST['to_be_processed_deadline'] : '';
		$vo->for_caseload = isset($_POST['for_caseload']) ? $_POST['for_caseload'] : '';
		$vo->fs_to_be_processed = isset($_POST['fs_to_be_processed']) ? $_POST['fs_to_be_processed'] : '';

		DAO::saveObjectToTable($link, "manager_comments", $vo);

		if(IS_AJAX)
		{
			header("Content-Type: text/plain");
			echo $vo->id;
		}
		else
		{
			http_redirect('do.php?_action=read_training_record&manager_comment_tab=1&id=' . $vo->tr_id);
		}
	}
}
?>