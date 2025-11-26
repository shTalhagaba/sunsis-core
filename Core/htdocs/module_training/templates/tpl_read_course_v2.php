<?php /* @var $course Course */ ?>
<?php /* @var $framework Framework */ ?>
<?php /* @var $provider Organisation */ ?>
<?php /* @var $provider_main_location Location */ ?>
<?php /* @var $group CourseGroupVO */ ?>

<!DOCTYPE html>
<head xmlns="http://www.w3.org/1999/html">
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<title>View Course</title>
	<link rel="stylesheet" href="module_tracking/css/common.css" type="text/css"/>
	<link rel="stylesheet" href="/assets/adminlte/bootstrap/css/bootstrap.min.css">
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.5.0/css/font-awesome.min.css">
	<link rel="stylesheet" href="/assets/adminlte/plugins/jQueryUI/jquery-ui.css">
	<link rel="stylesheet" href="/assets/adminlte/dist/css/AdminLTE.min.css">
	<link href="/assets/adminlte/plugins/toastr/toastr.min.css" rel="stylesheet">
	<link href="/assets/adminlte/plugins/duallistbox/bootstrap-duallistbox.min.css" rel="stylesheet">
	<link href="/module_training/assets/jstree/dist/themes/default/style.min.css" rel="stylesheet">
	<link href="/assets/adminlte/plugins/datatables/jquery.dataTables.css" rel="stylesheet">

	<!--[if lt IE 9]>
	<script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
	<script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
	<![endif]-->
	<style>
		/* Start by setting display:none to make this hidden.
		   Then we position it in relation to the viewport window
		   with position:fixed. Width, height, top and left speak
		   for themselves. Background we set to 80% white with
		   our animation centered, and no-repeating */
		.modal {
			display:    none;
			position:   fixed;
			z-index:    1000;
			top:        0;
			left:       0;
			height:     100%;
			width:      100%;
			background: rgba( 255, 255, 255, .8 ) url('images/ajax-loader.gif') 50% 50% no-repeat;
		}

		/* When the body has the loading class, we turn
		   the scrollbar off with overflow:hidden */
		body.loading .modal {
			overflow: hidden;
		}

		/* Anytime the body has the loading class, our
		   modal element will be visible */
		body.loading .modal {
			display: block;
		}
	</style>
	<style>
		.disabled {
			pointer-events: none;
			opacity: 0.4;
		}
		.ui-datepicker .ui-datepicker-title select {
			color: #000;
		}
		.btn-app{
			border-radius:3px;position:relative;padding:15px 5px;margin:0 0 5px 10px;min-width:80px;height:60px;
			text-align:center;color:#666;border:1px solid #ddd;background-color:#f4f4f4;font-size:12px
		}
		.btn-app>.fa{
			font-size:20px;display:block
		}
		.btn-app:hover{
			background:#f4f4f4;color:#444;border-color:#aaa
		}
		.btn-app:active,.btn-app:focus{
			-webkit-box-shadow:inset 0 3px 5px rgba(0,0,0,0.125);-moz-box-shadow:inset 0 3px 5px rgba(0,0,0,0.125);
			box-shadow:inset 0 3px 5px rgba(0,0,0,0.125)
		}
		.btn-app>.badge{
			position:absolute;top:-3px;right:-10px;font-size:10px;font-weight:400
		}
		.chkEnrolLearnersSelection, .chkDeleteLearnersSelection {
			transform: scale(1.4);
		}
	</style>

</head>
<body>
<div class="row">
	<div class="col-lg-12">
		<div class="banner">
			<div class="Title" style="margin-left: 6px;">View Course</div>
			<div class="ButtonBar">
				<span class="btn btn-sm btn-default" onclick="window.location.href='<?php echo $_SESSION['bc']->getPrevious(); ?>';"><i class="fa fa-arrow-circle-o-left"></i> Cancel</span>
			</div>
			<div class="ActionIconBar">
				<?php if(!$_SESSION['user']->isAdmin()) { ?>
				<input type="checkbox" name="caseload_only"
					<?php echo $_SESSION['caseload_learners_only'] == '1' ? 'checked="checked"' : ''; ?>
				       onclick="updateCaseloadCheck(this);"/> My caseload only &nbsp;
				<?php } ?>
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

