<?php /* @var $employer Organisation */ ?>
<?php /* @var $location Location */ ?>

<!DOCTYPE html>
<head xmlns="http://www.w3.org/1999/html">
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<title>View Onboarding Learner</title>

	<link rel="stylesheet" href="module_tracking/css/common.css" type="text/css"/>
	<link rel="stylesheet" href="/assets/adminlte/bootstrap/css/bootstrap.min.css">
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.5.0/css/font-awesome.min.css">
	<link rel="stylesheet" href="/assets/adminlte/plugins/jQueryUI/jquery-ui.css">
	<link rel="stylesheet" href="/assets/adminlte/dist/css/AdminLTE.min.css">
	<link rel="stylesheet" href="/assets/adminlte/plugins/summernote/summernote.css">
	<link rel="stylesheet" href="/assets/adminlte/plugins/summernote/summernote-bs3.css">


	<!--[if lt IE 9]>
	<script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
	<script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
	<![endif]-->

	<style type="text/css">
		.ui-datepicker .ui-datepicker-title select {
			color: #000;
		}
	</style>

	<script src="/assets/adminlte/plugins/jQuery/jquery-2.2.3.min.js"></script>
    <script src="/assets/adminlte/plugins/jQueryUI/jquery-ui.min.js"></script>
    <script src="/assets/adminlte/bootstrap/js/bootstrap.min.js"></script>
    <script src="/common.js" type="text/javascript"></script>
    <script src="/assets/adminlte/dist/js/app.min.js"></script>
    <script src="/assets/adminlte/plugins/summernote/summernote.min.js"></script>


</head>
<body>
<div class="row">
	<div class="col-lg-12">
		<div class="banner">
			<div class="Title" style="margin-left: 6px;">View Onboarding Learner
				[<?php echo $vo->firstnames . ' ' . $vo->surname; ?>]
			</div>
			<div class="ButtonBar">
				<span class="btn btn-sm btn-default"
				      onclick="window.location.href='<?php echo $_SESSION['bc']->getPrevious(); ?>';"><i
					class="fa fa-arrow-circle-o-left"></i> Cancel</span>
				<span class="btn btn-sm btn-default"
				      onclick="window.location.href='do.php?_action=edit_ob_learner&id=<?php echo $vo->id; ?>';"><i
					class="fa fa-edit"></i> Edit</span>
			</div>
			<div class="ActionIconBar">

			</div>
		</div>

	</div>
</div>
<div class="row">
	<div class="col-lg-12">
		<?php $_SESSION['bc']->render($link); ?>
	</div>
</div>
<br>

<div class="content-wrapper">

