<?php /* @var $wb WBSystemsAndResources */ ?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/html" xmlns="http://www.w3.org/1999/html" xmlns="http://www.w3.org/1999/html"
      xmlns="http://www.w3.org/1999/html">
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<title>Systems & Resources workbook</title>
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

<form name="frm_wb_systems_and_resources" id="frm_wb_systems_and_resources" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
<input type="hidden" name="_action" value="save_wb_systems_and_resources" />
<input type="hidden" name="id" value="<?php echo $wb->id; ?>" />
<input type="hidden" name="tr_id" value="<?php echo $wb->tr_id; ?>" />
<input type="hidden" name="wb_status" id="wb_status" value="" />
<input type="hidden" name="full_save" id="full_save" value="<?php echo $wb->full_save; ?>" />
<input type="hidden" name="full_save_feedback" id="full_save_feedback" value="<?php echo $wb->full_save_feedback; ?>" />
<div class="container-float">
<div class="wrapper" style="background-color: #ffffff;">

<header class="main-header"><span style="margin-left: 50%;"><?php echo DAO::getSingleValue($link, "SELECT title FROM student_frameworks WHERE tr_id = '{$wb->tr_id}'"); ?></span></header>

<div class="content-wrapper" style="background-color: #ffffff;">

<?php echo $wb->savers_or_sp == 'savers' ? '<section class="content-header"><h1 style="color: #0000ff;">Systems and resources</h1></section>' : '<section class="content-header"><h1>Systems and resources</h1></section>' ?>

<section class="content">

<div id="wizard">

<h1>Systems and resources</h1>
<div class="step-content">
	<?php echo $wb->getPageTopLine(); ?>
	<div style="position: absolute; top: 40%; right: 50%;" class="lead">
		<?php echo $wb->savers_or_sp == 'savers' ? '<h2 class="text-bold" style="color: #0000ff;">Systems <span class="text-red">&</span> Resources</h2>' : '<h2 class="text-bold">Systems & Resources</h2>' ?>
		<p class="text-center" >Module 8</p>
	</div>

	<?php echo $wb->getPageBottomLine(); ?>
</div> <!--.page 1 ends-->

<h1>Systems and resources</h1>
<div class="step-content">
	<?php echo $wb->getPageTopLine(); ?>

	<div class="row"><div class="col-sm-12"><div class="pull-right"><?php echo HTML::renderWorkbookIcons('', true); ?></div></div></div>


	<div class="row">
		<div class="col-sm-12">
			<p>This module is about the types of systems and resources and their uses in different types of business operations. To work effectively in business you need to understand the systems and resources available, what it is used for and how to use it. We use systems and resources in our everyday work life to enable us to work efficiently and effectively. Using any systems or resources will require you to know and understand the company processes and procedures. </p>
			<p>In this module you will look at:</p>
			<ul style="margin-left: 15px; margin-bottom: 15px;">
				<li>Your own system and resource training</li>
				<li>Payment methods</li>
				<li>Store technology and equipment</li>
			</ul>
		</div>
		<div class="col-sm-12">
			<img src="module_eportfolio/assets/images/wb8_pg2_img1.png" />
		</div>
	</div>

	<p><br></p>

	<div class="row"><div class="col-sm-12"><div class="pull-right"><?php echo HTML::renderWorkbookIcons('', true); ?></div></div></div>

	<?php echo $wb->getPageBottomLine(); ?>
</div> <!--.page 2 ends-->

