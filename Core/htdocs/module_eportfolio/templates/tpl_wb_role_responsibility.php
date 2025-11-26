<?php /* @var $wb WBRoleResponsibility */ ?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/html" xmlns="http://www.w3.org/1999/html" xmlns="http://www.w3.org/1999/html"
      xmlns="http://www.w3.org/1999/html">
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<title>Your role and responsibility & Personal organisation</title>
	<meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
	<link rel="stylesheet" href="/assets/adminlte/bootstrap/css/bootstrap.min.css">
	<link rel="stylesheet" href="/assets/adminlte/dist/css/AdminLTE.min.css">
	<link href="module_eportfolio/assets/jquery.steps.css" rel="stylesheet">
	<link rel="stylesheet" href="/assets/adminlte/plugins/jQueryUI/jquery-ui.css">
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.5.0/css/font-awesome.min.css">
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/ionicons/2.0.1/css/ionicons.min.css">
	<link rel="stylesheet" href="/assets/adminlte/dist/css/skins/_all-skins.min.css">
	<link href="/assets/adminlte/plugins/pace/pace.css" rel="stylesheet">
	<link href="https://gitcdn.github.io/bootstrap-toggle/2.2.2/css/bootstrap-toggle.min.css" rel="stylesheet">
	<link rel="stylesheet" href="/assets/adminlte/plugins/iCheck/all.css">
	<link href="/assets/adminlte/plugins/toastr/toastr.min.css" rel="stylesheet">

	<!--[if lt IE 9]>
	<script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
	<script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
	<![endif]-->

	<style>
		html,
		body {
			padding-top: 30px;
			height: 100%;
		}

		.step-content {
			border:2px groove blue;
			min-height: 640px;
			border-radius: 10px;
		}

		.topLine {
			position: absolute; top: 0;
			margin-bottom: 5px;
		}

		.bottomLine {
			position: absolute; bottom: 0;
		}

		h2 {
			color: #C71585;
		}

		.navbar-fixed-top {
			min-height: 50px;
			max-height: 50px;
			background: #ffffff url("module_eportfolio/assets/images/pp.png") center center;
		}

		@media (min-width: 768px) {
			.navbar-custom {
				/*padding: 5px 0;*/
				-webkit-transition: padding 0.3s;
				-moz-transition: padding 0.3s;
				transition: padding 0.3s;
			}
			.navbar-custom.affix {
				padding: 0;
			}
		}

		textarea {
			border:1px solid #3366FF;
			border-radius: 5px;
			border-left: 5px solid #3366FF;
		}

		.sigbox {
			border-radius: 15px;
			border: 1px solid #EEE;
			cursor: pointer;
		}
		.sigboxselected {
			border-radius: 25px;
			border: 2px solid #EEE;
			cursor: pointer;
			background-color: #d3d3d3;
		}

		.ui-dialog-titlebar-close {
			visibility: hidden;
		}

		textarea:disabled {
			opacity:1;
		}
	</style>
	<style>
		.text-3d{
			font-size: 40px;
			color: #94942C;
			font-family: Arial Black, Gadget, sans-serif;
			text-shadow: 0px 0px 0 rgb(129,129,25),-1px 1px 0 rgb(121,121,17),-2px 2px 0 rgb(112,112,8),-3px 3px 0 rgb(103,103,-1),-4px 4px 0 rgb(95,95,-9),-5px 5px 0 rgb(86,86,-18),-6px 6px 0 rgb(77,77,-27),-7px 7px 0 rgb(68,68,-36),-8px 8px 0 rgb(60,60,-44),-9px 9px 0 rgb(51,51,-53),
			-10px 10px  0 rgb(42,42,-62),-11px 11px 10px rgba(0,0,0,0.6),-11px 11px 1px rgba(0,0,0,0.5),0px 0px 10px rgba(0,0,0,.2)
		}

	</style>
</head>

<body>
<nav id="mainNav" class="navbar navbar-default navbar-fixed-top navbar-custom">
	<div class="container-float">
		<div class="navbar-header page-scroll">
			<a class="navbar-brand" href="#"><img height="35px" class="headerlogo" src="images/logos/<?php echo $wb->getHeaderLogo($link); ?>" /></a>
		</div>
	</div>
	<div class="text-center" style="margin-top: 5px;"><h3><?php echo $tr->firstnames . ' '  . strtoupper($tr->surname); ?></h3></div>
</nav>

