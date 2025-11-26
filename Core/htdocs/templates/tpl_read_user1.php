<?php /* @var $vo User */ ?>
<!DOCTYPE html>
<head xmlns="http://www.w3.org/1999/html">
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<title>View Learner</title>

	<link rel="stylesheet" href="module_tracking/css/common.css" type="text/css"/>
	<link rel="stylesheet" href="/assets/adminlte/bootstrap/css/bootstrap.min.css">
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.5.0/css/font-awesome.min.css">
	<link rel="stylesheet" href="/assets/adminlte/plugins/chosen/bootstrap-chosen.css">
	<link rel="stylesheet" href="/assets/adminlte/plugins/jQueryUI/jquery-ui.css">
	<link rel="stylesheet" href="/assets/adminlte/plugins/iCheck/all.css">
	<link rel="stylesheet" href="/assets/adminlte/dist/css/AdminLTE.min.css">
	<link rel="stylesheet" type="text/css" href="/assets/css/jquery.timepicker.css" />
	<link href="https://gitcdn.github.io/bootstrap-toggle/2.2.2/css/bootstrap-toggle.min.css" rel="stylesheet">


	<!--[if lt IE 9]>
	<script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
	<script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
	<![endif]-->

	<style type="text/css">
		.ui-datepicker .ui-datepicker-title select {
			color: #000;
		}
	</style>
</head>
<body>
<div class="row">
	<div class="col-lg-12">
		<div class="banner">
			<div class="Title" style="margin-left: 6px;">View Learner [<?php echo $vo->firstnames . ' ' . $vo->surname; ?>]</div>
			<div class="ButtonBar">
				<span class="btn btn-sm btn-default" onclick="window.location.href='<?php echo $_SESSION['bc']->getPrevious(); ?>';"><i class="fa fa-arrow-circle-o-left"></i> Cancel</span>
				<span class="btn btn-sm btn-default" onclick="window.location.href='do.php?_action=edit_learner&id=<?php echo $vo->id; ?>&username=<?php echo $vo->username; ?>';"><i class="fa fa-edit"></i> Edit</span>
				<span class="btn btn-sm btn-default" onclick="window.open('do.php?_action=pdf_from_learner&username=<?php echo $vo->username; ?>');"><i class="fa fa-file-pdf-o"></i> Basic ILR</span>
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

<?php
$trss = DAO::getResultset($link, "SELECT tr.id AS tr_id, DATE_FORMAT(tr.start_date, '%d/%m/%Y') AS tr_start,  DATE_FORMAT(tr.`target_date`, '%d/%m/%Y') AS tr_target, IF(courses.title IS NULL, '', courses.title) AS c_title FROM tr LEFT JOIN courses_tr on courses_tr.tr_id=tr.id LEFT JOIN courses on courses.id = courses_tr.course_id WHERE tr.username='{$vo->username}';", DAO::FETCH_ASSOC);
?>

<div class="content-wrapper">

