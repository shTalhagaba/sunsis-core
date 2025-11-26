<?php /* @var $vo OrganisationVO */ ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
 "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
	<title>School</title>
	<link rel="stylesheet" href="/common.css" type="text/css"/>
	<script src="/js/jquery.min.js" type="text/javascript"></script>
	<script src="js/common.js" type="text/javascript"></script>
	
	<script language="JavaScript">
	function deleteRecord()
	{
		if(window.confirm("Delete this provider?"))
		{
			window.location.replace('do.php?_action=delete_provider&id=<?php echo $vo->id; ?>');
		}
	}
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
	<div class="Title">Training Provider</div>
	<div class="ButtonBar">
		<button onclick="window.location.href='do.php?_action=view_providers';">Close</button>
		<button onclick="window.location.replace('do.php?id=<?php echo $vo->id; ?>&_action=edit_provider');">Edit</button>
		<button onclick="window.location.href='do.php?_action=edit_location&organisations_id=<?php echo $vo->id; ?>'">Add new location</button>
		<button onclick="window.location.href='do.php?_action=edit_personnel&organisations_id=<?php echo $vo->id; ?>'">Add new employee</button>
		<?php if($_SESSION['user']->type!=12){?>
			<button onclick="deleteRecord();" style="color:red">Delete</button>
		<?php } ?>
	</div>
	<div class="ActionIconBar">
		<button onclick="window.print()" title="Print-friendly view"><img src="/images/btn-printer.gif" width="16" height="16" style="vertical-align:text-bottom" /></button>
		<button onclick="window.location.reload(false);" title="Refresh view (see the latest changes from other users)"><img src="/images/btn-refresh.gif" width="16" height="16" style="vertical-align:text-bottom" /></button>
	</div>
</div>

<table border="0" cellspacing="4" cellpadding="4">
	<tr>
		<td class="fieldLabel"><abbr title="UK Provider Reference Number">UKPRN</abbr>:</td>
		<td class="fieldValue"><?php if($vo->ukprn != '') { ?><a href="" onclick="document.forms['display_UKRLP_record'].submit();return false;"
		title="Display provider's record in the UKRLP online database"><?php echo htmlspecialchars($vo->ukprn); ?></a>
		<img src="/images/external.png" /><?php } ?></td>
	</tr>
	<tr><td class="fieldLabel">Full/legal name:</td><td class="fieldValue"><?php echo htmlspecialchars($vo->legal_name); ?></td></tr>
	<tr><td class="fieldLabel">Abbreviation:</td><td class="fieldValue"><?php echo htmlspecialchars($vo->short_name); ?></td></tr>
</table>

<!--  Hidden form -->
<form name="display_UKRLP_record" method="post" action="http://www.ukrlp.co.uk/ukrlp/ukrlp_provider.page_pls_searchProviders" target="_blank">
	<input type="hidden" name="pn_ukprn" value="<?php echo htmlspecialchars($vo->ukprn); ?>" />
	<input type="hidden" name="x" value="" />
</form>

<br/>

<h3>Locations</h3>

<?php
$st2 = $link->query($locations_query);
if($st2)
{
	if($st2->rowCount() > 0)
	{
		echo '<table class="resultset" border="0" cellpadding="6" cellspacing="0" style="margin-left:10px">';
		echo '<tr><th>&nbsp;</th><th>Name</th><th>Locality</th><th>City</th></tr>';
		
		while($row = $st2->fetch())
		{
			echo HTML::viewrow_opening_tag('do.php?_action=read_location&id=' . $row['id']);
			echo '<td><a href="do.php?_action=read_location&id=' . $row['id'] . '"><img src="/images/building-icon.gif" border="0" /></a></td>';
			echo '<td>' . HTML::cell($row['full_name']) . '</td>';
			echo '<td>' . HTML::cell($row['address_line_2']) . '</td>';
			echo '<td>' . HTML::cell($row['address_line_3']) . '</td>';
			echo '</tr>';
		}
		
		echo '</table>';
	}
	else
	{
		echo '<p style="margin-left:10px;font-style:italic;font-weight:bold;color:red">None entered.  Locations are required when scheduling lessons for a course at this provider.</p>';
	}
	
}
else
{
	throw new DatabaseException($link, $locations_query);
}
?>

<br/>

<h3>Personnel</h3>

<?php
$st= $link->query($personnel_query);
if($st)
{
	if($st->rowCount() > 0)
	{
		echo '<table class="resultset" border="0" cellpadding="6" cellspacing="0" style="margin-left:10px">';
		echo '<tr><th>&nbsp;</th><th>Surname</th><th>Firstnames</th><th>Department</th><th>Job Role</th><th>Telephone</th></tr>';
		
		while($row = $st->fetch())
		{
			echo HTML::viewrow_opening_tag('do.php?_action=read_personnel&id=' . $row['id']);
			echo '<td><a href="do.php?_action=read_personnel&id=' . $row['id'] . '"><img src="/images/blue-person.gif" border="0" /></a></td>';
			echo '<td>' . HTML::cell($row['surname']) . '</td>';
			echo '<td>' . HTML::cell($row['firstnames']) . '</td>';
			echo '<td>' . HTML::cell($row['department']) . '</td>';
			echo '<td>' . HTML::cell($row['job_role']) . '</td>';
			echo '<td>' . HTML::cell($row['telephone']) . '</td>';
			echo '</tr>';
		}
		
		echo '</table>';
	}
	else
	{
		echo '<p style="margin-left:10px;font-style:italic;font-weight:bold;color:red">None entered. Personnel are required when creating courses, teaching groups and lessons for this provider.</p>';
	}
	
}
else
{
	throw new DatabaseException($link, $personnel_query);
}
?>


</body>
</html>