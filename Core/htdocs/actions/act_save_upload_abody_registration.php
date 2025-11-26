<?php
class save_upload_abody_registration implements IAction
{
	public function execute(PDO $link)
	{
		
		// User has uploaded a public key
		$filename = $_FILES['uploadedfile']['tmp_name'];
		
		// Get content
		$content = file_get_contents($filename);

		$handle = fopen($filename,"r");
		$st = fgets($handle);
		
		while($st = fgets($handle))
		{
			// Extract values
			$arr = explode("+",$st);

			if($arr[1]=='REGACK')
			{
				$date = "'" . substr($arr[4],0,4) . "-" . substr($arr[4],4,2) . "-" . substr($arr[4],6,2) . "'" ;
				//throw new Exception($date);
			}
				
			$flag = $arr[0];
			if($flag=='ACK1')
			{
				$tr_id = $arr[1];
				$abrn = $arr[2];
				$firstname = $arr[3];
				$surname = $arr[4];
				
				DAO::execute($link, "update student_qualifications set awarding_body_date = $date, awarding_body_reg = '$abrn' WHERE LOCATE('EDEXCEL', awarding_body)>0 AND tr_id = '$tr_id';");
			}	
			
		}
		
		http_redirect($_SESSION['bc']->getPrevious());
	}
}
?>