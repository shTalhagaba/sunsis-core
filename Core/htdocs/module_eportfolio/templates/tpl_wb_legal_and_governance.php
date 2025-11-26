<?php /* @var $wb WBLegalAndGovernance */ ?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/html" xmlns="http://www.w3.org/1999/html" xmlns="http://www.w3.org/1999/html"
      xmlns="http://www.w3.org/1999/html" xmlns="http://www.w3.org/1999/html" xmlns="http://www.w3.org/1999/html"
      xmlns="http://www.w3.org/1999/html" xmlns="http://www.w3.org/1999/html" xmlns="http://www.w3.org/1999/html">
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<title>Legal & Governance and Diversity</title>
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

<form name="frm_wb_legal_and_governance" id="frm_wb_legal_and_governance" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post" enctype="multipart/form-data">
<input type="hidden" name="_action" value="save_wb_legal_and_governance"/>
<input type="hidden" name="id" value="<?php echo $wb->id; ?>"/>
<input type="hidden" name="tr_id" value="<?php echo $wb->tr_id; ?>"/>
<input type="hidden" name="wb_status" id="wb_status" value=""/>
<input type="hidden" name="full_save" id="full_save" value="<?php echo $wb->full_save; ?>"/>
<input type="hidden" name="full_save_feedback" id="full_save_feedback" value="<?php echo $wb->full_save_feedback; ?>"/>

<div class="container-float">
<div class="wrapper" style="background-color: #ffffff;">

<header class="main-header"><span style="margin-left: 50%;"><?php echo DAO::getSingleValue($link, "SELECT title FROM student_frameworks WHERE tr_id = '{$wb->tr_id}'"); ?></span></header>

<div class="content-wrapper" style="background-color: #ffffff;">

<?php echo $wb->savers_or_sp == 'savers' ? '<section class="content-header"><h1 style="color: #0000ff;">Legal & Governance and Diversity</h1></section>' : '<section class="content-header"><h1>Legal & Governance and Diversity</h1></section>' ?>

<section class="content">

<div id="wizard">

<h1>Legal & Governance and Diversity</h1>

<div class="step-content">
	<?php echo $wb->getPageTopLine(); ?>
	<div style="position: absolute; top: 40%; right: 50%;" class="lead">
		<?php echo $wb->savers_or_sp == 'savers' ? '<h2 class="text-bold" style="color: #0000ff;">Legal & Governance and Diversity</h2>' : '<h2 class="text-bold">Legal & Governance and Diversity</h2>' ?>
		<p class="text-center">Module</p>
	</div>

	<?php echo $wb->getPageBottomLine(); ?>
</div>
<!--.page 1 ends-->

<h1>Legal & Governance and Diversity</h1>

<div class="step-content">
	<?php echo $wb->getPageTopLine(); ?>
	<div class="row"><div class="col-sm-12"><div class="pull-right"><button title="save information" class="btn btn-warning dim" type="button" onclick="partialSave();"><i class="fa fa-save"></i> </button><?php echo HTML::renderWorkbookIcons('', true); ?></div></div></div>

	<div class="row">
		<div class="col-sm-12">
			<p class="text-bold">This section is about Legal and Governance in the workplace and what you need to know and do.</p>
			<p>When working in retail it is important to ensure that you are familiar with and adhere to the policies and procedures that exist within the organisation.</p>
			<p>Organisations and individuals that work for them must operate within these frameworks to ensure the brand promise and service offer are delivered as well as ensuring all legal and ethical needs are met.</p>
			<p>All retail businesses develop their own policies and procedures. This is to help ensure that they remain legally compliant. In Superdrug you can find the full list of policies and procedures in the big red book that is usually found in the staffroom. This manual contains information on the company policies such as breaks, holiday and maternity leave to name a few.</p>
			<p>In store it is important that all staff are aware of the laws that exist to protect the rights of customers as well as themselves. This ensures that if they ever have a problem in store, customers are given the best possible service and leave happy on every occasion. Other benefits of staff being aware of laws, policies and procedures are that mistakes are quickly rectified; standards are maintained; employees are accountable for their mistakes and the company will have a good reputation.</p>
			<p>All of these = A SATISFIED CUSTOMER!</p>
			<p>In order to make yourself aware of these requirements, refer to the latest manual either in store or on the Hub. Ask your manager for guidance on any legal and ethical requirements which may apply. There are a range of legal requirement that have an impact on the delivery of customer service. The main pieces of legislation include: Health and Safety, Consumer Rights Act and Data Protection.</p>
		</div>
		<div class="col-sm-12">
			<h2>Meeting regulations and legislation</h2>
			<p>
				<?php if($wb->savers_or_sp == 'savers') { ?>
				<img src="module_eportfolio/assets/images/wb4_pg2_img1_.png" />
				<?php } else { ?>
				<img src="module_eportfolio/assets/images/wb4_pg2_img1.png" />
				<?php } ?>
				<strong>Learner journey / Visit plan</strong>
			</p>
		</div>
		<div class="col-sm-12">
			<p>Before you complete this section please ensure you have spent time completing the training detailed below and this has been signed off.</p>
			<p><br></p>
			<div class="row">
				<div class="col-sm-12 table-responsive">
					<table class="table table-bordered table-striped">
						<tr><th>Learning</th><th>Workbook/IPad/In-store activity</th><th>Date completed</th></tr>
						<?php
						$items = $wb->savers_or_sp == 'savers' ? array('Policies and procedures','Law and legislation','Age related sales/challenge 25') : array('Policies and procedures','Law and legislation','Age related sales');
						$j = 0;
						foreach($items AS $i)
						{
							$key = 'DC'.++$j;
							echo '<tr>';
							echo '<td>' . $i . '</td>';
							echo '<td>In store activity</td>';
							echo '<td>' . HTML::datebox($key, $answers->Journey->$key->__toString()) . '</td>';
							echo '</tr>';
						}
						?>
					</table>
				</div>
			</div>
		</div>
	</div>

	<p><br></p>
	<div class="row"><div class="col-sm-12"><div class="pull-right"><button title="save information" class="btn btn-warning dim" type="button" onclick="partialSave();"><i class="fa fa-save"></i> </button><?php echo HTML::renderWorkbookIcons('', true); ?></div></div></div>
	<?php echo $wb->getPageBottomLine(); ?>
</div>
<!--.page 2 ends-->

<h1>Legal & Governance and Diversity</h1>

<div class="step-content">
	<?php echo $wb->getPageTopLine(); ?>
	<div class="row"><div class="col-sm-12"><div class="pull-right"><?php echo HTML::renderWorkbookIcons('', true); ?></div></div></div>

	<div class="row">
		<div class="col-sm-4 col-sm-offset-4">
			<div class="text-center" style="padding: 15px; border: #90ee90 solid 2px;">
				<p><span class="text-bold">Legal</span> - relating to the law or permitted by law</p>
				<p><span class="text-bold">Governance</span> - establishment of policies, and continuous monitoring of their proper implementation, by the members of the governing body of an organisation.</p>
			</div>
		</div>
	</div>

	<div class="row">
		<div class="col-sm-12">
			<div style="background-color: #adff2f; padding: 15px; border: #000000 2px solid;"><p class="text-center text-bold">How does consumer protection protect the rights of customers?</p></div>
			<div>
				<p><br>The Consumer Rights Act 2015 became law on 01 October 2015, replacing three major pieces of consumer legislation - the Sale of Goods Act, Unfair Terms in Consumer Contracts Regulations and the Supply of Goods and Services Act.</p>
				<img class="img-responsive" src="module_eportfolio/assets/images/wb11_pg4_img1.png" />
			</div>
		</div>
	</div>

	<p><br></p>
	<div class="row"><div class="col-sm-12"><div class="pull-right"><?php echo HTML::renderWorkbookIcons('', true); ?></div></div></div>
	<?php echo $wb->getPageBottomLine(); ?>
</div>
<!--.page 3 ends-->

<h1>Legal & Governance and Diversity</h1>

<div class="step-content">
	<?php echo $wb->getPageTopLine(); ?>
	<div class="row"><div class="col-sm-12"><div class="pull-right"><?php echo HTML::renderWorkbookIcons('', true); ?></div></div></div>

	<div class="row">
		<div class="col-sm-6">
			<p class="text-bold">Product quality</p>
			<p>As with the Sale of Goods Act, under the Consumer Rights Act all products must be of satisfactory quality, fit for purpose and as described.</p>
			<ul style="margin-left: 15px; margin-bottom: 15px;">
				<li>
					<p class="text-bold">Satisfactory quality</p>
					<p>Goods shouldn't be faulty or damaged when you receive them. You should ask what a reasonable person would consider satisfactory for the goods in question. For example, bargain bucket products won't be held to as high standards as luxury goods. </p>
				</li>
				<li>
					<p class="text-bold">Fit for purpose</p>
					<p>The goods should be fit for the purpose they are supplied for, as well as any specific purpose you made known to the retailer before you agreed to buy the goods. </p>
				</li>
				<li>
					<p class="text-bold">As described</p>
					<p>The goods supplied must match any description given to you, or any models or samples shown to you at the time of purchase.</p>
				</li>
			</ul>
			<p class="text-bold">30-day right to reject</p>
			<p>If what you've bought doesn't satisfy any one of the three criteria outlined above, you have a claim under the Consumer Rights Act however this right is limited to 30 days from the date you buy your product. After 30 days you will not be legally entitled to a full refund if your item develops a fault, although some sellers may offer you an extended refund period.</p>
			<p>Your rights under the Consumer Rights Act are against the retailer – the company that sold you the product – not the manufacturer, and so you must take any claim to the retailer. </p>
		</div>
		<div class="col-sm-6">
			<p class="text-bold">Repair or replace</p>

			<p>If you are outside the 30-day right to reject, you have to give the retailer one opportunity to repair or replace any goods or digital content which are of unsatisfactory quality, unfit for purpose or not as described. </p>
			<p>You can ask the retailer to repair or replace faulty goods but it can normally choose whichever would be cheapest or easier for it to do.</p>
			<p>You're entitled to a full or partial refund instead of a repair or replacement if any of the following are true:</p>
			<ul style="margin-left: 15px; margin-bottom: 15px;">
				<li>The cost of the repair or replacement is disproportionate to the value of the goods or digital content</li>
				<li>A repair or replacement is impossible</li>
				<li>A repair or replacement would cause you significant inconvenience</li>
				<li>The repair would take an unreasonably long amount of time</li>
			</ul>
			<p>If a repair or replacement is not possible, or the attempt at repair fails, or the first replacement also turns out to be defective, you have a further right to receive a refund of up to 100% of the price you paid or to reject the goods for a full refund.</p>
			<p>If you don't want a refund and still want your product repaired or replaced, you have the right to request the retailer makes further attempts at a repair or replacement.</p>
			<p class="text-bold">Repair or replace</p>
			<p>If you discover the fault within the first six months from purchase, it is presumed to have been there since the time of purchase - unless the retailer can prove otherwise.</p>
		</div>
	</div>

	<p><br></p>
	<div class="row"><div class="col-sm-12"><div class="pull-right"><?php echo HTML::renderWorkbookIcons('', true); ?></div></div></div>
	<?php echo $wb->getPageBottomLine(); ?>
</div>
<!--.page 4 ends-->

<h1>Legal & Governance and Diversity</h1>

<div class="step-content">
	<?php echo $wb->getPageTopLine(); ?>
	<div class="row"><div class="col-sm-12"><div class="pull-right">
		<?php
		if($feedback->Scenarios->Status->__toString() == 'NA')
			echo HTML::renderWorkbookIcons('Scenarios', false, 'btn-danger');
		else
			echo HTML::renderWorkbookIcons('Scenarios', false, 'btn-success');
		?>
	</div></div></div>



	<div class="row">
		<div class="col-sm-6">
			<p class="text-bold">Digital content rights</p>
			<p>The Consumer Rights Act defines digital content as ‘data which is produced and supplied in digital form.'</p>
			<p>Digital content must also be:</p>
			<ul style="margin-left: 15px; margin-bottom: 15px;">
				<li>Of satisfactory quality</li>
				<li>Fit for a particular purpose</li>
				<li>As described by the seller</li>
			</ul>
			<p>If digital content does not conform to these criteria, you have the right to a repair or replacement of the digital content you've purchased.</p>
			<p class="text-bold">Delivery rights</p>
			<p>The retailer is responsible for goods until they are in your physical possession or in the possession of someone appointed by you to accept them.
				This means that retailers are liable for the service provided by the couriers they employ - the delivery firm is not liable.
			</p>
			<p class="text-bold">Late deliveries </p>
			<p>There is a default delivery period of 30 days during which the retailer needs to deliver unless a longer period has been agreed.</p>
		</div>
		<div class="col-sm-6">
			<p>If the retailer fails to deliver within the 30 days or on the date that has been agreed, you can do the following:</p>
			<ul style="margin-left: 15px; margin-bottom: 15px;">
				<li>If your delivery is later than agreed and it was essential that it was delivered on time, then you have the right to terminate the purchase and get a full refund.</li>
				<li>If the delivery isn't time essential but another reasonable delivery time can't be agreed, you're also within your right to cancel the order for a full refund.</li>
			</ul>
			<p class="text-bold">Supply of a service</p>
			<p>The term 'service' covers a wide variety of services including large and small-scale work you might have carried out in your home or elsewhere.</p>
			<p>Examples of services provided without goods include:</p>
			<ul style="margin-left: 15px; margin-bottom: 15px;">
				<li>Dry cleaning</li>
				<li>Entertainment</li>
				<li>Work done by professionals, such as solicitors, estate agents and accountants</li>
			</ul>
			<p>The service contract is governed by the Consumer Rights Act which means you can use this as protection should anything go wrong.</p>

		</div>
		<div class="col-sm-12" <?php echo $feedback->Scenarios->Status->__toString() == 'A'?'style="pointer-events:none;"':''; ?>>
			<table class="table">
				<tr><th style="width: 40%;">Scenario</th><th style="60%;">Rights and action</th></tr>
				<tr>
					<td>You have taken receipt of a mobile phone which isn't the model you ordered.</td>
					<td><textarea rows="3" name="Scenario1" style="width: 100%;"><?php echo $answers->Scenarios->Scenario1->__toString(); ?></textarea> </td>
				</tr>
				<tr>
					<td>Your washing machine was due to be delivered on August 1st however after many phone calls it arrives on 1 September. You no longer want the item.</td>
					<td><textarea rows="3" name="Scenario2" style="width: 100%;"><?php echo $answers->Scenarios->Scenario2->__toString(); ?></textarea> </td>
				</tr>
			</table>
		</div>
	</div>

	<?php if($wb->id != '' && $_SESSION['user']->type == User::TYPE_ASSESSOR && ($wb->wb_status == Workbook::STATUS_LEARNER_COMPLETED || $wb->wb_status == Workbook::STATUS_BEING_CHECKED || $wb->wb_status == Workbook::STATUS_IV_REJECTED)) {?>
	<div class="row well">
		<div class="col-sm-12">
			<div class="box box-success box-solid">
				<div class="box-header with-border"><h3 class="box-title">Assessor Feedback</h3></div>
				<div class="box-body assessorFeedback" <?php echo $feedback->Scenarios->Status->__toString() == 'A'?'style="pointer-events:none;"':''; ?>>
					<div class="form-group">
						<label for="status_Scenarios" class="col-sm-12 control-label">Status:</label>
						<div class="col-sm-12">
							<?php
							echo HTML::selectChosen('status_Scenarios', $answer_status, $feedback->Scenarios->Status->__toString() == 'A'?$feedback->Scenarios->Status->__toString():'', false, true); ?>
						</div>
					</div>
					<div class="form-group">
						<label for="comments_Scenarios" class="col-sm-12 control-label">Comments:</label>
						<div class="col-sm-12">
							<textarea name="comments_Scenarios" rows="7" style="width: 100%;"><?php echo $feedback->Scenarios->Comments->__toString(); ?></textarea>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<?php } ?>

	<div class="row"><div class="col-sm-12"><div class="pull-right">
		<?php
		if($feedback->Scenarios->Status->__toString() == 'NA')
			echo HTML::renderWorkbookIcons('Scenarios', false, 'btn-danger');
		else
			echo HTML::renderWorkbookIcons('Scenarios', false, 'btn-success');
		?>
	</div></div></div>

	<?php echo $wb->getPageBottomLine(); ?>
</div>
<!--.page 5 ends-->

<h1>Legal & Governance and Diversity</h1>

