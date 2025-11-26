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
	<link rel="stylesheet" href="/assets/adminlte/plugins/iCheck/all.css">
	<link href="/assets/adminlte/plugins/daterangepicker/daterangepicker.css" rel="stylesheet">
	<link href="/assets/adminlte/plugins/toastr/toastr.min.css" rel="stylesheet">

	<!--[if lt IE 9]>
	<script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
	<script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
	<![endif]-->

	<style>
		.graph_container{
			width:auto;
			min-height:300px;
			height:auto;
		}
	</style>

</head>

<body>

<div class="wrapper">
<header class="main-header"><img class="img-rounded" src="images/logos/SUNlogo.jpg" height="35px;"/></span><span class="btn btn-xs btn-default pull-right" onclick="localStorage.clear(); window.location.reload();"><i class="fa fa-refresh"></i> </span> </header>

<div class="content-wrapper">
<!--<section class="content-header">
		  <h1>Home</h1>
	  </section>-->

<section class="content">
<div class="row">
	<div class="col-lg-6">
		<div class="box box-primary boxStatsLearnersByStatus">
			<div class="box-header with-border"><h3 class="box-title">Learners (<?php echo $current_contract_year . ' - '; echo  (int)$current_contract_year + 1; ?>)</h3>
				<div class="box-tools pull-right">
					<button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
					<button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
				</div>
			</div>
			<div class="box-body">
				<h3 class='text-center loading' style="display: none;"><i class="fa fa-refresh fa-spin"></i> loading statistics ...</h3>
				<div class="row">
					<div class="col-lg-6 col-xs-6">
						<div class="small-box bg-aqua continuing">
							<div class="inner">
								<h3 class="number">0</h3>
								<p>Learners in training</p>
							</div>
							<div class="icon"><i class="fa fa-hourglass-half"></i></div>
							<a class="small-box-footer link" href="">Click to see <i class="fa fa-arrow-circle-right"></i></a>
						</div>
					</div>
					<div class="col-lg-6 col-xs-6">
						<div class="small-box bg-red past_planned_end_date">
							<div class="inner">
								<h3 class="number">0</h3>
								<p>Learners past planned end date</p>
							</div>
							<div class="icon"><i class="fa fa-calendar-plus-o"></i></div>
							<a class="small-box-footer link" href="">Click to see <i class="fa fa-arrow-circle-right"></i></a>
						</div>
					</div>
				</div>
				<div class="row">
					<div class="col-lg-6 col-xs-6">
						<div class="small-box bg-yellow temp_withdrawn">
							<div class="inner">
								<h3 class="number">0</h3>
								<p>Learners temporarily withdrawn</p>
							</div>
							<div class="icon"><i class="fa fa-pause"></i></div>
							<a class="small-box-footer link" href="">Click to see <i class="fa fa-arrow-circle-right"></i></a>
						</div>
					</div>
					<div class="col-lg-6 col-xs-6">
						<div class="small-box bg-red withdrawn">
							<div class="inner">
								<h3 class="number">0</h3>
								<p>Learners withdrawn</p>
							</div>
							<div class="icon"><i class="fa fa-chain-broken"></i></div>
							<a class="small-box-footer link" href="">Click to see <i class="fa fa-arrow-circle-right"></i></a>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<div class="col-lg-6">
		<div class="box box-primary boxStatsLearnersByProgression">
			<div class="box-header with-border"><h3 class="box-title">Completion/Progression (<?php echo $current_contract_year . ' - '; echo  (int)$current_contract_year + 1; ?>)</h3>
				<div class="box-tools pull-right">
					<button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
					<button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
				</div>
			</div>
			<div class="box-body">
				<h3 class='text-center loading' style="display: none;"><i class="fa fa-refresh fa-spin"></i> loading statistics ...</h3>
				<div class="row">
					<div class="col-lg-6 col-xs-6">
						<div class="small-box bg-green completed">
							<div class="inner">
								<h3 class="number">0</h3>
								<p>Learners completed</p>
							</div>
							<div class="icon"><i class="fa fa-graduation-cap"></i></div>
							<a class="small-box-footer link" href="">Click to see <i class="fa fa-arrow-circle-right"></i></a>
						</div>
					</div>
					<div class="col-lg-6 col-xs-6">
						<div class="small-box bg-aqua progl2l3">
							<div class="inner">
								<h3 class="number">0</h3>
								<p>Progressions Level 2 to Level 3</p>
							</div>
							<div class="icon"><i class="fa fa-graduation-cap"></i></div>
							<a class="small-box-footer link" href="">Click to see <i class="fa fa-arrow-circle-right"></i></a>
						</div>
					</div>
					<?php if(DB_NAME == 'am_crackerjack') { ?>
					<div class="col-lg-6 col-xs-6">
						<div class="small-box bg-aqua progsp">
							<div class="inner">
								<h3 class="number">0</h3>
								<p>Progressions Study Program to Traineeship</p>
							</div>
							<div class="icon"><i class="fa fa-graduation-cap"></i></div>
							<a class="small-box-footer link" href="">Click to see <i class="fa fa-arrow-circle-right"></i></a>
						</div>
					</div>
					<?php } else {?>
					<div class="col-lg-6 col-xs-6">
						<div class="small-box bg-aqua progl3l4">
							<div class="inner">
								<h3 class="number">0</h3>
								<p>Progressions Level 3 to Level 4</p>
							</div>
							<div class="icon"><i class="fa fa-graduation-cap"></i></div>
							<a class="small-box-footer link" href="">Click to see <i class="fa fa-arrow-circle-right"></i></a>
						</div>
					</div>
					<?php } ?>
					<div class="col-lg-6 col-xs-6">
						<div class="small-box bg-aqua progta">
							<div class="inner">
								<h3 class="number">0</h3>
								<p>Progressions Traineeship to Apprenticeship</p>
							</div>
							<div class="icon"><i class="fa fa-graduation-cap"></i></div>
							<a class="small-box-footer link" href="">Click to see <i class="fa fa-arrow-circle-right"></i></a>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<div class="row">
	<div class="col-sm-4">
		<div class="row">
			<div class="col-sm-12">
				<div class="box">
					<div class="box-body"><div id="panelLearnersByProgress"></div></div>
				</div>
			</div>
			<div class="col-sm-12">
				<div class="box">
					<div class="box-body">
						<p class="text-info">The Sunesis <a href="do.php?_action=file_repository">File Repository</a> provides a secure conduit for the movement of sensitive data files between users and Perspective.</p>
						<div id="panelFileRepo"></div>
					</div>
				</div>
			</div>
			<div class="col-sm-12">
				<div class="box collapsed-box">
					<div class="box-header with-border">
						<h3 class="box-title"><span class="fa fa-calendar"></span> ILR Submissions (<?php echo $current_contract_year . ' - '; echo  (int)$current_contract_year + 1; ?>)</h3>
						<div class="box-tools pull-right">
							<button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-plus"></i></button>
							<button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
						</div>
					</div>
					<div class="box-body">
						<table class="table table-bordered">
							<tr><th>Period</th><th>Last Submission Date</th><th>Total ILRs</th><th>Valid ILRs</th><th>Invalid ILRs</th></tr>
							<?php
							$submissions_details = DAO::getResultset($link, "SELECT * FROM central.lookup_submission_dates WHERE contract_year IN (2020)", DAO::FETCH_ASSOC);
							$current_submission = DAO::getSingleValue($link, "SELECT right(submission,2) FROM central.`lookup_submission_dates` WHERE contract_year = 2020  and CURDATE() BETWEEN start_submission_date AND last_submission_date;");
							$current_submission = 'W' . $current_submission;
							foreach($submissions_details AS $submission_record)
							{
								if($submission_record['submission'] < $current_submission)
									continue;
								if($submission_record['submission'] == $current_submission)
								{
									$today = new Date(date('Y-m-d'));
									$last_submission_date = new Date($submission_record['last_submission_date']);
									$days_left = Date::dateDiffInfo($today, $last_submission_date);
									echo '<tr bgcolor="orange"><td bgcolor="orange">' . $submission_record['submission'] . '</td><td bgcolor="orange">' . Date::toShort($submission_record['last_submission_date']) . ' (' . $days_left['days'] . ' days left)</td><td>' . $total_ilrs . '</td><td><a href="do.php?_action=view_training_records&_reset=1&ViewTrainingRecords_filter_funding=1">' . $valid_ilrs . '</a></td><td><a href="do.php?_action=view_training_records&_reset=1&ViewTrainingRecords_filter_funding=2">' . $invalid_ilrs . '</a></td></tr>';
								}
								else
									echo '<tr><td>' . $submission_record['submission'] . '</td><td>' . Date::toShort($submission_record['last_submission_date']) . '</td><td>-</td><td>-</td><td>-</td></tr>';
							}
							?>
						</table>
					</div>
				</div>
			</div>
			<div class="col-sm-12">
				<div class="box collapsed-box">
					<div class="box-header with-border">
						<h3 class="box-title"><span class="fa fa-info-circle"></span> How to Guides</h3>
						<div class="box-tools pull-right">
							<button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-plus"></i></button>
							<button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
						</div>
					</div>
					<div class="box-body" style="max-height: 250px; overflow-y: scroll;">
						<p class="text-info">Please use the guides below to help with your use of Sunesis. All our 'How to' guides are in PDF format.</p>
						<ul class="list-group list-group-unbordered">
							<?php
							$how_to_dir = DATA_ROOT."/uploads/am_demo/howto";
							$files = Repository::readDirectory($how_to_dir);
							if(count($files) == 0){
								echo '<i>No files uploaded</i>';
							}
							foreach($files as $f)
							{
								if($f->isDir()){
									continue;
								}
								$ext = new SplFileInfo($f->getName());
								$ext = $ext->getExtension();
								$image = 'fa-file';
								if($ext == 'doc' || $ext == 'docx')
									$image = 'fa-file-word-o';
								elseif($ext == 'pdf')
									$image = 'fa-file-pdf-o';
								elseif($ext == 'txt')
									$image = 'fa-file-text-o';
								echo '<li class="list-group-item"><a href="do.php?_action=downloader&path=/am_demo/howto/'. "&f=" . $f->getName() . '"><i class="fa '.$image.'"></i> ' . htmlspecialchars((string)$f->getName()) . '</a><br><span class="direct-chat-timestamp "><i class="fa fa-clock-o"></i> <small>' . date("d/m/Y H:i:s", $f->getModifiedTime()) .'</small></span></li>';
							}
							?>
						</ul>
					</div>
				</div>
			</div>
		</div>
	</div>
	<div class="col-sm-8">
		<div class="row">
			<div class="col-sm-<?php echo $_SESSION['user']->isAdmin() ? '12' : '12'; ?>">
				<div class="box">
					<div class="box-body table-responsive"><div class="chart-responsive" id="panelLearnersByAssessors"></div></div>
				</div>
			</div>
			<div class="col-sm-<?php echo $_SESSION['user']->isAdmin() ? '12' : '12'; ?>">
				<div class="box">
					<div class="box-body"><div class="chart-responsive" id="panelLearnersByEthnicity"></div></div>
				</div>
			</div>
			<div class="col-sm-12">
				<div class="box">
					<!--<div class="box-body table-responsive"><div class="chart-responsive" id="panelLearnersByContracts"></div></div>-->
					<div class="box-body table-responsive"><div class="chart-responsive" id="panelAchieversForecast"></div></div>
				</div>
			</div>
		</div>
	</div>
