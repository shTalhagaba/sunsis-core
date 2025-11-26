<?php
/**
 * TODO - change the candidate_metadata to only store one set of values
 * - Matthew Cannon RTTG:
 * -  A candidate may have multiple applications to vacancies,
 * -  but their answers to the screening questions will be the same for every application.
 */
class Candidate extends Entity
{
	public static function loadFromDatabase(PDO $link, $id)	{
		$query = "SELECT * FROM candidate WHERE id=" . addslashes((string)$id) . ";";
		$st = $link->query($query);
		$candidate = null;
		if( $st ) {
			$row = $st->fetch();
			if( $row ) {
				$candidate = new Candidate();
				$candidate->populate($row);
			}
		}
		else {
			throw new DatabaseException($link, $query);
		}

		// populate with the candidate_applications
		$query = "SELECT * from candidate_applications WHERE candidate_id = ".addslashes((string)$id)." and (application_status IS NULL OR application_status != 2)";
		$st = $link->query($query);
		if ( $st ) {
			//doesn't this only do the one then? investigate...
			while( $row = $st->fetch() ) {
				if ( isset($row['vacancy_id']) && $row['vacancy_id'] > 0 ) {
					$candidate->applications[$row['vacancy_id']] = $row['application_screening'];
				}
				$candidate->next_action = $row['next_action_date'];
				$candidate->app_status = $row['application_comments'];
			}
		}
		else {
			throw new DatabaseException($link, $query);
		}

		// populate with the candidate qualifications
		if(DB_NAME=="am_baltic" || DB_NAME=="am_demo" || DB_NAME=="ams")
			$query = "SELECT candidate_qualification.* FROM candidate_qualification WHERE candidate_qualification.candidate_id = ".addslashes((string)$id)."  AND qualification_level != '12' AND qualification_level != '11' OR (qualification_level IS NULL AND candidate_id = $id) ORDER BY candidate_qualification.id ;";
		else
			$query = "SELECT candidate_qualification.* FROM candidate_qualification WHERE candidate_qualification.candidate_id = ".addslashes((string)$id)."  AND qualification_level != 'NVQ' AND qualification_level != 'BTEC' OR (qualification_level IS NULL AND candidate_id = $id) ORDER BY candidate_qualification.id ;";
		$st = $link->query($query);

		if( $st ) {
			while( $edu_row = $st->fetch() ) {
				$candidate->qualifications[] = array(
					'level' => $edu_row['qualification_level'],
					'subject' => $edu_row['qualification_subject'],
//					'grade' => $edu_row['qualification_grade'],
//					'date' => $edu_row['qualification_date']
					'grade' => $edu_row['qualification_grade']

				);
			}
		}
		else {
			throw new DatabaseException($link, $query);
		}

		// populate with the candidate qualifications
		if(DB_NAME=="am_baltic" || DB_NAME=="am_demo" || DB_NAME=="ams")
			$query = "SELECT candidate_qualification.* FROM candidate_qualification WHERE candidate_qualification.candidate_id = ".addslashes((string)$id)."  AND (qualification_level = '12' OR qualification_level = '11') ORDER BY candidate_qualification.id;";
		else
			$query = "SELECT candidate_qualification.* FROM candidate_qualification WHERE candidate_qualification.candidate_id = ".addslashes((string)$id)."  AND (qualification_level = 'NVQ' OR qualification_level = 'BTEC') ORDER BY candidate_qualification.id;";
		$st = $link->query($query);

		if( $st ) {
			while( $edu_row = $st->fetch() ) {
				$candidate->qualificationsOther[] = array(
					'level' => $edu_row['qualification_level'],
					'subject' => $edu_row['qualification_subject'],
					'grade' => $edu_row['qualification_grade'],
					'date' => $edu_row['qualification_date']
				);
			}
		}
		else {
			throw new DatabaseException($link, $query);
		}

		// populate with the candidate history
		$query = "SELECT candidate_history.* FROM candidate_history WHERE candidate_history.candidate_id = ".addslashes((string)$id)." ORDER BY candidate_history.end_date;";
		$st = $link->query($query);

		if( $st ) {
			while( $edu_row = $st->fetch() ) {
				$candidate->employment_history[] = array(
					'start_date' => $edu_row['start_date'],
					'end_date' => $edu_row['end_date'],
					'company_name' => $edu_row['company_name'],
					'job_title' => $edu_row['job_title'],
					'skills' => $edu_row['skills']
				);
			}
		}

		$candidate->populate_metadata($link);
		return $candidate;
	}

	/**
	 *
	 * Enter description here ...
	 * @param PDO $link
	 * @param int $display_tab = tab to present the additional data in 1/2/3/4
	 * @param boolean $set_assessor = 1 if you can enrol to learner from this point / 0 if you can't ( default 0 )
	 */
	public function render_candidate_details( PDO $link, $display_tab = '', $set_assessor = 0 ) {

		if ( '' == $display_tab ) {
			return '';
		}

		// establish row colors
		$row_colors = array(
			'high' => '#E0EAD0',
			'med' => '#FFE6D7',
			'low' => '#FFBFBF'
		);

		//metadata capture types
		// instantiate the user
		$registrant = new User();
		// get the client specific data required for capture
		$registrant->getUserMetaData($link, $this->username);

		$return_html = '';

		$return_html .= '<tr id="detail_'.$display_tab.'_'.$this->id.'" style="display:none;">';
		$return_html .= '<td colspan="10">';
		$return_html .= '<table class="cand_info" >';
		$return_html .= '<tbody>';
		$return_html .= '<tr><td colspan="4" style="background-color: #eee;" ><strong>Candidate Information</strong></td>';

		// re 09/11/2011 - show link to CV if uploaded
		// ----
		$cv_file_link = '&nbsp;';
		if ( file_exists(DATA_ROOT."/uploads/".DB_NAME."/recruitment/".$this->id.".doc") ) {
			$cv_file_link = '<a href="do.php?_action=downloader&f=recruitment/'.$this->id.'.doc">Applicants CV</a> (doc)';
		}
		elseif( file_exists(DATA_ROOT."/uploads/".DB_NAME."/recruitment/".$this->id.".docx") ) {
			$cv_file_link = '<a href="do.php?_action=downloader&f=recruitment/'.$this->id.'.docx">Applicants CV</a> (docx)';
		}
		elseif( file_exists(DATA_ROOT."/uploads/".DB_NAME."/recruitment/".$this->id.".pdf") ) {
			$cv_file_link = '<a href="do.php?_action=downloader&f=recruitment/'.$this->id.'.pdf">Applicants CV</a> (pdf)';
		}
		$return_html .= '<td style="background-color: #eee;" >'.$cv_file_link.'</td>';
		// ----
		$return_html .= '</tr>';
		$return_html .= '<tr>';
		$return_html .= '<td><strong>Date of Birth:</strong> '.$this->dob.'</td>';
		$return_html .= '<td>NI Number: '.$this->national_insurance.'</td>';
		$return_html .= '</tr>';
		$return_html .= '<tr>';
		$return_html .= '<td><strong>Telephone:</strong> '.$this->telephone.'</td>';
		$return_html .= '<td><strong>Email:</strong> '.$this->email.'</td>';
		$return_html .= '</tr>';
		// Support Request 22110: Glenn Jones of Real Time Training Group
		if ( isset($this->mobile) ) {
			$return_html .= '<tr>';
			$return_html .= '<td><strong>Mobile:</strong> '.$this->mobile.'</td>';
			$return_html .= '<td>&nbsp;</td>';
			$return_html .= '</tr>';
		}
		$return_html .= '<tr><td colspan="4" style="background-color: #eee;" ><strong>Candidate Profile</strong></td></tr>';
		$return_html .= '<tr><td colspan="4" >'.nl2br($this->comment).'</tr>';

		// applications
		$has_applications = 0;
		if ( sizeof($this->applications) > 0 ) {
			$return_html .= '<tr><td colspan="4" style="background-color: #eee;" ><strong>Vacancy Applications</strong></td></tr>';
			foreach ( $this->applications as $vacancy_id => $screen_score ) {

				$screen_level = 'low';
				if ( $screen_score >= 45 && $screen_score <= 70 ) {
					$screen_level = 'med';
				}
				else if ( $screen_score >= 70 ) {
					$screen_level = 'high';
				}

				$vacancy_details = DAO::getResultset($link, 'select vacancies.job_title, vacancies.code, vacancies.postcode, vacancies.active, organisations.legal_name from vacancies, organisations where vacancies.id = '.$vacancy_id.' and vacancies.employer_id = organisations.id' );
				if( isset($vacancy_details[0][0]) ) {
					$return_html .= '<tr style="background-color:'.$row_colors[$screen_level].'">';
					$return_html .= '<td><a href="/do.php?_action=view_vacancy&pc='.$vacancy_details[0][2].'&id='.$vacancy_id.'&display='.$this->id	.'">'.$vacancy_details[0][1].'</a></td>';
					$return_html .= '<td colspan="3">'.$vacancy_details[0][4].' - '.$vacancy_details[0][0];
					if ( $vacancy_details[0][3] != 1 ) {
						$return_html .= ' (<strong>inactive</strong>) ';
					}
					else {
						$return_html .= ' (<strong>actively recruiting</strong>) ';
					}
					$return_html .= '</td>';
					$return_html .= '</tr>';
					$has_applications = 1;
				}
			}
		}
		if ( $has_applications == 0 ) {
			$return_html .= '<tr>';
			$return_html .= '<td>No Vacancies Applied For</td><td colspan="3" >&nbsp;</td>';
			$return_html .= '</tr>';
		}

		foreach ( $this->metadata as $section => $section_content ) {
			if ( $section == 'Qualifications' ) {
				if ( sizeof($this->employment_history) > 0 ) {
					$return_html .= '<tr><td colspan="4" style="background-color: #eee;" ><strong>Employment History</strong></td></tr>';
					$return_html .= '<tr style="font-weight: bold" >';
					$return_html .= '<td>Job Description</td><td>Skills</td><td>Start Date</td><td>End Date</td>';
					$return_html .= '</tr>';
					foreach ( $this->employment_history as $edu_pos => $edu_row ) {
						$return_html .= '<tr>';
						$return_html .= '<td>'.$edu_row['company_name'].' - '.$edu_row['job_title'].'</td><td>'.$edu_row['skills'].'</td><td>'.$edu_row['start_date'].'</td><td>'.$edu_row['end_date'].'</td>';
						$return_html .= '</tr>';
					}
				}
				$return_html .= '<tr><td colspan="4" style="background-color: #eee;" ><strong>'.$section.'</strong></td></tr>';
				foreach ( $this->qualifications as $edu_pos => $edu_row ) {
					$return_html .= '<tr>';
					$return_html .= '<td>'.$edu_row['level'].'</td><td>'.$edu_row['subject'].'</td><td>'.$edu_row['grade'].'</td><td>&nbsp;</td>';
					$return_html .= '</tr>';
				}
			}
			else {
				$return_html .= '<tr><td colspan="4" style="background-color: #eee;" ><strong>'.$section.'</strong></td></tr>';
			}


			if ( isset($registrant->user_metadata[$section]) ) {

				$available_questions = $registrant->user_metadata[$section];
				foreach ( $available_questions as $title => $info ) {
					$format_titles = explode("_", $title);
					$format_details = explode("_", $info);
					$return_html .= '<tr>';
					$return_html .= '<td>'.$format_titles[1].'</td>';
					if ( isset($section_content[$format_titles[1]]) ){
						$return_html .= '<td colspan="3">'.$section_content[$format_titles[1]].'</td>';
					}
					else {
						$return_html .= '<td colspan="3">answer not supplied</td>';
					}
					$return_html .= '</tr>';
				}
			}

			//shoehorn employment history in
			if ( $section == 'Qualifications' ) {

			}
		}

		// setting of assessor
		if ( $set_assessor ) {
			$return_html .= '<tr><td colspan="4" style="background-color: #eee;">';
			$return_html .= '<form action="do.php" name="enroll_'.$this->id.'">';
			$return_html .= '<input type="hidden" name="_action" value="convert_candidates" />';
			$return_html .= '<input type="hidden" name="cid" value="'.$this->id.'" />';
			$return_html .= '<input type="hidden" name="id" value="'.$set_assessor.'" />';
			$return_html .= '<strong>Enrol this applicant</strong></td></tr>';
			if(DB_NAME == "am_baltic" || DB_NAME == "am_baltic_demo")
			{
				if($this->status_code == 18)
				{
					$return_html .= '<tr><td>Proposed Assessor</td><td>';
					// move all this post haste!!
					$assessor_sql = <<<HEREDOC
		SELECT
			username,
			CONCAT(firstnames, ' ', surname),
			NULL
		FROM
			users
		INNER JOIN organisations on organisations.id = users.employer_id
		where type=3
HEREDOC;
					$assessor_select = DAO::getResultset($link, $assessor_sql);
					$return_html .= HTML::select('assessor', $assessor_select, '', true, false);
					$return_html .= '</td></tr>';
				}
				if($this->status_code != 18)
					$return_html .= '<tr><td></td><td><a href="#" onclick="javascript:send_to_employer('.$this->id.');">Move to Employer Selection</a></form></td></tr>';
				else
					$return_html .= '<tr><td></td><td><a href="#" onclick="javascript:enroll_'.$this->id.'.submit();">enrol candidate</a></form></td></tr>';
			}
			else
			{
				$return_html .= '<tr><td>Proposed Assessor</td><td>';
				// move all this post haste!!
				$assessor_sql = <<<HEREDOC
SELECT
	username,
	CONCAT(firstnames, ' ', surname),
	NULL
FROM
	users
INNER JOIN organisations on organisations.id = users.employer_id
where type=3
HEREDOC;
				$assessor_select = DAO::getResultset($link, $assessor_sql);
				$return_html .= HTML::select('assessor', $assessor_select, '', true, false);
				$return_html .= '</td></tr>';
				$return_html .= '<tr><td></td><td><a href="#" onclick="javascript:enroll_'.$this->id.'.submit();">enrol candidate</a></form></td></tr>';
			}

			/*
						$links = DAO::getResultset($link, "select firstnames, surname, ni, uln,id from users where type = 5 and firstnames = '$this->firstnames' and surname = '$this->surname'");
						$index = 0;
						foreach($links as $existing)
						{
							$index++;
							if($index==1)
							   $return_html .= '<tr><td>Potential Links Found</td></tr>';
							$return_html .= '<tr><td><form action="do.php" name="link_'.$existing[4].'">';
							$return_html .= '<input type="hidden" name="_action" value="convert_candidates" />';
							$return_html .= '<input type="hidden" name="lid" value="'.$existing[4].'" />';
							$return_html .= $existing[0] . ' ' . $existing[1] . ' ' . $existing[2] . '</td><td><a href="#" onclick="javascript:link_'.$existing[4].'.submit();">Link candidate</a></form></td></tr>';
						}
						*/

		}

		// output the full candidate history	
		$return_html .= $this->candidate_notes->render($link);
		$return_html .= '</tbody>';
		$return_html .= '</table>';
		$return_html .= '</td>';
		$return_html .= '</tr>';

		return $return_html;
	}


