
<!DOCTYPE html>
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<title>Sunesis | EPA Results Dashboard</title>
	<link rel="stylesheet" href="module_tracking/css/common.css" type="text/css"/>
	<link rel="stylesheet" href="/assets/adminlte/bootstrap/css/bootstrap.min.css">
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.5.0/css/font-awesome.min.css">
	<link rel="stylesheet" href="/assets/adminlte/plugins/jQueryUI/jquery-ui.css">
	<link rel="stylesheet" href="/assets/adminlte/dist/css/AdminLTE.min.css">

	<!--[if lt IE 9]>
	<script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
	<script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
	<![endif]-->

	<style>

		.ui-datepicker .ui-datepicker-title select {
			color: #000;
		}
	</style>

</head>

<body>
<div class="row">
	<div class="col-lg-12">
		<div class="banner">
			<div class="Title" style="margin-left: 6px;">EPA Results Dashboard</div>
			<div class="ButtonBar">
				<span class="btn btn-sm btn-default" onclick="window.location.href='<?php echo $_SESSION['bc']->getPrevious(); ?>';"><i class="fa fa-arrow-circle-o-left"></i> Cancel</span>
			</div>
			<div class="ActionIconBar"></div>
		</div>
	</div>
</div>

<div class="row">
	<div class="col-lg-12">
		<?php $_SESSION['bc']->render($link); ?>
	</div>
</div>
<p></p>

