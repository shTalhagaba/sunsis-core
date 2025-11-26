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
		var actionId = <?php echo $action->id ? $action->id : 'null'; ?>;

		$(function(){
			$('#btnSave').click(function(e){
				saveRecord();
			});

			$('#btnCancel').click(function(e){
				if (actionId) {
					window.location.replace("do.php?_action=sa_crontab&subaction=read&id=" + actionId);
				} else {
					window.location.href = "do.php?_action=sa_crontab";
				}
			});

			$('select[name="task"]').change(function(e){
				var task = $('select[name="task"]').val();
				$('div#Configuration').load('do.php?_action=sa_crontab&subaction=editConfiguration&id=' + actionId + '&task=' + task);
			}).change();

			// Crontab fields cannot be blank
			$('input.Crontab').blur(function(e){
				$(this).val(jQuery.trim($(this).val()));
				if ($(this).prop('name') == 'minute') {
					if ($(this).val() == '' || $(this).val() == '*') {
						$(this).val('0')
					}
				} else {
					if ($(this).val() == '') {
						$(this).val('*');
					}
				}
			});


		});


		function saveRecord()
		{
			if (window.saveLock) {
				return;
			}
			window.saveLock = true;

			var myForm = document.forms[0];
			if (!validateForm(myForm)) {
				window.saveLock = false;
				return;
			}

			var $minute = $('input[name="minute"]');
			if ($minute.val() == '*') {
				alert("Please do not specify a task to run every minute.");
				$minute.val('');
				$minute.focus();
				window.saveLock = false;
				return;
			}

			var client = ajaxPostForm(myForm);
			if (client) {
				var id = client.responseText;
				window.location.replace('do.php?_action=sa_crontab&subaction=read&id=' + id);
			}
			window.saveLock = false;
		}
	</script>

	<style type="text/css">
		div#PropertiesNoop, div#PropertiesSynchroniseEmployers, div#PropertiesSynchroniseLearners {
			display: none;
		}
	</style>

</head>

<body>
<div class="banner">
	<div class="Title">Smart Assessor: Scheduled Task</div>
	<div class="ButtonBar">
		<button id="btnCancel">Cancel</button>
		<button id="btnSave">Save</button>
	</div>
	<div class="ActionIconBar">
		<button onclick="window.print()" title="Print-friendly view"><img src="/images/btn-printer.gif" width="16" height="16" style="vertical-align:text-bottom" /></button>
		<button onclick="window.location.reload(false);" title="Refresh view (see the latest changes from other users)"><img src="/images/btn-refresh.gif" width="16" height="16" style="vertical-align:text-bottom" /></button>
	</div>
</div>

<h3 class="introduction">Instructions</h3>
<p class="introduction">Scheduled task.</p>

<form action="do.php?_action=sa_crontab&subaction=save" method="post">
<input type="hidden" name="id" value="<?php echo htmlspecialchars((string)$action->id); ?>" ?>
<h3>Task</h3>
<table cellspacing="4" cellpadding="4" style="margin-left:10px; width:590px">
	<col width="150"/>
	<tr>
		<td class="fieldLabel">Task</td>
		<td><?php echo HTML::select('task', $tasks, $action->task, false, true); ?></td>
	</tr>
	<tr>
		<td class="fieldLabel">Order</td>
		<td><input type="text" class="compulsory" name="order" value="<?php echo htmlspecialchars((string)$action->order); ?>" size="3"/></td>
	</tr>
	<tr>
		<td class="fieldLabel">Email log to</td>
		<td><input type="text" class="optional" name="mail_log" value="<?php echo htmlspecialchars((string)$action->mail_log); ?>" size="30"/>
		<span style="color:gray;font-style:italic;margin-left:20px">comma separated</span> </td>
	</tr>
	<tr>
		<td class="fieldLabel">Email errors to</td>
		<td><input type="text" class="optional" name="mail_errors" value="<?php echo htmlspecialchars((string)$action->mail_errors); ?>" size="30"/>
			<span style="color:gray;font-style:italic;margin-left:20px">comma separated</span> </td>
	</tr>
	<tr>
		<td class="fieldLabel">Mode</td>
		<td><?php echo HTML::select('read_only', array(array(1,'Read only'), array(0, 'Read / Write')), $action->read_only, false, true); ?></td>
	</tr>
