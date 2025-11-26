<?php
/**
 * learner_doc
 * @author: richard elmes
 * @copyright: perspective ltd 2011
 */
class learner_doc implements IAction
{
	public function execute(PDO $link) {
		
		// Validate data entry
		$username = isset($_REQUEST['username']) ? $_REQUEST['username'] : '';
		$filename = isset($_REQUEST['docname']) ? $_REQUEST['docname'] : '';
		
		if ( '' == $filename || '' == $username ) {
			throw new Exception('Learner Documents: We cannot understand your request!');
		}
		
		// add the extension onto the filename
		$filename .= '.docx';
		
		// set the locations for files
		$target_path = DATA_ROOT."/uploads/".DB_NAME;
		$file_manager = new Docx($filename, DATA_ROOT.'/uploads/'.DB_NAME.'/learner_doc_templates/', DATA_ROOT."/uploads/".DB_NAME."/".$username."/");		
		$file_manager->extract_docx();
		
		// Create Value Object
		$user_obj = User::loadFromDatabase($link, $username);
		if( is_null($user_obj) ) {
			throw new Exception("No user with username '$username'");
		}

		// Employer address
		$address = new Address($user_obj->loc);
		$addressLines = $address->to4Lines();
			
		$learner_address = $user_obj->home_address_line_1 != ''?preg_replace('/\&/', 'and', $user_obj->home_address_line_1):' ';
		$learner_address .= $user_obj->home_address_line_2 != ''?' '.preg_replace('/\&/', 'and', $user_obj->home_address_line_2):' ';
		$learner_address .= $user_obj->home_address_line_3 != ''?' '.preg_replace('/\&/', 'and', $user_obj->home_address_line_3):' ';
		$learner_address .= $user_obj->home_address_line_4 != ''?' '.preg_replace('/\&/', 'and', $user_obj->home_address_line_4):' ';
		$learner_address .= $user_obj->home_postcode != ''?' '.preg_replace('/\&/', 'and', $user_obj->home_postcode):' ';

		$learner_address_line1 = $user_obj->home_address_line_1 != ''?preg_replace('/\&/', 'and', $user_obj->home_address_line_1):' ';
		$learner_address_line2 = $user_obj->home_address_line_2 != ''?' '.preg_replace('/\&/', 'and', $user_obj->home_address_line_2):' ';
		$learner_address_line3 = $user_obj->home_address_line_3 != ''?' '.preg_replace('/\&/', 'and', $user_obj->home_address_line_3):' ';
		$learner_address_line4 = $user_obj->home_address_line_4 != ''?' '.preg_replace('/\&/', 'and', $user_obj->home_address_line_4):' ';
		$learner_address_postcode = $user_obj->home_postcode != ''?' '.preg_replace('/\&/', 'and', $user_obj->home_postcode):' ';

		$learner_address_line12 = $learner_address_line1;
		if($learner_address_line1 != '' && $learner_address_line2 != '')
			$learner_address_line12 = $learner_address_line1 . ' ' . $learner_address_line2;
		elseif($learner_address_line1 == '' && $learner_address_line2 != '')
			$learner_address_line12 = $learner_address_line2;

		$learner_address_line34 = $learner_address_line3;
		if($learner_address_line3 != '' && $learner_address_line4 != '')
			$learner_address_line34 = $learner_address_line3 . ' ' . $learner_address_line4;
		elseif($learner_address_line3 == '' && $learner_address_line4 != '')
			$learner_address_line34 = $learner_address_line4;

		$file_manager->update_docx('LEARNER_ADDRESS_LINE12', $learner_address_line12);
		$file_manager->update_docx('LEARNER_ADDRESS_LINE34', $learner_address_line34);
		$file_manager->update_docx('LEARNER_ADDRESS_POSTCODE', $learner_address_postcode);

		// populate the document
		// re - these all look generic
		// ---
		$file_manager->update_docx('LEARNER_NAME', $user_obj->firstnames.' '.$user_obj->surname);
		$file_manager->update_docx('LEARNER_FIRSTNAMES', $user_obj->firstnames);
		$file_manager->update_docx('TODAY', date("F j, Y"));
		$file_manager->update_docx('LEARNER_DOB', Date::toShort($user_obj->dob));
		$file_manager->update_docx('EMPLOYER_NAME', $user_obj->org->legal_name);
		$addressLine1AndLine2 = $addressLines[0];
		if(!is_null($addressLines[1]) && $addressLines[1] != '')
			$addressLine1AndLine2 .= ', ' . $addressLines[1];
		$file_manager->update_docx('EMPLOYER_ADDRESS_1_2', preg_replace('/\&/', 'and', $addressLine1AndLine2));

		$addressLine3AndLine4 = "";
		if(trim($addressLines[1]) != trim($addressLines[2]))
		{
			if($addressLines[2] != '' && $addressLines[3] != '')
				$addressLine3AndLine4 = $addressLines[2] . ', ' . $addressLines[3];
			elseif($addressLines[2] != '' && $addressLines[3] == '')
				$addressLine3AndLine4 = $addressLines[2];
			elseif($addressLines[2] == '' && $addressLines[3] != '')
				$addressLine3AndLine4 = $addressLines[3];
		}
		else
		{
			$addressLine3AndLine4 = $addressLines[3];
		}
		$file_manager->update_docx('EMPLOYER_ADDRESS_3_4', preg_replace('/\&/', 'and', $addressLine3AndLine4));
		$file_manager->update_docx('EMPLOYER_ADDRESS_1', preg_replace('/\&/', 'and', $addressLines[0]));
		$file_manager->update_docx('EMPLOYER_ADDRESS_2', preg_replace('/\&/', 'and', $addressLines[1]));
		$file_manager->update_docx('EMPLOYER_ADDRESS_3', preg_replace('/\&/', 'and', $addressLines[2].', '.$addressLines[3]));
		$file_manager->update_docx('EMPLOYER_POSTCODE', $user_obj->loc->postcode);
		$file_manager->update_docx('LEARNER_ADDRESS', $learner_address);

		
		
		// ---
		
		// re - this one looks superdruggie
		// ---
		$file_manager->update_docx('LEARNER_LOCATION', preg_replace('/\&/', 'and', $user_obj->org->legal_name));
		// ---

		// write the updated document.xml to the temporary location.
		$file_manager->write_docx();
		// re-archive the temporary location files and place in write location
		$final_file = $file_manager->bundle_docx();
		// remove any temporary files created during the manipulations
	 	$file_manager->cleanup_docx();
		
		// relmes - updated to use the filename generated from the docx library
		http_redirect("do.php?_action=downloader&path=".$username."&f=".$final_file);		
		
	}
}
?>

