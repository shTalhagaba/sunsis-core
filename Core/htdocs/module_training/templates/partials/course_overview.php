<?php if($_SESSION['user']->isAdmin()){?>
<div class="row">
	<div class="col-sm-2"></div>
	<div class="col-sm-8 well well-sm">
		<span class="pull-left btn btn-xs btn-primary" onclick="window.location.replace('do.php?_action=edit_course&id=<?php echo $course->id; ?>');"><i class="fa fa-edit"></i> Edit Course</span> &nbsp;
		<span class="btn btn-xs btn-primary" onclick="window.location.replace('do.php?_action=read_course_v2&subview=add_group_multiple&id=<?php echo $course->id; ?>');"><i class="fa fa-sitemap"></i> Add Cohorts</span> &nbsp;
		<span class="btn btn-xs btn-primary" onclick="window.location.replace('do.php?_action=read_course_v2&subview=tracking_template_view&id=<?php echo $course->id; ?>');"><i class="fa fa-file"></i> Tracking Template</span> &nbsp;
		<span class="btn btn-xs btn-info" onclick="navigateToAttendanceReport();"><i class="fa fa-table"></i> Attendance Report</span> &nbsp;
		<span class="pull-right btn btn-xs btn-danger"><i class="fa fa-trash"></i> Delete Course</span>
	</div>
	<div class="col-sm-2"></div>
</div>
<?php } ?>
<p></p>
<div class="row">
	<div class="col-sm-6">
		<span class="lead text-bold">Course Overview</span>
		<div class="box box-primary">
			<div class="box-header">
				<span class="box-title"><?php echo $course->title; ?></span>
			</div>
			<div class="box-body">
				<div class="table-responsive">
					<table class="table table-bordered">
						<tr>
							<th>Provider</th>
							<td>
								<?php
								echo $provider->legal_name . '<br>';
								echo $provider_main_location->address_line_1 != '' ? $provider_main_location->address_line_1 . ', ' : '';
								echo $provider_main_location->address_line_2 != '' ? $provider_main_location->address_line_2 . ', ' : '';
								echo $provider_main_location->address_line_3 != '' ? $provider_main_location->address_line_3 . ', ' : '';
								echo $provider_main_location->address_line_4 != '' ? $provider_main_location->address_line_4 . '<br>' : '';
								echo $provider_main_location->postcode != '' ? '<i class="fa fa-map-marker"></i> ' . $provider_main_location->postcode . '<br>' : '';
								?>
							</td>
						</tr>
						<tr>
							<th>Duration</th>
							<td><?php echo Date::toShort($course->course_start_date) . ' - ' . Date::toShort($course->course_end_date); ?></td>
						</tr>
						<tr>
							<th>First Review</th>
							<td><?php echo $course->subsequent; ?> Weeks</td>
						</tr>
						<tr>
							<th>Subsequent Reviews</th>
							<td><?php echo $course->frequency; ?> Weeks</td>
						</tr>
						<tr>
							<th>Programme Type</th>
							<td><?php echo $course->programmeTitle($link); ?></td>
						</tr>
						<tr>
							<th>Framework</th>
							<td><?php echo $framework->title; ?></td>
						</tr>
						<tr>
							<th>Description</th>
							<td><?php echo $course->description; ?></td>
						</tr>
						<tr>
							<th>Qualifications</th>
							<td>
								<?php
								$result = DAO::getResultset($link, "SELECT id, internaltitle FROM framework_qualifications WHERE framework_id = '{$framework->id}'", DAO::FETCH_ASSOC);
								foreach($result AS $row)
									echo "{$row['id']} {$row['internaltitle']}<hr style='margin: 1px;'>";
								?>
							</td>
						</tr>
					</table>
				</div>
			</div>
		</div>
	</div>
	<div class="col-sm-6">
		<span class="lead text-bold">&nbsp;</span>
		<div class="box box-primary">
			<div class="box-header"><span class="box-title">Cohorts</span></div>
			<div class="box-body" style="max-height: 850px; overflow-y: scroll;">
				<?php if($course->groupsCount($link) == 0) {?>
				<i>No cohorts have been created for this course.</i>
				<?php } else {
				$members = "(SELECT COUNT(*) FROM group_members WHERE groups_id = groups.id ) AS members";
				if($_SESSION['caseload_learners_only'] == 1)
					$members = "(SELECT COUNT(*) FROM group_members INNER JOIN tr ON group_members.`tr_id` = tr.id WHERE groups_id = groups.id AND tr.`coach` = '{$_SESSION['user']->id}') AS members";
				$sql = <<<SQL
SELECT groups.id, groups.title, $members
FROM groups
WHERE groups.courses_id = '{$course->id}'
ORDER BY groups.title
SQL;
				$groups = DAO::getResultset($link, $sql, DAO::FETCH_ASSOC);
				foreach($groups AS $group)
				{
					$bg = $group['members'] > 0 ? 'green' : 'yellow';
					echo <<<HTML
<div class="col-sm-12">
  <div class="info-box" style="cursor: pointer" onclick="window.location.replace('do.php?_action=read_course_v2&subview=group_view&id={$course->id}&group_id={$group['id']}&from_view=overview')">
    <span class="info-box-icon bg-{$bg}"><i class="fa fa-users"></i></span>
    <div class="info-box-content">
      <span class="info-box-number">{$group['title']}</span>
      <span class="info-box-text">Learners Count: {$group['members']}</span>
    </div>
  </div>
</div>
HTML;
				}
				} ?>
			</div>
		</div>
	</div>
