<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
 "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Course</title>
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
	//if(element.checked)
	//	alert("Information:" +
	//		"\nSelecting all learners will move them to this new group.");
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

function learnersAlreadyAssigned()
{
	var form = document.forms['form1'];
	var checkboxes = form.elements['members[]'];
	var fields = form.elements['titles[]'];
	var learners = new Array();
	var groupName = document.forms['form1'].title.value; // get the group name this will be compared with the student groups in case of edit group mode. as if the student is belonging to the same group which is edited then it should not be notified.

	if (typeof fields !== 'undefined' && fields.length > 0)	// checking the learners (possible that there are no learners so far registered for the course)
	{
		for (var i = 0; i < fields.length; i++)
		{
			if (checkboxes[i].checked && fields[i].value != "NULL")
			{
				var groupNameOfLearner = fields[i].value.split("-");
				if (groupNameOfLearner[1] != groupName)
				{
					learners.push(fields[i].value);
					//alert("learner is added as its group name is "+groupNameOfLearner+" and the group to be edited is "+groupName);
				}
			}
		}
		if(learners.length > 0)
			return confirm("You have selected some student(s) who are already assigned to other groups. Do you wish to move those students to new group?");
		else
			return true;
	}
	else // if no learners are registered for the course then allow to create new group
		return true;

}

