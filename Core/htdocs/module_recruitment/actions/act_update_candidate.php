<?php
define('METRES_IN_A_MILE', 1609.344);

class update_candidate implements IAction
{
	public function execute(PDO $link) {
		
		if( $_REQUEST['candidate_id'] == '' ) {
			return null;
		}

        $registration = Candidate::loadFromDatabase($link, $_REQUEST['candidate_id']);
        
        // does populate clean up the form data ?
        // any hacking methods tested for etc....
		$registration->populate($_REQUEST);
		
		// allow empty county entries - why does form not validate this upfront?? 
		if ( !isset($registration->county) ) {
			$registration->county = '';
		}
		
		$registration->id = $_REQUEST['candidate_id'];
		
		// candidate header record save
		$registration->dob = $registration->dob_year."-".$registration->dob_month."-".$registration->dob_day;
		// this is returning false as the candidate already exists
		
		if ( !$registration->save($link) ) {
		    // http_redirect('do.php?_action=view_candidate_register&msg=2');			
		}
		
		// save any bespoke values 
		if ( SystemConfig::getEntityValue($link, 'recruitment_bespoke') ) {
			$screen_total_score = 0;
			$screen_total_checks = 0;
			
			//get an array of the values to populate
			// instantiate the user 
			$registrant = new User();
			// get the client specific data required for capture
			$registrant->getUserMetaData($link);
			// fill them up
			foreach ( $registrant->user_metadata as $page => $field_array )	{ 
				foreach ( $field_array as $title => $type ) {
					$format_titles = explode("_", $title);
					$format_details = explode("_", $type);
					$format_db_column = 'string';
					$capture_title = 'reg_'.$format_titles[0];
										
					if ( isset($_REQUEST[$capture_title]) ) {
						if ( is_array($_REQUEST[$capture_title]) ) {
							foreach ( $_REQUEST[$capture_title] as $location => $field_value ) {
								if ( $field_value ) {
									// do check for a score value
									if ( $format_db_column != 'int' ) {
										$field_value = "'".addslashes((string)$field_value)."'";
									}
									
									// re 10/08/2011 - duplication of records when no vacancy selected
									$sql_candidate_metadata = "INSERT INTO candidate_metadata ( userinfoid, candidateid, vacancy_id, ".$format_db_column."value ) VALUES (".$format_titles[0].", ".$registration->id.", NULL, ".$field_value.") ON DUPLICATE KEY UPDATE ".$format_db_column."value = ".$field_value;
									
									if ( isset($_REQUEST['id']) && is_numeric($_REQUEST['id']) ) {
										// $sql_candidate_metadata = "REPLACE INTO candidate_metadata ( userinfoid, candidateid, vacancy_id, ".$format_db_column."value ) VALUES (".$format_titles[0].", ".$registration->id.", ".$_REQUEST['id'].", ".$field_value.")";			
										$sql_candidate_metadata = "INSERT INTO candidate_metadata ( userinfoid, candidateid, vacancy_id, ".$format_db_column."value ) VALUES (".$format_titles[0].", ".$registration->id.", ".$_REQUEST['id'].", ".$field_value.") ON DUPLICATE KEY UPDATE ".$format_db_column."value = ".$field_value;
										throw new Exception('why wherefore '.$_REQUEST['id']);
									}
									else {
										// if no vacancy 
										// remove duplicates due to unique key NULL rule in mysql
										// http://bugs.mysql.com/bug.php?id=25544 ( its not a bug )
										// ---
										// RE - need to check what is going on here, as we could remove
										//      candidate details when they have no vacancy....
										// ---
										//$sql_rmv_null_candidate_metadata = 'DELETE FROM candidate_metadata where userinfoid = '.$format_titles[0].' and candidateid = '.$registration->id.' and vacancy_id is NULL';
										// $st = $link->query($sql_rmv_null_candidate_metadata);
									}
									
									$st = $link->query($sql_candidate_metadata);
        							if( !$st ) {
										throw new Exception('We have had a problem saving your details');
        							}
								}	
							}
						}
						else {
							$field_value = $_REQUEST[$capture_title];	
							
							if ( $field_value ) {
								if ( $format_db_column != 'int' ) {
									$field_value = "'".addslashes((string)$_REQUEST[$capture_title])."'";
								}
								
								// re 10/08/2011 - duplication of records when no vacancy selected
								// $sql_candidate_metadata = "REPLACE INTO candidate_metadata ( userinfoid, candidateid, vacancy_id, ".$format_db_column."value ) VALUES (".$format_titles[0].", ".$registration->id.", NULL, ".$field_value.")";
								$sql_candidate_metadata = "INSERT INTO candidate_metadata ( userinfoid, candidateid, vacancy_id, ".$format_db_column."value ) VALUES (".$format_titles[0].", ".$registration->id.", NULL, ".$field_value.") ON DUPLICATE KEY UPDATE ".$format_db_column."value = ".$field_value;
																	
								if ( isset($_REQUEST['id']) && is_numeric($_REQUEST['id']) ) {
									// $sql_candidate_metadata = "REPLACE INTO candidate_metadata ( userinfoid, candidateid, vacancy_id, ".$format_db_column."value ) VALUES (".$format_titles[0].", ".$registration->id.", ".$_REQUEST['id'].", ".$field_value.")";
									$sql_candidate_metadata = "INSERT INTO candidate_metadata ( userinfoid, candidateid, vacancy_id, ".$format_db_column."value ) VALUES (".$format_titles[0].", ".$registration->id.", ".$_REQUEST['id'].", ".$field_value.") ON DUPLICATE KEY UPDATE ".$format_db_column."value = ".$field_value;		
								}
								else {
									// if no vacancy 
									// remove duplicates due to unique key NULL rule in mysql
									// http://bugs.mysql.com/bug.php?id=25544 ( its not a bug )
									// $sql_rmv_null_candidate_metadata = 'DELETE FROM candidate_metadata where userinfoid = '.$format_titles[0].' and candidateid = '.$registration->id.' and vacancy_id is NULL';
									// $st = $link->query($sql_rmv_null_candidate_metadata);
									
								}
								$st = $link->query($sql_candidate_metadata);
        						if( !$st ) {
									throw new Exception('We have had a problem saving your details '.$sql_candidate_metadata);
        						}
							}
						}
						
		

						if ( isset($_REQUEST['ass_screen_'.$format_titles[0]]) ) {
							if ( $_REQUEST['ass_screen_'.$format_titles[0]] !== '0' ) {
								$screen_total_score += $_REQUEST['ass_screen_'.$format_titles[0]];
								$screen_total_checks++;
							}
						}
					}
				}
			}	
			
			// save the action to the candidate notes
			$candidate_note = new CandidateNotes();
			$candidate_note->candidate_id = $registration->id;
			$candidate_note->note = 'Candidate Screened';
			$candidate_note->username = $_SESSION['user']->username;
			$candidate_note->status = 1;	
			$candidate_note->save($link);
			
			// find the screening result percentage
			$possible_total = $screen_total_checks*4;
            $original_screen = $registration->screening_score;
            if( $possible_total > 0 ) {
            	$registration->screening_score = sprintf("%02d", (100/$possible_total)*$screen_total_score);
            	
            	$candidate_note->note = 'Screening score changed from '.$original_screen.' to '.$registration->screening_score;
				$candidate_note->username = $_SESSION['user']->username;
				$candidate_note->status = 0;	
				$candidate_note->save($link);
            }
            
                        
           // save the screening score to the application
            if ( isset($_REQUEST['id'] ) ) {	            	
            	$applicant_screen_score = 'REPLACE INTO candidate_applications (candidate_id, vacancy_id, application_screening, has_been_screened) values ('.$registration->id.', '.$_REQUEST['id'].', '.$registration->screening_score.', 1 )';
            	$st = $link->query($applicant_screen_score);
            }            
			$registration->save($link);		

		}
		
		// re 09/11/2011 - save the cv
		// ----
		$this->upload_candidate_files($registration->id);
		// ----
		
		
		//re - why do we have to do this?
		$return_path = preg_replace('/&amp;/', '&', $_SESSION['bc']->getCurrent());
		
		http_redirect($return_path);

		
	}
	