	public function render_candidate_form( PDO $link, $display_tab = '', $vacancy_id = '' ) {

		// establish row colors
		$row_colors = array(
			'high' => '#E0EAD0',
			'med' => '#FFE6D7',
			'low' => '#FFBFBF'
		);

		// new candidate flag
		$new_candidate_registration = isset($this->id) ? 0 : 1;
		if ( '' == $display_tab ) { // || '' == $vacancy_id ) {
			return;
		}

		// date drop down populations
		$day = array(array('','dd'),array('01',1),array('02',2),array('03',3),array('04',4),array('05',5),array('06',6),array('07',7),array('08',8),array('09',9),array('10',10),array('11',11),array('12',12),array(13,13),array(14,14),array(15,15),array(16,16),array(17,17),array(18,18),array(19,19),array(20,20),array(21,21),array(22,22),array(23,23),array(24,24),array(25,25),array(26,26),array(27,27),array(28,28),array(29,29),array(30,30),array(31,31));
		$month = array(array('','mon'),array('01','Jan'),array('02','Feb'),array('03','Mar'),array('04','Apr'),array('05','May'),array('06','Jun'),array('07','Jul'),array('08','Aug'),array('09','Sep'),array(10,'Oct'),array(11,'Nov'),array(12,'Dec'));
		$year = array(array('','yyyy'));
		for( $a = 2016; $a>=1960; $a-- ) {
			$year[] = array($a,$a);
		}
		$candidate_dob = explode('-', $this->dob);

		//metadata capture types
		// instantiate the user
		$registrant = new User();
		// get the client specific data required for capture
		$registrant->getUserMetaData($link);

		$return_html = '';
		$return_html .= '<div class="block"><form action="do.php" method="post" name="screen_'.$this->id.'" id="screen_'.$this->id.'" enctype="multipart/form-data">';
		$return_html .= '<input type="hidden" name="candidate_id" value="'.$this->id.'" />';
		if ( $new_candidate_registration ) {
			$return_html .= '<input type="hidden" name="_action" value="save_candidate" />';
			$enrolled_value = "";
			if ( $vacancy_id != '' ) {
				$enrolled_value = 1;
			}
			$return_html .= '<input type="hidden" name="enrolled" value="'.$enrolled_value.'" />';
			$return_html .= '<input type="hidden" name="applications[]" value="'.$vacancy_id.'" />';
		}
		else {
			$return_html .= '<input type="hidden" name="_action" value="update_candidate" />';
			$return_html .= '<input type="hidden" name="id" value="'.$vacancy_id.'" />';
		}
		$return_html .= '<input type="hidden" name="tab" value="'.$display_tab.'" />';
		$return_html .= '<input type="hidden" name="hascomefrom" value="'.$_REQUEST['_action'].'" />';
		$return_html .= '<input type="hidden" name="pc" value="" />';
		$return_html .= '<table class="cand_info">';
		$return_html .= '<tbody>';
		if ( !$new_candidate_registration ) {
			if(DB_NAME=="am_baltic_demo" || DB_NAME=="am_demo" || DB_NAME=="am_baltic")
			{
				$photopath = $this->getPhotoPath();
				if($photopath)
				{
					$photopath = "do.php?_action=display_image&username=".rawurlencode($this->username)."&candidate_id=".$this->id;
				}
				else
				{
					$photopath = "/images/no_photo.png";
				}

				//$return_html .= '<tr><td colspan="4"><img align="center" id="pic" height="160" alt="Photograph" border="2" src="'.$photopath.'" /></td></tr>';
			}
			$return_html .= '<tr><td colspan="4" style="background-color: #eee;" ><strong>Update Applicant Status</strong> ['.$this->app_status.']</td></tr>';
			$return_html .= '<tr id="app_comment_'.$this->id.'" >';
			$return_html .= '<td>Select the status that best describes this application:</td>';
			$return_html .= '<td><select id="app_note_'.$this->id.'">';
			$return_html .= '<option value="">Please select...</option>';
			$selected = $this->app_status == 'Awaiting Screening'?'selected': ' ';
			$return_html .= '<option ' . $selected . ' value="Awaiting Screening">Awaiting Screening</option>';
			$selected = $this->app_status == 'Requested CV'?'selected': ' ';
			$return_html .= '<option ' . $selected . ' value="Requested CV">Requested CV</option>';
			$selected = $this->app_status == 'Unable to speak to candidate'?'selected': ' ';
			$return_html .= '<option ' . $selected . ' value="Unable to speak to candidate">Unable to speak to candidate</option>';
			$selected = $this->app_status == 'CV Received'?'selected': ' ';
			$return_html .= '<option ' . $selected . ' value="CV Received">CV Received</option>';
			$selected = $this->app_status == 'Screened'?'selected': ' ';
			$return_html .= '<option ' . $selected . ' value="Screened">Screened</option>';
			$return_html .= '</select></td>';
			$return_html .= '<td colspan="2">&nbsp;</td>';
			$return_html .= '</tr>';
			$return_html .= '<tr>';
			$return_html .= '<td>Next Action Date:</td>';
			$return_html .= '<td>';
			// $candidate->next_action 
			$next_action = array(date('Y'), date('m'), date('j')+1);
			if ( isset($this->next_action) ) {
				$next_action = preg_split("/-/", $this->next_action);
			}
			$return_html .= HTML::select('nad_day_'.$this->id, $day, $next_action[2], false, true);
			$return_html .= HTML::select('nad_month_'.$this->id, $month, $next_action[1], false, true);
			$return_html .= HTML::select('nad_year_'.$this->id, $year, $next_action[0], false, true);
			$return_html .= '</td>';
			$return_html .= '<td>&nbsp;</td>';
			if ( is_int($vacancy_id) ) {
				$return_html .= '<td><a href="#" onclick="save_cand_status('.$this->id.', '.$vacancy_id.');" >save applicant action</a></td>';
			}
			else {
				$return_html .= '<td><a href="#" onclick="save_cand_status('.$this->id.');" >save applicant action</a></td>';
			}
			$return_html .= '</tr>';
		}
		$return_html .= '<tr><td colspan="4" style="background-color: #eee;" ><strong>Candidate Information</strong></td>';
		// re 09/11/2011 - show link to CV if uploaded
		// ----
		$cv_file_link = '&nbsp;';
		$cv_file_link_2 = '&nbsp;';
		if(DB_NAME == "am_baltic_demo" || DB_NAME == "am_demo" || DB_NAME=="am_baltic")
		{
			if ( file_exists(DATA_ROOT."/uploads/".DB_NAME."/recruitment/cv_1_".$this->id.".doc") ) {
				$cv_file_link = '<a href="do.php?_action=downloader&path=/recruitment/&f=cv_1_'.$this->id.'.doc">Applicants CV</a> (doc)';
			}
			elseif( file_exists(DATA_ROOT."/uploads/".DB_NAME."/recruitment/cv_1_".$this->id.".docx") ) {
				$cv_file_link = '<a href="do.php?_action=downloader&path=/recruitment&f=cv_1_'.$this->id.'.docx">Applicants CV</a> (docx)';
			}
			elseif( file_exists(DATA_ROOT."/uploads/".DB_NAME."/recruitment/cv_1_".$this->id.".pdf") ) {
				$cv_file_link = '<a href="do.php?_action=downloader&path=/recruitment/&f=cv_1_'.$this->id.'.pdf">Applicants CV</a> (pdf)';
			}
			if ( file_exists(DATA_ROOT."/uploads/".DB_NAME."/recruitment/cv_2_".$this->id.".doc") ) {
				$cv_file_link_2 = '<a href="do.php?_action=downloader&path=/recruitment/&f=cv_2_'.$this->id.'.doc">Applicants CV</a> (doc)';
			}
			elseif( file_exists(DATA_ROOT."/uploads/".DB_NAME."/recruitment/cv_2_".$this->id.".docx") ) {
				$cv_file_link_2 = '<a href="do.php?_action=downloader&path=/recruitment&f=cv_2_'.$this->id.'.docx">Applicants CV</a> (docx)';
			}
			elseif( file_exists(DATA_ROOT."/uploads/".DB_NAME."/recruitment/cv_2_".$this->id.".pdf") ) {
				$cv_file_link_2 = '<a href="do.php?_action=downloader&path=/recruitment/&f=cv_2_'.$this->id.'.pdf">Applicants CV</a> (pdf)';
			}
			if($cv_file_link == '&nbsp;')
				$cv_file_link = 'CV Not Provided';
			if($cv_file_link_2 == '&nbsp;')
				$cv_file_link_2 = 'CV Not Provided';
		}
		else
		{
			if ( file_exists(DATA_ROOT."/uploads/".DB_NAME."/recruitment/".$this->id.".doc") ) {
				$cv_file_link = '<a href="do.php?_action=downloader&path=/recruitment/&f='.$this->id.'.doc">Applicants CV</a> (doc)';
			}
			elseif( file_exists(DATA_ROOT."/uploads/".DB_NAME."/recruitment/".$this->id.".docx") ) {
				$cv_file_link = '<a href="do.php?_action=downloader&path=/recruitment&f='.$this->id.'.docx">Applicants CV</a> (docx)';
			}
			elseif( file_exists(DATA_ROOT."/uploads/".DB_NAME."/recruitment/".$this->id.".pdf") ) {
				$cv_file_link = '<a href="do.php?_action=downloader&path=/recruitment/&f='.$this->id.'.pdf">Applicants CV</a> (pdf)';
			}
		}
		$mock_file_link = '&nbsp;';
		if ( file_exists(DATA_ROOT."/uploads/".DB_NAME."/recruitment/mock_".$this->id.".doc") ) {
			$mock_file_link = '<a href="do.php?_action=downloader&path=/recruitment/&f=mock_'.$this->id.'.doc">Applicants Mock Interview</a> (doc)';
		}
		elseif( file_exists(DATA_ROOT."/uploads/".DB_NAME."/recruitment/mock_".$this->id.".docx") ) {
			$mock_file_link = '<a href="do.php?_action=downloader&path=/recruitment&f=mock_'.$this->id.'.docx">Applicants Mock Interview</a> (docx)';
		}
		elseif( file_exists(DATA_ROOT."/uploads/".DB_NAME."/recruitment/mock_".$this->id.".pdf") ) {
			$mock_file_link = '<a href="do.php?_action=downloader&path=/recruitment/&f=mock_'.$this->id.'.pdf">Applicants Mock Interview</a> (pdf)';
		}
		$notes_file_link = '&nbsp;';
		if ( file_exists(DATA_ROOT."/uploads/".DB_NAME."/recruitment/notes_".$this->id.".doc") ) {
			$notes_file_link = '<a href="do.php?_action=downloader&path=/recruitment/&f=notes_'.$this->id.'.doc">Applicants Interview Notes</a> (doc)';
		}
		elseif( file_exists(DATA_ROOT."/uploads/".DB_NAME."/recruitment/notes_".$this->id.".docx") ) {
			$notes_file_link = '<a href="do.php?_action=downloader&path=/recruitment&f=notes_'.$this->id.'.docx">Applicants Interview Notes</a> (docx)';
		}
		elseif( file_exists(DATA_ROOT."/uploads/".DB_NAME."/recruitment/notes_".$this->id.".pdf") ) {
			$notes_file_link = '<a href="do.php?_action=downloader&path=/recruitment/&f=notes_'.$this->id.'.pdf">Applicants Interview Notes</a> (pdf)';
		}

		if(DB_NAME == "am_baltic_demo" || DB_NAME == "am_demo" || DB_NAME=="am_baltic")
		{
			if($mock_file_link == '&nbsp;')
				$mock_file_link = 'Mock Interview Not Provided';
			if($notes_file_link == '&nbsp;')
				$notes_file_link = 'Interview Notes Not Provided';
		}
		//$return_html .= '<td style="background-color: #eee;" >&nbsp;</td>';
		// ----
		$return_html .= '</tr>';

		// is this a new candidate registration
		if ( $new_candidate_registration ) {
			$return_html .= '<tr>';
			$return_html .= '	<td class="" style="text-align: left;"><strong>First Name(s): *</strong></td>';
			$return_html .= '	<td><input class="compulsory" type="text" name="firstnames"  size="40" maxlength="100"/> </td>';
			$return_html .= '	<td class="" style="text-align: left;"><strong>Family Name: *</strong></td>';
			$return_html .= '	<td><input class="compulsory" type="text" name="surname"  size="40" maxlength="100"/></td>';
			$return_html .= '</tr>';
			$return_html .= '<tr>';
			$return_html .= '	<td class="" style="text-align: left;"><strong>Gender: *</strong></td>';
			$return_html .= '	<td style="text-align: left;">';
			$gender = "SELECT id, description, null FROM lookup_gender;";
			$gender = DAO::getResultset($link, $gender);
			array_unshift($gender,array('','Please select one',''));
			$return_html .= HTML::select('gender', $gender, '', true, true);
			$return_html .= '	</td>';
			$return_html .= '	<td class=""><strong>Ethnicity: *</strong></td>';
			$return_html .= '	<td>';
			$L12_dropdown = DAO::getResultset($link,"SELECT Ethnicity, CONCAT(Ethnicity, ' ', Ethnicity_Desc), null from lis201213.ilr_ethnicity order by Ethnicity;");
			array_unshift($L12_dropdown,array('','Please select one',''));
			$return_html .= HTML::select('ethnicity', $L12_dropdown, '', true, true);
			$return_html .= '	</td>';
			$return_html .= '</tr>';
		}

		$return_html .= '<tr>';
		$return_html .= '<td><strong>Date of Birth: *</strong></td><td>';
		$return_html .= HTML::select('dob_day', $day, $candidate_dob[2], false, true);
		$return_html .= HTML::select('dob_month', $month, $candidate_dob[1], false, true);
		$return_html .= HTML::select('dob_year', $year, $candidate_dob[0], false, true);
		$return_html .= '</td>';
		$return_html .= '<td>NI Number:</td><td><input type="text" name="national_insurance" value="'.$this->national_insurance.'" id="national_insurance" maxlength="13"/></td>';
		$return_html .= '</tr>';
		// ---
		if ( $cv_file_link == '&nbsp;') {
			if( DB_NAME != "am_baltic_demo" && DB_NAME != "am_demo"  || DB_NAME=="am_baltic") {
				$return_html .= '<tr>';
				$return_html .= '<td>Upload a CV:</td><td colspan="3" >';
				$return_html .= '<input type="file" name="uploadedfile" id="uploadedfile" />';
				$return_html .= '</td>';
				$return_html .= '</tr>';
			}
		}
		else {
			if( DB_NAME == "am_baltic_demo" || DB_NAME == "am_demo"  || DB_NAME=="am_baltic") {
			}
			else {
				$return_html .= '<tr>';
				$return_html .= '<td>CV:</td><td colspan="3" >';
				$return_html .= $cv_file_link;
				$return_html .= '</td>';
				$return_html .= '</tr>';
				$return_html .= '<tr>';
				$return_html .= '<td>Upload a New CV:</td><td colspan="3" >';
				$return_html .= '<input type="file" name="uploadedfile" id="uploadedfile" />';
				$return_html .= '</td>';
				$return_html .= '</tr>';
			}
		}
		// ---
		// ---
		if(DB_NAME!="am_demo" && DB_NAME!="am_baltic_demo" && DB_NAME!="am_baltic")
		{

			if ( $mock_file_link == '&nbsp;' ) {
				$return_html .= '<tr>';
				$return_html .= '<td>Upload mock interview:</td><td colspan="3" >';
				$return_html .= '<input type="file" name="uploadedmockfile" id="uploadedmockfile" />';
				$return_html .= '</td>';
				$return_html .= '</tr>';
			}
			else {
				$return_html .= '<tr>';
				$return_html .= '<td>Mock interview:</td><td colspan="3" >';
				$return_html .= $mock_file_link;
				$return_html .= '</td>';
				$return_html .= '</tr>';
			}
			// ---
			// ---
			if ( $notes_file_link == '&nbsp;' ) {

				$return_html .= '<tr>';
				$return_html .= '<td>Upload interview notes:</td><td colspan="3" >';
				$return_html .= '<input type="file" name="uploadednotesfile" id="uploadednotesfile" />';
				$return_html .= '</td>';
				$return_html .= '</tr>';
			}
			else {
				$return_html .= '<tr>';
				$return_html .= '<td>Interview notes:</td><td colspan="3" >';
				$return_html .= $notes_file_link;
				$return_html .= '</td>';
				$return_html .= '</tr>';
			}
			// ---
		}
		$return_html .= '</tr>';
		if(DB_NAME=="am_demo" || DB_NAME=="am_baltic_demo" || DB_NAME=="am_baltic")
		{
			if ( $new_candidate_registration )
				$return_html .= '<tr><td>Candidate Source: </td><td>' . HTML::select('source', DAO::getResultset($link, "SELECT id, description FROM lookup_source ORDER BY description"),'',true,false) . '</td></tr>';
			else
				$return_html .= '<tr><td>Candidate Source: </td><td>' . HTML::select('source', DAO::getResultset($link, "SELECT id, description FROM lookup_source ORDER BY description"),$this->source,true,true) . '</td></tr>';
			if ( $new_candidate_registration )
				$return_html .= '<tr><td>Candidate Status: </td><td>' . HTML::select('status_code', DAO::getResultset($link, "SELECT id, description FROM lookup_candidate_status ORDER BY description"),'',true,false) . '</td></tr>';
			else
				$return_html .= '<tr><td>Candidate Status: </td><td>' . HTML::select('status_code', DAO::getResultset($link, "SELECT id, description FROM lookup_candidate_status ORDER BY description"),$this->status_code,true,true) . '</td></tr>';
		}

		$return_html .= '<tr><td colspan="4" style="background-color: #eee;" ><strong>Contact Details</strong></td></tr>';
		$return_html .= '	<tr>';
		if(DB_NAME=="am_demo" || DB_NAME=="am_baltic_demo" || DB_NAME=="am_baltic")
		{
			$return_html .= '		<td class="compulsory"><strong>Building No/Name & Street: *</strong></td>';
			$return_html .= '		<td><input class="compulsory" type="text" name="address1" maxlength="100" value="'. $this->address1 .'" /></td>';
		}
		else
		{
			$return_html .= '		<td class="">House name:</td>';
			$return_html .= '		<td><input type="text" name="address1" maxlength="100" value="'. $this->address1 .'" /></td>';
		}
		if(DB_NAME=="am_demo" || DB_NAME=="am_baltic_demo" || DB_NAME=="am_baltic")
			$return_html .= '		<td class=""><strong>Suburb/Village: *</strong></td>';
		else
			$return_html .= '		<td class=""><strong>Street and number: *</strong></td>';
		$return_html .= '		<td><input class="compulsory" type="text" name="address2" maxlength="100" value="' .' '. $this->address2.'" /></td>';
		$return_html .= '	</tr>';
		$return_html .= '	<tr>';
		$return_html .= '		<td class="">';
		$return_html .= '			<strong>Town: *</strong>';
		$return_html .= '		</td>';
		$return_html .= '		<td>';
		$return_html .= '		<input class="compulsory" type="text" name="borough" maxlength="100" value="'.$this->borough.'" />';
		$return_html .= '		</td>';
		$return_html .= '		<td class=""><strong>County: *</strong></td>';
		$sql = "SELECT description, description, NULL FROM central.lookup_counties GROUP BY description ORDER BY description ASC;";
		$counties = DAO::getResultSet($link, $sql);
		$return_html .= '		<td>' . HTML::select("county", $counties, $this->county, true, true) . '</td>';
		//$return_html .= '		<td><input class="compulsory" type="text" id="county" name="county" maxlength="100" value="'.$this->county.'" /></td>';
		$return_html .= '	</tr>';
		$return_html .= '	<tr>';
		// add in check for postcode goelocation validity here
		if ( isset($this->postcode) && $this->latitude === NULL ) {
			$return_html .= '		<td class="" style="color: #FF0000">Unrecognised Postcode: * (<a href="http://maps.google.co.uk/maps?q='.$this->address2.'+'.$this->county.'&hl=en" target="_blank">lookup address using google maps</a>)</td>';
			$return_html .= '		<td><input class="compulsory" type="text" name="postcode" id="postcode" maxlength="100" value="'.$this->postcode.'" style="color: #FF0000" /></td>';
		}
		else {
			$return_html .= '		<td class=""><strong>Postcode: *</strong></td>';
			$return_html .= '		<td><input class="compulsory" type="text" name="postcode" id="postcode" maxlength="8" value="'.$this->postcode.'" /></td>';
		}
		$return_html .= '		<td class="">Region</td>';
		$return_html .= '		<td>';
		// $region_dropdown = array(array('North West','North West',''), array('North East','North East',''), array('Midlands','Midlands',''), array('East Midlands','East Midlands',''), array('West Midlands','West Midlands',''), array('London North','London North',''), array('London South','London South',''), array('Peterborough','Peterborough',''), array('Yorkshire','Yorkshire',''));
		$region_dropdown = 'select description, description, null from lookup_vacancy_regions order by description;';
		$region_dropdown = DAO::getResultset($link, $region_dropdown);

		$return_html .= HTML::select('region', $region_dropdown, $this->region, true, false);
		$return_html .= '		</td>';
		$return_html .= '	</tr>';
		$return_html .= '<tr>';
		$return_html .= '<td>Telephone:</td><td><input type="text" name="telephone" value="'.$this->telephone.'" maxlength="20"/></td>';
		$return_html .= '<td>Email:</td><td>';
		if ( $new_candidate_registration ) {
			$return_html .= '<input type="text" name="email" value="'.$this->email.'" id="email" />';
		}
		else {
			$return_html .= '<input type="text" name="email" value="'.$this->email.'" id="email" />';
			//$return_html .= '<a href="mailto:'.$this->email.'">'.$this->email.'</a>';
		}
		$return_html .= '</td>';
		$return_html .= '</tr>';
		$return_html .= '	<tr>';
		$return_html .= '		<td class=""><strong>Mobile: *</strong></td>';
		$return_html .= '		<td><input class="compulsory" type="text" name="mobile" id="mobile" maxlength="20" value="'.$this->mobile.'" /></td>';
		$return_html .= '		<td class="">Fax:</td>';
		$return_html .= '		<td><input type="text" name="fax" id="fax" maxlength="20" value="'.$this->fax.'" /></td>';
		$return_html .= '	</tr>';

		$return_html .= $this->present_screen_qualifications($link);
		$return_html .= $this->present_screen_employment();

		$return_html .= '<tr><td colspan="4" style="background-color: #eee;" ><strong>Candidate Profile</strong></tr>';
		$return_html .= '<tr><td colspan="4" ><textarea name="comment" style="width:98%" >'.$this->comment.'</textarea></tr>';

		// applications
		if ( !$new_candidate_registration ) {
			$return_html .= '<tr><td colspan="4" style="background-color: #eee;" ><strong>Vacancy Applications</strong>&nbsp;<a href="#">&raquo;</a></td></tr>';
			$has_applications = 0;

			foreach ( $this->applications as $other_vac_id => $screen_score ) {

				$screen_level = 'low';
				if ( $screen_score >= 45 && $screen_score <= 70 ) {
					$screen_level = 'med';
				}
				else if ( $screen_score >= 70 ) {
					$screen_level = 'high';
				}

				$vacancy_details = DAO::getResultset($link, 'select vacancies.job_title, vacancies.code, vacancies.postcode, vacancies.active, organisations.legal_name from vacancies, organisations where vacancies.id = '.$other_vac_id.' and vacancies.employer_id = organisations.id' );
				if( isset($vacancy_details[0][0]) ) {
					if ( $vacancy_id == $other_vac_id ) {
						$return_html .= '<tr>';
						$return_html .= '<td><strong>'.$vacancy_details[0][0].'</strong> (current vacancy)</td>';

					}
					else {
						$return_html .= '<tr style="background-color:'.$row_colors[$screen_level].'">';
						$return_html .= '<td><a href="/do.php?_action=view_vacancy&pc='.$vacancy_details[0][2].'&id='.$other_vac_id.'&display='.$this->id	.'">'.$vacancy_details[0][0].'</a></td>';

					}
					$return_html .= '<td colspan="2" >'.$vacancy_details[0][4];
					if ( $vacancy_details[0][3] != 1 ) {
						$return_html .= ' (<strong>inactive</strong>) ';
					}
					else {
						$return_html .= ' (<strong>actively recruiting</strong>) ';
					}
					$return_html .= '</td>';
					$return_html .= '<td>'.$vacancy_details[0][2].'</td>';
					$return_html .= '</tr>';
					$has_applications = 1;
				}
			}
			if ( $has_applications == 0 ) {
				$return_html .= '<tr>';
				$return_html .= '<td>No Vacancies Applied For</td><td colspan="3" >&nbsp;</td>';
				$return_html .= '</tr>';
			}
		}

		// find the sector type to display if required
		$screening_section = '';
		if ( $new_candidate_registration && $vacancy_id != '' ) {
			$sector_type = DAO::getSingleValue($link, 'SELECT lookup_vacancy_type.description FROM vacancies LEFT JOIN lookup_vacancy_type ON vacancies.type = lookup_vacancy_type.id WHERE vacancies.id = '.$vacancy_id);
			$screening_section = 'Additional Information - '.$sector_type;
		}

		// need to refresh the metadata specfic for vacancy		
		$test = array();
		$test = $this->metadata;

		$this->populate_metadata($link,$vacancy_id);

		foreach ( $this->metadata as $section => $section_content ) {
			if ( $new_candidate_registration && preg_match('/^Additional Information - /', $section) ) {
				if( $screening_section != '' && $screening_section != $section ) {
					continue;
				}
			}

			$return_html .= '<tr><td colspan="4" style="background-color: #eee;" ><strong>'.$section.'</strong></td>';
			$return_html .= '</tr>';
			$arr = array(127,128,129,138,145,152,158,164);
			$available_questions = $registrant->user_metadata[$section];
			foreach ( $available_questions as $title => $info ) {
				$format_titles = explode("_", $title);
				$format_details = explode("_", $info);
				$return_html .= '<tr>';
				$return_html .= '<td>'.$format_titles[1].'</td><td colspan=3>';
				if ( isset($section_content[$format_titles[1]]) ){
					// re 23/08/2011
					// not sure why the format_titles[1] is included at the start
					// $return_html .= $format_titles[1].': <textarea name="reg_'.$format_titles[0].'" style="width:98%" >'.$section_content[$format_titles[1]].'</textarea></td>';
					if(in_array($format_titles[0], $arr))
						$return_html .= '<textarea rows = "6" name="reg_'.$format_titles[0].'" style="width:98%" >'.$section_content[$format_titles[1]].'</textarea></td>';
					else
						$return_html .= '<textarea name="reg_'.$format_titles[0].'" style="width:98%" >'.$section_content[$format_titles[1]].'</textarea></td>';
				}
				else {
					if(in_array($format_titles[0], $arr))
						$return_html .= '<textarea rows = "6" name="reg_'.$format_titles[0].'" style="width:98%" >answer not supplied</textarea></td>';
					else
						$return_html .= '<textarea name="reg_'.$format_titles[0].'" style="width:98%" >answer not supplied</textarea></td>';
				}
				$return_html .= '</tr>';
			}
		}
		if ( $new_candidate_registration && $vacancy_id == '' )
		{
			$return_html .= '<tr><td colspan="4" style="background-color: #eee;" ><strong>Save Application</strong></td></tr>';
			$return_html .= '<tr><td colspan="4" style="text-align: center;" >';
			$return_html .= '<input type="button" name="screen_as_candidate" class="screen_button as_candidate" value="Save Candidate" onclick="setscreening(\'0\', \''.$this->id.'\');" />';
			$return_html .= '<input type="hidden" name="screening_score" value="" />';

			$return_html .= '</td></tr>';
		}
		else
		{
			$return_html .= '<tr><td colspan="4" style="background-color: #eee;" ><strong>Grade Applicant and Save Screening </strong></td></tr>';
			$return_html .= '<tr><td colspan="4" style="text-align: center;" >';
			$return_html .= '<input type="button" name="screen_green" class="screen_button green" value="Save as a Green Applicant" onclick="setscreening(\'100\', \''.$this->id.'\');" />';
			$return_html .= '<input type="button" name="screen_amber" class="screen_button amber" value="Save as an Amber Applicant" onclick="setscreening(\'65\', \''.$this->id.'\');" />';
			$return_html .= '<input type="button" name="screen_red" class="screen_button red" value="Save as a Red Applicant" onclick="setscreening(\'0\', \''.$this->id.'\');" />';
			$return_html .= '<input type="hidden" name="screening_score" value="" />';
			// $return_html .= '<input type="submit" name="update candidate" value="Save Candidate Screening" />';
			// re: 06/09/2011 IE rendering issue
			// $return_html .= '</form>';

			$return_html .= '</td></tr>';
		}
		$return_html .= '<input type="hidden" name="candidate_created_by" value="sunesis_user" />'; //to ask if they want to send email to the candidate

		if ( !$new_candidate_registration ) {
			// output the full candidate history	
			$return_html .= $this->candidate_notes->render($link);
		}

		$return_html .= '</tbody>';
		$return_html .= '</table>';

		// re: 06/09/2011 IE rendering issue
		$return_html .= '</form></div>';

		return $return_html;
	}