<div class="container-fluid">

	<div class="row">
		<div class="col-sm-12">
			<table class="table table-bordered">
				<tr>
					<td>
						<span class="text-bold">Course Title:</span><br>
						<?php echo $course->title; ?>
						<span class="pull-right">
							<?php
							echo $course->active == '1' ? '<label class="label label-success">Active</label>' : '<label class="label label-danger">Not Active</label>';
							?>
						</span>
					</td>
					<td>
						<span class="text-bold">Provider:</span><br>
						<?php
						echo $provider->legal_name . ' &nbsp; ';
						echo $provider_main_location->address_line_1 != '' ? $provider_main_location->address_line_1 . ', ' : '';
						echo $provider_main_location->address_line_4 != '' ? $provider_main_location->address_line_4 . ', ' : '';
						echo $provider_main_location->postcode != '' ? '<i class="fa fa-map-marker"></i> ' . $provider_main_location->postcode . '<br>' : '';
						?>
					</td>
					<td><span class="text-bold">Duration:</span><br><?php echo Date::toShort($course->course_start_date) . ' - ' . Date::toShort($course->course_end_date); ?></td>
				</tr>
			</table>
		</div>
	</div>
	<div class="row">
		<div class="col-sm-2"></div>
		<div class="col-sm-8">
			<div class="well well-sm well-white" style="padding-bottom: 1px;">
				<a class="btn btn-app <?php echo $btn_overview; ?>" href="do.php?_action=read_course_v2&subview=overview&id=<?php echo $course->id; ?>">
					<i class="fa fa-folder"></i> Overview
				</a>
				<a class="btn btn-app <?php echo $btn_learners; ?>" href="do.php?_action=read_course_v2&subview=learners&id=<?php echo $course->id; ?>">
					<span class="badge bg-purple"><?php echo $course->learnersCount($link); ?></span>
					<i class="fa fa-users"></i> Learners
				</a>
				<a class="btn btn-app <?php echo $btn_groups; ?>" href="do.php?_action=read_course_v2&subview=groups&id=<?php echo $course->id; ?>">
					<span class="badge bg-purple"><?php echo $course->groupsCount($link); ?></span>
					<i class="fa fa-object-group"></i> Cohorts
				</a>
				<a class="btn btn-app <?php echo $btn_training_groups; ?>" href="do.php?_action=read_course_v2&subview=training_groups&id=<?php echo $course->id; ?>">
					<span class="badge bg-purple"><?php echo $course->trainingGroupsCount($link); ?></span>
					<i class="fa fa-object-group"></i> Training Groups
				</a>
				<a class="btn btn-app <?php echo $btn_tracking; ?>" href="do.php?_action=read_course_v2&subview=tracking_view&id=<?php echo $course->id; ?>">
					<i class="fa fa-check-circle"></i> Tracker
				</a>
			</div>
		</div>
		<div class="col-sm-2"></div>
	</div>


	<?php if($subview == 'overview') {?>
	<div class="row">
		<div class="col-sm-12 colCourseOverview">
			<?php include_once(__DIR__ . '/partials/course_overview.php'); ?>
		</div>
	</div>
	<?php } ?>
	<?php if($subview == 'learners') {?>
	<div class="row">
		<div class="col-sm-12 colCourseLearners">
			<?php include_once(__DIR__ . '/partials/course_learners.php'); ?>
		</div>
	</div>
	<?php } ?>
	<?php if($subview == 'enrol_learners') {?>
	<div class="row">
		<div class="col-sm-12 colEnrolLearners">
			<?php include_once(__DIR__ . '/partials/enrol_learners.php'); ?>
		</div>
	</div>
	<?php } ?>
	<?php if($subview == 'delete_learners') {?>
	<div class="row">
		<div class="col-sm-12 colDeleteLearners">
			<?php include_once(__DIR__ . '/partials/delete_learners.php'); ?>
		</div>
	</div>
	<?php } ?>
	<?php if($subview == 'groups') {?>
	<div class="row">
		<div class="col-sm-12 colCourseGroups">
			<?php include_once(__DIR__ . '/partials/course_groups.php'); ?>
		</div>
	</div>
	<?php } ?>
	<?php if($subview == 'training_groups') {?>
	<div class="row">
		<div class="col-sm-12 colCourseGroupTrainingGroups">
			<?php include_once(__DIR__ . '/partials/course_training_groups.php'); ?>
		</div>
	</div>
	<?php } ?>
	<?php if($subview == 'group_view') {?>
	<div class="row">
		<div class="col-sm-12 colGroupView">
			<?php include_once(__DIR__ . '/partials/group_view.php'); ?>
		</div>
	</div>
	<?php } ?>
	<?php if($subview == 'add_group_multiple') {?>
	<div class="row">
		<div class="col-sm-12 colAddGroupMultiple">
			<?php include_once(__DIR__ . '/partials/add_group_multiple.php'); ?>
		</div>
	</div>
	<?php } ?>
	<?php if($subview == 'tracking_template_view') {?>
	<div class="row">
		<div class="col-sm-12 colViewTrackingTemplate">
			<?php include_once(__DIR__ . '/partials/tracking_template_view.php'); ?>
		</div>
	</div>
	<?php } ?>
	<?php if($subview == 'edit_tracking_template') {?>
	<div class="row">
		<div class="col-sm-12 colEditTrackingTemplate">
			<?php include_once(__DIR__ . '/partials/edit_tracking_template.php'); ?>
		</div>
	</div>
	<?php } ?>
	<?php if($subview == 'tracking_view') {?>
	<div class="row">
		<div class="col-sm-12 colViewTracking">
			<?php include_once(__DIR__ . '/partials/tracking_view.php'); ?>
		</div>
	</div>
	<?php } ?>
	<?php if($subview == 'add_edit_group') {?>
	<div class="row">
		<div class="col-sm-12 colManageGroup">
			<?php include_once(__DIR__ . '/partials/group_form.php'); ?>
		</div>
	</div>
	<?php } ?>
	<?php if($subview == 'add_training_group_multiple') {?>
	<div class="row">
		<div class="col-sm-12 colAddTrainingGroupMultiple">
			<?php include_once(__DIR__ . '/partials/add_training_group_multiple.php'); ?>
		</div>
	</div>
	<?php } ?>
	<?php if($subview == 'add_edit_training_group') {?>
	<div class="row">
		<div class="col-sm-12 colManageTrainingGroup">
			<?php include_once(__DIR__ . '/partials/training_group_form.php'); ?>
		</div>
	</div>
	<?php } ?>
	<?php if($subview == 'training_group_view') {?>
	<div class="row">
		<div class="col-sm-12 colViewTrainingGroup">
			<?php include_once(__DIR__ . '/partials/training_group_view.php'); ?>
		</div>
	</div>
	<?php } ?>

