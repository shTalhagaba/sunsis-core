<?php if($_SESSION['user']->isAdmin()){?>
<div class="row">
	<div class="col-sm-2"></div>
	<div class="col-sm-8 well well-sm">
		<span class="pull-left btn btn-xs btn-primary" onclick="window.location.replace('do.php?_action=read_course_v2&subview=enrol_learners&id=<?php echo $course->id; ?>');"><i class="fa fa-graduation-cap"></i> Enrol Learners</span>
		<span class="pull-right btn btn-xs btn-danger" onclick="window.location.replace('do.php?_action=read_course_v2&subview=delete_learners&id=<?php echo $course->id; ?>');"><i class="fa fa-trash"></i> Remove Learners</span>
	</div>
	<div class="col-sm-2"></div>
</div>
<?php } ?>
<p></p>
<div class="col-sm-12">
	<span class="lead text-bold">Learners</span>
	<form class="pull-right" method="get" name="preferences" action="<?php echo $_SERVER['PHP_SELF']; ?>">
		<input type="hidden" name="_action" value="read_course_v2" />
		<input type="hidden" name="subview" value="learners" />
		<input type="hidden" name="id" value="<?php echo $course->id; ?>" />
		<input type="hidden" name="ViewCourseLearners_showFSPassStats" value="<?php echo $viewSubview->getPreference('showFSPassStats'); ?>" />
		<input type="hidden" name="ViewCourseLearners_showAttendanceStats" value="<?php echo $viewSubview->getPreference('showAttendanceStats'); ?>" />
		<input type="hidden" name="ViewCourseLearners_showProgressStats" value="<?php echo $viewSubview->getPreference('showProgressStats'); ?>" />
		<input type="checkbox" name="showFSPassStats_ui" value="1" <?php echo $viewSubview->getPreference('showFSPassStats')=='1'?'checked="checked"':''; ?> onclick="document.forms['preferences'].elements['ViewCourseLearners_showFSPassStats'].value=(this.checked?'1':'0');document.forms['preferences'].submit()"/>FS Pass Stats
		<input type="checkbox" name="showAttendanceStats_ui" value="1" <?php echo $viewSubview->getPreference('showAttendanceStats')=='1'?'checked="checked"':''; ?> onclick="document.forms['preferences'].elements['ViewCourseLearners_showAttendanceStats'].value=(this.checked?'1':'0');document.forms['preferences'].submit()"/>Attendance
		<input type="checkbox" name="showProgressStats_ui" value="1" <?php echo $viewSubview->getPreference('showProgressStats')=='1'?'checked="checked"':''; ?> onclick="document.forms['preferences'].elements['ViewCourseLearners_showProgressStats'].value=(this.checked?'1':'0');document.forms['preferences'].submit()"/>Progress
	</form>
	<div class="box box-info box-solid collapsed-box">
		<div class="box-header with-border small">
			<span><i class="fa fa-search"></i> Filters</span>
			<div class="box-tools pull-right"><button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-plus"></i></button></div>
		</div>
		<div class="box-body">
			<form name="filters" method="get" action="<?php echo $_SERVER['PHP_SELF'] ?>" id="applyFilter">
				<input type="hidden" name="_action" value="read_course_v2" />
				<input type="hidden" name="subview" value="learners" />
				<input type="hidden" name="id" value="<?php echo $course->id; ?>" />
				<div class="row">
					<div class="col-sm-3">
						Learner Ref (l03)<br><?php echo $viewSubview->getFilterHTML('filter_learner_l03'); ?>
					</div>
					<div class="col-sm-3">
						Learner Firstnames<br><?php echo $viewSubview->getFilterHTML('filter_learner_firstnames'); ?>
					</div>
					<div class="col-sm-3">
						Learner Surname<br><?php echo $viewSubview->getFilterHTML('filter_learner_surname'); ?>
					</div>
					<div class="col-sm-3">
						Without Cohort/TG<br><?php echo $viewSubview->getFilterHTML('filter_without_cohort_or_tg'); ?>
					</div>
				</div>
				<div class="row">
					<div class="col-sm-4">
						Training Status<br><?php echo $viewSubview->getFilterHTML('filter_tr_record_status'); ?>
					</div>
					<div class="col-sm-4">
						Group<br><?php echo $viewSubview->getFilterHTML('filter_group'); ?>
					</div>
					<div class="col-sm-4">
						Training Group<br><?php echo $viewSubview->getFilterHTML('filter_tg'); ?>
					</div>
				</div>
				<div class="row">
					<div class="col-sm-12"><hr style="margin: 2px;"></div>
					<div class="col-sm-6"><button class="btn btn-xs btn-block btn-info" type="submit"><i class="fa fa-check"></i> Apply</button></div>
					<div class="col-sm-6"><button class="btn btn-xs btn-block btn-default" type="button" onclick="resetFilters();"><i class="fa fa-refresh"></i> Reset</div>
				</div>
			</form>
		</div>
	</div>
	<?php echo $viewSubview->render($link, $course->id); ?>
</div>