<form name="frm_wb_role_responsibility" id="frm_wb_role_responsibility" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
	<input type="hidden" name="_action" value="save_wb_role_responsibility" />
	<input type="hidden" name="id" value="<?php echo $wb->id; ?>" />
	<input type="hidden" name="tr_id" value="<?php echo $wb->tr_id; ?>" />
	<input type="hidden" name="wb_status" id="wb_status" value="" />
	<input type="hidden" name="full_save" id="full_save" value="<?php echo $wb->full_save; ?>" />
	<input type="hidden" name="full_save_feedback" id="full_save_feedback" value="<?php echo $wb->full_save_feedback; ?>" />

	<div class="container-float">
		<div class="wrapper" style="background-color: #ffffff;">

		<header class="main-header"><span style="margin-left: 50%;"><?php echo DAO::getSingleValue($link, "SELECT title FROM student_frameworks WHERE tr_id = '{$wb->tr_id}'"); ?></span></header>

		<div class="content-wrapper" style="background-color: #ffffff;">

		<?php echo $wb->savers_or_sp == 'savers' ? '<section class="content-header"><h1 style="color: #0000ff;">Your role and responsibility <span class="text-red">&</span> Personal organisation</h1></section>' : '<section class="content-header"><h1>Your role and responsibility & Personal organisation</h1></section>' ?>

		<section class="content">

		<div id="wizard">

			<h1>Your role and responsibility & Personal organisation</h1>
			<div class="step-content">
				<?php echo $wb->getPageTopLine(); ?>
				<div style="position: absolute; top: 40%; right: 50%;" class="lead">
					<?php echo $wb->savers_or_sp == 'savers' ? '<h2 style="color: #0000ff;">Your role and responsibility <span class="text-red">&</span> Personal organisation</h2>':'<h2>Your role and responsibility & Personal organisation</h2>'; ?>

					<p class="text-center" >Module 5</p>
				</div>

				<?php echo $wb->getPageBottomLine(); ?>
			</div> <!--.page 1 ends-->

			<h1>Your role and responsibility & Personal organisation</h1>
			<div class="step-content">
				<?php echo $wb->getPageTopLine(); ?>

				<div class="row"><div class="col-sm-12"><div class="pull-right"><?php echo HTML::renderWorkbookIcons('', true); ?></div></div></div>

				<div class="row text-bold">
					<div class="col-sm-12">
						<h1>Your role and responsibility & Personal organisation</h1>
						<p>This module is on your role and responsibility within <?php echo ucfirst($wb->savers_or_sp); ?> as well as your own personal organisation skills.  It is about, understanding what is expected of you at all times and how you plan your time to ensure that agreed tasks are achieved.</p>
						<p>In this module you will look at:</p>
						<ul style="margin-left: 15px; margin-bottom: 15px;">
							<li>Your own role and responsibilities</li>
							<li>Work objectives</li>
							<li>What a SMART objective is</li>
							<li>Benefits of SMART objectives</li>
							<li>Eisenhower’s Urgent / Important Principle</li>
							<li>Tools and techniques to monitor progress and completion of tasks</li>
						</ul>

					</div>
					<div class="col-sm-12">
						<img src="module_eportfolio/assets/images/wb5_pg2_img1.png" />
					</div>
				</div>
				<p><br></p>

				<div class="row"><div class="col-sm-12"><div class="pull-right"><?php echo HTML::renderWorkbookIcons('', true); ?></div></div></div>

				<?php echo $wb->getPageBottomLine(); ?>
			</div> <!--.page 2 ends-->

			<h1>Your role and responsibility & Personal organisation</h1>
			<div class="step-content">
				<?php echo $wb->getPageTopLine(); ?>

				<div class="row"><div class="col-sm-12"><div class="pull-right">
					<?php
					if($feedback->DefinitionsRoleAndResponsibilities->Status->__toString() == 'NA')
						echo HTML::renderWorkbookIcons('DefinitionsRoleAndResponsibilities', false, 'btn-danger');
					else
						echo HTML::renderWorkbookIcons('DefinitionsRoleAndResponsibilities', false, 'btn-success');
					?>
				</div></div></div>

				<div class="row">
					<div class="col-sm-12">
						<h1>Your role and responsibility</h1>
					</div>
				</div>
				<div class="row">
					<div class="col-sm-12">
						<div class="row" style="display: flex;">
							<div class="col-sm-6 text-center" style="flex: 1; padding: 1em; background-color: #90ee90; font-weight: bolder;">
								<h3>Role</h3>
								<p>The function assumed or part played by a person in a particular situation</p>
							</div>
							<div class="col-sm-6 text-center"  style="flex: 1; padding: 1em; background-color: #82CAFF; font-weight: bolder; " >
								<h3>Responsibility</h3>
								<p>A thing which one is required to do as part of a job, role, or legal obligation</p>
							</div>
						</div>
					</div>
					<div class="col-sm-12 text-bold">
						<p>Think about the definitions of role and responsibility and answer the following questions: &nbsp; <img src="module_eportfolio/assets/images/wb4_img1.png" /></p>
					</div>
					<div class="col-sm-12" <?php echo $feedback->DefinitionsRoleAndResponsibilities->Status->__toString() == 'A'?'style="pointer-events:none;"':''; ?>>
						<p class="text-bold"><img src="module_eportfolio/assets/images/wb2_img1.png" /> &nbsp;What is your job role? &nbsp;</p>
						<p><textarea name="JobRole" style="width: 100%;" rows="3"><?php echo $answers->DefinitionsRoleAndResponsibilities->JobRole->__toString(); ?></textarea></p>
						<p class="text-bold"><img src="module_eportfolio/assets/images/wb2_img1.png" /> &nbsp;What are  your responsibilities? &nbsp;</p>
						<p><textarea name="Responsibilities" style="width: 100%;" rows="3"><?php echo $answers->DefinitionsRoleAndResponsibilities->Responsibilities->__toString(); ?></textarea></p>
					</div>
				</div>
				<div class="row">
					<div class="col-sm-1"></div>
					<div class="col-sm-3 text-center text-bold" style="border-radius: 25px; border: 2px solid #73AD21; padding: 20px; ">
						<p>An objective</p>
						<p>A specific result that a person or system aims to achieve within a time frame and with available resources</p>
					</div>
					<div class="col-sm-8">
						<p><strong>Work objectives </strong>are the tasks that you need to achieve at work. Some objectives are tasks which you and your team must do on a daily basis such as stock replenishment, facing up, serving customers at the till or dealing with their enquiries. These work objectives are agreed when you start your job and are included in your job description.</p>
					</div>
				</div>
				<div class="row" <?php echo $feedback->DefinitionsRoleAndResponsibilities->Status->__toString() == 'A'?'style="pointer-events:none;"':''; ?>>
					<div class="col-sm-12">
						<p class="text-bold"><img src="module_eportfolio/assets/images/wb2_img1.png" /> &nbsp;List 3 other daily work objectives below. &nbsp;</p>
						<p><textarea name="DailyWorkObjectives" style="width: 100%;" rows="3"><?php echo $answers->DefinitionsRoleAndResponsibilities->DailyWorkObjectives->__toString(); ?></textarea></p>
						<p class="text-bold"><img src="module_eportfolio/assets/images/wb2_img1.png" /> &nbsp;Other work objectives may be done every few days, weekly, fortnightly or even monthly. List some examples below.&nbsp;</p>
						<p><textarea name="OtherWOrkObjectives" style="width: 100%;" rows="3"><?php echo $answers->DefinitionsRoleAndResponsibilities->OtherWOrkObjectives->__toString(); ?></textarea></p>
						<p class="text-bold"><img src="module_eportfolio/assets/images/wb2_img1.png" /> &nbsp;Objectives relating to business targets will also be set for the team. What are they?&nbsp;</p>
						<p><textarea name="BusinessTargetObjectives" style="width: 100%;" rows="3"><?php echo $answers->DefinitionsRoleAndResponsibilities->BusinessTargetObjectives->__toString(); ?></textarea></p>
					</div>
				</div>

				<?php if($wb->id != '' && $_SESSION['user']->type == User::TYPE_ASSESSOR && ($wb->wb_status == Workbook::STATUS_LEARNER_COMPLETED || $wb->wb_status == Workbook::STATUS_BEING_CHECKED || $wb->wb_status == Workbook::STATUS_IV_REJECTED)) {?>
				<div class="row well">
					<div class="col-sm-12">
						<div class="box box-success box-solid">
							<div class="box-header with-border"><h3 class="box-title">Assessor Feedback</h3></div>
							<div class="box-body assessorFeedback" <?php echo $feedback->DefinitionsRoleAndResponsibilities->Status->__toString() == 'A'?'style="pointer-events:none;"':''; ?>>
								<div class="form-group">
									<label for="status_DefinitionsRoleAndResponsibilities" class="col-sm-12 control-label">Status:</label>
									<div class="col-sm-12">
										<?php
										echo HTML::selectChosen('status_DefinitionsRoleAndResponsibilities', $answer_status, $feedback->DefinitionsRoleAndResponsibilities->Status->__toString() == 'A'?$feedback->DefinitionsRoleAndResponsibilities->Status->__toString():'', false, true); ?>
									</div>
								</div>
								<div class="form-group">
									<label for="comments_DefinitionsRoleAndResponsibilities" class="col-sm-12 control-label">Comments:</label>
									<div class="col-sm-12">
										<textarea name="comments_DefinitionsRoleAndResponsibilities" rows="7" style="width: 100%;"><?php echo $feedback->DefinitionsRoleAndResponsibilities->Comments->__toString(); ?></textarea>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
				<?php } ?>

				<p><br></p>

				<div class="row"><div class="col-sm-12"><div class="pull-right">
					<?php
					if($feedback->DefinitionsRoleAndResponsibilities->Status->__toString() == 'NA')
						echo HTML::renderWorkbookIcons('DefinitionsRoleAndResponsibilities', false, 'btn-danger');
					else
						echo HTML::renderWorkbookIcons('DefinitionsRoleAndResponsibilities', false, 'btn-success');
					?>
				</div></div></div>

				<?php echo $wb->getPageBottomLine(); ?>
			</div> <!--.page 3 ends-->

			<h1>Your role and responsibility & Personal organisation</h1>
			<div class="step-content">
				<?php echo $wb->getPageTopLine(); ?>

				<div class="row"><div class="col-sm-12"><div class="pull-right">
					<?php
					if($feedback->SMARTObjectives->Status->__toString() == 'NA')
						echo HTML::renderWorkbookIcons('SMARTObjectives', false, 'btn-danger');
					else
						echo HTML::renderWorkbookIcons('SMARTObjectives', false, 'btn-success');
					?>
				</div></div></div>

				<div class="row">
					<div class="col-sm-12">
						<h1>Your role and responsibility</h1>
						<p><strong>SMART objectives</strong></p>
						<p>Objectives need to be agreed and to do so they must be communicated between managers, team leaders and any team members they are relevant to. This could be done in a team briefing or on a one to one basis. The important thing is to ask questions if you are unsure and to ensure you have all the information you need before you carry out a task.</p>
					</div>
				</div>
				<div class="row">
					<div class="col-sm-12">
						<h3>Any objective you agree to should be SMART</h3>
					</div>
					<div class="col-sm-12">
						<table class="">
							<tr><td class="text-3d pull-right">S</td><td>Specific – you need to know exactly what you need to do. What, where, how, when, who etc.</td></tr>
							<tr><td class="text-3d pull-right">M</td><td>Measurable – solid criteria to measure progress against so you will know if you have achieved your goal.</td></tr>
							<tr><td class="text-3d pull-right">A</td><td>Achievable – is it possible? Can you do it?</td></tr>
							<tr><td class="text-3d pull-right">R</td><td>Realistic – you need to be willing and able to make it happen.</td></tr>
							<tr><td class="text-3d pull-right">T</td><td>Time – when do you need to complete the objective for?</td></tr>
						</table>
					</div>
					<div class="col-sm-12 text-center text-bold">
						<p>
							<img src="module_eportfolio/assets/images/wb2_img1.png" /> &nbsp;
							The table below has some examples of work objectives. Tick the boxes to show which ones are SMART objectives. If they’re not SMART give a reason in the comments box. &nbsp;
							<img src="module_eportfolio/assets/images/wb4_img1.png" />
						</p>
					</div>
					<div class="col-sm-12 table-responsive" <?php echo $feedback->SMARTObjectives->Status->__toString() == 'A'?'style="pointer-events:none;"':''; ?>>
						<table class="table row-border">
							<tr class="text-center"><th>Objective</th><th>SMART</th><th>Not SMART</th><th>Comments</th></tr>
							<tr><td>Fill up some of the hair dyes</td><td></td><td><span class="fa fa-check"></span> </td><td>Not specific as you don’t know what some hair dyes means.  You also don’t know how long you have to do the task. </td></tr>
							<tr>
								<td>It's 9am now so I would like you to sell 3 razor packs by the end of your shift at 5pm.</td>
								<td><input type="radio" name="Objective1Type[]" value="S" <?php echo $answers->SMARTObjectives->Objective1->Type->__toString() == 'S' ? 'checked="checked" ': ''; ?> /></td>
								<td><input type="radio" name="Objective1Type[]" value="NS" <?php echo $answers->SMARTObjectives->Objective1->Type->__toString() == 'NS' ? 'checked="checked" ': ''; ?> /></td>
								<td><textarea name="Objective1Comments" rows="3" style="width: 100%;"><?php echo $answers->SMARTObjectives->Objective1->Comments->__toString(); ?></textarea> </td>
							</tr>
							<tr>
								<td>You have one hour to put 3 cages of stock out on the skin section.</td>
								<td><input type="radio" name="Objective2Type[]" value="S" <?php echo $answers->SMARTObjectives->Objective2->Type->__toString() == 'S' ? 'checked="checked" ': ''; ?> /></td>
								<td><input type="radio" name="Objective2Type[]" value="NS" <?php echo $answers->SMARTObjectives->Objective2->Type->__toString() == 'NS' ? 'checked="checked" ': ''; ?> /></td>
								<td><textarea name="Objective2Comments" rows="3" style="width: 100%;"><?php echo $answers->SMARTObjectives->Objective2->Comments->__toString(); ?></textarea> </td></tr>
							<tr>
								<td>Please put the POS out at the front of the store</td>
								<td><input type="radio" name="Objective3Type[]" value="S" <?php echo $answers->SMARTObjectives->Objective3->Type->__toString() == 'S' ? 'checked="checked" ': ''; ?> /></td>
								<td><input type="radio" name="Objective3Type[]" value="NS" <?php echo $answers->SMARTObjectives->Objective3->Type->__toString() == 'NS' ? 'checked="checked" ': ''; ?> /></td>
								<td><textarea name="Objective3Comments" rows="3" style="width: 100%;"><?php echo $answers->SMARTObjectives->Objective3->Comments->__toString(); ?></textarea> </td>
							</tr>
							<tr><th colspan="4" class="bg-blue">Can you think of a SMART objective of your own? Write it below:</th></tr>
							<tr><td colspan="4"><textarea name="YourSMARTObjective" style="width: 100%;" rows="3"><?php echo $answers->SMARTObjectives->YourSMARTObjective->__toString(); ?></textarea> </td></tr>
						</table>
					</div>
					<div class="col-sm-12 text-center"><img src="module_eportfolio/assets/images/wb5_pg4_img1.png" /></div>
				</div>

				<p><br></p>

				<div class="row" <?php echo $feedback->SMARTObjectives->Status->__toString() == 'A'?'style="pointer-events:none;"':''; ?>>
					<div class="col-sm-12 table-responsive">
						<img src="module_eportfolio/assets/images/wb5_pg5_img1.png" />
					</div>
					<div class="col-sm-12">
						<p class="text-bold"><img src="module_eportfolio/assets/images/wb2_img1.png" /> &nbsp;What would happen if you didn’t have work objectives? &nbsp;</p>
						<p><textarea name="ImpactOfNoWorkObjective" style="width: 100%;" rows="3"><?php echo $answers->SMARTObjectives->ImpactOfNoWorkObjective->__toString(); ?></textarea></p>
						<p class="text-bold"><img src="module_eportfolio/assets/images/wb2_img1.png" /> &nbsp;Think about your own role and your responsibilities. Give 2 examples of how these have an impact on the team’s goals? &nbsp;</p>
						<p><textarea name="YourRoleAndResponsibilitiesImpactOnTeamGoal" style="width: 100%;" rows="5"><?php echo $answers->SMARTObjectives->YourRoleAndResponsibilitiesImpactOnTeamGoal->__toString(); ?></textarea></p>
					</div>
				</div>
				<div class="row text-center">
					<div class="col-sm-6"><img src="module_eportfolio/assets/images/wb5_pg5_img2.png" /> </div>
					<div class="col-sm-6"><img src="module_eportfolio/assets/images/wb5_pg5_img3.png" /> </div>
				</div>

				<?php if($wb->id != '' && $_SESSION['user']->type == User::TYPE_ASSESSOR && ($wb->wb_status == Workbook::STATUS_LEARNER_COMPLETED || $wb->wb_status == Workbook::STATUS_BEING_CHECKED || $wb->wb_status == Workbook::STATUS_IV_REJECTED)) {?>
				<div class="row well">
					<div class="col-sm-12">
						<div class="box box-success box-solid">
							<div class="box-header with-border"><h3 class="box-title">Assessor Feedback</h3></div>
							<div class="box-body assessorFeedback" <?php echo $feedback->SMARTObjectives->Status->__toString() == 'A'?'style="pointer-events:none;"':''; ?>>
								<div class="form-group">
									<label for="status_SMARTObjectives" class="col-sm-12 control-label">Status:</label>
									<div class="col-sm-12">
										<?php
										echo HTML::selectChosen('status_SMARTObjectives', $answer_status, $feedback->SMARTObjectives->Status->__toString() == 'A'?$feedback->SMARTObjectives->Status->__toString():'', false, true); ?>
									</div>
								</div>
								<div class="form-group">
									<label for="comments_SMARTObjectives" class="col-sm-12 control-label">Comments:</label>
									<div class="col-sm-12">
										<textarea name="comments_SMARTObjectives" rows="7" style="width: 100%;"><?php echo $feedback->SMARTObjectives->Comments->__toString(); ?></textarea>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
				<?php } ?>

				<p><br></p>

				<div class="row"><div class="col-sm-12"><div class="pull-right">
					<?php
					if($feedback->SMARTObjectives->Status->__toString() == 'NA')
						echo HTML::renderWorkbookIcons('SMARTObjectives', false, 'btn-danger');
					else
						echo HTML::renderWorkbookIcons('SMARTObjectives', false, 'btn-success');
					?>
				</div></div></div>

				<?php echo $wb->getPageBottomLine(); ?>
			</div> <!--.page 4, 5 ends-->

			<h1>Your role and responsibility & Personal organisation</h1>
			<div class="step-content">
				<?php echo $wb->getPageTopLine(); ?>

				<div class="row"><div class="col-sm-12"><div class="pull-right"><?php echo HTML::renderWorkbookIcons('', true); ?></div></div></div>

				<div class="row">
					<div class="col-sm-12">
						<h1>Personal Organisation</h1>
					</div>
					<div class="col-sm-12">
						<p><strong>Eisenhower's Urgent/Important Principle</strong></p>
						<p>Once objectives have been agreed, the next step is to make a decision on how each objective will be achieved and on time. You and / or your team may have a long list of objectives to be completed so how do you decide what to do first?</p>
						<p>One way to do this is to think about each task and decide if it is urgent and / or important. This will then enable you to decide what needs to be done first.</p>
					</div>
				</div>
				<div class="row"  style="display: flex;">
					<div class="col-sm-1"></div>
					<div class="col-sm-4 bg-aqua-gradient text-center">
						<p><strong>Eisenhower's Urgent/Important Principle </strong>helps you think about your priorities, and determine which of your activities are important and which are, essentially, distractions</p>
						<p><img src="module_eportfolio/assets/images/wb5_pg6_img1.png" /></p>
					</div>
					<div class="col-sm-1"></div>
					<div class="col-sm-4 bg-purple-gradient text-center">
						<p>Dwight D. Eisenhower was the 34th President of the United States from 1953 until 1961. Before becoming President, he served as a general in the United States Army and as the Allied Forces Supreme Commander during World War II. He also later became NATO’s first supreme commander.</p>
						<p>Eisenhower had to make tough decisions continuously about which of the many tasks he should focus on each day. This finally led him to invent the world-famous Eisenhower principle, which today helps us prioritise by urgency and importance.</p>
					</div>
					<div class="col-sm-1"></div>
				</div>
				<div class="row">
					<div class="col-sm-6">
						<p><br><strong>What are Urgent and Important Activities?</strong></p>
						<ul style="margin-left: 15px;">
							<li>Urgent means that a task requires immediate attention</li>
							<li>Important tasks are things that contribute to our long term mission, values and goals</li>
						</ul>
					</div>
					<div class="col-sm-4"><img class="pull-left" src="module_eportfolio/assets/images/wb2_img2.png" /></div>
				</div>
				<div class="row">
					<div class="col-sm-12">
						<table class="table row-border text-center text-bold">
							<tr class="text-center"><th>&nbsp;</th><th class="text-red">URGENT</th><th class="text-red">NOT URGENT</th></tr>
							<tr style="line-height: 15px;"><td class="text-red ">IMPORTANT</td><td class="text-blue" style="vertical-align: middle;">DO <br> Do it now</td><td style="vertical-align: middle;" class="text-blue">DECIDE<br> Plan a time to do it</td></tr>
							<tr style="line-height: 15px;"><td class="text-red ">NOT IMPORTANT</td><td style="vertical-align: middle;" class="text-blue">DELEGATE<br> Who can do it for you?</td><td style="vertical-align: middle;" class="text-blue">DELETE<br> Eliminate it</td></tr>
						</table>
					</div>
				</div>

				<div class="row">
					<div class="col-sm-12">

					</div>
				</div>

				<p><br></p>

				<div class="row"><div class="col-sm-12"><div class="pull-right"><?php echo HTML::renderWorkbookIcons('', true); ?></div></div></div>

				<?php echo $wb->getPageBottomLine(); ?>
			</div> <!--.page 6 ends-->

			<h1>Personal organisation</h1>
			<div class="step-content">
				<?php echo $wb->getPageTopLine(); ?>

				<div class="row"><div class="col-sm-12"><div class="pull-right">
					<?php
					if($feedback->EisenhowerPrinciple->Status->__toString() == 'NA')
						echo HTML::renderWorkbookIcons('EisenhowerPrinciple', false, 'btn-danger');
					else
						echo HTML::renderWorkbookIcons('EisenhowerPrinciple', false, 'btn-success');
					?>
				</div></div></div>

				<div class="row">
					<div class="col-sm-12">
						<h1>Personal Organisation</h1>
					</div>
					<div class="col-sm-12 text-center text-bold">
						<p>
							<img src="module_eportfolio/assets/images/wb2_img1.png" /> &nbsp;
							Think about some of the tasks you do in store and list them in the most appropriate box: &nbsp;
							<img src="module_eportfolio/assets/images/wb4_img1.png" />
						</p>
					</div>
					<div class="col-sm-12" <?php echo $feedback->EisenhowerPrinciple->Status->__toString() == 'A'?'style="pointer-events:none;"':''; ?>>
						<table class="table row-border">
							<tr class="text-center"><th>&nbsp;</th><th >URGENT</th><th >NOT URGENT</th></tr>
							<tr style="line-height: 15px;">
								<td >IMPORTANT</td>
								<td><span  class="text-bold">1 DO - Do it now</span><br><textarea name="Do" style="width: 100%;" rows="5"><?php echo $answers->EisenhowerPrinciple->Do->__toString(); ?></textarea> </td>
								<td><span  class="text-bold">2 DECIDE - Plan a time to do it</span><br><textarea name="Decide" style="width: 100%;" rows="5"><?php echo $answers->EisenhowerPrinciple->Decide->__toString(); ?></textarea></td>
							</tr>
							<tr style="line-height: 15px;">
								<td>NOT IMPORTANT</td>
								<td><span  class="text-bold">3 DELEGATE - Who can do it for you?</span><br><textarea name="Delegate" style="width: 100%;" rows="5"><?php echo $answers->EisenhowerPrinciple->Delegate->__toString(); ?></textarea></td>
								<td><span  class="text-bold">4 DELETE - Eliminate it</span><br><textarea name="Delete" style="width: 100%;" rows="5"><?php echo $answers->EisenhowerPrinciple->Delete->__toString(); ?></textarea></td>
							</tr>
						</table>
					</div>
				</div>
				<div class="row">
					<div class="col-sm-6">
						<p><strong>1. Improtant and Urgent</strong></p>
						<p>There are two distinct types of urgent and important activities: ones that you could not have foreseen, and others that you've left until the last minute.</p>
						<p>To try to eliminate last-minute activities you need to plan ahead, however; you can't always predict or avoid some issues and crises. The best approach is to leave some time to handle any unexpected issues or unplanned important activities.</p>
						<p><strong>2. Important but Not Urgent</strong></p>
						<p>These are the activities that help you achieve your personal and professional goals, and complete important work.</p>
						<p>Make sure that you have plenty of time to do these things properly, so that they do not become urgent. Also, remember to leave enough time in your schedule to deal with unforeseen problems. This maximises the chance of keeping on track, and helps you avoid the stress of work becoming more urgent than necessary.</p>

					</div>
					<div class="col-sm-6">
						<p><strong>3. Not Important but Urgent</strong></p>
						<p>Urgent but not important tasks are things that prevent you from achieving your goals. Ask yourself whether you can reschedule or delegate them.</p>
						<p>A common source of such activities is other people. Sometimes it's appropriate to say "no" to people politely, or to encourage them to solve the problem themselves.</p>
						<p><strong>4. Not Important and Not Urgent</strong></p>
						<p>These activities are just a distraction – avoid them if possible.</p>
						<p>You can simply ignore or cancel many of them. However, some may be activities that other people want you to do, even though they don't contribute to your own desired outcomes. Again, say "no" politely, if you can, and explain why you cannot do it.</p>
						<p>If people see that you are clear about your objectives and boundaries, they will often avoid asking you to do "not important" activities in the future.</p>
					</div>
				</div>

				<?php if($wb->id != '' && $_SESSION['user']->type == User::TYPE_ASSESSOR && ($wb->wb_status == Workbook::STATUS_LEARNER_COMPLETED || $wb->wb_status == Workbook::STATUS_BEING_CHECKED || $wb->wb_status == Workbook::STATUS_IV_REJECTED)) {?>
				<div class="row well">
					<div class="col-sm-12">
						<div class="box box-success box-solid">
							<div class="box-header with-border"><h3 class="box-title">Assessor Feedback</h3></div>
							<div class="box-body assessorFeedback" <?php echo $feedback->EisenhowerPrinciple->Status->__toString() == 'A'?'style="pointer-events:none;"':''; ?>>
								<div class="form-group">
									<label for="status_EisenhowerPrinciple" class="col-sm-12 control-label">Status:</label>
									<div class="col-sm-12">
										<?php
										echo HTML::selectChosen('status_EisenhowerPrinciple', $answer_status, $feedback->EisenhowerPrinciple->Status->__toString() == 'A'?$feedback->EisenhowerPrinciple->Status->__toString():'', false, true); ?>
									</div>
								</div>
								<div class="form-group">
									<label for="comments_EisenhowerPrinciple" class="col-sm-12 control-label">Comments:</label>
									<div class="col-sm-12">
										<textarea name="comments_EisenhowerPrinciple" rows="7" style="width: 100%;"><?php echo $feedback->EisenhowerPrinciple->Comments->__toString(); ?></textarea>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
				<?php } ?>

				<p><br></p>

				<div class="row"><div class="col-sm-12"><div class="pull-right">
					<?php
					if($feedback->EisenhowerPrinciple->Status->__toString() == 'NA')
						echo HTML::renderWorkbookIcons('EisenhowerPrinciple', false, 'btn-danger');
					else
						echo HTML::renderWorkbookIcons('EisenhowerPrinciple', false, 'btn-success');
					?>
				</div></div></div>

				<?php echo $wb->getPageBottomLine(); ?>
			</div> <!--.page 7 ends-->

			<h1>Personal organisation</h1>
			<div class="step-content">
				<?php echo $wb->getPageTopLine(); ?>

				<div class="row"><div class="col-sm-12"><div class="pull-right">
					<?php
					if($feedback->ToolsTechniquesToMonitorProgress->Status->__toString() == 'NA')
						echo HTML::renderWorkbookIcons('ToolsTechniquesToMonitorProgress', false, 'btn-danger');
					else
						echo HTML::renderWorkbookIcons('ToolsTechniquesToMonitorProgress', false, 'btn-success');
					?>
				</div></div></div>

				<div class="row" <?php echo $feedback->ToolsTechniquesToMonitorProgress->Status->__toString() == 'A'?'style="pointer-events:none;"':''; ?>>
					<div class="col-sm-12">
						<h1>Personal Organisation</h1>
						<p><strong>Tools and techniques to monitor progress</strong> &nbsp; <img src="module_eportfolio/assets/images/wb2_img2.png" /></p>
						<p>Now you have decided which task or objective to complete first, the next step is to monitor the progress of the tasks to meet the agreed deadline.</p>
						<p class="text-bold"><img src="module_eportfolio/assets/images/wb2_img1.png" /> &nbsp;What does your team currently do in store to check and monitor whether a task is being completed on time?</p>
						<p><textarea name="HowToCheckTaskIsBeingCompleted" style="width: 100%;" rows="5"><?php echo $answers->ToolsTechniquesToMonitorProgress->HowToCheckTaskIsBeingCompleted->__toString(); ?></textarea></p>
						<p class="text-bold"><img src="module_eportfolio/assets/images/wb2_img1.png" /> &nbsp;What other tools could you use to monitor the progress of a task? </p>
						<p><textarea name="ToolsToMonitorProgress" style="width: 100%;" rows="5"><?php echo $answers->ToolsTechniquesToMonitorProgress->ToolsToMonitorProgress->__toString(); ?></textarea></p>
						<p class="text-bold"><img src="module_eportfolio/assets/images/wb2_img1.png" /> &nbsp;What techniques could you use to monitor the progress of a task? </p>
						<p><textarea name="TechniquesToMonitorProgress" style="width: 100%;" rows="5"><?php echo $answers->ToolsTechniquesToMonitorProgress->TechniquesToMonitorProgress->__toString(); ?></textarea></p>
						<p class="text-bold"><img src="module_eportfolio/assets/images/wb2_img1.png" /> &nbsp;What would you do if a task wasn’t going to plan? How would you deal with the situation? </p>
						<p><textarea name="TaskNotGoingToPlan" style="width: 100%;" rows="5"><?php echo $answers->ToolsTechniquesToMonitorProgress->TaskNotGoingToPlan->__toString(); ?></textarea></p>
					</div>
				</div>
				<?php if($wb->id != '' && $_SESSION['user']->type == User::TYPE_ASSESSOR && ($wb->wb_status == Workbook::STATUS_LEARNER_COMPLETED || $wb->wb_status == Workbook::STATUS_BEING_CHECKED || $wb->wb_status == Workbook::STATUS_IV_REJECTED)) {?>
				<div class="row well">
					<div class="col-sm-12">
						<div class="box box-success box-solid">
							<div class="box-header with-border"><h3 class="box-title">Assessor Feedback</h3></div>
							<div class="box-body assessorFeedback" <?php echo $feedback->ToolsTechniquesToMonitorProgress->Status->__toString() == 'A'?'style="pointer-events:none;"':''; ?>>
								<div class="form-group">
									<label for="status_ToolsTechniquesToMonitorProgress" class="col-sm-12 control-label">Status:</label>
									<div class="col-sm-12">
										<?php
										echo HTML::selectChosen('status_ToolsTechniquesToMonitorProgress', $answer_status, $feedback->ToolsTechniquesToMonitorProgress->Status->__toString() == 'A'?$feedback->ToolsTechniquesToMonitorProgress->Status->__toString():'', false, true); ?>
									</div>
								</div>
								<div class="form-group">
									<label for="comments_ToolsTechniquesToMonitorProgress" class="col-sm-12 control-label">Comments:</label>
									<div class="col-sm-12">
										<textarea name="comments_ToolsTechniquesToMonitorProgress" rows="7" style="width: 100%;"><?php echo $feedback->ToolsTechniquesToMonitorProgress->Comments->__toString(); ?></textarea>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
				<?php } ?>
				<p><br></p>
				<div class="row"><div class="col-sm-12"><div class="pull-right">
					<?php
					if($feedback->ToolsTechniquesToMonitorProgress->Status->__toString() == 'NA')
						echo HTML::renderWorkbookIcons('ToolsTechniquesToMonitorProgress', false, 'btn-danger');
					else
						echo HTML::renderWorkbookIcons('ToolsTechniquesToMonitorProgress', false, 'btn-success');
					?>
				</div></div></div>


				<?php echo $wb->getPageBottomLine(); ?>
			</div> <!--.page 8 ends-->

			<h1>Personal organisation</h1>
			<div class="step-content">
				<?php echo $wb->getPageTopLine(); ?>

				<div class="row"><div class="col-sm-12"><div class="pull-right">
					<?php
					if($feedback->QualificationQuestions->Status->__toString() == 'NA')
						echo HTML::renderWorkbookIcons('QualificationQuestions', false, 'btn-danger');
					else
						echo HTML::renderWorkbookIcons('QualificationQuestions', false, 'btn-success');
					?>
				</div></div></div>

				<div class="row">
					<div class="col-sm-12">
						<h1>Your role and responsibility & Personal Organisation</h1>
					</div>
				</div>
				<div class="row">
					<div class="col-sm-12 text-center">
						<h2>Qualification questions</h2>
					</div>
				</div>
				<div class="row" <?php echo $feedback->QualificationQuestions->Status->__toString() == 'A'?'style="pointer-events:none;"':''; ?>>
					<div class="col-sm-12">
						<div class="box box-solid box-success">
							<div class="box-header">
								<h3 class="box-title"><b>Unit 5: Role and responsibilities within a customer service environment</b></h3>
							</div>
							<div class="box-body">
								<p>To achieve learning outcome 1 (Understand their role and responsibility with the organisation) answer the following questions in as much detail as you can.</p>
								<p><strong>1.1 Describe your role and responsibilities within the organisation</strong></p>
								<p><textarea name="Unit1_1" style="width: 100%" rows="5"><?php echo $answers->QualificationQuestions->Unit1_1->__toString(); ?></textarea></p>
								<p><strong>1.2 Explain the importance of good customer service to the customer and in turn the organisation</strong></p>
								<p><textarea name="Unit1_2" style="width: 100%" rows="5"><?php echo $answers->QualificationQuestions->Unit1_2->__toString(); ?></textarea></p>
								<p><strong>1.3 Explain how the actions taken in the context of your job role and responsibilities impact on others in the organisation</strong></p>
								<p><textarea name="Unit1_3" style="width: 100%" rows="5"><?php echo $answers->QualificationQuestions->Unit1_3->__toString(); ?></textarea></p>
							</div>
						</div>
					</div>
				</div>
				<?php if($wb->id != '' && $_SESSION['user']->type == User::TYPE_ASSESSOR && ($wb->wb_status == Workbook::STATUS_LEARNER_COMPLETED || $wb->wb_status == Workbook::STATUS_BEING_CHECKED || $wb->wb_status == Workbook::STATUS_IV_REJECTED)) {?>
				<div class="row well">
					<div class="col-sm-12">
						<div class="box box-success box-solid">
							<div class="box-header with-border"><h3 class="box-title">Assessor Feedback</h3></div>
							<div class="box-body assessorFeedback" <?php echo $feedback->QualificationQuestions->Status->__toString() == 'A'?'style="pointer-events:none;"':''; ?>>
								<div class="form-group">
									<label for="status_QualificationQuestions" class="col-sm-12 control-label">Status:</label>
									<div class="col-sm-12">
										<?php
										echo HTML::selectChosen('status_QualificationQuestions', $answer_status, $feedback->QualificationQuestions->Status->__toString() == 'A'?$feedback->QualificationQuestions->Status->__toString():'', false, true); ?>
									</div>
								</div>
								<div class="form-group">
									<label for="comments_QualificationQuestions" class="col-sm-12 control-label">Comments:</label>
									<div class="col-sm-12">
										<textarea name="comments_QualificationQuestions" rows="7" style="width: 100%;"><?php echo $feedback->QualificationQuestions->Comments->__toString(); ?></textarea>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
				<?php } ?>
				<p><br></p>

				<div class="row"><div class="col-sm-12"><div class="pull-right">
					<?php
					if($feedback->QualificationQuestions->Status->__toString() == 'NA')
						echo HTML::renderWorkbookIcons('QualificationQuestions', false, 'btn-danger');
					else
						echo HTML::renderWorkbookIcons('QualificationQuestions', false, 'btn-success');
					?>
				</div></div></div>

				<?php echo $wb->getPageBottomLine(); ?>
			</div> <!--.page 9 ends-->

			<h1>Personal organisation</h1>
			<div class="step-content">
				<?php echo $wb->getPageTopLine(); ?>

				<div class="row">
					<div class="col-sm-12">
						<h1>Your role and responsibility & Personal Organisation</h1>
					</div>
				</div>
				<p><br><strong>Finally</strong></p>
				<div class="row">
					<div class="col-sm-12">
						<p class="text-bold"><img src="module_eportfolio/assets/images/wb2_pg18_img1.png" /> &nbsp; You may want to do/have done some independent learning for this module. Which websites have you used to research the topics in this module? Record them below.</p>
					</div>
				</div>
				<div class="row">
					<div class="col-sm-12 table-responsive">
						<table class="table table-responsive">
							<tr><th>Website name</th><th>Topic</th><th>Date completed</th><th>Time taken</th></tr>
							<?php
							for($i = 1; $i <= 5; $i++)
							{
								$key = 'Set'.$i;
								$f1 = 'rsrch_set'.$i.'_website';
								$f2 = 'rsrch_set'.$i.'_topic';
								$f3 = 'rsrch_set'.$i.'_date_completed';
								$f4 = 'rsrch_set'.$i.'_time_taken';
								$f1_val = isset($answers->Research->$key->Website)?$answers->Research->$key->Website:'';
								$f2_val = isset($answers->Research->$key->Topic)?$answers->Research->$key->Topic:'';
								$f3_val = isset($answers->Research->$key->DateCompleted)?$answers->Research->$key->DateCompleted:'';
								$f4_val = isset($answers->Research->$key->TimeTaken)?$answers->Research->$key->TimeTaken:'';
								echo '<tr>';
								echo '<td><input class="form-control" name="rsrch_set'.$i.'_website" id="rsrch_set'.$i.'_website" size="50" value="'. $f1_val . '" /></td>';
								echo '<td><input class="form-control" name="rsrch_set'.$i.'_topic" id="rsrch_set'.$i.'_topic" size="50" value="'. $f2_val . '" /></td>';
								echo '<td>' . HTML::datebox("rsrch_set".$i."_date_completed", $f3_val) . '</td>';
								echo '<td>' . HTML::timebox("rsrch_set".$i."_time_taken", $f4_val) . '</td>';
								echo '</tr>';
							}
							?>
						</table>
					</div>
				</div>
				<div class="row">
					<div class="col-sm-12">
						<p><strong>Congratulations! You have competed this module. <img src="module_eportfolio/assets/images/wb2_pg18_img2.png" /> </p>
					</div>
				</div>
				<div class="row">
					<div class="col-sm-12 table-responsive">
						<table class="table row-border">
							<tr><th>&nbsp;</th><th>Name</th><th>Signature</th><th>Date</th></tr>
							<tr>
								<td>Apprentice</td>
								<?php if($_SESSION['user']->type == User::TYPE_LEARNER) {?>
								<td><h2 class="content-max-width"><?php echo $_SESSION['user']->firstnames . ' ' . $_SESSION['user']->surname; ?></td>
								<td>
						<span class="btn btn-info" onclick="getSignature('learner');">
							<img id="img_user_signature" src="do.php?_action=generate_image&<?php echo $wb->learner_signature != ''?$wb->learner_signature:'title=Click here to sign&font=Signature_Regular.ttf&size=25'; ?>" style="border: 2px solid;border-radius: 15px;" />
							<input type="hidden" name="user_signature" id="user_signature" value="<?php echo $wb->learner_signature; ?>" />
						</span>
								</td>
								<td><h2 class="content-max-width"><?php echo date('d/m/Y'); ?></h2></td>
								<?php } else { ?>
								<td><h2 class="content-max-width"><?php echo $tr->firstnames . ' ' . $tr->surname; ?></h2> </td>
								<td><img src="do.php?_action=generate_image&<?php echo $wb->learner_signature; ?>&size=25" style="border: 2px solid;border-radius: 15px;" /></td>
								<td><h2 class="content-max-width"><?php echo Date::toShort($wb->learner_sign_date)  ; ?></h2></td>
								<?php } ?>
							</tr>
							<?php if($_SESSION['user']->type == User::TYPE_ASSESSOR) {?>
							<tr>
								<td>Assessor</td>
								<td><h2 class="content-max-width"><?php echo $_SESSION['user']->firstnames . ' ' . $_SESSION['user']->surname; ?></td>
								<td>
						<span class="btn btn-info" onclick="getSignature('assessor');">
							<img id="img_user_signature" src="do.php?_action=generate_image&<?php echo $wb->assessor_signature != ''?$wb->assessor_signature:'title=Click here to sign&font=Signature_Regular.ttf&size=25'; ?>" style="border: 2px solid;border-radius: 15px;" />
							<input type="hidden" name="user_signature" id="user_signature" value="<?php echo $wb->assessor_signature; ?>" />
						</span>
								</td>
								<td><h2 class="content-max-width"><?php echo date('d/m/Y'); ?></h2></td>
							</tr>
							<?php } if($_SESSION['user']->type != User::TYPE_ASSESSOR && $wb->assessor_signature != '') {?>
							<tr>
								<td>Assessor</td>
								<td><h2 class="content-max-width"><?php echo DAO::getSingleValue($link, "SELECT CONCAT(firstnames, ' ' , surname) FROM users WHERE id = '{$tr->assessor}'"); ?></h2> </td>
								<td><img src="do.php?_action=generate_image&<?php echo $wb->assessor_signature; ?>&size=25" style="border: 2px solid;border-radius: 15px;" /></td>
								<td><h2 class="content-max-width"><?php echo Date::toShort($wb->assessor_sign_date)  ; ?></h2></td>
							</tr>
							<?php } ?>
						</table>
					</div>
				</div>

				<?php echo $wb->renderIVSection($link); ?>
				<p><br></p>

				<?php echo $wb->getPageBottomLine(); ?>
			</div> <!--.page 10 ends-->

		</div> <!--.wizards ends-->

		</section> <!--.content ends-->

		</div> <!--.content-wrapper ends-->

		</div> <!--.wrapper ends-->
	</div>
