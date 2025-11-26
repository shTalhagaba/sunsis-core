<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
	"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
	<title>Smart Assessor: Scheduled Task</title>
	<link rel="stylesheet" href="/common.css" type="text/css"/>
	<link rel="stylesheet" href="/jquery-ui/css/jquery-ui-1.8.17.custom.css" type="text/css"/>
	<link rel="stylesheet" type="text/css" media="print" href="/print.css" />
	<script src="/js/jquery.min.js" type="text/javascript"></script>
	<script src="/common.js" type="text/javascript"></script>
	<script src="/jquery-ui/js/jquery-ui-1.8.17.custom.min.js" type="text/javascript"></script>

	<script type="text/javascript">
		var actionId = <?php echo $action->id; ?>;
		$(function(){
			$('#btnEdit').click(function(e){
				window.location.replace("do.php?_action=sa_crontab&subaction=edit&id=" + actionId);
			});
			$('#btnClose').click(function(e){
				window.location.replace("do.php?_action=sa_crontab");
			});
			$('#btnRun').click(function(e){
				runTask();
			});
			$('#btnDelete').click(function(e){
				deleteTaskConfirm();
			});


			$('#dialogTaskOutput').dialog({
				modal: true,
				width: 600,
				closeOnEscape: true,
				autoOpen: false,
				resizable: false,
				draggable: false,
				buttons: {
					'OK': function() {$(this).dialog('close');}
				}
			});

			$('#dialogConfirmation').dialog({
				modal: true,
				width: 400,
				closeOnEscape: true,
				autoOpen: false,
				resizable: false,
				draggable: false,
				buttons: {
					'Yes': function() {
						$(this).dialog('close');
						deleteTask();
					},
					'No': function() {$(this).dialog('close');}
				}
			});

		});


		function runTask()
		{
			var $dialog = $('#dialogTaskOutput');
			$('div#output', $dialog).html('<img src="/images/progress-animations/loading51.gif" />').css('text-align', 'center');
			$dialog.dialog('open');
			$('div#output', $dialog).load('do.php?_action=sa_crontab&subaction=run&id=' + actionId, function(){
				$('div#output', $dialog).css('text-align', 'left');
			});
		}

		function deleteTaskConfirm()
		{
			$('div#dialogConfirmation').dialog('open');
		}

		function deleteTask()
		{
			var client = ajaxRequest('do.php?_action=sa_crontab&subaction=delete&id=' + actionId);
			if (client) {
				window.location.href = 'do.php?_action=sa_crontab';
			}
		}

	</script>

	<style type="text/css">
		div#output {
			width: 100%;
			height: 300px;
			padding: 3px;
			overflow: scroll;
			font-size: 8pt;
			font-family: 'Segoe UI', Tahoma, Arial, sans-serif;
			border: silver 1px solid;
		}
	</style>

</head>

<body>
<div class="banner">
	<div class="Title">Smart Assessor: Scheduled Task</div>
	<div class="ButtonBar">
		<button id="btnClose">Close</button>
		<button id="btnEdit">Edit</button>
		<?php if($_SESSION['user']->isAdmin() && (SOURCE_BLYTHE_VALLEY || SOURCE_HOME)): ?>
		<button id="btnDelete">Delete</button>
		<button id="btnRun">Run Now</button>
		<?php endif; ?>
	</div>
	<div class="ActionIconBar">
		<button onclick="window.print()" title="Print-friendly view"><img src="/images/btn-printer.gif" width="16" height="16" style="vertical-align:text-bottom" /></button>
		<button onclick="window.location.reload(false);" title="Refresh view (see the latest changes from other users)"><img src="/images/btn-refresh.gif" width="16" height="16" style="vertical-align:text-bottom" /></button>
	</div>
</div>

<h3 class="introduction">Instructions</h3>
<p class="introduction">Scheduled task.</p>

<h3>Task</h3>
<table cellspacing="4" cellpadding="4" style="margin-left:10px; width:590px">
	<col width="150"/>
	<tr>
		<td class="fieldLabel">Task</td>
		<td class="fieldValue"><?php echo htmlspecialchars((string)$action->task); ?></td>
	</tr>
	<tr>
		<td class="fieldLabel">Order</td>
		<td class="fieldValue"><?php echo htmlspecialchars((string)$action->order); ?></td>
	</tr>
	<tr>
		<td class="fieldLabel">Email log to</td>
		<td class="fieldValue"><?php echo htmlspecialchars((string)$action->mail_log); ?></td>
	</tr>
	<tr>
		<td class="fieldLabel">Email errors to</td>
		<td class="fieldValue"><?php echo htmlspecialchars((string)$action->mail_errors); ?></td>
	</tr>
	<tr>
		<td class="fieldLabel">Mode</td>
		<td class="fieldValue"><?php echo htmlspecialchars(HTML::yesNoUnknown($action->read_only, 'Read only', 'Read / Write')); ?></td>
	</tr>
</table>

<h4>Task-specific settings</h4>
<?php $this->renderActionConfigurationRead($action); ?>

<h3>Schedule</h3>
<table cellspacing="4" cellpadding="4" style="margin-left:10px; width:590px">
	<col width="150"/>
	<tr>
		<td class="fieldLabel">Enabled</td>
		<td class="fieldValue"><?php echo htmlspecialchars(HTML::yesNoUnknown($action->enabled)); ?></td>
	</tr>
</table>

<h4>Specification</h4>
<table cellspacing="4" cellpadding="4" style="margin-left:10px; width:590px">
	<col width="150"/>
	<tr>
		<td class="fieldLabel">Minute</td>
		<td class="fieldValue"><?php echo htmlspecialchars((string)$action->minute); ?></td>
	</tr>
	<tr>
		<td class="fieldLabel">Hour</td>
		<td class="fieldValue"><?php echo htmlspecialchars((string)$action->hour); ?></td>
	</tr>
	<tr>
		<td class="fieldLabel">Day of Month</td>
		<td class="fieldValue"><?php echo htmlspecialchars((string)$action->day_of_month); ?></td>
	</tr>
	<tr>
		<td class="fieldLabel">Month</td>
		<td class="fieldValue"><?php echo htmlspecialchars((string)$action->month); ?></td>
	</tr>
	<tr>
		<td class="fieldLabel">Day of Week</td>
		<td class="fieldValue"><?php echo htmlspecialchars((string)$action->day_of_week); ?></td>
	</tr>
</table>

<div id="dialogTaskOutput" title="Task Output" style="display:none">
	<div id="output"></div>
</div>

<div id="dialogConfirmation" title="Confirm">Delete this task?</div>

</body>

</html>