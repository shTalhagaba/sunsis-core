<!DOCTYPE html>
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<title>Add Learners</title>
	<link rel="stylesheet" href="module_tracking/css/common.css?n=<?php echo time(); ?>" type="text/css"/>
	<link rel="stylesheet" href="/assets/adminlte/bootstrap/css/bootstrap.min.css">
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.5.0/css/font-awesome.min.css">
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/ionicons/2.0.1/css/ionicons.min.css">
	<link rel="stylesheet" href="/assets/adminlte/plugins/jQueryUI/jquery-ui.css">
	<link rel="stylesheet" href="/assets/adminlte/dist/css/AdminLTE.min.css">
	<link href="/assets/adminlte/plugins/datatables/jquery.dataTables.css" rel="stylesheet">
	<link rel="stylesheet" href="/assets/adminlte/plugins/bootstrap-validator/bootstrapValidator.css">
	<link href="https://gitcdn.github.io/bootstrap-toggle/2.2.2/css/bootstrap-toggle.min.css" rel="stylesheet">

	<!--[if lt IE 9]>
	<script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
	<script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
	<![endif]-->


	<style>
		.ui-datepicker .ui-datepicker-title select {
			color: #000;
		}
		input[id^="home_postcode"] {
			text-transform: uppercase;
		}
		input[id^="firstnames"], input[id^="surname"] {
			text-transform: capitalize;
		}
		input[id^="home_email"] {
			text-transform: lowercase;
		}
	</style>
