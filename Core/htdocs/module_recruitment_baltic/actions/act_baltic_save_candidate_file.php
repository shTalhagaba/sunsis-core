<?php
class baltic_save_candidate_file implements IAction
{
	public function execute(PDO $link)
	{
		$candidate_id = isset($_REQUEST['candidate_id']) ? $_REQUEST['candidate_id'] : '';
		$control_name = isset($_REQUEST['control_name']) ? $_REQUEST['control_name'] : '';
		$mode = isset($_REQUEST['mode']) ? $_REQUEST['mode'] : '';
		$file_prefix = isset($_REQUEST['file_prefix']) ? $_REQUEST['file_prefix'] : '';

		if(!$candidate_id){
			throw new Exception("Missing querystring argument, candidate_id");
		}

		$target_directory = 'recruitment';

		if($mode == 'update')
			$this->removePreviousCV($candidate_id, $file_prefix);

		$valid_extensions = array('pdf', 'doc', 'docx');
		$filepaths = Repository::processFileUploads($control_name, $target_directory, $valid_extensions);
		foreach($filepaths as $filepath)
		{
			$ext = pathinfo($filepath, PATHINFO_EXTENSION);
			$path = dirname($filepath);
			rename($filepath, $path.'/'.$file_prefix.$candidate_id.'.'.$ext);
		}

		// save the action to the candidate notes
		$candidate_note = new CandidateNotes();
		$candidate_note->candidate_id = $candidate_id;
		if($mode == 'update')
			$candidate_note->note = 'Candidate File ' . $file_prefix.$candidate_id . ' updated by '.$_SESSION['user']->username;
		else
			$candidate_note->note = 'Candidate File ' . $file_prefix.$candidate_id . ' uploaded by '.$_SESSION['user']->username;
		$candidate_note->username = $_SESSION['user']->username;
		$candidate_note->status = 1;
		$candidate_note->save($link);

		http_redirect('do.php?_action=baltic_read_candidate&candidate_id=' . $candidate_id);
	}

	private function removePreviousCV($candidate_id, $file_prefix)
	{

		if ( file_exists(DATA_ROOT."/uploads/".DB_NAME."/recruitment/".$file_prefix.$candidate_id.".doc") ) {
			unlink(DATA_ROOT."/uploads/".DB_NAME."/recruitment/".$file_prefix.$candidate_id.".doc");
		}
		if( file_exists(DATA_ROOT."/uploads/".DB_NAME."/recruitment/".$file_prefix.$candidate_id.".docx") ) {
			unlink(DATA_ROOT."/uploads/".DB_NAME."/recruitment/".$file_prefix.$candidate_id.".docx");
		}
		if( file_exists(DATA_ROOT."/uploads/".DB_NAME."/recruitment/".$file_prefix.$candidate_id.".pdf") ) {
			unlink(DATA_ROOT."/uploads/".DB_NAME."/recruitment/".$file_prefix.$candidate_id.".pdf");
		}
	}

}
?>