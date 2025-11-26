<?php /* @var $vo Organisation */ ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
	"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1"/>
	<title>Management Dashboard</title>
	<link rel="stylesheet" href="/common.css" type="text/css"/>
<!--	<link rel="stylesheet" href="/css/view_dashboard.css" type="text/css"/>-->
	<script src="/js/jquery.min.js" type="text/javascript"></script>
	<script src="/common.js" type="text/javascript"></script>

	<link rel="stylesheet" href="/jquery-ui/css/jquery-ui-1.8.11.custom.css" type="text/css"/>
	<script language="JavaScript" src="/jquery-ui/js/jquery-1.5.2.min.js"></script>
	<script language="JavaScript" src="/jquery-ui/js/jquery-ui-1.8.11.custom.min.js"></script>
	<script language="JavaScript" src="/common.js"></script>
	<script type="text/javascript" src="/scripts/view_dashboard.js?i=8181"></script>

	<script language="javascript" src="/js/highcharts2.js" type="text/javascript"></script>
	<script language="javascript" src="/js/highcharts-more.js" type="text/javascript"></script>
	<script src="/js/modules/exporting.js" type="text/javascript"></script>
	<script>

	</script>

</head>

<body>
<div class="banner">
	<div class="Title">Management Dashboards</div>
	<div class="ButtonBar">
		<button onclick="window.location.href='<?php echo $_SESSION['bc']->getPrevious();?>';">Close</button>
		<button onclick="resetToDefaultPosition('');">Reset Default Positions</button>
		<?php echo HTML::select('dashboard_panels', DAO::getResultset($link, "SELECT panel_name, panel_heading, null FROM dashboard_panels WHERE visible = 0 AND user = '" . $_SESSION['user']->username . "' "), '', true); ?>
	</div>
	<div class="ActionIconBar">
		<button onclick="window.print()" title="Print-friendly view"><img src="/images/btn-printer.gif" width="16" height="16" style="vertical-align:text-bottom"/></button>
	</div>
</div>

<div class="panels" id="panels">
	<?php
	$i = 1;
	foreach($allPanels AS $panel)
	{
		if(in_array($panel['panel_name'], $panelsToShow))
		{
			echo "<div class=\"panel\" id=\"panel" . $i . "\" title = \"" . $panel['panel_heading'] . "\" ></div>";
		}
		$i++;
	}
	?>
</div>
<script type="text/javascript">
	$( document ).ready(function() {
		var panelsToShow = "<?php echo implode(',', $panelsToShow); ?>";
		var panelsToShow = panelsToShow.split(',');
		for (var i = 0; i < panelsToShow.length; i++)
		{
			//doCheck('panel'+i);
			doCheck(panelsToShow[i]);
		}
	});

	function doCheck(panel) {
		$.ajax({
			type: "POST",
			url: "do.php?_action=view_dashboard",
			data: "p="+panel,
			async:true,
			success: function(text)
			{
				response = text;
				var script = document.createElement("script");
				script.innerHTML = response;
				document.head.appendChild(script);
			}
		});
	}




</script>

</body>
</html>