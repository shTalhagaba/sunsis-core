<?php /* @var $wb WBMarketing */ ?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/html" xmlns="http://www.w3.org/1999/html" xmlns="http://www.w3.org/1999/html"
      xmlns="http://www.w3.org/1999/html" xmlns="http://www.w3.org/1999/html">
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<title>Marketing workbook</title>
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

<form name="frm_wb_marketing" id="frm_wb_marketing" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
<input type="hidden" name="_action" value="save_wb_marketing"/>
<input type="hidden" name="id" value="<?php echo $wb->id; ?>"/>
<input type="hidden" name="tr_id" value="<?php echo $wb->tr_id; ?>"/>
<input type="hidden" name="wb_status" id="wb_status" value=""/>
<input type="hidden" name="full_save" id="full_save" value="<?php echo $wb->full_save; ?>"/>
<input type="hidden" name="full_save_feedback" id="full_save_feedback" value="<?php echo $wb->full_save_feedback; ?>"/>

<div class="container-float">
<div class="wrapper" style="background-color: #ffffff;">

<header class="main-header"><span style="margin-left: 50%;"><?php echo DAO::getSingleValue($link, "SELECT title FROM student_frameworks WHERE tr_id = '{$wb->tr_id}'"); ?></span></header>

<div class="content-wrapper" style="background-color: #ffffff;">

<?php echo $wb->savers_or_sp == 'savers' ? '<section class="content-header"><h1 style="color: #0000ff;">Marketing</h1></section>' : '<section class="content-header"><h1>Marketing</h1></section>' ?>

<section class="content">

<div id="wizard">

<h1>Marketing</h1>

<div class="step-content">
	<?php echo $wb->getPageTopLine(); ?>
	<div style="position: absolute; top: 40%; right: 50%;" class="lead">
		<?php echo $wb->savers_or_sp == 'savers' ? '<h2 class="text-bold" style="color: #0000ff;">Marketing</h2>' : '<h2 class="text-bold">Marketing</h2>' ?>
		<p class="text-center">Module</p>
	</div>

	<?php echo $wb->getPageBottomLine(); ?>
</div>
<!--.page 1 ends-->

<h1>Marketing</h1>