<h1>Systems and resources</h1>
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
				$Journey = $answers->Journey;
				$activities = $wb->savers_or_sp == 'savers' ? array(array('InStore', 'In-store activity')) : array(array('iPad', 'iPad'),array('InStore', 'In-store activity'));
				$items = WBSystemsAndResources::getLearningJourneyItems($wb->savers_or_sp);
				$j = 0;
				foreach($items AS $i)
				{
					++$j;
					$act = 'Act'.$j;
					$dc = 'DC'.$j;
					echo '<tr>';
					echo '<td>' . $i . '</td>';
					echo '<td class="bg-info">' . HTML::selectChosen($act, $activities, $Journey->$act->__toString()) . '</td>';
					echo '<td class="text-bold">' . HTML::datebox($dc, $Journey->$dc->__toString()) . '</td>';
					echo '</tr>';
				}
				?>
			</table>
		</div>
	</div>

	<p><br></p>

	<div class="row"><div class="col-sm-12"><div class="pull-right"><button title="save information" class="btn btn-warning dim" type="button" onclick="partialSave();"><i class="fa fa-save"></i> </button><?php echo HTML::renderWorkbookIcons('', true); ?></div></div></div>

	<?php echo $wb->getPageBottomLine(); ?>
</div> <!--.page 2 ends-->

<h1>Systems and resources</h1>
<div class="step-content">
	<?php echo $wb->getPageTopLine(); ?>

	<div class="row"><div class="col-sm-12"><div class="pull-right"><?php echo HTML::renderWorkbookIcons('', true); ?></div></div></div>


	<div class="row">
		<div class="col-sm-12">
			<p class="text-bold">Payment methods</p>
		</div>
	</div>

	<div class="row">
		<div class="col-sm-6">
			<p class="text-bold">EPOS -Electronic point of sale<img class="pull-right" src="module_eportfolio/assets/images/wb2_img2.png" /></p>
			<p class="text-bold">Till</p>
			<p>Whilst on the till you need to ensure that the service you give is professional, accurate and confident as you may be the only person the customer speaks to.</p>
			<p>Always keep an eye on the till point even if you are not working on it. This will mean that you will spot the build-up of queues before they happen!
				Remember 3 or more people are a queue.
			</p>
			<p>Customers have a range of methods that they can use to pay for their purchases. Some retail businesses will accept almost all possible methods of payment whereas others may only accept cash.</p>
			<p>It is therefore important that you know which methods of payment are acceptable where you work and how to process them. We will consider the different methods of payment that you may have to deal with in this section.</p>
			<p class="text-bold">Cash</p>
			<p>Despite a continued increase in electronic methods of payment, cash is still an important way for customers to pay for their purchases.</p>
			<p>Cash payments are relatively simple to process but there are risks associated with cash that you must always consider. You may be offered counterfeit notes and coins so you need to ensure that the cash you are accepting is genuine.</p>
		</div>
		<div class="col-sm-6">
			<p>Notes can be checked using an ultraviolet light or a security pen depending on the note. Key security features can be checked such as: raised print, size, see through window and microlettering.  New notes have been introduced to reduce the forgeries in circulation.</p>
			<p>It was estimated that 3% of all old £1 coins in circulation were forgeries. You should check the coins you accept. The new £1 coins are being introduced to reduce the forgeries in circulation.</p>
			<p>Take care when giving change that you do so accurately. It is usually considered good practice in these circumstances to confirm how much the customer has given you, count back the change to the customer and avoid putting the money tendered away until the customer is satisfied with their change.</p>
			<p>In this way both you and the customer can see how much was given if there is a query.</p>
			<p><?php echo ucfirst($wb->savers_or_sp); ?> has strict procedures for accepting and handling cash payments:</p>
			<ul style="margin-left: 15px;">
				<li>Total the sale</li>
				<li>Inform the customer of the amount</li>
				<li>Accept payment from the customer</li>
				<li>Count the cash given</li>
				<li>Ring the amount given into the till</li>
				<li>Place the money in the till or counter cache</li>
				<li>Take the change from the till</li>
				<li>Take the receipt from the till</li>
				<li>Close the till drawer</li>
				<li>Hand the receipt to the customer</li>
				<li>Count the customer’s change into their hand</li>
			</ul>
		</div>
	</div>

	<p><br></p>

	<div class="row"><div class="col-sm-12"><div class="pull-right"><?php echo HTML::renderWorkbookIcons('', true); ?></div></div></div>

	<?php echo $wb->getPageBottomLine(); ?>
