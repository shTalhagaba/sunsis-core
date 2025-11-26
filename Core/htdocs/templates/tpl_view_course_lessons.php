<?php /* @var $view View */ ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
	"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Course Lessons</title>
<link rel="stylesheet" href="/common.css" type="text/css"/>
<link rel="stylesheet" type="text/css" media="print" href="/print.css" />
<script src="/js/jquery.min.js" type="text/javascript"></script>
<script src="/common.js" type="text/javascript"></script>

<link rel="stylesheet" href="/jquery-ui/css/jquery-ui-1.8.11.custom.css" type="text/css"/>
<script language="JavaScript" src="/jquery-ui/js/jquery-1.5.2.min.js"></script>
<script language="JavaScript" src="/jquery-ui/js/jquery-ui-1.8.11.custom.min.js"></script>
<script language="JavaScript" src="/common.js"></script>

<script language="JavaScript">


function validateFilters(form)
{
	//var f = document.forms[0];

	for(var i = 0; i < form.elements.length; i++)
	{
		if(form.elements[i].validate && (form.elements[i].validate() == false) )
		{
			return false;
		}
	}

	return true;
}

function addLessons(event)
{
	var f = document.forms['addLesson'];
	f.elements['_action'].value = "add_course_lesson";
	// GROUP
	if(f.elements['groups_id'].value == '')
	{
		alert("Lessons can only be scheduled for existing groups - please select a group (you will need to create a group first if none yet exist)");
		f.elements['groups_id'].focus();
		stopEvent(event);
		return false;
	}

	// DATE
	if(f.elements['date'].validate() == false)
	{
		stopEvent(event);
		return false;
	}
	if(f.elements['date'].value == '')
	{
		alert("Please enter a date");
		f.elements['date'].focus();
		stopEvent(event);
		return false;
	}

	// START TIME
	var regTime = /^(\d\d):(\d\d)$/;
	var hours;
	var minutes;
	if(matches = f.elements['start_time'].value.match(regTime))
	{
		hours = parseInt(matches[1]);
		minutes = parseInt(matches[2]);
		if(hours < 0 || hours > 24 || minutes < 0 || minutes > 59)
		{
			alert("Please enter the start time in 24 hour format HH:MM");
			f.elements['start_time'].focus();
			stopEvent(event);
			return false;
		}
	}
	else
	{
		alert("Please enter the start time in 24 hour format HH:MM");
		f.elements['start_time'].focus();
		stopEvent(event);
		return false;
	}

	// END TIME
	var regTime = /^(\d\d):(\d\d)$/;
	var hours;
	var minutes;
	if(matches = f.elements['end_time'].value.match(regTime))
	{
		hours = parseInt(matches[1]);
		minutes = parseInt(matches[2]);
		if(hours < 0 || hours > 24 || minutes < 0 || minutes > 59)
		{
			alert("Please enter the end time in 24 hour format HH:MM");
			f.elements['end_time'].focus();
			stopEvent(event);
			return false;
		}
	}
	else
	{
		alert("Please enter the end time in 24 hour format HH:MM");
		f.elements['end_time'].focus();
		stopEvent(event);
		return false;
	}

	// SCHEDULE
	var numToAdd = parseInt(f.elements['_number_to_add'].value);
	if(isNaN(numToAdd))
	{
		alert("Please enter the number of lessons to add");
		f.elements['_number_to_add'].focus();
		stopEvent(event);
		return false;
	}

	// LOCATION
	if(f.elements['location'].value == '')
	{
		if(f.elements['location'].options.length == 1)
		{
			alert("No locations have been entered for this training provider."
				+ " You will need to create one or more locations before you can schedule lessons.");
			stopEvent(event);
			return false;
		}
		else
		{

			alert("Please select a location.");
			f.elements['location'].focus();
			stopEvent(event);
			return false;
		}
	}

	// TUTOR
	if(f.elements['tutor'].value == '')
	{
		if(f.elements['tutor'].options.length == 1)
		{
			alert("No personnel have been entered for this training provider."
				+ " You will need to create one or more personnel entries before you can schedule lessons.");
			stopEvent(event);
			return false;
		}
		else
		{

			alert("Please select a tutor.");
			f.elements['tutor'].focus();
			stopEvent(event);
			return false;
		}
	}

<?php if(DB_NAME=="am_reed" || DB_NAME=="am_reed_demo" || DB_NAME=="ams"){ ?>
	// Qualification check
	if(f.elements['qualification'].value == '')
	{
		alert("Qualification is required to create the lessons. Please select the qualification.");
		f.elements['qualification'].focus();
		stopEvent(event);
		return false;
	}
	//Module check
	if(f.elements['module'].value == '')
	{
		alert("Module is required to create the lessons. Please select the module.");
		f.elements['module'].focus();
		stopEvent(event);
		return false;
	}

	<?php } ?>

	// Create lessons
	f.submit();
}