</div>
</section>

</div>

<footer class="main-footer">
	<div class="pull-right hidden-xs">Powered by Sunesis &nbsp;|&nbsp;&copy; <?php echo date('Y'); ?> Perspective Ltd</div>
	<strong><?php echo date('D, d M Y'); ?></strong>
</footer>

</div>

<script src="/assets/adminlte/plugins/jQuery/jquery-2.2.3.min.js"></script>
<script src="/assets/adminlte/bootstrap/js/bootstrap.min.js"></script>
<script src="/assets/adminlte/dist/js/app.min.js"></script>
<script src="/assets/adminlte/plugins/toastr/toastr.min.js"></script>
<script src="/assets/adminlte/plugins/daterangepicker/moment.min.js"></script>
<script src="/assets/adminlte/plugins/daterangepicker/daterangepicker.js"></script>
<script src="/assets/adminlte/plugins/iCheck/icheck.min.js"></script>
<script src="/assets/adminlte/plugins/chartjs/ChartNew.js"></script>
<script src="/assets/adminlte/plugins/chartjs/shapesInChart.js"></script>
<script src="/assets/adminlte/plugins/chartjs/drillDown.js"></script>

<SCRIPT>

$(function(){
	loadStatsLearnersByStatus();
	loadStatsLearnersByProgression();
	loadDgtLearnersByProgress();
	loadDgtFileRepo();
	loadBarLearnersByAssessors();
	loadStatsLearnersByEthnicity();
	//loadBarLearnersByContracts();
	loadBarAchieversForecast();
});

