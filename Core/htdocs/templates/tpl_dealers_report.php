<?php /* @var $view View */ ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
 "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Companies</title>
<link rel="stylesheet" href="/common.css" type="text/css"/>
<link rel="stylesheet" type="text/css" media="print" href="/print.css" />
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

function div_filter_crumbs_onclick(div)
{
	showHideBlock(div);
	showHideBlock('div_filters');
}

function details(id)
{

	showHideBlock(document.getElementById(id));
}

</script>

<style type="text/css">
tr.participating
{
	color:black;
	cursor:pointer;
}

tr.notparticipating
{
	color:gray;
	cursor:pointer;
}

tr.empty
{
	background: lightgreen;
}

tr.over
{
	background: red;

}

td.dealer
{
	cursor: pointer;
	background-image:url('/images/paper-background-orange.jpg');
}



</style>
</head>

<body>
<div class="banner">
	<div class="Title">Dealers Report</div>
	<div class="ButtonBar">

	</div>
	<div class="ActionIconBar">
		<button onclick="showHideBlock('div_filters');showHideBlock('div_filter_crumbs');" title="Show/hide filters"><img src="/images/btn-filter.gif" width="16" height="16" style="vertical-align:text-bottom" /></button>
		<button onclick="window.print()" title="Print-friendly view"><img src="/images/btn-printer.gif" width="16" height="16" style="vertical-align:text-bottom" /></button>
		<button onclick="window.location.reload(false);" title="Refresh view (see the latest changes from other users)"><img src="/images/btn-refresh.gif" width="16" height="16" style="vertical-align:text-bottom" /></button>
	</div>
</div>

<?php $_SESSION['bc']->render($link); ?>

<?php echo $view->getFilterCrumbs() ?>

<div id="div_filters" style="display:none">
<form method="get" action="<?php echo $_SERVER['PHP_SELF'] ?>">
<input type="hidden" name="page" value="1" />
<input type="hidden" name="_action" value="dealers_report" />
<table>
	<tr>
		<td>Records per page: </td>
		<td><?php echo $view->getFilterHTML(View::KEY_PAGE_SIZE); ?></td>
	</tr>
	<tr>
		<td>Sort by:</td>
		<td><?php echo $view->getFilterHTML('order_by'); ?></td>
	</tr>
	<tr>
		<td>Manufacturer:</td>
		<td><?php echo $view->getFilterHTML('filter_manufacturer'); ?></td>
	</tr>
	<tr>
		<td>Dealer Group:</td>
		<td><?php echo $view->getFilterHTML('filter_group'); ?></td>
	</tr>
	<tr>
		<td>Region:</td>
		<td><?php echo $view->getFilterHTML('filter_region'); ?></td>
	</tr>
	<tr>
		<td>Town:</td>
		<td><?php echo $view->getFilterHTML('filter_town'); ?></td>
	</tr>
	<tr>
		<td>Locality:</td>
		<td><?php echo $view->getFilterHTML('filter_locality'); ?></td>
	</tr>
	<tr>
		<td>Postcode:</td>
		<td><?php echo $view->getFilterHTML('filter_postcode'); ?></td>
	</tr>
	<tr>
		<td>Dealer Type:</td>
		<td><?php echo $view->getFilterHTML('filter_type'); ?></td>
	</tr>
	<tr>
		<td>Dealers Participation:</td>
		<td><?php echo $view->getFilterHTML('filter_dealers_participating'); ?></td>
	</tr>
	<tr>
		<td>Dealers Name:</td>
		<td><?php echo $view->getFilterHTML('filter_legal_name'); ?></td>
	</tr>
	<tr>
		<td>Health & Safety Timeliness:</td>
		<td><?php echo $view->getFilterHTML('by_health_safety_timeliness'); ?></td>
	</tr>
	<tr>
		<td>Health & Safety compliance:</td>
		<td><?php echo $view->getFilterHTML('by_health_safety_compliance'); ?></td>
	</tr>
	<tr>
		<td>Date:</td>
		<td>from <?php echo $view->getFilterHTML('start_date'); ?>
		&nbsp;&nbsp;&nbsp;&nbsp;to <?php echo $view->getFilterHTML('end_date'); ?></td>
	</tr>
	
</table>
<input type="submit" value="Go"/>&nbsp;<input type="button" onclick="resetViewFilters(document.forms[0]);" value="Reset" />
</form>
</div>

<div align="center" style="margin-top:50px;">
	<?php echo $view->render($link, $view); ?>
</div>


<!-- Popup calendar -->
<div id="calPop1" style="position:absolute;visibility:hidden;background-color:white;"></div>

</body>
</html>