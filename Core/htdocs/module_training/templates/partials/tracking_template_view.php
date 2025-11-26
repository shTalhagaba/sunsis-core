<div class="row">
	<div class="col-sm-2"></div>
	<div class="col-sm-8 well well-sm">
		<span class="pull-left btn btn-xs btn-primary"
		      onclick="window.location.replace('do.php?_action=read_course_v2&subview=edit_tracking_template&id=<?php echo $course->id; ?>');">
			<i class="fa fa-edit"></i> Edit Template</span> &nbsp;
	</div>
	<div class="col-sm-2"></div>
</div>
<p></p>

<div class="row">
	<div class="col-sm-12">
		<span class="lead text-bold">Tracking Template</span>

		<p></p>
	</div>
	<div class="col-sm-12">
		<?php
		$tracking_template = $course->getKSBTemplate($link);
		if(count($tracking_template->sections) == 0) {
		?>
		<div class="alert alert-info">
			<h4><i class="icon fa fa-info"></i> Alert!</h4>
			No tracking template has been set for this course "<?php echo $course->title; ?>". Please click "Edit Template" to set a tracking template for this course.
		</div>
		<?php } else {?>
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
						'<div class="tab-pane active" id="tab_'.$section->section_id.'">' :
						'<div class="tab-pane" id="tab_'.$section->section_id.'">';

					foreach($section->elements AS $element)
					{
						echo '<ul>';

						echo '<li><span class="text-blue">' . $element->element_title . '</span>';
						foreach($element->evidences AS $evidence)
						{
							echo '<ul>';
							echo '<li>' . $evidence->evidence_title . '</li>';
							echo '</ul>';
						}
						echo '</li>';

						echo '</ul>';
					}

					echo '</div>';

					$first_section = false;
				}
				?>
			</div>
		</div>
		<?php } ?>
	</div>
</div>