<div class="step-content">
	<?php echo $wb->getPageTopLine(); ?>
	<div class="row"><div class="col-sm-12"><div class="pull-right">
		<?php
		if($feedback->DifferentiationWithCompetitors->Status->__toString() == 'NA')
			echo HTML::renderWorkbookIcons('DifferentiationWithCompetitors', false, 'btn-danger');
		else
			echo HTML::renderWorkbookIcons('DifferentiationWithCompetitors', false, 'btn-success');
		?>
	</div></div></div>
	<div class="row">
		<div class="col-sm-4 col-sm-offset-4">
			<div class="text-center" style="padding: 15px;">
				<h3 class="text-pink">Marketing</h3>
				<p style="border: 1px solid #0000ff; padding: 15px;"><span class="text-bold">Marketing </span><i>is technique of promoting, selling, and distributing a product or service</i></p>
			</div>
		</div>
	</div>

	<div class="row">
		<div class="col-sm-4 col-sm-offset-4">
			<div class="bg-blue text-center" style="padding: 15px; border: #000000 solid 1px;">
				<p><span class="text-bold">What is business positioning and how does it relate to market share and competitors?</span></p>
			</div>
		</div>
	</div>

	<div class="row">
		<div class="col-sm-6">
			<p><span class="text-bold">Business positioning</span> is how you differentiate your <span class="text-bold">product</span> or service from that of your competitors and then determines which <span class="text-bold">market</span> niche to fill. Business positioning helps establish your <span class="text-bold">product's</span> or service's identity within the eyes of the customer.</p>
			<p><span class="text-bold">Market share</span> represents the percentage of an industry or market's total sales that is earned by a particular company over a specified time period.</p>
			<p><span class="text-bold">Market share</span> is calculated by taking the company's sales over the period and dividing it by the total sales of the industry over the same period. This calculation is used to give a general idea of the size of a company in relation to its market and its competitors. For example, if a company sells £100 million worth of tractors a year domestically, and the total tractors sold in the United Kingdom are £200 million, the company's UK market share for tractors would be 50%.</p>
			<p><span class="text-bold">Why is market share so important?</span> Investors look at market share increases and decreases, because they can be a sign of the competitiveness of the company's products or services.</p>
		</div>
		<div class="col-sm-6">
			<p>As the total market for a product or service grows, a company that is maintaining its market share is growing at the same rate as the total market. A company that is growing its market share will be growing faster than its competitors. This encourages the investors to back this company.</p>
			<p class="text-bold"><img src="module_eportfolio/assets/images/wb2_pg18_img1.png"/> &nbsp; Research <?php echo ucfirst($wb->savers_or_sp); ?> and identify how we differentiate our products and services from our competitors.</p>
			<p class="text-bold"><img src="module_eportfolio/assets/images/wb2_img1.png" /> &nbsp; Record your findings below:</p>
			<p <?php echo $feedback->DifferentiationWithCompetitors->Status->__toString() == 'A'?'style="pointer-events:none;"':''; ?>><textarea name="DifferentiationWithCompetitors" rows="5" style="width: 100%;"><?php echo $answers->DifferentiationWithCompetitors->__toString(); ?></textarea></p>
		</div>
	</div>

	<?php if($wb->id != '' && $_SESSION['user']->type == User::TYPE_ASSESSOR && ($wb->wb_status == Workbook::STATUS_LEARNER_COMPLETED || $wb->wb_status == Workbook::STATUS_BEING_CHECKED || $wb->wb_status == Workbook::STATUS_IV_REJECTED)) {?>
	<div class="row well">
		<div class="col-sm-12">
			<div class="box box-success box-solid">
				<div class="box-header with-border"><h3 class="box-title">Assessor Feedback</h3></div>
				<div class="box-body assessorFeedback" <?php echo $feedback->DifferentiationWithCompetitors->Status->__toString() == 'A'?'style="pointer-events:none;"':''; ?>>
					<div class="form-group">
						<label for="status_DifferentiationWithCompetitors" class="col-sm-12 control-label">Status:</label>
						<div class="col-sm-12">
							<?php
							echo HTML::selectChosen('status_DifferentiationWithCompetitors', $answer_status, $feedback->DifferentiationWithCompetitors->Status->__toString() == 'A'?$feedback->DifferentiationWithCompetitors->Status->__toString():'', false, true); ?>
						</div>
					</div>
					<div class="form-group">
						<label for="comments_DifferentiationWithCompetitors" class="col-sm-12 control-label">Comments:</label>
						<div class="col-sm-12">
							<textarea name="comments_DifferentiationWithCompetitors" rows="7" style="width: 100%;"><?php echo $feedback->DifferentiationWithCompetitors->Comments->__toString(); ?></textarea>
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
		if($feedback->DifferentiationWithCompetitors->Status->__toString() == 'NA')
			echo HTML::renderWorkbookIcons('DifferentiationWithCompetitors', false, 'btn-danger');
		else
			echo HTML::renderWorkbookIcons('DifferentiationWithCompetitors', false, 'btn-success');
		?>
	</div></div></div>
	<?php echo $wb->getPageBottomLine(); ?>
</div>
<!--.page 2 ends-->

<h1>Marketing</h1>

<div class="step-content">
	<?php echo $wb->getPageTopLine(); ?>
	<div class="row"><div class="col-sm-12"><div class="pull-right">
		<?php
		if($feedback->MarketingStrategy->Status->__toString() == 'NA')
			echo HTML::renderWorkbookIcons('MarketingStrategy', false, 'btn-danger');
		else
			echo HTML::renderWorkbookIcons('MarketingStrategy', false, 'btn-success');
		?>
	</div></div></div>
	<div class="row">
		<div class="col-sm-4 col-sm-offset-4">
			<div class="text-center" style="padding: 15px; border: #0000ff dashed 3px;">
				<p>A <span class="text-bold">marketing strategy</span> aims to make a <span class="text-bold">company brand</span> occupy a distinct <span class="text-bold">business position</span>, relative to competing brands (competitors) in the mind and eyes of the customer.</p>
			</div>
		</div>
	</div>
	<div class="row">
		<div class="col-sm-12">
			<p>Companies apply a marketing strategy either by emphasising the distinguishing features of their brand (what it is, what it does and how, etc.) or they may try to create a suitable image (inexpensive or premium, practical or luxurious, entry-level or high-end, etc.) through advertising.</p>
			<p>Once a brand is positioned, it is very difficult to reposition it without destroying its credibility.</p>
		</div>
		<div class="col-sm-12">
			<img class="img-responsive pull-right" src="module_eportfolio/assets/images/wb9_pg9_img1.png" />
			<p class="text-bold"><img src="module_eportfolio/assets/images/wb2_pg18_img1.png"/> &nbsp;
				Research 2 retailers. E.g. A supermarket and clothes shop. Think about what goods they sell, their brands and thier position in the market. How do they differentiate themselves from <?php echo ucfirst($wb->savers_or_sp); ?>?
			</p>
		</div>
		<div class="col-sm-12">
			<table class="table table-bordered" <?php echo $feedback->MarketingStrategy->Status->__toString() == 'A'?'style="pointer-events:none;"':''; ?>>
				<thead><tr><th>Business name</th><th>Type of business/brand</th><th>Business position in the market</th><th>How do they differentiate themselves from each other?</th></tr></thead>
				<tbody>
				<?php
				$ms = $answers->MarketingStrategy;
				for($i = 1; $i <= 2; $i++)
				{
					$set = 'Set'.$i;
					echo '<tr>';
					echo '<td><textarea name="MarketingStrategy'.$set.'BN" style="width: 100%;">'.$ms->$set->BN->__toString().'</textarea> </td>';
					echo '<td><textarea name="MarketingStrategy'.$set.'Type" style="width: 100%;">'.$ms->$set->Type->__toString().'</textarea> </td>';
					echo '<td><textarea name="MarketingStrategy'.$set.'BP" style="width: 100%;">'.$ms->$set->BP->__toString().'</textarea> </td>';
					echo '<td><textarea name="MarketingStrategy'.$set.'HowDifferent" style="width: 100%;">'.$ms->$set->HowDifferent->__toString().'</textarea> </td>';
					echo '</tr>';
				}
				?>
				</tbody>
			</table>
		</div>
	</div>

	<?php if($wb->id != '' && $_SESSION['user']->type == User::TYPE_ASSESSOR && ($wb->wb_status == Workbook::STATUS_LEARNER_COMPLETED || $wb->wb_status == Workbook::STATUS_BEING_CHECKED || $wb->wb_status == Workbook::STATUS_IV_REJECTED)) {?>
	<div class="row well">
		<div class="col-sm-12">
			<div class="box box-success box-solid">
				<div class="box-header with-border"><h3 class="box-title">Assessor Feedback</h3></div>
				<div class="box-body assessorFeedback" <?php echo $feedback->MarketingStrategy->Status->__toString() == 'A'?'style="pointer-events:none;"':''; ?>>
					<div class="form-group">
						<label for="status_MarketingStrategy" class="col-sm-12 control-label">Status:</label>
						<div class="col-sm-12">
							<?php
							echo HTML::selectChosen('status_MarketingStrategy', $answer_status, $feedback->MarketingStrategy->Status->__toString() == 'A'?$feedback->MarketingStrategy->Status->__toString():'', false, true); ?>
						</div>
					</div>
					<div class="form-group">
						<label for="comments_MarketingStrategy" class="col-sm-12 control-label">Comments:</label>
						<div class="col-sm-12">
							<textarea name="comments_MarketingStrategy" rows="7" style="width: 100%;"><?php echo $feedback->MarketingStrategy->Comments->__toString(); ?></textarea>
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
		if($feedback->MarketingStrategy->Status->__toString() == 'NA')
			echo HTML::renderWorkbookIcons('MarketingStrategy', false, 'btn-danger');
		else
			echo HTML::renderWorkbookIcons('MarketingStrategy', false, 'btn-success');
		?>
	</div></div></div>
	<?php echo $wb->getPageBottomLine(); ?>
</div>
<!--.page 3 ends-->

<h1>Marketing</h1>

<div class="step-content">
	<?php echo $wb->getPageTopLine(); ?>
	<div class="row"><div class="col-sm-12"><div class="pull-right">
		<?php
		if($feedback->MarketingStrategy->Status->__toString() == 'NA')
			echo HTML::renderWorkbookIcons('USP', false, 'btn-danger');
		else
			echo HTML::renderWorkbookIcons('USP', false, 'btn-success');
		?>
	</div></div></div>
	<div class="row">
		<div class="col-sm-4 col-sm-offset-4">
			<div class="text-center" style="padding: 15px; border: #0000ff dashed 3px;">
				<p><span class="text-bold">USP</span> or <span class="text-bold">U</span>nique <span class="text-bold">S</span>elling <span class="text-bold">P</span>roposition is a real or perceived benefit of a good or service that differentiates it from the competing brands and gives its customer a logical reason to prefer it over other brands.</p>
			</div>
		</div>
	</div>
	<?php $usp = $answers->USP; ?>
	<div class="row">
		<div class="col-sm-6">
			<p><span class="text-bold">USP</span> is a factor that differentiates its product/service from its competitors, for example the lowest cost, the highest quality or the first-ever product of its kind.</p>
			<p>A <span class="text-bold">USP</span> could be thought of as "what you have that competitors don't."</p>
			<p class="text-bold">Why do you need a Unique Selling Proposition?</p>
			<p>Many prospective customers have difficulty deciding which option in the industry is the one that deserves their time, money and trust.</p>
			<p>This selection can be a daunting process for customers that don't have the experience to know what separates one competitor from another. By making your unique selling proposition obvious, different and memorable enough that they can see exactly what your business has to offer that the other guys do not you will assist the customer in making a decision</p>
			<p class="text-bold"><img src="module_eportfolio/assets/images/wb2_img1.png" /> &nbsp; What is <?php echo ucfirst($wb->savers_or_sp); ?>'s unique selling proposition?</p>
			<p  <?php echo $feedback->USP->Status->__toString() == 'A'?'style="pointer-events:none;"':''; ?>><textarea name="OurUSP" style="width: 100%;"><?php echo $usp->OurUSP->__toString(); ?></textarea></p>
		</div>
		<div class="col-sm-6">
			<p>Promotions and advertising campaigns are a series of advertisements using various marketing tools that share the same message and ideas to promote a business or event to a target audience.</p>
			<p>A typical campaign uses different media resources including internet, newspapers, television, radio, and print advertising.</p>
			<p>Promotional campaigns are better known as sales promotions. Companies use them to provide customers with discounts or incentives to purchase products or services.</p>
			<p>Information on sales promotions may be embedded in advertising messages.  Sales promotions are generally run for shorter time periods than advertising. The reason is that sales promotions are usually more product or service focused.</p>
			<p>Promotional campaigns may include new products, price reductions and buy-one-get-one-free promotions.</p>
			<img class="img-responsive pull-right" src="module_eportfolio/assets/images/wb4_pg4_img1.png" />
		</div>
		<div class="col-sm-12">
			<p class="text-bold"><img src="module_eportfolio/assets/images/wb2_pg18_img1.png"/> &nbsp; Research 3 similar businesses USP (Unique Selling Propositions). Think about how they compare to our own businesses USP.</p>
			<p class="text-bold"><img src="module_eportfolio/assets/images/wb2_img1.png" /> &nbsp; Record your findings in the table below:</p>
			<table class="table table-bordered" <?php echo $feedback->USP->Status->__toString() == 'A'?'style="pointer-events:none;"':''; ?>>
				<thead><tr><th>Business name</th><th>USP</th><th>How they compare to our own USP?</th></tr></thead>
				<tbody>
				<?php
				for($i = 1; $i <= 2; $i++)
				{
					$set = 'Set'.$i;
					echo '<tr>';
					echo '<td><textarea name="USP'.$set.'BN" style="width: 100%;">'.$usp->SimilarBusinessesUSP->$set->BN->__toString().'</textarea> </td>';
					echo '<td><textarea name="USP'.$set.'USP" style="width: 100%;">'.$usp->SimilarBusinessesUSP->$set->USP->__toString().'</textarea> </td>';
					echo '<td><textarea name="USP'.$set.'Comparison" style="width: 100%;">'.$usp->SimilarBusinessesUSP->$set->Comparison->__toString().'</textarea> </td>';
					echo '</tr>';
				}
				?>
				</tbody>
			</table>
		</div>
		<div class="col-sm-12">
			<img class="img-responsive" src="module_eportfolio/assets/images/wb_r08_pg5_img1.png" />
		</div>
	</div>

	<?php if($wb->id != '' && $_SESSION['user']->type == User::TYPE_ASSESSOR && ($wb->wb_status == Workbook::STATUS_LEARNER_COMPLETED || $wb->wb_status == Workbook::STATUS_BEING_CHECKED || $wb->wb_status == Workbook::STATUS_IV_REJECTED)) {?>
	<div class="row well">
		<div class="col-sm-12">
			<div class="box box-success box-solid">
				<div class="box-header with-border"><h3 class="box-title">Assessor Feedback</h3></div>
				<div class="box-body assessorFeedback" <?php echo $feedback->USP->Status->__toString() == 'A'?'style="pointer-events:none;"':''; ?>>
					<div class="form-group">
						<label for="status_USP" class="col-sm-12 control-label">Status:</label>
						<div class="col-sm-12">
							<?php
							echo HTML::selectChosen('status_USP', $answer_status, $feedback->USP->Status->__toString() == 'A'?$feedback->USP->Status->__toString():'', false, true); ?>
						</div>
					</div>
					<div class="form-group">
						<label for="comments_USP" class="col-sm-12 control-label">Comments:</label>
						<div class="col-sm-12">
							<textarea name="comments_USP" rows="7" style="width: 100%;"><?php echo $feedback->USP->Comments->__toString(); ?></textarea>
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
		if($feedback->MarketingStrategy->Status->__toString() == 'NA')
			echo HTML::renderWorkbookIcons('USP', false, 'btn-danger');
		else
			echo HTML::renderWorkbookIcons('USP', false, 'btn-success');
		?>
	</div></div></div>
	<?php echo $wb->getPageBottomLine(); ?>
</div>
<!--.page 4 ends-->

<h1>Marketing</h1>

<div class="step-content">
	<?php echo $wb->getPageTopLine(); ?>
	<div class="row"><div class="col-sm-12"><div class="pull-right">
		<?php
		if($feedback->MarketingStrategy->Status->__toString() == 'NA')
			echo HTML::renderWorkbookIcons('Advertising', false, 'btn-danger');
		else
			echo HTML::renderWorkbookIcons('Advertising', false, 'btn-success');
		?>
	</div></div></div>
	<div class="row">
		<div class="col-sm-4 col-sm-offset-4">
			<div class="text-center" style="padding: 15px; border: #0000ff dashed 3px;">
				<p><span class="text-bold">Advertising</span> refers to the marketing communication that businesses use to persuade, encourage or manipulate audiences to get them to take some sort of action</p>
			</div>
		</div>
	</div>
	<?php $ad = $answers->Advertising; ?>
	<div class="row">
		<div class="col-sm-6">
			<p>The most common desired outcome of an advertising campaign is that the customer makes a purchase or follows some other rule of consumer behaviour. Advertising to ideological and political purposes is also popular.</p>
			<p>Advertising is more media-focused and widespread than a promotional campaign.  Companies also advertise through social media sites such as Facebook and Twitter. Advertisers use branding or associating an image or name with specific qualities to get in to the consciousness of the consumer or will attempt to get them to feel a certain way or believe a certain line.</p>
			<p>Advertising campaigns may include such things as coupons or Loyalty Programs.</p>
			<p>Advertising is generally more focused on the reasons people should make a purchase.</p>
		</div>
		<div class="col-sm-6">
			<p>Advertising is also limited to one-way communication, while the customer generally interacts with sales assistant or other retail employees during promotional campaigns.</p>
			<div class="bg-light-blue text-center" style="padding: 10px;">
				<p>In the UK advertisers must adhere to regulations and advertising codes of practice to ensure they advertise legally.</p>
				<p>All marketing and advertising must be legal, decent, truthful, honest and socially responsible.</p>
				<p>Advertisers must give an accurate description of their product or service and not include false or deceptive messages, use aggressive sales techniques or leave out important information.</p>
			</div>
		</div>
		<div class="col-sm-12">
			<p class="text-bold"><img src="module_eportfolio/assets/images/wb2_img1.png" /> &nbsp; Find out about your own store promotional campaigns. Think about how often they are run, what they involve and how successful they are. Record your findings below:</p>
			<p <?php echo $feedback->Advertising->Status->__toString() == 'A'?'style="pointer-events:none;"':''; ?>><textarea name="OurCompaign" style="width: 100%;" rows="5"><?php echo $ad->OurCompaign->__toString(); ?></textarea></p>
		</div>
	</div>

	<div class="row">
		<div class="col-sm-4 col-sm-offset-4">
			<div class="bg-blue text-center" style="padding: 15px; border: #000000 solid 1px;">
				<p><span class="text-bold">How do methods used for promotion impact on the customers buying decision?</span></p>
			</div>
		</div>
	</div>

	<div class="row">
		<div class="col-sm-12">
			<ul style="margin-left: 15px;">
				<li><p><span class="text-bold">Loyalty programs</span> encourage customers to buy, by offering a point's scheme that enables them to save points and get money off their shop. Marketing the customers on the loyalty scheme by e mail encourages customers to return with offers of exclusive sales, discounts, coupons and additional points for a limited time period.</p></li>
				<li><p><span class="text-bold">Promotional pricing</span> can include price reductions on selected products that are in demand, special offers on a wide range of products in the store and price reductions on frequently used items. Backed by a good advertising campaign and good clear point of sale material customers are drawn to offers and will be enticed to buy goods with the perception of saving money. </p></li>
				<li><p><span class="text-bold">Point of purchase</span> displays are displays that are either at the till point or can be situated at intervals during the customer journey through your store. These are great offers that are displayed in bins or on shelves and are bold displays with clear ticketing. Customers do not fail to miss these displays as they make the journey to the pay point. These can also be called add-ons or sell-ups.</p></li>
				<li><p><span class="text-bold">New Lines/ranges</span> are displays set up to highlight that the store has a new range/lines that another retailer may not stock yet. It encourages the customer to be the first to buy/try and usually has an introductory offer price which is also seen as a saving to the customer. </p></li>
				<li><p><span class="text-bold">Own label</span> products are often included in promotional activities as there is a larger profit margin on these lines. Often a USP is advertised to encourage customers to buy. For example with <?php echo ucfirst($wb->savers_or_sp); ?> it is the 100% Happiness Guarantee.</p></li>
			</ul>
		</div>
		<div class="col-sm-12">
			<p class="text-bold"><img src="module_eportfolio/assets/images/wb2_img1.png" /> &nbsp; Consider how different promotional activities impact on our customers buying decisions. Complete the table below:</p>
			<table class="table table-bordered" <?php echo $feedback->Advertising->Status->__toString() == 'A'?'style="pointer-events:none;"':''; ?>>
				<thead><tr><th>Activity</th><th>How they impact on the customers buying decision</th></tr></thead>
				<tbody>
				<tr>
					<td>3 for 2</td>
					<td>Encourage customers to buy more than they intended and realise how much money they are saving</td>
				</tr>
				<?php
				for($i = 1; $i <= 2; $i++)
				{
					$set = 'Set'.$i;
					echo '<tr>';
					echo '<td><textarea name="Advertising'.$set.'Activity" style="width: 100%;">'.$ad->SimilarBusinessCompaign->$set->Activity->__toString().'</textarea> </td>';
					echo '<td><textarea name="Advertising'.$set.'Impact" style="width: 100%;">'.$ad->SimilarBusinessCompaign->$set->Impact->__toString().'</textarea> </td>';
					echo '</tr>';
				}
				?>
				</tbody>
			</table>
		</div>
	</div>

	<?php if($wb->id != '' && $_SESSION['user']->type == User::TYPE_ASSESSOR && ($wb->wb_status == Workbook::STATUS_LEARNER_COMPLETED || $wb->wb_status == Workbook::STATUS_BEING_CHECKED || $wb->wb_status == Workbook::STATUS_IV_REJECTED)) {?>
	<div class="row well">
		<div class="col-sm-12">
			<div class="box box-success box-solid">
				<div class="box-header with-border"><h3 class="box-title">Assessor Feedback</h3></div>
				<div class="box-body assessorFeedback" <?php echo $feedback->Advertising->Status->__toString() == 'A'?'style="pointer-events:none;"':''; ?>>
					<div class="form-group">
						<label for="status_Advertising" class="col-sm-12 control-label">Status:</label>
						<div class="col-sm-12">
							<?php
							echo HTML::selectChosen('status_Advertising', $answer_status, $feedback->Advertising->Status->__toString() == 'A'?$feedback->Advertising->Status->__toString():'', false, true); ?>
						</div>
					</div>
					<div class="form-group">
						<label for="comments_Advertising" class="col-sm-12 control-label">Comments:</label>
						<div class="col-sm-12">
							<textarea name="comments_Advertising" rows="7" style="width: 100%;"><?php echo $feedback->Advertising->Comments->__toString(); ?></textarea>
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
		if($feedback->MarketingStrategy->Status->__toString() == 'NA')
			echo HTML::renderWorkbookIcons('Advertising', false, 'btn-danger');
		else
			echo HTML::renderWorkbookIcons('Advertising', false, 'btn-success');
		?>
	</div></div></div>
	<?php echo $wb->getPageBottomLine(); ?>
</div>
<!--.page 5 ends-->

<h1>Marketing</h1>

<div class="step-content">
	<?php echo $wb->getPageTopLine(); ?>
	<div class="row"><div class="col-sm-12"><div class="pull-right">
		<?php
		if($feedback->MarketingStrategy->Status->__toString() == 'NA')
			echo HTML::renderWorkbookIcons('Competitors', false, 'btn-danger');
		else
			echo HTML::renderWorkbookIcons('Competitors', false, 'btn-success');
		?>
	</div></div></div>
	<div class="row">
		<div class="col-sm-4 col-sm-offset-4">
			<div class="text-center" style="padding: 15px; border: #0000ff dashed 3px;">
				<p><span class="text-bold">A competitor</span> is any person or entity which is a rival against another.</p>
			</div>
		</div>
	</div>
	<div class="row">
		<div class="col-sm-12">
			<p>In business, a competitor is a company in the same industry or a similar industry which offers a similar product or service.  The presence of one or more competitors can reduce the prices of goods and services as the companies attempt to gain a larger market share. Competition also requires companies to become more efficient in order to reduce costs. Fast-food restaurants McDonald's and Burger King are competitors, as are Coca-Cola and Pepsi.</p>
		</div>
	</div>

	<div class="row">
		<div class="col-sm-4 col-sm-offset-4">
			<div class="bg-blue text-center" style="padding: 15px; border: #000000 solid 1px;">
				<p><span class="text-bold">Who are our competitors?</span></p>
			</div>
		</div>
	</div>

	<div class="row">
		<div class="col-sm-6">
			<img class="img-responsive" src="module_eportfolio/assets/images/<?php echo $wb->savers_or_sp == 'savers' ? 'wb_r08_pg8_img11.png' : 'wb_r08_pg8_img1.png'; ?>" />
		</div>
		<div class="col-sm-6">
			<table class="table table-bordered" <?php echo $feedback->Competitors->Status->__toString() == 'A'?'style="pointer-events:none;"':''; ?>>
				<tbody>
				<tr>
					<th align="center"><h4 class="text-bold text-center">A</h4> </th>
					<td><textarea name="CompetitorsA" style="width: 100%;"><?php echo $answers->Competitors->A->__toString(); ?></textarea> </td>
				</tr>
				<tr>
					<th align="center"><h4 class="text-bold text-center">B</h4> </th>
					<td><textarea name="CompetitorsB" style="width: 100%;"><?php echo $answers->Competitors->B->__toString(); ?></textarea> </td>
				</tr>
				<tr>
					<th align="center"><h4 class="text-bold text-center">C</h4> </th>
					<td><textarea name="CompetitorsC" style="width: 100%;"><?php echo $answers->Competitors->C->__toString(); ?></textarea> </td>
				</tr>
				</tbody>
			</table>
		</div>
		<div class="col-sm-12">
			<p>Knowing who their competitors are, and what they are offering, can help businesses make their products, services and marketing stand out. It will enable them to set prices competitively and help to respond to rival marketing campaigns with their own initiatives.</p>
			<p>This knowledge can be used to create marketing strategies that take advantage of competitors' weaknesses, and improve own business performance. Threats posed by both new entrants to the market and current competitors can also be assessed. This knowledge will help businesses be realistic about how successful they can be.</p>
		</div>
	</div>

	<?php if($wb->id != '' && $_SESSION['user']->type == User::TYPE_ASSESSOR && ($wb->wb_status == Workbook::STATUS_LEARNER_COMPLETED || $wb->wb_status == Workbook::STATUS_BEING_CHECKED || $wb->wb_status == Workbook::STATUS_IV_REJECTED)) {?>
	<div class="row well">
		<div class="col-sm-12">
			<div class="box box-success box-solid">
				<div class="box-header with-border"><h3 class="box-title">Assessor Feedback</h3></div>
				<div class="box-body assessorFeedback" <?php echo $feedback->Competitors->Status->__toString() == 'A'?'style="pointer-events:none;"':''; ?>>
					<div class="form-group">
						<label for="status_Competitors" class="col-sm-12 control-label">Status:</label>
						<div class="col-sm-12">
							<?php
							echo HTML::selectChosen('status_Competitors', $answer_status, $feedback->Competitors->Status->__toString() == 'A'?$feedback->Competitors->Status->__toString():'', false, true); ?>
						</div>
					</div>
					<div class="form-group">
						<label for="comments_Competitors" class="col-sm-12 control-label">Comments:</label>
						<div class="col-sm-12">
							<textarea name="comments_Competitors" rows="7" style="width: 100%;"><?php echo $feedback->Competitors->Comments->__toString(); ?></textarea>
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
		if($feedback->MarketingStrategy->Status->__toString() == 'NA')
			echo HTML::renderWorkbookIcons('Competitors', false, 'btn-danger');
		else
			echo HTML::renderWorkbookIcons('Competitors', false, 'btn-success');
		?>
	</div></div></div>
	<?php echo $wb->getPageBottomLine(); ?>
</div>
<!--.page 6 ends-->

<h1>Marketing</h1>

<div class="step-content">
	<?php echo $wb->getPageTopLine(); ?>
	<div class="row"><div class="col-sm-12"><div class="pull-right"><?php echo HTML::renderWorkbookIcons('', true); ?></div></div></div>
	<div class="row">
		<div class="col-sm-6">
			<p>All businesses face competition. Even if there is only one restaurant in town it must compete with cinemas, bars and other businesses where customers will spend their money instead.</p>
			<p>With increased use of the Internet to buy goods and services and to find places to go, businesses are no longer just competing with their immediate neighbours. They could find themselves competing with businesses from other countries as well.</p>
			<p>Competitors could be a new business offering a substitute or similar product that makes another's redundant. Competition is not just another business that might take money away. It can be another product or service that's being developed and which a business ought to be selling or looking to license before somebody else takes it up.</p>
			<p>Businesses need to be constantly on the lookout for possible new competition.</p>
			<p>They can get clues to the existence of competitors from:</p>
			<ul style="margin-left: 15px;">
				<li>Local business directories</li>
				<li>Local Chamber of Commerce</li>
				<li>Advertising</li>
				<li>Press reports</li>
				<li>Exhibitions and trade fairs</li>
				<li>questionnaires</li>
				<li>Searching on the Internet for similar products or services</li>
				<li>Information provided by customers</li>
				<li>Flyers and marketing literature that have been sent to you</li>
			</ul>
			<img class="img-responsive" src="module_eportfolio/assets/images/wb7_pg5_img1.png" />
		</div>
		<div class="col-sm-6">
			<p>Businesses need to know a number of things about their competitors.</p>
			<ul style="margin-left: 15px;">
				<li>How their competitors do business</li>
				<li>What products or services they provide and how they market them to customers</li>
				<li>The prices they charge</li>
				<li>How they distribute and deliver</li>
				<li>The devices they employ to enhance customer loyalty and what back-up service they offer</li>
				<li>Their brand and design values</li>
				<li>Whether they innovate - business methods as well as products</li>
				<li>Their staff numbers and the caliber of staff that they attract</li>
				<li>How they use IT - for example, if they're technology-aware and offer a website and email</li>
				<li>Their media activities - check their website as well as local newspapers, radio, television and any outdoor advertising</li>
				<li>How they treat their customers</li>
			</ul>
			<ul style="margin-left: 15px;">
			<p>Businesses should also find out as much as possible about their competitors' customers, such as:</p>
				<li>Who they are</li>
				<li>What products or services different customers buy from them</li>
				<li>What customers see as their strengths and weaknesses</li>
				<li>Whether there are any long-standing customers</li>
				<li>If they've had an influx of customers recently</li>
			</ul>
			<p>Businesses should also listen to their own customers and suppliers. As well as asking how well they're performing, they should ask which of their competitors they buy from and how they compare.</p>

		</div>
	</div>

	<p><br></p>
	<div class="row"><div class="col-sm-12"><div class="pull-right"><?php echo HTML::renderWorkbookIcons('', true); ?></div></div></div>
	<?php echo $wb->getPageBottomLine(); ?>
</div>
<!--.page 7 ends-->

<h1>Marketing</h1>

<div class="step-content">
	<?php echo $wb->getPageTopLine(); ?>
	<div class="row"><div class="col-sm-12"><div class="pull-right">
		<?php
		if($feedback->SWOT->Status->__toString() == 'NA')
			echo HTML::renderWorkbookIcons('SWOT', false, 'btn-danger');
		else
			echo HTML::renderWorkbookIcons('SWOT', false, 'btn-success');
		?>
	</div></div></div>
	<div class="row">
		<div class="col-sm-12">
			<p>A SWOT Analysis is a useful technique for understanding a business's Strengths and Weaknesses, as well as identifying both Opportunities and any Threats they may face. If a business uses a SWOT analysis to look at themselves as well as their competitors it can help them distinguish themselves from their competitors and compete successfully in their market.</p>
			<p class="text-bold"><img src="module_eportfolio/assets/images/wb2_img1.png" /> &nbsp; Have a go at completing a SWOT analysis for your store and one of your competitors. You may want to discuss and complete this with your assessor.</p>
			<p><u><?php echo strtoupper($wb->savers_or_sp); ?></u></p>
		</div>
	</div>
	<?php $swot = $answers->SWOT; ?>
	<div class="row" <?php echo $feedback->SWOT->Status->__toString() == 'A'?'style="pointer-events:none;"':''; ?>>
		<div class="col-sm-6 text-center">
			<p><strong>Strength</strong></p>
			<textarea name="our_strength" id="our_strength" rows="10" cols="50" style="width: 100%"><?php echo $swot->OurSWOT->Strength->__toString(); ?></textarea>
		</div>
		<div class="col-sm-6 text-center">
			<p><strong>Weaknesses</strong></p>
			<textarea name="our_weakness" id="our_weakness"  rows="10" cols="50"  style="width: 100%"><?php echo $swot->OurSWOT->Weaknesses->__toString(); ?></textarea>
		</div>
	</div>
	<div class="row">
		<div class="col-sm-12 text-center">
			<p><h2>SWOT ANALYSIS - <?php echo strtoupper($wb->savers_or_sp); ?></h2> </p>
		</div>
	</div>
	<div class="row" <?php echo $feedback->SWOT->Status->__toString() == 'A'?'style="pointer-events:none;"':''; ?> >
		<div class="col-sm-6 text-center">
			<p><strong>Opportunities</strong></p>
			<textarea name="our_opportunity" id="our_opportunity" rows="10" cols="50"  style="width: 100%"><?php echo $swot->OurSWOT->Opportunities->__toString(); ?></textarea>
		</div>
		<div class="col-sm-6 text-center">
			<p><strong>Threats</strong></p>
			<textarea name="our_threat" id="our_threat" rows="10" cols="50" style="width: 100%"><?php echo $swot->OurSWOT->Threats->__toString(); ?></textarea>
		</div>
	</div>

	<div class="row">
		<div class="col-sm-12"><hr><p><u>COMPETITOR</u></p></div>
	</div>

	<div class="row" <?php echo $feedback->SWOT->Status->__toString() == 'A'?'style="pointer-events:none;"':''; ?>>
		<div class="col-sm-6 text-center">
			<p><strong>Strength</strong></p>
			<textarea name="comp_strength" id="swot_strength" rows="10" cols="50" style="width: 100%"><?php echo $swot->CompetitorSWOT->Strength->__toString(); ?></textarea>
		</div>
		<div class="col-sm-6 text-center">
			<p><strong>Weaknesses</strong></p>
			<textarea name="comp_weakness" id="swot_weakness"  rows="10" cols="50"  style="width: 100%"><?php echo $swot->CompetitorSWOT->Weaknesses->__toString(); ?></textarea>
		</div>
	</div>
	<div class="row">
		<div class="col-sm-12 text-center">
			<p><h2>SWOT ANALYSIS - COMPETITOR</h2> </p>
		</div>
	</div>
	<div class="row"  <?php echo $feedback->SWOT->Status->__toString() == 'A'?'style="pointer-events:none;"':''; ?>>
		<div class="col-sm-6 text-center">
			<p><strong>Opportunities</strong></p>
			<textarea name="comp_opportunity" id="swot_opportunity" rows="10" cols="50"  style="width: 100%"><?php echo $swot->CompetitorSWOT->Opportunities->__toString(); ?></textarea>
		</div>
		<div class="col-sm-6 text-center">
			<p><strong>Threats</strong></p>
			<textarea name="comp_threat" id="swot_threat" rows="10" cols="50" style="width: 100%"><?php echo $swot->CompetitorSWOT->Threats->__toString(); ?></textarea>
		</div>
	</div>

	<div class="row">
		<div class="col-sm-12">
			<p class="text-center"><img class="img-responsive" src="module_eportfolio/assets/images/<?php echo $wb->savers_or_sp == 'savers' ? 'wb_r08_pg11_img11.png' : 'wb_r08_pg11_img1.png'; ?>" /> </p>
		</div>
	</div>

	<?php if($wb->id != '' && $_SESSION['user']->type == User::TYPE_ASSESSOR && ($wb->wb_status == Workbook::STATUS_LEARNER_COMPLETED || $wb->wb_status == Workbook::STATUS_BEING_CHECKED || $wb->wb_status == Workbook::STATUS_IV_REJECTED)) {?>
	<div class="row well">
		<div class="col-sm-12">
			<div class="box box-success box-solid">
				<div class="box-header with-border"><h3 class="box-title">Assessor Feedback</h3></div>
				<div class="box-body assessorFeedback" <?php echo $feedback->SWOT->Status->__toString() == 'A'?'style="pointer-events:none;"':''; ?>>
					<div class="form-group">
						<label for="status_SWOT" class="col-sm-12 control-label">Status:</label>
						<div class="col-sm-12">
							<?php
							echo HTML::selectChosen('status_SWOT', $answer_status, $feedback->SWOT->Status->__toString() == 'A'?$feedback->SWOT->Status->__toString():'', false, true); ?>
						</div>
					</div>
					<div class="form-group">
						<label for="comments_SWOT" class="col-sm-12 control-label">Comments:</label>
						<div class="col-sm-12">
							<textarea name="comments_SWOT" rows="7" style="width: 100%;"><?php echo $feedback->SWOT->Comments->__toString(); ?></textarea>
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
		if($feedback->SWOT->Status->__toString() == 'NA')
			echo HTML::renderWorkbookIcons('SWOT', false, 'btn-danger');
		else
			echo HTML::renderWorkbookIcons('SWOT', false, 'btn-success');
		?>
	</div></div></div>
	<?php echo $wb->getPageBottomLine(); ?>
</div>
<!--.page 8 ends-->

<h1>Marketing</h1>

<div class="step-content">
	<?php echo $wb->getPageTopLine(); ?>
	<div class="row"><div class="col-sm-12"><div class="pull-right">
		<?php
		if($feedback->BusinessStrapline->Status->__toString() == 'NA')
			echo HTML::renderWorkbookIcons('BusinessStrapline', false, 'btn-danger');
		else
			echo HTML::renderWorkbookIcons('BusinessStrapline', false, 'btn-success');
		?>
	</div></div></div>
	<div class="row">
		<div class="col-sm-4 col-sm-offset-4">
			<div class="text-center" style="padding: 15px; border: #0000ff dashed 3px;">
				<p><span class="text-bold">A Business Strapline</span> is a way a business can represent itself in a short sentence that will help persuade customers to choose them</p>
			</div>
		</div>
	</div>

	<div class="row">
		<div class="col-sm-12">
			<p>A great marketing strap line should summarise what the brand stands for in just a few words.</p>
			<p>Some are descriptive such as eBay's “The world's online market place” but they don't have to be.</p>
			<p>It should however support brand positioning and communicate what the business is about.</p>
			<p>The benefits of developing and using a brand strapline include:</p>
			<img class="img-responsive pull-right" src="module_eportfolio/assets/images/wb_r08_pg12_img1.png" />
			<ul style="margin-left: 15px;">
				<li>Instant brand positioning in just a few words</li>
				<li>Memory hook for potential customers</li>
				<li>Helps to develop affinity with the brand</li>
				<li>Differentiates from competitors</li>
				<li>A great customer marketing framing tool</li>
			</ul>
		</div>
		<div class="col-sm-12">
			<p class="text-bold"><img src="module_eportfolio/assets/images/wb2_img1.png" /> &nbsp; What is <?php echo ucfirst($wb->savers_or_sp); ?>'s strapline? Record this and three other company's straplines below:</p>
			<table class="table table-bordered" <?php echo $feedback->BusinessStrapline->Status->__toString() == 'A'?'style="pointer-events:none;"':''; ?>>
				<thead><tr><th>Company Name</th><th>Company strapline</th></tr></thead>
				<tbody>
				<tr>
					<td><input type="hidden" name="BusinessStraplineSet1CN" value="<?php echo ucfirst($wb->savers_or_sp); ?>" /><?php echo ucfirst($wb->savers_or_sp); ?></td>
					<td><textarea name="BusinessStraplineSet1SL" style="width: 100%;"><?php echo $answers->BusinessStrapline->Set1->SL->__toString(); ?></textarea> </td>
				</tr>
				<?php
				for($i = 2; $i <= 4; $i++)
				{
					$set = 'Set'.$i;
					echo '<tr>';
					echo '<td><textarea name="BusinessStrapline'.$set.'CN" style="width: 100%;">'.$answers->BusinessStrapline->$set->CN->__toString().'</textarea> </td>';
					echo '<td><textarea name="BusinessStrapline'.$set.'SL" style="width: 100%;">'.$answers->BusinessStrapline->$set->SL->__toString().'</textarea> </td>';
					echo '</tr>';
				}
				?>
				</tbody>
			</table>
		</div>
	</div>

	<?php if($wb->id != '' && $_SESSION['user']->type == User::TYPE_ASSESSOR && ($wb->wb_status == Workbook::STATUS_LEARNER_COMPLETED || $wb->wb_status == Workbook::STATUS_BEING_CHECKED || $wb->wb_status == Workbook::STATUS_IV_REJECTED)) {?>
	<div class="row well">
		<div class="col-sm-12">
			<div class="box box-success box-solid">
				<div class="box-header with-border"><h3 class="box-title">Assessor Feedback</h3></div>
				<div class="box-body assessorFeedback" <?php echo $feedback->BusinessStrapline->Status->__toString() == 'A'?'style="pointer-events:none;"':''; ?>>
					<div class="form-group">
						<label for="status_BusinessStrapline" class="col-sm-12 control-label">Status:</label>
						<div class="col-sm-12">
							<?php
							echo HTML::selectChosen('status_BusinessStrapline', $answer_status, $feedback->BusinessStrapline->Status->__toString() == 'A'?$feedback->BusinessStrapline->Status->__toString():'', false, true); ?>
						</div>
					</div>
					<div class="form-group">
						<label for="comments_BusinessStrapline" class="col-sm-12 control-label">Comments:</label>
						<div class="col-sm-12">
							<textarea name="comments_BusinessStrapline" rows="7" style="width: 100%;"><?php echo $feedback->BusinessStrapline->Comments->__toString(); ?></textarea>
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
		if($feedback->BusinessStrapline->Status->__toString() == 'NA')
			echo HTML::renderWorkbookIcons('BusinessStrapline', false, 'btn-danger');
		else
			echo HTML::renderWorkbookIcons('BusinessStrapline', false, 'btn-success');
		?>
	</div></div></div>
	<?php echo $wb->getPageBottomLine(); ?>
</div>
<!--.page 9 ends-->

<h1>Marketing</h1>

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
			<p class="callout callout-info text-bold">Now you have completed the section on Marketing answer the following questions:</p>
			<p class="text-bold">Unit 3 - 1.1 Explain what is meant by market share</p>
			<textarea name="Question1" rows="7" style="width: 100%;"><?php echo $QualificationQuestions->Question1->__toString(); ?></textarea>
			<p><br></p>
			<p class="text-bold">Unit 3 - 1.2 Explain what is meant by unique selling point (USP)</p>
			<textarea name="Question2" rows="7" style="width: 100%;"><?php echo $QualificationQuestions->Question2->__toString(); ?></textarea>
			<p><br></p>
			<p class="text-bold">Unit 3 - 1.3 Identify how your business competes against its competitors</p>
			<textarea name="Question3" rows="7" style="width: 100%;"><?php echo $QualificationQuestions->Question3->__toString(); ?></textarea>
			<p><br></p>
			<p class="text-bold">Unit 3 - 1.4 Identify your business' main competitors</p>
			<textarea name="Question4" rows="7" style="width: 100%;"><?php echo $QualificationQuestions->Question4->__toString(); ?></textarea>
			<p><br></p>
			<p class="text-bold">Unit 3 - 2.1 Explain how purchasing decisions can be influenced by providing accurate guidance on product and price comparisons</p>
			<textarea name="Question5" rows="7" style="width: 100%;"><?php echo $QualificationQuestions->Question5->__toString(); ?></textarea>
			<p><br></p>
			<p class="text-bold">Unit 3 - 2.2 Explain where the business sits within the wider industry</p>
			<textarea name="Question6" rows="7" style="width: 100%;"><?php echo $QualificationQuestions->Question6->__toString(); ?></textarea>
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
<!--.page 10 ends-->

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

		$("#frm_wb_marketing :input").not(".assessorFeedback :input, #signature_text, #frm_wb_marketing :input[type=hidden], .btnViewSectionHistory, .btnViewAssessorComments, #iv_status, #iv_comments, #reopen_comments").prop("disabled", true);

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
							var myForm = document.forms['frm_wb_marketing'];
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
				var myForm = document.forms['frm_wb_marketing'];
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

					var myForm = document.forms['frm_wb_marketing'];
					myForm.elements['full_save'].value = 'Y';
					myForm.elements['full_save_feedback'].value = 'Y';
					window.onbeforeunload = null;
					myForm.submit();

				},
				'Save And Come Back Later':function () {

					var myForm = document.forms['frm_wb_marketing'];
					myForm.elements['full_save'].value = 'Y';
					myForm.elements['full_save_feedback'].value = 'N';
					window.onbeforeunload = null;
					myForm.submit();

				}
			}
		});
	});

	function partialSave() {
		$('#frm_wb_marketing :input[name=full_save]').val('N');
		$($('#frm_wb_marketing').serializeArray()).each(function(i, field)
		{
			document.forms["frm_wb_marketing"].elements[field.name].value = field.value.replace(/£/g, "GBP");
		});
		$.ajax({
			type:'POST',
			url:'do.php?_action=save_wb_marketing',
			data:$('#frm_wb_marketing').serialize(),
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
