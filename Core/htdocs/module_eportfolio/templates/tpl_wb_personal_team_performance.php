<?php /* @var $wb WBTeamWorking */ ?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/html" xmlns="http://www.w3.org/1999/html" xmlns="http://www.w3.org/1999/html"
      xmlns="http://www.w3.org/1999/html">
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<title>Personal and team performance workbook</title>
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
		.text-3d{
			font-size: 40px;
			color: #94942C;
			font-family: Arial Black, Gadget, sans-serif;
			text-shadow: 0px 0px 0 rgb(129,129,25),-1px 1px 0 rgb(121,121,17),-2px 2px 0 rgb(112,112,8),-3px 3px 0 rgb(103,103,-1),-4px 4px 0 rgb(95,95,-9),-5px 5px 0 rgb(86,86,-18),-6px 6px 0 rgb(77,77,-27),-7px 7px 0 rgb(68,68,-36),-8px 8px 0 rgb(60,60,-44),-9px 9px 0 rgb(51,51,-53),
			-10px 10px  0 rgb(42,42,-62),-11px 11px 10px rgba(0,0,0,0.6),-11px 11px 1px rgba(0,0,0,0.5),0px 0px 10px rgba(0,0,0,.2)
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
	<div class="text-center" style="margin-top: 5px;"><h3><?php echo $tr->firstnames . ' '  . strtoupper($tr->surname); ?></h3></div>
</nav>

<form name="frm_wb_personal_team_performance" id="frm_wb_personal_team_performance" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
	<input type="hidden" name="_action" value="save_wb_personal_team_performance" />
	<input type="hidden" name="id" value="<?php echo $wb->id; ?>" />
	<input type="hidden" name="tr_id" value="<?php echo $wb->tr_id; ?>" />
	<input type="hidden" name="wb_status" id="wb_status" value="" />
	<input type="hidden" name="full_save" id="full_save" value="<?php echo $wb->full_save; ?>" />
	<input type="hidden" name="full_save_feedback" id="full_save_feedback" value="<?php echo $wb->full_save_feedback; ?>" />
	<div class="container-float">
		<div class="wrapper" style="background-color: #ffffff;">

		<header class="main-header"><span style="margin-left: 50%;"><?php echo DAO::getSingleValue($link, "SELECT title FROM student_frameworks WHERE tr_id = '{$wb->tr_id}'"); ?></span></header>

		<div class="content-wrapper" style="background-color: #ffffff;">

		<?php echo $wb->savers_or_sp == 'savers' ? '<section class="content-header"><h1 style="color: #0000ff;">Personal and Team Performance</h1></section>' : '<section class="content-header"><h1>Personal and Team Performance</h1></section>' ?>

		<section class="content">

		<div id="wizard">

			<h1>Personal and Team Performance</h1>
			<div class="step-content">
				<?php echo $wb->getPageTopLine(); ?>
				<div style="position: absolute; top: 40%; right: 50%;" class="lead">
					<?php echo $wb->savers_or_sp == 'savers' ? '<h2 class="text-bold" style="color: #0000ff;">Personal and Team Performance</h2>' : '<h2 class="text-bold">Personal and Team Performance</h2>' ?>
					<p class="text-center" >Module 6</p>
				</div>

				<p><br></p>

				<?php echo $wb->getPageBottomLine(); ?>
			</div> <!--.page 1 ends-->

			<h1>Personal and Team Performance</h1>
			<div class="step-content">
				<?php echo $wb->getPageTopLine(); ?>

				<div class="row"><div class="col-sm-12"><div class="pull-right">
					<?php
					if($feedback->Team->Status->__toString() == 'NA')
						echo HTML::renderWorkbookIcons('Team', false, 'btn-danger');
					else
						echo HTML::renderWorkbookIcons('Team', false, 'btn-success');
					?>
				</div></div></div>



				<div class="row">
					<div class="col-sm-12 text-center">
						<h2>Team</h2>
						<div style="flex: 1; padding: 1em; background-color: #adff2f; font-weight: bolder;">
							<h3>A team</h3>
							<p>A group of people with a full set of complementary skills required to complete a task, job or project</p>
						</div>
					</div>
				</div>
				<div class="row">
					<div class="col-sm-12">
						<p><strong>What makes an effective team and what is meant by team dynamics?</strong></p>
						<p>Team members:</p>
						<ul style="margin-left: 15px;">
							<li>Operate with a high degree of interdependence</li>
							<li>Share authority and responsibility for self-management</li>
							<li>Are accountable for the collective performance</li>
							<li>Work toward a common goal and shared rewards(s)</li>
						</ul>
						<p>A team becomes more than just a collection of people when a strong sense of mutual commitment creates synergy, thus generating performance greater than the sum of the performance of its individual members.</p>
						<p><strong><p>
							<img src="module_eportfolio/assets/images/wb2_img1.png" /> &nbsp;
							What teams are you involved in? List some of the teams. Think about both in and out of work: &nbsp;
							<img src="module_eportfolio/assets/images/wb4_img1.png" />
						</p></strong></p>
						<p <?php echo $feedback->Team->Status->__toString() == 'A'?'style="pointer-events:none;"':''; ?>><textarea name="Team" style="width: 100%;" rows="5"><?php echo $answers->Team->__toString(); ?></textarea> </p>
						<p class="text-bold">Teamwork and performance</p>
						<p>Good teamwork is essential in all organisations.</p>
						<p>It signifies that:</p>
						<p>People are working towards a shared purpose and common goals and in so doing they are sharing their varied skills in complementary roles and in cooperation with each other.</p>
						<p>Organisations are much more likely to perform well when their people work effectively as a team. </p>
						<p>Working together a team can apply individual perspectives, experience and skills to solve complex problems, creating new solutions and ideas that may be beyond the scope of any one individual.</p>
					</div>
				</div>
				<div class="row">
					<div class="col-sm-8">
						<p>As well as enhancing an organisation’s performance good teamwork benefits individuals too. It enables mutual support and learning and can generate a sense of belonging and commitment.</p>
					</div>
					<div class="col-sm-4"><img src="module_eportfolio/assets/images/wb6_pg3_img1.png" /> </div>
				</div>

				<?php if($wb->id != '' && $_SESSION['user']->type == User::TYPE_ASSESSOR && ($wb->wb_status == Workbook::STATUS_LEARNER_COMPLETED || $wb->wb_status == Workbook::STATUS_BEING_CHECKED || $wb->wb_status == Workbook::STATUS_IV_REJECTED)) {?>
				<div class="row well">
					<div class="col-sm-12">
						<div class="box box-success box-solid">
							<div class="box-header with-border"><h3 class="box-title">Assessor Feedback</h3></div>
							<div class="box-body assessorFeedback" <?php echo $feedback->Team->Status->__toString() == 'A'?'style="pointer-events:none;"':''; ?>>
								<div class="form-group">
									<label for="status_Team" class="col-sm-12 control-label">Status:</label>
									<div class="col-sm-12">
										<?php
										echo HTML::selectChosen('status_Team', $answer_status, $feedback->Team->Status->__toString() == 'A'?$feedback->Team->Status->__toString():'', false, true); ?>
									</div>
								</div>
								<div class="form-group">
									<label for="comments_Team" class="col-sm-12 control-label">Comments:</label>
									<div class="col-sm-12">
										<textarea name="comments_Team" rows="7" style="width: 100%;"><?php echo $feedback->Team->Comments->__toString(); ?></textarea>
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
					if($feedback->Team->Status->__toString() == 'NA')
						echo HTML::renderWorkbookIcons('Team', false, 'btn-danger');
					else
						echo HTML::renderWorkbookIcons('Team', false, 'btn-success');
					?>
				</div></div></div>

				<?php echo $wb->getPageBottomLine(); ?>
			</div> <!--.page 3 ends-->

			<h1>Personal and Team Performance</h1>
			<div class="step-content">
				<?php echo $wb->getPageTopLine(); ?>

				<div class="row"><div class="col-sm-12"><div class="pull-right">
					<?php
					if($feedback->EffectiveTeam->Status->__toString() == 'NA')
						echo HTML::renderWorkbookIcons('EffectiveTeam', false, 'btn-danger');
					else
						echo HTML::renderWorkbookIcons('EffectiveTeam', false, 'btn-success');
					?>
				</div></div></div>



				<div class="row">
					<div class="col-sm-6">
						<p><strong>An effective team needs the following:</strong></p>
						<p><br></p>
						<p><strong>Clear objectives</strong></p>
						<p>These are mutually-agreed aims and objectives and everyone has a clear understanding of them.</p>
						<p><br></p>
						<p><strong>Effective processes</strong></p>
						<p>These are good processes for making, communicating, implementing and reviewing decisions.</p>
						<p><br></p>
						<p><strong>Good communication</strong></p>
						<p>Communication is productive and it is effective up, down and across the organisation.</p>
						<p><br></p>
						<p><strong>Appropriate leadership</strong></p>
						<p>The team trusts their manager and feels that they are led in an appropriate way.</p>
						<p><br></p>
						<p><strong>Support and trust</strong></p>
						<p>People help each other by listening, evaluating, offering ideas, encouraging experimentation and giving support.</p>
						<p><br></p>
						<p><strong>Openness and conflict</strong></p>
						<p>People express themselves openly and honestly. There is a willingness to work through difficult situations or conflict constructively.</p>
						<p><br></p>
						<p><strong>Mutual co-operation</strong></p>
						<p>There is a readiness to be involved and committed. Individuals’ abilities, knowledge and experience are pooled and used by the team. There is acceptance of each others strengths and weaknesses.</p>
						<p><br></p>
						<p><strong>Individual development</strong></p>
						<p>Mistakes are faced openly and used as a vehicle for learning. Individuals are given opportunities to develop new skills and experience.</p>
						<p><br></p>
						<p><strong>Sound inter-group relations</strong></p>
						<p>The team enjoys good relations with other teams, departments and agencies, each valuing and respecting the other.</p>
					</div>
					<div class="col-sm-6">
						<p><strong>Balanced roles</strong></p>
						<p>There is a good balance of skills, abilities and aspirations. Team members have a clear understanding of each individual’s role in achieving overall team objectives.</p>
						<p><br></p>
						<p><strong>Regular reviews</strong></p>
						<p>The team regularly reviews its performance and goals and alters its priorities and practices in the light of review.</p>
						<p><strong><p>
							<img src="module_eportfolio/assets/images/wb2_img1.png" /> &nbsp;
							Looking at all the points listed do you think your team is effective? Which areas do you think your team could improve on? Make some notes and discuss with your assessor.
						</p></strong></p>
						<p <?php echo $feedback->EffectiveTeam->Status->__toString() == 'A'?'style="pointer-events:none;"':''; ?>><textarea name="EffectiveTeam" style="width: 100%;" rows="10"><?php echo $answers->EffectiveTeam->__toString(); ?></textarea> </p>
						<p><img src="module_eportfolio/assets/images/wb6_pg4_img1.png" /></p>
					</div>
				</div>

				<?php if($wb->id != '' && $_SESSION['user']->type == User::TYPE_ASSESSOR && ($wb->wb_status == Workbook::STATUS_LEARNER_COMPLETED || $wb->wb_status == Workbook::STATUS_BEING_CHECKED || $wb->wb_status == Workbook::STATUS_IV_REJECTED)) {?>
				<div class="row well">
					<div class="col-sm-12">
						<div class="box box-success box-solid">
							<div class="box-header with-border"><h3 class="box-title">Assessor Feedback</h3></div>
							<div class="box-body assessorFeedback" <?php echo $feedback->EffectiveTeam->Status->__toString() == 'A'?'style="pointer-events:none;"':''; ?>>
								<div class="form-group">
									<label for="status_EffectiveTeam" class="col-sm-12 control-label">Status:</label>
									<div class="col-sm-12">
										<?php
										echo HTML::selectChosen('status_EffectiveTeam', $answer_status, $feedback->EffectiveTeam->Status->__toString() == 'A'?$feedback->EffectiveTeam->Status->__toString():'', false, true); ?>
									</div>
								</div>
								<div class="form-group">
									<label for="comments_EffectiveTeam" class="col-sm-12 control-label">Comments:</label>
									<div class="col-sm-12">
										<textarea name="comments_EffectiveTeam" rows="7" style="width: 100%;"><?php echo $feedback->EffectiveTeam->Comments->__toString(); ?></textarea>
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
					if($feedback->EffectiveTeam->Status->__toString() == 'NA')
						echo HTML::renderWorkbookIcons('EffectiveTeam', false, 'btn-danger');
					else
						echo HTML::renderWorkbookIcons('EffectiveTeam', false, 'btn-success');
					?>
				</div></div></div>

				<?php echo $wb->getPageBottomLine(); ?>
			</div> <!--.page 4 ends-->

			<h1>Personal and Team Performance</h1>
			<div class="step-content">
				<?php echo $wb->getPageTopLine(); ?>

				<div class="row"><div class="col-sm-12"><div class="pull-right">
					<?php
					if($feedback->TeamDynamics->Status->__toString() == 'NA')
						echo HTML::renderWorkbookIcons('TeamDynamics', false, 'btn-danger');
					else
						echo HTML::renderWorkbookIcons('TeamDynamics', false, 'btn-success');
					?>
				</div></div></div>



				<div class="row">
					<div class="col-sm-6">
						<p class="text-bold text-center">
							<div style="border: 2px solid #0063dc;border-radius: 15px; padding: 10px;">
								<p>Team dynamics are the unconscious psychological forces that influence the direction of a team’s behaviour and performance.</p>
							</div>
						</p>
						<p>Team dynamics are like undercurrents in the sea, which can carry boats in a different direction to the one they intend to sail.</p>
					</div>
					<div class="col-sm-6">
						<p>Team dynamics are created by the nature of the team’s work and the personalities within the team. Working relationships with other people and the environment can also affect the team work together.</p>
						<p>Team dynamics can be good - for example, when they improve overall team performance and/or get the best out of individual team members.  They can also be bad - for example, when they cause unproductive conflict, demotivation and prevent the team from achieving its goals.</p>
					</div>
				</div>
				<div class="row" <?php echo $feedback->TeamDynamics->Status->__toString() == 'A'?'style="pointer-events:none;"':''; ?>>
					<div class="col-sm-12">
						<p class="text-bold"><img src="module_eportfolio/assets/images/wb2_img1.png" /> &nbsp;What are the team dynamics like in your team?</p>
						<p><textarea name="TeamDynamics" style="width: 100%;" rows="5"><?php echo $answers->TeamDynamics->__toString(); ?></textarea> </p>
					</div>
				</div>
				<div class="row">
					<div class="col-sm-12">
						<p><strong>Different methods, including the use of effective negotiation to positively influence a team</strong></p>
					</div>
				</div>
				<div class="row">
					<div class="col-sm-12 text-center">
						<div style="border: 2px solid #0063dc;border-radius: 15px; padding: 10px;">
							<p><strong>Negotiation</strong> is a method by which people settle differences.It is a process by which compromise or agreement is reached while avoiding argument and dispute.</p>
						</div>
					</div>
				</div>
				<div class="row">
					<div class="col-sm-6">
						<p>Specific forms of negotiation are used in many situations: international affairs, the legal system, government, industrial disputes or domestic relationships as examples.</p>
						<p>However, general negotiation skills can be learned and applied in a wide range of activities.  Negotiation skills can be of great benefit in resolving any differences that arise between you and others.</p>
					</div>
					<div class="col-sm-6">
						<p><strong>Stages of Negotiation</strong></p>
						<p>In order to achieve a desirable outcome, it may be useful to follow a structured approach to negotiation.</p>
						<p>The process of negotiation includes the following stages:</p>
						<ol style="margin-left: 15px;">
							<li>Preparation</li>
							<li>Discussion</li>
							<li>Clarification of goals</li>
							<li>Negotiate towards a Win-Win outcome</li>
							<li>Agreement</li>
							<li>Implementation of a course of action</li>
						</ol>
					</div>
				</div>

				<?php if($wb->id != '' && $_SESSION['user']->type == User::TYPE_ASSESSOR && ($wb->wb_status == Workbook::STATUS_LEARNER_COMPLETED || $wb->wb_status == Workbook::STATUS_BEING_CHECKED || $wb->wb_status == Workbook::STATUS_IV_REJECTED)) {?>
				<div class="row well">
					<div class="col-sm-12">
						<div class="box box-success box-solid">
							<div class="box-header with-border"><h3 class="box-title">Assessor Feedback</h3></div>
							<div class="box-body assessorFeedback" <?php echo $feedback->TeamDynamics->Status->__toString() == 'A'?'style="pointer-events:none;"':''; ?>>
								<div class="form-group">
									<label for="status_TeamDynamics" class="col-sm-12 control-label">Status:</label>
									<div class="col-sm-12">
										<?php
										echo HTML::selectChosen('status_TeamDynamics', $answer_status, $feedback->TeamDynamics->Status->__toString() == 'A'?$feedback->TeamDynamics->Status->__toString():'', false, true); ?>
									</div>
								</div>
								<div class="form-group">
									<label for="comments_TeamDynamics" class="col-sm-12 control-label">Comments:</label>
									<div class="col-sm-12">
										<textarea name="comments_TeamDynamics" rows="7" style="width: 100%;"><?php echo $feedback->TeamDynamics->Comments->__toString(); ?></textarea>
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
					if($feedback->TeamDynamics->Status->__toString() == 'NA')
						echo HTML::renderWorkbookIcons('TeamDynamics', false, 'btn-danger');
					else
						echo HTML::renderWorkbookIcons('TeamDynamics', false, 'btn-success');
					?>
				</div></div></div>

				<?php echo $wb->getPageBottomLine(); ?>
			</div> <!--.page 5 ends-->

			<h1>Personal and Team Performance</h1>
			<div class="step-content">
				<?php echo $wb->getPageTopLine(); ?>

				<div class="row"><div class="col-sm-12"><div class="pull-right">
					<?php
					if($feedback->PersuationAndInfluencingSkills->Status->__toString() == 'NA')
						echo HTML::renderWorkbookIcons('PersuationAndInfluencingSkills', false, 'btn-danger');
					else
						echo HTML::renderWorkbookIcons('PersuationAndInfluencingSkills', false, 'btn-success');
					?>
				</div></div></div>



				<div class="row">
					<div class="col-sm-6">
						<p><strong>Persuasion and Influencing Skills</strong></p>
						<p>A key part of being able to negotiate successfully is to be able to persuade and influence others.</p>
						<p><br></p>
						<p><strong>Ways to Influence and Persuade Nagging</strong></p>
						<p>We all know people who aim to persuade by talking constantly. They seem to think they can grind others into submission, by simply reiterating their point of view constantly. This, basically, is nagging. It does sometimes work, of course, because their colleagues or family give in solely to get some peace.</p>
					</div>
					<div class="col-sm-6">
						<p>As a general rule, others persuaded in this way probably haven’t bought into the idea and are not committed to it.</p>
						<p><br></p>
						<p><strong>Coercion</strong></p>
						<p>Others fall back on the power of their position and order others to do what they want. This, in its most unpleasant sense, is coercion. Again, their family or colleagues won’t necessarily like what they’re doing.</p>
						<p>If it’s hard they may well give up. More orders will be issued to rescue the idea; however this may be unsuccessful, because those involved are doing it because they have to, not because they want to.</p>
					</div>
				</div>
				<div class="row">
					<div class="col-sm-12">
						<p><strong>A Better Way</strong></p>
						<p>The ‘Holy Grail’ of persuasion is to get others to buy into the idea and want to do it your way. The best way of doing that is in a way that others don’t notice.</p>
					</div>
				</div>
				<div class="row">
					<div class="col-sm-12 text-center">
						<div style="border: 2px solid #ffc0cb;border-radius: 15px; padding: 10px;">
							<p><strong>The fable of the sun and the wind is a good example:</strong></p>
						</div>
					</div>
				</div>
				<p><br></p>
				<div class="row text-center">
					<div class="col-sm-12">
						<div class="bg-pink text-bold" style="padding: 10px;">
							<p style="font-style: italic;">The wind and the sun decided to have a competition to decide once and for all who was stronger. They agreed that the winner would be the one who could persuade a man to take off his coat. The wind blew and blew, but the man only held on more tightly to his coat. Then the sun shone gently down, and within minutes, the man took off his coat. </p>
						</div>
					</div>
				</div>
				<p><br></p>
				<div class="row">
					<div class="col-sm-12 text-center">
						<div style="border: 2px solid #ffc0cb;border-radius: 15px; padding: 10px;">
							<p><strong>The moral here is that you can’t force someone to do what they don’t want; instead, the art of persuasion is to get them to want what you want.</strong></p>
						</div>
					</div>
				</div>

				<div class="row" <?php echo $feedback->PersuationAndInfluencingSkills->Status->__toString() == 'A'?'style="pointer-events:none;"':''; ?>>
					<div class="col-sm-12">
						<p class="text-bold"><img src="module_eportfolio/assets/images/wb2_img1.png" /> &nbsp;What have you negotiated recently within a team you are involved in?  You might have examples from inside and outside of work. Explain what happened and how you were involved.</p>
						<h3>Inside work</h3>
						<p><textarea name="InsideWork" style="width: 100%;" rows="5"><?php echo $answers->PersuationAndInfluencingSkills->InsideWork->__toString(); ?></textarea></p>
					</div>
				</div>

				<div class="row" <?php echo $feedback->PersuationAndInfluencingSkills->Status->__toString() == 'A'?'style="pointer-events:none;"':''; ?>>
					<div class="col-sm-12">
						<h3>Outside work</h3>
						<p><textarea name="OutsideWork" style="width: 100%;" rows="5"><?php echo $answers->PersuationAndInfluencingSkills->OutsideWork->__toString(); ?></textarea> </p>
						<p class="text-bold">Ways, in which team members / teams work together, interact and provide support to each other to meet business objectives and how to effectively participate in team meetings or briefings. </p>
					</div>
				</div>

				<div class="row">
					<div class="col-sm-6"><img src="module_eportfolio/assets/images/wb6_pg7_img1.png" /></div>
					<div class="col-sm-6">
						<div style="border: 2px solid #0044cc;border-radius: 15px; padding: 10px;">
							<p><strong>Talent wins games</strong>, but teamwork and intelligence wins championships.</p>
						</div>
					</div>
				</div>

				<div class="row" <?php echo $feedback->PersuationAndInfluencingSkills->Status->__toString() == 'A'?'style="pointer-events:none;"':''; ?>>
					<div class="col-sm-12">
						<p class="text-bold"><img src="module_eportfolio/assets/images/wb2_img1.png" /> &nbsp;When have you attended team briefings or meetings in your store? How did you contribute? Make some notes below and then discuss with your assessor.</p>
						<p><textarea name="YourContributionInTeamMeetings" style="width: 100%;" rows="7"><?php echo $answers->PersuationAndInfluencingSkills->YourContributionInTeamMeetings->__toString(); ?></textarea> </p>
						<p class="text-bold"><img src="module_eportfolio/assets/images/wb2_img1.png" /> &nbsp;Think about your store team – give some examples of how you work together and support each other to meet business objectives. Consider that larger stores may have more than one team or night shift at certain times of the year.</p>
						<p><textarea name="ExamplesOfWorkingAndSupporting" style="width: 100%;" rows="7"><?php echo $answers->PersuationAndInfluencingSkills->ExamplesOfWorkingAndSupporting->__toString(); ?></textarea> </p>
						<p class="text-bold"><img src="module_eportfolio/assets/images/wb2_img1.png" /> &nbsp;Think about the wider team, your area or region. Speak to your manager about how your store team may support others in your area?</p>
						<p><textarea name="HowYourTeamSupportOthers" style="width: 100%;" rows="7"><?php echo $answers->PersuationAndInfluencingSkills->HowYourTeamSupportOthers->__toString(); ?></textarea> </p>
					</div>
				</div>

				<?php if($wb->id != '' && $_SESSION['user']->type == User::TYPE_ASSESSOR && ($wb->wb_status == Workbook::STATUS_LEARNER_COMPLETED || $wb->wb_status == Workbook::STATUS_BEING_CHECKED || $wb->wb_status == Workbook::STATUS_IV_REJECTED)) {?>
				<div class="row well">
					<div class="col-sm-12">
						<div class="box box-success box-solid">
							<div class="box-header with-border"><h3 class="box-title">Assessor Feedback</h3></div>
							<div class="box-body assessorFeedback" <?php echo $feedback->PersuationAndInfluencingSkills->Status->__toString() == 'A'?'style="pointer-events:none;"':''; ?>>
								<div class="form-group">
									<label for="status_PersuationAndInfluencingSkills" class="col-sm-12 control-label">Status:</label>
									<div class="col-sm-12">
										<?php
										echo HTML::selectChosen('status_PersuationAndInfluencingSkills', $answer_status, $feedback->PersuationAndInfluencingSkills->Status->__toString() == 'A'?$feedback->PersuationAndInfluencingSkills->Status->__toString():'', false, true); ?>
									</div>
								</div>
								<div class="form-group">
									<label for="comments_PersuationAndInfluencingSkills" class="col-sm-12 control-label">Comments:</label>
									<div class="col-sm-12">
										<textarea name="comments_PersuationAndInfluencingSkills" rows="7" style="width: 100%;"><?php echo $feedback->PersuationAndInfluencingSkills->Comments->__toString(); ?></textarea>
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
					if($feedback->PersuationAndInfluencingSkills->Status->__toString() == 'NA')
						echo HTML::renderWorkbookIcons('PersuationAndInfluencingSkills', false, 'btn-danger');
					else
						echo HTML::renderWorkbookIcons('PersuationAndInfluencingSkills', false, 'btn-success');
					?>
				</div></div></div>

				<?php echo $wb->getPageBottomLine(); ?>
			</div> <!--.page 6 ends-->

			<h1>Personal and Team Performance</h1>
			<div class="step-content">
				<?php echo $wb->getPageTopLine(); ?>

				<div class="row"><div class="col-sm-12"><div class="pull-right">
					<?php
					if($feedback->ImplicationsOfNotWorkingTogether->Status->__toString() == 'NA')
						echo HTML::renderWorkbookIcons('ImplicationsOfNotWorkingTogether', false, 'btn-danger');
					else
						echo HTML::renderWorkbookIcons('ImplicationsOfNotWorkingTogether', false, 'btn-success');
					?>
				</div></div></div>



				<div class="row">
					<div class="col-sm-12">
						<h3 class="text-center">The implications when team members do not work together.</h3>
					</div>
					<div class="col-sm-12" <?php echo $feedback->ImplicationsOfNotWorkingTogether->Status->__toString() == 'A'?'style="pointer-events:none;"':''; ?>>
						<p class="text-bold"><img src="module_eportfolio/assets/images/wb2_img1.png" /> &nbsp;Below are some examples of things people do which don’t support team working and cause teams to fail. Can you think of some of your own to complete the mind map?</p>
						<div class="row">
							<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-center">
								<img class="img-responsive" src="module_eportfolio/assets/images/wb6_pg8_img1.png" />
							</div>
						</div>
						<div class="row">
							<div class="col-lg-2 col-md-2 col-sm-2 col-xs-2 text-center">
								<p><h4 class="text-bold pull-right">Q1</h4></p>
							</div>
							<div class="col-lg-10 col-md-10 col-sm-10 col-xs-10 text-center">
								<p><textarea name="ExampleGap1" style="width: 100%;"><?php echo $answers->ImplicationsOfNotWorkingTogether->ExampleGap1->__toString(); ?></textarea></p>
							</div>
						</div>
						<div class="row">
							<div class="col-lg-2 col-md-2 col-sm-2 col-xs-2 text-center">
								<p><h4 class="text-bold pull-right">Q2</h4></p>
							</div>
							<div class="col-lg-10 col-md-10 col-sm-10 col-xs-10 text-center">
								<p><textarea name="ExampleGap2" style="width: 100%;"><?php echo $answers->ImplicationsOfNotWorkingTogether->ExampleGap2->__toString(); ?></textarea></p>
							</div>
						</div>
						<div class="row">
							<div class="col-lg-2 col-md-2 col-sm-2 col-xs-2 text-center">
								<p><h4 class="text-bold pull-right">Q3</h4></p>
							</div>
							<div class="col-lg-10 col-md-10 col-sm-10 col-xs-10 text-center">
								<p><textarea name="ExampleGap3" style="width: 100%;"><?php echo $answers->ImplicationsOfNotWorkingTogether->ExampleGap3->__toString(); ?></textarea></p>
							</div>
						</div>
					</div>
					<div class="col-sm-12" <?php echo $feedback->ImplicationsOfNotWorkingTogether->Status->__toString() == 'A'?'style="pointer-events:none;"':''; ?>>
						<p class="text-bold"><img src="module_eportfolio/assets/images/wb2_img1.png" /> &nbsp; Looking back at the discussion point for this section - What are the implications of team members not working together? What would happen? Make some notes below:</p>
						<p><textarea name="DetailNotes" rows="7" style="width: 100%;"><?php echo $answers->ImplicationsOfNotWorkingTogether->DetailNotes->__toString(); ?></textarea> </p>
					</div>
				</div>

				<?php if($wb->id != '' && $_SESSION['user']->type == User::TYPE_ASSESSOR && ($wb->wb_status == Workbook::STATUS_LEARNER_COMPLETED || $wb->wb_status == Workbook::STATUS_BEING_CHECKED || $wb->wb_status == Workbook::STATUS_IV_REJECTED)) {?>
				<div class="row well">
					<div class="col-sm-12">
						<div class="box box-success box-solid">
							<div class="box-header with-border"><h3 class="box-title">Assessor Feedback</h3></div>
							<div class="box-body assessorFeedback" <?php echo $feedback->ImplicationsOfNotWorkingTogether->Status->__toString() == 'A'?'style="pointer-events:none;"':''; ?>>
								<div class="form-group">
									<label for="status_ImplicationsOfNotWorkingTogether" class="col-sm-12 control-label">Status:</label>
									<div class="col-sm-12">
										<?php
										echo HTML::selectChosen('status_ImplicationsOfNotWorkingTogether', $answer_status, $feedback->ImplicationsOfNotWorkingTogether->Status->__toString() == 'A'?$feedback->ImplicationsOfNotWorkingTogether->Status->__toString():'', false, true); ?>
									</div>
								</div>
								<div class="form-group">
									<label for="comments_ImplicationsOfNotWorkingTogether" class="col-sm-12 control-label">Comments:</label>
									<div class="col-sm-12">
										<textarea name="comments_ImplicationsOfNotWorkingTogether" rows="7" style="width: 100%;"><?php echo $feedback->ImplicationsOfNotWorkingTogether->Comments->__toString(); ?></textarea>
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
					if($feedback->ImplicationsOfNotWorkingTogether->Status->__toString() == 'NA')
						echo HTML::renderWorkbookIcons('ImplicationsOfNotWorkingTogether', false, 'btn-danger');
					else
						echo HTML::renderWorkbookIcons('ImplicationsOfNotWorkingTogether', false, 'btn-success');
					?>
				</div></div></div>

				<?php echo $wb->getPageBottomLine(); ?>
			</div> <!--.page 8 ends-->

			<h1>Personal and Team Performance</h1>
			<div class="step-content">
				<?php echo $wb->getPageTopLine(); ?>

				<div class="row"><div class="col-sm-12"><div class="pull-right">
					<?php
					if($feedback->PositiveInfluencing->Status->__toString() == 'NA')
						echo HTML::renderWorkbookIcons('PositiveInfluencing', false, 'btn-danger');
					else
						echo HTML::renderWorkbookIcons('PositiveInfluencing', false, 'btn-success');
					?>
				</div></div></div>



				<div class="row">
					<div class="col-sm-12">
						<p class="text-bold">Other methods for positively influencing a team:</p>
					</div>
				</div>
				<div class="row">
					<div class="col-sm-1"></div>
					<div class="col-sm-10">
						<div style="border: 2px solid #ffc0cb;border-radius: 15px; padding: 10px;">
							<p><strong>Leadership matter</strong></p>
							<p>Innovation comes from inspiration. Teams must be led by managers that go beyond balancing budgets and schedules. The strongest leaders set goals, priorities and roles for their teams and encourage each team member to achieve their personal best while keeping strategic goals in mind.
								Leaders must be clear on vision, know where the team is going and have a clear idea of how individual efforts lead to accomplishing important strategic goals. If done well, leadership can create a culture of continuous improvements to productivity. Leaders should be open to new ideas and willing to take risks in order to reach higher performance levels.
							</p>
						</div>
					</div>
					<div class="col-sm-1"></div>
				</div>

				<p><br></p>

				<div class="row">
					<div class="col-sm-6">
						<div style="border: 3px dotted #ffc0cb;border-radius: 15px; padding: 10px; ">
							<p><strong>Keep learning.</strong></p>
							<p>Productivity is increased when team members have all the skills they need to succeed.</p>
							<p>A team should know that skill development is expected.</p>
							<p>Letting employees stretch their wings and take on new and different roles creates a culture of support for learning and innovation.</p>
							<p>Small changes to roles can fuel enthusiasm and productivity within the team.</p>
						</div>

					</div>
					<div class="col-sm-6">
						<div style="border: 3px dotted #0044cc;border-radius: 15px; padding: 10px; ">
							<p><strong>Hire the right people.</strong></p>
							<p>Hiring well can be the single greatest factor in contributing to a company’s success. The right person will not just bring a particular skill set or knowledge base but will be a solid addition to drive forward a company’s vision and values.
							</p>
							<p>Research shows that candidates who interview with their potential team mates and high-level managers have more success from the start. Thinking in terms of retention and innovation and how this person will fit before sending the job offer will result in a collaborative team whose skills and vision meet and strengthen each other.</p>
						</div>
					</div>
				</div>
				<div class="row">
					<div class="col-sm-12">
					</div>
				</div>
				<div class="row" style="display: flex;">
					<div class="col-sm-6">
						<p class="text-bold"><img src="module_eportfolio/assets/images/wb2_img1.png" /> &nbsp;What methods do you see used in <?php echo ucfirst($wb->savers_or_sp); ?>?</p>
						<p><textarea <?php echo $feedback->PositiveInfluencing->Status->__toString() == 'A'?'pointer-events:none; ':''; ?> name="PositiveInfluencingWhat" style="width: 100%;" rows="10"><?php echo $answers->PositiveInfluencing->What->__toString(); ?></textarea> </p>
					</div>
					<div class="col-sm-6">
						<div style="border: 3px solid #0044cc;border-radius: 15px; padding: 10px; ">
							<p><strong>Build commitment.</strong></p>
							<p>A company culture that celebrates innovation and dedication to a vision and strategy will do as much to motivate employees as having a competitive business plan and strategy.</p>
							<p><br></p>
							<p>Employees whose ideas and concerns are listened to and acted upon by management will feel connected and part of the decision making process.</p>
							<p><br></p>
							<p>Feeling part of goes a long way toward building commitment and dedication</p>
						</div>
					</div>
				</div>
				<div class="row">
					<div class="col-sm-12">
						<p class="text-bold"><img class="pull-left" src="module_eportfolio/assets/images/wb2_img1.png" /> <br>&nbsp;Why it is important to listen positively? Why should we value differences of opinion and challenges? Make some notes in the box:</p>
						<textarea <?php echo $feedback->PositiveInfluencing->Status->__toString() == 'A'?'pointer-events:none; ':''; ?> name="PositiveInfluencingWhy" style="width: 100%" rows="5"><?php echo $answers->PositiveInfluencing->Why->__toString(); ?></textarea>
					</div>
				</div>

				<?php if($wb->id != '' && $_SESSION['user']->type == User::TYPE_ASSESSOR && ($wb->wb_status == Workbook::STATUS_LEARNER_COMPLETED || $wb->wb_status == Workbook::STATUS_BEING_CHECKED || $wb->wb_status == Workbook::STATUS_IV_REJECTED)) {?>
				<div class="row well">
					<div class="col-sm-12">
						<div class="box box-success box-solid">
							<div class="box-header with-border"><h3 class="box-title">Assessor Feedback</h3></div>
							<div class="box-body assessorFeedback" <?php echo $feedback->PositiveInfluencing->Status->__toString() == 'A'?'style="pointer-events:none;"':''; ?>>
								<div class="form-group">
									<label for="status_PositiveInfluencing" class="col-sm-12 control-label">Status:</label>
									<div class="col-sm-12">
										<?php
										echo HTML::selectChosen('status_PositiveInfluencing', $answer_status, $feedback->PositiveInfluencing->Status->__toString() == 'A'?$feedback->PositiveInfluencing->Status->__toString():'', false, true); ?>
									</div>
								</div>
								<div class="form-group">
									<label for="comments_PositiveInfluencing" class="col-sm-12 control-label">Comments:</label>
									<div class="col-sm-12">
										<textarea name="comments_PositiveInfluencing" rows="7" style="width: 100%;"><?php echo $feedback->PositiveInfluencing->Comments->__toString(); ?></textarea>
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
					if($feedback->PositiveInfluencing->Status->__toString() == 'NA')
						echo HTML::renderWorkbookIcons('PositiveInfluencing', false, 'btn-danger');
					else
						echo HTML::renderWorkbookIcons('PositiveInfluencing', false, 'btn-success');
					?>
				</div></div></div>

				<?php echo $wb->getPageBottomLine(); ?>
			</div> <!--.page 9 ends-->

			<h1>Personal and Team Performance</h1>
			<div class="step-content">
				<?php echo $wb->getPageTopLine(); ?>

				<div class="row"><div class="col-sm-12"><div class="pull-right">
					<?php
					if($feedback->RolesResp->Status->__toString() == 'NA')
						echo HTML::renderWorkbookIcons('RolesResp', false, 'btn-danger');
					else
						echo HTML::renderWorkbookIcons('RolesResp', false, 'btn-success');
					?>
				</div></div></div>

				<div class="row" <?php //echo $feedback->RolesResp->Status->__toString() == 'A'?'style="pointer-events:none;"':''; ?>>
					<br>
					<div class="col-sm-12" <?php echo $feedback->RolesResp->Status->__toString() == 'A'?'style="pointer-events:none;"':''; ?>>
						<p class="text-bold">The roles and responsibilities of team members</p>
						<p>There are a number of different roles within your store and each comes with their own responsibilities to ensure all tasks are completed and to contribute to the success of the team/store.</p>
						<p class="text-bold"><img class="pull-left" src="module_eportfolio/assets/images/wb2_img1.png" /> <br>&nbsp;Listed below are a number of roles. Next to each you need to list two responsibilities for each one and make sure you give different responsibilities to each role.</p>
						<div class="col-sm-12 table-responsive">
							<table class="table text-center">
								<tr>
									<td style='width: 35%; border: 3px solid #00bfff;vertical-align: middle;font-size:14.0pt;padding:10px;text-align: center;background-color: lightblue'><b>Roles</b></td>
									<td style="width: 5%; vertical-align: middle;"><img class="img-responsive" src="module_eportfolio/assets/images/wb6_pg8_img2.png" /></td>
									<td style="border: 3px solid #00bfff;vertical-align: middle;font-size:14.0pt;padding:10px;text-align: center;background-color: lightgreen"><p class="text-bold">Responsibilities</p></td>
								</tr>
								<tr>
									<td style='border: 3px solid #00bfff;vertical-align: middle;font-size:14.0pt;padding:10px;text-align: center;'><b>Store Manager</b></td>
									<td style="width: 5%; vertical-align: middle;"><img class="img-responsive" src="module_eportfolio/assets/images/wb6_pg8_img2.png" /></td>
									<td style="border: 3px solid #00bfff;vertical-align: middle;font-size:14.0pt;padding:10px;text-align: center;"><textarea name="SM" style="width: 100%;"><?php echo $answers->RolesResp->Roles->SM->__toString(); ?></textarea></td>
								</tr>
								<tr>
									<td style='border: 3px solid #00bfff;vertical-align: middle;font-size:14.0pt;padding:10px;text-align: center;'><b>Assistant Manager</b></td>
									<td style="width: 5%; vertical-align: middle;"><img class="img-responsive" src="module_eportfolio/assets/images/wb6_pg8_img2.png" /></td>
									<td style="border: 3px solid #00bfff;vertical-align: middle;font-size:14.0pt;padding:10px;text-align: center;"><textarea name="AM" style="width: 100%;"><?php echo $answers->RolesResp->Roles->AM->__toString(); ?></textarea></td>
								</tr>
								<tr>
									<td style='border: 3px solid #00bfff;vertical-align: middle;font-size:14.0pt;padding:10px;text-align: center;'><b>Team Leader</b></td>
									<td style="width: 5%; vertical-align: middle;"><img class="img-responsive" src="module_eportfolio/assets/images/wb6_pg8_img2.png" /></td>
									<td style="border: 3px solid #00bfff;vertical-align: middle;font-size:14.0pt;padding:10px;text-align: center;"><textarea name="TL" style="width: 100%;"><?php echo $answers->RolesResp->Roles->TL->__toString(); ?></textarea></td>
								</tr>
								<tr>
									<td style='border: 3px solid #00bfff;vertical-align: middle;font-size:14.0pt;padding:10px;text-align: center;'><b>Beauty Specialist</b><p class="small text-muted">Write N/A if not applicable</p></td>
									<td style="width: 5%; vertical-align: middle;"><img class="img-responsive" src="module_eportfolio/assets/images/wb6_pg8_img2.png" /></td>
									<td style="border: 3px solid #00bfff;vertical-align: middle;font-size:14.0pt;padding:10px;text-align: center;"><textarea name="BS" style="width: 100%;"><?php echo $answers->RolesResp->Roles->BS->__toString(); ?></textarea></td>
								</tr>
								<tr>
									<td style='border: 3px solid #00bfff;vertical-align: middle;font-size:14.0pt;padding:10px;text-align: center;'><b>Sales Assistant</b></td>
									<td style="width: 5%; vertical-align: middle;"><img class="img-responsive" src="module_eportfolio/assets/images/wb6_pg8_img2.png" /></td>
									<td style="border: 3px solid #00bfff;vertical-align: middle;font-size:14.0pt;padding:10px;text-align: center;"><textarea name="SA" style="width: 100%;"><?php echo $answers->RolesResp->Roles->SA->__toString(); ?></textarea></td>
								</tr>
								<tr>
									<td style='border: 3px solid #00bfff;vertical-align: middle;font-size:14.0pt;padding:10px;text-align: center;'><b>Apprentice</b></td>
									<td style="width: 5%; vertical-align: middle;"><img class="img-responsive" src="module_eportfolio/assets/images/wb6_pg8_img2.png" /></td>
									<td style="border: 3px solid #00bfff;vertical-align: middle;font-size:14.0pt;padding:10px;text-align: center;"><textarea name="App" style="width: 100%;"><?php echo $answers->RolesResp->Roles->App->__toString(); ?></textarea></td>
								</tr>
								<tr>
									<td style='border: 3px solid #00bfff;vertical-align: middle;font-size:14.0pt;padding:10px;text-align: center;'><b>Pharmacist</b><p class="small text-muted">Write N/A if not applicable</p></td>
									<td style="width: 5%; vertical-align: middle;"><img class="img-responsive" src="module_eportfolio/assets/images/wb6_pg8_img2.png" /></td>
									<td style="border: 3px solid #00bfff;vertical-align: middle;font-size:14.0pt;padding:10px;text-align: center;"><textarea name="Pha" style="width: 100%;"><?php echo $answers->RolesResp->Roles->Pha->__toString(); ?></textarea> </td>
								</tr>
							</table>
						</div>
						<p class="text-bold"><img class="pull-left" src="module_eportfolio/assets/images/wb2_img1.png" /> <br>&nbsp;How do you demonstrate an interest in other team members' roles?</p>
						<textarea name="HowToShowInterest" style="width: 100%" rows="5"><?php echo $answers->RolesResp->HowToShowInterest->__toString(); ?></textarea>
						<p class="text-bold"><img class="pull-left" src="module_eportfolio/assets/images/wb2_img1.png" /> <br>&nbsp;What information and resources do you need to know in store? Below are examples of either the type of information you may need or where you can go to find it. Fill in the blanks.</p>
						<div class="table-responsive">
							<table class="table table-bordered">
								<tr><th>Type of information</th><th>Where it is located / who can you ask?</th></tr>
								<tr><td>Equal Opportunities policy </td><td><textarea name="EqualOpportunitiesPolicy" style="width: 100%;"><?php echo $answers->RolesResp->EqualOpportunitiesPolicy->__toString(); ?></textarea> </td></tr>
								<tr><td><textarea name="TheHub" style="width: 100%;"><?php echo $answers->RolesResp->TheHub->__toString(); ?></textarea> </td><td><?php echo $wb->savers_or_sp == 'savers' ? 'Back office' : 'The Hub' ?></td></tr>
								<tr><td><?php echo $wb->savers_or_sp == 'savers' ? 'SAS product information' : 'Daily Sales Target' ?></td><td><textarea name="DailySalesTarget" style="width: 100%;"><?php echo $answers->RolesResp->DailySalesTarget->__toString(); ?></textarea> </td></tr>
								<tr><td><textarea name="StoreManager" style="width: 100%;"><?php echo $answers->RolesResp->StoreManager->__toString(); ?></textarea> </td><td>Store manager</td></tr>
							</table>
						</div>
						<p class="text-bold">Add some of your own below:</p>
						<div class="table-responsive">
							<table class="table table-bordered">
								<tr><th>Type of information</th><th>Where it is located / who can you ask?</th></tr>
								<tr><td><textarea name="YourOwnRolesType1" style="width: 100%;"><?php echo $answers->RolesResp->YourOwnRoles->Set1->Type->__toString(); ?></textarea> </td><td><textarea name="YourOwnRolesWhereLocated1" style="width: 100%;"><?php echo $answers->RolesResp->YourOwnRoles->Set1->WhereLocated->__toString(); ?></textarea> </td></tr>
								<tr><td><textarea name="YourOwnRolesType2" style="width: 100%;"><?php echo $answers->RolesResp->YourOwnRoles->Set2->Type->__toString(); ?></textarea> </td><td><textarea name="YourOwnRolesWhereLocated2" style="width: 100%;"><?php echo $answers->RolesResp->YourOwnRoles->Set2->WhereLocated->__toString(); ?></textarea> </td></tr>
							</table>
						</div>
						<img class="img-responsive center-block" src="module_eportfolio/assets/images/wb_r05_pg10_img1.png" />
					</div>
				</div>

				<?php if($wb->id != '' && $_SESSION['user']->type == User::TYPE_ASSESSOR && ($wb->wb_status == Workbook::STATUS_LEARNER_COMPLETED || $wb->wb_status == Workbook::STATUS_BEING_CHECKED || $wb->wb_status == Workbook::STATUS_IV_REJECTED)) {?>
				<div class="row well">
					<div class="col-sm-12">
						<div class="box box-success box-solid">
							<div class="box-header with-border"><h3 class="box-title">Assessor Feedback</h3></div>
							<div class="box-body assessorFeedback" <?php echo $feedback->RolesResp->Status->__toString() == 'A'?'style="pointer-events:none;"':''; ?>>
								<div class="form-group">
									<label for="status_RolesResp" class="col-sm-12 control-label">Status:</label>
									<div class="col-sm-12">
										<?php echo HTML::selectChosen('status_RolesResp', $answer_status, $feedback->RolesResp->Status->__toString() == 'A'?$feedback->RolesResp->Status->__toString():'', false, true); ?>
									</div>
								</div>
								<div class="form-group">
									<label for="comments_RolesResp" class="col-sm-12 control-label">Comments:</label>
									<div class="col-sm-12">
										<textarea name="comments_RolesResp" rows="7" style="width: 100%;"><?php echo $feedback->RolesResp->Comments->__toString(); ?></textarea>
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
					if($feedback->RolesResp->Status->__toString() == 'NA')
						echo HTML::renderWorkbookIcons('RolesResp', false, 'btn-danger');
					else
						echo HTML::renderWorkbookIcons('RolesResp', false, 'btn-success');
					?>
				</div></div></div>

				<?php echo $wb->getPageBottomLine(); ?>
			</div> <!--.page 10 ends-->

			<h1>Personal and Team Performance</h1>
			<div class="step-content">
				<?php echo $wb->getPageTopLine(); ?>
				<div class="row"><div class="col-sm-12"><div class="pull-right">
					<?php
					if($feedback->Questions->Status->__toString() == 'NA')
						echo HTML::renderWorkbookIcons('Questions', false, 'btn-danger');
					else
						echo HTML::renderWorkbookIcons('Questions', false, 'btn-success');
					?>
				</div></div></div>
				<br>
				<div class="row" <?php echo $feedback->Questions->Status->__toString() == 'A'?'style="pointer-events:none;"':''; ?>>
					<div class="col-sm-12">
						<p class="text-bold"><img class="pull-left" src="module_eportfolio/assets/images/wb2_img1.png" /> <br>&nbsp;Why do you think it is important to fulfill agreements made with team members or to keep them informed when there is a problem?</p>
						<textarea name="Question1" style="width: 100%" rows="5"><?php echo $answers->Questions->Question1->__toString(); ?></textarea>
						<p class="text-bold"><img class="pull-left" src="module_eportfolio/assets/images/wb2_img1.png" /> <br>&nbsp;Give an example of when you have fulfilled an agreement with a team member.</p>
						<textarea name="Question2" style="width: 100%" rows="5"><?php echo $answers->Questions->Question2->__toString(); ?></textarea>
						<p class="text-bold"><img class="pull-left" src="module_eportfolio/assets/images/wb2_img1.png" /> <br>&nbsp;Think about how you have built effective working relationships with your team members. How did you build effective working relationships when you first started working with your own team? Is there anything that you thought worked well or not so well? Give some examples.</p>
						<textarea name="Question3" style="width: 100%" rows="5"><?php echo $answers->Questions->Question3->__toString(); ?></textarea>
						<p class="text-bold"><img class="pull-left" src="module_eportfolio/assets/images/wb2_img1.png" /> <br>&nbsp;Think of a time when you have set an example. Record the situation and how you demonstrated a professional and positive approach.</p>
						<textarea name="Question4" style="width: 100%" rows="5"><?php echo $answers->Questions->Question4->__toString(); ?></textarea>
						<p class="text-bold"><img class="pull-left" src="module_eportfolio/assets/images/wb2_img1.png" /> <br>&nbsp;Think about a time when you have helped to strengthen team dynamics. This may have been through demonstrating personal commitment. Detail what happened below.</p>
						<textarea name="Question5" style="width: 100%" rows="5"><?php echo $answers->Questions->Question5->__toString(); ?></textarea>
						<p class="text-bold"><img class="pull-left" src="module_eportfolio/assets/images/wb2_img1.png" /> <br>&nbsp;Give an example of when you have taken a positive approach to helping team members to support the business, offering help them where possible. (Your own store team).</p>
						<textarea name="Question6" style="width: 100%" rows="5"><?php echo $answers->Questions->Question6->__toString(); ?></textarea>
						<p class="text-bold"><img class="pull-left" src="module_eportfolio/assets/images/wb2_img1.png" /> <br>&nbsp;Give an example of when you have taken a positive approach to helping team members to support the business, offering help them where possible. (The wider team – area manager, head office or stores in your area?)</p>
						<textarea name="Question7" style="width: 100%" rows="5"><?php echo $answers->Questions->Question7->__toString(); ?></textarea>
						<p class="text-bold"><img class="pull-left" src="module_eportfolio/assets/images/wb2_img1.png" /> <br>&nbsp;Give an example of working with another team member – they might be new to the business or have never done a task before – what did you do?</p>
						<textarea name="Question8" style="width: 100%" rows="5"><?php echo $answers->Questions->Question8->__toString(); ?></textarea>
					</div>
				</div>
				<?php if($wb->id != '' && $_SESSION['user']->type == User::TYPE_ASSESSOR && ($wb->wb_status == Workbook::STATUS_LEARNER_COMPLETED || $wb->wb_status == Workbook::STATUS_BEING_CHECKED || $wb->wb_status == Workbook::STATUS_IV_REJECTED)) {?>
				<div class="row well">
					<div class="col-sm-12">
						<div class="box box-success box-solid">
							<div class="box-header with-border"><h3 class="box-title">Assessor Feedback</h3></div>
							<div class="box-body assessorFeedback" <?php echo $feedback->Questions->Status->__toString() == 'A'?'style="pointer-events:none;"':''; ?>>
								<div class="form-group">
									<label for="status_Questions" class="col-sm-12 control-label">Status:</label>
									<div class="col-sm-12">
										<?php echo HTML::selectChosen('status_Questions', $answer_status, $feedback->Questions->Status->__toString() == 'A'?$feedback->Questions->Status->__toString():'', false, true); ?>
									</div>
								</div>
								<div class="form-group">
									<label for="comments_Questions" class="col-sm-12 control-label">Comments:</label>
									<div class="col-sm-12">
										<textarea name="comments_Questions" rows="7" style="width: 100%;"><?php echo $feedback->Questions->Comments->__toString(); ?></textarea>
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
					if($feedback->Questions->Status->__toString() == 'NA')
						echo HTML::renderWorkbookIcons('Questions', false, 'btn-danger');
					else
						echo HTML::renderWorkbookIcons('Questions', false, 'btn-success');
					?>
				</div></div></div>
				<?php echo $wb->getPageBottomLine(); ?>
			</div> <!--.page 10 ends-->

			<h1>Personal and Team Performance</h1>
			<div class="step-content">
				<?php echo $wb->getPageTopLine(); ?>
				<div class="row"><div class="col-sm-12"><div class="pull-right">
					<?php
					if($feedback->Objective->Status->__toString() == 'NA')
						echo HTML::renderWorkbookIcons('Objective', false, 'btn-danger');
					else
						echo HTML::renderWorkbookIcons('Objective', false, 'btn-success');
					?>
				</div></div></div>
				<br>
				<div class="row">
					<div class="col-sm-4 col-sm-offset-4">
						<div class="text-center" style="padding: 15px; border: red solid 2px;">
							<h3>An objective</h3>
							<p><i>A specific result that a person or system aims to achieve within a time frame and with available resources</i></p>
						</div>
					</div>
				</div>
				<div class="row">
					<div class="col-sm-12" <?php echo $feedback->Objective->Status->__toString() == 'A'?'style="pointer-events:none;"':''; ?>>
						<p><b>Work objectives </b>are the tasks that you need to achieve at work. Some objectives are tasks which you and your team must do on a daily basis such as stock replenishment, facing up, serving customers at the till or dealing with their enquiries.</p>
						<p>These work objectives are agreed when you start your job and are included in your job description.</p>
						<p class="text-bold"><img src="module_eportfolio/assets/images/wb2_img1.png" /> &nbsp;List 3 other daily work objectives below.</p>
						<p><textarea name="DailyWorkObj" style="width: 100%;" rows="5"><?php echo $answers->Objective->DailyWorkObj->__toString(); ?></textarea> </p>
						<p class="text-bold"><img src="module_eportfolio/assets/images/wb2_img1.png" /> &nbsp;Other work objectives may be done every few days, weekly, fortnightly or even monthly.<br><b>List some examples below.</b></p>
						<p><textarea name="OtherWorkObj" style="width: 100%;" rows="5"><?php echo $answers->Objective->OtherWorkObj->__toString(); ?></textarea> </p>
						<p class="text-bold"><img src="module_eportfolio/assets/images/wb2_img1.png" /> &nbsp;Objectives relating to business targets will also be set for the team. What are they?</p>
						<p><textarea name="BusinessWorkObj" style="width: 100%;" rows="5"><?php echo $answers->Objective->BusinessWorkObj->__toString(); ?></textarea> </p>
					</div>
				</div>
				<div class="row">
					<div class="col-sm-12">
						<h1>Your role and responsibility</h1>
						<p><strong>SMART objectives</strong></p>
						<p>Objectives need to be agreed and to do so they must be communicated between managers, team leaders and any team members they are relevant to. This could be done in a team briefing or on a one to one basis. The important thing is to ask questions if you are unsure and to ensure you have all the information you need before you carry out a task.</p>
					</div>
				</div>
				<div class="row">
					<div class="col-sm-12">
						<h3>Any objective you agree to should be SMART</h3>
					</div>
					<div class="col-sm-12">
						<table class="">
							<tr><td class="text-3d pull-right">S</td><td>Specific – you need to know exactly what you need to do. What, where, how, when, who etc.</td></tr>
							<tr><td class="text-3d pull-right">M</td><td>Measurable – solid criteria to measure progress against so you will know if you have achieved your goal.</td></tr>
							<tr><td class="text-3d pull-right">A</td><td>Achievable – is it possible? Can you do it?</td></tr>
							<tr><td class="text-3d pull-right">R</td><td>Realistic – you need to be willing and able to make it happen.</td></tr>
							<tr><td class="text-3d pull-right">T</td><td>Time – when do you need to complete the objective for?</td></tr>
						</table>
					</div>
					<div class="col-sm-12 text-center text-bold">
						<p>
							<img src="module_eportfolio/assets/images/wb2_img1.png" /> &nbsp;
							The table below has some examples of work objectives. Tick the boxes to show which ones are SMART objectives. If they’re not SMART give a reason in the comments box. &nbsp;
							<img src="module_eportfolio/assets/images/wb4_img1.png" />
						</p>
					</div>
					<div class="col-sm-12 table-responsive" <?php echo $feedback->Objective->Status->__toString() == 'A'?'style="pointer-events:none;"':''; ?>>
						<table class="table row-border">
							<tr class="text-center"><th>Objective</th><th>SMART</th><th>Not SMART</th><th>Comments</th></tr>
							<tr><td>Fill up some of the hair dyes</td><td></td><td><span class="fa fa-check"></span> </td><td>Not specific as you don’t know what some hair dyes means.  You also don’t know how long you have to do the task. </td></tr>
							<tr>
								<td>It's 9am now so I would like you to sell 3 razor packs by the end of your shift at 5pm.</td>
								<td><input type="radio" name="Objective1Type[]" value="S" <?php echo $answers->Objective->SMARTObjectives->Objective1->Type->__toString() == 'S' ? 'checked="checked" ': ''; ?> /></td>
								<td><input type="radio" name="Objective1Type[]" value="NS" <?php echo $answers->Objective->SMARTObjectives->Objective1->Type->__toString() == 'NS' ? 'checked="checked" ': ''; ?> /></td>
								<td><textarea name="Objective1Comments" rows="3" style="width: 100%;"><?php echo $answers->Objective->SMARTObjectives->Objective1->Comments->__toString(); ?></textarea> </td>
							</tr>
							<tr>
								<td>You have one hour to put 3 cages of stock out on the skin section.</td>
								<td><input type="radio" name="Objective2Type[]" value="S" <?php echo $answers->Objective->SMARTObjectives->Objective2->Type->__toString() == 'S' ? 'checked="checked" ': ''; ?> /></td>
								<td><input type="radio" name="Objective2Type[]" value="NS" <?php echo $answers->Objective->SMARTObjectives->Objective2->Type->__toString() == 'NS' ? 'checked="checked" ': ''; ?> /></td>
								<td><textarea name="Objective2Comments" rows="3" style="width: 100%;"><?php echo $answers->Objective->SMARTObjectives->Objective2->Comments->__toString(); ?></textarea> </td></tr>
							<tr>
								<td>Please put the POS out at the front of the store</td>
								<td><input type="radio" name="Objective3Type[]" value="S" <?php echo $answers->Objective->SMARTObjectives->Objective3->Type->__toString() == 'S' ? 'checked="checked" ': ''; ?> /></td>
								<td><input type="radio" name="Objective3Type[]" value="NS" <?php echo $answers->Objective->SMARTObjectives->Objective3->Type->__toString() == 'NS' ? 'checked="checked" ': ''; ?> /></td>
								<td><textarea name="Objective3Comments" rows="3" style="width: 100%;"><?php echo $answers->Objective->SMARTObjectives->Objective3->Comments->__toString(); ?></textarea> </td>
							</tr>
							<tr><th colspan="4" class="bg-blue">Can you think of a SMART objective of your own? Write it below:</th></tr>
							<tr><td colspan="4"><textarea name="YourSMARTObjective" style="width: 100%;" rows="3"><?php echo $answers->Objective->YourSMARTObjective->__toString(); ?></textarea> </td></tr>
						</table>
					</div>
				</div>

				<div class="row" <?php echo $feedback->Objective->Status->__toString() == 'A'?'style="pointer-events:none;"':''; ?>>
					<div class="col-sm-12 table-responsive">
						<img class="img-responsive center-block" src="module_eportfolio/assets/images/wb5_pg5_img1.png" />
					</div>
					<div class="col-sm-12">
						<p class="text-bold"><img src="module_eportfolio/assets/images/wb2_img1.png" /> &nbsp;What would happen if you didn’t have work objectives? &nbsp;</p>
						<p><textarea name="ImpactOfNoWorkObjective" style="width: 100%;" rows="3"><?php echo $answers->Objective->ImpactOfNoWorkObjective->__toString(); ?></textarea></p>
						<p class="text-bold"><img src="module_eportfolio/assets/images/wb2_img1.png" /> &nbsp;Think about your own role and your responsibilities. Give 2 examples of how these have an impact on the team’s goals? &nbsp;</p>
						<p><textarea name="YourRoleAndResponsibilitiesImpactOnTeamGoal" style="width: 100%;" rows="5"><?php echo $answers->Objective->YourRoleAndResponsibilitiesImpactOnTeamGoal->__toString(); ?></textarea></p>
						<p class="text-bold">The benefits to the business of more effective ways of working</p>
						<p>All the tasks you do in store have a process to be followed to enable you to achieve them. The process may be very simple or have a number of steps to get to the end result. You may wonder why you need to follow all the steps or think you can do it a different way to get to the end result.</p>
						<p>In some cases, the process and all the steps are there to ensure regulations are complied with and can’t be changed however with other work objectives, thinking of more effective ways of working could bring increased success to a business.</p>
						<img class="img-responsive center-block" src="module_eportfolio/assets/images/wb_r05_pg15_img1.png" />
						<p class="text-bold"><img class="pull-left" src="module_eportfolio/assets/images/wb2_img1.png" /> <br>&nbsp;Can you think of one more benefit to the business of more effective ways of working?</p>
						<textarea name="OneMoreBenefit" style="width: 100%" rows="5"><?php echo $answers->Objective->OneMoreBenefit->__toString(); ?></textarea>
					</div>
				</div>
				<?php if($wb->id != '' && $_SESSION['user']->type == User::TYPE_ASSESSOR && ($wb->wb_status == Workbook::STATUS_LEARNER_COMPLETED || $wb->wb_status == Workbook::STATUS_BEING_CHECKED || $wb->wb_status == Workbook::STATUS_IV_REJECTED)) {?>
				<div class="row well">
					<div class="col-sm-12">
						<div class="box box-success box-solid">
							<div class="box-header with-border"><h3 class="box-title">Assessor Feedback</h3></div>
							<div class="box-body assessorFeedback" <?php echo $feedback->Objective->Status->__toString() == 'A'?'style="pointer-events:none;"':''; ?>>
								<div class="form-group">
									<label for="status_Objective" class="col-sm-12 control-label">Status:</label>
									<div class="col-sm-12">
										<?php echo HTML::selectChosen('status_Objective', $answer_status, $feedback->Objective->Status->__toString() == 'A'?$feedback->Objective->Status->__toString():'', false, true); ?>
									</div>
								</div>
								<div class="form-group">
									<label for="comments_Objective" class="col-sm-12 control-label">Comments:</label>
									<div class="col-sm-12">
										<textarea name="comments_Objective" rows="7" style="width: 100%;"><?php echo $feedback->Objective->Comments->__toString(); ?></textarea>
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
					if($feedback->Objective->Status->__toString() == 'NA')
						echo HTML::renderWorkbookIcons('Objective', false, 'btn-danger');
					else
						echo HTML::renderWorkbookIcons('Objective', false, 'btn-success');
					?>
				</div></div></div>

				<?php echo $wb->getPageBottomLine(); ?>
			</div> <!--.page 10 ends-->

			<h1>Personal and Team Performance</h1>
			<div class="step-content">
				<?php echo $wb->getPageTopLine(); ?>
				<div class="row"><div class="col-sm-12"><div class="pull-right">
					<?php
					if($feedback->PDP->Status->__toString() == 'NA')
						echo HTML::renderWorkbookIcons('PDP', false, 'btn-danger');
					else
						echo HTML::renderWorkbookIcons('PDP', false, 'btn-success');
					?>
				</div></div></div>
				<br>
				<div class="row">
					<div class="col-sm-4 col-sm-offset-4">
						<div class="bg-red text-center" style="padding: 15px;">
							<h3>Personal Development Plan (PDP)</h3>
							<p><i>Personal development planning is the process of creating an action plan for personal development within the context of a career, education, relationship or for self-improvement</i></p>
						</div>
					</div>
				</div>
				<div class="row">
					<div class="col-sm-12">
						<p class="text-bold">A Personal Development Plan (PDP) is completed with a line manager and is usually broken down into columns to show:</p>
						<div class="table-responsive">
							<table class="table table-bordered">
								<tr><th>Objectives – What needs to be achieved?</th><th>How will I do this? – What resources are required?</th><th>Target – when will I complete this development?</th><th>Review – how have I used what I learned?</th></tr>
								<tr><td>E.G. Learn how to use the HHT</td><td>Training to be given by Mary using the HHT </td><td>(Date/time)</td><td>Used HHT to process price checking </td></tr>
								<tr><td>E.G. Learn the process for implementing planograms</td><td>Shadow Paul completing a planogram update</td><td>(Date/time)</td><td>Assist store team in store moves of sections/plans  pre- Christmas</td></tr>
							</table>
						</div>
					</div>
				</div>
				<div class="row">
					<div class="col-sm-12">
						<p>As with work objectives, personal objectives should also be SMART (Specific, Measurable, Achievable, Realistic and Timely).  If not then the chances of achieving the objectives is reduced and the individual could become demotivated. The objective could be suggested by a manager but the individual needs to buy in to and believe they can achieve or it could be a wasted exercise.</p>
					</div>
				</div>
				<div class="row">
					<div class="col-sm-6">
						<p><strong>Benefits of a PDP to the individuals</strong></p>
						<ul style="margin-left: 15px; margin-bottom: 15px;">
							<li>It can help plan their development</li>
							<li>It can help agree what they need from the business in order to develop</li>
							<li>They can learn new skills that will be useful in their work and help with career progression</li>
							<li>They will feel valued by their employer</li>
						</ul>
					</div>
					<div class="col-sm-6">
						<p><strong>Benefits of a PDP to the retail business</strong></p>
						<ul style="margin-left: 15px; margin-bottom: 15px;">
							<li>They can identify personal objectives for individuals which will help meet the organisations business objectives.</li>
							<li>Employees will have improved and wide ranging skills.</li>
							<li>Employees will have an increased commitment to the business.</li>
							<li>Employees will be loyal as they will see that they have a career rather than a job.</li>
						</ul>
					</div>
				</div>
				<div class="row">
					<div class="col-sm-6">

						<p class="text-bold">How to identify own learning needs and improve own performance and identity</p>
						<p>Identifying your development needs can be challenging.  Often, we find ourselves looking at what training courses are available and deciding which of those would be most helpful. In fact, it is better to try and identify what the development need is and then to work out ways of meeting that need, which may or may not be a training course.</p>
						<p class="text-bold">There are 3 stages to identifying your needs:</p>
						<p class="text-bold">1. Identify what skills, knowledge and behaviours are ‘required' for you to do your job well.</p>
						<p>Every role in customer service has a job description. A job description will list the things that you are expected to do. A person specification will identify the skills, experience, knowledge and behaviours that you need to do that job well.</p>
						<p>At this stage, it's also worth thinking about the skills, knowledge and behaviours that you may need to develop in the future in your current job. You may know, for example, that you are interested in a working your way up from a sales assistant to a store manager.</p>
						<p><img src="module_eportfolio/assets/images/wb2_pg2_img1.png" /></p>
					</div>
					<div class="col-sm-6">
						<img src="module_eportfolio/assets/images/wb2_pg2_img2.png" />
						<p class="text-bold">2. Look at the skills, knowledge and behaviours you actually have now. </p>
						<p>Once you have identified what you are required to do, ask yourself how effectively you match against your job description. You could consider talking this through with a friend / colleague or with your manager.</p>
						<p>It's important to ask yourself some questions at this stage and answer honestly.  Are there areas of your work, for example, where developing more confidence would make a real difference to your success in your job?  Are there knowledge, skills and behaviours that you only need on occasion that would benefit from some development?  Can you identify areas where you feel confident and believe you perform well that could be an even greater strength for you with some development?</p>
						<p class="text-bold">3. Compare ‘actual' with ‘required' to identify the gaps. These are your development needs. </p>
						<p>Finally, try and be as specific as possible about what you need to do differently. This will really help you when you are deciding how to best address your development needs. It will also help you review and measure your success.</p>
						<p>Think about what you want to do but also why. Why do you want to achieve? What is the end result you are looking for? How are you going to do it? This will all help you when deciding on the best solution to address your needs. It will also help you to look back and review what you have done to see if it was a success.</p>
					</div>
				</div>

				<div class="row">
					<div class="col-sm-12">
						<p class="text-bold">How to identify own learning needs and improve own performance and identity</p>
						<p>Other opportunities to help you identify learning needs are:</p>
						<p><strong>Induction programmes</strong> - think back to your own induction. You might have been daunted by all the things you needed to learn or you might have realised you already knew how to do some of the things you were being asked to do. By doing this you will have been able to focus on the things you hadn’t done before and learn new things which would enhance your own performance in your role.</p>
						<p><strong>Changes - </strong> throughout your career you will come across changes to the business or to the role that will highlight things you need to learn. It might be something as simple as new products being introduced. You will need to learn about them so you can sell them to the customers. This additional learning will enable you do your job better and give you more confidence in your role.</p>
						<p><strong>Problems - </strong> there will be occasions when problems at work will bring your learning needs to light. You may identify the problem but not know how to deal with it as it isn’t something you have dealt with before. You can then use this as an opportunity to increase your knowledge and improve your performance and avoid similar problems in the future.</p>
						<p><strong>Reviews - </strong>  During the course of this qualification you will receive formal reviews of your performance from your manager and assessor. You will need to use this review process to inform your PDP of any changes.
						<p>It is good practice to add any new learning needs to your personal development plan (PDP) as soon as you identify them so they don’t get forgotten and you can plan in how and when you are going to address them.</p>
					</div>
				</div>
				<div class="row">
					<div class="col-sm-6">
						<p class="text-bold"><img class="pull-left" src="module_eportfolio/assets/images/wb2_img1.png" /> <br>&nbsp;How do you think you personally demonstrate drive and commitment?</p><br>
						<textarea name="PDPQ1" style="width: 100%" rows="5"><?php echo $answers->PDP->Q1->__toString(); ?></textarea>
					</div>
					<div class="col-sm-6">
						<p class="text-bold"><img class="pull-left" src="module_eportfolio/assets/images/wb2_img1.png" /> <br>&nbsp;Give some examples of how and when you have taken ownership for your own performance and personal development.</p>
						<textarea name="PDPQ2" style="width: 100%" rows="5"><?php echo $answers->PDP->Q2->__toString(); ?></textarea>
					</div>
				</div>
				<div class="row">
					<div class="col-sm-12"><img class="img-responsive center-block" src="module_eportfolio/assets/images/wb2_pg17_img1.png" /></p></div>
				</div>
				<?php if($wb->id != '' && $_SESSION['user']->type == User::TYPE_ASSESSOR && ($wb->wb_status == Workbook::STATUS_LEARNER_COMPLETED || $wb->wb_status == Workbook::STATUS_BEING_CHECKED || $wb->wb_status == Workbook::STATUS_IV_REJECTED)) {?>
				<div class="row well">
					<div class="col-sm-12">
						<div class="box box-success box-solid">
							<div class="box-header with-border"><h3 class="box-title">Assessor Feedback</h3></div>
							<div class="box-body assessorFeedback" <?php echo $feedback->PDP->Status->__toString() == 'A'?'style="pointer-events:none;"':''; ?>>
								<div class="form-group">
									<label for="status_PDP" class="col-sm-12 control-label">Status:</label>
									<div class="col-sm-12">
										<?php echo HTML::selectChosen('status_PDP', $answer_status, $feedback->PDP->Status->__toString() == 'A'?$feedback->PDP->Status->__toString():'', false, true); ?>
									</div>
								</div>
								<div class="form-group">
									<label for="comments_PDP" class="col-sm-12 control-label">Comments:</label>
									<div class="col-sm-12">
										<textarea name="comments_PDP" rows="7" style="width: 100%;"><?php echo $feedback->PDP->Comments->__toString(); ?></textarea>
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
					if($feedback->PDP->Status->__toString() == 'NA')
						echo HTML::renderWorkbookIcons('PDP', false, 'btn-danger');
					else
						echo HTML::renderWorkbookIcons('PDP', false, 'btn-success');
					?>
				</div></div></div>
				<?php echo $wb->getPageBottomLine(); ?>
			</div> <!--.page 10 ends-->

			<h1>Personal and Team Performance</h1>
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
								<p class="text-bold">Now you have completed the section on Personal and Team Performance answer the following questions:</p>
								<p><strong>Unit 6 - 1.1<br>Explain how personal performance contributes to the success of the business</strong></p>
								<p><textarea name="Unit1_1" style="width: 100%" rows="12"><?php echo $answers->QualificationQuestions->Unit1_1->__toString(); ?></textarea></p>
								<p><strong>Unit 6 - 1.2<br>Identify how to improve own methods of working</strong></p>
								<p><textarea name="Unit1_2" style="width: 100%" rows="12"><?php echo $answers->QualificationQuestions->Unit1_2->__toString(); ?></textarea></p>
								<p><strong>Unit 6 - 1.3<br>Explain how to support own learning and development</strong></p>
								<p><textarea name="Unit1_3" style="width: 100%" rows="12"><?php echo $answers->QualificationQuestions->Unit1_3->__toString(); ?></textarea></p>
								<p><strong>Unit 6 - 2.1<br>Describe how all colleagues and teams are dependent on each other to meet business objectives</strong></p>
								<p><textarea name="Unit2_1" style="width: 100%" rows="12"><?php echo $answers->QualificationQuestions->Unit2_1->__toString(); ?></textarea></p>
								<p><strong>Unit 6 - 2.2<br>List what other departments are involved in your day to day work and explain how you interact with them</strong></p>
								<p><textarea name="Unit2_2" style="width: 100%" rows="12"><?php echo $answers->QualificationQuestions->Unit2_2->__toString(); ?></textarea></p>
								<p><strong>Unit 6 - 2.3<br>Explain how to support and influence a team positively</strong></p>
								<p><textarea name="Unit2_3" style="width: 100%" rows="12"><?php echo $answers->QualificationQuestions->Unit2_3->__toString(); ?></textarea></p>
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

			<h1>Personal and Team Performance</h1>
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
						<p><strong>Congratulations! You have competed this module. <img src="module_eportfolio/assets/images/wb2_pg18_img2.png" /> </p>
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

		$("#frm_wb_personal_team_performance :input").not(".assessorFeedback :input, #signature_text, #frm_wb_personal_team_performance :input[type=hidden], .btnViewSectionHistory, .btnViewAssessorComments, #iv_status, #iv_comments, #reopen_comments").prop("disabled", true);

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
					return alert('Your signature is required to complete the workbook, please sign the workbook');

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
							var myForm = document.forms['frm_wb_personal_team_performance'];
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
				var myForm = document.forms['frm_wb_personal_team_performance'];
				myForm.elements['full_save'].value = 'Y';
				return previewInputInformation();
				<?php } else {?>
				return window.history.back();
				<?php } ?>
			}
		});

		//$('ul[role="tablist"]').hide();

		$('input[type=checkbox]').iCheck({
			checkboxClass: 'icheckbox_flat-green',
			radioClass: 'iradio_flat-green'
		});

		$('input[type=radio]').iCheck({
			radioClass: 'iradio_square-green'
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

					var myForm = document.forms['frm_wb_personal_team_performance'];
					myForm.elements['full_save'].value = 'Y';
					myForm.elements['full_save_feedback'].value = 'Y';
					window.onbeforeunload = null;
					myForm.submit();

				},
				'Save And Come Back Later':function () {

					var myForm = document.forms['frm_wb_personal_team_performance'];
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
		$('#frm_wb_personal_team_performance :input[name=full_save]').val('N');
		$($('#frm_wb_personal_team_performance').serializeArray()).each(function(i, field)
		{
			document.forms["frm_wb_personal_team_performance"].elements[field.name].value = field.value.replace(/£/g, "GBP");
		});
		$.ajax({
			type:'POST',
			url:'do.php?_action=save_wb_personal_team_performance',
			data: $('#frm_wb_personal_team_performance').serialize(),
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
