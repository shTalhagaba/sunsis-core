<?php
class update_ilrs_from_csv implements IAction
{
	public function execute(PDO $link)
	{

		$edrs = '';
		$handle = fopen("imi.csv","r");
		$st = fgets($handle);
		
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

				$l03 = str_pad($arr[0],12,'0',STR_PAD_LEFT);
				$old = $arr[1];
				$new = substr($arr[2],0,2);
				
				$xml = DAO::getSingleValue($link, "select ilr from ilr where l03='$l03' and submission='W08'");

				//$pageDom = new DomDocument();
				//$pageDom->loadXML($xml);
				$pageDom = XML::loadXmlDom($xml);
				$e = $pageDom->getElementsByTagName('A14');
				$a = 1;
				$evidences = Array();
				$data='';
				foreach($e as $node)
				{
					$node->nodeValue = $new;
				}

				$ilr = $pageDom->saveXML();
				
				$ilr=substr($ilr,21);
				
				$sql = "update ilr set ilr = '$ilr' where submission='W08' and l03 = '$l03'";
			DAO::execute($link, $sql);
					
		}			
		fclose($handle);
	}
}
?>