<?php /* @var $vo Group */  ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
 "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Group</title>
<link rel="stylesheet" href="/common.css" type="text/css"/>
<script src="/js/jquery.min.js" type="text/javascript"></script>
<script src="/common.js" type="text/javascript"></script>

<script language="JavaScript">

function deleteRecord()
{
	if(window.confirm("Permanently delete this group?"))
	{
		window.location.replace('do.php?_action=delete_group&group=<?php echo $vo->id ?>');
	}
}
</script>

</head>
<body>
<div class="banner">
	<div class="Title">Security Group</div>
	<div class="ButtonBar">
		<button onclick="window.location.href='do.php?_action=view_groups';">Close</button>
		<button onclick="window.location.replace('do.php?_action=edit_group&group=<?php echo $vo->id; ?>');">Edit</button>
		<?php if($_SESSION['user']->type!=12){?>
			<button onclick="deleteRecord();" style="color:red">Delete</button>
		<?php } ?>
	</div>
	<div class="ActionIconBar">

	</div>
</div>

<h3 class="introduction">Introduction</h3>
<p class="introduction">Security groups are used as an administrative convenience when working with
Access Control Lists (ACLs). Specifying a group name in an ACL instead of individual names
prevents omissions, increases readability and reduces workload when the membership of the
group changes.</p>
<p class="introduction">Groups may be nested e.g. four groups called <code>north managers</code>, <code>east managers</code>, <code>south managers</code>
and <code>west managers</code> could be combined in a fifth group called <code>all regional managers</code>.</p>

<h3>Group Definition</h3>
<table border="0" cellpadding="4" cellspacing="4" style="margin-left:10px">
	<col width="100" />
	<tr>
		<td class="fieldLabel">Title</td>
		<td style="font-family:monospace"><?php echo htmlspecialchars((string)$vo->group_name); ?></td>
	</tr>
	<tr>
		<td class="fieldLabel" valign="top">Description</td>
		<td><div width="400"><?php echo htmlspecialchars((string)$vo->description); ?></div></td>
	</tr>
	<tr>
		<td class="fieldLabel_compulsory" valign="top">Members</td>
		<td style="font-family:monospace"><div width="400"><?php echo nl2br(htmlspecialchars(implode("\n", $vo->getMembers()))); ?></div></td>
	</tr>
</table>

</body>
</html>