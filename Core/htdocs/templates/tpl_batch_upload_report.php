<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
 "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Qualifications</title>
<link rel="stylesheet" href="/common.css" type="text/css"/>
<link rel="stylesheet" type="text/css" media="print" href="/print.css" />
<script src="/js/jquery.min.js" type="text/javascript"></script>
<script src="/common.js" type="text/javascript"></script>
</head>
<body>
<div class="banner">
	<div class="Title">Batch Upload Report</div>
	<div class="ButtonBar">
	</div>
	<div class="ActionIconBar">
	</div>
</div>

<?php 
echo '<table class="resultset"><tr><th>Centre Number</th><th>Qualification</th><th>Result</th></tr>';
for($a=0; $a<count($centres);$a++)
{
	echo '<tr><td>' . $centres[$a] . '</td><td>' . $quals[$a] . '</td><td>' . $reports[$a] . '</td></tr>';
}
echo '</table>';

?>
 
</body>
</html>