<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
	"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Smart Assessor: Scheduled Tasks</title>
<link rel="stylesheet" href="/common.css" type="text/css"/>
<link rel="stylesheet" href="/jquery-ui/css/jquery-ui-1.8.17.custom.css" type="text/css"/>
<link rel="stylesheet" type="text/css" media="print" href="/print.css" />
<script src="/js/jquery.min.js" type="text/javascript"></script>
<script src="/common.js" type="text/javascript"></script>
<script src="/jquery-ui/js/jquery-ui-1.8.17.custom.min.js" type="text/javascript"></script>

<script type="text/javascript">
$(function(){

	$('select[name="scheduler_status"]').change(function(e){
		var client = ajaxRequest('do.php?_action=sa_crontab&subaction=updateCrontabSettings&enabled=' + $(this).val());
		if (client) {
			alert("Scheduler status updated");
		}
	});

});


function newRecord()
{
	window.location.href="do.php?_action=sa_crontab&subaction=edit";
}

	function viewLog()
	{
		window.location.href="do.php?_action=sa_crontab_log";
	}

</script>

<style type="text/css">
	h3.introduction {
		width: 100%;
		padding: 0px 0px 3px 0px;
		margin: 0px;
	}

	p.introduction {
		width: 100%;
		padding: 0px;
		margin: 10px 0px 0px 0px;
	}

	ul.introduction {
		font-family: sans-serif;
		font-size: 11pt;
		color: #176281;
		font-style: normal;
		text-align: justify;
		margin: 15px 0px 10px 0px;
		/*width: 800px;*/
	}

	li {
		margin-top: 5px;
	}

	div#Filters {
		margin-top: 40px;
		text-align: center;
		background-color: #bbca85;
		padding: 8px;
		width: 95%;
		margin-left: auto;
		margin-right: auto;

		border-radius: 8px;

		-moz-box-shadow: 3px 3px 5px rgba(127,108,56,0.4);
		-webkit-box-shadow: 3px 3px 5px rgba(127,108,56,0.4);
		box-shadow: 3px 3px 5px rgba(127,108,56,0.4);
	}

	div#content {
		margin-top: 25px;
		text-align: center;
	}

</style>

</head>

<body>
<div class="banner">
	<div class="Title">Smart Assessor: Scheduled Tasks</div>
	<div class="ButtonBar">
		<?php if ($_SESSION['user']->isAdmin() && (SOURCE_HOME || SOURCE_BLYTHE_VALLEY)) : ?>
			<button onclick="newRecord()">New</button>
		<?php endif; ?>
		<button onclick="viewLog()">View log</button>
	</div>
	<div class="ActionIconBar">
		<button onclick="window.print()" title="Print-friendly view"><img src="/images/btn-printer.gif" width="16" height="16" style="vertical-align:text-bottom" /></button>
		<button onclick="window.location.reload(false);" title="Refresh view (see the latest changes from other users)"><img src="/images/btn-refresh.gif" width="16" height="16" style="vertical-align:text-bottom" /></button>
	</div>
</div>

<div style="width:800px;margin-left:auto;margin-right:auto;">
	<h3 class="introduction">Instructions</h3>
	<div class="Newspaper">
		<p class="introduction">Synchronisation with Smart Assessor occurs on a scheduled basis. Use this page to
		create a synchronisation schedule.</p>
		<p class="introduction">Synchronisation tasks create new records in Smart Assessor and update linked records
		in both Sunesis and Smart Assessor. The synchronisation tasks allow configuration of which fields will be updated
		and which system is considered the authoritative source for each field.</p>
	</div>

	<div id="Filters">
		<table width="100%">
			<col width="120"/><col/>
			<tr>
				<td align="left" style="font-weight:bold">Scheduler Status: </td>
				<td align="left"><?php echo HTML::select('scheduler_status', $schedulerStatusOptions, $schedulerStatus, false); ?></td>
			</tr>
		</table>
	</div>

	<div id="content"><?php $this->renderView($link, $view); ?></div>
</div>

<div id="output"></div>

</body>

</html>