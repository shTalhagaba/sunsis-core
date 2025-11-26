<!DOCTYPE html>
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<title>Enrol Learners</title>
	<link rel="stylesheet" href="module_tracking/css/common.css?n=<?php echo time(); ?>" type="text/css"/>
	<link rel="stylesheet" href="/assets/adminlte/bootstrap/css/bootstrap.min.css">
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.5.0/css/font-awesome.min.css">
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/ionicons/2.0.1/css/ionicons.min.css">
	<link rel="stylesheet" href="/assets/adminlte/plugins/jQueryUI/jquery-ui.css">
	<link rel="stylesheet" href="/assets/adminlte/dist/css/AdminLTE.min.css">
	<link rel="stylesheet" href="/assets/adminlte/plugins/iCheck/all.css">

	<!--[if lt IE 9]>
	<script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
	<script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
	<![endif]-->


	<style>
		.ui-datepicker .ui-datepicker-title select {
			color: #000;
		}

		thead, th {
			text-align: center;
		}

		.disabled{
			pointer-events:none;
			opacity:0.4;
		}
	</style>
</head>
<body>
<div class="row">
	<div class="col-lg-12">
		<div class="banner">
			<div class="Title" style="margin-left: 6px;">Enrol Learners</div>
			<div class="ButtonBar">
				<span class="btn btn-sm btn-default" onclick="window.location.href='<?php echo $_SESSION['bc']->getPrevious(); ?>';"><i class="fa fa-arrow-circle-o-left"></i> Cancel</span>
			</div>
			<div class="ActionIconBar">

			</div>
		</div>

	</div>
</div>
<div class="row">
	<div class="col-lg-12">
		<?php $_SESSION['bc']->render($link); ?>
	</div>
