<?php /* @var $tr TrainingRecord */ ?>
<?php /* @var $qualification StudentQualification */ ?>
<?php /* @var $employer Organisation */ ?>
<?php /* @var $framework Framework */ ?>

<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<title>Sunesis | Review Form</title>
	<meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
	<link rel="stylesheet" href="module_tracking/css/common.css" type="text/css"/>
	<link rel="stylesheet" href="/assets/adminlte/bootstrap/css/bootstrap.min.css">
	<link rel="stylesheet" href="/assets/adminlte/plugins/jQueryUI/jquery-ui.css">
	<link rel="stylesheet" href="/assets/adminlte/dist/css/AdminLTE.min.css">
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.5.0/css/font-awesome.min.css">
	<link href="/assets/adminlte/plugins/toastr/toastr.min.css" rel="stylesheet">
	<link rel="stylesheet" href="/assets/adminlte/plugins/iCheck/all.css">
	<link href="https://gitcdn.github.io/bootstrap-toggle/2.2.2/css/bootstrap-toggle.min.css" rel="stylesheet">
	<link rel="stylesheet" type="text/css" href="/assets/css/jquery.timepicker.css" />

	<!--[if lt IE 9]>
	<script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
	<script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
	<![endif]-->

	<style>
		html,
		body {
			height: 100%
		}
		textarea {
			border:1px solid #3366FF;
			border-radius: 5px;
			border-left: 5px solid #3366FF;
		}
		.ui-datepicker .ui-datepicker-title select {
			color: #000;
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
		#btnGoTop {
			display: none;
			position: fixed;
			bottom: 20px;
			right: 30px;
			z-index: 99;
			font-size: 18px;
			border: none;
			outline: none;
			color: white;
			cursor: pointer;
			padding: 5px;
			border-radius: 4px;
		}
		.fieldValue{
			box-shadow: 2px 2px 5px gray;
		}

	</style>

</head>

<body>

<div class="row">
	<div class="col-lg-12">
		<div class="banner">
			<div class="Title" style="margin-left: 6px;">Review Form</div>
			<div class="ButtonBar">
                <?php if(isset($_SESSION['user'])) { ?>
				<span class="btn btn-sm btn-default" onclick="window.location.href='<?php echo $_SESSION['bc']->getPrevious(); ?>';"><i class="fa fa-arrow-circle-o-left"></i> Close</span>
                <?php } ?>
				<?php
					echo $disable_save ? '<span class="btn btn-sm btn-default disabled"><i class="fa fa-save"></i> Save</span>' : '<span class="btn btn-sm btn-default" onclick="save();"><i class="fa fa-save"></i> Save</span>';
				?>
			</div>
			<div class="ActionIconBar">
				<span class="btn btn-sm btn-info fa fa-file-pdf-o"
				      onclick="window.location.href='do.php?_action=sd_form&subaction=export&review_id=<?php echo $review->id; ?>&tr_id=<?php echo $tr->id; ?>'" title="Generate pdf">
				</span>
				<div class="pull-right" id="clock"></div>
			</div>
		</div>

	</div>
</div>
<div class="row">
	<div class="col-lg-12">
		<?php
		if(isset($_SESSION['bc']))
			$_SESSION['bc']->render($link);
		?>
	</div>
</div>

<br>

