<?php
class save_otj implements IAction
{
	public function execute(PDO $link)
	{
		$vo = new OTJ();
		$vo->populate($_POST);

		$tr_username = DAO::getSingleValue($link, "SELECT username FROM tr WHERE tr.id = '{$vo->tr_id}'");
	        $valid_extensions = array('pdf', 'doc', 'docx', 'xls', 'xlsx', 'csv', 'txt', 'xml', 'zip', 'rar', '7z');

		DAO::transaction_start($link);
		try
		{
			$vo->save($link);

	        	$target_directory = "/{$tr_username}/OTJ Diaries/{$vo->id}";
        	    	$r = Repository::processFileUploads('uploaded_file', $target_directory, $valid_extensions);

			DAO::transaction_commit($link);
		}
		catch (Exception $e)
		{
			DAO::transaction_rollback($link, $e);
			throw new WrappedException($e);
		}

		http_redirect('do.php?_action=read_training_record&otj_tab=1&id=' . $vo->tr_id);
	}
}

?>