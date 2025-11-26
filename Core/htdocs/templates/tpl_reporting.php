<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<title>Sunesis | Homepage</title>
	<meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
	<link rel="stylesheet" href="/assets/adminlte/bootstrap/css/bootstrap.min.css">
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.5.0/css/font-awesome.min.css">
	<link rel="stylesheet" href="/assets/adminlte/dist/css/AdminLTE.min.css">

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

<div class="row">
	<div class="col-sm-12 col-md-8 col-md-offset-2">
		<div id="panelFundingByProvision"></div>
	</div>
</div>
<div class="row" id="rowGraphs" style="display: none;">
	<div class="col-sm-12 col-md-8 col-md-offset-2">
		<p id="lblMainHeading" class="text-center text-bold"></p>
		<div id="panelLearnersByEthnicity"></div>
		<div id="panelLearnersByGender"></div>
		<div id="panelLearnersByLLDD"></div>
		<div id="panelLearnersByLLDDCategories"></div>
		<div id="panelLearnersByAgeRange"></div>
		<div id="panelLearnersByEmployers"></div>
	</div>
</div>

<script src="/assets/adminlte/plugins/jQuery/jquery-2.2.3.min.js"></script>
<script src="/assets/adminlte/bootstrap/js/bootstrap.min.js"></script>
<script src="/assets/adminlte/plugins/chartjs/ChartNew.js"></script>
<script src="/assets/adminlte/plugins/chartjs/shapesInChart.js"></script>
<script src="/assets/adminlte/plugins/chartjs/drillDown.js"></script>

<SCRIPT>

$(function(){
	loadBarFundingByProvision();
});

function loadBarFundingByProvision()
{
	$.ajax({
		type:'GET',
		url:'do.php?_action=ajax_reporting&subaction=all_learners_by_funding_provision',
		dataType: 'json',
		beforeSend: function(){
			$("#panelFundingByProvision").html("<h5 class='text-bold'><i class=\"fa fa-refresh fa-spin\"></i> loading graph ...</h5>");
		},
		success: function(response) {
			response.options.mouseDownLeft = fnFundingByProvision;
			$("#panelFundingByProvision").html('<canvas id="barFundingByProvision" height="600" width="1200"></canvas>');
			var myLine = new Chart(document.getElementById("barFundingByProvision").getContext("2d")).HorizontalStackedBar(response.data, response.options);
		},
		error: function(data, textStatus, xhr){
			console.log(data.responseText);
		}
	});
}

function fnFundingByProvision(event,ctx,config,data,other)
{
	if(other != null && other != undefined)
	{
		console.log(other);
		$('#rowGraphs').show();
		$('#lblMainHeading').html('<h4 class="text-bold">' + other.v1 + ' - ' + other.v2 + ' <span class="text-blue">(' + other.v3 + ' learners)</span> </h4> ');
		loadDgtLearnersByEthnicity(other.v2, other.v1);
		loadDgtLearnersByGender(other.v2, other.v1);
		loadDgtLearnersByLLDD(other.v2, other.v1);
		loadBarLearnersByLLDDCategories(other.v2, other.v1);
		loadBarLearnersByAgeRange(other.v2, other.v1);
		loadBarLearnersByEmployers(other.v2, other.v1);
	}
}

function setColor(area,ctx,data,statdata,i,j,othervars)
{
	return(data.datasets[i].fillColor);
}

function loadDgtLearnersByEthnicity(funding_provision, status)
{
	$.ajax({
		type:'GET',
		url:'do.php?_action=ajax_reporting&subaction=learners_by_ethnicity&funding_provision='+encodeURIComponent(funding_provision)+'&status='+encodeURIComponent(status),
		dataType: 'json',
		beforeSend: function(){
			$("#panelLearnersByEthnicity").html("<h5 class='text-bold'><i class=\"fa fa-refresh fa-spin\"></i> loading graph ...</h5>");
		},
		success: function(response) {
			$("#panelLearnersByEthnicity").html('<canvas id="dgtLearnersByEthnicity" height="600" width="1200"></canvas>');
			var myDoughnut = new Chart(document.getElementById("dgtLearnersByEthnicity").getContext("2d")).Doughnut(response.data, response.options);
		},
		error: function(data, textStatus, xhr){
			console.log(data);
		}
	});
}

