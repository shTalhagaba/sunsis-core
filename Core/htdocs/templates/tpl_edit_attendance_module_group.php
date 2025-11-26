<?php /* @var $g_vo AttendanceModuleGroupVO */ ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
	"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
	<title>Attendance Module Group</title>
	<link rel="stylesheet" href="/common.css" type="text/css"/>
	<script src="/js/jquery.min.js" type="text/javascript"></script>
	<script src="/common.js" type="text/javascript"></script>

	<link rel="stylesheet" href="/jquery-ui/css/jquery-ui-1.8.11.custom.css" type="text/css"/>
	<script language="JavaScript" src="/jquery-ui/js/jquery-1.5.2.min.js"></script>
	<script language="JavaScript" src="/jquery-ui/js/jquery-ui-1.8.11.custom.min.js"></script>
	<script language="JavaScript" src="/common.js"></script>

	<script language="JavaScript">

		function members_onclick(element)
		{
			var row = element.parentNode.parentNode;

			if(element.checked == true)
			{
				row.style.backgroundColor = 'orange';
			}
			else
			{
				row.style.backgroundColor = '';
			}
		}


		function selectAll_onclick(element)
		{


			var form = element.form;
			var checkboxes = form.elements['members[]'];

			for(var i = 0; i < checkboxes.length; i++)
			{
				checkboxes[i].checked = element.checked;
				members_onclick(checkboxes[i]);
			}
		}


		function body_onload()
		{
			var f = document.forms['form1'];
			var boxes = f.elements['members[]'];

			if(boxes!=null && boxes!='null')
			{
				for(var i = 0; i < boxes.length; i++)
				{
					members_onclick(boxes[i]);
				}

			}
		}

		function save()
		{
			var myForm = document.forms[0];

			if(validateForm(myForm) == true)
			{
				myForm.submit();
			}
			else
			{
				return false;
			}
		}

	</script>



</head>

<body onload="body_onload();">
<div class="banner">
	<div class="Title"><?php echo $g_vo->id == 0 ? 'New Teaching Group' : 'Teaching Group: ' . $g_vo->title ?></div>
	<div class="ButtonBar">
		<?php if($_SESSION['user']->type!=12){?>
		<button onclick="save();">Save</button>
		<?php }?>
		<button onclick="window.location.href='<?php echo $_SESSION['bc']->getPrevious();?>';">Cancel</button>
	</div>
	<div class="ActionIconBar">

	</div>
</div>

<form name="form1" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
	<input type="hidden" name="id" value="<?php echo $g_vo->id ?>" />
	<input type="hidden" name="module_id" value="<?php echo $g_vo->module_id ?>" />
	<input type="hidden" name="old_tutor" value="<?php echo $g_vo->tutor ?>" />
	<input type="hidden" name="old_assessor" value="<?php echo $g_vo->assessor?>" />
	<input type="hidden" name="old_verifier" value="<?php echo $g_vo->verifier?>" />
	<input type="hidden" name="_action" value="save_attendance_module_group" />

	<?php $_SESSION['bc']->render($link); ?>

	<br />

	<?php
		include "include_attendance_module_navigator.php";
	?>

	<h3>Group details</h3>
	<p class="sectionDescription">Keep group names as short as possible in order
		that they do not take up too much space in views. For example, if there is only one
		group for the entire course then a suitable group name would be 'A'.</p>
	<table  border="0" cellspacing="0" cellpadding="6" style="margin-left:10px">
		<tr>
			<td class="fieldLabel_compulsory">Group Name:</td>
			<td><input class="compulsory" type="text" name="title" value="<?php echo htmlspecialchars((string)$g_vo->title); ?>" size="50" maxlength="200"/></td>
		</tr>
		<?php if(DB_NAME != "am_lcurve") {?>
		<tr>
			<td class="fieldLabel_optional">Group FS Tutor:</td>
			<td><?php echo HTML::select('tutor', $tutor_select, $g_vo->tutor, true); ?></td>
		</tr>
		<tr>
			<td class="fieldLabel_optional">Group Assessor:</td>
			<td><?php echo HTML::select('assessor', $assessor_select, $g_vo->assessor, true); ?></td>
		</tr>
		<?php } ?>
	</table>
	<br/>

	<?php if($g_vo->id == 0) { ?>
	<h3>Select Learners</h3>
	<?php } else { ?>
	<h3>Learners in this group</h3>
	<?php } ?>
	<div align="left">
		<table class="resultset" border="0" style="margin-left:10px" cellspacing="0" cellpadding="6">
			<?php
			$sql  = $view->getSQLStatement()->__toString();
			$st2 = $link->query($sql);
			if($st2)
			{
				if($st2->rowCount() > 0)
				{
					echo '<tr><th><input type="checkbox" name="selectAll" value="" onclick="selectAll_onclick(this);" />'
							. '</th><th>&nbsp;</th><th>Surname</th><th>Firstnames</th><th>Employer</th><th>Enrolment No. </th><th>Training Period</th><th>Qualification Start Date</th><th>Learner Group(s)</th><th>Course</th></tr>';


					while($row = $st2->fetch())
					{
						if(isset($row['is_member']) && $row['is_member'] == '1')
						{
							$checkbox = '<input type="checkbox" name="members[]" value="' . $row['pot_id'] . '" checked="1" onclick="members_onclick(this)" />';
						}
						else
						{
							$checkbox = '<input type="checkbox" name="members[]" value="' . $row['pot_id'] . '" onclick="members_onclick(this)" />';
						}
						echo '<tr>';
						echo '<td align="left">' . $checkbox . '</td>';
						if($row['gender'] == 'M')
						{
							echo '<td align="left"><img src="/images/boy-blonde-hair.gif" /></td>';
						}
						elseif($row['gender'] == 'F')
						{
							echo '<td align="left"><img src="/images/girl-black-hair.gif" /></td>';
						}
						else
							echo '<td align="left"></td>';
						echo '<td align="left" style="font-style: italic; text-transform: uppercase">' . HTML::cell($row['surname']) . '</td>';
						echo '<td align="left">' . HTML::cell($row['firstnames']) . '</td>';
						echo '<td align="left">' . HTML::cell($row['short_name']) . '</td>';
						echo '<td align="left">' . HTML::cell($row['enrollment_no']) . '</td>';
						echo '<td align="left"><small>' . HTML::cell(Date::to($row['start_date'], Date::SHORT) . ' to ' . Date::to($row['target_date'], Date::SHORT)) . '</small></td>';
						echo '<td align="left">' . HTML::cell(Date::to($row['qual_start_date'], Date::SHORT)) . '</td>';
						$learner_exiting_groups = DAO::getSingleValue($link, "SELECT GROUP_CONCAT(attendance_module_groups.title SEPARATOR ', ') FROM attendance_module_groups INNER JOIN group_members ON attendance_module_groups.id = group_members.groups_id AND group_members.tr_id = " . $row['tr_id']);
						echo '<td align="left">' . HTML::cell($learner_exiting_groups) . '</td>';
						echo '<td align="left">' . HTML::cell($row['course']) . '</td>';
						echo '</tr>';

					}
				}
			}
			else
			{
				throw new DatabaseException($link, $sql);
			}
			?>
		</table>
	</div>


</form>
<p></p>

</body>
</html>