function loadStatsLearnersByStatus()
{
	var response = localStorage.getItem("getStatsLearnersByStatus");
	if(response !== null)
	{
		response = JSON.parse(response);
		$('.boxStatsLearnersByStatus .continuing .number').html(response.continuing);
		$('.boxStatsLearnersByStatus .continuing .link').attr('href', 'do.php?_action=view_training_records&_reset=1&ViewTrainingRecords_filter_record_status[]=1&ViewTrainingRecords_filter_contract_year=<?php echo $current_contract_year; ?>');
		$('.boxStatsLearnersByStatus .temp_withdrawn .number').html(response.temp_withdrawn);
		$('.boxStatsLearnersByStatus .temp_withdrawn .link').attr('href', 'do.php?_action=view_training_records&_reset=1&ViewTrainingRecords_filter_record_status[]=7&ViewTrainingRecords_filter_contract_year=<?php echo $current_contract_year; ?>');
		$('.boxStatsLearnersByStatus .withdrawn .number').html(response.withdrawn);
		$('.boxStatsLearnersByStatus .withdrawn .link').attr('href', 'do.php?_action=view_training_records&_reset=1&ViewTrainingRecords_filter_record_status[]=3&ViewTrainingRecords_filter_contract_year=<?php echo $current_contract_year; ?>');
		$('.boxStatsLearnersByStatus .past_planned_end_date .number').html(response.past_planned_end_date);
		$('.boxStatsLearnersByStatus .past_planned_end_date .link').attr('href', 'do.php?_action=view_training_records&_reset=1&ViewTrainingRecords_filter_record_status[]=1&ViewTrainingRecords_filter_contract_year=<?php echo $current_contract_year; ?>&ViewTrainingRecords_target_end_date=<?php echo date('d/m/Y'); ?>');
		$('.boxStatsLearnersByStatus .loading').hide();

		$('.boxStatsLearnersByProgression .completed .number').html(response.completed);
		$('.boxStatsLearnersByProgression .completed .link').attr('href', 'do.php?_action=view_training_records&_reset=1&ViewTrainingRecords_filter_record_status[]=2&ViewTrainingRecords_filter_contract_year=<?php echo $current_contract_year; ?>');
	}
	else
	{
		$.ajax({
			type:'GET',
			url:'do.php?_action=home_page_v2&subaction=getStatsLearnersByStatus',
			dataType: 'json',
			beforeSend: function(){
				$('.boxStatsLearnersByStatus .loading').show();
			},
			success: function(response) {
				localStorage.setItem('getStatsLearnersByStatus', JSON.stringify(response));
				$('.boxStatsLearnersByStatus .continuing .number').html(response.continuing);
				$('.boxStatsLearnersByStatus .continuing .link').attr('href', 'do.php?_action=view_training_records&_reset=1&ViewTrainingRecords_filter_record_status[]=1&ViewTrainingRecords_filter_contract_year=<?php echo $current_contract_year; ?>');
				$('.boxStatsLearnersByStatus .temp_withdrawn .number').html(response.temp_withdrawn);
				$('.boxStatsLearnersByStatus .temp_withdrawn .link').attr('href', 'do.php?_action=view_training_records&_reset=1&ViewTrainingRecords_filter_record_status[]=6&ViewTrainingRecords_filter_contract_year=<?php echo $current_contract_year; ?>');
				$('.boxStatsLearnersByStatus .withdrawn .number').html(response.withdrawn);
				$('.boxStatsLearnersByStatus .withdrawn .link').attr('href', 'do.php?_action=view_training_records&_reset=1&ViewTrainingRecords_filter_record_status[]=3&ViewTrainingRecords_filter_contract_year=<?php echo $current_contract_year; ?>');
				$('.boxStatsLearnersByStatus .past_planned_end_date .number').html(response.past_planned_end_date);
				$('.boxStatsLearnersByStatus .past_planned_end_date .link').attr('href', 'do.php?_action=view_training_records&_reset=1&ViewTrainingRecords_filter_record_status[]=1&ViewTrainingRecords_filter_contract_year=<?php echo $current_contract_year; ?>&ViewTrainingRecords_target_end_date=<?php echo date('d/m/Y'); ?>');
				$('.boxStatsLearnersByStatus .loading').hide();

				$('.boxStatsLearnersByProgression .completed .number').html(response.completed);
				$('.boxStatsLearnersByProgression .completed .link').attr('href', 'do.php?_action=view_training_records&_reset=1&ViewTrainingRecords_filter_record_status[]=2&ViewTrainingRecords_filter_contract_year=<?php echo $current_contract_year; ?>');

			},
			error: function(data, textStatus, xhr){
				console.log(data.responseText);
			}
		});
	}
}

