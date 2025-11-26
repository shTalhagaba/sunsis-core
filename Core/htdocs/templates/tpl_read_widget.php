<?php /* @var $vo Beneficiary */ ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
 "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
	<title>Widget</title>
	<link rel="stylesheet" href="/common.css" type="text/css"/>
	<script src="/js/jquery.min.js" type="text/javascript"></script>
	<script src="/common.js" type="text/javascript"></script>
	
	<script language="JavaScript">
	function deleteRecord()
	{
		if(window.confirm("Permanently delete this record?"))
		{
			window.location.replace('do.php?_action=delete_widget&id=<?php echo $vo->id; ?>');
		}
	}
	</script>
</head>

<body>
<div class="banner">
	<div class="Title">Widget</div>
	<div class="ButtonBar">
		<button onclick="window.history.go(-1)">Close</button>
		<?php if($acl->isAuthorised($_SESSION['user'], 'write')) { ?>
		<button	onclick="window.location.replace('do.php?id=<?php echo $vo->id; ?>&_action=edit_widget');">Edit</button>
		<?php } ?>
		<?php if($vo->isSafeToDelete($link)){ ?>
		<button onclick="deleteRecord();" style="color:red">Delete</button>
		<?php } ?>
	</div>
	<div class="ActionIconBar">
		<button onclick="window.print()" title="Print-friendly view"><img src="/images/btn-printer.gif" width="16" height="16" style="vertical-align:text-bottom" /></button>
		<button onclick="window.location.reload(false);" title="Refresh view (see the latest changes from other users)"><img src="/images/btn-refresh.gif" width="16" height="16" style="vertical-align:text-bottom" /></button>
	</div>
</div>

<h3 class="introduction">Introduction</h3>
<p class="introduction">A widget is a demonstation object.</p>

<h3>Core Details</h3>
<table border="0" cellspacing="4" cellpadding="4" style="margin-left:10px">
<col width="140" />
	<tr>
		<td class="fieldLabel">Title:</td>
		<td class="fieldValue" style="font-family: monospace"><?php echo htmlspecialchars((string)$vo->title); ?></td>
	</tr>
</table>

<h3>Document Access Control</h3>
<table border="0" cellspacing="4" cellpadding="4" style="margin-left:10px">
<col width="140" />
	<tr>
		<td class="fieldLabel" valign="top">Read:</td>
		<td class="fieldValue"><?php echo '<p class="aclEntry">'.implode('</p><p class="aclEntry">', $acl->getIdentities('read')).'</p>'; ?></td>
	</tr>
	<tr>
		<td class="fieldLabel" valign="top">Edit:</td>
		<td class="fieldValue"><?php echo '<p class="aclEntry">'.implode('</p><p class="aclEntry">', $acl->getIdentities('write')).'</p>'; ?></td>
	</tr>
</table>

<h3>Audit Trail</h3>
<?php Note::renderNotes($link, 'widgets', $vo->id); ?>

</body>
</html>
