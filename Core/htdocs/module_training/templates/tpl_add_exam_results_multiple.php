<!DOCTYPE html>
<head xmlns="http://www.w3.org/1999/html">
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<title>Exam Results</title>
	<link rel="stylesheet" href="module_tracking/css/common.css" type="text/css"/>
	<link rel="stylesheet" href="/assets/adminlte/bootstrap/css/bootstrap.min.css">
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.5.0/css/font-awesome.min.css">
	<link rel="stylesheet" href="/assets/adminlte/plugins/jQueryUI/jquery-ui.css">
	<link rel="stylesheet" href="/assets/adminlte/dist/css/AdminLTE.min.css">
	<link href="/assets/adminlte/plugins/toastr/toastr.min.css" rel="stylesheet">

	<!--[if lt IE 9]>
	<script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
	<script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
	<![endif]-->
	<style>
		.disabled {
			pointer-events: none;
			opacity: 0.4;
		}
		.ui-datepicker .ui-datepicker-title select {
			color: #000;
		}
		.chkLearners {
			transform: scale(1.4);
		}
		.fsProgress {
			display: none;
		}
	</style>

</head>
<body>
<div class="row">
	<div class="col-lg-12">
		<div class="banner">
			<div class="Title" style="margin-left: 6px;">Exam Results</div>
			<div class="ButtonBar">
				<span class="btn btn-sm btn-default" onclick="window.location.href='<?php echo $_SESSION['bc']->getPrevious(); ?>';"><i class="fa fa-arrow-circle-o-left"></i> Cancel</span>
			</div>
			<div class="ActionIconBar">
				<?php if(!$_SESSION['user']->isAdmin()) { ?>
				<input type="checkbox" name="caseload_only"
					<?php echo $_SESSION['caseload_learners_only'] == '1' ? 'checked="checked"' : ''; ?>
					   onclick="updateCaseloadCheck(this);"/> My caseload only &nbsp;
				<?php } ?>
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

