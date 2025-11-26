<?php /* @var $learner TrainingRecord */ ?>
<?php /* @var $employer_main_site Location */ ?>
<?php /* @var $college_main_site Location */ ?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/html" xmlns="http://www.w3.org/1999/html" xmlns="http://www.w3.org/1999/html"
      xmlns="http://www.w3.org/1999/html" xmlns="http://www.w3.org/1999/html">
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<title><?php echo $company_name; ?> | Onboarding</title>
	<meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
	<link rel="stylesheet" href="/assets/adminlte/bootstrap/css/bootstrap.min.css">
	<link rel="stylesheet" href="/assets/adminlte/dist/css/AdminLTE.min.css">
	<link href="/assets/adminlte/plugins/JQuerySteps/jquery.steps.css" rel="stylesheet">
	<link rel="stylesheet" href="/assets/adminlte/plugins/jQueryUI/jquery-ui.css">
	<link rel="stylesheet" href="/assets/adminlte/plugins/iCheck/all.css">
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.5.0/css/font-awesome.min.css">
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/ionicons/2.0.1/css/ionicons.min.css">
	<link href="https://gitcdn.github.io/bootstrap-toggle/2.2.2/css/bootstrap-toggle.min.css" rel="stylesheet">
	<link rel="stylesheet" href="/assets/adminlte/plugins/bootstrap-validator/bootstrapValidator.css">
	<link rel="stylesheet" href="/module_onboarding/css/onboarding.css">

</head>


<body>

<nav id="mainNav" class="navbar navbar-default navbar-fixed-top navbar-custom">
	<div class="container">
		<div class="navbar-header page-scroll">
			<a class="navbar-brand" href="#">
				<img height="50px" class="headerlogo" src="<?php echo $header_image1; ?>" />
			</a>
		</div>
	</div>
</nav>

<content id="landingPage">
	<!--<div class="jumbotron">
		<div class="container">
			<div class="nts-secondary-teaser-gradient" style="max-width: 450px; padding: 25px; border-radius: 25px;">
				<h2>Apprenticeship On-boarding<br>Data Capture</h2>
			</div>
		</div>
	</div>-->

	<div class="jumbotron">
		<div class="container">
			<div class="img-responsive" style="margin-top: 5%; margin-left: 15%; margin-bottom: 5%;"><img src="images/logos/siemens/front-page-heading.png" /></div>
		</div>
	</div>

	<div class="nts-secondary-teaser-gradient">
		<div class="text-center" style="padding: 5px;"><button id="btnStartOnboarding" onclick="$('#landingPage').hide(); $('#contentForm').show();" style=" padding-left: 50px; padding-right: 50px;" class="btn btn-primary text-uppercase"><strong>Start</strong>&nbsp; <i class="fa fa-play"></i></button></div>
	</div>
</content>

<content id="contentForm" style="display: none;">
<div class="nts-secondary-teaser-gradient">
	<div class="container"><h3>Apprenticeship On-boarding Data Capture</h3></div>
</div>
<br>
<div class="container">

<div id="loading" title="Please wait"></div>

<form class="form-horizontal" name="frmOnBoarding" id="frmOnBoarding" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post"  autocomplete="off" enctype="multipart/form-data">
<input type="hidden" name="_action" value="save_onboarding" />
<input type="hidden" name="is_finished" value="" />
<input type="hidden" name="id" value="<?php echo $ob_learner->id; ?>" />
<input type="hidden" name="username" value="<?php echo $learner->username; ?>" />
<input type="hidden" name="tr_id" value="<?php echo $learner->id; ?>" />

<h3>Privacy Notice & GDPR</h3>
<step id="step1">
	<div class="nts-secondary-teaser-gradient" style="padding: 5px; border-radius: 5px;">
		<h4>Privacy Notice</h4>
	</div>
	<div class="well">
		<h4><strong>How We Use Your Personal Information</strong></h4>
		<p>This privacy notice is issued by the Education and Skills Funding Agency (ESFA), on behalf of the Secretary of State for the Department of Education (DfE). It is to inform learners how their personal information will be used by the DfE, the ESFA (an executive agency of the DfE) and any successor bodies to these organisations. For the purposes of relevant data protection legislation, the DfE is the data controller for personal data processed by the ESFA.</p>
		<p>Your personal information is used by the DfE to exercise its functions and to meet its statutory responsibilities, including under the Apprenticeships, Skills, Children and Learning Act 2009 and to create and maintain a unique learner number (ULN) and a personal learning record (PLR). Your information will be securely destroyed after it is no longer required for these purposes.</p>
		<p>Your information may be shared with third parties for education, training, employment and well-being related purposes, including for research. This will only take place where the law allows it and the sharing is in compliance with data protection legislation.</p>
		<p>The English European Social Fund (ESF) Managing Authority (or agents acting on its behalf) may contact you in order for them to carry out research and evaluation to inform the effectiveness of training.</p>
		<p>You can agree to be contacted for other purposes by ticking any of the following boxes:</p>

		<table class="table table-responsive">
			<tr>
				<td><input class="clsICheck" <?php echo in_array('1', $selected_RUI)?'checked="checked"':''; ?> type="checkbox" name="RUI[]" value="1" /><label>About courses or learning opportunities</label></td>
				<td><input class="clsICheck" <?php echo in_array('1', $selected_PMC)?'checked="checked"':''; ?> type="checkbox" name="PMC[]" value="1" /><label>By post</label></td>
			</tr>
			<tr>
				<td><input class="clsICheck" <?php echo in_array('2', $selected_RUI)?'checked="checked"':''; ?> type="checkbox" name="RUI[]" value="2" /><label>For surveys and research</label></td>
				<td><input class="clsICheck" <?php echo in_array('2', $selected_PMC)?'checked="checked"':''; ?> type="checkbox" name="PMC[]" value="2" /><label>By phone</label></td>
			</tr>
			<tr>
				<td>&nbsp;</td>
				<td><input class="clsICheck" <?php echo in_array('3', $selected_PMC)?'checked="checked"':''; ?> type="checkbox" name="PMC[]" value="3" /><label>By email</label></td>
			</tr>
		</table>
		<p class="well">Further information about use of and access to your personal data, details of organisations with whom we regularly share data, information about how long we retain your data, and how to change your consent to being contacted, please visit:
		<br><a target="_blank" href="https://www.gov.uk/government/publications/esfa-privacy-notice">https://www.gov.uk/government/publications/esfa-privacy-notice</a>
		</p>
	</div>

	<div class="nts-secondary-teaser-gradient" style="padding: 5px; border-radius: 5px;">
		<img height="50px" style="background-color: #ffffff;" class="pull-right" src="<?php echo $header_image1; ?>" />
		<h4>GDPR</h4>How we use your personal data
	</div>
	<div class="well">
		<p>As you are aware <?php echo $company_name; ?> is your training provider. We want to be transparent with you about how we collect, process and store your data</p>
		<h4><strong>What information do we need?</strong></h4>
		<ul style="margin-left: 15px;">
			<li>Your contact details and personal characteristics</li>
			<li>Medical information we need to know to keep you safe</li>
			<li>Academic progress and attendance records</li>
			<li>Support needs and other pastoral information</li>
			<li>What you do next once you've finished your apprenticeship</li>
		</ul>
		<h4><strong>We will use your personal data in a number of ways, such as:</strong></h4>
		<ul style="margin-left: 15px;">
			<li>Support and monitor your learning, progress and achievement</li>
			<li>Provide you with advice, guidance and pastoral support</li>
			<li>Analyse our performance</li>
			<li>Meet our legal obligations</li>
		</ul>
		<h4><strong>Where do we keep your data?</strong></h4>
		<p>The information we collect about you is used by our staff in the UK. All of our data is stored in the UK, and our electronic data is stored on servers in the UK.</p>
		<h4><strong>How long do we keep your data?</strong></h4>
		<p>We are required to keep all documents, information, data, reports, accounts, records or written or verbal explanations relating to your apprenticeship for a minimum of 6 years after the end of you apprenticeship.</p>
		<h4><strong>Who will we share your information with?</strong></h4>
		<p>We may share information about you with certain other organizations, or get information about you from them. These other organisation�s include government departments, local authorities and examination boards.</p>
		<p>We are required by law to provide certain information about you to the Education and Skills funding agency. We may also haveto provide information to the European Social Fund (ESF).</p>
		<p>We will not give your information about you to anyone without your consent unless the law or policies allow us to do so.</p>
		<h4><strong>Contacting you</strong></h4>
		<p>We will contact you about your attendance, learning, progress and assessment in respect of the course you are studying.</p>
	</div>
	
	<div class="well">
		<h4><strong><i class="fa fa-warning (alias)"></i> Disclaimer</strong> <span class="text-red text-bold"> (please tick the following options to continue)</span> </h4>
		<input class="clsICheck" type="checkbox" name="disclaimer[]" value="1" /><label>I agree to adhere to the rules and regulations of the Data Protection Act 1998 and the Freedom of Information Act 2000, ensuring high standards in the returning and communication of personal information and giving  a general right of access to all recorded information held by public authorities, including educational establishments.</label>
		<br><input class="clsICheck" type="checkbox" name="disclaimer[]" value="2" /><label>I agree to promote and adhere to Equal Opportunity and Diversity policies on race, gender, age, disability, religion or belief and sexual orientation within the Apprenticeship Programme.</label>
		<br><input class="clsICheck" type="checkbox" name="disclaimer[]" value="3" /><label>I have read and understood GDPR statement regarding my personal data.</label>
	</div>
</step>

