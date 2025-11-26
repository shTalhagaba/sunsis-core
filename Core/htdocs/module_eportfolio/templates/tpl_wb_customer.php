<?php /* @var $wb WBCustomer */ ?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<title>Customer workbook</title>
	<meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
	<link rel="stylesheet" href="/assets/adminlte/bootstrap/css/bootstrap.min.css">
	<link rel="stylesheet" href="/assets/adminlte/dist/css/AdminLTE.min.css">
	<link href="module_eportfolio/assets/jquery.steps.css" rel="stylesheet">
	<link rel="stylesheet" href="/assets/adminlte/plugins/jQueryUI/jquery-ui.css">
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.5.0/css/font-awesome.min.css">
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

<form name="frm_wb_customer" id="frm_wb_customer" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
	<input type="hidden" name="_action" value="save_wb_customer"/>
	<input type="hidden" name="id" value="<?php echo $wb->id; ?>"/>
	<input type="hidden" name="tr_id" value="<?php echo $wb->tr_id; ?>"/>
	<input type="hidden" name="wb_status" id="wb_status" value=""/>
	<input type="hidden" name="full_save" id="full_save" value="<?php echo $wb->full_save; ?>"/>
	<input type="hidden" name="full_save_feedback" id="full_save_feedback"
	       value="<?php echo $wb->full_save_feedback; ?>"/>

	<div class="container-float">
		<div class="wrapper" style="background-color: #ffffff;">

			<header class="main-header"><span style="margin-left: 50%;"><?php echo DAO::getSingleValue($link, "SELECT title FROM student_frameworks WHERE tr_id = '{$wb->tr_id}'"); ?></span></header>

			<div class="content-wrapper" style="background-color: #ffffff;">

				<?php echo $wb->savers_or_sp == 'savers' ? '<section class="content-header"><h1 style="color: #0000ff;">Customer</h1></section>' : '<section class="content-header"><h1>Customer</h1></section>' ?>

				<section class="content">

					<div id="wizard">

						<h1>Customer</h1>

						<div class="step-content">
							<?php echo $wb->getPageTopLine(); ?>
							<div style="position: absolute; top: 40%; right: 50%;" class="lead">
								<?php echo $wb->savers_or_sp == 'savers' ? '<h2 class="text-bold" style="color: #0000ff;">Customer</h2>' : '<h2 class="text-bold">Customer</h2>' ?>
								<p class="text-center">Module</p>
							</div>

							<?php echo $wb->getPageBottomLine(); ?>
						</div>
						<!--.page 1 ends-->

						<h1>Customer</h1>

						<div class="step-content">
							<?php echo $wb->getPageTopLine(); ?>
							<div class="row"><div class="col-sm-12"><div class="pull-right"><?php echo HTML::renderWorkbookIcons('', true); ?></div></div></div>
							<p class="text-bold">This section is about the customer and communicating in the workplace and what you need to know and do.</p>
							<p>Customer Service and communication is about making every interaction with a customer an opportunity to increase, gain, maintain or re-establish their loyalty. It is about delivering excellent customer service in line with the business culture and values in all activities. In retail, customer service and communication can be the way that customers are treated when they make contact with the shop or store, but that is just a part of a much bigger picture.</p>
							<p>Everything you do in retail contributes to customer service and the experiences your customers enjoy when they deal with you. Customer service is a topic that you should be able to relate to because you are a customer almost every day of your life! You may have been disappointed by the poor customer service you have received. However, you should be able to think about occasions when customer service has been good and an organisation has really done all it can to meet your needs. This is the excellent standard you should be trying to achieve. To be able to do this you need to understand your customers.</p>
							<div class="row">
								<div class="col-sm-12"><p><br></p></div>
								<div class="col-sm-12">
									<p>
										<?php if($wb->savers_or_sp == 'savers') { ?>
										<img src="module_eportfolio/assets/images/wb4_pg2_img1_.png" />
										<?php } else { ?>
										<img src="module_eportfolio/assets/images/wb4_pg2_img1.png" />
										<?php } ?>
										<strong>Learner journey / Visit plan</strong>
									</p>
								</div>
								<div class="col-sm-12"><p>Before you start this module please ensure you have completed all of the training detailed below. If you haven't you will need to speak to your manager / mentor to arrange when you will complete it.</p></div>
							</div>
							<div class="row">
								<div class="col-sm-12 table-responsive">
									<table class="table table-bordered table-striped">
										<tr><th>Learning</th><th><?php echo $wb->savers_or_sp == 'savers' ? 'Workbooks/First steps' : 'Workbook/IPad/In-store activity'; ?></th><th>Date completed</th></tr>
										<?php
										$items = WBCustomer::getLearningJourneyItems($wb->savers_or_sp);
										$j = 0;
										foreach($items AS $i)
										{
											$key = 'DC'.++$j;
											echo '<tr>';
											echo '<td>' . $i . '</td>';
											echo $wb->savers_or_sp == 'savers' ? '<td>First Steps</td>' : '<td>iPad</td>';
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

						<h1>Customer</h1>

						<div class="step-content">
							<?php echo $wb->getPageTopLine(); ?>
							<div class="row"><div class="col-sm-12"><div class="pull-right">
								<?php
								if($feedback->ServiceObservation->Status->__toString() == 'NA')
									echo HTML::renderWorkbookIcons('ServiceObservation', false, 'btn-danger');
								else
									echo HTML::renderWorkbookIcons('ServiceObservation', false, 'btn-success');
								?>
							</div></div></div>
							<div class="row">
								<div class="col-sm-12">
									<img class="pull-left" src="module_eportfolio/assets/images/wb2_img1.png" /> &nbsp;
									Ask your manager/mentor to complete a Service observation of you delivering stunning service on the shop floor. You can ask them to complete the one on this page or take a photo of one completed in store. Save this observation for your file
									<?php
									echo $wb->savers_or_sp == 'savers' ?
										'<div style="padding: 10px; border: 2px solid;border-radius: 15px;"><img class="img-responsive" src="module_eportfolio/assets/images/wb_r02_pg3_img1_.png" /></div>' :
										'<img class="img-responsive" src="module_eportfolio/assets/images/wb_r02_pg3_img1.png" />';
									?>
									<br>
									<img class="pull-left" src="module_eportfolio/assets/images/wb2_img1.png" /> &nbsp;
									<?php echo $wb->savers_or_sp == 'savers' ? 'Using the Savers Service Standards Sign off above, answer the following questions' : 'Using the Service Observation above, complete the questions below:' ?>
									<div class="table-responsive">
										<table class="table table-bordered"<?php echo $feedback->ServiceObservation->Status->__toString() == 'A'?'style="pointer-events:none;"':''; ?>>
											<tr><th style="width: 40%;">Question</th><th style="width: 60%;">Answer</th></tr>
											<tr><td>What went well?</td><td><textarea name="ServiceObservationQ1" style="width: 100%;"><?php echo $answers->ServiceObservation->Q1->__toString(); ?></textarea> </td></tr>
											<tr><td>What did not go well?</td><td><textarea name="ServiceObservationQ2" style="width: 100%;"><?php echo $answers->ServiceObservation->Q2->__toString(); ?></textarea> </td></tr>
											<tr><td>What feedback were you given?</td><td><textarea name="ServiceObservationQ3" style="width: 100%;"><?php echo $answers->ServiceObservation->Q3->__toString(); ?></textarea> </td></tr>
											<tr><td>What will you do differently from now on?</td><td><textarea name="ServiceObservationQ4" style="width: 100%;"><?php echo $answers->ServiceObservation->Q4->__toString(); ?></textarea> </td></tr>
										</table>
									</div>
									<p class="text-center"><img class="text-center" src="module_eportfolio/assets/images/wb_r02_pg4_img1.png" /></p>
								</div>
							</div>
							<?php if($wb->id != '' && $_SESSION['user']->type == User::TYPE_ASSESSOR && ($wb->wb_status == Workbook::STATUS_LEARNER_COMPLETED || $wb->wb_status == Workbook::STATUS_BEING_CHECKED || $wb->wb_status == Workbook::STATUS_IV_REJECTED)) {?>
							<div class="row well">
								<div class="col-sm-12">
									<div class="box box-success box-solid">
										<div class="box-header with-border"><h3 class="box-title">Assessor Feedback</h3></div>
										<div class="box-body assessorFeedback" <?php echo $feedback->ServiceObservation->Status->__toString() == 'A'?'style="pointer-events:none;"':''; ?>>
											<div class="form-group">
												<label for="status_ServiceObservation" class="col-sm-12 control-label">Status:</label>
												<div class="col-sm-12">
													<?php
													echo HTML::selectChosen('status_ServiceObservation', $answer_status, $feedback->ServiceObservation->Status->__toString() == 'A'?$feedback->ServiceObservation->Status->__toString():'', false, true); ?>
												</div>
											</div>
											<div class="form-group">
												<label for="comments_ServiceObservation" class="col-sm-12 control-label">Comments:</label>
												<div class="col-sm-12">
													<textarea name="comments_ServiceObservation" rows="7" style="width: 100%;"><?php echo $feedback->ServiceObservation->Comments->__toString(); ?></textarea>
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
								if($feedback->ServiceObservation->Status->__toString() == 'NA')
									echo HTML::renderWorkbookIcons('ServiceObservation', false, 'btn-danger');
								else
									echo HTML::renderWorkbookIcons('ServiceObservation', false, 'btn-success');
								?>
							</div></div></div>
							<?php echo $wb->getPageBottomLine(); ?>
						</div>
						<!--.page 3 ends-->

						<h1>Customer</h1>

						<div class="step-content">
							<?php echo $wb->getPageTopLine(); ?>
							<div class="row"><div class="col-sm-12"><div class="pull-right">
								<?php
								if($feedback->InternalAndExternalCustomers->Status->__toString() == 'NA')
									echo HTML::renderWorkbookIcons('InternalAndExternalCustomers', false, 'btn-danger');
								else
									echo HTML::renderWorkbookIcons('InternalAndExternalCustomers', false, 'btn-success');
								?>
							</div></div></div>
							<div class="row">
								<div class="col-sm-4 col-sm-offset-4">
									<div class="bg-red text-center" style="padding: 15px;">
										<h3>Customer</h3>
										<p class="text-bold">A person who buys goods or services from a shop or business</p>
										<p class="text-bold">A person of a specified kind with whom one has to deal</p>
									</div>
								</div>
							</div>
							<div class="row">
								<div class="col-sm-6">
									<p>Customers are the most important people for any business. They are the resource upon which the success of the business depends. Organisations are dependent upon their customers. If they do not develop customer loyalty and satisfaction, they could lose their customers.</p>
									<p>An <strong>external customer</strong> is a customer who purchases a company's products or services but is not an employee or part of the organisation. For example, a person who goes to a retail store and buys merchandise is an external customer.</p>
								</div>
								<div class="col-sm-6">
									<p>An <strong>internal customer</strong> is a customer who is directly connected to an organisation, and is usually (but not necessarily) internal to that business. Internal customers are usually stakeholders, employees, or shareholders.</p>
									<p>An <strong>internal customer</strong> is any member of a business who relies on assistance from another to fulfil their job duties, such as a sales representative who needs assistance from a customer service representative to place an order.</p>
								</div>
							</div>
							<div class="row">
								<div class="col-sm-12 text-center">
									<img src="module_eportfolio/assets/images/wb4_pg4_img1.png" />
								</div>
							</div>
							<div class="row" <?php echo $feedback->InternalAndExternalCustomers->Status->__toString() == 'A'?'style="pointer-events:none;"':''; ?>>
								<div class="col-sm-12 text-center text-bold">
									<p>
										<img src="module_eportfolio/assets/images/wb2_img1.png" /> &nbsp;
										Identify which of the following are internal and external customers to you in <?php echo ucfirst($wb->savers_or_sp); ?>? &nbsp;
									</p>
								</div>
								<div class="col-sm-12 table-responsive" <?php echo $feedback->InternalAndExternalCustomers->Status->__toString() == 'A'?'style="pointer-events:none;"':''; ?>>
									<?php
									$InternalCustomers = array();
									if(isset($answers->InternalAndExternalCustomers->InternalCustomers))
										$InternalCustomers = explode(',', $answers->InternalAndExternalCustomers->InternalCustomers->__toString());
									$ExternalCustomers = array();
									if(isset($answers->InternalAndExternalCustomers->ExternalCustomers))
										$ExternalCustomers = explode(',', $answers->InternalAndExternalCustomers->ExternalCustomers->__toString());
									?>
									<table class="table row-border">
										<tr><th>Description</th><th>External Customer?</th><th>Internal Customer?</th></tr>
										<tr><td><?php echo ucfirst($wb->savers_or_sp); ?> Regional General Manager</td><td><input type="radio" name="SRGM[]" value="E" <?php echo in_array('SRGM', $ExternalCustomers)?'checked="checked"':''; ?> '/></td><td><input type="radio" name="SRGM[]" value="I" <?php echo in_array('SRGM', $InternalCustomers)?'checked="checked"':''; ?> /></td></tr>
										<tr><td><?php echo ucfirst($wb->savers_or_sp); ?> delivery driver</td><td><input type="radio" name="SDD[]" value="E" <?php echo in_array('SDD', $ExternalCustomers)?'checked="checked"':''; ?> /></td><td><input type="radio" name="SDD[]" value="I" <?php echo in_array('SDD', $InternalCustomers)?'checked="checked"':''; ?> /></td></tr>
										<tr><td>You</td><td><input type="radio" name="YOU[]" value="E" <?php echo in_array('YOU', $ExternalCustomers)?'checked="checked"':''; ?> /></td><td><input type="radio" name="YOU[]" value="I" <?php echo in_array('YOU', $InternalCustomers)?'checked="checked"':''; ?> /></td></tr>
										<tr><td>Friend who works in Greggs</td><td><input type="radio" name="FWWIG[]" value="E" <?php echo in_array('FWWIG', $ExternalCustomers)?'checked="checked"':''; ?> /></td><td><input type="radio" name="FWWIG[]" value="I" <?php echo in_array('FWWIG', $InternalCustomers)?'checked="checked"':''; ?> /></td></tr>
										<tr><td>Local Postman</td><td><input type="radio" name="LP[]" value="E" <?php echo in_array('LP', $ExternalCustomers)?'checked="checked"':''; ?> /></td><td><input type="radio" name="LP[]" value="I" <?php echo in_array('LP', $InternalCustomers)?'checked="checked"':''; ?> /></td></tr>
									</table>
								</div>
							</div>
							<?php if($wb->id != '' && $_SESSION['user']->type == User::TYPE_ASSESSOR && ($wb->wb_status == Workbook::STATUS_LEARNER_COMPLETED || $wb->wb_status == Workbook::STATUS_BEING_CHECKED || $wb->wb_status == Workbook::STATUS_IV_REJECTED)) {?>
							<div class="row well">
								<div class="col-sm-12">
									<div class="box box-success box-solid">
										<div class="box-header with-border"><h3 class="box-title">Assessor Feedback</h3></div>
										<div class="box-body assessorFeedback" <?php echo $feedback->InternalAndExternalCustomers->Status->__toString() == 'A'?'style="pointer-events:none;"':''; ?>>
											<div class="form-group">
												<label for="status_InternalAndExternalCustomers" class="col-sm-12 control-label">Status:</label>
												<div class="col-sm-12">
													<?php
													echo HTML::selectChosen('status_InternalAndExternalCustomers', $answer_status, $feedback->InternalAndExternalCustomers->Status->__toString() == 'A'?$feedback->InternalAndExternalCustomers->Status->__toString():'', false, true); ?>
												</div>
											</div>
											<div class="form-group">
												<label for="comments_InternalAndExternalCustomers" class="col-sm-12 control-label">Comments:</label>
												<div class="col-sm-12">
													<textarea name="comments_InternalAndExternalCustomers" rows="7" style="width: 100%;"><?php echo $feedback->InternalAndExternalCustomers->Comments->__toString(); ?></textarea>
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
								if($feedback->InternalAndExternalCustomers->Status->__toString() == 'NA')
									echo HTML::renderWorkbookIcons('InternalAndExternalCustomers', false, 'btn-danger');
								else
									echo HTML::renderWorkbookIcons('InternalAndExternalCustomers', false, 'btn-success');
								?>
							</div></div></div>
							<?php echo $wb->getPageBottomLine(); ?>
						</div>
						<!--.page 5 ends-->

						<h1>Customer</h1>

						<div class="step-content">
							<?php echo $wb->getPageTopLine(); ?>
							<div class="row"><div class="col-sm-12"><div class="pull-right"><?php echo HTML::renderWorkbookIcons('', true); ?></div></div></div>
							<div class="row">
								<div class="col-sm-6">
									<p><strong>Types of customers</strong></p>
									<p>An organisation may have many different types of customers. A customer can come in many different forms. They can be an individual or another organisation ' as long as they receive a form of customer service from a service deliverer, they are a customer.</p>
									<p><strong>Loyal customers</strong></p>
									<p>These tend to provide an organisation with a large part of its sales and profits. They are happy with the products offered and the standards of service they receive. Organisations depend heavily on their loyal customers and must look after them if they wish to keep them.</p>
									<p><strong>Discount customers</strong></p>
									<p>These tend to buy from an organisation only when discounts or sale prices are offered. They can be encouraged to buy from an organisation via special offers and discounts, so marketing is an important part of maintaining a relationship with these types of customers.</p>
									<p><strong>Impulse customers</strong></p>
									<p>These customers tend to buy on impulse and can therefore be more difficult to sell to. They may not have very high levels of loyalty to the business and the challenge for the organisation is to present these types of customers with products or services which will stimulate them to buy.</p>
								</div>
								<div class="col-sm-6">
									<p><strong>Need-based customers</strong></p>
									<p>These customers tend only to buy products or services they have a specific need for. These customers need to be handled in a very positive way to show them reasons to buy from an organisation - if not handled the right way there is a high risk of losing them.</p>
									<p><strong>Wandering customers</strong></p>
									<p>These are the least profitable types of customers and sometimes do not know what to buy. They will often visit an organisation just to confirm what it offers and to compare against competitors. They must be properly informed in order to make a buying decision.</p>
									<p>It is important that an organisation caters for a wide range of different needs but that it focuses its efforts on meeting the needs of the majority of its customers, in particular its loyal customers, as these tend to account for the bulk of sales and profits.</p>
									<p class="text-center"><img src="module_eportfolio/assets/images/wb4_pg5_img1.png" /> </p>
								</div>
								<div class="col-sm-12">
									<p class="text-bold">How the culture of different businesses impact on the style of interaction with customers</p>
									<p>Customer expectation is what a customer thinks should happen and how they think they should be treated. These expectations can be formed in a number of ways, one being culture. This may be the culture of the organisation. For example, if customer service is really important to the organisation and its staff then it will have a customer service culture and customers' expectations will be higher than that of a company that does not have such a culture. This may affect customers' buying habits and approaches. For example, if the key factor for a customer is price, they may have a lower service expectation of a company that trades mainly on price.</p>
								</div>
							</div>


							<p><br></p>
							<div class="row"><div class="col-sm-12"><div class="pull-right"><?php echo HTML::renderWorkbookIcons('', true); ?></div></div></div>
							<?php echo $wb->getPageBottomLine(); ?>
						</div>
						<!--.page 6 ends-->

						<h1>Customer</h1>

						<div class="step-content">
							<?php echo $wb->getPageTopLine(); ?>
							<div class="row"><div class="col-sm-12"><div class="pull-right">
								<?php
								if($feedback->Retailers->Status->__toString() == 'NA')
									echo HTML::renderWorkbookIcons('Retailers', false, 'btn-danger');
								else
									echo HTML::renderWorkbookIcons('Retailers', false, 'btn-success');
								?>
							</div></div></div>

							<div class="row text-center">
								<div class="col-sm-5">
									<div class="row">
										<div class="col-sm-1"><img src="module_eportfolio/assets/images/wb2_img1.png" /></div>
										<div class="col-sm-11"><p class="text-bold">Which retailers do you think customers have higher expectations of regarding their customer service?</p></div>
									</div>
								</div>
								<div class="col-sm-2"></div>
								<div class="col-sm-5">
									<div class="row">
										<div class="col-sm-1"><img src="module_eportfolio/assets/images/wb2_img1.png" /></div>
										<div class="col-sm-11"><p class="text-bold">Which retailers do you think customers have lower expectations of regarding customer service and higher expectations of regarding their prices?</p></div>
									</div>
								</div>
							</div>
							<div class="row text-center" <?php echo $feedback->Retailers->Status->__toString() == 'A'?'style="pointer-events:none;"':''; ?>>
								<div class="col-sm-5">
									<p><textarea name="Higher" rows="5" cols="50" style="width: 100%;"><?php echo $answers->Retailers->Higher->__toString(); ?></textarea></p>
								</div>
								<div class="col-sm-2">
									
								</div>
								<div class="col-sm-5">
									<p><textarea name="Lower" rows="5" cols="50" style="width: 100%;"><?php echo $answers->Retailers->Lower->__toString(); ?></textarea></p>
								</div>
							</div>
							<div class="row">
								<div class="col-sm-12">
									<p class="text-bold">Don't forget you can also look online and research your answers, if you do, record it in the independent learning section at the end of this workbook</p>
									<p>Understanding customers' needs and the ability to meet their needs in line with the business is essential.
										When we think about customer service we are often thinking about how we can meet customers' needs. It is important to understand that these needs can be based on several factors.
									</p>
								</div>
							</div>
							<div class="row">
								<div class="col-sm-6">
									<p><strong>Cultural and other factors which may affect customer expectations include:</strong></p>
									<ul style="margin-left: 15px; margin-bottom: 15px;">
										<li>Nationality</li>
										<li>Tradition</li>
										<li>Gender</li>
										<li>Ethnicity</li>
										<li>Religion</li>
										<li>Legislation</li>
										<li>Urban or rural home</li>
										<li>Language</li>
									</ul>
								</div>
								<div class="col-sm-6">
									<p><strong>Other examples of customers' needs and priorities within the retail business could include:</strong></p>
									<ul style="margin-left: 15px;">
										<li>Dietary requirements or allergies - providing information on products</li>
										<li>Disabled access and facilities - wheelchair access and hearing loops</li>
										<li>Translation services</li>
										<li>Parking</li>
										<li>Flexible payment options</li>
										<li>Learning difficulties</li>
									</ul>
								</div>
							</div>
							<div class="row">
								<div class="col-sm-12">
									<p><strong>Religion and religious beliefs </strong>can also have an impact on a customer's needs and priorities. Expectations may be regarding purchasing food and the way animals are slaughtered.</p>
									<p><strong>Foreign visitors </strong>may expect retail staff to be able to communicate with them in their own language.</p>
								</div>
							</div>
							<div class="row">
								<div class="col-sm-12">
									<p>Try different approaches to communicate:</p>
									<ul style="margin-left: 15px;">
										<li>Write things down or use pictures or diagrams to help</li>
										<li>Do you have a colleague that speaks the language or has an understanding of the person's cultural background and needs?</li>
									</ul>
								</div>
							</div>
							<?php if($wb->id != '' && $_SESSION['user']->type == User::TYPE_ASSESSOR && ($wb->wb_status == Workbook::STATUS_LEARNER_COMPLETED || $wb->wb_status == Workbook::STATUS_BEING_CHECKED || $wb->wb_status == Workbook::STATUS_IV_REJECTED)) {?>
							<div class="row well">
								<div class="col-sm-12">
									<div class="box box-success box-solid">
										<div class="box-header with-border"><h3 class="box-title">Assessor Feedback</h3></div>
										<div class="box-body assessorFeedback" <?php echo $feedback->Retailers->Status->__toString() == 'A'?'style="pointer-events:none;"':''; ?>>
											<div class="form-group">
												<label for="status_Retailers" class="col-sm-12 control-label">Status:</label>
												<div class="col-sm-12">
													<?php
													echo HTML::selectChosen('status_Retailers', $answer_status, $feedback->Retailers->Status->__toString() == 'A'?$feedback->Retailers->Status->__toString():'', false, true); ?>
												</div>
											</div>
											<div class="form-group">
												<label for="comments_Retailers" class="col-sm-12 control-label">Comments:</label>
												<div class="col-sm-12">
													<textarea name="comments_Retailers" rows="7" style="width: 100%;"><?php echo $feedback->Retailers->Comments->__toString(); ?></textarea>
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
								if($feedback->Retailers->Status->__toString() == 'NA')
									echo HTML::renderWorkbookIcons('Retailers', false, 'btn-danger');
								else
									echo HTML::renderWorkbookIcons('Retailers', false, 'btn-success');
								?>
							</div></div></div>
							<?php echo $wb->getPageBottomLine(); ?>
						</div>
						<!--.page 7 ends-->

						<h1>Customer</h1>

						<div class="step-content">
							<?php echo $wb->getPageTopLine(); ?>
							<div class="row"><div class="col-sm-12"><div class="pull-right">
								<?php
								if($feedback->CustomersWithSpecialNeeds->Status->__toString() == 'NA')
									echo HTML::renderWorkbookIcons('CustomersWithSpecialNeeds', false, 'btn-danger');
								else
									echo HTML::renderWorkbookIcons('CustomersWithSpecialNeeds', false, 'btn-success');
								?>
							</div></div></div>
                            <div class="row" <?php echo $feedback->CustomersWithSpecialNeeds->Status->__toString() == 'A'?'style="pointer-events:none;"':''; ?>>
                                <div class="col-sm-12">
                                    <p class="text-bold">
                                        <img src="module_eportfolio/assets/images/wb2_img1.png" /> &nbsp;
                                        Give an example of when you have served a customer who had specific needs and explain what you did?
                                    </p>
                                    <p><textarea name="CustomersWithSpecialNeedsYourExperience" rows="10" style="min-width: 100%"><?php echo $answers->CustomersWithSpecialNeeds->YourExperience->__toString(); ?></textarea></p>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-12">
                                    <p><strong>Characteristics of challenging customers</strong></p>
                                    <p>There may be a number of occasions and situations when you could find it challenging to deal with a customer. Often the reason we find a customer challenging is because of our own limitations, points of view, and attitude or skill level. Language or cultural barriers can prove challenging. In situations like this it is important to remain calm and patient and put yourself in the shoes of the customer.</p>
                                </div>
                            </div>
                            <div class="row text-center">
                                <div class="col-sm-5">
                                    <div class="row">
                                        <div class="col-sm-1"><img src="module_eportfolio/assets/images/wb2_img1.png" /></div>
                                        <div class="col-sm-11"><p class="text-bold">How would you feel if you were struggling to make yourself understood?</p></div>
                                    </div>
                                </div>
                                <div class="col-sm-2"></div>
                                <div class="col-sm-5">
                                    <div class="row">
                                        <div class="col-sm-1"><img src="module_eportfolio/assets/images/wb2_img1.png" /></div>
                                        <div class="col-sm-11"><p class="text-bold">How do you think the customer feels?</p></div>
                                    </div>
                                </div>
                            </div>
                            <div class="row text-center" <?php echo $feedback->CustomersWithSpecialNeeds->Status->__toString() == 'A'?'style="pointer-events:none;"':''; ?>>
                                <div class="col-sm-5">
                                    <p><textarea name="CustomersWithSpecialNeedsIfYouStruggle" rows="5" cols="50" style="width: 100%;"><?php echo $answers->CustomersWithSpecialNeeds->IfYouStruggle->__toString(); ?></textarea></p>
                                </div>
                                <div class="col-sm-2">

                                </div>
                                <div class="col-sm-5">
                                    <p><textarea name="CustomersWithSpecialNeedsHowCustomerFeels" rows="5" cols="50" style="width: 100%;"><?php echo $answers->CustomersWithSpecialNeeds->HowCustomerFeels->__toString(); ?></textarea></p>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-12">
                                    <p><strong>Customers with special requirements </strong>can sometimes be challenging to assist. Various disabilities such as hearing impairment, visual impairment or mobility difficulties can require extra thought and flexibility of approach on your or the organisations' part in order to ensure you provide the same high standards of service to all customer groups. You may have to speak more slowly, listen carefully and use images or diagrams to aid communication.</p>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-6">
                                    <p>In addition to you taking a helpful and flexible approach, other ways that organisations can assist these types of customers include:</p>
                                    <ul style="margin-left: 15px; margin-bottom: 15px;">
                                        <li>Braille signage and documents</li>
                                        <li>Hearing loops</li>
                                        <li>Adequately trained staff</li>
                                    </ul>
                                </div>
                                <div class="col-sm-6">
                                    <p></p><img src="module_eportfolio/assets/images/wb4_pg8_img2.png" />
                                    <img src="module_eportfolio/assets/images/wb4_pg8_img1.png" /></p>
                                </div>
                            </div>
							<?php if($wb->id != '' && $_SESSION['user']->type == User::TYPE_ASSESSOR && ($wb->wb_status == Workbook::STATUS_LEARNER_COMPLETED || $wb->wb_status == Workbook::STATUS_BEING_CHECKED || $wb->wb_status == Workbook::STATUS_IV_REJECTED)) {?>
							<div class="row well">
								<div class="col-sm-12">
									<div class="box box-success box-solid">
										<div class="box-header with-border"><h3 class="box-title">Assessor Feedback</h3></div>
										<div class="box-body assessorFeedback" <?php echo $feedback->CustomersWithSpecialNeeds->Status->__toString() == 'A'?'style="pointer-events:none;"':''; ?>>
											<div class="form-group">
												<label for="status_CustomersWithSpecialNeeds" class="col-sm-12 control-label">Status:</label>
												<div class="col-sm-12">
													<?php
													echo HTML::selectChosen('status_CustomersWithSpecialNeeds', $answer_status, $feedback->CustomersWithSpecialNeeds->Status->__toString() == 'A'?$feedback->CustomersWithSpecialNeeds->Status->__toString():'', false, true); ?>
												</div>
											</div>
											<div class="form-group">
												<label for="comments_CustomersWithSpecialNeeds" class="col-sm-12 control-label">Comments:</label>
												<div class="col-sm-12">
													<textarea name="comments_CustomersWithSpecialNeeds" rows="7" style="width: 100%;"><?php echo $feedback->CustomersWithSpecialNeeds->Comments->__toString(); ?></textarea>
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
								if($feedback->CustomersWithSpecialNeeds->Status->__toString() == 'NA')
									echo HTML::renderWorkbookIcons('CustomersWithSpecialNeeds', false, 'btn-danger');
								else
									echo HTML::renderWorkbookIcons('CustomersWithSpecialNeeds', false, 'btn-success');
								?>
							</div></div></div>
							<?php echo $wb->getPageBottomLine(); ?>
						</div>
						<!--.page 8 ends-->

						<h1>Customer</h1>

						<div class="step-content">
							<?php echo $wb->getPageTopLine(); ?>
							<div class="row"><div class="col-sm-12"><div class="pull-right">
								<?php
								if($feedback->ImpatientCustomers->Status->__toString() == 'NA')
									echo HTML::renderWorkbookIcons('ImpatientCustomers', false, 'btn-danger');
								else
									echo HTML::renderWorkbookIcons('ImpatientCustomers', false, 'btn-success');
								?>
							</div></div></div>
                            <div class="row">
                                <div class="col-sm-6">
                                    <p><strong>Impatient or angry customers </strong>will often display signs of how they feel by facial expression. They may raise their voice or be sarcastic; their body language may be intimidating.</p>
                                </div>
                                <div class="col-sm-6">
                                    <img src="module_eportfolio/assets/images/wb_r02_pg9_img1.png" />
                                </div>
                            </div>
                            <br>
                            <div class="row">
                                <div class="col-sm-6">
                                    <p>Whenever a customer displays these characteristics, you must remain calm and aim to not take things personally.</p>
                                    <p>In many cases the customer just needs to feel they are being listened to and empathised with.</p>
                                    <ul style="margin-left: 15px; margin-bottom: 15px;">
                                        <li>Nod from time to time to display that you are listening to them</li>
                                        <li>Repeat key points back to them</li>
                                    </ul>
                                </div>
                                <div class="col-sm-6">
	                                <img src="module_eportfolio/assets/images/wb_r02_pg9_img2.png" />
                                </div>
                            </div>
                            <br>
                            <div class="row">
                                <div class="col-sm-12">
                                    <p><strong>Demanding customers</strong> will present challenging behaviour at times. It may be that they require additional and more detailed information before making a purchase, they may challenge company policy or procedures, or they may demand more of your time or wish to speak to a more senior or experienced member of staff or management.</p>
                                    <p><strong>Elderly customers </strong>can sometimes present challenges in terms of health issues.</p>
                                    <p>You may need to take more time when helping someone who is elderly or whose health may be failing. Communication, understanding and physical limitations are factors which you may need to take into account.</p>
                                </div>
                            </div>
                            <br>
                            <div class="row">
                                <div class="col-sm-6">
                                    <p><strong>Language or cultural barriers can prove challenging.</strong> In situations like this it is important to remain calm and patient and put yourself in the shoes of the customer.</p>
                                </div>
                                <div class="col-sm-6">
	                                <img src="module_eportfolio/assets/images/wb4_pg7_img1.png" />
                                </div>
                            </div>
                            <div class="row" <?php echo $feedback->ImpatientCustomers->Status->__toString() == 'A'?'style="pointer-events:none;"':''; ?>>
                                <div class="col-sm-12">
                                    <p class="text-bold"><img src="module_eportfolio/assets/images/wb2_img1.png" /> &nbsp;Think about all the different types of customers above and give an example of when you have dealt with a challenging situation. </p>
                                    <textarea name="ImpatientCustomersWhatYouDid" style="width: 100%" rows="5"><?php echo $answers->ImpatientCustomers->WhatYouDid->__toString(); ?></textarea>
                                </div>
                            </div>
							<br>
							<div class="row" <?php echo $feedback->ImpatientCustomers->Status->__toString() == 'A'?'style="pointer-events:none;"':''; ?>>
								<div class="col-sm-12">
									<p class="text-bold"><img src="module_eportfolio/assets/images/wb2_img1.png" /> &nbsp;Think about the challenging situation you have just written about on the previous question. What could you do differently next time?</p>
									<textarea name="ImpatientCustomersWhatYouWillDo" style="width: 100%" rows="5"><?php echo $answers->ImpatientCustomers->WhatYouWillDo->__toString(); ?></textarea>
								</div>
							</div>
							<div class="row" >
								<div class="col-sm-12">
									<p>In this next section you will consider how your behaviour affects the impression that customers form from the service they are receiving and you will also look at how good communication can give a positive impression of both yourself and your store.</p>
									<p>By the end of this section you will be able to:</p>
									<ul style="margin-left: 15px; margin-bottom: 15px;">
										<li>Understand how to give your customers a positive impression of yourself and your store</li>
										<li>Understand how to help our customers by demonstrating a can do attitude</li>
										<li>Identify your customers' needs and respond appropriately</li>
										<li>Understand how to identify dissatisfaction and deal with customer complaints</li>
										<li>Leave customers with a positive impression of their shopping experience</li>
									</ul>
									<p><strong>First impressions</strong></p>
									<p>Always put the customer first.  First impressions are very important.  Customers who come into your store see not only the products on sale but the people who serve them.  You need to be able to provide customers with the correct information and treat them pleasantly and politely.  If you deliver this then you will be providing excellent customer service and the customer will leave the store having a positive impression of both you and your store.</p>
									<p>Customers expect you to look 'the part' and they always notice when someone is well groomed.  You should always be a good advertisement for your store and your organisation.</p>
									<p>The customer is the most important visitor to your store; they do not depend on you, you depend on them.  The customer is not an interruption to your work; they are the purpose of it.</p>
									<p>A smile and a greeting will really help your customers to feel welcome, but serving customers well goes much further than this.  Some customers will simply ask for any assistance that they might need, but many more will not.  There are lots of different ways in which these customers will signal for help.  It is important that you learn what the signals are so you can respond appropriately.</p>
								</div>
							</div>
							<?php if($wb->id != '' && $_SESSION['user']->type == User::TYPE_ASSESSOR && ($wb->wb_status == Workbook::STATUS_LEARNER_COMPLETED || $wb->wb_status == Workbook::STATUS_BEING_CHECKED || $wb->wb_status == Workbook::STATUS_IV_REJECTED)) {?>
							<div class="row well">
								<div class="col-sm-12">
									<div class="box box-success box-solid">
										<div class="box-header with-border"><h3 class="box-title">Assessor Feedback</h3></div>
										<div class="box-body assessorFeedback" <?php echo $feedback->ImpatientCustomers->Status->__toString() == 'A'?'style="pointer-events:none;"':''; ?>>
											<div class="form-group">
												<label for="status_ImpatientCustomers" class="col-sm-12 control-label">Status:</label>
												<div class="col-sm-12">
													<?php
													echo HTML::selectChosen('status_ImpatientCustomers', $answer_status, $feedback->ImpatientCustomers->Status->__toString() == 'A'?$feedback->ImpatientCustomers->Status->__toString():'', false, true); ?>
												</div>
											</div>
											<div class="form-group">
												<label for="comments_ImpatientCustomers" class="col-sm-12 control-label">Comments:</label>
												<div class="col-sm-12">
													<textarea name="comments_ImpatientCustomers" rows="7" style="width: 100%;"><?php echo $feedback->ImpatientCustomers->Comments->__toString(); ?></textarea>
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
								if($feedback->ImpatientCustomers->Status->__toString() == 'NA')
									echo HTML::renderWorkbookIcons('ImpatientCustomers', false, 'btn-danger');
								else
									echo HTML::renderWorkbookIcons('ImpatientCustomers', false, 'btn-success');
								?>
							</div></div></div>
							<?php echo $wb->getPageBottomLine(); ?>
						</div>
						<!--.page 9 ends-->

						<h1>Customer</h1>

						<div class="step-content">
							<?php echo $wb->getPageTopLine(); ?>
							<div class="row"><div class="col-sm-12"><div class="pull-right">
								<?php
								if($feedback->HelpSigns->Status->__toString() == 'NA')
									echo HTML::renderWorkbookIcons('HelpSigns', false, 'btn-danger');
								else
									echo HTML::renderWorkbookIcons('HelpSigns', false, 'btn-success');
								?>
							</div></div></div>
                            <div class="row">
                                <div class="col-sm-12">
                                    <p class="text-bold">Helping our customers</p>
                                    <p>Remember, customers are the number one priority at all times.  Whatever you are doing, whether you are putting out stock, tidying the shelves or setting up a promotion, you must always offer to help the customer before continuing the tasks.  Whilst you are completing your task you must be constantly aware of the customers around you.  You should always work tidily, effectively and efficiently.</p>
                                    <p>All of our customers are different and they need to be given the service that best suits them.  It is important that you identify their needs and ensure they leave the store feeling good about their experience.  Customers will let you know when they need help; even if they don't come and ask you, the signs that they want help are unmistakeable. When dealing with customers you just have to remember what it is like to be a customer yourself.</p>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-6">
                                    <p>The 'I need help' signs include:</p>
                                    <ul style="margin-left: 15px; margin-bottom: 15px;">
                                        <li>Looking around</li>
                                        <li>Returning to the same product repeatedly</li>
                                        <li>Reading instructions on products</li>
                                        <li>Looking at their watches</li>
                                        <li>Making eye contact with you</li>
                                    </ul>
                                </div>
                                <div class="col-sm-6" <?php echo $feedback->HelpSigns->Status->__toString() == 'A'?'style="pointer-events:none;"':''; ?>>
                                    <p class="text-bold"><img src="module_eportfolio/assets/images/wb2_img1.png" /> &nbsp;Can you think of any others 'I need help signs?</p>
                                    <textarea name="HelpSigns" style="width: 100%" rows="5"><?php echo $answers->HelpSigns->__toString(); ?></textarea>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-12">
                                    <p>In order to deliver excellent customer service you will need to be able to communicate effectively with your customers in numerous different ways.  These will include:</p>
                                    <ul style="margin-left: 15px; margin-bottom: 15px;">
                                        <li>Face to face</li>
                                        <li>By telephone</li>
                                    </ul>
                                    <p>When communicating face to face with your customer the single biggest thing they will notice is your body language.  Body language rarely lies, so look out for what the body is telling you (and be aware of what your own body language is saying).</p>
                                    <p>Using the telephone is a daily activity for all of us and most people are quite casual when they do so.  Giving the customer a good impression means being polite, speaking clearly and listening carefully.  It gives a good impression to a caller if the telephone is answered promptly and clearly.  The procedure for answering the phone when it rings is:</p>
                                    <p class="text-bold"><i>"Good morning, <?php echo ucfirst($wb->savers_or_sp); ?> (store name), Sue speaking, how may I help you?"</i></p>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-6">
                                    <p>If you have to pass the query to another team member, explain this to the customer.  Speak to your colleague outlining the request or query so the customer doesn't have to explain it all again.  If you have to call a customer, make sure you have all the relevant information beforehand.</p>
                                </div>
                                <div class="col-sm-6">
                                    <img src="module_eportfolio/assets/images/wb3_pg9_img1.png" />
                                </div>
                            </div>
							<?php if($wb->id != '' && $_SESSION['user']->type == User::TYPE_ASSESSOR && ($wb->wb_status == Workbook::STATUS_LEARNER_COMPLETED || $wb->wb_status == Workbook::STATUS_BEING_CHECKED || $wb->wb_status == Workbook::STATUS_IV_REJECTED)) {?>
							<div class="row well">
								<div class="col-sm-12">
									<div class="box box-success box-solid">
										<div class="box-header with-border"><h3 class="box-title">Assessor Feedback</h3></div>
										<div class="box-body assessorFeedback" <?php echo $feedback->HelpSigns->Status->__toString() == 'A'?'style="pointer-events:none;"':''; ?>>
											<div class="form-group">
												<label for="status_HelpSigns" class="col-sm-12 control-label">Status:</label>
												<div class="col-sm-12">
													<?php
													echo HTML::selectChosen('status_HelpSigns', $answer_status, $feedback->HelpSigns->Status->__toString() == 'A'?$feedback->HelpSigns->Status->__toString():'', false, true); ?>
												</div>
											</div>
											<div class="form-group">
												<label for="comments_HelpSigns" class="col-sm-12 control-label">Comments:</label>
												<div class="col-sm-12">
													<textarea name="comments_HelpSigns" rows="7" style="width: 100%;"><?php echo $feedback->HelpSigns->Comments->__toString(); ?></textarea>
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
								if($feedback->HelpSigns->Status->__toString() == 'NA')
									echo HTML::renderWorkbookIcons('HelpSigns', false, 'btn-danger');
								else
									echo HTML::renderWorkbookIcons('HelpSigns', false, 'btn-success');
								?>
							</div></div></div>
							<?php echo $wb->getPageBottomLine(); ?>
						</div>
						<!--.page 11 ends-->

						<h1>Customer</h1>

						<div class="step-content">
							<?php echo $wb->getPageTopLine(); ?>
							<div class="row"><div class="col-sm-12"><div class="pull-right"><?php echo HTML::renderWorkbookIcons('', true); ?></div></div></div>
                            <div class="row">
                                <div class="col-sm-12">
                                    <p class="text-bold">Identifying customer needs and responding appropriately</p>
                                    <p>The correct way to identify customer needs is to use the correct questioning techniques. This is commonly called customer service language. This involves using questioning skills and the correct body language.</p>
                                </div>
                            </div>
                            <br>
                            <div class="row">
                                <div class="col-sm-6">
                                    <p>Responding to the answers the customer gives us and the information we give to them is just as important and is a vital part in giving excellent service.</p>
                                    <p>In this section we will look at:</p>
                                    <ul style="margin-left: 15px; margin-bottom: 15px;">
                                        <li>Questioning techniques</li>
                                        <li>Listening techniques</li>
                                        <li>Body language</li>
                                        <li>Clarifying information</li>
                                    </ul>
                                </div>
                                <div class="col-sm-6">
                                    <img src="module_eportfolio/assets/images/wb3_pg10_img1.png" />
                                </div>
                            </div>
                            <br>
                            <div class="row">
                                <div class="col-sm-12">
                                    <p class="text-bold">Open Questions</p>
                                    <p>These are questions that invite the customer to give a range of answers, not just a simple yes or no. This allows the customer to go into detail. Example of an open question: 'How can I help you'? The key word in this sentence is HOW?  This gives the customer the opportunity to go into detail of exactly how you can help them.</p>
                                    <br>
                                    <p class="text-bold">Other examples that open questions usually start with:</p>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-12">
                                    <img src="module_eportfolio/assets/images/wb3_pg10_img2.png" />
                                </div>
                            </div>
							<p><br></p>
							<div class="row"><div class="col-sm-12"><div class="pull-right"><?php echo HTML::renderWorkbookIcons('', true); ?></div></div></div>
							<?php echo $wb->getPageBottomLine(); ?>
						</div>
						<!--.page 12 ends-->

						<h1>Customer</h1>

						<div class="step-content">
							<?php echo $wb->getPageTopLine(); ?>
							<div class="row"><div class="col-sm-12"><div class="pull-right"><?php echo HTML::renderWorkbookIcons('', true); ?></div></div></div>
                            <div class="row">
                                <div class="col-sm-12">
                                    <p>It is important that anyone working in Retail can use open and closed questions with customers, and that they know when to use each type. Open questions are best used at the start of the retail selling process as they allow the customer to respond positively (building rapport with the staff member), and it also allows the member of staff to establish exactly what the customer needs. When determining what the customer needs it is important that open questions are also probing.</p>
                                    <p><strong>Probing Questions</strong> <br>These are questions designed to find out a little bit more about what the customer wants from their purchase. Probing questions allow staff members to find out the facts, understand the needs, establish likes and dislikes and establish the customers' budget.</p>
                                    <p class="text-bold">An example of a probing question:</p>
                                    <ul style="margin-left: 15px; margin-bottom: 15px;">
                                        <li>Which products have you used before?</li>
                                        <li>What did you, or didn't you like about that?</li>
                                    </ul>
                                    <p><strong>Closed Questions</strong><br>These are questions that give you the opportunity to summarise what information you have been given and also to show the customer that you have fully understood this information. They are also used to steer the conversation to the required outcome. These are questions that invite a yes or a no response.</p>
                                    <p class="text-bold">Examples of a closed questions are:</p>
                                    <ul style="margin-left: 15px; margin-bottom: 15px;">
                                        <li>Do you need a carrier bag?</li>
                                        <li>Would you like this product?</li>
                                    </ul>
                                    <p><strong>Listening Techniques</strong><br>Asking questions is just one part of the process of finding out what customers are looking for. If you do not listen carefully to what your customers tell you, you will miss out on important information that could help you to understand what customers require.</p>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-6">
                                    <p class="text-bold">Active listening involves:</p>
                                    <ul style="margin-left: 15px; margin-bottom: 15px;">
                                        <li>Listening carefully to what your customers tell you</li>
                                        <li>Showing that you are listening</li>
                                        <li>Making eye contact</li>
                                        <li>Using suitable body language</li>
                                        <li>Asking more questions if required</li>
                                    </ul>
                                </div>
                                <div class="col-sm-6">
                                    <img src="module_eportfolio/assets/images/wb3_pg11_img1.png" />
                                </div>
                            </div>
							<p><br></p>
							<div class="row"><div class="col-sm-12"><div class="pull-right"><?php echo HTML::renderWorkbookIcons('', true); ?></div></div></div>

							<?php echo $wb->getPageBottomLine(); ?>
						</div>
						<!--.page 13 ends-->


	                    <h1>Customer</h1>

	                    <div class="step-content">
	                        <?php echo $wb->getPageTopLine(); ?>
		                    <div class="row"><div class="col-sm-12"><div class="pull-right"><?php echo HTML::renderWorkbookIcons('', true); ?></div></div></div>
	                        <div class="row">
	                            <div class="col-sm-12">
	                                <p class="text-bold">Body Language</p>
	                            </div>
	                        </div>

	                        <div class="row">
	                            <div class="col-sm-12">
	                                <p><br>Your body language, when you communicate face to face, is very important especially when dealing with customers' situations. Your body language should say the same as your words. </p>
	                                <p><br>The most effective way of putting customers at ease and gaining their trust is to communicate effectively and with the intent of doing your best to help them. When it comes to face-to-face communication, how effectively we build rapport is a key element of helping customers feel at ease.</p>
	                                <p><img class="pull-right img-responsive" src="module_eportfolio/assets/images/wb7_pg7_img1.png" /></p>
	                                <p><br>Effective body language is focused around a few key areas. The ability to maintain eye contact, adopting an 'open stance' with your body position and nodding your head to demonstrate you understand the customer, are all key elements of body language and help to build rapport. </p>
	                                <p><br>The tone of voice you adopt and the style of language and words you use will also have a significant effect on how you are perceived by the customer. Smiling at the customer and greeting them in a manner appropriate to the style and brand of your organisation will also help to make the customer feel more at ease and begin to build their trust in you.</p>
	                                <p class="text-bold"><br>Clarifying information</p>
	                                <p>Sometimes our customers can become confused with information that we may have given them.</p>
	                                <p><br>Whilst you are talking to customers, an easy way to avoid confusion is to encourage them to ask questions. </p>
	                            </div>
	                            <div class="col-sm-6">
	                                <p>If you are unsure if your customer has understood the explanation you have given them, you can always ask:</p>
	                                <p><img class="img-responsive" src="module_eportfolio/assets/images/wb3_pg12_img1.png" /></p>
	                            </div>
	                            <div class="col-sm-6">
	                                <p>If the customer still appears confused, ask questions such as:</p>
	                                <p><img class="img-responsive" src="module_eportfolio/assets/images/wb3_pg12_img2.png" /></p>
	                            </div>
	                        </div>

	                        <p><br></p>
		                    <div class="row"><div class="col-sm-12"><div class="pull-right"><?php echo HTML::renderWorkbookIcons('', true); ?></div></div></div>

	                        <?php echo $wb->getPageBottomLine(); ?>
	                    </div>
	                    <!--.page 14 ends-->

	                    <h1>Customer</h1>

	                    <div class="step-content">
	                        <?php echo $wb->getPageTopLine(); ?>
		                    <div class="row"><div class="col-sm-12"><div class="pull-right"><?php echo HTML::renderWorkbookIcons('', true); ?></div></div></div>

	                        <div class="row">
	                            <div class="col-sm-12">
	                                <p><img class="pull-right img-responsive" src="module_eportfolio/assets/images/wb3_pg13_img1.png" /></p>
	                                <p><strong>Dealing with complaints or confusion</strong><br>There are a number of different ways that can help us to know if a customer is dissatisfied. The most common way a customer will demonstrate dissatisfaction is via their behaviour. This will display itself in a number of ways including body language, voice tone, volume and language.</p>
	                            </div>
	                        </div>
	                        <div class="row">
	                            <div class="col-sm-12">
	                                <p>In all stores there will be times when customers will want to complain.  The reasons for the complaint will vary.  <?php echo ucfirst($wb->savers_or_sp); ?> does have a policy for dealing with complaints:</p>
	                                <ul style="margin-left: 15px; margin-bottom: 15px;">
	                                    <li>To prevent the store from losing customers</li>
	                                    <li>To prevent the store from losing money</li>
	                                </ul>
	                                <p>If a customer comes to you with a complaint, you must deal with that person courteously and effectively.  Complaints can be sorted out if you use the correct procedures and have the right attitude towards the person making the complaint.</p>
	                                <p>Always treat complaints as opportunities for improvement.</p>
	                                <p>If a complaint is made because goods are faulty the customer is legally entitled to their money back, even without a receipt, providing the customer can show they bought the product from <?php echo ucfirst($wb->savers_or_sp); ?>.</p>
	                                <p>In some cases it may be that the customer has no legal right to a refund or an exchange.  In this case we have a policy which allows goods to be returned.  This decision is made by the manager to maintain goodwill and customer service.</p>
	                            </div>
	                        </div>
	                        <div class="row">
	                            <div class="bg-red text-bold" style="padding: 15px; margin-bottom: 15px;">
	                                <p>Did you know?</p>
	                                <ul style="margin-left: 15px; margin-bottom: 15px;">
	                                    <li>The average unhappy customer will tell between 8 - 16 people</li>
	                                    <li>If you make an effort to sort out a problem, between 82% - 95% of customers will return</li>
	                                </ul>
	                            </div>
	                        </div>
	                        <div class="row">
	                            <div class="col-sm-12">
	                                <p>Below are some tips that will help you serve the customer efficiently and professionally.  You should follow the tips to minimise the chance of the customer becoming difficult. If your customer is being difficult from the beginning you should still follow the advice below to keep your customer service levels high.</p>
	                            </div>
	                        </div>
	                        <div class="row">
	                            <div class="col-sm-6">
	                                <ul style="margin-left: 15px; margin-bottom: 15px;">
	                                    <li><strong>Welcome </strong>the customer, smile and give them your full attention</li>
	                                    <li><strong>Listen </strong>carefully to what the customer is saying</li>
	                                    <li><strong>Ask questions </strong>to establish the reasons for the complaint</li>
	                                </ul>
	                            </div>
	                            <div class="col-sm-6">
	                                <ul style="margin-left: 15px; margin-bottom: 15px;">
	                                    <li>If you are unable to deal with the complaint, politely inform the customer that you will need to <strong>call your manager</strong></li>
	                                    <li><strong>Be friendly </strong>and polite at all times</li>
	                                    <li>Remember you must <strong>never argue </strong>with a customer</li>
	                                </ul>
	                            </div>
	                        </div>
	                        <p><br></p>

		                    <div class="row"><div class="col-sm-12"><div class="pull-right"><?php echo HTML::renderWorkbookIcons('', true); ?></div></div></div>
	                        <?php echo $wb->getPageBottomLine(); ?>
	                    </div>
	                    <!--.page 15 ends-->

	                    <h1>Customer</h1>

	                    <div class="step-content">
	                        <?php echo $wb->getPageTopLine(); ?>
		                    <div class="row"><div class="col-sm-12"><div class="pull-right">
			                    <?php
			                    if($feedback->GladSureSorryTechnique->Status->__toString() == 'NA')
				                    echo HTML::renderWorkbookIcons('GladSureSorryTechnique', false, 'btn-danger');
			                    else
				                    echo HTML::renderWorkbookIcons('GladSureSorryTechnique', false, 'btn-success');
			                    ?>
		                    </div></div></div>
	                        <div class="row" <?php echo $feedback->GladSureSorryTechnique->Status->__toString() == 'A'?'style="pointer-events:none;"':''; ?>>
	                            <div class="col-sm-12">
	                                <p class="text-bold">
			                            <img src="module_eportfolio/assets/images/wb2_img1.png" />
										<?php if($wb->savers_or_sp == 'savers') {?>
										&nbsp;When you have dealt with a complaint in your store? Write the details below
										<?php } else {?>
			                            &nbsp;Document a time when you have used the Glad, Sure, Sorry technique
										<?php } ?>
		                            </p>
	                                <textarea name="GladSureSorryTechnique" style="width: 100%" rows="15"><?php echo $answers->GladSureSorryTechnique->__toString(); ?></textarea>
	                            </div>
	                        </div>
	                        <div class="row">
	                            <div class="col-sm-12">
	                                <p class="text-bold">Lasting Impressions</p>
	                                <p>It is very important to smile, be friendly and polite.  A genuine 'Hi', 'Good morning' or 'Goodbye' will leave a lasting positive impression with your customers. If you think a customer needs help, a 'Hi' or another friendly greeting that you feel comfortable using is the best way to approach the customer.</p>
	                                <p>Be mindful to balance the needs of the customer and that of the company, there is a fine line between delivering great customer service and wasting time chatting!</p>

	                                <p>Also some customers are on their lunch break so don't have the time to chat themselves. When dealing with customers always remember:</p>
	                                <ul style="margin-left: 15px; margin-bottom: 15px;">
	                                    <li>Welcome customers with a smile and a friendly approach</li>
	                                    <li>Take a real interest in each customer and know when they need help</li>
	                                    <li>Have a professional, well-groomed image</li>
	                                    <li>Recognise customers who are waiting for service</li>
	                                    <li>Know as much as you can about the products and services you provide</li>
	                                </ul>
	                                <p>Ensuring you meet your customers' needs for information on the products and services you provide in store is essential.  It will ensure that you gain your customers' trust which will mean that they will return to your store and also tell their family and friends about the great customer service they received.</p>
	                            </div>
	                        </div>
		                    <?php if($wb->id != '' && $_SESSION['user']->type == User::TYPE_ASSESSOR && ($wb->wb_status == Workbook::STATUS_LEARNER_COMPLETED || $wb->wb_status == Workbook::STATUS_BEING_CHECKED || $wb->wb_status == Workbook::STATUS_IV_REJECTED)) {?>
		                    <div class="row well">
			                    <div class="col-sm-12">
				                    <div class="box box-success box-solid">
					                    <div class="box-header with-border"><h3 class="box-title">Assessor Feedback</h3></div>
					                    <div class="box-body assessorFeedback" <?php echo $feedback->GladSureSorryTechnique->Status->__toString() == 'A'?'style="pointer-events:none;"':''; ?>>
						                    <div class="form-group">
							                    <label for="status_GladSureSorryTechnique" class="col-sm-12 control-label">Status:</label>
							                    <div class="col-sm-12">
								                    <?php
								                    echo HTML::selectChosen('status_GladSureSorryTechnique', $answer_status, $feedback->GladSureSorryTechnique->Status->__toString() == 'A'?$feedback->GladSureSorryTechnique->Status->__toString():'', false, true); ?>
							                    </div>
						                    </div>
						                    <div class="form-group">
							                    <label for="comments_GladSureSorryTechnique" class="col-sm-12 control-label">Comments:</label>
							                    <div class="col-sm-12">
								                    <textarea name="comments_GladSureSorryTechnique" rows="7" style="width: 100%;"><?php echo $feedback->GladSureSorryTechnique->Comments->__toString(); ?></textarea>
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
			                    if($feedback->GladSureSorryTechnique->Status->__toString() == 'NA')
				                    echo HTML::renderWorkbookIcons('GladSureSorryTechnique', false, 'btn-danger');
			                    else
				                    echo HTML::renderWorkbookIcons('GladSureSorryTechnique', false, 'btn-success');
			                    ?>
		                    </div></div></div>
	                        <?php echo $wb->getPageBottomLine(); ?>
	                    </div>
	                    <!--.page 16 ends-->

	                    <h1>Customer</h1>

	                    <div class="step-content">
	                        <?php echo $wb->getPageTopLine(); ?>
		                    <div class="row"><div class="col-sm-12"><div class="pull-right"><?php echo HTML::renderWorkbookIcons('', true); ?></div></div></div>
	                        <p><br></p>
	                        <div class="row">
	                            <div class="col-sm-12">
	                                <p class="text-bold">The key features and benefits of excellent customer service</p>
	                            </div>
	                        </div>
	                        <div class="row">
	                            <div class="col-sm-12">
	                                <p>Excellent customer service is paramount to the success of a business. You will need to know how the business you work for approaches customer service and the procedures or processes in place to ensure that this occurs.  Every business will be unique in its approach to customer service; below are some examples of general features and benefits.</p>
	                            </div>
	                            <div class="col-sm-12">
	                                <img class="img-responsive" src="module_eportfolio/assets/images/wb3_pg5_img1.png" />
	                            </div>
	                            <div class="col-sm-12">
	                                <p><h4 class="text-bold">Meeting customers' needs</h4></p>
	                                <p class="text-bold">Some of the benefits of having excellent customer service are:</p>
	                                <ul style="margin-left: 15px; margin-bottom: 15px;">
	                                    <li>Customer retention</li>
	                                    <li>Repeat business</li>
	                                    <li>New customers</li>
	                                    <li>Excellent business reputation</li>
	                                    <li>Brand power</li>
	                                    <li>Best value</li>
	                                </ul>
	                            </div>
	                            <div class="col-sm-4">
	                                <div class="bg-red text-center" style="padding: 10px;">
	                                    <p>
	                                        <?php echo ucfirst($wb->savers_or_sp); ?> Customer Promise<br><br>
	                                        We want to make sure each and every one of our customers experience that '<?php echo ucfirst($wb->savers_or_sp); ?> Feeling'.<br><br>
	                                        Customer service is such an integral part of our business. One of our main aims at <?php echo ucfirst($wb->savers_or_sp); ?> is to ensure that we give you great customer service and an enjoyable, pleasant shopping experience, whilst offering the latest in beauty and health.
	                                    </p>
	                                </div>
	                            </div>
	                        </div>
	                        <p><br></p>
		                    <div class="row"><div class="col-sm-12"><div class="pull-right"><?php echo HTML::renderWorkbookIcons('', true); ?></div></div></div>
	                        <?php echo $wb->getPageBottomLine(); ?>
	                    </div>
	                    <!--.page 17 ends-->

						<h1>Customer</h1>

						<div class="step-content">
							<?php echo $wb->getPageTopLine(); ?>
							<div class="row"><div class="col-sm-12"><div class="pull-right">
								<?php
								if($feedback->GoodCustomerService->Status->__toString() == 'NA')
									echo HTML::renderWorkbookIcons('GoodCustomerService', false, 'btn-danger');
								else
									echo HTML::renderWorkbookIcons('GoodCustomerService', false, 'btn-success');
								?>
							</div></div></div>

							<div class="row">
								<div class="col-sm-12">
									<p class="text-bold"><img src="module_eportfolio/assets/images/wb2_img1.png" /> &nbsp;What specific features are there in <?php echo ucfirst($wb->savers_or_sp); ?>'s customer service offer? List them below and for each feature, show what the benefits are?</p>
									<div class="table-responsive">
										<table class="table table-bordered text-center" <?php echo $feedback->GoodCustomerService->Status->__toString() == 'A'?'style="pointer-events:none;"':''; ?>>
											<tr class="bg-gray"><th style="width: 33%;">Customer Service Offer</th><th style="width: 33%;">Features</th><th style="width: 34%">Benefits</th></tr>
											<tr><td>Customer Experience training</td><td>All staff are trained to the same standard across the business</td><td>Customers will get the same customer service experience in any store they visit</td></tr>
											<tr>
												<td><textarea name="GoodCustomerServiceSet1Offer" style="width: 100%"><?php echo $answers->GoodCustomerService->SuperdrugSpecificFeatures->Set1->Offer->__toString(); ?></textarea>
												</td><td><textarea name="GoodCustomerServiceSet1Features" style="width: 100%"><?php echo $answers->GoodCustomerService->SuperdrugSpecificFeatures->Set1->Features->__toString(); ?></textarea> </td>
												<td><textarea name="GoodCustomerServiceSet1Benefits" style="width: 100%"><?php echo $answers->GoodCustomerService->SuperdrugSpecificFeatures->Set1->Benefits->__toString(); ?></textarea> </td>
											</tr>
											<tr>
												<td><textarea name="GoodCustomerServiceSet2Offer" style="width: 100%"><?php echo $answers->GoodCustomerService->SuperdrugSpecificFeatures->Set2->Offer->__toString(); ?></textarea> </td>
												<td><textarea name="GoodCustomerServiceSet2Features" style="width: 100%"><?php echo $answers->GoodCustomerService->SuperdrugSpecificFeatures->Set2->Features->__toString(); ?></textarea> </td>
												<td><textarea name="GoodCustomerServiceSet2Benefits" style="width: 100%"><?php echo $answers->GoodCustomerService->SuperdrugSpecificFeatures->Set2->Benefits->__toString(); ?></textarea> </td>
											</tr>
										</table>
									</div>
								</div>
							</div>
							<div class="row">
								<div class="col-sm-6">
									<p class="text-bold">The importance of excellent customer service to business success and the implications of poor customer service</p>
									<p class="text-bold">1.	It's what customers will remember</p>
									<p>People will always remember if customer service was really great or really bad. If a person represents a company in a good way, customers will remember the company fondly. If a person represents the company in a negative way, they'll probably lose a customer and generate some bad press.</p>
									<p class="text-bold">2. It reflects on the entire business</p>
									<p>If a company has good customer service, people naturally assume they have good products. If they have bad customer service, people naturally assume they have bad products. This can apply to any area such as shipping, returns or services. Even though this may not be entirely true, a company needs to be aware that this is how customers can think.</p>
								</div>
								<div class="col-sm-6">
									<p class="text-bold">3. It shows customers you care</p>
									<p>When time is taken to courteously and effectively take care of customers' issues, it shows you truly care about them. A company should genuinely care about their customers, since they are the most important aspect of their business. Without customers, they won't sell any products or services. Customers who feel as though a company cares about them are much more likely to refer others and become repeat customers themselves.</p>
									<p class="text-bold">4. It is a great marketing angle</p>
									<p>From a business point of view, good customer service is a great marketing angle. It's something that can be used in advertisements. People like to hear you have had great customer service.  Anything that can help with effective marketing is worth the extra effort. This angle works best when other real-life customers talk about how great customer service is, so a good idea can be to ask for reviews.</p>
								</div>
							</div>

							<div class="row">
								<div class="col-sm-12" <?php echo $feedback->GoodCustomerService->Status->__toString() == 'A'?'style="pointer-events:none;"':''; ?>>
									<p class="text-bold"><img src="module_eportfolio/assets/images/wb2_img1.png" /> &nbsp; Identify a local retail business which has a good reputation for customer service and explain why you think this is?</p>
									<textarea name="StoreWithGoodCS" style="width: 100%;" rows="5"><?php echo $answers->GoodCustomerService->StoreWithGoodCS->__toString(); ?></textarea>
								</div>
							</div>

							<div class="row">
								<div class="col-sm-6">
									<p class="text-bold">Excellent customer service can help:</p>
									<ul style="margin-left: 15px;">
										<li>Increase customer loyalty</li>
										<li>Increase the amount of money each customer spends and how often they do</li>
										<li>Generate positive word-of-mouth and   reputation</li>
										<li>Decrease barriers to buying for example, if a business has an excellent reputation of customer service for refunds, it is more likely to entice a hesitant buyer to purchase from you</li>
									</ul>
									<p class="text-bold">Implications of poor customer service</p>
									<p>Just as excellent customer service impacts the success of a company, poor customer service has implications too.</p>
									<p class="text-bold">Loss of current customers</p>
									<p>Consumers tend to do business with a company because it's convenient, it's a habit, or they're looking for a particular product or service that's hard to find elsewhere.</p>
									<p>Even the most loyal customers can be turned away by poor levels of service.</p>
									<p class="text-bold">Loss of potential customers</p>
									<p>When new customers walk into a business and find themselves ignored, talked down to or subjected to long queues, they may head for the door before they even reach for their wallets.</p>
									<p class="text-bold">Loss of future customers</p>
									<p>Customers who experience poor service levels often tell their friends and family members about the bad experience to warn them away. This will cost the business potential customers. People will have already formed a negative opinion of the business before ever setting foot in the door.</p>
								</div>
								<div class="col-sm-6" <?php echo $feedback->GoodCustomerService->Status->__toString() == 'A'?'style="pointer-events:none;"':''; ?>>
									<p class="text-bold">Loss of reputation</p>
									<p>A reputation for poor service can be hard to shake. It can keep other businesses from partnering or working with you. It also can turn away good employment candidates who might assume that if customers are treated poorly, employees are treated badly as well.</p>
									<p class="text-bold">Loss of employees</p>
									<p>Even poor-performing employees don't like to be confronted by unhappy customers, which can result in high turnover within the workforce. It is costly and time consuming to constantly have to advertise for new workers, then recruit and train them.</p>
									<p class="text-bold">Loss of profits</p>
									<p>Poor customer service typically results in fewer customers, which translates into lower sales and profits for the business.</p>
									<p class="text-bold"><img src="module_eportfolio/assets/images/wb2_img1.png" /> &nbsp; Identify a local retail business which has a bad reputation for customer service and explain why you think this is.</p>
									<textarea name="StoreWithBadCS" style="width: 100%;" rows="5"><?php echo $answers->GoodCustomerService->StoreWithBadCS->__toString(); ?></textarea>
								</div>
							</div>

							<?php if($wb->id != '' && $_SESSION['user']->type == User::TYPE_ASSESSOR && ($wb->wb_status == Workbook::STATUS_LEARNER_COMPLETED || $wb->wb_status == Workbook::STATUS_BEING_CHECKED || $wb->wb_status == Workbook::STATUS_IV_REJECTED)) {?>
							<div class="row well">
								<div class="col-sm-12">
									<div class="box box-success box-solid">
										<div class="box-header with-border"><h3 class="box-title">Assessor Feedback</h3></div>
										<div class="box-body assessorFeedback" <?php echo $feedback->GoodCustomerService->Status->__toString() == 'A'?'style="pointer-events:none;"':''; ?>>
											<div class="form-group">
												<label for="status_GoodCustomerService" class="col-sm-12 control-label">Status:</label>
												<div class="col-sm-12">
													<?php
													echo HTML::selectChosen('status_GoodCustomerService', $answer_status, $feedback->GoodCustomerService->Status->__toString() == 'A'?$feedback->GoodCustomerService->Status->__toString():'', false, true); ?>
												</div>
											</div>
											<div class="form-group">
												<label for="comments_GoodCustomerService" class="col-sm-12 control-label">Comments:</label>
												<div class="col-sm-12">
													<textarea name="comments_GoodCustomerService" rows="7" style="width: 100%;"><?php echo $feedback->GoodCustomerService->Comments->__toString(); ?></textarea>
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
								if($feedback->GoodCustomerService->Status->__toString() == 'NA')
									echo HTML::renderWorkbookIcons('GoodCustomerService', false, 'btn-danger');
								else
									echo HTML::renderWorkbookIcons('GoodCustomerService', false, 'btn-success');
								?>
							</div></div></div>
							<?php echo $wb->getPageBottomLine(); ?>
						</div>
						<!--.page 18 ends-->

						<h1>Customer</h1>

						<div class="step-content">
							<?php echo $wb->getPageTopLine(); ?>
							<div class="row"><div class="col-sm-12"><div class="pull-right"><?php echo HTML::renderWorkbookIcons('', true); ?></div></div></div>

							<div class="row">
								<div class="col-sm-12">
									<p class="text-bold">The importance of repeat business and ways to encourage customer loyalty in the retail industry</p>
									<img class="pull-right" src="module_eportfolio/assets/images/wb3_pg6_img1.png" />
									<p class="text-bold">Repeat business</p>
									<p>Repeat business customers will recognise and trust the brand, this increases brand awareness and power and helps to build and expand the business reputation to other customers.</p>
									<p>Loyal customers tend to provide the business with a large part of its sales/profits. They are happy with the products offered and the standards of service they receive. The retail business will depend heavily on their loyal customers and must look after them if they wish to keep them.</p>
									<p>Customer loyalty is so vital that some retailers develop expensive loyalty schemes to try to ensure that customers will come to them when they need the products or services it offers. While these schemes are effective, a more significant factor to keep customers is excellent customer service.</p>
									<img class="pull-right" src="module_eportfolio/assets/images/wb_r02_pg20_img1.png" />
									<p>Another obvious yet surprisingly often overlooked reason repeat business is so important is that having a happy customer return means not having to spend money attracting a new one.</p>
									<p>When these satisfied customers then refer others to the company, there are again no costs associated with getting the new customers!</p>
									<img class="pull-right" src="module_eportfolio/assets/images/wb_r02_pg20_img2.png" />
									<p>All a company really has to do to make that happen is to treat the customer right when they do business with them. The best way to get MORE business is to do GOOD business. It may not be easy, but it really is that simple.</p>
									<p class="text-bold">How to encourage customer loyalty</p>
									<p>Building customer loyalty gives a high return on the time, effort and money invested in providing good customer service. Loyal customers buy more, more regularly, and the cost of selling to them is low. They will frequently recommend your business to others.</p>
									<p>Understanding customers, providing good service and staying in touch all help improve customer loyalty.</p>
								</div>
							</div>

							<p><br></p>
							<div class="row"><div class="col-sm-12"><div class="pull-right"><?php echo HTML::renderWorkbookIcons('', true); ?></div></div></div>
							<?php echo $wb->getPageBottomLine(); ?>
						</div>
						<!--.page 20 ends-->

						<h1>Customer</h1>

						<div class="step-content">
							<?php echo $wb->getPageTopLine(); ?>
							<div class="row"><div class="col-sm-12"><div class="pull-right">
								<?php
								if($feedback->CustomerLoyality->Status->__toString() == 'NA')
									echo HTML::renderWorkbookIcons('CustomerLoyality', false, 'btn-danger');
								else
									echo HTML::renderWorkbookIcons('CustomerLoyality', false, 'btn-success');
								?>
							</div></div></div>

							<div class="row">
								<div class="col-sm-12">
									<p class="text-bold"><img src="module_eportfolio/assets/images/wb2_img1.png" /> &nbsp; In the table below there are a number of ways to encourage customer loyalty. Think about how we encourage customer loyalty in <?php echo ucfirst($wb->savers_or_sp); ?> and make notes how you see this being done.
										You may not be able to give examples for all points so discuss them with your assessor to learn a bit more about each one.</p>
								</div>
								<div class="col-sm-12">
									<div class="table-responsive">
										<table class="table table-bordered" <?php echo $feedback->CustomerLoyality->Status->__toString() == 'A'?'style="pointer-events:none;"':''; ?>>
											<tr><th colspan="2" class="bg-blue text-center">1.	Make customer care a key part of a business strategy</th> </tr>
											<tr><th style="width: 50%;">Ways to encourage customer loyalty</th><th style="width: 50%;">How do we do this?</th></tr>
											<?php if($wb->savers_or_sp != 'savers') {?>
											<tr><td>Effective customer relationship management means organising the entire business to focus on the needs of customers.</td><td>E.g. everyone has had the same Customer Experience training. Gober Method toolkit</td></tr>
											<?php } ?>
											<tr>
												<td>Make sure customer-facing employees have all the information they need to serve customers. Give them powers to make certain decisions independently.</td>
												<td><textarea rows="3" name="CustomerLoyalityQ1" style="width: 100%;"><?php echo $answers->CustomerLoyality->Q1->__toString(); ?></textarea> </td>
											</tr>
											<tr>
												<td>Draw up procedures and standards for handling customer contact. For example, standards for speed and courtesy.</td>
												<td><textarea rows="3" name="CustomerLoyalityQ2" style="width: 100%;"><?php echo $answers->CustomerLoyality->Q2->__toString(); ?></textarea> </td>
											</tr>
											<tr><th colspan="2" class="bg-blue text-center">2.	Learn as much about your different customer segments as you can</th> </tr>
											<tr>
												<td>Find out what, when and how customers buy, and use this information to improve the service offered. Use a database to record information about customer's buying habits so the service can be tailored to meet their needs.</td>
												<td><textarea rows="3" name="CustomerLoyalityQ3" style="width: 100%;"><?php echo $answers->CustomerLoyality->Q3->__toString(); ?></textarea> </td>
											</tr>
											<tr><th colspan="2" class="bg-blue text-center">3.	Develop a brand around the company, products or services - (If customers can identify with a company and feel good about it, they will be more likely to remain loyal.)</th> </tr>
											<tr>
												<td>Provide consistently high levels of customer service.</td>
												<td><textarea rows="3" name="CustomerLoyalityQ4" style="width: 100%;"><?php echo $answers->CustomerLoyality->Q4->__toString(); ?></textarea> </td>
											</tr>
											<tr>
												<td>Design and deliver a 'customer experience'. This addresses how to handle customers whenever they contact the business. </td>
												<td><textarea rows="3" name="CustomerLoyalityQ5" style="width: 100%;"><?php echo $answers->CustomerLoyality->Q5->__toString(); ?></textarea> </td>
											</tr>
											<tr><th colspan="2" class="bg-blue text-center">4.	Essentials of customer care</th> </tr>
											<tr>
												<td>Think of ways to make life easier for customers. Try to save the customer inconvenience at every stage of the buying experience. For example, provide a simple procedure for returning unwanted goods. </td>
												<td><textarea rows="3" name="CustomerLoyalityQ6" style="width: 100%;"><?php echo $answers->CustomerLoyality->Q6->__toString(); ?></textarea> </td>
											</tr>
											<tr>
												<td>Identify and address weaknesses that could affect customer service.</td>
												<td><textarea rows="3" name="CustomerLoyalityQ7" style="width: 100%;"><?php echo $answers->CustomerLoyality->Q7->__toString(); ?></textarea> </td>
											</tr>
											<tr><th colspan="2" class="bg-blue text-center">5.	Measure customer service levels</th> </tr>
											<tr>
												<td>Identify key performance indicators (KPIs). For example, the number of complaints received. How long does a click and collect order take to get to store? Monitor KPIs regularly and make changes if necessary. For example, if the level of click and collect deliveries fall, you need to find out why and take steps to deal with the problem. </td>
												<td><textarea rows="3" name="CustomerLoyalityQ8" style="width: 100%;"><?php echo $answers->CustomerLoyality->Q8->__toString(); ?></textarea> </td>
											</tr>
											<tr>
												<td>Use 'mystery shoppers' to check standards of service at every point where customers interact with a business.</td>
												<td><textarea rows="3" name="CustomerLoyalityQ9" style="width: 100%;"><?php echo $answers->CustomerLoyality->Q9->__toString(); ?></textarea> </td>
											</tr>
											<tr><th colspan="2" class="bg-blue text-center">6.	Customer feedback - The more you know about customers, the better you can meet their needs</th> </tr>
											<tr>
												<td>Ask new customers why they chose you over the competition and existing customers what you could do better. Get feedback online by encouraging customers to engage on social media. </td>
												<td><textarea rows="3" name="CustomerLoyalityQ10" style="width: 100%;"><?php echo $answers->CustomerLoyality->Q10->__toString(); ?></textarea> </td>
											</tr>
											<tr><th colspan="2" class="bg-blue text-center">7.	Added-value schemes</th> </tr>
											<tr>
												<td>
													<p>A successful loyalty scheme pays for itself by encouraging more frequent purchases. The most common loyalty schemes are based on offering rewards to loyal customers.</p>
													<p>Retail businesses can offer loyalty cards which work this way. Some schemes offer customers a discount off their next purchase If the discount is only valid for a limited time, you encourage prompt action.</p>
												</td>
												<td><textarea rows="3" name="CustomerLoyalityQ11" style="width: 100%;"><?php echo $answers->CustomerLoyality->Q11->__toString(); ?></textarea> </td>
											</tr>
											<tr><th colspan="2" class="bg-blue text-center">8.	Employees</th> </tr>
											<tr>
												<td>
													<p>Make sure everyone gets the training they need.</p>
													<p>Staff who have regular contact with customers should receive training on customer service. They are at the front line of a business and need to give an efficient, professional image at all times.</p>
													<p>Make sure all staff have been carefully trained how to do their jobs and understand how it will affect the customer if they do not do it properly.</p>
												</td>
												<td><textarea rows="3" name="CustomerLoyalityQ12" style="width: 100%;"><?php echo $answers->CustomerLoyality->Q12->__toString(); ?></textarea> </td>
											</tr>
											<tr><th colspan="2" class="bg-blue text-center">9.	Technology</th> </tr>
											<tr>
												<td>Upgrading the technology you use could improve your customer relationships.</td>
												<td><textarea rows="3" name="CustomerLoyalityQ13" style="width: 100%;"><?php echo $answers->CustomerLoyality->Q13->__toString(); ?></textarea> </td>
											</tr>
										</table>
									</div>
								</div>
							</div>
							<?php if($wb->id != '' && $_SESSION['user']->type == User::TYPE_ASSESSOR && ($wb->wb_status == Workbook::STATUS_LEARNER_COMPLETED || $wb->wb_status == Workbook::STATUS_BEING_CHECKED || $wb->wb_status == Workbook::STATUS_IV_REJECTED)) {?>
							<div class="row well">
								<div class="col-sm-12">
									<div class="box box-success box-solid">
										<div class="box-header with-border"><h3 class="box-title">Assessor Feedback</h3></div>
										<div class="box-body assessorFeedback" <?php echo $feedback->CustomerLoyality->Status->__toString() == 'A'?'style="pointer-events:none;"':''; ?>>
											<div class="form-group">
												<label for="status_CustomerLoyality" class="col-sm-12 control-label">Status:</label>
												<div class="col-sm-12">
													<?php
													echo HTML::selectChosen('status_CustomerLoyality', $answer_status, $feedback->CustomerLoyality->Status->__toString() == 'A'?$feedback->CustomerLoyality->Status->__toString():'', false, true); ?>
												</div>
											</div>
											<div class="form-group">
												<label for="comments_CustomerLoyality" class="col-sm-12 control-label">Comments:</label>
												<div class="col-sm-12">
													<textarea name="comments_CustomerLoyality" rows="7" style="width: 100%;"><?php echo $feedback->CustomerLoyality->Comments->__toString(); ?></textarea>
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
								if($feedback->CustomerLoyality->Status->__toString() == 'NA')
									echo HTML::renderWorkbookIcons('CustomerLoyality', false, 'btn-danger');
								else
									echo HTML::renderWorkbookIcons('CustomerLoyality', false, 'btn-success');
								?>
							</div></div></div>
							<?php echo $wb->getPageBottomLine(); ?>
						</div>
						<!--.page 21 ends-->

						<h1>Customer</h1>

						<div class="step-content">
							<?php echo $wb->getPageTopLine(); ?>
							<div class="row"><div class="col-sm-12"><div class="pull-right">
								<?php
								if($feedback->CustomerExperience->Status->__toString() == 'NA')
									echo HTML::renderWorkbookIcons('CustomerExperience', false, 'btn-danger');
								else
									echo HTML::renderWorkbookIcons('CustomerExperience', false, 'btn-success');
								?>
							</div></div></div>

							<div class="row">
								<div class="col-sm-12" <?php echo $feedback->CustomerExperience->Status->__toString() == 'A'?'style="pointer-events:none;"':''; ?>>
									<p class="text-bold">What is meant by a 'customer experience?'</p>
									<p>The 'customer experience' is simply the result of what happens between the business and its customer during interactions. This includes how the experience of interactions feels and how they are perceived by each customer. The customers' perception will provoke an emotional response and this response can have both negative and positive effects.</p>
									<p>It is important to acknowledge that the 'customer experience' will differ depending on the type of business, for example, shopping in a high-end store will give a different experience to shopping in a lower cost store but customers can still have a positive response, depending on their needs and expectations.</p>
									<p>The experiences customers have during interactions with both the employee and the business will play a big part in forming their expectations and opinions. This includes how employees follow procedures and processes to ensure that customer complaints are acted upon to turn a potential negative experience into a positive one.</p>
									<p class="text-bold"><img src="module_eportfolio/assets/images/wb2_img1.png" /> &nbsp; What Customer Experience training have you had in <?php echo ucfirst($wb->savers_or_sp); ?>? What impact do you think it has on our customers? </p>
									<textarea name="CustomerExperienceYourTraining" style="width: 100%;" rows="5"><?php echo $answers->CustomerExperience->YourTraining->__toString(); ?></textarea>
								</div>
								<div class="col-sm-12">
									<p class="text-bold"><img src="module_eportfolio/assets/images/wb2_img1.png" /> &nbsp; Give an example of your own customer experiences in other retailers. How do you think it compares with what you do in <?php echo ucfirst($wb->savers_or_sp); ?>? </p>
									<div class="table-responsive">
										<table class="table table-bordered" <?php echo $feedback->CustomerExperience->Status->__toString() == 'A'?'style="pointer-events:none;"':''; ?>>
											<tr><th style="width: 33%">Store</th><th style="width: 33%">Experience</th><th style="width: 34%">How did the service compare with what you do? Good/Bad?</th></tr>
											<?php
											$YourExperienceWithOthers = $answers->CustomerExperience->YourExperienceWithOthers;
											for($i = 1; $i <= 3; $i++)
											{
												$set = 'Set'.$i;
												echo '<tr>';
												echo '<td><textarea name="CustomerExperience'.$set.'Store" style="width: 100%;">'.$YourExperienceWithOthers->$set->Store->__toString() . '</textarea></td>';
												echo '<td><textarea name="CustomerExperience'.$set.'Experience" style="width: 100%;">'.$YourExperienceWithOthers->$set->Experience->__toString() . '</textarea></td>';
												echo '<td><textarea name="CustomerExperience'.$set.'GoodOrBad" style="width: 100%;">'.$YourExperienceWithOthers->$set->GoodOrBad->__toString() . '</textarea></td>';
												echo '</tr>';
											}
											?>
										</table>
									</div>
									<p class="text-bold">Typical customer profile(s) - i.e. who the target customers of the business are and the products/services that they typically purchase</p>
									<p>A customer profile is a description of a customer or set of customers and will vary according to the retail business. They are a way for a company to build a picture of their customers and will include information on geographical, demographic, financial and other personal characteristics such as purchase or credit history.  Customer profiles will help the retail business identify and meet customers' needs and expectations.</p>
									<p>Some of the pieces of information that may be included in a customer profile are:</p>
									<ul style="margin-left: 15px;">
										<li>Location</li>
										<li>Gender</li>
										<li>Requirements</li>
										<li>Income</li>
										<li>Product limitations</li>
										<li>Timescales</li>
										<li>Interests and hobbies</li>
										<li>Preferences</li>
									</ul>
									<p>Recognising and meeting customers' needs and expectations forms a critical part of an organisation's ability to be successful. Identifying and understanding customers' needs and obtaining feedback enables an organisation to adjust its products and services, where required. This helps achieve more customer loyalty by building and maintaining relationships with customers.</p>
									<p class="text-bold"><img src="module_eportfolio/assets/images/wb2_img1.png" /> &nbsp; What do you think the typical customer profile of your store is? Speak to your team to also get their ideas. Do you think it is different from <?php echo ucfirst($wb->savers_or_sp); ?> stores in your area or others you have visited? </p>
									<p <?php echo $feedback->CustomerExperience->Status->__toString() == 'A'?'style="pointer-events:none;"':''; ?>><textarea name="CustomerExperienceTypicalCustomerProfile" style="width: 100%;" rows="7"><?php echo $answers->CustomerExperience->TypicalCustomerProfile->__toString(); ?></textarea></p>
								</div>
							</div>
							<?php if($wb->id != '' && $_SESSION['user']->type == User::TYPE_ASSESSOR && ($wb->wb_status == Workbook::STATUS_LEARNER_COMPLETED || $wb->wb_status == Workbook::STATUS_BEING_CHECKED || $wb->wb_status == Workbook::STATUS_IV_REJECTED)) {?>
							<div class="row well">
								<div class="col-sm-12">
									<div class="box box-success box-solid">
										<div class="box-header with-border"><h3 class="box-title">Assessor Feedback</h3></div>
										<div class="box-body assessorFeedback" <?php echo $feedback->CustomerExperience->Status->__toString() == 'A'?'style="pointer-events:none;"':''; ?>>
											<div class="form-group">
												<label for="status_CustomerExperience" class="col-sm-12 control-label">Status:</label>
												<div class="col-sm-12">
													<?php
													echo HTML::selectChosen('status_CustomerExperience', $answer_status, $feedback->CustomerExperience->Status->__toString() == 'A'?$feedback->CustomerExperience->Status->__toString():'', false, true); ?>
												</div>
											</div>
											<div class="form-group">
												<label for="comments_CustomerExperience" class="col-sm-12 control-label">Comments:</label>
												<div class="col-sm-12">
													<textarea name="comments_CustomerExperience" rows="7" style="width: 100%;"><?php echo $feedback->CustomerExperience->Comments->__toString(); ?></textarea>
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
								if($feedback->CustomerExperience->Status->__toString() == 'NA')
									echo HTML::renderWorkbookIcons('CustomerExperience', false, 'btn-danger');
								else
									echo HTML::renderWorkbookIcons('CustomerExperience', false, 'btn-success');
								?>
							</div></div></div>
							<?php echo $wb->getPageBottomLine(); ?>
						</div>
						<!--.page 22 ends-->

						<h1>Customer</h1>

						<div class="step-content">
							<?php echo $wb->getPageTopLine(); ?>
							<div class="row"><div class="col-sm-12"><div class="pull-right">
								<?php
								if($feedback->CustomerPurchasingHabit->Status->__toString() == 'NA')
									echo HTML::renderWorkbookIcons('CustomerPurchasingHabit', false, 'btn-danger');
								else
									echo HTML::renderWorkbookIcons('CustomerPurchasingHabit', false, 'btn-success');
								?>
							</div></div></div>

							<div class="row">
								<div class="col-sm-12">
									<p class="text-bold">What is a customer purchasing habit?</p>
									<p>Customer purchasing or buying habits are the tendencies customers have when purchasing products and services.</p>
									<p>It is described as the purchase of the same brands over and over again, more due to absence of dissatisfaction than because of a positive loyalty. Habit buying is associated usually with low involvement products such as toothpaste.</p>
									<p>There are two types of influences that significantly affect the buying habits of customers and are the ultimate factors that will sway customers to purchase your product or service. These two types of influences are internal and external.</p>

								</div>
							</div>
							<div class="row">
								<div class="col-sm-6"><img class="img-responsive" src="module_eportfolio/assets/images/wb_r02_pg25_img1.png" /> </div>
								<div class="col-sm-6"><img class="img-responsive" src="module_eportfolio/assets/images/wb_r02_pg25_img2.png" /> </div>
								<div class="col-sm-12">
									<div class="table-responsive">
										<table class="table table-bordered" <?php echo $feedback->CustomerPurchasingHabit->Status->__toString() == 'A'?'style="pointer-events:none;"':''; ?>>
											<tr><th colspan="2"><p class="text-bold"><img src="module_eportfolio/assets/images/wb2_img1.png" /> &nbsp; Fill in the empty boxes with some ideas of your own for both internal and external influences </p></th></tr>
											<tr><th style="width: 10%;" class="text-center"><h4 class="text-bold">A</h4></th><td><textarea rows="3" name="CustomerPurchasingHabitA" style="width: 100%;"><?php echo $answers->CustomerPurchasingHabit->A->__toString(); ?></textarea> </td></tr>
											<tr><th style="width: 10%;" class="text-center"><h4 class="text-bold">B</h4></th><td><textarea rows="3" name="CustomerPurchasingHabitB" style="width: 100%;"><?php echo $answers->CustomerPurchasingHabit->B->__toString(); ?></textarea> </td></tr>
											<tr><th style="width: 10%;" class="text-center"><h4 class="text-bold">C</h4></th><td><textarea rows="3" name="CustomerPurchasingHabitC" style="width: 100%;"><?php echo $answers->CustomerPurchasingHabit->C->__toString(); ?></textarea> </td></tr>
											<tr><th style="width: 10%;" class="text-center"><h4 class="text-bold">D</h4></th><td><textarea rows="3" name="CustomerPurchasingHabitD" style="width: 100%;"><?php echo $answers->CustomerPurchasingHabit->D->__toString(); ?></textarea> </td></tr>
										</table>
									</div>
								</div>
							</div>
							<?php if($wb->id != '' && $_SESSION['user']->type == User::TYPE_ASSESSOR && ($wb->wb_status == Workbook::STATUS_LEARNER_COMPLETED || $wb->wb_status == Workbook::STATUS_BEING_CHECKED || $wb->wb_status == Workbook::STATUS_IV_REJECTED)) {?>
							<div class="row well">
								<div class="col-sm-12">
									<div class="box box-success box-solid">
										<div class="box-header with-border"><h3 class="box-title">Assessor Feedback</h3></div>
										<div class="box-body assessorFeedback" <?php echo $feedback->CustomerPurchasingHabit->Status->__toString() == 'A'?'style="pointer-events:none;"':''; ?>>
											<div class="form-group">
												<label for="status_CustomerPurchasingHabit" class="col-sm-12 control-label">Status:</label>
												<div class="col-sm-12">
													<?php
													echo HTML::selectChosen('status_CustomerPurchasingHabit', $answer_status, $feedback->CustomerPurchasingHabit->Status->__toString() == 'A'?$feedback->CustomerPurchasingHabit->Status->__toString():'', false, true); ?>
												</div>
											</div>
											<div class="form-group">
												<label for="comments_CustomerPurchasingHabit" class="col-sm-12 control-label">Comments:</label>
												<div class="col-sm-12">
													<textarea name="comments_CustomerPurchasingHabit" rows="7" style="width: 100%;"><?php echo $feedback->CustomerPurchasingHabit->Comments->__toString(); ?></textarea>
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
								if($feedback->CustomerPurchasingHabit->Status->__toString() == 'NA')
									echo HTML::renderWorkbookIcons('CustomerPurchasingHabit', false, 'btn-danger');
								else
									echo HTML::renderWorkbookIcons('CustomerPurchasingHabit', false, 'btn-success');
								?>
							</div></div></div>
							<?php echo $wb->getPageBottomLine(); ?>
						</div>
						<!--.page 23 ends-->

						<h1>Customer</h1>

						<div class="step-content">
							<?php echo $wb->getPageTopLine(); ?>
							<div class="row"><div class="col-sm-12"><div class="pull-right"><?php echo HTML::renderWorkbookIcons('', true); ?></div></div></div>

							<div class="row">
								<div class="col-sm-6">
									<p class="text-bold">How customers purchasing habits are influenced through social trends and the media</p>
									<p>Social trends in the media can have a great influence on customers' purchasing habits.</p>
									<?php if($wb->savers_or_sp == 'savers') { ?>
									<img class="img-responsive pull-right" src="module_eportfolio/assets/images/wb_r02_pg26_img2_.png" />
									<?php } else { ?>
									<img class="img-responsive pull-right" src="module_eportfolio/assets/images/wb_r02_pg26_img2.png" />
									<?php } ?>
									<p>Advertising campaigns have proven to be hugely successful and can reach a wider audience through social media, television, radio and popular magazines or newspapers.</p>
								</div>
								<div class="col-sm-6">
									<p>Celebrities used in advertising can have a massive influence on customers' purchasing habits and TV programmes or movies are often sponsored by businesses also promoting their products throughout the production.</p>
									<?php if($wb->savers_or_sp == 'savers') { ?>
									<img class="img-responsive pull-right" src="module_eportfolio/assets/images/wb_r02_pg26_img1_.png" />
									<?php } else { ?>
									<img class="img-responsive pull-right" src="module_eportfolio/assets/images/wb_r02_pg26_img1.png" />
									<?php } ?>
									<p>Social media sites are widely used to post the latest products and services of a business and can reach a multitude of people in a short time with the ability to share and comment on them.</p>
								</div>
								<div class="col-sm-12">
									<p>Word-of-mouth advertising has always been an important part of achieving sales for a company. With social media, word-of-mouth advertising can go worldwide in an instant with a single message sent from a single consumer. With some networks, the consumer can communicate with thousands of people at the click of a button, easily spreading a message about a company. Of course, this has its' drawbacks, as negative information is just as easily spread.</p>
									<img class="img-responsive pull-right" src="module_eportfolio/assets/images/wb_r02_pg26_img4.png" />
									<p>One of the reasons that the Internet in general and social media in particular are so effective for consumers is that it's fast. Shoppers can easily look up hours of operation, location and online shopping opportunities while they are on the train or standing in line at the coffee shop. The more fresh content posted daily, the better chance a business has of getting on the first page of a search. Social media sites provide a means to keep content fresh, alive and active.</p>
									<img class="img-responsive pull-right" src="module_eportfolio/assets/images/wb_r02_pg26_img3.png" />
								</div>
								<div class="col-sm-6">
									<p>Before purchasing a product or service online, many of us will firstly look up a review to find out what others think about the product or how customer service is handled. If the first reviews we read are negative then this can influence our behaviour and lead us to search for an alternative.</p>
									<img class="img-responsive pull-right" src="module_eportfolio/assets/images/wb_r02_pg26_img5.png" />
								</div>
								<div class="col-sm-6">
									<p>Alternatively, good reviews will give us confidence to go ahead and make that purchase.</p>
									<img class="img-responsive pull-right" src="module_eportfolio/assets/images/wb_r02_pg26_img7.png" />
									<img class="img-responsive pull-right" src="module_eportfolio/assets/images/wb_r02_pg26_img6.png" />
								</div>
							</div>
							
							<p><br></p>
							<div class="row"><div class="col-sm-12"><div class="pull-right"><?php echo HTML::renderWorkbookIcons('', true); ?></div></div></div>
							<?php echo $wb->getPageBottomLine(); ?>
						</div>
						<!--.page 24 ends-->

						<h1>Customer</h1>

						<div class="step-content">
							<?php echo $wb->getPageTopLine(); ?>
							<div class="row"><div class="col-sm-12"><div class="pull-right">
								<?php
								if($feedback->Feedback->Status->__toString() == 'NA')
									echo HTML::renderWorkbookIcons('Feedback', false, 'btn-danger');
								else
									echo HTML::renderWorkbookIcons('Feedback', false, 'btn-success');
								?>
							</div></div></div>

							<?php $Feedback = $answers->Feedback; ?>
							<div class="row">
								<div class="col-sm-12">
									<p><span style="font-size: larger;" class="text-bold">What is feedback?</span> - The process of communicating with someone about something that they have done or said, with a view to changing or encouraging that behaviour.</p>
								</div>
								<div class="col-sm-12">
									<p><span class="text-bold">Effective feedback is that which is clearly heard, understood and accepted.</span> Those are the areas that are within your power. You have no control over whether the recipient chooses to act upon your feedback.</p>
									<p>Receiving Feedback</p>
									<img class="img-responsive pull-right" src="module_eportfolio/assets/images/wb_r02_pg27_img1.png" />
									<p>It is important to think about what skills you need to receive feedback, especially when it is something you don't want to hear, and not least because not everyone is skilled at giving feedback.</p>
									<p class="text-bold">Be Open to the Feedback</p>
									<p>In order to hear feedback, you need to listen to it. Don't think about what you're going to say in reply, just listen. Notice the non-verbal communication as well and listen to what your colleague is not saying, as well as what they are.</p>
									<p>You may want to use different types of questions to clarify the situation, and reflect back your understanding, including emotions.</p>
									<img class="img-responsive" src="module_eportfolio/assets/images/wb_r02_pg27_img2.png" />
									<p class="text-bold">For example, you might say:</p>
									<p>"When you said ..., would it be fair to say that you meant ... and felt ...?"</p>
									<p>"Have I understood correctly that when I did ..., you felt ...?"</p>
									<p>Make sure that your reflection and questions focus on behaviour and not personality.</p>
									<p>Emotional intelligence is essential. You need to be aware of your emotions (self-awareness) and also be able to manage them (self-control), so that even if the feedback causes an emotional response, you can control it.</p>
									<p class="text-bold">Finally</p>
									<p>Always thank the person who has given you the feedback. They have already seen that you have listened and understood, now accept it.</p>
									<p>Acceptance in this way does not mean that you need to act on it. However, you do then need to consider the feedback and decide how, if at all, you wish to act upon it. That is entirely up to you but remember that the person giving the feedback felt strongly enough to bother mentioning it to you.</p>
									<p>Do them the courtesy of at least giving the matter some consideration. If nothing else, with negative feedback, you want to know how not to generate that response again.</p>
								</div>
							</div>

							<div class="row">
								<div class="col-sm-12" <?php echo $feedback->Feedback->Status->__toString() == 'A'?'style="pointer-events:none;"':''; ?>>
									<p class="text-bold">Think about when you receive feedback at work; sometimes it may be more formal than others. Who gives you the feedback? What do they give you feedback about?</p>
									<div class="table-responsive">
										<table class="table table-bordered">
											<tr><th style="width: 50%;">Who gives you feedback?</th><th style="width: 50%;">What is the feedback about?</th></tr>
											<tr><td><textarea name="FeedbackQ1" style="width: 100%;" rows="7"><?php echo $Feedback->Q1->__toString(); ?></textarea> </td><td><textarea name="FeedbackQ2" style="width: 100%;" rows="7"><?php echo $Feedback->Q2->__toString(); ?></textarea> </td></tr>
										</table>
									</div>
									<p class="text-bold"><img src="module_eportfolio/assets/images/wb2_img1.png" /> &nbsp; Give an example of positive feedback you have received. What was the feedback and how did it make you feel?</p>
									<textarea name="FeedbackQ3" style="width: 100%;" rows="5"><?php echo $Feedback->Q3->__toString(); ?></textarea>
									<p class="text-bold"><img src="module_eportfolio/assets/images/wb2_img1.png" /> &nbsp; Give an example of feedback you have received about your customer service in store. What was the feedback and how did it make you feel?</p>
									<textarea name="FeedbackQ4" style="width: 100%;" rows="5"><?php echo $Feedback->Q4->__toString(); ?></textarea>
									<p class="text-bold"><img src="module_eportfolio/assets/images/wb2_img1.png" /> &nbsp; Have you ever been given some feedback which was negative or constructive? What was the feedback and how did it make you feel? Did you start to do anything differently afterwards?</p>
									<textarea name="FeedbackQ5" style="width: 100%;" rows="5"><?php echo $Feedback->Q5->__toString(); ?></textarea>
									<p class="text-bold"><img src="module_eportfolio/assets/images/wb2_img1.png" /> &nbsp; Have you ever given feedback to someone else in work? Write your example in the box below. Do you think it went well or would you do anything differently next time? If you haven't given anyone feedback, why not give it a go and see what happens.</p>
									<textarea name="FeedbackQ6" style="width: 100%;" rows="5"><?php echo $Feedback->Q6->__toString(); ?></textarea>
								</div>
							</div>

							<div class="row">
								<div class="col-sm-12">
									<p>On the previous pages we have looked at feedback given by colleagues, managers or perhaps customers to an individual. Next we will look at the importance of a business getting feedback from customers on a larger scale and how to do so.</p>
								</div>
								<div class="col-sm-6">
									<div class="bg-blue text-center text-bold">
										<p>Customer feedback is important because it provides business owners with insight that they can use to improve their business, products and/or overall customer experience</p>
									</div>
									<p class="text-bold"> It offers the best way to measure customer satisfaction</p>
									<p>Measuring customer satisfaction helps you determine whether your product or service meets or exceeds customer expectations.</p>
									<p class="text-bold">It provides actionable insight to create a better customer experience</p>
									<p>Improving the customer experience should be the primary reason for gathering customer feedback. The process of winning new business and retaining existing customers is getting harder and harder. Offering an amazing experience that keeps customers coming back and referring their friends, is the best way to stand out from the competition.</p>
									<img class="img-responsive" src="module_eportfolio/assets/images/wb_r02_pg30_img1.png" />
								</div>
								<div class="col-sm-6">
									<p>To create an amazing experience, customers need to be asked what they want and findings can be used to create a consistent, personalised experience. If a business can create an experience that is better than their competitors, customers will remain loyal and ignore tempting competitive offers.</p>
									<p class="text-bold">It can help improve a product or service</p>
									<p>Listening to customers is the only way to guarantee creating a product or service that they actually want to buy. Customer feedback is commonly used throughout the product development process to ensure that the end product is something that solves a customer's problem or fulfils a need.</p>
									<p class="text-bold">It can help improve customer retention</p>
									<p>Customer feedback offers a direct line of communication with customers so businesses can determine if a customer is unhappy with the product or service they are delivering, before losing their custom.</p>
									<p>A happy customer is a retained customer. By listening to unhappy customers, feedback can be used to ensure all customers have a better experience and will want to return.</p>
								</div>
								<div class="col-sm-12" <?php echo $feedback->Feedback->Status->__toString() == 'A'?'style="pointer-events:none;"':''; ?>>
									<p class="text-bold"><img src="module_eportfolio/assets/images/wb2_img1.png" /> &nbsp; What types of customer feedback can you think of that your store will have received?</p>
									<textarea name="FeedbackQ7" style="width: 100%;" rows="5"><?php echo $Feedback->Q7->__toString(); ?></textarea>
								</div>
								<div class="col-sm-12">
									<h4 class="text-bold"> How do we collect customer feedback?</h4>
									<p>It can be done in two different ways</p>
									<img class="img-responsive pull-right" src="module_eportfolio/assets/images/wb_r02_pg31_img1.png" />
									<ul style="margin-left: 15px;">
										<li>By receiving and recording unsolicited feedback</li>
										<li>By gathering solicited feedback</li>
									</ul>
									<p class="text-bold">Unsolicited feedback</p>
									<p>When feedback is unsolicited it means that it has not been asked for.</p>
									<p>Unsolicited feedback may come in the form of</p>
									<ul style="margin-left: 15px;">
										<li>Customers' informal comments about service</li>
										<li>Praise received from customers when they have experienced excellent customer service</li>
										<li>Complaints from customers when they are dissatisfied</li>
										<li>Things you have overheard customers say to each other</li>
									</ul>
									<p>One of the main benefits of unsolicited feedback is that customers tell you about the things that are important to them.</p>
									<p>Unsolicited feedback can easily be lost before it can be used to make improvements or to build on strengths. To avoid this it is useful to keep records of any feedback customers give to you and to pass it on to someone who has the responsibility to act upon it.</p>
									<p class="text-bold">Solicited feedback</p>
									<p>Solicited feedback is the feedback that customers have been asked to provide.</p>
									<p>It may be gathered using:</p>
									<p><span  class="text-bold">Surveys and questionnaires: </span>Retailers decide what they want customers to tell them about. They often ask customers to rate aspects of service (from poor to very good). The results are collated to give a cross-section of opinions. The combined feedback can be used to influence future decisions.</p>
									<p><span  class="text-bold">Comment books or cards:</span> Customers are invited to give their opinions about any aspect of dealing with the business. Customers may identify issues the management was not aware of. Feedback may come in the form of praise or criticism. When feedback has been obtained from customers it is sensible to act upon it and put improvements in place</p>
									<p class="text-bold"><img src="module_eportfolio/assets/images/wb2_img1.png" /> &nbsp; Where do you think <?php echo ucfirst($wb->savers_or_sp); ?> gets most of its customer feedback from?</p>
									<p <?php echo $feedback->Feedback->Status->__toString() == 'A'?'style="pointer-events:none;"':''; ?>><textarea name="FeedbackQ8" style="width: 100%;" rows="5"><?php echo $Feedback->Q8->__toString(); ?></textarea></p>
								</div>
							</div>
							<?php if($wb->id != '' && $_SESSION['user']->type == User::TYPE_ASSESSOR && ($wb->wb_status == Workbook::STATUS_LEARNER_COMPLETED || $wb->wb_status == Workbook::STATUS_BEING_CHECKED || $wb->wb_status == Workbook::STATUS_IV_REJECTED)) {?>
							<div class="row well">
								<div class="col-sm-12">
									<div class="box box-success box-solid">
										<div class="box-header with-border"><h3 class="box-title">Assessor Feedback</h3></div>
										<div class="box-body assessorFeedback" <?php echo $feedback->Feedback->Status->__toString() == 'A'?'style="pointer-events:none;"':''; ?>>
											<div class="form-group">
												<label for="status_Feedback" class="col-sm-12 control-label">Status:</label>
												<div class="col-sm-12">
													<?php
													echo HTML::selectChosen('status_Feedback', $answer_status, $feedback->Feedback->Status->__toString() == 'A'?$feedback->Feedback->Status->__toString():'', false, true); ?>
												</div>
											</div>
											<div class="form-group">
												<label for="comments_Feedback" class="col-sm-12 control-label">Comments:</label>
												<div class="col-sm-12">
													<textarea name="comments_Feedback" rows="7" style="width: 100%;"><?php echo $feedback->Feedback->Comments->__toString(); ?></textarea>
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
								if($feedback->Feedback->Status->__toString() == 'NA')
									echo HTML::renderWorkbookIcons('Feedback', false, 'btn-danger');
								else
									echo HTML::renderWorkbookIcons('Feedback', false, 'btn-success');
								?>
							</div></div></div>
							<?php echo $wb->getPageBottomLine(); ?>
						</div>
						<!--.page 25,26,27,28,29 ends-->

						<h1>Customer</h1>

						<div class="step-content">
							<?php echo $wb->getPageTopLine(); ?>
							<div class="row"><div class="col-sm-12"><div class="pull-right">
								<?php
								if($feedback->LocateCustomerInformation->Status->__toString() == 'NA')
									echo HTML::renderWorkbookIcons('LocateCustomerInformation', false, 'btn-danger');
								else
									echo HTML::renderWorkbookIcons('LocateCustomerInformation', false, 'btn-success');
								?>
							</div></div></div>

							<div class="row">
								<div class="col-sm-12">
									<p class="text-bold">Customer information</p>
									<p>Information on customers is kept in different locations and for different purposes within the retail business.</p>
									<p>Businesses may need to access customer details for different reasons:</p>
									<img class="img-responsive pull-right" src="module_eportfolio/assets/images/wb_r02_pg32_img1.png" />
									<ul style="margin-left: 15px;">
										<?php if($wb->savers_or_sp == 'savers'){?>
										<li>Complaints</li>
										<li>Customer feedback</li>
										<li>Customer orders</li>
										<li>Marketing campaigns</li>
										<li>Order and Collect orders</li>
										<li>Pharmacy prescriptions</li>
										<li>Beauty studio appointments</li>
										<li>Online shopping</li>
									<?php } else {?>
										<li>Beauty card registrations</li>
										<li>Beauty card searches</li>
										<li>Order and Collect orders</li>
										<li>Pharmacy prescriptions</li>
										<li>Beauty studio appointments</li>
										<li>Online shopping</li>
										<li>Complaints</li>
										<li>Customer feedback</li>
										<li>Marketing campaigns</li>
									<?php } ?>
									</ul>
									<p><br></p>
									<p class="text-bold">Where to find this information</p>
									<img class="img-responsive pull-right" src="module_eportfolio/assets/images/wb_r02_pg32_img2.png" />
									<ul style="margin-left: 15px;">
										<li>Tills</li>
										<li>iPad</li>
										<li>Back office database</li>
										<li>Head office database</li>
										<li>Pharmacy</li>
										<li>Internet</li>
										<li>Reports</li>
										<li>Appointments book</li>
									</ul>
									<p>Remember: All information held on customers is subject to confidentiality and data protection</p>
									<p class="text-bold">
										<img src="module_eportfolio/assets/images/wb2_img1.png" />
										<?php if($wb->savers_or_sp == 'savers'){?>
										&nbsp; When might you need to take customer personal information and what would you do with it?
										<?php } else {?>
										&nbsp; Describe a time when you have had to locate customer information to complete a task and record below
										<?php } ?>
									</p>
									<p <?php echo $feedback->LocateCustomerInformation->Status->__toString() == 'A'?'style="pointer-events:none;"':''; ?>><textarea name="LocateCustomerInformation" style="width: 100%;" rows="5"><?php echo $answers->LocateCustomerInformation->__toString(); ?></textarea></p>
								</div>
							</div>
							<?php if($wb->id != '' && $_SESSION['user']->type == User::TYPE_ASSESSOR && ($wb->wb_status == Workbook::STATUS_LEARNER_COMPLETED || $wb->wb_status == Workbook::STATUS_BEING_CHECKED || $wb->wb_status == Workbook::STATUS_IV_REJECTED)) {?>
							<div class="row well">
								<div class="col-sm-12">
									<div class="box box-success box-solid">
										<div class="box-header with-border"><h3 class="box-title">Assessor Feedback</h3></div>
										<div class="box-body assessorFeedback" <?php echo $feedback->LocateCustomerInformation->Status->__toString() == 'A'?'style="pointer-events:none;"':''; ?>>
											<div class="form-group">
												<label for="status_LocateCustomerInformation" class="col-sm-12 control-label">Status:</label>
												<div class="col-sm-12">
													<?php
													echo HTML::selectChosen('status_LocateCustomerInformation', $answer_status, $feedback->LocateCustomerInformation->Status->__toString() == 'A'?$feedback->LocateCustomerInformation->Status->__toString():'', false, true); ?>
												</div>
											</div>
											<div class="form-group">
												<label for="comments_LocateCustomerInformation" class="col-sm-12 control-label">Comments:</label>
												<div class="col-sm-12">
													<textarea name="comments_LocateCustomerInformation" rows="7" style="width: 100%;"><?php echo $feedback->LocateCustomerInformation->Comments->__toString(); ?></textarea>
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
								if($feedback->LocateCustomerInformation->Status->__toString() == 'NA')
									echo HTML::renderWorkbookIcons('LocateCustomerInformation', false, 'btn-danger');
								else
									echo HTML::renderWorkbookIcons('LocateCustomerInformation', false, 'btn-success');
								?>
							</div></div></div>
							<?php echo $wb->getPageBottomLine(); ?>
						</div>
						<!--.page 30 ends-->

						<h1>Customer</h1>

						<div class="step-content">
							<?php echo $wb->getPageTopLine(); ?>
							<div class="row"><div class="col-sm-12"><div class="pull-right">
								<?php
								if($feedback->PurchasingMethods->Status->__toString() == 'NA')
									echo HTML::renderWorkbookIcons('PurchasingMethods', false, 'btn-danger');
								else
									echo HTML::renderWorkbookIcons('PurchasingMethods', false, 'btn-success');
								?>
							</div></div></div>

							<div class="row">
								<div class="col-sm-6">
									<p class="text-bold"> Different methods customers want to use to purchase products e.g. in store, orders, online and how to make it a positive experience</p>
									<p>Customers will have a preference as to how they want to purchase products or services. More and more customers are turning to the internet to make their purchases, whether this is through 'click and collectâ€™ services where items can be reserved on line and picked up in store, or by online ordering and delivery direct to their door.</p>
									<p>Telephone ordering is also a popular time saving way to purchase services or products. These options for purchasing can often lack the personal touch and many customers still prefer to make their purchases in store so that they still get that face to face interaction.</p>
									<p class="text-bold">Multichannel sales</p>
									<p>This offers customers a choice of convenient purchasing methods such as in store, via a website, over the phone, mail order catalogues, via a mobile phone app and click and collect amongst others.</p>
									<p>Policies in place for delivery, charges and returning goods need to be the same across all channels and customer service must remain consistent.</p>
									<img class="img-responsive" src="module_eportfolio/assets/images/wb_r02_pg33_img1.png" />
								</div>
								<div class="col-sm-6" <?php echo $feedback->PurchasingMethods->Status->__toString() == 'A'?'style="pointer-events:none;"':''; ?>>
									<p>Customers often like the convenience and speed of ordering on line, they can sign up as regular customers, opening an account and only having to give their details once.</p>
									<p>Some businesses will allow customers to order goods using just 1 click of the mouse. Potentially there are issues with this method of purchasing. The business will put measures in place such as password protection and use key words or phrases as an additional level of security, but the risk of accounts being hacked should always be considered.</p>
									<p class="text-bold">
										<img src="module_eportfolio/assets/images/wb2_img1.png" />
										<?php if($wb->savers_or_sp == 'savers'){?>
										&nbsp; List below the different ways that customers of AS Watson can make a purchase?
										<?php } else {?>
										&nbsp; List below the different ways that customers of Superdrug can make a purchase?
										<?php } ?>
									</p>
									<textarea name="PurchasingMethodsQ1" style="width: 100%;" rows="10"><?php echo $answers->PurchasingMethods->Q1->__toString(); ?></textarea>
									<p class="text-bold">
										<img src="module_eportfolio/assets/images/wb2_img1.png" />
										<?php if($wb->savers_or_sp == 'savers'){?>
										&nbsp; Explain how AS Watson ensures that customer receive a positive experience when using online services?
										<?php } else {?>
										&nbsp; Explain the order and collect process and how do you ensure it is a positive experience for the customer?
										<?php } ?>
									</p>
									<textarea name="PurchasingMethodsQ2" style="width: 100%;" rows="10"><?php echo $answers->PurchasingMethods->Q2->__toString(); ?></textarea>
								</div>
							</div>
							<?php if($wb->id != '' && $_SESSION['user']->type == User::TYPE_ASSESSOR && ($wb->wb_status == Workbook::STATUS_LEARNER_COMPLETED || $wb->wb_status == Workbook::STATUS_BEING_CHECKED || $wb->wb_status == Workbook::STATUS_IV_REJECTED)) {?>
							<div class="row well">
								<div class="col-sm-12">
									<div class="box box-success box-solid">
										<div class="box-header with-border"><h3 class="box-title">Assessor Feedback</h3></div>
										<div class="box-body assessorFeedback" <?php echo $feedback->PurchasingMethods->Status->__toString() == 'A'?'style="pointer-events:none;"':''; ?>>
											<div class="form-group">
												<label for="status_PurchasingMethods" class="col-sm-12 control-label">Status:</label>
												<div class="col-sm-12">
													<?php
													echo HTML::selectChosen('status_PurchasingMethods', $answer_status, $feedback->PurchasingMethods->Status->__toString() == 'A'?$feedback->PurchasingMethods->Status->__toString():'', false, true); ?>
												</div>
											</div>
											<div class="form-group">
												<label for="comments_PurchasingMethods" class="col-sm-12 control-label">Comments:</label>
												<div class="col-sm-12">
													<textarea name="comments_PurchasingMethods" rows="7" style="width: 100%;"><?php echo $feedback->PurchasingMethods->Comments->__toString(); ?></textarea>
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
								if($feedback->PurchasingMethods->Status->__toString() == 'NA')
									echo HTML::renderWorkbookIcons('PurchasingMethods', false, 'btn-danger');
								else
									echo HTML::renderWorkbookIcons('PurchasingMethods', false, 'btn-success');
								?>
							</div></div></div>
							<?php echo $wb->getPageBottomLine(); ?>
						</div>
						<!--.page 33 ends-->

						<h1>Customer</h1>

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
									<h3 class="text-bold">Qualification questions</h3>
								</div>
							</div>

							<?php $QualificationQuestions = $answers->QualificationQuestions; ?>
							<div class="row">
								<div class="col-sm-12" <?php echo $feedback->QualificationQuestions->Status->__toString() == 'A'?'style="pointer-events:none;"':''; ?>>
									<p class="callout callout-info text-bold">Now you have completed the section on Business and Brand reputation answer the following questions:</p>
									<p class="text-bold">Unit 1 - 1.1: Identify and describe different types of customer to the business</p>
									<textarea name="Question1" rows="7" style="width: 100%;"><?php echo $QualificationQuestions->Question1->__toString(); ?></textarea>
									<p><br></p>
									<p class="text-bold">Unit 1 - 1.2 Describe what a customer purchasing habit is</p>
									<textarea name="Question2" rows="7" style="width: 100%;"><?php echo $QualificationQuestions->Question2->__toString(); ?></textarea>
									<p><br></p>
									<p class="text-bold">Unit 1 - 1.3  Explain how knowing your customer purchasing habits can help to increase sales</p>
									<textarea name="Question3" rows="7" style="width: 100%;"><?php echo $QualificationQuestions->Question3->__toString(); ?></textarea>
									<p><br></p>
									<p class="text-bold">Unit 1 - 2.3 Describe how to support and increase sales</p>
									<textarea name="Question4" rows="7" style="width: 100%;"><?php echo $QualificationQuestions->Question4->__toString(); ?></textarea>
									<p><br></p>
									<p class="text-bold">Unit 1 - 2.4 Explain how to encourage customer loyalty and how this increases sales</p>
									<textarea name="Question5" rows="7" style="width: 100%;"><?php echo $QualificationQuestions->Question5->__toString(); ?></textarea>
									<?php if($wb->savers_or_sp == 'savers'){?>
									<p><br></p>
									<p class="text-bold">Unit 1 - 3.1 Identify how customer feedback can be obtained</p>
									<textarea name="Question6" rows="7" style="width: 100%;"><?php echo $QualificationQuestions->Question6->__toString(); ?></textarea>
									<p><br></p>
									<p class="text-bold">Unit 1 - 3.2 Describe how customer feedback can help to improve own quality of service provision</p>
									<textarea name="Question7" rows="7" style="width: 100%;"><?php echo $QualificationQuestions->Question7->__toString(); ?></textarea>
									<?php }?>
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
						<!--.page 34,35 ends-->

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

		$("#frm_wb_customer :input").not(".assessorFeedback :input, #signature_text, #frm_wb_customer :input[type=hidden], .btnViewSectionHistory, .btnViewAssessorComments, #iv_status, #iv_comments, #reopen_comments").prop("disabled", true);

		<?php } ?>

		$("#wizard").steps({
			transitionEffect:"fade",
			transitionEffectSpeed:500,
			//startIndex:11,
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
							var myForm = document.forms['frm_wb_customer'];
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
				var myForm = document.forms['frm_wb_customer'];
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

		$('input[type=radio]').iCheck({
			radioClass: 'iradio_square-green'
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

					var myForm = document.forms['frm_wb_customer'];
					myForm.elements['full_save'].value = 'Y';
					myForm.elements['full_save_feedback'].value = 'Y';
					window.onbeforeunload = null;
					myForm.submit();

				},
				'Save And Come Back Later':function () {

					var myForm = document.forms['frm_wb_customer'];
					myForm.elements['full_save'].value = 'Y';
					myForm.elements['full_save_feedback'].value = 'N';
					window.onbeforeunload = null;
					myForm.submit();

				}
			}
		});
	});

	function partialSave() {
		$('#frm_wb_customer :input[name=full_save]').val('N');
		$($('#frm_wb_customer').serializeArray()).each(function(i, field)
		{
			document.forms["frm_wb_customer"].elements[field.name].value = field.value.replace(/£/g, "GBP");
		});
		$.ajax({
			type:'POST',
			url:'do.php?_action=save_wb_customer',
			data:$('#frm_wb_customer').serialize(),
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
