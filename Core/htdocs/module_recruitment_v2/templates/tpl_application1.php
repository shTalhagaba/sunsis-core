<?php /* @var $view RecViewVacancies */ ?>
<?php /* @var $candidate RecCandidate */ ?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/html" xmlns="http://www.w3.org/1999/html" xmlns="http://www.w3.org/1999/html"
      xmlns="http://www.w3.org/1999/html" xmlns="http://www.w3.org/1999/html">
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<title>Superdrug Apprenticeship Recruitment | Application Form</title>
	<meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
	<link rel="stylesheet" href="/assets/adminlte/bootstrap/css/bootstrap.css">
	<link rel="stylesheet" href="/assets/adminlte/dist/css/AdminLTE.min.css">
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.5.0/css/font-awesome.min.css">
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/ionicons/2.0.1/css/ionicons.min.css">
	<link rel="stylesheet" href="/assets/adminlte/plugins/jQueryUI/jquery-ui.css">
	<link rel="stylesheet" href="/assets/adminlte/plugins/bootstrap-validator/bootstrapValidator.css">
	<link rel="stylesheet" href="/assets/adminlte/plugins/iCheck/all.css">
	<link href="https://gitcdn.github.io/bootstrap-toggle/2.2.2/css/bootstrap-toggle.min.css" rel="stylesheet">
	<link rel="stylesheet" type="text/css" href="/assets/css/jquery.timepicker.css" />
	<link rel="stylesheet" href="/module_recruitment_v2/css/superdrug.css">

	<!--<link href="css/jquery.steps.css?n=<?php /*echo time(); */?>" rel="stylesheet">-->
	<link href="module_eportfolio/assets/jquery.steps.css" rel="stylesheet">



	<style>
		body {
			padding-top: 100px;
		}
		@media (min-width: 992px) {
			body {
				padding-top: 100px;
			}
		}
		input[type="text"].compulsory, select.compulsory, textarea.compulsory
		{
			border-width: 1px;
			border-color: #648827;
			background-color: #f3fae5 !important;
			border-style: solid;
			padding: 2px;
		}
		.fieldLabel_compulsory
		{
			font-weight: bold;
			font-size: 100%;
			color: black;
		}
		.fieldLabel_optional
		{
			font-weight: normal;
			font-size: 100%;
			color: #555555;
		}
		#postcode{text-transform:uppercase}
		.ui-datepicker .ui-datepicker-title select {
			color: #000;
		}
	</style>
</head>


<body>
<?php
$logo = SystemConfig::getEntityValue($link, 'logo');
if($logo == '')
	$logo = 'SUNlogo.jpg';
?>

<nav id="mainNav" class="navbar navbar-default navbar-fixed-top navbar-custom">
	<div class="container">
		<div class="navbar-header page-scroll">
			<a class="navbar-brand" href="https://www.superdrug.com/" target="_blank">
				<img height="60px" class="headerlogo" src="images/logos/<?php echo $logo; ?>" />
			</a>
		</div>
	</div>
</nav>

