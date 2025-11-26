<?php /* @var $vo OTJ */ ?>
<?php /* @var $pot_vo TrainingRecord */ ?>

<!DOCTYPE html>
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<title><?php echo $vo->id == ''?'Add GLH Details':'Edit GLH Details'; ?></title>
	<link rel="stylesheet" href="module_tracking/css/common.css" type="text/css"/>
	<link rel="stylesheet" href="/assets/adminlte/bootstrap/css/bootstrap.min.css">
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.5.0/css/font-awesome.min.css">
	<link rel="stylesheet" href="/assets/adminlte/plugins/jQueryUI/jquery-ui.css">
	<link rel="stylesheet" href="/assets/adminlte/dist/css/AdminLTE.min.css">
	<link rel="stylesheet" type="text/css" href="/assets/css/jquery.timepicker.css" />

	<!--[if lt IE 9]>
	<script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
	<script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
	<![endif]-->

	<style>
		.ui-datepicker .ui-datepicker-title select {
			color: #000;
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
			<div class="Title" style="margin-left: 6px;"><?php echo $vo->id == ''?'Add GLH Details':'Edit GLH Details'; ?></div>
			<div class="ButtonBar">
				<span class="btn btn-sm btn-default" onclick="window.location.href='<?php echo $_SESSION['bc']->getPrevious(); ?>';"><i class="fa fa-arrow-circle-o-left"></i> Cancel</span>
				<?php if($enable_save){?>
				<span class="btn btn-sm btn-default" onclick="save();"><i class="fa fa-save"></i> Save</span>
				<?php } ?>
				<?php if($enable_save && !is_null($vo->id) && $vo->id != ''){?>
				<span class="btn btn-sm btn-default" onclick="delete_record(<?php echo $vo->id; ?>);"><i class="fa fa-trash"></i> Delete</span>
				<?php } ?>
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
		<div class="col-sm-6">
			<div class="callout">
				<div class="row">
					<label class="col-sm-4 control-label fieldLabel_optional">Learner Name:</label>
					<div class="col-sm-8 text-bold"><?php echo $pot_vo->firstnames . ' ' . $pot_vo->surname; ?></div>
				</div>
				<div class="row">
					<label class="col-sm-4 control-label fieldLabel_optional">Training Dates:</label>
					<div class="col-sm-8 text-bold"><?php echo Date::toShort($pot_vo->start_date) . ' ' . Date::toShort($pot_vo->target_date); ?></div>
				</div>
				<div class="row">
					<label class="col-sm-4 control-label fieldLabel_optional">Reference:</label>
					<div class="col-sm-8 text-bold"><?php echo $pot_vo->l03; ?></div>
				</div>
			</div>
		</div>
	</div>
</div>

<div class="row">
	<form class="form-horizontal" name="frmGLH" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post" enctype="multipart/form-data">
		<input type="hidden" name="_action" value="save_glh" />
		<input type="hidden" name="id" value="<?php echo $vo->id ?>" />
		<input type="hidden" name="tr_id" value="<?php echo $vo->tr_id ?>" />
		<div class="col-md-8">

			<div class="box box-primary">

				<div class="box-body">
					<div class="form-group">
						<label for="input_date" class="col-sm-3 control-label fieldLabel_compulsory">Date:</label>
						<div class="col-sm-9">
							<?php echo HTML::datebox('date', $vo->date, true); ?>
						</div>
					</div>
					<div class="form-group">
						<label class="col-sm-3 control-label fieldLabel_compulsory">Enter Time Or Duration:</label>
						<div class="col-sm-9">
							<div class="col-sm-5">
								From: <?php echo HTML::timebox('time_from', $vo->time_from); ?>&nbsp;
								To: <?php echo HTML::timebox('time_to', $vo->time_to); ?>
							</div>
							<div class="col-sm-1">|</div>
							<div class="col-sm-6">
								Hours: <?php echo HTML::select('duration_hours', $ddlHours, $vo->duration_hours <= 9 ? '0'.$vo->duration_hours : $vo->duration_hours , false); ?> &nbsp;&nbsp; 
								Minutes: <?php echo HTML::select('duration_minutes', $ddlMinutes, $vo->duration_minutes, false); ?>
							</div>
						</div>
					</div>
					<div class="form-group">
						<label for="type" class="col-sm-3 control-label fieldLabel_optional">Type:</label>
						<div class="col-sm-9">
							<?php echo HTML::selectChosen('type', $types, $vo->type, true); ?>
						</div>
					</div>
					<div class="form-group">
						<label for="comments" class="col-sm-3 control-label fieldLabel_optional">Comments:</label>
						<div class="col-sm-9">
							<textarea name="comments" id="comments" rows="10" style="width: 100%;"><?php echo $vo->comments; ?></textarea>
						</div>
					</div>
					<div class="form-group">
						<label for="uploaded_file" class="col-sm-3 control-label fieldLabel_optional">Upload File:</label>
						<div class="col-sm-9">
                            				<input class="optional" type="file" id="uploaded_file" name="uploaded_file" />
						</div>
					</div>
				</div>

			</div>

			<div class="box box-primary">
                		<div class="box-body">
                    			<?php echo $files_html; ?>
                		</div>
            		</div>

		</div>

		<?php if($other_records != '') { ?>
		<div class="col-md-4">
			<div class="box box-primary">
				<div class="box-header with-border"><h2 class="box-title">Other Records</h2>
					<div class="box-body">
						<?php echo $other_records; ?>
					</div>
				</div>
			</div>
		<?php } ?>

	</form>
</div>
<div id="dialogDeleteFile" style="display:none" title="Delete record"></div>
<br>

<script src="/assets/adminlte/plugins/jQuery/jquery-2.2.3.min.js"></script>
<script src="/assets/adminlte/plugins/jQueryUI/jquery-ui.min.js"></script>
<script src="/assets/adminlte/bootstrap/js/bootstrap.min.js"></script>
<script src="/assets/adminlte/dist/js/app.min.js"></script>
<script src="/common.js" type="text/javascript"></script>
<script type="text/javascript" src="/assets/js/jquery/jquery.timepicker.js"></script>

<script language="JavaScript">

	$(function() {

		$('.datepicker').datepicker({
			format: 'dd/mm/yyyy'
		});

		$('#input_date').attr('class', 'datepicker compulsory form-control');

		$(".timebox").timepicker({ timeFormat: 'H:i' });

		$('.timebox').bind('timeFormatError timeRangeError', function() {
			this.value = '';
			alert("Please choose a valid time");
			this.focus();
		});


		$('#input_time_from, #input_time_to').on('change', function(){
			var diff = calculateDuration();
			if(diff != '')
			{
				$('#duration_hours').val(diff["hours"]);
				$('#duration_minutes').val(diff["minutes"]);
			}
		});

	});

	function msToTime(duration)
	{
		var milliseconds = parseInt((duration % 1000) / 100),
			seconds = Math.floor((duration / 1000) % 60),
			minutes = Math.floor((duration / (1000 * 60)) % 60),
			hours = Math.floor((duration / (1000 * 60 * 60)) % 24);

		hours = (hours < 10) ? "0" + hours : hours;
		minutes = (minutes < 10) ? "0" + minutes : minutes;
		seconds = (seconds < 10) ? "0" + seconds : seconds;

		var diff = {};
		diff['hours'] = hours;
		diff['minutes'] = minutes;
		return diff;
	}

	function calculateDuration()
	{
		var date = $('#input_date').val();
		var time_from = $('#input_time_from').val();
		var time_to = $('#input_time_to').val();

		var date_string1 = date + " " + time_from + ":00";
		var date_string2 = date + " " + time_to + ":00";

		var d1 = stringToDate(date_string1);
		var d2 = stringToDate(date_string2);

		if(d2-d1 <= 0)
		{
			return '';
		}

		return msToTime(d2-d1)
	}

	function save()
	{
		var myForm = document.forms["frmGLH"];
		if(validateForm(myForm) == false)
		{
			return false;
		}

		if(myForm.duration_hours.value == '00' && myForm.duration_minutes.value == '00')
		{
			alert('Please enter time or duration');
			return false;
		}

		if($('#input_time_from').val() != '' && $('#input_time_to').val() != '')
		{
			var diff = calculateDuration();
			var str1 = $('#duration_hours').val() + ":" + $('#duration_minutes').val();
			var str2 = diff["hours"] + ":" + diff["minutes"];
			if( str1 !== str2 )
			{
				alert('If you enter both time and duration, please make sure that they are consistent.');
				return false;
			}
		}


		myForm.submit();
	}

	function delete_record(record_id)
	{
		if(!confirm('This action cannot be undone, are you sure you want to delete this record?'))
			return;
		var client = ajaxRequest('do.php?_action=edit_glh_hours&ajax_request=true&glh_id='+ encodeURIComponent(record_id));
		alert(client.responseText);
		window.history.back();
	}

	function downloadFile(path)
    {
        window.location.href="do.php?_action=downloader&f=" + encodeURIComponent(path);
    }

    function deleteFile(path)
    {
        confirmation("Deletion is permanent and irrecoverable.  Continue?").then(function (answer) {
            var ansbool = (String(answer) == "true");
            if(ansbool){

                var client = ajaxRequest('do.php?_action=delete_file&f=' + encodeURIComponent(path));
                if(client)
                    window.location.replace("do.php?_action=edit_glh_hours&tr_id="+encodeURIComponent(<?php echo $vo->tr_id; ?>)+"&glh_id=" + encodeURIComponent(<?php echo $vo->id; ?>));
            }
        });
    }

    function confirmation(question) {
        var defer = $.Deferred();
        $('<div></div>')
            .html(question)
            .dialog({
                autoOpen: true,
                modal: true,
                title: 'Confirmation',
                buttons: {
                    "Yes": function () {
                        defer.resolve("true");//this text 'true' can be anything. But for this usage, it should be true or false.
                        $(this).dialog("close");
                    },
                    "No": function () {
                        defer.resolve("false");//this text 'false' can be anything. But for this usage, it should be true or false.
                        $(this).dialog("close");
                    }
                },
                close: function () {
                    //$(this).remove();
                    $(this).dialog('destroy').remove()
                }
            });
        return defer.promise();
    };
</script>

</body>
</html>