</head>
<body>
<div class="row">
	<div class="col-lg-12">
		<div class="banner">
			<div class="Title" style="margin-left: 6px;">Add Learners</div>
			<div class="ButtonBar">
				<span class="btn btn-sm btn-default" onclick="window.location.href='<?php echo $_SESSION['bc']->getPrevious(); ?>';"><i class="fa fa-arrow-circle-o-left"></i> Cancel</span>
				<span class="btn btn-sm btn-default" onclick="previewInputInformation(); "><i class="fa fa-eye"></i> Preview  </span>
				<span class="btn btn-sm btn-default" onclick="saveFrmAddLearners(); "><i class="fa fa-save"></i> Create </span>
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
	<form class="form-horizontal" name="frmAddLearners" id="frmAddLearners" action="<?php echo $_SERVER['PHP_SELF']; ?>">
		<input type="hidden" name="_action" value="save_ob_learners" />
		<input type="hidden" name="formName" value="frmAddLearners" />
		<table id="tblLearners" class="table row-border">
			<thead>
			<tr>
				<th>First Name(s)</th>
				<th>Surname</th>
				<th>DOB</th>
				<th>NI No.</th>
				<th>Gender</th>
				<th>Postcode</th>
				<th>Personal Email</th>
				<th>Employer/Business</th>
				<th>Job Title</th>
				<th>College</th>
				<th>Start Date</th>
				<th>Planned End Date</th>
				<th>Est. end date of practical period</th>
				<th>Planned off the job hours</th>
				<th>Framework/Standard Title</th>
				<th>Course</th>
				<th>Main Aim (NVQ) / Development Competence Qualification</th>
				<th>Technical Certificate</th>
				<th>Level 2/ Foundation Competence Qualification<br>(if applicable)</th>
				<th>Functional Skills Maths (if applicable)</th>
				<th>Functional Skills English (if applicable)</th>
				<th>Functional Skills ICT (if applicable)</th>
				<th>Other Qual. (if applicable)</th>
				<th>ERR</th>
				<th>PLTS</th>
			</tr>
			</thead>
			<tbody>
			<?php
				for($i = 1; $i <= 5; $i++)
				{
					echo '<tr id="row'.$i.'">';
					echo '<td bgcolor="#f0fff0"><input type="text" name="firstnames'.$i.'" id="firstnames'.$i.'" /></td>';
					echo '<td bgcolor="#f0fff0"><input type="text" name="surname'.$i.'" id="surname'.$i.'" /></td>';
					echo '<td bgcolor="#f0fff0">' . HTML::datebox('dob'.$i, '') . '</td>';
					echo '<td bgcolor="#f0fff0"><input type="text" name="ni'.$i.'" id="ni'.$i.'" /></td>';
					echo '<td bgcolor="#f0fff0">' . HTML::select('gender'.$i, InductionHelper::getDDLGender(), '', true) . '</td>';
					echo '<td bgcolor="#f0fff0"><input type="text" name="home_postcode'.$i.'" id="home_postcode'.$i.'" size="10" /></td>';
					echo '<td bgcolor="#f0fff0"><input type="text" name="home_email'.$i.'" id="home_email'.$i.'" size="35" /></td>';
					echo '<td bgcolor="#f0fff0">' . HTML::select('employer_id'.$i, $ddlEmployers, '', true, false, true, 1, ' style="width: 150px !important; min-width: 50px; max-width: 150px;" ') . '</td>';
					echo '<td bgcolor="#f0fff0">' . HTML::select('job_title'.$i, $ddlJobTitles, '', true, false, true, 1, ' style="width: 150px !important; min-width: 50px; max-width: 150px;" ') . '</td>';
					echo '<td bgcolor="#f0fff0">' . HTML::select('college_id'.$i, $ddlColleges, '', true, false, true, 1, ' style="width: 150px !important; min-width: 50px; max-width: 150px;" ') . '</td>';
					echo '<td bgcolor="#f0fff7">' . HTML::datebox('start_date'.$i, '') . '</td>';
					echo '<td bgcolor="#f0fff7">' . HTML::datebox('planned_end_date'.$i, '') . '</td>';
					echo '<td bgcolor="#f0fff7">' . HTML::datebox('target_date_practical_period'.$i, '') . '</td>';
					echo '<td bgcolor="#f0fff0"><input type="text" name="planned_otj_hours'.$i.'" id="planned_otj_hours'.$i.'" /></td>';
					echo '<td bgcolor="#f0fff7">' . HTML::select('framework_id'.$i, $ddlFrameworks, '', true, false, true, 1, ' style="width: 150px !important; min-width: 50px; max-width: 150px;" ') . '</td>';
					echo '<td bgcolor="#f0fff7">' . HTML::select('course_id'.$i, array(), '', true, false, true, 1, ' style="width: 150px !important; min-width: 50px; max-width: 150px;" ') . '</td>';
					echo '<td bgcolor="#f0fff7">' . HTML::select('main_aim'.$i, array(), '', true, false, true, 1, ' style="width: 150px !important; min-width: 50px; max-width: 150px;" ') . '</td>';
					echo '<td bgcolor="#f0fff7">' . HTML::select('tech_cert'.$i, array(), '', true, false, true, 1, ' style="width: 150px !important; min-width: 50px; max-width: 150px;" ') . '</td>';
					echo '<td bgcolor="#f0fff7">' . HTML::select('l2_found_competence'.$i, array(), '', true, false, true, 1, ' style="width: 150px !important; min-width: 50px; max-width: 150px;" ') . '</td>';
					echo '<td bgcolor="#f0fff7">' . HTML::select('fs_maths'.$i, array(), '', true, false, true, 1, ' style="width: 150px !important; min-width: 50px; max-width: 150px;" ') . '</td>';
					echo '<td bgcolor="#f0fff7">' . HTML::select('fs_eng'.$i, array(), '', true, false, true, 1, ' style="width: 150px !important; min-width: 50px; max-width: 150px;" ') . '</td>';
					echo '<td bgcolor="#f0fff7">' . HTML::select('fs_ict'.$i, array(), '', true, false, true, 1, ' style="width: 150px !important; min-width: 50px; max-width: 150px;" ') . '</td>';
					echo '<td bgcolor="#f0fff7">' . HTML::select('other_qual'.$i, array(), '', true, false, true, 1, ' style="width: 150px !important; min-width: 50px; max-width: 150px;" ') . '</td>';
					echo '<td bgcolor="#f0fff7"><input type="checkbox" name="ERR'.$i.'" id="ERR'.$i.'" data-toggle="toggle" data-on="Yes" data-off="No" data-onstyle="success" data-offstyle="danger" /></td>';
					echo '<td bgcolor="#f0fff7"><input type="checkbox" name="PLTS'.$i.'" id="PLTS'.$i.'" data-toggle="toggle" data-on="Yes" data-off="No" data-onstyle="success" data-offstyle="danger" /></td>';
					echo '</tr>';
				}
			?>
			</tbody>
		</table>
	</form>