<div class="container-fluid">
    <form name="frmReview" id="frmReview" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
    <input type="hidden" name="_action" value="save_review_form" />
    <input type="hidden" name="review_id" value="<?php echo $review->id; ?>" />
    <input type="hidden" name="tr_id" value="<?php echo $tr->id; ?>" />

		<div class="row">
			<div class="col-sm-4">
				<img class="img img-responsive" style="" src="images/logos/ASWatson.png" alt="">
			</div>
			<div class="col-sm-4">
				<div style="border: #0000ff solid 1px">
					<h5 class="text-bold text-center">Overall Progression</h5>
					<h5 style="font-size: 40px;" class="text-bold text-center text-blue"><?php echo $review_form->overall_progression == '' ? sprintf("%.2f", $tr->l36) : $review_form->overall_progression; ?>%</h5>
					<input type="hidden" name="overall_progression" value="<?php echo $review_form->overall_progression == '' ? sprintf("%.2f", $tr->l36) : $review_form->overall_progression; ?>" />
				</div>
			</div>
			<div class="col-sm-4">
				<p class="lead text-bold">Monthly Visit and Review Record</p>
				<p><div class="form-group">
					<label for="date" class="col-sm-5 control-label fieldLabel_compulsory">Next Review Date:</label>
					<div class="col-sm-7">
						<?php
						echo !$disable_save ?
							HTML::datebox("a_next_visit", $review_form->a_next_visit, true, false):
							'<span class="fieldValue" style="padding: 1px 5px 2px 5px;">' . Date::toShort($review_form->a_next_visit) . '</span><br>';
						?>
					</div>
				</div></p><p>
				<div class="form-group">
					<label for="date" class="col-sm-5 control-label fieldLabel_compulsory">Next Review Time:</label>
					<div class="col-sm-7">
						<?php
						echo !$disable_save ?
							HTML::timebox('a_next_visit_time', $review_form->a_next_visit_time, false):
							'<p><span class="fieldValue" style="padding: 1px 5px 2px 5px;">' . $review_form->a_next_visit_time . '</span></p>';
						?>
					</div>
				</div></p>
			</div>
		</div>

		<div class="row"  style="border: #0000ff solid 1px">
			<div class="col-sm-3">
				<span class="text-bold lead">Visit Details</span>
			</div>
			<div class="col-sm-3 text-center <?php echo $superdrug ? 'bg-green' : ''; ?>">
				<span class="lead">Superdrug</span> &nbsp; <?php echo $superdrug ? '<i class="fa fa-check-square fa-lg"></i>' : '';?>
			</div>
			<div class="col-sm-3 text-center <?php echo $savers ? 'bg-green' : ''; ?>">
				<span class="lead">Savers</span> &nbsp; <?php echo $savers ? '<i class="fa fa-check-square fa-lg"></i>' : '';?>
			</div>
			<div class="col-sm-3 text-center">

			</div>
		</div>

		<div class="row">
			<div class="col-sm-4">
				<ul class="list-group list-group-unbordered">
					<li class="list-group-item">
						<b>Learner Name: </b> <span class="fieldValue" style="padding: 2px;"><?php echo $tr->firstnames .' ' . $tr->surname; ?></span>
					</li>
					<li class="list-group-item">
						<b>Qualification & Level: </b> <br>
						<span class="fieldValue" style="padding: 1px 5px 2px 5px;">
							<?php echo isset($qualification->id) ? $qualification->id . ' ' . $qualification->title : $framework->title; ?>
						</span>
					</li>
					<li class="list-group-item">
						<b>Qualification Start Date: </b>
						<span class="fieldValue" style="padding: 2px;">
							<?php echo isset($qualification->start_date) ? Date::toShort($qualification->start_date) : Date::toShort($tr->start_date); ?>
						</span>
					</li>
				</ul>
			</div>
			<div class="col-sm-4">
				<ul class="list-group list-group-unbordered">
					<li class="list-group-item">
						<b>Assessor Name: </b> <span class="fieldValue" style="padding: 2px;"><?php echo DAO::getSingleValue($link, "SELECT CONCAT(firstnames, ' ', surname) FROM users WHERE users.id = '{$tr->assessor}'"); ?></span>
					</li>
					<li class="list-group-item">
						<b>Today's Date: </b>
						<?php
						if(!$disable_save)
							echo HTML::datebox("a_visit_date", is_null($review_form->a_visit_date) ? date('d/m/Y') : $review_form->a_visit_date, true, false);
						else
							echo '<span class="fieldValue" style="padding: 2px;">' . Date::toShort($review_form->a_visit_date) . '</span>';
						?>
					</li>
					<li class="list-group-item">
						<b>Expected End Date: </b>
						<span class="fieldValue" style="padding: 2px;">
							<?php echo isset($qualification->end_date) ? Date::toShort($qualification->end_date) : Date::toShort($tr->target_date); ?>
						</span>
					</li>
				</ul>
			</div>
			<div class="col-sm-4">
				<ul class="list-group list-group-unbordered">
					<li class="list-group-item">
						<b>Location: </b><br> <span class="fieldValue" style="padding: 1px 5px 2px 5px;"><?php echo $employer->legal_name; ?></span>
					</li>
					<li class="list-group-item">
						<b>Start Time / End time: </b>
						<?php
						if(!$disable_save)
							echo HTML::timebox('a_visit_start', $review_form->a_visit_start, false) . ' / ' . HTML::timebox('a_visit_end', $review_form->a_visit_end, false);
						else
							echo '<span class="fieldValue" style="padding: 2px;">' . $review_form->a_visit_start . ' / ' . $review_form->a_visit_end . '</span>';
						?>
					</li>
					<li class="list-group-item">
						<b>Contract End Date: </b> <span class="fieldValue" style="padding: 2px;">TBD</span>
					</li>
				</ul>
			</div>
		</div>

		<div class="row">
			<div class="col-sm-12">
				<div class="box box-solid box-success">
					<div class="box-header">
						<span class="box-title"><b>Record of any communications between visits (e.g. calls, emails or text support)</b></span>
					</div>
					<div class="box-body">
						<?php
						if(!$disable_save)
                            echo '<textarea onblur="return checkLength(event, this, 800)" onkeypress="return checkLength(event, this, 800)" name="a_record_of_comm" id="a_record_of_comm" style="width: 100%" rows="5">' . $review_form->a_record_of_comm . '</textarea>';
                        else
                            echo $review_form->a_record_of_comm == '' ? '<p><i class="fa fa-info-circle"></i> <i class="text-muted">Nothing has been entered by an assessor</i></p>' : '<p>' . nl2br((string) $review_form->a_record_of_comm) . '</p>';
                        ?>
					</div>
				</div>
			</div>
		</div>

		<div class="row">
			<div class="col-sm-12">
				<div class="box box-solid box-success">
					<div class="box-header">
						<span class="box-title"><b>Review of work to be completed since last visit</b></span>
					</div>
					<div class="box-body">
						<div class="row">
							<div class="col-sm-7">
								<div class="box box-solid box-default">
									<div class="box-header">
										<span class="box-title"><b>Standards/ Workbooks/ E&D/ Other</b></span>
									</div>
									<div class="box-body">
										<?php
										if(!$disable_save)
										{
											$std_quals_result = DAO::getResultset($link, "SELECT id, title, qualification_type FROM student_qualifications WHERE tr_id = '{$tr->id}'", DAO::FETCH_ASSOC);
											$non_fs_quals = '';
											$fs_quals = '';
											foreach($std_quals_result AS $std_qual)
											{
												if($std_qual['qualification_type'] == 'FS')
													$fs_quals = $std_qual['id'] . ' - ' . $std_qual['title'] . PHP_EOL;
												else
													$non_fs_quals = $std_qual['id'] . ' - ' . $std_qual['title'] . PHP_EOL;
											}
											if(is_null($review_form->a_qual_last_visit))
											{
												echo '<textarea name="a_qual_last_visit" id="a_qual_last_visit" style="width: 100%" rows="5">';
												echo $non_fs_quals;
												echo '</textarea>';
											}
											else
											{
												echo '<textarea name="a_qual_last_visit" id="a_qual_last_visit" style="width: 100%" rows="5">' . $review_form->a_qual_last_visit . '</textarea>';
											}
										}
										else
										{
											echo $review_form->a_qual_last_visit == '' ? '<p><i class="fa fa-info-circle"></i> <i class="text-muted">Nothing has been entered by an assessor</i></p>' : '<p>' . nl2br((string) $review_form->a_qual_last_visit) . '</p>';
										}
										?>
									</div>
								</div>
							</div>
							<div class="col-sm-5">
								<div class="box box-solid box-default">
									<div class="box-header">
										<span class="box-title"><b>Functional Skills (if applicable)</b></span>
									</div>
									<div class="box-body">
										<?php
										if(!$disable_save)
											echo $review_form->a_fs_last_visit == '' ?
												'<textarea name="a_fs_last_visit" id="a_fs_last_visit" style="width: 100%" rows="5">' . $fs_quals . '</textarea>' :
												'<textarea name="a_fs_last_visit" id="a_fs_last_visit" style="width: 100%" rows="5">' . $review_form->a_fs_last_visit . '</textarea>';
										else
											echo $review_form->a_fs_last_visit == '' ? '<p><i class="fa fa-info-circle"></i> <i class="text-muted">Nothing has been entered by an assessor</i></p>' : '<p>' . nl2br((string) $review_form->a_fs_last_visit) . '</p>';
										?>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>

		<div class="row">
			<div class="col-sm-12">
				<div class="box box-solid box-success">
					<div class="box-header">
						<span class="box-title"><b>Record of assessment activities (as planned on last visit) undertaken today</b></span>
					</div>
					<div class="box-body">
						<div class="row">
							<div class="col-sm-12">
								<div class="box box-solid box-default">
									<div class="box-header">
										<span class="box-title"><b>Standards/ Workbooks/ E&D/ Other</b></span>
									</div>
									<div class="box-body">
										<?php
										if(!$disable_save)
											echo '<textarea name="a_qual_today" id="a_qual_today" style="width: 100%" rows="5">' . $review_form->a_qual_today . '</textarea>';
										else
											echo $review_form->a_qual_today == '' ? '<p><i class="fa fa-info-circle"></i> <i class="text-muted">Nothing has been entered by an assessor</i></p>' : '<p>' . nl2br((string) $review_form->a_qual_today) . '</p>';
										?>
									</div>
								</div>
							</div>
							<div class="col-sm-12">
								<div class="box box-solid box-default">
									<div class="box-header">
										<span class="box-title"><b>Functional Skills</b></span>
									</div>
									<div class="box-body">
										<?php
										if(!$disable_save)
											echo '<textarea name="a_fs_today" id="a_fs_today" style="width: 100%" rows="5">' . $review_form->a_fs_today . '</textarea>';
										else
											echo $review_form->a_fs_today == '' ? '<p><i class="fa fa-info-circle"></i> <i class="text-muted">Nothing has been entered by an assessor</i></p>' : '<p>' . $review_form->a_fs_today . '</p>';
										?>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>

		<div class="row">
			<div class="col-sm-12">
				<div class="box box-solid box-success">
					<div class="box-header">
						<span class="box-title"><b>Work for YOU to complete by next visit</b></span>
					</div>
					<div class="box-body">
						<div class="row">
							<div class="col-sm-7">
								<div class="box box-solid box-default">
									<div class="box-header">
										<span class="box-title"><b>Standards/ Workbooks/ E&D/ Other</b></span>
									</div>
									<div class="box-body">
										<?php
										if(!$disable_save)
											echo '<textarea name="a_qual_next_visit" id="a_qual_next_visit" style="width: 100%" rows="10">' . $review_form->a_qual_next_visit . '</textarea>';
										else
											echo $review_form->a_qual_next_visit == '' ? '<p><i class="fa fa-info-circle"></i> <i class="text-muted">Nothing has been entered by an assessor</i></p>' : '<p>' . nl2br((string) $review_form->a_qual_next_visit) . '</p>';
										?>
									</div>
								</div>
							</div>
							<div class="col-sm-5">
								<div class="box box-solid box-default">
									<div class="box-header">
										<span class="box-title"><b>Functional Skills (if applicable)</b></span>
									</div>
									<div class="box-body">
										<?php
										if(!$disable_save)
											echo '<textarea name="a_fs_next_visit" id="a_fs_next_visit" style="width: 100%" rows="10">' . $review_form->a_fs_next_visit . '</textarea>';
										else
											echo $review_form->a_fs_next_visit == '' ? '<p><i class="fa fa-info-circle"></i> <i class="text-muted">Nothing has been entered by an assessor</i></p>' : '<p>' . nl2br((string) $review_form->a_fs_next_visit) . '</p>';
										?>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>

		<div class="row">
			<div class="col-sm-12">
				<div class="box box-solid box-success">
					<div class="box-header">
						<span class="box-title"><b>Activities to take place at next visit</b></span>
					</div>
					<div class="box-body">
						<div class="row">
							<div class="col-sm-7">
								<div class="box box-solid box-default">
									<div class="box-header">
										<span class="box-title"><b>Standards/ Workbooks/ E&D/ Other</b></span>
									</div>
									<div class="box-body">
										<?php
										if(!$disable_save)
											echo '<textarea name="a_qual_act_next_visit" id="a_qual_act_next_visit" style="width: 100%" rows="10">' . $review_form->a_qual_act_next_visit . '</textarea>';
										else
											echo $review_form->a_qual_act_next_visit == '' ? '<p><i class="fa fa-info-circle"></i> <i class="text-muted">Nothing has been entered by an assessor</i></p>' : '<p>' . nl2br((string) $review_form->a_qual_act_next_visit) . '</p>';
										?>
   									</div>
								</div>
							</div>
							<div class="col-sm-5">
								<div class="box box-solid box-default">
									<div class="box-header">
										<span class="box-title"><b>Functional Skills (if applicable)</b></span>
									</div>
									<div class="box-body">
										<?php
										if(!$disable_save)
											echo '<textarea name="a_fs_act_next_visit" id="a_fs_act_next_visit" style="width: 100%" rows="10">' . $review_form->a_fs_act_next_visit . '</textarea>';
										else
											echo $review_form->a_fs_act_next_visit == '' ? '<p><i class="fa fa-info-circle"></i> <i class="text-muted">Nothing has been entered by an assessor</i></p>' : '<p>' . $review_form->a_fs_act_next_visit . '</p>';
										?>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>

		<div class="row">
			<div class="col-sm-12">
				<div class="box box-solid box-success">
					<div class="box-header"><span class="box-title"><b>% Progression Review</b></span></div>
					<div class="box-body">
						<div class="row">
							<div class="col-sm-12">
								<div class="table-responsive">
									<table class="table table-bordered">
										<?php
										if($is_workbook_learner)
										{
											$this->renderWorkbookProgress($link, $qualification, $tr);
										}
										else
										{
											$this->renderNonWorkbookProgress($link, $qualification, $tr);
										}
										?>
									</table>
								</div>
							</div>
							<div class="col-sm-6">
								<div class="table-responsive">
									<table class="table table-bordered">
										<caption style="display: table-caption;text-align: center" class="text-bold">Booklets</caption>
										<thead><tr><th>All About You</th><th>E&D</th></tr></thead>
										<tbody>
										<tr>
											<td><textarea name="a_booklet_abu" style="width: 100%;" rows="4"><?php echo $review_form->a_booklet_abu; ?></textarea></td>
											<td><textarea name="a_booklet_end" style="width: 100%;" rows="4"><?php echo $review_form->a_booklet_end; ?></textarea></td>
										</tr>
										<tr><th>Health & Safety</th><th>Substance Misuse</th></tr>
										<tr>
											<td><textarea name="a_booklet_hns" style="width: 100%;" rows="4"><?php echo $review_form->a_booklet_hns; ?></textarea></td>
											<td><textarea name="a_booklet_sm" style="width: 100%;" rows="4"><?php echo $review_form->a_booklet_sm; ?></textarea></td>
										</tr>
										</tbody>
									</table>
								</div>
							</div>
							<div class="col-sm-6">
								<div class="table-responsive">
									<table class="table table-bordered text-center">
										<caption style="display: table-caption;text-align: center" class="text-bold">
											Maths / ICT F.Skills Target Date: <?php echo HTML::datebox("a_fs_maths_ict_target_date", $review_form->a_fs_maths_ict_target_date, true, false); ?>
										</caption>
										<thead><tr><th>Maths</th><th>Level</th><th>ICT</th><th>Level</th></tr></thead>
										<tbody>
										<tr>
											<td><input type="text" name="a_maths_level1" value="<?php echo $review_form->a_maths_level1; ?>" size="5" /></td><td>1</td>
											<td><input type="text" name="a_ict_level1" value="<?php echo $review_form->a_ict_level1; ?>" size="5" /></td><td>1</td>
										</tr>
										<tr>
											<td><input type="text" name="a_maths_level2" value="<?php echo $review_form->a_maths_level2; ?>" size="5" /></td><td>2</td>
											<td><input type="text" name="a_ict_level2" value="<?php echo $review_form->a_ict_level2; ?>" size="5" /></td><td>2</td>
										</tr>
										<tr><th colspan="4">English F.Skills Target Date: <?php echo HTML::datebox("a_fs_eng_target_date", $review_form->a_fs_eng_target_date, true, false); ?></th></tr>
										<tr><th>Reading</th><th>Writing</th><th>Speaking Listening</th><th>Level</th></tr>
										<tr>
											<td><input type="text" name="a_eng_read_level1" value="<?php echo $review_form->a_eng_read_level1; ?>" size="5" /></td>
											<td><input type="text" name="a_eng_write_level1" value="<?php echo $review_form->a_eng_write_level1; ?>" size="5" /></td>
											<td><input type="text" name="a_eng_speak_listen_level1" value="<?php echo $review_form->a_eng_speak_listen_level1; ?>" size="5" /></td>
											<td>1</td>
										</tr>
										<tr>
											<td><input type="text" name="a_eng_read_level2" value="<?php echo $review_form->a_eng_read_level2; ?>" size="5" /></td>
											<td><input type="text" name="a_eng_write_level2" value="<?php echo $review_form->a_eng_write_level2; ?>" size="5" /></td>
											<td><input type="text" name="a_eng_speak_listen_level2" value="<?php echo $review_form->a_eng_speak_listen_level2; ?>" size="5" /></td>
											<td>2</td>
										</tr>
										</tbody>
									</table>
								</div>
							</div>
							<div class="col-sm-5">
								<div class="box box-solid box-default">
									<div class="box-header">
										<span class="box-title"><b>Feedback on spellings, grammar and punctuation</b></span>
									</div>
									<div class="box-body">
										<?php
										if(!$disable_save)
											echo '<textarea name="a_fdbck_sgp" id="a_fdbck_sgp" style="width: 100%" rows="10">' . $review_form->a_fdbck_sgp . '</textarea>';
										else
											echo $review_form->a_fdbck_sgp == '' ? '<p><i class="fa fa-info-circle"></i> <i class="text-muted">Nothing has been entered by an assessor</i></p>' : '<p>' . $review_form->a_fdbck_sgp . '</p>';
										?>
									</div>
								</div>
							</div>
							<div class="col-sm-7">
								<div class="box box-solid box-info">
									<div class="box-header">
										<span class="box-title"><b>Equality and Diversity - what have you learnt and discussed since last visit?</b></span>
									</div>
									<div class="box-body">
										<?php
										if(!$disable_save)
											echo '<textarea name="l_equ_div" id="l_equ_div" style="width: 100%" rows="10">' . $review_form->l_equ_div . '</textarea>';
										else
											echo $review_form->l_equ_div == '' ? '<p><i class="fa fa-info-circle"></i> <i class="text-muted">Nothing has been entered by an assessor</i></p>' : '<p>' . $review_form->l_equ_div . '</p>';
										?>
									</div>
								</div>
							</div>
						</div>

						<div class="row">
							<div class="col-sm-7">
								<div class="box box-solid box-info">
									<div class="box-header">
										<span class="box-title"><b>What training, coaching or learning have you been involved in since last visit?</b></span>
									</div>
									<div class="box-body">
										<?php
										if(!$disable_save)
											echo '<textarea name="l_training" id="l_training" style="width: 100%" rows="5">' . $review_form->l_training . '</textarea>';
										else
											echo $review_form->l_training == '' ? '<p><i class="fa fa-info-circle"></i> <i class="text-muted">Nothing has been entered by an assessor</i></p>' : '<p>' . nl2br((string) $review_form->l_training) . '</p>';
										?>
									</div>
								</div>
							</div>
							<div class="col-sm-5">
								<div class="box box-solid box-info">
									<div class="box-header">
										<span class="box-title"><b>What are your future aspirations (Short/ long term)</b></span>
									</div>
									<div class="box-body">
										<?php
										if(!$disable_save)
											echo '<textarea name="l_future_asp" id="l_future_asp" style="width: 100%" rows="5">' . $review_form->l_future_asp . '</textarea>';
										else
											echo $review_form->l_future_asp == '' ? '<p><i class="fa fa-info-circle"></i> <i class="text-muted">Nothing has been entered by an assessor</i></p>' : '<p>' . $review_form->l_future_asp . '</p>';
										?>
									</div>
								</div>
							</div>
						</div>

						<div class="row">
							<div class="col-sm-12">
								<div class="box box-solid box-info">
									<div class="box-header">
										<span class="box-title"><b>What support are you receiving for your qualification?</b></span>
									</div>
									<div class="box-body">
										<?php
										if(!$disable_save)
											echo '<textarea name="l_support" id="l_support" style="width: 100%" rows="5">' . $review_form->l_support . '</textarea>';
										else
											echo $review_form->l_support == '' ? '<p><i class="fa fa-info-circle"></i> <i class="text-muted">Nothing has been entered by an assessor</i></p>' : '<p>' . $review_form->l_support . '</p>';
										?>
									</div>
								</div>
							</div>
						</div>

						<div class="row">
							<div class="col-sm-6">
								<div class="form-group">
									<label for="" class="col-sm-8 control-label fieldLabel_compulsory">Do you feel safe at work? </label>
									<div class="col-sm-4">
                                        <?php
                                        echo $review_form->l_feel_safe == 'Y' ?
	                                        '<input value="Y" class="yes_no_toggle" type="checkbox" name="l_feel_safe" id="l_feel_safe" data-toggle="toggle" checked="checked" data-toggle="toggle" data-on="Yes" data-off="No" data-onstyle="success" data-offstyle="danger" />':
	                                        '<input value="Y" class="yes_no_toggle" type="checkbox" name="l_feel_safe" id="l_feel_safe" data-toggle="toggle" data-on="Yes" data-off="No" data-onstyle="success" data-offstyle="danger" />';
                                        ?>
									</div>
								</div>
							</div>
							<div class="col-sm-6">
								<div class="form-group">
									<label for="" class="col-sm-8 control-label fieldLabel_compulsory">Have you had any accidents or incidents at work since our last meeting? </label>
									<div class="col-sm-4">
                                        <?php
										echo $review_form->l_had_acc == 'Y' ?
											'<input value="Y" class="yes_no_toggle" type="checkbox" name="l_had_acc" id="l_had_acc" data-toggle="toggle" checked="checked" data-toggle="toggle" data-on="Yes" data-off="No" data-onstyle="success" data-offstyle="danger" />':
											'<input value="Y" class="yes_no_toggle" type="checkbox" name="l_had_acc" id="l_had_acc" data-toggle="toggle" data-on="Yes" data-off="No" data-onstyle="success" data-offstyle="danger" />';
                                        ?>
									</div>
								</div>
							</div>
						</div>

						<hr>

						<div class="row">
							<div class="col-sm-6">
								<div class="form-group">
									<label for="" class="col-sm-8 control-label fieldLabel_compulsory">Have there been any changes since our last meeting?
										<small>e.g. places of work, duties, duty manager, change of address/ name</small>
									</label>
									<div class="col-sm-4">
										<?php
										echo $review_form->l_hav_changes == 'Y' ?
											'<input value="Y" class="yes_no_toggle" type="checkbox" name="l_hav_changes" id="l_hav_changes" data-toggle="toggle" checked="checked" data-toggle="toggle" data-on="Yes" data-off="No" data-onstyle="success" data-offstyle="danger" />':
											'<input value="Y" class="yes_no_toggle" type="checkbox" name="l_hav_changes" id="l_hav_changes" data-toggle="toggle" data-on="Yes" data-off="No" data-onstyle="success" data-offstyle="danger" />';
										?>
									</div>
								</div>
							</div>
							<div class="col-sm-6">
								<div class="form-group">
									<label for="" class="col-sm-8 control-label fieldLabel_compulsory">Do you have any health issues or other things which may affect your assessments? </label>
									<div class="col-sm-4">
										<?php
										echo $review_form->l_have_health_issues == 'Y' ?
											'<input class="yes_no_toggle" value="Y" type="checkbox" name="l_have_health_issues" id="l_have_health_issues" data-toggle="toggle" checked="checked" data-toggle="toggle" data-on="Yes" data-off="No" data-onstyle="success" data-offstyle="danger" />':
											'<input class="yes_no_toggle" value="Y" type="checkbox" name="l_have_health_issues" id="l_have_health_issues" data-toggle="toggle" data-on="Yes" data-off="No" data-onstyle="success" data-offstyle="danger" />';
										?>
									</div>
								</div>
							</div>
						</div>

						<hr>

						<div class="row">
							<div class="col-sm-6">
								<div class="box box-solid box-warning">
									<div class="box-header">
										<span class="box-title"><b>Duty Manager checklist</b></span>
									</div>
									<div class="box-body">
										<div class="table-responsive">
											<table class="table row-border">
												<tr class="bg-gray">
													<th>Is your apprentice</th>
													<th>Yes / No</th>
												</tr>
												<?php
												$i = 0;
												foreach($this->getManagerChecklist() AS $key => $value)
												{
													$i++;
													echo '<tr>';
													echo '<th>' . $value . '</th>';
													echo '<td>';
													$checked = $review_form->$key == 'Y' ? 'checked="checked"' : '';
													echo '<input class="yes_no_toggle" value="Y" type="checkbox" name="'.$key.'" id="'.$key.'" ' . $checked . ' data-toggle="toggle" data-toggle="toggle" data-on="Yes" data-off="No" data-onstyle="success" data-offstyle="danger" />';
													echo '</td>';
													echo '</tr>';
													if($i == 7)
													{
														echo '<tr class="bg-gray"><th colspan="2">Do they have:</th></tr>';
													}
												}
												?>
											</table>
										</div>
									</div>
								</div>
							</div>
							<div class="col-sm-6">
								<div class="box box-solid box-warning">
									<div class="box-header">
										<span class="box-title"><b>Duty Manager Feedback</b></span>
									</div>
									<div class="box-body">
										<?php
										if(!$disable_save)
											echo '<textarea name="m_feedback" id="m_feedback" style="width: 100%" rows="10">' . $review_form->m_feedback . '</textarea>';
										else
											echo $review_form->m_feedback == '' ? '<p><i class="fa fa-info-circle"></i> <i class="text-muted">Nothing has been entered by the assessor</i></p>' : '<p>' . nl2br((string) $review_form->m_feedback) . '</p>';
										?>
									</div>
								</div>
							</div>
						</div>

						<div class="row">
							<div class="col-sm-6">
								<div class="box box-solid box-default">
									<div class="box-header">
										<span class="box-title"><b>Assessor Feedback</b></span>
									</div>
									<div class="box-body">
										<?php
										if(!$disable_save)
											echo '<textarea name="a_feedback" id="a_feedback" style="width: 100%" rows="5">' . $review_form->a_feedback . '</textarea>';
										else
											echo $review_form->a_feedback == '' ? '<p><i class="fa fa-info-circle"></i> <i class="text-muted">Nothing has been entered by the assessor</i></p>' : '<p>' . nl2br((string) $review_form->a_feedback) . '</p>';
										?>
									</div>
								</div>
							</div>
							<div class="col-sm-6">
								<div class="box box-solid box-info">
									<div class="box-header">
										<span class="box-title"><b>Learner Feedback</b></span>
									</div>
									<div class="box-body">
										<?php
										if(!$disable_save)
											echo '<textarea name="l_feedback" id="l_feedback" style="width: 100%" rows="5">' . $review_form->l_feedback . '</textarea>';
										else
											echo $review_form->l_feedback == '' ? '<p><i class="fa fa-info-circle"></i> <i class="text-muted">Nothing has been entered by the assessor</i></p>' : '<p>' . nl2br((string) $review_form->l_feedback) . '</p>';
										?>
									</div>
								</div>
							</div>
						</div>

						<div class="row">
							<div class="col-sm-6">
								<div class="box box-solid box-default">
									<div class="box-header">
										<span class="box-title"><b>Changes to be notified to Admin Team.
											<small>e.g. put on break, back from break, address, job role</small></b></span>
									</div>
									<div class="box-body">
										<?php
										if(!$disable_save)
											echo '<textarea name="a_chngs_to_notify" id="a_chngs_to_notify" style="width: 100%" rows="5">' . $review_form->a_chngs_to_notify . '</textarea>';
										else
											echo $review_form->a_chngs_to_notify == '' ? '<p><i class="fa fa-info-circle"></i> <i class="text-muted">Nothing has been entered by the assessor</i></p>' : '<p>' . $review_form->a_chngs_to_notify . '</p>';
										?>
									</div>
								</div>
							</div>
							<div class="col-sm-6">
								<div class="box box-solid box-default">
									<div class="box-header">
										<span class="box-title"><b>Tests sat today <u>or</u> reason for non-completion</b></span>
									</div>
									<div class="box-body">
										<?php
										if(!$disable_save)
											echo '<textarea name="a_tests_sat" id="a_tests_sat" style="width: 100%" rows="5">' . $review_form->a_tests_sat . '</textarea>';
										else
											echo $review_form->a_tests_sat == '' ? '<p><i class="fa fa-info-circle"></i> <i class="text-muted">Nothing has been entered by the assessor</i></p>' : '<p>' . $review_form->a_tests_sat . '</p>';
										?>
									</div>
								</div>
							</div>
						</div>

					</div>
				</div>
			</div>
		</div>

