<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
 "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Server Status</title>
<link rel="stylesheet" href="/common.css" type="text/css"/>
<link rel="stylesheet" type="text/css" media="print" href="/print.css" />
<script src="/js/jquery.min.js" type="text/javascript"></script>
<script src="/common.js" type="text/javascript"></script>

<style type="text/css">
pre
{
	margin-left: 10px;
}

</style>

</head>

<body>
<div class="banner">
	<div class="Title">Web Server Status</div>
	<div class="ButtonBar">
		<button onclick="window.history.go(-1);">Back</button>
	</div>
	<div class="ActionIconBar">
		<button onclick="window.print()" title="Print-friendly view"><img src="/images/btn-printer.gif" width="16" height="16" style="vertical-align:text-bottom" /></button>
		<button onclick="window.location.reload(false);" title="Refresh view (see the latest changes from other users)"><img src="/images/btn-refresh.gif" width="16" height="16" style="vertical-align:text-bottom" /></button>
	</div>
</div>

<h3 class="introduction">Introduction</h3>
<p class="introduction">This page allows Perspective system administrators
to monitor the status of the host web-server. This page is only accessible
from Blythe Valley IP addresses.</p>

<h3>Running Processes</h3>
<pre>
<?php $this->renderTop(); ?>
</pre>

<h3>Free Memory</h3>
<p class="sectionDescription">The third row shows used and free memory adjusted for the filesystem cache. Units in megabytes.</p>
<pre>
<?php $this->renderFree(); ?>
</pre>

<h3>Free Entropy</h3>
<p class="sectionDescription">Entropy available for random number generation</p>
<pre>
<?php $this->renderEntropy(); ?>
</pre>

<h3>Server-wide disk usage</h3>
<p class="sectionDescription">HTML files and MySQL datafiles are stored under <code>/srv</code></p>
<pre>
<?php $this->renderDiskSpace(); ?>
</pre>

<h3>Repository disk usage</h3>
<pre>
<?php $this->renderRepositoryUsage(); ?>
</pre>

</body>

</html>