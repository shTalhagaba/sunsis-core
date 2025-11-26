<?php /* @var $wb WBHSAndSecurity */ ?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/html" xmlns="http://www.w3.org/1999/html" xmlns="http://www.w3.org/1999/html"
      xmlns="http://www.w3.org/1999/html" xmlns="http://www.w3.org/1999/html" xmlns="http://www.w3.org/1999/html"
      xmlns="http://www.w3.org/1999/html">
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<title>H&S and Security workbook</title>
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

<form name="frm_wb_hs_and_security" id="frm_wb_hs_and_security" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
	<input type="hidden" name="_action" value="save_wb_hs_and_security"/>
	<input type="hidden" name="id" value="<?php echo $wb->id; ?>"/>
	<input type="hidden" name="tr_id" value="<?php echo $wb->tr_id; ?>"/>
	<input type="hidden" name="wb_status" id="wb_status" value=""/>
	<input type="hidden" name="full_save" id="full_save" value="<?php echo $wb->full_save; ?>"/>
	<input type="hidden" name="full_save_feedback" id="full_save_feedback"
	       value="<?php echo $wb->full_save_feedback; ?>"/>

	<div class="container-float">
		<div class="wrapper" style="background-color: #ffffff;">

			<header class="main-header"><span style="margin-left: 40%;"><?php echo DAO::getSingleValue($link, "SELECT title FROM student_frameworks WHERE tr_id = '{$wb->tr_id}'"); ?></span></header>

			<div class="content-wrapper" style="background-color: #ffffff;">

				<?php echo $wb->savers_or_sp == 'savers' ? '<section class="content-header"><h1 style="color: #0000ff;">H&S and Security</h1></section>' : '<section class="content-header"><h1>H&S and Security</h1></section>' ?>

				<section class="content">

					<div id="wizard">

						<h1>H&S and Security</h1>

						<div class="step-content">
							<?php echo $wb->getPageTopLine(); ?>
							<div style="position: absolute; top: 40%; right: 50%;" class="lead">
								<?php echo $wb->savers_or_sp == 'savers' ? '<h2 class="text-bold" style="color: #0000ff;">H&S and Security</h2>' : '<h2 class="text-bold">H&S and Security</h2>' ?>
								<p class="text-center">Module</p>
							</div>

							<?php echo $wb->getPageBottomLine(); ?>
						</div>
						<!--.page 1 ends-->

						<h1>H&S and Security</h1>

						<div class="step-content">
							<?php echo $wb->getPageTopLine(); ?>
							<div class="row"><div class="col-sm-12"><div class="pull-right"><?php echo HTML::renderWorkbookIcons('', true); ?></div></div></div>
							<div class="row">
								<div class="col-sm-12">
									<p class="text-bold">This section is about Health & Safety in the workplace and what you need to know and do.</p>
									<p>It is essential that all retail workplaces are safe for people to work in and to visit when they are shopping. In order for that to happen everyone must play their part.</p>
									<p>For this you must be able to:</p>
									<ul style="margin-left: 15px; margin-bottom: 15px;">
										<li>Understand and follow laws and regulations</li>
										<li>Follow company policy and procedures</li>
										<li>Support colleagues to work within company guidelines</li>
										<li>Use equipment safely</li>
										<li>Identify hazards</li>
										<li>Take action to prevent injury or damage</li>
										<li>Report any accidents and emergencies</li>
									</ul>
								</div>
							</div>
							<div class="row">
								<div class="col-sm-12"><p><br></p></div>
								<div class="col-sm-12"><p><img src="module_eportfolio/assets/images/wb4_pg2_img1.png" /> <strong>Learner journey / Visit plan</strong></p></div>
								<div class="col-sm-12">Before you start this module please ensure you have had completed all of the training on the iPad detailed below. If you haven’t, you will need to speak to your manager / mentor to arrange when you will complete it.  You will also need to complete the Health and Safety booklet and Substance Misuse booklet given to you by your Assessor on day 1.</div>
							</div>
							<div class="row">
								<div class="col-sm-12 table-responsive">
									<table class="table table-bordered table-striped">
										<tr><th>Learning</th><th><?php echo $wb->savers_or_sp == 'savers' ? 'Workbooks/First steps' : 'Workbook/IPad/In-store activity'; ?></th><th>Date completed</th></tr>
										<?php
										$items = WBHSAndSecurity::getLearningJourneyItems($wb->savers_or_sp);
										$j = 0;
										foreach($items AS $i)
										{
											$key = 'DC'.++$j;
											echo '<tr>';
											echo '<td>' . $i . '</td>';
											echo $wb->savers_or_sp == 'savers' ? '<td>iPad</td>' : '<td>iPad</td>';
											echo '<td>' . HTML::datebox($key, $answers->Journey->$key->__toString()) . '</td>';
											echo '</tr>';
										}
										?>
									</table>
								</div>
							</div>
							<p><br></p>
							<div class="row"><div class="col-sm-12"><div class="pull-right"><?php echo HTML::renderWorkbookIcons('', true); ?></div></div></div>
							<?php echo $wb->getPageBottomLine(); ?>
						</div>
						<!--.page 2 ends-->

						<h1>H&S and Security</h1>

						<div class="step-content">
							<?php echo $wb->getPageTopLine(); ?>
							<div class="row"><div class="col-sm-12"><div class="pull-right">
								<?php
								if($feedback->Responsibilities->Status->__toString() == 'NA')
									echo HTML::renderWorkbookIcons('Responsibilities', false, 'btn-danger');
								else
									echo HTML::renderWorkbookIcons('Responsibilities', false, 'btn-success');
								?>
							</div></div></div>
							<div class="row">
								<div class="col-sm-12">
									<p>In order for everyone to know what they have to do in relation to health and safety there are laws and regulations in place, which describe the responsibilities of all concerned</p>
									<p>The first piece of legislation you need to know about is the Health and Safety at Work Act. This Act of Parliament has been in existence for around 40 years and forms the basis of all the health and safety regulations that have been introduced since that time.</p>
									<p>On the company intranet and “The Hub” all the health & safety procedures applicable to the business have been documented to ensure we comply with the legislation and regulations. If we follow these procedures we will have a safe workplace for our colleagues and a safe environment for our customers to have a great shopping experience. On your health and safety noticeboard you will find several health and safety posters. One of those, shown here, is the Health and Safety at Work Law poster which details your responsibilities whilst at work.</p>
								</div>
							</div>
							<div class="row">
								<div class="col-sm-4">
									<img src="module_eportfolio/assets/images/wb11_pg20_img3.png" class="img-responsive pull-right" />
								</div>
								<div class="col-sm-8">
									<p class="text-center">Locate your Health and Safety Law poster.</p>
									<p class="text-center">List three responsibilities of employers and employees in the table below:</p>
									<div class="table-responsive">
										<table class="table table-bordered" <?php echo $feedback->Responsibilities->Status->__toString() == 'A'?'style="pointer-events:none;"':''; ?>>
											<tr><th>Responsibilities of Emplopyer</th><th>Responsibilities of Employee</th></tr>
											<?php
											$Responsibilities = $answers->Responsibilities;
											for($i = 1; $i <= 3; $i++)
											{
												$key = 'Set'.$i;
												echo '<tr>';
												echo '<td><textarea rows="5" name="Employer'.$i.'" style="width: 100%;">'.$Responsibilities->$key->Employer->__toString().'</textarea> </td>';
												echo '<td><textarea rows="5" name="Employee'.$i.'" style="width: 100%;">'.$Responsibilities->$key->Employee->__toString().'</textarea> </td>';
												echo '</tr>';
											}
											?>
										</table>
									</div>
									<p>There are regulations about many aspects of health and safety that are relevant to activities on retail premises. These include regulations in areas such as:</p>
									<ul style="margin-left: 15px;">
										<li>Hazardous substances</li>
										<li>Manual handling</li>
										<li>Fire safety</li>
										<li>Accident and emergencies</li>
										<li>Risk assessment</li>
									</ul>
								</div>
							</div>
							<?php if($wb->id != '' && $_SESSION['user']->type == User::TYPE_ASSESSOR && ($wb->wb_status == Workbook::STATUS_LEARNER_COMPLETED || $wb->wb_status == Workbook::STATUS_BEING_CHECKED || $wb->wb_status == Workbook::STATUS_IV_REJECTED)) {?>
							<div class="row well">
								<div class="col-sm-12">
									<div class="box box-success box-solid">
										<div class="box-header with-border"><h3 class="box-title">Assessor Feedback</h3></div>
										<div class="box-body assessorFeedback" <?php echo $feedback->Responsibilities->Status->__toString() == 'A'?'style="pointer-events:none;"':''; ?>>
											<div class="form-group">
												<label for="status_Responsibilities" class="col-sm-12 control-label">Status:</label>
												<div class="col-sm-12">
													<?php
													echo HTML::selectChosen('status_Responsibilities', $answer_status, $feedback->Responsibilities->Status->__toString() == 'A'?$feedback->Responsibilities->Status->__toString():'', false, true); ?>
												</div>
											</div>
											<div class="form-group">
												<label for="comments_Responsibilities" class="col-sm-12 control-label">Comments:</label>
												<div class="col-sm-12">
													<textarea name="comments_Responsibilities" rows="7" style="width: 100%;"><?php echo $feedback->Responsibilities->Comments->__toString(); ?></textarea>
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
								if($feedback->Responsibilities->Status->__toString() == 'NA')
									echo HTML::renderWorkbookIcons('Responsibilities', false, 'btn-danger');
								else
									echo HTML::renderWorkbookIcons('Responsibilities', false, 'btn-success');
								?>
							</div></div></div>
							<?php echo $wb->getPageBottomLine(); ?>
						</div>
						<!--.page 3 ends-->

						<h1>H&S and Security</h1>

						<div class="step-content">
							<?php echo $wb->getPageTopLine(); ?>
							<div class="row"><div class="col-sm-12"><div class="pull-right">
								<?php
								if($feedback->Hazards->Status->__toString() == 'NA')
									echo HTML::renderWorkbookIcons('Hazards', false, 'btn-danger');
								else
									echo HTML::renderWorkbookIcons('Hazards', false, 'btn-success');
								?>
							</div></div></div>
							<div class="row"><div class="col-sm-12"><div class="pull-right"><?php echo HTML::renderWorkbookIcons('', true); ?></div></div></div>
							<div class="row">
								<div class="col-sm-12">
									<p class="text-bold">Hazardous Substances</p>
									<p>To protect people at work from hazardous substances the government introduced the Control of Substances Hazardous to Health Regulations 2002, often referred to as COSHH.</p>
									<p>You will be able to recognise hazardous substances (covered by COSHH regulations) because they all have a label showing one of the following hazard warning symbols:</p>
									<img class="img-responsive" src="module_eportfolio/assets/images/wb_r01_pg4_img1.png" />
									<p class="text-bold"><img class="img-responsive pull-left" src="module_eportfolio/assets/images/wb2_img1.png" /> There are a few other COSHH symbols that are not as common and you may see them in and around the buildings.</p>
									<p>Research COSHH and complete the activity below which is to draw examples of symbols and identify the hazards. <i>Don’t forget to record which websites you did your research on in the independent learning section at the end of this module.</i></p>
									<div class="table-responsive">
										<table class="table table-bordered">
											<tr><th>COSHH SYMBOL</th><th>COSHH SYMBOL</th><th>COSHH SYMBOL</th></tr>
											<tr>
												<td><textarea name="COSHH1" style="width: 100%;"><?php echo $answers->Hazards->COSHH1->__toString(); ?></textarea> </td>
												<td><textarea name="COSHH2" style="width: 100%;"><?php echo $answers->Hazards->COSHH2->__toString(); ?></textarea> </td>
												<td><textarea name="COSHH3" style="width: 100%;"><?php echo $answers->Hazards->COSHH3->__toString(); ?></textarea> </td>
											</tr>
											<tr><td>Hazard is?</td><td>Hazard is?</td><td>Hazard is?</td></tr>
										</table>
									</div>
								</div>
							</div>
							<div class="row">
								<div class="col-sm-6">
									<p><b>COSHH</b> regulations give <b>employers</b> responsibilities to:</p>
									<p><b>Make health risk assessments</b> - Risk assessments will identify the precautions to be taken when dealing with hazardous substances and the action to be taken if someone comes into contact with substances.</p>
									<p><b>Prevent or control exposure to hazardous substances</b> - Employers should aim to prevent any exposure to hazardous substances but if that’s not “reasonably practicable” they should control any exposure.</p>
								</div>
								<div class="col-sm-6">
									<p><b>Carry out monitoring activities</b> - Monitoring could take form of exposure monitoring, health checks and environmental checks.</p>
									<p><b>Maintain control measures</b> - Employers should make sure that the control measures they identify through risk assessment are implemented by everyone.</p>
									<p><b>Train employees</b> - Training should be given to all employees so they can protect themselves from harm.</p>
								</div>
							</div>

							<?php if($wb->id != '' && $_SESSION['user']->type == User::TYPE_ASSESSOR && ($wb->wb_status == Workbook::STATUS_LEARNER_COMPLETED || $wb->wb_status == Workbook::STATUS_BEING_CHECKED || $wb->wb_status == Workbook::STATUS_IV_REJECTED)) {?>
							<div class="row well">
								<div class="col-sm-12">
									<div class="box box-success box-solid">
										<div class="box-header with-border"><h3 class="box-title">Assessor Feedback</h3></div>
										<div class="box-body assessorFeedback" <?php echo $feedback->Hazards->Status->__toString() == 'A'?'style="pointer-events:none;"':''; ?>>
											<div class="form-group">
												<label for="status_Hazards" class="col-sm-12 control-label">Status:</label>
												<div class="col-sm-12">
													<?php
													echo HTML::selectChosen('status_Hazards', $answer_status, $feedback->Hazards->Status->__toString() == 'A'?$feedback->Hazards->Status->__toString():'', false, true); ?>
												</div>
											</div>
											<div class="form-group">
												<label for="comments_Hazards" class="col-sm-12 control-label">Comments:</label>
												<div class="col-sm-12">
													<textarea name="comments_Hazards" rows="7" style="width: 100%;"><?php echo $feedback->Hazards->Comments->__toString(); ?></textarea>
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
								if($feedback->Hazards->Status->__toString() == 'NA')
									echo HTML::renderWorkbookIcons('Hazards', false, 'btn-danger');
								else
									echo HTML::renderWorkbookIcons('Hazards', false, 'btn-success');
								?>
							</div></div></div>

							<?php echo $wb->getPageBottomLine(); ?>
						</div>
						<!--.page 4 ends-->

						<h1>H&S and Security</h1>

						<div class="step-content">
							<?php echo $wb->getPageTopLine(); ?>
							<div class="row"><div class="col-sm-12"><div class="pull-right"><?php echo HTML::renderWorkbookIcons('', true); ?></div></div></div>
							<div class="row">
								<div class="col-sm-12">
									<p class="text-bold">As an employee you have responsibilities in relation to hazardous substances you must:</p>
									<ul style="margin-left: 15px; margin-bottom: 15px;">
										<li>Know what hazardous substances are present where you work</li>
										<li>Recognise the information warning you about hazardous substances on labels and hazardous substance assessments completed by your employer</li>
										<li>Know about the precautions identified by your employers risk assessments</li>
										<li>Use any protective equipment as instructed</li>
										<li>Use hazardous substances for their intended purpose only</li>
										<li>Store any substances in their original containers</li>
										<li>Follow training and instructions provided by your employer</li>
										<li>Report any problems immediately</li>
									</ul>
									<img class="img-responsive pull-right" src="module_eportfolio/assets/images/wb_r01_pg5_img1.png" />
									<p class="text-bold">Manual Handling</p>
									<p>In retail you handle goods from the moment they are delivered to the store until the moment they are bought and taken away by the customer.</p>
									<p>Other manual handling activities may involve lifting and moving fixtures, fittings and waste.</p>

								</div>
							</div>
							<div class="row">
								<div class="col-sm-6">
									<p>Did you know that manual handling activities are responsible for a large percentage of all injuries in the workplace?</p>
									<p>It is vital that you move and handle goods safely. To do this you must be able to:</p>
									<ul style="margin-left: 15px; margin-bottom: 15px;">
										<li>Understand  manual handling guidelines and follow them</li>
										<li>Know how to and check all equipment before use,  to ensure it is fit for purpose</li>
										<li>Understand the risks associated with manual handling and risk assess each load before moving</li>
									</ul>
								</div>
								<div class="col-sm-6">
									<p class="text-bold"> Manual handling guidelines</p>
									<ul style="margin-left: 15px; margin-bottom: 15px;">
										<li>There are techniques which make lifting and carrying safe; before you lift anything, plan how you are going to carry it</li>
										<li>Assess the load</li>
										<li>Ask for help if you need it</li>
										<li>Remove any obstructions between the load and where you are taking it</li>
										<li>Where appropriate lift in stages</li>
									</ul>
								</div>
								<div class="col-sm-12">
									<p class="text-bold"> Using work equipment</p>
									<p>Before using any equipment or materials you should be trained how to do so. This training may include:</p>
									<ul style="margin-left: 15px; margin-bottom: 15px;">
										<li>Checking equipment is safe before using it. If it is faulty, report it immediately and label the equipment to prevent anyone else using it before it is repaired</li>
										<li>Use the correct equipment for the task. For example, use step ladders rather than foot stools to reach high racking</li>
										<li>Use all equipment in the right way. Never use equipment until you have been trained to do so</li>
										<li>Check the safe maximum load of any equipment before using it to move anything heavy</li>
										<li>Wear all protective equipment provided. If it is faulty, report it</li>
									</ul>
								</div>
							</div>
							<p><br></p>
							<div class="row"><div class="col-sm-12"><div class="pull-right"><?php echo HTML::renderWorkbookIcons('', true); ?></div></div></div>
							<?php echo $wb->getPageBottomLine(); ?>
						</div>
						<!--.page 5 ends-->

						<h1>H&S and Security</h1>

						<div class="step-content">
							<?php echo $wb->getPageTopLine(); ?>
							<div class="row"><div class="col-sm-12"><div class="pull-right"><?php echo HTML::renderWorkbookIcons('', true); ?></div></div></div>
							<div class="row">
								<div class="col-sm-6">
									<p class="text-bold">Fire safety</p>
									<p>If you discover a fire, the first action you should always take is to raise the alarm. Fire can spread very quickly. The earlier you can alert everyone about the fire, the better the chance of reducing the harm the fire might cause.</p>
									<img class="img-responsive center-block" src="module_eportfolio/assets/images/wb_r01_pg6_img1.png" />
									<p class="text-bold">If you discover a fire:</p>
									<ul style="margin-left: 15px; margin-bottom: 15px;">
										<li>Raise the alarm by breaking the glass on the fire alarm points</li>
										<li>Raise the alarm by shouting ‘fire’ if there is no alarm system</li>
									</ul>
									<img class="img-responsive center-block" src="module_eportfolio/assets/images/wb_r01_pg6_img2.png" />
									<p class="text-bold">If you hear the fire alarm:</p>
									<ul style="margin-left: 15px; margin-bottom: 15px;">
										<li>Leave the building by the nearest exit guiding customers who need help</li>
										<li>Do not use lifts</li>
										<li>Do not stop to collect belongings</li>
										<li>Go to the assembly point</li>
										<li>Report to the fire marshal about the location and nature of the fire</li>
									</ul>
									<img class="img-responsive center-block" src="module_eportfolio/assets/images/wb_r01_pg6_img3.png" />
								</div>
								<div class="col-sm-6">
									<p class="text-bold">Accident and emergencies</p>
									<p>In the event of a bomb alert businesses should have a security plan which provides details of how to respond to:</p>
									<ul style="margin-left: 15px; margin-bottom: 15px;">
										<li>A bomb threat (e.g. a telephone call)</li>
										<li>The discovery of a suspicious package</li>
									</ul>
									<p>The plan should include details of how the premises will be searched and evacuated.</p>
									<p>There are seven key instructions that apply to most incidents of this kind:</p>
									<ul style="margin-left: 15px; margin-bottom: 15px;">
										<li>Do not touch suspicious items</li>
										<li>Do not use mobile phones or radios in the immediate vicinity</li>
										<li>Move everyone away to a safe distance</li>
										<li>Prevent others from approaching</li>
										<li>Communicate safely with colleagues and customers</li>
										<li>Notify the police</li>
										<li>Brief the police if you discovered the item or received the threat</li>
									</ul>
									<p>In the event of acute illness or accident your prompt action could make the difference between life and death.</p>
									<p>In most workplaces there will be first aiders who have been trained to deal with the initial treatment of illness and injury.</p>
									<p>If first aiders are not available then an appointed person will take charge of the incident.</p>
									<ul style="margin-left: 15px; margin-bottom: 15px;">
										<li>Contact the first aider or appointed person immediately so that appropriate treatment can be given without delay</li>
										<li>Complete details in the accident book</li>
									</ul>
								</div>
							</div>

							<p><br></p>
							<div class="row"><div class="col-sm-12"><div class="pull-right"><?php echo HTML::renderWorkbookIcons('', true); ?></div></div></div>
							<?php echo $wb->getPageBottomLine(); ?>
						</div>
						<!--.page 6 ends-->

						<h1>H&S and Security</h1>

						<div class="step-content">
							<?php echo $wb->getPageTopLine(); ?>
							<div class="row"><div class="col-sm-12"><div class="pull-right">
								<?php
								if($feedback->WorkplaceSafety->Status->__toString() == 'NA')
									echo HTML::renderWorkbookIcons('WorkplaceSafety', false, 'btn-danger');
								else
									echo HTML::renderWorkbookIcons('WorkplaceSafety', false, 'btn-success');
								?>
							</div></div></div>
							<div class="row">
								<div class="col-sm-12">
									<p class="text-bold">Workplace safety/risk assessments</p>
									<p>Everyone at work needs to be aware of the hazards and risks that are present in the workplace so that they can take the appropriate action to avoid the risks. Before you can properly identify hazards and risks you need to understand what the words hazard and risk really mean.</p>
								</div>
								<div class="col-sm-6">
									<p class="text-bold">What is a hazard?</p>
									<ul style="margin-left: 15px; margin-bottom: 15px;">
										<li>A hazard is anything with the potential to cause harm</li>
									</ul>
									<p class="text-bold">What is a risk?</p>
									<ul style="margin-left: 15px; margin-bottom: 15px;">
										<li>A risk is the likelihood of harm from the hazard</li>
									</ul>
									<p class="text-bold">Typical hazards in retail premises include:</p>
									<ul style="margin-left: 15px; margin-bottom: 15px;">
										<li>Moving and handling activities</li>
										<li>Wet or untidy floors</li>
										<li>Steps, ladders and equipment</li>
										<li>Fixtures and fittings</li>
										<li>Hazardous products</li>
									</ul>
									<p>Part of your responsibility includes looking out for hazards and doing the right thing when hazards are noticed.</p>

								</div>
								<div class="col-sm-6">
									<p>Your employer is required to conduct risk assessments of the workplace and the activities that are undertaken in it.</p>
									<p>The first step of a risk assessment is to look for hazards. When hazards are discovered, through the risk assessment process, the risks associated with them are assessed and precautions are taken to eliminate or, at least control the risks.</p>
									<p>Although your employer’s risk assessments are part of an ongoing process, which reviews hazards on a regular basis, this does not mean that you do not have to be on the lookout for new hazards as you go about your work:</p>
									<ul style="margin-left: 15px; margin-bottom: 15px;">
										<li>Always be vigilant</li>
										<li>Look for things that may cause harm to you, your colleagues or customers</li>
										<li>Do something about it when you find a hazard</li>
									</ul>
								</div>
								<div class="col-sm-12">
									<p class="text-bold"><img class="img-responsive pull-left" src="module_eportfolio/assets/images/wb2_img1.png" /> Complete the fact finding activity below </p>
									<div class="table-responsive">
										<table class="table table-bordered"  <?php echo $feedback->WorkplaceSafety->Status->__toString() == 'A'?'style="pointer-events:none;"':''; ?>>
											<tr><th style="width: 50%;">Question</th><th>Answer</th></tr>
											<tr><th>Where would you find your accident book?</th><td><textarea name="WorkplaceSafetyQ1" style="width: 100%;"><?php echo $answers->WorkplaceSafety->Q1->__toString(); ?></textarea> </td></tr>
											<tr><th>Who is the stores’ first aider or appointed person?</th><td><textarea name="WorkplaceSafetyQ2" style="width: 100%;"><?php echo $answers->WorkplaceSafety->Q2->__toString(); ?></textarea> </td></tr>
											<tr><th>Where is the stores’ evacuation meeting place?</th><td><textarea name="WorkplaceSafetyQ3" style="width: 100%;"><?php echo $answers->WorkplaceSafety->Q3->__toString(); ?></textarea> </td></tr>
											<tr><th>Where is your first aid box located?</th><td><textarea name="WorkplaceSafetyQ4" style="width: 100%;"><?php echo $answers->WorkplaceSafety->Q4->__toString(); ?></textarea> </td></tr>
											<tr><th>Where would you find details in your store of all health and safety policies and procedures?</th><td><textarea name="WorkplaceSafetyQ5" style="width: 100%;"><?php echo $answers->WorkplaceSafety->Q5->__toString(); ?></textarea> </td></tr>
											<tr><th>List the locations of the stores’ fire extinguishers</th><td><textarea name="WorkplaceSafetyQ6" style="width: 100%;"><?php echo $answers->WorkplaceSafety->Q6->__toString(); ?></textarea> </td></tr>
											<tr><th>List the locations of the stores fire alarm points (break glass)</th><td><textarea name="WorkplaceSafetyQ7" style="width: 100%;"><?php echo $answers->WorkplaceSafety->Q7->__toString(); ?></textarea> </td></tr>
											<tr><th>List two products in your store that have a COSHH symbol displayed on them</th><td><textarea name="WorkplaceSafetyQ8" style="width: 100%;"><?php echo $answers->WorkplaceSafety->Q8->__toString(); ?></textarea> </td></tr>
										</table>
									</div>
									<p class="text-bold"><img class="pull-left" src="module_eportfolio/assets/images/wb2_pg18_img1.png" /> &nbsp; For more information visit <a href="http://www.hse.gov.uk/workers/" target="_blank">www.hse.gov.uk/workers/</a></p>

								</div>
							</div>

							<?php if($wb->id != '' && $_SESSION['user']->type == User::TYPE_ASSESSOR && ($wb->wb_status == Workbook::STATUS_LEARNER_COMPLETED || $wb->wb_status == Workbook::STATUS_BEING_CHECKED || $wb->wb_status == Workbook::STATUS_IV_REJECTED)) {?>
							<div class="row well">
								<div class="col-sm-12">
									<div class="box box-success box-solid">
										<div class="box-header with-border"><h3 class="box-title">Assessor Feedback</h3></div>
										<div class="box-body assessorFeedback" <?php echo $feedback->WorkplaceSafety->Status->__toString() == 'A'?'style="pointer-events:none;"':''; ?>>
											<div class="form-group">
												<label for="status_WorkplaceSafety" class="col-sm-12 control-label">Status:</label>
												<div class="col-sm-12">
													<?php
													echo HTML::selectChosen('status_WorkplaceSafety', $answer_status, $feedback->WorkplaceSafety->Status->__toString() == 'A'?$feedback->WorkplaceSafety->Status->__toString():'', false, true); ?>
												</div>
											</div>
											<div class="form-group">
												<label for="comments_WorkplaceSafety" class="col-sm-12 control-label">Comments:</label>
												<div class="col-sm-12">
													<textarea name="comments_WorkplaceSafety" rows="7" style="width: 100%;"><?php echo $feedback->WorkplaceSafety->Comments->__toString(); ?></textarea>
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
								if($feedback->WorkplaceSafety->Status->__toString() == 'NA')
									echo HTML::renderWorkbookIcons('WorkplaceSafety', false, 'btn-danger');
								else
									echo HTML::renderWorkbookIcons('WorkplaceSafety', false, 'btn-success');
								?>
							</div></div></div>
							<?php echo $wb->getPageBottomLine(); ?>
						</div>
						<!--.page 7 ends-->

						<h1>H&S and Security</h1>

						<div class="step-content">
							<?php echo $wb->getPageTopLine(); ?>
							<div class="row"><div class="col-sm-12"><div class="pull-right"><?php echo HTML::renderWorkbookIcons('', true); ?></div></div></div>
							<div class="row">
								<div class="col-sm-12">
									<p class="text-bold">This section is about Security in the workplace and what you need to know and do.</p>
									<p>Security is about protecting your store, stock, colleagues, customers and cash.  In your role you will deal with many areas of security.  It is important to understand your authority limits (what you can and cannot do) and are able to report risks to your Manager that are outside of your authority limits.</p>
									<p>It is also important to follow company policy and procedures when dealing with security at work. Effective security requires all of the team to work together.  By being alert and vigilant at all times you and the rest of the team will contribute effectively to managing security.  By monitoring the behaviour of others and your working environment, you will be able to identify risks to security.</p>
									<p>Once a risk is identified, the next steps are to prevent or minimise them. You need to report risks immediately to an appropriate person (Team Leader, Assistant Manager or Store Manager).</p>
									<img class="img-responsive center-block" src="module_eportfolio/assets/images/wb_r01_pg8_img1.png" />
									<p class="text-bold">Throughout this section you will become aware of:</p>
									<ul style="margin-left: 15px; margin-bottom: 15px;">
										<li>High risk areas / times</li>
										<li>Protecting stock, cash, your store and people</li>
										<li>Risks to people, including violent or abusive behaviour and the reporting of incidents</li>
										<li>Risks to stock, including damage and theft</li>
										<li>Risks to your store, including vandalism and burglary</li>
									</ul>
								</div>
								<div class="col-sm-12">
									<p class="text-bold"><img src="module_eportfolio/assets/images/wb4_pg2_img1.png" /> Learner journey / Visit plan</p>
								</div>
								<div class="col-sm-12">
									<p>Before you start this section please ensure you have had completed all of the training on the iPad detailed below. If you haven’t you will need to speak to your manager / mentor to arrange when you will complete it.</p>
									<p><br></p>
									<div class="row">
										<div class="col-sm-12 table-responsive">
											<table class="table table-bordered table-striped">
												<tr><th>Learning</th><th>Workbook/IPad/In-store activity</th><th>Date completed</th></tr>
												<?php
												$items = array('Security','Shrinkage awareness','Violence and aggression');
												$j = 0;
												foreach($items AS $i)
												{
													$key = 'DC'.++$j;
													echo '<tr>';
													echo '<td>' . $i . '</td>';
													echo '<td>iPad</td>';
													echo '<td>' . HTML::datebox('Security'.$key, $answers->Security->$key->__toString()) . '</td>';
													echo '</tr>';
												}
												?>
											</table>
										</div>
									</div>
								</div>
							</div>
							<p><br></p>
							<div class="row"><div class="col-sm-12"><div class="pull-right"><?php echo HTML::renderWorkbookIcons('', true); ?></div></div></div>
							<?php echo $wb->getPageBottomLine(); ?>
						</div>
						<!--.page 8 ends-->

						<h1>H&S and Security</h1>

						<div class="step-content">
							<?php echo $wb->getPageTopLine(); ?>
							<div class="row"><div class="col-sm-12"><div class="pull-right"><?php echo HTML::renderWorkbookIcons('', true); ?></div></div></div>
							<div class="row">
								<div class="col-sm-6">
									<p class="text-bold">Staying Aware</p>
									<p>The biggest positive contribution you can make is being alert and vigilant at all times.  By watching out for suspicious behaviour you can identify any security risks.</p>
									<p>This is just the first step; you must then take immediate action and report it to the appropriate person.  This would usually be your Store Manager. If you are unsure about anything, please refer to the policies and procedures manual or ask your line manager.</p>
									<p class="text-bold">High Risk Times</p>
									<p>There are high risk areas and times in your store and being aware of these will help you to minimise shrinkage in your store.  The high risk times will be individual to your store and may be different to other stores.</p>
									<img class="img-responsive center-block" src="module_eportfolio/assets/images/wb_r01_pg9_img1.png" />
									<p class="text-bold">Stock Protection</p>
									<p class="text-bold">Damages</p>
									<p>No business can afford to lose or damage stock. It is very important that the stock is protected from the moment it enters our stores, both on the shop floor and in the warehouse.</p>
									<p>Stock may be at risk of damage while it is being stored.  To prevent this from happening simple guidelines must be followed.</p>
									<p>For example, ensure that stock is not stored in totes which can cause breakage and leakage and heavy stock is not stored on top of fragile products.</p>
								</div>
								<div class="col-sm-6">
									<p>It is also important to separate your damaged stock from each other. That which may be resold at a reduced price due to damage should be separated from unsaleable damages. This is to prevent any unsaleable damages leaking or causing additional damage to the saleable stock.</p>
									<p>Stock could also get damaged whilst it is being moved.  Throwing boxes of stock around could lead to it getting ripped, dented, or crack and leak.</p>
									<p>Also think about where to display different types of stock as a wrongly located product could end up getting damaged.</p>
									<p class="text-bold">Theft</p>
									<p>Retail businesses face a range of security risks resulting from criminal activity. Theft of stock is one of the main types of criminal activity encountered in stores.</p>
									<img class="img-responsive center-block" src="module_eportfolio/assets/images/wb_r01_pg9_img2.png" />
									<p>Customer theft accounts for at least 82% of all retail crime, and because many shoplifting incidents are undetected, this figure may be even higher.</p>
									<p>Shoplifters feel that it is acceptable to steal from shops as they see it as a victimless crime. Shoplifting is not a victimless crime, as you will see when you read about the effects that retail crime has on businesses and their staff.</p>
									<p>One of the worst kinds of retail crime is theft by employees. Retail employers put trust in their staff and sadly this is sometimes abused. Employee theft may take the form of stealing merchandise, stealing cash or fraudulent activity.</p>
								</div>
							</div>
							<p><br></p>
							<div class="row"><div class="col-sm-12"><div class="pull-right"><?php echo HTML::renderWorkbookIcons('', true); ?></div></div></div>
							<?php echo $wb->getPageBottomLine(); ?>
						</div>
						<!--.page 9 ends-->

						<h1>H&S and Security</h1>

						<div class="step-content">
							<?php echo $wb->getPageTopLine(); ?>
							<div class="row"><div class="col-sm-12"><div class="pull-right"><?php echo HTML::renderWorkbookIcons('', true); ?></div></div></div>
							<div class="row">
								<div class="col-sm-12">
									<p><b>Robbery</b> can be defined as theft involving threat or force. Whilst robberies account for a much smaller proportion of retail crime than customer or employee theft, the impact that robberies have on staff and customers is very serious. Criminals who are prepared to commit robbery are likely to target very high value goods or large sums of cash, often protected by security systems.</p>
									<img class="img-responsive pull-right" src="module_eportfolio/assets/images/wb_r01_pg10_img1.png" />
									<p>Any theft is a serious criminal offence.  Stock can be stolen while it is being stored, moved or displayed on the shop floor. There are also certain types of stock that are at a greater risk of theft, being aware of these lines will minimise shrinkage in your store. You can put things in place in your store to help minimise the risk.</p>
									<p>For example:</p>
									<ul style="margin-left: 15px; margin-bottom: 15px;">
										<li>High risk items such as cosmetics and fragrance may be stored in a locked area</li>
										<li>High value stock must be security tagged</li>
										<li>High value stock should be displayed where it is very visible to all</li>
										<li>Ensure high visibility stickers are on key lines in store</li>
									</ul>
									<p class="text-bold">Cash & cards</p>
									<p class="text-bold">Cash</p>
									<p>Cash is even more vulnerable to theft than stock. Criminals stealing stock have to sell it in order to raise cash and in doing so rarely get what products are worth when they sell them illegally.</p>
									<p>If they can steal cash they get what might be described as full value. The two main threats to cash at the payment point are theft such as a till snatch or robbery, where force or threat is used to steal. Another way that is used to steal is by using fake money.</p>
									<p>Anyone thinking about committing any of these crimes knows the risks and will be more likely to steal from you if they think they will come away with a lot of cash. For this reason, retail businesses must take all precautions to secure cash whilst it is on their premises</p>
									<p class="text-bold">The main actions taken are:</p>
									<ul style="margin-left: 15px; margin-bottom: 15px;">
										<li>Follow your company procedures for cash handling at all times</li>
										<li>Ensure you use the counter cache as per company policy</li>
										<li>Never exceed the limit on notes kept in the till drawer</li>
										<li>Only open your till drawer for the minimum time to serve each customer</li>
										<li>Make sure your payment point is totally secure before you leave the area</li>
										<li>Always use a security pen/machine to check the authenticity of note</li>
									</ul>
									<img class="img-responsive pull-right" src="module_eportfolio/assets/images/wb_r01_pg10_img2.png" />
								</div>
							</div>
							<p><br></p>
							<div class="row"><div class="col-sm-12"><div class="pull-right"><?php echo HTML::renderWorkbookIcons('', true); ?></div></div></div>
							<?php echo $wb->getPageBottomLine(); ?>
						</div>
						<!--.page 10 ends-->

						<h1>H&S and Security</h1>

						<div class="step-content">
							<?php echo $wb->getPageTopLine(); ?>
							<div class="row"><div class="col-sm-12"><div class="pull-right"><?php echo HTML::renderWorkbookIcons('', true); ?></div></div></div>
							<div class="row">
								<div class="col-sm-12">
									<p class="text-bold">Cards</p>
									<p>E-crime and fraud are a significant threat to a retail business. 80% of retailers say they have seen this type of crime rise in recent years and after customer theft, it is the most damaging type of criminal activity faced by retailers. This is partly due to the increase in e-commerce (online shopping).</p>
									<p>The main types of fraud being:</p>
									<ul style="margin-left: 15px; margin-bottom: 15px;">
										<li>Debit and credit card fraud</li>
										<li>Account credit fraud</li>
										<li>Refund fraud</li>
										<li>Voucher and gift card fraud</li>
										<li>Identity fraud- commonly found with internet shopping</li>
									</ul>
									<img class="img-responsive pull-right" src="module_eportfolio/assets/images/wb_r01_pg11_img1.png" />
									<p>Retail businesses can take steps to reduce this crime by following the policies and procedures for:</p>
									<ul style="margin-left: 15px; margin-bottom: 15px;">
										<li>Processing refunds</li>
										<li>Processing  voucher and gift card transactions</li>
										<li>Processing click and collect orders</li>
										<li>Processing card sales</li>
									</ul>
								</div>
								<div class="col-sm-6">
									<p class="text-bold">Violent and abusive behaviour</p>
									<img class="img-responsive pull-left" src="module_eportfolio/assets/images/wb_r01_pg11_img2.png" />
									<p>People are a vital asset in your store – without them profits cannot be made. Your store is a busy place so there will be a range of people in your store at any one time including team members, contractors/suppliers and customers.</p>
									<p>It is vital that people feel secure whilst they are in your store.  Any violent or abusive behaviour that occurs will make people feel threatened and they may not return to your store.</p>
									<p>The different types of behaviour could include:</p>
									<ul style="margin-left: 15px; margin-bottom: 15px;">
										<li>Customers being rude – shouting at a team member</li>
										<li>Customers losing their temper</li>
										<li>Team members arguing amongst themselves</li>
										<li>Physical violence</li>
									</ul>
								</div>
								<div class="col-sm-6">
									<p>Always remember to never take action unless you are sure it’s the right thing to do.  You must consider your own personal safety at all times.</p>
									<p>Whilst every situation is different some basic guidelines can be followed.</p>
									<p class="text-bold">Do:</p>
									<ul style="margin-left: 15px; margin-bottom: 15px;">
										<li>Follow company procedures</li>
										<li>Always report any incidents to the appropriate person</li>
										<li>Keep calm and think before you take any action</li>
									</ul>
									<p class="text-bold">Don’t:</p>
									<ul style="margin-left: 15px; margin-bottom: 15px;">
										<li>Put yourself at personal risk</li>
										<li>Put others at risk</li>
										<li>Be drawn into an argument</li>
									</ul>
								</div>
							</div>
							<p><br></p>
							<div class="row"><div class="col-sm-12"><div class="pull-right"><?php echo HTML::renderWorkbookIcons('', true); ?></div></div></div>
							<?php echo $wb->getPageBottomLine(); ?>
						</div>
						<!--.page 11 ends-->

						<h1>H&S and Security</h1>

						<div class="step-content">
							<?php echo $wb->getPageTopLine(); ?>
							<div class="row"><div class="col-sm-12"><div class="pull-right">
								<?php
								if($feedback->Security->Status->__toString() == 'NA')
									echo HTML::renderWorkbookIcons('Security', false, 'btn-danger');
								else
									echo HTML::renderWorkbookIcons('Security', false, 'btn-success');
								?>
							</div></div></div>
							<div class="row">
								<div class="col-sm-12">
									<p class="text-bold">Terrorist threats</p>
									<p>Whilst the threat of terrorism may seem unlikely you must ensure everyone is protected from the risks as much as possible.  Always be alert and vigilant and watch out for any suspicious behaviour. Always ask for help as terrorism, although rare, requires specially trained people to ensure the safety of everyone. Our customers and team members are vital to our business and if they feel at risk in any way they won’t stay loyal and will go elsewhere.</p>
									<p class="text-bold">Store protection/vandalism and burglary</p>
									<img class="img-responsive pull-right" src="module_eportfolio/assets/images/wb_r01_pg12_img1.png" />
									<p>Your store must be protected during and after work hours. Stores are protected in different ways depending on location, size and layout.</p>
									<p>Methods of protection include:</p>
									<ul style="margin-left: 15px; margin-bottom: 15px;">
										<li>Security shutters
										<li>CCTV
										<li>Store Guards / Detectives
										<li>Radio link
										<li>Store alarm and panic button
									</ul>
									<p>It is vital that any issues or concerns are reported to your line manager. Any reports should be clear and concise to ensure they can be passed to the relevant department.</p>

								</div>
								<div class="col-sm-12">
									<div class="table-responsive">
										<table class="table table-bordered"  <?php echo $feedback->Security->Status->__toString() == 'A'?'style="pointer-events:none;"':''; ?>>
											<tr><th style="width: 60%;">Question</th><th>Answer</th></tr>
											<tr><td>What are the high risk times in your store?</td><td><textarea name="SecurityQ1" style="width: 100%;"><?php echo $answers->Security->Q1->__toString(); ?></textarea> </td></tr>
											<tr><td>List 3 high risk areas/departments in your store:</td><td><textarea name="SecurityQ2" style="width: 100%;"><?php echo $answers->Security->Q2->__toString(); ?></textarea> </td></tr>
											<tr><td>To whom would you report any suspicious behaviour?</td><td><textarea name="SecurityQ3" style="width: 100%;"><?php echo $answers->Security->Q3->__toString(); ?></textarea> </td></tr>
											<tr><td>List 3 ways you can minimise damages:</td><td><textarea name="SecurityQ4" style="width: 100%;"><?php echo $answers->Security->Q4->__toString(); ?></textarea> </td></tr>
											<tr><td>What is the cash limit in any till drawer?</td><td><textarea name="SecurityQ5" id="SecurityQ5" style="width: 100%;"><?php echo $answers->Security->Q5->__toString(); ?></textarea> </td></tr>
											<tr><td>List 3 ways you can deter product theft:</td><td><textarea name="SecurityQ6" style="width: 100%;"><?php echo $answers->Security->Q6->__toString(); ?></textarea> </td></tr>
											<tr><td>Where would you find the company’s policies and procedures on security?</td><td><textarea name="SecurityQ7" style="width: 100%;"><?php echo $answers->Security->Q7->__toString(); ?></textarea> </td></tr>
											<tr><td>List 3 of the methods your store has for protecting its’ premises:</td><td><textarea name="SecurityQ8" style="width: 100%;"><?php echo $answers->Security->Q8->__toString(); ?></textarea> </td></tr>
										</table>
									</div>
								</div>
								     
							</div>
							<?php if($wb->id != '' && $_SESSION['user']->type == User::TYPE_ASSESSOR && ($wb->wb_status == Workbook::STATUS_LEARNER_COMPLETED || $wb->wb_status == Workbook::STATUS_BEING_CHECKED || $wb->wb_status == Workbook::STATUS_IV_REJECTED)) {?>
							<div class="row well">
								<div class="col-sm-12">
									<div class="box box-success box-solid">
										<div class="box-header with-border"><h3 class="box-title">Assessor Feedback</h3></div>
										<div class="box-body assessorFeedback" <?php echo $feedback->Security->Status->__toString() == 'A'?'style="pointer-events:none;"':''; ?>>
											<div class="form-group">
												<label for="status_Security" class="col-sm-12 control-label">Status:</label>
												<div class="col-sm-12">
													<?php
													echo HTML::selectChosen('status_Security', $answer_status, $feedback->Security->Status->__toString() == 'A'?$feedback->Security->Status->__toString():'', false, true); ?>
												</div>
											</div>
											<div class="form-group">
												<label for="comments_Security" class="col-sm-12 control-label">Comments:</label>
												<div class="col-sm-12">
													<textarea name="comments_Security" rows="7" style="width: 100%;"><?php echo $feedback->Security->Comments->__toString(); ?></textarea>
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
								if($feedback->Security->Status->__toString() == 'NA')
									echo HTML::renderWorkbookIcons('Security', false, 'btn-danger');
								else
									echo HTML::renderWorkbookIcons('Security', false, 'btn-success');
								?>
							</div></div></div>
							<?php echo $wb->getPageBottomLine(); ?>
						</div>
						<!--.page 12 ends-->
						<h1>H&S and Security</h1>
						<div class="step-content">
							<?php echo $wb->getPageTopLine(); ?>

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
									<p><strong>Congratulations! You have completed this module. <img src="module_eportfolio/assets/images/wb2_pg18_img2.png" /> </p>
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
						</div>

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

		$("#frm_wb_hs_and_security :input").not(".assessorFeedback :input, #signature_text, #frm_wb_hs_and_security :input[type=hidden], .btnViewSectionHistory, .btnViewAssessorComments, #iv_status, #iv_comments, #reopen_comments").prop("disabled", true);

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
							var myForm = document.forms['frm_wb_hs_and_security'];
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
				var myForm = document.forms['frm_wb_hs_and_security'];
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

					var myForm = document.forms['frm_wb_hs_and_security'];
					myForm.elements['full_save'].value = 'Y';
					myForm.elements['full_save_feedback'].value = 'Y';
					window.onbeforeunload = null;
					myForm.submit();

				},
				'Save And Come Back Later':function () {

					var myForm = document.forms['frm_wb_hs_and_security'];
					myForm.elements['full_save'].value = 'Y';
					myForm.elements['full_save_feedback'].value = 'N';
					window.onbeforeunload = null;
					myForm.submit();

				}
			}
		});
	});

	function partialSave() {
		$('#frm_wb_hs_and_security :input[name=full_save]').val('N');
		$($('#frm_wb_hs_and_security').serializeArray()).each(function(i, field)
		{
			document.forms["frm_wb_hs_and_security"].elements[field.name].value = field.value.replace(/£/g, "GBP");
		});

		$.ajax({
			type:'POST',
			url:'do.php?_action=save_wb_hs_and_security',
			data:$('#frm_wb_hs_and_security').serialize(),
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
