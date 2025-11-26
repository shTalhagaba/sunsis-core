<?php /* @var $wb WBProductAndService */ ?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/html" xmlns="http://www.w3.org/1999/html" xmlns="http://www.w3.org/1999/html"
      xmlns="http://www.w3.org/1999/html">
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<title>Product and service workbook</title>
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

<form name="frm_wb_product_and_service" id="frm_wb_product_and_service" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
<input type="hidden" name="_action" value="save_wb_product_and_service" />
<input type="hidden" name="id" value="<?php echo $wb->id; ?>" />
<input type="hidden" name="tr_id" value="<?php echo $wb->tr_id; ?>" />
<input type="hidden" name="wb_status" id="wb_status" value="" />
<input type="hidden" name="full_save" id="full_save" value="<?php echo $wb->full_save; ?>" />
<input type="hidden" name="full_save_feedback" id="full_save_feedback" value="<?php echo $wb->full_save_feedback; ?>" />
<div class="container-float">
<div class="wrapper" style="background-color: #ffffff;">

<header class="main-header"><span style="margin-left: 50%;"><?php echo DAO::getSingleValue($link, "SELECT title FROM student_frameworks WHERE tr_id = '{$wb->tr_id}'"); ?></span></header>

<div class="content-wrapper" style="background-color: #ffffff;">

<?php echo $wb->savers_or_sp == 'savers' ? '<section class="content-header"><h1 style="color: #0000ff;">Product <span class="text-red">&</span> service</h1></section>' : '<section class="content-header"><h1>Product and service</h1></section>' ?>

<section class="content">

<div id="wizard">

<h1>Product and service</h1>
<div class="step-content">
	<?php echo $wb->getPageTopLine(); ?>
	<div style="position: absolute; top: 40%; right: 50%;" class="lead">
		<?php echo $wb->savers_or_sp == 'savers' ? '<h2 class="text-bold" style="color: #0000ff;">Product <span class="text-red">&</span> service</h2>' : '<h2 class="text-bold">Product & service</h2>' ?>
		<p class="text-center" >Module 10</p>
	</div>

	<?php echo $wb->getPageBottomLine(); ?>
</div> <!--.page 1 ends-->

<h1>Product and service</h1>
<div class="step-content">
	<?php echo $wb->getPageTopLine(); ?>

	<div class="row"><div class="col-sm-12"><div class="pull-right"><?php echo HTML::renderWorkbookIcons('', true); ?></div></div></div>



	<div class="row">
		<div class="col-sm-12">
			<p>This module is about product & service in the workplace and what you need to know and do.
				It is also about promoting brand through behaviour and customer service techniques. Brand is the identity of the company and communicates what type of company it is, what its customers can expect from it and also what quality of service it provides. Product and service is about the type of products and services it delivers.
			</p>
			<p>In this workbook you will look at:</p>
			<ul style="margin-left: 15px; margin-bottom: 15px;">
				<li>Your own product and service training</li>
				<li>What are products and service?</li>
				<li>Preparing to deliver</li>
				<li>Knowing your products</li>
				<li>Features and benefits</li>
				<li>Active/link selling</li>
				<li>Own brand</li>
				<li>The five steps of selling model</li>
				<li>Identifying customer needs</li>
				<li>Customer legal rights</li>
				<li>Excellent service</li>
			</ul>
		</div>
		<div class="col-sm-12">
			<img src="module_eportfolio/assets/images/wb10_pg2_img1.png" />
		</div>
	</div>

	<p><br></p>

	<div class="row"><div class="col-sm-12"><div class="pull-right"><?php echo HTML::renderWorkbookIcons('', true); ?></div></div></div>

	<?php echo $wb->getPageBottomLine(); ?>
</div> <!--.page 2 ends-->