</form>

<div id = "loading"></div>

<div id="dialogPreview" title="Verify information before save">
	<p>Please verify your input information.</p>
	<div id="divPreview" class="small"></div>
</div>
<div class="row"><div class="col-sm-12"><span class="btn btn-xs btn-default" onclick="window.history.back();"><i class="fa fa-arrow-back"></i> Go Back</span></div></div>

<script src="/assets/adminlte/plugins/jQuery/jquery-2.2.3.min.js"></script>
<script src="module_eportfolio/assets/jquery.steps.js"></script>
<script src="https://gitcdn.github.io/bootstrap-toggle/2.2.2/js/bootstrap-toggle.min.js"></script>
<script src="/assets/adminlte/plugins/jQueryUI/jquery-ui.min.js"></script>
<script src="/assets/adminlte/plugins/iCheck/icheck.min.js"></script>
<script src="/assets/adminlte/bootstrap/js/bootstrap.min.js"></script>
<script src="/assets/adminlte/plugins/bootstrap-validator/bootstrapValidator.js"></script>
<script src="/assets/adminlte/plugins/toastr/toastr.min.js"></script>
<script src="/assets/js/autoresize.js"></script>


<script type="text/javascript">
	$(function () {

		<?php if($disable_answers){?>

		$("#frm_wb_role_responsibility :input").not(".assessorFeedback :input, #signature_text, #frm_wb_role_responsibility :input[type=hidden], .btnViewSectionHistory, .btnViewAssessorComments, #iv_status, #iv_comments, #reopen_comments").prop("disabled", true);

		<?php } ?>


		$("#wizard").steps({
			transitionEffect:"fade",
			transitionEffectSpeed:500,
			//startIndex:14,
			enableAllSteps:true,
			enableKeyNavigation:true,
			onStepChanging:function (event, currentIndex, newIndex) {
				return true;
			},
			onStepChanged:function (event, currentIndex, priorIndex) {
				//window.scrollTo(0, 0);
				return true;
			},
			onFinishing:function (event, currentIndex) {
				if($("#user_signature").val() == '')
					return alert('Your signature is required to complete the workbook, please sign the workbook');

				return true;
			},
			onFinished:function (event, currentIndex) {

			<?php if($_SESSION['user']->type == User::TYPE_LEARNER && !$wb->enableForUser()){ ?>
				return window.history.back();
				<?php } ?>
			<?php if($_SESSION['user']->type == User::TYPE_ASSESSOR && !$wb->enableForUser()){ ?>
				return window.history.back();
				<?php } ?>


				<?php if($_SESSION['user']->type == User::TYPE_LEARNER){ ?>
				//if(!confirm('Are you sure, you want to save this workbook as COMPLETED and send it to your assessor?'))
				//	return false;
				$('<div></div>').html('Are you sure, you want to save this workbook as COMPLETED and send it to your assessor?').dialog({
					title:'Confirmation',
					resizable: false,
					modal:true,
					buttons:{
						"Yes":function () {
							var myForm = document.forms['frm_wb_role_responsibility'];
							myForm.elements['full_save'].value = 'Y';
							window.onbeforeunload = null;
							myForm.submit();
						},
						"No":function () {
							$(this).dialog("close");
							return false;
						},
						"Save And Come Back Later":function () {
							$(this).dialog("close");
							partialSave();
							window.onbeforeunload = null;
							window.location.href='do.php?_action=learner_home_page';
						}
					}
				});
				<?php } elseif($_SESSION['user']->type == User::TYPE_ASSESSOR) { ?>
				var myForm = document.forms['frm_wb_role_responsibility'];
				myForm.elements['full_save'].value = 'Y';
				return previewInputInformation();
				<?php } else {?>
				return window.history.back();
				<?php } ?>
			}
		});

		//$('ul[role="tablist"]').hide();

		$('input[type=checkbox]').iCheck({
			checkboxClass: 'icheckbox_flat-green',
			radioClass: 'iradio_flat-green'
		});

		$('input[type=radio]').iCheck({
			radioClass: 'iradio_square-green'
		});

		$('#dialogPreview').dialog({
			modal: true,
			width: 'auto',
			maxWidth: 550,
			height: 'auto',
			maxHeight: 500,
			closeOnEscape: true,
			autoOpen: false,
			resizable: true,
			draggable: true,
			buttons: {
				'Cancel': function() {$(this).dialog('close');},
				'OK': function() {

					var myForm = document.forms['frm_wb_role_responsibility'];
					myForm.elements['full_save'].value = 'Y';
					myForm.elements['full_save_feedback'].value = 'Y';
					window.onbeforeunload = null;
					myForm.submit();

				},
				'Save And Come Back Later':function () {

					var myForm = document.forms['frm_wb_role_responsibility'];
					myForm.elements['full_save'].value = 'Y';
					myForm.elements['full_save_feedback'].value = 'N';
					window.onbeforeunload = null;
					myForm.submit();

				}
			}
		});
	});

	function partialSave()
	{
		$('#frm_wb_role_responsibility :input[name=full_save]').val('N');
		$($('#frm_wb_role_responsibility').serializeArray()).each(function(i, field)
		{
			document.forms["frm_wb_role_responsibility"].elements[field.name].value = field.value.replace(/£/g, "GBP");
		});
		$.ajax({
			type:'POST',
			url:'do.php?_action=save_wb_role_responsibility',
			data: $('#frm_wb_role_responsibility').serialize(),
			async: false,
			beforeSend: function(){
				//$("#loading").dialog('open').html("<p><i class=\"fa fa-refresh fa-spin\"></i> Busy ...</p>");
			},
			success: function(data, textStatus, xhr) {
				toastr.success('The information has been saved');
				reset();
				startInterval();
			},
			error: function(data, textStatus, xhr){
				var _msg = "";
				if(data.readyState == 0)
				{
					_msg = "No internet connection. The information has not been saved. \n";
					_msg += "Ready State: " + data.readyState + "\n";
					_msg += "Status Text: " + data.statusText + "\n";
					return alert(_msg);
				}
				var myxml = data.responseText,
					xmlDoc = $.parseXML( myxml ),
					$xml = $( xmlDoc );
				$(data.responseXML).find('error').each(function()
				{
					_msg = "Something went wrong, the information has not been saved. \n";
					_msg += "Error Message: " + $(this).find('message').text() + "\n";
					alert(_msg);
				});
			}
		});
	}


