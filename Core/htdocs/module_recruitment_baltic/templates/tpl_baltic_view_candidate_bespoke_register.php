<?php 
  // sector type for application
  $sector_type = '';


  // date drop down populations
  $day = array(array('','dd'),array(1,1),array(2,2),array(3,3),array(4,4),array(5,5),array(6,6),array(7,7),array(8,8),array(9,9),array(10,10),array(11,11),array(12,12),array(13,13),array(14,14),array(15,15),array(16,16),array(17,17),array(18,18),array(19,19),array(20,20),array(21,21),array(22,22),array(23,23),array(24,24),array(25,25),array(26,26),array(27,27),array(28,28),array(29,29),array(30,30),array(31,31));
  $month = array(array('','mon'),array(1,'Jan'),array(2,'Feb'),array(3,'Mar'),array(4,'Apr'),array(5,'May'),array(6,'Jun'),array(7,'Jul'),array(8,'Aug'),array(9,'Sep'),array(10,'Oct'),array(11,'Nov'),array(12,'Dec'));
  $year = array(array('','yyyy'));

  $this_year = date("Y")+2;
  $early_year = $this_year-60;

  for($a = $this_year; $a >= $early_year; $a--) {
  	$year[] = array($a,$a);
  }

	$qualifications_presented = 0;

  // awfully greedy pattern matches - need to change
  // - ie issue with onchange on array[] - removing it so verfiy this
  $day_options = preg_replace("/onchange=\"(.*)\"/","", HTML::select('comp_day[]', $day, '', false, true));
  $day_options = preg_replace("/id=\"(.*)\"/", "", $day_options);
  $mon_options = preg_replace("/onchange=\"(.*)\"/","", HTML::select('comp_mon[]', $month, '', false, true));
  $mon_options = preg_replace("/id=\"(.*)\"/", "", $mon_options);
  $year_options = preg_replace("/onchange=\"(.*)\"/","", HTML::select('comp_year[]', $year, '', false, true));
  $year_options = preg_replace("/id=\"(.*)\"/", "", $year_options);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
 "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Perspective - Sunesis</title>
<!-- link rel="stylesheet" href="/common.css" type="text/css" / -->
<link rel="stylesheet" href="/css/core.css" type="text/css"/>
<link rel="stylesheet" href="/css/open.css" type="text/css"/>
<link rel="stylesheet" href="/print.css" media="print" type="text/css"/>
<?php
	// #176 - allow for client specific styling
	$css_filename = SystemConfig::getEntityValue($link, 'styling');
	if ( $css_filename != '' ) {
		echo '<link rel="stylesheet" href="/css/client/'.$css_filename.'" type="text/css"/>';	
	} 
?>

</head>
<body onload="body_onload()" id="registration">
<?php 
	$filename = DAO::getSingleValue($link, "Select value from configuration where entity='logo'");
	$filename = ($filename=='')?'perspective.png':$filename;
?>
  <div id="recruitment">
    <div id="customerlogo">
      <!--  img src="/images/logos/<?php echo $filename; ?>" alt="Sunesis - <?php echo DB_NAME; ?> candidate registration" / -->
    </div>