function editLessons(event)
{
	var f = document.forms['addLesson'];
	f.elements['_action'].value = "edit_course_lessons";

	var lessons = f.elements['lessons[]'];
	var numSelected = 0;
	var registersTaken = false;

	if(getInternetExplorerVersion() == -1) // If not IE
	{
		if(lessons instanceof HTMLInputElement)
		{
			if(lessons[i].checked)
			{
				numSelected++;
				registersTaken = f.elements['lessons'].hasRegister || registersTaken; // Flag
			}
		}
		else
		{
			for(var i = 0; i < lessons.length; i++)
			{
				if(lessons[i].checked)
				{
					numSelected++;
					registersTaken = lessons[i].hasRegister || registersTaken; // Flag
				}
			}
		}
	}
	else // If IE
	{
		var objType = lessons.toString.call(lessons);
		if(objType != '[object HTMLInputElement]')
		{
			for(var i = 0; i < lessons.length; i++)
			{
				if(lessons[i].checked)
				{
					numSelected++;
					registersTaken = lessons[i].hasRegister || registersTaken; // Flag
				}
			}
		}
		else
		{
			if(lessons[i].checked)
			{
				numSelected++;
				registersTaken = f.elements['lessons'].hasRegister || registersTaken; // Flag
			}
		}
	}

	if(numSelected == 0)
	{
		alert("Please select one or more lessons to edit");
		stopEvent(event);
		return false;
	}

	if(registersTaken)
	{
		if(!confirm("One or more of the selected lessons have had registers taken for them. Are you sure you want to continue?"))
		{
			stopEvent(event);
			return false;
		}
	}

	var changes = '';

	if(f.elements['groups_id'].value != '')
	{
		changes += "  Group = '" + f.elements['groups_id'].options[f.elements['groups_id'].selectedIndex].text + "'\r\n";
	}
	if(f.elements['location'].value != '')
	{
		changes += "  Location = '" + f.elements['location'].options[f.elements['location'].selectedIndex].text + "'\r\n";
	}
	if(f.elements['tutor'].value != '')
	{
		changes += "  Tutor = '" + f.elements['tutor'].options[f.elements['tutor'].selectedIndex].text + "'\r\n";
	}
	if(f.elements['start_time'].value != '')
	{
		changes += "  Start time = '" + f.elements['start_time'].value + "'\r\n";
	}
	if(f.elements['end_time'].value != '')
	{
		changes += "  End time = '" + f.elements['end_time'].value + "'\r\n";
	}
	if(f.elements['qualification'].value != '')
	{
		changes += "  Qualification = '" + f.elements['qualification'].value + "'\r\n";
	}
	if(f.elements['module'].value != '')
	{
		changes += "  Module = '" + document.getElementById('module').options[f.elements['module'].selectedIndex].text + "'\r\n";
	}


	if(changes.length == 0)
	{
		alert("Please enter amendments into one or more of: group, location, tutor, start time, end time.");
		stopEvent(event);
		return false;
	}


	if(confirm("Amend " + numSelected + " lesson(s), making the following changes?\r\n\r\n" + changes))
	{
		f.submit();
	}
	else
	{
		stopEvent(event);
		return false;
	}
}


