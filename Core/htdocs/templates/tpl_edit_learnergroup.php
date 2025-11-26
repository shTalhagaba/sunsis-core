<?php /* @var $vo Contract */ ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
 "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Contract</title>
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
</script>

<script language="JavaScript">
function save()
{
	var myForm = document.forms[0];
	if(validateForm(myForm) == false)
	{
		return false;
	}
	
	myForm.submit();
}

</script>

</head>
<body>
<div class="banner">
	<div class="Title">Leaner Group</div>
	<div class="ButtonBar">
		<?php if($_SESSION['user']->type!=12){?>			
		<button onclick="save();">Save</button>
		<?php }?>
 		<button onclick="if(confirm('Are you sure?'))window.history.go(-1);">Cancel</button> 
	</div>
	<div class="ActionIconBar">

	</div>
</div>


<h3>Details</h3>
<form name="form1" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
<input type="hidden" name="id" value="<?php echo $vo->id ?>" />
<input type="hidden" name="_action" value="save_learnergroup"/>
<table border="0" cellspacing="4" style="margin-left:10px">
	<col width="140" />
	<tr>
		<td class="fieldLabel_compulsory"> Title :</td>
		<td><input class="compulsory" type="text" name="title" value="<?php echo htmlspecialchars((string)$vo->title); ?>" size="40" /></td>
	</tr>
	<tr>
		<td class="fieldLabel_compulsory">Start Date:</td>
		<td><?php echo HTML::datebox('start_date', $vo->start_date, true); ?></td>
	</tr>
	<tr>
		<td class="fieldLabel_compulsory">End Date:</td>
		<td><?php echo HTML::datebox('end_date', $vo->end_date, true); ?></td>
	</tr>
<!-- <tr>
		<td class="fieldLabel_optional" style="cursor:help" onclick="alert('Sector ');" >Qualification type:</td>
		<td><?php echo HTML::select('sector', $sector_dropdown, $vo->sector, true, true); ?></td>
	</tr>
	<tr>
		<td class="fieldLabel_optional" valign="top">Comments:</td>
		<td><textarea class="optional" style="font-family:sans-serif; font-size:10pt" name="comments" rows="7" cols="60"><?php echo htmlspecialchars((string)$vo->comments); ?></textarea></td>
	</tr>
-->
</table>
</form>

<!-- Popup calendar -->
<div id="calPop1" style="position:absolute;visibility:hidden;background-color:white;"></div>

</body>
</html>