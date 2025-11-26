<?php
class word_report_1 implements IAction
{
	public function execute(PDO $link)
	{

		chdir(DATA_ROOT."/uploads/am_demo"); 
		
		// create object
		$zip = new ZipArchive();
		// open archive 
		if ($zip->open('wtr.docx', ZIPARCHIVE::CREATE) !== TRUE) {
		    die ("Could not open archive");
		}

		chdir("../../htdocs/test/wtr"); 
		
		// list of files to add
		$fileList = array(
		    '[Content_Types].xml',
		    '_rels/.rels',
		    'docProps/app.xml',
		    'docProps/core.xml',
			'word/endnotes.xml',
			'word/footer1.xml',
			'word/header1.xml',
			'word/styles.xml',
			'word/document.xml',
			'word/fontTable.xml',
			'word/footnotes.xml',
			'word/settings.xml',
			'word/webSettings.xml',
			'word/_rels/document.xml.rels',
			'word/_rels/footer1.xml.rels',
			'word/_rels/header1.xml.rels',
			'word/media/image1.jpeg',
			'word/media/image2.png',
			'word/theme/theme1.xml'
		);


		
		// add files
		foreach ($fileList as $f) {
		    $zip->addFile($f) or die ("ERROR: Could not add file: $f");   
		}
		
		// close and save archive
		$zip->close();
		
		http_redirect("do.php?_action=downloader&path=/am_demo&f=wtr.docx");
	}
}
?>