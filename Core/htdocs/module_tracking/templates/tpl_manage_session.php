<?php /* @var $session OperationsSession */ ?>
<!DOCTYPE html>
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<title>Sunesis - Manage Event</title>
	<link rel="stylesheet" href="module_tracking/css/common.css" type="text/css"/>
	<link rel="stylesheet" href="/assets/adminlte/bootstrap/css/bootstrap.min.css">
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.5.0/css/font-awesome.min.css">
	<link rel="stylesheet" href="/assets/adminlte/plugins/jQueryUI/jquery-ui.css">
	<link rel="stylesheet" href="/assets/adminlte/dist/css/AdminLTE.min.css">
	<link rel="stylesheet" href="/assets/adminlte/plugins/iCheck/all.css">
	<link href="/assets/adminlte/plugins/datatables/jquery.dataTables.css" rel="stylesheet">

	<!--[if lt IE 9]>
	<script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
	<script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
	<![endif]-->

	<style>
		.ui-datepicker .ui-datepicker-title select {
			color: #000;
		}
	</style>

	<script src="/assets/adminlte/plugins/jQuery/jquery-2.2.3.min.js"></script>
</head>
<body>
<div class="row">
	<div class="col-lg-12">
		<div class="banner">
			<div class="Title" style="margin-left: 6px;">Manage Event [<?php echo $session->unit_ref; ?>]</div>
			<div class="ButtonBar">
				<span class="btn btn-sm btn-default" onclick="window.location.href='<?php echo $_SESSION['bc']->getPrevious(); ?>';"><i class="fa fa-arrow-circle-o-left"></i> Cancel</span>
				<?php if(SOURCE_BLYTHE_VALLEY || $_SESSION['user']->op_access == 'W'){?>
				<span class="btn btn-sm btn-default" onclick="window.location.href='do.php?_action=edit_op_session&id=<?php echo $session->id; ?>';"><i class="fa fa-edit"></i> Edit</span>
				<?php } ?>
				<?php if((SOURCE_BLYTHE_VALLEY || $_SESSION['user']->op_access == 'W') && $session->isSafeToDelete($link)){?>
				<span class="btn btn-sm btn-default" onclick="deleteSession('<?php echo $session->id; ?>');"><i class="fa fa-trash"></i> Delete</span>
				<?php } ?>
			</div>
			<div class="ActionIconBar"></div>
		</div>
	</div>
</div>

<div class="row">
	<div class="col-lg-12">
		<?php $_SESSION['bc']->render($link); ?>
	</div>
</div>

<p></p>

<div class="container-fluid">
<div class="row">
	<div class="col-sm-12 well">
		<div align="center">
			<div class="row small">
				<div class="col-sm-2"><span class="text-bold">Type: </span><?php echo $session->getEventTypeDescription(); ?></div>
				<div class="col-sm-2"><span class="text-bold">Trainer: </span><?php echo $session->getPersonnelName($link); ?></div>
				<div class="col-sm-2"><span class="text-bold">Start Date Time: </span><?php echo Date::toShort($session->start_date) . ' (' . $session->start_time . ')'; ?></div>
				<div class="col-sm-2"><span class="text-bold">End Date Time: </span><?php echo Date::toShort($session->end_date) . ' (' . $session->end_time . ')'; ?></div>
				<div class="col-sm-2"><span class="text-bold">Created By: </span><?php echo $session->getCreatedBy($link); ?></div>
			</div>
			<div class="row small">
				<div class="col-sm-2"><span class="text-bold">Unit Reference: </span><?php echo $session->unit_ref; ?></div>
				<div class="col-sm-2"><span class="text-bold">Max Learners Allowed: </span><?php echo $session->max_learners; ?></div>
				<div class="col-sm-2"><span class="text-bold">Location: </span><?php echo htmlspecialchars((string)$session->location); ?></div>
				<div class="col-sm-2"><span class="text-bold">Test Location: </span><?php echo htmlspecialchars((string)$session->test_location); ?></div>
			</div>
		</div>
	</div>
</div>

<div class="row">
<div class="col-sm-12">
<div class="nav-tabs-custom">
<ul class="nav nav-tabs">
	<li class="<?php echo $tab1; ?>"><a class="tabHyperlink" href="#tab1" data-toggle="tab">General</a></li>
	<li class="<?php echo $tab2; ?>"><a class="tabHyperlink" href="#tab2" data-toggle="tab">Add/Remove Learners</a></li>
	<!--<li class="<?php /*echo $tab3; */?>"><a class="tabHyperlink" href="#tab3" data-toggle="tab">Register</a></li>-->