</div> <!--.page 5 ends-->

<h1>Systems and resources</h1>
<div class="step-content">
	<?php echo $wb->getPageTopLine(); ?>


	<?php
	if($wb->savers_or_sp == 'savers')
	{
		echo '<div class="row"><div class="col-sm-12"><div class="pull-right">' . HTML::renderWorkbookIcons('', true) . '</div></div></div>';
	}
	else
	{
		echo '<div class="row"><div class="col-sm-12"><div class="pull-right">';
		if($feedback->HowToRegisterBeautyCard->Status->__toString() == 'NA')
			echo HTML::renderWorkbookIcons('HowToRegisterBeautyCard', false, 'btn-danger');
		else
			echo HTML::renderWorkbookIcons('HowToRegisterBeautyCard', false, 'btn-success');
		echo '</div></div></div>';
	}
	?>

	<div class="row">
		<div class="col-sm-12">
			<img class="pull-right" src="module_eportfolio/assets/images/wb2_img2.png" />
		</div>
	</div>
	<div class="row">
		<div class="col-sm-12">
			<p>All notes should be checked to make sure they are real using a counterfeit note checker.  If you suspect a note is counterfeit you should:</p>
			<ul style="margin-left: 15px; margin-bottom: 15px;">
				<li>Inform the customer discreetly and sensitively that you suspect the note is counterfeit</li>
				<li>Inform the customer that you will have to call for a member of management</li>
			</ul>
			<p>If your manager agrees the note is counterfeit, legally the store has a responsibility to ensure that it is taken out of circulation. Your customer should then be informed of this. The police should be called as they need to remove the note from your possession and investigate any signs of criminal behaviour.  Remember to ALWAYS handle any of these situations sensitively with your customer as they may not have realised that they have a counterfeit note.</p>
		</div>
	</div>
	<div class="row">
		<div class="col-sm-8">
			<p>Other forms of payment which are taken by <?php echo ucfirst($wb->savers_or_sp); ?> include:</p>
			<ul style="margin-left: 15px; margin-bottom: 15px;">
				<li>Coupons / vouchers</li>
				<li>Beauty card points</li>
				<li>Gift cards</li>
				<li>Credit / Debit cards</li>
			</ul>
		</div>
		<div class="col-sm-4">
			<img class="" src="module_eportfolio/assets/images/wb8_pg6_img1.png" />
		</div>
	</div>
	<div class="row">
		<div class="col-sm-8">
			<p>When handed a coupon by the customer, check the following:</p>
			<ul style="margin-left: 15px; margin-bottom: 15px;">
				<li>Is it still in date?</li>
				<li>Is it valid in <?php echo ucfirst($wb->savers_or_sp); ?>? </li>
				<li>Does the coupon relate to the item being purchased?</li>
				<li>Has the customer spent the qualifying amount to warrant the coupon discount?</li>
				<li>Always cross through the coupon and record the value on the coupon</li>
			</ul>
		</div>
		<?php if($wb->savers_or_sp != 'savers') { ?>
		<div class="col-sm-4">
			<img class="pull-right" src="module_eportfolio/assets/images/wb8_pg6_img2.png" />
		</div>
		<?php } ?>
	</div>
	<?php if($wb->savers_or_sp != 'savers') { ?>
	<div class="row">
		<div class="col-sm-12">
			<p>Beauty card points are collected when purchasing goods from <?php echo ucfirst($wb->savers_or_sp); ?> when the customer has a loyalty card. These points can be seen at the till point after all goods have been processed and the customer is ready to pay. They can also be found printed on all receipts so the customer knows exactly how much they can take off their shopping. Points are a form of payment and can be redeemed at the till point at the point of paying.</p>
		</div>
	</div>
	<div class="row" <?php echo $feedback->HowToRegisterBeautyCard->Status->__toString() == 'A'?'style="pointer-events:none;"':''; ?>>
		<div class="col-sm-12">
			<p class="text-bold"><img src="module_eportfolio/assets/images/wb2_img1.png" /> &nbsp;Explain how you would register a new customers Beauty card. </p>
			<textarea name="HowToRegisterBeautyCard" style="width: 100%" rows="7"><?php echo $answers->HowToRegisterBeautyCard->__toString(); ?></textarea>
		</div>
	</div>
	<?php } ?>

	<?php if($wb->savers_or_sp != 'savers' && $wb->id != '' && $_SESSION['user']->type == User::TYPE_ASSESSOR && ($wb->wb_status == Workbook::STATUS_LEARNER_COMPLETED || $wb->wb_status == Workbook::STATUS_BEING_CHECKED || $wb->wb_status == Workbook::STATUS_IV_REJECTED)) {?>
	<div class="row well">
		<div class="col-sm-12">
			<div class="box box-success box-solid">
				<div class="box-header with-border"><h3 class="box-title">Assessor Feedback</h3></div>
				<div class="box-body assessorFeedback" <?php echo $feedback->HowToRegisterBeautyCard->Status->__toString() == 'A'?'style="pointer-events:none;"':''; ?>>
					<div class="form-group">
						<label for="status_HowToRegisterBeautyCard" class="col-sm-12 control-label">Status:</label>
						<div class="col-sm-12">
							<?php
							echo HTML::selectChosen('status_HowToRegisterBeautyCard', $answer_status, $feedback->HowToRegisterBeautyCard->Status->__toString() == 'A'?$feedback->HowToRegisterBeautyCard->Status->__toString():'', false, true); ?>
						</div>
					</div>
					<div class="form-group">
						<label for="comments_HowToRegisterBeautyCard" class="col-sm-12 control-label">Comments:</label>
						<div class="col-sm-12">
							<textarea name="comments_HowToRegisterBeautyCard" rows="7" style="width: 100%;"><?php echo $feedback->HowToRegisterBeautyCard->Comments->__toString(); ?></textarea>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<?php } ?>

	<p><br></p>

	<?php
	if($wb->savers_or_sp == 'savers')
	{
		echo '<div class="row"><div class="col-sm-12"><div class="pull-right">' . HTML::renderWorkbookIcons('', true) . '</div></div></div>';
	}
	else
	{
		echo '<div class="row"><div class="col-sm-12"><div class="pull-right">';
		if($feedback->HowToRegisterBeautyCard->Status->__toString() == 'NA')
			echo HTML::renderWorkbookIcons('HowToRegisterBeautyCard', false, 'btn-danger');
		else
			echo HTML::renderWorkbookIcons('HowToRegisterBeautyCard', false, 'btn-success');
		echo '</div></div></div>';
	}
	?>


	<?php echo $wb->getPageBottomLine(); ?>