function save()
{
	if(!learnersAlreadyAssigned())
		return;
	else
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
<input type="hidden" name="courses_id" value="<?php echo $g_vo->courses_id ?>" />
<input type="hidden" name="old_tutor" value="<?php echo $g_vo->tutor ?>" />
<input type="hidden" name="old_assessor" value="<?php echo $g_vo->assessor?>" />
<input type="hidden" name="old_verifier" value="<?php echo $g_vo->verifier?>" />
<input type="hidden" name="_action" value="save_course_group" />

<?php $_SESSION['bc']->render($link); ?>

<br />

    <?php if(DB_NAME=='ams' || DB_NAME=='am_reed_demo' || DB_NAME=='am_reed')
    include "include_group_navigator.php";
else
    include "include_course_navigator.php";
    ?>

<h3>Group details</h3>
<p class="sectionDescription">Keep group names as short as possible in order
that they do not take up too much space in views. For example, if there is only one
group for the entire course then a suitable group name would be 'A'.</p>
<table  border="0" cellspacing="0" cellpadding="6" style="margin-left:10px">
	<tr>
		<td class="fieldLabel_compulsory">Group Name:</td>
		<td><input class="compulsory" type="text" name="title" value="<?php echo htmlspecialchars((string)$g_vo->title); ?>" maxlength="100"/></td>
	</tr>
	<?php if(DB_NAME=='ams' || DB_NAME=='am_reed_demo' || DB_NAME=='am_reed') { ?>
	<tr>
		<td class="fieldLabel_compulsory">Training Provider:</td>
		<td><?php echo HTML::select('training_provider', $training_providers, $g_vo->courses_id, true, true); ?></td>
	</tr>
	<?php } ?>
	<?php if(DB_NAME=='ams' || DB_NAME=='am_nordic') { ?>
	<tr>
		<td class="fieldLabel_optional">Key Skills Tutor:</td>
		<td><?php echo HTML::select('tutor', $tutor_select, $g_vo->tutor, true, false); ?></td>
	</tr>
	<tr>
		<td class="fieldLabel_optional">Employability Tutor:</td>
		<td><?php echo HTML::select('old_tutor', $tutor_select, $g_vo->old_tutor, true, false); ?></td>
	</tr>
	<?php } elseif(DB_NAME=='am_dv8training' || DB_NAME=='am_lewisham' || DB_NAME=='am_platinum' || DB_NAME=='am_doncaster') { ?>
	<tr>
		<td class="fieldLabel_optional">FS Tutor:</td>
		<td><?php echo HTML::select('tutor', $tutor_select, $g_vo->tutor, true, false); ?></td>
	</tr>
	<tr>
		<td class="fieldLabel_optional">Support Tutor:</td>
		<td><?php echo HTML::select('old_tutor', $tutor_select, $g_vo->old_tutor, true, false); ?></td>
	</tr>
	<?php } else { ?>
	<tr>
		<td class="fieldLabel_compulsory">Group FS Tutor:</td>
		<td><?php echo HTML::select('tutor', $tutor_select, $g_vo->tutor, true, true); ?></td>
	</tr>
	<?php } ?>

	<tr>
		<td class="fieldLabel_optional">Group Assessor:</td>
		<td><?php echo HTML::select('assessor', $assessor_select, $g_vo->assessor, true, false); ?></td>
	</tr>
	<tr>
		<td class="fieldLabel_optional">Group IQA:</td>
		<td><?php echo HTML::select('verifier', $verifier_select, $g_vo->verifier, true, false); ?></td>
	</tr>
	<tr>
		<td class="fieldLabel_optional">Group Capacity:</td>
		<td><input class="optional" type="text" id="group_capacity" name="group_capacity" value="<?php echo $g_vo->group_capacity; ?>" maxlength="4" onkeypress="return numbersonly();" /></td>
	</tr>

<?php if(SystemConfig::getEntityValue($link, "workplace")){ ?>
	<tr>
		<td class="fieldLabel_optional">Work Experience Coordinator:</td>
		<td><?php echo HTML::select('wbcoordinator', $wbcoordinator_select, $g_vo->wbcoordinator, true, false); ?></td>
	</tr>
<?php } ?>
    <?php if(DB_NAME=='am_reed_demo' || DB_NAME=='ams' || DB_NAME=='am_reed'){ ?>
    <tr>
        <td class="fieldLabel_optional">Start Date:</td>
        <td><?php echo HTML::datebox('start_date', $g_vo->start_date, true); ?></td>
    </tr>
    <tr>
        <td class="fieldLabel_optional">End Date:</td>
        <td><?php echo HTML::datebox('end_date', $g_vo->end_date, true); ?></td>
    </tr>
    <tr>
        <td class="fieldLabel_compulsory">Capacity:</td>
        <td><input class="compulsory" type="text" name="capacity" value="<?php echo htmlspecialchars((string)$g_vo->capacity); ?>" maxlength="100"/></td>
    </tr>
    <tr>
        <td class="fieldLabel_optional">Status:</td>
        <td><?php echo HTML::select('status', $statuses, $g_vo->status); ?></td>
    </tr>
    <?php } ?>
</table>
<br/>

<?php if(DB_NAME!='am_reed' || DB_NAME!='am_reed_demo') { ?>

<h3>Learners in this group</h3>
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
			. '</th><th>&nbsp;</th><th>Surname</th><th>Firstnames</th><th>Employer</th><th>Enrolment No.</th><th>Current Group</th></tr>';
		
		while($row = $st2->fetch())
		{
			if($row['is_member'] == '1')
			{
				$checkbox = '<input type="checkbox" name="members[]" value="' . $row['pot_id'] . '" checked="1" onclick="members_onclick(this)" />';
			}
			else
			{
				$checkbox = '<input type="checkbox" name="members[]" value="' . $row['pot_id'] . '" onclick="members_onclick(this)" />';
			}
			
			echo '<tr>';
			echo '<td align="left">' . $checkbox . '</td>';
			if($row['gender'] == 1)
			{
				echo '<td align="left"><img src="/images/folder-blue.png" /></td>';
			}
			else
			{
				echo '<td align="left"><img src="/images/folder-red.png" /></td>';
			}
			echo '<td align="left" style="font-style: italic; text-transform: uppercase">' . HTML::cell($row['surname']) . '</td>';
			echo '<td align="left">' . HTML::cell($row['firstnames']) . '</td>';
			echo '<td align="left">' . HTML::cell($row['short_name']) . '</td>';
			echo '<td align="left">' . HTML::cell($row['enrollment_no']) . '</td>';
			echo '<td align="left">' . HTML::cell($row['title']) . '</td>';
			if(!is_null($row['title']))
				echo "<input type='hidden' name='titles[]' value='" . $row["pot_id"] . "-" . $row["title"] . "' /> ";
			else
				echo "<input type='hidden' name='titles[]' value='NULL' /> ";
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

<?php } ?>

</form>
<p></p>

</body>
</html>

