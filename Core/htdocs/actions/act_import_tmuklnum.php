<?php
class import_tmuklnum implements IAction
{
	public function execute(PDO $link)
	{

		$count=0;
		echo "Import TMUK Learner Numbers" . "<br/>";
		$handle = fopen("tmuklnu.csv","r");
		$st = fgets($handle);
		echo $st . "<br/>";

		while(!feof($handle))
		{
			$st = fgets($handle);
			$arr = explode(",",$st);
			if ( ! isset($arr[5]) ) continue;
			if ( substr(trim($arr[5]),0,3) == 'Not' ) continue;
			if ( preg_match('/\'/', $arr[1]) ) continue;
			$dob = substr($arr[5],6,4) . substr($arr[5],3,2) . substr($arr[5],0,2);
			
			$enroln = DAO::getSingleValue($link, "select enrollment_no from users where dob ='". $dob . "'". " and surname='" . $arr[1] . "'");

			
			
			$pad = 5 - strlen($arr[0]);
			$pstr="";
			while ($pad <> 0 )
			{
				$pad = $pad -1;
				$pstr = $pstr . "0";
			}
			$arr[0] = $pstr . $arr[0];
			//echo $arr[0] . "<br/>";
			
			//if ( (string)$enroln <> (string)$arr[0] )
			//{
				echo "<span style='color: red'>Updating $arr[1] - $arr[5] - $enroln ($arr[0])" . "</span><br/>";
				$sql = "update users set enrollment_no ='$arr[0]' where surname = '$arr[1]' and dob = '$dob'";
				echo $sql . "<br/>";
				$st5 = $link->query($sql);
				
			//}
			//else
			//{
			
			//	echo "Info $arr[1] - $arr[5] - $enroln ($arr[0])" . "<br/>";
				
			
			//}
		}
			echo "<br/><br/><br/>" ."Count =" . $count;
	}

}
	
?>