function loadStatsLearnersByProgression()
{
	var response = localStorage.getItem("getStatsLearnersByStatus");
	if(response !== null)
	{
		response = JSON.parse(response);
		$('.boxStatsLearnersByProgression .progl2l3 .number').html(response.progl2l3);
		$('.boxStatsLearnersByProgression .progl2l3 .link').attr('href', 'do.php?_action=view_l2l3_progression&_reset=1&ViewL2L3Progression_filter_report_type=0&ViewL2L3Progression_filter_second_contract_year=<?php echo $current_contract_year; ?>');
		$('.boxStatsLearnersByProgression .progl3l4 .number').html(response.progl3l4);
		$('.boxStatsLearnersByProgression .progl3l4 .link').attr('href', 'do.php?_action=view_l2l3_progression&_reset=1&ViewL2L3Progression_filter_report_type=1&ViewL2L3Progression_filter_second_contract_year=<?php echo $current_contract_year; ?>');
		$('.boxStatsLearnersByProgression .progsp .number').html(response.sp);
		$('.boxStatsLearnersByProgression .progsp .link').attr('href', 'do.php?_action=view_l2l3_progression&_reset=1ViewL2L3Progression_filter_report_type=3&ViewL2L3Progression_filter_second_contract_year=<?php echo $current_contract_year; ?>');
		$('.boxStatsLearnersByProgression .progta .number').html(response.ttoa);
		$('.boxStatsLearnersByProgression .progta .link').attr('href', 'do.php?_action=view_l2l3_progression&_reset=1&ViewL2L3Progression_filter_report_type=2&ViewL2L3Progression_filter_second_contract_year=<?php echo $current_contract_year; ?>');
		$('.boxStatsLearnersByProgression .loading').hide();
	}
	else
	{
		$.ajax({
			type:'GET',
			url:'do.php?_action=home_page_v2&subaction=getStatsLearnersByProgression',
			dataType: 'json',
			beforeSend: function(){
				$('.boxStatsLearnersByProgression .loading').show();
			},
			success: function(response) {
				localStorage.setItem('getStatsLearnersByProgression', JSON.stringify(response));
				$('.boxStatsLearnersByProgression .progl2l3 .number').html(response.progl2l3);
				$('.boxStatsLearnersByProgression .progl2l3 .link').attr('href', 'do.php?_action=view_l2l3_progression&_reset=1&ViewL2L3Progression_filter_report_type=0&ViewL2L3Progression_filter_second_contract_year=<?php echo $current_contract_year; ?>');
				$('.boxStatsLearnersByProgression .progl3l4 .number').html(response.progl3l4);
				$('.boxStatsLearnersByProgression .progl3l4 .link').attr('href', 'do.php?_action=view_l2l3_progression&_reset=1&ViewL2L3Progression_filter_report_type=1&ViewL2L3Progression_filter_second_contract_year=<?php echo $current_contract_year; ?>');
				$('.boxStatsLearnersByProgression .progsp .number').html(response.sp);
				$('.boxStatsLearnersByProgression .progsp .link').attr('href', 'do.php?_action=view_l2l3_progression&_reset=1ViewL2L3Progression_filter_report_type=3&ViewL2L3Progression_filter_second_contract_year=<?php echo $current_contract_year; ?>');
				$('.boxStatsLearnersByProgression .progta .number').html(response.ttoa);
				$('.boxStatsLearnersByProgression .progta .link').attr('href', 'do.php?_action=view_l2l3_progression&_reset=1&ViewL2L3Progression_filter_report_type=2&ViewL2L3Progression_filter_second_contract_year=<?php echo $current_contract_year; ?>');
				$('.boxStatsLearnersByProgression .loading').hide();
			},
			error: function(data, textStatus, xhr){
				console.log(data.responseText);
			}
		});
	}
}

