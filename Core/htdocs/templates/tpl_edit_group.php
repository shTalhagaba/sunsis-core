<?php /* @var $vo User */  ?>
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

function save()
{
	var myForm = document.forms[0];
	
	if(!validateForm(myForm))
	{
		return false;
	}

	// Group name validation
	var grp = myForm.elements['group_name'];
	grp.value = grp.value.toLowerCase();
	var re = /^[a-z][a-z0-9_]+$/;
	if(re.test(grp.value) == false)
	{
		alert("The group name may only contain letters, numbers and underscores, and may not begin with a number");
		grp.focus();
		return false;
	}
	
	
	// Submit the form by AJAX
	var client = ajaxPostForm(myForm);
	if(client != null)
	{
		window.location.replace('do.php?_action=read_group&group=' + client.responseText);
	}
}



function body_onload()
{

}
</script>

</head>
<body onload="body_onload()">
<div class="banner">
	<div class="Title">Security Group</div>
	<div class="ButtonBar">
		<?php if($_SESSION['user']->type!=12){?>			
		<button onclick="save();">Save</button>
		<?php }?>
		<button onclick="<?php echo $js_cancel; ?>">Cancel</button>
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
<form name="form1" action="<?php echo $_SERVER['PHP_SELF'] ?>?_action=save_group" method="post" enctype="multipart/form-data">
<input type="hidden" name="_action" value="save_group" />
<input type="hidden" name="id" value="<?php echo $vo->id; ?>" />

<table border="0" cellpadding="4" cellspacing="4" style="margin-left:10px">

	<tr>
		<td class="fieldLabel_compulsory">Title</td>
		<td><input class="compulsory" type="text" name="group_name" value="<?php echo htmlspecialchars((string)$vo->group_name); ?>" size="45"
		style="font-family:monospace" maxlength="45" onchange="this.value=this.value.toLowerCase()"/></td>
	</tr>
	<tr>
		<td class="fieldLabel_compulsory" valign="top">Description</td>
		<td><textarea name="description" rows="5" cols="50"><?php echo htmlspecialchars((string)$vo->description); ?></textarea></td>
	</tr>
</table>

<div style="margin-left:10px; margin-top:20px">
<?php $acl->renderList($link, 'members', $vo->getMembers(), ACL::EVERYONE|ACL::GROUPS|ACL::EMPLOYEES|ACL::EMPLOYEE_WILDCARDS); ?>
</div>

</form>




</body>
</html>