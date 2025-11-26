<?php /* @var $inductee Inductee */ ?>
<?php /* @var $induction Induction */ ?>
<?php /* @var $inductionProgramme InductionProgramme */ ?>

<!DOCTYPE html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title><?php echo $inductee->id == ''?'Create Induction':'Edit Induction'; ?></title>
    <link rel="stylesheet" href="module_tracking/css/common.css" type="text/css"/>
    <link rel="stylesheet" href="/assets/adminlte/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.5.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/ionicons/2.0.1/css/ionicons.min.css">
    <link rel="stylesheet" href="/assets/adminlte/plugins/chosen/bootstrap-chosen.css">
    <link rel="stylesheet" href="/assets/adminlte/plugins/jQueryUI/jquery-ui.css">
    <link rel="stylesheet" href="/assets/adminlte/plugins/iCheck/all.css">
    <link rel="stylesheet" href="/assets/adminlte/dist/css/AdminLTE.min.css">
    <link rel="stylesheet" type="text/css" href="/assets/css/jquery.timepicker.css" />

    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->


    <style>
        #home_postcode{text-transform:uppercase}
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
            <div class="Title" style="margin-left: 6px;"><?php echo $inductee->id == ''?'Create Induction':'Edit Induction'; ?></div>
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

<div class="row" id="messageBox" style="display: none;">
    <div class="col-md-8">
        <div class="box box-primary">
            <div class="box-header with-border"><h2 class="box-title">Matching learner(s) found</h2>
                <div class="box-tools pull-right"><span class="btn btn-md btn-primary" onclick="document.forms['frmLearner'].submit();" title="Create a new independent record "><i class="fa fa-plus-circle"></i> Create New</span></div>
            </div>
            <div class="box-body">
                <div id="tbl_duplicate_records" style="width: auto;"></div>
            </div>
            <div class="box-footer"></div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-4">

        <div class="box box-primary callout">
            <form class="form-horizontal" name="frmLearner" action="<?php echo $_SERVER['PHP_SELF']; ?>">
                <input type="hidden" name="id" value="<?php echo $inductee->id; ?>" />
                <input type="hidden" name="_action" value="save_induction" />
                <input type="hidden" name="formName" value="frmLearner" />
                <div class="box-header with-border"><h2 class="box-title">Learner <small>enter learner information</small></h2>
                    <div class="box-tools pull-right"></div>
                </div>
                <div class="box-body">
                    <div class="form-group">
                        <label for="input_employment_start_date" class="col-sm-4 control-label fieldLabel_compulsory">Employment Start Date:</label>
                        <div class="col-sm-8">
                            <?php echo HTML::datebox('employment_start_date', $inductee->employment_start_date, true); ?>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="firstnames" class="col-sm-4 control-label fieldLabel_compulsory">Firstnames:</label>
                        <div class="col-sm-8">
                            <input type="text" class="form-control compulsory" name="firstnames" id="firstnames" value="<?php echo $inductee->firstnames; ?>" maxlength="100" />
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="surname" class="col-sm-4 control-label fieldLabel_compulsory">Surname:</label>
                        <div class="col-sm-8">
                            <input type="text" class="form-control compulsory" name="surname" id="surname" value="<?php echo $inductee->surname; ?>" maxlength="100" />
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="input_dob" class="col-sm-4 control-label fieldLabel_compulsory">Date of Birth:</label>
                        <div class="col-sm-8">
                            <?php echo HTML::datebox('dob', $inductee->dob, true); ?>
                            <label class="label label-info" id="lblAgeToday"><?php echo Date::dateDiff(date("Y-m-d"), $inductee->dob); ?></label>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="gender" class="col-sm-4 control-label fieldLabel_compulsory">Gender:</label>
                        <div class="col-sm-8">
                            <?php echo HTML::selectChosen('gender', InductionHelper::getDDLGender(), $inductee->gender, true, true); ?>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="ni" class="col-sm-4 control-label fieldLabel_optional">National Insurance:</label>
                        <div class="col-sm-8">
                            <input type="text" class="form-control optional" name="ni" id="ni" value="<?php echo $inductee->ni; ?>" maxlength="9" />
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="home_telephone" class="col-sm-4 control-label fieldLabel_optional">Telephone:</label>
                        <div class="col-sm-8">
                            <input type="text" class="form-control optional" name="home_telephone" id="home_telephone" value="<?php echo $inductee->home_telephone; ?>" maxlength="100" />
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="home_mobile" class="col-sm-4 control-label fieldLabel_optional">Mobile:</label>
                        <div class="col-sm-8">
                            <input type="text" class="form-control optional" name="home_mobile" id="home_mobile" value="<?php echo $inductee->home_mobile; ?>" maxlength="100" />
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="home_email" class="col-sm-4 control-label fieldLabel_optional">Personal Email:</label>
                        <div class="col-sm-8">
                            <input type="text" class="form-control optional" name="home_email" id="home_email" value="<?php echo $inductee->home_email; ?>" maxlength="100" />
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="work_email" class="col-sm-4 control-label fieldLabel_optional">Work Email:</label>
                        <div class="col-sm-8">
                            <input type="text" class="form-control optional" name="work_email" id="work_email" value="<?php echo $inductee->work_email; ?>" maxlength="100" />
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="ldd" class="col-sm-4 control-label fieldLabel_optional">LDD:</label>
                        <div class="col-sm-8">
                            <?php echo HTML::selectChosen('ldd', InductionHelper::getDDLInductionLdd(), $inductee->ldd, true); ?>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="ldd_comments" class="col-sm-4 control-label fieldLabel_optional">LDD Comments:</label>
                        <div class="col-sm-8">
                            <textarea class="form-control optional" name="ldd_comments" id="ldd_comments" rows="5"><?php echo $inductee->ldd_comments; ?></textarea>
                        </div>
                    </div>
                    <!--<div class="form-group">
					<label for="next_of_kin" class="col-sm-4 control-label fieldLabel_optional">Next of Kin:</label>
					<div class="col-sm-8">
						<input type="text" class="form-control optional" name="next_of_kin" id="next_of_kin" value="<?php /*echo $inductee->next_of_kin; */?>" maxlength="100" />
					</div>
				</div>
				<div class="form-group">
					<label for="next_of_kin_tel" class="col-sm-4 control-label fieldLabel_optional">Next of Kin (tel.):</label>
					<div class="col-sm-8">
						<input type="text" class="form-control optional" name="next_of_kin_tel" id="next_of_kin_tel" value="<?php /*echo $inductee->next_of_kin_tel; */?>" maxlength="100" />
					</div>
				</div>
				<div class="form-group">
					<label for="next_of_kin_email" class="col-sm-4 control-label fieldLabel_optional">Next of Kin (email):</label>
					<div class="col-sm-8">
						<input type="text" class="form-control optional" name="next_of_kin_email" id="next_of_kin_email" value="<?php /*echo $inductee->next_of_kin_email; */?>" maxlength="100" />
					</div>
				</div>-->
                    <div class="form-group">
                        <label for="learner_id" class="col-sm-4 control-label fieldLabel_optional">Learner ID:</label>
                        <div class="col-sm-8">
                            <?php echo HTML::selectChosen('learner_id', InductionHelper::getDDLLearnerID(), $inductee->learner_id, true); ?>
                        </div>
                    </div>
                    <!--<div class="form-group">
                        <label for="learner_id_notes" class="col-sm-4 control-label fieldLabel_optional">Learner ID Comments: <i class="fa fa-comments" title="show saved comments" style="cursor: pointer" onclick="showInducteeNotes('learner_id_notes');"></i></label>
                        <div class="col-sm-8">
                            <textarea class="form-control optional" name="learner_id_notes" id="learner_id_notes" rows="5"></textarea>
                        </div>
                    </div>-->
                    <div class="form-group">
                        <label for="employer_type" class="col-sm-4 control-label fieldLabel_compulsory">Employer Type:</label>
                        <div class="col-sm-8">
                            <?php echo HTML::selectChosen('employer_type', InductionHelper::getDDLInducteeEmployerType(), $inductee->employer_type, true, true); ?>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="inductee_type" class="col-sm-4 control-label fieldLabel_compulsory">Learner Type:</label>
                        <div class="col-sm-8">
                            <?php echo HTML::selectChosen('inductee_type', InductionHelper::getDDLInducteeTypeV2(), $inductee->inductee_type, false, true); ?>
                        </div>
                    </div>
                    <!-- <div class="form-group">
                        <label for="location_area" class="col-sm-4 control-label fieldLabel_optional">Delivery Location:</label>
                        <div class="col-sm-8">
                            <?php //echo HTML::selectChosen('location_area', $ddlDeliveryLocations, $inductee->location_area, true, false); ?>
                        </div>
                    </div> -->
                    <div class="form-group">
                        <label for="employer_id" class="col-sm-4 control-label fieldLabel_compulsory">Employer:</label>
                        <div class="col-sm-8">
                            <?php echo HTML::selectChosen('employer_id', $ddlEmployers, $inductee->employer_id, true, true); ?>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="employer_location_id" class="col-sm-4 control-label fieldLabel_compulsory">Employer Location:</label>
                        <div class="col-sm-8">
                            <?php echo HTML::selectChosen('employer_location_id', $ddlEmployersLocations, $inductee->employer_location_id, false, true); ?>
                        </div>
                    </div>
		    <div class="form-group">
                        <label for="paid_hours" class="col-sm-4 control-label fieldLabel_compulsory">Paid working hours:</label>
                        <div class="col-sm-8">
                            <input type="text" class="form-control compulsory" name="paid_hours" id="paid_hours" value="<?php echo $inductee->paid_hours; ?>" maxlength="100" />
                        </div>
                    </div>
		    <div class="form-group">
                        <label for="salary" class="col-sm-4 control-label fieldLabel_compulsory">Salary:</label>
                        <div class="col-sm-8">
                            <input type="text" class="form-control compulsory" name="salary" id="salary" value="<?php echo $inductee->salary; ?>" maxlength="100" />
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="emp_crm_contacts" class="col-sm-4 control-label fieldLabel_compulsory">Employer Contacts:</label>
                    </div>
                    <div class="form-group">
                        <div class="col-sm-12">
                            <?php echo HTML::selectChosen('emp_crm_contacts', $ddlEmployerContacts, explode(',', $inductee->emp_crm_contacts), false, true, true, 10); ?>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="emp_crm_contacts_notes" class="col-sm-4 control-label fieldLabel_optional">Employer Contacts Notes: <i class="fa fa-comments" title="show saved comments" style="cursor: pointer" onclick="showInducteeNotes('emp_crm_contacts_notes');"></i></label>
                        <div class="col-sm-8">
                            <textarea class="form-control optional" name="emp_crm_contacts_notes" id="emp_crm_contacts_notes" rows="5"></textarea>
                        </div>
                    </div>
                </div>
                <div class="box-footer">
                    <button type="button" <?php echo $disabled; ?> class="btn btn-primary pull-right" onclick="saveFrmLearner(); "><i class="fa fa-save"></i> <?php echo $inductee->id == ''?'Create Learner':'Save Learner';?></button>
                </div>
            </form>
        </div>

    </div>

    <div class="col-md-4">

        <div class="box box-primary callout">
            <form class="form-horizontal" name="frmLearnerInduction" id="frmLearnerInduction" action="<?php echo $_SERVER['PHP_SELF']; ?>">
                <input type="hidden" name="id" value="<?php echo $induction->id; ?>" />
                <input type="hidden" name="inductee_id" value="<?php echo $inductee->id; ?>" />
                <input type="hidden" name="_action" value="save_induction" />
                <input type="hidden" name="formName" value="frmLearnerInduction" />
                <div class="box-header with-border"><h2 class="box-title">Induction <small>enter learner induction information</small></h2>
                    <div class="box-tools pull-right"></div>
                </div>
                <div class="box-body with-border">
                    <div class="form-group">
                        <label for="induction_status" class="col-sm-4 control-label fieldLabel_compulsory">Induction Status:</label>
                        <div class="col-sm-8">
                            <?php echo HTML::selectChosen('induction_status', $ddlInductionStatus, $induction->induction_status, true, true); ?>
                        </div>
                    </div>
                    <div class="callout" id="divIssueWithReason">
                        <div class="form-group">
                            <label for="comp_issue" class="col-sm-4 control-label fieldLabel_optional">Red Flag Learner:</label>
                            <div class="col-sm-8">
                                <?php echo HTML::selectChosen('comp_issue', $ddlYesNo, $induction->comp_issue, false); ?>
                            </div>
                        </div>
			            <div class="form-group">
                            <label for="red_le" class="col-sm-4 control-label fieldLabel_optional">Learner/Employer:</label>
                            <div class="col-sm-8">
                                <?php echo HTML::selectChosen('red_le', [['LC', 'Learner Conduct'], ['EC', 'Employer Conduct']], $induction->red_le, true); ?>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="comp_issue_notes" class="col-sm-4 control-label fieldLabel_optional">Reason:</label>
                            <div class="col-sm-8">
                                <textarea class="form-control optional" name="comp_issue_notes" id="comp_issue_notes" rows="5"><?php echo $induction->comp_issue_notes; ?></textarea>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="input_induction_date" class="col-sm-4 control-label fieldLabel_compulsory">Induction Date:</label>
                        <div class="col-sm-8">
                            <?php echo HTML::datebox('induction_date', $induction->induction_date, true); ?>
                            <label class="label label-info" id="lblAgeAtInduction"><?php echo Date::dateDiff($induction->induction_date, $inductee->dob); ?></label>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="input_planned_end_date" class="col-sm-4 control-label fieldLabel_compulsory">Planned End Date:</label>
                        <div class="col-sm-8">
                            <?php echo HTML::datebox('planned_end_date', $induction->planned_end_date, true); ?>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="join_time" class="col-sm-4 control-label fieldLabel_compulsory">Join Time:</label>
                        <div class="col-sm-8">
                            <?php echo HTML::selectChosen('join_time', InductionHelper::getDDLAMPM(), $induction->join_time); ?>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="induction_moved" class="col-sm-4 control-label fieldLabel_optional">Induction Moved:</label>
                        <div class="col-sm-8">
                            <?php echo HTML::selectChosen('induction_moved', InductionHelper::getDdlInductionMoved(), $induction->induction_moved, true); ?>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="induction_moved_reason" class="col-sm-4 control-label fieldLabel_optional">Induction Moved Reason:</label>
                        <div class="col-sm-8">
                            <?php echo HTML::selectChosen('induction_moved_reason', InductionHelper::getDdlInductionMovedReason(), $induction->induction_moved_reason, true); ?>
                        </div>
                    </div>
                    <!--<div class="form-group">
                        <label for="reinstated" class="col-sm-4 control-label fieldLabel_optional">Re-instated:</label>
                        <div class="col-sm-8">
                            <?php /*echo HTML::selectChosen('reinstated', $ddlYesNo, $induction->reinstated, false); */?>
                        </div>
                    </div>-->
                    <!--<div class="form-group">
                        <label for="miap" class="col-sm-4 control-label fieldLabel_optional">MIAP:</label>
                        <div class="col-sm-8">
                            <?php /*echo HTML::selectChosen('miap', InductionHelper::getDDLMIAP(), $induction->miap, false); */?>
                        </div>
                    </div>-->
                    <div class="form-group">
                        <label for="headset_issued" class="col-sm-4 control-label fieldLabel_optional">Headset Issued:</label>
                        <div class="col-sm-8">
                            <?php echo HTML::selectChosen('headset_issued', $ddlInductionHeadset, $induction->headset_issued, false); ?>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="webcam" class="col-sm-4 control-label fieldLabel_optional">Webcam:</label>
                        <div class="col-sm-8">
                            <?php echo HTML::selectChosen('webcam', InductionHelper::getDDLWebcam($link), $induction->webcam, true); ?>
                        </div>
                    </div>
                    <!-- <div class="form-group">
                        <label for="moredle_account" class="col-sm-4 control-label fieldLabel_optional">Moredle Account:</label>
                        <div class="col-sm-8">
                            <?php //echo HTML::selectChosen('moredle_account', $ddlYesNoNA, $induction->moredle_account, false); ?>
                        </div>
                    </div> -->
                    <div class="form-group">
                        <label for="iag_numeracy" class="col-sm-4 control-label fieldLabel_optional">Numeracy Level:</label>
                        <div class="col-sm-8">
                            <?php echo HTML::selectChosen('iag_numeracy', $ddlIAG, $induction->iag_numeracy, true); ?>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="iag_literacy" class="col-sm-4 control-label fieldLabel_optional">Literacy Level:</label>
                        <div class="col-sm-8">
                            <?php echo HTML::selectChosen('iag_literacy', $ddlIAG, $induction->iag_literacy, true); ?>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="math_cert" class="col-sm-4 control-label fieldLabel_optional">Maths Certificate:</label>
                        <div class="col-sm-8">
                            <?php echo HTML::selectChosen('math_cert', InductionHelper::getDdlCerts(), $induction->math_cert, true); ?>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="eng_cert" class="col-sm-4 control-label fieldLabel_optional">English Certificate:</label>
                        <div class="col-sm-8">
                            <?php echo HTML::selectChosen('eng_cert', InductionHelper::getDdlCerts(), $induction->eng_cert, true); ?>
                        </div>
                    </div>
                    <!-- <div class="form-group">
                        <label for="fs_exempt" class="col-sm-4 control-label fieldLabel_optional">Functional Skills Exempt:</label>
                        <div class="col-sm-8">
                            <?php //echo HTML::selectChosen('fs_exempt', InductionHelper::getDDLYesNo(), $induction->fs_exempt, true); ?>
                        </div>
                    </div>  --> 
                    <!--<div class="form-group">-->
                    <!--	<label for="iag_ict" class="col-sm-4 control-label fieldLabel_optional">ICT Level:</label>-->
                    <!--	<div class="col-sm-8">-->
                    <!--		--><?php //echo HTML::selectChosen('iag_ict', $ddlICT, $induction->iag_ict, true); ?>
                    <!--	</div>-->
                    <!--</div>-->
                    <!--<div class="form-group">
	<label for="diagnostics_completed" class="col-sm-4 control-label fieldLabel_optional">Diagnostics Completed:</label>
	<div class="col-sm-8">
		<?php /*echo HTML::selectChosen('diagnostics_completed', InductionHelper::getDDLYesNo(), $induction->diagnostics_completed, true); */?>
	</div>
