<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
 "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Eclipse Central Administration</title>
<script src="/js/jquery.min.js" type="text/javascript"></script>
<script src="/common.js" type="text/javascript"></script>
</head>

<link rel="stylesheet" href="/common.css" type="text/css"/>


<body>
<div class="banner">
	<div class="Title">Database Status</div>
	<div class="ButtonBar">
		<button onclick="window.history.go(-1);">Back</button>
	</div>
	<div class="ActionIconBar">

	</div>
</div>

<h3 class="introduction">Introduction</h3>
<p class="introduction">This page allows Perspective system administrators
to monitor the status of the MySQL server. This page is only accessible
from Blythe Valley IP addresses.</p>


<h3>Statistics</h3>
<?php echo $status ?>

<h3>Table Sizes</h3>
<?php $this->renderTableSizes($link); ?>

<h3>Server and connection variables</h3>
<?php $this->renderServerVariables($link); ?>


</body>
</html>
