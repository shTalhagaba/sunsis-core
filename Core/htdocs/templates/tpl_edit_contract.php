<?php /* @var $vo Contract */ ?>

<!DOCTYPE html>
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<title><?php echo $vo->id == ''?'Create Contract':'Edit Contract'; ?></title>
	<link rel="stylesheet" href="module_tracking/css/common.css" type="text/css"/>
	<link rel="stylesheet" href="/assets/adminlte/bootstrap/css/bootstrap.min.css">
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.5.0/css/font-awesome.min.css">
	<link rel="stylesheet" href="/assets/adminlte/plugins/jQueryUI/jquery-ui.css">
	<link rel="stylesheet" href="/assets/adminlte/dist/css/AdminLTE.min.css">
	<link href="https://gitcdn.github.io/bootstrap-toggle/2.2.2/css/bootstrap-toggle.min.css" rel="stylesheet">

	<!--[if lt IE 9]>
	<script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
	<script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
	<![endif]-->

	<style>
		.ui-datepicker .ui-datepicker-title select {
			color: #000;
		}
		.disabled{
			pointer-events:none;
			opacity:0.4;
		}
	</style>
</head>
<body>

<div class="row">
	<div class="col-lg-12">
		<div class="banner">
			<div class="Title" style="margin-left: 6px;"><?php echo $vo->id == ''?'Create Contract':'Edit Contract'; ?></div>
			<div class="ButtonBar">
				<span class="btn btn-sm btn-default" onclick="window.location.href='<?php echo $_SESSION['bc']->getPrevious(); ?>';"><i class="fa fa-arrow-circle-o-left"></i> Cancel</span>
				<span class="btn btn-sm btn-default" onclick="saveFrmContract();"><i class="fa fa-save"></i> Save</span>
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
	<form class="form-horizontal" name="frmContract" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
		<input type="hidden" name="_action" value="save_contract" />
		<input type="hidden" name="id" value="<?php echo $vo->id ?>" />
		<div class="col-md-8">

			<div class="box box-primary">

				<div class="box-body">
					<div class="form-group">
						<label for="active" class="col-sm-4 control-label fieldLabel_compulsory">Active:</label>
						<div class="col-sm-8">
							<?php
							echo $vo->active == '1' ?
								'<input value="1" class="yes_no_toggle" type="checkbox" name="active" data-toggle="toggle" checked="checked" data-toggle="toggle" data-on="Yes" data-off="No" data-onstyle="success" data-offstyle="danger" />':
								'<input value="1" class="yes_no_toggle" type="checkbox" name="active" data-toggle="toggle" data-on="Yes" data-off="No" data-onstyle="success" data-offstyle="danger" />';
							?>
						</div>
					</div>
					<div class="form-group">
						<label for="text" class="col-sm-4 control-label fieldLabel_compulsory">Title:</label>
						<div class="col-sm-8">
							<input class="form-control compulsory" type="text" name="title" value="<?php echo htmlspecialchars((string)$vo->title ?: ''); ?>" />
						</div>
					</div>
					<div class="form-group">
						<label for="contract_year" class="col-sm-4 control-label fieldLabel_compulsory">Contract Holder:</label>
						<div class="col-sm-8">
							<div class="callout">
								<div class="form-group">
									<label for="contract_holder" class="col-sm-4 control-label fieldLabel_compulsory">Org. Name:</label>
									<div class="col-sm-8">
										<?php echo HTML::selectChosen('contract_holder', $providers, $vo->contract_holder, true, true); ?>
									</div>
								</div>
								<div class="form-group">
									<label for="ukprn" class="col-sm-4 control-label fieldLabel_optional">Org. UKPRN:</label>
									<div class="col-sm-8">
										<input class="form-control optional" type="text" name="ukprn" id="ukprn" value="<?php echo htmlspecialchars((string)$vo->ukprn ?: ''); ?>" />
									</div>
								</div>
							</div>
						</div>
					</div>
					<div class="form-group">
						<label for="contract_year" class="col-sm-4 control-label fieldLabel_compulsory">Year & Dates:</label>
						<div class="col-sm-8">
							<div class="callout">
								<div class="form-group">
									<label for="contract_year" class="col-sm-4 control-label fieldLabel_compulsory">Contract Year:</label>
									<div class="col-sm-8">
										<?php echo HTML::selectChosen('contract_year', $contract_year, $vo->contract_year, true, true); ?>
									</div>
								</div>
								<div class="form-group">
									<label for="input_start_date" class="col-sm-4 control-label fieldLabel_compulsory">Start Date:</label>
									<div class="col-sm-8">
										<?php echo HTML::datebox('start_date', $vo->start_date, true); ?>
									</div>
								</div>
								<div class="form-group">
									<label for="input_end_date" class="col-sm-4 control-label fieldLabel_compulsory">End Date:</label>
									<div class="col-sm-8">
										<?php echo HTML::datebox('end_date', $vo->end_date, true); ?>
									</div>
								</div>
							</div>
						</div>
					</div>
					<div class="form-group">
						<label for="contract_year" class="col-sm-4 control-label fieldLabel_compulsory">Funding:</label>
						<div class="col-sm-8">
							<div class="callout">
								<div class="form-group">
									<label for="funded" class="col-sm-4 control-label fieldLabel_compulsory">Type:</label>
									<div class="col-sm-8">
										<?php
										echo $vo->funded == '1' ?
											'<input value="1" class="yes_no_toggle" type="checkbox" name="funded" data-toggle="toggle" checked="checked" data-toggle="toggle" data-on="Funded" data-off="Non-Funded" data-onstyle="success" data-offstyle="danger" />':
											'<input value="1" class="yes_no_toggle" type="checkbox" name="funded" data-toggle="toggle" data-on="Funded" data-off="Non-Funded" data-onstyle="success" data-offstyle="danger" />';
										?>
									</div>
								</div>
								<div class="form-group">
									<label for="funding_provision" class="col-sm-4 control-label fieldLabel_compulsory">Provision:</label>
									<div class="col-sm-8">
										<?php echo HTML::selectChosen('funding_provision', Contract::getDDLFundingProvisions(true), $vo->funding_provision, true, true); ?>
									</div>
								</div>
								<div class="form-group">
									<label for="proportion" class="col-sm-4 control-label fieldLabel_compulsory">Proportion:</label>
									<div class="col-sm-8">
										<input class="form-control compulsory" type="text" id = "proportion" name="proportion" onkeypress="return numbersonly(this, event);" value="<?php echo htmlspecialchars((string)$vo->proportion ?: ''); ?>" maxlength="3" />
									</div>
								</div>
							</div>
						</div>
					</div>
					<div class="form-group">
						<label for="contract_location" class="col-sm-4 control-label fieldLabel_compulsory">Contract Location:</label>
						<div class="col-sm-8">
							<?php echo HTML::selectChosen('contract_location', $contract_locations, $vo->contract_location, true, true); ?>
						</div>
					</div>
					<?php if($vo->funding_body != '' && $vo->funding_body != '2'){ ?>
					<div class="form-group">
						<label for="funding_body" class="col-sm-4 control-label fieldLabel_compulsory">Type of Funding:</label>
						<div class="col-sm-8">
							<?php echo HTML::selectChosen('funding_body', $funding_bodies, $vo->funding_body, true, true); ?>
						</div>
					</div>
					<?php } ?>
                    <?php if(DB_NAME=='am_crackerjack' or DB_NAME=='am_baltic_demo'){ ?>
                    <div class="form-group">
                        <label for="funding_body" class="col-sm-4 control-label fieldLabel_optional">Allocation:</label>
                        <div class="col-sm-8">
                            <?php echo HTML::selectChosen('allocation_id', $allocations, $vo->allocation_id, true, false); ?>
                        </div>
                    </div>
                    <?php } ?>
					<?php if($vo->contract_year<2011 && $vo->contract_year!=''){ ?>
					<div class="form-group">
						<label for="funding_body" class="col-sm-4 control-label fieldLabel_compulsory">L25 LSC Number:</label>
						<div class="col-sm-8">
							<?php echo HTML::selectChosen('L25', $L25_dropdown, $vo->L25, true, true); ?>
						</div>
					</div>
					<?php } else { ?>
					<div class="form-group">
						<label for="funding_body" class="col-sm-4 control-label fieldLabel_optional">Contracting Organisation:</label>
						<div class="col-sm-8">
							<?php echo HTML::selectChosen('L25', $ContOrg_dropdown, $vo->L25, true, false); ?>
						</div>
					</div>
					<?php } ?>




					<div class="form-group">
						<label for="funding_type" class="col-sm-4 control-label fieldLabel_compulsory">Include in QARs:</label>
						<div class="col-sm-8">
							<?php
							echo $vo->funding_type == '1' ?
								'<input value="1" class="yes_no_toggle" type="checkbox" name="funding_type" data-toggle="toggle" checked="checked" data-toggle="toggle" data-on="Include" data-off="Exclude" data-onstyle="success" data-offstyle="danger" />':
								'<input value="1" class="yes_no_toggle" type="checkbox" name="funding_type" data-toggle="toggle" data-on="Include" data-off="Exclude" data-onstyle="success" data-offstyle="danger" />';
							?>
						</div>
					</div>




				</div>

			</div>

		</div>
		<div class="col-md-4">
			<div class="box box-primary">
				<div class="box-body">
					<div class="form-group">
						<label for="description" class="col-sm-12 fieldLabel_optional">Description:</label>
						<div class="col-sm-12">
							<textarea name="description" id="description" rows="10" style="width: 100%;"><?php echo $vo->description; ?></textarea>
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
<script src="/assets/adminlte/dist/js/app.min.js"></script>
<script src="/common.js" type="text/javascript"></script>
<script src="https://gitcdn.github.io/bootstrap-toggle/2.2.2/js/bootstrap-toggle.min.js"></script>

