<?php /* @var $vo OrganisationVO */ ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
 "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Help Page</title>
<link rel="stylesheet" href="/styles/common.css" type="text/css"/>
<link rel="stylesheet" href="/styles/dynamicStyles.php" type="text/css"/>
<link rel="stylesheet" href="/styles/jquery-ui/jquery-ui-1.8.11.custom.css" type="text/css"/>

<script language="JavaScript" src="/scripts/jquery-1.5.2.min.js"></script>
<script language="JavaScript" src="/scripts/jquery-ui-1.8.11.custom.min.js"></script>
<script language="JavaScript" src="/scripts/common.js"></script>
	
<script language="JavaScript">
var recordId = "<?php echo $vo->id; ?>";

$(function(){
	// No way to select a parent in CSS, so we use jQuery
	// to alter the margins around a paragraph containing a single image
	$('div.Wiki > p > img').closest('p').each(function(){
		if($(this).text() == ""){
			$(this).css('margin-bottom', '2em').css('margin-left', '20px');
		}
	});
});

function deleteRecord()
{
	if(window.confirm("Delete this record?"))
	{
		window.location.replace('do.php?_action=delete_help&id=' + recordId);
	}
}

function editRecord()
{
	window.location.replace('do.php?_action=edit_help&id=' + recordId);
}
</script>
	
<style type="text/css">
div.Wiki
{
	border: #ffe7a7 solid 2px;
	border-radius: 12px;
	width: 540px;
	background-color: #fffdf6;
	/*padding: 0px 10px 10px 0px;*/
}

img.FloatingImage
{
	float: left;
	margin: 0px 10px 2px 0px;
}
</style>

</head>

<body>
<?php
if (! preg_match ( '/MSIE [1-6]/', $_SERVER ['HTTP_USER_AGENT'] ) && ($_SESSION ['screen_width'] >= 1024)) { ?>
<div class="RightMenu">
<div class="RightMenuTitle">Content</div>
<div class="RightMenuItem">- <a href=""
	onclick="document.getElementById('sectionGeneral').scrollIntoView(true);return false">Default</a></div>
<div class="RightMenuItem">- <a href=""
	onclick="document.getElementById('sectionIdentifiers').scrollIntoView(true);return false">Sys Admin</a></div>
<div class="RightMenuItem">- <a href=""
	onclick="document.getElementById('sectionMappings').scrollIntoView(true);return false">Partnership</a></div>
<div class="RightMenuItem">- <a href=""
	onclick="document.getElementById('sectionConfig').scrollIntoView(true);return false">Provider</a></div>
<div class="RightMenuItem">- <a href=""
	onclick="document.getElementById('sectionStatus').scrollIntoView(true);return false">School</a></div>
	
<div class="RightMenuTitle">Actions</div>
<div class="RightMenuItem">- <a href="" onclick="window.history.go(-1);return false">Close</a></div>
<div class="RightMenuItem">- <a href="" onclick="editRecord();return false">Edit</a></div>
</div>
<?php } ?>
<div class="banner">
	<div class="Title">Help Page</div>
	<div class="Timestamp"><?php echo date('D, d M Y H:i:s T'); ?></div>
	<div class="ButtonBar"><?php
		echo HTML::button("Close", "history.back()");
   		echo HTML::button("Edit", "editRecord();");
   		echo HTML::button("Delete", "deleteRecord();"); ?></div>
	<div class="ActionIconBar"><?php echo Help::renderIcon($link, __FILE__); ?><img src="/images/btn-printer.gif" class="ActionIcon" onclick="window.print()" width="25" height="25"  />
		<img src="/images/btn-refresh.gif" class="ActionIcon" onclick="window.location.reload(false);" width="25" height="25" /></div>
	<div class="banner_end"></div>
</div>

<h3 class="introduction">Introduction</h3>
<p class="introduction"><img src="/images/help-icon-9696.png" width="96" height="96" class="FloatingImage" />
CLM's online help content is organised into pages each referenced by a unique CamelCase key (e.g. <span style="font-style:normal;font-family:monospace">SifIntroduction</span>).
The key is used when creating links between help pages and as a means of associating help content
with CLM pages. Where a page key matches the name of a CLM page action, e.g. <span style="font-style:normal;font-family:monospace">ViewTrainingRecords</span>,
the help page will be accesible via a help icon on the CLM pages's button bar.</p>

<h3 id="sectionGeneral">General</h3>
<table border="0" cellspacing="4" cellpadding="4" style="margin-left:10px" width="590">
<col width="140"/>
	<tr><td class="fieldLabel">Key:</td><td class="fieldValue" style="font-family:monospace"><?php echo htmlspecialchars((string)$vo->key); ?></td></tr>
	<tr><td class="fieldLabel">Title:</td><td class="fieldValue"><?php echo htmlspecialchars((string)$vo->title); ?></td></tr>
	<tr><td class="fieldLabel">Redirect to page:</td><td class="fieldValue" style="font-family:monospace"><?php
		if($vo->key_redirect){
			echo '<a href="do.php?_action=read_help&id='.rawurlencode($vo->key_redirect).'">'.htmlspecialchars((string)$vo->key_redirect).'</a>'; 
		}?></td></tr>
</table>

<h4>Timestamps</h4>
<table border="0" cellspacing="4" cellpadding="4" style="margin-left:10px" width="590">
<col width="140"/>
	<tr><td class="fieldLabel">Created:</td><td class="fieldValue"><?php echo Date::to($vo->created, Date::DATETIME); ?></td></tr>
	<tr><td class="fieldLabel">Modified:</td><td class="fieldValue"><?php echo Date::to($vo->modified, Date::DATETIME); ?></td></tr>
</table>

<h3 id="sectionIdentifiers">Content</h3>
<p class="sectionDescription">The default content will be displayed to users where no more specific alternative exists.</p>
<h4>Default</h4>
<?php $this->renderContent($vo, $vo->default_content); ?>

<h4>System Administrators</h4>
<?php $this->renderContent($vo, $vo->admin_content); ?>

<h4>Partnership Coordinators</h4>
<?php $this->renderContent($vo, $vo->partnership_content); ?>

<h4>Lesson Provider Users</h4>
<?php $this->renderContent($vo, $vo->provider_content); ?>

<h4>Home School Users</h4>
<?php $this->renderContent($vo, $vo->school_content); ?>

</body>
</html>
