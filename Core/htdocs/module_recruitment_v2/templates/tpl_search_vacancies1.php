<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/html" xmlns="http://www.w3.org/1999/html" xmlns="http://www.w3.org/1999/html"
      xmlns="http://www.w3.org/1999/html" xmlns="http://www.w3.org/1999/html">
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<title>Superdrug | Apprenticeships</title>
	<meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
	<link rel="stylesheet" href="/assets/adminlte/bootstrap/css/bootstrap.css">
	<link rel="stylesheet" href="/assets/adminlte/dist/css/AdminLTE.min.css">
	<link rel="stylesheet" href="/assets/adminlte/plugins/jQueryUI/jquery-ui.css">
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.5.0/css/font-awesome.min.css">
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/ionicons/2.0.1/css/ionicons.min.css">
	<link href="assets/css/animate.css" rel="stylesheet">
	<link rel="stylesheet" href="/module_recruitment_v2/css/superdrug.css">

	<style>
		body {
			padding-top: 100px;
		}
		@media (min-width: 992px) {
			body {
				padding-top: 100px;
			}
		}
		#postcode{text-transform:uppercase}
		.ui-datepicker .ui-datepicker-title select {
			color: #000;
		}
	</style>
</head>


<body>
<?php
$logo = SystemConfig::getEntityValue($link, 'logo');
if($logo == '')
	$logo = 'SUNlogo.jpg';
?>

<nav id="mainNav" class="navbar navbar-default navbar-fixed-top navbar-custom">
	<div class="container">
		<div class="navbar-header page-scroll">
			<a class="navbar-brand" href="https://www.superdrug.com/" target="_blank">
				<img height="60px" class="headerlogo" src="images/logos/<?php echo $logo; ?>" />
			</a>
		</div>
	</div>
</nav>

<div class="container">
	<div class="row">
		<div class="col-lg-12">
			<div class="panel-gradient">
				<form class="form-horizontal" id="frmSearchVacancies" name="frmSearchVacancies" action="/do.php?_action=search_vacancies" method="post" autocomplete="off">
					<div class="form-group">
						<label for="sector" class="col-sm-4 control-label fieldLabel_compulsory">Sector:</label>
						<div class="col-sm-8">
							<?php echo HTML::selectChosen('sector', $type_ddl, $sector, true, false, true, 1, ' style="min-width:270px;" '); ?>
						</div>
					</div>
					<div class="form-group">
						<label for="region" class="col-sm-4 control-label fieldLabel_compulsory">Region:</label>
						<div class="col-sm-8">
							<?php echo HTML::selectChosen('region', $region_ddl, $sector, true, false, true, 1, ' style="min-width:270px;" '); ?>
						</div>
					</div>
					<div class="form-group">
						<label for="keywords" class="col-sm-4 control-label fieldLabel_compulsory">Keywords:</label>
						<div class="col-sm-8">
							<input type="text" class="form-control compulsory" name="keywords" id="keywords" value="<?php echo $keywords; ?>" maxlength="100" placeholder="e.g. Manchester, Retail, etc." />
						</div>
					</div>
					<div class="form-group">
						<div class="col-sm-12">
							<button class="pull-right btn btn-md btn-info"><i class="fa fa-search"></i> Search</button>
						</div>
					</div>
				</form>
			</div>
		</div>
	</div>

	<div class="row">

		<div class="col-sm-12 animated fadeInDown">
			<?php echo $this->viewVacanciesOptimized($link, $sector, $region, $keywords); ?>
		</div>

	</div>
</div>

<div class="footer">
	<table class="table">
		<tr>
			<td><img src="images/logos/siemens/top70.png" class="img-responsive" /></td>
			<td><img src="images/logos/ESF_logo_rgb_28mm.png" class="img-responsive" /></td>
			<td><img src="images/logos/top_employer.png" class="img-responsive" /></td>
			<td><img src="images/logos/SUNlogo.png" class="img-responsive" /></td>
		</tr>
	</table>
</div>