<!--        --><?php //if(!$disable_save) {?>
<!--        <div class="row">-->
<!--	        <div class="col-sm-12">-->
<!--		        <div class="well well-sm">-->
<!--					Have you completed all the relevant sections? &nbsp;-->
<!--			        <input class="yes_no_toggle" value="Y" type="checkbox" name="form_completed" id="form_completed" data-toggle="toggle" data-on="Yes" data-off="No" data-onstyle="success" data-offstyle="danger" />-->
<!--					<p id="form_completed_help_yes" class="text-muted" style="display: none;">-->
<!--						<i class="fa fa-info-circle"></i> Please sign the form, enter date and click 'Save'. Please note that you will not be able to edit the form then.-->
<!--					</p>-->
<!--			        <p id="form_completed_help_no" class="text-muted">-->
<!--						<i class="fa fa-info-circle"></i> You can save your information and come back later to continue.-->
<!--					</p>-->
<!--		        </div>-->
<!--	        </div>-->
<!--        </div>-->
<!--	    --><?php //} ?>

        <div class="row">
	        <div class="col-sm-12 table-responsive">
		        <table class="table table-bordered">
			        <tr><th>&nbsp;</th><th>Name</th><th>Signature</th><th>Date</th></tr>
					<tr>
				        <td>Learner</td>
				        <td><h5 class="content-max-width text-bold"><span class="fieldValue" style="padding: 2px;"><?php echo $tr->firstnames . ' ' . $tr->surname; ?></span></h5></td>
				        <td>
							<span class="btn btn-info" onclick="getSignature('learner');">
								<img id="img_l_sign" src="do.php?_action=generate_image&<?php echo $review_form->l_sign != ''?$review_form->l_sign:'title=Click here to sign&font=Signature_Regular.ttf&size=25'; ?>" style="border: 2px solid;border-radius: 15px;" />
								<input type="hidden" name="l_sign" id="l_sign" value="<?php echo $review_form->l_sign; ?>" />
							</span>
				        </td>
				        <td>
					        <?php
					        if(!$disable_save)
						        echo '<span class="content-max-width">' . HTML::datebox('l_sign_date', $review_form->l_sign_date) . '</span>';
							else
								echo '<span class="fieldValue" style="padding: 2px;">' . Date::toShort($review_form->l_sign_date) . '</span>';
					        ?>
				        </td>
			        </tr>
			        <tr>
				        <td>Assessor</td>
				        <td>
					        <h5 class="content-max-width text-bold">
						        <span class="fieldValue" style="padding: 2px;"><?php echo DAO::getSingleValue($link, "SELECT CONCAT(firstnames, ' ', surname) FROM users WHERE users.id = '{$tr->assessor}'"); ?></span>
					        </h5>
				        </td>
				        <td>
							<span class="btn btn-info" onclick="getSignature('assessor');">
								<img id="img_a_sign" src="do.php?_action=generate_image&<?php echo $review_form->a_sign != ''?$review_form->a_sign:'title=Click here to sign&font=Signature_Regular.ttf&size=25'; ?>" style="border: 2px solid;border-radius: 15px;" />
								<input type="hidden" name="a_sign" id="a_sign" value="<?php echo $review_form->a_sign; ?>" />
							</span>
				        </td>
				        <td>
					        <?php
					        if(!$disable_save)
						        echo '<span class="content-max-width">' . HTML::datebox('a_sign_date', $review_form->a_sign_date) . '</span>';
					        else
						        echo '<span class="fieldValue" style="padding: 2px;">' . Date::toShort($review_form->a_sign_date) . '</span>';
					        ?>
				        </td>
			        </tr>
			        <tr>
				        <td>Duty Manager</td>
				        <td>
					        <?php
					        if(!$disable_save)
						        echo '<input type="text" class="form-control" name="m_name" id="m_name" value="' . $review_form->m_name . '" />';
					        else
						        echo '<h5 class="content-max-width text-bold"><span class="fieldValue" style="padding: 2px;">' . $review_form->m_name . '</span></h5>';
					        ?>

				        </td>
				        <td>
					        <span class="btn btn-info" onclick="getSignature('manager');">
								<img id="img_m_sign" src="do.php?_action=generate_image&<?php echo $review_form->m_sign != ''?$review_form->m_sign:'title=Click here to sign&font=Signature_Regular.ttf&size=25'; ?>" style="border: 2px solid;border-radius: 15px;" />
								<input type="hidden" name="m_sign" id="m_sign" value="<?php echo $review_form->m_sign; ?>" />
							</span>
				        </td>
				        <td>
					        <?php
					        if(!$disable_save)
						        echo '<span class="content-max-width">' . HTML::datebox('m_sign_date', $review_form->m_sign_date) . '</span>';
					        else
						        echo '<span class="fieldValue" style="padding: 2px;">' . Date::toShort($review_form->m_sign_date) . '</span>';
					        ?>
				        </td>
			        </tr>

		        </table>
	        </div>
        </div>

		<hr>

	</form>

	<footer class="text-center">
		<p><i>The Superdrug Apprenticeship Team, NRDC, Stadium Way, Dale Lane Ind. Est. South Elmsall, Pontefract, West Yorkshire<code>WF9 2XR</code></i></p>
		<p><i>Helpline - 01977 657 008  &nbsp; Text 07510 242238  &nbsp; Fax - 01977 657 057  &nbsp; Email - superdrugnvq@uk.aswatson.com  Version 10 May 2017</i></p>
	</footer>