function fnAddLinksToDgtLearnerByProgress(event,ctx,config,data,other)
{
	console.log('asdasd');
	/*window.location.href = "do.php?_action=view_training_records&_reset=1&ViewTrainingRecords_filter_progress=1";
	   if(other != null && other != undefined)
	   {
		   console.log(other.v2, other.v1);
	   }*/
}

function loadDgtLearnersByProgress()
{
	var r = localStorage.getItem("getStatsLearnersByProgress");
	if(r !== null)
	{
		r = JSON.parse(r);
		$("#panelLearnersByProgress").html('<canvas id="dgtLearnersByProgress" height="800" width="700" style="width: 100%;"></canvas>');
		var myDoughnut = new Chart(document.getElementById("dgtLearnersByProgress").getContext("2d")).Doughnut(r.data, r.options);
		return;
	}
	$.ajax({
		type:'GET',
		url:'do.php?_action=home_page_v2&subaction=getStatsLearnersByProgress',
		dataType: 'json',
		beforeSend: function(){
			$("#panelLearnersByProgress").html("<h3 class='text-bold'><i class=\"fa fa-refresh fa-spin\"></i> loading graph ...</h3>");
		},
		success: function(response) {
			response.options.mouseDownLeft = fnAddLinksToDgtLearnerByProgress;
			localStorage.setItem('getStatsLearnersByProgress', JSON.stringify(response));
			$("#panelLearnersByProgress").html('<canvas id="dgtLearnersByProgress" height="350" width="350" style="width: 100%;"></canvas>');
			var myDoughnut = new Chart(document.getElementById("dgtLearnersByProgress").getContext("2d")).Doughnut(response.data, response.options);
		},
		error: function(data, textStatus, xhr){
			console.log(data);
		}
	});
}

