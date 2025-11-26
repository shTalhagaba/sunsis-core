<?php /* @var $vo Screen1 */ ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
	"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
	<title>Goods Receipt Note</title>
	<link rel="stylesheet" href="/common.css" type="text/css"/>
	<script src="/js/jquery.min.js" type="text/javascript"></script>
	<script src="/common.js" type="text/javascript"></script>

	<script type="text/javascript" src="/yui/2.4.1/build/yahoo-dom-event/yahoo-dom-event.js"></script>


	<script language="JavaScript">
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
	<div class="Title">Goods Receipt Note</div>
	<div class="ButtonBar">
		<button onclick="window.location.href='<?php echo $_SESSION['bc']->getPrevious();?>';">Close</button>
		&nbsp;
		<button onclick="window.location.replace('do.php?cps=<?php echo $vo->cps; ?>&_action=edit_screen1');">Edit</button>
	</div>
	<div class="ActionIconBar">
		<button onclick="window.print()" title="Print-friendly view"><img src="/images/btn-printer.gif" width="16" height="16" style="vertical-align:text-bottom" /></button>
		<button onclick="window.location.reload(false);" title="Refresh view (see the latest changes from other users)"><img src="/images/btn-refresh.gif" width="16" height="16" style="vertical-align:text-bottom" /></button>
	</div>
</div>

<?php $_SESSION['bc']->render($link); ?>

<h3>Goods Receipt Note</h3>
<table border="0" cellspacing="4" cellpadding="4">
	<col width="150" />
	<tr><td class="fieldLabel">CPS No.:</td><td class="fieldValue"><?php echo htmlspecialchars((string)$vo->cps); ?></td></tr>
	<tr><td class="fieldLabel">Date Received:</td><td class="fieldValue"><?php echo htmlspecialchars((string)$vo->date_received); ?></td></tr>
	<tr><td class="fieldLabel">Location:</td><td class="fieldValue"><?php echo htmlspecialchars((string)$vo->location); ?></td></tr>
	<tr><td class="fieldLabel">NATO No:</td><td class="fieldValue"><?php echo htmlspecialchars((string)$vo->nato); ?></td></tr>
	<tr><td class="fieldLabel">Multi-Part Pack List:</td><td class="fieldValue"><?php echo htmlspecialchars((string)$vo->multi_part); ?></td></tr>
	<tr><td class="fieldLabel">Repair & Refurb / New Case:</td><td class="fieldValue"><?php echo htmlspecialchars((string)$vo->repair_list); ?></td></tr>
	<tr><td class="fieldLabel">Received From / AC:</td><td class="fieldValue"><?php echo htmlspecialchars((string)$vo->recevied_from); ?></td></tr>
	<tr><td class="fieldLabel">For Customer:</td><td class="fieldValue"><?php echo htmlspecialchars((string)$vo->for_customer); ?></td></tr>
	<tr><td class="fieldLabel">Transport:</td><td class="fieldValue"><?php echo htmlspecialchars((string)$vo->transport); ?></td></tr>
	<tr><td class="fieldLabel">Advice Note No:</td><td class="fieldValue"><?php echo htmlspecialchars((string)$vo->advice_note); ?></td></tr>
	<tr><td class="fieldLabel">DMC:</td><td class="fieldValue"><?php echo htmlspecialchars((string)$vo->DMC); ?></td></tr>
	<tr><td class="fieldLabel">Pack Level:</td><td class="fieldValue"><?php echo htmlspecialchars((string)$vo->pack_level); ?></td></tr>
	<tr><td class="fieldLabel">Order No:</td><td class="fieldValue"><?php echo htmlspecialchars((string)$vo->order_no); ?></td></tr>
	<tr><td class="fieldLabel">Description:</td><td class="fieldValue"><?php echo htmlspecialchars((string)$vo->description); ?></td></tr>
	<tr><td class="fieldLabel">Note:</td><td class="fieldValue"><?php echo htmlspecialchars((string)$vo->note); ?></td></tr>
	<tr><td class="fieldLabel">Supp 640 No.:</td><td class="fieldValue"><?php echo htmlspecialchars((string)$vo->supp_640); ?></td></tr>
	<tr><td class="fieldLabel">Supp Cont No.:</td><td class="fieldValue"><?php echo htmlspecialchars((string)$vo->supp_cont_no); ?></td></tr>
	<tr><td class="fieldLabel">Warrant No.:</td><td class="fieldValue"><?php echo htmlspecialchars((string)$vo->warrant_no); ?></td></tr>
	<tr><td class="fieldLabel">Br 640 in.:</td><td class="fieldValue"><?php echo htmlspecialchars((string)$vo->br_640_in); ?></td></tr>
	<tr><td class="fieldLabel">Br 640 out.:</td><td class="fieldValue"><?php echo htmlspecialchars((string)$vo->br_640_out); ?></td></tr>
	<tr><td class="fieldLabel">No and Type of Containers.:</td><td class="fieldValue"><?php echo htmlspecialchars((string)$vo->no_and_type_of_containers); ?></td></tr>
	<tr><td class="fieldLabel">Contract Type:</td><td class="fieldValue"><?php echo htmlspecialchars((string)$vo->contract_type); ?></td></tr>


</body>
</html>