<div class="container-fluid">

	<div class="row">
		<div class="col-sm-12">
			<div class="callout callout-info">
				<i class="fa fa-info-circle"></i> Use this screen to input functional skills exam results for the learners.
			</div>
		</div>
	</div>

	<div class="row">
		<div class="col-sm-12">
			<div class="well well-sm">
				<form class="form-vertical" name="frmSelection" role="form" method="get" action="<?php echo $_SERVER['PHP_SELF']; ?>">
					<input type="hidden" name="_action" value="add_exam_results_multiple" />
					<input type="hidden" name="subaction" value="show_learners" />
					<div class="row">
						<div class="col-sm-4">
							<div class="form-group">
								<label for="course_id" class="col-sm-12 control-label">Course / Programme:</label>
								<div class="col-sm-12">
									<?php echo HTML::selectChosen('course_id', $courses_select, $course_id, true); ?>
								</div>
							</div>
						</div>
						<div class="col-sm-4">
							<div class="form-group">
								<label for="group_id" class="col-sm-12 control-label">Group / Cohort:</label>
								<div class="col-sm-12">
									<?php echo HTML::selectChosen('group_id', $groups_select, $group_id, true); ?>
								</div>
							</div>
						</div>
						<div class="col-sm-4">
							<div class="form-group">
								<label for="tg_id" class="col-sm-12 control-label">Training Group:</label>
								<div class="col-sm-12">
									<?php echo HTML::selectChosen('tg_id', $tgs_select, $tg_id, true); ?>
								</div>
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-sm-12">
							<p><br><button type="submit" class="btn btn-sm btn-info pull-right"><i class="fa fa-search"></i> Search</button></p>
						</div>
					</div>
				</form>
			</div>
		</div>
	</div>

	<div class="row">
		<div class="col-sm-12">
			<span class="lead text-bold">New Exam Result Entry</span>
		</div>
		<div class="col-sm-12">
			<hr style="margin-top: 2px;">
			<div id="divLearners">
				<?php
				if($course_id != '' && $group_id != '' && $tg_id != '')
				{
					$course = Course::loadFromDatabase($link, $course_id);
					$framework = Framework::loadFromDatabase($link, $course->framework_id);
					$qualifications_ddl = DAO::getResultset($link, "SELECT REPLACE(id, '/', '') AS id, CONCAT(REPLACE(id, '/', ''), ' - ', internaltitle) FROM framework_qualifications WHERE framework_id = '{$framework->id}'");
					echo '<form name="frmExamResultsMultiple" class="form-horizontal" action="do.php?_action=add_exam_results_multiple" method="post">';
						echo '<input type="hidden" name="subaction" value="save_exam_results" />';
						echo '<input type="hidden" name="course_id" value="'.$course_id.'" />';
						echo '<input type="hidden" name="group_id" value="'.$group_id.'" />';
						echo '<input type="hidden" name="tg_id" value="'.$tg_id.'" />';
						echo '<div class="box box-primary">';
							echo '<div class="box-header with-border"><span class="text-bold">Exam Result Details</span><span id="btnSubmit" class="btn btn-success btn-md pull-right"><i class="fa fa-save"></i> Save</span> </div>';
							echo '<div class="box-body">';
								echo '<div class="row">';
									echo '<div class="col-sm-6">';
										echo '<div class="form-group">';
											echo '<label for="qualification_id" class="col-sm-4 control-label fieldLabel_compulsory">Qualification:</label>';
											echo '<div class="col-sm-8">' . HTML::selectChosen('qualification_id', $qualifications_ddl, '', true, true) . '</div>';
											echo '<input type="hidden" name="qualification_title" value="" />';
										echo '</div>';
									echo '</div> ';
									echo '<div class="col-sm-6">';
										echo '<div class="form-group">';
											echo '<label for="unit_reference" class="col-sm-4 control-label fieldLabel_compulsory">Unit:</label>';
											echo '<div class="col-sm-8">' . HTML::selectChosen('unit_reference', [], '', true, true) . '<span class="text-info">(Units list auto-populates based on selected qualification)</span></div>';
											echo '<input type="hidden" name="unit_title" value="" />';
										echo '</div>';
									echo '</div> ';
								echo '</div> ';
								echo '<div class="row">';
									echo '<div class="col-sm-6">';
										echo '<div class="form-group">';
											echo '<label for="exam_date" class="col-sm-4 control-label fieldLabel_compulsory">Date of Exam:</label>';
											echo '<div class="col-sm-8">' . HTML::datebox('exam_date', '', true) . '</div>';
										echo '</div>';
									echo '</div> ';
									echo '<div class="col-sm-6">';
										echo '<div class="form-group">';
											echo '<label for="attempt_no" class="col-sm-4 control-label fieldLabel_compulsory">Attempt Number:</label>';
											echo '<div class="col-sm-8">' . HTML::selectChosen('attempt_no', $attempts_ddl, '', true, true) . '</div>';
										echo '</div>';
									echo '</div> ';
								echo '</div> ';
								echo '<span class="pull-right"><input type="checkbox" name="ViewCourseLearners_showFSPassStats" value="1" id="chkShowHideFSColumns" />Show FS 1st time Stats</span>';
								echo '<div class="row">';
									echo '<div class="col-sm-12">';
					$caseload = '';
					if($_SESSION['caseload_learners_only'] == 1)
						$caseload = " AND tr.coach = '{$_SESSION['user']->id}' ";
					$sql = <<<SQL
SELECT
	tr.id AS tr_id, tr.gender,
	tr.surname, tr.firstnames, tr.gender, tr.status_code, tr.username,
	(SELECT users.enrollment_no FROM users WHERE users.username = tr.username) AS enrollment_no,
	(SELECT organisations.legal_name FROM organisations WHERE organisations.id = tr.employer_id) AS employer_name,
	(SELECT CONCAT(COALESCE(`address_line_1`,''),' ',COALESCE(`address_line_2`,''),',',COALESCE(`postcode`,'')) FROM locations WHERE id = tr.employer_location_id) AS employer_location,
	(SELECT contracts.title FROM contracts WHERE contracts.id = tr.contract_id) AS contract_title,
	tr.l03, tr.uln,
	(SELECT COUNT(*) FROM exam_results WHERE tr_id = tr.id AND LOWER(qualification_title) LIKE '%math%' AND LOWER(unit_title) LIKE '%level 1%' AND attempt_no = 1 AND LOWER(exam_result) = 'pass') AS maths_l1,
	(SELECT COUNT(*) FROM exam_results WHERE tr_id = tr.id AND LOWER(qualification_title) LIKE '%math%' AND LOWER(unit_title) LIKE '%level 2%' AND attempt_no = 1 AND LOWER(exam_result) = 'pass') AS maths_l2,
	(SELECT COUNT(*) FROM exam_results WHERE tr_id = tr.id AND LOWER(qualification_title) LIKE '%english%' AND LOWER(qualification_title) LIKE '%level 1%' AND LOWER(unit_title) LIKE '%read%' AND attempt_no = 1 AND LOWER(exam_result) = 'pass') AS eng_l1_read,
	(SELECT COUNT(*) FROM exam_results WHERE tr_id = tr.id AND LOWER(qualification_title) LIKE '%english%' AND LOWER(qualification_title) LIKE '%level 1%' AND LOWER(unit_title) LIKE '%writ%' AND attempt_no = 1 AND LOWER(exam_result) = 'pass') AS eng_l1_write,
	(SELECT COUNT(*) FROM exam_results WHERE tr_id = tr.id AND LOWER(qualification_title) LIKE '%english%' AND LOWER(qualification_title) LIKE '%level 1%' AND LOWER(unit_title) LIKE '%speak%' AND attempt_no = 1 AND LOWER(exam_result) = 'pass') AS eng_l1_speak,
	(SELECT COUNT(*) FROM exam_results WHERE tr_id = tr.id AND LOWER(qualification_title) LIKE '%english%' AND LOWER(qualification_title) LIKE '%level 2%' AND LOWER(unit_title) LIKE '%read%' AND attempt_no = 1 AND LOWER(exam_result) = 'pass') AS eng_l2_read,
	(SELECT COUNT(*) FROM exam_results WHERE tr_id = tr.id AND LOWER(qualification_title) LIKE '%english%' AND LOWER(qualification_title) LIKE '%level 2%' AND LOWER(unit_title) LIKE '%writ%' AND attempt_no = 1 AND LOWER(exam_result) = 'pass') AS eng_l2_write,
	(SELECT COUNT(*) FROM exam_results WHERE tr_id = tr.id AND LOWER(qualification_title) LIKE '%english%' AND LOWER(qualification_title) LIKE '%level 2%' AND LOWER(unit_title) LIKE '%speak%' AND attempt_no = 1 AND LOWER(exam_result) = 'pass') AS eng_l2_speak
FROM
	tr
WHERE
	tr.tg_id = '$tg_id' $caseload
ORDER BY
	tr.surname
;
SQL;
					$st = DAO::query($link, $sql);
					if($st)
					{
						$attempts_ddl = [];
						for($i = 1; $i <= 15; $i++)
						{
							$attempts_ddl[] = [$i, $i];
						}
						echo '<div class="table-responsive"><table id="tblTGLearners" class="table table-bordered">';
						echo '<thead><tr><th>&nbsp;</th>';
						echo '<th>Gender</th><th>Learner Name</th><th>Organisation</th><th class="bg-green">Result</th>';
						echo '<th class="fsProgress">Maths L1</th>';
						echo '<th class="fsProgress">Maths L2</th>';
						echo '<th class="fsProgress">Eng. R L1</th>';
						echo '<th class="fsProgress">Eng. W L1</th>';
						echo '<th class="fsProgress">Eng. S L1</th>';
						echo '<th class="fsProgress">Eng. R L2</th>';
						echo '<th class="fsProgress">Eng. W L2</th>';
						echo '<th class="fsProgress">Eng. S L2</th>';
						echo '</thead><tbody>';
						while($row = $st->fetch())
						{
							echo '<tr style="background-color: orange;">';
							echo '<td align="center"><input class="chkLearners" type="checkbox" name="learnersTRIDs[]" checked="checked" onclick="learnersSelection_onclick(this);" value="' . $row['tr_id'] . '" /></td>';
							echo '<td>';
							if($row['gender'] == 'M')
								echo '<a href="do.php?_action=read_user&username=' . $row['username'] . '"><img src="/images/boy-blonde-hair.gif" border="0" /></a>';
							else
								echo '<a href="do.php?_action=read_user&username=' . $row['username'] . '"><img src="/images/girl-black-hair.gif" border="0" /></a>';
							echo '<i class="pull-right fa fa-info-circle fa-lg" style="cursor: pointer;" onclick="showSavedExamInfo('.$row['tr_id'].');"></i>';
							echo '</td>';
							echo '<td>';
							echo HTML::cell($row['surname'] . ', ' . $row['firstnames']);
							echo '</td>';
							echo '<td>' . HTML::cell($row['employer_name']) . '<br> &nbsp; <span class="small"><i class="fa fa-map-marker"></i> ' . HTML::cell($row['employer_location']) . '</span>' . '</td>';
							echo '<td>' . HTML::selectChosen('exam_result_'.$row['tr_id'], $exam_results_select, '', false, true) . '</td>';
							echo $row['maths_l1'] > 0 ? '<td class="fsProgress" align="center"><i style="color: green" class="fa fa-check fa-2x"></i></td>' : '<td class="fsProgress"></td>';
							echo $row['maths_l2'] > 0 ? '<td class="fsProgress" align="center"><i style="color: green" class="fa fa-check fa-2x"></i></td>' : '<td class="fsProgress"></td>';
							echo $row['eng_l1_read'] > 0 ? '<td class="fsProgress" align="center"><i style="color: green" class="fa fa-check fa-2x"></i></td>' : '<td class="fsProgress"></td>';
							echo $row['eng_l1_write'] > 0 ? '<td class="fsProgress" align="center"><i style="color: green" class="fa fa-check fa-2x"></i></td>' : '<td class="fsProgress"></td>';
							echo $row['eng_l1_speak'] > 0 ? '<td class="fsProgress" align="center"><i style="color: green" class="fa fa-check fa-2x"></i></td>' : '<td class="fsProgress"></td>';
							echo $row['eng_l2_read'] > 0 ? '<td class="fsProgress" align="center"><i style="color: green" class="fa fa-check fa-2x"></i></td>' : '<td class="fsProgress"></td>';
							echo $row['eng_l2_write'] > 0 ? '<td class="fsProgress" align="center"><i style="color: green" class="fa fa-check fa-2x"></i></td>' : '<td class="fsProgress"></td>';
							echo $row['eng_l2_speak'] > 0 ? '<td class="fsProgress" align="center"><i style="color: green" class="fa fa-check fa-2x"></i></td>' : '<td class="fsProgress"></td>';
							echo '</tr>';
						}
						echo '</tbody></table></div>';
					}
					else
					{
						throw new DatabaseException($link, $sql);
					}

					echo '</div> ';
								echo '</div> ';
							echo '</div>';
						echo '</div> ';
					echo '</form>';
				}
				else
				{
					echo '<i>Select course, group and training group. Click Search to bring the learners.</i>';
				}
				?>
			</div>
		</div>
	</div>

