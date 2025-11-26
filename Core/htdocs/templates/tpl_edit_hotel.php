<?php /* @var $hotel Employer */ ?>
<?php /* @var $mainLocation Location */ ?>

<!DOCTYPE html>
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<title><?php echo $hotel->id == ''?'Create Hotel':'Edit Hotel'; ?></title>
	<link rel="stylesheet" href="module_tracking/css/common.css" type="text/css"/>
	<link rel="stylesheet" href="/assets/adminlte/bootstrap/css/bootstrap.min.css">
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.5.0/css/font-awesome.min.css">
	<link rel="stylesheet" href="/assets/adminlte/plugins/jQueryUI/jquery-ui.css">
	<link rel="stylesheet" href="/assets/adminlte/dist/css/AdminLTE.min.css">
	<link href="https://gitcdn.github.io/bootstrap-toggle/2.2.2/css/bootstrap-toggle.min.css" rel="stylesheet">
	<link rel="stylesheet" href="/assets/adminlte/plugins/iCheck/all.css">

	<!--[if lt IE 9]>
	<script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
	<script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
	<![endif]-->


	<style>
		.ui-datepicker .ui-datepicker-title select {
			color: #000;
		}
	</style>
</head>
<body>
<div class="row">
	<div class="col-lg-12">
		<div class="banner">
			<div class="Title" style="margin-left: 6px;"><?php echo $hotel->id == ''?'Create Hotel':'Edit Hotel'; ?></div>
			<div class="ButtonBar">
				<span class="btn btn-sm btn-default" onclick="window.location.href='<?php echo $_SESSION['bc']->getPrevious(); ?>';"><i class="fa fa-arrow-circle-o-left"></i> Cancel</span>
				<span class="btn btn-sm btn-default" onclick="save();"><i class="fa fa-save"></i> Save</span>
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
<form class="form-horizontal" name="frmHotel" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
<input type="hidden" name="id" value="<?php echo $hotel->id; ?>" />
<input type="hidden" name="main_location_id" value="<?php echo $mainLocation->id; ?>" />
<input type="hidden" name="_action" value="save_hotel" />
<input type="hidden" name="organisation_type" value="<?php echo Organisation::TYPE_HOTEL; ?>" />
<div class="row">
<div class="col-sm-7">
<div class="box box-solid box-primary">
	<div class="box-header with-border">
		<h2 class="box-title">Basic Details</h2>
	</div>
	<div class="box-body">
		<div class="form-group">
			<label for="active" class="col-sm-4 control-label fieldLabel_compulsory">Active:</label>
			<div class="col-sm-8">
				<?php
				echo $hotel->active == '1' ?
					'<input value="1" class="yes_no_toggle" type="checkbox" name="active" data-toggle="toggle" checked="checked" data-toggle="toggle" data-on="Yes" data-off="No" data-onstyle="success" data-offstyle="danger" />':
					'<input value="1" class="yes_no_toggle" type="checkbox" name="active" data-toggle="toggle" data-on="Yes" data-off="No" data-onstyle="success" data-offstyle="danger" />';
				?>
			</div>
		</div>
		<div class="form-group">
			<label for="legal_name" class="col-sm-4 control-label fieldLabel_compulsory">Legal Name:</label>
			<div class="col-sm-8">
				<input type="text" class="form-control compulsory" name="legal_name" id="legal_name" value="<?php echo $hotel->legal_name; ?>" maxlength="200" />
			</div>
		</div>
		<div class="form-group">
			<label for="trading_name" class="col-sm-4 control-label fieldLabel_compulsory">Trading Name:</label>
			<div class="col-sm-8">
				<input type="text" class="form-control compulsory" name="trading_name" id="trading_name" value="<?php echo $hotel->trading_name; ?>" maxlength="200" />
			</div>
		</div>
		<div class="form-group">
			<label for="short_name" class="col-sm-4 control-label fieldLabel_compulsory">Abbreviation:</label>
			<div class="col-sm-8">
				<input type="text" class="form-control compulsory" name="short_name" id="short_name" value="<?php echo $hotel->short_name; ?>" maxlength="20" />
			</div>
		</div>
		<div class="form-group">
			<label for="edrs" class="col-sm-4 control-label fieldLabel_optional">EDRS:</label>
			<div class="col-sm-8">
				<input type="text" class="form-control optional" name="edrs" id="edrs" value="<?php echo $hotel->edrs; ?>" maxlength="10" onkeypress="return numbersonly(this);" />
			</div>
		</div>
		<div class="form-group">
			<label for="company_number" class="col-sm-4 control-label fieldLabel_optional">Company Number:</label>
			<div class="col-sm-8">
				<input type="text" class="form-control optional" name="company_number" id="company_number" value="<?php echo $hotel->company_number; ?>" maxlength="10" />
			</div>
		</div>
		<div class="form-group">
			<label for="vat_number" class="col-sm-4 control-label fieldLabel_optional">VAT Number:</label>
			<div class="col-sm-8">
				<input type="text" class="form-control optional" name="vat_number" id="vat_number" value="<?php echo $hotel->vat_number; ?>" maxlength="10" />
			</div>
		</div>
		<div class="form-group">
			<label for="retailer_code" class="col-sm-4 control-label fieldLabel_optional">Retailer Code:</label>
			<div class="col-sm-8">
				<input type="text" class="form-control optional" name="retailer_code" id="retailer_code" value="<?php echo $hotel->retailer_code; ?>" maxlength="10" />
			</div>
		</div>
		<div class="form-group">
			<label for="employer_code" class="col-sm-4 control-label fieldLabel_optional">Hotel Code:</label>
			<div class="col-sm-8">
				<input type="text" class="form-control optional" name="employer_code" id="employer_code" value="<?php echo $hotel->employer_code; ?>" maxlength="10" />
			</div>
		</div>
	</div>
