<?php /* @var $vo User */  ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
 "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Discrepency</title>
<link rel="stylesheet" href="/common.css" type="text/css"/>
<script src="/js/jquery.min.js" type="text/javascript"></script>
<script src="/common.js" type="text/javascript"></script>
</head>

<body>
<div class="banner">
	<div class="Title">Discrepency Report</div>
	<div class="ButtonBar">
		<!-- <button onclick="window.location.href='do.php?_action=edit_user&people=<?php //echo $people; ?>&people_type=<?php //echo $people_type; ?>';">New</button> -->
	</div>
	<div class="ActionIconBar">
		<button onclick="window.print()" title="Print-friendly view"><img src="/images/btn-printer.gif" width="16" height="16" style="vertical-align:text-bottom" /></button>
		<button onclick="window.location.reload(false);" title="Refresh view (see the latest changes from other users)"><img src="/images/btn-refresh.gif" width="16" height="16" style="vertical-align:text-bottom" /></button>
	</div>
</div>

<?php $_SESSION['bc']->render($link); ?>

<br />

<h1>Contracts <?php echo isset($_REQUEST['submission'])? ' for period ' . $_REQUEST['submission'] : ''; ?></h1>

<form method="get" action="do.php?">
<input type="hidden" name="_action" value="kpi_report" />
<input type="hidden" name="type" value="discrepency" />
<div>Select a time period: <select name="submission">
<?php
					// sort out the WXX values
					for($i = 1; $i <= 13; $i++)
					{
						$w = 'W' . str_pad($i, 2, '0', STR_PAD_LEFT);
						echo '<option value="' . $w . '">' . $w . '</option>';
					}
?></select> <input type="submit" name="submit" value="Filter" />
</div></form>
<?php echo $html; ?>

</body>
</html>