<?php
$tutor_sql = <<<HEREDOC
SELECT
	users.id,
	CONCAT(
		IF(firstnames IS NULL, '', IF(surname IS NULL,firstnames, CONCAT(firstnames,' '))),
		IF(surname IS NULL,'',surname),
		IF(department IS NOT NULL OR job_role IS NOT NULL,
			CONCAT(' (', IF(department IS NOT NULL, IF(job_role IS NOT NULL, CONCAT(department,', ', job_role),department), job_role), ')'), '')
	),
	NULL
FROM
	users
WHERE
	employer_id={$course->organisations_id} and type=2
ORDER BY
	firstnames;
HEREDOC;


$assessor_sql = <<<HEREDOC
SELECT
	users.id,
	CONCAT(firstnames, ' ', surname),
	NULL
FROM
	users
INNER JOIN organisations on organisations.id = users.employer_id
where (employer_id={$course->organisations_id} or organisations.organisation_type = 1) and type=3
order by firstnames
HEREDOC;


//	$que = "select CONCAT(firstnames, ' ', surname) from users INNER JOIN organisations on organisations.id = users.employer_id where username='$g_vo->assessor'";

$verifier_sql = <<<HEREDOC
SELECT
	users.id,
	CONCAT(firstnames, ' ', surname),
	NULL
FROM
	users

INNER JOIN organisations on organisations.id = users.employer_id
where employer_id={$course->organisations_id} and type=4
order by firstnames
HEREDOC;


$tutor_select = DAO::getResultset($link, $tutor_sql);
$assessor_select = DAO::getResultset($link, $assessor_sql);
$verifier_select = DAO::getResultset($link, $verifier_sql);
?>
<div class="row">
	<div class="col-sm-2"></div>
	<div class="col-sm-8 well well-sm">
		<span class="pull-left btn btn-xs btn-success" onclick="document.forms['frmAddEditGroup'].submit();"><i class="fa fa-save"></i> Save Cohort</span>
	</div>
	<div class="col-sm-2"></div>
</div>
<p></p>

<div class="row">
	<div class="col-sm-12">
		<span class="lead text-bold"><?php echo $group->id == '' ? 'Add' : 'Edit' ?> Cohort Details</span>
		<div class="box box-primary">
			<div class="box-body with-border">
				<form class="form-horizontal" name="frmAddEditGroup" role="form" method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
					<input type="hidden" name="_action" value="save_course_group" />
					<input type="hidden" name="courses_id" value="<?php echo $course->id; ?>" />
					<input type="hidden" name="id" value="<?php echo $group->id; ?>" />

					<div class="row">
						<div class="col-sm-6">
							<div class="form-group">
								<label for="title" class="col-sm-4 control-label fieldLabel_compulsory">Cohort Title:</label>
								<div class="col-sm-8">
									<input type="text" class="form-control compulsory" name="title" id="title" value="<?php echo $group->title; ?>" maxlength="100" />
								</div>
							</div>
						</div>
						<div class="col-sm-6">
							<div class="form-group">
								<label for="tutor" class="col-sm-4 control-label fieldLabel_optional">Cohort Tutor:</label>
								<div class="col-sm-8">
									<?php echo HTML::selectChosen('tutor', $tutor_select, $group->tutor, true); ?>
								</div>
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-sm-6">
							<div class="form-group">
								<label for="assessor" class="col-sm-4 control-label fieldLabel_optional">Cohort Assessor:</label>
								<div class="col-sm-8">
									<?php echo HTML::selectChosen('assessor', $assessor_select, $group->assessor, true); ?>
								</div>
							</div>
						</div>
						<div class="col-sm-6">
							<div class="form-group">
								<label for="verifier" class="col-sm-4 control-label fieldLabel_optional">Cohort Verifier:</label>
								<div class="col-sm-8">
									<?php echo HTML::selectChosen('verifier', $verifier_select, $group->verifier, true); ?>
								</div>
							</div>
						</div>
					</div>
					<div class="form-group">
						<label for="verifier" class="col-sm-12 fieldLabel_compulsory">Learners Selection:</label>
						<div class="col-sm-12">
							<select class="form-control dual_select" name="members[]" id="members" multiple>
								<?php
								$sql = <<<HEREDOC
SELECT
	tr.surname, tr.firstnames, tr.l03, tr.id AS tr_id, tr.uln, organisations.legal_name AS employer,
	(SELECT COUNT(*) FROM group_members WHERE group_members.tr_id=tr.id AND group_members.groups_id = '$group->id') AS is_member
FROM
	tr
	LEFT JOIN organisations on organisations.id = tr.employer_id
	LEFT JOIN courses_tr on courses_tr.tr_id = tr.id
	LEFT JOIN group_members ON (tr.id = group_members.`tr_id`)
WHERE
	courses_tr.course_id = '$course->id'
	AND (tr.id NOT IN (SELECT tr_id FROM group_members) OR tr.id IN (SELECT tr_id FROM group_members WHERE groups_id = '$group->id'))
group by tr.id
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
				</form>
			</div>

		</div>
	</div>
</div>