<div class="row">
	<div class="col-sm-4">
		<div class="row">
			<div class="col-sm-12">
				<div class="box box-primary">
					<div class="box-header"><span class="box-title with-header"><span
						class="lead text-bold"><?php echo htmlspecialchars((string)$vo->firstnames) . ' ' . htmlspecialchars(strtoupper($vo->surname)); ?></span></span>
					</div>
					<div class="table-responsive">
						<table class="table">
							<tr><th>Gender</th><td><?php echo $gender_description; ?></td></tr>
							<tr><th>Date of Birth</th><td><?php echo Date::toShort($vo->dob); ?><br><label class="label label-info"><?php echo Date::dateDiff(date("Y-m-d"), $vo->dob); ?></label></td></tr>
							<tr><th>Postcode</th><td><?php echo $vo->home_postcode; ?></td></tr>
							<tr><th>Email</th><td><a href="mailto:<?php echo $vo->home_email; ?>"><?php echo $vo->home_email; ?></a></td></tr>
							<tr><th>KS Assessment</th><td><?php echo isset($listAssessmentTypes[$vo->ks_assessment]) ? $listAssessmentTypes[$vo->ks_assessment] : $vo->ks_assessment; ?></td></tr>
							<tr><th>Employer</th><td><?php echo $employer->legal_name; ?></td>							</tr>
							<tr>
								<th>Employer Address</th>
								<td>
									<?php echo $location->address_line_1 != '' ? $location->address_line_1 . '<br>' : ''; ?>
									<?php echo $location->address_line_2 != '' ? $location->address_line_2 . '<br>' : ''; ?>
									<?php echo $location->address_line_3 != '' ? $location->address_line_3 . '<br>' : ''; ?>
									<?php echo $location->address_line_4 != '' ? $location->address_line_4 . '<br>' : ''; ?>
									<?php echo $location->postcode != '' ? $location->postcode . '<br>' : ''; ?>
								</td>
							</tr>
                            <tr><th>Contract</th><td><?php echo DAO::getSingleValue($link, "SELECT title FROM contracts WHERE contracts.id = '{$vo->contract_id}'"); ?></td></tr>
                            <tr><th>Coach</th><td><?php echo DAO::getSingleValue($link, "SELECT CONCAT(firstnames, ' ', surname) FROM users WHERE users.id = '{$vo->coach}'"); ?></td></tr>
						</table>
					</div>
				</div>
			</div>
			<div class="col-sm-12">
				<div class="box box-primary">
					<div class="box-header"><span class="box-title with-header"><?php echo $vo->user_id == '' ? 'Set Eligibility' : 'Learner is converted'; ?></span></div>
					<div class="box-body">
						<?php if($vo->user_id == ''){ ?>
						<span class="text-info">
							<i class="fa fa-info-circle"></i> Use this panel to select learner eligibility for onboarding.
						</span>
						<p><br></p>
						<table class="table table-bordered">
							<form method="post" name="frmEligibility" action="<?php echo $_SERVER['PHP_SELF']; ?>">
								<input type="hidden" name="_action" value="ajax_onboarding" />
								<input type="hidden" name="subaction" value="saveObLearnerEligibility" />
								<input type="hidden" name="ob_id" value="<?php echo $vo->id; ?>" />
								<tr>
									<th style="width: 50%;">Learner Eligibility</th>
									<td style="width: 50%;"><?php echo HTML::selectChosen('is_eligible', [['Y', 'Yes Eligible'], ['N', 'Not Eligible']], $vo->is_eligible, true, true); ?></td>
								</tr>
								<tr>
									<td colspan="2">
										<span class="btn btn-success btn-md btn-block" onclick="saveEligibility();">Save Eligibility</span>
									</td>
								</tr>
							</form>
						</table>
						<?php } ?>
						<?php if($vo->user_id != '') { ?>
						<p><br></p>
						<span class="text-info">
							<i class="fa fa-info-circle"></i> This learner has been converted into Sunesis Learner.
						</span>
						<p><br></p>
						<span class="btn btn-info btn-sm" onclick="window.location.replace('do.php?_action=read_learner&id=<?php echo $vo->user_id; ?>'); ">Navigate to Learner Screen</span>
						<?php } ?>
					</div>
				</div>
			</div>
		</div>
	</div>
	<div class="col-sm-8">
		<p>
			<span class="label <?php echo (!is_null($ks_assessment) && $ks_assessment->is_finished == 'Y') ?'label-success':'label-danger'; ?>"><?php echo (!is_null($ks_assessment) && $ks_assessment->is_finished == 'Y') ?'<span class="fa fa-check"></span>':'<span class="fa fa-close"></span>'; ?> K&S Assessment</span>
			<span class="label <?php echo $vo->is_initial_screening_done == 'Y' ?'label-success':'label-danger'; ?>"><?php echo $vo->is_initial_screening_done == 'Y' ?'<span class="fa fa-check"></span>':'<span class="fa fa-close"></span>'; ?> Initial Screening</span>
			<span class="label <?php echo $vo->user_id != '' ?'label-success':'label-danger'; ?>"><?php echo $vo->user_id != '' ?'<span class="fa fa-check"></span>':'<span class="fa fa-close"></span>'; ?> Converted to Sunesis Learner</span>
			<span class="label <?php echo $vo->is_finished == 'Y' ?'label-success':'label-danger'; ?>"><?php echo $vo->is_finished == 'Y' ?'<span class="fa fa-check"></span>':'<span class="fa fa-close"></span>'; ?> Onboarding Form Completed</span>
            <?php if(false){?>
            <span class="btn btn-sm btn-danger pull-right"
                  onclick="deleteObLearner(this);"><i
                        class="fa fa-trash"></i> Delete</span>
            <div id="frmDeleteObLearner" style="display: none;"></div>
            <?php } ?>
		</p>

        <div class="nav-tabs-custom bg-gray-light">
			<ul class="nav nav-tabs">
				<li class="active"><a href="#tab_forskills" data-toggle="tab">Skills Forward</a></li>
				<li><a href="#tab_ks" data-toggle="tab">Knowledge & Skills</a></li>
				<li><a href="#tab_ob_screening" data-toggle="tab">Initial Screening</a></li>
                <li><a href="#tab_enrolment" data-toggle="tab">Enrolment</a></li>
                <li><a href="#tab_emails" data-toggle="tab">Emails</a></li>
			</ul>
			<div class="tab-content">
				<div class="tab-pane active" id="tab_forskills">
					<span class="lead">Skills Forward / Forskills</span>
					<p></p>
					<?php if (is_null($forskills_info)) { ?>
					<div class="row">
						<div class="col-sm-6">
							<p><br></p>
							<span class="text-info">
								<i class="fa fa-info-circle fa-lg"></i> This learner has not been linked/registered with Skills Forward yet.
							</span>

							<p><br></p>

							<form name="frmSearchLearnerInForskills" role="form"
							      action="<?php echo $_SERVER['PHP_SELF']; ?>"
							      method="post">
								<input type="hidden" name="_action" value="ajax_forskills">
								<input type="hidden" name="subaction" value="findSimilarUsers">
								<input type="hidden" name="studentRef" value="<?php echo $vo->ob_username; ?>">
								<input type="hidden" name="email" value="<?php echo $vo->home_email; ?>">
                                <span class="btn btn-primary btn-md" onclick="registerLearnerToForskills();"><i
                                    class="fa fa-cog"></i> Click to register </span>
							</form>
						</div>
					</div>
					<?php } else { ?>
					<div class="row">
						<div class="col-sm-12">
							<table class="table table-responsive table-bordered">
								<?php $forskills_info = json_decode($forskills_info->user_details);?>
								<tr>
									<th class="bg-gray-light">Username</th>
									<td><?php echo $forskills_info->username; ?></td>
									<th class="bg-gray-light">User ID</th>
									<td><?php echo $forskills_info->idUser; ?></td>
									<th class="bg-gray-light">User Institution ID</th>
									<td><?php echo $forskills_info->idUserInstitution; ?></td>
									<th class="bg-gray-light">Student Ref</th>
									<td><?php echo $forskills_info->studentref; ?></td>
								</tr>
								<tr>
									<th class="bg-gray-light">Password</th>
									<td>
										<span id="lblPassword">**********</span><br>
										<a href="#" onclick="showHidePassword(this);">Show</a>
									</td>
								</tr>
							</table>
							<p>
								<span class="btn btn-info btn-md pull-right" onclick="getUserAssessments();"><i class="fa fa-cloud"></i> Download Assessments</span>
							</p>

							<div class="table-responsive" id="divForskillsAssessment"></div>
						</div>
					</div>
					<?php }?>
				</div> <!-- end tab-forskills -->
				<div class="tab-pane" id="tab_ks">
					<div class="row">
					<div class="col-sm-12">
						<span class="lead">Knowledge & Skills Assessment</span>

						<p></p>
						<?php if ($ks_assessment == null) { ?>
						<div class="row">
							<div class="col-sm-4">
								<span class="text-info text-bold pull-right">K & S Assessment URL for <?php echo isset($listAssessmentTypes[$vo->ks_assessment]) ? $listAssessmentTypes[$vo->ks_assessment] : $vo->ks_assessment; ?></span>
							</div>
							<div class="col-sm-8">
								<div class="input-group">
									<input id="ks_url" readonly type="text" class="form-control" value="<?php echo OnboardingHelper::generateKSAssessmentUrl($vo->id); ?>">
									<span class="input-group-addon" title="Click to copy the URL" onclick="copyUrl('ks_url', 'copyKsUrlTooltip');"> <i class="fa fa-copy"></i></span>
								</div>
								<span id="copyKsUrlTooltip"></span>
							</div>
						</div>
						<?php
						} //if($ks_assessment == null)
						else { ?>
						<div class="box box-info">
							<div class="box-header">
								<div class="box-header"><span
									class="box-title"><?php echo isset($listAssessmentTypes[$vo->ks_assessment]) ? $listAssessmentTypes[$vo->ks_assessment] : $vo->ks_assessment; ?></span>
								</div>
								<div class="box-body table-responsive">
									<div class="row">
										<div class="col-sm-4">
											<table class="table small table-bordered">
												<caption class="text-bold">Knowledge Elements</caption>
												<tr><th>Score</th><td><?php echo $k_stats->score . '/' . $k_stats->total_score; ?></td></tr>
												<tr><th>Answered 3 or 4</th><td><?php echo $k_stats->t_3_or_4; ?></td></tr>
												<tr>
                                                    <th>% Answered 3 or 4</th>
                                                    <td class="<?php echo floatval($k_stats->percentage_3_or_4) < 30 ? 'bg-green' : (floatval($k_stats->percentage_3_or_4) >= 30 && floatval($k_stats->percentage_3_or_4) <= 40 ? 'bg-yellow' : 'bg-red'); ?>">
                                                        <?php echo $k_stats->percentage_3_or_4; ?>%
                                                    </td>
                                                </tr>
											</table>
										</div>
										<div class="col-sm-4">
											<table class="table small table-bordered">
												<caption class="text-bold">Skills Elements</caption>
												<tr><th>Score</th><td><?php echo $s_stats->score . '/' . $s_stats->total_score; ?></td></tr>
												<tr><th>Answered 2 or 3</th><td><?php echo $s_stats->t_2_or_3; ?></td></tr>
												<tr>
                                                    <th>% Answered 2 or 3</th>
                                                    <td class="<?php echo floatval($s_stats->percentage_2_or_3) < 30 ? 'bg-green' : (floatval($s_stats->percentage_2_or_3) >= 30 && floatval($s_stats->percentage_2_or_3) <= 40 ? 'bg-yellow' : 'bg-red'); ?>">
                                                        <?php echo $s_stats->percentage_2_or_3; ?>%
                                                    </td>
                                                </tr>
											</table>
										</div>
										<div class="col-sm-4">
											<table class="table small table-bordered">
												<caption class="text-bold">K&S Combined</caption>
												<tr><th>Score</th><td><?php echo ($k_stats->score + $s_stats->score) . '/' . ($k_stats->total_score + $s_stats->total_score); ?></td></tr>
												<tr><th>Answered<br>3or4 and 2or3</th><td><?php echo ($k_stats->t_3_or_4 + $s_stats->t_2_or_3); ?></td></tr>
												<tr>
                                                    <th>% Answered<br>3or4 and 2or3</th>
                                                    <?php
                                                    $_k_s_combined = round((($k_stats->t_3_or_4 + $s_stats->t_2_or_3) / ($k_stats->total_score + $s_stats->total_score)) * 100, 2);
                                                    ?>
                                                    <td class="<?php echo floatval($_k_s_combined) < 30 ? 'bg-green' : (floatval($_k_s_combined) >= 30 && floatval($_k_s_combined) <= 40 ? 'bg-yellow' : 'bg-red'); ?>">
                                                        <?php echo $_k_s_combined; ?>%
                                                    </td>
                                                </tr>
											</table>
										</div>
									</div>

									<div class="row">
										<div class="col-sm-12">
                                            <table class="table table-bordered">
												<caption class="pad bg-green text-bold">Core Knowledge [Score: <?php echo $k_qs_total . '/' . count($ks_assessment->k_qs) * 4; ?>]</caption>
												<thead class="bg-gray-light"><tr><th>Question</th><th>Answer</th></tr></thead>
												<tbody>
													<?php
													foreach ($questions_k AS $id => $desc) {
														echo '<tr>';
														$q_id = 'q' . $id;
														echo '<td>' . $desc . '</td>';
														echo '<td>';
														if (isset($ks_assessment->k_qs[$q_id])) {
															echo isset($listKnowledgeOptions[$ks_assessment->k_qs[$q_id]]) ?
																$ks_assessment->k_qs[$q_id] . '.&nbsp;' . str_replace(' ', '&nbsp;', $listKnowledgeOptions[$ks_assessment->k_qs[$q_id]]) :
																$ks_assessment->k_qs[$q_id];
														}
														echo '</td>';
														echo '</tr>';
													}
													?>
												</tbody>
											</table>
											<hr>
											<table class="table table-bordered">
												<caption class="pad bg-green text-bold">Core Skills [Score: <?php echo $s_qs_total . '/' . count($ks_assessment->s_qs) * 3; ?>]</caption>
												<thead class="bg-gray-light"><tr><th>Question</th><th>Answer</th></tr></thead>
												<tbody>
													<?php
													foreach ($questions_s AS $id => $desc) {
														echo '<tr>';
														$q_id = 'q' . $id;
														echo '<td>' . $desc . '</td>';
														echo '<td>';
														if (isset($ks_assessment->s_qs[$q_id])) {
															echo isset($listSkillsOptions[$ks_assessment->s_qs[$q_id]]) ?
																$ks_assessment->s_qs[$q_id] . '.&nbsp;' . str_replace(' ', '&nbsp;', $listSkillsOptions[$ks_assessment->s_qs[$q_id]]) :
																$ks_assessment->s_qs[$q_id];
														}
														echo '</td>';
														echo '</tr>';
													}
													?>
												</tbody>
											</table>
											<hr>
											<?php if ($vo->ks_assessment == 'lmo') { ?>
											<table class="table table-bordered">
												<caption class="pad bg-green text-bold">Specialist Job Skills</caption>
												<tbody>
												<tr>
													<th>Please select a role that best describes your current job role</th>
												</tr>
												<tr>
													<td><?php echo isset($listJobRoles[$ks_assessment->your_role]) ? $listJobRoles[$ks_assessment->your_role] : $ks_assessment->your_role; ?></td>
												</tr>
												</tbody>
											</table>
											<hr>
											<table class="table table-bordered">
												<caption class="pad bg-green text-bold">Production Processing [Score: <?php echo $p_qs_total . '/' . count($ks_assessment->p_qs) * 4; ?>]</caption>
												<thead class="bg-gray-light"><tr><th>Question</th><th>Answer</th></tr></thead>
												<tbody>
												<tr>
													<td>What is your job title?</td>
													<td><?php echo trim($ks_assessment->job_title) != '' ? '<span class="small">' . $ks_assessment->job_title . '</span>' : ''; ?></td>
												</tr>
													<?php
													foreach ($questions_p AS $id => $desc) {
														echo '<tr>';
														$q_id = 'q' . $id;
														echo '<td>' . $desc . '</td>';
														echo '<td>';
														if (isset($ks_assessment->p_qs[$q_id])) {
															echo isset($listKnowledgeOptions[$ks_assessment->p_qs[$q_id]]) ?
																$ks_assessment->p_qs[$q_id] . '.&nbsp;' . str_replace(' ', '&nbsp;', $listKnowledgeOptions[$ks_assessment->p_qs[$q_id]]) :
																$ks_assessment->p_qs[$q_id];
														}
														echo '</td>';
														echo '</tr>';
													}
													?>
												</tbody>
											</table>
											<hr>
											<?php } //if($assessment_type == 'lmo')?>
											<table class="table table-bordered">
												<caption class="pad bg-green text-bold">Personal Development</caption>
												<thead class="bg-gray-light">
												<tr>
													<th>Question</th>
													<th>Answer</th>
												</tr>
												</thead>
												<tbody>
												<tr>
													<td>Please indicate how long you have worked for your organisation</td>
													<td>
														<?php echo isset($ks_assessment->pd_qs->pdq1) && isset($listHowLong[$ks_assessment->pd_qs->pdq1]) ? $listHowLong[$ks_assessment->pd_qs->pdq1] : ''; ?>
													</td>
												</tr>
												<tr>
													<td>If you have had a performance review, do you recall whether you discussed Training needs
														and/or Career development plans with your Manager?
													</td>
													<td>
														<?php echo isset($ks_assessment->pd_qs->pdq2) && isset($listYesNo[$ks_assessment->pd_qs->pdq2]) ? $listYesNo[$ks_assessment->pd_qs->pdq2] : ''; ?>
													</td>
												</tr>
												<tr>
													<td>5 topics that reflect your most important personal development and training needs for
														progression in your current role and further career development opportunities
													</td>
													<td>
														<?php echo isset($ks_assessment->pd_qs->pdq3) ? implode("<br>", $this->getDescriptions($listTopics, $ks_assessment->pd_qs->pdq3, true)) : ''; ?>
														<?php echo trim($ks_assessment->pd_qs->pdq3_other) != '' ? '<br><span class="small">' . $ks_assessment->pd_qs->pdq3_other . '</span>' : ''; ?>
													</td>
												</tr>
												<tr>
													<td>Please insert any other training needs you feel you require for personal development
													</td>
													<td><?php echo isset($ks_assessment->pd_qs->pdq4) ? $ks_assessment->pd_qs->pdq4 : ''; ?></td>
												</tr>
												<tr>
													<td>What skills would allow you to feel more confident at work?</td>
													<td>
														<?php echo isset($ks_assessment->pd_qs->pdq5) ? implode("<br>", $this->getDescriptions($listSkills, $ks_assessment->pd_qs->pdq5, true)) : ''; ?>
														<?php echo trim($ks_assessment->pd_qs->pdq5_other) != '' ? '<br><span class="small">' . $ks_assessment->pd_qs->pdq5_other . '</span>' : ''; ?>
													</td>
												</tr>
												<tr>
													<td>What, if anything, is preventing you from developing as you would like?</td>
													<td>
														<?php echo isset($ks_assessment->pd_qs->pdq6) ? implode("<br>", $this->getDescriptions($listChallanges, $ks_assessment->pd_qs->pdq6, true)) : ''; ?>
														<?php echo trim($ks_assessment->pd_qs->pdq6_other) != '' ? '<br><span class="small">' . $ks_assessment->pd_qs->pdq6_other . '</span>' : ''; ?>
													</td>
												</tr>
												<tr>
													<td>Why do you want enrol onto this apprenticeship?</td>
													<td>
														<?php echo trim($ks_assessment->pd_qs->pdq7) != '' ? '<span class="small">' . $ks_assessment->pd_qs->pdq7 . '</span>' : ''; ?>
													</td>
												</tr>
												<tr>
													<td>What is your current awareness/understanding of British Values and Prevent procedures?
														Do you know who to contact in the event of any issues being raised?
													</td>
													<td>
														<?php echo isset($ks_assessment->pd_qs->pdq8) && isset($listUnderstanding[$ks_assessment->pd_qs->pdq8]) ? $listUnderstanding[$ks_assessment->pd_qs->pdq8] : ''; ?>
													</td>
												</tr>
												<tr>
													<td>What is your current understanding of Safeguarding? Do you understand how to recognise
														and apply this in your workplace and broader society?
													</td>
													<td>
														<?php echo isset($ks_assessment->pd_qs->pdq9) && isset($listUnderstanding[$ks_assessment->pd_qs->pdq9]) ? $listUnderstanding[$ks_assessment->pd_qs->pdq9] : ''; ?>
													</td>
												</tr>
												</tbody>
											</table>
										</div>
									</div>

								</div>
							</div>
						</div>
						<?php } //else ?>
						</div>
					</div>
				</div>
				<div class="tab-pane" id="tab_ob_screening">
					<div class="row">
						<div class="col-sm-12"><span class="lead">Initial Screening Form</span></div>
					</div>

					<?php if ($vo->is_initial_screening_done != 'Y') { ?>
					<p><br></p>
					<div class="row">
						<div class="col-sm-4">
							<span class="text-info text-bold pull-right">Screening Form URL</span>
						</div>
						<div class="col-sm-8">
							<div class="input-group">
								<input id="ob_screening_url" readonly type="text" class="form-control" value="<?php echo OnboardingHelper::generateInitialScreeningUrl($vo->id); ?>">
								<span class="input-group-addon" title="Click to copy the URL" onclick="copyUrl('ob_screening_url', 'copyObFormUrlTooltip');"> <i class="fa fa-copy"></i></span>
							</div>
							<span id="copyObFormUrlTooltip"></span>
						</div>
					</div>
					<?php } else { ?>
					<p><br></p>
					<div class="row">
						<div class="col-sm-12">
							<div class="box box-info">
								<div class="box-header">
									<span class="box-title">Initial Screening Details</span>
								</div>
								<div class="box-body">
									<div class="col-sm-7">
										<table class="table table-bordered">
											<tr>
												<th>Title</th>
												<td><?php echo $vo->learner_title; ?></td>
											</tr>
											<tr>
												<th>Ethnicity</th>
												<td><?php echo DAO::getSingleValue($link, "SELECT Ethnicity_Desc FROM lis201213.ilr_ethnicity WHERE Ethnicity = '{$vo->ethnicity}'"); ?></td>
											</tr>
											<tr>
												<th>National Insurance</th>
												<td><?php echo $vo->ni; ?></td>
											</tr>
											<tr>
												<th>Home Address</th>
												<td>
													<?php echo $vo->home_address_line_1 != '' ? $vo->home_address_line_1 . '<br>' : '';?>
													<?php echo $vo->home_address_line_2 != '' ? $vo->home_address_line_2 . '<br>' : '';?>
													<?php echo $vo->home_address_line_3 != '' ? $vo->home_address_line_3 . '<br>' : '';?>
													<?php echo $vo->home_address_line_4 != '' ? $vo->home_address_line_4 . '<br>' : '';?>
													<?php echo $vo->home_postcode != '' ? $vo->home_postcode . '<br>' : '';?>
													<?php echo $vo->home_telephone != '' ? $vo->home_telephone . '<br>' : '';?>
													<?php echo $vo->home_mobile != '' ? $vo->home_mobile : '';?>
												</td>
											</tr>
											<tr>
												<th>Emergency Contact</th>
												<td>
													<?php echo $vo->em_con_title != '' ? $vo->em_con_title . ' ' : '';?>
													<?php echo $vo->em_con_name != '' ? $vo->em_con_name : '';?>
													<?php echo $vo->em_con_rel != '' ? ' (' . $vo->em_con_rel . ')<br>' : '';?>
													<?php echo $vo->em_con_tel != '' ? $vo->em_con_tel . '<br>' : '';?>
													<?php echo $vo->em_con_mob != '' ? $vo->em_con_mob : '';?>
												</td>
											</tr>
										</table>
										<img src="do.php?_action=generate_image&<?php echo $vo->learner_is_signature != '' ? $vo->learner_is_signature : 'title=Not Signed&font=Signature_Regular.ttf&size=25'; ?>"
										     style="border: 2px solid;border-radius: 15px;"/>
									</div>
									<div class="col-sm-5">
										<div class="table-responsive">
											<table class="table table-bordered">
												<caption class="text-bold">Learner Contact Preferences</caption>
												<tr>
													<td>About courses or learning opportunities.</td>
													<td><?php echo in_array(1, explode(',', $vo->RUI)) ? '<i class="fa fa-check"></i> ' : '<i class="fa fa-remove"></i> '; ?></td>
												</tr>
												<tr>
													<td>For surveys and research.</td>
													<td><?php echo in_array(2, explode(',', $vo->RUI)) ? '<i class="fa fa-check"></i> ' : '<i class="fa fa-remove"></i> '; ?></td>
												</tr>
												<tr>
													<td>By post</td>
													<td><?php echo in_array(1, explode(',', $vo->PMC)) ? '<i class="fa fa-check"></i> ' : '<i class="fa fa-remove"></i> '; ?></td>
												</tr>
												<tr>
													<td>By phone</td>
													<td><?php echo in_array(2, explode(',', $vo->PMC)) ? '<i class="fa fa-check"></i> ' : '<i class="fa fa-remove"></i> '; ?></td>
												</tr>
												<tr>
													<td>By email</td>
													<td><?php echo in_array(3, explode(',', $vo->PMC)) ? '<i class="fa fa-check"></i> ' : '<i class="fa fa-remove"></i> '; ?></td>
												</tr>
											</table>
										</div>
										<div class="table-responsive">
											<table class="table table-bordered">
												<caption class="text-bold">Learner Consent</caption>
												<tr>
													<td>I give consent to use my image on social media and for marketing purposes.</td>
													<td><?php echo in_array(1, explode(',', $vo->disclaimer)) ? '<i class="fa fa-check"></i> ' : '<i class="fa fa-remove"></i> '; ?></td>
												</tr>
												<tr>
													<td>I give consent for my coach to take voice recordings to use as evidence as a part of my course content.</td>
													<td><?php echo in_array(2, explode(',', $vo->disclaimer)) ? '<i class="fa fa-check"></i> ' : '<i class="fa fa-remove"></i> '; ?></td>
												</tr>
												<tr>
													<td>I give consent for my coach to take photo and film recordings.</td>
													<td><?php echo in_array(3, explode(',', $vo->disclaimer)) ? '<i class="fa fa-check"></i> ' : '<i class="fa fa-remove"></i> '; ?></td>
												</tr>
											</table>
										</div>
									</div>
									<div class="col-sm-12">
										<table class="table table-bordered">
											<tr>
												<th>Have you previously untaken any training in Lean, Manufacturing and/or Business
													Improvement Techniques?
												</th>
												<td><?php echo $vo->previous_training == 'Y' ? 'Yes' : 'No'; ?></td>
											</tr>
											<?php if (trim($vo->previous_training_details) != '') { ?>
											<tr>
												<th colspan="2">Details of this training</th>
											</tr>
											<tr>
												<td colspan="2"><?php echo $vo->previous_training_details; ?></td>
											</tr>
											<?php } ?>
										</table>
									</div>
									<div class="col-sm-12">
										<table class="table table-bordered">
											<tr>
												<th>Are you currently undertaking any other Apprenticeship, other qualifications or study
													with a college, university or other training provider?
												</th>
												<td><?php echo $vo->currently_undertaking_training == 'Y' ? 'Yes' : 'No'; ?></td>
											</tr>
											<tr>
												<th>Is this Apprenticeship at the same level or at a lower level than the highest
													qualification you already hold?
												</th>
												<td><?php echo $vo->same_or_lower == 'Y' ? 'Yes' : 'No'; ?></td>
											</tr>
											<tr>
												<th>The Apprenticeship is a genuine job with an accompanying skills development programme?
												</th>
												<td><?php echo $vo->genuine_job == 'Y' ? 'Yes' : 'No'; ?></td>
											</tr>
											<tr>
												<th>The knowledge and skills the Apprenticeship will provide is substantially different from
													any previous qualifications you already hold?
												</th>
												<td><?php echo $vo->genuine_job == 'Y' ? 'Yes' : 'No'; ?></td>
											</tr>
											<?php if (trim($vo->new_training_details) != '') { ?>
											<tr>
												<th colspan="2">Explain what new skills and knowledge you hope to gain by undertaking this
													Apprenticeship and how this will benefit you and your employer.
												</th>
											</tr>
											<tr>
												<td colspan="2"><?php echo $vo->new_training_details; ?></td>
											</tr>
											<?php } ?>
										</table>
									</div>
									<div class="col-sm-12">
										<table class="table table-bordered">
											<tr>
												<th>Nationality</th>
												<td><?php echo DAO::getSingleValue($link, "SELECT description FROM lookup_country_list WHERE code = '{$vo->nationality}'"); ?></td>
												<th>Country of birth</th>
												<td><?php echo DAO::getSingleValue($link, "SELECT country_name FROM central.lookup_countries WHERE country_code = '{$vo->country_of_birth}'"); ?></td>
											</tr>
										</table>
										<table class="table table-bordered">
											<tr>
												<th>Have you been resident in the UK/or other EEA country for the last 3 years?</th>
												<td><?php echo $vo->funding_q1 == 'Y' ? 'Yes' : 'No'; ?></td>
											</tr>
											<tr>
												<th>Do you have the right to live and work in the United Kingdom without restrictions?</th>
												<td><?php echo $vo->funding_q2 == 'Y' ? 'Yes' : 'No'; ?></td>
											</tr>
											<tr>
												<th>Are you a family member (husband, wife, civil partner, child, grandchild, dependent
													parent or grandparent) of an EEA citizen who has been ordinarily resident in the EEA for
													at least the previous 3 years?
												</th>
												<td><?php echo $vo->funding_q3 == 'Y' ? 'Yes' : 'No'; ?></td>
											</tr>
											<tr>
												<th>I am a Non-EEA citizen who has permission from the UK Government to live in the UK (not
													for educational purposes) and have been ordinarily resident in the UK for a least the
													previous 3 years?
												</th>
												<td><?php echo $vo->funding_q4 == 'Y' ? 'Yes' : 'No'; ?></td>
											</tr>
											<tr>
												<th colspan="2">I hold the following immigration status from the UK Government, or I am husband, wife,
													civil partner or child of someone who does (tick applicable)
												</th>
											</tr>
											<tr>
												<td colspan="2">
													<div class="table-responsive">
														<table class="table-bordered table">
															<?php $funding_q5 = explode(',', $vo->funding_q5) ?>
															<tr><td>Refugee Status</td><td><?php echo in_array('RS', $funding_q5) ? '<i class="fa fa-check"></i> ' : ''; ?></td></tr>
															<tr><td>Discretionary leave to remain</td><td><?php echo in_array('DLTR', $funding_q5) ? '<i class="fa fa-check"></i> ' : ''; ?></td></tr>
															<tr><td>Exceptional leave to enter or remain</td><td><?php echo in_array('ELTEOR', $funding_q5) ? '<i class="fa fa-check"></i> ' : ''; ?></td></tr>
															<tr><td>Indefinite leave to enter or remain</td><td><?php echo in_array('ILTEOR', $funding_q5) ? '<i class="fa fa-check"></i> ' : ''; ?></td></tr>
															<tr><td>Humanitarian protection</td><td><?php echo in_array('HP', $funding_q5) ? '<i class="fa fa-check"></i> ' : ''; ?></td></tr>
															<tr><td>I have leave outside the rules</td><td><?php echo in_array('IHLOTR', $funding_q5) ? '<i class="fa fa-check"></i> ' : ''; ?></td></tr>
														</table>
													</div>
												</td>
											</tr>
											<tr>
												<th>Are there any immigration restrictions on how long you can stay in the UK?</th>
												<td><?php echo $vo->funding_q6 == 'Y' ? 'Yes' : 'No'; ?></td>
											</tr>
											<tr>
												<th>Are you in the United Kingdom on a Tier 4 (general) Student Visa?</th>
												<td><?php echo $vo->funding_q7 == 'Y' ? 'Yes' : 'No'; ?></td>
											</tr>
											<tr>
												<th>Are you registered as an Asylum Seeker?</th>
												<td><?php echo $vo->funding_q8 == 'Y' ? 'Yes' : 'No'; ?></td>
											</tr>
											<tr>
												<td colspan="2">
													<div class="table-responsive">
														<table class="table-bordered table">
															<?php $funding_q9 = explode(',', $vo->funding_q9) ?>
															<tr><td>*Have you lived in the UK for six months or longer while your claim is being considered by the Home Office, and no decision on your claim has been made?</td><td><?php echo in_array('1', $funding_q9) ? '<i class="fa fa-check"></i> ' : ''; ?></td></tr>
															<tr><td>*Are you in the care of the local authority and receiving local authority and receiving local authority support?</td><td><?php echo in_array('2', $funding_q9) ? '<i class="fa fa-check"></i> ' : ''; ?></td></tr>
															<tr><td>*I have been refused asylum, but I have lodged an appeal and no decision has been made within 6 months of me lodging an appeal.</td><td><?php echo in_array('3', $funding_q9) ? '<i class="fa fa-check"></i> ' : ''; ?></td></tr>
															<tr><td>*I have been refused asylum but have been granted support under section 4 of the Immigration and Asylum Act 1999.</td><td><?php echo in_array('4', $funding_q9) ? '<i class="fa fa-check"></i> ' : ''; ?></td></tr>
														</table>
													</div>
												</td>
											</tr>
										</table>
									</div>
									<div class="col-sm-12">
										<table class="table table-bordered">
											<tr>
												<th>Are you an employee of the company <span><?php echo $employer->legal_name; ?></span>?</th>
												<td><?php echo $vo->emp_q4 == 'Y' ? 'Yes' : 'No'; ?></td>
											</tr>
											<tr>
												<th>Do you have a Contract of Employment?</th>
												<td><?php echo $vo->emp_q5 == 'Y' ? 'Yes' : 'No'; ?></td>
											</tr>
											<tr>
												<th>If NO, are you employed as a contractor or agency staff?</th>
												<td><?php echo $vo->emp_q6 == 'Y' ? 'Yes' : 'No'; ?></td>
											</tr>
											<tr>
												<th>How many hours are you contracted to work per week?</th>
												<td><?php echo $vo->emp_q7; ?></td>
											</tr>
											<tr>
												<th>How many days a week are you contracted to work?</th>
												<td><?php echo $vo->emp_q8; ?></td>
											</tr>
											<tr>
												<th>What type of contract do you have?</th>
												<td>
													<?php
													echo $vo->emp_q9 == 'P' ? 'Permanent' : '';
													echo $vo->emp_q9 == 'FT' ? 'Fixed Term' : '';
													echo $vo->emp_q9 == 'ZH' ? 'Zero Hours' : '';
													?>
												</td>
											</tr>
											<tr>
												<th>If you answered (Fixed term) above, please provide the contract end/expiry date. If you
													answered (Zero hours) above, please provide average weekly hours total.
												</th>
												<td><?php echo $vo->contract_end_date; echo $vo->avg_weekly_hours; ?></td>
											</tr>
											<tr>
												<th>Does the nature of your job role cause you to spend any of your contracted hours working
													outside of England? Are you planning to leave the country for any work commitments or
													extended leave over a month within the duration of the programme?
												</th>
												<td><?php echo $vo->emp_q11 == 'Y' ? 'Yes' : 'No'; ?></td>
											</tr>
											<tr>
												<th>If YES, provide details, number of days, hours or % of hours spent?</th>
												<td><?php echo $vo->emp_q12; ?></td>
											</tr>
											<tr>
												<th>Are you being paid at least the minimum wage which is relevant for your age?</th>
												<td><?php echo $vo->emp_q13 == 'Y' ? 'Yes' : 'No'; ?></td>
											</tr>
										</table>
									</div>
									<?php
									echo $this->renderFileRepository($vo);
									?>
								</div>
							</div>
						</div>
					</div>
					<?php } ?>
				</div>
                <div class="tab-pane" id="tab_enrolment">
                    <?php
                    if($vo->is_enrolled == 'N' && $vo->user_id != '')
                    {
                        include_once(__DIR__ . '/partials/ob_enrolment_form.php');
                    }
                    elseif($vo->is_enrolled == 'Y' && $vo->user_id != '')
                    {
                        echo '<span class="btn btn-info btn-sm" onclick="window.location.replace(\'do.php?_action=read_training_record&id='.$vo->linked_tr_id.'\'); "><i class="fa fa-folder-open"></i> Open Training Record</span>';
                        include_once (__DIR__ . '/partials/view_ob_training_details.php');
                    }
                    else
                    {
                        echo $vo->is_eligible == 'N' ? '<p><br><span class="text-red text-bold">The learner has been set as Not Eligible.</span></p>' : '<p><br><span class="text-bold">Waiting for eligibility outcome</span></p>';
                    }
                    ?>
                </div>
				<div class="tab-pane" id="tab_emails">
					<span class="lead">Emails</span>

					<p><br></p>

					<div class="row">
						<div class="col-sm-4 col-sm-offset-4">
							<span id="btnCompose" class="btn btn-primary btn-block margin-bottom" onclick="$(this).hide(); $('#mailBox').hide(); $('#composeNewMessageBox').show();">Compose New Email</span>
						</div>
						<div class="col-sm-12" id="composeNewMessageBox" style="display: none;">
							<?php echo $this->renderComposeNewMessageBox($link, $vo); ?>
						</div>
					</div>
					<hr>
					<div class="table-responsive">
						<table class="table table-bordered small">
							<?php $result = DAO::getResultset($link, "SELECT * FROM emails WHERE emails.entity_type = 'ob_learners' AND emails.entity_id = '{$vo->id}' ORDER BY created DESC", DAO::FETCH_ASSOC); ?>
							<caption class="lead text-bold text-center">Sent Emails (<?php echo count($result); ?>)</caption>
							<tr><th>DateTime</th><th>By</th><th>To Address</th><th>From Address</th><th>Subject</th><th>Email</th></tr>
							<?php
							foreach($result AS $row)
							{
								echo '<tr>';
								echo '<td>' . Date::to($row['created'], Date::DATETIME) . '</td>';
								echo '<td>' . DAO::getSingleValue($link, "SELECT CONCAT(users.firstnames, ' ', users.surname) FROM users WHERE users.id = '{$row['by_whom']}'") . '</td>';
								echo '<td>' . $row['email_to'] . '</td>';
								echo '<td>' . $row['email_from'] . '</td>';
								echo '<td>' . $row['email_subject'] . '</td>';
								echo '<td><span class="btn btn-xs btn-info" onclick="viewEmail(\''.$row['id'].'\');"><i class="fa fa-eye"></i> View Email</span> </td>';
								echo '</tr>';
							}
							?>
						</table>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>

