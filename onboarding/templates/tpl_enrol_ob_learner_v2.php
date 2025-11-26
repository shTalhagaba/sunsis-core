<?php /* @var $vo OnboardingLearner */ ?>
<?php /* @var $employer Organisation */ ?>
<?php /* @var $location Location */ ?>

<!DOCTYPE html>

<head xmlns="http://www.w3.org/1999/html">
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>View Onboarding Learner</title>

    <link rel="stylesheet" href="css/common.css" type="text/css" />
    <link rel="stylesheet" href="/assets/adminlte/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="/assets/adminlte/plugins/font-awesome/font-awesome.min.css">
    <link rel="stylesheet" href="/assets/adminlte/plugins/jQueryUI/jquery-ui.css">
    <link rel="stylesheet" href="/assets/adminlte/dist/css/AdminLTE.min.css">


    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->

    <style type="text/css">
        .ui-datepicker .ui-datepicker-title select {
            color: #000;
        }
    </style>

    <script src="/assets/adminlte/plugins/jQuery/jquery-2.2.3.min.js"></script>

</head>

<body>
    <div class="row">
        <div class="col-lg-12">
            <div class="banner">
                <div class="Title" style="margin-left: 6px;">View Onboarding Learner
                    [<?php echo $vo->firstnames . ' ' . $vo->surname; ?>]
                </div>
                <div class="ButtonBar">
                    <span class="btn btn-xs btn-default"
                        onclick="window.location.href='<?php echo $_SESSION['bc']->getPrevious(); ?>';"><i
                            class="fa fa-arrow-circle-o-left"></i> Cancel</span>
                    <span class="btn btn-xs btn-default" id="btnSave" onclick="enrol();"><i class="fa fa-save"></i> Save Enrolment</span>
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

    <div class="content-wrapper">

        <div class="row">
            <div class="col-sm-4">
                <div class="row">
                    <div class="col-sm-12">
                        <div class="box box-solid box-success">
                            <div class="box-header"><span class="box-title with-header"><span
                                        class="lead text-bold"><?php echo htmlspecialchars($vo->firstnames) . ' ' . htmlspecialchars(strtoupper($vo->surname)); ?></span></span>
                            </div>
                            <div class="box-body">
                                <div class="table-responsive">
                                    <table class="table">
                                        <tr>
                                            <th>Gender</th>
                                            <td><?php echo DAO::getSingleValue($link, "SELECT description FROM lookup_gender WHERE id = '{$vo->gender}'"); ?></td>
                                        </tr>
                                        <tr>
                                            <th>Date of Birth</th>
                                            <td><?php echo Date::toShort($vo->dob); ?><br><label class="label label-info"><?php echo Date::dateDiff(date("Y-m-d"), $vo->dob); ?></label></td>
                                        </tr>
                                        <tr>
                                            <th>Home Address (line 1)</th>
                                            <td><?php echo $vo->home_address_line_1; ?></td>
                                        </tr>
                                        <tr>
                                            <th>Home Address (line 2)</th>
                                            <td><?php echo $vo->home_address_line_2; ?></td>
                                        </tr>
                                        <tr>
                                            <th>Home Address (line 3)</th>
                                            <td><?php echo $vo->home_address_line_3; ?></td>
                                        </tr>
                                        <tr>
                                            <th>Home Address (line 4)</th>
                                            <td><?php echo $vo->home_address_line_4; ?></td>
                                        </tr>
                                        <tr>
                                            <th>Home Postcode</th>
                                            <td><?php echo $vo->home_postcode; ?></td>
                                        </tr>
                                        <tr>
                                            <th>Personal Mobile</th>
                                            <td><?php echo $vo->home_mobile; ?></td>
                                        </tr>
                                        <tr>
                                            <th>Personal Email</th>
                                            <td><a href="mailto:<?php echo $vo->home_email; ?>"><?php echo $vo->home_email; ?></a></td>
                                        </tr>
                                        <tr>
                                            <th>Personal Telephone</th>
                                            <td><?php echo $vo->home_telephone; ?></td>
                                        </tr>
                                        <tr>
                                            <th>Work Email</th>
                                            <td><a href="mailto:<?php echo $vo->work_email; ?>"><?php echo $vo->work_email; ?></a></td>
                                        </tr>
                                        <tr>
                                            <th>Ethnicity</th>
                                            <td><?php echo $vo->ethnicity == '' ? '' : LookupHelper::getEthnicitiesList($vo->ethnicity); ?></td>
                                        </tr>
                                        <tr>
                                            <th>ULN (Unique Learner Number)</th>
                                            <td><?php echo $vo->uln; ?></td>
                                        </tr>
                                        <tr>
                                            <th>National Insurance</th>
                                            <td><?php echo $vo->ni; ?></td>
                                        </tr>
                                        <tr>
                                            <th>BKSB Username</th>
                                            <td><?php echo $vo->bksb_username; ?></td>
                                        </tr>
                                        <?php if (DB_NAME == "am_ela") { ?>
                                            <tr>
                                                <th>DAS Admin</th>
                                                <td><?php echo $vo->das_admin; ?></td>
                                            </tr>
                                            <tr>
                                                <th>DAS Cohort No.</th>
                                                <td><?php echo $vo->das_cohort_no; ?></td>
                                            </tr>
                                        <?php } ?>
                                    </table>
                                    <hr>
                                    <table class="table text-info">
                                        <tr>
                                            <th>Created at</th>
                                            <td><?php echo Date::to($vo->created, Date::DATETIME); ?></td>
                                        </tr>
                                        <tr>
                                            <th>Created by</th>
                                            <td><?php echo $vo->getCreatorName($link); ?></td>
                                        </tr>
                                        <tr>
                                            <th>Last updated at</th>
                                            <td><?php echo Date::to($vo->updated, Date::DATETIME); ?></td>
                                        </tr>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
            <div class="col-sm-8">
                <form method="post" role="form" class="form-horizontal" name="frmEnrolment" action="<?php echo $_SERVER['PHP_SELF']; ?>?_action=save_enrol_ob_learner">
                    <input type="hidden" name="_action" value="save_enrol_ob_learner" />
                    <input type="hidden" name="ob_learner_id" value="<?php echo $vo->id; ?>" />

                    <div class="form-group">
                        <label for="training_provider" class="col-sm-5 control-label fieldLabel_compulsory">Training Provider:</label>
                        <div class="col-sm-7"><?php echo HTML::selectChosen('training_provider_location_id', $ddlTrainingProvidersLocations, '', true, true); ?></div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-5 control-label fieldLabel_compulsory">Employer:</label>
                        <div class="col-sm-7">
                            <?php echo HTML::selectChosen('employer_id', DAO::getResultset($link, "SELECT id, legal_name, null FROM organisations WHERE organisation_type = '" . Organisation::TYPE_EMPLOYER . "' AND active = 1 ORDER BY legal_name"), $vo->employer_id, true, true); ?>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="employer_location_id" class="col-sm-5 control-label fieldLabel_compulsory">Employer Location:</label>
                        <div class="col-sm-7">
                            <?php
                            echo $vo->employer_id != '' ?
                                HTML::selectChosen('employer_location_id', DAO::getResultset($link, "SELECT locations.id, CONCAT(COALESCE(locations.`full_name`), ' (',COALESCE(`address_line_1`,''),', ',COALESCE(`postcode`,''), ')') AS detail, null FROM locations WHERE locations.organisations_id = '$vo->employer_id' ORDER BY full_name ;"), $vo->employer_location_id, false, true) :
                                HTML::selectChosen('employer_location_id', [], '', false, true);
                            ?>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="line_manager_id" class="col-sm-5 control-label fieldLabel_optional">Line Manager/Supervisor:</label>
                        <div class="col-sm-7">
                            <?php
                            echo $vo->employer_id != '' ?
                                HTML::selectChosen('line_manager_id', DAO::getResultset($link, "SELECT contact_id, contact_name, null FROM organisation_contacts WHERE org_id = '$vo->employer_id'  AND job_role IN ('2', '28') ORDER BY contact_name ;"), $vo->line_manager_id, true) :
                                HTML::selectChosen('line_manager_id', [], '', true);
                            ?>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="subcontractor" class="col-sm-5 control-label fieldLabel_optional">Subcontractor:</label>
                        <div class="col-sm-7"><?php echo HTML::selectChosen('subcontractor_location_id', $ddlSubcontractorsLocations, '', false, false); ?></div>
                    </div>
                    <div class="form-group">
                        <label for="framework_id" class="col-sm-5 control-label fieldLabel_compulsory">Programme:</label>
                        <div class="col-sm-7">
                            <?php echo HTML::selectChosen('framework_id', $ddlFrameworks, '', true, true); ?>
                            <span class="text-info" id="spanOtjHours"></span>
                            <input type="hidden" name="otj_hours" id="otj_hours" />
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="epa_organisation" class="col-sm-5 control-label fieldLabel_compulsory">Assessment Organisation:</label>
                        <div class="col-sm-7">
                            <?php echo HTML::selectChosen('epa_organisation', $ddlEpaOrgs, '', true, true); ?>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="trainers" class="col-sm-5 control-label fieldLabel_optional">Assessor:</label>
                        <div class="col-sm-7"><?php echo HTML::selectChosen('trainers', [], '', true, false, true); ?></div>
                    </div>

                    <div class="form-group">
                        <label for="job_title" class="col-sm-5 control-label fieldLabel_optional ">Job Title:</label>
                        <div class="col-sm-7"><input type="text" class="form-control" name="job_title" id="job_title" maxlength="150" /></div>
                    </div>
                    <div class="form-group">
                        <label for="otj_duration_pw" class="col-sm-5 control-label fieldLabel_compulsory">Off the Job Hours per week:</label>
                        <div class="col-sm-7">
                            <?php
                            echo HTML::selectChosen('otj_duration_pw', OnboardingHelper::otjPerWeekDdl(), '', true, true);
                            ?>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="duration_practical_period" class="col-sm-5 control-label fieldLabel_compulsory">Duration Practical Period:</label>
                        <div class="col-sm-7">
                            <?php
                            echo HTML::selectChosen('duration_practical_period', [], '', true, true);
                            ?>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="contracted_hours_per_week" class="col-sm-5 control-label fieldLabel_compulsory">Contracted Hours per Week:</label>
                        <div class="col-sm-7">
                            <input type="text" class="form-control compulsory" name="contracted_hours_per_week" id="contracted_hours_per_week" onkeypress="return numbersonlywithpoint();" maxlength="4" />
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="weeks_to_be_worked_per_year" class="col-sm-5 control-label fieldLabel_compulsory">Weeks to be worked per year:</label>
                        <div class="col-sm-7">
                            <input class="form-control compulsory" type="text" name="weeks_to_be_worked_per_year" id="weeks_to_be_worked_per_year" maxlength="4" onkeypress="return numbersonlywithpoint();" value="<?php echo true ? 46.4 : ''; ?>" />
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="practical_period_start_date" class="col-sm-5 control-label fieldLabel_compulsory">Practical Period Start Date:</label>
                        <div class="col-sm-7"><?php echo HTML::datebox('practical_period_start_date', '', true); ?></div>
                    </div>
                    <div class="form-group">
                        <label for="practical_period_end_date" class="col-sm-5 control-label fieldLabel_compulsory">Practical Period End Date:</label>
                        <div class="col-sm-7"><?php echo HTML::datebox('practical_period_end_date', '', true); ?></div>
                    </div>                    
                </form>
                <p><span class="btn btn-block btn-primary" style="margin-bottom: 15px;" onclick="enrol();"><i class="fa fa-save"></i> Save Enrolment</span></p>
            </div>
        </div>

        <script src="/assets/adminlte/plugins/jQueryUI/jquery-ui.min.js"></script>
        <script src="/assets/adminlte/bootstrap/js/bootstrap.min.js"></script>
        <script src="/assets/adminlte/dist/js/app.min.js"></script>
        <script src="js/common.js" type="text/javascript"></script>

        <script>
            $(function() {

                $('.datepicker').attr('class', 'form-control compulsory');
            });

            var lookupOtjDurations = {hpw_6: null, hpw_7p5: null, hpw_9: null, hpw_10p5: null, hpw_12: null, hpw_13p5: null, hpw_15: null, otj_hours: null};

            function framework_id_onchange(framework, event) {
                var f = framework.form;

                var epa_org = document.getElementById('epa_organisation');
                // var epa_price = document.getElementById('epa_price');
                var apprenticeship_duration_inc_epa = document.getElementById('apprenticeship_duration_inc_epa');
                var duration_practical_period = document.getElementById('duration_practical_period');
                epa_org.value = '';
                // epa_price.value = '';

                var postData = 'do.php?_action=ajax_helper' +
                    '&subaction=getStandardEpaAndPrice' +
                    '&framework_id=' + encodeURIComponent(framework.value);

                var req = ajaxRequest(postData);
                if (req) {
                    var res = $.parseJSON(req.responseText);
                    epa_org.value = res.epa_org;
                    // epa_price.value = res.epa_price;
                }

                if (framework.value != '') {
                    framework.disabled = true;

                    var client = ajaxRequest('do.php?_action=ajax_helper&subaction=get_lookup_otj_durations&framework_id=' + encodeURIComponent(framework.value));
                    if (client) {
                        var res = $.parseJSON(client.responseText);
                        if( res == null || res == undefined) {
                            alert('Error: Unable to load Off the Job Hours');
                        } else if (res.error) {
                            alert(res.error);
                        } else if (res.otj_hours == null || res.otj_hours == undefined) {
                            alert('Error: Off the Job Hours not found for this programme');
                        } else {
                            lookupOtjDurations = res;
                            resetOtjAndDuration();
                            $("#spanOtjHours").html('Off the Job Hours: ' + (lookupOtjDurations.otj_hours || ''));
                            $("#otj_hours").val((lookupOtjDurations.otj_hours || ''));
                        }
                    }                    
                } else {
                    emptySelectElement(duration_practical_period);
                }
                framework.disabled = false;
            }

            function resetOtjAndDuration() {
                var otj_duration_pw = document.getElementById('otj_duration_pw');
                var duration_practical_period = document.getElementById('duration_practical_period');

                otj_duration_pw.value = '';
                emptySelectElement(duration_practical_period);

                otj_duration_pw.disabled = true;
                duration_practical_period.disabled = true;

                if (Object.keys(lookupOtjDurations).length > 0) {
                    duration_practical_period.options[0] = new Option('', '');
                    if (lookupOtjDurations.hpw_6 != null)
                        duration_practical_period.options[1] = new Option(lookupOtjDurations.hpw_6 + ' months', lookupOtjDurations.hpw_6);
                    if (lookupOtjDurations.hpw_7p5 != null)
                        duration_practical_period.options[2] = new Option(lookupOtjDurations.hpw_7p5 + ' months', lookupOtjDurations.hpw_7p5);
                    if (lookupOtjDurations.hpw_9 != null)
                        duration_practical_period.options[3] = new Option(lookupOtjDurations.hpw_9 + ' months', lookupOtjDurations.hpw_9);
                    if (lookupOtjDurations.hpw_10p5 != null)
                        duration_practical_period.options[4] = new Option(lookupOtjDurations.hpw_10p5 + ' months', lookupOtjDurations.hpw_10p5);
                    if (lookupOtjDurations.hpw_12 != null)
                        duration_practical_period.options[5] = new Option(lookupOtjDurations.hpw_12 + ' months', lookupOtjDurations.hpw_12);
                    if (lookupOtjDurations.hpw_13p5 != null)
                        duration_practical_period.options[6] = new Option(lookupOtjDurations.hpw_13p5 + ' months', lookupOtjDurations.hpw_13p5);
                    if (lookupOtjDurations.hpw_15 != null)
                        duration_practical_period.options[7] = new Option(lookupOtjDurations.hpw_15 + ' months', lookupOtjDurations.hpw_15);

                    otj_duration_pw.disabled = false;
                    duration_practical_period.disabled = false;
                } else {
                    otj_duration_pw.disabled = true;
                    duration_practical_period.disabled = true;
                }
            }

            function training_provider_location_id_onchange(provider_location, event) {
                var f = provider_location.form;

                var trainers = document.getElementById('trainers');

                if (provider_location.value != '') {
                    provider_location.disabled = true;

                    trainers.disabled = true;
                    ajaxPopulateSelect(trainers, 'do.php?_action=ajax_load_account_manager&subaction=load_provider_trainers&provider_location_id=' + provider_location.value);
                    trainers.disabled = false;

                    provider_location.disabled = false;
                    $('#trainers').trigger("chosen:updated");
                } else {
                    emptySelectElement(trainers);

                }
            }

            function employer_id_onchange(employer, event) {
                var f = employer.form;

                var employer_locations = document.getElementById('employer_location_id');
                var line_managers = document.getElementById('line_manager_id');

                if (employer.value != '') {
                    employer.disabled = true;

                    employer_locations.disabled = true;
                    ajaxPopulateSelect(employer_locations, 'do.php?_action=ajax_load_account_manager&subaction=load_employer_locations&employer_id=' + employer.value);
                    employer_locations.disabled = false;

                    line_managers.disabled = true;
                    ajaxPopulateSelect(line_managers, 'do.php?_action=ajax_load_account_manager&subaction=load_employer_contacts&employer_id=' + employer.value);
                    line_managers.disabled = false;

                    employer.disabled = false;
                } else {
                    emptySelectElement(employer_locations);
                    emptySelectElement(line_managers);

                }
            }

            function otj_duration_pw_onchange(otj_duration_pw, event) {
                var duration_practical_period = document.getElementById('duration_practical_period');
                duration_practical_period.disabled = true;
                duration_practical_period.value = (lookupOtjDurations[otj_duration_pw.value] || '');
                duration_practical_period.disabled = false;
            }

            function duration_practical_period_onchange(duration_practical_period, event) {
                var otj_duration_pw = document.getElementById('otj_duration_pw');
                otj_duration_pw.disabled = true;
                var otjKey = Object.keys(lookupOtjDurations).find(key => lookupOtjDurations[key] == duration_practical_period.value);
                otj_duration_pw.value = otjKey || '';
                otj_duration_pw.disabled = false;
            }

            function enrol() {
                // Lock the save button
                var btnSave = document.getElementById('btnSave');
                btnSave.disabled = true;

                var myForm = document.forms["frmEnrolment"];

                var practical_period_end_date = stringToDate(myForm.practical_period_end_date.value);
                var practical_period_start_date = stringToDate(myForm.practical_period_start_date.value);
                if ((practical_period_end_date) <= (practical_period_start_date)) {
                    alert("Practical period end date should be greater than practical period start date");
                    myForm.practical_period_end_date.focus();
                    return;
                }

                var minStartDate = new Date('2025-07-31');
                if (practical_period_start_date < minStartDate) {
                    alert("Practical period start date should be greater than or equal to 01/08/2025");
                    myForm.practical_period_start_date.focus();
                    return;
                }

                if (!validateForm(myForm)) {
                    btnSave.disabled = false;
                    return false;
                }

                myForm.submit();
            }

            function monthDiff(d1, d2) {
                var months;
                months = (d2.getFullYear() - d1.getFullYear()) * 12;
                months -= d1.getMonth();
                months += d2.getMonth();
                return months <= 0 ? 0 : months;
            }

            function apprenticeship_start_date_onfocus(apprenticeship_start_date) {
                if (apprenticeship_start_date.value == '') {
                    apprenticeship_start_date.value = apprenticeship_start_date.form.elements['practical_period_start_date'].value;
                }
            }

            $(function() {
                $("input[name=practical_period_start_date]").on('change', function() {

                    $("input[name=apprenticeship_start_date]").val($(this).val());

                    var practical_period_start_date = $(this).val();
                    var practical_duration = $('#duration_practical_period').val();
                    var app_duration = $('#apprenticeship_duration_inc_epa').val();
                    var qs = 'practical_period_start_date=' + encodeURIComponent(practical_period_start_date) + '&practical_duration=' + encodeURIComponent(practical_duration) + '&app_duration=' + encodeURIComponent(app_duration);
                    var req = ajaxRequest('do.php?_action=ajax_helper&subaction=calculate_end_date_from_duration&' + qs);
                    if (req) {
                        if (req.responseText != '') {
                            var res = $.parseJSON(req.responseText);
                            $("input[name=practical_period_end_date]").val(res.practical_period_end_date);
                            $("input[name=apprenticeship_end_date_inc_epa]").val(res.apprenticeship_end_date_inc_epa);
                        }
                    }
                });
            });
        </script>
</body>

</html>