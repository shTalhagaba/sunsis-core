<?php
class populate_learners_from_csv_file implements IAction
{
	public function execute(PDO $link)
	{

		$handle = fopen("nordic.csv","r");
		$st = fgets($handle);
		$user = new User();
		
		while(!feof($handle))
		{
			$st = fgets($handle);

			// Create Learners
			$user = new User();
			$arr = explode(",",$st);

			if($arr[0]=='END')
				throw new Exception($gc);
			
			$firstname = trim($arr[2]);
			$surname = trim($arr[1]);
			$enrollment_no = substr($arr[0],10,6);
//			$home_street_description = trim($arr[7]);
//			$home_locality = trim($arr[8]);
//			$home_town = trim($arr[9]);
//			$home_county = trim($arr[10]);
//			$home_postcode = trim($arr[12]);
//			$telephone = trim($arr[16]);
			$ni = trim($arr[5]);
//			$dob = trim($arr[18]);
//			$gender = (trim($arr[22])=="M")?"M":"F";
//			$ethnicity = trim($arr[23]);
//			$uln = trim($arr[38]);
			$emp_name = trim($arr[7]);
			$emp_id = DAO::getSingleValue($link, "select id from organisations where legal_name = '$emp_name'");

			if($emp_id =='')
				$emp_id = 11888;	
			
			$web_access = 1;
			
			$user->username = $firstname.$surname;

			//pre($arr[0]);
//			$emp_code = trim($arr[14]);
//			$emp_id = DAO::getSingleValue($link, "select id from organisations where legal_name = '$emp_code'");
//			if($emp_id == '')
//				throw new Exception($emp_code);
				
			$loc_id = DAO::getSingleValue($link, "select id from locations where organisations_id = '$emp_id'");

			$user->surname = $surname;
			$user->firstnames = $firstname;
			$user->enrollment_no = $enrollment_no;
			$user->password = "password";
//			$user->home_postcode = trim($home_postcode);
//			$user->home_street_description = $home_street_description;
//			$user->home_locality = $home_locality;
//			$user->home_town = $home_town;
//			$user->home_county = $home_county;
//			$user->telephone = $telephone;
//			$user->mobile = $mobile;
//			$user->email = $email;
//			$user->home_saon_start_number = $home_house_number;
			$user->ni = $ni;
			$user->type = 5;
			$user->employer_id = $emp_id;
			$user->employer_location_id = $loc_id;
//			$user->l24 = $arr[41];
//			$user->l15 = substr($arr[44],0,2);
//			$user->l16 = substr($arr[45],0,2);
//			$user->l14 = $arr[38];
//			$user->l35 = substr($arr[46],0,2);
//			$user->uln = $uln;
			
			
//			if($dob!='')
//				$user->dob = Date::toMySQL($dob);
			
//			if($ethnicity=='')	
//				$user->ethnicity = "23";
//			else
//				$user->ethnicity = $ethnicity;
			
//			if($gender=='')
				$user->gender = "U";
//			else
//				$user->gender = strtoupper($gender);

//			$uln = DAO::getSingleValue($link,"select count(*) from users where uln='$user->uln'");

//			if($uln==0)
				$user->save($link, true);
					
		}			
		fclose($handle); 
	}
}
?>