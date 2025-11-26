<?php
class baltic_save_candidate_employer implements IUnauthenticatedAction {
	
	public function execute(PDO $link) {
		
		$org = new CandidateEmployer();

		$org->populate($_POST);
		$org->id = 0;
		$result = $org->save($link);

		if ( $result == 1 ) {
			$loc = new CandidateLocation();
			$loc->populate($_POST);
			$loc->organisations_id = $org->id;
			$loc->save($link);
			
			$msg = 'Thank you, we have successfully registered you as an employer on our system';
		
			http_redirect('do.php?_action=view_candidate_employer&msg='.$msg);
		}
		else{
			http_redirect('do.php?_action=view_candidate_employer&msg='.$result);
		}
	}
}
?>