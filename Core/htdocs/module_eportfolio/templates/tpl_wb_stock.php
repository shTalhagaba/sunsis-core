<?php /* @var $wb WBStock */ ?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<title>Stock workbook</title>
	<meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
	<link rel="stylesheet" href="/assets/adminlte/bootstrap/css/bootstrap.min.css">
	<link rel="stylesheet" href="/assets/adminlte/dist/css/AdminLTE.min.css">
	<link href="module_eportfolio/assets/jquery.steps.css" rel="stylesheet">
	<link rel="stylesheet" href="/assets/adminlte/plugins/jQueryUI/jquery-ui.css">
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.5.0/css/font-awesome.min.css">
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

<form name="frm_wb_stock" id="frm_wb_stock" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
<input type="hidden" name="_action" value="save_wb_stock" />
<input type="hidden" name="id" value="<?php echo $wb->id; ?>" />
<input type="hidden" name="tr_id" value="<?php echo $wb->tr_id; ?>" />
<input type="hidden" name="wb_status" id="wb_status" value="" />
<input type="hidden" name="full_save" id="full_save" value="<?php echo $wb->full_save; ?>" />
<input type="hidden" name="full_save_feedback" id="full_save_feedback" value="<?php echo $wb->full_save_feedback; ?>" />
<div class="container-float">
<div class="wrapper" style="background-color: #ffffff;">

<header class="main-header"><span style="margin-left: 50%;"><?php echo DAO::getSingleValue($link, "SELECT title FROM student_frameworks WHERE tr_id = '{$wb->tr_id}'"); ?></span></header>

<div class="content-wrapper" style="background-color: #ffffff;">

<?php echo $wb->savers_or_sp == 'savers' ? '<section class="content-header"><h1 style="color: #0000ff;">Stock</h1></section>' : '<section class="content-header"><h1>Stock</h1></section>' ?>

<section class="content">

<div id="wizard">

<h1>Stock</h1>
<div class="step-content">
	<?php echo $wb->getPageTopLine(); ?>
	<div style="position: absolute; top: 40%; right: 50%;" class="lead">
		<?php echo $wb->savers_or_sp == 'savers' ? '<h2 class="text-bold" style="color: #0000ff;">Stock</h2>' : '<h2 class="text-bold">Stock</h2>' ?>
		<p class="text-center" >Module</p>
	</div>

	<?php echo $wb->getPageBottomLine(); ?>
</div> <!--.page 1 ends-->

<h1>Stock</h1>
<div class="step-content">
	<?php echo $wb->getPageTopLine(); ?>

	<div class="row"><div class="col-sm-12"><div class="pull-right"><?php echo HTML::renderWorkbookIcons('', true); ?></div></div></div>


	<div class="row">
		<div class="col-sm-12">
			<p>This section is about Stock management in the workplace and what you need to know and do.</p>
			<p>Stock management is an essential part of any retail business. It is important for you to know all about stock and stock management to ensure you can do your job correctly and to support the store to be a successful business.</p>
			<p>For this you must be able to:</p>
			<ul style="margin-left: 15px; margin-bottom: 15px;">
				<li>Understand what stock and stock management is</li>
				<li>Understand how the stock gets to you in store</li>
				<li>Be able to follow all procedures relating to stock</li>
				<li>Support colleagues to work within company guidelines</li>
				<li>Managing replenishment and storage</li>
				<li>Take action to prevent damage and loss</li>
			</ul>
		</div>
	</div>

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
				$activities = $wb->savers_or_sp == 'savers' ? array(array('Workbook', 'Workbook'),array('iPad', 'iPad'),array('InStore', 'In-store activity')) : array(array('Workbook', 'Workbook'),array('iPad', 'iPad'),array('InStore', 'In-store activity'));
				$items = WBStock::getLearningJourneyItems($wb->savers_or_sp);
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