	private function upload_candidate_files($candidate_id)
	{
		$target_directory = 'recruitment';
		
		$filepaths = Repository::processFileUploads('uploadedfile', $target_directory);
		foreach($filepaths as $filepath)
		{
			$ext = pathinfo($filepath, PATHINFO_EXTENSION);
			$path = dirname($filepath);
			rename($filepath, $path.'/'.$candidate_id.'.'.$ext);
		}
		
		$filepaths = Repository::processFileUploads('uploadedmockfile', $target_directory);
		foreach($filepaths as $filepath)
		{
			$ext = pathinfo($filepath, PATHINFO_EXTENSION);
			$path = dirname($filepath);
			rename($filepath, $path.'/mock_'.$candidate_id.'.'.$ext);
		}
		
		$filepaths = Repository::processFileUploads('uploadednotesfile', $target_directory);
		foreach($filepaths as $filepath)
		{
			$ext = pathinfo($filepath, PATHINFO_EXTENSION);
			$path = dirname($filepath);
			rename($filepath, $path.'/notes_'.$candidate_id.'.'.$ext);
		}
		
		/*
		// Create basic directory if does not exists
		if( !(file_exists(DATA_ROOT."/uploads/".DB_NAME)) ) {
			mkdir(DATA_ROOT."/uploads/".DB_NAME);
		}
		
		// Create directory for candidates if not existing
		if( !(file_exists(DATA_ROOT."/uploads/".DB_NAME."/recruitment/")) ) {
			mkdir(DATA_ROOT."/uploads/".DB_NAME."/recruitment/");
		}
		
		if( isset($_FILES['uploadedfile']['name']) ) {	
			if( $_FILES['uploadedfile']['name']!='' ) {
				
				$ext = pathinfo($_FILES['uploadedfile']['name'], PATHINFO_EXTENSION);
				$target_path = DATA_ROOT."/uploads/".DB_NAME."/recruitment/".$candidate_id.".".$ext;
				
				if( !(move_uploaded_file($_FILES['uploadedfile']['tmp_name'], $target_path)) ) { 
				    // throw new Exception("There was an error uploading the file, please try again!");
				}
			}
		}
		
		if( isset($_FILES['uploadedmockfile']['name']) ) {	
			if( $_FILES['uploadedmockfile']['name']!='' ) {
				
				$ext = pathinfo($_FILES['uploadedmockfile']['name'], PATHINFO_EXTENSION);
				$target_path = DATA_ROOT."/uploads/".DB_NAME."/recruitment/mock_".$candidate_id.".".$ext;
				
				if( !(move_uploaded_file($_FILES['uploadedmockfile']['tmp_name'], $target_path)) ) { 
				    // throw new Exception("There was an error uploading the file, please try again!");
				}
			}
		}
		
		if( isset($_FILES['uploadednotesfile']['name']) ) {	
			if( $_FILES['uploadednotesfile']['name']!='' ) {
				
				$ext = pathinfo($_FILES['uploadednotesfile']['name'], PATHINFO_EXTENSION);
				$target_path = DATA_ROOT."/uploads/".DB_NAME."/recruitment/notes_".$candidate_id.".".$ext;
				
				if( !(move_uploaded_file($_FILES['uploadednotesfile']['tmp_name'], $target_path)) ) { 
				    // throw new Exception("There was an error uploading the file, please try again!");
				}
			}
		}
		*/
	}
	
	
}
?>