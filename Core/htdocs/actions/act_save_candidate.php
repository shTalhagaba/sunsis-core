<?php
class save_candidate implements IUnauthenticatedAction
{
	public function execute(PDO $link)
	{
		$candidate_created_by = isset($_REQUEST['candidate_created_by'])?$_REQUEST['candidate_created_by']:'';
		$candidate_interests = isset($_REQUEST['cand_interests']) ? $_REQUEST['cand_interests'] : array();
		$registration = new Candidate();
		if(isset($_POST['job_by_email']))
		{
			if($_POST['job_by_email'][0] == '1')
				$_POST['job_by_email'][0] = 1;
			else
				$_POST['job_by_email'][0] = 0;
		}
		else
			$_POST['job_by_email'][0] = 0;

		if(isset($_POST['vacancies']))
			$str = explode(",", $_POST['vacancies'][0]);

		$mode = isset($_POST['mode'])?$_POST['mode']: '';

		if(isset($_FILES['uploadedfile']))
		{
			$fileSelected = empty($_FILES['uploadedfile']['name'])?'No':'Yes';
		}
		else
			$fileSelected = "No";

		// does populate clean up the form data ?
		// any hacking methods tested for etc....
		$registration->populate($_POST);
		// do some initial sanity checks on the data sent.
		// if not enough return to user - expand this list - why is front end validation not catching?

		if ( ( !isset($registration->firstnames) || $registration->firstnames == '' ) || ( !isset($registration->surname) || $registration->surname == '' ) )
		{
			// msg=3 - 'we are sorry we haven't been able to save your details at this time
			// ---
			http_redirect('do.php?_action=view_candidate_register&msg=3');
			exit;
		}

		if(isset($_POST['vacancies']))
			$registration->applications = explode(",", $_POST['vacancies'][0]);
		// add in variable to handle error reporting
		$storage_errors = '';

		$sector_type = '';

		// candidate header record save
		if($registration->dob_year && $registration->dob_month && $registration->dob_day)
		{
			$registration->dob = $registration->dob_year."-".$registration->dob_month."-".$registration->dob_day;
		}
		// this is returning false as the candidate already exists


		//// ======== re: this is new and has repeating code from the original functionality 
		//// ========     needs a clean up...
		// we have a returning candidate
		if ( isset($_REQUEST['candidate_id']) && $_REQUEST['candidate_id'] != '' && isset($registration->enrolled) )
		{
			// set up the applications
			foreach ( $registration->applications as $vacancy_id )
			{
				$sector_type = 'Additional Information - '.DAO::getSingleValue($link, "SELECT description FROM lookup_vacancy_type WHERE id IN (SELECT TYPE FROM vacancies WHERE id = ".$vacancy_id.")");

				$sql_candidate_vacancies = "insert into candidate_applications ( candidate_id, vacancy_id ) values (".$_REQUEST['candidate_id'].",".$vacancy_id.")";
				$st = $link->query($sql_candidate_vacancies);
				if( !$st )
				{
					$storage_errors .= "Vacancy [".$vacancy_id."] storage problem.<br/>";
				}
			}
			if ( SystemConfig::getEntityValue($link, 'recruitment_bespoke') )
			{
				$screen_total_score = 0;
				$screen_total_checks = 0;

				// set the maximum possible score.
				$maximum_score = 1;
				$screening_sql = 'select count(*) from users_capture_info where scorevalues is not null and (infogroupid IN (1,2,3,5) ';
				if ( $sector_type != "" )
				{
					$screening_sql .= ' OR infogroupname IN ("Additional Information","'.$sector_type.'")';
				}
				$screening_sql .= ' )';
				$maximum_score += DAO::getSingleValue($link, $screening_sql);

				//get an array of the values to populate
				// instantiate the user 
				$registrant = new User();
				// get the client specific data required for capture
				$registrant->getUserMetaData($link);
				// fill them up
				foreach ( $registrant->user_metadata as $page => $field_array )
				{
					foreach ( $field_array as $title => $type )
					{
						$format_titles = explode("_", $title);
						$format_details = explode("_", $type);
						$format_db_column = 'string';
						switch( $format_details[1] )
						{
							case 'int':
								$format_db_column = 'int';
								break;
							case 'date':
								$format_db_column = 'date';
								break;
							default:
								$format_db_column = 'string';
						}

						// $capture_title = preg_replace('/ /', '', strtolower($format_titles[1]));
						$capture_title = 'reg_'.$format_titles[0];


						if ( isset($_POST[$capture_title]) )
						{
							$field_value = $_POST[$capture_title];

							if ( $field_value )
							{
								// re: 02/09/2011 added in checks to 
								//     the data entered to allow storage						
								if ( $format_db_column != 'int' )
								{
									$field_value = "'".addslashes((string)$field_value)."'";
								}
								elseif( !is_numeric($field_value) )
								{
									$format_db_column = 'string';
									$field_value = "'".addslashes((string)$field_value)."'";
								}

								if ( isset($registration->enrolled) && $registration->enrolled != '' )
								{
									foreach ( $registration->applications as $vacancy_id )
									{
										// re: 02/09/2011 adding safety check for blank vacancy id
										if ( is_numeric($vacancy_id) )
										{
											$sql_candidate_metadata = "insert into candidate_metadata (userinfoid, candidateid, vacancy_id, ".$format_db_column."value ) values (".$format_titles[0].", ".$registration->id.", ".$vacancy_id.", ".$field_value.")";
										}
										else
										{
											$sql_candidate_metadata = "insert into candidate_metadata (userinfoid, candidateid, ".$format_db_column."value ) values (".$format_titles[0].", ".$registration->id.", ".$field_value.")";
										}
										$st = $link->query($sql_candidate_metadata);
										if( !$st )
										{
											// throw new Exception('We have had a problem saving your details');
											$storage_errors .= "Screening questions storage problem.<br/>";
										}
									}
								}
								else
								{
									$sql_candidate_metadata = "insert into candidate_metadata (userinfoid, candidateid, ".$format_db_column."value ) values (".$format_titles[0].", ".$registration->id.", ".$field_value.")";

									$st = $link->query($sql_candidate_metadata);
									if( !$st )
									{
										// throw new Exception('We have had a problem saving your details');
										$storage_errors .= "Screening questions storage problem.<br/>";
									}
								}
							}
						}
					}
				}
			}

			// re 09/11/2011 - save the cv
			// ----
			$this->upload_candidate_cv($registration->id);

			http_redirect('do.php?_action=view_candidate_register&msg=1');
		}
		//// ======== re: this is new and has repeating code from the original functionality 
		//// ========     needs a clean up...

		else
		{
			$saved = $registration->save($link);
			if((DB_NAME=="am_demo" || DB_NAME=="am_baltic_demo" || DB_NAME=="am_baltic") && $saved)
			{
				$this->saveCandidateInterests($link, $saved, $candidate_interests);
			}
			if ( !$saved )
			{

				http_redirect('do.php?_action=view_candidate_register&msg=2'); // we already have your details
			}
			else
			{
				if(DB_NAME=="am_demo" || DB_NAME=="ams" || DB_NAME=="am_baltic")
				{
					$age = Date::dateDiffInfo(date("Y-m-d"),$registration->dob);
					$years = $age['year'];
					$months = $age['month'];
					if(($years > 24) OR ($years == 23 AND $months > 11))
					{
						$this->sendEmailToCandidate($link, $registration, 'age_inappropriate', $candidate_created_by);
					}
					else
					{
						$this->sendEmailToCandidate($link, $registration, 'consider_email_sift', $candidate_created_by);
					}
				}
			}
		}


		// there is at least one vacancy applied for
		if ( isset($registration->applications) && sizeof($registration->applications) >= 1 )
		{
			// set up the applications
			foreach ( $registration->applications as $vacancy_id )
			{
				// re: added in a check for blank vacancy
				// - need to investigate why this would be the case
				if ( is_numeric($vacancy_id) )
				{
					$sector_type = 'Additional Information - '.DAO::getSingleValue($link, "SELECT description FROM lookup_vacancy_type WHERE id IN (SELECT TYPE FROM vacancies WHERE id = ".$vacancy_id.")");
					$sql_candidate_vacancies = "insert into candidate_applications ( candidate_id, vacancy_id ) values (".$registration->id.",".$vacancy_id.")";
					$st = $link->query($sql_candidate_vacancies);
					if( !$st )
					{
						// throw new Exception( 'We have had a problem saving your details' );
						$storage_errors .= "Vacancy [".$vacancy_id."] storage problem.<br/>";
					}
					$storage_errors .= "[".$sql_candidate_vacancies."]<br/>";
				}
			}

			// disable as only do this on actual conversion to learner
			// $vacancy = Vacancy::loadFromDatabase($link, $registration->enrolled);
			// $vacancy->update($link);
		}

		// save employment history
		$num_of_employers = sizeof($registration->company_name);

		for( $emp_cnt = 0; $emp_cnt < $num_of_employers; $emp_cnt++ )
		{
			if( "" != $registration->company_name[$emp_cnt] )
			{
				$sql_candidate_employers = "insert into candidate_history ( candidate_id, company_name, job_title, skills ";
				$sql_candidate_values = "values (".$registration->id.",'".addslashes((string)$registration->company_name[$emp_cnt])."','".addslashes((string)$registration->job_title[$emp_cnt])."', ";
				$sql_candidate_values .= "'".addslashes((string)$registration->job_skills[$emp_cnt])."' ";
				if( $registration->hist_sday[$emp_cnt])
				{
					$completion_date = $registration->hist_syear[$emp_cnt]."-".$registration->hist_smon[$emp_cnt]."-".$registration->hist_sday[$emp_cnt];
					$sql_candidate_employers .= ", start_date";
					$sql_candidate_values .= ",'".$completion_date."'";
				}

				if( $registration->hist_eday[$emp_cnt] )
				{
					$completion_date = $registration->hist_eyear[$emp_cnt]."-".$registration->hist_emon[$emp_cnt]."-".$registration->hist_eday[$emp_cnt];
					$sql_candidate_employers .= ", end_date";
					$sql_candidate_values .= ",'".$completion_date."'";
				}

				$sql_candidate_employers .= ") ".$sql_candidate_values.")";
				$st = $link->query($sql_candidate_employers);
				if( !$st )
				{
					// throw new Exception( 'We have had a problem saving your history details' );
					$storage_errors .= "Employment History storage problem.<br/>";
				}
			}
		}



		/*$registration->level = array_filter($registration->level);
		$registration->level = array_merge($registration->level);

		$registration->subject = array_filter($registration->subject);
		$registration->subject = array_merge($registration->subject);

		$registration->grade = array_filter($registration->grade);
		$registration->grade = array_merge($registration->grade);*/


		if($registration->last_education == 1)
		{
//			$registration->subject = array_fill(0,sizeof($registration->subject), 'NA');
			$registration->subject = NULL;

		}

		// save qualifications
		$num_of_qualifications = sizeof($registration->subject);
		//$this->removeExisting($link, $registration->id, "candidate_qualification");
		$sql_candidate_qualifications = "";

		for( $qual_cnt = 0; $qual_cnt < $num_of_qualifications; $qual_cnt++ )
		{
			if( "" != $registration->subject[$qual_cnt] )
			{
				if(!isset($registration->level[$qual_cnt]))
					$registration->level[$qual_cnt] = '';
				$sql_candidate_qualifications .= "insert into candidate_qualification ( candidate_id, qualification_level, qualification_subject";
				$sql_candidate_values = "values (".$registration->id.",'".$registration->level[$qual_cnt]."','".$registration->subject[$qual_cnt]."'";
				// grade/date field inclusions
				if ( isset($registration->grade[$qual_cnt]))
				{
					$sql_candidate_qualifications .= ", qualification_grade";
					$sql_candidate_values .= ",'".$registration->grade[$qual_cnt]."'";
				}

//				if( $registration->comp_day[$qual_cnt]) {
//					$completion_date = $registration->comp_year[$qual_cnt]."-".$registration->comp_mon[$qual_cnt]."-".$registration->comp_day[$qual_cnt];
//					$sql_candidate_qualifications .= ", qualification_date";
//			  		$sql_candidate_values .= ",'".$completion_date."'";
//				}
				$sql_candidate_qualifications .= ") ".$sql_candidate_values.");";
//				$st = $link->query($sql_candidate_qualifications);
//        		if( !$st ) {
//					 throw new Exception( 'We have had a problem saving your qualification details' );
//					$storage_errors .= "Qualification storage problem.<br/>";
//        		}
			}

		}


		$num_of_qualifications = sizeof($registration->subject_1);

		for( $qual_cnt = 0; $qual_cnt < $num_of_qualifications; $qual_cnt++ )
		{
			if( "" != $registration->subject_1[$qual_cnt] )
			{
				$sql_candidate_qualifications .= "insert into candidate_qualification ( candidate_id, qualification_level, qualification_subject";
				$sql_candidate_values = "values (".$registration->id.",'".$registration->level_1[$qual_cnt]."','".$registration->subject_1[$qual_cnt]."'";
				// grade/date field inclusions
				if ( $registration->grade_1[$qual_cnt] )
				{
					$sql_candidate_qualifications .= ", qualification_grade";
					$sql_candidate_values .= ",'".$registration->grade_1[$qual_cnt]."'";
				}
				else
				{
					$sql_candidate_qualifications .= ", qualification_grade";
					$sql_candidate_values .= ",'~'";
				}

				if( $registration->comp_day[$qual_cnt])
				{
					$completion_date = $registration->comp_year[$qual_cnt]."-".$registration->comp_mon[$qual_cnt]."-".$registration->comp_day[$qual_cnt];
					$sql_candidate_qualifications .= ", qualification_date";
					$sql_candidate_values .= ",'".$completion_date."'";
				}
				$sql_candidate_qualifications .= ") ".$sql_candidate_values.");";
//				$st = $link->query($sql_candidate_qualifications);
//				if( !$st ) {
//					$storage_errors .= "Qualification storage problem.<br/>";
//				}
			}
		}

		$link->beginTransaction();
		try
		{
			$this->removeExisting($link, $registration->id, "candidate_qualification");
			$link->query($sql_candidate_qualifications);
			$link->commit();
		}
		catch(Exception $e)
		{
			$link->rollback();
			throw new WrappedException($e);
		}

		// save the disabilities

		$sql_candidate_disability = "";
		foreach ( $registration->disability as $disability )
		{
			$sql_candidate_disability .= "insert into candidate_disability ( candidate_id, disability_code ) values (".$registration->id.",'".$disability."');";
		}
		$link->beginTransaction();
		try
		{
			$this->removeExisting($link, $registration->id, "candidate_disability");
			$link->query($sql_candidate_disability);
			$link->commit();
		}
		catch(Exception $e)
		{
			$link->rollback();
			throw new WrappedException($e);
		}
//		$st = $link->query($sql_candidate_disability);
//		if( !$st ) {
////			 throw new Exception('We have had a problem saving your disability details');
//			$storage_errors .= "Disability storage problem.<br/>";
//		}

		// save the difficulties

		$sql_candidate_difficulty = "";
		foreach ( $registration->difficulty as $difficulty )
		{
			$sql_candidate_difficulty .= "insert into candidate_difficulty ( candidate_id, difficulty_code ) values (".$registration->id.",'".$difficulty."');";
		}
		$link->beginTransaction();
		try
		{
			$this->removeExisting($link, $registration->id, "candidate_difficulty");
			$link->query($sql_candidate_difficulty);
			$link->commit();
		}
		catch(Exception $e)
		{
			$link->rollback();
			throw new WrappedException($e);
		}


		// save any bespoke values 
		if ( SystemConfig::getEntityValue($link, 'recruitment_bespoke') )
		{
			$screen_total_score = 0;
			$screen_total_checks = 0;

			// set the maximum possible score.
			$maximum_score = 1;
			$screening_sql = 'select count(*) from users_capture_info where scorevalues is not null and (infogroupid IN (1,2,3,5) ';
			if ( $sector_type != "" )
			{
				$screening_sql .= ' OR infogroupname IN ("Additional Information","'.$sector_type.'")';
			}
			$screening_sql .= ' )';
			$maximum_score += DAO::getSingleValue($link, $screening_sql);

			// qualification assessment valuation
			if ( $num_of_qualifications >= 5 )
			{
				$screen_total_checks++;
			}
			//get an array of the values to populate
			// instantiate the user 
			$registrant = new User();
			// get the client specific data required for capture
			$registrant->getUserMetaData($link);
			// fill them up
			foreach ( $registrant->user_metadata as $page => $field_array )
			{
				foreach ( $field_array as $title => $type )
				{
					$format_titles = explode("_", $title);
					$format_details = explode("_", $type);
					$format_db_column = 'string';
					switch( $format_details[1] )
					{
						case 'int':
							$format_db_column = 'int';
							break;
						case 'date':
							$format_db_column = 'date';
							break;
						default:
							$format_db_column = 'string';
					}

					// $capture_title = preg_replace('/ /', '', strtolower($format_titles[1]));
					$capture_title = 'reg_'.$format_titles[0];

					if ( isset($_POST[$capture_title]) )
					{
						if ( is_array($_POST[$capture_title]) )
						{
							foreach ( $_POST[$capture_title] as $location => $field_value )
							{
								if ( $field_value )
								{

									// do check for a score value
									$scores = DAO::getResultSet($link, 'select lookupvalues, scorevalues from users_capture_info where userinfoid = '.$format_titles[0], DAO::FETCH_ASSOC);
									$screen_scores = array();
									if ( isset($scores[0]['scorevalues']) ) {
										$screen_total_checks++;
										$screen_values = explode('|', $scores[0]['lookupvalues']);
										$screen_scores = explode(',', $scores[0]['scorevalues']);

										$score_position = array_search($field_value, $screen_values);
										if ( $score_position !== false ) {
											$screen_total_score += 	$screen_scores[$score_position];
										}
									}

									// re: 02/09/2011 added in checks to 
									//     the data entered to allow storage						
									if ( $format_db_column != 'int' ) {
										$field_value = "'".addslashes((string)$field_value)."'";
									}
									elseif( !is_numeric($field_value) ) {
										$format_db_column = 'string';
										$field_value = "'".addslashes((string)$field_value)."'";
									}

									if ( isset($registration->enrolled) && $registration->enrolled != '' ) {
										foreach ( $registration->applications as $vacancy_id ) {
											// re: 02/09/2011 adding safety check for blank vacancy id
											if ( is_numeric($vacancy_id) ) {
												$sql_candidate_metadata = "insert into candidate_metadata (userinfoid, candidateid, vacancy_id, ".$format_db_column."value ) values (".$format_titles[0].", ".$registration->id.", ".$vacancy_id.", ".$field_value.")";
											}
											else {
												$sql_candidate_metadata = "insert into candidate_metadata (userinfoid, candidateid, ".$format_db_column."value ) values (".$format_titles[0].", ".$registration->id.", ".$field_value.")";
											}

											$st = $link->query($sql_candidate_metadata);
											if( !$st ) {
												// throw new Exception('We have had a problem saving your details');
												$storage_errors .= "Screening questions storage problem.<br/>";
											}
										}
									}
									else {
										$sql_candidate_metadata = "insert into candidate_metadata (userinfoid, candidateid, ".$format_db_column."value ) values (".$format_titles[0].", ".$registration->id.", ".$field_value.")";

										$st = $link->query($sql_candidate_metadata);
										if( !$st ) {
											// throw new Exception('We have had a problem saving your details');
											$storage_errors .= "Screening questions storage problem.<br/>";
										}
									}
								}
							}
						}
						else {
							$field_value = $_POST[$capture_title];

							if ( $field_value ) {

								// do check for a score value
								$scores = DAO::getResultSet($link, 'select lookupvalues, scorevalues from users_capture_info where userinfoid = '.$format_titles[0], DAO::FETCH_ASSOC);
								$screen_scores = array();
								if ( isset($scores[0]['scorevalues']) ) {
									$screen_total_checks++;
									$screen_values = explode('|', $scores[0]['lookupvalues']);
									$screen_scores = explode(',', $scores[0]['scorevalues']);
									$score_position = array_search($field_value, $screen_values);
									if ( $score_position !== false ) {
										$screen_total_score += 	$screen_scores[$score_position];
									}
								}

								// re: 02/09/2011 added in checks to 
								//     the data entered to allow storage						
								if ( $format_db_column != 'int' ) {
									$field_value = "'".addslashes((string)$_POST[$capture_title])."'";
								}
								elseif( !is_numeric($_POST[$capture_title]) ) {
									$format_db_column = 'string';
									$field_value = "'".addslashes((string)$_POST[$capture_title])."'";
								}

								if ( isset($registration->enrolled) && $registration->enrolled != '' ) {
									foreach ( $registration->applications as $vacancy_id ) {
										// re: 02/09/2011 adding safety check for blank vacancy id
										if ( is_numeric($vacancy_id) ) {
											$sql_candidate_metadata = "insert into candidate_metadata (userinfoid, candidateid, vacancy_id, ".$format_db_column."value ) values (".$format_titles[0].", ".$registration->id.", ".$vacancy_id.", ".$field_value.")";
										}
										else {
											$sql_candidate_metadata = "insert into candidate_metadata (userinfoid, candidateid, ".$format_db_column."value ) values (".$format_titles[0].", ".$registration->id.", ".$field_value.")";
										}
										$st = $link->query($sql_candidate_metadata);
										if( !$st ) {
											// throw new Exception('We have had a problem saving your details');
											$storage_errors .= "Screening questions storage problem.<br/>";
										}
									}
								}
								else {
									$sql_candidate_metadata = "insert into candidate_metadata (userinfoid, candidateid, ".$format_db_column."value ) values (".$format_titles[0].", ".$registration->id.", ".$field_value.")";

									$st = $link->query($sql_candidate_metadata);
									if( !$st ) {
										// throw new Exception('We have had a problem saving your details');
										$storage_errors .= "Screening questions storage problem.<br/>";
									}
								}
							}
						}
					}
				}
			}

			// find the screening result percentage
			//$possible_total = $screen_total_checks*4;

			$possible_total = $maximum_score*4;

			if( $possible_total == 0 ) { $possible_total = 1; }

			$registration->screening_score = sprintf("%02d", (100/$possible_total)*$screen_total_score);

			$registration->save($link);

			// save the action to the candidate notes
			$candidate_note = new CandidateNotes();
			$candidate_note->candidate_id = $registration->id;
			$candidate_note->note = 'Candidate Registered';
			$candidate_note->username = $registration->firstnames.' '.$registration->surname;
			$candidate_note->status = 1;
			$candidate_note->save($link);

			if ( $storage_errors != "" ) {
				$candidate_note->candidate_id = $registration->id;
				$candidate_note->note = "Candidate Registration Problems:\n".$storage_errors;
				$candidate_note->username = $registration->firstnames.' '.$registration->surname;
				// changed status to from 0 to 1 to prevent display on top level
				$candidate_note->status = 1;
				$candidate_note->save($link);
			}
		}

		// re 09/11/2011 - save the cv
		// ----
		$this->upload_candidate_files($registration->id, $mode, $fileSelected);
		// ----

		if ( isset($_REQUEST['hascomefrom']) ) {
			$return_path = 'do.php?_action=view_vacancies';
			if ( isset($_SESSION['bc']) ) {
				/*				$_SESSION['bc']->index = sizeof($_SESSION['bc']->urls)-1;
				$backto = $_SESSION['bc']->index-1;		
				if ( !is_numeric($backto) ) {
					$backto = $_SESSION['bc']->index;
				}
				//re - why do we have to do this?
				if ( isset($_SESSION['bc']->urls[$backto]) ) {
					$return_path = preg_replace('/&amp;/', '&', $_SESSION['bc']->urls[$backto]);
				}*/
				if(SystemConfig::getEntityValue($link, 'module_recruitment_baltic'))
					$return_path = 'do.php?_action=baltic_read_candidate&id='.$registration->id;
				else
					$return_path = $_SESSION['bc']->getPrevious();
			}
			http_redirect($return_path);
		}
		else {
			http_redirect('do.php?_action=view_candidate_register&msg=1&enrolled='.$registration->enrolled);
		}
	}

