<?php
$groups_ddl = DAO::getResultset($link, "SELECT groups.id, groups.title, null FROM groups WHERE groups.courses_id = '{$course->id}' ORDER BY groups.title");
?>
<div class="row">
	<div class="col-sm-2"></div>
	<div class="col-sm-8 well well-sm">
		<span class="pull-left btn btn-xs btn-success" onclick="document.forms['frmAddEditTrainingGroup'].submit();"><i class="fa fa-save"></i> Save Training Group</span>
	</div>
	<div class="col-sm-2"></div>
</div>
<p></p>

<div class="row">
	<div class="col-sm-12">
		<span class="lead text-bold"><?php echo $tg->id == '' ? 'Create' : 'Edit' ?> Training Group</span>
		<div class="box box-primary">
			<div class="box-body with-border">
				<form class="form-horizontal" name="frmAddEditTrainingGroup" role="form" method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
					<input type="hidden" name="_action" value="save_training_group" />
					<input type="hidden" name="save_type" value="single" />
					<input type="hidden" name="course_id" value="<?php echo $course->id; ?>" />
					<input type="hidden" name="tg_id" value="<?php echo $tg->id; ?>" />

					<div class="row">
						<div class="col-sm-6">
							<div class="form-group">
								<?php if($tg->id == '') {?>
								<label for="group_id" class="col-sm-4 control-label fieldLabel_compulsory">Select Cohort:</label>
								<div class="col-sm-8">
									<?php echo HTML::selectChosen('group_id', $groups_ddl, $tg->group_id, true); ?>
								</div>
								<?php } else {?>
								<label for="group_id" class="col-sm-4 control-label fieldLabel_compulsory">Cohort:</label>
								<div class="col-sm-8">
									<input type="hidden" name="group_id" id="group_id" value="<?php echo $tg->group_id; ?>" />
									<h5 class="text-bold"><?php echo DAO::getSingleValue($link, "SELECT title FROM groups WHERE id = '{$tg->group_id}'"); ?></h5>
								</div>
								<?php } ?>
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-sm-6">
							<div class="form-group">
								<label for="title" class="col-sm-4 control-label fieldLabel_compulsory">Training Group Title:</label>
								<div class="col-sm-8">
									<input type="text" class="form-control compulsory" name="title" id="title" value="<?php echo $tg->title; ?>" maxlength="100" />
								</div>
							</div>
						</div>
					</div>
					<?php if($tg->id != ''){?>
					<div class="form-group">
						<label for="verifier" class="col-sm-12 fieldLabel_compulsory">Learners Selection:</label>
						<div class="col-sm-12">
							<select class="form-control dual_select" name="members[]" id="members" multiple>
								<?php
								$sql = <<<HEREDOC
SELECT
	tr.surname, tr.firstnames, tr.l03, tr.id AS tr_id, tr.uln,
	IF(tr.tg_id = '$tg->id', 1, 0) AS is_member,
	(SELECT training_groups.title FROm training_groups WHERE training_groups.id = tr.tg_id) AS tg_title
FROM
	tr
	INNER JOIN group_members ON group_members.tr_id = tr.id

WHERE
	group_members.groups_id = '$tg->group_id' AND (tr.`tg_id` IS NULL OR tr.`tg_id` = '$tg->id')
GROUP BY tr.id
ORDER BY
	tr.surname
HEREDOC;
								$members_list = DAO::getResultset($link, $sql, DAO::FETCH_ASSOC);
								foreach($members_list AS $member)
								{
									$selected = $member['is_member'] > 0 ? ' selected ' : '';
									echo '<option ' . $selected . ' value="' . $member['tr_id'] . '">' . $member['surname'] . ', ' . $member['firstnames'] . ' | L03: ' . $member['l03'] . ' | ULN: ' . $member['uln'] . '</option>';
								}
								?>
							</select>
						</div>
					</div>
					<?php } ?>
				</form>
			</div>

		</div>
	</div>
</div>

