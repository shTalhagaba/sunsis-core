<?php
class populate_learners_from_batch_file implements IAction
{
	public function execute(PDO $link)
	{

		$edrs = '';
		$handle = fopen("jlr","r");
		$st = fgets($handle);
		$ccc = 0;
		$aims = Array();
		$tr_id=0;
		$user = new User();
		$tr = new TrainingRecord();
		$usernames = array();
		$text = '';		
		while(!feof($handle))
		{

		
			$st = fgets($handle);
 
			// Create Learners
			
			if(trim(substr($st,20,2))=='10')
			{
			
			
				$user->firstnames = trim(substr($st,47,40));
				$user->surname = trim(substr($st,27,20));
				$surname = trim(substr($st,27,20));
				$usersurname = '';
				for($temp=0;$temp<=strlen($surname);$temp++)
				{
					$ch = ord(substr($surname,$temp,1));
					if(($ch>=65 && $ch<=97) || ($ch>=97 && $ch<=122))
					{
						$usersurname .= chr($ch);
					}
					else
					{
						$temp = strlen($surname)+1;
					}
				}
				
				if(in_array(strtolower(trim(substr($st,47,1)) . $usersurname), $usernames))
				{
					$usersurname = trim(substr($st,47,1)) . $usersurname;
				}

				
				$tempuname = trim(substr($st,47,1)) . $usersurname;
				$tempuname = strtolower($tempuname);
				$usernames[] = $tempuname;
				$user->username = $tempuname;

				$count = DAO::getSingleValue($link,"select count(*) from users where username='$user->username'");
				if($count>0)
				{
					do
					{
						$user->username = $user->username . "1";
						$count = DAO::getSingleValue($link,"select count(*) from users where username='$user->username'");
					}
					while($count>0);
				}
				
				$user->dob = trim(substr($st,87,2)) . "/" . substr($st,89,2) . "/" . substr($st,91,4);
				
				$user->password = "password";
				$user->record_status = 1;
				$user->ni = trim(substr($st,259,9));
				$user->gender = trim(substr($st,97,1));
				$user->ethnicity = trim(substr($st,95,2));
				$user->home_address_line_1 = trim(substr($st,113,30));
				$user->home_address_line_2 = trim(substr($st,141,30));
				$user->home_address_line_3 = trim(substr($st,171,30));
				$user->home_address_line_4 = trim(substr($st,201,30));
				$user->home_postcode = trim(substr($st,231,8));
				$user->home_telephone = trim(substr($st,239,15));
				$user->type = 5;


				$st = fgets($handle);

				$ed = trim(substr($st,155,8));
				
				$employer_id = DAO::getSingleValue($link,"select organisations.id from organisations inner join locations on locations.organisations_id = organisations.id where locations.postcode='$ed'");

				if($employer_id!='')
				{
		
					$location_id = DAO::getSingleValue($link,"select locations.id from organisations inner join locations on locations.organisations_id = organisations.id where locations.postcode='$ed'");

					$user->employer_id = $employer_id;
					$user->employer_location_id = $location_id;
					$user->save($link,true);
				}
				else
				{
					$aim = $ed;
					if(!in_array($aim,$aims))
						$aims[] = $aim;	
					
				}
				
				if($count=='')
				{				
					$edrs .= $ed . '->' . $user->firstnames . ' ' . $user->surname . '?';
					$emp = new Employer();
					$emp->legal_name = trim(substr($st,152,30));
					$emp->short_name = trim(substr($st,152,30));
					$emp->edrs = trim(substr($st,152,30));
					$emp->trading_name = trim(substr($st,152,30));
				//	$emp->save($link);
					
					$loc = new Location();
					$loc->short_name = trim(substr($st,152,30));
					$loc->full_name = trim(substr($st,152,30));
					$loc->organisations_id = $emp->id;
					$loc->postcode = trim(substr($st,182,8));
				//	$loc->save($link);
				}
				else
				{
					$emp = Employer::loadFromDatabase($link, $count);
					$loc_id = DAO::getSingleValue($link, "select id from locations where organisations_id=$emp->id");	
					$loc = Location::loadFromDatabase($link, $loc_id);
				}

				
	//			$text .= "\nEmployer: " . $emp->edrs;
	//			$text .= "Learner:" . $user->surname;
				
	//			$user->employer_id = $emp->id;
	//			$user->employer_location_id = $loc->id;
				//$user->save($link,true);
			}

		}
		fclose($handle);
		throw new Exception(implode($aims,","));
	}
}
?>