<div class="container">

	<div class="row">
		<div class="col-lg-12">
			<div class="panel-gradient">
				<form class="form-horizontal" id="frmSearchVacancies" name="frmSearchVacancies" action="/do.php?_action=search_vacancies" method="post" autocomplete="off">
					<div class="form-group">
						<label for="sector" class="col-sm-4 control-label fieldLabel_compulsory">Sector:</label>
						<div class="col-sm-8">
							<?php echo HTML::selectChosen('sector', $type_ddl, $sector, true, false, true, 1, ' style="min-width:270px;" '); ?>
						</div>
					</div>
					<div class="form-group">
						<label for="region" class="col-sm-4 control-label fieldLabel_compulsory">Region:</label>
						<div class="col-sm-8">
							<?php echo HTML::selectChosen('region', $region_ddl, $sector, true, false, true, 1, ' style="min-width:270px;" '); ?>
						</div>
					</div>
					<div class="form-group">
						<label for="keywords" class="col-sm-4 control-label fieldLabel_compulsory">Keywords:</label>
						<div class="col-sm-8">
							<input type="text" class="form-control compulsory" name="keywords" id="keywords" value="<?php echo $keywords; ?>" maxlength="100" placeholder="e.g. Manchester, Retail, etc." />
						</div>
					</div>
					<div class="form-group">
						<div class="col-sm-12">
							<button class="pull-right btn btn-md btn-info" onclick="searchVacancies();"><i class="fa fa-search"></i> Search</button>
						</div>
					</div>
				</form>
			</div>
		</div>
	</div>

	<?php if ( isset($_REQUEST['vacancy_id']) ) { $candidate_vacancy = RecVacancy::loadFromDatabase($link, $_REQUEST['vacancy_id']); ?>
	<div class="row">
		<div class="col-sm-12">
			<div class="callout callout-info" style="margin-top: 2px;">
				<?php echo $returning_candidate_message != '' ? $returning_candidate_message . '<p><br></p>' : ''; ?>
				You are applying for: <a href="do.php?_action=vacancy_detail&vacancy_id=<?php echo $candidate_vacancy->id; ?>"><?php echo $candidate_vacancy->vacancy_title; ?> (Reference: <?php echo $candidate_vacancy->vacancy_reference; ?>)</a>
			</div>
		</div>
	</div>
	<?php } ?>

	<?php if ( !isset($_REQUEST['msg']) ) { ?>
	<form class="form-horizontal" id="recruitmentForm" action="/do.php?_action=save_application" method="post"  autocomplete="off">

		<input type="hidden" name="candidate_id" id="candidate_id" value="<?php echo $candidate->id; ?>" />
		<input type="hidden" name="hascomefrom" value="<?php echo md5('hascomefromsunesiserec'); ?>" id="hascomefrom" />
		<input type="hidden" name="vacancy_id" value="<?php echo isset($candidate_vacancy->id)?$candidate_vacancy->id:''; ?>" />

		<?php if($candidate->id != '' && !is_null($candidate->id) && isset($candidate_vacancy)) {?>
		<h4>Application Questions</h4>
		<fieldset>
			<legend>Application Questions</legend>
			<div class="form-group">
				<label for="supplementary_question_1_answer" class="col-sm-6 control-label fieldLabel_compulsory"><i><?php echo $candidate_vacancy->getSupplementaryQuestion1Description($link); ?></i></label>
				<div class="col-sm-6"><textarea class="form-control compulsory required" name="supplementary_question_1_answer" id="supplementary_question_1_answer"></textarea></div>
			</div>
			<hr>
			<div class="form-group">
				<label for="supplementary_question_2_answer" class="col-sm-6 control-label fieldLabel_compulsory"><i><?php echo $candidate_vacancy->getSupplementaryQuestion2Description($link); ?></i></label>
				<div class="col-sm-6"><textarea class="form-control compulsory required" name="supplementary_question_2_answer" id="supplementary_question_2_answer"></textarea></div>
			</div>
			<hr>
			<?php
			$killer_questions = DAO::getResultset($link, "SELECT * FROM rec_vacancy_questions INNER JOIN rec_questions ON rec_vacancy_questions.question_id = rec_questions.id WHERE rec_questions.type = '2' AND vacancy_id = '{$candidate_vacancy->id}'", DAO::FETCH_ASSOC);
			if((isset($killer_questions) && count($killer_questions) > 0))
			{
				foreach($killer_questions AS $kq)
				{
					echo '<div class="form-group">';
					echo '<div class="col-sm-8"><span class="fieldLabel_compulsory"><i>' . $kq['description'] . '</i></span></div>';
					//echo '<div class="col-sm-4"><input type="checkbox" name="q_a_'.$kq['question_id'].'" value="q_a_'.$kq['question_id'].'" data-toggle="toggle" data-on="Yes" data-off="No" data-onstyle="success" data-offstyle="danger" /></div> ';
					echo '<div class="col-sm-4">' . HTML::select('q_a_'.$kq['question_id'], $yes_no_options, '', true, true) . '</div>';
					echo '</div> ';
					echo '<hr>';
				}
			}
			?>
			<?php
			$softer_questions = DAO::getResultset($link, "SELECT * FROM rec_vacancy_questions INNER JOIN rec_questions ON rec_vacancy_questions.question_id = rec_questions.id WHERE rec_questions.type IN ('3', '0', '1') AND vacancy_id = '{$candidate_vacancy->id}'", DAO::FETCH_ASSOC);
			if((isset($softer_questions) && count($softer_questions) > 0))
			{
				foreach($softer_questions AS $sq)
				{
					$class = 'optional';
					if($sq['type'] == '3')
						$class = 'compulsory';
					echo '<div class="form-group">';
					echo '<label class="col-sm-6 control-label fieldLabel_'.$class.'"><i>' . $sq['description'] . '</i></label>';
					echo '<div class="col-sm-6"><textarea class="form-control '.$class.' required" id="q_a_'.$sq['question_id'].'" name="q_a_'.$sq['question_id'].'"></textarea></div>';
					echo '</div> ';
					echo '<hr>';
				}
			}
			?>
			<div class="table-responsive">
				<table class="table">
					<caption><h5 class="text-bold">Availability to work</h5></caption>
					<?php
					$days = array('mon' => 'Monday', 'tue' => 'Tuesday', 'wed' => 'Wednesday', 'thu' => 'Thursday', 'fri' => 'Friday', 'sat' => 'Saturday', 'sun' => 'Sunday');
					foreach($days AS $key => $value)
					{
						echo '<tr>';
						echo '<td><label class="pull-right">' . $value . '</label></td>';
						echo '<td><input type="text" class="timebox" name="'.$key.'_start_time" size="5" placeholder="start" /> &nbsp; <input type="text" class="timebox" name="'.$key.'_end_time" size="5" placeholder="finish" /></td>';
						echo '</tr>';
					}
					?>
				</table>
			</div>
		</fieldset>
		<?php } else {?>

		<h4>Personal Information</h4>
		<fieldset>
			<legend>Personal Information</legend>
			<div class="form-group">
				<label for="firstnames" class="col-sm-4 control-label fieldLabel_compulsory">Firstnames:</label>
				<div class="col-sm-8"><input type="text" class="form-control compulsory required" name="firstnames" id="firstnames" value="<?php echo !is_null($candidate->firstnames)?$candidate->firstnames:$firstnames; ?>" maxlength="50" /></div>
			</div>
			<div class="form-group">
				<label for="surname" class="col-sm-4 control-label fieldLabel_compulsory">Surname:</label>
				<div class="col-sm-8"><input type="text" class="form-control compulsory required" name="surname" id="surname" value="<?php echo !is_null($candidate->surname)?$candidate->surname:$surname; ?>" maxlength="50" /></div>
			</div>
			<div class="form-group">
				<label for="gender" class="col-sm-4 control-label fieldLabel_compulsory">Gender:</label>
				<div class="col-sm-8"><?php echo HTML::selectChosen('gender', $genderDDL, $candidate->gender, true, true, true); ?></div>
			</div>
			<div class="form-group">
				<label for="ethnicity" class="col-sm-4 control-label fieldLabel_compulsory">Ethnicity:</label>
				<div class="col-sm-8"><?php echo HTML::selectChosen('ethnicity', $ethnicityDDL, $candidate->ethnicity, true, true, true); ?></div>
			</div>
			<div class="form-group">
				<label for="input_dob" class="col-sm-4 control-label fieldLabel_compulsory">Date of Birth:</label>
				<div class="col-sm-8"><?php echo HTML::datebox('dob', !is_null($candidate->dob)?$candidate->dob:$dob, true, true); ?></div>
			</div>
			<div class="form-group">
				<label for="national_insurance" class="col-sm-4 control-label fieldLabel_compulsory">National Insurance:</label>
				<div class="col-sm-8"><input type="text" class="form-control compulsory required" name="national_insurance" id="national_insurance" value="<?php echo !is_null($candidate->national_insurance)?$candidate->national_insurance:''; ?>" maxlength="50" /></div>
			</div>
			<div class="form-group">
				<label for="uploadedfile" class="col-sm-4 control-label fieldLabel_optional">Upload CV:</label>
				<div class="col-sm-8">
					<input class="optional" type="file" name="uploadedfile" id="uploadedfile" accept="doc,pdf,docx" />
					<span class="text-muted" style="font-size:smaller; color:gray;font-style:italic">Allowed files types: .pdf, .doc, .docx, .txt, .zip</span>
				</div>
			</div>
			<hr>
			<div class="form-group">
				<label for="address1" class="col-sm-4 control-label fieldLabel_compulsory">Address Line 1:</label>
				<div class="col-sm-8"><input type="text" class="form-control compulsory required" name="address1" id="address1" value="<?php echo $candidate->address1; ?>" maxlength="100" /></div>
			</div>
			<div class="form-group">
				<label for="address2" class="col-sm-4 control-label fieldLabel_optional">Address Line 2:</label>
				<div class="col-sm-8"><input type="text" class="form-control optional" name="address2" id="address2" value="<?php echo $candidate->address2; ?>" maxlength="100" /></div>
			</div>
			<div class="form-group">
				<label for="borough" class="col-sm-4 control-label fieldLabel_optional">Address Line 3:</label>
				<div class="col-sm-8"><input type="text" class="form-control optional" name="borough" id="borough" value="<?php echo $candidate->borough; ?>" maxlength="100" /></div>
			</div>
			<div class="form-group">
				<label for="county" class="col-sm-4 control-label fieldLabel_compulsory">County:</label>
				<div class="col-sm-8"><?php echo HTML::selectChosen('county', $countiesDDL, $candidate->county, true, true, true); ?></div>
			</div>
			<div class="form-group">
				<label for="postcode" class="col-sm-4 control-label fieldLabel_compulsory">Postcode:</label>
				<div class="col-sm-8"><input type="text" class="form-control compulsory required" name="postcode" id="postcode" value="<?php echo !is_null($candidate->postcode)?$candidate->postcode:$postcode; ?>" maxlength="8" /></div>
			</div>
			<div class="form-group">
				<label for="telephone" class="col-sm-4 control-label fieldLabel_compulsory">Telephone:</label>
				<div class="col-sm-8"><input type="text" class="form-control compulsory required" name="telephone" id="telephone" value="<?php echo $candidate->telephone; ?>" maxlength="20" /></div>
			</div>
			<div class="form-group">
				<label for="mobile" class="col-sm-4 control-label fieldLabel_compulsory">Mobile:</label>
				<div class="col-sm-8"><input type="text" class="form-control compulsory required" name="mobile" id="mobile" value="<?php echo $candidate->mobile; ?>" maxlength="20" /></div>
			</div>
			<div class="form-group">
				<label for="email" class="col-sm-4 control-label fieldLabel_compulsory">Email:</label>
				<div class="col-sm-8"><input type="text" class="form-control compulsory required" name="email" id="email" value="<?php echo $candidate->email; ?>" maxlength="180" /></div>
			</div>
			<div class="form-group">
				<label for="guardian_email" class="col-sm-4 control-label fieldLabel_compulsory">Parent / Guardian Email:</label>
				<div class="col-sm-8"><input type="text" class="form-control compulsory required" name="guardian_email" id="guardian_email" value="<?php echo $candidate->guardian_email; ?>" maxlength="180" /></div>
			</div>
			<div class="form-group">
				<label for="guardian_contact" class="col-sm-4 control-label fieldLabel_compulsory">Parent / Guardian Contact:</label>
				<div class="col-sm-8"><input type="text" class="form-control compulsory required" name="guardian_contact" id="guardian_contact" value="<?php echo $candidate->guardian_contact; ?>" maxlength="20" /></div>
			</div>
		</fieldset>

		<h4>Study History</h4>
		<fieldset>
			<legend>Study History</legend>
			<?php $qual_grades = DAO::getResultset($link,"SELECT id, description, NULL FROM lookup_gcse_grades WHERE description NOT IN ('Credit', 'Distinction', 'Distinction*', 'Merit') ORDER BY id;", DAO::FETCH_NUM); ?>
			<div class="row">
				<div class="col-sm-6">
					<div class="well">
						<h5 class="text-bold">GCSE English Language</h5>
						<div class="form-group">
							<label for="gcse_english_grade" class="col-sm-4 control-label fieldLabel_compulsory">Grade:</label>
							<div class="col-sm-8"><?php echo HTML::selectChosen('gcse_english_grade', $qual_grades, isset($candidate->getGCSEEnglishDetails($link)->qualification_grade)?$candidate->getGCSEEnglishDetails($link)->qualification_grade:'', true, true, true); ?></div>
						</div>
						<div class="form-group">
							<label for="input_gcse_english_date_completed" class="col-sm-4 control-label fieldLabel_optional">Date Completed:</label>
							<div class="col-sm-8"><?php echo HTML::datebox('gcse_english_date_completed', isset($candidate->getGCSEEnglishDetails($link)->qualification_date)?$candidate->getGCSEEnglishDetails($link)->qualification_date:''); ?></div>
						</div>
						<div class="form-group">
							<label for="gcse_english_school" class="col-sm-4 control-label fieldLabel_optional">School / Institution:</label>
							<div class="col-sm-8"><input type="text" class="form-control" name="gcse_english_school" id="gcse_english_school" value="<?php echo isset($candidate->getGCSEEnglishDetails($link)->institution)?$candidate->getGCSEEnglishDetails($link)->institution:''; ?>" maxlength="200" /></div>
						</div>
					</div>
				</div>
				<div class="col-sm-6">
					<div class="well">
						<h5 class="text-bold">GCSE Maths</h5>
						<div class="form-group">
							<label for="gcse_maths_grade" class="col-sm-4 control-label fieldLabel_compulsory">Grade:</label>
							<div class="col-sm-8"><?php echo HTML::selectChosen('gcse_maths_grade', $qual_grades, isset($candidate->getGCSEMathsDetails($link)->qualification_grade)?$candidate->getGCSEMathsDetails($link)->qualification_grade:'', true, true, true); ?></div>
						</div>
						<div class="form-group">
							<label for="input_gcse_maths_date_completed" class="col-sm-4 control-label fieldLabel_optional">Date Completed:</label>
							<div class="col-sm-8"><?php echo HTML::datebox('gcse_maths_date_completed', isset($candidate->getGCSEMathsDetails($link)->qualification_date)?$candidate->getGCSEMathsDetails($link)->qualification_date:''); ?></div>
						</div>
						<div class="form-group">
							<label for="gcse_maths_school" class="col-sm-4 control-label fieldLabel_optional">School / Institution:</label>
							<div class="col-sm-8"><input type="text" class="form-control" name="gcse_maths_school" id="gcse_maths_school" value="<?php echo isset($candidate->getGCSEMathsDetails($link)->institution)?$candidate->getGCSEMathsDetails($link)->institution:''; ?>" maxlength="200" /></div>
						</div>
					</div>
				</div>
			</div>
			<div class="row">
				<div class="col-sm-12"><p class="text-bold text-center" style="margin-bottom: 10px;">Other Qualifications</p> </div>
				<?php
				$qual_grades = DAO::getResultset($link,"SELECT id, description, NULL FROM lookup_gcse_grades ORDER BY id;", DAO::FETCH_NUM);
				for($i = 1; $i <= 4; $i++)
				{
					echo '<div class="col-sm-6 callout">';
					echo '<div class="form-group">';
					echo '<label for="level'.$i.'" class="col-sm-4 control-label fieldLabel_optional">Level:</label>';
					if(isset($qualifications['level'.$i]))
						echo '<div class="col-sm-8">'.HTML::selectChosen('level'.$i, $PriorAttain_dropdown, $qualifications['level'.$i], true, false).'</div>';
					else
						echo '<div class="col-sm-8">'.HTML::selectChosen('level'.$i, $PriorAttain_dropdown, '', true, false).'</div>';
					echo '</div>';
					echo '<div class="form-group">';
					echo '<label for="subject'.$i.'" class="col-sm-4 control-label fieldLabel_optional">Subject:</label>';
					if(isset($qualifications['subject'.$i]))
						echo '<div class="col-sm-8"><input type="text" class="form-control" name="subject'.$i.'" id="subject'.$i.'" value="'.$qualifications['subject'.$i].'" /></div>';
					else
						echo '<div class="col-sm-8"><input type="text" class="form-control" name="subject'.$i.'" id="subject'.$i.'" value="" /></div>';
					echo '</div>';
					echo '<div class="form-group">';
					echo '<label for="grade'.$i.'" class="col-sm-4 control-label fieldLabel_optional">Grade:</label>';
					if(isset($qualifications['grade'.$i]))
						echo '<div class="col-sm-8">'.HTML::selectChosen('grade'.$i, $qual_grades, $qualifications['grade'.$i], true, false).'</div>';
					else
						echo '<div class="col-sm-8">'.HTML::selectChosen('grade'.$i, $qual_grades, '', true, false).'</div>';
					echo '</div>';
					echo '<div class="form-group">';
					echo '<label for="date_completed'.$i.'" class="col-sm-4 control-label fieldLabel_optional"><span class="small">Date Completed:</span> </label>';
					if(isset($qualifications['date'.$i]))
						echo '<div class="col-sm-8">'.HTML::datebox('date_completed'.$i, $qualifications['date'.$i]).'</div>';
					else
						echo '<div class="col-sm-8">'.HTML::datebox('date_completed'.$i, '').'</div>';
					echo '</div>';
					echo '<div class="form-group">';
					echo '<label for="date_school'.$i.'" class="col-sm-4 control-label fieldLabel_optional"><span class="small">School / Institution:</span> </label>';
					if(isset($qualifications['institution'.$i]))
						echo '<div class="col-sm-8"><input type="text" class="form-control" name="date_school'.$i.'" id="date_school'.$i.'" value="'.$qualifications['institution'.$i].'" /></div>';
					else
						echo '<div class="col-sm-8"><input type="text" class="form-control" name="date_school'.$i.'" id="date_school'.$i.'" value="" /></div>';
					echo '</div>';
					echo '</div>';
				}
				?>
			</div>
		</fieldset>
		<h4>Employment History</h4>
		<fieldset>
			<legend>Employment History</legend>
			<div class="form-group">
				<label for="employment_status" class="col-sm-4 control-label fieldLabel_optional">What is your current employment status?</label>
				<div class="col-sm-8">
					<?php
					$op1 = ''; $op2 = ''; $op3 = ''; $op4 = ''; $op5 = '';
					if($candidate->employment_status == '1') $op1 = 'checked = "checked"';
					if($candidate->employment_status == '2') $op2 = 'checked = "checked"';
					if($candidate->employment_status == '3') $op3 = 'checked = "checked"';
					if($candidate->employment_status == '4') $op4 = 'checked = "checked"';
					if($candidate->employment_status == '5') $op5 = 'checked = "checked"';
					?>
					<p><input type="radio" name="employment_status" <?php echo $op1; ?>value="1">  &nbsp;Employed</p>
					<p><input type="radio" name="employment_status" <?php echo $op2; ?> value="2"> &nbsp;Self Employed</p>
					<p><input type="radio" name="employment_status" <?php echo $op3; ?> value="3"> &nbsp;Full Time Education or Training</p>
					<p><input type="radio" name="employment_status" <?php echo $op4; ?> value="4"> &nbsp;Unemployed</p>
					<p><input type="radio" name="employment_status" <?php echo $op5; ?> value="5"> &nbsp;Economically Inactive</p>
				</div>
			</div>
			<div id="div_hours_per_week" class="form-group" style="display: none;">
				<label for="hours_per_week" class="col-sm-4 control-label fieldLabel_optional">If employed, how many hours per week?</label>
				<div class="col-sm-8"><input type="text" class="form-control" name="hours_per_week" id="hours_per_week" value="<?php echo $candidate->hours_per_week; ?>" maxlength="5" onKeyPress="return numbersonly(this, event);" /></div>
			</div>
			<div id="div_time_last_worked" class="form-group" style="display: none;">
				<label for="time_last_worked" class="col-sm-4 control-label fieldLabel_optional">If not employed, when was the last time that you worked?</label>
				<div class="col-sm-8">
					<?php
					$op1 = ''; $op2 = ''; $op3 = ''; $op4 = ''; $op5 = '';
					if($candidate->employment_status == '1') $op1 = 'checked = "checked"';
					if($candidate->employment_status == '2') $op2 = 'checked = "checked"';
					if($candidate->employment_status == '3') $op3 = 'checked = "checked"';
					if($candidate->employment_status == '4') $op4 = 'checked = "checked"';
					if($candidate->employment_status == '5') $op5 = 'checked = "checked"';
					?>
					<p><input type="radio" name="time_last_worked" <?php echo $op1; ?>value="1">  &nbsp;Not yet been employed</p>
					<p><input type="radio" name="time_last_worked" <?php echo $op2; ?> value="6"> &nbsp;Less than 6 months</p>
					<p><input type="radio" name="time_last_worked" <?php echo $op3; ?> value="11"> &nbsp;6-11 months</p>
					<p><input type="radio" name="time_last_worked" <?php echo $op4; ?> value="23"> &nbsp;12-23 months</p>
					<p><input type="radio" name="time_last_worked" <?php echo $op5; ?> value="35"> &nbsp;24-35 months</p>
					<p><input type="radio" name="time_last_worked" <?php echo $op5; ?> value="36"> &nbsp;Over 36 months</p>
				</div>
			</div>
			<div class="row">
				<?php
				$qual_grades = DAO::getResultset($link,"SELECT id, description, NULL FROM lookup_gcse_grades ORDER BY id;", DAO::FETCH_NUM);
				for($i = 1; $i <= 4; $i++)
				{
					$company_name = isset($employments['company_name'.$i])?$employments['company_name'.$i]:'';
					$job_title = isset($employments['job_title'.$i])?$employments['job_title'.$i]:'';
					$skills = isset($employments['skills'.$i])?$employments['skills'.$i]:'';

					echo '<div class="col-sm-6 callout">';
					echo '<div class="form-group">';
					echo '<label for="company_name'.$i.'" class="col-sm-4 control-label fieldLabel_optional">Company Name:</label>';
					echo '<div class="col-sm-8"><input type="text" class="form-control" name="company_name'.$i.'" id="company_name'.$i.'" value="'.$company_name.'" /></div>';
					echo '</div>';
					echo '<div class="form-group">';
					echo '<label for="job_title'.$i.'" class="col-sm-4 control-label fieldLabel_optional">Job Title:</label>';
					echo '<div class="col-sm-8"><input type="text" class="form-control" name="job_title'.$i.'" id="job_title'.$i.'" value="'.$job_title.'" /></div>';
					echo '</div>';
					echo '<div class="form-group">';
					echo '<label for="start_date'.$i.'" class="col-sm-4 control-label fieldLabel_optional">Start Date:</label>';
					echo '<div class="col-sm-8">' . HTML::datebox('start_date'.$i, isset($employments['start_date'.$i])?$employments['start_date'.$i]:'', false) . '</div>';
					echo '</div>';
					echo '<div class="form-group">';
					echo '<label for="end_date'.$i.'" class="col-sm-4 control-label fieldLabel_optional">End Date:</label>';
					echo '<div class="col-sm-8">' . HTML::datebox('end_date'.$i, isset($employments['end_date'.$i])?$employments['end_date'.$i]:'', false) . '</div>';
					echo '</div>';
					echo '<div class="form-group">';
					echo '<label for="skills'.$i.'" class="col-sm-4 control-label fieldLabel_optional">Skills:</label>';
					echo '<div class="col-sm-8"><textarea class="form-control" name="skills'.$i.'" id="skills'.$i.'">' . $skills . '</textarea></div>';
					echo '</div>';
					echo '</div>';
				}
				?>
			</div>
		</fieldset>
		<?php if(isset($candidate_vacancy)){// if vacancy has been selected for applying?>
		<h4>Application Questions</h4>
		<fieldset>
			<legend>Application Questions</legend>
			<div class="form-group">
				<label for="supplementary_question_1_answer" class="col-sm-6 control-label fieldLabel_compulsory"><i><?php echo $candidate_vacancy->getSupplementaryQuestion1Description($link); ?></i></label>
				<div class="col-sm-6"><textarea class="form-control compulsory required" name="supplementary_question_1_answer" id="supplementary_question_1_answer"></textarea></div>
			</div>
			<hr>
			<div class="form-group">
				<label for="supplementary_question_2_answer" class="col-sm-6 control-label fieldLabel_compulsory"><i><?php echo $candidate_vacancy->getSupplementaryQuestion2Description($link); ?></i></label>
				<div class="col-sm-6"><textarea class="form-control compulsory required" name="supplementary_question_2_answer" id="supplementary_question_2_answer"></textarea></div>
			</div>
			<hr>
			<?php
			$killer_questions = DAO::getResultset($link, "SELECT * FROM rec_vacancy_questions INNER JOIN rec_questions ON rec_vacancy_questions.question_id = rec_questions.id WHERE rec_questions.type = '2' AND vacancy_id = '{$candidate_vacancy->id}'", DAO::FETCH_ASSOC);
			if((isset($killer_questions) && count($killer_questions) > 0))
			{
				foreach($killer_questions AS $kq)
				{
					echo '<div class="form-group">';
					echo '<div class="col-sm-8"><span class="fieldLabel_compulsory"><i>' . $kq['description'] . '</i></span></div>';
					//echo '<div class="col-sm-4"><input type="checkbox" name="q_a_'.$kq['question_id'].'" value="q_a_'.$kq['question_id'].'" data-toggle="toggle" data-on="Yes" data-off="No" data-onstyle="success" data-offstyle="danger" /></div> ';
					echo '<div class="col-sm-4">' . HTML::select('q_a_'.$kq['question_id'], $yes_no_options, '', true, true) . '</div>';
					echo '</div> ';
					echo '<hr>';
				}
			}
			?>
			<?php
			$softer_questions = DAO::getResultset($link, "SELECT * FROM rec_vacancy_questions INNER JOIN rec_questions ON rec_vacancy_questions.question_id = rec_questions.id WHERE rec_questions.type IN ('3', '0', '1') AND vacancy_id = '{$candidate_vacancy->id}'", DAO::FETCH_ASSOC);
			if((isset($softer_questions) && count($softer_questions) > 0))
			{
				foreach($softer_questions AS $sq)
				{
					$class = 'optional';
					if($sq['type'] == '3')
						$class = 'compulsory';
					echo '<div class="form-group">';
					echo '<label class="col-sm-6 control-label fieldLabel_'.$class.'"><i>' . $sq['description'] . '</i></label>';
					echo '<div class="col-sm-6"><textarea class="form-control '.$class.' required" id="q_a_'.$sq['question_id'].'" name="q_a_'.$sq['question_id'].'"></textarea></div>';
					echo '</div> ';
					echo '<hr>';
				}
			}
			?>
			<div class="table-responsive">
				<table class="table">
					<caption><h5 class="text-bold">Availability to work</h5></caption>
					<?php
					$days = array('mon' => 'Monday', 'tue' => 'Tuesday', 'wed' => 'Wednesday', 'thu' => 'Thursday', 'fri' => 'Friday', 'sat' => 'Saturday', 'sun' => 'Sunday');
					foreach($days AS $key => $value)
					{
						echo '<tr>';
						echo '<td><label class="pull-right">' . $value . '</label></td>';
						echo '<td><input type="text" class="timebox" name="'.$key.'_start_time" size="5" placeholder="start" /> &nbsp; <input type="text" class="timebox" name="'.$key.'_end_time" size="5" placeholder="finish" /></td>';
						echo '</tr>';
					}
					?>
				</table>
			</div>
		</fieldset>
		<?php } ?>

		<?php } ?>
		<h4>Confirmation</h4>
		<fieldset>
			<legend>Confirmation</legend>
			<div class="row">
				<div class="col-sm-12">
					<span class="text-bold">In order for us to use your information, please read the policy below, and click on 'register' if you are happy to send us your details.<br><br></span>
				</div>
			</div>
			<div class="row">
				<div class="col-sm-12">
					<span><i><?php include_once('templates/tpl_tac.php'); ?></i><br><br></span>
				</div>
			</div>
			<div class="row">
				<div class="col-sm-12">
					<div class="form-group">
						<input type="checkbox" name="acceptTerms-2" id="acceptTerms-2" /><label> &nbsp; I agree with the Terms and Conditions.</label>
					</div>
				</div>
			</div>
		</fieldset>
	</form>
	<?php } elseif( isset($_REQUEST['msg']) ) {
	if ( $_REQUEST['msg'] == 1 ) { ?>
	<div class="row">
		<div class="col-sm-12">
			<div class="callout callout-success text-center" style="margin: 25px;">
				<h5 class="text-bold"><i class="fa fa-check"></i> Your registration has been successful</h5>
				<hr>
				<span class="text-center">
					If you would like further information please contact us:<br/>
					Apprenticeship Recruitment Team<br/>
					Email: <a href="mailto:<?php echo SystemConfig::getEntityValue($link, 'rec_v2_email'); ?>"><?php echo SystemConfig::getEntityValue($link, 'rec_v2_email'); ?></a>
				</span>
			</div>
		</div>
	</div>
	<?php } // msg = 1
	elseif( $_REQUEST['msg'] == 2 ) { ?>
	<div class="row">
		<div class="col-sm-12">
			<div class="callout callout-info text-center" style="margin: 25px;">
				<h5 class="text-bold"><i class="fa fa-info-circle"></i> We already have your details</h5>
				<hr>
				<span class="text-center">
					If you would like to speak to anyone regarding this, please use the details below.<br/>
					Apprenticeship Recruitment Team<br/>
					Email: <a href="mailto:<?php echo SystemConfig::getEntityValue($link, 'rec_v2_email'); ?>"><?php echo SystemConfig::getEntityValue($link, 'rec_v2_email'); ?></a>
					<p align="center">Alternatively you can begin the <a href="<?php echo $_SERVER['PHP_SELF'].'?_action=search_vacancies'; ?>">registration process </a> again.</p>
				</span>
			</div>
		</div>
	</div>
	<?php } // msg = 2
	elseif( $_REQUEST['msg'] == 3 ) { ?>
	<div class="row">
		<div class="col-sm-12">
			<div class="callout callout-danger text-center" style="margin: 25px;">
				<h5 class="text-bold"><i class="fa fa-warning"></i> We are sorry, we have been unable to save your details at this time!</h5>
				<hr>
				<span class="text-center">
					If you would like to speak to anyone regarding this, please use the details below.<br/>
					Apprenticeship Recruitment Team<br/>
					Email: <a href="mailto:<?php echo SystemConfig::getEntityValue($link, 'rec_v2_email'); ?>"><?php echo SystemConfig::getEntityValue($link, 'rec_v2_email'); ?></a>
				</span>
			</div>
		</div>
	</div>
	<?php } // msg = 3
	elseif( $_REQUEST['msg'] == 4 ) { ?>
	<div class="row">
		<div class="col-sm-12">
			<div class="callout callout-info text-center" style="margin: 25px;">
				<h5 class="text-bold"><i class="fa fa-info-circle"></i> You have already applied for this vacancy.</h5>
				<hr>
				<span class="text-center">
					If you would like to speak to anyone regarding this, please use the details below.<br/>
					Apprenticeship Recruitment Team<br/>
					Email: <a href="mailto:<?php echo SystemConfig::getEntityValue($link, 'rec_v2_email'); ?>"><?php echo SystemConfig::getEntityValue($link, 'rec_v2_email'); ?></a>>
				</span>
			</div>
		</div>
	</div>
	<?php } // msg = 4
	elseif( $_REQUEST['msg'] == 5 ) { ?>
	<div class="row">
		<div class="col-sm-12">
			<div class="callout callout-info text-center" style="margin: 25px;">
				<h5 class="text-bold"><i class="fa fa-info-circle"></i> Thank you for your application for an apprenticeship with Superdrug</h5>
				<hr>
				<span class="text-center">
					Unfortunately you are not eligible for our apprenticeship based on the answers given so we are unable to proceed with your application.<br/>
					For other opportunities within Superdrug please follow the below link to apply.<br/>
					<a href="http://www.superdrug.jobs">www.superdrug.jobs</a><br/>
					Apprenticeship Recruitment Team<br/>
				</span>
			</div>
		</div>
	</div>
	<?php } //msg = 5 ?>
	<?php } // elseif ?>
