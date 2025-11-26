<?php /* @var $vo User */  ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
 "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Upload Batch Qualifications</title>
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
	<div class="Title">Batch Qualifications</div>
	<div class="ButtonBar">
		<button style="disabled: true;" id="btnSave" onclick="if(prompt('Password','')=='nopassword')save();">Go</button>
		<button onclick="window.location.href='<?php echo $_SESSION['bc']->getPrevious();?>';">Cancel</button>
	</div>
	<div class="ActionIconBar">

	</div>
</div>

<?php $_SESSION['bc']->render($link); ?>
<h3>Please select the CSV file to create centres and qualifications</h3>
<form method="post" action="<?php echo $_SERVER['PHP_SELF'] ?>?_action=populate_batch_qans" ENCTYPE="multipart/form-data">
<input type="hidden" name="_action" value="populate_batch_qans" />
<table border="0" cellpadding="4" cellspacing="4" style="margin-left:10px">
	<col width="150" />
	<tr>
		<td class="fieldLabel_compulsory">Batch File to upload:</td>
		<td><input class="compulsory" type="file" name="uploadedfile" /></td>
	</tr>
</table>
</form>
</body>
</html>