<script language="JavaScript">

	$(function() {

		$('.datepicker').datepicker({
			format: 'dd/mm/yyyy'
		});

		$('#input_start_date').attr('class', 'datepicker compulsory form-control');
		$('#input_end_date').attr('class', 'datepicker compulsory form-control');
	});

	function saveFrmContract()
	{
		var frmContract = document.forms["frmContract"];
		if(validateForm(frmContract) == false)
		{
			return false;
		}
		frmContract.submit();
	}

	function contract_year_onchange(year)
	{
		if(year.value == '')
		{
			document.getElementById('input_start_date').value = '';
			document.getElementById('input_end_date').value = '';
			return ;
		}
		var y = parseInt(year.value);
		var ny = y+1;
		document.getElementById('input_start_date').value = '01/08/' + y;
		document.getElementById('input_end_date').value = '31/07/' + ny;
	}

	function contract_holder_onchange(contractholder)
	{
		var request = ajaxBuildRequestObject();
		request.open("GET", expandURI('do.php?_action=ajax_get_ukprn&id=' + contractholder.value), false);
		request.setRequestHeader("x-ajax", "1");
		request.send(null);

		if(request.status == 200)
		{
			var ukprn = request.responseText;
			if(ukprn != 'error')
			{
				document.getElementById('ukprn').value = ukprn;
			}
			else
			{
			}
		}
		else
		{
			ajaxErrorHandler(request);
		}
	}
</script>

</body>
</html>