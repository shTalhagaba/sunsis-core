<?php /* @var $tr TrainingRecord */ ?>
<?php /* @var $op_details TROperationsVO */ ?>
<?php /* @var $inductee Inductee */ ?>
<!DOCTYPE html>

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Operations Details of Learner</title>
    <link rel="stylesheet" href="module_tracking/css/common.css" type="text/css" />
    <link rel="stylesheet" href="/assets/adminlte/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.5.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="/assets/adminlte/plugins/chosen/bootstrap-chosen.css">
    <link rel="stylesheet" href="/assets/adminlte/plugins/jQueryUI/jquery-ui.css">
    <link rel="stylesheet" href="/assets/adminlte/plugins/iCheck/all.css">
    <link rel="stylesheet" href="/assets/adminlte/dist/css/AdminLTE.min.css">
    <link rel="stylesheet" href="/assets/adminlte/plugins/summernote/summernote.css">
    <link rel="stylesheet" href="/assets/adminlte/plugins/summernote/summernote-bs3.css">
    <link href="/assets/adminlte/plugins/datatables/jquery.dataTables.css" rel="stylesheet">

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

        .loading-image {
            background-image: url('images/progress-animations/loading51.gif');
            background-color: rgba(255, 255, 255, 0.5);
            background-position: center center;
            background-repeat: no-repeat;
            filter: progid:DXImageTransform.Microsoft.gradient(startColorstr=#50FFFFFF, endColorstr=#50FFFFFF);
            width: 100%;
            height: 100%;
            position: fixed;
            top: 0;
            left: 0;
            z-index: 9999;
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
                <div class="Title" style="margin-left: 6px;">View Edit Operations Details of Learner</div>
                <div class="ButtonBar">
                    <span class="btn btn-sm btn-default" onclick="window.location.href='<?php echo $_SESSION['bc']->getPrevious(); ?>';"><i class="fa fa-arrow-circle-o-left"></i> Cancel</span>
                    <span class="btn btn-sm btn-default" onclick="window.location.href='do.php?_action=read_training_record&id=<?php echo $tr->id; ?>';"><i class="fa fa-folder-open"></i> View Training</span>
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
        <div class="col-md-3">
            <div class="well well-sm">
                <h2><?php echo htmlspecialchars((string)$tr->firstnames) . ' ' . htmlspecialchars(strtoupper($tr->surname)); ?></h2>
                <ul class="list-unstyled">
                    <?php echo trim($tr->home_email) != '' ? '<li><span class="fa fa-envelope"></span><a href="mailto:' . htmlspecialchars((string)$tr->home_email) . '"> ' . htmlspecialchars((string)$tr->home_email) . '</a> <span class="label label-info">Personal</span></li>' : ''; ?>
                    <?php echo trim($tr->learner_work_email) != '' ? '<li><span class="fa fa-envelope"></span><a href="mailto:' . htmlspecialchars((string)$tr->learner_work_email) . '"> ' . htmlspecialchars((string)$tr->learner_work_email) . '</a> <span class="label label-info">Work</span></li>' : ''; ?>
                    <?php echo trim($tr->home_telephone) != '' ? '<li><span class="fa fa-phone"></span> ' . htmlspecialchars((string)$tr->home_telephone) . '</li>' : ''; ?>
                    <?php echo trim($tr->home_mobile) != '' ? '<li><span class="fa fa-mobile-phone"></span> ' . htmlspecialchars((string)$tr->home_mobile) . '</li>' : ''; ?>
                    <?php echo trim($tr->work_telephone) != '' ? '<li><span class="fa fa-bank"></span> <span class="fa fa-phone"></span> ' . htmlspecialchars((string)$tr->work_telephone) . '</li>' : ''; ?>
                    <?php echo trim($tr->work_mobile) != '' ? '<li><span class="fa fa-bank"></span> <span class="fa fa-mobile-phone"></span> ' . htmlspecialchars((string)$tr->work_mobile) . '</li>' : ''; ?>
                </ul>
                <hr>
                <table class="table">
                    <col width="120" />
                    <?php
                    echo isset($induction->induction_date) ? '<tr><th>Induction Date:</th><td>' . Date::toShort($induction->induction_date) . '</td></tr>' : '';
                    ?>
                    <tr>
                        <th>Gender:</th>
                        <td><?php echo htmlspecialchars((string)$listGender[$tr->gender]); ?></td>
                    </tr>
                    <tr>
                        <th>Date of Birth:</th>
                        <td>
                            <?php
                            echo htmlspecialchars(Date::toMedium($tr->dob));
                            if ($tr->dob) {
                                echo '<span style="margin-left:30px;color:gray"></span><br><label class="label label-info">' . Date::dateDiff(date("Y-m-d"), $tr->dob) . '</label>';
                            }
                            ?>
                        </td>
                    </tr>
                    <tr>
                        <th>NI Number:</th>
                        <td><?php echo htmlspecialchars((string)$tr->ni); ?></td>
                    </tr>
                    <tr>
                        <th>Learner Ref.:</th>
                        <td><?php echo htmlspecialchars((string)$tr->l03); ?></td>
                    </tr>
                    <tr>
                        <th>ULN:</th>
                        <td><?php echo htmlspecialchars((string)$tr->uln); ?></td>
                    </tr>
                    <tr>
                        <td colspan="2" class="small"><?php echo DAO::getSingleValue($link, "SELECT title FROM student_frameworks WHERE tr_id = '{$tr->id}'"); ?></td>
                    </tr>
                </table>
            </div>
            <div class="box box-primary">
                <div class="box-header">
                    <?php
                    $exists = DAO::getSingleValue($link, "SELECT COUNT(*) FROM complaints INNER JOIN tr ON complaints.record_id = tr.id INNER JOIN organisations ON tr.employer_id = organisations.id WHERE organisations.id = '{$employer->id}'");
                    if ($exists > 0)
                        echo '<span class="bg-red" style="padding: 2px; border-radius: 3px;"> <i class="fa fa-warning" title="complaints for this employer"></i></span>';
                    ?>
                    <strong><i class="fa fa-map-marker margin-r-5"></i> Employer Details</strong><span class="btn btn-xs btn-primary pull-right" onclick="window.location.replace('do.php?_action=baltic_read_employer&id=<?php echo $employer->id; ?>')">Employer</span>
                </div>
                <div class="box-body no-padding" style="margin-left: 10px;">
                    <address class="small">
                        <?php
                        echo $employer->legal_name . '<br>';
                        echo trim($employer_location->address_line_1) != '' ? htmlspecialchars((string)$employer_location->address_line_1) . '<br>' : '';
                        echo trim($employer_location->address_line_2) != '' ? htmlspecialchars((string)$employer_location->address_line_2) . '<br>' : '';
                        echo trim($employer_location->address_line_3) != '' ? htmlspecialchars((string)$employer_location->address_line_3) . '<br>' : '';
                        echo trim($employer_location->address_line_4) != '' ? htmlspecialchars((string)$employer_location->address_line_4) . '<br>' : '';
                        echo trim($employer_location->postcode) != '' ? htmlspecialchars((string)$employer_location->postcode) . '<br>' : '';
                        echo trim($employer_location->telephone) != '' ? '<span class="fa fa-phone"></span> ' . htmlspecialchars((string)$employer_location->telephone) . '<br>' : '';
                        ?>
                    </address>
                    <p class="bg-red">
                        <?php
                        $not_linked = DAO::getObject($link, "SELECT not_linked, not_linked_comments FROM organisations WHERE id = '{$employer->id}'");
                        if (isset($not_linked->not_linked) && $not_linked->not_linked == '1') {
                            echo '&nbsp; <i class="fa fa-warning"></i> No longer working with this employer';
                        }
                        ?>
                    </p>
                </div>
            </div>
            <div class="box table-responsive">
                <table class="table table-responsive row-border small">
                    <thead>
                        <tr>
                            <th>Main<br>Contact</th>
                            <th>Contact Name</th>
                            <th>Tel.</th>
                            <th>Mobile</th>
                            <th>Email</th>
                            <th>Job Title</th>
                            <th>Comments</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $emp_contacts = DAO::getResultset($link, "SELECT * FROM organisation_contact WHERE org_id = '{$tr->employer_id}'", DAO::FETCH_ASSOC);
                        if (count($emp_contacts) == 0)
                            echo '<tr><td colspan="8">No CRM contact is found for learner\'s employer</td> </tr>';
                        else {
                            $mainContactIDs = explode(',', $op_details->main_contact_id);
                            foreach ($emp_contacts as $contact) {
                                echo '<tr>';
                                if (in_array($contact['contact_id'], $mainContactIDs))
                                    echo '<td><input checked="checked" class="radioMainContact" type="checkbox" name="main_contact_ids[]" value="' . $contact['contact_id'] . '" ></td>';
                                else
                                    echo  '<td><input class="radioMainContact" type="checkbox" name="main_contact_ids[]" value="' . $contact['contact_id'] . '" ></td>';
                                echo '<td>' . $contact['contact_title'] . ' ' . $contact['contact_name'] . '</td>';
                                echo '<td>' . $contact['contact_telephone'] . '</td>';
                                echo '<td>' . $contact['contact_mobile'] . '</td>';
                                echo '<td>' . $contact['contact_email'] . '</td>';
                                echo '<td>' . $contact['job_title'] . '</td>';
                                echo '<td>' . $contact['comments'] . '</td>';
                                echo '</tr>';
                            }
                        }
                        ?>
                    </tbody>
                </table>
            </div>
            <div class="box box-primary">
                <div class="box-header box-primary">
                    <span class="box-title">LRAS Configuration</span>
                </div>
                <div class="box-body">
                    <div class="callout callout-info">
                        Use this panel to add new options for LRAS section's drop downs. Please use this carefully and avoid adding duplicate options.
                    </div>
                    <label for="lookup_table" class="col-sm-12 control-label fieldLabel_compulsory">Select dropdown list:</label>
                    <div class="col-sm-12">
                        <?php 
                        $lookup_tables = [
                            ['lookup_safeguarding_triggers', 'Reasons'],
                            // ['lookup_safeguarding_contr_factors', 'Contributing Factors'],
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

        <div class="col-md-9">
            <?php echo $show_additional_support_message; ?>
            <?php if ($tr->status_code == "6") {
                echo '<div class="callout callout-warning">This is a break in learning record</div>';
            } ?>
            <?php if ($restart == "1") {
                echo '<div class="callout callout-warning">This is a restart learning record, please merge information from BIL record before saving anything for this record.</div>';
            } ?>
            <?php echo $restart == '1' ? '<span class="btn btn-sm btn-danger pull-right" onclick="$(\'#restartModal\').modal(\'show\');">Merge Information from BIL Record</span><br>' : ''; ?>
            <div class="nav-tabs-custom">
                <ul class="nav nav-tabs">
                    <li class="active"><a href="#tab1" data-toggle="tab">Details</a></li>
                    <li><a href="#tab2" data-toggle="tab">Notes</a></li>
                    <li><a href="#tab3" data-toggle="tab">Files</a></li>
                    <li><a href="#tab4" data-toggle="tab">Rescheduling</a></li>
                    <li><a href="#tabMatrix" data-toggle="tab">Matrix</a></li>
                    <li><a href="#tab5" data-toggle="tab">Mock</a></li>
                    <li><a href="#tab6" data-toggle="tab">EPA</a></li>
                    <li><a href="#tab7" data-toggle="tab">Additional Information</a></li>
                    <?php if (SOURCE_LOCAL || DB_NAME == "am_baltic_demo") { ?>
                        <li><a href="#tab8" data-toggle="tab">Emails</a></li>
                    <?php } ?>
                    <li><a href="#tab9" data-toggle="tab">Complaints <?php echo $complaints_counter; ?></a></li>
                </ul>
                <div class="tab-content">
                    <div class="active tab-pane" id="tab1">
                        <div class="box box-primary">
                            <form class="form-horizontal" method="POST" name="frmLearner" action="<?php echo $_SERVER['PHP_SELF']; ?>">
                                <input type="hidden" name="id" value="<?php echo $op_details->tr_id; ?>" />
                                <input type="hidden" name="_action" value="save_op_learner" />
                                <input type="hidden" name="formName" value="frmLearner" />
                                <input type="hidden" name="inductee_id" value="<?php echo $inductee_id; ?>" />
                                <input type="hidden" name="main_contact_id" value="" />
                                <input type="hidden" name="tracker_id" value="<?php echo $tracker_id; ?>" />
                                <div class="box-header with-border">
                                    <?php if (SOURCE_BLYTHE_VALLEY || $_SESSION['user']->op_access == 'W') { ?>
                                        <button type="button" class="btn btn-primary pull-right" onclick="saveFrmLearner(); "><i class="fa fa-save"></i> Save Information</button>
                                    <?php } ?>
                                </div>
                                <div class="box-body">
                                    <div class="row">
                                        <div class="col-sm-6">
                                            <div class="form-group">
                                                <label for="preferred_name" class="col-sm-4 control-label fieldLabel_optional">Preferred Name:</label>
                                                <div class="col-sm-8">
                                                    <input type="text" class="form-control optional" name="preferred_name" id="preferred_name" value="<?php echo $op_details->preferred_name == '' ? (isset($inductee) ? $inductee->preferred_name : '') : $op_details->preferred_name; ?>" maxlength="100" />
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label for="input_moc_on_demand_1" class="col-sm-4 control-label fieldLabel_optional">MOC on Demand 1:</label>
                                                <div class="col-sm-8">
                                                    <?php echo HTML::datebox('moc_on_demand_1', $op_details->moc_on_demand_1, false); ?>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-sm-6">
                                            <div class="form-group">
                                                <label for="crc_alert" class="col-sm-4 control-label fieldLabel_optional">CRC Alert:</label>
                                                <div class="col-sm-6">
                                                    <?php //echo HTML::selectChosen('crc_alert', InductionHelper::getDDLRedAmberYellow(), $op_details->crc_alert, true); 
                                                    ?>
                                                    <input class="radioRedLight" type="radio" name="crc_alert" value="R" <?php echo $op_details->crc_alert == 'R' ? 'checked="checked"' : ''; ?> /> &nbsp;
                                                    <input class="radioOrangeLight" type="radio" name="crc_alert" value="O" <?php echo $op_details->crc_alert == 'O' ? 'checked="checked"' : ''; ?> /> &nbsp;
                                                    <input class="radioYellowLight" type="radio" name="crc_alert" value="Y" <?php echo $op_details->crc_alert == 'Y' ? 'checked="checked"' : ''; ?> /> &nbsp;
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label for="input_moc_on_demand_2" class="col-sm-4 control-label fieldLabel_optional">MOC on Demand 2:</label>
                                                <div class="col-sm-8">
                                                    <?php echo HTML::datebox('moc_on_demand_2', $op_details->moc_on_demand_2, false); ?>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-sm-6">
                                            <div class="form-group">
                                                <label for="day_7_call_date" class="col-sm-4 control-label fieldLabel_optional">7 Day Call:</label>
                                                <div class="col-sm-8">
                                                    <?php echo HTML::datebox('day_7_call_date', $op_details->day_7_call_date, false); ?>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label for="day_7_call_notes" class="col-sm-4 control-label fieldLabel_optional">
                                                    7 Day Call Notes: <?php echo !is_null($op_details->day_7_call_notes) ? '<span class="fa fa-info-circle" title="To see the saved notes for this field click on \'Notes\' tab"></span>' : ''; ?>
                                                </label>
                                                <div class="col-sm-8">
                                                    <textarea class="form-control optional" name="day_7_call_notes" id="day_7_call_notes" rows="5"></textarea>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-sm-6">
                                            <div class="form-group">
                                                <label for="input_week_3_call" class="col-sm-4 control-label fieldLabel_optional">Week 3 Call:</label>
                                                <div class="col-sm-8">
                                                    <?php echo HTML::datebox('week_3_call', $op_details->week_3_call, false); ?>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label for="week_3_call_notes" class="col-sm-4 control-label fieldLabel_optional">
                                                    Week 3 Call Notes: <?php echo !is_null($op_details->week_3_call_notes) ? '<span class="fa fa-info-circle" title="To see the saved notes for this field click on \'Notes\' tab"></span>' : ''; ?>
                                                </label>
                                                <div class="col-sm-8">
                                                    <textarea class="form-control optional" name="week_3_call_notes" id="week_3_call_notes" rows="5"></textarea>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-sm-6">
                                            <div class="form-group">
                                                <label class="col-sm-4 control-label fieldLabel_optional" for="on_furlough">On Furlough:</label>
                                                <div class="col-sm-8">
                                                    <?php echo HTML::selectChosen('on_furlough', InductionHelper::getDDLYesNo(), $op_details->on_furlough, false); ?>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-sm-6">
					                    <div class="form-group">
                                                <label class="col-sm-4 control-label fieldLabel_optional" for="walled_garden">Registered on walled garden:</label>
                                                <div class="col-sm-8">
                                                    <?php echo HTML::selectChosen('walled_garden', InductionHelper::getDDLYesNo(), $op_details->walled_garden, true); ?>
                                                </div>
                                            </div>	                                            
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-sm-6">
                                            <div class="form-group">
                                                <label for="added_to_lms" class="col-sm-4 control-label fieldLabel_optional">Added to LMS:</label>
                                                <div class="col-sm-8">
                                                    <?php echo HTML::selectChosen('added_to_lms', InductionHelper::getDDLYesNo(), $op_details->added_to_lms, true); ?>
                                                </div>
                                            </div>
                                            
                                            <!--<div class="form-group">
                                            <label for="ldd_comments" class="col-sm-4 control-label fieldLabel_optional">LDD Comments:</label>
                                            <div class="col-sm-8">
                                                <textarea class="form-control optional" name="ldd_comments" id="ldd_comments" rows="5"><?php /*echo $op_details->ldd_comments; */ ?></textarea>
                                            </div>
                                        </div>-->
                                            
                                            <div class="form-group">
                                                <label for="general_comments" class="col-sm-4 control-label fieldLabel_optional">
                                                    General Comments:
                                                </label>
                                                <div class="col-sm-8">
                                                    <textarea class="form-control optional" name="general_comments" id="general_comments" rows="5"><?php echo htmlspecialchars((string)$op_details->general_comments); ?></textarea>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-sm-6">
                                            <div class="form-group">
                                                <label class="col-sm-4 control-label fieldLabel_optional" for="learner_status">Learner Status:</label>
                                                <div class="col-sm-8">
                                                    <?php echo HTML::selectChosen('learner_status', InductionHelper::getDDLLearnerStatus(), $op_details->learner_status, true); ?>
                                                </div>
                                            </div>
					                        <!-- <div class="form-group">
                                                <label for="lras_comments" class="col-sm-4 control-label fieldLabel_optional">
                                                    LRAS Comments: <i class="fa fa-comments" title="show saved comments" style="cursor: pointer" onclick="showFieldNotes('lras_comments');"></i>
                                                </label>
                                                <div class="col-sm-8">
                                                    <textarea class="form-control optional" name="lras_comments" id="lras_comments" rows="5"></textarea>
                                                </div>
                                            </div>
					                        <div class="form-group">
                                                <label for="ad_arrangement_req" class="col-sm-4 control-label fieldLabel_optional">
                                                    Support Arrangements Requested:
                                                </label>
                                                <div class="col-sm-8">
                                                    <textarea class="form-control optional" name="ad_arrangement_req" id="ad_arrangement_req" rows="5"><?php echo htmlspecialchars((string)$tr->ad_arrangement_req); ?></textarea>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label for="ad_arrangement_agr" class="col-sm-4 control-label fieldLabel_optional">
                                                    Support Arrangements Agreed:
                                                </label>
                                                <div class="col-sm-8">
                                                    <textarea class="form-control optional" name="ad_arrangement_agr" id="ad_arrangement_agr" rows="5"><?php echo htmlspecialchars((string)$tr->ad_arrangement_agr); ?></textarea>
                                                </div>
                                            </div>	 -->
                                        </div>
                                    </div>
                                    <div class="row">

                                        <?php if (is_a($inductee, 'Inductee')) { ?>
                                            <div class="col-sm-6">
                                                <div class="form-group">
                                                    <label for="learner_id" class="col-sm-4 control-label fieldLabel_optional">ID Checked:</label>
                                                    <div class="col-sm-8">
                                                        <?php echo HTML::selectChosen('learner_id', InductionHelper::getDDLLearnerID(), $inductee->learner_id, true); ?>
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <label for="learner_id_notes" class="col-sm-4 control-label fieldLabel_optional">
                                                        Learner ID Comments:
                                                    </label>
                                                    <div class="col-sm-8">
                                                        <textarea class="form-control optional" name="learner_id_notes" id="learner_id_notes" rows="5"><?php echo $op_details->learner_id_notes; ?></textarea>
                                                    </div>
                                                </div>
                                            </div>
                                        <?php } ?>
                                        
                                    </div>

                                    <div class="row">
                                        <div class="col-sm-12">
                                        <div class="box box-primary">
                                                <div class="box-header">
                                                    <span class="text-bold">LDD</span>
                                                </div>
                                                <div class="box-body">
                                                    <p class="text-info"><i class="fa fa-info-circle"></i> Additional information may be included in the LRAS section</p>
                                                    <div class="row">
                                                        <div class="col-sm-6">
                                                            <div class="form-group">
                                                                <label for="ldd" class="col-sm-4 control-label fieldLabel_optional">LDD:</label>
                                                                <div class="col-sm-8">
                                                                    <?php 
                                                                    echo HTML::selectChosen('ldd', InductionHelper::getDDLInductionLdd(), $op_details->ldd, true); 
                                                                    echo $llddIlrInfo;
                                                                    ?>
                                                                </div>
                                                            </div>
                                                            <div class="form-group">
                                                                <label for="additional_support" class="col-sm-4 control-label fieldLabel_optional">
                                                                    Additional Support:
                                                                </label>
                                                                <div class="col-sm-8">
                                                                    <textarea class="form-control optional" name="additional_support" id="additional_support" rows="5"><?php echo htmlspecialchars((string)$op_details->additional_support); ?></textarea>
                                                                    <?php echo $show_additional_support_message; ?>
                                                                </div>
                                                            </div>
                                                            <div class="form-group">
                                                                <label for="support_conversation" class="col-sm-4 control-label fieldLabel_optional">Support Conversation:</label>
                                                                <div class="col-sm-8">
                                                                    <?php 
                                                                    echo HTML::selectChosen('support_conversation', InductionHelper::getSupportConversationDdl(), $op_details->support_conversation, true); 
                                                                    ?>
                                                                </div>
                                                            </div>
                                                            <div class="form-group">
                                                                <label for="epa_reasonable_adjustment" class="col-sm-4 control-label fieldLabel_optional"> EPA Reasonable Adjustment:</label>
                                                                <div class="col-sm-8">
                                                                    <input type="text" class="form-control" name="epa_reasonable_adjustment" id="epa_reasonable_adjustment" value="<?php echo $op_details->epa_reasonable_adjustment; ?>" maxlength="70">
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-sm-6">
                                                            <?php if(isset($inductee) && $inductee->id != ''){?>
                                                            <div class="form-group">
                                                                <label for="input_sen_date" class="col-sm-4 control-label fieldLabel_optional">Date Informed:</label>
                                                                <div class="col-sm-8">
                                                                    <?php echo HTML::datebox('sen_date', $inductee->sen_date, false); ?>
                                                                </div>
                                                            </div>
                                                            <?php } ?>
                                                            <div class="form-group">
                                                                <label for="als_plan" class="col-sm-4 control-label fieldLabel_optional">ALS Plan:</label>
                                                                <div class="col-sm-8">
                                                                    <input type="checkbox" name="als_plan" id="als_plan" value="1" <?php echo $op_details->als_plan == 1 ? 'checked' : ''; ?> />
                                                                </div>
                                                            </div>
                                                            <div class="form-group">
                                                                <label for="diagnosis_evidence_required" class="col-sm-4 control-label fieldLabel_optional">Diagnosis Evidence Received:</label>
                                                                <div class="col-sm-8">
                                                                    <input type="checkbox" name="diagnosis_evidence_required" id="diagnosis_evidence_required" value="1" <?php echo $op_details->diagnosis_evidence_required == 1 ? 'checked' : ''; ?> />
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        
                                        <div class="col-sm-6">
                                            
                                            <div class="box box-solid box-warning">
                                                <div class="box-header"><span class="box-title lead">LRAS</span></div>
                                                <div class="box-body">
                                                    
                                                    <?php
                                                    if ($lras_details->Status->__toString() == "Y") {
                                                        echo '<div class="bg-info">';
                                                        echo '<i class="fa fa-info-circle"></i> ';
                                                        echo 'Current LRAS status of learner is <span class="text-bold">Yes</span>';
                                                        echo '. Please go to second tab to see full details ';
                                                        echo '</div>';
                                                    }
                                                    ?>
                                                    <div class="form-group">
                                                        <label for="chk_save_lras" class="col-sm-4 control-label fieldLabel_optional">Tick this box if you have added/updated LRAS information:</label>
                                                        <div class="col-sm-8">
                                                            <input type="checkbox" name="chk_save_lras" id="chk_save_lras" value="1" />
                                                        </div>
                                                    </div>
                                                    <div class="form-group">
                                                        <label for="lras_status" class="col-sm-4 control-label fieldLabel_optional">LRAS:</label>
                                                        <div class="col-sm-8">
                                                            <?php echo isset($lras_details->Status) ?
                                                                HTML::selectChosen('lras_status', InductionHelper::getDDLYesNo(), $lras_details->Status->__toString(), true) :
                                                                HTML::selectChosen('lras_status', InductionHelper::getDDLYesNo(), '', true); ?>
                                                        </div>
                                                    </div>
                                                    <div class="form-group">
                                                        <label for="lras_summary" class="col-sm-4 control-label fieldLabel_optional">LRAS Summary:</label>
                                                        <div class="col-sm-8">
                                                            <textarea class="form-control optional" name="lras_summary" id="lras_summary" rows="5"><?php echo isset($lras_details->Summary) ? $lras_details->Summary->__toString() : ''; ?></textarea>
                                                        </div>
                                                    </div>
                                                    <div class="form-group">
                                                        <label for="lras_reason" class="col-sm-4 control-label fieldLabel_optional">Reason:</label>
                                                        <div class="col-sm-8">
                                                            <?php echo isset($lras_details->Reason) ?
                                                                HTML::selectChosen('lras_reason', Safeguarding::getDdlTriggers($link), explode(",", $lras_details->Reason->__toString()), true, false, true, 10) : 
                                                                HTML::selectChosen('lras_reason', Safeguarding::getDdlTriggers($link), '', true, false, true, 10); ?>
                                                        </div>
                                                    </div>
                                                    <div class="form-group">
                                                        <label for="lras_category" class="col-sm-4 control-label fieldLabel_optional">Category:</label>
                                                        <div class="col-sm-8">
                                                            <?php echo isset($lras_details->Category) ? 
                                                                HTML::selectChosen('lras_category', Safeguarding::getDdlCategories($link), $lras_details->Category->__toString(), true, false, true) : 
                                                                HTML::selectChosen('lras_category', Safeguarding::getDdlCategories($link), '', true, false, true); ?>
                                                        </div>
                                                    </div>
                                                    <div class="form-group">
                                                        <label for="lras_date" class="col-sm-4 control-label fieldLabel_optional">Date:</label>
                                                        <div class="col-sm-8">
                                                            <?php echo isset($lras_details->LrasDate) ? 
                                                                HTML::datebox('lras_date', $lras_details->LrasDate->__toString(), false) : 
                                                                HTML::datebox('lras_date', '', false); ?>
                                                        </div>
                                                    </div>
                                                    <div class="form-group">
                                                        <label for="lras_owner" class="col-sm-4 control-label fieldLabel_optional">Owner:</label>
                                                        <div class="col-sm-8">
                                                            <?php echo isset($lras_details->Owner) ? 
                                                                HTML::selectChosen('lras_owner', InductionHelper::getDdlLrasOwner(), explode(",", $lras_details->Owner->__toString()), true, false, true, 10) : 
                                                                HTML::selectChosen('lras_owner', InductionHelper::getDdlLrasOwner(), [], true, false, true, 10); ?>
                                                        </div>
                                                    </div>
                                                    <div class="form-group">
                                                        <label for="lras_pro_react" class="col-sm-4 control-label fieldLabel_optional">Proactive/Reactive:</label>
                                                        <div class="col-sm-8">
                                                            <?php echo isset($lras_details->ProReact) ? 
                                                                HTML::selectChosen('lras_pro_react', Safeguarding::getDdlProRe(), $lras_details->ProReact->__toString(), true, false, true) : 
                                                                HTML::selectChosen('lras_pro_react', Safeguarding::getDdlProRe(), '', true, false, true); ?>
                                                        </div>
                                                    </div>
                                                    <div class="form-group">
                                                        <label for="lras_recommended_end_date" class="col-sm-4 control-label fieldLabel_optional">Recommended End Date:</label>
                                                        <div class="col-sm-8">
                                                            <?php echo isset($lras_details->RecommendedEndDate) ? 
                                                                HTML::datebox('lras_recommended_end_date', $lras_details->RecommendedEndDate->__toString(), false) : 
                                                                HTML::datebox('lras_recommended_end_date', '', false); ?>
                                                        </div>
                                                    </div>
                                                    <div class="form-group">
                                                        <label for="lras_support_provider" class="col-sm-4 control-label fieldLabel_optional">Support Provider:</label>
                                                        <div class="col-sm-8">
                                                            <?php echo isset($lras_details->SupportProvider) ? 
                                                                HTML::selectChosen('lras_support_provider', Safeguarding::getDdlSupportProvider(), explode(",", $lras_details->SupportProvider->__toString()), true, false, true, 10): 
                                                                HTML::selectChosen('lras_support_provider', Safeguarding::getDdlSupportProvider(), '', true, false, true, 10); ?>
                                                        </div>
                                                    </div>
                                                    <div class="form-group">
                                                        <label for="action_plan_agreed" class="col-sm-4 control-label fieldLabel_optional">Action Plan Agreed:</label>
                                                        <div class="col-sm-8">
                                                            <textarea class="form-control optional" name="action_plan_agreed" id="action_plan_agreed" rows="5"><?php echo isset($lras_details->ActionPlanAgreed) ? $lras_details->ActionPlanAgreed->__toString() : ''; ?></textarea>
                                                        </div>
                                                    </div>
                                                    <div class="form-group">
                                                        <label for="resources_provided" class="col-sm-4 control-label fieldLabel_optional">Resources/ Aftercare Provided:</label>
                                                        <div class="col-sm-8">
                                                            <textarea class="form-control optional" name="resources_provided" id="resources_provided" rows="5"><?php echo isset($lras_details->ResourcesProvided) ? $lras_details->ResourcesProvided->__toString() : ''; ?></textarea>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                        </div>
                                        <div class="col-sm-6">
                                            <div class="box box-solid box-success">
                                                <div class="box-header"><span class="text-bold">Completion</span></div>
                                                <div class="box-body">
                                                    <div class="form-group">
                                                        <label for="is_completed" class="col-sm-4 control-label fieldLabel_optional">Completed:</label>
                                                        <div class="col-sm-8">
                                                            <?php echo HTML::selectChosen('is_completed', InductionHelper::getDDLYesNo(), $op_details->is_completed, false); ?>
                                                        </div>
                                                    </div>
                                                    <div class="form-group">
                                                        <label for="completed_date" class="col-sm-4 control-label fieldLabel_optional">Completion Date:</label>
                                                        <div class="col-sm-8">
                                                            <?php echo HTML::datebox('completed_date', $op_details->completed_date, false); ?>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                        </div>
                                    </div>

                                </div>

                            </form>
                        </div>
                    </div>
                    <div class="tab-pane" id="tab2">
                        <div class="row">
                            <div class="col-sm-12">
                                <div class="text-center pad">
                                    <?php
                                    $notes_options = array(
                                        array('hour_48_call_notes', '48 Hour Call Notes'),
                                        array('day_7_call_notes', '7 Day Call Notes'),
                                        array('week_3_call_notes', 'Week 3 Call Notes'),
                                        array('lar_notes', 'LAR Notes'),
                                        array('break_in_learning_notes', 'Break in Learning Notes'),
                                        array('leaver_form_notes', 'Leaver Form Notes'),
                                        // array('learner_id_notes', 'Learner ID Notes'),
                                        array('coordinator_notes', 'Coordinator Comments from Induction'),
                                        array('last_learning_evidence', 'Last Learning Evidence'),
                                        array('leaver_notes', 'Leaver Notes'),
                                        array('peed_notes', 'PEED Details'),
                                        array('lras_notes', 'LRAS Details'),
                                    );
                                    echo HTML::select('notes_ddl', $notes_options, '', true);
                                    ?>
                                    &nbsp; <span class="btn btn-sm btn-info" onclick="showNotes();"><i class="fa fa-table"></i> Click to see notes</span>
                                </div>
                            </div>
                            <div class="col-sm-12">
                                <div id="divNotes" class="pad table-responsive">
                                    <p class="text-bold text-muted"><i class="fa fa-info-circle"></i> Please select an option and press the button to fetch the information</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="tab-pane" id="tab3">
                        <div class="row">
                            <div class="col-sm-6">
                                <div class="box">
                                    <?php
                                    $total_bil_files = DAO::getSingleValue($link, "SELECT COUNT(*) FROM tr_files WHERE tr_id = '{$tr->id}' AND file_type = '2'");
                                    $total_lar_files = DAO::getSingleValue($link, "SELECT COUNT(*) FROM tr_files WHERE tr_id = '{$tr->id}' AND file_type = '1'");
                                    $total_leaver_files = DAO::getSingleValue($link, "SELECT COUNT(*) FROM tr_files WHERE tr_id = '{$tr->id}' AND file_type = '3'");
                                    $total_complaints_files = DAO::getSingleValue($link, "SELECT COUNT(*) FROM tr_files WHERE tr_id = '{$tr->id}' AND file_type = '4'");
                                    ?>
                                    <ul style="margin-left: 5px;" class="list-group list-group-unbordered">
                                        <li class="list-group-item">
                                            <span class="text-bold">BIL: </span><?php echo $total_bil_files == 0 ? '<i class="text-muted">No files uploaded</i>' : '<span class="text-bold text-blue">' . $total_bil_files . '</span> <i class="text-muted">files uploaded</i>'; ?>
                                        </li>
                                        <li class="list-group-item">
                                            <span class="text-bold">LAR: </span><?php echo $total_lar_files == 0 ? '<i class="text-muted">No files uploaded</i>' : '<span class="text-bold text-blue">' . $total_lar_files . '</span> <i class="text-muted">files uploaded</i>'; ?>
                                        </li>
                                        <li class="list-group-item">
                                            <span class="text-bold">Leaver: </span><?php echo $total_leaver_files == 0 ? '<i class="text-muted">No files uploaded</i>' : '<span class="text-bold text-blue">' . $total_leaver_files . '</span> <i class="text-muted">files uploaded</i>'; ?>
                                        </li>
                                        <li class="list-group-item">
                                            <span class="text-bold">Complaints: </span><?php echo $total_complaints_files == 0 ? '<i class="text-muted">No files uploaded</i>' : '<span class="text-bold text-blue">' . $total_complaints_files . '</span> <i class="text-muted">files uploaded</i>'; ?>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="box">
                                    <form enctype="multipart/form-data" class="form-horizontal" method="post" name="frmOpLearnerFiles" action="<?php echo $_SERVER['PHP_SELF']; ?>">
                                        <input type="hidden" name="id" value="<?php echo $op_details->tr_id; ?>" />
                                        <input type="hidden" name="username" value="<?php echo $tr->username; ?>" />
                                        <input type="hidden" name="_action" value="save_op_learner" />
                                        <input type="hidden" name="formName" value="frmOpLearnerFiles" />
                                        <input type="hidden" name="tracker_id" value="<?php echo $tracker_id; ?>" />
                                        <div class="box-body">
                                            <?php $file_types = DAO::getResultset($link, "SELECT id, description, null FROM lookup_file_types ORDER BY description"); ?>
                                            <div class="form-group">
                                                <label for="uploaded_op_learner_file_type" class="col-sm-4 control-label fieldLabel_optional">File Type:</label>
                                                <div class="col-sm-8">
                                                    <?php echo HTML::select('uploaded_op_learner_file_type', $file_types, '', false); ?>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label for="uploaded_op_learner_file" class="col-sm-4 control-label fieldLabel_optional">File:</label>
                                                <div class="col-sm-8">
                                                    <input class="optional" type="file" name="uploaded_op_learner_file" />
                                                </div>
                                            </div>
                                        </div>
                                        <div class="box-footer">
                                            <?php if (SOURCE_BLYTHE_VALLEY || $_SESSION['user']->op_access == 'W') { ?>
                                                <span id="uploadFileButton" class="btn btn-sm btn-primary pull-right" onclick="uploadFile();"><i class="fa fa-upload"></i> Upload</span>
                                            <?php } ?>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-12">
                                <div class="box">
                                    <?php
                                    $sql = <<<SQL
SELECT
	tr_id,file_name,lookup_file_types.`description`,CONCAT(users.`firstnames`,' ',users.`surname`) AS uploaded_by,DATE_FORMAT(uploaded_date,'%d/%m/%Y %H:%i:%s') AS uploaded_date
FROM
	tr_files LEFT JOIN lookup_file_types ON tr_files.`file_type` = lookup_file_types.`id`
	LEFT JOIN users ON uploaded_by = users.`id`
WHERE tr_id = '$tr->id' ORDER BY tr_files.file_type, tr_files.uploaded_date DESC;
SQL;
                                    $repository = Repository::getRoot() . '/' . $tr->username . '/operations';
                                    $records = DAO::getResultset($link, $sql, DAO::FETCH_ASSOC);
                                    if (count($records) == 0) {
                                        echo '<i>No file uploaded</i>';
                                    } else {
                                        $desc = '';
                                        $first_iteration = true;
                                        foreach ($records as $row) {
                                            if ($first_iteration)
                                                echo '<ul style="margin-left: 15px; max-height: 500px; overflow-y: scroll;" class="bg-green list-group list-group-unbordered"><li class="text-center"><strong>' . $row['description'] . '</strong></li>';
                                            else {
                                                if ($desc != $row['description'])
                                                    echo '</ul><ul style="margin-left: 15px; max-height: 500px; overflow-y: scroll;" class="bg-green list-group list-group-unbordered"><li class="text-center"><strong>' . $row['description'] . '</strong></li>';
                                            }

                                            echo '<li class="list-group-item">';
                                            if (!is_file($repository . '/' . $row['file_name'])) continue;
                                            $file = new RepositoryFile($repository . '/' . $row['file_name']);
                                            if (!$file->isFile())
                                                continue;
                                            $ext = $file->getExtension();
                                            $image = $ext == 'doc' || $ext == 'docx' ? 'fa fa-file-word-o' : ($ext == 'pdf' ? 'fa fa-file-pdf-o' : ($ext == 'txt' ? 'fa fa-file-text-o' : 'fa fa-file'));
                                            echo '<a href="' . $file->getDownloadURL() . '"><i class="' . $image . '"></i> &nbsp; ' . $file->getName() . '</a> &nbsp;&nbsp;&nbsp; <span class="text-green">(' . $row['description'] . ')</span>';
                                            echo '<br>';
                                            echo '<span class="direct-chat-timestamp"><i class="fa fa-clock-o"></i> &nbsp; <small>' . Date::to($file->getModifiedTime(), Date::DATETIME) . '</small></span>';
                                            echo '<br>';
                                            echo '<span class="direct-chat-timestamp"><i class="fa fa-user"></i> &nbsp; <small>' . $row['uploaded_by'] . '</small></span><br>';
                                            echo '</li> ';
                                            $desc = $row['description'];
                                            $first_iteration = false;
                                        }
                                        echo '</ul>';
                                    }
                                    ?>
                                </div>
                            </div>

                        </div>
                    </div>
                    <div class="tab-pane" id="tab4">
                        <div class="row">
                            <div class="col-sm-12">
                                <span class="text-bold">Registered Events</span>
                                <?php
                                $sql = <<<SQL
SELECT DISTINCT sessions.id, sessions.personnel, sessions.event_type, sessions.start_date, sessions.start_time, sessions.end_date, sessions.end_time, sessions.max_learners, sessions.unit_ref, sessions.tracker_id, session_entries.entry_tr_id
FROM sessions INNER JOIN session_entries ON sessions.id = session_entries.entry_session_id
WHERE session_entries.entry_tr_id = '$tr->id'
SQL;
                                $result = DAO::getResultset($link, $sql, DAO::FETCH_ASSOC);
                                echo '<table class="table table-bordered table-condensed table-striped">';
                                echo '<tr><th>Unit/Course</th><th>Event Type</th><th>Trainer</th><th>Start</th><th>End</th><th>Max. allowed</th><th>Spaces available</th><th>Action</th></tr>';
                                if (count($result) == 0) {
                                    echo '<tr><td colspan="8">No records found</td></tr>';
                                } else {
                                    $event_types = InductionHelper::getListEventTypes();
                                    foreach ($result as $row) {
                                        echo '<tr>';
                                        echo '<td>' . $row['unit_ref'] . '</td>';
                                        echo isset($event_types[$row['event_type']]) ? '<td>' . $event_types[$row['event_type']] . '</td>' : '<td></td>';
                                        echo '<td>' . DAO::getSingleValue($link, "SELECT CONCAT(firstnames, ' ' , surname) FROM users WHERE users.id = '{$row['personnel']}'") . '</td>';
                                        echo '<td>' . Date::toShort($row['start_date']) . ' ' . $row['start_time'] . '</td>';
                                        echo '<td>' . Date::toShort($row['end_date']) . ' ' . $row['end_time'] . '</td>';
                                        echo '<td class="text-center">' . $row['max_learners'] . '</td>';
                                        $occupied = (int)DAO::getSingleValue($link, "SELECT COUNT(*) FROM session_entries WHERE entry_session_id = '{$row['id']}'");
                                        $available_spaces = (int)$row['max_learners'] - $occupied;
                                        echo '<td class="text-center">' . $available_spaces . '</td>';
                                        echo '<td><div class="callout callout-default">';
                                        echo '<p>Category: ' . HTML::select('categoryCancelEntry' . $row['id'], InductionHelper::getDdlReschedulingCategory(), '', true) . '</p>';
					                    echo '<p>Type: ' . HTML::select('typeCancelEntry' . $row['id'], InductionHelper::getDdlReschedulingType(), '', true, true) . '</p>';
                                        echo '<textarea id="txtAreaCancelEntry' . $row['id'] . '" cols="50" rows="3"></textarea><br><span class="btn btn-sm btn-danger" onclick="cancelEntry(\'' . $row['id'] . '\', \'' . $row['entry_tr_id'] . '\');"><i class="fa fa-remove"></i> Cancel</span>';
                                        echo '</div></td>';
                                        echo '</tr>';
                                    }
                                }
                                echo '</table>';
                                ?>
                            </div>

                            <div class="col-sm-12">
                                <span class="text-bold">Cancelled Events</span>
                                <?php
                                $sql = <<<SQL
SELECT DISTINCT sessions.id, (SELECT CONCAT(firstnames, ' ' , surname) FROM users WHERE id = session_cancellations.cancelled_by) AS cancelled_by,
sessions.personnel, sessions.event_type, sessions.start_date, sessions.start_time, sessions.end_date, sessions.end_time, 
sessions.max_learners, sessions.unit_ref, cancellation_date, session_cancellations.comments, session_cancellations.category, session_cancellations.cancellation_type,
(SELECT CONCAT(users.firstnames, ' ', users.surname) FROM users WHERE users.id = sessions.personnel) AS trainer, session_cancellations.id AS session_cancellation_id
FROM sessions INNER JOIN session_cancellations ON sessions.id = session_cancellations.session_id
WHERE session_cancellations.tr_id = '$tr->id'
SQL;
                                $result = DAO::getResultset($link, $sql, DAO::FETCH_ASSOC);
                                echo '<table class="table table-bordered table-condensed">';
                                echo '<tr><th>Unit/Course</th><th>Event Type</th><th>DateTime</th><th>Cancellation Date</th><th>Cancelled By</th><th>Category</th><th>Type</th><th>Trainer</th><th>Comments</th></tr>';
                                if (count($result) == 0) {
                                    echo '<tr><td colspan="9">No records found</td></tr>';
                                } else {
                                    $event_types = InductionHelper::getListEventTypes();
                                    $resched_categories = InductionHelper::getListReschedulingCategory();
				                    $cancellation_types_list = InductionHelper::getListReschedulingType();	
                                    foreach ($result as $row) {
                                        echo !$_SESSION['user']->isAdmin() ? '<tr>' : HTML::viewrow_opening_tag('do.php?_action=edit_op_session_cancellation_entry&session_cancellation_id=' . $row['session_cancellation_id'] . '&tr_id=' . $tr->id . '&tracker_id=' . $tracker_id);
                                        echo '<td class="small">' . $row['unit_ref'] . '</td>';
                                        echo isset($event_types[$row['event_type']]) ? '<td>' . $event_types[$row['event_type']] . '</td>' : '<td></td>';
                                        echo '<td>' . Date::toShort($row['start_date']) . ' ' . $row['start_time'] . ' - ' . Date::toShort($row['end_date']) . ' ' . $row['end_time'] . '</td>';
                                        echo '<td>' . Date::toShort($row['cancellation_date']) . '</td>';
                                        echo '<td>' . htmlspecialchars((string)$row['cancelled_by']) . '</td>';
                                        echo isset($resched_categories[$row['category']]) ? '<td>' . $resched_categories[$row['category']] . '</td>' : '<td>' . $row['category'] . '</td>';
                                        echo isset($cancellation_types_list[$row['cancellation_type']]) ? '<td>' . $cancellation_types_list[$row['cancellation_type']] . '</td>' : '<td>' . $row['cancellation_type'] . '</td>';
                                        echo '<td>' . htmlspecialchars((string)$row['trainer']) . '</td>';
                                        echo '<td class="small">' . htmlspecialchars((string)$row['comments']) . '</td>';
                                        echo '</tr>';
                                    }
                                }
                                echo '</table>';
                                ?>
                            </div>
                        </div>

                    </div>
                    <div class="tab-pane" id="tabMatrix">
                        <div class="row">
                            <div class="col-sm-12">
                                <table class="table table-bordered">
                                    <tr>
                                        <th style="width: 30%;">Induction Date:</th>
                                        <td>
                                            <?php
                                            echo isset($induction->induction_date) ? Date::toShort($induction->induction_date) : '';
                                            ?>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>First Course Date:</th>
                                        <td>
                                            <?php
                                            /*
                                        $tracker_units_list = DAO::getSingleColumn($link, "SELECT unit_ref  FROM op_tracker_units WHERE tracker_id = '{$tracker_id}'");
                                        $first_course_date = new Date('2050-01-01');
                                        foreach($tracker_units_list AS $_tracker_unit)
                                        {
                                            $_sql = "SELECT sessions.start_date FROM sessions INNER JOIN session_entries ON sessions.`id` = session_entries.`entry_session_id`
                                                    WHERE session_entries.`entry_tr_id` = '{$tr->id}' AND FIND_IN_SET('{$tracker_id}', sessions.`tracker_id`)  AND FIND_IN_SET('{$_tracker_unit}', unit_ref);";
                                            $course_date = DAO::getSingleValue($link, $_sql);
                                            if($course_date == '')
                                                continue;
                                            $course_date = new Date($course_date);
                                            if($course_date->before($first_course_date))
                                                $first_course_date = $course_date;
                                        }
                                        if($first_course_date->__toString() != '2050-01-01')
                                            echo $first_course_date->formatShort();
					*/
                                            $first_course_date = TrainingRecord::getFirstCourseDate($link, $tracker_id, $tr->id);
                                            echo !is_null($first_course_date) ? $first_course_date->formatShort() : '';
                                            ?>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>Number of Weeks Before First Course:</th>
                                        <td>
                                            <?php
                                            if (isset($induction->induction_date) && !is_null($first_course_date) && $first_course_date->__toString() != '2050-01-01') {
                                                echo DAO::getSingleValue($link, "SELECT ROUND(DATEDIFF('{$first_course_date->__toString()}', '{$induction->induction_date}')/7, 0)");
                                            }
                                            ?>
                                        </td>
                                    </tr>
                                </table>
                                <p><br></p>
                                <div class="box box-info">
                                    <div class="box-header no-pad">
                                        <?php if (SOURCE_BLYTHE_VALLEY || $_SESSION['user']->op_access == 'W') { ?>
                                            <span class="btn btn-sm btn-primary pull-right" onclick="saveMatrixTab();"><i class="fa fa-save"></i> Save Information</span>
                                        <?php } ?>
                                    </div>
                                    <div class="box-body">
                                        <form class="form-horizontal" name="frmMatrix" id="frmMatrix" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
                                            <input type="hidden" name="_action" value="ajax_tracking" />
                                            <input type="hidden" name="subaction" value="saveMatrixTab" />
                                            <input type="hidden" name="tr_id" value="<?php echo $tr->id; ?>" />
                                            <div class="form-group">
                                                <label for="coordinator_comments" class="col-sm-4 control-label">Coordinator Comments :</label>
                                                <div class="col-sm-8">
                                                    <textarea name="coordinator_comments" id="coordinator_comments" class="form-control" rows="5"><?php echo nl2br($op_details->coordinator_comments); ?></textarea>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label for="reason_outside_matrix" class="col-sm-4 control-label">Reason Outside Matrix:</label>
                                                <div class="col-sm-8">
                                                    <?php echo HTML::select('reason_outside_matrix', InductionHelper::getDdlReasonOutsideMatrix(), $op_details->reason_outside_matrix, true); ?>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="tab-pane" id="tab5">
                        <div class="row">
                            <div class="col-sm-12">
                                <div class="box">
                                    <div class="box-header">
                                        <p class="lead">Mock Status</p>
                                    </div>
                                    <div class="box-body">
                                        <div class="row">
                                            <?php
                                            $tracker_units = DAO::getResultset($link, "SELECT unit_ref, unit_ref, null  FROM op_tracker_units WHERE tracker_id = '{$tracker_id}'");

                                            ?>
                                            <form autocomplete="off" class="form-horizontal" name="frmUnitsMockDetails" id="frmUnitsMockDetails" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="get">
                                                <input type="hidden" name="_action" value="ajax_tracking" />
                                                <input type="hidden" name="subaction" value="saveMockEntry" />
                                                <input type="hidden" name="tracker_id" value="<?php echo $tracker_id; ?>" />
                                                <input type="hidden" name="tr_id" value="<?php echo $tr->id; ?>" />
                                                <div class="form-group">
                                                    <label for="mock_unit_ref" class="col-sm-4 control-label fieldLabel_compulsory">Unit :</label>
                                                    <div class="col-sm-8">
                                                        <?php echo HTML::selectChosen('mock_unit_ref', $tracker_units, '', false, true); ?>
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <label for="mock_unit_ref_status" class="col-sm-4 control-label fieldLabel_compulsory">Mock Status :</label>
                                                    <div class="col-sm-8">
                                                        <?php echo HTML::selectChosen('mock_unit_ref_status', InductionHelper::getDDLMockStatus(), '', false); ?>
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <label for="mock_unit_comments" class="col-sm-4 control-label fieldLabel_compulsory">Comments:</label>
                                                    <div class="col-sm-8">
                                                        <textarea class="form-control" name="mock_unit_comments" id="mock_unit_comments"></textarea>
                                                    </div>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                    <div class="box-footer">
                                        <?php if (SOURCE_BLYTHE_VALLEY || $_SESSION['user']->op_access == 'W') { ?>
                                            <span class="btn btn-primary pull-right" onclick="saveMockEntry();"><i class="fa fa-save"></i> Save Unit Mock Details</span>
                                        <?php } ?>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-12">
                                <div class="box box-solid box-info">
                                    <div class="box-header">
                                        <p class="lead">Log</p>
                                    </div>
                                    <div class="box-body">
                                        <?php
                                        $mock_list = InductionHelper::getListMockStatus();
                                        $result = DAO::getSingleColumn($link, "SELECT DISTINCT unit_ref FROM op_tracker_unit_mock WHERE tr_id = '{$tr->id}' ORDER BY unit_ref");
                                        echo '<table class="table row-border"> ';
                                        foreach ($result as $row) {
                                            echo '<tr class="bg-gray"><th colspan="5">' . $row . '</th></tr>';
                                            $details = DAO::getResultset($link, "SELECT * FROM op_tracker_unit_mock WHERE tr_id = '{$tr->id}' AND unit_ref = '{$row}' ORDER BY unit_ref", DAO::FETCH_ASSOC);
                                            foreach ($details as $d) {
                                                echo '<tr>';
                                                echo '<td><i class="fa fa-clock-o"></i> ' . Date::to($d['created'], Date::DATETIME) . '</td>';
                                                echo isset($mock_list[$d['mock_code']]) ? '<td>' . $mock_list[$d['mock_code']] . '</td>' : '<td>' . $d['mock_code'] . '</td>';
                                                echo '<td><i class="fa fa-user"></i> ' . DAO::getSingleValue($link, "SELECT CONCAT(firstnames, ' ' , surname) AS n FROM users WHERE id = '{$d['created_by']}'") . '</td>';
                                                echo '<td><i class="fa fa-comments"></i> ' . HTML::nl2p($d['comments']) . '</td>';
                                                echo $_SESSION['user']->op_access == 'W' ? '<td><span class="btn btn-xs btn-danger" title="Delete this mock entry" onclick="removeMockEntry(\'' . $d['id'] . '\');"><i class="fa fa-remove"></i></span> </td>' : '<td></td>';
                                                echo '</tr>';
                                            }
                                        }
                                        echo '</table>';
                                        ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="tab-pane" id="tab6">
                        <div class="box">
                            <div class="box-header">
                                <p class="lead">EPA</p>
                                <table class="table table-bordered">
                                    <?php
                                    $current_training_month = DAO::getSingleValue($link, "SELECT TIMESTAMPDIFF(MONTH, '" . Date::toMySQL($tr->start_date) . "', CURDATE());");

                                    $total_course_units = DAO::getSingleValue($link, 'SELECT COUNT(*) FROM op_tracker_unit_sch m1 LEFT JOIN op_tracker_unit_sch m2 ON (m1.tr_id = m2.tr_id AND m1.unit_ref = m2.unit_ref AND m1.id < m2.id) WHERE m2.id IS NULL AND m1.tr_id = "' . $tr->id . '" AND m1.`code` != "NR" AND m1.unit_ref NOT LIKE "% Test" AND m1.`unit_ref` != "SLC" AND m1.`unit_ref` NOT LIKE "%Workshop%" AND m1.`unit_ref` NOT LIKE "%Functional Skills%" AND m1.`unit_ref` NOT LIKE "%EPA Prep%"');
                                    if ($tracker_id == '9' || $tracker_id == '18')
                                        $passed_course_units = DAO::getSingleValue($link, 'SELECT COUNT(*) FROM op_tracker_unit_sch m1 LEFT JOIN op_tracker_unit_sch m2 ON (m1.tr_id = m2.tr_id AND m1.unit_ref = m2.unit_ref AND m1.id < m2.id) WHERE m2.id IS NULL AND m1.tr_id = "' . $tr->id . '" AND m1.`code` IN ("P", "MC", "D") AND m1.unit_ref NOT LIKE "% Test" AND m1.`unit_ref` != "SLC" AND m1.`unit_ref` NOT LIKE "%Workshop%" AND m1.`unit_ref` NOT LIKE "%Functional Skills%" AND m1.`unit_ref` NOT LIKE "%EPA Prep%"');
                                    else
                                        $passed_course_units = DAO::getSingleValue($link, 'SELECT COUNT(*) FROM op_tracker_unit_sch m1 LEFT JOIN op_tracker_unit_sch m2 ON (m1.tr_id = m2.tr_id AND m1.unit_ref = m2.unit_ref AND m1.id < m2.id) WHERE m2.id IS NULL AND m1.tr_id = "' . $tr->id . '" AND m1.`code` IN ("U") AND m1.unit_ref NOT LIKE "% Test" AND m1.`unit_ref` != "SLC" AND m1.`unit_ref` NOT LIKE "%Workshop%" AND m1.`unit_ref` NOT LIKE "%Functional Skills%" AND m1.`unit_ref` NOT LIKE "%EPA Prep%"');
                                    $course_class = '';
                                    $course_percentage = $total_course_units != 0 ? round(($passed_course_units / $total_course_units) * 100) : 'N/A';

                                    $std_framework = DAO::getObject($link, "SELECT frameworks.* FROM frameworks INNER JOIN student_frameworks ON frameworks.id = student_frameworks.id WHERE student_frameworks.tr_id = '{$tr->id}'");
                                    $course_percentage_set = DAO::getSingleValue($link, "SELECT COUNT(*) FROM op_course_percentage WHERE programme = '{$std_framework->short_name}'");
                                    $test_percentage_set = DAO::getSingleValue($link, "SELECT COUNT(*) FROM op_test_percentage WHERE programme = '{$std_framework->short_name}'");

                                    if ($std_framework->short_name != '' && $course_percentage_set > 0 && $course_percentage < 100 && $current_training_month > 0) {
                                        $max_month_value = DAO::getSingleValue($link, "SELECT MAX(max_month) FROM op_course_percentage WHERE programme = '{$std_framework->short_name}';");
                                        $course_class = "bg-green";
                                        if ($current_training_month > $max_month_value && $course_percentage < 100) {
                                            $course_class = "bg-red";
                                        } else {
                                            $op_course_progress_lookup = DAO::getObject($link, "SELECT op_course_percentage.* FROM op_course_percentage WHERE programme = '{$std_framework->short_name}' AND {$current_training_month} BETWEEN min_month AND max_month");
                                            if (isset($op_course_progress_lookup->min_percentage) && $course_percentage >= $op_course_progress_lookup->min_percentage)
                                                $course_class = "bg-green";
                                            else
                                                $course_class = "bg-red";
                                        }
                                    }
                                    if ($course_percentage >= 100 || $current_training_month == 0)
                                        $course_class = "bg-green";

                                    $total_exam_units = DAO::getSingleValue($link, 'SELECT COUNT(*) FROM op_tracker_unit_sch m1 LEFT JOIN op_tracker_unit_sch m2 ON (m1.tr_id = m2.tr_id AND m1.unit_ref = m2.unit_ref AND m1.id < m2.id) WHERE m2.id IS NULL AND m1.tr_id = "' . $tr->id . '" AND m1.`code` != "NR" AND (m1.unit_ref LIKE "% Test" OR m1.`unit_ref` = "SLC")');
                                    $passed_exam_units = DAO::getSingleValue($link, 'SELECT COUNT(*) FROM op_tracker_unit_sch m1 LEFT JOIN op_tracker_unit_sch m2 ON (m1.tr_id = m2.tr_id AND m1.unit_ref = m2.unit_ref AND m1.id < m2.id) WHERE m2.id IS NULL AND m1.tr_id = "' . $tr->id .'" AND ( (m1.`code` IN ("P", "MC", "D") AND m1.unit_ref LIKE "% Test") OR (m1.`code` IN ("U", "P") AND m1.`unit_ref` = "SLC"))');

                                    $test_percentage = $total_exam_units != 0 ? round(($passed_exam_units / $total_exam_units) * 100) : 'N/A';

                                    $test_class = '';
                                    if ($std_framework->short_name != '' && $test_percentage_set > 0 && $test_percentage < 100 && $current_training_month > 0) {
                                        $max_month_value = DAO::getSingleValue($link, "SELECT MAX(max_month) FROM op_test_percentage WHERE programme = '{$std_framework->short_name}';");
                                        $test_class = "bg-green";
                                        if ($current_training_month > $max_month_value && $test_percentage < 100) {
                                            $test_class = "bg-red";
                                        } else {
                                            $op_test_progress_lookup = DAO::getObject($link, "SELECT op_test_percentage.* FROM op_test_percentage WHERE programme = '{$std_framework->short_name}' AND {$current_training_month} BETWEEN min_month AND max_month");
                                            if ($test_percentage >= $op_test_progress_lookup->min_percentage)
                                                $test_class = "bg-green";
                                            else
                                                $test_class = "bg-red";
                                        }
                                    }
                                    if ($test_percentage >= 100 || $current_training_month == 0)
                                        $test_class = "bg-green";

                                    $ap_class = 'bg-green';
                                    $course_id = DAO::getSingleValue($link, "SELECT course_id FROM courses_tr WHERE tr_id = '{$tr->id}'");
                                    $total_ap_units = DAO::getSingleValue($link, "SELECT MAX(aps) FROM ap_percentage WHERE course_id = '{$course_id}';");
                                    //$passed_ap_units = DAO::getSingleValue($link, "SELECT COUNT(*) FROM assessment_plan_log WHERE tr_id = '{$tr->id}' AND paperwork = '3';");
                                    $passed_ap_units = DAO::getSingleValue($link, "SELECT COUNT(*) FROM assessment_plan_log LEFT JOIN assessment_plan_log_submissions AS sub ON sub.assessment_plan_id = assessment_plan_log.id AND
		        				sub.id = (SELECT MAX(id) FROM assessment_plan_log_submissions WHERE assessment_plan_log_submissions.assessment_plan_id = assessment_plan_log.id)
				                WHERE tr_id = '{$tr->id}' AND completion_date IS NOT NULL");
                                    $max_month_row = DAO::getObject($link, "SELECT * FROM ap_percentage WHERE course_id = '{$course_id}' ORDER BY id DESC LIMIT 1");
                                    $current_training_month = DAO::getSingleValue($link, "SELECT IF((DAY('{$tr->start_date}')<=13), ((PERIOD_DIFF(DATE_FORMAT(CURDATE(),\"%Y%m\"),DATE_FORMAT('{$tr->start_date}',\"%Y%m\")))+1), ((PERIOD_DIFF(DATE_FORMAT(CURDATE(),\"%Y%m\"),DATE_FORMAT('{$tr->start_date}',\"%Y%m\")))))");
                                    if (isset($max_month_row->id)) {
                                        $ap_class = 'bg-red';
                                        if ($current_training_month == 0)
                                            $ap_class = 'bg-green';
                                        elseif ($current_training_month > $max_month_row->max_month && $passed_ap_units >= $max_month_row->aps)
                                            $ap_class = 'bg-green';
                                        elseif ($current_training_month > $max_month_row->max_month && $passed_ap_units < $max_month_row->aps)
                                            $ap_class = 'bg-red';
                                        else {
                                            $month_row_id = DAO::getSingleValue($link, "SELECT id FROM ap_percentage WHERE course_id = '{$course_id}' AND '{$current_training_month}' BETWEEN min_month AND max_month");
                                            $aps_to_check = DAO::getSingleValue($link, "SELECT aps FROM ap_percentage WHERE course_id = '{$course_id}' AND id < '{$month_row_id}' ORDER BY id DESC LIMIT 1");
                                            if ($aps_to_check == '' || $passed_ap_units >= $aps_to_check)
                                                $ap_class = 'bg-green';
                                        }
                                    }

                                    ?>
                                    <tr>
                                        <?php echo $total_course_units != 0 ? '<td class="text-center ' . $course_class . '"><span class="text-bold" style="padding: 5px;">Technical Course Progress: </span>' . $passed_course_units . '/' . $total_course_units . ' = ' . $course_percentage  . '%</td>' : '<td class="text-center ' . $course_class . '">N/A</td>'; ?>
                                        <?php echo $total_exam_units != 0 ? '<td class="text-center ' . $test_class . '"><span class="text-bold" style="padding: 5px;">Test Progress:  </span>' . $passed_exam_units . '/' . $total_exam_units . ' = ' . $test_percentage  . '%</td>' : '<td class="text-center ' . $test_class . '">N/A</td>'; ?>
                                        <?php echo $total_ap_units != 0 ? '<td class="text-center ' . $ap_class . '"><span class="text-bold" style="padding: 5px;">Assessment Progress:  </span>' . $passed_ap_units . '/' . $total_ap_units . ' = ' . round(($passed_ap_units / $total_ap_units) * 100)  . '%</td>' : '<td class="text-center ' . $ap_class . '">0%</td>'; ?>
                                    </tr>
                                </table>
                                <?php if (SOURCE_BLYTHE_VALLEY || $_SESSION['user']->op_access == 'W') { ?>
                                    <span class="btn btn-primary btn-sm " onclick="$('#EPAModal').modal('show');"><i class="fa fa-plus"></i> Add New</span>
                                    <!--                                <div class="form-group pull-right">-->
                                    <!--                                    <label class="control-label fieldLabel_optional" for ="epa_owner">EPA Owner:</label>-->
                                    <!--                                    --><?php //echo HTML::selectChosen('epa_owner', InductionHelper::getDDLEPAOwner(), $op_details->epa_owner, true); 
                                                                                ?>
                                    <!--                                </div>-->
                                <?php } ?>
                            </div>
                            <div class="box-body">
                                <div class="table-responsive">
                                    <?php
                                    $result = DAO::getResultset($link, "SELECT op_epa.*, (SELECT COUNT(*) FROM op_epa_log WHERE op_epa_id = op_epa.`id`) AS is_logged FROM op_epa WHERE tr_id = '{$tr->id}' ORDER BY task, task_status", DAO::FETCH_ASSOC);
                                    echo '<table class="table table-bordered"><tr><th>Task Type</th><th>Task</th><th class="small" title="Potential Achievement Month">P. Ach. Month</th><th>Yes/No</th><th>Status</th><th>Task Date</th><th>Actual Date</th><th>End Date</th><th>Asmt Mthd1</th><th>Asmt Mthd2</th><th>EPAO</th><th>Comments</th><th></th></tr>';
                                    $op_tasks = InductionHelper::getListOpTask();
                                    $op_task_types = InductionHelper::getListOpTaskType();
                                    $op_tasks_status = InductionHelper::getListOpTaskStatus();
                                    $op_task_epao = InductionHelper::getListOpEpao();
                                    foreach ($result as $row) {
                                        echo '<tr>';
                                        echo isset($op_task_types[$row['task_type']]) ? '<td>' . $op_task_types[$row['task_type']] . '</td>' : '<td>' . $row['task_type'] . '</td>';
                                        echo isset($op_tasks[$row['task']]) ? '<td>' . $op_tasks[$row['task']] . '</td>' : '<td>' . $row['task'] . '</td>';
                                        echo '<td>' . $row['potential_achievement_month'] . '</td>';
                                        echo $row['task_applicable'] == 'N' ? '<td><label class="label label-danger">No</label> </td>' : '<td><label class="label label-success">Yes</label> </td>';
                                        echo isset($op_tasks_status[$row['task_status']]) ? '<td>' . $op_tasks_status[$row['task_status']] . '</td>' : '<td>' . $row['task_status'] . '</td>';
                                        echo '<td>' . Date::toShort($row['task_date']) . '</td>';
                                        echo '<td>' . Date::toShort($row['task_actual_date']) . '</td>';
                                        echo '<td>' . Date::toShort($row['task_end_date']) . '<br>' . $row['task_end_time'] . '</td>';
                                        echo '<td>' . $row['task_assessment_method1'] . '</td>';
                                        echo '<td>' . $row['task_assessment_method2'] . '</td>';
                                        echo isset($op_task_epao[$row['task_epao']]) ? '<td>' . $op_task_epao[$row['task_epao']] . '</td>' : '<td>' . $row['task_epao'] . '</td>';
                                        echo '<td class="small">' . nl2br($row['task_comments']) . '</td>';
                                        //echo '<td><span class="btn btn-primary btn-xs" onclick="$(\'#frmEPA input[name=task_id]\').val(\''.$row['id'].'\');$(\'#EPAModal\').modal(\'show\');"><i class="fa fa-edit"></i> Edit</span></td>';
                                        echo '<td>';
                                        echo '<span class="btn btn-primary btn-xs" onclick="prepareEPAModalForEdit(\'' . $row['id'] . '\', \'' . $row['task'] . '\');"><i class="fa fa-edit"></i> Edit</span>';
                                        if (SOURCE_LOCAL || in_array($_SESSION['user']->username, ["lmargach", "jcoates"]))
                                            echo ' <span class="btn btn-danger btn-xs" onclick="deleteEpaEntry(\'' . $row['id'] . '\');"><i class="fa fa-remove"></i> Del</span>';
                                        if ($row['is_logged'] > 0)
                                            echo '<span class="btn btn-primary btn-xs" title="View change logs" onclick="showEpaEntryLog(\'' . $row['id'] . '\');"><i class="fa fa-info_circle"></i> Log</span>';
                                        echo '</td>';
                                        echo '</tr>';
                                    }
                                    echo '</table>';
                                    ?>
                                </div>
                            </div>
                        </div>
                        <div class="box">
                            <form class="form-horizontal" method="post" name="frmPdprep" action="<?php echo $_SERVER['PHP_SELF']; ?>">
				                <input type="hidden" name="id" value="<?php echo $op_details->tr_id; ?>" />
                                <input type="hidden" name="tr_id" value="<?php echo $op_details->tr_id; ?>" />
                                <input type="hidden" name="_action" value="save_op_learner" />
                                <input type="hidden" name="formName" value="frmPdprep" />
                                <input type="hidden" name="tracker_id" value="<?php echo $tracker_id; ?>" />
                                <input type="hidden" name="total_mock_interviews" value="<?php echo !is_null($mock_interviews) ? count($mock_interviews->Set)+1 : 1; ?>" />
                                <input type="hidden" name="total_project_prep_session" value="<?php echo !is_null($project_prep_session) ? count($project_prep_session->Set)+1 : 1; ?>" />
                                <div class="box-header">
                                    <span class="box-title lead">PD Prep & Project:</span>
                                    <span class="btn btn-success btn-sm pull-right" onclick="submitFrmPdprep();"><i class="fa fa-save"></i> Save Information</span>
                                </div>
                                <div class="box-body">
                                    <div class="table-responsive">
                                        <table class="table table-bordered">
                                            <tr>
                                                <th colspan="3" class="bg-gray">PD Prep:</th>
                                            </tr>
                                            <tr>
                                                <th style="width: 33%;">Professional Discussion Prep - Month 9</th>
                                                <td style="width: 33%;">
                                                    <strong>Actual Date:</strong><br>
                                                    <?php echo HTML::datebox('pdp_month9_date', $op_details->pdp_month9_date); ?>
                                                </td>
                                                <td style="width: 33%;">
                                                    <strong>Completed:</strong><br>
                                                    <?php echo HTML::selectChosen('pdp_month9_completed', ['Yes', 'No'], $op_details->pdp_month9_completed, true); ?>
                                                </td>
                                            </tr>
                                            <tr>
                                                <th>Professional Discussion Prep - Month 12</th>
                                                <td>
                                                    <strong>Actual Date:</strong><br>
                                                    <?php echo HTML::datebox('pdp_month12_date', $op_details->pdp_month12_date); ?>
                                                </td>
                                                <td>
                                                    <strong>Completed:</strong><br>
                                                    <?php echo HTML::selectChosen('pdp_month12_completed', ['Yes', 'No'], $op_details->pdp_month12_completed, true); ?>
                                                </td>
                                            </tr>
                                            <tr>
                                                <th>Coach Signatures</th>
                                                <td>
                                                    <?php echo HTML::selectChosen('pdp_coach_sign', DAO::getResultset($link, "SELECT id, CONCAT(firstnames, ' ', surname) FROM users WHERE users.type = 3 AND web_access = 1 ORDER BY firstnames"), $op_details->pdp_coach_sign, true); ?>
                                                </td>
                                                <td></td>
                                            </tr>
                                        </table>
                                    </div>
                                    <div class="table-responsive">
                                        <!-- <table class="table table-bordered">
                                            <tr>
                                                <th style="width: 33%;">Mock Interview</th>
                                                <td style="width: 22%;">
                                                    <strong>Planned Date:</strong><br>
                                                    <?php echo HTML::datebox('mock_interview_planned_date', $op_details->mock_interview_planned_date); ?>
                                                </td>
                                                <td style="width: 22%;">
                                                    <strong>Actual Date:</strong><br>
                                                    <?php echo HTML::datebox('mock_interview_actual_date', $op_details->mock_interview_actual_date); ?>
                                                </td>
                                                <td style="width: 22%;">
                                                    <strong>Completed:</strong><br>
                                                    <?php echo HTML::selectChosen('mock_interview_completed', ['Yes', 'No'], $op_details->mock_interview_completed, true); ?>
                                                </td>
                                            </tr>
                                        </table> -->
					<table class="table table-bordered" id="tblProjectPrepSession">
                                            <tbody>
                                                <?php 
                                                if(!is_null($project_prep_session))
                                                {
                                                    $mc = 0;
                                                    foreach($project_prep_session->Set AS $Set)
                                                    {
                                                        ++$mc;
                                                        echo '<tr id="trProjectPrepSession_'.$mc.'">';
                                                        echo '<th style="width: 33%;" id="thProjectPrepSession_'.$mc.'">EPA Project Prep Session '.$mc.'</th>';
                                                        echo '<td style="width: 22%;">';
                                                        echo '<strong>Planned Date:</strong><br>';
                                                        echo '<input class="form-control datepicker" type="text" id="project_prep_session_planned_date_'.$mc.'" name="project_prep_session_planned_date_'.$mc.'" value="'.$Set->PlannedDate->__toString().'" size="10" maxlength="10" placeholder="dd/mm/yyyy">';
                                                        echo '</td>';
                                                        echo '<td style="width: 22%;">';
                                                        echo '<strong>Actual Date:</strong><br>';
                                                        echo '<input class="form-control datepicker" type="text" id="project_prep_session_interview_actual_date_'.$mc.'" name="project_prep_session_interview_actual_date_'.$mc.'" value="'.$Set->ActualDate->__toString().'" size="10" maxlength="10" placeholder="dd/mm/yyyy">';
                                                        echo '</td>';
                                                        echo '<td style="width: 22%;">';
                                                        echo '<strong>Completed:</strong><br>';
                                                        echo '<select name="project_prep_session_completed_'.$mc.'" id="project_prep_session_completed_'.$mc.'" class="chosen-select optional form-control">';
                                                        echo '<option value=""></option>';
                                                        echo '<option value="Yes" ' . ($Set->Completed->__toString() == 'Yes' ? 'selected="selected"' : '') . '>Yes</option>';
                                                        echo '<option value="No" ' . ($Set->Completed->__toString() == 'No' ? 'selected="selected"' : '') . '>No</option>';
                                                        echo '</select>';
                                                        echo '</td>';
                                                        echo '</tr>';
                                                    }
                                                    ++$mc;
                                                    echo '<tr id="trProjectPrepSession_'.$mc.'">';
                                                    echo '<th style="width: 33%;" id="thProjectPrepSession_'.$mc.'">EPA Project Prep Session '.$mc.'</th>';
                                                    echo '<td style="width: 22%;">';
                                                    echo '<strong>Planned Date:</strong><br>';
                                                    echo '<input class="form-control datepicker" type="text" id="project_prep_session_planned_date_'.$mc.'" name="project_prep_session_planned_date_'.$mc.'" value="" size="10" maxlength="10" placeholder="dd/mm/yyyy">';
                                                    echo '</td>';
                                                    echo '<td style="width: 22%;">';
                                                    echo '<strong>Actual Date:</strong><br>';
                                                    echo '<input class="form-control datepicker" type="text" id="project_prep_session_interview_actual_date_'.$mc.'" name="project_prep_session_interview_actual_date_'.$mc.'" value="" size="10" maxlength="10" placeholder="dd/mm/yyyy">';
                                                    echo '</td>';
                                                    echo '<td style="width: 22%;">';
                                                    echo '<strong>Completed:</strong><br>';
                                                    echo '<select name="project_prep_session_completed_'.$mc.'" id="project_prep_session_completed_'.$mc.'" class="chosen-select optional form-control">';
                                                    echo '<option value=""></option>';
                                                    echo '<option value="Yes">Yes</option>';
                                                    echo '<option value="No">No</option>';
                                                    echo '</select>';
                                                    echo '</td>';
                                                    echo '</tr>';
                                                }
                                                else
                                                {
                                                    $mc = 1;
                                                    echo '<tr id="trProjectPrepSession_'.$mc.'">';
                                                    echo '<th style="width: 33%;" id="thProjectPrepSession_'.$mc.'">EPA Project Prep Session '.$mc.'</th>';
                                                    echo '<td style="width: 22%;">';
                                                    echo '<strong>Planned Date:</strong><br>';
                                                    echo '<input class="form-control datepicker" type="text" id="project_prep_session_planned_date_'.$mc.'" name="project_prep_session_planned_date_'.$mc.'" value="" size="10" maxlength="10" placeholder="dd/mm/yyyy">';
                                                    echo '</td>';
                                                    echo '<td style="width: 22%;">';
                                                    echo '<strong>Actual Date:</strong><br>';
                                                    echo '<input class="form-control datepicker" type="text" id="project_prep_session_interview_actual_date_'.$mc.'" name="project_prep_session_interview_actual_date_'.$mc.'" value="" size="10" maxlength="10" placeholder="dd/mm/yyyy">';
                                                    echo '</td>';
                                                    echo '<td style="width: 22%;">';
                                                    echo '<strong>Completed:</strong><br>';
                                                    echo '<select name="project_prep_session_completed_'.$mc.'" id="project_prep_session_completed_'.$mc.'" class="chosen-select optional form-control">';
                                                    echo '<option value=""></option>';
                                                    echo '<option value="Yes">Yes</option>';
                                                    echo '<option value="No">No</option>';
                                                    echo '</select>';
                                                    echo '</td>';
                                                    echo '</tr>';
                                                }
                                                ?>
                                            </tbody>
                                        </table>
                                        <table class="table table-bordered" id="tblMockInterview">
                                            <tbody>
                                                <?php 
                                                if(!is_null($mock_interviews))
                                                {
                                                    $mc = 0;
                                                    foreach($mock_interviews->Set AS $Set)
                                                    {
                                                        ++$mc;
                                                        echo '<tr id="trMockInterview_'.$mc.'">';
                                                        echo '<th style="width: 33%;" id="thMockInterview_'.$mc.'">Mock Interview '.$mc.'</th>';
                                                        echo '<td style="width: 22%;">';
                                                        echo '<strong>Planned Date:</strong><br>';
                                                        echo '<input class="form-control datepicker" type="text" id="mock_interview_planned_date_'.$mc.'" name="mock_interview_planned_date_'.$mc.'" value="'.$Set->PlannedDate->__toString().'" size="10" maxlength="10" placeholder="dd/mm/yyyy">';
                                                        echo '</td>';
                                                        echo '<td style="width: 22%;">';
                                                        echo '<strong>Actual Date:</strong><br>';
                                                        echo '<input class="form-control datepicker" type="text" id="mock_interview_actual_date_'.$mc.'" name="mock_interview_actual_date_'.$mc.'" value="'.$Set->ActualDate->__toString().'" size="10" maxlength="10" placeholder="dd/mm/yyyy">';
                                                        echo '</td>';
                                                        echo '<td style="width: 22%;">';
                                                        echo '<strong>Completed:</strong><br>';
                                                        echo '<select name="mock_interview_completed_'.$mc.'" id="mock_interview_completed_'.$mc.'" class="chosen-select optional form-control">';
                                                        echo '<option value=""></option>';
                                                        echo '<option value="Yes" ' . ($Set->Completed->__toString() == 'Yes' ? 'selected="selected"' : '') . '>Yes</option>';
                                                        echo '<option value="No" ' . ($Set->Completed->__toString() == 'No' ? 'selected="selected"' : '') . '>No</option>';
                                                        echo '</select>';
                                                        echo '</td>';
                                                        echo '</tr>';
                                                    }
                                                    ++$mc;
                                                    echo '<tr id="trMockInterview_'.$mc.'">';
                                                    echo '<th style="width: 33%;" id="thMockInterview_'.$mc.'">Mock Interview '.$mc.'</th>';
                                                    echo '<td style="width: 22%;">';
                                                    echo '<strong>Planned Date:</strong><br>';
                                                    echo '<input class="form-control datepicker" type="text" id="mock_interview_planned_date_'.$mc.'" name="mock_interview_planned_date_'.$mc.'" value="" size="10" maxlength="10" placeholder="dd/mm/yyyy">';
                                                    echo '</td>';
                                                    echo '<td style="width: 22%;">';
                                                    echo '<strong>Actual Date:</strong><br>';
                                                    echo '<input class="form-control datepicker" type="text" id="mock_interview_actual_date_'.$mc.'" name="mock_interview_actual_date_'.$mc.'" value="" size="10" maxlength="10" placeholder="dd/mm/yyyy">';
                                                    echo '</td>';
                                                    echo '<td style="width: 22%;">';
                                                    echo '<strong>Completed:</strong><br>';
                                                    echo '<select name="mock_interview_completed_'.$mc.'" id="mock_interview_completed_'.$mc.'" class="chosen-select optional form-control">';
                                                    echo '<option value=""></option>';
                                                    echo '<option value="Yes">Yes</option>';
                                                    echo '<option value="No">No</option>';
                                                    echo '</select>';
                                                    echo '</td>';
                                                    echo '</tr>';
                                                }
                                                else
                                                {
                                                    $mc = 1;
                                                    echo '<tr id="trMockInterview_'.$mc.'">';
                                                    echo '<th style="width: 33%;" id="thMockInterview_'.$mc.'">Mock Interview '.$mc.'</th>';
                                                    echo '<td style="width: 22%;">';
                                                    echo '<strong>Planned Date:</strong><br>';
                                                    echo '<input class="form-control datepicker" type="text" id="mock_interview_planned_date_'.$mc.'" name="mock_interview_planned_date_'.$mc.'" value="" size="10" maxlength="10" placeholder="dd/mm/yyyy">';
                                                    echo '</td>';
                                                    echo '<td style="width: 22%;">';
                                                    echo '<strong>Actual Date:</strong><br>';
                                                    echo '<input class="form-control datepicker" type="text" id="mock_interview_actual_date_'.$mc.'" name="mock_interview_actual_date_'.$mc.'" value="" size="10" maxlength="10" placeholder="dd/mm/yyyy">';
                                                    echo '</td>';
                                                    echo '<td style="width: 22%;">';
                                                    echo '<strong>Completed:</strong><br>';
                                                    echo '<select name="mock_interview_completed_'.$mc.'" id="mock_interview_completed_'.$mc.'" class="chosen-select optional form-control">';
                                                    echo '<option value=""></option>';
                                                    echo '<option value="Yes">Yes</option>';
                                                    echo '<option value="No">No</option>';
                                                    echo '</select>';
                                                    echo '</td>';
                                                    echo '</tr>';
                                                }
                                                ?>
                                            </tbody>
                                        </table>
                                    </div>
                                    <div class="table-responsive">
                                        <table class="table table-bordered">
                                            <tr>
                                                <th colspan="5" class="bg-gray">Project Check ins:</th>
                                            </tr>
                                            <tr>
                                                <th style="width: 20%;">
                                                    Project Check In <br>
                                                    <span class="btn btn-sm btn-info pull-right" title="show saved information" onclick="showProjectCheckinNotes();">
                                                        <i class="fa fa-comments"></i> View Saved Information
                                                    </span>
                                                </th>
                                                <td style="width: 10%;">
                                                    <input type="checkbox" name="chk_save_project_checkin" id="chk_save_project_checkin" value="1" /><br>
                                                    <span class="small text-info">Select this tickbox if you want to update Check in Date and Comments.</span>
                                                </td>
                                                <td style="width: 20%;">
                                                    <strong>Check In Date Week 1:</strong><br>
                                                    <?php 
                                                    echo isset($project_checkin->DateWeek1) ? 
                                                        HTML::datebox('project_checkin_date_week1', $project_checkin->DateWeek1->__toString()) :
                                                        HTML::datebox('project_checkin_date_week1', ''); 
                                                    ?><br>
                                                    <strong>Check In Date Week 2:</strong><br>
                                                    <?php 
                                                    echo isset($project_checkin->DateWeek2) ? 
                                                        HTML::datebox('project_checkin_date_week2', $project_checkin->DateWeek2->__toString()) :
                                                        HTML::datebox('project_checkin_date_week2', ''); 
                                                    ?><br>
                                                    <strong>Check In Date Week 3:</strong><br>
                                                    <?php 
                                                    echo isset($project_checkin->DateWeek3) ? 
                                                        HTML::datebox('project_checkin_date_week3', $project_checkin->DateWeek3->__toString()) :
                                                        HTML::datebox('project_checkin_date_week3', ''); 
                                                    ?><br>
                                                    <strong>Check In Date Week 4:</strong><br>
                                                    <?php 
                                                    echo isset($project_checkin->DateWeek4) ? 
                                                        HTML::datebox('project_checkin_date_week4', $project_checkin->DateWeek4->__toString()) :
                                                        HTML::datebox('project_checkin_date_week4', ''); 
                                                    ?>
                                                </td>
                                                <td style="width: 20%;">
                                                    <strong>Check In Happened Week 1:</strong><br>
                                                    <?php 
                                                    echo isset($project_checkin->CheckInDoneWeek1) ? 
                                                        HTML::selectChosen('project_checkin_done_week1', ['Yes', 'No'], $project_checkin->CheckInDoneWeek1->__toString(), true) :
                                                        HTML::selectChosen('project_checkin_done_week1', ['Yes', 'No'], '', true); 
                                                    ?><br>
                                                    <strong>Check In Happened Week 2:</strong><br>
                                                    <?php 
                                                    echo isset($project_checkin->CheckInDoneWeek2) ? 
                                                        HTML::selectChosen('project_checkin_done_week2', ['Yes', 'No'], $project_checkin->CheckInDoneWeek2->__toString(), true) :
                                                        HTML::selectChosen('project_checkin_done_week2', ['Yes', 'No'], '', true); 
                                                    ?><br>
                                                    <strong>Check In Happened Week 3:</strong><br>
                                                    <?php 
                                                    echo isset($project_checkin->CheckInDoneWeek3) ? 
                                                        HTML::selectChosen('project_checkin_done_week3', ['Yes', 'No'], $project_checkin->CheckInDoneWeek3->__toString(), true) :
                                                        HTML::selectChosen('project_checkin_done_week3', ['Yes', 'No'], '', true); 
                                                    ?><br>
                                                    <strong>Check In Happened Week 4:</strong><br>
                                                    <?php 
                                                    echo isset($project_checkin->CheckInDoneWeek4) ? 
                                                        HTML::selectChosen('project_checkin_done_week4', ['Yes', 'No'], $project_checkin->CheckInDoneWeek4->__toString(), true) :
                                                        HTML::selectChosen('project_checkin_done_week4', ['Yes', 'No'], '', true); 
                                                    ?><br>
                                                </td>
                                                <td style="width: 30%;">
                                                    <strong>Comments:</strong><br>
                                                    <textarea class="form-control" name="project_checkin_comments" id="project_checkin_comments" rows="10"><?php echo isset($project_checkin->Comments) ? $project_checkin->Comments : ''; ?></textarea>
                                                </td>
                                            </tr>
                                        </table>
                                    </div>
                                    <div class="table-responsive">
                                        <table class="table table-bordered">
                                            <tr>
                                                <th class="bg-gray">Project Plan:</th>
                                            </tr>
                                            <tr>
                                                <td>
                                                    <textarea class="form-control" name="project_plan" id="project_plan" rows="10"><?php echo isset($op_details->project_plan) ? $op_details->project_plan : ''; ?></textarea>
                                                </td>
                                            </tr>
                                        </table>
                                    </div>
                                </div>
                                <div class="box-footer">
                                    <span class="btn btn-success btn-sm btn-block" onclick="submitFrmPdprep();"><i class="fa fa-save"></i> Save Information</span>
                                </div>
                            </form>    
                        </div>
                    </div>
                    <div class="tab-pane" id="tab7">
                        <div class="row">
                            <div class="col-sm-12">
                                <div class="box box-solid well callout">
                                    <div class="box-header no-pad">
                                        <?php if (SOURCE_BLYTHE_VALLEY || $_SESSION['user']->op_access == 'W') { ?>
                                            <span class="btn btn-sm btn-primary pull-right" onclick="saveAdditionalInfo();"><i class="fa fa-save"></i> Save Additional Information</span>
                                        <?php } ?>
                                    </div>
                                    <div class="box-body">
                                        <div class="row">
                                            <form autocomplete="off" name="frmAdditionalInfo" id="frmAdditionalInfo" action="<?php echo $_SERVER['PHP_SELF']; ?>">
                                                <input type="hidden" name="formName" value="frmAdditionalInfo" />
                                                <input type="hidden" name="id" value="<?php echo $op_details->tr_id; ?>" />
                                                <input type="hidden" name="tracker_id" value="<?php echo $tracker_id; ?>" />
                                                <div class="form-group">
                                                    <label for="type" class="col-sm-4 control-label fieldLabel_compulsory">Type:</label>
                                                    <div class="col-sm-8">
                                                        <?php echo HTML::selectChosen('type', DAO::getResultset($link, "SELECT id, description, null FROM lookup_op_add_details_types ORDER BY description"), '', true, true); ?>
                                                        <?php if (SOURCE_BLYTHE_VALLEY || $_SESSION['user']->op_access == 'W') { ?>
                                                            <span class="btn btn-xs btn-info" id="btnNewType" title="Add new type" onclick="$('#btnNewType').hide();$('#divNewType').show();">&nbsp;+&nbsp;</span>
                                                        <?php } ?>
                                                    </div>
                                                </div>
                                                <div class="form-group" id="divNewType" style="display: none;">
                                                    <label for="txNewtType" class="col-sm-4 control-label fieldLabel_optional">Enter New Type:</label>
                                                    <div class="col-sm-8">
                                                        <div class="callout">
                                                            <input class="form-control optional" type="text" id="txtNewType" value="" size="50" maxlength="50" />
                                                            <p class="small"> 50 characters max.</p>
                                                            <span class="btn btn-xs btn-primary" onclick="saveNewType();">&nbsp;Save Type&nbsp;</span>
                                                            <span class="btn btn-xs btn-info" onclick="$('#btnNewType').show();$('#divNewType').hide();">&nbsp;Cancel&nbsp;</span>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <label for="input_date" class="col-sm-4 control-label fieldLabel_optional">Date:</label>
                                                    <div class="col-sm-6">
                                                        <?php echo HTML::datebox('date', '', false); ?>
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <label for="detail" class="col-sm-4 control-label fieldLabel_optional">Detail:</label>
                                                    <div class="col-sm-8">
                                                        <textarea class="form-control optional" name="detail" id="detail" rows="5"></textarea>
                                                    </div>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-12">
                                <div class="box box-solid">
                                    <div class="box-header">
                                        <p class="lead">Additional Information</p>
                                    </div>
                                    <div class="box-body">
                                        <div class="row">
                                            <?php echo $this->renderAdditionalInformation($link, $op_details->additional_info); ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php if (SOURCE_LOCAL || DB_NAME == "am_baltic_demo") { ?>
                        <div class="tab-pane" id="tab8">
                            <div class="row">
                                <div class="col-sm-12"><span id="btnCompose" class="btn btn-primary btn-block margin-bottom" onclick="$(this).hide(); $('#mailBox').hide(); $('#composeNewMessageBox').show();">Compose</span></div>
                                <div class="col-sm-12" id="composeNewMessageBox" style="display: none;">
                                    <?php echo $this->renderComposeNewMessageBox($link, $tr, $tracker_id); ?>
                                </div>
                                <div class="col-sm-12" id="mailBox">
                                    <?php echo $this->renderMailbox($link, $tr); ?>
                                </div>
                            </div>
                        </div>
                    <?php } ?>
                    <div class="tab-pane" id="tab9">
                        <div class="row">
                            <div class="col-sm-12">
                                <?php if (Complaint::userWithEditAccess($_SESSION['user']->username)) { ?>
                                    <span class="btn btn-primary btn-sm " onclick="window.location.href='do.php?_action=edit_complaint_learner&record_id=<?php echo $tr->id; ?>';"><i class="fa fa-plus"></i> Add New</span>
                                <?php } ?>
                                <?php echo $this->renderComplaintsTable($link, $tr); ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>

    </div>

    <div class="loading-image" style="display: none;"></div>

    <?php
    if ($restart == "1" && $bil_tr_id != "") {
        echo <<<HTML
	<div class="modal fade" id="restartModal" role="dialog" data-backdrop="static" data-keyboard="false">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header"><button type="button" class="close" data-dismiss="modal">&times;</button><h5 class="modal-title text-bold"><i class="fa fa-warning"></i> Merge</h5></div>
				<div class="modal-body">
					<p>This action will pull information from existing BIL training record and store against this record. </p>
					<p>Are you sure you want continue?</p>
				</div>
				<div class="modal-footer">
					<span class="btn btn-sm btn-default pull-left" onclick="$('#restartModal').modal('hide');">No</span>
					<span class="btn btn-sm btn-primary pull-right" onclick="pullBILInformationForOperations();">Yes</span>
				</div>
			</div>
		</div>
	</div>
HTML;
    }
    ?>

    <div class="modal fade" id="EPAModal" role="dialog" data-backdrop="static" data-keyboard="false">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h5 class="modal-title text-bold">Details</h5>
                </div>
                <div class="modal-body">
                    <form class="form-horizontal" method="post" name="frmEPA" id="frmEPA" method="post" action="do.php?_action=save_op_learner">
                        <input type="hidden" name="formName" value="frmEPA" />
                        <input type="hidden" name="id" value="<?php echo $tr->id; ?>" />
                        <input type="hidden" name="task_id" value="" />
                        <input type="hidden" name="tracker_id" value="<?php echo $tracker_id; ?>" />
                        <div class="control-group">
                            <label class="control-label" for="task_type">Task Type:</label>
                            <?php echo HTML::selectChosen('task_type', InductionHelper::getDDLOpTaskType(), '', true, true); ?>
                        </div>
                        <div class="row">
                            <div class="col-sm-6">
                                <div class="control-group">
                                    <label class="control-label" for="task">Task:</label>
                                    <?php echo HTML::selectChosen('task', InductionHelper::getDDLOpTask($tracker_id), '', true, true); ?>
                                </div>
                            </div>
                            <div class="col-sm-3">
                                <div class="control-group">
                                    <label class="control-label" for="task_epao"><small>EPAO:</small></label>
                                    <?php echo HTML::selectChosen('task_epao', InductionHelper::getDDLOpEpao(), '', true, false); ?>
                                </div>
                            </div>
                            <div class="col-sm-3">
                                <div class="control-group">
                                    <label class="control-label" for="potential_achievement_month"><small>Potential Ach.Month:</small></label>
                                    <?php echo HTML::selectChosen('potential_achievement_month', InductionHelper::getDDLPotentialAchMonth(), '', true, false); ?>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-4">
                                <div class="control-group">
                                    <label class="control-label" for="task_applicable">Yes / No:</label> &nbsp;
                                    <?php echo HTML::selectChosen('task_applicable', InductionHelper::getDDLYesNo(), '', false, true); ?>
                                </div>
                            </div>   
                            <div class="col-sm-6">
                                <div class="control-group">
                                    <label class="control-label" for="task_status">Status:</label>
                                    <?php echo HTML::selectChosen('task_status', InductionHelper::getDDLOpTaskStatus(), '', true, true); ?>
                                </div>
                            </div>                         
                        </div>
                        <div class="row">
                            <div class="col-sm-4">
                                <div class="control-group">
                                    <label class="control-label" for="task_date">Task Date:</label>
                                    <input type="text" class="form-control compulsory required datepicker" id="task_date" name="task_date" value="" placeholder="dd/mm/yyyy">
                                </div>
                            </div>
                            <div class="col-sm-4">
                                <div class="control-group">
                                    <label class="control-label" for="task_actual_date">Actual Date:</label>
                                    <input type="text" class="form-control datepicker" id="task_actual_date" name="task_actual_date" value="" placeholder="dd/mm/yyyy">
                                </div>
                            </div>
                            <div class="col-sm-4">
                                <div class="control-group">
                                    <label class="control-label" for="task_end_date">Task End Date:</label>
                                    <input type="text" class="form-control datepicker" id="task_end_date" name="task_end_date" value="" placeholder="dd/mm/yyyy">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-6">
                                <div class="control-group">
                                    <label class="control-label" for="task_assessment_method1">Assessment Method 1:</label>
                                    <?php echo HTML::selectChosen('task_assessment_method1', InductionHelper::getDdlEpaAssessmentMethods(), '', true); ?>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="control-group">
                                    <label class="control-label" for="task_assessment_method2">Assessment Method 2:</label>
                                    <?php echo HTML::selectChosen('task_assessment_method2', InductionHelper::getDdlEpaAssessmentMethods(), '', true); ?>
                                </div>
                            </div>
                        </div>
                        <div class="control-group">
                            <label class="control-label" for="task_comments">Comments:</label>
                            <textarea class="form-control" name="task_comments" id="task_comments" rows="5" style="width: 100%;"></textarea>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default pull-left btn-md" onclick="$('#EPAModal').modal('hide');">Cancel</button>
                    <button type="button" id="btnEPAModalSave" class="btn btn-primary btn-md"><i class="fa fa-save"></i> Save</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="LAREntryUpdateModal" role="dialog" data-backdrop="static" data-keyboard="false">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h5 class="modal-title text-bold">Add/Edit Notes for this LAR update</h5>
                </div>
                <div class="modal-body">
                    <div class="small">
                        <table class="table table-bordered">
                            <tr>
                                <th>Creation Date Time</th>
                                <td id="modal_creation_date_time"></td>
                            </tr>
                            <tr>
                                <th>Create By</th>
                                <td id="modal_created_by"></td>
                            </tr>
                            <tr>
                                <th>Type</th>
                                <td id="modal_type"></td>
                            </tr>
                            <tr>
                                <th>LAR Date</th>
                                <td id="modal_lar_date"></td>
                            </tr>
                            <tr>
                                <th>Last Action Date</th>
                                <td id="modal_last_action_date"></td>
                            </tr>
                            <tr>
                                <th>Revisit Date</th>
                                <td id="modal_next_action_date"></td>
                            </tr>
                            <tr>
                                <th>RAG</th>
                                <td id="modal_rag"></td>
                            </tr>
                        </table>
                    </div>
                    <form autocomplete="off" class="form-horizontal" method="post" name="frmLAREntryUpdate" id="frmLAREntryUpdate" method="post" action="do.php?_action=ajax_tracking">
                        <input type="hidden" name="subaction" value="save_lar_entry_update" />
                        <input type="hidden" name="formName" value="frmLAREntryUpdate" />
                        <input type="hidden" name="tr_id" value="<?php echo $tr->id; ?>" />
                        <input type="hidden" name="modal_timestamp" id="modal_timestamp" value="" />
                        <div class="control-group">
                            <label class="control-label" for="modal_lar_notes">LAR Notes:</label>
                            <textarea class="form-control optional" name="modal_lar_notes" id="modal_lar_notes" rows="5"></textarea>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default pull-left btn-md" onclick="$('#LAREntryUpdateModal').modal('hide');">Cancel</button>
                    <button type="button" id="btnLAREntryUpdateModalSave" class="btn btn-primary btn-md"><i class="fa fa-save"></i> Save</button>
                </div>
            </div>
        </div>
    </div>

    <div id="dialogPreview" title="Email content" style="font-size: smaller;"></div>

    <br>

    <script src="/assets/adminlte/plugins/jQuery/jquery-2.2.3.min.js"></script>
    <script src="/assets/adminlte/plugins/jQueryUI/jquery-ui.min.js"></script>
    <script src="/assets/adminlte/bootstrap/js/bootstrap.min.js"></script>
    <script src="/assets/adminlte/plugins/chosen/chosen.jquery.js"></script>
    <script src="/assets/adminlte/plugins/iCheck/icheck.min.js"></script>
    <script src="/assets/adminlte/dist/js/app.min.js"></script>
    <script src="/assets/adminlte/plugins/summernote/summernote.min.js"></script>
    <script src="/assets/adminlte/plugins/datatables/jquery.dataTables.min.js"></script>
    <script src="/common.js" type="text/javascript"></script>

    <script language="JavaScript">
        function prepareEPAModalForEdit(op_epa_id, task) {
            <?php if ($_SESSION['user']->op_access != 'W') { ?>
                return alert('You are not authorised to perform this action.');
            <?php } ?>

            $('#task_status').val();
            var form = document.forms['frmEPA'];
            var task_status = form.elements['task_status'];
            ajaxPopulateSelect(task_status, 'do.php?_action=ajax_tracking&subaction=load_op_epa_status&op_task=' + task);

            $.ajax({
                type: 'GET',
                dataType: 'json',
                url: 'do.php?_action=ajax_tracking&subaction=get_op_epa_record&id=' + op_epa_id,
                async: false,
                success: function(data) {
                    $.each(data, function(key, value) {
                        if (key == 'id')
                            $('#frmEPA input[name=task_id]').val(value);
			else if (key == 'task_end_time')
                            $('#frmEPA input[name=task_end_time]').val(value);
                        else
                            $('#frmEPA #' + key).val(value);
                    });
                    $('#EPAModal').modal('show');
                },
                error: function(data, textStatus, xhr) {
                    console.log(data.responseText);
                }
            });
        }

        function pullBILInformationForOperations() {
            var bil_id = '<?php echo $bil_tr_id; ?>';
            var con_id = '<?php echo $tr->id; ?>';
            $.ajax({
                type: 'GET',
                url: 'do.php?_action=ajax_tracking&subaction=pullBILInformationForOperations',
                data: {
                    bil_id: bil_id,
                    con_id: con_id
                },
                dataType: 'json',
                async: false,
                beforeSend: function() {
                    $(".loading-image").show();
                },
                success: function() {
                    $(".loading-image").hide();
                    $('#restartModal').modal('hide');
                    window.location.reload();
                },
                error: function() {
                    console.log('error');
                    $(".loading-image").hide();
                }
            });
        }

        $(function() {
            $('.datepicker').datepicker({
                format: 'dd/mm/yyyy',
                yearRange: 'c-50:c+50'
            });

            // $('#actively_involved_in').chosen({
            //     width: "100%"
            // });
            $('#lar_reason, #lras_reason, #lras_support_provider, #lras_owner').chosen({
                width: "100%"
            });
            // $('#sec_lar_reason').chosen({
            //     width: "100%"
            // });

            $("button#btnEPAModalSave").click(function() {
                if (document.forms['frmEPA'].task.value != 18 && validateForm(document.forms['frmEPA']) == false) {
                    return;
                }
                $("#frmEPA").submit();
            });

            $("button#btnLAREntryUpdateModalSave").click(function(e) {
                e.preventDefault();

                var form = $("#frmLAREntryUpdate");
                var url = form.attr('action');

                $.ajax({
                    type: "POST",
                    url: url,
                    data: form.serialize(),
                    success: function(data) {
                        window.location.reload();
                    },
                    error: function(data) {
                        console.log(data);
                    }
                });
            });

            $('#task').change(function() {
                $('#task_status').val();
                var form = document.forms['frmEPA'];
                var task_status = form.elements['task_status'];
                ajaxPopulateSelect(task_status, 'do.php?_action=ajax_tracking&subaction=load_op_epa_status&op_task=' + this.value);
            });

            $(".datepicker").addClass("form-control");

            $('input[class=radioMainContact]').iCheck({
                checkboxClass: 'icheckbox_flat-red'
            });

            $('input[class=radioRedLight]').iCheck({
                radioClass: 'iradio_square-red',
                increaseArea: '20%'
            });
            $('input[class=radioOrangeLight]').iCheck({
                radioClass: 'iradio_square-orange',
                increaseArea: '20%'
            });
            $('input[class=radioYellowLight]').iCheck({
                radioClass: 'iradio_square-yellow',
                increaseArea: '20%'
            });

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

        function saveFrmLearner() {
            var min_date = stringToDate('<?php echo $tr->created; ?>');
            min_date.setHours(0, 0, 0, 0);
            var learner_48_hour_call_max_date = stringToDate('<?php echo $learner_48_hour_call_max_date->formatMySQL(); ?>');
            learner_48_hour_call_max_date.setHours(0, 0, 0, 0);
            var learner_3_week_max_date = stringToDate('<?php echo $learner_3_week_max_date->formatMySQL(); ?>');
            learner_3_week_max_date.setHours(0, 0, 0, 0);

            if ($('#input_week_3_call').val() != '') {
                var input_week_3_call = stringToDate($('#input_week_3_call').val());
                input_week_3_call.setHours(0, 0, 0, 0);
                if (input_week_3_call < min_date) {
                    if (!confirm('Week 3 call date is before the time period, are you sure you want to continue?'))
                        return;
                }
                if (input_week_3_call > learner_3_week_max_date) {
                    if (!confirm('Week 3 call date is after the time period, are you sure you want to continue?'))
                        return;
                }
            }

            // if ($('#last_learn_evidence').val() == "OTH" && $('#last_learn_evidence_notes').val().trim() == '') {
            //     alert('Please enter notes for "Last Learning Evidence" as you have selected the option "Other" ');
            //     return;
            // }

            if ($('#is_completed').val() == "Y" && $('#input_completed_date').val().trim() == '') {
                alert('Please input the "Completion Date" as you have selected "Completed" field "Yes" ');
                $('#input_completed_date').focus();
                return;
            }

            if ($('#lar').val() == "Y" && $('#input_lar_date').val().trim() == '') {
                alert('Please input "LAR Date"');
                $('#input_lar_date').focus();
                return;
            }

            // if ($('#break_in_learning').val() == "Y" && $('#input_bil_date').val().trim() == '') {
            //     alert('Please input "Break in Learning Date"');
            //     $('#input_bil_date').focus();
            //     return;
            // }

            if ($('#leaver').val() == "Y" && $('#input_leaver_date').val().trim() == '') {
                alert('Please input "Leaver Date" in the Leavers section');
                $('#input_leaver_date').focus();
                return;
            }

	    if ($('#leaver').val() == "Y" && $('#input_leaver_decision_made').val().trim() == '') {
                alert('Please input "Leaver Decision Date" in the Leavers section');
                $('#input_leaver_decision_made').focus();
                return;
            }
            var frmLearner = document.forms["frmLearner"];
            var selectedContacts = [];
            $("input[name='main_contact_ids[]']").each(function() {
                if (this.checked)
                    selectedContacts.push(this.value);
            });
            frmLearner.elements["main_contact_id"].value = selectedContacts.join(',');
            frmLearner.submit();
        }

        function uploadFile() {
            var myForm = document.forms["frmOpLearnerFiles"];
            if (validateForm(myForm) == false) {
                return false;
            }
            myForm.submit();
        }

        function cancelEntry(session_id, tr_id) {
            <?php if ($_SESSION['user']->op_access != 'W') { ?>
                return alert('You are not authorised to perform this action.');
            <?php } ?>
            if ($('#categoryCancelEntry' + session_id).val() == '') {
                alert("Please select the category from the list.");
                $('#categoryCancelEntry' + session_id).focus();
                return;
            }
	    if ($('#typeCancelEntry' + session_id).val() == '') {
                alert("Please select the type the list.");
                $('#typeCancelEntry' + session_id).focus();
                return;
            }
            if ($('#txtAreaCancelEntry' + session_id).val().trim() == '') {
                alert("Please provide reason for cancellation in the box");
                $('#txtAreaCancelEntry' + session_id).focus();
                return;
            }

            var url = 'do.php?_action=ajax_tracking&subaction=cancelSessionEntry';
            url += '&session_id=' + session_id;
            url += '&tr_id=' + tr_id;
            url += '&comments=' + encodeURIComponent($('#txtAreaCancelEntry' + session_id).val());
            url += '&category=' + encodeURIComponent($('#categoryCancelEntry' + session_id).val());
	    url += '&type=' + encodeURIComponent($('#typeCancelEntry' + session_id).val());

            $.ajax({
                type: 'POST',
                url: url,
                async: false,
                success: function(data, textStatus, xhr) {
                    window.location.reload();
                },
                error: function(data, textStatus, xhr) {
                    console.log(data.responseText);
                }
            });
        }

        function showNotes() {
            if ($('#notes_ddl').val() == '') {
                $('#divNotes').html('<p class="text-bold text-muted"><i class="fa fa-info-circle"></i> Please select an option and press the button to fetch the information</p>');
                return;
            }
            var tr_id = '<?php echo $tr->id; ?>';
            var inductee_id = '<?php echo is_a($inductee, 'Inductee') ? $inductee->id : '' ?>';
            var induction_id = '<?php echo is_a($induction, 'Induction') ? $induction->id : '' ?>';

            var url = 'do.php?_action=ajax_tracking&subaction=get_tr_operations_notes&tr_id=' + encodeURIComponent(tr_id) + '&note_type=' + encodeURIComponent($('#notes_ddl').val());
            if (inductee_id != '' && $('#notes_ddl').val() == 'learner_id_notes')
                url = 'do.php?_action=ajax_tracking&subaction=getInducteeNotes&inductee_id=' + encodeURIComponent(inductee_id) + '&note_type=' + encodeURIComponent($('#notes_ddl').val());
            else if (inductee_id != '' && $('#notes_ddl').val() == 'coordinator_notes')
                url = 'do.php?_action=ajax_tracking&subaction=getInductionNotes&induction_id=' + encodeURIComponent(induction_id) + '&note_type=' + encodeURIComponent($('#notes_ddl').val());
            else if (inductee_id == '' && ($('#notes_ddl').val() == 'learner_id_notes' || $('#notes_ddl').val() == 'coordinator_notes')) {
                alert('This lerner has not come from induction module');
                return;
            }


            $.ajax({
                type: 'GET',
                async: true,
                url: url,
                beforeSend: function() {
                    $("#divNotes").html('<div class="overlay"><i class="fa fa-refresh fa-spin"></i> <img id="loading" src="images/loading.gif" alt="Loading" /></div>');
                },
                success: function(response) {
                    $('#divNotes').html(response);
                    var _r = '<table id="tblLarNotes" class="table table-bordered"><caption class="lead">LAR Details</caption><thead><tr><th>Creation Date Time</th><th>Created By</th><th>Type</th><th>LAR Date</th><th>Last Action Date</th><th>Revisit Date</th><th>Reason</th><th>RAG</th><th>Owner</th><th>Notes</th><th>Actions</th></tr></thead><tbody><tr><td colspan="12"><i>No record found.</i></td></tr></tbody></table>';
                    if (response != _r) {
                        $('#tblLarNotes').DataTable({
                            "paging": false,
                            "lengthChange": false,
                            "searching": true,
                            "ordering": true,
                            "info": false,
                            "autoWidth": true
                        });
                    }
                },
                error: function(data, textStatus, xhr) {
                    console.log(data.responseText);
                    $("#divNotes").html('');
                }
            });
        }

        function saveMockEntry() {
            $.ajax({
                type: 'POST',
                url: 'do.php?_action=ajax_tracking',
                data: $('#frmUnitsMockDetails').serialize(),
                async: false,
                success: function(msg) {
                    window.location.reload();
                },
                error: function(msg) {
                    alert(msg);
                }
            });
        }

        function saveMatrixTab() {
            var frmMatrix = document.forms["frmMatrix"];
            var result = ajaxPostForm(frmMatrix);
            if (result)
                window.location.reload();
        }

        function saveNewType() {
            if ($('#txtNewType').val().trim() == '') {
                alert('Please enter the new type');
                return;
            }

            $('#divNewType').hide();
            $('#btnNewType').show();

            var client = ajaxRequest('do.php?_action=ajax_tracking&subaction=add_op_add_details_type&value=' + encodeURIComponent($('#txtNewType').val().trim()));

            $('#txtNewType').val();
            var form = document.forms['frmAdditionalInfo'];
            var type = form.elements['type'];
            ajaxPopulateSelect(type, 'do.php?_action=ajax_tracking&subaction=load_op_details_types');
        }

        function saveAdditionalInfo() {
            var myForm = document.forms['frmAdditionalInfo'];
            if (validateForm(myForm) == false) {
                return false;
            }
            $.ajax({
                type: 'GET',
                url: 'do.php?_action=save_op_learner',
                data: $('#frmAdditionalInfo').serialize(),
                success: function(data, textStatus, xhr) {
                    window.location.reload();
                }
            });
        }

        function removeMockEntry(entry_id) {
            if (!confirm('This action is irreversible, are you sure you want to remove this record?'))
                return;

            $.ajax({
                type: 'GET',
                url: 'do.php?_action=ajax_tracking&subaction=removeMockEntry&entry_id=' + entry_id,
                success: function(data) {
                    alert('The entry has been removed successfully.');
                    window.location.reload();
                },
                error: function(data, textStatus, xhr) {
                    console.log(data.responseText);
                }
            });

        }

        <?php if (SOURCE_LOCAL || DB_NAME == "am_baltic_demo") { ?>

            function load_email_template() {
                var email_template_type = $('#frmEmail #frmEmailTemplate');
                if (email_template_type.val() == '') {
                    alert('Please select template from templates list');
                    email_template_type.focus();
                    return false;
                }

                var client = ajaxRequest('do.php?_action=ajax_tracking&subaction=loadAndPrepareEmailTemplate&tr_id=' + <?php echo $tr->id; ?> + '&template_id=' + email_template_type.val(), null, null, loadAndPrepareEmailTemplateCallback);
            }

            function loadAndPrepareEmailTemplateCallback(client) {
                $('#frmEmail #compose-textarea').summernote('code', client.responseText);
            }

            function sendEmail() {
                var frmEmail = document.forms['frmEmail'];

                if (!validateForm(frmEmail))
                    return;

                if (!validateEmail(frmEmail.elements["frmEmailTo"].value)) {
                    alert('Please provide valid email to address');
                    frmEmail.elements["frmEmailTo"].focus();
                    return;
                }
                if (!validateEmail(frmEmail.elements["frmEmailFrom"].value)) {
                    alert('Please provide valid email from address');
                    frmEmail.elements["frmEmailFrom"].focus();
                    return;
                }

                frmEmail.submit();
            }

            function showEmail(email_id) {
                $.ajax({
                    type: 'GET',
                    url: 'do.php?_action=ajax_tracking&subaction=getEmailContent&email_id=' + email_id,
                    beforeSend: function() {
                        $("#dialogPreview").dialog({
                            title: "Please wait ..."
                        });
                        $("#dialogPreview").dialog('open').html("<p><i class=\"fa fa-refresh fa-spin\"></i> Fetching email ...</p>");
                    },
                    success: function(data) {
                        $("#dialogPreview").dialog({
                            title: "Email Detail"
                        });
                        $("#dialogPreview").dialog('open').html(data);
                    },
                    error: function(data, textStatus, xhr) {
                        console.log(data.responseText);
                    }
                });
            }

            $(function() {
                $('#compose-textarea').summernote({
                    toolbar: [
                        ['style', ['bold', 'italic', 'underline', 'clear']],
                        ['fontsize', ['fontsize']],
                        ['para', ['ul', 'ol', 'paragraph']],
                        ['height', ['height']],
                        ['insert', ['link', 'picture', 'hr']]
                    ],
                    height: 300,
                    callbacks: {
                        onImageUpload: function(files, editor, welEditable) {
                            sendFile(files[0], editor, welEditable);
                        }
                    }
                });

                function sendFile(file, editor, welEditable) {
                    data = new FormData();
                    data.append("file", file);
                    $.ajax({
                        data: data,
                        type: "POST",
                        url: "do.php?_action=ajax_tracking&subaction=upload_summernote_image",
                        cache: false,
                        contentType: false,
                        processData: false,
                        success: function(url) {
                            //editor.insertImage(welEditable, url);
                            $('#compose-textarea').summernote('editor.insertImage', url);
                        }
                    });
                }

                $('#dialogPreview').dialog({
                    modal: true,
                    width: 700,
                    height: 700,
                    closeOnEscape: true,
                    autoOpen: false,
                    resizable: true,
                    draggable: true,
                    buttons: {
                        'Close': function() {
                            $(this).dialog('close');
                        }
                    }
                });

                $("button#btnComplaintModalSave").click(function() {
                    if (validateForm(document.forms['frmComplaint']) == false) {
                        return;
                    }
                    $("#frmComplaint").submit();
                });

                $('input[class=gatewayPrep]').each(function() {
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

            });

            function saveGatewayPrep() {
                document.forms['frmGatewayPrep'].submit();
            }

            function saveGatewayReady() {
                document.forms['frmGatewayReady'].submit();
            }

            function saveEPAProject() {
                document.forms['frmEPAProject'].submit();
            }

            function saveInterview() {
                document.forms['frmInterview'].submit();
            }


        <?php } ?>

        function removeLARUpdateEntry(tr_id, timestamp) {
            if (!confirm('This action is irreversible, are you sure you want to remove this record?'))
                return;

            $.ajax({
                type: 'GET',
                url: 'do.php?_action=ajax_tracking&subaction=removeLARUpdateEntry&tr_id=' + encodeURIComponent(tr_id) + '&timestamp=' + encodeURIComponent(timestamp),
                success: function(data) {
                    alert('The entry has been removed successfully.');
                    window.location.reload();
                },
                error: function(data, textStatus, xhr) {
                    console.log(data.responseText);
                }
            });
        }

        function editLARUpdateEntry(tr_id, timestamp) {
            var form = document.forms['frmLAREntryUpdate'];
            form.reset();

            $.ajax({
                type: 'GET',
                dataType: 'json',
                url: 'do.php?_action=ajax_tracking&subaction=get_lar_update_entry_details&tr_id=' + encodeURIComponent(tr_id) + '&timestamp=' + encodeURIComponent(timestamp),
                async: false,
                success: function(data) {

                    $('#modal_creation_date_time').html(data.modal_creation_date_time);
                    $('#modal_created_by').html(data.modal_created_by);
                    $('#modal_type').html(data.modal_type);
                    $('#modal_lar_date').html(data.modal_lar_date);
                    $('#modal_last_action_date').html(data.modal_last_action_date);
                    $('#modal_next_action_date').html(data.modal_next_action_date);
                    $('#modal_sales_deadline_date').html(data.modal_sales_deadline_date);
                    $('#modal_rag').html(data.modal_rag);

                    $('#frmLAREntryUpdate #modal_timestamp').val(data.timestamp);
                    $('#frmLAREntryUpdate #modal_lar_notes').val(data.lar_notes);

                    $('#LAREntryUpdateModal').modal('show');
                },
                error: function(data, textStatus, xhr) {
                    console.log(data.responseText);
                }
            });
        }

        function generateLARTemplate(timestamp) {
            var tr_id = '<?php echo $tr->id; ?>';
            var tracker_id = '<?php echo $tracker_id; ?>';

            window.location.href = 'do.php?_action=generate_op_lar_docx&tr_id=' + tr_id + '&tracker_id=' + tracker_id + '&timestamp=' + timestamp;
        }

        function showEpaEntryLog(epa_id) {
            $.ajax({
                type: 'GET',
                url: 'do.php?_action=ajax_tracking&subaction=showEpaEntryLog&epa_id=' + encodeURIComponent(epa_id),
                success: function(response) {
                    $("<div></div>").html(response).dialog({
                        id: "dlg_epa_view_log",
                        title: "Logs for this EPA entry",
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
            });
        }

        function epa_owner_onchange(epa_owner) {
            var tr_id = '<?php echo $tr->id; ?>';
            var req = ajaxRequest('do.php?_action=ajax_tracking&subaction=saveEpaOwnerInOp&tr_id=' + encodeURIComponent(tr_id) + '&epa_owner=' + encodeURIComponent(epa_owner.value));
            if (req) {
                alert('EPA owner saved/updated.');
            }
        }

        function deleteEpaEntry(epa_entry_id) {
            if (!confirm('This action is irreversible, are you sure you want to remove this entry?'))
                return;

            var tr_id = '<?php echo $tr->id; ?>';
            $.ajax({
                type: 'GET',
                url: 'do.php?_action=ajax_tracking&subaction=deleteEpaEntry&tr_id=' + encodeURIComponent(tr_id) + '&epa_entry_id=' + encodeURIComponent(epa_entry_id),
                success: function(data) {
                    alert('The entry has been removed successfully.');
                    window.location.reload();
                },
                error: function(data, textStatus, xhr) {
                    console.log(data.responseText);
                }
            });
        }

	    function showFieldNotes(note_type)
        {
            var tr_id = '<?php echo $tr->id; ?>';
            if (tr_id == '')
                return;

            var postData = 'do.php?_action=ajax_tracking' +
                '&tr_id=' + encodeURIComponent(tr_id) +
                '&subaction=' + encodeURIComponent("getOperationNotes") +
                '&field=' + encodeURIComponent(note_type);

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

        function fetch_last_lar_summary()
        {
            var tr_id = <?php echo json_encode($tr->id); ?>;
                if (tr_id == '')
                    return;

                var postData = 'do.php?_action=ajax_tracking' +
                    '&tr_id=' + encodeURIComponent(tr_id) +
                    '&subaction=' + encodeURIComponent("fetch_last_lar_summary");
                
                var req = ajaxRequest(postData);
                $("textarea[name=lar_summary]").val(req.responseText);
        }

	    function submitFrmPdprep()
        {
            var frmPdprep = document.forms["frmPdprep"];
            frmPdprep.submit();
        }

	    function showProjectCheckinNotes()
        {
            var tr_id = '<?php echo $tr->id; ?>';
            if (tr_id == '')
                return;

            var postData = 'do.php?_action=ajax_tracking' +
                '&tr_id=' + encodeURIComponent(tr_id) +
                '&subaction=' + encodeURIComponent("showProjectCheckinNotes");

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

        function addAnotherRow() 
        {
            var row = $("#tblMockInterview tr").last().clone();
            var oldId = Number(row.attr('id').slice(-1));
            var newId = 1 + oldId;

            row.attr('id', 'trMockInterview_' + newId );

            row.find('#thMockInterview_' + oldId).closest('th').attr('id', 'thMockInterview_' + newId);
            row.find('#mock_interview_planned_date_' + oldId).closest('input').attr('name', 'mock_interview_planned_date_' + newId);
            row.find('#mock_interview_actual_date_' + oldId).closest('input').attr('name', 'mock_interview_actual_date_' + newId);
            row.find('#mock_interview_completed_' + oldId).closest('select').attr('name', 'mock_interview_completed_' + newId);

            row.find('#mock_interview_planned_date_' + oldId).attr('id', 'mock_interview_planned_date_' + newId);
            row.find('#mock_interview_actual_date_' + oldId).attr('id', 'mock_interview_actual_date_' + newId);
            row.find('#mock_interview_completed_' + oldId).attr('id', 'mock_interview_completed_' + newId);

            row.find('#mock_interview_planned_date_' + newId).value = '';
            row.find('#mock_interview_actual_date_' + newId).value = '';
            row.find('#mock_interview_completed_' + newId).value = '';

            row.find('#thMockInterview_' + newId).closest('th').html('Mock Interview ' + newId);
            $("input[type=hidden][name=total_mock_interviews]").val(newId);
            
            $('#tblMockInterview').append(row);
        }
    </script>

</body>

</html>