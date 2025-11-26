<?php /* @var $view VoltView */ ?>
<!DOCTYPE html>
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<title>Recruitment - Analytics</title>
	<link rel="stylesheet" href="/assets/adminlte/bootstrap/css/bootstrap.min.css">
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.5.0/css/font-awesome.min.css">
	<link rel="stylesheet" href="/assets/adminlte/dist/css/AdminLTE.min.css">
	<link rel="stylesheet" href="module_charts/assets/styles.css">

	<!--[if lt IE 9]>
	<script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
	<script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
	<![endif]-->


</head>
<body class="hold-transition skin-blue sidebar-mini">

<div class="content-wrapper" style="background-color: white;">
	<section class="content-header">
		<div class="row">
			<div class="col-sm-6">
				<div class="box">
					<div class="box-body"><div id="panelSearchesKeywords" style="min-width: 300px; height: 400px; margin: 30 auto"></div></div>
				</div>
			</div>
			<div class="col-sm-6">
				<div class="box">
					<div class="box-body"><div id="panelSearchesLocations" style="min-width: 300px; height: 400px; margin: 30 auto"></div></div>
				</div>
			</div>
			<div class="col-sm-6">
				<div class="box">
					<div class="box-body"><div id="panelLearnersByEthnicity" style="min-width: 300px; height: 400px; margin: 30 auto"></div></div>
				</div>
			</div>
			<div class="col-sm-6">
				<div class="box">
					<div class="box-body"><div id="panelLearnersByAgeBand" style="min-width: 300px; height: 400px; margin: 30 auto"></div></div>
				</div>
			</div>
			<div class="col-sm-6">
				<div class="box">
					<div class="box-body"><div id="panelLearnersByGender" style="min-width: 300px; height: 400px; margin: 30 auto"></div></div>
				</div>
			</div>
			<div class="col-sm-6">
				<div class="box">
					<div class="box-body"><div id="panelLearnersByPostcode" style="min-width: 300px; height: 400px; margin: 30 auto"></div></div>
				</div>
			</div>
		</div>
	</section>
</div>


<script src="/assets/adminlte/plugins/jQuery/jquery-2.2.3.min.js"></script>
<script src="/assets/adminlte/bootstrap/js/bootstrap.min.js"></script>
<script src="/common.js" type="text/javascript"></script>
<script src="https://code.highcharts.com/highcharts.src.js"></script>
<script src="https://code.highcharts.com/highcharts-3d.js"></script>
<script src="https://code.highcharts.com/modules/exporting.js"></script>
<script src="https://code.highcharts.com/modules/no-data-to-display.js"></script>
<script src="https://code.highcharts.com/modules/wordcloud.js"></script>
<script src="module_charts/assets/script.js"></script>

<script>
	$(document).ready(function(){

		var chart = new Highcharts.chart('panelSearchesKeywords', <?php echo $panelSearchesKeywords; ?>);
		var chart = new Highcharts.chart('panelSearchesLocations', <?php echo $panelSearchesLocations; ?>);
		var chart = new Highcharts.chart('panelLearnersByEthnicity', <?php echo $panelLearnersByEthnicity; ?>);
		var chart = new Highcharts.chart('panelLearnersByAgeBand', <?php echo $panelLearnersByAgeBand; ?>);
		var chart = new Highcharts.chart('panelLearnersByGender', <?php echo $panelLearnersByGender; ?>);
		var chart = new Highcharts.chart('panelLearnersByPostcode', <?php echo $panelLearnersByPostcode; ?>);


	});

</script>

</body>
</html>

