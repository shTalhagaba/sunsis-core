<?php /* @var $vo User */  ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
 "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Person</title>
<link rel="stylesheet" href="/common.css" type="text/css"/>
<script src="/js/jquery.min.js" type="text/javascript"></script>
<script src="/common.js" type="text/javascript"></script>

</head>

<body>
<div class="banner">
	<div class="Title">EDIM Reports Overview</div>
	<div class="ButtonBar">

	</div>
	<div class="ActionIconBar">
		<button onclick="window.location.reload(false);" title="Refresh view (see the latest changes from other users)"><img src="/images/btn-refresh.gif" width="16" height="16" style="vertical-align:text-bottom" /></button>
	</div>
</div>

<?php $_SESSION['bc']->render($link); ?>

<h2>List of available reports</h2>

<?php
echo '<table>';
foreach($types AS $selectValue => $options)
{
	echo '
		<form action="do.php" method="get">
		<input type="hidden" name="_action" value="kpi_report" />
		<input type="hidden" name="type" value="' . $selectValue . '" />
	';
	echo '<tr><td><strong>' . $options['title'] . '</strong> for contract: </td>';
	echo '<td><select name="submission">';
	$sql = "SELECT * from contracts where active = 1 order by contract_year desc";
	$st = $link->query($sql);
	if($st) 
	{
		while($row = $st->fetch())
		{
			echo '<option value="' . $row['id'] . '">' . $row['title'] . '</option>';
		}
	}					
	
	echo '</select></td><td> as <select name="output">';

	// output types
	foreach($options['output'] AS $key => $outputType)
	{
		echo '<option value="' . $outputType . '">' . $outputType . '</option>';
	}
	
	echo '</select> </td><td><input type="submit" name="submit" value="GO" /></form><br /></td></tr>';
}
echo '</table>';

?>

</body>
</html>