<div class="row">
	<div class="col-md-3">
	<div class="box box-primary">
		<div class="box-body box-profile">
			<img class="profile-user-img img-responsive img-circle" src="<?php echo $photopath; ?>" alt="User profile picture">
			<span class="profile-username"><?php echo htmlspecialchars((string)$vo->firstnames) . ' ' . htmlspecialchars(strtoupper((string)$vo->surname)); ?></span>
			<p class="text-muted"><?php echo htmlspecialchars((string)$vo->job_role); ?></p>
			<div class="col-sm-12 invoice-col">
				<b><?php echo $vo->org->legal_name; ?></b><br>
				<?php echo $vo->loc->address_line_3 != ''? $vo->loc->address_line_3 . '<br>':''; ?>
				<?php echo $vo->loc->address_line_4 != ''? $vo->loc->address_line_4 . '<br>':''; ?>
			</div>
		</div>
	</div>
	<div class="box box-primary">
		<div class="box-header with-border"><span class="box-title">Contact Information</span>
			<div class="box-tools pull-right">
				<button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
			</div>
		</div>

		<!-- /.box-header -->
		<div class="box-body">
			<strong><i class="fa fa-map-marker margin-r-5"></i> Work Contact Details</strong>
			<address>
				<?php
				echo trim((string)$work_address->address_line_1) != ''?htmlspecialchars((string)$work_address->address_line_1).'<br>':'';
				echo trim((string)$work_address->address_line_2) != ''?htmlspecialchars((string)$work_address->address_line_2).'<br>':'';
				echo trim((string)$work_address->address_line_3) != ''?htmlspecialchars((string)$work_address->address_line_3).'<br>':'';
				echo trim((string)$work_address->address_line_4) != ''?htmlspecialchars((string)$work_address->address_line_4).'<br>':'';
				echo trim((string)$work_address->postcode) != ''?htmlspecialchars((string)$work_address->postcode).'<br>':'';
				echo trim((string)$vo->work_telephone) != ''?'<span class="fa fa-phone"></span> '.htmlspecialchars((string)$vo->work_telephone).'<br>':'';
				echo trim((string)$vo->work_mobile) != ''?'<span class="fa fa-mobile-phone"></span> '.htmlspecialchars((string)$vo->work_mobile).'<br>':'';
				?>
			</address>

			<hr>

			<strong><i class="fa fa-map-marker margin-r-5"></i> Home Contact Details</strong>

			<address>
				<?php
				echo trim((string)$home_address->address_line_1) != ''?htmlspecialchars((string)$home_address->address_line_1).'<br>':'';
				echo trim((string)$home_address->address_line_2) != ''?htmlspecialchars((string)$home_address->address_line_2).'<br>':'';
				echo trim((string)$home_address->address_line_3) != ''?htmlspecialchars((string)$home_address->address_line_3).'<br>':'';
				echo trim((string)$home_address->address_line_4) != ''?htmlspecialchars((string)$home_address->address_line_4).'<br>':'';
				echo trim((string)$home_address->postcode) != ''?htmlspecialchars((string)$home_address->postcode).'<br>':'';
				echo trim((string)$vo->home_telephone) != ''?'<span class="fa fa-phone"></span> '.htmlspecialchars((string)$vo->home_telephone).'<br>':'';
				echo trim((string)$vo->home_mobile) != ''?'<span class="fa fa-mobile-phone"></span> '.htmlspecialchars((string)$vo->home_mobile).'<br>':'';
				echo trim((string)$vo->home_email) != ''?'<span class="fa  fa-envelope"></span> '.htmlspecialchars((string)$vo->home_email).'<br>':'';
				if($vo->rui != '')
				{
					$rui = explode(',', (string)$vo->rui);
					echo '<hr><p>Learner wishes to be contacted for: </p><ul>';
					echo in_array(1, $rui) ? '<li>About courses or learning opportunities</li>' : '';
					echo in_array(2, $rui) ? '<li>For surveys and research</li>' : '';
					echo '</ul>';
				}
				if($vo->pmc != '')
				{
					$pmc = explode(',', (string)$vo->pmc);
					echo '<hr><p>Learner contact preferences: </p><ul>';
					echo in_array(1, $pmc) ? '<li>By Post</li>' : '';
					echo in_array(2, $pmc) ? '<li>By Phone</li>' : '';
					echo in_array(3, $pmc) ? '<li>By Email</li>' : '';
					echo '</ul>';
				}
				?>
			</address>

			<hr>

		</div>
		<!-- /.box-body -->
	</div>
	<!-- /.box -->
</div>
<!-- /.col -->
<div class="col-md-9">

<div class="nav-tabs-custom">
<ul class="nav nav-tabs">
	<li class="active"><a href="#tab_details" data-toggle="tab">Details</a></li>
	<li><a href="#tab_enrol" data-toggle="tab">Enrolment / Training Records</a></li>
	<li><a href="#tab_safeguarding" data-toggle="tab">Safeguarding</a></li>
	<li><a href="#tab_iv" data-toggle="tab">Internal Validation</a></li>
