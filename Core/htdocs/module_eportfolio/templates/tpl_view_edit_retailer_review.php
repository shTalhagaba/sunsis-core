<?php /* @var $tr TrainingRecord */ ?>
<?php /* @var $review RtReview */ ?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/html" xmlns="http://www.w3.org/1999/html" xmlns="http://www.w3.org/1999/html"
      xmlns="http://www.w3.org/1999/html">
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<title>Retailer Diploma Review</title>
	<meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
	<link rel="stylesheet" href="/assets/adminlte/bootstrap/css/bootstrap.min.css">
	<link rel="stylesheet" href="/assets/adminlte/dist/css/AdminLTE.min.css">
	<link rel="stylesheet" href="/assets/adminlte/plugins/jQueryUI/jquery-ui.css">
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.5.0/css/font-awesome.min.css">
	<link rel="stylesheet" href="/assets/adminlte/plugins/iCheck/all.css">
	<link href="/assets/adminlte/plugins/pace/pace.css" rel="stylesheet">

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

<section class="content-header text-center"><h1>Retailer Diploma Review</h1></section>

<section class="content">

	<div class="row">
		<div class="col-sm-12">
			<span class="btn btn-md btn-default" onclick="window.location.href='<?php echo $_SESSION['bc']->getPrevious(); ?>';"><i class="fa fa-arrow-circle-o-left"></i> Go Back</span>
			<?php if(!is_null($review->learner_signature) && !is_null($review->assessor_signature)) { ?>
			<span class="btn btn-md btn-primary" onclick="" id="" disabled><i class="fa fa-save"></i> Save Changes</span>
			<?php } else {?>
			<span class="btn btn-md btn-primary" onclick="" id="btnSaveForm"><i class="fa fa-save"></i> Save Changes</span>
			<?php } ?>
			<span class="btn btn-sm btn-info fa fa-file-pdf-o pull-right"
				onclick="window.location.href='do.php?_action=view_edit_retailer_review&subaction=export&id=<?php echo $review->id; ?>&tr_id=<?php echo $tr->id; ?>'" title="Generate pdf">
			</span>			
		</div>
		<div class="col-sm-12 table-responsive">
			<form role="form" class="form-horizontal" name="frmRetailerReview" id="frmRetailerReview" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
				<input type="hidden" name="_action" value="save_retailer_review" />
				<input type="hidden" name="id" value="<?php echo $review->id; ?>" />
				<input type="hidden" name="tr_id" value="<?php echo $tr->id; ?>" />

				<?php $this->renderForm($link, $review); ?>

				<div class="row">
					<div class="col-sm-12 table-responsive">
						<table class="table table-bordered">
							<tr class="bg-gray"><th>&nbsp;</th><th>Name</th><th>Signature</th><th>Date</th></tr>
							<tr>
								<td>Apprentice</td>
								<?php if($_SESSION['user']->type == User::TYPE_LEARNER) {?>
								<td><h5 class="content-max-width"><?php echo $_SESSION['user']->firstnames . ' ' . $_SESSION['user']->surname; ?></h5></td>
								<td>
									<span class="btn btn-info" onclick="getSignature('learner');">
										<img id="img_learner_signature" src="do.php?_action=generate_image&<?php echo $review->learner_signature != ''?$review->learner_signature:'title=Click here to sign&font=Signature_Regular.ttf&size=25'; ?>" style="border: 2px solid;border-radius: 15px;" />
										<input type="hidden" name="learner_signature" id="learner_signature" value="<?php echo $review->learner_signature; ?>" />
									</span>
								</td>
								<td>
									<?php echo $review->l_sign_date != '' ? Date::toShort($review->l_sign_date) : 'NOT SIGNED YET'; ?>
								</td>
								<?php } else { ?>
								<td><h5 class="content-max-width"><?php echo $tr->firstnames . ' ' . $tr->surname; ?></h5> </td>
								<td><?php echo $review->learner_signature != '' ? '<img src="do.php?_action=generate_image&'.$review->learner_signature.'&size=25" style="border: 2px solid;border-radius: 15px;" />' : 'NOT SIGNED YET';?></td>
								<td><?php echo $review->l_sign_date != '' ? Date::toShort($review->l_sign_date) : 'NOT SIGNED YET'; ?></td>
								<?php } ?>
							</tr>
							<?php if($_SESSION['user']->type == User::TYPE_ASSESSOR) {?>
							<tr>
								<td>Assessor</td>
								<td><h5 class="content-max-width"><?php echo $_SESSION['user']->firstnames . ' ' . $_SESSION['user']->surname; ?></h5></td>
								<td>
									<span class="btn btn-info" onclick="getSignature('assessor');">
										<img id="img_assessor_signature" src="do.php?_action=generate_image&<?php echo $review->assessor_signature != ''?$review->assessor_signature:'title=Click here to sign&font=Signature_Regular.ttf&size=25'; ?>" style="border: 2px solid;border-radius: 15px;" />
										<input type="hidden" name="assessor_signature" id="assessor_signature" value="<?php echo $review->assessor_signature; ?>" />
									</span>
								</td>
								<td><?php echo $review->a_sign_date != '' ? Date::toShort($review->a_sign_date) : 'NOT SIGNED YET'; ?></td>
							</tr>
							<?php } if($_SESSION['user']->type != User::TYPE_ASSESSOR && $review->assessor_signature != '') {?>
							<tr>
								<td>Assessor</td>
								<td><h5 class="content-max-width"><?php echo DAO::getSingleValue($link, "SELECT CONCAT(firstnames, ' ' , surname) FROM users WHERE id = '{$tr->assessor}'"); ?></h5> </td>
								<td><img src="do.php?_action=generate_image&<?php echo $review->assessor_signature; ?>&size=25" style="border: 2px solid;border-radius: 15px;" /></td>
								<td><?php echo $review->a_sign_date != '' ? Date::toShort($review->a_sign_date) : 'NOT SIGNED YET'; ?></td>
							</tr>
							<?php } ?>
							<?php if($_SESSION['user']->type != User::TYPE_ASSESSOR && $review->assessor_signature == '') {?>
							<tr>
								<td>Assessor</td>
								<td><h5 class="content-max-width"><?php echo DAO::getSingleValue($link, "SELECT CONCAT(firstnames, ' ' , surname) FROM users WHERE id = '{$tr->assessor}'"); ?></h5> </td>
								<td colspan="2">NOT SIGNED YET</td>
							</tr>
							<?php } ?>
						</table>
					</div>
				</div>

			</form>
		</div>
		<button type="button" onclick="topFunction()" id="btnGoTop" title="Go to top"><i class="fa fa-arrow-up"></i> </button>
	</div>