</div>

<div class="row">
	<div class="col-sm-12">
		<div class="box">
			<div class="box-header with-border">
				<h5 class="box-title text-bold">First time pass rates</h5>
			</div>
			<div class="box-body">
				<form action="<?php echo $_SERVER['PHP_SELF']; ?>">
					<input type="hidden" name="_action" value="read_course_v2">
					<input type="hidden" name="id" value="<?php echo $course->id; ?>">
					<div class="row">
						<div class="col-sm-2"><span class="pull-right"> Exam date from </span></div>
						<div class="col-sm-3">
							<input class="datepicker compulsory" type="text" id="fpr_start_date" name="fpr_start_date"
							       value="<?php echo isset($_REQUEST['fpr_start_date']) ? $_REQUEST['fpr_start_date'] : Date::toShort($course->course_start_date); ?>" size="10" maxlength="10" placeholder="dd/mm/yyyy" />
						</div>
						<div class="col-sm-2"><span class="pull-right"> to </span></div>
						<div class="col-sm-3">
							<input class="datepicker compulsory" type="text" id="fpr_end_date" name="fpr_end_date"
							       value="<?php echo isset($_REQUEST['fpr_end_date']) ? $_REQUEST['fpr_end_date'] : Date::toShort($course->course_end_date); ?>" size="10" maxlength="10" placeholder="dd/mm/yyyy" />
						</div>
						<div class="col-sm-2">
							<span class="btn btn-info btn-xs" onclick="$(this).closest('form').submit();"> <i class="fa fa-refresh"></i></span>
						</div>
					</div>
				</form>
				<div class="row">
					<div class="col-sm-6"><div id="panelExamResultsMaths"></div></div>
					<div class="col-sm-6"><div id="panelExamResultsEnglish"></div>
				</div>
			</div>
		</div>
	</div>
	<div class="col-sm-6">
		<div id="panelLearnersByEthnicity"></div>
	</div>
	<div class="col-sm-6">
		<div id="panelLearnersByAgeBand"></div>
	</div>
	<div class="col-sm-6">
		<div id="panelLearnersByGender"></div>
	</div>
	<div class="col-sm-6">
		<div id="panelLearnersByAssessors"></div>
	</div>
	<div class="col-sm-6">
		<div id="panelLearnersByOutcomeType"></div>
	</div>
	<div class="col-sm-6">
		<div id="panelLearnersByOutcomeCode"></div>
	</div>
	<div class="col-sm-6">
		<div id="panelLearnersByProgress"></div>
	</div>
</div>

<script type="text/javascript">
	function navigateToAttendanceReport()
	{
		var course_id = '<?php echo $course->id; ?>';
		<?php
		$d = new DateTime("now");
		$m = cal_days_in_month(CAL_GREGORIAN, $d->format("m"), $d->format("Y"));
		?>
		var first_date = '<?php echo "01/".$d->format("m")."/".$d->format("Y"); ?>';
		var last_date = '<?php echo $m."/".$d->format("m")."/".$d->format("Y"); ?>';

		window.location.replace('do.php?_action=view_tracking_attendance_report' +
			'&_reset=1' +
			'&filter_course='+encodeURIComponent(course_id) +
			'&view_tracking_entries_filter_date_start_date=' + encodeURIComponent(first_date) +
			'&view_tracking_entries_filter_date_end_date=' + encodeURIComponent(last_date) +
			'&view_tracking_entries_filter_date_range=1' +
			'&view_tracking_entries_filter_date_link_ui=1' +
			'&view_tracking_entries_filter_date='+encodeURIComponent(first_date + '|' + last_date)
		);
	}
</script>