</div> <!--container-fluid-->

<script src="/assets/adminlte/plugins/jQuery/jquery-2.2.3.min.js"></script>
<script src="/assets/adminlte/plugins/jQueryUI/jquery-ui.min.js"></script>
<script src="/assets/adminlte/bootstrap/js/bootstrap.min.js"></script>
<script src="/assets/adminlte/dist/js/app.min.js"></script>
<script src="/common.js" type="text/javascript"></script>
<script src="/assets/adminlte/plugins/toastr/toastr.min.js"></script>


<script type="text/javascript">
	$(function() {

		$('.datepicker').attr('class', 'form-control');

	});

	function course_id_onchange(course)
	{
		$.ajax({
			type:'GET',
			url:'do.php?_action=add_exam_results_multiple&subaction=load_groups',
			data: {course_id: course.value} ,
			beforeSend: function() {
				$('#group_id')
					.find('option')
					.remove()
					.end()
					.append('<option value="">Loading groups/cohorts</option>')
					.val('')
				;
				$('#group_id').attr('disabled', true);
				$('#tg_id')
					.find('option')
					.remove()
					.end()
					.append('<option value="">Select group/cohort</option>')
					.val('')
				;
				$('#tg_id').attr('disabled', true);
			},
			success:function(html){
				$('#group_id').html(html);
				$('#group_id').attr('disabled', false);
			},
			error:function(msg){
				alert('Error: Please contact Sunesis Support with the screenshot.\r\n'+msg);
				console.log(msg);
			}
		});
	}

	function group_id_onchange(group)
	{
		$.ajax({
			type:'GET',
			url:'do.php?_action=add_exam_results_multiple&subaction=load_training_groups',
			data: {group_id: group.value} ,
			beforeSend: function() {
				$('#tg_id')
					.find('option')
					.remove()
					.end()
					.append('<option value="">Loading training groups</option>')
					.val('')
				;
				$('#tg_id').attr('disabled', true);
			},
			success:function(html){
				$('#tg_id').html(html);
				$('#tg_id').attr('disabled', false);
			},
			error:function(msg){
				alert('Error: Please contact Sunesis Support with the screenshot.\r\n'+msg);
				console.log(msg);
			}
		});
	}

	function qualification_id_onchange(qualification)
	{
		if(qualification.value == '')
			return;
		var framework_id = '<?php echo isset($course_id) ? DAO::getSingleValue($link, "SELECT framework_id FROM courses WHERE id = '{$course_id}'") : ''; ?>';
		$.ajax({
			type:'GET',
			url:'do.php?_action=add_exam_results_multiple&subaction=get_qualification_units',
			data: {qualification_id: qualification.value, framework_id: framework_id} ,
			beforeSend: function() {
				$('#unit_reference')
					.find('option')
					.remove()
					.end()
					.append('<option value="">Loading qualification units</option>')
					.val('')
				;
				$('#unit_reference').attr('disabled', true);
			},
			success:function(html){
				$('#unit_reference').html(html);
				$('#unit_reference').attr('disabled', false);
			},
			error:function(msg){
				alert('Something went wrong. Please try again.');
			}
		});
	}

	function learnersSelection_onclick(element)
	{
		var row = element.parentNode.parentNode;

		if(element.checked == true)
		{
			row.style.backgroundColor = 'orange';
		}
		else
		{
			row.style.backgroundColor = '';
		}
	}

	$('#btnSubmit').on('click', function(){
		var myForm = document.forms["frmExamResultsMultiple"];
		if(!validateForm(myForm))
		{
			return false;
		}
		if($('input[type=checkbox][class=chkLearners]:checked').length == 0)
		{
			return alert('Please select at least one learner.');
		}
		var qualification_id = myForm.elements['qualification_id'];
		myForm.elements["qualification_title"].value = qualification_id.options[qualification_id.selectedIndex].text;
		var unit_reference = myForm.elements['unit_reference'];
		myForm.elements["unit_title"].value = unit_reference.options[unit_reference.selectedIndex].text;

		myForm.submit();
	});

	$('#chkShowHideFSColumns').on('change', function(e){
		e.preventDefault();
		$('.fsProgress').hide();
		if(this.checked)
			$('.fsProgress').show();
	});

	function showSavedExamInfo(tr_id)
	{
		if(tr_id == '')
			return;

		var postData = 'do.php?_action=add_exam_results_multiple&subaction=showSavedExamInfo'
				+ '&tr_id=' + encodeURIComponent(tr_id)
			;

		var req = ajaxRequest(postData);
		$("<div></div>").html(req.responseText).dialog({
			id: "dlg_lrs_result",
			title: "Saved Comments",
			resizable: false,
			modal: true,
			width: 750,
			height: 500,

			buttons: {
				'Close': function() {$(this).dialog('close');}
			}
		});
	}
	function updateCaseloadCheck(checkbox)
	{
		console.log(checkbox.checked);
		var state = 0;
		if(checkbox.checked)
			state = 1;
		$.get("do.php?_action=ajax_module_training&subaction=update_caseload_check",
			{ 'state' : state })
			.done(function (d) {
				window.location.reload();
			})
			.fail(function () {
				window.location.reload();
			});
	}
</script>

</body>
</html>