</section>

</div>

<div id = "loading"></div>

<div id="dlgPrevReviews" title="Existing Reviews" style="font-size: smaller;"></div>

<script src="/assets/adminlte/plugins/jQuery/jquery-2.2.3.min.js"></script>
<script src="/assets/adminlte/plugins/jQueryUI/jquery-ui.min.js"></script>
<script src="/assets/adminlte/bootstrap/js/bootstrap.min.js"></script>
<script src="/assets/adminlte/plugins/iCheck/icheck.min.js"></script>
<script src="/assets/adminlte/plugins/toastr/toastr.min.js"></script>
<script src="/assets/js/autoresize.js"></script>

<script type="text/javascript">

	$(function(){
		$('input[type=radio][id$="Behind"]').iCheck({radioClass: 'iradio_square-red'});
		$('input[type=radio][id$="OnTrack"]').iCheck({radioClass: 'iradio_square-green'});
		$('input[type=radio][id$="Ahead"]').iCheck({radioClass: 'iradio_square-blue'});
		$('input[type=radio][id$="Complete"]').iCheck({radioClass: 'iradio_square-pink'});

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
			<?php if(!is_null($review->learner_signature) && !is_null($review->assessor_signature)) { echo ", disabled: true"; } ?>
		});

		$('#btnSaveForm').on('click', function(){

			$('#frmRetailerReview').submit();

		});

		<?php
		if(!is_null($review->learner_signature) && !is_null($review->assessor_signature))
		{
			echo "$(\"#frmRetailerReview :input\").prop(\"readonly\", \"readonly\");";
			echo "$(\"#frmRetailerReview :radio\").prop(\"disabled\", true);";
		}
		?>

		$('#dlgPrevReviews').dialog({
			modal: true,
			width: 700,
			height: 700,
			closeOnEscape: true,
			autoOpen: false,
			resizable: true,
			draggable: true,
			buttons: {
				'Close': function() {$(this).dialog('close');}
			}
		}).css("background", "#FFF");

	});

	function showPrevReviews(review_id, tr_id, section_name)
	{
		$.ajax({
			type:'GET',
			url:'do.php?_action=view_edit_retailer_review&subaction=getPrevReviews&review_id='+review_id+'&tr_id='+tr_id+'&section_name='+encodeURIComponent(section_name),
			beforeSend: function(){
				$("#dlgPrevReviews").dialog({ title: "Please wait ..." });
				$("#dlgPrevReviews").dialog('open').html("<p><i class=\"fa fa-refresh fa-spin\"></i> Fetching information ...</p>");
			},
			success: function(data) {
				$("#dlgPrevReviews").dialog({ title: "Existing reviews" });
				$("#dlgPrevReviews").dialog('open').html(data);
			},
			error: function(data, textStatus, xhr){
				console.log(data.responseText);
			}
		});
	}

	var phpLearnerSignature = '<?php echo $learner_signature; ?>';
	var phpAssessorSignature = '<?php echo $assessor_signature; ?>';

	var fonts = Array("Little_Days.ttf","ArtySignature.ttf","Signerica_Medium.ttf","Champignon_Alt_Swash.ttf","Bailey_MF.ttf","Carolina.ttf","DirtyDarren.ttf","Ruf_In_Den_Wind.ttf");
	var sizes = Array(30,40,15,30,30,30,25,30);

	loadDefaultSignatures('learner');
	loadDefaultSignatures('assessor');

	function loadDefaultSignatures(user)
	{
		for(var i = 1; i <= 8; i++)
			$("#panel_"+user+"_signature #img"+i).attr('src', 'images/loading.gif');

		for(var i = 0; i <= 7; i++)
			$("#panel_"+user+"_signature #img"+(i+1)).attr('src', 'do.php?_action=generate_image&title=Signature'+'&font='+fonts[i]+'&size='+sizes[i]);
	}

	function getSignature(user)
	{
		<?php if($_SESSION['user']->type == User::TYPE_LEARNER){?>
			if(user == 'assessor') return;
		<?php } ?>
		<?php if($_SESSION['user']->type == User::TYPE_ASSESSOR){?>
			if(user == 'learner') return;
		<?php } ?>

		if(user == 'learner')
		{
			$('#img_learner_signature').attr('src', 'do.php?_action=generate_image&'+phpLearnerSignature);
			$('#learner_signature').val('do.php?_action=generate_image&'+phpLearnerSignature);
			return;
		}
		if(user == 'assessor')
		{
			$('#img_assessor_signature').attr('src', 'do.php?_action=generate_image&'+phpAssessorSignature);
			$('#assessor_signature').val('do.php?_action=generate_image&'+phpAssessorSignature);
			return;
		}

		$( "#panel_"+user+"_signature" ).dialog( "open");
	}

	function SignatureSelected(sig, user)
	{
		$("#panel_"+user+"_signature .sigboxselected").attr("class", "#panel_"+user+"_signature sigbox");
		sig.className = "sigboxselected";
	}

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

	autosize(document.querySelectorAll('textarea'));

	localStorage.setItem('Inaam', 'MyValue');
</script>

</html>
