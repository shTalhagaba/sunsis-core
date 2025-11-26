<?php /* @var $session OperationsSession */ ?>
<!DOCTYPE html>
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<title>Sunesis - Event Register</title>
	<link rel="stylesheet" href="module_tracking/css/common.css" type="text/css"/>
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

	</style>

	<script src="/assets/adminlte/plugins/jQuery/jquery-2.2.3.min.js"></script>
</head>
<body>
<div class="row">
	<div class="col-lg-12">
		<div class="banner">
			<div class="Title" style="margin-left: 6px;">Event [<?php echo $session->unit_ref; ?>] Register</div>
			<div class="ButtonBar">
				<span class="btn btn-sm btn-default" onclick="window.location.href='<?php echo $_SESSION['bc']->getPrevious(); ?>';"><i class="fa fa-arrow-circle-o-left"></i> Cancel</span>
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
				<div class="col-sm-2"><span class="text-bold">Title: </span><?php echo $session->title; ?></div>
				<div class="col-sm-2"><span class="text-bold">Type: </span><?php echo $session->getEventTypeDescription(); ?></div>
				<div class="col-sm-2"><span class="text-bold">Trainer: </span><?php echo $session->getPersonnelName($link); ?></div>
				<div class="col-sm-2"><span class="text-bold">Start Date Time: </span><?php echo Date::toShort($session->start_date) . ' (' . $session->start_time . ')'; ?></div>
				<div class="col-sm-2"><span class="text-bold">End Date Time: </span><?php echo Date::toShort($session->end_date) . ' (' . $session->end_time . ')'; ?></div>
			</div>
			<div class="row small">
				<div class="col-sm-2"><span class="text-bold">Tracker Title: </span><?php echo $session->getTrackerTitle($link); ?></div>
				<div class="col-sm-2"><span class="text-bold">Unit Reference: </span><?php echo $session->unit_ref; ?></div>
				<div class="col-sm-2"><span class="text-bold">Max Learners Allowed: </span><?php echo $session->max_learners; ?></div>
			</div>
		</div>
	</div>
</div>

<div class="row">
	<div class="col-sm-12">
			<div class="box box-success">
				<div class="box-header with-border">
					<div class="col-sm-4"><h2 class="box-title">Event Register</h2></div>
					<div class="col-sm-8"><span class="btn btn-sm btn-primary pull-right" id="btnSaveSessionRegister"><i class="fa fa-save"></i> Save Register</span></div>
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
						<form name="frmSessionRegister" id="frmSessionRegister" action="/do.php" method="post" >
							<input type="hidden" name="_action" value="save_op_session_register" />
							<input type="hidden" name="session_id" value="<?php echo $session->id; ?>" />
							<?php echo $session->getRegister($link); ?>
						</form>
						<?php
					}
					?>
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

</script>

</body>
</html>