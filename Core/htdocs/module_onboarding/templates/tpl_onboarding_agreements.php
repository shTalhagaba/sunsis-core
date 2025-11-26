<?php /* @var $learner TrainingRecord */ ?>
<?php /* @var $employer_main_site Location */ ?>
<?php /* @var $college_main_site Location */ ?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/html" xmlns="http://www.w3.org/1999/html" xmlns="http://www.w3.org/1999/html"
      xmlns="http://www.w3.org/1999/html" xmlns="http://www.w3.org/1999/html">
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<title>Sunesis | Onboarding</title>
	<meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
	<link rel="stylesheet" href="/assets/adminlte/bootstrap/css/bootstrap.min.css">
	<link rel="stylesheet" href="/assets/adminlte/dist/css/AdminLTE.min.css">
	<link href="/assets/adminlte/plugins/JQuerySteps/jquery.steps.css?n=<?php echo time(); ?>" rel="stylesheet">
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
	<div class="jumbotron">
		<div class="container">
			<div class="nts-secondary-teaser-gradient" style="max-width: 450px; padding: 25px; border-radius: 25px;">
				<h2>Apprenticeship Programme<br>Update your Details</h2>
			</div>
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
<input type="hidden" name="_action" value="save_onboarding_agreements" />
<input type="hidden" name="is_finished" value="" />
<input type="hidden" name="id" value="<?php echo $ob_learner->id; ?>" />
<input type="hidden" name="username" value="<?php echo $learner->username; ?>" />
<input type="hidden" name="tr_id" value="<?php echo $learner->id; ?>" />

