<?php
class word_lewisham_ilp implements IAction
{
	public function execute(PDO $link)
	{

		$internaltitle = isset($_GET['internaltitle']) ? $_GET['internaltitle'] : '';
		$qualification_id = isset($_GET['qualification_id']) ? $_GET['qualification_id'] : '';
		$tr_id = isset($_GET['tr_id']) ? $_GET['tr_id'] : '';
		$framework_id = isset($_GET['framework_id']) ? $_GET['framework_id'] : '';
		
		$vo = TrainingRecord::loadFromDatabase($link, $tr_id);
		$sq = StudentQualification::loadFromDatabase($link, $qualification_id, $framework_id, $tr_id, $internaltitle);
		
		$internaltitle = DAO::getSingleValue($link, "SELECT title FROM courses INNER JOIN courses_tr ON courses_tr.course_id = courses.id WHERE courses_tr.tr_id = $tr_id;");
		
		$username = $_SESSION['user']->username;
		
		if(!(file_exists(DATA_ROOT."/uploads/".DB_NAME)))
			mkdir(DATA_ROOT."/uploads/".DB_NAME);
		
		$target_path = DATA_ROOT."/uploads/".DB_NAME;
		
		if((file_exists(DATA_ROOT."/uploads/".DB_NAME."/".$username))) {
			// clean down the directory 
			$this->delete_directory($target_path."/".$username);
		}
		mkdir($target_path."/".$username);
				
		// Decompress word file in user directory		
		$zip = new ZipArchive();   
		// open archive 
		if( DB_NAME == 'am_raytheon' ) {
			if ( $zip->open('arf.docx') !== TRUE ) {
			    die ("Could not open archive");
			}
		}
		// #190 {0000000230} - fareham review form
		elseif( DB_NAME == 'am_fareham' ) 
		{
			//if ( $zip->open('farehamreview.docx') !== TRUE ) 
			if ( $zip->open('arfdemo.docx') !== TRUE ) 
			{
				die ("Could not open archive");
			}
		}
		elseif( DB_NAME == 'am_lewisham' ) 
		{
			//if ( $zip->open('farehamreview.docx') !== TRUE ) 
			if ( $zip->open('lewishamilp.docx') !== TRUE )
			{
				die ("Could not open archive");
			}
		}
		else {
			if ( $zip->open('arfdemo.docx') !== TRUE ) {
			    die ("Could not open archive");
			}
		}		

		// extract contents to destination directory
		$zip->extractTo($target_path."/".$username);
		$zip->close();  

		// read file 
		$document = file_get_contents($target_path."/".$username."/word/document.xml");

		// Populate values
		$fp = fopen($target_path."/".$username."/word/document.xml", "w") or die("Couldn't create new file"); 
		$document = str_replace("GivenNames",$vo->firstnames . " " . $vo->surname,$document);
        $document = str_replace("LearnRefNumber",$vo->l03,$document);
        $document = str_replace("FworkTitle",DAO::getSingleValue($link,"select frameworks.title from frameworks inner join courses on courses.framework_id = frameworks.id inner join courses_tr on courses_tr.course_id = courses.id where courses_tr.tr_id = $vo->id limit 0,1"),$document);
        $document = str_replace("Fwork",DAO::getSingleValue($link,"select framework_code from frameworks inner join courses on courses.framework_id = frameworks.id inner join courses_tr on courses_tr.course_id = courses.id where courses_tr.tr_id = $vo->id limit 0,1"),$document);
        $document = str_replace("StartDate1","",$document);
        $document = str_replace("StartDate2","",$document);
        $document = str_replace("StartDate3","",$document);
        $document = str_replace("StartDate4","",$document);
        $document = str_replace("StartDate5","",$document);
        $document = str_replace("PlannedDate1","",$document);
        $document = str_replace("PlannedDate2","",$document);
        $document = str_replace("PlannedDate3","",$document);
        $document = str_replace("PlannedDate4","",$document);
        $document = str_replace("PlannedDate5","",$document);
        $document = str_replace("ActEnd1","",$document);
        $document = str_replace("ActEnd2","",$document);
        $document = str_replace("ActEnd3","",$document);
        $document = str_replace("ActEnd4","",$document);
        $document = str_replace("ActEnd5","",$document);
        $document = str_replace("NVQQAN","",$document);
        $document = str_replace("NVQAward","",$document);
        $document = str_replace("TechQAN","",$document);
        $document = str_replace("TechAward","",$document);
        $document = str_replace("CommQAN","",$document);
        $document = str_replace("CommAward","",$document);
        $document = str_replace("AONQAN","",$document);
        $document = str_replace("ICTQAN","",$document);
        if($vo->isGrouped($link))
            $assessor_name = DAO::getSingleValue($link, "select concat(firstnames, ' ', surname) from users inner join groups on groups.assessor = users.username inner join group_members on group_members.groups_id = groups.id where group_members.tr_id=$tr_id;");
        else
            $assessor_name = DAO::getSingleValue($link, "select concat(firstnames, ' ', surname) from users where username = '$vo->assessor'");
        $document = str_replace("AssessorName",$assessor_name,$document);
        if($vo->isGrouped($link))
            $AssessorContact = DAO::getSingleValue($link, "select work_telephone from users inner join groups on groups.assessor = users.username inner join group_members on group_members.groups_id = groups.id where group_members.tr_id=$tr_id;");
        else
            $AssessorContact = DAO::getSingleValue($link, "select work_telephone from users where username = '$vo->assessor'");
        $document = str_replace("AssessorContact",$AssessorContact,$document);
        $employer = DAO::getSingleValue($link, "select legal_name from organisations where id = '$vo->employer_id'");
        $document = str_replace("EmployerName",$employer,$document);
        $employer_contact_name  = DAO::getSingleValue($link, "SELECT contact_name FROM locations INNER JOIN organisations ON organisations.id = locations.organisations_id WHERE organisations.id = '$vo->employer_id'");
        $document = str_replace("ContactName",$employer_contact_name,$document);
        $EmployerAddress  = DAO::getSingleValue($link, "SELECT address_line_1 FROM locations INNER JOIN organisations ON organisations.id = locations.organisations_id WHERE organisations.id = '$vo->employer_id'");
        $document = str_replace("EmployerAddress",$EmployerAddress,$document);
        $telephone  = DAO::getSingleValue($link, "SELECT telephone FROM locations INNER JOIN organisations ON organisations.id = locations.organisations_id WHERE organisations.id = '$vo->employer_id'");
        $document = str_replace("EmployerTelephone",$telephone,$document);
        $email  = DAO::getSingleValue($link, "SELECT contact_email FROM locations INNER JOIN organisations ON organisations.id = locations.organisations_id WHERE organisations.id = '$vo->employer_id'");
        $document = str_replace("EmployerEmail",$email,$document);
//        $iv = DAO::getSingleValue($link, "select concat(firstnames, ' ', surname) from users where username = '$vo->iv'");
        $document = str_replace("IVName","",$document);
        $document = str_replace("IVContact","",$document);



        /*        $document = str_replace("Vehicle Maintenance and Repair",$internaltitle,$document);
                $document = str_replace("19-12-1978",Date::toMedium($vo->dob),$document);
                $actual_review_date = DAO::getSingleValue($link, "select meeting_date from assessor_review where tr_id = '$tr_id' order by meeting_date desc limit 0,1");
                $document = str_replace("15-04-2010",Date::toMedium($actual_review_date),$document);
                $employer = DAO::getSingleValue($link, "select legal_name from organisations where id = '$vo->employer_id'");
                $document = str_replace("Perspective Ltd",$employer,$document);
                $document = str_replace("Apprenticeship","",$document);
                $employer_contact_name  = DAO::getSingleValue($link, "SELECT contact_name FROM locations INNER JOIN organisations ON organisations.id = locations.organisations_id WHERE organisations.id = '$vo->employer_id'");
                $document = str_replace("Avril",$employer_contact_name,$document);
                $document = str_replace("01-01-2010",Date::toMedium($vo->start_date),$document);
                $document = str_replace("31-12-2010",Date::toMedium($vo->target_date),$document);
                $document = str_replace("15-03-2010","",$document);
                $document = str_replace("999999999999",$vo->l03,$document);

                if($vo->isGrouped($link))
                    $assessor_name = DAO::getSingleValue($link, "select concat(firstnames, ' ', surname) from users inner join groups on groups.assessor = users.username inner join group_members on group_members.groups_id = groups.id where group_members.tr_id=$tr_id;");
                else
                    $assessor_name = DAO::getSingleValue($link, "select concat(firstnames, ' ', surname) from users where username = '$vo->assessor'");

                $document = str_replace("Gary Harlock",$assessor_name,$document);

                // NVQ Unit Percentages
                $xml = DAO::getSingleValue($link, "select evidences from student_qualifications where tr_id = $tr_id and qualification_type='NVQ'");
                if($xml!='')
                {
                    //$pageDom = new DomDocument();
                    //$pageDom->loadXML(utf8_encode($xml));
                    $pageDom = XML::loadXmlDom(utf8_encode($xml));
                    $e = $pageDom->getElementsByTagName('unit');
                    $unit = 0;
                    foreach($e as $node)
                    {
                        $unit++;
                        $or = $node->getAttribute('owner_reference');
                        $p = $node->getAttribute('percentage');
                        $document = str_replace( ("U".$unit."T") , $or, $document);
                        $document = str_replace( ("U".$unit."P") , (sprintf("%.0F",$p)."%"), $document);
                    }
                    // Clear Units left
                    for($a = $unit+1; $a<=11; $a++)
                    {
                        $document = str_replace( ("U".$a."T") , "", $document);
                        $document = str_replace( ("U".$a."P") , "", $document);
                    }

                    $tcp = DAO::getSingleValue($link, "select IF(unitsUnderAssessment>100,100,unitsUnderAssessment) from student_qualifications  where tr_id = $tr_id and qualification_type='VRQ'");
                    $document = str_replace( "TCP" , (sprintf("%.0F",$tcp)."%"), $document);
                }
                else
                {
                    for($a = 1; $a<=11; $a++)
                    {
                        $document = str_replace( ("U".$a."T") , "", $document);
                        $document = str_replace( ("U".$a."P") , "", $document);
                    }
                }

                // Key skills
                // Communication
                $xml = DAO::getSingleValue($link, "select evidences from student_qualifications where tr_id = $tr_id and internaltitle like '%Key Skills in Communication%' and aptitude!=1");
                if($xml!='')
                {
                    //$pageDom = new DomDocument();
                    //$pageDom->loadXML($xml);
                    $pageDom = XML::loadXmlDom($xml);
                    $e = $pageDom->getElementsByTagName('unit');
                    foreach($e as $node)
                    {
                        $or = $node->getAttribute('owner_reference');
                        $p = $node->getAttribute('percentage');
                        if($or=="Part A" || $or=="PartA")
                        {
                            $document = str_replace( "Test1" , (sprintf("%.0F",$p)."%"), $document);
                        }
                        elseif($or=="Part B" || $or=="PartB")
                        {
                            $document = str_replace( "Port1" , (sprintf("%.0F",$p)."%"), $document);
                        }
                    }
                }


                // Application of Numbers
                $xml = DAO::getSingleValue($link, "select evidences from student_qualifications where tr_id = $tr_id and internaltitle like '%Key Skills in Application of Number%' and aptitude!=1");
                if($xml!='')
                {
                    //$pageDom = new DomDocument();
                    //$pageDom->loadXML(utf8_encode($xml));
                    $pageDom = XML::loadXmlDom(utf8_encode($xml));
                    $e = $pageDom->getElementsByTagName('unit');
                    foreach($e as $node)
                    {
                        $or = $node->getAttribute('owner_reference');
                        $p = $node->getAttribute('percentage');
                        if($or=="Part A" || $or=="PartA")
                        {
                            $document = str_replace( "Test2" , (sprintf("%.0F",$p)."%"), $document);
                        }
                        elseif($or=="Part B" || $or=="PartB")
                        {
                            $document = str_replace( "Port2" , (sprintf("%.0F",$p)."%"), $document);
                        }
                    }
                }

                // Information and Communication Technology
                $xml = DAO::getSingleValue($link, "select evidences from student_qualifications where tr_id = $tr_id and internaltitle like '%Key Skills in Information and Communication Technology%' and aptitude!=1");
                if($xml!='')
                {

                    //$pageDom = new DomDocument();
                    //$pageDom->loadXML($xml);
                    $pageDom = XML::loadXmlDom($xml);
                    $e = $pageDom->getElementsByTagName('unit');
                    foreach($e as $node)
                    {
                        $or = $node->getAttribute('owner_reference');
                        $p = $node->getAttribute('percentage');
                        if($or=="Part A" || $or=="PartA")
                        {
                            $document = str_replace( "Test3" , (sprintf("%.0F",$p)."%"), $document);
                        }
                        elseif($or=="Part B" || $or=="PartB")
                        {
                            $document = str_replace( "Port3" , (sprintf("%.0F",$p)."%"), $document);
                        }
                    }
                }

                $document = str_replace( "Test1" , "Exempt", $document);
                $document = str_replace( "Port1" , "Exempt", $document);
                $document = str_replace( "Test2" , "Exempt", $document);
                $document = str_replace( "Port2" , "Exempt", $document);
                $document = str_replace( "Test3" , "Exempt", $document);
                $document = str_replace( "Port3" , "Exempt", $document);
        */
		
		
		$document = fwrite($fp, $document); 
		fclose($fp);

		// create object
		$zip = new ZipArchive();
		// open archive 
		if ($zip->open($target_path."/".$username."/ILP.docx", ZIPARCHIVE::CREATE) !== TRUE) {
		    die ("Could not open archive");
		}
		// #190 {0000000230} - update to dynamically assess
		// Get the contents of the word document template directory 
		// - this ensures all the relevant bits are included
		// - replaces the previsou hardcoded method.
		//TODO create a library function for word manipulations		
		$this->docxDirectory($target_path."/".$username);

		// add files
		foreach ( $this->fileList as $f ) {
			// create the path to the file as stored in the archive
			$zip_pathname = str_replace($target_path."/".$username."/", "", $f);
			// ignore archive file
			if ( preg_match('/(.*).docx/', $zip_pathname) ) {
				continue;
			}
			// check if the file actually exists
			$full_file_path = realpath($f);
			if ( $full_file_path != '' ) {
				// add it to the archive
		   		$zip->addFile($f, $zip_pathname) or die ("ERROR: Could not add file: $f");  
			} 
		}
			
		// close and save archive
		$zip->close();
		http_redirect("do.php?_action=downloader&path=/".$username."/&f=ILP.docx");
	}
	