</ul>
<div class="tab-content">
<div class="active tab-pane" id="tab_details">

	<div class="row">

		<div class="col-sm-6">
			<div class="box box-primary">
				<div class="box-header with-border">
					<span class="box-title">Personal Details</span>
					<div class="box-tools pull-right"><button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button></div>
				</div>
				<div class="box-body no-padding">
					<div class="table-responsive">
						<table class="table">
							<tr><th style="width:30%">Gender:</th><td><?php echo htmlspecialchars((string)$gender_description); ?></td></tr>
							<tr>
								<th style="width:30%">Date of Birth:</th>
								<td>
									<?php
									echo htmlspecialchars((string)Date::toMedium($vo->dob));
									if ($vo->dob) {
										echo '<span style="margin-left:30px;color:gray">(' . Date::dateDiff(date("Y-m-d"),$vo->dob) . ')</span>';
									}
									?>
								</td>
							</tr>
							<tr><th style="width:30%">Ethnicity:</th><td><?php echo htmlspecialchars((string)$ethnicity_description); ?></td></tr>
							<tr><th style="width:30%">Nationality:</th><td><?php echo htmlspecialchars((string)$nationality_description); ?></td></tr>
							<tr><th style="width:30%">Job Role:</th><td><?php echo htmlspecialchars((string)$vo->job_role); ?></td></tr>
							<tr><th style="width:30%">Learner Provider Specified Monitoring (L42a):</th><td><?php echo htmlspecialchars((string)$vo->l42a); ?></td></tr>
							<tr><th style="width:30%">Learner Provider Specified Monitoring (L42b):</th><td><?php echo htmlspecialchars((string)$vo->l42b); ?></td></tr>
						</table>
					</div>
				</div>
			</div>
		</div>

		<div class="col-sm-6">
			<div class="box box-primary">
				<div class="box-header with-border">
					<span class="box-title">Identifiers</span>
					<div class="box-tools pull-right"><button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button></div>
				</div>
				<div class="box-body no-padding">
					<div class="table-responsive">
						<table class="table">
							<tr><th style="width:50%">Enrolment Number:</th><td><?php echo htmlspecialchars((string)$vo->enrollment_no); ?></td></tr>
							<tr><th style="width:50%">Unique Learner Number (ULN):</th><td><?php echo htmlspecialchars((string)$vo->l45); ?></td></tr>
							<tr><th style="width:50%">ILR Learner Reference Number (L03):</th><td><?php echo htmlspecialchars((string)$tr_l03); ?></td></tr>
							<tr><th style="width:50%">System Username:</th><td><?php echo htmlspecialchars((string)$vo->username); ?></td></tr>
							<tr><th style="width:50%">Awarding Body Registration Number:</th><td><?php echo htmlspecialchars((string)$vo->abr_number); ?></td></tr>
							<tr><th style="width:50%">UCAS Personal Identifier:</th><td><?php echo htmlspecialchars((string)$vo->ucas); ?></td></tr>
						</table>
					</div>
				</div>
			</div>
		</div>

	</div>

	<div class="row">
		<div class="col-sm-6">
			<div class="box box-primary">
				<div class="box-header with-border">
					<span class="box-title">Diagnostics</span>
					<div class="box-tools pull-right"><button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button></div>
				</div>
				<div class="box-body no-padding">
					<div class="table-responsive">
						<table class="table">
							<tr><th style="width:30%">Numeracy:</th><td><?php echo htmlspecialchars((string)$numeracy); ?></td></tr>
							<tr><th style="width:30%">Literacy:</th><td><?php echo htmlspecialchars((string)$literacy); ?></td></tr>
							<tr><th style="width:30%">ICT:</th><td><?php echo htmlspecialchars((string)$ict); ?></td></tr>
							<tr><th style="width:30%">ESOL:</th><td><?php echo DAO::getSingleValue($link, "SELECT description FROM lookup_pre_assessment WHERE id = '{$vo->esol}'"); ?></td></tr>
							<tr><th style="width:30%">Other:</th><td><?php echo DAO::getSingleValue($link, "SELECT description FROM lookup_pre_assessment WHERE id = '{$vo->other}'"); ?></td></tr>
							<tr><th style="width:30%">Prior Attainment Level:</th><td><?php echo DAO::getSingleValue($link, "SELECT description FROM central.lookup_prior_attainment WHERE code = '{$vo->high_level}'"); ?></td></tr>
							<tr><th style="width:30%">English is not the 1st language?:</th><td><input class="yes_no_toggle" type="checkbox" data-toggle="toggle" data-on="Yes" data-off="No" data-onstyle="success" data-offstyle="danger" <?php echo $vo->eng_first == '1' ? 'checked="checked"' : ''; ?> disabled="disabled" /></td></tr>
						</table>
					</div>
				</div>
			</div>
		</div>

		<div class="col-sm-6">
			<div class="box box-primary">
				<div class="box-header with-border">
					<span class="box-title">LLDD</span>
					<div class="box-tools pull-right"><button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button></div>
				</div>
				<div class="box-body" style="max-height: 250px; overflow-y: scroll;">
					<p><span class="text-bold">Does learner consider to have a learning difficulty, health problem or disability? </span><?php echo isset($LLDD[$vo->l14]) ? $LLDD[$vo->l14] : ''; ?></p>
					<table class="table table-bordered">
						<tr><th>LLDD Category</th><td>Primary</td></tr>
						<?php
						if($vo->lldd_cat == '') echo '<tr><td colspan="2">No LLDD category selected.</td> </tr>';
						$lldd_cat = explode(',', (string)$vo->lldd_cat);
						foreach($LLDDCat AS $key => $value)
						{
							if(in_array($key, $lldd_cat))
							{
								echo '<tr>';
								echo '<td>' . $value . '</td>';
								echo $key == $vo->primary_lldd ? '<td>Yes</td>' : '<td></td>';
								echo '</tr>';
							}
						}
						?>
					</table>
					<?php echo $vo->pass_to_als == '1' ? '<p><span class="text-bold">Additional Learning Support - Passed to ALS team</span></p>' : ''; ?>
				</div>
			</div>

		</div>

	</div>

	<div class="row">

		<div class="col-sm-6">

		</div>

		<div class="col-sm-6">
			<div class="box box-primary">
				<div class="box-header with-border">
					<span class="box-title">File Repository</span>
					<div class="box-tools pull-right"><button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button></div>
				</div>
				<div class="box-body" style="max-height: 250px; overflow-y: scroll;">
					<ul class="list-group list-group-unbordered">
						<?php
						$learner_dir = Repository::getRoot().'/'.trim((string)$vo->username);
						$files = Repository::readDirectory($learner_dir);
						if(count($files) == 0){
							echo '<i>No files uploaded</i>';
						}
						foreach($files as $f)
						{
							if($f->isDir()){
								continue;
							}
							$ext = new SplFileInfo($f->getName());
							$ext = $ext->getExtension();
							$image = 'fa-file';
							if($ext == 'doc' || $ext == 'docx')
								$image = 'fa-file-word-o';
							elseif($ext == 'pdf')
								$image = 'fa-file-pdf-o';
							elseif($ext == 'txt')
								$image = 'fa-file-text-o';
							echo '<li class="list-group-item"><a href=""><i class="fa '.$image.'"></i> ' . htmlspecialchars((string)$f->getName()) . '</a><br><span class="direct-chat-timestamp "><i class="fa fa-clock-o"></i> <small>' . date("d/m/Y H:i:s", $f->getModifiedTime()) .'</small></span></li>';
						}
						?>
					</ul>
				</div>
			</div>

		</div>
	</div>


