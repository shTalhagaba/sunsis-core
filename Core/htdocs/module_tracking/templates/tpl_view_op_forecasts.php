
<!DOCTYPE html>
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<title>View Operations Forecasts</title>
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
		.boxHeading {
			font-family: Arial;
			font-size: 24px;
			font-weight: bold;
			color: #666;
		}
	</style>
</head>
<body>
<div class="row">
	<div class="col-lg-12">
		<div class="banner">
			<div class="Title" style="margin-left: 6px;">View Operations Forecasts</div>
			<div class="ButtonBar">
				<span class="btn btn-sm btn-default" onclick="window.location.href='<?php echo $_SESSION['bc']->getPrevious(); ?>';"><i class="fa fa-arrow-circle-o-left"></i> Cancel</span>
			</div>
			<div class="ActionIconBar">

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
	<div class="row">
		<div class="col-sm-8 col-sm-offset-2">
			<div class="well well-sm">
				<form class="form-inline" name="frmOpForecasts" action="<?php echo $_SERVER['PHP_SELF']; ?>">
					<input type="hidden" name="_action" value="view_op_forecasts" />
					<input type="hidden" name="subview" value="<?php echo $subview; ?>" />

					<div class="form-group">
						<label for="filter_month" class="col-sm-5 control-label fieldLabel_compulsory">Select Month:</label>
						<div class="col-sm-4">
							<?php echo HTML::selectChosen('filter_target_month', $this->getMonthsDDL($link, $current_contract_year), $month, true); ?>
						</div>
					</div>
					<div class="form-group">
						<label for="filter_quarter" class="col-sm-4 control-label fieldLabel_compulsory">Select Quarter:</label>
						<div class="col-sm-8">
							<?php echo HTML::selectChosen('filter_quarter', $this->getQuartersDDL($link, $current_contract_year, true), $quarter, true); ?>
						</div>
					</div>
					<div class="form-group">
						<div class="col-sm-8"><button type="submit" class="btn btn-sm btn-default"><i class="fa fa-refresh"></i> </button> </div>
					</div>
				</form>
			</div>
		</div>

	</div>
	<div class="row">
		<?php if($subview == 'view_ach_forecast_in_prog'){?>
		<div class="col-sm-12">
			<div class="box">
				<div class="box-header text-center"><span class="boxHeading">Achievers Forecast - In Progress </span> </div>
				<div class="row">
					<div class="col-sm-6">
						<div class="box-body"><div  id="panelAchieversInProgressC"><canvas id="dgtAchieversInProgressC" height="600" width="652" ></div></div>
					</div>
					<div class="col-sm-6">
						<div class="box-body"><div  id="panelAchieversInProgressT"><canvas id="dgtAchieversInProgressT" height="600" width="652" ></canvas></div></div>
					</div>
					<div class="col-sm-6">
						<div class="box-body"><div  id="panelAchieversInProgressA"><canvas id="dgtAchieversInProgressA" height="600" width="650"></canvas></div></div>
					</div>
				</div>
			</div>
		</div>
		<?php } ?>
		<?php if($subview == 'view_ach_forecast_gateway_ready'){?>
		<div class="col-sm-12">
			<div class="box">
				<div class="box-header text-center"><span class="boxHeading">Achievers Forecast - Gateway Ready </span> </div>
				<div class="row">
					<div class="col-sm-6">
						<div class="box-body"><div  id="panelEmployerReferenceComplete"><canvas id="dgtEmployerReferenceComplete" height="550" width="600" ></div></div>
					</div>
					<div class="col-sm-6">
						<div class="box-body"><div  id="panelSummativePortfolioSignedOff"><canvas id="dgtSummativePortfolioSignedOff" height="550" width="600" ></canvas></div></div>
					</div>
					<div class="col-sm-6">
						<div class="box-body"><div  id="panelGatewayDeclarationsComplete"><canvas id="dgtGatewayDeclarationsComplete" height="550" width="600"></canvas></div></div>
					</div>
					<div class="col-sm-6">
						<div class="box-body"><div  id="panelPassedToSupportServices"><canvas id="dgtPassedToSupportServices" height="550" width="600" ></div></div>
					</div>
					<div class="col-sm-6">
						<div class="box-body"><div  id="panelProjectComplete"><canvas id="dgtProjectComplete" height="550" width="600" ></canvas></div></div>
					</div>
					<div class="col-sm-6">
						<div class="box-body"><div  id="panelInterviewSet"><canvas id="dgtInterviewSet" height="550" width="600"></canvas></div></div>
					</div>
				</div>
			</div>
		</div>
		<?php } ?>
		<?php if($subview == 'view_ach_forecast_framework'){?>
		<div class="col-sm-12">
			<div class="box">
				<div class="box-body table-responsive"><div class="chart-responsive" id="panelAchieversForecast"><canvas id="barAchievers" height="300" width="700" style="width: 100%;"></div></div>
			</div>
		</div>
		<?php } ?>
	</div>
</div>


