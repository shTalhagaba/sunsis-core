<?php /* @var $tr TrainingRecord */ ?>

<!DOCTYPE html>
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<title>Sunesis | Video Evidences</title>
	<link rel="stylesheet" href="module_tracking/css/common.css" type="text/css"/>
	<link rel="stylesheet" href="/assets/adminlte/bootstrap/css/bootstrap.min.css">
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.5.0/css/font-awesome.min.css">
	<link rel="stylesheet" href="/assets/adminlte/plugins/jQueryUI/jquery-ui.css">
	<link rel="stylesheet" href="/assets/adminlte/dist/css/AdminLTE.min.css">

	<!--[if lt IE 9]>
	<script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
	<script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
	<![endif]-->


	<style>
		.ui-datepicker .ui-datepicker-title select {
			color: #000;
		}
		.note-group-select-from-files {
			display: none;
		}
	</style>
</head>
<body>
<div class="row">
	<div class="col-lg-12">
		<div class="banner">
			<div class="Title" style="margin-left: 6px;">Video Evidence</div>
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


<div class="row">
	<div class="col-sm-12">
		<p><br></p>
		<table class="table table-bordered">
			<thead class="bg-gray">
			<tr>
				<th>QAN</th>
				<th>Unit</th><th>File Name</th><th>Upload Timestamp</th><th>Uploaded By</th><th>Status</th><th>Assessor Feedback</th>
				<th>Action</th>
			</tr>
			</thead>
			<tbody>
			<?php
			$status_ddl = [0 => 'Awaiting Assessor Feedback', 1 => 'Assessor Accepted', 'Referred to Learner'];
			$records = DAO::getResultset($link, "SELECT * FROM video_files WHERE tr_id = '{$tr->id}' ORDER BY id", DAO::FETCH_ASSOC);
			if(count($records) == 0)
			{
				echo '<tr><td colspan="4"><i class="fa fa-info-circle"></i> No files have been uploaded.</td></tr>';
			}
			else
			{
				foreach($records AS $row)
				{
					$status_desc = isset($status_ddl[$row['status']]) ? $status_ddl[$row['status']] : $row['status'];
					echo '<tr>';
					echo '<td>' . $row['qan'] . '</td>';
					echo '<td>' . $row['unit_ref'] . '</td>';
					echo '<td>' . $row['file_name'] . '&nbsp;<span class="btn btn-info btn-sm" onclick="window.open(\'http://sunesis/do.php?_action=play_video_file&video_file_id='.$row['id'].'&username='.$tr->username.'\', \'_blank\');"><i class="fa fa-play-circle fa-lg"></i></span> </td>';
					echo '<td>' . Date::to($row['uploaded_date'], Date::DATETIME) . '</td>';
					echo '<td>' . DAO::getSingleValue($link, "SELECT CONCAT(firstnames, ' ', surname) FROM users WHERE users.id = '{$row['uploaded_by']}'") . '</td>';
					echo '<td>' . $status_desc . '</td>';
					echo '<td>' . nl2br((string) $row['assessor_comments']) . '</td>';
					echo '<td><span class="btn btn-primary btn-xs" onclick="prepareEPAModalForEdit(\'' . $row['id'] . '\');"><i class="fa fa-edit"></i> Feedback</span></td>';
					echo '</tr>';
				}
			}
			?>
			</tbody>
		</table>
	</div>
</div>

<br>

<div class="modal fade" id="EPAModal" role="dialog" data-backdrop="static" data-keyboard="false">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal">&times;</button>
				<h5 class="modal-title text-bold">Details</h5>
			</div>
			<div class="modal-body">
				<form class="form-horizontal" method="post" name="frmEPA" id="frmEPA" method="post" action="do.php?_action=tr_videos">
					<input type="hidden" name="formName" value="frmEPA" />
					<input type="hidden" name="subaction" value="save_assessor_feedback" />
					<input type="hidden" name="id" id="id" value="" />
					<input type="hidden" name="tr_id" value="<?php echo $tr->id; ?>" />

					<div class="control-group">
						<label class="control-label" for ="status">Status:</label>
						<?php echo HTML::selectChosen('status', $statuses, '', true, true); ?>
					</div>
					<div class="control-group">
						<label class="control-label" for ="assessor_comments">Assessor Comments:</label>
						<textarea class="form-control" name="assessor_comments" id="assessor_comments" rows="5" style="width: 100%;"></textarea>
					</div>
				</form>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-default pull-left btn-md" onclick="$('#EPAModal').modal('hide');">Cancel</button>
				<button type="button" id="btnEPAModalSave" class="btn btn-primary btn-md"><i class="fa fa-save"></i> Save</button>
			</div>
		</div>
	</div>
</div>


<script src="/assets/adminlte/plugins/jQuery/jquery-2.2.3.min.js"></script>
<script src="/assets/adminlte/plugins/jQueryUI/jquery-ui.min.js"></script>
<script src="/assets/adminlte/bootstrap/js/bootstrap.min.js"></script>
<script src="/assets/adminlte/dist/js/app.min.js"></script>
<script src="/common.js" type="text/javascript"></script>

<script language="JavaScript">

	$(function() {

	});

	$("button#btnEPAModalSave").click(function(){
		if(validateForm(document.forms['frmEPA']) == false)
		{
			return;
		}
		$("#frmEPA").submit();
	});

	function prepareEPAModalForEdit(op_epa_id)
	{
		$.ajax({
			type:'GET',
			dataType: 'json',
			url:'do.php?_action=tr_videos&subaction=get_video_file_record&id='+op_epa_id,
			async: false,
			success: function(data) {
				$.each( data, function( key, value ) {
					$('#frmEPA #'+key).val(value);
				});
				$('#EPAModal').modal('show');
			},
			error: function(data, textStatus, xhr){
				console.log(data.responseText);
			}
		});
	}

	function uploadFile()
	{
		var frmUploadFile = document.forms["frmUploadFile"];

		if(!validateForm(frmUploadFile))
		{
			return;
		}

		frmUploadFile.submit();
	}


</script>

</body>
</html>