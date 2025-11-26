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
		<span class="pull-left btn btn-xs btn-success"
		      onclick="document.forms['frmAddGroupMultiple'].submit();">
			<i class="fa fa-save"></i> Save Cohorts
		</span>
	</div>
	<div class="col-sm-2"></div>
</div>
<p></p>
<div class="row">
	<div class="col-sm-12">
		<span class="lead text-bold">Add Cohorts</span>
		<div class="callout callout-info">
			<i class="fa fa-info-circle"></i> Create multiple cohorts for this course "<?php echo $course->title; ?>"
		</div>
	</div>
	<div>
		<div class="col-sm-9">
			<form class="form-horizontal" name="frmAddGroupMultiple" role="form" method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
				<input type="hidden" name="_action" value="save_course_groups" />
				<input type="hidden" name="save_type" value="multiple" />
				<input type="hidden" name="course_id" value="<?php echo $course->id; ?>" />
				<table class="table table-bordered">
					<tr><th style="width: 30%">Cohort Title</th><th>Tutor</th><th>Assessor</th><th>Verifier</th></tr>
					<?php
					for($i = 1; $i <= 10; $i++)
					{
						echo '<tr>';
						echo '<td><input type="text" class="form-control " name="title'.$i.'" maxlength="100" /></td>';
						echo '<td>' . HTML::selectChosen('tutor'.$i, $tutor_select, '', true) . '</td>';
						echo '<td>' . HTML::selectChosen('assessor'.$i, $assessor_select, '', true) . '</td>';
						echo '<td>' . HTML::selectChosen('verifier'.$i, $verifier_select, '', true) . '</td>';
						echo '</tr>';
					}
					?>
				</table>
			</form>
		</div>
		<div class="col-sm-3">
			<span class="lead">Existing Cohorts of <?php echo $course->title; ?></span><br>
			<?php
			$groups = DAO::getSingleColumn($link, "SELECT title FROM groups WHERE courses_id = '{$course->id}' ORDER BY id", DAO::FETCH_ASSOC);
			if(count($groups) == 0)
				echo '<i>No cohorts found.</i>';
			else
			{
				echo '<ul>';
				foreach($groups AS $g)
					echo '<li>' . $g . '</li>';
				echo '</ul>';
			}
			?>
		</div>
	</div>
</div>

