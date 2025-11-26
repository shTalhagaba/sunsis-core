<div class="row">
	<div class="col-sm-2"></div>
	<div class="col-sm-8 well well-sm">
		<span class="pull-left btn btn-xs btn-success"
		      onclick="document.forms['frmAddTrainingGroupMultiple'].submit();">
			<i class="fa fa-save"></i> Save Training Groups
		</span>
	</div>
	<div class="col-sm-2"></div>
</div>
<p></p>
<div class="row">
	<div class="col-sm-12">
		<span class="lead text-bold">Add Training Groups to Cohort: </span><span class="lead text-blue"><?php echo $group->title; ?></span>
		<div class="callout callout-info">
			<i class="fa fa-info-circle"></i> Create multiple training groups for this cohort "<?php echo $group->title; ?>". Learners can be added into training groups by editing the training groups individually.
		</div>
	</div>
	<div>
		<div class="col-sm-4">
			<form class="form-horizontal" name="frmAddTrainingGroupMultiple" role="form" method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
				<input type="hidden" name="_action" value="save_training_group" />
				<input type="hidden" name="save_type" value="multiple" />
				<input type="hidden" name="course_id" value="<?php echo $course->id; ?>" />
				<input type="hidden" name="group_id" value="<?php echo $group->id; ?>" />
				<table class="table table-bordered">
					<tr><th>Training Group Title</th></tr>
					<?php
					for($i = 1; $i <= 10; $i++)
					{
						echo '<tr>';
						echo '<td><input type="text" class="form-control " name="titles[]" id="title'.$i.'" maxlength="100" /></td>';
						echo '</tr>';
					}
					?>
				</table>
			</form>
		</div>
		<div class="col-sm-2"></div>
		<div class="col-sm-4">
			<span class="lead">Existing Training Groups of Cohort: <?php echo $group->title; ?></span><br>
			<?php
			$tgs = DAO::getSingleColumn($link, "SELECT title FROM training_groups WHERE group_id = '{$group->id}' ORDER BY id", DAO::FETCH_ASSOC);
			if(count($tgs) == 0)
				echo '<i>No training groups found.</i>';
			else
			{
				echo '<ul>';
				foreach($tgs AS $tg)
					echo '<li>' . $tg . '</li>';
				echo '</ul>';
			}
			?>
		</div>
	</div>
</div>

