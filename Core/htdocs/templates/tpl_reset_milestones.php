<?php /* @var $vo OrganisationVO */ ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
 "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
	<title>Reset Milestones</title>
	<link rel="stylesheet" href="/common.css" type="text/css"/>
	<script src="/js/jquery.min.js" type="text/javascript"></script>
	<script src="/common.js" type="text/javascript"></script>

<script language="JavaScript">
function resetMilestones()
{
	postData = "aa=2";	
	var client = ajaxRequest('do.php?_action=ajax_reset_milestones', postData);
	if(client != null)
	{
		alert("Milestones have been regenerated");
		window.history.go(-1);
	}
}
</script>

</head>
<body onload="resetMilestones()">
<div class="banner">
	<div class="Title">Reset Milestones</div>
	<div class="ButtonBar">

	</div>
	<div class="ActionIconBar">

	</div>
</div>

<table  border="0" cellspacing="4" cellpadding="4" style="margin-left:10px; align-vertical: middle; align-horizontal: center">
	<tr>
		<td>Please wait......</td>
	</tr>
</table>
</body>
</html>
