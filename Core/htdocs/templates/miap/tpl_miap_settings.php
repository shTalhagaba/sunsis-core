<!DOCTYPE html>
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<title>MIAP Settings</title>
	<link rel="stylesheet" href="module_tracking/css/common.css?n=<?php echo time(); ?>" type="text/css"/>
	<link rel="stylesheet" href="/assets/adminlte/bootstrap/css/bootstrap.min.css">
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.5.0/css/font-awesome.min.css">
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/ionicons/2.0.1/css/ionicons.min.css">
	<link rel="stylesheet" href="/assets/adminlte/plugins/jQueryUI/jquery-ui.css">
	<link rel="stylesheet" href="/assets/adminlte/dist/css/AdminLTE.min.css">
	<link rel="stylesheet" href="/assets/adminlte/plugins/bootstrap-validator/bootstrapValidator.css">

	<!--[if lt IE 9]>
	<script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
	<script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
	<![endif]-->

</head>
<body>
<div class="row">
	<div class="col-lg-12">
		<div class="banner">
			<div class="Title" style="margin-left: 6px;">MIAP: Settings</div>
			<div class="ButtonBar">
				<span class="btn btn-sm btn-default" onclick="window.location.href='<?php echo $_SESSION['bc']->getPrevious(); ?>';"><i class="fa fa-arrow-circle-o-left"></i> Cancel</span>
			</div>
			<div class="ActionIconBar">

			</div>
		</div>

	</div>
</div>
<div class="row">
	<div class="col-lg-12">
		<?php $_SESSION['bc']->render($link); ?>
	</div>
</div>
<br>

<div class="row">
	<div class="col-lg-12">
		<div class="callout callout-info">
			<i class="fa fa-info-circle"></i> This page provides access to settings and options required to enable integration with MIAP/LRS web services.
		</div>
	</div>
</div>

<div class="row">
	<div class="col-lg-6">
		<form class="form-horizontal" name="frmSettings" id="frmSettings" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post" enctype="multipart/form-data">
			<input type="hidden" name="_action" value="miap_settings" />
			<input type="hidden" name="subaction" value="save" />
			<div class="box-header with-border"><h2 class="box-title">MIAP/LRS Connection Parameters</h2></div>
			<div class="box-body">
				<div class="form-group">
					<label for="miap_soap_enabled" class="col-sm-4 control-label fieldLabel_compulsory">Enable:</label>
					<div class="col-sm-8">
						<?php echo HTML::selectChosen('miap_soap_enabled', $ddlYesNo, SystemConfig::getEntityValue($link, "miap.soap.enabled"), false); ?>
					</div>
				</div>
				<div class="form-group">
					<label for="miap_soap_password" class="col-sm-4 control-label fieldLabel_compulsory">Password:</label>
					<div class="col-sm-8">
						<input type="text" class="form-control" name="miap_soap_password" id="miap_soap_password" value="<?php echo SystemConfig::getEntityValue($link, "miap.soap.password"); ?>" maxlength="16" />
					</div>
				</div>
				<div class="form-group">
					<label for="miap_soap_ukprn" class="col-sm-4 control-label fieldLabel_compulsory">UKPRN:</label>
					<div class="col-sm-8">
						<input type="text" class="form-control" name="miap_soap_ukprn" id="miap_soap_ukprn" value="<?php echo SystemConfig::getEntityValue($link, "miap.soap.ukprn"); ?>" maxlength="8" />
					</div>
				</div>
				<div class="form-group">
					<label for="miap_soap_wsdl_local_cert" class="col-sm-4 control-label fieldLabel_compulsory">Certificate (.pem file):</label>
					<div class="col-sm-8">
						<p><?php if($certificate != '') echo  $certificate; ?></p>
						<input type="file" class="form-control optional" name="miap_soap_wsdl_local_cert" id="miap_soap_wsdl_local_cert" />
					</div>
				</div>
			</div>
			<div class="box-footer">
				<button type="button" class="btn btn-primary pull-right" onclick="saveFrmSettings(); "><i class="fa fa-save"></i> Save</button>
			</div>
		</form>
	</div>
</div>
<br>

<script src="/assets/adminlte/plugins/jQuery/jquery-2.2.3.min.js"></script>
<script src="/assets/adminlte/plugins/jQueryUI/jquery-ui.min.js"></script>
<script src="/assets/adminlte/bootstrap/js/bootstrap.min.js"></script>
<script src="/assets/adminlte/dist/js/app.min.js"></script>
<script src="/common.js?n=<?php echo time(); ?>" type="text/javascript"></script>
<script src="/assets/adminlte/plugins/bootstrap-validator/bootstrapValidator.js"></script>
<script src="https://cdn.jsdelivr.net/jquery.validation/1.16.0/additional-methods.min.js"></script>

<script language="JavaScript">
	function toggleFields()
	{
		$('#miap_soap_password').attr('disabled', false);
		$('#miap_soap_ukprn').attr('disabled', false);
		$('#miap_soap_wsdl_local_cert').attr('disabled', false);

		if($('#miap_soap_enabled').val() == 0)
		{
			$('#miap_soap_password').attr('disabled', true);
			$('#miap_soap_ukprn').attr('disabled', true);
			$('#miap_soap_wsdl_local_cert').attr('disabled', true);
		}
	}
	$('#miap_soap_enabled').change(function() {
		toggleFields();
	});

	$(function(){

		toggleFields();

		$('#frmSettings').validate({
			rules: {
				"miap_soap_password": {
					required:function(element){
						return $("#miap_soap_enabled").val() == '1';
					},
					minlength: 16
				},
				"miap_soap_ukprn": {
					required:function(element){
						return $("#miap_soap_enabled").val() == '1';
					},
					minlength: 6,
					digits: true
				},
				"miap_soap_wsdl_local_cert": {
					required:function(element){
						return $("#miap_soap_enabled").val() == '1';
					},
					extension: "pem"
				}
			},
			highlight: function (element) {
				$(element).closest('.control-group').removeClass('success').addClass('error');
			},
			success: function (element) {
				element.text('OK!').addClass('success')
					.closest('.control-group').removeClass('error').addClass('success');
			}
		});
	});

	function saveFrmSettings()
	{

		if(!$("#frmSettings").valid())
			return;

		var myForm = document.forms['frmSettings'];
		myForm.submit();
	}
</script>

</body>
</html>