<div id="dialogForskillsSimilarRecords" style="display:none" title="Skills Forward - Similar Results"></div>

<form name="frmRegisterUserToForskills" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
	<input type="hidden" name="_action" value="ajax_forskills">
	<input type="hidden" name="subaction" value="registerUser">
	<input type="hidden" name="studentRef" value="<?php echo $vo->ob_username; ?>">
	<input type="hidden" name="email" value="<?php echo $vo->home_email; ?>">
	<input type="hidden" name="firstName" value="<?php echo $vo->firstnames; ?>">
	<input type="hidden" name="lastName" value="<?php echo $vo->surname; ?>">
	<input type="hidden" name="dob" value="<?php echo Date::to($vo->dob, 'Y-m-d'); ?>">
	<input type="hidden" name="gender" value="<?php echo in_array($vo->gender, ['M', 'F']) ? $vo->gender : ''; ?>">
	<input type="hidden" name="ninumber" value="<?php echo $vo->ni; ?>">
	<input type="hidden" name="address1" value="<?php echo $vo->home_address_line_1; ?>">
	<input type="hidden" name="address2" value="<?php echo $vo->home_address_line_2; ?>">
	<input type="hidden" name="address3" value="<?php echo $vo->home_address_line_3; ?>">
	<input type="hidden" name="address4" value="<?php echo $vo->home_address_line_4; ?>">
	<input type="hidden" name="postcode" value="<?php echo $vo->home_postcode; ?>">
	<input type="hidden" name="phone1" value="<?php echo $vo->home_telephone; ?>">
	<input type="hidden" name="phone2" value="<?php echo $vo->home_mobile; ?>">
	<input type="hidden" name="password" value="<?php echo $password; ?>">