</div>
<div class="box box-solid box-primary">
	<div class="box-header with-border">
		<h2 class="box-title">Additional Details</h2>
	</div>
	<div class="box-body">
		<div class="form-group">
			<label for="company_rating" class="col-sm-4 control-label fieldLabel_optional">Rating:</label>
			<div class="col-sm-8">
				<table class="table table-bordered text-center">
					<tr>
						<td><i class="fa fa-trophy fa-2x" style="color: #ffd700;"></i></td>
						<td><i class="fa fa-trophy fa-2x" style="color: silver;"></i></td>
						<td><i class="fa fa-trophy fa-2x" style="color: #cd7f32;"></i></td>
					</tr>
					<tr>
						<td><input type="radio" name="company_rating" <?php echo $hotel->company_rating == 'G' ? 'checked="checked"' : ''; ?> value="G"></td>
						<td><input type="radio" name="company_rating" <?php echo $hotel->company_rating == 'S' ? 'checked="checked"' : ''; ?> value="S"></td>
						<td><input type="radio" name="company_rating" <?php echo $hotel->company_rating == 'B' ? 'checked="checked"' : ''; ?> value="B"></td>
					</tr>
				</table>
			</div>
		</div>
		<div class="form-group">
			<label for="sector" class="col-sm-4 control-label fieldLabel_optional">Sector:</label>
			<div class="col-sm-8">
				<?php echo HTML::selectChosen('sector', $ddlSectors, $hotel->sector, true); ?>
			</div>
		</div>
		<div class="form-group">
			<label for="manufacturer" class="col-sm-4 control-label fieldLabel_optional">Group Hotel:</label>
			<div class="col-sm-8">
				<?php echo HTML::selectChosen('manufacturer', $ddlGroupEmployers, $hotel->manufacturer, true); ?>
			</div>
		</div>
		<div class="form-group">
			<label for="region" class="col-sm-4 control-label fieldLabel_optional">Sales Region:</label>
			<div class="col-sm-8">
				<?php echo HTML::selectChosen('region', $ddlRegions, $hotel->region, true); ?>
			</div>
		</div>
		<div class="form-group">
			<label for="code" class="col-sm-4 control-label fieldLabel_optional">Size:</label>
			<div class="col-sm-8">
				<?php echo HTML::selectChosen('code', $ddlCodes, $hotel->code, true); ?>
			</div>
		</div>
		<div class="form-group">
			<label for="site_employees" class="col-sm-4 control-label fieldLabel_optional">On-site Employees:</label>
			<div class="col-sm-8">
				<input type="text" class="form-control optional" name="site_employees" id="site_employees" value="<?php echo $hotel->site_employees; ?>" maxlength="5" />
			</div>
		</div>
		<div class="form-group">
			<label for="creator" class="col-sm-4 control-label fieldLabel_optional">Account Manager:</label>
			<div class="col-sm-8">
				<?php echo HTML::selectChosen('creator', $account_manager_dropdown, $hotel->creator, true); ?>
			</div>
		</div>
		<div class="form-group">
			<label for="lead_referral" class="col-sm-4 control-label fieldLabel_optional">Lead Referral:</label>
			<div class="col-sm-8">
				<input type="text" class="form-control optional" name="lead_referral" id="lead_referral" value="<?php echo $hotel->lead_referral; ?>" maxlength="50" />
			</div>
		</div>
		<div class="form-group">
			<label for="parent_org" class="col-sm-4 control-label fieldLabel_optional">Delivery Partner:</label>
			<div class="col-sm-8">
				<?php echo HTML::selectChosen('parent_org', $ddlDeliveryPartners, $hotel->parent_org, true); ?>
			</div>
		</div>
		<div class="form-group">
			<label for="health_safety" class="col-sm-4 control-label fieldLabel_optional">Health & Safety:</label>
			<div class="col-sm-8">
				<?php
				echo $hotel->health_safety == '1' ?
					'<input value="1" class="yes_no_toggle" type="checkbox" name="health_safety" data-toggle="toggle" checked="checked" data-toggle="toggle" data-on="Yes" data-off="No" data-onstyle="success" data-offstyle="danger" />':
					'<input value="1" class="yes_no_toggle" type="checkbox" name="health_safety" data-toggle="toggle" data-on="Yes" data-off="No" data-onstyle="success" data-offstyle="danger" />';
				?>
			</div>
		</div>
		<div class="form-group">
			<label for="due_diligence" class="col-sm-4 control-label fieldLabel_optional">Due Diligence:</label>
			<div class="col-sm-8">
				<?php
				echo $hotel->due_diligence == '1' ?
					'<input value="1" class="yes_no_toggle" type="checkbox" name="due_diligence" data-toggle="toggle" checked="checked" data-toggle="toggle" data-on="Yes" data-off="No" data-onstyle="success" data-offstyle="danger" />':
					'<input value="1" class="yes_no_toggle" type="checkbox" name="due_diligence" data-toggle="toggle" data-on="Yes" data-off="No" data-onstyle="success" data-offstyle="danger" />';
				?>
			</div>
		</div>
		<div class="form-group">
			<label for="ono" class="col-sm-4 control-label fieldLabel_optional">ONA:</label>
			<div class="col-sm-8">
				<?php
				echo $hotel->ono == '1' ?
					'<input value="1" class="yes_no_toggle" type="checkbox" name="ono" data-toggle="toggle" checked="checked" data-toggle="toggle" data-on="Yes" data-off="No" data-onstyle="success" data-offstyle="danger" />':
					'<input value="1" class="yes_no_toggle" type="checkbox" name="ono" data-toggle="toggle" data-on="Yes" data-off="No" data-onstyle="success" data-offstyle="danger" />';
				?>
			</div>
		</div>
		<div class="form-group">
			<label for="levy_employer" class="col-sm-4 control-label fieldLabel_optional">Levy Hotel:</label>
			<div class="col-sm-8">
				<?php
				echo $hotel->levy_employer == '1' ?
					'<input value="1" class="yes_no_toggle" type="checkbox" name="levy_employer" data-toggle="toggle" checked="checked" data-toggle="toggle" data-on="Yes" data-off="No" data-onstyle="success" data-offstyle="danger" />':
					'<input value="1" class="yes_no_toggle" type="checkbox" name="levy_employer" data-toggle="toggle" data-on="Yes" data-off="No" data-onstyle="success" data-offstyle="danger" />';
				?>
			</div>
		</div>
		<div class="form-group">
			<label for="levy" class="col-sm-4 control-label fieldLabel_optional">Levy Amount:</label>
			<div class="col-sm-8">
				<input type="text" class="form-control optional" name="levy" id="levy" value="<?php echo $hotel->levy; ?>" maxlength="10" onkeypress="return numbersonly(this);" />
			</div>
		</div>
		<div class="form-group">
			<label for="url" class="col-sm-4 control-label fieldLabel_optional">URL:</label>
			<div class="col-sm-8">
				<input type="text" class="form-control optional" name="url" id="url" value="<?php echo $hotel->url; ?>" maxlength="250" />
			</div>
		</div>
		<?php if(in_array(DB_NAME, ["am_sd_demo", "am_superdrug"])) { ?>
		<div class="form-group">
			<label for="salary_rate" class="col-sm-4 control-label fieldLabel_optional">Salary Rate:</label>
			<div class="col-sm-8">
				<?php echo HTML::selectChosen('salary_rate', $salary_rate_options, $hotel->salary_rate, false, false); ?>
			</div>
		</div>
		<?php } ?>
		<?php if(SOURCE_LOCAL || in_array(DB_NAME, ["am_baltic_demo", "am_baltic"])) { ?>
		<div class="form-group">
			<label for="source" class="col-sm-4 control-label fieldLabel_optional">Source:</label>
			<div class="col-sm-8">
				<?php
				$source_options = DAO::getResultset($link, "SELECT id, description FROM lookup_prospect_source ORDER BY description");
				echo HTML::selectChosen('source', $source_options, $hotel->source, true);
				?>
			</div>
		</div>
		<div class="form-group">
			<label for="not_linked" class="col-sm-4 control-label fieldLabel_optional">No longer working with this hotel:</label>
			<div class="col-sm-8">
				<?php
				echo $hotel->not_linked == '1' ?
					'<input value="1" class="yes_no_toggle" type="checkbox" name="not_linked" data-toggle="toggle" checked="checked" data-toggle="toggle" data-on="Yes" data-off="No" data-onstyle="success" data-offstyle="danger" />':
					'<input value="1" class="yes_no_toggle" type="checkbox" name="not_linked" data-toggle="toggle" data-on="Yes" data-off="No" data-onstyle="success" data-offstyle="danger" />';
				?>
			</div>
		</div>
		<div class="form-group">
			<label for="not_linked_comments" class="col-sm-4 control-label fieldLabel_optional">Comments:</label>
			<div class="col-sm-8">
				<textarea class="form-control" name="not_linked_comments" id="not_linked_comments" cols="30" rows="10"><?php echo nl2br((string)$hotel->not_linked_comments); ?></textarea>
			</div>
		</div>
		<?php } ?>
	</div>
