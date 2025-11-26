<?php /* @var $tr TrainingRecord */ ?>
<?php /* @var $assessor User */ ?>
<?php /* @var $tutor User */ ?>

<!DOCTYPE html>
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<title>Sunesis</title>
	<link rel="stylesheet" href="/assets/adminlte/bootstrap/css/bootstrap.css">
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.5.0/css/font-awesome.min.css">
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/ionicons/2.0.1/css/ionicons.min.css">
	<link rel="stylesheet" href="/assets/adminlte/plugins/jQueryUI/jquery-ui.css">
	<link rel="stylesheet" href="/assets/adminlte/dist/css/AdminLTE.css">
	<link rel="stylesheet" href="assets/adminlte/dist/css/skins/_all-skins.css">
	<link rel="stylesheet" href="/assets/adminlte/plugins/fullcalendar/fullcalendar.min.css">
	<link rel="stylesheet" href="/assets/adminlte/plugins/FileTree/jQueryFileTree.css">

	<!--[if lt IE 9]>
	<script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
	<script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
	<![endif]-->

	<style>
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
	</style>

	<script src="/assets/adminlte/plugins/jQuery/jquery-2.2.3.min.js"></script>

</head>
<body class="hold-transition skin-blue sidebar-mini">


<?php include_once(__DIR__ . '/layout/tpl_main_header.php'); ?>

<div class="content">

<div class="row">

<div class="col-lg-4 col-md-4 col-sm-12">
	<div class="box box-primary table-responsive collapsed-box">
		<div class="box-header with-border">
			<h3 class="box-title"><i class="fa fa-user"></i> Learner Details</h3>
			<div class="box-tools pull-right"><button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-plus"></i></button></div>
		</div>
		<div class="box-body box-profile">
			<img class="profile-user-img img-responsive img-circle" src="<?php echo $photopath; ?>" alt="Learner profile picture">
			<h3 class="profile-username text-center"><?php echo $_SESSION['user']->firstnames . ' ' . $_SESSION['user']->surname; ?></h3>
			<p class="text-muted text-center"><?php echo $_SESSION['user']->job_role; ?></p>
			<i class="fa fa-envelope"></i> <a href="mailto:<?php echo $_SESSION['user']->home_email; ?>"><?php echo $_SESSION['user']->home_email; ?></a>
			<ul class="list-group list-group-unbordered">
				<li class="list-group-item">
					<strong><i class="fa fa-map-marker margin-r-5"></i> Address</strong>
					<p class="text-muted">
						<?php
						echo !is_null($_SESSION['user']->home_address_line_1)?$_SESSION['user']->home_address_line_1 . '<br>':'';
						echo !is_null($_SESSION['user']->home_address_line_2)?$_SESSION['user']->home_address_line_2 . '<br>':'';
						echo !is_null($_SESSION['user']->home_address_line_3)?$_SESSION['user']->home_address_line_3 . '<br>':'';
						echo !is_null($_SESSION['user']->home_address_line_4)?$_SESSION['user']->home_address_line_4 . '<br>':'';
						echo !is_null($_SESSION['user']->home_postcode)?$_SESSION['user']->home_postcode . ' &nbsp; <a href="http://maps.google.co.uk/maps?f=q&hl=en&q=' . urlencode($_SESSION['user']->home_postcode) . '" target="_blank"><i class="fa fa-external-link"></i></a>':'';
						?>
					</p>
					<p>
						<i class="fa fa-phone"></i> <?php echo $_SESSION['user']->home_telephone; ?><br>
						<i class="fa fa-mobile"></i> <?php echo $_SESSION['user']->home_mobile; ?><br>
					</p>
				</li>
			</ul>
		</div>
	</div>
	<div class="box box-primary collapsed-box">
		<div class="box-header with-border">
			<h3 class="box-title"><i class="fa fa-building"></i> &nbsp; <i class="fa fa-users"></i> Organisations & Users</h3>
			<div class="box-tools pull-right"><button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-plus"></i></button></div>
		</div>
		<div class="box-body">
			<div class="row">
				<div class="col-lg-12 col-md-12 col-sm-12">
					<div class="row">
						<div class="col-lg-6 col-md-6 col-sm-6">
							<div class="well">
								<strong>Training Provider</strong>
								<p class="text-muted">
									<?php
									echo $tr->provider_full_name . '<br>';
									echo !is_null($tr->provider_address_line_1)?$tr->provider_address_line_1 . '<br>':'';
									echo !is_null($tr->provider_address_line_2)?$tr->provider_address_line_2 . '<br>':'';
									echo !is_null($tr->provider_address_line_3)?$tr->provider_address_line_3 . '<br>':'';
									echo !is_null($tr->provider_address_line_4)?$tr->provider_address_line_4 . '<br>':'';
									echo !is_null($tr->provider_postcode)?$tr->provider_postcode . ' &nbsp; <a href="http://maps.google.co.uk/maps?f=q&hl=en&q=' . urlencode($tr->provider_postcode) . '" target="_blank"><i class="fa fa-external-link"></i></a>':'';
									?>
								</p>
							</div>
						</div>
						<div class="col-lg-6 col-md-6 col-sm-6">
							<div class="well">
								<strong>Employer</strong>
								<p class="text-muted">
									<?php
									echo $tr->legal_name . '<br>';
									echo !is_null($tr->work_address_line_1)?$tr->work_address_line_1 . '<br>':'';
									echo !is_null($tr->work_address_line_2)?$tr->work_address_line_2 . '<br>':'';
									echo !is_null($tr->work_address_line_3)?$tr->work_address_line_3 . '<br>':'';
									echo !is_null($tr->work_address_line_4)?$tr->work_address_line_4 . '<br>':'';
									echo !is_null($tr->work_postcode)?$tr->work_postcode . ' &nbsp; <a href="http://maps.google.co.uk/maps?f=q&hl=en&q=' . urlencode($tr->work_postcode) . '" target="_blank"><i class="fa fa-external-link"></i></a>':'';
									?>
								</p>
							</div>
						</div>
					</div>
				</div>
				<div class="col-lg-12 col-md-12 col-sm-12">
					<div class="row">
						<?php if(!is_null($assessor)) {?>
						<div class="col-lg-12 col-md-12 col-sm-12">
							<div class="well">
								<strong>Assessor</strong>
								<img class="img-responsive img-md img-circle" src="<?php echo $assessor_photopath; ?>" alt="Assessor profile picture" />
								<h4><?php echo $assessor->firstnames . ' ' . $assessor->surname; ?></h4>
								<?php echo !is_null($assessor->work_email)?'<a href="mailto:'.$assessor->work_email.'">'.$assessor->work_email . '</a><br>':''; ?>
								<?php echo !is_null($assessor->work_telephone)?'<i class="fa fa-phone"></i> '.$assessor->work_telephone . '<br>':''; ?>
								<?php echo !is_null($assessor->work_mobile)?'<i class="fa fa-mobile"></i> '.$assessor->work_mobile . '<br>':''; ?>
							</div>
						</div>
						<?php } ?>
					</div>
				</div>
			</div>
		</div>
	</div>
	<div class="box box-primary collapsed-box">
		<div class="box-header with-border"><h3 class="box-title"><i class="fa fa-graduation-cap"></i> Training Details</h3>
			<div class="box-tools pull-right"><button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-plus"></i></button></div>
		</div>
		<div class="box-body  table-responsive">
			<table class="table row-border small">
				<tr><th>ULN:</th><td><?php echo $tr->uln; ?></td></tr>
				<tr><th>Learning Reference:</th><td><?php echo $tr->l03; ?></td></tr>
				<tr><th>Status:</th><td><?php echo isset($listStatus[$tr->status_code])?$listStatus[$tr->status_code]:''; ?></td></tr>
				<tr><th>Start Date:</th><td><?php echo Date::toShort($tr->start_date); ?></td></tr>
				<tr><th>Planned End Date:</th><td><?php echo Date::toShort($tr->target_date); ?></td></tr>
				<tr><th>Actual End Date:</th><td><?php echo !is_null($tr->closure_date)?Date::toShort($tr->closure_date):'-'; ?></td></tr>
				<tr><th>Standard:</th><td><?php echo !is_null($framework->framework_code)?$framework->framework_code . ' - ' . $framework->title:$framework->title; ?></td></tr>
			</table>
		</div>
	</div>
	<div class="box box-primary">
		<div class="box-header with-border"><h3 class="box-title"><i class="fa fa-font"></i> Your Signature</h3>
			<div class="box-tools pull-right"><button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button></div>
		</div>
		<div class="box-body">
						<span class="btn btn-info" onclick="getSignature();">
							<img id="img_user_signature" src="do.php?_action=generate_image&<?php echo $learner_signature == '' ? 'title=Create your signature&font=Signature_Regular.ttf&size=20' : $learner_signature; ?>" style="border: 1px solid;border-radius: 15px;" />
							<input type="hidden" name="user_signature" id="user_signature" value="<?php echo $learner_signature; ?>" />
						</span>
		</div>
	</div>
	<div class="box box-primary">
		<div class="box-header with-border"><h3 class="box-title"><i class="fa fa-files-o"></i> File Repository</h3>
			<div class="box-tools pull-right"><button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button></div>
		</div>
		<div class="box-body">
			<?php
			$repository = Repository::getRoot().'/'.$tr->username;
			$files = Repository::readDirectory($repository);
			?>
			<div class="fileTree"></div>
		</div>
	</div>
	<?php if(SOURCE_LOCAL || DB_NAME == "am_sd_demo"  || DB_NAME == "am_superdrug") {?>
	<div class="box box-primary">
		<div class="box-header with-border"><h3 class="box-title"><i class="fa fa-tv"></i> Your Showcase</h3>
			<div class="box-tools pull-right"><button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button></div>
		</div>
		<div class="box-body">
			<a class="btn btn-app btn-block" href="#" onclick="return window.phpLearnerSignature == '' ? alert('Please first create your signature') : window.location.href='do.php?_action=edit_learner_showcase&tr_id=<?php echo $tr->id; ?>'">
				<i class="fa fa-tv"></i> Your Showcase
			</a>
		</div>
	</div>
	<div class="box box-primary">
		<div class="box-header with-border"><h3 class="box-title"><i class="fa fa-files-o"></i> Visit Record</h3>
			<div class="box-tools pull-right"><button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button></div>
		</div>
		<div class="box-body">
			<div class="table-responsive">
				<?php echo $this->renderReviews($link, $tr); ?>
			</div>
		</div>
	</div>
	<?php } ?>