</form>

<form name="frmSaveNewlyCreatedForskillsLearnerInSunesis" action="<?php echo $_SERVER['PHP_SELF']; ?>"
      method="post">
	<input type="hidden" name="_action" value="ajax_forskills">
	<input type="hidden" name="subaction" value="saveNewlyCreatedUserInSunesis">
	<input type="hidden" name="sunesis_username" value="<?php echo $vo->ob_username; ?>">
	<input type="hidden" name="password" value="<?php echo $password; ?>">
	<input type="hidden" name="data" value="">
</form>

<form method="post" role="form" class="form-horizontal" name="frmLearner" action="<?php echo $_SERVER['PHP_SELF']; ?>">
	<input type="hidden" name="_action" value="ajax_onboarding" />
	<input type="hidden" name="subaction" value="createSunesisLearner" />
	<input type="hidden" name="ob_id" value="<?php echo $vo->id; ?>" />
</form>




<script>
var _p = '<?php echo $password; ?>';
$(function () {
	$('.datepicker').datepicker({
		format:'dd/mm/yyyy',
		yearRange:'c-50:c+50'
	});

	$('.datepicker').attr('class', 'datepicker');

	$('#dialogForskillsSimilarRecords').dialog({
		modal:true,
		width:450,
		closeOnEscape:true,
		autoOpen:false,
		resizable:false,
		draggable:false,
		buttons:{
			'Delete':function () {

			},
			'Cancel':function () {
				$(this).dialog('close');
			}
		}
	});

	$('#frmEmailBody').summernote({
		toolbar:[
			['style', ['bold', 'italic', 'underline', 'clear']],
			['fontsize', ['fontsize']],
			['para', ['ul', 'ol', 'paragraph']],
			['height', ['height']],
			['insert', ['link', 'picture', 'hr']]
		],
		height:300,
		callbacks:{
			onImageUpload:function (files, editor, welEditable) {
				sendFile(files[0], editor, welEditable);
			}
		}
	});

	<?php if (!is_null($forskills_info)) { ?>
	//getUserAssessments();
	<?php } ?>

});

