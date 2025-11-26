<?php /* @var $view View */ ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
 "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Qualifications</title>
<link rel="stylesheet" href="/common.css" type="text/css"/>
<link rel="stylesheet" type="text/css" media="print" href="/print.css" />
<script language="javascript" src="/js/jquery.min.js" type="text/javascript"></script>
<script language="JavaScript" src="/common.js"></script>

	<link rel="stylesheet" href="/jquery-ui/css/jquery-ui-1.8.11.custom.css" type="text/css"/>
	<script language="JavaScript" src="/jquery-ui/js/jquery-1.5.2.min.js"></script>
	<script language="JavaScript" src="/jquery-ui/js/jquery-ui-1.8.11.custom.min.js"></script>
	<script language="JavaScript" src="/common.js"></script>

	<script type="text/javascript">

function save()
{
	var myForm = document.forms[0];
	if(validateForm(myForm) == false)
	{
		return false;
	}
	
	myForm.submit();
}

<?php echo $this->renderJavaScript($link, $vo); ?>

</script>

<style type="text/css">

td.fieldLabel_compulsory
{
	font-weight: bold;
	font-size: 10pt;
	color: #294586;
}
</style>

</head>
<body>
<div class="banner">
	<div class="Title">Edit Announcement</div>
	<div class="ButtonBar">
		<?php if($_SESSION['user']->type!=12){?>			
		<button onclick="save();">Save</button>
		<?php }?>
		<button onclick="window.location.href='<?php echo $_SESSION['bc']->getPrevious();?>';">Cancel</button>
	</div>

</div>

<h3 class="introduction">Introduction</h3>
<p class="sectionDescription">Use an announcement to release information to Sunesis users.</p>
<p class="sectionDescription">The Publication date of an announcement determines from which date it will be visible to the selected audience.</p>
<p class="sectionDescription">The Expiry date is an optional field that will stop the announcement from displaying after the specified date. Leaving this field blank will mean the announcement will continue to be displayed until the announcement is altered or removed.</p> 


<form name="form1" action="<?php echo $_SERVER['PHP_SELF'] ?>" method="post" >
<input type="hidden" name="_action" value="save_announcement" />
<input type="hidden" name="id" value="<?php echo $vo->id; ?>" />
<input type="hidden" name="organisations_id" value="<?php echo $vo->organisations_id; ?>" />
<input type="hidden" name="users_id" value="<?php echo $vo->users_id; ?>" />
<input type="hidden" name="created" value="<?php echo $vo->created; ?>" />
<input type="hidden" name="author" value="<?php echo $_SESSION['user']->username; ?>" />

<h3>General</h3>

<table border="0" cellpadding="4" cellspacing="4" style="margin-left:10px">
	<tr>
		<td width="140" class="fieldLabel_compulsory">Title*</td>
		<td><input class="compulsory" type="text" name="title" value="<?php echo htmlspecialchars((string)$vo->title); ?>" size="50" maxlength="50"/></td>
	</tr>
	<tr>
		<td width="140" class="fieldLabel_compulsory">Subtitle*</td>
		<td><input class="compulsory" type="text" name="subtitle" value="<?php echo htmlspecialchars((string)$vo->subtitle); ?>" size="50" maxlength="100"/></td>
	</tr>
	<tr>
		<td width="140" class="fieldLabel_compulsory">Publication date*</td>
		<td><?php echo HTML::datebox("publication_date", $vo->publication_date); ?></td>
	</tr>
	
	<tr>
		<td width="140" class="fieldLabel_compulsory">Expiry date*</td>
		<td><?php echo HTML::datebox("expiry_date", $vo->expiry_date); ?> <span style="color:gray;margin-left:10px">(the date to stop displaying this announcement)</span></td>
	</tr>

	<?php if($vo->created) {?>
	<tr>
		<td width="140" class="fieldLabel_compulsory">Created</td>
		<td><?php echo htmlspecialchars(Date::to($vo->created, Date::DATETIME)); ?></td>
	</tr>
	<?php } ?>
	<?php if($vo->modified) {?>
	<tr>
		<td width="140" class="fieldLabel_compulsory">Last modified</td>
		<td><?php echo htmlspecialchars(Date::to($vo->modified, Date::DATETIME)); ?></td>
	</tr>
	<?php } ?>
<!--	<tr>
		<td width="140" class="fieldLabel_compulsory">Author*</td>
		<td><?php //echo HTML::select('author', $author, $vo->author, false, false); ?></td>
	</tr>
-->
    <tr>
        <td width="140" class="fieldLabel_compulsory">Author*</td>
        <td><?php echo $_SESSION['user']->username; ?></td>
    </tr>
	<tr>
		<td width="140" class="fieldLabel_compulsory">Organisation</td>
		<td><?php echo htmlspecialchars((string)$vo->organisations_legal_name); ?></td>
	</tr>
</table>

<h3>Content</h3>
<p class="sectionDescription">Supports <?php Help::renderLink($link, "WikiFormatting", "wiki markup"); ?>.</p>


<textarea name="content" rows="20" style="width:580px;margin-left:10px"><?php echo htmlspecialchars((string)$vo->content); ?></textarea>

</form>

<!-- Popup calendar -->
<div id="calPop1" style="position:absolute;visibility:hidden;background-color:white;"></div>


</body>
</html>


