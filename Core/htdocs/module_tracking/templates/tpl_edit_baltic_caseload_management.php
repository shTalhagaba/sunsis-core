<!DOCTYPE html>

<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<title>Caseload Management</title>
	<link rel="stylesheet" href="module_tracking/css/common.css" type="text/css" />
	<link rel="stylesheet" href="/assets/adminlte/bootstrap/css/bootstrap.min.css">
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.5.0/css/font-awesome.min.css">
	<link rel="stylesheet" href="/assets/adminlte/plugins/jQueryUI/jquery-ui.css">
	<link rel="stylesheet" href="/assets/adminlte/dist/css/AdminLTE.min.css">

	<!--[if lt IE 9]>
	<script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
	<script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
	<![endif]-->

	<style>
		.ui-datepicker .ui-datepicker-title select {
			color: #000;
		}

		.disabled {
			pointer-events: none;
			opacity: 0.4;
		}
        input[type=checkbox] {
			transform: scale(1.4);
		}
	</style>
    
</head>

<body>

	<div class="row">
		<div class="col-lg-12">
			<div class="banner">
				<div class="Title" style="margin-left: 6px;">Caseload Management</div>
				<div class="ButtonBar">
					<button type="button" class="btn btn-xs btn-default" onclick="window.location.href='<?php echo $_SESSION['bc']->getPrevious(); ?>';"><i class="fa fa-arrow-circle-o-left"></i> Cancel</button>
						<button type="button" class="btn btn-xs btn-default" onclick="save(this);"><i class="fa fa-save"></i> Save</button>
						<?php if (!is_null($vo->id) && $vo->id != '' && ( $_SESSION['user']->isAdmin() || $_SESSION['user']->id == $vo->created_by || in_array($_SESSION['user']->username, ["ecann123", "hgibson1", "jrearsv", "lroddamcarty", "marbrown", "mijones12", "rachaelgreen"]) )) { ?>
							<button type="button" class="btn btn-xs btn-danger" onclick="delete_record(<?php echo $vo->id; ?>);"><i class="fa fa-trash"></i> Delete</button>
						<?php } ?>
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
		<form class="form-horizontal" name="form1" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
			<input type="hidden" name="_action" value="save_baltic_caseload_management" />
			<input type="hidden" name="id" value="<?php echo $vo->id ?>" />
			<input type="hidden" name="tr_id" value="<?php echo $vo->tr_id ?>" />
			<div class="col-sm-7">

				<div class="box box-primary">   
                    <div class="box-header">
                        <h5 class="lead text-info text-bold">Add/Edit Details</h5>
			<?php echo $show_info_msg; ?>
                    </div>
					<div class="box-body">
						<div class="form-group">
							<label for="status" class="col-sm-4 control-label fieldLabel_optional">Status:</label>
							<div class="col-sm-8">
								<?php
								$status_ddl = [
									['Low Risk', 'Low Risk'],
									['Medium Risk', 'Medium Risk'],
									['High Risk', 'High Risk']
								];
								echo HTML::selectChosen('status', $status_ddl, $vo->status, true, false);
								?>
							</div>
						</div>
						<div class="form-group">
							<label for="risk_origin" class="col-sm-4 control-label fieldLabel_optional">Risk Origin:</label>
							<div class="col-sm-8">
								<?php
								echo HTML::selectChosen('risk_origin', InductionHelper::caseloadRiskOriginDdl(), $vo->risk_origin, true, false);
								?>
							</div>
						</div>
						<div class="form-group">
							<label for="initial_date_raised" class="col-sm-4 control-label fieldLabel_optional">Initial Date Raised:</label>
							<div class="col-sm-8">
								<?php echo HTML::datebox('initial_date_raised', $vo->initial_date_raised, false); ?>
							</div>
						</div>
						<div class="form-group">
							<label for="pm_revisit_date_agreed" class="col-sm-4 control-label fieldLabel_optional">PM Revisit Date Agreed:</label>
							<div class="col-sm-8">
								<?php echo HTML::datebox('pm_revisit_date_agreed', $vo->pm_revisit_date_agreed, false); ?>
							</div>
						</div>
						<div class="form-group">
							<label for="peed_agreed_recommended_date" class="col-sm-4 control-label fieldLabel_optional">PEED - Agreed Recommended End Date:</label>
							<div class="col-sm-8">
								<?php echo HTML::datebox('peed_agreed_recommended_date', $vo->peed_agreed_recommended_date, false); ?>
							</div>
						</div>
                        			<div class="form-group">
							<label for="root_cause" class="col-sm-4 control-label fieldLabel_optional">Root Cause:</label>
							<div class="col-sm-8">
								<?php echo HTML::selectChosen('root_cause', InductionHelper::getDdlLeaverMotive(), $vo->root_cause, true, false); ?>
							</div>
						</div>
						<div class="form-group">
							<label for="risk_summary" class="col-sm-4 control-label fieldLabel_optional">Risk Summary:</label>
							<div class="col-sm-8">
								<textarea class="optional" name="risk_summary" id="risk_summary" rows="3" style="width: 100%;"><?php echo $vo->risk_summary; ?></textarea>
							</div>
						</div>
						<div class="form-group">
							<label for="action_plan" class="col-sm-4 control-label fieldLabel_optional">Action Plan:</label>
							<div class="col-sm-8">
								<textarea class="optional" name="action_plan" id="action_plan" rows="3" style="width: 100%;"><?php echo $vo->action_plan; ?></textarea>
							</div>
						</div>
						<div class="form-group">
							<label for="sales_lar" class="col-sm-4 control-label fieldLabel_optional">Talent Pool:</label>
							<div class="col-sm-8">
                                				<?php echo HTML::checkbox('sales_lar', 1, $vo->sales_lar == '1' ? true : false); ?>
							</div>
						</div>
						<div class="form-group">
							<label for="sales_lars_expiry" class="col-sm-4 control-label fieldLabel_optional">Sales LAR Expiry Date:</label>
							<div class="col-sm-8">
								<?php echo HTML::datebox('sales_lars_expiry', $vo->sales_lars_expiry, false); ?>
							</div>
						</div>
						<div class="form-group">
							<label for="bil" class="col-sm-4 control-label fieldLabel_optional">BIL:</label>
							<div class="col-sm-8">
                                <?php echo HTML::checkbox('bil', 1, $vo->bil == '1' ? true : false); ?>
							</div>
						</div>
						<div class="form-group">
							<label for="reinstated" class="col-sm-4 control-label fieldLabel_optional">Reinstated:</label>
							<div class="col-sm-8">
                                <?php echo HTML::checkbox('reinstated', 1, $vo->reinstated == '1' ? true : false); ?>
							</div>
						</div>
						<div class="form-group">
							<label for="change_of_employer" class="col-sm-4 control-label fieldLabel_optional">Change of Employer:</label>
							<div class="col-sm-8">
                                <?php echo HTML::checkbox('change_of_employer', 1, $vo->change_of_employer == '1' ? true : false); ?>
							</div>
						</div>
                        <div class="form-group">
							<label for="closed_date" class="col-sm-4 control-label fieldLabel_optional">Closed Date:</label>
							<div class="col-sm-8">
								<?php echo HTML::datebox('closed_date', $vo->closed_date, false); ?>
							</div>
						</div>
                        <div class="form-group">
							<label for="destination" class="col-sm-4 control-label fieldLabel_optional">Destination:</label>
							<div class="col-sm-8">
								<?php 
                                $destination_ddl = [
									['Continuing - No concern', 'Continuing - No concern'],
									['Continuing - Monitoring', 'Continuing - Monitoring'],
									['Continuing - LRAS', 'Continuing - LRAS'],
									['EPA Ready', 'EPA Ready'],
									['Leaver', 'Leaver'],
									// ['Direct Leaver - No intervention', 'Direct Leaver - No intervention'],
								];
                                echo HTML::selectChosen('destination', $destination_ddl, $vo->destination, true, false);
                                ?>
							</div>                            
						</div>
                        <div class="form-group">
							<label for="leaver_decision_made" class="col-sm-4 control-label fieldLabel_optional">Leaver Decision Made:</label>
							<div class="col-sm-8">
								<?php echo HTML::datebox('leaver_decision_made', $vo->leaver_decision_made, false); ?>
							</div>
						</div>
                        <div class="form-group">
							<label for="leaver_reason" class="col-sm-4 control-label fieldLabel_optional">Leaver Reason:</label>
							<div class="col-sm-8">
								<?php 
                                $leaver_reason_ddl = [
									['Dismissal', 'Dismissal'],
									['Resignation', 'Resignation'],
									['Redundancy/End of Contract', 'Redundancy/End of Contract'],
									['Removed from Apprenticeship - Apprentice', 'Removed from Apprenticeship - Apprentice'],
									['Removed from Apprenticeship - Employer', 'Removed from Apprenticeship - Employer'],
									['Removed from Apprenticeship - Baltic', 'Removed from Apprenticeship - Baltic'],
								];
                                echo HTML::selectChosen('leaver_reason', $leaver_reason_ddl, $vo->leaver_reason, true, false);
                                ?>
							</div>                            
						</div>
                        <div class="form-group">
							<label for="positive_outcome" class="col-sm-4 control-label fieldLabel_optional">Positive Outcome:</label>
							<div class="col-sm-8">
								<?php 
                                $positive_outcome_ddl = [
									['Higher/Further Education', 'Higher/Further Education'],
									['Full time role', 'Full time role'],
									['Promotion', 'Promotion'],
								];
                                echo HTML::selectChosen('positive_outcome', $positive_outcome_ddl, $vo->positive_outcome, true, false);
                                ?>
							</div>                            
						</div>
                        <div class="form-group">
							<label for="potential_return" class="col-sm-4 control-label fieldLabel_optional">Potential Return:</label>
							<div class="col-sm-8">
                                <?php echo HTML::checkbox('potential_return', 1, $vo->potential_return == '1' ? true : false); ?>
							</div>
						</div>
                        <div class="form-group">
							<label for="previous_leaver" class="col-sm-4 control-label fieldLabel_optional">Previous Leaver:</label>
							<div class="col-sm-8">
                                <?php echo HTML::checkbox('previous_leaver', 1, $vo->previous_leaver == '1' ? true : false); ?>
							</div>
						</div>
						<div class="form-group">
							<label for="leaver_note" class="col-sm-4 control-label fieldLabel_optional">Leaver Note:</label>
							<div class="col-sm-8">
								<textarea class="optional" name="leaver_note" id="leaver_note" rows="3" style="width: 100%;"><?php echo $vo->leaver_note; ?></textarea>
							</div>
						</div>
					</div>

					<div class="box-footer">
						<span class="lead text-bold">Audit</span>
						<div class="form-group">
							<label for="bh_consultation_start" class="col-sm-4 control-label fieldLabel_optional">BH Consultation Start Date:</label>
							<div class="col-sm-8">
								<?php echo HTML::datebox('bh_consultation_start', $vo->bh_consultation_start, false); ?>
							</div>
						</div>
						<div class="form-group">
							<label for="bh_revisit" class="col-sm-4 control-label fieldLabel_optional">BH Revisit Date:</label>
							<div class="col-sm-8">
								<?php echo HTML::datebox('bh_revisit', $vo->bh_revisit, false); ?>
							</div>
						</div>
						<div class="form-group">
							<label for="bh_consultation_closed" class="col-sm-4 control-label fieldLabel_optional">BH Consultation Closed Date:</label>
							<div class="col-sm-8">
								<?php echo HTML::datebox('bh_consultation_closed', $vo->bh_consultation_closed, false); ?>
							</div>
						</div>
						<div class="form-group">
							<label for="bh_shortlist" class="col-sm-4 control-label fieldLabel_optional">BH Shortlist:</label>
							<div class="col-sm-8">
                                				<?php echo HTML::checkbox('bh_shortlist', 1, $vo->bh_shortlist == '1' ? true : false); ?>
							</div>
						</div>
					</div>
					<!-- <div class="box-footer">
						<span class="lead text-bold">Audit Notes</span>
						<div class="form-group">
							<label for="audited" class="col-sm-4 control-label fieldLabel_optional">Audited by Ben Hogg:</label>
							<div class="col-sm-8">
                                <?php echo HTML::checkbox('audited', 1, $vo->audited == '1' ? true : false); ?>
							</div>
						</div>
						<div class="form-group">
							<label for="right_candidate" class="col-sm-4 control-label fieldLabel_optional">Right Candidate:</label>
							<div class="col-sm-8">
                                <?php //echo HTML::checkbox('right_candidate', 1, $vo->right_candidate == '1' ? true : false); ?>
							</div>
						</div>
						<div class="form-group">
							<label for="right_employer" class="col-sm-4 control-label fieldLabel_optional">Right Employer:</label>
							<div class="col-sm-8">
                                <?php //echo HTML::checkbox('right_employer', 1, $vo->right_employer == '1' ? true : false); ?>
							</div>
						</div>
						<div class="form-group">
							<label for="right_support" class="col-sm-4 control-label fieldLabel_optional">Right Support:</label>
							<div class="col-sm-8">
                                <?php //echo HTML::checkbox('right_support', 1, $vo->right_support == '1' ? true : false); ?>
							</div>
						</div>
						<div class="form-group">
							<label for="sbi_recommed" class="col-sm-4 control-label fieldLabel_optional">SBI Recommended:</label>
							<div class="col-sm-8">
                                <?php //echo HTML::checkbox('sbi_recommed', 'P', in_array('P', $sbi_recommed) ? true : false); ?> &nbsp; Positive <br>
                                <?php //echo HTML::checkbox('sbi_recommed', 'D', in_array('D', $sbi_recommed) ? true : false); ?> &nbsp; Developmental 
							</div>
						</div>
						<div class="form-group">
							<label for="auditor_notes" class="col-sm-4 control-label fieldLabel_optional">Auditor Note:</label>
							<div class="col-sm-8">
								<textarea class="optional" name="auditor_notes" id="auditor_notes" rows="3" style="width: 100%;" maxlength="5000"><?php //echo $vo->auditor_notes; ?></textarea>
							</div>
						</div>
						<div class="form-group">
							<label for="actions_required" class="col-sm-4 control-label fieldLabel_optional">Actions Required:</label>
							<div class="col-sm-8">
								<textarea class="optional" name="actions_required" id="actions_required" rows="3" style="width: 100%;" maxlength="5000"><?php //echo $vo->actions_required; ?></textarea>
							</div>
						</div>						
					</div> -->

				</div>

			</div>
            <div class="col-sm-1"></div>
			<div class="col-sm-4">
                <div class="callout callout-defaul">
                    <h5 class="lead text-bold">Learner Details</h5>
                    <span class="text-bold">Name: </span><?php echo $tr->firstnames . ' ' . $tr->surname; ?><br>
                    <span class="text-bold">Employer: </span><?php echo $tr->legal_name; ?><br>
                    <span class="text-bold">Programme: </span><?php echo DAO::getSingleValue($link, "SELECT title FROM student_frameworks WHERE tr_id = '{$tr->id}'"); ?><br>
                    <span class="text-bold">Start Date: </span><?php echo Date::toShort($tr->start_date); ?><br>
                    <span class="text-bold">Planned End Date: </span><?php echo Date::toShort($tr->target_date); ?><br>
				</div>
            </div>

		</form>
	</div>
	<div id="dialogDeleteEntry" style="display:none" title="Delete Entry"></div>
	<br>

	<script src="/assets/adminlte/plugins/jQuery/jquery-2.2.3.min.js"></script>
	<script src="/assets/adminlte/plugins/jQueryUI/jquery-ui.min.js"></script>
	<script src="/assets/adminlte/bootstrap/js/bootstrap.min.js"></script>
	<script src="/assets/adminlte/dist/js/app.min.js"></script>
	<script src="/common.js" type="text/javascript"></script>
	<script type="text/javascript" src="/assets/js/jquery/jquery.timepicker.js"></script>

	<script language="JavaScript">
		$(function() {


		});

		function save(save_btn) {
			
			var closedDate = $("input[name=closed_date]").val();
			var destination = $("select[name=destination]").val();
			if(closedDate != '' && destination != '')
			{
				closedDate = stringToDate(closedDate);
				var currentDate = stringToDate('<?php echo date('Y-m-d'); ?>');
				var allowedPastDate = new Date(currentDate);
				allowedPastDate.setMonth(allowedPastDate.getMonth() - 1);
				allowedPastDate.setDate(allowedPastDate.getDate() + 1);
			}

			if (closedDate < allowedPastDate && destination == 'Leaver') 
			{
				let allowedDateMinFormatted = formatDateGB(allowedPastDate);
        			alert('The closed date cannot be more than one month in the past. The earliest allowed date is ' + allowedDateMinFormatted);
				return false;
    			}

			var myForm = document.forms["form1"];
			if (validateForm(myForm) == false) {
				return false;
			}
			$(save_btn).attr('disabled', true);
			myForm.submit();
		}

		function delete_record(record_id) {
			if (!confirm('This action cannot be undone, are you sure you want to delete this record?'))
				return;
			var client = ajaxRequest('do.php?_action=edit_baltic_caseload_management&ajax_request=true&id=' + encodeURIComponent(record_id));
			alert(client.responseText);
			$("button").attr('disabled', true);
			window.history.back();
		}
	</script>

</body>

</html>