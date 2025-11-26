<?php /* @var $wb WBCustomerExperience */ ?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/html" xmlns="http://www.w3.org/1999/html" xmlns="http://www.w3.org/1999/html"
      xmlns="http://www.w3.org/1999/html">
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<title>Customer Experience workbook</title>
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

<form name="frm_wb_customer_experience" id="frm_wb_customer_experience" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
<input type="hidden" name="_action" value="save_wb_customer_experience" />
<input type="hidden" name="id" value="<?php echo $wb->id; ?>" />
<input type="hidden" name="tr_id" value="<?php echo $wb->tr_id; ?>" />
<input type="hidden" name="wb_status" id="wb_status" value="" />
<input type="hidden" name="full_save" id="full_save" value="<?php echo $wb->full_save; ?>" />
<input type="hidden" name="full_save_feedback" id="full_save_feedback" value="<?php echo $wb->full_save_feedback; ?>" />
<div class="container-float">
<div class="wrapper" style="background-color: #ffffff;">

<header class="main-header"><span style="margin-left: 50%;"><?php echo DAO::getSingleValue($link, "SELECT title FROM student_frameworks WHERE tr_id = '{$wb->tr_id}'"); ?></span></header>

<div class="content-wrapper" style="background-color: #ffffff;">

<?php echo $wb->savers_or_sp == 'savers' ? '<section class="content-header"><h1 style="color: #0000ff;">Customer experience</h1></section>' : '<section class="content-header"><h1>Customer experience</h1></section>' ?>

<section class="content">

<div id="wizard">

<h1>Customer experience</h1>
<div class="step-content">
	<?php echo $wb->getPageTopLine(); ?>
	<div style="position: absolute; top: 40%; right: 50%;" class="lead">
		<?php echo $wb->savers_or_sp == 'savers' ? '<h2 class="text-bold" style="color: #0000ff;">Customer Experience</h2>' : '<h2 class="text-bold">Customer Experience</h2>' ?>
		<p class="text-center" >Module 3</p>
	</div>

	<?php echo $wb->getPageBottomLine(); ?>
</div> <!--.page 1 ends-->

<h1>Customer experience</h1>
<div class="step-content">
	<?php echo $wb->getPageTopLine(); ?>

	<div class="row"><div class="col-sm-12"><div class="pull-right"><?php echo HTML::renderWorkbookIcons('', true); ?></div></div></div>

	<div class="row">
		<div class="col-sm-12">
			<p>This module is about the customer experience. The definition of a customer experience is (the product of an interaction between the organisation and the customer over the period of their relationship).  It is about making every customer have a good shopping experience and making sure they want to return to your store again and again. It is about making every interaction with a customer an opportunity to increase, gain, maintain or re-establish their loyalty.</p>
			<p>In this module you will look at:</p>
			<ul style="margin-left: 15px; margin-bottom: 15px;">
				<li>What is meant by a �customer experience?�</li>
				<li>Customer service standards, policies and procedures</li>
				<li>The key features and benefits of excellent customer service </li>
				<li>The <?php echo ucfirst($wb->savers_or_sp); ?> customer service offer </li>
				<li>The implications of poor customer service</li>
				<li>How to give your customers a positive impression of yourself and your store</li>
				<li>How to identify your customers� needs and respond appropriately</li>
				<li>Understand how to deal with customer conflict and challenge</li>
				<li>Leave customers with a positive impression of their shopping experience and build loyalty</li>
				<li>Understanding different types of measurement and evaluation tools available to monitor customer service levels</li>
			</ul>
		</div>
		<div class="col-sm-12">
			<img src="module_eportfolio/assets/images/wb3_pg2_img1.png" />
		</div>
	</div>

	<p><br></p>

	<div class="row"><div class="col-sm-12"><div class="pull-right"><?php echo HTML::renderWorkbookIcons('', true); ?></div></div></div>

	<?php echo $wb->getPageBottomLine(); ?>
</div> <!--.page 2 ends-->

