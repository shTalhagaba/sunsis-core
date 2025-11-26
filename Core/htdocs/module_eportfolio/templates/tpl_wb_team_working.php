<?php /* @var $wb WBTeamWorking */ ?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/html">
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<title>Team Working workbook</title>
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
	<div class="text-center" style="margin-top: 5px;"><h3><?php echo $tr->firstnames . ' '  . strtoupper($tr->surname); ?></h3></div>
</nav>

<form name="frm_wb_team_working" id="frm_wb_team_working" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
	<input type="hidden" name="_action" value="save_wb_team_working" />
	<input type="hidden" name="id" value="<?php echo $wb->id; ?>" />
	<input type="hidden" name="tr_id" value="<?php echo $wb->tr_id; ?>" />
	<input type="hidden" name="wb_status" id="wb_status" value="" />
	<input type="hidden" name="full_save" id="full_save" value="<?php echo $wb->full_save; ?>" />
	<input type="hidden" name="full_save_feedback" id="full_save_feedback" value="<?php echo $wb->full_save_feedback; ?>" />
	<div class="container-float">
		<div class="wrapper" style="background-color: #ffffff;">

		<header class="main-header"><span style="margin-left: 50%;"><?php echo DAO::getSingleValue($link, "SELECT title FROM student_frameworks WHERE tr_id = '{$wb->tr_id}'"); ?></span></header>

		<div class="content-wrapper" style="background-color: #ffffff;">

		<?php echo $wb->savers_or_sp == 'savers' ? '<section class="content-header"><h1 style="color: #0000ff;">Team working</h1></section>' : '<section class="content-header"><h1>Team working</h1></section>' ?>

		<section class="content">

		<div id="wizard">

			<h1>Team working</h1>
			<div class="step-content">
				<?php echo $wb->getPageTopLine(); ?>
				<div style="position: absolute; top: 40%; right: 50%;" class="lead">
					<?php echo $wb->savers_or_sp == 'savers' ? '<h2 class="text-bold" style="color: #0000ff;">Team working</h2>' : '<h2 class="text-bold">Team working</h2>' ?>
					<p class="text-center" >Module 6</p>
				</div>

				<p><br></p>

				<?php echo $wb->getPageBottomLine(); ?>
			</div> <!--.page 1 ends-->

			<h1>Team working</h1>
			<div class="step-content">
				<?php echo $wb->getPageTopLine(); ?>

				<div class="row"><div class="col-sm-12"><div class="pull-right"><?php echo HTML::renderWorkbookIcons('', true); ?></div></div></div>

				<div class="row text-bold">
					<div class="col-sm-12">

						<p>This module is about teamwork. The definition of teamwork is the combined action of a group, especially when effective and efficient. Team work is very important to the success of the business as it raises motivation and morale, boosts productivity and gives each individual a sense of belonging.</p>
						<p>In this module you will look at:</p>
						<ul style="margin-left: 15px; margin-bottom: 15px;">
							<li>What is a team</li>
							<li>What makes an effective team</li>
							<li>What is meant by team dynamics</li>
							<li>Different ways to positively influence a team</li>
							<li>The implications when team members do not work together</li>
							<li>Adapting behaviour and sharing ideas to support good teamwork</li>
						</ul>
					</div>
					<div class="col-sm-12">
						<img src="module_eportfolio/assets/images/wb6_pg2_img1.png" />
					</div>
				</div>

				<p><br></p>

				<div class="row"><div class="col-sm-12"><div class="pull-right"><?php echo HTML::renderWorkbookIcons('', true); ?></div></div></div>

				<?php echo $wb->getPageBottomLine(); ?>
			</div> <!--.page 2 ends-->

			<h1>Team working</h1>
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

			<h1>Team working</h1>
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

			<h1>Team working</h1>
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

			<h1>Team working</h1>
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

			<h1>Team working</h1>
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
						<p class="text-bold"><img src="module_eportfolio/assets/images/wb2_img1.png" /> &nbsp;What are the implications of team members not working together? What would happen? Make some notes below:</p>
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

			<h1>Team working</h1>
			<div class="step-content">
				<?php echo $wb->getPageTopLine(); ?>

				<div class="row"><div class="col-sm-12"><div class="pull-right">
					<?php
					if($feedback->SuperdrugMethods->Status->__toString() == 'NA')
						echo HTML::renderWorkbookIcons('SuperdrugMethods', false, 'btn-danger');
					else
						echo HTML::renderWorkbookIcons('SuperdrugMethods', false, 'btn-success');
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
				<div class="row" style="display: flex; <?php echo $feedback->SuperdrugMethods->Status->__toString() == 'A'?'pointer-events:none; ':''; ?>">
					<div class="col-sm-6">
						<p class="text-bold"><img src="module_eportfolio/assets/images/wb2_img1.png" /> &nbsp;What methods do you see used in <?php echo ucfirst($wb->savers_or_sp); ?>?</p>
						<p><textarea name="SuperdrugMethods" style="width: 100%;" rows="10"><?php echo $answers->SuperdrugMethods->__toString(); ?></textarea> </p>
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

				<?php if($wb->id != '' && $_SESSION['user']->type == User::TYPE_ASSESSOR && ($wb->wb_status == Workbook::STATUS_LEARNER_COMPLETED || $wb->wb_status == Workbook::STATUS_BEING_CHECKED || $wb->wb_status == Workbook::STATUS_IV_REJECTED)) {?>
				<div class="row well">
					<div class="col-sm-12">
						<div class="box box-success box-solid">
							<div class="box-header with-border"><h3 class="box-title">Assessor Feedback</h3></div>
							<div class="box-body assessorFeedback" <?php echo $feedback->SuperdrugMethods->Status->__toString() == 'A'?'style="pointer-events:none;"':''; ?>>
								<div class="form-group">
									<label for="status_SuperdrugMethods" class="col-sm-12 control-label">Status:</label>
									<div class="col-sm-12">
										<?php
										echo HTML::selectChosen('status_SuperdrugMethods', $answer_status, $feedback->SuperdrugMethods->Status->__toString() == 'A'?$feedback->SuperdrugMethods->Status->__toString():'', false, true); ?>
									</div>
								</div>
								<div class="form-group">
									<label for="comments_SuperdrugMethods" class="col-sm-12 control-label">Comments:</label>
									<div class="col-sm-12">
										<textarea name="comments_SuperdrugMethods" rows="7" style="width: 100%;"><?php echo $feedback->SuperdrugMethods->Comments->__toString(); ?></textarea>
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
					if($feedback->SuperdrugMethods->Status->__toString() == 'NA')
						echo HTML::renderWorkbookIcons('SuperdrugMethods', false, 'btn-danger');
					else
						echo HTML::renderWorkbookIcons('SuperdrugMethods', false, 'btn-success');
					?>
				</div></div></div>

				<?php echo $wb->getPageBottomLine(); ?>
			</div> <!--.page 9 ends-->

			<h1>Team working</h1>
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



				<div class="row">
					<div class="col-sm-10">
						<p class="text-bold">Since you started in your role you will have learnt a lot about a range of skills including customer service, selling techniques and working with others. Think about what you knew when you started and what you know now and answer the following questions:</p>
					</div>
					<div class="col-sm-2"><img src="module_eportfolio/assets/images/wb2_img2.png" /></div>
				</div>
				<div class="row" <?php echo $feedback->Questions->Status->__toString() == 'A'?'style="pointer-events:none;"':''; ?>>
					<div class="col-sm-12">
						<p class="text-bold"><img src="module_eportfolio/assets/images/wb2_img1.png" /> &nbsp;Give an example of when you have shared information to others to support good customer service.</p>
						<p><textarea name="Question1" style="width: 100%;" rows="7"><?php echo $answers->Questions->Question1->__toString(); ?></textarea> </p>
						<p class="text-bold"><img src="module_eportfolio/assets/images/wb2_img1.png" /> &nbsp;Give an example of when you have learnt something yourself and then shared this with a colleague to help them with their customer service e.g.  Your Star Buys sales are really good because of something you have learnt to do which you have been able to pass on to others.</p>
						<p><textarea name="Question2" style="width: 100%;" rows="7"><?php echo $answers->Questions->Question2->__toString(); ?></textarea> </p>
						<p class="text-bold"><img src="module_eportfolio/assets/images/wb2_img1.png" /> &nbsp;Give an example of when you have had to communicate information (in a timely and reliable manner) to team members to help them in meeting customers’ needs.</p>
						<p><textarea name="Question3" style="width: 100%;" rows="7"><?php echo $answers->Questions->Question3->__toString(); ?></textarea> </p>
					</div>
				</div>

				<p><br></p>

				<div class="row text-center">
					<div class="col-sm-12">
						<img src="module_eportfolio/assets/images/wb6_pg10_img1.png" />
					</div>
				</div>

				<p><br></p>

				<div class="row" <?php echo $feedback->Questions->Status->__toString() == 'A'?'style="pointer-events:none;"':''; ?>>
					<div class="col-sm-12">
						<p class="text-bold"><img src="module_eportfolio/assets/images/wb2_img1.png" /> &nbsp;Give an example of a time you have adapted your behaviour and communication approach to meet the needs of a team member.</p>
						<p><textarea name="Question4" style="width: 100%;" rows="7"><?php echo $answers->Questions->Question4->__toString(); ?></textarea> </p>
						<p class="text-bold"><img src="module_eportfolio/assets/images/wb2_img1.png" /> &nbsp;Give an example of a time you have adapted your behaviour and communication approach to meet the needs of a customer.</p>
						<p><textarea name="Question5" style="width: 100%;" rows="7"><?php echo $answers->Questions->Question5->__toString(); ?></textarea> </p>
						<p class="text-bold"><img src="module_eportfolio/assets/images/wb2_img1.png" /> &nbsp;Give two examples of when you have presented reasoned ideas for improving customer service to appropriate colleagues</p>
						<p><textarea name="Question6" style="width: 100%;" rows="7"><?php echo $answers->Questions->Question6->__toString(); ?></textarea> </p>
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
										<?php
										echo HTML::selectChosen('status_Questions', $answer_status, $feedback->Questions->Status->__toString() == 'A'?$feedback->Questions->Status->__toString():'', false, true); ?>
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

				<div class="row text-center">
					<div class="col-sm-12">
						<img src="module_eportfolio/assets/images/wb6_pg11_img1.png" />
					</div>
				</div>

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

			<h1>Team working</h1>
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

		$("#frm_wb_team_working :input").not(".assessorFeedback :input, #signature_text, #frm_wb_team_working :input[type=hidden], .btnViewSectionHistory, .btnViewAssessorComments, #iv_status, #iv_comments, #reopen_comments").prop("disabled", true);

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
							var myForm = document.forms['frm_wb_team_working'];
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
				var myForm = document.forms['frm_wb_team_working'];
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

					var myForm = document.forms['frm_wb_team_working'];
					myForm.elements['full_save'].value = 'Y';
					myForm.elements['full_save_feedback'].value = 'Y';
					window.onbeforeunload = null;
					myForm.submit();

				},
				'Save And Come Back Later':function () {

					var myForm = document.forms['frm_wb_team_working'];
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
		$('#frm_wb_team_working :input[name=full_save]').val('N');
		$($('#frm_wb_team_working').serializeArray()).each(function(i, field)
		{
			document.forms["frm_wb_team_working"].elements[field.name].value = field.value.replace(/£/g, "GBP");
		});
		$.ajax({
			type:'POST',
			url:'do.php?_action=save_wb_team_working',
			data: $('#frm_wb_team_working').serialize(),
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
<script src="module_eportfolio/assets/wb_common.js"></script>

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
