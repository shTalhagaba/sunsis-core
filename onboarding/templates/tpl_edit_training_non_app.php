<?php /* @var $vo TrainingRecord */ ?>
<?php /* @var $ob_learner OnboardingLearner */ ?>
<!DOCTYPE html>

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Edit Details</title>
    <link rel="stylesheet" href="css/common.css" type="text/css" />
    <link rel="stylesheet" href="/assets/adminlte/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="/assets/adminlte/plugins/font-awesome/font-awesome.min.css">
    <link rel="stylesheet" href="/assets/adminlte/plugins/jQueryUI/jquery-ui.css">
    <link rel="stylesheet" href="/assets/adminlte/dist/css/AdminLTE.min.css">
    <link href="/assets/adminlte/plugins/chosen/bootstrap-chosen.css" rel="stylesheet">

    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->


    <style>
        #home_postcode,
        #work_postcode,
        #ni {
            text-transform: uppercase
        }

        .ui-datepicker .ui-datepicker-title select {
            color: #000;
        }

        input[type=checkbox], input[type=radio] {
			transform: scale(1.4);
		}
    </style>
</head>

<body>
    <div class="row">
        <div class="col-sm-12">
            <div class="banner">
                <div class="Title" style="margin-left: 6px;">Edit Details</div>
                <div class="ButtonBar">
                    <span class="btn btn-xs btn-default" onclick="window.location.href='<?php echo $_SESSION['bc']->getPrevious(); ?>';"><i class="fa fa-arrow-circle-o-left"></i> Cancel</span>
                    <span class="btn btn-xs btn-default" onclick="save();"><i class="fa fa-save"></i> Save Information</span>
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

    <div class="container-fluid">

        <form method="post" role="form" class="form-horizontal" name="frmLearner" action="<?php echo $_SERVER['PHP_SELF']; ?>">
            <input type="hidden" name="_action" value="save_training_non_app" />
            <input type="hidden" name="id" value="<?php echo $vo->id; ?>" />

            <?php if( !in_array($vo->status_code, [TrainingRecord::STATUS_CHANGE_OF_EMPLOYER, TrainingRecord::STATUS_CONVERTED]) ) { ?>
            <div class="row">
                <div class="col-sm-6 col-sm-offset-2">
                    <div class="form-group">
                        <label for="status_code" class="col-sm-3 control-label fieldLabel_compulsory">Status Code:</label>
                        <div class="col-sm-9"><?php echo HTML::selectChosen('status_code', LookupHelper::getTrainingStatusDdl([TrainingRecord::STATUS_CHANGE_OF_EMPLOYER, TrainingRecord::STATUS_CONVERTED]), $vo->status_code, true, true); ?></div>
                    </div>
                </div>
            </div>
            <?php } else { ?>
                <input type="hidden" name="status_code" value="<?php echo $vo->status_code; ?>" />
            <?php } ?>
            <div class="row">
                <div class="col-sm-6">
                    <div class="box-body">
                        <div class="form-group">
                            <label for="training_provider" class="col-sm-3 control-label fieldLabel_compulsory">Training Provider:</label>
                            <div class="col-sm-9"><?php echo HTML::selectChosen('training_provider_location_id', $ddlTrainingProvidersLocations, $vo->provider_location_id, true, true); ?></div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-3 control-label fieldLabel_compulsory">Employer:</label>
                            <div class="col-sm-9">
                                <?php echo HTML::selectChosen('employer_id', DAO::getResultset($link, "SELECT id, legal_name, null FROM organisations WHERE organisation_type = '" . Organisation::TYPE_EMPLOYER . "' AND active = 1 ORDER BY legal_name"), $vo->employer_id, false, true); ?>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="employer_location_id" class="col-sm-3 control-label fieldLabel_compulsory">Employer Location:</label>
                            <div class="col-sm-9"><?php echo HTML::selectChosen('employer_location_id', $ddlEmployersLocations, $vo->employer_location_id, false, true); ?></div>
                        </div>
                        <div class="form-group">
                            <label for="subcontractor" class="col-sm-3 control-label fieldLabel_optional">Subcontractor:</label>
                            <div class="col-sm-9"><?php echo HTML::selectChosen('subcontractor_location_id', $ddlSubcontractorsLocations, $vo->subcontractor_location_id, false, false); ?></div>
                        </div>
                        <?php if(false) { ?>
                        <div class="form-group">
                            <label for="framework_id" class="col-sm-3 control-label fieldLabel_compulsory">Standard:</label>
                            <div class="col-sm-8">
                                <?php echo HTML::selectChosen('framework_id', $ddlFrameworks, $vo->framework_id, true, true); ?>
                                <span class="chkUpdatePrices text-info"><input type="checkbox" name="refresh_prices" value="1"> Tick this box if you want to refresh the prices from standard on save.</span>
                            </div>
                            <div class="col-sm-1"><span class="btn btn-info btn-xs" onclick="showFrameworkInfo();"><i class="fa fa-info-circle"></i></span></div>
                        </div>
                        <?php } else { ?>
                            <input type="hidden" name="framework_id" value="<?php echo $vo->framework_id; ?>" />
                        <?php } ?>
                        <div class="form-group">
                            <label for="trainers" class="col-sm-3 control-label fieldLabel_optional">Assessor:</label>
                            <div class="col-sm-9"><?php echo HTML::selectChosen('trainers', $ddlTrainers, $vo->trainers, true, false, true, 1); ?></div>
                        </div>
                        <div class="form-group">
                            <label for="contracted_hours_per_week" class="col-sm-3 control-label fieldLabel_optional">Contracted Hours per Week:</label>
                            <div class="col-sm-9">
                                <input type="text" class="form-control" value="<?php echo $vo->contracted_hours_per_week; ?>" name="contracted_hours_per_week" id="contracted_hours_per_week" onkeypress="return numbersonlywithpoint();" maxlength="4" />
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="practical_period_start_date" class="col-sm-3 control-label fieldLabel_compulsory">Start Date:</label>
                            <div class="col-sm-9"><?php echo HTML::datebox('practical_period_start_date', $vo->practical_period_start_date, true); ?></div>
                        </div>
                        <div class="form-group">
                            <label for="practical_period_end_date" class="col-sm-3 control-label fieldLabel_compulsory">Planned End Date:</label>
                            <div class="col-sm-9"><?php echo HTML::datebox('practical_period_end_date', $vo->practical_period_end_date, true); ?></div>
                        </div>
                        <div class="form-group">
                            <label for="glh" class="col-sm-3 control-label fieldLabel_optional">Guided Learning Hours (GLH):</label>
                            <div class="col-sm-9">
                                <input type="text" class="form-control" name="glh" id="glh" onkeypress="return numbersonly();" maxlength="4" value="<?php echo $vo->glh; ?>" />
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="job_title" class="col-sm-3 control-label fieldLabel_optional">Job Title:</label>
                            <div class="col-sm-9"><input type="text" class="form-control optional" name="job_title" id="job_title" value="<?php echo $vo->job_title; ?>" maxlength="149" /></div>
                        </div>
                        <div class="form-group">
                            <label for="hhs" class="col-sm-3 control-label fieldLabel_optional">Household Situation:</label>
                            <div class="col-sm-9"><?php echo HTML::selectChosen('hhs', LookupHelper::getDDLHhs(), $vo->hhs, true); ?></div>
                        </div>
                        <div class="form-group">
                            <label for="type_of_funding" class="col-sm-3 control-label fieldLabel_optional">Type of Funding:</label>
                            <div class="col-sm-9"><?php echo HTML::selectChosen('type_of_funding', [['Fully Funded', 'Fully Funded'], ['Co-Funded', 'Co-Funded']], $vo->type_of_funding, true); ?></div>
                        </div>
                        <?php if($framework->fund_model_extra == Framework::FUNDING_STREAM_LEARNER_LOAN){ ?>
                            <div class="form-group">
                                <label for="commercial_fee" class="col-sm-3 control-label fieldLabel_optional">Commercial Fee:</label>
                                <div class="col-sm-9">
                                    <input type="text" class="form-control" value="<?php echo $vo->commercial_fee; ?>" name="commercial_fee" id="commercial_fee" onkeypress="return numbersonlywithpoint();" maxlength="8" />
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="commercial_fee_emp_cont" class="col-sm-3 control-label fieldLabel_optional">Employer paying any part of fee:</label>
                                <div class="col-sm-9">
                                    <?php echo HTML::selectChosen('commercial_fee_emp_cont', [['Yes', 'Yes'], ['No', 'No']], $vo->commercial_fee_emp_cont, true); ?>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="all_amount" class="col-sm-3 control-label fieldLabel_optional">Advanced Learner Loan Amount:</label>
                                <div class="col-sm-9">
                                    <input type="text" class="form-control" value="<?php echo $vo->all_amount; ?>" name="all_amount" id="all_amount" onkeypress="return numbersonlywithpoint();" maxlength="8" />
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="all_before" class="col-sm-3 control-label fieldLabel_optional">Learner had Advanced Learner Loan before:</label>
                                <div class="col-sm-9">
                                    <?php echo HTML::selectChosen('all_before', [['Yes', 'Yes'], ['No', 'No']], $vo->all_before, true); ?>
                                </div>
                            </div>
                        <?php } ?>
                        <?php if($framework->fund_model_extra == Framework::FUNDING_STREAM_COMMERCIAL){ ?>
                            <div class="form-group">
                                <label for="commercial_fee" class="col-sm-3 control-label fieldLabel_optional">Commercial Fee:</label>
                                <div class="col-sm-9">
                                    <input type="text" class="form-control" value="<?php echo $vo->commercial_fee; ?>" name="commercial_fee" id="commercial_fee" onkeypress="return numbersonlywithpoint();" maxlength="8" />
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="commercial_fee_emp_cont" class="col-sm-3 control-label fieldLabel_optional">Employer paying any part of fee:</label>
                                <div class="col-sm-9">
                                    <?php echo HTML::selectChosen('commercial_fee_emp_cont', [['Yes', 'Yes'], ['No', 'No']], $vo->commercial_fee_emp_cont, true); ?>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="all_amount" class="col-sm-3 control-label fieldLabel_optional">Purchase Order Number:</label>
                                <div class="col-sm-9">
                                    <input type="text" class="form-control" value="<?php echo $vo->purchase_order_no; ?>" name="purchase_order_no" id="purchase_order_no" maxlength="25" />
                                </div>
                            </div>
                        <?php } ?>
                        <span class="lead text-bold">LLDD</span><br>
                        <div class="form-group">
                            <label for="LLDD" class="col-sm-6 control-label fieldLabel_optional">Does learner have a learning difficulty, health problem or disability?:</label>
                            <div class="col-sm-6">
                                <?php echo HTML::selectChosen('LLDD', $LLDD, $vo->LLDD, true); ?>
                            </div>
                        </div>
                        <div class="form-group" id="divLLDDCat" style="display: none;">                               
                            <div class="table-responsive">
                                <table class="table table-bordered table-striped small">
                                    <tr>
                                        <th>Click to select the Category</th>
                                        <th>Primary Category</th>
                                    </tr>
                                    <?php
                                    foreach ($LLDDCat as $key => $value) {
                                        $checked = in_array($key, $selected_llddcat) ? 'checked="checked"' : '';
                                        $checked_pri = $key == $vo->primary_lldd ? 'checked="checked"' : '';
                                        echo '<tr>';
                                        echo '<td align="center" valign="center"><input type="checkbox" name="llddcat[]" ' . $checked . ' value="' . $key . '" /> &nbsp; ' . $value . '</td>';
                                        echo '<td><p><input type="radio" name="primary_lldd" value="' . $key . '" ' . $checked_pri . '></td>';
                                        echo '</tr>';
                                    }
                                    ?>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-sm-6">
                    <div class="box-body">
                        <div class="form-group">
                            <label for="learner_title" class="col-sm-3 control-label fieldLabel_optional ">Title:</label>
                            <div class="col-sm-9"><?php echo HTML::selectChosen('learner_title', $titlesDdl, $ob_learner->learner_title, true); ?></div>
                        </div>
                        <div class="form-group">
                            <label for="firstnames" class="col-sm-3 control-label fieldLabel_compulsory">Firstnames:</label>
                            <div class="col-sm-9"><input type="text" class="form-control compulsory" name="firstnames" id="firstnames" value="<?php echo $ob_learner->firstnames; ?>" maxlength="100" /></div>
                        </div>
                        <div class="form-group">
                            <label for="surname" class="col-sm-3 control-label fieldLabel_compulsory">Surname:</label>
                            <div class="col-sm-9"><input type="text" class="form-control compulsory" name="surname" id="surname" value="<?php echo $ob_learner->surname; ?>" maxlength="100" /></div>
                        </div>
                        <div class="form-group">
                            <label for="gender" class="col-sm-3 control-label fieldLabel_compulsory">Gender:</label>
                            <div class="col-sm-9"><?php echo HTML::selectChosen('gender', LookupHelper::getDDLGender(), $ob_learner->gender, true, false); ?></div>
                        </div>
                        <div class="form-group">
                            <label for="input_dob" class="col-sm-3 control-label fieldLabel_compulsory">Date of Birth:</label>
                            <div class="col-sm-9"><?php echo HTML::datebox('dob', $ob_learner->dob, true); ?></div>
                        </div>
                        <div class="form-group">
                            <label for="home_address_line_1" class="col-sm-3 control-label fieldLabel_optional">Home Address Line 1:</label>
                            <div class="col-sm-9"><input type="text" class="form-control" name="home_address_line_1" id="home_address_line_1" value="<?php echo $ob_learner->home_address_line_1; ?>" maxlength="100" /></div>
                        </div>
                        <div class="form-group">
                            <label for="home_address_line_2" class="col-sm-3 control-label fieldLabel_optional">Home Address Line 2:</label>
                            <div class="col-sm-9"><input type="text" class="form-control" name="home_address_line_2" id="home_address_line_2" value="<?php echo $ob_learner->home_address_line_2; ?>" maxlength="100" /></div>
                        </div>
                        <div class="form-group">
                            <label for="home_address_line_3" class="col-sm-3 control-label fieldLabel_optional">Home Address Line 3 (Town):</label>
                            <div class="col-sm-9"><input type="text" class="form-control" name="home_address_line_3" id="home_address_line_3" value="<?php echo $ob_learner->home_address_line_3; ?>" maxlength="100" /></div>
                        </div>
                        <div class="form-group">
                            <label for="home_address_line_4" class="col-sm-3 control-label fieldLabel_optional">Home Address Line 4 (County):</label>
                            <div class="col-sm-9"><input type="text" class="form-control" name="home_address_line_4" id="home_address_line_4" value="<?php echo $ob_learner->home_address_line_4; ?>" maxlength="100" /></div>
                        </div>
                        <div class="form-group">
                                <label for="borough" class="col-sm-3 control-label fieldLabel_optional">Borough (if in London):</label>
                                <div class="col-sm-9"><input type="text" class="form-control" name="borough" id="borough" value="<?php echo $ob_learner->borough; ?>" maxlength="70" /></div>
                            </div>
                        <div class="form-group">
                            <label for="home_postcode" class="col-sm-3 control-label fieldLabel_optional">Postcode:</label>
                            <div class="col-sm-9"><input type="text" class="form-control" name="home_postcode" value="<?php echo $ob_learner->home_postcode; ?>" id="home_postcode" maxlength="10" /></div>
                        </div>
                        <div class="form-group">
                            <label for="home_email" class="col-sm-3 control-label fieldLabel_compulsory">Personal Email:</label>
                            <div class="col-sm-9"><input type="email" class="form-control compulsory" name="home_email" id="home_email" value="<?php echo $ob_learner->home_email; ?>" /></div>
                        </div>
                        <div class="form-group">
                            <label for="home_telephone" class="col-sm-3 control-label fieldLabel_optional">Personal Telephone:</label>
                            <div class="col-sm-9"><input type="text" class="form-control " name="home_telephone" id="home_telephone" value="<?php echo $ob_learner->home_telephone; ?>" maxlength="20" /></div>
                        </div>
                        <div class="form-group">
                            <label for="home_mobile" class="col-sm-3 control-label fieldLabel_optional">Personal Mobile:</label>
                            <div class="col-sm-9"><input type="text" class="form-control " name="home_mobile" id="home_mobile" value="<?php echo $ob_learner->home_mobile; ?>" maxlength="20" /></div>
                        </div>
                        <div class="form-group">
                            <label for="work_email" class="col-sm-3 control-label fieldLabel_optional">Work Email:</label>
                            <div class="col-sm-9"><input type="email" class="form-control optional" name="work_email" id="work_email" value="<?php echo $ob_learner->work_email; ?>" /></div>
                        </div>
                        <div class="form-group">
                            <label for="uln" class="col-sm-3 control-label fieldLabel_optional">ULN (Unique Learner Number):</label>
                            <div class="col-sm-9">
                                <input type="text" class="form-control optional" name="uln" id="uln" value="<?php echo $ob_learner->uln; ?>" />
                                <?php if(SystemConfig::getEntityValue($link, "lrs")) {?>
                                <button type="button" class="btn btn-primary btn-xs" id="btnDownloadUln"><i class="fa fa-cloud-download"></i> Download from LRS</button>
                                <?php } ?>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="ni" class="col-sm-3 control-label fieldLabel_optional">National Insurance:</label>
                            <div class="col-sm-9"><input type="text" class="form-control optional" name="ni" id="ni" value="<?php echo $ob_learner->ni; ?>" /></div>
                        </div>
                        <div class="form-group">
                            <label for="ethnicity" class="col-sm-3 control-label fieldLabel_optional">Ethnicity:</label>
                            <div class="col-sm-9"><?php echo HTML::selectChosen('ethnicity', LookupHelper::getEthnicitiesDdl(), $ob_learner->ethnicity, true); ?></div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-sm-12">
                        <span class="lead text-bold">Employment Status</span>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-6">
                        <p class="">What learner did prior to starting Programme on the <label><?php echo Date::toLong($vo->practical_period_start_date); ?></label>.</p>
                        <div style="margin-left: 15px;">
                            <?php
                            $ipe = ''; $nipn = ''; $nipl = ''; $nk = '';
                            if($vo->EmploymentStatus == '10') $ipe = 'checked = "checked"';
                            if($vo->EmploymentStatus == '11') $nipn = 'checked = "checked"';
                            if($vo->EmploymentStatus == '12') $nipl = 'checked = "checked"';
                            if($vo->EmploymentStatus == '98') $nk = 'checked = "checked"';
                            ?>
                            <p><input type="radio" name="EmploymentStatus" <?php echo $ipe; ?>value="10"> &nbsp; In paid employment</p>
                            <p><input type="radio" name="EmploymentStatus" <?php echo $nipn; ?> value="11"> &nbsp; Not in paid employment, looking for work and available to start work</p>
                            <p><input type="radio" name="EmploymentStatus" <?php echo $nipl; ?> value="12"> &nbsp; Not in paid employment, not looking for work and/or not available to start work</p>
                            <p><input type="radio" name="EmploymentStatus" <?php echo $nk; ?> value="98"> &nbsp; Not known / don't want to provide</p>

                        </div>
                    </div>
                    <div class="col-sm-6">
                        <table id="tbl_emp_status_10" class="table row-border">
                            <?php
                            $work_curr_emp_checked = '';
                            if($vo->EmploymentStatus == '10' && $vo->work_curr_emp == '1') $work_curr_emp_checked = 'checked = "checked"';
                            $SEI_checked = '';
                            if($vo->EmploymentStatus == '10' && $vo->SEI == '1') $SEI_checked = 'checked = "checked"';
                            $PEI_checked = '';
                            if(($vo->EmploymentStatus == '11' || $vo->EmploymentStatus == '12') && $vo->PEI == '1') $PEI_checked = 'checked = "checked"';
                            $SEM_checked = '';
                            if($vo->EmploymentStatus == '10' && $vo->SEM == '1') $SEM_checked = 'checked = "checked"';
                            ?>
                            <tr>
                                <th>Was the learner employed with current employer<br>prior to starting this Programme?</th>
                                <td><input type="checkbox" name="work_curr_emp" id="work_curr_emp" value="1" data-toggle="toggle" data-on="Yes" data-off="No" data-onstyle="success" data-offstyle="danger" <?php echo $work_curr_emp_checked; ?> /></td>
                            </tr>
                            <tr>
                                <th>If not, was the learner self-employed?</th>
                                <td><input type="checkbox" name="SEI" id="SEI" data-toggle="toggle" value="1" data-on="Yes" data-off="No" data-onstyle="success" data-offstyle="danger" <?php echo $SEI_checked; ?> /></td>
                            </tr>
                            <tr>
                                <th>Employer Name?</th>
                                <td><input class="form-control compulsory" type="text" name="empStatusEmployer" id="empStatusEmployer" value="<?php echo $vo->empStatusEmployer == '' ? DAO::getSingleValue($link, "SELECT legal_name FROM organisations WHERE organisations.id = '{$vo->employer_id}'") : $vo->empStatusEmployer; ?>" maxlength="100" /></td>
                            </tr>
                            <tr>
                                <th>How long the learner was you employed?</th>
                                <td><?php echo HTML::selectChosen('LOE', $LOE_dropdown, $vo->LOE, false); ?></td>
                            </tr>
                            <tr>
                                <th>How many hours did learner work each week?</th>
                                <td><?php echo HTML::selectChosen('EII', $EII_dropdown, $vo->EII, false); ?></td>
                            </tr>
                            <?php if(in_array($framework->fund_model, [Framework::FUNDING_STREAM_BOOTCAMP, Framework::FUNDING_STREAM_ASF])) { ?>
                            <tr>
                                <?php if($framework->fund_model == Framework::FUNDING_STREAM_BOOTCAMP) { ?>
                                    <th>Are your earnings below the London Living Wage of &pound;13.85 per hour, or gross salary of less than &pound;27,007.50?</th>
                                <?php } else { ?>
                                    <td>Are your earnings below the London Living Wage of &pound;13.85 per hour, or gross salary of less than &pound;27,007.50?</td>
                                <?php } ?>
                                <td><?php echo HTML::selectChosen('earnings_below_llw', [['Yes', 'Yes'], ['No', 'No']], $vo->earnings_below_llw, true); ?></td>
                            </tr>
                            <?php } ?>
                        </table>
                        <table id="tbl_emp_status_11_12" class="table row-border">
                            <tr>
                                <th>How long the learner was un-employed before <label class="text-blue"><?php echo Date::toLong($vo->practical_period_start_date); ?></label>?</th>
                                <td><?php echo HTML::selectChosen('LOU', $LOU_dropdown, $vo->LOU, false); ?></td>
                            </tr>
                            <tr>
                                <th>Did learner receive any of these benefits?</th>
                                <td><?php echo HTML::selectChosen('BSI', $BSI_dropdown, $vo->BSI, false); ?></td>
                            </tr>
                            <tr>
                                <th>If another state benefit, provide details.</th>
                                <td><input type="text" class="form-control" name="BSI_other_details" id="BSI_other_details" value="<?php echo $vo->BSI_other_details; ?>" maxlength="50" /></td>
                            </tr>
                            <tr>
                                <th>Was the learner in Full Time Education or Training prior to <label class="text-blue"><?php echo Date::toLong($vo->practical_period_start_date); ?></label>?</th>
                                <td><input type="checkbox" name="PEI" id="PEI" data-toggle="toggle" value="1" data-on="Yes" data-off="No" data-onstyle="success" data-offstyle="danger" <?php echo $PEI_checked; ?> /></td>
                            </tr>
                        </table>
                    </div>
                </div>

                

                <div class="row">
                    <div class="col-sm-8 col-sm-offset-2">
                        <span class="btn btn-block btn-primary" style="margin-bottom: 15px;" onclick="save();"><i class="fa fa-save"></i> Save Information</span>
                    </div>
                </div>

        </form>



    </div>


    <script src="/assets/adminlte/plugins/jQuery/jquery-2.2.3.min.js"></script>
    <script src="/assets/adminlte/plugins/jQueryUI/jquery-ui.min.js"></script>
    <script src="/assets/adminlte/bootstrap/js/bootstrap.min.js"></script>
    <script src="/assets/adminlte/dist/js/app.min.js"></script>
    <script src="js/common.js" type="text/javascript"></script>
    <script language="JavaScript" src="js/password.js"></script>
    <script src="/assets/adminlte/plugins/chosen/chosen.jquery.js"></script>

    <script language="JavaScript">

        $(function() {
            $('.datepicker')
                .not(":input[name=planned_epa_date], input[name=induction_f1], input[name=induction_f2], input[name=induction_f3], input[name=induction_f4], input[name=employment_start_date]")
                .attr('class', 'datepicker form-control compulsory');

            $('input#contracted_hours_per_week').blur(function() {
                if ($(this).val().trim() == '')
                    return;

                var num = parseFloat($(this).val());
                var cleanNum = num.toFixed(1);
                $(this).val(cleanNum);
            });

        });

        function employer_id_onchange(employer, event) {
            var f = employer.form;

            var employer_locations = document.getElementById('employer_location_id');

            if (employer.value != '') {
                employer.disabled = true;

                employer_locations.disabled = true;
                ajaxPopulateSelect(employer_locations, 'do.php?_action=ajax_load_account_manager&subaction=load_employer_locations&employer_id=' + employer.value);
                employer_locations.disabled = false;

                employer.disabled = false;
            } else {
                emptySelectElement(employer_locations);
            }
        }


        function save() {
            var myForm = document.forms["frmLearner"];

            if (!validateForm(myForm)) {
                return false;
            }

            selected_lldd = [];
            if($('#LLDD').val() == 'Y')
            {
                var v = 0;
                $("input[name='llddcat[]']").each( function () {
                    if(this.checked)
                    {
                        v++;
                        selected_lldd.push(this.value);
                    }
                });
                if(v == 0)
                {
                    alert('Please select at least one option from applicable LLDD categories.');
                    return false;
                }
                if( $.inArray($('input[name="primary_lldd"]:checked').val(), selected_lldd) < 0)
                {
                    alert('Please select Primary LLDD from your chosen LLDD categories.');
                    return false;
                }
            }

            // First and second name validation
            var fn = myForm.elements['firstnames'];
            var sn = myForm.elements['surname'];
            var re = /^[a-zA-Z\x27\x2D ]+$/;
            if (re.test(fn.value) == false) {
                alert("The firstname(s) may only contain the letters a-z, spaces, hyphens and apostrophes.");
                fn.focus();
                btnSave.disabled = false;
                return false;
            }
            if (re.test(sn.value) == false) {
                alert("The surname may only contain the letters a-z, spaces, hyphens and apostrophes.");
                sn.focus();
                btnSave.disabled = false;
                return false;
            }

            if (myForm.home_postcode.value != '' && !validatePostcode(myForm.home_postcode.value)) {
                alert("Please enter the valid postcode");
                btnSave.disabled = false;
                myForm.home_postcode.focus();
                return false;
            }

            if (!validateEmail(myForm.home_email.value)) {
                alert("Please enter the valid personal email address");
                btnSave.disabled = false;
                myForm.home_email.focus();
                return false;
            }

            if (myForm.work_email.value != '' && !validateEmail(myForm.work_email.value)) {
                alert("Please enter the valid work email address");
                btnSave.disabled = false;
                myForm.work_email.focus();
                return false;
            }

            var practical_period_end_date = stringToDate(myForm.practical_period_end_date.value);
            var practical_period_start_date = stringToDate(myForm.practical_period_start_date.value);
            if ((practical_period_end_date) <= (practical_period_start_date))
            {
                alert("Practical period end date should be greater than practical period start date");
                myForm.practical_period_end_date.focus();
                return;
            }

            myForm.submit();
        }

        $('button#btnDownloadUln').on('click', function(event){
            //event.preventDefault();
            if(
                $("input[name=surname]").val() == '' ||
                $("input[name=firstnames]").val() == '' || 
                $("input[name=dob]").val() == '' || 
                $("input[name=home_postcode]").val() == ''
            )
            {
                alert('Firstnames, Surname, DOB and Postcode are required to use this functionality. Please provide these fields.');
                return false;
            }

            $(this).attr('disabled', true);
            $(this).html('<i class="fa fa-refresh fa-spin"></i> Contacting LRS ...');
            $.ajax({
                url: 'do.php?_action=ajax_lrs&subaction=learnerByDemographics',
                type: 'GET',
                data: {
                    'FindType': 'FUL',
                    'FamilyName': $("input[name=surname]").val(),
                    'GivenName': $("input[name=firstnames]").val(),
                    'DateOfBirth': $("input[name=dob]").val(),
                    'Gender': $("select[name=gender]").val(),
                    'LastKnownPostCode': $("input[name=home_postcode]").val(),
                    'EmailAddress': $("input[name=home_email]").val()
                },
                dataType: 'json',
                success: function(response) {
                    $('button#btnDownloadUln').attr('disabled', false);
                    $('button#btnDownloadUln').html('<i class="fa fa-cloud-download"></i> Download from LRS');
                    if(response.status == "WSRC0004")
                    {
                        if(response.learners_count === 1)
                        {
                            $("input[name=uln]").val(response.learner[0].ULN);
                        }
                    }
                    if(response.status == "WSRC0003")
                    {
                        if(response.learners_count === 1)
                        {
                            $("input[name=uln]").val(response.learner[0].ULN);
                            var html = '<i class="fa fa-info-circle"></i> This is a linked learner record with Master ULN and Linked ULN.<br>';
                            html += '<span class="text-bold">Master ULN:</span> ' + response.learner[0].ULN + '<br>';
                            html += (response.learner[0].LinkedULNs.ULN) ? '<span class="text-bold">Linked ULNs:</span> ' + response.learner[0].LinkedULNs.ULN.join(", ") + '<br>' : '';
                            html += 'System has copied the Master ULN in Unique Learner Number field.';
                            var title = response.status + ": Information: Linked Learner"; 
                        }
                        else
                        {
                            var html = '<i class="fa fa-info-circle"></i> Too many matches.<br>';
                            html += 'LRS has returned ' + response.learners_count + ' possible matches based on your search. ';
                            html += 'Please provide some more information.';
                            var title = response.status + ": Information: Possible matches"; 
                        }
                        $("<div></div>").html(html).dialog({
                                id: "dlg_lrs_result",
                                title: title,
                                resizable: false,
                                modal: true,
                                width: 750,
                                height: 500,
                                buttons: {
                                    'Close': function() {
                                        $(this).dialog('close');
                                        return false;
                                    }
                                }
                        });
                    }
                    if(response.status == "WSRC0001")
                    {
                        var html = '<i class="fa fa-info-circle"></i> No match.<br>';
                        html += 'LRS could not find any matching record for this learner.';
                        $("<div></div>").html(html).dialog({
                            id: "dlg_lrs_result",
                            title: response.status + ": Information: No Match",
                            resizable: false,
                            modal: true,
                            width: 750,
                            height: 250,
                            buttons: {
                                'Close': function() {
                                    $(this).dialog('close');
                                    return false;
                                }
                            }
                        });
                    }
                    if(response.SOAP_faultcode !== undefined && response.SOAP_faultcode != '')
                    {
                        var fault = 'SOAP faultcode: ' + response.SOAP_faultcode + '<br>' + 
                            'LRS_ErrorCode: ' + response.LRS_ErrorCode + '<br>' + 
                            'LRS_Description: ' + response.LRS_Description + '<br>' + 
                            'LRS_FurtherDetails: ' + response.LRS_FurtherDetails + '<br>' 
                            ;
                        $("<div></div>").html(fault).dialog({
                            id: "dlg_lrs_result",
                            title: "Error",
                            resizable: false,
                            modal: true,
                            width: 750,
                            height: 500,
                            buttons: {
                                'Close': function() {
                                    $(this).dialog('close');
                                    return false;
                                }
                            }
                        });
                    }
                    console.log('success');
                    console.log(response);
                },
                error: function(request, error) {
                    $('button#btnDownloadUln').attr('disabled', false);
                    $('button#btnDownloadUln').html('<i class="fa fa-cloud-download"></i> Download from LRS');
                    console.log("error");
                    console.log("Request: " + JSON.stringify(request));
                }
            });
        });

        $('#LLDD').change(function() {
            if($(this).val() == 'Y')
                $('#divLLDDCat').show();
            else
                $('#divLLDDCat').hide();
        });

        if($('#LLDD').val() == 'Y')
        {
            $('#divLLDDCat').show();
        }

        showEmploymentStatusFieldsIfAlreadySaved();

        $("input[name=EmploymentStatus]").on('click', function(event){
	    return;
            if(this.value == 10)
            {
                $('#tbl_emp_status_10').show();
                $('#tbl_emp_status_11_12').hide();
            }
            else if(this.value == 11 || this.value == 12)
            {
                $('#tbl_emp_status_10').hide();
                $('#tbl_emp_status_11_12').show();
            }
            else
            {
                $('#tbl_emp_status_10').hide();
                $('#tbl_emp_status_11_12').hide();
            }
        });

        
        function showEmploymentStatusFieldsIfAlreadySaved()
        {
	    return;
            var EmpStatus = $("input[name=EmploymentStatus]:checked").val();

            if(EmpStatus == '10')
            {
                $('#tbl_emp_status_10').show();
                $('#tbl_emp_status_11_12').hide();
            }
            else if(EmpStatus == '11' || EmpStatus == '12')
            {
                $('#tbl_emp_status_10').hide();
                $('#tbl_emp_status_11_12').show();
            }
            else
            {
                $('#tbl_emp_status_10').hide();
                $('#tbl_emp_status_11_12').hide();
            }
        }

    </script>

</body>

</html>