<?php
class save_learner_internal_validation implements IAction
{

	public function execute(PDO $link)
	{
		$vo = new InternalValidation();
		$vo->populate($_POST);

		DAO::transaction_start($link);
		try
		{

			$vo->save($link);

			if(isset($_REQUEST['unit_references']) && count($_REQUEST['unit_references']) > 0)
				$vo->saveIVUnits($link, $_REQUEST['unit_references']);

			// File uploads
			if (isset($_FILES['evidence']) && $_FILES['evidence']['error'] == UPLOAD_ERR_OK && is_uploaded_file($_FILES['evidence']['tmp_name']))
			{
				if(isset($_REQUEST['tr_id']) && $_REQUEST['tr_id'] != '')
					$target_directory = DAO::getSingleValue($link, "SELECT username FROM tr WHERE tr.id = '{$vo->tr_id}'");

				$max_file_upload = Repository::parseFileSize(ini_get("upload_max_filesize"));
				if(Repository::getRemainingSpace() < $max_file_upload)
				{
					$max_file_upload = Repository::getRemainingSpace();
				}
				$file_name = 'iv_evidence_'.$vo->id;

				$files = glob(Repository::getRoot().'/'.$target_directory.'/'.$file_name.'.*');
				if(count($files) > 0)
				{
					foreach($files AS $file)
					{
						if(is_file($file))
							unlink($file);
					}
				}
				$valid_extensions = array('doc', 'docx', 'pdf');
				$r = Repository::processFileUploads('evidence', $target_directory, $valid_extensions, $max_file_upload, $file_name); // 6.0MB max
				$vo->evidence = basename($r[0]);
			}

			$vo->save($link);

			DAO::transaction_commit($link);
		}
		catch(Exception $e)
		{
			DAO::transaction_rollback($link, $e);
			throw new WrappedException($e);
		}

		if(IS_AJAX)
		{
			header("Content-Type: text/plain");
			echo $vo->id;
		}
		else
		{
			http_redirect('do.php?_action=read_training_record&internal_validation_tab=1&id=' . $vo->tr_id);
		}
	}
}
?>