function loadStatsLearnersByEthnicity()
{
	var r = localStorage.getItem("getStatsLearnersByEthnicity");
	if(r !== null)
	{
		r = JSON.parse(r);
		$("#panelLearnersByEthnicity").html('<canvas id="dgtLearnersByEthnicity" height="450" width="450" style="width: 100%;"></canvas>');
		var myDoughnut = new Chart(document.getElementById("dgtLearnersByEthnicity").getContext("2d")).Doughnut(r.data, r.options);
		return;
	}
	$.ajax({
		type:'GET',
		url:'do.php?_action=home_page_v2&subaction=getStatsLearnersByEthnicity',
		dataType: 'json',
		beforeSend: function(){
			$("#panelLearnersByEthnicity").html("<h3 class='text-bold'><i class=\"fa fa-refresh fa-spin\"></i> loading graph ...</h3>");
		},
		success: function(response) {
			localStorage.setItem('getStatsLearnersByEthnicity', JSON.stringify(response));
			$("#panelLearnersByEthnicity").html('<canvas id="dgtLearnersByEthnicity" height="450" width="450" style="width: 100%;"></canvas>');
			var myDoughnut = new Chart(document.getElementById("dgtLearnersByEthnicity").getContext("2d")).Doughnut(response.data, response.options);
		},
		error: function(data, textStatus, xhr){
			console.log(data);
		}
	});
}

function loadDgtFileRepo()
{
	$.ajax({
		type:'GET',
		url:'do.php?_action=home_page_v2&subaction=getStatsFileRepo',
		dataType: 'json',
		beforeSend: function(){
			$("#panelFileRepo").html("<h3 class='text-bold'><i class=\"fa fa-refresh fa-spin\"></i> loading graph ...</h3>");
		},
		success: function(response) {
			$("#panelFileRepo").html('<canvas id="dgtFileRepo" height="800" width="700" style="width: 100%;"></canvas>');
			var myDoughnut = new Chart(document.getElementById("dgtFileRepo").getContext("2d")).Doughnut(response.data, response.options);
		},
		error: function(data, textStatus, xhr){
			console.log(data);
		}
	});
}