</div>

<div class="col-lg-8 col-md-8 col-sm-12">
<?php if($this->_type == 'cs') {?>
<div class="row">
	<div class="col-lg-12 col-md-12 col-sm-12">
		<div class="box box-success box-solid">
			<div class="box-header with-border">
				<h3 class="box-title"><i class="fa fa-book"></i> Workbooks</h3>
				<div class="box-tools pull-right"><button class="btn btn-box-tool" onclick="showWorkbooksKeys();"><i class="fa fa-info-circle"></i></button><button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button></div>
			</div>
			<div class="box-body">
				<div class="col-md-6 col-sm-6 col-xs-12" style="cursor: pointer" onclick="return window.phpLearnerSignature == '' ? alert('Please first create your signature') : window.location.href='do.php?_action=wb_developing_self&id=<?php echo isset($wb_developing_self->id)?$wb_developing_self->id:''; ?>&tr_id=<?php echo $tr->id; ?>'">
					<div class="info-box bg-green">
						<span class="info-box-icon"><i class="<?php echo $wb_developing_self->getStatusIcon(); ?>"></i></span>
						<div class="info-box-content">
							<span class="info-box-number">Developing Self</span>
							<span class="">Module 2</span>
							<div class="progress"><div class="progress-bar" style="width: <?php echo $wb_developing_self_percentage_completed; ?>%"></div></div>
							<span class="progress-description"><?php echo $wb_developing_self_percentage_completed; ?>% completed</span>
						</div>
					</div>
				</div>
				<div class="col-md-6 col-sm-6 col-xs-12" style="cursor: pointer" onclick="return window.phpLearnerSignature == '' ? alert('Please first create your signature') : window.location.href='do.php?_action=wb_customer_experience&id=<?php echo isset($wb_customer_experience->id)?$wb_customer_experience->id:''; ?>&tr_id=<?php echo $tr->id; ?>'">
					<div class="info-box bg-green">
						<span class="info-box-icon"><i class="<?php echo $wb_customer_experience->getStatusIcon(); ?>"></i></span>
						<div class="info-box-content">
							<span class="info-box-number">Customer Experience</span>
							<span class="">Module 3</span>
							<div class="progress"><div class="progress-bar" style="width: <?php echo $wb_customer_experience_percentage_completed; ?>%"></div></div>
							<span class="progress-description"><?php echo $wb_customer_experience_percentage_completed; ?>% completed</span>
						</div>
					</div>
				</div>
				<div class="col-md-6 col-sm-6 col-xs-12" style="cursor: pointer" onclick="return window.phpLearnerSignature == '' ? alert('Please first create your signature') : window.location.href='do.php?_action=wb_knowing_your_customers&id=<?php echo isset($wb_knowing_your_customers->id)?$wb_knowing_your_customers->id:''; ?>&tr_id=<?php echo $tr->id; ?>'">
					<div class="info-box bg-green">
						<span class="info-box-icon"><i class="<?php echo $wb_knowing_your_customers->getStatusIcon(); ?>"></i></span>
						<div class="info-box-content">
							<span class="info-box-number">Knowing your Customers</span>
							<span class="">Module 4</span>
							<div class="progress"><div class="progress-bar" style="width: <?php echo $wb_knowing_your_customers_percentage_completed; ?>%"></div></div>
							<span class="progress-description"><?php echo $wb_knowing_your_customers_percentage_completed; ?>% completed</span>
						</div>
					</div>
				</div>
				<div class="col-md-6 col-sm-6 col-xs-12" style="cursor: pointer" onclick="return window.phpLearnerSignature == '' ? alert('Please first create your signature') : window.location.href='do.php?_action=wb_role_responsibility&id=<?php echo isset($wb_role_responsibility->id)?$wb_role_responsibility->id:''; ?>&tr_id=<?php echo $tr->id; ?>'">
					<div class="info-box bg-green">
						<span class="info-box-icon"><i class="<?php echo $wb_role_responsibility->getStatusIcon(); ?>"></i></span>
						<div class="info-box-content">
							<span class="info-box-number">Personal Organisation</span>
							<span class="">Module 5</span>
							<div class="progress"><div class="progress-bar" style="width: <?php echo $wb_role_responsibility_percentage_completed; ?>%"></div></div>
							<span class="progress-description"><?php echo $wb_role_responsibility_percentage_completed; ?>% completed</span>
						</div>
					</div>
				</div>
				<div class="col-md-6 col-sm-6 col-xs-12" style="cursor: pointer" onclick="return window.phpLearnerSignature == '' ? alert('Please first create your signature') : window.location.href='do.php?_action=wb_team_working&id=<?php echo isset($wb_team_working->id)?$wb_team_working->id:''; ?>&tr_id=<?php echo $tr->id; ?>'">
					<div class="info-box bg-green">
						<span class="info-box-icon"><i class="<?php echo $wb_team_working->getStatusIcon(); ?>"></i></span>
						<div class="info-box-content">
							<span class="info-box-number">Team Working</span>
							<span class="">Module 6</span>
							<div class="progress"><div class="progress-bar" style="width: <?php echo $wb_team_working_percentage_completed; ?>%"></div></div>
							<span class="progress-description"><?php echo $wb_team_working_percentage_completed; ?>% completed</span>
						</div>
					</div>
				</div>
				<div class="col-md-6 col-sm-6 col-xs-12" style="cursor: pointer" onclick="return window.phpLearnerSignature == '' ? alert('Please first create your signature') : window.location.href='do.php?_action=wb_communication&id=<?php echo isset($wb_communication->id)?$wb_communication->id:''; ?>&tr_id=<?php echo $tr->id; ?>'">
					<div class="info-box bg-green">
						<span class="info-box-icon"><i class="<?php echo $wb_communication->getStatusIcon(); ?>"></i></span>
						<div class="info-box-content">
							<span class="info-box-number">Communication</span>
							<span class="">Module 7</span>
							<div class="progress"><div class="progress-bar" style="width: <?php echo $wb_communication_percentage_completed; ?>%"></div></div>
							<span class="progress-description"><?php echo $wb_communication_percentage_completed; ?>% completed</span>
						</div>
					</div>
				</div>
				<div class="col-md-6 col-sm-6 col-xs-12" style="cursor: pointer" onclick="return window.phpLearnerSignature == '' ? alert('Please first create your signature') : window.location.href='do.php?_action=wb_systems_and_resources&id=<?php echo isset($wb_systems_and_resources->id)?$wb_systems_and_resources->id:''; ?>&tr_id=<?php echo $tr->id; ?>'">
					<div class="info-box bg-green">
						<span class="info-box-icon"><i class="<?php echo $wb_systems_and_resources->getStatusIcon(); ?>"></i></span>
						<div class="info-box-content">
							<span class="info-box-number">Systems & Resources</span>
							<span class="">Module 8</span>
							<div class="progress"><div class="progress-bar" style="width: <?php echo $wb_systems_and_resources_percentage_completed; ?>%"></div></div>
							<span class="progress-description"><?php echo $wb_systems_and_resources_percentage_completed; ?>% completed</span>
						</div>
					</div>
				</div>
				<div class="col-md-6 col-sm-6 col-xs-12" style="cursor: pointer" onclick="return window.phpLearnerSignature == '' ? alert('Please first create your signature') : window.location.href='do.php?_action=wb_understanding_the_organisation&id=<?php echo isset($wb_understanding_the_organisation->id)?$wb_understanding_the_organisation->id:''; ?>&tr_id=<?php echo $tr->id; ?>'">
					<div class="info-box bg-green">
						<span class="info-box-icon"><i class="<?php echo $wb_understanding_the_organisation->getStatusIcon(); ?>"></i></span>
						<div class="info-box-content">
							<span class="info-box-number">Understanding the Organisation</span>
							<span class="">Module 9</span>
							<div class="progress"><div class="progress-bar" style="width: <?php echo $wb_understanding_the_organisation_percentage_completed; ?>%"></div></div>
							<span class="progress-description"><?php echo $wb_understanding_the_organisation_percentage_completed; ?>% completed</span>
						</div>
					</div>
				</div>
				<div class="col-md-6 col-sm-6 col-xs-12" style="cursor: pointer" onclick="return window.phpLearnerSignature == '' ? alert('Please first create your signature') : window.location.href='do.php?_action=wb_product_and_service&id=<?php echo isset($wb_product_and_service->id)?$wb_product_and_service->id:''; ?>&tr_id=<?php echo $tr->id; ?>'">
					<div class="info-box bg-green">
						<span class="info-box-icon"><i class="<?php echo $wb_product_and_service->getStatusIcon(); ?>"></i></span>
						<div class="info-box-content">
							<span class="info-box-number">Product and Service</span>
							<span class="">Module 10</span>
							<div class="progress"><div class="progress-bar" style="width: <?php echo $wb_product_and_service_percentage_completed; ?>%"></div></div>
							<span class="progress-description"><?php echo $wb_product_and_service_percentage_completed; ?>% completed</span>
						</div>
					</div>
				</div>
				<div class="col-md-6 col-sm-6 col-xs-12" style="cursor: pointer" onclick="return window.phpLearnerSignature == '' ? alert('Please first create your signature') : window.location.href='do.php?_action=wb_meeting_regulations_and_legislation&id=<?php echo isset($wb_meeting_regulations_and_legislation->id)?$wb_meeting_regulations_and_legislation->id:''; ?>&tr_id=<?php echo $tr->id; ?>'">
					<div class="info-box bg-green">
						<span class="info-box-icon"><i class="<?php echo $wb_meeting_regulations_and_legislation->getStatusIcon(); ?>"></i></span>
						<div class="info-box-content">
							<span class="info-box-number">Meeting regulations and legislation</span>
							<span class="">Module 11</span>
							<div class="progress"><div class="progress-bar" style="width: <?php echo $wb_meeting_regulations_and_legislation_percentage_completed; ?>%"></div></div>
							<span class="progress-description"><?php echo $wb_meeting_regulations_and_legislation_percentage_completed; ?>% completed</span>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
	<?php } ?>