function deleteLessons(event)
{
	var f = document.forms['addLesson'];
	f.elements['_action'].value = "delete_course_lessons";

	var lessonCheckboxes = f.elements['lessons[]'];
	if(!lessonCheckboxes.length)
	{
		// A single lesson on the page is returned as an HTMLInputElement
		// Wrap it in an array to allow the loop below to process it
		lessonCheckboxes = new Array(lessonCheckboxes);
	}

	// Iterate through all checkboxes
	var numSelected = 0;
	for(var i = 0; i < lessonCheckboxes.length; i++)
	{
		if(lessonCheckboxes[i].checked)
		{
			numSelected++;
			if(lessonCheckboxes[i].hasRegister == true)
			{
				alert("One or more of your selected lessons have register entries. You cannot delete lessons that learners have attended.");
				lessonCheckboxes[i].focus();
				stopEvent(event);
				return false;
			}
		}
	}



	if(numSelected == 0)
	{
		alert("Please select one or more lessons to delete");
		stopEvent(event);
		return false;
	}


	if(confirm("Delete " + numSelected + " lesson(s)?"))
	{
		f.submit();
		//return false;
	}
	else
	{
		stopEvent(event);
		return false;
	}
}


function getInternetExplorerVersion()
{
	var rv = -1;
	if (navigator.appName == 'Microsoft Internet Explorer')
	{
		var ua = navigator.userAgent;
		var re  = new RegExp("MSIE ([0-9]{1,}[\.0-9]{0,})");
		if (re.exec(ua) != null)
			rv = parseFloat( RegExp.$1 );
	}
	else if (navigator.appName == 'Netscape')
	{
		var ua = navigator.userAgent;
		var re  = new RegExp("Trident/.*rv:([0-9]{1,}[\.0-9]{0,})");
		if (re.exec(ua) != null)
			rv = parseFloat( RegExp.$1 );
	}
	return rv;
}


function div_filter_crumbs_onclick(div)
{
	showHideBlock(div);
	showHideBlock('div_filters');
}


function lessons_onclick(element, event)
{
	var row = element.parentNode.parentNode;

	if(element.checked == true)
	{
		row.style.backgroundColor = row.oldBackgroundColor = 'orange';
	}
	else
	{
		row.style.backgroundColor = row.oldBackgroundColor = '';
	}

	// Stop the event from reaching the table row
	stopEvent(event);
}


function selectAll_onclick(element)
{
	var form = element.form;
	var checkboxes = form.elements['lessons[]'];

	for(var i = 0; i < checkboxes.length; i++)
	{
		checkboxes[i].checked = element.checked;
		lessons_onclick(checkboxes[i]);
	}
}


function stopEvent(event)
{
	if(event != null)
	{
		if(event.stopPropagation)
		{
			event.stopPropagation(); // DOM 2
		}
		else
		{
			event.cancelBubble = true; // IE
		}
	}
}

</script>

</head>

<body>
<div class="banner">
	<div class="Title">Course: Lessons</div>
	<div class="ButtonBar">
		<button onclick="window.location.href='<?php echo $_SESSION['bc']->getPrevious();?>';">Close</button>
		<button onclick="showHideBlock('div_addLesson');">Batch Add/Edit/Delete lessons</button>
	</div>
	<div class="ActionIconBar">
		<button onclick="showHideBlock('div_filters');showHideBlock('div_filter_crumbs');" title="Show/hide filters"><img src="/images/btn-filter.gif" width="16" height="16" style="vertical-align:text-bottom" /></button>
		<button onclick="window.print()" title="Print-friendly view"><img src="/images/btn-printer.gif" width="16" height="16" style="vertical-align:text-bottom" /></button>
		<button onclick="window.location.reload(false);" title="Refresh view (see the latest changes from other users)"><img src="/images/btn-refresh.gif" width="16" height="16" style="vertical-align:text-bottom" /></button>
	</div>
</div>

<?php $_SESSION['bc']->render($link); ?>

<br />


<?php if(DB_NAME=='ams' || DB_NAME=='am_reed_demo' || DB_NAME=='am_reed')
	include "include_group_navigator.php";
else
	include "include_course_navigator.php";
?>