</div>

<div id="dialogPreview" title="Preview input information before save" style="font-size: smaller;">
	<p>Please verify your input information.</p>
	<div id="divPreview" class="small"></div>
</div>

<script src="/assets/adminlte/plugins/jQuery/jquery-2.2.3.min.js"></script>
<script src="/assets/adminlte/plugins/jQueryUI/jquery-ui.min.js"></script>
<script src="/assets/adminlte/plugins/datatables/jquery.dataTables.min.js"></script>
<script src="/assets/adminlte/bootstrap/js/bootstrap.min.js"></script>
<script src="/assets/adminlte/dist/js/app.min.js"></script>
<script src="/common.js?n=<?php echo time(); ?>" type="text/javascript"></script>
<script src="/assets/adminlte/plugins/bootstrap-validator/bootstrapValidator.js"></script>
<script src="/assets/adminlte/plugins/fullcalendar/moment.js"></script>
<script src="https://gitcdn.github.io/bootstrap-toggle/2.2.2/js/bootstrap-toggle.min.js"></script>

<script language="JavaScript">
	$('#tblLearners').DataTable({
		"paging": false,
		"lengthChange": false,
		"searching": false,
		"ordering": false,
		"info": false,
		"autoWidth": true
	});

	$(function(){

		$('#frmAddLearners').validate();

		$('[id^="surname"], [id^="home_email"], [id^="employer_id"]').each(function(){
			var row = $(this).closest('tr').attr('id');
			row = row.replace('row', '');
			$(this).rules("add", {
				required:function(element){
					return $('#firstnames'+row).val().trim() != '';
				}

			});
		});

		$('[id^="home_postcode"]').each(function(){
			var row = $(this).closest('tr').attr('id');
			row = row.replace('row', '');
			$(this).rules("add", {
				postcodeUK:function(element){
					return $('#firstnames'+row).val() != '';
				}
			});
		});

		$('[id^="ni"]').each(function(){
			var row = $(this).closest('tr').attr('id');
			row = row.replace('row', '');
			$(this).rules("add", {
				niUK:function(element){
					return $('#firstnames'+row).val() != '';
				}
			});
		});

		$('[id^="home_email"]').each(function(){
			var row = $(this).closest('tr').attr('id');
			row = row.replace('row', '');
			$(this).rules("add", {
				emailCheck:function(element){
					return $('#firstnames'+row).val() != '';
				}
			});
		});

		$('[id^="input_planned_end_date"], [id^="framework_id"], [id^="course_id"]').each(function(){
			var row = $(this).closest('tr').attr('id');
			row = row.replace('row', '');
			$(this).rules("add", {
				required:function(element){
					return $('#firstnames'+row).val() != '' && $('#input_start_date'+row).val() != '';
				}
			});
		});

		jQuery.validator.addMethod("postcodeUK", function(value, element) {
			return this.optional(element) || /^[A-Z]{1,2}[0-9]{1,2} ?[0-9][A-Z]{2}$/i.test(value);
		}, "Please specify a valid Postcode");

		jQuery.validator.addMethod("emailCheck", function(value, element) {
			return this.optional(element) || /^(([^<>()[\]\\.,;:\s@\"]+(\.[^<>()[\]\\.,;:\s@\"]+)*)|(\".+\"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/i.test(value);
		}, "Please specify a valid Email address");

		jQuery.validator.addMethod("niUK", function(value, element) {
			return this.optional(element) || /^\s*[a-zA-Z]{2}(?:\s*\d\s*){6}[a-zA-Z]?\s*$/i.test(value);
		}, "Please specify a valid National Insurance Number");

		$('#dialogPreview').dialog({
			modal: true,
			width: 1150,
			height: 700,
			closeOnEscape: true,
			autoOpen: false,
			resizable: true,
			draggable: true,
			buttons: {
				'Close': function() {$(this).dialog('close');}
			}
		});

	});

	$('select[id^="framework_id"]').change(function(){
		var row = $(this).closest('tr').attr('id');
		row = row.replace('row', '');
		var framework_id = $(this).val();

		loadDDL('get_courses', row, framework_id);
		loadDDL('get_tech_certs', row, framework_id);
		loadDDL('get_l2_found_competences', row, framework_id);
		loadDDL('get_main_aims', row, framework_id);
		loadDDL('get_fs_maths', row, framework_id);
		loadDDL('get_fs_eng', row, framework_id);
		loadDDL('get_fs_ict', row, framework_id);
		loadDDL('get_other_qual', row, framework_id);
	});

	function loadDDL(subaction, row, framework_id)
	{
		var ddl_id = '';
		var ddl_loading_msg = 'Loading ';
		if(subaction == 'get_courses')
		{
			ddl_id = '#course_id'+row;
			ddl_loading_msg += 'courses ...';
		}
		if(subaction == 'get_tech_certs')
		{
			ddl_id = '#tech_cert'+row;
			ddl_loading_msg += 'technical certificates ...';
		}
		else if(subaction == 'get_l2_found_competences')
		{
			subaction = 'get_tech_certs';
			ddl_id = '#l2_found_competence'+row;
			ddl_loading_msg += 'L2 foundation competence ...';
		}
		else if(subaction == 'get_main_aims')
		{
			subaction = 'get_tech_certs';
			ddl_id = '#main_aim'+row;
			ddl_loading_msg += 'main aim ...';
		}
		else if(subaction == 'get_fs_maths')
		{
			subaction = 'get_fs_maths';
			ddl_id = '#fs_maths'+row;
			ddl_loading_msg += 'FS Maths ...';
		}
		else if(subaction == 'get_fs_eng')
		{
			subaction = 'get_fs_eng';
			ddl_id = '#fs_eng'+row;
			ddl_loading_msg += 'FS English ...';
		}
		else if(subaction == 'get_fs_ict')
		{
			subaction = 'get_fs_ict';
			ddl_id = '#fs_ict'+row;
			ddl_loading_msg += 'FS ICT ...';
		}
		else if(subaction == 'get_other_qual')
		{
			subaction = 'get_tech_certs';
			ddl_id = '#other_qual'+row;
			ddl_loading_msg += 'Other qualifications ...';
		}

		$.ajax({
			type:'GET',
			url:'do.php?_action=add_ob_learners&subaction='+subaction,
			data: {framework_id: framework_id} ,
			beforeSend: function() {
				$(ddl_id)
					.find('option')
					.remove()
					.end()
					.append('<option value="">' + ddl_loading_msg + '</option>')
					.val('')
				;
				$(ddl_id).attr('disabled', true);
			},
			success:function(html){
				$(ddl_id).html(html);
				$(ddl_id).attr('disabled', false);
			},
			error:function(msg){
				alert('Error: Please contact Sunesis Support with the screenshot.\r\n'+msg);
				console.log(msg);
			}
		});
	}

	function previewInputInformation()
	{
		if(!$("#frmAddLearners").valid())
			return;

		var html = '<table class="table table-bordered">';
		html += '<thead><tr>';
		html += '<th>First Name(s)</th><th>Surname</th><th>DOB</th><th>NI No.</th><th>Gender</th><th>Postcode</th><th>Personal Email</th><th>Employer/Business</th>';
		html += '<th>College</th><th>Start Date</th><th>Age at Learning Start Date</th><th>Planned End Date</th><th>Framework/Standard Title</th><th>Course</th><th>Main Aim (NVQ) / Development Competence Qualification</th>';
		html += '<th>Technical Certificate</th><th>Level 2/ Foundation Competence Qualification<br>(if applicable)</th>';
		html += '<th>Functional Skills Maths (if applicable)</th><th>Functional Skills English (if applicable)</th><th>Functional Skills ICT (if applicable)</th><th>Other Qual.</th><th>ERR</th><th>PLTS</th>';
		html += '</tr></thead>';
		html += '<tbody>';
		for(var i=1; i<=5; i++)
		{
			if($('#firstnames'+i).val().trim() != '')
			{
				html += '<tr>';
				html += '<td>' + $('#firstnames'+i).val().trim() + '</td>';
				html += '<td>' + $('#surname'+i).val().trim() + '</td>';
				html += '<td>' + $('#input_dob'+i).val().trim() + '</td>';
				html += '<td>' + $('#ni'+i).val().trim() + '</td>';
				html += '<td>' + $('#gender'+i+' option:selected').text() + '</td>';
				html += '<td>' + $('#home_postcode'+i).val().trim() + '</td>';
				html += '<td>' + $('#home_email'+i).val().trim() + '</td>';
				html += '<td>' + $('#employer_id'+i+' option:selected').text() + '</td>';
				html += '<td>' + $('#college_id'+i+' option:selected').text() + '</td>';
				html += '<td>' + $('#input_start_date'+i).val().trim() + '</td>';
				var years = 'n/a';
				if($('#input_start_date'+i).val().trim() != '' && $('#input_dob'+i).val().trim() != '')
				{
					var now = moment($('#input_start_date'+i).val().trim());
					var end = moment($('#input_dob'+i).val().trim());
					var duration = moment.duration(now.diff(end));
					var years = Math.round(duration.asYears());
				}
				html += '<td>' + years + '</td>';
				html += '<td>' + $('#input_planned_end_date'+i).val().trim() + '</td>';
				html += '<td>' + $('#framework_id'+i+' option:selected').text() + '</td>';
				html += '<td>' + $('#course_id'+i+' option:selected').text() + '</td>';
				html += '<td>' + $('#main_aim'+i+' option:selected').text().replace('Select an option', '') + '</td>';
				html += '<td>' + $('#tech_cert'+i+' option:selected').text().replace('Select an option', '') + '</td>';
				html += '<td>' + $('#l2_found_competence'+i+' option:selected').text().replace('Select an option', '') + '</td>';
				html += '<td>' + $('#fs_maths'+i+' option:selected').text().replace('Select an option', '') + '</td>';
				html += '<td>' + $('#fs_eng'+i+' option:selected').text().replace('Select an option', '') + '</td>';
				html += '<td>' + $('#fs_ict'+i+' option:selected').text().replace('Select an option', '') + '</td>';
				html += '<td>' + $('#other_qual'+i+' option:selected').text().replace('Select an option', '') + '</td>';
				if($('#ERR'+i).prop('checked'))
					html += '<td>Yes</td>';
				else
					html += '<td>No</td>';
				if($('#PLTS'+i).prop('checked'))
					html += '<td>Yes</td>';
				else
					html += '<td>No</td>';
				html += '</tr>';
			}
		}
		html += '</tbody>';
		html += '</table>';
		$('#divPreview').html(html);
		$('#dialogPreview').dialog('open');
	}

	function saveFrmAddLearners()
	{

		if(!$("#frmAddLearners").valid())
			return;

		var myForm = document.forms["frmAddLearners"];
		myForm.submit();
	}

</script>

</body>
</html>