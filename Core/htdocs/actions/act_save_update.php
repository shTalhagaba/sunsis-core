<?php
class save_update implements IAction
{
	public function execute(PDO $link)
	{
		$username = isset($_REQUEST['username']) ? trim($_REQUEST['username']) : '';
		$tr_id = isset($_REQUEST['tr_id']) ? $_REQUEST['tr_id'] : '';
		$section = isset($_REQUEST['section']) ? $_REQUEST['section'] : '';

		if(!$username){
			throw new Exception("Missing querystring argument, username");
		}
		if(preg_match('/[^A-Za-z0-9_\\- ]/', $username)){
			throw new Exception("Illegal characters in querystring argument 'username' (".$username.")");
		}
		if(!$tr_id){
			throw new Exception("Missing querystring argument, tr_id");
		}

		$exists = DAO::getSingleValue($link, "SELECT 1 FROM users WHERE username='".addslashes((string)$username)."'");
		if(!$exists){
			throw new Exception("No user exists with username '".$username."'");
		}

		$target_directory = $username;
		if($section != '')
			$target_directory .= '/'.$section;
		if(DB_NAME == "am_superdrug" || DB_NAME == "am_baltic")
			$valid_extensions = array('pdf', 'doc', 'docx', 'xls', 'xlsx', 'csv', 'txt', 'xml', 'zip', 'rar', '7z', 'amr', 'jpeg', 'jpg');
		elseif(DB_NAME == "am_demo")
			$valid_extensions = array('pdf', 'doc', 'docx', 'xls', 'xlsx', 'csv', 'txt', 'xml', 'zip', 'rar', '7z', 'amr', 'jpeg', 'jpg', 'mp4');
		else
			$valid_extensions = array('pdf', 'doc', 'docx', 'xls', 'xlsx', 'csv', 'txt', 'xml', 'zip', 'rar', '7z', 'amr', 'jpeg', 'jpg');

		$max_file_upload = Repository::parseFileSize(ini_get("upload_max_filesize"));

		if(Repository::getRemainingSpace() < $max_file_upload){
			$max_file_upload = Repository::getRemainingSpace();
		}

		Repository::processFileUploads('uploadedfile', $target_directory, $valid_extensions, $max_file_upload); // 6.0MB max

		http_redirect("do.php?_action=training_file_repo&tr_id={$tr_id}&folder={$section}");

		// if($section!= '')
		// 	http_redirect('do.php?_action=read_training_record&repo=1&id=' . $tr_id . '&section='.$section);
		// else
		//	http_redirect('do.php?_action=read_training_record&repo=1&id=' . $tr_id);
	}


}
?>