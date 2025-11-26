<?php /* @var $organisation Organisation */ ?>
<?php /* @var $location Location */ ?>

<!DOCTYPE html>
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<title><?php echo $location->id == ''?'Add Location':'Edit Location'; ?></title>
	<link rel="stylesheet" href="css/common.css" type="text/css"/>
	<link rel="stylesheet" href="/assets/adminlte/bootstrap/css/bootstrap.min.css">
	<link rel="stylesheet" href="/assets/adminlte/plugins/font-awesome/font-awesome.min.css">
	<link rel="stylesheet" href="/assets/adminlte/plugins/jQueryUI/jquery-ui.css">
	<link rel="stylesheet" href="/assets/adminlte/dist/css/AdminLTE.min.css">
	<link rel="stylesheet" href="/assets/adminlte/plugins/bootstrap-toggle/bootstrap-toggle.min.css">
	<link rel="stylesheet" href="/assets/adminlte/plugins/iCheck/all.css">

	<!--[if lt IE 9]>
	<script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
	<script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
	<![endif]-->


	<style>
		#postcode{text-transform:uppercase}
		.ui-datepicker .ui-datepicker-title select {
			color: #000;
		}
	</style>
</head>
<body>
<div class="row">
	<div class="col-lg-12">
		<div class="banner">
			<div class="Title" style="margin-left: 6px;"><?php echo $location->id == ''?'Add Location':'Edit Location'; ?></div>
			<div class="ButtonBar">
				<span class="btn btn-xs btn-default" onclick="window.location.href='<?php echo $_SESSION['bc']->getPrevious(); ?>';"><i class="fa fa-arrow-circle-o-left"></i> Cancel</span>
				<span class="btn btn-xs btn-default" onclick="save();"><i class="fa fa-save"></i> Save</span>
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