</table>

<h4>Task-specific settings</h4>
<div id="Configuration"></div>

<h3>Schedule</h3>
<p class="sectionDescription">Schedules should be specified in Linux crontab notation.
	<abbr title="e.g. '9,10,11,12'">Lists</abbr>,
	<abbr title="e.g. '9-12'">ranges</abbr>
	and <abbr title="e.g. '8-18/2'">stepped ranges</abbr> are supported. The default value is the
	wildcard asterisk, indicating 'every' or 'all'.</p>
<p class="sectionDescription">All criteria must be true for a task to run, with one exception. If both
	<span style="text-decoration: underline">day of month</span>
	and <span style="text-decoration: underline">day of week</span> are specified, then
the task will run if either criterion is true.  For example, specifying the criteria
	<span style="text-decoration: underline">day of month</span>=10 and
	<span style="text-decoration: underline">day of week</span>=2
	will run the task on the 10th day of every month <b>and</b> every Tuesday.</p>
	<p class="sectionDescription">If two or more tasks are scheduled to run at the same time, they
		will be run in the order they are listed in the scheduled tasks view. Change the value of the
		<span style="text-decoration: underline">order</span> field to influence the display order.</p>

<table cellspacing="4" cellpadding="4" style="margin-left:10px; width:590px">
	<col width="150"/>
	<tr>
		<td class="fieldLabel">Enabled</td>
		<td><?php echo HTML::select('enabled', array(array(1,'Yes'), array(0, 'No')), $action->enabled, false, true); ?></td>
	</tr>
</table>

<?php if ($_SESSION['user']->isAdmin() && (SOURCE_BLYTHE_VALLEY || SOURCE_LOCAL)): ?>
<h4>Specification</h4>
<table cellspacing="4" cellpadding="4" style="margin-left:10px; width:590px">
	<col width="150"/>
	<tr>
		<td class="fieldLabel">Minute</td>
		<td><input type="text" class="Crontab compulsory" name="minute" value="<?php echo htmlspecialchars((string)$action->minute); ?>" size="10" />
		<span style="color:gray;font-style:italic;margin-left:20px">0 - 59</span></td>
	</tr>
	<tr>
		<td class="fieldLabel">Hour</td>
		<td><input type="text" class="Crontab compulsory" name="hour" value="<?php echo htmlspecialchars((string)$action->hour); ?>" size="10" />
		<span style="color:gray;font-style:italic;margin-left:20px">0 - 23</span></td>
	</tr>
	<tr>
		<td class="fieldLabel">Day of Month</td>
		<td><input type="text" class="Crontab compulsory" name="day_of_month" value="<?php echo htmlspecialchars((string)$action->day_of_month); ?>" size="10" />
		<span style="color:gray;font-style:italic;margin-left:20px">1 - 31</span></td>
	</tr>
	<tr>
		<td class="fieldLabel">Month</td>
		<td><input type="text" class="Crontab compulsory" name="month" value="<?php echo htmlspecialchars((string)$action->month); ?>" size="10" />
		<span style="color:gray;font-style:italic;margin-left:20px">1 - 12</span></td>
	</tr>
	<tr>
		<td class="fieldLabel">Day of Week</td>
		<td><input type="text" class="Crontab compulsory" name="day_of_week" value="<?php echo htmlspecialchars((string)$action->day_of_week); ?>" size="10" />
		<span style="color:gray;font-style:italic;margin-left:20px">0 - 6 (Sunday = 0)</span></td>
	</tr>
</table>
<?php endif; ?>

</form>

</body>

</html>