</div>
-->
                    <div class="form-group">
                        <label for="wfd_assessment" class="col-sm-4 control-label fieldLabel_optional">GCSE Eligibility Met:</label>
                        <div class="col-sm-8">
                            <?php echo HTML::selectChosen('wfd_assessment', InductionHelper::getDDLYesNo(), $induction->wfd_assessment, true); ?>
                        </div>
                    </div>
                    <!--<div class="form-group">
	<label for="dip_ws_delivery" class="col-sm-4 control-label fieldLabel_optional">Quality Category:</label>
	<div class="col-sm-8">
		<?php /*echo HTML::selectChosen('dip_ws_delivery', InductionHelper::getDDLQualityCategory(), $induction->dip_ws_delivery, true); */?>
	</div>
	</div>
	-->
                    
                    <div class="form-group">
                        <label for="induction_owner" class="col-sm-4 control-label fieldLabel_compulsory">Induction Owner:</label>
                        <div class="col-sm-8">
                            <?php echo HTML::selectChosen('induction_owner', $ddlInductionOwners, $induction->induction_owner, true); ?>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="input_induction_arranged" class="col-sm-4 control-label fieldLabel_optional">Induction Arranged:</label>
                        <div class="col-sm-8">
                            <?php echo HTML::datebox('induction_arranged', $induction->induction_arranged, false); ?>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="induction_assessor" class="col-sm-4 control-label fieldLabel_compulsory">Induction Host:</label>
                        <div class="col-sm-8">
                            <?php echo HTML::selectChosen('induction_assessor', $ddlInductionAssessors, $induction->induction_assessor, true, true); ?>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="assigned_assessor" class="col-sm-4 control-label fieldLabel_optional">Assigned Learning Mentor:</label>
                        <div class="col-sm-8">
                            <?php echo HTML::selectChosen('assigned_assessor', $ddlAssignedAssessors, $induction->assigned_assessor, true, false); ?>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="assigned_coord" class="col-sm-4 control-label fieldLabel_optional">Assigned Coordinator:</label>
                        <div class="col-sm-8">
                            <?php echo HTML::selectChosen('assigned_coord', $ddlAssignedCoordinators, $induction->assigned_coord, true, false); ?>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="brm" class="col-sm-4 control-label fieldLabel_compulsory">CEM:</label>
                        <div class="col-sm-8">
                            <input type="text" class="form-control compulsory" name="brm" id="brm" value="<?php echo $induction->brm; ?>" maxlength="100" />
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="lead_gen" class="col-sm-4 control-label fieldLabel_compulsory">Business Consultant:</label>
                        <div class="col-sm-8">
                            <input type="text" class="form-control compulsory" name="lead_gen" id="lead_gen" value="<?php echo $induction->lead_gen; ?>" maxlength="100" />
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="resourcer" class="col-sm-4 control-label fieldLabel_compulsory">Recruiter:</label>
                        <div class="col-sm-8">
                            <input type="text" class="form-control compulsory" name="resourcer" id="resourcer" value="<?php echo $induction->resourcer; ?>" maxlength="100" />
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="arm" class="col-sm-4 control-label fieldLabel_compulsory">Account Relationship Manager:</label>
                        <div class="col-sm-8">
                            <input type="text" class="form-control optional" name="arm" id="arm" value="<?php echo $induction->arm; ?>" maxlength="100" />
                            <?php //echo HTML::selectChosen('arm', InductionHelper::getDDLInductionARM($link), $induction->arm, true, true); ?>
                        </div>
                    </div>
                    <!--
					<div class="form-group">
						<label for="sunesis_account" class="col-sm-4 control-label fieldLabel_optional">Sunesis Account:</label>
						<div class="col-sm-8">
							<?php /*echo HTML::selectChosen('sunesis_account', $ddlYesNo, $induction->sunesis_account, false); */?>
						</div>
					</div>
