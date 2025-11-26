<?php /* @var $wb WBEnvironment */ ?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/html" xmlns="http://www.w3.org/1999/html" xmlns="http://www.w3.org/1999/html"
      xmlns="http://www.w3.org/1999/html" xmlns="http://www.w3.org/1999/html">
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<title>Environment workbook</title>
	<meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
	<link rel="stylesheet" href="/assets/adminlte/bootstrap/css/bootstrap.min.css">
	<link rel="stylesheet" href="/assets/adminlte/dist/css/AdminLTE.min.css">
	<link href="module_eportfolio/assets/jquery.steps.css" rel="stylesheet">
	<link rel="stylesheet" href="/assets/adminlte/plugins/jQueryUI/jquery-ui.css">
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.5.0/css/font-awesome.min.css">
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/ionicons/2.0.1/css/ionicons.min.css">
	<link rel="stylesheet" href="/assets/adminlte/dist/css/skins/_all-skins.min.css">
	<link href="/assets/adminlte/plugins/pace/pace.css" rel="stylesheet">
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
			border: 2px groove blue;
			min-height: 640px;
			border-radius: 10px;
		}

		.topLine {
			position: absolute;
			top: 0;
			margin-bottom: 5px;
		}

		.bottomLine {
			position: absolute;
			bottom: 0;
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
			border: 1px solid #3366FF;
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
			opacity: 1;
		}

	</style>
</head>

<body>

<nav id="mainNav" class="navbar navbar-default navbar-fixed-top navbar-custom">
	<div class="container-float">
		<div class="navbar-header page-scroll">
			<a class="navbar-brand" href="#"><img height="35px" class="headerlogo"
			                                      src="images/logos/<?php echo $wb->getHeaderLogo($link); ?>"/></a>
		</div>
	</div>
	<div class="pull-right" id="clock"></div>
	<div class="text-center" style="margin-top: 5px;">
	<h3><?php echo $tr->firstnames . ' ' . strtoupper($tr->surname); ?></h3></div>
</nav>