</div> <!--.page 6 ends-->

<h1>Systems and resources</h1>
<div class="step-content">
	<?php echo $wb->getPageTopLine(); ?>

	<div class="row"><div class="col-sm-12"><div class="pull-right"><?php echo HTML::renderWorkbookIcons('', true); ?></div></div></div>


	<div class="row">
		<div class="col-sm-12">
			<img class="pull-right" src="module_eportfolio/assets/images/wb2_img2.png" />
		</div>
	</div>

	<div class="row">
		<div class="col-sm-6">
			<p class="text-bold">Cards</p>
			<p>A wide range of plastic payment cards are available for customers to use when they pay for goods and services in shops and stores.</p>
			<p>Most customers will have more than one card in their purse or wallet that they could use when they pay you.</p>
			<p>Payment cards of all types carry security features to help reduce the risk of them being used fraudulently. These include holograms, information contained in the magnetic strip, the computer chip and customer signature.</p>
			<p>Most cards accepted for payment bear the Visa or MasterCard logos.</p>
			<p> A recent addition to the electronic methods of payment that customers can use is Contactless Payment.</p>
		</div>
		<div class="col-sm-6">
			<p>In businesses that accept Contactless Payment, customers can make purchases up to a maximum of £30 by touching their cards on a special reader.
				When they hear a bleep the transaction is complete. As an extra security feature some transactions will require the customer to enter a PIN.
			</p>
			<p>Most credit and debit cards are now chip and PIN.  The chip in a person’s card contains confidential information relating to that person.</p>
			<p>Chip & PIN removes the need for a customer to sign for a card transaction.  To authorise a chip and PIN payment, the customer places their card in a small terminal at the cash desk (there is usually no need for the cashier to hold the customer’s card during the transaction).</p>
		</div>
	</div>
	<div class="row">
		<div class="col-sm-12">
			<img class="text-center" src="module_eportfolio/assets/images/wb8_pg7_img1.png" />
		</div>
	</div>
	<div class="row">
		<div class="col-sm-12">
			<p>The customer then follows the on-screen instructions which appear on the terminal; they will be prompted to:</p>
			<ul style="margin-left: 15px; margin-bottom: 15px;">
				<li>Wait while the transaction is authorised</li>
				<li>Remove and re-insert the card if they have put it in the wrong way</li>
				<li>Confirm the amount by entering their PIN number via the keypad</li>
				<li>Remove their card on completion of the transaction</li>
				<li>On occasions, a card transaction will be declined. This may be because the PIN used is incorrect or for some other reason such as the card being reported lost or stolen, or if the customer has insufficient funds in their account to pay for the goods.</li>
			</ul>
			<p>If the PIN was incorrect the customer can retry as a mistake may have been made at first. If transactions continue to be declined you should politely advise your customer to contact their card provider. Customers may be able to pay you in some other way so that you can complete the sale. It is essential that you know and follow your company procedures for dealing with non-chip and PIN transactions.
				If in doubt, ask your Manager.
			</p>
		</div>
	</div>

	<p><br></p>

	<div class="row"><div class="col-sm-12"><div class="pull-right"><?php echo HTML::renderWorkbookIcons('', true); ?></div></div></div>

	<?php echo $wb->getPageBottomLine(); ?>
