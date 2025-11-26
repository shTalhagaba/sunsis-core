<?php
class cg_report implements IAction
{
	public function execute(PDO $link)
	{

		$count=0;
		echo "City and Guild report" . "<br/>";
		$handle = fopen("cg.csv","r");
		$st = fgets($handle);

		//echo "<table border=1><tr><td>L03</td><td><b>REGNUMBER</b></td><td>AIM</td><td>LEVEL</td><td>TYPE CODE</td><td>Title</td><td>NAME</td><td>EMPLOYER</td></tr>";
		echo "<table border=1><tr><b><td>L03</td><td><b>REGNUMBER</b></td><td>AIM</td><td>LEVEL</td><td>Title</td><td>NAME</td><td>EMPLOYER</td></b></tr>";
		while(!feof($handle))
		{
			$st = fgets($handle);
			$arr = explode(",",$st);
			//die(trim($arr[6]));
			if ( ! isset($arr[0]) ) continue;
			if ( ! isset($arr[1]) ) continue;
			if ( ! isset($arr[2]) ) continue;
			
			try {
			$l03=substr(trim($arr[0]),1,strlen(trim($arr[0]))-1);
			} catch ( Exception $e ) { echo  $arr[0] . " caused a problem" ;  }
			

			$regnumber=substr(trim($arr[1]),1,strlen(trim($arr[1]))-1);
			
			
			$aim=substr(trim($arr[2]),1,strlen(trim($arr[2]))-1);
			
			
			$learnername = DAO::getSingleValue($link, "select CONCAT(tr.surname,', ', tr.firstnames) as name from tr where l03 ='" . $l03 . "'");

			$empid = DAO::getSingleValue($link, "select employer_id  as emp_id from tr where l03 ='" . $l03 . "'");
			
			$empname = DAO::getSingleValue($link, "select legal_name  as anme from organisations where id ='" . $empid . "'");

			$awb = DAO::getSingleValue($link, "select CONCAT(lad200910.LEARNING_AIM.AWARDING_BODY_CODE,', ',lad200910.LEARNING_AIM.NOTIONAL_NVQ_LEVEl_CODE) as data from lad200910.LEARNING_AIM where LEARNING_AIM_REF ='" . $aim . "'");
			$typecode = DAO::getSingleValue($link, "select LEARNING_AIM_TYPE_CODE as data from lad200910.LEARNING_AIM where LEARNING_AIM_REF ='" . $aim . "'");
			$title = DAO::getSingleValue($link, "select LEARNING_AIM_TITLE as data from lad200910.LEARNING_AIM where LEARNING_AIM_REF ='" . $aim . "'");
			
			//$awb = DAO::getSingleValue($link, "select LEARNING_AIM.AWARDING_BODY_CODE as data from lad200910.LEARNING_AIM where LEARNING_AIM_REF ='" . $aim . "'");
			
			if ( (substr($awb,0,2) == 'CG') and ($learnername != "") )
			{
				//echo $l03 . "--" . $regnumber . "--" . 	$aim . "--" . $awb . "--".$learnername . "--". $empname ."<br/>";	
				//echo "<tr><td>$l03</td><td>$regnumber</td><td>$aim</td><td>$awb</td><td>$typecode</td><td>$title</td><td>$learnername</td><td>$empname</td></tr>";
				echo "<tr><td>$l03</td><td>$regnumber</td><td>$aim</td><td>$awb</td><td>$title</td><td>$learnername</td><td>$empname</td></tr>";
			}
			
			//echo "<table><tr>$l03<td>$regnumber</td><td>$aim</td><td>$awb</td><td>$learnername</td><td>$empname</td></tr>";
			
		}
		echo "</table>";
	}

}
		
		
/*		
		$edrs = '';
		$handle = fopen("ml.csv","r");
		$st = fgets($handle);
		
		while(!feof($handle))
		{

			$st = fgets($handle);
			// Extract values
			$arr = explode(",",$st);

			$dob = trim($arr[3]);	
			try
			{
				$dob = Date::toMySQL($dob);
			}
			catch(Exception $e)
			{
				throw new Exception($dob);
			}
			
			$mn = $arr[0];
			$mn = str_pad($mn, 5, "0", STR_PAD_LEFT);  
			
			$st = $link->query("update users set enrollment_no = '$mn' where dob = '$dob'");

			//throw new Exception("update users set enrollment_no = $mn where dob = $dob");
			
		}
	}
}		
*/		
		