function getUserAssessments() {
	$('#divForskillsAssessment').html('<i class="fa fa-refresh fa-spin"></i> <img id="loading" src="images/loading.gif" alt="Loading" />');

	var url = 'do.php?_action=ajax_forskills' +
		'&subaction=getUserAssessments' +
		'&username=' + encodeURIComponent('<?php echo $vo->ob_username; ?>');

	var client = ajaxRequest(url);

	if (client.responseText == 'No records found matching filter parameters.')
		$('#divForskillsAssessment').html('<i class="fa fa-info-circle"></i> No assessment record has been found.');
	else
		$('#divForskillsAssessment').html(client.responseText);
}

function registerLearnerToForskills() {
	console.log('starting registerLearnerToForskills');
	var result = searchLearnerInForskills();
	result = $.parseJSON(result);
	if (result.code != '404') {
		return alert('Duplication: registration failed.');
	}

	var regForm = document.forms["frmRegisterUserToForskills"];

	var client = ajaxPostForm(regForm);
	if (client.status != 200) {
		return alert('Something went wrong, please raise a support request with the details of your action');
	}
	else {
		console.log(client.responseText);
		var response = $.parseJSON(client.responseText);
		if(response.code == 200) {
			saveNewlyCreatedForskillsLearnerInSunesis();
		}
		else {
			alert("API Error\r\nCode: " + response.code + "\r\nMessage: " + response.errors[0]+"\r\nPlease raise a support request detailing your action.");
		}
	}


}

