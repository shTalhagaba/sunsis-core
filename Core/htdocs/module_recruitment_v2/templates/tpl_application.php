<?php /* @var $view RecViewVacancies */ ?>
<?php /* @var $candidate RecCandidate */ ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
	"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xmlns="http://www.w3.org/1999/html">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1"/>
	<title>Application Form</title>

	<link rel="stylesheet" href="/jquery-ui/css/jquery-ui-1.8.24.custom.css" type="text/css"/>
	<link href="css/jquery.steps.css?n=<?php echo time(); ?>" rel="stylesheet">
	<link rel="stylesheet" type="text/css" href="../module_recruitment_v2/css/application.css?n=<?php echo time(); ?>"/>
	<link rel="stylesheet" type="text/css" href="../common.css?n=<?php echo time(); ?>"/>
	<link rel="stylesheet" type="text/css" href="/assets/css/jquery.timepicker.css" />

	<script src="jquery-ui/js/jquery-1.11.0.min.js"></script>
	<script src="jquery-ui/js/jquery-ui-1.8.24.custom.min.js"></script>
	<script src="js/jquery.steps.js"></script>
	<script src="js/form-validation/jquery.validate.min.js"></script>
	<script type="text/javascript" src="/assets/js/jquery/jquery.timepicker.js"></script>

	<!--<script src="/common.js?n=<?php /*echo time(); */?>" type="text/javascript"></script>-->
	<script src="../module_recruitment_v2/js/application.js?n=<?php echo time(); ?>"></script>


	<script type="text/javascript">
		$(function(){

			jQuery.browser = {};
			(function () {
				jQuery.browser.msie = false;
				jQuery.browser.version = 0;
				if (navigator.userAgent.match(/MSIE ([0-9]+)\./)) {
					jQuery.browser.msie = true;
					jQuery.browser.version = RegExp.$1;
				}
			})();

			$( ".datepicker" ).datepicker({
				dateFormat: 'dd/mm/yy',
				yearRange: 'c-50:c+50',
				changeMonth: false,
				changeYear: true,
				constrainInput: true,
				buttonImage: "/images/calendar-icon.gif",
				buttonImageOnly: true,
				buttonText: "Show calendar",
				showOn: "both",
				showAnim: "fadeIn"
			});

			$('input[name=dob]').datepicker("option", "yearRange", "-50:+1");
			$('input[name=dob]').datepicker("option", "defaultDate", "-18y");

			$(".timebox").timepicker({ timeFormat: 'H:i' });

			$('.timebox').bind('timeFormatError timeRangeError', function() {
				this.value = '';
				alert("Please choose a valid time");
				this.focus();
			});
		});

	</script>


	<style type="text/css">

		.searchPanel {
			width: 30%;
			float: left;
			min-width: 400px;
			border: 3px solid #B5B8C8;
			border-radius: 15px;
		}

		.resultPanel {
			margin-left: 35%;
			border: 3px solid #B5B8C8;
			border-radius: 15px;
		}

		#messageBox {
			position: relative;
			top: 35%;
			left: 10%;
			margin-top: 10px;
			margin-left: -70px;
			width: 500px;
		}
		tr td:first-child {
			-moz-border-radius-topleft: 5px;
			-moz-border-radius-bottomleft: 5px;
			-webkit-border-top-left-radius: 5px;
			-webkit-border-bottom-left-radius: 5px;
			padding: 5px;
		}

		tr td:last-child {
			-moz-border-radius-topright: 5px;
			-moz-border-radius-bottomright: 5px;
			-webkit-border-top-right-radius: 5px;
			-webkit-border-bottom-right-radius: 5px;
		}
		.trSectionHeading {
			letter-spacing: 1px;
			font-size: 150%;
			font-weight: bold;
			color: #ffffff;
		}
	</style>

</head>
<?php
$logo = SystemConfig::getEntityValue($link, 'logo');
if($logo == '')
	$logo = 'SUNlogo.jpg';
?>
<body>
<div id="wrapper">
<div id="headerwrap">
	<div id="header">
		<div id="logo"><img src="/images/logos/<?php echo $logo; ?>" height="50" /></div>
	</div>
</div>

