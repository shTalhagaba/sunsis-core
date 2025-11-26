<?php /* @var $wb WBBusinessAndBrandReputation */ ?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/html" xmlns="http://www.w3.org/1999/html" xmlns="http://www.w3.org/1999/html"
      xmlns="http://www.w3.org/1999/html" xmlns="http://www.w3.org/1999/html">
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<title>Business and Brand Reputation workbook</title>
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

<form name="frm_wb_business_and_brand_reputation" id="frm_wb_business_and_brand_reputation" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
<input type="hidden" name="_action" value="save_wb_business_and_brand_reputation"/>
<input type="hidden" name="id" value="<?php echo $wb->id; ?>"/>
<input type="hidden" name="tr_id" value="<?php echo $wb->tr_id; ?>"/>
<input type="hidden" name="wb_status" id="wb_status" value=""/>
<input type="hidden" name="full_save" id="full_save" value="<?php echo $wb->full_save; ?>"/>
<input type="hidden" name="full_save_feedback" id="full_save_feedback" value="<?php echo $wb->full_save_feedback; ?>"/>

<div class="container-float">
<div class="wrapper" style="background-color: #ffffff;">

<header class="main-header"><span style="margin-left: 50%;"><?php echo DAO::getSingleValue($link, "SELECT title FROM student_frameworks WHERE tr_id = '{$wb->tr_id}'"); ?></span></header>

<div class="content-wrapper" style="background-color: #ffffff;">

<?php echo $wb->savers_or_sp == 'savers' ? '<section class="content-header"><h1 style="color: #0000ff;">Business and Brand Reputation</h1></section>' : '<section class="content-header"><h1>Business and Brand Reputation</h1></section>' ?>

<section class="content">

<div id="wizard">

<h1>Business and Brand Reputation</h1>

<div class="step-content">
	<?php echo $wb->getPageTopLine(); ?>
	<div style="position: absolute; top: 40%; right: 50%;" class="lead">
		<?php echo $wb->savers_or_sp == 'savers' ? '<h2 class="text-bold" style="color: #0000ff;">Business and Brand Reputation</h2>' : '<h2 class="text-bold">Business and Brand Reputation</h2>' ?>
		<p class="text-center">Module</p>
	</div>

	<?php echo $wb->getPageBottomLine(); ?>
</div>
<!--.page 1 ends-->

<h1>Business and Brand Reputation</h1>

<div class="step-content">
	<?php echo $wb->getPageTopLine(); ?>
	<div class="row"><div class="col-sm-12"><div class="pull-right"><?php echo HTML::renderWorkbookIcons('', true); ?></div></div></div>
	<div class="row">
		<div class="col-sm-4 col-sm-offset-4">
			<div class="bg-blue text-center" style="padding: 15px;">
				<h3>Business</h3>
				<p class="text-bold">The activity of buying and selling goods and services</p>
				<p class="text-bold">A particular company that buys and sells goods and services</p>
			</div>
		</div>
	</div>
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

	<p><br></p>
	<div class="row"><div class="col-sm-12"><div class="pull-right"><?php echo HTML::renderWorkbookIcons('', true); ?></div></div></div>
	<?php echo $wb->getPageBottomLine(); ?>
</div>
<!--.page 2 ends-->