<h1>Product and service</h1>
<div class="step-content">
	<?php echo $wb->getPageTopLine(); ?>

	<div class="row"><div class="col-sm-12"><div class="pull-right"><button title="save information" class="btn btn-warning dim" type="button" onclick="partialSave();"><i class="fa fa-save"></i> </button><?php echo HTML::renderWorkbookIcons('', true); ?></div></div></div>



	<div class="row">
		<div class="col-sm-12"><p><br></p></div>
		<div class="col-sm-12"><p><img src="module_eportfolio/assets/images/wb4_pg2_img1.png" /> <strong>Learner journey / Visit plan</strong></p></div>
		<div class="col-sm-12"><p>Before you start this module please ensure you have completed all of the training detailed below. If you haven’t you will need to speak to your manager / mentor to arrange when you will complete it.</p></div>
	</div>
	<div class="row">
		<div class="col-2m-12 table-responsive">
			<table class="table table-bordered table-striped">
				<tr><th>Learning</th><th>Workbook/IPad/In-store activity</th><th>Date completed</th></tr>
				<?php
				$items = WBProductAndService::getLearningJourneyItems();
				$j = 0;
				foreach($items AS $i)
				{
					$key = 'DC'.++$j;
					echo '<tr>';
					echo '<td>' . $i . '</td>';
					echo '<td>In-store activity</td>';
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

<h1>Product and service</h1>
<div class="step-content">
	<?php echo $wb->getPageTopLine(); ?>

	<div class="row"><div class="col-sm-12"><div class="pull-right">
		<?php
		if($feedback->ProductAndService->Status->__toString() == 'NA')
			echo HTML::renderWorkbookIcons('ProductAndService', false, 'btn-danger');
		else
			echo HTML::renderWorkbookIcons('ProductAndService', false, 'btn-success');
		?>
	</div></div></div>

	<div class="row">

		<div class="col-sm-12">

			<p class="text-bold">What are product and service?</p>
		</div>

	</div>

	<div class="row">
		<div class="col-sm-12" <?php echo $feedback->ProductAndService->Status->__toString() == 'A'?'style="pointer-events:none;"':''; ?>>
			<p><span class="text-bold">Organisations</span> spend a lot of time and money on building a brand. The brand is one of their most valuable assets and without a strong brand identity an organisation will find it more difficult to engage with their customers. A strong brand provides customers with a set of clear expectations of what that organisation stands for and what the customer can expect from it.</p>
			<p class="text-bold">Service</p>
			<p>The term ‘service offer’ is used to describe the type and nature of the services offered by an organisation. Many organisations will strive to make their service offer stand out in order to gain an advantage over their competitors. Their aim is to boost their opportunities, capture new customers and retain more of their existing customers. When offering something different or better than a competitor, the service offer may sometimes be referred to as a Unique Service Offer or USO for short.</p>
			<p class="text-bold">Product</p>
			<p>It is very important that product and service knowledge is kept up to date in order to ensure that service standards are maintained. New information, product changes, product performance and customer feedback can affect what is current and correct about a product or service offer.</p>
			<p class="text-bold">Organisations can use a wide range of resources and methods to stay up to date:</p>
			<ul style="margin-left: 15px; margin-bottom: 15px;">
				<li>Product brochures and manuals</li>
				<li>Catalogues and product specifications</li>
				<li>Price lists</li>
				<li>Colleagues knowledge, internet and intranet resources</li>
				<li>Customer comments and feedback</li>
				<li>Staff training</li>
			</ul>
			<p class="text-bold">Key Points:</p>
			<ul style="margin-left: 15px; margin-bottom: 15px;">
				<li>Having a service offer with a USO will allow the organisation to gain an advantage over its competitors</li>
				<li>A service offer will attract new customers and retain customer loyalty</li>
			</ul>
			<p>Keeping up to date with product and service offers enables employees within an organisation to maintain and enhance the standards of customer service they and the organisation can deliver to their customers</p>
			<p class="text-bold"><img src="module_eportfolio/assets/images/wb2_img1.png" /> &nbsp;What do you think <?php echo ucfirst($wb->savers_or_sp); ?>'s Unique Service Offer (USO) is? Speak to your team to see what they think and make some notes in the box below. &nbsp; <img src="module_eportfolio/assets/images/wb2_img2.png" /> </p>
			<textarea rows="5" name="ProductAndService" style="width: 100%;"><?php echo $answers->ProductAndService->__toString(); ?></textarea>
		</div>
	</div>

	<?php if($wb->id != '' && $_SESSION['user']->type == User::TYPE_ASSESSOR && ($wb->wb_status == Workbook::STATUS_LEARNER_COMPLETED || $wb->wb_status == Workbook::STATUS_BEING_CHECKED || $wb->wb_status == Workbook::STATUS_IV_REJECTED)) {?>
	<div class="row well">
		<div class="col-sm-12">
			<div class="box box-success box-solid">
				<div class="box-header with-border"><h3 class="box-title">Assessor Feedback</h3></div>
				<div class="box-body assessorFeedback" <?php echo $feedback->ProductAndService->Status->__toString() == 'A'?'style="pointer-events:none;"':''; ?>>
					<div class="form-group">
						<label for="status_ProductAndService" class="col-sm-12 control-label">Status:</label>
						<div class="col-sm-12">
							<?php
							echo HTML::selectChosen('status_ProductAndService', $answer_status, $feedback->ProductAndService->Status->__toString() == 'A'?$feedback->ProductAndService->Status->__toString():'', false, true); ?>
						</div>
					</div>
					<div class="form-group">
						<label for="comments_ProductAndService" class="col-sm-12 control-label">Comments:</label>
						<div class="col-sm-12">
							<textarea name="comments_ProductAndService" rows="7" style="width: 100%;"><?php echo $feedback->ProductAndService->Comments->__toString(); ?></textarea>
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
		if($feedback->ProductAndService->Status->__toString() == 'NA')
			echo HTML::renderWorkbookIcons('ProductAndService', false, 'btn-danger');
		else
			echo HTML::renderWorkbookIcons('ProductAndService', false, 'btn-success');
		?>
	</div></div></div>

	<?php echo $wb->getPageBottomLine(); ?>
</div> <!--.page 4 ends-->

<h1>Product and service</h1>
<div class="step-content">
	<?php echo $wb->getPageTopLine(); ?>

	<div class="row"><div class="col-sm-12"><div class="pull-right">
		<?php
		if($feedback->PreparingToDeliver->Status->__toString() == 'NA')
			echo HTML::renderWorkbookIcons('PreparingToDeliver', false, 'btn-danger');
		else
			echo HTML::renderWorkbookIcons('PreparingToDeliver', false, 'btn-success');
		?>
	</div></div></div>

	<div class="row">

		<div class="col-sm-12">

			<p class="text-bold">Preparing to deliver</p>
		</div>

	</div>

	<div class="row">
		<div class="col-sm-6">
			<blockquote>If you are going to be able to deliver excellent standards of customer service it is important to be fully prepared before starting work.</blockquote>
		</div>
		<div class="col-sm-6">
			<ul style="margin-left: 15px; margin-bottom: 15px;">
				<li>Ensure you arrive early to start your work</li>
				<li>Personal presentation</li>
				<li>Uniform clean and prepared</li>
				<li>Pen or pencil</li>
				<li>Awareness of working times and breaks</li>
				<li>Update from manager on starting shift</li>
				<li>Correct attitude</li>
			</ul>
		</div>
	</div>

	<div class="row">
		<div class="col-sm-12"  <?php echo $feedback->PreparingToDeliver->Status->__toString() == 'A'?'style="pointer-events:none;"':''; ?>>
			<p class="text-bold"><img src="module_eportfolio/assets/images/wb2_img1.png" /> &nbsp;What do you do before starting your shift? Can you think of any to add to the list?</p>
			<div class="row">
				<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-center">
					<img class="img-responsive" src="module_eportfolio/assets/images/wb10_pg5_img1.png" />
				</div>
			</div>
			<div class="row">
				<div class="col-lg-2 col-md-2 col-sm-2 col-xs-2 text-center">
					<p><h4 class="text-bold pull-right">Q1</h4></p>
				</div>
				<div class="col-lg-10 col-md-10 col-sm-10 col-xs-10 text-center">
					<p><textarea rows="5" name="PreparingToDeliverQuestion1" style="width: 100%;"><?php echo $answers->PreparingToDeliver->Question1->__toString(); ?></textarea></p>
				</div>
			</div>
			<div class="row">
				<div class="col-lg-2 col-md-2 col-sm-2 col-xs-2 text-center">
					<p><h4 class="text-bold pull-right">Q2</h4></p>
				</div>
				<div class="col-lg-10 col-md-10 col-sm-10 col-xs-10 text-center">
					<p><textarea rows="5" name="PreparingToDeliverQuestion2" style="width: 100%;"><?php echo $answers->PreparingToDeliver->Question2->__toString(); ?></textarea></p>
				</div>
			</div>
			<div class="row">
				<div class="col-lg-2 col-md-2 col-sm-2 col-xs-2 text-center">
					<p><h4 class="text-bold pull-right">Q3</h4></p>
				</div>
				<div class="col-lg-10 col-md-10 col-sm-10 col-xs-10 text-center">
					<p><textarea rows="5" name="PreparingToDeliverQuestion3" style="width: 100%;"><?php echo $answers->PreparingToDeliver->Question3->__toString(); ?></textarea></p>
				</div>
			</div>
			<div class="row">
				<div class="col-lg-2 col-md-2 col-sm-2 col-xs-2 text-center">
					<p><h4 class="text-bold pull-right">Q4</h4></p>
				</div>
				<div class="col-lg-10 col-md-10 col-sm-10 col-xs-10 text-center">
					<p><textarea rows="5" name="PreparingToDeliverQuestion4" style="width: 100%;"><?php echo $answers->PreparingToDeliver->Question4->__toString(); ?></textarea></p>
				</div>
			</div>
		</div>
	</div>
	<div class="row">
		<div class="col-sm-6">
			<p>One of the key elements of working in a team is effective communication.</p>
			<p class="text-bold">Effective communication allows team members to:</p>
			<ul style="margin-left: 15px; margin-bottom: 15px;">
				<li>Work together</li>
				<li>Pass on information to each other</li>
				<li>Ask for and offer help</li>
				<li>Use the expertise of the team</li>
				<li>Clarify and resolve misunderstandings</li>
			</ul>
		</div>
		<div class="col-sm-6"><img class="img-responsive" src="module_eportfolio/assets/images/wb10_pg5_img2.png" /></div>
		<div class="col-sm-12">
			<p class="text-bold">Effective team members need abilities and skills such as being able to:</p>
		</div>
		<div class="col-sm-6">
			<ul style="margin-left: 15px; margin-bottom: 15px;">
				<li>Communicate successfully</li>
				<li>Listen to what others say</li>
				<li>Support the team </li>
				<li>Work competently</li>
				<li>Adapt to different situations</li>
			</ul>
		</div>
		<div class="col-sm-6" <?php echo $feedback->PreparingToDeliver->Status->__toString() == 'A'?'style="pointer-events:none;"':''; ?>>
			<p class="text-bold"><img src="module_eportfolio/assets/images/wb2_img1.png" /> &nbsp;What other skills or abilities can you think of?</p>
			<textarea rows="5" name="OtherSkills" style="width: 100%; "><?php echo $answers->PreparingToDeliver->OtherSkills->__toString(); ?></textarea>
		</div>
		<div class="col-sm-12">
			<p>To work effectively objectives must be communicated between managers, supervisors and the team members who will be implementing them. This usually happens by means of team meetings and individual discussions during which the objectives can be set and agreed. Always make sure that you know exactly what is required of you when you are given objectives. Ask questions if anything is not clear to you.</p>
		</div>
	</div>

	<?php if($wb->id != '' && $_SESSION['user']->type == User::TYPE_ASSESSOR && ($wb->wb_status == Workbook::STATUS_LEARNER_COMPLETED || $wb->wb_status == Workbook::STATUS_BEING_CHECKED || $wb->wb_status == Workbook::STATUS_IV_REJECTED)) {?>
	<div class="row well">
		<div class="col-sm-12">
			<div class="box box-success box-solid">
				<div class="box-header with-border"><h3 class="box-title">Assessor Feedback</h3></div>
				<div class="box-body assessorFeedback" <?php echo $feedback->PreparingToDeliver->Status->__toString() == 'A'?'style="pointer-events:none;"':''; ?>>
					<div class="form-group">
						<label for="status_PreparingToDeliver" class="col-sm-12 control-label">Status:</label>
						<div class="col-sm-12">
							<?php
							echo HTML::selectChosen('status_PreparingToDeliver', $answer_status, $feedback->PreparingToDeliver->Status->__toString() == 'A'?$feedback->PreparingToDeliver->Status->__toString():'', false, true); ?>
						</div>
					</div>
					<div class="form-group">
						<label for="comments_PreparingToDeliver" class="col-sm-12 control-label">Comments:</label>
						<div class="col-sm-12">
							<textarea name="comments_PreparingToDeliver" rows="7" style="width: 100%;"><?php echo $feedback->PreparingToDeliver->Comments->__toString(); ?></textarea>
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
		if($feedback->PreparingToDeliver->Status->__toString() == 'NA')
			echo HTML::renderWorkbookIcons('PreparingToDeliver', false, 'btn-danger');
		else
			echo HTML::renderWorkbookIcons('PreparingToDeliver', false, 'btn-success');
		?>
	</div></div></div>

	<?php echo $wb->getPageBottomLine(); ?>
</div> <!--.page 5 ends-->

<h1>Product and service</h1>
<div class="step-content">
	<?php echo $wb->getPageTopLine(); ?>

	<div class="row"><div class="col-sm-12"><div class="pull-right">
		<?php
		if($feedback->KnowingYourProducts->Status->__toString() == 'NA')
			echo HTML::renderWorkbookIcons('KnowingYourProducts', false, 'btn-danger');
		else
			echo HTML::renderWorkbookIcons('KnowingYourProducts', false, 'btn-success');
		?>
	</div></div></div>

	<div class="row">
		<div class="col-sm-12">

			<p class="text-bold">Knowing Your Products</p>
		</div>
	</div>
	<div class="row">
		<div class="col-sm-12" <?php echo $feedback->KnowingYourProducts->Status->__toString() == 'A'?'style="pointer-events:none;"':''; ?>>
			<p>The more you know about your products and your customers the easier it is to deal with any queries.  Up to date and relevant product knowledge demonstrates to your customers that you are well informed and capable of answering any questions or queries they may have regarding any product.</p>
			<p class="text-bold">Make sure you have read and understand the:</p>
			<ul style="margin-left: 15px; margin-bottom: 15px;">
				<li>Product information – best sellers and basic information</li>
				<li>Latest promotion bulletin</li>
				<li>Any store leaflets/magazines</li>
				<li>Features and benefits</li>
			</ul>
			<p class="text-bold"><img src="module_eportfolio/assets/images/wb2_img1.png" /> &nbsp;What are the names of any in store magazines or resources that you have access to which can help you with your product knowledge? &nbsp;<img src="module_eportfolio/assets/images/wb2_img2.png" /> </p>
			<textarea rows="5" name="KnowingYourProducts" style="width: 100%; "><?php echo $answers->KnowingYourProducts->__toString(); ?></textarea>
			<p><br>By offering your customers the best experience on the high street through product knowledge, expertise and passionate selling techniques, we can deliver fantastic customer service.</p>
			<p class="text-bold">Understand the Benefits and Uses of Product Knowledge</p>
			<p>Having good product knowledge is a key aspect of being an effective sales assistant.  It allows the sales assistant to answer any questions that a customer may have about certain products.</p>
			<p>Good product knowledge also allows a sales assistant to overcome any objections that a customer may have to buying a particular product.</p>
			<p>Knowledgeable staff reassures the customers that they know what they are talking about and will therefore recommend the correct products. Having a good product knowledge means knowing what the features and benefits of products are.</p>
		</div>
		<div class="col-sm-12 table-responsive">
			<table class="table">
				<tr class="text-center">
					<td style="vertical-align: middle;" class="bg-blue text-bold"><p>What is a feature?</p></td>
					<td style="vertical-align: middle;"><img src="module_eportfolio/assets/images/wb10_pg6_img1.png" /></td>
					<td style="vertical-align: middle; border: #0000ff; border-style: solid;"><p>Features are the attributes that describe your product or service in detail</p></td>
					<td style="vertical-align: middle;">
						<p><img src="module_eportfolio/assets/images/wb10_pg6_img1.png" /></p>
						<p><img src="module_eportfolio/assets/images/wb10_pg6_img1.png" /></p>
						<p><img src="module_eportfolio/assets/images/wb10_pg6_img1.png" /></p>
					</td>
					<td style="vertical-align: middle;">
						<table class="table">
							<tr><td><div style="vertical-align: middle; border: #0000ff; border-style: dashed; padding: 15px;">Descriptions - size, weight, colour etc.</div></td></tr>
							<tr><td><div style="vertical-align: middle; border: #0000ff; border-style: solid; padding: 15px;">Technical details</div></td></tr>
							<tr><td><div style="vertical-align: middle; border: #0000ff; border-style: dotted; padding: 15px;">Product specification</div></td></tr>
						</table>
					</td>
				</tr>
			</table>
		</div>
	</div>

	<?php if($wb->id != '' && $_SESSION['user']->type == User::TYPE_ASSESSOR && ($wb->wb_status == Workbook::STATUS_LEARNER_COMPLETED || $wb->wb_status == Workbook::STATUS_BEING_CHECKED || $wb->wb_status == Workbook::STATUS_IV_REJECTED)) {?>
	<div class="row well">
		<div class="col-sm-12">
			<div class="box box-success box-solid">
				<div class="box-header with-border"><h3 class="box-title">Assessor Feedback</h3></div>
				<div class="box-body assessorFeedback" <?php echo $feedback->KnowingYourProducts->Status->__toString() == 'A'?'style="pointer-events:none;"':''; ?>>
					<div class="form-group">
						<label for="status_KnowingYourProducts" class="col-sm-12 control-label">Status:</label>
						<div class="col-sm-12">
							<?php
							echo HTML::selectChosen('status_KnowingYourProducts', $answer_status, $feedback->KnowingYourProducts->Status->__toString() == 'A'?$feedback->KnowingYourProducts->Status->__toString():'', false, true); ?>
						</div>
					</div>
					<div class="form-group">
						<label for="comments_KnowingYourProducts" class="col-sm-12 control-label">Comments:</label>
						<div class="col-sm-12">
							<textarea name="comments_KnowingYourProducts" rows="7" style="width: 100%;"><?php echo $feedback->KnowingYourProducts->Comments->__toString(); ?></textarea>
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
		if($feedback->KnowingYourProducts->Status->__toString() == 'NA')
			echo HTML::renderWorkbookIcons('KnowingYourProducts', false, 'btn-danger');
		else
			echo HTML::renderWorkbookIcons('KnowingYourProducts', false, 'btn-success');
		?>
	</div></div></div>

	<?php echo $wb->getPageBottomLine(); ?>
</div> <!--.page 6 ends-->

<h1>Product and service</h1>
<div class="step-content">
	<?php echo $wb->getPageTopLine(); ?>

	<div class="row"><div class="col-sm-12"><div class="pull-right">
		<?php
		if($feedback->FeaturesAndBenefits->Status->__toString() == 'NA')
			echo HTML::renderWorkbookIcons('FeaturesAndBenefits', false, 'btn-danger');
		else
			echo HTML::renderWorkbookIcons('FeaturesAndBenefits', false, 'btn-success');
		?>
	</div></div></div>

	<div class="row">
		<div class="col-sm-12">

			<p class="text-bold">Feature and benefits</p>
		</div>
	</div>

	<div class="row">
		<div class="col-sm-12">
			<h4 class="text-bold">What is a benefit?</h4>
			<blockquote>Features describe your product, but the benefits solve a problem for the customer or fulfil a need.</blockquote>
			<p class="text-bold">In essence, the benefit sells your product or service.</p>
			<p>Here's what to include when describing your product's benefits:</p>
			<ul style="margin-left: 15px; margin-bottom: 15px;">
				<li>How will your product or service solve a problem for the customer?</li>
				<li>How will your service fulfil a customer’s need?</li>
				<li>In what way is your product better than the competition's?</li>
				<li>Is your price better than the competition's?</li>
				<li>Will your service improve the customer's life? Make her/him happier? Reduce risk? Make him more productive? Reduce costs in the long run?</li>
				<li>Does your product last longer than the competition's?</li>
			</ul>
		</div>
		<div class="col-sm-12" <?php echo $feedback->FeaturesAndBenefits->Status->__toString() == 'A'?'style="pointer-events:none;"':''; ?>>
			<p class="text-bold"><img src="module_eportfolio/assets/images/wb2_img1.png" /> &nbsp;Now you know what a feature and a benefit are put your knowledge to the test below by choosing a product from each of the departments listed and give one feature and one benefit for each.</p>
			<?php $departments = WBProductAndService::getDepartmentsList(); ?>
			<div class="table-responsive">
				<table class="table table-bordered text-center">
					<tr><th>Department</th><th>Product</th><th>Feature</th><th>Benefit</th></tr>
					<?php
					foreach($departments AS $d)
					{
						echo '<tr>';
						echo '<th>' . $d . '</th>';
						//echo '<td><input name="' . $d . '_Product" size="30" value="' . $answers->FeaturesAndBenefits->$d->Product->__toString() . '" /></td>';
						//echo '<td><input name="' . $d . '_Feature" size="30" value="' . $answers->FeaturesAndBenefits->$d->Feature->__toString() . '" /></td>';
						//echo '<td><input name="' . $d . '_Benefit" size="30" value="' . $answers->FeaturesAndBenefits->$d->Benefit->__toString() . '" /></td>';
						echo '<td><textarea rows="4" name="' . $d . '_Product" style="width: 100%;">' . $answers->FeaturesAndBenefits->$d->Product->__toString() . '</textarea></td>';
						echo '<td><textarea rows="4" name="' . $d . '_Feature" style="width: 100%;">' . $answers->FeaturesAndBenefits->$d->Feature->__toString() . '</textarea></td>';
						echo '<td><textarea rows="4" name="' . $d . '_Benefit" style="width: 100%;">' . $answers->FeaturesAndBenefits->$d->Benefit->__toString() . '</textarea></td>';
						echo '</tr>';
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
				<div class="box-body assessorFeedback" <?php echo $feedback->FeaturesAndBenefits->Status->__toString() == 'A'?'style="pointer-events:none;"':''; ?>>
					<div class="form-group">
						<label for="status_FeaturesAndBenefits" class="col-sm-12 control-label">Status:</label>
						<div class="col-sm-12">
							<?php
							echo HTML::selectChosen('status_FeaturesAndBenefits', $answer_status, $feedback->FeaturesAndBenefits->Status->__toString() == 'A'?$feedback->FeaturesAndBenefits->Status->__toString():'', false, true); ?>
						</div>
					</div>
					<div class="form-group">
						<label for="comments_FeaturesAndBenefits" class="col-sm-12 control-label">Comments:</label>
						<div class="col-sm-12">
							<textarea name="comments_FeaturesAndBenefits" rows="7" style="width: 100%;"><?php echo $feedback->FeaturesAndBenefits->Comments->__toString(); ?></textarea>
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
		if($feedback->FeaturesAndBenefits->Status->__toString() == 'NA')
			echo HTML::renderWorkbookIcons('FeaturesAndBenefits', false, 'btn-danger');
		else
			echo HTML::renderWorkbookIcons('FeaturesAndBenefits', false, 'btn-success');
		?>
	</div></div></div>

	<?php echo $wb->getPageBottomLine(); ?>
</div> <!--.page 7 ends-->

<h1>Product and service</h1>
<div class="step-content">
	<?php echo $wb->getPageTopLine(); ?>

	<div class="row"><div class="col-sm-12"><div class="pull-right">
		<?php
		if($feedback->ActiveLinkSelling->Status->__toString() == 'NA')
			echo HTML::renderWorkbookIcons('ActiveLinkSelling', false, 'btn-danger');
		else
			echo HTML::renderWorkbookIcons('ActiveLinkSelling', false, 'btn-success');
		?>
	</div></div></div>

	<div class="row">
		<div class="col-sm-12">

			<p class="text-bold">Active/Link Selling</p>
		</div>
	</div>
	<div class="row">
		<div class="col-sm-8" <?php echo $feedback->ActiveLinkSelling->Status->__toString() == 'A'?'style="pointer-events:none;"':''; ?>>
			<p>Active selling is about informing our customers about the new products, services or offers that can make the difference between whether the customer shops with us or the competition.</p>
			<p>It’s a service to the customer, an opportunity to recommend products and build customer loyalty. This is often done at the till point. Make sure your customer is aware of any key promotions we have in store that day.</p>
		</div>
		<div class="col-sm-4"><img src="module_eportfolio/assets/images/wb10_pg8_img1.png" /></div>
		<div class="col-sm-12">
			<p>Remember to use features and benefits. Make sure you know what they are for the active sell products at the tills. They may not be interested in the offer, which is fine, but it could be something they want and they will be very grateful that you have highlighted the offer to them so they didn’t miss out!</p>
		</div>
		<div class="col-sm-6">
			<p class="text-bold"><img src="module_eportfolio/assets/images/wb2_img1.png" /> &nbsp;What are the current <?php echo $wb->savers_or_sp == 'savers'?'SAS':'Star Buys'; ?> on offer in your store?</p>
			<textarea rows="5" name="CurrentStartBuysOffers" style="width: 100%;"><?php echo $answers->ActiveLinkSelling->CurrentStartBuysOffers->__toString(); ?></textarea>
			<p><img src="module_eportfolio/assets/images/wb10_pg8_img2.png" /></p>
		</div>
		<div class="col-sm-6">
			<p class="text-bold"><img src="module_eportfolio/assets/images/wb2_img1.png" /> &nbsp;Choose one of the <?php echo $wb->savers_or_sp == 'savers'?'SAS':'Star Buys'; ?> and describe how you would sell it to your customers.
				Don’t forget to mention any features and benefits it may have
			</p>
			<textarea rows="5" name="StartBuysBenefits" style="width: 100%;"><?php echo $answers->ActiveLinkSelling->StartBuysBenefits->__toString(); ?></textarea>
		</div>
		<div class="col-sm-12">
			<p><span class="text-bold">Link selling</span> is offering a product linked to something that a customer is already buying.  For example if a customer buys some nail polish you could also offer them nail polish remover.</p>
			<p>Waterfall units are an important place that can be used to display possible active sell lines.
				Active selling and link selling are both important to our business.  This is because they encourage customers to buy more products in our stores, therefore increasing our sales.
			</p>
			<p class="text-bold"><img src="module_eportfolio/assets/images/wb2_img1.png" /> &nbsp;Complete the table to show what products you could link sell to the customer in each transaction.</p>
			<div class="table-responsive">
				<table class="table table-bordered text-center">
					<tr><th>Product</th><th>Link selling item</th></tr>
					<tr><th>Mascara</th><td><textarea rows="4" name="MascaraLink" style="width: 100%;"><?php echo $answers->ActiveLinkSelling->MascaraLink->__toString(); ?></textarea> </td></tr>
					<tr><th>Self-tan</th><td><textarea rows="4" name="SelfTanLink" style="width: 100%;"><?php echo $answers->ActiveLinkSelling->SelfTanLink->__toString(); ?></textarea> </td></tr>
					<tr><th>Shampoo</th><td><textarea rows="4" name="ShampooLink" style="width: 100%;"><?php echo $answers->ActiveLinkSelling->ShampooLink->__toString(); ?></textarea> </td></tr>
				</table>
			</div>
		</div>
	</div>

	<?php if($wb->id != '' && $_SESSION['user']->type == User::TYPE_ASSESSOR && ($wb->wb_status == Workbook::STATUS_LEARNER_COMPLETED || $wb->wb_status == Workbook::STATUS_BEING_CHECKED || $wb->wb_status == Workbook::STATUS_IV_REJECTED)) {?>
	<div class="row well">
		<div class="col-sm-12">
			<div class="box box-success box-solid">
				<div class="box-header with-border"><h3 class="box-title">Assessor Feedback</h3></div>
				<div class="box-body assessorFeedback" <?php echo $feedback->ActiveLinkSelling->Status->__toString() == 'A'?'style="pointer-events:none;"':''; ?>>
					<div class="form-group">
						<label for="status_ActiveLinkSelling" class="col-sm-12 control-label">Status:</label>
						<div class="col-sm-12">
							<?php
							echo HTML::selectChosen('status_ActiveLinkSelling', $answer_status, $feedback->ActiveLinkSelling->Status->__toString() == 'A'?$feedback->ActiveLinkSelling->Status->__toString():'', false, true); ?>
						</div>
					</div>
					<div class="form-group">
						<label for="comments_ActiveLinkSelling" class="col-sm-12 control-label">Comments:</label>
						<div class="col-sm-12">
							<textarea name="comments_ActiveLinkSelling" rows="7" style="width: 100%;"><?php echo $feedback->ActiveLinkSelling->Comments->__toString(); ?></textarea>
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
		if($feedback->ActiveLinkSelling->Status->__toString() == 'NA')
			echo HTML::renderWorkbookIcons('ActiveLinkSelling', false, 'btn-danger');
		else
			echo HTML::renderWorkbookIcons('ActiveLinkSelling', false, 'btn-success');
		?>
	</div></div></div>

	<?php echo $wb->getPageBottomLine(); ?>
</div> <!--.page 8 ends-->

<h1>Product and service</h1>
<div class="step-content">
	<?php echo $wb->getPageTopLine(); ?>

	<div class="row"><div class="col-sm-12"><div class="pull-right">
		<?php
		if($feedback->OwnBrand->Status->__toString() == 'NA')
			echo HTML::renderWorkbookIcons('OwnBrand', false, 'btn-danger');
		else
			echo HTML::renderWorkbookIcons('OwnBrand', false, 'btn-success');
		?>
	</div></div></div>

	<div class="row">
		<div class="col-sm-12">

			<p class="text-bold">Own Brand</p>
		</div>
	</div>
	<div class="row">
		<div class="col-sm-3"><img src="module_eportfolio/assets/images/wb10_pg9_img1.png" /> </div>
		<div class="col-sm-9">
			<p>We have over 1500 Own Brand products across all product categories – Health, Beauty and Toiletries – so there really is something to appeal to everybody. Our Own Brand products offer some real advantages for our customers. Not only are they great quality but they are also cheaper than similar main brands which means that they offer excellent value for money.</p>
			<p>All of our Own Brand products are sold with a 100% happiness guarantee. This means that customers can have a no quibble free refund on all Own Brand purchases if, for any reason, they are not happy with it.</p>
		</div>
		<div class="col-sm-12" <?php echo $feedback->OwnBrand->Status->__toString() == 'A'?'style="pointer-events:none;"':''; ?>>
			<p>Another key selling point is that ALL our products are BUAV approved (not tested on animals) and some are also Paraben free (parabens have been suggested to be linked to cancer. Although nothing is proven many customers are therefore keen to use products that do not contain parabens at all). These features are listed on the packaging.</p>
			<p>You may have already sold Own Brand products and we hope you use our Own Brand products yourself – great products at a great price...and you get 30% discount too!!</p>
			<p class="text-bold">Recommending the Product</p>
			<p>Where possible, make it Own Brand. It’s great to recommend products to our customers especially if you have personal experience of using them.</p>
			<p class="text-bold">
				<img src="module_eportfolio/assets/images/wb2_img1.png" /> &nbsp;Give an example of a time when you have promoted an Own Brand product and told the customer about the 100% happiness guarantee.
				If you haven’t done it yet, have a go and record what happened below.&nbsp;
				<img src="module_eportfolio/assets/images/wb2_img2.png" />
			</p>
			<textarea rows="4" name="PromotionExample" style="width: 100%;"><?php echo $answers->OwnBrand->PromotionExample->__toString(); ?></textarea>
			<p class="text-bold">
				<img src="module_eportfolio/assets/images/wb2_img1.png" /> &nbsp;Next complete the table below with examples of branded products you get asked for and then identify an Own Brand product you could recommend instead
			</p>
			<div class="table-responsive">
				<table class="table table-bordered text-center">
					<tr><th>Department</th><th>Branded Product</th><th>Own Brand recommendation</th></tr>
					<tr><th>Skin</th><td><textarea name="OwnBrandSkinBranded" style="width: 100%;" rows="3"><?php echo $answers->OwnBrand->Skin->Branded->__toString(); ?></textarea></td><td><textarea name="OwnBrandSkinOwnBrand" style="width: 100%;" rows="3"><?php echo $answers->OwnBrand->Skin->OwnBrand->__toString(); ?></textarea></td></tr>
					<tr><th>Mens</th><td><textarea name="OwnBrandMensBranded" style="width: 100%;" rows="3"><?php echo $answers->OwnBrand->Mens->Branded->__toString(); ?></textarea></td><td><textarea name="OwnBrandMensOwnBrand" style="width: 100%;" rows="3"><?php echo $answers->OwnBrand->Mens->OwnBrand->__toString(); ?></textarea></td></tr>
					<tr><th>Cosmetics</th><td><textarea name="OwnBrandCosmeticsBranded" style="width: 100%;" rows="3"><?php echo $answers->OwnBrand->Cosmetics->Branded->__toString(); ?></textarea></td><td><textarea name="OwnBrandCosmeticsOwnBrand" style="width: 100%;" rows="3"><?php echo $answers->OwnBrand->Cosmetics->OwnBrand->__toString(); ?></textarea></td></tr>
				</table>
			</div>
		</div>
	</div>

	<?php if($wb->id != '' && $_SESSION['user']->type == User::TYPE_ASSESSOR && ($wb->wb_status == Workbook::STATUS_LEARNER_COMPLETED || $wb->wb_status == Workbook::STATUS_BEING_CHECKED || $wb->wb_status == Workbook::STATUS_IV_REJECTED)) {?>
	<div class="row well">
		<div class="col-sm-12">
			<div class="box box-success box-solid">
				<div class="box-header with-border"><h3 class="box-title">Assessor Feedback</h3></div>
				<div class="box-body assessorFeedback" <?php echo $feedback->OwnBrand->Status->__toString() == 'A'?'style="pointer-events:none;"':''; ?>>
					<div class="form-group">
						<label for="status_OwnBrand" class="col-sm-12 control-label">Status:</label>
						<div class="col-sm-12">
							<?php
							echo HTML::selectChosen('status_OwnBrand', $answer_status, $feedback->OwnBrand->Status->__toString() == 'A'?$feedback->OwnBrand->Status->__toString():'', false, true); ?>
						</div>
					</div>
					<div class="form-group">
						<label for="comments_OwnBrand" class="col-sm-12 control-label">Comments:</label>
						<div class="col-sm-12">
							<textarea name="comments_OwnBrand" rows="7" style="width: 100%;"><?php echo $feedback->OwnBrand->Comments->__toString(); ?></textarea>
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
		if($feedback->OwnBrand->Status->__toString() == 'NA')
			echo HTML::renderWorkbookIcons('OwnBrand', false, 'btn-danger');
		else
			echo HTML::renderWorkbookIcons('OwnBrand', false, 'btn-success');
		?>
	</div></div></div>

	<?php echo $wb->getPageBottomLine(); ?>
</div> <!--.page 9 ends-->

<h1>Product and service</h1>
<div class="step-content">
	<?php echo $wb->getPageTopLine(); ?>

	<div class="row"><div class="col-sm-12"><div class="pull-right"><?php echo HTML::renderWorkbookIcons('', true); ?></div></div></div>

	<div class="row">
		<div class="col-sm-12">

			<p class="text-bold">The Five Steps of Selling Model</p>
		</div>
	</div>
	<div class="row">
		<div class="col-sm-12">
			<p><br>One of the most important jobs in retail is being able to sell to the customer. On the previous pages we talked about the importance of active and link selling, knowing your products and understanding the difference between features and benefits.<br></p>
			<p><br>Next we will look at using all that knowledge and how to put it in to practice to be a successful sales person.<br></p>
		</div>
		<div class="col-sm-12 table-responsive text-center">
			<img class="img-responsive" src="module_eportfolio/assets/images/wb10_pg10_img1.png" />
		</div>
	</div>

	<p><br></p>

	<div class="row"><div class="col-sm-12"><div class="pull-right"><?php echo HTML::renderWorkbookIcons('', true); ?></div></div></div>

	<?php echo $wb->getPageBottomLine(); ?>
</div> <!--.page 10 ends-->

<h1>Product and service</h1>
<div class="step-content">
	<?php echo $wb->getPageTopLine(); ?>

	<div class="row"><div class="col-sm-12"><div class="pull-right"><?php echo HTML::renderWorkbookIcons('', true); ?></div></div></div>

	<div class="row">
		<div class="col-sm-12">
			<p class="text-bold">Step 1: Building effective rapport with the customer</p>
			<p>Rapport is having a close relationship to those around us.  Rapport occurs when two or more people feel that they are on the same wavelength because they feel similar or relate well to each other. </p>
			<p><br>You can build up rapport with your customers by chatting with them about the products they like and by providing good customer service. Having a good rapport with the customer is really important as it ensures that they trust you, and are confident in the advice offered.  This makes them more likely to make a purchase.</p>
			<p><br>Building rapport should happen throughout the whole selling process, starting when you first see the customer and approach them, lasting right until after you have closed the sale.</p>
		</div>
		<div class="col-sm-6">
			<p class="text-bold">Step 2: Ask questions to establish what the customer needs</p>
			<p>Having effective questioning skills is really important as it will help you to choose the most appropriate products for your customer.</p>
			<p><br>Remember to use a range of open and probing questions at the beginning of the conversation as these will be the most effective.  You can also revisit your customer and communication section see examples of the questions you could use.</p>
			<p class="text-bold"><br>Step 3: Find the right product for the customer</p>
			<p>Once you know what the customers’ needs are, you will be able to choose a range of products to offer to them. Be aware of all the features and benefits of the products so you can help the customer to make the correct decision. A good tip is to be able to compare and contrast products.</p>
			<p class="text-bold"><br>Step 4: Link the product features to its benefits</p>
			<p>The next step is to be able to link the features of a product to a benefit. An example would be a small size hairspray. The feature is its size and the benefit is that it would fit into a handbag and be light to carry. This is a very important part of the process as it gives the customer all the information they need to make an informed choice.</p>
		</div>
		<div class="col-sm-6">
			<img class="img-responsive" src="module_eportfolio/assets/images/wb10_pg11_img1.png" />
			<p class="text-bold"><br>Step 5: Close the sale</p>
			<p>Customers will display buying signals to demonstrate they are ready. These can be verbal or non-verbal.</p>
			<p><br>Verbal buying signals may be the customer asking specific questions.  They may ask the sales assistant to clarify the details of the product.  They may also ask the sales assistant if the product is in stock and may ask others what they think of a product.</p>
			<p><br>Non-verbal buying signals may include a customer looking for a long time at one particular product. The final non-verbal buying signal is that the customer will reach for their purse or wallet.</p>
			<p><br>Once a customer has shown buying signals it is now time to close the sale.  One key way of doing this is asking the customer if they are ready to make their purchase and how would they like to pay (using closed questions). Now is also the ideal time to offer any link sales and incentives.</p>
		</div>
	</div>

	<p><br></p>

	<div class="row"><div class="col-sm-12"><div class="pull-right"><?php echo HTML::renderWorkbookIcons('', true); ?></div></div></div>

	<?php echo $wb->getPageBottomLine(); ?>
</div> <!--.page 11 ends-->

<h1>Product and service</h1>
<div class="step-content">
	<?php echo $wb->getPageTopLine(); ?>

	<div class="row"><div class="col-sm-12"><div class="pull-right">
		<?php
		if($feedback->IdentifyingCustomerNeeds->Status->__toString() == 'NA')
			echo HTML::renderWorkbookIcons('IdentifyingCustomerNeeds', false, 'btn-danger');
		else
			echo HTML::renderWorkbookIcons('IdentifyingCustomerNeeds', false, 'btn-success');
		?>
	</div></div></div>

	<div class="row">
		<div class="col-sm-12">

			<p class="text-bold">Identifying customer needs</p>
		</div>
	</div>
	<div class="row">
		<div class="col-sm-12" <?php echo $feedback->IdentifyingCustomerNeeds->Status->__toString() == 'A'?'style="pointer-events:none;"':''; ?>>
			<p>To identify customers’ needs you will need to ask them questions and actively listen to their responses.
				By asking the right questions you can go from having a bit of an idea of what they need to understanding them fully and being able to recommend products to meet or exceed their expectations.
			</p>
			<p><br>Closed questions can usually only be answered with a yes or no response whereas open questions request information from customers and usually encourage a detailed answer.</p>
			<p><br>A probing question is a type of open or closed question that digs a bit deeper and can help you to understand the finer details of the customers’ needs.</p>
			<p class="text-bold">
				<img src="module_eportfolio/assets/images/wb2_img1.png" /> &nbsp;Read the questions below and record next to each one whether you think it is a closed, open or probing question by selecting the relevant box
			</p>
			<div class="table-responsive">
				<table class="table table-bordered">
					<tr><th class="text-center">Question</th><th class="text-center">Open</th><th class="text-center">Closed</th><th class="text-center">Probing</th></tr>
					<?php
					$IdentifyingCustomerNeeds = $answers->IdentifyingCustomerNeeds;
					$i = 0;
					foreach(WBProductAndService::getClosedOpenProbingQuestionsList() AS $q)
					{
						echo '<tr>';
						echo '<td>' . $q . '</td>';
						$key = 'Question'.++$i;
						$open = $IdentifyingCustomerNeeds->$key->__toString() == 'O' ? 'checked="checked"' : '';
						$closed = $IdentifyingCustomerNeeds->$key->__toString() == 'C' ? 'checked="checked"' : '';
						$probing = $IdentifyingCustomerNeeds->$key->__toString() == 'P' ? 'checked="checked"' : '';
						echo '<td class="text-center"><input type="radio" name="'.$key.'" value="O" ' . $open . ' /></td>';
						echo '<td class="text-center"><input type="radio" name="'.$key.'" value="C" ' . $closed . ' /></td>';
						echo '<td class="text-center"><input type="radio" name="'.$key.'" value="P"' . $probing . ' /></td>';
						echo '</tr>';
					}
					?>
				</table>
			</div>
			<p class="text-bold">
				<img src="module_eportfolio/assets/images/wb2_img1.png" /> &nbsp;From the table can you identify the key words you need to use to ask an open question?
				List them below:
			</p>
			<textarea rows="5" name="OpenQuestionKeyWords" style="width: 100%;"><?php echo $IdentifyingCustomerNeeds->OpenQuestionKeyWords->__toString(); ?></textarea>
		</div>
	</div>

	<p><br></p>

	<div class="row" <?php echo $feedback->IdentifyingCustomerNeeds->Status->__toString() == 'A'?'style="pointer-events:none;"':''; ?>>
		<div class="col-sm-12">
			<p><span class="text-bold">Closed questions</span> can have a negative or positive impact on your conversation with your customer. If you start with a closed question, it may be the only question you get to ask as they may answer no and then you have nowhere to go.</p>
		</div>
		<div class="col-sm-3"><img class="img-responsive" src="module_eportfolio/assets/images/wb10_pg13_img1.png" /></div>
		<div class="col-sm-9">
			<p>If you start with an <span class="text-bold">open question</span>  to determine what they need help with, you can use <span class="text-bold">closed questions</span> later in the sales process to help narrow down any recommendations you make and help get a decision from the customer.</p>
			<p>For example after asking a number of open and probing questions you may have found out that your customer wants a black mascara.</p>
			<p><br>You show the customer 3 different mascaras. They all meet their needs but a decision has not yet been made. If you continue to ask open questions or show more options they may have too many to make a decision from and not make a purchase.</p>
		</div>
		<div class="col-sm-12">
			<p>If instead you start to ask <span class="text-bold">closed questions</span>, such as ‘Do you like this one? You will lead the customer to make a decision and ultimately a sale.</p>
		</div>
		<div class="col-sm-6">
			<p class="text-bold">
				<img src="module_eportfolio/assets/images/wb2_img1.png" /> &nbsp;Give an example of when you identified a customer’s needs by asking them questions.<br>
				What happened, did they make a purchase?
			</p>
			<textarea rows="5" name="ExampleAskingQuestions" style="width: 100%;"><?php echo $IdentifyingCustomerNeeds->ExampleAskingQuestions->__toString(); ?></textarea>
			<p class="text-bold"><img src="module_eportfolio/assets/images/wb2_img1.png" /> &nbsp;Give an example of when you think you could have done more to identify a customer’s needs.<br>What happened?</p>
			<textarea rows="5" name="ExampleToDoBetter" style="width: 100%;"><?php echo $IdentifyingCustomerNeeds->ExampleToDoBetter->__toString(); ?></textarea>
		</div>
		<div class="col-sm-6">
			<p class="text-bold">
				<img src="module_eportfolio/assets/images/wb2_img1.png" /> &nbsp;If you don’t ask the right type of questions what do you think will happen
			</p>
			<textarea rows="5" name="IncorrectQuestionResult" style="width: 100%;"><?php echo $IdentifyingCustomerNeeds->IncorrectQuestionResult->__toString(); ?></textarea>
			<p><img class="img-responsive" src="module_eportfolio/assets/images/wb10_pg13_img1.png" /></p>
		</div>
	</div>

	<?php if($wb->id != '' && $_SESSION['user']->type == User::TYPE_ASSESSOR && ($wb->wb_status == Workbook::STATUS_LEARNER_COMPLETED || $wb->wb_status == Workbook::STATUS_BEING_CHECKED || $wb->wb_status == Workbook::STATUS_IV_REJECTED)) {?>
	<div class="row well">
		<div class="col-sm-12">
			<div class="box box-success box-solid">
				<div class="box-header with-border"><h3 class="box-title">Assessor Feedback</h3></div>
				<div class="box-body assessorFeedback" <?php echo $feedback->IdentifyingCustomerNeeds->Status->__toString() == 'A'?'style="pointer-events:none;"':''; ?>>
					<div class="form-group">
						<label for="status_IdentifyingCustomerNeeds" class="col-sm-12 control-label">Status:</label>
						<div class="col-sm-12">
							<?php
							echo HTML::selectChosen('status_IdentifyingCustomerNeeds', $answer_status, $feedback->IdentifyingCustomerNeeds->Status->__toString() == 'A'?$feedback->IdentifyingCustomerNeeds->Status->__toString():'', false, true); ?>
						</div>
					</div>
					<div class="form-group">
						<label for="comments_IdentifyingCustomerNeeds" class="col-sm-12 control-label">Comments:</label>
						<div class="col-sm-12">
							<textarea name="comments_IdentifyingCustomerNeeds" rows="7" style="width: 100%;"><?php echo $feedback->IdentifyingCustomerNeeds->Comments->__toString(); ?></textarea>
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
		if($feedback->IdentifyingCustomerNeeds->Status->__toString() == 'NA')
			echo HTML::renderWorkbookIcons('IdentifyingCustomerNeeds', false, 'btn-danger');
		else
			echo HTML::renderWorkbookIcons('IdentifyingCustomerNeeds', false, 'btn-success');
		?>
	</div></div></div>

	<?php echo $wb->getPageBottomLine(); ?>
</div> <!--.page 12, 13 ends-->

<h1>Product and service</h1>
<div class="step-content">
	<?php echo $wb->getPageTopLine(); ?>

	<div class="row"><div class="col-sm-12"><div class="pull-right"><?php echo HTML::renderWorkbookIcons('', true); ?></div></div></div>

	<div class="row">
		<div class="col-sm-12">

			<p class="text-bold">Customers Legal Rights</p>
		</div>
	</div>
	<div class="row">
		<div class="col-sm-12">
			<p>To ensure you promote the brand and offer product and service correctly you must also know and understand the organisations policies and procedures and the law and legislation that protects customers.</p>
			<p><br>In this section we will look at what a policy and procedure is and also look at some of the law and legislation that applies in store.</p>
		</div>
		<div class="col-sm-12 table-responsive">
			<table class="table text-center">
				<tr>
					<td style="vertical-align: middle; background-color: #ffff00;"><p class="text-bold"> What is a policy?</p>
						<p><i>A policy is a short statement outlining what the company will do in different situations<br>
						E.g. refunding of goods</i></p>
						</p>
					</td>
					<td style="vertical-align: middle;"><img src="module_eportfolio/assets/images/wb10_pg6_img1.png" /></td>
					<td style="vertical-align: middle; background-color: #adff2f;"><p class="text-bold"> What is a procedure?</p>
						<p><i>A procedure is a list or explanation of how to do something<br>
							E.g. when dealing with the refund
						</i></p>
						</p>
					</td>
				</tr>
			</table>
		</div>
		<div class="col-sm-12">
			<p class="text-bold">When dealing with a refund an example of the procedure that we must follow is:</p>
			<ul style="margin-left: 15px; margin-bottom: 15px;">
				<li>A member of management is required to authorise all refunds and exchanges</li>
				<li>The customer must have the original receipt for all refunds (unless the item being returned is faulty)</li>
				<li>A product must be returned within 28 days of purchase (unless faulty)</li>
			</ul>
			<p><br>Misleading customers so they buy products which are not suitable will destroy their confidence and loyalty to your store.  In the long term, your sales and customer loyalty will be maintained by being honest and by exceeding your customers’ expectations. There are legal rights that protect customers and allow them to return goods which have been sold to them; this may be because the goods do not meet their requirements or they may have been misled by the seller.</p>
			<p class="text-bold">The three key areas of legislation that you must understand as an employee in retail are:</p>
			<table class="table table-bordered text-center"><tr><td class="bg-green">Customer Rights Act</td><td> &nbsp; </td><td class="bg-blue">Trade Description Act 1968</td><td> &nbsp; </td><td class="bg-purple">Price Marking Order 2004</td></tr></table>
			<p class="text-bold">Customer Rights Act 1965</p>
			<p>The Consumer Rights Act 2015 became law on 01 October 2015, replacing three major pieces of consumer legislation - the Sale of Goods Act, Unfair Terms in Consumer Contracts Regulations, and the Supply of Goods and Services Act. It was introduced to simplify, strengthen and modernise the law, giving clearer shopping rights.</p>
			<p class="text-bold">Product quality</p>
			<p>As with the Sale of Goods Act, under the Consumer Rights Act all products must be of satisfactory quality, fit for purpose and as described.</p>
			<p><span class="text-bold">Satisfactory quality: </span> Goods shouldn't be faulty or damaged when you receive them.</p>
			<p><span class="text-bold">Fit for purpose: </span> The goods should be fit for the purpose they are supplied for.</p>
			<p><span class="text-bold">As described: </span> The goods supplied must match any description given to you, or any models or samples shown to you at the time of purchase.</p>
		</div>
	</div>

	<p><br></p>

	<div class="row"><div class="col-sm-12"><div class="pull-right"><?php echo HTML::renderWorkbookIcons('', true); ?></div></div></div>

	<?php echo $wb->getPageBottomLine(); ?>
</div> <!--.page 14 ends-->

<h1>Product and service</h1>
<div class="step-content">
	<?php echo $wb->getPageTopLine(); ?>

	<div class="row"><div class="col-sm-12"><div class="pull-right">
		<?php
		if($feedback->LegalRights->Status->__toString() == 'NA')
			echo HTML::renderWorkbookIcons('LegalRights', false, 'btn-danger');
		else
			echo HTML::renderWorkbookIcons('LegalRights', false, 'btn-success');
		?>
	</div></div></div>

	<div class="row">
		<div class="col-sm-12">
			<p class="text-bold">Trade Description Act 1968</p>
			<p>This act makes it illegal to make false or misleading statements about goods or a service offered. This means the product must match any description given in writing or verbally.  For example, a pack of cotton buds described as ‘all wool’ on the label must be 100% wool. The act carries criminal penalties, and is enforced using Trading Standards Officers.</p>
			<img class="img-responsive" src="module_eportfolio/assets/images/wb10_pg14_img1.png" />
			<p class="text-bold">Price Marking Order 2004</p>
			<p>This Act states that where goods are offered to customers a price must be displayed.  The price must include VAT and all other taxes. The price ticket should also include how much the item is in units.
				Legally, the price must also be clear and easily identifiable. Customers must be able to see the price of a product without the need to ask someone for help.
			</p>
			<p class="text-bold">
				<img src="module_eportfolio/assets/images/wb2_img1.png" /> &nbsp;For each of the three areas of legislation identify one task or procedure that you do on a regular basis which ensures compliance with the law
			</p>
			<?php
			$Legislation = $answers->LegalRights->Legislation;
			?>
			<table class="table table-bordered text-center" <?php echo $feedback->LegalRights->Status->__toString() == 'A'?'style="pointer-events:none;"':''; ?>>
				<tr><th>Legislation</th><th>Task / Procedure</th><th>Why is this important?</th></tr>
				<tr><th>Consumer Rights Act 2015</th><td><textarea name="CRA2015Task" style="width: 100%;" rows="3"><?php echo $Legislation->CRA2015->Task->__toString(); ?></textarea></td><td><textarea name="CRA2015Why" style="width: 100%;" rows="3"><?php echo $Legislation->CRA2015->Why->__toString(); ?></textarea></td></tr>
				<tr><th>Trade Description Act 1968</th><td><textarea name="TDA1968Task" style="width: 100%;" rows="3"><?php echo $Legislation->TDA1968->Task->__toString(); ?></textarea></td><td><textarea name="TDA1968Why" style="width: 100%;" rows="3"><?php echo $Legislation->TDA1968->Why->__toString(); ?></textarea></td></tr>
				<tr><th>Price Marking Order 2004</th><td><textarea name="PMA2004Task" style="width: 100%;" rows="3"><?php echo $Legislation->PMA2004->Task->__toString(); ?></textarea></td><td><textarea name="PMA2004Why" style="width: 100%;" rows="3"><?php echo $Legislation->PMA2004->Why->__toString(); ?></textarea></td></tr>
			</table>
		</div>
	</div>

	<?php if($wb->id != '' && $_SESSION['user']->type == User::TYPE_ASSESSOR && ($wb->wb_status == Workbook::STATUS_LEARNER_COMPLETED || $wb->wb_status == Workbook::STATUS_BEING_CHECKED || $wb->wb_status == Workbook::STATUS_IV_REJECTED)) {?>
	<div class="row well">
		<div class="col-sm-12">
			<div class="box box-success box-solid">
				<div class="box-header with-border"><h3 class="box-title">Assessor Feedback</h3></div>
				<div class="box-body assessorFeedback" <?php echo $feedback->LegalRights->Status->__toString() == 'A'?'style="pointer-events:none;"':''; ?>>
					<div class="form-group">
						<label for="status_LegalRights" class="col-sm-12 control-label">Status:</label>
						<div class="col-sm-12">
							<?php
							echo HTML::selectChosen('status_LegalRights', $answer_status, $feedback->LegalRights->Status->__toString() == 'A'?$feedback->LegalRights->Status->__toString():'', false, true); ?>
						</div>
					</div>
					<div class="form-group">
						<label for="comments_LegalRights" class="col-sm-12 control-label">Comments:</label>
						<div class="col-sm-12">
							<textarea name="comments_LegalRights" rows="7" style="width: 100%;"><?php echo $feedback->LegalRights->Comments->__toString(); ?></textarea>
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
		if($feedback->LegalRights->Status->__toString() == 'NA')
			echo HTML::renderWorkbookIcons('LegalRights', false, 'btn-danger');
		else
			echo HTML::renderWorkbookIcons('LegalRights', false, 'btn-success');
		?>
	</div></div></div>

	<?php echo $wb->getPageBottomLine(); ?>
</div> <!--.page 15 ends-->

<h1>Product and service</h1>
<div class="step-content">
	<?php echo $wb->getPageTopLine(); ?>

	<div class="row"><div class="col-sm-12"><div class="pull-right">
		<?php
		if($feedback->ExcellentService->Status->__toString() == 'NA')
			echo HTML::renderWorkbookIcons('ExcellentService', false, 'btn-danger');
		else
			echo HTML::renderWorkbookIcons('ExcellentService', false, 'btn-success');
		?>
	</div></div></div>

	<div class="row">
		<div class="col-sm-12">

			<p class="text-bold">Excellent Service</p>
		</div>
	</div>
	<div class="row">
		<div class="col-sm-12" <?php echo $feedback->ExcellentService->Status->__toString() == 'A'?'style="pointer-events:none;"':''; ?>>
			<p>If you meet your customers’ needs and expectations you should be able to provide customer service that can be described, at least, as satisfactory. However, you should be aiming to deliver truly excellent customer service.</p>
			<p class="text-bold"><br>How do you make the step up from satisfactory to excellent?</p>
			<p>Providing a product or service that a customer wants, and doing this in the ways that the customer expects, is not going to be good enough. You cannot change your customers’ needs but you can do more for them than they expect you to do.</p>
			<p><br>This involves <span class="text-bold">'going the extra mile'</span> and giving extra attention to everything that affects your customers.<br>
				When we deliver excellent customer service:
			</p>
			<ul style="margin-left: 15px; margin-bottom: 15px;">
				<li>Customers’ needs are met</li>
				<li>Customers’ expectations are exceeded</li>
				<li>Customers enjoy the shopping experience</li>
				<li>Customers want to come back and will recommend the business to others</li>
			</ul>
			<p>Customers who receive excellent standards of service will become more loyal to those organisations that deliver on their service offer, than those that do not. The ability to build repeat business is very much affected by the levels of customer loyalty, which in turn is strongly linked to the service offer delivered by the organisation. If a business has a good reputation for high levels of customer service it will often attract new customers and therefore increase sales and profits even more.</p>
			<p class="text-bold">
				<img src="module_eportfolio/assets/images/wb2_img1.png" /> &nbsp;Give an example of when you have given excellent customer service to a customer and gone the extra mile.
			</p>
			<textarea rows="5" name="ExcellentService" style="width: 100%;"><?php echo $answers->ExcellentService->__toString(); ?></textarea>
		</div>
	</div>
	<div class="row">
		<div class="col-sm-6 text-center"><img src="module_eportfolio/assets/images/wb10_pg16_img1.png" /></div>
		<div class="col-sm-6 text-center"><img src="module_eportfolio/assets/images/wb10_pg16_img2.png" /></div>
	</div>

	<?php if($wb->id != '' && $_SESSION['user']->type == User::TYPE_ASSESSOR && ($wb->wb_status == Workbook::STATUS_LEARNER_COMPLETED || $wb->wb_status == Workbook::STATUS_BEING_CHECKED || $wb->wb_status == Workbook::STATUS_IV_REJECTED)) {?>
	<div class="row well">
		<div class="col-sm-12">
			<div class="box box-success box-solid">
				<div class="box-header with-border"><h3 class="box-title">Assessor Feedback</h3></div>
				<div class="box-body assessorFeedback" <?php echo $feedback->ExcellentService->Status->__toString() == 'A'?'style="pointer-events:none;"':''; ?>>
					<div class="form-group">
						<label for="status_ExcellentService" class="col-sm-12 control-label">Status:</label>
						<div class="col-sm-12">
							<?php
							echo HTML::selectChosen('status_ExcellentService', $answer_status, $feedback->ExcellentService->Status->__toString() == 'A'?$feedback->ExcellentService->Status->__toString():'', false, true); ?>
						</div>
					</div>
					<div class="form-group">
						<label for="comments_ExcellentService" class="col-sm-12 control-label">Comments:</label>
						<div class="col-sm-12">
							<textarea name="comments_ExcellentService" rows="7" style="width: 100%;"><?php echo $feedback->ExcellentService->Comments->__toString(); ?></textarea>
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
		if($feedback->ExcellentService->Status->__toString() == 'NA')
			echo HTML::renderWorkbookIcons('ExcellentService', false, 'btn-danger');
		else
			echo HTML::renderWorkbookIcons('ExcellentService', false, 'btn-success');
		?>
	</div></div></div>

	<?php echo $wb->getPageBottomLine(); ?>
</div> <!--.page 16 ends-->

<h1>Product and service</h1>
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
			<h3 class="text-bold">Unit 3: Understanding the organisation</h3>
			<div class="callout callout-info">
				<p>To achieve learning outcome 2 (Understand the products or services that are available from your organisation) answer the following questions in as much detail as you can.</p>
				<p>NB: * Please check unit amplification after qualification questions</p>
			</div>
		</div>
		<div class="col-sm-12" <?php echo $feedback->QualificationQuestions->Status->__toString() == 'A'?'style="pointer-events:none;"':''; ?>>
			<p class="text-bold">2.1 Describe the features and benefits of products / services the organisation offers*</p>
			<textarea name="QualQuestion1" style="width: 100%;" rows="7"><?php echo $answers->QualificationQuestions->Question1->__toString(); ?></textarea>
			<p class="text-bold">2.2 Describe how to maintain their knowledge of the organisation’s products and / or services</p>
			<textarea name="QualQuestion2" style="width: 100%;" rows="7"><?php echo $answers->QualificationQuestions->Question2->__toString(); ?></textarea>
			<p class="text-bold">2.3 Explain why it is important to update their knowledge on the organisation’s products / services</p>
			<textarea name="QualQuestion3" style="width: 100%;" rows="7"><?php echo $answers->QualificationQuestions->Question3->__toString(); ?></textarea>
		</div>
		<div class="col-sm-12">
			<p><br></p>
			<div class="col-sm-12 table-responsive">
				<table class="table row-border"  <?php echo $_SESSION['user']->type == User::TYPE_LEARNER || !$wb->enableForUser() ?'style="pointer-events:none;"':''; ?>>
					<?php
					$criteria_met = explode(',', $wb->wb_content->CriteriaMet->__toString());
					?>
					<tr><th>Amplifications / Indicative content</th><th>Criteria met?</th></tr>
					<tr>
						<td>
							<p>2.1 – Learners should describe both the features and the benefits of products / services that their organisation offers:</p>
							<ul style="margin-left: 15px;">
								<li>features are what the product/service does; and</li>
								<li>benefits are how the features meet the needs of customers</li>
							</ul>
						</td>
						<td>
							<input class="clsCriteriaMet" name="CriteriaMet[]" type="checkbox" value="1" <?php echo in_array(1, $criteria_met) ? 'checked="checked"' : ''; ?> />
						</td>
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
</div> <!--.page 17 ends-->

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
</div> <!--.page 13 ends-->

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

		$("#frm_wb_product_and_service :input").not(".clsCriteriaMet, .assessorFeedback :input, #signature_text, #frm_wb_product_and_service :input[type=hidden], .btnViewSectionHistory, .btnViewAssessorComments, #iv_status, #iv_comments, #reopen_comments").prop("disabled", true);

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
				//return alert('Your signature is required to complete the workbook, please sign the workbook');
					return custom_alert_OK_only('Your signature is required to complete the workbook, please sign the workbook');

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
							var myForm = document.forms['frm_wb_product_and_service'];
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
				var myForm = document.forms['frm_wb_product_and_service'];
				myForm.elements['full_save'].value = 'Y';
				return previewInputInformation();
				<?php } else {?>
				return window.history.back();
				<?php } ?>
			}

		});

		$('input[type=radio]').iCheck({
			radioClass: 'iradio_square-green'
		});

		$('input[type=checkbox]').iCheck({
			checkboxClass: 'icheckbox_flat-green'
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

					var myForm = document.forms['frm_wb_product_and_service'];
					myForm.elements['full_save'].value = 'Y';
					myForm.elements['full_save_feedback'].value = 'Y';
					window.onbeforeunload = null;
					myForm.submit();

				},
				'Save And Come Back Later':function () {

					var myForm = document.forms['frm_wb_product_and_service'];
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
		$('#frm_wb_product_and_service :input[name=full_save]').val('N');
		$($('#frm_wb_product_and_service').serializeArray()).each(function(i, field)
		{
			document.forms["frm_wb_product_and_service"].elements[field.name].value = field.value.replace(/£/g, "GBP");
		});
		$.ajax({
			type:'POST',
			url:'do.php?_action=save_wb_product_and_service',
			data: $('#frm_wb_product_and_service').serialize(),
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

</html>