</div>
<!-- /.tab-pane --><!-- /.tab-pane -->

<div class="tab-pane" id="tab_enrol">
	<div class="row">
		<div class="col-sm-12">
			<div class="box box-primary">
				<div class="box-header">
					<div class="box-title">Training Records</div>
					<div class="box-body">
						<div class="table-responsive">
							<table class="table table-bordered">
								<tr><th>Contract</th><th>Course / Framework</th><th>Status</th><th>Dates</th><th></th></tr>
								<?php
								$sql = <<<SQL
SELECT
	contracts.title AS contract, courses.title AS course, student_frameworks.title AS framework, tr.start_date, tr.target_date, tr.closure_date, tr.status_code, tr.id
FROM
	tr LEFT JOIN contracts ON tr.contract_id = contracts.id
	LEFT JOIN courses_tr ON tr.id = courses_tr.tr_id
	LEFT JOIN courses ON courses_tr.course_id = courses.id
	LEFT JOIN student_frameworks ON student_frameworks.tr_id = tr.id
WHERE
	tr.username = '$vo->username'
ORDER BY
	tr.id DESC
;
SQL;
								$result = DAO::getResultset($link, $sql, DAO::FETCH_ASSOC);
								foreach($result AS $row)
								{
									echo '<tr>';
									echo '<td>' . $row['contract'] . '</td>';
									echo '<td>';
									echo 'Course: ' . $row['course'] . '<br>';
									echo 'Framework: ' . $row['framework'];
									echo '</td>';
									if($row['status_code'] == '1')
										echo '<td><label class="label label-primary">Continuing</label></td>';
									elseif($row['status_code'] == '2')
										echo '<td><label class="label label-success">Completed</label></td>';
									elseif($row['status_code'] == '3')
										echo '<td><label class="label label-danger">Withdrawn</label></td>';
									elseif($row['status_code'] == '6')
										echo '<td><label class="label label-warning">Temp. Withdrawn</label></td>';
									else
										echo '<td><label class="label label-info">' . $row['status_code'] . '</label></td>';
									echo '<td>';
									echo 'Start Date: ' . Date::toShort($row['start_date']) . '<br>';
									echo 'Planned End Date: ' . Date::toShort($row['target_date']) . '<br>';
									echo 'Actual End Date: ' . Date::toShort($row['closure_date']);
									echo '</td>';
									echo '<td><span class="btn btn-sm btn-block btn-info" onclick="window.location.href=\'do.php?_action=read_training_record&id='.$row['id'].'\'"><i class="fa fa-folder-open"></i> View</span> </td>';
									echo '</tr>';
								}
								?>
							</table>
						</div>
					</div>
					<div class="box-footer">
						<div class="callout callout-default">
							<span class="lead">Enrolment</span>
							<form method="post" class="form-horizontal" name="frmEnrolLearner" action="<?php echo $_SERVER['PHP_SELF']; ?>" autocomplete="false">
								<input type="hidden" name="_action" value="save_start_training" />
								<input type="hidden" name="id" value="<?php echo $vo->id; ?>" />
								<input type="hidden" name="username" value="<?php echo $vo->username; ?>" />
								<div class="form-group">
									<label for="course_id" class="col-sm-3 control-label fieldLabel_compulsory">Course:</label>
									<div class="col-sm-9">
										<?php echo HTML::select('course_id', $courses, '', true, true); ?>
									</div>
								</div>
								<div class="form-group">
									<label for="provider_location_id" class="col-sm-3 control-label fieldLabel_compulsory">Location:</label>
									<div class="col-sm-9">
										<?php echo HTML::select('provider_location_id', $locations, '', true, true); ?>
									</div>
								</div>
								<div class="form-group">
									<label for="assessor" class="col-sm-3 control-label fieldLabel_optional">Assessor:</label>
									<div class="col-sm-9">
										<?php echo HTML::select('assessor', $assessors, '', true); ?>
									</div>
								</div>
								<div class="form-group">
									<label for="tutor" class="col-sm-3 control-label fieldLabel_optional">FS Tutor:</label>
									<div class="col-sm-9">
										<?php echo HTML::select('tutor', $tutors, '', true); ?>
									</div>
								</div>
								<div class="form-group">
									<label for="verifier" class="col-sm-3 control-label fieldLabel_optional">IQA:</label>
									<div class="col-sm-9">
										<?php echo HTML::select('verifier', $verifiers, '', true); ?>
									</div>
								</div>
								<div class="form-group">
									<label for="contract_id" class="col-sm-3 control-label fieldLabel_compulsory">Contract:</label>
									<div class="col-sm-9">
										<?php echo HTML::select('contract_id', $contracts, '', true, true); ?>
									</div>
								</div>
								<div class="form-group">
									<label for="input_start_date" class="col-sm-3 control-label fieldLabel_compulsory">Start Date:</label>
									<div class="col-sm-9">
										<?php echo HTML::datebox('start_date', '', true); ?>
									</div>
								</div>
								<div class="form-group">
									<label for="input_end_date" class="col-sm-3 control-label fieldLabel_compulsory">Planned End Date:</label>
									<div class="col-sm-9">
										<?php echo HTML::datebox('end_date', '', true); ?>
									</div>
								</div>
								<div class="form-group">
									<div class="col-sm-3"></div>
									<div class="col-sm-9">
										<span class="btn btn-primary btn-block btnEnrolLearner"><b><i class="fa fa-check"></i> Create New Training Record</b></span>
									</div>
								</div>
							</form>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<!-- /.tab-pane -->

