<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
	"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
	<title>Smart Assessor: Learner IV Comparison</title>
	<link rel="stylesheet" href="/common.css" type="text/css"/>
	<link rel="stylesheet" href="/jquery-ui/css/jquery-ui-1.8.17.custom.css" type="text/css"/>
	<link rel="stylesheet" type="text/css" media="print" href="/print.css" />
	<script src="/js/jquery.min.js" type="text/javascript"></script>
	<script src="/common.js" type="text/javascript"></script>
	<script src="/jquery-ui/js/jquery-ui-1.8.17.custom.min.js" type="text/javascript"></script>

	<script type="text/javascript">
		$(function(){
			showProgressAnimation('loading51.gif');
			$('div#content').load('do.php?_action=sa_learnerlinkiv_diff&subaction=rendercontent', null, onContentLoad);
		});

		function showProgressAnimation(filename)
		{
			filename = filename ? filename : 'loading51.gif'; // default
			$('div#content').html('<img src="/images/progress-animations/' + encodeURIComponent(filename) + '"/>');
		}

		function onContentLoad(responseText, textStatus, xmlHttpRequest)
		{

		}

	</script>

	<style type="text/css">
		h3, h3:first-child {
			margin-top: 50px;
			width: 100%;
			text-align: left;
		}

		h3.introduction {
			width: 100%;
			padding: 0px 0px 3px 0px;
			margin: 0px;
		}

		p.introduction {
			width: 100%;
			padding: 0px;
			margin: 10px 0px 0px 0px;
		}

		ul.introduction {
			font-family: sans-serif;
			font-size: 11pt;
			color: #176281;
			font-style: normal;
			text-align: justify;
			margin: 15px 0px 10px 0px;
			/*width: 800px;*/
		}

		li {
			margin-top: 5px;
		}

		div#Filters {
			margin-top: 40px;
			text-align: center;
			background-color: #bbca85;
			padding: 8px;
			width: 95%;
			margin-left: auto;
			margin-right: auto;

			border-radius: 8px;

			-moz-box-shadow: 3px 3px 5px rgba(127,108,56,0.4);
			-webkit-box-shadow: 3px 3px 5px rgba(127,108,56,0.4);
			box-shadow: 3px 3px 5px rgba(127,108,56,0.4);
		}

		div#content {
			margin-top: 25px;
			text-align: center;
		}

		td.Highlight {
			background-color: yellow;
		}

		td.SmartAssessorId {
			font-size: 6pt;
			font-family: 'Segoe UI', Tahoma, Sans-Serif;
		}

		td.BrokenLinkId {
			color: gray;
			text-decoration: line-through;
		}

		table.CompareRecords {
			margin-top: 20px;
			margin-left: auto;
			margin-right: auto;
			width:800px;
			table-layout:fixed;
			word-wrap: break-word;
		}

		table.CompareRecords:first-child {
			margin-top: 0px;
		}

	</style>

</head>

<body>
<div class="banner">
	<div class="Title">Smart Assessor: Learner IV Comparison</div>
	<div class="ButtonBar">

	</div>
	<div class="ActionIconBar">
		<button onclick="window.print()" title="Print-friendly view"><img src="/images/btn-printer.gif" width="16" height="16" style="vertical-align:text-bottom" /></button>
		<button onclick="window.location.reload(false);" title="Refresh view (see the latest changes from other users)"><img src="/images/btn-refresh.gif" width="16" height="16" style="vertical-align:text-bottom" /></button>
	</div>
</div>

<div style="width:800px;margin-left:auto;margin-right:auto;">
	<h3 class="introduction">Instructions</h3>
	<div class="Newspaper">
		<p class="introduction">This report details the differences (if any) in learner IV records linked between
		Sunesis and Smart Assessor. The differences will be resolved at the next scheduled synchronisation.</p>
	</div>
</div>

<div id="content"></div>

</body>

</html>