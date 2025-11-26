<div class="row">
	<div class="col-sm-2"></div>
	<div class="col-sm-8 well well-sm">
		<span class="btn btn-xs btn-primary"
		      onclick="window.location.href='do.php?_action=add_learners_tracking&subview=show_learners&course_id=<?php echo $course->id; ?>'">
			<i class="fa fa-graduation-cap"></i> Record Tracking
		</span>
	</div>
	<div class="col-sm-2"></div>
</div>
<p></p>

<div class="row">
	<div class="col-sm-12">
		<span class="lead text-bold">Tracking </span>

		<p></p>
	</div>
	<div class="col-sm-12">
		<?php
		$tracking_template = $course->getKSBTemplate($link);
		if(count($tracking_template->sections) == 0) {
		?>
		<div class="alert alert-info">
			<h4><i class="icon fa fa-info"></i> Alert!</h4>
			No tracking template has been set for this course "<?php echo $course->title; ?>". Please go to Overview tab and set the tracking template for this course.
		</div>
		<?php } else {?>
		<p class="text-center">
			<?php
			$status_ddl = [
				[1, 'Continuing Learners'],
				[2, 'Completed Learners'],
				[3, 'Withdrawn Learners'],
				[6, 'Temp. Withdrawn Learners'],
				[4, 'Transferred Learners'],
			];
			echo HTML::select('tracking_learner_status', $status_ddl, $tracking_learner_status, false);
			?>
		</p>
		<span class="btn btn-default btn-sm pull-right" onclick="download_tracking_view_to_csv('<?php echo $course->id; ?>');"><i class="fa fa-download"></i> Download</span>
		<div class="nav-tabs-custom">
			<ul class="nav nav-tabs">
				<?php
				$first_section = true;
				foreach($tracking_template->sections AS $section)
				{
					echo $first_section ?
						'<li class="active"><a href="#tab_'.$section->section_id.'" data-toggle="tab">'.$section->section_title.'</a></li>' :
						'<li><a href="#tab_'.$section->section_id.'" data-toggle="tab">'.$section->section_title.'</a></li>';
					$first_section = false;
				}
				?>
			</ul>
			<div class="tab-content">
				<?php
				$first_section = true;
				foreach($tracking_template->sections AS $section)
				{
					echo $first_section ?
						'<div class="tab-pane active" id="tab_'.$section->section_id.'">'.$this->renderStudentsTrackingTab($link, $course->id, $section, $tracking_learner_status).'</div>' :
						'<div class="tab-pane" id="tab_'.$section->section_id.'">'.$this->renderStudentsTrackingTab($link, $course->id, $section, $tracking_learner_status).'</div>';

					$first_section = false;
				}
				?>
			</div>
		</div>
		<?php } ?>
	</div>
</div>