</div> <!-- container ends here -->

<div class="footer">
	<table class="table">
		<tr>
			<td><img src="images/logos/siemens/top70.png" class="img-responsive" /></td>
			<td><img src="images/logos/ESF_logo_rgb_28mm.png" class="img-responsive" /></td>
			<td><img src="images/logos/top_employer.png" class="img-responsive" /></td>
			<td><img src="images/logos/SUNlogo.png" class="img-responsive" /></td>
		</tr>
	</table>
</div>


<script src="/assets/adminlte/plugins/jQuery/jquery-2.2.3.min.js"></script>
<!--<script src="/assets/adminlte/plugins/JQuerySteps/jquery.steps.js"></script>-->
<script src="module_eportfolio/assets/jquery.steps.js"></script>
<script src="/assets/adminlte/plugins/jQueryUI/jquery-ui.min.js"></script>
<script src="/assets/adminlte/bootstrap/js/bootstrap.min.js"></script>
<script src="/assets/adminlte/plugins/bootstrap-validator/bootstrapValidator.js"></script>
<script src="https://gitcdn.github.io/bootstrap-toggle/2.2.2/js/bootstrap-toggle.min.js"></script>
<script src="/assets/adminlte/plugins/iCheck/icheck.min.js"></script>
<script type="text/javascript" src="/assets/js/jquery/jquery.timepicker.js"></script>
<script src="../module_recruitment_v2/js/search_vacancies.js"></script>


