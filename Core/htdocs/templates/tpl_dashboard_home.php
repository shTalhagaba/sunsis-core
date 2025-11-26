<!DOCTYPE html>
<html lang="en">
<head>
	<title>Bootstrap Example</title>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>

	<script src="/common.js?n=<?php echo time(); ?>" type="text/javascript"></script>

	<script src="https://code.highcharts.com/7.0.0/highcharts.js"></script>
	<script src="https://code.highcharts.com/7.0.0/highcharts-more.js"></script>
	<script src="https://code.highcharts.com/7.0.0/highcharts-3d.js"></script>
	<script src="https://code.highcharts.com/7.0.0/modules/exporting.js"></script>
	<script src="https://code.highcharts.com/7.0.0/modules/solid-gauge.js"></script>


	<style>
			/* Set height of the grid so .sidenav can be 100% (adjust as needed) */
		.row.content {height: 550px}

			/* Set gray background color and 100% height */
		.sidenav {
			background-color: #f1f1f1;
			height: 100%;
		}

			/* On small screens, set height to 'auto' for the grid */
		@media screen and (max-width: 767px) {
			.row.content {height: auto;}
		}

		.panel-body{
			text-align: center;
			font-size: larger;
		}
	</style>
</head>
<body>
<?php
$filename = SystemConfig::getEntityValue($link, "logo");
$filename = $filename ? $filename : 'perspective.png';
?>

<div class="container-fluid">
	<div class="row">

		<div class="col-sm-12"><iframe src="https://drive.google.com/file/d/1g6TaMCTNu8gaVfu0sfr_37eqiieQMP9g/preview" width="640" height="480"></iframe></div>
	</div>
<div class="row">
<p><br><hr></p>
</div>
<div class="row">
		<div class="col-sm-12"><iframe src="https://drive.google.com/file/d/1u18gqVBOwFzWkNymW5ySGKQdGAfDRbHd/preview" width="640" height="480"></iframe></div>

	</div>	
</div>
<script>
</script>
</body>
</html>