<h3>Personal Information</h3>
<step id="step2">
	<div class="nts-secondary-teaser-gradient" style="padding: 5px; border-radius: 5px;">
		<h4>Personal Information</h4>
	</div><br>
	<div class="col-sm-6">
		<div class="form-group">
			<label for="learner_title" class="col-sm-4 control-label fieldLabel_compulsory">Title:</label>
			<div class="col-sm-8">
				<?php echo HTML::selectChosen('learner_title', $titlesDDl, $ob_learner->learner_title, true); ?>
			</div>
		</div>
		<div class="form-group">
			<label for="firstnames" class="col-sm-4 control-label fieldLabel_compulsory">First Name(s):</label>
			<div class="col-sm-8">
				<input type="text" class="form-control compulsory" name="firstnames" id="firstnames" value="<?php echo $ob_learner->firstnames; ?>" maxlength="100" />
			</div>
		</div>
		<div class="form-group">
			<label for="surname" class="col-sm-4 control-label fieldLabel_compulsory">Surname:</label>
			<div class="col-sm-8">
				<input type="text" class="form-control compulsory" name="surname" id="surname" value="<?php echo $ob_learner->surname; ?>" maxlength="100" />
			</div>
		</div>
		<div class="form-group">
			<label for="input_dob" class="col-sm-4 control-label fieldLabel_compulsory">Date of Birth:</label>
			<div class="col-sm-8"><input class="datecontrol compulsory form-control" type="text" id="input_dob" name="dob" value="<?php echo Date::toShort($learner->dob); ?>" size="10" maxlength="10" placeholder="dd/mm/yyyy" /></div>
		</div>
		<div class="form-group">
			<label for="ethnicity" class="col-sm-4 control-label fieldLabel_optional">Ethnicity:</label>
			<div class="col-sm-8">
				<?php echo HTML::selectChosen('ethnicity', $ethnicityDDL, $ob_learner->ethnicity, true); ?>
			</div>
		</div>
		<div class="form-group">
			<label for="gender" class="col-sm-4 control-label fieldLabel_compulsory">Gender:</label>
			<div class="col-sm-8">
				<?php echo HTML::selectChosen('gender', InductionHelper::getDDLGender(), $ob_learner->gender, true, true); ?>
			</div>
		</div>
		<div class="form-group">
			<label for="ni" class="col-sm-4 control-label fieldLabel_compulsory">National Insurance:</label>
			<div class="col-sm-8">
				<input type="text" class="form-control compulsory" name="ni" id="ni" value="<?php echo $ob_learner->ni; ?>" maxlength="9" />
			</div>
		</div>
		<div class="form-group small">
			<label for="LLDD" class="col-sm-4 control-label fieldLabel_compulsory">Do you consider yourself to have a learning difficulty, health problem or disability?:</label>
			<div class="col-sm-8">
				<?php echo HTML::selectChosen('LLDD', $LLDD, $ob_learner->LLDD, true, true); ?>
			</div>
		</div>
		<div class="form-group" id="divLLDDCat" style="display: none;">
			<div class="col-sm-12" style="max-height: 300px; overflow-y: scroll;">
				<label>Select categories:</label>
				<table class="text-center">
					<tr><th>Category</th><th>Primary</th></tr>
					<?php
					foreach($LLDDCat AS $key => $value)
					{
						$checked = in_array($key, $selected_llddcat)?'checked="checked"':'';
						$checked_pri = $key == $ob_learner->primary_lldd?'checked="checked"':'';
						echo '<tr><td><input class="clsICheck" type="checkbox" name="llddcat[]" '.$checked.' value="'.$key.'" /><label>'.$value.'</label></td><td><p><input type="radio" name="primary_lldd" value="'.$key.'" '.$checked_pri.'></td></tr>';
					}
					?>
				</table>
			</div>
		</div>
		<div class="form-group small">
			<label for="care_leaver" class="col-sm-6 control-label fieldLabel_optional">Are you a care leaver?:</label>
			<div class="col-sm-6">
				<input type="checkbox" name="care_leaver" data-toggle="toggle" data-on="Yes" data-off="No" data-onstyle="success" data-offstyle="danger" <?php echo $ob_learner->care_leaver == "1" ? 'checked="checked"' : '';?> />
			</div>
		</div>
		<div class="form-group small">
			<label for="EHC_Plan" class="col-sm-6 control-label fieldLabel_optional">Do you have an EHC Plan?:</label>
			<div class="col-sm-6">
				<input type="checkbox" name="EHC_Plan" data-toggle="toggle" data-on="Yes" data-off="No" data-onstyle="success" data-offstyle="danger" <?php echo $ob_learner->EHC_Plan == "1" ? 'checked="checked"' : '';?> />
			</div>
		</div>
		<div class="form-group small">
			<label for="care_or_ehc" class="col-sm-4 control-label fieldLabel_optional">Upload evidence for care leaver or EHC plan:</label>
			<div class="col-sm-8">
				<input type="file" class="form-control optional" name="care_or_ehc" id="care_or_ehc" value=""  />
			</div>
		</div>
	</div>
	<div class="col-sm-6">
		<div class="form-group">
			<label for="home_address_line_1" class="col-sm-4 control-label fieldLabel_compulsory">Address Line 1:</label>
			<div class="col-sm-8">
				<input type="text" class="form-control compulsory" name="home_address_line_1" id="home_address_line_1" value="<?php echo $ob_learner->home_address_line_1; ?>" maxlength="100" />
			</div>
		</div>
		<div class="form-group">
			<label for="home_address_line_2" class="col-sm-4 control-label fieldLabel_optional">Address Line 2:</label>
			<div class="col-sm-8">
				<input type="text" class="form-control optional" name="home_address_line_2" id="home_address_line_2" value="<?php echo $ob_learner->home_address_line_2; ?>" maxlength="100" />
			</div>
		</div>
		<div class="form-group">
			<label for="home_address_line_3" class="col-sm-4 control-label fieldLabel_compulsory">City:</label>
			<div class="col-sm-8">
				<input type="text" class="form-control compulsory" name="home_address_line_3" id="home_address_line_3" value="<?php echo $ob_learner->home_address_line_3; ?>" maxlength="100" />
			</div>
		</div>
		<div class="form-group">
			<label for="home_address_line_4" class="col-sm-4 control-label fieldLabel_optional">County:</label>
			<div class="col-sm-8">
				<input type="text" class="form-control optional" name="home_address_line_4" id="home_address_line_4" value="<?php echo $ob_learner->home_address_line_4; ?>" maxlength="100" />
			</div>
		</div>
		<div class="form-group">
			<label for="home_postcode" class="col-sm-4 control-label fieldLabel_compulsory">Postcode:</label>
			<div class="col-sm-8">
				<input type="text" class="form-control compulsory" name="home_postcode" id="home_postcode" value="<?php echo $learner->home_postcode; ?>" maxlength="100" />
			</div>
		</div>
		<div class="form-group">
			<label for="home_telephone" class="col-sm-4 control-label fieldLabel_optional">Telephone:</label>
			<div class="col-sm-8">
				<input type="text" class="form-control optional" name="home_telephone" id="home_telephone" value="<?php echo $ob_learner->home_telephone; ?>" maxlength="100" />
			</div>
		</div>
		<div class="form-group">
			<label for="home_mobile" class="col-sm-4 control-label fieldLabel_optional">Mobile Phone:</label>
			<div class="col-sm-8">
				<input type="text" class="form-control optional" name="home_mobile" id="home_mobile" value="<?php echo $ob_learner->home_mobile; ?>" maxlength="100" />
			</div>
		</div>
		<div class="form-group">
			<label for="home_email" class="col-sm-4 control-label fieldLabel_optional">Email:</label>
			<div class="col-sm-8">
				<input type="text" class="form-control optional" name="home_email" id="home_email" value="<?php echo $learner->home_email; ?>" maxlength="100" />
			</div>
		</div>
		<div class="callout">
			<label>Emergency Contact</label>
			<div class="form-group">
				<label for="em_con_title" class="col-sm-4 control-label fieldLabel_optional">Title:</label>
				<div class="col-sm-8">
					<?php echo HTML::selectChosen('em_con_title', $titlesDDl, $ob_learner->em_con_title, true); ?>
				</div>
			</div>
			<div class="form-group">
				<label for="em_con_name" class="col-sm-4 control-label fieldLabel_optional">Name:</label>
				<div class="col-sm-8">
					<input type="text" class="form-control optional" name="em_con_name" id="em_con_name" value="<?php echo $ob_learner->em_con_name; ?>" maxlength="100" />
				</div>
			</div>
			<div class="form-group">
				<label for="em_con_rel" class="col-sm-4 control-label fieldLabel_optional">Relationship:</label>
				<div class="col-sm-8">
					<input type="text" class="form-control optional" name="em_con_rel" id="em_con_rel" value="<?php echo $ob_learner->em_con_rel; ?>" maxlength="100" />
				</div>
			</div>
			<div class="form-group">
				<label for="em_con_tel" class="col-sm-4 control-label fieldLabel_optional">Telephone:</label>
				<div class="col-sm-8">
					<input type="text" class="form-control optional" name="em_con_tel" id="em_con_tel" value="<?php echo $ob_learner->em_con_tel; ?>" maxlength="100" />
				</div>
			</div>
			<div class="form-group">
				<label for="em_con_mob" class="col-sm-4 control-label fieldLabel_optional">Mobile:</label>
				<div class="col-sm-8">
					<input type="text" class="form-control optional" name="em_con_mob" id="em_con_mob" value="<?php echo $ob_learner->em_con_mob; ?>" maxlength="100" />
				</div>
			</div>
		</div>
	</div>
</step>

