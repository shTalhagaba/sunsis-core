<?php
class save_candidate_employer implements IAction
{
	public function execute(PDO $link)
	{

		$org = new CandidateEmployer();

		$org->populate($_POST);
		$org->id = 0;
		$org->save($link);

		$loc = new CandidateLocation();
		$loc->populate($_POST);
		$loc->organisations_id = $org->id;
		$loc->save($link);
		
		http_redirect('do.php?_action=registration_complete');
	}
}
?>