<div class="tab-pane" id="tab_safeguarding">

	<div class="row">
		<div class="col-sm-12">
			<span id="btnAddIncident" class="btn btn-md btn-primary" onclick="$('#btnAddIncident').hide();$('#divNewIncident').show();"><i class="fa fa-plus"></i> Add Incident</span>
		</div>
	</div>

	<div class="row">
		<div class="col-sm-6">
			<div class="box box-primary" style="display: none;" id="divNewIncident">
				<div class="box-header with-border"><span class="box-title">Provide incident details</span></div>
				<form role="form">
					<div class="box-body">
						<div class="form-group">
							<label for="incident_date">Date</label>
							<div class="input-group date">
								<div class="input-group-addon"><i class="fa fa-calendar"></i></div>
								<input type="text" class="form-control pull-right" id="incident_date">
							</div>
						</div>
						<div class="form-group">
							<label for="incident_time">Time</label>
							<div class="input-group time">
								<div class="input-group-addon"><i class="fa fa-clock-o"></i></div>
								<input type="text" class="form-control pull-right" id="incident_time">
							</div>
						</div>
						<div class="form-group">
							<label for="categories">Category</label>
							<div>
								<select data-placeholder="Select Category" class="chosen-select" id="categories">
									<option value="">Select Category</option>
									<option value="Suicidal">Suicidal</option>
									<option value="Welfare">Welfare</option>
									<option value="Radicalisation">Radicalisation</option>
									<option value="Employment">Employment</option>
									<option value="Medical Issues">Medical Issues</option>
								</select>
							</div>
						</div>
						<div class="form-group">
							<label for="alert_users">Users</label>
							<div>
								<select data-placeholder="Select Users" class="chosen-select" multiple id="alert_users">
									<option value="">Select</option>
									<?php
									$users = DAO::getSingleColumn($link, "SELECT CONCAT(firstnames, ' ', surname) FROM users LIMIT 50");
									foreach($users AS $user)
										echo '<option value="' . $user . '">' . $user . '</option>';
									?>
								</select>
							</div>
						</div>
						<div class="form-group">
							<label for="agencies_contacted">Agencies Contacted</label>
							<div>
								<select data-placeholder="Select Agencies" class="chosen-select" multiple id="agencies_contacted">
									<option value="">Select</option>
									<?php
									$orgs = DAO::getSingleColumn($link, "SELECT legal_name FROM organisations LIMIT 50");
									foreach($orgs AS $o)
										echo '<option value="' . $o . '">' . $o . '</option>';
									?>
								</select>
							</div>
						</div>
						<div class="form-group">
							<label>Detail</label>
							<textarea class="form-control" rows="3" placeholder="Enter detail..."></textarea>
						</div>
					</div>

					<div class="box-footer">
						<button id="btnSaveIncident" onclick="$('#btnAddIncident').show();$('#divNewIncident').hide();" type="button" class="btn btn-primary"><i class="fa fa-check"></i> Save</button>
						<button id="btnCloseDivNewIncident" onclick="$('#btnAddIncident').show();$('#divNewIncident').hide();" type="button" class="btn btn-default pull-right"><i class="fa fa-close"></i> Cancel</button>
					</div>
				</form>
			</div>
		</div>
	</div>

	<br>

	<div class="row">
		<div class="col-sm-12">
			<div class="box">
				<div class="box-header"><span class="box-title">Incidents</span></div>
				<div class="box-body">
					<table id="tblIncidents" class="table table-bordered table-striped">
						<thead>
						<tr><th style="width:10%"><i class="fa fa-clock-o"></i> DateTime</th><th style="width:40%"><i class="fa fa-warning"></i> Incident</th><th style="width:10%">Category</th><th style="width:15%"><i class="fa fa-users"></i> Staff Members</th><th style="width:15%"><i class="fa  fa-building"></i> Agencies Contacted</th><th style="width:10%">Actions</th></tr>
						</thead>
						<tbody>
						<tr>
							<td>01/02/2017 11:00</td>
							<td>Was seen looking at radical websites, when spoken to he was told about these sites by his older brother.</td>
							<td><span class="label label-danger">Radicalisation</span> </td>
							<td>Joe Bloggs<br>Bolggs John</td>
							<td>NHS</td>
							<td>
								<div class="btn-group-vertical">
									<button type="button" class="btn btn-sm btn-info"><i class="fa fa-folder-open-o"></i> Detail</span></button>
									<button type="button" class="btn btn-sm btn-primary"><i class="fa fa-edit"></i> Edit</button>
								</div>
							</td>
						</tr>
						<tr>
							<td>02/02/2017 11:00</td>
							<td>Noticed red marks on Helen's wrist. She quickly covered them with her jumper when she realise I had noticed.</td>
							<td><span class="label label-danger">Self Harm</span> </td>
							<td>Joe Bloggs<br>John Smith</td>
							<td>NHS</td>
							<td>
								<div class="btn-group-vertical">
									<button type="button" class="btn btn-sm btn-info"><i class="fa fa-folder-open-o"></i> Detail</span></button>
									<button type="button" class="btn btn-sm btn-primary"><i class="fa fa-edit"></i> Edit</button>
								</div>
							</td>
						</tr>
						<tr>
							<td>03/02/2017 11:00</td>
							<td>Due to his medical condition James has needed to go home early twice this week. Needs following up.</td>
							<td><span class="label label-warning">Medical Issues</span> </td>
							<td>Joe Bloggs<br>John Smith</td>
							<td>NHS</td>
							<td>
								<div class="btn-group-vertical">
									<button type="button" class="btn btn-sm btn-info"><i class="fa fa-folder-open-o"></i> Detail</span></button>
									<button type="button" class="btn btn-sm btn-primary"><i class="fa fa-edit"></i> Edit</button>
								</div>
							</td>
						</tr>
						<tr>
							<td>13/02/2017 11:00</td>
							<td>Received a phone call from Jenny this morning to say she couldn't come to work today as she has no money for the bus fare. A colleague has also noticed she has had no food for lunch in the past week.</td>
							<td><span class="label label-info">Welfare</span> </td>
							<td>Joe Bloggs<br>John Smith</td>
							<td>NHS</td>
							<td>
								<div class="btn-group-vertical">
									<button type="button" class="btn btn-sm btn-info"><i class="fa fa-folder-open-o"></i> Detail</span></button>
									<button type="button" class="btn btn-sm btn-primary"><i class="fa fa-edit"></i> Edit</button>
								</div>
							</td>
						</tr>
						<tr>
							<td>13/02/2017 11:00</td>
							<td>Paul has reported to me today that he has been upset about comments made by a colleague who is also contacting him outside of work hours.</td>
							<td><span class="label label-warning">Employment</span> </td>
							<td>Joe Bloggs<br>Paul</td>
							<td>NHS</td>
							<td>
								<div class="btn-group-vertical">
									<button type="button" class="btn btn-sm btn-info"><i class="fa fa-folder-open-o"></i> Detail</span></button>
									<button type="button" class="btn btn-sm btn-primary"><i class="fa fa-edit"></i> Edit</button>
								</div>
							</td>
						</tr>
						</tbody>
					</table>
				</div>
			</div>
		</div>
	</div>

