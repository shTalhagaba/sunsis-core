
<!DOCTYPE html>
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<title>Sunesis | Onboarding Dashboard</title>
	<link rel="stylesheet" href="module_tracking/css/common.css?n=<?php echo time(); ?>" type="text/css"/>
	<link rel="stylesheet" href="/assets/adminlte/bootstrap/css/bootstrap.min.css">
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.5.0/css/font-awesome.min.css">
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/ionicons/2.0.1/css/ionicons.min.css">
	<link rel="stylesheet" href="/assets/adminlte/plugins/jQueryUI/jquery-ui.css">
	<link rel="stylesheet" href="/assets/adminlte/dist/css/AdminLTE.min.css">

	<!--[if lt IE 9]>
	<script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
	<script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
	<![endif]-->


</head>

<body>
<div class="row">
	<div class="col-lg-12">
		<div class="banner">
			<div class="Title" style="margin-left: 6px;">Onboarding Dashboard</div>
			<div class="ButtonBar"></div>
			<div class="ActionIconBar"></div>
		</div>
	</div>
</div>

<div class="row">
	<div class="col-lg-12">
		<?php $_SESSION['bc']->render($link); ?>
	</div>
</div>

<p></p>

<div class="container-fluid">
	<div class="row">
		<div class="col-sm-6">
			<div class="box box-primary">
				<div class="box-header with-border">
					<h2 class="box-title"><span class="fa fa-calendar"></span> Learners</h2>
					<div class="box-tools pull-right">
					<button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
						<button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
					</div>
				</div>
				<div class="box-body">
					<div class="row">
						<div class="col-lg-6 col-xs-6">
							<div class="small-box bg-yellow">
								<div class="inner">
									<h2><?php echo isset($stats['Added'])?$stats['Added']:0; ?></h2>
									<p>Added <i>(not enrolled)</i></p>
								</div>
								<div class="icon"><i class="fa fa-users"></i></div>
								<a href="do.php?_action=view_ob_report&_reset=1&ViewOnboardingReport_filter_stage=Added" class="small-box-footer">Click to see <i class="fa fa-arrow-circle-right"></i></a>
							</div>
						</div>
						<div class="col-lg-6 col-xs-6">
							<div class="small-box bg-red">
								<div class="inner">
									<h2><?php echo isset($stats['Awaiting Learner'])?$stats['Awaiting Learner']:0; ?></h2>
									<p>Awaiting Learners</p>
								</div>
								<div class="icon"><i class="fa fa-users"></i></div>
								<a href="do.php?_action=view_ob_report&_reset=1&ViewOnboardingReport_filter_stage=Awaiting Learner" class="small-box-footer">Click to see <i class="fa fa-arrow-circle-right"></i></a>
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-lg-6 col-xs-6">
							<div class="small-box bg-blue">
								<div class="inner">
									<h2><?php echo isset($stats['Learner Completed And Awaiting Employer'])?$stats['Learner Completed And Awaiting Employer']:0; ?></h2>
									<p>Learners Completed And Awaiting Employer</p>
								</div>
								<div class="icon"><i class="fa fa-users"></i></div>
								<a href="do.php?_action=view_ob_report&_reset=1&ViewOnboardingReport_filter_stage=Learner Completed And Awaiting Employer" class="small-box-footer">Click to see <i class="fa fa-arrow-circle-right"></i></a>
							</div>
						</div>
						<div class="col-lg-6 col-xs-6">
							<div class="small-box bg-green">
								<div class="inner">
									<h2><?php echo isset($stats['Fully Completed'])?$stats['Fully Completed']:0; ?></h2>
									<p>Fully Completed</p>
								</div>
								<div class="icon"><i class="fa fa-users"></i></div>
								<a href="do.php?_action=view_ob_report&_reset=1&ViewOnboardingReport_filter_stage=Fully Completed" class="small-box-footer">Click to see <i class="fa fa-arrow-circle-right"></i></a>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>

		<div class="col-sm-6">
			<div class="box box-primary">
				<div class="box-header with-border">
					<h2 class="box-title"><span class="fa fa-calendar"></span> Learners by Employer Business Code</h2>
					<div class="box-tools pull-right">
						<button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
						<button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
					</div>
				</div>
				<div class="box-body">
					<?php echo $employerCodeStats; ?>
				</div>
			</div>
		</div>
	</div>
</div>

<script src="/assets/adminlte/plugins/jQuery/jquery-2.2.3.min.js"></script>
<script src="/assets/adminlte/plugins/jQueryUI/jquery-ui.min.js"></script>
<script src="/assets/adminlte/bootstrap/js/bootstrap.min.js"></script>
<script src="/assets/adminlte/dist/js/app.min.js"></script>
<script src="/common.js?n=<?php echo time(); ?>" type="text/javascript"></script>

<script language="JavaScript">

</script>

</body>
</html>
