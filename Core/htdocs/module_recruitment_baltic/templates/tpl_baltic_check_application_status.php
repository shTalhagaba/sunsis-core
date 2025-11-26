<?php /* @var $view View */ ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
	"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Check Application Status</title>

<script src="/js/jquery.min.js" type="text/javascript"></script>
<script src="/common.js" type="text/javascript"></script>

	<link rel="stylesheet" href="/jquery-ui/css/jquery-ui-1.8.11.custom.css" type="text/css"/>
	<script language="JavaScript" src="/jquery-ui/js/jquery-1.5.2.min.js"></script>
	<script language="JavaScript" src="/jquery-ui/js/jquery-ui-1.8.11.custom.min.js"></script>
	<script language="JavaScript" src="/common.js"></script>

<link rel="stylesheet" href="/common.css" type="text/css"/>
<link rel="stylesheet" type="text/css" media="print" href="/print.css" />

	<script>
		function submitForm()
		{
			var myForm = document.forms['frm_status'];
			var first_name = myForm.elements['first_name'].value;
			var surname = myForm.elements['surname'].value;
			var dob = myForm.elements['dob'].value;
			var email = myForm.elements['email'].value;


			if (first_name == '' || surname == ''|| dob == ''|| email == '')
			{
				alert("Please provide complete information.");
				return;
			}
			else
			{
				myForm.submit();
			}
		}

	</script>

</head>

<body>
<div class="banner">
	<div class="Title">Check Application Status</div>
	<div class="ButtonBar">
	</div>
	<div class="ActionIconBar">
	</div>
</div>


<div id="maincontent">

	<div align="left" style="margin-top:50px;">
		<?php if(!isset($result)) { ?>
		<table border="0" cellspacing="4" cellpadding="4" style="margin-left:10px">
			<form name="frm_status" action="<?php echo $_SERVER['PHP_SELF'].'?_action=baltic_check_application_status' ?>" method="post" autocomplete="off">
				<input type="hidden" name="form_submitted" value="1" />
				<col width="150" />
				<tr><td class="fieldLabel">Enter First Name:</td><td><input type="text" name="first_name" value="" /></td></tr>
				<tr><td class="fieldLabel">Enter Surname:</td><td><input type="text" name="surname" value="" /></td></tr>
				<tr><td class="fieldLabel">Enter Date of Birth:</td><td><?php echo HTML::datebox('dob', '', true); ?></td></tr>
				<tr><td class="fieldLabel">Enter Email:</td><td><input type="text" name="email" value="" /></td></tr>
				<tr><td align="center" colspan="2"><button type="button" onclick="submitForm();">Check Status</button> </td> </tr>
			</form>
		</table>
		<?php
		}
		else
		{
			echo $result;
		}
		?>
	</div>
</div>
<noscript>
	<?php include_once('templates/tpl_noscript.php'); ?>
</noscript>
</body>
</html>