</ul>
<div class="tab-content">
<div class="<?php echo $tab1; ?> tab-pane" id="tab1">
	<div class="box box-success">
		<div class="box-header with-border">
			<div class="col-sm-4"><h2 class="box-title">Learners</h2></div>
			<div class="col-sm-4">Spaces available: <span class="label label-info"><?php echo (int)$session->max_learners - count($session->entries); ?></span></div>
			<div class="col-sm-4">Learners attending: <span class="label label-info"><?php echo count($session->entries); ?></span></div>
		</div>
		<div class="box-body">
			<?php
			if(count($session->entries) == 0)
			{
				echo '<div class="callout callout-danger"><i class="fa fa-info-circle"></i> No learner attached to this event yet</div> ';
			}
			else
			{
				?>
				<table class="table row-border" cellspacing="0" width="100%" id="tblTab1">
				<thead><tr><th></th><th>Firstnames</th><th>Surname</th><th>Programme</th><th>Company</th><th>Work Email</th><th>Home Email</th><th>Mobile</th>
				<?php if($session->isExam()){echo '<th>Select Unit/Exam</th>';}?>
				</tr></thead>
				<tbody>
				<?php

				$ddlUnits = array();
				foreach(explode(',', $session->unit_ref) AS $_v)
					$ddlUnits[] = array($_v, $_v);

				foreach($session->entries AS $e)
				{
					echo '<tr>';
					$tr = TrainingRecord::loadFromDatabase($link, $e['entry_tr_id']);
					if($tr->gender == 'M')
						echo '<td><a href="do.php?_action=read_training_record&id=' . $tr->id . '"><img class="img-circle" src="/images/boy-blonde-hair.gif" /></a></td>';
					elseif($tr->gender == 'F')
						echo '<td><a href="do.php?_action=read_training_record&id=' . $tr->id . '"><img src="/images/girl-black-hair.gif" /></a></td>';
					else
						echo '<td><a href="do.php?_action=read_training_record&id=' . $tr->id . '"><img src="/images/blue-person.gif" /></a></td>';
					echo '<td>' . $tr->firstnames . '</td>';
					echo '<td>' . $tr->surname . '</td>';
					echo '<td>' . $tr->getCourseTitle($link) . '</td>';
					echo '<td>' . $tr->legal_name . '</td>';
					echo '<td>' . $tr->work_email . '</td>';
					echo '<td>' . $tr->home_email . '</td>';
					echo '<td>' . $tr->work_mobile . '</td>';
					echo $session->isExam() ? '<td id="session_entries_id'.$e['entry_id'].'">' . HTML::select('exam_name', $ddlUnits, $e['entry_exam_name'], true) . '</td>' : '';
					echo '</tr>';
					unset($tr);
				}
			}
			?>
		</tbody>
		</table>
		</div>
	</div>