<h1>Business and Brand Reputation</h1>

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
		<div class="col-sm-12 text-center"><hr></div>
		<?php if($wb->savers_or_sp == 'savers') { ?>
		<div class="col-sm-12 text-center">
			<p class="text-bold"><img src="module_eportfolio/assets/images/wb2_img1.png" /> &nbsp;
				For your first activity, do some research and see if you can find out what Savers Vision, Mission and Values are. <br>
				Record your findings in the boxes below and then answer some questions
			</p>
		</div>
		<?php } else {?>
		<div class="col-sm-12 text-center">
			<p class="text-bold"><img src="module_eportfolio/assets/images/wb2_img1.png" /> &nbsp;
				For your next activity, log on to the Hub and watch the <?php echo ucfirst($wb->savers_or_sp); ?> Vision and Mission video. <br>
				On the Hub go to: <?php echo ucfirst($wb->savers_or_sp); ?> News >> Working Here >> Our Vision, Mission and Values Follow the onscreen instructions to watch the video. <br>
				Write in the boxes below what the Vision, Mission and Core Values are and then answer some questions
			</p>
		</div>
		<?php } ?>
		<?php $VisionMissionCoreValues = $answers->VisionMissionCoreValues; ?>
		<div class="col-sm-12 text-center">
			<p class="text-bold" style="font-size: 17.5px;">Vision</p>
			<p <?php echo $feedback->VisionMissionCoreValues->Status->__toString() == 'A'?'style="pointer-events:none;"':''; ?>><textarea name="Vision" style="width: 100%;" rows="7"><?php echo $VisionMissionCoreValues->Vision->__toString(); ?></textarea></p>
			<p><hr></p>
		</div>
		<div class="col-sm-12 text-center">
			<p class="text-bold" style="font-size: 17.5px;">Mission</p>
			<p <?php echo $feedback->VisionMissionCoreValues->Status->__toString() == 'A'?'style="pointer-events:none;"':''; ?>><textarea name="Mission" style="width: 100%;" rows="7"><?php echo $VisionMissionCoreValues->Mission->__toString(); ?></textarea> </p>
			<p><hr></p>
		</div>
		<div class="col-sm-12 text-center">
			<p class="text-bold" style="font-size: 17.5px;">Values</p>
			<p <?php echo $feedback->VisionMissionCoreValues->Status->__toString() == 'A'?'style="pointer-events:none;"':''; ?>><textarea name="CoreValues" style="width: 100%;" rows="7"><?php echo $VisionMissionCoreValues->CoreValues->__toString(); ?></textarea> </p>
			<p><hr></p>
		</div>
		<div class="col-sm-6 text-center">
			<p class="text-bold"><img src="module_eportfolio/assets/images/wb2_img1.png" /> &nbsp;What do you think are the benefits of a company having a Vision, Mission and Values?</p>
			<p <?php echo $feedback->VisionMissionCoreValues->Status->__toString() == 'A'?'style="pointer-events:none;"':''; ?>><textarea name="Benefits" style="width: 100%;" rows="7"><?php echo $VisionMissionCoreValues->Benefits->__toString(); ?></textarea> </p>
		</div>
		<div class="col-sm-6 text-center">
			<p class="text-bold"><img src="module_eportfolio/assets/images/wb2_img1.png" /> &nbsp;How do you think it impacts your role?</p>
			<p <?php echo $feedback->VisionMissionCoreValues->Status->__toString() == 'A'?'style="pointer-events:none;"':''; ?>><textarea name="ImpactOnRole" style="width: 100%;" rows="7"><?php echo $VisionMissionCoreValues->ImpactOnRole->__toString(); ?></textarea> </p>
		</div>
		<?php if($wb->savers_or_sp == 'savers') { ?>
		<input type="hidden" name="People" value="" />
		<?php } else {?>
		<div class="col-sm-12"><p><hr></p></div>
		<div class="col-sm-12">
			<p class="text-bold"><img src="module_eportfolio/assets/images/wb2_img1.png" /> &nbsp;Who are the people in this video? &nbsp;</p>
			<p <?php echo $feedback->VisionMissionCoreValues->Status->__toString() == 'A'?'style="pointer-events:none;"':''; ?>><textarea name="People" style="width: 100%;" rows="7"><?php echo $VisionMissionCoreValues->People->__toString(); ?></textarea> </p>
		</div>
		<?php } ?>
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
</div>
<!--.page 3 ends-->

<h1>Business and Brand Reputation</h1>

<div class="step-content">
	<?php echo $wb->getPageTopLine(); ?>
	<div class="row"><div class="col-sm-12"><div class="pull-right"><?php echo HTML::renderWorkbookIcons('', true); ?></div></div></div>

	<div class="row">
		<div class="col-sm-12">
			<p>Brand Standards or brand guidelines are essentially a set of rules that explain how a brand works. These guidelines typically include basic information such as:</p>
			<ol style="margin-left: 15px;">
				<li>An overview of the brand's history, vision, personality and key values.</li>
				<li>Brand message or mission statement – including examples of 'tone of voice'.</li>
				<li>Logo usage – where and how to use a logo including minimum sizes, spacing and what not to do with it.</li>
				<li>Colour palette – showing primary and secondary colour palettes with colour breakdowns for print, screen and web.</li>
				<li>Type style – showing the specific font to use and details of the font family and default fonts for web use.</li>
				<li>Image style/photography – examples of image style and photographs that work with the brand.</li>
				<li>Business card and letterhead design – examples of how the logo and font are used for standard company literature.</li>
			</ol>
			<p>A small business that was just starting out may only require a few key marketing tools at this point and focusing on the above areas may be enough.</p>
		</div>
		<div class="col-sm-12"><p class="text-center text-bold">More detailed Brand Standards / guidelines would also include:</p></div>
		<?php if($wb->savers_or_sp == 'savers') { ?>
		<div class="col-sm-3"><img class="img-responsive" src="images/logos/Savers.png" /></div>
		<?php } else {?>
		<div class="col-sm-3"><img class="img-responsive" src="images/logos/superdrug.bmp" /></div>
		<?php } ?>
		<div class="col-sm-6">
			<div class="bg-red text-center" style="border: #000000;">
				<p>Design layouts and grids</p>
				<p>Social media profile page applications</p>
				<p>Brochure/flyer layout options</p>
				<p>Website layout</p>
				<p>Signage specifications</p>
				<p>Advertising treatments</p>
				<p>Merchandising applications</p>
				<p>Copywriting style (a.k.a. “tone of voice”)</p>
				<p>Editorial guidelines</p>
			</div>
		</div>
		<div class="col-sm-3"><img class="img-responsive" src="module_eportfolio/assets/images/wbr07_pg4_img1.png" /></div>
		<div class="col-sm-12">
			<p>Brand standards / guidelines should be flexible enough for designers to be creative, but rigid enough to keep the brand easily recognisable. Consistency is important especially if the brand is required to extend across multiple media platforms.</p>
			<p>Ideally, brand standards should do the double duty of creating awareness of a brand and differentiating the brand from any competition.</p>
			<p>Every business has a brand even if it doesn't act like it. The brand is reflected by the look, feel and tone of voice of their website and marketing material. It can also be reflected in the way staff communicate and deal with customers.</p>
			<p>For a business it is crucial that messages are consistent at every point that customers come in to contact with them, whether that is by talking to one of the team over the telephone or receiving an email or tweet from them.</p>
			<p>Brand standards provide company employees with focus and direction to ensure that they mirror the messages in their day to day work.</p>
		</div>
	</div>

	<p><br></p>
	<div class="row"><div class="col-sm-12"><div class="pull-right"><?php echo HTML::renderWorkbookIcons('', true); ?></div></div></div>
	<?php echo $wb->getPageBottomLine(); ?>
