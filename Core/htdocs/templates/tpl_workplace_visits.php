<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
 "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
	<title>Assessor Review</title>
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



function save(count)
{

	xml = '<evidences>';
	for(a=1;a<=count; a++)
	{
	
		if(document.getElementById('input_start_date'+a).value!='dd/mm/yyyy')
		{		
			xml += "<evidence>";
			xml += "<workplace_id>" + document.getElementById('workplace_id'+a).value + "</workplace_id>";
			xml += "<start_date>" + document.getElementById('input_start_date'+a).value + "</start_date>";
			xml += "<end_date>" + document.getElementById('input_end_date'+a).value + "</end_date>";
			xml += "<comments>" + document.getElementById('comments'+a).value + "</comments>";
			xml += "</evidence>";
		}
	}
	xml += "</evidences>";
	

	var request = ajaxBuildRequestObject();
	if(request != null)
	{
	
		var postData = 'xml=' + xml
		+ '&tr_id=' + <?php echo $tr_id; ?>;

		//alert(postData.substring(0, 200));
		request.open("POST", expandURI('do.php?_action=save_workplace_visits'), false); // (method, uri, synchronous)
		request.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
		request.setRequestHeader("x-ajax", "1"); // marker for server code
		request.send(postData);

		if(request.status == 200)
		{
			// SUCCESS
			//var debug = document.getElementById("debug");
			//debug.textContent = request.responseText;
			//return false;
			window.location.href='do.php?_action=read_training_record&id=<?php echo rawurlencode($tr_id); ?>';
		}
		else
		{
			alert(request.responseText);
		}
	}
	else
	{
		alert("Could not create XMLHttpRequest object");
	}
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
</style>



<body>
<div class="banner">
	<div class="Title">Workplace Visits</div>
	<div class="ButtonBar">
		<button onclick="window.location.href='do.php?_action=read_training_record&id=<?php echo rawurlencode($tr_id); ?>';">Close</button>
		<button onclick="save(<?php echo ($index+2); ?>);">Save</button>
	</div>
	<div class="ActionIconBar">
		<button onclick="window.print()" title="Print-friendly view"><img src="/images/btn-printer.gif" width="16" height="16" style="vertical-align:text-bottom" /></button>
		<button onclick="window.location.reload(false);" title="Refresh view (see the latest changes from other users)"><img src="/images/btn-refresh.gif" width="16" height="16" style="vertical-align:text-bottom" /></button>
	</div>
</div>

<h3>Learner</h3>
<table border="0" cellspacing="4" cellpadding="4" style="margin-left:10px">
	<col width="150" /><col />
	<tr>
		<td class="fieldLabel">Firstnames</td>
		<td class="fieldValue"><?php echo htmlspecialchars((string)$vo->firstnames); ?></td>
		<td class="fieldLabel">Surname</td>
		<td class="fieldValue"><?php echo htmlspecialchars((string)$vo->surname); ?></td>
	</tr>
</table>	

<h3>Workplace Visits</h3>
<form>
<?php
echo '<div align="left"><table class="resultset" border="0" cellspacing="0" cellpadding="6">';
echo '<thead><tr><th> Workplaces </th><th>Start Date</th><th>End Date</th><th> Comments </th></thead>';
echo '<tbody>';
for($a = 1; $a<=($index+2); $a++)
{
	$workplace_id = isset($data[$a]['workplace_id'])?$data[$a]['workplace_id']:'';
	$start_date = isset($data[$a]['start_date'])?$data[$a]['start_date']:'';
	$end_date = isset($data[$a]['end_date'])?$data[$a]['end_date']:'';
	$comments = isset($data[$a]['comments'])?$data[$a]['comments']:'';
	
	echo "<td>" . HTML::select('workplace_id'.$a, $workplaces, $workplace_id, true, true) . "</td>";
	echo "<td>" . HTML::datebox('start_date'.$a, $start_date, true) . "</td>";
	echo "<td>" . HTML::datebox('end_date'.$a, $end_date, true) . "</td>";
	echo "<td align=center><input type=text name='comments" . $a . "' id='comments" . $a . "' size=20 value='" . $comments . "' ></td>";  
	echo "</tr>";
}

echo "</tbody></table></div>";
?>
</form>

<!-- Popup calendar -->
<div id="calPop1" style="position:absolute;visibility:hidden;background-color:white;"></div>

</body>
</html>