<script src="/assets/adminlte/plugins/jQuery/jquery-2.2.3.min.js"></script>
<script src="/assets/adminlte/plugins/jQueryUI/jquery-ui.min.js"></script>
<script src="/common.js" type="text/javascript"></script>
<script src="/assets/adminlte/bootstrap/js/bootstrap.min.js"></script>
<script src="/assets/adminlte/plugins/chartjs/ChartNew.js"></script>


<script language="JavaScript">

	var pie_graphs = $.parseJSON('<?php echo $pie_graphs; ?>');
	var pie_graphs1 = $.parseJSON('<?php echo $pie_graphs1; ?>');
	var bar_graph = $.parseJSON('<?php echo $bar_graph; ?>');
	var month = '<?php echo $month; ?>';


	$(function(){

		drawPieCharts();

		drawBarChart();

		drawPieChartsGatewayReady();

	});

	function fnShowPieDetail(event,ctx,config,data,other)
	{
		var panel = 'panel' + ctx.canvas.id.substring(3);
		var canvas_id = ctx.canvas.id;
		$.ajax({
			type:'GET',
			url:'do.php?_action=view_op_forecasts&subview=getPieDetail&canvas_id='+encodeURIComponent(canvas_id)+"&section="+encodeURIComponent(other.v1),
			dataType: 'json',
			beforeSend: function(){
				$("#"+panel).html("<h5 class='text-bold'><i class=\"fa fa-refresh fa-spin\"></i> loading graph ...</h5>");
			},
			success: function(response) {
				$("#"+panel).html('<canvas id="'+canvas_id+'" height="350" width="350" style="width: 100%;"></canvas>');
				var myDoughnut = new Chart(document.getElementById(canvas_id).getContext("2d")).Doughnut(response.data, response.options);
			},
			error: function(data, textStatus, xhr){
				console.log(data);
			}
		});
	}

	function drawPieCharts()
	{
		if(window.pie_graphs != '0')
		{
			if(pie_graphs.graph1.data.datasets.length > 0)
			{
				pie_graphs.graph1.options.mouseDownLeft = fnShowPieDetail;
				var graph1 = new Chart(document.getElementById("dgtAchieversInProgressC").getContext("2d")).Pie(pie_graphs.graph1.data, pie_graphs.graph1.options);
			}

			if(pie_graphs.graph2.data.datasets.length > 0)
			{
				pie_graphs.graph2.options.mouseDownLeft = fnShowPieDetail;
				var graph2 = new Chart(document.getElementById("dgtAchieversInProgressT").getContext("2d")).Pie(pie_graphs.graph2.data, pie_graphs.graph2.options);
			}

			if(pie_graphs.graph3.data.datasets.length > 0)
			{
				pie_graphs.graph3.options.mouseDownLeft = fnShowPieDetail;
				var graph3 = new Chart(document.getElementById("dgtAchieversInProgressA").getContext("2d")).Pie(pie_graphs.graph3.data, pie_graphs.graph3.options);
			}
		}
	}

	function drawPieChartsGatewayReady()
	{
		if(window.pie_graphs1 != '0')
		{
			if(pie_graphs1.graph1.data.datasets.length > 0)
				var graph1 = new Chart(document.getElementById("dgtEmployerReferenceComplete").getContext("2d")).Pie(pie_graphs1.graph1.data, pie_graphs1.graph1.options);

			if(pie_graphs1.graph2.data.datasets.length > 0)
				var graph2 = new Chart(document.getElementById("dgtSummativePortfolioSignedOff").getContext("2d")).Pie(pie_graphs1.graph2.data, pie_graphs1.graph2.options);

			if(pie_graphs1.graph3.data.datasets.length > 0)
				var graph3 = new Chart(document.getElementById("dgtGatewayDeclarationsComplete").getContext("2d")).Pie(pie_graphs1.graph3.data, pie_graphs1.graph3.options);

			if(pie_graphs1.graph4.data.datasets.length > 0)
				var graph4 = new Chart(document.getElementById("dgtPassedToSupportServices").getContext("2d")).Pie(pie_graphs1.graph4.data, pie_graphs1.graph4.options);

			if(pie_graphs1.graph5.data.datasets.length > 0)
				var graph5 = new Chart(document.getElementById("dgtProjectComplete").getContext("2d")).Pie(pie_graphs1.graph5.data, pie_graphs1.graph5.options);

			if(pie_graphs1.graph6.data.datasets.length > 0)
				var graph6 = new Chart(document.getElementById("dgtInterviewSet").getContext("2d")).Pie(pie_graphs1.graph6.data, pie_graphs1.graph6.options);
		}
	}

	function drawBarChart()
	{
		if(window.bar_graph != '0')
		{
			if(month == '')
				var lineGraph = new Chart(document.getElementById("barAchievers").getContext("2d")).Bar(bar_graph.data, bar_graph.options);
			else
				var lineGraph = new Chart(document.getElementById("barAchievers").getContext("2d")).Doughnut(bar_graph.data, bar_graph.options);
		}
	}

</script>

</body>
</html>