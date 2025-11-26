<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
 "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
	<title>Learner Review</title>
	<link rel="stylesheet" href="/common.css" type="text/css"/>
	<script src="/js/jquery.min.js" type="text/javascript"></script>
	<script src="/common.js" type="text/javascript"></script>
	
<!-- Calendar popup: credit to Matt Kruse (www.javascripttoolbox.com) -->
<script language="JavaScript" src="/calendarPopup/CalendarPopup.js"></script>

<!-- Initialise calendar popup -->
<script language="JavaScript">
<?php if(preg_match('/MSIE [1-6]/', $_SERVER['HTTP_USER_AGENT']) ) { ?>
	var calPop = new CalendarPopup();
	calPop.showNavigationDropdowns();
<?php } else { ?>
	var calPop = new CalendarPopup("calPop1");
	calPop.showNavigationDropdowns();
	document.write(getCalendarStyles());
<?php } ?>


function entry_onclick(radio)
{
	var td = radio.parentNode;
	var tr = td.parentNode;

	var inputs = tr.getElementsByTagName("td");

	for(var i = 5; i < 8; i++)
	{
		if(inputs[i].tagName == 'TD')
		{
			if(inputs[i].className=='redd')
				inputs[i].className='redl';
			
			if(inputs[i].className=='greend')
				inputs[i].className='greenl';

			if(inputs[i].className=='yellowd')
				inputs[i].className='yellowl';
		}
	}

	if(td.className=='redl')
		td.className='redd';
	
	if(td.className=='greenl')
		td.className='greend';

	if(td.className=='yellowl')
		td.className='yellowd';
}

function savereview(count)
{

	xml = '<reviews>';
	for(a=1;a<=count; a++)
	{
		if(document.getElementById('input_meeting_'+a).value!='dd/mm/yyyy')
		{
			myForm = document.forms[0];
			
			buttons = myForm.elements["traffic" +a];
			rowSelected = false;
			for(var i = 0; i < buttons.length; i++)
			{
				if(buttons[i].checked == true)
				{
					trafficxml = "<traffic>" + buttons[i].value + "</traffic>";
					rowSelected = true;
					break;
				}
			}
				
			if(rowSelected == false)
			{
				alert("Missing attendance data for row " + a);
				buttons[0].focus();
				return false;
			}

			xml += "<review>";
			xml += "<date>" + document.getElementById('input_meeting_'+a).value + "</date>";
			xml += trafficxml;
			xml += "<assessor>" + document.getElementById('assessor_'+a).value + "</assessor>";
			xml += "<comment>" + "NA" + "</comment>";
			xml += "</review>";
		}
		else
		{
			break;
		}
	}
	xml += "</reviews>";
	
	//frequency = document.getElementById('frequency')[document.getElementById('frequency').selectedIndex].value;

	frequency = <?php echo $weeks; ?>;

	var request = ajaxBuildRequestObject();
	if(request != null)
	{
	
		var postData = 'tr_id=' + <?php echo $tr_id; ?>
			+ '&frequency=' + frequency
			+ '&weeks=' + <?php echo $weeks; ?>
			+ '&xml=' + xml;

		//alert(postData.substring(0, 200));
		request.open("POST", expandURI('do.php?_action=save_assessor_review'), false); // (method, uri, synchronous)
		request.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
		request.setRequestHeader("x-ajax", "1"); // marker for server code
		request.send(postData);
	}
}


