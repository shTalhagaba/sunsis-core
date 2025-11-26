<?php /* @var $view View */ ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
 "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Qualifications</title>
<link rel="stylesheet" href="/common.css" type="text/css"/>
<link rel="stylesheet" type="text/css" media="print" href="/print.css" />
<script src="/js/jquery.min.js" type="text/javascript"></script>
<script src="/common.js" type="text/javascript"></script>

<script language="JavaScript">

function div_filter_crumbs_onclick(div)
{
	showHideBlock(div);
	showHideBlock('div_filters');
}

</script>

</head>

<body>
<div class="banner">
	<div class="Title">Learner Group</div>
	<div class="ButtonBar">
 		<button onclick="window.location.href='do.php?_action=view_frameworks';">Close</button> 
 		<button onclick="window.location.href='do.php?_action=edit_framework&framework_id=<?php echo rawurlencode($id); ?>';">Edit</button> 
<!-- 	<button onclick="if(confirm('Are you sure?'))window.location.href='do.php?_action=delete_framework&framework_id=<?php //echo rawurlencode($id); ?>';">Delete </button> -->
<!--	<button onclick="window.location.href='do.php?_action=get_qualification&framework_id=<?php echo rawurlencode($id); ?>';">Attach Qualification </button>
 		<button onclick="window.location.href='do.php?_action=get_framework&framework_id=<?php echo rawurlencode($id); ?>';">Attach Framework</button> -->
	</div>
	<div class="ActionIconBar">
		<button onclick="showHideBlock('div_filters');showHideBlock('div_filter_crumbs');" title="Show/hide filters"><img src="/images/btn-filter.gif" width="16" height="16" style="vertical-align:text-bottom" /></button>
		<button onclick="window.print()" title="Print-friendly view"><img src="/images/btn-printer.gif" width="16" height="16" style="vertical-align:text-bottom" /></button>
		<button onclick="window.location.reload(false);" title="Refresh view (see the latest changes from other users)"><img src="/images/btn-refresh.gif" width="16" height="16" style="vertical-align:text-bottom" /></button>
	</div>
</div>

<?php echo $view->getFilterCrumbs() ?> 

<div id="div_filters" style="display:none">
<form method="get" action="<?php echo $_SERVER['PHP_SELF'] ?>">
<input type="hidden" name="_action" value="view_users" />
<table>
	<tr>
		<td>Records per page: </td>
		<td><?php echo $view->getFilterHTML(View::KEY_PAGE_SIZE); ?></td>
	</tr>
	<tr>
		<td>Sort by:</td>
		<td><?php echo $view->getFilterHTML('order_by'); ?></td>
	</tr>
</table>
<input type="submit" value="Go"/>&nbsp;<input type="button" onclick="resetViewFilters(document.forms[0]);" value="Reset" />
</form>
</div>
<h3> Learner Group Details</h3>
<table border="0" cellspacing="4" cellpadding="4">
	<col width="100" /><col />
	<tr>
		<td class="fieldLabel" style="cursor:help"> Learner Group Title </td>
		<td width="300" class="fieldValue" style="font-family:Arial"><?php echo htmlspecialchars((string)$vo->title); ?></td>
	</tr>
	<tr>
		<td class="fieldLabel" style="cursor:help"> Start Date:</td>
		<td width="100" class="fieldValue"><?php echo htmlspecialchars(date_format(date_create($vo->start_date),"d-m-Y")); ?></td>
	</tr>
	<tr>
		<td class="fieldLabel" style="cursor:help">End Date:</td>
		<td width="100" class="fieldValue"><?php echo htmlspecialchars(date_format(date_create($vo->end_date),"d-m-Y")); ?></td>
	</tr>
<!-- <tr>
		<td class="fieldLabel" style="cursor:help">Sector:</td>
		<td width="300" class="fieldValue" style="font-family:Arial"><?php //echo htmlspecialchars((string)$sector); ?></td>
	</tr>
	<tr>
		<td class="fieldLabel" style="cursor:help">Comments:</td>
		<td width="500" class="fieldValue" style="font-family:Arial"><?php //echo htmlspecialchars((string)$vo->comments); ?></td>
	</tr>
-->
</table>


<h3> Learners </h3>
	<span class="button" onclick="window.location.replace('do.php?_action=get_qualification&framework_id=<?php echo rawurlencode($id); ?>');"> Include Learner </span>
	<span class="button" onclick="window.location.replace('do.php?_action=get_framework&framework_id=<?php echo rawurlencode($id); ?>');"> Include Learner Group</span>
<div align="left" style="margin-top:10px;">
<?php echo $view->render($link,$vo->title); ?>
</div>

</body>
</html>