</script>

</body>

<script>
	var phpWorkbookID = '<?php echo $wb->id; ?>';
	var phpBookmarks = '<?php echo $wb_bookmarks; ?>';
	var phpStepsWithQuestions = '<?php echo $wb->getStepsWithQuestions(); ?>';
	var phpLearnerSignature = '<?php echo $learner_signature; ?>';
	var phpAssessorSignature = '<?php echo $assessor_signature; ?>';

</script>
<script src="module_eportfolio/assets/wb_common.js?n=<?php echo time(); ?>"></script>


<script>
	var counter = 0;
	var timer = null;

	function tictac(){
		counter++;
		if(counter >= 240)
			$("#clock").html('Time since last saved: <span class="text-bold">' + counter + '</span> seconds');
		if(counter == 300)
		{
			var html = '<p><span class="text-bold">It has been 5 minutes since you last saved your workbook. </span></p>';
			$("<div></div>").html(html).dialog({
				title: " Please save your information",
				resizable: false,
				modal: true,
				width: 'auto',
				maxWidth: 550,
				height: 'auto',
				maxHeight: 500,
				closeOnEscape: false,
				buttons: {
					'Save': function() {
						$(this).dialog('close');
						partialSave();
					}
				}
			}).css("background", "#FFF");
		}
	}

	function reset()
	{
		clearInterval(timer);
		counter=0;
		$("#clock").html('');
	}
	function startInterval()
	{
		timer= setInterval("tictac()", 1000);
	}
	function stopInterval()
	{
		clearInterval(timer);
	}
	<?php if($_SESSION['user']->type == User::TYPE_LEARNER && in_array($wb->wb_status, array(0,1,4))){ ?>
	startInterval();
		<?php } ?>

	<?php
	if($_SESSION['user']->type == User::TYPE_LEARNER)
	{
		if(in_array($wb->wb_status, array(2,3,5)))
			echo 'window.onbeforeunload = null;';
		else
			echo 'window.onbeforeunload = body_onbeforeunload;';
	}
	if($_SESSION['user']->type == User::TYPE_ASSESSOR)
	{
		if(in_array($wb->wb_status, array(0,1,4,5)))
			echo 'window.onbeforeunload = null;';
		else
			echo 'window.onbeforeunload = body_onbeforeunload;';
	}
	?>

	autosize(document.querySelectorAll('textarea'));

</script>




</body>

</html>