<div class="step-content">
	<?php echo $wb->getPageTopLine(); ?>
	<div class="row"><div class="col-sm-12"><div class="pull-right"><?php echo HTML::renderWorkbookIcons('', true); ?></div></div></div>

	<div class="row">
		<div class="col-sm-12">
			<div class="table-responsive">
				<table class="table">
					<tr><td colspan="3"><div style="background-color: #adff2f; padding: 15px; border: #000000 2px solid;"><p class="text-center text-bold">How are consumers protected from unfair trading practices?</p></div></td></tr>
					<tr><td colspan="3" align="center" class="text-center"><img src="module_eportfolio/assets/images/wb11_img1.png" /></td></tr>
					<tr><td colspan="3" align="center" class="text-center"><div style="padding: 5px; border: #adff2f dashed 2px;"><b>The Consumer Protection from Unfair Trading Regulations 2008</b> protects consumers from unfair or misleading trading practices and ban misleading omissions and aggressive sales tactics.</div> </td></tr>
					<tr><td align="center" class="text-center"><img src="module_eportfolio/assets/images/wb11_img1.png" /></td><td align="center" class="text-center"><img src="module_eportfolio/assets/images/wb11_img1.png" /></td><td align="center" class="text-center"><img src="module_eportfolio/assets/images/wb11_img1.png" /></td></tr>
					<tr><td align="center" class="text-center"><div style="padding: 5px; border: #adff2f dashed 2px;">A general ban on unfair commercial practices</div> </td><td align="center" class="text-center"><div style="padding: 5px; border: #000000 solid 2px;">A ban on misleading and aggressive practices which are assessed in light of the effect they have, or are likely to have, on the average consumer</div> </td><td align="center" class="text-center"><div style="padding: 5px; border: #adff2f dashed 2px;">The 'blacklist' of commercial practices which will always be unfair and so are banned outright</div> </td></tr>
				</table>
			</div>
			<div class="col-sm-4">
				<p class="text-bold">What is unfair?</p>
				<p>Under the Regulations, a commercial practice is 'unfair' if it fits both of the following requirements:</p>
				<ul style="margin-left: 15px; margin-bottom: 15px;">
					<li>It falls below the good-faith standards of skill and care that a trader in that industry would be expected to exercise towards customers, and</li>
					<li>It affects, or is likely to affect, consumers' ability to make an informed decision about whether to purchase a particular product</li>
				</ul>
			</div>
			<div class="col-sm-4">
				<p><span class="text-bold">Misleading actions</span> include advertising goods that don't exist, or offering just a few items at the advertised price with no hope of meeting large demand.</p>
				<p>Traders are also banned from making misleading comparisons.</p>
				<p>The Regulations also offer protection against traders who miss out key information that you might need to make an informed decision.</p>
			</div>
			<div class="col-sm-4">
				<p class="text-bold">Banned practices include:</p>
				<ul style="margin-left: 15px; margin-bottom: 15px;">
					<li>Bait advertising - luring the consumer with attractive advertising around special prices when the trader knows that he cannot offer that product, or only has a few in stock at that price.</li>
					<li>Bait and switch - promoting one product with the intention of selling you something else. </li>
				</ul>
			</div>
		</div>
	</div>

	<p><br></p>
	<div class="row"><div class="col-sm-12"><div class="pull-right"><?php echo HTML::renderWorkbookIcons('', true); ?></div></div></div>
	<?php echo $wb->getPageBottomLine(); ?>
</div>
<!--.page 6 ends-->

<h1>Legal & Governance and Diversity</h1>