</div>

<div class="tab-pane" id="tab_iv">
	<span class="lead">Internal Validation</span>

	<p class="callout callout-info"><i class="fa fa-info-circle"></i> This feature can be used to create organisation internal validation checks and save information for each learner</p>

	<div class="table-responsive">
		<table class="table table-bordered table-hover">
			<tbody>
			<?php
			$questions = DAO::getSingleColumn($link, "SELECT description FROM rec_questions");
			$i = 0;
			foreach($questions AS $qs)
			{
				$i++;
				$q = 'q'.$i;
				echo '<tr>';
				echo '<td style="width: 30%;">' . $qs . '</td>';
//				echo '<td><input class="yes_no_toggle" type="checkbox" name="PEI" id="PEI" data-toggle="toggle" data-on="Yes" data-off="No" data-onstyle="success" data-offstyle="danger" /></td>';
				echo '<td style="width: 30%;">';
				echo <<<HTML
<table class="table">
	<tr><td class="text-success">Yes</td><td class="text-danger">No</td><td class="text-info">N/A</td></tr>
	<tr><td><input type="radio" name="$q"></td><td><input type="radio" name="$q"></td><td><input type="radio" name="$q"></td></tr>
</table>
HTML;

				echo '</td>';
				echo '<td style="width: 40%;"><textarea rows="3" style="width: 100%;"></textarea></td>';
				echo '</tr>';
			}
			echo '<tr><td colspan="3"><span class="btn btn-sm btn-block btn-primary"><i class="fa fa-save"></i> Save</span></td></tr>';
			?>
			</tbody>
		</table>
	</div>