<h3>Prior Attainment</h3>
<step id="prior_attainment">
	<div class="nts-secondary-teaser-gradient" style="padding: 5px; border-radius: 5px;">
		<h4>Prior Attainment</h4>
	</div>

	<div class="well well-sm"><p>Please list your educational prior attainment and include your maths, english, ICT, or any other engineering related qualifications.</p></div>
	<div style="max-height: 600px; overflow-y: scroll;">
		<table class="table table-responsive row-border cw-table-list">
			<tr><th style="width: 25%;">GCSE/A/AS Level</th><th style="width: 25%;">Subject</th><th style="width: 15%;">Predicted Grade</th><th style="width: 15%;">Actual Grade</th><th style="width: 20%;">Date Completed</th></tr>
			<tbody>
			<tr><?php $ob_eng = DAO::getObject($link, "SELECT * FROM ob_learners_pa WHERE ob_learner_id = '{$ob_learner->id}' AND level = '101'");?>
				<td>GCSE <input type="hidden" name="gcse_english_level" value="101" /></td>
				<td>English Language<input type="hidden" name="gcse_english_subject" value="English" /></td>
				<td>
					<?php $qual_grades = DAO::getResultset($link,"SELECT id, description, NULL FROM lookup_gcse_grades WHERE description NOT IN ('Credit', 'Distinction*') ORDER BY id;", DAO::FETCH_NUM);
					echo HTML::selectChosen('gcse_english_grade_predicted', $qual_grades, isset($ob_eng->p_grade)?$ob_eng->p_grade:'', true, true, true);
					?>
				</td>
				<td><?php echo HTML::selectChosen('gcse_english_grade_actual', $qual_grades, isset($ob_eng->a_grade)?$ob_eng->a_grade:'', true, true, true); ?></td>
				<td><input class="datecontrol compulsory form-control" type="text" name="gcse_english_date_completed" id="input_gcse_english_date_completed" value="<?php echo isset($ob_eng->date_completed)?Date::toShort($ob_eng->date_completed):''; ?>" size="10" maxlength="10" placeholder="dd/mm/yyyy" /></td>
			</tr>
			<tr>
				<?php
				$ob_maths = DAO::getObject($link, "SELECT * FROM ob_learners_pa WHERE ob_learner_id = '{$ob_learner->id}' AND level = '102'");
				?>
				<td>GCSE <input type="hidden" name="gcse_maths_level" value="102" /></td>
				<td>Maths<input type="hidden" name="gcse_maths_subject" value="Maths" /></td>
				<td><?php echo HTML::selectChosen('gcse_maths_grade_predicted', $qual_grades, isset($ob_maths->p_grade)?$ob_maths->p_grade:'', true, true, true); ?></td>
				<td><?php echo HTML::selectChosen('gcse_maths_grade_actual', $qual_grades, isset($ob_maths->a_grade)?$ob_maths->a_grade:'', true, true, true); ?></td>
				<td><input class="datecontrol compulsory form-control" type="text" name="gcse_maths_date_completed" id="input_gcse_maths_date_completed" value="<?php echo isset($ob_maths->date_completed)?Date::toShort($ob_maths->date_completed):''; ?>" size="10" maxlength="10" placeholder="dd/mm/yyyy" /></td>
			</tr>
			<?php
			for($i = 1; $i <= 15; $i++)
			{
				$ob_q = DAO::getObject($link, "SELECT * FROM ob_learners_pa WHERE ob_learner_id = '{$ob_learner->id}' AND q_type = '{$i}'");
				echo '<tr>';
				echo '<td>' . HTML::selectChosen('level'.$i, $QualLevelsDDL, isset($ob_q->level)?$ob_q->level:'', true, false, true) . '</td>';
				if(isset($ob_q->subject))
					echo '<td><input class="form-control compulsory" type="text" name="subject'.$i.'" id="subject'.$i.'" value="' . $ob_q->subject . '" /></td>';
				else
					echo '<td><input class="form-control compulsory" type="text" name="subject'.$i.'" id="subject'.$i.'" value="" /></td>';
				echo '<td>' . HTML::selectChosen('predicted_grade'.$i, $qual_grades, isset($ob_q->p_grade)?$ob_q->p_grade:'', true, false, true) . '</td>';
				echo '<td>' . HTML::selectChosen('actual_grade'.$i, $qual_grades, isset($ob_q->a_grade)?$ob_q->a_grade:'', true, false, true) . '</td>';
				if(isset($ob_q->date_completed))
					echo '<td><input class="datecontrol compulsory form-control" type="text" name="date_completed'.$i.'" id="input_date_completed'.$i.'" value="'.Date::toShort($ob_q->date_completed).'" size="10" maxlength="10" placeholder="dd/mm/yyyy" /><input class="form-control optional" type="hidden" name="q_type'.$i.'" id="q_type'.$i.'" value="'.$i.'" /></td>';
				else
					echo '<td><input class="datecontrol compulsory form-control" type="text" name="date_completed'.$i.'" id="input_date_completed'.$i.'" value="" size="10" maxlength="10" placeholder="dd/mm/yyyy" /><input class="form-control optional" type="hidden" name="q_type'.$i.'" id="q_type'.$i.'" value="'.$i.'" /></td>';
				echo '</tr>';
			}
			?>
		</table>
	</div>

	<table class="table table-responsive row-border cw-table-list">
		<tr style="background-color: #e0ffff;">
			<td colspan="5">
				<label>Prior Attainment Level</label>
				<p>
					<i class="text-muted">Please use the <span style="margin-top: 2px;" class="btn btn-info btn-sm" onclick="window.open('PriorAttainmentGuidance2018_19.pdf', '_blank')"><i class="fa fa-info-circle"></i> Guidance Notes</span>
						to let us know the overall level of prior attainment of your qualifications achieved to date.<br>For example,</i>
				</p>
				<ul style="margin-left: 25px;">
					<li><i class="text-muted">if you have 4 GCSE's with Grades A - C, this would fall into Level 1</i></li>
					<li><i class="text-muted">if you have 5 GCSE's with Grades A - C, this would fall into Level 2</i></li>
				</ul>
			</td>
		</tr>
		<tr style="background-color: #e0ffff;">
			<?php $ob_high = DAO::getObject($link, "SELECT * FROM ob_learners_pa WHERE ob_learner_id = '{$ob_learner->id}' AND q_type = 'h'"); ?>
			<td colspan="1" align="right">
				I consider my Prior Attainment Level to be
			</td>
			<td colspan="4" align="left">
                <?php
                echo isset($ob_high->level) ?
                    HTML::selectChosen('high_level', $PriorAttainDDL, $ob_high->level, true, false, true) :
                    HTML::selectChosen('high_level', $PriorAttainDDL, '', true, false, true);
                ?>
            </td>
		</tr>
		<tr>
			<td colspan="5"><i class="fa fa-info-circle"></i> <label>Allowed file types: &nbsp;</label> <i class="fa fa-file-pdf-o" title="pdf (.pdf)"></i> <i class="fa fa-file-word-o" title="Microsoft Word (.doc, .docx)"></i> <i class="fa fa-file-text" title="Text file (.txt)"></i> <i class="fa fa-file-image-o" title="Images (.png, .jpg)"></i></td>
		</tr>
		<tr>
			<td colspan="5">
				<label for="file1" class="col-sm-4 control-label fieldLabel_optional">Certificate / Evidence :</label>
				<div class="col-sm-8">
					<input type="file" class="form-control optional" name="file1" id="file1" value=""  />
				</div>
			</td>
		</tr>
		<tr>
			<td colspan="5">
				<label for="file2" class="col-sm-4 control-label fieldLabel_optional">Certificate / Evidence :</label>
				<div class="col-sm-8">
					<input type="file" class="form-control optional" name="file2" id="file2" value=""  />
				</div>
			</td>
		</tr>
		<tr>
			<td colspan="5">
				<label for="file3" class="col-sm-4 control-label fieldLabel_optional">Certificate / Evidence :</label>
				<div class="col-sm-8">
					<input type="file" class="form-control optional" name="file3" id="file3" value=""  />
				</div>
			</td>
		</tr>
		<tr>
			<td colspan="5">
				<label for="file4" class="col-sm-4 control-label fieldLabel_optional">Certificate / Evidence :</label>
				<div class="col-sm-8">
					<input type="file" class="form-control optional" name="file4" id="file4" value=""  />
				</div>
			</td>
		</tr>
		<tr>
			<td colspan="5">
				<label for="file5" class="col-sm-4 control-label fieldLabel_optional">Certificate / Evidence :</label>
				<div class="col-sm-8">
					<input type="file" class="form-control optional" name="file5" id="file5" value=""  />
				</div>
			</td>
		</tr>
		<tr>
			<td colspan="5">
				<label for="file6" class="col-sm-4 control-label fieldLabel_optional">Certificate / Evidence :</label>
				<div class="col-sm-8">
					<input type="file" class="form-control optional" name="file6" id="file6" value=""  />
				</div>
			</td>
		</tr>
		</tbody>
	</table>
	<p><div class="callout callout-info"><span class="fa fa-info-circle"></span> You should bring copies / or originals of your certificates for induction meeting</div></p>
</step>

