<?php /* @var $vo OrganisationVO */ ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
 "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Edit Help</title>
<link rel="stylesheet" href="/styles/common.css" type="text/css"/>
<link rel="stylesheet" href="/styles/dynamicStyles.php" type="text/css"/>
<script language="JavaScript" src="/scripts/jquery-1.5.2.min.js"></script>
<script language="JavaScript" src="/scripts/common.js"></script>

<script language="JavaScript">

// jQuery initialisation
$(function(){

	// Determine whether default_content field should be compulsory
	if($('input[name="key_redirect"]').val()){
		$('textarea[name="default_content"]').removeClass("compulsory");
	} else {
		$('textarea[name="default_content"]').addClass("compulsory");
	}
	

	// Enforce proper formatting of the page key
	$('input[name="key"]').blur(function(e){
		$(this).val( $(this).val().replace(/\b(.)/g, function(str, p1, offset, s){return p1.toUpperCase();}) );
		$(this).val( $(this).val().replace(/[^A-Za-z]/g, ''));
	});
	
	$('input[name="key_redirect"]').blur(function(e){
		$(this).val( $(this).val().replace(/\b(.)/g, function(str, p1, offset, s){return p1.toUpperCase();}) );
		$(this).val( $(this).val().replace(/[^A-Za-z]/g, ''));
		if($(this).val()){
			$('textarea[name="default_content"]').removeClass("compulsory");
		} else {
			$('textarea[name="default_content"]').addClass("compulsory");
		}
	});
	
});


function save()
{
	if (window.saveLock) {
		return;
	}
	
	window.saveLock = true;

	var myForm = document.forms[0];

	// General validation
	if(validateForm(myForm) == false)
	{
		window.saveLock = false;
		return;
	}
	
	// Validation
	client = ajaxPostForm(myForm);
	if(client != null)
	{
		window.location.replace('do.php?_action=read_help&id=' + client.responseText);
		return;
	}
	
	window.saveLock = false;
}

function previewContent(content)
{
	if(!content){
		return;
	}
	
	var title = document.forms[0].elements['title'].value;
	displayHelp(null, title, content);
}

function launchFileManager()
{
	window.open("do.php?_action=file_manager", "filemanager", "width=1000,height=500,scrollbars=yes");
}
</script>

<style type="text/css">
td.DefaultValue
{
	color: gray;
	text-align: right;
	font-size: 8pt;
}

span.PreviewLink
{
	color: orange;
	font-weight: normal;
	font-size: 10pt;
	cursor: pointer;
	letter-spacing: normal;
	padding: 2px;
	border: 1px solid #ffcd62;
}

span.PreviewLink:hover
{
	background-color: #ffcd62;
	color: white;
}

h4
{
	padding-bottom: 3px;
}

img.FloatingImage
{
	float: left;
	margin: 0px 10px 2px 0px;
}
</style>

</head>
<body>
<div class="banner">
	<div class="Title"><?php echo $help->id == "" ? "New Help Page" : "Edit Help Page";?></div>
	<div class="Timestamp"><?php echo date('D, d M Y H:i:s T'); ?></div>
	<div class="ButtonBar"><?php
		echo HTML::button("Save", "save();");
		echo HTML::button("Cancel", $js_cancel);
		echo HTML::button("File Manager", "launchFileManager()"); ?></div>
	<div class="ActionIconBar"><?php echo Help::renderIcon($link, __FILE__); ?></div>
	<div class="banner_end"></div>
</div>

<h3 class="introduction">Introduction</h3>
<p class="introduction"><img src="/images/help-icon-9696.png" width="96" height="96" class="FloatingImage" />
CLM's online help content is organised into pages each referenced by a unique CamelCase key (e.g. <span style="font-style:normal;font-family:monospace">SifIntroduction</span>).
The key is used when creating links between help pages and as a means of associating help content
with CLM pages. Where a page key matches the name of a CLM page action, e.g. <span style="font-style:normal;font-family:monospace">ViewTrainingRecords</span>,
the help page will be accesible via a help icon on the CLM pages's button bar.</p>


