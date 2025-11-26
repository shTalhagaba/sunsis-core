<?php /* @var $wb WBCommunication */ ?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<title>Communication workbook</title>
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

<form name="frm_wb_communication" id="frm_wb_communication" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
<input type="hidden" name="_action" value="save_wb_communication" />
<input type="hidden" name="id" value="<?php echo $wb->id; ?>" />
<input type="hidden" name="tr_id" value="<?php echo $wb->tr_id; ?>" />
<input type="hidden" name="wb_status" id="wb_status" value="" />
<input type="hidden" name="full_save" id="full_save" value="<?php echo $wb->full_save; ?>" />
<input type="hidden" name="full_save_feedback" id="full_save_feedback" value="<?php echo $wb->full_save_feedback; ?>" />
<div class="container-float">
<div class="wrapper" style="background-color: #ffffff;">

<header class="main-header"><span style="margin-left: 50%;"><?php echo DAO::getSingleValue($link, "SELECT title FROM student_frameworks WHERE tr_id = '{$wb->tr_id}'"); ?></span></header>

<div class="content-wrapper" style="background-color: #ffffff;">

<?php echo $wb->savers_or_sp == 'savers' ? '<section class="content-header"><h1 style="color: #0000ff;">Communication</h1></section>' : '<section class="content-header"><h1>Communication</h1></section>' ?>