<h3>Eligibility</h3>
<step>
	<div class="nts-secondary-teaser-gradient" style="padding: 5px; border-radius: 5px;">
		<h4>Eligibility Checklist</h4>
	</div>
	<span data-toggle="modal" data-target="#modalChecklistGuidance" style="margin-top: 2px;" class="btn btn-info"><i class="fa fa-info-circle"></i> Guidance Notes</span>
	<table class="table row-border">
		<tbody>
		<?php
		if(DB_NAME == "am_lead_demo")
			$questions = DAO::getResultset($link, "SELECT * FROM lookup_onboarding_questions WHERE id IN (1, 2, 3, 4, 5, 6, 7, 8) ORDER BY id", DAO::FETCH_ASSOC);
		else
			$questions = DAO::getResultset($link, "SELECT * FROM lookup_onboarding_questions WHERE id IN (1, 2, 3) ORDER BY id", DAO::FETCH_ASSOC);
		$saved_eligibility_list = explode(',', $ob_learner->EligibilityList);
		foreach($questions AS $q)
		{
			$checked = in_array($q['id'], $saved_eligibility_list)?'checked="checked"':'';
			echo in_array($q['id'], array(7,8,9,10,11,12))?'<tr style="background-color: #e0ffff;">':'<tr>';
			echo '<td>' . $q['description'] . '</td>';
			echo '<td><input type="checkbox" name="EligibilityList[]" value="'.$q['id'].'" data-toggle="toggle" data-on="Yes" data-off="No" data-onstyle="success" data-offstyle="danger"'.$checked.' /></td>';
			echo '</tr>';
		}
		?>
		</tbody>
	</table>
	<table class="table row-border">
		<tbody>
		<tr><td>Country of birth:</td><td><?php echo HTML::selectChosen('country_of_birth', $countries, $ob_learner->country_of_birth, false); ?></td><td>Country of permanent residence:</td><td><?php echo HTML::selectChosen('country_of_perm_residence', $countries, $ob_learner->country_of_perm_residence, false); ?></td></tr>
		<tr>
			<td>Nationality:</td>
			<td>
				<?php
				if($ob_learner->nationality != '')
					echo HTML::selectChosen('nationality', $nationalities, $ob_learner->nationality, false);
				else
					echo HTML::selectChosen('nationality', $nationalities, '27', false);
				?>
			</td>
		</tr>
		<tr><td>Please provide a copy of your passport or birth certificate:</td><td><input type="file" class="form-control optional" name="evidence_pp" id="evidence_pp" value=""  /></td></tr>
		</tbody>
	</table>
	<table class="table">
		<tbody>
		<tr style="background-color: #d3d3d3;"><th colspan="2">Applicants not born in the United Kingdom, please answer the following questions</th></tr>
		<tr><td>Are you a non-EU citizen currently resident in the UK?</td><td><input type="checkbox" name="is_non_eu_resident" data-toggle="toggle" data-on="Yes" data-off="No" data-onstyle="success" data-offstyle="danger"<?php echo $ob_learner->is_non_eu_resident == "1" ? 'checked="checked"' : '';?> /></td></tr>
		<tr><td colspan="2">If you have checked the box, please provide the following information in order to assist us in making an assessment of your fee status.</td></tr>
		<tr><td>Date of first entry to the UK:</td><td><input class="datecontrol compulsory form-control" type="text" id="date_of_first_uk_entry" name="date_of_first_uk_entry" value="<?php echo Date::toShort($ob_learner->date_of_first_uk_entry); ?>" size="10" maxlength="10" placeholder="dd/mm/yyyy" /></td></tr>
		<tr><td>Date of most recent entry to the UK (excluding holidays):</td><td><input class="datecontrol compulsory form-control" type="text" id="date_of_most_recent_uk_entry" name="date_of_most_recent_uk_entry" value="<?php echo Date::toShort($ob_learner->date_of_most_recent_uk_entry); ?>" size="10" maxlength="10" placeholder="dd/mm/yyyy" /></td></tr>
		<tr><td>Have you been granted indefinite Leave to Enter/Remain in the UK? If yes, please provide a copy of your ILR status as evidence.</td><td><input type="file" class="form-control optional" name="evidence_ilr" id="evidence_ilr" value=""  /></td></tr>
		</tbody>
	</table>
	<table class="table">
		<tbody>
		<tr><td>Do you need a visa to study in the UK?</td><td><input type="checkbox" name="need_visa_to_study" data-toggle="toggle" data-on="Yes" data-off="No" data-onstyle="success" data-offstyle="danger"<?php echo $ob_learner->need_visa_to_study == "1" ? 'checked="checked"' : '';?> /></td></tr>
		<tr><td>If you have checked the box, please provide your passport number:</td><td><input class="form-control" type="text" name="passport_number" id="passport_number" value="<?php echo $ob_learner->passport_number; ?>" /></td></tr>
		<tr><td>If no, under what immigration category will you enter the UK:</td><td><input class="form-control" type="text" name="immigration_category" id="immigration_category" value="<?php echo $ob_learner->immigration_category; ?>" /></td></tr>
		<tr><td>Have you previously been granted a visa to study in the UK? If yes, please upload a copy of any such visas.</td><td><input type="file" class="form-control optional" name="evidence_previous_uk_study_visa" id="evidence_previous_uk_study_visa" value=""  /></td></tr>
		</tbody>
	</table>
	<table class="table row-border">
		<tbody>
		<?php
		if(DB_NAME == "am_lead_demo")
			$questions = DAO::getResultset($link, "SELECT * FROM lookup_onboarding_questions WHERE id > 8 ORDER BY id", DAO::FETCH_ASSOC);
		else
			$questions = DAO::getResultset($link, "SELECT * FROM lookup_onboarding_questions WHERE id IN (25, 26) ORDER BY id", DAO::FETCH_ASSOC);
		$saved_eligibility_list = explode(',', $ob_learner->EligibilityList);
		foreach($questions AS $q)
		{
			$checked = in_array($q['id'], $saved_eligibility_list)?'checked="checked"':'';
			echo in_array($q['id'], array(7,8,9,10,11,12))?'<tr style="background-color: #e0ffff;">':'<tr>';
			echo '<td>' . $q['description'] . '</td>';
			echo '<td><input type="checkbox" name="EligibilityList[]" value="'.$q['id'].'" data-toggle="toggle" data-on="Yes" data-off="No" data-onstyle="success" data-offstyle="danger"'.$checked.' /></td>';
			echo '</tr>';
		}
		?>
		</tbody>
	</table>

	<div class="modal fade" id="modalChecklistGuidance" role="dialog" data-backdrop="static" data-keyboard="false">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<h4 class="modal-title">Apprenticeship Eligibility Checklist 2017/18</h4>
				</div>
				<div class="modal-body">
					<h4><strong>Guidance Notes:</strong></h4>
					<strong>EHC Plans and Care - </strong>
					<p>A child in care is defined as:</p>
					<ul style="margin-left: 15px;">
						<li>a young person who is 16 or 17 and who has been looked after by the local authority/Health and Social Care Trust for at least a period of 13 weeks since the age of 14, and who is still looked after</li>
						<li>a young person who is 16 or 17 who has left care after their 16th birthday and before leaving care was an eligible child</li>
						<li>a former relevant child - a young person who is aged between 18 and 21 (up to their 25th birthday if they are in education or training) who, before turning 18, was either an eligible or a relevant child, or both</li>
					</ul>
					<strong>Exceptional Status - </strong>
					<ul style="margin-left: 15px;">
						<li>Where an individual or relevant family member has applied for an extension or variation of their current immigration permission in the UK they will be treated as if they have that leave. This only applies if the application was made before their current permission expired. Their leave continues until the Home Office make a decision on their immigration application.</li>
						<li>An individual, or relevant family member, is considered to have the immigration permission that they held when they made their application for an extension, and their eligibility would be based upon this status.</li>
					</ul>
					<strong>Right of Abode - </strong>
					<ul style="margin-left: 15px;">
						<li>Is the right to live permanently in the UK without immigration restrictions.</li>
					</ul>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-default pull-left" data-dismiss="modal">Close</button>
				</div>
			</div>
		</div>
	</div>
</step>

<h3>ESF Declaration</h3>
<step>
	<div class="nts-secondary-teaser-gradient" style="padding: 5px; border-radius: 5px;">
		<h4>ESF Declaration</h4>
	</div>
	<div class="well">
		<p>The European Social Fund (ESF) was set up to improve employment opportunities in the European Union and so help raise standards of living. It aims to help people fulfil their potential by giving them better skills and better job prospects.</p>
		<p>As one of the EU's Structural Funds, ESF seeks to reduce differences in prosperity across the EU an enhance and social cohesion. So although ESF funding is spread across the EU, most money goes to those countries and regions where economic development is less advanced.</p>
		<p>The ESF is a key part of the strategy for jobs and smart, sustainable and inclusive growth. It supports the EU's goal od increasing employment by giving unemployed and disadvantaged people the training and support they need to enter jobs. ESF also quips the workforce with the skills needed by business in a competitive global economy.</p>
		<p>This investment is providing new opportunities to people who face the greatest barriers to work and learning. The programme is contributing to the governments social justice strategy by providing additional support to disadvantaged groups such as troubled families and young people NEET. It is supporting growth by investing in apprenticeships and workplace learning.</p>
		<p>ESF funds are distributed through public agencies such as the Skills Funding Agency, DWP and National Offender Management Service (NOMS). These agencies are known as 'Co-financing Organisations'. Their role is to bring together ESF and domestic funding for employment and skills so that ESF complements domestic programmes.</p>
	</div>
	<input class="clsICheck" type="checkbox" name="chkESF" value="1" /><label>Please tick this box to confirm you agree to the above ESF Declaration</label>
</step>

