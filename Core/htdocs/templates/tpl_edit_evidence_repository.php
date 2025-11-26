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
		xml += "<evidence>";
		xml += "<title>" + document.getElementById('title'+a).firstChild.nodeValue + "</title>";
		xml += "<reference>" + document.getElementById('reference'+a).value + "</reference>";
		xml += "<portfolio>" + document.getElementById('portfolio'+a).value + "</portfolio>";
		xml += "<type>" + document.getElementById('type'+a).value + "</type>";
		xml += "<content>" + document.getElementById('content'+a).value + "</content>";
		xml += "<category>" + document.getElementById('category'+a).value + "</category>";
		xml += "</evidence>";
	}
	xml += "</evidences>";
	

	var request = ajaxBuildRequestObject();
	if(request != null)
	{
	
		var postData = 'xml=' + xml
		+ '&qualification_id=' + <?php echo '"' . $qualification_id . '"'; ?>
		+ '&internaltitle=' + <?php echo '"' . $internaltitle . '"' ; ?>;

		//alert(postData.substring(0, 200));
		request.open("POST", expandURI('do.php?_action=save_evidences_repository'), false); // (method, uri, synchronous)
		request.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
		request.setRequestHeader("x-ajax", "1"); // marker for server code
		request.send(postData);

		if(request.status == 200)
		{
			// SUCCESS
			//var debug = document.getElementById("debug");
			//debug.textContent = request.responseText;
			//return false;
			window.location.href='do.php?_action=read_qualification&id=<?php echo rawurlencode($qualification_id); ?>&internaltitle=<?php echo rawurlencode($internaltitle); ?>';
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
	<div class="Title">Evidence Repository</div>
	<div class="ButtonBar">
		<button onclick="window.location.href='do.php?_action=read_qualification&id=<?php echo rawurlencode($qualification_id); ?>&internaltitle=<?php echo rawurlencode($internaltitle); ?>';">Close</button>
		<?php if($_SESSION['user']->type!=12){?>			
		<button onclick="save(<?php echo $no_of_evidences; ?>);">Save</button>
		<?php }?>
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
		<td class="fieldLabel">QCA:</td>
		<td class="fieldValue"><?php echo htmlspecialchars((string)$qualification_id); ?></td>
		<td class="fieldLabel">Internal Title:</td>
		<td class="fieldValue"><?php echo htmlspecialchars((string)$internaltitle); ?></td>
	</tr>
</table>	

<h3>Evidence Templates</h3>
<form>
<?php
echo '<div align="left"><table class="resultset" border="0" cellspacing="0" cellpadding="6">';
echo '<thead><tr><th>&nbsp;</th><th>Title</th><th>Reference</th><th>Portfolio <br>Page No.</th><th> Type </th><th>Content</th><th>Category</th></thead>';
echo '<tbody>';

//foreach($evidences as $evidence)
$test='';
for($a=1; $a<=$no_of_evidences; $a++)
{
	

	echo "<tr><td>" . $a . "</td><td id='title" . $a . "' width=150>" . $data[$a]['title'] . '</td>';   
	
	$ref = isset($data[$a]['reference'])?$data[$a]['reference']:'';
	$por = isset($data[$a]['portfolio'])?$data[$a]['portfolio']:'';
	echo "<td align=center><input type=text name='reference" . $a . "' id='reference" . $a . "' size=3 value='" . $ref . "' ></td>";  
	echo "<td align=center><input type=text name='portfolio" . $a . "' id='portfolio" . $a . "' size=3 value='" . $por . "' ></td>";
	echo "<td>" . HTML::select('type'.$a, $dropdown_type, isset($data[$a]['type'])?$data[$a]['type']:'' , true, true) . "</td>";
	echo "<td>" . HTML::select('content'.$a, $dropdown_content, isset($data[$a]['content'])?$data[$a]['content']:'' , true, true) . "</td>";
	echo "<td>" . HTML::select('category'.$a, $dropdown_category, isset($data[$a]['category'])?$data[$a]['category']:'' , true, true) . "</td>";
	echo "</tr>";
}

//throw new Exception($test);
echo "</tbody></table></div>";
?>
</form>

<!-- Popup calendar -->
<div id="calPop1" style="position:absolute;visibility:hidden;background-color:white;"></div>

</body>
</html>