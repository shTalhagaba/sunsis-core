<?php /* @var $vo User */  ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
 "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Person</title>
<link rel="stylesheet" href="/common.css" type="text/css"/>
<script src="/js/jquery.min.js" type="text/javascript"></script>
<script src="/common.js" type="text/javascript"></script>

<!-- Calendar popup: credit to Matt Kruse (www.javascripttoolbox.com) -->
<script type="text/javascript" src="/calendarPopup/CalendarPopup.js"></script>

<!-- Initialise calendar popup -->
<script type="text/javascript">
<?php if(preg_match('/MSIE [1-6]/', $_SERVER['HTTP_USER_AGENT']) ) { ?>
	var calPop = new CalendarPopup();
	calPop.showNavigationDropdowns();
<?php } else { ?>
	var calPop = new CalendarPopup("calPop1");
	calPop.showNavigationDropdowns();
	document.write(getCalendarStyles());
<?php } ?>
</script>


<script type="text/javascript">

function validateFilters()
{

/*	var f = document.forms[0];
	
	var e = f.elements['cohort'];

	if(e.value != '')
	{
		var num = parseInt(e.value);
		if(isNaN(num))
		{
			alert("Cohort field accepts numeric values only");
			e.focus();
			return false;
		}
	}
*/	
	return true;
	
}


function div_filter_crumbs_onclick(div)
{
	showHideBlock(div);
	showHideBlock('div_filters');
}


function resetFilters()
{
	resetViewFilters(document.forms[1]);
	refreshQualificationList();
}
	
function filter_qualification_type_onchange(qualType)
{
	refreshQualificationList();
}

function filter_qualification_level_onchange(qualLevel)
{
	refreshQualificationList();
}

function refreshQualificationList()
{	

	var f = document.forms['filters'];
	var globe = document.getElementById('globe1');

	f.reset();
	
	var qualLevel = f.elements['filter_qualification_level'];
	var qualType = f.elements['filter_qualification_type'];
//	var qual = f.elements['filter_qualification_title'];
	
	// Disable controls
//	qual.disabled = true;
	
	// Populate course dropdown with a list of courses for the provider
//	globe.style.display = 'inline';
//	var url = 'do.php?_action=ajax_load_qualification_dropdown&qual_level=' + qualLevel.value + '&qual_type=' + qualType.value;
//	ajaxPopulateSelect(qual, url);
	
	// reactivate controls
//	qual.disabled = false;
//	globe.style.display = 'none';

	
	return false;
}

function newHelp()
{
	window.location.href='do.php?_action=edit_help';
}

</script>

</head>

<body>
<div class="banner">
	<div class="Title"></div>
	<div class="ButtonBar">
		<!-- <button onclick="window.location.href='do.php?_action=edit_user&people=<?php //echo $people; ?>&people_type=<?php //echo $people_type; ?>';">New</button> -->
	</div>
	<div class="ActionIconBar">
		<button onclick="window.print()" title="Print-friendly view"><img src="/images/btn-printer.gif" width="16" height="16" style="vertical-align:text-bottom" /></button>
		<button onclick="window.location.reload(false);" title="Refresh view (see the latest changes from other users)"><img src="/images/btn-refresh.gif" width="16" height="16" style="vertical-align:text-bottom" /></button>
	</div>
</div>

<?php $_SESSION['bc']->render($link); ?>

<br />
<?php 
/*
foreach($helpItems AS $category => $items)
{
	echo '<h1>' . $category . '</h1>';
	echo '<ul>';
	foreach($items as $key => $itemInfo)
	{
		echo '<li><a href="do.php?_action=view_help&amp;id=' . $itemInfo['help_id'] . '">' . $itemInfo['title'] . '</a></li>';
	}
	echo '</ul>';
}
*/

?>

<?php echo $view->getFilterCrumbs() ?>

<div id="div_filters" style="display:none">
<form method="get" action="<?php echo $_SERVER['PHP_SELF'] ?>">
<input type="hidden" name="page" value="1" />
<input type="hidden" name="_action" value="view_help" />
<table>
	<tr>
		<td>Records per page: </td>
		<td><?php echo $view->getFilterHTML(View::KEY_PAGE_SIZE); ?></td>
	</tr>
	<tr>
		<td>Sort by:</td>
		<td><?php echo $view->getFilterHTML(View::KEY_ORDER_BY); ?></td>
	</tr>
</table>
<input type="submit" value="Go"/>&nbsp;<input type="button" onclick="resetViewFilters(document.forms[0]);" value="Reset" />
</form>
</div>

<div align="center" style="margin-top:50px;">
<?php echo $view->getViewNavigator(); ?>
<table class="resultset" border="0" cellspacing="0" cellpadding="6">
	<thead>
	<tr>
		<th>&nbsp;</th>
		<th>Key</th>
		<th>Title</th>
		<th>Redirect to Key</th>
	</tr>
	</thead>
	<tbody>
	
	<?php
	$st = $link->query($view->getSQL());
	if($st)
	{
		while( $row = $st->fetch() ) 
		{
			echo HTML::viewrow_opening_tag('/admin/do.php?_action=read_help&id=' . $row['id']);
			echo '<td align="center"><img src="/images/help-icon-3232.png" border="0" title="#'.$row['id'].'" /></td>';
			echo '<td align="left" style="font-family:monospace">' . HTML::cell($row['key']) . '</td>';
			echo '<td align="left">' . HTML::cell($row['title']) . '</td>';
			echo '<td align="left" style="font-family:monospace">' . HTML::cell($row['key_redirect']) . '</td>';
			echo '</tr>';
		}
	
		//mysqli_free_result($result);
	}
	else
	{
	//	throw new Exception("Error in query: $query" . mysqli_error($link));
	}
	?>
	</tbody>
</table>
<?php echo $view->getViewNavigator(); ?>
</div>



<!-- Popup calendar -->
<div id="calPop1" style="position:absolute;visibility:hidden;background-color:white;"></div>

</body>
</html>