<h1>Stock</h1>
<div class="step-content">
	<?php echo $wb->getPageTopLine(); ?>

	<div class="row"><div class="col-sm-12"><div class="pull-right"><?php echo HTML::renderWorkbookIcons('', true); ?></div></div></div>
	<div class="row">
		<div class="col-md-3 col-md-offset-2">
			<div style="padding: 15px; border: #000000 solid 2px;"><span class="text-blue">Stock</span> <i>is the goods or merchandise kept on the premises of a shop or warehouse and available for sale or distribution.</i></div>
		</div>
		<div class="col-md-3 col-md-offset-2">
			<div style="padding: 15px; border: #0000ff dashed 2px;"><span class="text-blue">Stock management</span> <i>is the function of understanding the stock mix of a company and the different demands on that <span class="text-blue">stock.</span></i></div>
		</div>
	</div>
	<div class="row">
		<div class="col-sm-12">
			<p><br></p>
			<p>The demands on stock are influenced by both external and internal factors to keep supplies at a reasonable or required level. Let's start by having a look at where our stock comes from and the different stages of how it gets to the customer.</p>
		</div>
		<div class="col-sm-12 table-responsive">
			<table class="table text-center">
				<tr>
					<td style='width: 15%; vertical-align: middle;font-size:14.0pt;padding:10px;text-align: center;'><b class="text-light-blue">Manufacturer</b></td>
					<td style="width: 5%; vertical-align: middle;"><img class="img-responsive" src="module_eportfolio/assets/images/wb_r09_pg3_img1.png" /></td>
					<td class="bg-light-blue" style="vertical-align: middle;font-size:14.0pt;padding:10px;text-align: center;"><p>The manaufacture is the person or company who make the goods we sell</p></td>
				</tr>
				<tr>
					<td style='vertical-align: middle;font-size:14.0pt;padding:10px;text-align: center;'><b class="text-red">Supplier</b></td>
					<td style="width: 5%; vertical-align: middle;"><img class="img-responsive" src="module_eportfolio/assets/images/wb_r09_pg3_img1.png" /></td>
					<td class="bg-red" style="vertical-align: middle;font-size:14.0pt;padding:10px;text-align: center;"><p>A supplier is an entity that supplies these goods to us. They can be the same person/company as the manufacturer.</p> </td>
				</tr>
				<tr>
					<td style='vertical-align: middle;font-size:14.0pt;padding:10px;text-align: center;'><b class="text-orange">Distribution Centre</b></td>
					<td style="width: 5%; vertical-align: middle;"><img class="img-responsive" src="module_eportfolio/assets/images/wb_r09_pg3_img1.png" /></td>
					<td class="bg-orange" style="vertical-align: middle;font-size:14.0pt;padding:10px;text-align: center;"><p>The distribution centre is a warehouse or other specialised building, which is stocked with these goods so they can be redistributed to store or direct to the customer</p> </td>
				</tr>
				<tr>
					<td style='vertical-align: middle;font-size:14.0pt;padding:10px;text-align: center;'><b>Supply Chain</b></td>
					<td style="width: 5%; vertical-align: middle;"><img class="img-responsive" src="module_eportfolio/assets/images/wb_r09_pg3_img1.png" /></td>
					<td style="vertical-align: middle;font-size:14.0pt;padding:10px;text-align: center; border: #000000 solid 3px;"><div>A supply chain is a system of organisations, people, activities, information, and resources involved in moving the goods from supplier to customer</div> </td>
				</tr>
				<tr>
					<td style='vertical-align: middle;font-size:14.0pt;padding:10px;text-align: center;'><b class="text-green">Transport Network</b></td>
					<td style="width: 5%; vertical-align: middle;"><img class="img-responsive" src="module_eportfolio/assets/images/wb_r09_pg3_img1.png" /></td>
					<td  class="bg-green" style="vertical-align: middle;font-size:14.0pt;padding:10px;text-align: center;"><p>The transport network describes a structure which allows the movement or flow of the goods to the store. It could be via road, rail, air or sea.</p> </td>
				</tr>
				<tr>
					<td style='vertical-align: middle;font-size:14.0pt;padding:10px;text-align: center;'><b class="text-blue">Store</b></td>
					<td style="width: 5%; vertical-align: middle;"><img class="img-responsive" src="module_eportfolio/assets/images/wb_r09_pg3_img1.png" /></td>
					<td class="bg-blue" style="vertical-align: middle;font-size:14.0pt;padding:10px;text-align: center;"><p>The goods arrive at Superdrug / Savers and are prepared for sale by the store team</p> </td>
				</tr>
				<tr>
					<td style='vertical-align: middle;font-size:14.0pt;padding:10px;text-align: center;'><b class="text-pink">Customer</b></td>
					<td style="width: 5%; vertical-align: middle;"><img class="img-responsive" src="module_eportfolio/assets/images/wb_r09_pg3_img1.png" /></td>
					<td class="bg-pink" style="vertical-align: middle;font-size:14.0pt;padding:10px;text-align: center;"><p>Customers are now able to purchase goods or services from our stores</p> </td>
				</tr>
			</table>
		</div>
	</div>

	<p><br></p>

	<div class="row"><div class="col-sm-12"><div class="pull-right"><?php echo HTML::renderWorkbookIcons('', true); ?></div></div></div>

	<?php echo $wb->getPageBottomLine(); ?>
</div> <!--.page 3 ends-->

