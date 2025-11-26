<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
 "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
	<title>Assessor Review</title>
	<link rel="stylesheet" href="/common.css" type="text/css"/>
	<script src="/js/jquery.min.js" type="text/javascript"></script>
	<script src="/common.js" type="text/javascript"></script>


	<link rel="stylesheet" href="/jquery-ui/css/jquery-ui-1.8.11.custom.css" type="text/css"/>
	<script language="JavaScript" src="/jquery-ui/js/jquery-1.5.2.min.js"></script>
	<script language="JavaScript" src="/jquery-ui/js/jquery-ui-1.8.11.custom.min.js"></script>
	<script language="JavaScript" src="/common.js"></script>


	<script language="JavaScript">


function save(count, master_save)
{

	xml = '<evidences>';

	for(a=1;a<=count; a++)
	{
	
		if(document.getElementById('planned'+a).checked)
		{		
			xml += "<evidence>";
			xml += "<workplace_id>" + document.getElementById('workplace_id'+a).value + "</workplace_id>";
			xml += "<start_date>" + document.getElementById("date"+a).firstChild.nodeValue + "</start_date>";

			if(document.getElementById('actual'+a).checked)
				xml += "<end_date>" + document.getElementById("date"+a).firstChild.nodeValue + "</end_date>";
			else
				xml += "<end_date>" + "dd/mm/yyyy" + "</end_date>";
			
			xml += "<comments>" + document.getElementById('comments'+a).value + "</comments>";
			xml += "</evidence>";
		}
	}
	xml += "</evidences>";
	
	var request = ajaxBuildRequestObject();
	if(request != null)
	{
	
		var postData = 'xml=' + xml
		+ '&tr_id=' + <?php echo $tr_id; ?>
		+ '&current_month=' + <?php echo $current_monthn; ?>
		+ '&current_year='  + <?php echo $current_year; ?>;

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
			if(master_save)
				window.location.href='<?php echo $_SESSION['bc']->getPrevious(); ?>';
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
	<div class="Title">Work Experience</div>
	<div class="ButtonBar">
		<button onclick="window.location.href='<?php echo $_SESSION['bc']->getPrevious();?>';">Close</button>
		<?php if($_SESSION['user']->type!=12){?>
		<button onclick="save(<?php echo $days_in_month; ?>, 1);">Save</button>
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
		<td class="fieldLabel">Firstnames</td>
		<td class="fieldValue"><?php echo htmlspecialchars((string)$vo->firstnames); ?></td>
		<td class="fieldLabel">Surname</td>
		<td class="fieldValue"><?php echo htmlspecialchars((string)$vo->surname); ?></td>
	</tr>
	<tr>
		<td class="fieldLabel">Planned Days</td>
		<td class="fieldValue"><?php echo htmlspecialchars((string)$planned_work_experience); ?></td>
		<td class="fieldLabel">Actual Days</td>
		<td class="fieldValue"><?php echo htmlspecialchars((string)$actual_work_experience); ?></td>
	</tr>
</table>	

<h3>Work Experience </h3>

<div>
<?php 

echo '<table><tr><td>' . $sd->getYear() . '</td>';
$year = $sd->getYear();
while($sd->getDate()<=$course_end_date->getDate())
{
	if($year!=$sd->getYear())
	{	
		echo '</tr><tr><td>' . $sd->getYear() . '</td>';
		$year = $sd->getYear();
	}
		
	echo '<td><span class="button" onclick="save('; 
	echo $days_in_month . '); window.location.replace(';
	$url = 'do.php?tr_id=' . $tr_id . '&current_month=' . $sd->getMonth() . '&navigate=1' . '&current_year=' . $sd->getYear() . '&_action=edit_work_experience';
	echo "'" . $url . "');" . '">';
	echo substr($months_of_the_year[$sd->getMonth()-1],0,3) . '</span></td>';
	
	$sd->addDays(30);
}
echo '</tr></table>';
?>
</div>


<form>
<?php
echo '<div align="left"><table class="resultset" border="0" cellspacing="0" cellpadding="6">';
?>
<thead><tr>

<?php 
//throw new Exception($course_start_date->getDays() . $course_start_date->getMonth() . $course_start_date->getYear());
if($course_start_date->getDate()<$display_start_date->getDate()) { ?>
<td style="border-style: none" colspan=2 align=left><span class="button" onclick="save(<?php echo $days_in_month; ?>); window.location.replace('do.php?tr_id=<?php echo $tr_id; ?>&navigate=<?php echo "previous"; ?>&current_month=<?php echo $current_monthn; ?>&current_year=<?php echo $current_year; ?>&_action=edit_work_experience');"> Previous </span> </td>
<?php } else { ?>	
<td style="border-style: none" colspan=2 align=left>&nbsp;</td>
<?php } ?>

<td align="center" style="font-size: 15pt; border-style: none;"> <?php echo $current_montht . ', ' . $current_year  ; ?>  </td>

<?php if($course_end_date->getDate()>$display_end_date->getDate()) { ?>
<td style="border-style: none" align="right" colspan=3><span class="button" onclick="save(<?php echo $days_in_month; ?>); window.location.replace('do.php?tr_id=<?php echo $tr_id; ?>&navigate=<?php echo "next"; ?>&current_month=<?php echo $current_monthn; ?>&current_year=<?php echo $current_year; ?>&_action=edit_work_experience');"> &nbsp;&nbsp;Next&nbsp;&nbsp; </span> </td>
<?php } else { ?>	
<td style="border-style: none" colspan=2 align=left>&nbsp;</td>
<?php } 
echo '<tr><th> Date </th> <th> Planned </th><th>Dealers</th><th>Actual</th><th> Comments </th></tr></thead><tbody>';

for($a = 1; $a<=$days_in_month; $a++)
{
	$date = str_pad($a,2,'0',STR_PAD_LEFT) . '/' . str_pad($current_monthn,2,'0',STR_PAD_LEFT) . '/' . $current_year;
	$workplace_id = isset($visits[$a]['workplace_id'])?$data[$a]['workplace_id']:'';
	$start_date = isset($visits[$a]['start_date'])?$data[$a]['start_date']:'';
	$end_date = isset($visits[$a]['end_date'])?$data[$a]['end_date']:'';
	$comments = isset($visits[$a]['comments'])?$data[$a]['comments']:'';

	$this_date = new Date($date);
	
	if($this_date->getDate()>=$course_start_date->getDate() && $this_date->getDate()<=$course_end_date->getDate())
		$disabled = "";
	else
		$disabled = "disabled";
	echo "<td id='date" . $a . "'>" . HTML::cell($date) . "</td>"; 
//	echo "<td>" . HTML::datebox('start_date'.$a, $start_date, true) . "</td>";
//	echo "<td>" . HTML::datebox('end_date'.$a, $end_date, true) . "</td>";
	
	if($visits[$a]['start_date']!=null)
		echo "<td align='center'><input type='checkbox' checked id='planned" . $a . "' " . $disabled . " name='planned" . $a . "' ></td>";
	else
		echo "<td align='center'><input type='checkbox' id='planned" . $a . "' " . $disabled . " name='planned" . $a . "' ></td>";
	
	if($disabled=='')	
		echo "<td>" . HTML::select('workplace_id'.$a, $workplaces, $workplace_id, true, true) . "</td>";
	else
		echo "<td>" . HTML::select('workplace_id'.$a, $workplaces, $workplace_id, true, true, 0) . "</td>";

	if($visits[$a]['end_date']!=null)
		echo "<td align='center'><input type='checkbox' checked id='actual" . $a . "' " . $disabled . " name='actual" . $a . "' ></td>";
	else
		echo "<td align='center'><input type='checkbox' id='actual" . $a . "' " . $disabled . " name='actual" . $a . "' ></td>";
	
	$comments = htmlspecialchars((string)$comments,ENT_QUOTES);
		
	echo "<td align=center><input type=text name='comments" . $a . "' id='comments" . $a . "' " . $disabled . " size=20 value='" . $comments . "' ></td>";  
	echo "</tr>";
}

echo "</tbody></table></div>";
?>
</form>

<!-- Popup calendar -->
<div id="calPop1" style="position:absolute;visibility:hidden;background-color:white;"></div>

</body>
</html>