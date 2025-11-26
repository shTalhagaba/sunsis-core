<?php
class save_lewisham_employers implements IAction
{
	public function execute(PDO $link)
	{

		$filename = $_FILES['uploadedfile']['tmp_name'];
		$content = file_get_contents($filename);
		$handle = fopen($filename,"r");

		$count='';
		while(!feof($handle))
		{
			$st = fgets($handle);
			$arr = explode(",",$st);

			if(sizeof($arr)<9)
				pre($arr);

			// Lewisham CSV Starts
/*						$brand 			= $arr[0];
						$emp_id 		= $arr[0];
						$legal_name 	= addslashes((string)$arr[2]);
						$edrs 			= trim($arr[3]);
						$sector 		= (trim($arr[4])>0)?trim($arr[4]):0;
						$account_manager = trim($arr[5]);
						$contact_name = addslashes(trim($arr[6]));
						$telephone = substr(trim($arr[7]),0,15);
						$mobile = trim($arr[8]);
						$email = trim($arr[9]);
						$main = 1;//trim($arr[10]);
						$location_name = trim($arr[11]);
						$bname = '';//addslashes(trim($arr[12]));
						$bnumber = '';//trim($arr[13]);
						$street = addslashes(trim($arr[14]));
						$locality = trim($arr[15]);
						$town = trim($arr[16]);
						$county = trim($arr[17]);
						$postcode = substr(trim($arr[18]),0,8);
						$phone = trim($arr[19]);
						$fax = trim($arr[20]);
						$size = (trim($arr[21])>0)?trim($arr[21]):0;
*/
			// Midkent CSV Ends

/*			// Pera CSV Starts
			$legal_name 	= addslashes((string)$arr[0]);
			$edrs 			= trim($arr[9]);
			$sector 		= 1;
			$account_manager = "";
			$contact_name = addslashes(trim($arr[6]));
			$telephone = substr(trim($arr[7]),0,15);
			$mobile = "";
			$email = trim($arr[8]);
			$location_name = "Main Site";
			$street = addslashes(trim($arr[1]));
			$locality = trim($arr[2]);
			$town = trim($arr[3]);
			$county = trim($arr[4]);
			$postcode = substr(trim($arr[5]),0,8);
			$phone = trim($arr[7]);
			$fax = "";
			$size = 1;
*/
			// Pera CSV Ends

			// Midkent CSV Starts
			$emp_id 		= $arr[0];
			$legal_name 	= addslashes((string)$arr[1]);
			$edrs 			= trim($arr[2]);
			$sector 		= (trim($arr[3])>0)?trim($arr[3]):0;
			$account_manager = trim($arr[5]);
			$contact_name = addslashes(trim($arr[14])) . ' ' . addslashes(trim($arr[15])) . ' ' . addslashes(trim($arr[16])) . ' ' . addslashes(trim($arr[17]));
			$telephone = substr(trim($arr[4]),0,15);
			$mobile = trim($arr[4]);
			$email = trim($arr[6]);
			$main = 1;//trim($arr[10]);
			$location_name = "Main Site";
			$bname = '';//addslashes(trim($arr[12]));
			$bnumber = '';//trim($arr[13]);
			$street = addslashes(trim($arr[7])) . ' ' . addslashes(trim($arr[8]));
			$locality = trim($arr[9]);
			$town = trim($arr[10]);
			$county = trim($arr[11]);
			$postcode = substr(trim($arr[12]),0,8);
			$phone = trim($arr[4]);
			$fax = trim($arr[5]);
			//$size = (trim($arr[21])>0)?trim($arr[21]):0;
			$size = 0;
			// Midkent CSV Ends
			
			$employer_id = DAO::getSingleValue($link, "select id from organisations where trim(edrs) = '$edrs'");
			if($edrs!="")
			{
				if($employer_id=='')
				{
					$emp = new Employer();
					$emp->id = DAO::getSingleValue($link, "select max(id)+1 from organisations");
					$emp->legal_name = $legal_name;
					$emp->trading_name = $legal_name;
					$emp->sector = $sector;
					$emp->edrs = $edrs;	
					$creator = DAO::getSingleValue($link, "select username from users where concat(firstnames, ' ',surname) = '$account_manager'");
					$emp->creator = $creator;
					$emp->organisation_type = 2;
					$emp->active=1;
					$emp->size = $size;
				//	$emp->save($link);
	
					$loc = new Location();
					$loc->organisations_id = $emp->id;
					$loc->is_legal_address = 1;
					$loc->contact_name = $contact_name;
					$loc->contact_telephone = $telephone;
					$loc->contact_mobile = $mobile;
					$loc->contact_email = $email;
					$loc->full_name = $location_name;
					$loc->address_line_1 = $street;
					$loc->address_line_2 = ($locality=='')?'':$locality;
					$loc->address_line_3 = ($town=='')?'':$town;
					$loc->address_line_4 = ($county=='')?'':$county;
					$loc->postcode = ($postcode=='')?'':$postcode;
					$loc->telephone = $phone;
					$loc->fax = $fax;
				//	$loc->save($link);
				}
				else
				{
					$emp = Employer::loadFromDatabase($link, $employer_id);
					$emp->legal_name = $legal_name;
					$emp->trading_name = $legal_name;
					$emp->sector = $sector;
					$creator = DAO::getSingleValue($link, "select username from users where concat(firstnames, ' ',surname) = '$account_manager'");
					$emp->creator = $creator;
					$emp->organisation_type = 2;
					$emp->size = (int)$size;
					$emp->save($link);

					$loc_id = DAO::getSingleValue($link, "select id from locations where organisations_id = '$employer_id'");
					$loc = Location::loadFromDatabase($link, $loc_id);
					$loc->organisations_id = $emp->id;
					$loc->is_legal_address = 1;
					$loc->contact_name = $contact_name;
					$loc->contact_telephone = $telephone;
					$loc->contact_mobile = $mobile;
					$loc->contact_email = $email;
					$loc->full_name = $location_name;
					$loc->address_line_1 = $street;
					$loc->address_line_2 = ($locality=='')?'':$locality;
					$loc->address_line_3 = ($town=='')?'':$town;
					$loc->address_line_4 = ($county=='')?'':$county;
					$loc->postcode = ($postcode=='')?'':$postcode;
					$loc->telephone = $phone;
					$loc->fax = $fax;
					$loc->save($link);
				}	
			}
			
				//if($edrs=="163758840")
				//pre("reached");
			
		}
			fclose($handle);
			pre("Complete");
	}
}
?>