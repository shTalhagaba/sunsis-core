
<!DOCTYPE html>
<head xmlns="http://www.w3.org/1999/html">
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<title><?php echo $epa_assessor->id == '' ? 'Add ' : 'Edit ';?>EPA Assessor</title>
	<link rel="stylesheet" href="css/common.css" type="text/css"/>
	<link rel="stylesheet" href="/assets/adminlte/bootstrap/css/bootstrap.min.css">
	<link rel="stylesheet" href="/assets/adminlte/plugins/font-awesome/font-awesome.min.css">
	<link rel="stylesheet" href="/assets/adminlte/plugins/jQueryUI/jquery-ui.css">
	<link rel="stylesheet" href="/assets/adminlte/dist/css/AdminLTE.min.css">

	<!--[if lt IE 9]>
	<script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
	<script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
	<![endif]-->

	<style>
		.disabled {
			pointer-events: none;
			opacity: 0.4;
		}
	</style>

</head>
<body>
<div class="row">
	<div class="col-lg-12">
		<div class="banner">
			<div class="Title" style="margin-left: 6px;"><?php echo $epa_assessor->id == '' ? 'Add ' : 'Edit ';?>EPA Assessor</div>
			<div class="ButtonBar">
				<span class="btn btn-sm btn-default"
				      onclick="window.location.href='<?php echo $_SESSION['bc']->getPrevious(); ?>';"><i
					class="fa fa-arrow-circle-o-left"></i> Cancel</span>
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

	<div class="row">
		<div class="col-sm-4">
			<div class="box box-primary">
				<div class="box-header with-border">
					<h5 class="text-bold lead"><?php echo $EPA_Org->EPA_ORG_ID . ' / ' . $EPA_Org->EP_Assessment_Organisations; ?></h5>
					<label class="label label-info"><?php echo $EPA_Org->Organisation_type; ?></label>
				</div>
				<div class="box-body">
					<span class="text-bold">Contact:</span><br>
					<i class="fa fa-user"></i> <?php echo $EPA_Org->Contact_Name; ?><br>
					<?php echo $EPA_Org->Contact_address1; ?><br>
					<?php echo $EPA_Org->Contact_address2; ?><br>
					<?php echo $EPA_Org->Contact_address3; ?><br>
					<?php echo $EPA_Org->Contact_address4; ?><br>
					<i class="fa fa-map-marker"></i> <a target="_blank" href="https://www.google.co.uk/maps?f=q&hl=en&q=<?php echo urlencode($EPA_Org->Postcode); ?>"> <?php echo $EPA_Org->Postcode; ?></a><br>
					<i class="fa fa-phone"></i> <?php echo $EPA_Org->Contact_number; ?><br>
					<i class="fa fa-envelope"></i> <a href="mailto:<?php echo $EPA_Org->Contact_email; ?>"><?php echo $EPA_Org->Contact_email; ?></a><br>
					<i class="fa fa-external-link"></i> <a target="_blank" href="<?php echo $EPA_Org->Link_to_website; ?>"><?php echo $EPA_Org->Link_to_website; ?></a><br>
					<hr>
					<span class="text-bold">Delivery Areas:</span><br>
					<?php echo $EPA_Org->Delivery_Area_1 != '' ? $EPA_Org->Delivery_Area_1 . '<br>' : ''; ?>
					<?php echo $EPA_Org->Delivery_Area_2 != '' ? $EPA_Org->Delivery_Area_2 . '<br>' : ''; ?>
					<?php echo $EPA_Org->Delivery_Area_3 != '' ? $EPA_Org->Delivery_Area_2 . '<br>' : ''; ?>
					<?php echo $EPA_Org->Delivery_Area_4 != '' ? $EPA_Org->Delivery_Area_4 . '<br>' : ''; ?>
				</div>
			</div>
		</div>
		<div class="col-sm-8">
			<div class="box box-default">
				<div class="box-header with-border">
					<div class="box-title">EPA Assessor Details</div>
				</div>
				<div class="box-body">
					<form autocomplete="off" name="frmEPAOrgAssessor" class="form-horizontal"  action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
						<input type="hidden" name="_action" value="edit_epa_org_assessor">
						<input type="hidden" name="subaction" value="save_epa_org_assessor">
						<input type="hidden" name="id" value="<?php echo $epa_assessor->id; ?>">
						<input type="hidden" name="EPA_Org_ID" value="<?php echo $EPA_Org->EPA_ORG_ID; ?>">

						<div class="form-group">
							<label for="title" class="col-sm-4 control-label fieldLabel_optional">Title:</label>
							<div class="col-sm-8">
								<input type="text" class="form-control optional" name="title" id="title" value="<?php echo $epa_assessor->title; ?>" placeholder="Mr./Mrs./Dr. " maxlength="10" />
							</div>
						</div>
						<div class="form-group">
							<label for="firstnames" class="col-sm-4 control-label fieldLabel_compulsory">First Name(s):</label>
							<div class="col-sm-8">
								<input type="text" class="form-control compulsory" name="firstnames" id="firstnames" value="<?php echo $epa_assessor->firstnames; ?>" maxlength="70" />
							</div>
						</div>
						<div class="form-group">
							<label for="surname" class="col-sm-4 control-label fieldLabel_compulsory">Surname:</label>
							<div class="col-sm-8">
								<input type="text" class="form-control compulsory" name="surname" id="surname" value="<?php echo $epa_assessor->surname; ?>" maxlength="70" />
							</div>
						</div>
						<div class="form-group">
							<label for="address1" class="col-sm-4 control-label fieldLabel_compulsory">Address Line 1:</label>
							<div class="col-sm-8">
								<input type="text" class="form-control compulsory" name="address1" id="address1" value="<?php echo $epa_assessor->address1; ?>" maxlength="100" />
							</div>
						</div>
						<div class="form-group">
							<label for="address2" class="col-sm-4 control-label fieldLabel_optional">Address Line 2:</label>
							<div class="col-sm-8">
								<input type="text" class="form-control optional" name="address2" id="address2" value="<?php echo $epa_assessor->address2; ?>" maxlength="100" />
							</div>
						</div>
						<div class="form-group">
							<label for="address3" class="col-sm-4 control-label fieldLabel_optional">Address Line 3:</label>
							<div class="col-sm-8">
								<input type="text" class="form-control optional" name="address3" id="address3" value="<?php echo $epa_assessor->address3; ?>" maxlength="100" />
							</div>
						</div>
						<div class="form-group">
							<label for="address4" class="col-sm-4 control-label fieldLabel_optional">Address Line 4:</label>
							<div class="col-sm-8">
								<input type="text" class="form-control optional" name="address4" id="address4" value="<?php echo $epa_assessor->address4; ?>" maxlength="100" />
							</div>
						</div>
						<div class="form-group">
							<label for="postcode" class="col-sm-4 control-label fieldLabel_optional">Postcode:</label>
							<div class="col-sm-8">
								<input type="text" class="form-control optional" name="postcode" id="postcode" value="<?php echo $epa_assessor->postcode; ?>" maxlength="15" />
							</div>
						</div>
						<div class="form-group">
							<label for="email" class="col-sm-4 control-label fieldLabel_optional">Email:</label>
							<div class="col-sm-8">
								<input type="text" class="form-control optional" name="email" id="email" value="<?php echo $epa_assessor->email; ?>" maxlength="150" />
							</div>
						</div>
						<div class="form-group">
							<label for="telephone" class="col-sm-4 control-label fieldLabel_optional">Telephone:</label>
							<div class="col-sm-8">
								<input type="text" class="form-control optional" name="telephone" id="telephone" value="<?php echo $epa_assessor->telephone; ?>" maxlength="20" />
							</div>
						</div>

					</form>
				</div>
			</div>
		</div>
	</div>


	<script src="/assets/adminlte/plugins/jQuery/jquery-2.2.3.min.js"></script>
	<script src="/assets/adminlte/plugins/jQueryUI/jquery-ui.min.js"></script>
	<script src="/assets/adminlte/bootstrap/js/bootstrap.min.js"></script>
	<script src="/assets/adminlte/dist/js/app.min.js"></script>
	<script src="js/common.js" type="text/javascript"></script>

	<script>
		function save()
		{
			var myForm = document.forms['frmEPAOrgAssessor'];
			if(validateForm(myForm))
			{
				myForm.submit();
			}
		}
	</script>
</body>
</html>