<?php if($this->_type == 'retail') {?>
<div class="row">
	<div class="col-lg-12 col-md-12 col-sm-12">
		<div class="box box-success box-solid">
			<div class="box-header with-border">
				<h3 class="box-title"><i class="fa fa-book"></i> Workbooks</h3>
				<div class="box-tools pull-right"><button class="btn btn-box-tool" onclick="showWorkbooksKeys();"><i class="fa fa-info-circle"></i></button><button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button></div>
			</div>
			<div class="box-body">
				<div class="col-md-6 col-sm-6 col-xs-12" style="cursor: pointer" onclick="return window.phpLearnerSignature == '' ? alert('Please first create your signature') : window.location.href='do.php?_action=wb_hs_and_security&id=<?php echo isset($wb_hs_and_security->id)?$wb_hs_and_security->id:''; ?>&tr_id=<?php echo $tr->id; ?>'">
					<div class="info-box bg-green">
						<span class="info-box-icon"><i class="<?php echo $wb_hs_and_security->getStatusIcon(); ?>"></i></span>
						<div class="info-box-content">
							<span class="info-box-number">H&S And Security</span>
							<span class="">Module</span>
							<div class="progress"><div class="progress-bar" style="width: <?php echo $wb_hs_and_security_percentage_completed; ?>%"></div></div>
							<span class="progress-description"><?php echo $wb_hs_and_security_percentage_completed; ?>% completed</span>
						</div>
					</div>
				</div>
				<div class="col-md-6 col-sm-6 col-xs-12" style="cursor: pointer" onclick="return window.phpLearnerSignature == '' ? alert('Please first create your signature') : window.location.href='do.php?_action=wb_customer&id=<?php echo isset($wb_customer->id)?$wb_customer->id:''; ?>&tr_id=<?php echo $tr->id; ?>'">
					<div class="info-box bg-green">
						<span class="info-box-icon"><i class="<?php echo $wb_customer->getStatusIcon(); ?>"></i></span>
						<div class="info-box-content">
							<span class="info-box-number small">Customer</span>
							<span class="">Module</span>
							<div class="progress"><div class="progress-bar" style="width: <?php echo $wb_customer_percentage_completed; ?>%"></div></div>
							<span class="progress-description"><?php echo $wb_customer_percentage_completed; ?>% completed</span>
						</div>
					</div>
				</div>
				<div class="col-md-6 col-sm-6 col-xs-12" style="cursor: pointer" onclick="return window.phpLearnerSignature == '' ? alert('Please first create your signature') : window.location.href='do.php?_action=wb_communication&id=<?php echo isset($wb_communication->id)?$wb_communication->id:''; ?>&tr_id=<?php echo $tr->id; ?>'">
					<div class="info-box bg-green">
						<span class="info-box-icon"><i class="<?php echo $wb_communication->getStatusIcon(); ?>"></i></span>
						<div class="info-box-content">
							<span class="info-box-number">Communication</span>
							<span class="">Module</span>
							<div class="progress"><div class="progress-bar" style="width: <?php echo $wb_communication_percentage_completed; ?>%"></div></div>
							<span class="progress-description"><?php echo $wb_communication_percentage_completed; ?>% completed</span>
						</div>
					</div>
				</div>
				<div class="col-md-6 col-sm-6 col-xs-12" style="cursor: pointer" onclick="return window.phpLearnerSignature == '' ? alert('Please first create your signature') : window.location.href='do.php?_action=wb_technical&id=<?php echo isset($wb_technical->id)?$wb_technical->id:''; ?>&tr_id=<?php echo $tr->id; ?>'">
					<div class="info-box bg-green">
						<span class="info-box-icon"><i class="<?php echo $wb_technical->getStatusIcon(); ?>"></i></span>
						<div class="info-box-content">
							<span class="info-box-number">Technical</span>
							<span class="">Module</span>
							<div class="progress"><div class="progress-bar" style="width: <?php echo $wb_technical_percentage_completed; ?>%"></div></div>
							<span class="progress-description"><?php echo $wb_technical_percentage_completed; ?>% completed</span>
						</div>
					</div>
				</div>
				<div class="col-md-6 col-sm-6 col-xs-12" style="cursor: pointer" onclick="return window.phpLearnerSignature == '' ? alert('Please first create your signature') : window.location.href='do.php?_action=wb_personal_team_performance&id=<?php echo isset($wb_personal_team_performance->id)?$wb_personal_team_performance->id:''; ?>&tr_id=<?php echo $tr->id; ?>'">
					<div class="info-box bg-green">
						<span class="info-box-icon"><i class="<?php echo $wb_personal_team_performance->getStatusIcon(); ?>"></i></span>
						<div class="info-box-content">
							<span class="info-box-number">Personal Team Performance</span>
							<span class="">Module</span>
							<div class="progress"><div class="progress-bar" style="width: <?php echo $wb_personal_team_performance_percentage_completed; ?>%"></div></div>
							<span class="progress-description"><?php echo $wb_personal_team_performance_percentage_completed; ?>% completed</span>
						</div>
					</div>
				</div>
				<div class="col-md-6 col-sm-6 col-xs-12" style="cursor: pointer" onclick="return window.phpLearnerSignature == '' ? alert('Please first create your signature') : window.location.href='do.php?_action=wb_retail_product_and_service&id=<?php echo isset($wb_product_and_service->id)?$wb_product_and_service->id:''; ?>&tr_id=<?php echo $tr->id; ?>'">
					<div class="info-box bg-green">
						<span class="info-box-icon"><i class="<?php echo $wb_product_and_service->getStatusIcon(); ?>"></i></span>
						<div class="info-box-content">
							<span class="info-box-number">Product and Service</span>
							<span class="">Module</span>
							<div class="progress"><div class="progress-bar" style="width: <?php echo $wb_product_and_service_percentage_completed; ?>%"></div></div>
							<span class="progress-description"><?php echo $wb_product_and_service_percentage_completed; ?>% completed</span>
						</div>
					</div>
				</div>
				<div class="col-md-6 col-sm-6 col-xs-12" style="cursor: pointer" onclick="return window.phpLearnerSignature == '' ? alert('Please first create your signature') : window.location.href='do.php?_action=wb_stock&id=<?php echo isset($wb_stock->id)?$wb_stock->id:''; ?>&tr_id=<?php echo $tr->id; ?>'">
					<div class="info-box bg-green">
						<span class="info-box-icon"><i class="<?php echo $wb_stock->getStatusIcon(); ?>"></i></span>
						<div class="info-box-content">
							<span class="info-box-number">Stock</span>
							<span class="">Module</span>
							<div class="progress"><div class="progress-bar" style="width: <?php echo $wb_stock_percentage_completed; ?>%"></div></div>
							<span class="progress-description"><?php echo $wb_stock_percentage_completed; ?>% completed</span>
						</div>
					</div>
				</div>
				<div class="col-md-6 col-sm-6 col-xs-12" style="cursor: pointer" onclick="return window.phpLearnerSignature == '' ? alert('Please first create your signature') : window.location.href='do.php?_action=wb_financial&id=<?php echo isset($wb_financial->id)?$wb_financial->id:''; ?>&tr_id=<?php echo $tr->id; ?>'">
					<div class="info-box bg-green">
						<span class="info-box-icon"><i class="<?php echo $wb_financial->getStatusIcon(); ?>"></i></span>
						<div class="info-box-content">
							<span class="info-box-number">Financial</span>
							<span class="">Module</span>
							<div class="progress"><div class="progress-bar" style="width: <?php echo $wb_financial_percentage_completed; ?>%"></div></div>
							<span class="progress-description"><?php echo $wb_financial_percentage_completed; ?>% completed</span>
						</div>
					</div>
				</div>
				<div class="col-md-6 col-sm-6 col-xs-12" style="cursor: pointer" onclick="return window.phpLearnerSignature == '' ? alert('Please first create your signature') : window.location.href='do.php?_action=wb_environment&id=<?php echo isset($wb_environment->id)?$wb_environment->id:''; ?>&tr_id=<?php echo $tr->id; ?>'">
					<div class="info-box bg-green">
						<span class="info-box-icon"><i class="<?php echo $wb_environment->getStatusIcon(); ?>"></i></span>
						<div class="info-box-content">
							<span class="info-box-number">Environment</span>
							<span class="">Module</span>
							<div class="progress"><div class="progress-bar" style="width: <?php echo $wb_environment_percentage_completed; ?>%"></div></div>
							<span class="progress-description"><?php echo $wb_environment_percentage_completed; ?>% completed</span>
						</div>
					</div>
				</div>
				<div class="col-md-6 col-sm-6 col-xs-12" style="cursor: pointer" onclick="return window.phpLearnerSignature == '' ? alert('Please first create your signature') : window.location.href='do.php?_action=wb_business_and_brand_reputation&id=<?php echo isset($wb_business_and_brand_reputation->id)?$wb_business_and_brand_reputation->id:''; ?>&tr_id=<?php echo $tr->id; ?>'">
					<div class="info-box bg-green">
						<span class="info-box-icon"><i class="<?php echo $wb_business_and_brand_reputation->getStatusIcon(); ?>"></i></span>
						<div class="info-box-content">
							<span class="info-box-number">Business & Brand Reputation</span>
							<span class="">Module</span>
							<div class="progress"><div class="progress-bar" style="width: <?php echo $wb_business_and_brand_reputation_percentage_completed; ?>%"></div></div>
							<span class="progress-description"><?php echo $wb_business_and_brand_reputation_percentage_completed; ?>% completed</span>
						</div>
					</div>
				</div>
				<div class="col-md-6 col-sm-6 col-xs-12" style="cursor: pointer" onclick="return window.phpLearnerSignature == '' ? alert('Please first create your signature') : window.location.href='do.php?_action=wb_legal_and_governance&id=<?php echo isset($wb_legal_and_governance->id)?$wb_legal_and_governance->id:''; ?>&tr_id=<?php echo $tr->id; ?>'">
					<div class="info-box bg-green">
						<span class="info-box-icon"><i class="<?php echo $wb_legal_and_governance->getStatusIcon(); ?>"></i></span>
						<div class="info-box-content">
							<span class="info-box-number small">Legal and Governance</span>
							<span class="">Module</span>
							<div class="progress"><div class="progress-bar" style="width: <?php echo $wb_legal_and_governance_percentage_completed; ?>%"></div></div>
							<span class="progress-description"><?php echo $wb_legal_and_governance_percentage_completed; ?>% completed</span>
						</div>
					</div>
				</div>
				<div class="col-md-6 col-sm-6 col-xs-12" style="cursor: pointer" onclick="return window.phpLearnerSignature == '' ? alert('Please first create your signature') : window.location.href='do.php?_action=wb_marketing&id=<?php echo isset($wb_marketing->id)?$wb_marketing->id:''; ?>&tr_id=<?php echo $tr->id; ?>'">
					<div class="info-box bg-green">
						<span class="info-box-icon"><i class="<?php echo $wb_marketing->getStatusIcon(); ?>"></i></span>
						<div class="info-box-content">
							<span class="info-box-number">Marketing</span>
							<span class="">Module</span>
							<div class="progress"><div class="progress-bar" style="width: <?php echo $wb_marketing_percentage_completed; ?>%"></div></div>
							<span class="progress-description"><?php echo $wb_marketing_percentage_completed; ?>% completed</span>
						</div>
					</div>
				</div>
				<div class="col-md-6 col-sm-6 col-xs-12" style="cursor: pointer" onclick="return window.phpLearnerSignature == '' ? alert('Please first create your signature') : window.location.href='do.php?_action=wb_sales_promotion_marchandising&id=<?php echo isset($wb_sales_promotion_marchandising->id)?$wb_sales_promotion_marchandising->id:''; ?>&tr_id=<?php echo $tr->id; ?>'">
					<div class="info-box bg-green">
						<span class="info-box-icon"><i class="<?php echo $wb_sales_promotion_marchandising->getStatusIcon(); ?>"></i></span>
						<div class="info-box-content">
							<span class="info-box-number small">Sales and Promotion & Merchandising</span>
							<span class="">Module</span>
							<div class="progress"><div class="progress-bar" style="width: <?php echo $wb_sales_promotion_marchandising_percentage_completed; ?>%"></div></div>
							<span class="progress-description"><?php echo $wb_sales_promotion_marchandising_percentage_completed; ?>% completed</span>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
	<?php } ?>
