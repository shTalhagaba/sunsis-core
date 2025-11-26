<?php /* @var $vo Organisation */ ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
 "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
	<title>Organisation</title>
	<link rel="stylesheet" href="/common.css" type="text/css"/>
	<script src="/js/jquery.min.js" type="text/javascript"></script>
	<script src="/common.js" type="text/javascript"></script>

<script type="text/javascript" src="/yui/2.4.1/build/yahoo-dom-event/yahoo-dom-event.js"></script>

	
<script language="JavaScript">
	function deleteRecord()
	{
		if(window.confirm("Delete this provider?"))
		{
			window.location.replace('do.php?_action=delete_provider&id=<?php echo $vo->id; ?>');
		}
	}

function populate()
{
	var grid_level = document.getElementById('grid_level');
	grid_level.clear();
	var ty = "<?php echo $vo->organisation_type; ?>";
	grid_level.setValues(ty.split(','));
}

//YAHOO.util.Event.onDOMReady(populate);


</script>
</head>

<style type="text/css">
.label
{
	font-weight:bold;
}

</style>

<body>
<div class="banner">
	<div class="Title">Candidate Employer</div>
	<div class="ButtonBar">
<!-- 	<button onclick="window.location.href='do.php?_action=view_organisations&organisation_type=<?php // echo rawurlencode($organisation_type);?>';">Close</button>
		&nbsp; -->
		<button onclick="window.location.replace('do.php?id=<?php echo $vo->id;?>&_action=delete_candidate_employer');">Delete</button>
	</div>
	<div class="ActionIconBar">
		<button onclick="window.print()" title="Print-friendly view"><img src="/images/btn-printer.gif" width="16" height="16" style="vertical-align:text-bottom" /></button>
		<button onclick="window.location.reload(false);" title="Refresh view (see the latest changes from other users)"><img src="/images/btn-refresh.gif" width="16" height="16" style="vertical-align:text-bottom" /></button>
	</div>
</div>

<?php $_SESSION['bc']->render($link); ?>

<h3>Details</h3>
<table border="0" cellspacing="4" cellpadding="4">
	<col width="150" />
	<tr><td class="fieldLabel">Employer name:</td><td class="fieldValue"><?php echo htmlspecialchars((string)$vo->legal_name); ?></td></tr>
	<tr><td class="fieldLabel">EDRS number:</td><td class="fieldValue"><?php echo htmlspecialchars((string)$vo->edrs); ?></td></tr>
	<tr><td class="fieldLabel">Company number:</td><td class="fieldValue"><?php echo htmlspecialchars((string)$vo->company_number); ?></td></tr>
	<tr><td class="fieldLabel">VAT Number:</td><td class="fieldValue"><?php echo htmlspecialchars((string)$vo->vat_number); ?></td></tr>
	<tr><td class="fieldLabel">Retailer Code:</td><td class="fieldValue"><?php echo htmlspecialchars((string)$vo->retailer_code); ?></td></tr>
	<tr><td class="fieldLabel">Employer Code:</td><td class="fieldValue"><?php echo htmlspecialchars((string)$vo->employer_code); ?></td></tr>
	<tr><td class="fieldLabel">District:</td><td class="fieldValue"><?php echo htmlspecialchars((string)$vo->district); ?></td></tr>
</table>
</body>
</html>