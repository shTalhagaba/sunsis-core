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
		if(window.confirm("Delete this record?"))
		{
			window.location.replace('do.php?_action=delete_awarding_body&id=<?php echo $vo->id; ?>');
		}
	}

function populate()
{
	var grid_level = document.getElementById('grid_level');
	grid_level.clear();
	var ty = "<?php echo $vo->organisation_type;?>";
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
	<div class="Title"><?php echo $page_title ?></div>
	<div class="ButtonBar">
		<button onclick="window.location.href='<?php echo $_SESSION['bc']->getPrevious();?>';">Close</button>
		<button onclick="window.location.replace('do.php?id=<?php echo $vo->id; ?>&_action=edit_awarding_body');">Edit</button>
		<?php if($_SESSION['user']->type!=12){?>
			<button onclick="deleteRecord();">Delete</button>
		<?php } ?>
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
	<tr><td class="fieldLabel">Trading name:</td><td class="fieldValue"><?php echo htmlspecialchars((string)$vo->trading_name); ?></td></tr>
	<tr><td class="fieldLabel">Abbreviation:</td><td class="fieldValue"><?php echo htmlspecialchars((string)$vo->short_name); ?></td></tr>
	<tr><td class="fieldLabel">Category:</td><td class="fieldValue">Awarding Body</td></tr>
	<tr><td class="fieldLabel">Company Number:</td><td class="fieldValue"><?php echo htmlspecialchars((string)$vo->company_number); ?></td></tr>



<!-- <tr>
		<td class="fieldLabel_compulsory" valign="top">Type:</td>
		<td class="fieldValue"><?php //echo HTML::checkboxGrid('level', $type_checkboxes, null, 3, false); ?></td>
	</tr>
-->
</table>

<h3>Locations</h3>
<span class="button" style="margin-bottom: 15px;" onclick="window.location.href='do.php?_action=edit_location&organisations_id=<?php echo $vo->id; ?>&back=<?php echo "contractholder"; ?>'"> Add new location </span>
<?php $locations->render($link,'read_contractholder'); ?>

<!-- 
<h3>Apprentices</h3>
<span class="button" onclick="window.location.href='do.php?_action=edit_user&organisations_id=<?php echo $vo->id; ?>&people=<?php echo "Learner"; ?>&people_type=<?php echo 5; ?>'"> Add new apprentice </span>
<?php //$vo3->render($link); ?>

<h3>Other Learners</h3>
<span class="button" onclick="window.location.href='do.php?_action=edit_user&organisations_id=<?php echo $vo->id; ?>&people=<?php echo "Other Learner"; ?>&people_type=<?php echo 16; ?> '"> Add other learner </span>
<?php //$vo5->render($link);?>
-->
<h3>Qualifications</h3>

<?php $vo6->render($link);?>


<h3>System Users</h3>
<span class="button" onclick="window.location.href='do.php?_action=edit_user&organisations_id=<?php echo $vo->id; ?>&route=1&people_type=<?php echo 17; ?>'"> Add External verifier </span>
<br></br>
<?php $this->renderPersonnel($link, $vo); ?>

<!-- 
<h3>Training Records</h3>
<?php //$vo4->render($link); ?>
-->


</body>
</html>