<!--  Filters  -->
<form method="GET" action="<?php echo $_SERVER['PHP_SELF'] ?>" onsubmit="return validateFilters(this);">
	<?php //echo $view->getFilterCrumbs() ?>
	<div id="div_filters" style="display:none;" >
		<input type="hidden" name="course_id" value="<?php echo $course_id ?>" />
		<input type="hidden" name="_action" value="view_course_lessons" />

		<table border="0">
			<tr>
				<td>Group:</td>
				<td><?php echo $view->getFilterHTML('filter_group'); ?></td>
			</tr>
			<tr>
				<td>Location:</td>
				<td><?php echo $view->getFilterHTML('filter_location'); ?></td>
			</tr>
			<tr>
				<td>FS Tutor:</td>
				<td><?php echo $view->getFilterHTML('filter_tutor'); ?></td>
			</tr>
			<tr>
				<td>Dates:</td>
				<td>from <?php echo $view->getFilterHTML('start_date'); ?>
					&nbsp;&nbsp;&nbsp;&nbsp;to <?php echo $view->getFilterHTML('end_date'); ?></td>
			</tr>
			<tr>
				<td>Lessons per page: </td>
				<td><?php echo $view->getFilterHTML(View::KEY_PAGE_SIZE); ?></td>
			</tr>
			<tr>
				<td>Sort by:</td>
				<td><?php echo $view->getFilterHTML(View::KEY_ORDER_BY); ?></td>
			</tr>
			<tr>
				<td><input type="submit" value="Go"/>&nbsp;<input type="button" onclick="resetViewFilters(this.form);" value="Reset" /></td>
			</tr>
		</table>
</form>
</div>


