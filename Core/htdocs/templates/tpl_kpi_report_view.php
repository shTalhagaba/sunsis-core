<?php /* @var $vo User */  ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
 "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title><?php echo $reportInfo['title']; ?></title>
<link rel="stylesheet" href="/common.css" type="text/css"/>
<script src="/js/jquery.min.js" type="text/javascript"></script>
<script src="/common.js" type="text/javascript"></script>
<?php if($reportName != 'starters_leavers_achievers') { ?>
<script type="text/javascript" src="/js/jquery.tablesorter.min.js"></script>
<script type="text/javascript">
$(document).ready(function() 
	    {
	        $("#dataMatrix").tablesorter(); 
	    } 
	); 
	   
</script>
<?php } ?>
</head>

<body>
<div class="banner">
	<div class="Title"><?php echo $reportInfo['title']; ?></div>
	<div class="ButtonBar">

	</div>
	<div class="ActionIconBar">
		<button onclick="window.print()" title="Print-friendly view"><img src="/images/btn-printer.gif" width="16" height="16" style="vertical-align:text-bottom" /></button>
		<button onclick="window.location='<?php echo $url . '&output=CSV'; ?>'" title="Export to .CSV file"><img src="/images/btn-excel.gif" width="16" height="16" style="vertical-align:text-bottom" /></button>			
		<button onclick="window.location.reload(false);" title="Refresh view (see the latest changes from other users)"><img src="/images/btn-refresh.gif" width="16" height="16" style="vertical-align:text-bottom" /></button>
	</div>
</div>

<?php $_SESSION['bc']->render($link); ?>



<?php echo '<h3>' . $reportInfo['title'] . '</h3><p>' . $reportInfo['description'] . '</p>' . $outputHTML; ?>

</body>
</html>