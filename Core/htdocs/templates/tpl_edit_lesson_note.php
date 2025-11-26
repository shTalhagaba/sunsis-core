<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
	"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
	<title>Lesson Note</title>
	<link rel="stylesheet" href="/common.css" type="text/css"/>
	<script src="/js/jquery.min.js" type="text/javascript"></script>
	<script src="/common.js" type="text/javascript"></script>

	<script language="JavaScript">
		function save()
		{
			var myForm = document.forms[0];
			var btnSave = document.getElementById('btnSave');

			if(validateForm(myForm) == false)
			{
				return false;
			}

			btnSave.disabled = true;

			var client = ajaxPostForm(myForm);
			if(client != null)
			{
				window.location.replace('do.php?_action=read_register&lesson_id=<?php echo $vo->lessons_id; ?>');
			}

			btnSave.disabled = false;
		}

		function public_onclick(element)
		{
			var grid = document.getElementById('grid_readers');

			if(element.value == 1)
			{
				grid.clear();
				grid.disable();
				grid.style.color = 'gray';
			}
			else
			{
				grid.reset();
				grid.setValues("<?php echo $_SESSION['user']->employer_id; ?>" );
				grid.enable();
				grid.style.color = 'black';
			}
		}


		function body_onload()
		{
			var public_0 = document.getElementById("public_0");
			var public_1 = document.getElementById("public_1");
			var grid = document.getElementById('grid_readers');

			if(public_1.checked)
			{
				grid.clear();
				grid.disable();
				grid.style.color = 'gray';
			}
			else
			{
				grid.reset();
				grid.enable();
				grid.style.color = 'black';
			}
		}
	</script>

</head>
<body onload="body_onload()">
<div class="banner">
	<div class="Title">Edit Note</div>
	<div class="ButtonBar">
		<?php if($_SESSION['user']->type!=12){?>
		<button id="btnSave" onclick="save();">Save</button>
		<?php }?>
		<button onclick="window.location.replace('do.php?_action=read_register&lesson_id=<?php echo $vo->lessons_id; ?>')">Cancel</button>
	</div>
	<div class="ActionIconBar">

	</div>
</div>

<form name="form1" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
	<input type="hidden" name="id" value="<?php echo $vo->id ?>" />
	<input type="hidden" name="lessons_id" value="<?php echo $vo->lessons_id ?>" />
	<input type="hidden" name="_action" value="add_lesson_note" />
	<table border="0" style="margin-left:10px" cellspacing="4" cellpadding="4">
		<col width="100"/>
		<tr>
			<td class="fieldLabel">Author:</td>
			<td class="fieldValue"><?php echo $vo->firstnames.' '.$vo->surname.' (<code>'.$vo->username.'</code>) @ '.$vo->organisation_name.' ' ?></td>
		</tr>
		<tr>
			<td class="fieldLabel">Created:</td>
			<td class="fieldValue"><?php echo date('D, d M Y H:i:s T', strtotime($vo->created)); ?></td>
		</tr>
		<tr>
			<td class="fieldLabel_compulsory">Subject:</td>
			<td><input style="font-family:monospace" class="compulsory" type="text" name="subject" value="<?php echo htmlspecialchars((string)$vo->subject) ?>" size="45" maxlength="100"/></td>
		</tr>
		<tr>
			<td class="fieldLabel_compulsory" valign="top">Note:</td>
			<td><textarea style="font-family:monospace" name="note" cols="45" rows="15"><?php echo htmlspecialchars((string)$vo->note); ?></textarea></td>
		</tr>
		<tr>
			<td class="fieldLabel_compulsory" valign="top">Readership:</td>
			<td><fieldset id="readership_fieldset">
				<legend><input type="radio" id="public_1" name="public" value="1" onclick="public_onclick(this);" <?php if(count($vo->readers) == 0){echo 'checked="checked"';} ?>/>Public
					<input style="margin-left: 10px" type="radio" id="public_0" name="public" value="0" onclick="public_onclick(this);" <?php if(count($vo->readers) > 0){echo 'checked="checked"';} ?> />Private (selected organisations only)</legend>
				<?php //echo HTML::checkboxGrid('readers', $readers_dropdown, $vo->readers, 4, (count($vo->readers) > 0)); ?>
			</fieldset>
			</td>
		</tr>
	</table>


</form>
</body>
</html>