</div>
</div>
<!-- /.tab-content -->
</div>
<!-- /.nav-tabs-custom -->
</div>
<!-- /.col -->
</div>
<!-- /.row -->

</div>

<script src="/assets/adminlte/plugins/jQuery/jquery-2.2.3.min.js"></script>
<script src="/assets/adminlte/plugins/jQueryUI/jquery-ui.min.js"></script>
<script src="/assets/adminlte/bootstrap/js/bootstrap.min.js"></script>
<script src="/assets/adminlte/plugins/chosen/chosen.jquery.js"></script>
<script src="/assets/adminlte/plugins/iCheck/icheck.min.js"></script>
<script src="/assets/adminlte/dist/js/app.min.js"></script>
<script src="/common.js" type="text/javascript"></script>
<script type="text/javascript" src="/assets/js/jquery/jquery.timepicker.js"></script>
<script language="JavaScript" src="/password.js"></script>
<script src="https://gitcdn.github.io/bootstrap-toggle/2.2.2/js/bootstrap-toggle.min.js"></script>

<script >
	$(function(){
		//Date picker
		$('.datepicker').datepicker({
			format: 'dd/mm/yyyy',
			yearRange: 'c-50:c+50'
		});

		$('.datepicker').attr('class', 'datepicker form-control');

		$('#incident_date').datepicker({
			autoclose: true
		});
		$("#incident_time").timepicker({ timeFormat: 'H:i' });

		$('input[type=radio]').iCheck({
			radioClass: 'iradio_square-red'
		});



		$('.chosen-select').chosen({width: "100%"});

	});

	function course_id_onchange(course, event)
	{
		var providers_locations = document.getElementById('provider_location_id');

		if(course.value != '')
		{
			course.disabled = true;

			providers_locations.disabled = true;
			ajaxPopulateSelect(providers_locations, 'do.php?_action=ajax_load_account_manager&subaction=load_provider_locations&course_id=' + course.value);
			providers_locations.disabled = false;

			course.disabled =false;
		}
		else
		{
			emptySelectElement(providers_locations);
		}
	}

	function provider_location_id_onchange(location, event)
	{
		var assessors = document.getElementById('assessor');
		var tutors = document.getElementById('tutor');

		if(location.value != '')
		{
			location.disabled = true;

			assessors.disabled = true;
			ajaxPopulateSelect(assessors, 'do.php?_action=ajax_load_account_manager&subaction=load_assessors&location_id=' + location.value);
			assessors.disabled = false;

			tutors.disabled = true;
			ajaxPopulateSelect(tutors, 'do.php?_action=ajax_load_account_manager&subaction=load_tutors&location_id=' + location.value);
			tutors.disabled = false;

			location.disabled =false;
		}
		else
		{
			emptySelectElement(assessors);
			emptySelectElement(tutors);
		}
	}

	$('.btnEnrolLearner').on('click', function(){
		var form = document.forms['frmEnrolLearner'];

		if(form.elements["course_id"].value == '')
		{
			return alert('Please select course');
		}
		if(form.elements["provider_location_id"].value == '')
		{
			return alert('Please select location');
		}
		if(form.elements["contract_id"].value == '')
		{
			return alert('Please select contract');
		}
		if(form.elements["start_date"].value == '')
		{
			return alert('Please select start date');
		}
		if(form.elements["end_date"].value == '')
		{
			return alert('Please select end date');
		}

		form.submit();
	});
</script>
</body>
</html>