</div> <!--container-fluid-->

<script type="text/javascript">
	var phpCourseID = '<?php echo $course->id; ?>';
</script>
<script src="/assets/adminlte/plugins/jQuery/jquery-2.2.3.min.js"></script>
<script src="/assets/adminlte/plugins/jQueryUI/jquery-ui.min.js"></script>
<script src="/assets/adminlte/bootstrap/js/bootstrap.min.js"></script>
<script src="/assets/adminlte/dist/js/app.min.js"></script>
<script src="/common.js" type="text/javascript"></script>
<script src="/assets/adminlte/plugins/toastr/toastr.min.js"></script>
<script src="https://code.highcharts.com/7.0.0/highcharts.src.js"></script>
<script src="https://code.highcharts.com/7.0.0/highcharts-3d.js"></script>
<script src="https://code.highcharts.com/7.0.0/modules/exporting.js"></script>
<script src="https://code.highcharts.com/7.0.0/modules/no-data-to-display.js"></script>
<script src="/assets/adminlte/plugins/duallistbox/jquery.bootstrap-duallistbox.js"></script>
<script src="/module_training/assets/jstree/dist/jstree.js"></script>
<script src="/assets/adminlte/plugins/datatables/jquery.dataTables.min.js"></script>
<script src="/module_training/assets/module_training_js.js?n=<?php echo time(); ?>"></script>


<script type="text/javascript">
	$(function() {
		<?php if($subview == 'overview') {?>
		var chart = new Highcharts.chart('panelLearnersByEthnicity', <?php echo $panelLearnersByEthnicity; ?>);
		var chart = new Highcharts.chart('panelLearnersByAgeBand', <?php echo $panelLearnersByAgeBand; ?>);
		var chart = new Highcharts.chart('panelLearnersByGender', <?php echo $panelLearnersByGender; ?>);
		var chart = new Highcharts.chart('panelLearnersByAssessors', <?php echo $panelLearnersByAssessors; ?>);
		var chart = new Highcharts.chart('panelLearnersByOutcomeType', <?php echo $panelLearnersByOutcomeType; ?>);
		var chart = new Highcharts.chart('panelLearnersByOutcomeCode', <?php echo $panelLearnersByOutcomeCode; ?>);
		var chart = new Highcharts.chart('panelLearnersByProgress', <?php echo $panelLearnersByProgress; ?>);
		var chart = new Highcharts.chart('panelExamResultsMaths', <?php echo $panelExamResultsMaths; ?>);
		var chart = new Highcharts.chart('panelExamResultsEnglish', <?php echo $panelExamResultsEnglish; ?>);
		<?php } ?>
	});

</script>

<div class="modal"><!-- Place at bottom of page --></div>

</body>
</html>