</div>
<div class="<?php echo $tab2; ?> tab-pane" id="tab2">
	<div class="box box-success">
		<div class="box-header with-border">
			<div class="row">
				<div class="col-sm-4"><h2 class="box-title">Add/Remove Learners</h2></div>
				<div class="col-sm-4">Spaces available: <span class="label label-info" id="txtSpacesAvailable"><?php echo (int)$session->max_learners - (int)count($session->entries); ?></span></div>
			</div>
			<div class="row">
				<div class="col-sm-12">
					<div class="bg-gray pad">
						<form class="form-inline" name="frmTab2Filters" id="frmTab2Filters" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="get">
							<input type="hidden" name="_action" value="manage_session" />
							<input type="hidden" name="id" value="<?php echo $session->id; ?>" />
							<input type="hidden" name="selected_tab" value="<?php echo $selected_tab; ?>" />
							<div class="form-group">
								Induction Date between: &nbsp; <input type="text" class="datepicker" id="tab2FilterIndDateFrom" name="tab2FilterIndDateFrom" value="<?php echo $tab2FilterIndDateFrom; ?>" size="10" maxlength="10" placeholder="dd/mm/yyyy" /> &nbsp;
								and: &nbsp; <input type="text" class="datepicker" id="tab2FilterIndDateTo" name="tab2FilterIndDateTo" value="<?php echo $tab2FilterIndDateTo; ?>" size="10" maxlength="10" placeholder="dd/mm/yyyy" /> &nbsp;
								<span class="btn btn-sm btn-info" onclick="document.forms['frmTab2Filters'].submit();"><i class="fa fa-filter"></i> Apply Filter</span>
							</div>
						</form>
					</div>
				</div>
			</div>
		</div>
		<div class="box-body">
			<form name="frmAddRemoveLearners" id="frmAddRemoveLearners" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
				<input type="hidden" name="_action" value="save_op_session_learners" />
				<input type="hidden" name="id" value="<?php echo $session->id; ?>" />
				<table class="table row-border small" cellspacing="0" width="100%" id="tblTab2">
					<thead><tr><th></th><th></th><th>Firstnames</th><th>Surname</th><th>Programme</th><th>Company</th><th>Company Email(s)</th><th>Work Email</th><th>Home Email</th><th>Mobile</th></tr></thead>
					<tbody>
					<?php
					$_frameworks = DAO::getSingleColumn($link, "SELECT op_tracker_frameworks.framework_id FROM op_tracker_frameworks WHERE op_tracker_frameworks.tracker_id IN ({$session->tracker_id})");
					$_frameworks_to_unset = array();
					$_frameworks_to_keep = array();
					$_unit_refs = explode(',', $session->unit_ref);
					foreach($_unit_refs AS $_u_ref)
					{
						foreach($_frameworks AS $_f_id)
						{
							$_chk = DAO::getSingleValue($link, "SELECT extractvalue(evidences, '//unit[@op_title=\"".addslashes((string)$_u_ref)."\" and @track=\"true\"]/@title') AS chk FROM  framework_qualifications WHERE framework_id = '{$_f_id}' HAVING chk != '';");
							if($_chk != '' && !in_array($_f_id, $_frameworks_to_keep))
								$_frameworks_to_keep[] = $_f_id ;
						}
					}

					$_frameworks = $_frameworks_to_keep;

					$_frameworks = implode(',', $_frameworks);
					if($_frameworks == '')
					{
						echo '<div class="callout callout-danger"><i class="fa fa-info-circle"></i> No learner found </div> ';
					}
					else
					{
						$induction_clause = '';
						if($tab2FilterIndDateFrom != '' && $tab2FilterIndDateTo != '')
							$induction_clause = ' AND induction.induction_date BETWEEN "' . Date::toMySQL($tab2FilterIndDateFrom) . '" AND "' . Date::toMySQL($tab2FilterIndDateTo) . '"';
						elseif($tab2FilterIndDateFrom != '' && $tab2FilterIndDateTo == '')
							$induction_clause = ' AND induction.induction_date >= "' . Date::toMySQL($tab2FilterIndDateFrom) . '"';
						elseif($tab2FilterIndDateFrom == '' && $tab2FilterIndDateTo != '')
							$induction_clause = ' AND induction.induction_date <= "' . Date::toMySQL($tab2FilterIndDateTo) . '"';

						$sql = <<<SQL
SELECT DISTINCT
	tr_id, tr.firstnames, tr.surname, tr.work_email, tr.home_email, tr.work_mobile, legal_name, tr.gender,
	(SELECT courses.title FROM courses INNER JOIN courses_tr ON courses.id = courses_tr.course_id WHERE courses_tr.tr_id = tr.id) AS programme
FROM
    student_frameworks
    LEFT JOIN tr ON student_frameworks.tr_id = tr.id
    #LEFT JOIN framework_qualifications ON (framework_qualifications.framework_id = student_frameworks.id AND LOCATE('$session->unit_ref', evidences) > 0 )
	LEFT JOIN organisations ON tr.employer_id = organisations.id
	LEFT JOIN inductees ON tr.`username` = inductees.`sunesis_username`
  	LEFT JOIN induction ON inductees.`id` = induction.`inductee_id`
WHERE
	student_frameworks.id IN ($_frameworks)  AND tr_id IS NOT NULL AND tr.status_code = '1'
	$induction_clause
SQL;


						$result = DAO::getResultset($link, $sql, DAO::FETCH_ASSOC);
						if(count($result) == 0)
						{
							echo '<div class="callout callout-danger"><i class="fa fa-info-circle"></i> No learner found </div> ';
						}
						else
						{
							foreach($result AS $row)
							{
								$contact_emails = '';
								$main_contact_ids = DAO::getSingleValue($link, "SELECT main_contact_id FROM tr_operations WHERE tr_id = '{$row['tr_id']}'");
								if($main_contact_ids != '')
								{
									$main_contact_ids = explode(',', $main_contact_ids);
									foreach($main_contact_ids AS $contact_id)
										$contact_emails .= DAO::getSingleValue($link, "SELECT contact_email FROM organisation_contact WHERE contact_id = '{$contact_id}'") . '<br>';
								}
								$found = DAO::getSingleValue($link, "SELECT COUNT(*) FROM session_entries WHERE entry_session_id = '{$session->id}' AND entry_tr_id = '{$row['tr_id']}'");
								$checked = $found == '0' ? '' : ' checked="checked" ';
								echo '<tr>';
								if(SOURCE_BLYTHE_VALLEY || $_SESSION['user']->op_access == 'W')
								{
									if($found == 0)
										echo '<td><span onclick="$(this).css(\'pointer-events\', \'none\');addLearner(\''.$row['tr_id'].'\');" class="btn btn-xs btn-primary" title="Add this learner"><i class="fa fa-plus"></i></span> </td>';
									else
										echo '<td><span onclick="removeLearner(\''.$row['tr_id'].'\');" class="btn btn-xs btn-danger" title="Remove this learner"><i class="fa fa-remove"></i></span> </td>';
								}
								else
								{
									echo '<td></td>';
								}
								if($row['gender'] == 'M')
									echo '<td><a href="do.php?_action=read_training_record&id=' . $row['tr_id'] . '"><img class="img-circle" src="/images/boy-blonde-hair.gif" /></a></td>';
								elseif($row['gender'] == 'F')
									echo '<td><a href="do.php?_action=read_training_record&id=' . $row['tr_id'] . '"><img src="/images/girl-black-hair.gif" /></a></td>';
								else
									echo '<td><a href="do.php?_action=read_training_record&id=' . $row['tr_id'] . '"><img src="/images/blue-person.gif" /></a></td>';
								echo '<td>' . $row['firstnames'] . '</td>';
								echo '<td>' . $row['surname'] . '</td>';
								echo '<td>' . $row['programme'] . '</td>';
								echo '<td>' . $row['legal_name'] . '</td>';
								echo '<td>' . $contact_emails . '</td>';
								echo '<td>' . $row['work_email'] . '</td>';
								echo '<td>' . $row['home_email'] . '</td>';
								echo '<td>' . $row['work_mobile'] . '</td>';
								echo '</tr>';
							}
						}
					}

					?>
					</tbody>
				</table>
			</form>
		</div>
	</div>
