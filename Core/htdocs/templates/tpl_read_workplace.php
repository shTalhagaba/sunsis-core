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

	function uploadFile()
	{
		var myForm = document.forms[1];
		myForm.submit();
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
	<div class="Title"><?php echo $vo->legal_name; ?></div>
	<div class="ButtonBar">
		<button onclick="window.location.href='<?php echo $_SESSION['bc']->getPrevious();?>';">Close</button>
		<button onclick="window.location.replace('do.php?id=<?php echo $vo->id; ?>&_action=edit_workplace');">Edit</button>
	<!-- <button onclick="window.location.replace('do.php?id=<?php echo $vo->id; ?>&_action=edit_health_and_safety&back=read_workplace');">Health & Safety</button> -->
		<button onclick="deleteRecord();">Delete</button>
	</div>
	<div class="ActionIconBar">
		<button onclick="window.print()" title="Print-friendly view"><img src="/images/btn-printer.gif" width="16" height="16" style="vertical-align:text-bottom" /></button>
		<button onclick="window.location.reload(false);" title="Refresh view (see the latest changes from other users)"><img src="/images/btn-refresh.gif" width="16" height="16" style="vertical-align:text-bottom" /></button>
	</div>
</div>

<?php $_SESSION['bc']->render($link); ?>

<h3>Name</h3>
<table border="0" cellspacing="4" cellpadding="4">
	<col width="150" />
	<tr><td class="fieldLabel">Legal name:</td><td class="fieldValue"><?php echo htmlspecialchars((string)$vo->legal_name); ?></td></tr>
	<tr><td class="fieldLabel">Manufacturer:</td><td class="fieldValue"><?php $m = DAO::getSingleValue($link, "select title from brands where id = $vo->manufacturer");echo htmlspecialchars((string)$m); ?></td></tr>
	<tr><td class="fieldLabel">Dealer Group:</td><td class="fieldValue"><?php echo htmlspecialchars((string)$vo->dealer_group); ?></td></tr>
	<tr><td class="fieldLabel">Dealer Type:</td><td class="fieldValue"><?php echo htmlspecialchars((string)$vo->org_type); ?></td></tr>
	<tr><td class="fieldLabel">CI:</td><td class="fieldValue"><?php echo htmlspecialchars((string)$vo->code); ?></td></tr>
	<tr><td class="fieldLabel">Region:</td><td class="fieldValue"><?php echo htmlspecialchars((string)$vo->region); ?></td></tr>
	<tr><td class="fieldLabel">No. of Workplacements Available:</td><td class="fieldValue"><?php echo htmlspecialchars((string)$vo->workplaces_available); ?></td></tr>

	<tr><td class="fieldLabel">Participating?:</td>
	<td class=""><?php echo HTML::checkbox('dealer_participating', 1, $vo->dealer_participating, false); ?></td></tr>
	<tr><td class="fieldLabel">Reason not participating:</td><td class="fieldValue"><?php echo htmlspecialchars((string)$reason_not_participating); ?></td></tr>
	
<!-- <tr>
		<td class="fieldLabel_compulsory" valign="top">Type:</td>
		<td class="fieldValue"><?php //echo HTML::checkboxGrid('level', $type_checkboxes, null, 3, false); ?></td>
	</tr> -->
</table>

<!-- Hidden form for displaying a provider's UKRLP record -->
<form name="display_UKRLP_record" method="post" action="http://www.ukrlp.co.uk/ukrlp/ukrlp_provider.page_pls_searchProviders" target="_blank">
	<input type="hidden" name="pn_ukprn" value="<?php echo htmlspecialchars((string)$vo->ukprn); ?>" />
	<input type="hidden" name="x" value="" />
</form>

<h3>CRM Notes</h3>
<span class="button" style="margin-bottom: 15px;" onclick="window.location.href='do.php?_action=edit_crm_note&organisations_id=<?php echo $vo->id; ?>&organisation_type=read_workplace'"> Add New Note </span>
<?php $view2->render($link,'read_workplace'); ?>

<h3>Locations</h3>
<span class="button" style="margin-bottom: 15px;" onclick="window.location.href='do.php?_action=edit_location&organisations_id=<?php echo $vo->id; ?>&back=<?php echo "trainingprovider"; ?>'"> Add new location </span>
<?php $locations->render($link,'read_workplace'); ?>

<h3>File Repository</h3>
<?php echo $html2;?>
<div>
	<form method="post" action="<?php echo $_SERVER['PHP_SELF'] ?>?_action=save_employer_repository" ENCTYPE="multipart/form-data">
		<input type="hidden" name="_action" value="save_employer_repository" />
		<input type="hidden" name="emp_id" value="<?php echo $emp_id;?>" />

		<table border="0" cellpadding="4" cellspacing="4" style="margin-left:10px">
			<col width="150" />
			<tr>
				<td class="fieldLabel_compulsory">File to upload:</td>
				<?php
				// re - 01/03/2012 - changed the form element name #22414
				//    - there are too many things called uploadFile around here
				//    - for clarity.  Also removed camelcase and replaced with
				//    - underscored word separation as above support request
				//    - was caused by camelcase issue.
				?>
				<td><input class="compulsory" type="file" name="uploaded_employer_file" />&nbsp;
					<span id="uploadFileButton" class="button" onclick="uploadFile()">&nbsp;Upload&nbsp;</span>
				</td>
			</tr>
		</table>
	</form>
</div>


<h3>System Users</h3>
<span class="button" onclick="window.location.href='do.php?_action=edit_user&organisations_id=<?php echo $vo->id; ?>&people=<?php echo "Other Learner"; ?>&people_type=<?php echo 1; ?> '"> Add administrator </span>
<?php $vo5->render($link);?>

<h3>Work Experience Placements</h3>
<?php $view->render($link); ?>

</body>
</html>