function saveNewlyCreatedForskillsLearnerInSunesis() {
	console.log('starting saveNewlyCreatedForskillsLearnerInSunesis');
	var result = searchLearnerInForskills();
	result = $.parseJSON(result);
	if (result.data.length != 1) {
		return alert('Something went wrong, please raise a support request with the details of your action.');
	}

	var myForm = document.forms["frmSaveNewlyCreatedForskillsLearnerInSunesis"];

	myForm.data.value = JSON.stringify(result.data);

	var client = ajaxPostForm(myForm);

	if (!client) {
		alert(client);
	}

	window.location.reload();
}

function searchLearnerInForskills() {
	console.log('starting searchLearnerInForskills');
	var result = '';
	var myForm = document.forms["frmSearchLearnerInForskills"];

	var client = ajaxPostForm(myForm);

	if (client.status != 200) {
		return alert('Something went wrong, please raise a support request with the details of your action.');
	}
	else {
		return client.responseText;
	}
}

function copyUrl(url, copy_ele) {
	var copyText = document.getElementById(url);
	copyText.select();
	copyText.setSelectionRange(0, 99999);
	document.execCommand('copy');
	var tooltip = document.getElementById(copy_ele);
	tooltip.innerHTML = "Copied: " + copyText.value;
	$("#" + copy_ele).show().delay(1000).hide(0);
}