</div>
<!--<div class="<?php /*echo $tab3; */?> tab-pane" id="tab3">
						<div class="box box-success">
							<div class="box-header with-border">
								<div class="col-sm-4"><h2 class="box-title">Event Register</h2></div>
								<?php /*if(SOURCE_BLYTHE_VALLEY || $_SESSION['user']->op_access == 'W') {*/?>
								<div class="col-sm-8"><span class="btn btn-sm btn-primary pull-right" id="btnSaveSessionRegister"><i class="fa fa-save"></i> Save Register</span></div>
								<?php /*} */?>
							</div>
							<div class="box-body table-responsive no-padding">
								<?php
/*								if(count($session->entries) == 0)
								{
									echo '<div class="callout callout-danger"><i class="fa fa-info-circle"></i> No learner attached to this event yet</div> ';
								}
								else
								{
								*/?>
								<form name="frmSessionRegister" id="frmSessionRegister" action="/do.php" method="post" >
									<input type="hidden" name="_action" value="save_op_session_register" />
									<input type="hidden" name="session_id" value="<?php /*echo $session->id; */?>" />
									<?php /*echo $session->getRegister($link); */?>
								</form>
								<?php
/*								}
								*/?>
							</div>
						</div>
					</div>-->

</div>
</div>
</div>
</div>
</div>


<script src="/assets/adminlte/plugins/jQueryUI/jquery-ui.min.js"></script>
<script src="/assets/adminlte/bootstrap/js/bootstrap.min.js"></script>
<script src="/assets/adminlte/dist/js/app.min.js"></script>
<script src="/common.js" type="text/javascript"></script>
<script src="/assets/adminlte/plugins/iCheck/icheck.min.js"></script>
<script src="/assets/adminlte/plugins/datatables/jquery.dataTables.min.js"></script>