<div class="row">
	<div class="col-lg-6 col-md-6 col-sm-6">
		<div class="row" id="divProgress">
			<?php if($this->_type == 'cs') {?>
			<div class="col-sm-12">
				<div class="box box-success box-solid">
					<div class="box-header with-border"><h3 class="box-title"><i class="fa fa-calendar"></i> Reviews & Observations</h3>
						<div class="box-tools pull-right"><button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i> </button> </div>
					</div>
					<div class="box-body ">
						<a class="btn btn-app" href="#" onclick="return window.phpLearnerSignature == '' ? alert('Please first create your signature') : window.location.href='do.php?_action=cs_review&id=<?php echo isset($cs_review->id)?$cs_review->id:''; ?>&tr_id=<?php echo $tr->id; ?>'">
							<i class="fa fa-edit"></i> Customer Service Practitioner Self-assessment / Review
						</a>
						<a class="btn btn-app" href="#" onclick="return window.phpLearnerSignature == '' ? alert('Please first create your signature') : window.location.href='do.php?_action=cs_observation&id=<?php echo isset($cs_observation->id)?$cs_observation->id:''; ?>&tr_id=<?php echo $tr->id; ?>'">
							<i class="fa fa-edit"></i> Customer Service Practitioner Observation & Performance checklist
						</a>
					</div>
				</div>
			</div>
			<?php } ?>
			<?php if($this->_type == 'retail') {?>
			<div class="col-sm-12">
				<div class="box box-success box-solid">
					<div class="box-header with-border"><h3 class="box-title"><i class="fa fa-calendar"></i> Observations & Self-Assessment</h3>
						<div class="box-tools pull-right"><button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i> </button> </div>
					</div>
					<div class="box-body ">
						<a class="btn btn-app" href="#" onclick="return window.phpLearnerSignature == '' ? alert('Please first create your signature') : window.location.href='do.php?_action=rt_observation&id=<?php echo isset($rt_observation->id)?$rt_observation->id:''; ?>&tr_id=<?php echo $tr->id; ?>'">
							<i class="fa fa-edit"></i> Retailer observation performance checklist
						</a>
					</div>
					<div class="box-body ">
						<a class="btn btn-app" href="#" onclick="return window.phpLearnerSignature == '' ? alert('Please first create your signature') : window.location.href='do.php?_action=view_edit_retailer_self_assessment&tr_id=<?php echo $tr->id; ?>'">
							<i class="fa fa-edit"></i> Retail self assessment
						</a>
					</div>
				</div>
				<div class="box box-success box-solid small">
					<div class="box-header with-border"><h3 class="box-title"><i class="fa fa-calendar"></i> Retailer Reviews</h3>
						<div class="box-tools pull-right"><button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i> </button> </div>
					</div>
					<div class="box-body table-responsive">
						<table class="table table-bordered">
							<!--<tr><th colspan="3"><span class="btn btn-primary btn-xs" id="btnCreateNewRetailerReview" <?php /*echo $btn_retailer_add_new_review_status; */?> >Create New</span> </th> </tr>-->
							<tr><th></th><th>Completed</th><th>Last modified date</th></tr>
							<?php
							$result = DAO::getResultset($link, "SELECT id, tr_id, learner_signature, assessor_signature, modified FROM retailer_reviews WHERE tr_id = '{$tr->id}' ORDER BY id", DAO::FETCH_ASSOC);
							if(count($result) == 0)
								echo '<tr><td colspan="3"><i class="text-muted"> No reviews found</i></td> </tr>';
							else
							{
								$i = 0;
								foreach($result AS $row)
								{
									//echo '<tr>';
									echo HTML::viewrow_opening_tag('do.php?_action=view_edit_retailer_review&id='.$row['id'].'&tr_id=' . $row['tr_id']);
									echo '<td>Review ' . ++$i . '</td>';
									if(!is_null($row['learner_signature']) && !is_null($row['assessor_signature']))
										echo '<td>Yes</td>';
									else
										echo '<td>No</td>';
									echo '<td>' . Date::to($row['modified'], Date::DATETIME) . '</td>';
									echo '</tr>';
								}
							}
							?>
						</table>
					</div>
				</div>
			</div>
			<?php } ?>
			<div class="col-sm-12">
				<div class="box box-info box-solid">
					<div class="box-header with-border"><h3 class="box-title"><i class="fa fa-line-chart"></i> Progress</h3>
						<div class="box-tools pull-right"><button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button></div>
					</div>
					<div class="box-body">
						<table class="table table-bordered well-white">
							<tr><th colspan="3" class="text-center text-bold">Overall Summary</th></tr>
							<tr>
								<td style="width: 15%;">Target</td>
								<td style="width: 70%;">
									<div class="progress progress-sm progress-striped active">
										<div class="progress-bar progress-bar-primary" style="width: <?php echo round($target); ?>%"></div>
									</div>
								</td>
								<td style="width: 15%;"><span class="badge bg-light-blue"><?php echo round($target); ?>%</span></td>
							</tr>
							<tr>
								<td style="width: 15%;">Achieved</td>
								<td style="width: 70%;">
									<div class="progress progress-sm progress-striped active">
										<div class="progress-bar progress-bar-success" style="width: <?php echo round($achieved); ?>%"></div>
									</div>
								</td>
								<td style="width: 15%;"><span class="badge bg-green"><?php echo round($achieved); ?>%</span></td>
							</tr>
						</table>
						<hr>
						<table class="table table-striped row-border">
							<?php
							foreach($stdQuals AS $q)
							{
								$btnHTML = "";
								if($q->id == Workbook::CS_QAN)
								{
									$btnHTML = "";
								}
								echo <<<HTML
<tr>
	<td style="width: 5%;"><i class="fa fa-graduation-cap"></i> </td>
	<td style="width: 15%;">$q->id</td>
	<td style="width: 30%;">$q->title</td>
	<td style="width: 50%;">
		<table class="table table-bordered well-white">
			<tr>
				<td style="width: 15%;">Target</td>
				<td style="width: 70%;">
					<div class="progress progress-sm progress-striped active">
						<div class="progress-bar progress-bar-primary" style="width: $q->target%"></div>
					</div>
				</td>
				<td style="width: 15%;"><span class="badge bg-light-blue">$q->target%</span></td>
			</tr>
			<tr>
				<td style="width: 15%;">Achieved</td>
				<td style="width: 70%;">
					<div class="progress progress-sm progress-striped active">
						<div class="progress-bar progress-bar-success" style="width: $q->achieved%"></div>
					</div>
				</td>
				<td style="width: 15%;">
					<span class="badge bg-green">$q->achieved%</span><br>
				</td>
			</tr>
		</table>
	</td>
</tr>
HTML;
							}
							//echo '<tr><td colspan="4"><span class="btn btn-block btn-success" onclick="$(\'#divProgress\').hide();$(\'#divWorkbooks\').show();"><i class="fa fa-book"></i> &nbsp; Workbooks</span> </td></tr>';
							?>

						</table>
					</div>
				</div>
			</div>
		</div>
	</div>
	<div class="col-lg-6 col-md-6 col-sm-6">
		<div class="box box-info box-solid">
			<div class="box-header with-border"><h3 class="box-title"><i class="fa fa-calendar"></i> Diary</h3>
				<div class="box-tools pull-right"><button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button></div>
			</div>
			<div class="box-body no-padding">
				<div id="calendar"></div>
			</div>
		</div>
		<div class="box box-info box-solid">
			<div class="box-header with-border"><h3 class="box-title"><i class="fa fa-calendar"></i> Training Records</h3>
				<div class="box-tools pull-right"><button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button></div>
			</div>
			<div class="box-body no-padding">
				<div class="table-responsive">
					<?php 
					$sql = <<<SQL
