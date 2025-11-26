<?php /* @var $tr TrainingRecord */ ?>

<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/html" xmlns="http://www.w3.org/1999/html" xmlns="http://www.w3.org/1999/html"
      xmlns="http://www.w3.org/1999/html">
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<title>Retailer Self Assessment</title>
	<meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
	<link rel="stylesheet" href="/assets/adminlte/bootstrap/css/bootstrap.min.css">
	<link rel="stylesheet" href="/assets/adminlte/dist/css/AdminLTE.min.css">
	<link rel="stylesheet" href="/assets/adminlte/plugins/jQueryUI/jquery-ui.css">
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.5.0/css/font-awesome.min.css">
	<link rel="stylesheet" href="/assets/adminlte/plugins/iCheck/all.css">

	<!--[if lt IE 9]>
	<script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
	<script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
	<![endif]-->

	<style>

		html,
		body {
			padding-top: 30px;
			height: 100%;
		}

		.step-content {
			border:2px groove blue;
			min-height: 640px;
			border-radius: 10px;
		}

		h2 {
			color: #0000ff;
		}

		.navbar-fixed-top {
			min-height: 50px;
			max-height: 50px;
			background: #ffffff url("module_eportfolio/assets/images/pp.png") center center;
		}

		@media (min-width: 768px) {
			.navbar-custom {
				/*padding: 5px 0;*/
				-webkit-transition: padding 0.3s;
				-moz-transition: padding 0.3s;
				transition: padding 0.3s;
			}
			.navbar-custom.affix {
				padding: 0;
			}
		}

		input[disabled] {
			opacity: .5;
		}
		textarea {
			border:1px solid #3366FF;
			border-radius: 5px;
			border-left: 5px solid #3366FF;
			color:gray;
			font-size:16px;
		}

		.sigbox {
			border-radius: 15px;
			border: 1px solid #EEE;
			cursor: pointer;
		}
		.sigboxselected {
			border-radius: 25px;
			border: 2px solid #EEE;
			cursor: pointer;
			background-color: #d3d3d3;
		}

		.ui-datepicker .ui-datepicker-title select {
			color: #000000;
		}

		input[type=text]{
			line-height:25px;
			color:gray;
			font-size:16px;
		}

		.disabledd{
			pointer-events:none;
			opacity:0.7;
		}

		#btnGoTop {
			display: none;
			position: fixed;
			bottom: 20px;
			right: 30px;
			z-index: 99;
			font-size: 18px;
			border: none;
			outline: none;
			background-color: green;
			color: white;
			cursor: pointer;
			padding: 5px;
			border-radius: 4px;
		}

		#btnGoTop:hover {
			background-color: #555;
		}

		input[type=checkbox] {
			transform: scale(1.4);
		}
	</style>
</head>

<body>

<nav id="mainNav" class="navbar navbar-default navbar-fixed-top navbar-custom">
	<div class="container-float">
		<div class="navbar-header page-scroll">
			<a class="navbar-brand" href="#">
				<img height="35px" class="headerlogo" src="images/logos/superdrug.png" />
			</a>
		</div>
	</div>
	<div class="text-center" style="margin-top: 5px;"><h3><?php echo $tr->firstnames . ' '  . strtoupper($tr->surname); ?></h3></div>
</nav>

<header class="main-header"></header>

<div class="content-wrapper" style="background-color: #ffffff;">

	<section class="content-header text-center"><h1>Retailer Self Assessment</h1></section>

	<section class="content">

		<div class="row">
			<div class="col-sm-12">
				<span class="btn btn-md btn-default" onclick="window.location.href='<?php echo $_SESSION['bc']->getPrevious(); ?>';"><i class="fa fa-arrow-circle-o-left"></i> Go Back</span>
				<span class="btn btn-md btn-primary" <?php echo $_SESSION['user']->type != User::TYPE_LEARNER ? "disabled": ""; ?> id="btnSaveForm"><i class="fa fa-save"></i> Save Changes</span>
			</div>
			<div class="col-sm-12 table-responsive">
				<form role="form" class="form-horizontal" name="frmRetailerSelfAssessment" id="frmRetailerSelfAssessment" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
					<input type="hidden" name="_action" value="save_retailer_self_assessment" />
					<input type="hidden" name="tr_id" value="<?php echo $tr->id; ?>" />

					<?php $this->renderForm($link, $assessment); ?>

				</form>
			</div>
			<button type="button" onclick="topFunction()" id="btnGoTop" title="Go to top"><i class="fa fa-arrow-up"></i> </button>
		</div>

	</section>

</div>

<div id = "loading"></div>

<script src="/assets/adminlte/plugins/jQuery/jquery-2.2.3.min.js"></script>
<script src="/assets/adminlte/plugins/jQueryUI/jquery-ui.min.js"></script>
<script src="/assets/adminlte/bootstrap/js/bootstrap.min.js"></script>
<script src="/assets/adminlte/plugins/iCheck/icheck.min.js"></script>

<script type="text/javascript">

	$(function(){
		//$('input[type=checkbox]').iCheck({checkboxClass: 'icheckbox_square-green'});

		$('.datepicker').datepicker({
			dateFormat: 'dd/mm/yy',
			yearRange: 'c-50:c+50',
			changeMonth: false,
			changeYear: true,
			constrainInput: true,
			buttonImage: "/images/calendar-icon.gif",
			buttonImageOnly: true,
			buttonText: "Show calendar",
			showOn: "both",
			showAnim: "fadeIn"
		});

		$('#btnSaveForm').on('click', function(){
			<?php echo $_SESSION['user']->type != User::TYPE_LEARNER ? "return;": "$('#frmRetailerSelfAssessment').submit();"; ?>
		});

		<?php
		if($_SESSION['user']->type != User::TYPE_LEARNER)
		{
			echo "$(\"#frmRetailerSelfAssessment :checkbox\").prop(\"disabled\", true);";
		}
		?>

		/*$('input[type=checkbox]').on('ifChecked', function(){
			$(this).closest('tr').attr('class', 'bg-green');
		});

		$('input[type=checkbox]').on('ifUnchecked', function(){
			$(this).closest('tr').attr('class', '');
		});*/

		$('input[type=checkbox]').on('change', function(){
            if(this.checked)
                $(this).closest('tr').attr('class', 'bg-green');
            else
                $(this).closest('tr').attr('class', '');
        });

	});

	window.onscroll = function() {scrollFunction()};

	function scrollFunction() {
		if (document.body.scrollTop > 20 || document.documentElement.scrollTop > 20) {
			document.getElementById("btnGoTop").style.display = "block";
		} else {
			document.getElementById("btnGoTop").style.display = "none";
		}
	}

	function topFunction() {
		document.body.scrollTop = 0;
		document.documentElement.scrollTop = 0;
	}


</script>

</html>