<div id="contentwrap">
<div id="content">
<div class="searchPanel">
	<form id="frmSearchVacancies" name="frmSearchVacancies" action="/do.php?_action=search_vacancies" method="post" autocomplete="off">
		<table>
			<tr>
				<td><label for="sector">Sector:</label></td>
				<?php echo '<td>' . HTML::select('sector', $type_ddl, $sector, true, false, true, 1, ' style="min-width:270px;" ') . '</td>'; ?>
			</tr>
			<tr>
				<td><label for="region">Region:</label></td>
				<?php echo '<td>' . HTML::select('region', $region_ddl, $region, true) . '</td>'; ?>
			</tr>
			<tr>
				<td><label for="keywords">Keywords:</label></td>
				<?php echo '<td><input type="text" name="keywords" id="keywords" value="' . $keywords . '" /></td>'; ?>
			</tr>
			<tr>
				<td colspan="2" align="right"><span style="width: 95%; padding-top: 5px; padding-bottom: 5px; font-size: 1.5em;" class="recButton" onclick="searchVacancies();">Search &raquo;</span></td>
			</tr>
			<?php if(DB_NAME == "am_superdrug"){?>
			<tr>
				<td colspan="2"><span style="width: 95%; padding-top: 5px; padding-bottom: 5px; font-size: 1.2em; background-color: #FF69B4" class="recButton" onclick="window.open('https://www.superdrug.jobs/see-all-vacancies.html','_self');">Not interested in apprenticeships? Click here</span></td>
			</tr>
			<?php } ?>
		</table>
	</form>
</div> <!--searchPanel-->