<?php

	$vacancy_applications = '';

    if ( isset($_REQUEST['vac_id']) ) {
		$vacancy_applications .= 'You are applying for these vacancies</h3><p>';
		foreach ($_REQUEST['vac_id'] as $key => $value ) {
			$candidate_vacancy = Vacancy::loadFromDatabase($link, $value);
			$sector_type = $candidate_vacancy->vac_desc;
			$vacancy_applications .= '<input type="hidden" name="applications[]" value="'.$candidate_vacancy->id.'" />';
			$vacancy_applications .= '<input type="hidden" name="enrolled" value="1" />';
			$vacancy_applications .= '<strong>'.$candidate_vacancy->code.'</strong> '.$candidate_vacancy->job_title.'<br/>';
		}
		echo '</p>';
	}

	// only ask them the sector specifics & the legals
	if ( isset($_REQUEST['candidate_id']) && $_REQUEST['candidate_id'] != '' ) {
		$previously_answered = 0;
		$candidate_name = '';
		$candidate_sql = 'SELECT candidate.firstnames, lookup_vacancy_type.description FROM candidate LEFT JOIN candidate_applications ON candidate.id = candidate_applications.candidate_id LEFT JOIN vacancies ON candidate_applications.vacancy_id = vacancies.id LEFT JOIN lookup_vacancy_type ON vacancies.type = lookup_vacancy_type.id WHERE candidate.id = '.$_REQUEST['candidate_id'];
		$candidate_information = DAO::getResultset($link, $candidate_sql);
		foreach ( $candidate_information as $name => $value ) {
			$candidate_name = $value[0];
			if ( $value[1] == $sector_type ) {
				$previously_answered = 1;
			}
		}	
	?>
		    <div id="divWarnings"></div>
    		<div id="divMessages">
    			<ul id="status">
    				<?php 
    					if ( $previously_answered !== 1 ) {
    						echo '<li id="status_1" class="active" >Sector Questions</li>';
    						echo '<li id="status_2" >Confirmation</li>';
    					}
    					else {
    						echo '<li id="status_1" class="active" >Confirmation</li>';		
    					}
    				?>
				</ul>
    		</div>
    		<div id="main">
	  			<form name="recruitmentForm" action="/do.php?_action=save_candidate" method="post" enctype="multipart/form-data">
	  				<input type="hidden" name="candidate_id" value="<?php echo $_REQUEST['candidate_id']; ?>" />
	    			<input type="hidden" name="screen_width" />
	    			<input type="hidden" name="screen_height" />
	    			<input type="hidden" name="color_depth" />
	    			<input type="hidden" name="flash" />
	    			<input type="hidden" name="destination" value="<?php echo (isset($_REQUEST['destination'])?htmlspecialchars((string)$_REQUEST['destination']):''); ?>" />	
	    			<div id="wizard">
						<div id="items">		
    	<?php
    	echo '<h3><strong>'.$candidate_name.'</strong>, '.$vacancy_applications;
		$capture_count = 3;
		if ( $previously_answered !== 1 ) {
			$screening_section = 'Additional Information - '.$sector_type;
			foreach ( $registrant->user_metadata as $page => $field_array ) {
				if( $screening_section != $page ) {
					continue;
				}
				echo '<div id="registration_1" class="formentry" >';
				echo '<h1>'.$page.'</h1>';
				echo '<p>Please complete as much of the information in the following form as you can.  Anything marked with an asterisk (*) we need in order to match you to the vacancies.</p>';

				echo '<table>';
				foreach ( $field_array as $title => $type ) {
					$format_titles = explode("_", $title);
					$format_details = explode("_", $type);
					echo '<tr><td>'.$format_titles[1];
					$system_title = preg_replace('/ /', '', strtolower($format_titles[1]));
								
					$element_class = '';
					if ( $format_details[0] == 1 ) {
						$element_class = 'compulsory';
						echo '<span style="color: red">&nbsp; * </span>';
					}
					echo '</td><td>';
					switch ($format_details[1]) {
						case 'text':
							// echo '<textarea class="'.$element_class.'" name="'.$system_title.'"></textarea>';
							echo '<textarea class="'.$element_class.'" name="reg_'.$format_titles[0].'"></textarea>';
							break;
						case 'date':
							echo HTML::select('dob_day', $day, '', false, true); 
							echo HTML::select('dob_month', $month, '', false, true); 
							echo HTML::select('dob_year', $year, '', false, true); 
							break;
						case 'int':
							// echo '<select class="'.$element_class.'" name="'.$system_title.'" >'; 
							echo '<select class="'.$element_class.'" name="reg_'.$format_titles[0].'" >';
							for( $i = 0; $i <= 20; $i++ ) {
								echo '<option value="'.$i.'">'.$i.'</option>';
							}
							echo '</select>';
							break;
						case 'float':
							// echo '<input class="'.$element_class.'" type="text" name="'.$system_title.'"  size="10" maxlength="20"/>'; 
							echo '<input class="'.$element_class.'" type="text" name="reg_'.$format_titles[0].'"  size="10" maxlength="20"/>';
							break;
						case 'checkbox':
							echo $this->present_checkbox_values($link, $format_titles[0]);
							break;
						case 'radio':
							echo $this->present_radio_values($link, $format_titles[0]);
							break;
						case 'select':
							echo $this->present_select_values($link, $format_titles[0]);
							break;
						// strings
						default: 
							// echo '<input class="'.$element_class.'" type="text" name="'.$system_title.'"  size="40" maxlength="100"/>'; 
							echo '<input class="'.$element_class.'" type="text" name="reg_'.$format_titles[0].'"  size="40" maxlength="100"/>'; 
							break;
					}
					echo '</td></tr>';
				}
				echo '</table>';
				echo '	<div class="navigation" >';
				echo '		<button type="button" class="next right button" id="proceed_2" >Proceed &raquo;</button>';
				echo '	</div>';
				echo '</div>';
			}
			echo '<div id="registration_2" class="formentry">';
		}
		else {
			echo '<p>We already have all the information we need to progress your application on these vacancies<br/>Please confirm you agree with our privacy policy and we will be in touch shortly</p>';
			echo '<div id="registration_1" class="formentry">';	
		}
		?>
 					<h1>Privacy Policy</h1>
 					<p>
 						In order for us to use your information, please read the policy below, and click on 'register' if you are happy to send us your details.
 					</p>
 					<table>
						<tr>
							<td>				
								<?php include_once('templates/tpl_terms_and_conditions.php'); ?> 
							</td>
						</tr>
					</table>
    				<div class="navigation" >
    	<?php 
    			if ( $previously_answered !== 1 ) {
    				echo '<button type="button" class="previous button" id="bproceed_1" >&laquo; Back</button>';
    			}
    	?>
						<button onclick="javascript:return save();" class="button" >Register</button>
					</div>
				</div>
			</div>
		</div>
    </form>
  </div>
			<?php 
			$capture_count++;
	}
    // #116 relmes
    elseif ( ( !isset($_REQUEST['msg']) ) ) {
?>
    <div id="divWarnings"></div>
    <div id="divMessages">
    <ul id="status">
    	<li id="status_1" class="active">Personal Information</li>
		<li id="status_2" >Contact Details</li>
<?php
	$capture_count = 3;
	$sector_questions = 0;
	$qualification_header = 0;
	foreach ( $registrant->user_metadata as $page => $field_array )	{ 
		// only display top level section - refine this!!
		if ( !preg_match('/ - /', $page ) ) {
			echo '<li id="status_'.$capture_count.'" '; 
			echo ' >'.$page.'</li>';
			if ( $page == 'Qualifications' ) {
				$qualification_header = 1;
			}
			$capture_count++;
		}
		else if ( 0 === $sector_questions && $vacancy_applications != '' ) {
			echo '<li id="status_'.$capture_count.'" '; 
			echo ' >Sector Questions</li>';
			$sector_questions = $capture_count;
			$capture_count++;
		}
	}
	if ( $qualification_header == 0 ) {
		echo '<li id="status_'.$capture_count.'" ';
		echo ' >Qualifications</li>';
		$capture_count++;
	}
?>
	  <li id="status_<?php echo $capture_count; ?>" >Confirmation</li>
	</ul>
    </div>				
    <div id="main">
	  <form name="recruitmentForm" action="/do.php?_action=save_candidate" method="post" enctype="multipart/form-data">
	    <input type="hidden" name="screen_width" />
	    <input type="hidden" name="screen_height" />
	    <input type="hidden" name="color_depth" />
	    <input type="hidden" name="flash" />
	    <input type="hidden" name="destination" value="<?php echo (isset($_REQUEST['destination'])?htmlspecialchars((string)$_REQUEST['destination']):''); ?>" />
	    <?php // this margin is affecting the display at low resolutions ?>
		<?php 
	echo '<h3>'.$vacancy_applications;
	if ( $vacancy_applications == '' ) {
		echo '</h3>';	
	}
?>
	<div id="wizard">
		<div id="items">
		<div id="registration_1" class="formentry" >
		  	<h1>Personal Information</h1>
		  	<p>
		  		Please complete as much of the information in the following form as you can.  Anything marked with an asterisk (*) we need in order to match you to the vacancies.  
		  	</p>
		  	<!-- p>
		  		If you do not have all the information required please give us a call on 0121-506-9667 to discuss how we can help you.
		  	</p -->
		  	<table>
				<tr>
					<td width="250" class="" style="text-align: left;">First Name(s): <span style="color: red">&nbsp; * </span></td>
					<td><input class="compulsory" type="text" name="firstnames"  size="40" maxlength="100"/></td>
				</tr>
				<tr>
					<td class="" style="text-align: left;">Family Name: <span style="color: red">&nbsp; * </span></td>
					<td><input class="compulsory" type="text" name="surname"  size="40" maxlength="100"/></td>
				</tr>
				<tr>
					<td class="" style="text-align: left;">Gender: <span style="color: red">&nbsp; * </span></td>
					<td style="text-align: left;">
				  	<?php
				    	$gender = "SELECT id, description, null FROM lookup_gender;";
				    	$gender = DAO::getResultset($link, $gender);
				    	array_unshift($gender,array('','Please select one',''));
				    	echo HTML::select('gender', $gender, '', false, true); 
				  	?>
					</td>
				</tr>
				<tr>
					<td class="">Ethnicity: <span style="color: red">&nbsp; * </span></td>
					<td>
				  	<?php 
						$L12_dropdown = DAO::getResultset($link,"SELECT Ethnicity, CONCAT(Ethnicity, ' ', Ethnicity_Desc), null from lis201213.ilr_ethnicity order by Ethnicity;");
						array_unshift($L12_dropdown,array('','Please select one',''));
						echo HTML::select('ethnicity', $L12_dropdown, '', false, true); 
			      	?>
			    	</td>
				</tr>
				<tr>
					<td class="">Date of Birth: <span style="color: red">&nbsp; * </span></td>
					<td>
					<?php
						echo HTML::select('dob_day', $day, '', false, true); 
						echo HTML::select('dob_month', $month, '', false, true); 
						echo HTML::select('dob_year', $year, '', false, true); 
					?>
					</td>
				</tr>		
				<tr>
					<td class="">National Insurance: <br/><small>format: LL######L (no spaces)</small></td>
					<td><input type="text" name="national_insurance" id="national_insurance" size="10" maxlength="10"/></td>
				</tr>
				<tr>
					<td class="" style="text-align: left;">Upload your CV:</td>
					<td><input type="file" name="uploadedfile" id="uploadedfile" /></td>
				</tr>
			  </table>
			<div class="navigation" >
				&nbsp;<button type="button" class="next button" id="proceed_2" >Proceed &raquo;</button>
			</div>
		</div>
		<div id="registration_2" class="formentry" >
			<h1>Contact Details</h1>
			<table>
				<tr>
					<td width="250" class="">House name:</td>
					<td><input type="text" name="address1"  size="40" maxlength="100"/></td>
				</tr>
				<tr>
					<td width="240" class="">Street and number: <span style="color: red">&nbsp; * </span></td>
					<td><input class="compulsory" type="text" name="address2"  size="40" maxlength="100"/></td>
				</tr>
				<tr>
					<td class="">
						Town: <span style="color: red">&nbsp; * </span>
					</td>
					<td>
						<input class="compulsory" type="text" name="borough"  size="40" maxlength="100" />
					</td>
				</tr>
				<tr>
					<td class="">County: <span style="color: red">&nbsp; * </span></td>
					<td>
						<?php echo HTML::select('county', $counties, "", true, true); ?>
					</td>
				</tr>
				<tr>
					<td width="240" class="">Postcode: <span style="color: red">&nbsp; * </span></td>
					<td><input class="compulsory" type="text" name="postcode" id="postcode" size="8" maxlength="100"/></td>
				</tr>
				<?php
				// -------------------------------
				// this was a RTTG requirement - we have removed
				// as not required for existing recruitment staff.
				// -------------------------------
				// <tr>
				// 	<td width="240" class="">Region: <span style="color: red">&nbsp; * </span></td>
				// 	<td>
				// <?php
				// //	$region_dropdown = array(array('North West','North West',''), array('North East','North East',''), array('Midlands','Midlands',''), array('East Midlands','East Midlands',''), array('West Midlands','West Midlands',''), array('London North','London North',''), array('London South','London South',''), array('Peterborough','Peterborough',''), array('Yorkshire','Yorkshire',''));
				// 	$region_dropdown = 'select description, description, null from lookup_vacancy_regions order by description;';
				// 	$region_dropdown = DAO::getResultset($link, $region_dropdown);
				// 	echo HTML::select('region', $region_dropdown, '', true, false);
				//
				// 	</td>
				// </tr>
				// -------------------------------
				?>

				<tr>
					<td width="240" class="">
						Telephone: <span style="color: red">&nbsp; * </span>
						<br/>
						<small>Please enter only numbers and spaces</small>
					</td>
					<td>
						<input class="compulsory" type="text" name="telephone" id="telephone" size="15" maxlength="20"/>
					</td>
				</tr>
				<tr>
					<td width="240" class="">Mobile: <span style="color: red">&nbsp; * </span>
					<br/>
					<small>Please enter only numbers and spaces</small></td>
					<td><input class="compulsory" type="text" name="mobile" id="mobile" size="15" maxlength="20"/></td>
				</tr>
				<tr>
					<td width="240" class="">Fax:</td>
					<td><input type="text" name="fax" id="fax" size="15" maxlength="20"/></td>
				</tr>
				<tr>
					<td width="240" class="">Email:</td>
					<td><input type="text" name="email" id="email" size="20" maxlength="100"/></td>
				</tr>
			</table>
			<div class="navigation" >
				<button type="button" class="previous button" id="bproceed_1" >&laquo; Back</button>
				<button type="button" class="next right button" id="proceed_3" >Proceed &raquo;</button>
			</div>
		</div>
		<?php
		$capture_count = 3;
		foreach ( $registrant->user_metadata as $page => $field_array ) {
			$previous_count = $capture_count-1;
			$next_count = $capture_count+1;
			
			$screening_section = '';
			if ( $sector_questions == $capture_count ) {
				$screening_section = 'Additional Information - '.$sector_type;
				if( $screening_section != $page ) {
					// $capture_count++;
					continue;
				}
			}
			if ( preg_match('/ - /', $page) && $page != $screening_section ) {
				continue;
			}
			
			echo '<div id="registration_'.$capture_count.'" class="formentry" >';
			echo '<h1>'.$page.'</h1>';
			echo '<p>Please complete as much of the information in the following form as you can.  Anything marked with an asterisk (*) we need in order to match you to the vacancies.</p>';

			if ( ( $page == 'Equal Opportunities' ) && ( DB_NAME == 'am_raytheon' || DB_NAME == 'am_ray_recruit' ) ) {
				echo '<p>To assist us in monitoring the effectiveness of our Equality and Diversity policy please provide the following information below.';
				echo '  The information provided is used for monitoring purposes only and plays no part in the selection process.';
				echo '  It is stored and in line with the Data Protection Act 1998, is seen as "Sensitive Personal Data".';
				echo '  This personal data will not be shared with any 3rd party such as a possible employer, and will be stored securely within our protected IT system.';
				echo '  At Raytheon, we welcome applications from any community,';
				echo ' irrespective of age, disability, gender, race, religion, belief or sexual orientation.</p>';
			}

			// standard qualification section - centralise this
			if ( $page == 'Qualifications' ) {
				$this->present_qualification_questions($link);
				$qualifications_presented = 1;
			?>
        		<p>Please answer the questions below</p>
        	<?php
			}
			echo '<table>';
			foreach ( $field_array as $title => $type ) {
				$format_titles = explode("_", $title);
				$format_details = explode("_", $type);
				echo '<tr><td>'.$format_titles[1];
				$system_title = preg_replace('/ /', '', strtolower($format_titles[1]));
				
				
				$element_class = '';
				if ( $format_details[0] == 1 ) {
					$element_class = 'compulsory';
					echo '<span style="color: red">&nbsp; * </span>';
				}
				echo '</td><td>';
				switch ($format_details[1]) {
					case 'text':
						// echo '<textarea class="'.$element_class.'" name="'.$system_title.'"></textarea>';
						echo '<textarea class="'.$element_class.'" name="reg_'.$format_titles[0].'"></textarea>';
						break;
					case 'date':
						echo HTML::select('dob_day', $day, '', false, true); 
						echo HTML::select('dob_month', $month, '', false, true); 
						echo HTML::select('dob_year', $year, '', false, true); 
						break;
					case 'int':
						// echo '<select class="'.$element_class.'" name="'.$system_title.'" >'; 
						echo '<select class="'.$element_class.'" name="reg_'.$format_titles[0].'" >';
						for( $i = 0; $i <= 20; $i++ ) {
							echo '<option value="'.$i.'">'.$i.'</option>';
						}
						echo '</select>';
						break;
					case 'float':
						// echo '<input class="'.$element_class.'" type="text" name="'.$system_title.'"  size="10" maxlength="20"/>'; 
						echo '<input class="'.$element_class.'" type="text" name="reg_'.$format_titles[0].'"  size="10" maxlength="20"/>';
						break;
					case 'checkbox':
						echo $this->present_checkbox_values($link, $format_titles[0]);
						break;
					case 'radio':
						echo $this->present_radio_values($link, $format_titles[0]);
						break;
					case 'select':
						echo $this->present_select_values($link, $format_titles[0]);
						break;
					// strings
					default: 
						// echo '<input class="'.$element_class.'" type="text" name="'.$system_title.'"  size="40" maxlength="100"/>'; 
						echo '<input class="'.$element_class.'" type="text" name="reg_'.$format_titles[0].'"  size="40" maxlength="100"/>'; 
						break;
				}
				echo '</td></tr>';
			}
			echo '</table>';
			echo '	<div class="navigation" >';
			if ( $capture_count > 1 ) {
				echo '		<button type="button" class="previous button" id="bproceed_'.$previous_count.'" >&laquo; Back</button>';
			}
			echo '		<button type="button" class="next right button" id="proceed_'.$next_count.'" >Proceed &raquo;</button>';
			echo '	</div>';
			echo '</div>';
			$capture_count++;
		}
		if ( $qualifications_presented == 0 ) {
			$previous_count++;
			$next_count++;
			echo '<div id="registration_'.$capture_count.'" class="formentry" >';
			echo '<h1>Qualifications</h1>';
			echo '<p>Please complete as much of the information in the following form as you can.  Anything marked with an asterisk (*) we need in order to match you to the vacancies.</p>';
			$this->present_qualification_questions($link);
			echo '	<div class="navigation" >';
			if ( $capture_count > 1 ) {
				echo '		<button type="button" class="previous button" id="bproceed_'.$previous_count.'" >&laquo; Back</button>';
			}
			echo '		<button type="button" class="next right button" id="proceed_'.$next_count.'" >Proceed &raquo;</button>';
			echo '	</div>';
			echo '</div>';
			$capture_count++;
		}

		?>

		<div id="registration_<?php echo $capture_count; ?>" class="formentry">
 			<h1>Privacy Policy</h1>
 			<p>
 				In order for us to use your information, please read the policy below, and click on 'register' if you are happy to send us your details.
 			</p>
 			<table>
				<tr>
					<td>				
					<?php include_once('templates/tpl_terms_and_conditions.php'); ?> 
					</td>
				</tr>
			</table>
    		<div class="navigation" >
    		   	<button type="button" class="previous button" id="bproceed_<?php echo --$capture_count; ?>" >&laquo; Back</button>
				<button onclick="javascript:return save();" class="button" >Register</button>
			</div>
		</div>
	  </div>
	</div>
    </form>
  </div>
  <script type="text/javascript">
	//<![CDATA[
	/*
    * relmes: extra validation for candidate registration requirements
    */
	var ele = document.getElementById("national_insurance");
	ele.validate = function() {
		/*
		* only concerned with ni if its been filled in.
		*/
		if ( this.value != "" ) {
			if( !this.value.match( /^[A-Za-z]{2}[0-9]{2}[0-9]{2}[0-9]{2}[A-Za-z]{1}$/ ) ) {
				alert("Incorrect format of National Insurance Number 'LL######L'");
				this.focus();
		    	return false;
			}
		}
		return true;
	}

	var cv_upload = document.getElementById("uploadedfile");
	cv_upload.validate = function() {
		/*
		* only concerned with CV if its being uploaded.
		*/
		if ( this.value != "" ) {
			extArray = new Array(".doc", ".pdf", ".docx");
			ext = this.value.slice(this.value.indexOf(".")).toLowerCase();
		    for (var i = 0; i < extArray.length; i++) {
		    	if (extArray[i] == ext) { 
			    	return true; 
			    }
		    }
		    alert("We only accept .doc or .pdf files for your CV");
			this.focus();
		    return false;
		}
	}


	var email_valid = document.getElementById("email");
    email_valid.validate = function() {
    	/*
		* only concerned with email if its been filled in.
		*/
        if ( this.value != "" ) {
    		if( !this.value.match( /^[-+.\w]{1,64}@[-.\w]{1,64}\.[-.\w]{2,6}$/ ) ) {
				alert("Incorrect format for Email Address");
				this.focus();
		    	return false;
    		}
		}
		return true;
    }

    var mobile_valid = document.getElementById("mobile");
    mobile_valid.validate = function() {
    	/*
		* only concerned with email if its been filled in.
		*/
        if ( this.value != "" ) {
    		if( !this.value.match( /^[0-9\s]{1,20}$/ ) ) {
				alert("Incorrect format for Mobile Number");
				this.focus();
		    	return false;
    		}
		}
		return true;
    }

    var fax_valid = document.getElementById("fax");
    fax_valid.validate = function() {
    	/*
		* only concerned with email if its been filled in.
		*/
        if ( this.value != "" ) {
    		if( !this.value.match( /^[0-9\s]{1,20}$/ ) ) {
				alert("Incorrect format for Mobile Number");
				this.focus();
		    	return false;
    		}
		}
		return true;
    }

    var telephone_valid = document.getElementById("telephone");
    telephone_valid.validate = function() {
    	/*
		* only concerned with email if its been filled in.
		*/
        if ( this.value != "" ) {
    		if( !this.value.match( /^[0-9\s]{1,20}$/ ) ) {
				alert("Incorrect format for Telephone Number");
				this.focus();
		    	return false;
    		}
		}
		return true;
    }

    var postcode_valid = document.getElementById("postcode");
    postcode_valid.validate = function() {
            	if( !this.value.match(/(^gir\s0aa$)|(^[a-pr-uwyz]((\d{1,2})|([a-hk-y]\d{1,2})|(\d[a-hjks-uw])|([a-hk-y]\d[abehmnprv-y]))\s\d[abd-hjlnp-uw-z]{2}$)/i ) ) {
			alert("Incorrect format for Postcode");
			this.focus();
		    return false;
		}
		return true;
    }
	//]]>
	</script>
<?php 
    } 
    else if ( isset($_REQUEST['msg']) ) {
    	// check if the enrolled flag is set to allow 
    	// a registrant to apply for a particular course
    	$vacancy_enroll = '';
    	if ( $_REQUEST['msg'] == 1 ) {
			if ( isset($_REQUEST['enrolled']) && is_int($_REQUEST['enrolled']) ) {
				$vacancy_enroll = $_REQUEST['enrolled'];
				$candidate_vacancy = Vacancy::loadFromDatabase($link, $vacancy_enroll);
				$candidate_vacancy->update($link);
			}
		?>
			
	<div id="divMessages">
		Your registration has been successful.
	</div>	
	<div id="main" style="text-align: center;" >
		Thank you for taking the time to complete our registration form. [<?php echo $vacancy_enroll; ?>]
		<br/>
		You have successfully registered for this vacancy, one of our team will call you within 48 hours if you need to contact us, please email <a href="mailto:<?php echo SystemConfig::getEntityValue($link, 'recruitment_email'); ?>"><?php echo SystemConfig::getEntityValue($link, 'recruitment_email'); ?></a>
        or call on: <?php echo SystemConfig::getEntityValue($link, 'recruitment_contact'); ?>
        <br/>
        <br/>
		Return to <a href="<?php echo SystemConfig::getEntityValue($link, 'recruitment_home'); ?>">our website</a>
		<br/> 
	</div>		   	
<?php    
    	}
    	elseif( $_REQUEST['msg'] == 2 ) {
?>
	<div id="divMessages">
		We already have your details.
	</div>	
	<div id="main" style="text-align: center;" >
		If you would like to speak to anyone regarding this, please use the details below.</a>
		<br/>
		Please contact us at: <a href="mailto:<?php echo SystemConfig::getEntityValue($link, 'recruitment_email'); ?>"><?php echo SystemConfig::getEntityValue($link, 'recruitment_email'); ?></a>
		<br/>
        Or help desk hotline: <?php echo SystemConfig::getEntityValue($link, 'recruitment_contact'); ?>
	</div>		   	
<?php      		
    	}
    	elseif( $_REQUEST['msg'] == 3 ) {
?>
	<div id="divMessages">
		We are sorry, we have been unable to save your details at this time.
	</div>	
	<div id="main" style="text-align: center;" >
		If you would like to speak to anyone regarding this, please use the details below.</a>
		<br/>
		Please contact us at: <a href="mailto:<?php echo SystemConfig::getEntityValue($link, 'recruitment_email'); ?>"><?php echo SystemConfig::getEntityValue($link, 'recruitment_email'); ?></a>
		<br/>
        Or help desk hotline: <?php echo SystemConfig::getEntityValue($link, 'recruitment_contact'); ?>
	</div>		   	
<?php      		
    	}	
    }
?>
</div>
<script src="/js/jquery.min.js" type="text/javascript"></script>
<script src="/common.js" type="text/javascript"></script>
<script language="javascript" src="/js/sunesis-registration.js" type="text/javascript"></script>
<noscript>
	<?php include_once('templates/tpl_noscript.php'); ?>
</noscript>
</body>
</html>
