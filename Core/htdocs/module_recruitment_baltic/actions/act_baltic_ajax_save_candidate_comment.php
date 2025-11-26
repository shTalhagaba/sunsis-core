<?php
class baltic_ajax_save_candidate_comment implements IAction
{
	public function execute(PDO $link)
	{
		header('Content-Type: text/plain;');
		$id = isset($_REQUEST['comment'])?$_REQUEST['comment']:'';
		$candid = isset($_REQUEST['candid'])?$_REQUEST['candid']:'';
		$status = isset($_REQUEST['status'])?1:0;
		if( '' == $id || '' == $candid) {
		    
		}
		else {	
			$candidate_note = new CandidateNotes();
			$candidate_note->candidate_id = $candid;
			$candidate_note->note = htmlspecialchars((string)$id);
			$candidate_note->username = $_SESSION['user']->username;
			$candidate_note->status = 0;	
			echo $candidate_note->save($link);		
		}
	}
}
?>
