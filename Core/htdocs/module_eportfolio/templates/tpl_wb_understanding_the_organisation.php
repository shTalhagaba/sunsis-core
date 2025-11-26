<?php /* @var $wb WBUnderstandingTheOrganisation */ ?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/html" xmlns="http://www.w3.org/1999/html" xmlns="http://www.w3.org/1999/html"
      xmlns="http://www.w3.org/1999/html">
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<title>Understanding the organisation workbook</title>
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

<form name="frm_wb_understanding_the_organisation" id="frm_wb_understanding_the_organisation" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
<input type="hidden" name="_action" value="save_wb_understanding_the_organisation" />
<input type="hidden" name="id" value="<?php echo $wb->id; ?>" />
<input type="hidden" name="tr_id" value="<?php echo $wb->tr_id; ?>" />
<input type="hidden" name="wb_status" id="wb_status" value="" />
<input type="hidden" name="full_save" id="full_save" value="<?php echo $wb->full_save; ?>" />
<input type="hidden" name="full_save_feedback" id="full_save_feedback" value="<?php echo $wb->full_save_feedback; ?>" />
<div class="container-float">
<div class="wrapper" style="background-color: #ffffff;">

<header class="main-header"><span style="margin-left: 50%;"><?php echo DAO::getSingleValue($link, "SELECT title FROM student_frameworks WHERE tr_id = '{$wb->tr_id}'"); ?></span></header>

<div class="content-wrapper" style="background-color: #ffffff;">

<?php echo $wb->savers_or_sp == 'savers' ? '<section class="content-header"><h1 style="color: #0000ff;">Understanding the organisation</h1></section>' : '<section class="content-header"><h1>Understanding the organisation</h1></section>' ?>

<section class="content">

<div id="wizard">

<h1>Understanding the organisation</h1>
<div class="step-content">
	<?php echo $wb->getPageTopLine(); ?>
	<div style="position: absolute; top: 40%; right: 50%;" class="lead">
		<?php echo $wb->savers_or_sp == 'savers' ? '<h2 class="text-bold" style="color: #0000ff;">Understanding the organisation</h2>' : '<h2 class="text-bold">Understanding the organisation</h2>' ?>
		<p class="text-center" >Module 9</p>
	</div>

	<?php echo $wb->getPageBottomLine(); ?>
</div> <!--.page 1 ends-->

<h1>Understanding the organisation</h1>
<div class="step-content">
	<?php echo $wb->getPageTopLine(); ?>

	<div class="row"><div class="col-sm-12"><div class="pull-right"><?php echo HTML::renderWorkbookIcons('', true); ?></div></div></div>


	<div class="row">
		<div class="col-sm-12">
			<p>This module is about understanding the purpose of an organisation, its culture, core values and brand.
				The purpose of all retail organisations is to sell products to consumers and ultimately make a profit. How this is achieved as well as the culture, core values and brand will be different for each business.
			</p>
			<p>In this module you will look at:</p>
			<ul style="margin-left: 15px; margin-bottom: 15px;">
				<li>The difference between public, private and nonprofit sectors</li>
				<li>Vision, mission and core values of an organisation</li>
				<li>Brand reputation</li>
				<li>Brand image</li>
				<li>Brand promise</li>
				<li>An organisations culture</li>
				<li>Digital media</li>
			</ul>
		</div>
		<div class="col-sm-12">
			<img src="module_eportfolio/assets/images/wb9_pg2_img1.png" />
		</div>
	</div>

	<p><br></p>

	<div class="row"><div class="col-sm-12"><div class="pull-right"><?php echo HTML::renderWorkbookIcons('', true); ?></div></div></div>

	<?php echo $wb->getPageBottomLine(); ?>
</div> <!--.page 2 ends-->

<h1>Understanding the organisation</h1>
<div class="step-content">
	<?php echo $wb->getPageTopLine(); ?>

	<div class="row"><div class="col-sm-12"><div class="pull-right"><?php echo HTML::renderWorkbookIcons('', true); ?></div></div></div>


	<div class="row">
		<div class="col-sm-12">
			<p class="text-bold">Public, Private and Nonprofit Sectors</p>
		</div>
	</div>

	<div class="row">
		<div class="col-sm-12 table-responsive">
			<table class="table">
				<tr>
					<td style="vertical-align: middle;" class="bg-blue"><p>The Public Sector (often referred to collectively as 'The Government') is responsible for providing all Public Services in the UK. From Healthcare to Education, Social Care to Housing, Refuse Collection to International Development, Tourism Promotion to Pensions.</p></td>
					<td style="vertical-align: middle;"><img src="module_eportfolio/assets/images/wb9_pg3_img.png" /></td>
					<td style="vertical-align: middle; border: #d2d6de; border-style: solid;"><p style="font-style: italic;">The aims of the Public sector are to provide essential services to the public whilst staying within an agreed budget. Some of the services will focus on enforcing legislation.  Work may be contracted out to organisations within the private sector</p></td>
				</tr>
				<tr><td colspan="3"></td> </tr>
				<tr><td colspan="3"></td> </tr>
				<tr>
					<td style="vertical-align: middle;" class="bg-pink"><p>The Private sector encompasses all for-profit businesses that are not owned or operated by the government. It is also known as the commercial sector.</p></td>
					<td style="vertical-align: middle;"><img src="module_eportfolio/assets/images/wb9_pg3_img.png" /></td>
					<td style="vertical-align: middle; border: #FFC0CB; border-style: solid;"><p style="font-style: italic;">The aim of a business in the private sector is to survive by making a profit. This may be a sole-trader working alone, like a newsagent, or thousands of shareholders in a large Public Limited Company.
						Businesses gain a larger market-share by increasing the sales of their products against competitors. This may involve reducing prices. To win the loyalty of customers and encourage repeat sales, businesses need to be reliable and provide a quality service to their customers.</p>
					</td>
				</tr>
				<tr><td colspan="3"></td> </tr>
				<tr><td colspan="3"></td> </tr>
				<tr>
					<td style="vertical-align: middle;" class="bg-green"><p>The purpose of the non-profit sector is to improve and enrich society. It exists to create social wealth rather than material wealth. It is sometimes referred to as the voluntary sector, civil society, the third sector, non-profit, not-for-profit, charity, social and even beyond profit sector. They will be exempt from paying tax but are not allowed to make a profit.</p></td>
					<td style="vertical-align: middle;"><img src="module_eportfolio/assets/images/wb9_pg3_img.png" /></td>
					<td style="vertical-align: middle; border: #00a65a; border-style: solid;"><p style="font-style: italic;">Aims will depend on the type of service offered however examples are fundraising for a cause, providing volunteers to support individuals or groups in the community or improving local facilities or advisory services.</p></td>
				</tr>
			</table>
		</div>
		<div class="col-sm-12 text-center">
			<img src="module_eportfolio/assets/images/wb9_pg3_img1.png" />
		</div>
	</div>

	<p><br></p>

	<div class="row"><div class="col-sm-12"><div class="pull-right"><?php echo HTML::renderWorkbookIcons('', true); ?></div></div></div>

	<?php echo $wb->getPageBottomLine(); ?>