function save(count)
{

	xml = '<reviews>';
	for(a=1;a<=count; a++)
	{
		if(document.getElementById('input_meeting_'+a).value!='dd/mm/yyyy')
		{
			myForm = document.forms[0];
			
			buttons = myForm.elements["traffic" +a];
			rowSelected = false;
			for(var i = 0; i < buttons.length; i++)
			{
				if(buttons[i].checked == true)
				{
					trafficxml = "<traffic>" + buttons[i].value + "</traffic>";
					rowSelected = true;
					break;
				}
			}
				
			if(rowSelected == false)
			{
				alert("Missing attendance data for row " + a);
				buttons[0].focus();
				return false;
			}

			xml += "<review>";
			xml += "<date>" + document.getElementById('input_meeting_'+a).value + "</date>";
			xml += trafficxml;
			xml += "<assessor>" + document.getElementById('assessor_'+a).value + "</assessor>";
			xml += "<comment>" + "NA" + "</comment>";
			xml += "<paperwork>" + document.getElementById('paperwork_'+a).checked + "</paperwork>";
			xml += "<assessorcomments>" + htmlspecialchars(document.getElementById('comments'+a).value) + "</assessorcomments>";
			xml += "</review>";
		}
		else
		{
			break;
		}
	}
	xml += "</reviews>";

	//frequency = document.getElementById('frequency')[document.getElementById('frequency').selectedIndex].value;

	frequency = <?php echo $weeks; ?>;

	var postData = 'tr_id=' + <?php echo $tr_id; ?>
		+ '&frequency=' + frequency
		+ '&weeks=' + <?php echo $weeks; ?>
		+ '&xml=' + encodeURIComponent(xml);

	var request = ajaxRequest('do.php?_action=save_assessor_review', postData);
			
	if(request.status == 200)
	{
		// SUCCESS
		//var debug = document.getElementById("debug");
		//debug.textContent = request.responseText;
		//return false;

		window.location.replace(<?php echo "'" . $_SESSION['bc']->getPrevious() . "'"; ?>);
	}
	else
	{
		alert(request.responseText);
	}
}

function showComments(s)
{
	s.title = document.getElementById("comments"+s.id).value;	
	showHideBlock(document.getElementById("comments"+s.id));
}

</script>
</head>
<style type="text/css">
.label
{
	font-weight:bold;
}

.download
{
	background-color:red;
}

.Action
{
	cursor:pointer;
}

td.greenl
{
	background-image:url('/images/trafficlight-green.jpg');
	background-color:white;
	background-repeat: no-repeat;
	background-position: center;
	opacity: 0.2;
	filter: alpha(opacity=20); 	
}

td.redl
{
	background-image:url('/images/trafficlight-red.jpg');
	background-color:white;
	background-repeat: no-repeat;
	background-position: center;	
	opacity: 0.2;
	filter: alpha(opacity=20); 	
}

td.yellowl
{
	background-image:url('/images/trafficlight-yellow.jpg');
	background-color:white;
	background-repeat: no-repeat;
	background-position: center;	
	opacity: 0.2;
	filter: alpha(opacity=20); 	
}

td.greend
{
	background-image:url('/images/trafficlight-green.jpg');
	background-color:white;
	background-repeat: no-repeat;
	background-position: center;
	opacity: 1;
	filter: alpha(opacity=100); 	
}

td.redd
{
	background-image:url('/images/trafficlight-red.jpg');
	background-color:white;
	background-repeat: no-repeat;
	background-position: center;	
	opacity: 1;
	filter: alpha(opacity=100); 	
}

td.yellowd
{
	background-image:url('/images/trafficlight-yellow.jpg');
	background-color:white;
	background-repeat: no-repeat;
	background-position: center;	
	opacity: 1;
	filter: alpha(opacity=100); 	
}

</style>



<body>
<div class="banner">
	<div class="Title">Learner Review</div>
	<div class="ButtonBar">
		<button onclick="window.location.href='<?php echo $_SESSION['bc']->getPrevious();?>';">Close</button>
		<?php if($_SESSION['user']->type!=12){?>
		<button onclick="save(<?php echo sizeof($master_date);?>);">Save</button>
		<?php }?>
		<?php if(DB_NAME=='am_raytheon' || DB_NAME=='am_demo' || DB_NAME=='ams' || DB_NAME=='am_learningworld' || DB_NAME=='am_peopleserve' || DB_NAME=='am_superdrug' || DB_NAME=='am_accenture' || DB_NAME == 'am_midkent' || DB_NAME == 'am_portsmouth' ){?>
			<button onclick="window.location.replace('do.php?tr_id=<?php echo $tr_id; ?>&_action=word_assessor_review_form');">Learner Review Form</button>
		<?php }?>
	</div>
	<div class="ActionIconBar">
		<button onclick="window.print()" title="Print-friendly view"><img src="/images/btn-printer.gif" width="16" height="16" style="vertical-align:text-bottom" /></button>
		<button onclick="window.location.reload(false);" title="Refresh view (see the latest changes from other users)"><img src="/images/btn-refresh.gif" width="16" height="16" style="vertical-align:text-bottom" /></button>
	</div>
