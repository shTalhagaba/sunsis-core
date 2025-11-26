<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<title>e-Recruitment | Homepage</title>
	<meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
	<link rel="stylesheet" href="/assets/adminlte/bootstrap/css/bootstrap.min.css">
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.5.0/css/font-awesome.min.css">
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/ionicons/2.0.1/css/ionicons.min.css">
	<link rel="stylesheet" href="/assets/adminlte/plugins/jvectormap/jquery-jvectormap-1.2.2.css">
	<link rel="stylesheet" href="/assets/adminlte/dist/css/AdminLTE.min.css?n=<?php echo time(); ?>">
	<link rel="stylesheet" href="/assets/adminlte/dist/css/skins/_all-skins.min.css">
	<link href="/assets/adminlte/plugins/datatables/jquery.dataTables.css" rel="stylesheet">
	<link href="/assets/adminlte/plugins/chosen/bootstrap-chosen.css" rel="stylesheet">

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
			<!--<h1><span class="fa fa-dashboard"></span> e-Recruitment Dashboard<span class="pull-right"><img class="img-rounded" src="images/logos/SUNlogo.jpg" height="35px;"/></span></h1>-->
		</section>

		<section class="content">

			<div class="row">
				<div class="col-lg-12">
					<div class="box box-solid box-info">
						<div class="box-header with-border"><h3 class="box-title">Applications</h3>
							<div class="box-tools pull-right">
								<button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
								<button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
							</div>
						</div>
						<div class="box-body">
							<div class="row">
								<div class="col-lg-3 col-xs-3">
									<div class="small-box bg-pink">
										<div class="inner">
											<h3><?php echo DAO::getSingleValue($link, "SELECT COUNT(*) FROM candidate_applications WHERE current_status = '1'");?></h3>
											<p>Screened</p>
										</div>
										<div class="icon"><i class="fa fa-files-o"></i></div>
										<a href="#" class="small-box-footer"></a>
									</div>
								</div>

								<div class="col-lg-3 col-xs-3">
									<div class="small-box bg-lightpink">
										<div class="inner">
											<h3><?php echo DAO::getSingleValue($link, "SELECT COUNT(*) FROM candidate_applications WHERE current_status = '2'");?></h3>
											<p>Telephone Interviewed</p>
										</div>
										<div class="icon"><i class="fa fa-files-o"></i></div>
										<a href="#" class="small-box-footer"></a>
									</div>
								</div>
								<div class="col-lg-3 col-xs-3">
									<div class="small-box bg-hotpink">
										<div class="inner">
											<h3><?php echo DAO::getSingleValue($link, "SELECT COUNT(*) FROM candidate_applications WHERE current_status = '3'");?></h3>
											<p>CV Sent</p>
										</div>
										<div class="icon"><i class="fa fa-files-o"></i></div>
										<a href="#" class="small-box-footer"></a>
									</div>
								</div>

								<div class="col-lg-3 col-xs-3">
									<div class="small-box bg-deeppink">
										<div class="inner">
											<h3><?php echo DAO::getSingleValue($link, "SELECT COUNT(*) FROM candidate_applications WHERE current_status = '4'");?></h3>
											<p>Interview Successful</p>
										</div>
										<div class="icon"><i class="fa fa-files-o"></i></div>
										<a href="#" class="small-box-footer"></a>
									</div>
								</div>

							</div>
						</div>
					</div>
				</div>
			</div>

			<div class="row">
				<div class="col-lg-6">
					<div class="box box-solid box-info">
						<div class="box-header with-border"><h3 class="box-title">Vacancies with not screened applications</h3>
							<div class="box-tools pull-right">
								<button type="button" class="btn btn-box-tool"><i class="fa fa-file-excel-o" id="export-tblNotScreenedApplications"></i></button>
								<button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
								<button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
							</div>
						</div>
						<div class="box-body">
							<div class="table-responsive">
								<?php $this->renderDashboardTableForVacanciesWithNotScreenedApplications($link); ?>
							</div>
						</div>
					</div>


				</div>

				<div class="col-lg-6">
					<div class="box box-solid box-info">
						<div class="box-header with-border"><h3 class="box-title">Filled vacancies</h3>
							<div class="box-tools pull-right">
								<button type="button" class="btn btn-box-tool"><i class="fa fa-file-excel-o" id="export-tblFilledApplications"></i></button>
								<button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
								<button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
							</div>
						</div>
						<div class="box-body">
							<div class="table-responsive">
								<?php $this->renderDashboardTableForVacanciesWithFilledApplications($link); ?>
							</div>
						</div>
					</div>
				</div>

			</div>

			<div class="row">
				<div class="col-lg-4">
					<form action="do.php" name="find_candidate">
						<input type="hidden" name="_action" value="rec_view_candidates" />
						<input type="hidden" name="_reset" value="1" />
						<input id="RecViewCandidates_filter_distance" type="hidden" value="5" name="RecViewCandidates_filter_distance">
						<div class="box box-solid box-info">
							<div class="box-header with-border"><h3 class="box-title">Search Candidates</h3>
								<div class="box-tools pull-right">
									<button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
									<button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
								</div>
							</div>
							<div class="box-body">
								<!--<p>
										<div class="info-box">
											<span class="info-box-icon bg-yellow"><i class="fa fa-users"></i></span>
											<div class="info-box-content">
												<span class="info-box-text">Total Candidates</span>
												<span class="info-box-number"><?php /*echo (int)DAO::getSingleValue($link, "SELECT COUNT(*) FROM candidate"); */?></span>
											</div>
										</div>
										</p>-->
								<p>
								<div class="input-group">
									<span class="input-group-addon"><i class="glyphicon glyphicon-text-color"></i></span>
									<input type="text" class="form-control" id="RecViewCandidates_filter_firstnames" name="RecViewCandidates_filter_firstnames" placeholder="Candidate First Name" />
								</div>
								</p>
								<p>
								<div class="input-group">
									<span class="input-group-addon"><i class="glyphicon glyphicon-text-color"></i></span>
									<input type="text" class="form-control" id="RecViewCandidates_filter_surname" name="RecViewCandidates_filter_surname" placeholder="Candidate Surname" />
								</div>
								</p>
								<p>
								<div class="input-group">
									<span class="input-group-addon"><i class="glyphicon glyphicon-map-marker"></i></span>
									<input type="text" class="form-control" id="RecViewCandidates_filter_postcodes" name="RecViewCandidates_filter_postcodes" placeholder="Postcode to search in 5 miles radius" />
								</div>
								</p>
							</div>
							<div class="box-footer">
								<button type="submit" name="search_candidates" class="btn btn-primary pull-right"><i class="fa fa-search"></i> Search</button>
							</div>
						</div>
					</form>
				</div>
				<div class="col-lg-4">
					<form action="do.php" name="find_employer">
						<input type="hidden" name="_action" value="view_employers" />
						<input type="hidden" name="_reset" value="1" />
						<div class="box box-solid box-info">
							<div class="box-header with-border"><h3 class="box-title">Search Employers</h3>
								<div class="box-tools pull-right">
									<button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
									<button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
								</div>
							</div>
							<div class="box-body">
								<p>
								<div class="input-group">
									<span class="input-group-addon"><i class="glyphicon glyphicon-text-color"></i></span>
									<input type="text" class="form-control" id="ViewGroupEmployers_filter_name" name="ViewGroupEmployers_filter_name" placeholder="Employer Legal Name" />
								</div>
								</p>
								<p>
								<div class="input-group">
									<span class="input-group-addon"><i class="fa fa-tags"></i></span>
									<select id="ViewGroupEmployers_by_vacancies" name="ViewGroupEmployers_by_vacancies">
										<option value="1" selected="selected">With or Without Vacancies</option>
										<option value="2">With Vacancies</option>
										<option value="3">Without Vacancies</option>
									</select>
								</div>
								</p>
								<p>
								<div class="input-group">
									<span class="input-group-addon"><i class="fa fa-list"></i></span>
									<?php echo HTML::select('ViewGroupEmployers_filter_county', DAO::getResultset($link, 'SELECT DISTINCT address_line_4, address_line_4, null, CONCAT("having address_line_4=",CHAR(39),address_line_4,CHAR(39)) FROM locations INNER JOIN organisations ON organisations.id = locations.organisations_id WHERE organisations.organisation_type = 2 AND locations.is_legal_address = 1 ORDER BY locations.address_line_4'), '', true); ?>
								</div>
								</p>
							</div>
							<div class="box-footer">
								<button type="submit" name="view_employers" class="btn btn-primary pull-right"><i class="fa fa-search"></i> Search</button>
							</div>
						</div>
					</form>
				</div>
				<div class="col-lg-4">
					<form action="do.php" name="find_vacancy">
						<input type="hidden" name="_action" value="rec_view_vacancies" />
						<input type="hidden" name="_reset" value="1" />
						<div class="box box-solid box-info">
							<div class="box-header with-border"><h3 class="box-title">Search Vacancies</h3>
								<div class="box-tools pull-right">
									<button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
									<button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
								</div>
							</div>
							<div class="box-body">
								<p>
								<div class="input-group">
									<span class="input-group-addon"><i class="glyphicon glyphicon-text-color"></i></span>
									<input type="text" class="form-control" id="RecViewVacancies_filter_vacancy_reference" name="RecViewVacancies_filter_vacancy_reference" placeholder="Vacancy Reference" />
								</div>
								</p>
								<p>
								<div class="input-group">
									<span class="input-group-addon"><i class="glyphicon glyphicon-text-color"></i></span>
									<input type="text" class="form-control" id="RecViewVacancies_filter_vacancy_title" name="RecViewVacancies_filter_vacancy_title" placeholder="Vacancy Title" />
								</div>
								</p>
								<p>
								<div class="input-group">
									<span class="input-group-addon"><i class="glyphicon glyphicon-text-color"></i></span>
									<input type="text" class="form-control" id="RecViewVacancies_filter_employername" name="RecViewVacancies_filter_employername" placeholder="Employer Name" />
								</div>
								</p>
							</div>
							<div class="box-footer">
								<button type="submit" name="view_vacancies" class="btn btn-primary pull-right"><i class="fa fa-search"></i> Search</button>
							</div>
						</div>
					</form>
				</div>
			</div>

			<div class="row">
				<div class="col-lg-4">
					<div class="box box-solid box-info" id="divPieChart">
						<div class="box-header with-border">
							<h3 class="box-title"><span class="fa fa-pie-chart"></span> Candidates by Gender (<?php echo date('Y')-1 . ' - ' . date('Y'); ?>)</h3>
							<div class="box-tools pull-right">
								<button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
								<button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
							</div>
						</div>
						<div class="box-body">
							<div class="row">
								<div class="col-md-12">
									<div class="chart-responsive">
										<canvas style="display: block;" id="pieChartLearnerProgress" height="150"></canvas>
									</div>
								</div>
							</div>
						</div>
						<div class="box-footer no-padding">
							<ul class="nav nav-pills nav-stacked">
								<li><a href="#">Male<span class="pull-right text-red"><?php echo round(($gender_stats['male_applicants']/$gender_stats_total)*100);?>%</span></a></li>
								<li><a href="#">Female<span class="pull-right text-red"><?php echo round(($gender_stats['female_applicants']/$gender_stats_total)*100);?>%</span></a></li>
								<li><a href="#">Unknown<span class="pull-right text-red"><?php echo round(($gender_stats['unknown_applicants']/$gender_stats_total)*100);?>%</span></a></li>
								<li><a href="#">Witheld<span class="pull-right text-red"><?php echo round(($gender_stats['witheld_applicants']/$gender_stats_total)*100);?>%</span></a></li>
							</ul>
						</div>
					</div>

					<div class="box box-solid box-info">
						<div class="box-header with-border">
							<h3 class="box-title"><span class="fa fa-dashboard"></span> Successful Candidates by Age Group (<?php echo date('Y')-1 . ' - ' . date('Y'); ?>)</h3>
							<div class="box-tools pull-right">
								<button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
								<button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
							</div>
						</div>
						<div class="box-body">
							<div class="row"><?php echo $this->renderAgeGroupDashboard($link); ?></div>
						</div>
					</div>

				</div>

				<div class="col-md-8">
					<div class="row">

						<div class="col-sm-6">
							<div class="box box-solid box-info">
								<div class="box-header with-border">
									<h3 class="box-title"><span class="fa fa-table"></span> Candidates by Ethnicity (<?php echo date('Y')-1 . ' - ' . date('Y'); ?>)</h3>
									<div class="box-tools pull-right">
										<button type="button" class="btn btn-box-tool"><i class="fa fa-file-excel-o" id="export-tblEthnicityStats"></i></button>
										<button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
										<button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
									</div>
								</div>
								<div class="box-body" style="max-height: 350px; overflow-y: scroll;">
									<?php echo $ethnicityTable; ?>
								</div>
							</div>
						</div>

						<div class="col-sm-6">
							<div class="box box-solid box-info">
								<div class="box-header with-border">
									<h3 class="box-title"><span class="fa fa-table"></span> Applications by Region (<?php echo date('Y')-1 . ' - ' . date('Y'); ?>)</h3>
									<div class="box-tools pull-right">
										<button type="button" class="btn btn-box-tool"><i class="fa fa-file-excel-o" id="export-tblFilledVacanciesByRegion"></i></button>
										<button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
										<button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
									</div>
								</div>
								<div class="box-body" style="max-height: 350px; overflow-y: scroll;">
									<?php echo $this->renderFilledVacanciesByRegionTable($link); ?>
								</div>
							</div>
						</div>

					</div>

				</div>

			</div>

		</section>

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
<script src="/assets/adminlte/plugins/chartjs/Chart.min.js"></script>
<script src="/assets/adminlte/plugins/datatables/jquery.dataTables.min.js"></script>
<script src="/assets/adminlte/plugins/chosen/chosen.jquery.js"></script>
<script src="/js/table-export.js" type="text/javascript"></script>
<script>

	$(function(){
		$('#ViewGroupEmployers_by_vacancies').chosen({width: "100%"});
		$('#ViewGroupEmployers_filter_county').chosen({width: "100%"});

		$('#tblNotScreenedApplications').DataTable({
			"paging": true,
			"lengthChange": true,
			"searching": true,
			"ordering": true,
			"info": true,
			"autoWidth": false,
			"pageLength": 5
		});
		$('#tblFilledApplications').DataTable({
			"paging": true,
			"lengthChange": true,
			"searching": true,
			"ordering": true,
			"info": true,
			"autoWidth": false,
			"pageLength": 5
		});
		$('#tblEthnicityStats').DataTable({
			"paging": false,
			"lengthChange": false,
			"searching": false,
			"ordering": false,
			"info": true,
			"autoWidth": false
		});
		$('#tblFilledVacanciesByRegion').DataTable({
			"paging": false,
			"lengthChange": false,
			"searching": false,
			"ordering": false,
			"info": true,
			"autoWidth": false
		});

		var pieChartCanvas = $("#pieChartLearnerProgress").get(0).getContext("2d");
		var pieChartLearnerProgress = new Chart(pieChartCanvas);
		var PieData = [
			{value: <?php echo $gender_stats['male_applicants']; ?>,color: "lightgreen",label: "Male"},
			{value: <?php echo $gender_stats['female_applicants']; ?>,color: "pink",label: "Female"},
			{value: <?php echo $gender_stats['unknown_applicants']; ?>,color: "lightblue",label: "Unknown"},
			{value: <?php echo $gender_stats['witheld_applicants']; ?>,color: "red",label: "Witheld"}
		];
		var pieOptions = {
			percentageInnerCutout: 0, // This is 0 for Pie charts
			animationSteps: 100,
			animationEasing: "easeOutBounce",
			animateRotate: true,
			animateScale: false,
			responsive: true,
			maintainAspectRatio: false,
			bezierCurve : false,
			onAnimationComplete: function(){
				//console.log(this.toBase64Image());
			}
		};
		pieChartLearnerProgress.Doughnut(PieData, pieOptions);

		$('i[id^=export-]').click(function () {
			var table_name = $(this).attr('id').replace('export-', '');
			var data = $('#'+table_name).table2CSV();
			data = data.replace(/\&nbsp;/g, '');
			window.location.href = 'do.php?_action=downloader_table&csv_name='+table_name+'&csv_text=' + data;
		});

	});

</script>
</body>
</html>