<section class="content">

	<div id="wizard">

	<h1>Communication</h1>
	<div class="step-content">
		<?php echo $wb->getPageTopLine(); ?>
		<div style="position: absolute; top: 40%; right: 50%;" class="lead">
			<?php echo $wb->savers_or_sp == 'savers' ? '<h2 class="text-bold" style="color: #0000ff;">Communication</h2>' : '<h2 class="text-bold">Communication</h2>' ?>
			<p class="text-center" >Module <?php if($wb->getQAN($link) ==  Workbook::CS_QAN){?>7<?php }?></p>
		</div>
		<?php echo $wb->getPageBottomLine(); ?>
	</div> <!--.page 1 ends-->

	<?php if($wb->getQAN($link) ==  Workbook::CS_QAN){?>
	<h1>Communication</h1>
	<div class="step-content">
		<?php echo $wb->getPageTopLine(); ?>

		<div class="row"><div class="col-sm-12"><div class="pull-right"><?php echo HTML::renderWorkbookIcons('', true); ?></div></div></div>

		<div class="row">
			<div class="col-sm-12">

				<p>This module is about communication. The definition of communication is the imparting or exchanging of information by speaking, writing or using some other medium. Good communication is essential between all team members within stores as well as the wider organisation to ensure a successful business.  Communication is also the key to excellent customer service when used effectively.</p>
				<p>In this module you will look at:</p>
				<ul style="margin-left: 15px; margin-bottom: 15px;">
					<li>The Importance of good communication</li>
					<li>The effects of negative communication in the workplace</li>
					<li>The impact of body language in communication</li>
					<li>Body language skills</li>
					<li>How to acknowledge effectively</li>
					<li>The importance of using different methods of communication</li>
				</ul>
			</div>
			<div class="col-sm-12">
				<img src="module_eportfolio/assets/images/wb7_pg1_img1.png" />
			</div>
		</div>

		<p><br></p>

		<div class="row"><div class="col-sm-12"><div class="pull-right"><?php echo HTML::renderWorkbookIcons('', true); ?></div></div></div>

		<?php echo $wb->getPageBottomLine(); ?>
	</div> <!--.page 2 ends-->
	<?php } ?>

	<h1>Communication</h1>
	<div class="step-content">
		<?php echo $wb->getPageTopLine(); ?>

		<div class="row"><div class="col-sm-12"><div class="pull-right"><?php echo HTML::renderWorkbookIcons('', true); ?></div></div></div>

		<div class="row" style="text-align: center">

			<div class="col-sm-12">

				<p style="border: 3px solid red; padding: 10px;"><b>Communication</b> <i>is the imparting or exchanging of information by speaking, writing, or using some other medium.</i></p>
				<p style="letter-spacing: 2px; border: 1px solid #000000; padding: 10px; background-color: #ff69b4; color: #ffffff; font-weight: bolder;">Why is excellent communication so important to businesses?</p>
				<p><span class="glyphicon glyphicon-arrow-down"></span></p>
				<p style="border: 3px solid red; padding: 10px; text-align: center"><b>Good communication is essential for building a team that will make a business a success.</b></p>
			</div>

		</div>

		<div class="row">

			<div class="col-sm-6">
				<div class="callout callout-info lead">
					<h4>Communication</h4>
					<p>is important if members of a company are to work as a team towards the same goal.</p>
				</div>
				<p class="well well-sm">Interaction amongst employees builds an efficient team.</p>
				<p class="well well-sm">In order to have a good team, a good leader is essential. The leader must communicate well with every member that is involved in the business in order to convey to each employee their jobs and expectations.</p>
				<p class="well well-sm">He or she must be a motivating person who encourages people to work hard and to have a mindset of achieving various goals.</p>
				<p class="well well-sm">A leader that communicates well creates a team that performs well in all departments.</p>
				<p class="well well-sm">Good communication also prevents misunderstandings among people in the workplace.</p>
			</div>

			<div class="col-sm-6">
				<p class="well well-sm">Any misunderstandings that do happen will be resolved in an amicable manner. It also means that unnecessary friction is avoided.</p>
				<p class="well well-sm">Employees therefore will be able to concentrate better on their work.</p>
				<p class="well well-sm">A company that has good communication between the top management and the junior employees creates an inclusive atmosphere. The junior employees will feel included in the company, and as a result, will be encouraged to work harder.</p>
				<p class="well well-sm">It is not easy to work in an environment that you might feel out of place in. When members of a company communicate efficiently, a positive atmosphere is created.</p>
				<p class="well well-sm">If there is a positive atmosphere in the workplace, internal problems are sorted out easily and quickly.</p>
			</div>

		</div>

		<p><br></p>

		<div class="row"><div class="col-sm-12"><div class="pull-right"><?php echo HTML::renderWorkbookIcons('', true); ?></div></div></div>

		<?php echo $wb->getPageBottomLine(); ?>
	</div> <!--.page 3 ends-->

	<h1>Communication</h1>
	<div class="step-content">
		<?php echo $wb->getPageTopLine(); ?>

		<div class="row"><div class="col-sm-12"><div class="pull-right">
			<?php
			if($feedback->PoorCommunicationExamples->Status->__toString() == 'NA')
				echo HTML::renderWorkbookIcons('PoorCommunicationExamples', false, 'btn-danger');
			else
				echo HTML::renderWorkbookIcons('PoorCommunicationExamples', false, 'btn-success');
			?>
		</div></div></div>

		<div class="row">

			<div class="col-sm-12">

				<p><b>Good Communication</b> will show improvements in the quality of service between the company and its customers.</p>
				<p>If the company and its employees interact well with their customers and deal with their needs promptly, the customers are likely to continue doing business with them.</p>
				<p>The company will also be in a position to provide better service. This is because as you communicate with the customers, you will be able to figure out exactly what they want from you.</p>
				<p>This way, you can make the necessary improvements to products or services that the customers require.</p>
				<p>People who run successful businesses know that the customer always comes first. It is the customer who brings in the profits. That is why it is important for the company to interact well with each other and the customers in order to reach business goals.</p>
				<div class="callout callout-info lead">
					<p class="text-center"><b>Good business communication is vital if you want your company to be a success.</b></p>
				</div>
				<blockquote style="border: 3px dashed #ff69b4">
					<p>"A customer is the most important visitor on our premises; he is not dependent on us. We are dependent on him. He is not an interruption in our work. He is the purpose of it. He is
						not an outsider in our business. He is part of it. We are not doing him a favour by serving him. He is doing us a favour by giving us an opportunity to do so."
						<small><cite title="Mahatma Gandhi">Mahatma Gandhi</cite></small>
					</p>
				</blockquote>
				<p style="letter-spacing: 2px; border: 1px solid #000000; padding: 10px; background-color: #ff69b4; color: #ffffff; font-weight: bolder; text-align: center">What is the impact on businesses of poor or inappropriate communication?</p>
			</div>

		</div>

		<div class="row">

			<div class="col-sm-6">
				<p>There are many opportunities for poor communication to take place in any work environment.</p>
				<p><b>For example:</b></p>
				<p>A store manager doesn't receive a phone message from the area manager which means she doesn't do an important piece of work on time.</p>
			</div>

			<div class="col-sm-6">
				<p>A click and collect order can't be found when the customer comes to collect it because the new team member hasn't been told the process to follow.</p>
				<p>A supervisor didn't know what to do when they were running the shift because they couldn't read the manager's writing in the hand over diary.</p>
			</div>

		</div>

		<div class="row" <?php echo $feedback->PoorCommunicationExamples->Status->__toString() == 'A'?'style="pointer-events:none;"':''; ?>>

			<div class="col-sm-12">
				<p class="text-bold"><img src="module_eportfolio/assets/images/wb2_img1.png" /> &nbsp;Can you think of any of your own examples of poor communication that may have happened in your store recently?</p>
				<p><textarea name="PoorCommunicationExamples" style="width: 100%;" rows="10"><?php echo $answers->PoorCommunicationExamples->__toString(); ?></textarea> </p>
			</div>

		</div>

		<?php if($wb->id != '' && $_SESSION['user']->type == User::TYPE_ASSESSOR && ($wb->wb_status == Workbook::STATUS_LEARNER_COMPLETED || $wb->wb_status == Workbook::STATUS_BEING_CHECKED || $wb->wb_status == Workbook::STATUS_IV_REJECTED)) {?>
		<div class="row well">
			<div class="col-sm-12">
				<div class="box box-success box-solid">
					<div class="box-header with-border"><h3 class="box-title">Assessor Feedback</h3></div>
					<div class="box-body assessorFeedback" <?php echo $feedback->PoorCommunicationExamples->Status->__toString() == 'A'?'style="pointer-events:none;"':''; ?>>
						<div class="form-group">
							<label for="status_PoorCommunicationExamples" class="col-sm-12 control-label">Status:</label>
							<div class="col-sm-12">
								<?php echo HTML::selectChosen('status_PoorCommunicationExamples', $answer_status, $feedback->PoorCommunicationExamples->Status->__toString() == 'A'?$feedback->PoorCommunicationExamples->Status->__toString():'', false, true); ?>
							</div>
						</div>
						<div class="form-group">
							<label for="comments_PoorCommunicationExamples" class="col-sm-12 control-label">Comments:</label>
							<div class="col-sm-12">
								<textarea name="comments_PoorCommunicationExamples" rows="7" style="width: 100%;"><?php echo $feedback->PoorCommunicationExamples->Comments->__toString(); ?></textarea>
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
			if($feedback->PoorCommunicationExamples->Status->__toString() == 'NA')
				echo HTML::renderWorkbookIcons('PoorCommunicationExamples', false, 'btn-danger');
			else
				echo HTML::renderWorkbookIcons('PoorCommunicationExamples', false, 'btn-success');
			?>
		</div></div></div>

		<?php echo $wb->getPageBottomLine(); ?>
	</div> <!--.page 4 ends-->

	<h1>Communication</h1>
	<div class="step-content">
		<?php echo $wb->getPageTopLine(); ?>

		<div class="row"><div class="col-sm-12"><div class="pull-right">
			<?php
			if($feedback->ExampleToOvercomeNegativeCommunication->Status->__toString() == 'NA')
				echo HTML::renderWorkbookIcons('ExampleToOvercomeNegativeCommunication', false, 'btn-danger');
			else
				echo HTML::renderWorkbookIcons('ExampleToOvercomeNegativeCommunication', false, 'btn-success');
			?>
		</div></div></div>

		<div class="row">
			<div class="col-sm-12">

				<p class="text-bold">Effects of Negative Communication in the Workplace</p>
			</div>
		</div>

		<div class="row">

			<div class="col-sm-6">
				<p>It doesn't matter how big or small the lack of communication is poor communication will strain the productivity of the company. Awareness of problems in communication is the first step toward solving them.</p>
				<p>If communication skills are poor, employees lack enthusiasm, motivation, creativity and inspiration in doing their jobs and will question why they are doing them. If employees are given
					unclear instructions it can lead to confusion and can perhaps result in them becoming demoralised.</p>

			</div>

			<div class="col-sm-6 pull-right">
				<img src="module_eportfolio/assets/images/wb7_pg5_img1.png" />
			</div>

		</div>

		<div class="row">

			<div class="col-sm-12">
				<p>Negative communication includes rumours, misinformation, misinterpretation, incomplete information and employee slander (making false and damaging statements about someone).</p>
				<p>While some are done on purpose such as employee slander, other negative communication occurs without any intent of malice, such as unknowingly relaying incomplete information.</p>
				<p>One side effect to negative communication is workplace conflict. When one employee spreads false rumours about another staff member, the result can often be a verbal or physical altercation between the two parties.</p>
				<p>Negative communication, whether intended or not, can have an effect on staff morale. Persistent intended negative communication can add stress to the workplace that makes it difficult to develop a productive work environment.</p>
				<p>Unintentional negative communication can be forgiven up to a point, but when it becomes a regular occurrence, it can lead to a drop in employee confidence in the company.</p>
				<p>An atmosphere of negative communication can be extremely difficult for a workplace to recover from. Intentional and unintentional negative communication erodes trust, to the point where information must be checked several times before it is acted upon.</p>
				<p></p>
			</div>

		</div>

		<div class="row" <?php echo $feedback->ExampleToOvercomeNegativeCommunication->Status->__toString() == 'A'?'style="pointer-events:none;"':''; ?>>

			<div class="col-sm-12">
				<p class="text-bold"><img src="module_eportfolio/assets/images/wb2_img1.png" /> &nbsp;Give an example of how you think you would overcome negative communication</p>
				<p><textarea name="ExampleToOvercomeNegativeCommunication" style="width: 100%;" rows="10"><?php echo $answers->ExampleToOvercomeNegativeCommunication->__toString(); ?></textarea> </p>
			</div>
			<div class="col-sm-12">
				<img class="pull-right" src="module_eportfolio/assets/images/obs1.png" />
			</div>

		</div>

		<?php if($wb->id != '' && $_SESSION['user']->type == User::TYPE_ASSESSOR && ($wb->wb_status == Workbook::STATUS_LEARNER_COMPLETED || $wb->wb_status == Workbook::STATUS_BEING_CHECKED || $wb->wb_status == Workbook::STATUS_IV_REJECTED)) {?>
		<div class="row well">
			<div class="col-sm-12">
				<div class="box box-success box-solid">
					<div class="box-header with-border"><h3 class="box-title">Assessor Feedback</h3></div>
					<div class="box-body assessorFeedback" <?php echo $feedback->ExampleToOvercomeNegativeCommunication->Status->__toString() == 'A'?'style="pointer-events:none;"':''; ?>>
						<div class="form-group">
							<label for="status_ExampleToOvercomeNegativeCommunication" class="col-sm-12 control-label">Status:</label>
							<div class="col-sm-12">
								<?php echo HTML::selectChosen('status_ExampleToOvercomeNegativeCommunication', $answer_status, $feedback->ExampleToOvercomeNegativeCommunication->Status->__toString() == 'A'?$feedback->ExampleToOvercomeNegativeCommunication->Status->__toString():'', false, true); ?>
							</div>
						</div>
						<div class="form-group">
							<label for="comments_ExampleToOvercomeNegativeCommunication" class="col-sm-12 control-label">Comments:</label>
							<div class="col-sm-12">
								<textarea name="comments_ExampleToOvercomeNegativeCommunication" rows="7" style="width: 100%;"><?php echo $feedback->ExampleToOvercomeNegativeCommunication->Comments->__toString(); ?></textarea>
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
			if($feedback->ExampleToOvercomeNegativeCommunication->Status->__toString() == 'NA')
				echo HTML::renderWorkbookIcons('ExampleToOvercomeNegativeCommunication', false, 'btn-danger');
			else
				echo HTML::renderWorkbookIcons('ExampleToOvercomeNegativeCommunication', false, 'btn-success');
			?>
		</div></div></div>

		<?php echo $wb->getPageBottomLine(); ?>
	</div> <!--.page 5 ends-->

	<h1>Communication</h1>
	<div class="step-content">
		<?php echo $wb->getPageTopLine(); ?>

		<div class="row"><div class="col-sm-12"><div class="pull-right"><?php echo HTML::renderWorkbookIcons('', true); ?></div></div></div>

		<div class="row">
			<div class="col-sm-12">

				<p class="text-bold">The impact of body language in communication</p>
			</div>
		</div>

		<div class="row">

			<div class="col-sm-12">
				<blockquote style="border: 3px dashed #ff69b4">
					<p><b>Body language</b> <i>is the conscious and unconscious movements and postures by which attitudes and feelings are communicated.</i></p>
				</blockquote>

			</div>

		</div>

		<div class="row">

			<div class="col-sm-12 text-center">
				<p style="letter-spacing: 2px; border: 1px solid #000000; padding: 10px; background-color: #ff69b4; color: #ffffff; font-weight: bolder;">What is the impact of body language in communication?</p>
			</div>

		</div>

		<div class="row">

			<div class="col-sm-12">
				<p>The difference between the words people speak and our understanding of what they are saying comes from non?verbal communication, otherwise known as "body language." By developing your awareness of the
					signs and signals of body language, you can more easily understand other people, and more effectively communicate with them.</p>
				<p>There are sometimes subtle – and sometimes not so subtle – movements, gestures, facial expressions and even shifts in our whole bodies that indicate something is going on. The way we talk, walk, sit and stand all
					say something about us, and whatever is happening on the inside can be reflected on the outside.</p>
				<p>By becoming more aware of this body language and understanding what it might mean, you can learn to read people more easily. This puts you in a better position to communicate effectively with them.
					What's more, by increasing your understanding of others, you can also become more aware of the messages that you convey to them.</p>
				<p>There are times when we send mixed messages – we say one thing yet our body language reveals something different. This non?verbal language will affect how we act and react to others, and how they react to us.</p>
			</div>

		</div>

		<div class="row">

			<div class="col-sm-6">
				<div class="well">
					<p><b>Typical things to look for in confident people include:</b></p>
					<ul style="margin-left: 15px;">
						<li>Posture – standing tall with shoulders back.</li>
						<li>Eye contact – solid with a "smiling" face.</li>
						<li>Gestures with hands and arms – purposeful and deliberate.</li>
						<li>Speech – slow and clear.</li>
						<li>Tone of voice – moderate to low.</li>
					</ul>
				</div>
			</div>

			<div class="col-sm-6">
				<div class="well">
					<p><b>Some of the common signs that the person you are speaking with may be feeling defensive include:</b></p>
					<ul style="margin-left: 15px;">
						<li>Hand/arm gestures are small and close to his or her body.</li>
						<li>Facial expressions are minimal.</li>
						<li>Body is physically turned away from you.</li>
						<li>Arms are crossed in front of body.</li>
						<li>Eyes maintain little contact, or are downcast.</li>
					</ul>
				</div>
			</div>

		</div>

		<p><br></p>

		<div class="row"><div class="col-sm-12"><div class="pull-right"><?php echo HTML::renderWorkbookIcons('', true); ?></div></div></div>

		<?php echo $wb->getPageBottomLine(); ?>
	</div> <!--.page 6 ends-->

	<h1>Communication</h1>
	<div class="step-content">
		<?php echo $wb->getPageTopLine(); ?>

		<div class="row"><div class="col-sm-12"><div class="pull-right">
			<?php
			if($feedback->BodyLanguageSkills->Status->__toString() == 'NA')
				echo HTML::renderWorkbookIcons('BodyLanguageSkills', false, 'btn-danger');
			else
				echo HTML::renderWorkbookIcons('BodyLanguageSkills', false, 'btn-success');
			?>
		</div></div></div>

		<div class="row">
			<div class="col-sm-12">

				<p class="text-bold">Body language skills</p>
			</div>
		</div>

		<div class="row">

			<div class="col-sm-12">
				<p><b>Body language skills</b> are really important in different situations. As well as deciphering other people's body language, you can use this knowledge to convey feelings that you're not actually experiencing.</p>
				<p>For example, if you are about to enter into a situation where you are not as confident as you'd like to be, such as giving a big presentation or attending an important meeting, you can adopt these "confidence"
					signs and signals to project confidence.</p>
				<p>Equally, if you are feeling somewhat defensive going into a negotiating situation, you can monitor your own body language to ensure that the messages you are conveying are ones that say that you are open
					and receptive to what is being discussed.</p>
				<p>By picking up these signs, you can change what you say or how you say it to help the other person become more at ease, and more receptive to what you are saying.</p>
			</div>

		</div>

		<p class="text-bold text-center"><img src="module_eportfolio/assets/images/wb2_img1.png" /> &nbsp;Ask your manager / mentor to observe you and give you feedback on your body language. List the feedback below &nbsp;<img src="module_eportfolio/assets/images/obs1.png" /> </p>

		<div class="row"  style="display: flex;  <?php echo $feedback->BodyLanguageSkills->Status->__toString() == 'A'?' pointer-events:none;':''; ?>">
			<div class="col-sm-5 text-center" style="flex: 1;">
				<p class="text-bold">Positive body language</p>
				<p><textarea name="Positive" style="width: 100%;" rows="10"><?php echo $answers->BodyLanguageSkills->Positive->__toString(); ?></textarea> </p>
			</div>
			<div class="col-sm-1 text-center" style="flex: 1;"><img src="module_eportfolio/assets/images/obs1.png" /></div>
			<div class="col-sm-5 text-center" style="flex: 1;">
				<p class="text-bold">Negative body language</p>
				<p><textarea name="Negative" style="width: 100%;" rows="10"><?php echo $answers->BodyLanguageSkills->Negative->__toString(); ?></textarea> </p>
			</div>

		</div>

		<div class="row">
			<div class="col-sm-12 text-center">
				<img src="module_eportfolio/assets/images/wb7_pg7_img1.png" />
			</div>
		</div>

		<?php if($wb->id != '' && $_SESSION['user']->type == User::TYPE_ASSESSOR && ($wb->wb_status == Workbook::STATUS_LEARNER_COMPLETED || $wb->wb_status == Workbook::STATUS_BEING_CHECKED || $wb->wb_status == Workbook::STATUS_IV_REJECTED)) {?>
		<div class="row well">
			<div class="col-sm-12">
				<div class="box box-success box-solid">
					<div class="box-header with-border"><h3 class="box-title">Assessor Feedback</h3></div>
					<div class="box-body assessorFeedback" <?php echo $feedback->BodyLanguageSkills->Status->__toString() == 'A'?'style="pointer-events:none;"':''; ?>>
						<div class="form-group">
							<label for="status_BodyLanguageSkills" class="col-sm-12 control-label">Status:</label>
							<div class="col-sm-12">
								<?php echo HTML::selectChosen('status_BodyLanguageSkills', $answer_status, $feedback->BodyLanguageSkills->Status->__toString() == 'A'?$feedback->BodyLanguageSkills->Status->__toString():'', false, true); ?>
							</div>
						</div>
						<div class="form-group">
							<label for="comments_BodyLanguageSkills" class="col-sm-12 control-label">Comments:</label>
							<div class="col-sm-12">
								<textarea name="comments_BodyLanguageSkills" rows="7" style="width: 100%;"><?php echo $feedback->BodyLanguageSkills->Comments->__toString(); ?></textarea>
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
			if($feedback->BodyLanguageSkills->Status->__toString() == 'NA')
				echo HTML::renderWorkbookIcons('BodyLanguageSkills', false, 'btn-danger');
			else
				echo HTML::renderWorkbookIcons('BodyLanguageSkills', false, 'btn-success');
			?>
		</div></div></div>

		<?php echo $wb->getPageBottomLine(); ?>
	</div> <!--.page 7 ends-->

	<h1>Communication</h1>
	<div class="step-content">
		<?php echo $wb->getPageTopLine(); ?>

		<div class="row"><div class="col-sm-12"><div class="pull-right">
			<?php
			if($feedback->CustomerScenarios->Status->__toString() == 'NA')
				echo HTML::renderWorkbookIcons('CustomerScenarios', false, 'btn-danger');
			else
				echo HTML::renderWorkbookIcons('CustomerScenarios', false, 'btn-success');
			?>
		</div></div></div>

		<div class="row">
			<div class="col-sm-12">

				<p class="text-bold">How to acknowledge effectively</p>
			</div>
		</div>

		<div class="row">

			<div class="col-sm-12 text-center">
				<p style="letter-spacing: 2px; border: 1px solid #000000; padding: 10px; background-color: #ff69b4; color: #ffffff; font-weight: bolder;">What is the importance of non-judgmental listening in the communication process?</p>
			</div>

		</div>

		<div class="row">

			<div class="col-sm-12 text-center">
				<p><span class="glyphicon glyphicon-arrow-down"></span></p>
				<p style="border: 3px solid red; padding: 10px; text-align: center"><b>It allows the listener to hear and understand exactly what is being said whilst enabling the person to talk freely and comfortably</b></p>
			</div>

		</div>

		<div class="row">

			<div class="col-sm-12">
				<p>Active listening is a method of listening and responding to another person that encourages them to communicate clearly and fully. It is listener orientated and based on a 70:30 rule, meaning you focus your
					efforts on the speaker and aim to talk no more than 30% of the time whilst encouraging them to make up 70% of the conversation.</p>
			</div>

		</div>

		<div class="row">

			<div class="col-sm-6">
				<p><b>1. Be Non Judgmental and Pause Your Inner Voice:</b></p>
				<p>There is a good listening quote that says:</p>
				<blockquote><p>"People don't listen to what you say. People listen to what they say to themselves about what you say."</p></blockquote>
				<p>We can find ourselves evaluating conversations and hearing our 'inner voice'; the part of the conscious mind that is constantly processing and judging information.</p>
				<p>A common trait in human communication is to constantly take in and judge what is being said, which can be useful in certain circumstances, but not when practicing true active listening, as this can lead to two problems.</p>
				<p>Firstly it encourages premature evaluation of what is being said. This is because the average attention span during a conversation is only a
					matter of a few minutes (10 to 12) and people tend to reach a conclusion before this time.</p>
				<p>Secondly forming and voicing a judgment can stifle the speaker and often miss the true point being made.</p>
				<p><b>2. Body Language:</b></p>
				<p>Positive body language is important in building rapport and getting the talker to open up. Try to lean slightly forward when the person is talking, maintain eye contact and nod your head at key
					points. This sounds a bit forced so in practice it's important it's done naturally.</p>
				<p>Good listening body language demonstrates 'attendance' in a conversation. It shows the speaker you are interested, encouraging them to talk further.</p>
				<p><b>3. Seek to understand before you seek to be understood:</b></p>
				<p>When we enter a conversation with the aim of understanding the other person, rather than be understood ourselves, our intention will be to listen.</p>
				<p>Therefore try listening to understand, rather than to respond.</p>
			</div>

			<div class="col-sm-6">
				<p><b>4. Paraphrase:</b></p>
				<p>You can really demonstrate active listening by paraphrasing and explaining back to the speaker what you've just heard.</p>
				<p>This both checks your understanding and demonstrates 'attendance' to the speaker – i.e. that you're genuinely interested in their needs and have heard what they are saying.</p>
				<p><b>5. Acknowledgement</b></p>
				<p>A fast and highly effective way of practicing active listening is by acknowledging to the speaker you've heard what they have said.</p>
				<p>This makes the speaker feel understood.</p>
				<p>When people feel truly understood by you, they believe you to be perceptive and intelligent, which is also a great rapport builder.</p>
				<p>In difficult conversations feelings crave acknowledgement. And unless they receive the acknowledgement they need, feelings will cause trouble.</p>
				<p><b>How to acknowledge effectively:</b></p>
				<p>Imagine a scenario where another team member tells you they feel let down, and even lied to, by the manager regarding targets.
					If you were to reply "I haven't had any problems so don't worry about it." This is not an effective acknowledgement. Instead it would be more useful to reply: "It sounds like you’re really
					upset about this and if I was in your shoes I would feel concerned too."</p>
				<p>Of course there is no one perfect thing to say in every situation, and at times you can use your body
					language to convey acknowledgment. But the key to successful acknowledgement is to verbally recognise people's feelings and invisible questions. We must also acknowledge before moving onto problem solving</p>
				<p>In fact acknowledging and understanding are completely different from agreeing.</p>
				<p>This situation may happen with colleagues in store but it is also likely to happen with customers</p>

			</div>
		</div>

		<div class="row" <?php echo $feedback->CustomerScenarios->Status->__toString() == 'A'?'style="pointer-events:none;"':''; ?>>
			<div class="col-sm-12">
				<p class="text-bold"><img src="module_eportfolio/assets/images/wb2_img1.png" /> &nbsp;Complete the table below to show what you would say in each scenario to show you are acknowledging what the customer has said. &nbsp;<img src="module_eportfolio/assets/images/obs1.png" /> </p>
				<div class="table-responsive">
					<table class="table">
						<tr><th style="width: 30%;" class="text-center">Customer scenario</th><th class="text-center" style="width: 70%;">What would you say?</th></tr>
						<tr><td>A customer complains about the queue</td><td><textarea name="Scenario1Reply" rows="5" style="width: 100%"><?php echo $answers->CustomerScenarios->Scenario1Reply->__toString(); ?></textarea> </td></tr>
						<tr><td>A customer brings back a faulty hairdryer</td><td><textarea name="Scenario2Reply" rows="5" style="width: 100%"><?php echo $answers->CustomerScenarios->Scenario2Reply->__toString(); ?></textarea> </td></tr>
						<tr><td>A customer is giving you a long list of things they are looking for</td><td><textarea name="Scenario3Reply" rows="5" style="width: 100%"><?php echo $answers->CustomerScenarios->Scenario3Reply->__toString(); ?></textarea> </td></tr>
					</table>
				</div>
			</div>
		</div>

		<?php if($wb->id != '' && $_SESSION['user']->type == User::TYPE_ASSESSOR && ($wb->wb_status == Workbook::STATUS_LEARNER_COMPLETED || $wb->wb_status == Workbook::STATUS_BEING_CHECKED || $wb->wb_status == Workbook::STATUS_IV_REJECTED)) {?>
		<div class="row well">
			<div class="col-sm-12">
				<div class="box box-success box-solid">
					<div class="box-header with-border"><h3 class="box-title">Assessor Feedback</h3></div>
					<div class="box-body assessorFeedback" <?php echo $feedback->CustomerScenarios->Status->__toString() == 'A'?'style="pointer-events:none;"':''; ?>>
						<div class="form-group">
							<label for="status_CustomerScenarios" class="col-sm-12 control-label">Status:</label>
							<div class="col-sm-12">
								<?php echo HTML::selectChosen('status_CustomerScenarios', $answer_status, $feedback->CustomerScenarios->Status->__toString() == 'A'?$feedback->CustomerScenarios->Status->__toString():'', false, true); ?>
							</div>
						</div>
						<div class="form-group">
							<label for="comments_CustomerScenarios" class="col-sm-12 control-label">Comments:</label>
							<div class="col-sm-12">
								<textarea name="comments_CustomerScenarios" rows="7" style="width: 100%;"><?php echo $feedback->CustomerScenarios->Comments->__toString(); ?></textarea>
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
			if($feedback->CustomerScenarios->Status->__toString() == 'NA')
				echo HTML::renderWorkbookIcons('CustomerScenarios', false, 'btn-danger');
			else
				echo HTML::renderWorkbookIcons('CustomerScenarios', false, 'btn-success');
			?>
		</div></div></div>

		<?php echo $wb->getPageBottomLine(); ?>
	</div> <!--.page 8,9 ends-->

	<h1>Communication</h1>
	<div class="step-content">
		<?php echo $wb->getPageTopLine(); ?>

		<div class="row"><div class="col-sm-12"><div class="pull-right">
			<?php
			if($feedback->EmpathyExample->Status->__toString() == 'NA')
				echo HTML::renderWorkbookIcons('EmpathyExample', false, 'btn-danger');
			else
				echo HTML::renderWorkbookIcons('EmpathyExample', false, 'btn-success');
			?>
		</div></div></div>



		<div class="row">

			<div class="col-sm-12">
				<p><b>6. Use Silence and Questions Effectively:</b></p>
				<p>As an active listener silence is a valuable tool. In many cases you will learn more by maintaining your silence than asking questions.
					This is because your silence allows the speaker time to explain them self, helping you identify the true issues.</p>
				<p>Whilst maintaining silence is important, asking relevant questions is also an effective way of enhancing communication,
					and a key part of active listening. As mentioned earlier, ensure the other person is speaking around 70% of the time, meaning you need to make your 30% as effective as possible.</p>
				<p>Use open questions (what, when, where, why and how) if you want someone to expand and closed questions
					(such as do or if) when you need to narrow the conversation down. Only ever ask one question at a time, more confuses conversation.</p>
				<p><b>7. Empathy:</b></p>
				<p>Empathy is a fundamental skill that an active listener should have. Different in nature to sympathy, it is the
					ability to recognise and share feelings expressed by another. Empathy also allows people in a conversation to establish a connection and build trust.</p>
			</div>

		</div>

		<div class="row">

			<div class="col-sm-12 text-center">
				<p style="border: 3px solid red; padding: 10px; text-align: center"><b>Empathy<br><i>Empathy is the ability to understand and share the feelings of another</i></b></p>
			</div>

		</div>

		<div class="row" <?php echo $feedback->EmpathyExample->Status->__toString() == 'A'?'style="pointer-events:none;"':''; ?>>

			<div class="col-sm-12">
				<p class="text-bold"><img src="module_eportfolio/assets/images/wb2_img1.png" /> &nbsp;Give an example of when you have empathised with a person you have been communicating with. It could be a colleague, an internal or an external customer.</p>
				<p><textarea name="EmpathyExample" style="width: 100%;" rows="10"><?php echo $answers->EmpathyExample->__toString(); ?></textarea> </p>
			</div>

		</div>

		<?php if($wb->id != '' && $_SESSION['user']->type == User::TYPE_ASSESSOR && ($wb->wb_status == Workbook::STATUS_LEARNER_COMPLETED || $wb->wb_status == Workbook::STATUS_BEING_CHECKED || $wb->wb_status == Workbook::STATUS_IV_REJECTED)) {?>
		<div class="row well">
			<div class="col-sm-12">
				<div class="box box-success box-solid">
					<div class="box-header with-border"><h3 class="box-title">Assessor Feedback</h3></div>
					<div class="box-body assessorFeedback" <?php echo $feedback->EmpathyExample->Status->__toString() == 'A'?'style="pointer-events:none;"':''; ?>>
						<div class="form-group">
							<label for="status_EmpathyExample" class="col-sm-12 control-label">Status:</label>
							<div class="col-sm-12">
								<?php echo HTML::selectChosen('status_EmpathyExample', $answer_status, $feedback->EmpathyExample->Status->__toString() == 'A'?$feedback->EmpathyExample->Status->__toString():'', false, true); ?>
							</div>
						</div>
						<div class="form-group">
							<label for="comments_EmpathyExample" class="col-sm-12 control-label">Comments:</label>
							<div class="col-sm-12">
								<textarea name="comments_EmpathyExample" rows="7" style="width: 100%;"><?php echo $feedback->EmpathyExample->Comments->__toString(); ?></textarea>
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
			if($feedback->EmpathyExample->Status->__toString() == 'NA')
				echo HTML::renderWorkbookIcons('EmpathyExample', false, 'btn-danger');
			else
				echo HTML::renderWorkbookIcons('EmpathyExample', false, 'btn-success');
			?>
		</div></div></div>

		<?php echo $wb->getPageBottomLine(); ?>
	</div> <!--.page 10 ends-->

	<h1>Communication</h1>
	<div class="step-content">
		<?php echo $wb->getPageTopLine(); ?>

		<div class="row"><div class="col-sm-12"><div class="pull-right"><?php echo HTML::renderWorkbookIcons('', true); ?></div></div></div>



		<div class="row">

			<div class="col-sm-12 text-center">
				<blockquote style="border: 3px dashed #ff69b4">
					<p>
						<b>Rapport</b><br>
						<i>A close and harmonious relationship in which the people or groups concerned understand each other's feelings or ideas and communicate well</i>
					</p>
				</blockquote>
			</div>

		</div>

		<div class="row">

			<div class="col-sm-12">
				<div class="callout callout-info lead">
					<p>It is important to build rapport with your customers as it gets their unconscious mind to accept and begin to process your suggestions.
						They are made to feel comfortable and relaxed and will therefore listen and respond.</p>
				</div>

			</div>

		</div>

		<div class="row">

			<div class="col-sm-12 text-center">
				<img src="/module_eportfolio/assets/images/wb7_pg11_img1.png" />
			</div>

		</div>

		<p><br></p>

		<div class="row"><div class="col-sm-12"><div class="pull-right"><?php echo HTML::renderWorkbookIcons('', true); ?></div></div></div>

		<?php echo $wb->getPageBottomLine(); ?>
	</div> <!--.page 11 ends-->

	<h1>Communication</h1>
	<div class="step-content">
		<?php echo $wb->getPageTopLine(); ?>

		<div class="row"><div class="col-sm-12"><div class="pull-right">
			<?php
			if($feedback->CommunicationMethodsImportance->Status->__toString() == 'NA')
				echo HTML::renderWorkbookIcons('CommunicationMethodsImportance', false, 'btn-danger');
			else
				echo HTML::renderWorkbookIcons('CommunicationMethodsImportance', false, 'btn-success');
			?>
		</div></div></div>



		<div class="row">

			<div class="col-sm-12 text-center">
				<p style="letter-spacing: 2px; border: 1px solid #000000; padding: 10px; background-color: red; color: #ffffff; font-weight: bolder;">Why Do We Need Different Communication Methods?</p>
				<p><span class="glyphicon glyphicon-arrow-down"></span></p>
				<p style="border: 3px dashed red; padding: 10px; text-align: center"><b>Write your own thoughts / opinion of why we need different communication methods</b></p>
				<p><span class="glyphicon glyphicon-arrow-down"></span></p>
				<textarea name="Why" rows="5" style="width: 100%"><?php echo $answers->CommunicationMethodsImportance->Why->__toString(); ?></textarea>
			</div>

		</div>

		<div class="row" <?php echo $feedback->CommunicationMethodsImportance->Status->__toString() == 'A'?'style="pointer-events:none;"':''; ?>>

			<div class="col-sm-12">
				<p class="text-bold"><img src="module_eportfolio/assets/images/wb2_img1.png" /> &nbsp;Which different communication methods do you use in store with colleagues and customers? Think about both formal and informal ways of communicating to others.</p>
				<p><textarea name="Which" style="width: 100%;" rows="10"><?php echo $answers->CommunicationMethodsImportance->Which->__toString(); ?></textarea> </p>
			</div>

		</div>

		<?php if($wb->id != '' && $_SESSION['user']->type == User::TYPE_ASSESSOR && ($wb->wb_status == Workbook::STATUS_LEARNER_COMPLETED || $wb->wb_status == Workbook::STATUS_BEING_CHECKED || $wb->wb_status == Workbook::STATUS_IV_REJECTED)) {?>
		<div class="row well">
			<div class="col-sm-12">
				<div class="box box-success box-solid">
					<div class="box-header with-border"><h3 class="box-title">Assessor Feedback</h3></div>
					<div class="box-body assessorFeedback" <?php echo $feedback->CommunicationMethodsImportance->Status->__toString() == 'A'?'style="pointer-events:none;"':''; ?>>
						<div class="form-group">
							<label for="status_CommunicationMethodsImportance" class="col-sm-12 control-label">Status:</label>
							<div class="col-sm-12">
								<?php echo HTML::selectChosen('status_CommunicationMethodsImportance', $answer_status, $feedback->CommunicationMethodsImportance->Status->__toString() == 'A'?$feedback->CommunicationMethodsImportance->Status->__toString():'', false, true); ?>
							</div>
						</div>
						<div class="form-group">
							<label for="comments_CommunicationMethodsImportance" class="col-sm-12 control-label">Comments:</label>
							<div class="col-sm-12">
								<textarea name="comments_CommunicationMethodsImportance" rows="7" style="width: 100%;"><?php echo $feedback->CommunicationMethodsImportance->Comments->__toString(); ?></textarea>
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
			if($feedback->CommunicationMethodsImportance->Status->__toString() == 'NA')
				echo HTML::renderWorkbookIcons('CommunicationMethodsImportance', false, 'btn-danger');
			else
				echo HTML::renderWorkbookIcons('CommunicationMethodsImportance', false, 'btn-success');
			?>
		</div></div></div>

		<?php echo $wb->getPageBottomLine(); ?>
	</div> <!--.page 12 ends-->

	<?php if($wb->getQAN($link) == Workbook::RETAIL_QAN){?>
	<h1>Qualification Questions</h1>
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
						<p><strong>Unit 1 - 2.1<br>Describe different methods of communicating with customers</strong></p>
						<p><textarea name="Unit2_1" style="width: 100%" rows="5"><?php echo $answers->QualificationQuestions->Unit2_1->__toString(); ?></textarea></p>
						<p><strong>Unit 1 - 2.2<br>Describe how to determine an individual's situation and needs</strong></p>
						<p><textarea name="Unit2_2" style="width: 100%" rows="5"><?php echo $answers->QualificationQuestions->Unit2_2->__toString(); ?></textarea></p>
						<p><strong>Unit 2 - 3.1<br>Identify what is meant by rapport</strong></p>
						<p><textarea name="Unit3_1" style="width: 100%" rows="5"><?php echo $answers->QualificationQuestions->Unit3_1->__toString(); ?></textarea></p>
						<p><strong>Unit 2 - 3.2<br>Explain how to establish a rapport with customers</strong></p>
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
	</div>
	<?php } ?>
	
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

	$("#frm_wb_communication :input").not(".assessorFeedback :input, #signature_text, #frm_wb_communication :input[type=hidden], .btnViewSectionHistory, .btnViewAssessorComments, #iv_status, #iv_comments, #reopen_comments").prop("disabled", true);

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
						var myForm = document.forms['frm_wb_communication'];
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
			var myForm = document.forms['frm_wb_communication'];
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

				var myForm = document.forms['frm_wb_communication'];
				myForm.elements['full_save'].value = 'Y';
				myForm.elements['full_save_feedback'].value = 'Y';
				window.onbeforeunload = null;
				myForm.submit();

			},
			'Save And Come Back Later':function () {

				var myForm = document.forms['frm_wb_communication'];
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
	$('#frm_wb_communication :input[name=full_save]').val('N');
	$($('#frm_wb_communication').serializeArray()).each(function(i, field)
	{
		document.forms["frm_wb_communication"].elements[field.name].value = field.value.replace(/£/g, "GBP");
	});
	$.ajax({
		type:'POST',
		url:'do.php?_action=save_wb_communication',
		data: $('#frm_wb_communication').serialize(),
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
	var phpStepsWithQuestions = '<?php echo $wb->getStepsWithQuestions($link); ?>';
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
