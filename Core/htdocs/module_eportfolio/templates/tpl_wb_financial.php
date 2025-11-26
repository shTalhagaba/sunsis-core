<?php /* @var $wb WBEnvironment */ ?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/html" xmlns="http://www.w3.org/1999/html" xmlns="http://www.w3.org/1999/html"
      xmlns="http://www.w3.org/1999/html" xmlns="http://www.w3.org/1999/html">
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<title>Financial workbook</title>
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

<form name="frm_wb_financial" id="frm_wb_financial" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
	<input type="hidden" name="_action" value="save_wb_financial"/>
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

				<?php echo $wb->savers_or_sp == 'savers' ? '<section class="content-header"><h1 style="color: #0000ff;">Financial</h1></section>' : '<section class="content-header"><h1>Financial</h1></section>' ?>

				<section class="content">

					<div id="wizard">

						<h1>Financial</h1>

						<div class="step-content">
							<?php echo $wb->getPageTopLine(); ?>
							<div style="position: absolute; top: 40%; right: 50%;" class="lead">
								<?php echo $wb->savers_or_sp == 'savers' ? '<h2 class="text-bold" style="color: #0000ff;">Financial</h2>' : '<h2 class="text-bold">Financial</h2>' ?>
								<p class="text-center">Module</p>
							</div>
							<?php echo $wb->getPageBottomLine(); ?>
						</div>
						<!--.page 1 ends-->

						<h1>Financial</h1>

						<div class="step-content">
							<?php echo $wb->getPageTopLine(); ?>
							<div class="row"><div class="col-sm-12"><div class="pull-right"><?php echo HTML::renderWorkbookIcons('', true); ?></div></div></div>
							<blockquote class="text-center" style="border: 3px solid #0000ff">
								<p><b>Financial</b> <i>usually refers to money matters or transactions of some size or importance</i></p>
							</blockquote>
							<blockquote class="text-center" style="border: 3px dashed #0000ff">
								<p><b>Commercial awareness</b> <i>is the ability to understand what makes a business or organisation successful, through either buying or selling products or supplying services to a market.</i></p>
							</blockquote>
							<div class="row">
								<div class="col-sm-6">
									<p class="text-bold">Here are some of the general principles of operating commercially in a retail environment:</p>
									<ul style="margin-left: 15px;">
										<li>
											<p class="text-bold">Start with the end in mind.</p>
											<p>Think about your vision, mission and values and work towards them. Basically, what do you want to achieve?</p>
										</li>
										<li>
											<p class="text-bold">Know your market</p>
											<p>It is critical to know what your customer wants. Many organisations think they know what customers want but they have n	ot actually asked them.</p>
										</li>
										<li>
											<p class="text-bold">Manage your costs</p>
											<p>It is important to manage all of your business costs. These include things for example, stock, wages, stationary, time, marketing, rent, rates and power.</p>
										</li>
										<li>
											<p class="text-bold">Manage your waste/loss</p>
											<p>Think about wastage and loss. Reducing the amount of damages, out of code and internal and external theft</p>
										</li>
									</ul>
								</div>
								<div class="col-sm-6">
									<ul style="margin-left: 15px;">
										<li>
											<p class="text-bold">Sales</p>
											<p>Know your sales targets and work towards achieving them. Remember, if you do not meet your sales target you may need to cut the cost of something else to meet your goals.</p>
										</li>
										<li>
											<p class="text-bold">Profit</p>
											<p>Work towards the end goal which is of course profit. A business needs to make profit to continue operating</p>
										</li>
									</ul>
									<img class="img-responsive pull-right" src="module_eportfolio/assets/images/wb_r12_pg2_img1.png" />
								</div>
							</div>
							<p><br></p>
							<div class="row"><div class="col-sm-12"><div class="pull-right"><?php echo HTML::renderWorkbookIcons('', true); ?></div></div></div>
							<?php echo $wb->getPageBottomLine(); ?>
						</div>
						<!--.page 2 ends-->

						<h1>Financial</h1>

						<div class="step-content">
							<?php echo $wb->getPageTopLine(); ?>
							<div class="row"><div class="col-sm-12"><div class="pull-right">
								<?php
								if($feedback->Cost->Status->__toString() == 'NA')
									echo HTML::renderWorkbookIcons('Cost', false, 'btn-danger');
								else
									echo HTML::renderWorkbookIcons('Cost', false, 'btn-success');
								?>
							</div></div></div>
							<div class="row">
								<div class="col-md-4 col-md-offset-4"><div class="bg-light-blue text-bold" style="padding: 15px;">Cost - an amount that has to be paid or given up in order to get something</div></div>
							</div>
							<div class="row">
								<div class="col-md-12"><p class="text-center"><br><img src="module_eportfolio/assets/images/wb_r12_pg3_img1.png" /><br></p></div>
							</div>
							<blockquote class="text-center" style="border: 3px dashed #0000ff">
								<p>In <b>business, cost</b> <i>is usually a monetary valuation of (1) effort, (2) material, (3) resources, (4) time and utilities consumed, (5) risks incurred</i></p>
							</blockquote>
							<div class="row">
								<div class="col-md-12">
									<p>A business has many different costs, from paying for <b>stock and materials</b>, through to paying the <b>rent or the heating bill</b>. By careful classification of these costs a business can analyses its performance and make better-informed decisions</p>
									<p class="text-bold"><img class="pull-left" src="module_eportfolio/assets/images/wb2_img1.png" /> &nbsp; What costs does <?php echo ucfirst($wb->savers_or_sp); ?> have? Write your ideas on this page.</p>
									<p <?php echo $feedback->Cost->Status->__toString() == 'A'?'style="pointer-events:none;"':''; ?>><textarea rows="5" name="SuperdrugCost" style="width: 100%;"><?php echo $answers->Cost->SuperdrugCost->__toString(); ?></textarea></p>
									<p class="text-center"><img src="module_eportfolio/assets/images/wb_r12_pg3_img2.png" /></p>
									<p>So how can a different approach to just one process that does not affect customer service save business money? (An example would be turning off the lights in a room that is not used saving energy costs.)</p>
									<p class="text-bold"><img class="pull-left" src="module_eportfolio/assets/images/wb2_img1.png" /> &nbsp; Discuss costs with your colleagues and management team. Thinking about all of the costs that <?php echo ucfirst($wb->savers_or_sp); ?> has, come up with three cost saving ideas on the table below.</p>
									<div class="table-responsive">
										<table class="table table-bordered" <?php echo $feedback->Cost->Status->__toString() == 'A'?'style="pointer-events:none;"':''; ?>>
											<tr><th style="width: 30%;">Cost</th><th style="width: 70%;">Process</th></tr>
											<tr><td>E.G. Lighting</td><td>Turn off all lights in room when not in use</td></tr>
											<?php
											$CostSavingIdeas = $answers->Cost->CostSavingIdeas;
											for($i = 1; $i <= 3; $i++)
											{
												$key = 'Set'.$i;
												echo '<tr>';
												echo '<td><textarea name="CostSavingIdea'.$i.'" style="width: 100%;">'.$answers->Cost->CostSavingIdeas->$key->Idea->__toString().'</textarea> </td>';
												echo '<td><textarea name="CostSavingProcess'.$i.'" style="width: 100%;">'.$answers->Cost->CostSavingIdeas->$key->Process->__toString().'</textarea> </td>';
												echo '</tr>';
											}
											?>
										</table>
									</div>
									<p class="text-center"><img src="module_eportfolio/assets/images/wb_r12_pg4_img1.png" /></p>
								</div>
							</div>
							<?php if($wb->id != '' && $_SESSION['user']->type == User::TYPE_ASSESSOR && ($wb->wb_status == Workbook::STATUS_LEARNER_COMPLETED || $wb->wb_status == Workbook::STATUS_BEING_CHECKED || $wb->wb_status == Workbook::STATUS_IV_REJECTED)) {?>
							<div class="row well">
								<div class="col-sm-12">
									<div class="box box-success box-solid">
										<div class="box-header with-border"><h3 class="box-title">Assessor Feedback</h3></div>
										<div class="box-body assessorFeedback" <?php echo $feedback->Cost->Status->__toString() == 'A'?'style="pointer-events:none;"':''; ?>>
											<div class="form-group">
												<label for="status_Cost" class="col-sm-12 control-label">Status:</label>
												<div class="col-sm-12">
													<?php
													echo HTML::selectChosen('status_Cost', $answer_status, $feedback->Cost->Status->__toString() == 'A'?$feedback->Cost->Status->__toString():'', false, true); ?>
												</div>
											</div>
											<div class="form-group">
												<label for="comments_Cost" class="col-sm-12 control-label">Comments:</label>
												<div class="col-sm-12">
													<textarea name="comments_Cost" rows="7" style="width: 100%;"><?php echo $feedback->Cost->Comments->__toString(); ?></textarea>
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
								if($feedback->Cost->Status->__toString() == 'NA')
									echo HTML::renderWorkbookIcons('Cost', false, 'btn-danger');
								else
									echo HTML::renderWorkbookIcons('Cost', false, 'btn-success');
								?>
							</div></div></div>
							<?php echo $wb->getPageBottomLine(); ?>
						</div>
						<!--.page 3 ends-->

						<h1>Financial</h1>

						<div class="step-content">
							<?php echo $wb->getPageTopLine(); ?>
							<div class="row"><div class="col-sm-12"><div class="pull-right">
								<?php
								if($feedback->KPIs->Status->__toString() == 'NA')
									echo HTML::renderWorkbookIcons('KPIs', false, 'btn-danger');
								else
									echo HTML::renderWorkbookIcons('KPIs', false, 'btn-success');
								?>
							</div></div></div>
							<blockquote class="text-center" style="border: 3px dashed #0000ff">
								<p><b>A Key Performance Indicator (KPI)</b> <i>is usually a measurable value that demonstrates how effectively a company is achieving key business objectives.</i></p>
							</blockquote>
							<div class="row">
								<div class="col-sm-12">
									<p>Organisations use <b>KPIs</b> to evaluate their success.  KPIs help us to measure how well companies, business units, projects or individuals are performing compared to their strategic goals and objectives.</p>
									<p>Well-designed KPIs provide the vital instruments that give us a clear understanding of current levels of performance targets. Not all the retailers adopt the same kind of KPI to meet their organisational goals but having certain a KPI in an organisation has become mandatory for a retailer.</p>
								</div>
								<div class="col-sm-6">
									<p class="text-bold">Basic KPIs used by a retailer:</p>
									<ol style="margin-left: 15px;">
										<li><p><b>Sales</b> - annual turnover, transactions made, basket spend, footfall - all against last year's figures and this year's budget</p></li>
										<li><p><b>Loss prevention</b> - Shrinkage loss, (stock loss or cash loss)</p></li>
										<li><p><b>Operational</b> - availability, inventory integrity</p></li>
										<li><p><b>Salary</b></p></li>
										<li><p><b>Service</b> - Complaints that are made</p></li>
										<li><p><b>HR Department</b> - training, coaching, staff turnover</p></li>
										<li><p><b>Variable costs</b> - any expenses made at an additional cost are avoidable</p></li>
									</ol>
									<p class="text-bold">How does the KPI help increase your sales?</p>
									<p>Once the KPI is defined it gives a clear idea about the goals and the measure and finally what to do with them.  It gives a clear idea what is important in the organisation and for what they have to work for to achieve.</p>
									<p>The KPI can be used as the performance measurement tool. It helps in managing the performance of the organisation.</p>
								</div>
								<div class="col-sm-6">
									<p>A basic <b>KPI</b> might be <b>Sales</b> but this can then be broken down in to more detail such as:</p>
									<ul style="margin-left: 15px;">
										<li><p><b>Sales per hour</b> - identifies how much each sales person takes in an allocated amount of time</p></li>
										<li><p><b>Average Sale</b> - identifies the average selling price of a sales person.</p></li>
										<li><p><b>Items per sale</b> - determines how many items each customer purchases</p></li>
										<li><p><b>Conversion Rate</b> - shows how many visitors to the store became customers</p></li>
										<li><p><b>Wage to Sales Ratio</b> - gives a graph comparing the hourly wages of a sales person to hourly sales they have made. This KPI determine their performance level and how effective they are</p></li>
									</ul>
									<p class="text-center"><img src="module_eportfolio/assets/images/wb_r12_pg5_img1.png" /></p>
								</div>
								<div class="col-sm-12" <?php echo $feedback->KPIs->Status->__toString() == 'A'?'style="pointer-events:none;"':''; ?>>
									<p>It is really important to monitor the KPIs mentioned on the previous page to ensure you are performing as well as planned. Monitoring these can also ensure that you take any corrective action in a timely manner if you need to.</p>
									<p>More importantly, good customer service and effective sales assistants affect the company brand/image of the business and help to ensure the business is operating commercially.</p>
									<p class="text-bold"><img class="pull-left" src="module_eportfolio/assets/images/wb2_img1.png" /> &nbsp; Talk to you colleagues and management team and find out exactly what your KPIs are. List some of them below.</p>
									<textarea rows="5" name="ListOfKPIs" style="width: 100%;"><?php echo $answers->KPIs->ListOfKPIs->__toString(); ?></textarea>
									<p class="text-center"><img src="module_eportfolio/assets/images/wb_r12_pg3_img2.png" /></p>
									<p class="text-bold"><img class="pull-left" src="module_eportfolio/assets/images/wb2_img1.png" /> &nbsp; Select two of the KPIs above and speak to your management team on how your store is performing against these. Write your findings in the box below.</p>
									<textarea rows="5" name="StorePerformanceForKPIs" style="width: 100%;"><?php echo $answers->KPIs->StorePerformanceForKPIs->__toString(); ?></textarea>
								</div>
							</div>
							<?php if($wb->id != '' && $_SESSION['user']->type == User::TYPE_ASSESSOR && ($wb->wb_status == Workbook::STATUS_LEARNER_COMPLETED || $wb->wb_status == Workbook::STATUS_BEING_CHECKED || $wb->wb_status == Workbook::STATUS_IV_REJECTED)) {?>
							<div class="row well">
								<div class="col-sm-12">
									<div class="box box-success box-solid">
										<div class="box-header with-border"><h3 class="box-title">Assessor Feedback</h3></div>
										<div class="box-body assessorFeedback" <?php echo $feedback->KPIs->Status->__toString() == 'A'?'style="pointer-events:none;"':''; ?>>
											<div class="form-group">
												<label for="status_KPIs" class="col-sm-12 control-label">Status:</label>
												<div class="col-sm-12">
													<?php
													echo HTML::selectChosen('status_KPIs', $answer_status, $feedback->KPIs->Status->__toString() == 'A'?$feedback->KPIs->Status->__toString():'', false, true); ?>
												</div>
											</div>
											<div class="form-group">
												<label for="comments_KPIs" class="col-sm-12 control-label">Comments:</label>
												<div class="col-sm-12">
													<textarea name="comments_KPIs" rows="7" style="width: 100%;"><?php echo $feedback->KPIs->Comments->__toString(); ?></textarea>
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
								if($feedback->KPIs->Status->__toString() == 'NA')
									echo HTML::renderWorkbookIcons('KPIs', false, 'btn-danger');
								else
									echo HTML::renderWorkbookIcons('KPIs', false, 'btn-success');
								?>
							</div></div></div>
							<?php echo $wb->getPageBottomLine(); ?>
						</div>
						<!--.page 5 ends-->

						<h1>Financial</h1>

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
							<blockquote class="text-center" style="border: 3px dashed #0000ff">
								<p><b>A sales target</b> <i>is a goal set for a salesperson or sales department measured in revenue or units sold for a specific time.</i></p>
							</blockquote>
							<div class="row">
								<div class="col-sm-12">
									<p><b>Sales targets</b> help keep both the individual and team focused on achieving their goals.</p>
									<p>In a retail store, interaction with the customer is face to face.  Sales targets will often be set according to the 'footfall' which passes through the store.</p>
									<p><b>Footfall</b> is simply a retail term which describes the number of people who pass through the store each day. In a shopping centre it is the amount of customers who enter that shopping centre per day/per week.</p>
									<p>Footfall is split up into two distinct groups - those who know what they want to buy and are coming into the store with the specific aim of purchasing a particular product and those who are just 'browsing'.</p>
									<p>By following the <b>Customer Experience</b> training you have had and simply asking each customer if you can help them you will improve the sales figures in your store. This interaction can sometimes prompt a 'window shopper' or browser into becoming a buyer.</p>
									<p>Other two areas where you may be able to increase sales are through <b>cross-selling (link selling)</b> and <b>upselling</b>.</p>
									<p><b>Cross-selling</b> is where a person has expressed an interest in a particular product and you can identify an opportunity to sell them additional related goods.</p>
									<p><b>Upselling</b> is where you try to sell a more advanced (and more costly) version of a particular product to somebody who has expressed an interest in a product. A good opportunity to try out these techniques is while you are processing a sale. You have the customer's undivided attention and you would be interacting with them at this point anyway. This is an effective use of time and most retailers have adopted this opportunity to increase sales and provide the best customer service through building a rapport with the customer.</p>
									<p class="text-bold"><img class="pull-left" src="module_eportfolio/assets/images/wb2_img1.png" /> &nbsp; Think about the stores KPI and your individual KPI for STARBUYS this week. What are these targets?</p>
									<div class="table-responsive">
										<table class="table table-bordered" <?php echo $feedback->SalesTarget->Status->__toString() == 'A'?'style="pointer-events:none;"':''; ?>>
											<tr><th style="width: 33%;"><?php echo $wb->savers_or_sp == 'savers' ? 'SAS item' : 'STARBUY'; ?></th><th style="width: 33%;">STORE KPI</th><th style="width: 33%;">INDIVIDUAL KPI</th></tr>
											<?php
											$SalesTarget = $answers->SalesTarget;
											for($i = 1; $i <= 4; $i++)
											{
												$key = 'Set'.$i;
												echo '<tr>';
												echo '<td><textarea name="STARBUYKPIs'.$i.'STARBUY" style="width: 100%;">'.$SalesTarget->STARBUYSKPIs->$key->STARBUY->__toString().'</textarea> </td>';
												echo '<td><textarea name="STARBUYKPIs'.$i.'StoreKPI" style="width: 100%;">'.$SalesTarget->STARBUYSKPIs->$key->StoreKPI->__toString().'</textarea> </td>';
												echo '<td><textarea name="STARBUYKPIs'.$i.'IndividualKPI" style="width: 100%;">'.$SalesTarget->STARBUYSKPIs->$key->IndividualKPI->__toString().'</textarea> </td>';
												echo '</tr>';
											}
											?>
										</table>
									</div>
									<p class="text-bold"><img class="pull-left" src="module_eportfolio/assets/images/wb2_img1.png" /> &nbsp; Thinking about your individual KPI for STARBUYS, list at least three ways you will promote these products effectively to meet your targets.</p>
									<div class="table-responsive">
										<table class="table table-bordered" <?php echo $feedback->SalesTarget->Status->__toString() == 'A'?'style="pointer-events:none;"':''; ?>>
											<tr><th style="width: 50%;"><?php echo $wb->savers_or_sp == 'savers' ? 'SAS item' : 'STARBUY'; ?></th><th style="width: 50%;">HOW TO PROMOTE</th></tr>
											<?php
											$STARBUYSPromotion = $answers->SalesTarget->STARBUYSPromotion;
											for($i = 1; $i <= 3; $i++)
											{
												$key = 'Set'.$i;
												echo '<tr>';
												echo '<td><textarea rows="5" name="STARBUYSPromotion'.$i.'KPI" style="width: 100%;">'.$STARBUYSPromotion->$key->KPI->__toString().'</textarea> </td>';
												echo '<td><textarea rows="5" name="STARBUYSPromotion'.$i.'Promotion" style="width: 100%;">'.$STARBUYSPromotion->$key->Promotion->__toString().'</textarea> </td>';
												echo '</tr>';
											}
											?>
										</table>
									</div>
									<p class="text-bold"><img class="pull-left" src="module_eportfolio/assets/images/wb2_img1.png" /> &nbsp; Talk to your management team about your weekly sales target for this week.  What is that target? How is it broken down? How is the store performing to date?</p>
									<p <?php echo $feedback->SalesTarget->Status->__toString() == 'A'?'style="pointer-events:none;"':''; ?>><textarea rows="5" name="WeeklySalesTarget" style="width: 100%;"><?php echo $answers->SalesTarget->WeeklySalesTarget->__toString(); ?></textarea> </p>
									<p class="text-bold"><img class="pull-left" src="module_eportfolio/assets/images/wb2_img1.png" /> &nbsp; Think of ways you can support your team to achieve this target. List your ideas below:</p>
									<p <?php echo $feedback->SalesTarget->Status->__toString() == 'A'?'style="pointer-events:none;"':''; ?>><textarea rows="5" name="TeamSupportToAchieveTarget" style="width: 100%;"><?php echo $answers->SalesTarget->TeamSupportToAchieveTarget->__toString(); ?></textarea> </p>
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
						<!--.page 6 ends-->

						<h1>Financial</h1>

						<div class="step-content">
							<?php echo $wb->getPageTopLine(); ?>
							<div class="row"><div class="col-sm-12"><div class="pull-right">
								<?php
								if($feedback->Wastage->Status->__toString() == 'NA')
									echo HTML::renderWorkbookIcons('Wastage', false, 'btn-danger');
								else
									echo HTML::renderWorkbookIcons('Wastage', false, 'btn-success');
								?>
							</div></div></div>
							<blockquote class="text-center" style="border: 3px dashed #0000ff">
								<p><b>Wastage</b> <i>is loss by deterioration, wear, or destruction.</i></p>
							</blockquote>
							<p>Most retail stores suffer waste at some time or another and examples of this are <b>goods, cardboard </b>and <b>plastic</b>. Wastage can also occur in the wider business and examples of this are <b>recycling waste</b> from stores, <b>water consumption</b> and <b>fuel</b>.</p>
							<p class="text-bold"><img class="pull-left" src="module_eportfolio/assets/images/wb2_img1.png" /> &nbsp; Think about the wastage of goods in your store. Think of at least four reasons goods may become unfit for sale and how you can avoid this happening. Complete the table below:</p>
							<div class="table-responsive">
								<table class="table table-bordered" <?php echo $feedback->Wastage->Status->__toString() == 'A'?'style="pointer-events:none;"':''; ?>>
									<tr><th style="width: 50%;">Reason product is unfit for sale</th><th style="width: 50%;">How you can avoid this happening</th></tr>
									<tr><td>E.G. Product has damaged outer packaging and it battered or misshapen.</td><td>Careful handling, moving and storage of product.</td></tr>
									<?php
									$StoreWastage = $answers->Wastage->StoreWastage;
									for($i = 1; $i <= 4; $i++)
									{
										$key = 'Set'.$i;
										echo '<tr>';
										echo '<td><textarea name="StoreWastage'.$i.'Reason" style="width: 100%;">'.$StoreWastage->$key->Reason->__toString().'</textarea> </td>';
										echo '<td><textarea name="StoreWastage'.$i.'HowToAvoid" style="width: 100%;">'.$StoreWastage->$key->HowToAvoid->__toString().'</textarea> </td>';
										echo '</tr>';
									}
									?>
								</table>
							</div>
							<p class="text-bold"><img class="pull-left" src="module_eportfolio/assets/images/wb2_img1.png" /> &nbsp; Thinking about products being unfit for sale due to being out of date, complete a date coding activity in store. When you have completed this, fill in the answer box below: How often is date coding activity completed in store? What process did you follow and what did you find out of date or short coded?</p>
							<p><textarea rows="5" <?php echo $feedback->Wastage->Status->__toString() == 'A'?'style="pointer-events:none;"':''; ?> name="DateCodingActivity" style="width: 100%;"><?php echo $answers->Wastage->DateCodingActivity->__toString(); ?></textarea> </p>
							<div class="row">
								<div class="col-sm-6">
									<p>How does the wider business aim to reduce waste?</p>
									<p class="text-bold"><?php echo ucfirst($wb->savers_or_sp); ?>'s Sustainability Policy</p>
									<p class="text-bold">Waste Policy</p>
									<p><?php echo ucfirst($wb->savers_or_sp); ?> recognises the importance, value and benefits of managing our business sustainably.</p>
									<p>Our Sustainability Policy sets the framework for <?php echo ucfirst($wb->savers_or_sp); ?>'s commitment to environmental good practice, social responsibility and economic prosperity.</p>
									<p>About 5.9million tons of packaging waste are generated in the UK each year (www.wrap.org.uk/retail/drivers_for_change/index.html) therefore part of our sustainability commitment involves how we deal with issues surrounding waste generation, management and disposal.</p>
									<p>The main waste objectives in our sustainability policy are to reduce the waste generated by working with our suppliers and distributors and identify the most suitable opportunities for recycling waste materials ensuring their safe disposal.</p>
									<p>In our waste policy these objectives are realised through the following objectives.</p>
									<p class="text-center"><img class="img-responsive" src="module_eportfolio/assets/images/wb_r12_pg10_img1.png" /></p>
									<p class="text-bold">Waste Management</p>
									<ul style="margin-left: 15px;">
										<li>We will undertake regular waste audits at stores, offices and distribution centres to regularly reassess the quantities of waste generated. From these audits we will review the opportunities for waste reduction and to maximise recycling.</li>
										<li>We will provide effective communication to our staff on waste management including waste identification guidance.</li>
										<li>We will provide adequate training on waste management for staff who handle different types of waste.</li>
										<li>We will ensure storage facilities are suitable for the types of waste that are generated.</li>
										<li>All types of waste to be placed into the correct containers which will be stored at designated waste areas to comply with duty of care and fire safety requirements.</li>
										<li>Waste management figures will be documented and reported on an annual basis from all parts of the business.</li>
									</ul>
								</div>
								<div class="col-sm-6">
									<p class="text-bold"><br>Our Suppliers</p>
									<ul style="margin-left: 15px;">
										<li>We will encourage our own brand product suppliers to use less packaging materials and use materials which are more easily recycled.</li>
										<li>We will seek opportunities to reduce waste by working with suppliers to streamline packaging of goods sold in our stores</li>
										<li>Construction, renovation or refit projects we will specify that contractors are to divert 50% of waste generated from landfill from 2011.</li>
									</ul>
									<p class="text-bold"><br>Our Customers</p>
									<ul style="margin-left: 15px;">
										<li>We will continue to work to reduce the number of plastic bags given to customers and provide only biodegradable bags on the phase out of offering plastic bags to customers.</li>
										<li>We will continue to include our on-pack recycling (www.onpackrecyclinglabel.org.uk) information to enable customers to recycle our product packaging more easily.</li>
									</ul>
									<p class="text-bold"><br>Our Operations</p>
									<ul style="margin-left: 15px;">
										<li>In our offices we will provide more recycling bins and fewer general waste bins to enable staff to recycle more easily.</li>
										<li>We will seek opportunities to reduce significant material use.</li>
										<li>Encourage the re-use of materials and use of those with a high recycled content.</li>
										<li>We will monitor the use of printing paper look to reuse paper where possible before it is recycled.</li>
									</ul>
								</div>
								<div class="col-sm-12">
									<p class="text-bold text-center"><br><img class="pull-left" src="module_eportfolio/assets/images/wb2_img1.png" /> &nbsp; Thinking about <?php echo ucfirst($wb->savers_or_sp); ?>'s Sustainability Policy, what do you do on a daily basis in store to contribute to this policy?</p>
									<div class="table-responsive">
										<table class="table table-bordered" <?php echo $feedback->Wastage->Status->__toString() == 'A'?'style="pointer-events:none;"':''; ?>>
											<tr><th class="text-center" colspan="3">Your Contributions</th></tr>
											<tr>
												<td><textarea name="SustainabilityContribution1" style="width: 100%;"><?php echo $answers->Wastage->SustainabilityContribution1->__toString(); ?></textarea> </td>
												<td><textarea name="SustainabilityContribution2" style="width: 100%;"><?php echo $answers->Wastage->SustainabilityContribution2->__toString(); ?></textarea> </td>
												<td><textarea name="SustainabilityContribution3" style="width: 100%;"><?php echo $answers->Wastage->SustainabilityContribution3->__toString(); ?></textarea> </td></tr>
										</table>
									</div>
								</div>
							</div>
							<?php if($wb->id != '' && $_SESSION['user']->type == User::TYPE_ASSESSOR && ($wb->wb_status == Workbook::STATUS_LEARNER_COMPLETED || $wb->wb_status == Workbook::STATUS_BEING_CHECKED || $wb->wb_status == Workbook::STATUS_IV_REJECTED)) {?>
							<div class="row well">
								<div class="col-sm-12">
									<div class="box box-success box-solid">
										<div class="box-header with-border"><h3 class="box-title">Assessor Feedback</h3></div>
										<div class="box-body assessorFeedback" <?php echo $feedback->Wastage->Status->__toString() == 'A'?'style="pointer-events:none;"':''; ?>>
											<div class="form-group">
												<label for="status_Wastage" class="col-sm-12 control-label">Status:</label>
												<div class="col-sm-12">
													<?php
													echo HTML::selectChosen('status_Wastage', $answer_status, $feedback->Wastage->Status->__toString() == 'A'?$feedback->Wastage->Status->__toString():'', false, true); ?>
												</div>
											</div>
											<div class="form-group">
												<label for="comments_Wastage" class="col-sm-12 control-label">Comments:</label>
												<div class="col-sm-12">
													<textarea name="comments_Wastage" rows="7" style="width: 100%;"><?php echo $feedback->Wastage->Comments->__toString(); ?></textarea>
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
								if($feedback->Wastage->Status->__toString() == 'NA')
									echo HTML::renderWorkbookIcons('Wastage', false, 'btn-danger');
								else
									echo HTML::renderWorkbookIcons('Wastage', false, 'btn-success');
								?>
							</div></div></div>
							<?php echo $wb->getPageBottomLine(); ?>
						</div>
						<!--.page 7 ends-->

						<h1>Financial</h1>

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
									<p class="text-center"><h3 class="text-center text-bold">Qualification questions</h3></p>
									<p>Now you have completed the section on Financial answer the following questions:</p>
									<p class="text-bold">Unit 7 - 1.1: Identify the key principles of operating commercially</p>
									<textarea name="Unit1_1" rows="7" style="width: 100%;"><?php echo $answers->QualificationQuestions->Unit1_1->__toString(); ?></textarea>
									<p class="text-bold">Unit 7 - 1.2: Explain how to support the financial performance of a business including reducing wastage and returns</p>
									<textarea name="Unit1_2" rows="7" style="width: 100%;"><?php echo $answers->QualificationQuestions->Unit1_2->__toString(); ?></textarea>
									<p class="text-bold">Unit 7 - 1.3: Explain methods for working towards sales targets</p>
									<textarea name="Unit1_3" rows="7" style="width: 100%;"><?php echo $answers->QualificationQuestions->Unit1_3->__toString(); ?></textarea>
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
						<!--.page 8 ends-->

						<h1>Financial</h1>

						<div class="step-content">
							<?php echo $wb->getPageTopLine(); ?>
							<br>
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
						<!--.page 9 ends-->

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

		$("#frm_wb_financial :input").not(".assessorFeedback :input, #signature_text, #frm_wb_financial :input[type=hidden], .btnViewSectionHistory, .btnViewAssessorComments, #iv_status, #iv_comments, #reopen_comments").prop("disabled", true);

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
							var myForm = document.forms['frm_wb_financial'];
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
				var myForm = document.forms['frm_wb_financial'];
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

					var myForm = document.forms['frm_wb_financial'];
					myForm.elements['full_save'].value = 'Y';
					myForm.elements['full_save_feedback'].value = 'Y';
					window.onbeforeunload = null;
					myForm.submit();

				},
				'Save And Come Back Later':function () {

					var myForm = document.forms['frm_wb_financial'];
					myForm.elements['full_save'].value = 'Y';
					myForm.elements['full_save_feedback'].value = 'N';
					window.onbeforeunload = null;
					myForm.submit();

				}
			}
		});
	});

	function partialSave() {
		$('#frm_wb_financial :input[name=full_save]').val('N');
		$($('#frm_wb_financial').serializeArray()).each(function(i, field)
		{
			document.forms["frm_wb_financial"].elements[field.name].value = field.value.replace(/£/g, "GBP");
		});

		$.ajax({
			type:'POST',
			url:'do.php?_action=save_wb_financial',
			data:$('#frm_wb_financial').serialize(),
			async:false,
			beforeSend:function () {
				//$("#loading").dialog('open').html("<p><i class=\"fa fa-refresh fa-spin\"></i> Busy ...</p>");
			},
			success:function (data, status, xhr) {
				console.log(data);
				console.log(status);
				console.log(xhr.status);
				if(xhr.status == 200 && data == window.phpWorkbookID)
				{
					$('#frm_wb_financial').sisyphus().manuallyReleaseData();
					toastr.success('The information has been saved');
				}
				else
				{
					alert('Your session has been reset, please don\'t close the browser, login again and open this workbook. Your data will not be lost.');
				}
				reset();
				startInterval();
			},
			error:function (data, status, xhr) {
				if(status == "error")
				{
					if(data.readyState == 0)
						alert("Request not initialized, this could be because of no internet connection");
					else
						alert("Unknown error, please report to the administrator.");
				}

				console.log(data);
				console.log(status);
				console.log(xhr);
				console.log(xhr.status);
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
<script src="module_eportfolio/assets/sisyphus.min.js"></script>


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

	$( function() {
		$( "#frm_wb_financial" ).sisyphus({
			customKeySuffix: "<?php echo $wb->id; ?>",
			autoRelease: true,
			onRestore: function(){
				$.post('do.php?_action=save_wb_financial',$('#frm_wb_financial').serialize(),function(data, status, xhr)
				{
					console.log(data);
					console.log(status);
					console.log(xhr);
					console.log(xhr.status);
					if(xhr.status == 200 && data == window.phpWorkbookID)
						$('#frm_wb_financial').sisyphus().manuallyReleaseData();
					return true;
				});
			}
		});
	} );
</script>

</html>
