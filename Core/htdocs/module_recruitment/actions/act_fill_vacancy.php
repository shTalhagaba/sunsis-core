<?php
define('METRES_IN_A_MILE', 1609.344);

class fill_vacancy implements IAction {
	public function execute(PDO $link) {
		// Validate data entry
		// vacancy id
		$id = isset($_REQUEST['id']) ? $_REQUEST['id']:'';		
		// candidate id
		$cid = isset($_REQUEST['cd_id']) ? $_REQUEST['cd_id']:'';		
		// postcode 
		$pc = isset($_REQUEST['pc'])?$_REQUEST['pc']:'';	
		// ensure we have both vacancy and candidate
		if( ( $id != '')&&($cid != '' ) ) {
			$vacancy = Vacancy::loadFromDatabase($link, $id);
			// only add a candidate if spaces available
			if ( ( $vacancy->no_of_vacancies > 0 )||( isset($_REQUEST['rmv']) ) ) {	
				$candidate = Candidate::loadFromDatabase($link, $cid);
				// if removal requested without any enrollment - delete the candidate
				// - we should let them know as well
				if ( isset($_REQUEST['rmv']) ) {
					if ( isset($_REQUEST['da']) ) {
						$vacancy->feedback['message'] = '<strong>'.$candidate->firstnames.' '.$candidate->surname.'</strong> has been removed from the system!';
						$vacancy->feedback['background-color'] = '#F6B035';
						$vacancy->feedback['location'] = '#tab-1';
						$candidate->delete($link);
						
						// save the action to the candidate notes
						// this is for posterity only - potential to report against
						$candidate_note = new CandidateNotes();
						$candidate_note->candidate_id = $candidate->id;
						$candidate_note->note = 'Candidate ['.$candidate->firstnames.' '.$candidate->surname.'] Deleted';
						$candidate_note->username = $_SESSION['user']->username;
						$candidate_note->status = 1;	
						$candidate_note->save($link);
					}
					else {
						// delete from candidate_applications
						$candidate->remove_application($link, $id);
						// $candidate->status = 0;
						// $candidate->save($link);
						$vacancy->feedback['message'] = '<strong>'.$candidate->firstnames.' '.$candidate->surname.'</strong> removed from the vacancy';
						$vacancy->feedback['background-color'] = '#DCE5CD';
						$vacancy->feedback['location'] = '#tab-3';
						
						// save the action to the candidate notes
						$candidate_note = new CandidateNotes();
						$candidate_note->candidate_id = $candidate->id;
						$candidate_note->note = 'Candidate removed from vacancy '.$vacancy->job_title.' '.$vacancy->trading_name;
						$candidate_note->username = $_SESSION['user']->username;
						$candidate_note->status = 1;	
						$candidate_note->save($link);
					}
				}
				else {
					// this leaves all the other applications the candidate has made in place
					// we could delete them if required?
					$candidate->enrolled = $id;
					// approved candidate
					$candidate->status = 1;
					$candidate->save($link);
					// set the incremental value of spaces available
					// $vacancy->update($link);
					$vacancy->feedback['message'] = '<strong>'.$candidate->firstnames.' '.$candidate->surname.'</strong> added to the vacancy';
					$vacancy->feedback['background-color'] = '#DCE5CD';
					$vacancy->feedback['location'] = '#tab-3';
					
					// save the action to the candidate notes
					$candidate_note = new CandidateNotes();
					$candidate_note->candidate_id = $candidate->id;
					$candidate_note->note = 'Candidate added to vacancy '.$vacancy->code;
					$candidate_note->username = $_SESSION['user']->username;
					$candidate_note->status = 1;	
					$candidate_note->save($link);
				}
			}
			else {
				$vacancy->feedback['message'] = 'Candidate not added - The vacancy is full!';
				$vacancy->feedback['background-color'] = '#F6B035';
				$vacancy->feedback['location'] = '#tab-1';
			}			
			$vacancy->radius = isset($_REQUEST['radius'])?$_REQUEST['radius']:'25';
			$vacancy->radius_metres = $vacancy->radius * METRES_IN_A_MILE;
		}		
		$view = ViewVacancies::getInstance($link);
		$view->refresh($link, $_REQUEST);		
		// Presentation
		include('tpl_view_vacancy.php');
	}
}
?>