<h3>Welcome Back</h3>
<step>
	<div class="nts-secondary-teaser-gradient" style="padding: 5px; border-radius: 5px;">
		<h4>Welcome back, <?php echo $ob_learner->firstnames; ?></h4>
	</div>
	<div class="well">
		<p>Minor changes have been made to your Apprenticeship Programme including Start and Planned End Dates. Therefore, please review these documents.</p>
	</div>
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
				<tr><th>Title:</th><td id="ilp_learner_title"><?php echo $ob_learner->learner_title; ?></td></tr>
				<tr><th>Learner First Name(s):</th><td id="ilp_firstnames"><?php echo $ob_learner->firstnames; ?></td></tr>
				<tr><th>Learner Surname:</th><td id="ilp_surname"><?php echo $ob_learner->surname; ?></td></tr>
				<tr><th>Date of Birth:</th><td id="ilp_input_dob"><?php echo Date::toShort($ob_learner->dob); ?></td></tr>
				<tr><th>Address:</th><td id="ilp_address"><?php echo $ob_learner->home_address_line_1 . ' ' . $ob_learner->home_address_line_2 . ' ' . $ob_learner->home_address_line_3 . ' ' . $ob_learner->home_address_line_4; ?></td></tr>
				<tr><th>Postcode:</th><td id="ilp_home_postcode"><?php echo $ob_learner->home_postcode; ?></td></tr>
				<tr><th>Email:</th><td id="ilp_home_email"><?php echo $ob_learner->home_email; ?></td></tr>
				<tr><th>Telephone:</th><td id="ilp_home_telephone"><?php echo $ob_learner->home_telephone; ?></td></tr>
				<tr><th>Mobile:</th><td id="ilp_home_mobile"><?php echo $ob_learner->home_mobile; ?></td></tr>
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
				<tr><th>Business Code:</th><td><?php echo DAO::getSingleValue($link, "SELECT title FROM brands INNER JOIN employer_business_codes ON brands.id = employer_business_codes.`brands_id` WHERE employer_business_codes.`employer_id` = '{$learner->employer_id}';"); ?></td></tr>
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
		<div class="col-lg-6 col-md-6 col-sm-12">
			<table class="table table-responsive table-bordered ilp">
				<tr><th colspan="2" style="background-color: #e0ffff;">Emergency Contact Details</th></tr>
				<tr><th>Title:</th><td id="ilp_em_con_title"><?php echo $ob_learner->em_con_title; ?></td></tr>
				<tr><th>Name:</th><td id="ilp_em_con_name"><?php echo $ob_learner->em_con_name; ?></td></tr>
				<tr><th>Relationship to Learner:</th><td id="ilp_em_con_rel"><?php echo $ob_learner->em_con_rel; ?></td></tr>
				<tr><th>Home Number:</th><td id="ilp_em_con_tel"><?php echo $ob_learner->em_con_tel; ?></td></tr>
				<tr><th>Mobile Number:</th><td id="ilp_em_con_mob"><?php echo $ob_learner->em_con_mob; ?></td></tr>
			</table>
		</div>
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
				<?php
				$ob_eng = DAO::getObject($link, "SELECT * FROM ob_learners_pa WHERE ob_learner_id = '{$ob_learner->id}' AND level = '101'");
				echo '<tr>';
				echo '<td>GCSE - English Language</td>';
				echo '<td>' . Date::toShort($ob_eng->date_completed) . '</td>';
				echo isset($ob_eng->a_grade)?'<td>' . $ob_eng->a_grade . '</td>':'<td>' . $ob_eng->p_grade . '</td>';
				echo '<td></td>';
				echo '</tr>';
				?>
				<?php
				$ob_maths = DAO::getObject($link, "SELECT * FROM ob_learners_pa WHERE ob_learner_id = '{$ob_learner->id}' AND level = '102'");
				echo '<tr>';
				echo '<td>GCSE - Maths</td>';
				echo '<td>' . Date::toShort($ob_maths->date_completed) . '</td>';
				echo isset($ob_maths->a_grade)?'<td>' . $ob_maths->a_grade . '</td>':'<td>' . $ob_maths->p_grade . '</td>';
				echo '<td></td>';
				echo '</tr>';
				?>
				<?php
				$records = DAO::getResultset($link, "SELECT * FROM ob_learners_pa WHERE ob_learner_id = '{$ob_learner->id}' AND level NOT IN ('101', '102') AND subject != 'h'", DAO::FETCH_ASSOC);
				foreach($records AS $row)
				{
					echo '<tr>';
					echo '<td>' . $row['subject'] . '</td>';
					echo '<td>' . Date::toShort($row['date_completed']) . '</td>';
					echo isset($row['a_grade'])?'<td>' . $row['a_grade'] . '</td>':'<td>' . $row['p_grade'] . '</td>';
					echo '<td></td>';
					echo '</tr>';
				}
				?>
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
			<li>identify the skill, trade or occupation for which the apprentice is being trained; and</li>
			<li>confirm the qualifying Apprenticeship framework that the apprentice is following.</li>
		</ul>
		<p></p>
		<p>The Apprenticeship Agreement is incorporated into and does not replace the written statement of particulars issued to the individual in accordance with the requirements of the Employment Rights Act 1996.</p>
		<p>The Apprenticeship is to be treated as being a contract of service not a contract of Apprenticeship.</p>
	</div>

	<h4><strong>Apprenticeship Particulars</strong></h4>
	<table class="table row-border">
		<?php $f_t = DAO::getSingleValue($link, "SELECT title FROM student_frameworks WHERE tr_id = '{$learner->id}'");?>
		<tr><th>Apprentice name:</th><td><?php echo $learner->firstnames . ' ' . $learner->surname; ?></td></tr>
		<tr>
			<th>Skill, trade or occupation for which the apprentice is being trained:</th>
			<td><textarea name="skills_trade_occ" id="skills_trade_occ" rows="5" cols="50"><?php echo $ob_learner->job_title != ''?DAO::getSingleValue($link, "SELECT description FROM lookup_job_titles WHERE id = '{$ob_learner->job_title}'"):''; ?></textarea></td>
		</tr>
		<tr><th>Relevant Apprenticeship framework and level:</th><td><?php echo $f_t; ?></td></tr>
		<tr><th>Start date:</th><td><?php echo Date::toShort($learner->start_date); ?></td></tr>
		<tr><th>Estimated completion of learning date:</th><td><?php echo Date::toShort($learner->target_date); ?></td></tr>
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
				<img id="img_learner_signature" src="do.php?_action=generate_image&title=Click here to sign&font=Signature_Regular.ttf&size=25" style="border: 2px solid;border-radius: 15px;" />
				<?php } else {?>
				<img id="img_learner_signature" src="do.php?_action=generate_image&<?php echo $ob_learner->learner_signature; ?>&size=25" style="border: 2px solid;border-radius: 15px;" />
				<?php } ?>
				<input type="hidden" name="learner_signature" id="learner_signature" value="<?php echo !is_null($ob_learner->learner_signature)?'do.php?_action=generate_image&'.$ob_learner->learner_signature:''; ?>" />
			</span>
			<!--<p><i class="text-muted">click on your signature if you want to change</i></p>-->
		</div>
		<div class="col-sm-4">
			<h2 class="content-max-width"><?php echo date('d/m/Y'); ?></h2>
		</div>
	</div>

	<hr>

	<!--<div id="panel_signature" title="Signature Panel">
		<div class="callout callout-info"><i class="fa fa-info-circle"></i> Enter your name/initials, then select the signature font you like and press "Add". </div>
		<div>
			<table class="table row-border">
				<tr><td>Enter your name/initials</td><td><input type="text" id="signature_text" onkeyup="refreshSignature();" onkeypress="return onlyAlphabets(event,this);" /></td></tr>
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
	</div>-->
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
				<td><img src="images/logos/siemens/top100.jpg" width="100px" height="165px" /></td>
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
<script src="/module_onboarding/js/onboarding_agreements.js?n=<?php echo time(); ?>"></script>

</body>
</html>
