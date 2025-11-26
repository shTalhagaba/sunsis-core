<?php
class baltic_ajax_display_candidate_details implements IAction
{
	public function execute(PDO $link)
	{
		header('Content-Type: text/html;');
		$candid = isset($_REQUEST['candid'])?$_REQUEST['candid']:'';
		$vacid = isset($_REQUEST['vacid'])?$_REQUEST['vacid']:'';
		$tabid = isset($_REQUEST['tabid'])?$_REQUEST['tabid']:'';
		if( '' == $candid || '' == $vacid || '' == $tabid ) {
		    
		}
		else {
			
			$candidate_details = Candidate::loadFromDatabase($link, $candid);
			// load the candidate history
			$candidate_details->candidate_notes = CandidateNotes::loadFromDatabase($link, $candid);	
			echo $candidate_details->render_candidate_details($link, $tabid, $vacid);
		}
	}
}
?>