	// function to obtain the contents of a docx archive
	// - this differs depending on the content on the word document
	// - so needs to be dynamic
	private function docxDirectory($path = '') {
		if ( '' != $path ) {
			$handle = @opendir($path);
 			while (false !== ($file = readdir($handle))) {
        		if ($file == '.' || $file == '..') continue;

        		if ( is_dir("$path/$file")) {
            		$this->docxDirectory("$path/$file");
        		} else {
        			$this->fileList[] = $path.'/'.$file;  
        		}
    		}
    		closedir($handle);
		}
	}
	
	// function to remove the contents of a directory 
	// - required to clean down the user folder to prevent erroneous files
	// - being zipped into the archive
	private function delete_directory($path = '') {
		if ( '' != $path ) {
			if ( $handle = @opendir($path) ) {
				$array = array();
				while (false !== ($file = readdir($handle))) {
					if ($file != "." && $file != "..") {
						if(is_dir($path."/".$file)) {
							if(!@rmdir($path."/".$file)) { 
                				$this->delete_directory($path."/".$file.'/'); // Not empty? Delete the files inside it
							}
						}
						else {
							@unlink($path."/".$file);
						}
					}
				}
				closedir($handle);
				@rmdir($path);
			}
		}
	}
	
	
	// array to hold the content of the word extraction folders
	private $fileList = array();
}
?>