	private function checkDuplicate(PDO $link)
	{
		if ( "" != $this->national_insurance ) {
			$query = "select id from candidate where national_insurance = '".addslashes((string)$this->national_insurance)."';";
		}
		else {
			$query = "select id from candidate where firstnames = '".addslashes((string)$this->firstnames)."' and surname = '".addslashes((string)$this->surname)."' and postcode = '".addslashes((string)$this->postcode)."';";
		}
		$st = $link->query($query);
		$candidate = null;
		if( $st ) {
			$row = $st->fetch();
			// there is a duplicate candidate on the system
			// let the requestor know
			if( $row ) {
				return 1;
			}
		}
		// this is a clean candidate registration
		return 0;
	}

	public function save(PDO $link)
	{
		if($this->id == '') {
			// check for a duplicate candidate
			if ( $this->checkDuplicate($link) ) {
				return false;
			}
			// New record
			$this->created = ''; // DAO will save this as NULL, which results in a fresh timestamp
		}
		else {
			// Check for changes
			$existing_record = Candidate::loadFromDatabase($link, $this->id);
		}

		// Set up Geo location
		// relmes - do a check if the postcode has been included.
		//        - should be a required field?
		if ( isset($this->postcode) ) {
			$loc = new GeoLocation();
			// RE - added passing of PDO to allow for location storage
			$loc->setPostcode($this->postcode, $link);
			$this->longitude = $loc->getLongitude();
			$this->latitude = $loc->getLatitude();
			$this->easting = $loc->getEasting();
			$this->northing = $loc->getNorthing();
		}

		if(isset($this->extra_support_for_app) && $this->extra_support_for_app != '' && strlen($this->extra_support_for_app) > 999)
			$this->extra_support_for_app = substr($this->extra_support_for_app,0,999);

		DAO::saveObjectToTable($link, 'candidate', $this);

		$cand_application_sql = '';
		// add in the save to candidate_applications table
		// if it has moved onto approval 

		// -----
		// re 10/08/2011 - here is the problem......
		// -----
		// the status and enrolled values are true of the candidate
		// even when saving on an empty data set
		// $this->enrolled needs to be looked at
		// #TODO: enrolled is a legacy flag from initial spec 1 vacancy 1 applicant set up, needs to be removed from functionality

		if( isset($_REQUEST['id']) && is_numeric($_REQUEST['id']) ) {
			$this->enrolled = $_REQUEST['id'];
		}
		// just a test here
		elseif( isset($_REQUEST['hascomefrom']) && $_REQUEST['hascomefrom'] == 'new_candidate' ) {
			$this->enrolled = 1;
		}
		else {
			$this->enrolled = NULL;
		}

		// this is new addition to the vacancy
		// use the action to determine if it needs to set up all
		// the data 
		// if ( 1 == $this->status && $this->enrolled > 1 ) {
		if ( $_REQUEST['_action'] == 'fill_vacancy' && $this->enrolled >= 1 )
		{
			//excuted when from direct candidate list approve button is pressed
			$cand_application_sql = 'REPLACE INTO candidate_applications ( candidate_id, vacancy_id, application_status, application_screening, has_been_screened ) VALUES ( '.$this->id.', '.$this->enrolled.', '.$this->status.', '.$this->screening_score.', 1 )';
			DAO::execute($link, $cand_application_sql);

			// $cand_metadata_sql = 'REPLACE INTO candidate_metadata (userinfoid, candidateid, stringvalue, intvalue, datevalue, floatvalue, vacancy_id) ';
			// $cand_metadata_sql .= 'SELECT userinfoid, candidateid, stringvalue, intvalue, datevalue, floatvalue, '.$this->enrolled.' FROM candidate_metadata WHERE candidateid = '.$this->id.' AND vacancy_id IS NULL';

			// DAO::execute($link, $cand_metadata_sql);
		}
		elseif ( $this->enrolled >= 1 && is_numeric($this->screening_score) )
		{
			// this applications contains a 0 key value......!!! change
			if ( sizeof($this->applications) > 0 ) {
				foreach ( $this->applications as $appli_id ) {
					if ( is_numeric($appli_id) && $appli_id != 0 ) {
						$cand_application_sql = 'REPLACE INTO candidate_applications ( candidate_id, vacancy_id, application_screening, has_been_screened ) VALUES ( '.$this->id.', '.$appli_id.', '.$this->screening_score.', 0 )';
						DAO::execute($link, $cand_application_sql);
					}
				}
			}
			else if ( is_numeric($this->enrolled) ) {// executed when you select vacancy and search candidate and screen the candidate against the seleced vacancy
				$cand_application_sql = 'REPLACE INTO candidate_applications ( candidate_id, vacancy_id, application_screening, has_been_screened ) VALUES ( '.$this->id.', '.$this->enrolled.', '.$this->screening_score.', 1 )';
				DAO::execute($link, $cand_application_sql);
			}
		}
		else
		{
			if ( !is_numeric($this->screening_score) )
			{
				$this->screening_score = 0;
			}
			// executed when you view the candidate and click screen and press either of the three save buttons
			$cand_application_sql = 'update candidate_applications set application_screening = '.$this->screening_score.', has_been_screened = 1 where candidate_id = '.$this->id;
			DAO::execute($link, $cand_application_sql);
		}

		// why do we have to add this here to resolve #22565
		$cand_application_sql = 'update candidate_applications set application_screening = '.$this->screening_score.' where candidate_id = '.$this->id;
		DAO::execute($link, $cand_application_sql);

		return $this->id;
	}