<div class="resultPanel" style="min-width: 900px;">
<step style="min-height: 100px;">
<legend align="center"></legend>
<?php if ( !isset($_REQUEST['msg']) ) { ?>
<div id="wizard" style="padding: 6px;" >
<form id="recruitmentForm" action="/do.php?_action=save_application" method="post"  autocomplete="off">
	<?php
	if ( isset($_REQUEST['vacancy_id']) )
	{
		$candidate_vacancy = RecVacancy::loadFromDatabase($link, $_REQUEST['vacancy_id']);
	?>
	<div>
		<step class="fieldsetWithWhiteBorder">
			<legend><?php echo $returning_candidate_message . '<p><br></p>'; ?>You are applying for: <a href="do.php?_action=vacancy_detail&vacancy_id=<?php echo $candidate_vacancy->id; ?>"><?php echo $candidate_vacancy->vacancy_title; ?> (Reference: <?php echo $candidate_vacancy->vacancy_reference; ?>)</a></legend>
			<input type="hidden" name="vacancy_id" value="<?php echo $candidate_vacancy->id; ?>" />
		</step>
	</div>
	<?php
	}
	?>

<input type="hidden" name="candidate_id" id="candidate_id" value="<?php echo $candidate->id; ?>" />
<input type="hidden" name="hascomefrom" value="<?php echo md5('hascomefromsunesiserec'); ?>" id="hascomefrom" />

<?php if($candidate->id != '' && !is_null($candidate->id) && isset($candidate_vacancy)) { ?>
<h3>Application</h3>
	<step class="fieldsetWithWhiteBorder">
		<table width="100%">
			<tr bgcolor="#FF69B4"><td colspan="2" align="center"><span class="trSectionHeading">Update Your CV</span></td></tr>
			<tr>
				<td align="right"><label for="file">Upload CV:</label></td>
				<td align="left"><input class="optional" type="file" name="uploadedfile" id="uploadedfile" style="max-width: 300px; min-width: 300px;" /></td>
			</tr>
		</table>
		<table width="100%">
			<tr bgcolor="#FF69B4"><td colspan="3" align="center"><span class="trSectionHeading">Application Questions</span></td></tr>
			<tr>
				<td valign="top"><strong><i><?php echo $candidate_vacancy->getSupplementaryQuestion1Description($link); ?></i></strong></td>
				<td colspan="2"><textarea rows="5" class="compulsory" cols="70" id="supplementary_question_1_answer" name="supplementary_question_1_answer"></textarea></td>
			</tr>
			<tr>
				<td valign="top"><strong><i><?php echo $candidate_vacancy->getSupplementaryQuestion2Description($link); ?></i></strong></td>
				<td colspan="2"><textarea class="compulsory" rows="5" cols="70" id="supplementary_question_2_answer" name="supplementary_question_2_answer"></textarea></td>
			</tr>
		</table>
		<?php
		$killer_questions = DAO::getResultset($link, "SELECT * FROM rec_vacancy_questions INNER JOIN rec_questions ON rec_vacancy_questions.question_id = rec_questions.id WHERE rec_questions.type = '2' AND vacancy_id = '{$candidate_vacancy->id}'", DAO::FETCH_ASSOC);
		if((isset($killer_questions) && count($killer_questions) > 0))
		{
			echo '<table width="100%">';
			foreach($killer_questions AS $kq)
			{
				echo '<tr>';
				echo '<td colspan="2"><strong><i>' . $kq['description'] . '</i></strong></td>';
				echo '<td>' . HTML::select('q_a_'.$kq['question_id'], $yes_no_options, '', true, true) . '</td>';
				echo '</tr>';
			}
			echo '</table>';
		}
		$softer_questions = DAO::getResultset($link, "SELECT * FROM rec_vacancy_questions INNER JOIN rec_questions ON rec_vacancy_questions.question_id = rec_questions.id WHERE rec_questions.type IN ('3', '0', '1') AND vacancy_id = '{$candidate_vacancy->id}'", DAO::FETCH_ASSOC);
		if((isset($softer_questions) && count($softer_questions) > 0))
		{
			echo '<table width="100%">';
			foreach($softer_questions AS $sq)
			{
				$class = 'optional';
				if($sq['type'] == '3')
					$class = 'compulsory';
				echo '<tr>';
				echo '<td valign="top"><strong><i>' . $sq['description'] . '</i></strong></td>';
				echo '<td valign="top"><textarea class="'.$class.'" rows="5" cols="70" id="q_a_'.$sq['question_id'].'" name="q_a_'.$sq['question_id'].'"></textarea></td>';
				echo '</tr>';
			}
			echo '</table>';
		}
		?>
		<table style="text-align: center" width="100%">
			<tr bgcolor="#FF69B4"><td colspan="8" align="center"><span class="trSectionHeading">Availability to work</span></td></tr>
			<tr><th></th><th>Monday</th><th>Tuesday</th><th>Wednesday</th><th>Thursday</th><th>Friday</th><th>Saturday</th><th>Sunday</th></tr>
			<tr>
				<th>Start Time</th>
				<td><input class="timebox" type="text" id="mon_start_time" name="mon_start_time" size="5" /></td>
				<td><input class="timebox" type="text" id="tue_start_time" name="tue_start_time" size="5" /></td>
				<td><input class="timebox" type="text" id="wed_start_time" name="wed_start_time" size="5" /></td>
				<td><input class="timebox" type="text" id="thu_start_time" name="thu_start_time" size="5" /></td>
				<td><input class="timebox" type="text" id="fri_start_time" name="fri_start_time" size="5" /></td>
				<td><input class="timebox" type="text" id="sat_start_time" name="sat_start_time" size="5" /></td>
				<td><input class="timebox" type="text" id="sun_start_time" name="sun_start_time" size="5" /></td>
			</tr>
			<tr>
				<th>End Time</th>
				<td><input class="timebox" type="text" id="mon_end_time" name="mon_end_time" size="5" /></td>
				<td><input class="timebox" type="text" id="tue_end_time" name="tue_end_time" size="5" /></td>
				<td><input class="timebox" type="text" id="wed_end_time" name="wed_end_time" size="5" /></td>
				<td><input class="timebox" type="text" id="thu_end_time" name="thu_end_time" size="5" /></td>
				<td><input class="timebox" type="text" id="fri_end_time" name="fri_end_time" size="5" /></td>
				<td><input class="timebox" type="text" id="sat_end_time" name="sat_end_time" size="5" /></td>
				<td><input class="timebox" type="text" id="sun_end_time" name="sun_end_time" size="5" /></td>
			</tr>
		</table>
	</step>
<?php } else {?>

<h3>Personal Information</h3>
<step class="fieldsetWithWhiteBorder">
	<table width="100%">
		<tr bgcolor="#FF69B4"><td colspan="2" align="center"><span class="trSectionHeading">Basic Details</span></td></tr>
		<tr><td align="right"><label for="firstnames">First Name(s) *:</label></td><td align="left"><input id="firstnames" name="firstnames" type="text" class="compulsory" value="<?php echo !is_null($candidate->firstnames)?$candidate->firstnames:$firstnames; ?>" size="30" style="max-width: 300px; min-width: 300px;" /></td></tr>
		<tr><td align="right"><label for="surname">Surname *:</label></td><td align="left"><input id="surname" name="surname" type="text" class="compulsory" value="<?php echo !is_null($candidate->surname)?$candidate->surname:$surname; ?>" style="max-width: 300px; min-width: 300px;" /></td></tr>
		<tr>
			<td align="right"><label for="gender">Gender *:</label></td>
			<td align="left"><?php echo HTML::select('gender', $genderDDL, $candidate->gender, true, true, true, '1', ' style="max-width: 300px; min-width: 300px;" '); ?></td>
		</tr>
		<tr>
			<td align="right"><label for="ethnicity">Ethnicity *:</label></td>
			<td align="left"><?php echo HTML::select('ethnicity', $ethnicityDDL, $candidate->ethnicity, true, true, true, 1, ' style="max-width: 300px; min-width: 300px;" '); ?></td>
		</tr>
		<tr><td align="right"><label for="dob">Date of Birth *:</label></td><td align="left"><?php echo HTML::datebox('dob', !is_null($candidate->dob)?$candidate->dob:$dob, true) ?></td></tr>
		<!--<tr><td align="right"><label for="dob">Date of Birth *:</label></td><td align="left"><input class="datepicker compulsory" type="text" id="input_dob" name="dob" value="" size="10" maxlength="10" placeholder="dd/mm/yyyy" /></td></tr>-->
		<tr><td align="right"><label for="national_insurance">National Insurance *:</label></td><td align="left"><input id="national_insurance" name="national_insurance" type="text" class="compulsory" value="<?php echo $candidate->national_insurance; ?>" style="max-width: 300px; min-width: 300px;" /></td></tr>
		<tr>
			<td align="right"><label for="file">Upload CV:</label></td>
			<td align="left">
				<input class="optional" type="file" name="uploadedfile" id="uploadedfile" style="max-width: 300px; min-width: 300px;" />
				<br><span style="font-size:smaller; color:gray;font-style:italic">Allowed files types: .pdf, .doc, .docx, .txt, .zip</span>
			</td>
		</tr>
	</table>
	<table width="100%">
		<tr bgcolor="#FF69B4"><td colspan="2" align="center"><span class="trSectionHeading">Contact Information</span></td></tr>
		<tr><td align="right"><label for="address1">Address Line 1 *:</label></td><td align="left"><input id="address1" name="address1" type="text" class="compulsory" value="<?php echo $candidate->address1; ?>" style="max-width: 300px; min-width: 300px;" /></td></tr>
		<tr><td align="right"><label for="address2">Address Line 2:</label></td><td align="left"><input id="address2" name="address2" type="text" class="optional" value="<?php echo $candidate->address2; ?>" style="max-width: 300px; min-width: 300px;" /></td></tr>
		<tr><td align="right"><label for="borough">Address Line 3:</label></td><td align="left"><input id="borough" name="borough" type="text" class="optional" value="<?php echo $candidate->borough; ?>" style="max-width: 300px; min-width: 300px;" /></td></tr>
		<tr>
			<td align="right"><label for="county">County *:</label></td>
			<td align="left"><?php echo HTML::select('county', $countiesDDL, $candidate->county, true, true, true, '1', ' style="max-width: 300px; min-width: 300px;" '); ?></td>
		</tr>
		<tr><td align="right"><label for="postcode">Postcode *:</label></td><td align="left"><input id="postcode" name="postcode" type="text" class="compulsory" value="<?php echo !is_null($candidate->postcode)?$candidate->postcode:$postcode; ?>" style="max-width: 300px; min-width: 300px;" /></td></tr>
		<tr><td align="right"><label for="telephone">Telephone *:</label></td><td align="left"><input type="text" name="telephone" id="telephone" class="compulsory" size="15" maxlength="20" value="<?php echo $candidate->telephone; ?>" style="max-width: 300px; min-width: 300px;" /></td></tr>
		<tr><td align="right"><label for="mobile">Mobile *:</label></td><td align="left"><input type="text" name="mobile" id="mobile" class="compulsory" size="15" maxlength="20" value="<?php echo $candidate->mobile; ?>" style="max-width: 300px; min-width: 300px;" /></td></tr>
		<tr><td align="right"><label for="email">Email *:</label></td><td align="left"><input class="compulsory" type="text" name="email" id="email" size="20" maxlength="100" value="<?php echo $candidate->email; ?>" style="max-width: 300px; min-width: 300px;" /></td></tr>
		<tr><td align="right"><label for="guardian_email">Parent/Guardian Email *:</label></td><td align="left"><input class="compulsory" type="text" name="guardian_email" id="guardian_email" size="20" maxlength="100" value="<?php echo $candidate->guardian_email; ?>" style="max-width: 300px; min-width: 300px;" /></td></tr>
		<tr><td align="right"><label for="guardian_contact">Parent/Guardian Contact *:</label></td><td align="left"><input class="compulsory" type="text" name="guardian_contact" id="guardian_contact" size="20" maxlength="20" value="<?php echo $candidate->guardian_contact; ?>" style="max-width: 300px; min-width: 300px;" /></td></tr>
	</table>
</step>

<h3>Study History</h3>
<step class="fieldsetWithWhiteBorder">
	<!--<table width="100%">
		<tr bgcolor="#FF69B4"><td colspan="2" align="center"><span class="trSectionHeading">Study History</span></td></tr>
		<tr>
			<td align="right">Highest education completed:</td>
			<td align="left"><?php /*echo HTML::select('last_education', $PriorAttain_dropdown, $candidate->last_education, false, false); */?> </td>
		</tr>
	</table>
	<hr>-->
	<?php
	?>
		<table width="100%" style="font-size: smaller;" cellspacing="0">
			<tr bgcolor="#FF69B4"><td colspan="5" align="center"><span class="trSectionHeading">Study History</span></td></tr>
			<tr><th align="left" width="200">GCSE/A/AS Level</th><th align="left" width="200">Subject</th><th align="left" width="200">Grade</th><th>Date Completed</th><th>School/Institution</th></tr>
			<tbody>
			<tr>
				<td>GCSE *</td>
				<td>English Language</td>
				<td>
					<?php $qual_grades = DAO::getResultset($link,"SELECT id, description, NULL FROM lookup_gcse_grades WHERE description NOT IN ('Credit', 'Distinction', 'Distinction*', 'Merit') ORDER BY id;", DAO::FETCH_NUM);
					echo HTML::select('gcse_english_grade', $qual_grades, isset($candidate->getGCSEEnglishDetails($link)->qualification_grade)?$candidate->getGCSEEnglishDetails($link)->qualification_grade:'', true, true, true, 1, ' style="max-width: 100px; min-width: 50px;" ');
					?>
				</td>
				<td><?php echo HTML::datebox('gcse_english_date_completed', isset($candidate->getGCSEEnglishDetails($link)->qualification_date)?$candidate->getGCSEEnglishDetails($link)->qualification_date:''); ?></td>
				<td><input type="text" name="gcse_english_school" id="gcse_english_school" value="<?php echo isset($candidate->getGCSEEnglishDetails($link)->institution)?$candidate->getGCSEEnglishDetails($link)->institution:''; ?>" /></td>
			</tr>
			<tr>
				<td>GCSE *</td>
				<td>Maths</td>
				<td>
					<?php $qual_grades = DAO::getResultset($link,"SELECT id, description, NULL FROM lookup_gcse_grades WHERE description NOT IN ('Credit', 'Distinction', 'Distinction*', 'Merit') ORDER BY id;", DAO::FETCH_NUM);
					echo HTML::select('gcse_maths_grade', $qual_grades, isset($candidate->getGCSEMathsDetails($link)->qualification_grade)?$candidate->getGCSEMathsDetails($link)->qualification_grade:'', true, true, true, 1,  ' style="max-width: 100px; min-width: 50px;" ');
					?>
				</td>
				<td><?php echo HTML::datebox('gcse_maths_date_completed', isset($candidate->getGCSEMathsDetails($link)->qualification_date)?$candidate->getGCSEMathsDetails($link)->qualification_date:''); ?></td>
				<td><input type="text" name="gcse_maths_school" id="gcse_maths_school" value="<?php echo isset($candidate->getGCSEMathsDetails($link)->institution)?$candidate->getGCSEMathsDetails($link)->institution:''; ?>" /></td>
			</tr>
			<tr>
				<td><?php echo HTML::select('level1', $PriorAttain_dropdown, isset($qualifications['level1'])?$qualifications['level1']:'', true, false, true, 1,  ' style="max-width: 150px; min-width: 50px;" ');?></td>
				<td><input type="text" name="subject1" id="subject1" value="<?php echo isset($qualifications['subject1'])?$qualifications['subject1']:''; ?>" /></td>
				<td>
					<?php $qual_grades = DAO::getResultset($link,"SELECT id, description, NULL FROM lookup_gcse_grades ORDER BY id;", DAO::FETCH_NUM);
					echo HTML::select('grade1', $qual_grades, isset($qualifications['grade1'])?$qualifications['grade1']:'', true, false, true, 1,  ' style="max-width: 50; min-width: 50px;" ');
					?>
				</td>
				<td><?php echo HTML::datebox('date_completed1', isset($qualifications['date1'])?$qualifications['date1']:''); ?></td>
				<td><input type="text" name="date_school1" id="date_school1" value="<?php echo isset($qualifications['institution1'])?$qualifications['institution1']:''; ?>" /></td>
			</tr>
			<tr>
				<td><?php echo HTML::select('level2', $PriorAttain_dropdown, isset($qualifications['level2'])?$qualifications['level2']:'', true, false, true, 1,  ' style="max-width: 150px; min-width: 50px;" ');?></td>
				<td><input type="text" name="subject2" id="subject2" value="<?php echo isset($qualifications['subject2'])?$qualifications['subject2']:''; ?>" /></td>
				<td>
					<?php $qual_grades = DAO::getResultset($link,"SELECT id, description, NULL FROM lookup_gcse_grades ORDER BY id;", DAO::FETCH_NUM);
					echo HTML::select('grade2', $qual_grades, isset($qualifications['grade2'])?$qualifications['grade2']:'', true, false, true, 1,  ' style="max-width: 50; min-width: 50px;" ');
					?>
				</td>
				<td><?php echo HTML::datebox('date_completed2', isset($qualifications['date2'])?$qualifications['date2']:''); ?></td>
				<td><input type="text" name="date_school2" id="date_school2" value="<?php echo isset($qualifications['institution2'])?$qualifications['institution2']:''; ?>" /></td>
			</tr>
			<tr>
				<td><?php echo HTML::select('level3', $PriorAttain_dropdown, isset($qualifications['level3'])?$qualifications['level3']:'', true, false, true, 1,  ' style="max-width: 150px; min-width: 50px;" ');?></td>
				<td><input type="text" name="subject3" id="subject3" value="<?php echo isset($qualifications['subject3'])?$qualifications['subject3']:''; ?>" /></td>
				<td>
					<?php $qual_grades = DAO::getResultset($link,"SELECT id, description, NULL FROM lookup_gcse_grades ORDER BY id;", DAO::FETCH_NUM);
					echo HTML::select('grade3', $qual_grades, isset($qualifications['grade3'])?$qualifications['grade3']:'', true, false, true, 1,  ' style="max-width: 50; min-width: 50px;" ');
					?>
				</td>
				<td><?php echo HTML::datebox('date_completed3', isset($qualifications['date3'])?$qualifications['date3']:''); ?></td>
				<td><input type="text" name="date_school3" id="date_school3" value="<?php echo isset($qualifications['institution3'])?$qualifications['institution3']:''; ?>" /></td>
			</tr>
			</tbody>
		</table>
</step>

<h3>Employment Status</h3>
<step class="fieldsetWithWhiteBorder">
	<table width="100%">
		<tr bgcolor="#FF69B4"><td colspan="2" align="center"><span class="trSectionHeading">Employment History</span></td></tr>
		<tr>
			<td align="right"><label for="employment_status">What is your current employment status?:</label></td>
			<td align="left">
				<?php
				$sql = "SELECT id, status_description, NULL FROM lookup_candidate_employment_status ORDER BY id;";
				$employment_statuses = DAO::getResultSet($link, $sql);
				echo HTML::select('employment_status', $employment_statuses, $candidate->employment_status, true, false, true, '1', ' style="max-width: 300px; min-width: 300px;" ');
				?>
			</td>
		</tr>
		<tr>
			<td align="right"><label for="hours_per_week">If employed, how many hours per week:</label></td>
			<td align="left"><input type="text" name="hours_per_week" id="hours_per_week" size="2" value="<?php echo $candidate->hours_per_week; ?>" /><span style="font-size: 10px;">Enter only digits</span></td>
		</tr>
		<tr>
			<td align="right"><label for="time_last_worked">If not employed, when was the last time that you worked:</label></td>
			<td align="left">
				<?php $last_time_worked = array(array('0','Please select one'),array('1','Not yet been employed'),array('6','Less than 6 months'),array('11','6-11 months'),array('23','12-23 months'),array('35','24-35 months'),array('36','Over 36 months'));
				echo HTML::select('time_last_worked', $last_time_worked, $candidate->time_last_worked, false, false, false, '1', ' style="max-width: 300px; min-width: 300px;" ');
				?>
			</td>
		</tr>
	</table>
	<hr>
	<step class="fieldsetWithWhiteBorder">
		<table width="100%" id="tbl_employment" style="font-size: smaller;">
			<thead><th>Company Name</th><th>Job Title</th><th>Start Date</th><th>End Date</th><th>Skills</th></thead>
			<tbody>
			<?php
			for($i = 1; $i <= 5; $i++)
			{
				$company_name = isset($employments['company_name'.$i])?$employments['company_name'.$i]:'';
				$job_title = isset($employments['job_title'.$i])?$employments['job_title'.$i]:'';
				$skills = isset($employments['skills'.$i])?$employments['skills'.$i]:'';
				echo '<tr>';
				echo '<td valign="top"><input type="text" name="company_name'.$i.'" id="company_name'.$i.'" value="'.$company_name.'" /></td>';
				echo '<td valign="top"><input type="text" name="job_title'.$i.'" id="job_title'.$i.'" value="'.$job_title.'" /></td>';
				echo '<td valign="top">' . HTML::datebox('start_date'.$i, isset($employments['start_date'.$i])?$employments['start_date'.$i]:'', false) . '</td>';
				echo '<td valign="top">' . HTML::datebox('end_date'.$i, isset($employments['end_date'.$i])?$employments['end_date'.$i]:'', false) . '</td>';
				echo '<td valign="top"><textarea name="skills'.$i.'" id="skills'.$i.'">' . $skills . '</textarea></td>';
				echo '</tr>';
			}
			?>
			</tbody>
		</table>
	</step>
</step>

<?php if(isset($candidate_vacancy)){// if vacancy has been selected for applying?>
<h3>Application Questions</h3>
<step class="fieldsetWithWhiteBorder">
	<table width="100%">
		<tr bgcolor="#FF69B4"><td colspan="3" align="center"><span class="trSectionHeading">Application Questions</span></td></tr>
		<tr>
			<td valign="top"><strong><i><?php echo $candidate_vacancy->getSupplementaryQuestion1Description($link); ?></i></strong></td>
			<td colspan="2"><textarea rows="5" class="compulsory" cols="70" id="supplementary_question_1_answer" name="supplementary_question_1_answer"></textarea></td>
		</tr>
		<tr>
			<td valign="top"><strong><i><?php echo $candidate_vacancy->getSupplementaryQuestion2Description($link); ?></i></strong></td>
			<td colspan="2"><textarea class="compulsory" rows="5" cols="70" id="supplementary_question_2_answer" name="supplementary_question_2_answer"></textarea></td>
		</tr>
	</table>
<?php
	$killer_questions = DAO::getResultset($link, "SELECT * FROM rec_vacancy_questions INNER JOIN rec_questions ON rec_vacancy_questions.question_id = rec_questions.id WHERE rec_questions.type = '2' AND vacancy_id = '{$candidate_vacancy->id}'", DAO::FETCH_ASSOC);
	if((isset($killer_questions) && count($killer_questions) > 0))
	{
		echo '<table width="100%">';
		foreach($killer_questions AS $kq)
		{
			echo '<tr>';
			echo '<td colspan="2"><strong><i>' . $kq['description'] . '</i></strong></td>';
			echo '<td>' . HTML::select('q_a_'.$kq['question_id'], $yes_no_options, '', true, true) . '</td>';
			echo '</tr>';
		}
		echo '</table>';
	}
	$softer_questions = DAO::getResultset($link, "SELECT * FROM rec_vacancy_questions INNER JOIN rec_questions ON rec_vacancy_questions.question_id = rec_questions.id WHERE rec_questions.type IN ('3', '0', '1') AND vacancy_id = '{$candidate_vacancy->id}'", DAO::FETCH_ASSOC);
	if((isset($softer_questions) && count($softer_questions) > 0))
	{
		echo '<table width="100%">';
		foreach($softer_questions AS $sq)
		{
			$class = 'optional';
			if($sq['type'] == '3')
				$class = 'compulsory';
			echo '<tr>';
			echo '<td valign="top"><strong><i>' . $sq['description'] . '</i></strong></td>';
			echo '<td valign="top"><textarea class="'.$class.'" rows="5" cols="70" id="q_a_'.$sq['question_id'].'" name="q_a_'.$sq['question_id'].'"></textarea></td>';
			echo '</tr>';
		}
		echo '</table>';
	}
?>
	<table style="text-align: center" width="100%">
		<tr bgcolor="#FF69B4"><td colspan="8" align="center"><span class="trSectionHeading">Availability to work</span></td></tr>
		<tr><th></th><th>Monday</th><th>Tuesday</th><th>Wednesday</th><th>Thursday</th><th>Friday</th><th>Saturday</th><th>Sunday</th></tr>
		<tr>
			<th>Start Time</th>
			<td><input class="timebox" type="text" id="mon_start_time" name="mon_start_time" size="5" /></td>
			<td><input class="timebox" type="text" id="tue_start_time" name="tue_start_time" size="5" /></td>
			<td><input class="timebox" type="text" id="wed_start_time" name="wed_start_time" size="5" /></td>
			<td><input class="timebox" type="text" id="thu_start_time" name="thu_start_time" size="5" /></td>
			<td><input class="timebox" type="text" id="fri_start_time" name="fri_start_time" size="5" /></td>
			<td><input class="timebox" type="text" id="sat_start_time" name="sat_start_time" size="5" /></td>
			<td><input class="timebox" type="text" id="sun_start_time" name="sun_start_time" size="5" /></td>
		</tr>
		<tr>
			<th>End Time</th>
			<td><input class="timebox" type="text" id="mon_end_time" name="mon_end_time" size="5" /></td>
			<td><input class="timebox" type="text" id="tue_end_time" name="tue_end_time" size="5" /></td>
			<td><input class="timebox" type="text" id="wed_end_time" name="wed_end_time" size="5" /></td>
			<td><input class="timebox" type="text" id="thu_end_time" name="thu_end_time" size="5" /></td>
			<td><input class="timebox" type="text" id="fri_end_time" name="fri_end_time" size="5" /></td>
			<td><input class="timebox" type="text" id="sat_end_time" name="sat_end_time" size="5" /></td>
			<td><input class="timebox" type="text" id="sun_end_time" name="sun_end_time" size="5" /></td>
		</tr>
	</table>
</step>
<?php } // if vacancy has been selected for applying ?>

<?php } ?>
<h3>Confirmation</h3>
<step class="fieldsetWithWhiteBorder">
	<p>
		In order for us to use your information, please read the policy below, and click on 'register' if you are happy to send us your details.
	</p>
	<table>
		<tr>
			<td>
				<?php include_once('templates/tpl_tac.php'); ?>
			</td>
		</tr>
	</table>
	<label for="acceptTerms-2">I agree with the Terms and Conditions.</label><input id="acceptTerms-2" name="acceptTerms" type="checkbox">
	<!--<div class="g-recaptcha" data-sitekey="6Lf1CA8UAAAAALNLvtqY2VUp-tyfhaYDzW13fsT8"></div>-->
</step>
</form>
</div>
<!--wizard-->
<?php
}
elseif( isset($_REQUEST['msg']) )
{
	if ( $_REQUEST['msg'] == 1 )
	{
	?>
	<div id="messageBox" class="ui-widget ui-widget-content ui-corner-all">
		<div class="ui-widget-header ui-corner-all">
			<span id="ui-dialog-title-dialog" class="ui-dialog-title"><p align="center">Your registration has been successful</p></span>
		</div>
		<div style="height: auto; min-height: 109px; width: auto;" class="ui-widget-content">
			<p align="center">Your CV has been sent to the Apprenticeship Recruitment Team. We will be in touch with you shortly.</p>

			<p align="center">
				If you would like further information please contact us:<br/>
				Apprenticeship Recruitment Team<br/>
				Email: <a href="mailto:<?php echo SystemConfig::getEntityValue($link, 'rec_v2_email'); ?>"><?php echo SystemConfig::getEntityValue($link, 'rec_v2_email'); ?></a>
			</p>
		</div>
	</div>
		<?php
	}
	elseif( $_REQUEST['msg'] == 2 )
	{
		?>
	<div id="messageBox" class="ui-widget ui-widget-content ui-corner-all">
		<div class="ui-widget-header ui-corner-all">
			<span id="ui-dialog-title-dialog" class="ui-dialog-title"><p align="center">We already have your details</p></span>
		</div>
		<div style="height: auto; min-height: 109px; width: auto;" class="ui-widget-content">
			<p align="center">
				If you would like to speak to anyone regarding this, please use the details below.<br/>
				Apprenticeship Recruitment Team<br/>
				Email: <a href="mailto:<?php echo SystemConfig::getEntityValue($link, 'rec_v2_email'); ?>"><?php echo SystemConfig::getEntityValue($link, 'rec_v2_email'); ?></a>
			<p align="center">Alternatively you can begin the <a href="<?php echo $_SERVER['PHP_SELF'].'?_action=search_vacancies'; ?>">registration process </a> again.</p>
			</p>
		</div>
	</div>
		<?php
	}
	elseif( $_REQUEST['msg'] == 3 )
	{
		?>
	<div id="messageBox" class="ui-widget ui-widget-content ui-corner-all">
		<div class="ui-widget-header ui-corner-all">
			<span id="ui-dialog-title-dialog" class="ui-dialog-title"><p align="center">We are sorry, we have been unable to save your details at this time!</p></span>
		</div>
		<div style="height: auto; min-height: 109px; width: auto;" class="ui-widget-content">
			<p align="center">
				If you would like to speak to anyone regarding this, please use the details below.<br/>
				Apprenticeship Recruitment Team<br/>
				Email: <a href="mailto:<?php echo SystemConfig::getEntityValue($link, 'rec_v2_email'); ?>"><?php echo SystemConfig::getEntityValue($link, 'rec_v2_email'); ?></a>
			</p>
		</div>
	</div>
		<?php
	}
	elseif( $_REQUEST['msg'] == 4 )
	{
		?>
	<div id="messageBox" class="ui-widget ui-widget-content ui-corner-all">
		<div class="ui-widget-header ui-corner-all">
			<span id="ui-dialog-title-dialog" class="ui-dialog-title"><p align="center">You have already applied for this vacancy.</p></span>
		</div>
		<div style="height: auto; min-height: 109px; width: auto;" class="ui-widget-content">
			<p align="center">
				If you would like to speak to anyone regarding this, please use the details below.<br/>
				Apprenticeship Recruitment Team<br/>
				Email: <a href="mailto:<?php echo SystemConfig::getEntityValue($link, 'rec_v2_email'); ?>"><?php echo SystemConfig::getEntityValue($link, 'rec_v2_email'); ?></a>
			</p>
		</div>
	</div>
		<?php
	}
	elseif( $_REQUEST['msg'] == 5 )
	{
		?>
	<div id="messageBox" class="ui-widget ui-widget-content ui-corner-all">
		<div class="ui-widget-header ui-corner-all">
			<span id="ui-dialog-title-dialog" class="ui-dialog-title"><p align="center">Thank you for your application for an apprenticeship with Superdrug</p></span>
		</div>
		<div style="height: auto; min-height: 109px; width: auto;" class="ui-widget-content">
			<p align="center">
				Unfortunately you are not eligible for our apprenticeship based on the answers given so we are unable to proceed with your application.<br/>
				For other opportunities within Superdrug please follow the below link to apply.<br/>
				<a href="http://www.superdrug.jobs">www.superdrug.jobs</a><br/>
				Apprenticeship Recruitment Team<br/>
			</p>
		</div>
	</div>
		<?php
	}
	echo '<p><br>&nbsp;</p>';
	echo '<p><br>&nbsp;</p>';
	echo '<p><br>&nbsp;</p>';
	echo '<p><br>&nbsp;</p>';
	echo '<p><br>&nbsp;</p>';
	echo '<p><br>&nbsp;</p>';
	echo '<p><br>&nbsp;</p>';
	echo '<p><br>&nbsp;</p>';
}
?>
</step>
</div> <!--resultPanel-->
</div> <!--content-->
</div> <!--contentwrap-->
<div id="footerwrap">
	<div id="footer">
		<span style="float: left; text-align: left; margin-left: 10px;"><?php echo date('D, d M Y'); ?></span>
		<span style="float: right; text-align: right; margin-right: 5px;">Powered by Sunesis &nbsp;|&nbsp;&copy; <?php echo date('Y'); ?> Perspective Ltd</span>
	</div>
</div>
</div> <!--wrapper-->
<!--js-->
<!--<script src='https://www.google.com/recaptcha/api.js'></script>-->
</body>
</html>