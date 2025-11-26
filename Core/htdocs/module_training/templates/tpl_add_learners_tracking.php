<!DOCTYPE html>
<head xmlns="http://www.w3.org/1999/html">
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<title>Record Tracking</title>
	<link rel="stylesheet" href="module_tracking/css/common.css" type="text/css"/>
	<link rel="stylesheet" href="/assets/adminlte/bootstrap/css/bootstrap.min.css">
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.5.0/css/font-awesome.min.css">
	<link rel="stylesheet" href="/assets/adminlte/plugins/jQueryUI/jquery-ui.css">
	<link rel="stylesheet" href="/assets/adminlte/dist/css/AdminLTE.min.css">
	<link href="/assets/adminlte/plugins/toastr/toastr.min.css" rel="stylesheet">
	<link rel="stylesheet" href="/assets/adminlte/plugins/iCheck/all.css">

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
		.chkTrackerEvidence {
			transform: scale(1.4);
		}
	</style>

</head>
<body class="table-responsive">
<div class="row">
	<div class="col-lg-12">
		<div class="banner">
			<div class="Title" style="margin-left: 6px;">Record Tracking</div>
			<div class="ButtonBar">
				<span class="btn btn-sm btn-default" onclick="goBack();"><i class="fa fa-arrow-circle-o-left"></i> Cancel</span>
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