	private function removePreviousCV($candidate_id)
	{

		if ( file_exists(DATA_ROOT."/uploads/".DB_NAME."/recruitment/".$candidate_id.".doc") ) {
			unlink(DATA_ROOT."/uploads/".DB_NAME."/recruitment/".$candidate_id.".doc");
		}
		if( file_exists(DATA_ROOT."/uploads/".DB_NAME."/recruitment/".$candidate_id.".docx") ) {
			unlink(DATA_ROOT."/uploads/".DB_NAME."/recruitment/".$candidate_id.".docx");
		}
		if( file_exists(DATA_ROOT."/uploads/".DB_NAME."/recruitment/".$candidate_id.".pdf") ) {
			unlink(DATA_ROOT."/uploads/".DB_NAME."/recruitment/".$candidate_id.".pdf");
		}
	}

	private function upload_candidate_files($candidate_id, $mode = false, $fileSelected = false)
	{
		$target_directory = 'recruitment';

		if($mode == 'application' AND $fileSelected == 'Yes')
			$this->removePreviousCV($candidate_id);

		$filepaths = Repository::processFileUploads('uploadedfile', $target_directory);
		foreach($filepaths as $filepath)
		{
			$ext = pathinfo($filepath, PATHINFO_EXTENSION);
			$path = dirname($filepath);
			if(DB_NAME=="am_demo" || DB_NAME=="am_baltic_demo" || DB_NAME=="am_baltic")
				rename($filepath, $path.'/cv_1_'.$candidate_id.'.'.$ext);
			else
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
		if(DB_NAME=="am_demo" || DB_NAME=="am_baltic_demo" || DB_NAME=="am_baltic")
		{
			$filepaths = Repository::processFileUploads('uploadedphoto', $target_directory);
			foreach($filepaths as $filepath)
			{
				$ext = pathinfo($filepath, PATHINFO_EXTENSION);
				$path = dirname($filepath);
				rename($filepath, $path.'/photo_'.$candidate_id.'.'.$ext);
			}
		}
	}

	private function removeExisting(PDO $link, $id, $table)
	{
		$sql = "DELETE FROM " . $table . " WHERE candidate_id = " . $id;
//		if($table == "candidate_difficulty")
//		{var_dump($sql);exit;}
		$link->query($sql);
	}


	private function sendEmailToCandidate(PDO $link, Candidate $candidate, $email_type='', $candidate_created_by)
	{
		$sender_email = SystemConfig::getEntityValue($link, 'baltic_recruitment_email');
		$headers  = 'MIME-Version: 1.0' . "\r\n";
		$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";

		if(DB_NAME=="am_baltic")
		{
			$headers .= 'From: Baltic Training Services <' . $sender_email . '>' . PHP_EOL .
				'Reply-To: Baltic Training Services <' . $sender_email . '>' . PHP_EOL .
				'X-Mailer: PHP/' . phpversion();
		}
		else
		{
			$headers .= 'From: Sunesis <' . $sender_email . '>' . PHP_EOL .
				'Reply-To: Sunesis <' . $sender_email . '>' . PHP_EOL .
				'X-Mailer: PHP/' . phpversion();
		}

		$recruitment_email = RecruitmentEmail::getInstance($link);

		if($candidate_created_by == 'candidate')
		{
			if(isset($candidate->applications) AND count($candidate->applications) > 0)
			{
				foreach ( $candidate->applications as $vacancy_id )
				{
					if($vacancy_id != '')
					{
						$vacancy = Vacancy::loadFromDatabase($link, $vacancy_id);
						$email_subject = DAO::getSingleValue($link, "SELECT email_subject FROM candidate_email_templates WHERE email_type = '$email_type'");
						$email_template = DAO::getSingleValue($link, "SELECT email_contents FROM candidate_email_templates WHERE email_type = '$email_type'");
						$email_template = str_replace('**CANDIDATE_NAME**', $candidate->firstnames . ' ' . $candidate->surname, $email_template);
						$email_template = str_replace('**JOB_TITLE**', $vacancy->job_title, $email_template);
						$email_template = str_replace('**JOB_REFERENCE**', $vacancy->code, $email_template);
					}
					else
					{
						if($email_type != 'age_inappropriate')
							$email_type = 'candidate_registration_welcome';
						$email_subject = DAO::getSingleValue($link, "SELECT email_subject FROM candidate_email_templates WHERE email_type = '$email_type'");
						$email_template = DAO::getSingleValue($link, "SELECT email_contents FROM candidate_email_templates WHERE email_type = '$email_type'");
						$email_template = str_replace('**CANDIDATE_NAME**', $candidate->firstnames . ' ' . $candidate->surname, $email_template);
					}

					if(mail($candidate->email, $email_subject, $email_template, $headers))
						$this->logAutomaticEmail($link, $candidate, $email_subject, $email_template);
				}
			}
			else
			{
				$email_subject = DAO::getSingleValue($link, "SELECT email_subject FROM candidate_email_templates WHERE email_type = '$email_type'");
				$email_template = DAO::getSingleValue($link, "SELECT email_contents FROM candidate_email_templates WHERE email_type = '$email_type'");
				$email_template = str_replace('**CANDIDATE_NAME**', $candidate->firstnames . ' ' . $candidate->surname, $email_template);

				if(mail($candidate->email, $email_subject, $email_template))
					$this->logAutomaticEmail($link, $candidate, $email_subject, $email_template);
			}
		}
		return true;
	}

	private function saveCandidateInterests(PDO $link, $candidate_id, array $members)
	{
		$sql = "DELETE FROM candidate_sector_choice WHERE candidate_id = ".$candidate_id;
		DAO::execute($link, $sql);

		$data = array();
		foreach($members as $member)
		{
			$data[] = array('candidate_id' => $candidate_id, 'sector' => $member);
		}

		DAO::multipleRowInsert($link, 'candidate_sector_choice', $data);
	}

	private function logAutomaticEmail(PDO $link, Candidate $candidate, $subject, $email_content)
	{
		$vo = new CandidateEmail();
		$vo->candidate_id = $candidate->id;
		$vo->sender_name = htmlspecialchars('Sunesis');
		$vo->sender_email = htmlspecialchars(SystemConfig::getEntityValue($link, 'baltic_recruitment_email'));
		$vo->receiver_name = htmlspecialchars((string)$candidate->firstnames . " " . $candidate->surname);
		$vo->receiver_email = htmlspecialchars((string)$candidate->email);
		$vo->subject = htmlspecialchars((string)$subject);
		$vo->date_sent = date('Y-m-j');
		$vo->time_sent = date("H:i:s");
		$vo->email_body = $email_content;
		$vo->email_html_preview = 'Not Available';
		$vo->sent_from_sunesis = 1;
		$vo->save($link);
	}
}
?>