</div> <!--.page 3 ends-->

<h1>Understanding the organisation</h1>
<div class="step-content">
	<?php echo $wb->getPageTopLine(); ?>

	<div class="row"><div class="col-sm-12"><div class="pull-right">
		<?php
		if($feedback->Sectors->Status->__toString() == 'NA')
			echo HTML::renderWorkbookIcons('Sectors', false, 'btn-danger');
		else
			echo HTML::renderWorkbookIcons('Sectors', false, 'btn-success');
		?>
	</div></div></div>


	<div class="row">
		<div class="col-sm-10 text-center">
			<p class="text-bold"><img src="module_eportfolio/assets/images/wb2_img1.png" /> &nbsp; Complete the table below by giving two examples of organisations for each of the three sectors. Include <?php echo ucfirst($wb->savers_or_sp); ?> in your answer. &nbsp; <img src="module_eportfolio/assets/images/wb2_img2.png" /></p>
		</div>
	</div>

	<div class="row">
		<div class="col-sm-12 table-responsive">
			<table class="table row-border text-center">
				<tr><th>SECTOR</th><th>ORGANISATION</th><th>AIM OF THE ORGANISATION</th> </tr>
				<?php
				$Public = $answers->Sectors->Public;
				for($i = 1; $i <= 2; $i++)
				{
					$key = 'Set'.$i;
					echo '<tr>';
					echo '<td><span class="text-blue text-bold" style="font-size: larger; text-shadow: 0px 5px 2px rgba(150, 150, 161, 0.89);">PUBLIC SECTOR</span> </td>';
					echo '<td><input type="text" name="PublicOrganisation'.$i.'" class="form-control" value="' . $Public->$key->Organisation->__toString() . '" /></td>';
					echo '<td><textarea rows="3" name="PublicAimOfOrganisation'.$i.'" style="width: 100%;">' . $Public->$key->AimOfOrganisation->__toString() . '</textarea></td>';
					echo '</tr>';
				}
				$Private = $answers->Sectors->Private;
				for($i = 1; $i <= 2; $i++)
				{
					$key = 'Set'.$i;
					echo '<tr>';
					echo '<td><span class="text-pink text-bold" style="font-size: larger; text-shadow: 0px 5px 7px rgba(150, 150, 161, 0.89);">PRIVATE SECTOR</span> </td>';
					echo '<td><input type="text" name="PrivateOrganisation'.$i.'" class="form-control" value="' . $Private->$key->Organisation->__toString() . '" /></td>';
					echo '<td><textarea rows="3" name="PrivateAimOfOrganisation'.$i.'" style="width: 100%;">' . $Private->$key->AimOfOrganisation->__toString() . '</textarea></td>';
					echo '</tr>';
				}
				$NonProfit = $answers->Sectors->NonProfit;
				for($i = 1; $i <= 2; $i++)
				{
					$key = 'Set'.$i;
					echo '<tr>';
					echo '<td><span class="text-green text-bold" style="font-size: larger; text-shadow: 0px 5px 2px rgba(150, 150, 161, 0.89);">NON PROFIT SECTOR</span> </td>';
					echo '<td><input type="text" name="NonProfitOrganisation'.$i.'" class="form-control" value="' . $NonProfit->$key->Organisation->__toString() . '" /></td>';
					echo '<td><textarea rows="3" name="NonProfitAimOfOrganisation'.$i.'" style="width: 100%;">' . $NonProfit->$key->AimOfOrganisation->__toString() . '</textarea></td>';
					echo '</tr>';
				}
				?>
			</table>
		</div>
		<div class="col-sm-12 text-center">
			<div class="col-sm-6"><img src="module_eportfolio/assets/images/wb9_pg4_img1.png" /></div>
			<div class="col-sm-6"><img src="module_eportfolio/assets/images/wb9_pg4_img2.png" /></div>
		</div>
	</div>

	<?php if($wb->id != '' && $_SESSION['user']->type == User::TYPE_ASSESSOR && ($wb->wb_status == Workbook::STATUS_LEARNER_COMPLETED || $wb->wb_status == Workbook::STATUS_BEING_CHECKED || $wb->wb_status == Workbook::STATUS_IV_REJECTED)) {?>
	<div class="row well">
		<div class="col-sm-12">
			<div class="box box-success box-solid">
				<div class="box-header with-border"><h3 class="box-title">Assessor Feedback</h3></div>
				<div class="box-body assessorFeedback" <?php echo $feedback->Sectors->Status->__toString() == 'A'?'style="pointer-events:none;"':''; ?>>
					<div class="form-group">
						<label for="status_Sectors" class="col-sm-12 control-label">Status:</label>
						<div class="col-sm-12">
							<?php
							echo HTML::selectChosen('status_Sectors', $answer_status, $feedback->Sectors->Status->__toString() == 'A'?$feedback->Sectors->Status->__toString():'', false, true); ?>
						</div>
					</div>
					<div class="form-group">
						<label for="comments_Sectors" class="col-sm-12 control-label">Comments:</label>
						<div class="col-sm-12">
							<textarea name="comments_Sectors" rows="7" style="width: 100%;"><?php echo $feedback->Sectors->Comments->__toString(); ?></textarea>
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
		if($feedback->Sectors->Status->__toString() == 'NA')
			echo HTML::renderWorkbookIcons('Sectors', false, 'btn-danger');
		else
			echo HTML::renderWorkbookIcons('Sectors', false, 'btn-success');
		?>
	</div></div></div>

	<?php echo $wb->getPageBottomLine(); ?>
</div> <!--.page 4 ends-->

<h1>Understanding the organisation</h1>
<div class="step-content">
	<?php echo $wb->getPageTopLine(); ?>

	<div class="row"><div class="col-sm-12"><div class="pull-right">
		<?php
		if($feedback->VisionMissionCoreValues->Status->__toString() == 'NA')
			echo HTML::renderWorkbookIcons('VisionMissionCoreValues', false, 'btn-danger');
		else
			echo HTML::renderWorkbookIcons('VisionMissionCoreValues', false, 'btn-success');
		?>
	</div></div></div>


	<div class="row">
		<div class="col-sm-12">
			<p class="text-bold text-center" style="font-size: larger;">Vision, Mission, and Core Values</p>
		</div>
		<div class="col-sm-6">
			<blockquote style="border: 3px dashed #0000ff">
				<p class="text-center text-bold">A vision statement</p>
				<p class="text-center">An aspirational description of what an organisation would like to achieve or accomplish in the mid-term or long-term future. It is intended to serve as a clear guide for choosing current and future courses of action.</p>
			</blockquote>
		</div>
		<div class="col-sm-6">
			<blockquote style="border: 3px dashed #0000ff">
				<p class="text-center text-bold">A mission statement</p>
				<p class="text-center">A written declaration of an organisation's core purpose and focus that normally remains unchanged over time.</p>
			</blockquote>
		</div>
	</div>

	<div class="row">
		<div class="col-sm-12">
			<p>A mission is different from a vision in that the former is the cause and the latter is the effect; a mission is something to be accomplished whereas a vision is something to be pursued for that accomplishment. </p>
		</div>
		<div class="col-sm-12">
			<blockquote style="border: 3px dashed #ffc0cb">
				<p class="text-center text-bold">Corporate values</p>
				<p class="text-center">The operating philosophies or principles that guide an organisation's internal conduct as well as its relationship with its customers, partners and shareholders<br></p>
				<p class="text-center">Core values are usually summarised in the mission statement or in the company's statement of core values.</p>
			</blockquote>
		</div>
		<div class="col-sm-12">
			<div style="border: 2px dashed #0000ff">
				<p><h4 class="text-center text-bold">Corporate objectives</h4></p>
				<p class="text-center">A well-defined and realistic goal set by a company that often influences its internal strategic decisions. Most corporate objective targets used by a business will specify the time frame anticipated for their achievement and how the company's success in doing so is to be assessed.</p>
			</div>
		</div>
	</div>

	<div class="row">
		<div class="col-sm-12 text-center">
			<p class="text-bold"><img src="module_eportfolio/assets/images/wb2_img1.png" /> &nbsp; As well as having the above vision, mission and corporate values <?php echo ucfirst($wb->savers_or_sp); ?> also has a purpose statement (i.e. what they are trying to achieve). Fill in the blanks to complete <?php echo ucfirst($wb->savers_or_sp); ?>’s purpose. &nbsp; <img src="module_eportfolio/assets/images/wb2_img2.png" /></p>
		</div>
	</div>

	<div class="row">
		<div class="col-sm-12">
			<p style="font-size: 17.5px; ">
				<?php
				$VisionMissionCoreValues = $answers->VisionMissionCoreValues;
				$SuperdrugStatement = $VisionMissionCoreValues->SuperdrugStatement;
				?>
				Our purpose is quite <input type="text" name="Blank1" size="15" value="<?php echo $SuperdrugStatement->Blank1->__toString(); ?>" /> to be the <input type="text" name="Blank2" size="15" value="<?php echo $SuperdrugStatement->Blank2->__toString(); ?>" />
				in everyday accessible <input type="text" name="Blank3" size="15" value="<?php echo $SuperdrugStatement->Blank3->__toString(); ?>" /> and health. We are committed to bring <input type="text" name="Blank4" size="15" value="<?php echo $SuperdrugStatement->Blank4->__toString(); ?>" />
				and the latest styles and trends to <input type="text" name="Blank5" size="15" value="<?php echo $SuperdrugStatement->Blank5->__toString(); ?>" /> high streets in the UK and Republic of Ireland
				at <input type="text" name="Blank6" size="15" value="<?php echo $SuperdrugStatement->Blank6->__toString(); ?>" /> prices.
			</p>
		</div>
	</div>

	<div class="row">
		<div class="col-sm-12 text-center"><hr></div>
		<div class="col-sm-12 text-center">
			<p class="text-bold"><img src="module_eportfolio/assets/images/wb2_img1.png" /> &nbsp;
				For your next activity, log on to the Hub and watch the <?php echo ucfirst($wb->savers_or_sp); ?> Vision and Mission video. <br>
				On the Hub go to: <?php echo ucfirst($wb->savers_or_sp); ?> News >> Working Here >> Our Vision, Mission and Values Follow the onscreen instructions to watch the video. <br>
				Write in the boxes below what the Vision, Mission and Core Values are and then answer some questions
			</p>
		</div>
		<div class="col-sm-12 text-center">
			<p class="text-bold" style="font-size: 17.5px;">Vision</p>
			<p><textarea name="Vision" style="width: 100%;" rows="7"><?php echo $VisionMissionCoreValues->Vision->__toString(); ?></textarea></p>
			<p><hr></p>
		</div>
		<div class="col-sm-12 text-center">
			<p class="text-bold" style="font-size: 17.5px;">Mission</p>
			<p><textarea name="Mission" style="width: 100%;" rows="7"><?php echo $VisionMissionCoreValues->Mission->__toString(); ?></textarea> </p>
			<p><hr></p>
		</div>
		<div class="col-sm-12 text-center">
			<p class="text-bold" style="font-size: 17.5px;">Core Values</p>
			<p><textarea name="CoreValues" style="width: 100%;" rows="7"><?php echo $VisionMissionCoreValues->CoreValues->__toString(); ?></textarea> </p>
			<p><hr></p>
		</div>
		<div class="col-sm-6 text-center">
			<p class="text-bold"><img src="module_eportfolio/assets/images/wb2_img1.png" /> &nbsp;What do you think are the benefits of a company having a Vision, Mission and Core Values?</p>
			<p><textarea name="Benefits" style="width: 100%;" rows="7"><?php echo $VisionMissionCoreValues->Benefits->__toString(); ?></textarea> </p>
		</div>
		<div class="col-sm-6 text-center">
			<p class="text-bold"><img src="module_eportfolio/assets/images/wb2_img1.png" /> &nbsp;How do you think it impacts your role?</p>
			<p><textarea name="ImpactOnRole" style="width: 100%;" rows="7"><?php echo $VisionMissionCoreValues->ImpactOnRole->__toString(); ?></textarea> </p>
		</div>
		<div class="col-sm-12"><p><hr></p></div>
		<div class="col-sm-12">
			<p class="text-bold"><img src="module_eportfolio/assets/images/wb2_img1.png" /> &nbsp;Who are the people in this video?<img src="module_eportfolio/assets/images/wb2_img2.png" /> &nbsp;</p>
			<p><textarea name="People" style="width: 100%;" rows="7"><?php echo $VisionMissionCoreValues->People->__toString(); ?></textarea> </p>
		</div>
	</div>

	<?php if($wb->id != '' && $_SESSION['user']->type == User::TYPE_ASSESSOR && ($wb->wb_status == Workbook::STATUS_LEARNER_COMPLETED || $wb->wb_status == Workbook::STATUS_BEING_CHECKED || $wb->wb_status == Workbook::STATUS_IV_REJECTED)) {?>
	<div class="row well">
		<div class="col-sm-12">
			<div class="box box-success box-solid">
				<div class="box-header with-border"><h3 class="box-title">Assessor Feedback</h3></div>
				<div class="box-body assessorFeedback" <?php echo $feedback->VisionMissionCoreValues->Status->__toString() == 'A'?'style="pointer-events:none;"':''; ?>>
					<div class="form-group">
						<label for="status_VisionMissionCoreValues" class="col-sm-12 control-label">Status:</label>
						<div class="col-sm-12">
							<?php
							echo HTML::selectChosen('status_VisionMissionCoreValues', $answer_status, $feedback->VisionMissionCoreValues->Status->__toString() == 'A'?$feedback->VisionMissionCoreValues->Status->__toString():'', false, true); ?>
						</div>
					</div>
					<div class="form-group">
						<label for="comments_VisionMissionCoreValues" class="col-sm-12 control-label">Comments:</label>
						<div class="col-sm-12">
							<textarea name="comments_VisionMissionCoreValues" rows="7" style="width: 100%;"><?php echo $feedback->VisionMissionCoreValues->Comments->__toString(); ?></textarea>
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
		if($feedback->VisionMissionCoreValues->Status->__toString() == 'NA')
			echo HTML::renderWorkbookIcons('VisionMissionCoreValues', false, 'btn-danger');
		else
			echo HTML::renderWorkbookIcons('VisionMissionCoreValues', false, 'btn-success');
		?>
	</div></div></div>

	<?php echo $wb->getPageBottomLine(); ?>
</div> <!--.page 5,6 ends-->

<h1>Understanding the organisation</h1>
<div class="step-content">
	<?php echo $wb->getPageTopLine(); ?>

	<div class="row"><div class="col-sm-12"><div class="pull-right">
		<?php
		if($feedback->BrandImagePromise->Status->__toString() == 'NA')
			echo HTML::renderWorkbookIcons('BrandImagePromise', false, 'btn-danger');
		else
			echo HTML::renderWorkbookIcons('BrandImagePromise', false, 'btn-success');
		?>
	</div></div></div>


	<div class="row">
		<div class="col-sm-6">
			<blockquote style="border: 1px dashed #ffc0cb">
				<p class="text-center text-bold">
					<span class="text-bold text-purple">Brand reputation</span>
					<i>refers to how a particular brand (whether for an individual or a company) is viewed by others.</i>
				</p>
			</blockquote>
		</div>
		<div class="col-sm-6"><img src="module_eportfolio/assets/images/wb9_pg7_img1.png" /></div>
		<div class="col-sm-12"><p>A favourable brand reputation means consumers trust your company, and feel good about purchasing your goods or services.</p></div>
		<div class="col-sm-6">
			<blockquote style="border: 1px dashed #000000">
				<p class="text-center text-bold">
					<span class="text-bold text-purple">Brand Image</span>
					<i>- a company’s brand image is the sum total of all the perceptions held by their current, past, and potential customers about the company’s specific products and services.</i>
				</p>
			</blockquote>
			<p>Brand attributes include things like quality, value, variety and the shopping experience. From the potential buyer’s perspective, brand is all about, “What’s in it for me?”</p>
			<p class="text-bold text-purple">Reputation</p>
			<p>Reputation, on the other hand, is the entirety of the public’s opinion about a company’s corporate actions. Reputation attributes include community building, corporate culture, policy, job creation, and citizenship. The public asks the question, “Is this company the good guys or the bad guys?”</p>
		</div>
		<div class="col-sm-6">
			<p class="text-bold text-purple">Brand Image and Reputation</p>
			<p>Although they are two different things, brand image and reputation  are obviously strongly related and often trend in the same direction, especially in times of crisis. However, in some cases a strong brand image can overcome reputation problems and reputation problems can damage a strong brand.</p>
			<p>Regardless of how it is represented, a brand image and reputation will depend on a number of factors, such as the products a company sells, the actions it takes and the manner in which it communicates to customers.</p>
			<blockquote style="border: 1px dashed #000000">
				<p class="text-center text-bold">
					<span class="text-bold text-purple">A brand promise</span>
					<i>is the statement that an organisation makes to its customers in order to help them identify what they can expect when they have contact with their people, products and/or services.</i>
				</p>
			</blockquote>
			<p>It is often associated with the company name and logo. The type of language used within an organisation and the style of language used with customers’ plays an important role in reinforcing and supporting a brand promise.</p>
			<p>The style of customer service language used must support the brand promise. One experience for a customer will create either a positive or negative view of the brand.</p>
		</div>
	</div>

	<div class="row">
		<div class="col-sm-12">
			<?php
			$BrandImagePromise = $answers->BrandImagePromise;
			$InWork = $BrandImagePromise->InWork;
			$OutsideWork = $BrandImagePromise->OutsideWork;
			?>
			<p class="text-bold"><img src="module_eportfolio/assets/images/wb2_img1.png" /> &nbsp;When you think about <?php echo ucfirst($wb->savers_or_sp); ?> what words would you use to describe our brand image and promise?<img src="module_eportfolio/assets/images/wb2_img2.png" /> &nbsp;</p>
			<p><textarea name="Superdrug" style="width: 100%;" rows="7"><?php echo $BrandImagePromise->Superdrug->__toString(); ?></textarea> </p>
		</div>
	</div>

	<p><br></p>

	<div class="row">
		<div class="col-sm-12">
			<p class="text-bold"><img src="module_eportfolio/assets/images/wb2_img1.png" /> &nbsp;Complete the tables below to show how what we do inside and outside of work can impact on a company’s brand image and reputation. An example for each has been provided.&nbsp;</p>
			<p class="text-bold">Topics you may want to use in your examples:</p>
		</div>
		<div class="col-sm-12">
			<div class="callout callout-info text-center">
				Social Media, Data Protection, Customer Perception, Customer Service, Sales, Profit,
				Law and Legislation, Team Work
			</div>
		</div>
	</div>

	<div class="row">
		<div class="col-sm-12">
			<div class="box box-solid box-success">
				<div class="box-header with-border">
					<h3 class="box-title">In work</h3>
					<div class="box-tools pull-right">
						<img class="pull-right" src="module_eportfolio/assets/images/wb2_img2.png" />
					</div>
				</div>
				<div class="box-body">
					<div class="table-responsive">
						<table class="table table-bordered text-center">
							<tr class="bg-gray"><th style="width: 50%;">Positive Behaviour</th><th style="width: 50%;">Impact on business success</th></tr>
							<tr><td>Knowledgeable about products and sharing information with customers</td><td>Customer will tell friends of their good experience and return to store</td></tr>
							<?php
							for($i = 1; $i <= 2; $i++)
							{
								$key = 'Set'.$i;
								echo '<tr>';
								echo '<td><textarea rows="3" name="InWorkPositiveBehaviour'.$i.'" style="width: 100%;">' . $InWork->Positive->$key->Behaviour->__toString() . '</textarea> </td>';
								echo '<td><textarea rows="3" name="InWorkPositiveImpactOnBusiness'.$i.'" style="width: 100%;">' . $InWork->Positive->$key->ImpactOnBusiness->__toString() . '</textarea> </td>';
								echo '</tr>';
							}
							?>
						</table>
					</div>
					<div class="table-responsive">
						<table class="table table-bordered text-center">
							<tr class="bg-gray"><th style="width: 50%;">Negative Behaviour</th><th style="width: 50%;">Impact on business success</th></tr>
							<tr><td>Unhappy staff using bad language on the shop floor</td><td>Customer feels uncomfortable and leaves the store. Changes their opinion of the brand</td></tr>
							<?php
							for($i = 1; $i <= 2; $i++)
							{
								$key = 'Set'.$i;
								echo '<tr>';
								echo '<td><textarea rows="3" name="InWorkNegativeBehaviour'.$i.'" style="width: 100%;">' . $InWork->Negative->$key->Behaviour->__toString() . '</textarea> </td>';
								echo '<td><textarea rows="3" name="InWorkNegativeImpactOnBusiness'.$i.'" style="width: 100%;">' . $InWork->Negative->$key->ImpactOnBusiness->__toString() . '</textarea> </td>';
								echo '</tr>';
							}
							?>
						</table>
					</div>
				</div>
			</div>
		</div>
	</div>

	<div class="row"><div class="col-sm-12 text-center"> <p><img src="module_eportfolio/assets/images/wb9_pg9_img1.png" /></p></div> </div>

	<div class="row">
		<div class="col-sm-12">
			<div class="box box-solid box-success">
				<div class="box-header with-border">
					<h3 class="box-title">Outside of work</h3>
					<div class="box-tools pull-right">
						<img class="pull-right" src="module_eportfolio/assets/images/wb2_img2.png" />
					</div>
				</div>
				<div class="box-body">
					<div class="table-responsive">
						<table class="table table-bordered text-center">
							<tr class="bg-gray"><th style="width: 50%;">Positive Behaviour</th><th style="width: 50%;">Impact on business success</th></tr>
							<tr><td>Telling family and friends about how much you enjoy your job</td><td>Friends and family will share your experiences with their friends and will visit SD/SV</td></tr>
							<?php
							for($i = 1; $i <= 2; $i++)
							{
								$key = 'Set'.$i;
								echo '<tr>';
								echo '<td><textarea name="OutsideWorkPositiveBehaviour'.$i.'" style="width: 100%;">' . $OutsideWork->Positive->$key->Behaviour->__toString() . '</textarea> </td>';
								echo '<td><textarea name="OutsideWorkPositiveImpactOnBusiness'.$i.'" style="width: 100%;">' . $OutsideWork->Positive->$key->ImpactOnBusiness->__toString() . '</textarea> </td>';
								echo '</tr>';
							}
							?>
						</table>
					</div>
					<div class="table-responsive">
						<table class="table table-bordered text-center">
							<tr class="bg-gray"><th style="width: 50%;">Negative Behaviour</th><th style="width: 50%;">Impact on business success</th></tr>
							<tr><td>Talking about customers negatively in public</td><td>People will overhear conversation and think we have unprofessional staff and share this with others</td></tr>
							<?php
							for($i = 1; $i <= 2; $i++)
							{
								$key = 'Set'.$i;
								echo '<tr>';
								echo '<td><textarea rows="3" name="OutsideWorkNegativeBehaviour'.$i.'" style="width: 100%;">' . $OutsideWork->Negative->$key->Behaviour->__toString() . '</textarea> </td>';
								echo '<td><textarea rows="3" name="OutsideWorkNegativeImpactOnBusiness'.$i.'" style="width: 100%;">' . $OutsideWork->Negative->$key->ImpactOnBusiness->__toString() . '</textarea> </td>';
								echo '</tr>';
							}
							?>
						</table>
					</div>
				</div>
			</div>
		</div>
	</div>

	<?php if($wb->id != '' && $_SESSION['user']->type == User::TYPE_ASSESSOR && ($wb->wb_status == Workbook::STATUS_LEARNER_COMPLETED || $wb->wb_status == Workbook::STATUS_BEING_CHECKED || $wb->wb_status == Workbook::STATUS_IV_REJECTED)) {?>
	<div class="row well">
		<div class="col-sm-12">
			<div class="box box-success box-solid">
				<div class="box-header with-border"><h3 class="box-title">Assessor Feedback</h3></div>
				<div class="box-body assessorFeedback" <?php echo $feedback->BrandImagePromise->Status->__toString() == 'A'?'style="pointer-events:none;"':''; ?>>
					<div class="form-group">
						<label for="status_BrandImagePromise" class="col-sm-12 control-label">Status:</label>
						<div class="col-sm-12">
							<?php
							echo HTML::selectChosen('status_BrandImagePromise', $answer_status, $feedback->BrandImagePromise->Status->__toString() == 'A'?$feedback->BrandImagePromise->Status->__toString():'', false, true); ?>
						</div>
					</div>
					<div class="form-group">
						<label for="comments_BrandImagePromise" class="col-sm-12 control-label">Comments:</label>
						<div class="col-sm-12">
							<textarea name="comments_BrandImagePromise" rows="7" style="width: 100%;"><?php echo $feedback->BrandImagePromise->Comments->__toString(); ?></textarea>
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
		if($feedback->BrandImagePromise->Status->__toString() == 'NA')
			echo HTML::renderWorkbookIcons('BrandImagePromise', false, 'btn-danger');
		else
			echo HTML::renderWorkbookIcons('BrandImagePromise', false, 'btn-success');
		?>
	</div></div></div>

	<?php echo $wb->getPageBottomLine(); ?>
</div> <!--.page 7,8,9 ends-->

<h1>Understanding the organisation</h1>
<div class="step-content">
	<?php echo $wb->getPageTopLine(); ?>

	<div class="row"><div class="col-sm-12"><div class="pull-right">
		<?php
		if($feedback->OrganisationCulture->Status->__toString() == 'NA')
			echo HTML::renderWorkbookIcons('OrganisationCulture', false, 'btn-danger');
		else
			echo HTML::renderWorkbookIcons('OrganisationCulture', false, 'btn-success');
		?>
	</div></div></div>


	<div class="row">
		<div class="col-sm-12">
			<p class="text-bold">An organisation's culture</p>
		</div>
	</div>

	<div class="row">
		<div class="col-sm-12">
			<p>Culture is a word for people's 'way of life', meaning the things groups do and the way they do them.A service culture is therefore a way to deliver service to customers on a regular basis.</p>
			<?php if($wb->savers_or_sp == 'savers'){?>
			<p>In Savers we do this by following our customer service training E.g. Savers service standards.</p>
			<?php } else {?>
			<p>In Superdrug we do this by following our Customer Experience training E.g. Positive First and Ownership and Responsibility.</p>
			<?php } ?>
		</div>
	</div>

	<?php
	$OrganisationCulture = $answers->OrganisationCulture;
	$Demonstration = $OrganisationCulture->Demonstration;
	?>

	<div class="row">
		<div class="col-sm-12">
			<p class="text-bold"><img src="module_eportfolio/assets/images/wb2_img1.png" /> &nbsp;Give 2 examples of demonstrating service culture.<img src="module_eportfolio/assets/images/wb2_img2.png" /> &nbsp;</p>
			<table class="table table-bordered">
				<tr class="bg-gray"><th style="width: 50%;">What do we do?</th><th style="width: 50%;">How do we do it?</th></tr>
				<?php
				for($i = 1; $i <= 2; $i++)
				{
					$key = 'Set'.$i;
					echo '<tr><td><textarea rows="3" name="What'.$i.'" style="width: 100%;">' . $Demonstration->$key->What->__toString() . '</textarea> </td><td><textarea name="How'.$i.'" style="width: 100%;">' . $Demonstration->$key->How->__toString() . '</textarea> </td></tr>';
				}
				?>
			</table>
		</div>
		<div class="col-sm-12">
			<p class="text-bold"><img src="module_eportfolio/assets/images/wb2_img1.png" /> &nbsp;How do <?php echo ucfirst($wb->savers_or_sp); ?>'s core values link to our service culture?&nbsp;</p>
			<textarea rows="3" name="LinkOfCoreValues" style="width: 100%;"><?php echo $OrganisationCulture->LinkOfCoreValues->__toString(); ?></textarea>
		</div>
	</div>
	<p><br></p>
	<div class="row">
		<div class="col-sm-6 text-center text-bold" style="background-color: #ffff00; padding: 20px;">
			<p>Policies are rules and guidelines formulated or adopted by an organisation to reach their long-term goals</p>
		</div>
		<div class="col-sm-6 text-center text-bold bg-green" style=" padding: 20px;">
			<p>Procedures are a series of actions conducted in a certain order or manner.</p>
		</div>
	</div>

	<div class="row">
		<div class="col-sm-12">
			<p class="text-bold"><img src="module_eportfolio/assets/images/wb2_img1.png" /> &nbsp;Identify two organisational policies / procedures that could affect your customer service role and explain why.&nbsp;</p>
			<textarea name="ImpactOfPoliciesProcedures" style="width: 100%;" rows="7"><?php echo $OrganisationCulture->ImpactOfPoliciesProcedures->__toString(); ?></textarea>
		</div>
	</div>

	<?php if($wb->id != '' && $_SESSION['user']->type == User::TYPE_ASSESSOR && ($wb->wb_status == Workbook::STATUS_LEARNER_COMPLETED || $wb->wb_status == Workbook::STATUS_BEING_CHECKED || $wb->wb_status == Workbook::STATUS_IV_REJECTED)) {?>
	<div class="row well">
		<div class="col-sm-12">
			<div class="box box-success box-solid">
				<div class="box-header with-border"><h3 class="box-title">Assessor Feedback</h3></div>
				<div class="box-body assessorFeedback" <?php echo $feedback->OrganisationCulture->Status->__toString() == 'A'?'style="pointer-events:none;"':''; ?>>
					<div class="form-group">
						<label for="status_OrganisationCulture" class="col-sm-12 control-label">Status:</label>
						<div class="col-sm-12">
							<?php
							echo HTML::selectChosen('status_OrganisationCulture', $answer_status, $feedback->OrganisationCulture->Status->__toString() == 'A'?$feedback->OrganisationCulture->Status->__toString():'', false, true); ?>
						</div>
					</div>
					<div class="form-group">
						<label for="comments_OrganisationCulture" class="col-sm-12 control-label">Comments:</label>
						<div class="col-sm-12">
							<textarea name="comments_OrganisationCulture" rows="7" style="width: 100%;"><?php echo $feedback->OrganisationCulture->Comments->__toString(); ?></textarea>
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
		if($feedback->OrganisationCulture->Status->__toString() == 'NA')
			echo HTML::renderWorkbookIcons('OrganisationCulture', false, 'btn-danger');
		else
			echo HTML::renderWorkbookIcons('OrganisationCulture', false, 'btn-success');
		?>
	</div></div></div>

	<?php echo $wb->getPageBottomLine(); ?>
</div> <!--.page 10 ends-->

<h1>Understanding the organisation</h1>
<div class="step-content">
	<?php echo $wb->getPageTopLine(); ?>

	<div class="row"><div class="col-sm-12"><div class="pull-right">
		<?php
		if($feedback->DigitalMedia->Status->__toString() == 'NA')
			echo HTML::renderWorkbookIcons('DigitalMedia', false, 'btn-danger');
		else
			echo HTML::renderWorkbookIcons('DigitalMedia', false, 'btn-success');
		?>
	</div></div></div>


	<div class="row">
		<div class="col-sm-12">
			<p class="text-bold">Digital media</p>
		</div>
	</div>

	<div class="row">
		<div class="col-sm-8">
			<p>Digital media refers to audio, video, and photo content. Digital media can be created, viewed, distributed, modified and preserved on digital electronics devices and can be used in a social or business environment.</p>
			<p>In <?php echo ucfirst($wb->savers_or_sp); ?> a digital media/online behaviour policy exists to ensure strict guidelines are followed when using this method of communication. It is particularly important to follow the procedures in this policy to ensure protection of the brand and to meet all legislation.</p>
		</div>
		<div class="col-sm-4"><img src="module_eportfolio/assets/images/wb9_pg11_img1.png" /></div>
	</div>

	<div class="row">
		<div class="col-sm-12">
			<p class="text-bold"><img src="module_eportfolio/assets/images/wb2_img1.png" /> &nbsp;Read the digital media / online behaviour policy and ensure you have completed the social media training on the Hub. Identify 2 important guidelines within the policy below.&nbsp;<img src="module_eportfolio/assets/images/wb2_img2.png" /> </p>
			<textarea name="DigitalMedia" style="width: 100%;" rows="10"><?php echo $answers->DigitalMedia->__toString(); ?></textarea>
		</div>
	</div>

	<div class="row">
		<div class="col-sm-6 text-center"><img src="module_eportfolio/assets/images/wb9_pg11_img2.png" /></div>
		<div class="col-sm-6 text-center"><img src="module_eportfolio/assets/images/wb9_pg11_img3.png" /></div>
	</div>

	<?php if($wb->id != '' && $_SESSION['user']->type == User::TYPE_ASSESSOR && ($wb->wb_status == Workbook::STATUS_LEARNER_COMPLETED || $wb->wb_status == Workbook::STATUS_BEING_CHECKED || $wb->wb_status == Workbook::STATUS_IV_REJECTED)) {?>
	<div class="row well">
		<div class="col-sm-12">
			<div class="box box-success box-solid">
				<div class="box-header with-border"><h3 class="box-title">Assessor Feedback</h3></div>
				<div class="box-body assessorFeedback" <?php echo $feedback->DigitalMedia->Status->__toString() == 'A'?'style="pointer-events:none;"':''; ?>>
					<div class="form-group">
						<label for="status_DigitalMedia" class="col-sm-12 control-label">Status:</label>
						<div class="col-sm-12">
							<?php
							echo HTML::selectChosen('status_DigitalMedia', $answer_status, $feedback->DigitalMedia->Status->__toString() == 'A'?$feedback->DigitalMedia->Status->__toString():'', false, true); ?>
						</div>
					</div>
					<div class="form-group">
						<label for="comments_DigitalMedia" class="col-sm-12 control-label">Comments:</label>
						<div class="col-sm-12">
							<textarea name="comments_DigitalMedia" rows="7" style="width: 100%;"><?php echo $feedback->DigitalMedia->Comments->__toString(); ?></textarea>
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
		if($feedback->DigitalMedia->Status->__toString() == 'NA')
			echo HTML::renderWorkbookIcons('DigitalMedia', false, 'btn-danger');
		else
			echo HTML::renderWorkbookIcons('DigitalMedia', false, 'btn-success');
		?>
	</div></div></div>

	<?php echo $wb->getPageBottomLine(); ?>
</div> <!--.page 11 ends-->

<h1>Understanding the organisation</h1>
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

	<?php
	$QualificationQuestions = $answers->QualificationQuestions;
	?>
	<div class="row">
		<div class="col-sm-12">
			<h4 class="text-bold">Unit 3: Understanding the organisation</h4>
			<p class="callout callout-info">To achieve learning outcome 1 (Know the purpose of the business) answer the following questions in as much detail as you can.</p>
			<p class="text-bold">1.1 State the aims of the organisation in relation to its relevant sector.</p>
			<textarea name="Question1" rows="7" style="width: 100%;"><?php echo $QualificationQuestions->Question1->__toString(); ?></textarea>
			<p><br></p>
			<p class="text-bold">1.2 State what is meant by the organisations brand promise </p>
			<textarea name="Question2" rows="7" style="width: 100%;"><?php echo $QualificationQuestions->Question2->__toString(); ?></textarea>
			<p><hr></p>
			<p class="callout callout-info">To achieve learning outcome 3 (Know your organisations core values and how they link to service culture) answer the following questions in as much detail as you can.</p>
			<p class="text-bold">3.1  Identify <?php echo ucfirst($wb->savers_or_sp); ?>’s core values</p>
			<textarea name="Question3" rows="7" style="width: 100%;"><?php echo $QualificationQuestions->Question3->__toString(); ?></textarea>
			<p><br></p>
			<p class="text-bold">3.2 Explain how <?php echo ucfirst($wb->savers_or_sp); ?>’s core values relate to its service culture </p>
			<textarea name="Question4" rows="7" style="width: 100%;"><?php echo $QualificationQuestions->Question4->__toString(); ?></textarea>
			<p><hr></p>
			<p class="callout callout-info">To achieve learning outcome 4 (Know internal policies and procedures relevant to their role and organisation) answer the following questions in as much detail as you can.</p>
			<p class="text-bold">4.1 State the purpose of different internal organisational policies and procedures that affect their customer service role.</p>
			<textarea name="Question5" rows="7" style="width: 100%;"><?php echo $QualificationQuestions->Question5->__toString(); ?></textarea>
			<p><br></p>
			<p class="text-bold">4.2 Describe the type of guidelines contained in a digital media policy that affect the use of social and digital media in the work environment</p>
			<textarea name="Question6" rows="7" style="width: 100%;"><?php echo $QualificationQuestions->Question6->__toString(); ?></textarea>
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
</div> <!--.page 12,13,14 ends-->

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

		$("#frm_wb_understanding_the_organisation :input").not(".assessorFeedback :input, #signature_text, #frm_wb_understanding_the_organisation :input[type=hidden], .btnViewSectionHistory, .btnViewAssessorComments, #iv_status, #iv_comments, #reopen_comments").prop("disabled", true);

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
							var myForm = document.forms['frm_wb_understanding_the_organisation'];
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
				var myForm = document.forms['frm_wb_understanding_the_organisation'];
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

					var myForm = document.forms['frm_wb_understanding_the_organisation'];
					myForm.elements['full_save'].value = 'Y';
					myForm.elements['full_save_feedback'].value = 'Y';
					window.onbeforeunload = null;
					myForm.submit();

				},
				'Save And Come Back Later':function () {

					var myForm = document.forms['frm_wb_understanding_the_organisation'];
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
		$('#frm_wb_understanding_the_organisation :input[name=full_save]').val('N');
		$($('#frm_wb_understanding_the_organisation').serializeArray()).each(function(i, field)
		{
			document.forms["frm_wb_understanding_the_organisation"].elements[field.name].value = field.value.replace(/£/g, "GBP");
		});
		$.ajax({
			type:'POST',
			url:'do.php?_action=save_wb_understanding_the_organisation',
			data: $('#frm_wb_understanding_the_organisation').serialize(),
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
