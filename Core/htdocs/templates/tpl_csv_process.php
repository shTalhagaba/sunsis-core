<?php /* @var $view View */ ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
	"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
	<title>Sunesis</title>
	<link rel="stylesheet" href="/common.css" type="text/css"/>
	<script src="/js/jquery.min.js" type="text/javascript"></script>
	<script src="/common.js" type="text/javascript"></script>
	<script type="text/javascript" src="/yui/2.4.1/build/yahoo-dom-event/yahoo-dom-event.js"></script>

	<!-- CSS for TabView -->

	<link rel="stylesheet" type="text/css" href="/yui/2.4.1/build/tabview/assets/skins/sam/tabview.css">
	<link rel="stylesheet" type="text/css" href="/yui/2.4.1/build/tabview/assets/border_tabs.css">


	<!-- Dependency source files -->

	<script type="text/javascript" src="/yui/2.4.1/build/yahoo-dom-event/yahoo-dom-event.js"></script>
	<script type="text/javascript" src="/yui/2.4.1/build/container/container.js"></script>

	<!-- Page-specific script -->
	<script type="text/javascript" src="/yui/2.4.1/build/utilities/utilities.js"></script>
	<script type="text/javascript" src="/yui/2.4.1/build/element/element-beta.js"></script>

	<script type="text/javascript" src="/yui/2.4.1/build/treeview/treeview.js"></script>
	<script type="text/javascript" src="/yui/2.4.1/build/animation/animation.js"></script>
	<script type="text/javascript" src="/yui/2.4.1/build/accordion_menu/accordion-menu-v2.js"></script>

	<script type="text/javascript" src="/yui/2.4.1/build/dragdrop/dragdrop.js" ></script>
	<script type="text/javascript" src="/yui/2.4.1/build/tabview/tabview.js"></script>

	<script type="text/javascript">
		YAHOO.namespace("am.scope");



		function treeInit() {


			myTabs = new YAHOO.widget.TabView("demo");
		}


		YAHOO.util.Event.onDOMReady(treeInit);



	</script>



</head>

<body class="yui-skin-sam">
<div class="banner">
	<div class="Title">Sunesis Records VS CSV File</div>
<!--	<div class="ActionIconBar">
		<button onclick="window.print()" title="Print-friendly view"><img src="/images/btn-printer.gif" width="16" height="16" style="vertical-align:text-bottom" /></button>
		<button onclick="window.location.reload(false);" title="Refresh view (see the latest changes from other users)"><img src="/images/btn-refresh.gif" width="16" height="16" style="vertical-align:text-bottom" /></button>
	</div>
-->
</div>
<table style="margin-top:10px">


</table>

<div id="demo" class="yui-navset">

	<br>
	<ul class="yui-nav">
		<li class="selected"><a href="#tab1"><em>Similar Records</em></a></li>
		<li class=""><a href="#tab2"><em>Discrepancies</em></a></li>
		<li class=""><a href="#tab3"><em>Summary</em></a></li>
	</ul>

	<div class="yui-content" style='background: white'>
		<div id="tab1"><p>
			<div align="center" style="margin-top:50px;">
				<?php
				echo "<h3>Similarities</h3>";
				if(isset($report1))
					echo $report1;//$report1 declared in the act_csv_process
				?>
			</div>
			</p>
		</div>

		<div id="tab2">
			<p>
			<div align="center" style="margin-top:50px;">
				<?php
				echo "<h3>Discrepancies</h3>";
				if(isset($report2))
					echo $report2;//$report2 declared in the act_csv_process
				?>
			</div>
			</p>
		</div>

		<div id="tab3">
			<p>
			<div align="center" style="margin-top:50px;">
				<?php
				echo "<h3>Summary</h3>";

				echo $labels[0]  . " = <strong>£ " . $data[0] . "</strong>";
				echo "<br><br>";
				echo $labels[1]  . " = <strong>£ " . $data[1] . "</strong>";
				echo "<br><br><br>";
				$x = $data[1];
				$y = $data[0];
				if($x > 0)
				{
					$accuracyPercentage = (1 - (abs($x - $y)) / $x) * 100;
					$errorPercentage = 100 - $accuracyPercentage;
				}
				else
				{
					$accuracyPercentage = 0;
					$errorPercentage = 0;
				}
				echo "Accuracy = <strong><big>" . round($accuracyPercentage, "2") . "% </big></strong>";


				$k = array();
				$k[] = Array("" => "", "AccuracyPercentage" => round($accuracyPercentage, "2"),"ErrorPercentage" => round($errorPercentage, "2"), "Total" =>100);

				$report3 = new DataMatrix(array_keys($k[0]), $k, false);
				$report3->addTotalColumns(array('Accuracy', 'Error', 'Total'));

				echo "<h3>Bar Chart</h3>";
				echo $report3->to('BarChart');
				echo "<h3>Pie Chart</h3>";
				echo $report3->to('PieChart');

			?>
			</div>
			</p>




		</div>
	</div>
</div>
<?php //if(isset($report))echo $report; ?>
</body>
</html>

<!--action="do.php?_action=csv_process"-->