<div class="step-content">
	<?php echo $wb->getPageTopLine(); ?>
	<div class="row"><div class="col-sm-12"><div class="pull-right">
		<?php
		if($feedback->ConsumerCreditAct->Status->__toString() == 'NA')
			echo HTML::renderWorkbookIcons('ConsumerCreditAct', false, 'btn-danger');
		else
			echo HTML::renderWorkbookIcons('ConsumerCreditAct', false, 'btn-success');
		?>
	</div></div></div>



	<div class="row">
		<div class="col-sm-12">
			<p>Over the next few pages we are going to look at different legislation / regulations that Retailers must comply with.</p>
			<table class="table">
				<tr>
					<td><img class="pull-left" src="module_eportfolio/assets/images/wb2_pg18_img1.png" /></td>
					<td>
						<p>For each you will need to research them in more detail and then answer two questions:</p>
						<ol style="margin-left: 15px; margin-bottom: 15px;">
							<li>How do they protect consumers?</li>
							<li>How do they impact the business?</li>
						</ol>
					</td>
					<td></td>
				</tr>
			</table>
			<table class="table" <?php echo $feedback->ConsumerCreditAct->Status->__toString() == 'A'?'style="pointer-events:none;"':''; ?>>
				<tr><td colspan="2"><div style=" padding: 15px; border: #adff2f 2px solid;"><p class="text-center"><b>The Consumer Credit Act</b> regulates credit card purchases but also gives you protection when you enter into a loan or hire agreement. It also gives you the right to a cooling off period</p></div></td></tr>
				<tr><td align="center" class="text-center"><img src="module_eportfolio/assets/images/wb11_img1.png" /></td><td align="center" class="text-center"><img src="module_eportfolio/assets/images/wb11_img1.png" /></td></tr>
				<tr><td><div style="background-color: #adff2f; padding: 15px; border: #000000 2px solid;"><p class="text-center text-bold">How does it protect consumers?</p> </div></td><td><div style="background-color: #adff2f; padding: 15px; border: #000000 2px solid;"><p class="text-center text-bold">How does it impact the business?</p> </div></td></tr>
				<tr><td align="center" class="text-center"><img src="module_eportfolio/assets/images/wb11_img1.png" /></td><td align="center" class="text-center"><img src="module_eportfolio/assets/images/wb11_img1.png" /></td></tr>
				<tr><td><textarea name="CCAHowProtect" style="width: 100%;" rows="10"><?php echo $answers->ConsumerCreditAct->HowProtect->__toString(); ?></textarea> </td><td><textarea name="CCAHowImpact" style="width: 100%;" rows="10"><?php echo $answers->ConsumerCreditAct->HowImpact->__toString(); ?></textarea> </td></tr>
			</table>
		</div>
	</div>

	<?php if($wb->id != '' && $_SESSION['user']->type == User::TYPE_ASSESSOR && ($wb->wb_status == Workbook::STATUS_LEARNER_COMPLETED || $wb->wb_status == Workbook::STATUS_BEING_CHECKED || $wb->wb_status == Workbook::STATUS_IV_REJECTED)) {?>
	<div class="row well">
		<div class="col-sm-12">
			<div class="box box-success box-solid">
				<div class="box-header with-border"><h3 class="box-title">Assessor Feedback</h3></div>
				<div class="box-body assessorFeedback" <?php echo $feedback->ConsumerCreditAct->Status->__toString() == 'A'?'style="pointer-events:none;"':''; ?>>
					<div class="form-group">
						<label for="status_ConsumerCreditAct" class="col-sm-12 control-label">Status:</label>
						<div class="col-sm-12">
							<?php
							echo HTML::selectChosen('status_ConsumerCreditAct', $answer_status, $feedback->ConsumerCreditAct->Status->__toString() == 'A'?$feedback->ConsumerCreditAct->Status->__toString():'', false, true); ?>
						</div>
					</div>
					<div class="form-group">
						<label for="comments_ConsumerCreditAct" class="col-sm-12 control-label">Comments:</label>
						<div class="col-sm-12">
							<textarea name="comments_ConsumerCreditAct" rows="7" style="width: 100%;"><?php echo $feedback->ConsumerCreditAct->Comments->__toString(); ?></textarea>
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
		if($feedback->ConsumerCreditAct->Status->__toString() == 'NA')
			echo HTML::renderWorkbookIcons('ConsumerCreditAct', false, 'btn-danger');
		else
			echo HTML::renderWorkbookIcons('ConsumerCreditAct', false, 'btn-success');
		?>
	</div></div></div>
	<?php echo $wb->getPageBottomLine(); ?>
</div>
<!--.page 7 ends-->

<h1>Legal & Governance and Diversity</h1>

<div class="step-content">
	<?php echo $wb->getPageTopLine(); ?>
	<div class="row"><div class="col-sm-12"><div class="pull-right">
		<?php
		if($feedback->DataProtectionAct->Status->__toString() == 'NA')
			echo HTML::renderWorkbookIcons('DataProtectionAct', false, 'btn-danger');
		else
			echo HTML::renderWorkbookIcons('DataProtectionAct', false, 'btn-success');
		?>
	</div></div></div>



	<div class="row">
		<div class="col-sm-12">

			<table class="table" <?php echo $feedback->DataProtectionAct->Status->__toString() == 'A'?'style="pointer-events:none;"':''; ?>>
				<tr><td colspan="2"><div style=" padding: 15px; border: #000000 2px solid;"><p class="text-center">The <b>General Data Protection Regulation</b> (GDPR) is a regulation by which the European Parliament, the Council of the European Union and the European Commission intend to strengthen and unify data protection for all individuals within the European Union (EU).</p></div></td></tr>
				<tr><td align="center" class="text-center"><img src="module_eportfolio/assets/images/wb11_img2.png" /></td><td align="center" class="text-center"><img src="module_eportfolio/assets/images/wb11_img2.png" /></td></tr>
				<tr><td><div style="background-color: #adff2f; padding: 15px; border: #000000 2px solid;"><p class="text-center text-bold">How does it protect consumers?</p> </div></td><td><div style="background-color: #adff2f; padding: 15px; border: #000000 2px solid;"><p class="text-center text-bold">How does it impact the business?</p> </div></td></tr>
				<tr><td align="center" class="text-center"><img src="module_eportfolio/assets/images/wb11_img2.png" /></td><td align="center" class="text-center"><img src="module_eportfolio/assets/images/wb11_img2.png" /></td></tr>
				<tr><td><textarea name="DPAHowProtect" style="width: 100%;" rows="10"><?php echo $answers->DataProtectionAct->HowProtect->__toString(); ?></textarea> </td><td><textarea name="DPAHowImpact" style="width: 100%;" rows="10"><?php echo $answers->DataProtectionAct->HowImpact->__toString(); ?></textarea> </td></tr>
			</table>
		</div>
	</div>

	<div class="row">
		<div class="col-sm-12" <?php echo $feedback->DataProtectionAct->Status->__toString() == 'A'?'style="pointer-events:none;"':''; ?>>
			<p class="text-bold">Data Protection Act</p>

			<p>The GDPR controls how your personal information is used by organisations, businesses or the government.</p>
			<img class="img-responsive" src="module_eportfolio/assets/images/wb11_pg10_img1.png" />
			<p class="text-bold">There is stronger legal protection for more sensitive information, such as:</p>
			<table class="table">
				<tr>
					<td>
						<ul style="margin-left: 15px; margin-bottom: 15px;">
							<li>Ethnic background</li>
							<li>Political opinions</li>
						</ul>
					</td>
					<td>
						<ul style="margin-left: 15px; margin-bottom: 15px;">
							<li>Religious beliefs</li>
							<li>Health</li>
						</ul>
					</td>
					<td>
						<ul style="margin-left: 15px; margin-bottom: 15px;">
							<li>Sexual health</li>
							<li>Criminal records</li>
						</ul>
					</td>
				</tr>
			</table>
			<p class="text-bold"><img class="pull-left" src="module_eportfolio/assets/images/wb2_img1.png" /> &nbsp; What information do we request off our customers and why?</p>
			<textarea  rows="5" name="DPAWhatInfo" style="width: 100%;"><?php echo $answers->DataProtectionAct->WhatInfo->__toString(); ?></textarea>
		</div>
	</div>

	<div class="row">
		<div class="col-sm-12">
			<p class="text-bold">Personal data an employer can keep about an employee</p>
			<p>Employees' personal data should be kept safe, secure and up to date by an employer.</p>
			<p class="text-bold">Data an employer can keep about an employee includes:</p>
			<img class="pull-right" src="module_eportfolio/assets/images/wb11_pg11_img1.png" />
			<ul style="margin-left: 15px; margin-bottom: 15px;">
				<li>Name</li>
				<li>Address</li>
				<li>Date of birth</li>
				<li>Sex</li>
				<li>Education and qualifications</li>
				<li>Work experience</li>
				<li>National Insurance number</li>
				<li>Tax code</li>
				<li>Details of any unknown disabililty</li>
				<li>Emergency contact details</li>
			</ul>
			<p class="text-bold">They will also keep details about an employee such as:</p>
			<ul style="margin-left: 15px; margin-bottom: 15px;">
				<li>Employment history with the organisation</li>
				<li>Employment terms and conditions (e.g. pay, hours of work, holidays, benefits, absence)</li>
				<li>Any accidents connected with work</li>
				<li>Any training taken</li>
				<li>Any disciplinary action</li>
			</ul>
			<p class="text-bold">An employee has a right to be told:</p>
			<ul style="margin-left: 15px; margin-bottom: 15px;">
				<li>What records are kept and how they're used</li>
				<li>The confidentiality of the records</li>
				<li>How these records can help with their training and development at work</li>
			</ul>
			<p>If an employee asks to find out what data is kept on them, the employer will have 40 days to provide a copy of the information. An employer shouldn't keep data any longer than necessary and they must follow the rules on data protection.</p>
			<p class="text-bold"><img class="pull-left" src="module_eportfolio/assets/images/wb2_img1.png" /> &nbsp; If you were to request to see your personal data how much is the admin fee?</p>
			<p <?php echo $feedback->DataProtectionAct->Status->__toString() == 'A'?'style="pointer-events:none;"':''; ?>><textarea rows="3" name="DPAAdminFee" style="width: 100%;"><?php echo $answers->DataProtectionAct->AdminFee->__toString(); ?></textarea></p>
			<p class="text-center"><img class="text-center" src="module_eportfolio/assets/images/wb11_pg11_img2.png" /></p>
		</div>
	</div>

	<p><br></p>

	<?php if($wb->id != '' && $_SESSION['user']->type == User::TYPE_ASSESSOR && ($wb->wb_status == Workbook::STATUS_LEARNER_COMPLETED || $wb->wb_status == Workbook::STATUS_BEING_CHECKED || $wb->wb_status == Workbook::STATUS_IV_REJECTED)) {?>
	<div class="row well">
		<div class="col-sm-12">
			<div class="box box-success box-solid">
				<div class="box-header with-border"><h3 class="box-title">Assessor Feedback</h3></div>
				<div class="box-body assessorFeedback" <?php echo $feedback->DataProtectionAct->Status->__toString() == 'A'?'style="pointer-events:none;"':''; ?>>
					<div class="form-group">
						<label for="status_DataProtectionAct" class="col-sm-12 control-label">Status:</label>
						<div class="col-sm-12">
							<?php
							echo HTML::selectChosen('status_DataProtectionAct', $answer_status, $feedback->DataProtectionAct->Status->__toString() == 'A'?$feedback->DataProtectionAct->Status->__toString():'', false, true); ?>
						</div>
					</div>
					<div class="form-group">
						<label for="comments_DataProtectionAct" class="col-sm-12 control-label">Comments:</label>
						<div class="col-sm-12">
							<textarea name="comments_DataProtectionAct" rows="7" style="width: 100%;"><?php echo $feedback->DataProtectionAct->Comments->__toString(); ?></textarea>
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
		if($feedback->DataProtectionAct->Status->__toString() == 'NA')
			echo HTML::renderWorkbookIcons('DataProtectionAct', false, 'btn-danger');
		else
			echo HTML::renderWorkbookIcons('DataProtectionAct', false, 'btn-success');
		?>
	</div></div></div>
	<?php echo $wb->getPageBottomLine(); ?>
</div>
<!--.page 2 ends-->

<h1>Legal & Governance and Diversity</h1>

<div class="step-content">
	<?php echo $wb->getPageTopLine(); ?>
	<div class="row"><div class="col-sm-12"><div class="pull-right">
		<?php
		if($feedback->WeightsAndMeasuresAct->Status->__toString() == 'NA')
			echo HTML::renderWorkbookIcons('WeightsAndMeasuresAct', false, 'btn-danger');
		else
			echo HTML::renderWorkbookIcons('WeightsAndMeasuresAct', false, 'btn-success');
		?>
	</div></div></div>



	<div class="col-sm-12">
		<div class="table-responsive">
			<table class="table" <?php echo $feedback->WeightsAndMeasuresAct->Status->__toString() == 'A'?'style="pointer-events:none;"':''; ?>>
				<tr><td colspan="2"><div style="padding: 15px; border: #adff2f 2px solid;"><p class="text-center text-bold">Weights and Measures Act 2015?</p><p class="text-center">An act to consolidate certain enactments relating to weights and measures</p> </div></td></tr>
				<tr><td colspan="2" align="center" class="text-center"><img src="module_eportfolio/assets/images/wb11_img1.png" /></td></tr>
				<tr><td colspan="2" align="center" class="text-center"><div style="background-color: #adff2f; padding: 5px; border: #000000 solid 2px;"><b>What did you find out about this Act? What type of business would it apply to? </b></div> </td></tr>
				<tr><td colspan="2" align="center" class="text-center"><img src="module_eportfolio/assets/images/wb11_img1.png" /></td></tr>
				<tr><td colspan="2" align="center" class="text-center"><textarea rows="5" name="WMAInformation" style="width: 100%;"><?php echo $answers->WeightsAndMeasuresAct->Information->__toString(); ?></textarea> </td></tr>
				<tr><td align="center" class="text-center"><img src="module_eportfolio/assets/images/wb11_img1.png" /></td><td align="center" class="text-center"><img src="module_eportfolio/assets/images/wb11_img1.png" /></td></tr>
				<tr><td align="center" class="text-center text-bold"><div style="background-color: #adff2f; padding: 5px; border: #000000 solid 2px;">How does it protect consumers?</div> </td><td align="center" class="text-center text-bold"><div style="background-color: #adff2f; padding: 5px; border: #000000 solid 2px;">How does it impact a business?</div> </td></tr>
				<tr><td align="center" class="text-center"><img src="module_eportfolio/assets/images/wb11_img1.png" /></td><td align="center" class="text-center"><img src="module_eportfolio/assets/images/wb11_img1.png" /></td></tr>
				<tr><td align="center"><textarea name="WMAHowProtect" style="width: 100%;" rows="7"><?php echo $answers->WeightsAndMeasuresAct->HowProtect->__toString(); ?></textarea> </td><td align="center"><textarea name="WMAHowImpact" style="width: 100%;" rows="7"><?php echo $answers->WeightsAndMeasuresAct->HowImpact->__toString(); ?></textarea> </td></tr>
			</table>
		</div>
	</div>

	<?php if($wb->id != '' && $_SESSION['user']->type == User::TYPE_ASSESSOR && ($wb->wb_status == Workbook::STATUS_LEARNER_COMPLETED || $wb->wb_status == Workbook::STATUS_BEING_CHECKED || $wb->wb_status == Workbook::STATUS_IV_REJECTED)) {?>
	<div class="row well">
		<div class="col-sm-12">
			<div class="box box-success box-solid">
				<div class="box-header with-border"><h3 class="box-title">Assessor Feedback</h3></div>
				<div class="box-body assessorFeedback" <?php echo $feedback->WeightsAndMeasuresAct->Status->__toString() == 'A'?'style="pointer-events:none;"':''; ?>>
					<div class="form-group">
						<label for="status_WeightsAndMeasuresAct" class="col-sm-12 control-label">Status:</label>
						<div class="col-sm-12">
							<?php
							echo HTML::selectChosen('status_WeightsAndMeasuresAct', $answer_status, $feedback->WeightsAndMeasuresAct->Status->__toString() == 'A'?$feedback->WeightsAndMeasuresAct->Status->__toString():'', false, true); ?>
						</div>
					</div>
					<div class="form-group">
						<label for="comments_WeightsAndMeasuresAct" class="col-sm-12 control-label">Comments:</label>
						<div class="col-sm-12">
							<textarea name="comments_WeightsAndMeasuresAct" rows="7" style="width: 100%;"><?php echo $feedback->WeightsAndMeasuresAct->Comments->__toString(); ?></textarea>
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
		if($feedback->WeightsAndMeasuresAct->Status->__toString() == 'NA')
			echo HTML::renderWorkbookIcons('WeightsAndMeasuresAct', false, 'btn-danger');
		else
			echo HTML::renderWorkbookIcons('WeightsAndMeasuresAct', false, 'btn-success');
		?>
	</div></div></div>
	<?php echo $wb->getPageBottomLine(); ?>
</div>
<!--.page 2 ends-->

<h1>Legal & Governance and Diversity</h1>

<div class="step-content">
	<?php echo $wb->getPageTopLine(); ?>
	<div class="row"><div class="col-sm-12"><div class="pull-right">
		<?php
		if($feedback->LicensingLaws->Status->__toString() == 'NA')
			echo HTML::renderWorkbookIcons('LicensingLaws', false, 'btn-danger');
		else
			echo HTML::renderWorkbookIcons('LicensingLaws', false, 'btn-success');
		?>
	</div></div></div>



	<div class="row">
		<div class="col-sm-12">
			<div class="table-responsive">
				<table class="table" <?php echo $feedback->LicensingLaws->Status->__toString() == 'A'?'style="pointer-events:none;"':''; ?>>
					<tr><td colspan="2"><div style="padding: 15px; border: #adff2f 2px solid;"><p class="text-center text-bold">Licensing laws</p><p class="text-center">Licensing laws control where, when and to whom alcohol can be sold or supplied. Licensing is governed by the Licensing Act 2003 in England and Wales, and by the Licensing (Scotland) Act 2005 in Scotland.</p> </div></td></tr>
					<tr><td colspan="2" align="center" class="text-center"><img src="module_eportfolio/assets/images/wb11_img2.png" /></td></tr>
					<tr><td colspan="2" align="center" class="text-center"><div style="background-color: #adff2f; padding: 5px; border: #000000 solid 2px;"><p class="text-bold">What did you find out about this Act? What type of business would it apply to?</p></div> </td></tr>
					<tr><td colspan="2" align="center" class="text-center"><img src="module_eportfolio/assets/images/wb11_img2.png" /></td></tr>
					<tr><td colspan="2" align="center" class="text-center"><textarea rows="5" name="LLInformation" style="width: 100%;"><?php echo $answers->LicensingLaws->Information->__toString(); ?></textarea> </td></tr>
					<tr><td align="center" class="text-center"><img src="module_eportfolio/assets/images/wb11_img2.png" /></td><td align="center" class="text-center"><img src="module_eportfolio/assets/images/wb11_img2.png" /></td></tr>
					<tr><td align="center" class="text-center text-bold"><div style="background-color: #adff2f; padding: 5px; border: #000000 solid 2px;">How does it protect consumers?</div> </td><td align="center" class="text-center text-bold"><div style="background-color: #adff2f; padding: 5px; border: #000000 solid 2px;">How does it impact a business?</div> </td></tr>
					<tr><td align="center" class="text-center"><img src="module_eportfolio/assets/images/wb11_img1.png" /></td><td align="center" class="text-center"><img src="module_eportfolio/assets/images/wb11_img1.png" /></td></tr>
					<tr><td align="center"><textarea name="LLHowProtect" style="width: 100%;" rows="7"><?php echo $answers->LicensingLaws->HowProtect->__toString(); ?></textarea> </td><td align="center"><textarea name="LLHowImpact" style="width: 100%;" rows="7"><?php echo $answers->LicensingLaws->HowImpact->__toString(); ?></textarea> </td></tr>
				</table>
			</div>
		</div>
	</div>

	<?php if($wb->id != '' && $_SESSION['user']->type == User::TYPE_ASSESSOR && ($wb->wb_status == Workbook::STATUS_LEARNER_COMPLETED || $wb->wb_status == Workbook::STATUS_BEING_CHECKED || $wb->wb_status == Workbook::STATUS_IV_REJECTED)) {?>
	<div class="row well">
		<div class="col-sm-12">
			<div class="box box-success box-solid">
				<div class="box-header with-border"><h3 class="box-title">Assessor Feedback</h3></div>
				<div class="box-body assessorFeedback" <?php echo $feedback->LicensingLaws->Status->__toString() == 'A'?'style="pointer-events:none;"':''; ?>>
					<div class="form-group">
						<label for="status_LicensingLaws" class="col-sm-12 control-label">Status:</label>
						<div class="col-sm-12">
							<?php
							echo HTML::selectChosen('status_LicensingLaws', $answer_status, $feedback->LicensingLaws->Status->__toString() == 'A'?$feedback->LicensingLaws->Status->__toString():'', false, true); ?>
						</div>
					</div>
					<div class="form-group">
						<label for="comments_LicensingLaws" class="col-sm-12 control-label">Comments:</label>
						<div class="col-sm-12">
							<textarea name="comments_LicensingLaws" rows="7" style="width: 100%;"><?php echo $feedback->LicensingLaws->Comments->__toString(); ?></textarea>
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
		if($feedback->LicensingLaws->Status->__toString() == 'NA')
			echo HTML::renderWorkbookIcons('LicensingLaws', false, 'btn-danger');
		else
			echo HTML::renderWorkbookIcons('LicensingLaws', false, 'btn-success');
		?>
	</div></div></div>
	<?php echo $wb->getPageBottomLine(); ?>
</div>
<!--.page 2 ends-->

<h1>Legal & Governance and Diversity</h1>

<div class="step-content">
	<?php echo $wb->getPageTopLine(); ?>
	<div class="row"><div class="col-sm-12"><div class="pull-right">
		<?php
		if($feedback->AgeRelatedLegislation->Status->__toString() == 'NA')
			echo HTML::renderWorkbookIcons('AgeRelatedLegislation', false, 'btn-danger');
		else
			echo HTML::renderWorkbookIcons('AgeRelatedLegislation', false, 'btn-success');
		?>
	</div></div></div>



	<div class="row">
		<div class="col-sm-12">
			<div class="table-responsive">
				<table class="table" <?php echo $feedback->AgeRelatedLegislation->Status->__toString() == 'A'?'style="pointer-events:none;"':''; ?>>
					<tr><td><div style="padding: 15px; border: #adff2f 2px solid;"><p class="text-center text-bold">Age related legislation</p><p class="text-center">There are a variety of products in the UK, which by law can only be sold to customers, or by employees who are of a minimum specified age.</p> </div></td></tr>
					<tr><td align="center" class="text-center"><img src="module_eportfolio/assets/images/wb11_img2.png" /></td></tr>
					<tr><td align="center" class="text-center"><div style="background-color: #adff2f; padding: 5px; border: #000000 solid 2px;"><p class="text-bold">How does this legislation protect consumers?</p></div> </td></tr>
					<tr><td align="center" class="text-center"><img src="module_eportfolio/assets/images/wb11_img2.png" /></td></tr>
					<tr><td align="center" class="text-center"><textarea rows="3" name="ARLHowProtect" style="width: 100%;"><?php echo $answers->AgeRelatedLegislation->HowProtect->__toString(); ?></textarea> </td></tr>
					<tr><td align="center" class="text-center"><img src="module_eportfolio/assets/images/wb11_img2.png" /></td></tr>
					<tr><td align="center" class="text-center"><div style="background-color: #adff2f; padding: 5px; border: #000000 solid 2px;"><p class="text-bold">How does it impact the business?</p></div> </td></tr>
					<tr><td align="center" class="text-center"><img src="module_eportfolio/assets/images/wb11_img2.png" /></td></tr>
					<tr><td align="center" class="text-center"><textarea rows="3" name="ARLHowImpact" style="width: 100%;"><?php echo $answers->AgeRelatedLegislation->HowImpact->__toString(); ?></textarea> </td></tr>
					<tr><td align="center" class="text-center"><img src="module_eportfolio/assets/images/wb11_img2.png" /></td></tr>
					<tr><td align="center" class="text-center"><div style="background-color: #adff2f; padding: 5px; border: #000000 solid 2px;"><p class="text-bold">What products do Retailers sell that have age restrictions?</p></div> </td></tr>
					<tr><td align="center" class="text-center"><img src="module_eportfolio/assets/images/wb11_img2.png" /></td></tr>
				</table>	
				<table class="table" <?php echo $feedback->AgeRelatedLegislation->Status->__toString() == 'A'?'style="pointer-events:none;"':''; ?>>
					<tr><th class="text-center">Product</th><th class="text-center">Age</th></tr>
					<?php
					for($i = 1; $i <= 5; $i++)
					{
						$key = 'Set'.$i;
						echo '<tr>';
						echo '<td><input type="text" name="ARL'.$key.'Product" style="width: 100%;" value="'.$answers->AgeRelatedLegislation->AgeRestrictions->$key->Product->__toString() . '" /></td>';
						echo '<td><input type="text" name="ARL'.$key.'Age" style="width: 100%;" value="'.$answers->AgeRelatedLegislation->AgeRestrictions->$key->Age->__toString() . '" /></td>';
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
				<div class="box-body assessorFeedback" <?php echo $feedback->AgeRelatedLegislation->Status->__toString() == 'A'?'style="pointer-events:none;"':''; ?>>
					<div class="form-group">
						<label for="status_AgeRelatedLegislation" class="col-sm-12 control-label">Status:</label>
						<div class="col-sm-12">
							<?php
							echo HTML::selectChosen('status_AgeRelatedLegislation', $answer_status, $feedback->AgeRelatedLegislation->Status->__toString() == 'A'?$feedback->AgeRelatedLegislation->Status->__toString():'', false, true); ?>
						</div>
					</div>
					<div class="form-group">
						<label for="comments_AgeRelatedLegislation" class="col-sm-12 control-label">Comments:</label>
						<div class="col-sm-12">
							<textarea name="comments_AgeRelatedLegislation" rows="7" style="width: 100%;"><?php echo $feedback->AgeRelatedLegislation->Comments->__toString(); ?></textarea>
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
		if($feedback->AgeRelatedLegislation->Status->__toString() == 'NA')
			echo HTML::renderWorkbookIcons('AgeRelatedLegislation', false, 'btn-danger');
		else
			echo HTML::renderWorkbookIcons('AgeRelatedLegislation', false, 'btn-success');
		?>
	</div></div></div>
	<?php echo $wb->getPageBottomLine(); ?>
</div>
<!--.page 2 ends-->

<h1>Legal & Governance and Diversity</h1>

<div class="step-content">
	<?php echo $wb->getPageTopLine(); ?>
	<div class="row"><div class="col-sm-12"><div class="pull-right">
		<?php
		if($feedback->HealthAndSafetyNotes->Status->__toString() == 'NA')
			echo HTML::renderWorkbookIcons('HealthAndSafetyNotes', false, 'btn-danger');
		else
			echo HTML::renderWorkbookIcons('HealthAndSafetyNotes', false, 'btn-success');
		?>
	</div></div></div>



	<div class="row">
		<div class="col-sm-12">
			<p class="text-bold">Health & Safety</p>

			<img class="img-responsive text-center" src="module_eportfolio/assets/images/wb11_pg16_img1.png" />
			<p><span class="text-bold">The Health and Safety at Work etc. Act 1974 </span> (also referred to as HSWA, the HSW Act, the 1974 Act or HASAWA) is the primary piece of legislation covering occupational health and safety in Great Britain. The Health and Safety Executive, with local authorities (and other enforcing authorities) is responsible for enforcing the Act and a number of other Acts and Statutory Instruments relevant to the working environment.</p>
			<p><span class="text-bold">Statutory Instruments</span> are pieces of secondary legislation made under specific Acts of Parliament. These cover a wide range of subjects, including control of asbestos at work and working at height.</p>
			<p>A full list of legislation enforced by HSE (Health & Safety Executive) is available on their website <a href="http://www.hse.gov.uk" target="_blank">www.hse.gov.uk</a></p>
			<p class="text-bold"><img class="pull-left" src="module_eportfolio/assets/images/wb2_img1.png" /> &nbsp; Go to the website <a href="http://www.hse.gov.uk" target="_blank">www.hse.gov.uk</a> and do your own research on Health and Safety. Make some notes below to show the key areas you feel are important.</p>
			<p <?php echo $feedback->HealthAndSafetyNotes->Status->__toString() == 'A'?'style="pointer-events:none;"':''; ?>><textarea rows="5" name="HealthAndSafetyNotes" style="width: 100%;"><?php echo $answers->HealthAndSafetyNotes->__toString(); ?></textarea></p>
			<p class="text-bold">What are the main requirements of Health and Safety legislation?</p>
			<p>Health and safety laws apply to all businesses. An employer, or a self-employed person, is responsible for health and safety in their business. Health and safety laws are there to protect the employer, employees and the public from workplace dangers.</p>
			<p class="text-bold">Health and Safety policy</p>
			<p>If a business has fewer than five employees they don't have to write down their risk assessment or health and safety policy. Over five employees a business must have a written policy which describes how health and safety is to be managed in the business. It will let staff and others know about the commitment to health and safety and should clearly say who does what, when and how.</p>
			<p class="text-bold">Competent person</p>
			<p>An employer must appoint someone competent to help meet their health and safety duties. A competent person is someone with the necessary skills, knowledge and experience to manage health and safety.</p>
		</div>
	</div>

	<?php if($wb->id != '' && $_SESSION['user']->type == User::TYPE_ASSESSOR && ($wb->wb_status == Workbook::STATUS_LEARNER_COMPLETED || $wb->wb_status == Workbook::STATUS_BEING_CHECKED || $wb->wb_status == Workbook::STATUS_IV_REJECTED)) {?>
	<div class="row well">
		<div class="col-sm-12">
			<div class="box box-success box-solid">
				<div class="box-header with-border"><h3 class="box-title">Assessor Feedback</h3></div>
				<div class="box-body assessorFeedback" <?php echo $feedback->HealthAndSafetyNotes->Status->__toString() == 'A'?'style="pointer-events:none;"':''; ?>>
					<div class="form-group">
						<label for="status_HealthAndSafetyNotes" class="col-sm-12 control-label">Status:</label>
						<div class="col-sm-12">
							<?php
							echo HTML::selectChosen('status_HealthAndSafetyNotes', $answer_status, $feedback->HealthAndSafetyNotes->Status->__toString() == 'A'?$feedback->HealthAndSafetyNotes->Status->__toString():'', false, true); ?>
						</div>
					</div>
					<div class="form-group">
						<label for="comments_HealthAndSafetyNotes" class="col-sm-12 control-label">Comments:</label>
						<div class="col-sm-12">
							<textarea name="comments_HealthAndSafetyNotes" rows="7" style="width: 100%;"><?php echo $feedback->HealthAndSafetyNotes->Comments->__toString(); ?></textarea>
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
		if($feedback->HealthAndSafetyNotes->Status->__toString() == 'NA')
			echo HTML::renderWorkbookIcons('HealthAndSafetyNotes', false, 'btn-danger');
		else
			echo HTML::renderWorkbookIcons('HealthAndSafetyNotes', false, 'btn-success');
		?>
	</div></div></div>
	<?php echo $wb->getPageBottomLine(); ?>
</div>
<!--.page 2 ends-->

<h1>Legal & Governance and Diversity</h1>

<div class="step-content">
	<?php echo $wb->getPageTopLine(); ?>
	<div class="row"><div class="col-sm-12"><div class="pull-right">
		<?php
		if($feedback->RiskAssessment->Status->__toString() == 'NA')
			echo HTML::renderWorkbookIcons('RiskAssessment', false, 'btn-danger');
		else
			echo HTML::renderWorkbookIcons('RiskAssessment', false, 'btn-success');
		?>
	</div></div></div>



	<div class="row">
		<div class="col-sm-12">
			<p class="text-bold">Risk assessments</p>
			<p>A risk assessment is required by law and involves evaluating what might cause harm in the workplace and deciding whether reasonable steps are being taken to prevent that harm. </p>
			<p class="text-bold">Step 1: Identify hazards, i.e. anything that may cause harm.</p>
			<p>Employers have a duty to assess the health and safety risks faced by their workers. An employer must systematically check for possible physical, mental, chemical and biological hazards.</p>
			<ul style="margin-left: 15px; margin-bottom: 15px;">
				<li><span class="text-bold">Physical:</span> e.g. lifting, awkward postures, slips and trips, noise, dust, machinery, computer equipment. An ergonomic hazard is a physical factor within the environment that harms the musculoskeletal system. Ergonomic hazards include themes such as repetitive movement, manual handling, workplace/job/task design, uncomfortable workstation height and poor body positioning</li>
				<li><span class="text-bold">Mental:</span>  e.g. excess workload, long hours, working with high-need clients, bullying, etc. These are also called 'psychosocial' hazards, affecting mental health and occurring within working relationships.</li>
			</ul>
			<ul style="margin-left: 15px; margin-bottom: 15px;">
				<li><span class="text-bold">Chemical:</span> e.g. asbestos, cleaning fluids, aerosols, etc.</li>
				<li><span class="text-bold">Biological:</span> including tuberculosis, hepatitis and other infectious diseases faced by healthcare workers, home care staff and other healthcare professionals.</li>
			</ul>
			<p class="text-bold"><br>Step 2: Decide who may be harmed, and how.</p>
			<img class="img-responsive pull-right" src="module_eportfolio/assets/images/wb11_pg17_img1.png" />
			<p>Starting with their employees a business must identify who is at risk. They must also assess risks faced by agency and contract staff, visitors, clients and other members of the public on their premises.</p>
			<p>Employers must review work routines in all the different locations and situations where their employees are employed. For example: In a supermarket, hazards are found in the repetitive tasks at the checkout, in lifting loads, and in slips and trips from spillages and obstacles in the shop and storerooms.</p>
			<p>Employers have special duties towards the health and safety of young workers, disabled employees, night workers, shift workers, and pregnant or breastfeeding women.</p>
			<p class="text-bold">Step 3: Assess the risks and take action.</p>
			<p>Employers must consider how likely it is that each hazard could cause harm. This will determine whether or not they should reduce the level of risk. Even after all precautions have been taken, some risk usually remains. Employers must decide for each remaining hazard whether the risk remains high, medium or low.</p>
			<p class="text-bold">Step 4: Make a record of the findings.</p>
			<p>The record should include details of any hazards noted in the risk assessment, and action taken to reduce or eliminate risk.</p>
			<p>This record provides proof that the assessment was carried out, and is used as the basis for a later review of working practices.</p>
			<p class="text-bold">Step 5: Review the risk assessment.</p>
			<p>A risk assessment must be kept under review in order to:</p>
			<img class="img-responsive pull-right" src="module_eportfolio/assets/images/wb11_pg18_img1.png" />
			<ul style="margin-left: 15px; margin-bottom: 15px;">
				<li>Ensure that agreed safe working practices continue to be applied (e.g. that management's safety instructions are respected by supervisors and line managers); and</li>
				<li>Take account of any new working practices, new machinery or more demanding work targets.</li>
			</ul>
			<p class="text-bold"><img class="img-responsive pull-left" src="module_eportfolio/assets/images/wb2_img1.png" /> &nbsp; Speak to your manager about what risk assessments they have to complete in store. Give details below.</p>
			<table class="table" <?php echo $feedback->RiskAssessment->Status->__toString() == 'A'?'style="pointer-events:none;"':''; ?>>
				<tr><th class="text-center">Risk assessment type</th><th class="text-center">How often is done?</th><th class="text-center">What could happen if it is not done?</th></tr>
				<?php
				for($i = 1; $i <= 3; $i++)
				{
					$key = 'Set'.$i;
					echo '<tr>';
					echo '<td><input type="text" name="RA'.$key.'Type" style="width: 100%;" value="'.$answers->RiskAssessment->InStore->$key->Type->__toString().'" /></td>';
					echo '<td><input type="text" name="RA'.$key.'Frequency" style="width: 100%;" value="'.$answers->RiskAssessment->InStore->$key->Frequency->__toString().'" /></td>';
					echo '<td><input type="text" name="RA'.$key.'Impact" style="width: 100%;" value="'.$answers->RiskAssessment->InStore->$key->Impact->__toString().'" /></td>';
					echo '</tr>';
				}
				?>
			</table>
			<p class="text-bold"><img class="img-responsive pull-left" src="module_eportfolio/assets/images/wb2_img1.png" /> &nbsp; Ask your manager if you can complete a risk assessment and record what you did below.</p>
			<table class="table" <?php echo $feedback->RiskAssessment->Status->__toString() == 'A'?'style="pointer-events:none;"':''; ?>>
				<tr><th class="text-center">Risk assessment</th><th class="text-center">What were your findings?</th><th class="text-center">Any recommendations?</th></tr>
				<tr>
					<td><textarea rows="5" name="YoursAssessment" style="width: 100%;"><?php echo $answers->RiskAssessment->Yours->YoursAssessment->__toString(); ?></textarea> </td>
					<td><textarea rows="5" name="YoursFindings" style="width: 100%;"><?php echo $answers->RiskAssessment->Yours->YoursFindings->__toString(); ?></textarea> </td>
					<td><textarea rows="5" name="YoursRecommendations" style="width: 100%;"><?php echo $answers->RiskAssessment->Yours->YoursRecommendations->__toString(); ?></textarea> </td>
				</tr>
			</table>
		</div>
	</div>

	<?php if($wb->id != '' && $_SESSION['user']->type == User::TYPE_ASSESSOR && ($wb->wb_status == Workbook::STATUS_LEARNER_COMPLETED || $wb->wb_status == Workbook::STATUS_BEING_CHECKED || $wb->wb_status == Workbook::STATUS_IV_REJECTED)) {?>
	<div class="row well">
		<div class="col-sm-12">
			<div class="box box-success box-solid">
				<div class="box-header with-border"><h3 class="box-title">Assessor Feedback</h3></div>
				<div class="box-body assessorFeedback" <?php echo $feedback->RiskAssessment->Status->__toString() == 'A'?'style="pointer-events:none;"':''; ?>>
					<div class="form-group">
						<label for="status_RiskAssessment" class="col-sm-12 control-label">Status:</label>
						<div class="col-sm-12">
							<?php
							echo HTML::selectChosen('status_RiskAssessment', $answer_status, $feedback->RiskAssessment->Status->__toString() == 'A'?$feedback->RiskAssessment->Status->__toString():'', false, true); ?>
						</div>
					</div>
					<div class="form-group">
						<label for="comments_RiskAssessment" class="col-sm-12 control-label">Comments:</label>
						<div class="col-sm-12">
							<textarea name="comments_RiskAssessment" rows="7" style="width: 100%;"><?php echo $feedback->RiskAssessment->Comments->__toString(); ?></textarea>
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
		if($feedback->RiskAssessment->Status->__toString() == 'NA')
			echo HTML::renderWorkbookIcons('RiskAssessment', false, 'btn-danger');
		else
			echo HTML::renderWorkbookIcons('RiskAssessment', false, 'btn-success');
		?>
	</div></div></div>
	<?php echo $wb->getPageBottomLine(); ?>
</div>
<!--.page 2 ends-->

<h1>Legal & Governance and Diversity</h1>

<div class="step-content">
	<?php echo $wb->getPageTopLine(); ?>
	<div class="row"><div class="col-sm-12"><div class="pull-right"><?php echo HTML::renderWorkbookIcons('', true); ?></div></div></div>



	<div class="row">
		<div class="col-sm-6">
			<p class="text-bold">Main requirements of health and safety legislation</p>
			<p class="text-bold">Consult employees</p>
			<p>Employees have to be consulted on health and safety. This can be done by listening and talking to them about:</p>
			<ul style="margin-left: 15px; margin-bottom: 15px;">
				<li>Health and safety and the work they do</li>
				<li>How risks are controlled</li>
				<li>The best ways of providing information and training</li>
			</ul>
			<p>Consultation is a two-way process, allowing employees to raise concerns and influence decisions on the management of health and safety.</p>
			<p class="text-bold">Provide training and information</p>
			<p>Everyone who works for an organisation needs to know how to work safely and without risks to health. Clear instructions, information and adequate training must be provided for all employees. </p>
			<p>Contractors and self-employed people who also work for the organisation must also receive the right level of information on: </p>
			<ul style="margin-left: 15px; margin-bottom: 15px;">
				<li>Hazards and risks they may face, if any </li>
				<li>Measures in place to deal with those hazards and risks, if necessary</li>
				<li>How to follow any emergency procedures.</li>
			</ul>
			<p>Health and safety training should take place during working hours and it must not be paid for by employees.</p>
			<p class="text-bold">Provide the right workplace facilities</p>
			<p>The right workplace facilities for everyone in the workplace, including people with disabilities must be provided.</p>
		</div>
		<div class="col-sm-6">

			<p>Basic things to consider are. Welfare facilities</p>
			<ul style="margin-left: 15px; margin-bottom: 15px;">
				<li>Toilets and hand basins, with soap and towels or a hand-dryer</li>
				<li>Drinking water</li>
				<li>A place to store clothing (and somewhere to change if special clothing is worn for work)</li>
				<li>Somewhere to rest and eat meals.</li>
			</ul>
			<img class="img-responsive pull-left" src="module_eportfolio/assets/images/wb11_pg19_img1.png" />
			<img class="img-responsive pull-left" src="module_eportfolio/assets/images/wb11_pg19_img2.png" />
			<div style="clear: both;"></div>
			<p class="text-bold"><br></p>
			<p class="text-bold">Health issues</p>
			<p>To have a healthy working environment, an employer needs to make sure there is:</p>
			<ul style="margin-left: 15px; margin-bottom: 15px;">
				<li>Good ventilation – a supply of fresh, clean air drawn from outside or a ventilation system</li>
				<li>A reasonable working temperature (usually at least 16°C, or 13°C for strenuous work, unless other laws require lower temperatures)</li>
				<li>Lighting suitable for the work being carried out</li>
				<li>Enough room space and suitable workstations and seating</li>
				<li>A clean workplace with appropriate waste containers</li>
			</ul>
			<p class="text-bold">Safety issues</p>
			<p>To keep the workplace safe an employer must: </p>
			<ul style="margin-left: 15px; margin-bottom: 15px;">
				<li>Properly maintain the premises and work equipment</li>
				<li>Keep floors and traffic routes free from obstruction</li>
				<li>Have windows that can open and be cleaned safely</li>
				<li>Make sure that any transparent (e.g. glass) doors or walls are protected or made of safety material.</li>
			</ul>
		</div>
	</div>

	<p><br></p>

	<div class="row"><div class="col-sm-12"><div class="pull-right"><?php echo HTML::renderWorkbookIcons('', true); ?></div></div></div>
	<?php echo $wb->getPageBottomLine(); ?>
</div>
<!--.page 2 ends-->

<h1>Legal & Governance and Diversity</h1>

<div class="step-content">
	<?php echo $wb->getPageTopLine(); ?>
	<div class="row"><div class="col-sm-12"><div class="pull-right">
		<?php
		if($feedback->FirstAid->Status->__toString() == 'NA')
			echo HTML::renderWorkbookIcons('FirstAid', false, 'btn-danger');
		else
			echo HTML::renderWorkbookIcons('FirstAid', false, 'btn-success');
		?>
	</div></div></div>



	<div class="row">
		<div class="col-sm-6">

			<p class="text-bold">First aid</p>
			<p>There must be first-aid arrangements in all workplaces. An employer is responsible for making sure that employees receive immediate attention if they are taken ill or are injured at work. Accidents and illness can happen at any time and first aid can save lives and prevent minor injuries from becoming major ones. </p>
			<p>Arrangements will depend on the particular circumstances in each workplace.</p>
			<p>As a minimum, a workplace must have: </p>
			<ul style="margin-left: 15px; margin-bottom: 15px;">
				<li>A suitably stocked first-aid box</li>
				<li>An appointed person to take charge of first-aid arrangements</li>
				<li>Information for all employees giving details of first-aid arrangements</li>
			</ul>
			<p class="text-center"> <img class="img-responsive" src="module_eportfolio/assets/images/wb11_pg20_img1.png" /></p>
			<p class="text-bold">Accidents and ill health</p>
			<p>Under health and safety law, an employer must report and keep a record of certain injuries, incidents and cases of work-related disease.</p>
			<p>Keeping records will help to identify patterns of accidents and injuries, and will help when completing risk assessments.</p>
			<img class="img-responsive text-center" src="module_eportfolio/assets/images/wb11_pg20_img2.png" />
			<p><br></p>
			<p class="text-bold"><img class="img-responsive" src="module_eportfolio/assets/images/wb2_img1.png" /> &nbsp; What does RIDDOR stand for?</p>
			<p <?php echo $feedback->FirstAid->Status->__toString() == 'A'?'style="pointer-events:none;"':''; ?>><textarea name="RIDDOR" style="width: 100%;"><?php echo $answers->FirstAid->RIDDOR->__toString(); ?></textarea></p>
		</div>
		<div class="col-sm-6">
			<p class="text-bold">Display the health and safety law poster</p>
			<img class="img-responsive pull-right" src="module_eportfolio/assets/images/wb11_pg20_img3.png" />
			<p>If a company or person employs anyone else, they must display the health and safety law poster where workers can easily read it.</p>
			<p>The poster outlines British health and safety laws and includes a straightforward list that tells workers what they and their employers need to do. Details of any employee safety representatives or health and safety contacts can also be added</p>
			<p class="text-bold"><img class="img-responsive pull-left" src="module_eportfolio/assets/images/wb2_img1.png" /> &nbsp; Where is your H&S law poster displayed? </p>
			<p <?php echo $feedback->FirstAid->Status->__toString() == 'A'?'style="pointer-events:none;"':''; ?>><textarea name="Poster" style="width: 100%;"><?php echo $answers->FirstAid->Poster->__toString(); ?></textarea></p>
			<p class="text-bold">Get insurance </p>
			<p>If a business has employees they will probably need employers' liability insurance.</p>
			<p>If an employee is injured or becomes ill as a result of the work they do, they can claim compensation. As long as reasonable steps have been taken to prevent accidents or harm to employees (and the injury or illness was caused after 1 October 2013), an employer shouldn't have to pay compensation.</p>
			<p class="text-bold">Keep up to date</p>
			<p>It is a good idea to follow news and events in the industry to help keep knowledge of health and safety policies and risk assessments up to date.</p>
		</div>
	</div>

	<?php if($wb->id != '' && $_SESSION['user']->type == User::TYPE_ASSESSOR && ($wb->wb_status == Workbook::STATUS_LEARNER_COMPLETED || $wb->wb_status == Workbook::STATUS_BEING_CHECKED || $wb->wb_status == Workbook::STATUS_IV_REJECTED)) {?>
	<div class="row well">
		<div class="col-sm-12">
			<div class="box box-success box-solid">
				<div class="box-header with-border"><h3 class="box-title">Assessor Feedback</h3></div>
				<div class="box-body assessorFeedback" <?php echo $feedback->FirstAid->Status->__toString() == 'A'?'style="pointer-events:none;"':''; ?>>
					<div class="form-group">
						<label for="status_FirstAid" class="col-sm-12 control-label">Status:</label>
						<div class="col-sm-12">
							<?php
							echo HTML::selectChosen('status_FirstAid', $answer_status, $feedback->FirstAid->Status->__toString() == 'A'?$feedback->FirstAid->Status->__toString():'', false, true); ?>
						</div>
					</div>
					<div class="form-group">
						<label for="comments_FirstAid" class="col-sm-12 control-label">Comments:</label>
						<div class="col-sm-12">
							<textarea name="comments_FirstAid" rows="7" style="width: 100%;"><?php echo $feedback->FirstAid->Comments->__toString(); ?></textarea>
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
		if($feedback->FirstAid->Status->__toString() == 'NA')
			echo HTML::renderWorkbookIcons('FirstAid', false, 'btn-danger');
		else
			echo HTML::renderWorkbookIcons('FirstAid', false, 'btn-success');
		?>
	</div></div></div>
	<?php echo $wb->getPageBottomLine(); ?>
</div>
<!--.page 2 ends-->

<h1>Legal & Governance and Diversity</h1>

<div class="step-content">
	<?php echo $wb->getPageTopLine(); ?>
	<div class="row"><div class="col-sm-12"><div class="pull-right">
		<?php
		if($feedback->HSVideo->Status->__toString() == 'NA')
			echo HTML::renderWorkbookIcons('HSVideo', false, 'btn-danger');
		else
			echo HTML::renderWorkbookIcons('HSVideo', false, 'btn-success');
		?>
	</div></div></div>



	<div class="row">
		<div class="col-sm-12">

			<p class="text-bold">For this next exercise you will need to revisit the Health and Safety training you undertook when you first started in your role. You should have watched a number of videos on the Hub already but it never hurts to refresh your memory!</p>
			<div>
				<p class="text-bold"><img class="img-responsive pull-left" src="module_eportfolio/assets/images/wb2_img1.png" /> &nbsp; Log on to the Hub >> Departments >> Health and Safety >> Stores </p>
				<p class="text-bold">Here you will find information and the Health and Safety videos. Watch the videos and answer the questions below.</p>
			</div>
			<div class="table-responsive">
				<table class="table table-bordered table-striped" <?php echo $feedback->HSVideo->Status->__toString() == 'A'?'style="pointer-events:none;"':''; ?>>
					<tr><th style="width: 30%;">H&S video</th><th style="width: 70%;"></th></tr>
					<tr>
						<td><p class="text-bold">Equipment Safety</p><img class="img-responsive" src="module_eportfolio/assets/images/wb11_pg21_img1.png" /></td>
						<td>
							<table class="table">
								<tr>
									<th style="width: 40%;">1. If you had a bulky load which you need to put on a high shelf what should you do?</th>
									<td style="width: 60%;"><textarea name="HSVQuestion1" style="width: 100%;"><?php echo $answers->HSVideo->Question1->__toString(); ?></textarea> </td>
								</tr>
								<tr>
									<th>2. What should you check for on a kick stool to ensure it is safe?</th>
									<td><textarea name="HSVQuestion2" style="width: 100%;"><?php echo $answers->HSVideo->Question2->__toString(); ?></textarea> </td>
								</tr>
								<tr>
									<th>3. If a cage is unsafe what do you have to do?</th>
									<td><textarea name="HSVQuestion3" style="width: 100%;"><?php echo $answers->HSVideo->Question3->__toString(); ?></textarea> </td>
								</tr>
							</table>
						</td>
					</tr>
					<tr>
						<td><p class="text-bold">Fire Safety</p><img class="img-responsive" src="module_eportfolio/assets/images/wb11_pg21_img2.png" /></td>
						<td>
							<table class="table">
								<tr>
									<th style="width: 40%;">1. Where should you be able to find your stores Fire Evacuation Plan?</th>
									<td style="width: 60%;"><textarea name="HSVQuestion4" style="width: 100%;"><?php echo $answers->HSVideo->Question4->__toString(); ?></textarea> </td>
								</tr>
								<tr>
									<th>2. In relation to fire safety you should know the location of 3 things. What are they?</th>
									<td><textarea name="HSVQuestion5" style="width: 100%;"><?php echo $answers->HSVideo->Question5->__toString(); ?></textarea> </td>
								</tr>
								<tr>
									<th>3. How often should fire drills take place?</th>
									<td><textarea name="HSVQuestion6" style="width: 100%;"><?php echo $answers->HSVideo->Question6->__toString(); ?></textarea> </td>
								</tr>
							</table>
						</td>
					</tr>
					<tr>
						<td><p class="text-bold">First Aid</p><img class="img-responsive" src="module_eportfolio/assets/images/wb11_pg21_img3.png" /></td>
						<td>
							<table class="table">
								<tr>
									<th style="width: 40%;">1. Where should information about first aiders, appointed person and location of the first aid box be displayed?</th>
									<td style="width: 60%;"><textarea name="HSVQuestion7" style="width: 100%;"><?php echo $answers->HSVideo->Question7->__toString(); ?></textarea> </td>
								</tr>
								<tr>
									<th>2. What should not be kept in the First Aid box?</th>
									<td><textarea name="HSVQuestion8" style="width: 100%;"><?php echo $answers->HSVideo->Question8->__toString(); ?></textarea> </td>
								</tr>
								<tr>
									<th>3. If you witness an incident what should you check for and what should you do?</th>
									<td><textarea name="HSVQuestion9" style="width: 100%;"><?php echo $answers->HSVideo->Question9->__toString(); ?></textarea> </td>
								</tr>
							</table>
						</td>
					</tr>
					<tr>
						<td><p class="text-bold">Manual handling</p><img class="img-responsive" src="module_eportfolio/assets/images/wb11_pg22_img1.png" /></td>
						<td>
							<table class="table">
								<tr>
									<th style="width: 40%;">1. What is manual handling?</th>
									<td style="width: 60%;"><textarea name="HSVQuestion10" style="width: 100%;"><?php echo $answers->HSVideo->Question10->__toString(); ?></textarea> </td>
								</tr>
								<tr>
									<th>2. What injuries could occur if you don't follow good manual handling techniques?</th>
									<td><textarea name="HSVQuestion11" style="width: 100%;"><?php echo $answers->HSVideo->Question11->__toString(); ?></textarea> </td>
								</tr>
								<tr>
									<th>3. What is the correct way to move a cage?Push or pull?</th>
									<td><textarea name="HSVQuestion12" style="width: 100%;"><?php echo $answers->HSVideo->Question12->__toString(); ?></textarea> </td>
								</tr>
							</table>
						</td>
					</tr>
					<tr>
						<td><p class="text-bold">Slips, Trips, and Falls</p><img class="img-responsive" src="module_eportfolio/assets/images/wb11_pg22_img2.png" /></td>
						<td>
							<table class="table">
								<tr>
									<th style="width: 40%;">1. Name 2 of the main causes of slips and trips.</th>
									<td style="width: 60%;"><textarea name="HSVQuestion13" style="width: 100%;"><?php echo $answers->HSVideo->Question13->__toString(); ?></textarea> </td>
								</tr>
								<tr>
									<th>2. How should you deal with a spillage?</th>
									<td><textarea name="HSVQuestion14" style="width: 100%;"><?php echo $answers->HSVideo->Question14->__toString(); ?></textarea> </td>
								</tr>
								<tr>
									<th>3. What is the telephone number of the Accident Reporting line?</th>
									<td><textarea name="HSVQuestion15" style="width: 100%;"><?php echo $answers->HSVideo->Question15->__toString(); ?></textarea> </td>
								</tr>
							</table>
						</td>
					</tr>
					<tr>
						<td><p class="text-bold">Visitors / Contractors</p><img class="img-responsive" src="module_eportfolio/assets/images/wb11_pg22_img3.png" /></td>
						<td>
							<table class="table">
								<tr>
									<th style="width: 40%;">1. What should you ask a contractor for when they arrive in your store?</th>
									<td style="width: 60%;"><textarea name="HSVQuestion16" style="width: 100%;"><?php echo $answers->HSVideo->Question16->__toString(); ?></textarea> </td>
								</tr>
								<tr>
									<th>2. What book should all contractors complete?</th>
									<td><textarea name="HSVQuestion17" style="width: 100%;"><?php echo $answers->HSVideo->Question17->__toString(); ?></textarea> </td>
								</tr>
								<tr>
									<th>3. What register should a contractor be shown before they start work?</th>
									<td><textarea name="HSVQuestion18" style="width: 100%;"><?php echo $answers->HSVideo->Question18->__toString(); ?></textarea> </td>
								</tr>
							</table>
						</td>
					</tr>
					<tr>
						<td><p class="text-bold">Waste Management</p><img class="img-responsive" src="module_eportfolio/assets/images/wb11_pg23_img1.png" /></td>
						<td>
							<table class="table">
								<tr>
									<th style="width: 40%;">1. How many different types of waste are there in our stores?</th>
									<td style="width: 60%;"><textarea name="HSVQuestion19" style="width: 100%;"><?php echo $answers->HSVideo->Question19->__toString(); ?></textarea> </td>
								</tr>
								<tr>
									<th>2. Name the 2 types of waste which can't be recycled.</th>
									<td><textarea name="HSVQuestion20" style="width: 100%;"><?php echo $answers->HSVideo->Question20->__toString(); ?></textarea> </td>
								</tr>
								<tr>
									<th>3. Why should your team ensure your waste is separated before it is returned to the DC?</th>
									<td><textarea name="HSVQuestion21" style="width: 100%;"><?php echo $answers->HSVideo->Question21->__toString(); ?></textarea> </td>
								</tr>
							</table>
						</td>
					</tr>
					<tr>
						<td><p class="text-bold">Pharmacy Safety</p><img class="img-responsive" src="module_eportfolio/assets/images/wb11_pg23_img2.png" /></td>
						<td>
							<table class="table">
								<tr>
									<th style="width: 40%;">1. Identify a potential health and safety hazard behind the pharmacy counter?</th>
									<td style="width: 60%;"><textarea name="HSVQuestion22" style="width: 100%;"><?php echo $answers->HSVideo->Question22->__toString(); ?></textarea> </td>
								</tr>
								<tr>
									<th>2. Name one thing you need to do when you receive returned medicines in the pharmacy.</th>
									<td><textarea name="HSVQuestion23" style="width: 100%;"><?php echo $answers->HSVideo->Question23->__toString(); ?></textarea> </td>
								</tr>
								<tr>
									<th>3. What should you do if you were presented with an unprotected needle?</th>
									<td><textarea name="HSVQuestion24" style="width: 100%;"><?php echo $answers->HSVideo->Question24->__toString(); ?></textarea> </td>
								</tr>
							</table>
						</td>
					</tr>
				</table>
				<p class="text-bold"><img class="img-responsive pull-left" src="module_eportfolio/assets/images/wb2_img1.png" />Finally who is your Regional contact within the Health and Safety team?</p>
				<td><textarea <?php echo $feedback->HSVideo->Status->__toString() == 'A'?'style="pointer-events:none;"':''; ?> name="RegionalHSContact" style="width: 100%;"><?php echo $answers->HSVideo->RegionalHSContact->__toString(); ?></textarea> </td>
				<img class="img-responsive" src="module_eportfolio/assets/images/wb11_pg23_img3.png" />
			</div>
		</div>
	</div>

	<?php if($wb->id != '' && $_SESSION['user']->type == User::TYPE_ASSESSOR && ($wb->wb_status == Workbook::STATUS_LEARNER_COMPLETED || $wb->wb_status == Workbook::STATUS_BEING_CHECKED || $wb->wb_status == Workbook::STATUS_IV_REJECTED)) {?>
	<div class="row well">
		<div class="col-sm-12">
			<div class="box box-success box-solid">
				<div class="box-header with-border"><h3 class="box-title">Assessor Feedback</h3></div>
				<div class="box-body assessorFeedback" <?php echo $feedback->HSVideo->Status->__toString() == 'A'?'style="pointer-events:none;"':''; ?>>
					<div class="form-group">
						<label for="status_HSVideo" class="col-sm-12 control-label">Status:</label>
						<div class="col-sm-12">
							<?php
							echo HTML::selectChosen('status_HSVideo', $answer_status, $feedback->HSVideo->Status->__toString() == 'A'?$feedback->HSVideo->Status->__toString():'', false, true); ?>
						</div>
					</div>
					<div class="form-group">
						<label for="comments_HSVideo" class="col-sm-12 control-label">Comments:</label>
						<div class="col-sm-12">
							<textarea name="comments_HSVideo" rows="7" style="width: 100%;"><?php echo $feedback->HSVideo->Comments->__toString(); ?></textarea>
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
		if($feedback->HSVideo->Status->__toString() == 'NA')
			echo HTML::renderWorkbookIcons('HSVideo', false, 'btn-danger');
		else
			echo HTML::renderWorkbookIcons('HSVideo', false, 'btn-success');
		?>
	</div></div></div>
	<?php echo $wb->getPageBottomLine(); ?>
</div>
<!--.page 2 ends-->

<h1>Legal & Governance and Diversity</h1>

<div class="step-content">
	<?php echo $wb->getPageTopLine(); ?>
	<div class="row"><div class="col-sm-12"><div class="pull-right">
		<?php
		if($feedback->SecurityMeasures->Status->__toString() == 'NA')
			echo HTML::renderWorkbookIcons('SecurityMeasures', false, 'btn-danger');
		else
			echo HTML::renderWorkbookIcons('SecurityMeasures', false, 'btn-success');
		?>
	</div></div></div>



	<div class="row">
		<div class="col-sm-12">
			<p class="text-bold">The main requirements of legislation in relation to security</p>
			<img class="img-responsive pull-right" src="module_eportfolio/assets/images/wb11_pg24_img1.png" />
			<p class="text-bold">Management of Health at Work Regulations (MHSWR) 1999</p>
			<p>These regulations require employers to consider the health and safety risks to employees and to carry out a risk assessment to protect employees from exposure to reasonably foreseeable risks.</p>
			<p class="text-bold">Those risks include work related violence.</p>
			<p class="text-bold">Hierarchy of control</p>
			<p>The law requires an employer to carry out risk reduction using a clear hierarchy of controls. Where it is reasonably practicable to do so they should always adopt the following controls in descending order of priority:</p>
			<ol style="margin-left: 15px; margin-bottom: 15px;">
				<li>Eliminate the risk completely by removing the hazard, e.g. arrange for a competent security company to handle cash-in-transit procedures and exclude known troublemakers.</li>
				<li>Where elimination is not possible or reasonably practicable in the circumstances, substitute a hazardous activity of process with one which is less hazardous or use improved equipment or technical solutions that reduces the level of risk. E.g. ensure that high value goods are effectively tagged; use CCTV or improved surveillance by staff.</li>
				<li>If this is not possible they should redesign the equipment or work activity to reduce the risks. E.g. arrange for cash handling to be carried out in a secure area or when the premises are closed to the public rather than in front of customers; redesign the layout of the workplace to give better lines of sight and better CCTV coverage.</li>
				<li>If redesign is not possible it may be reasonably practicable to physically remove exposed people from risk. E.g. help to provide safe transport home for staff working late.</li>
				<li>Finally an employer should put in controls such as training, safe systems of work and personal protective measures.</li>
			</ol>
			<p class="text-bold"><img class="img-responsive pull-left" src="module_eportfolio/assets/images/wb2_img1.png" /> List 3 security measures and procedures that you follow in your store.</p>
			<table class="table" <?php echo $feedback->SecurityMeasures->Status->__toString() == 'A'?'style="pointer-events:none;"':''; ?>>
				<tr><th>Security measure</th><th>Why is this in place?</th></tr>
				<?php
				for($i = 1; $i <= 3; $i++)
				{
					$key = 'Set'.$i;
					echo '<tr>';
					echo '<td><textarea name="SM'.$key.'Measure" style="width: 100%;">'.$answers->SecurityMeasures->YouFollowInStore->$key->Measure.'</textarea> </td>';
					echo '<td><textarea name="SM'.$key.'Why" style="width: 100%;">'.$answers->SecurityMeasures->YouFollowInStore->$key->Why.'</textarea> </td>';
					echo '</tr>';
				}
				?>
			</table>
		</div>
	</div>

	<p><br></p>
	<div class="row">
		<div class="col-sm-6">
			<p class="text-bold">Safeguarding your own personal security</p>
			<p>The personal safety of you, your colleagues and members of the general public is more important than the security of the business and its property.</p>
			<p class="text-bold"><img class="img-responsive pull-left" src="module_eportfolio/assets/images/wb2_img1.png" /> What security measures or procedures are in place to protect you, your colleagues and the public from harm?</p>
			<p <?php echo $feedback->SecurityMeasures->Status->__toString() == 'A'?'style="pointer-events:none;"':''; ?> ><textarea name="OtherMeasuresForProtection" style="width: 100%;"><?php echo $answers->SecurityMeasures->OtherMeasuresForProtection->__toString(); ?></textarea></p>
		</div>
		<div class="col-sm-6">
			<p class="text-bold">You should ensure that you:</p>
			<ul style="margin-left: 15px; margin-bottom: 15px;">
				<li>Assess the risks involved in any action you intend to take</li>
				<li>Avoid dangerous situations</li>
				<li>Put your personal security before the security of property</li>
				<li>Get help from an appropriate person as soon as possible</li>
				<li>Keep safe</li>
			</ul>
			<img class="img-responsive pull-left" src="module_eportfolio/assets/images/wb11_pg25_img1.png" />
		</div>
		<div class="col-sm-12" <?php echo $feedback->SecurityMeasures->Status->__toString() == 'A'?'style="pointer-events:none;"':''; ?>>
			<p class="text-bold">Dealing with internal and external theft</p>
			<p class="text-bold"><img class="img-responsive pull-left" src="module_eportfolio/assets/images/wb2_img1.png" /> What actions should you take if you observe a member of the public stealing goods?If you are unsure speak to your manager or mentor.</p>
			<textarea name="CustomerStealing" style="width: 100%;"><?php echo $answers->SecurityMeasures->CustomerStealing->__toString(); ?></textarea>
			<p class="text-bold"><img class="img-responsive pull-left" src="module_eportfolio/assets/images/wb2_img1.png" /> What types of behaviour or signs have you come across that could suggest a customer was acting dishonestly?</p>
			<textarea name="CustomerActDishonestly" style="width: 100%;"><?php echo $answers->SecurityMeasures->CustomerActDishonestly->__toString(); ?></textarea>
			<p class="text-bold"><img class="img-responsive pull-left" src="module_eportfolio/assets/images/wb2_img1.png" /> What action should you take if you observe a member of your store team stealing?</p>
			<textarea name="TeamMemberStealing" style="width: 100%;"><?php echo $answers->SecurityMeasures->TeamMemberStealing->__toString(); ?></textarea>
		</div>
	</div>

	<?php if($wb->id != '' && $_SESSION['user']->type == User::TYPE_ASSESSOR && ($wb->wb_status == Workbook::STATUS_LEARNER_COMPLETED || $wb->wb_status == Workbook::STATUS_BEING_CHECKED || $wb->wb_status == Workbook::STATUS_IV_REJECTED)) {?>
	<div class="row well">
		<div class="col-sm-12">
			<div class="box box-success box-solid">
				<div class="box-header with-border"><h3 class="box-title">Assessor Feedback</h3></div>
				<div class="box-body assessorFeedback" <?php echo $feedback->SecurityMeasures->Status->__toString() == 'A'?'style="pointer-events:none;"':''; ?>>
					<div class="form-group">
						<label for="status_SecurityMeasures" class="col-sm-12 control-label">Status:</label>
						<div class="col-sm-12">
							<?php
							echo HTML::selectChosen('status_SecurityMeasures', $answer_status, $feedback->SecurityMeasures->Status->__toString() == 'A'?$feedback->SecurityMeasures->Status->__toString():'', false, true); ?>
						</div>
					</div>
					<div class="form-group">
						<label for="comments_SecurityMeasures" class="col-sm-12 control-label">Comments:</label>
						<div class="col-sm-12">
							<textarea name="comments_SecurityMeasures" rows="7" style="width: 100%;"><?php echo $feedback->SecurityMeasures->Comments->__toString(); ?></textarea>
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
		if($feedback->SecurityMeasures->Status->__toString() == 'NA')
			echo HTML::renderWorkbookIcons('SecurityMeasures', false, 'btn-danger');
		else
			echo HTML::renderWorkbookIcons('SecurityMeasures', false, 'btn-success');
		?>
	</div></div></div>
	<?php echo $wb->getPageBottomLine(); ?>
</div>
<!--.page 2 ends-->

<h1>Legal & Governance and Diversity</h1>

<div class="step-content">
	<?php echo $wb->getPageTopLine(); ?>
	<div class="row"><div class="col-sm-12"><div class="pull-right">
		<?php
		if($feedback->DealingEmergency->Status->__toString() == 'NA')
			echo HTML::renderWorkbookIcons('DealingEmergency', false, 'btn-danger');
		else
			echo HTML::renderWorkbookIcons('DealingEmergency', false, 'btn-success');
		?>
	</div></div></div>

	<div class="row">
		<div class="col-sm-12">

			<p class="text-bold">Dealing with accidents and emergencies</p>

			<table class="table" <?php echo $feedback->DealingEmergency->Status->__toString() == 'A'?'style="pointer-events:none;"':''; ?>>
				<tr><td align="center"><div style="padding: 15px; border: red 5px dashed;"><p><span class="text-bold">An Emergency</span> is a serious, unexpected, and often dangerous situation requiring immediate action</p></div></td></tr>
				<tr><td align="center" class="text-center"><img src="module_eportfolio/assets/images/wb11_pg26_img1.png" /></td></tr>
				<tr><td align="center" class="text-center"><div style="padding: 5px; border: red solid 2px;"><p>There are a number of different situations that could be classed as an emergency</p><p>Can you name them below and briefly explain what you would do in each situation?</p></div> </td></tr>
				<tr><td align="center" class="text-center"><img src="module_eportfolio/assets/images/wb11_pg26_img1.png" /></td></tr>
				<tr><td align="center" class="text-center"><textarea name="DealingEmergency" style="width: 100%;" rows="10"><?php echo $answers->DealingEmergency->__toString(); ?></textarea> </td></tr>
			</table>
		</div>
	</div>

	<?php if($wb->id != '' && $_SESSION['user']->type == User::TYPE_ASSESSOR && ($wb->wb_status == Workbook::STATUS_LEARNER_COMPLETED || $wb->wb_status == Workbook::STATUS_BEING_CHECKED || $wb->wb_status == Workbook::STATUS_IV_REJECTED)) {?>
	<div class="row well">
		<div class="col-sm-12">
			<div class="box box-success box-solid">
				<div class="box-header with-border"><h3 class="box-title">Assessor Feedback</h3></div>
				<div class="box-body assessorFeedback" <?php echo $feedback->DealingEmergency->Status->__toString() == 'A'?'style="pointer-events:none;"':''; ?>>
					<div class="form-group">
						<label for="status_DealingEmergency" class="col-sm-12 control-label">Status:</label>
						<div class="col-sm-12">
							<?php
							echo HTML::selectChosen('status_DealingEmergency', $answer_status, $feedback->DealingEmergency->Status->__toString() == 'A'?$feedback->DealingEmergency->Status->__toString():'', false, true); ?>
						</div>
					</div>
					<div class="form-group">
						<label for="comments_DealingEmergency" class="col-sm-12 control-label">Comments:</label>
						<div class="col-sm-12">
							<textarea name="comments_DealingEmergency" rows="7" style="width: 100%;"><?php echo $feedback->DealingEmergency->Comments->__toString(); ?></textarea>
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
		if($feedback->DealingEmergency->Status->__toString() == 'NA')
			echo HTML::renderWorkbookIcons('DealingEmergency', false, 'btn-danger');
		else
			echo HTML::renderWorkbookIcons('DealingEmergency', false, 'btn-success');
		?>
	</div></div></div>
	<?php echo $wb->getPageBottomLine(); ?>
</div>
<!--.page 2 ends-->

<h1>Legal & Governance and Diversity</h1>

<div class="step-content">
	<?php echo $wb->getPageTopLine(); ?>
	<div class="row"><div class="col-sm-12"><div class="pull-right">
		<?php
		if($feedback->CaseStudy->Status->__toString() == 'NA')
			echo HTML::renderWorkbookIcons('CaseStudy', false, 'btn-danger');
		else
			echo HTML::renderWorkbookIcons('CaseStudy', false, 'btn-success');
		?>
	</div></div></div>



	<div class="row">
		<div class="col-sm-6">
			<p class="text-bold">Fire safety</p>
			<p>Fires need three things to start</p>
			<ul style="margin-left: 15px; margin-bottom: 15px;">
				<li>A source of ignition (heat)</li>
				<li>A source of fuel (something that burns)</li>
				<li>Oxygen</li>
			</ul>
			<img class="img-responsive" src="module_eportfolio/assets/images/wb11_pg27_img1.png" />
		</div>
		<div class="col-sm-6">
			<p>Sources of ignition include heaters, lighting, naked flames, electrical equipment, smokers' materials (cigarettes, matches etc.) and anything else that can get very hot or cause sparks.</p>
			<p>Sources of fuel include wood, paper, plastic, rubber or foam, loose packaging materials, waste rubbish and furniture.</p>
			<p>Sources of oxygen include the air around us.</p>
		</div>
		<div class="col-sm-12">
			<div style="margin: 10px; padding: 10px; background-color: #90ee90;" class="text-bold">
				<p class="text-center">Case Study</p>
				<p><i>A shopkeeper regularly threw packing waste by the back door of his shop as he quickly stocked the shelves after a delivery. His workers sometimes opened the back door to have a cigarette break outside.</i></p>
				<p><i> One week he'd left the pile of rubbish for several days and a discarded cigarette butt caused it to catch fire. By the time the fire was spotted and put out, it had caused substantial damage to his back door and his shelving units. There was a significant cost in damaged stock and repairs. </i></p>
				<p class="text-bold"><img class="img-responsive pull-left" src="module_eportfolio/assets/images/wb2_img1.png" /> &nbsp; How could the fire have been prevented? What should the shopkeeper have done?</p>
				<p <?php echo $feedback->CaseStudy->Status->__toString() == 'A'?'style="pointer-events:none;"':''; ?>><textarea rows="5" name="CaseStudy" style="margin-top: 5px; width: 100%;"><?php echo $answers->CaseStudy->__toString(); ?></textarea></p>
			</div>
			<p class="text-bold">What do employers have to do?</p>
			<p>Employers (and / or building owners or occupiers) must carry out a fire safety risk assessment and keep it up to date.</p>
			<p>Based on the findings of the assessment, employers need to ensure that adequate and appropriate fire safety measures are in place to minimise the risk of injury or loss of life in the event of a fire.</p>
			<p>To help prevent fire in the workplace, the risk assessment should identify what could cause a fire to start, i.e. sources of ignition (heat or sparks) substances that burn and the people who may be at risk.</p>
			<img class="img-responsive pull-left" src="module_eportfolio/assets/images/wb11_pg27_img2.png" /> &nbsp;
			<img class="img-responsive pull-right" src="module_eportfolio/assets/images/wb11_pg27_img3.png" />
		</div>
	</div>

	<?php if($wb->id != '' && $_SESSION['user']->type == User::TYPE_ASSESSOR && ($wb->wb_status == Workbook::STATUS_LEARNER_COMPLETED || $wb->wb_status == Workbook::STATUS_BEING_CHECKED || $wb->wb_status == Workbook::STATUS_IV_REJECTED)) {?>
	<div class="row well">
		<div class="col-sm-12">
			<div class="box box-success box-solid">
				<div class="box-header with-border"><h3 class="box-title">Assessor Feedback</h3></div>
				<div class="box-body assessorFeedback" <?php echo $feedback->CaseStudy->Status->__toString() == 'A'?'style="pointer-events:none;"':''; ?>>
					<div class="form-group">
						<label for="status_CaseStudy" class="col-sm-12 control-label">Status:</label>
						<div class="col-sm-12">
							<?php
							echo HTML::selectChosen('status_CaseStudy', $answer_status, $feedback->CaseStudy->Status->__toString() == 'A'?$feedback->CaseStudy->Status->__toString():'', false, true); ?>
						</div>
					</div>
					<div class="form-group">
						<label for="comments_CaseStudy" class="col-sm-12 control-label">Comments:</label>
						<div class="col-sm-12">
							<textarea name="comments_CaseStudy" rows="7" style="width: 100%;"><?php echo $feedback->CaseStudy->Comments->__toString(); ?></textarea>
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
		if($feedback->CaseStudy->Status->__toString() == 'NA')
			echo HTML::renderWorkbookIcons('CaseStudy', false, 'btn-danger');
		else
			echo HTML::renderWorkbookIcons('CaseStudy', false, 'btn-success');
		?>
	</div></div></div>
	<?php echo $wb->getPageBottomLine(); ?>
</div>
<!--.page 2 ends-->

<h1>Legal & Governance and Diversity</h1>

<div class="step-content">
	<?php echo $wb->getPageTopLine(); ?>
	<div class="row"><div class="col-sm-12"><div class="pull-right">
		<?php
		if($feedback->FloorPlan->Status->__toString() == 'NA')
			echo HTML::renderWorkbookIcons('FloorPlan', false, 'btn-danger');
		else
			echo HTML::renderWorkbookIcons('FloorPlan', false, 'btn-success');
		?>
	</div></div></div>



	<div class="row">
		<div class="col-sm-12">
			<p>Once risks have been identified, appropriate action can be taken to control them.</p>
			<ul style=" margin-left: 15px; margin-bottom: 15px;">
				<li>A fire safety risk assessment should be carried out</li>
				<li>Sources of ignition and flammable substances should be kept apart</li>
				<li>Accidental fires should be avoided, e.g. make sure heaters cannot be knocked over</li>
				<li>Ensure good housekeeping at all times, e.g. avoid build-up of rubbish that could burn</li>
				<li>Consider how to detect fires and how to warn people quickly if they start, e.g. installing smoke alarms and fire alarms or bells</li>
				<li>Have the correct fire-fighting equipment for putting a fire out quickly</li>
				<li>Keep fire exits and escape routes clearly marked and unobstructed at all times</li>
				<li>Ensure workers receive appropriate training on procedures they need to follow, including fire drills</li>
				<li>Review and update the risk assessment regularly</li>
			</ul>
			<table class="table" <?php echo $feedback->FloorPlan->Status->__toString() == 'A'?'style="pointer-events:none;"':''; ?>>
				<tr><th><img class="img-responsive pull-left" src="module_eportfolio/assets/images/wb2_img1.png" /></th><th>Draw a store floor plan on a paper showing where all the fire exits, signs, extinguishers and alarms can be found.  Identify any hazards and don't forget to include a key. <br>Scan and then upload your document.</th><th></th></tr>
				<?php
				$existing_floor_plan = $answers->FloorPlan->__toString();
				if(trim($existing_floor_plan) != '' && file_exists(Repository::getRoot().'/'.$tr->username.'/wb11/'.$existing_floor_plan))
				{
					echo '<tr><td></td><td><a href="#" onclick="downloadFloorPlanEvidence(\'/'.$tr->username.'/wb11/'.$existing_floor_plan.'\');">Download Floor Plan <i class="fa fa-download"></i></a></td><td></td></tr>';
				}
				?>
				<tr><td></td><td><input type="file" name="FloorPlan" /></td><td></td></tr>
				<tr><td></td><td><span id="btnUploadFloorPlanEvidence" class="btn btn-primary btn-md" onclick="uploadFloorPlanEvidence();"><i class="fa fa-upload"></i> Upload File</span><br><i class="text-muted">uploading the file will replace any previously uploaded file</i></td><td></td> </tr>
			</table>
		</div>
	</div>

	<p><br></p>

	<div class="row">
		<div class="col-sm-3"><img class="img-responsive " src="module_eportfolio/assets/images/wb11_pg28_img1.png" /></div>
		<div class="col-sm-3"><img class="img-responsive " src="module_eportfolio/assets/images/wb11_pg28_img2.png" /></div>
		<div class="col-sm-3"><img class="img-responsive " src="module_eportfolio/assets/images/wb11_pg28_img3.png" /></div>
		<div class="col-sm-3"><img class="img-responsive " src="module_eportfolio/assets/images/wb11_pg28_img4.png" /></div>
	</div>

	<?php if($wb->id != '' && $_SESSION['user']->type == User::TYPE_ASSESSOR && ($wb->wb_status == Workbook::STATUS_LEARNER_COMPLETED || $wb->wb_status == Workbook::STATUS_BEING_CHECKED || $wb->wb_status == Workbook::STATUS_IV_REJECTED)) {?>
	<div class="row well">
		<div class="col-sm-12">
			<div class="box box-success box-solid">
				<div class="box-header with-border"><h3 class="box-title">Assessor Feedback</h3></div>
				<div class="box-body assessorFeedback" <?php echo $feedback->FloorPlan->Status->__toString() == 'A'?'style="pointer-events:none;"':''; ?>>
					<div class="form-group">
						<label for="status_FloorPlan" class="col-sm-12 control-label">Status:</label>
						<div class="col-sm-12">
							<?php
							echo HTML::selectChosen('status_FloorPlan', $answer_status, $feedback->FloorPlan->Status->__toString() == 'A'?$feedback->FloorPlan->Status->__toString():'', false, true); ?>
						</div>
					</div>
					<div class="form-group">
						<label for="comments_FloorPlan" class="col-sm-12 control-label">Comments:</label>
						<div class="col-sm-12">
							<textarea name="comments_FloorPlan" rows="7" style="width: 100%;"><?php echo $feedback->FloorPlan->Comments->__toString(); ?></textarea>
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
		if($feedback->FloorPlan->Status->__toString() == 'NA')
			echo HTML::renderWorkbookIcons('FloorPlan', false, 'btn-danger');
		else
			echo HTML::renderWorkbookIcons('FloorPlan', false, 'btn-success');
		?>
	</div></div></div>
	<?php echo $wb->getPageBottomLine(); ?>
</div>
<!--.page 2 ends-->

<h1>Legal & Governance and Diversity</h1>

<div class="step-content">
	<?php echo $wb->getPageTopLine(); ?>
	<div class="row"><div class="col-sm-12"><div class="pull-right">
		<?php
		if($feedback->ContraveningLegislation->Status->__toString() == 'NA')
			echo HTML::renderWorkbookIcons('ContraveningLegislation', false, 'btn-danger');
		else
			echo HTML::renderWorkbookIcons('ContraveningLegislation', false, 'btn-success');
		?>
	</div></div></div>



	<div class="row">
		<div class="col-sm-12">
			<p class="text-bold">Legal and commercial implications to a business of contravening legislation</p>
			<p>Depending on the severity of the breach, the implications to the business may be different depending on the legislation and circumstances involved.</p>
			<p>However in all cases failing to meet with legal requirements means the company or you is breaking the law!</p>
			<p class="text-bold"><img class="img-responsive pull-left" src="module_eportfolio/assets/images/wb2_img1.png" /> &nbsp; Let's look at some examples of contravening legislation. For each read the text and then explain what implications this could have on the business and those involved. &nbsp; </p>
		</div>
		<div class="col-sm-12 table-responsive">
			<table class="table" <?php echo $feedback->ContraveningLegislation->Status->__toString() == 'A'?'style="pointer-events:none;"':''; ?>>
				<tr>
					<td style="width: 25%; vertical-align: middle;"><div class="bg-blue" style="padding: 10px;">A supermarket worker is sent for their break and on the way to the staff room they see a spillage. They only have 15 minutes and the canteen is about to close. They ignore the spillage and think that someone else will see it. Unfortunately a customer slips on it and hurts their back.</div> </td>
					<td style="width: 10%; vertical-align: middle;"><img class="img-responsive" src="module_eportfolio/assets/images/wb11_pg29_img1.png" /></td>
					<td style="width: 25%; vertical-align: middle;"><div style="border: #000000 2px dashed; padding: 10px;"><p class="text-bold">What are the consequences to the supermarket / staff member?</p> </div> </td>
					<td style="width: 10%; vertical-align: middle;"><img class="img-responsive" src="module_eportfolio/assets/images/wb11_pg29_img2.png" /></td>
					<td style="width: 30%; vertical-align: middle;"><textarea rows="5" name="CLQ1" style="width: 100%; "><?php echo $answers->ContraveningLegislation->Q1->__toString(); ?></textarea> </td>
				</tr>
				<tr>
					<td style="width: 25%; vertical-align: middle;"><div class="bg-purple" style="padding: 10px;">A restaurant owner finds he has some seafood that went out of date the day before. Instead of throwing it away he decides to use it in the Special of the Day. He only sells one but that person gets sick and writes a review online and phones up the local council.</div> </td>
					<td style="width: 10%; vertical-align: middle;"><img class="img-responsive" src="module_eportfolio/assets/images/wb11_pg29_img1.png" /></td>
					<td style="width: 25%; vertical-align: middle;"><div style="border: #000000 2px dashed; padding: 10px;"><p class="text-bold">What are the consequences to the restaurant and its owner?</p> </div> </td>
					<td style="width: 10%; vertical-align: middle;"><img class="img-responsive" src="module_eportfolio/assets/images/wb11_pg29_img2.png" /></td>
					<td style="width: 30%; vertical-align: middle;"><textarea rows="5" name="CLQ2" style="width: 100%; "><?php echo $answers->ContraveningLegislation->Q2->__toString(); ?></textarea> </td>
				</tr>
				<tr>
					<td style="width: 25%; vertical-align: middle;"><div class="bg-light-blue" style="padding: 10px;">An employee in a pub blocks the fire exit with rubbish. It's raining so decides to put it out later as they don't want to get wet. The pub has a visit from the Health and Safety Executive who finds the exit blocked.</div> </td>
					<td style="width: 10%; vertical-align: middle;"><img class="img-responsive" src="module_eportfolio/assets/images/wb11_pg29_img1.png" /></td>
					<td style="width: 25%; vertical-align: middle;"><div style="border: #000000 2px dashed; padding: 10px;"><p class="text-bold">What are the consequences to the pub/employee?</p> </div> </td>
					<td style="width: 10%; vertical-align: middle;"><img class="img-responsive" src="module_eportfolio/assets/images/wb11_pg29_img2.png" /></td>
					<td style="width: 30%; vertical-align: middle;"><textarea rows="5" name="CLQ3" style="width: 100%; "><?php echo $answers->ContraveningLegislation->Q3->__toString(); ?></textarea> </td>
				</tr>
			</table>
		</div>
	</div>

	<?php if($wb->id != '' && $_SESSION['user']->type == User::TYPE_ASSESSOR && ($wb->wb_status == Workbook::STATUS_LEARNER_COMPLETED || $wb->wb_status == Workbook::STATUS_BEING_CHECKED || $wb->wb_status == Workbook::STATUS_IV_REJECTED)) {?>
	<div class="row well">
		<div class="col-sm-12">
			<div class="box box-success box-solid">
				<div class="box-header with-border"><h3 class="box-title">Assessor Feedback</h3></div>
				<div class="box-body assessorFeedback" <?php echo $feedback->ContraveningLegislation->Status->__toString() == 'A'?'style="pointer-events:none;"':''; ?>>
					<div class="form-group">
						<label for="status_ContraveningLegislation" class="col-sm-12 control-label">Status:</label>
						<div class="col-sm-12">
							<?php
							echo HTML::selectChosen('status_ContraveningLegislation', $answer_status, $feedback->ContraveningLegislation->Status->__toString() == 'A'?$feedback->ContraveningLegislation->Status->__toString():'', false, true); ?>
						</div>
					</div>
					<div class="form-group">
						<label for="comments_ContraveningLegislation" class="col-sm-12 control-label">Comments:</label>
						<div class="col-sm-12">
							<textarea name="comments_ContraveningLegislation" rows="7" style="width: 100%;"><?php echo $feedback->ContraveningLegislation->Comments->__toString(); ?></textarea>
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
		if($feedback->ContraveningLegislation->Status->__toString() == 'NA')
			echo HTML::renderWorkbookIcons('ContraveningLegislation', false, 'btn-danger');
		else
			echo HTML::renderWorkbookIcons('ContraveningLegislation', false, 'btn-success');
		?>
	</div></div></div>
	<?php echo $wb->getPageBottomLine(); ?>
</div>
<!--.page 2 ends-->

<h1>Legal & Governance and Diversity</h1>

<div class="step-content">
	<?php echo $wb->getPageTopLine(); ?>
	<div class="row"><div class="col-sm-12"><div class="pull-right"><?php echo HTML::renderWorkbookIcons('', true); ?></div></div></div>

	<div class="row">

		<div class="col-sm-12">
			<h2>Meeting regulations and legislation</h2>
			<p class="text-bold">Taking steps to prevent underage sales</p>
		</div>

	</div>

	<div class="row">
		<div class="col-sm-12">
			<p>If you sell age-restricted products to a person under the minimum legal age, you may commit an offence under the relevant law. The penalties can include a fine or even imprisonment. There are laws that give you a legal defence which is often referred to as the 'due diligence' defence. Basically you must prove that you took 'all reasonable precautions / all reasonable steps' and exercised 'all due diligence' to avoid committing an offence.</p>
			<p>This means an employer is responsible for making sure that their team does not sell age-restricted products to people under the minimum legal age. To do this a business needs to set up effective systems which should be regularly monitored to ensure everyone is following the company policy and not contravening legislation.</p>
			<p class="text-bold">Age verification checks</p>
			<img class="img-responsive pull-right" src="module_eportfolio/assets/images/wb11_pg30_img1.png" />
			<p>Verify the age of potential buyers by asking to see an identity card that bears the PASS hologram (the Proof of Age Standards Scheme. (PASS) is the UK's national proof of age accreditation scheme supported by the Home Office, the Scottish government, the Association of Chief Police Officers (ACPO), Police Scotland and the Chartered Trading Standards Institute (CTSI))</p>
			<p><br></p>
			<p class="text-bold">Challenge 21 / Challenge 25</p>
			<img class="img-responsive pull-right" src="module_eportfolio/assets/images/wb11_pg30_img2.png" />
			<p>Challenge 21 and Challenge 25 are part of a scheme in the United Kingdom, introduced by the British Beer and Pub Association (BBPA) with the intention of preventing young people gaining access to age restricted products including cigarettes and alcoholic beverages. Under the scheme, customers attempting to buy age restricted products are asked to prove their age if in the retailer's opinion they look under 21 or 25.</p>
			<p class="text-bold">Staff training</p>
			<p>All staff should receive adequate training on underage sales. A training record should be kept and regularly updated. </p>
			<p class="text-bold">Use of till prompts </p>
			<p>Prompts that appear on the till when an age restricted product is scanned can be used to remind staff to carry out age verification checks.</p>
			<p class="text-bold">Store layout, signage and CCTV</p>
			<p>Age-restricted products should be kept where they can be monitored by staff. For example, fireworks stored on the shop floor must by law be kept in a secure cabinet. There must be adequate signs to inform consumers of the minimum legal age to purchase and businesses are legally required to display notices for tobacco and fireworks.</p>
			<p class="text-bold">Keep and maintain a refusals register</p>
			<p>This means keeping a record (date, time, incident, description of potential buyer) where sales of age-restricted products have been refused. This helps to demonstrate that sales are actively refused and an effective system is in place.</p>
		</div>
		<div class="col-sm-12">
			<div class="table-responsive">
				<table class="table table-bordered">
					<tr><th>Goods</th><th>Age restriction</th></tr>
					<tr>
						<td>
							<p>Adult fireworks and sparklers (category F2 [outdoor use - confined areas] and category F3 [outdoor use - large open areas] fireworks)</p>
						</td>
						<td>
							<p>18 and over</p>
						</td>
					</tr>
					<tr>
						<td>
							<p>Aerosol paint</p>
						</td>
						<td>
							<p>16 and over</p>
						</td>
					</tr>
					<tr>
						<td>
							<p>Alcohol</p>
						</td>
						<td>
							<p>18 and over</p>
						</td>
					</tr>
					<tr>
						<td>
							<p>Christmas crackers</p>
						</td>
						<td>
							<p>12 and over</p>
						</td>
					</tr>
					<tr>
						<td>
							<p>Crossbows</p>
						</td>
						<td>
							<p>18 and over</p>
						</td>
					</tr>
					<tr>
						<td>
							<p>Knives / axes / blades</p>
						</td>
						<td>
							<p>18 and over (in Scotland domestic knives can be sold to those aged 16 and over)</p>
						</td>
					</tr>
					<tr>
						<td>
							<p>Lighter refills containing butane</p>
						</td>
						<td>
							<p>18 and over</p>
						</td>
					</tr>
					<tr>
						<td>
							<p>Liqueur confectionery (Scotland only)</p>
						</td>
						<td>
							<p>16 and over</p>
						</td>
					</tr>
					<tr>
						<td>
							<p>Lottery tickets / 'instant win' cards</p>
						</td>
						<td>
							<p>16 and over</p>
						</td>
					</tr>
					<tr>
						<td>
							<p>Nicotine inhaling products (England and Wales only)</p>
						</td>
						<td>
							<p>18 and over</p>
						</td>
					</tr>
					<tr>
						<td>
							<p>Party poppers and similar low-hazard low-noise fireworks (category F1) (except Christmas crackers)</p>
						</td>
						<td>
							<p>16 and over</p>
						</td>
					</tr>
					<tr>
						<td>
							<p>Petrol</p>
						</td>
						<td>
							<p>16 and over</p>
						</td>
					</tr>
					<tr>
						<td>
							<p>Sunbeds</p>
						</td>
						<td>
							<p>18 and over</p>
						</td>
					</tr>
					<tr>
						<td>
							<p>Tobacco</p>
						</td>
						<td>
							<p>18 and over</p>
						</td>
					</tr>
					<tr>
						<td>
							<p>Video recordings: U (universal)</p>
						</td>
						<td>
							<p>unrestricted</p>
						</td>
					</tr>
					<tr>
						<td>
							<p>Video recordings: PG (parental guidance)</p>
						</td>
						<td>
							<p>unrestricted</p>
						</td>
					</tr>
					<tr>
						<td>
							<p>Video recordings: classification 12</p>
						</td>
						<td>
							<p>12 and over</p>
						</td>
					</tr>
					<tr>
						<td>
							<p>Video recordings: classification 15</p>
						</td>
						<td>
							<p>15 and over</p>
						</td>
					</tr>
					<tr>
						<td>
							<p>Video recordings: classification 18</p>
						</td>
						<td>
							<p>18 and over</p>
						</td>
					</tr>
				</table>
			</div>
		</div>
	</div>

	<p><br></p>

	<div class="row"><div class="col-sm-12"><div class="pull-right"><?php echo HTML::renderWorkbookIcons('', true); ?></div></div></div>
	<?php echo $wb->getPageBottomLine(); ?>
</div>
<!--.page 2 ends-->

<h1>Legal & Governance and Diversity</h1>

<div class="step-content">
	<?php echo $wb->getPageTopLine(); ?>
	<div class="row"><div class="col-sm-12"><div class="pull-right">
		<?php
		if($feedback->Penalties->Status->__toString() == 'NA')
			echo HTML::renderWorkbookIcons('Penalties', false, 'btn-danger');
		else
			echo HTML::renderWorkbookIcons('Penalties', false, 'btn-success');
		?>
	</div></div></div>

	<div class="row">

		<div class="col-sm-12">
			<p class="text-bold">Penalties</p>
		</div>

	</div>

	<div class="row">
		<div class="col-sm-12">
			<p class="text-bold"><img class="img-responsive pull-left" src="module_eportfolio/assets/images/wb2_img1.png" /> &nbsp; Below are a number of age restricted products. There are also a number of different penalties if these items are sold to someone underage. Your task is to match them up. &nbsp; </p>
			<p class="text-bold">NB Most have more than one possible penalty and you can use each penalty more than once.</p>
		</div>
	</div>
	<div class="row">
		<div class="col-sm-12 table-responsive">
			<table class="table" <?php echo $feedback->Penalties->Status->__toString() == 'A'?'style="pointer-events:none;"':''; ?>>
				<tr>
					<td style="vertical-align: middle;"><img class="img-responsive" src="module_eportfolio/assets/images/wb11_pg32_img1.png" /></td>
					<td style="vertical-align: middle;"><img class="img-responsive" src="module_eportfolio/assets/images/wb11_pg32_img2.png" /></td>
					<td style="vertical-align: middle;"><img class="img-responsive" src="module_eportfolio/assets/images/wb11_pg32_img3.png" /></td>
					<td style="vertical-align: middle;"><img class="img-responsive" src="module_eportfolio/assets/images/wb11_pg32_img4.png" /></td>
					<td style="vertical-align: middle;"><img class="img-responsive" src="module_eportfolio/assets/images/wb11_pg32_img5.png" /></td>
				</tr>
				<tr>
					<td><textarea name="PenaltiesQuestion1" style="width: 100%;"><?php echo $answers->Penalties->Question1->__toString(); ?></textarea> </td>
					<td><textarea name="PenaltiesQuestion2" style="width: 100%;"><?php echo $answers->Penalties->Question2->__toString(); ?></textarea> </td>
					<td><textarea name="PenaltiesQuestion3" style="width: 100%;"><?php echo $answers->Penalties->Question3->__toString(); ?></textarea> </td>
					<td><textarea name="PenaltiesQuestion4" style="width: 100%;"><?php echo $answers->Penalties->Question4->__toString(); ?></textarea> </td>
					<td><textarea name="PenaltiesQuestion5" style="width: 100%;"><?php echo $answers->Penalties->Question5->__toString(); ?></textarea> </td>
				</tr>
			</table>
		</div>
		<div class="col-sm-12">
			<img class="img-responsive" src="module_eportfolio/assets/images/wb11_pg32_img10.png" />
		</div>
		<div class="col-sm-12 table-responsive">
			<table class="table" <?php echo $feedback->Penalties->Status->__toString() == 'A'?'style="pointer-events:none;"':''; ?>>
				<tr>
					<td><textarea name="PenaltiesQuestion6" style="width: 100%;"><?php echo $answers->Penalties->Question6->__toString(); ?></textarea> </td>
					<td><textarea name="PenaltiesQuestion7" style="width: 100%;"><?php echo $answers->Penalties->Question7->__toString(); ?></textarea> </td>
					<td><textarea name="PenaltiesQuestion8" style="width: 100%;"><?php echo $answers->Penalties->Question8->__toString(); ?></textarea> </td>
					<td><textarea name="PenaltiesQuestion9" style="width: 100%;"><?php echo $answers->Penalties->Question9->__toString(); ?></textarea> </td>
				</tr>
				<tr>
					<td style="vertical-align: middle;"><img class="img-responsive" src="module_eportfolio/assets/images/wb11_pg32_img6.png" /></td>
					<td style="vertical-align: middle;"><img class="img-responsive" src="module_eportfolio/assets/images/wb11_pg32_img7.png" /></td>
					<td style="vertical-align: middle;"><img class="img-responsive" src="module_eportfolio/assets/images/wb11_pg32_img8.png" /></td>
					<td style="vertical-align: middle;"><img class="img-responsive" src="module_eportfolio/assets/images/wb11_pg32_img9.png" /></td>
				</tr>
			</table>
		</div>
	</div>

	<?php if($wb->id != '' && $_SESSION['user']->type == User::TYPE_ASSESSOR && ($wb->wb_status == Workbook::STATUS_LEARNER_COMPLETED || $wb->wb_status == Workbook::STATUS_BEING_CHECKED || $wb->wb_status == Workbook::STATUS_IV_REJECTED)) {?>
	<div class="row well">
		<div class="col-sm-12">
			<div class="box box-success box-solid">
				<div class="box-header with-border"><h3 class="box-title">Assessor Feedback</h3></div>
				<div class="box-body assessorFeedback" <?php echo $feedback->Penalties->Status->__toString() == 'A'?'style="pointer-events:none;"':''; ?>>
					<div class="form-group">
						<label for="status_Penalties" class="col-sm-12 control-label">Status:</label>
						<div class="col-sm-12">
							<?php
							echo HTML::selectChosen('status_Penalties', $answer_status, $feedback->Penalties->Status->__toString() == 'A'?$feedback->Penalties->Status->__toString():'', false, true); ?>
						</div>
					</div>
					<div class="form-group">
						<label for="comments_Penalties" class="col-sm-12 control-label">Comments:</label>
						<div class="col-sm-12">
							<textarea name="comments_Penalties" rows="7" style="width: 100%;"><?php echo $feedback->Penalties->Comments->__toString(); ?></textarea>
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
		if($feedback->Penalties->Status->__toString() == 'NA')
			echo HTML::renderWorkbookIcons('Penalties', false, 'btn-danger');
		else
			echo HTML::renderWorkbookIcons('Penalties', false, 'btn-success');
		?>
	</div></div></div>
	<?php echo $wb->getPageBottomLine(); ?>
</div>
<!--.page 2 ends-->

<h1>Legal & Governance and Diversity</h1>

<div class="step-content">
	<?php echo $wb->getPageTopLine(); ?>
	<div class="row"><div class="col-sm-12"><div class="pull-right">
		<?php
		if($feedback->Diversity->Status->__toString() == 'NA')
			echo HTML::renderWorkbookIcons('Diversity', false, 'btn-danger');
		else
			echo HTML::renderWorkbookIcons('Diversity', false, 'btn-success');
		?>
	</div></div></div>

	<div class="row">
		<div class="col-sm-4 col-sm-offset-4">
			<div class="bg-blue text-center" style="padding: 15px; ">
				<p><span class="text-bold">Diversity</span></p>
				<p> means </p>
				<p>understanding that each individual is unique and recognizing our individual differences</p>
			</div>
		</div>
	</div>

	<div class="row">
		<div class="col-sm-12">
			<p>The population in the UK today is diverse. People are different and varied in the way they look, dress, how they behave, what they believe in, where they work and live, their gender and how old they are.</p>
			<p class="text-bold">Diversity recognises that:</p>
			<ul style="margin-left: 15px;">
				<li>Society consists of individuals and groups with varying backgrounds, experiences, styles, perceptions, values and beliefs</li>
				<li>Everybody is different – where there are two people there is diversity</li>
				<li>We need to understand, value and respect those differences</li>
			</ul>
			<p><br></p>
			<p class="text-bold"> <img class="pull-left" src="module_eportfolio/assets/images/wb2_pg18_img1.png" /> &nbsp;Log on to the Hub >> Department >> Corporate Social Responsibility (CSR) >> Workplace >> Diversity Aspire Group >> Click to watch their video</p>
			<p>Empathy is sometimes confused with sympathy however they are two different things.</p>
			<p>Sympathy is feeling compassion, sorrow or pity for the hardships that another person encounters while empathy is putting you in the shoes of another.</p>
		</div>
		<div class="col-sm-12">
			<div class="col-sm-6 text-center">
				<div class="bg-blue text-center" style="padding: 15px; ">
					<p><span class="text-bold">Empathy</span></p>
					<p><i>Empathy is the ability to understand and share the feelings of another</i></p>
				</div>
			</div>
			<div class="col-sm-6 text-center"><img src="module_eportfolio/assets/images/wb_r11_pg31_img1.png" class="img-responsive" /> </div>
		</div>
		<div class="col-sm-12">
			<div class="col-sm-8">
				<p class="text-bold"><img class="img-responsive pull-left" src="module_eportfolio/assets/images/wb2_img1.png" /> &nbsp; Give an example of when you have displayed empathy towards another person.</p>
				<p <?php echo $feedback->Diversity->Status->__toString() == 'A'?'style="pointer-events:none;"':''; ?>><textarea  rows="5" style="width: 100%;" name="DiversityExample"><?php echo $answers->Diversity->Example->__toString(); ?></textarea></p>
			</div>
			<div class="col-sm-4 text-center"><img src="module_eportfolio/assets/images/wb_r11_pg31_img2.png" class="img-responsive" /> </div>
		</div>
	</div>

	<div class="row">
		<div class="col-sm-12">
			<p class="text-bold">The Equality Act 2010</p>
			<p><img src="module_eportfolio/assets/images/wb_r11_pg32_img1.png" class="img-responsive pull-right" /></p>
			<p>The Equality Act became law in October 2010. It replaced previous legislation (such as the Race Relations Act 1976 and the Disability Discrimination Act 1995) and ensures consistency in what employers and employees need to do to make their workplaces a fair environment and comply with the law.</p>
			<p>The Equality Act 2010 legally protects people from discrimination in the workplace and in the wider society. By replacing previous laws with a single act it makes the law easier to understand and strengthens protection in some situations. It sets out the different ways in which it's unlawful to treat someone.</p>
		</div>
	</div>
	<div class="row">
		<div class="col-sm-12">
			<p class="text-bold"><img class="img-responsive pull-left" src="module_eportfolio/assets/images/wb2_img1.png" /> &nbsp; It is against the law to discriminate anyone because of a protected characteristic. There are 9 protected characteristics. We have given you 6 below. Add in the other 3.</p>
			<div class="table-responsive">
				<table class="table table-bordered" <?php echo $feedback->Diversity->Status->__toString() == 'A'?'style="pointer-events:none;"':''; ?>>
					<tr><td>1. Being or becoming a transsexual person</td></tr>
					<tr><td>2. Being married or in a civil partnership</td></tr>
					<tr><td>3. Being pregnant or on maternity leave</td></tr>
					<tr><td>4. Race including colour, nationality, ethnic or national origin</td></tr>
					<tr><td>5. Religion, belief or lack of religion / belief</td></tr>
					<tr><td>6. Sex</td></tr>
					<tr><td><textarea style="width: 100%;" name="DiversityC1"><?php echo $answers->Diversity->C1->__toString(); ?></textarea></td></tr>
					<tr><td><textarea style="width: 100%;" name="DiversityC2"><?php echo $answers->Diversity->C2->__toString(); ?></textarea>  </td></tr>
					<tr><td><textarea style="width: 100%;" name="DiversityC3"><?php echo $answers->Diversity->C3->__toString(); ?></textarea>  </td></tr>
				</table>
			</div>
		</div>
	</div>
	<div class="row">
		<div class="col-sm-6">
			<p>You're protected from discrimination:</p>
			<ul style="margin-left: 15px;">
				<li>At work</li>
				<li>In education</li>
				<li>As a consumer</li>
				<li>When using public services</li>
				<li>When buying or renting property</li>
				<li>As a member or guest of a private club or association</li>
			</ul>
		</div>
		<div class="col-sm-6">
			<p>You're also protected from discrimination if:</p>
			<ul style="margin-left: 15px;">
				<li>You're associated with someone who has protected characteristic. E.g. a family member or friend</li>
				<li>You've complained about discrimination or supported someone else's claim</li>
			</ul>
		</div>
		<div class="col-sm-12">
			<p><br></p>
			<p>Discrimination can come in one of the following forms:</p>
			<ul style="margin-left: 15px;">
				<li>Direct discrimination – treating someone with a protected characteristic less favourably than others.</li>
				<li>Indirect discrimination – putting rules or arrangements in place that apply to everyone but that put someone with a protected characteristic at an unfair disadvantage.</li>
				<li>Harassment – unwanted behaviour linked to a protected characteristic that violates someone's dignity or creates an offensive environment for them.</li>
				<li>Victimisation – treating someone unfairly because they've complained about discrimination or harassment.</li>
			</ul>
		</div>
		<div class="col-sm-12">
			<p><br></p>
			<p class="text-bold">The law protects against discrimination at work, including:</p>
			<img src="module_eportfolio/assets/images/wb_r11_pg33_img1.png" class="img-responsive pull-right" />
			<ul style="margin-left: 15px;">
				<li>Dismissal</li>
				<li>Employment terms and conditions</li>
				<li>Pay and benefits</li>
				<li>Promotion and transfer opportunities</li>
				<li>Training</li>
				<li>Recruitment</li>
				<li>Redundancy</li>
			</ul>
			<p><br></p>
			<p>Some forms of discrimination are only allowed if they're needed for the way the organisation works, e.g.</p>
			<ul style="margin-left: 15px;">
				<li>A Roman Catholic school restricting application for admission of pupils to Catholics only
				<li>Employing only women in a health centre for Muslim women
			</ul>
			<p class="text-bold">Disability</p>
			<p>If you're disabled you have the same rights as other workers. Employers should also make ‘reasonable adjustments' to help disabled employees and job applicants with:</p>
			<ul style="margin-left: 15px;">
				<li>Application forms, e.g. providing forms in Braille, audio formats</li>
				<li>Aptitude tests, e.g. giving extra time to complete the tests</li>
				<li>Dismissal or redundancy</li>
				<li>Discipline and grievances</li>
				<li>Interview arrangements, e.g. wheelchair access, communicator support</li>
				<li>Making sure the workplace has the right facilities and equipment for disabled workers or someone offered a job</li>
				<li>Promotion, transfer and training opportunities</li>
				<li>Terms of employment, including pay</li>
				<li>Work related benefits like access to recreation or refreshment facilities</li>
			</ul>
			<p class="text-bold">What you can do</p>
			<p>If you think you've been unfairly discriminated against you can:</p>
			<ul style="margin-left: 15px;">
				<li>Complain directly to the person or organisation</li>
				<li>Use someone else to help you sort it out (called mediation or alternative dispute resolution)</li>
				<li>Make a claim in a court or tribunal</li>
				<li>Contact the Equality Advisory Support Service for help and advice</li>
			</ul>
			<p class="text-bold">Discrimination at work</p>
			<ul style="margin-left: 15px;">
				<li>Employees should talk to their employer first to try to sort out the problem informally</li>
				<li>Employees should follow their employer's policy for making a complaint</li>
				<li>If things can't be sorted out informally, talk to ACAS, Citizens Advice or a trade union representative</li>
			</ul>
			<p><br></p>
			<p class="text-bold"> <img class="pull-left" src="module_eportfolio/assets/images/wb2_pg18_img1.png" /> &nbsp;<i>More information on discrimination and the Equality Act can be found on the Hub and in your Equality, Diversity and Safeguarding workbook. Details of Superdrug's Equality and Diversity Policy including how to make a complaint can be found in the Policies and Procedures manual.</i></p>
		</div>
	</div>

	<?php if($wb->id != '' && $_SESSION['user']->type == User::TYPE_ASSESSOR && ($wb->wb_status == Workbook::STATUS_LEARNER_COMPLETED || $wb->wb_status == Workbook::STATUS_BEING_CHECKED || $wb->wb_status == Workbook::STATUS_IV_REJECTED)) {?>
	<div class="row well">
		<div class="col-sm-12">
			<div class="box box-success box-solid">
				<div class="box-header with-border"><h3 class="box-title">Assessor Feedback</h3></div>
				<div class="box-body assessorFeedback" <?php echo $feedback->Diversity->Status->__toString() == 'A'?'style="pointer-events:none;"':''; ?>>
					<div class="form-group">
						<label for="status_Diversity" class="col-sm-12 control-label">Status:</label>
						<div class="col-sm-12">
							<?php
							echo HTML::selectChosen('status_Diversity', $answer_status, $feedback->Diversity->Status->__toString() == 'A'?$feedback->Diversity->Status->__toString():'', false, true); ?>
						</div>
					</div>
					<div class="form-group">
						<label for="comments_Diversity" class="col-sm-12 control-label">Comments:</label>
						<div class="col-sm-12">
							<textarea name="comments_Diversity" rows="7" style="width: 100%;"><?php echo $feedback->Diversity->Comments->__toString(); ?></textarea>
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
		if($feedback->Diversity->Status->__toString() == 'NA')
			echo HTML::renderWorkbookIcons('Diversity', false, 'btn-danger');
		else
			echo HTML::renderWorkbookIcons('Diversity', false, 'btn-success');
		?>
	</div></div></div>
	<?php echo $wb->getPageBottomLine(); ?>
</div>
<!--.page 2 ends-->

<h1>Legal & Governance and Diversity</h1>

<div class="step-content">
	<?php echo $wb->getPageTopLine(); ?>
	<div class="row"><div class="col-sm-12"><div class="pull-right"><?php echo HTML::renderWorkbookIcons('', true); ?></div></div></div>

	<div class="row">
		<div class="col-sm-4 col-sm-offset-4">
			<div class="text-center" style="padding: 15px; border: #90ee90 solid 2px; background-color: #90ee90;">
				<p><span class="text-bold">Demographics</span></p>
				<p><i>The statistical data of a population, especially those showing average age, income, education, etc.</i></p>
			</div>
		</div>
	</div>

	<div class="row">
		<div class="col-sm-12">
			<p>Demographics indicate the general characteristics of the population in a specific region. These characteristics include purchasing power (the financial ability to buy products and services) types of residence and means of transportation. Family status and educational level are also included.</p>
			<p>Analysing these traits allows businesses to, for example, verify if it is capable of being successful in the region and determine the prices for products and services that encourage consumer spending. This analysis also helps develop advertising messages and marketing plans that appeal to target markets, leading to effective campaigns.</p>
		</div>
	</div>

	<div class="row">
		<div class="col-sm-4 col-sm-offset-4">
			<div class="text-center bg-blue">
				<p><span class="text-bold">Psychographics</span></p>
				<p><i>The study and classification of people according to their attitudes, aspirations and other psychological criteria, especially in market research</i></p>
			</div>
		</div>
	</div>

	<div class="row">
		<div class="col-sm-12">
			<p>Demographics work together with psychographics. Psychographics focus on the attitudes and lifestyles of potential customers, such as personal preferences, hobbies and interests. Psychographics also explain why consumers buy a product or service. </p>
		</div>
		<div class="col-sm-6"><img src="module_eportfolio/assets/images/wb_r11_pg34_img1.png" class="img-responsive" /></div>
		<div class="col-sm-6"><img src="module_eportfolio/assets/images/wb_r11_pg34_img2.png" class="img-responsive" /></div>
	</div>

	<p><br></p>
	<div class="row"><div class="col-sm-12"><div class="pull-right"><?php echo HTML::renderWorkbookIcons('', true); ?></div></div></div>
	<?php echo $wb->getPageBottomLine(); ?>
</div>
<!--.page 2 ends-->

<h1>Legal & Governance and Diversity</h1>

<div class="step-content">
	<?php echo $wb->getPageTopLine(); ?>
	<div class="row"><div class="col-sm-12"><div class="pull-right">
		<?php
		if($feedback->Demographics->Status->__toString() == 'NA')
			echo HTML::renderWorkbookIcons('Demographics', false, 'btn-danger');
		else
			echo HTML::renderWorkbookIcons('Demographics', false, 'btn-success');
		?>
	</div></div></div>

	<div class="row">
		<div class="col-sm-12">
			<p class="text-bold">What are the local demographics where you live?</p>
			<p class="text-bold"><img class="img-responsive pull-left" src="module_eportfolio/assets/images/wb2_img1.png" /> &nbsp; Describe where you live and what you see as the local demographics.E.g. do you live in a city, town or village? What is the approximate average age of where you live?</p>
			<div class="table-responsive">
				<table class="table table-bordered"  <?php echo $feedback->Demographics->Status->__toString() == 'A'?'style="pointer-events:none;"':''; ?>>
					<tr><th style="width: 15%;"><p>Demographic characteristic</p></th><th style="width: 25%;"><p>Examples</p></th><th><p>What is it like where you live?</p></th></tr>
					<tr>
						<td>
							<p>Average age</p>
						</td>
						<td>
							<p>Under 20s</p>
							<p>Students / Young parents / Over 40s/ Retired etc.</p>
						</td>
						<td>
							<textarea name="DemographicsQ1" style="width: 100%;"><?php echo $answers->Demographics->Q1->__toString(); ?></textarea>
						</td>
					</tr>
					<tr>
						<td>
							<p>Type of area</p>
						</td>
						<td>
							<p>Village / Small town / Large town / City etc.</p>
						</td>
						<td>
							<textarea name="DemographicsQ2" style="width: 100%;"><?php echo $answers->Demographics->Q2->__toString(); ?></textarea>
						</td>
					</tr>
					<tr>
						<td>
							<p>Type of residences</p>
						</td>
						<td>
							<p>Flats / Shared accommodation / Student houses / Housing estates etc.</p>
						</td>
						<td>
							<textarea name="DemographicsQ3" style="width: 100%;"><?php echo $answers->Demographics->Q3->__toString(); ?></textarea>
						</td>
					</tr>
					<tr>
						<td>
							<p>Means of transportation</p>
						</td>
						<td>
							<p>Trams / Bus / Train / Car</p>
						</td>
						<td>
							<textarea name="DemographicsQ4" style="width: 100%;"><?php echo $answers->Demographics->Q4->__toString(); ?></textarea>
						</td>
					</tr>
					<tr>
						<td>
							<p>Family status</p>
						</td>
						<td>
							<p>Married / Single / Divorced</p>
						</td>
						<td>
							<textarea name="DemographicsQ5" style="width: 100%;"><?php echo $answers->Demographics->Q5->__toString(); ?></textarea>
						</td>
					</tr>
					<tr>
						<td>
							<p>Educational level</p>
						</td>
						<td>
							<p>High School / College / University</p>
						</td>
						<td>
							<textarea name="DemographicsQ6" style="width: 100%;"><?php echo $answers->Demographics->Q6->__toString(); ?></textarea>
						</td>
					</tr>
					<tr>
						<td>
							<p>Employment</p>
						</td>
						<td>
							<p>Unemployed / Part time / Full time / Retired</p>
						</td>
						<td>
							<textarea name="DemographicsQ7" style="width: 100%;"><?php echo $answers->Demographics->Q7->__toString(); ?></textarea>
						</td>
					</tr>
				</table>
			</div>
			<p class="text-bold"><img class="img-responsive pull-left" src="module_eportfolio/assets/images/wb2_img1.png" /> &nbsp; Next think about the psychographics of your area. Think about the attitudes and lifestyles of your customers, such as personal preferences, hobbies and interests.
				Are people buying products mainly for necessity or because they can treat themselves?
				Do they spend money on the latest trend or do they only like buying a bargain?
			</p>
			<p <?php echo $feedback->Demographics->Status->__toString() == 'A'?'style="pointer-events:none;"':''; ?>><textarea rows="7" style="width: 100%;" name="DemographicsQ8"><?php echo $answers->Demographics->Q8->__toString(); ?></textarea> </p>
		</div>
	</div>

	<?php if($wb->id != '' && $_SESSION['user']->type == User::TYPE_ASSESSOR && ($wb->wb_status == Workbook::STATUS_LEARNER_COMPLETED || $wb->wb_status == Workbook::STATUS_BEING_CHECKED || $wb->wb_status == Workbook::STATUS_IV_REJECTED)) {?>
	<div class="row well">
		<div class="col-sm-12">
			<div class="box box-success box-solid">
				<div class="box-header with-border"><h3 class="box-title">Assessor Feedback</h3></div>
				<div class="box-body assessorFeedback" <?php echo $feedback->Demographics->Status->__toString() == 'A'?'style="pointer-events:none;"':''; ?>>
					<div class="form-group">
						<label for="status_Demographics" class="col-sm-12 control-label">Status:</label>
						<div class="col-sm-12">
							<?php
							echo HTML::selectChosen('status_Demographics', $answer_status, $feedback->Demographics->Status->__toString() == 'A'?$feedback->Demographics->Status->__toString():'', false, true); ?>
						</div>
					</div>
					<div class="form-group">
						<label for="comments_Demographics" class="col-sm-12 control-label">Comments:</label>
						<div class="col-sm-12">
							<textarea name="comments_Demographics" rows="7" style="width: 100%;"><?php echo $feedback->Demographics->Comments->__toString(); ?></textarea>
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
		if($feedback->Demographics->Status->__toString() == 'NA')
			echo HTML::renderWorkbookIcons('Demographics', false, 'btn-danger');
		else
			echo HTML::renderWorkbookIcons('Demographics', false, 'btn-success');
		?>
	</div></div></div>
	<?php echo $wb->getPageBottomLine(); ?>
</div>
<!--.page 2 ends-->

<h1>Legal & Governance and Diversity</h1>

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
			<p class="text-bold">Now you have completed the section on Legal and Governance answer the following questions:</p>
			<p class="text-bold">Unit 8 – 1.1: Explain legislative responsibilities relating to the business and the products and / or services being sold</p>
			<textarea name="QQuestion1" rows="10" style="width: 100%;"><?php echo $answers->QualificationQuestions->Question1->__toString(); ?></textarea>
			<p class="text-bold">Unit 8 – 1.2: Explain the importance of protecting people's health, safety and security</p>
			<textarea name="QQuestion2" rows="10" style="width: 100%;"><?php echo $answers->QualificationQuestions->Question2->__toString(); ?></textarea>
			<p class="text-bold">Unit 8 – 1.3: Explain the consequences of not following legal guidelines</p>
			<textarea name="QQuestion3" rows="12" style="width: 100%;"><?php echo $answers->QualificationQuestions->Question3->__toString(); ?></textarea>
			<p class="text-bold">Unit 8 – 2.1: Explain why it is important and how to work with people from a wide range of backgrounds and cultures</p>
			<textarea name="QQuestion4" rows="10" style="width: 100%;"><?php echo $answers->QualificationQuestions->Question4->__toString(); ?></textarea>
			<p class="text-bold">Unit 8 – 2.2: Explain why it is important to respect other people's cultures and beliefs</p>
			<textarea name="QQuestion5" rows="10" style="width: 100%;"><?php echo $answers->QualificationQuestions->Question5->__toString(); ?></textarea>
			<p class="text-bold">Unit 8 – 2.3: Explain how local demographics can impact on the product range of the business</p>
			<textarea name="QQuestion6" rows="13" style="width: 100%;"><?php echo isset($answers->QualificationQuestions->Question6) ? $answers->QualificationQuestions->Question6->__toString() : ''; ?></textarea>
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
<!--.page 2 ends-->

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

		$("#frm_wb_legal_and_governance :input").not(".assessorFeedback :input, #signature_text, #frm_wb_legal_and_governance :input[type=hidden], .btnViewSectionHistory, .btnViewAssessorComments, #iv_status, #iv_comments, #reopen_comments").prop("disabled", true);

		$('#btnUploadFloorPlanEvidence').attr('disabled', 'disabled');

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
							var myForm = document.forms['frm_wb_legal_and_governance'];
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
				var myForm = document.forms['frm_wb_legal_and_governance'];
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

					var myForm = document.forms['frm_wb_legal_and_governance'];
					myForm.elements['full_save'].value = 'Y';
					myForm.elements['full_save_feedback'].value = 'Y';
					window.onbeforeunload = null;
					myForm.submit();

				},
				'Save And Come Back Later':function () {

					var myForm = document.forms['frm_wb_legal_and_governance'];
					myForm.elements['full_save'].value = 'Y';
					myForm.elements['full_save_feedback'].value = 'N';
					window.onbeforeunload = null;
					myForm.submit();

				}
			}
		});
	});

	function uploadFloorPlanEvidence()
	{
	<?php if($disable_answers){?>
		return;
		<?php } ?>
		var fileName = $("input:file").val();
		if(fileName == '')
			return alert('Please select a file to upload');

		window.onbeforeunload = null;

		partialSave();

		$('#frm_wb_legal_and_governance').submit();

	}

	function downloadFloorPlanEvidence(path)
	{
		window.onbeforeunload = null;

		window.location.href="do.php?_action=downloader&f=" + encodeURIComponent(path);
	}

	function partialSave() {
		$('#frm_wb_legal_and_governance :input[name=full_save]').val('N');
		$($('#frm_wb_legal_and_governance').serializeArray()).each(function(i, field)
		{
			document.forms["frm_wb_legal_and_governance"].elements[field.name].value = field.value.replace(/£/g, "GBP");
		});
		$.ajax({
			type:'POST',
			url:'do.php?_action=save_wb_legal_and_governance',
			data:$('#frm_wb_legal_and_governance').serialize(),
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
