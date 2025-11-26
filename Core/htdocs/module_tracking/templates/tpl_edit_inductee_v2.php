<?php /* @var $inductee Inductee */ ?>
<?php /* @var $induction Induction */ ?>
<?php /* @var $inductionProgramme InductionProgramme */ ?>

<!DOCTYPE html>

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title><?php echo $inductee->id == '' ? 'Create Induction' : 'Edit Induction'; ?></title>
    <link rel="stylesheet" href="module_tracking/css/common.css" type="text/css" />
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
        #home_postcode {
            text-transform: uppercase
        }

        .ui-datepicker .ui-datepicker-title select {
            color: #000;
        }

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
                <div class="Title" style="margin-left: 6px;"><?php echo $inductee->id == '' ? 'Create Induction' : 'Edit Induction [' . $inductee->firstnames . ' ' . $inductee->surname . ']'; ?></div>
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
                <div class="box-header with-border">
                    <h2 class="box-title">Matching learner(s) found</h2>
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
        <div class="col-sm-12">
            <p class="text-center">
                <?php 
                if(isset($inductionProgramme->programme_id) && $inductionProgramme->programme_id != '' )
                {
                    echo DAO::getSingleValue($link, "SELECT courses.title FROM courses WHERE courses.id = '{$inductionProgramme->programme_id}'"); 
                }
                /*if(isset($inductee->id))
                {
                    echo ' | Inductee ID: ' . $inductee->id;    
                } */                   
                if(isset($induction->id))
                {
                    echo ' | Induction ID: ' . $induction->id;
                }
                ?>
            </p>
        </div>
    </div>	

    <div class="row">
        <div class="col-sm-12">
            <div class="nav-tabs-custom">
                <ul class="nav nav-tabs">
                    <li class="active"><a href="#tabLearner" data-toggle="tab"><span class="text-bold">Learner</span></a></li>
                    <li><a href="#tabInduction" data-toggle="tab"><span class="text-bold">Induction</span></a></li>
                    <li><a href="#tabProgramme" data-toggle="tab"><span class="text-bold">Programme</span></a></li>
                </ul>
                <div class="tab-content">
                    <div class="active tab-pane" id="tabLearner">
                        <form class="form-horizontal" name="frmLearner" action="<?php echo $_SERVER['PHP_SELF']; ?>">
                            <input type="hidden" name="id" value="<?php echo $inductee->id; ?>" />
                            <input type="hidden" name="_action" value="save_induction" />
                            <input type="hidden" name="formName" value="frmLearner" />
                            <div class="box box-primary box-solid">
                                <div class="box-header">
                                    <h5 class="box-title">Learner Information</h5>
                                </div>
                                <div class="box-body">


                                    <div class="row">
                                        <div class="col-sm-6">
                                            <div class="box box-info box-solid">
                                                <div class="box-header with-border">
                                                    <span class="box-title">Employment Details</span>
                                                </div>
                                                <div class="box-body">
                                                    <div class="form-group">
                                                        <label for="input_employment_start_date" class="col-sm-4 control-label fieldLabel_compulsory">Employment Start Date:</label>
                                                        <div class="col-sm-8">
                                                            <?php echo HTML::datebox('employment_start_date', $inductee->employment_start_date, true); ?>
                                                        </div>
                                                    </div>
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
                                                        <label for="emp_crm_contacts" class="col-sm-4 control-label fieldLabel_compulsory">Employer Contacts:</label>
                                                        <div class="col-sm-8">
                                                            <?php echo HTML::selectChosen('emp_crm_contacts', $ddlEmployerContacts, explode(',', $inductee->emp_crm_contacts), false, true, true, 10); ?>
                                                        </div>
                                                    </div>
                                                    <div class="form-group">
                                                        <label for="employer_type" class="col-sm-4 control-label fieldLabel_compulsory">Employer Type:</label>
                                                        <div class="col-sm-8">
                                                            <?php echo HTML::selectChosen('employer_type', InductionHelper::getDDLInducteeEmployerType(), $inductee->employer_type, true, true); ?>
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
                                                        <label for="emp_crm_contacts_notes" class="col-sm-4 control-label fieldLabel_optional">Employer Contacts Notes: <i class="fa fa-comments" title="show saved comments" style="cursor: pointer" onclick="showInducteeNotes('emp_crm_contacts_notes');"></i></label>
                                                        <div class="col-sm-8">
                                                            <textarea class="form-control optional" name="emp_crm_contacts_notes" id="emp_crm_contacts_notes" rows="5"></textarea>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="box box-info box-solid">
                                                <div class="box-header with-border">
                                                    <span class="box-title">LDD</span>
                                                </div>
                                                <div class="box-body">
                                                    <div class="form-group">
                                                        <label for="ldd" class="col-sm-4 control-label fieldLabel_optional">LDD:</label>
                                                        <div class="col-sm-8">
                                                            <?php echo HTML::selectChosen('ldd', InductionHelper::getDDLInductionLdd(), $inductee->ldd, true); ?>
                                                        </div>
                                                    </div>
						    <!-- <div class="form-group">
                                                        <label for="sen_type" class="col-sm-4 control-label fieldLabel_optional">SEN / Mental Health:</label>
                                                        <div class="col-sm-8">
                                                            <?php //echo HTML::selectChosen('sen_type', InductionHelper::getDdlSen(), $inductee->sen_type, true); ?>
                                                        </div>
                                                    </div>
                                                    <div class="form-group">
                                                        <label for="input_sen_date" class="col-sm-4 control-label fieldLabel_optional">Date Informed:</label>
                                                        <div class="col-sm-8">
                                                            <?php //echo HTML::datebox('sen_date', $inductee->sen_date, false); ?>
                                                        </div>
                                                    </div> -->
                                                    <div class="form-group">
                                                        <label for="ldd_comments" class="col-sm-4 control-label fieldLabel_optional">LDD/Support Comments:</label>
                                                        <div class="col-sm-8">
                                                            <textarea class="form-control optional" name="ldd_comments" id="ldd_comments" rows="5"><?php echo $inductee->ldd_comments; ?></textarea>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-sm-6">
                                            <div class="box box-info box-solid">
                                                <div class="box-header with-border">
                                                    <span class="box-title">Basic Details</span>
                                                </div>
                                                <div class="box-body">
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
                                                        <label for="home_telephone" class="col-sm-4 control-label fieldLabel_optional">Mobile:</label>
                                                        <div class="col-sm-8">
                                                            <input type="text" class="form-control optional" name="home_telephone" id="home_telephone" value="<?php echo $inductee->home_telephone; ?>" maxlength="100" />
                                                        </div>
                                                    </div>
                                                    <!-- <div class="form-group">
                                                        <label for="home_mobile" class="col-sm-4 control-label fieldLabel_optional">Mobile:</label>
                                                        <div class="col-sm-8">
                                                            <input type="text" class="form-control optional" name="home_mobile" id="home_mobile" value="<?php //echo $inductee->home_mobile; ?>" maxlength="100" />
                                                        </div>
                                                    </div> -->
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
                                                        <label for="preferred_name" class="col-sm-4 control-label fieldLabel_optional">Preferred Name and/or Pronoun:</label>
                                                        <div class="col-sm-8">
                                                            <input type="text" class="form-control" name="preferred_name" id="preferred_name" value="<?php echo $inductee->preferred_name; ?>" maxlength="70" />
                                                        </div>
                                                    </div>
                                                    
                                                    <div class="form-group">
                                                        <label for="general_comments" class="col-sm-4 control-label fieldLabel_optional">General Comments:</label>
                                                        <div class="col-sm-8">
                                                            <textarea class="form-control optional" name="general_comments" id="general_comments" rows="5" maxlength="800"><?php echo $inductee->general_comments; ?></textarea>
                                                        </div>
                                                    </div>
                                                    <div class="form-group">
                                                        <label for="learner_id" class="col-sm-4 control-label fieldLabel_optional">ID Checked:</label>
                                                        <div class="col-sm-8">
                                                            <?php echo HTML::selectChosen('learner_id', InductionHelper::getDDLLearnerID(), $inductee->learner_id, true); ?>
                                                        </div>
                                                    </div>
                                                    <div class="form-group">
                                                        <label for="inductee_type" class="col-sm-4 control-label fieldLabel_compulsory">Learner Type:</label>
                                                        <div class="col-sm-8">
                                                            <?php echo HTML::selectChosen('inductee_type', InductionHelper::getDDLInducteeTypeV2(), $inductee->inductee_type, true, true); ?>
                                                        </div>
                                                    </div>
						    <!-- <div class="form-group">
                                                        <label for="comp_issue" class="col-sm-4 control-label fieldLabel_optional">Talent Development Candidate:</label>
                                                        <div class="col-sm-8">
                                                            <?php //echo HTML::selectChosen('tdc', $ddlYesNo, $inductee->tdc, true); ?>
                                                        </div>
                                                    </div> -->
						    <!-- <div class="form-group">
                                                        <label for="arm_chance_to_progress" class="col-sm-4 control-label fieldLabel_optional">ARM - Chance to Progress:</label>
                                                        <div class="col-sm-8">
                                                            <?php //echo HTML::selectChosen('arm_chance_to_progress', InductionHelper::getArmChanceToProgressDdl(), $inductee->arm_chance_to_progress, true); ?>
                                                        </div>
                                                    </div>	 -->	
                                                </div>
                                            </div>

                                        </div>
                                    </div>

                                </div>
                                <div class="box-footer">
                                    <button type="button" <?php echo $disabled; ?> class="btn btn-success btn-block" onclick="saveFrmLearner(); "><i class="fa fa-save"></i> <?php echo $inductee->id == '' ? 'Create Learner' : 'Save Learner'; ?></button>
                                </div>
                            </div>
                        </form>
                    </div>
                    <div class="tab-pane" id="tabInduction">
                        <form class="form-horizontal" name="frmLearnerInduction" id="frmLearnerInduction" action="<?php echo $_SERVER['PHP_SELF']; ?>">
                            <input type="hidden" name="id" value="<?php echo $induction->id; ?>" />
                            <input type="hidden" name="inductee_id" value="<?php echo $inductee->id; ?>" />
                            <input type="hidden" name="_action" value="save_induction" />
                            <input type="hidden" name="formName" value="frmLearnerInduction" />
                            <div class="box box-primary box-solid">
                                <div class="box-header">
                                    <h5 class="box-title">Induction Information</h5>
                                </div>
                                <div class="box-body">

                                    <div class="row">
                                        <div class="col-sm-6">
                                            <div class="form-group">
                                                <label for="induction_status" class="col-sm-4 control-label fieldLabel_compulsory">Induction Status:</label>
                                                <div class="col-sm-8">
                                                    <?php echo HTML::selectChosen('induction_status', $ddlInductionStatus, $induction->induction_status, true, true); ?>
                                                </div>
                                            </div>
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
                                                <label for="projected_induction_date" class="col-sm-4 control-label fieldLabel_compulsory">Projected Induction Date:</label>
                                                <div class="col-sm-8">
                                                    <?php echo HTML::datebox('projected_induction_date', $induction->projected_induction_date, true); ?>
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
                                                <label for="input_cohort_date" class="col-sm-4 control-label fieldLabel_optional">Cohort Date:</label>
                                                <div class="col-sm-8">
                                                    <?php echo HTML::datebox('cohort_date', $induction->cohort_date); ?>
                                                </div>
                                            </div>
                                            <?php if(isset($inductionProgramme->programme_id) && $inductionProgramme->programme_id != '' ) { ?>
                                            <div class="form-group">
                                                <label for="input_planned_end_date" class="col-sm-4 control-label fieldLabel_compulsory">Planned End Date:</label>
                                                <div class="col-sm-8">
                                                    <?php //echo HTML::datebox('planned_end_date', $induction->planned_end_date, true); ?>
                                                    <?php echo Date::toShort($induction->planned_end_date); ?>
                                                </div>
                                            </div>
                                            <?php } ?>
                                            <!-- <div class="form-group">
                                                <label for="join_time" class="col-sm-4 control-label fieldLabel_compulsory">Join Time:</label>
                                                <div class="col-sm-8">
                                                    <?php //echo HTML::selectChosen('join_time', InductionHelper::getDDLAMPM(), $induction->join_time); ?>
                                                </div>
                                            </div> -->
                                            <!-- <div class="form-group">
                                                <label for="induction_assessor" class="col-sm-4 control-label fieldLabel_compulsory">Induction Host:</label>
                                                <div class="col-sm-8">
                                                    <?php //echo HTML::selectChosen('induction_assessor', $ddlInductionAssessors, $induction->induction_assessor, true, true); ?>
                                                </div>
                                            </div> -->
                                            <div class="form-group">
                                                <label for="commit_statement" class="col-sm-4 control-label fieldLabel_compulsory">Training Plan:</label>
                                                <div class="col-sm-8">
                                                    <?php echo HTML::selectChosen('commit_statement', InductionHelper::getDDLCommitmentStatementOnly(), $induction->commit_statement, true, true); ?>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label for="enrolment_form" class="col-sm-4 control-label fieldLabel_optional">Eligibility Form:</label>
                                                <div class="col-sm-8">
                                                    <?php echo HTML::selectChosen('enrolment_form', InductionHelper::getDDLCommitmentStatement(), $induction->enrolment_form, true); ?>
                                                </div>
                                            </div>
                                            
                                            <!-- <div class="form-group">
                                                <label for="headset_issued" class="col-sm-4 control-label fieldLabel_optional">Headset Issued:</label>
                                                <div class="col-sm-8">
                                                    <?php //echo HTML::selectChosen('headset_issued', $ddlInductionHeadset, $induction->headset_issued, true); ?>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label for="webcam" class="col-sm-4 control-label fieldLabel_optional">Webcam:</label>
                                                <div class="col-sm-8">
                                                    <?php //echo HTML::selectChosen('webcam', InductionHelper::getDDLWebcam($link), $induction->webcam, true); ?>
                                                </div>
                                            </div> -->
                                            <div class="form-group">
                                                <label for="induction_moved" class="col-sm-4 control-label fieldLabel_optional">Induction Moved:</label>
                                                <div class="col-sm-8">
                                                    <?php echo HTML::selectChosen('induction_moved', InductionHelper::getDdlInductionMoved(), $induction->induction_moved, true); ?>
                                                </div>
                                            </div>
					                        <div class="form-group">
                                                <label for="induction_moved_date" class="col-sm-4 control-label fieldLabel_optional">Induction Moved Date:</label>
                                                <div class="col-sm-8">
                                                    <?php echo HTML::datebox('induction_moved_date', $induction->induction_moved_date, false); ?>
                                                </div>
                                            </div>	
                                            <div class="form-group">
                                                <label for="induction_moved_reason" class="col-sm-4 control-label fieldLabel_optional">Induction Moved Reason:</label>
                                                <div class="col-sm-8">
                                                    <?php echo HTML::selectChosen('induction_moved_reason', InductionHelper::getDdlInductionMovedReason(), $induction->induction_moved_reason, true); ?>
                                                </div>
                                            </div>
					                        <div class="form-group">
                                                <label for="holding_reason" class="col-sm-4 control-label fieldLabel_optional">Holding Induction Reason:</label>
                                                <div class="col-sm-8">
                                                    <?php echo HTML::selectChosen('holding_reason', InductionHelper::getDdlHoldingInductionReason(), $induction->holding_reason, true); ?>
                                                </div>
                                            </div>
					                        <div class="form-group">
                                                <label for="input_date_added_to_hi" class="col-sm-4 control-label fieldLabel_optional">Added to Holding Induction:</label>
                                                <div class="col-sm-8">
                                                    <?php echo HTML::datebox('date_added_to_hi', $induction->date_added_to_hi); ?>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label for="input_date_removed_from_hi" class="col-sm-4 control-label fieldLabel_optional">Removed from Holding Induction:</label>
                                                <div class="col-sm-8">
                                                    <?php echo HTML::datebox('date_removed_from_hi', $induction->date_removed_from_hi); ?>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label for="grey_section_comments" class="col-sm-4 control-label fieldLabel_optional">Holding Induction Comments: <i class="fa fa-comments" title="show saved comments" style="cursor: pointer" onclick="showNotes('grey_section_comments');"></i></label>
                                                <div class="col-sm-8">
                                                    <textarea class="form-control optional" name="grey_section_comments" id="grey_section_comments" rows="5"></textarea>
                                                </div>
                                            </div>
					                        <div class="form-group">
                                                <label for="contact_comments" class="col-sm-4 control-label fieldLabel_optional">Contact Comments: <i class="fa fa-comments" title="show saved comments" style="cursor: pointer" onclick="showNotes('contact_comments');"></i></label>
                                                <div class="col-sm-8">
                                                    <textarea class="form-control optional" name="contact_comments" id="contact_comments" rows="5"></textarea>
                                                </div>
                                            </div>	
                                            <!-- <div class="form-group">
                                                <label for="induction_notes" class="col-sm-4 control-label fieldLabel_optional">Induction Comments: <i class="fa fa-comments" title="show saved comments" style="cursor: pointer" onclick="showNotes('induction_notes');"></i></label>
                                                <div class="col-sm-8">
                                                    <textarea class="form-control optional" name="induction_notes" id="induction_notes" rows="5"></textarea>
                                                </div>
                                            </div> -->
                                            <div class="form-group">
                                                <label for="withdrawn_reason" class="col-sm-4 control-label fieldLabel_optional">Withdrawn Reason: </label>
                                                <div class="col-sm-8">
                                                    <textarea class="form-control optional" name="withdrawn_reason" id="withdrawn_reason" rows="5"><?php echo $induction->withdrawn_reason; ?></textarea>
                                                </div>
                                            </div>
                                            <!-- <div class="form-group">
                                                <label for="input_date_moved_from_grey_section" class="col-sm-4 control-label fieldLabel_optional">Date moved from holding section:</label>
                                                <div class="col-sm-8">
                                                    <?php //echo HTML::datebox('date_moved_from_grey_section', $induction->date_moved_from_grey_section, true); ?>
                                                </div>
                                            </div> -->
                                            <div class="form-group">
                                                <label for="coordinator_notes" class="col-sm-4 control-label fieldLabel_optional">Coach Comments: <i class="fa fa-comments" title="show saved comments" style="cursor: pointer" onclick="showNotes('coordinator_notes');"></i></label>
                                                <div class="col-sm-8">
                                                    <textarea class="form-control optional" name="coordinator_notes" id="coordinator_notes" rows="5"></textarea>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label for="placement_id" class="col-sm-4 control-label fieldLabel_compulsory">Placement ID:</label>
                                                <div class="col-sm-8">
                                                    <input class="form-control compulsory" type="text" name="placement_id" id="placement_id" value="<?php echo $induction->placement_id; ?>" maxlength="70" />
                                                </div>
                                            </div>
                                            <div class="box box-info box-solid">
                                                <div class="box-header with-border">
                                                    <span class="box-title">Related Users</span>
                                                </div>
                                                <div class="box-body">
                                                    <div class="form-group">
                                                        <label for="assigned_assessor" class="col-sm-4 control-label fieldLabel_optional">Coach:</label>
                                                        <div class="col-sm-8">
                                                            <?php echo HTML::selectChosen('assigned_assessor', $ddlAssignedAssessors, $induction->assigned_assessor, true, false); ?>
                                                        </div>
                                                    </div>
                                                    <!-- <div class="form-group">
                                                        <label for="assigned_coord" class="col-sm-4 control-label fieldLabel_optional">Coordinator:</label>
                                                        <div class="col-sm-8">
                                                            <?php //echo HTML::selectChosen('assigned_coord', $ddlAssignedCoordinators, $induction->assigned_coord, true, false); ?>
                                                        </div>
                                                    </div> -->
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
                                                        <label for="resourcer" class="col-sm-4 control-label fieldLabel_compulsory">Candidate Recruiter:</label>
                                                        <div class="col-sm-8">
                                                            <input type="text" class="form-control compulsory" name="resourcer" id="resourcer" value="<?php echo $induction->resourcer; ?>" maxlength="100" />
                                                        </div>
                                                    </div>
                                                    <div class="form-group">
                                                        <label for="emp_recruiter" class="col-sm-4 control-label fieldLabel_compulsory">Employer Recruiter:</label>
                                                        <div class="col-sm-8">
                                                            <input type="text" class="form-control compulsory" name="emp_recruiter" id="emp_recruiter" value="<?php echo $induction->emp_recruiter; ?>" maxlength="100" />
                                                        </div>
                                                    </div>
                                                    <div class="form-group">
                                                        <label for="arm" class="col-sm-4 control-label fieldLabel_compulsory">Account Relationship Manager:</label>
                                                        <div class="col-sm-8">
                                                            <input type="text" class="form-control compulsory" name="arm" id="arm" value="<?php echo $induction->arm; ?>" maxlength="100" />
                                                            <?php //echo HTML::selectChosen('arm', InductionHelper::getDDLInductionARM($link), $induction->arm, true, true); 
                                                            ?>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                        </div>
                                        <div class="col-sm-6">
                                            <div class="box box-info box-solid">
                                                <div class="box-header with-border">
                                                    <span class="box-title">Red Flag Information</span>
                                                </div>
                                                <div class="box-body">
                                                    <div class="form-group">
                                                        <label for="comp_issue" class="col-sm-4 control-label fieldLabel_optional">Red Flag Learner:</label>
                                                        <div class="col-sm-8">
                                                            <?php echo HTML::selectChosen('comp_issue', $ddlYesNo, $induction->comp_issue, false); ?>
                                                        </div>
                                                    </div>
                                                    <div class="form-group">
                                                        <label for="red_le" class="col-sm-4 control-label fieldLabel_optional">Reason:</label>
                                                        <div class="col-sm-8">
                                                            <?php echo HTML::selectChosen('red_le', InductionHelper::getDdlRedFlagReason(), $induction->red_le, true); ?>
                                                        </div>
                                                    </div>
                                                    <div class="form-group">
                                                        <label for="comp_issue_notes" class="col-sm-4 control-label fieldLabel_optional">Red Flag Details:</label>
                                                        <div class="col-sm-8">
                                                            <textarea class="form-control optional" name="comp_issue_notes" id="comp_issue_notes" rows="5"><?php echo $induction->comp_issue_notes; ?></textarea>
                                                        </div>
                                                    </div>
                                                    <div class="form-group">
                                                        <label for="app_opp_concern" class="col-sm-4 control-label fieldLabel_optional">Approved Opportunity Concern:</label>
                                                        <div class="col-sm-8">
                                                            <textarea class="form-control optional" name="app_opp_concern" id="app_opp_concern" rows="5" maxlength="800"><?php echo $induction->app_opp_concern; ?></textarea>
                                                        </div>
                                                    </div>
                                                    <div class="form-group">
                                                        <label for="learner_concerns" class="col-sm-4 control-label fieldLabel_optional">Learner Concerns:</label>
                                                        <div class="col-sm-8">
                                                            <textarea class="form-control optional" name="learner_concerns" id="learner_concerns" rows="5" maxlength="800"><?php echo $induction->learner_concerns; ?></textarea>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="box box-info box-solid">
                                                <div class="box-header with-border">
                                                    <span class="box-title">Funding Information</span>
                                                </div>
                                                <div class="box-body">
                                                    <div class="form-group">
                                                        <label for="levy_payer" class="col-sm-4 control-label fieldLabel_optional">Levy Payer:</label>
                                                        <div class="col-sm-8">
                                                            <?php echo HTML::selectChosen('levy_payer', $ddlYesNo, $induction->levy_payer, true); ?>
                                                        </div>
                                                    </div>
                                                    <?php if($expertProviderPilot){ ?>
                                                    <div class="form-group">
                                                        <label class="col-sm-4 control-label"></label>
                                                        <div class="col-sm-8">
                                                            <span class="text-info"><i class="fa fa-info-circle"></i> Employer is Expert Provider Owner</span>
                                                        </div>
                                                    </div>
                                                    <?php } ?>
                                                    <?php if($expertProviderTransactor){ ?>
                                                    <div class="form-group">
                                                        <label class="col-sm-4 control-label"></label>
                                                        <div class="col-sm-8">
                                                            <span class="text-info"><i class="fa fa-info-circle"></i> Employer is Expert Provider Transactor</span>
                                                        </div>
                                                    </div>
                                                    <?php } ?>
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
                                                        <label for="das_account_created" class="col-sm-4 control-label fieldLabel_optional">DAS Account Created Date:</label>
                                                        <div class="col-sm-8">
                                                        <?php echo HTML::datebox('das_account_created', $induction->das_account_created); ?>
                                                        </div>
                                                    </div>
                                                    <div class="form-group">
                                                        <label for="das_account_contact" class="col-sm-4 control-label fieldLabel_compulsory">Digital Account Contact:</label>
                                                        <div class="col-sm-8">
                                                            <input class="form-control compulsory" type="text" name="das_account_contact" id="das_account_contact" value="<?php echo $induction->das_account_contact; ?>" maxlength="100">
                                                        </div>
                                                    </div>
                                                    <div class="form-group">
                                                        <label for="das_account_telephone" class="col-sm-4 control-label fieldLabel_compulsory">Digital Account Telephone:</label>
                                                        <div class="col-sm-8">
                                                            <input class="form-control compulsory" type="text" name="das_account_telephone" id="das_account_telephone" value="<?php echo $induction->das_account_telephone; ?>" maxlength="50">
                                                        </div>
                                                    </div>
                                                    <div class="form-group">
                                                        <label for="das_account_email" class="col-sm-4 control-label fieldLabel_compulsory">Digital Account Email:</label>
                                                        <div class="col-sm-8">
                                                            <input class="form-control compulsory" type="text" name="das_account_email" id="das_account_email" value="<?php echo $induction->das_account_email; ?>" maxlength="150">
                                                        </div>
                                                    </div>
                                                    <div class="form-group">
                                                        <label for="das_comments" class="col-sm-4 control-label fieldLabel_optional">DAS Comments: <i class="fa fa-comments" title="show saved comments" style="cursor: pointer" onclick="showNotes('das_comments');"></i></label>
                                                        <div class="col-sm-8">
                                                            <textarea class="form-control optional" name="das_comments" id="das_comments" rows="5"></textarea>
                                                        </div>
                                                    </div>
                                                    <!-- <div class="form-group">
                                                        <label for="input_passed_to_admin" class="col-sm-4 control-label fieldLabel_optional">Passed to Admin:</label>
                                                        <div class="col-sm-8">
                                                            <?php //echo HTML::datebox('passed_to_admin', $induction->passed_to_admin); ?>
                                                        </div>
                                                    </div> -->
                                                </div>
                                            </div>
                                            <div class="box box-info box-solid">
                                                <div class="box-header with-border">
                                                    <span class="box-title">Functional Skills</span>
                                                </div>
                                                <div class="box-body">
                                                    <div class="form-group">
                                                        <label for="iag_numeracy" class="col-sm-4 control-label fieldLabel_optional">Numeracy Level:</label>
                                                        <div class="col-sm-8">
                                                            <?php //echo HTML::selectChosen('iag_numeracy', $ddlIAG, $induction->iag_numeracy, true); ?>
                                                            <input class="form-control" type="text" name="iag_numeracy" id="iag_numeracy" value="<?php echo $induction->iag_numeracy; ?>" maxlength="100" />
                                                        </div>
                                                    </div>
                                                    <div class="form-group">
                                                        <label for="iag_literacy" class="col-sm-4 control-label fieldLabel_optional">Literacy Level:</label>
                                                        <div class="col-sm-8">
                                                            <?php //echo HTML::selectChosen('iag_literacy', $ddlIAG, $induction->iag_literacy, true); ?>
                                                            <input class="form-control" type="text" name="iag_literacy" id="iag_literacy" value="<?php echo $induction->iag_literacy; ?>" maxlength="100" />
                                                        </div>
                                                    </div>
                                                    <!-- <div class="form-group">
                                                        <label for="math_cert" class="col-sm-4 control-label fieldLabel_optional">Maths Certificate:</label>
                                                        <div class="col-sm-8">
                                                            <?php //echo HTML::selectChosen('math_cert', InductionHelper::getDdlCerts(), $induction->math_cert, true); ?>
                                                        </div>
                                                    </div>
                                                    <div class="form-group">
                                                        <label for="eng_cert" class="col-sm-4 control-label fieldLabel_optional">English Certificate:</label>
                                                        <div class="col-sm-8">
                                                            <?php //echo HTML::selectChosen('eng_cert', InductionHelper::getDdlCerts(), $induction->eng_cert, true); ?>
                                                        </div>
                                                    </div> -->
                                                    <div class="form-group">
                                                        <label for="wfd_assessment" class="col-sm-4 control-label fieldLabel_optional">Functional Skills Exemption Status English:</label>
                                                        <div class="col-sm-8">
                                                            <?php echo HTML::selectChosen('wfd_assessment', InductionHelper::getDdlYesNoFsExempt(), $induction->wfd_assessment, true); ?>
                                                        </div>
                                                    </div>
                                                    <div class="form-group">
                                                        <label for="maths_gcse_elig_met" class="col-sm-4 control-label fieldLabel_optional">Functional Skills Exemption Status Maths:</label>
                                                        <div class="col-sm-8">
                                                            <?php echo HTML::selectChosen('maths_gcse_elig_met', InductionHelper::getDdlYesNoFsExempt(), $induction->maths_gcse_elig_met, true); ?>
                                                        </div>
                                                    </div>
                                                    <!-- <div class="form-group">
                                                        <label for="maths_gcse_grade" class="col-sm-4 control-label fieldLabel_optional">Maths GCSE Grade:</label>
                                                        <div class="col-sm-8">
                                                            <input class="form-control" type="text" name="maths_gcse_grade" id="maths_gcse_grade" value="<?php //echo $induction->maths_gcse_grade; ?>" maxlength="70" />
                                                        </div>
                                                    </div>
                                                    <div class="form-group">
                                                        <label for="eng_gcse_grade" class="col-sm-4 control-label fieldLabel_optional">English GCSE Grade:</label>
                                                        <div class="col-sm-8">
                                                            <input class="form-control" type="text" name="eng_gcse_grade" id="eng_gcse_grade" value="<?php //echo $induction->eng_gcse_grade; ?>" maxlength="70" />
                                                        </div>
                                                    </div>
                                                    <div class="form-group">
                                                        <label for="sci_gcse_grade" class="col-sm-4 control-label fieldLabel_optional">Science GCSE Grade:</label>
                                                        <div class="col-sm-8">
                                                            <input class="form-control" type="text" name="sci_gcse_grade" id="sci_gcse_grade" value="<?php //echo $induction->sci_gcse_grade; ?>" maxlength="70" />
                                                        </div>
                                                    </div>
                                                    <div class="form-group">
                                                        <label for="it_gcse_grade" class="col-sm-4 control-label fieldLabel_optional">IT GCSE Grade:</label>
                                                        <div class="col-sm-8">
                                                            <input class="form-control" type="text" name="it_gcse_grade" id="it_gcse_grade" value="<?php //echo $induction->it_gcse_grade; ?>" maxlength="70" />
                                                        </div>
                                                    </div> -->
                                                </div>
                                            </div>

                                        </div>
                                    </div>

                                </div>
                                <div class="box-footer">
                                    <button id="btnSaveFrmLearnerInduction" <?php echo $disabled; ?> type="button" class="btn btn-success btn-block" onclick="saveFrmLearnerInduction(); "><i class="fa fa-save"></i> Save Induction</button>
                                </div>
                            </div>

                        </form>
                    </div>
                    <div class="tab-pane" id="tabProgramme">
                        <form class="form-horizontal" name="frmLearnerProgramme" id="frmLearnerProgramme" action="<?php echo $_SERVER['PHP_SELF']; ?>">
                            <input type="hidden" name="id" value="<?php echo $inductionProgramme->id; ?>" />
                            <input type="hidden" name="inductee_id" value="<?php echo $inductee->id; ?>" />
                            <input type="hidden" name="_action" value="save_induction" />
                            <input type="hidden" name="formName" value="frmLearnerProgramme" />
                            <div class="row">
                                <div class="col-sm-6">
                                    <div class="box box-primary box-solid">
                                        <div class="box-header">
                                            <h5 class="box-title">Induction Programme Information</h5>
                                        </div>
                                        <div class="box-body">

                                            <div class="form-group">
                                                <label for="programme_id" class="col-sm-3 control-label fieldLabel_compulsory">Programme:</label>
                                                <div class="col-sm-9">
                                                    <?php echo HTML::selectChosen('programme_id', $ddlCourseProgramme, $inductionProgramme->programme_id, true, true); ?>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label for="data_pathway" class="col-sm-3 control-label fieldLabel_optional">Data Pathway:</label>
                                                <div class="col-sm-9">
                                                    <?php echo HTML::selectChosen('data_pathway', InductionHelper::getDataPathwayDdl(), $inductionProgramme->data_pathway, true); ?>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label for="it_pathway" class="col-sm-3 control-label fieldLabel_optional">IT Pathway:</label>
                                                <div class="col-sm-9">
                                                    <?php echo HTML::selectChosen('it_pathway', InductionHelper::getITPathwayDdl(), $inductionProgramme->it_pathway, true); ?>
                                                </div>
                                            </div>
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
                                            <!-- <div class="form-group">
                                                <label for="related_quals" class="col-sm-4 control-label fieldLabel_optional">Related Qualifications:</label>
                                                <div class="col-sm-8">
                                                    <?php //echo HTML::selectChosen('related_quals', InductionHelper::getDdlRelatedQualifications($link), $inductionProgramme->related_quals, true); ?>
                                                </div>
                                            </div> -->
                                            <!-- <div class="form-group">
                                                <label for="programme_notes" class="col-sm-4 control-label fieldLabel_compulsory">Comments: <i class="fa fa-comments" title="show saved comments" style="cursor: pointer" onclick="showProgrammeNotes('programme_notes');"></i></label>
                                                <div class="col-sm-8">
                                                    <textarea class="form-control compulsory" name="programme_notes" id="programme_notes" rows="5"></textarea>
                                                </div>
                                            </div> -->

                                        </div>
                                        
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                <div class="box box-primary box-solid">
                                        <div class="box-header">
                                            <h5 class="box-title">Coordinators</h5>
                                        </div>
                                        <div class="box-body">

                                            <!-- <div class="form-group">
                                                <label for="coordinator_notes_program" class="col-sm-4 control-label fieldLabel_optional">Coordinator Comments:  <i class="fa fa-comments" title="show saved comments" style="cursor: pointer" onclick="showProgrammeNotes('coordinator_notes_program');"></i></label>
                                                <div class="col-sm-8">
                                                    <textarea class="form-control optional" name="coordinator_notes_program" id="coordinator_notes_program" rows="5"></textarea>
                                                </div>
                                            </div> -->
                                            <!-- <div class="form-group">
                                                <label for="skills_scan" class="col-sm-4 control-label fieldLabel_optional">Skills Scan:</label>
                                                <div class="col-sm-8">
                                                    <?php //echo HTML::selectChosen('skills_scan', InductionHelper::getDdlIpSkillsScan($link), $inductionProgramme->skills_scan, true); ?>
                                                </div>
                                            </div> -->
                                            <div class="form-group">
                                                <label for="skills_scan_grade" class="col-sm-4 control-label fieldLabel_optional">Skills Scan Grade:</label>
                                                <div class="col-sm-8">
                                                <input type="text" class="form-control optional" name="skills_scan_grade" id="skills_scan_grade" value="<?php echo $inductionProgramme->skills_scan_grade; ?>" maxlength="50" />
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label for="funding_reduction" class="col-sm-4 control-label fieldLabel_optional">Funding Reduction:</label>
                                                <div class="col-sm-8">
                                                    <?php echo HTML::selectChosen('funding_reduction', InductionHelper::getDdlFundingReduction(), explode(',', $inductionProgramme->funding_reduction), true, false, true); ?>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label for="funding_reduction_further_details" class="col-sm-4 control-label fieldLabel_optional">Funding Reduction: Further Details:  </label>
                                                <div class="col-sm-8">
                                                    <textarea class="form-control optional" name="funding_reduction_further_details" id="funding_reduction_further_details" rows="5" maxlength="800"><?php echo $inductionProgramme->funding_reduction_further_details; ?></textarea>
                                                </div>
                                            </div>
                                            <!-- <div class="form-group">
                                                <label for="funding_reduction_other" class="col-sm-4 control-label fieldLabel_optional">Other (please explain):  </label>
                                                <div class="col-sm-8">
                                                    <textarea class="form-control optional" name="funding_reduction_other" id="funding_reduction_other" rows="5" maxlength="800"><?php //echo $inductionProgramme->funding_reduction_other; ?></textarea>
                                                </div>
                                            </div> -->
                                            <!-- <div class="form-group">
                                                <label for="prior_quals_further_details" class="col-sm-4 control-label fieldLabel_optional">Prior Quals: Further Details:</label>
                                                <div class="col-sm-8">
                                                    <textarea class="form-control optional" name="prior_quals_further_details" id="prior_quals_further_details" rows="5" maxlength="800"><?php echo $inductionProgramme->prior_quals_further_details; ?></textarea>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label for="employer_agreed_reduction_further_details" class="col-sm-4 control-label fieldLabel_optional">Employer Agreed Reduction: Further Details:</label>
                                                <div class="col-sm-8">
                                                    <textarea class="form-control optional" name="employer_agreed_reduction_further_details" id="employer_agreed_reduction_further_details" rows="5" maxlength="800"><?php echo $inductionProgramme->employer_agreed_reduction_further_details; ?></textarea>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label for="prev_app_further_details" class="col-sm-4 control-label fieldLabel_optional">Previous Apprenticeship: Further Details:</label>
                                                <div class="col-sm-8">
                                                    <textarea class="form-control optional" name="prev_app_further_details" id="prev_app_further_details" rows="5" maxlength="800"><?php echo $inductionProgramme->prev_app_further_details; ?></textarea>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label for="admin_error_details" class="col-sm-4 control-label fieldLabel_optional">Admin/Processing Error: Further Details:</label>
                                                <div class="col-sm-8">
                                                    <textarea class="form-control optional" name="admin_error_details" id="admin_error_details" rows="5" maxlength="800"><?php echo $inductionProgramme->admin_error_details; ?></textarea>
                                                </div>
                                            </div> -->
                                            <div class="form-group">
                                                <label for="reduction_price" class="col-sm-4 control-label fieldLabel_optional">Reduction Price:</label>
                                                <div class="col-sm-8">
                                                    <input class="form-control" type="text" name="reduction_price" id="reduction_price" value="<?php echo $inductionProgramme->reduction_price; ?>" onkeypress="return numbersonly();" maxlength="5" />
                                                </div>
                                            </div>
                                            <!-- <div class="form-group">
                                                <label for="call_arranged_for" class="col-sm-4 control-label fieldLabel_optional">Call Arranged For:</label>
                                                <div class="col-sm-8">
                                                    <?php //echo HTML::datebox('call_arranged_for', $inductionProgramme->call_arranged_for); ?>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label for="ip_status" class="col-sm-4 control-label fieldLabel_optional">Status:</label>
                                                <div class="col-sm-8">
                                                    <?php //echo HTML::selectChosen('ip_status', InductionHelper::getDdlIpStatus(), $inductionProgramme->ip_status, true); ?>
                                                </div>
                                            </div> -->
                                            <div class="form-group">
                                                <label for="prior_experience_further_details" class="col-sm-4 control-label fieldLabel_optional">Prior Experience: Further Details:</label>
                                                <div class="col-sm-8">
                                                    <?php echo HTML::selectChosen('prior_experience_further_details', InductionHelper::getDdlPriorExpFurtherDetails(), $inductionProgramme->prior_experience_further_details, true); ?>
                                                </div>
                                            </div>
                                            <!-- <div class="form-group">
                                                <label for="reduction_keyed_by_admin" class="col-sm-4 control-label fieldLabel_optional">Reduction Keyed by Admin:</label>
                                                <div class="col-sm-8">
                                                    <input type="checkbox" name="reduction_keyed_by_admin" value="1" <?php //echo $inductionProgramme->reduction_keyed_by_admin == 1 ? 'checked' : ''; ?> /><label>Reduction Keyed by Admin</label>
                                                </div>
                                            </div> -->

                                        </div>
                                        
                                    </div>
                                </div>
                                <div class="col-sm-12">
                                    <button id="btnSaveFrmLearnerProgramme" <?php echo $disabled; ?> type="button" class="btn btn-success btn-block" onclick="saveFrmLearnerProgramme(); "><i class="fa fa-save"></i> Save Programme</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
	    <span class="text-info small"><?php echo $inductee->sf_Id; ?></span>
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

            $('input[type=checkbox]').each(function() {
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

            $('#employer_id').chosen({
                placeholder_text_single: "Select an employer"
            });
            $('#emp_crm_contacts').chosen();//$('#funding_reduction').chosen();

            $('#input_dob').attr('class', 'datepicker compulsory form-control');
            $('#input_induction_date').attr('class', 'datepicker compulsory form-control');
	    $('#input_cohort_date').attr('class', 'datepicker optional form-control');
            $('#input_nine_month_end').attr('class', 'datepicker compulsory form-control');
            //$('#input_planned_end_date').attr('class', 'datepicker compulsory form-control');
	        $('#input_date_removed_from_hi, #input_date_added_to_hi').attr('class', 'datepicker form-control');	
            $('#input_employment_start_date, #input_projected_induction_date').attr('class', 'datepicker compulsory form-control');
            $('#input_date_moved_from_grey_section').attr('class', 'datepicker optional form-control');
            $('#input_induction_arranged').attr('class', 'datepicker optional form-control');

            $(".timebox").timepicker({
                timeFormat: 'H:i'
            });

            $('.timebox').bind('timeFormatError timeRangeError', function() {
                this.value = '';
                alert("Please choose a valid time");
                this.focus();
            });

            <?php if ($inductee->id == '') { ?>
                $('#frmLearnerInduction').find(':input:not(:disabled)').prop('disabled', true);
            <?php } ?>
            <?php if ($induction->id == '') { ?>
                $('#frmLearnerProgramme').find(':input:not(:disabled)').prop('disabled', true);
            <?php } ?>

            var disabled_induction_assessors = [<?php echo DAO::getSingleValue($link, "SELECT GROUP_CONCAT(user_id) FROM lookup_induction_assessors WHERE enabled = 'N'") ?>];
            $("#induction_assessor option").each(function() {
                if ($.inArray(parseInt(this.value), disabled_induction_assessors) != -1)
                    $(this).attr('disabled', 'disabled');
            });
            var disabled_assigned_assessors = [<?php echo DAO::getSingleValue($link, "SELECT GROUP_CONCAT(user_id) FROM lookup_assigned_assessors WHERE enabled = 'N'") ?>];
            $("#assigned_assessor option").each(function() {
                if ($.inArray(parseInt(this.value), disabled_assigned_assessors) != -1)
                    $(this).attr('disabled', 'disabled');
            });
            // var disabled_delivery_locations = [<?php //echo DAO::getSingleValue($link, "SELECT GROUP_CONCAT(id) FROM lookup_delivery_locations WHERE enabled = 'N'")
                                                    ?>];
            // $("#location_area option").each(function(){
            //     if($.inArray(parseInt(this.value), disabled_delivery_locations) != -1)
            //         $(this).attr('disabled', 'disabled');
            // });
            var disabled_assigned_coord = [24233, 28444, 22988, 27362, 22552, 21097, 27443];
            $("#assigned_coord option").each(function() {
                if ($.inArray(parseInt(this.value), disabled_assigned_coord) != -1)
                    $(this).attr('disabled', 'disabled');
            });
	    var disabled_induction_owners = [<?php echo DAO::getSingleValue($link, "SELECT GROUP_CONCAT(user_id) FROM lookup_induction_owners WHERE enabled = 'N'") ?>];
            $("#induction_owner option").each(function() {
                if ($.inArray(parseInt(this.value), disabled_induction_owners) != -1)
                    $(this).attr('disabled', 'disabled');
            });

            if (window.phpInductionStatus == 'C') {
                $('#divIssueWithReason').show();
            }

	    $("#inductee_type option").each(function() {
                if ($.inArray(this.value, ['DXC', 'HOET']) != -1)
                    $(this).attr('disabled', 'disabled');
            });
        });

        /*
        $('#induction_status').change(function(){
            if($(this).val() == 'C')
                $('#divIssueWithReason').show();
            else
                $('#divIssueWithReason').hide();
        });
        */

        function saveFrmLearnerProgramme() {
            $("#btnSaveFrmLearnerProgramme").prop("disabled", true);
            var myForm = document.forms["frmLearnerProgramme"];
            if (validateForm(myForm) == false) {
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

        function saveFrmLearnerInduction() {
            $("#btnSaveFrmLearnerInduction").prop("disabled", true);
            var myForm = document.forms["frmLearnerInduction"];
            if (validateForm(myForm) == false) {
                $("#btnSaveFrmLearnerInduction").prop("disabled", false);
                return false;
            }
            if ($('#induction_status').val() == 'S' && $('#levy_payer').val() == '') {
                alert('Please select levy payer option in order to update induction status to \'Scheduled\'');
                $('#levy_payer').focus();
                $("#btnSaveFrmLearnerInduction").prop("disabled", false);
                return;
            }
	    if ($('#induction_status').val() == 'S' && $('#assigned_assessor').val() == '') {
                alert("Please select the option in Coach dropdown list in Related Users panel of Induction tab. ");
                $('#assigned_assessor').focus();
                return false;
            }	
            if ($('#induction_status').val() == 'C') {
                var validation = '';
                //if($('#ni').val().trim() == '')
                //	validation += '- National insurance number is blank<br>';
                if ($('#employer_id').val() == '')
                    validation += '- Employer is not selected<br>';
                if ($('#input_induction_date').val() == '')
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
                if ($('#commit_statement').val() != 'FC')
                    validation += '- Training Plan should be \'Fully Completed\'<br>';
                if ($('#planned_end_date').val() == '')
                    validation += '- Planned end date is blank<br>';
                if ($('#comp_issue').val() == 'Y' && $('#comp_issue_notes').val().trim() == '')
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
                if (validation != '') {
                    $("#btnSaveFrmLearnerInduction").prop("disabled", false);
                    $("<div></div>").html(validation).dialog({
                        id: "dlg_lrs_result",
                        title: "Validation errors for status Completed",
                        resizable: false,
                        modal: true,
                        width: 750,
                        height: 500,

                        buttons: {
                            'Close': function() {
                                $(this).dialog('close');
                            }
                        }
                    });
                } else {
                    myForm.submit();
                }
            } else {
                myForm.submit();
            }
        }

        function saveFrmLearner() {
            var myForm = document.forms["frmLearner"];
            if (validateForm(myForm) == false) {
                return false;
            }
            if ($('#home_email').val().trim() != '' && validateEmail($('#home_email').val().trim()) == false) {
                alert('Incorrect format for learner\'s email');
                $('#home_email').focus();
                return false;
            }
            if ($('#ni').val().trim() != '' && validateNI($('#ni').val().trim()) == false) {
                alert('Incorrect format for learner\'s National Insurance');
                $('#ni').focus();
                return false;
            }

            <?php if ($inductee->id == '') { ?>
                var parameters = '&firstnames=' + encodeURIComponent(myForm.elements["firstnames"].value) +
                    '&surname=' + encodeURIComponent(myForm.elements["surname"].value) +
                    '&dob=' + encodeURIComponent(myForm.elements["dob"].value) +
                    '&subaction=' + encodeURIComponent("checkNewInducteeDuplicates");

                var req = ajaxRequest('do.php?_action=ajax_tracking' + parameters, null, null, saveFrmLearnerCallback);
            <?php } else { ?>
                myForm.submit();
            <?php } ?>
        }

        function saveFrmLearnerCallback(req) {
            if (req.status == '200') {
                if (req.responseText == '') {
                    document.forms["frmLearner"].submit();
                } else {
                    var html = '';
                    html = '<table class="table table-responsive">' +
                        '<thead><tr><th>&nbsp;</th><th>Inductee ID</th><th>Creation Date</th><th>First Name(s)</th><th>Surname</th><th>DOB</th><th>Postcode</th><th>Created By</th></tr></thead>' +
                        '<tbody>';

                    var myObject = eval('(' + req.responseText + ')');
                    for (var i in myObject) {
                        html += '<tr>';
                        html += '<td><span class="btn btn-info btn-sm" onclick="window.location.href=\'do.php?_action=edit_inductee&id=' + myObject[i]['id'] + '\'" ><i class="fa fa-folder-open"></i> Open</span> &nbsp;</td>';
                        html += '<td>' + myObject[i]["id"] + '</td>' +
                            '<td>' + formatDate(myObject[i]["created"], true) + '</td>' +
                            '<td>' + myObject[i]["firstnames"] + '</td>' +
                            '<td>' + myObject[i]["surname"] + '</td>' +
                            '<td>' + formatDate(myObject[i]["dob"], false) + '</td>' +
                            /*'<td>' + myObject[i]["home_postcode"] + '</td>' +*/
                            '<td>' + myObject[i]["created_by"] + '</td>';
                        html += '</tr>';
                    }
                    html += '</tbody></table>';
                    $('#tbl_duplicate_records').html(html);
                    $('#messageBox').show();
                    $(this).scrollTop(0);
                }
            } else {
                alert(req.responseText);
            }
            $('.loading-gif').hide();
        }

        function employer_id_onchange(employer, event) {
            var f = location.form;

            var employer_locations = document.getElementById('employer_location_id');
            var emp_crm_contacts = document.getElementById('emp_crm_contacts');

            if (employer.value != '') {
                employer.disabled = true;

                employer_locations.disabled = true;
                ajaxPopulateSelect(employer_locations, 'do.php?_action=ajax_tracking&subaction=load_employer_locations&employer_id=' + employer.value);
                employer_locations.disabled = false;

                emp_crm_contacts.disabled = true;
                ajaxPopulateSelect(emp_crm_contacts, 'do.php?_action=ajax_tracking&subaction=load_employer_contacts&employer_id=' + employer.value);
                $('#emp_crm_contacts').attr('disabled', false).trigger("chosen:updated");
                emp_crm_contacts.disabled = false;

                employer.disabled = false;
            } else {
                emptySelectElement(employer_locations);
                emptySelectElement(emp_crm_contacts);
            }
        }

        function showInducteeNotes(note_type) {
            var inductee_id = '<?php echo $inductee->id; ?>';
            if (inductee_id == '')
                return;

            var postData = 'do.php?_action=ajax_tracking' +
                '&inductee_id=' + encodeURIComponent(inductee_id) +
                '&subaction=' + encodeURIComponent("getInducteeNotes") +
                '&note_type=' + encodeURIComponent(note_type);

            var req = ajaxRequest(postData);
            $("<div></div>").html(req.responseText).dialog({
                id: "dlg_lrs_result",
                title: "Saved Comments",
                resizable: false,
                modal: true,
                width: 750,
                height: 500,

                buttons: {
                    'Close': function() {
                        $(this).dialog('close');
                    }
                }
            });
        }

        function showNotes(note_type) {
            var induction_id = '<?php echo $induction->id; ?>';
            if (induction_id == '')
                return;

            var postData = 'do.php?_action=ajax_tracking' +
                '&induction_id=' + encodeURIComponent(induction_id) +
                '&subaction=' + encodeURIComponent("getInductionNotes") +
                '&note_type=' + encodeURIComponent(note_type);

            var req = ajaxRequest(postData);
            $("<div></div>").html(req.responseText).dialog({
                id: "dlg_lrs_result",
                title: "Saved Comments",
                resizable: false,
                modal: true,
                width: 750,
                height: 500,

                buttons: {
                    'Close': function() {
                        $(this).dialog('close');
                    }
                }
            });
        }

        function showProgrammeNotes(note_type) {
            var programme_id = '<?php echo $inductionProgramme->id; ?>';
            if (programme_id == '')
                return;

            var postData = 'do.php?_action=ajax_tracking' +
                '&programme_id=' + encodeURIComponent(programme_id) +
                '&subaction=' + encodeURIComponent("getInductionProgrammeNotes") +
                '&note_type=' + encodeURIComponent(note_type);

            var req = ajaxRequest(postData);
            $("<div></div>").html(req.responseText).dialog({
                id: "dlg_lrs_result",
                title: "Saved Comments",
                resizable: false,
                modal: true,
                width: 750,
                height: 500,

                buttons: {
                    'Close': function() {
                        $(this).dialog('close');
                    }
                }
            });

        }

        $('#input_dob').change(function() {
            $('#lblAgeToday').html(getAge(this.value, ''));
            if ($('#input_induction_date').val() != '')
                $('#lblAgeAtInduction').html(getAge(this.value, $('#input_induction_date').val()));
        });

        $('#input_induction_date').change(function() {
            $('#lblAgeAtInduction').html(getAge($('#input_dob').val(), this.value));
        });

        function getAge(dateString, dateFrom, yearsOnly) {
            if (dateFrom == '')
                var now = new Date();
            else
                var now = stringToDate(dateFrom);

            var today = new Date(now.getYear(), now.getMonth(), now.getDate());
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
                var monthAge = 12 + monthNow - monthDob;
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

            if (yearsOnly)
                return age;

            if (age.years > 1) yearString = " years";
            else yearString = " year";
            if (age.months > 1) monthString = " months";
            else monthString = " month";
            if (age.days > 1) dayString = " days";
            else dayString = " day";


            if ((age.years > 0) && (age.months > 0) && (age.days > 0))
                ageString = age.years + yearString + ", " + age.months + monthString + ", and " + age.days + dayString;
            else if ((age.years == 0) && (age.months == 0) && (age.days > 0))
                ageString = "Only " + age.days + dayString + " old!";
            else if ((age.years > 0) && (age.months == 0) && (age.days == 0))
                ageString = age.years + yearString + " old. Happy Birthday!!";
            else if ((age.years > 0) && (age.months > 0) && (age.days == 0))
                ageString = age.years + yearString + " and " + age.months + monthString + " old.";
            else if ((age.years == 0) && (age.months > 0) && (age.days > 0))
                ageString = age.months + monthString + " and " + age.days + dayString + " old.";
            else if ((age.years > 0) && (age.months == 0) && (age.days > 0))
                ageString = age.years + yearString + " and " + age.days + dayString + " old.";
            else if ((age.years == 0) && (age.months > 0) && (age.days == 0))
                ageString = age.months + monthString + " old.";
            else ageString = "Oops! Could not calculate age!";

            return ageString;
        }
    </script>

</body>

</html>