</div>
<br>
<div align="center" class="table-responsive small">
	<form class="form-horizontal" name="frmEnrolLearners" id="frmAddLearners" action="<?php echo $_SERVER['PHP_SELF']; ?>">
		<input type="hidden" name="_action" value="enrol_ob_learners" />
		<table id="tblLearners" class="table table-bordered table-striped" cellspacing="0">
			<thead>
			<tr>
				<th>Learner Details</th>
				<th>Training Details</th>
				<th>Additional Information</th>
				<th>Action</th>
			</tr>
			</thead>
			<tbody>
			<?php
			$sql = new SQLStatement("
SELECT
  ob_learners.id, ob_learners.firstnames,ob_learners.surname,ob_learners.dob,ob_learners.home_postcode,ob_learners.job_title,
  ob_learners.home_email,ob_learners.start_date,ob_learners.planned_end_date,ob_learners.course_id,ob_learners.gender,
  ob_learners.ERR,ob_learners.PLTS,ob_learners.employer_id,employers.`legal_name` AS employer,
  colleges.`legal_name` AS college,frameworks.`title` AS framework,courses.`title` AS course,
  (SELECT CONCAT(REPLACE(id, '/', ''), '-', internaltitle) FROM framework_qualifications t1 WHERE t1.framework_id = ob_learners.`framework_id` AND REPLACE(id, '/', '') = ob_learners.`tech_cert`) AS tech_cert,
  (SELECT CONCAT(REPLACE(id, '/', ''), '-', internaltitle) FROM framework_qualifications t2 WHERE t2.framework_id = ob_learners.`framework_id` AND REPLACE(id, '/', '') = ob_learners.`l2_found_competence`) AS l2_found_competence,
  (SELECT CONCAT(REPLACE(id, '/', ''), '-', internaltitle) FROM framework_qualifications t3 WHERE t3.framework_id = ob_learners.`framework_id` AND REPLACE(id, '/', '') = ob_learners.`main_aim` LIMIT 0, 1) AS main_aim,
  (SELECT CONCAT(REPLACE(id, '/', ''), '-', internaltitle) FROM framework_qualifications t4 WHERE t4.framework_id = ob_learners.`framework_id` AND REPLACE(id, '/', '') = ob_learners.`fs_maths`) AS fs_maths,
  (SELECT CONCAT(REPLACE(id, '/', ''), '-', internaltitle) FROM framework_qualifications t5 WHERE t5.framework_id = ob_learners.`framework_id` AND REPLACE(id, '/', '') = ob_learners.`fs_eng`) AS fs_eng,
  (SELECT CONCAT(REPLACE(id, '/', ''), '-', internaltitle) FROM framework_qualifications t6 WHERE t6.framework_id = ob_learners.`framework_id` AND REPLACE(id, '/', '') = ob_learners.`fs_ict`) AS fs_ict,
  (SELECT CONCAT(REPLACE(id, '/', ''), '-', internaltitle) FROM framework_qualifications t6 WHERE t6.framework_id = ob_learners.`framework_id` AND REPLACE(id, '/', '') = ob_learners.`other_qual`) AS other_qual
FROM
  ob_learners
  LEFT JOIN organisations AS employers ON ob_learners.`employer_id` = employers.`id`
  LEFT JOIN organisations AS colleges ON ob_learners.`college_id` = colleges.`id`
  LEFT JOIN frameworks ON ob_learners.`framework_id` = frameworks.`id`
  LEFT JOIN courses ON ob_learners.`course_id` = courses.`id`
  LEFT JOIN users ON ob_learners.`user_id` = users.`id`
  LEFT JOIN tr ON users.`username` = tr.`username`


ORDER BY
  ob_learners.`created` DESC
");
			$sql->setClause("WHERE tr.id IS NULL");
			if(!in_array(DB_NAME, ["am_lead", "am_lead_demo"]))
				$sql->setClause("WHERE ob_learners.start_date IS NOT NULL");
			else
				$sql->setClause("WHERE ob_learners.user_id IS NOT NULL");
//pre($sql->__toString());
			$records = DAO::getResultset($link, $sql->__toString(), DAO::FETCH_ASSOC);
			foreach($records AS $row)
			{
				echo '<tr id="row'.$row['id'].'">';
				echo '<td>';
				echo '<dl class="dl-horizontal">';
				echo '<dt>Name:</dt><dd><span class="text-muted">' . $row['firstnames'] . ' ' . strtoupper($row['surname']) .'</span></dd>';
				echo '<dt>Date of Birth:</dt><dd><span class="text-muted">' . Date::toShort($row['dob']) . '</span></dd>';
				echo '<dt>Gender:</dt><dd><span class="text-muted">' . $row['gender'] . '</span></dd>';
				echo '<dt>Postcode:</dt><dd><span class="text-muted">' . $row['home_postcode'] . '</span></dd>';
				echo '<dt>Email:</dt><dd><span class="text-muted">' . $row['home_email'] . '</span></dd>';
				echo '<dt>Employer:</dt><dd><span class="text-muted">' . $row['employer'] . '</span></dd>';
				echo '<dt>Job Title:</dt><dd><span class="text-muted">' . DAO::getSingleValue($link, "SELECT description FROM lookup_job_roles WHERE id = '{$row['job_title']}'") . '</span></dd>';
				echo '<dt>College:</dt><dd><span class="text-muted">' . $row['college'] . '</span></dd>';
				echo '</dl>';
				echo '</td>';
				echo '<td>';
				echo '<dl class="dl-horizontal">';
				echo '<dt>Dates:</dt><dd><span class="text-muted">' . Date::toShort($row['start_date']) . ' - ' . Date::toShort($row['planned_end_date']) .'</span></dd>';
				if($row['dob'] != '')
				{
					$d1 = new Date($row['dob']);
					$d2 = new Date($row['start_date']);
					$date_diff = Date::dateDiffInfo($d1, $d2);
					echo '<dt>Age at training start:</dt><dd>'.$date_diff['year'].' years</dd>';
				}
				else
				{
					echo '<dt>Age at training start:</dt><dd>n/a</dd>';
				}
				echo '<dt>Framework:</dt><dd><span class="text-muted">' . $row['framework'] . '</span></dd>';
				echo '<dt>Course:</dt><dd><span class="text-muted">' . $row['course'] . '</span></dd>';
				echo '</dl>';
				echo '<table class="table table-bordered">';
				echo '<tr><th>&nbsp;</th><th>Qualification</th><th>Start Date</th><th>Plan. End Date</th></tr>';
				if($row['main_aim'] != '')
					echo '<tr><td>Main Aim:</td><td class="text-muted">' . $row['main_aim'] . '</td><td>'.HTML::datebox('ma_start_'.$row['id'], $row['start_date']).'</td><td>'.HTML::datebox('ma_pe_'.$row['id'], $row['planned_end_date']).'</td></tr>';
				if($row['tech_cert'] != '')
					echo '<tr><td>Tech Cert:</td><td class="text-muted">' . $row['tech_cert'] . '</td><td>'.HTML::datebox('tc_start_'.$row['id'], $row['start_date']).'</td><td>'.HTML::datebox('tc_pe_'.$row['id'], $row['planned_end_date']).'</td></tr>';
				if($row['l2_found_competence'] != '')
					echo '<tr><td>L2 Found. Competence:</td><td span class="text-muted">' . $row['l2_found_competence'] . '</td><td>'.HTML::datebox('l2_start_'.$row['id'], $row['start_date']).'</td><td>'.HTML::datebox('l2_pe_'.$row['id'], $row['planned_end_date']).'</td></tr>';
				if($row['fs_maths'] != '')
					echo '<tr><td>FS Maths:</td><td class="text-muted">' . $row['fs_maths'] . '</td><td>'.HTML::datebox('fsm_start_'.$row['id'], $row['start_date']).'</td><td>'.HTML::datebox('fsm_pe_'.$row['id'], $row['planned_end_date']).'</td></tr>';
				if($row['fs_eng'] != '')
					echo '<tr><td>FS Eng:</td><td class="text-muted">' . $row['fs_eng'] . '</td><td>'.HTML::datebox('fse_start_'.$row['id'], $row['start_date']).'</td><td>'.HTML::datebox('fse_pe_'.$row['id'], $row['planned_end_date']).'</td></tr>';
				if($row['fs_ict'] != '')
					echo '<tr><td>FS ICT:</td><td class="text-muted">' . $row['fs_ict'] . '</td><td>'.HTML::datebox('fsi_start_'.$row['id'], $row['start_date']).'</td><td>'.HTML::datebox('fsi_pe_'.$row['id'], $row['planned_end_date']).'</td></tr>';
				if($row['other_qual'] != '')
					echo '<tr><td>Other Qualification:</td><td class="text-muted">' . $row['other_qual'] . '</td><td>'.HTML::datebox('oq_start_'.$row['id'], $row['start_date']).'</td><td>'.HTML::datebox('oq_pe_'.$row['id'], $row['planned_end_date']).'</td></tr>';
				echo '</table> ';
				echo '<dl class="dl-horizontal">';
				if($row['ERR'] == '1')
					echo '<dt>ERR:</dt><dd><span class="text-muted">Yes</span></dd>';
				else
					echo '<dt>ERR:</dt><dd><span class="text-muted">No</span></dd>';
				if($row['PLTS'] == '1')
					echo '<dt>PLTS:</dt><dd><span class="text-muted">Yes</span></dd>';
				else
					echo '<dt>PLTS:</dt><dd><span class="text-muted">No</span></dd>';
				echo '</dl>';
				echo '</td>';
				echo '<td><table>';
				$contracts = DAO::getResultset($link, "SELECT id, title, null FROM contracts WHERE contract_year >= '2020' ORDER BY title");
				array_unshift($contracts, array('','Select contract',''));
				$course_groups = DAO::getResultset($link, "SELECT id, title, null FROM groups WHERE courses_id = '{$row['course_id']}' ORDER BY title");
				array_unshift($course_groups, array('','Select course group',''));
				$assessors = DAO::getResultset($link, "SELECT id, CONCAT(firstnames, ' ', surname), null FROM users WHERE users.type = '" . User::TYPE_ASSESSOR . "' ORDER BY firstnames");
				array_unshift($assessors, array('','Select an assessor',''));
				$tutors = DAO::getResultset($link, "SELECT id, CONCAT(firstnames, ' ', surname), null FROM users WHERE users.type = '" . User::TYPE_TUTOR . "' ORDER BY firstnames");
				array_unshift($tutors, array('','Select a tutor',''));
				$app_coordinators = DAO::getResultset($link, "SELECT id, CONCAT(firstnames, ' ', surname), null FROM users WHERE users.type = '" . User::TYPE_APPRENTICE_COORDINATOR . "' ORDER BY firstnames");
				array_unshift($app_coordinators, array('','Select an apprenticeship coordinator',''));
				$employer_contacts = DAO::getResultset($link, "SELECT contact_id, contact_name, null FROM organisation_contact WHERE org_id = '" . $row['employer_id'] . "' AND job_role = '" . User::JOB_ROLE_LINE_MANAGER . "' ORDER BY contact_name");
				array_unshift($employer_contacts, array('','Select an employer contact',''));
				echo '<tr><td>' . HTML::select('contract'.$row['id'], $contracts) . '</td></tr>';
				echo '<tr><td>' . HTML::select('course_group'.$row['id'], $course_groups) . '</td></tr>';
				echo '<tr><td>' . HTML::select('assessor'.$row['id'], $assessors) . '</td></tr>';
				echo '<tr><td>' . HTML::select('tutor'.$row['id'], $tutors) . '</td></tr>';
				echo '<tr><td>' . HTML::select('app_coordinator'.$row['id'], $app_coordinators) . '</td></tr>';
				echo '<tr><td>' . HTML::select('employer_contact'.$row['id'], $employer_contacts) . '</td></tr>';
				echo '</table></td>';
				echo '<td><input class="chkLearnerChoice" type="checkbox" name="learners[]" value="' . $row['id'] . '" /></td>';
				//echo '<td></td>';
				echo '</tr>';
			}
			?>
			<tr><td colspan="4" class="disabled" id="tdEnrolLearners"><span id="btnEnrolLearners" class="btn btn-lg btn-primary pull-right" onclick="enrolLearners();">Create Training Records</span></td></tr>
			</tbody>
		</table>
	</form>
</div>

<div id="loading" title="Creating training records">
	<p>Please wait ...</p>
</div>


<script src="/assets/adminlte/plugins/jQuery/jquery-2.2.3.min.js"></script>
<script src="/assets/adminlte/plugins/jQueryUI/jquery-ui.min.js"></script>
<script src="/assets/adminlte/bootstrap/js/bootstrap.min.js"></script>
<script src="/assets/adminlte/dist/js/app.min.js"></script>
<script src="/common.js?n=<?php echo time(); ?>" type="text/javascript"></script>
<script src="/assets/adminlte/plugins/iCheck/icheck.min.js"></script>

<script language="JavaScript">

	function enrolLearners()
	{
		var selectedLearners = [];
		var data = [];

		$("input[name='learners[]']").each( function () {
			if(this.checked)
				selectedLearners.push(this.value);
		});
		if(selectedLearners.length == 0)
		{
			alert('Please select the learner(s)');
			return false;
		}
		for(var i = 0; i < selectedLearners.length; i++)
		{
			if($('#contract'+selectedLearners[i]).val() == '')
			{
				alert('Please select contract for all your selected learners');
				return;
			}

			var quals = [];
			quals.push({
				tc_start: $('#input_tc_start_'+selectedLearners[i]).val(),
				tc_end: $('#input_tc_pe_'+selectedLearners[i]).val(),
				l2_start: $('#input_l2_start_'+selectedLearners[i]).val(),
				l2_end: $('#input_l2_pe_'+selectedLearners[i]).val(),
				ma_start: $('#input_ma_start_'+selectedLearners[i]).val(),
				ma_end: $('#input_ma_pe_'+selectedLearners[i]).val(),
				fsm_start: $('#input_fsm_start_'+selectedLearners[i]).val(),
				fsm_end: $('#input_fsm_pe_'+selectedLearners[i]).val(),
				fse_start: $('#input_fse_start_'+selectedLearners[i]).val(),
				fse_end: $('#input_fse_pe_'+selectedLearners[i]).val(),
				fsi_start: $('#input_fsi_start_'+selectedLearners[i]).val(),
				fsi_end: $('#input_fsi_pe_'+selectedLearners[i]).val(),
				oq_start: $('#input_oq_start_'+selectedLearners[i]).val(),
				oq_end: $('#input_oq_pe_'+selectedLearners[i]).val()
			});

			data.push({
				ob_learner_id: selectedLearners[i],
				contract_id: $('#contract'+selectedLearners[i]).val(),
				group_id: $('#course_group'+selectedLearners[i]).val(),
				assessor_id: $('#assessor'+selectedLearners[i]).val(),
				tutor_id: $('#tutor'+selectedLearners[i]).val(),
				app_coordinator_id: $('#app_coordinator'+selectedLearners[i]).val(),
				crm_contact_id: $('#employer_contact'+selectedLearners[i]).val(),
				quals_dates: quals
			});
		}

		$.ajax({
			url: 'do.php?_action=enrol_ob_learners&subaction=enrol_learners',
			data: {data: JSON.stringify(data)},
			method: 'post',
			beforeSend: function(){
				$("#loading").dialog('open').html("<p><i class=\"fa fa-refresh fa-spin\"></i> creating training records for the learners, please Wait...</p>");
			},
			success: function(data) {
				$('#loading').html("<p class=''>Action completed: your selected learners have been enrolled successfully</p>" + data);
			},
			error: function(data){
				console.log(data, 'error');
				$('#loading').html("<p class=''>Action aborted: </p>" + data.responseText);
			}
		});

		//console.log();
	}

	$(function(){

		$("#loading").dialog({
			autoOpen: false,
			width: 'auto',
			height: 'auto',
			modal: true,
			closeOnEscape: false,
			resizable: false,
			draggable: true,
			buttons: {
				'OK': function() {
					window.location.reload();
				}
			}
		});

		$('.chkLearnerChoice').iCheck({
			checkboxClass: 'icheckbox_flat-red',
			radioClass: 'iradio_flat-red'
		});

		$('input[name="learners[]"]').on('ifChecked', function (event){
			$('#tdEnrolLearners').attr('class', '');
			var len = $("[name='learners[]']:checked").length;
			if(len == 1)
				$('#btnEnrolLearners').html('Create ' + len + ' Training Record');
			else if(len > 1)
				$('#btnEnrolLearners').html('Create ' + len + ' Training Records');
			else
			{
				$('#btnEnrolLearners').html('Create Training Record');
				$('#tdEnrolLearners').attr('class', 'disabled');
			}
		});
		$('input[name="learners[]"]').on('ifUnchecked', function (event) {
			$('#tdEnrolLearners').attr('class', '');
			var len = $("[name='learners[]']:checked").length;
			if(len == 1)
				$('#btnEnrolLearners').html('Create ' + len + ' Training Record');
			else if(len > 1)
				$('#btnEnrolLearners').html('Create ' + len + ' Training Records');
			else
			{
				$('#btnEnrolLearners').html('Create Training Record');
				$('#tdEnrolLearners').attr('class', 'disabled');
			}
		});

	});
</script>

</body>
</html>