</div>

<?php $_SESSION['bc']->render($link); ?>

<h3>Learner</h3>
<table border="0" cellspacing="4" cellpadding="4" style="margin-left:10px">
	<col width="150" /><col />
	<tr>
		<td class="fieldLabel">Surname:</td>
		<td class="fieldValue"><?php echo htmlspecialchars((string)$training_record->surname); ?></td>
		<td class="fieldLabel">Firstname(s):</td>
		<td class="fieldValue"><?php echo htmlspecialchars((string)$training_record->firstnames); ?></td>
	</tr>
	<tr>
		<td class="fieldLabel">Start Date:</td>
		<td class="fieldValue"><?php echo htmlspecialchars(Date::toMedium($training_record->start_date)); ?></td>
		<td class="fieldLabel">Projected End Date</td>
		<td class="fieldValue"><?php echo htmlspecialchars(date::toMedium($training_record->target_date)); ?></td>
	</tr>
	<tr>
		<td class="fieldLabel">Review frequency:</td>
		<td class="fieldValue"><?php echo htmlspecialchars((string)$weeks) . " Weeks"; ?></td>
	</tr>
</table>	

<!-- <h3>Review frequency</h3>
<p class='sectionDescription'>
Please save this screen to generate the modified assessor review table after you change the review frequency. 
</p>
  
<table border="0" cellspacing="4" cellpadding="4" style="margin-left:10px">
	<col width="150" /><col />
	<tr>
		<td class="fieldLabel_compulsory">Review Frequency</td>
		<td><?php //echo HTML::select('frequency', $frequency_dropdown, $weeks, false, true); ?></td>
	</tr>
