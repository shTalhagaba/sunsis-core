<?php /* @var $ob_learner User */ ?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/html">
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<title><?php echo $company_name; ?> | Commitment Statement</title>
	<meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
	<link rel="stylesheet" href="/assets/adminlte/bootstrap/css/bootstrap.min.css">
	<link rel="stylesheet" href="/assets/adminlte/dist/css/AdminLTE.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.5.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="/module_onboarding/css/onboarding.css">

	<style type="text/css">
		textarea, input[type=text] {
			border:1px solid #3366FF;
			border-radius: 5px;
			border-left: 5px solid #3366FF;
		}
		input[type=checkbox] {
			transform: scale(1.4);
		}
		.loader{
			position: fixed;
			left: 0px;
			top: 0px;
			width: 100%;
			height: 100%;
			z-index: 1000;
			background: url('images/progress-animations/loading51.gif')
			50% 50% no-repeat rgba( 255, 255, 255, .8 );
		}
		.disabledRow {
			pointer-events: none;
			opacity: 0.7;
		}

	</style>

	<script src="/assets/adminlte/plugins/jQuery/jquery-2.2.3.min.js"></script>

</head>


<body>

<nav id="mainNav" class="navbar navbar-default navbar-fixed-top navbar-custom" style="background-color: black;background-image: linear-gradient(to right, black, gold)">
	<div class="container">
		<div class="navbar-header page-scroll">
			<a class="navbar-brand" href="#">
				<img height="50px" class="headerlogo" src="<?php echo $header_image1; ?>"/>
			</a>
		</div>
	</div>
</nav>

<content id="contentForm">
	<div class="nts-secondary-teaser-gradient">
		<div class="container"><h3>Commitment Statement</h3></div>
	</div>
	<br>

	<div class="container">

    <?php
    if($doc == 'cs') 
        include_once(__DIR__ . '/partials/commitment_statement.php'); 
    ?>
		
	</div>

</content>

<div class="loader" style="display: none;"></div>

<footer class="main-footer">
	<div class="pull-left">
		<table class="table" style="border-collapse: separate; border-spacing: 0.5em;">
			<tr>
				<td><img width="230px" src="<?php echo $header_image1; ?>"/></td>
				<td><img src="images/logos/siemens/ESF.png"/></td>
			</tr>
		</table>
	</div>
	<div class="pull-right">
		<img src="images/logos/SUNlogo.png"/>
	</div>
</footer>

<script type="text/javascript">
	var phpHeaderLogo1 = '<?php echo $header_image1; ?>';
	var phpHeaderLogo2 = '<?php echo $header_image2; ?>';
</script>

<script src="/assets/adminlte/bootstrap/js/bootstrap.min.js"></script>



<script type="text/javascript">


</script>

</body>
</html>
