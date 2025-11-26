<?php
class new_candidate implements IAction
{
	public function execute(PDO $link)
	{

		if ( !isset($_REQUEST['vacancy_id']) ) {
			// resets the breadcrumb trail.
			$_SESSION['bc']->index=0;
			$_SESSION['bc']->add($link, "do.php?_action=new_candidate", "New Candidate");
		}

		$candidate = new Candidate();
		$candidate->candidate_notes = new CandidateNotes();
		$candidate->dob = date('Y-m-d');
		
		//metadata capture types
  		// instantiate the user 
		$registrant = new User();
		// get the client specific data required for capture
		$registrant->getUserMetaData($link);
		
		$candidate->metadata = $registrant->user_metadata;
		
		require_once('tpl_new_candidate.php');
		
	}
}
?>