</div> <!--.page 7 ends-->

<h1>Systems and resources</h1>
<div class="step-content">
	<?php echo $wb->getPageTopLine(); ?>

	<div class="row"><div class="col-sm-12"><div class="pull-right"><?php echo HTML::renderWorkbookIcons('', true); ?></div></div></div>


	<div class="row">
		<div class="col-sm-12">
			<p class="text-bold">Store technology and equipment</p><img class="pull-right" src="module_eportfolio/assets/images/wb2_img2.png" />
		</div>
	</div>
	<div class="row">
		<div class="col-sm-6">
			<p class="text-bold">HHT-Hand held terminal</p>
			<p>The hand held terminal is a device that has many uses in a retail environment but is most associated with sending stock information to a main source (Head Office).</p>
			<p>To better understand why this is so important you need to be familiar with the stock system of your organisation.</p>
			<p>In <?php echo ucfirst($wb->savers_or_sp); ?> we operate an Automatic Replenishment System. Simply, it is a way of replenishing stock automatically. Your store is set up with a level of stock based on your size of store, planograms in each department and the historical sales.</p>
			<p>As stock is sold through your tills, your back office computer sends information to head office and the system automatically reorders the stock sold. This is simply put however there are lots of reasons that stock may not be replenished correctly and therefore the use of the HHT is really important.</p>
			<p>Some of the reasons you may not get the correct stock redelivered are:</p>
			<ul style="margin-left: 15px; margin-bottom: 15px;">
				<li>Stock theft</li>
				<li>Seasonal uplift in sales</li>
				<li>Damages</li>
				<li>Miss-picks</li>
			</ul>
			<p>There are a number of reasons you may also need additional stock that would not ordinarily be sent in automatically:</p>
			<ul style="margin-left: 15px; margin-bottom: 15px;">
				<li>Additional displays in store</li>
				<li>Competitor closure</li>
				<li>Customer order</li>
			</ul>
		</div>
		<div class="col-sm-6">
			<p>The <span class="text-bold">HHT</span> is a really important tool to correct balances of stock periodically to enable your auto replenishment system to work effectively and to enable you to have the correct stock available for the customer. </p>
			<p>Some of the activities are below:</p>
			<ul style="margin-left: 15px; margin-bottom: 15px;">
				<li>Event counting/Zero to zero</li>
				<li>Stock takes</li>
			</ul>
			<p>The HHT is also used for a range of activities that are completed in stores:</p>
			<ul style="margin-left: 15px; margin-bottom: 15px;">
				<li>Processing damages</li>
				<li>Price checking</li>
				<li>Tag requests</li>
				<li>Tester orders</li>
				<li>Managers orders</li>
			</ul>
			<p><span class="text-bold">iPad</span> is the latest addition in technology in <?php echo ucfirst($wb->savers_or_sp); ?> and all stores now have at least one. It has many uses and was introduced and is invaluable for both customers and employees.
				<?php if($wb->savers_or_sp != 'savers') { ?>
				Some of its uses are:</p>
			<ul style="margin-left: 15px; margin-bottom: 15px;">
				<li>New team member training</li>
				<li>Training updates for existing employees</li>
				<li>Receiving online customer orders</li>
				<li>Product information</li>
			</ul>
				<?php } ?>
			<p class="text-bold">Back office</p>
			<p>This is the main computer that collects and sends information to Head Office. In addition it is used to:</p>
			<ul style="margin-left: 15px; margin-bottom: 15px;">
				<li>Plan staffing schedules</li>
				<li>Process time and attendance</li>
				<li>Receive and send communications </li>
			</ul>
		</div>
	</div>

	<p><br></p>

	<div class="row"><div class="col-sm-12"><div class="pull-right"><?php echo HTML::renderWorkbookIcons('', true); ?></div></div></div>

	<?php echo $wb->getPageBottomLine(); ?>
