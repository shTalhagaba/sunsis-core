<?php
class delete_candidate_employer implements IAction
{
	public function execute(PDO $link)
	{
		// Validate data entry
		$org_id = isset($_REQUEST['id'])?$_REQUEST['id']:'';
						
		if($org_id == '')
		{
			throw new Exception("Missing argument \$org_id");
		}
		
		$vo = CandidateEmployer::loadFromDatabase($link, $org_id);

	 	
		if(is_null($vo))
		{
			
			throw new Exception("Could not find Organisation '$qualification_id'");
			
		}
		
		$vo->delete($link);
		
		http_redirect($_SESSION['bc']->getPrevious());
	}
}
