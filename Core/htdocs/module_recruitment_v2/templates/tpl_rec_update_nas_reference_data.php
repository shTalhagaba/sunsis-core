<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
	"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
	<title>NAS Reference Data Service</title>
	<link rel="stylesheet" href="/common.css" type="text/css"/>
	<script src="/js/jquery.min.js" type="text/javascript"></script>

	<link rel="stylesheet" href="/jquery-ui/css/jquery-ui-1.8.11.custom.css" type="text/css"/>

	<script language="JavaScript" src="/jquery-ui/js/jquery-1.5.2.min.js"></script>

	<!--<script language="JavaScript" src="/jquery-ui/js/jquery-1.11.0.min.js"></script>-->
	<script language="JavaScript" src="/jquery-ui/js/jquery-ui-1.8.11.custom.min.js"></script>
	<script src="/common.js" type="text/javascript"></script>

	<script language="JavaScript">
		function downloadApprenticeshipFrameworks()
		{
			$('.loading-gif').show();
			var client = ajaxRequest('do.php?_action=rec_update_nas_reference_data&subaction=ApprenticeshipFrameworks', null, null, downloadApprenticeshipFrameworksCallback);
		}
		function downloadApprenticeshipFrameworksCallback(client)
		{
			if(client != null)
			{
				$('.loading-gif').show();
			}
			else
			{
				alert(client.responseText);
			}
		}
	</script>

</head>
<body>
<div class="banner">
	<div class="Title">NAS Reference Data Service</div>
	<div class="ButtonBar">

	</div>
	<div class="ActionIconBar">

	</div>
</div>

<?php $_SESSION['bc']->render($link); ?>

<div style="width:800px;margin-left:auto;margin-right:auto;">
	<h3 class="introduction">Instructions</h3>
	<div class="Newspaper">
		<p class="introduction">This tool allows you to download reference data from National Apprenticeship Service and update in Client's system.</p>
		<p class="introduction">The Reference Data service provides system integrators with the ability to download reference data
			from the Recruit an apprentice system. This reference data is the used as input parameters to other
			web service calls.</p>
		<p class="introduction">This reference data includes the following lookups. <ul><li>Apprenticeship Frameworks</li><li>Counties</li><li>Web service Error Codes</li><li>Regions</li></ul></p>
	</div>
</div>

<div class="loading-gif" id="divProgress" style="display: none;" >
	<img src="/images/progress-animations/loading51.gif" alt="Loading" class="loading-gif" />
</div>
<br>
<div align="center">
	<form name="mainForm" id="mainForm" method="post">
		<input type="hidden" name="_action" value="rec_update_nas_reference_data" />

		<table class="resultset" cellpadding="6" cellspacing="0">
			<thead><tr><th>Entity</th><th>Detail</th><th>Action</th></tr></thead>
			<tr>
				<td>Apprenticeship Frameworks</td>
				<td>Use this tool to obtain a list of the apprenticeship frameworks which are available within the
					Recruit an apprentice system.
				</td>
				<td><span class="button" onclick="downloadApprenticeshipFrameworks();">Download</span></td>
			</tr>
			<tr>
				<td>Counties</td>
				<td>Use this tool to obtain a list of the counties which are available within the Apprenticeship
					vacancies system.
				</td>
				<td><span class="button" onclick="downloadCounties();">Download</span></td>
			</tr>
			<tr>
				<td>Error Codes</td>
				<td>Use this tool to return a list of error codes and associated descriptions which can be returned
					by the various web services within the Recruit an apprentice system</td>
				<td><span class="button" onclick="downloadErrorCodes();">Download</span></td>
			</tr>
			<tr>
				<td>Regions</td>
				<td>Use this tool to obtain a list of the geographic regions (based on government regions) which
					are available within the Recruit an apprentice system.</td>
				<td><span class="button" onclick="downloadRegions();">Download</span></td>
			</tr>
		</table>
	</form>
</div>

</body>
</html>