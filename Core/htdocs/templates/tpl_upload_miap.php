<?php /* @var $vo User */  ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
 "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Person</title>
<link rel="stylesheet" href="/common.css" type="text/css"/>
<script src="/js/jquery.min.js" type="text/javascript"></script>
<script src="/common.js" type="text/javascript"></script>


<script language="JavaScript">
function save()
{
	var myForm = document.forms[0];
	
	myForm.submit();
	
	
}



</script>

</head>
<body>
<div class="banner">
	<div class="Title">Update ULNs</div>
	<div class="ButtonBar">
		<?php if($_SESSION['user']->type!=12){?>
		<button id="btnSave" onclick="save();">Update</button>
		<?php }?>
		<button onclick="window.location.href='<?php echo $_SESSION['bc']->getPrevious();?>';">Cancel</button>
	</div>
	<div class="ActionIconBar">

	</div>
</div>

<?php $_SESSION['bc']->render($link); ?>

<h3>Please select file to upload</h3>
<form method="post" action="<?php echo $_SERVER['PHP_SELF'] ?>?_action=save_upload_miap" ENCTYPE="multipart/form-data">
<input type="hidden" name="_action" value="save_upload_miap" />
<table border="0" cellpadding="4" cellspacing="4" style="margin-left:10px">
	<col width="150" />
	<tr>
		<td class="fieldLabel_compulsory">File to upload:</td>
		<td><input class="compulsory" type="file" name="uploadedfile" /></td>
	</tr>
</table>
</form>
</body>
</html>
