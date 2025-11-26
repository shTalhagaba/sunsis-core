<?php /* @var $tr User */ ?>
<?php /* @var $safeguarding Safeguarding */ ?>

<!DOCTYPE html>
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<title>Add/Edit Safeguarding Info</title>
	<link rel="stylesheet" href="module_tracking/css/common.css" type="text/css"/>
	<link rel="stylesheet" href="/assets/adminlte/bootstrap/css/bootstrap.min.css">
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.5.0/css/font-awesome.min.css">
	<link rel="stylesheet" href="/assets/adminlte/plugins/jQueryUI/jquery-ui.css">
	<link rel="stylesheet" href="/assets/adminlte/dist/css/AdminLTE.min.css">
	<link href="/assets/adminlte/plugins/chosen/bootstrap-chosen.css" rel="stylesheet">

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
	<div class="col-sm-12">
		<div class="banner">
			<div class="Title" style="margin-left: 6px;">Add/Edit Safeguarding Info</div>
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
	<div class="col-sm-12">
		<?php $_SESSION['bc']->render($link); ?>
	</div>
</div>
<br>

<div class="row">
	<div class="col-sm-12">
		<div class="col-sm-6">
			<div class="callout">
				<div class="row">
					<div class="col-sm-12">
						<span class="text-bold text-info">Learner Name: </span><?php echo $tr->firstnames . ' ' . $tr->surname; ?> | 
						<span class="text-bold text-info">Learner Ref: </span><?php echo $tr->l03; ?> | 
						<span class="text-bold text-info">Employer: </span><?php echo $tr->legal_name; ?> | 
					</div>
				</div>
			</div>
		</div>
	</div>
</div>

<div class="row">
	
	<div class="col-sm-7 col-sm-offset-1">
		<form class="form-horizontal" name="frmSafeguarding" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
			<input type="hidden" name="_action" value="save_safeguarding" />
			<input type="hidden" name="id" value="<?php echo $safeguarding->id; ?>" />
			<input type="hidden" name="tr_id" value="<?php echo $tr->id; ?>" />
			<div class="box box-primary">

				<div class="box-body">
					<div class="form-group">
						<label for="triggers" class="col-sm-3 control-label fieldLabel_optional">Triggers:</label>
						<div class="col-sm-9">
							<?php echo HTML::selectChosen('triggers', Safeguarding::getDdlTriggers($link), $safeguarding->triggers, true); ?>
						</div>
					</div>
					<div class="form-group">
						<label for="factors" class="col-sm-3 control-label fieldLabel_optional">Contributing Factors:</label>
						<div class="col-sm-9">
							<?php echo HTML::selectChosen('factors', Safeguarding::getDdlContributingFactors($link), $safeguarding->factors, true, false, true, 10); ?>
						</div>
					</div>
					<div class="form-group">
						<label for="routeway" class="col-sm-3 control-label fieldLabel_optional">Routeway:</label>
						<div class="col-sm-9">
							<?php echo HTML::selectChosen('routeway', Safeguarding::getDdlRouteways(), $safeguarding->routeway, true); ?>
						</div>
					</div>
					<div class="form-group">
						<label for="summary" class="col-sm-3 control-label fieldLabel_optional">Summary:</label>
						<div class="col-sm-9">
							<textarea name="summary" id="summary" rows="3" style="width: 100%;" maxlength="1200"><?php echo $safeguarding->summary; ?></textarea>
						</div>
					</div>
					<div class="form-group">
						<label for="action_plan" class="col-sm-3 control-label fieldLabel_optional">Action Plan:</label>
						<div class="col-sm-9">
							<textarea name="action_plan" id="action_plan" rows="3" style="width: 100%;" maxlength="1200"><?php echo $safeguarding->action_plan; ?></textarea>
						</div>
					</div>
					<div class="form-group">
						<label for="category" class="col-sm-3 control-label fieldLabel_optional">Category:</label>
						<div class="col-sm-9">
							<?php echo HTML::selectChosen('category', Safeguarding::getDdlCategories($link), $safeguarding->category, true, false, true, 10); ?>
						</div>
					</div>
					<div class="form-group">
						<label for="reported_by" class="col-sm-3 control-label fieldLabel_optional">Reported By:</label>
						<div class="col-sm-9">
							<?php echo HTML::selectChosen('reported_by', Safeguarding::getDdlSafeguardingReportedBy(), $safeguarding->category, true, false, true); ?>
						</div>
					</div>
					<div class="form-group">
						<label for="date" class="col-sm-3 control-label fieldLabel_optional">Date Reported/Opened:</label>
						<div class="col-sm-9">
							<?php echo HTML::datebox('date', $safeguarding->date); ?>
						</div>
					</div>
					<div class="form-group">
						<label for="date_closed" class="col-sm-3 control-label fieldLabel_optional">Date Closed/Removed:</label>
						<div class="col-sm-9">
							<?php echo HTML::datebox('date_closed', $safeguarding->date_closed); ?>
						</div>
					</div>
					<div class="form-group">
						<label for="reactive_proactive" class="col-sm-3 control-label fieldLabel_optional">Reactive/ Proactive:</label>
						<div class="col-sm-9">
							<?php echo HTML::selectChosen('reactive_proactive', Safeguarding::getDdlProRe(), $safeguarding->reactive_proactive, true); ?>
						</div>
					</div>
					<div class="form-group">
						<label for="recommended_end_date" class="col-sm-3 control-label fieldLabel_optional">Recommended End Date:</label>
						<div class="col-sm-9">
							<?php echo HTML::datebox('recommended_end_date', $safeguarding->recommended_end_date); ?>
						</div>
					</div>
					<div class="form-group">
						<label for="support_provider" class="col-sm-3 control-label fieldLabel_optional">Support Provider:</label>
						<div class="col-sm-9">
						<?php echo HTML::selectChosen('support_provider', Safeguarding::getDdlSupportProvider(), $safeguarding->support_provider, true, false, true, 10); ?>
						</div>
					</div>
					<div class="form-group">
						<label for="learner_voice" class="col-sm-3 control-label fieldLabel_optional">Learner Voice:</label>
						<div class="col-sm-9">
							<textarea name="learner_voice" id="learner_voice" rows="3" style="width: 100%;" maxlength="1200"><?php echo $safeguarding->learner_voice; ?></textarea>
						</div>
					</div>
					<div class="form-group">
						<label for="app_success_comments" class="col-sm-3 control-label fieldLabel_optional">Apprentice Success Comments:</label>
						<div class="col-sm-9">
							<textarea name="app_success_comments" id="app_success_comments" rows="3" style="width: 100%;" maxlength="1200"><?php echo $safeguarding->app_success_comments; ?></textarea>
						</div>
					</div>

				</div>

			</div>
		</form>
	</div>

	<div class="col-sm-4">
		<div class="box box-primary">
			<div class="box-header box-primary">
				<span class="box-title">Configuration</span>
			</div>
			<div class="box-body">
				<div class="callout callout-info">
					Use this panel to add new options in the drop downs. Please use this carefully and avoid adding duplicate options.
				</div>
				<label for="lookup_table" class="col-sm-12 control-label fieldLabel_compulsory">Select dropdown list:</label>
				<div class="col-sm-12">
					<?php 
					$lookup_tables = [
						['lookup_safeguarding_triggers', 'Triggers'],
						['lookup_safeguarding_contr_factors', 'Contributing Factors'],
						['lookup_safeguarding_categories', 'Categories'],
					];
					echo HTML::selectChosen('lookup_table_name', $lookup_tables, null, true, false); 
					?>
				</div>
				<label for="lookup_table_option" class="col-sm-12 control-label fieldLabel_compulsory">Enter new option:</label>
				<div class="col-sm-12">
					<input class="form-control" type="text" name="lookup_table_option" id="lookup_table_option" maxlength="70" />
				</div>
			</div>
			<div class="box-footer">
				<button type="button" class="btn btn-xs btn-success btn-block" id="btnAddLookupOption"><i class="fa fa-save"></i> Click to Add</button>
			</div>

		</div>
	</div>
	