<div class="container-fluid">
	<div class="row">
		<div class="col-sm-12">
			<div class="box box-info" id="divPanels">
				<div class="box-header with-border">
					<h1 class="box-title"> <label class="text-center">EPA Results</label></h1>
					<div class="box-tools pull-right">
						<button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
					</div>
				</div>
				<div class="box-body">
					<form name="frmEPADates" method="get" action="<?php echo $_SERVER['PHP_SELF']; ?>">
						<input type="hidden" name="_action" value="epa_dashboard" />
						<div class="row well well-sm">
							<div class="col-sm-2"><span class="text-bold pull-right">From:</span></div>
							<div class="col-sm-3">
								<?php echo HTML::select('start_month', $months, $start_month, false); ?> &nbsp;
								<?php echo HTML::select('start_year', $years, $start_year, false); ?> &nbsp;
							</div>
							<div class="col-sm-2"><span class="text-bold pull-right">To:</span></div>
							<div class="col-sm-3">
								<?php echo HTML::select('end_month', $months, $end_month, false); ?> &nbsp;
								<?php echo HTML::select('end_year', $years, $end_year, false); ?> &nbsp;
							</div>
							<div class="col-sm-2"><button type="submit" class="btn btn-xs btn-info"><i class="fa fa-refresh"></i> Refresh</button></div>
						</div>
					</form>

					<hr>

					<div class="row">
						<div class="col-sm-8">
							<div class="box box-success box-solid">
								<div class="box-header with-border"><h1 class="box-title">MTD Pass Rates </h1> &nbsp; [<?php echo $this->m_name($start_month) . ' ' . $start_year; ?> - <?php echo $this->m_name($end_month) . ' ' . $end_year; ?>]</div>
								<div class="box-body">
									<div class="table-responsive">
										<?php
										echo '<table class="table table-bordered text-center">';
										echo '<thead class="bg-gray"><tr><th>Distinction</th><th>Merit</th><th>Pass</th><th class="text-red">Fail</th><th>Resit Pass</th><th class="text-red">Resit Fail</th><th class="bg-gray">Total</th></tr></thead>';
										echo '<tbody>';
										echo '<tr>';
										foreach(["distinction", "merit", "pass", "fail", "resit_pass", "resit_fail"] AS $_grade)
										{
											echo '<td class="text-blue" style="cursor: pointer;" onclick="showDetail(\''.implode(',', $gradesTotals[$_grade]).'\');">';
											echo count($gradesTotals[$_grade]);
											echo '</td>';
										}
										$_temp = array_merge($gradesTotals['distinction'], $gradesTotals['merit'], $gradesTotals['pass'], $gradesTotals['resit_pass'], $gradesTotals['fail'], $gradesTotals['resit_fail']);
										echo '<td class="text-blue text-bold" style="cursor: pointer;" onclick="showDetail(\'' . implode(',', $_temp) . '\');">' . $grand_total . '</td>';
										echo '<tr>';
										foreach(["distinction", "merit", "pass", "fail", "resit_pass", "resit_fail"] AS $_grade)
										{
											echo '<td class="text-bold">';
											echo $grand_total > 0 ? round((count($gradesTotals[$_grade]) / $grand_total)*100, 2) : 0;
											echo '%</td>';
										}
										echo '<td></td>';
										echo '</tr>';
										echo '</tbody>';
										echo '</table> ';
										?>
									</div>
								</div>
							</div>
						</div>
						<div class="col-sm-4">
							<div class="col-lg-12 col-xs-12">
								<div class="small-box bg-green">
									<div class="inner">
										<p>Pass rates</p>
										<h2>
											<?php
											$v1 = count($gradesTotals['distinction']) + count($gradesTotals['merit']) + count($gradesTotals['pass']) + count($gradesTotals['resit_pass']);
											echo $grand_total > 0 ?
												$v1 . '/' . $grand_total . ' = ' . round( ($v1/$grand_total)*100  ,2) . '%' :
												'0%';
											$__temp = array_merge($gradesTotals['distinction'], $gradesTotals['merit'], $gradesTotals['pass'], $gradesTotals['resit_pass']);
											?>
										</h2>
									</div>
									<div class="icon"><i class="fa fa-graduation-cap"></i></div>
									<a href="#" onclick="showDetail('<?php echo implode(',', $__temp); ?>');" class="small-box-footer">Click to see <i class="fa fa-arrow-circle-right"></i></a>
								</div>
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-sm-12">
							<div class="box  box-success box-solid">
								<div class="box-header with-border"><h1 class="box-title">By Programme</h1> &nbsp; [<?php echo $this->m_name($start_month) . ' ' . $start_year; ?> - <?php echo $this->m_name($end_month) . ' ' . $end_year; ?>]</div>
								<div class="box-body table-responsive">
									<?php
									echo '<table class="table table-bordered text-center" border="1">';
									echo '<thead>';
									echo '<tr>';
									echo '<th></th>';
									foreach($programmes AS $programme)
									{
										echo '<th class="bg-gray">' . $programme->title . '</th>';
									}
									echo '<th class="bg-gray">Total</th>';
									echo '</tr>';
									echo '</thead>';
									echo '<tbody>';
									foreach($grades AS $grade)
									{
										$row_total = [];
										echo '<tr>';
										echo '<th class="bg-gray">' . ucwords(str_replace("_"," ", $grade)) . '</th>';
										foreach($programmes AS $programme)
										{
											echo '<td class="text-blue" style="cursor: pointer;" onclick="showDetail(\'' . implode(',', $programme->$grade) . '\');">' . count($programme->$grade) . '</td>';
											$row_total = array_merge($row_total, $programme->$grade);
										}
										echo '<td class="text-blue text-bold" style="cursor: pointer;" onclick="showDetail(\'' . implode(',', $row_total) . '\');">' . count($row_total) . '</td>';
										echo '</tr>';
									}
									echo '<tr><th class="bg-gray">Total</th>';
									foreach($programmes AS $programme)
									{
										$col_total = [];
										foreach($grades AS $grade)
										{
											$col_total = array_merge($col_total, $programme->$grade);
										}
										echo '<td class="text-blue text-bold" style="cursor: pointer;" onclick="showDetail(\'' . implode(',', $col_total) . '\');">' . count($col_total) . '</td>';
									}
									echo '<td class="text-blue" style="cursor: pointer;" onclick="showDetail(\'' . implode(',', $_temp) . '\');">' . $grand_total . '</td>';
									echo '</tr>';

									echo '<tr>';
									echo '<th class="bg-gray">FTPR %</th>';
									foreach($programmes AS $programme)
									{
										$v1 = count($programme->distinction) + count($programme->merit) + count($programme->pass);
										$v2 = count($programme->total) - count($programme->resit_pass) - count($programme->resit_fail);
										$v2 = $v2 == 0 ? 1 : $v2;
										echo '<td class="text-bold">' . round(($v1 / $v2) * 100, 2) . '%</td>';
									}
									$v1 = count($gradesTotals['distinction']) + count($gradesTotals['merit']) + count($gradesTotals['pass']);
									$v2 = $grand_total - count($gradesTotals['resit_pass']) - count($gradesTotals['resit_fail']);
									$v2 = $v2 == 0 ? 1 : $v2;
									echo '<td class="text-bold">' . round(($v1 / $v2) * 100, 2) . '%</td>';
									echo '</tr>';

									echo '<tr>';
									echo '<th class="bg-gray">OPR %</th>';
									foreach($programmes AS $programme)
									{
										$v1 = count($programme->distinction) + count($programme->merit) + count($programme->pass) + count($programme->resit_pass);
										$v2 = count($programme->total) - count($programme->resit_pass) - count($programme->resit_fail);
										$v2 = $v2 == 0 ? 1 : $v2;
										echo '<td class="text-bold">' . round(($v1 / $v2) * 100, 2) . '%</td>';
									}
									$v1 = count($gradesTotals['distinction']) + count($gradesTotals['merit']) + count($gradesTotals['pass']) + count($gradesTotals['resit_pass']);
									$v2 = $grand_total - count($gradesTotals['resit_pass']) - count($gradesTotals['resit_fail']);
									$v2 = $v2 == 0 ? 1 : $v2;
									echo '<td class="text-bold">' . round(($v1 / $v2) * 100, 2) . '%</td>';
									echo '</tr>';

									echo '<tr>';
									echo '<th class="bg-gray">TPR %</th>';
									foreach($programmes AS $programme)
									{
										$v1 = count($programme->distinction) + count($programme->merit) + count($programme->pass) + count($programme->resit_pass);
										$v2 = count($programme->total);
										echo '<td class="text-bold">' . round(($v1 / $v2) * 100, 2) . '%</td>';
									}
									$v1 = count($gradesTotals['distinction']) + count($gradesTotals['merit']) + count($gradesTotals['pass']) + count($gradesTotals['resit_pass']);
									$v2 = $grand_total > 0 ? $grand_total : 1;
									echo '<td class="text-bold">' . round(($v1 / $v2) * 100, 2) . '%</td>';
									echo '</tr>';

									echo '</tbody>';
									echo '</table>';

									?>
								</div>
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-sm-12">
							<div class="box  box-success box-solid">
								<div class="box-header with-border"><h1 class="box-title">By Supervisor</h1> &nbsp; [<?php echo $this->m_name($start_month) . ' ' . $start_year; ?> - <?php echo $this->m_name($end_month) . ' ' . $end_year; ?>]</div>
								<div class="box-body table-responsive">
									<?php
									echo '<table class="table table-bordered text-center" border="1">';
									echo '<thead>';
									echo '<tr>';
									echo '<th></th>';
									foreach($supervisors AS $supervisor)
									{
										echo '<th class="bg-gray">' . $supervisor->title . '</th>';
									}
									echo '<th class="bg-gray">Total</th>';
									echo '</tr>';
									echo '</thead>';
									echo '<tbody>';
									foreach($grades AS $grade)
									{
										$row_total = [];
										echo '<tr>';
										echo '<th class="bg-gray">' . ucwords(str_replace("_"," ", $grade)) . '</th>';
										foreach($supervisors AS $supervisor)
										{
											echo '<td class="text-blue" style="cursor: pointer;" onclick="showDetail(\'' . implode(',', $supervisor->$grade) . '\');">' . count($supervisor->$grade) . '</td>';
											$row_total = array_merge($row_total, $supervisor->$grade);
										}
										echo '<td class="text-blue text-bold" style="cursor: pointer;" onclick="showDetail(\'' . implode(',', $row_total) . '\');">' . count($row_total) . '</td>';
										echo '</tr>';
									}
									echo '<tr><th class="bg-gray">Total</th>';
									foreach($supervisors AS $supervisor)
									{
										$col_total = [];
										foreach($grades AS $grade)
										{
											$col_total = array_merge($col_total, $supervisor->$grade);
										}
										echo '<td class="text-blue text-bold" style="cursor: pointer;" onclick="showDetail(\'' . implode(',', $col_total) . '\');">' . count($col_total) . '</td>';
									}
									echo '<td class="text-blue text-bold" style="cursor: pointer;" onclick="showDetail(\'' . implode(',', $_temp) . '\');">' . $grand_total_p . '</td>';
									echo '</tr>';

									echo '<tr>';
									echo '<th class="bg-gray">FTPR %</th>';
									foreach($supervisors AS $supervisor)
									{
										$v1 = count($supervisor->distinction) + count($supervisor->merit) + count($supervisor->pass);
										$v2 = count($supervisor->total) - count($supervisor->resit_pass) - count($supervisor->resit_fail);
										$v2 = $v2 == 0 ? 1 : $v2;
										echo '<td class="text-bold">' . round(($v1 / $v2) * 100, 2) . '%</td>';
									}
									$v1 = count($gradesTotals_p['distinction']) + count($gradesTotals_p['merit']) + count($gradesTotals_p['pass']);
									$v2 = $grand_total_p - count($gradesTotals_p['resit_pass']) - count($gradesTotals_p['resit_fail']);
									$v2 = $v2 == 0 ? 1 : $v2;
									echo '<td class="text-bold">' . round(($v1 / $v2) * 100, 2) . '%</td>';
									echo '</tr>';

									echo '<tr>';
									echo '<th class="bg-gray">OPR %</th>';
									foreach($supervisors AS $supervisor)
									{
										$v1 = count($supervisor->distinction) + count($supervisor->merit) + count($supervisor->pass) + count($supervisor->resit_pass);
										$v2 = count($supervisor->total) - count($supervisor->resit_pass) - count($supervisor->resit_fail);
										$v2 = $v2 == 0 ? 1 : $v2;
										echo '<td class="text-bold">' . round(($v1 / $v2) * 100, 2) . '%</td>';
									}
									$v1 = count($gradesTotals_p['distinction']) + count($gradesTotals_p['merit']) + count($gradesTotals_p['pass']) + count($gradesTotals_p['resit_pass']);
									$v2 = $grand_total_p - count($gradesTotals_p['resit_pass']) - count($gradesTotals_p['resit_fail']);
									$v2 = $v2 == 0 ? 1 : $v2;
									echo '<td class="text-bold">' . round(($v1 / $v2) * 100, 2) . '%</td>';
									echo '</tr>';

									echo '<tr>';
									echo '<th class="bg-gray">TPR %</th>';
									foreach($supervisors AS $supervisor)
									{
										$v1 = count($supervisor->distinction) + count($supervisor->merit) + count($supervisor->pass) + count($supervisor->resit_pass);
										$v2 = count($supervisor->total);
										echo '<td class="text-bold">' . round(($v1 / $v2) * 100, 2) . '%</td>';
									}
									$v1 = count($gradesTotals_p['distinction']) + count($gradesTotals_p['merit']) + count($gradesTotals_p['pass']) + count($gradesTotals_p['resit_pass']);
									$v2 = $grand_total_p > 0 ? $grand_total_p : 1;
									echo '<td class="text-bold">' . round(($v1 / $v2) * 100, 2) . '%</td>';
									echo '</tr>';

									echo '</tbody>';
									echo '</table>';



									?>
								</div>
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-sm-12">
							<div class="box  box-success box-solid">
								<div class="box-header with-border"><h1 class="box-title">By Assessor</h1> &nbsp; [<?php echo $this->m_name($start_month) . ' ' . $start_year; ?> - <?php echo $this->m_name($end_month) . ' ' . $end_year; ?>]</div>
								<div class="box-body table-responsive">
									<?php
									echo '<table class="table table-bordered text-center" border="1">';
									echo '<thead>';
									echo '<tr>';
									echo '<th></th>';
									foreach($assessors AS $assessor)
									{
										echo '<th class="bg-gray">' . $assessor->title . '</th>';
									}
									echo '<th class="bg-gray">Total</th>';
									echo '</tr>';
									echo '</thead>';
									echo '<tbody>';
									foreach($grades AS $grade)
									{
										$row_total = [];
										echo '<tr>';
										echo '<th class="bg-gray">' . ucwords(str_replace("_"," ", $grade)) . '</th>';
										foreach($assessors AS $assessor)
										{
											echo '<td class="text-blue" style="cursor: pointer;" onclick="showDetail(\'' . implode(',', $assessor->$grade) . '\');">' . count($assessor->$grade) . '</td>';
											$row_total = array_merge($row_total, $assessor->$grade);
										}
										echo '<td class="text-blue text-bold" style="cursor: pointer;" onclick="showDetail(\'' . implode(',', $row_total) . '\');">' . count($row_total) . '</td>';
										echo '</tr>';
									}
									echo '<tr><th class="bg-gray">Total</th>';
									foreach($assessors AS $assessor)
									{
										$col_total = [];
										foreach($grades AS $grade)
										{
											$col_total = array_merge($col_total, $assessor->$grade);
										}
										echo '<td class="text-blue text-bold" style="cursor: pointer;" onclick="showDetail(\'' . implode(',', $col_total) . '\');">' . count($col_total) . '</td>';
									}
									echo '<td class="text-blue text-bold" style="cursor: pointer;" onclick="showDetail(\'' . implode(',', $_temp) . '\');">' . $grand_total_a . '</td>';
									echo '</tr>';

									echo '<tr>';
									echo '<th class="bg-gray">FTPR %</th>';
									foreach($assessors AS $assessor)
									{
										$v1 = count($assessor->distinction) + count($assessor->merit) + count($assessor->pass);
										$v2 = count($assessor->total) - count($assessor->resit_pass) - count($assessor->resit_fail);
										$v2 = $v2 == 0 ? 1 : $v2;
										echo '<td class="text-bold">' . round(($v1 / $v2) * 100, 2) . '%</td>';
									}
									$v1 = count($gradesTotals_a['distinction']) + count($gradesTotals_a['merit']) + count($gradesTotals_a['pass']);
									$v2 = $grand_total_a - count($gradesTotals_a['resit_pass']) - count($gradesTotals_a['resit_fail']);
									$v2 = $v2 == 0 ? 1 : $v2;
									echo '<td class="text-bold">' . round(($v1 / $v2) * 100, 2) . '%</td>';
									echo '</tr>';

									echo '<tr>';
									echo '<th class="bg-gray">OPR %</th>';
									foreach($assessors AS $assessor)
									{
										$v1 = count($assessor->distinction) + count($assessor->merit) + count($assessor->pass) + count($assessor->resit_pass);
										$v2 = count($assessor->total) - count($assessor->resit_pass) - count($assessor->resit_fail);
										$v2 = $v2 == 0 ? 1 : $v2;
										echo '<td class="text-bold">' . round(($v1 / $v2) * 100, 2) . '%</td>';
									}
									$v1 = count($gradesTotals_a['distinction']) + count($gradesTotals_a['merit']) + count($gradesTotals_a['pass']) + count($gradesTotals_a['resit_pass']);
									$v2 = $grand_total_a - count($gradesTotals_a['resit_pass']) - count($gradesTotals_a['resit_fail']);
									$v2 = $v2 == 0 ? 1 : $v2;
									echo '<td class="text-bold">' . round(($v1 / $v2) * 100, 2) . '%</td>';
									echo '</tr>';

									echo '<tr>';
									echo '<th class="bg-gray">TPR %</th>';
									foreach($assessors AS $assessor)
									{
										$v1 = count($assessor->distinction) + count($assessor->merit) + count($assessor->pass) + count($assessor->resit_pass);
										$v2 = count($assessor->total);
										echo '<td class="text-bold">' . round(($v1 / $v2) * 100, 2) . '%</td>';
									}
									$v1 = count($gradesTotals_a['distinction']) + count($gradesTotals_a['merit']) + count($gradesTotals_a['pass']) + count($gradesTotals_a['resit_pass']);
									$v2 = $grand_total_a > 0 ? $grand_total_a : 1;
									echo '<td class="text-bold">' . round(($v1 / $v2) * 100, 2) . '%</td>';
									echo '</tr>';

									echo '</tbody>';
									echo '</table>';



									?>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>

