<?php if($_SESSION['user']->isAdmin()){?>
<div class="row">
	<div class="col-sm-2"></div>
	<div class="col-sm-8 well well-sm">
		<span class="pull-left btn btn-xs btn-primary"
		      onclick="window.location.href='do.php?_action=read_course_v2&subview=add_edit_group&id=<?php echo $course->id; ?>&group_id=<?php echo $group->id; ?>&from_view=group_view'">
			<i class="fa fa-edit"></i> Edit Cohort
		</span> &nbsp;
		<span class="btn btn-xs btn-primary"
		      onclick="window.location.href='do.php?_action=read_course_v2&subview=add_training_group_multiple&id=<?php echo $course->id; ?>&group_id=<?php echo $group->id; ?>&from_view=group_view'">
			<i class="fa fa-sitemap"></i> Add Training Groups
		</span>
		<span class="pull-right btn btn-xs btn-danger" onclick="delete_cohort('<?php echo $group->id; ?>');"><i class="fa fa-trash"></i> Delete Cohort</span>
	</div>
	<div class="col-sm-2"></div>
</div>
<?php } ?>
<p></p>
<div class="row">
	<div class="col-sm-12">
		<span class="lead text-bold">Cohort Detail </span>
	</div>
	<div class="col-sm-12">
		<table class="table table-bordered">
			<tr>
				<td><span class="text-bold">Title:</span><br><?php echo $group->title; ?></td>
				<td><span class="text-bold">Tutor:</span><br><?php echo $group->tutorName($link); ?></td>
				<td><span class="text-bold">Assessor:</span><br><?php echo $group->assessorName($link); ?></td>
				<td><span class="text-bold">Verifier:</span><br><?php echo $group->verifierName($link); ?></td>
			</tr>
		</table>
	</div>
</div>

<div class="row">
	<div class="col-sm-12">
		<span class="lead text-bold">Training Groups </span> <span class="badge bg-purple"><?php echo $group->getTrainingGroupsCount($link); ?></span>
	</div>
	<div class="col-sm-12 well well-sm">
		<?php
		$caseload = '';
		if($_SESSION['caseload_learners_only'] == '1')
			$caseload = " AND tr.coach = '{$_SESSION['user']->id}' ";
		$tgs = DAO::getResultset($link, "SELECT training_groups.id, training_groups.title, (SELECT COUNT(*) FROM tr WHERE tr.tg_id = training_groups.id {$caseload}) AS learners FROM training_groups WHERE group_id = '{$group->id}' ORDER BY title", DAO::FETCH_ASSOC);
		if(count($tgs) == 0)
			echo '<i>No training groups found.</i>';
		else
		{
			foreach($tgs AS $row)
			{
				$bg = $row['learners'] > 0 ? 'green' : 'yellow';
				echo <<<HTML
<div class="col-sm-3">
  <div class="info-box" style="cursor: pointer" onclick="window.location.replace('do.php?_action=read_course_v2&subview=training_group_view&id={$course->id}&group_id={$group->id}&tg_id={$row['id']}&from_view=group_view')">
    <span class="info-box-icon bg-{$bg}"><i class="fa fa-users"></i></span>
    <div class="info-box-content">
      <span class="info-box-number">{$row['title']}</span>
      <span class="info-box-text">Learners Count: {$row['learners']}</span>
    </div>
  </div>
</div>
HTML;
			}
		}
		?>
		<hr>
	</div>
</div>

<div class="row">
	<div class="col-sm-12">
		<span class="lead text-bold">Learners </span> <span class="badge bg-purple"><?php echo $group->getLearnersCount($link); ?></span>
	</div>
	<div class="col-sm-12">
		<?php
		echo $group_learners->render($link, $course->id, ['subview' => 'group_view']);
		?>
	</div>
</div>

