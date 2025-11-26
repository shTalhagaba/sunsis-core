<?php
class save_lewisham_department implements IAction
{
	public function execute(PDO $link)
	{

		$filename = $_FILES['uploadedfile']['tmp_name'];
		$content = file_get_contents($filename);
		$handle = fopen($filename,"r");
		
		$st = fgets($handle);
		$count='';
		while(!feof($handle))
		{
			$st = fgets($handle);
			$arr = explode(",",$st);
			
			$l03 = '';
			$dept_code = '';

			$l03 			= substr($arr[0],0,8);
			$dept_code 		= substr(trim($arr[1]),0,2);

			if($dept_code == "SP")
			{	
				$provider_id = 2694;
				$provider_location_id = 3024;
			}
			elseif($dept_code == "CT")
			{
				$provider_id = 2692;
				$provider_location_id = 3022;
			}
			elseif($dept_code == "CP")
			{
				$provider_id = 2691;
				$provider_location_id = 3021;
			}
			elseif($dept_code == "WB")
			{
				$provider_id = 1361;
				$provider_location_id = 2340;
			}
			elseif($dept_code == "CI")
			{
				$provider_id = 2693;
				$provider_location_id = 3023;
			}
			else 
			{
				pre($dept_code);
			}


			DAO::execute($link, "update tr set provider_id = '$provider_id', provider_location_id = '$provider_location_id' where trim(l03) = '$l03'");
		
		}
						
			fclose($handle);
			pre("Complete");
	}
}
?>