<form method="get" action="<?php echo $_SERVER['PHP_SELF'] ?>" id="frmFilters" name="frmFilters">
	<input type="hidden" name="_action" value="view_epa_dash_learners" />
	<input type="hidden" name="_reset" value="1" />
	<input type="hidden" name="filter_ids" value="" />
</form>

<form name="frmDashPDF" action="do.php?_action=operations_dashboard" method="post">
	<input type="hidden" name="subaction" value="generate_dash_pdf" />
	<input type="hidden" name="html" value="" />
</form>

<script src="/assets/adminlte/plugins/jQuery/jquery-2.2.3.min.js"></script>
<script src="/assets/adminlte/plugins/jQueryUI/jquery-ui.min.js"></script>
<script src="/assets/adminlte/bootstrap/js/bootstrap.min.js"></script>
<script src="/assets/adminlte/dist/js/app.min.js"></script>
<script src="/common.js" type="text/javascript"></script>

<script language="JavaScript">

	function showDetail(ids)
	{
		var frmFilters = document.forms["frmFilters"];
		frmFilters.filter_ids.value = ids;

		frmFilters.submit();
	}

	$(function(){


	});
</script>

</body>
</html>
<?php
$programmes = null;
$grand_total = null;
$gradesTotals = null;
$assessors = null;
$grand_total_a = null;
$gradesTotals_a = null;
$supervisors = null;
$grand_total_p = null;
$gradesTotals_p = null;

?>