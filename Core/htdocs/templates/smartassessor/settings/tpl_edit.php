<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
	"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
	<title>Smart Assessor: Settings</title>
	<link rel="stylesheet" href="/common.css" type="text/css"/>
	<link rel="stylesheet" href="/jquery-ui/css/jquery-ui-1.8.17.custom.css" type="text/css"/>
	<link rel="stylesheet" type="text/css" media="print" href="/print.css" />
	<script src="/js/jquery.min.js" type="text/javascript"></script>
	<script src="/common.js" type="text/javascript"></script>
	<script src="/jquery-ui/js/jquery-ui-1.8.17.custom.min.js" type="text/javascript"></script>

	<script type="text/javascript">
		$(function(){
			$('#btnCancel').click(function(e){
				window.location.replace("do.php?_action=sa_settings");
			});

			$('#btnSave').click(onSave);
		});

		function onSave(e)
		{
			var client = ajaxPostForm(document.forms[0]);
			if (client) {
				window.location.replace("do.php?_action=sa_settings");
			}
		}

	</script>

	<style type="text/css">

	</style>

</head>

<body>
<div class="banner">
	<div class="Title">Smart Assessor: Settings</div>
	<div class="ButtonBar">
		<button id="btnSave">Save</button>
		<button id="btnCancel">Cancel</button>
	</div>
	<div class="ActionIconBar">
		<button onclick="window.print()" title="Print-friendly view"><img src="/images/btn-printer.gif" width="16" height="16" style="vertical-align:text-bottom" /></button>
		<button onclick="window.location.reload(false);" title="Refresh view (see the latest changes from other users)"><img src="/images/btn-refresh.gif" width="16" height="16" style="vertical-align:text-bottom" /></button>
	</div>
</div>

<h3 class="introduction">Instructions</h3>
<p class="introduction">Sunesis learner data may be synchronised with learner data in Smart Assessor. This page provides
	access to settings and options required to connect to Smart Assessor.</p>

<h3>SOAP Connection Parameters</h3>
<form action="do.php?_action=sa_settings&subaction=save" method="post">
<table cellspacing="4" style="margin-left:10px; width:590px">
	<col width="100"/>
	<tr>
		<td class="fieldLabel">WSDL</td>
		<td><input type="text" name="wsdl" value="<?php echo htmlspecialchars((string)$wsdl); ?>" size="50" maxlength="50" /></td>
	</tr>
	<tr>
		<td class="fieldLabel">API Key</td>
		<td><input type="text" name="api_key" value="<?php echo htmlspecialchars((string)$apiKey); ?>" size="50" maxlength="50" /></td>
	</tr>
	<tr>
		<td class="fieldLabel">Namespace</td>
		<td><input type="text" name="namespace" value="<?php echo htmlspecialchars((string)$namespace); ?>" size="50" maxlength="50" /></td>
	</tr>
	<tr>
		<td class="fieldLabel">Enabled</td>
		<td><?php echo HTML::select("enabled", array(array('1', 'Yes'), array('0', 'No')), $enabled); ?></td>
	</tr>
	<tr>
		<td class="fieldLabel">Display menu</td>
		<td><?php echo HTML::select("display_menu", array(array('1', 'Yes'), array('0', 'No')), $display_menu); ?></td>
	</tr>
</table>
</form>

</body>

</html>