<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
	"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
	<title>Vacancy Question</title>
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

		function type_onchange(ele)
		{
			if(ele.value == '1')
			{
				$('#lblSectorID').attr('class', 'fieldLabel_compulsory')
				$('#sector_id').attr('class', 'compulsory validate[required]')
			}
			else
			{
				$('#lblSectorID').attr('class', 'fieldLabel_optional')
				$('#sector_id').attr('class', 'optional')
			}
		}
	</script>

</head>
<body>
<div class="banner">
	<div class="Title">Vacancy Question</div>
	<div class="ButtonBar">
		<button onclick="save();">Save</button>
		<button onclick="window.location.href='<?php echo $_SESSION['bc']->getPrevious(); ?>';">Cancel</button>
	</div>
	<div class="ActionIconBar">

	</div>
</div>

<?php $_SESSION['bc']->render($link); ?>

<h3>Details</h3>
<form name="frmVacancyQuestion" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
	<input type="hidden" name="id" value="<?php echo $id; ?>" />
	<input type="hidden" name="_action" value="rec_edit_question" />
	<input type="hidden" name="subaction" value="save_record" />

	<table border="0" cellspacing="8" style="margin-left:10px">
		<tr valign="top">
			<td class="fieldLabel_compulsory">Type:</td>
			<td><?php echo HTML::select('type', $types, $type, false, true); ?></td>
		</tr>
		<tr valign="top">
			<td class="fieldLabel_optional" id="lblSectorID">Type:</td>
			<td><?php echo HTML::select('sector_id', $sector_types, $sector_id, true); ?></td>
		</tr>
		<tr valign="top">
			<td class="fieldLabel_compulsory">Description:</td>
			<td><textarea class="compulsory" rows="10" cols="50" id="description" name="description"><?php echo $question_desc; ?></textarea></td>
		</tr>
	</table>

</body>
</html>