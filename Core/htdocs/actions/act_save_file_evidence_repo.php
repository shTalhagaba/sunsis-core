<?php
class save_file_evidence_repo implements IAction
{
	public function execute(PDO $link)
	{
		$username = isset($_REQUEST['username']) ? trim($_REQUEST['username']) : ''; // username of the training record
		$qualification_id = isset($_REQUEST['qualification_id'])?$_REQUEST['qualification_id']:'';
		$tr_id = isset($_REQUEST['tr_id'])?$_REQUEST['tr_id']:'';
		$internal_title = isset($_REQUEST['internaltitle'])?$_REQUEST['internaltitle']:'';
		$section = isset($_REQUEST['section']) ? $_REQUEST['section'] : 'portfolio';
		$framework_id = isset($_REQUEST['framework_id'])?$_REQUEST['framework_id']:'';
		$evidence_type = isset($_REQUEST['evidenceType'])?$_REQUEST['evidenceType']:'';
		$evidence_desc = isset($_REQUEST['evidenceDesc'])?$_REQUEST['evidenceDesc']:'';

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
		$valid_extensions = array('pdf', 'doc', 'docx', 'xls', 'xlsx', 'csv', 'txt', 'xml', 'zip', 'rar', '7z', 'amr', 'jpg');

		Repository::processFileUploads('uploadedfile', $target_directory, $valid_extensions);

		$file_size = $_FILES['uploadedfile']['size'];
		$sql = "INSERT INTO tr_qual_portfolio_evidences (evidence_name, evidence_type, evidence_description, tr_id, evidence_size, uploaded_by) VALUES ('" . $_FILES['uploadedfile']['name'] . "', " . $evidence_type . ", '" . $evidence_desc . "', " . $tr_id . ", " . $file_size . ", '" . $_SESSION['user']->username . "')";
		DAO::execute($link, $sql);

		http_redirect('do.php?_action=tr_qual_evidence_repo&tr_id='.$tr_id.'&qualification_id='.$qualification_id.'&framework_id='.$framework_id.'&internaltitle='.$internal_title);
	}


}
?>