<div class="content-wrapper">

	<div class="row">
		<div class="col-sm-12">
			<div class="callout callout-info">
				<i class="fa fa-info-circle"></i> Use this screen to record learners tracking. Select Course, Cohort and Training Group. Click Search to bring the sections (Knowledge, Skills etc.).
				Then select the section to bring the tracker to record information.
			</div>
		</div>
	</div>

	<div class="row">
		<div class="col-sm-12">

		</div>
	</div>

	<div class="row">
		<div class="col-sm-12">
			<div class="well well-sm">
				<form class="form-vertical" name="frmSelection" role="form" method="get" action="<?php echo $_SERVER['PHP_SELF']; ?>">
					<input type="hidden" name="_action" value="add_learners_tracking" />
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
						<div class="col-sm-3">
							<div class="form-group">
								<label for="group_id" class="col-sm-12 control-label">Group / Cohort:</label>
								<div class="col-sm-12">
									<?php echo HTML::selectChosen('group_id', $groups_select, $group_id, true); ?>
								</div>
							</div>
						</div>
						<div class="col-sm-3">
							<div class="form-group">
								<label for="tg_id" class="col-sm-12 control-label">Training Group:</label>
								<div class="col-sm-12">
									<?php echo HTML::selectChosen('tg_id', $tgs_select, $tg_id, true); ?>
								</div>
							</div>
						</div>
						<div class="col-sm-2">
							<p><br><button type="submit" class="btn btn-sm btn-info pull-right"><i class="fa fa-search"></i> Search</button></p>
						</div>
					</div>
				</form>
			</div>
		</div>
	</div>

	<div class="row">
		<div class="col-sm-12">
			<div class="well well-sm">
				<div class="divSection row">
					<div class="col-sm-12">
						<?php
						if($course_id != '' && $group_id != '' && $tg_id != '')
						{
							$template_sections = DAO::getResultset($link, "SELECT id, title FROM tracking_template WHERE course_id = '{$course_id}' AND section_id IS NULL AND element_id IS NULL", DAO::FETCH_ASSOC);
							echo '<p class="text-bold">Select Section</p>';
							foreach($template_sections AS $section)
							{
								echo '<div class="col-sm-4">';
								echo '<div class="form-group">';
								$_section_title = $section['title'] == 'PLTS - Personal Learning and Thinking Skills' ? 'PLTS' : $section['title'];
								echo '<input type="radio" class="radioSections" name="section_id" id="section_id'.$section['id'].'" value ="'.$section['id'].'" /> <label>' . $_section_title . '</label>';
								echo '</div>';
								echo '</div> ';
							}
						}
						else
						{
							echo '<div class="callout callout-info"><i class="fa fa-info-circle"></i> Select Course, Cohort and Training Group from above panel. Then press Search button to load sections in this panel.</div> ';
						}
						?>
					</div>
				</div>
			</div>
		</div>
	</div>

	<div class="row">
		<div class="col-sm-6">
			<span class="lead text-bold">Record Tracking Information</span>
			<p></p>
		</div>
		<div class="col-sm-6">
			<?php
			if(isset($tg_id) && $tg_id != '')
			{
				$repository = Repository::getRoot().'/t_groups/'.$tg_id;
				$files = Repository::readDirectory($repository);
				if(count($files) > 0)
				{
					echo '<span id="btnInfo" class="btn btn-info btn-xs pull-right"><i class="fa fa-files-o"></i></span>';
					echo '<div class="table-responsive tg_files" style="display: none;"><table class="table row-border">';
					echo '<tr>';
					$col = 0;
					foreach($files as $f)
					{
						if($f->isDir()){
							continue;
						}
						$col++;
						if($col > 3)
						{
							$col = 0;
							echo '</tr><tr>';
						}
						$ext = new SplFileInfo($f->getName());
						$ext = $ext->getExtension();
						$image = 'fa-file';
						if($ext == 'doc' || $ext == 'docx')
							$image = 'fa-file-word-o';
						elseif($ext == 'pdf')
							$image = 'fa-file-pdf-o';
						elseif($ext == 'txt')
							$image = 'fa-file-text-o';
						echo '<td>';
						echo '<a href="' . $f->getDownloadURL() . '"><i class="fa '.$image.'"></i> ' . htmlspecialchars((string)$f->getName()) . '</a><br><span class="direct-chat-timestamp "><i class="fa fa-clock-o"></i> <small>' . date("d/m/Y H:i:s", $f->getModifiedTime()) .'</small></span>';
						echo '</td>';
					}
					echo '</tr>';
					echo '</table></div>';
				}
			}
			?>
		</div>
	</div>

	<div class="row">
		<div class="col-sm-12">
			<span class="btn btn-success" style="display: none;"
			      onclick="submitFormTracking('0');"
			      id="btnSaveAndStay"><i class="fa fa-save"></i> Save and Stay</span>
			<span class="btn btn-success" style="display: none;"
			      onclick="submitFormTracking('1');"
			      id="btnSaveAndBack"><i class="fa fa-save"></i> Save and Go Back</span>
			<form name="frmTracking" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">

				<input type="hidden" name="_action" value="add_learners_tracking">
				<input type="hidden" name="subaction" value="save_tracking">
				<input type="hidden" name="course_id" value="<?php echo $course_id; ?>">
				<input type="hidden" name="group_id" value="<?php echo $group_id; ?>">
				<input type="hidden" name="tg_id" value="<?php echo $tg_id; ?>">
				<input type="hidden" name="stay_on" value="0">
				<input type="hidden" name="stay_on_section" value="">

				<div class="row">
					<div class="col-sm-4"></div>
					<div class="col-sm-4">
						<div id="div_ksb_tracker_date" style="display: none;"><strong>Enter Date:  &nbsp; </strong><?php echo HTML::datebox('ksb_tracker_date', '', true); ?></div>
					</div>
					<div class="col-sm-4"></div>
				</div>

				<div class="tracker"></div>
			</form>
		</div>
	</div>

	<div class="modal fade" id="updateDateModal" role="dialog" data-backdrop="static" data-keyboard="false">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal">&times;</button>
					<h5 class="modal-title text-bold">Edit Tracking Date</h5>
				</div>
				<div class="modal-body">
					<table class="table table-bordered">
						<caption><i class="fa fa-info-circle"></i> Use this panel to amend the tracking date.</caption>
						<tr><th>Learner: </th><td id="modalLearnerName"></td></tr>
						<tr><th>Column: </th><td id="modalColumnName"></td></tr>
					</table>
					<form class="form-horizontal" method="post" name="frm_update_tracking_date" id="frm_update_tracking_date" method="post" action="do.php?_action=add_learners_tracking&subaction=update_tracking_date">
						<input type="hidden" name="tr_id" value="" />
						<input type="hidden" name="tracking_id" value="" />
						<input type="hidden" name="course_id" value="<?php echo $course_id; ?>">
						<input type="hidden" name="group_id" value="<?php echo $group_id; ?>">
						<input type="hidden" name="tg_id" value="<?php echo $tg_id; ?>">
						<input type="hidden" name="stay_on_section" value="" />
						<div class="control-group">
							<label class="control-label" for ="input_tracking_date">Date:</label>
							<input class="datepicker form-control compulsory" type="text" id="input_tracking_date" name="tracking_date" value="" size="10" maxlength="10" placeholder="dd/mm/yyyy" />
						</div>
					</form>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-default pull-left btn-md" onclick="$('#updateDateModal').modal('hide');">Cancel</button>
					<button type="button" id="btnUpdateDateModal" class="btn btn-primary btn-md"><i class="fa fa-save"></i> Save</button>
				</div>
			</div>
		</div>
	</div>

</div> <!--container-fluid-->

<script src="/assets/adminlte/plugins/jQuery/jquery-2.2.3.min.js"></script>
<script src="/assets/adminlte/plugins/jQueryUI/jquery-ui.min.js"></script>
<script src="/assets/adminlte/bootstrap/js/bootstrap.min.js"></script>
<script src="/assets/adminlte/dist/js/app.min.js"></script>
<script src="/common.js" type="text/javascript"></script>
<script src="/assets/adminlte/plugins/iCheck/icheck.min.js"></script>


