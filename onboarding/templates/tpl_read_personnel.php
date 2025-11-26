<?php /* @var $vo OrganisationVO */ ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
 "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
	<title>Personnel</title>
	<link rel="stylesheet" href="/common.css" type="text/css"/>
	<script src="/js/jquery.min.js" type="text/javascript"></script>
	<script src="js/common.js" type="text/javascript"></script>
	<script language="JavaScript">
	function deleteRecord()
	{
		if(window.confirm("Delete this staff member?"))
		{
			window.location.replace('do.php?_action=delete_personnel&id=<?php echo $vo->id; ?>');
		}
	}
	</script>
</head>

<body>
<div class="banner">
	<div class="Title">Employee</div>
	<div class="ButtonBar">
		<button onclick="<?php echo $js_close ?>">Close</button>
		<button	onclick="window.location.replace('do.php?id=<?php echo $vo->id; ?>&_action=edit_personnel');">Edit</button>
		<?php if($_SESSION['user']->type!=12){?>
			<button onclick="deleteRecord();">Delete</button>
		<?php } ?>
	</div>
	<div class="ActionIconBar">
		<button onclick="window.print()" title="Print-friendly view"><img src="/images/btn-printer.gif" width="16" height="16" style="vertical-align:text-bottom" /></button>
		<button onclick="window.location.reload(false);" title="Refresh view (see the latest changes from other users)"><img src="/images/btn-refresh.gif" width="16" height="16" style="vertical-align:text-bottom" /></button>
	</div>
</div>


<table border="0" cellspacing="4" cellpadding="4" style="margin-left:10px">
	<tr>
		<td class="fieldLabel">Organisation:</td>
		<td class="fieldValue"><?php echo htmlspecialchars($o_vo->legal_name); ?></td>
	</tr>
	<tr>
		<td class="fieldLabel">Firstnames:</td>
		<td class="fieldValue"><?php echo htmlspecialchars($vo->firstnames); ?></td>
	</tr>
	<tr>
		<td class="fieldLabel">Surname:</td>
		<td class="fieldValue"><?php echo htmlspecialchars($vo->surname); ?></td>
	</tr>
	<tr>
		<td class="fieldLabel">Department:</td>
		<td class="fieldValue"><?php echo htmlspecialchars($vo->department); ?></td>
	</tr>
	<tr>
		<td class="fieldLabel">Job Role:</td>
		<td class="fieldValue"><?php echo htmlspecialchars($vo->job_role); ?></td>
	</tr>
	<tr>
		<td class="fieldLabel" valign="top">Postal Address:</td>
		<td class="fieldValue"><?php echo $bs7666->formatRead(); ?></td>
	</tr>
	<tr>
		<td class="fieldLabel">Telephone:</td>
		<td class="fieldValue"><?php echo htmlspecialchars($vo->telephone); ?></td>
	</tr>
	<tr>
		<td class="fieldLabel">Mobile:</td>
		<td class="fieldValue"><?php echo htmlspecialchars($vo->mobile); ?></td>
	</tr>
	<tr>
		<td class="fieldLabel">Fax:</td>
		<td class="fieldValue"><?php echo htmlspecialchars($vo->fax); ?></td>
	</tr>
	<tr>
		<td class="fieldLabel">Email:</td>
		<td class="fieldValue"><?php echo htmlspecialchars($vo->email); ?></td>
	</tr>
</table>


</body>
</html>