</div>
<!--.page 4 ends-->

<h1>Business and Brand Reputation</h1>

<div class="step-content">
	<?php echo $wb->getPageTopLine(); ?>
	<div class="row"><div class="col-sm-12"><div class="pull-right">
		<?php
		if($feedback->Logos->Status->__toString() == 'NA')
			echo HTML::renderWorkbookIcons('Logos', false, 'btn-danger');
		else
			echo HTML::renderWorkbookIcons('Logos', false, 'btn-success');
		?>
	</div></div></div>

	<div class="row">
		<div class="col-sm-12">
			<img class="img-responsive" src="module_eportfolio/assets/images/wbr07_pg5_img1.png" />
			<p><br></p>
			<p>Above are some well known brands and logos. Within the logo they all tell you who they are for but you will ultimately recognise them because of the specific colours and font being used. If you were to see any in a different size or style you would question its authenticity.</p>
		</div>
		<div class="col-sm-12 text-center">
			<p class="text-bold"><img src="module_eportfolio/assets/images/wb2_img1.png" /> &nbsp; Below are some well known brand logos. Can you identify whose they are?</p>
		</div>
		<div class="col-sm-12 table-responsive">
			<table class="table">
				<tr>
					<td><img class="img-responsive" src="module_eportfolio/assets/images/wbr07_pg5_img2.png" /></td>
					<td <?php echo $feedback->Logos->Status->__toString() == 'A'?'style="pointer-events:none;"':''; ?>><textarea name="L1" style="width: 100%;"><?php echo $answers->Logos->L1->__toString(); ?></textarea> </td>
					<td><img class="img-responsive" src="module_eportfolio/assets/images/wbr07_pg5_img3.png" /></td>
					<td <?php echo $feedback->Logos->Status->__toString() == 'A'?'style="pointer-events:none;"':''; ?>><textarea name="L2" style="width: 100%;"><?php echo $answers->Logos->L2->__toString(); ?></textarea> </td>
				</tr>
				<tr>
					<td><img class="img-responsive" src="module_eportfolio/assets/images/wbr07_pg5_img4.png" /></td>
					<td <?php echo $feedback->Logos->Status->__toString() == 'A'?'style="pointer-events:none;"':''; ?>><textarea name="L3" style="width: 100%;"><?php echo $answers->Logos->L3->__toString(); ?></textarea> </td>
					<td><img class="img-responsive" src="module_eportfolio/assets/images/wbr07_pg5_img5.png" /></td>
					<td <?php echo $feedback->Logos->Status->__toString() == 'A'?'style="pointer-events:none;"':''; ?>><textarea name="L4" style="width: 100%;"><?php echo $answers->Logos->L4->__toString(); ?></textarea> </td>
				</tr>
			</table>
		</div>
	</div>

	<?php if($wb->id != '' && $_SESSION['user']->type == User::TYPE_ASSESSOR && ($wb->wb_status == Workbook::STATUS_LEARNER_COMPLETED || $wb->wb_status == Workbook::STATUS_BEING_CHECKED || $wb->wb_status == Workbook::STATUS_IV_REJECTED)) {?>
	<div class="row well">
		<div class="col-sm-12">
			<div class="box box-success box-solid">
				<div class="box-header with-border"><h3 class="box-title">Assessor Feedback</h3></div>
				<div class="box-body assessorFeedback" <?php echo $feedback->Logos->Status->__toString() == 'A'?'style="pointer-events:none;"':''; ?>>
					<div class="form-group">
						<label for="status_Logos" class="col-sm-12 control-label">Status:</label>
						<div class="col-sm-12">
							<?php
							echo HTML::selectChosen('status_Logos', $answer_status, $feedback->Logos->Status->__toString() == 'A'?$feedback->Logos->Status->__toString():'', false, true); ?>
						</div>
					</div>
					<div class="form-group">
						<label for="comments_Logos" class="col-sm-12 control-label">Comments:</label>
						<div class="col-sm-12">
							<textarea name="comments_Logos" rows="7" style="width: 100%;"><?php echo $feedback->Logos->Comments->__toString(); ?></textarea>
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
		if($feedback->Logos->Status->__toString() == 'NA')
			echo HTML::renderWorkbookIcons('Logos', false, 'btn-danger');
		else
			echo HTML::renderWorkbookIcons('Logos', false, 'btn-success');
		?>
	</div></div></div>
	<?php echo $wb->getPageBottomLine(); ?>
</div>
<!--.page 5 ends-->

<h1>Business and Brand Reputation</h1>

<div class="step-content">
	<?php echo $wb->getPageTopLine(); ?>
	<div class="row"><div class="col-sm-12"><div class="pull-right">
		<?php
		if($feedback->OwnBrandStandards->Status->__toString() == 'NA')
			echo HTML::renderWorkbookIcons('OwnBrandStandards', false, 'btn-danger');
		else
			echo HTML::renderWorkbookIcons('OwnBrandStandards', false, 'btn-success');
		?>
	</div></div></div>

	<div class="row">
		<div class="col-sm-12">
			<p class="text-bold"><img src="module_eportfolio/assets/images/wb2_img1.png" /> &nbsp;Think about <?php echo ucfirst($wb->savers_or_sp); ?>'s own brand standards.</p>
			<p class="text-bold">What do you think they are?</p>
			<p <?php echo $feedback->OwnBrandStandards->Status->__toString() == 'A'?'style="pointer-events:none;"':''; ?>><textarea name="What" style="width: 100%;" rows="4"><?php echo $answers->OwnBrandStandards->What->__toString(); ?></textarea> </p>
		</div>
		<div class="col-sm-12">
			<p class="text-bold"><img src="module_eportfolio/assets/images/wb2_img1.png" /> &nbsp;Where you see our brand being communicated on a daily basis?</p>
			<p <?php echo $feedback->OwnBrandStandards->Status->__toString() == 'A'?'style="pointer-events:none;"':''; ?>><textarea name="Where" style="width: 100%;" rows="4"><?php echo $answers->OwnBrandStandards->Where->__toString(); ?></textarea> </p>
		</div>
		<?php if($wb->savers_or_sp == 'savers') { ?>
		<input type="hidden" name="HubNotes" value="" />
		<?php } else { ?>
		<div class="col-sm-12">
			<p class="text-bold"><img src="module_eportfolio/assets/images/wb2_img1.png" /> &nbsp; Log on to the Hub, locate and read the Brand tone of voice guidance. You will find it underneath the Vision, Mission and Values information which you read and watched earlier in this section.</p>
			<p class="text-bold">On the Hub go to: <?php echo ucfirst($wb->savers_or_sp); ?> News => Working Here => Brand Tone of Voice</p>
			<p class="text-bold">Make some notes below to show your understanding of them and their purpose. Discuss with your assessor.</p>
			<p <?php echo $feedback->OwnBrandStandards->Status->__toString() == 'A'?'style="pointer-events:none;"':''; ?>><textarea name="HubNotes" style="width: 100%;" rows="5"><?php echo $answers->OwnBrandStandards->HubNotes->__toString(); ?></textarea> </p>
		</div>
		<?php } ?>
		<div class="col-sm-12">
			<p class="text-bold"><img src="module_eportfolio/assets/images/wb2_img1.png" /> &nbsp; Finally, how do you think you personally, positively present the business brand standards in all communications with customers? Make some notes below:</p>
			<p <?php echo $feedback->OwnBrandStandards->Status->__toString() == 'A'?'style="pointer-events:none;"':''; ?>><textarea name="HowYourPresentBrand" style="width: 100%;" rows="8"><?php echo $answers->OwnBrandStandards->HowYourPresentBrand->__toString(); ?></textarea> </p>
		</div>
	</div>

	<?php if($wb->id != '' && $_SESSION['user']->type == User::TYPE_ASSESSOR && ($wb->wb_status == Workbook::STATUS_LEARNER_COMPLETED || $wb->wb_status == Workbook::STATUS_BEING_CHECKED || $wb->wb_status == Workbook::STATUS_IV_REJECTED)) {?>
	<div class="row well">
		<div class="col-sm-12">
			<div class="box box-success box-solid">
				<div class="box-header with-border"><h3 class="box-title">Assessor Feedback</h3></div>
				<div class="box-body assessorFeedback" <?php echo $feedback->OwnBrandStandards->Status->__toString() == 'A'?'style="pointer-events:none;"':''; ?>>
					<div class="form-group">
						<label for="status_OwnBrandStandards" class="col-sm-12 control-label">Status:</label>
						<div class="col-sm-12">
							<?php
							echo HTML::selectChosen('status_OwnBrandStandards', $answer_status, $feedback->OwnBrandStandards->Status->__toString() == 'A'?$feedback->OwnBrandStandards->Status->__toString():'', false, true); ?>
						</div>
					</div>
					<div class="form-group">
						<label for="comments_OwnBrandStandards" class="col-sm-12 control-label">Comments:</label>
						<div class="col-sm-12">
							<textarea name="comments_OwnBrandStandards" rows="7" style="width: 100%;"><?php echo $feedback->OwnBrandStandards->Comments->__toString(); ?></textarea>
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
		if($feedback->OwnBrandStandards->Status->__toString() == 'NA')
			echo HTML::renderWorkbookIcons('OwnBrandStandards', false, 'btn-danger');
		else
			echo HTML::renderWorkbookIcons('OwnBrandStandards', false, 'btn-success');
		?>
	</div></div></div>

	<?php echo $wb->getPageBottomLine(); ?>
</div>
<!--.page 6 ends-->

<h1>Business and Brand Reputation</h1>

<div class="step-content">
	<?php echo $wb->getPageTopLine(); ?>
	<div class="row"><div class="col-sm-12"><div class="pull-right">
		<?php
		if($feedback->CorporateObjectives->Status->__toString() == 'NA')
			echo HTML::renderWorkbookIcons('CorporateObjectives', false, 'btn-danger');
		else
			echo HTML::renderWorkbookIcons('CorporateObjectives', false, 'btn-success');
		?>
	</div></div></div>

	<div class="row">
		<div class="col-sm-12">
			<p class="text-bold">The purpose of setting objectives and why they are important for businesses to be successful</p>
		</div>
	</div>
	<div class="row">
		<div class="col-sm-4 col-sm-offset-4">
			<div class="bg-light-blue text-center" style="padding: 15px;">
				<h5>Corporate objectives</h5>
				<p class="text-center">Well-defined and realistic goals set by a company that often influences its internal strategic decisions. Most corporate objective targets used by a business will specify the time frame anticipated for their achievement and how the company's success in doing so is to be assessed</p>
			</div>
		</div>
	</div>
	<div class="row">
		<div class="col-sm-12">
			<p>Aims and objectives are the 'ends' that an organisation seeks to achieve. It then has to decide the means it will use to achieve those ends draw up a plan and devise a strategy.</p>
			<p>By setting aims and objectives, companies give themselves a sense of purpose and direction. This provides a framework around which to create their plans. With an overall plan in place, a company can set particular targets and monitor its progress towards reaching them.</p>
		</div>
		<div class="col-sm-6">
			<p>In <?php echo ucfirst($wb->savers_or_sp); ?> we have many aims and objectives to ensure we continue to be a successful and profitable company.</p>
			<p>Examples of a corporate objective for <?php echo ucfirst($wb->savers_or_sp); ?> are:</p>
			<ul style="margin-left: 15px;">
				<li>Company sales targets over a 12 month period</li>
				<li>Beauty card registrations over a 12 month period</li>
			</ul>
			<p>Both are related to what you do in store however there are many other areas of the business that are also responsible for meeting these objectives.</p>
			<p>To hit a sales target you need the products in store to sell to the customers. Who decides what products we are going to sell?</p>
		</div>
		<div class="col-sm-6">
			<p>How do they decide how much we are going to sell them for? How do the products get to you in store? How do the customers know what products we are selling and what offers we have?</p>
			<p>As you can see there are many departments and people within the company who are dedicated and focused on achieving the corporate objective of hitting our sales target. It has to be broken down in to smaller more manageable targets for different departments or individuals to support and achieve.</p>
			<?php if($wb->savers_or_sp == 'savers') { ?>
			<p>You can find out more about what is going on in the business and in stores by speaking to your manager and area manager.</p>
			<?php } else {?>
			<p>You can find out more about what is going on in the business and in stores by logging on to the Hub.</p>
			<p>On the Hub go to: Superdrug News Highlights or Read all about it</p>
			<?php } ?>
		</div>
		<div class="col-sm-12">
			<p class="text-bold"><img src="module_eportfolio/assets/images/wb2_img1.png" /> &nbsp; What would happen if a company didn't have any objectives?</p>
			<p <?php echo $feedback->CorporateObjectives->Status->__toString() == 'A'?'style="pointer-events:none;"':''; ?>><textarea name="ImpactOfNotHavingObjectives" style="width: 100%;" rows="8"><?php echo $answers->CorporateObjectives->ImpactOfNotHavingObjectives->__toString(); ?></textarea> </p>
		</div>
	</div>

	<div class="row">
		<div class="col-sm-5"><img class="img-responsive" src="module_eportfolio/assets/images/wbr07_pg8_img1.png" /> </div>
		<div class="col-sm-7">
			<p class="text-bold">How objectives relate to own role in the business</p>
			<p>As previously mentioned we need to have objectives to ensure we continue to have purpose and direction and to continue to be successful as a company.</p>
			<p>Everyone within the company has objectives that they need to work towards to achieve</p>
		</div>
		<div class="col-sm-12">
			<p class="text-bold"><img src="module_eportfolio/assets/images/wb2_img1.png" /> &nbsp; Think about some of the objectives you have in store on a daily, weekly or monthly basis. You may need to speak to your manager or mentor. List them in the relevant box.</p>
		</div>
	</div>
	<div class="row" <?php echo $feedback->CorporateObjectives->Status->__toString() == 'A'?'style="pointer-events:none;"':''; ?>>
		<div class="col-sm-3"><p class="text-bold">Daily:</p> </div>
		<div class="col-sm-9"><textarea name="Daily" style="width: 100%;" rows="8"><?php echo $answers->CorporateObjectives->Daily->__toString(); ?></textarea></div>
		<div class="col-sm-3"><p class="text-bold">Weekly:</p> </div>
		<div class="col-sm-9"><textarea name="Weekly" style="width: 100%;" rows="8"><?php echo $answers->CorporateObjectives->Weekly->__toString(); ?></textarea></div>
		<div class="col-sm-3"><p class="text-bold">Monthly:</p> </div>
		<div class="col-sm-9"><textarea name="Monthly" style="width: 100%;" rows="8"><?php echo $answers->CorporateObjectives->Monthly->__toString(); ?></textarea></div>
		<div class="col-sm-12">
			<p class="text-bold"><img src="module_eportfolio/assets/images/wb2_img1.png" /> &nbsp; What is your own role within the store to help achieve these objectives?</p>
			<p><textarea name="YourRole" style="width: 100%;" rows="8"><?php echo $answers->CorporateObjectives->YourRole->__toString(); ?></textarea> </p>
			<img class="pull-right img-responsive" src="module_eportfolio/assets/images/wbr07_pg8_img2.png" />
		</div>
	</div>

	<?php if($wb->id != '' && $_SESSION['user']->type == User::TYPE_ASSESSOR && ($wb->wb_status == Workbook::STATUS_LEARNER_COMPLETED || $wb->wb_status == Workbook::STATUS_BEING_CHECKED || $wb->wb_status == Workbook::STATUS_IV_REJECTED)) {?>
	<div class="row well">
		<div class="col-sm-12">
			<div class="box box-success box-solid">
				<div class="box-header with-border"><h3 class="box-title">Assessor Feedback</h3></div>
				<div class="box-body assessorFeedback" <?php echo $feedback->CorporateObjectives->Status->__toString() == 'A'?'style="pointer-events:none;"':''; ?>>
					<div class="form-group">
						<label for="status_CorporateObjectives" class="col-sm-12 control-label">Status:</label>
						<div class="col-sm-12">
							<?php
							echo HTML::selectChosen('status_CorporateObjectives', $answer_status, $feedback->CorporateObjectives->Status->__toString() == 'A'?$feedback->CorporateObjectives->Status->__toString():'', false, true); ?>
						</div>
					</div>
					<div class="form-group">
						<label for="comments_CorporateObjectives" class="col-sm-12 control-label">Comments:</label>
						<div class="col-sm-12">
							<textarea name="comments_CorporateObjectives" rows="7" style="width: 100%;"><?php echo $feedback->CorporateObjectives->Comments->__toString(); ?></textarea>
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
		if($feedback->CorporateObjectives->Status->__toString() == 'NA')
			echo HTML::renderWorkbookIcons('CorporateObjectives', false, 'btn-danger');
		else
			echo HTML::renderWorkbookIcons('CorporateObjectives', false, 'btn-success');
		?>
	</div></div></div>
	<?php echo $wb->getPageBottomLine(); ?>
</div>
<!--.page 7 ends-->

<h1>Business and Brand Reputation</h1>

<div class="step-content">
	<?php echo $wb->getPageTopLine(); ?>
	<div class="row"><div class="col-sm-12"><div class="pull-right">
		<?php
		if($feedback->BrandReputation->Status->__toString() == 'NA')
			echo HTML::renderWorkbookIcons('BrandReputation', false, 'btn-danger');
		else
			echo HTML::renderWorkbookIcons('BrandReputation', false, 'btn-success');
		?>
	</div></div></div>

	<div class="row">
		<div class="col-sm-12"><h4 class="text-bold text-center text-red">Brand Reputation</h4> </div>
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
					<i>- a company's brand image is the sum total of all the perceptions held by their current, past, and potential customers about the company's specific products and services.</i>
				</p>
			</blockquote>
			<p>Brand attributes include things like quality, value, variety and the shopping experience. From the potential buyer's perspective, brand is all about, “What's in it for me?”</p>
			<p class="text-bold text-purple">Reputation</p>
			<p>Reputation, on the other hand, is the entirety of the public's opinion about a company's corporate actions. Reputation attributes include community building, corporate culture, policy, job creation, and citizenship. The public asks the question, “Is this company the good guys or the bad guys?”</p>
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
			<p>It is often associated with the company name and logo. The type of language used within an organisation and the style of language used with customers' plays an important role in reinforcing and supporting a brand promise.</p>
			<p>The style of customer service language used must support the brand promise. One experience for a customer will create either a positive or negative view of the brand.</p>
		</div>
	</div>

	<div class="row">
		<div class="col-sm-12">
			<?php
			$BrandReputation = $answers->BrandReputation;
			$InWork = $BrandReputation->InWork;
			$OutsideWork = $BrandReputation->OutsideWork;
			?>
			<p class="text-bold"><img src="module_eportfolio/assets/images/wb2_img1.png" /> &nbsp;When you think about <?php echo ucfirst($wb->savers_or_sp); ?> what words would you use to describe our brand image and promise? &nbsp;</p>
			<p <?php echo $feedback->BrandReputation->Status->__toString() == 'A'?'style="pointer-events:none;"':''; ?>><textarea name="Superdrug" style="width: 100%;" rows="7"><?php echo $BrandReputation->Superdrug->__toString(); ?></textarea> </p>
		</div>
	</div>

	<p><br></p>

	<div class="row">
		<div class="col-sm-12">
			<p class="text-bold"><img src="module_eportfolio/assets/images/wb2_img1.png" /> &nbsp;Complete the tables below to show how what we do inside and outside of work can impact on a company's brand image and reputation. An example for each has been provided.&nbsp;</p>
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

				</div>
				<div class="box-body">
					<div class="table-responsive">
						<table class="table table-bordered text-center" <?php echo $feedback->BrandReputation->Status->__toString() == 'A'?'style="pointer-events:none;"':''; ?>>
							<tr class="bg-gray"><th style="width: 50%;">Positive Behaviour</th><th style="width: 50%;">Impact on business success</th></tr>
							<tr><td>Knowledgeable about products and sharing information with customers</td><td>Customer will tell friends of their good experience and return to store</td></tr>
							<?php
							for($i = 1; $i <= 2; $i++)
							{
								$key = 'Set'.$i;
								echo '<tr>';
								echo '<td><textarea name="InWorkPositiveBehaviour'.$i.'" style="width: 100%;">' . $InWork->Positive->$key->Behaviour->__toString() . '</textarea> </td>';
								echo '<td><textarea name="InWorkPositiveImpactOnBusiness'.$i.'" style="width: 100%;">' . $InWork->Positive->$key->ImpactOnBusiness->__toString() . '</textarea> </td>';
								echo '</tr>';
							}
							?>
						</table>
					</div>
					<div class="table-responsive">
						<table class="table table-bordered text-center" <?php echo $feedback->BrandReputation->Status->__toString() == 'A'?'style="pointer-events:none;"':''; ?>>
							<tr class="bg-gray"><th style="width: 50%;">Negative Behaviour</th><th style="width: 50%;">Impact on business success</th></tr>
							<tr><td>Unhappy staff using bad language on the shop floor</td><td>Customer feels uncomfortable and leaves the store. Changes their opinion of the brand</td></tr>
							<?php
							for($i = 1; $i <= 2; $i++)
							{
								$key = 'Set'.$i;
								echo '<tr>';
								echo '<td><textarea name="InWorkNegativeBehaviour'.$i.'" style="width: 100%;">' . $InWork->Negative->$key->Behaviour->__toString() . '</textarea> </td>';
								echo '<td><textarea name="InWorkNegativeImpactOnBusiness'.$i.'" style="width: 100%;">' . $InWork->Negative->$key->ImpactOnBusiness->__toString() . '</textarea> </td>';
								echo '</tr>';
							}
							?>
						</table>
					</div>
				</div>
			</div>
		</div>
	</div>

	<div class="row">
		<div class="col-sm-12">
			<div class="box box-solid box-success">
				<div class="box-header with-border">
					<h3 class="box-title">Outside of work</h3>

				</div>
				<div class="box-body">
					<div class="table-responsive">
						<table class="table table-bordered text-center" <?php echo $feedback->BrandReputation->Status->__toString() == 'A'?'style="pointer-events:none;"':''; ?>>
							<tr class="bg-gray"><th style="width: 50%;">Positive Behaviour</th><th style="width: 50%;">Impact on business success</th></tr>
							<tr><td>Telling family and friends about how much you enjoy your job</td><td>Friends and family will share your experiences with their friends and will visit <?php echo $wb->savers_or_sp == 'savers' ? 'Savers' : 'SD/SV';?></td></tr>
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
						<table class="table table-bordered text-center" <?php echo $feedback->BrandReputation->Status->__toString() == 'A'?'style="pointer-events:none;"':''; ?>>
							<tr class="bg-gray"><th style="width: 50%;">Negative Behaviour</th><th style="width: 50%;">Impact on business success</th></tr>
							<tr><td>Talking about customers negatively in public</td><td>People will overhear conversation and think we have unprofessional staff and share this with others</td></tr>
							<?php
							for($i = 1; $i <= 2; $i++)
							{
								$key = 'Set'.$i;
								echo '<tr>';
								echo '<td><textarea name="OutsideWorkNegativeBehaviour'.$i.'" style="width: 100%;">' . $OutsideWork->Negative->$key->Behaviour->__toString() . '</textarea> </td>';
								echo '<td><textarea name="OutsideWorkNegativeImpactOnBusiness'.$i.'" style="width: 100%;">' . $OutsideWork->Negative->$key->ImpactOnBusiness->__toString() . '</textarea> </td>';
								echo '</tr>';
							}
							?>
						</table>
					</div>
				</div>
			</div>
		</div>
	</div>

	<div class="row">
		<div class="col-sm-12">
			<p class="text-bold">Dealing with relevant situations that may affect brand reputation in line with company policy</p>
			<p>There are a many factors which could affect brand reputation. How a store looks, customer service and value for money are just some examples.</p>
			<p>It could also be affected by information about the company which is readily available to the general public.</p>
			<p>Does the business have an environmental policy, do they support a charity and do they pay their staff well?</p>
			<p>If the media is reporting positive stories about a company to show they have good ethics and look after their people and the planet, the consumer will feel they can be trusted and will be comfortable to shop in their stores. If, however, the media communicates negative stories or reports then this can have a negative effect.</p>
		</div>
	</div>
	<div class="row">
		<div class="col-sm-6 col-sm-offset-4">
			<div class="bg-light-blue text-center" style="padding: 15px;">
				<h5>Corporate objectives</h5>
				<p>An example of a story which made the news last year was that a restaurant owned by a celebrity chef was paying the staff less than minimum wage.</p>
				<p>It was also discovered that as well as not paying the staff correctly and fairly the celebrity chef in question was also keeping the discretionary service charge meant for his team.</p>
				<p>Making national news will not have done his brand reputation any good at all and he may have seen a drop in business.</p>
				<p>His actions to rectify the situation were to write to each employee apologising and promising them that he would pay them what they were owed.</p>
				<p>This was also reported in the national news so did he do it because he knew it was the right thing to do, or because he knew he needed to do so to protect his brand?!</p>
			</div>
		</div>
	</div>
	<div class="col-sm-12">
		<p class="text-bold"><img src="module_eportfolio/assets/images/wb2_img1.png" /> &nbsp; Based on the information you have read on brand reputation give an example of when you have dealt with a situation that may have affected <?php echo ucfirst($wb->savers_or_sp); ?>'s brand reputation.
			E.g. an occasion in store when a customer wasn't happy about something however you were able to resolve the situation to their satisfaction
		</p>
		<p <?php echo $feedback->BrandReputation->Status->__toString() == 'A'?'style="pointer-events:none;"':''; ?>><textarea name="ExampleOfDealing" style="width: 100%;" rows="8"><?php echo $BrandReputation->ExampleOfDealing->__toString(); ?></textarea> </p>
		<img class="pull-right img-responsive" src="module_eportfolio/assets/images/wbr07_pg8_img2.png" />
	</div>

	<?php if($wb->id != '' && $_SESSION['user']->type == User::TYPE_ASSESSOR && ($wb->wb_status == Workbook::STATUS_LEARNER_COMPLETED || $wb->wb_status == Workbook::STATUS_BEING_CHECKED || $wb->wb_status == Workbook::STATUS_IV_REJECTED)) {?>
	<div class="row well">
		<div class="col-sm-12">
			<div class="box box-success box-solid">
				<div class="box-header with-border"><h3 class="box-title">Assessor Feedback</h3></div>
				<div class="box-body assessorFeedback" <?php echo $feedback->BrandReputation->Status->__toString() == 'A'?'style="pointer-events:none;"':''; ?>>
					<div class="form-group">
						<label for="status_BrandReputation" class="col-sm-12 control-label">Status:</label>
						<div class="col-sm-12">
							<?php
							echo HTML::selectChosen('status_BrandReputation', $answer_status, $feedback->BrandReputation->Status->__toString() == 'A'?$feedback->BrandReputation->Status->__toString():'', false, true); ?>
						</div>
					</div>
					<div class="form-group">
						<label for="comments_BrandReputation" class="col-sm-12 control-label">Comments:</label>
						<div class="col-sm-12">
							<textarea name="comments_BrandReputation" rows="7" style="width: 100%;"><?php echo $feedback->BrandReputation->Comments->__toString(); ?></textarea>
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
		if($feedback->BrandReputation->Status->__toString() == 'NA')
			echo HTML::renderWorkbookIcons('BrandReputation', false, 'btn-danger');
		else
			echo HTML::renderWorkbookIcons('BrandReputation', false, 'btn-success');
		?>
	</div></div></div>
	<?php echo $wb->getPageBottomLine(); ?>
</div>
<!--.page 9 ends-->

<h1>Business and Brand Reputation</h1>

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
			<p class="callout callout-info text-bold">Now you have completed the section on Business and Brand reputation answer the following questions:</p>
			<p class="text-bold">Unit 2 - 1.1: Identify the vision of your organisation</p>
			<textarea name="Question1" rows="7" style="width: 100%;"><?php echo $QualificationQuestions->Question1->__toString(); ?></textarea>
			<p><br></p>
			<p class="text-bold">Unit 2 - 1.2 Identify the objectives of your organisation</p>
			<textarea name="Question2" rows="7" style="width: 100%;"><?php echo $QualificationQuestions->Question2->__toString(); ?></textarea>
			<p><br></p>
			<p class="text-bold">Unit 2 - 1.3  Identify what your organisation’s brand standards are</p>
			<textarea name="Question3" rows="7" style="width: 100%;"><?php echo $QualificationQuestions->Question3->__toString(); ?></textarea>
			<p><br></p>
			<p class="text-bold">Unit 2 - 1.4 Explain how your organisation’s vision, objectives and brand standards help to contribute to success</p>
			<textarea name="Question4" rows="7" style="width: 100%;"><?php echo $QualificationQuestions->Question4->__toString(); ?></textarea>
			<p><br></p>
			<p class="text-bold">Unit 2 - 2.1 State why brand reputation is important</p>
			<textarea name="Question5" rows="7" style="width: 100%;"><?php echo $QualificationQuestions->Question5->__toString(); ?></textarea>
			<p><br></p>
			<p class="text-bold">Unit 2 - 2.2 State why business reputation is important</p>
			<textarea name="Question6" rows="7" style="width: 100%;"><?php echo $QualificationQuestions->Question6->__toString(); ?></textarea>
			<p><br></p>
			<p class="text-bold">Unit 2 - 2.3 Identify what can affect brand and business reputation</p>
			<textarea name="Question7" rows="7" style="width: 100%;"><?php echo $QualificationQuestions->Question7->__toString(); ?></textarea>
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
	var totalActivist = 0;
	var totalReflector = 0;
	var totalTheorist = 0;
	var totalPragmatist = 0;

	$(function () {

	<?php if ($disable_answers) { ?>

		$("#frm_wb_business_and_brand_reputation :input").not(".assessorFeedback :input, #signature_text, #frm_wb_business_and_brand_reputation :input[type=hidden], .btnViewSectionHistory, .btnViewAssessorComments, #iv_status, #iv_comments, #reopen_comments").prop("disabled", true);

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
							var myForm = document.forms['frm_wb_business_and_brand_reputation'];
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
				var myForm = document.forms['frm_wb_business_and_brand_reputation'];
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

					var myForm = document.forms['frm_wb_business_and_brand_reputation'];
					myForm.elements['full_save'].value = 'Y';
					myForm.elements['full_save_feedback'].value = 'Y';
					window.onbeforeunload = null;
					myForm.submit();

				},
				'Save And Come Back Later':function () {

					var myForm = document.forms['frm_wb_business_and_brand_reputation'];
					myForm.elements['full_save'].value = 'Y';
					myForm.elements['full_save_feedback'].value = 'N';
					window.onbeforeunload = null;
					myForm.submit();

				}
			}
		});
	});

	function partialSave() {
		$('#frm_wb_business_and_brand_reputation :input[name=full_save]').val('N');
		$($('#frm_wb_business_and_brand_reputation').serializeArray()).each(function(i, field)
		{
			document.forms["frm_wb_business_and_brand_reputation"].elements[field.name].value = field.value.replace(/£/g, "GBP");
		});
		$.ajax({
			type:'POST',
			url:'do.php?_action=save_wb_business_and_brand_reputation',
			data:$('#frm_wb_business_and_brand_reputation').serialize(),
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