<form name="form1" action="<?php echo $_SERVER['PHP_SELF'] ?>" method="post">
<input type="hidden" name="_action" value="save_help" />
<input type="hidden" name="id" value="<?php echo $help->id; ?>" />

<h3>General</h3>
<p class="sectionDescription">Keys should be in CamelCase, with at least two capital letters.</p>
<table border="0" cellspacing="4" style="margin-left:10px">
<col width="130"/>
	<tr>
		<td class="fieldLabel_compulsory">Key:</td>
		<td><input class="compulsory" type="text" name="key" value="<?php echo htmlspecialchars((string)$help->key); ?>" size="40" maxlength="100" />
		<span style="color:gray;font-style:italic;margin-left:10px">CamelCase</span></td>
	</tr>
	<tr>
		<td class="fieldLabel_compulsory">Title:</td>
		<td><input class="compulsory" type="text" name="title" value="<?php echo htmlspecialchars((string)$help->title); ?>" size="40" maxlength="100" /></td>
	</tr>
	<tr>
		<td class="fieldLabel_optional">Redirect to page:</td>
		<td><input class="optional" type="text" name="key_redirect" value="<?php echo htmlspecialchars((string)$help->key_redirect); ?>" size="40" maxlength="100" />
		<span style="color:gray;font-style:italic;margin-left:10px">CamelCase</span></td>
	</tr>
</table>

<h3>Content</h3>
<p class="sectionDescription">You are recommended to use <b>Firefox 4</b>, <b>Chrome</b> or <b>Safari</b> for content authoring. Each of these
browsers will allow you to resize the fields below to your own preference.</p>

<h4><table cellpadding="0" cellspacing="0" border="0" width="590">
<tr><td align="left">Default</td>
<td align="right"><span class="PreviewLink" onclick="previewContent(document.forms[0].elements['default_content'].value); return false;">Preview</span></td>
</tr></table></h4>
<textarea name="default_content" rows="15" style="width:580px;margin-left:10px"><?php echo htmlspecialchars((string)$help->default_content); ?></textarea>

<h4><table cellpadding="0" cellspacing="0" border="0" width="590">
<tr><td align="left">System Administrators</td>
<td align="right"><span class="PreviewLink" onclick="previewContent(document.forms[0].elements['admin_content'].value); return false;">Preview</span></td>
</tr></table></h4>
<textarea name="admin_content" rows="3" style="width:580px;margin-left:10px"><?php echo htmlspecialchars((string)$help->admin_content); ?></textarea>

<h4><table cellpadding="0" cellspacing="0" border="0" width="590">
<tr><td align="left">Partnership Coordinators</td>
<td align="right"><span class="PreviewLink" onclick="previewContent(document.forms[0].elements['partnership_content'].value); return false;">Preview</span></td>
</tr></table></h4>
<textarea name="partnership_content" rows="3" style="width:580px;margin-left:10px"><?php echo htmlspecialchars((string)$help->partnership_content); ?></textarea>

<h4><table cellpadding="0" cellspacing="0" border="0" width="590">
<tr><td align="left">Lesson Provider Users</td>
<td align="right"><span class="PreviewLink" onclick="previewContent(document.forms[0].elements['provider_content'].value); return false;">Preview</span></td>
</tr></table></h4>
<textarea name="provider_content" rows="3" style="width:580px;margin-left:10px"><?php echo htmlspecialchars((string)$help->provider_content); ?></textarea>

<h4><table cellpadding="0" cellspacing="0" border="0" width="590">
<tr><td align="left">Home School Users</td>
<td align="right"><span class="PreviewLink" onclick="previewContent(document.forms[0].elements['school_content'].value); return false;">Preview</span></td>
</tr></table></h4>
<textarea name="school_content" rows="3" style="width:580px;margin-left:10px"><?php echo htmlspecialchars((string)$help->school_content); ?></textarea>

</form>

</body>
</html>