SELECT
  tr.id,
  (SELECT student_frameworks.title FROM student_frameworks WHERE tr_id = tr.id)AS framework_title,
  DATE_FORMAT(tr.start_date, '%d/%m/%Y') AS start_date,
  DATE_FORMAT(tr.target_date, '%d/%m/%Y') AS planned_end_date,
  DATE_FORMAT(tr.closure_date, '%d/%m/%Y') AS end_date,
  CASE tr.status_code
	WHEN '1' THEN 'Continuing'
	WHEN '2' THEN 'Completed'
	WHEN '3' THEN 'Withdrawn'
	WHEN '6' THEN 'Temp. Withdrawn'
  END AS status_desc
FROM
  tr
WHERE tr.username = '{$tr->username}';
SQL;

					$learner_trs = DAO::getResultset($link, $sql, DAO::FETCH_ASSOC);
					echo '<table class="table table-bordered">';
					foreach($learner_trs AS $_tr)
					{
						echo '<tr>';
						echo '<td>' . $_tr['framework_title'] . '</td>';
						echo '<td>';
						echo 'Start Date: ' . $_tr['start_date'] . '<br>';
						echo 'Planned&nbsp;End&nbsp;Date:&nbsp;' . $_tr['planned_end_date'] . '<br>';
						echo 'Actual End Date: ' . $_tr['end_date'] . '<br>';
						echo 'Status: ' . $_tr['status_desc'];
						echo '</td>';
						echo '<td><span class="btn btn-xs btn-info" onclick="window.location.href=\'do.php?_action=read_training_record&id='.$_tr['id'].'\'"><i class="fa fa-folder-open"></i> Open</span></td>';
						echo '</tr>';
					}
					echo '</table>';
					?>
				</div>
			</div>
		</div>
	</div>