<h1>Stock</h1>
<div class="step-content">
	<?php echo $wb->getPageTopLine(); ?>

	<div class="row"><div class="col-sm-12"><div class="pull-right"><?php echo HTML::renderWorkbookIcons('', true); ?></div></div></div>

	<div class="row">
		<div class="col-sm-12">
			<p>Let's look at when it reaches our distribution centre and do a virtual tour <a target="_blank" href="https://www.youtube.com/watch?v=qonL6m0UXL0&feature=youtu.be">https://youtu.be/qonL6m0UXL0</a></p>
		</div>
	</div>
	<div class="row">
		<div class="col-md-3 col-md-offset-2">
			<img class="img-responsive" src="module_eportfolio/assets/images/wb_r09_pg4_img1.png" />
		</div>
		<div class="col-md-3 col-md-offset-2">
			<img class="img-responsive" src="module_eportfolio/assets/images/wb_r09_pg4_img2.png" />
		</div>
	</div>
	<div class="row">
		<div class="col-sm-12">
			<p class="text-bold">Next we will look at some of the different ways we manage our stock in <?php echo ucfirst($wb->savers_or_sp); ?></p>
		</div>
		<div class="col-sm-6">
			<p><span class="text-bold">Stock ordering </span>is the ordering of new stock to replenish shelves in a retail business. Stock ordering is done via a computerised system which is known as Automatic Replenishment. Manual orders can also be placed to fulfil unexpected demands.</p>
			<p><span class="text-bold">Replenishment stock control </span>is a system for replacing stock on shelves and display areas when customers have made purchases.</p>
			<p>A <span class="text-bold">stocktake</span> is a count of all of the stock in store. Within <?php echo ucfirst($wb->savers_or_sp); ?> this is done a minimum of twice a year. The purpose of a stock take is to confirm how much stock is physically in the store and then reconcile that figure with the amount shown at head office. This will also highlight how much stock has been lost / stolen.</p>
			<p><span class="text-bold">Event counting/mini stocktakes</span> is a count of specific stock lines on a weekly basis, to correct store stock (on hand) with head office records of stock. This is usually done on high value / departments of stock to correct balances in between full stock takes.</p>
			<p><span class="text-bold">Stock rotation</span> is displaying and storing stock in a way that ensures the shortest life product is always used first. This process ensures sell through of oldest stock first and avoids reducing goods.</p>
		</div>
		<div class="col-sm-6">
			<p><span class="text-bold">Date code </span>checking is a process to ensure all products which have a sell by / best before date are sold before the date expires. In <?php echo ucfirst($wb->savers_or_sp); ?> this is done weekly to ensure we comply with the law and move slow selling products through the system.</p>
			<p>The <span class="text-bold">damages </span>process is system of identifying and removing products which are unfit for sale. This ensures we are complying with law and legislation. Products are identified as unfit for sale and disposed of or damaged but saleable and reduced accordingly.</p>
			<p><span class="text-bold">Stock records </span>are documented evidence of numbers / actions that have taken place in store to ensure that stock is controlled and legal processes are complied with. They should be kept in store to support your stock management processes. Head office also has a record of all your store stock balances.</p>
			<p><span class="text-bold">Stock security </span>is a process of protecting stock from theft. It includes tagging of high value / high risk products, using high visibility stickers on key lines, barrier checks and floor walks. Other methods of protection include: security shutters, CCTV, store guards, radio link, store alarms and till bell warnings.</p>
		</div>
	</div>
	<p><br></p>

	<div class="row"><div class="col-sm-12"><div class="pull-right"><?php echo HTML::renderWorkbookIcons('', true); ?></div></div></div>

	<?php echo $wb->getPageBottomLine(); ?>
</div> <!--.page 4 ends-->