<h3>Employment Status</h3>
<step>
	<div class="nts-secondary-teaser-gradient" style="padding: 5px; border-radius: 5px;">
		<h4>Employment Status Questionnaire</h4>
	</div><br>
	<p class="">Please tell us more about what you did prior to starting your Apprenticeship Programme on the <label><?php echo Date::toLong($learner->start_date); ?></label>.</p>
	<div class="form-group">
		<label for="EmploymentStatus" class="col-sm-4 control-label fieldLabel_optional">Were you</label>
		<div class="col-sm-8">
			<?php
			$ipe = ''; $nipn = ''; $nipl = ''; $nk = '';
			if($ob_learner->EmploymentStatus == '10') $ipe = 'checked = "checked"';
			if($ob_learner->EmploymentStatus == '11') $nipn = 'checked = "checked"';
			if($ob_learner->EmploymentStatus == '12') $nipl = 'checked = "checked"';
			if($ob_learner->EmploymentStatus == '98') $nk = 'checked = "checked"';
			?>
			<p><input type="radio" name="EmploymentStatus" <?php echo $ipe; ?>value="10"> In paid employment</p>
			<p><input type="radio" name="EmploymentStatus" <?php echo $nipn; ?> value="11"> Not in paid employment, looking for work and available to start work</p>
			<p><input type="radio" name="EmploymentStatus" <?php echo $nipl; ?> value="12"> Not in paid employment, not looking for work and/or not available to start work</p>
			<p><input type="radio" name="EmploymentStatus" <?php echo $nk; ?> value="98"> Not known / don't want to provide</p>
		</div>
	</div>
	<table id="tbl_emp_status_10" class="table row-border" style="display: none;">
		<?php
		$work_curr_emp_checked = '';
		if($ob_learner->EmploymentStatus == '10' && $ob_learner->work_curr_emp == '1')$work_curr_emp_checked = 'checked = "checked"';
		$SEI_checked = '';
		if($ob_learner->EmploymentStatus == '10' && $ob_learner->SEI == '1')$SEI_checked = 'checked = "checked"';
		$PEI_checked = '';
		if(($ob_learner->EmploymentStatus == '11' || $ob_learner->EmploymentStatus == '12') && $ob_learner->PEI == '1')$PEI_checked = 'checked = "checked"';
		$SEM_checked = '';
		if($ob_learner->EmploymentStatus == '10' && $ob_learner->SEM == '1')$SEM_checked = 'checked = "checked"';
		?>
		<tr>
			<th>Were you employed with your current employer<br>prior to you starting your Apprenticeship Programme?</th>
			<td><input type="checkbox" name="work_curr_emp" id="work_curr_emp" data-toggle="toggle" data-on="Yes" data-off="No" data-onstyle="success" data-offstyle="danger" <?php echo $work_curr_emp_checked; ?> /></td>
		</tr>
		<tr>
			<th>If not, were you self-employed?</th>
			<td><input type="checkbox" name="SEI" id="SEI" data-toggle="toggle" data-on="Yes" data-off="No" data-onstyle="success" data-offstyle="danger" <?php echo $SEI_checked; ?> /></td>
		</tr>
		<tr>
			<th>Tell us your Employer Name?</th>
			<td><input class="form-control compulsory" type="text" name="empStatusEmployer" id="empStatusEmployer" value="<?php echo $ob_learner->empStatusEmployer; ?>" /></td>
		</tr>
		<tr>
			<th>Was the company a Small Employer with less than 50 employees?</th>
			<td><input type="checkbox" name="SEM" id="SEM" data-toggle="toggle" data-on="Yes" data-off="No" data-onstyle="success" data-offstyle="danger" <?php echo $SEM_checked; ?> /></td>
		</tr>
		<tr>
			<th>How long were you employed?</th>
			<td><?php echo HTML::selectChosen('LOE', $LOE_dropdown, $ob_learner->LOE, false); ?></td>
		</tr>
		<tr>
			<th>How many hours did you work each week?</th>
			<td><?php echo HTML::selectChosen('EII', $EII_dropdown, $ob_learner->EII, false); ?></td>
		</tr>
	</table>
	<table id="tbl_emp_status_11_12" class="table row-border" style="display: none;">
		<tr>
			<th>How long were you un-employed before <label class="text-blue"><?php echo Date::toLong($learner->start_date); ?></label>?</th>
			<td><?php echo HTML::selectChosen('LOU', $LOU_dropdown, $ob_learner->LOU, false); ?></td>
		</tr>
		<tr>
			<th>Did you receive any of these benefits?</th>
			<td><?php echo HTML::selectChosen('BSI', $BSI_dropdown, $ob_learner->BSI, false); ?></td>
		</tr>
		<tr>
			<th>Were you in Full Time Education or Training prior to <label class="text-blue"><?php echo Date::toLong($learner->start_date); ?></label>?</th>
			<td><input type="checkbox" name="PEI" id="PEI" data-toggle="toggle" data-on="Yes" data-off="No" data-onstyle="success" data-offstyle="danger" <?php echo $PEI_checked; ?> /></td>
		</tr>
	</table>

</step>

<h3>Individual Learning Plan</h3>
<step>
<div class="nts-secondary-teaser-gradient" style="padding: 5px; border-radius: 5px;">
	<h4>Individual Learning Plan</h4>
</div>
<div class="small">
	<div class="row"><div class="col-sm-12"><strong>Section 1: Learner, Employer / Organisation and Provider (as applicable) Details: </strong></div></div>
	<div class="row">
		<div class="col-lg-6 col-md-6 col-sm-12">
			<table class="table table-responsive table-bordered ilp" id="ilp_learner">
				<tr><th colspan="2" style="background-color: #e0ffff;">Learner Details</th></tr>
				<tr><th>Title:</th><td id="ilp_learner_title"></td></tr>
				<tr><th>Learner First Name(s):</th><td id="ilp_firstnames"></td></tr>
				<tr><th>Learner Surname:</th><td id="ilp_surname"></td></tr>
				<!--<tr><th>Date of Birth:</th><td id="ilp_input_dob"></td></tr>
				<tr><th>Address:</th><td id="ilp_address"></td></tr>
				<tr><th>Postcode:</th><td id="ilp_home_postcode"></td></tr>-->
				<tr><th>Email:</th><td id="ilp_home_email"></td></tr>
				<tr><th>Telephone:</th><td id="ilp_home_telephone"></td></tr>
				<tr><th>Mobile:</th><td id="ilp_home_mobile"></td></tr>
			</table>
		</div>
		<div class="col-lg-6 col-md-6 col-sm-12">
			<table class="table table-responsive table-bordered ilp">
				<tr><th colspan="2" style="background-color: #e0ffff;">Employer Organisation Details</th></tr>
				<tr><th>Apprentice Coordinator Name:</th><td><?php echo DAO::getSingleValue($link, "SELECT CONCAT(firstnames, ' ', surname) FROM users WHERE users.id = '{$learner->programme}'"); ?></td></tr>
				<tr><th>Employer Name:</th><td><?php echo DAO::getSingleValue($link, "SELECT legal_name FROM organisations WHERE id = '{$learner->employer_id}'"); ?></td></tr>
				<tr><th>Employer Contact:</th><td><?php echo DAO::getSingleValue($link, "SELECT contact_name FROM organisation_contact WHERE contact_id = '{$learner->crm_contact_id}'"); ?></td></tr>
				<tr><th>Employer Address:</th><td><?php echo $employer_main_site->address_line_1 . ' ' . $employer_main_site->address_line_2 . ' ' . $employer_main_site->address_line_3 . ' ' . $employer_main_site->address_line_4; ?></td></tr>
				<tr><th>Postcode:</th><td><?php echo $employer_main_site->postcode; ?></td></tr>