-->
                    <div class="form-group">
                        <label for="commit_statement" class="col-sm-4 control-label fieldLabel_compulsory">Commitment Statement:</label>
                        <div class="col-sm-8">
                            <?php echo HTML::selectChosen('commit_statement', InductionHelper::getDDLCommitmentStatement(), $induction->commit_statement, true, true); ?>
                        </div>
                    </div>

                    <!-- <div class="callout">
                         <div class="form-group">
                            <table class="table">
                                <tr>
                                    <td>
                                        <input type="checkbox" name="commit_signed[]" value="E" <?php /*echo strpos($induction->commit_signed, 'E') !== false? 'checked':''; */?> /><label>Employer Signed</label>
                                    </td>
                                    <td>
                                        <input type="checkbox" name="commit_signed[]" value="L" <?php /*echo strpos($induction->commit_signed, 'L') !== false? 'checked':''; */?> /><label>Learner Signed</label>
                                    </td>
                                    <td>
					                    <input type="checkbox" name="commit_signed[]" value="P" <?php /*echo strpos($induction->commit_signed, 'P') !== false? 'checked':''; */?> /><label>Parent Signed</label>
				                    </td>
                                </tr>
                            </table>
                        </div> 
                    </div> -->
                    <div class="form-group">
                        <label for="enrolment_form" class="col-sm-4 control-label fieldLabel_optional">Enrolment Form:</label>
                        <div class="col-sm-8">
                            <?php echo HTML::selectChosen('enrolment_form', InductionHelper::getDDLCommitmentStatement(), $induction->enrolment_form, true); ?>
                        </div>
                    </div>
                    <div class="callout">
                        <div class="form-group">
                            <div class="form-group">
                                <label for="input_date_moved_from_grey_section" class="col-sm-4 control-label fieldLabel_optional">Date moved from holding section:</label>
                                <div class="col-sm-8">
                                    <?php echo HTML::datebox('date_moved_from_grey_section', $induction->date_moved_from_grey_section, true); ?>
                                </div>
                            </div>
                        </div>
			            <div class="form-group">
                            <label for="withdrawn_reason" class="col-sm-4 control-label fieldLabel_optional">Withdrawn Reason: </label>
                            <div class="col-sm-8">
                                <textarea class="form-control optional" name="withdrawn_reason" id="withdrawn_reason" rows="5"><?php echo $induction->withdrawn_reason; ?></textarea>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="grey_section_comments" class="col-sm-4 control-label fieldLabel_optional">Holding Induction Comments: <i class="fa fa-comments" title="show saved comments" style="cursor: pointer" onclick="showNotes('grey_section_comments');"></i></label>
                            <div class="col-sm-8">
                                <textarea class="form-control optional" name="grey_section_comments" id="grey_section_comments" rows="5"></textarea>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="induction_notes" class="col-sm-4 control-label fieldLabel_optional">Induction Comments: <i class="fa fa-comments" title="show saved comments" style="cursor: pointer" onclick="showNotes('induction_notes');"></i></label>
                        <div class="col-sm-8">
                            <textarea class="form-control optional" name="induction_notes" id="induction_notes" rows="5"></textarea>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="coordinator_notes" class="col-sm-4 control-label fieldLabel_optional">Coordinator Comments: <i class="fa fa-comments" title="show saved comments" style="cursor: pointer" onclick="showNotes('coordinator_notes');"></i></label>
                        <div class="col-sm-8">
                            <textarea class="form-control optional" name="coordinator_notes" id="coordinator_notes" rows="5"></textarea>
                        </div>
                    </div>
                    <div class="callout callout-default">
                        <span class="lead text-bold">Funding Information</span>
                        <div class="form-group">
                            <label for="levy_payer" class="col-sm-4 control-label fieldLabel_optional">Levy Payer:</label>
                            <div class="col-sm-8">
                                <?php echo HTML::selectChosen('levy_payer', $ddlYesNo, $induction->levy_payer, true); ?>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="levy_app_completed" class="col-sm-4 control-label fieldLabel_optional">Levy Application Completed:</label>
                            <div class="col-sm-8">
                                <?php echo HTML::selectChosen('levy_app_completed', InductionHelper::getDdlLevyApplication(), $induction->levy_app_completed, true); ?>
                            </div>
                        </div>
			            <div class="form-group">
                            <label for="levy_comments" class="col-sm-4 control-label fieldLabel_optional">Levy Comments: <i class="fa fa-comments" title="show saved comments" style="cursor: pointer" onclick="showNotes('levy_comments');"></i></label>
                            <div class="col-sm-8">
                                <textarea class="form-control optional" name="levy_comments" id="levy_comments" rows="5"></textarea>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="das_account" class="col-sm-4 control-label fieldLabel_optional">DAS Account Created:</label>
                            <div class="col-sm-8">
                                <?php echo HTML::selectChosen('das_account', InductionHelper::getDDLDasAccountCreated(), $induction->das_account, true); ?>
                            </div>
                    	</div>
			            <div class="form-group">
                            <label for="das_comments" class="col-sm-4 control-label fieldLabel_optional">DAS Comments: <i class="fa fa-comments" title="show saved comments" style="cursor: pointer" onclick="showNotes('das_comments');"></i></label>
                            <div class="col-sm-8">
                                <textarea class="form-control optional" name="das_comments" id="das_comments" rows="5"></textarea>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="box-footer">
                    <button id="btnSaveFrmLearnerInduction" <?php echo $disabled; ?> type="button" class="btn btn-primary pull-right" onclick="saveFrmLearnerInduction(); "><i class="fa fa-save"></i> Save Induction</button>
                </div>
            </form>
        </div>

    </div>

    <div class="col-md-4">

        <div class="box box-primary callout">
            <form class="form-horizontal" name="frmLearnerProgramme" id="frmLearnerProgramme" action="<?php echo $_SERVER['PHP_SELF']; ?>">
                <input type="hidden" name="id" value="<?php echo $inductionProgramme->id; ?>" />
                <input type="hidden" name="inductee_id" value="<?php echo $inductee->id; ?>" />
                <input type="hidden" name="_action" value="save_induction" />
                <input type="hidden" name="formName" value="frmLearnerProgramme" />
                <div class="box-header with-border"><h2 class="box-title">Programme<small> enter learner programme information</small></h2>
                    <div class="box-tools pull-right"></div>
                </div>
                <div class="box-body with-border">
                    <div class="form-group">
                        <label for="programme_id" class="col-sm-4 control-label fieldLabel_compulsory">Programme:</label>
                        <div class="col-sm-8">
                            <?php echo HTML::selectChosen('programme_id', $ddlCourseProgramme, $inductionProgramme->programme_id, true, true); ?>
                        </div>
                    </div>
                    <!-- <div class="callout">
                        <div class="form-group">
                            <label for="eligibility_test_status" class="col-sm-4 control-label fieldLabel_optional">Eligibility Test Status:</label>
                            <div class="col-sm-8">
                                <?php /*echo HTML::selectChosen('eligibility_test_status', InductionHelper::getDDLEligibilityTestStatus(), $inductionProgramme->eligibility_test_status, true); */?>
                            </div>
                        </div>
                        <div class="form-group">
                            <table class="table">
                                <tr>
                                    <td>
                                        <input type="checkbox" name="eligibility_test_type[]" value="S" <?php /*echo strpos($inductionProgramme->eligibility_test_type, 'S') !== false? 'checked':'';*/ ?> /><label>Standard eligibility test</label>
                                    </td>
                                    <td>
                                        <input type="checkbox" name="eligibility_test_type[]" value="W" <?php /*echo strpos($inductionProgramme->eligibility_test_type, 'W') !== false? 'checked':'';*/ ?> /><label>WS 1 rework</label>
                                    </td>
                                    <td>
                                        <input type="checkbox" name="eligibility_test_type[]" value="N" <?php /*echo strpos($inductionProgramme->eligibility_test_type, 'N') !== false? 'checked':'';*/ ?> /><label>No test required</label>
                                    </td>
                                </tr>
                            </table>
                        </div>
                    </div> -->
                    <div class="form-group">
                        <label for="skilsure_username" class="col-sm-4 control-label fieldLabel_optional">Smart Assessor Username:</label>
                        <div class="col-sm-8">
                            <input type="text" class="form-control optional" name="skilsure_username" id="skilsure_username" value="<?php echo $inductionProgramme->skilsure_username; ?>" maxlength="100" />
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="skilsure_password" class="col-sm-4 control-label fieldLabel_optional">Smart Assessor Password:</label>
                        <div class="col-sm-8">
                            <input type="text" class="form-control optional" name="skilsure_password" id="skilsure_password" value="<?php echo $inductionProgramme->skilsure_password; ?>" maxlength="25" />
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="mentor_username" class="col-sm-4 control-label fieldLabel_optional">Mentor Username:</label>
                        <div class="col-sm-8">
                            <input type="text" class="form-control optional" name="mentor_username" id="mentor_username" value="<?php echo $inductionProgramme->mentor_username; ?>" maxlength="100" />
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="mentor_password" class="col-sm-4 control-label fieldLabel_optional">Mentor Password:</label>
                        <div class="col-sm-8">
                            <input type="text" class="form-control optional" name="mentor_password" id="mentor_password" value="<?php echo $inductionProgramme->mentor_password; ?>" maxlength="25" />
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="programme_notes" class="col-sm-4 control-label fieldLabel_compulsory">Comments: <i class="fa fa-comments" title="show saved comments" style="cursor: pointer" onclick="showProgrammeNotes('programme_notes');"></i></label>
                        <div class="col-sm-8">
                            <textarea class="form-control compulsory" name="programme_notes" id="programme_notes" rows="5"></textarea>
                        </div>
                    </div>
                </div>
                <div class="box-footer">
                    <button id="btnSaveFrmLearnerProgramme" <?php echo $disabled; ?> type="button" class="btn btn-primary pull-right" onclick="saveFrmLearnerProgramme(); "><i class="fa fa-save"></i> Save Programme</button>
                </div>
            </form>
        </div>

    </div>