</div>
</div>
</div>


</div>

<aside class="control-sidebar control-sidebar-dark">
	<!-- Create the tabs -->
	<ul class="nav nav-tabs nav-justified control-sidebar-tabs">
		<li><a href="#control-sidebar-home-tab" data-toggle="tab"><i class="fa fa-home"></i></a></li>
		<li><a href="#control-sidebar-settings-tab" data-toggle="tab"><i class="fa fa-info-circle"></i></a></li>
	</ul>
	<!-- Tab panes -->
	<div class="tab-content">
		<!-- Home tab content -->
		<div class="tab-pane" id="control-sidebar-home-tab">
			<h3 class="control-sidebar-heading">First tab</h3>

		</div>
		<div class="tab-pane" id="control-sidebar-settings-tab">
			Second tab
		</div>
	</div>
</aside>

<div class="control-sidebar-bg"></div>

<div id="eventContent" title="Event Details" style="display:none;">
	Start: <span id="startTime"></span><br>
	Assessor/Interviewer: <span id="e_assessor"></span><br><br>
	<p id="eventInfo"></p>

</div>

<div id="reviewContent" title="Event Details" style="display:none;">
	<table class="table row-border">
		<tr><th>Date:</th><td><span id="r_start"></span></td></tr>
		<tr><th>Type:</th><td>Customer Service Practitioner Self-assessment / Review</td></tr>
		<tr><th>Assessor:</th><td><?php echo $assessor->firstnames . ' ' . $assessor->surname; ?></td></tr>
	</table>