</table>	
-->
<h3>Learner Reviews</h3>
<form name="assessor">
<?php
echo '<div align="left"><table class="resultset" border="0" cellspacing="0" cellpadding="6">';
echo '<thead><tr><th>&nbsp;</th><th>Review <br> Meetings</th><th>Review meeting<br>due on</th><th>Review meeting<br> held on</th><th>Assessor</th><th>G</th><th>Y</th><th>R</th><th>Paperwork<br> Received</th><th>Comments</th></thead>';
echo '<tbody>';
$count = 1;
$last_meeting_held = '';
foreach($master_date as $meeting)
{	
	$i = 'meeting_'.$count;
	$j = 'assessor_'.$count;
	$k = 'comment_'.$count;
	$l = 'paperwork_'.$count;
	if($count<=$c)
	{
		
		$projected_end_date = new Date($training_record->target_date);
		$duedate = new Date($meeting);
		$actual_date = new Date($meeting_date[$count]);
		$actualstyle='';
		$duestyle='';
		if($duedate->getDate() > $projected_end_date->getDate())
		{
			$duestyle = "style = 'background-color: orange'";		
		}
		if($actual_date->getDate() > $projected_end_date->getDate())
		{
			$actualstyle = "style = 'background-color: orange'";		
		}
			
		echo "<tr>";
		echo "<td>&nbsp;</td><td align='center'>" . $count;  
		echo "</td><td align='center' $duestyle >" . $meeting . "</td>";
		echo "<td $actualstyle>" . HTML::datebox($i, $meeting_date[$count], true) . "</td>";
		echo "<td>" . HTML::select($j, $assessor_select, $assessor[$count], true, true) . "</td>";
		
		if($comments[$count]=='green')
			echo '<td align="center" class="greend" width="32"><input type="radio" checked value="green" name="traffic' .$count . '" title="Satisfactory" onclick="entry_onclick(this, arguments.length > 0 ? arguments[0] : window.event);"/></td>';
		else
			echo '<td align="center" class="greenl" width="32"><input type="radio" value="green" name="traffic' .$count . '" title="Satisfactory" onclick="entry_onclick(this, arguments.length > 0 ? arguments[0] : window.event);"/></td>';
		
		if($comments[$count]=='yellow')
			echo '<td align="center" class="yellowd" width="32"><input type="radio" checked value="yellow" name="traffic' .$count . '" title="Average" onclick="entry_onclick(this, arguments.length > 0 ? arguments[0] : window.event);"/></td>';
		else
			echo '<td align="center" class="yellowl" width="32"><input type="radio" value="yellow" name="traffic' .$count . '" title="Average" onclick="entry_onclick(this, arguments.length > 0 ? arguments[0] : window.event);"/></td>';
		
		if($comments[$count]=='red')
			echo '<td align="center" class="redd" width="32"><input type="radio" checked value="red" name="traffic' .$count . '" title="Dissatifactory" onclick="entry_onclick(this, arguments.length > 0 ? arguments[0] : window.event);"/></td>';
		else
			echo '<td align="center" class="redl" width="32"><input type="radio" value="red" name="traffic' .$count . '" title="Dissatifactory" onclick="entry_onclick(this, arguments.length > 0 ? arguments[0] : window.event);"/></td>';

		$checked = ($paperwork_received[$count]==1)?"checked":"";
		echo "<td align=center><input type='checkbox' id ='" . $l . "' name ='" . $l . "' " . $checked . "/>&nbsp;</td>";

		echo "<td style='vetical-align: middle'><table><tr><td><span title='" . $assessor_comments[$count] . "' class='button' id=" . $count . " onclick='showComments(this);'>+/-</span></td><td><textarea  rows=3 cols=30 style='display: none;' id='comments" . $count . "'>" . $assessor_comments[$count] . "</textarea></td></tr></table></td>"; 
		
	}
	else
	{
		
		$projected_end_date = new Date($training_record->target_date);
		$duedate = new Date($meeting);
		$style='';
		if($duedate->getDate() > $projected_end_date->getDate())
		{
			$style = "style = 'background-color: orange'";		
		}
		
		
		echo "<tr><td>&nbsp;</td><td align='center'>" . $count;
		echo "</td><td $style align='center'>" . Date::toShort($meeting) . "</td>";
		echo "<td>" . HTML::datebox($i, '', true) . "</td>";
		echo "<td>" . HTML::select($j, $assessor_select, '', true, true) . "</td>";

		echo '<td align="center" class="greenl" width="32"><input type="radio" value="green" name="traffic' .$count . '" title="Satisfactory" onclick="entry_onclick(this, arguments.length > 0 ? arguments[0] : window.event);"/></td>';
		echo '<td align="center" class="yellowl" width="32"><input type="radio" value="yellow" name="traffic' .$count . '" title="Average" onclick="entry_onclick(this, arguments.length > 0 ? arguments[0] : window.event);"/></td>';
		echo '<td align="center" class="redl" width="32"><input type="radio" value="red" name="traffic' .$count . '" title="Dissatifactory" onclick="entry_onclick(this, arguments.length > 0 ? arguments[0] : window.event);"/></td>';

			$checked = '';
			echo "<td align=center><input type='checkbox' id ='" . $l . "' name ='" . $l . "' " . $checked . "/>&nbsp;</td>";
		if( $c == 0 ) 
		{ 
			$last_meeting_held=''; 
		} 
		else 
		{ 
			$last_meeting_held = $d; 
		} // ICK fix
		
		?>

		<?php 
		
		echo "<td style='vetical-align: middle'><table><tr><td><span title=' ' class='button' id=" . $count . " onclick='showComments(this);'>+/-</span></td><td><textarea  rows=3 cols=30 style='display: none;' id='comments" . $count . "'>" . "</textarea></td></tr></table></td>"; 
				
		echo "</tr>";
		
	}	

	$count++;
}

echo "</tbody></table></div>";
?>
</form>

<!-- Popup calendar -->
<div id="calPop1" style="position:absolute;visibility:hidden;background-color:white;"></div>

</body>
</html>