</div> <!--.page 8 ends-->

<h1>Systems and resources</h1>
<div class="step-content">
	<?php echo $wb->getPageTopLine(); ?>

	<div class="row"><div class="col-sm-12"><div class="pull-right">
		<?php
		if($feedback->SystemsUse->Status->__toString() == 'NA')
			echo HTML::renderWorkbookIcons('SystemsUse', false, 'btn-danger');
		else
			echo HTML::renderWorkbookIcons('SystemsUse', false, 'btn-success');
		?>
	</div></div></div>


	<div class="row">
		<div class="col-sm-12">
			<img class="pull-right" src="module_eportfolio/assets/images/wb2_img2.png" />
		</div>
	</div>
	<div class="row">
		<div class="col-sm-12">
			<p class="text-bold"><img src="module_eportfolio/assets/images/wb2_img1.png" /> &nbsp;List 5 different systems, equipment and/or technology used in <?php echo ucfirst($wb->savers_or_sp); ?> and explain how each is used to meet customer needs effectively.</p>
		</div>
		<div class="col-sm-12 table-responsive">
			<table class="table row-border table-bordered" <?php echo $feedback->SystemsUse->Status->__toString() == 'A'?'style="pointer-events:none;"':''; ?>>
				<tr><th style="width: 25%;" class="text-center">System / Equipment / Technology</th><th class="text-center" style="width: 75%;">Comments</th></tr>
				<?php
				$_sd = $answers->SystemsUse->SuperdrugSystems;
				for($i = 1; $i <= 5; $i++)
				{
					$key = 'Set'.$i;
					$f1_val = isset($_sd->$key->System)?$_sd->$key->System->__toString():'';
					$f2_val = isset($_sd->$key->Comments)?$_sd->$key->Comments->__toString():'';
					echo '<tr>';
					echo '<td><textarea name="'.$key.'_System" style="width: 100%;" rows="3">' . $f1_val . '</textarea> </td>';
					echo '<td><textarea name="'.$key.'_Comments" style="width: 100%;" rows="5">' . $f2_val . '</textarea> </td>';
					echo '</tr>';
				}
				?>
			</table>
		</div>
		<div class="col-sm-12" <?php echo $feedback->SystemsUse->Status->__toString() == 'A'?'style="pointer-events:none;"':''; ?>>
			<p class="text-bold"><img src="module_eportfolio/assets/images/wb2_img1.png" /> &nbsp;What is the impact to customer service of not using these systems, equipment and technology effectively?</p>
			<p><textarea name="ImpactOfNoSystems" rows="7" style="width: 100%;"><?php echo $answers->SystemsUse->ImpactOfNoSystems->__toString(); ?></textarea> </p>
		</div>
	</div>
	<div class="row">
		<div class="col-sm-12 text-center">
			<img src="module_eportfolio/assets/images/wb8_pg10_img1.png" />
		</div>
	</div>

	<?php if($wb->id != '' && $_SESSION['user']->type == User::TYPE_ASSESSOR && ($wb->wb_status == Workbook::STATUS_LEARNER_COMPLETED || $wb->wb_status == Workbook::STATUS_BEING_CHECKED || $wb->wb_status == Workbook::STATUS_IV_REJECTED)) {?>
	<div class="row well">
		<div class="col-sm-12">
			<div class="box box-success box-solid">
				<div class="box-header with-border"><h3 class="box-title">Assessor Feedback</h3></div>
				<div class="box-body assessorFeedback" <?php echo $feedback->SystemsUse->Status->__toString() == 'A'?'style="pointer-events:none;"':''; ?>>
					<div class="form-group">
						<label for="status_SystemsUse" class="col-sm-12 control-label">Status:</label>
						<div class="col-sm-12">
							<?php
							echo HTML::selectChosen('status_SystemsUse', $answer_status, $feedback->SystemsUse->Status->__toString() == 'A'?$feedback->SystemsUse->Status->__toString():'', false, true); ?>
						</div>
					</div>
					<div class="form-group">
						<label for="comments_SystemsUse" class="col-sm-12 control-label">Comments:</label>
						<div class="col-sm-12">
							<textarea name="comments_SystemsUse" rows="7" style="width: 100%;"><?php echo $feedback->SystemsUse->Comments->__toString(); ?></textarea>
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
		if($feedback->SystemsUse->Status->__toString() == 'NA')
			echo HTML::renderWorkbookIcons('SystemsUse', false, 'btn-danger');
		else
			echo HTML::renderWorkbookIcons('SystemsUse', false, 'btn-success');
		?>
	</div></div></div>

	<?php echo $wb->getPageBottomLine(); ?>
</div> <!--.page 9 ends-->

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

	<?php if($disable_answers){?>

		$("#frm_wb_systems_and_resources :input").not(".assessorFeedback :input, #signature_text, #frm_wb_systems_and_resources :input[type=hidden], .btnViewSectionHistory, .btnViewAssessorComments, #iv_status, #iv_comments, #reopen_comments").prop("disabled", true);

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
							var myForm = document.forms['frm_wb_systems_and_resources'];
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
				var myForm = document.forms['frm_wb_systems_and_resources'];
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

					var myForm = document.forms['frm_wb_systems_and_resources'];
					myForm.elements['full_save'].value = 'Y';
					myForm.elements['full_save_feedback'].value = 'Y';
					window.onbeforeunload = null;
					myForm.submit();

				},
				'Save And Come Back Later':function () {

					var myForm = document.forms['frm_wb_systems_and_resources'];
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
		$('#frm_wb_systems_and_resources :input[name=full_save]').val('N');
		$($('#frm_wb_systems_and_resources').serializeArray()).each(function(i, field)
		{
			document.forms["frm_wb_systems_and_resources"].elements[field.name].value = field.value.replace(/£/g, "GBP");
		});
		$.ajax({
			type:'POST',
			url:'do.php?_action=save_wb_systems_and_resources',
			data: $('#frm_wb_systems_and_resources').serialize(),
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