<form name="frm_wb_environment" id="frm_wb_environment" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
	<input type="hidden" name="_action" value="save_wb_environment"/>
	<input type="hidden" name="id" value="<?php echo $wb->id; ?>"/>
	<input type="hidden" name="tr_id" value="<?php echo $wb->tr_id; ?>"/>
	<input type="hidden" name="wb_status" id="wb_status" value=""/>
	<input type="hidden" name="full_save" id="full_save" value="<?php echo $wb->full_save; ?>"/>
	<input type="hidden" name="full_save_feedback" id="full_save_feedback" value="<?php echo $wb->full_save_feedback; ?>"/>

	<div class="container-float">
		<div class="wrapper" style="background-color: #ffffff;">

			<header class="main-header"><span style="margin-left: 50%;"><?php echo DAO::getSingleValue($link, "SELECT title FROM student_frameworks WHERE tr_id = '{$wb->tr_id}'"); ?></span></header>

			<div class="content-wrapper" style="background-color: #ffffff;">

				<?php echo $wb->savers_or_sp == 'savers' ? '<section class="content-header"><h1 style="color: #0000ff;">Environment</h1></section>' : '<section class="content-header"><h1>Environment</h1></section>' ?>

				<section class="content">

					<div id="wizard">

						<h1>Environment</h1>

						<div class="step-content">
							<?php echo $wb->getPageTopLine(); ?>
							<div style="position: absolute; top: 40%; right: 50%;" class="lead">
								<?php echo $wb->savers_or_sp == 'savers' ? '<h2 class="text-bold" style="color: #0000ff;">Environment</h2>' : '<h2 class="text-bold">Environment</h2>' ?>
								<p class="text-center">Module</p>
							</div>

							<?php echo $wb->getPageBottomLine(); ?>
						</div>
						<!--.page 1 ends-->

						<h1>Environment</h1>

						<div class="step-content">
							<?php echo $wb->getPageTopLine(); ?>
							<div class="row"><div class="col-sm-12"><div class="pull-right"><?php echo HTML::renderWorkbookIcons('', true); ?></div></div></div>
							<blockquote class="text-center" style="border: 3px solid #000000">
								<p><b>Environment</b></p>
								<p><i>The surroundings or conditions in which a person, animal or plant lives or operates</i></p>
								<p><i>The natural world, as a whole or in a particular geographical area, especially as affected by human activity</i></p>
							</blockquote>
							<blockquote class="text-center" style="border: 3px dashed #ff69b4">
								<p>What are the main environmental matters that have implication for businesses and are there any that have particular relevance for the local business community?</p>
							</blockquote>
							<div class="text-center">
								<img src="module_eportfolio/assets/images/wb_r13_pg2_img1.png"/>
								<p class="text-bold">Environment is a part of <?php echo ucfirst($wb->savers_or_sp); ?>'s Sustainability Policy</p>
							</div>
							<div class="row">
								<div class="col-sm-6">
									<p><b>Emissions</b> - We will seek to reduce emissions from our activities through the development and implementation of specific action plans.</p>
									<p><b>Pollution Prevention</b> - We will undertake a risk assessment of our operations and ensure that appropriate measures are implemented to reduce the risk of pollution arising from our activities.</p>
									<p><b>Energy</b> - We will implement energy efficient measures throughout our stores and offices.</p>
									<p><b>Transport</b> - We will review how we manage our logistics and work with our staff to reduce the impact of business travel.</p>
									<p>We will consider the availability of sustainable transport facilities when selecting locations for all new stores.</p>
									<p><b>Waste</b> - We will identify the most suitable opportunities for recycling waste materials arising from our activities.</p>
								</div>
								<div class="col-sm-6">
									<p>We are committed to reducing the waste generated by working with our suppliers and distributors.</p>
									<p><b>Procurement</b> - We will review the environmental policies of manufacturers and sustainability credentials of the products we use in our operations.</p>
									<p>We will consider environmental best practice for the design, construction of all new stores and refurbishments of our existing stores.</p>
									<p><b>Climate Change Adaptation</b> - We will review the effects of climate change on our operations and implement the necessary actions.</p>
									<p><b>Water</b> - We will record water consumption through billing of stores and implement water efficient measures in new stores and during planned refurbishment.</p>
									<p class="text-center"><img src="module_eportfolio/assets/images/wb_r13_pg2_img2.png"/></p>
								</div>
							</div>
							<p><br></p>
							<div class="row"><div class="col-sm-12"><div class="pull-right"><?php echo HTML::renderWorkbookIcons('', true); ?></div></div></div>
							<?php echo $wb->getPageBottomLine(); ?>
						</div>
						<!--.page 2 ends-->

						<h1>Environment</h1>

						<div class="step-content">
							<?php echo $wb->getPageTopLine(); ?>
							<div class="row"><div class="col-sm-12"><div class="pull-right">
								<?php
								if($feedback->WorkActivitiesImpact->Status->__toString() == 'NA')
									echo HTML::renderWorkbookIcons('WorkActivitiesImpact', false, 'btn-danger');
								else
									echo HTML::renderWorkbookIcons('WorkActivitiesImpact', false, 'btn-success');
								?>
							</div></div></div>
							<blockquote class="text-center" style="border: 3px dashed #000000">
								<p class="text-center">How do work activities impact on the environment?</p>
							</blockquote>

							<div class="row">
								<div class="col-sm-12">
									<p>The place you work can affect the environment either positively or negatively to a very large degree. How eco-friendly your employer is when it comes to using energy to heat and cool the building, to bring products into it, and to remove waste from it has a major impact on your community and the planet.</p>
									<p>You may be surprised by some specific examples of ways the working world damages the environment:</p>
									<ul style="margin-left: 15px;">
										<li>Heating and air conditioning systems pump greenhouse gas emissions from offices into the atmosphere and use up vast amounts of electricity. Many buildings aren’t designed to include energy-efficient systems or technology to reduce the amount of heat and air conditioning they use.</li>
										<li>
											Many buildings are built from materials that don’t come from renewable sources.
											<img class="pull-right" src="module_eportfolio/assets/images/wb_r13_pg3_img1.png"/>
										</li>
										<li>Office buildings have a huge appetite for electricity to power lighting, air conditioning, computers, printers, and photocopiers. Equipment may be left on 24 hours a day, seven days a week - even when no one’s working.</li>
										<li>Offices consume vast amounts of paper. Even with more offices recycling paper, a large amount of paper waste still goes to landfill sites or incinerators.</li>
										<li>In addition to paper, offices produce a lot of other waste, including equipment (especially computers), because companies regularly upgrade their equipment to stay competitive. Electronics such as photocopiers and computers can end up in landfills, where they don’t break down and, even worse, can leach harmful chemicals into the ground and water.</li>
										<li>Rush-hour traffic jams in towns and cities are full of people trying to get to work — wasting time and polluting the atmosphere.</li>
									</ul>
								</div>
								<div class="col-sm-12">
									<p class="text-bold"><img src="module_eportfolio/assets/images/wb2_img1.png" /> &nbsp;What work activities do you do in your store that have a negative impact on the environment? &nbsp; </p>
									<div class="table-responsive" <?php echo $feedback->WorkActivitiesImpact->Status->__toString() == 'A'?'style="pointer-events:none;"':''; ?>>
										<table class="table">
											<tr><th style="width: 50%;" class="text-center">Work activity</th><th class="text-center" style="width: 50%;">Impact on the environment</th></tr>
											<?php
											for($i = 1; $i <= 3; $i++)
											{
												$set = 'Set'.$i;
												echo '<tr><td><textarea rows="3" name="WorkActivitiesImpactActivity'.$i.'" style="width: 100%;">'.$answers->WorkActivitiesImpact->$set->Activity->__toString() . '</textarea> </td><td><textarea name="WorkActivitiesImpactImpact'.$i.'" rows="3" style="width: 100%">'.$answers->WorkActivitiesImpact->$set->Impact->__toString().'</textarea> </td></tr>';
											}
											?>
										</table>
									</div>
								</div>
							</div>
							<?php if($wb->id != '' && $_SESSION['user']->type == User::TYPE_ASSESSOR && ($wb->wb_status == Workbook::STATUS_LEARNER_COMPLETED || $wb->wb_status == Workbook::STATUS_BEING_CHECKED || $wb->wb_status == Workbook::STATUS_IV_REJECTED)) {?>
							<div class="row well">
								<div class="col-sm-12">
									<div class="box box-success box-solid">
										<div class="box-header with-border"><h3 class="box-title">Assessor Feedback</h3></div>
										<div class="box-body assessorFeedback" <?php echo $feedback->WorkActivitiesImpact->Status->__toString() == 'A'?'style="pointer-events:none;"':''; ?>>
											<div class="form-group">
												<label for="status_WorkActivitiesImpact" class="col-sm-12 control-label">Status:</label>
												<div class="col-sm-12">
													<?php
													echo HTML::selectChosen('status_WorkActivitiesImpact', $answer_status, $feedback->WorkActivitiesImpact->Status->__toString() == 'A'?$feedback->WorkActivitiesImpact->Status->__toString():'', false, true); ?>
												</div>
											</div>
											<div class="form-group">
												<label for="comments_WorkActivitiesImpact" class="col-sm-12 control-label">Comments:</label>
												<div class="col-sm-12">
													<textarea name="comments_WorkActivitiesImpact" rows="7" style="width: 100%;"><?php echo $feedback->WorkActivitiesImpact->Comments->__toString(); ?></textarea>
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
								if($feedback->WorkActivitiesImpact->Status->__toString() == 'NA')
									echo HTML::renderWorkbookIcons('WorkActivitiesImpact', false, 'btn-danger');
								else
									echo HTML::renderWorkbookIcons('WorkActivitiesImpact', false, 'btn-success');
								?>
							</div></div></div>
							<?php echo $wb->getPageBottomLine(); ?>
						</div>
						<!--.page 3 ends-->

						<h1>Environment</h1>

						<div class="step-content">
							<?php echo $wb->getPageTopLine(); ?>
							<div class="row"><div class="col-sm-12"><div class="pull-right">
								<?php
								if($feedback->WorkActivitiesImpactImprove->Status->__toString() == 'NA')
									echo HTML::renderWorkbookIcons('WorkActivitiesImpactImprove', false, 'btn-danger');
								else
									echo HTML::renderWorkbookIcons('WorkActivitiesImpactImprove', false, 'btn-success');
								?>
							</div></div></div>
							<div class="row">
								<div class="col-sm-12">
									<p class="text-bold"><img src="module_eportfolio/assets/images/wb2_img1.png" /> &nbsp;Using your examples from the last activity what can you do to improve the impact on the environment? &nbsp; </p>
									<div class="table-responsive" <?php echo $feedback->WorkActivitiesImpactImprove->Status->__toString() == 'A'?'style="pointer-events:none;"':''; ?>>
										<table class="table">
											<tr><th style="width: 50%;" class="text-center">Work activity</th><th class="text-center" style="width: 50%;">Ways to improve the impact on the environment</th></tr>
											<?php
											for($i = 1; $i <= 5; $i++)
											{
												$set = 'Set'.$i;
												echo '<tr><td><textarea rows="3" name="WorkActivitiesImpactImproveActivity'.$i.'" style="width: 100%;">'.$answers->WorkActivitiesImpactImprove->$set->Activity->__toString() . '</textarea> </td><td><textarea name="WorkActivitiesImpactImproveImprovement'.$i.'" rows="3" style="width: 100%">'.$answers->WorkActivitiesImpactImprove->$set->Improvement->__toString().'</textarea> </td></tr>';
											}
											?>
										</table>
									</div>
									<p class="text-bold"><img src="module_eportfolio/assets/images/wb2_img1.png" /> &nbsp;Complete the exercise below &nbsp; </p>
									<ul style="margin-left: 15px;">
										<li>Choose a <?php echo ucfirst($wb->savers_or_sp); ?> initiative from the Environment policy</li>
										<li>Discuss and agree with your colleagues what you can do in store to support / promote</li>
										<li>Implement your ideas</li>
										<li>Record details below of what you did and what methods you used</li>
									</ul>
									<textarea rows="10" name="EnvironmentPolicyImplementation" style="width: 100%; <?php echo $feedback->WorkActivitiesImpactImprove->Status->__toString() == 'A'?'pointer-events:none;':''; ?>"><?php echo $answers->WorkActivitiesImpactImprove->EnvironmentPolicyImplementation->__toString(); ?></textarea>
								</div>
							</div>
							<?php if($wb->id != '' && $_SESSION['user']->type == User::TYPE_ASSESSOR && ($wb->wb_status == Workbook::STATUS_LEARNER_COMPLETED || $wb->wb_status == Workbook::STATUS_BEING_CHECKED || $wb->wb_status == Workbook::STATUS_IV_REJECTED)) {?>
							<div class="row well">
								<div class="col-sm-12">
									<div class="box box-success box-solid">
										<div class="box-header with-border"><h3 class="box-title">Assessor Feedback</h3></div>
										<div class="box-body assessorFeedback" <?php echo $feedback->WorkActivitiesImpactImprove->Status->__toString() == 'A'?'style="pointer-events:none;"':''; ?>>
											<div class="form-group">
												<label for="status_WorkActivitiesImpactImprove" class="col-sm-12 control-label">Status:</label>
												<div class="col-sm-12">
													<?php
													echo HTML::selectChosen('status_WorkActivitiesImpactImprove', $answer_status, $feedback->WorkActivitiesImpactImprove->Status->__toString() == 'A'?$feedback->WorkActivitiesImpactImprove->Status->__toString():'', false, true); ?>
												</div>
											</div>
											<div class="form-group">
												<label for="comments_WorkActivitiesImpactImprove" class="col-sm-12 control-label">Comments:</label>
												<div class="col-sm-12">
													<textarea name="comments_WorkActivitiesImpactImprove" rows="7" style="width: 100%;"><?php echo $feedback->WorkActivitiesImpactImprove->Status->__toString() == 'A'?$feedback->WorkActivitiesImpactImprove->Comments->__toString():''; ?></textarea>
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
								if($feedback->WorkActivitiesImpactImprove->Status->__toString() == 'NA')
									echo HTML::renderWorkbookIcons('WorkActivitiesImpactImprove', false, 'btn-danger');
								else
									echo HTML::renderWorkbookIcons('WorkActivitiesImpactImprove', false, 'btn-success');
								?>
							</div></div></div>
							<?php echo $wb->getPageBottomLine(); ?>
						</div>
						<!--.page 4 ends-->

						<h1>Environment</h1>

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
								<div class="col-sm-12 text-center">
									<h2 class="text-bold">Qualification questions</h2>
								</div>
								<div class="col-sm-12">
									<p>Now you have completed the section on Environment answer the following questions:</p>
									<div class="box box-solid box-success">
										<div class="box-header">
											<h3 class="box-title"><b>Unit 10 - 1.1: Explain how to take responsible decisions to minimise negative effects on the environment in all work activities</b></h3>
										</div>
										<div class="box-body" <?php echo $feedback->QualificationQuestions->Status->__toString() == 'A'?'style="pointer-events:none;"':''; ?>>
											<p><textarea name="Unit1_1" style="width: 100%" rows="10"><?php echo $answers->QualificationQuestions->Unit1_1->__toString(); ?></textarea></p>
										</div>
									</div>
									<div class="box box-solid box-success">
										<div class="box-header">
											<h3 class="box-title"><b>Unit 10 - 1.2: Recognise the effect of work activities on the environment through managing wastage and loss according to business procedures.</b></h3>
										</div>
										<div class="box-body" <?php echo $feedback->QualificationQuestions->Status->__toString() == 'A'?'style="pointer-events:none;"':''; ?>>
											<p><textarea name="Unit1_2" style="width: 100%" rows="12"><?php echo $answers->QualificationQuestions->Unit1_2->__toString(); ?></textarea></p>
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
						</div>
						<!--.page 5 ends-->

						<h1>Signature</h1>

						<div class="step-content">
							<?php echo $wb->getPageTopLine(); ?>
							<p><br><strong>Finally</strong></p>

							<div class="row">
								<div class="col-sm-12">
									<p class="text-bold"><img src="module_eportfolio/assets/images/wb2_pg18_img1.png"/>
										&nbsp; You may want to do/have done some independent learning for this module.
										Which websites have you used to research the topics in this module? Record them
										below.</p>
								</div>
							</div>
							<div class="row">
								<div class="col-sm-12 table-responsive">
									<table class="table table-responsive">
										<tr>
											<th>Website name</th>
											<th>Topic</th>
											<th>Date completed</th>
											<th>Time taken</th>
										</tr>
										<?php
										for ($i = 1; $i <= 5; $i++) {
											$key = 'Set' . $i;
											$f1 = 'rsrch_set' . $i . '_website';
											$f2 = 'rsrch_set' . $i . '_topic';
											$f3 = 'rsrch_set' . $i . '_date_completed';
											$f4 = 'rsrch_set' . $i . '_time_taken';
											$f1_val = isset($answers->Research->$key->Website) ? $answers->Research->$key->Website : '';
											$f2_val = isset($answers->Research->$key->Topic) ? $answers->Research->$key->Topic : '';
											$f3_val = isset($answers->Research->$key->DateCompleted) ? $answers->Research->$key->DateCompleted : '';
											$f4_val = isset($answers->Research->$key->TimeTaken) ? $answers->Research->$key->TimeTaken : '';
											echo '<tr>';
											echo '<td><input class="form-control" name="rsrch_set' . $i . '_website" id="rsrch_set' . $i . '_website" size="50" value="' . $f1_val . '" /></td>';
											echo '<td><input class="form-control" name="rsrch_set' . $i . '_topic" id="rsrch_set' . $i . '_topic" size="50" value="' . $f2_val . '" /></td>';
											echo '<td>' . HTML::datebox("rsrch_set" . $i . "_date_completed", $f3_val) . '</td>';
											echo '<td>' . HTML::timebox("rsrch_set" . $i . "_time_taken", $f4_val) . '</td>';
											echo '</tr>';
										}
										?>
									</table>
								</div>
							</div>
							<div class="row">
								<div class="col-sm-12">
									<p><strong>Congratulations! You have completed this module. <img
										src="module_eportfolio/assets/images/wb2_pg18_img2.png"/></p>
								</div>
							</div>
							<div class="row">
								<div class="col-sm-12 table-responsive">
									<table class="table row-border">
										<tr>
											<th>&nbsp;</th>
											<th>Name</th>
											<th>Signature</th>
											<th>Date</th>
										</tr>
										<tr>
											<td>Apprentice</td>
											<?php if ($_SESSION['user']->type == User::TYPE_LEARNER) { ?>
											<td><h2
												class="content-max-width"><?php echo $_SESSION['user']->firstnames . ' ' . $_SESSION['user']->surname; ?>
											</td>
											<td>
												<span class="btn btn-info" onclick="getSignature('learner');">
													<img id="img_user_signature"
													     src="do.php?_action=generate_image&<?php echo $wb->learner_signature != '' ? $wb->learner_signature : 'title=Click here to sign&font=Signature_Regular.ttf&size=25'; ?>"
													     style="border: 2px solid;border-radius: 15px;"/>
													<input type="hidden" name="user_signature" id="user_signature"
													       value="<?php echo $wb->learner_signature; ?>"/>
												</span>
											</td>
											<td><h2 class="content-max-width"><?php echo date('d/m/Y'); ?></h2></td>
											<?php } else { ?>
											<td><h2
												class="content-max-width"><?php echo $tr->firstnames . ' ' . $tr->surname; ?></h2>
											</td>
											<td><img
												src="do.php?_action=generate_image&<?php echo $wb->learner_signature; ?>&size=25"
												style="border: 2px solid;border-radius: 15px;"/></td>
											<td><h2
												class="content-max-width"><?php echo Date::toShort($wb->learner_sign_date); ?></h2>
											</td>
											<?php } ?>
										</tr>
										<?php if ($_SESSION['user']->type == User::TYPE_ASSESSOR) { ?>
										<tr>
											<td>Assessor</td>
											<td><h2
												class="content-max-width"><?php echo $_SESSION['user']->firstnames . ' ' . $_SESSION['user']->surname; ?>
											</td>
											<td>
												<span class="btn btn-info" onclick="getSignature('assessor');">
													<img id="img_user_signature"
													     src="do.php?_action=generate_image&<?php echo $wb->assessor_signature != '' ? $wb->assessor_signature : 'title=Click here to sign&font=Signature_Regular.ttf&size=25'; ?>"
													     style="border: 2px solid;border-radius: 15px;"/>
													<input type="hidden" name="user_signature" id="user_signature"
													       value="<?php echo $wb->assessor_signature; ?>"/>
												</span>
											</td>
											<td><h2 class="content-max-width"><?php echo date('d/m/Y'); ?></h2></td>
										</tr>
										<?php } if ($_SESSION['user']->type != User::TYPE_ASSESSOR && $wb->assessor_signature != '') { ?>
										<tr>
											<td>Assessor</td>
											<td><h2
												class="content-max-width"><?php echo DAO::getSingleValue($link, "SELECT CONCAT(firstnames, ' ' , surname) FROM users WHERE id = '{$tr->assessor}'"); ?></h2>
											</td>
											<td><img
												src="do.php?_action=generate_image&<?php echo $wb->assessor_signature; ?>&size=25"
												style="border: 2px solid;border-radius: 15px;"/></td>
											<td><h2
												class="content-max-width"><?php echo Date::toShort($wb->assessor_sign_date); ?></h2>
											</td>
										</tr>
										<?php } ?>
									</table>
								</div>
							</div>

							<?php echo $wb->renderIVSection($link); ?>

							<p><br></p>

							<?php echo $wb->getPageBottomLine(); ?>
						</div>
						<!--.page 18 ends-->

					</div>
					<!--.wizards ends-->

				</section>
				<!--.content ends-->

			</div>
			<!--.content-wrapper ends-->

		</div>
		<!--.wrapper ends-->
	</div>
</form>

<div id="loading"></div>

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
<script src="/assets/adminlte/plugins/toastr/toastr.min.js"></script>
<script src="/assets/js/autoresize.js"></script>

<script type="text/javascript">
	var totalActivist = 0;
	var totalReflector = 0;
	var totalTheorist = 0;
	var totalPragmatist = 0;

	$(function () {

	<?php if ($disable_answers) { ?>

		$("#frm_wb_environment :input").not(".assessorFeedback :input, #signature_text, #frm_wb_environment :input[type=hidden], .btnViewSectionHistory, .btnViewAssessorComments, #iv_status, #iv_comments, #reopen_comments").prop("disabled", true);

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
				if ($("#user_signature").val() == '')
				//return alert('Your signature is required to complete the workbook, please sign the workbook');
					return custom_alert_OK_only('Your signature is required to complete the workbook, please sign the workbook');

				return true;
			},
			onFinished:function (event, currentIndex) {

			<?php if ($_SESSION['user']->type == User::TYPE_LEARNER && !$wb->enableForUser()) { ?>
				return window.history.back();
				<?php } ?>
			<?php if ($_SESSION['user']->type == User::TYPE_ASSESSOR && !$wb->enableForUser()) { ?>
				return window.history.back();
				<?php } ?>


			<?php if ($_SESSION['user']->type == User::TYPE_LEARNER) { ?>
				//if(!confirm('Are you sure, you want to save this workbook as COMPLETED and send it to your assessor?'))
				//	return false;
				$('<div></div>').html('Are you sure, you want to save this workbook as COMPLETED and send it to your assessor?').dialog({
					title:'Confirmation',
					resizable:false,
					modal:true,
					buttons:{
						"Yes":function () {
							var myForm = document.forms['frm_wb_environment'];
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
							window.location.href = 'do.php?_action=learner_home_page';
						}
					}
				});
				<?php } elseif ($_SESSION['user']->type == User::TYPE_ASSESSOR) { ?>
				var myForm = document.forms['frm_wb_environment'];
				myForm.elements['full_save'].value = 'Y';
				return previewInputInformation();
				<?php } else { ?>
				return window.history.back();
				<?php } ?>
			}

		});

		$('input[type=checkbox]').iCheck({
			checkboxClass:'icheckbox_flat-red',
			radioClass:'iradio_flat-green'
		});

		$('#dialogPreview').dialog({
			modal:true,
			width:'auto',
			maxWidth:550,
			height:'auto',
			maxHeight:500,
			closeOnEscape:true,
			autoOpen:false,
			resizable:true,
			draggable:true,
			buttons:{
				'Cancel':function () {
					$(this).dialog('close');
				},
				'OK':function () {

					var myForm = document.forms['frm_wb_environment'];
					myForm.elements['full_save'].value = 'Y';
					myForm.elements['full_save_feedback'].value = 'Y';
					window.onbeforeunload = null;
					myForm.submit();

				},
				'Save And Come Back Later':function () {

					var myForm = document.forms['frm_wb_environment'];
					myForm.elements['full_save'].value = 'Y';
					myForm.elements['full_save_feedback'].value = 'N';
					window.onbeforeunload = null;
					myForm.submit();

				}
			}
		});
	});

	function partialSave() {
		$('#frm_wb_environment :input[name=full_save]').val('N');
		$($('#frm_wb_environment').serializeArray()).each(function(i, field)
		{
			document.forms["frm_wb_environment"].elements[field.name].value = field.value.replace(/£/g, "GBP");
		});
		$.ajax({
			type:'POST',
			url:'do.php?_action=save_wb_environment',
			data:$('#frm_wb_environment').serialize(),
			async:false,
			beforeSend:function () {
				//$("#loading").dialog('open').html("<p><i class=\"fa fa-refresh fa-spin\"></i> Busy ...</p>");
			},
			success:function (data, textStatus, xhr) {
				toastr.success('The information has been saved');
				reset();
				startInterval();
			},
			error:function (data, textStatus, xhr) {
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

	function tictac() {
		counter++;
		if (counter >= 240)
			$("#clock").html('Time since last saved: <span class="text-bold">' + counter + '</span> seconds');
		if (counter == 300) {
			var html = '<p><span class="text-bold">It has been 5 minutes since you last saved your workbook. </span></p>';
			$("<div></div>").html(html).dialog({
				title:" Please save your information",
				resizable:false,
				modal:true,
				width:'auto',
				maxWidth:550,
				height:'auto',
				maxHeight:500,
				closeOnEscape:false,
				buttons:{
					'Save':function () {
						$(this).dialog('close');
						partialSave();
					}
				}
			}).css("background", "#FFF");
		}
	}

	function reset() {
		clearInterval(timer);
		counter = 0;
		$("#clock").html('');
	}
	function startInterval() {
		timer = setInterval("tictac()", 1000);
	}
	function stopInterval() {
		clearInterval(timer);
	}
	<?php if ($_SESSION['user']->type == User::TYPE_LEARNER && in_array($wb->wb_status, array(0, 1, 4))) { ?>
	startInterval();
		<?php } ?>

	<?php
	if ($_SESSION['user']->type == User::TYPE_LEARNER) {
		if (in_array($wb->wb_status, array(2, 3, 5)))
			echo 'window.onbeforeunload = null;';
		else
			echo 'window.onbeforeunload = body_onbeforeunload;';
	}
	if ($_SESSION['user']->type == User::TYPE_ASSESSOR) {
		if (in_array($wb->wb_status, array(0, 1, 4, 5)))
			echo 'window.onbeforeunload = null;';
		else
			echo 'window.onbeforeunload = body_onbeforeunload;';
	}
	?>

	autosize(document.querySelectorAll('textarea'));

</script>

</html>