</div>
</div>
<div class="col-sm-5">
	<div class="box box-solid box-primary">
		<div class="box-header with-border">
			<h2 class="box-title">Main Location Details</h2>
		</div>
		<div class="box-body">
			<div class="form-group">
				<label for="full_name" class="col-sm-4 control-label fieldLabel_compulsory">Title:</label>
				<div class="col-sm-8">
					<input type="text" class="form-control compulsory" name="full_name" id="full_name" value="<?php echo $mainLocation->full_name == '' ? 'Main Site' : $mainLocation->full_name; ?>" maxlength="50" />
				</div>
			</div>
			<div class="form-group">
				<label for="address_line_1" class="col-sm-4 control-label fieldLabel_compulsory">Address Line 1:</label>
				<div class="col-sm-8">
					<input type="text" class="form-control compulsory" name="address_line_1" id="address_line_1" value="<?php echo $mainLocation->address_line_1; ?>" maxlength="100" />
				</div>
			</div>
			<div class="form-group">
				<label for="company_number" class="col-sm-4 control-label fieldLabel_optional">Address Line 2:</label>
				<div class="col-sm-8">
					<input type="text" class="form-control" name="address_line_2" id="address_line_2" value="<?php echo $mainLocation->address_line_2; ?>" maxlength="100" />
				</div>
			</div>
			<div class="form-group">
				<label for="company_number" class="col-sm-4 control-label fieldLabel_optional">Address Line 3:</label>
				<div class="col-sm-8">
					<input type="text" class="form-control" name="address_line_3" id="address_line_3" value="<?php echo $mainLocation->address_line_3; ?>" maxlength="100" />
				</div>
			</div>
			<div class="form-group">
				<label for="company_number" class="col-sm-4 control-label fieldLabel_optional">Address Line 4:</label>
				<div class="col-sm-8">
					<input type="text" class="form-control" name="address_line_4" id="address_line_4" value="<?php echo $mainLocation->address_line_4; ?>" maxlength="100" />
				</div>
			</div>
			<div class="form-group">
				<label for="company_number" class="col-sm-4 control-label fieldLabel_compulsory">Postcode:</label>
				<div class="col-sm-8">
					<input type="text" class="form-control compulsory" name="postcode" id="postcode" value="<?php echo $mainLocation->postcode; ?>" maxlength="10" />
				</div>
			</div>
			<div class="form-group">
				<label for="company_number" class="col-sm-4 control-label fieldLabel_optional">Telephone:</label>
				<div class="col-sm-8">
					<input type="text" class="form-control" name="telephone" id="telephone" value="<?php echo $mainLocation->telephone; ?>" maxlength="15" />
				</div>
			</div>
			<div class="form-group">
				<label for="company_number" class="col-sm-4 control-label fieldLabel_optional">Fax:</label>
				<div class="col-sm-8">
					<input type="text" class="form-control" name="fax" id="fax" value="<?php echo $mainLocation->fax; ?>" maxlength="15" />
				</div>
			</div>
			<div class="callout callout-default">
				<h5 class="text-bold">Primary Contact Person Details</h5>
				<div class="form-group">
					<label for="company_number" class="col-sm-4 control-label fieldLabel_optional">Name:</label>
					<div class="col-sm-8">
						<input type="text" class="form-control" name="contact_name" id="contact_name" value="<?php echo $mainLocation->contact_name; ?>" maxlength="50" />
					</div>
				</div>
				<div class="form-group">
					<label for="company_number" class="col-sm-4 control-label fieldLabel_optional">Mobile:</label>
					<div class="col-sm-8">
						<input type="text" class="form-control" name="contact_mobile" id="contact_mobile" value="<?php echo $mainLocation->contact_mobile; ?>" maxlength="15" />
					</div>
				</div>
				<div class="form-group">
					<label for="company_number" class="col-sm-4 control-label fieldLabel_optional">Telephone:</label>
					<div class="col-sm-8">
						<input type="text" class="form-control" name="contact_telephone" id="contact_telephone" value="<?php echo $mainLocation->contact_telephone; ?>" maxlength="15" />
					</div>
				</div>
				<div class="form-group">
					<label for="company_number" class="col-sm-4 control-label fieldLabel_optional">Email:</label>
					<div class="col-sm-8">
						<input type="text" class="form-control" name="contact_email" id="contact_email" value="<?php echo $mainLocation->contact_email; ?>" maxlength="50" />
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
</div>
</form>
</div>