<h1>Customer experience</h1>
<div class="step-content">
	<?php echo $wb->getPageTopLine(); ?>

	<div class="row"><div class="col-sm-12"><div class="pull-right">
		<?php
		if($feedback->CustomerExperienceMeaning->Status->__toString() == 'NA')
			echo HTML::renderWorkbookIcons('CustomerExperienceMeaning', false, 'btn-danger');
		else
			echo HTML::renderWorkbookIcons('CustomerExperienceMeaning', false, 'btn-success');
		?>
	</div></div></div>

	<div class="row">
		<div class="col-sm-12">

			<p class="text-bold">What is meant by a �customer experience?�</p>
		</div>
	</div>

	<div class="row">
		<div class="col-sm-12">
			<p>The �customer experience� is simply the result of what happens between the business and its customer during interactions. This includes how the experience of interactions feels and how they are perceived by each customer. The customers� perception will provoke an emotional response and this response can have both negative and positive effects.</p>
			<p><br>It is important to acknowledge that the �customer experience� will differ depending on the type of business, for example, shopping in a high-end store will give a different experience to shopping in a lower cost store but customers can still have a positive response, depending on their needs and expectations.</p>
			<p><br>The experiences customers have during interactions with both the employee and the business will play a big part in forming their expectations and opinions. This will include how employees behave whilst   establishing the facts and using them to create a customer focused experience.</p>
			<p class="text-bold"><img src="module_eportfolio/assets/images/wb2_img1.png" /> &nbsp; What Customer Experience training have you had in <?php echo ucfirst($wb->savers_or_sp); ?> and what impact do you think it has on our customers?</p>
			<table class="table table-bordered" <?php echo $feedback->CustomerExperienceMeaning->Status->__toString() == 'A'?'style="pointer-events:none;"':''; ?>>
				<tr><th>Customer Experience Training you have had?</th><th>What have you learned?</th><th>What impact has this had on customers?</th></tr>
				<?php
				$_sd = $answers->CustomerExperienceMeaning->Superdrug;
				for($i = 1; $i <= 3; $i++)
				{
					$key = 'Set'.$i;
					$f1_val = isset($_sd->$key->Training)?$_sd->$key->Training->__toString():'';
					$f2_val = isset($_sd->$key->Learning)?$_sd->$key->Learning->__toString():'';
					$f3_val = isset($_sd->$key->Impact)?$_sd->$key->Impact->__toString():'';
					echo '<tr>';
					echo '<td><textarea rows="3" name="'.$key.'_Training" style="width: 100%;">' . $f1_val . '</textarea> </td>';
					echo '<td><textarea rows="3" name="'.$key.'_Learning" style="width: 100%;">' . $f2_val . '</textarea> </td>';
					echo '<td><textarea rows="3" name="'.$key.'_Impact" style="width: 100%;">' . $f3_val . '</textarea> </td>';
					echo '</tr>';
				}
				?>
			</table>
			<p class="text-bold"><img src="module_eportfolio/assets/images/wb2_img1.png" /> &nbsp; Give two examples of your own customer experiences in other retailers. How do you think it compares with what you do in <?php echo ucfirst($wb->savers_or_sp); ?>?</p>
			<table class="table table-bordered" <?php echo $feedback->CustomerExperienceMeaning->Status->__toString() == 'A'?'style="pointer-events:none;"':''; ?>>
				<tr><th>Retailer</th><th>Experience</th><th>How did the service compare with what you do? Good/Bad?</th></tr>
				<?php
				$_nsd = $answers->CustomerExperienceMeaning->NonSuperdrug;
				for($i = 1; $i <= 2; $i++)
				{
					$key = 'Set'.$i;
					$f1_val = isset($_nsd->$key->Retailer)?$_nsd->$key->Retailer->__toString():'';
					$f2_val = isset($_nsd->$key->Experience)?$_nsd->$key->Experience->__toString():'';
					$f3_val = isset($_nsd->$key->Comparison)?$_nsd->$key->Comparison->__toString():'';
					echo '<tr>';
					echo '<td><textarea rows="3" name="'.$key.'_Retailer" style="width: 100%;">' . $f1_val . '</textarea> </td>';
					echo '<td><textarea rows="3" name="'.$key.'_Experience" style="width: 100%;">' . $f2_val . '</textarea> </td>';
					echo '<td><textarea rows="3" name="'.$key.'_Comparison" style="width: 100%;">' . $f3_val . '</textarea> </td>';
					echo '</tr>';
				}
				?>
			</table>
			<p><img class="pull-right" src="module_eportfolio/assets/images/wb4_img1.png" /></p>
		</div>
	</div>

	<?php if($wb->id != '' && $_SESSION['user']->type == User::TYPE_ASSESSOR && ($wb->wb_status == Workbook::STATUS_LEARNER_COMPLETED || $wb->wb_status == Workbook::STATUS_BEING_CHECKED || $wb->wb_status == Workbook::STATUS_IV_REJECTED)) {?>
	<div class="row well">
		<div class="col-sm-12">
			<div class="box box-success box-solid">
				<div class="box-header with-border"><h3 class="box-title">Assessor Feedback</h3></div>
				<div class="box-body assessorFeedback" <?php echo $feedback->CustomerExperienceMeaning->Status->__toString() == 'A'?'style="pointer-events:none;"':''; ?>>
					<div class="form-group">
						<label for="status_CustomerExperienceMeaning" class="col-sm-12 control-label">Status:</label>
						<div class="col-sm-12">
							<?php
							echo HTML::selectChosen('status_CustomerExperienceMeaning', $answer_status, $feedback->CustomerExperienceMeaning->Status->__toString() == 'A'?$feedback->CustomerExperienceMeaning->Status->__toString():'', false, true); ?>
						</div>
					</div>
					<div class="form-group">
						<label for="comments_CustomerExperienceMeaning" class="col-sm-12 control-label">Comments:</label>
						<div class="col-sm-12">
							<textarea name="comments_CustomerExperienceMeaning" rows="7" style="width: 100%;"><?php echo $feedback->CustomerExperienceMeaning->Comments->__toString(); ?></textarea>
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
		if($feedback->CustomerExperienceMeaning->Status->__toString() == 'NA')
			echo HTML::renderWorkbookIcons('CustomerExperienceMeaning', false, 'btn-danger');
		else
			echo HTML::renderWorkbookIcons('CustomerExperienceMeaning', false, 'btn-success');
		?>
	</div></div></div>

	<?php echo $wb->getPageBottomLine(); ?>
</div> <!--.page 3 ends-->

<h1>Customer experience</h1>
<div class="step-content">
	<?php echo $wb->getPageTopLine(); ?>

	<div class="row"><div class="col-sm-12"><div class="pull-right"><?php echo HTML::renderWorkbookIcons('', true); ?></div></div></div>

	<div class="row">
		<div class="col-sm-12">

			<p class="text-bold">Customer service standards, policies and procedures</p>
		</div>
	</div>
	<div class="row">
		<div class="col-sm-12">
			<p>To ensure that everybody in the business understands what they have to do to meet customers� needs and expectations and how they can deliver excellent customer service, the retail industry develops:</p>
			<table class="table text-center">
				<tr><td style="vertical-align: middle;"><div style="background-color: #adff2f; padding: 10px;"><h4 class="text-bold">Customer service standards</h4></div></td></tr>
				<tr><td style="vertical-align: middle;"><img src="module_eportfolio/assets/images/wb3_pg4_img1.png" /></td></tr>
				<tr><td style="vertical-align: middle;"><div style="border: #000000; border-style: solid; padding: 10px;"><p><span class="text-bold">Customer service standards</span> are measurable objectives that can be used to gauge whether or not customers are receiving the quality of service required by a business or the industry as a whole.</p></div></td></tr>
				<tr><td style="vertical-align: middle;"><div style="background-color: #ffff00; padding: 10px;"><h4 class="text-bold">Customer service policies</h4></div></td></tr>
				<tr><td style="vertical-align: middle;"><img src="module_eportfolio/assets/images/wb3_pg4_img2.png" /></td></tr>
				<tr>
					<td style="vertical-align: middle;">
						<div style="border: #000000; border-style: solid; padding: 10px;">
							<p>Businesses develop Customer service policies to describe how they want their customers to be treated in particular situations.</p>
							<p>Customer service policies in retail may be about subjects such as:</p>
							<li>Waiting times at tills</li>
							<li>Approaching customers on the sales floor</li>
							<li>Refunds and exchanges</li>
							<li>Dealing with customer complaints</li>
						</div>
					</td>
				</tr>
				<tr><td style="vertical-align: middle;" class="bg-green"><div style="padding: 10px;"><h4 class="text-bold">Customer service procedures</h4></div></td></tr>
				<tr><td style="vertical-align: middle;" align="center"><img src="module_eportfolio/assets/images/wb3_pg4_img3.png" /></td></tr>
				<tr>
					<td style="vertical-align: middle; ">
						<div style="border: #000000; border-style: solid;">
							<p>The <span class="text-bold">Customer service procedures</span> of the organisation tell the employees of the business how they should work in order to adhere to the customer service policies and to achieve the customer service standard of the organisation.</p>
							<p>Customer service procedures should break down customer service activities into their component parts and describe how each aspect of the task should be undertaken.</p>
						</div>
					</td>
				</tr>
			</table>
			<p>You will be able to see that customer service standards can be used to develop an organisation�s customer service policies. In turn customer service policies are used to create the procedures which influence the service that every customer receives. Customers themselves benefit from customer service standards, policies and procedures. Effective customer service standards can drive up the quality of service provided throughout the retail industry as a whole. Customer service policies and procedures should mean that all customers are treated the same in particular situations.</p>
		</div>
	</div>

	<p><br></p>

	<div class="row"><div class="col-sm-12"><div class="pull-right"><?php echo HTML::renderWorkbookIcons('', true); ?></div></div></div>

	<?php echo $wb->getPageBottomLine(); ?>
</div> <!--.page 4 ends-->

<h1>Customer experience</h1>
<div class="step-content">
	<?php echo $wb->getPageTopLine(); ?>

	<div class="row"><div class="col-sm-12"><div class="pull-right"><?php echo HTML::renderWorkbookIcons('', true); ?></div></div></div>

	<div class="row">
		<div class="col-sm-12">

			<p class="text-bold">The key features and benefits of excellent customer service</p>
		</div>
	</div>
	<div class="row">
		<div class="col-sm-10">
			<p>Excellent customer service is paramount to the success of a business. You will need to know how the business you work for approaches customer service and the procedures or processes in place to ensure that this occurs.  Every business will be unique in its approach to customer service; below are some examples of general features and benefits.</p>
		</div>
		<div class="col-sm-2">
			<img class="pull-right" src="module_eportfolio/assets/images/wb4_img1.png" />
		</div>
		<div class="col-sm-12">
			<img class="img-responsive" src="module_eportfolio/assets/images/wb3_pg5_img1.png" />
		</div>
		<div class="col-sm-12">
			<p><h4 class="text-bold">Meeting customers' expectations</h4></p>
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
			<div class="bg-red" style="padding: 10px;">
				<p>
					<?php echo ucfirst($wb->savers_or_sp); ?> Customer Promise<br><br>
					We want to make sure each and every one of our customers experience that �<?php echo ucfirst($wb->savers_or_sp); ?> Feeling�.<br><br>
					Customer service is such an integral part of our business. One of our main aims at <?php echo ucfirst($wb->savers_or_sp); ?> is to ensure that we give you great customer service and an enjoyable, pleasant shopping experience, whilst offering the latest in beauty and health.
				</p>
			</div>
		</div>
	</div>

	<p><br></p>

	<div class="row"><div class="col-sm-12"><div class="pull-right"><?php echo HTML::renderWorkbookIcons('', true); ?></div></div></div>

	<?php echo $wb->getPageBottomLine(); ?>
</div> <!--.page 5 ends-->

<h1>Customer experience</h1>
<div class="step-content">
	<?php echo $wb->getPageTopLine(); ?>

	<div class="row"><div class="col-sm-12"><div class="pull-right">
		<?php
		if($feedback->CustomerExperienceFeatures->Status->__toString() == 'NA')
			echo HTML::renderWorkbookIcons('CustomerExperienceFeatures', false, 'btn-danger');
		else
			echo HTML::renderWorkbookIcons('CustomerExperienceFeatures', false, 'btn-success');
		?>
	</div></div></div>



	<div class="row">
		<div class="col-sm-12" <?php echo $feedback->CustomerExperienceFeatures->Status->__toString() == 'A'?'style="pointer-events:none;"':''; ?>>
			<p class="text-bold"><img src="module_eportfolio/assets/images/wb2_img1.png" /> &nbsp; What specific features are there in <?php echo ucfirst($wb->savers_or_sp); ?>�s customer service offer? List them below and for each feature, show what the benefits are?</p>
			<table class="table table-bordered">
				<tr><th style="width: 33%;">Customer Service Offer</th><th style="width: 33%;">Features</th><th style="width: 33%;">Benefits</th></tr>
				<tr><td>Customer Experience training</td><td>All staff are trained to the same standard across the business</td><td>Customers will get the same customer service experience in any store they visit</td></tr>
				<?php
				$_sd = $answers->CustomerExperienceFeatures->Superdrug;
				for($i = 1; $i <= 3; $i++)
				{
					$key = 'Set'.$i;
					$f1_val = isset($_sd->$key->Offer)?$_sd->$key->Offer->__toString():'';
					$f2_val = isset($_sd->$key->Features)?$_sd->$key->Features->__toString():'';
					$f3_val = isset($_sd->$key->Benefits)?$_sd->$key->Benefits->__toString():'';
					echo '<tr>';
					echo '<td><textarea rows="3" name="'.$key.'_Offer" style="width: 100%;">' . $f1_val . '</textarea> </td>';
					echo '<td><textarea rows="3" name="'.$key.'_Features" style="width: 100%;">' . $f2_val . '</textarea> </td>';
					echo '<td><textarea rows="3" name="'.$key.'_Benefits" style="width: 100%;">' . $f3_val . '</textarea> </td>';
					echo '</tr>';
				}
				?>
			</table>
			<img class="text-center img-responsive" src="module_eportfolio/assets/images/wb3_pg6_img1.png" />
			<p class="text-bold"><img src="module_eportfolio/assets/images/wb2_img1.png" /> &nbsp; Identify a local retail business which has a good reputation for customer service and explain why you think this is?</p>
			<textarea name="NonSuperdrug" style="width: 100%;" rows="7"><?php echo $answers->CustomerExperienceFeatures->NonSuperdrug->__toString(); ?></textarea>
		</div>
	</div>

	<?php if($wb->id != '' && $_SESSION['user']->type == User::TYPE_ASSESSOR && ($wb->wb_status == Workbook::STATUS_LEARNER_COMPLETED || $wb->wb_status == Workbook::STATUS_BEING_CHECKED || $wb->wb_status == Workbook::STATUS_IV_REJECTED)) {?>
	<div class="row well">
		<div class="col-sm-12">
			<div class="box box-success box-solid">
				<div class="box-header with-border"><h3 class="box-title">Assessor Feedback</h3></div>
				<div class="box-body assessorFeedback" <?php echo $feedback->CustomerExperienceFeatures->Status->__toString() == 'A'?'style="pointer-events:none;"':''; ?>>
					<div class="form-group">
						<label for="status_CustomerExperienceFeatures" class="col-sm-12 control-label">Status:</label>
						<div class="col-sm-12">
							<?php
							echo HTML::selectChosen('status_CustomerExperienceFeatures', $answer_status, $feedback->CustomerExperienceFeatures->Status->__toString() == 'A'?$feedback->CustomerExperienceFeatures->Status->__toString():'', false, true); ?>
						</div>
					</div>
					<div class="form-group">
						<label for="comments_CustomerExperienceFeatures" class="col-sm-12 control-label">Comments:</label>
						<div class="col-sm-12">
							<textarea name="comments_CustomerExperienceFeatures" rows="7" style="width: 100%;"><?php echo $feedback->CustomerExperienceFeatures->Comments->__toString(); ?></textarea>
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
		if($feedback->CustomerExperienceFeatures->Status->__toString() == 'NA')
			echo HTML::renderWorkbookIcons('CustomerExperienceFeatures', false, 'btn-danger');
		else
			echo HTML::renderWorkbookIcons('CustomerExperienceFeatures', false, 'btn-success');
		?>
	</div></div></div>

	<?php echo $wb->getPageBottomLine(); ?>
</div> <!--.page 6 ends-->

<h1>Customer experience</h1>
<div class="step-content">
	<?php echo $wb->getPageTopLine(); ?>

	<div class="row"><div class="col-sm-12"><div class="pull-right">
		<?php
		if($feedback->ImplicationsOfPoorCustomerService->Status->__toString() == 'NA')
			echo HTML::renderWorkbookIcons('ImplicationsOfPoorCustomerService', false, 'btn-danger');
		else
			echo HTML::renderWorkbookIcons('ImplicationsOfPoorCustomerService', false, 'btn-success');
		?>
	</div></div></div>

	<div class="row">
		<div class="col-sm-12">

			<p class="text-bold">Implications of poor customer service</p>
		</div>
	</div>

	<div class="row">
		<div class="col-sm-12" <?php echo $feedback->ImplicationsOfPoorCustomerService->Status->__toString() == 'A'?'style="pointer-events:none;"':''; ?>>
			<p>Just as excellent customer service impacts the success of a company, poor customer service has implications too.</p>
			<ul style="margin-left: 15px; margin-bottom: 15px;">
				<li>
					<p class="text-bold">Loss of current customers</p>
					<p>Consumers tend to do business with a company because it�s convenient, it�s a habit, or they�re looking for a particular product or service that�s hard to find elsewhere. Even the most loyal customers can be turned away by poor levels of service.</p>
				</li>
				<li>
					<p class="text-bold">Loss of potential customers</p>
					<p>When new customers walk into a business and find themselves ignored, talked down to or subjected to long queues, they may head for the door before they even reach for their wallets.</p>
				</li>
				<li>
					<p class="text-bold">Loss of future customers</p>
					<p>Customers who experience poor service levels often tell their friends and family members about the bad experience to warn them away. This will cost the business potential customers. People will have already formed a negative opinion of the business before ever setting foot in the door.</p>
				</li>
				<li>
					<p class="text-bold">Loss of reputation</p>
					<p>A reputation for poor service can be hard to shake. It can keep other businesses from partnering or working with you. It also can turn away good employment candidates who might assume that if customers are treated poorly, employees are treated badly as well.</p>
				</li>
				<li>
					<p class="text-bold">Loss of employees</p>
					<p>Even poor-performing employees don�t like to be confronted by unhappy customers, which can result in high turnover within the workforce. It is costly and time consuming to constantly have to advertise for new workers, then recruit and train them</p>
				</li>
				<li>
					<p class="text-bold">Loss of profits</p>
					<p>Poor customer service typically results in fewer customers, which translates into lower sales and profits for the business.</p>
				</li>
			</ul>
			<p class="text-bold"><img src="module_eportfolio/assets/images/wb2_img1.png" /> &nbsp; Identify a local retail business which has a bad reputation for customer service and explain why you think this is.</p>
			<textarea name="ImplicationsOfPoorCustomerService" style="width: 100%;" rows="7"><?php echo $answers->ImplicationsOfPoorCustomerService->__toString(); ?></textarea>
			<p><img class="pull-right" src="module_eportfolio/assets/images/wb4_img1.png" /></p>
		</div>
	</div>

	<?php if($wb->id != '' && $_SESSION['user']->type == User::TYPE_ASSESSOR && ($wb->wb_status == Workbook::STATUS_LEARNER_COMPLETED || $wb->wb_status == Workbook::STATUS_BEING_CHECKED || $wb->wb_status == Workbook::STATUS_IV_REJECTED)) {?>
	<div class="row well">
		<div class="col-sm-12">
			<div class="box box-success box-solid">
				<div class="box-header with-border"><h3 class="box-title">Assessor Feedback</h3></div>
				<div class="box-body assessorFeedback" <?php echo $feedback->ImplicationsOfPoorCustomerService->Status->__toString() == 'A'?'style="pointer-events:none;"':''; ?>>
					<div class="form-group">
						<label for="status_ImplicationsOfPoorCustomerService" class="col-sm-12 control-label">Status:</label>
						<div class="col-sm-12">
							<?php
							echo HTML::selectChosen('status_ImplicationsOfPoorCustomerService', $answer_status, $feedback->ImplicationsOfPoorCustomerService->Status->__toString() == 'A'?$feedback->ImplicationsOfPoorCustomerService->Status->__toString():'', false, true); ?>
						</div>
					</div>
					<div class="form-group">
						<label for="comments_ImplicationsOfPoorCustomerService" class="col-sm-12 control-label">Comments:</label>
						<div class="col-sm-12">
							<textarea name="comments_ImplicationsOfPoorCustomerService" rows="7" style="width: 100%;"><?php echo $feedback->ImplicationsOfPoorCustomerService->Comments->__toString(); ?></textarea>
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
		if($feedback->ImplicationsOfPoorCustomerService->Status->__toString() == 'NA')
			echo HTML::renderWorkbookIcons('ImplicationsOfPoorCustomerService', false, 'btn-danger');
		else
			echo HTML::renderWorkbookIcons('ImplicationsOfPoorCustomerService', false, 'btn-success');
		?>
	</div></div></div>

	<?php echo $wb->getPageBottomLine(); ?>
</div> <!--.page 7 ends-->

<h1>Customer experience</h1>
<div class="step-content">
	<?php echo $wb->getPageTopLine(); ?>

	<div class="row"><div class="col-sm-12"><div class="pull-right"><?php echo HTML::renderWorkbookIcons('', true); ?></div></div></div>

	<div class="row">
		<div class="col-sm-12">

			<p class="text-bold">First impressions</p>
		</div>
	</div>

	<div class="row">
		<div class="col-sm-12">
			<p><br>Next you will consider how your behaviour affects the impression that customers form from the service they are receiving and you will also look at how good communication can give a positive impression of both yourself and your store.</p>
			<p><br>Always put the customer first.  First impressions are very important.  Customers who come into your store see not only the products on sale but the people who serve them.  You need to be able to provide customers with the correct information and treat them pleasantly and politely.  If you deliver this then you will be providing excellent customer service and the customer will leave the store having a positive impression of both you and your store.</p>
			<p><br>Customers expect you to look �the part� and they always notice when someone is well groomed.  You should always be a good advertisement for your store and your organisation.</p>
			<p><br>The customer is the most important visitor to your store; they do not depend on you, you depend on them.  The customer is not an interruption to your work; they are the purpose of it.</p>
			<p><br>A smile and a greeting will really help your customers to feel welcome, but serving customers well goes much further than this.  Some customers will simply ask for any assistance that they might need, but many more will not.  There are lots of different ways in which these customers will signal for help.  It is important that you learn what the signals are so you can respond appropriately.</p>
			<p><img class="img-responsive" src="module_eportfolio/assets/images/wb3_pg8_img1.png" /></p>
			<p><img class="pull-right" src="module_eportfolio/assets/images/wb4_img1.png" /></p>
		</div>
	</div>

	<p><br></p>

	<div class="row"><div class="col-sm-12"><div class="pull-right"><?php echo HTML::renderWorkbookIcons('', true); ?></div></div></div>

	<?php echo $wb->getPageBottomLine(); ?>
</div> <!--.page 8 ends-->

<h1>Customer experience</h1>
<div class="step-content">
	<?php echo $wb->getPageTopLine(); ?>

	<div class="row"><div class="col-sm-12"><div class="pull-right">
		<?php
		if($feedback->HelpingOurCustomers->Status->__toString() == 'NA')
			echo HTML::renderWorkbookIcons('HelpingOurCustomers', false, 'btn-danger');
		else
			echo HTML::renderWorkbookIcons('HelpingOurCustomers', false, 'btn-success');
		?>
	</div></div></div>

	<div class="row">
		<div class="col-sm-12">

			<p class="text-bold">Helping our customers</p>
		</div>
	</div>

	<div class="row">
		<div class="col-sm-12">
			<p><br>Remember, customers are the number one priority at all times.  Whatever you are doing, whether you are putting out stock, tidying the shelves or setting up a promotion, you must always offer to help the customer before continuing the tasks.  Whilst you are completing your task you must be constantly aware of the customers around you.  You should always work tidily, effectively and efficiently.</p>
			<p><br>All of our customers are different and they need to be given the service that best suits them.  It is important that you identify their needs by establishing the facts and ensure they leave the store feeling good about their experience.  Customers will let you know when they need help; even if they don�t come and ask you, the signs that they want help are unmistakeable. When dealing with customers you just have to remember what it is like to be a customer yourself.</p>
		</div>
		<div class="col-sm-6">
			<p>The �I need help� signs include:</p>
			<ul style="margin-left: 15px; margin-bottom: 15px;">
				<li>Looking around</li>
				<li>Returning to the same product repeatedly</li>
				<li>Reading instructions on products</li>
				<li>Looking at their watches</li>
				<li>Making eye contact with you</li>
			</ul>
		</div>
		<div class="col-sm-6" <?php echo $feedback->HelpingOurCustomers->Status->__toString() == 'A'?'style="pointer-events:none;"':''; ?>>
			<p class="text-bold"><img src="module_eportfolio/assets/images/wb2_img1.png" /> &nbsp; Can you think of any others �I need help signs?</p>
			<textarea name="HelpingOurCustomers" style="width: 100%;" rows="7"><?php echo $answers->HelpingOurCustomers->__toString(); ?></textarea>
		</div>
		<div class="col-sm-12">
			<p>In order to deliver excellent customer service you will need to be able to communicate effectively with your customers in numerous different ways.  These will include:</p>
			<ul style="margin-left: 15px; margin-bottom: 15px;">
				<li>Face to face</li>
				<li>By telephone</li>
			</ul>
			<p>When communicating face to face with your customer the single biggest thing they will notice is your body language.  Body language rarely lies, so look out for what the body is telling you (and be aware of what your own body language is saying).</p>
			<p><br>Using the telephone is a daily activity for all of us and most people are quite casual when they do so.  Giving the customer a good impression means being polite, speaking clearly and listening carefully.  It gives a good impression to a caller if the telephone is answered promptly and clearly.  The procedure for answering the phone when it rings is:</p>
			<blockquote class="text-bold"><i>�Good morning, <?php echo ucfirst($wb->savers_or_sp); ?> (store name), Sue speaking, how may I help you?�</i></blockquote>
			<p>If you have to pass the query to another team member, explain this to the customer.  Speak to your colleague outlining the request or query so the customer doesn�t have to explain it all again.  If you have to call a customer, make sure you have all the relevant information beforehand.</p>
			<p><img class="img-responsive" src="module_eportfolio/assets/images/wb3_pg9_img1.png" /></p>
			<p><img class="pull-right" src="module_eportfolio/assets/images/wb4_img1.png" /></p>
		</div>
	</div>

	<?php if($wb->id != '' && $_SESSION['user']->type == User::TYPE_ASSESSOR && ($wb->wb_status == Workbook::STATUS_LEARNER_COMPLETED || $wb->wb_status == Workbook::STATUS_BEING_CHECKED || $wb->wb_status == Workbook::STATUS_IV_REJECTED)) {?>
	<div class="row well">
		<div class="col-sm-12">
			<div class="box box-success box-solid">
				<div class="box-header with-border"><h3 class="box-title">Assessor Feedback</h3></div>
				<div class="box-body assessorFeedback" <?php echo $feedback->HelpingOurCustomers->Status->__toString() == 'A'?'style="pointer-events:none;"':''; ?>>
					<div class="form-group">
						<label for="status_HelpingOurCustomers" class="col-sm-12 control-label">Status:</label>
						<div class="col-sm-12">
							<?php
							echo HTML::selectChosen('status_HelpingOurCustomers', $answer_status, $feedback->HelpingOurCustomers->Status->__toString() == 'A'?$feedback->HelpingOurCustomers->Status->__toString():'', false, true); ?>
						</div>
					</div>
					<div class="form-group">
						<label for="comments_HelpingOurCustomers" class="col-sm-12 control-label">Comments:</label>
						<div class="col-sm-12">
							<textarea name="comments_HelpingOurCustomers" rows="7" style="width: 100%;"><?php echo $feedback->HelpingOurCustomers->Comments->__toString(); ?></textarea>
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
		if($feedback->HelpingOurCustomers->Status->__toString() == 'NA')
			echo HTML::renderWorkbookIcons('HelpingOurCustomers', false, 'btn-danger');
		else
			echo HTML::renderWorkbookIcons('HelpingOurCustomers', false, 'btn-success');
		?>
	</div></div></div>

	<?php echo $wb->getPageBottomLine(); ?>
</div> <!--.page 9 ends-->

<h1>Customer experience</h1>
<div class="step-content">
	<?php echo $wb->getPageTopLine(); ?>

	<div class="row"><div class="col-sm-12"><div class="pull-right"><?php echo HTML::renderWorkbookIcons('', true); ?></div></div></div>

	<div class="row">
		<div class="col-sm-12">

			<p class="text-bold">Identifying customer needs and responding appropriately.</p>
		</div>
	</div>

	<div class="row">
		<div class="col-sm-12">
			<p><br>The correct way to identify customer needs and establish the facts is to use the correct questioning techniques. This is commonly called customer service language. This involves using questioning skills and the correct body language. </p>
			<p><br>Responding to the answers the customer gives us and the information we give to them is just as important and is a vital part in creating a customer focused experience.</p>
		</div>
		<div class="col-sm-6">
			<p><br>In this section we will look at:</p>
			<ul style="margin-left: 15px; margin-bottom: 15px;">
				<li>Questioning techniques</li>
				<li>Listening techniques</li>
				<li>Body language</li>
				<li>Clarifying information</li>
			</ul>
		</div>
		<div class="col-sm-6">
			<p><img class="img-responsive" src="module_eportfolio/assets/images/wb3_pg10_img1.png" /></p>
		</div>
		<div class="col-sm-12">
			<p class="text-bold">Open Questions</p>
			<p>These are questions that invite the customer to give a range of answers, not just a simple yes or no. This allows the customer to go into detail. Example of an open question: �How can I help you�? The key word in this sentence is HOW?  This gives the customer the opportunity to go into detail of exactly how you can help them.</p>
			<p class="text-bold">Other examples that open questions usually start with:</p>
			<p><img class="img-responsive" src="module_eportfolio/assets/images/wb3_pg10_img2.png" /></p>
		</div>
	</div>

	<p><br></p>

	<div class="row"><div class="col-sm-12"><div class="pull-right"><?php echo HTML::renderWorkbookIcons('', true); ?></div></div></div>

	<?php echo $wb->getPageBottomLine(); ?>
</div> <!--.page 10 ends-->

<h1>Customer experience</h1>
<div class="step-content">
	<?php echo $wb->getPageTopLine(); ?>

	<div class="row"><div class="col-sm-12"><div class="pull-right"><?php echo HTML::renderWorkbookIcons('', true); ?></div></div></div>



	<div class="row">
		<div class="col-sm-12">
			<p>It is important that anyone working in a customer focused environment can use open and closed questions with customers, and that they know when to use each type. Open questions are best used at the start of the retail selling process as they allow the customer to respond positively (building rapport with the staff member), and it also allows the member of staff to establish exactly what the customer needs. When determining what the customer needs it is important that open questions are also probing.</p>
			<p class="text-bold"><br>Probing Questions </p>
			<p>These are questions designed to find out a little bit more about what the customer wants from their purchase. Probing questions allow staff members to find out the facts, understand the needs, establish likes and dislikes and establish the customers� budget.</p>
			<p class="text-bold"><br>An example of a probing question:</p>
			<ul style="margin-left: 15px; margin-bottom: 15px;">
				<li>Which products have you used before? </li>
				<li>What did you, or didn�t you like about that?</li>
			</ul>
			<p class="text-bold"><br>Closed Questions </p>
			<p>These are questions that give you the opportunity to summarise what information you have been given and also to show the customer that you have fully understood this information. They are also used to steer the conversation to the required outcome. These are questions that invite a yes or a no response.</p>
			<p class="text-bold"><br>Examples of a closed questions are: </p>
			<ul style="margin-left: 15px; margin-bottom: 15px;">
				<li>Do you need a carrier bag? </li>
				<li>Would you like this product?</li>
			</ul>
			<p><img class="pull-right img-responsive" src="module_eportfolio/assets/images/wb3_pg11_img1.png" /></p>
			<p class="text-bold"><br>Listening Techniques</p>
			<p>Asking questions is just one part of the process of finding out what customers are looking for. If you do not listen carefully to what your customers tell you, you will miss out on important information that could help you to understand what customers require. </p>
			<p class="text-bold"><br>Active listening involves:</p>
			<ul style="margin-left: 15px; margin-bottom: 15px;">
				<li>Listening carefully to what your customers tell you</li>
				<li>Showing that you are listening</li>
				<li>Making eye contact</li>
				<li>Using suitable body language</li>
				<li>Asking more questions if required</li>
			</ul>
		</div>
	</div>

	<p><br></p>

	<div class="row"><div class="col-sm-12"><div class="pull-right"><?php echo HTML::renderWorkbookIcons('', true); ?></div></div></div>

	<?php echo $wb->getPageBottomLine(); ?>
</div> <!--.page 11 ends-->

<h1>Customer experience</h1>
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
			<p><br>Your body language, when you communicate face to face, is very important especially when dealing with customers� situations. Your body language should say the same as your words. </p>
			<p><br>The most effective way of putting customers at ease and gaining their trust is to communicate effectively and with the intent of doing your best to help them. When it comes to face-to-face communication, how effectively we build rapport is a key element of helping customers feel at ease.</p>
			<p><img class="pull-right img-responsive" src="module_eportfolio/assets/images/wb7_pg7_img1.png" /></p>
			<p><br>Effective body language is focused around a few key areas. The ability to maintain eye contact, adopting an �open stance� with your body position and nodding your head to demonstrate you understand the customer, are all key elements of body language and help to build rapport. </p>
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
		<p><img class="pull-right" src="module_eportfolio/assets/images/wb4_img1.png" /></p>
	</div>

	<p><br></p>

	<div class="row"><div class="col-sm-12"><div class="pull-right"><?php echo HTML::renderWorkbookIcons('', true); ?></div></div></div>

	<?php echo $wb->getPageBottomLine(); ?>
</div> <!--.page 12 ends-->

<h1>Customer experience</h1>
<div class="step-content">
	<?php echo $wb->getPageTopLine(); ?>

	<div class="row"><div class="col-sm-12"><div class="pull-right">
		<?php
		if($feedback->DealConflict->Status->__toString() == 'NA')
			echo HTML::renderWorkbookIcons('DealConflict', false, 'btn-danger');
		else
			echo HTML::renderWorkbookIcons('DealConflict', false, 'btn-success');
		?>
	</div></div></div>

	<div class="row">
		<div class="col-sm-12">

			<p class="text-bold">Dealing with customer conflict and challenge</p>
			<p><img class="pull-right img-responsive" src="module_eportfolio/assets/images/wb2_img2.png" /></p>
		</div>
	</div>

	<div class="row">
		<div class="col-sm-12">
			<p><img class="pull-right img-responsive" src="module_eportfolio/assets/images/wb3_pg13_img1.png" /></p>
			<p>There are a number of different ways that can help us to know if a customer is dissatisfied. The most common way a customer will demonstrate dissatisfaction is via their behaviour. This will display itself in a number of ways including body language, voice tone, volume and language. In all stores there will be times when customers will want to complain.  The reasons for the complaint will vary.  In most retail businesses complaints will fall into one of three categories: Customer service, products or systems and procedures.</p>

		</div>
	</div>

	<div class="row" <?php echo $feedback->DealConflict->Status->__toString() == 'A'?'style="pointer-events:none;"':''; ?>>
		<div class="col-sm-12" >
			<p class="text-bold"><img src="module_eportfolio/assets/images/wb2_img1.png" /> &nbsp;Identify different things that customers might complain about for each category by filling in the empty boxes.</p>
			<div class="row well">
				<div class="pull-left col-lg-6 col-md-8 col-sm-8 col-xs-8 text-center">
					<img class="img-responsive" src="module_eportfolio/assets/images/wb3_pg13_img2.png" />
				</div>
				<div class="pull-left col-lg-6 col-md-4 col-sm-4 col-xs-4 text-center">
					<div class="row">
						<div class="col-lg-2 col-md-2 col-sm-2 col-xs-2 text-center">
							<p><h4 class="text-bold">Q1</h4></p>
						</div>
						<div class="col-lg-10 col-md-10 col-sm-10 col-xs-10 text-center">
							<p><input type="text" name="Q1" value="<?php echo $answers->DealConflict->Complains->Q1->__toString(); ?>" style="width: 100%;" /></p>
						</div>
					</div>
					<div class="row">
						<div class="col-lg-2 col-md-2 col-sm-2 col-xs-2 text-center">
							<p><h4 class="text-bold">Q2</h4></p>
						</div>
						<div class="col-lg-10 col-md-10 col-sm-10 col-xs-10 text-center">
							<p><input type="text" name="Q2" value="<?php echo $answers->DealConflict->Complains->Q2->__toString(); ?>" style="width: 100%;" /></p>
						</div>
					</div>
					<div class="row">
						<div class="col-lg-2 col-md-2 col-sm-2 col-xs-2 text-center">
							<p><h4 class="text-bold">Q3</h4></p>
						</div>
						<div class="col-lg-10 col-md-10 col-sm-10 col-xs-10 text-center">
							<p><input type="text" name="Q3" value="<?php echo $answers->DealConflict->Complains->Q3->__toString(); ?>" style="width: 100%;" /></p>
						</div>
					</div>
					<div class="row">
						<div class="col-lg-2 col-md-2 col-sm-2 col-xs-2 text-center">
							<p><h4 class="text-bold">Q4</h4></p>
						</div>
						<div class="col-lg-10 col-md-10 col-sm-10 col-xs-10 text-center">
							<p><input type="text" name="Q4" value="<?php echo $answers->DealConflict->Complains->Q4->__toString(); ?>" style="width: 100%;" /></p>
						</div>
					</div>
					<div class="row">
						<div class="col-lg-2 col-md-2 col-sm-2 col-xs-2 text-center">
							<p><h4 class="text-bold">Q5</h4></p>
						</div>
						<div class="col-lg-10 col-md-10 col-sm-10 col-xs-10 text-center">
							<p><input type="text" name="Q5" value="<?php echo $answers->DealConflict->Complains->Q5->__toString(); ?>" style="width: 100%;" /></p>
						</div>
					</div>
					<div class="row">
						<div class="col-lg-2 col-md-2 col-sm-2 col-xs-2 text-center">
							<p><h4 class="text-bold">Q6</h4></p>
						</div>
						<div class="col-lg-10 col-md-10 col-sm-10 col-xs-10 text-center">
							<p><input type="text" name="Q6" value="<?php echo $answers->DealConflict->Complains->Q6->__toString(); ?>" style="width: 100%;" /></p>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>

	<div class="row">
		<div class="col-sm-12">
			<p><?php echo ucfirst($wb->savers_or_sp); ?> does have a policy for dealing with complaints:</p>
			<ul style="margin-left: 15px; margin-bottom: 15px;">
				<li>To prevent the store from losing customers</li>
				<li>To prevent the store from losing money</li>
			</ul>
			<p>If a customer comes to you with a complaint, you must deal with that person courteously and effectively. You need to establish the facts before you can solve the problem.  Complaints can be sorted out if you use the correct procedures and have the right attitude towards the person making the complaint.</p>
			<p>If you want to know how good a retailer�s customer service really is, examine the way it deals with customers� complaints.  When customers are unhappy and feel the need to make a complaint it is most important that you let them say what they want to say without interruption.</p>
			<p>Whilst customers are explaining their problems and making their complaints you will need to listen very carefully. Try to sort out the relevant information from the other things the customer tells you so that you get a good idea of the real problem.</p>
			<p>You may need to ask some questions to help you understand the situation better. You can use the same questioning techniques that you use to ask about customers� needs. When customers are answering your questions continue to listen carefully to their replies and collect as much additional information as possible.</p>
			<p><img class="pull-right" src="module_eportfolio/assets/images/wb3_pg14_img1.png" /></p>
			<p>Now that you understand the customers� complaints you will need to do something about them, but before you take any further action you should let the customers know that their concerns have been heard and understood.</p>
			<p class="text-bold">The best way to do this is to summarise customers� complaints back to them:</p>
			<ul style="margin-left: 15px; margin-bottom: 15px;">
				<li>If you have got it right, the customer will know you understand</li>
				<li>If you have misunderstood anything the customer can correct you</li>
			</ul>
			<p class="text-bold">What you do next will depend on factors such as:</p>
			<ul style="margin-left: 15px; margin-bottom: 15px;">
				<li>The nature of the complaint</li>
				<li>Your level of responsibility within the business</li>
			</ul>
			<p>Whatever the next step is going to be, you should explain to the customer what is going to happen. Customers will then know what is taking place and will feel that action is being taken, particularly if you have to hand over to someone else. It is important to know what your authority levels are and who to escalate them to. If you are passing customers� complaints on to another member of your team, make sure that all the information you have collected from the customer is passed on too. </p>
		</div>
	</div>

	<div class="row">
		<div class="col-sm-12">
			<p>Always treat complaints as opportunities for improvement. If a complaint is made because goods are faulty the customer is legally entitled to their money back, even without a receipt, providing the customer can show they bought the product from <?php echo ucfirst($wb->savers_or_sp); ?>.</p>
			<p>In some cases it may be that the customer has no legal right to a refund or an exchange.  In this case we have a policy which allows goods to be returned.  This decision is made by the manager to maintain goodwill and customer service.</p>
			<div class="bg-red text-bold" style="padding: 15px; margin-bottom: 15px;">
				<p>Did you know?</p>
				<ul style="margin-left: 15px; margin-bottom: 15px;">
					<li>The average unhappy customer will tell between 8 � 16 people</li>
					<li>If you make an effort to sort out a problem, between 82% - 95% of customers will return</li>
				</ul>
			</div>
			<p>Below are some tips that will help you serve the customer efficiently and professionally.  You should follow the tips to minimise the chance of the customer becoming difficult. If your customer is being difficult from the beginning you should still follow the advice below to keep your customer service levels high.</p>
			<ul style="margin-left: 15px; margin-bottom: 15px;">
				<li><span class="text-bold">Welcome</span> the customer, smile and give them your full attention</li>
				<li><span class="text-bold">Listen</span> carefully to what the customer is saying</li>
				<li><span class="text-bold">Ask questions</span> to establish the reasons for the complaint</li>
				<li><span class="text-bold">Explain</span> the next steps and /or customer options in a logical manner</li>
				<li><span class="text-bold">Provide</span> clear sign-posting or a resolution to meet customers� needs and manage expectations</li>
				<li><span class="text-bold">Deal</span> with customers conflict or challenge in line with organisational policies and procedures</li>
				<li><span class="text-bold">Resolve</span> customers challenge or conflict in line with organisational policies and procedures</li>
				<li><span class="text-bold">Keep</span> customers informed of progress while resolving issues</li>
				<li><span class="text-bold">Maintain</span> an accurate record of customer issues and progress to resolution if required</li>
				<li><span class="text-bold">If you are unable</span> to deal with the complaint, politely inform the customer that you will need to <span class="text-bold">call your manager</span> </li>
				<li><span class="text-bold">Be friendly</span> and polite at all times</li>
			</ul>
			<p><img class="pull-right" src="module_eportfolio/assets/images/wb2_img2.png" /></p>
		</div>
	</div>

	<div class="row" <?php echo $feedback->DealConflict->Status->__toString() == 'A'?'style="pointer-events:none;"':''; ?>>
		<div class="col-sm-12">
			<p class="text-bold"><img class="pull-left" src="module_eportfolio/assets/images/wb2_img1.png" /> &nbsp; Following the steps to resolving customer conflict and challenge, record two different occasions where you have dealt with customers. Record what you did at each stage and discuss with your assessor.</p>
			<table class="table table-bordered">
				<tr class="bg-pink"><th colspan="2"><h4 class="text-bold">Overview of customer conflict or challenge</h4> </th> </tr>
				<?php
				$example1 = $answers->DealConflict->Examples->Example1;
				$j = 0;
				foreach(WBCustomerExperience::getOverviewOfCustomerConflictQuestions() AS $i)
				{
					$key = 'Step'.++$j;
					echo '<tr><th style="width: 40%;">'.$i.'</th><td style="width: 60%;"><textarea name="Example1_'.$key.'" rows="3" style="width: 100%;">'.$example1->$key .'</textarea> </td></tr>';
				}
				?>
			</table>
			<p><hr></p>
			<table class="table table-bordered">
				<tr class="bg-pink"><th colspan="2"><h4 class="text-bold">Overview of customer conflict or challenge</h4> </th> </tr>
				<?php
				$example2 = $answers->DealConflict->Examples->Example2;
				$j = 0;
				foreach(WBCustomerExperience::getOverviewOfCustomerConflictQuestions() AS $i)
				{
					$key = 'Step'.++$j;
					echo '<tr><th style="width: 40%;">'.$i.'</th><td style="width: 60%;"><textarea name="Example2_'.$key.'" rows="3" style="width: 100%;">'.$example2->$key .'</textarea> </td></tr>';
				}
				?>
			</table>
		</div>
	</div>

	<?php if($wb->id != '' && $_SESSION['user']->type == User::TYPE_ASSESSOR && ($wb->wb_status == Workbook::STATUS_LEARNER_COMPLETED || $wb->wb_status == Workbook::STATUS_BEING_CHECKED || $wb->wb_status == Workbook::STATUS_IV_REJECTED)) {?>
	<div class="row well">
		<div class="col-sm-12">
			<div class="box box-success box-solid">
				<div class="box-header with-border"><h3 class="box-title">Assessor Feedback</h3></div>
				<div class="box-body assessorFeedback" <?php echo $feedback->DealConflict->Status->__toString() == 'A'?'style="pointer-events:none;"':''; ?>>
					<div class="form-group">
						<label for="status_DealConflict" class="col-sm-12 control-label">Status:</label>
						<div class="col-sm-12">
							<?php
							echo HTML::selectChosen('status_DealConflict', $answer_status, $feedback->DealConflict->Status->__toString() == 'A'?$feedback->DealConflict->Status->__toString():'', false, true); ?>
						</div>
					</div>
					<div class="form-group">
						<label for="comments_DealConflict" class="col-sm-12 control-label">Comments:</label>
						<div class="col-sm-12">
							<textarea name="comments_DealConflict" rows="7" style="width: 100%;"><?php echo $feedback->DealConflict->Comments->__toString(); ?></textarea>
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
		if($feedback->DealConflict->Status->__toString() == 'NA')
			echo HTML::renderWorkbookIcons('DealConflict', false, 'btn-danger');
		else
			echo HTML::renderWorkbookIcons('DealConflict', false, 'btn-success');
		?>
	</div></div></div>

	<?php echo $wb->getPageBottomLine(); ?>
</div> <!--.page 13 ends-->

<h1>Customer experience</h1>
<div class="step-content">
	<?php echo $wb->getPageTopLine(); ?>

	<div class="row"><div class="col-sm-12"><div class="pull-right"><?php echo HTML::renderWorkbookIcons('', true); ?></div></div></div>

	<div class="row">
		<div class="col-sm-12">

			<p class="text-bold">Lasting Impressions</p>
		</div>
	</div>

	<div class="row">
		<div class="col-sm-12">
			<p>It is very important to smile, be friendly and polite.  A genuine �Hi�, �Good morning� or �Goodbye� will leave a lasting positive impression with your customers. If you think a customer needs help, a �Hi� or another friendly greeting that you feel comfortable using is the best way to approach the customer.</p>
			<p>Also some customers are on their lunch break so don�t have the time to chat themselves. When dealing with customers always remember:</p>
			<ul style="margin-left: 15px; margin-bottom: 15px;">
				<li>Welcome customers with a smile and a friendly approach</li>
				<li>Take a real interest in each customer and know when they need help</li>
				<li>Have a professional, well-groomed image</li>
				<li>Recognise customers who are waiting for service</li>
				<li>Know as much as you can about the products and services you provide</li>
			</ul>
			<p>Ensuring you meet your customers� needs for information on the products and services you provide in store is essential.  It will ensure that you gain your customers� trust which will mean that they will return to your store and also tell their family and friends about the great customer service they received.</p>
			<p style="margin-top: 15px; margin-bottom: 15px;" class="text-bold">The importance of repeat business and ways to encourage customer loyalty in the retail industry</p>
			<ul style="margin-left: 15px; margin-bottom: 15px;">
				<li>Repeat business customers will recognise and trust the brand, this increases brand awareness and power and helps to build and expand the business reputation to other customers.</li>
				<li>Loyal customers tend to provide the business with a large part of its sales/profits. They are happy with the products offered and the standards of service they receive. The retail business will depend heavily on their loyal customers and must look after them if they wish to keep them.</li>
				<li>Customer loyalty is so vital that some retailers develop expensive loyalty schemes to try to ensure that customers will come to them when they need the products or services it offers. While these schemes are effective, a more significant factor to keep customers is excellent customer service.</li>
				<li>Another obvious yet surprisingly often overlooked reason repeat business is so important is that having a happy customer return means not having to spend money attracting a new one</li>
				<li>When these satisfied customers then refer others to the company, there are again no costs associated with getting the new customers!</li>
				<li>All a company really has to do to make that happen is to treat the customer right when they do business with them. The best way to get MORE business is to do GOOD business. It may not be easy, but it really is that simple.</li>
				<li>Building customer loyalty gives a high return on the time, effort and money invested in providing good customer service.</li>
			</ul>
		</div>
	</div>

	<p><br></p>

	<div class="row"><div class="col-sm-12"><div class="pull-right"><?php echo HTML::renderWorkbookIcons('', true); ?></div></div></div>

	<?php echo $wb->getPageBottomLine(); ?>
</div> <!--.page 17 ends-->

<h1>Customer experience</h1>
<div class="step-content">
	<?php echo $wb->getPageTopLine(); ?>

	<div class="row"><div class="col-sm-12"><div class="pull-right">
		<?php
		if($feedback->Tools->Status->__toString() == 'NA')
			echo HTML::renderWorkbookIcons('Tools', false, 'btn-danger');
		else
			echo HTML::renderWorkbookIcons('Tools', false, 'btn-success');
		?>
	</div></div></div>

	<div class="row">
		<div class="col-sm-12">

			<p class="text-bold">Measurement and evaluation tools</p>
		</div>
	</div>

	<div class="row">
		<div class="col-sm-12" <?php echo $feedback->Tools->Status->__toString() == 'A'?'style="pointer-events:none;"':''; ?>>
			<p>To continue delivering an excellent customer experience at all times we need to monitor service levels to determine what we are doing well and things we need to improve.  We do this by using measurement and evaluation tools.  Some of these are listed below:</p>
			<div class="pull-left" style="border: #0000cc dashed 3px; padding: 10px; margin: 5px;">
				<span class="text-bold">A Key Performance Indicator (KPI)</span><i> is a measurable value that demonstrates how effectively a company is achieving key business objectives.</i>
			</div>
			<p>Organisations use <span class="text-bold">KPIs</span> to evaluate their success. <span class="text-bold">KPIs</span> help us to measure how well companies, business units, projects or individuals are performing compared to their strategic goals and objectives.</p>
			<br>
			<p class="text-bold">Basic KPIs used by retailers to monitor customer service levels</p>
			<ol style="margin-left: 15px; margin-bottom: 15px;">
				<li><span class="text-bold">Sales</span> - annual turnover, customer transactions made, customer basket spend, footfall , conversion rates- all against last year�s figures and this year�s budget</li>
				<li><span class="text-bold">Service</span> � Complaints, compliments, mystery shop</li>
				<li><span class="text-bold">Loss prevention</span> � Shrinkage loss, (stock loss or cash loss)</li>
				<li><span class="text-bold">Operational</span> � availability, costs, staffing</li>
				<li><span class="text-bold">HR development</span> � training, coaching, staff turnover</li>
			</ol>
			<p>It is really important to monitor the KPIs mentioned to ensure you are performing as well as planned. Monitoring these can also ensure that you take any corrective action in a timely manner if you need to.E.G. Mystery shop report states team member not offering a Star buy.  Corrective action may be retraining so that everyone knows the process.</p>
			<p class="text-bold"><img class="pull-left" src="module_eportfolio/assets/images/wb2_img1.png" /> &nbsp; Select one of your stores KPIs. Explain how well the store is performing against target. &nbsp; <img class="pull-right" src="module_eportfolio/assets/images/wb2_img2.png" /></p>
			<textarea name="KPIStorePerformance" style="width: 100%;"><?php echo $answers->Tools->KPIStorePerformance->__toString(); ?></textarea>
			<p class="text-bold"><img class="pull-left" src="module_eportfolio/assets/images/wb2_img1.png" /> &nbsp; How does the above KPI performance affect your customer service levels? &nbsp; <img class="pull-right" src="module_eportfolio/assets/images/wb2_img2.png" /></p>
			<textarea name="KPIEffect" style="width: 100%;"><?php echo $answers->Tools->KPIEffect->__toString(); ?></textarea>
		</div>
	</div>

	<div class="row">
		<div class="col-sm-12">
			<p><span class="text-bold">Footfall</span> is a retail term which describes the number of people who pass through the store each day.
				In a shopping centre it is the amount of customers who enter that shopping centre per day/per week.
				Footfall is converted into those who have spent and those who have not. This helps us to identify a range of service requirements and opportunities.
			</p>
			<p><span class="text-bold">Customer conversion rates</span> are the number of people entering your stores (footfall) who go on to purchase goods. E.G.  100 people enter the store and 50 people purchase good= 1 in 2 or 50% conversion rates</p>
			<p>By following the <span class="text-bold">Customer Experience</span> training you have had and simply asking each customer if you can help them you will improve the sales figures and conversion rates in your store.</p>
			<p class="text-bold">Measuring Service</p>
			<p>One of the ways we measure how well our team are contributing to the overall customer experience and influencing sales and conversion rates is to complete observations on team members. These are called <span class="text-bold">Customer Experience Observations</span>. These are designed to ensure team members are following their customer experience training and allow management to give feedback, support and further training if required.</p>
			<p>We come into contact with our customers when working on the shop floor and while working on the till.
				In both instances we are expected to deliver exceptional service and do this in a number of ways. For example, when serving on the till we are expected to offer additional products/services. There are specific and different criteria we need to meet when interacting with customers on the tills and on the shop floor, therefore two types of observations, shop floor and tills.
			</p>
			<p>How well do you think you meet these criteria and convert a browsing customer into a buying customer?</p>
			<p class="text-bold"><img class="pull-left" src="module_eportfolio/assets/images/wb2_img1.png" /> &nbsp; Ask your Manager/Mentor to complete a <?php echo $wb->savers_or_sp == 'savers'?'Savers Standards':'Customer Experience';?> Observation of you on the shop floor and the till and give you the feedback. Complete the table below: &nbsp;</p>
			<table class="table" <?php echo $feedback->Tools->Status->__toString() == 'A'?'style="pointer-events:none;"':''; ?>>
				<tr><th>What criteria did you meet?</th><th>What criteria did you not meet?</th><th>What are you going to do differently/better next time?</th></tr>
				<?php
				$Observation = $answers->Tools->Observation;
				for($i = 1; $i <= 3; $i++)
				{
					$key = 'Set'.$i;
					echo '<tr>';
					echo '<td><textarea name="'.$key.'_CriteriaMet" style="width: 100%;">' . $Observation->$key->CriteriaMet->__toString() .' </textarea></td>';
					echo '<td><textarea name="'.$key.'_CriteriaNotMet" style="width: 100%;">' . $Observation->$key->CriteriaNotMet->__toString() .' </textarea></td>';
					echo '<td><textarea name="'.$key.'_NextTime" style="width: 100%;">' . $Observation->$key->NextTime->__toString() .' </textarea></td>';
					echo '</tr>';
				}
				?>
			</table>
		</div>
	</div>

	<div class="row">
		<div class="col-sm-12">
			<p>One of the best ways to enable us to measure and evaluate our customer experience is from the customers themselves. This feedback is collected and communicated in a number of ways. Some of them are listed below:</p>
			<ul style="margin-left: 15px; margin-bottom: 15px;">
				<li>Feedback from customers on social media</li>
				<li>One to one feedback to team members while working in a store</li>
				<li>Complaints and compliments reported to our Head Office Customer Service Department</li>
				<li>E mail or written feedback internally from internal customers</li>
				<li>Mystery shopper visits</li>
			</ul>
			<p>All of this feedback is used to measure service levels and allow stores/company to take actions to improve service.</p>
			<p>Mystery shopper visits happen periodically in all stores over a year period.  A mystery shopper visits the store and looks at particular aspects of customer service in various areas. </p>
			<p>For example:</p>
			<ul style="margin-left: 15px; margin-bottom: 15px;">
				<li>The store team</li>
				<li>First impressions</li>
				<li>The enquiry</li>
				<li>Special offers and promotions</li>
			</ul>
			<p><img class="pull-right" src="module_eportfolio/assets/images/wb3_pg20_img1.png" /></p>
			<p>Scores are recorded on a report for each of the specific areas to be measured and an overall score given for the customer experience. Individual comments are also recorded on this report as feedback so that the store can see both strengths and areas for improvements.</p>
			<p>Stores compare the report to historical reports to measure performance and evaluate any actions taken to improve and maintain service standards. Actions that are taken from this report are applied to a store action plan which is then communicated to the team.</p>
			<p class="text-bold"><img class="pull-left" src="module_eportfolio/assets/images/wb2_img1.png" /> &nbsp; Locate your last two Mystery Shopper Reports. Complete the table below to compare scores and overall performance. &nbsp;</p>
			<div class="table-responsive" <?php echo $feedback->Tools->Status->__toString() == 'A'?'style="pointer-events:none;"':''; ?>>
				<?php
				$MSReportEvaluation = $answers->Tools->MSReportEvaluation;
				?>
				<table class="table">
					<caption><h4 class="text-center">Mystery Shopper Report Evaluation</h4> </caption>
					<tr><th>Service Area Measured</th><th>Latest Report %</th><th>Previous Report %</th><th>+ or � % </th></tr>
					<tr>
						<?php echo $wb->savers_or_sp == 'savers'?'<td>Tills<input type="hidden" name="MSReportEvaluation_Set1_Area" value="Tills" /></td>':'<td>First Impressions<input type="hidden" name="MSReportEvaluation_Set1_Area" value="First Impressions" /></td>'; ?>
						<td><input class="clsMSRLatest" type="text" name="MSReportEvaluation_Set1_Latest" size="5" maxlength="5" value="<?php echo $MSReportEvaluation->Set1->Latest->__toString(); ?>" /></td>
						<td><input class="clsMSRPrevious" type="text" name="MSReportEvaluation_Set1_Previous" size="5" maxlength="5" value="<?php echo $MSReportEvaluation->Set1->Previous->__toString(); ?>" /></td>
						<td><input class="clsMSRDifference" type="text" name="MSReportEvaluation_Set1_Difference" size="5" maxlength="5" value="<?php echo $MSReportEvaluation->Set1->Difference->__toString(); ?>" /></td>
					</tr>
					<tr>
						<?php echo $wb->savers_or_sp == 'savers'?'<td>Sell In<input type="hidden" name="MSReportEvaluation_Set2_Area" value="Sell In" /></td>':'<td>The Store Team<input type="hidden" name="MSReportEvaluation_Set2_Area" value="The Store Team" /></td>'; ?>
						<td><input class="clsMSRLatest" type="text" name="MSReportEvaluation_Set2_Latest" size="5" maxlength="5" value="<?php echo $MSReportEvaluation->Set2->Latest->__toString(); ?>" /></td>
						<td><input class="clsMSRPrevious" type="text" name="MSReportEvaluation_Set2_Previous" size="5" maxlength="5" value="<?php echo $MSReportEvaluation->Set2->Previous->__toString(); ?>" /></td>
						<td><input class="clsMSRDifference" type="text" name="MSReportEvaluation_Set2_Difference" size="5" maxlength="5" value="<?php echo $MSReportEvaluation->Set2->Difference->__toString(); ?>" /></td>
					</tr>
					<?php
					for($i = 3; $i <= 7; $i++)
					{
						$key = 'Set'.$i;
						echo '<tr>';
						echo '<td><input type="text" name="MSReportEvaluation_'.$key.'_Area" style="width: 100%;" value="' . $MSReportEvaluation->$key->Area->__toString() . '" /></td>';
						echo '<td><input class="clsMSRLatest" type="text" name="MSReportEvaluation_'.$key.'_Latest" size="5" maxlength="5" value="' . $MSReportEvaluation->$key->Latest->__toString() . '" /></td>';
						echo '<td><input class="clsMSRPrevious" type="text" name="MSReportEvaluation_'.$key.'_Previous" size="5" maxlength="5" value="' . $MSReportEvaluation->$key->Previous->__toString() . '" /></td>';
						echo '<td><input class="clsMSRDifference" type="text" name="MSReportEvaluation_'.$key.'_Difference" size="5" maxlength="5" value="' . $MSReportEvaluation->$key->Difference->__toString() . '" /></td>';
						echo '</tr>';
					}
					?>
					<tr><th>Totals</th><th id="lblTotalLatest">0</th><th id="lblTotalPrevious">0</th><th id="lblTotalDifference">0</th></tr>
				</table>
			</div>
			<img class="pull-right" src="module_eportfolio/assets/images/wb2_img2.png" />
		</div>
	</div>

	<div class="row">
		<div class="col-sm-12" <?php echo $feedback->Tools->Status->__toString() == 'A'?'style="pointer-events:none;"':''; ?>>
			<p class="text-bold"><img class="pull-left" src="module_eportfolio/assets/images/wb2_img1.png" /> &nbsp; Taking the information from the evaluation  table and the feedback given on the mystery shopper report discuss all of this with your manager and team what and discuss what needs to be improved/maintained and how you can do this. &nbsp;</p>
			<img class="pull-right" src="module_eportfolio/assets/images/wb3_pg21_img1.png" />
			<p>Agree these with your manager and fill in the action plan below:</p>
			<div class="table-responsive">
				<table class="table">
					<tr><th style="width: 20%;"> Service Area to be improved or maintained?</th><th style="width: 20%;">What needs to be achieved?</th><th style="width: 20%;">Implementation?</th><th style="width: 20%;">By whom?</th><th style="width: 20%;">By when?</th></tr>
					<?php if($wb->savers_or_sp != 'savers'){?>
					<tr><td>First Impressions</td><td>Stock out gaps need to be reduced</td><td>More pick lists and stock out counts completed on a regular basis</td><td>Management/Team Leaders</td><td>Ongoing to be evaluated on ...</td></tr>
					<?php } ?>
					<?php
					$ActionPlan = $answers->Tools->ActionPlan;
					for($i = 1; $i <= 5; $i++)
					{
						$key = 'Set'.$i;
						echo '<tr>';
						if($i == 1 && $wb->savers_or_sp == 'savers')
							echo $ActionPlan->$key->Area->__toString() == '' ? '<td><textarea name="ActionPlan_'.$key.'_Area" style="width: 100%;">E.G Tills</textarea></td>' : '<td><textarea name="ActionPlan_'.$key.'_Area" style="width: 100%;">' . $ActionPlan->$key->Area->__toString() . '</textarea></td>';
						else
							echo '<td><textarea name="ActionPlan_'.$key.'_Area" style="width: 100%;">' . $ActionPlan->$key->Area->__toString() . '</textarea></td>';
						echo '<td><textarea name="ActionPlan_'.$key.'_WhatToAchieve" style="width: 100%;">' . $ActionPlan->$key->WhatToAchieve->__toString() . '</textarea></td>';
						echo '<td><textarea name="ActionPlan_'.$key.'_Implementation" style="width: 100%;">' . $ActionPlan->$key->Implementation->__toString() . '</textarea></td>';
						echo '<td><textarea name="ActionPlan_'.$key.'_ByWhom" style="width: 100%;">' . $ActionPlan->$key->ByWhom->__toString() . '</textarea></td>';
						echo '<td><textarea name="ActionPlan_'.$key.'_ByWhen" style="width: 100%;">' . $ActionPlan->$key->ByWhen->__toString() . '</textarea></td>';
						echo '</tr>';
					}
					?>
				</table>
			</div>
			<p class="text-bold"><img class="pull-left" src="module_eportfolio/assets/images/wb2_img1.png" /> &nbsp; Revisit this action plan and evaluate its effectiveness.  Record your findings below: &nbsp;</p>
			<textarea name="ActionPlanRevision" rows="7" style="width: 100%;"><?php echo $answers->Tools->ActionPlanRevision->__toString(); ?></textarea>
			<img class="pull-right" src="module_eportfolio/assets/images/wb2_img2.png" />
		</div>
	</div>

	<div class="row">
		<div class="col-sm-12" <?php echo $feedback->Tools->Status->__toString() == 'A'?'style="pointer-events:none;"':''; ?>>
			<p>There are many other measurement and evaluation tools that are available to monitor customer service levels that are used in stores. Some of these tools are used for other purposes however double up as they also have a big effect on service.</p>
			<p>Some of these are mentioned below:</p>
			<img class="pull-right" src="module_eportfolio/assets/images/wb2_img2.png" />
			<ul style="margin-left: 15px; margin-bottom: 15px;">
				<li>Individual performance reviews</li>
				<li>Sales reports</li>
				<li>Star buy reports</li>
				<li>Area Manager visit book</li>
				<li>Audit reports</li>
				<li>Till performance reports</li>
				<li>Stock availability reports</li>
				<li>Event counts</li>
			</ul>
			<p class="text-bold"><img class="pull-left" src="module_eportfolio/assets/images/wb2_img1.png" /> &nbsp; Discuss these with your management and team.  Pick out at least two of the examples above and explain what these tell you and how these can be used to measure and evaluate customer service levels. &nbsp;</p>
			<textarea name="ToolsDiscussion" rows="7" style="width: 100%;"><?php echo $answers->Tools->ToolsDiscussion->__toString(); ?></textarea>
			<img class="pull-right" src="module_eportfolio/assets/images/wb3_pg22_img1.png" />
		</div>
	</div>

	<?php if($wb->id != '' && $_SESSION['user']->type == User::TYPE_ASSESSOR && ($wb->wb_status == Workbook::STATUS_LEARNER_COMPLETED || $wb->wb_status == Workbook::STATUS_BEING_CHECKED || $wb->wb_status == Workbook::STATUS_IV_REJECTED)) {?>
	<div class="row well">
		<div class="col-sm-12">
			<div class="box box-success box-solid">
				<div class="box-header with-border"><h3 class="box-title">Assessor Feedback</h3></div>
				<div class="box-body assessorFeedback" <?php echo $feedback->Tools->Status->__toString() == 'A'?'style="pointer-events:none;"':''; ?>>
					<div class="form-group">
						<label for="status_Tools" class="col-sm-12 control-label">Status:</label>
						<div class="col-sm-12">
							<?php
							echo HTML::selectChosen('status_Tools', $answer_status, $feedback->Tools->Status->__toString() == 'A'?$feedback->Tools->Status->__toString():'', false, true); ?>
						</div>
					</div>
					<div class="form-group">
						<label for="comments_Tools" class="col-sm-12 control-label">Comments:</label>
						<div class="col-sm-12">
							<textarea name="comments_Tools" rows="7" style="width: 100%;"><?php echo $feedback->Tools->Comments->__toString(); ?></textarea>
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
		if($feedback->Tools->Status->__toString() == 'NA')
			echo HTML::renderWorkbookIcons('Tools', false, 'btn-danger');
		else
			echo HTML::renderWorkbookIcons('Tools', false, 'btn-success');
		?>
	</div></div></div>

	<?php echo $wb->getPageBottomLine(); ?>
</div> <!--.page 18 ends-->

<h1>Customer experience</h1>
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
		<div class="col-sm-12" <?php echo $feedback->QualificationQuestions->Status->__toString() == 'A'?'style="pointer-events:none;"':''; ?>>
			<h4 class="text-bold">Unit 1: Understand the needs and expectations of customers</h4>
			<p><br>To achieve learning outcome 3 (Understand how to create a customer focused experience) answer the following questions in as much detail as you can.<br></p>
			<p class="text-bold">3.1 Explain how an understanding of the facts can be used to create a customer focused experience</p>
			<textarea name="Question1" rows="6" style="width: 100%; "><?php echo $answers->QualificationQuestions->Question1->__toString(); ?></textarea>
			<p class="text-bold">3.2 Explain how to build trust with customers and the importance of doing so</p>
			<textarea name="Question2" rows="6" style="width: 100%; "><?php echo $answers->QualificationQuestions->Question2->__toString(); ?></textarea>
			<p class="text-bold">3.3 Explain how to respond to customer needs and requirements positively</p>
			<textarea name="Question3" rows="6" style="width: 100%; "><?php echo $answers->QualificationQuestions->Question3->__toString(); ?></textarea>
			<h4 class="text-bold">Unit 3: Understanding the organisation</h4>
			<p><br>To achieve learning outcome 5 (Understand types of measurement and evaluation tools available to monitor customer service levels) answer the following question in as much detail as you can.<br></p>
			<p class="text-bold">5.1 Describe the measures and evaluation tools used in the organisation to monitor customer service levels.</p>
			<textarea name="Question4" rows="6" style="width: 100%; "><?php echo $answers->QualificationQuestions->Question4->__toString(); ?></textarea>
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
</div> <!--.page 23 ends-->

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
</div> <!--.page 11 ends-->

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

		$('.clsMSRLatest, .clsMSRPrevious').on('keydown', function (e) {
			-1 !== $.inArray(e.keyCode, [190,46, 8, 9, 27, 13]) || (/65|67|86|88/.test(e.keyCode) && (e.ctrlKey === true || e.metaKey === true)) && (!0 === e.ctrlKey || !0 === e.metaKey) || 35 <= e.keyCode && 40 >= e.keyCode || (e.shiftKey || 48 > e.keyCode || 57 < e.keyCode) && (96 > e.keyCode || 105 < e.keyCode) && e.preventDefault()
		});

		$('.clsMSRDifference').on('keydown', function (e) {
			-1 !== $.inArray(e.keyCode, [190,107,189,46, 8, 9, 27, 13]) || (/65|67|86|88/.test(e.keyCode) && (e.ctrlKey === true || e.metaKey === true)) && (!0 === e.ctrlKey || !0 === e.metaKey) || 35 <= e.keyCode && 40 >= e.keyCode || (e.shiftKey || 48 > e.keyCode || 57 < e.keyCode) && (96 > e.keyCode || 105 < e.keyCode) && e.preventDefault()
		});

	<?php if($disable_answers){?>

		$("#frm_wb_customer_experience :input").not(".assessorFeedback :input, #signature_text, #frm_wb_customer_experience :input[type=hidden], .btnViewSectionHistory, .btnViewAssessorComments, #iv_status, #iv_comments, #reopen_comments").prop("disabled", true);

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
							var myForm = document.forms['frm_wb_customer_experience'];
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
				var myForm = document.forms['frm_wb_customer_experience'];
				myForm.elements['full_save'].value = 'Y';
				return previewInputInformation();
				<?php } else {?>
				return window.history.back();
				<?php } ?>
			}

		});

		$('input[type=checkbox]').iCheck({
			checkboxClass: 'icheckbox_flat-green',
			radioClass: 'iradio_flat-green'
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

					var myForm = document.forms['frm_wb_customer_experience'];
					myForm.elements['full_save'].value = 'Y';
					myForm.elements['full_save_feedback'].value = 'Y';
					window.onbeforeunload = null;
					myForm.submit();

				},
				'Save And Come Back Later':function () {

					var myForm = document.forms['frm_wb_customer_experience'];
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
		$('#frm_wb_customer_experience :input[name=full_save]').val('N');
		$($('#frm_wb_customer_experience').serializeArray()).each(function(i, field)
		{
			document.forms["frm_wb_customer_experience"].elements[field.name].value = field.value.replace(/�/g, "GBP");
		});
		$.ajax({
			type:'POST',
			url:'do.php?_action=save_wb_customer_experience',
			data: $('#frm_wb_customer_experience').serialize(),
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

	$('.clsMSRLatest').keyup(function(){
		var total = 0;
		$('.clsMSRLatest').each(function(){
			if($.isNumeric($(this).val()))
				total += parseFloat($(this).val());
		});
		$('#lblTotalLatest').html(total.toFixed(2));
	});
	$('.clsMSRPrevious').keyup(function(){
		var total = 0;
		$('.clsMSRPrevious').each(function(){
			if($.isNumeric($(this).val()))
				total += parseFloat($(this).val());
		});
		$('#lblTotalPrevious').html(total.toFixed(2));
	});
	$('.clsMSRDifference').keyup(function(){
		var total = 0;
		$('.clsMSRDifference').each(function(){
			if($.isNumeric($(this).val()))
				total += parseFloat($(this).val());
		});
		$('#lblTotalDifference').html(total.toFixed(2));
	});

	$(function(){
		var totalLatest = 0;
		$('.clsMSRLatest').each(function(){
			if($.isNumeric($(this).val()))
				totalLatest += parseFloat($(this).val());
		});
		$('#lblTotalLatest').html(totalLatest.toFixed(2));
		var totalPrevious = 0;
		$('.clsMSRPrevious').each(function(){
			if($.isNumeric($(this).val()))
				totalPrevious += parseFloat($(this).val());
		});
		$('#lblTotalPrevious').html(totalPrevious.toFixed(2));
		var totalDifference = 0;
		$('.clsMSRDifference').each(function(){
			if($.isNumeric($(this).val()))
				totalDifference += parseFloat($(this).val());
		});
		$('#lblTotalDifference').html(totalDifference.toFixed(2));
	});

	autosize(document.querySelectorAll('textarea'));
</script>

</html>
