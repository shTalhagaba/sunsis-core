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
	
	<script language="JavaScript">
	function deleteRecord()
	{
	
		alert("Under development!");
		exit();
		if(window.confirm("Delete this provider?"))
		{
			window.location.replace('do.php?_action=delete_provider&id=<?php echo $vo->id; ?>');
		}
	}
	
	
function importContract(event)
{
	var myForm = document.forms[0];
	var buttons = myForm.elements['contracts'];
	
	id = buttons[buttons.selectedIndex].value;

	if(id == '')
	{
		alert("Please select a Contract");
		return false;
	}
	else
	{
		
		var postData = 'contract_to_import_id=' + id
		+ '&current_contract_id=' + <?php echo $vo->id; ?>;
	
		var client = ajaxRequest('do.php?_action=import_contract', postData);
		if(client != null)
		{
			var xml = client.responseXML;
			var report = client.responseXML.documentElement;
			var tags = report.getElementsByTagName('success');
			if(tags.length > 0)
			{
				alert("ILR Form saved!");
				window.history.go(-1);
			}
		}
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
	<div class="Title"><?php echo htmlspecialchars((string)$vo->title); ?></div>
	<div class="ButtonBar">
		<button onclick="window.location.href='<?php echo $_SESSION['bc']->getPrevious();?>';">Close</button>
		<?php if($acl->isAuthorised($_SESSION['user'], 'write') || ($_SESSION['user']->type == User::TYPE_MANAGER && DB_NAME=="am_lead")) { ?>
		<button onclick="window.location.replace('do.php?id=<?php echo $vo->id; ?>&_action=edit_contract');">Edit</button>
		<?php } ?>
		<?php if($_SESSION['user']->type!=12 && $_SESSION['user']->type!=User::TYPE_ORGANISATION_VIEWER){?>
		<button onclick="deleteRecord();">Delete</button>
		<?php }?>
			<button onclick="window.location.replace('do.php?contract_id=<?php echo $id; ?>&_action=edit_ilr<?php echo $contract_year?>&template=1');">ILR Template</button>
<!--			<button onclick="if(prompt('Password','')=='thereisnopassword')window.location.replace('do.php?contract_id=<?php echo $vo->id; ?>&_action=import_contracts');">Migrate</button>-->
<!-- 	<button onclick="showHideBlock('div_addImportContracts');"> Import Previous Contract </button> -->
		<?php if(SOURCE_BLYTHE_VALLEY || SOURCE_LOCAL) { ?><button onclick="window.location.replace('do.php?contract_id=<?php echo $vo->id; ?>&_action=generate_violations_report');">Validate</button><?php } ?>
	</div>
	<div class="ActionIconBar">
		<button onclick="window.print()" title="Print-friendly view"><img src="/images/btn-printer.gif" width="16" height="16" style="vertical-align:text-bottom" /></button>
		<button onclick="window.location.reload(false);" title="Refresh view (see the latest changes from other users)"><img src="/images/btn-refresh.gif" width="16" height="16" style="vertical-align:text-bottom" /></button>
	</div>
</div>

<?php $_SESSION['bc']->render($link); ?>

<form name="form2" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
<div id="div_addImportContracts" align="center" style="margin-top:10px;margin-bottom:20px;<?php echo $showPanel == '1'?'display:block':'display:none'; ?>">
	<table border="0" style="margin:10px;background-color:silver; border:1px solid black;padding:3px;">
		<tr>
			<td align="right">Select Contracts</td>
			<td><?php echo HTML::select('contracts', $contracts, null, true); ?></td>
			<td>
			<div style="margin:20px 0px 20px 10px">
				<span class="button" onclick="importContract();"> Import </span>
			</div>	
			</td>
		</tr>
	</table>
</div>
</form>


<h3>Details</h3>
<table border="0" cellspacing="4" cellpadding="4" style="margin-left:10px">
	<col width="150" />
	<tr>
		<td class="fieldLabel">Title:</td><td class="fieldValue"><?php echo htmlspecialchars((string)$vo->title); ?></td>
		<td class="fieldLabel">Description:</td><td class="fieldValue"><?php echo htmlspecialchars((string)$vo->description); ?></td>
	</tr>
	<tr>
		<td class="fieldLabel">Contract Holder:</td><td class="fieldValue"><?php echo htmlspecialchars((string) $contract_holder); ?></td>
		<td class="fieldLabel">Funded:</td><td class="fieldValue"><?php echo htmlspecialchars((string) DAO::getSingleValue($link,"select contract_type from lookup_contract_types where id = '$vo->funded'")); ?></td>
	</tr>
	<tr>
		<td class="fieldLabel">Location:</td><td class="fieldValue"><?php echo htmlspecialchars((string) $contract_location); ?></td>
		<td class="fieldLabel">Contract Year:</td><td class="fieldValue"><?php echo htmlspecialchars((string)$vo->contract_year.'-'.str_pad((substr($vo->contract_year,2,2)+1),2,'0',0)); ?></td>
	</tr>
<!-- 	<tr>
		<td class="fieldLabel">Start Date:</td><td class="fieldValue"><?php //echo htmlspecialchars(Date::toMedium($vo->start_date)); ?></td>
		<td class="fieldLabel">End Date:</td><td class="fieldValue"><?php //echo htmlspecialchars(Date::toMedium($vo->end_date)); ?></td>
	</tr>
-->	

	<tr>
		<td class="fieldLabel">UPIN:</td><td class="fieldValue"><?php echo htmlspecialchars((string)$vo->upin); ?></td>
		<td class="fieldLabel">UKPRN:</td><td class="fieldValue"><?php echo htmlspecialchars((string)$vo->ukprn); ?></td>
	</tr>
</table>

<?php if ($_SESSION['user']->isAdmin() && SystemConfig::get('smartassessor.soap.enabled')) : ?>
<h3>Learner Synchronisation Options</h3>
<table border="0" cellspacing="4" cellpadding="4" style="margin-left:10px">
	<col width="150" /><col width="200"/>
	<tr>
		<td class="fieldLabel_compulsory">Smart Assessor:</td>
		<td class="fieldValue"><?php echo HTML::yesNoUnknown($vo->sync_learners_smart_assessor); ?></td>
	</tr>
</table>
	<?php endif; ?>

<?php /*if($data->TrainingRecords>1) 

echo "<h3> Learner Progress Summary </h3>";

$graph = new BAR_GRAPH("hBar");
$graph->values = $data->OnTrack . ',' . $data->Behind;
$graph->labels = "On Track, Behind";
$graph->showValues = 1;
$graph->barWidth = 20;
$graph->barLength = 2.9;
$graph->labelSize = 12;
$graph->absValuesSize = 12;
$graph->percValuesSize = 12;
$graph->graphPadding = 10;
$graph->graphBGColor = "#ABCDEF";
$graph->graphBorder = "1px solid blue";
$graph->barColors = "#A0C0F0,#0A0B0C";
$graph->barBGColor = "#E0F0FF";
$graph->barBorder = "2px outset white";
$graph->labelColor = "#000000";
$graph->labelBGColor = "#C0E0FF";
$graph->labelBorder = "2px groove white";
$graph->absValuesColor = "#000000";
$graph->absValuesBGColor = "#FFFFFF";
$graph->absValuesBorder = "2px groove white";
echo $graph->create();
?>

<?php if($data->TrainingRecords>1) 
{ ?>

<!-- <h3> Progress Summary </h3>
<table style='margin-bottom:50px; border-style:solid; border-color:grey; ' border="none" id=tblgraph align='left' cellpadding="6" cellspacing=0>
<tr style='width:100%; '>
<td align=left width="120px"> <div style='width:100; height:3'>Training Records (<?php echo $data->TrainingRecords; ?>)</div></td>
<td align=left width="480px" valign=middle> <div style='background-color:MidnightBlue; width:100%;' /> 
<p style='position:relative; left:100px'> <font face=arial size='-2'>&nbsp;</font></p></td> <td> 100% </td></tr>

<tr style='width:100%'>
<td align=left width="120px"> <div style='width:100; height:3'> On Track (<?php echo $data->OnTrack; ?>) </div></td>
<td align=left width="480px"> <div style='background-color:DarkGreen; width:<?php echo ($data->OnTrack/$data->TrainingRecords*100);?>%;' /> 
<p style='position:relative; left:150px'> <font face=arial size='-2'>&nbsp;</font></p></td> <td> <?php echo round(($data->OnTrack/$data->TrainingRecords*100),2);?>% </td> </tr>

<tr style='width:100%'>
<td align=left width="120px"> <div style='width:100; height:3'> Behind (<?php echo $data->Behind; ?>)</div></td>
<td align=left width="480px"> <div style='background-color:DarkRed; width:<?php echo ($data->Behind/$data->TrainingRecords*100);?>%;' /> 
<p style='position:relative; left:150px'> <font face=arial size='-2'>&nbsp;</font></p></td> <td><?php echo round(($data->Behind/$data->TrainingRecords*100),2);?>% </td></tr>
</table>
</p></div>
<br><br><br><br><br><br><br>
-->
<?php } ?>
*/
?>
<h3>Status of ILRs  </h3>
<br>
<div align="left"><table class="resultset" border="0" cellspacing="0" cellpadding="6" style="margin-left:10px">
<thead><tr><th>&nbsp;</th><th> Submission </th> <th> Last Submisison Date </th> <th> Valid / Invalid </th><th> Approved / Unapproved </th> <th> Active / Suspended </th> 
<th><a href="do.php?id=<?php echo $vo->id; ?>&_action=edit_profile&data=profile">Profile</a></th> 
<th><a href="do.php?id=<?php echo $vo->id; ?>&_action=edit_profile&data=pfr">PFR</a></th> 
<th> Status  </th></tr></thead>
<tbody>
<?php 

	if($vo->funding_body=='2')
	{
		echo '<tr>';
		$vo3->render($link,'W01');
		echo '</tr><tr>';
		$vo3->render($link,'W02');
		echo '</tr><tr>';
		$vo3->render($link,'W03');
		echo '</tr><tr>';
		$vo3->render($link,'W04');
		echo '</tr><tr>';
		$vo3->render($link,'W05');
		echo '</tr><tr>';
		$vo3->render($link,'W06');
		echo '</tr><tr>';
		$vo3->render($link,'W07');
		echo '</tr><tr>';
		$vo3->render($link,'W08');
		echo '</tr><tr>';
		$vo3->render($link,'W09');
		echo '</tr><tr>';
		$vo3->render($link,'W10');
		echo '</tr><tr>';
		$vo3->render($link,'W11');
		echo '</tr><tr>';
		$vo3->render($link,'W12');
		echo '</tr><tr>';
		$vo3->render($link,'W13');
		echo '</tr>';
	}
	elseif($vo->funding_body=='1')
	{
		echo '<tr>';
		$vo3->render($link,'W01');
		echo '</tr><tr>';
		$vo3->render($link,'W02');
		echo '</tr><tr>';
		$vo3->render($link,'W03');
		echo '</tr><tr>';
		$vo3->render($link,'W04');
		echo '</tr><tr>';
		$vo3->render($link,'W05');
		echo '</tr>';
	}
?>
</tbody></table></div align="center">

<!-- <h3>Training Records </h3>
<?php //$vo4->render($link); ?>
-->
<!-- 
<h3>Document Access Control</h3>
<table border="0" cellspacing="4" cellpadding="4" style="margin-left:10px">
<col width="140" />
	<tr>
		<td class="fieldLabel" valign="top">Read:</td>
		<td class="fieldValue"><?php echo '<p class="aclEntry">'.implode('</p><p class="aclEntry">', $acl->getIdentities('read')).'</p>'; ?></td>
	</tr>
	<tr>
		<td class="fieldLabel" valign="top">Edit:</td>
		<td class="fieldValue"><?php echo '<p class="aclEntry">'.implode('</p><p class="aclEntry">', $acl->getIdentities('write')).'</p>'; ?></td>
	</tr>
</table>

<h3>Audit Trail</h3>
<?php //Note::renderNotes($link, 'trainingrecord', $vo->id); ?>
-->
</body>
</html>