<!--				<tr><th>Business Code:</th><td>--><?php //echo DAO::getSingleValue($link, "SELECT title FROM brands INNER JOIN employer_business_codes ON brands.id = employer_business_codes.`brands_id` WHERE employer_business_codes.`employer_id` = '{$learner->employer_id}';"); ?><!--</td></tr>-->
				<tr><th>Mobile:</th><td><?php echo DAO::getSingleValue($link, "SELECT contact_mobile FROM organisation_contact WHERE contact_id = '{$learner->crm_contact_id}'"); ?></td></tr>
				<tr><th>Telephone:</th><td><?php echo DAO::getSingleValue($link, "SELECT contact_telephone FROM organisation_contact WHERE contact_id = '{$learner->crm_contact_id}'"); ?></td></tr>
			</table>
		</div>
	</div>

	<div class="row">
		<div class="col-lg-12 col-md-12 col-sm-12">
			<table class="table table-responsive table-bordered ilp">
				<tr><th colspan="6" style="background-color: #e0ffff;">Programme Details</th></tr>
				<tr><th>Programme Title:</th><td colspan="5"><?php echo DAO::getSingleValue($link, "SELECT title FROM student_frameworks WHERE tr_id = '{$learner->id}'"); ?></td></tr>
				<tr><th>Programme Type:</th><td colspan="5"><?php echo $programme_type; ?></td></tr>
				<tr>
					<th>Programme Start Date:</th><td><?php echo Date::toShort($learner->start_date); ?></td>
					<th>Programme Expected Completion Date:</th><td><?php echo Date::toShort($learner->target_date); ?></td>
					<th>Programme Actual End Date:</th><td>&nbsp; &nbsp; &nbsp;</td>
				</tr>
			</table>
		</div>
	</div>

	<div class="row">
		<!--<div class="col-lg-6 col-md-6 col-sm-12">
			<table class="table table-responsive table-bordered ilp">
				<tr><th colspan="2" style="background-color: #e0ffff;">Emergency Contact Details</th></tr>
				<tr><th>Title:</th><td id="ilp_em_con_title"></td></tr>
				<tr><th>Name:</th><td id="ilp_em_con_name"></td></tr>
				<tr><th>Relationship to Learner:</th><td id="ilp_em_con_rel"></td></tr>
				<tr><th>Home Number:</th><td id="ilp_em_con_tel"></td></tr>
				<tr><th>Mobile Number:</th><td id="ilp_em_con_mob"></td></tr>
			</table>
		</div>-->
		<div class="col-lg-6 col-md-6 col-sm-12">
			<table class="table table-responsive table-bordered ilp">
				<tr><th colspan="2" style="background-color: #e0ffff;">College Details</th></tr>
				<tr><th>College Name:</th><td><?php echo DAO::getSingleValue($link, "SELECT legal_name FROM organisations WHERE id = '{$learner->college_id}'"); ?></td></tr>
				<tr><th>College Contact:</th><td><?php echo isset($college_main_site->contact_name)?$college_main_site->contact_name:''; ?></td></tr>
				<tr><th>College Address:</th>
					<td>
						<?php
						if(isset($college_main_site->address_line_1))
							echo $college_main_site->address_line_1 . ' ' . $college_main_site->address_line_2 . ' ' . $college_main_site->address_line_3 . ' ' . $college_main_site->address_line_4 . '<br>' . $college_main_site->postcode;
						else
							echo '';
						?>
					</td>
				</tr>
			</table>
		</div>
	</div>

	<div class="row">
		<div class="col-lg-12 col-md-12 col-sm-12">
			<table class="table table-responsive table-bordered ilp text-center">
				<tr><th colspan="4" style="background-color: #e0ffff;">1b: Prior Attainment</th></tr>
				<tr><th>Qualification Title (Prior Attainment)</th><th>Date Awarded</th><th>Grade</th><th>Exemption Reason</th></tr>
				<tr id="ilp_gcse_english"><td></td><td></td><td></td><td>&nbsp;</td></tr>
				<tr id="ilp_gcse_maths"><td></td><td></td><td></td><td>&nbsp;</td></tr>
				<tr id="ilp_pa1"><td></td><td></td><td></td><td>&nbsp;</td></tr>
				<tr id="ilp_pa2"><td></td><td></td><td></td><td>&nbsp;</td></tr>
				<tr id="ilp_pa3"><td></td><td></td><td></td><td>&nbsp;</td></tr>
				<tr id="ilp_pa4"><td></td><td></td><td></td><td>&nbsp;</td></tr>
				<tr id="ilp_pa5"><td></td><td></td><td></td><td>&nbsp;</td></tr>
				<tr id="ilp_pa6"><td></td><td></td><td></td><td>&nbsp;</td></tr>
				<tr id="ilp_pa7"><td></td><td></td><td></td><td>&nbsp;</td></tr>
				<tr id="ilp_pa8"><td></td><td></td><td></td><td>&nbsp;</td></tr>
				<tr id="ilp_pa9"><td></td><td></td><td></td><td>&nbsp;</td></tr>
				<tr id="ilp_pa10"><td></td><td></td><td></td><td>&nbsp;</td></tr>
				<tr id="ilp_pa11"><td></td><td></td><td></td><td>&nbsp;</td></tr>
				<tr id="ilp_pa12"><td></td><td></td><td></td><td>&nbsp;</td></tr>
				<tr id="ilp_pa13"><td></td><td></td><td></td><td>&nbsp;</td></tr>
				<tr id="ilp_pa14"><td></td><td></td><td></td><td>&nbsp;</td></tr>
				<tr id="ilp_pa15"><td></td><td></td><td></td><td>&nbsp;</td></tr>
				<tr class="disabledStyle">
					<td>
						<table class="table table-bordered">
							<tr><th>Exemption Evidence Seen?</th><th>Yes</th><th>No</th></tr>
							<tr><th>Copy Received?</th><th>Yes</th><th>No</th></tr>
						</table>
					</td>
					<td colspan="3">Admin use only</td>
				</tr>
			</table>
		</div>
	</div>
</div>

<hr>

<div class="small">
	<div class="row">
		<div class="col-lg-12 col-md-12 col-sm-12">
			<table class="table table-responsive table-bordered ilp disabledStyle">
				<tr><th colspan="4" style="background-color: #e0ffff;">Section 2: Initial / Diagnostic Assessment Summary: </th></tr>
				<tr><th>Assessment Method Used</th><th>Date of Assessment</th><th>Results</th><th>Recommendations / Areas to work on / Support requires</th></tr>
				<tr><td></td><td></td><td></td><td>&nbsp;</td></tr>
				<tr><td></td><td></td><td></td><td>&nbsp;</td></tr>
				<tr><td></td><td></td><td></td><td>&nbsp;</td></tr>
				<tr><td></td><td></td><td></td><td>&nbsp;</td></tr>
				<tr class="text-center">
					<th>ALN (Additional Learning Needs)</th>
					<th>Yes</th>
					<th>No</th>
					<td>
						<table class="table table-bordered">
							<tr><th>Identified area of need</th><th>Plan of Support</th></tr>
							<tr><td></td><td></td></tr>
						</table>
					</td>
				</tr>
			</table>
		</div>
	</div>
</div>

<div class="small">
	<div class="row">
		<div class="col-lg-12 col-md-12 col-sm-12">
			<table class="table table-responsive table-bordered ilp">
				<tr><th style="background-color: #e0ffff;">2a: Personal, Carer & Progression Objectives: </th></tr>
				<tr><td>Include any prior work experience.  Complete and record the employment objectives of the learner and any further career / progression aspirations, including full / part time education following the term of the programme.</td> </tr>
				<tr><th>Any prior work experience completed?</th></tr>
				<tr><td>&nbsp;</td></tr>
				<tr><th>Employement and Career Progression Objectives</th></tr>
				<tr><td>&nbsp;</td></tr>
				<tr><th style="background-color: #e0ffff;">2b: Induction: </th></tr>
				<tr><th>Outline details of Induction training, including any specific outcomes:</th></tr>
				<tr>
					<td>
						All learners will participate in an induction which covers the content below as a minimum requirement:-
						<ul style="margin-left: 25px;">
							<li>Business overview and introductions</li>
							<li>Programme content and delivery (Essential Programme Components etc)</li>
							<li>Reviews: The importance and frequency</li>
							<li>Equal Opportunities & Inclusion Policy ( why and how we will be covering these through reviews)</li>
							<li>Health and Safety / Zero Harm</li>
							<li>Disciplinary and Grievance Procedures</li>
							<li>Terms and Conditions of the Learning Agreement</li>
							<li>Safeguarding & Prevent</li>
							<li>Appeals against assessment procedure</li>
							<li>Data Protection</li>
							<li>IT Usage and Communication Policy</li>
							<li>Expected Behaviours and Responsibilities</li>
						</ul>
					</td>
				</tr>
				<tr>
					<td>
						<table class="table table-bordered">
							<tr><th>Signatures</th><th>Year 1</th><th>Date</th><th>Year 2</th><th>Date</th></tr>
							<tr><th>Learner</th><th><input class="clsICheck" type="checkbox" name="ilp_signature" value="" /><label>Click to agree</label></th><th><?php echo date('d/m/Y'); ?></th><th class="disabledStyle">Year 2</th><th class="disabledStyle">Date</th></tr>
							<tr class="disabledStyle"><th>Business Representative</th><th></th><th></th><th></th><th></th></tr>
						</table>
					</td>
				</tr>
			</table>
		</div>
	</div>
</div>

<hr>