<script language="JavaScript">
	var phpMaxLearners = '<?php echo $session->max_learners; ?>';
	var phpSessionEntries = '<?php echo count($session->entries); ?>';

	function addLearner(tr_id)
	{
		if(parseInt(window.phpMaxLearners) - parseInt(window.phpSessionEntries) <= 0)
		{
			alert('There is no space available.');
			return;
		}

		$.ajax({
			type:'POST',
			url:'do.php?_action=ajax_tracking&subaction=addLearnerToSession&session_id=<?php echo $session->id; ?>&tr_id='+tr_id,
			success: function(data, textStatus, xhr) {
				//alert('Learner is added to the session');
				window.location.reload();
			},
			error: function(data, textStatus, xhr){
				console.log(data.responseText);
			}
		});
	}

	function removeLearner(tr_id)
	{
		$.ajax({
			type:'POST',
			url:'do.php?_action=ajax_tracking&subaction=removeLearnerFromSession&session_id=<?php echo $session->id; ?>&tr_id='+tr_id,
			success: function(data, textStatus, xhr) {
				alert('Learner is removed from this event');
				window.location.reload();
			},
			error: function(data, textStatus, xhr){
				console.log(data.responseText);
			}
		});
	}

	$('.tabHyperlink').click(function(){
		var selected_tab = $(this).attr('href');
		selected_tab = selected_tab.replace('#', '');
		var client = ajaxRequest('do.php?_action=ajax_tracking&subaction=saveOpSessionTabInSession&selected_tab='+selected_tab);
	});

	$("input[type=radio]").on('ifChecked', function(event){
		var totalAttended = 0;
		var totalLate = 0;
		var totalVeryLate = 0;
		var totalAbsent = 0;

		$("input[name^='AttendanceStatus']").each(function(i, obj) {
			if(obj.checked)
			{
				//console.log(obj.name, obj.value);
				if(obj.value == "AT")
				{
					totalAttended++;
				}
				else if(obj.value == "LA")
				{
					totalLate++;
				}
				else if(obj.value == "VL")
				{
					totalVeryLate++;
				}
				else if(obj.value == "AB")
				{
					totalAbsent++;
				}
			}

		});

		$("#txtAttended, #txtLate, #txtVeryLate, #txtAbsent").html('0');
		$("#txtAttended").html(totalAttended);
		$("#txtLate").html(totalLate);
		$("#txtVeryLate").html(totalVeryLate);
		$("#txtAbsent").html(totalAbsent);
	});

	$("#btnSaveSessionRegister").click(function(){
		var frmSessionRegister = document.forms["frmSessionRegister"];
		frmSessionRegister.submit();
		/*
		  $.ajax({
			  type: "POST",
			  url: "do.php?_action=ajax_tracking&subaction=saveOPSessionRegister",
			  data: $('#frmSessionRegister').serialize(),
			  //dataType: 'json',
			  success: function(booking){
				  console.log(booking);
			  },
			  error: function(msg){
				  console.log(msg);
				  alert('Something went wrong, operation aborted. Please start again.');
			  }
		  });
  */
	});

	$(function(){
		$('#tblTab2, #tblTab1').DataTable({
			"paging": false,
			"lengthChange": false,
			"searching": true,
			"ordering": true,
			"info": false,
			"autoWidth": true
		});
	});

	$('select[name="exam_name"]').on('change', function(){
		if(this.value == '')
			return;

		var id = $(this).parent().attr('id');
		id = id.replace('session_entries_id', '');

		check_unit_ref_applicable_for_learner(id, this.value);
	});

	function check_unit_ref_applicable_for_learner(id, value)
	{
		$.ajax({
			type:'GET',
			async: false,
			url:'do.php?_action=ajax_tracking&subaction=check_unit_ref_applicable_for_learner&entry_id='+encodeURIComponent(id)+'&unit_ref='+encodeURIComponent(value),
			success: function(response) {
				if(response == 0)
				{
					alert('Your selected exam/unit "'+ value +'" is not applicable for this learner.');
					$('#session_entries_id'+id).children()[0].value = '';
				}
			},
			error: function(data, textStatus, xhr){
				console.log(data.responseText);
			}
		});
	}

	function deleteSession(session_id)
	{
		if(!confirm('This action is irreversible, are you sure you want to continue?'))
		{
			return false;
		}

		$.ajax({
			type:'POST',
			url:'do.php?_action=ajax_tracking&subaction=delete_op_session&session_id='+encodeURIComponent(session_id),
			success: function(response) {
				if(response == 'success')
				{
					alert('Session is delete from the system');
					window.location.href='do.php?_action=view_operations_schedule_tabular';
				}
			},
			error: function(data, textStatus, xhr){
				alert(data.responseText);
			}
		});
	}
</script>

</body>
</html>