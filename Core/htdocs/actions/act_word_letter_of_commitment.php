<?php
class word_letter_of_commitment implements IAction
{
	public function execute(PDO $link)	{

		$tr_id = isset($_GET['tr_id']) ? $_GET['tr_id'] : '';
		$vo = TrainingRecord::loadFromDatabase($link, $tr_id);
		
		$username = $_SESSION['user']->username;
		if( !( file_exists(DATA_ROOT."/uploads/".DB_NAME) ) ) {
			mkdir(DATA_ROOT."/uploads/".DB_NAME);
		}
		
		$target_path = DATA_ROOT."/uploads/".DB_NAME;
		
		// Check if correct file type has been selected to upload i.e. gif, jpg, or png		
		if( !(file_exists(DATA_ROOT."/uploads/".DB_NAME."/".$username)) ) {
			mkdir($target_path."/".$username);
		}
//		else
//		{
//			pre("../uploads/".DB_NAME."/".$username);
//			$this->delete_directory("../uploads/".DB_NAME."/".$username);
//			mkdir($target_path.$username);
//		}
				
		$num = '';
		$lit = '';
		
		$sql = <<<HEREDOC
			select * from users where username = "$vo->username";
HEREDOC;

		$st = $link->query($sql);
		if($st) {
			while( $row = $st->fetch() ) {
				$num = $row['numeracy'];
				$lit = $row['literacy'];
			}
		}

		$numeracy = '';
		if( $num > 0 ) {
			$numeracy = DAO::getSingleValue($link, 'select description from lookup_pre_assessment where id = ' . $num);
		}
		
		$literacy = '';
		if( $lit > 0 ) {
			$literacy = DAO::getSingleValue($link, 'select description from lookup_pre_assessment where id = ' . $lit);
		}

		// Decompress word file in user directory		
		$zip = new ZipArchive();   
		// open archive 
		if ($zip->open('loc.docx') !== TRUE) {
		    throw new Exception("Could not open archive");
		}
		// extract contents to destination directory
		$zip->extractTo($target_path."/".$username);
		$zip->close();    

		// read file 
		$document = file_get_contents($target_path."/".$username."/word/document.xml");

		// Populate values
		$fp = fopen($target_path."/".$username."/word/document.xml", "w") or die("Couldn't create new file");

		$document = str_replace("xlx", $literacy, $document);
		$document = str_replace("xnx", $numeracy, $document);
		
		$document = str_replace("Given_name",$vo->firstnames, $document);
		
		$document = str_replace("Given_name",$vo->firstnames, $document);
		$document = str_replace("Family_name",$vo->surname, $document);
		$document = str_replace("Address_1", $vo->home_address_line_1, $document);
		$document = str_replace("Address_2", $vo->home_address_line_2, $document);
		$document = str_replace("Given_borough", "", $document);
		$document = str_replace("Given_city", $vo->home_address_line_3, $document);
		$document = str_replace("Postal_code", $vo->home_postcode, $document);
		$document = str_replace("Date_of_birth", $vo->dob, $document);
		$document = str_replace("Given_gender", $vo->gender, $document);
		$document = str_replace("Home_number", $vo->home_telephone, $document);
		$document = str_replace("Work_number", $vo->work_telephone, $document);
		$document = str_replace("Mobile_number", $vo->home_mobile, $document);		
		$document = str_replace("Email_address", $vo->home_email, $document);
		
		$document = str_replace("Pc_company", "", $document);
		$document = str_replace("Pc_address_1", $vo->work_address_line_1, $document);
		$document = str_replace("Pc_address_2", $vo->work_address_line_2, $document);
		$document = str_replace("Pc_borough", "", $document);
		$document = str_replace("Pc_city", $vo->work_address_line_3, $document);
		$document = str_replace("Pc_postal_code", $vo->work_postcode, $document);
		$document = str_replace("Pc_representative", "", $document);
		$document = str_replace("Pc_position", "", $document);

		$document = str_replace("Tr_company", "", $document);
		$document = str_replace("Tr_address_1", $vo->provider_address_line_1, $document);
		$document = str_replace("Tr_address_2", $vo->provider_address_line_2, $document);
		$document = str_replace("Tr_borough", "", $document);
		$document = str_replace("Tr_city", $vo->provider_address_line_3, $document);
		$document = str_replace("Tr_postal_code", $vo->provider_postcode, $document);
		$document = str_replace("Tr_representative", "", $document);
		$document = str_replace("Tr_position", "", $document);		
		
		$document = fwrite($fp, $document); 
		fclose($fp); 		

		// create object
		$zip = new ZipArchive();
		// open archive 
		// docx filetype on earlier versions of windows may require:
		// http://www.microsoft.com/downloads/en/details.aspx?FamilyId=941B3470-3AE9-4AEE-8F43-C6BB74CD1466&displaylang=en
		//
		if ( $zip->open($target_path."/".$username."/letter_of_commitment.docx", ZIPARCHIVE::CREATE ) !== TRUE) {
		    throw new Exception("Could not open archive");
		}
		
	//	pre($target_path."/".$username."/arf.docx");
				
		chdir($target_path . "/" . $username); 

			// list of files to add
		$fileList = array(
		    '[Content_Types].xml',
		    '_rels/.rels',
		    'docProps/app.xml',
		    'docProps/core.xml',
			'word/document.xml',
			'word/endnotes.xml',
			'word/fontTable.xml',
			'word/footer1.xml',
			'word/footnotes.xml',
			'word/header1.xml',
			'word/numbering.xml',
			'word/settings.xml',
			'word/styles.xml',
			'word/webSettings.xml',
			'word/_rels/document.xml.rels',
			'word/_rels/header1.xml.rels',
			'word/media/image1.jpeg',
			'word/media/image2.jpeg',
			'word/theme/theme1.xml',
			'customXml/_rels/item1.xml.rels',
			'customXml/item1.xml',
			'customXml/itemProps1.xml'
		);
		
		// add files
		foreach ($fileList as $f) {
		    $zip->addFile($f) or die ("ERROR: Could not add file: $f");   
		}
		
		// close and save archive
		$zip->close();
		http_redirect("do.php?_action=downloader&path=/".$username."&f=letter_of_commitment.docx");
	}
}
?>