/*		
		$trs = 0;
		$sql = "SELECT * FROM ilr where contract_id = 11";
		$st = $link->query($sql);
		if($st) 
		{
			while($row = $st->fetch())
			{
				$xml = $row['ilr'];
				$submission = $row['submission'];
				$tr_id = $row['tr_id'];
				$contract_id = $row['contract_id'];

				$trs += 1;
				
				$pageDom = new DomDocument();
				$pageDom->loadXML($xml);
				$e = $pageDom->getElementsByTagName('A51a');
				$a = 1;
				$evidences = Array();
				$data='';
				foreach($e as $node)
				{
					$node->nodeValue = "100";
				}
		
				$ilr = $pageDom->saveXML();
				
				$ilr=substr($ilr,21);
				
				$sql2 = "update ilr set ilr = '$ilr' where submission='$submission' and tr_id = '$tr_id' and contract_id = '$contract_id'";
				$st2 = $link->query($sql2);			
				if(!$st2)
					throw new Exception("Error");	
			}
			
			throw new Exception($trs);
		}		
	}
}		
		
*/		
		
		
/*		
 		$sql = "SELECT * FROM tr";
		$st = $link->query($sql);
		if($st) 
		{
			$ilrs = 0;
			$valid = 0;
			while($row = $st->fetch())
			{
				$tr_id = $row['id'];
				
				$ilr = DAO::getSingleValue($link, "select ilr from ilr where tr_id = $tr_id and submission = (select max(submission) from ilr where tr_id = $tr_id)");

				$vo = Ilr2008::loadFromXML($ilr);
				
				$disability = $vo->learnerinformation->L15;
				$learning_difficulty = $vo->learnerinformation->L16;
				
				$sql = "update tr set disability = '$disability', learning_difficulty = '$learning_difficulty' where id = $tr_id";
				$st2 = $link->query($sql);
			}
		}
	}
}
*/	
	
	
/*		$sql = "SELECT * FROM ilr";
		$st = $link->query($sql);
		if($st) 
		{
			while($row = $st->fetch())
			{
				$submission = $row['submission'];
				$tr_id = $row['tr_id'];
				$contract_id = $row['contract_id'];
				$l03 = $row['L03'];
				$ilr = $row['ilr'];
				
				//$ilrxml = new SimpleXMLElement($ilr);
				$ilrxml = XML::loadSimpleXML($ilr);
				foreach ($ilrxml->main as $item) 
				{
				
					$a01 = ($item->A01=='')?0:$item->A01;
					$a02 = ($item->A02=='')?0:$item->A02;
					$a03 = $item->A03;
					$a04 = ($item->A04=='')?0:$item->A04;
					$a05 = ($item->A05=='')?0:$item->A05;
					$a06 = ($item->A06=='')?0:$item->A06;
					$a07 = ($item->A07=='')?0:$item->A07;
					$a08 = ($item->A08=='')?0:$item->A08;
					$a09 = $item->A09;
					$a10 = ($item->A10=='')?0:$item->A10;
					$a11a = ($item->A11a=='')?0:$item->A11a;
					$a11b = ($item->A11b=='')?0:$item->A11b;
					$a12 = 0;
					$a13 = ($item->A13=='')?0:$item->A13;
					$a14 = ($item->A14=='')?0:$item->A14;
					$a15 = ($item->A15=='')?0:$item->A15;
					$a16 = ($item->A16=='')?0:$item->A16;
					$a17 = ($item->A17=='')?0:$item->A17;
					$a18 = ($item->A18=='')?0:$item->A18;
					$a19 = ($item->A19=='')?0:$item->A19;
					$a20 = ($item->A20=='')?0:$item->A20;
					$a21 = ($item->A21=='')?0:$item->A21;
					$a22 = $item->A22;
					$a23 = $item->A23;
					$a24 = ($item->A24=='')?0:$item->A24;
					$a26 = ($item->A26=='')?0:$item->A26;
					$a27 = $item->A27;
					$a28 = $item->A28;
					$a31 = ($item->A31=='')?'null':"'" . $item->A31 . "'";
					$a32 = ($item->A32=='')?0:$item->A32;
					$a33 = $item->A33;
					$a34 = ($item->A34=='')?0:$item->A34;
					$a35 = ($item->A35=='')?0:$item->A35;
					$a36 = $item->A36;
					$a37 = ($item->A37=='')?0:$item->A37;
					$a38 = ($item->A38=='')?0:$item->A38;
					$a39 = ($item->A39=='')?0:$item->A39;
					$a40 = ($item->A40=='')?'null':"'" . $item->A40 . "'";
					$a43 = ($item->A43=='')?0:$item->A43;
					$a44 = $item->A44;
					$a45 = $item->A45;
					$a46a = ($item->A46a=='')?0:$item->A46a;
					$a46b = ($item->A46b=='')?0:$item->A46b;
					$a47a = ($item->A47a=='')?0:$item->A47a;
					$a47b = ($item->A47b=='')?0:$item->A47b;
					$a48a = $item->A48a;
					$a48b = $item->A48b;
					$a49 = $item->A49;
					$a50 = ($item->A50=='')?0:$item->A50;
					$a51a = ($item->A51a=='')?0:$item->A51a;
					$a52 = ($item->A52=='')?0:$item->A52;
					$a53 = ($item->A53=='')?0:$item->A53;
					$a54 = $item->A54;
					$a55 = ($item->A55=='')?0:$item->A55;
					$a56 = ($item->A56=='')?0:$item->A56;
					$a57 = ($item->A57=='')?0:$item->A57;
					$a58 = ($item->A58=='')?0:$item->A58;
					$a59 = ($item->A59=='')?0:$item->A59;
					$a60 = ($item->A60=='')?0:$item->A60;
					$a61 = $item->A61;
					$a62 = ($item->A62=='')?0:$item->A62;
					$a63 = ($item->A63=='')?0:$item->A63;
					$a64 = ($item->A64=='')?0:$item->A64;
					$a65 = ($item->A65=='')?0:$item->A65;
					$a66 = ($item->A66=='')?0:$item->A66;
					$a67 = ($item->A67=='')?0:$item->A67;
					$a68 = ($item->A68=='')?0:$item->A68;
					
			$sql2 = <<<HEREDOC
insert into learning_aims
values ($contract_id, $tr_id, $a01, $a02, '$a03', $a04, $a05, 0, $a07, $a08, '$a09', $a10, $a11a, $a11b, 
$a12, $a13, $a14, $a15, $a16, $a17, $a18, $a19, $a20, $a21, '$a22', '$a23', $a24, $a26, '$a27', '$a28', 
$a31, $a32, '$a33', $a34, $a35, '$a36', $a37, $a38, $a39, $a40, $a43, '$a44', '$a45', $a46a, $a46b, 
$a47a, $a47b, '$a48a', '$a48b', '$a49', '$a50', '$a51a', $a52, $a53, '$a54', $a55, $a56, $a57, $a58, $a59, 
$a60, '$a61', $a62, $a63, $a64, $a65, $a66, $a67, $a68);
HEREDOC;
	
					$st2 = $link->query($sql2);
					if(!$st2)
						//throw new Exception($sql2);
						throw new Exception(implode($link->errorInfo()));
					
				}
			}
		}
	}
}
	
	
	
	
/*
		$sql = "SELECT * FROM tr";
		$st = $link->query($sql);
		if($st) 
		{
			$ilrs = 0;
			$valid = 0;
			while($row = $st->fetch())
			{
				$tr_id = $row['id'];
				
				$ilr = DAO::getSingleValue($link, "select ilr from ilr where tr_id = $tr_id and submission = (select max(submission) from ilr where tr_id = $tr_id)");

				$vo = Ilr2008::loadFromXML($ilr);
				
				$status = $vo->aims[0]->A34;

				$sql = "update tr set status_code = '$status' where id = $tr_id";
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
	
	
	
?>
