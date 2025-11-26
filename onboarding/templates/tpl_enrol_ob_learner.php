<?php /* @var $vo OnboardingLearner */ ?>
<?php /* @var $employer Organisation */ ?>
<?php /* @var $location Location */ ?>

<!DOCTYPE html>
<head xmlns="http://www.w3.org/1999/html">
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>View Onboarding Learner</title>

    <link rel="stylesheet" href="css/common.css" type="text/css"/>
    <link rel="stylesheet" href="/assets/adminlte/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="/assets/adminlte/plugins/font-awesome/font-awesome.min.css">
    <link rel="stylesheet" href="/assets/adminlte/plugins/jQueryUI/jquery-ui.css">
    <link rel="stylesheet" href="/assets/adminlte/dist/css/AdminLTE.min.css">
    <link rel="stylesheet" href="/assets/adminlte/plugins/summernote/summernote.css">
    <link rel="stylesheet" href="/assets/adminlte/plugins/summernote/summernote-bs3.css">


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
                                    <tr><th>Gender</th><td><?php echo DAO::getSingleValue($link, "SELECT description FROM lookup_gender WHERE id = '{$vo->gender}'"); ?></td></tr>
                                    <tr><th>Date of Birth</th><td><?php echo Date::toShort($vo->dob); ?><br><label class="label label-info"><?php echo Date::dateDiff(date("Y-m-d"), $vo->dob); ?></label></td></tr>
                                    <tr><th>Home Address (line 1)</th><td><?php echo $vo->home_address_line_1; ?></td></tr>
                                    <tr><th>Home Address (line 2)</th><td><?php echo $vo->home_address_line_2; ?></td></tr>
                                    <tr><th>Home Address (line 3)</th><td><?php echo $vo->home_address_line_3; ?></td></tr>
                                    <tr><th>Home Address (line 4)</th><td><?php echo $vo->home_address_line_4; ?></td></tr>
                                    <tr><th>Home Postcode</th><td><?php echo $vo->home_postcode; ?></td></tr>
                                    <tr><th>Personal Mobile</th><td><?php echo $vo->home_mobile; ?></td></tr>
                                    <tr><th>Personal Email</th><td><a href="mailto:<?php echo $vo->home_email; ?>"><?php echo $vo->home_email; ?></a></td></tr>
                                    <tr><th>Personal Telephone</th><td><?php echo $vo->home_telephone; ?></td></tr>
                                    <tr><th>Work Email</th><td><a href="mailto:<?php echo $vo->work_email; ?>"><?php echo $vo->work_email; ?></a></td></tr>
                                    <tr><th>Ethnicity</th><td><?php echo $vo->ethnicity == '' ? '' : LookupHelper::getEthnicitiesList($vo->ethnicity); ?></td></tr>
                                    <tr><th>ULN (Unique Learner Number)</th><td><?php echo $vo->uln; ?></td></tr>
                                    <tr><th>National Insurance</th><td><?php echo $vo->ni; ?></td></tr>
                                    <tr><th>BKSB Username</th><td><?php echo $vo->bksb_username; ?></td></tr>
                                    <?php if(DB_NAME == "am_ela"){ ?>
                                    <tr><th>DAS Admin</th><td><?php echo $vo->das_admin; ?></td></tr>
                                    <tr><th>DAS Cohort No.</th><td><?php echo $vo->das_cohort_no; ?></td></tr>
                                    <?php } ?>
                                </table>
                                <hr>
                                <table class="table text-info">
                                    <tr><th>Created at</th><td><?php echo Date::to($vo->created, Date::DATETIME); ?></td></tr>
                                    <tr><th>Created by</th><td><?php echo $vo->getCreatorName($link); ?></td></tr>
                                    <tr><th>Last updated at</th><td><?php echo Date::to($vo->updated, Date::DATETIME); ?></td></tr>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
        <div class="col-sm-8">
            <div class="row">
                <div class="col-sm-12">
                    <div class="callout callout-info">
                        Use this form to enrol the learner on the Programme.
                    </div>
                    <form method="post" role="form" class="form-horizontal" name="frmEnrolment" action="<?php echo $_SERVER['PHP_SELF']; ?>?_action=save_enrol_ob_learner">
                        <input type="hidden" name="_action" value="save_enrol_ob_learner" />
                        <input type="hidden" name="ob_learner_id" value="<?php echo $vo->id; ?>" />

                        <div class="callout callout-default">
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
                                <div class="col-sm-6">
                                    <?php echo HTML::selectChosen('framework_id', $ddlFrameworks, '', true, true); ?>
                                </div>
                                <div class="col-sm-1"><span class="btn btn-info btn-xs" onclick="showFrameworkInfo();"><i class="fa fa-info-circle"></i></span></div>
                            </div>
                            <div class="form-group">
                                <label for="epa_organisation" class="col-sm-5 control-label fieldLabel_compulsory">Epa Organisation:</label>
                                <div class="col-sm-7">
                                    <?php echo HTML::selectChosen('epa_organisation', $ddlEpaOrgs, '', true, true); ?>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="trainers" class="col-sm-5 control-label fieldLabel_optional">Assessor:</label>
                                <div class="col-sm-7"><?php echo HTML::selectChosen('trainers', [], '', true, false, true); ?></div>
                            </div>
                        </div>
                        <div class="callout callout-default">

                            <div class="form-group">
                                <label for="job_title" class="col-sm-5 control-label fieldLabel_optional ">Job Title:</label>
                                <div class="col-sm-7"><input type="text" class="form-control" name="job_title" id="job_title" maxlength="150" /></div>
                            </div>
                            <div class="form-group">
                                <label for="contracted_hours_per_week" class="col-sm-5 control-label fieldLabel_compulsory">Contracted Hours per Week:</label>
                                <div class="col-sm-7">
                                    <input type="text" class="form-control compulsory" name="contracted_hours_per_week" id="contracted_hours_per_week" onkeypress="return numbersonlywithpoint();" onfocusout="adjustDuration(this);" maxlength="4" />
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="weeks_to_be_worked_per_year" class="col-sm-5 control-label fieldLabel_compulsory">Weeks to be worked per year:</label>
                                <div class="col-sm-7">
                                    <input class="form-control compulsory" type="text" name="weeks_to_be_worked_per_year" id="weeks_to_be_worked_per_year" maxlength="4" onkeypress="return numbersonlywithpoint();" value="<?php echo true ? 46.4 : ''; ?>" />
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="epa_price" class="col-sm-5 control-label fieldLabel_compulsory">EPA Price:</label>
                                <div class="col-sm-7">
                                    <input type="text" class="form-control compulsory" name="epa_price" id="epa_price" onkeypress="return numbersonly();" />
                                </div>
                            </div>
                            <div class="well well-sm">
                                <div class="form-group">
                                    <label for="practical_period_start_date" class="col-sm-5 control-label fieldLabel_compulsory">Practical Period Start Date:</label>
                                    <div class="col-sm-7"><?php echo HTML::datebox('practical_period_start_date', '', true); ?></div>
                                </div>
                                <div class="form-group">
                                    <label for="duration_practical_period" class="col-sm-5 control-label fieldLabel_compulsory">Practical Period Duration (months):</label>
                                    <div class="col-sm-7">
                                        <input class="form-control compulsory" type="text" name="duration_practical_period" id="duration_practical_period" onkeypress="return numbersonly();" maxlength="3" />
                                        months
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="practical_period_end_date" class="col-sm-5 control-label fieldLabel_compulsory">Practical Period End Date:</label>
                                    <div class="col-sm-7"><?php echo HTML::datebox('practical_period_end_date', '', true); ?></div>
                                </div>
                            </div>
                            <div class="well well-sm">
                                <div class="form-group">
                                    <label for="apprenticeship_start_date" class="col-sm-5 control-label fieldLabel_compulsory">Apprenticeship Start Date:</label>
                                    <div class="col-sm-7">
                                        <?php //echo HTML::datebox('apprenticeship_start_date', '', true); ?>
                                        <input class="form-control compulsory" type="text" id="input_apprenticeship_start_date" name="apprenticeship_start_date" 
                                        value="" size="10" maxlength="10" placeholder="dd/mm/yyyy" onfocus="apprenticeship_start_date_onfocus(this);">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="apprenticeship_duration_inc_epa" class="col-sm-5 control-label fieldLabel_compulsory">Apprenticeship Duration (including EPA):</label>
                                    <div class="col-sm-7">
                                        <input class="form-control compulsory" type="text" name="apprenticeship_duration_inc_epa" id="apprenticeship_duration_inc_epa" onkeypress="return numbersonly();" maxlength="3" />
                                        months
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="apprenticeship_end_date_inc_epa" class="col-sm-5 control-label fieldLabel_compulsory">Apprenticeship End Date (including EPA):</label>
                                    <div class="col-sm-7"><?php echo HTML::datebox('apprenticeship_end_date_inc_epa', '', true); ?></div>
                                </div>
                            </div>
                            <?php if($employer->funding_type == "LG") { ?>
                            <div class="form-group">
                                <label for="levy_gifted" class="col-sm-5 control-label fieldLabel_optional">Levy Gifted:</label>
                                <div class="col-sm-7"><?php echo HTML::selectChosen('levy_gifted', ['Yes', 'No'], '', true); ?></div>
                            </div>
                            <?php } ?>
                        </div>

                    </form>

                </div>
            </div>
            <div class="row">
                <div class="col-sm-12">
                    <span class="btn btn-block btn-primary" style="margin-bottom: 15px;" onclick="enrol();"><i class="fa fa-save"></i> Save Enrolment</span>
                </div>
            </div>
        </div>
    </div>

    <script src="/assets/adminlte/plugins/jQueryUI/jquery-ui.min.js"></script>
    <script src="/assets/adminlte/bootstrap/js/bootstrap.min.js"></script>
    <script src="/assets/adminlte/dist/js/app.min.js"></script>
    <script src="js/common.js" type="text/javascript"></script>
    <script src="/assets/adminlte/plugins/summernote/summernote.min.js"></script>

    <script>

        $(function () {

            $('#frmEmailBody').summernote({
                toolbar:[
                    ['style', ['bold', 'italic', 'underline', 'clear']],
                    ['fontsize', ['fontsize']],
                    ['para', ['ul', 'ol', 'paragraph']],
                    ['height', ['height']],
                    ['insert', ['link', 'picture', 'hr']]
                ],
                height:300,
                callbacks:{
                    onImageUpload:function (files, editor, welEditable) {
                        sendFile(files[0], editor, welEditable);
                    }
                }
            });

            $('.datepicker').attr('class', 'form-control compulsory');
        });

        function showFrameworkInfo()
        {
            var framework_id = $('#framework_id').val();
            if(framework_id == '')
            {
                alert('Please select the standard to see its information.');
                return;
            }

            var postData = 'do.php?_action=ajax_helper'
                + '&subaction=getStandardInfo'
                + '&framework_id=' + encodeURIComponent(framework_id)
            ;

            var req = ajaxRequest(postData);
            $("<div></div>").html(req.responseText).dialog({
                id: "dlg_info",
                title: "Standard Information",
                resizable: false,
                modal: true,
                width: 450,
                height: 350,

                buttons: {
                    'Close': function() {$(this).dialog('close');}
                }
            });
        }

        function framework_id_onchange(framework, event)
        {
            var f = framework.form;

            var epa_org = document.getElementById('epa_organisation');
            var epa_price = document.getElementById('epa_price');
            var duration_practical_period = document.getElementById('duration_practical_period');
            var apprenticeship_duration_inc_epa = document.getElementById('apprenticeship_duration_inc_epa');

            epa_org.value = '';
            epa_price.value = '';
            duration_practical_period.value = '';
            apprenticeship_duration_inc_epa.value = '';

            var postData = 'do.php?_action=ajax_helper'
                + '&subaction=getStandardEpaAndPrice'
                + '&framework_id=' + encodeURIComponent(framework.value)
            ;

            var req = ajaxRequest(postData);
            if(req)
            {
                var res = $.parseJSON(req.responseText);
                epa_org.value = res.epa_org;
                epa_price.value = res.epa_price;
                duration_practical_period.value = res.duration_in_months;
                apprenticeship_duration_inc_epa.value = parseInt(res.duration_in_months) + parseInt(res.epa_duration);
            }

        }

        function training_provider_location_id_onchange(provider_location, event)
        {
            var f = provider_location.form;

            var trainers = document.getElementById('trainers');

            if(provider_location.value != '')
            {
                provider_location.disabled = true;

                trainers.disabled = true;
                ajaxPopulateSelect(trainers, 'do.php?_action=ajax_load_account_manager&subaction=load_provider_trainers&provider_location_id=' + provider_location.value);
                trainers.disabled = false;

                provider_location.disabled =false;
                $('#trainers').trigger("chosen:updated");
            }
            else
            {
                emptySelectElement(trainers);

            }
        }

        function employer_id_onchange(employer, event)
        {
            var f = employer.form;

            var employer_locations = document.getElementById('employer_location_id');
	    var line_managers = document.getElementById('line_manager_id');	

            if(employer.value != '')
            {
                employer.disabled = true;

                employer_locations.disabled = true;
                ajaxPopulateSelect(employer_locations, 'do.php?_action=ajax_load_account_manager&subaction=load_employer_locations&employer_id=' + employer.value);
                employer_locations.disabled = false;

		line_managers.disabled = true;
                ajaxPopulateSelect(line_managers, 'do.php?_action=ajax_load_account_manager&subaction=load_employer_contacts&employer_id=' + employer.value);
                line_managers.disabled = false;

                employer.disabled =false;
            }
            else
            {
                emptySelectElement(employer_locations);
		emptySelectElement(line_managers);

            }
        }

        function enrol()
        {
            // Lock the save button
            var btnSave = document.getElementById('btnSave');
            btnSave.disabled = true;

            var myForm = document.forms["frmEnrolment"];

            var practical_period_end_date = stringToDate(myForm.practical_period_end_date.value);
            var practical_period_start_date = stringToDate(myForm.practical_period_start_date.value);

            var maxStartDate = new Date('2025-07-31');
            if (practical_period_start_date > maxStartDate) {
                alert("Practical period start date should be less than or equal to 31/07/2025");
                myForm.practical_period_start_date.focus();
                return;
            }

            if ((practical_period_end_date) <= (practical_period_start_date))
            {
                alert("Practical period end date should be greater than practical period start date");
                myForm.practical_period_end_date.focus();
                return;
            }

            var apprenticeship_start_date = stringToDate(myForm.apprenticeship_start_date.value);
            var apprenticeship_end_date_inc_epa = stringToDate(myForm.apprenticeship_end_date_inc_epa.value);
            if ((apprenticeship_end_date_inc_epa) <= (apprenticeship_start_date))
            {
                alert("Apprenticeship end date should be greater than apprenticeship start date");
                myForm.apprenticeship_end_date_inc_epa.focus();
                return;
            }

            if(apprenticeship_start_date < practical_period_start_date)
            {
                alert("Apprenticeship start date cannot be before the practical period start date.");
                myForm.apprenticeship_start_date.focus();
                return;
            }

            if(apprenticeship_end_date_inc_epa < practical_period_end_date)
            {
                alert("Apprenticeship end date cannot be before the practical period end date.");
                myForm.apprenticeship_end_date_inc_epa.focus();
                return;
            }

            // var duration_practical_period = stringToDate(myForm.duration_practical_period.value);
            // var duration_of_practical_dates = monthDiff(practical_period_start_date, practical_period_end_date);
            // if(duration_of_practical_dates != duration_practical_period)
            // {
            //     alert("Practical Period End Date is not valid according to the Practical Period Duration.");
            //     myForm.practical_period_end_date.focus();
            //     return;
            // }

            if( !validateForm(myForm) )
            {
                btnSave.disabled = false;
                return false;
            }

            myForm.submit();
        }

        function monthDiff(d1, d2)
        {
            var months;
            months = (d2.getFullYear() - d1.getFullYear()) * 12;
            months -= d1.getMonth();
            months += d2.getMonth();
            return months <= 0 ? 0 : months;
        }

        function apprenticeship_start_date_onfocus(apprenticeship_start_date)
        {
            if(apprenticeship_start_date.value == '')
            {
                apprenticeship_start_date.value = apprenticeship_start_date.form.elements['practical_period_start_date'].value;
            }
        }

	$(function(){
            $("input[name=practical_period_start_date]").on('change', function(){

                $("input[name=apprenticeship_start_date]").val($(this).val());

                var practical_period_start_date = $(this).val();
                var practical_duration = $('#duration_practical_period').val();
                var app_duration = $('#apprenticeship_duration_inc_epa').val();
                var qs = 'practical_period_start_date='+encodeURIComponent(practical_period_start_date)+'&practical_duration='+encodeURIComponent(practical_duration)+'&app_duration='+encodeURIComponent(app_duration);
                var req = ajaxRequest('do.php?_action=ajax_helper&subaction=calculate_end_date_from_duration&'+qs);
                if(req)
                {
                    if(req.responseText != '')
                    {
                        var res = $.parseJSON(req.responseText);
                        $("input[name=practical_period_end_date]").val(res.practical_period_end_date);
                        $("input[name=apprenticeship_end_date_inc_epa]").val(res.apprenticeship_end_date_inc_epa);
                    }
                }
            });
        });

	function adjustDuration(ele)
        {
            var contracted_hours_per_week = ele.value;
            if(parseFloat(contracted_hours_per_week) >= 30)
            {
                return;
            }

            var postData = 'do.php?_action=ajax_helper'
                + '&subaction=getStandardEpaAndPrice'
                + '&framework_id=' + encodeURIComponent($("select[name=framework_id]").val())
            ;

            var req = ajaxRequest(postData);
            if(req)
            {
                var res = $.parseJSON(req.responseText);
                if(res.duration_in_months !== '')
                {
                    var duration_practical_period = document.getElementById('duration_practical_period');
                    var apprenticeship_duration_inc_epa = document.getElementById('apprenticeship_duration_inc_epa');

                    var recommended_duration = parseInt(res.duration_in_months);
                    var minimum_duration_part_time = Math.ceil( ( recommended_duration * 30 )/parseFloat(contracted_hours_per_week) );

                    duration_practical_period.value = minimum_duration_part_time;
                    apprenticeship_duration_inc_epa.value = minimum_duration_part_time + parseInt(res.epa_duration);
                }
            }
        }

    </script>
</body>
</html>
