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
	var ty = "<?php echo $vo->organisation_type;?>";
	grid_level.setValues(ty.split(','));
}

YAHOO.util.Event.onDOMReady(populate);


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
	<div class="Title"><?php echo $page_title ?></div>
	<div class="ButtonBar">
		<?php if($organisation_type!=0) { ?>				
		<button onclick="window.location.href='do.php?_action=view_organisations&organisation_type=<?php echo rawurlencode($organisation_type);?>';">Close</button>
		<?php } ?>
		<button onclick="window.location.replace('do.php?id=<?php echo $vo->id; ?>&organisation_type=<?php echo $organisation_type;?>&_action=edit_organisation');">Edit</button>
		<button onclick="window.location.href='do.php?_action=edit_location&organisations_id=<?php echo $vo->id; ?>'">Add new location</button>
		<button onclick="window.location.href='do.php?_action=edit_user&organisations_id=<?php echo $vo->id; ?>&route=1'">Add new employee</button>
		<button onclick="window.location.href='do.php?_action=edit_user&organisations_id=<?php echo $vo->id; ?>&route=2'">Add new learner</button>
		<?php if($_SESSION['user']->type!=12){?>
			<button onclick="deleteRecord();">Delete</button>
		<?php } ?>
	</div>
	<div class="ActionIconBar">
		<button onclick="window.print()" title="Print-friendly view"><img src="/images/btn-printer.gif" width="16" height="16" style="vertical-align:text-bottom" /></button>
		<button onclick="window.location.reload(false);" title="Refresh view (see the latest changes from other users)"><img src="/images/btn-refresh.gif" width="16" height="16" style="vertical-align:text-bottom" /></button>
	</div>
</div>

<h3>Name</h3>
<table border="0" cellspacing="4" cellpadding="4">
	<col width="150" />
	<tr><td class="fieldLabel">Legal name:</td><td class="fieldValue"><?php echo htmlspecialchars((string)$vo->legal_name); ?></td></tr>
	<tr><td class="fieldLabel">Trading name:</td><td class="fieldValue"><?php echo htmlspecialchars((string)$vo->trading_name); ?></td></tr>
	<tr><td class="fieldLabel">Abbreviation:</td><td class="fieldValue"><?php echo htmlspecialchars((string)$vo->short_name); ?></td></tr>
	<tr><td class="fieldLabel">Category:</td><td class="fieldValue"><?php //echo htmlspecialchars((string)$lookup_org_type[$vo->org_type_id]); ?></td></tr>
	<tr>
		<td class="fieldLabel_compulsory" valign="top">Type:</td>
		<td class="fieldValue"><?php echo HTML::checkboxGrid('level', $type_checkboxes, null, 3, false); ?></td>
	</tr>
</table>



<h3>Identification Codes</h3>
<table border="0" cellspacing="4" cellpadding="4">
	<col width="150" />
	<tr><td class="fieldLabel">Company Number:</td><td class="fieldValue"><?php echo htmlspecialchars((string)$vo->company_number); ?></td></tr>
	<tr><td class="fieldLabel">VAT Number:</td><td class="fieldValue"><?php echo htmlspecialchars((string)$vo->vat_number); ?></td></tr>
		<tr>
		<td class="fieldLabel"><abbr title="UK Provider Reference Number">UKPRN</abbr>:</td>
		<td class="fieldValue"><?php if($vo->ukprn != '') { ?><a href="" onclick="document.forms['display_UKRLP_record'].submit();return false;"
		title="Display provider's record in the UKRLP online database"><?php echo htmlspecialchars((string)$vo->ukprn); ?></a>
		<img src="/images/external.png" /><?php } ?></td>
	</tr>
</table>

<!-- Hidden form for displaying a provider's UKRLP record -->
<form name="display_UKRLP_record" method="post" action="http://www.ukrlp.co.uk/ukrlp/ukrlp_provider.page_pls_searchProviders" target="_blank">
	<input type="hidden" name="pn_ukprn" value="<?php echo htmlspecialchars((string)$vo->ukprn); ?>" />
	<input type="hidden" name="x" value="" />
</form>

<h3>Locations</h3>

<?php $this->renderLocations($link, $vo); ?>

<h3>Learners</h3>
<?php $this->renderLearners($link, $vo); ?>


<h3>Personnel</h3>
<?php $this->renderPersonnel($link, $vo); ?>


<h3>Training Records</h3>
<?php $vo4->render($link); ?>


<h3>Access Control</h3>
<p class="sectionDescription">Will include roles for administering the organisation,
reading all documents of the organisation and creating new users(?).  In security terms,
an organisation is a "domain" and there are varying levels of access to it.
Ordinary users have the least access of all, and may only see their own documents
and any documents to which they have been granted privileges by an organisation administrator.</p>

</body>
</html>