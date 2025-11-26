<?php /* @var $view VoltView*/ ?>
<!DOCTYPE html>
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<title>View K&S Assessment Report</title>
	<link rel="stylesheet" href="module_tracking/css/common.css" type="text/css"/>
	<link rel="stylesheet" href="/assets/adminlte/bootstrap/css/bootstrap.min.css">
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.5.0/css/font-awesome.min.css">
	<link rel="stylesheet" href="/assets/adminlte/dist/css/AdminLTE.min.css">
	<link rel="stylesheet" href="/assets/adminlte/plugins/jQueryUI/jquery-ui.css">

	<!--[if lt IE 9]>
	<script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
	<script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
	<![endif]-->

	<style>
		body {
			overflow: scroll;
		}
		.ui-datepicker .ui-datepicker-title select {
			color: #000;
		}
	</style>
</head>
<body>
<div class="row">
	<div class="col-lg-12">
		<div class="banner">
			<div class="Title" style="margin-left: 6px;">View K&S Assessment Report</div>
			<div class="ButtonBar">
				<span class="btn btn-sm btn-default" onclick="window.location.href='<?php echo $_SESSION['bc']->getPrevious(); ?>';"><i class="fa fa-arrow-circle-o-left"></i> Cancel</span>
			</div>
			<div class="ActionIconBar">
				<span class="btn btn-sm btn-info fa fa-filter" onclick="showHideBlock('div_filters');showHideBlock('div_filter_crumbs');" title="Show/hide filters"></span>
				<span class="btn btn-sm btn-info fa fa-file-excel-o" onclick="window.location.href='do.php?_action=view_ks_assessment_report&subaction=export_csv'" title="Export to .CSV file"></span>
				<span class="btn btn-sm btn-info fa fa-refresh" onclick="window.location.reload(false);" title="Refresh view (see the latest changes from other users)"></span>
			</div>
		</div>
	</div>
</div>
<div class="row">
	<div class="col-lg-12">
		<?php $_SESSION['bc']->render($link); ?>
	</div>
</div>

<div class="container-fluid">
	<p><br></p>

	<div class="row">
		<div class="col-sm-12">
			<h4 class="lead text-center text-bold"><?php echo $ddlAssessmentTypes[$type]; ?> (<?php echo $_q == 'k' ? 'Knowledge ' : ($_q == 'p' ? 'Production Processing ' : 'Skills '); ?>Elements)</h4>
			<span class="btn btn-sm btn-info" onclick="showGraph();"><i class="fa fa-bar-chart"></i></span>
		</div>
	</div>


</div>

</div>

<div id="dialogDeleteRecord" style="display:none" title="Scores Graph">
	<div id="graph" style="width: 500px; height: 400px;"></div>
</div>


<script src="/assets/adminlte/plugins/jQuery/jquery-2.2.3.min.js"></script>
<script src="/assets/adminlte/plugins/jQueryUI/jquery-ui.min.js"></script>
<script src="/common.js" type="text/javascript"></script>
<script src="/assets/adminlte/bootstrap/js/bootstrap.min.js"></script>
<script src="https://code.highcharts.com/highcharts.src.js"></script>
<script src="https://code.highcharts.com/highcharts-3d.js"></script>
<script src="https://code.highcharts.com/modules/exporting.js"></script>
<script src="https://code.highcharts.com/modules/no-data-to-display.js"></script>

<script language="JavaScript">
	$(function() {
		$('img[title="Show calendar"]').hide();
		$(".DateBox").datepicker();

		$('#dialogDeleteRecord').dialog({
			modal: true,
			width: 550,
			closeOnEscape: true,
			autoOpen: false,
			resizable: false,
			draggable: false
		});


		var chart = new Highcharts.chart('graph', <?php echo $graph; ?>);
	});

	function showGraph()
	{
		var $dialog = $('#dialogDeleteRecord');



		$dialog.dialog("open");
	}

</script>

</body>
</html>