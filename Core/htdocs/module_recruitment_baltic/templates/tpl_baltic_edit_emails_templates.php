<?php /* @var $vo Contract */ ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
	"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
	<title>Recruitment Module Email Templates</title>
	<link rel="stylesheet" href="/common.css" type="text/css"/>
	<script src="/js/jquery.min.js" type="text/javascript"></script>
	<script src="/common.js" type="text/javascript"></script>

	<link rel="stylesheet" href="/jquery-ui/css/jquery-ui-1.8.11.custom.css" type="text/css"/>
	<script language="JavaScript" src="/jquery-ui/js/jquery-1.5.2.min.js"></script>
	<script language="JavaScript" src="/jquery-ui/js/jquery-ui-1.8.11.custom.min.js"></script>
	<script language="JavaScript" src="/common.js"></script>

	<script language="JavaScript">
		function save()
		{
			var myForm = document.forms[0];
			if(validateForm(myForm) == false)
			{
				return false;
			}

			myForm.submit();
		}
	</script>

</head>
<body>
<div class="banner">
	<div class="Title">Recruitment Module Email Templates</div>
	<div class="ButtonBar">
		<?php if($_SESSION['user']->type!=12){?>
		<button onclick="save();">Save</button>
		<?php }?>
		<button onclick="window.location.href='<?php echo $_SESSION['bc']->getPrevious();?>';">Cancel</button>
	</div>
	<div class="ActionIconBar">

	</div>
</div>

<?php $_SESSION['bc']->render($link); ?>

<h3>Email Templates</h3>
<h4>Template 1</h4>
<form name="form1" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
	<input type="hidden" name="id" value="<?php echo $vo->id ?>" />
	<input type="hidden" name="_action" value="save_emails_templates" />
	<table border="0" cellspacing="4" style="margin-left:10px">
		<col width="170" />
		<tr>
			<td class="fieldLabel_compulsory">Email Type:</td>
			<td><input class="compulsory" type="text" name="title" value="" size="40" /></td>
		</tr>
		<tr>
			<td class="fieldLabel_compulsory">Email Type:</td>
			<td><input class="compulsory" type="text" name="title" value="" size="40" /></td>
		</tr>
		<tr>
			<td class="fieldLabel_compulsory">Email Type:</td>
			<td><input class="compulsory" type="text" name="title" value="" size="40" /></td>
		</tr>
	</table>
</body>
</html>