<div class="container-fluid">

	<div class="row">
		<div class="col-sm-7">
			<div class="callout">
				<span class="lead text-bold"><?php echo $organisation->legal_name; ?></span>
			</div>
			<form class="form-horizontal" name="frmLocation" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
				<input type="hidden" name="id" value="<?php echo $location->id; ?>" />
				<input type="hidden" name="organisations_id" value="<?php echo $location->organisations_id; ?>" />
				<input type="hidden" name="_action" value="save_location" />

				<div class="box box-solid box-primary">
					<div class="box-header with-border">
						<h2 class="box-title">Location Details</h2>
					</div>
					<div class="box-body">
						<div class="form-group">
							<label for="full_name" class="col-sm-4 control-label fieldLabel_compulsory">Location Title:</label>
							<div class="col-sm-8">
								<input type="text" class="form-control compulsory" name="full_name" id="full_name" value="<?php echo $location->full_name; ?>" maxlength="100" />
							</div>
						</div>
						<div class="form-group">
							<label for="is_legal_address" class="col-sm-4 control-label fieldLabel_compulsory small">Is this the main/legal address for this organisation?:</label>
							<div class="col-sm-8">
								<?php
								echo $location->is_legal_address == '1' ?
									'<input value="1" class="yes_no_toggle" type="checkbox" name="is_legal_address" data-toggle="toggle" checked="checked" data-toggle="toggle" data-on="Yes" data-off="No" data-onstyle="success" data-offstyle="danger" />':
									'<input value="0" class="yes_no_toggle" type="checkbox" name="is_legal_address" data-toggle="toggle" data-on="Yes" data-off="No" data-onstyle="success" data-offstyle="danger" />';
								?>
							</div>
						</div>
						<div class="form-group">
							<label for="address_line_1" class="col-sm-4 control-label fieldLabel_compulsory">Building No./Name & Street:</label>
							<div class="col-sm-8">
								<input type="text" class="form-control compulsory" name="address_line_1" id="address_line_1" value="<?php echo $location->address_line_1; ?>" maxlength="100" />
							</div>
						</div>
						<div class="form-group">
							<label for="address_line_2" class="col-sm-4 control-label fieldLabel_optional">Suburb / Village:</label>
							<div class="col-sm-8">
								<input type="text" class="form-control optional" name="address_line_2" id="address_line_2" value="<?php echo $location->address_line_2; ?>" maxlength="100" />
							</div>
						</div>
						<div class="form-group">
							<label for="address_line_3" class="col-sm-4 control-label fieldLabel_optional">Town / City:</label>
							<div class="col-sm-8">
								<input type="text" class="form-control optional" name="address_line_3" id="address_line_3" value="<?php echo $location->address_line_3; ?>" maxlength="100" />
							</div>
						</div>
						<div class="form-group">
							<label for="address_line_4" class="col-sm-4 control-label fieldLabel_optional">County:</label>
							<div class="col-sm-8">
								<input type="text" class="form-control optional" name="address_line_4" id="address_line_4" value="<?php echo $location->address_line_4; ?>" maxlength="100" />
							</div>
						</div>
						<div class="form-group">
							<label for="postcode" class="col-sm-4 control-label fieldLabel_compulsory">Postcode:</label>
							<div class="col-sm-8">
								<input type="text" class="form-control compulsory" name="postcode" id="postcode" value="<?php echo $location->postcode; ?>" onkeyup="this.value = this.value.toUpperCase();" maxlength="10" />
							</div>
						</div>
						<div class="form-group">
							<label for="telephone" class="col-sm-4 control-label fieldLabel_optional">Telephone:</label>
							<div class="col-sm-8">
								<input type="text" class="form-control optional" name="telephone" id="telephone" value="<?php echo $location->telephone; ?>" maxlength="50" />
							</div>
						</div>
						<div class="form-group">
							<label for="fax" class="col-sm-4 control-label fieldLabel_optional">Fax:</label>
							<div class="col-sm-8">
								<input type="text" class="form-control optional" name="fax" id="fax" value="<?php echo $location->fax; ?>" maxlength="50" />
							</div>
						</div>
						<hr>
						<div class="form-group">
							<label for="contact_name" class="col-sm-4 control-label fieldLabel_optional">Main Contact Name:</label>
							<div class="col-sm-8">
								<input type="text" class="form-control optional" name="contact_name" id="contact_name" value="<?php echo $location->contact_name; ?>" maxlength="150" />
							</div>
						</div>
						<div class="form-group">
							<label for="contact_mobile" class="col-sm-4 control-label fieldLabel_optional">Main Contact Mobile Number:</label>
							<div class="col-sm-8">
								<input type="text" class="form-control optional" name="contact_mobile" id="contact_mobile" value="<?php echo $location->contact_mobile; ?>" maxlength="50" />
							</div>
						</div>
						<div class="form-group">
							<label for="contact_telephone" class="col-sm-4 control-label fieldLabel_optional">Main Contact Telephone Number:</label>
							<div class="col-sm-8">
								<input type="text" class="form-control optional" name="contact_telephone" id="contact_telephone" value="<?php echo $location->contact_telephone; ?>" maxlength="50" />
							</div>
						</div>
						<div class="form-group">
							<label for="contact_email" class="col-sm-4 control-label fieldLabel_optional">Main Contact Email:</label>
							<div class="col-sm-8">
								<input type="text" class="form-control optional" name="contact_email" id="contact_email" value="<?php echo $location->contact_email; ?>" maxlength="150" />
							</div>
						</div>
					</div>
					<div class="box-footer">
						<span class="btn btn-sm btn-primary btn-block" onclick="save();"><i class="fa fa-save"></i> Save Information</span>
					</div>
				</div>

			</form>
		</div>
		<div class="col-sm-5">
			<div class="box box-info">
				<div class="box-header with-border">
					<h2 class="box-title">Other Locations</h2>
				</div>
				<div class="box-body">
					<?php echo $this->renderOtherLocations($link, $location); ?>
				</div>
			</div>
		</div>
	</div>
</div>

<br>

<script src="/assets/adminlte/plugins/jQuery/jquery-2.2.3.min.js"></script>
<script src="/assets/adminlte/plugins/jQueryUI/jquery-ui.min.js"></script>
<script src="/assets/adminlte/bootstrap/js/bootstrap.min.js"></script>
<script src="/assets/adminlte/plugins/chosen/chosen.jquery.js"></script>
<script src="js/common.js" type="text/javascript"></script>
<script src="/assets/adminlte/plugins/bootstrap-toggle/bootstrap-toggle.min.js"></script>
<script src="/assets/adminlte/plugins/iCheck/icheck.min.js"></script>

<script language="JavaScript">

	function save()
	{
		var myForm = document.forms["frmLocation"];
		if(!validateForm(myForm))
		{
			return;
		}

		if(!validatePostcode(myForm.postcode.value))
		{
			alert('Please enter valid postcode.');
			myForm.postcode.focus();
			return;
		}

		myForm.submit();
	}

	$(function(){
		$('input[type=radio]').iCheck({
			radioClass: 'iradio_square-green'
		});
	});
</script>

</body>
</html>