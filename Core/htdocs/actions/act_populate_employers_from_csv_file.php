<?php
class populate_employers_from_csv_file implements IAction
{
	public function execute(PDO $link)
	{

		$edrs = '';
		$handle = fopen("skillspoint.csv","r");
		$st = fgets($handle);
		$count='';
		
		while(!feof($handle))
		{
			$st = fgets($handle);
			$arr = explode(",",$st);

			$employer_id = '';
			$legal_name = $arr[0];
			$edrs = $arr[1];
			$contact_name = $arr[5];
			$telephone = trim($arr[6]);
			$mobile = $arr[7];
			$email = $arr[8];
			$location_title = $arr[10];
			$building_name = $arr[11];
			$street = $arr[12] . ' ' .$arr[13];
			$locality = $arr[14];
			$town = trim($arr[15]);
			$county = trim($arr[16]);
			$postcode = trim($arr[17]);
			$emp_telephone = trim($arr[18]);
						
		
			// If Exists				
			$id = DAO::getSingleValue($link,"select id from organisations where legal_name = '$legal_name'");
			
			if($id=='')
			{				
				$emp = new Employer();
				$emp->id = DAO::getSingleValue($link, "select max(id)+1 from organisations");
				//$emp->employer_code = $arr[0];
				$emp->legal_name = $legal_name;
				$emp->trading_name = $legal_name;
				$emp->organisation_type = 2;
				//$fsm = $sector;
				//$emp->edrs = $arr[13];
				$emp->active=1;
				$emp->save($link);
			}
			else
			{
				$emp = Organisation::loadFromDatabase($link, $id);
			}
				
				$loc = new Location();
				$loc->organisations_id = $emp->id;
				if($id!='')
					$loc->is_legal_address = 0;
				else 
					$loc->is_legal_address = 1;
				
				$loc->full_name = $location_title;
				$loc->address_line_1 = $street;
				$loc->address_line_2 = ($town=='')?'':$town;
				$loc->address_line_3 = ($county=='')?'':$county;
				$loc->postcode = ($postcode=='')?'':$postcode;
				$loc->telephone = $telephone;
				$loc->contact_name = $contact_name;
				$loc->save($link);
		}			
			fclose($handle);
	}
}
?>