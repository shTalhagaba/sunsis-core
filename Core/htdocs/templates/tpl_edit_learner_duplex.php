<?php
 /* @var $vo User */ 
 $psa_only_clause = $_SESSION['user']->employer_id == '3278' ? " AND crm_training_schedule.venue = 'Peterborough Skills Academy' " : "";
 ?>

<!DOCTYPE html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title><?php echo $vo->id == '' ? 'Add' : 'Edit'; ?> Learner</title>
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
        #home_postcode, #work_postcode, #ni{text-transform:uppercase}
        .ui-datepicker .ui-datepicker-title select {
            color: #000;
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
            <div class="Title" style="margin-left: 6px;"><?php echo $vo->id == '' ? 'Add' : 'Edit'; ?> Learner</div>
            <div class="ButtonBar">
                <span class="btn btn-sm btn-default" onclick="window.location.href='<?php echo $_SESSION['bc']->getPrevious(); ?>';"><i class="fa fa-arrow-circle-o-left"></i> Cancel</span>
                <span id="btnSave" class="btn btn-sm btn-default" onclick="save(); ">
                    <i class="fa fa-save"></i> <?php echo $vo->id == '' ? 'Save' : 'Save'; ?> Learner
                </span>
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
        <div class="col-sm-6">
            <form method="post" role="form" class="form-horizontal" name="frmLearner" action="<?php echo $_SERVER['PHP_SELF']; ?>">
                <input type="hidden" name="_action" value="save_learner_duplex" />
                <input type="hidden" name="formName" value="frmLearner" />
                <input type="hidden" name="id" value="<?php echo $vo->id; ?>" />
                <input type="hidden" name="username" value="<?php echo $vo->username; ?>" />

                <div class="box box-primary">
                    <div class="box-header with-border">
                        <span class="box-title">Basic Details</span>
                        <span class="pull-right">
                            <input class="yes_no_toggle" type="checkbox" name="crb" id="crb" value="1" data-toggle="toggle" data-on="Archive" data-off="Live" data-onstyle="danger" data-offstyle="success" <?php echo $vo->crb == '1' ? 'checked="checked"' : ''; ?> />
                        </span>
                    </div>
                    <div class="box-body">
                        <div class="form-group">
                            <label for="employer_id" class="col-sm-3 control-label fieldLabel_compulsory">Employer:</label>
                            <div class="col-sm-9"><?php echo HTML::selectChosen('employer_id', $ddlEmployers, $vo->employer_id, true, true); ?></div>
                        </div>
                        <div class="form-group">
                            <label for="employer_location_id" class="col-sm-3 control-label fieldLabel_compulsory">Employer Location:</label>
                            <div class="col-sm-9"><?php echo HTML::selectChosen('employer_location_id', $ddlEmployersLocations, $vo->employer_location_id, true, true); ?></div>
                        </div>
                        <div class="form-group">
                            <label for="firstnames" class="col-sm-3 control-label fieldLabel_compulsory">Firstnames:</label>
                            <div class="col-sm-9"><input type="text" class="form-control compulsory" name="firstnames" id="firstnames" maxlength="100" value="<?php echo $vo->firstnames; ?>" /></div>
                        </div>
                        <div class="form-group">
                            <label for="surname" class="col-sm-3 control-label fieldLabel_compulsory">Surname:</label>
                            <div class="col-sm-9"><input type="text" class="form-control compulsory" name="surname" id="surname" maxlength="100" value="<?php echo $vo->surname; ?>" /></div>
                        </div>
                        <div class="form-group">
                            <label for="gender" class="col-sm-3 control-label fieldLabel_optional">Gender:</label>
                            <div class="col-sm-9"><?php echo HTML::selectChosen('gender', InductionHelper::getDDLGender(), $vo->gender, true, false);?>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="input_dob" class="col-sm-3 control-label fieldLabel_optional">Date of Birth:</label>
                            <div class="col-sm-9"><?php echo HTML::datebox('dob', $vo->dob); ?></div>
                        </div>
                        <div class="form-group">
                            <label for="ni" class="col-sm-3 control-label fieldLabel_optional">National Insurance:</label>
                            <div class="col-sm-9">
                                <input type="text" class="form-control optional" name="ni" id="ni" value="<?php echo strtoupper($vo->ni); ?>" maxlength="9" onkeyup="this.value = this.value.toUpperCase();" />
                            </div>
                        </div>
			<div class="callout callout-default">
			    <div class="form-group">
                                <label for="home_address_line_1" class="col-sm-3 control-label fieldLabel_optional">Home Address Line 1:</label>
                                <div class="col-sm-9">
                                    <input type="text" class="form-control optional" name="home_address_line_1" id="home_address_line_1" value="<?php echo $vo->home_address_line_1; ?>" maxlength="100" />
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="home_address_line_2" class="col-sm-3 control-label fieldLabel_optional">Home Address Line 2:</label>
                                <div class="col-sm-9">
                                    <input type="text" class="form-control optional" name="home_address_line_2" id="home_address_line_2" value="<?php echo $vo->home_address_line_2; ?>" maxlength="100" />
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="home_address_line_3" class="col-sm-3 control-label fieldLabel_optional">Home Address Line 3:</label>
                                <div class="col-sm-9">
                                    <input type="text" class="form-control optional" name="home_address_line_3" id="home_address_line_3" value="<?php echo $vo->home_address_line_3; ?>" maxlength="100" />
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="home_address_line_4" class="col-sm-3 control-label fieldLabel_optional">Home Address Line 4:</label>
                                <div class="col-sm-9">
                                    <input type="text" class="form-control optional" name="home_address_line_4" id="home_address_line_4" value="<?php echo $vo->home_address_line_4; ?>" maxlength="100" />
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="home_postcode" class="col-sm-3 control-label fieldLabel_optional">Postcode:</label>
                                <div class="col-sm-9">
                                    <input type="text" class="form-control optional" name="home_postcode" id="home_postcode" maxlength="12" value="<?php echo $vo->home_postcode; ?>" />
                                    <span id="lblInvalidPostcode" class="text-red" style="<?php echo ($vo->home_postcode != '' && $is_valid_postcode == 0) ? 'display: block;' : 'display: none;';?>">Invalid postcode according to the lookup</span>
                                    <span id="lblValidPostcode" class="text-green" style="<?php echo ($vo->home_postcode != '' && $is_valid_postcode > 0) ? 'display: block;' : 'display: none;';?>">Valid postcode according to the lookup</span>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="bypass_postcode" class="col-sm-3 control-label fieldLabel_optional">Bypass Postcode Region:</label>
                                <div class="col-sm-9">
                                    <?php echo HTML::selectChosen('bypass_postcode', [[0, "No"], [1, "Yes"]], $vo->bypass_postcode); ?>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="home_email" class="col-sm-3 control-label fieldLabel_optional">Email:</label>
                            <div class="col-sm-9"><input type="email" class="form-control optional" name="home_email" id="home_email" value="<?php echo $vo->home_email; ?>" /></div>
                        </div>
			            <div class="form-group">
                            <label for="work_email" class="col-sm-3 control-label fieldLabel_optional">Secondary Email:</label>
                            <div class="col-sm-9"><input type="email" class="form-control optional" name="work_email" id="work_email" value="<?php echo $vo->work_email; ?>" /></div>
                        </div>
			            <div class="form-group text-red">
                            <label for="l24" class="col-sm-3 control-label fieldLabel_optional">Level 1/Level 2:</label>
                            <div class="col-sm-9">
                                <?php echo HTML::checkboxGrid('l24', [['L1', 'Level 1'], ['L2', 'Level 2']], explode(',', $vo->l24), 2); ?>
                            </div>
                        </div>
                        <div class="callout callout-default">
                            <div class="form-group text-red">
                                <label for="l41a" class="col-sm-3 control-label fieldLabel_optional">To Rebook:</label>
                                <div class="col-sm-9">
                                    <?php echo HTML::checkboxGrid('l41a', [['L3', 'Level 3'], ['L4', 'Level 4']], explode(',', $vo->l41a)); ?>
                                </div>
                            </div>
							<div class="form-group">
                                <label for="level1_date" class="col-sm-3 control-label fieldLabel_optional">Level 1 Training Date:</label>
                                <div class="col-sm-9">
                                    <?php
                                    $ddlTrainingDates = DAO::getResultset($link, "SELECT id, CONCAT(LEVEL, ': ', DATE_FORMAT(training_date, '%d/%m/%Y'), ' - ', DATE_FORMAT(training_end_date, '%d/%m/%Y')), venue FROM crm_training_schedule WHERE level = 'L1' {$psa_only_clause} ORDER BY venue, training_date;");
                                    $selected_l1 = DAO::getSingleColumn($link, "SELECT schedule_id FROM training WHERE training.learner_id = '{$vo->id}' AND schedule_id IN (SELECT id FROM crm_training_schedule WHERE level = 'L1');");
                                    echo HTML::selectChosen('level1_date', $ddlTrainingDates, $selected_l1, true);
                                    ?>
                                </div>
                            </div>
							<div class="form-group">
                                <label for="level2_date" class="col-sm-3 control-label fieldLabel_optional">Level 2 Training Date:</label>
                                <div class="col-sm-9">
                                    <?php
                                    $ddlTrainingDates = DAO::getResultset($link, "SELECT id, CONCAT(LEVEL, ': ', DATE_FORMAT(training_date, '%d/%m/%Y'), ' - ', DATE_FORMAT(training_end_date, '%d/%m/%Y')), venue FROM crm_training_schedule WHERE level = 'L2' {$psa_only_clause} ORDER BY venue, training_date;");
                                    $selected_l2 = DAO::getSingleColumn($link, "SELECT schedule_id FROM training WHERE training.learner_id = '{$vo->id}' AND schedule_id IN (SELECT id FROM crm_training_schedule WHERE level = 'L2');");
                                    echo HTML::selectChosen('level2_date', $ddlTrainingDates, $selected_l2, true);
                                    ?>
                                </div>
                            </div>	
                            <div class="form-group">
                                <label for="level3_date" class="col-sm-3 control-label fieldLabel_optional">Level 3 Training Date:</label>
                                <div class="col-sm-9">
                                    <?php
                                    $ddlTrainingDates = DAO::getResultset($link, "SELECT id, CONCAT(LEVEL, ': ', DATE_FORMAT(training_date, '%d/%m/%Y'), ' - ', DATE_FORMAT(training_end_date, '%d/%m/%Y')), venue FROM crm_training_schedule WHERE level = 'L3' {$psa_only_clause} ORDER BY venue, training_date;");
                                    $selected_l3 = DAO::getSingleColumn($link, "SELECT schedule_id FROM training WHERE training.learner_id = '{$vo->id}' AND schedule_id IN (SELECT id FROM crm_training_schedule WHERE level = 'L3');");
                                    echo HTML::selectChosen('level3_date', $ddlTrainingDates, $selected_l3, true);
                                    ?>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="level4_date" class="col-sm-3 control-label fieldLabel_optional">Level 4 Training Date:</label>
                                <div class="col-sm-9">
                                    <?php
                                    $ddlTrainingDates = DAO::getResultset($link, "SELECT id, CONCAT(LEVEL, ': ', DATE_FORMAT(training_date, '%d/%m/%Y'), ' - ', DATE_FORMAT(training_end_date, '%d/%m/%Y')), venue FROM crm_training_schedule WHERE level = 'L4' {$psa_only_clause} ORDER BY venue, training_date;");
                                    $selected_l4 = DAO::getSingleColumn($link, "SELECT schedule_id FROM training WHERE training.learner_id = '{$vo->id}' AND schedule_id IN (SELECT id FROM crm_training_schedule WHERE level = 'L4');");
                                    echo HTML::selectChosen('level4_date', $ddlTrainingDates, $selected_l4, true);
                                    ?>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="ML3_date" class="col-sm-3 control-label fieldLabel_optional">MAN Level 3 Training Date:</label>
                                <div class="col-sm-9">
                                    <?php
                                    $ddlTrainingDates = DAO::getResultset($link, "SELECT id, CONCAT(LEVEL, ': ', DATE_FORMAT(training_date, '%d/%m/%Y'), ' - ', DATE_FORMAT(training_end_date, '%d/%m/%Y')), venue FROM crm_training_schedule WHERE level = 'ML3' {$psa_only_clause} ORDER BY venue, training_date;");
                                    $selected_ML3 = DAO::getSingleColumn($link, "SELECT schedule_id FROM training WHERE training.learner_id = '{$vo->id}' AND schedule_id IN (SELECT id FROM crm_training_schedule WHERE level = 'ML3');");
                                    echo HTML::selectChosen('ML3_date', $ddlTrainingDates, $selected_ML3, true);
                                    ?>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="FG_date" class="col-sm-3 control-label fieldLabel_optional">FG Training Date:</label>
                                <div class="col-sm-9">
                                    <?php
                                    $ddlTrainingDates = DAO::getResultset($link, "SELECT id, CONCAT(LEVEL, ': ', DATE_FORMAT(training_date, '%d/%m/%Y'), ' - ', DATE_FORMAT(training_end_date, '%d/%m/%Y')), venue FROM crm_training_schedule WHERE level = 'FG' {$psa_only_clause} ORDER BY venue, training_date;");
                                    $selected_FG = DAO::getSingleColumn($link, "SELECT schedule_id FROM training WHERE training.learner_id = '{$vo->id}' AND schedule_id IN (SELECT id FROM crm_training_schedule WHERE level = 'FG');");
                                    echo HTML::selectChosen('FG_date', $ddlTrainingDates, $selected_FG, true);
                                    ?>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="ADASL1_date" class="col-sm-3 control-label fieldLabel_optional">ADAS L1 Training Date:</label>
                                <div class="col-sm-9">
                                    <?php
                                    $ddlTrainingDates = DAO::getResultset($link, "SELECT id, CONCAT(LEVEL, ': ', DATE_FORMAT(training_date, '%d/%m/%Y'), ' - ', DATE_FORMAT(training_end_date, '%d/%m/%Y')), venue FROM crm_training_schedule WHERE level = 'ADASL1' {$psa_only_clause} ORDER BY venue, training_date;");
                                    $selected_ADASL1 = DAO::getSingleColumn($link, "SELECT schedule_id FROM training WHERE training.learner_id = '{$vo->id}' AND schedule_id IN (SELECT id FROM crm_training_schedule WHERE level = 'ADASL1');");
                                    echo HTML::selectChosen('ADASL1_date', $ddlTrainingDates, $selected_ADASL1, true);
                                    ?>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="ADASL2_date" class="col-sm-3 control-label fieldLabel_optional">ADAS L2 Training Date:</label>
                                <div class="col-sm-9">
                                    <?php
                                    $ddlTrainingDates = DAO::getResultset($link, "SELECT id, CONCAT(LEVEL, ': ', DATE_FORMAT(training_date, '%d/%m/%Y'), ' - ', DATE_FORMAT(training_end_date, '%d/%m/%Y')), venue FROM crm_training_schedule WHERE level = 'ADASL2' {$psa_only_clause} ORDER BY venue, training_date;");
                                    $selected_ADASL2 = DAO::getSingleColumn($link, "SELECT schedule_id FROM training WHERE training.learner_id = '{$vo->id}' AND schedule_id IN (SELECT id FROM crm_training_schedule WHERE level = 'ADASL2');");
                                    echo HTML::selectChosen('ADASL2_date', $ddlTrainingDates, $selected_ADASL1, true);
                                    ?>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="ADASL3_date" class="col-sm-3 control-label fieldLabel_optional">ADAS L3 Training Date:</label>
                                <div class="col-sm-9">
                                    <?php
                                    $ddlTrainingDates = DAO::getResultset($link, "SELECT id, CONCAT(LEVEL, ': ', DATE_FORMAT(training_date, '%d/%m/%Y'), ' - ', DATE_FORMAT(training_end_date, '%d/%m/%Y')), venue FROM crm_training_schedule WHERE level = 'ADASL3' {$psa_only_clause} ORDER BY venue, training_date;");
                                    $selected_ADASL3 = DAO::getSingleColumn($link, "SELECT schedule_id FROM training WHERE training.learner_id = '{$vo->id}' AND schedule_id IN (SELECT id FROM crm_training_schedule WHERE level = 'ADASL3');");
                                    echo HTML::selectChosen('ADASL3_date', $ddlTrainingDates, $selected_ADASL1, true);
                                    ?>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="LVDT_date" class="col-sm-3 control-label fieldLabel_optional">LVDT Training Date:</label>
                                <div class="col-sm-9">
                                    <?php
                                    $ddlTrainingDates = DAO::getResultset($link, "SELECT id, CONCAT(LEVEL, ': ', DATE_FORMAT(training_date, '%d/%m/%Y'), ' - ', DATE_FORMAT(training_end_date, '%d/%m/%Y')), venue FROM crm_training_schedule WHERE level = 'LVDT' {$psa_only_clause} ORDER BY venue, training_date;");
                                    $selected_LVDT = DAO::getSingleColumn($link, "SELECT schedule_id FROM training WHERE training.learner_id = '{$vo->id}' AND schedule_id IN (SELECT id FROM crm_training_schedule WHERE level = 'LVDT');");
                                    echo HTML::selectChosen('LVDT_date', $ddlTrainingDates, $selected_LVDT, true);
                                    ?>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="input_l48" class="col-sm-3 control-label fieldLabel_optional">L3 Certificate Sent:</label>
                                <div class="col-sm-9"><?php echo HTML::datebox('l48', $vo->l48); ?></div>
                            </div>
                            <div class="form-group">
                                <label for="input_initial_appointment_date" class="col-sm-3 control-label fieldLabel_optional">L4 Certificate Sent:</label>
                                <div class="col-sm-9"><?php echo HTML::datebox('initial_appointment_date', $vo->initial_appointment_date); ?></div>
                            </div>
                        </div>
                        <!--<div class="form-group">
                            <label for="duplex_status" class="col-sm-3 control-label fieldLabel_optional">Status:</label>
                            <div class="col-sm-9">
                                <?php /*echo HTML::selectChosen('duplex_status', [[1, 'New'], [2, 'Enrolled'], [3, 'Completed']], $vo->duplex_status); */?>
                            </div>
                        </div>-->
                        <div class="form-group">
                            <label for="imi_redeem_code" class="col-sm-3 control-label fieldLabel_optional">IMI Redeem Code:</label>
                            <div class="col-sm-9"><input type="text" class="form-control" name="imi_redeem_code" id="imi_redeem_code" maxlength="100" value="<?php echo $vo->imi_redeem_code; ?>" /></div>
                        </div>
                        <div class="form-group">
                            <label for="imi_candidate_number" class="col-sm-3 control-label fieldLabel_optional">IMI Candidate Number:</label>
                            <div class="col-sm-9"><input type="text" class="form-control" name="imi_candidate_number" id="imi_candidate_number" maxlength="12" value="<?php echo $vo->imi_candidate_number; ?>" /></div>
                        </div>
                        <div class="form-group">
                            <label for="home_mobile" class="col-sm-3 control-label fieldLabel_optional">Mobile Number:</label>
                            <div class="col-sm-9"><input type="text" name="home_mobile" id="home_mobile" class="form-control" value="<?php echo $vo->home_mobile; ?>" /></div>
                        </div>
			            <div class="form-group">
                            <label for="trainer" class="col-sm-3 control-label fieldLabel_optional">Trainer:</label>
                            <div class="col-sm-9">
                                <?php
                                $trainers_ddl = DAO::getResultset($link, "SELECT id, CONCAT(firstnames, ' ', surname), job_role FROM users WHERE users.type = 2 ORDER BY firstnames");
                                echo HTML::selectChosen('trainer', $trainers_ddl, $vo->trainer, true, false);
                                ?>
                            </div>
                        </div>
			<div class="form-group">
                            <label for="ifl" class="col-sm-3 control-label fieldLabel_optional">Outstanding Payment:</label>
                            <div class="col-sm-9">
                                <?php echo HTML::selectChosen('ifl', [[0, "No"], [1, "Yes"]], $vo->ifl, true); ?>
                            </div>
                        </div>
			            <div class="form-group">
                            <label for="x509_serial" class="col-sm-3 control-label fieldLabel_optional">Notes:</label>
                            <div class="col-sm-9"><textarea name="x509_serial" id="x509_serial" class="form-control" rows="8"><?php echo $vo->x509_serial; ?></textarea></div>
                        </div>
                        <div class="form-group">
                            <label for="duplex_emp_status" class="col-sm-3 control-label fieldLabel_optional">Employment Status:</label>
                            <div class="col-sm-9">
                                <?php echo HTML::selectChosen('duplex_emp_status', [["Unemployed", "Unemployed"], ["Employed", "Employed"], ["Self Employed", "Self Employed"]], $vo->duplex_emp_status, true); ?>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="duplex_funding_available" class="col-sm-3 control-label fieldLabel_optional">Funding Available:</label>
                            <div class="col-sm-9">
                                <?php echo HTML::selectChosen('duplex_funding_available', [["Yes", "Yes"], ["No", "No"]], $vo->duplex_funding_available, true); ?>
                            </div>
                        </div>

                    </div>
                    <div class="box-footer">
			<span id="btnSave2" class="btn btn-md btn-success btn-block" onclick="save();"><i class="fa fa-save"></i> Save Information</span>
                    </div>
                </div>

            </form>
        </div>

        <div class="col-sm-6">
            <div class="box box-info box-solid info-div" id="SimilarRecords">
                <div class="box-header"><span class="box-title">Similar Records</span></div>
                <div class="box-body">
                    <div class="callout callout-info small">
                        <p><i class="fa fa-info-circle"></i> We have found the following similar records, in order to avoid duplication please check the matching results.</p>
                    </div>
                </div>
            </div>
        </div>

    </div>

    <div class="row">
        <div class="cols-m-12">
            <p><br></p>
            <p><br></p>
            <p><br></p>
            <p><br></p>
            <p><br></p>
        </div>
    </div>

</div>

<div id="dialogDuplicate" title="Possible duplicate">
    <p>The record you are editing is a possible duplicate of the record
        below. The match is made on forename, surname and date of birth (if provided). </p>
    <table style="margin-left:10px">
        <col width="160"/><col/>
        <tr>
            <td class="text-bold">Firstnames</td>
            <td id="firstnames"></td>
        </tr>
        <tr>
            <td class="text-bold">Surname</td>
            <td id="surname"></td>
        </tr>
        <tr>
            <td class="text-bold">Date of birth</td>
            <td id="dob"></td>
        </tr>
        <tr>
            <td class="text-bold">Gender</td>
            <td id="gender"></td>
        </tr>
        <tr>
            <td class="text-bold">Employer</td>
            <td id="employer"></td>
        </tr>
    </table>
</div>



<script src="/assets/adminlte/plugins/jQuery/jquery-2.2.3.min.js"></script>
<script src="/assets/adminlte/plugins/jQueryUI/jquery-ui.min.js"></script>
<script src="/assets/adminlte/bootstrap/js/bootstrap.min.js"></script>
<script src="/assets/adminlte/dist/js/app.min.js"></script>
<script src="/common.js" type="text/javascript"></script>
<script language="JavaScript" src="/password.js"></script>
<script src="https://gitcdn.github.io/bootstrap-toggle/2.2.2/js/bootstrap-toggle.min.js"></script>

<script language="JavaScript">

    function employer_id_onchange(employer, event)
    {
        var f = employer.form;

        var employer_locations = document.getElementById('employer_location_id');

        if(employer.value != '')
        {
            employer.disabled = true;

            employer_locations.disabled = true;
            ajaxPopulateSelect(employer_locations, 'do.php?_action=ajax_load_account_manager&subaction=load_employer_locations&employer_id=' + employer.value);
            employer_locations.disabled = false;

            employer.disabled =false;
        }
        else
        {
            emptySelectElement(employer_locations);

        }
    }


    function AddLearner(form_name)
    {
        var myForm = document.forms[form_name];
        if(!validateForm(myForm))
        {
            return;
        }
        if(form_name == 'frmAddLearnerAddress' && !validatePostcode(myForm.home_postcode.value))
        {
            alert('Please enter valid postcode.');
            myForm.home_postcode.focus();
            return;
        }

        myForm.submit();
    }


    function findSimilarRecords()
    {
        var $divSimilarRecords = $('div#SimilarRecords');
        if($divSimilarRecords.length == 0)
        {
            return;
        }

        // Hide the section while we work on it
        $divSimilarRecords.hide();

        var firstnames = $('input[name="firstnames"]').val();
        var surname = $('input[name="surname"]').val();
        var dob = $('input[name="dob"]').val();

        // Don't proceed without at least the first and second name
        if(!firstnames || !surname)
        {
            return;
        }

        var url = 'do.php?_action=add_learner&subaction=findSimilarRecords'
            + "&firstnames=" + encodeURIComponent(firstnames)
            + "&surname=" + encodeURIComponent(surname)
            + "&dob=" + encodeURIComponent(dob);
        var client = ajaxRequest(url);
        var html = null;
        if (client)
        {
            var records = jQuery.parseJSON(client.responseText);
            if (records.length)
            {
                $('div.SimilarRecord', $divSimilarRecords).remove();
                var $node = null;
                for (var i = 0; i < records.length; i++)
                {
                    html = '<ul class="SimilarRecord"><li style="cursor: pointer">'
                        + htmlspecialchars(records[i].firstnames) + ' ' + htmlspecialchars(records[i].surname)
                        + '</li></ul>';
                    $node = $(html);
                    $node.data('id', records[i].id);
                    $node.data('username', records[i].username);
                    $node.data('firstnames', records[i].firstnames);
                    $node.data('surname', records[i].surname);
                    $node.data('dob', records[i].dob);
                    $node.data('gender', records[i].gender);
                    $node.data('employer', records[i].employer);
                    $node.click(function(e){
                        viewDuplicateRecord($(this));
                    });
                    $divSimilarRecords.append($node);
                }
                $divSimilarRecords.show();
            }
        }
    }

    /**
     * Opens the duplicate dialog window
     * @param $divDuplicate
     */
    function viewDuplicateRecord($divDuplicate)
    {
        var $dialog = $('#dialogDuplicate');
        $dialog.data('id', $divDuplicate.data('id'));
        $dialog.data('username', $divDuplicate.data('username'));
        $('td#firstnames', $dialog).text($divDuplicate.data('firstnames'));
        $('td#surname', $dialog).text($divDuplicate.data('surname'));
        $('td#dob', $dialog).text($divDuplicate.data('dob'));
        $('td#gender', $dialog).text($divDuplicate.data('gender'));
        $('td#employer', $dialog).text($divDuplicate.data('employer'));
        $('td#id', $dialog).text($divDuplicate.data('id'));
        $dialog.dialog("open");
    }

    // jQuery initialisation
    $(function(){


        $('#input_dob').attr('class', 'datepicker optional form-control');
	    $('#input_initial_appointment_date').attr('class', 'datepicker optional form-control');
        $('#input_l48').attr('class', 'datepicker optional form-control');

        $('input[name="firstnames"],input[name="surname"],input[name="dob"]').change(function(e){
            findSimilarRecords();
        });
        <?php if($vo->id =='') {?>
            findSimilarRecords();
        <?php } else { ?>
        $('div#SimilarRecords').hide();
        <?php } ?>

        $('#dialogDuplicate').dialog({
            modal: true,
            width: 550,
            closeOnEscape: true,
            autoOpen: false,
            resizable: true,
            draggable: true,
            buttons: {
                'View full record': function() {
                    //$(this).dialog('close');
                    window.open('do.php?_action=read_user&username='+$(this).data('username'));
                },
                'Close': function() {$(this).dialog('close');}
            }
        });

        $('input[name="home_postcode"]').change(function(e){
            if(this.value.trim() != '')
            {
                if(!validatePostcode(this.value.trim()))
                {
                    alert("Please enter a valid postcode");
                    return false;
                }
                var url = 'do.php?_action=edit_learner_duplex&subaction=validatePostcodeInLookup'
                    + "&home_postcode=" + encodeURIComponent(this.value.trim())
                var client = ajaxRequest(url);
                if(client.responseText == '0')
                {
                    $('#lblInvalidPostcode').show();
                    $('#lblValidPostcode').hide();
                }
                if(client.responseText == '1')
                {
                    $('#lblInvalidPostcode').hide();
                    $('#lblValidPostcode').show();
                }
            }
        });

    });

    function save()
    {
        // Lock the save button
        var btnSave = document.getElementById('btnSave');
	var btnSave2 = document.getElementById('btnSave2');
        btnSave.disabled = true;
	btnSave2.disabled = true;

        var myForm = document.forms["frmLearner"];

        if( !validateForm(myForm) )
        {
            btnSave.disabled = false;
		btnSave2.disabled = false;
            return false;
        }

        // First and second name validation
        var fn = myForm.elements['firstnames'];
        var sn = myForm.elements['surname'];
        var re = /^[a-zA-Z\x27\x2D ]+$/;
        if (re.test(fn.value) == false)
        {
            alert("The firstname(s) may only contain the letters a-z, spaces, hyphens and apostrophes.");
            fn.focus();
            btnSave.disabled = false;
		btnSave2.disabled = false;
            return false;
        }
        if (re.test(sn.value) == false)
        {
            alert("The surname may only contain the letters a-z, spaces, hyphens and apostrophes.");
            sn.focus();
            btnSave.disabled = false;
		btnSave2.disabled = false;
            return false;
        }

        if(myForm.home_postcode.value != '' && !validatePostcode(myForm.home_postcode.value))
        {
            alert("Please enter the valid postcode");
            btnSave.disabled = false;
		btnSave2.disabled = false;
            myForm.home_postcode.focus();
            return false;
        }

        if(myForm.home_email.value != '' && !validateEmail(myForm.home_email.value))
        {
            alert("Please enter the valid email address");
            btnSave.disabled = false;
		btnSave2.disabled = false;
            myForm.home_email.focus();
            return false;
        }

	myForm.submit();
    }


</script>

</body>
</html>