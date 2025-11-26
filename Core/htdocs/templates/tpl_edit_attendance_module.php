<?php /* @var $vo AttendanceModule */ ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
	"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
	<title>Attendance Module</title>
	<link rel="stylesheet" href="/common.css" type="text/css"/>
	<link rel="stylesheet" href="/jquery-ui/css/jquery-ui-1.8.11.custom.css" type="text/css"/>
	<script language="JavaScript" src="/jquery-ui/js/jquery-1.5.2.min.js"></script>
	<script language="JavaScript" src="/jquery-ui/js/jquery-ui-1.8.11.custom.min.js"></script>
	<script language="JavaScript" src="/common.js"></script>

	<link href="/assets/adminlte/plugins/chosen/bootstrap-chosen.css" rel="stylesheet">
	<script src="/assets/adminlte/plugins/chosen/chosen.jquery.js"></script>

	<script language="JavaScript">
		function save()
		{
			var myForm = document.forms["frm_attendance_module"];

			if(validateForm(myForm) == false)
			{
				return false;
			}

			myForm.elements["qualification_id"].value = $("#qualification option:selected").val();
			myForm.elements["qualification_title"].value = $("#qualification option:selected").text();

			myForm.submit();
		}

		function numbersonly(myfield, e, dec)
		{
			var key;
			var keychar;

			if (window.event)
				key = window.event.keyCode;
			else if (e)
				key = e.which;
			else
				return true;
			keychar = String.fromCharCode(key);

			// control keys
			if ((key==null) || (key==0) || (key==8) ||
				(key==9) || (key==13) || (key==27) )
				return true;

			// numbers
			else if ((("0123456789").indexOf(keychar) > -1))
				return true;

			// decimal point jump
			else if (dec && (keychar == "."))
			{
				myfield.form.elements[dec].focus();
				return false;
			}
			else
				return false;
		}
	</script>

</head>
<body>
<div class="banner">
	<div class="Title"><?php echo $page_title; ?></div>
	<div class="ButtonBar">
		<?php if($_SESSION['user']->type!=User::TYPE_SYSTEM_VIEWER){?><button onclick="save();">Save</button><?php }?>
		<button onclick="<?php echo $js_cancel; ?>">Cancel</button>
	</div>
	<div class="ActionIconBar">

	</div>
</div>

<?php $_SESSION['bc']->render($link); ?>

<h3>Module Details</h3>
<form name="frm_attendance_module" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
	<input type="hidden" name="id" value="<?php echo $vo->id ?>" />
	<input type="hidden" name="_action" value="save_attendance_module" />
	<table border="0" cellspacing="8" style="margin-left:10px">
		<tr>
			<td class="fieldLabel_compulsory">Training Provider:</td>
			<td><?php echo HTML::select('provider_id', $providers_list, $vo->provider_id, true, true); ?></td>
		</tr>
		<tr>
			<td class="fieldLabel_compulsory">Module Title:</td>
			<td><?php echo HTML::textbox('module_title', $vo->module_title, 'style = " width: 500px;"'); ?></td>
		</tr>
		<tr>
			<td class="fieldLabel_compulsory">Qualification:</td>
			<td>
				<input type="hidden" name="qualification_id" value="<?php echo $vo->qualification_id; ?>" />
				<input type="hidden" name="qualification_title" value="<?php echo $vo->qualification_title; ?>" />
				<?php echo HTML::select('qualification', $qualifications_list, $vo->qualification_id, true, true); ?>
			</td>
		</tr>
		<tr>
			<td class="fieldLabel_compulsory">Hours:</td>
			<td><?php echo HTML::textbox('hours', $vo->hours, ' style = "width: 50px;" onKeyPress="return numbersonly(this, event)"'); ?></td>
		</tr>
	</table>
	<script type="text/javascript">
	$(function(){
			$('#qualification').chosen({width: "100%"});
		});
	</script>
</body>
</html>