<script>

	$(function(){

		$('#acceptTerms-2').each(function(){
			var self = $(this),
				label = self.next(),
				label_text = label.text();
			label.remove();
			self.iCheck({
				checkboxClass: 'icheckbox_line-blue',
				insert: '<div class="icheck_line-icon"></div>' + label_text
			});
		});

		$(".timebox").timepicker({ timeFormat: 'H:i' });

		$('.timebox').bind('timeFormatError timeRangeError', function() {
			this.value = '';
			alert("Please choose a valid time");
			this.focus();
		});

		$('input[type=radio]').iCheck({
			radioClass: 'iradio_square-red'
		});

		$("input[name=employment_status]").on('ifChecked', function(event){
			if(this.value == 1 || this.value == 2 || this.value == 3)
			{
				$('#div_hours_per_week').show();
				$('#div_time_last_worked').hide();
			}
			else if(this.value == 4 || this.value == 5)
			{
				$('#div_hours_per_week').hide();
				$('#div_time_last_worked').show();
			}
			else
			{
				$('#div_hours_per_week').hide();
				$('#div_time_last_worked').hide();
			}
		});

		var form = $("#recruitmentForm").show();

		form.steps({
			headerTag: "h4",
			bodyTag: "fieldset",
			transitionEffect: "slideLeft",
			//startIndex: 4,
			onStepChanging: function (event, currentIndex, newIndex)
			{
				// Allways allow previous action even if the current form is not valid!
				if (currentIndex > newIndex) {
					return true;
				}
				// Needed in some cases if the user went back (clean up)
				if (currentIndex < newIndex) {
					// To remove error styles
					form.find(".body:eq(" + newIndex + ") label.error").remove();
					form.find(".body:eq(" + newIndex + ") .error").removeClass("error");
					var file = $('input[type=file]#uploadedfile').val();
					var exts = ['doc','docx','pdf','txt','zip'];//extensions
					//the file has any value?
					if ( file ) {
						// split file name at dot
						var get_ext = file.split('.');
						// reverse name to check extension
						get_ext = get_ext.reverse();
						// check file type is valid as given in 'exts' array
						if ( $.inArray ( get_ext[0].toLowerCase(), exts ) > -1 ){
							// OK
						} else {
							alert( 'File type not allowed, please upload CV with valid extensions.' );
						}
					}
				}
				form.validate().settings.ignore = ":disabled,:hidden";
				return form.valid();
			},
			onStepChanged: function (event, currentIndex, priorIndex)
			{
				// Used to skip the "Warning" step if the user is old enough and wants to the previous step.
				/*if (currentIndex === 2 && priorIndex === 3) {
							 form.steps("previous");
							 }*/
			},
			onFinishing: function (event, currentIndex)
			{
				form.validate().settings.ignore = ":disabled";
				return form.valid();
			},
			onFinished: function (event, currentIndex) {
				var myForm = document.forms['recruitmentForm'];
				myForm.enctype = "multipart/form-data";
				myForm.submit();
			}
		}).validate({
				errorPlacement: function errorPlacement(error, element) { element.after(error); },
				rules:{
					firstnames:{
						required:true
					},
					surname:{
						required:true
					},
					gender:{
						required:true
					},
					ethnicity:{
						required:true
					},
					dob:{
						required:true,
						dateUK:true
					},
					national_insurance:{
						required:true,
						niUK:true
					},
					address1:{
						required:true
					},
					county:{
						required:true
					},
					postcode:{
						required:true,
						postcodeUK:true
					},
					region:{
						required:true
					},
					telephone:{
						required:true
					},
					mobile:{
						required:true
					},
					email:{
						required:true,
						emailCheck:true
					},
					gcse_english_grade:{
						required:true
					},
					gcse_maths_grade:{
						required:true
					},
					guardian_email:{
						required:true,
						emailCheck:true
					},
					guardian_contact:{
						required:true
					},
					supplementary_question_1_answer:{
						maxlength: 500
					},
					supplementary_question_2_answer:{
						maxlength: 500
					},
					q_a_8:{
						maxlength: 500
					},
					q_a_9:{
						maxlength: 500
					},
					q_a_10:{
						maxlength: 500
					},
					q_a_11:{
						maxlength: 500
					},
					q_a_12:{
						maxlength: 500
					},
					q_a_13:{
						maxlength: 500
					},
					q_a_14:{
						maxlength: 500
					}
				}
			});

		jQuery.validator.addMethod("postcodeUK", function(value, element) {
			return this.optional(element) || /^[A-Z]{1,2}[0-9]{1,2} ?[0-9][A-Z]{2}$/i.test(value);
		}, "Please specify a valid Postcode");

		jQuery.validator.addMethod('phoneUK', function(phone_number, element) {
			return this.optional(element) || phone_number.length > 9 &&
				phone_number.match(/^(((\+44)? ?(\(0\))? ?)|(0))( ?[0-9]{3,4}){3}$/);
		}, 'Please specify a valid phone number');

		jQuery.validator.addMethod("niUK", function(value, element) {
			return this.optional(element) || /^\s*[a-zA-Z]{2}(?:\s*\d\s*){6}[a-zA-Z]?\s*$/i.test(value);
		}, "Please specify a valid National Insurance Number");

		jQuery.validator.addMethod("emailCheck", function(value, element) {
			return this.optional(element) || /^(([^<>()[\]\\.,;:\s@\"]+(\.[^<>()[\]\\.,;:\s@\"]+)*)|(\".+\"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/i.test(value);
		}, "Please specify a valid Email address");

		jQuery.validator.addMethod("dateUK",function(value, element) {
				return value == ''?true:value.match(/^(?=\d)(?:(?:31(?!.(?:0?[2469]|11))|(?:30|29)(?!.0?2)|29(?=.0?2.(?:(?:(?:1[6-9]|[2-9]\d)(?:0[48]|[2468][048]|[13579][26])|(?:(?:16|[2468][048]|[3579][26])00)))(?:\x20|$))|(?:2[0-8]|1\d|0?[1-9]))([-.\/])(?:1[012]|0?[1-9])\1(?:1[6-9]|[2-9]\d)\d\d(?:(?=\x20\d)\x20|$))?(((0?[1-9]|1[012])(:[0-5]\d){0,2}(\x20[AP]M))|([01]\d|2[0-3])(:[0-5]\d){1,2})?$/);
			}, "Please enter a date in the format dd/mm/yyyy."
		);

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

		$('#input_dob').attr('class', 'datepicker compulsory form-control');
		$('#input_gcse_english_date_completed').attr('class', 'datepicker compulsory form-control');
		$('#input_gcse_maths_date_completed').attr('class', 'datepicker compulsory form-control');

		jQuery.validator.addClassRules('compulsory', {
			required: true
		});
		jQuery.validator.addClassRules('datepicker', {
			dateUK:true
		});
	});


	function searchVacancies()
	{
		var myForm = document.forms["frmSearchVacancies"];
		myForm.submit();
	}

	function numbersonly(myfield, e, dec)
	{
		var key;
		var keychar;

		if (window.event)
			key = window.event.keyCode;
		else if (e)
			key = e.which;
		else
			return true;

		keychar = String.fromCharCode(key);

		// To check if it goes beyond 100
		//if(parseInt(myfield.value+keychar)<0 || parseInt(myfield.value+keychar)>100)
		//	return false;

		// control keys
		if ((key==null) || (key==0) || (key==8) ||
			(key==9) || (key==13) || (key==27) )
			return true;

		// numbers
		else if ((("0123456789").indexOf(keychar) > -1))
			return true;

		// decimal point jump
		else if (dec && (keychar == "."))
		{
			myfield.form.elements[dec].focus();
			return false;
		}
		else
			return false;

	}
</script>

<!-- Hotjar Tracking Code for http://www.superdrug.com/ -->
<script>
    (function(h,o,t,j,a,r){
        h.hj=h.hj||function(){(h.hj.q=h.hj.q||[]).push(arguments)};
        h._hjSettings={hjid:136263,hjsv:6};
        a=o.getElementsByTagName('head')[0];
        r=o.createElement('script');r.async=1;
        r.src=t+h._hjSettings.hjid+j+h._hjSettings.hjsv;
        a.appendChild(r);
    })(window,document,'https://static.hotjar.com/c/hotjar-','.js?sv=');
</script>
</body>
</html>
