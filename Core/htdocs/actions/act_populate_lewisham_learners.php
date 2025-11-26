<?php
class populate_lewisham_learners implements IAction
{
	public function execute(PDO $link)
	{

		$handle = fopen("lewisham.csv","r");
		$st = fgets($handle);
		$count='';
		DAO::execute($link, "truncate lewisham_learners");
		while(!feof($handle))
		{
			$st = fgets($handle);
			$arr = explode(",",$st);

			$empid 			= $arr[0];
			$location_name 	= $arr[1];
			$l03 		 	= $arr[2];	
			$ni 			= $arr[3];
			$firstname 		= $arr[4];
			$surname 		= trim($arr[5]);
			$gender = trim($arr[6]);
			$dob = Date::toMySQL(trim($arr[7]));
			$job_role = trim($arr[8]);
			$diagnostic = trim($arr[9]);
			$numeracy = trim($arr[10]);
			$literacy = trim($arr[11]);
			$esol = trim($arr[12]);
			$enrolment = trim($arr[13]);
			$ethnicity = trim($arr[14]);
			$web_access = trim($arr[15]);
			$house_number = trim($arr[16]);
			$house_name = trim($arr[17]);
			$street_name = trim($arr[18]);
			$locality = trim($arr[19]);
			$town = trim($arr[20]);
			$county = trim($arr[21]);
			$postcode = trim($arr[22]);
			$phone = trim($arr[23]);
			$mobile = trim($arr[24]);
			$fax = trim($arr[25]);
			$email = trim($arr[26]);
			$l14 = trim($arr[27]);
			$l15 = trim($arr[28]);
			$l16 = trim($arr[29]);
			$l24 = trim($arr[30]);
			$l34a = trim($arr[31]);
			$l34b = trim($arr[32]);
			$l34c = trim($arr[33]);
			$l34d = trim($arr[34]);
			$l35 = trim($arr[35]);
			$l37 = trim($arr[36]);
			$l28a = trim($arr[37]);
			$l28b = trim($arr[38]);
			$l39 = trim($arr[39]);
			$l40a = trim($arr[40]);
			$l40b = trim($arr[41]);
			$l41a = trim($arr[42]);
			$l41b = trim($arr[43]);
			$l42a = trim($arr[44]);
			$l42b = trim($arr[45]);
			$l45 = trim($arr[46]);
			$l47 = trim($arr[47]);
			$l48 = trim($arr[48]);
			$course_id = trim($arr[49]);
			$group_id = trim($arr[50]);
			$start_date = Date::toMySQL(trim($arr[51]));
			$target_date = Date::toMySQL(trim($arr[52]));
			$contract_id = trim($arr[53]);
			$department = trim($arr[57]);
			
			$sql = "insert into lewisham_learners (empid, location_name, l03, ni, firstname, surname, gender, dob, job_role, diagnostic, numeracy, literacy, esol, enrolment, ethnicity, web_access, house_number, house_name, street_name, locality, town, county, postcode, phone, mobile, fax, email, l14, l15, l16, l24, l34a, l34b, l34c, l34d, l35, l37, l28a, l28b, l39, l40a, l40b, l41a, l41b, l42a, l42b, l45, l47, l48, course_id, group_id, start_date, target_date, contract_id, department) values('$empid', '$location_name', '$l03', '$ni', '$firstname', '$surname', '$gender','$dob','$job_role','$diagnostic','$numeracy','$literacy',
			'$esol','$enrolment','$ethnicity','$web_access','$house_number','$house_name','$street_name','$locality','$town','$county','$postcode','$phone','$mobile','$fax','$email','$l14',
			'$l15','$l16','$l24','$l34a','$l34b','$l34c','$l34d','$l35','$l37','$l28a','$l28b','$l39','$l40a','$l40b','$l41a','$l41b','$l42a','$l42b','$l45','$l47','$l48','$course_id',
			'$group_id','$start_date','$target_date','$contract_id','$department')";
			DAO::execute($link, $sql);
		}			
			fclose($handle);
	}
}
?>