<!-- Add/Edit/Delete lesson h ,hjform -->
<form name="addLesson" action="<?php echo $_SERVER['PHP_SELF'] ?>" method="post">
	<input type="hidden" name="course_id" value="<?php echo $course_id; ?>" />
	<?php if(DB_NAME=='ams' || DB_NAME=='am_reed_demo' || DB_NAME=='am_reed') {?>
	<input type="hidden" name="group_id" value="<?php echo $group_id; ?>" />
	<?php } ?>
	<input type="hidden" name="_action" value="add_course_lesson" />

	<div id="div_addLesson" align="center" style="margin-top:10px;margin-bottom:20px;<?php echo $showPanel == '1'?'display:block':'display:none'; ?>">
		<table border="0" style="margin:10px;background-color:silver; border:1px solid black;padding:3px; border-radius: 15px;">
			<tr>
				<td align="right">Location:</td><td colspan="2"><?php echo HTML::select('location', $locations, $lesson_location, true); ?></td>
				<td align="right">Group:</td><td>
				<?php
				if(DB_NAME=='ams' || DB_NAME=='am_reed_demo' || DB_NAME=='am_reed')
					echo HTML::select('groups_id', $groupnames2, $lesson_group, false, true);
				else
					echo HTML::select('groups_id', $groupnames, $lesson_group, true);
				?></td>
				<td align="right">Start Time:</td><td><input type="text" name="start_time" value="<?php echo $lesson_start_time ?>" size="5" maxlength="5"/></td>
				<td align="right">End Time:</td><td><input type="text" name="end_time" value="<?php echo $lesson_end_time ?>" size="5" maxlength="5"/></td>
			</tr>
			<tr>
				<td align="right">FS Tutor:</td><td colspan="2"><?php echo HTML::select('tutor', $personnel, $lesson_tutor, true); ?></td>
			</tr>
			<?php if(DB_NAME=="am_reed" || DB_NAME=="am_reed_demo" || DB_NAME=="ams"){ ?>
			<tr>
				<td align="right">Qualification:</td><td colspan="2"><?php echo HTML::select('qualification', $qualifications, $lesson_tutor, true, true); ?></td>
				<td align="right">Module:</td><td colspan="1"><?php echo HTML::select('module', $modules, '', true, true); ?></td>
			</tr>
			<?php } else {?>
			<tr>
				<td align="right">Qualification:</td><td colspan="2"><?php echo HTML::select('qualification', $qualifications, $lesson_tutor, true); ?></td>
			</tr>
			<?php } ?>
			<tr>
				<td colspan="9"><div style="border:1px outset silver;padding:3px;background-color:#CCCCCC">
					<button onclick="return addLessons(arguments.length > 0 ? arguments[0] : window.event);">Add</button>
					<input type="text" name="_number_to_add" size="2" maxlength="2" value="<?php echo $lesson_number_to_add ?>"/>
					lesson(s) beginning <?php echo HTML::datebox('date', $lesson_date); ?>
					and recurring
					<?php echo HTML::select('_frequency', $frequency_options, $lesson_frequency, false); ?>
					thereafter</div></td>
			</tr>
			<tr>
				<td colspan="9" align="left"><div style="border:1px outset silver;padding:3px;background-color:#CCCCCC">
					<table cellspacing="0" cellpadding="0" border="0" width="100%">
						<tr>
							<td align="left"><button onclick="return editLessons(arguments.length > 0 ? arguments[0] : window.event);">Amend</button> selected lessons</td>
							<td align="right"><button style="color:red" onclick="return deleteLessons(arguments.length > 0 ? arguments[0] : window.event);">Delete</button> selected lessons</td>
						</tr>
					</table>
				</div>
				</td>
			</tr>
		</table>
	</div>




	<div align="center" style="margin-top:30px;">
		<?php echo $view->getViewNavigator(); ?>
		<table class="resultset" border="0" cellpadding="6" cellspacing="0">
			<tr>
				<th><input type="checkbox" name="selectAll" value="" onclick="selectAll_onclick(this);" /></th>
				<th>R</th>
				<th>Grp</th>
				<th colspan="2">Date</th>
				<th colspan="2">Period</th>
				<th>Site</th>
				<th>Qualification</th>
				<th>FS Tutor</th>
				<th>Module</th>
			</tr>

			<?php
			$query = $view->getSQLStatement()->__toString();
			//echo "<p>$query</p>";
			$st = $link->query($query);

			if($st)
			{
				while($row = $st->fetch())
				{
					//echo "<tr onclick=\"window.location.href='do.php?_action=edit_lesson&id={$row['id']}';\" style=\"cursor:pointer\">";
					echo HTML::viewrow_opening_tag("do.php?_action=edit_lesson&id={$row['id']}");

					if($row['num_entries'] > 0)
					{
						echo <<<HEREDOC
<td onclick="stopEvent(arguments.length > 0 ? arguments[0] : window.event);">
<input type="checkbox" id="lessons_{$row['id']}" name="lessons[]" value="{$row['id']}"
onclick="lessons_onclick(this, arguments.length > 0 ? arguments[0] : window.event);" />
<script language="JavaScript">document.getElementById("lessons_{$row['id']}").hasRegister = true;</script>
</td>
<td><a title="View register #{$row['id']}" href="do.php?_action=read_register&lesson_id={$row['id']}"><img src="/images/clipboard16x16.gif" border="0"/></a></td>
HEREDOC;
					}
					else
					{
						echo <<<HEREDOC
<td onclick="stopEvent(arguments.length > 0 ? arguments[0] : window.event);">
<input type="checkbox" id="lessons_{$row['id']}" name="lessons[]" value="{$row['id']}"
onclick="lessons_onclick(this, arguments.length > 0 ? arguments[0] : window.event)" />
<script language="JavaScript">document.getElementById("lessons_{$row['id']}").hasRegister = false;</script>
</td>
<td><a title="View register #{$row['id']}" href="do.php?_action=read_register&lesson_id={$row['id']}"><img src="/images/clipboard16x16.gif" border="0"/></a></td>
HEREDOC;
					}
					echo '<td align="center">' . HTML::cell($row['title']) . '</td>';
					echo '<td align="left">' . HTML::cell($row['day']) . '</td>';
					echo '<td align="left">' . HTML::cell($row['date']) . '</td>';
					echo '<td align="left">' . HTML::cell($row['start_time']) . '</td>';
					echo '<td align="left">' . HTML::cell($row['end_time']) . '</td>';
					echo '<td align="left">' . HTML::cell($row['location_name']) . '</td>';
					echo '<td align="left">' . HTML::cell($row['qualification']) . '</td>';
					echo '<td align="left">' . HTML::cell($row['tutor']) . '</td>';
					echo '<td align="left">' . HTML::cell($row['module']) . '</td>';
					echo '</tr>';
				}

			}
			else
			{
				throw new DatabaseException($link, $query);
			}
			?>
		</table>
		<?php echo $view->getViewNavigator(); ?>
	</div>

</form>

<!-- Popup calendar -->
<div id="calPop1" style="position:absolute;visibility:hidden;background-color:white;"></div>

</body>
</html>