</div>

<div id="dialogDeleteFile" style="display:none" title="Delete record"></div>
<br>

<script src="/assets/adminlte/plugins/jQuery/jquery-2.2.3.min.js"></script>
<script src="/assets/adminlte/plugins/jQueryUI/jquery-ui.min.js"></script>
<script src="/assets/adminlte/bootstrap/js/bootstrap.min.js"></script>
<script src="/assets/adminlte/dist/js/app.min.js"></script>
<script src="/common.js" type="text/javascript"></script>
<script src="/assets/adminlte/plugins/chosen/chosen.jquery.js"></script>

<script language="JavaScript">

	$(function() {

		$('.datepicker').datepicker({
			format: 'dd/mm/yyyy'
		});

		$('.datepicker').addClass('form-control');

		$('#factors, #category, #support_provider').chosen({width: "100%"});

		$("button#btnAddLookupOption").on('click', function(){
			$(this).attr('disabled', true);
			var lookup_table_name = $("select[name=lookup_table_name]").val();
			var lookup_table_option = $("input[name=lookup_table_option]").val();
			if(lookup_table_name == '' || lookup_table_option == '')
			{
				alert('Please select the dropdown list and enter new option for it.');
				$(this).attr('disabled', false);
				return false;
			}
			$.ajax({
                		type: 'POST',
                		url: 'do.php?_action=ajax_tracking&subaction=add_option_to_lookup',
				data: {lookup_table_name: lookup_table_name, lookup_table_option: lookup_table_option},
                		success: function(result) {
                    			if(result == 'success')
					{
						alert('The new option is added successfully in the list.');
						window.location.reload();
					}
                		},
                		error: function(data, textStatus, xhr) {
                    			alert(data.responseText);
                		}
            		});
		});

	});


	function save()
	{
		var myForm = document.forms["frmSafeguarding"];
		if(validateForm(myForm) == false)
		{
			return false;
		}


		myForm.submit();
	}


</script>

</body>
</html>