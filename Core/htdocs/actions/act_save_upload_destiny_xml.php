<?php
class save_upload_destiny_xml implements IAction
{
	public function execute(PDO $link)
	{
		
		// User has uploaded a public key
		$filename = $_FILES['uploadedfile']['tmp_name'];
		
		// Get content
		$content = file_get_contents($filename);

		//$pageDom = new DomDocument();
		//$pageDom->loadXML($content);
		$pageDom = XML::loadXmlDom($content);
				
		$e = $pageDom->getElementsByTagName('Field');
		foreach($e as $node)
		{
			if($node->getElementsByTagName('Name')->item(0)->nodeValue=="L09")
				$surname = $node->getElementsByTagName('valStr')->item(0)->nodeValue;
				
			if($node->getElementsByTagName('Name')->item(0)->nodeValue=="L10")
				$firstname = $node->getElementsByTagName('valStr')->item(0)->nodeValue;

			if($node->getElementsByTagName('Name')->item(0)->nodeValue=="L13")
				$gender = $node->getElementsByTagName('valStr')->item(0)->nodeValue;

			if($node->getElementsByTagName('Name')->item(0)->nodeValue=="L11DD")
				$dob = $node->getElementsByTagName('valStr')->item(0)->nodeValue;
				
			if($node->getElementsByTagName('Name')->item(0)->nodeValue=="L11MM")
				$dob .= "/" . $node->getElementsByTagName('valStr')->item(0)->nodeValue;

			if($node->getElementsByTagName('Name')->item(0)->nodeValue=="L11YY")
				$dob .= "/" . $node->getElementsByTagName('valStr')->item(0)->nodeValue;
				
		}

//		$dob = Date::toMySQL($dob);	
		
		
		$user = new User();
		$user->username = $firstname.$surname;
		
		$user->password = "password";
		$user->record_status = 1;
		$user->type = 5;
//		$user->dob = $dob;
		$user->gender = "M";
		$user->surname = $surname;
		$user->firstnames = $firstname;
		$user->employer_id = 682;
		$user->employer_location_id = 106;
		$user->save($link, true);		
		
		http_redirect('do.php?_action=read_user&username=' . $firstname.$surname);		
		
	}
}
?>