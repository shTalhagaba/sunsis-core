<?php /* @var $wb WBSalesPromotionMarchandising */ ?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/html" xmlns="http://www.w3.org/1999/html" xmlns="http://www.w3.org/1999/html"
      xmlns="http://www.w3.org/1999/html" xmlns="http://www.w3.org/1999/html" xmlns="http://www.w3.org/1999/html"
      xmlns="http://www.w3.org/1999/html" xmlns="http://www.w3.org/1999/html" xmlns="http://www.w3.org/1999/html">
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<title>Sales and Promotion & Merchandising workbook</title>
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

<form name="frm_wb_sales_promotion_marchandising" id="frm_wb_sales_promotion_marchandising" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
<input type="hidden" name="_action" value="save_wb_sales_promotion_marchandising"/>
<input type="hidden" name="id" value="<?php echo $wb->id; ?>"/>
<input type="hidden" name="tr_id" value="<?php echo $wb->tr_id; ?>"/>
<input type="hidden" name="wb_status" id="wb_status" value=""/>
<input type="hidden" name="full_save" id="full_save" value="<?php echo $wb->full_save; ?>"/>
<input type="hidden" name="full_save_feedback" id="full_save_feedback" value="<?php echo $wb->full_save_feedback; ?>"/>

<div class="container-float">
<div class="wrapper" style="background-color: #ffffff;">

<header class="main-header"><span style="margin-left: 50%;"><?php echo DAO::getSingleValue($link, "SELECT title FROM student_frameworks WHERE tr_id = '{$wb->tr_id}'"); ?></span></header>

<div class="content-wrapper" style="background-color: #ffffff;">

<?php echo $wb->savers_or_sp == 'savers' ? '<section class="content-header"><h1 style="color: #0000ff;">Sales and Promotion & Merchandising</h1></section>' : '<section class="content-header"><h1>Sales and Promotion & Merchandising</h1></section>' ?>

<section class="content">

<div id="wizard">

<h1>Sales and Promotion & Merchandising</h1>

<div class="step-content">
	<?php echo $wb->getPageTopLine(); ?>
	<div style="position: absolute; top: 40%; right: 50%;" class="lead">
		<?php echo $wb->savers_or_sp == 'savers' ? '<h2 class="text-bold" style="color: #0000ff;">Sales and Promotion & Merchandising</h2>' : '<h2 class="text-bold">Sales and Promotion & Merchandising</h2>' ?>
		<p class="text-center">Module</p>
	</div>

	<?php echo $wb->getPageBottomLine(); ?>
</div>
<!--.page 1 ends-->

<h1>Sales and Promotion & Merchandising</h1>