</div>

<div id="panel_signature" title="Signature Panel">
	<div class="callout callout-info"><i class="fa fa-info-circle"></i> Enter your name/initials, then select the signature font you like and press "Create". </div>
	<div>
		<table class="table row-border">
			<tr><td>Enter your name/initials</td><td><input type="text" id="signature_text" onkeyup="refreshSignature();" onkeypress="return onlyAlphabets(event,this);" /></td></tr>
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


<script src="/assets/adminlte/plugins/jQueryUI/jquery-ui.min.js"></script>
<script src="/assets/adminlte/bootstrap/js/bootstrap.min.js"></script>
<script src="/assets/adminlte/dist/js/app.min.js"></script>
<script src="/common.js" type="text/javascript"></script>
<script type="text/javascript" src="/assets/adminlte/plugins/fullcalendar/moment.js"></script>
<script type="text/javascript" src="/assets/adminlte/plugins/fullcalendar/fullcalendar.js"></script>
<script type="text/javascript" src="/assets/adminlte/plugins/FileTree/jQueryFileTree.js"></script>
<link href="/assets/adminlte/plugins/chosen/bootstrap-chosen.css" rel="stylesheet">
<script src="/assets/adminlte/plugins/chosen/chosen.jquery.js"></script>


<script>
var phpLearnerSignature = '<?php echo $learner_signature; ?>';
$(function(){

	if(window.phpLearnerSignature == '')
	{
		$("<div></div>").html('<p><strong>Create your signature</strong></p><p>Your signature is required, please use "Your Signature" panel to create your signature.<br></p>').dialog({
			title: " Important information ",
			resizable: false,
			modal: true,
			width: 'auto',
			maxWidth: 550,
			height: 'auto',
			maxHeight: 500,
			closeOnEscape: false,
			buttons: {
				'OK': function() {
					$(this).dialog('close');
				}
			}
		}).css("background", "#FFF");
	}

	$('#calendar').fullCalendar({
		header    : {
			left  : 'prev,next today',
			center: 'title',
			right : 'month,agendaWeek,agendaDay'
		},
		buttonText: {
			today: 'today',
			month: 'month',
			week : 'week',
			day  : 'day'
		},
		weekends: false,
		events: 'do.php?_action=learner_calendar_manager&tr_id=<?php echo $tr->id; ?>',
		eventRender: function (event, element) {
			if(event.type != 'review')
			{
				element.attr('href', 'javascript:void(0);');
				element.click(function() {
					$("#startTime").html(moment(event.start).format('MMM Do h:mm A'));
					$("#e_assessor").html(event.assessor);
					$("#eventInfo").html(event.description);
					$("#eventLink").attr('href', event.url);
					$("#eventContent").dialog({
						modal: true,
						title: event.title,
						width:350,
						draggable: false,
						buttons:{
							"Close":function () {
								$(this).dialog("close");
							}
						}
					});
				});
			}
			else
			{
				element.attr('href', 'javascript:void(0);');
				element.click(function() {
					$("#r_start").html(moment(event.start).format('DD/MM/YYYY'));
					$("#eventLink").attr('href', event.url);
					$("#reviewContent").dialog({
						modal: true,
						title: event.title,
						width:350,
						draggable: false,
						buttons:{
							"Close":function () {
								$(this).dialog("close");
							}
						}
					});
				});
			}
		},
		editable  : false,
		droppable : false, // this allows things to be dropped onto the calendar !!!
		views: {
			basic: {
				// options apply to basicWeek and basicDay views
			},
			agenda: {
				// options apply to agendaWeek and agendaDay views
			},
			week: {
				columnFormat: 'ddd D/M'
			},
			day: {
				// options apply to basicDay and agendaDay views
			}
		}
	});

});