</div>

<div id="panel_signature" title="Signature Panel">
	<div class="callout callout-info"><i class="fa fa-info-circle"></i> Enter the name/initials, press 'Generate' and select the signature font you like and press "Create". </div>
	<div class="table-responsive">
		<table class="table row-border">
			<tr>
				<td class="small">Enter the name/initials</td>
				<td><input type="text" id="signature_text" onkeypress="return onlyAlphabets(event,this);" /> &nbsp; <span class="btn btn-xs btn-primary" onclick="refreshSignature();">Generate</span> </td>
			</tr>
			<tr>
				<td onclick="SignatureSelected(this);" class="sigbox"><img id="img1" src=""  /></td>
				<td onclick="SignatureSelected(this);" class="sigbox"><img id="img2" src=""  /></td>
			</tr>
			<tr>
				<td onclick="SignatureSelected(this);" class="sigbox"><img id="img3" src=""  /></td>
				<td onclick="SignatureSelected(this);" class="sigbox"><img id="img4" src=""  /></td>
			</tr>
			<tr>
				<td onclick="SignatureSelected(this);" class="sigbox"><img id="img5" src=""  /></td>
				<td onclick="SignatureSelected(this);" class="sigbox"><img id="img6" src=""  /></td>
			</tr>
			<tr>
				<td onclick="SignatureSelected(this);" class="sigbox"><img id="img7" src=""  /></td>
				<td onclick="SignatureSelected(this);" class="sigbox"><img id="img8" src=""  /></td>
			</tr>
		</table>
	</div>