<div class="step-content">
	<?php echo $wb->getPageTopLine(); ?>
	<div class="row"><div class="col-sm-12"><div class="pull-right">
		<?php
		if($feedback->SalesTarget->Status->__toString() == 'NA')
			echo HTML::renderWorkbookIcons('SalesTarget', false, 'btn-danger');
		else
			echo HTML::renderWorkbookIcons('SalesTarget', false, 'btn-success');
		?>
	</div></div></div>

	<div class="row">
		<div class="col-sm-4 col-sm-offset-4">
			<div class="text-center" style="padding: 15px; border: #000000 solid 1px; background-color: #90ee90;">
				<h5 class="text-bold">Sales</h5>
				<p><i>The exchange of a commodity for money; the action of selling something</i></p>
			</div>
		</div>
	</div>

	<div class="row" style="margin-top:10px;">
		<div class="col-sm-4 col-sm-offset-4">
			<div class="text-center" style="padding: 15px; border: #000000 solid 1px; background-color: #90ee90;">
				<h5 class="text-bold">Promotion</h5>
				<p><i>The publicising of a product, organisation, or venture so as to increase sales or public awareness</i></p>
			</div>
		</div>
	</div>

	<div class="row" style="margin-top:10px;">
		<div class="col-sm-4 col-sm-offset-4">
			<div class="text-center" style="padding: 15px; border: #000000 solid 1px; background-color: #000000; color: #90ee90;">
				<h5 class="text-bold">Sales targets</h5>
				<p>A fixed amount of sales that a person or organisation wants to achieve</p>
			</div>
		</div>
	</div>

	<div class="row" style="margin-top: 10px;">
		<div class="col-sm-12">
			<p class="text-bold"><img src="module_eportfolio/assets/images/wb2_img1.png" /> &nbsp; Speak to your manager to get this week's sales targets for the following:</p>
		</div>
		<div class="col-sm-12" <?php echo $feedback->SalesTarget->Status->__toString() == 'A'?'style="pointer-events:none;"':''; ?>>
			<div class="table-responsive">
				<table class="table table-bordered">
					<tr><td>Total sales:</td><td><textarea name="SalesTargetTotal" style="width: 100%;"><?php echo $answers->SalesTarget->Total->__toString(); ?></textarea> </td></tr>
					<tr><td><?php echo $wb->savers_or_sp == 'savers' ? 'SAS' : 'Star Buys'; ?>:</td><td><textarea name="SalesTargetStarBuys" style="width: 100%;"><?php echo $answers->SalesTarget->StarBuys->__toString(); ?></textarea> </td></tr>
					<?php if($wb->savers_or_sp != 'savers'){ ?>
					<tr><td>Beauty cards:</td><td><textarea name="SalesTargetBeautyCards" style="width: 100%;"><?php echo $answers->SalesTarget->BeautyCards->__toString(); ?></textarea> </td></tr>
					<?php } else { ?>
					<input type="hidden" name="SalesTargetBeautyCards" value="" />
					<?php } ?>
				</table>
			</div>
			<p>What do you personally have to do to help the team achieve these targets?</p>
			<p style="border: #90ee90 solid 1px; padding: 5px;"><textarea name="SalesTargetYourHelp" style="width: 100%" rows="5"><?php echo $answers->SalesTarget->YourHelp->__toString(); ?></textarea> </p>
		</div>
	</div>

	<?php if($wb->id != '' && $_SESSION['user']->type == User::TYPE_ASSESSOR && ($wb->wb_status == Workbook::STATUS_LEARNER_COMPLETED || $wb->wb_status == Workbook::STATUS_BEING_CHECKED || $wb->wb_status == Workbook::STATUS_IV_REJECTED)) {?>
	<div class="row well">
		<div class="col-sm-12">
			<div class="box box-success box-solid">
				<div class="box-header with-border"><h3 class="box-title">Assessor Feedback</h3></div>
				<div class="box-body assessorFeedback" <?php echo $feedback->SalesTarget->Status->__toString() == 'A'?'style="pointer-events:none;"':''; ?>>
					<div class="form-group">
						<label for="status_SalesTarget" class="col-sm-12 control-label">Status:</label>
						<div class="col-sm-12">
							<?php
							echo HTML::selectChosen('status_SalesTarget', $answer_status, $feedback->SalesTarget->Status->__toString() == 'A'?$feedback->SalesTarget->Status->__toString():'', false, true); ?>
						</div>
					</div>
					<div class="form-group">
						<label for="comments_SalesTarget" class="col-sm-12 control-label">Comments:</label>
						<div class="col-sm-12">
							<textarea name="comments_SalesTarget" rows="7" style="width: 100%;"><?php echo $feedback->SalesTarget->Comments->__toString(); ?></textarea>
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
		if($feedback->SalesTarget->Status->__toString() == 'NA')
			echo HTML::renderWorkbookIcons('SalesTarget', false, 'btn-danger');
		else
			echo HTML::renderWorkbookIcons('SalesTarget', false, 'btn-success');
		?>
	</div></div></div>
	<?php echo $wb->getPageBottomLine(); ?>
</div>
<!--.page 2 ends-->

<h1>Sales and Promotion & Merchandising</h1>

<div class="step-content">
	<?php echo $wb->getPageTopLine(); ?>
	<div class="row"><div class="col-sm-12"><div class="pull-right">
		<?php
		if($feedback->SalesOpportunities->Status->__toString() == 'NA')
			echo HTML::renderWorkbookIcons('SalesOpportunities', false, 'btn-danger');
		else
			echo HTML::renderWorkbookIcons('SalesOpportunities', false, 'btn-success');
		?>
	</div></div></div>

	<div class="row">
		<div class="col-sm-12">
			<p class="text-bold">How sales targets differ according to the retail calendar</p>
			<p>Having worked in retail now for a few months you will have seen many different promotions during your time in store. Have you noticed anything different about the sales targets you have been given in store or personally? What about the amount of stock you receive for the promotions? Do you get more at some times than others?</p>
			<p class="text-bold"><img src="module_eportfolio/assets/images/wb2_img1.png" /> &nbsp; List below some key sales opportunities in the retail calendar that will affect you </p>
			<p>Also think about dates which may not affect you in <?php echo $wb->savers_or_sp == 'savers' ? 'Savers' : 'Superdrug'; ?> but might impact other retail businesses.</p>
			<p>Alternatively there might be some dates which are only relevant to you - there could be an event on in your town so you know it will be busier because of that - head office may know this and therefore increase your targets. All of these dates and events are sales opportunities for you, your store and the business</p>
		</div>
		<div class="col-sm-12">
			<div class="table-responsive">
				<table class="table table-bordered" <?php echo $feedback->SalesOpportunities->Status->__toString() == 'A'?'style="pointer-events:none;"':''; ?>>
					<tr><th>Month</th><th>Key sales opportunities</th><th>Relevant products</th></tr>
					<?php
					$months = WBSalesPromotionMarchandising::getMonthsDDL();
					foreach($months AS $key => $value)
					{
						$_sopp = $key .'SOpp';
						$_rprod = $key .'RProd';
						echo '<tr>';
						echo '<th>'.$value.'</th>';
						echo '<td><textarea name="'.$key.'SOpp" style="width: 100%;">'.$answers->SalesOpportunities->$_sopp->__toString().'</textarea> </td>';
						echo '<td><textarea name="'.$key.'RProd" style="width: 100%;">'.$answers->SalesOpportunities->$_rprod->__toString().'</textarea> </td>';
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
				<div class="box-body assessorFeedback" <?php echo $feedback->SalesOpportunities->Status->__toString() == 'A'?'style="pointer-events:none;"':''; ?>>
					<div class="form-group">
						<label for="status_SalesOpportunities" class="col-sm-12 control-label">Status:</label>
						<div class="col-sm-12">
							<?php
							echo HTML::selectChosen('status_SalesOpportunities', $answer_status, $feedback->SalesOpportunities->Status->__toString() == 'A'?$feedback->SalesOpportunities->Status->__toString():'', false, true); ?>
						</div>
					</div>
					<div class="form-group">
						<label for="comments_SalesOpportunities" class="col-sm-12 control-label">Comments:</label>
						<div class="col-sm-12">
							<textarea name="comments_SalesOpportunities" rows="7" style="width: 100%;"><?php echo $feedback->SalesOpportunities->Comments->__toString(); ?></textarea>
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
		if($feedback->SalesOpportunities->Status->__toString() == 'NA')
			echo HTML::renderWorkbookIcons('SalesOpportunities', false, 'btn-danger');
		else
			echo HTML::renderWorkbookIcons('SalesOpportunities', false, 'btn-success');
		?>
	</div></div></div>
	<?php echo $wb->getPageBottomLine(); ?>
</div>
<!--.page 3 ends-->

<h1>Sales and Promotion & Merchandising</h1>

<div class="step-content">
	<?php echo $wb->getPageTopLine(); ?>
	<div class="row"><div class="col-sm-12"><div class="pull-right">
		<?php
		if($feedback->SellingProcess->Status->__toString() == 'NA')
			echo HTML::renderWorkbookIcons('SellingProcess', false, 'btn-danger');
		else
			echo HTML::renderWorkbookIcons('SellingProcess', false, 'btn-success');
		?>
	</div></div></div>

	<div class="row">
		<div class="col-sm-12">
			<p class="text-bold">Different selling techniques and how and when to use them</p>
			<p>The selling process is a series of steps followed by a salesperson while selling a product. The selling process is a complete cycle which starts from identifying the customers to closing the deal with them.</p>
			<p>Below is a 5 step selling process. You may remember this from the Product and Service workbook. There are others which are similar perhaps with different wording and you may see some which have 7 steps. Overall their purpose is the same.</p>
		</div>
	</div>

	<div class="row">
		<div class="col-sm-12">
			<p class="text-bold"><img src="module_eportfolio/assets/images/wb2_img1.png" /> &nbsp; Fill in the gaps to complete the 5 steps of selling process</p>
		</div>
		<div class="col-sm-6">
			<img class="img-responsive" src="module_eportfolio/assets/images/wb_r10_pg4_img1.png" />
		</div>
		<div class="col-sm-6">
			<div class="table-responsive">
				<table class="table table-bordered" <?php echo $feedback->SellingProcess->Status->__toString() == 'A'?'style="pointer-events:none;"':''; ?>>
					<tr><th>A</th><td><textarea name="SellingProcessA" style="width: 100%;"><?php echo $answers->SellingProcess->A->__toString(); ?></textarea> </td></tr>
					<tr><th>B</th><td><textarea name="SellingProcessB" style="width: 100%;"><?php echo $answers->SellingProcess->B->__toString(); ?></textarea> </td></tr>
					<tr><th>C</th><td><textarea name="SellingProcessC" style="width: 100%;"><?php echo $answers->SellingProcess->C->__toString(); ?></textarea> </td></tr>
				</table>
			</div>
		</div>
		<div class="col-sm-12">
			<p>This selling process is a good starting point however to be able to sell a product or service to any customer you will also need to be able to do the following:</p>
		</div>
		<div class="col-sm-6">
			<p class="text-bold">Know your product</p>
			<p>The more you know about what you are selling to customers the better. Having comprehensive product knowledge will reassure them that you know what you are talking about and gain their trust. The customer wants to be able to ask questions and feel confident in the information they are being given. If you don't know your product the customer is likely to go elsewhere and find someone who does.</p>
			<img class="img-responsive" src="module_eportfolio/assets/images/wb_r10_pg4_img2.png" />
		</div>
		<div class="col-sm-6">
			<p class="text-bold">Listen to your customer and overcome objections</p>
			<p>From the start of the process you need to actively listen to the customers' questions, needs or objections. You need to take them seriously and address each one. If you spend the time listening and ask the right type of questions it will help you match a product to meet their needs quickly.</p>
			<p>In some cases you may get objections. By listening it will enable you to understand and deal with the situation. You won't always be able to overcome them however you may be able to offer a more suitable alternative instead.</p>
		</div>
	</div>

	<?php if($wb->id != '' && $_SESSION['user']->type == User::TYPE_ASSESSOR && ($wb->wb_status == Workbook::STATUS_LEARNER_COMPLETED || $wb->wb_status == Workbook::STATUS_BEING_CHECKED || $wb->wb_status == Workbook::STATUS_IV_REJECTED)) {?>
	<div class="row well">
		<div class="col-sm-12">
			<div class="box box-success box-solid">
				<div class="box-header with-border"><h3 class="box-title">Assessor Feedback</h3></div>
				<div class="box-body assessorFeedback" <?php echo $feedback->SellingProcess->Status->__toString() == 'A'?'style="pointer-events:none;"':''; ?>>
					<div class="form-group">
						<label for="status_SellingProcess" class="col-sm-12 control-label">Status:</label>
						<div class="col-sm-12">
							<?php
							echo HTML::selectChosen('status_SellingProcess', $answer_status, $feedback->SellingProcess->Status->__toString() == 'A'?$feedback->SellingProcess->Status->__toString():'', false, true); ?>
						</div>
					</div>
					<div class="form-group">
						<label for="comments_SellingProcess" class="col-sm-12 control-label">Comments:</label>
						<div class="col-sm-12">
							<textarea name="comments_SellingProcess" rows="7" style="width: 100%;"><?php echo $feedback->SellingProcess->Comments->__toString(); ?></textarea>
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
		if($feedback->SellingProcess->Status->__toString() == 'NA')
			echo HTML::renderWorkbookIcons('SellingProcess', false, 'btn-danger');
		else
			echo HTML::renderWorkbookIcons('SellingProcess', false, 'btn-success');
		?>
	</div></div></div>
	<?php echo $wb->getPageBottomLine(); ?>
</div>
<!--.page 4 ends-->

<h1>Sales and Promotion & Merchandising</h1>

<div class="step-content">
	<?php echo $wb->getPageTopLine(); ?>
	<div class="row"><div class="col-sm-12"><div class="pull-right">
		<?php
		if($feedback->OvercomingObjections->Status->__toString() == 'NA')
			echo HTML::renderWorkbookIcons('OvercomingObjections', false, 'btn-danger');
		else
			echo HTML::renderWorkbookIcons('OvercomingObjections', false, 'btn-success');
		?>
	</div></div></div>

	<div class="row">
		<div class="col-sm-12">
			<p>The types of question you ask your customer can also help you identify their needs.</p>
		</div>
		<div class="col-sm-6">
			<p class="text-bold">Closed questions</p>
			<p>A closed question leads a customer to only answer with a yes or no answer. If a closed question is used at the start of the selling process you may not get very far. E.g. Can I help you?</p>
			<img class="img-responsive pull-right" src="module_eportfolio/assets/images/wb_r10_pg5_img1.png" />
			<p>If it is however used later in the sales process it will help you to lead your customer to make a decision. E.g. do you like it in red?</p>
			<p class="text-bold">Open questions</p>
			<p>An open question will therefore do the opposite and help you to gain more information from your customer.</p>
			<img class="img-responsive pull-right" src="module_eportfolio/assets/images/wb_r10_pg5_img2.png" />
			<p>E.g. how can I help you? What are you looking for today? How many do you want?</p>
		</div>
		<div class="col-sm-6">
			<p class="text-bold">Probing questions</p>
			<p>Probing questions will help you to find out the detail of what the customer wants by digging a bit deeper.</p>
			<img class="img-responsive pull-right" src="module_eportfolio/assets/images/wb_r10_pg5_img3.png" />
			<p>E.g. how much do you want to spend? What is your maximum budget?</p>
			<p>By asking the right type of questions you will save time and get the information you need off your customer to go on to be able to match a product to their needs. If you don't ask the right type of questions you could find yourself with no better understanding of what the customer wants and both you and the customer could feel that their time has been wasted.</p>
		</div>
		<div class="col-sm-12">
			<p class="text-bold">Overcoming objections</p>
			<p>As previously mentioned, part of the selling process may be dealing with any objections that the customer may have. Objections don't need to be the end of the potential sale, just a hurdle for you as the sales person to get over. Objections could be about the price, colour, size or specification of the product. How you deal with it may determine if you are able to overcome it and close the sale.</p>
		</div>
		<div class="col-sm-12">
			<p class="text-bold"><img src="module_eportfolio/assets/images/wb2_img1.png" /> &nbsp; Please read the scenarios below. Fill in the gaps to show what actions you could take to overcome the objections and close the sale.</p>
			<div class="table-responsive">
				<table class="table table-bordered" <?php echo $feedback->OvercomingObjections->Status->__toString() == 'A'?'style="pointer-events:none;"':''; ?>>
					<tr><th style="width: 20%;">Scenario</th><th style="width: 20%;">Objection – what the customer says</th><th style="width: 40%;">What would you do / say next?</th></tr>
					<tr>
						<td>Customer is looking for a fragrance for their girlfriend and you show them 3 choices over £30</td>
						<td>Thanks but they're too expensive, I think I'll try somewhere else.</td>
						<td><textarea name="OvercomingObjectionsS1" style="width: 100%;"><?php echo $answers->OvercomingObjections->S1->__toString(); ?></textarea> </td>
					</tr>
					<tr>
						<td>Customer wants to buy a new hairdryer</td>
						<td>I want to be able to take it on holiday and this one seems really heavy.</td>
						<td><textarea name="OvercomingObjectionsS2" style="width: 100%;"><?php echo $answers->OvercomingObjections->S2->__toString(); ?></textarea> </td>
					</tr>
					<tr>
						<td>You actively sell a customer the face wipes which are on <?php echo $wb->savers_or_sp == 'savers' ? 'SAS' : 'Star Buys'; ?></td>
						<td>No thanks, I had an allergic reaction last time I used them.</td>
						<td><textarea name="OvercomingObjectionsS3" style="width: 100%;"><?php echo $answers->OvercomingObjections->S3->__toString(); ?></textarea> </td>
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
				<div class="box-body assessorFeedback" <?php echo $feedback->OvercomingObjections->Status->__toString() == 'A'?'style="pointer-events:none;"':''; ?>>
					<div class="form-group">
						<label for="status_OvercomingObjections" class="col-sm-12 control-label">Status:</label>
						<div class="col-sm-12">
							<?php
							echo HTML::selectChosen('status_OvercomingObjections', $answer_status, $feedback->OvercomingObjections->Status->__toString() == 'A'?$feedback->OvercomingObjections->Status->__toString():'', false, true); ?>
						</div>
					</div>
					<div class="form-group">
						<label for="comments_OvercomingObjections" class="col-sm-12 control-label">Comments:</label>
						<div class="col-sm-12">
							<textarea name="comments_OvercomingObjections" rows="7" style="width: 100%;"><?php echo $feedback->OvercomingObjections->Comments->__toString(); ?></textarea>
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
		if($feedback->OvercomingObjections->Status->__toString() == 'NA')
			echo HTML::renderWorkbookIcons('OvercomingObjections', false, 'btn-danger');
		else
			echo HTML::renderWorkbookIcons('OvercomingObjections', false, 'btn-success');
		?>
	</div></div></div>
	<?php echo $wb->getPageBottomLine(); ?>
</div>
<!--.page 5 ends-->

<h1>Sales and Promotion & Merchandising</h1>

<div class="step-content">
	<?php echo $wb->getPageTopLine(); ?>
	<div class="row"><div class="col-sm-12"><div class="pull-right">
		<?php
		if($feedback->Displays->Status->__toString() == 'NA')
			echo HTML::renderWorkbookIcons('Displays', false, 'btn-danger');
		else
			echo HTML::renderWorkbookIcons('Displays', false, 'btn-success');
		?>
	</div></div></div>

	<div class="row">
		<div class="col-sm-12">
			<div class="row">
				<div class="col-sm-4 col-sm-offset-4">
					<div class="text-center" style="padding: 15px; border: #0000ff solid 3px;">
						<p><span class="text-bold">Merchandising </span><i> is the process of presenting products for sale in a retail environment in ways that influence shoppers' buying decisions.</i></p>
					</div>
				</div>
			</div>
		</div>
	</div>

	<div class="row">
		<div class="col-sm-12">
			<p>Merchandising includes determining the best shelf location for each product, building eye-catching displays that attract potential buyers and using signage to provide pricing and other product information.</p>
			<p>Merchandising also involves the selection of the proper product mix to carry in the store.</p>
			<p>Special pricing and promotions are another part of the merchandising process.</p>
			<p>Sales occur when the customer actually selects the product and completes the purchasing transaction.</p>
			<p>In a retail environment, stores employ sales assistants to help customers to choose products and to process the sale.  In many stores sales assistants are the people who are involved in the merchandising of stock and therefore are fully aware of offers and promotions.</p>
			<p>Although sales and merchandising are two different functions, they are closely related.</p>
			<p>Effective merchandising leads to sales, even without the aid of a sales assistant, as it induces customers to make purchases. For example, a prominently displayed cosmetics stand with the latest styles and vibrant colours can entice customers to browse and possibly purchase the items.</p>
			<p>The technique of cross-merchandising, where two compatible items are displayed together, can lead to additional purchases.</p>
		</div>
	</div>

	<div class="row">
		<div class="col-sm-12">
			<p class="text-bold"><img src="module_eportfolio/assets/images/wb2_img1.png" /> &nbsp; Walk around your store and look at your displays. Select 5 products you would purchase and for each explain why.</p>
			<div class="table-responsive">
				<table class="table table-bordered" <?php echo $feedback->Displays->Status->__toString() == 'A'?'style="pointer-events:none;"':''; ?>>
					<tr><th>Display</th><th>Product</th><th>Why would you want to buy?</th></tr>
					<?php
					$Displays = $answers->Displays;
					for($i = 1; $i <= 5; $i++)
					{
						$set = 'Set'.$i;
						echo '<tr>';
						echo '<td><textarea name="Displays'.$set.'Dis" style="width: 100%;">'.$Displays->$set->Dis->__toString().'</textarea> </td>';
						echo '<td><textarea name="Displays'.$set.'Prod" style="width: 100%;">'.$Displays->$set->Prod->__toString().'</textarea> </td>';
						echo '<td><textarea name="Displays'.$set.'What" style="width: 100%;">'.$Displays->$set->What->__toString().'</textarea> </td>';
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
				<div class="box-body assessorFeedback" <?php echo $feedback->Displays->Status->__toString() == 'A'?'style="pointer-events:none;"':''; ?>>
					<div class="form-group">
						<label for="status_Displays" class="col-sm-12 control-label">Status:</label>
						<div class="col-sm-12">
							<?php
							echo HTML::selectChosen('status_Displays', $answer_status, $feedback->Displays->Status->__toString() == 'A'?$feedback->Displays->Status->__toString():'', false, true); ?>
						</div>
					</div>
					<div class="form-group">
						<label for="comments_Displays" class="col-sm-12 control-label">Comments:</label>
						<div class="col-sm-12">
							<textarea name="comments_Displays" rows="7" style="width: 100%;"><?php echo $feedback->Displays->Comments->__toString(); ?></textarea>
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
		if($feedback->Displays->Status->__toString() == 'NA')
			echo HTML::renderWorkbookIcons('Displays', false, 'btn-danger');
		else
			echo HTML::renderWorkbookIcons('Displays', false, 'btn-success');
		?>
	</div></div></div>
	<?php echo $wb->getPageBottomLine(); ?>
</div>
<!--.page 6 ends-->

<h1>Sales and Promotion & Merchandising</h1>

<div class="step-content">
	<?php echo $wb->getPageTopLine(); ?>
	<div class="row"><div class="col-sm-12"><div class="pull-right"><?php echo HTML::renderWorkbookIcons('', true); ?></div></div></div>

	<div class="row">
		<div class="col-sm-12">
			<p>While sales assistants in a retail environment may be required to perform sales and merchandising activities, there are some differences in the skills needed for each.</p>
			<ul style="margin-left: 15px;">
				<li>The sales function requires strong verbal presentation skills to persuade customers to make a purchase, as well as customer service skills.</li>
				<li>Merchandising typically requires more creative skills, such as the ability to come up with ideas for interesting displays and to make merchandise appear as attractive as possible.</li>
			</ul>
		</div>
		<div class="col-sm-6">
			<p class="text-bold"><br>Window displays</p>
			<p>Creating a window display is not a new concept.</p>
			<p>In fact using a shop window to display goods to entice customers in and is a method that has been used since trading began.</p>
			<p>Retailers used their shop windows to display what products they had to sell that their competitors didn't.</p>
			<p>Window displays also offered an insight into the quality of the business and/or products.</p>
			<p>Window displays also reflected the business identity and brand. For example a butcher's window would display sausages to show it was a butchers shop!</p>
		</div>
		<div class="col-sm-6">
			<img class="img-responsive " src="module_eportfolio/assets/images/wb_r10_pg7_img1.png" /><p><br></p>
			<img class="img-responsive " src="module_eportfolio/assets/images/wb_r10_pg7_img2.png" />
		</div>
		<div class="col-sm-12">
			<p class="text-bold">The concept of window displays is still true for today's customers.</p>
			<p>For example <?php echo $wb->savers_or_sp == 'savers' ? 'Savers' : 'Superdrug'; ?> have displays of health and beauty products or posters that are new and exciting letting the customers know they are shopping in a health and beauty store that has up to the minute new and exciting products.</p>
		</div>
		<div class="col-sm-6"><img class="img-responsive " src="module_eportfolio/assets/images/wb_r10_pg7_img3.png" /></div>
		<div class="col-sm-6"><img class="img-responsive " src="module_eportfolio/assets/images/wb_r10_pg7_img4.png" /></div>
	</div>

	<p><br></p>
	<div class="row"><div class="col-sm-12"><div class="pull-right"><?php echo HTML::renderWorkbookIcons('', true); ?></div></div></div>
	<?php echo $wb->getPageBottomLine(); ?>
</div>
<!--.page 7 ends-->

<h1>Sales and Promotion & Merchandising</h1>

<div class="step-content">
	<?php echo $wb->getPageTopLine(); ?>
	<div class="row"><div class="col-sm-12"><div class="pull-right">
		<?php
		if($feedback->Window->Status->__toString() == 'NA')
			echo HTML::renderWorkbookIcons('Window', false, 'btn-danger');
		else
			echo HTML::renderWorkbookIcons('Window', false, 'btn-success');
		?>
	</div></div></div>

	<div class="row">
		<div class="col-sm-12">
			<p class="text-bold"> How do window and store displays translate brand and business identity to customers? </p>
		</div>
		<div class="col-sm-6">
			<div class="text-center" style="padding: 15px; border: #000000 solid 2px;">
				<p><span class="text-bold">A brand identity </span><i>is a variation of elements (such as colors, design, logotype, name, and symbol) that together identify and distinguish the brand in the customers' mind.</i></p>
			</div>
		</div>
		<div class="col-sm-6">
			<div class="text-center" style="padding: 15px; border: #90ee90 solid 2px;">
				<p><span class="text-bold">A business identity </span><i>is the manner which a firm or business presents themselves to the public, such as customers as well as employees.</i></p>
			</div>
		</div>
		<div class="col-sm-12">
			<h4 class="text-bold">What's in your window?</h4>
			<p class="text-bold"><img src="module_eportfolio/assets/images/wb2_img1.png" /> &nbsp; Look at your store's window display and complete the questions below.</p>
		</div>
		<div class="col-sm-12 table-responsive" <?php echo $feedback->Window->Status->__toString() == 'A'?'style="pointer-events:none;"':''; ?>>
			<table class="table" >
				<?php $Window = $answers->Window; ?>
				<tr>
					<td style="width: 30%; vertical-align: middle; border: red solid 3px;"><h4 class="text-bold" >What's in your window?</h4> </td>
					<td style="width: 10%; vertical-align: middle;"><img class="img-responsive" src="module_eportfolio/assets/images/wb11_pg29_img1.png" /></td>
					<td style="width: 60%; vertical-align: middle;"><textarea name="WindowYourWindowWhat" style="width: 100%; "><?php echo $Window->Your->What->__toString(); ?></textarea> </td>
				</tr>
				<tr>
					<td style="width: 30%; vertical-align: middle;border: red solid 3px;">What do you think it is telling the customer about <?php echo $wb->savers_or_sp == 'savers' ? 'Savers' : 'Superdrug'; ?> and your store? Does it give a positive impression?</td>
					<td style="width: 10%; vertical-align: middle;"><img class="img-responsive" src="module_eportfolio/assets/images/wb11_pg29_img1.png" /></td>
					<td style="width: 60%; vertical-align: middle;"><textarea name="WindowYourWindowIsItGood" style="width: 100%; "><?php echo $Window->Your->IsItGood->__toString(); ?></textarea> </td>
				</tr>
			</table>
		</div>
		<div class="col-sm-12">
			<p class="text-bold"><img src="module_eportfolio/assets/images/wb2_img1.png" /> &nbsp; Next, look at other windows on the high street or shopping centre where you live.
				Pick a good example and a bad example of a window display and explain why you think this below.
			</p>
			<div class="table-responsive">
				<table class="table table-bordered" <?php echo $feedback->Window->Status->__toString() == 'A'?'style="pointer-events:none;"':''; ?>>
					<tr><th style="width: 15%">Store</th><th style="width: 30%">What's in the window?</th><th style="width: 10%">Good or bad example</th><th style="width: 45%">Why do you think this?</th></tr>
					<?php
					for($i = 1; $i <= 2; $i++)
					{
						$set = 'Set'.$i;
						echo '<tr>';
						echo '<td><textarea name="Competitors'.$set.'Store" style="width: 100%;">'.$Window->Competitors->$set->Store->__toString().'</textarea></td>';
						echo '<td><textarea name="Competitors'.$set.'What" style="width: 100%;">'.$Window->Competitors->$set->What->__toString().'</textarea></td>';
						echo '<td><textarea name="Competitors'.$set.'GoodOrBad" style="width: 100%;">'.$Window->Competitors->$set->GoodOrBad->__toString().'</textarea></td>';
						echo '<td><textarea name="Competitors'.$set.'Why" style="width: 100%;">'.$Window->Competitors->$set->Why->__toString().'</textarea></td>';
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
				<div class="box-body assessorFeedback" <?php echo $feedback->Window->Status->__toString() == 'A'?'style="pointer-events:none;"':''; ?>>
					<div class="form-group">
						<label for="status_Window" class="col-sm-12 control-label">Status:</label>
						<div class="col-sm-12">
							<?php
							echo HTML::selectChosen('status_Window', $answer_status, $feedback->Window->Status->__toString() == 'A'?$feedback->Window->Status->__toString():'', false, true); ?>
						</div>
					</div>
					<div class="form-group">
						<label for="comments_Window" class="col-sm-12 control-label">Comments:</label>
						<div class="col-sm-12">
							<textarea name="comments_Window" rows="7" style="width: 100%;"><?php echo $feedback->Window->Comments->__toString(); ?></textarea>
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
		if($feedback->Window->Status->__toString() == 'NA')
			echo HTML::renderWorkbookIcons('Window', false, 'btn-danger');
		else
			echo HTML::renderWorkbookIcons('Window', false, 'btn-success');
		?>
	</div></div></div>
	<?php echo $wb->getPageBottomLine(); ?>
</div>
<!--.page 8 ends-->

<h1>Sales and Promotion & Merchandising</h1>

<div class="step-content">
	<?php echo $wb->getPageTopLine(); ?>
	<div class="row"><div class="col-sm-12"><div class="pull-right">
		<?php
		if($feedback->Merchandising->Status->__toString() == 'NA')
			echo HTML::renderWorkbookIcons('Merchandising', false, 'btn-danger');
		else
			echo HTML::renderWorkbookIcons('Merchandising', false, 'btn-success');
		?>
	</div></div></div>

	<div class="row">
		<div class="col-sm-6">
			<p class="text-bold"> Store Displays</p>
			<p>Store displays are as important as window displays.</p>
			<p>Once your potential customer is in the store it is important that you display all your offers in the best possible light.</p>
			<p>It is then up to your sales assistants to engage potential customer by offering excellent customer service and supplying them with what they need and came in for.</p>
		</div>
		<div class="col-sm-6">
			<p>Displaying any deals or offers that your business may be running in the store is an excellent idea. Not only will this set you apart from your competitors it will encourage potential customers to make the most of what you are offering.</p>
			<p>It is important to remember that your displays will help to generate sales if they are set up correctly and then it is up to your sales assistants to use their skills to finalise the deal.</p>
		</div>
		<div class="col-sm-12" <?php echo $feedback->Merchandising->Status->__toString() == 'A'?'style="pointer-events:none;"':''; ?>>
			<p class="text-bold"><img src="module_eportfolio/assets/images/wb2_img1.png" /> &nbsp; Dual merchandising is the placing of the same product in two separate locations. It is important for a number of reasons. What do you think they are?</p>
			<textarea name="DualProducts" style="width: 100%;" rows="5"><?php echo $answers->Merchandising->DualProducts->__toString(); ?></textarea>
		</div>
		<div class="col-sm-12">
			<p class="text-bold">How you display stock is important but where you place the displays is also very important.</p>
			<p>All stores have areas that are 'hot', 'cold' and 'warm' spots. In the retail industry this means the particular areas that get high, medium and low amounts of attention from customers.</p>
			<p>Customer traffic flow determines where these hot, warm and cold spots are.</p>
			<p>Here are some useful points:</p>
			<ul style="margin-left: 15px;">
				<li>The store entrance is a hot spot that most stores take advantage of to catch the attention of passing traffic.</li>
				<li>If given a chance most customers turn left after entering a store, therefore the area to the left of the entrance is a hot spot.</li>
				<li>Cold spots are often at the back of a store, or in hidden or hard to get to corners.</li>
				<li>Basic lines, such as bread and newspapers in a supermarket are often placed in cold spots to force customers to go there.</li>
				<li>The point of sale area is a hot spot that many retailers take advantage of by displaying impulse items such as chocolate or snacks.</li>
				<li>The walkway between the store entrance and the point of sale area is a hot spot.</li>
				<li>In a fashion retailer warm spots include those areas around fitting rooms and mirrors.</li>
				<li>Department stores and supermarkets often arrange fixtures and display racks in a grid pattern to form aisles which direct traffic flow.</li>
				<li>The placement of fixtures and display racks should allow easy access to all parts of the store for customers and visitors, and should consider access for prams, wheelchairs and customers with vision impairment.</li>
			</ul>
		</div>
	</div>

	<div class="row">
		<div class="col-sm-12">
			<p class="text-bold"><img src="module_eportfolio/assets/images/wb2_img1.png" /> &nbsp; Where are the hot, medium and cold spots in your store and what is displayed in each?</p>
			<div class="table-responsive">
				<table class="table table-bordered" <?php echo $feedback->Merchandising->Status->__toString() == 'A'?'style="pointer-events:none;"':''; ?>>
					<tr><th style="background-color: #000000; width: 10%;"></th><th>Location in store</th><th>Products displayed</th></tr>
					<tr><th>Hot spot</th><th>Store entrance</th><th>New promotional items</th></tr>
					<tr><td colspan="3" style="background-color: #000000; text-align: center;"><img class="" src="module_eportfolio/assets/images/wb_r10_pg10_img3.png" /></td></tr>
					<tr>
						<th>Hot spot</th>
						<td><textarea name="MerchandisingSpotsH1" style="width: 100%;"><?php echo $answers->Merchandising->Spots->H1->__toString(); ?></textarea> </td>
						<td><textarea name="MerchandisingSpotsH2" style="width: 100%;"><?php echo $answers->Merchandising->Spots->H2->__toString(); ?></textarea> </td>
					</tr>
					<tr>
						<th>Medium spot</th>
						<td><textarea name="MerchandisingSpotsM1" style="width: 100%;"><?php echo $answers->Merchandising->Spots->M1->__toString(); ?></textarea> </td>
						<td><textarea name="MerchandisingSpotsM2" style="width: 100%;"><?php echo $answers->Merchandising->Spots->M2->__toString(); ?></textarea> </td>
					</tr>
					<tr>
						<th>Cold spot</th>
						<td><textarea name="MerchandisingSpotsC1" style="width: 100%;"><?php echo $answers->Merchandising->Spots->C1->__toString(); ?></textarea> </td>
						<td><textarea name="MerchandisingSpotsC2" style="width: 100%;"><?php echo $answers->Merchandising->Spots->C2->__toString(); ?></textarea> </td>
					</tr>
				</table>
			</div>
			<p class="text-bold">Product placement</p>
			<p>There are marketing strategies which you may not be aware of that also have an effect on customers buying habits. Have you ever considered how retailers decide where to place items on the shelves and more importantly, why they place them where they do?</p>
			<p>When you see items on a shelf, you are actually looking at a planogram. A planogram is defined as a “diagram or model that indicates the placement of retail products on shelves in order to maximise sales”.</p>
			<div style="margin: 15px;"> <img style="margin: 15px;" class="img-responsive pull-left" src="module_eportfolio/assets/images/wb_r10_pg10_img2.png" /></div>
			<p>Within these planograms, one phrase commonly used is “eye level is buy level”, indicating that products positioned at eye level are likely to sell better. You may find that the more expensive options are at eye level or just below, while the store's own brands are placed higher or lower on the shelves.</p>
			<p>The “number of facings”, how many items of a product you can see, also has an effect on sales. The more visible a product, the higher the sales are likely to be. The location of goods in an aisle is also important.</p>
			<p>There is a school of thought that goods placed at the start of an aisle do not sell as well. A customer needs time to adjust to being in the aisle, so it takes a little time before they can decide what to buy.</p>
		</div>
	</div>
	<div class="row">
		<div class="col-sm-12" <?php echo $feedback->Merchandising->Status->__toString() == 'A'?'style="pointer-events:none;"':''; ?>>
			<img class="pull-right img-responsive" src="module_eportfolio/assets/images/wb_r10_pg11_img1.png" />
			<p>You might think that designing a good planogram is about putting similar goods together. Retailers however have found it makes sense to place some goods together even though they are not in the same category. Beer and crisps is an example that you may see in a supermarket. If you are buying beer, crisps seem like a good idea and convenience makes a purchase more likely.</p>
			<p>This idea of placing complementary goods together is a difficult problem. Beer and crisps might seem an easy choice but this could have an effect on the overall sales of crisps, especially if the space given to crisps in other parts of the store is reduced. And what do you do with peanuts; put them near the beer as well?</p>
			<img class="pull-right img-responsive" src="module_eportfolio/assets/images/wb_r10_pg11_img2.png" />
			<p>Retailers will also want customers to buy more expensive products – a process known as “upselling”. If you want to persuade the customer to buy the more expensive brand of moisturiser, how should you arrange the store? You still need to stock the cheaper options, for those that are really are on a budget. But for the customers that can afford it, you want them to choose the premium product. Getting that balance right is not easy.</p>
			<p class="text-bold"><img src="module_eportfolio/assets/images/wb2_img1.png" /> &nbsp; Choose a section in your store and look at the product placement. Describe what you see?</p>
			<p><textarea name="SectionPlacement" style="width: 100%;"><?php echo $answers->Merchandising->SectionPlacement->__toString(); ?></textarea> </p>
			<p class="text-bold"><img src="module_eportfolio/assets/images/wb2_img1.png" /> &nbsp; Next think about complementary products. Walk around your store and make notes of any items that are displayed to complement another product but can also be found elsewhere in their own section.</p>
			<p><textarea name="ComplProds" style="width: 100%;"><?php echo $answers->Merchandising->ComplProds->__toString(); ?></textarea> </p>
			<p class="text-center"><img src="module_eportfolio/assets/images/wb_r10_pg11_img3.png" /></p>
			<p class="text-bold"><img src="module_eportfolio/assets/images/wb2_img1.png" /> &nbsp; For your next task complete the exercise below. Ensure you discuss and agree it with your manager before you start</p>
			<ul style="margin-left: 15px;">
				<li>Set up a display in your store</li>
				<li>Maintain the display over a period of 2 weeks</li>
				<li>Evaluate sales and get feedback from customers and colleagues</li>
				<li>Analyse feedback and sales – e.g. was it successful?</li>
				<li>Record what you did and your results below</li>
			</ul>
			<p><textarea name="Exercise" style="width: 100%;" rows="7"><?php echo $answers->Merchandising->Exercise->__toString(); ?></textarea> </p>
			<p class="text-bold"><img src="module_eportfolio/assets/images/wb2_img1.png" /> &nbsp; Finally, discuss the exercise with your assessor and identify anything you would do differently next time.</p>
			<p><textarea name="ExerciseNext" style="width: 100%;"><?php echo $answers->Merchandising->ExerciseNext->__toString(); ?></textarea> </p>
		</div>
	</div>

	<?php if($wb->id != '' && $_SESSION['user']->type == User::TYPE_ASSESSOR && ($wb->wb_status == Workbook::STATUS_LEARNER_COMPLETED || $wb->wb_status == Workbook::STATUS_BEING_CHECKED || $wb->wb_status == Workbook::STATUS_IV_REJECTED)) {?>
	<div class="row well">
		<div class="col-sm-12">
			<div class="box box-success box-solid">
				<div class="box-header with-border"><h3 class="box-title">Assessor Feedback</h3></div>
				<div class="box-body assessorFeedback" <?php echo $feedback->Merchandising->Status->__toString() == 'A'?'style="pointer-events:none;"':''; ?>>
					<div class="form-group">
						<label for="status_Merchandising" class="col-sm-12 control-label">Status:</label>
						<div class="col-sm-12">
							<?php
							echo HTML::selectChosen('status_Merchandising', $answer_status, $feedback->Merchandising->Status->__toString() == 'A'?$feedback->Merchandising->Status->__toString():'', false, true); ?>
						</div>
					</div>
					<div class="form-group">
						<label for="comments_Merchandising" class="col-sm-12 control-label">Comments:</label>
						<div class="col-sm-12">
							<textarea name="comments_Merchandising" rows="7" style="width: 100%;"><?php echo $feedback->Merchandising->Comments->__toString(); ?></textarea>
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
		if($feedback->Merchandising->Status->__toString() == 'NA')
			echo HTML::renderWorkbookIcons('Merchandising', false, 'btn-danger');
		else
			echo HTML::renderWorkbookIcons('Merchandising', false, 'btn-success');
		?>
	</div></div></div>
	<?php echo $wb->getPageBottomLine(); ?>
</div>
<!--.page 9 ends-->

<h1>Sales and Promotion & Merchandising</h1>

<div class="step-content">
	<?php echo $wb->getPageTopLine(); ?>
	<div class="row"><div class="col-sm-12"><div class="pull-right"><?php echo HTML::renderWorkbookIcons('', true); ?></div></div></div>

	<div class="row">
		<div class="col-sm-4 col-sm-offset-4">
			<div class="text-center" style="padding: 15px; border: #0000ff dashed 3px;">
				<p><span class="text-bold">Misrepresentation</span> <i>is a false statement of fact or law which induces the customer to enter a contract.</i></p>
			</div>
		</div>
	</div>

	<div class="row">
		<div class="col-sm-12">
			<p><br></p>
			<p class="text-bold">Misrepresentation when displaying goods can have a damaging affects not only customers but also on the business.</p>
			<p><br></p>
			<div class="bg-light-blue" style="padding: 15px;">
				<p class="text-bold text-center">Misrepresentation Act 1967</p>
				<p>The Misrepresentation Act exists to protect consumers from false or fraudulent claims that induce you into buying something or entering into a contract. It also allows you to claim damages.</p>
				<p class="text-bold">Types of misrepresentation</p>
				<p>A misrepresentation is a statement of fact (not opinion) which is made by a seller before a contract is made. If a consumer relied on that statement when deciding whether or not to go ahead with a purchase and this then turns out to be wrong, they may be able to claim compensation.</p>
				<p class="text-bold">Fraudulent misrepresentation</p>
				<p>A fraudulent misrepresentation occurs when someone makes a statement that</p>
				<ul style=" margin-left: 15px;">
					<li>They know is untrue, or,</li>
					<li>They make without believing it is true, or,</li>
					<li>They make recklessly (i.e. that person does not care about whether the statement is true or not).</li>
				</ul>
				<p>If a consumer enters into a contract as a result of a fraudulent misrepresentation, they can choose to unwind the contract, claim damages, or both.</p>
				<p class="text-bold">Negligent misrepresentation</p>
				<p>There is a negligent misrepresentation under the Misrepresentation Act 1967 where a statement is made carelessly or without reasonable grounds for believing its truth.</p>
				<p>When a claim for negligent misrepresentation under the Act is based on negligence, the law states that the person who made the misrepresentation has to disprove the negligence.</p>
				<p>In other words, they must prove that they had reasonable grounds to believe the statement and they believed the facts represented were true.</p>
				<p class="text-bold">Innocent misrepresentation</p>
				<p>This is where a person making a misrepresentation, when entering into a contract, had reasonable grounds for believing that his or her false statement was true.</p>
				<p>In other words, it is made entirely without fault. This type of misrepresentation primarily allows for the contract to be cancelled.</p>
				<p>However the court has discretion to award damages instead of allowing you to end the contract if it deems it appropriate. It cannot award both.</p>
			</div>
		</div>
	</div>

	<p><br></p>
	<div class="row"><div class="col-sm-12"><div class="pull-right"><?php echo HTML::renderWorkbookIcons('', true); ?></div></div></div>
	<?php echo $wb->getPageBottomLine(); ?>
</div>
<!--.page 10 ends-->

<h1>Sales and Promotion & Merchandising</h1>

<div class="step-content">
	<?php echo $wb->getPageTopLine(); ?>
	<div class="row"><div class="col-sm-12"><div class="pull-right"><?php echo HTML::renderWorkbookIcons('', true); ?></div></div></div>

	<div class="row">
		<div class="col-sm-12">
			<div class="bg-pink" style="padding: 15px;">
				<p class="text-bold text-center">Misrepresentation Act 1967</p>
				<p>Once it has been established that there has been a misrepresentation and what type it is, the remedies available can be determined.</p>
				<p>There are two types of remedy:
				<ul style=" margin-left: 15px;">
					<li>Damages: Financial compensation designed to compensate the victim of a misrepresentation for the harm done insofar as money can do this.</li>
					<li>Unwinding a contract: The ability to end a contract and the parties are treated as though the contract never existed</li>
				</ul>
				<p>The availability of the different remedies is mostly determined by the type of misrepresentation and the stage the contract has reached when the victim discovers the misrepresentation.</p>
				<p>This would be judged on both the nature of the innocent misrepresentation and the losses suffered by the victim of the misrepresentation.</p>
				<p class="text-bold">Limitations of a misrepresentation</p>
				<p>There are certain limitations on the right to unwinding a contract.</p>
				<p>For example, if a person is aware of a misrepresentation but chooses to continue with the contract (either in writing or through conduct), they will not then be able to go back to the person who made the misrepresentation and end the contract, or indeed go to court and ask them to unwind the contract if they change their mind later.</p>
				<p>In law, you would be taken to have “affirmed” the contract.</p>
				<p>An example would be, if you purchased a car on the basis of a misrepresentation as to the number of owners and then, after discovering the truth, you nevertheless continued to use it.</p>
				<p>You may find that the court would say that by doing so, you had affirmed the contract.</p>
				<p>In other words you could not later go back to the seller to end the contract, asking him to take back the car.</p>
			</div>
			<p><br></p>
			<p class="text-bold">Other Acts</p>
			<p>There is more cover with more recent laws, so you should also see <span class="text-bold">Consumer Protection from Unfair Trading Regulations 2008 (updated 2014).</span></p>
			<p>Consumers are also covered by the <span class="text-bold">Consumer Rights Act 2015</span> if the item does not match the description, is not fit for purpose or of satisfactory quality</p>
		</div>
	</div>

	<p><br></p>
	<div class="row"><div class="col-sm-12"><div class="pull-right"><?php echo HTML::renderWorkbookIcons('', true); ?></div></div></div>
	<?php echo $wb->getPageBottomLine(); ?>
</div>
<!--.page 11 ends-->

<h1>Sales and Promotion & Merchandising</h1>

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
			<p class="callout callout-info text-bold">Now you have completed the section on Sales and Promotion and Merchandising answer the following questions:</p>
			<p class="text-bold">Unit 4 - 1.1 Identify the sales opportunities that exist across the year</p>
			<textarea name="Question1" rows="7" style="width: 100%;"><?php echo $QualificationQuestions->Question1->__toString(); ?></textarea>
			<p><br></p>
			<p class="text-bold">Unit 4 - 1.2 Identify the seasonal products in your business</p>
			<textarea name="Question2" rows="7" style="width: 100%;"><?php echo $QualificationQuestions->Question2->__toString(); ?></textarea>
			<p><br></p>
			<p class="text-bold">Unit 4 - 1.3 Explain why understanding customers buying habits at different times of the year is important</p>
			<textarea name="Question3" rows="7" style="width: 100%;"><?php echo $QualificationQuestions->Question3->__toString(); ?></textarea>
			<p><br></p>
			<p class="text-bold">Unit 4 - 2.1 Explain how to optimise sales through effective product placement</p>
			<textarea name="Question4" rows="7" style="width: 100%;"><?php echo $QualificationQuestions->Question4->__toString(); ?></textarea>
			<p><br></p>
			<p class="text-bold">Unit 4 - 2.2 Identify what is meant by the term 'hot spot'</p>
			<textarea name="Question5" rows="7" style="width: 100%;"><?php echo $QualificationQuestions->Question5->__toString(); ?></textarea>
			<p><br></p>
			<p class="text-bold">Unit 4 - 2.3 Describe how to increase sales through product placement by utilising 'hot spots'.</p>
			<textarea name="Question6" rows="7" style="width: 100%;"><?php echo $QualificationQuestions->Question6->__toString(); ?></textarea>
			<p><br></p>
			<p class="text-bold">Unit 4 - 2.4 Explain the relationship between sales and space</p>
			<textarea name="Question7" rows="7" style="width: 100%;"><?php echo $QualificationQuestions->Question7->__toString(); ?></textarea>
			<p><br></p>
			<p class="text-bold">Unit 4 - 2.5 Describe how to increase customer spend through associated products and services</p>
			<textarea name="Question8" rows="7" style="width: 100%;"><?php echo $QualificationQuestions->Question8->__toString(); ?></textarea>
			<p><br></p>
			<p class="text-bold">Unit 4 - 2.6 Identify how to ensure product displays remain attractive, appealing and safe to customers</p>
			<textarea name="Question9" rows="7" style="width: 100%;"><?php echo $QualificationQuestions->Question9->__toString(); ?></textarea>
			<p><br></p>
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
<!--.page 12 ends-->

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

	$(function () {

	<?php if ($disable_answers) { ?>

		$("#frm_wb_sales_promotion_marchandising :input").not(".assessorFeedback :input, #signature_text, #frm_wb_sales_promotion_marchandising :input[type=hidden], .btnViewSectionHistory, .btnViewAssessorComments, #iv_status, #iv_comments, #reopen_comments").prop("disabled", true);

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
							var myForm = document.forms['frm_wb_sales_promotion_marchandising'];
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
				var myForm = document.forms['frm_wb_sales_promotion_marchandising'];
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

					var myForm = document.forms['frm_wb_sales_promotion_marchandising'];
					myForm.elements['full_save'].value = 'Y';
					myForm.elements['full_save_feedback'].value = 'Y';
					window.onbeforeunload = null;
					myForm.submit();

				},
				'Save And Come Back Later':function () {

					var myForm = document.forms['frm_wb_sales_promotion_marchandising'];
					myForm.elements['full_save'].value = 'Y';
					myForm.elements['full_save_feedback'].value = 'N';
					window.onbeforeunload = null;
					myForm.submit();

				}
			}
		});
	});

	function partialSave() {
		$('#frm_wb_sales_promotion_marchandising :input[name=full_save]').val('N');
		$($('#frm_wb_sales_promotion_marchandising').serializeArray()).each(function(i, field)
		{
			document.forms["frm_wb_sales_promotion_marchandising"].elements[field.name].value = field.value.replace(/£/g, "GBP");
		});
		$.ajax({
			type:'POST',
			url:'do.php?_action=save_wb_sales_promotion_marchandising',
			data:$('#frm_wb_sales_promotion_marchandising').serialize(),
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
