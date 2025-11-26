<?php
class read_candidate_employer implements IAction
{
	public function execute(PDO $link)
	{
		$id = isset($_GET['id']) ? $_GET['id'] : '';

		$_SESSION['bc']->add($link, "do.php?_action=read_candidate_employer&id=" . $id, "View Candidate Employer");
		
		if($id == '' || !is_numeric($id))
		{
			throw new Exception("You must specify a numeric id in the querystring");
		}
	
		$vo = CandidateEmployer::loadFromDatabase($link, $id);
				
		include('tpl_read_candidate_employer.php');
	}
}
?>