</div>

<div class="modal fade" id="modal-default">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">Default Modal</h4>
            </div>
            <div class="modal-body">
                <p>One fine body&hellip;</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary">Save changes</button>
            </div>
        </div>
    </div>
</div>

<br>

<script src="/assets/adminlte/plugins/jQuery/jquery-2.2.3.min.js"></script>
<script src="/assets/adminlte/plugins/jQueryUI/jquery-ui.min.js"></script>
<script src="/assets/adminlte/bootstrap/js/bootstrap.min.js"></script>
<script src="/assets/adminlte/plugins/chosen/chosen.jquery.js"></script>
<script src="/assets/adminlte/plugins/iCheck/icheck.min.js"></script>
<script src="/assets/adminlte/dist/js/app.min.js"></script>
<script src="/common.js" type="text/javascript"></script>
<script type="text/javascript" src="/assets/js/jquery/jquery.timepicker.js"></script>

<script language="JavaScript">
    var phpInductionStatus = '<?php echo $induction->induction_status; ?>';

    $(function() {
        $('.datepicker').datepicker({
            format: 'dd/mm/yyyy',
            yearRange: 'c-50:c+50'
        });

        $('input[type=checkbox]').each(function(){
            var self = $(this),
                label = self.next(),
                label_text = label.text();

            label.remove();
            self.iCheck({
                checkboxClass: 'icheckbox_line-blue',
                radioClass: 'iradio_line',
                insert: '<div class="icheck_line-icon"></div>' + label_text
            });

        });

        $('#employer_id').chosen({placeholder_text_single: "Select an employer"});
        $('#emp_crm_contacts').chosen();

        $('#input_dob').attr('class', 'datepicker compulsory form-control');
        $('#input_induction_date').attr('class', 'datepicker compulsory form-control');
        $('#input_nine_month_end').attr('class', 'datepicker compulsory form-control');
        $('#input_planned_end_date').attr('class', 'datepicker compulsory form-control');
        $('#input_employment_start_date').attr('class', 'datepicker compulsory form-control');
        $('#input_date_moved_from_grey_section').attr('class', 'datepicker optional form-control');
        $('#input_induction_arranged').attr('class', 'datepicker optional form-control');

        $(".timebox").timepicker({ timeFormat: 'H:i' });

        $('.timebox').bind('timeFormatError timeRangeError', function() {
            this.value = '';
            alert("Please choose a valid time");
            this.focus();
        });

        <?php if($inductee->id == ''){?>
        $('#frmLearnerInduction').find(':input:not(:disabled)').prop('disabled',true);
        <?php } ?>
        <?php if($induction->id == ''){?>
        $('#frmLearnerProgramme').find(':input:not(:disabled)').prop('disabled',true);
        <?php } ?>

        var disabled_induction_assessors = [<?php echo DAO::getSingleValue($link, "SELECT GROUP_CONCAT(user_id) FROM lookup_induction_assessors WHERE enabled = 'N'")?>];
        $("#induction_assessor option").each(function(){
            if($.inArray(parseInt(this.value), disabled_induction_assessors) != -1)
                $(this).attr('disabled', 'disabled');
        });
        var disabled_assigned_assessors = [<?php echo DAO::getSingleValue($link, "SELECT GROUP_CONCAT(user_id) FROM lookup_assigned_assessors WHERE enabled = 'N'")?>];
        $("#assigned_assessor option").each(function(){
            if($.inArray(parseInt(this.value), disabled_assigned_assessors) != -1)
                $(this).attr('disabled', 'disabled');
        });
        // var disabled_delivery_locations = [<?php //echo DAO::getSingleValue($link, "SELECT GROUP_CONCAT(id) FROM lookup_delivery_locations WHERE enabled = 'N'")?>];
        // $("#location_area option").each(function(){
        //     if($.inArray(parseInt(this.value), disabled_delivery_locations) != -1)
        //         $(this).attr('disabled', 'disabled');
        // });
	var disabled_assigned_coord = [24233, 21406, 28444, 22988, 27362, 22552, 21097, 27443, 26054];
        $("#assigned_coord option").each(function(){
            if($.inArray(parseInt(this.value), disabled_assigned_coord) != -1)
                $(this).attr('disabled', 'disabled');
        });

        if(window.phpInductionStatus == 'C')
        {
            $('#divIssueWithReason').show();
        }
    });

    /*
    $('#induction_status').change(function(){
        if($(this).val() == 'C')
            $('#divIssueWithReason').show();
        else
            $('#divIssueWithReason').hide();
    });
    */

    function saveFrmLearnerProgramme()
    {
	    $("#btnSaveFrmLearnerProgramme").prop("disabled", true);
        var myForm = document.forms["frmLearnerProgramme"];
        if(validateForm(myForm) == false)
        {
	        $("#btnSaveFrmLearnerProgramme").prop("disabled", false);
            return false;
        }
        // var req = ajaxRequest('do.php?_action=ajax_tracking&subaction=isL4Programme&programme_id='+$('#programme_id').val());
        // if(req.responseText == 'yes' && $('#eligibility_test_status').val().trim() == '')
        // {
        //     alert('\'Eligibility Test Status\' cannot be left blank if learner is on Level 4 programme');
        //     $('#eligibility_test_status').focus();
	    //     $("#btnSaveFrmLearnerProgramme").prop("disabled", false);
        //     return;
        // }
        myForm.submit();
    }

    function saveFrmLearnerInduction()
    {
	$("#btnSaveFrmLearnerInduction").prop("disabled", true);
        var myForm = document.forms["frmLearnerInduction"];
        if(validateForm(myForm) == false)
        {
	    $("#btnSaveFrmLearnerInduction").prop("disabled", false);	
            return false;
        }
        if($('#induction_status').val() == 'S' && $('#levy_payer').val() == '')
        {
            alert('Please select levy payer option in order to update induction status to \'Scheduled\'');
            $('#levy_payer').focus();
	    $("#btnSaveFrmLearnerInduction").prop("disabled", false);
            return;
        }
        if($('#induction_status').val() == 'C')
        {
            var validation = '';
            //if($('#ni').val().trim() == '')
            //	validation += '- National insurance number is blank<br>';
            if($('#employer_id').val() == '')
                validation += '- Employer is not selected<br>';
            if($('#input_induction_date').val() == '')
                validation += '- Induction date is blank<br>';
            //if($('#moredle_account').val() != 'Y')
            //    validation += '- Moredle account is not selected as \'Yes\'<br>';
            /*if($('#iag_literacy').val() == '')
                            validation += '- Literacy level is blank<br>';
                        if($('#iag_numeracy').val() == '')
                            validation += '- Numeracy level is blank<br>';
                        if($('#iag_ict').val() == '')
                            validation += '- ICT level is blank<br>';*/
            //if($('#sla_received').val() == '' || $('#sla_received').val() == 'N' || $('#sla_received').val() == 'R')
            //    validation += '- SLA received should be \'Yes New\' or \'Yes Old\'<br>';
            if($('#commit_statement').val() != 'FC')
                validation += '- Commitment statement should be \'Fully Completed\'<br>';
            if($('#planned_end_date').val() == '')
                validation += '- Planned end date is blank<br>';
            if($('#comp_issue').val() == 'Y' && $('#comp_issue_notes').val().trim() == '')
                validation += '- If this is a read flag learner, please provide the reason<br>';
            //validation += '- If there is issue with induction, please provide the reason<br>';
            var age = getAge($('#input_dob').val(), '', true);
            // if(age.years == 16 || age.years == 17)
            // {
            //     var c = 0;
            //     $("input[name='commit_signed[]']").each( function () {
            //         if(this.checked)
            //             c++;
            //     });
            //     if(c != 2)
            //         validation += '- If the learner is 16 or 17 then all two commitment statement options (Employer Signed, Learner Signed) should be ticked.<br>';
            // }
            if(validation != '')
            {
		$("#btnSaveFrmLearnerInduction").prop("disabled", false);
                $("<div></div>").html(validation).dialog({
                    id: "dlg_lrs_result",
                    title: "Validation errors for status Completed",
                    resizable: false,
                    modal: true,
                    width: 750,
                    height: 500,

                    buttons: {
                        'Close': function() {$(this).dialog('close');}
                    }
                });
            }
            else
            {
                myForm.submit();
            }
        }
        else
        {
            myForm.submit();
        }
    }

    function saveFrmLearner()
    {
        var myForm = document.forms["frmLearner"];
        if(validateForm(myForm) == false)
        {
            return false;
        }
        if($('#home_email').val().trim() != '' && validateEmail($('#home_email').val().trim()) == false)
        {
            alert('Incorrect format for learner\'s email');
            $('#home_email').focus();
            return false;
        }
        if($('#ni').val().trim() != '' && validateNI($('#ni').val().trim()) == false)
        {
            alert('Incorrect format for learner\'s National Insurance');
            $('#ni').focus();
            return false;
        }

        <?php if($inductee->id == ''){?>
        var parameters = '&firstnames=' + encodeURIComponent(myForm.elements["firstnames"].value)
            + '&surname=' + encodeURIComponent(myForm.elements["surname"].value)
            + '&dob=' + encodeURIComponent(myForm.elements["dob"].value)
            + '&subaction=' + encodeURIComponent("checkNewInducteeDuplicates")
        ;

        var req = ajaxRequest('do.php?_action=ajax_tracking'+parameters, null, null, saveFrmLearnerCallback);
        <?php } else {?>
        myForm.submit();
        <?php } ?>
    }

    function saveFrmLearnerCallback(req)
    {
        if(req.status == '200')
        {
            if(req.responseText == '')
            {
                document.forms["frmLearner"].submit();
            }
            else
            {
                var html = '';
                html = '<table class="table table-responsive">' +
                    '<thead><tr><th>&nbsp;</th><th>Inductee ID</th><th>Creation Date</th><th>First Name(s)</th><th>Surname</th><th>DOB</th><th>Postcode</th><th>Created By</th></tr></thead>' +
                    '<tbody>';

                var myObject = eval('(' + req.responseText + ')');
                for (var i in myObject)
                {
                    html += '<tr>';
                    html += '<td><span class="btn btn-info btn-sm" onclick="window.location.href=\'do.php?_action=edit_inductee&id=' + myObject[i]['id'] + '\'" ><i class="fa fa-folder-open"></i> Open</span> &nbsp;</td>';
                    html += '<td>' + myObject[i]["id"] + '</td>' +
                        '<td>' + formatDate(myObject[i]["created"], true) + '</td>' +
                        '<td>' + myObject[i]["firstnames"] + '</td>' +
                        '<td>' + myObject[i]["surname"] + '</td>' +
                        '<td>' + formatDate(myObject[i]["dob"], false) + '</td>' +
                        /*'<td>' + myObject[i]["home_postcode"] + '</td>' +*/
                        '<td>' + myObject[i]["created_by"] + '</td>'
                    ;
                    html += '</tr>';
                }
                html += '</tbody></table>';
                $('#tbl_duplicate_records').html(html);
                $('#messageBox').show();
                $(this).scrollTop(0);
            }
        }
        else
        {
            alert(req.responseText);
        }
        $('.loading-gif').hide();
    }

    function employer_id_onchange(employer, event)
    {
        var f = location.form;

        var employer_locations = document.getElementById('employer_location_id');
        var emp_crm_contacts = document.getElementById('emp_crm_contacts');

        if(employer.value != '')
        {
            employer.disabled = true;

            employer_locations.disabled = true;
            ajaxPopulateSelect(employer_locations, 'do.php?_action=ajax_tracking&subaction=load_employer_locations&employer_id=' + employer.value);
            employer_locations.disabled = false;

            emp_crm_contacts.disabled = true;
            ajaxPopulateSelect(emp_crm_contacts, 'do.php?_action=ajax_tracking&subaction=load_employer_contacts&employer_id=' + employer.value);
            $('#emp_crm_contacts').attr('disabled', false).trigger("chosen:updated");
            emp_crm_contacts.disabled = false;

            employer.disabled =false;
        }
        else
        {
            emptySelectElement(employer_locations);
            emptySelectElement(emp_crm_contacts);
        }
    }

    function showInducteeNotes(note_type)
    {
        var inductee_id = '<?php echo $inductee->id; ?>';
        if(inductee_id == '')
            return;

        var postData = 'do.php?_action=ajax_tracking'
            + '&inductee_id=' + encodeURIComponent(inductee_id)
            + '&subaction=' + encodeURIComponent("getInducteeNotes")
            + '&note_type=' + encodeURIComponent(note_type)
        ;

        var req = ajaxRequest(postData);
        $("<div></div>").html(req.responseText).dialog({
            id: "dlg_lrs_result",
            title: "Saved Comments",
            resizable: false,
            modal: true,
            width: 750,
            height: 500,

            buttons: {
                'Close': function() {$(this).dialog('close');}
            }
        });
    }

    function showNotes(note_type)
    {
        var induction_id = '<?php echo $induction->id; ?>';
        if(induction_id == '')
            return;

        var postData = 'do.php?_action=ajax_tracking'
            + '&induction_id=' + encodeURIComponent(induction_id)
            + '&subaction=' + encodeURIComponent("getInductionNotes")
            + '&note_type=' + encodeURIComponent(note_type)
        ;

        var req = ajaxRequest(postData);
        $("<div></div>").html(req.responseText).dialog({
            id: "dlg_lrs_result",
            title: "Saved Comments",
            resizable: false,
            modal: true,
            width: 750,
            height: 500,

            buttons: {
                'Close': function() {$(this).dialog('close');}
            }
        });
    }

    function showProgrammeNotes(note_type)
    {
        var programme_id = '<?php echo $inductionProgramme->id; ?>';
        if(programme_id == '')
            return;

        var postData = 'do.php?_action=ajax_tracking'
            + '&programme_id=' + encodeURIComponent(programme_id)
            + '&subaction=' + encodeURIComponent("getInductionProgrammeNotes")
            + '&note_type=' + encodeURIComponent(note_type)
        ;

        var req = ajaxRequest(postData);
        $("<div></div>").html(req.responseText).dialog({
            id: "dlg_lrs_result",
            title: "Saved Comments",
            resizable: false,
            modal: true,
            width: 750,
            height: 500,

            buttons: {
                'Close': function() {$(this).dialog('close');}
            }
        });

    }

    $('#input_dob').change(function()
    {
        $('#lblAgeToday').html(getAge(this.value, ''));
        if($('#input_induction_date').val() != '')
            $('#lblAgeAtInduction').html(getAge(this.value, $('#input_induction_date').val()));
    });

    $('#input_induction_date').change(function()
    {
        $('#lblAgeAtInduction').html(getAge($('#input_dob').val(), this.value));
    });

    function getAge(dateString, dateFrom, yearsOnly)
    {
        if(dateFrom == '')
            var now = new Date();
        else
            var now = stringToDate(dateFrom);

        var today = new Date(now.getYear(),now.getMonth(),now.getDate());
        var yearNow = now.getYear();
        var monthNow = now.getMonth();
        var dateNow = now.getDate();

        /*var dob = new Date(dateString.substring(6,10),
               dateString.substring(0,2)-1,
               dateString.substring(3,5)
           );*/

        var dob = stringToDate(dateString);

        var yearDob = dob.getYear();
        var monthDob = dob.getMonth();
        var dateDob = dob.getDate();
        var age = {};
        var ageString = "";
        var yearString = "";
        var monthString = "";
        var dayString = "";


        yearAge = yearNow - yearDob;

        if (monthNow >= monthDob)
            var monthAge = monthNow - monthDob;
        else {
            yearAge--;
            var monthAge = 12 + monthNow -monthDob;
        }

        if (dateNow >= dateDob)
            var dateAge = dateNow - dateDob;
        else {
            monthAge--;
            var dateAge = 31 + dateNow - dateDob;

            if (monthAge < 0) {
                monthAge = 11;
                yearAge--;
            }
        }

        age = {
            years: yearAge,
            months: monthAge,
            days: dateAge
        };

        if(yearsOnly)
            return age;

        if ( age.years > 1 ) yearString = " years";
        else yearString = " year";
        if ( age.months> 1 ) monthString = " months";
        else monthString = " month";
        if ( age.days > 1 ) dayString = " days";
        else dayString = " day";


        if ( (age.years > 0) && (age.months > 0) && (age.days > 0) )
            ageString = age.years + yearString + ", " + age.months + monthString + ", and " + age.days + dayString;
        else if ( (age.years == 0) && (age.months == 0) && (age.days > 0) )
            ageString = "Only " + age.days + dayString + " old!";
        else if ( (age.years > 0) && (age.months == 0) && (age.days == 0) )
            ageString = age.years + yearString + " old. Happy Birthday!!";
        else if ( (age.years > 0) && (age.months > 0) && (age.days == 0) )
            ageString = age.years + yearString + " and " + age.months + monthString + " old.";
        else if ( (age.years == 0) && (age.months > 0) && (age.days > 0) )
            ageString = age.months + monthString + " and " + age.days + dayString + " old.";
        else if ( (age.years > 0) && (age.months == 0) && (age.days > 0) )
            ageString = age.years + yearString + " and " + age.days + dayString + " old.";
        else if ( (age.years == 0) && (age.months > 0) && (age.days == 0) )
            ageString = age.months + monthString + " old.";
        else ageString = "Oops! Could not calculate age!";

        return ageString;
    }



</script>

</body>
</html>