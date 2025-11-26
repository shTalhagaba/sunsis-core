<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
	"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
	<title>e-Recruitment Home Page</title>
	<link rel="stylesheet" href="common.css" type="text/css"/>
	<style type="text/css">
			/******* Background Style ******************/
		div.block
		{
			text-align: left;
			border-width: 1px;
			border-style: solid;
			border-color: #E3E3E3 #BFBFBF #BFBFBF #E3E3E3;
			padding: 8px!important;
			margin-bottom: 1.5em;
			word-wrap: break-word;
			width: 95%!important;
			/* To enable gradients in IE < 9 */
			zoom: 1;
			-moz-border-radius: 7px;
			-webkit-border-radius: 7px;
			border-radius: 7px;
			-moz-box-shadow: 3px 3px 5px rgba(127,108,56,0.4);
			-webkit-box-shadow: 3px 3px 5px rgba(127,108,56,0.4);
			box-shadow: 3px 3px 5px rgba(127,108,56,0.4);
			/* http://www.colorzilla.com/gradient-editor/ */
			background: rgb(255,255,255); /* Old browsers */
			/* IE9 SVG, needs conditional override of 'filter' to 'none' */
			background: url(data:image/svg+xml;base64,PD94bWwgdmVyc2lvbj0iMS4wIiA/Pgo8c3ZnIHhtbG5zPSJodHRwOi8vd3d3LnczLm9yZy8yMDAwL3N2ZyIgd2lkdGg9IjEwMCUiIGhlaWdodD0iMTAwJSIgdmlld0JveD0iMCAwIDEgMSIgcHJlc2VydmVBc3BlY3RSYXRpbz0ibm9uZSI+CiAgPGxpbmVhckdyYWRpZW50IGlkPSJncmFkLXVjZ2ctZ2VuZXJhdGVkIiBncmFkaWVudFVuaXRzPSJ1c2VyU3BhY2VPblVzZSIgeDE9IjAlIiB5MT0iMCUiIHgyPSIwJSIgeTI9IjEwMCUiPgogICAgPHN0b3Agb2Zmc2V0PSIwJSIgc3RvcC1jb2xvcj0iI2ZmZmZmZiIgc3RvcC1vcGFjaXR5PSIxIi8+CiAgICA8c3RvcCBvZmZzZXQ9IjEwMCUiIHN0b3AtY29sb3I9IiNmNmY2ZjYiIHN0b3Atb3BhY2l0eT0iMSIvPgogIDwvbGluZWFyR3JhZGllbnQ+CiAgPHJlY3QgeD0iMCIgeT0iMCIgd2lkdGg9IjEiIGhlaWdodD0iMSIgZmlsbD0idXJsKCNncmFkLXVjZ2ctZ2VuZXJhdGVkKSIgLz4KPC9zdmc+);
			background: -moz-linear-gradient(top,  rgba(255,255,255,1) 0%, rgba(246,246,246,1) 100%); /* FF3.6+ */
			background: -webkit-gradient(linear, left top, left bottom, color-stop(0%,rgba(255,255,255,1)), color-stop(100%,rgba(246,246,246,1))); /* Chrome,Safari4+ */
			background: -webkit-linear-gradient(top,  rgba(255,255,255,1) 0%,rgba(246,246,246,1) 100%); /* Chrome10+,Safari5.1+ */
			background: -o-linear-gradient(top,  rgba(255,255,255,1) 0%,rgba(246,246,246,1) 100%); /* Opera 11.10+ */
			background: -ms-linear-gradient(top,  rgba(255,255,255,1) 0%,rgba(246,246,246,1) 100%); /* IE10+ */
			background: linear-gradient(top,  rgba(255,255,255,1) 0%,rgba(246,246,246,1) 100%); /* W3C */
			filter: progid:DXImageTransform.Microsoft.gradient( startColorstr='#ffffff', endColorstr='#f6f6f6',GradientType=0 ); /* IE6-8 */
		}
	</style>
	<link rel="stylesheet" type="text/css" media="print" href="/print.css" />
</head>

<body id="candidates">
<div class="banner">
	<div class="Title">e-Recruitment Home Page</div>
	<div class="ButtonBar">

	</div>
	<div class="ActionIconBar">
		<button onclick="window.print()" title="Print-friendly view"><img src="/images/btn-printer.gif" width="16" height="16" style="vertical-align:text-bottom" /></button>
		<button onclick="window.location.reload(false);" title="Refresh view (see the latest changes from other users)"><img src="/images/btn-refresh.gif" width="16" height="16" style="vertical-align:text-bottom" /></button>
	</div>
</div>



<div id="infoblock">
	<?php
	$_SESSION['bc']->render($link);
	?>
</div>

<div class="block">
	<?php
	echo '<h1>View: <a href="?_action=recruitment_v2_home_page&amp;v=3&amp;y=' . intval($_REQUEST['y']) . '&amp;m=' . intval($_REQUEST['m']) . '&amp;d=' . intval($_REQUEST['d']) . '">Daily</a> | <a href="?_action=recruitment_v2_home_page&amp;v=2&amp;y=' . intval($_REQUEST['y']) . '&amp;m=' . intval($_REQUEST['m']) . '&amp;d=' . intval($_REQUEST['d']) . '">Weekly</a> | <a href="?_action=recruitment_v2_home_page&amp;v=1&amp;y=' . intval($_REQUEST['y']) . '&amp;m=' . intval($_REQUEST['m']) . '&amp;d=' . intval($_REQUEST['d']) . '">Monthly</a></h1>';
	echo $dataHTML;
	?>
</div>
<?php
//include the footer options
include_once('layout/tpl_footer.php');
?>

</body>
</html>
