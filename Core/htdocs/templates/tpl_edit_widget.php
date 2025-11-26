<?php /* @var $vo Organisation */ ?>
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
function save()
{
	// Lock the save button
	var btnSave = document.getElementById('btnSave');
	btnSave.disabled = true;
	
	var myForm = document.forms[0];
	if(validateForm(myForm) == false)
	{
		btnSave.disabled = false;
		return false;
	}
	
	var client = ajaxPostForm(myForm);
	if(client != null)
	{
		window.location.replace('do.php?_action=read_widget&id=' + client.responseText);
	}
	
	btnSave.disabled = false;
}

</script>

</head>
<body>
<div class="banner">
	<div class="Title">Widget</div>
	<div class="ButtonBar">
		<button id="btnSave" onclick="save(); return false;">Save</button>
		<button onclick="<?php echo $js_cancel; ?>">Cancel</button>
	</div>
	<div class="ActionIconBar">

	</div>
</div>

<form name="form1" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
<input type="hidden" name="id" value="<?php echo $vo->id ?>" />
<input type="hidden" name="_action" value="save_widget" />
<input type="hidden" name="seq" value="<?php echo $vo->seq ?>" />

<h3 class="introduction">Introduction</h3>
<p class="introduction">Widgets are demonstration objects.</p>

<h3>Core Details</h3>
<table border="0" cellspacing="4" style="margin-left:10px">
	<col width="140"/>
	<tr>
		<td class="fieldLabel_compulsory">Title:</td>
		<td><input type="text" name="title" class="compulsory" value="<?php echo htmlspecialchars((string)$vo->title); ?>" size="50" maxlength="100"/></td>
	</tr>
</table>

<h3>Document Access Control</h3>
<h4>Read</h4>
<div style="margin-left:10px"><?php
	if($_SESSION['user']->isAdmin())
	{
		$acl->renderList($link, 'acl_read', $acl->getIdentities('read'));
	}
	elseif(count($_SESSION['user']->getACLFilters()) > 0)
	{
		$acl->renderList($link, 'acl_read', $acl->getIdentities('read'), ACL::EMPLOYEES|ACL::EMPLOYEE_WILDCARDS);
	}
	else
	{
		echo '<p class="aclEntry">'.implode('</p><p class="aclEntry">', $acl->getIdentities('read')).'</p>';
	}
?></div>

<h4>Edit</h4>
<p class="sectionDescription">This example obey's the user's ACL filters (normally both read
and edit sections would use the filters, but I have disabled them for read to show the difference)</p>
<div style="margin-left:10px"><?php
	if($_SESSION['user']->isAdmin())
	{
		$acl->renderList($link, 'acl_write', $acl->getIdentities('write'));
	}
	elseif(count($_SESSION['user']->getACLFilters()) > 0)
	{
		$acl->renderList($link, 'acl_write', $acl->getIdentities('write'), ACL::EMPLOYEES|ACL::EMPLOYEE_WILDCARDS, $_SESSION['user']->getACLFilters());
	}
	else
	{
		echo '<p class="aclEntry">'.implode('</p><p class="aclEntry">', $acl->getIdentities('write')).'</p>';
	}
?></div>

</form>
</body>
</html>