<div class="small">
	<div class="row">
		<div class="col-lg-12 col-md-12 col-sm-12">
			<table class="table table-responsive table-bordered ilp">
				<tr><th colspan="8" style="background-color: #e0ffff;">Section 3: Framework / Standard</th></tr>
				<tr><th>Title of Outcome</th><th>Awarding Organisation</th><th>Level</th><th>Qualification ref Number</th><th>Qualification Start Date</th><th>Planned Completion Date</th><th>Registration Number</th><th>Actual date of Completion</th></tr>
				<?php if($ob_learner->l2_found_competence != '' && isset($l2_found_competence->title)) { ?>
				<tr>
					<td><?php echo $l2_found_competence->title; ?></td>
					<td><?php echo isset($l2_found_competence->awarding_body)?$l2_found_competence->awarding_body:''; ?></td>
					<td><?php echo isset($l2_found_competence->level)?$l2_found_competence->level:''; ?></td>
					<td><?php echo isset($l2_found_competence->id)?$l2_found_competence->id:''; ?></td>
					<td><?php echo isset($l2_found_competence->start_date)?Date::toShort($l2_found_competence->start_date):''; ?></td>
					<td><?php echo isset($l2_found_competence->end_date)?Date::toShort($l2_found_competence->end_date):''; ?></td>
					<td><?php echo isset($l2_found_competence->awarding_body_reg)?$l2_found_competence->awarding_body_reg:''; ?></td>
					<td></td>
				</tr>
				<?php } else { ?>
				<tr><td>Level 2 Foundational Competence</td><td></td><td></td><td></td><td></td><td></td><td></td><td></td></tr>
				<?php } ?>
				<?php if($ob_learner->main_aim != '' && isset($main_aim->title)) { ?>
				<tr>
					<td><?php echo $main_aim->title; ?></td>
					<td><?php echo isset($main_aim->awarding_body)?$main_aim->awarding_body:''; ?></td>
					<td><?php echo isset($main_aim->level)?$main_aim->level:''; ?></td>
					<td><?php echo isset($main_aim->id)?$main_aim->id:''; ?></td>
					<td><?php echo isset($main_aim->start_date)?Date::toShort($main_aim->start_date):''; ?></td>
					<td><?php echo isset($main_aim->end_date)?Date::toShort($main_aim->end_date):''; ?></td>
					<td><?php echo isset($main_aim->awarding_body_reg)?$main_aim->awarding_body_reg:''; ?></td>
					<td></td>
				</tr>
				<?php } else { ?>
				<tr><td>Main Aim (NVQ) / Development Competence Qualification</td><td></td><td></td><td></td><td></td><td></td><td></td><td></td></tr>
				<?php } ?>
				<?php if($ob_learner->tech_cert != '' && isset($tech_cert->title)) { ?>
				<tr>
					<td><?php echo $tech_cert->title; ?></td>
					<td><?php echo isset($tech_cert->awarding_body)?$tech_cert->awarding_body:''; ?></td>
					<td><?php echo isset($tech_cert->level)?$tech_cert->level:''; ?></td>
					<td><?php echo isset($tech_cert->id)?$tech_cert->id:''; ?></td>
					<td><?php echo isset($tech_cert->start_date)?Date::toShort($tech_cert->start_date):''; ?></td>
					<td><?php echo isset($tech_cert->end_date)?Date::toShort($tech_cert->end_date):''; ?></td>
					<td><?php echo isset($tech_cert->awarding_body_reg)?$tech_cert->awarding_body_reg:''; ?></td>
					<td></td>
				</tr>
				<?php } else { ?>
				<tr><td>Technical Certificate</td><td></td><td></td><td></td><td></td><td></td><td></td><td></td></tr>
				<?php } ?>
				<tr><td>Gateway Assessment</td><td></td><td></td><td></td><td></td><td></td><td></td><td></td></tr>
				<?php if($ob_learner->ERR == '1' && isset($err->title)) { ?>
				<tr>
					<td><?php echo $err->title; ?></td>
					<td><?php echo isset($err->awarding_body)?$err->awarding_body:''; ?></td>
					<td><?php echo isset($err->level)?$err->level:''; ?></td>
					<td><?php echo isset($err->id)?$err->id:''; ?></td>
					<td><?php echo isset($err->start_date)?Date::toShort($err->start_date):''; ?></td>
					<td><?php echo isset($err->end_date)?Date::toShort($err->end_date):''; ?></td>
					<td><?php echo isset($err->awarding_body_reg)?$err->awarding_body_reg:''; ?></td>
					<td></td>
				</tr>
				<?php } else { ?>
				<tr><td>ERR</td><td></td><td></td><td></td><td></td><td></td><td></td><td></td></tr>
				<?php } ?>
				<?php if($ob_learner->PLTS == '1' && isset($plts->title)) { ?>
				<tr>
					<td><?php echo $plts->title; ?></td>
					<td><?php echo isset($plts->awarding_body)?$plts->awarding_body:''; ?></td>
					<td><?php echo isset($plts->level)?$plts->level:''; ?></td>
					<td><?php echo isset($plts->id)?$plts->id:''; ?></td>
					<td><?php echo isset($plts->start_date)?Date::toShort($plts->start_date):''; ?></td>
					<td><?php echo isset($plts->end_date)?Date::toShort($plts->end_date):''; ?></td>
					<td><?php echo isset($plts->awarding_body_reg)?$plts->awarding_body_reg:''; ?></td>
					<td></td>
				</tr>
				<?php } else { ?>
				<tr><td>PLTS</td><td></td><td></td><td></td><td></td><td></td><td></td><td></td></tr>
				<?php } ?>
				<tr><td>End Point Assessment</td><td></td><td></td><td></td><td></td><td></td><td></td><td></td></tr>
				<tr><th colspan="8" style="background-color: #e0ffff;">Additional Component Qualifications</th></tr>
				<tr><td>&nbsp;</td><td></td><td></td><td></td><td></td><td></td><td></td><td></td></tr>
				<tr><th colspan="8" style="background-color: #e0ffff;">Functional Skills</th></tr>
				<?php if($ob_learner->fs_maths != '' && isset($fs_maths->title)) { ?>
				<tr>
					<td>Functional Skills Maths</td>
					<td><?php echo isset($fs_maths->awarding_body)?$fs_maths->awarding_body:''; ?></td>
					<td><?php echo isset($fs_maths->level)?$fs_maths->level:''; ?></td>
					<td><?php echo isset($fs_maths->id)?$fs_maths->id:''; ?></td>
					<td><?php echo isset($fs_maths->start_date)?Date::toShort($fs_maths->start_date):''; ?></td>
					<td><?php echo isset($fs_maths->end_date)?Date::toShort($fs_maths->end_date):''; ?></td>
					<td><?php echo isset($fs_maths->awarding_body_reg)?$fs_maths->awarding_body_reg:''; ?></td>
					<td></td>
				</tr>
				<?php } else { ?>
				<tr><td>Functional Skills Maths</td><td></td><td></td><td></td><td></td><td></td><td></td><td></td></tr>
				<?php } ?>
				<?php if($ob_learner->fs_eng != '' && isset($fs_eng->title)) { ?>
				<tr>
					<td>Functional Skills English</td>
					<td><?php echo isset($fs_eng->awarding_body)?$fs_eng->awarding_body:''; ?></td>
					<td><?php echo isset($fs_eng->level)?$fs_eng->level:''; ?></td>
					<td><?php echo isset($fs_eng->id)?$fs_eng->id:''; ?></td>
					<td><?php echo isset($fs_eng->start_date)?Date::toShort($fs_eng->start_date):''; ?></td>
					<td><?php echo isset($fs_eng->end_date)?Date::toShort($fs_eng->end_date):''; ?></td>
					<td><?php echo isset($fs_eng->awarding_body_reg)?$fs_eng->awarding_body_reg:''; ?></td>
					<td></td>
				</tr>
				<?php } else { ?>
				<tr><td>Functional Skills English</td><td></td><td></td><td></td><td></td><td></td><td></td><td></td></tr>
				<?php } ?>
				<?php if($ob_learner->fs_ict == '1' && isset($fs_ict->title)) { ?>
				<tr>
					<td>Functional Skills ICT</td>
					<td><?php echo isset($fs_ict->awarding_body)?$fs_ict->awarding_body:''; ?></td>
					<td><?php echo isset($fs_ict->level)?$fs_ict->level:''; ?></td>
					<td><?php echo isset($fs_ict->id)?$fs_ict->id:''; ?></td>
					<td><?php echo isset($fs_ict->start_date)?Date::toShort($fs_ict->start_date):''; ?></td>
					<td><?php echo isset($fs_ict->end_date)?Date::toShort($fs_ict->end_date):''; ?></td>
					<td><?php echo isset($fs_ict->awarding_body_reg)?$fs_ict->awarding_body_reg:''; ?></td>
					<td></td>
				</tr>
				<?php } else { ?>
				<tr><td>Functional Skills ICT</td><td></td><td></td><td></td><td></td><td></td><td></td><td></td></tr>
				<?php } ?>
			</table>
		</div>
	</div>
</div>

<hr>

<div class="small disabledStyle">
	<div class="row">
		<div class="col-lg-12 col-md-12 col-sm-12">
			<table class="table table-responsive table-bordered ilp">
				<tr><th colspan="6" style="background-color: #e0ffff;">Section 4: Training Delivery</th></tr>
				<tr><th colspan="6">4a: Technical Training (On/Off Job)</th></tr>
				<tr><th>Unit Number</th><th>Mandatory / Optional</th><th>Awarding Organisation</th><th>Title of Unit</th><th>GLH</th><th>Completion Date</th></tr>
				<tr><td>&nbsp;</td><td></td><td></td><td></td><td></td><td></td></tr>
				<tr><th colspan="6">4b: NVQ / Competence Training</th></tr>
				<tr><th>Unit Number</th><th>Mandatory / Optional</th><th>Awarding Organisation</th><th>Title of Unit</th><th>GLH</th><th>Completion Date</th></tr>
				<tr><td>&nbsp;</td><td></td><td></td><td></td><td></td><td></td></tr>
			</table>
		</div>
	</div>
</div>

<hr>

<div class="small disabledStyle">
	<div class="row">
		<div class="col-lg-12 col-md-12 col-sm-12">
			<table class="table table-responsive table-bordered ilp">
				<tr><th colspan="3" style="background-color: #e0ffff;">Section 5: Progress Review</th></tr>
				<tr><th colspan="3">Formal Review Dates: (Discuss essential framework progression and career progression opportunities)</th></tr>
				<tr><th>Proposed Review Date</th><th>Actual Review Date</th><th>Summary</th></tr>
				<tr><td>&nbsp;</td><td></td><td></td></tr>
			</table>
		</div>
	</div>
</div>

<hr>

<div class="small disabledStyle">
	<div class="row">
		<div class="col-lg-12 col-md-12 col-sm-12">
			<table class="table table-responsive table-bordered ilp">
				<tr><th style="background-color: #e0ffff;">Section 6: Completion</th></tr>
				<tr><th>Current job role?</th></tr>
				<tr><td>&nbsp;</td></tr>
				<tr><th>Evaluate your current job role against your orignial career objectives</th></tr>
				<tr><td>&nbsp;</td></tr>
				<tr><th>Learner Destination and Progression</th></tr>
				<tr><td>&nbsp;</td></tr>
			</table>
		</div>
	</div>
</div>
</step>