function loadBarLearnersByAssessors()
{
	var r = localStorage.getItem("getStatsLearnersByAssessors");
	if(r !== null)
	{
		r = JSON.parse(r);
		$("#panelLearnersByAssessors").html('<canvas id="barLearnersByAssessors" height="550" width="550" style="width: 100%;"></canvas>');
	<?php if($_SESSION['user']->type == User::TYPE_ASSESSOR) {?>
		var myLine1 = new Chart(document.getElementById("barLearnersByAssessors").getContext("2d")).Bar(r.data, r.options);
		<?php } else {?>
		var myLine1 = new Chart(document.getElementById("barLearnersByAssessors").getContext("2d")).StackedBar(r.data, r.options);
		<?php } ?>
		return;
	}
	$.ajax({
		type:'GET',
		url:'do.php?_action=home_page_v2&subaction=getStatsLearnersByAssessors',
		dataType: 'json',
		beforeSend: function(){
			$("#panelLearnersByAssessors").html("<h3 class='text-bold'><i class=\"fa fa-refresh fa-spin\"></i> loading graph ...</h3>");
		},
		success: function(response) {
			localStorage.setItem('getStatsLearnersByAssessors', JSON.stringify(response));
			$("#panelLearnersByAssessors").html('<canvas id="barLearnersByAssessors" height="550" width="550" style="width: 100%;"></canvas>');
		<?php if($_SESSION['user']->type == User::TYPE_ASSESSOR) {?>
			var myLine1 = new Chart(document.getElementById("barLearnersByAssessors").getContext("2d")).Bar(response.data, response.options);
			<?php } else {?>
			var myLine1 = new Chart(document.getElementById("barLearnersByAssessors").getContext("2d")).StackedBar(response.data, response.options);
			<?php } ?>
		},
		error: function(data, textStatus, xhr){
			console.log(data.responseText);
		}
	});
}

function loadBarLearnersByContracts()
{
	var r = localStorage.getItem("getStatsLearnersByContracts");
	if(r !== null)
	{
		r = JSON.parse(r);
		$("#panelLearnersByContracts").html('<canvas id="barLearnersByContracts" height="600" width="700" style="width: 100%;"></canvas>');
		var myLine1 = new Chart(document.getElementById("barLearnersByContracts").getContext("2d")).StackedBar(r.data, r.options);
		return;
	}
	$.ajax({
		type:'GET',
		url:'do.php?_action=home_page_v2&subaction=getStatsLearnersByContracts',
		dataType: 'json',
		beforeSend: function(){
			$("#panelLearnersByContracts").html("<h3 class='text-bold'><i class=\"fa fa-refresh fa-spin\"></i> loading graph ...</h3>");
		},
		success: function(response) {
			localStorage.setItem('getStatsLearnersByContracts', JSON.stringify(response));
			$("#panelLearnersByContracts").html('<canvas id="barLearnersByContracts" height="600" width="700" style="width: 100%;"></canvas>');
			var myLine1 = new Chart(document.getElementById("barLearnersByContracts").getContext("2d")).StackedBar(response.data, response.options);
		},
		error: function(data, textStatus, xhr){
			console.log(data.responseText);
		}
	});
}

function loadBarAchieversForecast()
{
	var r = localStorage.getItem("getStatsAchieversForecast");
	if(false && r !== null)
	{
		r = JSON.parse(r);
		$("#panelAchieversForecast").html('<canvas id="barAchieversForecast" height="600" width="700" style="width: 100%;"></canvas>');
		var myLine1 = new Chart(document.getElementById("barAchieversForecast").getContext("2d")).Bar(r.data, r.options);
		return;
	}
	$.ajax({
		type:'GET',
		url:'do.php?_action=home_page_v2&subaction=getAchieversForecast',
		dataType: 'json',
		beforeSend: function(){
			$("#panelAchieversForecast").html("<h3 class='text-bold'><i class=\"fa fa-refresh fa-spin\"></i> loading graph ...</h3>");
		},
		success: function(response) {console.log(response);
			localStorage.setItem('getAchieversForecast', JSON.stringify(response));
			$("#panelAchieversForecast").html('<canvas id="barAchieversForecast" height="600" width="700" style="width: 100%;"></canvas>');
			var myLine1 = new Chart(document.getElementById("barAchieversForecast").getContext("2d")).Bar(response.data, response.options);
		},
		error: function(data, textStatus, xhr){
			console.log(data.responseText);
		}
	});
}

</SCRIPT>

</body>
</html>
