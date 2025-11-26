<?php /* @var $wb WBKnowingYourCustomers */ ?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/html" xmlns="http://www.w3.org/1999/html" xmlns="http://www.w3.org/1999/html"
      xmlns="http://www.w3.org/1999/html">
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<title>Knowing Your Customers</title>
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
</head>

<body>
<nav id="mainNav" class="navbar navbar-default navbar-fixed-top navbar-custom">
	<div class="container-float">
		<div class="navbar-header page-scroll">
			<a class="navbar-brand" href="#"><img height="35px" class="headerlogo" src="images/logos/<?php echo $wb->getHeaderLogo($link); ?>" /></a>
		</div>
	</div>
	<div class="pull-right" id="clock"></div>
	<div class="text-center" style="margin-top: 5px;"><h3><?php echo $tr->firstnames . ' '  . strtoupper($tr->surname); ?></h3></div>
</nav>

<form name="frm_wb_knowing_your_customers" id="frm_wb_knowing_your_customers" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
	<input type="hidden" name="_action" value="save_wb_knowing_your_customers" />
	<input type="hidden" name="id" value="<?php echo $wb->id; ?>" />
	<input type="hidden" name="tr_id" value="<?php echo $wb->tr_id; ?>" />
	<input type="hidden" name="wb_status" id="wb_status" value="" />
	<input type="hidden" name="full_save" id="full_save" value="<?php echo $wb->full_save; ?>" />
	<input type="hidden" name="full_save_feedback" id="full_save_feedback" value="<?php echo $wb->full_save_feedback; ?>" />

	<div class="container-float">
		<div class="wrapper" style="background-color: #ffffff;">

		<header class="main-header"><span style="margin-left: 50%;"><?php echo DAO::getSingleValue($link, "SELECT title FROM student_frameworks WHERE tr_id = '{$wb->tr_id}'"); ?></span></header>

		<div class="content-wrapper" style="background-color: #ffffff;">

		<?php echo $wb->savers_or_sp == 'savers' ? '<section class="content-header"><h1 style="color: #0000ff;">Knowing your Customers</h1></section>' : '<section class="content-header"><h1>Knowing your Customers</h1></section>' ?>

		<section class="content">

		<div id="wizard">

			<h1>Knowing your Customers</h1>
			<div class="step-content">
				<?php echo $wb->getPageTopLine(); ?>
				<div style="position: absolute; top: 40%; right: 50%;" class="lead">
					<?php echo $wb->savers_or_sp == 'savers' ? '<h2 class="text-bold" style="color: #0000ff;">Knowing your Customers</h2>' : '<h2 class="text-bold"> Knowing your Customers</h2>' ?>
					<p class="text-center" >Module 4</p>
				</div>


				<?php echo $wb->getPageBottomLine(); ?>
			</div> <!--.page 1 ends-->

			<h1>Knowing your Customers</h1>
			<div class="step-content">
				<?php echo $wb->getPageTopLine(); ?>

				<div class="row"><div class="col-sm-12"><div class="pull-right"><?php echo HTML::renderWorkbookIcons('', true); ?></div></div></div>

				<div class="row">
					<div class="col-sm-12">

						<p>This module is about knowing and understanding your customers and what you need to know and do.</p>
						<p>Customer service is about making every interaction with a customer an opportunity to increase, gain, maintain or re-establish their loyalty. It is about delivering excellent customer service in line with the business culture and values in all activities. In retail, customer service can be the way that customers are treated when they make contact with the shop or store, but that is just a part of a much bigger picture.</p>
						<p>Everything you do in retail contributes to customer service and the experiences your customers enjoy when they deal with you. Customer service is a topic that you should be able to relate to because you are a customer almost every day of your life. You may have been disappointed by the poor customer service you have received. However, you should be able to think about occasions when customer service has been good and an organisation has really done all it can to meet your needs. This is the excellent standard you should be trying to achieve. To be able to do this you need to understand your customers.</p>
						<p>In this module you will look at:</p>
						<ul style="margin-left: 15px; margin-bottom: 15px;">
							<li>Your own customer service training</li>
							<li>The difference between internal and external customers</li>
							<li>Specific needs of different customers</li>
							<li>Customer expectations</li>
							<li>Equality and Diversity</li>
							<li>Why it is important to build good customer relationships</li>
						</ul>

					</div>
					<div class="col-sm-12">
						<img src="module_eportfolio/assets/images/wb4_pg1_img1.png" />
					</div>
				</div>
				<p><br></p>

				<div class="row"><div class="col-sm-12"><div class="pull-right"><?php echo HTML::renderWorkbookIcons('', true); ?></div></div></div>

				<?php echo $wb->getPageBottomLine(); ?>
			</div> <!--.page 2 ends-->

			<h1>Knowing your Customers</h1>
			<div class="step-content">
				<?php echo $wb->getPageTopLine(); ?>

				<div class="row"><div class="col-sm-12"><div class="pull-right"><button title="save information" class="btn btn-warning dim" type="button" onclick="partialSave();"><i class="fa fa-save"></i> </button><?php echo HTML::renderWorkbookIcons('', true); ?></div></div></div>

				<div class="row">
					<div class="col-sm-12"><p><br></p></div>
					<div class="col-sm-12"><p><img src="module_eportfolio/assets/images/wb4_pg2_img1.png" /> <strong>Learner journey / Visit plan</strong></p></div>
					<div class="col-sm-12"><p>Before you start this module please ensure you have completed all of the training detailed below. If you haven’t you will need to speak to your manager / mentor to arrange when you will complete it.</p></div>
				</div>
				<div class="row">
					<div class="col-sm-12 table-responsive">
						<table class="table table-bordered table-striped">
							<tr><th>Learning</th><th><?php echo $wb->savers_or_sp == 'savers' ? 'Workbooks/First steps' : 'Workbook/IPad/In-store activity'; ?></th><th>Date completed</th></tr>
							<?php
							$items = WBKnowingYourCustomers::getLearningJourneyItems($wb->savers_or_sp);
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

				<div class="row"><div class="col-sm-12"><div class="pull-right"><button title="save information" class="btn btn-warning dim" type="button" onclick="partialSave();"><i class="fa fa-save"></i> </button><?php echo HTML::renderWorkbookIcons('', true); ?></div></div></div>

				<?php echo $wb->getPageBottomLine(); ?>
			</div> <!--.page 3 ends-->

			<h1>Knowing your Customers</h1>
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


				<div class="row" style="display: flex;">
					<div class="col-sm-6 text-center" style="flex: 1; padding: 1em; background-color: #ff0000; font-weight: bolder;">
						<h3>Customer</h3>
						<p>A person who buys goods or services from a shop or business</p>
						<p>A person of a specified kind with whom one has to deal</p>
					</div>
					<div class="col-sm-6 text-center"  style="flex: 1; padding: 1em; background-color: #82CAFF; font-weight: bolder; " >
						<h3>Customer Service</h3>
						<p>Customer service is the provision of service to customers before, during and after a purchase</p>
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
							<img src="module_eportfolio/assets/images/wb4_img1.png" />
						</p>
					</div>
					<div class="col-sm-12 table-responsive">
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
							<tr><td>Apprenticeship Assessor</td><td><input type="radio" name="AA[]" value="E" <?php echo in_array('AA', $ExternalCustomers)?'checked="checked"':''; ?> /></td><td><input type="radio" name="AA[]" value="I" <?php echo in_array('AA', $InternalCustomers)?'checked="checked"':''; ?> /></td></tr>
							<?php if($wb->savers_or_sp != 'savers') { ?>
							<tr><td>Store Pharmacist</td><td><input type="radio" name="SP[]" value="E" <?php echo in_array('SP', $ExternalCustomers)?'checked="checked"':''; ?> /></td><td><input type="radio" name="SP[]" value="I" <?php echo in_array('SP', $InternalCustomers)?'checked="checked"':''; ?> /></td></tr>
							<?php } ?>
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
										<textarea name="comments_InternalAndExternalCustomers" rows="7" style="width: 100%;"><?php echo $feedback->InternalAndExternalCustomers->Status->__toString() == 'A'?$feedback->InternalAndExternalCustomers->Comments->__toString():''; ?></textarea>
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
			</div> <!--.page 4 ends-->

			<h1>Knowing your Customers</h1>
			<div class="step-content">
				<?php echo $wb->getPageTopLine(); ?>

				<div class="row"><div class="col-sm-12"><div class="pull-right">
					<?php
					if($feedback->StoreCategories->Status->__toString() == 'NA')
						echo HTML::renderWorkbookIcons('StoreCategories', false, 'btn-danger');
					else
						echo HTML::renderWorkbookIcons('StoreCategories', false, 'btn-success');
					?>
				</div></div></div>


				<div class="row">
					<div class="col-sm-6">
						<p><strong>Types of customers</strong></p>
						<p>An organisation may have many different types of customers. A customer can come in many different forms. They can be an individual or another organisation – as long as they receive a form of customer service from a service deliverer, they are a customer.</p>
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
						<p><img src="module_eportfolio/assets/images/wb4_pg5_img1.png" /> </p>
					</div>
				</div>
				<div class="row">
					<div class="col-sm-12 text-center text-bold">
						<p>
							<img src="module_eportfolio/assets/images/wb2_img1.png" /> &nbsp;
							Based on the information above, which category do the customers in your store fit in to? Add your comments to each in the table below. &nbsp;
							<img src="module_eportfolio/assets/images/wb4_img1.png" />
						</p>
					</div>
				</div>
				<div class="row" <?php echo $feedback->StoreCategories->Status->__toString() == 'A'?'style="pointer-events:none;"':''; ?>>
					<div class="col-sm-12 table-responsive">
						<table class="table row-border">
							<tr><th>Type of customer</th><th>Comment</th></tr>
							<tr><td><strong>Loyal</strong></td><td><textarea name="Loyal" rows="5" cols="50" style="width: 100%;"><?php echo $answers->StoreCategories->Loyal->__toString(); ?></textarea> </td></tr>
							<tr><td><strong>Discount</strong></td><td><textarea name="Discount" rows="5" cols="50" style="width: 100%;"><?php echo $answers->StoreCategories->Discount->__toString(); ?></textarea> </td></tr>
							<tr><td><strong>Impulse</strong></td><td><textarea name="Impulse" rows="5" cols="50" style="width: 100%;"><?php echo $answers->StoreCategories->Impulse->__toString(); ?></textarea> </td></tr>
							<tr><td><strong>Wandering</strong></td><td><textarea name="Wandering" rows="5" cols="50" style="width: 100%;"><?php echo $answers->StoreCategories->Wandering->__toString(); ?></textarea> </td></tr>
							<tr><td><strong>Need-based</strong></td><td><textarea name="NeedBased" rows="5" cols="50" style="width: 100%;"><?php echo $answers->StoreCategories->NeedBased->__toString(); ?></textarea> </td></tr>
						</table>
					</div>
				</div>

				<?php if($wb->id != '' && $_SESSION['user']->type == User::TYPE_ASSESSOR && ($wb->wb_status == Workbook::STATUS_LEARNER_COMPLETED || $wb->wb_status == Workbook::STATUS_BEING_CHECKED || $wb->wb_status == Workbook::STATUS_IV_REJECTED)) {?>
				<div class="row well">
					<div class="col-sm-12">
						<div class="box box-success box-solid">
							<div class="box-header with-border"><h3 class="box-title">Assessor Feedback</h3></div>
							<div class="box-body assessorFeedback" <?php echo $feedback->StoreCategories->Status->__toString() == 'A'?'style="pointer-events:none;"':''; ?>>
								<div class="form-group">
									<label for="status_StoreCategories" class="col-sm-12 control-label">Status:</label>
									<div class="col-sm-12">
										<?php
										echo HTML::selectChosen('status_StoreCategories', $answer_status, $feedback->StoreCategories->Status->__toString() == 'A'?$feedback->StoreCategories->Status->__toString():'', false, true); ?>
									</div>
								</div>
								<div class="form-group">
									<label for="comments_StoreCategories" class="col-sm-12 control-label">Comments:</label>
									<div class="col-sm-12">
										<textarea name="comments_StoreCategories" rows="7" style="width: 100%;"><?php echo $feedback->StoreCategories->Comments->__toString(); ?></textarea>
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
					if($feedback->StoreCategories->Status->__toString() == 'NA')
						echo HTML::renderWorkbookIcons('StoreCategories', false, 'btn-danger');
					else
						echo HTML::renderWorkbookIcons('StoreCategories', false, 'btn-success');
					?>
				</div></div></div>

				<?php echo $wb->getPageBottomLine(); ?>
			</div> <!--.page 5 ends-->

			<h1>Knowing your Customers</h1>
			<div class="step-content">
				<?php echo $wb->getPageTopLine(); ?>

				<div class="row"><div class="col-sm-12"><div class="pull-right">
					<?php
					if($feedback->CustomerExpectations->Status->__toString() == 'NA')
						echo HTML::renderWorkbookIcons('CustomerExpectations', false, 'btn-danger');
					else
						echo HTML::renderWorkbookIcons('CustomerExpectations', false, 'btn-success');
					?>
				</div></div></div>


				<div class="row">
					<div class="col-sm-12">
						<div class="bg-green text-bold text-center" style="padding: 15px;">
							<p><h3>Customer Expectations</h3></p>
							<p>Customer expectations are what customers thinks should happen and how they think they should be treated</p>
						</div>
						<p>Customer expectations are what customers think should happen and how they think they should be treated. These expectations can be formed in a number of ways.</p>
						<p>One way may be the culture of the organisation. For example, if customer service is really important to the organisation and its staff then it will have a customer service culture and customers’ expectations will be higher than that of a company that does not have such a culture. This may affect customers’ buying habits and approaches. For example, if the key factor for a customer is price (they just want to get the cheapest they can) they will expect a bargain but not necessarily expect great customer service.</p>
					</div>
				</div>
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
				<div class="row text-center" <?php echo $feedback->CustomerExpectations->Status->__toString() == 'A'?'style="pointer-events:none;"':''; ?>>
					<div class="col-sm-5">
						<p><textarea name="Higher" rows="5" cols="50" style="width: 100%;"><?php echo $answers->CustomerExpectations->Higher->__toString(); ?></textarea></p>
					</div>
					<div class="col-sm-2">
						<img src="module_eportfolio/assets/images/wb4_img1.png" />
					</div>
					<div class="col-sm-5">
						<p><textarea name="Lower" rows="5" cols="50" style="width: 100%;"><?php echo $answers->CustomerExpectations->Lower->__toString(); ?></textarea></p>
					</div>
				</div>
				<div class="row">
					<div class="col-sm-12">
						<p>Understanding customers’ needs and the ability to meet their needs in line with the business is essential.
							When we think about customer service we are often thinking about how we can meet customers’ needs. It is important to understand that these needs can be based on several factors.
						</p>
					</div>
				</div>
				<div class="row">
					<div class="col-sm-6">
						<p><strong>Cultural and other factors which may affect customer expectations include:</strong></p>
						<ul style="margin-left: 15px;">
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
						<p><strong>Other examples of customers’ needs and priorities within the retail business could include:</strong></p>
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

				<?php if($wb->id != '' && $_SESSION['user']->type == User::TYPE_ASSESSOR && ($wb->wb_status == Workbook::STATUS_LEARNER_COMPLETED || $wb->wb_status == Workbook::STATUS_BEING_CHECKED || $wb->wb_status == Workbook::STATUS_IV_REJECTED)) {?>
				<div class="row well">
					<div class="col-sm-12">
						<div class="box box-success box-solid">
							<div class="box-header with-border"><h3 class="box-title">Assessor Feedback</h3></div>
							<div class="box-body assessorFeedback" <?php echo $feedback->CustomerExpectations->Status->__toString() == 'A'?'style="pointer-events:none;"':''; ?>>
								<div class="form-group">
									<label for="status_CustomerExpectations" class="col-sm-12 control-label">Status:</label>
									<div class="col-sm-12">
										<?php
										echo HTML::selectChosen('status_CustomerExpectations', $answer_status, $feedback->CustomerExpectations->Status->__toString() == 'A'?$feedback->CustomerExpectations->Status->__toString():'', false, true); ?>
									</div>
								</div>
								<div class="form-group">
									<label for="comments_CustomerExpectations" class="col-sm-12 control-label">Comments:</label>
									<div class="col-sm-12">
										<textarea name="comments_CustomerExpectations" rows="7" style="width: 100%;"><?php echo $feedback->CustomerExpectations->Comments->__toString(); ?></textarea>
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
					if($feedback->CustomerExpectations->Status->__toString() == 'NA')
						echo HTML::renderWorkbookIcons('CustomerExpectations', false, 'btn-danger');
					else
						echo HTML::renderWorkbookIcons('CustomerExpectations', false, 'btn-success');
					?>
				</div></div></div>

				<?php echo $wb->getPageBottomLine(); ?>
			</div> <!--.page 6 ends-->

			<h1>Knowing your Customers</h1>
			<div class="step-content">
				<?php echo $wb->getPageTopLine(); ?>

				<div class="row"><div class="col-sm-12"><div class="pull-right">
					<?php
					if($feedback->CustomerWithSpecificNeeds->Status->__toString() == 'NA')
						echo HTML::renderWorkbookIcons('CustomerWithSpecificNeeds', false, 'btn-danger');
					else
						echo HTML::renderWorkbookIcons('CustomerWithSpecificNeeds', false, 'btn-success');
					?>
				</div></div></div>


				<div class="row">
					<div class="col-sm-12">
						<p><strong>Religion and religious beliefs </strong>can also have an impact on a customer’s needs and priorities. Expectations may be regarding purchasing food and the way animals are slaughtered.</p>
						<p><strong>Foreign visitors </strong>may expect retail staff to be able to communicate with them in their own language.</p>
					</div>
				</div>
				<div class="row">
					<div class="col-sm-6">
						<p>Try different approaches to communicate:</p>
						<ul style="margin-left: 15px;">
							<li>Write things down or use pictures or diagrams to help</li>
							<li>Do you have a colleague that speaks the language or has an understanding of the person’s cultural background and needs?</li>
						</ul>
					</div>
					<div class="col-sm-6">
						<img src="module_eportfolio/assets/images/wb4_pg7_img1.png" />
					</div>
				</div>
				<div class="row" <?php echo $feedback->CustomerWithSpecificNeeds->Status->__toString() == 'A'?'style="pointer-events:none;"':''; ?>>
					<div class="col-sm-12 text-center">
						<p class="text-bold">
							<img src="module_eportfolio/assets/images/wb2_img1.png" /> &nbsp;
							Give an example of when you have served a customer who had specific needs.
							Explain when and how you adapted your service approach to meet their needs and priorities?
							&nbsp;
							<img src="module_eportfolio/assets/images/wb4_img1.png" />
						</p>
						<p><textarea name="ExampleOfCustomerService" rows="10" style="min-width: 100%"><?php echo $answers->CustomerWithSpecificNeeds->ExampleOfCustomerService->__toString(); ?></textarea></p>
					</div>
				</div>
				<div class="row">
					<div class="col-sm-6">
						<p><strong>The Equality Act 2010 legally protects people from discrimination in the workplace and in wider society.</strong></p>
						<p><strong>It is against the law to discriminate against anyone because of the following ‘protected characteristics’.</strong></p>
						<ul style="margin-left: 15px; margin-bottom: 15px;">
							<li>Age</li>
							<li>Being or becoming a transsexual person</li>
							<li>Being married or in a civil partnership</li>
							<li>Being pregnant or on maternity leave</li>
							<li>Disability</li>
							<li>Race including colour, nationality, ethnic or national origin</li>
							<li>Religion, belief or lack of religion/belief</li>
							<li>Sex</li>
							<li>Sexual orientation</li>
						</ul>
					</div>
					<div class="col-sm-6">
						<p><img src="module_eportfolio/assets/images/wb4_pg7_img2.png" /></p>
						<p><strong>You’re protected from discrimination:</strong></p>
						<ul style="margin-left: 15px; margin-bottom: 15px;">
							<li>At work</li>
							<li>As a consumer</li>
							<li>In education</li>
							<li>when using public services</li>
							<li>When buying or renting property</li>
							<li>As a member or guest of a private club or association</li>
						</ul>
					</div>
				</div>

				<p><br></p>

				<div class="row" <?php echo $feedback->CustomerWithSpecificNeeds->Status->__toString() == 'A'?'style="pointer-events:none;"':''; ?>>
					<div class="col-sm-12 text-center">
						<p class="text-bold">
							<img src="module_eportfolio/assets/images/wb2_img1.png" /> &nbsp;
							Describe some of the specific needs and priorities of different customers who are protected under the Equality Act 2010.
							&nbsp;
							<img src="module_eportfolio/assets/images/wb4_img1.png" />
						</p>
						<p><textarea name="NeedsAndPriorities" rows="10" style="min-width: 100%"><?php echo $answers->CustomerWithSpecificNeeds->NeedsAndPriorities->__toString(); ?></textarea></p>
					</div>
				</div>
				<div class="row">
					<div class="col-sm-12">
						<p><strong>Customers with special requirements </strong>can sometimes be challenging to assist. Various disabilities such as hearing impairment, visual impairment or mobility difficulties can require extra thought and flexibility of approach on your or the organisations’ part in order to ensure you provide the same high standards of service to all customer groups. You may have to speak more slowly, listen carefully and use images or diagrams to aid communication.</p>
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
						<p><img src="module_eportfolio/assets/images/wb4_pg8_img1.png" /></p>
					</div>
				</div>
				<div class="row">
					<div class="col-sm-12">
						<p>There may be a number of occasions and situations when you could find it challenging to deal with a customer. Often the reason we find a customer challenging is because of our own limitations, points of view, and attitude or skill level. Language or cultural barriers can prove challenging. In situations like this it is important to remain calm and patient and put yourself in the shoes of the customer.</p>
						<p class="text-center"><img src="module_eportfolio/assets/images/wb4_pg8_img2.png" /></p>
					</div>
				</div>

				<?php if($wb->id != '' && $_SESSION['user']->type == User::TYPE_ASSESSOR && ($wb->wb_status == Workbook::STATUS_LEARNER_COMPLETED || $wb->wb_status == Workbook::STATUS_BEING_CHECKED || $wb->wb_status == Workbook::STATUS_IV_REJECTED)) {?>
				<div class="row well">
					<div class="col-sm-12">
						<div class="box box-success box-solid">
							<div class="box-header with-border"><h3 class="box-title">Assessor Feedback</h3></div>
							<div class="box-body assessorFeedback" <?php echo $feedback->CustomerWithSpecificNeeds->Status->__toString() == 'A'?'style="pointer-events:none;"':''; ?>>
								<div class="form-group">
									<label for="status_CustomerWithSpecificNeeds" class="col-sm-12 control-label">Status:</label>
									<div class="col-sm-12">
										<?php
										echo HTML::selectChosen('status_CustomerWithSpecificNeeds', $answer_status, $feedback->CustomerWithSpecificNeeds->Status->__toString() == 'A'?$feedback->CustomerWithSpecificNeeds->Status->__toString():'', false, true); ?>
									</div>
								</div>
								<div class="form-group">
									<label for="comments_CustomerWithSpecificNeeds" class="col-sm-12 control-label">Comments:</label>
									<div class="col-sm-12">
										<textarea name="comments_CustomerWithSpecificNeeds" rows="7" style="width: 100%;"><?php echo $feedback->CustomerWithSpecificNeeds->Comments->__toString(); ?></textarea>
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
					if($feedback->CustomerWithSpecificNeeds->Status->__toString() == 'NA')
						echo HTML::renderWorkbookIcons('CustomerWithSpecificNeeds', false, 'btn-danger');
					else
						echo HTML::renderWorkbookIcons('CustomerWithSpecificNeeds', false, 'btn-success');
					?>
				</div></div></div>

				<?php echo $wb->getPageBottomLine(); ?>
			</div> <!--.page 7, 8 ends-->

			<h1>Knowing your Customers</h1>
			<div class="step-content">
				<?php echo $wb->getPageTopLine(); ?>

				<div class="row"><div class="col-sm-12"><div class="pull-right">
					<?php
					if($feedback->PoorCustomerServiceImplications->Status->__toString() == 'NA')
						echo HTML::renderWorkbookIcons('PoorCustomerServiceImplications', false, 'btn-danger');
					else
						echo HTML::renderWorkbookIcons('PoorCustomerServiceImplications', false, 'btn-success');
					?>
				</div></div></div>


				<div class="row">
					<div class="col-sm-12">
						<p><strong>The importance of building good customer relationships to the organisation</strong></p>
						<p>If a company wants to be successful then they need to ensure that they have excellent customer service so that customers keep coming back, spend their money in their store and tell their friends to do the same.</p>
					</div>
				</div>
				<div class="row">
					<div class="col-sm-12">
						<p><strong>Excellent Customer Service:</strong></p>
						<p><strong>1. It’s what customers will remember</strong></p>
						<p>People will always remember if customer service was really great or really bad. If a person represents a company in a good way, customers will remember the company fondly. If a person represents the company in a negative way, they’ll probably lose a customer and generate some bad press.</p>
						<p><strong>2. It reflects on the entire business</strong></p>
						<p>If a company has good customer service, people naturally assume they have good products. If they have bad customer service, people naturally assume they have bad products. This can apply to any area such as shipping, returns or services. Even though this may not be entirely true, a company needs to be aware that this is how customers can think.</p>
						<p><strong>3. It shows customers you care</strong></p>
						<p>When time is taken to courteously and effectively take care of customers’ issues, it shows you truly care about them. A company should genuinely care about their customers, since they are the most important aspect of their business. Without customers, they won’t sell any products or services. Customers who feel as though a company cares about them are much more likely to refer others and become repeat customers themselves.</p>
						<p><strong>4. It is a great marketing angle</strong></p>
						<p>From a business point of view, good customer service is a great marketing angle. It’s something that can be used in advertisements. People like to hear you have had great customer service.  Anything that can help with effective marketing is worth the extra effort. This angle works best when other real-life customers talk about how great customer service is, so a good idea can be to ask for reviews.</p>
					</div>
				</div>
				<div class="row">
					<div class="col-sm-12">
						<p><strong>Excellent customer service can help:</strong></p>
						<ul style="margin-left: 15px;">
							<li>Increase customer loyalty</li>
							<li>Increase the amount of money each customer spends and how often they do</li>
							<li>Generate positive word-of-mouth and reputation</li>
						</ul>
					</div>
				</div>
				<div class="row" <?php echo $feedback->PoorCustomerServiceImplications->Status->__toString() == 'A'?'style="pointer-events:none;"':''; ?>>
					<div class="col-sm-12 text-center">
						<p class="text-bold">
							<img src="module_eportfolio/assets/images/wb2_img1.png" /> &nbsp;
							What are some of the implications of poor customer service?
							&nbsp;
							<img src="module_eportfolio/assets/images/wb4_img1.png" />
						</p><p><textarea name="PoorCustomerServiceImplications" rows="10" style="min-width: 100%"><?php echo $answers->PoorCustomerServiceImplications->__toString(); ?></textarea> </p>
					</div>
				</div>

				<?php if($wb->id != '' && $_SESSION['user']->type == User::TYPE_ASSESSOR && ($wb->wb_status == Workbook::STATUS_LEARNER_COMPLETED || $wb->wb_status == Workbook::STATUS_BEING_CHECKED || $wb->wb_status == Workbook::STATUS_IV_REJECTED)) {?>
				<div class="row well">
					<div class="col-sm-12">
						<div class="box box-success box-solid">
							<div class="box-header with-border"><h3 class="box-title">Assessor Feedback</h3></div>
							<div class="box-body assessorFeedback" <?php echo $feedback->PoorCustomerServiceImplications->Status->__toString() == 'A'?'style="pointer-events:none;"':''; ?>>
								<div class="form-group">
									<label for="status_PoorCustomerServiceImplications" class="col-sm-12 control-label">Status:</label>
									<div class="col-sm-12">
										<?php
										echo HTML::selectChosen('status_PoorCustomerServiceImplications', $answer_status, $feedback->PoorCustomerServiceImplications->Status->__toString() == 'A'?$feedback->PoorCustomerServiceImplications->Status->__toString():'', false, true); ?>
									</div>
								</div>
								<div class="form-group">
									<label for="comments_PoorCustomerServiceImplications" class="col-sm-12 control-label">Comments:</label>
									<div class="col-sm-12">
										<textarea name="comments_PoorCustomerServiceImplications" rows="7" style="width: 100%;"><?php echo $feedback->PoorCustomerServiceImplications->Comments->__toString(); ?></textarea>
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
					if($feedback->PoorCustomerServiceImplications->Status->__toString() == 'NA')
						echo HTML::renderWorkbookIcons('PoorCustomerServiceImplications', false, 'btn-danger');
					else
						echo HTML::renderWorkbookIcons('PoorCustomerServiceImplications', false, 'btn-success');
					?>
				</div></div></div>

				<?php echo $wb->getPageBottomLine(); ?>
			</div> <!--.page 9 ends-->

			<h1>Knowing your Customers</h1>
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
						<h2>Qualification  questions</h2>
					</div>
				</div>
				<div class="row" <?php echo $feedback->QualificationQuestions->Status->__toString() == 'A'?'style="pointer-events:none;"':''; ?>>
					<div class="col-sm-12">
						<div class="box box-solid box-success">
							<div class="box-header">
								<h3 class="box-title"><b>Unit 1: Understand the needs and expectations of customers</b></h3>
							</div>
							<div class="box-body">
								<p>To achieve learning outcome 1 (Understand who customers are) answer the following questions in as much detail as you can.</p>
								<p>NB: * Please check unit amplification after qualification questions</p>
								<p><strong>1.1	Explain the importance of building good customer relations to the organisation *</strong></p>
								<p><textarea name="Unit1_1" style="width: 100%" rows="5"><?php echo $answers->QualificationQuestions->Unit1_1->__toString(); ?></textarea></p>
								<p><strong>1.2	Explain the difference between internal and external customers</strong></p>
								<p><textarea name="Unit1_2" style="width: 100%" rows="5"><?php echo $answers->QualificationQuestions->Unit1_2->__toString(); ?></textarea></p>
								<p><strong>1.3/1.4	Describe different types of internal and external customers giving examples from within <?php echo ucfirst($wb->savers_or_sp); ?> *</strong></p>
								<p><textarea name="Unit1_3_And_1_4" style="width: 100%" rows="5"><?php echo $answers->QualificationQuestions->Unit1_3_And_1_4->__toString(); ?></textarea></p>
								<p><hr></p>
								<p>To achieve learning outcome 2 (Understand how to manage customer needs and expectations) answer the following questions in as much detail as you can.</p>
								<p><strong>2.1 Describe specific needs and priorities of different customers, including those protected under current Equality law *</strong></p>
								<p><textarea name="Unit2_1" style="width: 100%" rows="5"><?php echo $answers->QualificationQuestions->Unit2_1->__toString(); ?></textarea></p>
								<p><strong>2.2 Explain when and how to adapt your service approach to meet the needs and expectations of customers *</strong></p>
								<p><textarea name="Unit2_2" style="width: 100%" rows="5"><?php echo $answers->QualificationQuestions->Unit2_2->__toString(); ?></textarea></p>
								<p><strong>2.3 Why is it important to manage customer expectations?</strong></p>
								<p><textarea name="Unit2_3" style="width: 100%" rows="5"><?php echo $answers->QualificationQuestions->Unit2_3->__toString(); ?></textarea></p>
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
			</div> <!--.page 10 ends-->

			<h1>Knowing your Customers</h1>
			<div class="step-content">
				<?php echo $wb->getPageTopLine(); ?>


				<div class="row">
					<div class="col-sm-12 table-responsive">
						<table class="table row-border"  <?php echo $_SESSION['user']->type == User::TYPE_LEARNER || !$wb->enableForUser() ?'style="pointer-events:none;"':''; ?>>
							<?php
							$criteria_met = explode(',', $wb->wb_content->CriteriaMet->__toString());
							?>
							<tr><th>Amplifications / Indicative content</th><th>Criteria met?</th></tr>
							<tr>
								<td>1.1 – Learners should show understanding of how good customer relations lead to customer satisfaction and customer loyalty, which in turn impact upon organisational performance. Learners should also demonstrate understanding of the consequences of poor customer relations.</td>
								<td><input class="clsCriteriaMet" name="CriteriaMet[]" type="checkbox" value="1" <?php echo in_array(1, $criteria_met) ? 'checked="checked"' : ''; ?> /></td>
							</tr>
							<tr>
								<td>
									<p>1.3 – Learners should show understanding of how categorising customers in to groups can be helpful when identifying and meeting their needs, e.g.</p>
									<ul style="margin-left: 15px;">
										<li>customers described according to their gender, age, cultural background, etc;</li>
										<li>challenging or dissatisfied customers</li>
										<li>loyal customers, impulse buyers and discount shoppers</li>
									</ul>
								</td>
								<td><input class="clsCriteriaMet" name="CriteriaMet[]" value="2" type="checkbox" <?php echo in_array(2, $criteria_met) ? 'checked="checked"' : ''; ?> /></td>
							</tr>
							<tr>
								<td>
									<p>2.1 – Learners should:</p>
									<ul style="margin-left: 15px;">
										<li>show understanding of the different between a need and a priority, in the context of customer service</li>
										<li>show understanding of current Equality law; and</li>
										<li>describe the specific needs and priorities of at least three different types of customer, including one who has a protected characteristic under the Equality Act 2010.</li>
									</ul>
								</td>
								<td><input class="clsCriteriaMet" name="CriteriaMet[]" value="3" type="checkbox" <?php echo in_array(3, $criteria_met) ? 'checked="checked"' : ''; ?> /></td>
							</tr>
							<tr>
								<td>
									<p>2.2 – Learner should show understanding of the difference between a need and an expectation. Learners should then explain when and how to adapt their service approach to meet the needs and expectations of at least three different types of customer.</p>
								</td>
								<td><input class="clsCriteriaMet" name="CriteriaMet[]" value="4" type="checkbox" <?php echo in_array(4, $criteria_met) ? 'checked="checked"' : ''; ?> /></td>
							</tr>
						</table>
					</div>
				</div>

				<p><br></p>

				<?php echo $wb->getPageBottomLine(); ?>
			</div> <!--.page 12 ends-->

			<h1>Signature</h1>
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
			</div> <!--.page 18 ends-->


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

		$("#frm_wb_knowing_your_customers :input").not(".clsCriteriaMet, .assessorFeedback :input, #signature_text, #frm_wb_knowing_your_customers :input[type=hidden], .btnViewSectionHistory, .btnViewAssessorComments, #iv_status, #iv_comments, #reopen_comments").prop("disabled", true);

		<?php } ?>

		$("#wizard").steps({
			transitionEffect:"fade",
			transitionEffectSpeed:500,
			//startIndex:10,
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
							var myForm = document.forms['frm_wb_knowing_your_customers'];
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
				var myForm = document.forms['frm_wb_knowing_your_customers'];
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

		$('.datepicker').datepicker({
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

					var myForm = document.forms['frm_wb_knowing_your_customers'];
					myForm.elements['full_save'].value = 'Y';
					myForm.elements['full_save_feedback'].value = 'Y';
					window.onbeforeunload = null;
					myForm.submit();

				},
				'Save And Come Back Later':function () {

					var myForm = document.forms['frm_wb_knowing_your_customers'];
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
		$('#frm_wb_knowing_your_customers :input[name=full_save]').val('N');
		$($('#frm_wb_knowing_your_customers').serializeArray()).each(function(i, field)
		{
			document.forms["frm_wb_knowing_your_customers"].elements[field.name].value = field.value.replace(/£/g, "GBP");
		});
		$.ajax({
			type:'POST',
			url:'do.php?_action=save_wb_knowing_your_customers',
			data: $('#frm_wb_knowing_your_customers').serialize(),
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

	function previewInputInformation()
	{
		var html = '';

		$('select[name^=status_]').each(function(){
			var section = this.id.replace('status_', '');
			html += '<p><span class="text-bold">Section: </span>' + section + '</p>';
			if(this.value == 'A')
				html += '<p><span class="text-bold">Status:  </span>ACCEPTED</p>';
			else
				html += '<p><span class="text-bold">Status:  </span>NOT ACCEPTED</p>';
			html += '<p><span class="text-bold">Comments: </span><br>' + $('textarea[name="comments_'+section+'"]').val() + '</p><hr>';
		});

		$('#divPreview').html(html);
		$('#dialogPreview').dialog('open').css("background", "#FFF");
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

</html>