<script type="text/javascript">
	$(function() {

		$('.datepicker').datepicker({
			format: 'dd/mm/yyyy'

		});

		$("button#btnUpdateDateModal").click(function(e){
			e.preventDefault();
			var form = $("#frm_update_tracking_date");
			var url = form.attr('action');
			var td = '#cellDate_'+$('#frm_update_tracking_date input[name=tr_id]').val()+'_'+$('#frm_update_tracking_date input[name=tracking_id]').val();

			$.ajax({
				type: "POST",
				url: url,
				data: form.serialize(),
				success: function(data)
				{
					if(data.length > 15)
						window.location.href=data;
					else
					{
						$(td).html(data);
						$('#updateDateModal').modal('hide');
					}
				},
				error: function(data)
				{
					console.log(data);
				}
			});
		});

		$('input[class=radioSections]').each(function () {
			var self = $(this);
			var label = self.next();
			var label_text = label.text();
			var checkboxClass;

			if (this.checked) {
				checkboxClass = 'icheckbox_line-green';
			} else  {
				checkboxClass = 'icheckbox_line-aero';
			}
			label.remove();
			self.iCheck({
				radioClass: checkboxClass,
				insert: '<div class="icheck_line-icon"></div>' + label_text
			});
		});

		var getUrlParameter = function getUrlParameter(sParam) {
			var sPageURL = window.location.search.substring(1),
				sURLVariables = sPageURL.split('&'),
				sParameterName,
				i;

			for (i = 0; i < sURLVariables.length; i++) {
				sParameterName = sURLVariables[i].split('=');

				if (sParameterName[0] === sParam) {
					return sParameterName[1] === undefined ? true : decodeURIComponent(sParameterName[1]);
				}
			}
		};

		var stay_on_section = getUrlParameter('stay_on_section');
		if(stay_on_section !== undefined)
		{
			$('#section_id'+stay_on_section).iCheck('check');
			$('#section_id'+stay_on_section).iCheck('update');
		}

	});

	$(document).on("change","[name^='evidence_']", function(){
		var evid_tr_id = 'evid_' + this.id.replace('evidence_', '');
		if(this.checked)
			$("[name^='"+evid_tr_id+"']").prop('checked', true);
		else
			$("[name^='"+evid_tr_id+"']").prop('checked', false);
	});

	$(document).on('ifChanged', 'input.radioSections', function() {

		var self = $(this);
		var label = self.parent();
		var label_text = label.text();
		var radioClass;
		if (this.checked) {
			radioClass = 'icheckbox_line-green';
			updateTracker(this.value);
		} else  {
			radioClass = 'icheckbox_line-aero';
		}
		self.iCheck({
			radioClass: radioClass,
			insert: '<div class="icheck_line-icon"></div>' + label_text
		});

	}).trigger('ifChanged');

	function updateTracker(section_id)
	{
		$.ajax({
			type:'GET',
			url:'do.php?_action=add_learners_tracking&subaction=renderStudentsTrackingTab',
			data: {tg_id: $('#tg_id').val(), section_id: section_id} ,
			beforeSend: function() {
				$('.tracker').html('<div class="overlay"><i class="fa fa-refresh fa-spin fa-2x"></i> <img id="loading" src="images/loading.gif" alt="Loading" /></div>');
			},
			success:function(html){
				$('#btnSaveAndStay').show();
				$('#btnSaveAndBack').show();
				$('#div_ksb_tracker_date').show();
				$('.tracker').html(html);
			},
			error:function(msg){
				alert('Error: Please contact Sunesis Support with the screenshot.\r\n'+msg);
				console.log(msg);
			}
		});
	}



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

	function submitFormTracking(stay_on)
	{
		var myForm = document.forms['frmTracking'];

		if(!validateForm(myForm))
		{
			return false;
		}

		myForm.elements['stay_on'].value = stay_on;
		myForm.elements['stay_on_section'].value = $('input[type=radio][name=section_id]:checked').val();
		myForm.submit();
	}

	$('#btnInfo').on('click', function(){
		$('.tg_files').toggle();
	});

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

	function updateTrackingDate(element, tr_id, tracking_id)
	{
		var old_date = $(element).siblings("span.cellDate").html();
//		$(element).siblings("span.cellDate").html('maani');
		var learner_name_td = $(element).parents("td").siblings(":first");
		var current_td = $(element).parents("td");
		var table = $(current_td).closest('table');
		var element_title = table.find('.secondRow th').eq(current_td.index()).text();

		$('#modalLearnerName').html(learner_name_td.text());
		$('#modalColumnName').html(element_title);
		$('#input_tracking_date').val(old_date);

		$('#frm_update_tracking_date input[name=tr_id]').val(tr_id);
		$('#frm_update_tracking_date input[name=tracking_id]').val(tracking_id);
		$('#frm_update_tracking_date input[name=stay_on_section]').val($('input[type=radio][name=section_id]:checked').val());
		$('#updateDateModal').modal('show');
	}

	function goBack()
    	{
        	var url = '<?php echo $_SESSION['bc']->getPrevious(); ?>';

        	var course_id = $("select[name=course_id]").val();
        	var group_id = $("select[name=group_id]").val();
        	var tg_id = $("select[name=tg_id]").val();
        	if(course_id != '' && group_id != '' && tg_id != '')
        	{
            		url = 'do.php?_action=read_course_v2&subview=training_group_view&id='+course_id+'&group_id='+group_id+'&tg_id='+tg_id+'&from_view=group_view';
        	}

        	window.location.href = url;
    	}
</script>

</body>
</html>