<div class="modal fade" id="applicationModal" role="dialog" data-backdrop="static" data-keyboard="false">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal">&times;</button>
				<h4 class="modal-title">Your Details</h4>
			</div>
			<div class="modal-body">
				<form class="form-horizontal" method="post" name="frmCandidateApply" id="frmCandidateApply" method="post" action="do.php?_action=application">
					<input type="hidden" name="vacancy_id" value="" />
					<input type="hidden" name="new_candidate" value="1" />
					<p>If you have registered with us previously, provide the following details and click on <span class="text-bold">Returning Candidate</span>.</p>
					<p>Alternatively, you can leave these fields blank and click <span class="text-bold">New Candidate</span>.</p>
					<div class="control-group">
						<label class="control-label" for ="firstname">First Name:</label>
						<input type="text" class="form-control compulsory required" id="firstname" name="firstname" value="">
					</div>
					<div class="control-group">
						<label class="control-label" for ="surname">Surname:</label>
						<input type="text" class="form-control compulsory required" id="surname" name="surname" value="">
					</div>
					<div class="control-group">
						<label class="control-label" for ="dob">Date of Birth:</label>
						<input type="text" class="form-control datepicker compulsory required" id="dob" name="dob" value="">
					</div>
					<div class="control-group">
						<label class="control-label" for ="postcode">Postcode:</label>
						<input type="text" class="form-control compulsory required" id="postcode" name="postcode" value="">
					</div>
				</form>
			</div>
			<div class="modal-footer">
				<button type="button" id="btnModalReturningCandidate" class="btn btn-default pull-left">Returning Candidate</button>
				<button type="button" id="btnModalNewCandidate" class="btn btn-primary">New Candidate</button>
			</div>
		</div>
	</div>
</div>


<script src="/assets/adminlte/plugins/jQuery/jquery-2.2.3.min.js"></script>
<script src="/assets/adminlte/plugins/bootstrap-validator/bootstrapValidator.js"></script>
<script src="/assets/adminlte/bootstrap/js/bootstrap.min.js"></script>
<script src="/assets/adminlte/plugins/jQueryUI/jquery-ui.min.js"></script>


<script>
	$(function(){

		$("button#btnModalNewCandidate").click(function(){
			$("#frmCandidateApply").validate().cancelSubmit = true;
			$("#frmCandidateApply").submit();
		});

		$("button#btnModalReturningCandidate").click(function(){
			if(!$("#frmCandidateApply").valid())
				return;

			$("#frmCandidateApply input[name=new_candidate]").val('0');

			$("#frmCandidateApply").submit();
		});

		$( ".datepicker" ).datepicker({
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

		$('input[name=dob]').datepicker("option", "yearRange", "-50:+1");
		$('input[name=dob]').datepicker("option", "defaultDate", "-18y");

		jQuery.validator.addMethod("dateUK",function(value, element) {
				return value == ''?true:value.match(/^(?=\d)(?:(?:31(?!.(?:0?[2469]|11))|(?:30|29)(?!.0?2)|29(?=.0?2.(?:(?:(?:1[6-9]|[2-9]\d)(?:0[48]|[2468][048]|[13579][26])|(?:(?:16|[2468][048]|[3579][26])00)))(?:\x20|$))|(?:2[0-8]|1\d|0?[1-9]))([-.\/])(?:1[012]|0?[1-9])\1(?:1[6-9]|[2-9]\d)\d\d(?:(?=\x20\d)\x20|$))?(((0?[1-9]|1[012])(:[0-5]\d){0,2}(\x20[AP]M))|([01]\d|2[0-3])(:[0-5]\d){1,2})?$/);
			}, "Please enter a date in the format dd/mm/yyyy."
		);
		jQuery.validator.addMethod("postcodeUK", function(value, element) {
			return this.optional(element) || /^[A-Z]{1,2}[0-9]{1,2} ?[0-9][A-Z]{2}$/i.test(value);
		}, "Please specify a valid Postcode");
		jQuery.validator.addClassRules('compulsory', {
			required: true
		});
		jQuery.validator.addClassRules('datepicker', {
			dateUK:true
		});
		$("#frmCandidateApply").validate({rules:{postcode:{postcodeUK:true}}});
	});

	function applyForVacancy(vacancy_id)
	{
		$("#frmCandidateApply input[name=vacancy_id]").val(vacancy_id);
		$('#applicationModal').modal('show');
	}
</script>

<!-- Hotjar Tracking Code for http://www.superdrug.com/ -->
<script>
    (function(h,o,t,j,a,r){
        h.hj=h.hj||function(){(h.hj.q=h.hj.q||[]).push(arguments)};
        h._hjSettings={hjid:136263,hjsv:6};
        a=o.getElementsByTagName('head')[0];
        r=o.createElement('script');r.async=1;
        r.src=t+h._hjSettings.hjid+j+h._hjSettings.hjsv;
        a.appendChild(r);
    })(window,document,'https://static.hotjar.com/c/hotjar-','.js?sv=');
</script>
</body>
</html>