<h3>Apprenticeship Agreement</h3>
<step>

	<div class="nts-secondary-teaser-gradient" style="padding: 5px; border-radius: 5px;">
		<h4>Apprenticeship Agreement</h4>
	</div>

	<div class="text-center"><img src="/images/logos/app_logo.jpg" alt="Apprenticeship" /></div>

	<div class="well">
		<p>Further to the Apprenticeships (Form of Apprenticeship Agreement) Regulations which came into force on 6th April 2012, an Apprenticeship Agreement is required at the commencement of an Apprenticeship for all new apprentices who start on or after that date.</p>
		<p>The purpose of the Apprenticeship Agreement is to:-</p>
		<ul style="margin-left: 25px;">
			<li>the skill, trade or occupation for which the apprentice is being trained;</li>
			<li>the apprenticeship standard or framework connected to the apprenticeship;</li>
			<li>the dates during which the apprenticeship is expected to take place; and</li>
			<li>the amount of off the job training that the apprentice is to receive.</li>
		</ul>
		<p></p>
		<p>The Apprenticeship Agreement is incorporated into and does not replace the written statement of particulars issued to the individual in accordance with the requirements of the Employment Rights Act 1996.</p>
		<p>The Apprenticeship is to be treated as being a contract of service not a contract of Apprenticeship.</p>
	</div>

	<h4><strong>Apprenticeship Particulars</strong></h4>
	<span style="margin-top: 2px;" class="btn btn-info btn-sm" onclick="window.open('app_agreement_guidance.pdf', '_blank')"><i class="fa fa-info-circle"></i> Guidance Notes</span>
	<table class="table row-border">
		<?php $f_t = DAO::getSingleValue($link, "SELECT title FROM student_frameworks WHERE tr_id = '{$learner->id}'");?>
		<?php $f_id = DAO::getSingleValue($link, "SELECT id FROM student_frameworks WHERE tr_id = '{$learner->id}'");?>
		<?php $is_standard = DAO::getSingleValue($link, "SELECT StandardCode FROM frameworks WHERE id = '{$f_id}'");?>
		<tr><th>Apprentice name:</th><td><?php echo $learner->firstnames . ' ' . $learner->surname; ?></td></tr>
		<tr>
			<th>Skill, trade or occupation for which the apprentice is being trained:</th>
			<td><textarea name="skills_trade_occ" id="skills_trade_occ" rows="5" cols="50"><?php echo $ob_learner->job_title != ''?DAO::getSingleValue($link, "SELECT description FROM lookup_job_titles WHERE id = '{$ob_learner->job_title}'"):''; ?></textarea></td>
		</tr>
		<tr><th>Relevant Apprenticeship framework and level:</th><td><?php echo $f_t; ?></td></tr>
		<tr><th>Place of work (employer):</th><td><?php echo DAO::getSingleValue($link, "SELECT legal_name FROM organisations WHERE id = '{$learner->employer_id}'"); ?></td></tr>
		<tr>
			<td colspan="2">
				<table class="table table-bordered">
					<tr>
						<th>Start date of apprenticeship<br>(see note 3):</th><td><?php echo Date::toShort($learner->start_date); ?></td>
						<th>End date of apprenticeship<br>(see note 3):</th><td><?php echo Date::toShort($learner->target_date); ?></td>
					</tr>
					<?php
					if($is_standard != '')
					{
					?>
					<tr>
						<th>Start date of practical period<br>(see note 4):</th><td><?php echo Date::toShort($learner->start_date); ?></td>
						<th>Estimated end date of practical period<br>(see note 4):</th><td><?php echo Date::toShort($ob_learner->target_date_practical_period); ?></td>
					</tr>
					<tr>
						<th>Duration of practical period<br>(see note 4):</th>
						<td>
							<?php
							//$_diff = Date::dateDiffInfo($learner->start_date, $ob_learner->target_date_practical_period);
							//$month_diff = isset($_diff['month'])?$_diff['month']:'';
							//echo $month_diff . ' month(s)';
							$_diff = DAO::getSingleValue($link, "SELECT TIMESTAMPDIFF(MONTH, '$learner->start_date', '$ob_learner->target_date_practical_period');");
							if(is_null($_diff))
								echo '';
							else
								echo $_diff . ' month(s)';
							?>
						</td>
						<th>Planned amount of off-the-job training (hours)<br>(see notes 9 and 10):</th><td><?php echo $ob_learner->planned_otj_hours; ?></td>
					</tr>
					<?php } ?>
				</table>
			</td>
		</tr>
		<!--<tr><th>Start date:</th><td><?php /*echo Date::toShort($learner->start_date); */?></td></tr>-->
		<!--<tr><th>Estimated completion of learning date:</th><td><?php /*echo Date::toShort($learner->target_date); */?></td></tr>-->
		<!--<tr><th>Location:</th><td><?php /*echo $employer_main_site->address_line_1 . ' ' . $employer_main_site->address_line_2 . ' ' . $employer_main_site->address_line_3 . ' ' . $employer_main_site->address_line_4; */?></td></tr>-->
		<tr><th colspan="2"><br></th></tr>
		<tr><th><input class="clsICheck" type="checkbox" name="app_agreement" value="" /><label>Click to agree</label></th><td class="text-bold"><?php echo date('d/m/Y'); ?></td></tr>
	</table>
</step>

<h3>ILR Confirmation and Signature</h3>
<step>

	<div class="nts-secondary-teaser-gradient" style="padding: 5px; border-radius: 5px;">
		<h4>ILR Confirmation and Signature</h4>
	</div>

	<br>
	<div class="row">
		<div class="col-lg-12 col-md-12 col-sm-12">
			<label>I certify that the information contained on this form is correct</label>
		</div>
	</div>

	<hr>

	<div class="row">
		<div class="col-sm-8">
			<span class="btn btn-info" onclick="getSignature();">
				<?php if(is_null($ob_learner->learner_signature)) {?>
				<img id="img_learner_signature" src="do.php?_action=generate_image&title=Click here to sign&font=Signature_Regular.ttf&size=20" style="border: 2px solid;border-radius: 15px; width: 100%;" />
				<?php } else {?>
				<img id="img_learner_signature" src="do.php?_action=generate_image&title=<?php echo $ob_learner->learner_signature; ?>&size=20" style="border: 2px solid;border-radius: 15px; width: 100%;" />
				<?php } ?>
				<input type="hidden" name="learner_signature" id="learner_signature" value="" />
			</span>
		</div>
		<div class="col-sm-4">
			<h2 class="content-max-width"><?php echo date('d/m/Y'); ?></h2>
		</div>
	</div>

	<hr>

	<div id="panel_signature" title="Signature Panel">
		<div class="callout callout-info"><i class="fa fa-info-circle"></i> Enter your name/initials, then select the signature font you like and press "Add". </div>
		<div>
			<table class="table row-border">
				<tr><td>Enter your name/initials</td><td><input maxlength="23" type="text" id="signature_text" onkeyup="refreshSignature();" onkeypress="return onlyAlphabets(event,this);" /></td></tr>
				<tr>
					<td onclick="SignatureSelected(this);" class="sigbox"><img id="img1" src=""  /></td>
					<td onclick="SignatureSelected(this);" class="sigbox"><img id="img2" src=""  /></td>
				</tr>
				<tr>
					<td onclick="SignatureSelected(this);" class="sigbox"><img id="img3" src=""  /></td>
					<td onclick="SignatureSelected(this);" class="sigbox"><img id="img4" src=""  /></td>
				</tr>
				<tr>
					<td onclick="SignatureSelected(this);" class="sigbox"><img id="img5" src=""  /></td>
					<td onclick="SignatureSelected(this);" class="sigbox"><img id="img6" src=""  /></td>
				</tr>
				<tr>
					<td onclick="SignatureSelected(this);" class="sigbox"><img id="img7" src=""  /></td>
					<td onclick="SignatureSelected(this);" class="sigbox"><img id="img8" src=""  /></td>
				</tr>
			</table>
		</div>
	</div>

	<div class="callout callout-info"><span class="fa fa-info-circle"></span> Please bring copies / or originals of your passport, Driving License, Immigration permission, relevant Visa, e.g. Tier 1, Evidence you are a care leaver or have an EHC plan, etc. to your on-boarding induction day if you have had trouble uploading them today.</div>
</step>

</form>
</div>

</content>



<footer class="main-footer">
	<div class="pull-left">
		<table class="table" style="border-collapse: separate; border-spacing: 0.5em;">
			<tr>
				<td><img width="230px" src="images/logos/siemens/ESFA.png" /></td>
				<td><img src="images/logos/siemens/ESF.png" /></td>
				<td><img src="images/logos/siemens/ofsted.jpg" /></td>
				<td><img src="images/logos/siemens/top70.png" width="200px" height="99px" /></td>
				<!--<td><img src="images/logos/siemens/top100.jpg" width="100px" height="165px" /></td>-->
			</tr>
		</table>
	</div>
	<div class="pull-right">
		<img src="images/logos/SUNlogo.png" />
	</div>
</footer>

<script type="text/javascript">
	var phpHeaderLogo1 = '<?php echo $header_image1; ?>';
	var phpHeaderLogo2 = '<?php echo $header_image2; ?>';
</script>

<script src="/assets/adminlte/plugins/jQuery/jquery-2.2.3.min.js"></script>
<script src="/assets/adminlte/plugins/JQuerySteps/jquery.steps.js"></script>
<script src="https://gitcdn.github.io/bootstrap-toggle/2.2.2/js/bootstrap-toggle.min.js"></script>
<script src="/assets/adminlte/plugins/jQueryUI/jquery-ui.min.js"></script>
<script src="/assets/adminlte/plugins/iCheck/icheck.min.js"></script>
<script src="/assets/adminlte/bootstrap/js/bootstrap.min.js"></script>
<script src="/assets/adminlte/plugins/bootstrap-validator/bootstrapValidator.js"></script>
<script src="/module_onboarding/js/onboarding.js?n=<?php echo time(); ?>"></script>

</body>
</html>
