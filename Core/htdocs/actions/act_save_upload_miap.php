<?php
class save_upload_miap implements IAction
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
			$arr = explode(",",$st);

			$uln = @str_replace('"','',$arr[4]);
			$tr_id = @str_replace('"','',$arr[5]);

			$sql = "SELECT * FROM ilr  WHERE tr_id = '$tr_id'";
			$st2 = $link->query($sql);
			if($st2)
			{
				while($row = $st2->fetch())
				{	
					$ilr = $row['ilr'];
					$tr_id = $row['tr_id'];
					$L03 = $row['L03'];
					$contract_id = $row['contract_id'];
					$submission = $row['submission'];
					$contract_year = DAO::getSingleValue($link, "select contract_year from contracts where id = '$contract_id'");

					//$pageDom = new DomDocument();
					//$pageDom->loadXML($ilr);
					if($contract_year<=2011)
					{
						$pageDom = XML::loadXmlDom($ilr);
						// learner level
						$e = $pageDom->getElementsByTagName('learner');
						$count = 0;
						foreach($e as $node)
						{
							$node->getElementsByTagName('L45')->item(0)->nodeValue = $uln;
						}

						// Programme aim
						$e = $pageDom->getElementsByTagName('programmeaim');
						$count = 0;
						foreach($e as $node)
						{
							$node->getElementsByTagName('A55')->item(0)->nodeValue = $uln;
						}

						// Main Aim
						$e = $pageDom->getElementsByTagName('main');
						$count = 0;
						foreach($e as $node)
						{
							$node->getElementsByTagName('A55')->item(0)->nodeValue = $uln;
						}

						// Subsidiary Aim
						$e = $pageDom->getElementsByTagName('subaim');
						$count = 0;
						foreach($e as $node)
						{
							$node->getElementsByTagName('A55')->item(0)->nodeValue = $uln;
						}
					}
					else
					{
						$pageDom = XML::loadXmlDom($ilr);
						$e = $pageDom->getElementsByTagName('Learner');
						$count = 0;
						foreach($e as $node)
						{
							$node->getElementsByTagName('ULN')->item(0)->nodeValue = $uln;
						}
					}

					$ilr = $pageDom->saveXML();
			
					$ilr=substr($ilr,21);
					$ilr = str_replace("'", "&apos;" , $ilr);

					DAO::execute($link, "update ilr set ilr = '$ilr' where contract_id ='$contract_id' and tr_id = '$tr_id' and submission = '$submission' and l03 = '$L03'");
                    DAO::execute($link, "update tr set uln = '$uln' where tr.id = '$tr_id'");
                    DAO::execute($link, "update users set l45 = '$uln' where username in (select username from tr where id = '$tr_id')");
				}
			}
		}
		
		http_redirect($_SESSION['bc']->getPrevious());
	}
	
	
	public function delete_directory($dirname) 
	{
		if (is_dir($dirname))
		$dir_handle = opendir($dirname);
		if (!$dir_handle)
			return false;
		while($file = readdir($dir_handle)) 
		{
			if ($file != "." && $file != "..") 
			{
				if (!is_dir($dirname."/".$file))
					unlink($dirname."/".$file);
				else
					$this->delete_directory($dirname.'/'.$file);
			}
		}
		closedir($dir_handle);
		rmdir($dirname);
		return true;
	} 
	
	
	private function readPublicKeyFile()
	{
		if(ini_get("safe_mode") != '')
		{
			throw new Exception("PHP Safe mode is on -- VoLT needs safe mode off. Check setting in php.ini");
		}
		
		if($_FILES['keyfile']['tmp_name'] != '')
		{
			// User has uploaded a public key
			$filename = $_FILES['keyfile']['tmp_name'];
			
			// Get content
			$content = file_get_contents($filename);
		
			// Erase temporary file
			unlink($filename);
			
			return new PublicKey($content);
		}
		else
		{
			return null;	
		}
	}
	
 
}
?>