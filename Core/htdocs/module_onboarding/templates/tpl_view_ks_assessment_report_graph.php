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
			<h4 class="lead text-center text-bold">
				<?php echo $ddlAssessmentTypes[$type]; ?> (<?php echo $_q == 'k' ? 'Knowledge ' : ($_q == 'p' ? 'Production Processing ' : 'Skills '); ?>Elements)
<!--				<span class="btn btn-sm btn-info" onclick="showScoreGraph();"><i class="fa fa-bar-chart"></i></span>-->
			</h4>
		</div>
	</div>

		<?php
		$i = 1;
		foreach($this->graphs AS $graph)
		{
			if($i == 1) echo '<div class="row">';

			echo '<div class="col-sm-3">';
				echo '<div class="box box-primary">';
					echo '<div class="box-body">';
						echo '<div id="'.$graph->graph_id.'"></div>';
					echo '</div>';
					echo '<div class="box-footer small">';
						echo $graph->question_desc;
					echo '</div>';
				echo '</div>';
			echo '</div>';
			if(in_array($i, [4,8,12,16,20]))  echo '</div><div class="row">';
			$i++;
		}
		?>
		</div>
<!--</div>-->


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

	<?php
	foreach($this->graphs AS $graph)
	{
		echo 'var chart = new Highcharts.chart(\''.$graph->graph_id.'\', '.$graph->graph.');';
	}
	?>

	});

</script>

</body>
</html>