$(document).ready( function() {
	$('.fileTree').fileTree({
		script: 'do.php?_action=files'
		,root: '<?php echo '/'.$tr->username . '/'; ?>'
		,loadMessage: 'Loading Files ...'
	}, function(file) {
		//console.log(file);
	});

	$( "#panel_signature" ).dialog({
		autoOpen: false,
		modal: true,
		draggable: false,
		width: "auto",
		height: 500,
		buttons: {
			'Create': function() {
				if($('#signature_text').val() == '')
				{
					alert('Please input your signature');
					$('#signature_text').focus();
					return;
				}
				if($('.sigboxselected').children('img')[0] === undefined)
				{
					alert('Please select your font');
					return;
				}
				$("#img_user_signature").attr('src', $('.sigboxselected').children('img')[0].src);
				var _link = $('.sigboxselected').children('img')[0].src;
				_link = _link.split('&');
				$("#user_signature").val(_link[1]+'&'+_link[2]+'&'+_link[3]);
				if($('#user_signature').val() == '')
				{
					alert('Please create your signature');
					return;
				}
				if(!confirm('You can only save your signature once, are you sure you want to continue?'))
				{
					$("#img_user_signature").attr('src', 'do.php?_action=generate_image&title=Create your signature&font=Signature_Regular.ttf&size=20');
					$(this).dialog('close');
					return;
				}
				saveSignature();
				$(this).dialog('close');
			},
			'Cancel': function() {$(this).dialog('close');}
		}
	});

	if(window.phpLearnerSignature == '')
	{
		loadDefaultSignatures();
	}
});

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

function getSignature()
{
<?php if($learner_signature != '') { ?> return; <?php } ?>
	$( "#panel_signature" ).dialog( "open");
}

function SignatureSelected(sig)
{
	$(".sigboxselected").attr("class", "sigbox");
	sig.className = "sigboxselected";
}

function saveSignature()
{
	$.ajax({
		type:'POST',
		url:'do.php?_action=save_user_signature&from_page=learner_home_page&id=<?php echo $user_id; ?>&user_signature='+encodeURIComponent($('#user_signature').val()),
		success: function(data, textStatus, xhr) {
			window.location.reload();
		},
		error: function(data, textStatus, xhr){
			console.log(data.responseText);
		}
	});
}

function showWorkbooksKeys()
{
	var html = '<table class="table row-border">';
	html += '<tr><td><i class="fa fa-flag-o"></i></td><td>Not Started by Learner</td></tr>';
	html += '<tr><td><i class="fa fa-refresh"></i></td><td>In Progress by Learner</td></tr>';
	html += '<tr><td><i class="fa fa-send-o"></i></td><td>Submitted by Learner and awaiting Assessor feedback</td></tr>';
	html += '<tr><td><i class="fa fa-hourglass-o"></i></td><td>Being marked by Assessor</td></tr>';
	html += '<tr><td><i class="fa fa-warning"></i></td><td>Marked and Not Accepted by Assessor</td></tr>';
	html += '<tr><td><i class="fa fa-thumbs-o-up"></i></td><td>Marked and Signed Off by Assessor</td></tr>';
	html += '<tr><td><i class="fa fa-check"></i></td><td>Accepted by IV</td></tr>';
	html += '<tr><td><i class="fa fa-close"></i></td><td>Not Accepted by IV</td></tr>';
	html += '</table>';
	$("<div></div>").html(html).dialog({
		title: " Key ",
		resizable: false,
		modal: true,
		width: 'auto',
		maxWidth: 550,
		height: 'auto',
		maxHeight: 500,
		closeOnEscape: false,
		buttons: {
			'OK': function() {
				$(this).dialog('close');
			}
		}
	}).css("background", "#FFF");
}

</script>
</body>
</html>