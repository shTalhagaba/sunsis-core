<?php
class populate_raytheonlrns implements IAction
{
	public function execute(PDO $link)
	{

		echo "Start" . "<br/>";
		$gc = '';
		$handle = fopen("raytheonlrns.csv","r");
		$st = fgets($handle);			// Read in header info
		
		$tr_id=0;
		$user = new User();
		$tr = new TrainingRecord();
		$usernames = array();
		$text = '';		
		while(!feof($handle))
		{

			$st = fgets($handle);
	
			
			// Create Learners
			$user = new User();
			$arr = explode(",",$st);

			if ( ! isset($arr[3]) ) continue;
			
			$l03 = $arr[3];
			$surname = $arr[4];
			$firstname = $arr[5];
			$dob = substr($arr[6],0,10);
			//$ethnicity = $arr[12];
			$gender = $arr[7];
			$postcode = $arr[15];
			$number = $arr[9];
			$home_street_description = $arr[11];
			$home_locality = $arr[12];
			$home_town = $arr[13];
			$home_county = $arr[14];
			$telephone = $arr[16];
			
	//		$location_title = $arr[16];
			$employer_title = $arr[0];				

	
			$usersurname = '';
			for($temp=0;$temp<=strlen($arr[2]);$temp++)
			{
				$ch = ord(substr($arr[2],$temp,1));
				if(($ch>=65 && $ch<=97) || ($ch>=97 && $ch<=122))
				{
					$usersurname .= chr($ch);
				}
				else
				{
					$temp = strlen($arr[2])+1;
				}
			}
			
			if(in_array(strtolower(trim(substr($st,49,1)) . $usersurname), $usernames))
			{
				$usersurname = trim(substr($st,49,1)) . $usersurname;
			}

			
			$tempuname = trim(substr($arr[3],0,1)) . $usersurname;
			$tempuname = strtolower($tempuname);
			$usernames[] = $tempuname;
			$user->username = $tempuname;

			//$user->username = $learner_number;
			
			// If Exists				
			$count = DAO::getSingleValue($link,"select count(*) from users where username='$user->username'");
			if($count>0)
			{
				do
				{
					$user->username = $user->username . rand(1,100);
					$count = DAO::getSingleValue($link,"select count(*) from users where username='$user->username'");
				}
				while($count>0);
			}
			
			
			$employer_title = trim($employer_title);
//			$location_title = trim($location_title);

//			$location_title = $arr[10];
//			$loc_id = DAO::getSingleValue($link,"select id from locations where full_name = '$location_title'");
//			$emp_id = DAO::getSingleValue($link,"select organisations_id from locations where id='$loc_id'");
			
			// relmes - change for php 5.3
			// http://www.php.net/manual/en/function.mysql-real-escape-string.php
			$sql = "select id from organisations where legal_name='". mysql_real_escape_string($employer_title) . "'";

			$emp_id = DAO::getSingleValue($link, $sql);
//			$loc_id = DAO::getSingleValue($link,"select id from locations where organisations_id='$emp_id' and full_name = '$location_title'");


			
			$sql = "select * from locations where organisations_id='$emp_id' limit 1";
			$query = $link->query($sql);
			$result = $query->setFetchMode(PDO::FETCH_ASSOC);
			$row = $query->fetch();	
			
			
			$loc_id = $row['id'];	//DAO::getSingleValue($link,"select id from locations where organisations_id='$emp_id' limit 1");
			
			if($emp_id=='')
				$gc .= ',' . $location_title;
//			elseif($loc_id=='')
//				throw new Exception($location_title);


			
			
			$user->password = "password";
			$user->uln = $l03;
			$user->type = 5; // Learner
			$user->surname = $surname;
			$user->firstnames = $firstname;
			$user->employer_id = $emp_id;
			$user->employer_location_id = $loc_id;
			$user->dob = Date::toMySQL($dob);
			$user->ethnicity = 23;		// White British
			$user->gender = substr($gender,0,1);
			$user->home_postcode = trim($postcode);
			$user->home_paon_start_number = $number;
			$user->home_street_description = $home_street_description;
			$user->home_locality = $home_locality;
			$user->home_town = $home_town;
			$user->home_county = $home_county;
			$user->home_telephone = $telephone;
			
			$user->work_postcode = $row['postcode'];
			$user->work_paon_start_number = $row['paon_start_number'];;
			$user->work_street_description = $row['street_description'];
			$user->work_locality = $row['locality'];
			$user->work_town = $row['town'];
			$user->work_county = $row['county'];
			$user->work_telephone = $row['telephone'];
			
			$uln = DAO::getSingleValue($link,"select count(*) from users where uln='$user->uln'");

			$company_town = $row['town'];
			
			//if($uln==0)
			//{
				echo "Adding User = $user->surname, $user->firstnames , Employer = $employer_title ,Town = $company_town , Location id = $user->employer_location_id , Home tel = $telephone" . "<br/>";
				$user->save($link, true);
			//}
					
		}			
		
		fclose($handle); 
		
		//throw new Exception($gc);
	}
}
?>