	public function delete(PDO $link)
	{

		$cand_id = addslashes((string)$this->id);

		// Delete the candidate and all associated records
		// RE EXPAND OUT TO INCLUDE quals and employment history
		$sql = <<<HEREDOC
DELETE FROM
	candidate, candidate_applications 
USING
	candidate
	LEFT JOIN candidate_applications
	ON candidate.id = candidate_applications.candidate_id
WHERE
	candidate.id = '$cand_id';
HEREDOC;
		DAO::execute($link, $sql);
	}

	public function convertToLearner (PDO $link, $vacancy_id = '' ) {

		if( '' != $this->id && '' != $vacancy_id ) {
			$existing_record = Candidate::loadFromDatabase($link, $this->id);
			// create the minimum necessary user details
			// username
			$this->username =  strtolower(substr($this->firstnames, 0, 1).$this->surname);
			$this->username = str_replace("'", "", $this->username);
			$found = DAO::getSingleValue($link, "select username from users where username = '$this->username'");
			if($found)
				$this->username = $this->national_insurance;
			if(is_null($this->username) || $this->username == '')
				$this->username = $this->id . '_' . strtolower(substr($this->firstnames, 0, 1).$this->surname);
			$this->username = str_replace("'", "", $this->username);
			$this->username =  strtolower(substr($this->username, 0, 44));
			$this->assessor = isset($_REQUEST['assessor'])?$_REQUEST['assessor']:'';
			$this->comment = isset($_REQUEST['candidate_comments'])?$_REQUEST['candidate_comments']:'';
			// Validate unique user identities

			// relmes - refined to check key data to ensure no duplications
			$sql = "SELECT ni FROM users where type = 5 FOR UPDATE";
			$user_list = DAO::getSingleColumn($link, $sql);

			$username_incre = 1;

			if( !is_null($this->national_insurance) && in_array($this->national_insurance, $user_list) )
			{
				return 'We already have a user matching this candidate!';


				// remove any digits at the end of the username
				$this->username = preg_replace('/\d+$/', '', $this->username);
				// increment the username values
				$this->username .= $username_incre;
				$username_incre++;
			}

			// default values for disabilities etc
			$candidate_l14 = 9;		// L14 learning difficulties / disabilities
			$candidate_l15 = 99;	// L15 disability or health problem
			$candidate_l16 = 99;	// L16 learning difficulty
			$candidate_l47 = 98;	// L47 current employment status
			$candidate_l24 = 'XF';	// L24 country of domicile ( XF = England )

			// disabilities
			$sql_disability = "SELECT cda.disability_code FROM candidate_disability AS cda WHERE cda.candidate_id = ".$this->id;
			$user_disability = DAO::getResultset($link, $sql_disability);
			// the candidate has selected disabilities 
			// - we can set the L14 value to indicate this
			// - if only one we can populate the L15 value on the user 
			if ( count($user_disability) > 0 ) {
				$candidate_l14 = 1;
				// set L15 to indicate multiple diabilities
				$candidate_l15 = 90;
				// only the one disability				
				if ( count($user_disability) == 1 ) {
					// get the specific disability
					$candidate_l15 = $user_disability[0][0];
				}
			}

			// difficulities
			$sql_difficulty = "SELECT cdi.difficulty_code FROM candidate_difficulty AS cdi WHERE cdi.candidate_id = ".$this->id;
			$user_difficulty = DAO::getResultset($link, $sql_difficulty);
			// the candidate has selected difficulties 
			// - we can set the L14 value to indicate this
			// - if only one we can populate the L16 value on the user 
			if ( count($user_difficulty) > 0 ) {
				$candidate_l14 = 1;
				// set L16 to indicate multiple difficulties
				$candidate_l16 = 90;
				if ( count($user_difficulty) == 1 ) {
					// get the specific difficulty
					$candidate_l16 = $user_difficulty[0][0];
				}
			}

			// get the location / employer ids
			//  - not nice way of doing this..

			$sql_organisation = "SELECT	vacancies.*, locations.* FROM vacancies, locations WHERE locations.id = vacancies.location AND vacancies.id = ".$vacancy_id;
			$user_organisation = DAO::getResultset($link, $sql_organisation);
			if ( count($user_organisation) !== 1 ) {
				return 'We cannot find the organisation for this vacancy!';
			}
			$candidate_organisation = $user_organisation[0][7];
			$candidate_location = $user_organisation[0][9];
			$work_street_description = $user_organisation[0][33];
			$work_locality = $user_organisation[0][34];
			$work_town = $user_organisation[0][35];
			$work_county = $user_organisation[0][36];
			$work_postcode = $user_organisation[0][37];
			$work_telephone = $user_organisation[0][38];
			$work_fax = $user_organisation[0][39];

			// employment status
			if ( $this->employment <= 2 ) {
				// candidate has indicated they are in employment
				$candidate_l47 = 1;
			}
			elseif ( $this->employment > 2 ) {
				// candidate has indicated they are not in employment
				$candidate_l47 = 5;
			}

			// country of domicile
			// disabled due to #22599 issue
			// if ( NULL !== $this->county ) {
			// 	$candidate_l24 = 'GB';
			// }

			// this needs to be changed
			$password = 'pa55word';

			// hash password
			$pwd_sha1 = sha1($password);

			$note = new Note();
			$note->subject = "Document created";

			// update this to include work address also.
			// do this via the User object instead.


			$sql = <<<HEREDOC
INSERT INTO users ( username, 
					firstnames, 
					surname, 
					employer_id, 
					employer_location_id, 
					password, 
					pwd_sha1, 
					dob, 
					ni,
					gender,	
					ethnicity, 
					home_address_line_1,
					home_address_line_2,
					home_address_line_3,
					home_address_line_4,
					home_postcode, 
					home_telephone,
					home_fax, 
					home_mobile, 
					home_email, 
					work_address_line_1,
					work_address_line_2,
					work_address_line_3,
					work_address_line_4,
					work_postcode,
					work_telephone,
					work_fax,
					modified, 
					created, 
					type, 
					l14, 
					l15, 
					l16, 
					l24, 
					l47 
	) 	values 	( 
					'$this->username', 
					'$this->firstnames', 
					'$this->surname', 
					'$candidate_organisation', 
					'$candidate_location', 
					'$password', 
					'$pwd_sha1', 
					'$this->dob', 
					'$this->national_insurance', 
					'$this->gender', 
					'$this->ethnicity', 
					'$this->address1', 
					'$this->address2',
					'$this->borough',
					'$this->county',
					'$this->postcode', 
					'$this->telephone', 
					'$this->fax',
					'$this->mobile',
					'$this->email',
					'$work_street_description',
					'$work_locality',
					'$work_town',
					'$work_county',
					'$work_postcode',
					'$work_telephone',
					'$work_fax',
					 now(), 
					 now(), 
					'5',
					'$candidate_l14',	
					'$candidate_l15',
					'$candidate_l16', 
					'$candidate_l24',
					'$candidate_l47' 
	)
HEREDOC;
			$st = $link->query($sql);

			if( !$st ) {
				return implode($link->errorInfo());
			}
			else {
				if(DB_NAME=="am_baltic" || DB_NAME=="am_baltic_demo" || DB_NAME == "am_ray_recruit" || DB_NAME == "am_lcurve_demo")
					$this->status_code = 15; // after conversion change the candidate status

				$this->save($link);

				// Update Employer Address

				$link->query("UPDATE users
INNER JOIN locations ON locations.id = users.`employer_location_id`
SET users.`work_address_line_1` = locations.`address_line_1`,
users.`work_address_line_2` = locations.`address_line_2`,
users.`work_address_line_3` = locations.`address_line_3`,
users.`work_address_line_4` = locations.`address_line_4`,
users.`work_email` = locations.`contact_email`,
users.`work_fax` = locations.`fax`,
users.`work_mobile` = locations.`contact_mobile`,
users.`work_postcode` = locations.`postcode`,
users.`work_telephone` = locations.`telephone`
WHERE users.`username` = '$this->username';");

				// End

				// save the action to the candidate notes
				$candidate_note = new CandidateNotes();
				$candidate_note->candidate_id = $existing_record->id;
				$candidate_note->note = 'Candidate converted to learner '.$this->username;
				$candidate_note->username = $_SESSION['user']->username;
				$candidate_note->status = 1;

				$candidate_note->save($link);

				return 'New user <strong>'.$this->username.'</strong> created!';
			}
		}
		else {
			return 'Cannot build new user 2';
		}
	}

	/**
	 * Function to handle the management of multiple vacancies.
	 * RE 07/07/2011:
	 * THIS IS UPDATED TO NO LONGER REMOVE THE APPLICATION ASSOCIATED DATA
	 * AS REPORTING IS REQUIRED AGAINST CANDIDATE REMOVED FROM APPLICATIONS
	 */
	public function remove_application (PDO $link, $vacancy_id = '') {

		if ( !$vacancy_id ) {
			return null;
		}
		$cand_id = $this->id;


		// Delete the appplication for this candidate.
		// EXPAND TO INCLUDE REMOVAL from METADATA
		// $sql = <<<HEREDOC
		// 
		// DELETE FROM candidate_applications, candidate_metadata
// USING
		// candidate_applications
		// LEFT JOIN candidate_metadata
		// ON candidate_applications.candidate_id = candidate_metadata.candidateid
		// AND candidate_applications.candidate_id = candidate_metadata.candidateid
// WHERE
// 		candidate_applications.candidate_id = '$cand_id'
// AND 	candidate_applications.vacancy_id = '$vacancy_id';
// HEREDOC;

		$sql = <<<HEREDOC
		UPDATE candidate_applications set application_status = 2
WHERE
 		candidate_applications.candidate_id = '$cand_id'
AND 	candidate_applications.vacancy_id = '$vacancy_id'

HEREDOC;
		DAO::execute($link, $sql);

		$this->enrolled = 0;
		// Get the count of applications still outstanding for this candidate.
		$sql = "select count(*) from candidate_applications WHERE candidate_id = '$cand_id' and application_status != 2 ";
		$applications = DAO::getSingleValue($link, $sql);
		if ( $applications >= 1 ) {
			$this->enrolled = 1;
		}
	}

	private function present_screen_qualifications (PDO $link) {

		$return_html = '';

		$return_html .= '<tr><td colspan="4" style="background-color: #eee;" ><strong>Qualifications</strong><a name="nvq_quals"></a></td></tr>';
		foreach ( $this->qualifications as $edu_pos => $edu_row ) {//<input type="hidden" name="grade[]" value="~" />';
			if(DB_NAME=="am_baltic_demo" || DB_NAME=="am_demo" || DB_NAME=="am_baltic" || DB_NAME == "am_ray_recruit" || DB_NAME == "am_lcurve_demo")
			{
				$level = "";
				if(!is_null($edu_row['level']) AND $edu_row['level'] != '')
					$level = DAO::getSingleValue($link, "SELECT description FROM lookup_candidate_qualification WHERE id = " . $edu_row['level']);
				$return_html .= '<tr>';
				// Grades for GCSE / A / AS level options
				$qual_grades = array(array('A*','A*'),array('A','A'),array('B','B'),array('C','C'),array('D','D'),array('E','E'),array('F','F'),array('G','G'),array('U','U'));
				$return_html .= '<td><input type="hidden" name="level[]" value="'.$edu_row['level'].'" />'.$level.'</td><td><input type="hidden" name="subject[]" value="'.$edu_row['subject'].'" />'.$edu_row['subject'].'</td>';
				$return_html .= '<td>'.HTML::cell($edu_row['grade']).'</td>';
				$return_html .= '</tr>';
			}
			else
			{
				$return_html .= '<tr>';
				//$return_html .= '<td>'.$edu_row['level'].'</td><td>'.$edu_row['subject'].'</td><td>'.$edu_row['grade'].'</td>';
				// Grades for GCSE / A / AS level options
				$qual_grades = array(array('A*','A*'),array('A','A'),array('B','B'),array('C','C'),array('D','D'),array('E','E'),array('F','F'),array('G','G'),array('U','U'));
				//$return_html .= '<td><input type="hidden" name="level[]" value="'.$edu_row['level'].'" />'.$edu_row['level'].'</td><td><input type="hidden" name="subject[]" value="'.$edu_row['subject'].'" />'.$edu_row['subject'].'</td><td><input type="text" name="grade[]" value="'.$edu_row['grade'].'" /></td>';
				$return_html .= '<td><input type="hidden" name="level[]" value="'.$edu_row['level'].'" />'.$edu_row['level'].'</td><td><input type="hidden" name="subject[]" value="'.$edu_row['subject'].'" />'.$edu_row['subject'].'</td>';
				//$return_html .= '<td><input type="text" name="grade[]" value="'.$edu_row['grade'].'" /></td>';
				$return_html .= '<td>'.HTML::select('grade[]',$qual_grades,$edu_row['grade'],true).'</td>';
				$return_html .= '</tr>';
			}
		}

		// new candidate section
		if (  !$this->id ) {
			// date drop down populations
			$day = array(array('','dd'),array(1,1),array(2,2),array(3,3),array(4,4),array(5,5),array(6,6),array(7,7),array(8,8),array(9,9),array(10,10),array(11,11),array(12,12),array(13,13),array(14,14),array(15,15),array(16,16),array(17,17),array(18,18),array(19,19),array(20,20),array(21,21),array(22,22),array(23,23),array(24,24),array(25,25),array(26,26),array(27,27),array(28,28),array(29,29),array(30,30),array(31,31));
			$month = array(array('','mon'),array(1,'Jan'),array(2,'Feb'),array(3,'Mar'),array(4,'Apr'),array(5,'May'),array(6,'Jun'),array(7,'Jul'),array(8,'Aug'),array(9,'Sep'),array(10,'Oct'),array(11,'Nov'),array(12,'Dec'));
			$year = array(array('','yyyy'));
			for($a = 2016; $a>=1930; $a--) {
				$year[] = array($a,$a);
			}
			// - ie issue with onchange on array[] - removing it so verfiy this
			$day_options = preg_replace("/onchange=\"(.*)\"/","", HTML::select('comp_day[]', $day, '', false, true));
			$day_options = preg_replace("/id=\"(.*)\"/", "", $day_options);
			$mon_options = preg_replace("/onchange=\"(.*)\"/","", HTML::select('comp_mon[]', $month, '', false, true));
			$mon_options = preg_replace("/id=\"(.*)\"/", "", $mon_options);
			$year_options = preg_replace("/onchange=\"(.*)\"/","", HTML::select('comp_year[]', $year, '', false, true));
			$year_options = preg_replace("/id=\"(.*)\"/", "", $year_options);

			// none compulsory date fields
			$qual_day_options = preg_replace("/onchange=\"(.*)\"/","", HTML::select('comp_day[]', $day, '', false, false));
			$qual_day_options = preg_replace("/id=\"(.*)\"/", "", $qual_day_options);
			$qual_mon_options = preg_replace("/onchange=\"(.*)\"/","", HTML::select('comp_mon[]', $month, '', false, false));
			$qual_mon_options = preg_replace("/id=\"(.*)\"/", "", $qual_mon_options);
			$qual_year_options = preg_replace("/onchange=\"(.*)\"/","", HTML::select('comp_year[]', $year, '', false, false));
			$qual_year_options = preg_replace("/id=\"(.*)\"/", "", $qual_year_options);

			// GCSE / A / AS level options
			if(DB_NAME=="am_demo" || DB_NAME=="am_baltic_demo" || DB_NAME=="am_baltic" || DB_NAME == "am_ray_recruit" || DB_NAME == "am_lcurve_demo")
				$qual_level_one = DAO::getResultset($link, "SELECT id, description FROM lookup_candidate_qualification WHERE id IN (2,3,4)");
			else
				$qual_level_one = array(array('GCSE','GCSE'),array('A', 'A Level'),array('AS','AS Level'), array('Other','Other'));
			// - ie issue with onchange on array[] - removing it so verfiy this
			$qual_level_one_options = preg_replace("/onchange=\"(.*)\"/", "", HTML::select('level[]', $qual_level_one, '', true, false, true));
			$qual_level_one_options = preg_replace("/id=\"(.*)\"/", "", $qual_level_one_options);

			// NVQ / BTEC level options
			if(DB_NAME=="am_demo" || DB_NAME=="am_baltic_demo" || DB_NAME=="am_baltic" || DB_NAME == "am_ray_recruit" || DB_NAME == "am_lcurve_demo")
				$qual_level_two = DAO::getResultset($link, "SELECT id, description FROM lookup_candidate_qualification WHERE id NOT IN (2,3,4)");
			else
				$qual_level_two = array(array('NVQ','NVQ'),array('BTEC','BTEC'),array('Key Skills', 'Key Skills'), array('Functional Skills','Functional Skills'), array('Other','Other'));
			// - ie issue with onchange on array[] - removing it so verify this
			$qual_level_two_options = preg_replace("/onchange=\"(.*)\"/", "", HTML::select('level[]', $qual_level_two, '', true, false, true));
			$qual_level_two_options = preg_replace("/id=\"(.*)\"/", "", $qual_level_two_options);

			// Grades for GCSE / A / AS level options
			$qual_grades = array(array('A*','A*'),array('A','A'),array('B','B'),array('C','C'),array('D','D'),array('E','E'),array('F','F'),array('G','G'),array('U','U'));
			// - ie issue with onchange on array[] - removing it so verify this
			$qualification_grade_options = preg_replace("/onchange=\"(.*)\"/", "", HTML::select('grade[]', $qual_grades, '', true, false, true));
			$qualification_grade_options = preg_replace("/id=\"(.*)\"/", "",$qualification_grade_options);
			// mandatory grades
			$mandatory_grade_options = preg_replace("/id=\"(.*)\"/", "class=\"compulsory\"", HTML::select('grade[]', $qual_grades, '', true, true, true));

			$return_html .= '<tr>';
			$return_html .= '<td colspan="4">';
			$return_html .= '	<table class="resultset" id="qual_one" >';
			$return_html .= '		<thead>';
			$return_html .= '		<tr>';
			$return_html .= '			<th>GCSE/A/AS Level <a href="#study_quals" onclick="javascript:newqual(\'qual_one\')" >add another</a></th><th>Subject</th><th>Grade</th>';
			$return_html .= '		</tr>';
			$return_html .= '		</thead>';
			$return_html .= '		<tbody>';
			$return_html .= '		<tr>';
			$return_html .= '			<td>';
			$return_html .= '	GCSE';
			if(DB_NAME=="am_demo" || DB_NAME=="am_baltic_demo" || DB_NAME=="am_baltic" || DB_NAME == "am_ray_recruit" || DB_NAME == "am_lcurve_demo")
				$return_html .= '	<input type="hidden" name="level[]" value="2" />';
			else
				$return_html .= '	<input type="hidden" name="level[]" value="GCSE" />';
			$return_html .= '</td>';
			$return_html .= '<td >';
			$return_html .= '	<strong>English *</strong>';
			$return_html .= '	<input type="hidden" name="subject[]"  value="English" />';
			$return_html .= '</td>';
			$return_html .= '<td>';
			$return_html .= $mandatory_grade_options;
			$return_html .= '	<input type="hidden" name="comp_day[]" value="00" />';
			$return_html .= '	<input type="hidden" name="comp_mon[]" value="00" />';
			$return_html .= '	<input type="hidden" name="comp_year[]" value="0000" />';
			$return_html .= '</td>';
			$return_html .= '</tr>';
			$return_html .= '<tr>';
			$return_html .= '<td>';
			$return_html .= '	GCSE';
			if(DB_NAME=="am_demo" || DB_NAME=="am_baltic_demo" || DB_NAME=="am_baltic" || DB_NAME == "am_ray_recruit" || DB_NAME == "am_lcurve_demo")
				$return_html .= '	<input type="hidden" name="level[]" value="2" />';
			else
				$return_html .= '	<input type="hidden" name="level[]" value="GCSE" />';
			$return_html .= '</td>';
			$return_html .= '<td >';
			$return_html .= '	<strong>Maths *</strong>';
			$return_html .= '	<input type="hidden" name="subject[]"  value="Maths" />';
			$return_html .= '</td>';
			$return_html .= '<td>';
			$return_html .= $mandatory_grade_options;
			$return_html .= '	<input type="hidden" name="comp_day[]" value="00" />';
			$return_html .= '	<input type="hidden" name="comp_mon[]" value="00" />';
			$return_html .= '	<input type="hidden" name="comp_year[]" value="0000" />';
			$return_html .= '</td>';
			$return_html .= '</tr>';
			$return_html .= '<tr>';
			$return_html .= '<td>';
			$return_html .= $qual_level_one_options;
			$return_html .= '</td>';
			$return_html .= '<td ><input type="text" name="subject[]"  size="40" maxlength="100"/></td>';
			$return_html .= '<td>';
			$return_html .= $qualification_grade_options;
			$return_html .= '	<input type="hidden" name="comp_day[]" value="00" />';
			$return_html .= '	<input type="hidden" name="comp_mon[]" value="00" />';
			$return_html .= '	<input type="hidden" name="comp_year[]" value="0000" />';
			$return_html .= '</td>';
			$return_html .= '</tr>';
			$return_html .= '</tbody>';
			$return_html .= '</table>';
			$return_html .= '<table class="resultset" id="qual_two" >';
			$return_html .= '<thead>';
			$return_html .= '<tr>';
			$return_html .= '	<th>Qualification Level <a href="#quals" onclick="javascript:newqual(\'qual_two\')">add another</a></th>';
			$return_html .= '	<th>Course</th>';
			$return_html .= '	<th>Date Completed</th>';
			$return_html .= '</tr>';
			$return_html .= '</thead>';
			$return_html .= '<tbody>';
			$return_html .= '	<tr>';
			$return_html .= '		<td>';
			$return_html .= $qual_level_two_options;
			$return_html .= '		</td>';
			$return_html .= '		<td><input type="text" name="subject[]"  size="40" maxlength="100"/></td>';
			$return_html .= '		<td>';
			$return_html .= '		<input type="hidden" name="grade[]" value="~" />';

			$return_html .= preg_replace("/\r\n/","", $qual_day_options);
			$return_html .= preg_replace("/\r\n/","", $qual_mon_options);
			$return_html .= preg_replace("/\r\n/","", $qual_year_options);
			$return_html .= '</td>';
			$return_html .= '</tr>';
			$return_html .= '</tbody>';
			$return_html .= '</table>';
			$return_html .= '</td>';
			$return_html .= '</tr>';
		}

		return $return_html;
	}

	private function present_screen_employment () {

		$return_html = '';

		if ( sizeof($this->employment_history) > 0 ) {
			$return_html .= '<tr><td colspan="4" style="background-color: #eee;" ><strong>Employment History</strong></td></tr>';
			$return_html .= '<tr style="font-weight: bold" >';
			$return_html .= '<td>Job Description</td><td>Skills</td><td>Start Date</td><td>End Date</td>';
			$return_html .= '</tr>';
			foreach ( $this->employment_history as $edu_pos => $edu_row ) {
				$return_html .= '<tr>';
				$return_html .= '<td>'.$edu_row['company_name'].' - '.$edu_row['job_title'].'</td><td>'.$edu_row['skills'].'</td><td>'.$edu_row['start_date'].'</td><td>'.$edu_row['end_date'].'</td>';
				$return_html .= '</tr>';
			}
		}

		if ( !$this->id ) {

			// date drop down populations
			$day = array(array('','dd'),array(1,1),array(2,2),array(3,3),array(4,4),array(5,5),array(6,6),array(7,7),array(8,8),array(9,9),array(10,10),array(11,11),array(12,12),array(13,13),array(14,14),array(15,15),array(16,16),array(17,17),array(18,18),array(19,19),array(20,20),array(21,21),array(22,22),array(23,23),array(24,24),array(25,25),array(26,26),array(27,27),array(28,28),array(29,29),array(30,30),array(31,31));
			$month = array(array('','mon'),array(1,'Jan'),array(2,'Feb'),array(3,'Mar'),array(4,'Apr'),array(5,'May'),array(6,'Jun'),array(7,'Jul'),array(8,'Aug'),array(9,'Sep'),array(10,'Oct'),array(11,'Nov'),array(12,'Dec'));
			$year = array(array('','yyyy'));
			for($a = 2016; $a>=1930; $a--) {
				$year[] = array($a,$a);
			}

			// employment history date fields
			$hist_sday_options = preg_replace("/id=\"(.*)\"/", "", HTML::select('hist_sday[]', $day, '', false, false));
			$hist_smon_options = preg_replace("/id=\"(.*)\"/", "", HTML::select('hist_smon[]', $month, '', false, false));
			$hist_syear_options = preg_replace("/id=\"(.*)\"/", "", HTML::select('hist_syear[]', $year, '', false, false));

			$hist_eday_options = preg_replace("/id=\"(.*)\"/", "", HTML::select('hist_eday[]', $day, '', false, false));
			$hist_emon_options = preg_replace("/id=\"(.*)\"/", "", HTML::select('hist_emon[]', $month, '', false, false));
			$hist_eyear_options = preg_replace("/id=\"(.*)\"/", "", HTML::select('hist_eyear[]', $year, '', false, false));

			$return_html .= '<tr><td colspan="4" style="background-color: #eee;" ><strong>Employment History (including paid employment, school work experience and voluntary positions)</strong></td></tr>';
			for( $hist_cnt = 1; $hist_cnt <= 4; $hist_cnt++ ) {
				$return_html .= '	<tr>';
				$return_html .= '		<td>Company '.$hist_cnt.' Name:</td>';
				$return_html .= '		<td><input type="text" name="company_name[]"  maxlength="100" /></td>';
				$return_html .= '		<td>Job Title:</td>';
				$return_html .= '		<td><input type="text" name="job_title[]"  maxlength="100" /></td>';
				$return_html .= '	</tr>';
				$return_html .= '	<tr>';
				$return_html .= '		<td>Start Date:</td>';
				$return_html .= '		<td>';
				$return_html .= preg_replace("/\r\n/","", $hist_sday_options);
				$return_html .= preg_replace("/\r\n/","", $hist_smon_options);
				$return_html .= preg_replace("/\r\n/","", $hist_syear_options);
				$return_html .= '		</td>';
				$return_html .= '		<td>End Date:</td>';
				$return_html .= '		<td>';
				$return_html .= preg_replace("/\r\n/","", $hist_eday_options);
				$return_html .= preg_replace("/\r\n/","", $hist_emon_options);
				$return_html .= preg_replace("/\r\n/","", $hist_eyear_options);
				$return_html .= '		</td>';
				$return_html .= '	</tr>';
				$return_html .= '	<tr>';
				$return_html .= '		<td>Skills Learnt:</td>';
				$return_html .= '		<td colspan="3" ><textarea name="job_skills[]" style="width:98%" ></textarea></td>';
				$return_html .= '	</tr>';
			}
		}
		return $return_html;
	}

	public function populate_metadata(PDO $link, $vacancy_id = '' ) {

		/**
		 * RE 09/03/2012 - taken off specific vacancy check
		 * TODO RE: there is an issue with storage in DB.
		 * ----
		 * Now attempts to populate with as much data
		 * as can be found in the candidate_metadata
		 * - this is inefficient and needs to be looked at
		 */
		$sql_vacancy_specific = '';
		// $sql_vacancy_specific = ' AND vacancy_id is NULL ';
		// if ( isset($_REQUEST['id']) && $_REQUEST['_action'] == 'view_vacancy' && ( $vacancy_id == '' ) ) {
		// 	$sql_vacancy_specific = ' AND vacancy_id = '.addslashes((string)$_REQUEST['id']);
		// }
		// elseif( $vacancy_id != '' ) {
		// 	$sql_vacancy_specific = ' AND vacancy_id = '.addslashes((string)$vacancy_id);
		// }

		// populate with metadata
		$query = <<<HEREDOC
                                        SELECT
                                                users_capture_info.infogroupname,
                                                users_capture_info.userinfoid,
                                                users_capture_info.userinfoname,
                                                IF (candidate_metadata.stringvalue IS NOT NULL, candidate_metadata.stringvalue,
                                                        IF (candidate_metadata.intvalue IS NOT NULL, candidate_metadata.intvalue ,
                                                                IF( candidate_metadata.floatvalue IS NOT NULL, candidate_metadata.floatvalue,
                                                                        IF( candidate_metadata.datevalue IS NOT NULL, candidate_metadata.datevalue, '')
                                                                )
                                                        )
                                                ) AS candidate_value
                                        FROM
                                                users_capture_info LEFT JOIN candidate_metadata
                                        ON      candidate_metadata.userinfoid = users_capture_info.userinfoid
                                        AND     candidateid = '{$this->id}'
                                        $sql_vacancy_specific
                                        ORDER BY users_capture_info.infogroupid, users_capture_info.infoorder ASC;
HEREDOC;
		$cand_st = $link->query($query);
		$screening_group = '';
		if($cand_st) {
			while( $meta_row = $cand_st->fetch() ) {
				if ( $meta_row['infogroupname'] != $screening_group ) {
					// re - over zealous resetting of array data stopped
					//    - issue caused by the order of the retreived data
					if ( !isset($this->metadata[$meta_row['infogroupname']]) ) {
						$this->metadata[$meta_row['infogroupname']] = array();
					}
					$screening_group = $meta_row['infogroupname'];
				}
				// check if we have already set a value other than 'answer not supplied'
				if ( isset($this->metadata[$meta_row['infogroupname']][$meta_row['userinfoname']]) ) {
					if ( $this->metadata[$meta_row['infogroupname']][$meta_row['userinfoname']] == 'answer not supplied' && $meta_row['candidate_value'] != 'answer not supplied' ) {
						$this->metadata[$meta_row['infogroupname']][$meta_row['userinfoname']] = $meta_row['candidate_value'];
					}
				}
				else {
					$this->metadata[$meta_row['infogroupname']][$meta_row['userinfoname']] = $meta_row['candidate_value'];
				}
			}
		}
	}

	public function getCVLink($id, $name)
	{
		$cv_file_link = '&nbsp;';
		if ( file_exists(DATA_ROOT."/uploads/".DB_NAME."/recruitment/".$id.".doc") ) {
			$cv_file_link = '<a href="do.php?_action=downloader&path=/recruitment/&f='.$id.'.doc">' . $name . ' - CV</a> (doc)';
		}
		elseif( file_exists(DATA_ROOT."/uploads/".DB_NAME."/recruitment/".$id.".docx") ) {
			$cv_file_link = '<a href="do.php?_action=downloader&path=/recruitment&f='.$id.'.docx">' . $name . ' - CV</a> (docx)';
		}
		elseif( file_exists(DATA_ROOT."/uploads/".DB_NAME."/recruitment/".$id.".pdf") ) {
			$cv_file_link = '<a href="do.php?_action=downloader&path=/recruitment/&f='.$id.'.pdf">' . $name . ' - CV</a> (pdf)';
		}
		return $cv_file_link;
	}


	/**
	 * Returns the user's photograph, searching for it in three possible locations:<br/>
	 * 1) Lewisham's /photos directory<br/>
	 * 2) The user's /username/photos directory<br/>
	 * 3) The user's /username directory<br/>
	 * @author iss
	 * @return absolute filepath to the user's photograph
	 */
	public function getPhotoPath()
	{

		$root = Repository::getRoot();
		$photo_root = $root.'/photos';
		if(!is_dir($photo_root)){
			$photo_root = null;
		}
		$user_root = $root.'/'.$this->id;
		if(!is_dir($user_root)){
			$user_root = null;
		}
		$user_photo_root =  $root.'/recruitment';
		if(!is_dir($user_photo_root)){
			$user_photo_root = null;
		}

		// (2)
		if($user_photo_root){
			$images = glob($user_photo_root.'/photo_'. $this->id .'.{jpg,jpeg,gif,png,JPG,JPEG,GIF,PNG}', GLOB_BRACE);
			if(count($images) > 0){
				return $images[0]; // return first image in the glob
			}
		}
		// (3)
		if($user_root){
			$images = glob($user_root.'/*.{jpg,jpeg,gif,png,JPG,JPEG,GIF,PNG}', GLOB_BRACE);
			foreach($images as $image){
				if(stripos($image, 'signat') === false){
					return $image; // return first image that is not a user's signature
				}
			}
		}
		return null;
	}

	public function displayCandidateAddresses ( PDO $link )
	{
		$candidate_address_details = '';
		$candidate_address_details .= '<h3>Contact Details</h3>';
		$candidate_address_details .= '<table border="0" cellspacing="4" cellpadding="4" style="margin-left:10px">';
		$candidate_address_details .= '<col width="170" /><col width="400" />';
		$candidate_address_details .= '<tr>';
		$candidate_address_details .= '<td class="fieldLabel" valign="top">House Name</td>';
		$candidate_address_details .= '<td class="fieldValue">' . $this->address1 . '</td>';
		$candidate_address_details .= '</tr>';
		$candidate_address_details .= '<tr>';
		$candidate_address_details .= '<td class="fieldLabel" valign="top">Street and Number</td>';
		$candidate_address_details .= '<td class="fieldValue">' . $this->address2 . '</td>';
		$candidate_address_details .= '</tr>';
		$candidate_address_details .= '<tr>';
		$candidate_address_details .= '<td class="fieldLabel" valign="top">Town</td>';
		$candidate_address_details .= '<td class="fieldValue">' . $this->borough . '</td>';
		$candidate_address_details .= '</tr>';
		$candidate_address_details .= '<tr>';
		$candidate_address_details .= '<td class="fieldLabel" valign="top">County</td>';
		$candidate_address_details .= '<td class="fieldValue">' . $this->county . '</td>';
		$candidate_address_details .= '</tr>';
		$candidate_address_details .= '<tr>';
		if(DB_NAME=="am_baltic_demo" || DB_NAME == "am_demo" || DB_NAME=="am_baltic")
		{
			$candidate_address_details .= '<td class="fieldLabel" valign="top">Region</td>';
			$candidate_address_details .= '<td class="fieldValue">' . $this->region . '</td>';
			$candidate_address_details .= '</tr>';
			$candidate_address_details .= '<tr>';
		}
		$candidate_address_details .= '<td class="fieldLabel" valign="top">Postcode</td>';
		$candidate_address_details .= '<td class="fieldValue"><a href="http://maps.google.co.uk/maps?f=q&hl=en&q=' . $this->postcode . '" target="_blank">' . $this->postcode . '</a></td>';
		$candidate_address_details .= '</tr>';
		$candidate_address_details .= '<tr>';
		if(DB_NAME=="am_baltic_demo" || DB_NAME == "am_demo" || DB_NAME=="am_baltic")
		{
			$candidate_address_details .= '<td class="fieldLabel" valign="top">Country</td>';
			if(isset($this->country) && $this->country != '')
				$candidate_address_details .= '<td class="fieldValue">' . DAO::getSingleValue($link, "SELECT country_name FROM lookup_countries WHERE id = " . $this->country) . '</td>';
			else
				$candidate_address_details .= '<td class="fieldValue"></td>';
			$candidate_address_details .= '</tr>';
			$candidate_address_details .= '<tr>';
		}
		$candidate_address_details .= '<td class="fieldLabel">Telephone</td>';
		$candidate_address_details .= '<td class="fieldValue">'.htmlspecialchars((string)$this->telephone).'</td>';
		$candidate_address_details .= '</tr>';
		$candidate_address_details .= '<tr>';
		$candidate_address_details .= '<td class="fieldLabel">Mobile</td>';
		$candidate_address_details .= '<td class="fieldValue">'.htmlspecialchars((string)$this->mobile).'</td>';
		$candidate_address_details .= '</tr>';
		$candidate_address_details .= '<tr>';
		$candidate_address_details .= '<td class="fieldLabel">Fax</td>';
		$candidate_address_details .= '<td class="fieldValue">'.htmlspecialchars((string)$this->fax).'</td>';
		$candidate_address_details .= '</tr>';
		$candidate_address_details .= '<tr>';
		$candidate_address_details .= '<td class="fieldLabel">Email</td>';
		$candidate_address_details .= '<td class="fieldValue"><a href="mailto:' . htmlspecialchars((string)$this->email) . '">' . htmlspecialchars((string)$this->email).'</td>';
		$candidate_address_details .= '</tr>';
		$candidate_address_details .= '</table>';

		return $candidate_address_details;
	}

	public function render_candidate_qualifications (PDO $link, $showIcon = true, $editMode = false)
	{
		if(!$editMode)
		{
			// populate with the candidate qualifications
			$query = "SELECT candidate_qualification.* FROM candidate_qualification WHERE candidate_qualification.candidate_id = ".addslashes((string)$this->id)."  ORDER BY candidate_qualification.id;";
			$st = $link->query($query);
			$qualifications = array();
			if( $st ) {
				while( $edu_row = $st->fetch() ) {
					$qualifications[] = array(
						'level' => $edu_row['qualification_level'],
						'subject' => $edu_row['qualification_subject'],
						'date' => $edu_row['qualification_date'],
						'grade' => $edu_row['qualification_grade'],
						'school' => $edu_row['school_name']

					);
				}
			}
			else
			{
				throw new DatabaseException($link, $query);
			}

			$return_html = '';
			$return_html .= '<h3>Qualifications</h3>';
			$return_html .= '<table border="0" class="resultset" cellspacing="4" cellpadding="4" style="margin-left:10px">';
			$return_html .= '<thead><th>&nbsp;</th><th>Level</th><th>Subject</th><th>Grade</th><th>Date</th><th>School</th></thead>';

			if(count($qualifications) > 0)
			{
				foreach ( $qualifications as $edu_pos => $edu_row )
				{
					$return_html .= '<tr>';
					if($showIcon)
						$return_html .= '<td><img src="/images/achieved.jpg" border="0" /></td>';
					else
						$return_html .= '<td></td>';
					$return_html .= '<td>'.$edu_row['level'].'</td><td>'.$edu_row['subject'].'</td><td>'.$edu_row['grade'].'</td><td>'.$edu_row['date'].'</td><td>'.$edu_row['school'].'</td>';
					$return_html .= '</tr>';
				}
			}
			$return_html .= '</table>';
			return $return_html;
		}
		else
		{
			$index = 0;
			// populate with the candidate qualifications
			$query = "SELECT candidate_qualification.* FROM candidate_qualification WHERE candidate_qualification.candidate_id = ".addslashes((string)$this->id)."  ORDER BY candidate_qualification.id;";
			$st = $link->query($query);
			$qualifications = array();
			if( $st ) {
				while( $edu_row = $st->fetch() ) {
					$qualifications[] = array(
						'level' => $edu_row['qualification_level'],
						'subject' => $edu_row['qualification_subject'],
						'date' => $edu_row['qualification_date'],
						'grade' => $edu_row['qualification_grade'],
						'school' => $edu_row['school_name']

					);
				}
			}
			else
			{
				throw new DatabaseException($link, $query);
			}

			$return_html = '';
			//$return_html .= '<h3>Qualifications</h3>';//id="edit_qual" onclick="javascript:newqual(\'qual_three\')"
			$return_html .= '<form name="candidate_qualifications" action="baltic_save_candidate_qualifications">';
			$return_html .= '<input type="hidden" name="candidate_id" value="' . $this->id . '" />';
			$return_html .= '<table id="tbl_qualification" border="0" class="resultset" cellspacing="4" cellpadding="4" style="margin-left:10px">';
			$return_html .= '<thead><tr><th colspan="2">Highest Education Completed</th><th colspan="4">';
			$return_html .= HTML::select('last_education', DAO::getResultset($link, "SELECT * FROM lookup_candidate_qualification"), $this->last_education, true);
			$return_html .= '</th></tr><tr><th>&nbsp;</th><th>Level</th><th>Subject</th><th>Grade</th><th>Date</th><th>School</th></tr></thead>';


			if(count($qualifications) > 0)
			{
				foreach ( $qualifications as $edu_pos => $edu_row )
				{
					$return_html .= '<tr>';
					if($showIcon)
						$return_html .= '<td><img src="/images/achieved.jpg" width="30" height="30" border="0" /></td>';
					else
						$return_html .= '<td></td>';
					$qual_levels = DAO::getResultset($link, "SELECT id, description FROM lookup_candidate_qualification ORDER BY description;");
					$return_html .= '<td>' . HTML::select('level' . $index, $qual_levels,$edu_row['level'],true) . '</td>';
					$return_html .= '<td><input type="text" name="subject' . $index . '" id="subject' . $index . '" value="' . $edu_row['subject'] . '" /></td>';
					$qual_grades = array(array('A*','A*'),array('A','A'),array('B','B'),array('C','C'),array('D','D'),array('E','E'),array('F','F'),array('G','G'),array('U','U'));
					$return_html .= '<td>' . HTML::select('grade' . $index, $qual_grades,$edu_row['grade'],true) . '</td>';
					$return_html .= '<td>' . HTML::datebox('date' . $index, $edu_row['date']) . '</td>';
					$return_html .= '<td><input type="text" name="school' . $index . '" id="school' . $index . '" value="' . $edu_row['school'] . '" /></td>';

					$return_html .= '</tr>';

					$index++;
				}
			}
			$return_html .= '<input type="hidden" name="indexValue" value="' . $index . '" />';
			$return_html .= '<input type="hidden" name="new_record" value="0" />';
			$return_html .= '</table></form>';
			return $return_html;
		}
	}

	public function render_candidate_employment (PDO $link, $showIcon = true, $editMode = false)
	{
		if(!$editMode)
		{
			$return_html = '';
			$return_html .= '<h3>Employment History</h3>';
			$return_html .= '<table id="tbl_employment" border="0" class="resultset" cellspacing="4" cellpadding="4" style="margin-left:10px">';
			$return_html .= '<thead><th>&nbsp;</th><th>Company Name</th><th>Job Description</th><th>Skills</th><th>Start Date</th><th>End Date</th></thead>';

			if ( sizeof($this->employment_history) > 0 )
			{
				foreach ( $this->employment_history as $edu_pos => $edu_row )
				{
					$return_html .= '<tr>';
					if($showIcon)
						$return_html .= '<td><img src="/images/emp_history.jpg" border="0" width="30" height="30" /></td>';
					else
						$return_html .= '<td></td>';
					$return_html .= '<td>'.$edu_row['company_name'].'</td><td>'.$edu_row['job_title'].'</td><td>'.$edu_row['skills'].'</td><td>'.$edu_row['start_date'].'</td><td>'.$edu_row['end_date'].'</td>';
					$return_html .= '</tr>';
				}
			}

			$return_html .= '</table>';
			return $return_html;
		}
		else
		{
			$index = 0;
			$return_html = '';
			//$return_html .= '<h3>Employment History</h3>';
			$return_html .= '<form name="candidate_employment" action="baltic_save_candidate_employment">';
			$return_html .= '<input type="hidden" name="candidate_id" value="' . $this->id . '" />';
			$return_html .= '<table id="tbl_employment" border="0" class="resultset" cellspacing="4" cellpadding="4" style="margin-left:10px">';
			$return_html .= '<thead><tr><th colspan="3">Employment Status</th><th colspan="3">';
			$return_html .= HTML::select('employment_status', DAO::getResultset($link, "select id, status_description, null from lookup_candidate_employment_status order by id;"), $this->employment_status, true);
			$return_html .= '</th></tr><tr></tr><th>&nbsp;</th><th>Company Name</th><th>Job Description</th><th>Skills</th><th>Start Date</th><th>End Date</th></tr></thead>';


			if ( sizeof($this->employment_history) > 0 )
			{
				foreach ( $this->employment_history as $edu_pos => $edu_row )
				{
					$return_html .= '<tr>';
					if($showIcon)
						$return_html .= '<td><img src="/images/emp_history.jpg" border="0" width="30" height="30" /></td>';
					else
						$return_html .= '<td></td>';
					$return_html .= '<td><input type="text" name="company_name' . $index . '" id="company_name' . $index . '" value="' . $edu_row['company_name'] . '" /></td>';
					$return_html .= '<td><input type="text" name="job_title' . $index . '" id="job_title' . $index . '" value="' . $edu_row['job_title'] . '" /></td>';
					$return_html .= '<td><input type="text" name="skills' . $index . '" id="skills' . $index . '" value="' . $edu_row['skills'] . '" /></td>';
					$return_html .= '<td>' . HTML::datebox('start_date' . $index, $edu_row['start_date']) . '</td>';
					$return_html .= '<td>' . HTML::datebox('end_date' . $index, $edu_row['end_date']) . '</td>';

					$return_html .= '</tr>';

					$index++;
				}
			}
			$return_html .= '<input type="hidden" name="indexValue" value="' . $index . '" />';
			$return_html .= '<input type="hidden" name="new_record" value="0" />';
			$return_html .= '</table></form>';
			return $return_html;
		}
	}

	public function render_candidate_applications(PDO $link, $showIcon = true)
	{
		$return_html = '';
		$return_html .= '<h3>Vacancies Applied</h3>';
		$return_html .= '<table border="0" class="resultset" cellspacing="4" cellpadding="4" style="margin-left:10px">';
		$return_html .= '<thead><th>&nbsp;</th><th>Vacancy Code</th><th>Vacancy Title</th><th>Employer</th><th>Vacancy Postcode</th><th>Screening Status</th><th>Application Status</th></thead>';

		// applications
		$has_applications = 0;
		if ( sizeof($this->applications) > 0 )
		{
			foreach ( $this->applications as $vacancy_id => $screen_score )
			{
				$screen_level = 'low';
				if ( $screen_score >= 45 && $screen_score <= 70 )
				{
					$screen_level = 'med';
				}
				else if ( $screen_score >= 70 )
				{
					$screen_level = 'high';
				}

				$sql = <<<HEREDOC
					SELECT
						vacancies.job_title,
						vacancies.code,
						vacancies.postcode,
						vacancies.active,
						organisations.legal_name ,
						candidate_applications.*
					FROM
						vacancies INNER JOIN organisations ON vacancies.employer_id = organisations.id
						INNER JOIN candidate_applications ON candidate_applications.`vacancy_id` = vacancies.id AND candidate_applications.`candidate_id` = $this->id
					WHERE vacancies.id = '$vacancy_id' ;
HEREDOC;
				$vacancy_details = DAO::getResultset($link, $sql );
				//$vacancy_details = DAO::getResultset($link, 'select vacancies.job_title, vacancies.code, vacancies.postcode, vacancies.active, organisations.legal_name from vacancies, organisations where vacancies.id = '.$vacancy_id.' and vacancies.employer_id = organisations.id' );
				if( isset($vacancy_details[0][0]) )
				{
					$return_html .= '<tr>';
					if($showIcon)
						$return_html .= '<td><img src="/images/vacancy.jpg" border="0" /></td>';
					else
						$return_html .= '<td></td>';
					$return_html .= '<td><a href="/do.php?_action=view_vacancy&pc='.$vacancy_details[0][2].'&id='.$vacancy_id.'&display='.$this->id	.'">'.$vacancy_details[0][1].'</a></td>';
					$return_html .= '<td>'.$vacancy_details[0][0].'</td>';
					$return_html .= '<td>'.$vacancy_details[0][4].'</td>';
					$return_html .= '<td>'.$vacancy_details[0][2].'</td>';

					if($vacancy_details[0][10] > 65)
						$return_html .= '<td align="center" title="Highly Suitable"><img src="/images/trafficlight-green.gif" border="0" /></td>';
					elseif($vacancy_details[0][10] > 0 AND $vacancy_details[0][10] <= 65 )
						$return_html .= '<td align="center" title="Suitable"><img src="/images/trafficlight-yellow.gif" border="0" /></td>';
					elseif($vacancy_details[0][10] == 0)
						$return_html .= '<td align="center" title="Not Suitable"><img src="/images/trafficlight-red.gif" border="0" /></td>';
					else
						$return_html .= '<td>Not Screened</td>';
					if($vacancy_details[0][9] == 1)
						$application_status = "Candidate Approved for Vacancy";
					elseif($vacancy_details[0][9] == 2)
						$application_status = "Candidate Removed for Vacancy";
					elseif($vacancy_details[0][9] == 3 && (DB_NAME == "am_baltic" || DB_NAME == "am_baltic_demo"))
						$application_status = "Candidate CV Sent to Employer";
					else
						$application_status = "Being Processed";
					$return_html .= '<td>'.$application_status.'</td>';
//					$return_html .= '<td>'.$vacancy_details[0][8].'</td>';
					$return_html .= '</tr>';
					$has_applications = 1;
				}
			}
		}
		if ( $has_applications == 0 ) {
			$return_html .= '<tr>';
			$return_html .= '<td>No Vacancies Applied For</td><td colspan="3" >&nbsp;</td>';
			$return_html .= '</tr>';
		}

		$return_html .= '</table>';
		return $return_html;
	}


	public $id = NULL;
	public $firstnames = NULL;
	public $surname = NULL;
	public $gender = NULL;
	public $ethnicity = NULL;
	public $dob = NULL;
	public $dob_year = NULL;
	public $dob_month = NULL;
	public $dob_day = NULL;
	public $national_insurance = NULL;

	public $address1 = NULL;
	public $address2 = NULL;
	public $address3 = NULL;
	public $borough = NULL;
	public $county = NULL;
	public $region = NULL;
	public $postcode = NULL;
	public $country = NULL;

	public $telephone = NULL;
	public $mobile = NULL;
	public $fax = NULL;
	public $email = NULL;
	public $employment = NULL;
	public $hours_per_week = NULL;
	public $time_last_worked = NULL;
	public $last_education = NULL;
	public $previous_qualification = NULL;
	public $registration = NULL;
	public $username = NULL;
	public $status = NULL;
	public $assessor = NULL;
	public $screening_score = NULL;
	public $comment = NULL;
	public $status_code = NULL;
	public $age = NULL;
	public $learner_name = NULL;
	public $distance = NULL;
	public $next_action = NULL;
	public $app_status = NULL;

	// application capture 
	public $difficulty = array();
	public $disability = array();
	public $level = array();
	public $grade = array();
	public $subject = array();

	public $qualifications = array();
	public $extra_qualifications = array();

	public $employment_history = array();
	public $company_name = array();
	public $job_title = array();
	public $job_skills = array();
	public $hist_sday = array();
	public $hist_smon = array();
	public $hist_syear = array();
	public $hist_eday = array();
	public $hist_emon = array();
	public $hist_eyear = array();

	public $metadata = array();

	public $northing = NULL;
	public $easting = NULL;
	public $latitude = NULL;
	public $longitude = NULL;

	// is candidate linked to a vacancy
	public $enrolled = NULL;

	// notes associated with candidate
	public $candidate_notes = NULL;

	// vacancies candidate is connected to
	// - can be multiple ( rttg request 19/05/2011 )
	public $applications = array();

	public $comp_date = array();
	public $comp_day = array();
	public $comp_mon = array();
	public $comp_year = array();

	public $job_by_email = NULL;
	public $employment_status = NULL;
	public $level_1 = array();
	public $subject_1 = array();
	public $grade_1 = array();
	public $qualificationsOther = array();
	public $candidateCVLink = NULL;
	public $candidatePhotoLink = NULL;

	public $bennett_test = NULL;
	public $numeracy = NULL;
	public $numeracy_diagnostic = NULL;
	public $literacy = NULL;
	public $literacy_diagnostic = NULL;
	public $esol = NULL;
	public $esol_diagnostic = NULL;
	public $source = NULL;
	public $source_other = NULL;
	public $source_vacancy = NULL;
	public $next_of_kin = NULL;
	public $applied_directly = NULL;
	public $has_been_screened = NULL;
	public $consultant = NULL;
	public $nearest_training_location = NULL;
	public $driver = NULL;
	public $next_of_kin_tel = NULL;
	public $next_of_kin_email = NULL;
	public $extra_support_for_app = NULL;
	public $jobatar = NULL;

	/*protected $audit_fields = array(
		'surname'=>'Surname',
		'firstnames'=>'First Name',
		'ethnicity'=>'Ethnicity',
		'dob'=>'Date of Birth',
		'national_insurance'=>'National Insurance',
		'address1'=>'Address Line 1'
	);*/


	protected  $audit_fields = array (
		'firstnames' => 'First Name',
		'surname' => 'Surname',
		'gender' => 'Gender',
		'ethnicity' => 'Ethnicity',
		'dob' => 'Date of Birth',
		'national_insurance' => 'National Insurance',
		'address1' => 'Address Line 1',
		'address2' => 'Address Line 2',
		'borough' => 'Borough',
		'county' => 'County',
		'postcode' => 'Postcode',
		'telephone' => 'Telephone',
		'mobile' => 'Mobile',
		'fax' => 'Fax',
		'email' => 'Email',
		'employment_status' => 'Employment Status',
		'hours_per_week' => 'Hours per week',
		'time_last_worked' => 'Time last worked',
		'last_education' => 'Last Education',
		'previous_qualification' => 'Previous Qualification',
		'created' => 'Created',
		'longitude' => 'Longitude',
		'latitude' => 'Latitude',
		'northing' => 'Northing',
		'easting' => 'Easting',
		'enrolled' => 'Enrolled',
		'username' => 'Username',
		'status' => 'Status',
		'assessor' => 'Assessor',
		'screening_score' => 'Screening_score',
		'comment' => 'Comment',
		'status_code' => 'Status Code',
		'region' => 'Region',
		'job_by_email' => 'Job by email',
		'consultant' => 'Consultant',
		'known' => 'Known',
		'country' => 'Country',
		'branch' => 'Branch',
		'division' => 'Division',
		'source' => 'Candidate Source',
		'status_date' => 'Status Date',
		'address3' => 'Address Line 3',
		'bennett_test' => 'Diagnostic Assessment',
		'numeracy' => 'Numeracy Test',
		'numeracy_diagnostic' => 'Numeracy Diagnostic Taken',
		'literacy' => 'Literacy Test',
		'literacy_diagnostic' => 'Literacy Diagnostic Taken',
		'esol' => 'Esol Test',
		'esol_diagnostic' => 'Esol Diagnostic Taken',
		'source_other' => 'Other Value of Source',
		'next_of_kin' => 'Next of Kin',
		'source_vacancy' => 'Other Value of Vacancy Source',
		'nearest_training_location' => 'Nearest Training Location',
		'driver' => 'Driver',
		'next_of_kin_tel' => 'Next of Kind Telephone Number',
		'next_of_kin_email' => 'Next of Kin Email',
		'jobatar' => 'Jobatar',
		'extra_support_for_app' => 'extra support for apprenticeship if placed'
	);
}
?>