<h1>Stock</h1>
<div class="step-content">
	<?php echo $wb->getPageTopLine(); ?>

	<div class="row"><div class="col-sm-12"><div class="pull-right"><?php echo HTML::renderWorkbookIcons('', true); ?></div></div></div>
	<div class="row">
		<div class="col-md-8 col-md-offset-2">
			<div class="bg-blue"><blockquote>If all of the stock management processes are completed accurately it should ensure the right levels of stock are maintained.</blockquote></div>
		</div>
	</div>
	<div class="row">
		<div class="col-sm-12 text-center">
			<p class="text-bold">BENEFITS FOR GOOD STOCK MANAGEMENT</p>
			<p>Maintains availability of stock for customers (you can’t sell what you haven't got!)</p>
			<p>Maintains good displays (encourages customers to buy – additional products)</p>
			<p>Helps to meet sales targets</p>
			<p>Deterring internal and external theft</p>
			<p>Reduces and identifies shrinkage and you can do something about it.</p>
			<p>Brand image / reputation (keeps the image and reputation intact)</p>
			<p>Complies with law and legislation (does what it says on the tin!)</p>
			<p>Supports good customer service (gives customers what they want)</p>
		</div>
	</div>
	<div class="row">
		<div class="col-md-8 col-md-offset-2">
			<div class="bg-blue"><blockquote>Bad stock management can lead to a poor performing store which will lose customers, stock and money. It can also damage the brand and reputation of the company.</blockquote></div>
		</div>
	</div>

	<p><br></p>

	<div class="row"><div class="col-sm-12"><div class="pull-right"><?php echo HTML::renderWorkbookIcons('', true); ?></div></div></div>

	<?php echo $wb->getPageBottomLine(); ?>
</div> <!--.page 5 ends-->

<h1>Stock</h1>
<div class="step-content">
	<?php echo $wb->getPageTopLine(); ?>

	<div class="row"><div class="col-sm-12"><div class="pull-right"><?php echo HTML::renderWorkbookIcons('', true); ?></div></div></div>

	<div class="row">
		<div class="col-sm-12">
			<p class="text-bold">Hygiene standards</p>
			<p>Good housekeeping and personal hygiene is essential in reducing the risk of illness.</p>
			<p>Germs multiply in warm, moist conditions.</p>
			<p>You must wash your hands:</p>
			<ul style="margin-left: 15px; margin-bottom: 10px;">
				<li>Before you start work</li>
				<li>Before you handle food</li>
				<li>After you have been to the toilet</li>
				<li>After smoking a cigarette</li>
			</ul>
			<p>Warehouse storage areas must be kept clean. Surfaces must be wiped down regularly. If there is a spillage place a ‘wet floor’ sign next to it and fetch a mop and bucket. Always pick up anything that is out of place and return it to its proper home. Appropriate attention must always be paid to food storage areas to prevent the risk of bacteria and contamination.
			<p class="text-bold">Storage Facilities and Conditions</p>
			<p>When goods arrive at the store they will need to be stored in different locations.</p>
			<p>These locations may be:</p>
			<img class="img-responsive pull-right" src="module_eportfolio/assets/images/wb_r09_pg6_img1.png" />
			<ul style="margin-left: 15px; margin-bottom: 10px;">
				<li>Warehouse shelves</li>
				<li>Shop floor shelves</li>
				<li>Shop floor drawers</li>
				<li>Warehouse on wheels (dollies)</li>
				<li>Security room</li>
				<li>Chillers</li>
				<li>Freezers</li>
			</ul>
			<p>All storage areas need to be fit for purpose. They need to be:</p>
			<ul style="margin-left: 15px; margin-bottom: 10px;">
				<li>Safe</li>
				<li>In working order</li>
				<li>Sufficient</li>
				<li>Adequate</li>
			</ul>
			<img class="img-responsive pull-right" src="module_eportfolio/assets/images/wb_r09_pg6_img2.png" />
			<p>Some of the conditions that need to be considered are:</p>
			<ul style="margin-left: 15px; margin-bottom: 10px;">
				<li>Dry – this is to ensure that packing and contents remain in a satisfactory condition</li>
				<li>Raised – away from potential hazards</li>
				<li>Cool – to avoid raising the temperature of the contents</li>
				<li>Dark – away from the potential damage caused by direct sunlight</li>
				<li>Chilled – to ensure the product remains at its recommended temperature</li>
				<li>Frozen – to ensure the product remains at its recommended temperature</li>
			<ul>
		</div>
	</div>
	<p><br></p>

	<div class="row"><div class="col-sm-12"><div class="pull-right"><?php echo HTML::renderWorkbookIcons('', true); ?></div></div></div>

	<?php echo $wb->getPageBottomLine(); ?>
</div> <!--.page 6 ends-->

<h1>Stock</h1>
<div class="step-content">
	<?php echo $wb->getPageTopLine(); ?>

	<div class="row"><div class="col-sm-12"><div class="pull-right">
		<?php
		if($feedback->ProtectingStock->Status->__toString() == 'NA')
			echo HTML::renderWorkbookIcons('ProtectingStock', false, 'btn-danger');
		else
			echo HTML::renderWorkbookIcons('ProtectingStock', false, 'btn-success');
		?>
	</div></div></div>

	<div class="row">
		<div class="col-sm-12" <?php echo $feedback->ProtectingStock->Status->__toString() == 'A'?'style="pointer-events:none;"':''; ?>>
			<p class="text-bold"><img src="module_eportfolio/assets/images/wb2_img1.png" /> &nbsp;What storage facilities do you have in your store?</p>
			<p><textarea name="ProtectingStockQ1" style="width: 100%;" rows="5"><?php echo $answers->ProtectingStock->Q1->__toString(); ?></textarea> </p>
			<p class="text-bold"><img src="module_eportfolio/assets/images/wb2_img1.png" /> &nbsp;Speak to your manager / mentor about preparing the storage areas and checking the equipment in preparation for your next <?php echo ucfirst($wb->savers_or_sp); ?> delivery. Complete this activity and record below what you have done.</p>
			<p><textarea name="ProtectingStockQ2" style="width: 100%;" rows="7"><?php echo $answers->ProtectingStock->Q2->__toString(); ?></textarea> </p>
			<p>Protecting stock</p>
			<p>Another area to consider when maintaining security is the protection of stock. No organisation can afford to lose or damage stock, the security of stock must be high priority from the moment it arrives in to store. It only takes a few moments for someone to steal or damage stock so you need to be alert and act responsibly.</p>
			<p>Stock quality is important to your employer and the customer, no customer wants to receive goods of poor quality. Customers will avoid picking damaged, old, out of date or tatty goods so why should you display these for the customer? If it is not fit for purpose you will need to reduce it, write it off and dispose of it. Maintaining the quality of stock is as important as having the correct amount.</p>
			<p>When handling, moving or storing goods, help to maintain the quality of the stock by checking for:</p>
			<ul style="margin-left: 15px; margin-bottom: 10px;">
				<li>Any damage</li>
				<li>Any deterioration</li>
				<li>Any stock with short codes</li>
				<li>Stock with expired dates</li>
			</ul>
		</div>
	</div>
	<?php if($wb->id != '' && $_SESSION['user']->type == User::TYPE_ASSESSOR && ($wb->wb_status == Workbook::STATUS_LEARNER_COMPLETED || $wb->wb_status == Workbook::STATUS_BEING_CHECKED || $wb->wb_status == Workbook::STATUS_IV_REJECTED)) {?>
	<div class="row well">
		<div class="col-sm-12">
			<div class="box box-success box-solid">
				<div class="box-header with-border"><h3 class="box-title">Assessor Feedback</h3></div>
				<div class="box-body assessorFeedback" <?php echo $feedback->ProtectingStock->Status->__toString() == 'A'?'style="pointer-events:none;"':''; ?>>
					<div class="form-group">
						<label for="status_ProtectingStock" class="col-sm-12 control-label">Status:</label>
						<div class="col-sm-12">
							<?php echo HTML::selectChosen('status_ProtectingStock', $answer_status, $feedback->ProtectingStock->Status->__toString() == 'A'?$feedback->ProtectingStock->Status->__toString():'', false, true); ?>
						</div>
					</div>
					<div class="form-group">
						<label for="comments_ProtectingStock" class="col-sm-12 control-label">Comments:</label>
						<div class="col-sm-12">
							<textarea name="comments_ProtectingStock" rows="7" style="width: 100%;"><?php echo $feedback->ProtectingStock->Comments->__toString(); ?></textarea>
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
		if($feedback->ProtectingStock->Status->__toString() == 'NA')
			echo HTML::renderWorkbookIcons('ProtectingStock', false, 'btn-danger');
		else
			echo HTML::renderWorkbookIcons('ProtectingStock', false, 'btn-success');
		?>
	</div></div></div>

	<?php echo $wb->getPageBottomLine(); ?>
</div> <!--.page 7 ends-->

<h1>Stock</h1>
<div class="step-content">
	<?php echo $wb->getPageTopLine(); ?>

	<div class="row"><div class="col-sm-12"><div class="pull-right">
		<?php
		if($feedback->PossibleRisks->Status->__toString() == 'NA')
			echo HTML::renderWorkbookIcons('PossibleRisks', false, 'btn-danger');
		else
			echo HTML::renderWorkbookIcons('PossibleRisks', false, 'btn-success');
		?>
	</div></div></div>

	<div class="row">
		<div class="col-sm-12">
			<p class="text-bold">Damage to Stock</p>
			<p>Stock can be damaged at various stages after it has entered the store. To prevent stock getting damaged during storage and display, guidelines must be followed especially in relation to maintaining the quality of the stock.  Stock can also be at risk of damage whilst it is being moved; incorrect use of equipment or throwing stock can result in packaging getting torn or dented.</p>
			<p>What indicators might alert you to stock being at risk?</p>
			<img class="img-responsive pull-right" src="module_eportfolio/assets/images/wb_r09_pg8_img1.png" />
			<ul style="margin-left: 15px; margin-bottom: 10px;">
				<li>Stock may be moved or stored incorrectly
				<li>Stock may be leaking or broken
				<li>Stock may be stored at the incorrect temperature
			</ul>
			<p class="text-bold">What steps need to be taken to deal with damaged stock?</p>
			<p>The action you may take will vary on your own area of responsibility, there are however some basic guidelines:</p>
		</div>
		<div class="col-sm-6">
			<p class="text-bold">DO</p>
			<ul style="margin-left: 15px; margin-bottom: 10px;">
				<li>Follow organisational procedures
				<li>Report the damage to your line  manager
				<li>Act promptly
				<li>Clean the area
				<li>Warn others
			</ul>
		</div>
		<div class="col-sm-6">
			<p class="text-bold">DON'T</p>
			<ul style="margin-left: 15px; margin-bottom: 10px;">
				<li>Forget safety
				<li>Ignore the damage
				<li>Put other stock at risk of damage
				<li>Throw away damaged stock until you are told to do so
			</ul>
		</div>
		<div class="col-sm-12">
			<p>If you find damaged stock you need to report it to the appropriate person. Warning others will hopefully minimise or prevent other stock getting damaged.</p>
			<p>Stock deterioration</p>
			<p class="text-bold">Goods deteriorate because they are handled incorrectly, stored incorrectly or allowed to perish or go out of date. Ensuring that you handle, move, rotate and store / display goods carefully will ensure you protect your stock and profits. To do this correctly and efficiently ensure that the storage and display areas are kept tidy so that stock can be found when it is needed.</p>
			<p class="text-bold"><img src="module_eportfolio/assets/images/wb2_img1.png" /> &nbsp;Complete the table below to show the possible risks to products in each scenario</p>
			<div class="table-responsive">
				<table class="table" <?php echo $feedback->PossibleRisks->Status->__toString() == 'A'?'style="pointer-events:none;"':''; ?>>
					<tr><th>Scenario</th><th>Possible risk to products</th></tr>
					<tr><td>Box of crisps stored in warehouse</td><td>Box gets left in the warehouse and goes out of date</td></tr>
					<tr><td>Trolley stacked too high with products</td><td><textarea name="Scenario1" style="width: 100%;"><?php echo $answers->PossibleRisks->Scenario1->__toString(); ?></textarea> </td></tr>
					<tr><td>High value items not tagged</td><td><textarea name="Scenario2" style="width: 100%;"><?php echo $answers->PossibleRisks->Scenario2->__toString(); ?></textarea> </td></tr>
					<tr><td>Toilet paper stored directly on the floor</td><td><textarea name="Scenario3" style="width: 100%;"><?php echo $answers->PossibleRisks->Scenario3->__toString(); ?></textarea> </td></tr>
					<tr><td>Stock stored in direct sunlight</td><td><textarea name="Scenario4" style="width: 100%;"><?php echo $answers->PossibleRisks->Scenario4->__toString(); ?></textarea> </td></tr>
				</table>
			</div>
		</div>
	</div>
	<?php if($wb->id != '' && $_SESSION['user']->type == User::TYPE_ASSESSOR && ($wb->wb_status == Workbook::STATUS_LEARNER_COMPLETED || $wb->wb_status == Workbook::STATUS_BEING_CHECKED || $wb->wb_status == Workbook::STATUS_IV_REJECTED)) {?>
	<div class="row well">
		<div class="col-sm-12">
			<div class="box box-success box-solid">
				<div class="box-header with-border"><h3 class="box-title">Assessor Feedback</h3></div>
				<div class="box-body assessorFeedback" <?php echo $feedback->PossibleRisks->Status->__toString() == 'A'?'style="pointer-events:none;"':''; ?>>
					<div class="form-group">
						<label for="status_PossibleRisks" class="col-sm-12 control-label">Status:</label>
						<div class="col-sm-12">
							<?php echo HTML::selectChosen('status_PossibleRisks', $answer_status, $feedback->PossibleRisks->Status->__toString() == 'A'?$feedback->PossibleRisks->Status->__toString():'', false, true); ?>
						</div>
					</div>
					<div class="form-group">
						<label for="comments_PossibleRisks" class="col-sm-12 control-label">Comments:</label>
						<div class="col-sm-12">
							<textarea name="comments_PossibleRisks" rows="7" style="width: 100%;"><?php echo $feedback->PossibleRisks->Comments->__toString(); ?></textarea>
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
		if($feedback->PossibleRisks->Status->__toString() == 'NA')
			echo HTML::renderWorkbookIcons('PossibleRisks', false, 'btn-danger');
		else
			echo HTML::renderWorkbookIcons('PossibleRisks', false, 'btn-success');
		?>
	</div></div></div>
	<?php echo $wb->getPageBottomLine(); ?>
</div> <!--.page 8 ends-->

<h1>Stock</h1>
<div class="step-content">
	<?php echo $wb->getPageTopLine(); ?>
	<div class="row"><div class="col-sm-12"><div class="pull-right">
		<?php
		if($feedback->InStoreStorageFacilities->Status->__toString() == 'NA')
			echo HTML::renderWorkbookIcons('InStoreStorageFacilities', false, 'btn-danger');
		else
			echo HTML::renderWorkbookIcons('InStoreStorageFacilities', false, 'btn-success');
		?>
	</div></div></div>

	<div class="row">
		<div class="col-sm-12" <?php echo $feedback->InStoreStorageFacilities->Status->__toString() == 'A'?'style="pointer-events:none;"':''; ?>>
			<p class="text-bold">Security of stock</p>
			<p>Theft can be both internal and external. Internal theft is anyone who works for the company being dishonest and stealing stock. External theft is people who do not work for the company putting the business at risk through taking stock they have not paid for. Security is another word for protection. To minimise theft you need to protect your stores stock by carrying out various activities on a daily basis.</p>
			<p class="text-bold"><img src="module_eportfolio/assets/images/wb2_img1.png" /> &nbsp;Please complete the missing gaps below to identify the different activities involved in protecting your stock?</p>
			<div class="row">
				<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-center">
					<img class="img-responsive" src="module_eportfolio/assets/images/wb_r09_pg9_img1.png" />
				</div>
			</div>
			<div class="row">
				<div class="col-lg-2 col-md-2 col-sm-2 col-xs-2 text-center">
					<p><h4 class="text-bold pull-right">Q1</h4></p>
				</div>
				<div class="col-lg-10 col-md-10 col-sm-10 col-xs-10 text-center">
					<p><textarea name="InStoreStorageFacilitiesQ1" style="width: 100%;"><?php echo $answers->InStoreStorageFacilities->Q1->__toString(); ?></textarea></p>
				</div>
			</div>
			<div class="row">
				<div class="col-lg-2 col-md-2 col-sm-2 col-xs-2 text-center">
					<p><h4 class="text-bold pull-right">Q2</h4></p>
				</div>
				<div class="col-lg-10 col-md-10 col-sm-10 col-xs-10 text-center">
					<p><textarea name="InStoreStorageFacilitiesQ2" style="width: 100%;"><?php echo $answers->InStoreStorageFacilities->Q2->__toString(); ?></textarea></p>
				</div>
			</div>
			<div class="row">
				<div class="col-lg-2 col-md-2 col-sm-2 col-xs-2 text-center">
					<p><h4 class="text-bold pull-right">Q3</h4></p>
				</div>
				<div class="col-lg-10 col-md-10 col-sm-10 col-xs-10 text-center">
					<p><textarea name="InStoreStorageFacilitiesQ3" style="width: 100%;"><?php echo $answers->InStoreStorageFacilities->Q3->__toString(); ?></textarea></p>
				</div>
			</div>
		</div>
	</div>
	<div class="row">
		<div class="col-sm-12">
			<p class="text-bold">What is stock rotation?</p>
			<img class="img-responsive pull-right" src="module_eportfolio/assets/images/wb_r09_pg9_img2.png" />
			<p>Stock rotation is a method for ensuring that older stock is sold before newer stock to ensure sell through and avoid the likelihood of it being unfit for sale.</p>
			<p>The main risk to the products is that they will go out of date therefore a loss will be incurred. This also complies with health and hygiene regulations as stores can be prosecuted for selling out of date stock.</p>
			<p>Another reason for rotating stock is to avoid stock being handled more times than is necessary. Handling stock frequently can result in damage to the packaging or contents. Finally customers expect to see products that they recognise. It is important to have stock on sale that has current and up to date packaging.</p>
			<p class="text-bold">Shrinkage</p>
			<p>Shrinkage is another word for loss. Shrinkage is caused by a number of different factors, some of which we have covered already. For example, if you have damaged stock which you are unable to sell, this is a loss to the business and therefore shrinkage.</p>
		</div>
	</div>
	<?php if($wb->id != '' && $_SESSION['user']->type == User::TYPE_ASSESSOR && ($wb->wb_status == Workbook::STATUS_LEARNER_COMPLETED || $wb->wb_status == Workbook::STATUS_BEING_CHECKED || $wb->wb_status == Workbook::STATUS_IV_REJECTED)) {?>
	<div class="row well">
		<div class="col-sm-12">
			<div class="box box-success box-solid">
				<div class="box-header with-border"><h3 class="box-title">Assessor Feedback</h3></div>
				<div class="box-body assessorFeedback" <?php echo $feedback->InStoreStorageFacilities->Status->__toString() == 'A'?'style="pointer-events:none;"':''; ?>>
					<div class="form-group">
						<label for="status_InStoreStorageFacilities" class="col-sm-12 control-label">Status:</label>
						<div class="col-sm-12">
							<?php echo HTML::selectChosen('status_InStoreStorageFacilities', $answer_status, $feedback->InStoreStorageFacilities->Status->__toString() == 'A'?$feedback->InStoreStorageFacilities->Status->__toString():'', false, true); ?>
						</div>
					</div>
					<div class="form-group">
						<label for="comments_InStoreStorageFacilities" class="col-sm-12 control-label">Comments:</label>
						<div class="col-sm-12">
							<textarea name="comments_InStoreStorageFacilities" rows="7" style="width: 100%;"><?php echo $feedback->InStoreStorageFacilities->Comments->__toString(); ?></textarea>
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
		if($feedback->InStoreStorageFacilities->Status->__toString() == 'NA')
			echo HTML::renderWorkbookIcons('InStoreStorageFacilities', false, 'btn-danger');
		else
			echo HTML::renderWorkbookIcons('InStoreStorageFacilities', false, 'btn-success');
		?>
	</div></div></div>

	<?php echo $wb->getPageBottomLine(); ?>
</div> <!--.page 3 ends-->

<h1>Stock</h1>
<div class="step-content">
	<?php echo $wb->getPageTopLine(); ?>
	<div class="row"><div class="col-sm-12"><div class="pull-right">
		<?php
		if($feedback->Deliveries->Status->__toString() == 'NA')
			echo HTML::renderWorkbookIcons('Deliveries', false, 'btn-danger');
		else
			echo HTML::renderWorkbookIcons('Deliveries', false, 'btn-success');
		?>
	</div></div></div>
	<div class="row">
		<div class="col-sm-12" <?php echo $feedback->Deliveries->Status->__toString() == 'A'?'style="pointer-events:none;"':''; ?>>
			<p class="text-bold">Deliveries</p>
			<p>Your main stock delivery comes from <?php echo ucfirst($wb->savers_or_sp); ?>'s distribution centre however you may also get deliveries from other sources e.g. post or carriers. Depending on the size of your store you may get more than one delivery a week. One of the most important things to know about deliveries is that your store will be charged for all items that are detailed on delivery notes. This could be a potential risk to shrinkage. This means that you must ensure that stock is accounted for. There are company policies and procedures for the checking of various delivery types. E.g. high value stock.</p>
			<p class="text-bold"><img src="module_eportfolio/assets/images/wb2_img1.png" /> &nbsp;Think about when you have checked off a delivery note / invoice. Detail what you did.</p>
			<p><textarea name="DeliveriesQ1" style="width: 100%;" rows="5"><?php echo $answers->Deliveries->Q1->__toString(); ?></textarea> </p>
			<p class="text-bold"><img src="module_eportfolio/assets/images/wb2_img1.png" /> &nbsp;Apart from quantities, what else did you consider when checking off the delivery?</p>
			<p><textarea name="DeliveriesQ2" style="width: 100%;" rows="7"><?php echo $answers->Deliveries->Q2->__toString(); ?></textarea> </p>
			<p class="text-bold"><img src="module_eportfolio/assets/images/wb2_img1.png" /> &nbsp;Give an example of when you have dealt with any incorrect quantities, faulty or substandard stock that you received on a delivery. If this hasn’t happened, speak to your manager or mentor to be involved when things go wrong.</p>
			<p><textarea name="DeliveriesQ3" style="width: 100%;" rows="5"><?php echo $answers->Deliveries->Q3->__toString(); ?></textarea> </p>
			<p class="text-bold"><img src="module_eportfolio/assets/images/wb2_img1.png" /> &nbsp;What paperwork did you complete and who did you report any findings to?</p>
			<p><textarea name="DeliveriesQ4" style="width: 100%;" rows="7"><?php echo $answers->Deliveries->Q4->__toString(); ?></textarea> </p>
		</div>
	</div>
	<?php if($wb->id != '' && $_SESSION['user']->type == User::TYPE_ASSESSOR && ($wb->wb_status == Workbook::STATUS_LEARNER_COMPLETED || $wb->wb_status == Workbook::STATUS_BEING_CHECKED || $wb->wb_status == Workbook::STATUS_IV_REJECTED)) {?>
	<div class="row well">
		<div class="col-sm-12">
			<div class="box box-success box-solid">
				<div class="box-header with-border"><h3 class="box-title">Assessor Feedback</h3></div>
				<div class="box-body assessorFeedback" <?php echo $feedback->Deliveries->Status->__toString() == 'A'?'style="pointer-events:none;"':''; ?>>
					<div class="form-group">
						<label for="status_Deliveries" class="col-sm-12 control-label">Status:</label>
						<div class="col-sm-12">
							<?php echo HTML::selectChosen('status_Deliveries', $answer_status, $feedback->Deliveries->Status->__toString() == 'A'?$feedback->Deliveries->Status->__toString():'', false, true); ?>
						</div>
					</div>
					<div class="form-group">
						<label for="comments_Deliveries" class="col-sm-12 control-label">Comments:</label>
						<div class="col-sm-12">
							<textarea name="comments_Deliveries" rows="7" style="width: 100%;"><?php echo $feedback->Deliveries->Comments->__toString(); ?></textarea>
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
		if($feedback->Deliveries->Status->__toString() == 'NA')
			echo HTML::renderWorkbookIcons('Deliveries', false, 'btn-danger');
		else
			echo HTML::renderWorkbookIcons('Deliveries', false, 'btn-success');
		?>
	</div></div></div>

	<?php echo $wb->getPageBottomLine(); ?>
</div> <!--.page 9 ends-->

<h1>Stock</h1>
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
	<br>
	<div class="row">
		<div class="col-sm-12 text-center">
			<h2>Qualification  questions</h2>
		</div>
	</div>
	<div class="row" <?php echo $feedback->QualificationQuestions->Status->__toString() == 'A'?'style="pointer-events:none;"':''; ?>>
		<div class="col-sm-12">
			<div class="box box-solid box-success">
				<div class="box-body">
					<p class="text-bold">Now you have completed the section on Communication, answer the following questions:</p>
					<p><strong>Unit 5 - 1.1<br>Identify how to maintain appropriate levels of stock</strong></p>
					<p><textarea name="Unit1_1" style="width: 100%" rows="5"><?php echo $answers->QualificationQuestions->Unit1_1->__toString(); ?></textarea></p>
					<p><strong>Unit 5 - 1.2<br>Identify key factors that will affect stock</strong></p>
					<p><textarea name="Unit1_2" style="width: 100%" rows="5"><?php echo $answers->QualificationQuestions->Unit1_2->__toString(); ?></textarea></p>
					<p><strong>Unit 5 - 2.1<br>Explain two different methods of keeping stock in the right conditions.</strong></p>
					<p><textarea name="Unit2_1" style="width: 100%" rows="5"><?php echo $answers->QualificationQuestions->Unit2_1->__toString(); ?></textarea></p>
					<p><strong>Unit 5 - 2.2<br>Explain how stock is maintained in the correct conditions within your business to minimise stock loss</strong></p>
					<p><textarea name="Unit2_2" style="width: 100%" rows="5"><?php echo $answers->QualificationQuestions->Unit2_2->__toString(); ?></textarea></p>
					<p><strong>Unit 5 - 3.1<br>Explain about a time when you have taken the appropriate action to identify stock issues and taken action to address them.</strong></p>
					<p><textarea name="Unit3_1" style="width: 100%" rows="5"><?php echo $answers->QualificationQuestions->Unit3_1->__toString(); ?></textarea></p>
					<p><strong>Unit 5 - 3.2<br>Explain how you have demonstrated minimising stock loss through accurate administration, mimimising waste and theft.</strong></p>
					<p><textarea name="Unit3_2" style="width: 100%" rows="5"><?php echo $answers->QualificationQuestions->Unit3_2->__toString(); ?></textarea></p>
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
							<?php echo HTML::selectChosen('status_QualificationQuestions', $answer_status, $feedback->QualificationQuestions->Status->__toString() == 'A'?$feedback->QualificationQuestions->Status->__toString():'', false, true); ?>
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

		$("#frm_wb_stock :input").not(".assessorFeedback :input, #signature_text, #frm_wb_stock :input[type=hidden], .btnViewSectionHistory, .btnViewAssessorComments, #iv_status, #iv_comments, #reopen_comments").prop("disabled", true);

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
							var myForm = document.forms['frm_wb_stock'];
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
				var myForm = document.forms['frm_wb_stock'];
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

					var myForm = document.forms['frm_wb_stock'];
					myForm.elements['full_save'].value = 'Y';
					myForm.elements['full_save_feedback'].value = 'Y';
					window.onbeforeunload = null;
					myForm.submit();

				},
				'Save And Come Back Later':function () {

					var myForm = document.forms['frm_wb_stock'];
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
		$('#frm_wb_stock :input[name=full_save]').val('N');
		$($('#frm_wb_stock').serializeArray()).each(function(i, field)
		{
			document.forms["frm_wb_stock"].elements[field.name].value = field.value.replace(/£/g, "GBP");
		});
		$.ajax({
			type:'POST',
			url:'do.php?_action=save_wb_stock',
			data: $('#frm_wb_stock').serialize(),
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