function showHidePassword(ele) {
	var show = !!($(ele).html() == 'Show');

	if (show) {
		$(ele).html('Hide');
		$('#lblPassword').html(_p);
	}
	else {
		$(ele).html('Show');
		$('#lblPassword').html('**********');
	}


}

function sendEmail()
{
	var frmEmail = document.forms["frmEmail"];
	if(!validateForm(frmEmail))
	{
		return;
	}

	var client = ajaxPostForm(frmEmail);
	if(client)
	{
		if(client.responseText == 'success')
			alert('Email has been sent successfully.');
		else
			alert('Unknown Email Error: Email has not been sent.');
	}
	else
	{
		alert(client);
	}
	window.location.reload();
}

function load_email_template_in_frmEmail()
{
	var frmEmail = document.forms["frmEmail"];
	var ob_learner_id = '<?php echo $vo->id; ?>';
	var email_template_type = frmEmail.frmEmailTemplate.value;

	if(email_template_type == '')
	{
		alert('Please select template from templates list');
		frmEmail.frmEmailTemplate.focus();
		return false;
	}

	function loadAndPrepareEmailTemplateCallback(client)
	{
		if(client.status == 200)
			$("#frmEmailBody").summernote("code", client.responseText);
	}

	var client = ajaxRequest('do.php?_action=ajax_actions&subaction=loadAndPrepareEmailTemplate' +
		'&entity_type=ob_learners&entity_id=' + ob_learner_id +
		'&template_type=' + email_template_type, null, null, loadAndPrepareEmailTemplateCallback);
}

