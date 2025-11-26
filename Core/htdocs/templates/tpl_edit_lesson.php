<?php /* @var $vo OrganisationVO */ ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
	"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
	<title>Lesson</title>
	<link rel="stylesheet" href="/common.css" type="text/css"/>
	<script src="/js/jquery.min.js" type="text/javascript"></script>
	<script src="/common.js" type="text/javascript"></script>

	<link rel="stylesheet" href="/jquery-ui/css/jquery-ui-1.8.11.custom.css" type="text/css"/>
	<script language="JavaScript" src="/jquery-ui/js/jquery-1.5.2.min.js"></script>
	<script language="JavaScript" src="/jquery-ui/js/jquery-ui-1.8.11.custom.min.js"></script>
	<script language="JavaScript" src="/common.js"></script>



	<script language="JavaScript">
		var registerEntriesExist = <?php echo (($l_vo->num_entries > 0) || ($l_vo->not_applicables > 0)) ? 'true' : 'false'; ?>;

		function save()
		{
			var myForm = document.forms[0];

			// General validation
			if(validateForm(myForm) == false)
			{
				return false;
			}

			// Detailed validation

			// If the lesson has already had a register taken
			// warn the user
			if(registerEntriesExist)
			{
				if(!confirm("This lesson has had a register taken for it. Continue?"))
				{
					return false;
				}
			}

			myForm.submit();
		}



		function body_onload()
		{
			var myForm = document.forms[0];
			/*
	  if(registerEntriesExist)
	  {
		  myForm.elements['groups_id'].disabled = true;
	  }
	  */
		}


	</script>

</head>
<body onload="body_onload()">
<div class="banner">
	<div class="Title">Edit Lesson</div>
	<div class="ButtonBar">
		<?php if($_SESSION['user']->type!=12){?>
		<button onclick="save();">Save</button>
		<?php }?>
		<button onclick="history.go(-1);">Cancel</button>
	</div>
	<div class="ActionIconBar">

	</div>
</div>

<form name="form1" action="<?php echo $_SERVER['PHP_SELF'] ?>" method="post">
	<input type="hidden" name="id" value="<?php echo $l_vo->id ?>" />
	<input type="hidden" name="group_id" value="<?php echo $g_vo->id ?>" />
	<input type="hidden" name="_action" value="save_lesson" />

	<table border="0" cellpadding="4" cellspacing="4" style="margin-left:10px">
		<?php if(DB_NAME!='am_reed_demo'  && DB_NAME!='am_reed') { ?>
		<tr>
			<td class="fieldLabel_optional">Provider:</td>
			<td class="fieldValue"><?php echo $p_vo->legal_name; ?></td>
		</tr>
		<tr>
			<td class="fieldLabel_optional">Course Title:</td>
			<td class="fieldValue"><?php echo $c_vo->title; ?></td>
		</tr>
		<?php } ?>
		<tr>
			<td class="fieldLabel_compulsory">Location:</td>
			<td><?php echo HTML::select('location', $locations, $l_vo->location, false, true); ?></td>
		</tr>
		<tr>
			<td class="fieldLabel_compulsory">Group:</td>
			<?php
			if( ($l_vo->num_entries > 0) || ($l_vo->not_applicables > 0) )
			{
				// Register entries exist for this lesson -- cannot change the group now
				echo '<td class="fieldValue">'.$g_vo->title;
				echo '<input type="hidden" name="groups_id" value="'.$g_vo->id.'" /></td>';
			}
			else
			{
				if(DB_NAME=='am_reed_demo'  || DB_NAME=='am_reed')
				{
					echo '<td class="fieldValue">'.$g_vo->title;
					echo '<input type="hidden" name="groups_id" value="'.$g_vo->id.'" /></td>';
				}
				else
					echo '<td>'.HTML::select('groups_id', $groups, $l_vo->groups_id, false, true).'</td>';
			}
			?>
		</tr>
		<tr>
			<td class="fieldLabel_compulsory">Date:</td>
			<td><?php echo HTML::datebox('date', $l_vo->date, true) ?></td>
		</tr>
		<tr>
			<td class="fieldLabel_compulsory">Start Time:</td>
			<td><?php echo HTML::timebox('start_time', $l_vo->start_time, true); ?>
				<span style="color:gray">(24 hour, HH:MM)</span></td>
		</tr>
		<tr>
			<td class="fieldLabel_compulsory">End Time:</td>
			<td><?php echo HTML::timebox('end_time', $l_vo->end_time, true); ?>
				<span style="color:gray">(24 hour, HH:MM)</span></td>
		</tr>

	</table>
</form>

<!-- Popup calendar -->
<div id="calPop1" style="position:absolute;visibility:hidden;background-color:white;"></div>

</body>
</html>