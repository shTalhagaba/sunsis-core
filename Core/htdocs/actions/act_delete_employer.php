<?php
class delete_employer implements IAction
{
	public function execute(PDO $link)
	{
		// Validate data entry
		$org_id = isset($_REQUEST['id'])?$_REQUEST['id']:'';
						
		if($org_id == '')
		{
			throw new Exception("Missing argument \$org_id");
		}

        $learners = DAO::getSingleValue($link, "select count(*) from users where type = 5 and employer_id = '$org_id'");
        if($learners!='' && $learners!='0')
            throw new Exception("This Employer has learners attached so it cannot be deleted");
		$learners = DAO::getSingleValue($link, "select count(*) from tr where tr.employer_id = '$org_id'");
        if($learners!='' && $learners!='0')
            throw new Exception("This Employer has learners attached so it cannot be deleted");

		$vo = Organisation::loadFromDatabase($link, $org_id);

	 	
		if(is_null($vo))
		{
			throw new Exception("Could not find Organisation '$org_id'");
		}
		
		$vo->delete($link);
		
		http_redirect($_SESSION['bc']->getPrevious());
	}
}