<br>

<script src="/assets/adminlte/plugins/jQuery/jquery-2.2.3.min.js"></script>
<script src="/assets/adminlte/plugins/jQueryUI/jquery-ui.min.js"></script>
<script src="/assets/adminlte/bootstrap/js/bootstrap.min.js"></script>
<script src="/assets/adminlte/plugins/chosen/chosen.jquery.js"></script>
<script src="/common.js" type="text/javascript"></script>
<script src="https://gitcdn.github.io/bootstrap-toggle/2.2.2/js/bootstrap-toggle.min.js"></script>
<script src="/assets/adminlte/plugins/iCheck/icheck.min.js"></script>

<script language="JavaScript">

	function save()
	{
		var myForm = document.forms["frmHotel"];
		if(!validateForm(myForm))
		{
			return;
		}

		if(!validatePostcode(myForm.postcode.value))
		{
			alert('Please enter valid postcode.');
			myForm.postcode.focus();
		}

		if(myForm.contact_email.value != '' && !validateEmail(myForm.contact_email.value))
		{
			alert('Please enter valid email address.');
			myForm.contact_email.focus();
		}

		var client = ajaxRequest('do.php?_action=save_hotel&subaction=validateEDRS&edrs=' + encodeURIComponent(myForm.edrs.value));

		if(client)
		{
			if(client.responseText == 0)
			{
				alert('Invalid EDRS');
				myForm.edrs.focus();
				return;
			}
			else
				return myForm.submit();
		}
		else
		{
			alert(client);
		}
	}

	$(function(){
		$('input[type=radio]').iCheck({
			radioClass: 'iradio_square-green'
		});
	});
</script>

</body>
</html>