function loadDgtLearnersByGender(funding_provision, status)
{
	$.ajax({
		type:'GET',
		url:'do.php?_action=ajax_reporting&subaction=learners_by_gender&funding_provision='+encodeURIComponent(funding_provision)+'&status='+encodeURIComponent(status),
		dataType: 'json',
		beforeSend: function(){
			$("#panelLearnersByGender").html("<h5 class='text-bold'><i class=\"fa fa-refresh fa-spin\"></i> loading graph ...</h5>");
		},
		success: function(response) {
			$("#panelLearnersByGender").html('<canvas id="dgtLearnersByGender" height="600" width="1200"></canvas>');
			var myDoughnut = new Chart(document.getElementById("dgtLearnersByGender").getContext("2d")).Doughnut(response.data, response.options);
		},
		error: function(data, textStatus, xhr){
			console.log(data);
		}
	});
}

function loadDgtLearnersByLLDD(funding_provision, status)
{
	$.ajax({
		type:'GET',
		url:'do.php?_action=ajax_reporting&subaction=learners_by_lldd&funding_provision='+encodeURIComponent(funding_provision)+'&status='+encodeURIComponent(status),
		dataType: 'json',
		beforeSend: function(){
			$("#panelLearnersByLLDD").html("<h5 class='text-bold'><i class=\"fa fa-refresh fa-spin\"></i> loading graph ...</h5>");
		},
		success: function(response) {
			$("#panelLearnersByLLDD").html('<canvas id="dgtLearnersByLLDD" height="600" width="1200"></canvas>');
			var myDoughnut = new Chart(document.getElementById("dgtLearnersByLLDD").getContext("2d")).Doughnut(response.data, response.options);
		},
		error: function(data, textStatus, xhr){
			console.log(data);
		}
	});
}

function loadBarLearnersByLLDDCategories(funding_provision, status)
{
	$.ajax({
		type:'GET',
		url:'do.php?_action=ajax_reporting&subaction=all_learners_by_lldd_categories&funding_provision='+encodeURIComponent(funding_provision)+'&status='+encodeURIComponent(status),
		dataType: 'json',
		beforeSend: function(){
			$("#panelLearnersByLLDDCategories").html("<h5 class='text-bold'><i class=\"fa fa-refresh fa-spin\"></i> loading graph ...</h5>");
		},
		success: function(response) {
			$("#panelLearnersByLLDDCategories").html('<canvas id="barLearnersByLLDDCategories" height="600" width="1200"></canvas>');
			var myLine1 = new Chart(document.getElementById("barLearnersByLLDDCategories").getContext("2d")).Bar(response.data, response.options);
		},
		error: function(data, textStatus, xhr){
			console.log(data.responseText);
		}
	});
}

function loadBarLearnersByAgeRange(funding_provision, status)
{
	$.ajax({
		type:'GET',
		url:'do.php?_action=ajax_reporting&subaction=learners_by_age_range&funding_provision='+encodeURIComponent(funding_provision)+'&status='+encodeURIComponent(status),
		dataType: 'json',
		beforeSend: function(){
			$("#panelLearnersByAgeRange").html("<h5 class='text-bold'><i class=\"fa fa-refresh fa-spin\"></i> loading graph ...</h5>");
		},
		success: function(response) {
			$("#panelLearnersByAgeRange").html('<canvas id="barLearnersByAgeRange" height="600" width="1200"></canvas>');
			var myLine1 = new Chart(document.getElementById("barLearnersByAgeRange").getContext("2d")).Doughnut(response.data, response.options);
		},
		error: function(data, textStatus, xhr){
			console.log(data.responseText);
		}
	});
}

function loadBarLearnersByEmployers(funding_provision, status)
{
	$.ajax({
		type:'GET',
		url:'do.php?_action=ajax_reporting&subaction=learners_by_employers&funding_provision='+encodeURIComponent(funding_provision)+'&status='+encodeURIComponent(status),
		dataType: 'json',
		beforeSend: function(){
			$("#panelLearnersByEmployers").html("<h5 class='text-bold'><i class=\"fa fa-refresh fa-spin\"></i> loading graph ...</h5>");
		},
		success: function(response) {
			$("#panelLearnersByEmployers").html('<canvas id="barLearnersByEmployers" height="600" width="1200"></canvas>');
			var myLine1 = new Chart(document.getElementById("barLearnersByEmployers").getContext("2d")).Bar(response.data, response.options);
		},
		error: function(data, textStatus, xhr){
			console.log(data.responseText);
		}
	});
}
</SCRIPT>

</body>
</html>