</div>

<?php if(!$disable_save) { ?>
<button class="btn btn-md btn-primary" type="button" onclick="save()" id="btnGoTop" title="Save the form"> <i class="fa fa-save"></i> Save &nbsp;</button>
<?php } else {?>
<button disabled="disabled" style="cursor: not-allowed" class="btn btn-md btn-primary>" type="button" id="btnGoTop" title="Save disabled - Form is completed"> <i class="fa fa-save"></i> Save &nbsp;</button>
<?php } ?>

<script src="/assets/adminlte/plugins/jQuery/jquery-2.2.3.min.js"></script>
<script src="/assets/adminlte/plugins/jQueryUI/jquery-ui.min.js"></script>
<script src="/assets/adminlte/bootstrap/js/bootstrap.min.js"></script>
<script src="/assets/adminlte/plugins/iCheck/icheck.min.js"></script>
<script src="https://gitcdn.github.io/bootstrap-toggle/2.2.2/js/bootstrap-toggle.min.js"></script>
<script src="/common.js" type="text/javascript"></script>
<script type="text/javascript" src="/assets/js/jquery/jquery.timepicker.js"></script>


<script language="JavaScript">

	// When the user scrolls down 20px from the top of the document, show the button
	window.onscroll = function() {scrollFunction()};

	function scrollFunction() {
		if (document.body.scrollTop > 20 || document.documentElement.scrollTop > 20) {
			document.getElementById("btnGoTop").style.display = "block";
		} else {
			document.getElementById("btnGoTop").style.display = "none";
		}
	}

	$(function(){

		$('.datepicker').datepicker({
			format: 'dd/mm/yyyy',
			yearRange: 'c-50:c+50'
		});

		$('.datepicker').attr('class');

		$(".timebox").timepicker({ timeFormat: 'H:i' });

		$('.timebox').bind('timeFormatError timeRangeError', function() {
			this.value = '';
			alert("Please choose a valid time");
			this.focus();
		});

		//if form is not available to edit then disable all checkboxes
		<?php if($disable_save){ ?>
		$('.yes_no_toggle').attr('disabled', 'disabled');
		<?php } ?>
	});

	$('#form_completed').on('change', function(){
		if(this.checked)
		{
			$('#form_completed_help_yes').show();
			$('#form_completed_help_no').hide();
		}
		else
		{
			$('#form_completed_help_yes').hide();
			$('#form_completed_help_no').show();
		}
	});

	function checkLength(e, t, l)
	{
		if(t.value.length>=l)
		{
//			alert('You have reached to the maximum length of this field');
			$("<div class='small'></div>").html('You have reached to the maximum length of this field').dialog({
				title: " Maximum number of characters ",
				resizable: false,
				modal: true,
				width: 500,
				maxWidth: 500,
				height: 'auto',
				maxHeight: 500,
				closeOnEscape: false,
				buttons: {
					'OK': function() {
						$(this).dialog('close');
						t.value = t.value.substr(0,l-1);
					}
				}
			}).css("background", "#FFF");
			//t.value = t.value.substr(0,l-1);
			//return false;
		}
	}

	function save()
	{
		var frmReview = document.forms["frmReview"];
		//var form_completed = frmReview.elements["form_completed"];

		var a_sign = frmReview.elements["a_sign"];
		var a_sign_date = frmReview.elements["a_sign_date"];
		if(a_sign.value.trim() != '' && a_sign_date.value.trim() == '')
		{
			alert('Please also provide assessor signature date');
			a_sign_date.focus();
			return;
		}

		var l_sign = frmReview.elements["l_sign"];
		var l_sign_date = frmReview.elements["l_sign_date"];
		if(l_sign.value.trim() != '' && l_sign_date.value.trim() == '')
		{
			alert('Please also provide learner signature date');
			l_sign_date.focus();
			return;
		}

		var m_sign = frmReview.elements["m_sign"];
		var m_sign_date = frmReview.elements["m_sign_date"];
		if(m_sign.value.trim() != '' && m_sign_date.value.trim() == '')
		{
			alert('Please also provide manager signature date');
			m_sign_date.focus();
			return;
		}

//		if(form_completed.checked && (l_sign.value.trim() == '' || a_sign.value.trim() == '' || m_sign.value.trim() == ''))
//		{
//			alert('Form can only be completed when you provide all three signatures.' );
//			return;
//		}

		frmReview.submit();
	}

	var phpLearnerSignature = '<?php echo $l_sign_img ?>';
	var phpAssessorSignature = '<?php echo $a_sign_img ?>';
	var phpManagerSignature = '<?php echo $review_form->m_sign; ?>';

	var fonts = Array("Little_Days.ttf","ArtySignature.ttf","Signerica_Medium.ttf","Champignon_Alt_Swash.ttf","Bailey_MF.ttf","Carolina.ttf","DirtyDarren.ttf","Ruf_In_Den_Wind.ttf");
	var sizes = Array(30,40,15,30,30,30,25,30);

	function refreshSignature()
	{
		for(var i = 1; i <= 8; i++)
			$("#img"+i).attr('src', 'images/loading.gif');

		for(var i = 0; i <= 7; i++)
			$("#img"+(i+1)).attr('src', 'do.php?_action=generate_image&title='+$("#signature_text").val()+'&font='+fonts[i]+'&size='+sizes[i]);
	}

	function loadDefaultSignatures()
	{
		for(var i = 1; i <= 8; i++)
			$("#img"+i).attr('src', 'images/loading.gif');

		for(var i = 0; i <= 7; i++)
			$("#img"+(i+1)).attr('src', 'do.php?_action=generate_image&title=Signature'+'&font='+fonts[i]+'&size='+sizes[i]);
	}

	function onlyAlphabets(e, t)
	{
		try {
			if (window.event) {
				var charCode = window.event.keyCode;
			}
			else if (e) {
				var charCode = e.which;
			}
			else { return true; }
			if ((charCode > 64 && charCode < 91) || (charCode > 96 && charCode < 123) || charCode == 32 || charCode == 39 || charCode == 45 || charCode == 8 || charCode == 46)
				return true;
			else
				return false;
		}
		catch (err) {
			alert(err.Description);
		}
	}

	function SignatureSelected(sig)
	{
		$(".sigboxselected").attr("class", "sigbox");
		sig.className = "sigboxselected";
	}

	$(function(){
		$( "#panel_signature" ).dialog({
			autoOpen: false,
			modal: true,
			draggable: false,
			width: "auto",
			height: 500,
			buttons: {
				'Create': function() {
					var panel = $(this).data('panel');
					if($('#signature_text').val() == '')
					{
						alert('Please input name/initials to generate signature.');
						$('#signature_text').focus();
						return;
					}
					if($('.sigboxselected').children('img')[0] === undefined)
					{
						alert('Please select your font');
						return;
					}
					var sign_field = '';
					if(panel == 'learner')
					{
						sign_field = 'l_sign';
					}
					if(panel == 'assessor')
					{
						sign_field = 'a_sign';
					}
					if(panel == 'manager')
					{
						sign_field = 'm_sign';
					}
					$("#img_"+sign_field).attr('src', $('.sigboxselected').children('img')[0].src);
					var _link = $('.sigboxselected').children('img')[0].src;
					_link = _link.split('&');
					$("#"+sign_field).val(_link[1]+'&'+_link[2]+'&'+_link[3]);
					if($('#'+sign_field).val() == '')
					{
						alert('Please create your signature');
						return;
					}

					$(this).dialog('close');
				},
				'Cancel': function() {$(this).dialog('close');}
			}
		});
	});

	function getSignature(user)
	{
		if(user == 'learner')
		{
			<?php if($_SESSION['user']->type != User::TYPE_LEARNER) { ?>
			alert('You cannot sign on learner\'s behalf.');
			return;
			<?php } ?>

			if(window.phpLearnerSignature == '')
			{
				$('#signature_text').val('');
				$( "#panel_signature").data('panel', 'learner').dialog( "open");
				return;
			}
			$('#img_l_sign').attr('src', 'do.php?_action=generate_image&'+window.phpLearnerSignature);
			$('#l_sign').val(window.phpLearnerSignature);
			return;
		}
		if(user == 'assessor')
		{
			<?php if($_SESSION['user']->type != User::TYPE_ASSESSOR) { ?>
			return;
			<?php } ?>

			if(window.phpAssessorSignature == '')
			{
				$('#signature_text').val('');
				$( "#panel_signature" ).data('panel', 'assessor').dialog( "open");
				return;
			}
			$('#img_a_sign').attr('src', 'do.php?_action=generate_image&'+window.phpAssessorSignature);
			$('#a_sign').val(window.phpAssessorSignature);
			return;
		}
		if(user == 'manager')
		{
			if(window.phpManagerSignature == '')
			{
				$('#signature_text').val('');
				$( "#panel_signature" ).data('panel', 'manager').dialog( "open");
				return;
			}
			$('#img_m_sign').attr('src', 'do.php?_action=generate_image&'+window.phpManagerSignature);
			$('#m_sign').val(window.phpManagerSignature);
			return;
		}
	}
</script>
<script type="text/javascript">
	var counter = 0;
	var timer = null;

	function tictac()
	{
		counter++;
		if(counter >= 240)
			$("#clock").html('Time since last saved: <span class="text-bold">' + counter + '</span> seconds');
		if(counter == 300)
		{
			var html = '<p><span class="text-bold">It has been 5 minutes since you last saved the form. </span></p>';
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
						save();
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
	<?php if(!$disable_save){?>
	startInterval();
	<?php } ?>

	<?php if($_SESSION['user']->type == User::TYPE_LEARNER) { ?>
	$("[name^='a_']").prop("disabled", true);
	$("[name^='m_']").prop("disabled", true);
	<?php } else {?>
	$("[name^='l_']").prop("disabled", true);
	<?php } ?>
</script>

</html>
