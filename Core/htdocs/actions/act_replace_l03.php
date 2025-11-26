<?php
class replace_l03 implements IAction
{
	public function execute(PDO $link)
	{
	
		$handle = fopen("troy.csv","r");
		$st = fgets($handle);
		$sub = '';
		while(!feof($handle))
		{
			$st = fgets($handle);
			$arr = explode(",",$st);

			$assessor = $arr[117];
			$l03 = $arr[2];
			
			$username = DAO::getSingleValue($link, "select username from users where CONCAT(firstnames,' ',surname) = '$assessor'");
			
			if($username != '')
			{
				$sql = "update tr set assessor = '$username' where l03 = '$l03'";
				DAO::execute($link, $sql);
			}
				
		
		}
			
			
	
	
/*		$id = 1439;
		$l03= "QS50115494RH";
		$old= "000000000586";
		$sql = "update tr set l03 = '$l03' where id = '$id'";
		$st = $link->query($sql);

		$sql = "select * from ilr where tr_id = $id";
		$st = $link->query($sql);
		if($st) 
		{
			$ilrs = 0;
			$valid = 0;
			while($row = $st->fetch())
			{

				$ilr = $row['ilr'];
				$submission = $row['submission'];
				$contract_id = $row['contract_id'];	

				$ilr = str_replace("<L03>" . $old . "</L03>","<L03>" . $l03 . "</L03>",$ilr);
				$ilr = str_replace("<A03>" . $old . "</A03>","<A03>" . $l03 . "</A03>",$ilr);
				
				$sql = "update ilr set ilr = '$ilr' where tr_id = $id and submission='$submission' and contract_id = $contract_id";
				$st2 = $link->query($sql);
			}
		}
		
*/		
	
	/*	$sql = "SELECT * FROM ilr";
		$st = $link->query($sql);
		if($st) 
		{
			$ilrs = 0;
			$valid = 0;
			while($row = $st->fetch())
			{
				$xml = $row['ilr'];
				$pos = strpos($xml,"<main>");
				if($pos === false)
				{
					$ilrs += 1;
					$xml = str_replace("<subaims>Array</subaims>","<subaims>0</subaims>", $xml);
					$xml = str_replace("<subaim>","<main>", $xml);
					$xml = str_replace("</subaim>","</main>", $xml);
					
					$submission = $row['submission'];
					$tr_id = $row['tr_id'];
					$contract_id = $row['contract_id'];
					
					$sql = "update ilr set ilr = '$xml' where submission='$submission' and tr_id = $tr_id and contract_id = $contract_id";
					$st2 = $link->query($sql);
				
				}
			}
		}
	*/
	
	
	
	
	
	}
}
?>