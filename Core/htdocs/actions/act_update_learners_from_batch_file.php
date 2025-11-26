<?php
class update_learners_from_batch_file implements IAction
{
	public function execute(PDO $link)
	{

		$edrs = '';
		$handle = fopen("sttgl.txt","r");
		$st = fgets($handle);
		
		$tr_id=0;
		$user = new User();
		$tr = new TrainingRecord();
		$usernames = Array();
		$text = '';		
		while(!feof($handle))
		{

			$st = fgets($handle);
			if(trim(substr($st,20,2))=='30' || trim(substr($st,20,2))=='35')
			{
				$aim = (string)substr($st,29,8);

				$found = DAO::getSingleValue($link, "select count(*) from qualifications where id = '$aim'");
				
				if($found==0)
					if(!in_array($aim, $usernames))
						$usernames[] = $aim;

	//			if($aim!='10052185' && $aim!='10052173' && $aim!='50020298' && $aim!='10060583' && $aim!='10047955' && $aim!='10060637' && $aim!='50037018' && $aim!='10060844')
//					throw new Exception($aim);
				
			//	if(!in_array($aim, $usernames))
			//		$usernames[] = $aim;
			}
			
/* 
			// Create Learners
			if(trim(substr($st,20,2))=='10')
			{
				$uln = trim(substr($st,364,10));
				$dob = substr($st,93,4) . "-" . substr($st,91,2) . "-" . trim(substr($st,89,2));
				$ni = trim(substr($st,261,9));
				$gender = trim(substr($st,99,1));
				$ethnicity = trim(substr($st,97,2));
				$home_street_description = trim(substr($st,113,30));
				$home_locality = trim(substr($st,143,30));
				$home_town = trim(substr($st,173,30));
				$home_county = trim(substr($st,203,30));
				$home_postcode = trim(substr($st,105,8));
				$home_telephone = trim(substr($st,241,15));

				
// enroling on a course
$query = <<<HEREDOC
update users
	set dob = '$dob', ni = '$ni', gender = '$gender', ethnicity = '$ethnicity', home_street_description = '$home_street_description',
	home_locality = '$home_locality', home_town = '$home_town', home_county = '$home_county', home_postcode = '$home_postcode',
	home_telephone = '$home_telephone'
where uln = $uln
HEREDOC;
				$st = $link->query($query);
				if($st== false)
				{
					throw new Exception("Could not enrol on this course " . implode($link->errorInfo()));
				}
			}
	
		*/
		
			
		}
		fclose($handle);
		die(print_r($usernames));
	}
}
?>