function viewEmail(email_id)
{
	if(email_id == '')
		return;

	var postData = 'do.php?_action=ajax_onboarding'
			+ '&subaction=' + encodeURIComponent("getEmail")
			+ '&email_id=' + encodeURIComponent(email_id)
		;

	var req = ajaxRequest(postData);
	$("<div class='small'></div>").html(req.responseText).dialog({
		id: "dialogEmailView",
		title: "Email",
		resizable: false,
		modal: true,
		width: 750,
		height: 500,

		buttons: {
			'Close': function() {$(this).dialog('close');}
		}
	});
}

function saveEligibility()
{
	var frmEligibility = document.forms["frmEligibility"];
	if(!validateForm(frmEligibility))
	{
		return false;
	}

	frmEligibility.submit();
}

function frmEmailTemplate_onchange(template)
{
    var client_name = '<?php echo $client_name = SystemConfig::getEntityValue($link, "client_name"); ?>';
    if(template.value == "SKILLS_FORWARD_LOGIN_DETAILS")
    {
        document.forms["frmEmail"].frmEmailSubject.value = client_name + " - Apprenticeship Onboarding Step 1";
    }
    if(template.value == "K_AND_S_URL")
    {
        document.forms["frmEmail"].frmEmailSubject.value = client_name + " - Apprenticeship Onboarding Step 2";
    }
    if(template.value == "INITIAL_SCREENING_URL")
    {
        document.forms["frmEmail"].frmEmailSubject.value = client_name + " - Apprenticeship Onboarding Step 3";
    }
    if(template.value == "ONBOARDING_URL")
    {
        document.forms["frmEmail"].frmEmailSubject.value = client_name + " - Onboarding Email";
    }
    if(template.value == "APPRENTICESHIP_SCREENING_SESSION")
    {
        document.forms["frmEmail"].frmEmailSubject.value = client_name + " - Apprenticeship Screening Session";
    }	
}

function deleteObLearner(ele)
{
    if(!confirm('This action is irreversible and cannot be undone. Are you sure you want to continue?'))
    {
        return;
    }

    $(ele).attr('disabled', true);
    ele.style.pointerEvents = 'none';
    $(ele).removeAttr("onclick");

    var ob_learner_id = '<?php echo $vo->id; ?>';
    $("#frmDeleteObLearner").append('<form action="do.php" method="POST">');
    $("#frmDeleteObLearner form").append('<input type="hidden" name="_action" value="ajax_actions"/>');
    $("#frmDeleteObLearner form").append('<input type="hidden" name="subaction" value="deleteObLearner"/>');
    $("#frmDeleteObLearner form").append('<input type="hidden" name="ob_learner_id" value="<?php echo $vo->id; ?>"/>');
    $("#frmDeleteObLearner form").append('<input type="hidden" name="token" value="<?php echo md5("sunesis{$_SESSION['user']->id}"); ?>"/>');
    $("#frmDeleteObLearner form").submit();
}
</script>
</body>
</html>
