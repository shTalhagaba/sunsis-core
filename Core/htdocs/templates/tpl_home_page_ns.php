<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<title>Sunesis | Homepage</title>
	<meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
	<link rel="stylesheet" href="/assets/adminlte/bootstrap/css/bootstrap.min.css">
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.5.0/css/font-awesome.min.css">
	<link rel="stylesheet" href="/assets/adminlte/plugins/jvectormap/jquery-jvectormap-1.2.2.css">
	<link rel="stylesheet" href="/assets/adminlte/dist/css/AdminLTE.min.css">
	<link rel="stylesheet" href="/assets/adminlte/dist/css/skins/_all-skins.min.css">

	<!--[if lt IE 9]>
	<script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
	<script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
	<![endif]-->
</head>

<body>

<div class="wrapper">
<header class="main-header"></header>

<div class="content-wrapper">
<section class="content-header">
	<h1><span class="fa fa-dashboard"></span> Dashboard<span class="pull-right"><img class="img-rounded" src="images/logos/SUNlogo.png" height="35px;"/></span></h1>
</section>

<section class="content">
	<div class="row">

		<div class="col-sm-6">
			<div class="box box-primary small">
				<div class="box-header with-border"><h3 class="box-title"><span class="glyphicon glyphicon-stats"></span> New Starts</h3>
					<div class="box-tools pull-right">
						<button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
						<button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
					</div>
				</div>
				<div class="box-body">
					<span class="text-info"><i class="fa fa-info-circle"></i> New starts over the previous 6 months</span>
					<div class="table-responsive">
						<table class="table table-bordered text-center">
							<?php
							echo '<tr>';
							foreach($start_stats_previous_3_months AS $month => $detail)
							{
								echo '<td>' . $month . '</td>';
							}
							echo '<td>Total</td>';
							echo '</tr>';
							echo '<tr>';
							$total = 0;
							
							foreach($start_stats_previous_3_months AS $month => $detail)
							{
								echo '<td class="text-blue text-bold" style="cursor: pointer;" onclick="showDetail(\'' . implode(',', $detail->tr_ids) . '\');">' . count($detail->tr_ids) . '</td>';
								$total += count($detail->tr_ids);
							}
							echo '<td>' . $total . '</td>';
							echo '</tr>';
							?>
						</table>
					</div>
				</div>
			</div>
			<div class="box box-primary small">
				<div class="box-header with-border"><h3 class="box-title"><span class="glyphicon glyphicon-stats"></span> Withdrawals</h3>
					<div class="box-tools pull-right">
						<button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
						<button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
					</div>
				</div>
				<div class="box-body">
					<div class="table-responsive">
						<div id="withdrawals_in_current_submission_year" style="height: 250px;"></div>
						<p class="small text-info"> A learner withdrawn back to within 42 days/6 weeks (qualifying period) of their start date receives no ESFA funding and is excluded from the calculation of achievement rates.</p>
					</div>
				</div>
			</div>
			<div class="box box-primary small">
				<div class="box-header with-border"><h3 class="box-title"><span class="glyphicon glyphicon-stats"></span> Completions Due</h3>
					<div class="box-tools pull-right">
						<button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
						<button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
					</div>
				</div>
				<div class="box-body">
					<div class="table-responsive">
						<div id="completions_due_by_expected_month" style="height: 350px;"></div>
					</div>
				</div>
			</div>
		</div>
		<div class="col-sm-6">
			<div class="box box-primary small">
				<div class="box-header with-border"><h3 class="box-title"><span class="glyphicon glyphicon-stats"></span> On-Programme Learners</h3>
					<div class="box-tools pull-right">
						<button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
						<button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
					</div>
				</div>
				<div class="box-body">
					<div class="table-responsive">
						<table class="table table-bordered">
							<?php
							echo '<tr>';
							echo '<th style="width: 50%;">On-programme learners</th>';
							echo '<td class="text-blue text-bold" style="cursor: pointer;" onclick="showDetail(\'' . implode(',', $on_programme_stats['on_programme']) . '\');">' . count($on_programme_stats['on_programme']) . '</td>';
							echo '</tr>';
							echo '<tr>';
							echo '<th>Of which: Overstayers</th>';
							echo '<td class="text-blue text-bold" style="cursor: pointer;" onclick="showDetail(\'' . implode(',', $on_programme_stats['overstayer']) . '\');">' . count($on_programme_stats['overstayer']) . '</td>';
							echo '</tr>';
							echo '<tr>';
							echo count($on_programme_stats['on_programme']) > 0 ?
								'<th>% of overstayers</th><td>' . round((count($on_programme_stats['overstayer']) / count($on_programme_stats['on_programme']))*100, 2) . '%</td>' :
								'<th>% of overstayers</th><td>' . round((count($on_programme_stats['overstayer']) / 1)*100, 2) . '%</td>';
							echo '</tr>';
							?>
						</table>
						<p><br></p>
						<div id="on_programme_by_duration_left_graph" style="height: 250px;"></div>
						<p><br></p>
						<div id="overstayers_by_expected_month" style="height: 350px;"></div>
					</div>
				</div>
			</div>

		</div>

	</div>

</section>

	<form method="get" action="<?php echo $_SERVER['PHP_SELF'] ?>" id="frmFilters" name="frmFilters">
		<input type="hidden" name="_action" value="view_home_page_dash_learners" />
		<input type="hidden" name="_reset" value="1" />
		<input type="hidden" name="filter_tr_ids" value="" />
	</form>

</div>

<footer class="main-footer">
	<div class="pull-right hidden-xs">
		Powered by Sunesis &nbsp;|&nbsp;&copy; <?php echo date('Y'); ?> Perspective Ltd
	</div>
	<strong>
		<?php echo date('D, d M Y'); ?>
</footer>

</div>

<script src="/assets/adminlte/plugins/jQuery/jquery-2.2.3.min.js"></script>
<script src="/assets/adminlte/bootstrap/js/bootstrap.min.js"></script>
<script src="/assets/adminlte/dist/js/app.min.js"></script>

<script src="https://code.highcharts.com/highcharts.src.js"></script>
<script src="https://code.highcharts.com/modules/drilldown.js"></script>
<script src="https://code.highcharts.com/modules/exporting.js"></script>
<script src="https://code.highcharts.com/modules/no-data-to-display.js"></script>
<script src="module_charts/assets/jsonfn.js"></script>

<script>

	$(function(){

		var chart = new Highcharts.chart('on_programme_by_duration_left_graph', <?php echo $on_programme_stats['on_programme_by_duration_left_graph']; ?>);
		var chart = new Highcharts.chart('overstayers_by_expected_month', <?php echo $overstayers_by_expected_month; ?>);
		var chart = new Highcharts.chart('withdrawals_in_current_submission_year', <?php echo $withdrawals_in_current_submission_year; ?>);
		var chart = new Highcharts.chart('completions_due_by_expected_month', <?php echo $completions_due_by_expected_month; ?>);

	});

	function showDetail(ids)
	{
		if(ids == '')
			return;

		var frmFilters = document.forms["frmFilters"];
		frmFilters.filter_tr_ids.value = ids;

		frmFilters.submit();
	}
</script>
</body>
</html>
