<?php if($_SESSION['user']->isAdmin()){?>
<div class="row">
	<div class="col-sm-2"></div>
	<div class="col-sm-8 well well-sm">
		<span class="pull-left btn btn-xs btn-primary" onclick="window.location.href='do.php?_action=read_course_v2&subview=add_edit_group&id=<?php echo $course->id; ?>'">
			<i class="fa fa-edit"></i> Add Cohort
		</span>

	</div>
	<div class="col-sm-2"></div>
</div>
<?php } ?>
<p></p>
<div class="col-sm-12">
	<span class="lead text-bold">Cohorts</span>
	<?php echo $viewSubview->render($link); ?>
</div>